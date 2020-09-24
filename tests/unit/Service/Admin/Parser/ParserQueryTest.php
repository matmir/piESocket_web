<?php

namespace App\Tests\Service\Admin\Parser;

use App\Service\Admin\Parser\ParserCommands;
use App\Service\Admin\Parser\ParserQuery;
use App\Service\Admin\Parser\ParserException;
use PHPUnit\Framework\TestCase;

/**
 * Unit test for ParserQuery class
 *
 * @author Mateusz Mirosławski
 */
class ParserQueryTest extends TestCase
{
    /**
     * Test query method
     */
    public function testQuerryErr1()
    {
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('query: Missing command field in array!');
        
        // Prepare command
        $cmd = array(
            'cmdd' => ParserCommands::GET_BIT,
            'tag' => 'TestTag'
        );
        
        // Prepare query
        $query = new ParserQuery();
        $query->query($cmd);
    }
    
    public function testQuerryErr2()
    {
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('query: Wrong command number!');
        
        // Prepare command
        $cmd = array(
            'cmd' => 2088,
            'tag' => 'TestTag'
        );
        
        // Prepare query
        $query = new ParserQuery();
        $query->query($cmd);
    }
    
    /**
     * Test query method for GET_BIT function
     */
    public function testQueryGetBit()
    {
        // Prepare command
        $cmd = array(
            'cmd' => ParserCommands::GET_BIT,
            'tag' => 'TestTag'
        );
        
        // Prepare query
        $query = new ParserQuery();
        $str = $query->query($cmd);
        
        // Access rights
        $ta = $query->getAccessRights();
        
        $this->assertEquals('10|TestTag', $str);
        $this->assertEquals(1, count($ta));
        $this->assertEquals('TestTag', $ta[0]['tagName']);
        $this->assertTrue($ta[0]['read']);
    }
    
    public function testQueryGetBitErr1()
    {
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('CMD_GET_BIT: Missing tag field in array!');
        
        // Prepare command
        $cmd = array(
            'cmd' => ParserCommands::GET_BIT,
            'tagb' => 'TestTag'
        );
        
        // Prepare query
        $query = new ParserQuery();
        $query->query($cmd);
    }
    
    public function testQueryGetBitErr2()
    {
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('parseTagName: Tag name is empty!');
        
        // Prepare command
        $cmd = array(
            'cmd' => ParserCommands::GET_BIT,
            'tag' => ''
        );
        
        // Prepare query
        $query = new ParserQuery();
        $query->query($cmd);
    }
    
    /**
     * Test query method for SET_BIT function
     */
    public function testQuerySetBit()
    {
        // Prepare command
        $cmd = array(
            'cmd' => ParserCommands::SET_BIT,
            'tag' => 'TestTag'
        );
        
        // Prepare query
        $query = new ParserQuery();
        $str = $query->query($cmd);
        
        // Access rights
        $ta = $query->getAccessRights();
        
        $this->assertEquals('11|TestTag', $str);
        $this->assertEquals(1, count($ta));
        $this->assertEquals('TestTag', $ta[0]['tagName']);
        $this->assertFalse($ta[0]['read']);
    }
    
    public function testQuerySetBitErr1()
    {
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('CMD_SET_BIT: Missing tag field in array!');
        
        // Prepare command
        $cmd = array(
            'cmd' => ParserCommands::SET_BIT,
            'tagg' => 'TestTag'
        );
        
        // Prepare query
        $query = new ParserQuery();
        $query->query($cmd);
    }
    
    /**
     * Test query method for RESET_BIT function
     */
    public function testQueryResetBit()
    {
        // Prepare command
        $cmd = array(
            'cmd' => ParserCommands::RESET_BIT,
            'tag' => 'TestTag'
        );
        
        // Prepare query
        $query = new ParserQuery();
        $str = $query->query($cmd);
        
        // Access rights
        $ta = $query->getAccessRights();
        
        $this->assertEquals('12|TestTag', $str);
        $this->assertEquals(1, count($ta));
        $this->assertEquals('TestTag', $ta[0]['tagName']);
        $this->assertFalse($ta[0]['read']);
    }
    
    public function testQueryResetBitErr1()
    {
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('CMD_RESET_BIT: Missing tag field in array!');
        
        // Prepare command
        $cmd = array(
            'cmd' => ParserCommands::RESET_BIT,
            'tagg' => 'TestTag'
        );
        
        // Prepare query
        $query = new ParserQuery();
        $query->query($cmd);
    }
    
    /**
     * Test query method for INVERT_BIT function
     */
    public function testQueryInvertBit()
    {
        // Prepare command
        $cmd = array(
            'cmd' => ParserCommands::INVERT_BIT,
            'tag' => 'TestTag'
        );
        
        // Prepare query
        $query = new ParserQuery();
        $str = $query->query($cmd);
        
        // Access rights
        $ta = $query->getAccessRights();
        
        $this->assertEquals('13|TestTag', $str);
        $this->assertEquals(1, count($ta));
        $this->assertEquals('TestTag', $ta[0]['tagName']);
        $this->assertFalse($ta[0]['read']);
    }
    
