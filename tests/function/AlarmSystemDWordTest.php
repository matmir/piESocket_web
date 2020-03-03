<?php

namespace App\Tests\Func;

use App\Tests\AlarmSystemFunctionTestCase;
use App\Entity\Admin\AlarmTrigger;
use App\Entity\Admin\TagType;

/**
 * Function tests for Alarm system - DWORD alarm
 *
 * @author Mateusz Mirosławski
 */
class AlarmSystemDWordTest extends AlarmSystemFunctionTestCase {
    
    /**
     * Test DWORD alarm Tag>value
     */
    public function testDWord1() {
        
        // Get alarm definition
        $alarmDef = $this->getAlarmDefinition(TagType::DWord);
        
        $data1 = $this->getAlarmsData($alarmDef);
        
        // Activate alarm definition
        $this->alarmMapper->enableAlarm($alarmDef->getId());
        
        // Alarm ON
        $this->parser->writeDWord($alarmDef->getTag()->getName(), 100801);
        
        // Wait on alarm pending state
        $this->waitOnAlarmPendingState($alarmDef->getId());
                        
        $data2 = $this->getAlarmsData($alarmDef);
        
        // Alarm OFF
        $this->parser->writeDWord($alarmDef->getTag()->getName(), 100700);
        
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
        
        $this->assertEquals(TagType::DWord, $alarmDef->getTag()->getType());
        $this->assertEquals(AlarmTrigger::TR_TAG_GT_VAL, $alarmDef->getTrigger());
        $this->assertEquals(100800, $alarmDef->getTriggerNumeric());
        $this->assertEquals('TEST_ALARM_DWORD', $alarmDef->getTag()->getName());
        $this->assertEquals('ALARM_DWORD_NACK', $alarmDef->getFeedbackNotAck()->getName());
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
        $this->assertEquals('DWORD alarm', $data2['pending'][0]->getMessage());
        $this->assertTrue($data2['pending'][0]->isActive());
        $this->assertFalse($data2['pending'][0]->isAck());
        
        $this->assertEquals(0, count($data2['archive']));
        $this->assertTrue($data2['feedbackNotAck']);
        
        // Data 3
        $this->assertFalse($data3['alarmDef']->isActive());
        $this->assertTrue($data3['alarmDef']->isPending());
        
        $this->assertEquals(1, count($data3['pending']));
        $this->assertEquals($alarmDef->getId(), $data3['pending'][0]->getDefinitionId());
        $this->assertEquals('DWORD alarm', $data3['pending'][0]->getMessage());
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
        $this->assertEquals('DWORD alarm', $data4['archive'][0]->getMessage());
        $this->assertFalse($data4['archive'][0]->isActive());
        $this->assertFalse($data4['archive'][0]->isAck());
        $this->assertFalse($data4['feedbackNotAck']);
    }
    
    /**
     * Test DWORD alarm Tag<value
     */
    public function testDWord2() {
        
        // Get alarm definition
        $alarmDef = $this->getAlarmDefinition(TagType::DWord);
        // Change trigger
        $alarmDef->setTrigger(AlarmTrigger::TR_TAG_LT_VAL);
        $alarmDef->setTriggerNumeric(100200);
        // Save alarm definition
        $this->alarmMapper->editAlarm($alarmDef);
        
        $data1 = $this->getAlarmsData($alarmDef);
        
        // Activate alarm definition
        $this->alarmMapper->enableAlarm($alarmDef->getId());
        
        // Alarm ON
        $this->parser->writeDWord($alarmDef->getTag()->getName(), 100150);
        
        // Wait on alarm pending state
        $this->waitOnAlarmPendingState($alarmDef->getId());
                        
        $data2 = $this->getAlarmsData($alarmDef);
        
        // Alarm OFF
        $this->parser->writeDWord($alarmDef->getTag()->getName(), 100200);
        
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
        
        $this->assertEquals(TagType::DWord, $alarmDef->getTag()->getType());
        $this->assertEquals(AlarmTrigger::TR_TAG_LT_VAL, $alarmDef->getTrigger());
        $this->assertEquals(100200, $alarmDef->getTriggerNumeric());
        $this->assertEquals('TEST_ALARM_DWORD', $alarmDef->getTag()->getName());
        $this->assertEquals('ALARM_DWORD_NACK', $alarmDef->getFeedbackNotAck()->getName());
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
        $this->assertEquals('DWORD alarm', $data2['pending'][0]->getMessage());
        $this->assertTrue($data2['pending'][0]->isActive());
        $this->assertFalse($data2['pending'][0]->isAck());
        
        $this->assertEquals(0, count($data2['archive']));
        $this->assertTrue($data2['feedbackNotAck']);
        
        // Data 3
        $this->assertFalse($data3['alarmDef']->isActive());
        $this->assertTrue($data3['alarmDef']->isPending());
        
        $this->assertEquals(1, count($data3['pending']));
        $this->assertEquals($alarmDef->getId(), $data3['pending'][0]->getDefinitionId());
        $this->assertEquals('DWORD alarm', $data3['pending'][0]->getMessage());
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
        $this->assertEquals('DWORD alarm', $data4['archive'][0]->getMessage());
        $this->assertFalse($data4['archive'][0]->isActive());
        $this->assertFalse($data4['archive'][0]->isAck());
        $this->assertFalse($data4['feedbackNotAck']);
    }
    
