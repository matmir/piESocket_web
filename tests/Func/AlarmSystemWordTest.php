<?php

namespace App\Tests\Func;

use App\Tests\AlarmSystemFunctionTestCase;
use App\Entity\Admin\AlarmTrigger;
use App\Entity\Admin\TagType;

/**
 * Function tests for Alarm system - WORD alarm
 *
 * @author Mateusz Mirosławski
 */
class AlarmSystemWordTest extends AlarmSystemFunctionTestCase
{
    /**
     * Test WORD alarm Tag>value
     */
    public function testWord1()
    {
        // Get alarm definition
        $alarmDef = $this->getAlarmDefinition(TagType::WORD);
        
        $data1 = $this->getAlarmsData($alarmDef);
        
        // Activate alarm definition
        $this->alarmMapper->enableAlarm($alarmDef->getId());
        
        // Alarm ON
        $this->parser->writeWord($alarmDef->getTag()->getName(), 25002);
        
        // Wait on alarm pending state
        $this->waitOnAlarmPendingState($alarmDef->getId());
                        
        $data2 = $this->getAlarmsData($alarmDef);
        
        // Alarm OFF
        $this->parser->writeWord($alarmDef->getTag()->getName(), 15600);
        
        // Wait on alarm active state to be off
        $this->waitOnAlarmActiveState($alarmDef->getId(), false);
        
        $data3 = $this->getAlarmsData($alarmDef);
        
        // Alarm ACK
        $this->parser->setBit($alarmDef->getHWAck()->getName());
        
        // Wait on alarm pending state to be off
        $this->waitOnAlarmPendingState($alarmDef->getId(), false);
                
        $data4 = $this->getAlarmsData($alarmDef);
        
        // Alarm ACK
        $this->parser->resetBit($alarmDef->getHWAck()->getName());
        $this->waitOnProcessDataSync();
        
        // Deactivate alarm definition
        $this->alarmMapper->enableAlarm($alarmDef->getId(), false);
        
        $this->assertEquals(TagType::WORD, $alarmDef->getTag()->getType());
        $this->assertEquals(AlarmTrigger::TR_TAG_GT_VAL, $alarmDef->getTrigger());
        $this->assertEquals(25000, $alarmDef->getTriggerNumeric());
        $this->assertEquals('TEST_ALARM_WORD', $alarmDef->getTag()->getName());
        $this->assertEquals('ALARM_WORD_NACK', $alarmDef->getFeedbackNotAck()->getName());
        $this->assertEquals('ALARM_HW_ACK', $alarmDef->getHWAck()->getName());
        $this->assertFalse($alarmDef->isAutoAck());
        $this->assertFalse($alarmDef->isActive());
        $this->assertFalse($alarmDef->isPending());
        
        // Data1
        $this->assertFalse($data1['alarmDef']->isActive());
        $this->assertFalse($data1['alarmDef']->isPending());
        $this->assertEquals(0, count($data1['pending']));
        $this->assertEquals(0, count($data1['archive']));
        $this->assertFalse($data1['feedbackNotAck']);
        
        // Data 2
        $this->assertTrue($data2['alarmDef']->isActive());
        $this->assertTrue($data2['alarmDef']->isPending());
        
        $this->assertEquals(1, count($data2['pending']));
        $this->assertEquals($alarmDef->getId(), $data2['pending'][0]->getDefinitionId());
        $this->assertEquals('WORD alarm', $data2['pending'][0]->getMessage());
        $this->assertTrue($data2['pending'][0]->isActive());
        $this->assertFalse($data2['pending'][0]->isAck());
        
        $this->assertEquals(0, count($data2['archive']));
        $this->assertTrue($data2['feedbackNotAck']);
        
        // Data 3
        $this->assertFalse($data3['alarmDef']->isActive());
        $this->assertTrue($data3['alarmDef']->isPending());
        
        $this->assertEquals(1, count($data3['pending']));
        $this->assertEquals($alarmDef->getId(), $data3['pending'][0]->getDefinitionId());
        $this->assertEquals('WORD alarm', $data3['pending'][0]->getMessage());
        $this->assertFalse($data3['pending'][0]->isActive());
        $this->assertFalse($data3['pending'][0]->isAck());
        
        $this->assertEquals(0, count($data3['archive']));
        $this->assertTrue($data3['feedbackNotAck']);
        
        // Data 4
        $this->assertFalse($data4['alarmDef']->isActive());
        $this->assertFalse($data4['alarmDef']->isPending());
        
        $this->assertEquals(0, count($data4['pending']));
        
        $this->assertEquals(1, count($data4['archive']));
        $this->assertEquals($alarmDef->getId(), $data4['archive'][0]->getDefinitionId());
        $this->assertEquals('WORD alarm', $data4['archive'][0]->getMessage());
        $this->assertFalse($data4['archive'][0]->isActive());
        $this->assertFalse($data4['archive'][0]->isAck());
        $this->assertFalse($data4['feedbackNotAck']);
    }
    