    public function testQueryInvertBitErr1()
    {
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('CMD_INVERT_BIT: Missing tag field in array!');
        
        // Prepare command
        $cmd = array(
            'cmd' => ParserCommands::INVERT_BIT,
            'tagg' => 'TestTag'
        );
        
        // Prepare query
        $query = new ParserQuery();
        $query->query($cmd);
    }
    
    /**
     * Test query method for GET_BITS function
     */
    public function testQueryGetBits()
    {
        // Prepare command
        $cmd = array(
            'cmd' => ParserCommands::GET_BITS,
            'tags' => ['TestTag1', 'TestTag2', 'TestTag3']
        );
        
        // Prepare query
        $query = new ParserQuery();
        $str = $query->query($cmd);
        
        // Access rights
        $ta = $query->getAccessRights();
        
        $this->assertEquals('20|TestTag1,TestTag2,TestTag3', $str);
        $this->assertEquals(3, count($ta));
        for ($i = 0; $i < count($ta); ++$i) {
            $this->assertEquals($cmd['tags'][$i], $ta[$i]['tagName']);
            $this->assertTrue($ta[$i]['read']);
        }
    }
    
    public function testQueryGetBitsErr1()
    {
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('CMD_GET_BITS: Missing tags field in array!');
        
        // Prepare command
        $cmd = array(
            'cmd' => ParserCommands::GET_BITS,
            'tagss' => ['TestTag1', 'TestTag2', 'TestTag3']
        );
        
        // Prepare query
        $query = new ParserQuery();
        $query->query($cmd);
    }
    
    public function testQueryGetBitsErr2()
    {
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('CMD_GET_BITS: Tags field is not array!');
        
        // Prepare command
        $cmd = array(
            'cmd' => ParserCommands::GET_BITS,
            'tags' => 'TestTag1'
        );
        
        // Prepare query
        $query = new ParserQuery();
        $query->query($cmd);
    }
    
    /**
     * Test query method for SET_BITS function
     */
    public function testQuerySetBits()
    {
        // Prepare command
        $cmd = array(
            'cmd' => ParserCommands::SET_BITS,
            'tags' => ['TestTag1', 'TestTag2', 'TestTag3']
        );
        
        // Prepare query
        $query = new ParserQuery();
        $str = $query->query($cmd);
        
        // Access rights
        $ta = $query->getAccessRights();
        
        $this->assertEquals('21|TestTag1,TestTag2,TestTag3', $str);
        $this->assertEquals(3, count($ta));
        for ($i = 0; $i < count($ta); ++$i) {
            $this->assertEquals($cmd['tags'][$i], $ta[$i]['tagName']);
            $this->assertFalse($ta[$i]['read']);
        }
    }
    
    public function testQuerySetBitsErr1()
    {
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('CMD_SET_BITS: Missing tags field in array!');
        
        // Prepare command
        $cmd = array(
            'cmd' => ParserCommands::SET_BITS,
            'tagss' => ['TestTag1', 'TestTag2', 'TestTag3']
        );
        
        // Prepare query
        $query = new ParserQuery();
        $query->query($cmd);
    }
    
    public function testQuerySetBitsErr2()
    {
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('CMD_SET_BITS: Tags field is not array!');
        
        // Prepare command
        $cmd = array(
            'cmd' => ParserCommands::SET_BITS,
            'tags' => 'TestTag1'
        );
        
        // Prepare query
        $query = new ParserQuery();
        $query->query($cmd);
    }
    
    /**
     * Test query method for GET_BYTE function
     */
    public function testQueryGetByte()
    {
        // Prepare command
        $cmd = array(
            'cmd' => ParserCommands::GET_BYTE,
            'tag' => 'TestB1'
        );
        
        // Prepare query
        $query = new ParserQuery();
        $str = $query->query($cmd);
        
        // Access rights
        $ta = $query->getAccessRights();
        
        $this->assertEquals('30|TestB1', $str);
        $this->assertEquals(1, count($ta));
        $this->assertEquals('TestB1', $ta[0]['tagName']);
        $this->assertTrue($ta[0]['read']);
    }
    
    public function testQueryGetByteErr1()
    {
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('CMD_GET_BYTE: Missing tag field in array!');
        
        // Prepare command
        $cmd = array(
            'cmd' => ParserCommands::GET_BYTE,
            'tagg' => 'TestB1'
        );
        
        // Prepare query
        $query = new ParserQuery();
        $query->query($cmd);
    }
    
