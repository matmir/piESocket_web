<?php

namespace App\Tests;

use Symfony\Component\Stopwatch\Stopwatch;
use App\Tests\BaseFunctionTestCase;
use App\Service\Admin\AlarmMapper;
use App\Entity\Admin\Alarm;

/**
 * Base function tests for Alarm system
 *
 * @author Mateusz Mirosławski
 */
abstract class AlarmSystemFunctionTestCase extends BaseFunctionTestCase
{
    /**
     * AlarmMapper object
     */
    protected $alarmMapper;
    
    public function setUp()
    {
        parent::setUp();
        
        $this->alarmMapper = self::$container->get(AlarmMapper::class);
        
        // Delete archived alarms
        $this->alarmMapper->deleteArchivedAlarm();
    }
    
    public function tearDown()
    {
        $this->alarmMapper = null;
        
        parent::tearDown();
    }
    
    /**
     * Get alarm definition
     *
     * @param int $type Alarm tag type
     * @return Alarm
     */
    public function getAlarmDefinition(int $type): Alarm
    {
        // Get alarms
        $alarms = $this->alarmMapper->getAlarms();
        
        $alarm = null;
        
        for ($i = 0; $i < count($alarms); ++$i) {
            if ($alarms[$i]->getTag()->getType() == $type) {
                $alarm = $alarms[$i];
                // stop searching
                break;
            }
        }
        
        return $alarm;
    }
    
    /**
     * Get alarms data (definition, pending, archived)
     *
     * @param Alarm $alarmDef Alarm definition object
     * @return array
     */
    public function getAlarmsData(Alarm $alarmDef): array
    {
        $ret['alarmDef'] = $this->alarmMapper->getAlarm($alarmDef->getId());
        $ret['pending'] = $this->alarmMapper->getPendingAlarms();
        $ret['archive'] = $this->alarmMapper->getArchivedAlarms();
        if ($alarmDef->isFeedbackNotAck()) {
            $ret['feedbackNotAck'] = $this->parser->getBit($alarmDef->getFeedbackNotAck()->getName());
        } else {
            $ret['feedbackNotAck'] = null;
        }
        
        return $ret;
    }
    
    /**
     * Wait on alarm pending flag
     *
     * @param int $alarmId Alarm definition identifier
     * @param bool $state Alarm pending flag state
     */
    public function waitOnAlarmPendingState(int $alarmId, bool $state = true)
    {
        $alarm = $this->alarmMapper->getAlarm($alarmId);
        
        // Delay protection
        $stopwatch = new Stopwatch();
        $stopwatch->start('alarmDelay');
        
        while ($alarm->isPending() != $state) {
            usleep(10000);
            $alarm = $this->alarmMapper->getAlarm($alarmId);
            
            // Check delay (1s max)
            if ($stopwatch->lap('alarmDelay')->getDuration() > 1000) {
                $this->fail("Timeout during waiting on alarm Pending flag!");
            }
        }
        
        // Check feedback tag
        if ($alarm->isFeedbackNotAck()) {
            while ($this->parser->getBit($alarm->getFeedbackNotAck()->getName()) != $state) {
                usleep(10000);

                // Check delay (1s max)
                if ($stopwatch->lap('alarmDelay')->getDuration() > 1000) {
                    $this->fail("Timeout during waiting on alarm FeedbackNotAck tag!");
                }
            }
        }
        
        // Stop delay
        $stopwatch->stop('alarmDelay');
    }
    
    /**
     * Wait on alarm active flag
     *
     * @param int $alarmId Alarm definition identifier
     * @param bool $state Alarm active flag state
     */
    public function waitOnAlarmActiveState(int $alarmId, bool $state = true)
    {
        $alarm = $this->alarmMapper->getAlarm($alarmId);
        
        // Delay protection
        $stopwatch = new Stopwatch();
        $stopwatch->start('alarmDelay');
        
        while ($alarm->isActive() != $state) {
            usleep(10000);
            $alarm = $this->alarmMapper->getAlarm($alarmId);
            
            // Check delay (1s max)
            if ($stopwatch->lap('alarmDelay')->getDuration() > 1000) {
                $this->fail("Timeout during waiting on alarm Active flag!");
            }
        }
        
        // Stop delay
        $stopwatch->stop('alarmDelay');
    }
}
