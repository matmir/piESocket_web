<?php

namespace App\Tests\Entity\Admin;

use App\Entity\Admin\Tag;
use App\Entity\Admin\TagType;
use App\Entity\Admin\Alarm;
use App\Entity\Admin\AlarmTrigger;
use App\Entity\Admin\AlarmEntity;
use App\Tests\Entity\Admin\TagLoggerTest;
use PHPUnit\Framework\TestCase;

/**
 * Unit test for AlarmEntity class
 *
 * @author Mateusz Mirosławski
 */
class AlarmEntityTest extends TestCase {
    
    /**
     * Test Default constructor
     */
    public function testDefaultConstructor() {
        
        $alarmE = new AlarmEntity();
        
        $this->assertEquals(0, $alarmE->getadid());
        $this->assertEquals('', $alarmE->getadTagName());
        $this->assertEquals(0, $alarmE->getadPriority());
        $this->assertEquals('', $alarmE->getadMessage());
        $this->assertEquals(1, $alarmE->getadTrigger());
        $this->assertEquals(0, $alarmE->getadTriggerB());
        $this->assertEquals(0, $alarmE->getadTriggerN());
        $this->assertEquals(0, $alarmE->getadTriggerR());
        $this->assertEquals(0, $alarmE->getadAutoAck());
        $this->assertEquals('', $alarmE->getadFeedbackNotACK());
        $this->assertEquals('', $alarmE->getadHWAck());
    }
    
    /**
     * Test setadid method
     */
    public function testSetId() {
        
        $alarmE = new AlarmEntity();
        $alarmE->setadid(89);
        
        $this->assertEquals(89, $alarmE->getadid());
    }
    
    /**
     * Test setadTagName method
     */
    public function testSetTagName() {
        
        $alarmE = new AlarmEntity();
        $alarmE->setadTagName('TestTag');
        
        $this->assertEquals('TestTag', $alarmE->getadTagName());
    }
    
    /**
     * Test setadPriority method
     */
    public function testSetPriority() {
        
        $alarmE = new AlarmEntity();
        $alarmE->setadPriority(66);
        
        $this->assertEquals(66, $alarmE->getadPriority());
    }
    
    /**
     * Test setadMessage method
     */
    public function testSetMessage() {
        
        $alarmE = new AlarmEntity();
        $alarmE->setadMessage('Test message');
        
        $this->assertEquals('Test message', $alarmE->getadMessage());
    }
    
    /**
     * Test setadTrigger method
     */
    public function testSetTrigger() {
        
        $alarmE = new AlarmEntity();
        $alarmE->setadTrigger(2);
        
        $this->assertEquals(2, $alarmE->getadTrigger());
    }
    
    /**
     * Test setadTriggerB method
     */
    public function testSetTriggerB() {
        
        $alarmE = new AlarmEntity();
        $alarmE->setadTriggerB(22);
        
        $this->assertEquals(22, $alarmE->getadTriggerB());
    }
    
    /**
     * Test setadTriggerN method
     */
    public function testSetTriggerN() {
        
        $alarmE = new AlarmEntity();
        $alarmE->setadTriggerN(23);
        
        $this->assertEquals(23, $alarmE->getadTriggerN());
    }
    
    /**
     * Test setadTriggerR method
     */
    public function testSetTriggerR() {
        
        $alarmE = new AlarmEntity();
        $alarmE->setadTriggerR(23.7);
        
        $this->assertEquals(23.7, $alarmE->getadTriggerR());
    }
    
    /**
     * Test setadAutoAck method
     */
    public function testSetadAutoAck() {
        
        $alarmE = new AlarmEntity();
        $alarmE->setadAutoAck(1);
        
        $this->assertEquals(1, $alarmE->getadAutoAck());
    }
    
    /**
     * Test setadFeedbackNotACK method
     */
    public function testSetadFeedbackNotACK() {
        
        $alarmE = new AlarmEntity();
        $alarmE->setadFeedbackNotACK('Tag2');
        
        $this->assertEquals('Tag2', $alarmE->getadFeedbackNotACK());
    }
    
    /**
     * Test setadHWAck method
     */
    public function testSetadHWAck() {
        
        $alarmE = new AlarmEntity();
        $alarmE->setadHWAck('Tag4');
        
        $this->assertEquals('Tag4', $alarmE->getadHWAck());
    }
    
