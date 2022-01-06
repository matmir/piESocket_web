<?php

namespace App\Tests\Func;

use App\Tests\AlarmSystemFunctionTestCase;
use App\Entity\Admin\AlarmTrigger;
use App\Entity\Admin\TagType;

/**
 * Function tests for Alarm system - REAL alarm
 *
 * @author Mateusz Mirosławski
 */
class AlarmSystemRealTest extends AlarmSystemFunctionTestCase
{
    /**
     * Test REAL alarm Tag>value
     */
    public function testReal1()
    {
        // Get alarm definition
        $alarmDef = $this->getAlarmDefinition(TagType::REAL);
        
        $data1 = $this->getAlarmsData($alarmDef);
        
        // Activate alarm definition
        $this->alarmMapper->enableAlarm($alarmDef->getId());
        
        // Alarm ON
        $this->parser->writeReal($alarmDef->getTag()->getName(), 60.76);
        
        // Wait on alarm pending state
        $this->waitOnAlarmPendingState($alarmDef->getId());
                        
        $data2 = $this->getAlarmsData($alarmDef);
        
        // Alarm OFF
        $this->parser->writeReal($alarmDef->getTag()->getName(), 60.75);
        
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
        
        $this->assertEquals(TagType::REAL, $alarmDef->getTag()->getType());
        $this->assertEquals(AlarmTrigger::TR_TAG_GT_VAL, $alarmDef->getTrigger());
        $this->assertEquals(60.75, $alarmDef->getTriggerReal());
        $this->assertEquals('TEST_ALARM_REAL', $alarmDef->getTag()->getName());
        $this->assertEquals('ALARM_REAL_NACK', $alarmDef->getFeedbackNotAck()->getName());
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
        $this->assertEquals('REAL alarm', $data2['pending'][0]->getMessage());
        $this->assertTrue($data2['pending'][0]->isActive());
        $this->assertFalse($data2['pending'][0]->isAck());
        
        $this->assertEquals(0, count($data2['archive']));
        $this->assertTrue($data2['feedbackNotAck']);
        
        // Data 3
        $this->assertFalse($data3['alarmDef']->isActive());
        $this->assertTrue($data3['alarmDef']->isPending());
        
        $this->assertEquals(1, count($data3['pending']));
        $this->assertEquals($alarmDef->getId(), $data3['pending'][0]->getDefinitionId());
        $this->assertEquals('REAL alarm', $data3['pending'][0]->getMessage());
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
        $this->assertEquals('REAL alarm', $data4['archive'][0]->getMessage());
        $this->assertFalse($data4['archive'][0]->isActive());
        $this->assertFalse($data4['archive'][0]->isAck());
        $this->assertFalse($data4['feedbackNotAck']);
    }
    
    /**
     * Test REAL alarm Tag<value
     */
    public function testReal2()
    {
        // Get alarm definition
        $alarmDef = $this->getAlarmDefinition(TagType::REAL);
        // Change trigger
        $alarmDef->setTrigger(AlarmTrigger::TR_TAG_LT_VAL);
        $alarmDef->setTriggerReal(5.78);
        // Save alarm definition
        $this->alarmMapper->editAlarm($alarmDef);
        
        $data1 = $this->getAlarmsData($alarmDef);
        
        // Activate alarm definition
        $this->alarmMapper->enableAlarm($alarmDef->getId());
        
        // Alarm ON
        $this->parser->writeReal($alarmDef->getTag()->getName(), 5.77);
        
        // Wait on alarm pending state
        $this->waitOnAlarmPendingState($alarmDef->getId());
                        
        $data2 = $this->getAlarmsData($alarmDef);
        
        // Alarm OFF
        $this->parser->writeReal($alarmDef->getTag()->getName(), 5.78);
        
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
        
        $this->assertEquals(TagType::REAL, $alarmDef->getTag()->getType());
        $this->assertEquals(AlarmTrigger::TR_TAG_LT_VAL, $alarmDef->getTrigger());
        $this->assertEquals(5.78, $alarmDef->getTriggerReal());
        $this->assertEquals('TEST_ALARM_REAL', $alarmDef->getTag()->getName());
        $this->assertEquals('ALARM_REAL_NACK', $alarmDef->getFeedbackNotAck()->getName());
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
        $this->assertEquals('REAL alarm', $data2['pending'][0]->getMessage());
        $this->assertTrue($data2['pending'][0]->isActive());
        $this->assertFalse($data2['pending'][0]->isAck());
        
        $this->assertEquals(0, count($data2['archive']));
        $this->assertTrue($data2['feedbackNotAck']);
        
        // Data 3
        $this->assertFalse($data3['alarmDef']->isActive());
        $this->assertTrue($data3['alarmDef']->isPending());
        
        $this->assertEquals(1, count($data3['pending']));
        $this->assertEquals($alarmDef->getId(), $data3['pending'][0]->getDefinitionId());
        $this->assertEquals('REAL alarm', $data3['pending'][0]->getMessage());
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
        $this->assertEquals('REAL alarm', $data4['archive'][0]->getMessage());
        $this->assertFalse($data4['archive'][0]->isActive());
        $this->assertFalse($data4['archive'][0]->isAck());
        $this->assertFalse($data4['feedbackNotAck']);
    }
    
