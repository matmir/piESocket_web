<?php

namespace App\Tests\Entity\Admin;

use App\Entity\Admin\DriverModbus;
use App\Entity\Admin\DriverModbusMode;
use PHPUnit\Framework\TestCase;

/**
 * Unit test for DriverModbus class
 *
 * @author Mateusz Mirosławski
 */
class DriverModbusTest extends TestCase
{
    /**
     * Test Default constructor
     */
    public function testDefaultConstructor()
    {
        $cfg = new DriverModbus();
        
        $this->assertEquals(0, $cfg->getId());
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
        $this->assertEquals(2, $cfg->getMaxByteAddress());
        $this->assertFalse($cfg->useSlaveIdInTCP());
    }
    
    /**
     * Test setId method
     */
    public function testSetId()
    {
        $cfg = new DriverModbus();
        $cfg->setId(65);
        
        $this->assertEquals(65, $cfg->getId());
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
        $this->assertEquals(2, $cfg->getMaxByteAddress());
        $this->assertFalse($cfg->useSlaveIdInTCP());
    }
    
    public function testSetIdWrong1()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('Modbus driver identifier wrong value');
        
        $cfg = new DriverModbus();
        $cfg->setId(-3);
    }
    
    /**
     * Test setMode method
     */
    public function testSetMode()
    {
        $cfg = new DriverModbus();
        $cfg->setMode(DriverModbusMode::RTU);
        
        $this->assertEquals(DriverModbusMode::RTU, $cfg->getMode());
        
        $this->assertEquals(0, $cfg->getId());
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
        $this->assertEquals(2, $cfg->getMaxByteAddress());
        $this->assertFalse($cfg->useSlaveIdInTCP());
    }
    
    public function testSetModeWrong1()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('DriverModbusMode::check: Invalid modbus driver mode identifier');
        
        $cfg = new DriverModbus();
        $cfg->setMode(3);
    }
    
    /**
     * Test setDriverPolling method
     */
    public function testSetDriverPolling()
    {
        $cfg = new DriverModbus();
        $cfg->setDriverPolling(105);
        
        $this->assertEquals(105, $cfg->getDriverPolling());
        
        $this->assertEquals(0, $cfg->getId());
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
        $this->assertEquals(2, $cfg->getMaxByteAddress());
        $this->assertFalse($cfg->useSlaveIdInTCP());
    }
    
    public function testSetDriverPollingWrong1()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('Modbus driver polling interval wrong value');
        
        $cfg = new DriverModbus();
        $cfg->setDriverPolling(0);
    }
    
    /**
     * Test setRegisterCount method
     */
    public function testSetRegisterCount()
    {
        $cfg = new DriverModbus();
        $cfg->setRegisterCount(10);
        
        $this->assertEquals(10, $cfg->getRegisterCount());
        $this->assertEquals(20, $cfg->getMaxByteAddress());
        
        $this->assertEquals(0, $cfg->getId());
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
        $this->assertFalse($cfg->useSlaveIdInTCP());
    }
    
    public function testSetRegisterCountWrong1()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('Modbus register count wrong value');
        
        $cfg = new DriverModbus();
        $cfg->setRegisterCount(0);
    }
    
    /**
     * Test setRTU_baud method
     */
    public function testSetRTUBaud()
    {
        $cfg = new DriverModbus();
        $cfg->setRTUbaud(11800);
        
        $this->assertEquals(11800, $cfg->getRTUbaud());
        
        $this->assertEquals(0, $cfg->getId());
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
        $this->assertEquals(2, $cfg->getMaxByteAddress());
        $this->assertFalse($cfg->useSlaveIdInTCP());
    }
    
    public function testSetRTUBaudWrong1()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('Modbus baud rate wrong value');
        
        $cfg = new DriverModbus();
        $cfg->setRTUbaud(0);
    }
    
    /**
     * Test setRTU_dataBit method
     */
    public function testSetRTUDataBit()
    {
        $cfg = new DriverModbus();
        $cfg->setRTUdataBit(5);
        
        $this->assertEquals(5, $cfg->getRTUdataBit());
        
        $this->assertEquals(0, $cfg->getId());
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
        $this->assertEquals(2, $cfg->getMaxByteAddress());
        $this->assertFalse($cfg->useSlaveIdInTCP());
    }
    
    public function testSetRTUDataBitWrong1()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('Modbus data bit wrong value');
        
        $cfg = new DriverModbus();
        $cfg->setRTUdataBit(9);
    }
    
    /**
     * Test setRTU_parity method
     */
    public function testSetRTUParity()
    {
        $cfg = new DriverModbus();
        $cfg->setRTUparity('O');
        
        $this->assertEquals('O', $cfg->getRTUparity());
        
        $this->assertEquals(0, $cfg->getId());
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
        $this->assertEquals(2, $cfg->getMaxByteAddress());
        $this->assertFalse($cfg->useSlaveIdInTCP());
    }
    
    public function testSetRTUParityWrong1()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('Modbus RTU parity wrong value');
        
        $cfg = new DriverModbus();
        $cfg->setRTUparity('d');
    }
    
    /**
     * Test setRTU_port method
     */
    public function testSetRTUPort()
    {
        $cfg = new DriverModbus();
        $cfg->setRTUport('/dev/ttyACM2');
        
        $this->assertEquals('/dev/ttyACM2', $cfg->getRTUport());
        
        $this->assertEquals(0, $cfg->getId());
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
        $this->assertEquals(2, $cfg->getMaxByteAddress());
        $this->assertFalse($cfg->useSlaveIdInTCP());
    }
    
    public function testSetRTUPortWrong1()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('Modbus RTU port can not be empty');
        
        $cfg = new DriverModbus();
        $cfg->setRTUport(' ');
    }
    
    /**
     * Test setRTU_stopBit method
     */
    public function testSetRTUStopBit()
    {
        $cfg = new DriverModbus();
        $cfg->setRTUstopBit(2);
        
        $this->assertEquals(2, $cfg->getRTUstopBit());
        
        $this->assertEquals(0, $cfg->getId());
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
        $this->assertEquals(2, $cfg->getMaxByteAddress());
        $this->assertFalse($cfg->useSlaveIdInTCP());
    }
    
    public function testSetRTUStopBitWrong1()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('Modbus stop bit wrong value');
        
        $cfg = new DriverModbus();
        $cfg->setRTUstopBit(3);
    }
    
    /**
     * Test setSlaveID method
     */
    public function testSetSlaveID()
    {
        $cfg = new DriverModbus();
        $cfg->setSlaveID(34);
        
        $this->assertEquals(34, $cfg->getSlaveID());
        
        $this->assertEquals(0, $cfg->getId());
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
        $this->assertEquals(2, $cfg->getMaxByteAddress());
        $this->assertFalse($cfg->useSlaveIdInTCP());
    }
    
    public function testSetSlaveIDWrong1()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('Modbus slave ID wrong value');
        
        $cfg = new DriverModbus();
        $cfg->setSlaveID(248);
    }
    
    /**
     * Test setTCP_addr method
     */
    public function testSetTCPAddr()
    {
        $cfg = new DriverModbus();
        $cfg->setTCPaddr('127.0.0.1');
        
        $this->assertEquals('127.0.0.1', $cfg->getTCPaddr());
        
        $this->assertEquals(0, $cfg->getId());
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
        $this->assertEquals(2, $cfg->getMaxByteAddress());
        $this->assertFalse($cfg->useSlaveIdInTCP());
    }
    
    public function testSetTCPAddrWrong1()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('Modbus slave IP address can not be empty');
        
        $cfg = new DriverModbus();
        $cfg->setTCPaddr(' ');
    }
    
    /**
     * Test setTCP_port method
     */
    public function testSetTCPPort()
    {
        $cfg = new DriverModbus();
        $cfg->setTCPport(45);
        
        $this->assertEquals(45, $cfg->getTCPport());
        
        $this->assertEquals(0, $cfg->getId());
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
        $this->assertEquals(2, $cfg->getMaxByteAddress());
        $this->assertFalse($cfg->useSlaveIdInTCP());
    }
    
    public function testSetTCPPortWrong1()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('Modbus TCP port wrong value');
        
        $cfg = new DriverModbus();
        $cfg->setTCPport(0);
    }
    
    /**
     * Test setSlaveIdUsageInTCP method
     */
    public function testSetSlaveIdUsageInTCP()
    {
        $cfg = new DriverModbus();
        $cfg->setSlaveIdUsageInTCP(true);
        
        $this->assertEquals(0, $cfg->getId());
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
        $this->assertEquals(2, $cfg->getMaxByteAddress());
        $this->assertTrue($cfg->useSlaveIdInTCP());
    }
    
    /**
     * Test isValid method
     */
    public function testIsValidWithoutID()
    {
        $cfg = new DriverModbus();
        
        $this->assertTrue($cfg->isValid());
    }
    
    public function testIsValidWithID()
    {
        $cfg = new DriverModbus();
        $cfg->setId(56);
        
        $this->assertTrue($cfg->isValid(true));
    }
    
    public function testGetModeName1()
    {
        $cfg = new DriverModbus();
        
        $this->assertEquals("TCP", DriverModbusMode::getName($cfg->getMode()));
    }
    
    public function testGetModeName2()
    {
        $cfg = new DriverModbus();
        $cfg->setMode(DriverModbusMode::RTU);
        
        $this->assertEquals("RTU", DriverModbusMode::getName($cfg->getMode()));
    }
    
    public function testGetModeName3()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('DriverModbusMode::getName: Invalid modbus driver mode identifier');
        
        DriverModbusMode::getName(8);
    }
}
