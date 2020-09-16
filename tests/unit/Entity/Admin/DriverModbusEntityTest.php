<?php

namespace App\Tests\Entity\Admin;

use App\Entity\Admin\DriverModbus;
use App\Entity\Admin\DriverSHM;
use App\Entity\Admin\DriverModbusMode;
use App\Entity\Admin\DriverModbusEntity;
use App\Entity\Admin\DriverConnection;
use App\Entity\Admin\DriverType;
use PHPUnit\Framework\TestCase;

/**
 * Unit test for DriverModbusEntity class
 *
 * @author Mateusz Mirosławski
 */
class DriverModbusEntityTest extends TestCase
{
    /**
     * Test default constructor
     */
    public function testDefaultConstructor()
    {
        $mbEntity = new DriverModbusEntity();
        
        $this->assertEquals(0, $mbEntity->getConnId());
        $this->assertEquals('', $mbEntity->getconnName());
        $this->assertEquals(0, $mbEntity->getId());
        $this->assertEquals(DriverModbusMode::TCP, $mbEntity->getMode());
        $this->assertEquals("192.168.0.5", $mbEntity->getTCPaddr());
        $this->assertEquals(502, $mbEntity->getTCPport());
        $this->assertEquals(0, $mbEntity->getTCPuseslaveID());
        $this->assertEquals("/dev/ttyACM1", $mbEntity->getRTUport());
        $this->assertEquals(57600, $mbEntity->getRTUbaud());
        $this->assertEquals('N', $mbEntity->getRTUparity());
        $this->assertEquals(8, $mbEntity->getRTUdataBit());
        $this->assertEquals(1, $mbEntity->getRTUstopBit());
        $this->assertEquals(2, $mbEntity->getSlaveID());
        $this->assertEquals(1, $mbEntity->getRegisterCount());
        $this->assertEquals(50, $mbEntity->getDriverPolling());
    }
    
    public function testSetConnId()
    {
        $mbEntity = new DriverModbusEntity();
        
        $mbEntity->setConnId(8);
        
        $this->assertEquals(8, $mbEntity->getConnId());
        $this->assertEquals('', $mbEntity->getconnName());
        $this->assertEquals(0, $mbEntity->getId());
        $this->assertEquals(DriverModbusMode::TCP, $mbEntity->getMode());
        $this->assertEquals("192.168.0.5", $mbEntity->getTCPaddr());
        $this->assertEquals(502, $mbEntity->getTCPport());
        $this->assertEquals(0, $mbEntity->getTCPuseslaveID());
        $this->assertEquals("/dev/ttyACM1", $mbEntity->getRTUport());
        $this->assertEquals(57600, $mbEntity->getRTUbaud());
        $this->assertEquals('N', $mbEntity->getRTUparity());
        $this->assertEquals(8, $mbEntity->getRTUdataBit());
        $this->assertEquals(1, $mbEntity->getRTUstopBit());
        $this->assertEquals(2, $mbEntity->getSlaveID());
        $this->assertEquals(1, $mbEntity->getRegisterCount());
        $this->assertEquals(50, $mbEntity->getDriverPolling());
    }
    
    public function testSetConnName()
    {
        $mbEntity = new DriverModbusEntity();
        
        $mbEntity->setConnName("conn1");
        
        $this->assertEquals(0, $mbEntity->getConnId());
        $this->assertEquals('conn1', $mbEntity->getconnName());
        $this->assertEquals(0, $mbEntity->getId());
        $this->assertEquals(DriverModbusMode::TCP, $mbEntity->getMode());
        $this->assertEquals("192.168.0.5", $mbEntity->getTCPaddr());
        $this->assertEquals(502, $mbEntity->getTCPport());
        $this->assertEquals(0, $mbEntity->getTCPuseslaveID());
        $this->assertEquals("/dev/ttyACM1", $mbEntity->getRTUport());
        $this->assertEquals(57600, $mbEntity->getRTUbaud());
        $this->assertEquals('N', $mbEntity->getRTUparity());
        $this->assertEquals(8, $mbEntity->getRTUdataBit());
        $this->assertEquals(1, $mbEntity->getRTUstopBit());
        $this->assertEquals(2, $mbEntity->getSlaveID());
        $this->assertEquals(1, $mbEntity->getRegisterCount());
        $this->assertEquals(50, $mbEntity->getDriverPolling());
    }
    
