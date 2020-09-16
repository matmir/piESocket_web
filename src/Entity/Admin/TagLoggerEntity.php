<?php

namespace App\Entity\Admin;

use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Admin\Tag;
use App\Entity\Admin\TagLogger;

/**
 * Class represents Tag logger object for Forms (add/edit)
 *
 * @author Mateusz Mirosławski
 */
class TagLoggerEntity
{
    /**
     * Tag logger identifier
     *
     * @Assert\PositiveOrZero
     */
    private $ltid;
    
    /**
     * Tag object name
     *
     * @Assert\NotBlank()
     * @Assert\Length(max=50)
     */
    private $ltTagName;
    
    /**
     * Tag logger interval object
     *
     * @Assert\NotBlank()
     * @Assert\Type("integer")
     * @Assert\Range(
     *      min = 1,
     *      max = 6
     * )
     */
    private $ltInterval;
    
    /**
     * Tag logger interval seconds
     *
     * @Assert\Type("integer")
     * @Assert\GreaterThanOrEqual(0)
     */
    private $ltIntervalS;
    
    public function __construct()
    {
        $this->ltid = 0;
        $this->ltTagName = '';
        $this->ltInterval = 0;
        $this->ltIntervalS = 0;
    }
    
    public function getltid()
    {
        return $this->ltid;
    }
    
    public function setltid($id)
    {
        $this->ltid = $id;
    }
    
    public function getltTagName()
    {
        return $this->ltTagName;
    }
    
    public function setltTagName($tagName)
    {
        $this->ltTagName = $tagName;
    }
    
    public function getltInterval()
    {
        return $this->ltInterval;
    }
    
    public function setltInterval($interval)
    {
        $this->ltInterval = $interval;
    }
    
    public function getltIntervalS()
    {
        return $this->ltIntervalS;
    }
    
    public function setltIntervalS($intervalS)
    {
        $this->ltIntervalS = $intervalS;
    }
    
    /**
     * Get full Tag logger object
     *
     * @param Tag $tag Tag object
     * @return TagLogger Tag logger object
     */
    public function getFullLoggerObject(Tag $tag): TagLogger
    {
        // Check if Tag object is valid
        $tag->isValid(true);
        
        // Logger
        $logger = new TagLogger($tag);
        $logger->setId($this->ltid);
        $logger->setInterval($this->ltInterval);
        $logger->setIntervalS($this->ltIntervalS);
        
        return $logger;
    }
    
    /**
     * Initialize from Logger object
     *
     * @param TagLogger $logger Tag logger object
     */
    public function initFromLoggerObject(TagLogger $logger)
    {
        // Check if logger is valid
        $logger->isValid(true);
        
        $this->ltid = $logger->getId();
        $this->ltTagName = $logger->getTag()->getName();
        $this->ltInterval = $logger->getInterval();
        $this->ltIntervalS = $logger->getIntervalS();
    }
}
