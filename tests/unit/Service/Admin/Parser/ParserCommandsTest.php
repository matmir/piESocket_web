<?php

namespace App\Tests\Service\Admin\Parser;

use App\Service\Admin\Parser\ParserCommands;
use PHPUnit\Framework\TestCase;

/**
 * Unit test for ParserCommands class
 *
 * @author Mateusz Mirosławski
 */
class ParserCommandsTest extends TestCase
{
    /**
     * Test checkCMD function
     */
    public function testCheckCMD()
    {
        // GET_BIT
        $this->assertTrue(ParserCommands::checkCMD(ParserCommands::GET_BIT));
        
        // SET_BIT
        $this->assertTrue(ParserCommands::checkCMD(ParserCommands::SET_BIT));
        
        // RESET_BIT
        $this->assertTrue(ParserCommands::checkCMD(ParserCommands::RESET_BIT));
        
        // INVERT_BIT
        $this->assertTrue(ParserCommands::checkCMD(ParserCommands::INVERT_BIT));
        
        // GET_BITS
        $this->assertTrue(ParserCommands::checkCMD(ParserCommands::GET_BITS));
        
        // SET_BITS
        $this->assertTrue(ParserCommands::checkCMD(ParserCommands::SET_BITS));
        
        // GET_BYTE
        $this->assertTrue(ParserCommands::checkCMD(ParserCommands::GET_BYTE));
        
        // WRITE_BYTE
        $this->assertTrue(ParserCommands::checkCMD(ParserCommands::WRITE_BYTE));
        
        // GET_WORD
        $this->assertTrue(ParserCommands::checkCMD(ParserCommands::GET_WORD));
        
        // WRITE_WORD
        $this->assertTrue(ParserCommands::checkCMD(ParserCommands::WRITE_WORD));
        
        // GET_DWORD
        $this->assertTrue(ParserCommands::checkCMD(ParserCommands::GET_DWORD));
        
        // WRITE_DWORD
        $this->assertTrue(ParserCommands::checkCMD(ParserCommands::WRITE_DWORD));
        
        // GET_INT
        $this->assertTrue(ParserCommands::checkCMD(ParserCommands::GET_INT));
        
        // WRITE_INT
        $this->assertTrue(ParserCommands::checkCMD(ParserCommands::WRITE_INT));
        
        // GET_REAL
        $this->assertTrue(ParserCommands::checkCMD(ParserCommands::GET_REAL));
        
        // WRITE_REAL
        $this->assertTrue(ParserCommands::checkCMD(ParserCommands::WRITE_REAL));
        
        // MULTI_CMD
        $this->assertTrue(ParserCommands::checkCMD(ParserCommands::MULTI_CMD));
        
        // ACK_ALARM
        $this->assertTrue(ParserCommands::checkCMD(ParserCommands::ACK_ALARM));
        
        // EXIT_APP
        $this->assertTrue(ParserCommands::checkCMD(ParserCommands::EXIT_APP));
        
        // Unknown CMD
        $this->assertFalse(ParserCommands::checkCMD(2085));
    }
}