    public function testSetId()
    {
        $mbEntity = new DriverModbusEntity();
        
        $mbEntity->setId(3);
        
        $this->assertEquals(0, $mbEntity->getConnId());
        $this->assertEquals('', $mbEntity->getconnName());
        $this->assertEquals(3, $mbEntity->getId());
        $this->assertEquals(DriverModbusMode::TCP, $mbEntity->getMode());
        $this->assertEquals("192.168.0.5", $mbEntity->getTCPaddr());
        $this->assertEquals(502, $mbEntity->getTCPport());
        $this->assertEquals(0, $mbEntity->getTCPuseslaveID());
        $this->assertEquals("/dev/ttyACM1", $mbEntity->getRTUport());
        $this->assertEquals(57600, $mbEntity->getRTUbaud());
        $this->assertEquals('N', $mbEntity->getRTUparity());
        $this->assertEquals(8, $mbEntity->getRTUdataBit());
        $this->assertEquals(1, $mbEntity->getRTUstopBit());
        $this->assertEquals(2, $mbEntity->getSlaveID());
        $this->assertEquals(1, $mbEntity->getRegisterCount());
        $this->assertEquals(50, $mbEntity->getDriverPolling());
    }
    
    public function testSetMode()
    {
        $mbEntity = new DriverModbusEntity();
        
        $mbEntity->setMode(DriverModbusMode::RTU);
        
        $this->assertEquals(0, $mbEntity->getConnId());
        $this->assertEquals('', $mbEntity->getconnName());
        $this->assertEquals(0, $mbEntity->getId());
        $this->assertEquals(DriverModbusMode::RTU, $mbEntity->getMode());
        $this->assertEquals("192.168.0.5", $mbEntity->getTCPaddr());
        $this->assertEquals(502, $mbEntity->getTCPport());
        $this->assertEquals(0, $mbEntity->getTCPuseslaveID());
        $this->assertEquals("/dev/ttyACM1", $mbEntity->getRTUport());
        $this->assertEquals(57600, $mbEntity->getRTUbaud());
        $this->assertEquals('N', $mbEntity->getRTUparity());
        $this->assertEquals(8, $mbEntity->getRTUdataBit());
        $this->assertEquals(1, $mbEntity->getRTUstopBit());
        $this->assertEquals(2, $mbEntity->getSlaveID());
        $this->assertEquals(1, $mbEntity->getRegisterCount());
        $this->assertEquals(50, $mbEntity->getDriverPolling());
    }
    
    public function testSetTcpAddr()
    {
        $mbEntity = new DriverModbusEntity();
        
        $mbEntity->setTCPaddr("192.168.8.8");
        
        $this->assertEquals(0, $mbEntity->getConnId());
        $this->assertEquals('', $mbEntity->getconnName());
        $this->assertEquals(0, $mbEntity->getId());
        $this->assertEquals(DriverModbusMode::TCP, $mbEntity->getMode());
        $this->assertEquals("192.168.8.8", $mbEntity->getTCPaddr());
        $this->assertEquals(502, $mbEntity->getTCPport());
        $this->assertEquals(0, $mbEntity->getTCPuseslaveID());
        $this->assertEquals("/dev/ttyACM1", $mbEntity->getRTUport());
        $this->assertEquals(57600, $mbEntity->getRTUbaud());
        $this->assertEquals('N', $mbEntity->getRTUparity());
        $this->assertEquals(8, $mbEntity->getRTUdataBit());
        $this->assertEquals(1, $mbEntity->getRTUstopBit());
        $this->assertEquals(2, $mbEntity->getSlaveID());
        $this->assertEquals(1, $mbEntity->getRegisterCount());
        $this->assertEquals(50, $mbEntity->getDriverPolling());
    }
    