    /**
     * Test REAL alarm Tag>=value
     */
    public function testReal3()
    {
        // Get alarm definition
        $alarmDef = $this->getAlarmDefinition(TagType::REAL);
        // Change trigger
        $alarmDef->setTrigger(AlarmTrigger::TR_TAG_GTE_VAL);
        $alarmDef->setTriggerReal(150.08);
        // Save alarm definition
        $this->alarmMapper->editAlarm($alarmDef);
        
        $data1 = $this->getAlarmsData($alarmDef);
        
        // Activate alarm definition
        $this->alarmMapper->enableAlarm($alarmDef->getId());
        
        // Alarm ON
        $this->parser->writeReal($alarmDef->getTag()->getName(), 150.08);
        
        // Wait on alarm pending state
        $this->waitOnAlarmPendingState($alarmDef->getId());
                        
        $data2 = $this->getAlarmsData($alarmDef);
        
        // Alarm OFF
        $this->parser->writeReal($alarmDef->getTag()->getName(), 150.07);
        
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
        
        $this->assertEquals(TagType::REAL, $alarmDef->getTag()->getType());
        $this->assertEquals(AlarmTrigger::TR_TAG_GTE_VAL, $alarmDef->getTrigger());
        $this->assertEquals(150.08, $alarmDef->getTriggerReal());
        $this->assertEquals('TEST_ALARM_REAL', $alarmDef->getTag()->getName());
        $this->assertEquals('ALARM_REAL_NACK', $alarmDef->getFeedbackNotAck()->getName());
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
        $this->assertEquals('REAL alarm', $data2['pending'][0]->getMessage());
        $this->assertTrue($data2['pending'][0]->isActive());
        $this->assertFalse($data2['pending'][0]->isAck());
        
        $this->assertEquals(0, count($data2['archive']));
        $this->assertTrue($data2['feedbackNotAck']);
        
        // Data 3
        $this->assertFalse($data3['alarmDef']->isActive());
        $this->assertTrue($data3['alarmDef']->isPending());
        
        $this->assertEquals(1, count($data3['pending']));
        $this->assertEquals($alarmDef->getId(), $data3['pending'][0]->getDefinitionId());
        $this->assertEquals('REAL alarm', $data3['pending'][0]->getMessage());
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
        $this->assertEquals('REAL alarm', $data4['archive'][0]->getMessage());
        $this->assertFalse($data4['archive'][0]->isActive());
        $this->assertFalse($data4['archive'][0]->isAck());
        $this->assertFalse($data4['feedbackNotAck']);
    }
    
