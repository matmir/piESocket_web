<?php

namespace App\Service\Admin;

use Doctrine\DBAL\Driver\Connection;
use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

use Symfony\Component\Config\Definition\Exception\Exception;

use App\Entity\Admin\Tag;
use App\Entity\Admin\TagArea;
use App\Entity\Paginator;
use App\Entity\AppException;
use App\Service\Admin\ConfigDriverMapper;

/**
 * Class to read/write Tags
 *
 * @author Mateusz Mirosławski
 */
class TagsMapper {
    
    private $dbConn;
    
    public function __construct(Connection $connection) {
        
        $this->dbConn = $connection;
    }
    
    /**
     * Get Tags
     * 
     * @param int $area Tag area (0 - all, 1 - input, 2 - output, 3 - memory)
     * @param int $sort Tag sorting (0 - ID, 1 - tag name, 2 - start address, 3 - tag type)
     * @param int $sortDESC Sorting direction (0 - ASC, 1 - DESC)
     * @param Paginator $paginator Paginator object
     * @return array Array with Tags
     */
    public function getTags(int $area = 0, int $sort = 0, int $sortDESC = 0, Paginator $paginator = null) {
        
        // Basic query
        $sql = 'SELECT * FROM tags';
        
        // Area
        if ($area > 0) {
            $sql .= ' WHERE tArea = ?';
        }
        
        // Order direction
        $oDirection = ($sortDESC==1)?('DESC'):('ASC');
        
        // Order
        switch ($sort) {
            case 0: $sql .= ' ORDER BY tid '.$oDirection; break;
            case 1: $sql .= ' ORDER BY tName '.$oDirection; break;
            case 2: $sql .= ' ORDER BY tByteAddress '.$oDirection.', tBitAddress '.$oDirection; break;
            case 3: $sql .= ' ORDER BY tType '.$oDirection; break;
            default: $sql .= ' ORDER BY tid '.$oDirection;
        }
        
        // Check paginator
        if (!is_null($paginator)) {
            $sql .= " ".$paginator->getSqlQuery();
        }
        
        // End query
        $sql .= ';';
        
        $statement = $this->dbConn->prepare($sql);
        
        if ($area > 0) {
            $statement->bindValue(1, $area, ParameterType::INTEGER);
        }
        
        $statement->execute();
        $items = $statement->fetchAll();
        
        $ret = array();
        
        foreach($items as $item) {
            
            // New tag
            $tag = new Tag();
            $tag->setId($item['tid']);
            $tag->setName($item['tName']);
            $tag->setType($item['tType']);
            $tag->setArea($item['tArea']);
            $tag->setByteAddress($item['tByteAddress']);
            $tag->setBitAddress($item['tBitAddress']);
            $tag->setReadAccess($item['tReadAccess']);
            $tag->setWriteAccess($item['tWriteAccess']);
            
            // Add to the array
            array_push($ret, $tag);
        }
        
        return $ret;
    }
    
    /**
     * Get number of all tags in DB
     * 
     * @param int $area Tag area
     * @return numeric Number of tags in DB
     * @throws Exception
     */
    public function getTagsCount(int $area = 0) {
        
        // Base query
        $sql = "SELECT count(*) AS 'cnt' FROM tags";
        
        // Area
        if ($area > 0) {
            $sql .= ' WHERE tArea = ?';
        }
        
        // End query
        $sql .= ';';
        
        $statement = $this->dbConn->prepare($sql);
        
        if ($area > 0) {
            $statement->bindValue(1, $area, ParameterType::INTEGER);
        }
        
        $statement->execute();
        $items = $statement->fetchAll();
        
        if (empty($items) || count($items) != 1) {
            throw new Exception("Error during executing count query!");
        }
        
        $item = $items[0];
        
        return $item['cnt'];
    }
    
    /**
     * Get Tag data
     * 
     * @param numeric $tagId Tag identifier
     * @return Tag Tag object
     * @throws Exception Tag identifier invalid or Tag not exist
     */
    public function getTag($tagId) {
        
        // Check tag identifier
        Tag::checkId($tagId);
        
        $statement = $this->dbConn->prepare('SELECT * FROM tags WHERE tid = ?;');
        $statement->bindValue(1, $tagId, ParameterType::INTEGER);
        $statement->execute();
        
        $items= $statement->fetchAll();
        
        if (empty($items)) {
            throw new Exception("Tag with identifier ".$tagId." does not exist!");
        }
        if (count($items) != 1) {
            throw new Exception("Query return more than one element!");
        }
        $item = $items[0];
        
        // New tag
        $tag = new Tag();
        $tag->setId($item['tid']);
        $tag->setName($item['tName']);
        $tag->setType($item['tType']);
        $tag->setArea($item['tArea']);
        $tag->setByteAddress($item['tByteAddress']);
        $tag->setBitAddress($item['tBitAddress']);
        $tag->setReadAccess($item['tReadAccess']);
        $tag->setWriteAccess($item['tWriteAccess']);
        
        return $tag;
    }
    
