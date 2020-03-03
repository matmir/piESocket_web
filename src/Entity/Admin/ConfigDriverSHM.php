<?php

namespace App\Entity\Admin;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class for SHM driver configuration
 * 
 * @author Mateusz Mirosławski
 */
class ConfigDriverSHM {
    
    /**
     * SHM segment name
     * 
     * @Assert\NotBlank()
     * @Assert\Length(max=200)
     */
    private $segmentName;
    
    /**
     * Max byte address
     */
    const maxProcessAddress = 5000;
    
    /**
     * Default constructor
     */
    public function __construct() {
                
        $this->segmentName = 'shm_segment';
    }
    
    /**
     * Get SHM segment name
     * 
     * @return string SHM segment name
     */
    public function getSegmentName() {
        
        return $this->segmentName;
    }
    
    /**
     * Set SHM segment name
     * 
     * @param string $val SHM segment name
     */
    public function setSegmentName(string $val) {
        
        $this->segmentName = $val;
    }
    
}