    public function testSetTcpPort()
    {
        $mbEntity = new DriverModbusEntity();
        
        $mbEntity->setTCPport(550);
        
        $this->assertEquals(0, $mbEntity->getConnId());
        $this->assertEquals('', $mbEntity->getconnName());
        $this->assertEquals(0, $mbEntity->getId());
        $this->assertEquals(DriverModbusMode::TCP, $mbEntity->getMode());
        $this->assertEquals("192.168.0.5", $mbEntity->getTCPaddr());
        $this->assertEquals(550, $mbEntity->getTCPport());
        $this->assertEquals(0, $mbEntity->getTCPuseslaveID());
        $this->assertEquals("/dev/ttyACM1", $mbEntity->getRTUport());
        $this->assertEquals(57600, $mbEntity->getRTUbaud());
        $this->assertEquals('N', $mbEntity->getRTUparity());
        $this->assertEquals(8, $mbEntity->getRTUdataBit());
        $this->assertEquals(1, $mbEntity->getRTUstopBit());
        $this->assertEquals(2, $mbEntity->getSlaveID());
        $this->assertEquals(1, $mbEntity->getRegisterCount());
        $this->assertEquals(50, $mbEntity->getDriverPolling());
    }
    
    public function testSetRTUPort()
    {
        $mbEntity = new DriverModbusEntity();
        
        $mbEntity->setRTUport("/dev/ttyACM3");
        
        $this->assertEquals(0, $mbEntity->getConnId());
        $this->assertEquals('', $mbEntity->getconnName());
        $this->assertEquals(0, $mbEntity->getId());
        $this->assertEquals(DriverModbusMode::TCP, $mbEntity->getMode());
        $this->assertEquals("192.168.0.5", $mbEntity->getTCPaddr());
        $this->assertEquals(502, $mbEntity->getTCPport());
        $this->assertEquals(0, $mbEntity->getTCPuseslaveID());
        $this->assertEquals("/dev/ttyACM3", $mbEntity->getRTUport());
        $this->assertEquals(57600, $mbEntity->getRTUbaud());
        $this->assertEquals('N', $mbEntity->getRTUparity());
        $this->assertEquals(8, $mbEntity->getRTUdataBit());
        $this->assertEquals(1, $mbEntity->getRTUstopBit());
        $this->assertEquals(2, $mbEntity->getSlaveID());
        $this->assertEquals(1, $mbEntity->getRegisterCount());
        $this->assertEquals(50, $mbEntity->getDriverPolling());
    }
    
    public function testSetRTUbaud()
    {
        $mbEntity = new DriverModbusEntity();
        
        $mbEntity->setRTUbaud(57630);
        
        $this->assertEquals(0, $mbEntity->getConnId());
        $this->assertEquals('', $mbEntity->getconnName());
        $this->assertEquals(0, $mbEntity->getId());
        $this->assertEquals(DriverModbusMode::TCP, $mbEntity->getMode());
        $this->assertEquals("192.168.0.5", $mbEntity->getTCPaddr());
        $this->assertEquals(502, $mbEntity->getTCPport());
        $this->assertEquals(0, $mbEntity->getTCPuseslaveID());
        $this->assertEquals("/dev/ttyACM1", $mbEntity->getRTUport());
        $this->assertEquals(57630, $mbEntity->getRTUbaud());
        $this->assertEquals('N', $mbEntity->getRTUparity());
        $this->assertEquals(8, $mbEntity->getRTUdataBit());
        $this->assertEquals(1, $mbEntity->getRTUstopBit());
        $this->assertEquals(2, $mbEntity->getSlaveID());
        $this->assertEquals(1, $mbEntity->getRegisterCount());
        $this->assertEquals(50, $mbEntity->getDriverPolling());
    }
    
