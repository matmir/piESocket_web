<?php

namespace App\Entity\Admin;

use Symfony\Component\Validator\Constraints as Assert;

use App\Entity\Admin\Tag;

/**
 * Class represents Tag object for Forms (add/edit)
 * 
 * @author Mateusz Mirosławski
 */
class TagEntity {
    
    /**
     * Tag identifier
     * 
     * @Assert\PositiveOrZero
     */
    private $tid;
    
    /**
     * Driver connection identifier
     * 
     * @Assert\NotBlank()
     * @Assert\Positive
     */
    private $tConnId;
    
    /**
     * Tag name
     * 
     * @Assert\NotBlank()
     * @Assert\Regex(
     *     pattern="/[^A-Za-z0-9_]/",
     *     match=false,
     *     message="Tag name contain invalid characters"
     * )
     * @Assert\Length(max=50)
     */
    private $tName;
    
    /**
     * Tag type
     * 
     * @Assert\NotBlank()
     * @Assert\Type("integer")
     * @Assert\Range(
     *      min = 1,
     *      max = 6
     * )
     */
    private $tType;
    
    /**
     * Tag area
     * 
     * @Assert\NotBlank()
     * @Assert\Type("integer")
     * @Assert\Range(
     *      min = 1,
     *      max = 3
     * )
     */
    private $tArea;
    
    /**
     * Tag byte address
     * 
     * @Assert\NotBlank()
     * @Assert\Type("integer")
     * @Assert\Range(
     *      min = 0,
     *      max = 10000
     * )
     */
    private $tByteAddress;
    
    /**
     * Tag bit address
     * 
     * @Assert\NotBlank()
     * @Assert\Type("integer")
     * @Assert\Range(
     *      min = 0,
     *      max = 7
     * )
     */
    private $tBitAddress;
    
    /**
     * Read access role
     * 
     * @Assert\NotBlank()
     * @Assert\Length(max=20)
     */
    private $tReadAccess;
    
    /**
     * Write access role
     * 
     * @Assert\NotBlank()
     * @Assert\Length(max=20)
     */
    private $tWriteAccess;
    
    public function __construct() {
        
        $this->tid = 0;
        $this->tConnId = 0;
        $this->tName = '';
        $this->tType = 0;
        $this->tArea = 0;
        $this->tBitAddress = 0;
        $this->tByteAddress = 0;
        $this->tReadAccess = 'ROLE_USER';
        $this->tWriteAccess = 'ROLE_USER';
    }
    
    public function gettid() {
        
        return $this->tid;
    }
    
    public function settid($id) {
        
        $this->tid = $id;
    }
    
    public function gettConnId() {
        
        return $this->tConnId;
    }
    
    public function settConnId($id) {
        
        $this->tConnId = $id;
    }
    
    public function gettName() {
        
        return $this->tName;
    }
    
    public function settName($nm) {
        
        $this->tName = $nm;
    }
    
    public function gettType() {
        
        return $this->tType;
    }
    
    public function settType(int $type) {
        
        $this->tType = $type;
    }
    
    public function gettArea() {
        
        return $this->tArea;
    }
    
    public function settArea($area) {
        
        $this->tArea = $area;
    }
    
    public function gettByteAddress() {
        
        return $this->tByteAddress;
    }
    
    public function settByteAddress($byteA) {
        
        $this->tByteAddress = $byteA;
    }
    
    public function gettBitAddress() {
        
        return $this->tBitAddress;
    }
    
    public function settBitAddress($bitA) {
        
        $this->tBitAddress = $bitA;
    }
    
    public function gettReadAccess() {
        
        return $this->tReadAccess;
    }
    
    public function settReadAccess($readA) {
        
        $this->tReadAccess = $readA;
    }
    
    public function gettWriteAccess() {
        
        return $this->tWriteAccess;
    }
    
    public function settWriteAccess($writeA) {
        
        $this->tWriteAccess = $writeA;
    }
    
    /**
     * Get Tag object
     * 
     * @return Tag Tag object
     */
    public function getFullTagObject() {
        
        // New tag
        $tag = new Tag();
        $tag->setId($this->tid);
        $tag->setConnId($this->tConnId);
        $tag->setName($this->tName);
        $tag->setType($this->tType);
        $tag->setArea($this->tArea);
        $tag->setByteAddress($this->tByteAddress);
        $tag->setBitAddress($this->tBitAddress);
        $tag->setReadAccess($this->tReadAccess);
        $tag->setWriteAccess($this->tWriteAccess);
        
        return $tag;
    }
    
    /**
     * Initialize from Tag object
     * 
     * @param Tag $tag Tag object
     */
    public function initFromTagObject(Tag $tag) {
        
        // Check if Tag object is valid
        $tag->isValid(true);
        
        $this->tid = $tag->getId();
        $this->tConnId = $tag->getConnId();
        $this->tName = $tag->getName();
        $this->tType = $tag->getType();
        $this->tArea = $tag->getArea();
        $this->tByteAddress = $tag->getByteAddress();
        $this->tBitAddress = $tag->getBitAddress();
        $this->tReadAccess = $tag->getReadAccess();
        $this->tWriteAccess = $tag->getWriteAccess();
    }
}