    /**
     * Test REAL alarm Tag<=value
     */
    public function testReal4()
    {
        // Get alarm definition
        $alarmDef = $this->getAlarmDefinition(TagType::REAL);
        // Change trigger
        $alarmDef->setTrigger(AlarmTrigger::TR_TAG_LTE_VAL);
        $alarmDef->setTriggerReal(-85.45);
        // Save alarm definition
        $this->alarmMapper->editAlarm($alarmDef);
        
        $data1 = $this->getAlarmsData($alarmDef);
        
        // Activate alarm definition
        $this->alarmMapper->enableAlarm($alarmDef->getId());
        
        // Alarm ON
        $this->parser->writeReal($alarmDef->getTag()->getName(), -85.45);
        
        // Wait on alarm pending state
        $this->waitOnAlarmPendingState($alarmDef->getId());
                        
        $data2 = $this->getAlarmsData($alarmDef);
        
        // Alarm OFF
        $this->parser->writeReal($alarmDef->getTag()->getName(), 5.93);
        
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
        
        $this->assertEquals(TagType::REAL, $alarmDef->getTag()->getType());
        $this->assertEquals(AlarmTrigger::TR_TAG_LTE_VAL, $alarmDef->getTrigger());
        $this->assertEquals(-85.45, $alarmDef->getTriggerReal());
        $this->assertEquals('TEST_ALARM_REAL', $alarmDef->getTag()->getName());
        $this->assertEquals('ALARM_REAL_NACK', $alarmDef->getFeedbackNotAck()->getName());
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
        $this->assertEquals('REAL alarm', $data2['pending'][0]->getMessage());
        $this->assertTrue($data2['pending'][0]->isActive());
        $this->assertFalse($data2['pending'][0]->isAck());
        
        $this->assertEquals(0, count($data2['archive']));
        $this->assertTrue($data2['feedbackNotAck']);
        
        // Data 3
        $this->assertFalse($data3['alarmDef']->isActive());
        $this->assertTrue($data3['alarmDef']->isPending());
        
        $this->assertEquals(1, count($data3['pending']));
        $this->assertEquals($alarmDef->getId(), $data3['pending'][0]->getDefinitionId());
        $this->assertEquals('REAL alarm', $data3['pending'][0]->getMessage());
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
        $this->assertEquals('REAL alarm', $data4['archive'][0]->getMessage());
        $this->assertFalse($data4['archive'][0]->isActive());
        $this->assertFalse($data4['archive'][0]->isAck());
        $this->assertFalse($data4['feedbackNotAck']);
    }
    
    /**
     * Test REAL alarm Tag=value
     */
    public function testReal5()
    {
        // Get alarm definition
        $alarmDef = $this->getAlarmDefinition(TagType::REAL);
        // Change trigger
        $alarmDef->setTrigger(AlarmTrigger::TR_TAG_EQ_VAL);
        $alarmDef->setTriggerReal(8.87);
        // Save alarm definition
        $this->alarmMapper->editAlarm($alarmDef);
        
        $data1 = $this->getAlarmsData($alarmDef);
        
        // Activate alarm definition
        $this->alarmMapper->enableAlarm($alarmDef->getId());
        
        // Alarm ON
        $this->parser->writeReal($alarmDef->getTag()->getName(), 8.87);
        
        // Wait on alarm pending state
        $this->waitOnAlarmPendingState($alarmDef->getId());
                        
        $data2 = $this->getAlarmsData($alarmDef);
        
        // Alarm OFF
        $this->parser->writeReal($alarmDef->getTag()->getName(), 8.871);
        
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
        
        $this->assertEquals(TagType::REAL, $alarmDef->getTag()->getType());
        $this->assertEquals(AlarmTrigger::TR_TAG_EQ_VAL, $alarmDef->getTrigger());
        $this->assertEquals(8.87, $alarmDef->getTriggerReal());
        $this->assertEquals('TEST_ALARM_REAL', $alarmDef->getTag()->getName());
        $this->assertEquals('ALARM_REAL_NACK', $alarmDef->getFeedbackNotAck()->getName());
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
        $this->assertEquals('REAL alarm', $data2['pending'][0]->getMessage());
        $this->assertTrue($data2['pending'][0]->isActive());
        $this->assertFalse($data2['pending'][0]->isAck());
        
        $this->assertEquals(0, count($data2['archive']));
        $this->assertTrue($data2['feedbackNotAck']);
        
        // Data 3
        $this->assertFalse($data3['alarmDef']->isActive());
        $this->assertTrue($data3['alarmDef']->isPending());
        
        $this->assertEquals(1, count($data3['pending']));
        $this->assertEquals($alarmDef->getId(), $data3['pending'][0]->getDefinitionId());
        $this->assertEquals('REAL alarm', $data3['pending'][0]->getMessage());
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
        $this->assertEquals('REAL alarm', $data4['archive'][0]->getMessage());
        $this->assertFalse($data4['archive'][0]->isActive());
        $this->assertFalse($data4['archive'][0]->isAck());
        $this->assertFalse($data4['feedbackNotAck']);
    }
    
