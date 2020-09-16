<?php

namespace App\Tests\Entity\Admin;

use App\Entity\Admin\AlarmItem;
use PHPUnit\Framework\TestCase;

/**
 * Unit test for AlarmItem class
 *
 * @author Mateusz Mirosławski
 */
class AlarmItemTest extends TestCase
{
    /**
     * Test Default constructor
     */
    public function testDefaultConstructor()
    {
        $alarmItem = new AlarmItem();
        
        $this->assertEquals(0, $alarmItem->getId());
        $this->assertEquals(0, $alarmItem->getDefinitionId());
        $this->assertEquals(0, $alarmItem->getPriority());
        $this->assertEquals('', $alarmItem->getMessage());
        $this->assertFalse($alarmItem->isActive());
        $this->assertFalse($alarmItem->isAck());
        $this->assertFalse($alarmItem->isOffTimestamp());
    }
    
    public function testDefaultConstructor1()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('Alarm on timestamp is NULL');
        
        $alarmItem = new AlarmItem();
        
        $alarmItem->getOnTimestamp();
    }
    
    public function testDefaultConstructor2()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('Alarm off timestamp is NULL');
        
        $alarmItem = new AlarmItem();
        
        $alarmItem->getOffTimestamp();
    }
    
    public function testDefaultConstructor3()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('Alarm ack timestamp is NULL');
        
        $alarmItem = new AlarmItem();
        
        $alarmItem->getAckTimestamp();
    }
    
    /**
     * Test setId method
     */
    public function testSetId()
    {
        $alarmItem = new AlarmItem();
        $alarmItem->setId(35);
        
        $this->assertEquals(35, $alarmItem->getId());
        
        $this->assertEquals(0, $alarmItem->getDefinitionId());
        $this->assertEquals(0, $alarmItem->getPriority());
        $this->assertEquals('', $alarmItem->getMessage());
        $this->assertFalse($alarmItem->isActive());
        $this->assertFalse($alarmItem->isAck());
        $this->assertFalse($alarmItem->isOffTimestamp());
    }
    
    /**
     * Test setDefinitionId method
     */
    public function testSetDefinitionId()
    {
        $alarmItem = new AlarmItem();
        $alarmItem->setDefinitionId(89);
        
        $this->assertEquals(89, $alarmItem->getDefinitionId());
        
        $this->assertEquals(0, $alarmItem->getId());
        $this->assertEquals(0, $alarmItem->getPriority());
        $this->assertEquals('', $alarmItem->getMessage());
        $this->assertFalse($alarmItem->isActive());
        $this->assertFalse($alarmItem->isAck());
        $this->assertFalse($alarmItem->isOffTimestamp());
    }
    
    /**
     * Test setPriority method
     */
    public function testSetPriority()
    {
        $alarmItem = new AlarmItem();
        $alarmItem->setPriority(3);
        
        $this->assertEquals(3, $alarmItem->getPriority());
        
        $this->assertEquals(0, $alarmItem->getId());
        $this->assertEquals(0, $alarmItem->getDefinitionId());
        $this->assertEquals('', $alarmItem->getMessage());
        $this->assertFalse($alarmItem->isActive());
        $this->assertFalse($alarmItem->isAck());
        $this->assertFalse($alarmItem->isOffTimestamp());
    }
    
    /**
     * Test setMessage method
     */
    public function testSetMessage()
    {
        $alarmItem = new AlarmItem();
        $alarmItem->setMessage('Test alarm');
        
        $this->assertEquals('Test alarm', $alarmItem->getMessage());
        
        $this->assertEquals(0, $alarmItem->getId());
        $this->assertEquals(0, $alarmItem->getDefinitionId());
        $this->assertEquals(0, $alarmItem->getPriority());
        $this->assertFalse($alarmItem->isActive());
        $this->assertFalse($alarmItem->isAck());
        $this->assertFalse($alarmItem->isOffTimestamp());
    }
    
    /**
     * Test setActive method
     */
    public function testSetActive()
    {
        $alarmItem = new AlarmItem();
        $alarmItem->setActive(true);
        
        $this->assertTrue($alarmItem->isActive());
        
        $this->assertEquals(0, $alarmItem->getId());
        $this->assertEquals(0, $alarmItem->getDefinitionId());
        $this->assertEquals(0, $alarmItem->getPriority());
        $this->assertEquals('', $alarmItem->getMessage());
        $this->assertFalse($alarmItem->isAck());
        $this->assertFalse($alarmItem->isOffTimestamp());
    }
    
    /**
     * Test setAck method
     */
    public function testSetAck()
    {
        $alarmItem = new AlarmItem();
        $alarmItem->setAck(true);
        
        $this->assertTrue($alarmItem->isAck());
        
        $this->assertEquals(0, $alarmItem->getId());
        $this->assertEquals(0, $alarmItem->getDefinitionId());
        $this->assertEquals(0, $alarmItem->getPriority());
        $this->assertEquals('', $alarmItem->getMessage());
        $this->assertFalse($alarmItem->isActive());
        $this->assertFalse($alarmItem->isOffTimestamp());
    }
    
    /**
     * Test setOnTimestamp method
     */
    public function testSetOnTimestamp()
    {
        $alarmItem = new AlarmItem();
        $alarmItem->setOnTimestamp('2019-08-07 14:56:07');
        
        $this->assertEquals('2019-08-07 14:56:07', $alarmItem->getOnTimestamp());
        
        $this->assertEquals(0, $alarmItem->getId());
        $this->assertEquals(0, $alarmItem->getDefinitionId());
        $this->assertEquals(0, $alarmItem->getPriority());
        $this->assertEquals('', $alarmItem->getMessage());
        $this->assertFalse($alarmItem->isActive());
        $this->assertFalse($alarmItem->isAck());
        $this->assertFalse($alarmItem->isOffTimestamp());
    }
    
    public function testSetOnTimestampWrong1()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('Alarm on timestamp can not be empty');
        
        $alarmItem = new AlarmItem();
        $alarmItem->setOnTimestamp(' ');
        
        $alarmItem->getOnTimestamp();
    }
    
    public function testSetOnTimestampWrong2()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('Alarm on timestamp wrong format');
        
        $alarmItem = new AlarmItem();
        $alarmItem->setOnTimestamp('2019.08.07 14:56:07');
        
        $alarmItem->getOnTimestamp();
    }
    
    /**
     * Test setOffTimestamp method
     */
    public function testSetOffTimestamp()
    {
        $alarmItem = new AlarmItem();
        $alarmItem->setOffTimestamp('2019-08-07 14:56:07');
        
        $this->assertEquals('2019-08-07 14:56:07', $alarmItem->getOffTimestamp());
        
        $this->assertEquals(0, $alarmItem->getId());
        $this->assertEquals(0, $alarmItem->getDefinitionId());
        $this->assertEquals(0, $alarmItem->getPriority());
        $this->assertEquals('', $alarmItem->getMessage());
        $this->assertFalse($alarmItem->isActive());
        $this->assertFalse($alarmItem->isAck());
        $this->assertTrue($alarmItem->isOffTimestamp());
    }
    
    public function testSetOffTimestampWrong1()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('Alarm off timestamp can not be empty');
        
        $alarmItem = new AlarmItem();
        $alarmItem->setOffTimestamp(' ');
        
        $alarmItem->getOffTimestamp();
    }
    
    public function testSetOffTimestampWrong2()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('Alarm off timestamp wrong format');
        
        $alarmItem = new AlarmItem();
        $alarmItem->setOffTimestamp('2019.08.07 14:56:07');
        
        $alarmItem->getOffTimestamp();
    }
    
    /**
     * Test setAckTimestamp method
     */
    public function testSetAckTimestamp()
    {
        $alarmItem = new AlarmItem();
        $alarmItem->setAckTimestamp('2019-08-07 14:56:07');
        
        $this->assertEquals('2019-08-07 14:56:07', $alarmItem->getAckTimestamp());
        
        $this->assertEquals(0, $alarmItem->getId());
        $this->assertEquals(0, $alarmItem->getDefinitionId());
        $this->assertEquals(0, $alarmItem->getPriority());
        $this->assertEquals('', $alarmItem->getMessage());
        $this->assertFalse($alarmItem->isActive());
        $this->assertFalse($alarmItem->isAck());
        $this->assertFalse($alarmItem->isOffTimestamp());
    }
    
    public function testSetAckTimestampWrong1()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('Alarm ack timestamp can not be empty');
        
        $alarmItem = new AlarmItem();
        $alarmItem->setAckTimestamp(' ');
        
        $alarmItem->getAckTimestamp();
    }
    
    public function testSetAckTimestampWrong2()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('Alarm ack timestamp wrong format');
        
        $alarmItem = new AlarmItem();
        $alarmItem->setAckTimestamp('2019.08.07 14:56:07');
        
        $alarmItem->getAckTimestamp();
    }
}
