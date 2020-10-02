<?php

namespace App\Service\Admin;

use Doctrine\DBAL\Driver\Connection;
use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\Config\Definition\Exception\Exception;
use App\Entity\Paginator;
use App\Entity\Admin\Tag;
use App\Entity\Admin\Alarm;
use App\Service\Admin\TagsMapper;
use App\Entity\Admin\AlarmItem;
use App\Entity\AppException;

/**
 * Class to read/write Alarms
 *
 * @author Mateusz Mirosławski
 */
class AlarmMapper
{
    private Connection $dbConn;
    
    public function __construct(Connection $connection)
    {
        $this->dbConn = $connection;
    }
    
    /**
     * Get Pending alarms
     *
     * @return array Array with Pending alarms
     */
    public function getPendingAlarms(): array
    {
        // Basic query
        $sql = 'SELECT * FROM alarms_pending ap, alarms_definition ad';
        $sql .= ' WHERE ap.apadid = ad.adid';
        
        // Sorting
        $sql .=  ' ORDER BY ad.adPriority ASC, ap.ap_onTimestamp DESC';
        
        // End query
        $sql .= ';';
        
        $statement = $this->dbConn->prepare($sql);
        
        $statement->execute();
        $items = $statement->fetchAll();
        
        $ret = array();
        
        foreach ($items as $item) {
            // New alarm Item
            $aItem = new AlarmItem();
            $aItem->setId($item['apid']);
            $aItem->setDefinitionId($item['apadid']);
            $aItem->setPriority($item['adPriority']);
            $aItem->setMessage($item['adMessage']);
            $aItem->setActive((($item['ap_active'] == 1) ? (true) : (false)));
            $aItem->setAck((($item['ap_ack'] == 1) ? (true) : (false)));
            
            // Is alarm on timestamp?
            if (!($item['ap_onTimestamp'] === null)) {
                $aItem->setOnTimestamp($item['ap_onTimestamp']);
            }
            
            // Is alarm off timestamp?
            if (!($item['ap_offTimestamp'] === null)) {
                $aItem->setOffTimestamp($item['ap_offTimestamp']);
            }
            
            // Is alarm ack timestamp?
            if (!($item['ap_ackTimestamp'] === null)) {
                $aItem->setAckTimestamp($item['ap_ackTimestamp']);
            }
            
            // Add to the array
            array_push($ret, $aItem);
        }
        
        return $ret;
    }
    
    /**
     * Get Archived alarms
     *
     * @param int $sort Alarm sorting (0 - ID, 1 - priority, 2 - on time, 3 - off time, 4 - ack time)
     * @param int $sortDESC Sorting direction (0 - ASC, 1 - DESC)
     * @return array Array with Archived alarms
     */
    public function getArchivedAlarms(int $sort = 0, int $sortDESC = 0, Paginator $paginator = null): array
    {
        // Basic query
        $sql = 'SELECT * FROM alarms_history ah, alarms_definition ad';
        $sql .= ' WHERE ah.ahadid = ad.adid';
        
        // Order direction
        $oDirection = ($sortDESC == 1) ? ('DESC') : ('ASC');
        
        // Order
        switch ($sort) {
            case 0:
                $sql .= ' ORDER BY ah.ahid ' . $oDirection;
                break;
            case 1:
                $sql .= ' ORDER BY ad.adPriority ' . $oDirection;
                break;
            case 2:
                $sql .= ' ORDER BY ah.ah_onTimestamp ' . $oDirection;
                break;
            case 3:
                $sql .= ' ORDER BY ah.ah_offTimestamp ' . $oDirection;
                break;
            case 4:
                $sql .= ' ORDER BY ah.ah_ackTimestamp ' . $oDirection;
                break;
            default:
                $sql .= ' ORDER BY ah.ahid ' . $oDirection;
        }
        
        // Check paginator
        if (!is_null($paginator)) {
            $sql .= " " . $paginator->getSqlQuery();
        }
        
        // End query
        $sql .= ';';
        
        $statement = $this->dbConn->prepare($sql);
        
        $statement->execute();
        $items = $statement->fetchAll();
        
        $ret = array();
        
        foreach ($items as $item) {
            // New alarm Item
            $aItem = new AlarmItem();
            $aItem->setId($item['ahid']);
            $aItem->setDefinitionId($item['ahadid']);
            $aItem->setPriority($item['adPriority']);
            $aItem->setMessage($item['adMessage']);
            
            // Is alarm on timestamp?
            if (!($item['ah_onTimestamp'] === null)) {
                $aItem->setOnTimestamp($item['ah_onTimestamp']);
            }
            
            // Is alarm off timestamp?
            if (!($item['ah_offTimestamp'] === null)) {
                $aItem->setOffTimestamp($item['ah_offTimestamp']);
            }
            
            // Is alarm ack timestamp?
            if (!($item['ah_ackTimestamp'] === null)) {
                $aItem->setAckTimestamp($item['ah_ackTimestamp']);
            }
            
            // Add to the array
            array_push($ret, $aItem);
        }
        
        return $ret;
    }
    