    /**
     * Test DWORD alarm Tag>=value
     */
    public function testDWord3() {
        
        // Get alarm definition
        $alarmDef = $this->getAlarmDefinition(TagType::DWord);
        // Change trigger
        $alarmDef->setTrigger(AlarmTrigger::TR_TAG_GTE_VAL);
        $alarmDef->setTriggerNumeric(250750);
        // Save alarm definition
        $this->alarmMapper->editAlarm($alarmDef);
        
        $data1 = $this->getAlarmsData($alarmDef);
        
        // Activate alarm definition
        $this->alarmMapper->enableAlarm($alarmDef->getId());
        
        // Alarm ON
        $this->parser->writeDWord($alarmDef->getTag()->getName(), 250750);
        
        // Wait on alarm pending state
        $this->waitOnAlarmPendingState($alarmDef->getId());
                        
        $data2 = $this->getAlarmsData($alarmDef);
        
        // Alarm OFF
        $this->parser->writeDWord($alarmDef->getTag()->getName(), 250700);
        
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
        
        $this->assertEquals(TagType::DWord, $alarmDef->getTag()->getType());
        $this->assertEquals(AlarmTrigger::TR_TAG_GTE_VAL, $alarmDef->getTrigger());
        $this->assertEquals(250750, $alarmDef->getTriggerNumeric());
        $this->assertEquals('TEST_ALARM_DWORD', $alarmDef->getTag()->getName());
        $this->assertEquals('ALARM_DWORD_NACK', $alarmDef->getFeedbackNotAck()->getName());
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
        $this->assertEquals('DWORD alarm', $data2['pending'][0]->getMessage());
        $this->assertTrue($data2['pending'][0]->isActive());
        $this->assertFalse($data2['pending'][0]->isAck());
        
        $this->assertEquals(0, count($data2['archive']));
        $this->assertTrue($data2['feedbackNotAck']);
        
        // Data 3
        $this->assertFalse($data3['alarmDef']->isActive());
        $this->assertTrue($data3['alarmDef']->isPending());
        
        $this->assertEquals(1, count($data3['pending']));
        $this->assertEquals($alarmDef->getId(), $data3['pending'][0]->getDefinitionId());
        $this->assertEquals('DWORD alarm', $data3['pending'][0]->getMessage());
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
        $this->assertEquals('DWORD alarm', $data4['archive'][0]->getMessage());
        $this->assertFalse($data4['archive'][0]->isActive());
        $this->assertFalse($data4['archive'][0]->isAck());
        $this->assertFalse($data4['feedbackNotAck']);
    }
    
