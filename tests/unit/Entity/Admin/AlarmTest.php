<?php

namespace App\Tests\Entity\Admin;

use App\Entity\Admin\Tag;
use App\Entity\Admin\TagType;
use App\Entity\Admin\Alarm;
use App\Entity\Admin\AlarmTrigger;
use App\Entity\AppException;
use App\Tests\Entity\Admin\TagLoggerTest;
use PHPUnit\Framework\TestCase;

/**
 * Unit test for Alarm class
 *
 * @author Mateusz Mirosławski
 */
class AlarmTest extends TestCase
{
    /**
     * Test Default constructor
     */
    public function testDefaultConstructor()
    {
        $tag = null;
        TagLoggerTest::createTag($tag);
        
        $alarm = new Alarm($tag);
        
        $this->assertEquals(0, $alarm->getId());
        
        $this->assertInstanceOf(Tag::class, $alarm->getTag());
        $this->assertEquals(14, $alarm->getTag()->getId());
        
        $this->assertEquals(1, $alarm->getPriority());
        $this->assertEquals('', $alarm->getMessage());
        
        $this->assertEquals(AlarmTrigger::TR_BIN, $alarm->getTrigger());
        
        $this->assertFalse($alarm->getTriggerBin());
        $this->assertEquals(0, $alarm->getTriggerNumeric());
        $this->assertEquals(0, $alarm->getTriggerReal());
        $this->assertFalse($alarm->isAutoAck());
        $this->assertFalse($alarm->isActive());
        $this->assertFalse($alarm->isPending());
        $this->assertNull($alarm->getFeedbackNotAck());
        $this->assertNull($alarm->getHWAck());
        $this->assertFalse($alarm->isEnabled());
    }
    
    public function testDefaultConstructorWrong()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('Tag name can not be empty');
        
        $tag = new Tag();
        
