<?php

namespace App\Tests\Entity\Admin;

use App\Entity\Admin\Tag;
use App\Entity\Admin\TagLogger;
use App\Entity\Admin\TagLoggerInterval;
use App\Entity\Admin\TagLoggerEntity;
use App\Tests\Entity\Admin\TagLoggerTest;
use PHPUnit\Framework\TestCase;

/**
 * Unit test for TagLoggerEntity class
 *
 * @author Mateusz Mirosławski
 */
class TagLoggerEntityTest extends TestCase
{
    /**
     * Test Default constructor
     */
    public function testDefaultConstructor()
    {
        $loggerEntity = new TagLoggerEntity();
        
        $this->assertEquals(0, $loggerEntity->getltid());
        $this->assertEquals('', $loggerEntity->getltTagName());
        $this->assertEquals(0, $loggerEntity->getltInterval());
        $this->assertEquals(0, $loggerEntity->getltIntervalS());
    }
    
    /**
     * Test setltid method
     */
    public function testSetId()
    {
        $loggerEntity = new TagLoggerEntity();
        $loggerEntity->setltid(85);
        
        $this->assertEquals(85, $loggerEntity->getltid());
    }
    
    /**
     * Test setltTagName method
     */
    public function testSetTagName()
    {
        $loggerEntity = new TagLoggerEntity();
        $loggerEntity->setltTagName('TestTag');
        
        $this->assertEquals('TestTag', $loggerEntity->getltTagName());
    }
    
    /**
     * Test setltInterval method
     */
    public function testSetInterval()
    {
        $loggerEntity = new TagLoggerEntity();
        $loggerEntity->setltInterval(4);
        
        $this->assertEquals(4, $loggerEntity->getltInterval());
    }
    
    /**
     * Test setltIntervalS method
     */
    public function testSetIntervalS()
    {
        $loggerEntity = new TagLoggerEntity();
        $loggerEntity->setltIntervalS(46);
        
        $this->assertEquals(46, $loggerEntity->getltIntervalS());
    }
    
    /**
     * Test getFullLoggerObject method
     */
    public function testGetFullLoggerObject()
    {
        $tag = null;
        TagLoggerTest::createTag($tag);
        
        $loggerEntity = new TagLoggerEntity();
        $loggerEntity->setltid(45);
        $loggerEntity->setltTagName($tag->getName());
        $loggerEntity->setltInterval(5);
        $loggerEntity->setltIntervalS(10);
        
        $tagLog = $loggerEntity->getFullLoggerObject($tag);
        
        $this->assertEquals(45, $tagLog->getId());
        
        $this->assertInstanceOf(Tag::class, $tagLog->getTag());
        $this->assertEquals(14, $tagLog->getTag()->getId());
        
        $this->assertEquals(TagLoggerInterval::I_XS, $tagLog->getInterval());
        
        $this->assertEquals(10, $tagLog->getIntervalS());
    }
    
    public function testGetFullLoggerObjectWrong()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('Tag name can not be empty');
        
        $tag = new Tag();
        
        $loggerEntity = new TagLoggerEntity();
        $loggerEntity->setltid(45);
        $loggerEntity->setltTagName('TestTag');
        $loggerEntity->setltInterval(5);
        $loggerEntity->setltIntervalS(10);
        
        $loggerEntity->getFullLoggerObject($tag);
    }
    
    /**
     * Test initFromLoggerObject method
     */
    public function testInitFromLoggerObject()
    {
        $tag = null;
        TagLoggerTest::createTag($tag);
        
        $tagLog = new TagLogger($tag);
        $tagLog->setId(78);
        $tagLog->setInterval(TagLoggerInterval::I_XS);
        $tagLog->setIntervalS(15);
        
        $loggerEntity = new TagLoggerEntity();
        $loggerEntity->initFromLoggerObject($tagLog);
        
        $this->assertEquals(78, $loggerEntity->getltid());
        $this->assertEquals($tag->getName(), $loggerEntity->getltTagName());
        $this->assertEquals(TagLoggerInterval::I_XS, $loggerEntity->getltInterval());
        $this->assertEquals(15, $loggerEntity->getltIntervalS());
    }
}