    /**
     * Test WORD alarm Tag<value
     */
    public function testWord2()
    {
        // Get alarm definition
        $alarmDef = $this->getAlarmDefinition(TagType::WORD);
        // Change trigger
        $alarmDef->setTrigger(AlarmTrigger::TR_TAG_LT_VAL);
        $alarmDef->setTriggerNumeric(15500);
        // Save alarm definition
        $this->alarmMapper->editAlarm($alarmDef);
        
        $data1 = $this->getAlarmsData($alarmDef);
        
        // Activate alarm definition
        $this->alarmMapper->enableAlarm($alarmDef->getId());
        
        // Alarm ON
        $this->parser->writeWord($alarmDef->getTag()->getName(), 15499);
        
        // Wait on alarm pending state
        $this->waitOnAlarmPendingState($alarmDef->getId());
                        
        $data2 = $this->getAlarmsData($alarmDef);
        
        // Alarm OFF
        $this->parser->writeWord($alarmDef->getTag()->getName(), 15600);
        
        // Wait on alarm active state to be off
        $this->waitOnAlarmActiveState($alarmDef->getId(), false);
        
        $data3 = $this->getAlarmsData($alarmDef);
        
        // Alarm ACK
        $this->parser->setBit($alarmDef->getHWAck()->getName());
        
        // Wait on alarm pending state to be off
        $this->waitOnAlarmPendingState($alarmDef->getId(), false);
                
        $data4 = $this->getAlarmsData($alarmDef);
        
        // Alarm ACK
        $this->parser->resetBit($alarmDef->getHWAck()->getName());
        $this->waitOnProcessDataSync();
        
        // Deactivate alarm definition
        $this->alarmMapper->enableAlarm($alarmDef->getId(), false);
        
        $this->assertEquals(TagType::WORD, $alarmDef->getTag()->getType());
        $this->assertEquals(AlarmTrigger::TR_TAG_LT_VAL, $alarmDef->getTrigger());
        $this->assertEquals(15500, $alarmDef->getTriggerNumeric());
        $this->assertEquals('TEST_ALARM_WORD', $alarmDef->getTag()->getName());
        $this->assertEquals('ALARM_WORD_NACK', $alarmDef->getFeedbackNotAck()->getName());
        $this->assertEquals('ALARM_HW_ACK', $alarmDef->getHWAck()->getName());
        $this->assertFalse($alarmDef->isAutoAck());
        $this->assertFalse($alarmDef->isActive());
        $this->assertFalse($alarmDef->isPending());
        
        // Data1
        $this->assertFalse($data1['alarmDef']->isActive());
        $this->assertFalse($data1['alarmDef']->isPending());
        $this->assertEquals(0, count($data1['pending']));
        $this->assertEquals(0, count($data1['archive']));
        $this->assertFalse($data1['feedbackNotAck']);
        
        // Data 2
        $this->assertTrue($data2['alarmDef']->isActive());
        $this->assertTrue($data2['alarmDef']->isPending());
        
        $this->assertEquals(1, count($data2['pending']));
        $this->assertEquals($alarmDef->getId(), $data2['pending'][0]->getDefinitionId());
        $this->assertEquals('WORD alarm', $data2['pending'][0]->getMessage());
        $this->assertTrue($data2['pending'][0]->isActive());
        $this->assertFalse($data2['pending'][0]->isAck());
        
        $this->assertEquals(0, count($data2['archive']));
        $this->assertTrue($data2['feedbackNotAck']);
        
        // Data 3
        $this->assertFalse($data3['alarmDef']->isActive());
        $this->assertTrue($data3['alarmDef']->isPending());
        
        $this->assertEquals(1, count($data3['pending']));
        $this->assertEquals($alarmDef->getId(), $data3['pending'][0]->getDefinitionId());
        $this->assertEquals('WORD alarm', $data3['pending'][0]->getMessage());
        $this->assertFalse($data3['pending'][0]->isActive());
        $this->assertFalse($data3['pending'][0]->isAck());
        
        $this->assertEquals(0, count($data3['archive']));
        $this->assertTrue($data3['feedbackNotAck']);
        
        // Data 4
        $this->assertFalse($data4['alarmDef']->isActive());
        $this->assertFalse($data4['alarmDef']->isPending());
        
        $this->assertEquals(0, count($data4['pending']));
        
        $this->assertEquals(1, count($data4['archive']));
        $this->assertEquals($alarmDef->getId(), $data4['archive'][0]->getDefinitionId());
        $this->assertEquals('WORD alarm', $data4['archive'][0]->getMessage());
        $this->assertFalse($data4['archive'][0]->isActive());
        $this->assertFalse($data4['archive'][0]->isAck());
        $this->assertFalse($data4['feedbackNotAck']);
    }
    
