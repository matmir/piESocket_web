<?php

namespace App\Service\Admin;

use Doctrine\DBAL\Driver\Connection;
use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\Config\Definition\Exception\Exception;
use App\Entity\Admin\Tag;
use App\Service\Admin\TagsMapper;
use App\Entity\Admin\ScriptItem;
use App\Entity\Paginator;
use App\Entity\AppException;

/**
 * Class to read/write Script items
 *
 * @author Mateusz Mirosławski
 */
class ScriptItemMapper
{
    private Connection $dbConn;
    
    public function __construct(Connection $connection)
    {
        $this->dbConn = $connection;
    }
    
    /**
     * Get Script items
     *
     * @param int $area Script item area (0 - all, 1 - input, 2 - output, 3 - memory)
     * @param int $sort Script item sorting (0 - ID, 1 - tag name,
     *                                      2 - script name, 3 - run flag, 4 - lock flag, 5 - enabled flag)
     * @param int $sortDESC Sorting direction (0 - ASC, 1 - DESC)
     * @param Paginator|null $paginator Paginator object
     * @return array Array with Script item
     */
    public function getScripts(int $area = 0, int $sort = 0, int $sortDESC = 0, ?Paginator $paginator = null): array
    {
        // Basic query
        $sql = 'SELECT * FROM scripts sc, tags t';
        $sql .= ' WHERE sc.scTagId=t.tid';
        
        // Area
        if ($area > 0) {
            $sql .= ' AND t.tArea = ?';
        }
        
        // Order direction
        $oDirection = ($sortDESC == 1) ? ('DESC') : ('ASC');
        
        // Order
        switch ($sort) {
            case 0:
                $sql .= ' ORDER BY sc.scid ' . $oDirection;
                break;
            case 1:
                $sql .= ' ORDER BY t.tName ' . $oDirection;
                break;
            case 2:
                $sql .= ' ORDER BY sc.scName ' . $oDirection;
                break;
            case 3:
                $sql .= ' ORDER BY sc.scRun ' . $oDirection;
                break;
            case 4:
                $sql .= ' ORDER BY sc.scLock ' . $oDirection;
                break;
            case 5:
                $sql .= ' ORDER BY sc.scEnable ' . $oDirection;
                break;
            default:
                $sql .= ' ORDER BY sc.scid ' . $oDirection;
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
        
        $statement->execute();
        $items = $statement->fetchAll();
        
        $ret = array();
        
        foreach ($items as $item) {
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
            
            // Script item
            $scriptItem = new ScriptItem($tag);
            $scriptItem->setId($item['scid']);
            $scriptItem->setName($item['scName']);
            $scriptItem->setRun((($item['scRun'] == 1) ? (true) : (false)));
            $scriptItem->setLocked((($item['scLock'] == 1) ? (true) : (false)));
            
            $tagsMapper = null;
            // Is feedback Tag?
            if (!($item['scFeedbackRun'] === null)) {
                // Tags mappers
                $tagsMapper = new TagsMapper($this->dbConn);
                $fbTag = $tagsMapper->getTag($item['scFeedbackRun']);
                $scriptItem->setFeedbackRun($fbTag);
            }
            
            $scriptItem->setEnabled((($item['scEnable'] == 1) ? (true) : (false)));
            
            // Add to the array
            array_push($ret, $scriptItem);
        }
        
        return $ret;
    }
    
    /**
     * Get number of all script items in DB
     *
     * @param int $area Tag area
     * @return int Number of script items in DB
     * @throws Exception
     */
    public function getScriptsCount(int $area = 0): int
    {
        // Base query
        $sql = "SELECT count(*) AS 'cnt' FROM tags t, scripts sc WHERE sc.scTagId=t.tid";
        
        // Area
        if ($area > 0) {
            $sql .= ' AND t.tArea = ?';
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
     * Get Script item data
     *
     * @param int $scriptId Script item identifier
     * @return ScriptItem Script item object
     * @throws Exception Script item identifier invalid or Script item not exist
     */
    public function getScript(int $scriptId): ScriptItem
    {
        // Check script identifier
        ScriptItem::checkId($scriptId);
        
        // Basic query
        $sql = 'SELECT * FROM scripts sc, tags t';
        $sql .= ' WHERE sc.scTagId=t.tid';
        $sql .= ' AND sc.scid = ?';
        
        $statement = $this->dbConn->prepare($sql);
        $statement->bindValue(1, $scriptId, ParameterType::INTEGER);
        $statement->execute();
        
        $items = $statement->fetchAll();
        
        if (empty($items)) {
            throw new Exception("Script item with identifier " . $scriptId . " does not exist!");
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

        // Script item
        $scriptItem = new ScriptItem($tag);
        $scriptItem->setId($item['scid']);
        $scriptItem->setName($item['scName']);
        $scriptItem->setRun((($item['scRun'] == 1) ? (true) : (false)));
        $scriptItem->setLocked((($item['scLock'] == 1) ? (true) : (false)));

        $tagsMapper = null;
        // Is feedback Tag?
        if (!($item['scFeedbackRun'] === null)) {
            // Tags mappers
            $tagsMapper = new TagsMapper($this->dbConn);
            $fbTag = $tagsMapper->getTag($item['scFeedbackRun']);
            $scriptItem->setFeedbackRun($fbTag);
        }

        $scriptItem->setEnabled((($item['scEnable'] == 1) ? (true) : (false)));
        
        return $scriptItem;
    }
    
    /**
     * Get Script item data by script name
     *
     * @param string $scriptName Script name
     * @return ScriptItem Script item object
     * @throws Exception Script item identifier invalid or Script item not exist
     */
    public function getScriptByName(string $scriptName): ScriptItem
    {
        // Check script name
        ScriptItem::checkName($scriptName);
        
        // Basic query
        $sql = 'SELECT * FROM scripts sc, tags t';
        $sql .= ' WHERE sc.scTagId=t.tid';
        $sql .= ' AND sc.scName = ?';
        
        $statement = $this->dbConn->prepare($sql);
        $statement->bindValue(1, $scriptName, ParameterType::STRING);
        $statement->execute();
        
        $items = $statement->fetchAll();
        
        if (empty($items)) {
            throw new Exception("Script item with name " . $scriptName . " does not exist!");
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

        // Script item
        $scriptItem = new ScriptItem($tag);
        $scriptItem->setId($item['scid']);
        $scriptItem->setName($item['scName']);
        $scriptItem->setRun((($item['scRun'] == 1) ? (true) : (false)));
        $scriptItem->setLocked((($item['scLock'] == 1) ? (true) : (false)));

        $tagsMapper = null;
        // Is feedback Tag?
        if (!($item['scFeedbackRun'] === null)) {
            // Tags mappers
            $tagsMapper = new TagsMapper($this->dbConn);
            $fbTag = $tagsMapper->getTag($item['scFeedbackRun']);
            $scriptItem->setFeedbackRun($fbTag);
        }

        $scriptItem->setEnabled((($item['scEnable'] == 1) ? (true) : (false)));
        
        return $scriptItem;
    }
    
    /**
     * Add Script item to the DB
     *
     * @param ScriptItem $newScript Script item to add
     */
    public function addScript(ScriptItem $newScript)
    {
        // Check if Script is valid
        $newScript->isValid();
        
        $q = 'INSERT INTO scripts (scTagId, scName, scFeedbackRun)';
        $q .= ' VALUES(?, ?, ?);';
        
        $stmt = $this->dbConn->prepare($q);
        
        $stmt->bindValue(1, $newScript->getTag()->getId(), ParameterType::INTEGER);
        $stmt->bindValue(2, $newScript->getName(), ParameterType::STRING);
        
        if ($newScript->isFeedbackRun()) {
            $stmt->bindValue(3, $newScript->getFeedbackRun()->getId(), ParameterType::INTEGER);
        } else {
            $stmt->bindValue(3, null, ParameterType::NULL);
        }
        
        try {
            if (!$stmt->execute()) {
                throw new Exception("Error during execute sql add query!");
            }
        } catch (UniqueConstraintViolationException $ex) {
            $tagExist = strpos($ex->getMessage(), "key 'scTagId'");
            $scriptExist = strpos($ex->getMessage(), "key 'scName'");
            
            $errMsg = '';
            $errCode = -1;
            
            if ($tagExist !== false) {
                $errMsg = "Script with tag: " . $newScript->getTag()->getName() . " exist in DB!";
                $errCode = AppException::SCRIPT_TAG_EXIST;
            }
            
            if ($scriptExist !== false) {
                $errMsg = "Script :" . $newScript->getName() . " exist in DB!";
                $errCode = AppException::SCRIPT_FILE_EXIST;
            }
            
            throw new AppException($errMsg, $errCode);
        }
    }
    
    /**
     * Edit Script item
     *
     * @param ScriptItem $newScript Script item to edit
     */
    public function editScript(ScriptItem $newScript)
    {
        // Check if Script is valid
        $newScript->isValid(true);
        
        $q = 'UPDATE scripts SET scTagId = ?, scName = ?, scFeedbackRun = ?';
        $q .= ' WHERE scid = ?;';
        
        $stmt = $this->dbConn->prepare($q);
        
        $stmt->bindValue(1, $newScript->getTag()->getId(), ParameterType::INTEGER);
        $stmt->bindValue(2, $newScript->getName(), ParameterType::STRING);
        
        if ($newScript->isFeedbackRun()) {
            $stmt->bindValue(3, $newScript->getFeedbackRun()->getId(), ParameterType::INTEGER);
        } else {
            $stmt->bindValue(3, null, ParameterType::NULL);
        }
        
        $stmt->bindValue(4, $newScript->getId(), ParameterType::INTEGER);
        
        try {
            if (!$stmt->execute()) {
                throw new Exception("Error during execute sql add query!");
            }
        } catch (UniqueConstraintViolationException $ex) {
            $tagExist = strpos($ex->getMessage(), "key 'scTagId'");
            $scriptExist = strpos($ex->getMessage(), "key 'scName'");
            
            $errMsg = '';
            $errCode = -1;
            
            if ($tagExist !== false) {
                $errMsg = "Script with tag: " . $newScript->getTag()->getName() . " exist in DB!";
                $errCode = AppException::SCRIPT_TAG_EXIST;
            }
            
            if ($scriptExist !== false) {
                $errMsg = "Script :" . $newScript->getName() . " exist in DB!";
                $errCode = AppException::SCRIPT_FILE_EXIST;
            }
            
            throw new AppException($errMsg, $errCode);
        }
    }
    
    /**
     * Delete Script item
     *
     * @param int $scriptId Script item identifier
     */
    public function deleteScript(int $scriptId)
    {
        // Check script identifier
        ScriptItem::checkId($scriptId);
        
        $statement = $this->dbConn->prepare('DELETE FROM scripts WHERE scid = ?;');
        $statement->bindValue(1, $scriptId, ParameterType::INTEGER);
                
        if (!$statement->execute()) {
            throw new Exception("Error during execute delete query!");
        }
    }
    
    /**
     * Enable script item
     *
     * @param int $scriptId Script item identifier
     * @param bool $en Enable flag
     */
    public function enableScript(int $scriptId, bool $en = true)
    {
        // Check script identifier
        ScriptItem::checkId($scriptId);
        
        $stmt = $this->dbConn->prepare('UPDATE scripts SET scEnable = ? WHERE scid = ?;');
        
        $stmt->bindValue(1, (($en) ? (1) : (0)), ParameterType::INTEGER);
        $stmt->bindValue(2, $scriptId, ParameterType::INTEGER);
        
        if (!$stmt->execute()) {
            throw new Exception("Error during execute sql update query!");
        }
    }
    
    /**
     * Check if given script name exist in DB
     *
     * @param string $scriptName Script name
     * @throws Exception
     */
    public function exist(string $scriptName): bool
    {
        // Check script name
        ScriptItem::checkName($scriptName);
        
        // Base query
        $sql = "SELECT count(*) AS 'cnt' FROM scripts sc WHERE sc.scName = ?;";
        
        $statement = $this->dbConn->prepare($sql);
        
        $statement->bindValue(1, $scriptName, ParameterType::STRING);
        
        $statement->execute();
        $items = $statement->fetchAll();
        
        if (empty($items) || count($items) != 1) {
            throw new Exception("Error during executing count query!");
        }
        
        $item = $items[0];
                
        return (($item['cnt'] == 1) ? (true) : (false));
    }
    
    /**
     * Set run and lock flag of the script
     *
     * @param string $scriptName Script name
     * @throws Exception
     */
    public function setFlags(string $scriptName)
    {
        // Check script name
        ScriptItem::checkName($scriptName);
        
        $stmt = $this->dbConn->prepare('UPDATE scripts SET scRun = 1, scLock=1 WHERE scName = ?;');
        
        $stmt->bindValue(1, $scriptName, ParameterType::STRING);
        
        if (!$stmt->execute()) {
            throw new Exception("Error during execute sql update query!");
        }
    }
    
    /**
     * Clear run flag of the script
     *
     * @param string $scriptName Script name
     * @throws Exception
     */
    public function clearRunFlag(string $scriptName)
    {
        // Check script name
        ScriptItem::checkName($scriptName);
        
        $stmt = $this->dbConn->prepare('UPDATE scripts SET scRun = 0 WHERE scName = ?;');
        
        $stmt->bindValue(1, $scriptName, ParameterType::STRING);
        
        if (!$stmt->execute()) {
            throw new Exception("Error during execute sql update query!");
        }
    }
}