    /**
     * Test getFullAlarmObject method
     */
    public function testGetFullAlarmObject() {
        
        $tag = null;
        TagLoggerTest::createTag($tag);
        
        $tagF = null;
        TagLoggerTest::createTag($tagF);
        $tagF->setId(16);
        $tagF->setType(TagType::Bit);
        $tagF->setName('FeedbackTag');
        
        $tagH = null;
        TagLoggerTest::createTag($tagH);
        $tagH->setId(17);
        $tagH->setType(TagType::Bit);
        $tagH->setName('HWTag');
        
        $alarmE = new AlarmEntity();
        $alarmE->setadid(54);
        $alarmE->setadPriority(100);
        $alarmE->setadMessage('Alarm msg1');
        $alarmE->setadTrigger(3);
        $alarmE->setadTriggerB(1);
        $alarmE->setadTriggerN(10);
        $alarmE->setadTriggerR(1.8);
        $alarmE->setadAutoAck(1);
        $alarmE->setadFeedbackNotACK($tagF->getName());
        $alarmE->setadHWAck($tagH->getName());
        
        $alarm = $alarmE->getFullAlarmObject($tag, $tagF, $tagH);
        
        $this->assertEquals(54, $alarm->getId());
        
        $this->assertInstanceOf(Tag::class, $alarm->getTag());
        $this->assertEquals(14, $alarm->getTag()->getId());
        
        $this->assertEquals(100, $alarm->getPriority());
        $this->assertEquals('Alarm msg1', $alarm->getMessage());
        
        $this->assertEquals(AlarmTrigger::TR_TAG_LT_VAL, $alarm->getTrigger());
        
        $this->assertTrue($alarm->getTriggerBin());
        $this->assertEquals(10, $alarm->getTriggerNumeric());
        $this->assertEquals(1.8, $alarm->getTriggerReal());
        $this->assertTrue($alarm->isAutoAck());
        
        $this->assertInstanceOf(Tag::class, $alarm->getFeedbackNotAck());
        $this->assertEquals(16, $alarm->getFeedbackNotAck()->getId());
        
        
        $this->assertInstanceOf(Tag::class, $alarm->getHWAck());
        $this->assertEquals(17, $alarm->getHWAck()->getId());
    }
    
    public function testGetFullAlarmObjectWrong1() {
        
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('Tag name can not be empty');
        
        $tag = null;
        TagLoggerTest::createTag($tag);
        
        $tagF = new Tag();
        
        $tagH = null;
        TagLoggerTest::createTag($tagH);
        $tagH->setId(17);
        $tagH->setName('HWTag');
        
        $alarmE = new AlarmEntity();
        $alarmE->setadid(54);
        $alarmE->setadPriority(100);
        $alarmE->setadMessage('Alarm msg1');
        $alarmE->setadTrigger(3);
        $alarmE->setadTriggerB(1);
        $alarmE->setadTriggerN(10);
        $alarmE->setadTriggerR(1.8);
        $alarmE->setadAutoAck(1);
        $alarmE->setadFeedbackNotACK($tagF->getName());
        $alarmE->setadHWAck($tagH->getName());
        
        $alarm = $alarmE->getFullAlarmObject($tag, $tagF, $tagH);
    }
    
    public function testGetFullAlarmObjectWrong2() {
        
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('Tag name can not be empty');
        
        $tag = null;
        TagLoggerTest::createTag($tag);
        
        $tagF = null;
        TagLoggerTest::createTag($tagF);
        $tagF->setId(16);
        $tagF->setType(TagType::Bit);
        $tagF->setName('FeedbackTag');
        
        $tagH = new Tag();
        
        $alarmE = new AlarmEntity();
        $alarmE->setadid(54);
        $alarmE->setadPriority(100);
        $alarmE->setadMessage('Alarm msg1');
        $alarmE->setadTrigger(3);
        $alarmE->setadTriggerB(1);
        $alarmE->setadTriggerN(10);
        $alarmE->setadTriggerR(1.8);
        $alarmE->setadAutoAck(1);
        $alarmE->setadFeedbackNotACK($tagF->getName());
        $alarmE->setadHWAck($tagH->getName());
        
        $alarm = $alarmE->getFullAlarmObject($tag, $tagF, $tagH);
    }
    