    /**
     * Test DWORD alarm Tag<=value
     */
    public function testDWord4() {
        
        // Get alarm definition
        $alarmDef = $this->getAlarmDefinition(TagType::DWord);
        // Change trigger
        $alarmDef->setTrigger(AlarmTrigger::TR_TAG_LTE_VAL);
        $alarmDef->setTriggerNumeric(180600);
        // Save alarm definition
        $this->alarmMapper->editAlarm($alarmDef);
        
        $data1 = $this->getAlarmsData($alarmDef);
        
        // Activate alarm definition
        $this->alarmMapper->enableAlarm($alarmDef->getId());
        
        // Alarm ON
        $this->parser->writeDWord($alarmDef->getTag()->getName(), 180600);
        
        // Wait on alarm pending state
        $this->waitOnAlarmPendingState($alarmDef->getId());
                        
        $data2 = $this->getAlarmsData($alarmDef);
        
        // Alarm OFF
        $this->parser->writeDWord($alarmDef->getTag()->getName(), 180900);
        
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
        
        $this->assertEquals(TagType::DWord, $alarmDef->getTag()->getType());
        $this->assertEquals(AlarmTrigger::TR_TAG_LTE_VAL, $alarmDef->getTrigger());
        $this->assertEquals(180600, $alarmDef->getTriggerNumeric());
        $this->assertEquals('TEST_ALARM_DWORD', $alarmDef->getTag()->getName());
        $this->assertEquals('ALARM_DWORD_NACK', $alarmDef->getFeedbackNotAck()->getName());
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
        $this->assertEquals('DWORD alarm', $data2['pending'][0]->getMessage());
        $this->assertTrue($data2['pending'][0]->isActive());
        $this->assertFalse($data2['pending'][0]->isAck());
        
        $this->assertEquals(0, count($data2['archive']));
        $this->assertTrue($data2['feedbackNotAck']);
        
        // Data 3
        $this->assertFalse($data3['alarmDef']->isActive());
        $this->assertTrue($data3['alarmDef']->isPending());
        
        $this->assertEquals(1, count($data3['pending']));
        $this->assertEquals($alarmDef->getId(), $data3['pending'][0]->getDefinitionId());
        $this->assertEquals('DWORD alarm', $data3['pending'][0]->getMessage());
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
        $this->assertEquals('DWORD alarm', $data4['archive'][0]->getMessage());
        $this->assertFalse($data4['archive'][0]->isActive());
        $this->assertFalse($data4['archive'][0]->isAck());
        $this->assertFalse($data4['feedbackNotAck']);
    }
    
    /**
     * Test DWORD alarm Tag=value
     */
    public function testDWord5() {
        
        // Get alarm definition
        $alarmDef = $this->getAlarmDefinition(TagType::DWord);
        // Change trigger
        $alarmDef->setTrigger(AlarmTrigger::TR_TAG_EQ_VAL);
        $alarmDef->setTriggerNumeric(2850650);
        // Save alarm definition
        $this->alarmMapper->editAlarm($alarmDef);
        
        $data1 = $this->getAlarmsData($alarmDef);
        
        // Activate alarm definition
        $this->alarmMapper->enableAlarm($alarmDef->getId());
        
        // Alarm ON
        $this->parser->writeDWord($alarmDef->getTag()->getName(), 2850650);
        
        // Wait on alarm pending state
        $this->waitOnAlarmPendingState($alarmDef->getId());
                        
        $data2 = $this->getAlarmsData($alarmDef);
        
        // Alarm OFF
        $this->parser->writeDWord($alarmDef->getTag()->getName(), 2850610);
        
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
        
        $this->assertEquals(TagType::DWord, $alarmDef->getTag()->getType());
        $this->assertEquals(AlarmTrigger::TR_TAG_EQ_VAL, $alarmDef->getTrigger());
        $this->assertEquals(2850650, $alarmDef->getTriggerNumeric());
        $this->assertEquals('TEST_ALARM_DWORD', $alarmDef->getTag()->getName());
        $this->assertEquals('ALARM_DWORD_NACK', $alarmDef->getFeedbackNotAck()->getName());
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
        $this->assertEquals('DWORD alarm', $data2['pending'][0]->getMessage());
        $this->assertTrue($data2['pending'][0]->isActive());
        $this->assertFalse($data2['pending'][0]->isAck());
        
        $this->assertEquals(0, count($data2['archive']));
        $this->assertTrue($data2['feedbackNotAck']);
        
        // Data 3
        $this->assertFalse($data3['alarmDef']->isActive());
        $this->assertTrue($data3['alarmDef']->isPending());
        
        $this->assertEquals(1, count($data3['pending']));
        $this->assertEquals($alarmDef->getId(), $data3['pending'][0]->getDefinitionId());
        $this->assertEquals('DWORD alarm', $data3['pending'][0]->getMessage());
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
        $this->assertEquals('DWORD alarm', $data4['archive'][0]->getMessage());
        $this->assertFalse($data4['archive'][0]->isActive());
        $this->assertFalse($data4['archive'][0]->isAck());
        $this->assertFalse($data4['feedbackNotAck']);
    }
    