    /**
     * Test WORD alarm Tag>=value
     */
    public function testWord3()
    {
        // Get alarm definition
        $alarmDef = $this->getAlarmDefinition(TagType::WORD);
        // Change trigger
        $alarmDef->setTrigger(AlarmTrigger::TR_TAG_GTE_VAL);
        $alarmDef->setTriggerNumeric(35000);
        // Save alarm definition
        $this->alarmMapper->editAlarm($alarmDef);
        
        $data1 = $this->getAlarmsData($alarmDef);
        
        // Activate alarm definition
        $this->alarmMapper->enableAlarm($alarmDef->getId());
        
        // Alarm ON
        $this->parser->writeWord($alarmDef->getTag()->getName(), 35000);
        
        // Wait on alarm pending state
        $this->waitOnAlarmPendingState($alarmDef->getId());
                        
        $data2 = $this->getAlarmsData($alarmDef);
        
        // Alarm OFF
        $this->parser->writeWord($alarmDef->getTag()->getName(), 15600);
        
        // Wait on alarm active state to be off
        $this->waitOnAlarmActiveState($alarmDef->getId(), false);
        
        $data3 = $this->getAlarmsData($alarmDef);
        
        // Alarm ACK
        $this->parser->setBit($alarmDef->getHWAck()->getName());
        
        // Wait on alarm pending state to be off
        $this->waitOnAlarmPendingState($alarmDef->getId(), false);
                
        $data4 = $this->getAlarmsData($alarmDef);
        
        // Alarm ACK
        $this->parser->resetBit($alarmDef->getHWAck()->getName());
        $this->waitOnProcessDataSync();
        
        // Deactivate alarm definition
        $this->alarmMapper->enableAlarm($alarmDef->getId(), false);
        
        $this->assertEquals(TagType::WORD, $alarmDef->getTag()->getType());
        $this->assertEquals(AlarmTrigger::TR_TAG_GTE_VAL, $alarmDef->getTrigger());
        $this->assertEquals(35000, $alarmDef->getTriggerNumeric());
        $this->assertEquals('TEST_ALARM_WORD', $alarmDef->getTag()->getName());
        $this->assertEquals('ALARM_WORD_NACK', $alarmDef->getFeedbackNotAck()->getName());
        $this->assertEquals('ALARM_HW_ACK', $alarmDef->getHWAck()->getName());
        $this->assertFalse($alarmDef->isAutoAck());
        $this->assertFalse($alarmDef->isActive());
        $this->assertFalse($alarmDef->isPending());
        
        // Data1
        $this->assertFalse($data1['alarmDef']->isActive());
        $this->assertFalse($data1['alarmDef']->isPending());
        $this->assertEquals(0, count($data1['pending']));
        $this->assertEquals(0, count($data1['archive']));
        $this->assertFalse($data1['feedbackNotAck']);
        
        // Data 2
        $this->assertTrue($data2['alarmDef']->isActive());
        $this->assertTrue($data2['alarmDef']->isPending());
        
        $this->assertEquals(1, count($data2['pending']));
        $this->assertEquals($alarmDef->getId(), $data2['pending'][0]->getDefinitionId());
        $this->assertEquals('WORD alarm', $data2['pending'][0]->getMessage());
        $this->assertTrue($data2['pending'][0]->isActive());
        $this->assertFalse($data2['pending'][0]->isAck());
        
        $this->assertEquals(0, count($data2['archive']));
        $this->assertTrue($data2['feedbackNotAck']);
        
        // Data 3
        $this->assertFalse($data3['alarmDef']->isActive());
        $this->assertTrue($data3['alarmDef']->isPending());
        
        $this->assertEquals(1, count($data3['pending']));
        $this->assertEquals($alarmDef->getId(), $data3['pending'][0]->getDefinitionId());
        $this->assertEquals('WORD alarm', $data3['pending'][0]->getMessage());
        $this->assertFalse($data3['pending'][0]->isActive());
        $this->assertFalse($data3['pending'][0]->isAck());
        
        $this->assertEquals(0, count($data3['archive']));
        $this->assertTrue($data3['feedbackNotAck']);
        
        // Data 4
        $this->assertFalse($data4['alarmDef']->isActive());
        $this->assertFalse($data4['alarmDef']->isPending());
        
        $this->assertEquals(0, count($data4['pending']));
        
        $this->assertEquals(1, count($data4['archive']));
        $this->assertEquals($alarmDef->getId(), $data4['archive'][0]->getDefinitionId());
        $this->assertEquals('WORD alarm', $data4['archive'][0]->getMessage());
        $this->assertFalse($data4['archive'][0]->isActive());
        $this->assertFalse($data4['archive'][0]->isAck());
        $this->assertFalse($data4['feedbackNotAck']);
    }
    