    /**
     * Test query method for WRITE_BYTE function
     */
    public function testQueryWriteByte()
    {
        // Prepare command
        $cmd = array(
            'cmd' => ParserCommands::WRITE_BYTE,
            'tag' => 'TestB1',
            'value' => 25
        );
        
        // Prepare query
        $query = new ParserQuery();
        $str = $query->query($cmd);
        
        // Access rights
        $ta = $query->getAccessRights();
        
        $this->assertEquals('31|TestB1,25', $str);
        $this->assertEquals(1, count($ta));
        $this->assertEquals('TestB1', $ta[0]['tagName']);
        $this->assertFalse($ta[0]['read']);
    }
    
    public function testQueryWriteByteErr1()
    {
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('CMD_WRITE_BYTE: Missing tag field in array!');
        
        // Prepare command
        $cmd = array(
            'cmd' => ParserCommands::WRITE_BYTE,
            'tagg' => 'TestB1',
            'value' => 25
        );
        
        // Prepare query
        $query = new ParserQuery();
        $query->query($cmd);
    }
    
    public function testQueryWriteByteErr2()
    {
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('CMD_WRITE_BYTE: Missing value field in array!');
        
        // Prepare command
        $cmd = array(
            'cmd' => ParserCommands::WRITE_BYTE,
            'tag' => 'TestB1',
            'valuee' => 25
        );
        
        // Prepare query
        $query = new ParserQuery();
        $query->query($cmd);
    }
    
    public function testQueryWriteByteErr3()
    {
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('CMD_WRITE_BYTE: Value need to be numeric!');
        
        // Prepare command
        $cmd = array(
            'cmd' => ParserCommands::WRITE_BYTE,
            'tag' => 'TestB1',
            'value' => '25ee'
        );
        
        // Prepare query
        $query = new ParserQuery();
        $query->query($cmd);
    }
    
    public function testQueryWriteByteErr4()
    {
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('CMD_WRITE_BYTE: Value is out of range!');
        
        // Prepare command
        $cmd = array(
            'cmd' => ParserCommands::WRITE_BYTE,
            'tag' => 'TestB1',
            'value' => 300
        );
        
        // Prepare query
        $query = new ParserQuery();
        $query->query($cmd);
    }
    
    /**
     * Test query method for GET_WORD function
     */
    public function testQueryGetWord()
    {
        // Prepare command
        $cmd = array(
            'cmd' => ParserCommands::GET_WORD,
            'tag' => 'TestW1'
        );
        
        // Prepare query
        $query = new ParserQuery();
        $str = $query->query($cmd);
        
        // Access rights
        $ta = $query->getAccessRights();
        
        $this->assertEquals('32|TestW1', $str);
        $this->assertEquals(1, count($ta));
        $this->assertEquals('TestW1', $ta[0]['tagName']);
        $this->assertTrue($ta[0]['read']);
    }
    
    public function testQueryGetWordErr1()
    {
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('CMD_GET_WORD: Missing tag field in array!');
        
        // Prepare command
        $cmd = array(
            'cmd' => ParserCommands::GET_WORD,
            'tagg' => 'TestW1'
        );
        
        // Prepare query
        $query = new ParserQuery();
        $query->query($cmd);
    }
    
    /**
     * Test query method for WRITE_WORD function
     */
    public function testQueryWriteWord()
    {
        // Prepare command
        $cmd = array(
            'cmd' => ParserCommands::WRITE_WORD,
            'tag' => 'TestW1',
            'value' => 2500
        );
        
        // Prepare query
        $query = new ParserQuery();
        $str = $query->query($cmd);
        
        // Access rights
        $ta = $query->getAccessRights();
        
        $this->assertEquals('33|TestW1,2500', $str);
        $this->assertEquals(1, count($ta));
        $this->assertEquals('TestW1', $ta[0]['tagName']);
        $this->assertFalse($ta[0]['read']);
    }
    
    public function testQueryWriteWordErr1()
    {
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('CMD_WRITE_WORD: Missing tag field in array!');
        
        // Prepare command
        $cmd = array(
            'cmd' => ParserCommands::WRITE_WORD,
            'tagg' => 'TestW1',
            'value' => 2500
        );
        
        // Prepare query
        $query = new ParserQuery();
        $query->query($cmd);
    }
    
    public function testQueryWriteWordErr2()
    {
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('CMD_WRITE_WORD: Missing value field in array!');
        
        // Prepare command
        $cmd = array(
            'cmd' => ParserCommands::WRITE_WORD,
            'tag' => 'TestW1',
            'valuee' => 2500
        );
        
        // Prepare query
        $query = new ParserQuery();
        $query->query($cmd);
    }
    
    public function testQueryWriteWordErr3()
    {
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('CMD_WRITE_WORD: Value need to be numeric!');
        
        // Prepare command
        $cmd = array(
            'cmd' => ParserCommands::WRITE_WORD,
            'tag' => 'TestW1',
            'value' => '2500ee'
        );
        
        // Prepare query
        $query = new ParserQuery();
        $query->query($cmd);
    }
    
