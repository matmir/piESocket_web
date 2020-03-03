<?php

namespace App\Tests\Func;

use App\Tests\AlarmSystemFunctionTestCase;
use App\Entity\Admin\AlarmTrigger;
use App\Entity\Admin\TagType;

/**
 * Function tests for Alarm system - INT alarm
 *
 * @author Mateusz Mirosławski
 */
class AlarmSystemIntTest extends AlarmSystemFunctionTestCase {
    
    /**
     * Test INT alarm Tag>value
     */
    public function testInt1() {
        
        // Get alarm definition
        $alarmDef = $this->getAlarmDefinition(TagType::INT);
        
        $data1 = $this->getAlarmsData($alarmDef);
        
        // Activate alarm definition
        $this->alarmMapper->enableAlarm($alarmDef->getId());
        
        // Alarm ON
        $this->parser->writeInt($alarmDef->getTag()->getName(), -90);
        
        // Wait on alarm pending state
        $this->waitOnAlarmPendingState($alarmDef->getId());
                        
        $data2 = $this->getAlarmsData($alarmDef);
        
        // Alarm OFF
        $this->parser->writeInt($alarmDef->getTag()->getName(), -200);
        
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
        
        $this->assertEquals(TagType::INT, $alarmDef->getTag()->getType());
        $this->assertEquals(AlarmTrigger::TR_TAG_GT_VAL, $alarmDef->getTrigger());
        $this->assertEquals(-100, $alarmDef->getTriggerNumeric());
        $this->assertEquals('TEST_ALARM_INT', $alarmDef->getTag()->getName());
        $this->assertEquals('ALARM_INT_NACK', $alarmDef->getFeedbackNotAck()->getName());
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
        $this->assertEquals('INT alarm', $data2['pending'][0]->getMessage());
        $this->assertTrue($data2['pending'][0]->isActive());
        $this->assertFalse($data2['pending'][0]->isAck());
        
        $this->assertEquals(0, count($data2['archive']));
        $this->assertTrue($data2['feedbackNotAck']);
        
        // Data 3
        $this->assertFalse($data3['alarmDef']->isActive());
        $this->assertTrue($data3['alarmDef']->isPending());
        
        $this->assertEquals(1, count($data3['pending']));
        $this->assertEquals($alarmDef->getId(), $data3['pending'][0]->getDefinitionId());
        $this->assertEquals('INT alarm', $data3['pending'][0]->getMessage());
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
        $this->assertEquals('INT alarm', $data4['archive'][0]->getMessage());
        $this->assertFalse($data4['archive'][0]->isActive());
        $this->assertFalse($data4['archive'][0]->isAck());
        $this->assertFalse($data4['feedbackNotAck']);
    }
    
    /**
     * Test INT alarm Tag<value
     */
    public function testInt2() {
        
        // Get alarm definition
        $alarmDef = $this->getAlarmDefinition(TagType::INT);
        // Change trigger
        $alarmDef->setTrigger(AlarmTrigger::TR_TAG_LT_VAL);
        $alarmDef->setTriggerNumeric(-600);
        // Save alarm definition
        $this->alarmMapper->editAlarm($alarmDef);
        
        $data1 = $this->getAlarmsData($alarmDef);
        
        // Activate alarm definition
        $this->alarmMapper->enableAlarm($alarmDef->getId());
        
        // Alarm ON
        $this->parser->writeInt($alarmDef->getTag()->getName(), -601);
        
        // Wait on alarm pending state
        $this->waitOnAlarmPendingState($alarmDef->getId());
                        
        $data2 = $this->getAlarmsData($alarmDef);
        
        // Alarm OFF
        $this->parser->writeInt($alarmDef->getTag()->getName(), -100);
        
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
        
        $this->assertEquals(TagType::INT, $alarmDef->getTag()->getType());
        $this->assertEquals(AlarmTrigger::TR_TAG_LT_VAL, $alarmDef->getTrigger());
        $this->assertEquals(-600, $alarmDef->getTriggerNumeric());
        $this->assertEquals('TEST_ALARM_INT', $alarmDef->getTag()->getName());
        $this->assertEquals('ALARM_INT_NACK', $alarmDef->getFeedbackNotAck()->getName());
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
        $this->assertEquals('INT alarm', $data2['pending'][0]->getMessage());
        $this->assertTrue($data2['pending'][0]->isActive());
        $this->assertFalse($data2['pending'][0]->isAck());
        
        $this->assertEquals(0, count($data2['archive']));
        $this->assertTrue($data2['feedbackNotAck']);
        
        // Data 3
        $this->assertFalse($data3['alarmDef']->isActive());
        $this->assertTrue($data3['alarmDef']->isPending());
        
        $this->assertEquals(1, count($data3['pending']));
        $this->assertEquals($alarmDef->getId(), $data3['pending'][0]->getDefinitionId());
        $this->assertEquals('INT alarm', $data3['pending'][0]->getMessage());
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
        $this->assertEquals('INT alarm', $data4['archive'][0]->getMessage());
        $this->assertFalse($data4['archive'][0]->isActive());
        $this->assertFalse($data4['archive'][0]->isAck());
        $this->assertFalse($data4['feedbackNotAck']);
    }
    