    public function testSetRTUparity()
    {
        $mbEntity = new DriverModbusEntity();
        
        $mbEntity->setRTUparity('E');
        
        $this->assertEquals(0, $mbEntity->getConnId());
        $this->assertEquals('', $mbEntity->getconnName());
        $this->assertEquals(0, $mbEntity->getId());
        $this->assertEquals(DriverModbusMode::TCP, $mbEntity->getMode());
        $this->assertEquals("192.168.0.5", $mbEntity->getTCPaddr());
        $this->assertEquals(502, $mbEntity->getTCPport());
        $this->assertEquals(0, $mbEntity->getTCPuseslaveID());
        $this->assertEquals("/dev/ttyACM1", $mbEntity->getRTUport());
        $this->assertEquals(57600, $mbEntity->getRTUbaud());
        $this->assertEquals('E', $mbEntity->getRTUparity());
        $this->assertEquals(8, $mbEntity->getRTUdataBit());
        $this->assertEquals(1, $mbEntity->getRTUstopBit());
        $this->assertEquals(2, $mbEntity->getSlaveID());
        $this->assertEquals(1, $mbEntity->getRegisterCount());
        $this->assertEquals(50, $mbEntity->getDriverPolling());
    }
    
    public function testSetRTUdataBit()
    {
        $mbEntity = new DriverModbusEntity();
        
        $mbEntity->setRTUdataBit(7);
        
        $this->assertEquals(0, $mbEntity->getConnId());
        $this->assertEquals('', $mbEntity->getconnName());
        $this->assertEquals(0, $mbEntity->getId());
        $this->assertEquals(DriverModbusMode::TCP, $mbEntity->getMode());
        $this->assertEquals("192.168.0.5", $mbEntity->getTCPaddr());
        $this->assertEquals(502, $mbEntity->getTCPport());
        $this->assertEquals(0, $mbEntity->getTCPuseslaveID());
        $this->assertEquals("/dev/ttyACM1", $mbEntity->getRTUport());
        $this->assertEquals(57600, $mbEntity->getRTUbaud());
        $this->assertEquals('N', $mbEntity->getRTUparity());
        $this->assertEquals(7, $mbEntity->getRTUdataBit());
        $this->assertEquals(1, $mbEntity->getRTUstopBit());
        $this->assertEquals(2, $mbEntity->getSlaveID());
        $this->assertEquals(1, $mbEntity->getRegisterCount());
        $this->assertEquals(50, $mbEntity->getDriverPolling());
    }
    
    public function testSetRTUstopBit()
    {
        $mbEntity = new DriverModbusEntity();
        
        $mbEntity->setRTUstopBit(2);
        
        $this->assertEquals(0, $mbEntity->getConnId());
        $this->assertEquals('', $mbEntity->getconnName());
        $this->assertEquals(0, $mbEntity->getId());
        $this->assertEquals(DriverModbusMode::TCP, $mbEntity->getMode());
        $this->assertEquals("192.168.0.5", $mbEntity->getTCPaddr());
        $this->assertEquals(502, $mbEntity->getTCPport());
        $this->assertEquals(0, $mbEntity->getTCPuseslaveID());
        $this->assertEquals("/dev/ttyACM1", $mbEntity->getRTUport());
        $this->assertEquals(57600, $mbEntity->getRTUbaud());
        $this->assertEquals('N', $mbEntity->getRTUparity());
        $this->assertEquals(8, $mbEntity->getRTUdataBit());
        $this->assertEquals(2, $mbEntity->getRTUstopBit());
        $this->assertEquals(2, $mbEntity->getSlaveID());
        $this->assertEquals(1, $mbEntity->getRegisterCount());
        $this->assertEquals(50, $mbEntity->getDriverPolling());
    }
    