    /**
     * Test WORD alarm Tag<=value
     */
    public function testWord4()
    {
        // Get alarm definition
        $alarmDef = $this->getAlarmDefinition(TagType::WORD);
        // Change trigger
        $alarmDef->setTrigger(AlarmTrigger::TR_TAG_LTE_VAL);
        $alarmDef->setTriggerNumeric(15100);
        // Save alarm definition
        $this->alarmMapper->editAlarm($alarmDef);
        
        $data1 = $this->getAlarmsData($alarmDef);
        
        // Activate alarm definition
        $this->alarmMapper->enableAlarm($alarmDef->getId());
        
        // Alarm ON
        $this->parser->writeWord($alarmDef->getTag()->getName(), 15100);
        
        // Wait on alarm pending state
        $this->waitOnAlarmPendingState($alarmDef->getId());
                        
        $data2 = $this->getAlarmsData($alarmDef);
        
        // Alarm OFF
        $this->parser->writeWord($alarmDef->getTag()->getName(), 15600);
        
        // Wait on alarm active state to be off
        $this->waitOnAlarmActiveState($alarmDef->getId(), false);
        
        $data3 = $this->getAlarmsData($alarmDef);
        
        // Alarm ACK
        $this->parser->setBit($alarmDef->getHWAck()->getName());
        
        // Wait on alarm pending state to be off
        $this->waitOnAlarmPendingState($alarmDef->getId(), false);
                
        $data4 = $this->getAlarmsData($alarmDef);
        
        // Alarm ACK
        $this->parser->resetBit($alarmDef->getHWAck()->getName());
        $this->waitOnProcessDataSync();
        
        // Deactivate alarm definition
        $this->alarmMapper->enableAlarm($alarmDef->getId(), false);
        
        $this->assertEquals(TagType::WORD, $alarmDef->getTag()->getType());
        $this->assertEquals(AlarmTrigger::TR_TAG_LTE_VAL, $alarmDef->getTrigger());
        $this->assertEquals(15100, $alarmDef->getTriggerNumeric());
        $this->assertEquals('TEST_ALARM_WORD', $alarmDef->getTag()->getName());
        $this->assertEquals('ALARM_WORD_NACK', $alarmDef->getFeedbackNotAck()->getName());
        $this->assertEquals('ALARM_HW_ACK', $alarmDef->getHWAck()->getName());
        $this->assertFalse($alarmDef->isAutoAck());
        $this->assertFalse($alarmDef->isActive());
        $this->assertFalse($alarmDef->isPending());
        
        // Data1
        $this->assertFalse($data1['alarmDef']->isActive());
        $this->assertFalse($data1['alarmDef']->isPending());
        $this->assertEquals(0, count($data1['pending']));
        $this->assertEquals(0, count($data1['archive']));
        $this->assertFalse($data1['feedbackNotAck']);
        
        // Data 2
        $this->assertTrue($data2['alarmDef']->isActive());
        $this->assertTrue($data2['alarmDef']->isPending());
        
        $this->assertEquals(1, count($data2['pending']));
        $this->assertEquals($alarmDef->getId(), $data2['pending'][0]->getDefinitionId());
        $this->assertEquals('WORD alarm', $data2['pending'][0]->getMessage());
        $this->assertTrue($data2['pending'][0]->isActive());
        $this->assertFalse($data2['pending'][0]->isAck());
        
        $this->assertEquals(0, count($data2['archive']));
        $this->assertTrue($data2['feedbackNotAck']);
        
        // Data 3
        $this->assertFalse($data3['alarmDef']->isActive());
        $this->assertTrue($data3['alarmDef']->isPending());
        
        $this->assertEquals(1, count($data3['pending']));
        $this->assertEquals($alarmDef->getId(), $data3['pending'][0]->getDefinitionId());
        $this->assertEquals('WORD alarm', $data3['pending'][0]->getMessage());
        $this->assertFalse($data3['pending'][0]->isActive());
        $this->assertFalse($data3['pending'][0]->isAck());
        
        $this->assertEquals(0, count($data3['archive']));
        $this->assertTrue($data3['feedbackNotAck']);
        
        // Data 4
        $this->assertFalse($data4['alarmDef']->isActive());
        $this->assertFalse($data4['alarmDef']->isPending());
        
        $this->assertEquals(0, count($data4['pending']));
        
        $this->assertEquals(1, count($data4['archive']));
        $this->assertEquals($alarmDef->getId(), $data4['archive'][0]->getDefinitionId());
        $this->assertEquals('WORD alarm', $data4['archive'][0]->getMessage());
        $this->assertFalse($data4['archive'][0]->isActive());
        $this->assertFalse($data4['archive'][0]->isAck());
        $this->assertFalse($data4['feedbackNotAck']);
    }
    
