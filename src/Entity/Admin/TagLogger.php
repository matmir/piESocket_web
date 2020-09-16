<?php

namespace App\Entity\Admin;

use Symfony\Component\Config\Definition\Exception\Exception;
use App\Entity\Admin\Tag;
use App\Entity\Admin\TagLoggerInterval;

/**
 * Class represents tag logger
 *
 * @author Mateusz Mirosławski
 */
class TagLogger
{
       
    /**
     * Tag logger identifier
     *
     */
    private $ltid;
    
    /**
     * Tag object
     *
     */
    private $ltTag;
    
    /**
     * Tag logger interval object
     *
     */
    private $ltInterval;
    
    /**
     * Tag logger interval seconds
     *
     */
    private $ltIntervalS;
    
    
    /**
     * Tag last log time
     *
     */
    private $ltLastUPD;
    
    /**
     * Tag last value
     *
     */
    private $ltLastValue;
    
    /**
     * Tag logger enabled flag
     *
     */
    private $ltEnable;
    
    /**
     * Default constructor
     *
     * @param Tag $tag Tag object
     */
    public function __construct(Tag $tag)
    {
        // Check Tag
        $tag->isValid(true);
        
        $this->ltid = 0;
        $this->ltTag = $tag;
        $this->ltInterval = TagLoggerInterval::I_1S;
        $this->ltIntervalS = 0;
        $this->ltLastUPD = null;
        $this->ltLastValue = 0;
        $this->ltEnable = false;
    }
    
    /**
     * Get Tag logger identifier
     *
     * @return int Tag logger identifier
     */
    public function getId(): int
    {
        return $this->ltid;
    }
    
    /**
     * Check Tag logger identifier
     *
     * @param int $id Tag logger identifier
     * @return bool True if Tag logger identifier is valid
     * @throws Exception if Tag logger identifier is invalid
     */
    public static function checkId(int $id): bool
    {
        // Check values
        if ($id < 0) {
            throw new Exception("Tag logger identifier wrong value");
        }
        
        return true;
    }
    
    /**
     * Set Tag logger identifier
     *
     * @param int $id Tag logger identifier
     */
    public function setId(int $id)
    {
        $this->checkId($id);
        
        $this->ltid = $id;
    }
    
    /**
     * Get Tag object
     *
     * @return Tag Tag object
     */
    public function getTag(): Tag
    {
        return $this->ltTag;
    }
    
    /**
     * Set Tag object
     *
     * @param Tag $tag Tag object
     */
    public function setTag(Tag $tag)
    {
        // Check tag object
        $tag->isValid(true);
        
        $this->ltTag = $tag;
    }
    
    /**
     * Get Tag logger interval identifier
     *
     * @return int Tag logger interval identifier
     */
    public function getInterval(): int
    {
        return $this->ltInterval;
    }
    
    /**
     * Set Tag logger interval identifier
     *
     * @param int $interval Tag logger interval identifier
     */
    public function setInterval(int $interval)
    {
        TagLoggerInterval::check($interval);
        
        $this->ltInterval = $interval;
    }
    
    /**
     * Get Tag logger interval seconds
     *
     * @return int Tag logger interval seconds
     */
    public function getIntervalS(): int
    {
        return $this->ltIntervalS;
    }
    
    /**
     * Check Tag logger interval seconds
     *
     * @param int $sec Tag logger interval seconds
     * @return bool True if Tag logger interval seconds are valid
     * @throws Exception If Tag logger interval seconds are invalid
     */
    private function checkIntervalS(int $sec): bool
    {
        if ($sec < 1 && $this->ltInterval == TagLoggerInterval::I_XS) {
            throw new Exception("Wrong Tag logger interval seconds value");
        }
        
        return true;
    }
    
    /**
     * Set Tag logger interval seconds
     *
     * @param int $sec Tag logger interval seconds
     */
    public function setIntervalS(int $sec)
    {
        // Check value
        $this->checkIntervalS($sec);
        
        $this->ltIntervalS = $sec;
    }
    
    /**
     * Get Tag logger last update time
     *
     * @return string Tag logger last update time
     */
    public function getLastLogTime(): string
    {
        return ($this->ltLastUPD === null) ? ('none') : ($this->ltLastUPD);
    }
    
    /**
     * Check Tag logger last update time
     *
     * @param string $lastTime Tag logger last update time
     * @return bool True if Tag logger last update time is valid
     * @throws Exception If Tag logger last update time is invalid
     */
    private function checkLastLogTime(string $lastTime)
    {
        if (trim($lastTime) == false) {
            throw new Exception("Tag logger update time can not be empty");
        }
        
        return true;
    }
    
    /**
     * Set Tag logger last update time
     *
     * @param string $lastTime Tag logger last update time
     */
    public function setLastLogTime(string $lastTime)
    {
        // Check time
        $this->checkLastLogTime($lastTime);
        
        $this->ltLastUPD = $lastTime;
    }
    
    /**
     * Get Tag last value
     *
     * @return Tag last value
     */
    public function getLastValue(bool $convert = false)
    {
        $ret = $this->ltLastValue;
        
        // Convert return value
        if ($convert) {
            switch (TagType::getName($this->ltTag->getType())) {
                case TagType::N_BIT:
                    $ret = (bool) $this->ltLastValue;
                    break;
                case TagType::N_REAL:
                    $ret = floatval($this->ltLastValue);
                    break;
                default:
                    $ret = (int) $this->ltLastValue;
            }
        }
        
        return $ret;
    }
    
    /**
     * Check Tag last value
     *
     * @param type $value Tag last value
     * @return bool True if Tag last value is valid
     * @throws Exception If Tag last value is invalid
     */
    private function checkLastValue($value)
    {
        if (!is_numeric($value)) {
            throw new Exception("Tag logger last value need to be numeric");
        }
        
        return true;
    }
    
    /**
     * Set Tag last value
     *
     * @param numeric $value Tag last value
     */
    public function setLastValue($value)
    {
        $this->checkLastValue($value);
        
        $this->ltLastValue = $value;
    }
    
    /**
     * Get Enabled flag
     *
     * @return bool Enabled flag
     */
    public function isEnabled(): bool
    {
        return $this->ltEnable;
    }
    
    /**
     * Set Enabled flag
     *
     * @param bool $en Enabled flag
     */
    public function setEnabled(bool $en)
    {
        $this->ltEnable = $en;
    }
    
    /**
     * Check if Tag logger object is valid
     *
     * @param bool $checkID Flag validating tag logger identifier
     * @return bool True if Tag logger is valid
     * @throws Exception Throws when Tag is invalid
     */
    public function isValid(bool $checkID = false): bool
    {
        // Check identifier
        if ($checkID) {
            $this->checkId($this->ltid);
        }
        
        // Check Tag
        $this->ltTag->isValid(true);
        
        // Check Interval
        TagLoggerInterval::check($this->ltInterval);
        
        // Check interval seconds
        $this->checkIntervalS($this->ltIntervalS);
        
        // Check last log time
        $this->checkLastLogTime(($this->ltLastUPD === null) ? ('none') : ($this->ltLastUPD));
        
        // Check last value
        $this->checkLastValue($this->ltLastValue);
        
        return true;
    }
}