    /**
     * Test DWORD alarm Tag!=value
     */
    public function testDWord6() {
        
        // Get alarm definition
        $alarmDef = $this->getAlarmDefinition(TagType::DWord);
        // Change trigger
        $alarmDef->setTrigger(AlarmTrigger::TR_TAG_NEQ_VAL);
        $alarmDef->setTriggerNumeric(2850610);
        // Save alarm definition
        $this->alarmMapper->editAlarm($alarmDef);
        
        $data1 = $this->getAlarmsData($alarmDef);
        
        // Activate alarm definition
        $this->alarmMapper->enableAlarm($alarmDef->getId());
        
        // Alarm ON
        $this->parser->writeDWord($alarmDef->getTag()->getName(), 2850611);
        
        // Wait on alarm pending state
        $this->waitOnAlarmPendingState($alarmDef->getId());
                        
        $data2 = $this->getAlarmsData($alarmDef);
        
        // Alarm OFF
        $this->parser->writeDWord($alarmDef->getTag()->getName(), 2850610);
        
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
        
        $this->assertEquals(TagType::DWord, $alarmDef->getTag()->getType());
        $this->assertEquals(AlarmTrigger::TR_TAG_NEQ_VAL, $alarmDef->getTrigger());
        $this->assertEquals(2850610, $alarmDef->getTriggerNumeric());
        $this->assertEquals('TEST_ALARM_DWORD', $alarmDef->getTag()->getName());
        $this->assertEquals('ALARM_DWORD_NACK', $alarmDef->getFeedbackNotAck()->getName());
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
        $this->assertEquals('DWORD alarm', $data2['pending'][0]->getMessage());
        $this->assertTrue($data2['pending'][0]->isActive());
        $this->assertFalse($data2['pending'][0]->isAck());
        
        $this->assertEquals(0, count($data2['archive']));
        $this->assertTrue($data2['feedbackNotAck']);
        
        // Data 3
        $this->assertFalse($data3['alarmDef']->isActive());
        $this->assertTrue($data3['alarmDef']->isPending());
        
        $this->assertEquals(1, count($data3['pending']));
        $this->assertEquals($alarmDef->getId(), $data3['pending'][0]->getDefinitionId());
        $this->assertEquals('DWORD alarm', $data3['pending'][0]->getMessage());
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
        $this->assertEquals('DWORD alarm', $data4['archive'][0]->getMessage());
        $this->assertFalse($data4['archive'][0]->isActive());
        $this->assertFalse($data4['archive'][0]->isAck());
        $this->assertFalse($data4['feedbackNotAck']);
    }
    
    /**
     * Test DWORD alarm - AutoAck
     */
    public function testDWord7() {
        
        // Get alarm definition
        $alarmDef = $this->getAlarmDefinition(TagType::DWord);
        // Set AutoAck flag
        $alarmDef->setAutoAck(true);
        // Change trigger
        $alarmDef->setTrigger(AlarmTrigger::TR_TAG_EQ_VAL);
        $alarmDef->setTriggerNumeric(3450199);
        // Save alarm definition
        $this->alarmMapper->editAlarm($alarmDef);
        
        $data1 = $this->getAlarmsData($alarmDef);
        
        // Activate alarm definition
        $this->alarmMapper->enableAlarm($alarmDef->getId());
        
        // Alarm ON
        $this->parser->writeDWord($alarmDef->getTag()->getName(), 3450199);
        
        // Wait on alarm pending state
        $this->waitOnAlarmPendingState($alarmDef->getId());
                        
        $data2 = $this->getAlarmsData($alarmDef);
        
        // Alarm OFF
        $this->parser->writeDWord($alarmDef->getTag()->getName(), 3450200);
        
        // Wait on alarm pending state to be off
        $this->waitOnAlarmPendingState($alarmDef->getId(), false);
                
        $data3 = $this->getAlarmsData($alarmDef);
        
        // Deactivate alarm definition
        $this->alarmMapper->enableAlarm($alarmDef->getId(), false);
        
        $this->assertEquals(TagType::DWord, $alarmDef->getTag()->getType());
        $this->assertEquals(AlarmTrigger::TR_TAG_EQ_VAL, $alarmDef->getTrigger());
        $this->assertEquals(3450199, $alarmDef->getTriggerNumeric());
        $this->assertEquals('TEST_ALARM_DWORD', $alarmDef->getTag()->getName());
        $this->assertEquals('ALARM_DWORD_NACK', $alarmDef->getFeedbackNotAck()->getName());
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
        $this->assertEquals('DWORD alarm', $data2['pending'][0]->getMessage());
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
        $this->assertEquals('DWORD alarm', $data3['archive'][0]->getMessage());
        $this->assertFalse($data3['archive'][0]->isActive());
        $this->assertFalse($data3['archive'][0]->isAck());
        $this->assertFalse($data3['feedbackNotAck']);
    }
    
