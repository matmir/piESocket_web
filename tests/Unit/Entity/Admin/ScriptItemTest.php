<?php

namespace App\Tests\Unit\Entity\Admin;

use App\Entity\Admin\Tag;
use App\Entity\Admin\TagType;
use App\Entity\Admin\ScriptItem;
use App\Tests\Unit\Entity\Admin\TagLoggerTest;
use PHPUnit\Framework\TestCase;

/**
 * Unit test for ScriptItem class
 *
 * @author Mateusz Mirosławski
 */
class ScriptItemTest extends TestCase
{
    /**
     * Test Default constructor
     */
    public function testDefaultConstructor()
    {
        $tag = null;
        TagLoggerTest::createTag($tag);
        $tag->setType(TagType::BIT);
        
        $script = new ScriptItem($tag);
        
        $this->assertEquals(0, $script->getId());
        $this->assertInstanceOf(Tag::class, $script->getTag());
        $this->assertEquals(14, $script->getTag()->getId());
        $this->assertEquals('', $script->getName());
        $this->assertFalse($script->isRunning());
        $this->assertFalse($script->isLocked());
        $this->assertFalse($script->isFeedbackRun());
        $this->assertFalse($script->isEnabled());
    }
    
    /**
     * Test setId method
     */
    public function testSetId()
    {
        $tag = null;
        TagLoggerTest::createTag($tag);
        $tag->setType(TagType::BIT);
        
        $script = new ScriptItem($tag);
        $script->setId(85);
        
        $this->assertEquals(85, $script->getId());
        $this->assertInstanceOf(Tag::class, $script->getTag());
        $this->assertEquals(14, $script->getTag()->getId());
        $this->assertEquals('', $script->getName());
        $this->assertFalse($script->isRunning());
        $this->assertFalse($script->isLocked());
        $this->assertFalse($script->isFeedbackRun());
        $this->assertFalse($script->isEnabled());
    }
    
    public function testSetIdWrong1()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('Script identifier wrong value');
        
        $tag = null;
        TagLoggerTest::createTag($tag);
        $tag->setType(TagType::BIT);
        
        $script = new ScriptItem($tag);
        $script->setId(-9);
    }
    
    /**
     * Test setTag method
     */
    public function testSetTag()
    {
        $tag = null;
        TagLoggerTest::createTag($tag);
        $tag->setType(TagType::BIT);
        
        $tagN = null;
        TagLoggerTest::createTag($tagN);
        $tagN->setId(66);
        $tagN->setType(TagType::BIT);
        
        $script = new ScriptItem($tag);
        $script->setTag($tagN);
        
        $this->assertEquals(66, $script->getTag()->getId());
    }
    
    /**
     * Test setName method
     */
    public function testSetName()
    {
        $tag = null;
        TagLoggerTest::createTag($tag);
        $tag->setType(TagType::BIT);
        
        $script = new ScriptItem($tag);
        $script->setName('script.sh');
        
        $this->assertEquals(0, $script->getId());
        $this->assertInstanceOf(Tag::class, $script->getTag());
        $this->assertEquals(14, $script->getTag()->getId());
        $this->assertEquals('script.sh', $script->getName());
        $this->assertFalse($script->isRunning());
        $this->assertFalse($script->isLocked());
        $this->assertFalse($script->isFeedbackRun());
        $this->assertFalse($script->isEnabled());
    }
    
    public function testSetNameWrong1()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('Script name can not be empty');
        
        $tag = null;
        TagLoggerTest::createTag($tag);
        $tag->setType(TagType::BIT);
        
        $script = new ScriptItem($tag);
        $script->setName(' ');
    }
    
    /**
     * Test setRun method
     */
    public function testSetRun()
    {
        $tag = null;
        TagLoggerTest::createTag($tag);
        $tag->setType(TagType::BIT);
        
        $script = new ScriptItem($tag);
        $script->setRun(true);
        
        $this->assertEquals(0, $script->getId());
        $this->assertInstanceOf(Tag::class, $script->getTag());
        $this->assertEquals(14, $script->getTag()->getId());
        $this->assertEquals('', $script->getName());
        $this->assertTrue($script->isRunning());
        $this->assertFalse($script->isLocked());
        $this->assertFalse($script->isFeedbackRun());
        $this->assertFalse($script->isEnabled());
    }
    
    /**
     * Test setLocked method
     */
    public function testSetLocked()
    {
        $tag = null;
        TagLoggerTest::createTag($tag);
        $tag->setType(TagType::BIT);
        
        $script = new ScriptItem($tag);
        $script->setLocked(true);
        
        $this->assertEquals(0, $script->getId());
        $this->assertInstanceOf(Tag::class, $script->getTag());
        $this->assertEquals(14, $script->getTag()->getId());
        $this->assertEquals('', $script->getName());
        $this->assertFalse($script->isRunning());
        $this->assertTrue($script->isLocked());
        $this->assertFalse($script->isFeedbackRun());
        $this->assertFalse($script->isEnabled());
    }
    
    /**
     * Test setFeedbackRun method
     */
    public function testSetFeedbackRun1()
    {
        $tag = null;
        TagLoggerTest::createTag($tag);
        $tag->setType(TagType::BIT);
        
        $script = new ScriptItem($tag);
        
        $this->assertNull($script->getFeedbackRun());
    }
    
    public function testSetFeedbackNotAck2()
    {
        $tag = null;
        TagLoggerTest::createTag($tag);
        $tag->setType(TagType::BIT);
        
        $tagF = null;
        TagLoggerTest::createTag($tagF);
        $tagF->setId(101);
        $tagF->setType(TagType::BIT);
        
        $script = new ScriptItem($tag);
        $script->setFeedbackRun($tagF);
        
        $this->assertTrue($script->isFeedbackRun());
        $this->assertInstanceOf(Tag::class, $script->getFeedbackRun());
        $this->assertEquals(101, $script->getFeedbackRun()->getId());
    }
    
    /**
     * Test setEnabled method
     */
    public function testSetEnabled()
    {
        $tag = null;
        TagLoggerTest::createTag($tag);
        $tag->setType(TagType::BIT);
        
        $script = new ScriptItem($tag);
        $script->setEnabled(true);
        
        $this->assertEquals(0, $script->getId());
        $this->assertInstanceOf(Tag::class, $script->getTag());
        $this->assertEquals(14, $script->getTag()->getId());
        $this->assertEquals('', $script->getName());
        $this->assertFalse($script->isRunning());
        $this->assertFalse($script->isLocked());
        $this->assertFalse($script->isFeedbackRun());
        $this->assertTrue($script->isEnabled());
    }
    
    /**
     * Test isValid method
     */
    public function testIsValid()
    {
        $tag = null;
        TagLoggerTest::createTag($tag);
        $tag->setType(TagType::BIT);
        
        $tagFB = null;
        TagLoggerTest::createTag($tagFB);
        $tagFB->setName('tettt');
        $tagFB->setType(TagType::BIT);
        
        $script = new ScriptItem($tag, $tagFB);
        $script->setName('test');
        
        $this->assertTrue($script->isValid(true));
    }
    
    public function testIsValidErr1()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('Missing Tag object');
        
        $tag = null;
        $script = new ScriptItem($tag);
        $script->setName('test');
        
        $this->assertTrue($script->isValid(true));
    }
}