    /**
     * Get Alarms
     *
     * @param int $area Alarm area (0 - all, 1 - input, 2 - output, 3 - memory)
     * @param int $sort Alarm sorting (0 - ID, 1 - tag name, 2 - priority, 3 - trigger type,
     *                                      4 - auto ack flag, 5 - active flag, 6 - pending flag, 7 - enable flag)
     * @param int $sortDESC Sorting direction (0 - ASC, 1 - DESC)
     * @param Paginator $paginator Paginator object
     * @return array Array with Alarms
     */
    public function getAlarms(int $area = 0, int $sort = 0, int $sortDESC = 0, Paginator $paginator = null): array
    {
        // Basic query
        $sql = 'SELECT * FROM alarms_definition ad, tags t';
        $sql .= ' WHERE ad.adtid=t.tid';
        
        // Area
        if ($area > 0) {
            $sql .= ' AND t.tArea = ?';
        }
        
        // Order direction
        $oDirection = ($sortDESC == 1) ? ('DESC') : ('ASC');
        
        // Order
        switch ($sort) {
            case 0:
                $sql .= ' ORDER BY ad.adid ' . $oDirection;
                break;
            case 1:
                $sql .= ' ORDER BY t.tName ' . $oDirection;
                break;
            case 2:
                $sql .= ' ORDER BY ad.adPriority ' . $oDirection;
                break;
            case 3:
                $sql .= ' ORDER BY ad.adTrigger ' . $oDirection;
                break;
            case 4:
                $sql .= ' ORDER BY ad.adAutoAck ' . $oDirection;
                break;
            case 5:
                $sql .= ' ORDER BY ad.adActive ' . $oDirection;
                break;
            case 6:
                $sql .= ' ORDER BY ad.adPending ' . $oDirection;
                break;
            case 7:
                $sql .= ' ORDER BY ad.adEnable ' . $oDirection;
                break;
            default:
                $sql .= ' ORDER BY ad.adid ' . $oDirection;
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
            
            // Alarm
            $alarm = new Alarm($tag);
            $alarm->setId($item['adid']);
            $alarm->setPriority($item['adPriority']);
            $alarm->setMessage($item['adMessage']);
            $alarm->setTrigger($item['adTrigger']);
            $alarm->setTriggerBin($item['adTriggerB']);
            $alarm->setTriggerNumeric($item['adTriggerN']);
            $alarm->setTriggerReal($item['adTriggerR']);
            $alarm->setAutoAck((($item['adAutoAck'] == 1) ? (true) : (false)));
            $alarm->setActive((($item['adActive'] == 1) ? (true) : (false)));
            $alarm->setPending((($item['adPending'] == 1) ? (true) : (false)));
            
            $tagsMapper = null;
            // Is feedback Tag?
            if (!($item['adFeedbackNotACK'] === null)) {
                // Tags mappers
                $tagsMapper = new TagsMapper($this->dbConn);
                $fbTag = $tagsMapper->getTag($item['adFeedbackNotACK']);
                $alarm->setFeedbackNotAck($fbTag);
            }
            
            // Is HW ack tag?
            if (!($item['adHWAck'] === null)) {
                // Tags mapper
                if (!($tagsMapper instanceof TagsMapper)) {
                    $tagsMapper = new TagsMapper($this->dbConn);
                }
                $hwAckTag = $tagsMapper->getTag($item['adHWAck']);
                $alarm->setHWAck($hwAckTag);
            }
            
            $alarm->setEnable((($item['adEnable'] == 1) ? (true) : (false)));
            
            // Add to the array
            array_push($ret, $alarm);
        }
        
        return $ret;
    }
    