    /**
     * Test DWORD alarm - no feedback tag
     */
    public function testDWord8() {
        
        // Get alarm definition
        $alarmDef = $this->getAlarmDefinition(TagType::DWord);
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
        $this->parser->writeDWord($alarmDef->getTag()->getName(), 3450199);
        
        // Wait on alarm pending state
        $this->waitOnAlarmPendingState($alarmDef->getId());
                        
        $data2 = $this->getAlarmsData($alarmDef);
        
        // Alarm OFF
        $this->parser->writeDWord($alarmDef->getTag()->getName(), 3450200);
        
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
        
        $this->assertEquals(TagType::DWord, $alarmDef->getTag()->getType());
        $this->assertEquals(AlarmTrigger::TR_TAG_EQ_VAL, $alarmDef->getTrigger());
        $this->assertEquals(3450199, $alarmDef->getTriggerNumeric());
        $this->assertEquals('TEST_ALARM_DWORD', $alarmDef->getTag()->getName());
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
        $this->assertEquals('DWORD alarm', $data2['pending'][0]->getMessage());
        $this->assertTrue($data2['pending'][0]->isActive());
        $this->assertFalse($data2['pending'][0]->isAck());
        
        $this->assertEquals(0, count($data2['archive']));
        $this->assertNull($data2['feedbackNotAck']);
        
        // Data 3
        $this->assertFalse($data3['alarmDef']->isActive());
        $this->assertTrue($data3['alarmDef']->isPending());
        
        $this->assertEquals(1, count($data3['pending']));
        $this->assertEquals($alarmDef->getId(), $data3['pending'][0]->getDefinitionId());
        $this->assertEquals('DWORD alarm', $data3['pending'][0]->getMessage());
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
        $this->assertEquals('DWORD alarm', $data4['archive'][0]->getMessage());
        $this->assertFalse($data4['archive'][0]->isActive());
        $this->assertFalse($data4['archive'][0]->isAck());
        $this->assertNull($data4['feedbackNotAck']);
    }
    
    /**
     * Test DWORD alarm - no HW ack tag
     */
    public function testDWord9() {
        
        // Get alarm definition
        $alarmDef = $this->getAlarmDefinition(TagType::DWord);
        // Delete HW ack tag
        $alarmDef->setHWAck();
        // Save alarm definition
        $this->alarmMapper->editAlarm($alarmDef);
        
        $data1 = $this->getAlarmsData($alarmDef);
        
        // Activate alarm definition
        $this->alarmMapper->enableAlarm($alarmDef->getId());
        
        // Alarm ON
        $this->parser->writeDWord($alarmDef->getTag()->getName(), 3450199);
        
        // Wait on alarm pending state
        $this->waitOnAlarmPendingState($alarmDef->getId());
                        
        $data2 = $this->getAlarmsData($alarmDef);
        
        // Alarm OFF
        $this->parser->writeDWord($alarmDef->getTag()->getName(), 34501994);
        
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
        
        $this->assertEquals(TagType::DWord, $alarmDef->getTag()->getType());
        $this->assertEquals(AlarmTrigger::TR_TAG_EQ_VAL, $alarmDef->getTrigger());
        $this->assertEquals(3450199, $alarmDef->getTriggerNumeric());
        $this->assertEquals('TEST_ALARM_DWORD', $alarmDef->getTag()->getName());
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
        $this->assertEquals('DWORD alarm', $data2['pending'][0]->getMessage());
        $this->assertTrue($data2['pending'][0]->isActive());
        $this->assertFalse($data2['pending'][0]->isAck());
        
        $this->assertEquals(0, count($data2['archive']));
        $this->assertNull($data2['feedbackNotAck']);
        
        // Data 3
        $this->assertFalse($data3['alarmDef']->isActive());
        $this->assertTrue($data3['alarmDef']->isPending());
        
        $this->assertEquals(1, count($data3['pending']));
        $this->assertEquals($alarmDef->getId(), $data3['pending'][0]->getDefinitionId());
        $this->assertEquals('DWORD alarm', $data3['pending'][0]->getMessage());
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
        $this->assertEquals('DWORD alarm', $data4['archive'][0]->getMessage());
        $this->assertFalse($data4['archive'][0]->isActive());
        $this->assertFalse($data4['archive'][0]->isAck());
        $this->assertNull($data4['feedbackNotAck']);
    }
}
