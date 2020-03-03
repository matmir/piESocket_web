<?php

namespace App\Tests\Func;

use App\Tests\AlarmSystemFunctionTestCase;
use App\Entity\Admin\AlarmTrigger;
use App\Entity\Admin\TagType;

/**
 * Function tests for Alarm system - BIN alarm
 *
 * @author Mateusz Mirosławski
 */
class AlarmSystemBinTest extends AlarmSystemFunctionTestCase {
    
    /**
     * Test BIN alarm
     */
    public function testBin1() {
        
        // Get alarm definition
        $alarmDef = $this->getAlarmDefinition(TagType::Bit);
        
        $data1 = $this->getAlarmsData($alarmDef);
        
        // Activate alarm definition
        $this->alarmMapper->enableAlarm($alarmDef->getId());
        
        // Alarm ON
        $this->parser->setBit($alarmDef->getTag()->getName());
        
        // Wait on alarm pending state
        $this->waitOnAlarmPendingState($alarmDef->getId());
                        
        $data2 = $this->getAlarmsData($alarmDef);
        
        // Alarm OFF
        $this->parser->resetBit($alarmDef->getTag()->getName());
        
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
        
        $this->assertEquals(TagType::Bit, $alarmDef->getTag()->getType());
        $this->assertEquals(AlarmTrigger::TR_BIN, $alarmDef->getTrigger());
        $this->assertEquals('TEST_ALARM_BIN', $alarmDef->getTag()->getName());
        $this->assertEquals('ALARM_BIN_NACK', $alarmDef->getFeedbackNotAck()->getName());
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
        $this->assertEquals('BIN alarm', $data2['pending'][0]->getMessage());
        $this->assertTrue($data2['pending'][0]->isActive());
        $this->assertFalse($data2['pending'][0]->isAck());
        
        $this->assertEquals(0, count($data2['archive']));
        $this->assertTrue($data2['feedbackNotAck']);
        
        // Data 3
        $this->assertFalse($data3['alarmDef']->isActive());
        $this->assertTrue($data3['alarmDef']->isPending());
        
        $this->assertEquals(1, count($data3['pending']));
        $this->assertEquals($alarmDef->getId(), $data3['pending'][0]->getDefinitionId());
        $this->assertEquals('BIN alarm', $data3['pending'][0]->getMessage());
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
        $this->assertEquals('BIN alarm', $data4['archive'][0]->getMessage());
        $this->assertFalse($data4['archive'][0]->isActive());
        $this->assertFalse($data4['archive'][0]->isAck());
        $this->assertFalse($data4['feedbackNotAck']);
    }
    
    /**
     * Test BIN alarm - AutoAck
     */
    public function testBin2() {
        
        // Get alarm definition
        $alarmDef = $this->getAlarmDefinition(TagType::Bit);
        // Set AutoAck flag
        $alarmDef->setAutoAck(true);
        // Save alarm definition
        $this->alarmMapper->editAlarm($alarmDef);
        
        $data1 = $this->getAlarmsData($alarmDef);
        
        // Activate alarm definition
        $this->alarmMapper->enableAlarm($alarmDef->getId());
        
        // Alarm ON
        $this->parser->setBit($alarmDef->getTag()->getName());
        
        // Wait on alarm pending state
        $this->waitOnAlarmPendingState($alarmDef->getId());
                        
        $data2 = $this->getAlarmsData($alarmDef);
        
        // Alarm OFF
        $this->parser->resetBit($alarmDef->getTag()->getName());
        
        // Wait on alarm pending state to be off
        $this->waitOnAlarmPendingState($alarmDef->getId(), false);
                
        $data3 = $this->getAlarmsData($alarmDef);
        
        // Deactivate alarm definition
        $this->alarmMapper->enableAlarm($alarmDef->getId(), false);
        
        $this->assertEquals(TagType::Bit, $alarmDef->getTag()->getType());
        $this->assertEquals(AlarmTrigger::TR_BIN, $alarmDef->getTrigger());
        $this->assertEquals('TEST_ALARM_BIN', $alarmDef->getTag()->getName());
        $this->assertEquals('ALARM_BIN_NACK', $alarmDef->getFeedbackNotAck()->getName());
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
        $this->assertEquals('BIN alarm', $data2['pending'][0]->getMessage());
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
        $this->assertEquals('BIN alarm', $data3['archive'][0]->getMessage());
        $this->assertFalse($data3['archive'][0]->isActive());
        $this->assertFalse($data3['archive'][0]->isAck());
        $this->assertFalse($data3['feedbackNotAck']);
    }
    
