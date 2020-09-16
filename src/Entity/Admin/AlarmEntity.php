<?php

namespace App\Entity\Admin;

use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Admin\Tag;
use App\Entity\Admin\TagType;
use App\Entity\Admin\Alarm;

/**
 * Class represents Alarm object for Forms (add/edit)
 *
 * @author Mateusz Mirosławski
 */
class AlarmEntity
{
    /**
     * Alarm identifier
     *
     * @Assert\PositiveOrZero
     */
    private $adid;
    
    /**
     * Tag object name
     *
     * @Assert\NotBlank()
     * @Assert\Length(max=50)
     */
    private $adTagName;
    
    /**
     * Alarm Priority
     *
     * @Assert\NotBlank()
     * @Assert\Type("integer")
     * @Assert\Range(
     *      min = 1,
     *      max = 5
     * )
     */
    private $adPriority;
    
    /**
     * Alarm message
     *
     * @Assert\NotBlank()
     * @Assert\Length(max=200)
     */
    private $adMessage;
    
    /**
     * Alarm trigger
     *
     * @Assert\NotBlank()
     * @Assert\Type("integer")
     * @Assert\Range(
     *      min = 1,
     *      max = 7
     * )
     */
    private $adTrigger;
    
    /**
     * Alarm trigger binary
     *
     * @Assert\NotBlank()
     * @Assert\Type("integer")
     * @Assert\Range(
     *      min = 0,
     *      max = 1
     * )
     */
    private $adTriggerB;
    
    /**
     * Alarm trigger numeric
     *
     * @Assert\NotBlank()
     * @Assert\Type("integer")
     */
    private $adTriggerN;
    
    /**
     * Alarm trigger real
     *
     * @Assert\NotBlank()
     * @Assert\Type("real")
     */
    private $adTriggerR;
    
    /**
     * Alarm automatic acknowledgment flag
     *
     * @Assert\NotBlank()
     * @Assert\Type("integer")
     * @Assert\Range(
     *      min = 0,
     *      max = 1
     * )
     */
    private $adAutoAck;
    
    /**
     * Feedback Tag informs controller that alarm is not acknowledgment
     *
     * @Assert\Length(max=50)
     */
    private $adFeedbackNotACK;
    
    /**
     * Tag HW alarm acknowledgment
     *
     * @Assert\Length(max=50)
     */
    private $adHWAck;
    
    public function __construct()
    {
        $this->adid = 0;
        $this->adTagName = '';
        $this->adPriority = 0;
        $this->adMessage = '';
        $this->adTrigger = 1;
        $this->adTriggerB = 0;
        $this->adTriggerN = 0;
        $this->adTriggerR = 0;
        $this->adAutoAck = 0;
        $this->adFeedbackNotACK = '';
        $this->adHWAck = '';
    }
    
    public function getadid()
    {
        return $this->adid;
    }
    
    public function setadid($id)
    {
        $this->adid = $id;
    }
    
    public function getadTagName()
    {
        return $this->adTagName;
    }
    
    public function setadTagName($nm)
    {
        $this->adTagName = $nm;
    }
    
    public function getadPriority()
    {
        return $this->adPriority;
    }
    
    public function setadPriority($prio)
    {
        $this->adPriority = $prio;
    }
    
    public function getadMessage()
    {
        return $this->adMessage;
    }
    
    public function setadMessage($msg)
    {
        $this->adMessage = $msg;
    }
    
    public function getadTrigger()
    {
        return $this->adTrigger;
    }
    
    public function setadTrigger($tr)
    {
        $this->adTrigger = $tr;
    }
    
    public function getadTriggerB()
    {
        return $this->adTriggerB;
    }
    
    public function setadTriggerB($tr)
    {
        $this->adTriggerB = $tr;
    }
    
    public function getadTriggerN()
    {
        return $this->adTriggerN;
    }
    
    public function setadTriggerN($tr)
    {
        $this->adTriggerN = $tr;
    }
    
    public function getadTriggerR()
    {
        return $this->adTriggerR;
    }
    
    public function setadTriggerR($tr)
    {
        $this->adTriggerR = $tr;
    }
    
    public function getadAutoAck()
    {
        return $this->adAutoAck;
    }
    
    public function setadAutoAck($flag)
    {
        $this->adAutoAck = $flag;
    }
    
    public function getadFeedbackNotACK()
    {
        return $this->adFeedbackNotACK;
    }
    
    public function setadFeedbackNotACK($val)
    {
        $this->adFeedbackNotACK = $val;
    }
    
    public function getadHWAck()
    {
        return $this->adHWAck;
    }
    
    public function setadHWAck($val)
    {
        $this->adHWAck = $val;
    }
    
    /**
     * Get full Alarm object
     *
     * @param Tag $tag Tag connected to the alarm
     * @param Tag $feedbackTag Feedback Tag
     * @param Tag $HWAckTag HW acknowledgment Tag
     * @return Alarm Alarm object
     */
    public function getFullAlarmObject(Tag $tag, Tag $feedbackTag = null, Tag $HWAckTag = null): Alarm
    {
        // Check if Tag object is valid
        $tag->isValid(true);
        
        $alarm = new Alarm($tag);
        $alarm->setId($this->adid);
        $alarm->setPriority($this->adPriority);
        $alarm->setMessage($this->adMessage);
        
        $alarm->setTrigger($this->adTrigger);
        
        $alarm->setTriggerBin((($this->adTriggerB == 0) ? (false) : (true)));
        $alarm->setTriggerNumeric($this->adTriggerN);
        $alarm->setTriggerReal($this->adTriggerR);
        $alarm->setAutoAck((($this->adAutoAck == 0) ? (false) : (true)));
        
        // Check Feedback tag
        if ($feedbackTag instanceof Tag) {
            $feedbackTag->isValid(true, true, TagType::BIT);
            $alarm->setFeedbackNotAck($feedbackTag);
        }
        
        // Check HW ack tag
        if ($HWAckTag instanceof Tag) {
            $HWAckTag->isValid(true, true, TagType::BIT);
            $alarm->setHWAck($HWAckTag);
        }
        
        return $alarm;
    }
    
    /**
     * Initialize from alarm object
     *
     * @param Alarm $alarm Alarm object
     */
    public function initFromAlarmObject(Alarm $alarm)
    {
        // Check if alarm is valid
        $alarm->isValid(true);
        
        $this->adid = $alarm->getId();
        $this->adTagName = $alarm->getTag()->getName();
        $this->adPriority = $alarm->getPriority();
        $this->adMessage = $alarm->getMessage();
        $this->adTrigger = $alarm->getTrigger();
        $this->adTriggerB = $alarm->getTriggerBin();
        $this->adTriggerN = $alarm->getTriggerNumeric();
        $this->adTriggerR = $alarm->getTriggerReal();
        $this->adAutoAck = $alarm->isAutoAck();
        
        if ($alarm->isFeedbackNotAck()) {
            $this->adFeedbackNotACK = $alarm->getFeedbackNotAck()->getName();
        } else {
            $this->adFeedbackNotACK = '';
        }
        
        if ($alarm->isHWAck()) {
            $this->adHWAck = $alarm->getHWAck()->getName();
        } else {
            $this->adHWAck = '';
        }
    }
}
