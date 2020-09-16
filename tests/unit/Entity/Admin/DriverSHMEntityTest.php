<?php

namespace App\Tests\Entity\Admin;

use App\Entity\Admin\DriverModbus;
use App\Entity\Admin\DriverSHM;
use App\Entity\Admin\DriverSHMEntity;
use App\Entity\Admin\DriverConnection;
use App\Entity\Admin\DriverType;
use PHPUnit\Framework\TestCase;

/**
 * Unit test for DriverSHMEntity class
 *
 * @author Mateusz Mirosławski
 */
class DriverSHMEntityTest extends TestCase
{
    /**
     * Test default constructor
     */
    public function testDefaultConstructor()
    {
        $shmEntity = new DriverSHMEntity();
        
        $this->assertEquals(0, $shmEntity->getConnId());
        $this->assertEquals('', $shmEntity->getconnName());
        $this->assertEquals(0, $shmEntity->getId());
        $this->assertEquals("shm_segment", $shmEntity->getSegmentName());
    }
    
    public function testSetConnId()
    {
        $shmEntity = new DriverSHMEntity();
        
        $shmEntity->setConnId(8);
        
        $this->assertEquals(8, $shmEntity->getConnId());
        $this->assertEquals('', $shmEntity->getconnName());
        $this->assertEquals(0, $shmEntity->getId());
        $this->assertEquals("shm_segment", $shmEntity->getSegmentName());
    }
    
    public function testSetConnName()
    {
        $shmEntity = new DriverSHMEntity();
        
        $shmEntity->setConnName("conn1");
        
        $this->assertEquals(0, $shmEntity->getConnId());
        $this->assertEquals('conn1', $shmEntity->getconnName());
        $this->assertEquals(0, $shmEntity->getId());
        $this->assertEquals("shm_segment", $shmEntity->getSegmentName());
    }
    
    public function testSetId()
    {
        $shmEntity = new DriverSHMEntity();
        
        $shmEntity->setId(3);
        
        $this->assertEquals(0, $shmEntity->getConnId());
        $this->assertEquals('', $shmEntity->getconnName());
        $this->assertEquals(3, $shmEntity->getId());
        $this->assertEquals("shm_segment", $shmEntity->getSegmentName());
    }
    
    public function testSetSegmentName()
    {
        $shmEntity = new DriverSHMEntity();
        
        $shmEntity->setSegmentName("shm11");
        
        $this->assertEquals(0, $shmEntity->getConnId());
        $this->assertEquals('', $shmEntity->getconnName());
        $this->assertEquals(0, $shmEntity->getId());
        $this->assertEquals("shm11", $shmEntity->getSegmentName());
    }
    
    public function testGetFullConnectionObject1()
    {
        $shmEntity = new DriverSHMEntity();
        $shmEntity->setConnId(8);
        $shmEntity->setconnName("conn3");
        $shmEntity->setId(45);
        $shmEntity->setSegmentName("shm11");
        
        $conn = $shmEntity->getFullConnectionObject();
        
        $this->assertEquals(8, $conn->getId());
        $this->assertEquals("conn3", $conn->getName());
        $this->assertEquals(DriverType::SHM, $conn->getType());
        $this->assertFalse($conn->isModbusConfig());
        $this->assertTrue($conn->isShmConfig());
        
        $cfg = $conn->getShmConfig();
        $this->assertEquals(45, $cfg->getId());
        $this->assertEquals('shm11', $cfg->getSegmentName());
    }
    
    public function testInitFromConnectionObject1()
    {
        // SHM CFG
        $cfg = new DriverSHM();
        $cfg->setId(65);
        $cfg->setSegmentName("seg_shm3");
        
        // Connection
        $conn = new DriverConnection();
        $conn->setId(3);
        $conn->setName("Conn5");
        $conn->setType(DriverType::SHM);
        $conn->setShmConfig($cfg);
        
        // Init entity
        $shmEntity = new DriverSHMEntity();
        $shmEntity->initFromConnectionObject($conn);
        
        $this->assertEquals(3, $shmEntity->getConnId());
        $this->assertEquals('Conn5', $shmEntity->getconnName());
        $this->assertEquals(65, $shmEntity->getId());
        $this->assertEquals("seg_shm3", $shmEntity->getSegmentName());
    }
    
    public function testInitFromConnectionObjectWrong1()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('Missing SHM configuration in connection object');
        
        // Modbus
        $mb = new DriverModbus();
        
        // Connection
        $conn = new DriverConnection();
        $conn->setId(3);
        $conn->setName("Conn5");
        $conn->setModbusConfig($mb);
        $conn->setType(DriverType::SHM);
        
        // Init entity
        $shmEntity = new DriverSHMEntity();
        $shmEntity->initFromConnectionObject($conn);
    }
}