    /**
     * Test REAL alarm Tag!=value
     */
    public function testReal6()
    {
        // Get alarm definition
        $alarmDef = $this->getAlarmDefinition(TagType::REAL);
        // Change trigger
        $alarmDef->setTrigger(AlarmTrigger::TR_TAG_NEQ_VAL);
        $alarmDef->setTriggerReal(8.871);
        // Save alarm definition
        $this->alarmMapper->editAlarm($alarmDef);
        
        $data1 = $this->getAlarmsData($alarmDef);
        
        // Activate alarm definition
        $this->alarmMapper->enableAlarm($alarmDef->getId());
        
        // Alarm ON
        $this->parser->writeReal($alarmDef->getTag()->getName(), 8.872);
        
        // Wait on alarm pending state
        $this->waitOnAlarmPendingState($alarmDef->getId());
                        
        $data2 = $this->getAlarmsData($alarmDef);
        
        // Alarm OFF
        $this->parser->writeReal($alarmDef->getTag()->getName(), 8.871);
        
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
        
        $this->assertEquals(TagType::REAL, $alarmDef->getTag()->getType());
        $this->assertEquals(AlarmTrigger::TR_TAG_NEQ_VAL, $alarmDef->getTrigger());
        $this->assertEquals(8.871, $alarmDef->getTriggerReal());
        $this->assertEquals('TEST_ALARM_REAL', $alarmDef->getTag()->getName());
        $this->assertEquals('ALARM_REAL_NACK', $alarmDef->getFeedbackNotAck()->getName());
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
        $this->assertEquals('REAL alarm', $data2['pending'][0]->getMessage());
        $this->assertTrue($data2['pending'][0]->isActive());
        $this->assertFalse($data2['pending'][0]->isAck());
        
        $this->assertEquals(0, count($data2['archive']));
        $this->assertTrue($data2['feedbackNotAck']);
        
        // Data 3
        $this->assertFalse($data3['alarmDef']->isActive());
        $this->assertTrue($data3['alarmDef']->isPending());
        
        $this->assertEquals(1, count($data3['pending']));
        $this->assertEquals($alarmDef->getId(), $data3['pending'][0]->getDefinitionId());
        $this->assertEquals('REAL alarm', $data3['pending'][0]->getMessage());
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
        $this->assertEquals('REAL alarm', $data4['archive'][0]->getMessage());
        $this->assertFalse($data4['archive'][0]->isActive());
        $this->assertFalse($data4['archive'][0]->isAck());
        $this->assertFalse($data4['feedbackNotAck']);
    }
    
    /**
     * Test REAL alarm - AutoAck
     */
    public function testReal7()
    {
        // Get alarm definition
        $alarmDef = $this->getAlarmDefinition(TagType::REAL);
        // Set AutoAck flag
        $alarmDef->setAutoAck(true);
        // Change trigger
        $alarmDef->setTrigger(AlarmTrigger::TR_TAG_EQ_VAL);
        $alarmDef->setTriggerReal(95.85);
        // Save alarm definition
        $this->alarmMapper->editAlarm($alarmDef);
        
        $data1 = $this->getAlarmsData($alarmDef);
        
        // Activate alarm definition
        $this->alarmMapper->enableAlarm($alarmDef->getId());
        
        // Alarm ON
        $this->parser->writeReal($alarmDef->getTag()->getName(), 95.85);
        
        // Wait on alarm pending state
        $this->waitOnAlarmPendingState($alarmDef->getId());
                        
        $data2 = $this->getAlarmsData($alarmDef);
        
        // Alarm OFF
        $this->parser->writeReal($alarmDef->getTag()->getName(), 95.87);
        
        // Wait on alarm pending state to be off
        $this->waitOnAlarmPendingState($alarmDef->getId(), false);
                
        $data3 = $this->getAlarmsData($alarmDef);
        
        // Deactivate alarm definition
        $this->alarmMapper->enableAlarm($alarmDef->getId(), false);
        
        $this->assertEquals(TagType::REAL, $alarmDef->getTag()->getType());
        $this->assertEquals(AlarmTrigger::TR_TAG_EQ_VAL, $alarmDef->getTrigger());
        $this->assertEquals(95.85, $alarmDef->getTriggerReal());
        $this->assertEquals('TEST_ALARM_REAL', $alarmDef->getTag()->getName());
        $this->assertEquals('ALARM_REAL_NACK', $alarmDef->getFeedbackNotAck()->getName());
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
        $this->assertEquals('REAL alarm', $data2['pending'][0]->getMessage());
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
        $this->assertEquals('REAL alarm', $data3['archive'][0]->getMessage());
        $this->assertFalse($data3['archive'][0]->isActive());
        $this->assertFalse($data3['archive'][0]->isAck());
        $this->assertFalse($data3['feedbackNotAck']);
    }
    