    public function testQueryWriteWordErr4()
    {
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('CMD_WRITE_WORD: Value is out of range!');
        
        // Prepare command
        $cmd = array(
            'cmd' => ParserCommands::WRITE_WORD,
            'tag' => 'TestW1',
            'value' => 65536
        );
        
        // Prepare query
        $query = new ParserQuery();
        $query->query($cmd);
    }
    
    /**
     * Test query method for GET_DWORD function
     */
    public function testQueryGetDWord()
    {
        // Prepare command
        $cmd = array(
            'cmd' => ParserCommands::GET_DWORD,
            'tag' => 'TestDW1'
        );
        
        // Prepare query
        $query = new ParserQuery();
        $str = $query->query($cmd);
        
        // Access rights
        $ta = $query->getAccessRights();
        
        $this->assertEquals('34|TestDW1', $str);
        $this->assertEquals(1, count($ta));
        $this->assertEquals('TestDW1', $ta[0]['tagName']);
        $this->assertTrue($ta[0]['read']);
    }
    
    public function testQueryGetDWordErr1()
    {
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('CMD_GET_DWORD: Missing tag field in array!');
        
        // Prepare command
        $cmd = array(
            'cmd' => ParserCommands::GET_DWORD,
            'tagg' => 'TestDW1'
        );
        
        // Prepare query
        $query = new ParserQuery();
        $query->query($cmd);
    }
    
    /**
     * Test query method for WRITE_DWORD function
     */
    public function testQueryWriteDWord()
    {
        // Prepare command
        $cmd = array(
            'cmd' => ParserCommands::WRITE_DWORD,
            'tag' => 'TestDW1',
            'value' => 2294967295
        );
        
        // Prepare query
        $query = new ParserQuery();
        $str = $query->query($cmd);
        
        // Access rights
        $ta = $query->getAccessRights();
        
        $this->assertEquals('35|TestDW1,2294967295', $str);
        $this->assertEquals(1, count($ta));
        $this->assertEquals('TestDW1', $ta[0]['tagName']);
        $this->assertFalse($ta[0]['read']);
    }
    
    public function testQueryWriteDWordErr1()
    {
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('CMD_WRITE_DWORD: Missing tag field in array!');
        
        // Prepare command
        $cmd = array(
            'cmd' => ParserCommands::WRITE_DWORD,
            'tagg' => 'TestDW1',
            'value' => 2294967295
        );
        
        // Prepare query
        $query = new ParserQuery();
        $query->query($cmd);
    }
    
    public function testQueryWriteDWordErr2()
    {
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('CMD_WRITE_DWORD: Missing value field in array!');
        
        // Prepare command
        $cmd = array(
            'cmd' => ParserCommands::WRITE_DWORD,
            'tag' => 'TestDW1',
            'valuee' => 2294967295
        );
        
        // Prepare query
        $query = new ParserQuery();
        $query->query($cmd);
    }
    
    public function testQueryWriteDWordErr3()
    {
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('CMD_WRITE_DWORD: Value need to be numeric!');
        
        // Prepare command
        $cmd = array(
            'cmd' => ParserCommands::WRITE_DWORD,
            'tag' => 'TestDW1',
            'value' => '2500ee'
        );
        
        // Prepare query
        $query = new ParserQuery();
        $query->query($cmd);
    }
    
    public function testQueryWriteDWordErr4()
    {
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('CMD_WRITE_DWORD: Value is out of range!');
        
        // Prepare command
        $cmd = array(
            'cmd' => ParserCommands::WRITE_DWORD,
            'tag' => 'TestDW1',
            'value' => -9
        );
        
        // Prepare query
        $query = new ParserQuery();
        $query->query($cmd);
    }
    
    /**
     * Test query method for GET_INT function
     */
    public function testQueryGetInt()
    {
        // Prepare command
        $cmd = array(
            'cmd' => ParserCommands::GET_INT,
            'tag' => 'TestINT1'
        );
        
        // Prepare query
        $query = new ParserQuery();
        $str = $query->query($cmd);
        
        // Access rights
        $ta = $query->getAccessRights();
        
        $this->assertEquals('36|TestINT1', $str);
        $this->assertEquals(1, count($ta));
        $this->assertEquals('TestINT1', $ta[0]['tagName']);
        $this->assertTrue($ta[0]['read']);
    }
    
    public function testQueryGetIntErr1()
    {
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('CMD_GET_INT: Missing tag field in array!');
        
        // Prepare command
        $cmd = array(
            'cmd' => ParserCommands::GET_INT,
            'tagg' => 'TestINT1'
        );
        
        // Prepare query
        $query = new ParserQuery();
        $query->query($cmd);
    }
    
    /**
     * Test query method for WRITE_INT function
     */
    public function testQueryWriteInt()
    {
        // Prepare command
        $cmd = array(
            'cmd' => ParserCommands::WRITE_INT,
            'tag' => 'TestINT1',
            'value' => -650
        );
        
        // Prepare query
        $query = new ParserQuery();
        $str = $query->query($cmd);
        
        // Access rights
        $ta = $query->getAccessRights();
        
        $this->assertEquals('37|TestINT1,-650', $str);
        $this->assertEquals(1, count($ta));
        $this->assertEquals('TestINT1', $ta[0]['tagName']);
        $this->assertFalse($ta[0]['read']);
    }
    
