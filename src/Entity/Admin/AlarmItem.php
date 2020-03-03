<?php

namespace App\Entity\Admin;

use Symfony\Component\Config\Definition\Exception\Exception;
use App\Entity\Admin\Alarm;

/**
 * Class represents active/archived alarm
 *
 * @author Mateusz Mirosławski
 */
class AlarmItem {
    
    /**
     * Pending alarm identifier
     */
    private $apid;
    
    /**
     * Alarm definition identifier
     */
    private $apadid;
    
    /**
     * Alarm priority
     */
    private $alarmPriority;
    
    /**
     * Alarm message
     */
    private $alarmMessage;
    
    /**
     * Alarm active flag
     */
    private $apActive;
    
    /**
     * Alarm acknowledgment flag
     */
    private $apAck;
    
    /**
     * Alarm on timestamp
     */
    private $apOnTimestamp;
    
    /**
     * Alarm off timestamp
     */
    private $apOffTimestamp;
    
    /**
     * Alarm acknowledgment timestamp
     */
    private $apAckTimestamp;
    
    /**
     * Default constructor
     */
    public function __construct() {
        
        $this->apid = 0;
        $this->apadid = 0;
        $this->alarmPriority = 0;
        $this->alarmMessage = '';
        $this->apActive = false;
        $this->apAck = false;
        $this->apOnTimestamp = null;
        $this->apOffTimestamp = null;
        $this->apAckTimestamp = null;
    }
    
    /**
     * Get Pending alarm identifier
     * 
     * @return int Pending alarm identifier
     */
    public function getId(): int {
        
        return $this->apid;
    }
    
    /**
     * Set Pending alarm identifier
     * 
     * @param int $id Pending alarm identifier
     */
    public function setId(int $id) {
        
        Alarm::checkId($id);
        
        $this->apid = $id;
    }
    
    /**
     * Get Alarm definition identifier
     * 
     * @return int Alarm definition identifier
     */
    public function getDefinitionId(): int {
        
        return $this->apadid;
    }
    
    /**
     * Set Alarm definition identifier
     * 
     * @param int $id Alarm definition identifier
     */
    public function setDefinitionId(int $id) {
        
        Alarm::checkId($id);
        
        $this->apadid = $id;
    }
    
    /**
     * Get Alarm priority
     * 
     * @return int Alarm priority
     */
    public function getPriority(): int {
        
        return $this->alarmPriority;
    }
    
    /**
     * Set Alarm priority
     * 
     * @param int $priority Alarm priority
     */
    public function setPriority(int $priority) {
        
        Alarm::checkPriority($priority);
        
        $this->alarmPriority = $priority;
    }
    
    /**
     * Get Alarm message
     * 
     * @return string Alarm message
     */
    public function getMessage(): string {
        
        return $this->alarmMessage;
    }
    
    /**
     * Set Alarm message
     * 
     * @param string $msg Alarm message
     */
    public function setMessage(string $msg) {
        
        // Check value
        Alarm::checkMessage($msg);
        
        $this->alarmMessage = $msg;
    }
    
    /**
     * Get Alarm active flag
     * 
     * @return bool Alarm active flag
     */
    public function isActive(): bool {
        
        return $this->apActive;
    }
    
    /**
     * Set Alarm active flag
     * 
     * @param bool $val Alarm active flag
     */
    public function setActive(bool $val) {
        
        $this->apActive = $val;
    }
    
    /**
     * Get Alarm acknowledgment flag
     * 
     * @return bool Alarm acknowledgment flag
     */
    public function isAck(): bool {
        
        return $this->apAck;
    }
    
    /**
     * Set Alarm acknowledgment flag
     * 
     * @param bool $val Alarm acknowledgment flag
     */
    public function setAck(bool $val) {
        
        $this->apAck = $val;
    }
    
    /**
     * Get Alarm on timestamp
     * 
     * @return string Alarm on timestamp
     * @throws Exception
     */
    public function getOnTimestamp(): string {
        
        if ($this->apOnTimestamp===null) {
            throw new Exception("Alarm on timestamp is NULL");
        }
        
        return $this->apOnTimestamp->format('Y-m-d H:i:s');
    }
    
    /**
     * Set Alarm on timestamp
     * 
     * @param string $timeStamp Alarm on timestamp
     * @throws Exception
     */
    public function setOnTimestamp(string $timeStamp) {
        
        if (trim($timeStamp) == false) {
            throw new Exception("Alarm on timestamp can not be empty");
        }
        
        // Create DateTime
        $dt = \DateTime::createFromFormat('Y-m-d H:i:s', $timeStamp);
        
        if ($dt===false) {
            throw new Exception("Alarm on timestamp wrong format");
        }
        
        $this->apOnTimestamp = $dt;
    }
    
    /**
     * Check if off timestamp exist
     * 
     * @return bool True if off timestamp exist
     */
    public function isOffTimestamp(): bool {
        
        $ret = false;
        
        if ($this->apOffTimestamp instanceof \DateTime) {
            $ret = true;
        }
        
        return $ret;
    }
    
    /**
     * Get Alarm off timestamp
     * 
     * @return string Alarm off timestamp
     * @throws Exception
     */
    public function getOffTimestamp(): string {
        
        if ($this->apOffTimestamp===null) {
            throw new Exception("Alarm off timestamp is NULL");
        }
        
        return $this->apOffTimestamp->format('Y-m-d H:i:s');
    }
    
    /**
     * Set Alarm off timestamp
     * 
     * @param string $timeStamp Alarm off timestamp
     * @throws Exception
     */
    public function setOffTimestamp(string $timeStamp) {
        
        if (trim($timeStamp) == false) {
            throw new Exception("Alarm off timestamp can not be empty");
        }
        
        // Create DateTime
        $dt = \DateTime::createFromFormat('Y-m-d H:i:s', $timeStamp);
        
        if ($dt===false) {
            throw new Exception("Alarm off timestamp wrong format");
        }
        
        $this->apOffTimestamp = $dt;
    }
    
    /**
     * Get Alarm ack timestamp
     * 
     * @return string Alarm ack timestamp
     * @throws Exception
     */
    public function getAckTimestamp(): string {
        
        if ($this->apAckTimestamp===null) {
            throw new Exception("Alarm ack timestamp is NULL");
        }
        
        return $this->apAckTimestamp->format('Y-m-d H:i:s');
    }
    
    /**
     * Set Alarm ack timestamp
     * 
     * @param string $timeStamp Alarm ack timestamp
     * @throws Exception
     */
    public function setAckTimestamp(string $timeStamp) {
        
        if (trim($timeStamp) == false) {
            throw new Exception("Alarm ack timestamp can not be empty");
        }
        
        // Create DateTime
        $dt = \DateTime::createFromFormat('Y-m-d H:i:s', $timeStamp);
        
        if ($dt===false) {
            throw new Exception("Alarm ack timestamp wrong format");
        }
        
        $this->apAckTimestamp = $dt;
    }
}