    /**
     * Test REAL alarm - no feedback tag
     */
    public function testReal8()
    {
        // Get alarm definition
        $alarmDef = $this->getAlarmDefinition(TagType::REAL);
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
        $this->parser->writeReal($alarmDef->getTag()->getName(), 95.85);
        
        // Wait on alarm pending state
        $this->waitOnAlarmPendingState($alarmDef->getId());
                        
        $data2 = $this->getAlarmsData($alarmDef);
        
        // Alarm OFF
        $this->parser->writeReal($alarmDef->getTag()->getName(), 95.87);
        
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
        
        $this->assertEquals(TagType::REAL, $alarmDef->getTag()->getType());
        $this->assertEquals(AlarmTrigger::TR_TAG_EQ_VAL, $alarmDef->getTrigger());
        $this->assertEquals(95.85, $alarmDef->getTriggerReal());
        $this->assertEquals('TEST_ALARM_REAL', $alarmDef->getTag()->getName());
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
        $this->assertEquals('REAL alarm', $data2['pending'][0]->getMessage());
        $this->assertTrue($data2['pending'][0]->isActive());
        $this->assertFalse($data2['pending'][0]->isAck());
        
        $this->assertEquals(0, count($data2['archive']));
        $this->assertNull($data2['feedbackNotAck']);
        
        // Data 3
        $this->assertFalse($data3['alarmDef']->isActive());
        $this->assertTrue($data3['alarmDef']->isPending());
        
        $this->assertEquals(1, count($data3['pending']));
        $this->assertEquals($alarmDef->getId(), $data3['pending'][0]->getDefinitionId());
        $this->assertEquals('REAL alarm', $data3['pending'][0]->getMessage());
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
        $this->assertEquals('REAL alarm', $data4['archive'][0]->getMessage());
        $this->assertFalse($data4['archive'][0]->isActive());
        $this->assertFalse($data4['archive'][0]->isAck());
        $this->assertNull($data4['feedbackNotAck']);
    }
    
    /**
     * Test REAL alarm - no HW ack tag
     */
    public function testReal9()
    {
        // Get alarm definition
        $alarmDef = $this->getAlarmDefinition(TagType::REAL);
        // Delete HW ack tag
        $alarmDef->setHWAck();
        // Save alarm definition
        $this->alarmMapper->editAlarm($alarmDef);
        
        $data1 = $this->getAlarmsData($alarmDef);
        
        // Activate alarm definition
        $this->alarmMapper->enableAlarm($alarmDef->getId());
        
        // Alarm ON
        $this->parser->writeReal($alarmDef->getTag()->getName(), 95.85);
        
        // Wait on alarm pending state
        $this->waitOnAlarmPendingState($alarmDef->getId());
                        
        $data2 = $this->getAlarmsData($alarmDef);
        
        // Alarm OFF
        $this->parser->writeReal($alarmDef->getTag()->getName(), 95.81);
        
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
        
        $this->assertEquals(TagType::REAL, $alarmDef->getTag()->getType());
        $this->assertEquals(AlarmTrigger::TR_TAG_EQ_VAL, $alarmDef->getTrigger());
        $this->assertEquals(95.85, $alarmDef->getTriggerReal());
        $this->assertEquals('TEST_ALARM_REAL', $alarmDef->getTag()->getName());
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
        $this->assertEquals('REAL alarm', $data2['pending'][0]->getMessage());
        $this->assertTrue($data2['pending'][0]->isActive());
        $this->assertFalse($data2['pending'][0]->isAck());
        
        $this->assertEquals(0, count($data2['archive']));
        $this->assertNull($data2['feedbackNotAck']);
        
        // Data 3
        $this->assertFalse($data3['alarmDef']->isActive());
        $this->assertTrue($data3['alarmDef']->isPending());
        
        $this->assertEquals(1, count($data3['pending']));
        $this->assertEquals($alarmDef->getId(), $data3['pending'][0]->getDefinitionId());
        $this->assertEquals('REAL alarm', $data3['pending'][0]->getMessage());
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
        $this->assertEquals('REAL alarm', $data4['archive'][0]->getMessage());
        $this->assertFalse($data4['archive'][0]->isActive());
        $this->assertFalse($data4['archive'][0]->isAck());
        $this->assertNull($data4['feedbackNotAck']);
    }
}
