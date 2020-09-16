<?php

namespace App\Entity\Admin;

use Symfony\Component\Config\Definition\Exception\Exception;
use App\Entity\Admin\Tag;
use App\Entity\Admin\TagType;
use App\Entity\Admin\AlarmTrigger;
use App\Entity\AppException;

/**
 * Class represents alarm definition
 *
 * @author Mateusz Mirosławski
 */
class Alarm
{
    /**
     * Alarm identifier
     */
    private $adid;
    
    /**
     * Tag object connected to the alarm
     */
    private $adTag;
    
    /**
     * Alarm priority (lower number -> most important)
     */
    private $adPriority;
    
    /**
     * Alarm message
     */
    private $adMessage;
    
    /**
     * Trigger object
     */
    private $adTrigger;
    
    /**
     * Tag binary value that triggers alarm
     */
    private $adTriggerB;
    
    /**
     * Tag numeric value that triggers alarm
     */
    private $adTriggerN;
    
    /**
     * Tag real value that triggers alarm
     */
    private $adTriggerR;
    
    /**
     * Alarm automatic acknowledgment
     */
    private $adAutoAck;
    
    /**
     * Alarm is active
     */
    private $adActive;
    
    /**
     * Alarm is pending
     */
    private $adPending;
    
    /**
     * Tag informs controller that alarm is not acknowledgment
     */
    private $adFeedbackNotACK;
    
    /**
     * Tag HW alarm acknowledgment
     */
    private $adHWAck;
    
    /**
     * Enable alarm
     */
    private $adEnable;
    
    /**
     * Default constructor
     *
     * @param Tag $tag Tag connected to the alarm
     */
    public function __construct(Tag $tag)
    {
        // Check Tag
        $tag->isValid(true);
        
        $this->adid = 0;
        $this->adTag = $tag;
        $this->adPriority = 0;
        $this->adMessage = 'none';
        $this->adTrigger = AlarmTrigger::TR_BIN;
        $this->adTriggerB = false;
        $this->adTriggerN = 0;
        $this->adTriggerR = 0;
        $this->adAutoAck = false;
        $this->adActive = false;
        $this->adPending = false;
        $this->adFeedbackNotACK = null;
        $this->adHWAck = null;
        $this->adEnable = false;
    }
    
    /**
     * Get Alarm identifier
     *
     * @return int Alarm identifier
     */
    public function getId(): int
    {
        return $this->adid;
    }
    
    /**
     * Check Alarm identifier
     *
     * @param int $id Alarm identifier
     * @return bool True if Alarm identifier is valid
     * @throws Exception if Alarm identifier is invalid
     */
    public static function checkId(int $id): bool
    {
        // Check values
        if ($id < 0) {
            throw new Exception("Alarm identifier wrong value");
        }
        
        return true;
    }
    
    /**
     * Set Alarm identifier
     *
     * @param int $id Alarm identifier
     */
    public function setId(int $id)
    {
        $this->checkId($id);
        
        $this->adid = $id;
    }
    
    /**
     * Get Tag connected to the alarm
     *
     * @return Tag Tag object
     */
    public function getTag(): Tag
    {
        return $this->adTag;
    }
    
    /**
     * Set Tag connected to the alarm
     *
     * @param Tag $tag Tag object
     */
    public function setTag(Tag $tag)
    {
        // Check tag object
        $tag->isValid(true);
        
        $this->adTag = $tag;
    }
    
    /**
     * Get Alarm priority
     *
     * @return int Alarm priority
     */
    public function getPriority(): int
    {
        return $this->adPriority;
    }
    
    /**
     * Check Alarm priority
     *
     * @param int $priority Alarm priority
     * @return bool True if Alarm priority is valid
     * @throws Exception If Alarm priority is invalid
     */
    public static function checkPriority(int $priority): bool
    {
        // Check values
        if ($priority < 0) {
            throw new Exception("Alarm priority wrong value");
        }
        
        return true;
    }
    
    /**
     * Set Alarm priority
     *
     * @param int $priority Alarm priority
     */
    public function setPriority(int $priority)
    {
        // Check value
        $this->checkPriority($priority);
        
        $this->adPriority = $priority;
    }
    