    /**
     * Test WORD alarm Tag=value
     */
    public function testWord5()
    {
        // Get alarm definition
        $alarmDef = $this->getAlarmDefinition(TagType::WORD);
        // Change trigger
        $alarmDef->setTrigger(AlarmTrigger::TR_TAG_EQ_VAL);
        $alarmDef->setTriggerNumeric(41000);
        // Save alarm definition
        $this->alarmMapper->editAlarm($alarmDef);
        
        $data1 = $this->getAlarmsData($alarmDef);
        
        // Activate alarm definition
        $this->alarmMapper->enableAlarm($alarmDef->getId());
        
        // Alarm ON
        $this->parser->writeWord($alarmDef->getTag()->getName(), 41000);
        
        // Wait on alarm pending state
        $this->waitOnAlarmPendingState($alarmDef->getId());
                        
        $data2 = $this->getAlarmsData($alarmDef);
        
        // Alarm OFF
        $this->parser->writeWord($alarmDef->getTag()->getName(), 20000);
        
        // Wait on alarm active state to be off
        $this->waitOnAlarmActiveState($alarmDef->getId(), false);
        
        $data3 = $this->getAlarmsData($alarmDef);
        
        // Alarm ACK
        $this->parser->setBit($alarmDef->getHWAck()->getName());
        
        // Wait on alarm pending state to be off
        $this->waitOnAlarmPendingState($alarmDef->getId(), false);
                
        $data4 = $this->getAlarmsData($alarmDef);
        
        // Alarm ACK
        $this->parser->resetBit($alarmDef->getHWAck()->getName());
        $this->waitOnProcessDataSync();
        
        // Deactivate alarm definition
        $this->alarmMapper->enableAlarm($alarmDef->getId(), false);
        
        $this->assertEquals(TagType::WORD, $alarmDef->getTag()->getType());
        $this->assertEquals(AlarmTrigger::TR_TAG_EQ_VAL, $alarmDef->getTrigger());
        $this->assertEquals(41000, $alarmDef->getTriggerNumeric());
        $this->assertEquals('TEST_ALARM_WORD', $alarmDef->getTag()->getName());
        $this->assertEquals('ALARM_WORD_NACK', $alarmDef->getFeedbackNotAck()->getName());
        $this->assertEquals('ALARM_HW_ACK', $alarmDef->getHWAck()->getName());
        $this->assertFalse($alarmDef->isAutoAck());
        $this->assertFalse($alarmDef->isActive());
        $this->assertFalse($alarmDef->isPending());
        
        // Data1
        $this->assertFalse($data1['alarmDef']->isActive());
        $this->assertFalse($data1['alarmDef']->isPending());
        $this->assertEquals(0, count($data1['pending']));
        $this->assertEquals(0, count($data1['archive']));
        $this->assertFalse($data1['feedbackNotAck']);
        
        // Data 2
        $this->assertTrue($data2['alarmDef']->isActive());
        $this->assertTrue($data2['alarmDef']->isPending());
        
        $this->assertEquals(1, count($data2['pending']));
        $this->assertEquals($alarmDef->getId(), $data2['pending'][0]->getDefinitionId());
        $this->assertEquals('WORD alarm', $data2['pending'][0]->getMessage());
        $this->assertTrue($data2['pending'][0]->isActive());
        $this->assertFalse($data2['pending'][0]->isAck());
        
        $this->assertEquals(0, count($data2['archive']));
        $this->assertTrue($data2['feedbackNotAck']);
        
        // Data 3
        $this->assertFalse($data3['alarmDef']->isActive());
        $this->assertTrue($data3['alarmDef']->isPending());
        
        $this->assertEquals(1, count($data3['pending']));
        $this->assertEquals($alarmDef->getId(), $data3['pending'][0]->getDefinitionId());
        $this->assertEquals('WORD alarm', $data3['pending'][0]->getMessage());
        $this->assertFalse($data3['pending'][0]->isActive());
        $this->assertFalse($data3['pending'][0]->isAck());
        
        $this->assertEquals(0, count($data3['archive']));
        $this->assertTrue($data3['feedbackNotAck']);
        
        // Data 4
        $this->assertFalse($data4['alarmDef']->isActive());
        $this->assertFalse($data4['alarmDef']->isPending());
        
        $this->assertEquals(0, count($data4['pending']));
        
        $this->assertEquals(1, count($data4['archive']));
        $this->assertEquals($alarmDef->getId(), $data4['archive'][0]->getDefinitionId());
        $this->assertEquals('WORD alarm', $data4['archive'][0]->getMessage());
        $this->assertFalse($data4['archive'][0]->isActive());
        $this->assertFalse($data4['archive'][0]->isAck());
        $this->assertFalse($data4['feedbackNotAck']);
    }
    
