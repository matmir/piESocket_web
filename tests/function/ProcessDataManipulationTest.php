<?php

namespace App\Tests\Func;

use App\Tests\BaseFunctionTestCase;
use App\Service\Admin\Parser\ParserException;
use App\Service\Admin\Parser\ParserReplyCodes;

/**
 * Function tests for controller process data manipulation
 *
 * @author Mateusz Mirosławski
 */
class ProcessDataManipulationTest extends BaseFunctionTestCase {
    
    /**
     * Test GetBit function
     */
    public function testGetBit() {
        
        // 0101 1011
        $pret = $this->parser->writeByte('TEST_BYTE10', 91);
        
        $this->assertEquals(ParserReplyCodes::OK, $pret);
        
        $this->waitOnProcessDataSync();
        
        $this->assertEquals(1, $this->parser->getBit('TEST_BIT0'));
        $this->assertEquals(1, $this->parser->getBit('TEST_BIT1'));
        $this->assertEquals(0, $this->parser->getBit('TEST_BIT2'));
        $this->assertEquals(1, $this->parser->getBit('TEST_BIT3'));
    }
    
    public function testGetBitWrong1() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('ServerError: Tag does not exist!');
        
        $this->parser->getBit('TEST_BITn');
    }
    
    public function testGetBitWrong2() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('ServerError: Tag has wrong type!');
        
        $this->parser->getBit('TEST_BYTE10');
    }
    
    /**
     * Test GetBits function
     */
    public function testGetBits() {
        
        // 0101 1011
        $pret = $this->parser->writeByte('TEST_BYTE10', 91);
        $this->assertEquals(ParserReplyCodes::OK, $pret);
        
        $this->waitOnProcessDataSync();
        
        // Prepare tag names array
        $tags = array('TEST_BIT4', 'TEST_BIT5', 'TEST_BIT6', 'TEST_BIT7');
        
        $vals = $this->parser->getBits($tags);
        
        $this->assertEquals(1, $vals[0]);
        $this->assertEquals(0, $vals[1]);
        $this->assertEquals(1, $vals[2]);
        $this->assertEquals(0, $vals[3]);
    }
    
    public function testGetBitsWrong1() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('ServerError: Tag does not exist!');
        
        // Prepare tag names array
        $tags = array('TEST_BIT4', 'TEST_BIT5d', 'TEST_BIT6', 'TEST_BIT7');
        $this->parser->getBits($tags);
    }
    
    public function testGetBitsWrong2() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('ServerError: Tag has wrong type!');
        
        // Prepare tag names array
        $tags = array('TEST_BIT4', 'TEST_BIT5', 'TEST_BYTE10', 'TEST_BIT7');
        $this->parser->getBits($tags);
    }
    
    /**
     * Test SetBit function
     */
    public function testSetBit() {
        
        // Check process byte
        $this->assertEquals(0, $this->parser->getByte('TEST_BYTE10'));
        
        $pret = $this->parser->setBit('TEST_BIT1');
        
        $this->assertEquals(ParserReplyCodes::OK, $pret);
        
        $this->waitOnProcessDataSync();
        
        $this->assertEquals(1, $this->parser->getBit('TEST_BIT1'));
    }
    
    public function testSetBitWrong1() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('ServerError: Tag does not exist!');
        
        $this->parser->setBit('TEST_BITn');
    }
    
    public function testSetBitWrong2() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('ServerError: Tag has wrong type!');
        
        $this->parser->setBit('TEST_BYTE10');
    }
    
    /**
     * Test ResetBit function
     */
    public function testResetBit() {
        
        // 0101 1011
        $pret1 = $this->parser->writeByte('TEST_BYTE10', 91);
        $this->assertEquals(ParserReplyCodes::OK, $pret1);
        
        $this->waitOnProcessDataSync();
        
        $this->assertEquals(91, $this->parser->getByte('TEST_BYTE10'));
        
        // 0100 1011
        $pret2 = $this->parser->resetBit('TEST_BIT4');
        $this->assertEquals(ParserReplyCodes::OK, $pret2);
        
        $this->waitOnProcessDataSync();
        
        $this->assertEquals(75, $this->parser->getByte('TEST_BYTE10'));
    }
    
    public function testResetBitWrong1() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('ServerError: Tag does not exist!');
        
        $this->parser->resetBit('TEST_BITn');
    }
    
    public function testResetBitWrong2() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('ServerError: Tag has wrong type!');
        
        $this->parser->resetBit('TEST_BYTE10');
    }
    
    /**
     * Test SetBits function
     */
    public function testSetBits() {
        
        // Check process byte
        $this->assertEquals(0, $this->parser->getByte('TEST_BYTE10'));
        
        // Prepare tag names array
        $tags = array('TEST_BIT0', 'TEST_BIT5', 'TEST_BIT7');
        
        $pret = $this->parser->setBits($tags);
        $this->assertEquals(ParserReplyCodes::OK, $pret);
        
        $this->waitOnProcessDataSync();
        
        $this->assertEquals(161, $this->parser->getByte('TEST_BYTE10'));
    }
    
    public function testSetBitsWrong1() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('ServerError: Tag does not exist!');
        
        // Prepare tag names array
        $tags = array('TEST_BIT0', 'TEST_BIT5', 'TEST_BITe7');
        
        $this->parser->setBits($tags);
    }
    
    public function testSetBitsWrong2() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('ServerError: Tag has wrong type!');
        
        // Prepare tag names array
        $tags = array('TEST_BYTE10', 'TEST_BIT5', 'TEST_BIT7');
        
        $this->parser->setBits($tags);
    }
    
    /**
     * Test InvertBit function
     */
    public function testInvertBit() {
        
        // Check process byte
        $this->assertEquals(0, $this->parser->getByte('TEST_BYTE10'));
        
        $pret = $this->parser->invertBit('TEST_BIT1');
        $this->assertEquals(ParserReplyCodes::OK, $pret);
        
        $this->waitOnProcessDataSync();
        
        $this->assertEquals(2, $this->parser->getByte('TEST_BYTE10'));
    }
    
    public function testInvertBitWrong1() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('ServerError: Tag does not exist!');
                
        $this->parser->invertBit('TEST_BIeT0');
    }
    
    public function testInvertBitWrong2() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('ServerError: Tag has wrong type!');
                
        $this->parser->invertBit('TEST_BYTE10');
    }
    
    /**
     * Test getByte function
     */
    public function testGetByte() {
        
        // Check process byte
        $this->assertEquals(0, $this->parser->getByte('TEST_BYTE10'));
        
        $pret = $this->parser->invertBit('TEST_BIT2');
        $this->assertEquals(ParserReplyCodes::OK, $pret);
        
        $this->waitOnProcessDataSync();
        
        $this->assertEquals(4, $this->parser->getByte('TEST_BYTE10'));
    }
    
    public function testGetByteWrong1() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('ServerError: Tag does not exist!');
        
        // Check process byte
        $this->assertEquals(0, $this->parser->getByte('TEST_BYTEg10'));
    }
    
    public function testGetByteWrong2() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('ServerError: Tag has wrong type!');
        
        // Check process byte
        $this->assertEquals(0, $this->parser->getByte('TEST_BIT5'));
    }
    
    /**
     * Test writeByte function
     */
    public function testWriteByte() {
        
        // Check process byte
        $this->assertEquals(0, $this->parser->getByte('TEST_BYTE10'));
        
        $pret = $this->parser->writeByte('TEST_BYTE10', 58);
        $this->assertEquals(ParserReplyCodes::OK, $pret);
        
        $this->waitOnProcessDataSync();
        
        $this->assertEquals(58, $this->parser->getByte('TEST_BYTE10'));
    }
    
    public function testWriteByteWrong1() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('ServerError: Tag does not exist!');
        
        // Check process byte
        $this->assertEquals(0, $this->parser->getByte('TEST_BYTE10'));
        
        $this->parser->writeByte('TEST_BYTrE10', 58);
    }
    
    public function testWriteByteWrong2() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('ServerError: Tag has wrong type!');
        
        // Check process byte
        $this->assertEquals(0, $this->parser->getByte('TEST_BYTE10'));
        
        $this->parser->writeByte('TEST_BIT3', 58);
    }
    
    /**
     * Test getWord function
     */
    public function testGetWord() {
        
        // Check process word
        $this->assertEquals(0, $this->parser->getWord('TEST_WORD10'));
        
        $pret = $this->parser->writeByte('TEST_BYTE11', 132);
        $this->assertEquals(ParserReplyCodes::OK, $pret);
        
        $this->waitOnProcessDataSync();
        
        $this->assertEquals(33792, $this->parser->getWord('TEST_WORD10'));
    }
    
    public function testGetWordWrong1() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('ServerError: Tag does not exist!');
        
        // Check process word
        $this->assertEquals(0, $this->parser->getWord('TEST_WORDd10'));
    }
    
    public function testGetWordWrong2() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('ServerError: Tag has wrong type!');
        
        // Check process word
        $this->assertEquals(0, $this->parser->getWord('TEST_BIT5'));
    }
    
    /**
     * Test writeWord function
     */
    public function testWriteWord() {
        
        // Check process word
        $this->assertEquals(0, $this->parser->getWord('TEST_WORD10'));
        
        $pret = $this->parser->writeWord('TEST_WORD10', 27900);
        $this->assertEquals(ParserReplyCodes::OK, $pret);
        
        $this->waitOnProcessDataSync();
        
        $this->assertEquals(27900, $this->parser->getWord('TEST_WORD10'));
        $this->assertEquals(252, $this->parser->getByte('TEST_BYTE10'));
        $this->assertEquals(108, $this->parser->getByte('TEST_BYTE11'));
    }
    
    public function testWriteWordWrong1() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('ServerError: Tag does not exist!');
        
        // Check process word
        $this->assertEquals(0, $this->parser->getWord('TEST_WORD10'));
        
        $this->parser->writeWord('TEST_BYTrE10', 27901);
    }
    
    public function testWriteWordWrong2() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('ServerError: Tag has wrong type!');
        
        // Check process word
        $this->assertEquals(0, $this->parser->getWord('TEST_WORD10'));
        
        $this->parser->writeWord('TEST_BYTE11', 27901);
    }
    
    /**
     * Test getDWord function
     */
    public function testGetDWord() {
        
        // Check process dword
        $this->assertEquals(0, $this->parser->getDWord('TEST_DWORD10'));
        
        $pret = $this->parser->writeWord('TEST_WORD12', 27901);
        $this->assertEquals(ParserReplyCodes::OK, $pret);
        
        $this->waitOnProcessDataSync();
        
        $this->assertEquals(1828519936, $this->parser->getDWord('TEST_DWORD10'));
    }
    
    public function testGetDWordWrong1() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('ServerError: Tag does not exist!');
        
        // Check process dword
        $this->assertEquals(0, $this->parser->getDWord('TEST_WORDd10'));
    }
    
    public function testGetDWordWrong2() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('ServerError: Tag has wrong type!');
        
        // Check process word
        $this->assertEquals(0, $this->parser->getDWord('TEST_WORD12'));
    }
    
    /**
     * Test writeDWord function
     */
    public function testWriteDWord() {
        
        // Check process dword
        $this->assertEquals(0, $this->parser->getDWord('TEST_DWORD10'));
        
        $pret = $this->parser->writeDWord('TEST_DWORD10', 1828520016);
        $this->assertEquals(ParserReplyCodes::OK, $pret);
        
        $this->waitOnProcessDataSync();
        
        $this->assertEquals(1828520016, $this->parser->getDWord('TEST_DWORD10'));
        $this->assertEquals(80, $this->parser->getWord('TEST_WORD10'));
        $this->assertEquals(27901, $this->parser->getWord('TEST_WORD12'));
    }
    
    public function testWriteDWordWrong1() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('ServerError: Tag does not exist!');
        
        // Check process dword
        $this->assertEquals(0, $this->parser->getDWord('TEST_DWORD10'));
        
        $this->parser->writeDWord('TEST_DWOwRD10', 27934501);
    }
    
    public function testWriteWordDWrong2() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('ServerError: Tag has wrong type!');
        
        // Check process dword
        $this->assertEquals(0, $this->parser->getDWord('TEST_WORD10'));
        
        $this->parser->writeDWord('TEST_BYTE11', 27901);
    }
    
    /**
     * Test getInt/writeInt function
     */
    public function testGetInt() {
        
        // Check process int
        $this->assertEquals(0, $this->parser->getInt('TEST_INT14'));
        
        $pret = $this->parser->writeInt('TEST_INT14', -3789);
        $this->assertEquals(ParserReplyCodes::OK, $pret);
        
        $this->waitOnProcessDataSync();
        
        $this->assertEquals(-3789, $this->parser->getInt('TEST_INT14'));
    }
    
    public function testGetIntWrong1() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('ServerError: Tag does not exist!');
        
        // Check process int
        $this->assertEquals(0, $this->parser->getInt('TEST_INeT14'));
    }
    
    public function testGetIntWrong2() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('ServerError: Tag has wrong type!');
        
        // Check process int
        $this->assertEquals(0, $this->parser->getInt('TEST_WORD12'));
    }
    
    public function testWriteIntWrong1() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('ServerError: Tag does not exist!');
        
        // Check process int
        $this->assertEquals(0, $this->parser->writeInt('TEST_INeT14', -1359));
    }
    
    public function testWriteIntWrong2() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('ServerError: Tag has wrong type!');
        
        // Check process int
        $this->assertEquals(0, $this->parser->writeInt('TEST_WORD12', -1359));
    }
    
    /**
     * Test getReal/writeReal function
     */
    public function testGetReal() {
        
        // Check process real
        $this->assertEquals(0, $this->parser->getReal('TEST_REAL30'));
        
        $pret = $this->parser->writeReal('TEST_REAL30', -789.048);
        $this->assertEquals(ParserReplyCodes::OK, $pret);
        
        $this->waitOnProcessDataSync();
        
        $this->assertEquals(-789.048, $this->parser->getReal('TEST_REAL30'));
    }
    
    public function testGetRealWrong1() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('ServerError: Tag does not exist!');
        
        // Check process int
        $this->assertEquals(0, $this->parser->getReal('TEST_REALe30'));
    }
    
    public function testGetRealWrong2() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('ServerError: Tag has wrong type!');
        
        // Check process int
        $this->assertEquals(0, $this->parser->getReal('TEST_WORD12'));
    }
    
    public function testWriteRealWrong1() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('ServerError: Tag does not exist!');
        
        // Check process int
        $this->assertEquals(0, $this->parser->writeReal('TEST_REALe30', -1359.78));
    }
    
    public function testWriteRealWrong2() {
        
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage('ServerError: Tag has wrong type!');
        
        // Check process int
        $this->assertEquals(0, $this->parser->writeReal('TEST_WORD12', -1359.78));
    }
}