    /**
     * Test BIN alarm - no feedback tag
     */
    public function testBin3() {
        
        // Get alarm definition
        $alarmDef = $this->getAlarmDefinition(TagType::Bit);
        // Delete feedback tag
        $alarmDef->setFeedbackNotAck();
        // Set AutoAck flag
        $alarmDef->setAutoAck(false);
        // Save alarm definition
        $this->alarmMapper->editAlarm($alarmDef);
        
        $data1 = $this->getAlarmsData($alarmDef);
        
        // Activate alarm definition
        $this->alarmMapper->enableAlarm($alarmDef->getId());
        
        // Alarm ON
        $this->parser->setBit($alarmDef->getTag()->getName());
        
        // Wait on alarm pending state
        $this->waitOnAlarmPendingState($alarmDef->getId());
                        
        $data2 = $this->getAlarmsData($alarmDef);
        
        // Alarm OFF
        $this->parser->resetBit($alarmDef->getTag()->getName());
        
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
        
        $this->assertEquals(TagType::Bit, $alarmDef->getTag()->getType());
        $this->assertEquals(AlarmTrigger::TR_BIN, $alarmDef->getTrigger());
        $this->assertEquals('TEST_ALARM_BIN', $alarmDef->getTag()->getName());
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
        $this->assertEquals('BIN alarm', $data2['pending'][0]->getMessage());
        $this->assertTrue($data2['pending'][0]->isActive());
        $this->assertFalse($data2['pending'][0]->isAck());
        
        $this->assertEquals(0, count($data2['archive']));
        $this->assertNull($data2['feedbackNotAck']);
        
        // Data 3
        $this->assertFalse($data3['alarmDef']->isActive());
        $this->assertTrue($data3['alarmDef']->isPending());
        
        $this->assertEquals(1, count($data3['pending']));
        $this->assertEquals($alarmDef->getId(), $data3['pending'][0]->getDefinitionId());
        $this->assertEquals('BIN alarm', $data3['pending'][0]->getMessage());
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
        $this->assertEquals('BIN alarm', $data4['archive'][0]->getMessage());
        $this->assertFalse($data4['archive'][0]->isActive());
        $this->assertFalse($data4['archive'][0]->isAck());
        $this->assertNull($data4['feedbackNotAck']);
    }
    
    /**
     * Test BIN alarm - no HW ack tag
     */
    public function testBin4() {
        
        // Get alarm definition
        $alarmDef = $this->getAlarmDefinition(TagType::Bit);
        // Delete HW ack tag
        $alarmDef->setHWAck();
        // Save alarm definition
        $this->alarmMapper->editAlarm($alarmDef);
        
        $data1 = $this->getAlarmsData($alarmDef);
        
        // Activate alarm definition
        $this->alarmMapper->enableAlarm($alarmDef->getId());
        
        // Alarm ON
        $this->parser->setBit($alarmDef->getTag()->getName());
        
        // Wait on alarm pending state
        $this->waitOnAlarmPendingState($alarmDef->getId());
                        
        $data2 = $this->getAlarmsData($alarmDef);
        
        // Alarm OFF
        $this->parser->resetBit($alarmDef->getTag()->getName());
        
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
        
        $this->assertEquals(TagType::Bit, $alarmDef->getTag()->getType());
        $this->assertEquals(AlarmTrigger::TR_BIN, $alarmDef->getTrigger());
        $this->assertEquals('TEST_ALARM_BIN', $alarmDef->getTag()->getName());
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
        $this->assertEquals('BIN alarm', $data2['pending'][0]->getMessage());
        $this->assertTrue($data2['pending'][0]->isActive());
        $this->assertFalse($data2['pending'][0]->isAck());
        
        $this->assertEquals(0, count($data2['archive']));
        $this->assertNull($data2['feedbackNotAck']);
        
        // Data 3
        $this->assertFalse($data3['alarmDef']->isActive());
        $this->assertTrue($data3['alarmDef']->isPending());
        
        $this->assertEquals(1, count($data3['pending']));
        $this->assertEquals($alarmDef->getId(), $data3['pending'][0]->getDefinitionId());
        $this->assertEquals('BIN alarm', $data3['pending'][0]->getMessage());
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
        $this->assertEquals('BIN alarm', $data4['archive'][0]->getMessage());
        $this->assertFalse($data4['archive'][0]->isActive());
        $this->assertFalse($data4['archive'][0]->isAck());
        $this->assertNull($data4['feedbackNotAck']);
    }
}