    /**
     * Test WORD alarm Tag!=value
     */
    public function testWord6()
    {
        // Get alarm definition
        $alarmDef = $this->getAlarmDefinition(TagType::WORD);
        // Change trigger
        $alarmDef->setTrigger(AlarmTrigger::TR_TAG_NEQ_VAL);
        $alarmDef->setTriggerNumeric(20000);
        // Save alarm definition
        $this->alarmMapper->editAlarm($alarmDef);
        
        $data1 = $this->getAlarmsData($alarmDef);
        
        // Activate alarm definition
        $this->alarmMapper->enableAlarm($alarmDef->getId());
        
        // Alarm ON
        $this->parser->writeWord($alarmDef->getTag()->getName(), 20100);
        
        // Wait on alarm pending state
        $this->waitOnAlarmPendingState($alarmDef->getId());
                        
        $data2 = $this->getAlarmsData($alarmDef);
        
        // Alarm OFF
        $this->parser->writeWord($alarmDef->getTag()->getName(), 20000);
        
        // Wait on alarm active state to be off
        $this->waitOnAlarmActiveState($alarmDef->getId(), false);
        
        $data3 = $this->getAlarmsData($alarmDef);
        
        // Alarm ACK
        $this->parser->setBit($alarmDef->getHWAck()->getName());
        
        // Wait on alarm pending state to be off
        $this->waitOnAlarmPendingState($alarmDef->getId(), false);
                
        $data4 = $this->getAlarmsData($alarmDef);
        
        // Alarm ACK
        $this->parser->resetBit($alarmDef->getHWAck()->getName());
        $this->waitOnProcessDataSync();
        
        // Deactivate alarm definition
        $this->alarmMapper->enableAlarm($alarmDef->getId(), false);
        
        $this->assertEquals(TagType::WORD, $alarmDef->getTag()->getType());
        $this->assertEquals(AlarmTrigger::TR_TAG_NEQ_VAL, $alarmDef->getTrigger());
        $this->assertEquals(20000, $alarmDef->getTriggerNumeric());
        $this->assertEquals('TEST_ALARM_WORD', $alarmDef->getTag()->getName());
        $this->assertEquals('ALARM_WORD_NACK', $alarmDef->getFeedbackNotAck()->getName());
        $this->assertEquals('ALARM_HW_ACK', $alarmDef->getHWAck()->getName());
        $this->assertFalse($alarmDef->isAutoAck());
        $this->assertFalse($alarmDef->isActive());
        $this->assertFalse($alarmDef->isPending());
        
        // Data1
        $this->assertFalse($data1['alarmDef']->isActive());
        $this->assertFalse($data1['alarmDef']->isPending());
        $this->assertEquals(0, count($data1['pending']));
        $this->assertEquals(0, count($data1['archive']));
        $this->assertFalse($data1['feedbackNotAck']);
        
        // Data 2
        $this->assertTrue($data2['alarmDef']->isActive());
        $this->assertTrue($data2['alarmDef']->isPending());
        
        $this->assertEquals(1, count($data2['pending']));
        $this->assertEquals($alarmDef->getId(), $data2['pending'][0]->getDefinitionId());
        $this->assertEquals('WORD alarm', $data2['pending'][0]->getMessage());
        $this->assertTrue($data2['pending'][0]->isActive());
        $this->assertFalse($data2['pending'][0]->isAck());
        
        $this->assertEquals(0, count($data2['archive']));
        $this->assertTrue($data2['feedbackNotAck']);
        
        // Data 3
        $this->assertFalse($data3['alarmDef']->isActive());
        $this->assertTrue($data3['alarmDef']->isPending());
        
        $this->assertEquals(1, count($data3['pending']));
        $this->assertEquals($alarmDef->getId(), $data3['pending'][0]->getDefinitionId());
        $this->assertEquals('WORD alarm', $data3['pending'][0]->getMessage());
        $this->assertFalse($data3['pending'][0]->isActive());
        $this->assertFalse($data3['pending'][0]->isAck());
        
        $this->assertEquals(0, count($data3['archive']));
        $this->assertTrue($data3['feedbackNotAck']);
        
        // Data 4
        $this->assertFalse($data4['alarmDef']->isActive());
        $this->assertFalse($data4['alarmDef']->isPending());
        
        $this->assertEquals(0, count($data4['pending']));
        
        $this->assertEquals(1, count($data4['archive']));
        $this->assertEquals($alarmDef->getId(), $data4['archive'][0]->getDefinitionId());
        $this->assertEquals('WORD alarm', $data4['archive'][0]->getMessage());
        $this->assertFalse($data4['archive'][0]->isActive());
        $this->assertFalse($data4['archive'][0]->isAck());
        $this->assertFalse($data4['feedbackNotAck']);
    }
    
    /**
     * Test WORD alarm - AutoAck
     */
    public function testWord7()
    {
        // Get alarm definition
        $alarmDef = $this->getAlarmDefinition(TagType::WORD);
        // Set AutoAck flag
        $alarmDef->setAutoAck(true);
        // Change trigger
        $alarmDef->setTrigger(AlarmTrigger::TR_TAG_EQ_VAL);
        $alarmDef->setTriggerNumeric(30100);
        // Save alarm definition
        $this->alarmMapper->editAlarm($alarmDef);
        
        $data1 = $this->getAlarmsData($alarmDef);
        
        // Activate alarm definition
        $this->alarmMapper->enableAlarm($alarmDef->getId());
        
        // Alarm ON
        $this->parser->writeWord($alarmDef->getTag()->getName(), 30100);
        
        // Wait on alarm pending state
        $this->waitOnAlarmPendingState($alarmDef->getId());
                        
        $data2 = $this->getAlarmsData($alarmDef);
        
        // Alarm OFF
        $this->parser->writeWord($alarmDef->getTag()->getName(), 30101);
        
        // Wait on alarm pending state to be off
        $this->waitOnAlarmPendingState($alarmDef->getId(), false);
                
        $data3 = $this->getAlarmsData($alarmDef);
        
        // Deactivate alarm definition
        $this->alarmMapper->enableAlarm($alarmDef->getId(), false);
        
        $this->assertEquals(TagType::WORD, $alarmDef->getTag()->getType());
        $this->assertEquals(AlarmTrigger::TR_TAG_EQ_VAL, $alarmDef->getTrigger());
        $this->assertEquals(30100, $alarmDef->getTriggerNumeric());
        $this->assertEquals('TEST_ALARM_WORD', $alarmDef->getTag()->getName());
        $this->assertEquals('ALARM_WORD_NACK', $alarmDef->getFeedbackNotAck()->getName());
        $this->assertEquals('ALARM_HW_ACK', $alarmDef->getHWAck()->getName());
        $this->assertTrue($alarmDef->isAutoAck());
        $this->assertFalse($alarmDef->isActive());
        $this->assertFalse($alarmDef->isPending());
        
        // Data1
        $this->assertFalse($data1['alarmDef']->isActive());
        $this->assertFalse($data1['alarmDef']->isPending());
        $this->assertEquals(0, count($data1['pending']));
        $this->assertEquals(0, count($data1['archive']));
        $this->assertFalse($data1['feedbackNotAck']);
        
        // Data 2
        $this->assertTrue($data2['alarmDef']->isActive());
        $this->assertTrue($data2['alarmDef']->isPending());
        
        $this->assertEquals(1, count($data2['pending']));
        $this->assertEquals($alarmDef->getId(), $data2['pending'][0]->getDefinitionId());
        $this->assertEquals('WORD alarm', $data2['pending'][0]->getMessage());
        $this->assertTrue($data2['pending'][0]->isActive());
        $this->assertFalse($data2['pending'][0]->isAck());
        
        $this->assertEquals(0, count($data2['archive']));
        $this->assertTrue($data2['feedbackNotAck']);
        
        // Data 3
        $this->assertFalse($data3['alarmDef']->isActive());
        $this->assertFalse($data3['alarmDef']->isPending());
        
        $this->assertEquals(0, count($data3['pending']));
        
        $this->assertEquals(1, count($data3['archive']));
        $this->assertEquals($alarmDef->getId(), $data3['archive'][0]->getDefinitionId());
        $this->assertEquals('WORD alarm', $data3['archive'][0]->getMessage());
        $this->assertFalse($data3['archive'][0]->isActive());
        $this->assertFalse($data3['archive'][0]->isAck());
        $this->assertFalse($data3['feedbackNotAck']);
    }
    
