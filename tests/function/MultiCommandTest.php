<?php

namespace App\Tests\Func;

use App\Tests\BaseFunctionTestCase;
use App\Service\Admin\Parser\ParserException;
use App\Service\Admin\Parser\ParserReplyCodes;
use App\Service\Admin\Parser\ParserCommands;

/**
 * Function tests for controller multi command tests
 *
 * @author Mateusz Mirosławski
 */
class MultiCommandTest extends BaseFunctionTestCase {
    
    /**
     * Test Multifunction command
     */
    public function testMulti1() {
        
        // Check process dword
        $this->assertEquals(0, $this->parser->getDWord('TEST_DWORD10'));
        
        // Prepare commands
        $cmds = array();
        array_push($cmds, $this->parser->writeByteCMD('TEST_BYTE10', 244));
        array_push($cmds, $this->parser->writeByteCMD('TEST_BYTE11', 117));
        array_push($cmds, $this->parser->writeWordCMD('TEST_WORD12', 36879));
        
        $pret = $this->parser->executeMultiCMD($cmds);
        $this->assertEquals(3, count($pret));
        $this->assertEquals(ParserCommands::WRITE_BYTE, $pret[0]['cmd']);
        $this->assertEquals(ParserReplyCodes::OK, $pret[0]['value']);
        $this->assertEquals(ParserCommands::WRITE_BYTE, $pret[1]['cmd']);
        $this->assertEquals(ParserReplyCodes::OK, $pret[1]['value']);
        $this->assertEquals(ParserCommands::WRITE_WORD, $pret[2]['cmd']);
        $this->assertEquals(ParserReplyCodes::OK, $pret[2]['value']);
        
        $this->waitOnProcessDataSync();
        
        $this->assertEquals(244, $this->parser->getByte('TEST_BYTE10'));
        $this->assertEquals(117, $this->parser->getByte('TEST_BYTE11'));
        $this->assertEquals(36879, $this->parser->getWord('TEST_WORD12'));
    }
    
    public function testMulti2() {
        
        // Check process dword
        $this->assertEquals(0, $this->parser->getDWord('TEST_DWORD10'));
        
        $pret1 = $this->parser->writeWord('TEST_WORD12', 27900);
        $this->assertEquals(ParserReplyCodes::OK, $pret1);
        
        $this->waitOnProcessDataSync();
        
        // Prepare commands
        $cmds = array();
        array_push($cmds, $this->parser->writeByteCMD('TEST_BYTE10', 74));
        array_push($cmds, $this->parser->writeByteCMD('TEST_BYTE11', 96));
        array_push($cmds, $this->parser->getWordCMD('TEST_WORD12'));
        
        $pret2 = $this->parser->executeMultiCMD($cmds);
        $this->assertEquals(3, count($pret2));
        $this->assertEquals(ParserCommands::WRITE_BYTE, $pret2[0]['cmd']);
        $this->assertEquals(ParserReplyCodes::OK, $pret2[0]['value']);
        $this->assertEquals(ParserCommands::WRITE_BYTE, $pret2[1]['cmd']);
        $this->assertEquals(ParserReplyCodes::OK, $pret2[1]['value']);
        $this->assertEquals(ParserCommands::GET_WORD, $pret2[2]['cmd']);
        $this->assertEquals(27900, $pret2[2]['value']);
        
        $this->waitOnProcessDataSync();
        
        $this->assertEquals(74, $this->parser->getByte('TEST_BYTE10'));
        $this->assertEquals(96, $this->parser->getByte('TEST_BYTE11'));
        $this->assertEquals(27900, $this->parser->getWord('TEST_WORD12'));
    }
    
    public function testMulti3() {
        
        // Check process dword
        $this->assertEquals(0, $this->parser->getDWord('TEST_DWORD10'));
        
        $pret1 = $this->parser->writeByte('TEST_BYTE10', 164);
        $this->assertEquals(ParserReplyCodes::OK, $pret1);
        
        $this->waitOnProcessDataSync();
        
        // Prepare commands
        $cmds = array();
        array_push($cmds, $this->parser->setBitsCMD(array('TEST_BIT0', 'TEST_BIT1', 'TEST_BIT3')));
        array_push($cmds, $this->parser->writeByteCMD('TEST_BYTE11', 117));
        array_push($cmds, $this->parser->writeWordCMD('TEST_WORD12', 36879));
        
        $pret = $this->parser->executeMultiCMD($cmds);
        $this->assertEquals(3, count($pret));
        $this->assertEquals(ParserCommands::SET_BITS, $pret[0]['cmd']);
        $this->assertEquals(ParserReplyCodes::OK, $pret[0]['value']);
        $this->assertEquals(ParserCommands::WRITE_BYTE, $pret[1]['cmd']);
        $this->assertEquals(ParserReplyCodes::OK, $pret[1]['value']);
        $this->assertEquals(ParserCommands::WRITE_WORD, $pret[2]['cmd']);
        $this->assertEquals(ParserReplyCodes::OK, $pret[2]['value']);
        
        $this->waitOnProcessDataSync();
        
        $this->assertEquals(175, $this->parser->getByte('TEST_BYTE10'));
        $this->assertEquals(117, $this->parser->getByte('TEST_BYTE11'));
        $this->assertEquals(36879, $this->parser->getWord('TEST_WORD12'));
    }
    
    public function testMultiWrong1() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('ServerError: Tag does not exist!');
        
        // Prepare commands
        $cmds = array();
        array_push($cmds, $this->parser->writeByteCMD('TEST_BYTtE10', 244));
        array_push($cmds, $this->parser->writeByteCMD('TEST_BYTE11', 117));
        array_push($cmds, $this->parser->writeWordCMD('TEST_WORD12', 36879));
        
        $this->parser->executeMultiCMD($cmds);
    }
    
    public function testMultiWrong2() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('ServerError: Tag has wrong type!');
        
        // Prepare commands
        $cmds = array();
        array_push($cmds, $this->parser->writeByteCMD('TEST_BYTE10', 244));
        array_push($cmds, $this->parser->writeWordCMD('TEST_BYTE11', 117));
        array_push($cmds, $this->parser->writeWordCMD('TEST_WORD12', 36879));
        
        $this->parser->executeMultiCMD($cmds);
    }
}
