<?php

namespace App\Tests\Service\Admin\Parser;

use App\Service\Admin\Parser\ParserCommands;
use App\Service\Admin\Parser\ParserReplyCodes;
use App\Service\Admin\Parser\ParserResponse;
use App\Service\Admin\Parser\ParserException;
use PHPUnit\Framework\TestCase;

/**
 * Unit test for ParserResponse class
 *
 * @author Mateusz Mirosławski
 */
class ParserResponseTest extends TestCase {
    
    /**
     * Test response method
     */
    public function testResponse_err1() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('response: Server response is empty!');
        
        // Server response
        $sres = '';
        
        // Prepare response
        $response = new ParserResponse();
        $response->response($sres);
    }
    
    public function testResponse_err2() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('response: Server response is not valid!');
        
        // Server response
        $sres = '10|0|4';
        
        // Prepare response
        $response = new ParserResponse();
        $response->response($sres);
    }
    
    public function testResponse_err3() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('response: Command response need to be numeric!');
        
        // Server response
        $sres = '10er|0';
        
        // Prepare response
        $response = new ParserResponse();
        $response->response($sres);
    }
    
    public function testResponse_err4() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('ServerError: Tag does not exist!');
        
        // Server response
        $sres = '-1|5';
        
        // Prepare response
        $response = new ParserResponse();
        $response->response($sres);
    }
    
    public function testResponse_err6() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('responseERROR: Error code need to be numeric!');
        
        // Server response
        $sres = '-1|5yt';
        
        // Prepare response
        $response = new ParserResponse();
        $response->response($sres);
    }
    
    public function testResponse_err7() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('response: Wrong command number!');
        
        // Server response
        $sres = '-50|9';
        
        // Prepare response
        $response = new ParserResponse();
        $response->response($sres);
    }
    
    /**
     * Test response method for GET_BIT function
     */
    public function testResponse_GET_BIT() {
        
        // Server response
        $sres = '10|0';
        
        // Prepare response
        $response = new ParserResponse();
        $dt = $response->response($sres);
        
        $this->assertArrayHasKey('cmd', $dt);
        $this->assertArrayHasKey('value', $dt);
        
        $this->assertEquals(ParserCommands::GET_BIT, $dt['cmd']);
        $this->assertEquals(0, $dt['value']);
    }
    
    public function testResponse_GET_BIT_err1() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('RES_GET_BIT: Data response need to be numeric!');
        
        // Server response
        $sres = '10|0dw';
        
        // Prepare response
        $response = new ParserResponse();
        $response->response($sres);
    }
    
    public function testResponse_GET_BIT_err2() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('RES_GET_BIT: Data response has invalid values!');
        
        // Server response
        $sres = '10|4';
        
        // Prepare response
        $response = new ParserResponse();
        $response->response($sres);
    }
    
    /**
     * Test response method for SET_BIT function
     */
    public function testResponse_SET_BIT() {
        
        // Server response
        $sres = '11|0';
        
        // Prepare response
        $response = new ParserResponse();
        $dt = $response->response($sres);
        
        $this->assertArrayHasKey('cmd', $dt);
        $this->assertArrayHasKey('value', $dt);
        
        $this->assertEquals(ParserCommands::SET_BIT, $dt['cmd']);
        $this->assertEquals(ParserReplyCodes::OK, $dt['value']);
    }
    
    public function testResponse_SET_BIT_err1() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('res_ok: Data response need to be numeric!');
        
        // Server response
        $sres = '11|0r';
        
        // Prepare response
        $response = new ParserResponse();
        $response->response($sres);
    }
    
    public function testResponse_SET_BIT_err2() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('res_ok: Data response has invalid value!');
        
        // Server response
        $sres = '11|3';
        
        // Prepare response
        $response = new ParserResponse();
        $response->response($sres);
    }
    
    /**
     * Test response method for RESET_BIT function
     */
    public function testResponse_RESET_BIT() {
        
        // Server response
        $sres = '12|0';
        
        // Prepare response
        $response = new ParserResponse();
        $dt = $response->response($sres);
        
        $this->assertArrayHasKey('cmd', $dt);
        $this->assertArrayHasKey('value', $dt);
        
        $this->assertEquals(ParserCommands::RESET_BIT, $dt['cmd']);
        $this->assertEquals(ParserReplyCodes::OK, $dt['value']);
    }
    
    public function testResponse_RESET_BIT_err1() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('res_ok: Data response need to be numeric!');
        
        // Server response
        $sres = '12|0r';
        
        // Prepare response
        $response = new ParserResponse();
        $response->response($sres);
    }
    
    public function testResponse_RESET_BIT_err2() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('res_ok: Data response has invalid value!');
        
        // Server response
        $sres = '12|3';
        
        // Prepare response
        $response = new ParserResponse();
        $response->response($sres);
    }
    
    /**
     * Test response method for INVERT_BIT function
     */
    public function testResponse_INVERT_BIT() {
        
        // Server response
        $sres = '13|0';
        
        // Prepare response
        $response = new ParserResponse();
        $dt = $response->response($sres);
        
        $this->assertArrayHasKey('cmd', $dt);
        $this->assertArrayHasKey('value', $dt);
        
        $this->assertEquals(ParserCommands::INVERT_BIT, $dt['cmd']);
        $this->assertEquals(ParserReplyCodes::OK, $dt['value']);
    }
    
    public function testResponse_INVERT_BIT_err1() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('res_ok: Data response need to be numeric!');
        
        // Server response
        $sres = '13|0r';
        
        // Prepare response
        $response = new ParserResponse();
        $response->response($sres);
    }
    
    public function testResponse_INVERT_BIT_err2() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('res_ok: Data response has invalid value!');
        
        // Server response
        $sres = '13|3';
        
        // Prepare response
        $response = new ParserResponse();
        $response->response($sres);
    }
    
    /**
     * Test response method for GET_BITS function
     */
    public function testResponse_GET_BITS() {
        
        // Server response
        $sres = '20|0,1,1';
        
        // Prepare response
        $response = new ParserResponse();
        $dt = $response->response($sres);
        
        $this->assertArrayHasKey('cmd', $dt);
        $this->assertArrayHasKey('values', $dt);
        
        $this->assertInternalType('array', $dt['values']);
        
        $this->assertEquals(ParserCommands::GET_BITS, $dt['cmd']);
        $this->assertEquals(0, $dt['values'][0]);
        $this->assertEquals(1, $dt['values'][1]);
        $this->assertEquals(1, $dt['values'][2]);
    }
    
    public function testResponse_GET_BITS_err1() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('RES_GET_BITS: Data is empty!');
        
        // Server response
        $sres = '20|';
        
        // Prepare response
        $response = new ParserResponse();
        $response->response($sres);
    }
    
    public function testResponse_GET_BITS_err2() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('RES_GET_BITS: Error during data explode!');
        
        // Server response
        $sres = '20|1';
        
        // Prepare response
        $response = new ParserResponse();
        $response->response($sres);
    }
    
    public function testResponse_GET_BITS_err3() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('RES_GET_BITS: Bit value is not numeric!');
        
        // Server response
        $sres = '20|1,r';
        
        // Prepare response
        $response = new ParserResponse();
        $response->response($sres);
    }
    
    public function testResponse_GET_BITS_err4() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('RES_GET_BITS: Bit has invalid value!');
        
        // Server response
        $sres = '20|1,2';
        
        // Prepare response
        $response = new ParserResponse();
        $response->response($sres);
    }
    
    /**
     * Test response method for SET_BITS function
     */
    public function testResponse_SET_BITS() {
        
        // Server response
        $sres = '21|0';
        
        // Prepare response
        $response = new ParserResponse();
        $dt = $response->response($sres);
        
        $this->assertArrayHasKey('cmd', $dt);
        $this->assertArrayHasKey('value', $dt);
        
        $this->assertEquals(ParserCommands::SET_BITS, $dt['cmd']);
        $this->assertEquals(ParserReplyCodes::OK, $dt['value']);
    }
    
    public function testResponse_SET_BITS_err1() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('res_ok: Data response need to be numeric!');
        
        // Server response
        $sres = '21|0r';
        
        // Prepare response
        $response = new ParserResponse();
        $response->response($sres);
    }
    
    public function testResponse_SET_BITS_err2() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('res_ok: Data response has invalid value!');
        
        // Server response
        $sres = '21|3';
        
        // Prepare response
        $response = new ParserResponse();
        $response->response($sres);
    }
    
    public function testResponse_SET_BITS_err3() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('res_ok: Data response need to be numeric!');
        
        // Server response
        $sres = '21|0,1';
        
        // Prepare response
        $response = new ParserResponse();
        $response->response($sres);
    }
    
    /**
     * Test response method for GET_BYTE function
     */
    public function testResponse_GET_BYTE_1() {
        
        // Server response
        $sres = '30|150';
        
        // Prepare response
        $response = new ParserResponse();
        $dt = $response->response($sres);
        
        $this->assertArrayHasKey('cmd', $dt);
        $this->assertArrayHasKey('value', $dt);
        
        $this->assertEquals(ParserCommands::GET_BYTE, $dt['cmd']);
        $this->assertEquals(150, $dt['value']);
    }
    
    public function testResponse_GET_BYTE_2() {
        
        // Server response
        $sres = '30|0';
        
        // Prepare response
        $response = new ParserResponse();
        $dt = $response->response($sres);
        
        $this->assertArrayHasKey('cmd', $dt);
        $this->assertArrayHasKey('value', $dt);
        
        $this->assertEquals(ParserCommands::GET_BYTE, $dt['cmd']);
        $this->assertEquals(0, $dt['value']);
    }
    
    public function testResponse_GET_BYTE_err1() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('RES_GET_BYTE: Data is empty!');
        
        // Server response
        $sres = '30|';
        
        // Prepare response
        $response = new ParserResponse();
        $response->response($sres);
    }
    
    public function testResponse_GET_BYTE_err2() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('RES_GET_BYTE: Data value is not numeric!');
        
        // Server response
        $sres = '30|w';
        
        // Prepare response
        $response = new ParserResponse();
        $response->response($sres);
    }
    
    public function testResponse_GET_BYTE_err3() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('RES_GET_BYTE: Data value is not numeric!');
        
        // Server response
        $sres = '30|150,78';
        
        // Prepare response
        $response = new ParserResponse();
        $response->response($sres);
    }
    
    public function testResponse_GET_BYTE_err4() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('RES_GET_BYTE: Data value is out of range!');
        
        // Server response
        $sres = '30|301';
        
        // Prepare response
        $response = new ParserResponse();
        $response->response($sres);
    }
    
    /**
     * Test response method for WRITE_BYTE function
     */
    public function testResponse_WRITE_BYTE() {
        
        // Server response
        $sres = '31|0';
        
        // Prepare response
        $response = new ParserResponse();
        $dt = $response->response($sres);
        
        $this->assertArrayHasKey('cmd', $dt);
        $this->assertArrayHasKey('value', $dt);
        
        $this->assertEquals(ParserCommands::WRITE_BYTE, $dt['cmd']);
        $this->assertEquals(ParserReplyCodes::OK, $dt['value']);
    }
    
    public function testResponse_WRITE_BYTE_err1() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('res_ok: Data response need to be numeric!');
        
        // Server response
        $sres = '31|0r';
        
        // Prepare response
        $response = new ParserResponse();
        $response->response($sres);
    }
    
    public function testResponse_WRITE_BYTE_err2() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('res_ok: Data response has invalid value!');
        
        // Server response
        $sres = '31|3';
        
        // Prepare response
        $response = new ParserResponse();
        $response->response($sres);
    }
    
    /**
     * Test response method for GET_WORD function
     */
    public function testResponse_GET_WORD_1() {
        
        // Server response
        $sres = '32|1500';
        
        // Prepare response
        $response = new ParserResponse();
        $dt = $response->response($sres);
        
        $this->assertArrayHasKey('cmd', $dt);
        $this->assertArrayHasKey('value', $dt);
        
        $this->assertEquals(ParserCommands::GET_WORD, $dt['cmd']);
        $this->assertEquals(1500, $dt['value']);
    }
    
    public function testResponse_GET_WORD_2() {
        
        // Server response
        $sres = '32|0';
        
        // Prepare response
        $response = new ParserResponse();
        $dt = $response->response($sres);
        
        $this->assertArrayHasKey('cmd', $dt);
        $this->assertArrayHasKey('value', $dt);
        
        $this->assertEquals(ParserCommands::GET_WORD, $dt['cmd']);
        $this->assertEquals(0, $dt['value']);
    }
    
    public function testResponse_GET_WORD_err1() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('RES_GET_WORD: Data is empty!');
        
        // Server response
        $sres = '32|';
        
        // Prepare response
        $response = new ParserResponse();
        $response->response($sres);
    }
    
    public function testResponse_GET_WORD_err2() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('RES_GET_WORD: Data value is not numeric!');
        
        // Server response
        $sres = '32|w';
        
        // Prepare response
        $response = new ParserResponse();
        $response->response($sres);
    }
    
    public function testResponse_GET_WORD_err3() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('RES_GET_WORD: Data value is not numeric!');
        
        // Server response
        $sres = '32|150,78';
        
        // Prepare response
        $response = new ParserResponse();
        $response->response($sres);
    }
    
    public function testResponse_GET_WORD_err4() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('RES_GET_WORD: Data value is out of range!');
        
        // Server response
        $sres = '32|301000';
        
        // Prepare response
        $response = new ParserResponse();
        $response->response($sres);
    }
    
    /**
     * Test response method for WRITE_WORD function
     */
    public function testResponse_WRITE_WORD() {
        
        // Server response
        $sres = '33|0';
        
        // Prepare response
        $response = new ParserResponse();
        $dt = $response->response($sres);
        
        $this->assertArrayHasKey('cmd', $dt);
        $this->assertArrayHasKey('value', $dt);
        
        $this->assertEquals(ParserCommands::WRITE_WORD, $dt['cmd']);
        $this->assertEquals(ParserReplyCodes::OK, $dt['value']);
    }
    
    public function testResponse_WRITE_WORD_err1() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('res_ok: Data response need to be numeric!');
        
        // Server response
        $sres = '33|0r';
        
        // Prepare response
        $response = new ParserResponse();
        $response->response($sres);
    }
    
    public function testResponse_WRITE_WORD_err2() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('res_ok: Data response has invalid value!');
        
        // Server response
        $sres = '33|3';
        
        // Prepare response
        $response = new ParserResponse();
        $response->response($sres);
    }
    
    /**
     * Test response method for GET_DWORD function
     */
    public function testResponse_GET_DWORD_1() {
        
        // Server response
        $sres = '34|1500000';
        
        // Prepare response
        $response = new ParserResponse();
        $dt = $response->response($sres);
        
        $this->assertArrayHasKey('cmd', $dt);
        $this->assertArrayHasKey('value', $dt);
        
        $this->assertEquals(ParserCommands::GET_DWORD, $dt['cmd']);
        $this->assertEquals(1500000, $dt['value']);
    }
    
    public function testResponse_GET_DWORD_2() {
        
        // Server response
        $sres = '34|0';
        
        // Prepare response
        $response = new ParserResponse();
        $dt = $response->response($sres);
        
        $this->assertArrayHasKey('cmd', $dt);
        $this->assertArrayHasKey('value', $dt);
        
        $this->assertEquals(ParserCommands::GET_DWORD, $dt['cmd']);
        $this->assertEquals(0, $dt['value']);
    }
    
    public function testResponse_GET_DWORD_err1() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('RES_GET_DWORD: Data is empty!');
        
        // Server response
        $sres = '34|';
        
        // Prepare response
        $response = new ParserResponse();
        $response->response($sres);
    }
    
    public function testResponse_GET_DWORD_err2() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('RES_GET_DWORD: Data value is not numeric!');
        
        // Server response
        $sres = '34|w';
        
        // Prepare response
        $response = new ParserResponse();
        $response->response($sres);
    }
    
    public function testResponse_GET_DWORD_err3() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('RES_GET_DWORD: Data value is not numeric!');
        
        // Server response
        $sres = '34|150,78';
        
        // Prepare response
        $response = new ParserResponse();
        $response->response($sres);
    }
    
    public function testResponse_GET_DWORD_err4() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('RES_GET_DWORD: Data value is out of range!');
        
        // Server response
        $sres = '34|4294967296';
        
        // Prepare response
        $response = new ParserResponse();
        $response->response($sres);
    }
    
    /**
     * Test response method for WRITE_DWORD function
     */
    public function testResponse_WRITE_DWORD() {
        
        // Server response
        $sres = '35|0';
        
        // Prepare response
        $response = new ParserResponse();
        $dt = $response->response($sres);
        
        $this->assertArrayHasKey('cmd', $dt);
        $this->assertArrayHasKey('value', $dt);
        
        $this->assertEquals(ParserCommands::WRITE_DWORD, $dt['cmd']);
        $this->assertEquals(ParserReplyCodes::OK, $dt['value']);
    }
    
    public function testResponse_WRITE_DWORD_err1() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('res_ok: Data response need to be numeric!');
        
        // Server response
        $sres = '35|0r';
        
        // Prepare response
        $response = new ParserResponse();
        $response->response($sres);
    }
    
    public function testResponse_WRITE_DWORD_err2() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('res_ok: Data response has invalid value!');
        
        // Server response
        $sres = '35|3';
        
        // Prepare response
        $response = new ParserResponse();
        $response->response($sres);
    }
    
    /**
     * Test response method for GET_INT function
     */
    public function testResponse_GET_INT_1() {
        
        // Server response
        $sres = '36|-850';
        
        // Prepare response
        $response = new ParserResponse();
        $dt = $response->response($sres);
        
        $this->assertArrayHasKey('cmd', $dt);
        $this->assertArrayHasKey('value', $dt);
        
        $this->assertEquals(ParserCommands::GET_INT, $dt['cmd']);
        $this->assertEquals(-850, $dt['value']);
    }
    
    public function testResponse_GET_INT_2() {
        
        // Server response
        $sres = '36|0';
        
        // Prepare response
        $response = new ParserResponse();
        $dt = $response->response($sres);
        
        $this->assertArrayHasKey('cmd', $dt);
        $this->assertArrayHasKey('value', $dt);
        
        $this->assertEquals(ParserCommands::GET_INT, $dt['cmd']);
        $this->assertEquals(0, $dt['value']);
    }
    
    public function testResponse_GET_INT_err1() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('RES_GET_INT: Data is empty!');
        
        // Server response
        $sres = '36|';
        
        // Prepare response
        $response = new ParserResponse();
        $response->response($sres);
    }
    
    public function testResponse_GET_INT_err2() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('RES_GET_INT: Data value is not numeric!');
        
        // Server response
        $sres = '36|w';
        
        // Prepare response
        $response = new ParserResponse();
        $response->response($sres);
    }
    
    public function testResponse_GET_INT_err3() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('RES_GET_INT: Data value is not numeric!');
        
        // Server response
        $sres = '36|150,78';
        
        // Prepare response
        $response = new ParserResponse();
        $response->response($sres);
    }
    
    public function testResponse_GET_INT_err4() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('RES_GET_INT: Data value is out of range!');
        
        // Server response
        $sres = '36|4294967296';
        
        // Prepare response
        $response = new ParserResponse();
        $response->response($sres);
    }
    
    /**
     * Test response method for WRITE_INT function
     */
    public function testResponse_WRITE_INT() {
        
        // Server response
        $sres = '37|0';
        
        // Prepare response
        $response = new ParserResponse();
        $dt = $response->response($sres);
        
        $this->assertArrayHasKey('cmd', $dt);
        $this->assertArrayHasKey('value', $dt);
        
        $this->assertEquals(ParserCommands::WRITE_INT, $dt['cmd']);
        $this->assertEquals(ParserReplyCodes::OK, $dt['value']);
    }
    
    public function testResponse_WRITE_INT_err1() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('res_ok: Data response need to be numeric!');
        
        // Server response
        $sres = '37|0r';
        
        // Prepare response
        $response = new ParserResponse();
        $response->response($sres);
    }
    
    public function testResponse_WRITE_INT_err2() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('res_ok: Data response has invalid value!');
        
        // Server response
        $sres = '37|3';
        
        // Prepare response
        $response = new ParserResponse();
        $response->response($sres);
    }
    
    /**
     * Test response method for GET_REAL function
     */
    public function testResponse_GET_REAL_1() {
        
        // Server response
        $sres = '38|-85.8';
        
        // Prepare response
        $response = new ParserResponse();
        $dt = $response->response($sres);
        
        $this->assertArrayHasKey('cmd', $dt);
        $this->assertArrayHasKey('value', $dt);
        
        $this->assertEquals(ParserCommands::GET_REAL, $dt['cmd']);
        $this->assertEquals(-85.8, $dt['value']);
    }
    
    public function testResponse_GET_REAL_2() {
        
        // Server response
        $sres = '38|0';
        
        // Prepare response
        $response = new ParserResponse();
        $dt = $response->response($sres);
        
        $this->assertArrayHasKey('cmd', $dt);
        $this->assertArrayHasKey('value', $dt);
        
        $this->assertEquals(ParserCommands::GET_REAL, $dt['cmd']);
        $this->assertEquals(0, $dt['value']);
    }
    
    public function testResponse_GET_REAL_err1() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('RES_GET_REAL: Data is empty!');
        
        // Server response
        $sres = '38|';
        
        // Prepare response
        $response = new ParserResponse();
        $response->response($sres);
    }
    
    /**
     * Test response method for WRITE_REAL function
     */
    public function testResponse_WRITE_REAL() {
        
        // Server response
        $sres = '39|0';
        
        // Prepare response
        $response = new ParserResponse();
        $dt = $response->response($sres);
        
        $this->assertArrayHasKey('cmd', $dt);
        $this->assertArrayHasKey('value', $dt);
        
        $this->assertEquals(ParserCommands::WRITE_REAL, $dt['cmd']);
        $this->assertEquals(ParserReplyCodes::OK, $dt['value']);
    }
    
    public function testResponse_WRITE_REAL_err1() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('res_ok: Data response need to be numeric!');
        
        // Server response
        $sres = '39|0r';
        
        // Prepare response
        $response = new ParserResponse();
        $response->response($sres);
    }
    
    public function testResponse_WRITE_REAL_err2() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('res_ok: Data response has invalid value!');
        
        // Server response
        $sres = '39|3';
        
        // Prepare response
        $response = new ParserResponse();
        $response->response($sres);
    }
    
    /**
     * Test response method for ACK_ALARM function
     */
    public function testResponse_ACK_ALARM() {
        
        // Server response
        $sres = '90|0';
        
        // Prepare response
        $response = new ParserResponse();
        $dt = $response->response($sres);
        
        $this->assertArrayHasKey('cmd', $dt);
        $this->assertArrayHasKey('value', $dt);
        
        $this->assertEquals(ParserCommands::ACK_ALARM, $dt['cmd']);
        $this->assertEquals(ParserReplyCodes::OK, $dt['value']);
    }
    
    public function testResponse_ACK_ALARM_err1() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('res_ok: Data response need to be numeric!');
        
        // Server response
        $sres = '90|0r';
        
        // Prepare response
        $response = new ParserResponse();
        $response->response($sres);
    }
    
    public function testResponse_ACK_ALARM_err2() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('res_ok: Data response has invalid value!');
        
        // Server response
        $sres = '90|3';
        
        // Prepare response
        $response = new ParserResponse();
        $response->response($sres);
    }
    
    /**
     * Test response method for EXIT_APP function
     */
    public function testResponse_EXIT_APP() {
        
        // Server response
        $sres = '600|0';
        
        // Prepare response
        $response = new ParserResponse();
        $dt = $response->response($sres);
        
        $this->assertArrayHasKey('cmd', $dt);
        $this->assertArrayHasKey('value', $dt);
        
        $this->assertEquals(ParserCommands::EXIT_APP, $dt['cmd']);
        $this->assertEquals(ParserReplyCodes::OK, $dt['value']);
    }
    
    public function testResponse_EXIT_APP_err1() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('res_ok: Data response need to be numeric!');
        
        // Server response
        $sres = '600|0r';
        
        // Prepare response
        $response = new ParserResponse();
        $response->response($sres);
    }
    
    public function testResponse_EXIT_APP_err2() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('res_ok: Data response has invalid value!');
        
        // Server response
        $sres = '600|3';
        
        // Prepare response
        $response = new ParserResponse();
        $response->response($sres);
    }
    
    /**
     * Test response method for GET_THREAD_CYCLE_TIME function
     */
    public function testResponse_GET_THREAD_CYCLE_TIME_1() {
        
        // Server response
        $sres = '500|3?2!Updater_1:1.0?1.1?1.2!Updater_2:2.0?2.1?2.2!Updater_3:3.0?3.1?3.2!';
        $sres .= 'DriverBuffer_1:1.3?1.4?1.5!DriverBuffer_2:2.3?2.4?2.5!';
        $sres .= 'Logger:10.0?10.1?10.2!LoggerWriter:20.0?20.1?20.2!Alarming:30.0?30.1?30.2!Script:40.0?40.1?40.2!';
        
        // Prepare response
        $response = new ParserResponse();
        $dt = $response->response($sres);
        
        $this->assertArrayHasKey('cmd', $dt);
        $this->assertArrayHasKey('values', $dt);
        
        $this->assertInternalType('array', $dt['values']);
        $this->assertEquals(8, count($dt['values']));
        
        $this->assertEquals(ParserCommands::GET_THREAD_CYCLE_TIME, $dt['cmd']);
        
        $val = $dt['values'];
        $this->assertArrayHasKey('UpdaterCnt', $val);
        $this->assertArrayHasKey('PollingCnt', $val);
        $this->assertArrayHasKey('Updater', $val);
        $this->assertInternalType('array', $val['Updater']);
        $this->assertArrayHasKey('Polling', $val);
        $this->assertInternalType('array', $val['Polling']);
        $this->assertArrayHasKey('Logger', $val);
        $this->assertInternalType('array', $val['Logger']);
        $this->assertArrayHasKey('LoggerWriter', $val);
        $this->assertInternalType('array', $val['LoggerWriter']);
        $this->assertArrayHasKey('Alarming', $val);
        $this->assertInternalType('array', $val['Alarming']);
        $this->assertArrayHasKey('Script', $val);
        $this->assertInternalType('array', $val['Script']);
        
        $this->assertEquals(3, $val['UpdaterCnt']);
        $this->assertEquals(2, $val['PollingCnt']);
        
        $this->assertEquals(3, count($val['Updater']));
        $this->assertArrayHasKey('Updater_1', $val['Updater']);
        $this->assertArrayHasKey('Updater_2', $val['Updater']);
        $this->assertArrayHasKey('Updater_3', $val['Updater']);
        
        foreach($val['Updater'] as $key => $value) {
            $upd = $val['Updater'][$key];
            $this->assertEquals($upd, $value);
            $this->assertInternalType('array', $upd);
            $this->assertArrayHasKey('min', $upd);
            $this->assertArrayHasKey('max', $upd);
            $this->assertArrayHasKey('current', $upd);
        }
        
        $this->assertEquals(2, count($val['Polling']));
        $this->assertArrayHasKey('DriverBuffer_1', $val['Polling']);
        $this->assertArrayHasKey('DriverBuffer_2', $val['Polling']);
        
        foreach($val['Polling'] as $key => $value) {
            $upd = $val['Polling'][$key];
            $this->assertEquals($upd, $value);
            $this->assertInternalType('array', $upd);
            $this->assertArrayHasKey('min', $upd);
            $this->assertArrayHasKey('max', $upd);
            $this->assertArrayHasKey('current', $upd);
        }
        
        $this->assertInternalType('array', $val['Logger']);
        $this->assertArrayHasKey('min', $val['Logger']);
        $this->assertArrayHasKey('max', $val['Logger']);
        $this->assertArrayHasKey('current', $val['Logger']);
        
        $this->assertInternalType('array', $val['LoggerWriter']);
        $this->assertArrayHasKey('min', $val['LoggerWriter']);
        $this->assertArrayHasKey('max', $val['LoggerWriter']);
        $this->assertArrayHasKey('current', $val['LoggerWriter']);
        
        $this->assertInternalType('array', $val['Alarming']);
        $this->assertArrayHasKey('min', $val['Alarming']);
        $this->assertArrayHasKey('max', $val['Alarming']);
        $this->assertArrayHasKey('current', $val['Alarming']);
        
        $this->assertInternalType('array', $val['Script']);
        $this->assertArrayHasKey('min', $val['Script']);
        $this->assertArrayHasKey('max', $val['Script']);
        $this->assertArrayHasKey('current', $val['Script']);
        
        $this->assertEquals(1.0, $val['Updater']['Updater_1']['min']);
        $this->assertEquals(1.1, $val['Updater']['Updater_1']['max']);
        $this->assertEquals(1.2, $val['Updater']['Updater_1']['current']);
        $this->assertEquals(2.0, $val['Updater']['Updater_2']['min']);
        $this->assertEquals(2.1, $val['Updater']['Updater_2']['max']);
        $this->assertEquals(2.2, $val['Updater']['Updater_2']['current']);
        $this->assertEquals(3.0, $val['Updater']['Updater_3']['min']);
        $this->assertEquals(3.1, $val['Updater']['Updater_3']['max']);
        $this->assertEquals(3.2, $val['Updater']['Updater_3']['current']);
        
        $this->assertEquals(1.3, $val['Polling']['DriverBuffer_1']['min']);
        $this->assertEquals(1.4, $val['Polling']['DriverBuffer_1']['max']);
        $this->assertEquals(1.5, $val['Polling']['DriverBuffer_1']['current']);
        $this->assertEquals(2.3, $val['Polling']['DriverBuffer_2']['min']);
        $this->assertEquals(2.4, $val['Polling']['DriverBuffer_2']['max']);
        $this->assertEquals(2.5, $val['Polling']['DriverBuffer_2']['current']);
        
        $this->assertEquals(10.0, $val['Logger']['min']);
        $this->assertEquals(10.1, $val['Logger']['max']);
        $this->assertEquals(10.2, $val['Logger']['current']);
        
        $this->assertEquals(20.0, $val['LoggerWriter']['min']);
        $this->assertEquals(20.1, $val['LoggerWriter']['max']);
        $this->assertEquals(20.2, $val['LoggerWriter']['current']);
        
        $this->assertEquals(30.0, $val['Alarming']['min']);
        $this->assertEquals(30.1, $val['Alarming']['max']);
        $this->assertEquals(30.2, $val['Alarming']['current']);
        
        $this->assertEquals(40.0, $val['Script']['min']);
        $this->assertEquals(40.1, $val['Script']['max']);
        $this->assertEquals(40.2, $val['Script']['current']);
    }
    
    public function testResponse_GET_THREAD_CYCLE_TIME_err1() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('RES_GET_THREAD_CYCLE_TIME: Data is empty!');
        
        // Server response
        $sres = '500|';
        
        // Prepare response
        $response = new ParserResponse();
        $response->response($sres);
    }
    
    public function testResponse_GET_THREAD_CYCLE_TIME_err2() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('RES_GET_THREAD_CYCLE_TIME: Error during data explode!');
        
        // Server response
        $sres = '500|3';
        
        // Prepare response
        $response = new ParserResponse();
        $response->response($sres);
    }
    
    public function testResponse_GET_THREAD_CYCLE_TIME_err3() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('RES_GET_THREAD_CYCLE_TIME: Error during count data explode!');
        
        // Server response
        $sres = '500|3?2?5!Updater_1:1.0?1.1?1.2!Updater_2:2.0?2.1?2.2!Updater_3:3.0?3.1?3.2!';
        $sres .= 'DriverBuffer_1:1.3?1.4?1.5!DriverBuffer_2:2.3?2.4?2.5!';
        $sres .= 'Logger:10.0?10.1?10.2!LoggerWriter:20.0?20.1?20.2!Alarming:30.0?30.1?30.2!Script:40.0?40.1?40.2!';
        
        // Prepare response
        $response = new ParserResponse();
        $response->response($sres);
    }
    
    public function testResponse_GET_THREAD_CYCLE_TIME_err4() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('RES_GET_THREAD_CYCLE_TIME: Error during Process Updater data explode!');
        
        // Server response
        $sres = '500|3?2!Updater_1:1.0?1.1?1.2!Upddater_2:2.0?2.1?2.2!Updater_3:3.0?3.1?3.2!';
        $sres .= 'DriverBuffer_1:1.3?1.4?1.5!DriverBuffer_2:2.3?2.4?2.5!';
        $sres .= 'Logger:10.0?10.1?10.2!LoggerWriter:20.0?20.1?20.2!Alarming:30.0?30.1?30.2!Script:40.0?40.1?40.2!';
        
        // Prepare response
        $response = new ParserResponse();
        $response->response($sres);
    }
    
    public function testResponse_GET_THREAD_CYCLE_TIME_err5() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('RES_GET_THREAD_CYCLE_TIME: Error during Driver polling data explode!');
        
        // Server response
        $sres = '500|3?2!Updater_1:1.0?1.1?1.2!Updater_2:2.0?2.1?2.2!Updater_3:3.0?3.1?3.2!';
        $sres .= 'DriverBuffer_1:1.3?1.4?1.5!DrivderBuffer_2:2.3?2.4?2.5!';
        $sres .= 'Logger:10.0?10.1?10.2!LoggerWriter:20.0?20.1?20.2!Alarming:30.0?30.1?30.2!Script:40.0?40.1?40.2!';
        
        // Prepare response
        $response = new ParserResponse();
        $response->response($sres);
    }
    
    public function testResponse_GET_THREAD_CYCLE_TIME_err6() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('RES_GET_THREAD_CYCLE_TIME: Error during Tag logger data explode!');
        
        // Server response
        $sres = '500|3?2!Updater_1:1.0?1.1?1.2!Updater_2:2.0?2.1?2.2!Updater_3:3.0?3.1?3.2!';
        $sres .= 'DriverBuffer_1:1.3?1.4?1.5!DriverBuffer_2:2.3?2.4?2.5!';
        $sres .= 'Logwger:10.0?10.1?10.2!LoggerWriter:20.0?20.1?20.2!Alarming:30.0?30.1?30.2!Script:40.0?40.1?40.2!';
        
        // Prepare response
        $response = new ParserResponse();
        $response->response($sres);
    }
    
    public function testResponse_GET_THREAD_CYCLE_TIME_err7() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('RES_GET_THREAD_CYCLE_TIME: Error during Tag logger writer data explode!');
        
        // Server response
        $sres = '500|3?2!Updater_1:1.0?1.1?1.2!Updater_2:2.0?2.1?2.2!Updater_3:3.0?3.1?3.2!';
        $sres .= 'DriverBuffer_1:1.3?1.4?1.5!DriverBuffer_2:2.3?2.4?2.5!';
        $sres .= 'Logger:10.0?10.1?10.2!LoggerWrditer:20.0?20.1?20.2!Alarming:30.0?30.1?30.2!Script:40.0?40.1?40.2!';
        
        // Prepare response
        $response = new ParserResponse();
        $response->response($sres);
    }
    
    public function testResponse_GET_THREAD_CYCLE_TIME_err8() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('RES_GET_THREAD_CYCLE_TIME: Error during Alarming data explode!');
        
        // Server response
        $sres = '500|3?2!Updater_1:1.0?1.1?1.2!Updater_2:2.0?2.1?2.2!Updater_3:3.0?3.1?3.2!';
        $sres .= 'DriverBuffer_1:1.3?1.4?1.5!DriverBuffer_2:2.3?2.4?2.5!';
        $sres .= 'Logger:10.0?10.1?10.2!LoggerWriter:20.0?20.1?20.2!Allarming:30.0?30.1?30.2!Script:40.0?40.1?40.2!';
        
        // Prepare response
        $response = new ParserResponse();
        $response->response($sres);
    }
    
    public function testResponse_GET_THREAD_CYCLE_TIME_err9() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('RES_GET_THREAD_CYCLE_TIME: Error during Script system data explode!');
        
        // Server response
        $sres = '500|3?2!Updater_1:1.0?1.1?1.2!Updater_2:2.0?2.1?2.2!Updater_3:3.0?3.1?3.2!';
        $sres .= 'DriverBuffer_1:1.3?1.4?1.5!DriverBuffer_2:2.3?2.4?2.5!';
        $sres .= 'Logger:10.0?10.1?10.2!LoggerWriter:20.0?20.1?20.2!Alarming:30.0?30.1?30.2!Scr7ipt:40.0?40.1?40.2!';
        
        // Prepare response
        $response = new ParserResponse();
        $response->response($sres);
    }
    
    public function testResponse_GET_THREAD_CYCLE_TIME_err10() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('RES_GET_THREAD_CYCLE_TIME: Error during thread values explode!');
        
        // Server response
        $sres = '500|3?2!Updater_1:1.0?1.1?1.2!Updater_2:2.0?2.1?2.2!Updater_3:3.0?3.1?3.2!';
        $sres .= 'DriverBuffer_1:1.3?1.4?1.5!DriverBuffer_2:2.3?2.4?2.5!';
        $sres .= 'Logger:10.0?10.1?10.2?44!LoggerWriter:20.0?20.1?20.2!Alarming:30.0?30.1?30.2!Script:40.0?40.1?40.2!';
        
        // Prepare response
        $response = new ParserResponse();
        $response->response($sres);
    }
    
    public function testResponse_GET_THREAD_CYCLE_TIME_err11() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('RES_GET_THREAD_CYCLE_TIME: Error during thread values parsing!');
        
        // Server response
        $sres = '500|3?2!Updater_1:1.0?1.1?1.2!Updater_2:2.0?2.1?2.2!Updater_3:3.0?3.1?3.2!';
        $sres .= 'DriverBuffer_1:1.3?1.4?1.5!DriverBuffer_2:2.3?2.4?2.5!';
        $sres .= 'Logger:10.0?10.1?10.2!LoggerWriter:20.0?r?20.2!Alarming:30.0?30.1?30.2!Script:40.0?40.1?40.2!';
        
        // Prepare response
        $response = new ParserResponse();
        $response->response($sres);
    }
    
    public function testResponse_GET_THREAD_CYCLE_TIME_err12() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('RES_GET_THREAD_CYCLE_TIME: Error during count data parsing!');
        
        // Server response
        $sres = '500|3?e!Updater_1:1.0?1.1?1.2!Updater_2:2.0?2.1?2.2!Updater_3:3.0?3.1?3.2!';
        $sres .= 'DriverBuffer_1:1.3?1.4?1.5!DriverBuffer_2:2.3?2.4?2.5!';
        $sres .= 'Logger:10.0?10.1?10.2!LoggerWriter:20.0?20.5?20.2!Alarming:30.0?30.1?30.2!Script:40.0?40.1?40.2!';
        
        // Prepare response
        $response = new ParserResponse();
        $response->response($sres);
    }
    
    /**
     * Test response method for MULTI_CMD function
     */
    public function testResponse_MULTI_CMD_1() {
        
        // Server response
        $sres = '50|10?1!11?0!12?0!13?0';
        
        // Prepare response
        $response = new ParserResponse();
        $dt = $response->response($sres);
        
        $this->assertArrayHasKey('cmd', $dt);
        $this->assertArrayHasKey('value', $dt);
        
        $this->assertInternalType('array', $dt['value']);
        
        $this->assertEquals(ParserCommands::MULTI_CMD, $dt['cmd']);
        
        $cmd1 = $dt['value'][0];
        $this->assertInternalType('array', $cmd1);
        $this->assertArrayHasKey('cmd', $cmd1);
        $this->assertArrayHasKey('value', $cmd1);
        $this->assertEquals(ParserCommands::GET_BIT, $cmd1['cmd']);
        $this->assertEquals(1, $cmd1['value']);
        
        $cmd2 = $dt['value'][1];
        $this->assertInternalType('array', $cmd2);
        $this->assertArrayHasKey('cmd', $cmd2);
        $this->assertArrayHasKey('value', $cmd2);
        $this->assertEquals(ParserCommands::SET_BIT, $cmd2['cmd']);
        $this->assertEquals(ParserReplyCodes::OK, $cmd2['value']);
        
        $cmd3 = $dt['value'][2];
        $this->assertInternalType('array', $cmd3);
        $this->assertArrayHasKey('cmd', $cmd3);
        $this->assertArrayHasKey('value', $cmd3);
        $this->assertEquals(ParserCommands::RESET_BIT, $cmd3['cmd']);
        $this->assertEquals(ParserReplyCodes::OK, $cmd3['value']);
        
        $cmd4 = $dt['value'][3];
        $this->assertInternalType('array', $cmd4);
        $this->assertArrayHasKey('cmd', $cmd4);
        $this->assertArrayHasKey('value', $cmd4);
        $this->assertEquals(ParserCommands::INVERT_BIT, $cmd4['cmd']);
        $this->assertEquals(ParserReplyCodes::OK, $cmd4['value']);
    }
    
    public function testResponse_MULTI_CMD_2() {
        
        // Server response
        $sres = '50|20?1,0,1,1!21?0!30?200!31?0';
        
        // Prepare response
        $response = new ParserResponse();
        $dt = $response->response($sres);
        
        $this->assertArrayHasKey('cmd', $dt);
        $this->assertArrayHasKey('value', $dt);
        
        $this->assertInternalType('array', $dt['value']);
        
        $this->assertEquals(ParserCommands::MULTI_CMD, $dt['cmd']);
        
        $cmd1 = $dt['value'][0];
        $this->assertInternalType('array', $cmd1);
        $this->assertArrayHasKey('cmd', $cmd1);
        $this->assertArrayHasKey('values', $cmd1);
        $this->assertEquals(ParserCommands::GET_BITS, $cmd1['cmd']);
        $this->assertInternalType('array', $cmd1['values']);
        $this->assertEquals(1, $cmd1['values'][0]);
        $this->assertEquals(0, $cmd1['values'][1]);
        $this->assertEquals(1, $cmd1['values'][2]);
        $this->assertEquals(1, $cmd1['values'][3]);
        
        $cmd2 = $dt['value'][1];
        $this->assertInternalType('array', $cmd2);
        $this->assertArrayHasKey('cmd', $cmd2);
        $this->assertArrayHasKey('value', $cmd2);
        $this->assertEquals(ParserCommands::SET_BITS, $cmd2['cmd']);
        $this->assertEquals(ParserReplyCodes::OK, $cmd2['value']);
        
        $cmd3 = $dt['value'][2];
        $this->assertInternalType('array', $cmd3);
        $this->assertArrayHasKey('cmd', $cmd3);
        $this->assertArrayHasKey('value', $cmd3);
        $this->assertEquals(ParserCommands::GET_BYTE, $cmd3['cmd']);
        $this->assertEquals(200, $cmd3['value']);
        
        $cmd4 = $dt['value'][3];
        $this->assertInternalType('array', $cmd4);
        $this->assertArrayHasKey('cmd', $cmd4);
        $this->assertArrayHasKey('value', $cmd4);
        $this->assertEquals(ParserCommands::WRITE_BYTE, $cmd4['cmd']);
        $this->assertEquals(ParserReplyCodes::OK, $cmd4['value']);
    }
    
    public function testResponse_MULTI_CMD_3() {
        
        // Server response
        $sres = '50|32?500!33?0!34?200000!35?0';
        
        // Prepare response
        $response = new ParserResponse();
        $dt = $response->response($sres);
        
        $this->assertArrayHasKey('cmd', $dt);
        $this->assertArrayHasKey('value', $dt);
        
        $this->assertInternalType('array', $dt['value']);
        
        $this->assertEquals(ParserCommands::MULTI_CMD, $dt['cmd']);
        
        $cmd1 = $dt['value'][0];
        $this->assertInternalType('array', $cmd1);
        $this->assertArrayHasKey('cmd', $cmd1);
        $this->assertArrayHasKey('value', $cmd1);
        $this->assertEquals(ParserCommands::GET_WORD, $cmd1['cmd']);
        $this->assertEquals(500, $cmd1['value']);
        
        $cmd2 = $dt['value'][1];
        $this->assertInternalType('array', $cmd2);
        $this->assertArrayHasKey('cmd', $cmd2);
        $this->assertArrayHasKey('value', $cmd2);
        $this->assertEquals(ParserCommands::WRITE_WORD, $cmd2['cmd']);
        $this->assertEquals(ParserReplyCodes::OK, $cmd2['value']);
        
        $cmd3 = $dt['value'][2];
        $this->assertInternalType('array', $cmd3);
        $this->assertArrayHasKey('cmd', $cmd3);
        $this->assertArrayHasKey('value', $cmd3);
        $this->assertEquals(ParserCommands::GET_DWORD, $cmd3['cmd']);
        $this->assertEquals(200000, $cmd3['value']);
        
        $cmd4 = $dt['value'][3];
        $this->assertInternalType('array', $cmd4);
        $this->assertArrayHasKey('cmd', $cmd4);
        $this->assertArrayHasKey('value', $cmd4);
        $this->assertEquals(ParserCommands::WRITE_DWORD, $cmd4['cmd']);
        $this->assertEquals(ParserReplyCodes::OK, $cmd4['value']);
    }
    
    public function testResponse_MULTI_CMD_4() {
        
        // Server response
        $sres = '50|36?-500!37?0!38?-3.14!39?0';
        
        // Prepare response
        $response = new ParserResponse();
        $dt = $response->response($sres);
        
        $this->assertArrayHasKey('cmd', $dt);
        $this->assertArrayHasKey('value', $dt);
        
        $this->assertInternalType('array', $dt['value']);
        
        $this->assertEquals(ParserCommands::MULTI_CMD, $dt['cmd']);
        
        $cmd1 = $dt['value'][0];
        $this->assertInternalType('array', $cmd1);
        $this->assertArrayHasKey('cmd', $cmd1);
        $this->assertArrayHasKey('value', $cmd1);
        $this->assertEquals(ParserCommands::GET_INT, $cmd1['cmd']);
        $this->assertEquals(-500, $cmd1['value']);
        
        $cmd2 = $dt['value'][1];
        $this->assertInternalType('array', $cmd2);
        $this->assertArrayHasKey('cmd', $cmd2);
        $this->assertArrayHasKey('value', $cmd2);
        $this->assertEquals(ParserCommands::WRITE_INT, $cmd2['cmd']);
        $this->assertEquals(ParserReplyCodes::OK, $cmd2['value']);
        
        $cmd3 = $dt['value'][2];
        $this->assertInternalType('array', $cmd3);
        $this->assertArrayHasKey('cmd', $cmd3);
        $this->assertArrayHasKey('value', $cmd3);
        $this->assertEquals(ParserCommands::GET_REAL, $cmd3['cmd']);
        $this->assertEquals(-3.14, $cmd3['value']);
        
        $cmd4 = $dt['value'][3];
        $this->assertInternalType('array', $cmd4);
        $this->assertArrayHasKey('cmd', $cmd4);
        $this->assertArrayHasKey('value', $cmd4);
        $this->assertEquals(ParserCommands::WRITE_REAL, $cmd4['cmd']);
        $this->assertEquals(ParserReplyCodes::OK, $cmd4['value']);
    }
    
    public function testResponse_MULTI_CMD_5() {
        
        // Server response
        $sres = '50|90?0!600?0';
        
        // Prepare response
        $response = new ParserResponse();
        $dt = $response->response($sres);
        
        $this->assertArrayHasKey('cmd', $dt);
        $this->assertArrayHasKey('value', $dt);
        
        $this->assertInternalType('array', $dt['value']);
        
        $this->assertEquals(ParserCommands::MULTI_CMD, $dt['cmd']);
        
        $cmd1 = $dt['value'][0];
        $this->assertInternalType('array', $cmd1);
        $this->assertArrayHasKey('cmd', $cmd1);
        $this->assertArrayHasKey('value', $cmd1);
        $this->assertEquals(ParserCommands::ACK_ALARM, $cmd1['cmd']);
        $this->assertEquals(ParserReplyCodes::OK, $cmd1['value']);
        
        $cmd2 = $dt['value'][1];
        $this->assertInternalType('array', $cmd2);
        $this->assertArrayHasKey('cmd', $cmd2);
        $this->assertArrayHasKey('value', $cmd2);
        $this->assertEquals(ParserCommands::EXIT_APP, $cmd2['cmd']);
        $this->assertEquals(ParserReplyCodes::OK, $cmd2['value']);
    }
    
    public function testResponse_MULTI_CMD_err1() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('RES_MULTI_CMD: Data is empty!');
        
        // Server response
        $sres = '50|';
        
        // Prepare response
        $response = new ParserResponse();
        $response->response($sres);
    }
    
    public function testResponse_MULTI_CMD_err2() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('RES_MULTI_CMD: Error during data explode!');
        
        // Server response
        $sres = '50|90?0';
        
        // Prepare response
        $response = new ParserResponse();
        $response->response($sres);
    }
    
    public function testResponse_MULTI_CMD_err3() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('RES_MULTI_CMD: Server response is invalid!');
        
        // Server response
        $sres = '50|36?-500!37?0?1!38?-3.14!39?0';
        
        // Prepare response
        $response = new ParserResponse();
        $response->response($sres);
    }
    
    public function testResponse_MULTI_CMD_err4() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('RES_MULTI_CMD: Command response need to be numeric!');
        
        // Server response
        $sres = '50|36?-500!37w?0!38?-3.14!39?0';
        
        // Prepare response
        $response = new ParserResponse();
        $response->response($sres);
    }
    
    public function testResponse_MULTI_CMD_err5() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('RES_MULTI_CMD: Wrong command number!');
        
        // Server response
        $sres = '50|36?-500!370?0!38?-3.14!39?0';
        
        // Prepare response
        $response = new ParserResponse();
        $response->response($sres);
    }
    
    public function testResponse_MULTI_CMD_err6() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('RES_MULTI_CMD: Can not call MULTI_CMD inside MULTI_CMD!');
        
        // Server response
        $sres = '50|36?-500!37?0!38?-3.14!50?0';
        
        // Prepare response
        $response = new ParserResponse();
        $response->response($sres);
    }
    
    public function testResponse_MULTI_CMD_err7() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('RES_MULTI_CMD: Can not call GET_THREAD_CYCLE_TIME inside MULTI_CMD!');
        
        // Server response
        $sres = '50|36?-500!37?0!38?-3.14!500?0';
        
        // Prepare response
        $response = new ParserResponse();
        $response->response($sres);
    }
    
    public function testResponse_MULTI_CMD_err8() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('ServerError: Unknown reply');
        
        // Server response
        $sres = '50|11?0!-1?3';
        
        // Prepare response
        $response = new ParserResponse();
        $response->response($sres);
    }
}
