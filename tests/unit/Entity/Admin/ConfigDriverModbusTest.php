<?php

namespace App\Tests\Entity\Admin;

use App\Entity\Admin\ConfigDriverModbus;
use PHPUnit\Framework\TestCase;

/**
 * Unit test for ConfigDriverModbus class
 *
 * @author Mateusz Mirosławski
 */
class ConfigDriverModbusTest extends TestCase {
    
    /**
     * Test Default constructor
     */
    public function testDefaultConstructor() {
        
        $cfg = new ConfigDriverModbus();
        
        $this->assertEquals('192.168.0.5', $cfg->getIpAddress());
        $this->assertEquals(502, $cfg->getPort());
        $this->assertEquals(1, $cfg->getRegisterCount());
        $this->assertEquals(50, $cfg->getDriverPolling());
        $this->assertEquals(2, $cfg->getSlaveID());
    }
    
    /**
     * Test setIpAddress method
     */
    public function testSetIpAddress() {
        
        $cfg = new ConfigDriverModbus();
        $cfg->setIpAddress('127.0.0.1');
        
        $this->assertEquals('127.0.0.1', $cfg->getIpAddress());
        
        $this->assertEquals(502, $cfg->getPort());
        $this->assertEquals(1, $cfg->getRegisterCount());
        $this->assertEquals(50, $cfg->getDriverPolling());
        $this->assertEquals(2, $cfg->getSlaveID());
    }
    
    /**
     * Test setPort method
     */
    public function testSetPort() {
        
        $cfg = new ConfigDriverModbus();
        $cfg->setPort(45);
        
        $this->assertEquals(45, $cfg->getPort());
        
        $this->assertEquals('192.168.0.5', $cfg->getIpAddress());
        $this->assertEquals(1, $cfg->getRegisterCount());
        $this->assertEquals(50, $cfg->getDriverPolling());
        $this->assertEquals(2, $cfg->getSlaveID());
    }
    
    /**
     * Test setSlaveID method
     */
    public function testSetSlaveID() {
        
        $cfg = new ConfigDriverModbus();
        $cfg->setSlaveID(34);
        
        $this->assertEquals(34, $cfg->getSlaveID());
        
        $this->assertEquals('192.168.0.5', $cfg->getIpAddress());
        $this->assertEquals(502, $cfg->getPort());
        $this->assertEquals(1, $cfg->getRegisterCount());
        $this->assertEquals(50, $cfg->getDriverPolling());
    }
    
    /**
     * Test setRegisterCount method
     */
    public function testSetRegisterCount() {
        
        $cfg = new ConfigDriverModbus();
        $cfg->setRegisterCount(10);
        
        $this->assertEquals(10, $cfg->getRegisterCount());
        
        $this->assertEquals('192.168.0.5', $cfg->getIpAddress());
        $this->assertEquals(502, $cfg->getPort());
        $this->assertEquals(50, $cfg->getDriverPolling());
        $this->assertEquals(2, $cfg->getSlaveID());
    }
    
    /**
     * Test setDriverPolling method
     */
    public function testSetDriverPolling() {
        
        $cfg = new ConfigDriverModbus();
        $cfg->setDriverPolling(105);
        
        $this->assertEquals(105, $cfg->getDriverPolling());
        
        $this->assertEquals('192.168.0.5', $cfg->getIpAddress());
        $this->assertEquals(502, $cfg->getPort());
        $this->assertEquals(1, $cfg->getRegisterCount());
        $this->assertEquals(2, $cfg->getSlaveID());
    }
}