    public function testGetFullAlarmObjectWrong3() {
        
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('Tag name can not be empty');
        
        $tag = new Tag();
        
        $tagF = null;
        TagLoggerTest::createTag($tagF);
        $tagF->setId(16);
        $tagF->setType(TagType::Bit);
        $tagF->setName('FeedbackTag');
        
        $tagH = null;
        TagLoggerTest::createTag($tagH);
        $tagH->setId(17);
        $tagH->setType(TagType::Bit);
        $tagH->setName('HWTag');
        
        $alarmE = new AlarmEntity();
        $alarmE->setadid(54);
        $alarmE->setadPriority(100);
        $alarmE->setadMessage('Alarm msg1');
        $alarmE->setadTrigger(3);
        $alarmE->setadTriggerB(1);
        $alarmE->setadTriggerN(10);
        $alarmE->setadTriggerR(1.8);
        $alarmE->setadAutoAck(1);
        $alarmE->setadFeedbackNotACK($tagF->getName());
        $alarmE->setadHWAck($tagH->getName());
        
        $alarm = $alarmE->getFullAlarmObject($tag, $tagF, $tagH);
    }
    
    /**
     * Test initFromAlarmObject method
     */
    public function testInitFromAlarmObject1() {
        
        $tag = null;
        TagLoggerTest::createTag($tag);
        
        $tagF = null;
        TagLoggerTest::createTag($tagF);
        $tagF->setId(16);
        $tagF->setType(TagType::Bit);
        $tagF->setName('FeedbackTag');
        
        $tagH = null;
        TagLoggerTest::createTag($tagH);
        $tagH->setId(17);
        $tagH->setType(TagType::Bit);
        $tagH->setName('HWTag');
        
        $alarm = new Alarm($tag);
        $alarm->setId(47);
        $alarm->setPriority(1);
        $alarm->setMessage('Message3');
        $alarm->setTrigger(AlarmTrigger::TR_TAG_GT_VAL);
        $alarm->setTriggerBin(true);
        $alarm->setTriggerNumeric(48);
        $alarm->setTriggerReal(-6.7);
        $alarm->setAutoAck(true);
        $alarm->setFeedbackNotAck($tagF);
        $alarm->setHWAck($tagH);
        
        $alarmE = new AlarmEntity();
        $alarmE->initFromAlarmObject($alarm);
        
        $this->assertEquals(47, $alarmE->getadid());
        $this->assertEquals($tag->getName(), $alarmE->getadTagName());
        $this->assertEquals(1, $alarmE->getadPriority());
        $this->assertEquals('Message3', $alarmE->getadMessage());
        $this->assertEquals(AlarmTrigger::TR_TAG_GT_VAL, $alarmE->getadTrigger());
        $this->assertEquals(1, $alarmE->getadTriggerB());
        $this->assertEquals(48, $alarmE->getadTriggerN());
        $this->assertEquals(-6.7, $alarmE->getadTriggerR());
        $this->assertEquals(1, $alarmE->getadAutoAck());
        $this->assertEquals('FeedbackTag', $alarmE->getadFeedbackNotACK());
        $this->assertEquals('HWTag', $alarmE->getadHWAck());
    }
    
    public function testInitFromAlarmObject2() {
        
        $tag = null;
        TagLoggerTest::createTag($tag);
        
        $alarm = new Alarm($tag);
        $alarm->setId(47);
        $alarm->setPriority(1);
        $alarm->setMessage('Message3');
        $alarm->setTrigger(AlarmTrigger::TR_TAG_GT_VAL);
        $alarm->setTriggerBin(true);
        $alarm->setTriggerNumeric(48);
        $alarm->setTriggerReal(-6.7);
        $alarm->setAutoAck(true);
        
        $alarmE = new AlarmEntity();
        $alarmE->initFromAlarmObject($alarm);
        
        $this->assertEquals(47, $alarmE->getadid());
        $this->assertEquals($tag->getName(), $alarmE->getadTagName());
        $this->assertEquals(1, $alarmE->getadPriority());
        $this->assertEquals('Message3', $alarmE->getadMessage());
        $this->assertEquals(AlarmTrigger::TR_TAG_GT_VAL, $alarmE->getadTrigger());
        $this->assertEquals(1, $alarmE->getadTriggerB());
        $this->assertEquals(48, $alarmE->getadTriggerN());
        $this->assertEquals(-6.7, $alarmE->getadTriggerR());
        $this->assertEquals(1, $alarmE->getadAutoAck());
        $this->assertEquals('', $alarmE->getadFeedbackNotACK());
        $this->assertEquals('', $alarmE->getadHWAck());
    }
}
