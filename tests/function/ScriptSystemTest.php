<?php

namespace App\Tests\Func;

use App\Tests\ScriptSystemFunctionTestCase;

/**
 * Function tests for Script system
 *
 * @author Mateusz Mirosławski
 */
class ScriptSystemTest extends ScriptSystemFunctionTestCase
{
    /**
     * Test script - test1.sh
     */
    public function testScript1()
    {
        // Get script definition
        $script = $this->scriptMapper->getScriptByName('test1.sh');
        
        // Get data
        $data1 = $this->getScriptData($script);
        
        // Activate script definition
        $this->scriptMapper->enableScript($script->getId());
        
        // Set script trigger bit
        $this->parser->setBit($script->getTag()->getName());
        
        // Wait on script run flags  (run, lock, feedback)
        $this->waitOnScriptFlags($script->getId());
        
        // Get data
        $data2 = $this->getScriptData($script);
        
        // Wait on script finish flags (run, lock, feedback)
        $this->waitOnScriptFlags($script->getId(), false, true, false, 20000);
        
        // Get data
        $data3 = $this->getScriptData($script);
        
        // Reset script trigger bit
        $this->parser->resetBit($script->getTag()->getName());
        
        // Wait on script run flags  (run, lock, feedback)
        $this->waitOnScriptFlags($script->getId(), false, false, false);
        
        // Get data
        $data4 = $this->getScriptData($script);
        
        // Deactivate script definition
        $this->scriptMapper->enableScript($script->getId(), false);
        
        // Check execution log
        $logPath = $this->cfg->getServerAppPath() . 'logs/scriptOutput/';
        $files = scandir($logPath);
        $log = file_get_contents($logPath . $files[2]);
        
        $this->assertEquals('TEST_SCRIPT1', $script->getTag()->getName());
        $this->assertEquals('TEST_SCRIPT1_RUN', $script->getFeedbackRun()->getName());
        
        $this->assertFalse($data1['scriptDef']->isRunning());
        $this->assertFalse($data1['scriptDef']->isLocked());
        $this->assertFalse($data1['feedbackRun']);
        
        $this->assertTrue($data2['scriptDef']->isRunning());
        $this->assertTrue($data2['scriptDef']->isLocked());
        $this->assertTrue($data2['feedbackRun']);
        
        $this->assertFalse($data3['scriptDef']->isRunning());
        $this->assertTrue($data3['scriptDef']->isLocked());
        $this->assertFalse($data3['feedbackRun']);
        
        $this->assertFalse($data4['scriptDef']->isRunning());
        $this->assertFalse($data4['scriptDef']->isLocked());
        $this->assertFalse($data4['feedbackRun']);
        
        $this->assertEquals(3, count($files));
        $this->assertEquals('.', $files[0]);
        $this->assertEquals('..', $files[1]);
        $this->assertTrue(strpos($log, $script->getName()) !== false);
    }
    
    /**
     * Test script - test1.sh - no feedbackRun tag
     */
    public function testScript2()
    {
        // Get script definition
        $script = $this->scriptMapper->getScriptByName('test1.sh');
        // Delete feedback tag
        $script->setFeedbackRun();
        $this->scriptMapper->editScript($script);
        // Get data
        $data1 = $this->getScriptData($script);
        
        // Activate script definition
        $this->scriptMapper->enableScript($script->getId());
        
        // Set script trigger bit
        $this->parser->setBit($script->getTag()->getName());
        
        // Wait on script run flags  (run, lock, feedback)
        $this->waitOnScriptFlags($script->getId());
        
        // Get data
        $data2 = $this->getScriptData($script);
        
        // Wait on script finish flags (run, lock, feedback)
        $this->waitOnScriptFlags($script->getId(), false, true, false, 7000);
        
        // Get data
        $data3 = $this->getScriptData($script);
        
        // Reset script trigger bit
        $this->parser->resetBit($script->getTag()->getName());
        
        // Wait on script run flags  (run, lock, feedback)
        $this->waitOnScriptFlags($script->getId(), false, false, false);
        
        // Get data
        $data4 = $this->getScriptData($script);
        
        // Deactivate script definition
        $this->scriptMapper->enableScript($script->getId(), false);
        
        // Check execution log
        $logPath = $this->cfg->getServerAppPath() . 'logs/scriptOutput/';
        $files = scandir($logPath);
        $log = file_get_contents($logPath . $files[3]);
        
        $this->assertEquals('TEST_SCRIPT1', $script->getTag()->getName());
        $this->assertNull($script->getFeedbackRun());
        
        $this->assertFalse($data1['scriptDef']->isRunning());
        $this->assertFalse($data1['scriptDef']->isLocked());
        $this->assertNull($data1['feedbackRun']);
        
        $this->assertTrue($data2['scriptDef']->isRunning());
        $this->assertTrue($data2['scriptDef']->isLocked());
        $this->assertNull($data2['feedbackRun']);
        
        $this->assertFalse($data3['scriptDef']->isRunning());
        $this->assertTrue($data3['scriptDef']->isLocked());
        $this->assertNull($data3['feedbackRun']);
        
        $this->assertFalse($data4['scriptDef']->isRunning());
        $this->assertFalse($data4['scriptDef']->isLocked());
        $this->assertNull($data4['feedbackRun']);
        
        $this->assertEquals(4, count($files));
        $this->assertEquals('.', $files[0]);
        $this->assertEquals('..', $files[1]);
        $this->assertTrue(strpos($log, $script->getName()) !== false);
    }
    