    /**
     * Test INT alarm Tag>=value
     */
    public function testInt3() {
        
        // Get alarm definition
        $alarmDef = $this->getAlarmDefinition(TagType::INT);
        // Change trigger
        $alarmDef->setTrigger(AlarmTrigger::TR_TAG_GTE_VAL);
        $alarmDef->setTriggerNumeric(-5);
        // Save alarm definition
        $this->alarmMapper->editAlarm($alarmDef);
        
        $data1 = $this->getAlarmsData($alarmDef);
        
        // Activate alarm definition
        $this->alarmMapper->enableAlarm($alarmDef->getId());
        
        // Alarm ON
        $this->parser->writeInt($alarmDef->getTag()->getName(), -5);
        
        // Wait on alarm pending state
        $this->waitOnAlarmPendingState($alarmDef->getId());
                        
        $data2 = $this->getAlarmsData($alarmDef);
        
        // Alarm OFF
        $this->parser->writeInt($alarmDef->getTag()->getName(), -10);
        
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
        
        $this->assertEquals(TagType::INT, $alarmDef->getTag()->getType());
        $this->assertEquals(AlarmTrigger::TR_TAG_GTE_VAL, $alarmDef->getTrigger());
        $this->assertEquals(-5, $alarmDef->getTriggerNumeric());
        $this->assertEquals('TEST_ALARM_INT', $alarmDef->getTag()->getName());
        $this->assertEquals('ALARM_INT_NACK', $alarmDef->getFeedbackNotAck()->getName());
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
        $this->assertEquals('INT alarm', $data2['pending'][0]->getMessage());
        $this->assertTrue($data2['pending'][0]->isActive());
        $this->assertFalse($data2['pending'][0]->isAck());
        
        $this->assertEquals(0, count($data2['archive']));
        $this->assertTrue($data2['feedbackNotAck']);
        
        // Data 3
        $this->assertFalse($data3['alarmDef']->isActive());
        $this->assertTrue($data3['alarmDef']->isPending());
        
        $this->assertEquals(1, count($data3['pending']));
        $this->assertEquals($alarmDef->getId(), $data3['pending'][0]->getDefinitionId());
        $this->assertEquals('INT alarm', $data3['pending'][0]->getMessage());
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
        $this->assertEquals('INT alarm', $data4['archive'][0]->getMessage());
        $this->assertFalse($data4['archive'][0]->isActive());
        $this->assertFalse($data4['archive'][0]->isAck());
        $this->assertFalse($data4['feedbackNotAck']);
    }
    
