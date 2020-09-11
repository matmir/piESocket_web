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
        $this->assertEquals(0, $this->parser->getDWord('MB_TEST_DWORD40'));
        
        // Prepare commands
        $cmds = array();
        array_push($cmds, $this->parser->writeByteCMD('TEST_BYTE10', 244));
        array_push($cmds, $this->parser->writeByteCMD('TEST_BYTE11', 117));
        array_push($cmds, $this->parser->writeWordCMD('TEST_WORD12', 36879));
        
        array_push($cmds, $this->parser->writeByteCMD('MB_TEST_BYTE40', 45));
        array_push($cmds, $this->parser->writeByteCMD('MB_TEST_BYTE41', 107));
        array_push($cmds, $this->parser->writeWordCMD('MB_TEST_WORD42', 10896));
        
        $pret = $this->parser->executeMultiCMD($cmds);
        $this->assertEquals(6, count($pret));
        $this->assertEquals(ParserCommands::WRITE_BYTE, $pret[0]['cmd']);
        $this->assertEquals(ParserReplyCodes::OK, $pret[0]['value']);
        $this->assertEquals(ParserCommands::WRITE_BYTE, $pret[1]['cmd']);
        $this->assertEquals(ParserReplyCodes::OK, $pret[1]['value']);
        $this->assertEquals(ParserCommands::WRITE_WORD, $pret[2]['cmd']);
        $this->assertEquals(ParserReplyCodes::OK, $pret[2]['value']);
        
        $this->assertEquals(ParserCommands::WRITE_BYTE, $pret[3]['cmd']);
        $this->assertEquals(ParserReplyCodes::OK, $pret[3]['value']);
        $this->assertEquals(ParserCommands::WRITE_BYTE, $pret[4]['cmd']);
        $this->assertEquals(ParserReplyCodes::OK, $pret[4]['value']);
        $this->assertEquals(ParserCommands::WRITE_WORD, $pret[5]['cmd']);
        $this->assertEquals(ParserReplyCodes::OK, $pret[5]['value']);
        
        $this->waitOnProcessDataSync(false);
        
        $this->assertEquals(244, $this->parser->getByte('TEST_BYTE10'));
        $this->assertEquals(117, $this->parser->getByte('TEST_BYTE11'));
        $this->assertEquals(36879, $this->parser->getWord('TEST_WORD12'));
        
        $this->assertEquals(45, $this->parser->getByte('MB_TEST_BYTE40'));
        $this->assertEquals(107, $this->parser->getByte('MB_TEST_BYTE41'));
        $this->assertEquals(10896, $this->parser->getWord('MB_TEST_WORD42'));
    }
    
    public function testMulti2() {
        
        // Check process dword
        $this->assertEquals(0, $this->parser->getDWord('TEST_DWORD10'));
        $this->assertEquals(0, $this->parser->getDWord('MB_TEST_DWORD40'));
        
        $pret_shm1 = $this->parser->writeWord('TEST_WORD12', 27900);
        $pret_mb1 = $this->parser->writeWord('MB_TEST_WORD42', 10333);
        
        $this->assertEquals(ParserReplyCodes::OK, $pret_shm1);
        $this->assertEquals(ParserReplyCodes::OK, $pret_mb1);
        
        $this->waitOnProcessDataSync(false);
        
        // Prepare commands
        $cmds = array();
        array_push($cmds, $this->parser->writeByteCMD('TEST_BYTE10', 74));
        array_push($cmds, $this->parser->writeByteCMD('TEST_BYTE11', 96));
        array_push($cmds, $this->parser->getWordCMD('TEST_WORD12'));
        
        array_push($cmds, $this->parser->writeByteCMD('MB_TEST_BYTE40', 12));
        array_push($cmds, $this->parser->writeByteCMD('MB_TEST_BYTE41', 13));
        array_push($cmds, $this->parser->getWordCMD('MB_TEST_WORD42'));
        
        $pret2 = $this->parser->executeMultiCMD($cmds);
        $this->assertEquals(6, count($pret2));
        $this->assertEquals(ParserCommands::WRITE_BYTE, $pret2[0]['cmd']);
        $this->assertEquals(ParserReplyCodes::OK, $pret2[0]['value']);
        $this->assertEquals(ParserCommands::WRITE_BYTE, $pret2[1]['cmd']);
        $this->assertEquals(ParserReplyCodes::OK, $pret2[1]['value']);
        $this->assertEquals(ParserCommands::GET_WORD, $pret2[2]['cmd']);
        $this->assertEquals(27900, $pret2[2]['value']);
        
        $this->assertEquals(ParserCommands::WRITE_BYTE, $pret2[3]['cmd']);
        $this->assertEquals(ParserReplyCodes::OK, $pret2[3]['value']);
        $this->assertEquals(ParserCommands::WRITE_BYTE, $pret2[4]['cmd']);
        $this->assertEquals(ParserReplyCodes::OK, $pret2[4]['value']);
        $this->assertEquals(ParserCommands::GET_WORD, $pret2[5]['cmd']);
        $this->assertEquals(10333, $pret2[5]['value']);
        
        $this->waitOnProcessDataSync(false);
        
        $this->assertEquals(74, $this->parser->getByte('TEST_BYTE10'));
        $this->assertEquals(96, $this->parser->getByte('TEST_BYTE11'));
        $this->assertEquals(27900, $this->parser->getWord('TEST_WORD12'));
        
        $this->assertEquals(12, $this->parser->getByte('MB_TEST_BYTE40'));
        $this->assertEquals(13, $this->parser->getByte('MB_TEST_BYTE41'));
        $this->assertEquals(10333, $this->parser->getWord('MB_TEST_WORD42'));
    }
    
    public function testMulti3() {
        
        // Check process dword
        $this->assertEquals(0, $this->parser->getDWord('TEST_DWORD10'));
        $this->assertEquals(0, $this->parser->getDWord('MB_TEST_DWORD40'));
        
        $pret1 = $this->parser->writeByte('TEST_BYTE10', 164);
        $pret2 = $this->parser->writeByte('MB_TEST_BYTE40', 204);
        
        $this->assertEquals(ParserReplyCodes::OK, $pret1);
        $this->assertEquals(ParserReplyCodes::OK, $pret2);
        
        $this->waitOnProcessDataSync(false);
        
        // Prepare commands
        $cmds = array();
        array_push($cmds, $this->parser->setBitsCMD(array('TEST_BIT0', 'TEST_BIT1', 'TEST_BIT3')));
        array_push($cmds, $this->parser->writeByteCMD('TEST_BYTE11', 117));
        array_push($cmds, $this->parser->writeWordCMD('TEST_WORD12', 36879));
        
        array_push($cmds, $this->parser->setBitsCMD(array('MB_TEST_BIT1', 'MB_TEST_BIT4', 'MB_TEST_BIT5')));
        array_push($cmds, $this->parser->writeByteCMD('MB_TEST_BYTE41', 102));
        array_push($cmds, $this->parser->writeWordCMD('MB_TEST_WORD42', 50803));
        
        $pret = $this->parser->executeMultiCMD($cmds);
        $this->assertEquals(6, count($pret));
        $this->assertEquals(ParserCommands::SET_BITS, $pret[0]['cmd']);
        $this->assertEquals(ParserReplyCodes::OK, $pret[0]['value']);
        $this->assertEquals(ParserCommands::WRITE_BYTE, $pret[1]['cmd']);
        $this->assertEquals(ParserReplyCodes::OK, $pret[1]['value']);
        $this->assertEquals(ParserCommands::WRITE_WORD, $pret[2]['cmd']);
        $this->assertEquals(ParserReplyCodes::OK, $pret[2]['value']);
        
        $this->assertEquals(ParserCommands::SET_BITS, $pret[3]['cmd']);
        $this->assertEquals(ParserReplyCodes::OK, $pret[3]['value']);
        $this->assertEquals(ParserCommands::WRITE_BYTE, $pret[4]['cmd']);
        $this->assertEquals(ParserReplyCodes::OK, $pret[4]['value']);
        $this->assertEquals(ParserCommands::WRITE_WORD, $pret[5]['cmd']);
        $this->assertEquals(ParserReplyCodes::OK, $pret[5]['value']);
        
        $this->waitOnProcessDataSync(false);
        
        $this->assertEquals(175, $this->parser->getByte('TEST_BYTE10'));
        $this->assertEquals(117, $this->parser->getByte('TEST_BYTE11'));
        $this->assertEquals(36879, $this->parser->getWord('TEST_WORD12'));
        
        $this->assertEquals(254, $this->parser->getByte('MB_TEST_BYTE40'));
        $this->assertEquals(102, $this->parser->getByte('MB_TEST_BYTE41'));
        $this->assertEquals(50803, $this->parser->getWord('MB_TEST_WORD42'));
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