    /**
     * Test script - test2.sh
     */
    public function testScript3()
    {
        // Get script definition
        $script = $this->scriptMapper->getScriptByName('test2.sh');
        
        // Get data
        $data1 = $this->getScriptData($script);
        
        // Activate script definition
        $this->scriptMapper->enableScript($script->getId());
        
        // Set script trigger bit
        $this->parser->setBit($script->getTag()->getName());
        
        // Wait on script run flags  (run, lock, feedback)
        $this->waitOnScriptFlags($script->getId());
        
        // Get data
        $data2 = $this->getScriptData($script);
        
        // Wait on script finish flags (run, lock, feedback)
        $this->waitOnScriptFlags($script->getId(), false, true, false, 7000);
        
        // Get data
        $data3 = $this->getScriptData($script);
        
        // Reset script trigger bit
        $this->parser->resetBit($script->getTag()->getName());
        
        // Wait on script run flags  (run, lock, feedback)
        $this->waitOnScriptFlags($script->getId(), false, false, false);
        
        // Get data
        $data4 = $this->getScriptData($script);
        
        // Deactivate script definition
        $this->scriptMapper->enableScript($script->getId(), false);
        
        // Check execution log
        $logPath = $this->cfg->getServerAppPath() . 'logs/scriptOutput/';
        $files = scandir($logPath);
        $log = file_get_contents($logPath . $files[4]);
        
        $this->assertEquals('TEST_SCRIPT2', $script->getTag()->getName());
        $this->assertNull($script->getFeedbackRun());
        
        $this->assertFalse($data1['scriptDef']->isRunning());
        $this->assertFalse($data1['scriptDef']->isLocked());
        $this->assertNull($data1['feedbackRun']);
        
        $this->assertTrue($data2['scriptDef']->isRunning());
        $this->assertTrue($data2['scriptDef']->isLocked());
        $this->assertNull($data2['feedbackRun']);
        
        $this->assertFalse($data3['scriptDef']->isRunning());
        $this->assertTrue($data3['scriptDef']->isLocked());
        $this->assertNull($data3['feedbackRun']);
        
        $this->assertFalse($data4['scriptDef']->isRunning());
        $this->assertFalse($data4['scriptDef']->isLocked());
        $this->assertNull($data4['feedbackRun']);
        
        $this->assertEquals(5, count($files));
        $this->assertEquals('.', $files[0]);
        $this->assertEquals('..', $files[1]);
        $this->assertTrue(strpos($log, $script->getName()) !== false);
    }
    