    /**
     * Test INT alarm Tag<=value
     */
    public function testInt4() {
        
        // Get alarm definition
        $alarmDef = $this->getAlarmDefinition(TagType::INT);
        // Change trigger
        $alarmDef->setTrigger(AlarmTrigger::TR_TAG_LTE_VAL);
        $alarmDef->setTriggerNumeric(-350);
        // Save alarm definition
        $this->alarmMapper->editAlarm($alarmDef);
        
        $data1 = $this->getAlarmsData($alarmDef);
        
        // Activate alarm definition
        $this->alarmMapper->enableAlarm($alarmDef->getId());
        
        // Alarm ON
        $this->parser->writeInt($alarmDef->getTag()->getName(), -350);
        
        // Wait on alarm pending state
        $this->waitOnAlarmPendingState($alarmDef->getId());
                        
        $data2 = $this->getAlarmsData($alarmDef);
        
        // Alarm OFF
        $this->parser->writeInt($alarmDef->getTag()->getName(), -340);
        
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
        
        $this->assertEquals(TagType::INT, $alarmDef->getTag()->getType());
        $this->assertEquals(AlarmTrigger::TR_TAG_LTE_VAL, $alarmDef->getTrigger());
        $this->assertEquals(-350, $alarmDef->getTriggerNumeric());
        $this->assertEquals('TEST_ALARM_INT', $alarmDef->getTag()->getName());
        $this->assertEquals('ALARM_INT_NACK', $alarmDef->getFeedbackNotAck()->getName());
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
        $this->assertEquals('INT alarm', $data2['pending'][0]->getMessage());
        $this->assertTrue($data2['pending'][0]->isActive());
        $this->assertFalse($data2['pending'][0]->isAck());
        
        $this->assertEquals(0, count($data2['archive']));
        $this->assertTrue($data2['feedbackNotAck']);
        
        // Data 3
        $this->assertFalse($data3['alarmDef']->isActive());
        $this->assertTrue($data3['alarmDef']->isPending());
        
        $this->assertEquals(1, count($data3['pending']));
        $this->assertEquals($alarmDef->getId(), $data3['pending'][0]->getDefinitionId());
        $this->assertEquals('INT alarm', $data3['pending'][0]->getMessage());
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
        $this->assertEquals('INT alarm', $data4['archive'][0]->getMessage());
        $this->assertFalse($data4['archive'][0]->isActive());
        $this->assertFalse($data4['archive'][0]->isAck());
        $this->assertFalse($data4['feedbackNotAck']);
    }
    
    /**
     * Test INT alarm Tag=value
     */
    public function testInt5() {
        
        // Get alarm definition
        $alarmDef = $this->getAlarmDefinition(TagType::INT);
        // Change trigger
        $alarmDef->setTrigger(AlarmTrigger::TR_TAG_EQ_VAL);
        $alarmDef->setTriggerNumeric(-1500);
        // Save alarm definition
        $this->alarmMapper->editAlarm($alarmDef);
        
        $data1 = $this->getAlarmsData($alarmDef);
        
        // Activate alarm definition
        $this->alarmMapper->enableAlarm($alarmDef->getId());
        
        // Alarm ON
        $this->parser->writeInt($alarmDef->getTag()->getName(), -1500);
        
        // Wait on alarm pending state
        $this->waitOnAlarmPendingState($alarmDef->getId());
                        
        $data2 = $this->getAlarmsData($alarmDef);
        
        // Alarm OFF
        $this->parser->writeInt($alarmDef->getTag()->getName(), -2000);
        
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
        
        $this->assertEquals(TagType::INT, $alarmDef->getTag()->getType());
        $this->assertEquals(AlarmTrigger::TR_TAG_EQ_VAL, $alarmDef->getTrigger());
        $this->assertEquals(-1500, $alarmDef->getTriggerNumeric());
        $this->assertEquals('TEST_ALARM_INT', $alarmDef->getTag()->getName());
        $this->assertEquals('ALARM_INT_NACK', $alarmDef->getFeedbackNotAck()->getName());
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
        $this->assertEquals('INT alarm', $data2['pending'][0]->getMessage());
        $this->assertTrue($data2['pending'][0]->isActive());
        $this->assertFalse($data2['pending'][0]->isAck());
        
        $this->assertEquals(0, count($data2['archive']));
        $this->assertTrue($data2['feedbackNotAck']);
        
        // Data 3
        $this->assertFalse($data3['alarmDef']->isActive());
        $this->assertTrue($data3['alarmDef']->isPending());
        
        $this->assertEquals(1, count($data3['pending']));
        $this->assertEquals($alarmDef->getId(), $data3['pending'][0]->getDefinitionId());
        $this->assertEquals('INT alarm', $data3['pending'][0]->getMessage());
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
        $this->assertEquals('INT alarm', $data4['archive'][0]->getMessage());
        $this->assertFalse($data4['archive'][0]->isActive());
        $this->assertFalse($data4['archive'][0]->isAck());
        $this->assertFalse($data4['feedbackNotAck']);
    }
    