    public function testSetSlaveID()
    {
        $mbEntity = new DriverModbusEntity();
        
        $mbEntity->setSlaveID(45);
        
        $this->assertEquals(0, $mbEntity->getConnId());
        $this->assertEquals('', $mbEntity->getconnName());
        $this->assertEquals(0, $mbEntity->getId());
        $this->assertEquals(DriverModbusMode::TCP, $mbEntity->getMode());
        $this->assertEquals("192.168.0.5", $mbEntity->getTCPaddr());
        $this->assertEquals(502, $mbEntity->getTCPport());
        $this->assertEquals(0, $mbEntity->getTCPuseslaveID());
        $this->assertEquals("/dev/ttyACM1", $mbEntity->getRTUport());
        $this->assertEquals(57600, $mbEntity->getRTUbaud());
        $this->assertEquals('N', $mbEntity->getRTUparity());
        $this->assertEquals(8, $mbEntity->getRTUdataBit());
        $this->assertEquals(1, $mbEntity->getRTUstopBit());
        $this->assertEquals(45, $mbEntity->getSlaveID());
        $this->assertEquals(1, $mbEntity->getRegisterCount());
        $this->assertEquals(50, $mbEntity->getDriverPolling());
    }
    
    public function testSetRegisterCount()
    {
        $mbEntity = new DriverModbusEntity();
        
        $mbEntity->setRegisterCount(52);
        
        $this->assertEquals(0, $mbEntity->getConnId());
        $this->assertEquals('', $mbEntity->getconnName());
        $this->assertEquals(0, $mbEntity->getId());
        $this->assertEquals(DriverModbusMode::TCP, $mbEntity->getMode());
        $this->assertEquals("192.168.0.5", $mbEntity->getTCPaddr());
        $this->assertEquals(502, $mbEntity->getTCPport());
        $this->assertEquals(0, $mbEntity->getTCPuseslaveID());
        $this->assertEquals("/dev/ttyACM1", $mbEntity->getRTUport());
        $this->assertEquals(57600, $mbEntity->getRTUbaud());
        $this->assertEquals('N', $mbEntity->getRTUparity());
        $this->assertEquals(8, $mbEntity->getRTUdataBit());
        $this->assertEquals(1, $mbEntity->getRTUstopBit());
        $this->assertEquals(2, $mbEntity->getSlaveID());
        $this->assertEquals(52, $mbEntity->getRegisterCount());
        $this->assertEquals(50, $mbEntity->getDriverPolling());
    }
    
    public function testSetDriverPolling()
    {
        $mbEntity = new DriverModbusEntity();
        
        $mbEntity->setDriverPolling(100);
        
        $this->assertEquals(0, $mbEntity->getConnId());
        $this->assertEquals('', $mbEntity->getconnName());
        $this->assertEquals(0, $mbEntity->getId());
        $this->assertEquals(DriverModbusMode::TCP, $mbEntity->getMode());
        $this->assertEquals("192.168.0.5", $mbEntity->getTCPaddr());
        $this->assertEquals(502, $mbEntity->getTCPport());
        $this->assertEquals(0, $mbEntity->getTCPuseslaveID());
        $this->assertEquals("/dev/ttyACM1", $mbEntity->getRTUport());
        $this->assertEquals(57600, $mbEntity->getRTUbaud());
        $this->assertEquals('N', $mbEntity->getRTUparity());
        $this->assertEquals(8, $mbEntity->getRTUdataBit());
        $this->assertEquals(1, $mbEntity->getRTUstopBit());
        $this->assertEquals(2, $mbEntity->getSlaveID());
        $this->assertEquals(1, $mbEntity->getRegisterCount());
        $this->assertEquals(100, $mbEntity->getDriverPolling());
    }
    
