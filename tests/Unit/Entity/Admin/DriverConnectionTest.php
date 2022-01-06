<?php

namespace App\Tests\Unit\Entity\Admin;

use App\Entity\Admin\DriverConnection;
use App\Entity\Admin\DriverType;
use App\Entity\Admin\DriverModbus;
use App\Entity\Admin\DriverModbusMode;
use App\Entity\Admin\DriverSHM;
use PHPUnit\Framework\TestCase;

/**
 * Unit test for DriverConnection class
 *
 * @author Mateusz Mirosławski
 */
class DriverConnectionTest extends TestCase
{
    /**
     * Test Default constructor
     */
    public function testDefaultConstructor()
    {
        $cfg = new DriverConnection();
        
        $this->assertEquals(0, $cfg->getId());
        $this->assertEquals('conn1', $cfg->getName());
        $this->assertEquals(DriverType::SHM, $cfg->getType());
        $this->assertEquals(null, $cfg->getModbusConfig());
        $this->assertEquals(null, $cfg->getShmConfig());
        $this->assertFalse($cfg->isModbusConfig());
        $this->assertFalse($cfg->isShmConfig());
        $this->assertFalse($cfg->isEnabled());
    }
    
    /**
     * Test setId method
     */
    public function testSetId()
    {
        $cfg = new DriverConnection();
        $cfg->setId(65);
        
        $this->assertEquals(65, $cfg->getId());
        $this->assertEquals('conn1', $cfg->getName());
        $this->assertEquals(DriverType::SHM, $cfg->getType());
        $this->assertEquals(null, $cfg->getModbusConfig());
        $this->assertEquals(null, $cfg->getShmConfig());
        $this->assertFalse($cfg->isModbusConfig());
        $this->assertFalse($cfg->isShmConfig());
        $this->assertFalse($cfg->isEnabled());
    }
    
    public function testSetIdWrong1()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('Driver connection identifier wrong value');
        
