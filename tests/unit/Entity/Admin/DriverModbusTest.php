<?php

namespace App\Tests\Entity\Admin;

use App\Entity\Admin\DriverModbus;
use App\Entity\Admin\DriverModbusMode;
use PHPUnit\Framework\TestCase;

/**
 * Unit test for ConfigDriverModbus class
 *
 * @author Mateusz Mirosławski
 */
class DriverModbusTest extends TestCase {
    
    /**
     * Test Default constructor
     */
    public function testDefaultConstructor() {
        
        $cfg = new DriverModbus();
        
        $this->assertEquals(DriverModbusMode::TCP, $cfg->getMode());
        $this->assertEquals('192.168.0.5', $cfg->getTCPaddr());
        $this->assertEquals(502, $cfg->getTCPport());
        $this->assertEquals('/dev/ttyACM1', $cfg->getRTUport());
        $this->assertEquals(57600, $cfg->getRTUbaud());
        $this->assertEquals('N', $cfg->getRTUparity());
        $this->assertEquals(8, $cfg->getRTUdataBit());
        $this->assertEquals(1, $cfg->getRTUstopBit());
        $this->assertEquals(1, $cfg->getRegisterCount());
        $this->assertEquals(50, $cfg->getDriverPolling());
        $this->assertEquals(2, $cfg->getSlaveID());
    }
    
    /**
     * Test setMode method
     */
    public function testSetMode() {
        
        $cfg = new DriverModbus();
        $cfg->setMode(DriverModbusMode::RTU);
        
        $this->assertEquals(DriverModbusMode::RTU, $cfg->getMode());
        
        $this->assertEquals('192.168.0.5', $cfg->getTCPaddr());
        $this->assertEquals(502, $cfg->getTCPport());
        $this->assertEquals('/dev/ttyACM1', $cfg->getRTUport());
        $this->assertEquals(57600, $cfg->getRTUbaud());
        $this->assertEquals('N', $cfg->getRTUparity());
        $this->assertEquals(8, $cfg->getRTUdataBit());
        $this->assertEquals(1, $cfg->getRTUstopBit());
        $this->assertEquals(1, $cfg->getRegisterCount());
        $this->assertEquals(50, $cfg->getDriverPolling());
        $this->assertEquals(2, $cfg->getSlaveID());
    }
    
    /**
     * Test setTCP_addr method
     */
    public function testSetTCPAddr() {
        
        $cfg = new DriverModbus();
        $cfg->setTCPaddr('127.0.0.1');
        
        $this->assertEquals('127.0.0.1', $cfg->getTCPaddr());
        
        $this->assertEquals(DriverModbusMode::TCP, $cfg->getMode());
        $this->assertEquals(502, $cfg->getTCPport());
        $this->assertEquals('/dev/ttyACM1', $cfg->getRTUport());
        $this->assertEquals(57600, $cfg->getRTUbaud());
        $this->assertEquals('N', $cfg->getRTUparity());
        $this->assertEquals(8, $cfg->getRTUdataBit());
        $this->assertEquals(1, $cfg->getRTUstopBit());
        $this->assertEquals(1, $cfg->getRegisterCount());
        $this->assertEquals(50, $cfg->getDriverPolling());
        $this->assertEquals(2, $cfg->getSlaveID());
    }
    
    /**
     * Test setTCP_port method
     */
    public function testSetTCPPort() {
        
        $cfg = new DriverModbus();
        $cfg->setTCPport(45);
        
        $this->assertEquals(45, $cfg->getTCPport());
        
        $this->assertEquals(DriverModbusMode::TCP, $cfg->getMode());
        $this->assertEquals('192.168.0.5', $cfg->getTCPaddr());
        $this->assertEquals('/dev/ttyACM1', $cfg->getRTUport());
        $this->assertEquals(57600, $cfg->getRTUbaud());
        $this->assertEquals('N', $cfg->getRTUparity());
        $this->assertEquals(8, $cfg->getRTUdataBit());
        $this->assertEquals(1, $cfg->getRTUstopBit());
        $this->assertEquals(1, $cfg->getRegisterCount());
        $this->assertEquals(50, $cfg->getDriverPolling());
        $this->assertEquals(2, $cfg->getSlaveID());
    }
    