    /**
     * Get number of all Alarms in DB
     *
     * @param int $area Alarm area
     * @return int Number of Alarms in DB
     * @throws Exception
     */
    public function getAlarmsCount(int $area = 0): int
    {
        // Base query
        $sql = "SELECT count(*) AS 'cnt' FROM tags t, alarms_definition ad WHERE ad.adtid=t.tid";
        
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
     * Get number of all Archived Alarms in DB
     *
     * @return int Number of Archived Alarms in DB
     * @throws Exception
     */
    public function getArchivedAlarmsCount(): int
    {
        // Base query
        $sql = "SELECT count(*) AS 'cnt' FROM alarms_history";
        
        // End query
        $sql .= ';';
        
        $statement = $this->dbConn->prepare($sql);
        
        $statement->execute();
        $items = $statement->fetchAll();
        
        if (empty($items) || count($items) != 1) {
            throw new Exception("Error during executing count query!");
        }
        
        $item = $items[0];
        
        return $item['cnt'];
    }
    
    /**
     * Get Alarm data
     *
     * @param int $alarmId Alarm identifier
     * @return Alarm Alarm object
     * @throws Exception Alarm identifier invalid or Alarm not exist
     */
    public function getAlarm(int $alarmId): Alarm
    {
        // Check alarm identifier
        Alarm::checkId($alarmId);
        
        // Basic query
        $sql = 'SELECT * FROM alarms_definition ad, tags t';
        $sql .= ' WHERE ad.adtid=t.tid';
        $sql .= ' AND ad.adid = ?';
        
        $statement = $this->dbConn->prepare($sql);
        $statement->bindValue(1, $alarmId, ParameterType::INTEGER);
        $statement->execute();
        
        $items = $statement->fetchAll();
        
        if (empty($items)) {
            throw new Exception("Alarm with identifier " . $alarmId . " does not exist!");
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

        // Alarm
        $alarm = new Alarm($tag);
        $alarm->setId($item['adid']);
        $alarm->setPriority($item['adPriority']);
        $alarm->setMessage($item['adMessage']);
        $alarm->setTrigger($item['adTrigger']);
        $alarm->setTriggerBin($item['adTriggerB']);
        $alarm->setTriggerNumeric($item['adTriggerN']);
        $alarm->setTriggerReal($item['adTriggerR']);
        $alarm->setAutoAck((($item['adAutoAck'] == 1) ? (true) : (false)));
        $alarm->setActive((($item['adActive'] == 1) ? (true) : (false)));
        $alarm->setPending((($item['adPending'] == 1) ? (true) : (false)));

        $tagsMapper = null;
        // Is feedback Tag?
        if (!($item['adFeedbackNotACK'] === null)) {
            // Tags mappers
            $tagsMapper = new TagsMapper($this->dbConn);
            $fbTag = $tagsMapper->getTag($item['adFeedbackNotACK']);
            $alarm->setFeedbackNotAck($fbTag);
        }

        // Is HW ack tag?
        if (!($item['adHWAck'] === null)) {
            // Tags mapper
            if (!($tagsMapper instanceof TagsMapper)) {
                $tagsMapper = new TagsMapper($this->dbConn);
            }
            $hwAckTag = $tagsMapper->getTag($item['adHWAck']);
            $alarm->setHWAck($hwAckTag);
        }

        $alarm->setEnable((($item['adEnable'] == 1) ? (true) : (false)));
        
        return $alarm;
    }
    
    /**
     * Add Alarm to the DB
     *
     * @param Alarm $newAlarm Alarm to add
     */
    public function addAlarm(Alarm $newAlarm)
    {
        // Check if Alarm is valid
        $newAlarm->isValid();
        
        $q = 'INSERT INTO alarms_definition (adtid, adPriority, adMessage, adTrigger, adTriggerB, adTriggerN';
        $q .= ', adTriggerR, adAutoAck, adFeedbackNotACK, adHWAck)';
        $q .= ' VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?);';
        
        $stmt = $this->dbConn->prepare($q);
        
        $stmt->bindValue(1, $newAlarm->getTag()->getId(), ParameterType::INTEGER);
        $stmt->bindValue(2, $newAlarm->getPriority(), ParameterType::INTEGER);
        $stmt->bindValue(3, $newAlarm->getMessage(), ParameterType::STRING);
        $stmt->bindValue(4, $newAlarm->getTrigger(), ParameterType::INTEGER);
        $stmt->bindValue(5, $newAlarm->getTriggerBin(), ParameterType::INTEGER);
        $stmt->bindValue(6, $newAlarm->getTriggerNumeric(), ParameterType::INTEGER);
        $stmt->bindValue(7, $newAlarm->getTriggerReal(), ParameterType::STRING);
        $stmt->bindValue(8, $newAlarm->isAutoAck(), ParameterType::INTEGER);
        
        if ($newAlarm->isFeedbackNotAck()) {
            $stmt->bindValue(9, $newAlarm->getFeedbackNotAck()->getId(), ParameterType::INTEGER);
        } else {
            $stmt->bindValue(9, null, ParameterType::NULL);
        }
        
        if ($newAlarm->isHWAck()) {
            $stmt->bindValue(10, $newAlarm->getHWAck()->getId(), ParameterType::INTEGER);
        } else {
            $stmt->bindValue(10, null, ParameterType::NULL);
        }
        
        try {
            if (!$stmt->execute()) {
                throw new Exception("Error during execute sql add query!");
            }
        } catch (UniqueConstraintViolationException $ex) {
            throw new AppException(
                "Alarm with tag: " . $newAlarm->getTag()->getName() . " exist in DB!",
                AppException::ALARM_TAG_EXIST
            );
        }
    }
    
    /**
     * Edit Alarm
     *
     * @param Alarm $newAlarm Alarm to edit
     */
    public function editAlarm(Alarm $newAlarm)
    {
        // Check if Alarm is valid
        $newAlarm->isValid(true);
        
        $q = 'UPDATE alarms_definition SET adtid = ?, adPriority = ?, adMessage = ?, adTrigger = ?, adTriggerB = ?';
        $q .= ', adTriggerN = ?, adTriggerR = ?, adAutoAck = ?, adFeedbackNotACK = ?, adHWAck = ?';
        $q .= ' WHERE adid = ?;';
        
        $stmt = $this->dbConn->prepare($q);
        
        $stmt->bindValue(1, $newAlarm->getTag()->getId(), ParameterType::INTEGER);
        $stmt->bindValue(2, $newAlarm->getPriority(), ParameterType::INTEGER);
        $stmt->bindValue(3, $newAlarm->getMessage(), ParameterType::STRING);
        $stmt->bindValue(4, $newAlarm->getTrigger(), ParameterType::INTEGER);
        $stmt->bindValue(5, $newAlarm->getTriggerBin(), ParameterType::INTEGER);
        $stmt->bindValue(6, $newAlarm->getTriggerNumeric(), ParameterType::INTEGER);
        $stmt->bindValue(7, $newAlarm->getTriggerReal(), ParameterType::STRING);
        $stmt->bindValue(8, $newAlarm->isAutoAck(), ParameterType::INTEGER);
        
        if ($newAlarm->isFeedbackNotAck()) {
            $stmt->bindValue(9, $newAlarm->getFeedbackNotAck()->getId(), ParameterType::INTEGER);
        } else {
            $stmt->bindValue(9, null, ParameterType::NULL);
        }
        
        if ($newAlarm->isHWAck()) {
            $stmt->bindValue(10, $newAlarm->getHWAck()->getId(), ParameterType::INTEGER);
        } else {
            $stmt->bindValue(10, null, ParameterType::NULL);
        }
        
        $stmt->bindValue(11, $newAlarm->getId(), ParameterType::INTEGER);
        
        try {
            if (!$stmt->execute()) {
                throw new Exception("Error during execute sql add query!");
            }
        } catch (UniqueConstraintViolationException $ex) {
            throw new AppException(
                "Alarm with tag: " . $newAlarm->getTag()->getName() . " exist in DB!",
                AppException::ALARM_TAG_EXIST
            );
        }
    }
    
    /**
     * Delete Alarm
     *
     * @param int $alarmId Alarm identifier
     */
    public function deleteAlarm(int $alarmId)
    {
        // Check alarm identifier
        Alarm::checkId($alarmId);
        
        $statement = $this->dbConn->prepare('DELETE FROM alarms_definition WHERE adid = ?;');
        $statement->bindValue(1, $alarmId, ParameterType::INTEGER);
        
        // Remove alarm history data
        $this->deleteArchivedAlarm($alarmId);
        
        if (!$statement->execute()) {
            throw new Exception("Error during execute delete query!");
        }
    }
    
    /**
     * Delete Archived Alarms
     *
     * @param int $alarmId Alarm definition identifier
     */
    public function deleteArchivedAlarm(int $alarmId = 0)
    {
        $sql = 'DELETE FROM alarms_history';
        
        if ($alarmId > 0) {
            $sql .= ' WHERE ahadid = ?';
        }
        
        $sql .= ';';
        
        $statement = $this->dbConn->prepare($sql);
        $statement->bindValue(1, $alarmId, ParameterType::INTEGER);
                
        if (!$statement->execute()) {
            throw new Exception("Error during execute delete query!");
        }
    }
    
    /**
     * Enable alarm
     *
     * @param int $alarmId Alarm identifier
     * @param bool $en Enable flag
     */
    public function enableAlarm(int $alarmId, bool $en = true)
    {
        // Check alarm identifier
        Alarm::checkId($alarmId);
        
        $stmt = $this->dbConn->prepare('UPDATE alarms_definition SET adEnable = ? WHERE adid = ?;');
        
        $stmt->bindValue(1, (($en) ? (1) : (0)), ParameterType::INTEGER);
        $stmt->bindValue(2, $alarmId, ParameterType::INTEGER);
        
        if (!$stmt->execute()) {
            throw new Exception("Error during execute sql update query!");
        }
    }
}