    public function testQueryWriteIntErr1()
    {
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('CMD_WRITE_INT: Missing tag field in array!');
        
        // Prepare command
        $cmd = array(
            'cmd' => ParserCommands::WRITE_INT,
            'tagg' => 'TestINT1',
            'value' => -650
        );
        
        // Prepare query
        $query = new ParserQuery();
        $query->query($cmd);
    }
    
    public function testQueryWriteIntErr2()
    {
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('CMD_WRITE_INT: Missing value field in array!');
        
        // Prepare command
        $cmd = array(
            'cmd' => ParserCommands::WRITE_INT,
            'tag' => 'TestINT1',
            'valuee' => -650
        );
        
        // Prepare query
        $query = new ParserQuery();
        $query->query($cmd);
    }
    
    public function testQueryWriteIntErr3()
    {
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('CMD_WRITE_INT: Value need to be numeric!');
        
        // Prepare command
        $cmd = array(
            'cmd' => ParserCommands::WRITE_INT,
            'tag' => 'TestINT1',
            'value' => '-650ee'
        );
        
        // Prepare query
        $query = new ParserQuery();
        $query->query($cmd);
    }
    
    public function testQueryWriteIntErr4()
    {
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('CMD_WRITE_INT: Value is out of range!');
        
        // Prepare command
        $cmd = array(
            'cmd' => ParserCommands::WRITE_INT,
            'tag' => 'TestINT1',
            'value' => -2147483649
        );
        
        // Prepare query
        $query = new ParserQuery();
        $query->query($cmd);
    }
    
    /**
     * Test query method for GET_REAL function
     */
    public function testQueryGetReal()
    {
        // Prepare command
        $cmd = array(
            'cmd' => ParserCommands::GET_REAL,
            'tag' => 'TestREAL1'
        );
        
        // Prepare query
        $query = new ParserQuery();
        $str = $query->query($cmd);
        
        // Access rights
        $ta = $query->getAccessRights();
        
        $this->assertEquals('38|TestREAL1', $str);
        $this->assertEquals(1, count($ta));
        $this->assertEquals('TestREAL1', $ta[0]['tagName']);
        $this->assertTrue($ta[0]['read']);
    }
    
    public function testQueryGetRealErr1()
    {
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('CMD_GET_REAL: Missing tag field in array!');
        
        // Prepare command
        $cmd = array(
            'cmd' => ParserCommands::GET_REAL,
            'tagg' => 'TestREAL1'
        );
        
        // Prepare query
        $query = new ParserQuery();
        $query->query($cmd);
    }
    
    /**
     * Test query method for WRITE_REAL function
     */
    public function testQueryWriteReal()
    {
        // Prepare command
        $cmd = array(
            'cmd' => ParserCommands::WRITE_REAL,
            'tag' => 'TestREAL1',
            'value' => -65.78
        );
        
        // Prepare query
        $query = new ParserQuery();
        $str = $query->query($cmd);
        
        // Access rights
        $ta = $query->getAccessRights();
        
        $this->assertEquals('39|TestREAL1,-65.78', $str);
        $this->assertEquals(1, count($ta));
        $this->assertEquals('TestREAL1', $ta[0]['tagName']);
        $this->assertFalse($ta[0]['read']);
    }
    
    public function testQueryWriteRealErr1()
    {
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('CMD_WRITE_REAL: Missing tag field in array!');
        
        // Prepare command
        $cmd = array(
            'cmd' => ParserCommands::WRITE_REAL,
            'tagg' => 'TestREAL1',
            'value' => -65.78
        );
        
        // Prepare query
        $query = new ParserQuery();
        $query->query($cmd);
    }
    
    public function testQueryWriteRealErr2()
    {
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('CMD_WRITE_REAL: Missing value field in array!');
        
        // Prepare command
        $cmd = array(
            'cmd' => ParserCommands::WRITE_REAL,
            'tag' => 'TestREAL1',
            'valuee' => -65.78
        );
        
        // Prepare query
        $query = new ParserQuery();
        $query->query($cmd);
    }
    
    public function testQueryWriteRealErr3()
    {
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('CMD_WRITE_REAL: Value need to be numeric!');
        
        // Prepare command
        $cmd = array(
            'cmd' => ParserCommands::WRITE_REAL,
            'tag' => 'TestREAL1',
            'value' => '-650ee'
        );
        
        // Prepare query
        $query = new ParserQuery();
        $query->query($cmd);
    }
    
