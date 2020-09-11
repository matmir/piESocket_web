<?php

namespace App\Tests\Entity\Admin;

use App\Entity\Admin\DriverSHM;
use PHPUnit\Framework\TestCase;

/**
 * Unit test for DriverSHM class
 *
 * @author Mateusz Mirosławski
 */
class DriverSHMTest extends TestCase {
    
    /**
     * Test Default constructor
     */
    public function testDefaultConstructor() {
        
        $cfg = new DriverSHM();
        
        $this->assertEquals(0, $cfg->getId());
        $this->assertEquals('shm_segment', $cfg->getSegmentName());
    }
    
    /**
     * Test setId method
     */
    public function testSetId() {
        
        $cfg = new DriverSHM();
        $cfg->setId(65);
        
        $this->assertEquals(65, $cfg->getId());
        $this->assertEquals('shm_segment', $cfg->getSegmentName());
    }
    
    public function testSetIdWrong1() {
        
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('SHM driver identifier wrong value');
        
        $cfg = new DriverSHM();
        $cfg->setId(-65);
    }
    
    /**
     * Test setSegmentName method
     */
    public function testSetSegmentName() {
        
        $cfg = new DriverSHM();
        $cfg->setSegmentName('test');
        
        $this->assertEquals(0, $cfg->getId());
        $this->assertEquals('test', $cfg->getSegmentName());
    }
    
    public function testSetSegmentNameWrong1() {
        
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('SHM segment name can not be empty');
        
        $cfg = new DriverSHM();
        $cfg->setSegmentName(' ');
    }
    
    /**
     * Test isValid method
     */
    public function testIsValidWithoutID() {
        
        $cfg = new DriverSHM();
        
        $this->assertTrue($cfg->isValid());
    }
    
    public function testIsValidWithID() {
        
        $cfg = new DriverSHM();
        $cfg->setId(56);
        
        $this->assertTrue($cfg->isValid(true));
    }
}