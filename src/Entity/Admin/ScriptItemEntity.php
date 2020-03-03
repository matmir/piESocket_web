<?php

namespace App\Entity\Admin;

use Symfony\Component\Validator\Constraints as Assert;

use App\Entity\Admin\Tag;
use App\Entity\Admin\TagType;
use App\Entity\Admin\ScriptItem;

/**
 * Class represents Script item object for Forms (add/edit)
 * 
 * @author Mateusz Mirosławski
 */
class ScriptItemEntity {
    
    /**
     * Script identifier
     * 
     * @Assert\PositiveOrZero
     */
    private $scid;
    
    /**
     * Tag object name
     * 
     * @Assert\NotBlank()
     * @Assert\Length(max=50)
     */
    private $scTagName;
    
    /**
     * Script name
     * 
     * @Assert\NotBlank()
     * @Assert\Length(max=50)
     */
    private $scName;
    
    /**
     * Tag informs controller that script is running (optional)
     * 
     * @Assert\Length(max=50)
     */
    private $scFeedbackRun;
    
    public function __construct() {
        
        $this->scid = 0;
        $this->scTagName = '';
        $this->scName = '';
        $this->scFeedbackRun = '';
    }
    
    public function getscid() {
        
        return $this->scid;
    }
    
    public function setscid($id) {
        
        $this->scid = $id;
    }
    
    public function getscTagName() {
        
        return $this->scTagName;
    }
    
    public function setscTagName($nm) {
        
        $this->scTagName = $nm;
    }
    
    public function getscName() {
        
        return $this->scName;
    }
    
    public function setscName($nm) {
        
        $this->scName = $nm;
    }
    
    public function getscFeedbackRun() {
        
        return $this->scFeedbackRun;
    }
    
    public function setscFeedbackRun($nm) {
        
        $this->scFeedbackRun = $nm;
    }
    
    /**
     * Get full Script item object
     * 
     * @param Tag $tag Tag connected to the script item
     * @param Tag $feedbackTag Feedback Tag
     * @return Alarm Alarm object
     */
    public function getFullScriptObject(Tag $tag, Tag $feedbackTag=null): ScriptItem {
        
        // Check if Tag object is valid
        $tag->isValid(true, true, TagType::Bit);
        
        $script = new ScriptItem($tag);
        $script->setId($this->scid);
        $script->setName($this->scName);
        
        // Check Feedback tag
        if ($feedbackTag instanceof Tag) {
            $feedbackTag->isValid(true, true, TagType::Bit);
            $script->setFeedbackRun($feedbackTag);
        }
        
        return $script;
    }
    
    /**
     * Initialize from script item object
     * 
     * @param ScriptItem $script Script item object
     */
    public function initFromScriptObject(ScriptItem $script) {
        
        // Check if script is valid
        $script->isValid(true);
        
        $this->scid = $script->getId();
        $this->scTagName = $script->getTag()->getName();
        $this->scName = $script->getName();
        
        if ($script->isFeedbackRun()) {
            $this->scFeedbackRun = $script->getFeedbackRun()->getName();
        } else {
            $this->scFeedbackRun = '';
        }
    }
}