    public function testQueryWriteRealErr4()
    {
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('CMD_WRITE_REAL: Value is out of range!');
        
        // Prepare command
        $cmd = array(
            'cmd' => ParserCommands::WRITE_REAL,
            'tag' => 'TestREAL1',
            'value' => '-6.8'
        );
        
        // Prepare query
        $query = new ParserQuery();
        $query->query($cmd);
    }
    
    /**
     * Test query method for ACK_ALARM function
     */
    public function testQueryAckAlarm1()
    {
        // Prepare command
        $cmd = array(
            'cmd' => ParserCommands::ACK_ALARM,
            'alarm_id' => 4
        );
        
        // Prepare query
        $query = new ParserQuery();
        $str = $query->query($cmd);
        
        // Access rights
        $ta = $query->getAccessRights();
        
        $this->assertEquals('90|4', $str);
        $this->assertEquals(1, count($ta));
        $this->assertEquals('CMD_ACK_ALARM', $ta[0]['specialFunc']);
        $this->assertEquals('ROLE_USER', $ta[0]['role']);
    }
    
    public function testQueryAckAlarm2()
    {
        // Prepare command
        $cmd = array(
            'cmd' => ParserCommands::ACK_ALARM,
            'alarm_id' => 4
        );
        
        // Prepare query
        $query = new ParserQuery();
        $query->setAckRights('ROLE_GUEST');
        $str = $query->query($cmd);
        
        // Access rights
        $ta = $query->getAccessRights();
        
        $this->assertEquals('90|4', $str);
        $this->assertEquals('ROLE_GUEST', $query->getAckRights());
        $this->assertEquals(1, count($ta));
        $this->assertEquals('CMD_ACK_ALARM', $ta[0]['specialFunc']);
        $this->assertEquals('ROLE_GUEST', $ta[0]['role']);
    }
    
    public function testQueryAckAlarmErr1()
    {
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('CMD_ACK_ALARM: Missing alarm_id field in array!');
        
        // Prepare command
        $cmd = array(
            'cmd' => ParserCommands::ACK_ALARM,
            'alarm_idd' => 4
        );
        
        // Prepare query
        $query = new ParserQuery();
        $query->query($cmd);
    }
    
    public function testQueryAckAlarmErr2()
    {
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('CMD_ACK_ALARM: alarm_id need to be numeric!');
        
        // Prepare command
        $cmd = array(
            'cmd' => ParserCommands::ACK_ALARM,
            'alarm_id' => '4rr'
        );
        
        // Prepare query
        $query = new ParserQuery();
        $query->query($cmd);
    }
    
    /**
     * Test query method for EXIT_APP function
     */
    public function testQueryExitApp()
    {
        // Prepare command
        $cmd = array(
            'cmd' => ParserCommands::EXIT_APP
        );
        
        // Prepare query
        $query = new ParserQuery();
        $str = $query->query($cmd);
        
        // Access rights
        $ta = $query->getAccessRights();
        
        $this->assertEquals('600|1', $str);
        $this->assertEquals(1, count($ta));
        $this->assertEquals('CMD_EXIT_APP', $ta[0]['specialFunc']);
        $this->assertEquals('ROLE_ADMIN', $ta[0]['role']);
    }
    
    /**
     * Test query method for GET_THREAD_CYCLE_TIME function
     */
    public function testQueryGetThreadCycleTime()
    {
        // Prepare command
        $cmd = array(
            'cmd' => ParserCommands::GET_THREAD_CYCLE_TIME
        );
        
        // Prepare query
        $query = new ParserQuery();
        $str = $query->query($cmd);
        
        // Access rights
        $ta = $query->getAccessRights();
        
        $this->assertEquals('500|1', $str);
        $this->assertEquals(1, count($ta));
        $this->assertEquals('CMD_GET_THREAD_CYCLE_TIME', $ta[0]['specialFunc']);
        $this->assertEquals('ROLE_ADMIN', $ta[0]['role']);
    }
    
    /**
     * Test query method for MULTI_CMD function
     */
    public function testQueryMultiCmd1()
    {
        // Prepare command
        $cmd = array(
            'cmd' => ParserCommands::MULTI_CMD,
            'value' => array(
                ['cmd' => ParserCommands::GET_INT, 'tag' => 'TagINT1'],
                ['cmd' => ParserCommands::GET_BIT, 'tag' => 'Tag1'],
                ['cmd' => ParserCommands::SET_BITS, 'tags' => ['Tag1', 'Tag2', 'Tag3']]
            )
        );
        
        // Prepare query
        $query = new ParserQuery();
        $str = $query->query($cmd);
        
        // Access rights
        $ta = $query->getAccessRights();
        
        $this->assertEquals('50|36?TagINT1!10?Tag1!21?Tag1,Tag2,Tag3', $str);
        
        $this->assertEquals(5, count($ta));
        $this->assertEquals('TagINT1', $ta[0]['tagName']);
        $this->assertTrue($ta[0]['read']);
        $this->assertEquals('Tag1', $ta[1]['tagName']);
        $this->assertTrue($ta[1]['read']);
        $this->assertEquals('Tag1', $ta[2]['tagName']);
        $this->assertFalse($ta[2]['read']);
        $this->assertEquals('Tag2', $ta[3]['tagName']);
        $this->assertFalse($ta[3]['read']);
        $this->assertEquals('Tag3', $ta[4]['tagName']);
        $this->assertFalse($ta[4]['read']);
    }
    
