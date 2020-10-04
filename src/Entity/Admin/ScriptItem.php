<?php

namespace App\Entity\Admin;

use Symfony\Component\Config\Definition\Exception\Exception;
use App\Entity\Admin\Tag;
use App\Entity\Admin\TagType;

/**
 * Class represents script item
 *
 * @author Mateusz Mirosławski
 */
class ScriptItem
{
    /**
     * Script identifier
     */
    private int $scid;
    
    /**
     * Tag object
     */
    private ?Tag $scTag;
    
    /**
     * Script name
     */
    private string $scName;
    
    /**
     * Script run flag
     */
    private bool $scRun;
    
    /**
     * Script lock flag
     */
    private bool $scLock;
    
    /**
     * Tag informs controller that script is running (optional)
     */
    private ?Tag $scFeedbackRun;
    
    /**
     * Script is enabled
     */
    private bool $scEnable;
    
    /**
     * Default constructor
     *
     * @param Tag|null $tag Tag object
     * @param Tag|null $fb Feedback tag
     * @param int $id Script item identifier
     * @param string $sName Script name
     */
    public function __construct(
        ?Tag $tag = null,
        ?Tag $fb = null,
        int $id = 0,
        string $sName = ''
    ) {
        // Check Tag
        if ($tag instanceof Tag) {
            $tag->isValid(true, true, TagType::BIT);
        }
        
        // Check feedback Tag
        if ($fb instanceof Tag) {
            $fb->isValid(true, true, TagType::BIT);
        }
        
        $this->scid = $id;
        $this->scTag = $tag;
        $this->scName = $sName;
        $this->scRun = false;
        $this->scLock = false;
        $this->scFeedbackRun = $fb;
        $this->scEnable = false;
    }
    
    /**
     * Get Script identifier
     *
     * @return int Script identifier
     */
    public function getId(): int
    {
        return $this->scid;
    }
    
    /**
     * Check Script identifier
     *
     * @param int $id Script identifier
     * @return bool True if Script identifier is valid
     * @throws Exception if Script identifier is invalid
     */
    public static function checkId(int $id): bool
    {
        // Check values
        if ($id < 0) {
            throw new Exception('Script identifier wrong value');
        }
        
        return true;
    }
    
    /**
     * Set Script identifier
     *
     * @param int $id Script identifier
     */
    public function setId(int $id)
    {
        $this->checkId($id);
        
        $this->scid = $id;
    }
    
    /**
     * Get Tag object
     *
     * @return Tag Tag object
     */
    public function getTag(): Tag
    {
        return $this->scTag;
    }
    
    /**
     * Set Tag object
     *
     * @param Tag $tag Tag object
     */
    public function setTag(Tag $tag)
    {
        // Check tag object
        $tag->isValid(true, true, TagType::BIT);
        
        $this->scTag = $tag;
    }
    
    /**
     * Check if script tag exist
     *
     * @return bool True if script Tag exist
     */
    public function isTag(): bool
    {
        $ret = false;
        
        if ($this->scTag instanceof Tag) {
            $ret = true;
        }
        
        return $ret;
    }
    
    /**
     * Get Script name
     *
     * @return string Script name
     */
    public function getName(): string
    {
        return $this->scName;
    }
    
    /**
     * Check Script name
     *
     * @param string $msg Script name
     * @return bool True if Script name is valid
     * @throws Exception if Script name is invalid
     */
    public static function checkName(string $msg): bool
    {
        if (trim($msg) == false) {
            throw new Exception('Script name can not be empty');
        }
        
        return true;
    }
    
    /**
     * Set Script name
     *
     * @param string $sname Script name
     */
    public function setName(string $sname)
    {
        // Check value
        $this->checkName($sname);
        
        $this->scName = $sname;
    }
    
    /**
     * Get Script run flag
     *
     * @return bool Script run flag
     */
    public function isRunning(): bool
    {
        return $this->scRun;
    }
    
    /**
     * Set Script run flag
     *
     * @param bool $val Script run flag
     */
    public function setRun(bool $val)
    {
        $this->scRun = $val;
    }
    
    /**
     * Get Script lock flag
     *
     * @return bool Script lock flag
     */
    public function isLocked(): bool
    {
        return $this->scLock;
    }
    
    /**
     * Set Script lock flag
     *
     * @param bool $val Script lock flag
     */
    public function setLocked(bool $val)
    {
        $this->scLock = $val;
    }
    
    /**
     * Get feedback tag
     *
     * @return Tag object or null
     */
    public function getFeedbackRun(): ?Tag
    {
        return $this->scFeedbackRun;
    }
    
    /**
     * Check if feedback tag exist
     *
     * @return bool True if feedback Tag exist
     */
    public function isFeedbackRun(): bool
    {
        $ret = false;
        
        if ($this->scFeedbackRun instanceof Tag) {
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
    private function checkFeedbackRun($feedback): bool
    {
        if ($feedback instanceof Tag) {
            $feedback->isValid(true, true, TagType::BIT);
        } elseif (!($feedback === null)) {
            throw new Exception('Feedback Tag is wrong type');
        }
        
        return true;
    }
    
    /**
     * Set feedback tag
     *
     * @param $feedback Tag object or null
     */
    public function setFeedbackRun(?Tag $feedback = null)
    {
        // Check value
        $this->checkFeedbackRun($feedback);
        
        $this->scFeedbackRun = $feedback;
    }
    
    /**
     * Get Script enable flag
     *
     * @return bool Script enable flag
     */
    public function isEnabled(): bool
    {
        return $this->scEnable;
    }
    
    /**
     * Set Script enable flag
     *
     * @param bool $val Script enable flag
     */
    public function setEnabled(bool $val)
    {
        $this->scEnable = $val;
    }
    
    /**
     * Check if Script item object is valid
     *
     * @param bool $checkID Flag validating script item identifier
     * @return bool True if Script item is valid
     * @throws Exception Throws when Script item is invalid
     */
    public function isValid(bool $checkID = false): bool
    {
        // Check identifier
        if ($checkID) {
            $this->checkId($this->scid);
        }
        
        // Check Tag
        if ($this->isTag()) {
            $this->scTag->isValid(true, true, TagType::BIT);
        } else {
            throw new Exception('Missing Tag object');
        }
        
        // Check Name
        $this->checkName($this->scName);
        
        // Check feedback Tag
        $this->checkFeedbackRun($this->scFeedbackRun);
        
        return true;
    }
}
