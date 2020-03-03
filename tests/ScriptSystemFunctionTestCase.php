<?php

namespace App\Tests;

use Symfony\Component\Stopwatch\Stopwatch;

use App\Tests\BaseFunctionTestCase;
use App\Service\Admin\ScriptItemMapper;
use App\Service\Admin\ConfigGeneralMapper;
use App\Entity\Admin\ScriptItem;

/**
 * Base function tests for Script system
 *
 * @author Mateusz Mirosławski
 */
abstract class ScriptSystemFunctionTestCase extends BaseFunctionTestCase {
    
    /**
     * ScriptmMapper object
     */
    protected $scriptMapper;
    
    /**
     * Configuration object
     */
    protected $cfg;
    
    public function setUp() {
                
        parent::setUp();
        
        $this->scriptMapper = self::$container->get(ScriptItemMapper::class);
        $this->cfg = self::$container->get(ConfigGeneralMapper::class);
    }
    
    public function tearDown() {
        
        $this->scriptMapper = null;
        $this->cfg = null;
        
        parent::tearDown();
    }
    
    /**
     * Get script data and feedback tag value
     * 
     * @param ScriptItem $scriptDef Script definition object
     * @return array
     */
    public function getScriptData(ScriptItem $scriptDef): array {
        
        $ret['scriptDef'] = $this->scriptMapper->getScript($scriptDef->getId());
        if ($scriptDef->isFeedbackRun()) {
            $ret['feedbackRun'] = $this->parser->getBit($scriptDef->getFeedbackRun()->getName());
        } else {
            $ret['feedbackRun'] = null;
        }
        
        return $ret;
    }
    
    /**
     * Wait on script flags and feedback tag
     * 
     * @param int $scriptId Script definition identifier
     * @param bool $run Script run flag state
     * @param bool $lock Script lock flag state
     * @param bool $fb Script lock flag state
     * @param int $waitTime Max wait time
     */
    public function waitOnScriptFlags(int $scriptId, bool $run = true, bool $lock = true, bool $fb = true, int $waitTime=1000) {
                
        $script = $this->scriptMapper->getScript($scriptId);
        
        // Delay protection
        $stopwatch = new Stopwatch();
        $stopwatch->start('scriptDelay');
        
        while ($script->isRunning() != $run) {
            usleep(10000);
            $script = $this->scriptMapper->getScript($scriptId);
            
            // Check delay (1s max)
            if ($stopwatch->lap('scriptDelay')->getDuration() > $waitTime) {
                $this->fail("Timeout during waiting on script Run flag!");
            }
        }
        
        while ($script->isLocked() != $lock) {
            usleep(10000);
            $script = $this->scriptMapper->getScript($scriptId);
            
            // Check delay (1s max)
            if ($stopwatch->lap('scriptDelay')->getDuration() > $waitTime) {
                $this->fail("Timeout during waiting on script Lock flag!");
            }
        }
        
        // Check feedback tag
        if ($script->isFeedbackRun()) {
            while ($this->parser->getBit($script->getFeedbackRun()->getName()) != $fb) {
                usleep(10000);

                // Check delay (1s max)
                if ($stopwatch->lap('scriptDelay')->getDuration() > $waitTime) {
                    $this->fail("Timeout during waiting on script FeedbackRun tag!");
                }
            }
        }
        
        // Stop delay
        $stopwatch->stop('scriptDelay');
    }
}