    public function testQueryMultiCmd2()
    {
        // Prepare command
        $cmd = array(
            'cmd' => ParserCommands::MULTI_CMD,
            'value' => array(
                ['cmd' => ParserCommands::SET_BIT, 'tag' => 'Tag1'],
                ['cmd' => ParserCommands::RESET_BIT, 'tag' => 'Tag2'],
                ['cmd' => ParserCommands::INVERT_BIT, 'tag' => 'Tag3'],
                ['cmd' => ParserCommands::GET_BITS, 'tags' => ['Tag4', 'Tag5', 'Tag6']]
            )
        );
        
        // Prepare query
        $query = new ParserQuery();
        $str = $query->query($cmd);
        
        // Access rights
        $ta = $query->getAccessRights();
        
        $this->assertEquals('50|11?Tag1!12?Tag2!13?Tag3!20?Tag4,Tag5,Tag6', $str);
        
        $this->assertEquals(6, count($ta));
        $this->assertEquals('Tag1', $ta[0]['tagName']);
        $this->assertFalse($ta[0]['read']);
        $this->assertEquals('Tag2', $ta[1]['tagName']);
        $this->assertFalse($ta[1]['read']);
        $this->assertEquals('Tag3', $ta[2]['tagName']);
        $this->assertFalse($ta[2]['read']);
        $this->assertEquals('Tag4', $ta[3]['tagName']);
        $this->assertTrue($ta[3]['read']);
        $this->assertEquals('Tag5', $ta[4]['tagName']);
        $this->assertTrue($ta[4]['read']);
        $this->assertEquals('Tag6', $ta[5]['tagName']);
        $this->assertTrue($ta[5]['read']);
    }
    
    public function testQueryMultiCmd3()
    {
        // Prepare command
        $cmd = array(
            'cmd' => ParserCommands::MULTI_CMD,
            'value' => array(
                ['cmd' => ParserCommands::GET_BYTE, 'tag' => 'TagB1'],
                ['cmd' => ParserCommands::WRITE_BYTE, 'tag' => 'TagB2', 'value' => 5],
                ['cmd' => ParserCommands::GET_WORD, 'tag' => 'TagW1'],
                ['cmd' => ParserCommands::WRITE_WORD, 'tag' => 'TagW2', 'value' => 500],
                ['cmd' => ParserCommands::GET_DWORD, 'tag' => 'TagD1'],
                ['cmd' => ParserCommands::WRITE_DWORD, 'tag' => 'TagD2', 'value' => 500000],
            )
        );
        
        // Prepare query
        $query = new ParserQuery();
        $str = $query->query($cmd);
        
        // Access rights
        $ta = $query->getAccessRights();
        
        $this->assertEquals('50|30?TagB1!31?TagB2,5!32?TagW1!33?TagW2,500!34?TagD1!35?TagD2,500000', $str);
        
        $this->assertEquals(6, count($ta));
        $this->assertEquals('TagB1', $ta[0]['tagName']);
        $this->assertTrue($ta[0]['read']);
        $this->assertEquals('TagB2', $ta[1]['tagName']);
        $this->assertFalse($ta[1]['read']);
        $this->assertEquals('TagW1', $ta[2]['tagName']);
        $this->assertTrue($ta[2]['read']);
        $this->assertEquals('TagW2', $ta[3]['tagName']);
        $this->assertFalse($ta[3]['read']);
        $this->assertEquals('TagD1', $ta[4]['tagName']);
        $this->assertTrue($ta[4]['read']);
        $this->assertEquals('TagD2', $ta[5]['tagName']);
        $this->assertFalse($ta[5]['read']);
    }
    
    public function testQueryMultiCmd4()
    {
        // Prepare command
        $cmd = array(
            'cmd' => ParserCommands::MULTI_CMD,
            'value' => array(
                ['cmd' => ParserCommands::WRITE_INT, 'tag' => 'TagINT', 'value' => -950],
                ['cmd' => ParserCommands::GET_REAL, 'tag' => 'TagR1'],
                ['cmd' => ParserCommands::WRITE_REAL, 'tag' => 'TagR2', 'value' => 5.7],
                ['cmd' => ParserCommands::ACK_ALARM, 'alarm_id' => 4],
                ['cmd' => ParserCommands::EXIT_APP]
            )
        );
        
        // Prepare query
        $query = new ParserQuery();
        $str = $query->query($cmd);
        
        // Access rights
        $ta = $query->getAccessRights();
        
        $this->assertEquals('50|37?TagINT,-950!38?TagR1!39?TagR2,5.7!90?4!600?1', $str);
        
        $this->assertEquals(5, count($ta));
        $this->assertEquals('TagINT', $ta[0]['tagName']);
        $this->assertFalse($ta[0]['read']);
        $this->assertEquals('TagR1', $ta[1]['tagName']);
        $this->assertTrue($ta[1]['read']);
        $this->assertEquals('TagR2', $ta[2]['tagName']);
        $this->assertFalse($ta[2]['read']);
        $this->assertEquals('CMD_ACK_ALARM', $ta[3]['specialFunc']);
        $this->assertEquals('ROLE_USER', $ta[3]['role']);
        $this->assertEquals('CMD_EXIT_APP', $ta[4]['specialFunc']);
        $this->assertEquals('ROLE_ADMIN', $ta[4]['role']);
    }
    