    /**
     * Test script - test1.sh and test2.sh
     */
    public function testScript4()
    {
        // Get script definition
        $script1 = $this->scriptMapper->getScriptByName('test1.sh');
        $script2 = $this->scriptMapper->getScriptByName('test2.sh');
        
        // Get data
        $data1S1 = $this->getScriptData($script1);
        $data1S2 = $this->getScriptData($script2);
        
        // Activate script definition
        $this->scriptMapper->enableScript($script1->getId());
        $this->scriptMapper->enableScript($script2->getId());
        
        // Set scripts trigger bits
        $cmds = array();
        array_push($cmds, $this->parser->setBitCMD($script1->getTag()->getName()));
        array_push($cmds, $this->parser->setBitCMD($script2->getTag()->getName()));
        $this->parser->executeMultiCMD($cmds);
        
        // Wait on script run flags  (run, lock, feedback)
        $this->waitOnScriptFlags($script1->getId());
        $this->waitOnScriptFlags($script2->getId());
        
        // Get data
        $data2S1 = $this->getScriptData($script1);
        $data2S2 = $this->getScriptData($script2);
        
        // Wait on script finish flags (run, lock, feedback)
        $this->waitOnScriptFlags($script1->getId(), false, true, false, 7000);
        $this->waitOnScriptFlags($script2->getId(), false, true, false, 7000);
        
        // Get data
        $data3S1 = $this->getScriptData($script1);
        $data3S2 = $this->getScriptData($script2);
        
        // Reset script trigger bit
        $cmds1 = array();
        array_push($cmds1, $this->parser->resetBitCMD($script1->getTag()->getName()));
        array_push($cmds1, $this->parser->resetBitCMD($script2->getTag()->getName()));
        $this->parser->executeMultiCMD($cmds1);
        
        // Wait on script run flags  (run, lock, feedback)
        $this->waitOnScriptFlags($script1->getId(), false, false, false);
        $this->waitOnScriptFlags($script2->getId(), false, false, false);
        
        // Get data
        $data4S1 = $this->getScriptData($script1);
        $data4S2 = $this->getScriptData($script2);
        
        // Deactivate script definition
        $this->scriptMapper->enableScript($script1->getId(), false);
        $this->scriptMapper->enableScript($script2->getId(), false);
        
        // Check execution log
        $logPath = $this->cfg->getServerAppPath() . 'logs/scriptOutput/';
        $files = scandir($logPath);
        
        $this->assertEquals('TEST_SCRIPT1', $script1->getTag()->getName());
        $this->assertNull($script1->getFeedbackRun());
        
        $this->assertEquals('TEST_SCRIPT2', $script2->getTag()->getName());
        $this->assertNull($script2->getFeedbackRun());
        
        $this->assertFalse($data1S1['scriptDef']->isRunning());
        $this->assertFalse($data1S1['scriptDef']->isLocked());
        $this->assertNull($data1S1['feedbackRun']);
        
        $this->assertFalse($data1S2['scriptDef']->isRunning());
        $this->assertFalse($data1S2['scriptDef']->isLocked());
        $this->assertNull($data1S2['feedbackRun']);
        
        $this->assertTrue($data2S1['scriptDef']->isRunning());
        $this->assertTrue($data2S1['scriptDef']->isLocked());
        $this->assertNull($data2S1['feedbackRun']);
        
        $this->assertTrue($data2S2['scriptDef']->isRunning());
        $this->assertTrue($data2S2['scriptDef']->isLocked());
        $this->assertNull($data2S2['feedbackRun']);
        
        $this->assertFalse($data3S1['scriptDef']->isRunning());
        $this->assertTrue($data3S1['scriptDef']->isLocked());
        $this->assertNull($data3S1['feedbackRun']);
        
        $this->assertFalse($data3S2['scriptDef']->isRunning());
        $this->assertTrue($data3S2['scriptDef']->isLocked());
        $this->assertNull($data3S2['feedbackRun']);
        
        $this->assertFalse($data4S1['scriptDef']->isRunning());
        $this->assertFalse($data4S1['scriptDef']->isLocked());
        $this->assertNull($data4S1['feedbackRun']);
        
        $this->assertFalse($data4S2['scriptDef']->isRunning());
        $this->assertFalse($data4S2['scriptDef']->isLocked());
        $this->assertNull($data4S2['feedbackRun']);
        
        $this->assertEquals(7, count($files));
        $this->assertEquals('.', $files[0]);
        $this->assertEquals('..', $files[1]);
    }
    