        $cfg = new DriverConnection();
        $cfg->setId(-65);
    }
    
    /**
     * Test setName method
     */
    public function testSetName()
    {
        $cfg = new DriverConnection();
        $cfg->setName("dfgh");
        
        $this->assertEquals(0, $cfg->getId());
        $this->assertEquals('dfgh', $cfg->getName());
        $this->assertEquals(DriverType::SHM, $cfg->getType());
        $this->assertEquals(null, $cfg->getModbusConfig());
        $this->assertEquals(null, $cfg->getShmConfig());
        $this->assertFalse($cfg->isModbusConfig());
        $this->assertFalse($cfg->isShmConfig());
        $this->assertFalse($cfg->isEnabled());
    }
    
    public function testSetNameWrong1()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('Driver connection name can not be empty');
        
        $cfg = new DriverConnection();
        $cfg->setName(" ");
    }
    
    /**
     * Test setType method
     */
    public function testSetType()
    {
        $cfg = new DriverConnection();
        $cfg->setType(DriverType::MODBUS);
        
        $this->assertEquals(0, $cfg->getId());
        $this->assertEquals('conn1', $cfg->getName());
        $this->assertEquals(DriverType::MODBUS, $cfg->getType());
        $this->assertEquals(null, $cfg->getModbusConfig());
        $this->assertEquals(null, $cfg->getShmConfig());
        $this->assertFalse($cfg->isModbusConfig());
        $this->assertFalse($cfg->isShmConfig());
        $this->assertFalse($cfg->isEnabled());
    }
    
    public function testSetTypeWrong1()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('DriverType::check: Invalid driver type identifier');
        
        $cfg = new DriverConnection();
        $cfg->setType(4);
    }
    
    /**
     * Test setModbusConfig method
     */
    public function testSetModbusConfig()
    {
        // Modbus CFG
        $mb = new DriverModbus();
        $mb->setId(7);
        $mb->setMode(DriverModbusMode::RTU);
        $mb->setRegisterCount(10);
        $mb->setSlaveID(6);
        $mb->setDriverPolling(300);
        $mb->setRTUbaud(6000);
        $mb->setRTUdataBit(7);
        $mb->setRTUparity('O');
        $mb->setRTUport("portCOM5");
        $mb->setRTUstopBit(2);
        
        $cfg = new DriverConnection();
        $cfg->setType(DriverType::MODBUS);
        $cfg->setModbusConfig($mb);
        
        $this->assertEquals(0, $cfg->getId());
        $this->assertEquals('conn1', $cfg->getName());
        $this->assertEquals(DriverType::MODBUS, $cfg->getType());
        
        $mbc = $cfg->getModbusConfig();
        $this->assertEquals(7, $mbc->getId());
        $this->assertEquals(DriverModbusMode::RTU, $mbc->getMode());
        $this->assertEquals('192.168.0.5', $mbc->getTCPaddr());
        $this->assertEquals(502, $mbc->getTCPport());
        $this->assertEquals('portCOM5', $mbc->getRTUport());
        $this->assertEquals(6000, $mbc->getRTUbaud());
        $this->assertEquals('O', $mbc->getRTUparity());
        $this->assertEquals(7, $mbc->getRTUdataBit());
        $this->assertEquals(2, $mbc->getRTUstopBit());
        $this->assertEquals(10, $mbc->getRegisterCount());
        $this->assertEquals(300, $mbc->getDriverPolling());
        $this->assertEquals(6, $mbc->getSlaveID());
        $this->assertEquals(20, $mbc->getMaxByteAddress());
        
        $this->assertEquals(null, $cfg->getShmConfig());
        $this->assertTrue($cfg->isModbusConfig());
        $this->assertFalse($cfg->isShmConfig());
        $this->assertFalse($cfg->isEnabled());
    }
    
    /**
     * Test setShmConfig method
     */
    public function testSetShmConfig()
    {
        // SHM CFG
        $shm = new DriverSHM();
        $shm->setId(7);
        $shm->setSegmentName("seg_shm22");
        
        $cfg = new DriverConnection();
        $cfg->setType(DriverType::SHM);
        $cfg->setShmConfig($shm);
        
        $this->assertEquals(0, $cfg->getId());
        $this->assertEquals('conn1', $cfg->getName());
        $this->assertEquals(DriverType::SHM, $cfg->getType());
        
        $shmc = $cfg->getShmConfig();
        $this->assertEquals(7, $shmc->getId());
        $this->assertEquals("seg_shm22", $shmc->getSegmentName());
        
        $this->assertEquals(null, $cfg->getModbusConfig());
        $this->assertFalse($cfg->isModbusConfig());
        $this->assertTrue($cfg->isShmConfig());
        $this->assertFalse($cfg->isEnabled());
    }
    
    /**
     * Test setEnable method
     */
    public function testSetEnable()
    {
        $cfg = new DriverConnection();
        $cfg->setEnable(true);
        
        $this->assertEquals(0, $cfg->getId());
        $this->assertEquals('conn1', $cfg->getName());
        $this->assertEquals(DriverType::SHM, $cfg->getType());
        $this->assertEquals(null, $cfg->getModbusConfig());
        $this->assertEquals(null, $cfg->getShmConfig());
        $this->assertFalse($cfg->isModbusConfig());
        $this->assertFalse($cfg->isShmConfig());
        $this->assertTrue($cfg->isEnabled());
    }
    
    /**
     * Test isValid method
     */
    public function testIsValidWithoutID()
    {
        // Shm CFG
        $shm = new DriverSHM();
        
        $cfg = new DriverConnection();
        $cfg->setShmConfig($shm);
        
        $this->assertTrue($cfg->isValid());
    }
    
    public function testIsValidWithID()
    {
        // Shm CFG
        $shm = new DriverSHM();
        
        $cfg = new DriverConnection();
        $cfg->setId(44);
        $cfg->setShmConfig($shm);
        
        $this->assertTrue($cfg->isValid(true));
    }
    
    public function testIsValidWrong1()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('Missing driver configuration object');
        
        $cfg = new DriverConnection();
        $cfg->isValid();
    }
    
    public function testGetDriverName1()
    {
        $cfg = new DriverConnection();
        
        $this->assertEquals("SHM", DriverType::getName($cfg->getType()));
    }
    
    public function testGetDriverName2()
    {
        $cfg = new DriverConnection();
        $cfg->setType(DriverType::MODBUS);
        
        $this->assertEquals("Modbus", DriverType::getName($cfg->getType()));
    }
    
    public function testGetDriverName3()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('DriverType::getName: Invalid driver type identifier');
        
        DriverType::getName(6);
    }
}
