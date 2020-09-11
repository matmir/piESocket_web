<?php

namespace App\Entity\Admin;

use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Component\Config\Definition\Exception\Exception;
use App\Entity\Admin\DriverConnectionEntity;
use App\Entity\Admin\DriverConnection;
use App\Entity\Admin\DriverType;
use App\Entity\Admin\DriverSHM;

/**
 * Class for SHM driver configuration
 * 
 * @author Mateusz Mirosławski
 */
class DriverSHMEntity extends DriverConnectionEntity {
    
    /**
     * SHM driver identifier
     * 
     * @Assert\PositiveOrZero
     */
    private $id;
    
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
        
        parent::__construct();
                
        $this->id = 0;
        $this->segmentName = 'shm_segment';
    }
    
    /**
     * Get SHM driver identifier
     * 
     * @return int SHM driver identifier
     */
    public function getId(): int {
        
        return $this->id;
    }
    
    /**
     * Set SHM driver identifier
     * 
     * @param int $id SHM driver identifier
     */
    public function setId(int $id) {
                
        $this->id = $id;
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
    
    /**
     * Get Driver connection object
     * 
     * @return DriverConnection Driver connection object
     */
    public function getFullConnectionObject(): DriverConnection {
        
        // New SHM
        $shm = new DriverSHM();
        $shm->setId($this->id);
        $shm->setSegmentName($this->segmentName);
        
        // New connection
        $conn = new DriverConnection();
        $conn->setId($this->connId);
        $conn->setName($this->connName);
        $conn->setType(DriverType::SHM);
        $conn->setShmConfig($shm);
        
        return $conn;
    }
    
    /**
     * Initialize from Driver connection object
     * 
     * @param DriverConnection $conn Driver connection object
     */
    public function initFromConnectionObject(DriverConnection $conn) {
        
        // Init parent
        parent::initFromConnectionObject($conn);
        
        if (!$conn->isShmConfig()) {
            throw new Exception("Missing SHM configuration in connection object");
        }
        
        $shm = $conn->getShmConfig();
        
        $this->id = $shm->getId();
        $this->segmentName = $shm->getSegmentName();
    }
}