    /**
     * Get Alarm message
     *
     * @return string Alarm message
     */
    public function getMessage(): string
    {
        return $this->adMessage;
    }
    
    /**
     * Check Alarm message
     *
     * @param string $msg Alarm message
     * @return bool True if Alarm message is valid
     * @throws Exception if Alarm message is invalid
     */
    public static function checkMessage(string $msg): bool
    {
        if (trim($msg) == false) {
            throw new Exception("Alarm message can not be empty");
        }
        
        return true;
    }
    
    /**
     * Set Alarm message
     *
     * @param string $msg Alarm message
     */
    public function setMessage(string $msg)
    {
        // Check value
        $this->checkMessage($msg);
        
        $this->adMessage = $msg;
    }
    
    /**
     * Get Alarm trigger identifier
     *
     * @return int Alarm trigger identifier
     */
    public function getTrigger(): int
    {
        return $this->adTrigger;
    }
    
    /**
     * Set Alarm trigger
     *
     * @param int $trigger Alarm trigger identifier
     */
    public function setTrigger(int $trigger)
    {
        // Check object
        AlarmTrigger::check($trigger);
        
        $this->adTrigger = $trigger;
    }
    
    /**
     * Get Trigger binary value
     *
     * @return bool Trigger binary value
     */
    public function getTriggerBin(): bool
    {
        return $this->adTriggerB;
    }
    
    /**
     * Set Trigger binary value
     *
     * @param bool $val Trigger binary value
     */
    public function setTriggerBin(bool $val)
    {
        $this->adTriggerB = $val;
    }
    
    /**
     * Get Trigger numeric value
     *
     * @return int Trigger numeric value
     */
    public function getTriggerNumeric(): int
    {
        return $this->adTriggerN;
    }
    
    /**
     * Set Trigger numeric value
     *
     * @param int $val Trigger numeric value
     */
    public function setTriggerNumeric(int $val)
    {
        $this->adTriggerN = $val;
    }
    
    /**
     * Get Trigger real value
     *
     * @return float Trigger real value
     */
    public function getTriggerReal(): float
    {
        return $this->adTriggerR;
    }
    
    /**
     * Set Trigger real value
     *
     * @param float $val Trigger real value
     */
    public function setTriggerReal(float $val)
    {
        $this->adTriggerR = $val;
    }
    
    /**
     * Get Alarm automatic acknowledgment flag
     *
     * @return bool Alarm automatic acknowledgment flag
     */
    public function isAutoAck(): bool
    {
        return $this->adAutoAck;
    }
    
    /**
     * Set Alarm automatic acknowledgment flag
     *
     * @param bool $val Alarm automatic acknowledgment flag
     */
    public function setAutoAck(bool $val)
    {
        $this->adAutoAck = $val;
    }
    
    /**
     * Get Alarm active flag
     *
     * @return bool Alarm active flag
     */
    public function isActive(): bool
    {
        return $this->adActive;
    }
    
    /**
     * Set Alarm active flag
     *
     * @param bool $val Alarm active flag
     */
    public function setActive(bool $val)
    {
        $this->adActive = $val;
    }
    
    /**
     * Get Alarm pending flag
     *
     * @return bool Alarm pending flag
     */
    public function isPending(): bool
    {
        return $this->adPending;
    }
    
    /**
     * Set Alarm pending flag
     *
     * @param bool $val Alarm pending flag
     */
    public function setPending(bool $val)
    {
        $this->adPending = $val;
    }
    
    /**
     * Get feedback tag
     *
     * @return Tag object or null
     */
    public function getFeedbackNotAck()
    {
        return $this->adFeedbackNotACK;
    }
    
    /**
     * Check if feedback tag exist
     *
     * @return bool True if feedback Tag exist
     */
    public function isFeedbackNotAck(): bool
    {
        $ret = false;
        
        if ($this->adFeedbackNotACK instanceof Tag) {
            $ret = true;
        }
        
        return $ret;
    }
    