    /**
     * Test INT alarm Tag!=value
     */
    public function testInt6() {
        
        // Get alarm definition
        $alarmDef = $this->getAlarmDefinition(TagType::INT);
        // Change trigger
        $alarmDef->setTrigger(AlarmTrigger::TR_TAG_NEQ_VAL);
        $alarmDef->setTriggerNumeric(-2000);
        // Save alarm definition
        $this->alarmMapper->editAlarm($alarmDef);
        
        $data1 = $this->getAlarmsData($alarmDef);
        
        // Activate alarm definition
        $this->alarmMapper->enableAlarm($alarmDef->getId());
        
        // Alarm ON
        $this->parser->writeInt($alarmDef->getTag()->getName(), -2001);
        
        // Wait on alarm pending state
        $this->waitOnAlarmPendingState($alarmDef->getId());
                        
        $data2 = $this->getAlarmsData($alarmDef);
        
        // Alarm OFF
        $this->parser->writeInt($alarmDef->getTag()->getName(), -2000);
        
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
        
        $this->assertEquals(TagType::INT, $alarmDef->getTag()->getType());
        $this->assertEquals(AlarmTrigger::TR_TAG_NEQ_VAL, $alarmDef->getTrigger());
        $this->assertEquals(-2000, $alarmDef->getTriggerNumeric());
        $this->assertEquals('TEST_ALARM_INT', $alarmDef->getTag()->getName());
        $this->assertEquals('ALARM_INT_NACK', $alarmDef->getFeedbackNotAck()->getName());
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
        $this->assertEquals('INT alarm', $data2['pending'][0]->getMessage());
        $this->assertTrue($data2['pending'][0]->isActive());
        $this->assertFalse($data2['pending'][0]->isAck());
        
        $this->assertEquals(0, count($data2['archive']));
        $this->assertTrue($data2['feedbackNotAck']);
        
        // Data 3
        $this->assertFalse($data3['alarmDef']->isActive());
        $this->assertTrue($data3['alarmDef']->isPending());
        
        $this->assertEquals(1, count($data3['pending']));
        $this->assertEquals($alarmDef->getId(), $data3['pending'][0]->getDefinitionId());
        $this->assertEquals('INT alarm', $data3['pending'][0]->getMessage());
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
        $this->assertEquals('INT alarm', $data4['archive'][0]->getMessage());
        $this->assertFalse($data4['archive'][0]->isActive());
        $this->assertFalse($data4['archive'][0]->isAck());
        $this->assertFalse($data4['feedbackNotAck']);
    }
    
    /**
     * Test INT alarm - AutoAck
     */
    public function testInt7() {
        
        // Get alarm definition
        $alarmDef = $this->getAlarmDefinition(TagType::INT);
        // Set AutoAck flag
        $alarmDef->setAutoAck(true);
        // Change trigger
        $alarmDef->setTrigger(AlarmTrigger::TR_TAG_EQ_VAL);
        $alarmDef->setTriggerNumeric(-780);
        // Save alarm definition
        $this->alarmMapper->editAlarm($alarmDef);
        
        $data1 = $this->getAlarmsData($alarmDef);
        
        // Activate alarm definition
        $this->alarmMapper->enableAlarm($alarmDef->getId());
        
        // Alarm ON
        $this->parser->writeInt($alarmDef->getTag()->getName(), -780);
        
        // Wait on alarm pending state
        $this->waitOnAlarmPendingState($alarmDef->getId());
                        
        $data2 = $this->getAlarmsData($alarmDef);
        
        // Alarm OFF
        $this->parser->writeInt($alarmDef->getTag()->getName(), -781);
        
        // Wait on alarm pending state to be off
        $this->waitOnAlarmPendingState($alarmDef->getId(), false);
                
        $data3 = $this->getAlarmsData($alarmDef);
        
        // Deactivate alarm definition
        $this->alarmMapper->enableAlarm($alarmDef->getId(), false);
        
        $this->assertEquals(TagType::INT, $alarmDef->getTag()->getType());
        $this->assertEquals(AlarmTrigger::TR_TAG_EQ_VAL, $alarmDef->getTrigger());
        $this->assertEquals(-780, $alarmDef->getTriggerNumeric());
        $this->assertEquals('TEST_ALARM_INT', $alarmDef->getTag()->getName());
        $this->assertEquals('ALARM_INT_NACK', $alarmDef->getFeedbackNotAck()->getName());
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
        $this->assertEquals('INT alarm', $data2['pending'][0]->getMessage());
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
        $this->assertEquals('INT alarm', $data3['archive'][0]->getMessage());
        $this->assertFalse($data3['archive'][0]->isActive());
        $this->assertFalse($data3['archive'][0]->isAck());
        $this->assertFalse($data3['feedbackNotAck']);
    }
    