    public function testGetFullConnectionObject1()
    {
        $mbEntity = new DriverModbusEntity();
        $mbEntity->setConnId(8);
        $mbEntity->setconnName("conn3");
        $mbEntity->setId(45);
        $mbEntity->setTCPaddr("10.8.4.2");
        $mbEntity->setTCPport(855);
        $mbEntity->setTCPuseslaveID(1);
        $mbEntity->setRegisterCount(60);
        $mbEntity->setDriverPolling(600);
        $mbEntity->setSlaveID(7);
        
        $conn = $mbEntity->getFullConnectionObject();
        
        $this->assertEquals(8, $conn->getId());
        $this->assertEquals("conn3", $conn->getName());
        $this->assertEquals(DriverType::MODBUS, $conn->getType());
        $this->assertTrue($conn->isModbusConfig());
        $this->assertFalse($conn->isShmConfig());
        
        $cfg = $conn->getModbusConfig();
        $this->assertEquals(45, $cfg->getId());
        $this->assertEquals(DriverModbusMode::TCP, $cfg->getMode());
        $this->assertEquals('10.8.4.2', $cfg->getTCPaddr());
        $this->assertEquals(855, $cfg->getTCPport());
        $this->assertEquals(1, $mbEntity->getTCPuseslaveID());
        $this->assertEquals('/dev/ttyACM1', $cfg->getRTUport());
        $this->assertEquals(57600, $cfg->getRTUbaud());
        $this->assertEquals('N', $cfg->getRTUparity());
        $this->assertEquals(8, $cfg->getRTUdataBit());
        $this->assertEquals(1, $cfg->getRTUstopBit());
        $this->assertEquals(60, $cfg->getRegisterCount());
        $this->assertEquals(600, $cfg->getDriverPolling());
        $this->assertEquals(7, $cfg->getSlaveID());
        $this->assertEquals(120, $cfg->getMaxByteAddress());
    }
    
    public function testGetFullConnectionObject2()
    {
        $mbEntity = new DriverModbusEntity();
        $mbEntity->setConnId(8);
        $mbEntity->setconnName("conn3");
        $mbEntity->setId(45);
        $mbEntity->setMode(DriverModbusMode::RTU);
        $mbEntity->setRTUdataBit(7);
        $mbEntity->setRTUport("port3");
        $mbEntity->setRTUbaud(4500);
        $mbEntity->setRTUparity('E');
        $mbEntity->setRTUstopBit(2);
        $mbEntity->setRegisterCount(40);
        $mbEntity->setDriverPolling(400);
        $mbEntity->setSlaveID(9);
        
        $conn = $mbEntity->getFullConnectionObject();
        
        $this->assertEquals(8, $conn->getId());
        $this->assertEquals("conn3", $conn->getName());
        $this->assertEquals(DriverType::MODBUS, $conn->getType());
        $this->assertTrue($conn->isModbusConfig());
        $this->assertFalse($conn->isShmConfig());
        
        $cfg = $conn->getModbusConfig();
        $this->assertEquals(45, $cfg->getId());
        $this->assertEquals(DriverModbusMode::RTU, $cfg->getMode());
        $this->assertEquals('192.168.0.5', $cfg->getTCPaddr());
        $this->assertEquals(502, $cfg->getTCPport());
        $this->assertEquals(0, $mbEntity->getTCPuseslaveID());
        $this->assertEquals('port3', $cfg->getRTUport());
        $this->assertEquals(4500, $cfg->getRTUbaud());
        $this->assertEquals('E', $cfg->getRTUparity());
        $this->assertEquals(7, $cfg->getRTUdataBit());
        $this->assertEquals(2, $cfg->getRTUstopBit());
        $this->assertEquals(40, $cfg->getRegisterCount());
        $this->assertEquals(400, $cfg->getDriverPolling());
        $this->assertEquals(9, $cfg->getSlaveID());
        $this->assertEquals(80, $cfg->getMaxByteAddress());
    }
    