    /**
     * Test script - Running too many scripts at once
     */
    public function testScript5()
    {
        // Get script definition
        $script1 = $this->scriptMapper->getScriptByName('test1.sh');
        $script2 = $this->scriptMapper->getScriptByName('test2.sh');
        $script3 = $this->scriptMapper->getScriptByName('test3.sh');
        $script4 = $this->scriptMapper->getScriptByName('test4.sh');
        $script5 = $this->scriptMapper->getScriptByName('test5.sh');
        $script6 = $this->scriptMapper->getScriptByName('test6.sh');
        
        // Get data
        $data1 = $this->getScriptData($script6);
        
        // Activate script definition
        $this->scriptMapper->enableScript($script1->getId());
        $this->scriptMapper->enableScript($script2->getId());
        $this->scriptMapper->enableScript($script3->getId());
        $this->scriptMapper->enableScript($script4->getId());
        $this->scriptMapper->enableScript($script5->getId());
        $this->scriptMapper->enableScript($script6->getId());
        
        // Set 5x scripts trigger bits
        $cmds = array();
        array_push($cmds, $this->parser->setBitCMD($script1->getTag()->getName()));
        array_push($cmds, $this->parser->setBitCMD($script2->getTag()->getName()));
        array_push($cmds, $this->parser->setBitCMD($script3->getTag()->getName()));
        array_push($cmds, $this->parser->setBitCMD($script4->getTag()->getName()));
        array_push($cmds, $this->parser->setBitCMD($script5->getTag()->getName()));
        $this->parser->executeMultiCMD($cmds);
        
        // Set script trigger bit
        $this->parser->setBit($script6->getTag()->getName());
        
        // Get data
        $data2 = $this->getScriptData($script6);
        
        // Wait on script flags (run, lock, feedback)
        $this->waitOnScriptFlags($script6->getId(), true, true, false, 20000);
        
        // Get data
        $data3 = $this->getScriptData($script6);
        
        // Reset script trigger bit
        $this->parser->resetBit($script6->getTag()->getName());
        
        // Wait on script run flags  (run, lock, feedback)
        $this->waitOnScriptFlags($script6->getId(), false, false, false, 5000);
        
        // Get data
        $data4 = $this->getScriptData($script6);
        
        // Wait on scripts finish flags (run, lock, feedback)
        $this->waitOnScriptFlags($script1->getId(), false, true, false, 10000);
        $this->waitOnScriptFlags($script2->getId(), false, true, false, 10000);
        $this->waitOnScriptFlags($script3->getId(), false, true, false, 10000);
        $this->waitOnScriptFlags($script4->getId(), false, true, false, 10000);
        $this->waitOnScriptFlags($script5->getId(), false, true, false, 10000);
        
        // Reset 5x scripts trigger bits
        $cmds2 = array();
        array_push($cmds2, $this->parser->resetBitCMD($script1->getTag()->getName()));
        array_push($cmds2, $this->parser->resetBitCMD($script2->getTag()->getName()));
        array_push($cmds2, $this->parser->resetBitCMD($script3->getTag()->getName()));
        array_push($cmds2, $this->parser->resetBitCMD($script4->getTag()->getName()));
        array_push($cmds2, $this->parser->resetBitCMD($script5->getTag()->getName()));
        $this->parser->executeMultiCMD($cmds2);
        
        // Wait on scripts finish flags (run, lock, feedback)
        $this->waitOnScriptFlags($script1->getId(), false, false, false, 10000);
        $this->waitOnScriptFlags($script2->getId(), false, false, false, 10000);
        $this->waitOnScriptFlags($script3->getId(), false, false, false, 10000);
        $this->waitOnScriptFlags($script4->getId(), false, false, false, 10000);
        $this->waitOnScriptFlags($script5->getId(), false, false, false, 10000);
        
        // Deactivate script definition
        $this->scriptMapper->enableScript($script1->getId(), false);
        $this->scriptMapper->enableScript($script2->getId(), false);
        $this->scriptMapper->enableScript($script3->getId(), false);
        $this->scriptMapper->enableScript($script4->getId(), false);
        $this->scriptMapper->enableScript($script5->getId(), false);
        $this->scriptMapper->enableScript($script6->getId(), false);
        
        // Check execution log
        $logPath = $this->cfg->getServerAppPath() . 'logs/scriptOutput/';
        $files = scandir($logPath);
        $log = file_get_contents($logPath . $files[12]);
        
        $logPath2 = $this->cfg->getServerAppPath() . 'logs/script/';
        $files2 = scandir($logPath2);
        $log2 = file_get_contents($logPath2 . $files2[2]);
        
        $this->assertEquals('TEST_SCRIPT6', $script6->getTag()->getName());
        
        $this->assertFalse($data1['scriptDef']->isRunning());
        $this->assertFalse($data1['scriptDef']->isLocked());
        $this->assertNull($data1['feedbackRun']);
        
        $this->assertFalse($data2['scriptDef']->isRunning());
        $this->assertFalse($data2['scriptDef']->isLocked());
        $this->assertNull($data2['feedbackRun']);
        
        $this->assertTrue($data3['scriptDef']->isRunning());
        $this->assertTrue($data3['scriptDef']->isLocked());
        $this->assertNull($data3['feedbackRun']);
        
        $this->assertFalse($data4['scriptDef']->isRunning());
        $this->assertFalse($data4['scriptDef']->isLocked());
        $this->assertNull($data4['feedbackRun']);
        
        $this->assertEquals(13, count($files));
        $this->assertEquals('.', $files[0]);
        $this->assertEquals('..', $files[1]);
        $this->assertTrue(strpos($log, $script6->getName()) !== false);
        
        $this->assertEquals(3, count($files2));
        $this->assertEquals('.', $files2[0]);
        $this->assertEquals('..', $files2[1]);
        $this->assertTrue(strpos($log2, "No empty runners for script") !== false);
        $this->assertTrue(strpos($log2, $script6->getName()) !== false);
    }
}