    /**
     * Test setRTU_port method
     */
    public function testSetRTUPort() {
        
        $cfg = new DriverModbus();
        $cfg->setRTUport('/dev/ttyACM2');
        
        $this->assertEquals('/dev/ttyACM2', $cfg->getRTUport());
        
        $this->assertEquals(DriverModbusMode::TCP, $cfg->getMode());
        $this->assertEquals('192.168.0.5', $cfg->getTCPaddr());
        $this->assertEquals(502, $cfg->getTCPport());
        $this->assertEquals(57600, $cfg->getRTUbaud());
        $this->assertEquals('N', $cfg->getRTUparity());
        $this->assertEquals(8, $cfg->getRTUdataBit());
        $this->assertEquals(1, $cfg->getRTUstopBit());
        $this->assertEquals(1, $cfg->getRegisterCount());
        $this->assertEquals(50, $cfg->getDriverPolling());
        $this->assertEquals(2, $cfg->getSlaveID());
    }
    
    /**
     * Test setRTU_baud method
     */
    public function testSetRTUBaud() {
        
        $cfg = new DriverModbus();
        $cfg->setRTUbaud(11800);
        
        $this->assertEquals(11800, $cfg->getRTUbaud());
        
        $this->assertEquals(DriverModbusMode::TCP, $cfg->getMode());
        $this->assertEquals('192.168.0.5', $cfg->getTCPaddr());
        $this->assertEquals(502, $cfg->getTCPport());
        $this->assertEquals('/dev/ttyACM1', $cfg->getRTUport());
        $this->assertEquals('N', $cfg->getRTUparity());
        $this->assertEquals(8, $cfg->getRTUdataBit());
        $this->assertEquals(1, $cfg->getRTUstopBit());
        $this->assertEquals(1, $cfg->getRegisterCount());
        $this->assertEquals(50, $cfg->getDriverPolling());
        $this->assertEquals(2, $cfg->getSlaveID());
    }
    
    /**
     * Test setRTU_parity method
     */
    public function testSetRTUParity() {
        
        $cfg = new DriverModbus();
        $cfg->setRTUparity('O');
        
        $this->assertEquals('O', $cfg->getRTUparity());
        
        $this->assertEquals(DriverModbusMode::TCP, $cfg->getMode());
        $this->assertEquals('192.168.0.5', $cfg->getTCPaddr());
        $this->assertEquals(502, $cfg->getTCPport());
        $this->assertEquals('/dev/ttyACM1', $cfg->getRTUport());
        $this->assertEquals(57600, $cfg->getRTUbaud());
        $this->assertEquals(8, $cfg->getRTUdataBit());
        $this->assertEquals(1, $cfg->getRTUstopBit());
        $this->assertEquals(1, $cfg->getRegisterCount());
        $this->assertEquals(50, $cfg->getDriverPolling());
        $this->assertEquals(2, $cfg->getSlaveID());
    }
    
    /**
     * Test setRTU_dataBit method
     */
    public function testSetRTUDataBit() {
        
        $cfg = new DriverModbus();
        $cfg->setRTUdataBit(5);
        
        $this->assertEquals(5, $cfg->getRTUdataBit());
        
        $this->assertEquals(DriverModbusMode::TCP, $cfg->getMode());
        $this->assertEquals('192.168.0.5', $cfg->getTCPaddr());
        $this->assertEquals(502, $cfg->getTCPport());
        $this->assertEquals('/dev/ttyACM1', $cfg->getRTUport());
        $this->assertEquals(57600, $cfg->getRTUbaud());
        $this->assertEquals('N', $cfg->getRTUparity());
        $this->assertEquals(1, $cfg->getRTUstopBit());
        $this->assertEquals(1, $cfg->getRegisterCount());
        $this->assertEquals(50, $cfg->getDriverPolling());
        $this->assertEquals(2, $cfg->getSlaveID());
    }
    
