<?php

namespace App\Tests\Entity\Admin;

use App\Entity\Admin\ConfigDriverSHM;
use PHPUnit\Framework\TestCase;

/**
 * Unit test for ConfigDriverSHM class
 *
 * @author Mateusz Mirosławski
 */
class ConfigDriverSHMTest extends TestCase {
    
    /**
     * Test Default constructor
     */
    public function testDefaultConstructor() {
        
        $cfg = new ConfigDriverSHM();
        
        $this->assertEquals('shm_segment', $cfg->getSegmentName());
    }
    
    /**
     * Test setSegmentName method
     */
    public function testSetSegmentName() {
        
        $cfg = new ConfigDriverSHM();
        $cfg->setSegmentName('test');
        
        $this->assertEquals('test', $cfg->getSegmentName());
    }
}