    /**
     * Get Tag data by Tag name
     * 
     * @param string $tagName Tag name
     * @return Tag Tag object
     * @throws Exception Tag name invalid or Tag not exist
     */
    public function getTagByName(string $tagName) {
        
        // Check tag name
        Tag::checkName($tagName);
        
        $statement = $this->dbConn->prepare('SELECT * FROM tags WHERE tName = ?;');
        $statement->bindValue(1, $tagName, ParameterType::STRING);
        $statement->execute();
        
        $items= $statement->fetchAll();
        
        if (empty($items)) {
            throw new AppException("Tag ".$tagName." does not exist!", AppException::TAG_NOT_EXIST);
        }
        if (count($items) != 1) {
            throw new Exception("Query return more than one element!");
        }
        $item = $items[0];
        
        // New tag
        $tag = new Tag();
        $tag->setId($item['tid']);
        $tag->setName($item['tName']);
        $tag->setType($item['tType']);
        $tag->setArea($item['tArea']);
        $tag->setByteAddress($item['tByteAddress']);
        $tag->setBitAddress($item['tBitAddress']);
        $tag->setReadAccess($item['tReadAccess']);
        $tag->setWriteAccess($item['tWriteAccess']);
        
        return $tag;
    }
    
    /**
     * Get Tags data by Tag name search
     * 
     * @param string $tagName Tag name to search
     * @return array Array with Tags
     * @throws AppException
     * @throws Exception
     */
    public function searchTagsByName(string $tagName) {
        
        // Check tag name
        Tag::checkName($tagName);
        
        // Add '%' at the end
        $tagName.="%";
        
        $statement = $this->dbConn->prepare('SELECT * FROM tags WHERE tName LIKE ?;');
        $statement->bindValue(1, $tagName, ParameterType::STRING);
        $statement->execute();
                
        $items= $statement->fetchAll();
                
        $ret = array();
        
        foreach($items as $item) {
            
            // New tag
            $tag = new Tag();
            $tag->setId($item['tid']);
            $tag->setName($item['tName']);
            $tag->setType($item['tType']);
            $tag->setArea($item['tArea']);
            $tag->setByteAddress($item['tByteAddress']);
            $tag->setBitAddress($item['tBitAddress']);
            $tag->setReadAccess($item['tReadAccess']);
            $tag->setWriteAccess($item['tWriteAccess']);
            
            // Add to the array
            array_push($ret, $tag);
        }
        
        return $ret;
    }
    
    /**
     * Check if given Tag has unique address
     * 
     * @param Tag $tag Tag object
     * @return boolean True if address does not exist in DB
     * @throws Exception
     */
    private function isTagAddressExist(Tag $tag) {
        
        $ret = false;
        
        $sql = "SELECT count(*) AS 'cnt' FROM tags WHERE tType = ? AND tArea = ? AND tByteAddress = ? AND tBitAddress = ?;";
                
        $statement = $this->dbConn->prepare($sql);
        
        $statement->bindValue(1, $tag->getType(), ParameterType::INTEGER);
        $statement->bindValue(2, $tag->getArea(), ParameterType::INTEGER);
        $statement->bindValue(3, $tag->getByteAddress(), ParameterType::INTEGER);
        $statement->bindValue(4, $tag->getBitAddress(), ParameterType::INTEGER);
        
        $statement->execute();
        $items = $statement->fetchAll();
        
        if (empty($items) || count($items) != 1) {
            throw new Exception("Error during executing count query!");
        }
        
        $item = $items[0];
        
        if ($item['cnt'] != 0) {
            $ret = true;
        }
        
        return $ret;
    }
    