    /**
     * Check feedack tag
     *
     * @param Tag $feedback Feedback tag
     * @return bool True if feedback tag is valid
     * @throws Exception if feedback tag is invalid
     */
    private function checkFeedbackNotAck($feedback): bool
    {
        if ($feedback instanceof Tag) {
            $feedback->isValid(true, true, TagType::BIT);
        } elseif (!($feedback === null)) {
            throw new Exception("Feedback Tag is wrong type");
        }
        
        return true;
    }
    
    /**
     * Set feedback tag
     *
     * @param $feedback Tag object or null
     */
    public function setFeedbackNotAck($feedback = null)
    {
        // Check value
        $this->checkFeedbackNotAck($feedback);
        
        $this->adFeedbackNotACK = $feedback;
    }
    
    /**
     * Get HW acknowledgment Tag
     *
     * @return Tag object or null
     */
    public function getHWAck()
    {
        return $this->adHWAck;
    }
    
    /**
     * Check if HW acknowledgment tag exist
     *
     * @return bool True if HW acknowledgment Tag exist
     */
    public function isHWAck(): bool
    {
        $ret = false;
        
        if ($this->adHWAck instanceof Tag) {
            $ret = true;
        }
        
        return $ret;
    }
    
    /**
     * Check HW acknowledgment Tag
     *
     * @param Tag $hwAck HW acknowledgment Tag
     * @return bool true if acknowledgment Tag is valid
     * @throws Exception if acknowledgment Tag is invalid
     */
    private function checkHWAck($hwAck): bool
    {
        if ($hwAck instanceof Tag) {
            $hwAck->isValid(true, true, TagType::BIT);
        } elseif (!($hwAck === null)) {
            throw new Exception("HW acknowledgment Tag is wrong type");
        }
        
        return true;
    }
    
    /**
     * Set HW acknowledgment Tag
     *
     * @param $hwAck Tag object or null
     */
    public function setHWAck($hwAck = null)
    {
        // Check value
        $this->checkHWAck($hwAck);
        
        $this->adHWAck = $hwAck;
    }
    
    /**
     * Get Alarm enable flag
     *
     * @return bool Alarm enable flag
     */
    public function isEnabled(): bool
    {
        return $this->adEnable;
    }
    
    /**
     * Set Alarm enable flag
     *
     * @param bool $val Alarm enable flag
     */
    public function setEnable(bool $val)
    {
        $this->adEnable = $val;
    }
    
    /**
     * Check Alarm trigger type
     *
     * @throws AppException
     */
    private function checkAlarmTrigger()
    {
        // Tag is BIT type
        if ($this->adTag->getType() == TagType::BIT) {
            // Check trigger
            if ($this->adTrigger != AlarmTrigger::TR_BIN) {
                throw new AppException(
                    "Alarm trigger need to be BIT type",
                    AppException::ALARM_TRIGGER_WRONG_TYPE
                );
            }
        } else { // Tag is numeric type
            // Check trigger
            if ($this->adTrigger == AlarmTrigger::TR_BIN) {
                throw new AppException(
                    "Alarm trigger need to be numeric type",
                    AppException::ALARM_TRIGGER_WRONG_TYPE
                );
            }
        }
    }
    
    /**
     * Check if Alarm object is valid
     *
     * @param bool $checkID Flag validating alarm identifier
     * @return bool True if Alarm is valid
     * @throws Exception Throws when Alarm is invalid
     */
    public function isValid(bool $checkID = false): bool
    {
        // Check identifier
        if ($checkID) {
            $this->checkId($this->adid);
        }
        
        // Check Tag
        $this->adTag->isValid($checkID);
        
        // Check priority
        $this->checkPriority($this->adPriority);
        
        // Check Message
        $this->checkMessage($this->adMessage);
        
        // Check Trigger
        AlarmTrigger::check($this->adTrigger);
        
        // Check feedback Tag
        $this->checkFeedbackNotAck($this->adFeedbackNotACK);
        
        // Check HW ack Tag
        $this->checkHWAck($this->adHWAck);
        
        // Check Tag type and trigger type
        $this->checkAlarmTrigger();
        
        return true;
    }
}
