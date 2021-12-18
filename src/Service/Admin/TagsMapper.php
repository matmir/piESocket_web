<?php

namespace App\Service\Admin;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\Config\Definition\Exception\Exception;
use App\Entity\Admin\Tag;
use App\Entity\Admin\TagArea;
use App\Entity\Paginator;
use App\Entity\AppException;
use App\Service\Admin\DriverConnectionMapper;

/**
 * Class to read/write Tags
 *
 * @author Mateusz Mirosławski
 */
class TagsMapper
{
    private Connection $dbConn;
    
    public function __construct(Connection $connection)
    {
        $this->dbConn = $connection;
    }
    
    /**
     * Get Tags
     *
     * @param int $area Tag area (0 - all, 1 - input, 2 - output, 3 - memory)
     * @param int $sort Tag sorting (0 - ID, 1 - tag name, 2 - start address, 3 - tag type)
     * @param int $sortDESC Sorting direction (0 - ASC, 1 - DESC)
     * @param Paginator|null $paginator Paginator object
     * @return array Array with Tags
     */
    public function getTags(int $area = 0, int $sort = 0, int $sortDESC = 0, ?Paginator $paginator = null): array
    {
        // Basic query
        $sql = 'SELECT * FROM tags t, driver_connections dc WHERE t.tConnId = dc.dcId';
        
        // Area
        if ($area > 0) {
            $sql .= ' AND tArea = ?';
        }
        
        // Order direction
        $oDirection = ($sortDESC == 1) ? ('DESC') : ('ASC');
        
        // Order
        switch ($sort) {
            case 0:
                $sql .= ' ORDER BY tid ' . $oDirection;
                break;
            case 1:
                $sql .= ' ORDER BY tName ' . $oDirection;
                break;
            case 2:
                $sql .= ' ORDER BY tByteAddress ' . $oDirection . ', tBitAddress ' . $oDirection;
                break;
            case 3:
                $sql .= ' ORDER BY tType ' . $oDirection;
                break;
            default:
                $sql .= ' ORDER BY tid ' . $oDirection;
        }
        
        // Check paginator
        if (!is_null($paginator)) {
            $sql .= " " . $paginator->getSqlQuery();
        }
        
        // End query
        $sql .= ';';
        
        $statement = $this->dbConn->prepare($sql);
        
        if ($area > 0) {
            $statement->bindValue(1, $area, ParameterType::INTEGER);
        }
        
        $results = $statement->execute();
        $items = $results->fetchAllAssociative();
        
        $ret = array();
        
        foreach ($items as $item) {
            // New tag
            $tag = new Tag();
            $tag->setId($item['tid']);
            $tag->setConnId($item['tConnId']);
            $tag->setConnName($item['dcName']);
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
     * @return int Number of tags in DB
     * @throws Exception
     */
    public function getTagsCount(int $area = 0): int
    {
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
        
        $results = $statement->execute();
        $items = $results->fetchAllAssociative();
        
        if (empty($items) || count($items) != 1) {
            throw new Exception("Error during executing count query!");
        }
        
        $item = $items[0];
        
        return $item['cnt'];
    }
    
    /**
     * Get Tag data
     *
     * @param int $tagId Tag identifier
     * @return Tag Tag object
     * @throws Exception Tag identifier invalid or Tag not exist
     */
    public function getTag(int $tagId)
    {
        // Check tag identifier
        Tag::checkId($tagId);
        
        $q = 'SELECT * FROM tags t, driver_connections dc WHERE t.tConnId = dc.dcId AND tid = ?;';
        $statement = $this->dbConn->prepare($q);
        $statement->bindValue(1, $tagId, ParameterType::INTEGER);
        
        $results = $statement->execute();
        $items = $results->fetchAllAssociative();
        
        if (empty($items)) {
            throw new Exception("Tag with identifier " . $tagId . " does not exist!");
        }
        if (count($items) != 1) {
            throw new Exception("Query return more than one element!");
        }
        $item = $items[0];
        
        // New tag
        $tag = new Tag();
        $tag->setId($item['tid']);
        $tag->setConnId($item['tConnId']);
        $tag->setConnName($item['dcName']);
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
    public function getTagByName(string $tagName): Tag
    {
        // Check tag name
        Tag::checkName($tagName);
        
        $q = 'SELECT * FROM tags t, driver_connections dc WHERE t.tConnId = dc.dcId AND tName = ?;';
        $statement = $this->dbConn->prepare($q);
        $statement->bindValue(1, $tagName, ParameterType::STRING);
        
        $results = $statement->execute();
        $items = $results->fetchAllAssociative();
        
        if (empty($items)) {
            throw new AppException("Tag " . $tagName . " does not exist!", AppException::TAG_NOT_EXIST);
        }
        if (count($items) != 1) {
            throw new Exception("Query return more than one element!");
        }
        $item = $items[0];
        
        // New tag
        $tag = new Tag();
        $tag->setId($item['tid']);
        $tag->setConnId($item['tConnId']);
        $tag->setConnName($item['dcName']);
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
    public function searchTagsByName(string $tagName): array
    {
        // Check tag name
        Tag::checkName($tagName);
        
        // Add '%' at the end
        $tagName .= "%";
        
        $q = 'SELECT * FROM tags t, driver_connections dc WHERE t.tConnId = dc.dcId AND tName LIKE ?;';
        $statement = $this->dbConn->prepare($q);
        $statement->bindValue(1, $tagName, ParameterType::STRING);
        
        $results = $statement->execute();
        $items = $results->fetchAllAssociative();
                
        $ret = array();
        
        foreach ($items as $item) {
            // New tag
            $tag = new Tag();
            $tag->setId($item['tid']);
            $tag->setConnId($item['tConnId']);
            $tag->setConnName($item['dcName']);
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
     * @return bool True if address does not exist in DB
     * @throws Exception
     */
    private function isTagAddressExist(Tag $tag): bool
    {
        $ret = false;
        
        $sql = "SELECT count(*) AS 'cnt' FROM tags WHERE tType = ? AND tArea = ?";
        $sql .= ' AND tByteAddress = ? AND tBitAddress = ?';
        $sql .= ' AND tConnId = ? AND tid <> ?;';
                
        $statement = $this->dbConn->prepare($sql);
        
        $statement->bindValue(1, $tag->getType(), ParameterType::INTEGER);
        $statement->bindValue(2, $tag->getArea(), ParameterType::INTEGER);
        $statement->bindValue(3, $tag->getByteAddress(), ParameterType::INTEGER);
        $statement->bindValue(4, $tag->getBitAddress(), ParameterType::INTEGER);
        $statement->bindValue(5, $tag->getConnId(), ParameterType::INTEGER);
        $statement->bindValue(6, $tag->getId(), ParameterType::INTEGER);
        
        $results = $statement->execute();
        $items = $results->fetchAllAssociative();
        
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
    public function addTag(Tag $newTag)
    {
        // Check if Tag is valid
        $newTag->isValid();
        
        // Check Tag address
        if ($this->isTagAddressExist($newTag)) {
            throw new AppException(
                "Tag " . $newTag->getName() . " address: " . TagArea::getPrefix($newTag->getArea()) .
                " " . $newTag->getByteAddress() . "." . $newTag->getBitAddress() . " exist in DB!",
                AppException::TAG_ADDRESS_EXIST
            );
        }
        
        // Check Byte address
        $conn = new DriverConnectionMapper($this->dbConn);
        $conn->checkDriverByteAddress($newTag);
        
        // Check Area
        $conn->checkDriverArea($newTag);
        
        // Query
        $q = 'INSERT INTO tags (tConnId, tName, tType, tArea, tByteAddress, tBitAddress, tReadAccess, tWriteAccess)';
        $q .= ' VALUES(?, ?, ?, ?, ?, ?, ?, ?);';
        
        $stmt = $this->dbConn->prepare($q);
        
        $stmt->bindValue(1, $newTag->getConnId(), ParameterType::INTEGER);
        $stmt->bindValue(2, $newTag->getName(), ParameterType::STRING);
        $stmt->bindValue(3, $newTag->getType(), ParameterType::INTEGER);
        $stmt->bindValue(4, $newTag->getArea(), ParameterType::INTEGER);
        $stmt->bindValue(5, $newTag->getByteAddress(), ParameterType::INTEGER);
        $stmt->bindValue(6, $newTag->getBitAddress(), ParameterType::INTEGER);
        $stmt->bindValue(7, $newTag->getReadAccess(), ParameterType::STRING);
        $stmt->bindValue(8, $newTag->getWriteAccess(), ParameterType::STRING);
        
        try {
            if (!$stmt->execute()) {
                throw new Exception("Error during execute sql add query!");
            }
        } catch (UniqueConstraintViolationException $ex) {
            throw new AppException(
                "Tag " . $newTag->getName() . " exist in DB!",
                AppException::TAG_NAME_EXIST
            );
        }
    }
    
    /**
     * Edit Tag
     *
     * @param Tag $newTag New Tag object
     * @param Tag $oldTag Old Tag object
     */
    public function editTag(Tag $newTag)
    {
        // Check if Tag is valid
        $newTag->isValid(true);
        
        // Check Tag address
        if ($this->isTagAddressExist($newTag)) {
            throw new AppException(
                "Tag " . $newTag->getName() . " address: " . TagArea::getPrefix($newTag->getArea()) .
                " " . $newTag->getByteAddress() . "." . $newTag->getBitAddress() . " exist in DB!",
                AppException::TAG_ADDRESS_EXIST
            );
        }
        
        // Check Byte address
        $conn = new DriverConnectionMapper($this->dbConn);
        $conn->checkDriverByteAddress($newTag);
        
        // Check Area
        $conn->checkDriverArea($newTag);
        
        // Query
        $q = 'UPDATE tags SET tConnId = ?, tName = ?, tType = ?, tArea = ?, tByteAddress = ?, tBitAddress = ?';
        $q .= ', tReadAccess = ?, tWriteAccess = ? WHERE tid = ?;';
        
        $stmt = $this->dbConn->prepare($q);
        
        $stmt->bindValue(1, $newTag->getConnId(), ParameterType::INTEGER);
        $stmt->bindValue(2, $newTag->getName(), ParameterType::STRING);
        $stmt->bindValue(3, $newTag->getType(), ParameterType::INTEGER);
        $stmt->bindValue(4, $newTag->getArea(), ParameterType::INTEGER);
        $stmt->bindValue(5, $newTag->getByteAddress(), ParameterType::INTEGER);
        $stmt->bindValue(6, $newTag->getBitAddress(), ParameterType::INTEGER);
        $stmt->bindValue(7, $newTag->getReadAccess(), ParameterType::STRING);
        $stmt->bindValue(8, $newTag->getWriteAccess(), ParameterType::STRING);
        $stmt->bindValue(9, $newTag->getId(), ParameterType::INTEGER);
        
        try {
            if (!$stmt->execute()) {
                throw new Exception("Error during execute sql add query!");
            }
        } catch (UniqueConstraintViolationException $ex) {
            throw new AppException(
                "Tag " . $newTag->getName() . " exist in DB!",
                AppException::TAG_NAME_EXIST
            );
        }
    }
    
    /**
     * Delete Tag
     *
     * @param int $tagId Tag identifier
     */
    public function deleteTag(int $tagId)
    {
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
                "Tag with identifier: " . $tagId . " is used inside system!",
                AppException::TAG_IS_USED
            );
        }
    }
}