    public function testQueryMultiCmdErr1()
    {
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('CMD_MULTI_CMD: Missing value field in array!');
        
        // Prepare command
        $cmd = array(
            'cmd' => ParserCommands::MULTI_CMD,
            'valuee' => array(
                ['cmd' => ParserCommands::GET_INT, 'tag' => 'TagINT1'],
                ['cmd' => ParserCommands::GET_BIT, 'tag' => 'Tag1'],
                ['cmd' => ParserCommands::SET_BITS, 'tags' => ['Tag1', 'Tag2', 'Tag3']]
            )
        );
        
        // Prepare query
        $query = new ParserQuery();
        $query->query($cmd);
    }
    
    public function testQueryMultiCmdErr2()
    {
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('CMD_MULTI_CMD: Value need to be an array!');
        
        // Prepare command
        $cmd = array(
            'cmd' => ParserCommands::MULTI_CMD,
            'value' => 8
        );
        
        // Prepare query
        $query = new ParserQuery();
        $query->query($cmd);
    }
    
    public function testQueryMultiCmdErr3()
    {
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('CMD_MULTI_CMD: Data is not array!');
        
        // Prepare command
        $cmd = array(
            'cmd' => ParserCommands::MULTI_CMD,
            'value' => array(
                7
            )
        );
        
        // Prepare query
        $query = new ParserQuery();
        $query->query($cmd);
    }
    
    public function testQueryMultiCmdErr4()
    {
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('CMD_MULTI_CMD: Missing command field in array!');
        
        // Prepare command
        $cmd = array(
            'cmd' => ParserCommands::MULTI_CMD,
            'value' => array(
                ['cmd' => ParserCommands::GET_INT, 'tag' => 'TagINT1'],
                ['cmdd' => ParserCommands::GET_BIT, 'tag' => 'Tag1'],
                ['cmd' => ParserCommands::SET_BITS, 'tags' => ['Tag1', 'Tag2', 'Tag3']]
            )
        );
        
        // Prepare query
        $query = new ParserQuery();
        $query->query($cmd);
    }
    
    public function testQueryMultiCmdErr5()
    {
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('CMD_MULTI_CMD: Wrong command number!');
        
        // Prepare command
        $cmd = array(
            'cmd' => ParserCommands::MULTI_CMD,
            'value' => array(
                ['cmd' => ParserCommands::GET_INT, 'tag' => 'TagINT1'],
                ['cmd' => ParserCommands::GET_BIT, 'tag' => 'Tag1'],
                ['cmd' => 2087, 'tags' => ['Tag1', 'Tag2', 'Tag3']]
            )
        );
        
        // Prepare query
        $query = new ParserQuery();
        $query->query($cmd);
    }
    
    public function testQueryMultiCmdErr6()
    {
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('CMD_MULTI_CMD: Can not call MULTI_CMD inside MULTI_CMD!');
        
        // Prepare command
        $cmd = array(
            'cmd' => ParserCommands::MULTI_CMD,
            'value' => array(
                ['cmd' => ParserCommands::GET_INT, 'tag' => 'TagINT1'],
                ['cmd' => ParserCommands::GET_BIT, 'tag' => 'Tag1'],
                ['cmd' => ParserCommands::MULTI_CMD, 'value' => ['cmd' => ParserCommands::GET_INT, 'tag' => 'TagINT8']]
            )
        );
        
        // Prepare query
        $query = new ParserQuery();
        $query->query($cmd);
    }
    
    public function testQueryMultiCmdErr7()
    {
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('CMD_MULTI_CMD: Can not call GET_THREAD_CYCLE_TIME inside MULTI_CMD!');
        
        // Prepare command
        $cmd = array(
            'cmd' => ParserCommands::MULTI_CMD,
            'value' => array(
                ['cmd' => ParserCommands::GET_INT, 'tag' => 'TagINT1'],
                ['cmd' => ParserCommands::GET_BIT, 'tag' => 'Tag1'],
                ['cmd' => ParserCommands::GET_THREAD_CYCLE_TIME]
            )
        );
        
        // Prepare query
        $query = new ParserQuery();
        $query->query($cmd);
    }
}