    /**
     * Test setRTU_stopBit method
     */
    public function testSetRTUStopBit() {
        
        $cfg = new DriverModbus();
        $cfg->setRTUstopBit(2);
        
        $this->assertEquals(2, $cfg->getRTUstopBit());
        
        $this->assertEquals(DriverModbusMode::TCP, $cfg->getMode());
        $this->assertEquals('192.168.0.5', $cfg->getTCPaddr());
        $this->assertEquals(502, $cfg->getTCPport());
        $this->assertEquals('/dev/ttyACM1', $cfg->getRTUport());
        $this->assertEquals(57600, $cfg->getRTUbaud());
        $this->assertEquals('N', $cfg->getRTUparity());
        $this->assertEquals(8, $cfg->getRTUdataBit());
        $this->assertEquals(1, $cfg->getRegisterCount());
        $this->assertEquals(50, $cfg->getDriverPolling());
        $this->assertEquals(2, $cfg->getSlaveID());
    }
    
    /**
     * Test setSlaveID method
     */
    public function testSetSlaveID() {
        
        $cfg = new DriverModbus();
        $cfg->setSlaveID(34);
        
        $this->assertEquals(34, $cfg->getSlaveID());
        
        $this->assertEquals(DriverModbusMode::TCP, $cfg->getMode());
        $this->assertEquals('192.168.0.5', $cfg->getTCPaddr());
        $this->assertEquals(502, $cfg->getTCPport());
        $this->assertEquals('/dev/ttyACM1', $cfg->getRTUport());
        $this->assertEquals(57600, $cfg->getRTUbaud());
        $this->assertEquals('N', $cfg->getRTUparity());
        $this->assertEquals(8, $cfg->getRTUdataBit());
        $this->assertEquals(1, $cfg->getRTUstopBit());
        $this->assertEquals(1, $cfg->getRegisterCount());
        $this->assertEquals(50, $cfg->getDriverPolling());
    }
    
    /**
     * Test setRegisterCount method
     */
    public function testSetRegisterCount() {
        
        $cfg = new DriverModbus();
        $cfg->setRegisterCount(10);
        
        $this->assertEquals(10, $cfg->getRegisterCount());
        
        $this->assertEquals(DriverModbusMode::TCP, $cfg->getMode());
        $this->assertEquals('192.168.0.5', $cfg->getTCPaddr());
        $this->assertEquals(502, $cfg->getTCPport());
        $this->assertEquals('/dev/ttyACM1', $cfg->getRTUport());
        $this->assertEquals(57600, $cfg->getRTUbaud());
        $this->assertEquals('N', $cfg->getRTUparity());
        $this->assertEquals(8, $cfg->getRTUdataBit());
        $this->assertEquals(1, $cfg->getRTUstopBit());
        $this->assertEquals(50, $cfg->getDriverPolling());
        $this->assertEquals(2, $cfg->getSlaveID());
    }
    
    /**
     * Test setDriverPolling method
     */
    public function testSetDriverPolling() {
        
        $cfg = new DriverModbus();
        $cfg->setDriverPolling(105);
        
        $this->assertEquals(105, $cfg->getDriverPolling());
        
        $this->assertEquals(DriverModbusMode::TCP, $cfg->getMode());
        $this->assertEquals('192.168.0.5', $cfg->getTCPaddr());
        $this->assertEquals(502, $cfg->getTCPport());
        $this->assertEquals('/dev/ttyACM1', $cfg->getRTUport());
        $this->assertEquals(57600, $cfg->getRTUbaud());
        $this->assertEquals('N', $cfg->getRTUparity());
        $this->assertEquals(8, $cfg->getRTUdataBit());
        $this->assertEquals(1, $cfg->getRTUstopBit());
        $this->assertEquals(1, $cfg->getRegisterCount());
        $this->assertEquals(2, $cfg->getSlaveID());
    }
}
