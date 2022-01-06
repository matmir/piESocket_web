<?php

namespace App\Tests\Unit\Entity\Admin;

use App\Entity\Admin\Tag;
use App\Entity\Admin\TagType;
use App\Entity\Admin\TagArea;
use App\Entity\Admin\TagLogger;
use App\Entity\Admin\TagLoggerInterval;
use PHPUnit\Framework\TestCase;

/**
 * Unit test for TagLogger class
 *
 * @author Mateusz Mirosławski
 */
class TagLoggerTest extends TestCase
{
    /**
     * Create simple Tag object
     *
     * @param $tag Tag object
     */
    public static function createTag(&$tag)
    {
        $tag = new Tag();
        $tag->setId(14);
        $tag->setName('TestTag');
        $tag->setArea(TagArea::MEMORY);
        $tag->setType(TagType::BYTE);
        $tag->setByteAddress(100);
        $tag->setBitAddress(0);
    }
    
    /**
     * Test Default constructor
     */
    public function testDefaultConstructor()
    {
        $tag = null;
        $this->createTag($tag);
        
        $tagLog = new TagLogger($tag);
        
        $this->assertEquals(0, $tagLog->getId());
        
        $this->assertInstanceOf(Tag::class, $tagLog->getTag());
        $this->assertEquals(14, $tagLog->getTag()->getId());
        
        $this->assertEquals(TagLoggerInterval::I_1S, $tagLog->getInterval());
        
        $this->assertEquals(0, $tagLog->getIntervalS());
        $this->assertEquals('none', $tagLog->getLastLogTime());
        $this->assertEquals(0, $tagLog->getLastValue());
        $this->assertFalse($tagLog->isEnabled());
    }
    
    public function testDefaultConstructorWrong()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('Tag name can not be empty');
        
        $tag = new Tag();
        
        $tagLog = new TagLogger($tag);
    }
    
    /**
     * Test setId method
     */
    public function testSetId()
    {
        $tag = null;
        $this->createTag($tag);
        
        $tagLog = new TagLogger($tag);
        $tagLog->setId(45);
        
        $this->assertEquals(45, $tagLog->getId());
    }
    
    public function testSetIdWrong2()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('Tag logger identifier wrong value');
        
        $tag = null;
        $this->createTag($tag);
        
        $tagLog = new TagLogger($tag);
        $tagLog->setId(-5);
    }
    
    /**
     * Test setTag method
     */
    public function testSetTag()
    {
        $tag = null;
        $this->createTag($tag);
        
        $tagN = null;
        $this->createTag($tagN);
        $tagN->setId(66);
        
        $tagLog = new TagLogger($tag);
        $tagLog->setTag($tagN);
        
        $this->assertEquals(66, $tagLog->getTag()->getId());
    }
    
    public function testSetTagWrong()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('Tag name can not be empty');
        
        $tag = null;
        $this->createTag($tag);
        
        $tagN = new Tag();
        
        $tagLog = new TagLogger($tag);
        $tagLog->setTag($tagN);
    }
    
    /**
     * Test setInterval method
     */
    public function testSetInterval()
    {
        $tag = null;
        $this->createTag($tag);
        
        $tagLog = new TagLogger($tag);
        $tagLog->setInterval(TagLoggerInterval::I_100MS);
        
        $this->assertEquals(TagLoggerInterval::I_100MS, $tagLog->getInterval());
    }
    
    /**
     * Test setIntervalS method
     */
    public function testSetIntervalS1()
    {
        $tag = null;
        $this->createTag($tag);
        
        $tagLog = new TagLogger($tag);
        $tagLog->setIntervalS(8);
        
        $this->assertEquals(8, $tagLog->getIntervalS());
    }
    
    public function testSetIntervalS2()
    {
        $tag = null;
        $this->createTag($tag);
        
        $tagLog = new TagLogger($tag);
        $tagLog->setIntervalS(0);
        
        $this->assertEquals(0, $tagLog->getIntervalS());
    }
    
    public function testSetIntervalSWrong()
    {
        $this->expectException(\App\Entity\AppException::class);
        $this->expectExceptionMessage('Tag logger interval seconds should be greater than 0');
        
        $tag = null;
        $this->createTag($tag);
        
        $tagLog = new TagLogger($tag);
        $tagLog->setInterval(TagLoggerInterval::I_XS);
        
        $tagLog->setIntervalS(0);
    }
    
    /**
     * Test setLastLogTime method
     */
    public function testSetLastLogTime()
    {
        $tag = null;
        $this->createTag($tag);
        
        $tagLog = new TagLogger($tag);
        $tagLog->setLastLogTime('2018-01-01 12:00:00');
        
        $this->assertEquals('2018-01-01 12:00:00', $tagLog->getLastLogTime());
    }
    
    public function testSetLastLogTimeWrong()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('Tag logger update time can not be empty');
        
        $tag = null;
        $this->createTag($tag);
        
        $tagLog = new TagLogger($tag);
        $tagLog->setLastLogTime(' ');
    }
    
    /**
     * Test setValue method
     */
    public function testSetValueNoConvert()
    {
        $tag = null;
        $this->createTag($tag);
        
        $tagLog = new TagLogger($tag);
        $tagLog->setLastValue(5);
        
        $this->assertEquals(5, $tagLog->getLastValue());
    }
    
    public function testSetValueConvert1()
    {
        $tag = null;
        $this->createTag($tag);
        $tag->setType(TagType::BIT);
        
        $tagLog = new TagLogger($tag);
        $tagLog->setLastValue(5);
        
        $this->assertEquals(TagType::BIT, $tagLog->getTag()->getType());
        $this->assertIsBool($tagLog->getLastValue(true));
        $this->assertTrue($tagLog->getLastValue(true));
    }
    
    public function testSetValueConvert2()
    {
        $tag = null;
        $this->createTag($tag);
        $tag->setType(TagType::REAL);
        
        $tagLog = new TagLogger($tag);
        $tagLog->setLastValue(-5.14);
        
        $this->assertEquals(TagType::REAL, $tagLog->getTag()->getType());
        $this->assertIsFloat($tagLog->getLastValue(true));
        $this->assertEquals(-5.14, $tagLog->getLastValue(true));
    }
    
    public function testSetValueConvert3()
    {
        $tag = null;
        $this->createTag($tag);
        $tag->setType(TagType::DWORD);
        
        $tagLog = new TagLogger($tag);
        $tagLog->setLastValue(25015);
        
        $this->assertEquals(TagType::DWORD, $tagLog->getTag()->getType());
        $this->assertIsInt($tagLog->getLastValue(true));
        $this->assertEquals(25015, $tagLog->getLastValue(true));
    }
    
    public function testSetValueWrong()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('Tag logger last value need to be numeric');
        
        $tag = null;
        $this->createTag($tag);
        
        $tagLog = new TagLogger($tag);
        $tagLog->setLastValue('five');
    }
    
    /**
     * Test setEnabled method
     */
    public function testSetEnabled()
    {
        $tag = null;
        $this->createTag($tag);
        
        $tagLog = new TagLogger($tag);
        $tagLog->setEnabled(true);
        
        $this->assertTrue($tagLog->isEnabled());
    }
    
    /**
     * Test isValid method
     */
    public function testIsValid()
    {
        $tag = null;
        $this->createTag($tag);
        
        $tagLog = new TagLogger($tag);
        
        $this->assertTrue($tagLog->isValid(true));
    }
    
    public function testIsValidErr1()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('Missing Tag object');
        
        $tag = null;
        $tagLog = new TagLogger($tag);
        
        $this->assertTrue($tagLog->isValid(true));
    }
}