    /**
     * Test WORD alarm - no feedback tag
     */
    public function testWord8()
    {
        // Get alarm definition
        $alarmDef = $this->getAlarmDefinition(TagType::WORD);
        // Set AutoAck flag
        $alarmDef->setAutoAck(false);
        // Delete feedback tag
        $alarmDef->setFeedbackNotAck();
        // Save alarm definition
        $this->alarmMapper->editAlarm($alarmDef);
        
        $data1 = $this->getAlarmsData($alarmDef);
        
        // Activate alarm definition
        $this->alarmMapper->enableAlarm($alarmDef->getId());
        
        // Alarm ON
        $this->parser->writeWord($alarmDef->getTag()->getName(), 30100);
        
        // Wait on alarm pending state
        $this->waitOnAlarmPendingState($alarmDef->getId());
                        
        $data2 = $this->getAlarmsData($alarmDef);
        
        // Alarm OFF
        $this->parser->writeWord($alarmDef->getTag()->getName(), 30101);
        
        // Wait on alarm active state to be off
        $this->waitOnAlarmActiveState($alarmDef->getId(), false);
        
        $data3 = $this->getAlarmsData($alarmDef);
        
        // Alarm ACK
        $this->parser->setBit($alarmDef->getHWAck()->getName());
        
        // Wait on alarm pending state to be off
        $this->waitOnAlarmPendingState($alarmDef->getId(), false);
                
        $data4 = $this->getAlarmsData($alarmDef);
        
        // Alarm ACK
        $this->parser->resetBit($alarmDef->getHWAck()->getName());
        $this->waitOnProcessDataSync();
        
        // Deactivate alarm definition
        $this->alarmMapper->enableAlarm($alarmDef->getId(), false);
        
        $this->assertEquals(TagType::WORD, $alarmDef->getTag()->getType());
        $this->assertEquals(AlarmTrigger::TR_TAG_EQ_VAL, $alarmDef->getTrigger());
        $this->assertEquals(30100, $alarmDef->getTriggerNumeric());
        $this->assertEquals('TEST_ALARM_WORD', $alarmDef->getTag()->getName());
        $this->assertNull($alarmDef->getFeedbackNotAck());
        $this->assertEquals('ALARM_HW_ACK', $alarmDef->getHWAck()->getName());
        $this->assertFalse($alarmDef->isAutoAck());
        $this->assertFalse($alarmDef->isActive());
        $this->assertFalse($alarmDef->isPending());
        
        // Data1
        $this->assertFalse($data1['alarmDef']->isActive());
        $this->assertFalse($data1['alarmDef']->isPending());
        $this->assertEquals(0, count($data1['pending']));
        $this->assertEquals(0, count($data1['archive']));
        $this->assertNull($data1['feedbackNotAck']);
        
        // Data 2
        $this->assertTrue($data2['alarmDef']->isActive());
        $this->assertTrue($data2['alarmDef']->isPending());
        
        $this->assertEquals(1, count($data2['pending']));
        $this->assertEquals($alarmDef->getId(), $data2['pending'][0]->getDefinitionId());
        $this->assertEquals('WORD alarm', $data2['pending'][0]->getMessage());
        $this->assertTrue($data2['pending'][0]->isActive());
        $this->assertFalse($data2['pending'][0]->isAck());
        
        $this->assertEquals(0, count($data2['archive']));
        $this->assertNull($data2['feedbackNotAck']);
        
        // Data 3
        $this->assertFalse($data3['alarmDef']->isActive());
        $this->assertTrue($data3['alarmDef']->isPending());
        
        $this->assertEquals(1, count($data3['pending']));
        $this->assertEquals($alarmDef->getId(), $data3['pending'][0]->getDefinitionId());
        $this->assertEquals('WORD alarm', $data3['pending'][0]->getMessage());
        $this->assertFalse($data3['pending'][0]->isActive());
        $this->assertFalse($data3['pending'][0]->isAck());
        
        $this->assertEquals(0, count($data3['archive']));
        $this->assertNull($data3['feedbackNotAck']);
        
        // Data 4
        $this->assertFalse($data4['alarmDef']->isActive());
        $this->assertFalse($data4['alarmDef']->isPending());
        
        $this->assertEquals(0, count($data4['pending']));
        
        $this->assertEquals(1, count($data4['archive']));
        $this->assertEquals($alarmDef->getId(), $data4['archive'][0]->getDefinitionId());
        $this->assertEquals('WORD alarm', $data4['archive'][0]->getMessage());
        $this->assertFalse($data4['archive'][0]->isActive());
        $this->assertFalse($data4['archive'][0]->isAck());
        $this->assertNull($data4['feedbackNotAck']);
    }
    
