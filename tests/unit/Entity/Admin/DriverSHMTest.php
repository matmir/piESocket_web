<?php

namespace App\Tests\Entity\Admin;

use App\Entity\Admin\DriverSHM;
use PHPUnit\Framework\TestCase;

/**
 * Unit test for ConfigDriverSHM class
 *
 * @author Mateusz Mirosławski
 */
class DriverSHMTest extends TestCase {
    
    /**
     * Test Default constructor
     */
    public function testDefaultConstructor() {
        
        $cfg = new DriverSHM();
        
        $this->assertEquals('shm_segment', $cfg->getSegmentName());
    }
    
    /**
     * Test setSegmentName method
     */
    public function testSetSegmentName() {
        
        $cfg = new DriverSHM();
        $cfg->setSegmentName('test');
        
        $this->assertEquals('test', $cfg->getSegmentName());
    }
}