    /**
     * Add Tag to the DB
     * 
     * @param Tag $newTag Tag to add
     */
    public function addTag(Tag $newTag) {
        
        // Check if Tag is valid
        $newTag->isValid();
        
        // Check Tag address
        if ($this->isTagAddressExist($newTag)) {
            throw new AppException(
                "Tag ".$newTag->getName()." address: ".TagArea::getPrefix($newTag->getArea()).
                " ".$newTag->getByteAddress().".".$newTag->getBitAddress()." exist in DB!",
                AppException::TAG_ADDRESS_EXIST
            );
        }
        
        // Check Byte address
        $cfg = new ConfigDriverMapper($this->dbConn);
        $cfg->checkDriverByteAddress($newTag);
        
        $stmt = $this->dbConn->prepare('INSERT INTO tags (tName, tType, tArea, tByteAddress, tBitAddress, tReadAccess, tWriteAccess) VALUES(?, ?, ?, ?, ?, ?, ?);');
        
        $stmt->bindValue(1, $newTag->getName(), ParameterType::STRING);
        $stmt->bindValue(2, $newTag->getType(), ParameterType::INTEGER);
        $stmt->bindValue(3, $newTag->getArea(), ParameterType::INTEGER);
        $stmt->bindValue(4, $newTag->getByteAddress(), ParameterType::INTEGER);
        $stmt->bindValue(5, $newTag->getBitAddress(), ParameterType::INTEGER);
        $stmt->bindValue(6, $newTag->getReadAccess(), ParameterType::STRING);
        $stmt->bindValue(7, $newTag->getWriteAccess(), ParameterType::STRING);
        
        try {
            
            if (!$stmt->execute()) {
                throw new Exception("Error during execute sql add query!");
            }
            
        } catch (UniqueConstraintViolationException $ex) {
            
            throw new AppException(
                "Tag ".$newTag->getName()." exist in DB!",
                AppException::TAG_NAME_EXIST
            );
            
        }
    }
    
    /**
     * Check if Tag address is changed
     * 
     * @param Tag $newTag  New Tag object
     * @param Tag $oldTag  Old Tag object
     * @return boolean True if address is different
     */
    private function isAddressChanged(Tag $newTag, Tag $oldTag) {
        
        $ret = false;
        
        // Check area
        if ($newTag->getArea() == $oldTag->getArea()) {
            // Check type
            if ($newTag->getType() == $oldTag->getType()) {
                // Check Byte address
                if ($newTag->getByteAddress() == $oldTag->getByteAddress()) {
                    // Check Bit address
                    if ($newTag->getBitAddress() != $oldTag->getBitAddress()) {
                        $ret = true;
                    }
                } else {
                    $ret = true;
                }
            } else {
                $ret = true;
            }
        } else {
            $ret = true;
        }
        
        return $ret;
    }
    
    /**
     * Edit Tag
     * 
     * @param Tag $newTag New Tag object
     * @param Tag $oldTag Old Tag object
     */
    public function editTag(Tag $newTag, Tag $oldTag) {
        
        // Check if Tag is valid
        $newTag->isValid(true);
        
        // Check Tag address
        if ($this->isAddressChanged($newTag, $oldTag) && $this->isTagAddressExist($newTag)) {
            throw new AppException(
                "Tag ".$newTag->getName()." address: ".TagArea::getPrefix($newTag->getArea()).
                " ".$newTag->getByteAddress().".".$newTag->getBitAddress()." exist in DB!",
                AppException::TAG_ADDRESS_EXIST
            );
        }
        
        // Check Byte address
        $cfg = new ConfigDriverMapper($this->dbConn);
        $cfg->checkDriverByteAddress($newTag);
        
        $stmt = $this->dbConn->prepare('UPDATE tags SET tName = ?, tType = ?, tArea = ?, tByteAddress = ?, tBitAddress = ?, tReadAccess = ?, tWriteAccess = ? WHERE tid = ?;');
        
        $stmt->bindValue(1, $newTag->getName(), ParameterType::STRING);
        $stmt->bindValue(2, $newTag->getType(), ParameterType::INTEGER);
        $stmt->bindValue(3, $newTag->getArea(), ParameterType::INTEGER);
        $stmt->bindValue(4, $newTag->getByteAddress(), ParameterType::INTEGER);
        $stmt->bindValue(5, $newTag->getBitAddress(), ParameterType::INTEGER);
        $stmt->bindValue(6, $newTag->getReadAccess(), ParameterType::STRING);
        $stmt->bindValue(7, $newTag->getWriteAccess(), ParameterType::STRING);
        $stmt->bindValue(8, $newTag->getId(), ParameterType::INTEGER);
        
        try {
            
            if (!$stmt->execute()) {
                throw new Exception("Error during execute sql add query!");
            }
            
        } catch (UniqueConstraintViolationException $ex) {

            throw new AppException(
                "Tag ".$newTag->getName()." exist in DB!",
                AppException::TAG_NAME_EXIST
            );
            
        }
    }
    
    /**
     * Delete Tag
     * 
     * @param numeric $tagId Tag identifier
     */
    public function deleteTag($tagId) {
                
        // Check tag identifier
        Tag::checkId($tagId);
        
        $statement = $this->dbConn->prepare('DELETE FROM tags WHERE tid = ?;');
        $statement->bindValue(1, $tagId, ParameterType::INTEGER);
        
        try {
            
            if (!$statement->execute()) {
                throw new Exception("Error during execute delete query!");
            }
            
        } catch (ForeignKeyConstraintViolationException $ex) {
            
            throw new AppException(
                "Tag with identifier: ".$tagId." is used inside system!",
                AppException::TAG_IS_USED
            );
        }
    }
}