    public function testInitFromConnectionObject1()
    {
        // Modbus CFG
        $mb = new DriverModbus();
        $mb->setId(7);
        $mb->setMode(DriverModbusMode::TCP);
        $mb->setRegisterCount(10);
        $mb->setSlaveID(6);
        $mb->setDriverPolling(300);
        $mb->setTCPaddr("172.0.0.6");
        $mb->setTCPport(600);
        $mb->setSlaveIdUsageInTCP(true);
        
        // Connection
        $conn = new DriverConnection();
        $conn->setId(3);
        $conn->setName("Conn5");
        $conn->setType(DriverType::MODBUS);
        $conn->setModbusConfig($mb);
        
        // Init entity
        $mbEntity = new DriverModbusEntity();
        $mbEntity->initFromConnectionObject($conn);
        
        $this->assertEquals(3, $mbEntity->getConnId());
        $this->assertEquals('Conn5', $mbEntity->getconnName());
        $this->assertEquals(7, $mbEntity->getId());
        $this->assertEquals(DriverModbusMode::TCP, $mbEntity->getMode());
        $this->assertEquals("172.0.0.6", $mbEntity->getTCPaddr());
        $this->assertEquals(600, $mbEntity->getTCPport());
        $this->assertEquals(1, $mbEntity->getTCPuseslaveID());
        $this->assertEquals("/dev/ttyACM1", $mbEntity->getRTUport());
        $this->assertEquals(57600, $mbEntity->getRTUbaud());
        $this->assertEquals('N', $mbEntity->getRTUparity());
        $this->assertEquals(8, $mbEntity->getRTUdataBit());
        $this->assertEquals(1, $mbEntity->getRTUstopBit());
        $this->assertEquals(6, $mbEntity->getSlaveID());
        $this->assertEquals(10, $mbEntity->getRegisterCount());
        $this->assertEquals(300, $mbEntity->getDriverPolling());
    }
    
    public function testInitFromConnectionObject2()
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
        
        // Connection
        $conn = new DriverConnection();
        $conn->setId(3);
        $conn->setName("Conn5");
        $conn->setType(DriverType::MODBUS);
        $conn->setModbusConfig($mb);
        
        // Init entity
        $mbEntity = new DriverModbusEntity();
        $mbEntity->initFromConnectionObject($conn);
        
        $this->assertEquals(3, $mbEntity->getConnId());
        $this->assertEquals('Conn5', $mbEntity->getconnName());
        $this->assertEquals(7, $mbEntity->getId());
        $this->assertEquals(DriverModbusMode::RTU, $mbEntity->getMode());
        $this->assertEquals("192.168.0.5", $mbEntity->getTCPaddr());
        $this->assertEquals(502, $mbEntity->getTCPport());
        $this->assertEquals(0, $mbEntity->getTCPuseslaveID());
        $this->assertEquals("portCOM5", $mbEntity->getRTUport());
        $this->assertEquals(6000, $mbEntity->getRTUbaud());
        $this->assertEquals('O', $mbEntity->getRTUparity());
        $this->assertEquals(7, $mbEntity->getRTUdataBit());
        $this->assertEquals(2, $mbEntity->getRTUstopBit());
        $this->assertEquals(6, $mbEntity->getSlaveID());
        $this->assertEquals(10, $mbEntity->getRegisterCount());
        $this->assertEquals(300, $mbEntity->getDriverPolling());
    }
    
    public function testInitFromConnectionObjectWrong1()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('Missing modbus configuration in connection object');
        
        // SHM
        $shm = new DriverSHM();
        
        // Connection
        $conn = new DriverConnection();
        $conn->setId(3);
        $conn->setName("Conn5");
        $conn->setShmConfig($shm);
        $conn->setType(DriverType::MODBUS);
        
        // Init entity
        $mbEntity = new DriverModbusEntity();
        $mbEntity->initFromConnectionObject($conn);
    }
}