        $alarm = new Alarm($tag);
    }
    
    /**
     * Test setId method
     */
    public function testSetId()
    {
        $tag = null;
        TagLoggerTest::createTag($tag);
        
        $alarm = new Alarm($tag);
        $alarm->setId(35);
        
        $this->assertEquals(35, $alarm->getId());
    }
    
    public function testSetIdWrong1()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('Alarm identifier wrong value');
        
        $tag = null;
        TagLoggerTest::createTag($tag);
        
        $alarm = new Alarm($tag);
        $alarm->setId(-8);
    }
    
    /**
     * Test setTag method
     */
    public function testSetTag()
    {
        $tag = null;
        TagLoggerTest::createTag($tag);
        
        $tagN = null;
        TagLoggerTest::createTag($tagN);
        $tagN->setId(66);
        
        $alarm = new Alarm($tag);
        $alarm->setTag($tagN);
        
        $this->assertEquals(66, $alarm->getTag()->getId());
    }
    
    public function testSetTagWrong()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('Tag name can not be empty');
        
        $tag = null;
        TagLoggerTest::createTag($tag);
        
        $tagN = new Tag();
        
        $alarm = new Alarm($tag);
        $alarm->setTag($tagN);
    }
    
    /**
     * Test setPriority method
     */
    public function testSetPriority()
    {
        $tag = null;
        TagLoggerTest::createTag($tag);
        
        $alarm = new Alarm($tag);
        $alarm->setPriority(80);
        
        $this->assertEquals(80, $alarm->getPriority());
    }
    
    public function testSetPriorityWrong()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('Alarm priority wrong value');
        
        $tag = null;
        TagLoggerTest::createTag($tag);
        
        $alarm = new Alarm($tag);
        $alarm->setPriority(-80);
    }
    
    /**
     * Test setMessage method
     */
    public function testSetMessage()
    {
        $tag = null;
        TagLoggerTest::createTag($tag);
        
        $alarm = new Alarm($tag);
        $alarm->setMessage('Some alarm');
        
        $this->assertEquals('Some alarm', $alarm->getMessage());
    }
    
    public function testSetMessageWrong()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('Alarm message can not be empty');
        
        $tag = null;
        TagLoggerTest::createTag($tag);
        
        $alarm = new Alarm($tag);
        $alarm->setMessage(' ');
    }
    
    /**
     * Test setTrigger method
     */
    public function testSetTrigger()
    {
        $tag = null;
        TagLoggerTest::createTag($tag);
        
        $alarm = new Alarm($tag);
        $alarm->setTrigger(AlarmTrigger::TR_TAG_GT_VAL);
        
        $this->assertEquals(AlarmTrigger::TR_TAG_GT_VAL, $alarm->getTrigger());
    }
    
    /**
     * Test setTriggerBin method
     */
    public function testSetTriggerBin()
    {
        $tag = null;
        TagLoggerTest::createTag($tag);
                
        $alarm = new Alarm($tag);
        $alarm->setTriggerBin(true);
        
        $this->assertTrue($alarm->getTriggerBin());
    }
    
    /**
     * Test setTriggerNumeric method
     */
    public function testSetTriggerNumeric()
    {
        $tag = null;
        TagLoggerTest::createTag($tag);
                
        $alarm = new Alarm($tag);
        $alarm->setTriggerNumeric(-850);
        
        $this->assertEquals(-850, $alarm->getTriggerNumeric());
    }
    
    /**
     * Test setTriggerReal method
     */
    public function testSetTriggerReal()
    {
        $tag = null;
        TagLoggerTest::createTag($tag);
                
        $alarm = new Alarm($tag);
        $alarm->setTriggerReal(-3.14);
        
        $this->assertEquals(-3.14, $alarm->getTriggerReal());
    }
    
    /**
     * Test setAutoAck method
     */
    public function testSetAutoAck()
    {
        $tag = null;
        TagLoggerTest::createTag($tag);
                
        $alarm = new Alarm($tag);
        $alarm->setAutoAck(true);
        
        $this->assertTrue($alarm->isAutoAck());
    }
    
    /**
     * Test setActive method
     */
    public function testSetActive()
    {
        $tag = null;
        TagLoggerTest::createTag($tag);
                
        $alarm = new Alarm($tag);
        $alarm->setActive(true);
        
        $this->assertTrue($alarm->isActive());
    }
    
    /**
     * Test setPending method
     */
    public function testSetPending()
    {
        $tag = null;
        TagLoggerTest::createTag($tag);
                
        $alarm = new Alarm($tag);
        $alarm->setPending(true);
        
        $this->assertTrue($alarm->isPending());
    }
    
    /**
     * Test setFeedbackNotAck method
     */
    public function testSetFeedbackNotAck1()
    {
        $tag = null;
        TagLoggerTest::createTag($tag);
                
        $alarm = new Alarm($tag);
        $alarm->setFeedbackNotAck();
        
        $this->assertNull($alarm->getFeedbackNotAck());
    }
    
    public function testSetFeedbackNotAck2()
    {
        $tag = null;
        TagLoggerTest::createTag($tag);
        
        $tagF = null;
        TagLoggerTest::createTag($tagF);
        $tagF->setId(10);
        $tagF->setType(TagType::BIT);
                
        $alarm = new Alarm($tag);
        $alarm->setFeedbackNotAck($tagF);
        
        $this->assertInstanceOf(Tag::class, $alarm->getFeedbackNotAck());
        $this->assertEquals(10, $alarm->getFeedbackNotAck()->getId());
    }
    
    public function testSetFeedbackNotAckWrong1()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('Tag name can not be empty');
        
        $tag = null;
        TagLoggerTest::createTag($tag);
        
        $tagF = new Tag();
                
        $alarm = new Alarm($tag);
        $alarm->setFeedbackNotAck($tagF);
    }
    
    public function testSetFeedbackNotAckWrong2()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('Feedback Tag is wrong type');
        
        $tag = null;
        TagLoggerTest::createTag($tag);
                        
        $alarm = new Alarm($tag);
        $alarm->setFeedbackNotAck('TagWrong');
    }
    
    /**
     * Test isFeedbackNotAck method
     */
    public function testIsFeedbackNotAck1()
    {
        $tag = null;
        TagLoggerTest::createTag($tag);
                        
        $alarm = new Alarm($tag);
        
        $this->assertFalse($alarm->isFeedbackNotAck());
    }
    
    public function testIsFeedbackNotAck2()
    {
        $tag = null;
        TagLoggerTest::createTag($tag);
        
        $tagF = null;
        TagLoggerTest::createTag($tagF);
        $tagF->setId(10);
        $tagF->setType(TagType::BIT);
                        
        $alarm = new Alarm($tag);
        $alarm->setFeedbackNotAck($tagF);
        
        $this->assertTrue($alarm->isFeedbackNotAck());
    }
    
    /**
     * Test setHWAck method
     */
    public function testSetHWAck1()
    {
        $tag = null;
        TagLoggerTest::createTag($tag);
                
        $alarm = new Alarm($tag);
        $alarm->setHWAck();
        
        $this->assertNull($alarm->getHWAck());
    }
    
    public function testSetHWAck2()
    {
        $tag = null;
        TagLoggerTest::createTag($tag);
        
        $tagF = null;
        TagLoggerTest::createTag($tagF);
        $tagF->setId(10);
        $tagF->setType(TagType::BIT);
                
        $alarm = new Alarm($tag);
        $alarm->setHWAck($tagF);
        
        $this->assertInstanceOf(Tag::class, $alarm->getHWAck());
        $this->assertEquals(10, $alarm->getHWAck()->getId());
    }
    
    public function testSetHWAckWrong1()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('Tag name can not be empty');
        
        $tag = null;
        TagLoggerTest::createTag($tag);
        
        $tagF = new Tag();
                
        $alarm = new Alarm($tag);
        $alarm->setHWAck($tagF);
    }
    
    public function testSetHWAckWrong2()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('HW acknowledgment Tag is wrong type');
        
        $tag = null;
        TagLoggerTest::createTag($tag);
                        
        $alarm = new Alarm($tag);
        $alarm->setHWAck('TagWrong');
    }
    
    /**
     * Test isHWAck method
     */
    public function testIsHWAck1()
    {
        $tag = null;
        TagLoggerTest::createTag($tag);
                        
        $alarm = new Alarm($tag);
        
        $this->assertFalse($alarm->isHWAck());
    }
    
    public function testIsHWAck2()
    {
        $tag = null;
        TagLoggerTest::createTag($tag);
        
        $tagF = null;
        TagLoggerTest::createTag($tagF);
        $tagF->setId(10);
        $tagF->setType(TagType::BIT);
                        
        $alarm = new Alarm($tag);
        $alarm->setHWAck($tagF);
        
        $this->assertTrue($alarm->isHWAck());
    }
    
    /**
     * Test setEnabled method
     */
    public function testSetEnabled()
    {
        $tag = null;
        TagLoggerTest::createTag($tag);
                                
        $alarm = new Alarm($tag);
        $alarm->setEnable(true);
        
        $this->assertTrue($alarm->isEnabled());
    }
    
    /**
     * Test isValid method
     */
    public function testIsValid()
    {
        $tag = null;
        TagLoggerTest::createTag($tag);
        
        $tagFB = null;
        TagLoggerTest::createTag($tagFB);
        $tagFB->setName('tettt');
        $tagFB->setType(TagType::BIT);
        
        $tagHW = null;
        TagLoggerTest::createTag($tagHW);
        $tagHW->setName('tetttw');
        $tagHW->setType(TagType::BIT);
                                
        $alarm = new Alarm($tag, $tagFB, $tagHW);
        $alarm->setMessage("Test message");
        $alarm->setTrigger(AlarmTrigger::TR_TAG_EQ_VAL);
        
        $this->assertTrue($alarm->isValid(true));
    }
    
    public function testIsValidErr1()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('Missing Tag object');
        
        $alarm = new Alarm();
        $alarm->setMessage("Test message");
        
        $this->assertTrue($alarm->isValid(true));
    }
    
    public function testIsValidWrong1()
    {
        $this->expectException(AppException::class);
        $this->expectExceptionMessage('Alarm trigger need to be BIT type');
        $this->expectExceptionCode(AppException::ALARM_TRIGGER_WRONG_TYPE);
        
        $tag = null;
        TagLoggerTest::createTag($tag);
        $tag->setType(TagType::BIT);
                                
        $alarm = new Alarm($tag);
        $alarm->setMessage("test msg");
        $alarm->setTrigger(AlarmTrigger::TR_TAG_EQ_VAL);
        
        $alarm->isValid(true);
    }
    
    public function testIsValidWrong2()
    {
        $this->expectException(AppException::class);
        $this->expectExceptionMessage('Alarm trigger need to be numeric type');
        $this->expectExceptionCode(AppException::ALARM_TRIGGER_WRONG_TYPE);
        
        $tag = null;
        TagLoggerTest::createTag($tag);
                                
        $alarm = new Alarm($tag);
        $alarm->setMessage("test msg");
        $alarm->setTrigger(AlarmTrigger::TR_BIN);
        
        $alarm->isValid(true);
    }
}