    /**
     * Test INT alarm - no feedback tag
     */
    public function testInt8() {
        
        // Get alarm definition
        $alarmDef = $this->getAlarmDefinition(TagType::INT);
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
        $this->parser->writeInt($alarmDef->getTag()->getName(), -780);
        
        // Wait on alarm pending state
        $this->waitOnAlarmPendingState($alarmDef->getId());
                        
        $data2 = $this->getAlarmsData($alarmDef);
        
        // Alarm OFF
        $this->parser->writeInt($alarmDef->getTag()->getName(), -781);
        
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
        
        $this->assertEquals(TagType::INT, $alarmDef->getTag()->getType());
        $this->assertEquals(AlarmTrigger::TR_TAG_EQ_VAL, $alarmDef->getTrigger());
        $this->assertEquals(-780, $alarmDef->getTriggerNumeric());
        $this->assertEquals('TEST_ALARM_INT', $alarmDef->getTag()->getName());
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
        $this->assertEquals('INT alarm', $data2['pending'][0]->getMessage());
        $this->assertTrue($data2['pending'][0]->isActive());
        $this->assertFalse($data2['pending'][0]->isAck());
        
        $this->assertEquals(0, count($data2['archive']));
        $this->assertNull($data2['feedbackNotAck']);
        
        // Data 3
        $this->assertFalse($data3['alarmDef']->isActive());
        $this->assertTrue($data3['alarmDef']->isPending());
        
        $this->assertEquals(1, count($data3['pending']));
        $this->assertEquals($alarmDef->getId(), $data3['pending'][0]->getDefinitionId());
        $this->assertEquals('INT alarm', $data3['pending'][0]->getMessage());
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
        $this->assertEquals('INT alarm', $data4['archive'][0]->getMessage());
        $this->assertFalse($data4['archive'][0]->isActive());
        $this->assertFalse($data4['archive'][0]->isAck());
        $this->assertNull($data4['feedbackNotAck']);
    }
    
    /**
     * Test INT alarm - no HW ack tag
     */
    public function testInt9() {
        
        // Get alarm definition
        $alarmDef = $this->getAlarmDefinition(TagType::INT);
        // Delete HW ack tag
        $alarmDef->setHWAck();
        // Save alarm definition
        $this->alarmMapper->editAlarm($alarmDef);
        
        $data1 = $this->getAlarmsData($alarmDef);
        
        // Activate alarm definition
        $this->alarmMapper->enableAlarm($alarmDef->getId());
        
        // Alarm ON
        $this->parser->writeInt($alarmDef->getTag()->getName(), -780);
        
        // Wait on alarm pending state
        $this->waitOnAlarmPendingState($alarmDef->getId());
                        
        $data2 = $this->getAlarmsData($alarmDef);
        
        // Alarm OFF
        $this->parser->writeInt($alarmDef->getTag()->getName(), -781);
        
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
        
        $this->assertEquals(TagType::INT, $alarmDef->getTag()->getType());
        $this->assertEquals(AlarmTrigger::TR_TAG_EQ_VAL, $alarmDef->getTrigger());
        $this->assertEquals(-780, $alarmDef->getTriggerNumeric());
        $this->assertEquals('TEST_ALARM_INT', $alarmDef->getTag()->getName());
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
        $this->assertEquals('INT alarm', $data2['pending'][0]->getMessage());
        $this->assertTrue($data2['pending'][0]->isActive());
        $this->assertFalse($data2['pending'][0]->isAck());
        
        $this->assertEquals(0, count($data2['archive']));
        $this->assertNull($data2['feedbackNotAck']);
        
        // Data 3
        $this->assertFalse($data3['alarmDef']->isActive());
        $this->assertTrue($data3['alarmDef']->isPending());
        
        $this->assertEquals(1, count($data3['pending']));
        $this->assertEquals($alarmDef->getId(), $data3['pending'][0]->getDefinitionId());
        $this->assertEquals('INT alarm', $data3['pending'][0]->getMessage());
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
        $this->assertEquals('INT alarm', $data4['archive'][0]->getMessage());
        $this->assertFalse($data4['archive'][0]->isActive());
        $this->assertFalse($data4['archive'][0]->isAck());
        $this->assertNull($data4['feedbackNotAck']);
    }
}