    /**
     * Test WORD alarm - no HW ack tag
     */
    public function testWord9()
    {
        // Get alarm definition
        $alarmDef = $this->getAlarmDefinition(TagType::WORD);
        // Delete HW ack tag
        $alarmDef->setHWAck();
        // Save alarm definition
        $this->alarmMapper->editAlarm($alarmDef);
        
        $data1 = $this->getAlarmsData($alarmDef);
        
        // Activate alarm definition
        $this->alarmMapper->enableAlarm($alarmDef->getId());
        
        // Alarm ON
        $this->parser->writeWord($alarmDef->getTag()->getName(), 30100);
        
        // Wait on alarm pending state
        $this->waitOnAlarmPendingState($alarmDef->getId());
                        
        $data2 = $this->getAlarmsData($alarmDef);
        
        // Alarm OFF
        $this->parser->writeWord($alarmDef->getTag()->getName(), 30101);
        
        // Wait on alarm active state to be off
        $this->waitOnAlarmActiveState($alarmDef->getId(), false);
        
        $data3 = $this->getAlarmsData($alarmDef);
        
        // Alarm ACK
        $this->parser->ackAlarm($alarmDef->getId());
        
        // Wait on alarm pending state to be off
        $this->waitOnAlarmPendingState($alarmDef->getId(), false);
                
        $data4 = $this->getAlarmsData($alarmDef);
        
        // Deactivate alarm definition
        $this->alarmMapper->enableAlarm($alarmDef->getId(), false);
        
        $this->assertEquals(TagType::WORD, $alarmDef->getTag()->getType());
        $this->assertEquals(AlarmTrigger::TR_TAG_EQ_VAL, $alarmDef->getTrigger());
        $this->assertEquals(30100, $alarmDef->getTriggerNumeric());
        $this->assertEquals('TEST_ALARM_WORD', $alarmDef->getTag()->getName());
        $this->assertNull($alarmDef->getFeedbackNotAck());
        $this->assertNull($alarmDef->getHWAck());
        $this->assertFalse($alarmDef->isAutoAck());
        $this->assertFalse($alarmDef->isActive());
        $this->assertFalse($alarmDef->isPending());
        
        // Data1
        $this->assertFalse($data1['alarmDef']->isActive());
        $this->assertFalse($data1['alarmDef']->isPending());
        $this->assertEquals(0, count($data1['pending']));
        $this->assertEquals(0, count($data1['archive']));
        $this->assertNull($data1['feedbackNotAck']);
        
        // Data 2
        $this->assertTrue($data2['alarmDef']->isActive());
        $this->assertTrue($data2['alarmDef']->isPending());
        
        $this->assertEquals(1, count($data2['pending']));
        $this->assertEquals($alarmDef->getId(), $data2['pending'][0]->getDefinitionId());
        $this->assertEquals('WORD alarm', $data2['pending'][0]->getMessage());
        $this->assertTrue($data2['pending'][0]->isActive());
        $this->assertFalse($data2['pending'][0]->isAck());
        
        $this->assertEquals(0, count($data2['archive']));
        $this->assertNull($data2['feedbackNotAck']);
        
        // Data 3
        $this->assertFalse($data3['alarmDef']->isActive());
        $this->assertTrue($data3['alarmDef']->isPending());
        
        $this->assertEquals(1, count($data3['pending']));
        $this->assertEquals($alarmDef->getId(), $data3['pending'][0]->getDefinitionId());
        $this->assertEquals('WORD alarm', $data3['pending'][0]->getMessage());
        $this->assertFalse($data3['pending'][0]->isActive());
        $this->assertFalse($data3['pending'][0]->isAck());
        
        $this->assertEquals(0, count($data3['archive']));
        $this->assertNull($data3['feedbackNotAck']);
        
        // Data 4
        $this->assertFalse($data4['alarmDef']->isActive());
        $this->assertFalse($data4['alarmDef']->isPending());
        
        $this->assertEquals(0, count($data4['pending']));
        
        $this->assertEquals(1, count($data4['archive']));
        $this->assertEquals($alarmDef->getId(), $data4['archive'][0]->getDefinitionId());
        $this->assertEquals('WORD alarm', $data4['archive'][0]->getMessage());
        $this->assertFalse($data4['archive'][0]->isActive());
        $this->assertFalse($data4['archive'][0]->isAck());
        $this->assertNull($data4['feedbackNotAck']);
    }
}
