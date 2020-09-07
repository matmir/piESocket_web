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
        $pret1 = $this->parser->writeByte('TEST_BYTE10', 91);
        // 1101 1001
        $pret2 = $this->parser->writeByte('MB_TEST_BYTE40', 217);
        
        $this->assertEquals(ParserReplyCodes::OK, $pret1);
        $this->assertEquals(ParserReplyCodes::OK, $pret2);
        
        $this->waitOnProcessDataSync(false);
        
        $this->assertEquals(1, $this->parser->getBit('TEST_BIT0'));
        $this->assertEquals(1, $this->parser->getBit('TEST_BIT1'));
        $this->assertEquals(0, $this->parser->getBit('TEST_BIT2'));
        $this->assertEquals(1, $this->parser->getBit('TEST_BIT3'));
        
        $this->assertEquals(1, $this->parser->getBit('MB_TEST_BIT0'));
        $this->assertEquals(0, $this->parser->getBit('MB_TEST_BIT1'));
        $this->assertEquals(0, $this->parser->getBit('MB_TEST_BIT2'));
        $this->assertEquals(1, $this->parser->getBit('MB_TEST_BIT3'));
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
        $pret1 = $this->parser->writeByte('TEST_BYTE10', 91);
        // 1101 1001
        $pret2 = $this->parser->writeByte('MB_TEST_BYTE40', 217);
        
        $this->assertEquals(ParserReplyCodes::OK, $pret1);
        $this->assertEquals(ParserReplyCodes::OK, $pret2);
        
        $this->waitOnProcessDataSync(false);
        
        // Prepare tag names array
        $tags = array('TEST_BIT4', 'TEST_BIT5', 'TEST_BIT6', 'TEST_BIT7',
                        'MB_TEST_BIT4', 'MB_TEST_BIT5', 'MB_TEST_BIT6', 'MB_TEST_BIT7');
        
        $vals = $this->parser->getBits($tags);
        
        $this->assertEquals(1, $vals[0]);
        $this->assertEquals(0, $vals[1]);
        $this->assertEquals(1, $vals[2]);
        $this->assertEquals(0, $vals[3]);
        
        $this->assertEquals(1, $vals[4]);
        $this->assertEquals(0, $vals[5]);
        $this->assertEquals(1, $vals[6]);
        $this->assertEquals(1, $vals[7]);
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
        $this->assertEquals(0, $this->parser->getByte('MB_TEST_BYTE40'));
        
        $pret1 = $this->parser->setBit('TEST_BIT1');
        $pret2 = $this->parser->setBit('MB_TEST_BIT5');
        
        $this->assertEquals(ParserReplyCodes::OK, $pret1);
        $this->assertEquals(ParserReplyCodes::OK, $pret2);
        
        $this->waitOnProcessDataSync(false);
        
        $this->assertEquals(1, $this->parser->getBit('TEST_BIT1'));
        $this->assertEquals(1, $this->parser->getBit('MB_TEST_BIT5'));
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
        $pret_shm1 = $this->parser->writeByte('TEST_BYTE10', 91);
        // 1101 1001
        $pret_mb1 = $this->parser->writeByte('MB_TEST_BYTE40', 217);
        
        $this->assertEquals(ParserReplyCodes::OK, $pret_shm1);
        $this->assertEquals(ParserReplyCodes::OK, $pret_mb1);
        
        $this->waitOnProcessDataSync(false);
        
        $this->assertEquals(91, $this->parser->getByte('TEST_BYTE10'));
        $this->assertEquals(217, $this->parser->getByte('MB_TEST_BYTE40'));
        
        // 0100 1011
        $pret_shm2 = $this->parser->resetBit('TEST_BIT4');
        // 1101 0001
        $pret_mb2 = $this->parser->resetBit('MB_TEST_BIT3');
        
        $this->assertEquals(ParserReplyCodes::OK, $pret_shm2);
        $this->assertEquals(ParserReplyCodes::OK, $pret_mb2);
        
        $this->waitOnProcessDataSync(false);
        
        $this->assertEquals(75, $this->parser->getByte('TEST_BYTE10'));
        $this->assertEquals(209, $this->parser->getByte('MB_TEST_BYTE40'));
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
        $this->assertEquals(0, $this->parser->getByte('MB_TEST_BYTE40'));
        
        // Prepare tag names array
        $tags = array('TEST_BIT0', 'TEST_BIT5', 'TEST_BIT7',
                        'MB_TEST_BIT2', 'MB_TEST_BIT4', 'MB_TEST_BIT6');
        
        $pret = $this->parser->setBits($tags);
        $this->assertEquals(ParserReplyCodes::OK, $pret);
        
        $this->waitOnProcessDataSync(false);
        
        $this->assertEquals(161, $this->parser->getByte('TEST_BYTE10'));
        $this->assertEquals(84, $this->parser->getByte('MB_TEST_BYTE40'));
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
        $this->assertEquals(0, $this->parser->getByte('MB_TEST_BYTE40'));
        
        $pret1 = $this->parser->invertBit('TEST_BIT1');
        $pret2 = $this->parser->invertBit('MB_TEST_BIT5');
        
        $this->assertEquals(ParserReplyCodes::OK, $pret1);
        $this->assertEquals(ParserReplyCodes::OK, $pret2);
        
        $this->waitOnProcessDataSync(false);
        
        $this->assertEquals(2, $this->parser->getByte('TEST_BYTE10'));
        $this->assertEquals(32, $this->parser->getByte('MB_TEST_BYTE40'));
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
        $this->assertEquals(0, $this->parser->getByte('MB_TEST_BYTE40'));
        
        $pret1 = $this->parser->invertBit('TEST_BIT2');
        $pret2 = $this->parser->invertBit('MB_TEST_BIT1');
        
        $this->assertEquals(ParserReplyCodes::OK, $pret1);
        $this->assertEquals(ParserReplyCodes::OK, $pret2);
        
        $this->waitOnProcessDataSync(false);
        
        $this->assertEquals(4, $this->parser->getByte('TEST_BYTE10'));
        $this->assertEquals(2, $this->parser->getByte('MB_TEST_BYTE40'));
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
        $this->assertEquals(0, $this->parser->getByte('MB_TEST_BYTE40'));
        
        $pret1 = $this->parser->writeByte('TEST_BYTE10', 58);
        $pret2 = $this->parser->writeByte('MB_TEST_BYTE40', 64);
        
        $this->assertEquals(ParserReplyCodes::OK, $pret1);
        $this->assertEquals(ParserReplyCodes::OK, $pret2);
        
        $this->waitOnProcessDataSync(false);
        
        $this->assertEquals(58, $this->parser->getByte('TEST_BYTE10'));
        $this->assertEquals(64, $this->parser->getByte('MB_TEST_BYTE40'));
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
        $this->assertEquals(0, $this->parser->getWord('MB_TEST_WORD40'));
        
        $pret1 = $this->parser->writeByte('TEST_BYTE11', 132);
        $pret2 = $this->parser->writeByte('MB_TEST_BYTE41', 74);
        
        $this->assertEquals(ParserReplyCodes::OK, $pret1);
        $this->assertEquals(ParserReplyCodes::OK, $pret2);
        
        $this->waitOnProcessDataSync(false);
        
        $this->assertEquals(33792, $this->parser->getWord('TEST_WORD10'));
        $this->assertEquals(18944, $this->parser->getWord('MB_TEST_WORD40'));
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
        $this->assertEquals(0, $this->parser->getWord('MB_TEST_WORD40'));
        
        $pret1 = $this->parser->writeWord('TEST_WORD10', 27900);
        $pret2 = $this->parser->writeWord('MB_TEST_WORD40', 55843);
        
        $this->assertEquals(ParserReplyCodes::OK, $pret1);
        $this->assertEquals(ParserReplyCodes::OK, $pret2);
        
        $this->waitOnProcessDataSync(false);
        
        $this->assertEquals(27900, $this->parser->getWord('TEST_WORD10'));
        $this->assertEquals(252, $this->parser->getByte('TEST_BYTE10'));
        $this->assertEquals(108, $this->parser->getByte('TEST_BYTE11'));
        
        $this->assertEquals(55843, $this->parser->getWord('MB_TEST_WORD40'));
        $this->assertEquals(35, $this->parser->getByte('MB_TEST_BYTE40'));
        $this->assertEquals(218, $this->parser->getByte('MB_TEST_BYTE41'));
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
        $this->assertEquals(0, $this->parser->getDWord('MB_TEST_DWORD40'));
        
        $pret1 = $this->parser->writeWord('TEST_WORD12', 27901);
        $pret2 = $this->parser->writeWord('MB_TEST_WORD42', 55844);
        
        $this->assertEquals(ParserReplyCodes::OK, $pret1);
        $this->assertEquals(ParserReplyCodes::OK, $pret2);
        
        $this->waitOnProcessDataSync(false);
        
        $this->assertEquals(1828519936, $this->parser->getDWord('TEST_DWORD10'));
        $this->assertEquals(3659792384, $this->parser->getDWord('MB_TEST_DWORD40'));
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
        $this->assertEquals(0, $this->parser->getDWord('MB_TEST_DWORD40'));
        
        $pret1 = $this->parser->writeDWord('TEST_DWORD10', 1828520016);
        $pret2 = $this->parser->writeDWord('MB_TEST_DWORD40', 3659793152);
        
        $this->assertEquals(ParserReplyCodes::OK, $pret1);
        $this->assertEquals(ParserReplyCodes::OK, $pret2);
        
        $this->waitOnProcessDataSync(false);
        
        $this->assertEquals(1828520016, $this->parser->getDWord('TEST_DWORD10'));
        $this->assertEquals(80, $this->parser->getWord('TEST_WORD10'));
        $this->assertEquals(27901, $this->parser->getWord('TEST_WORD12'));
        
        $this->assertEquals(3659793152, $this->parser->getDWord('MB_TEST_DWORD40'));
        $this->assertEquals(768, $this->parser->getWord('MB_TEST_WORD40'));
        $this->assertEquals(55844, $this->parser->getWord('MB_TEST_WORD42'));
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
        $this->assertEquals(0, $this->parser->getInt('MB_TEST_INT40'));
        
        $pret1 = $this->parser->writeInt('TEST_INT14', -3789);
        $pret2 = $this->parser->writeInt('MB_TEST_INT40', -6773);
        
        $this->assertEquals(ParserReplyCodes::OK, $pret1);
        $this->assertEquals(ParserReplyCodes::OK, $pret2);
        
        $this->waitOnProcessDataSync(false);
        
        $this->assertEquals(-3789, $this->parser->getInt('TEST_INT14'));
        $this->assertEquals(-6773, $this->parser->getInt('MB_TEST_INT40'));
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
        $this->assertEquals(0, $this->parser->getReal('MB_TEST_REAL44'));
        
        $pret1 = $this->parser->writeReal('TEST_REAL30', -789.048);
        $pret2 = $this->parser->writeReal('MB_TEST_REAL44', -106.007);
        
        $this->assertEquals(ParserReplyCodes::OK, $pret1);
        $this->assertEquals(ParserReplyCodes::OK, $pret2);
        
        $this->waitOnProcessDataSync(false);
        
        $this->assertEquals(-789.048, $this->parser->getReal('TEST_REAL30'));
        $this->assertEquals(-106.007, $this->parser->getReal('MB_TEST_REAL44'));
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
    
    public function testInputData1() {
        
        // Check SHM input data
        $this->assertEquals(0, $this->parser->getBit('TEST_LOG_BIT2'));
        $this->assertEquals(0, $this->parser->getByte('TEST_LOG_BYTE1'));
        $this->assertEquals(0, $this->parser->getWord('TEST_LOG_WORD1'));
        $this->assertEquals(0, $this->parser->getDWord('TEST_LOG_DWORD1'));
        $this->assertEquals(0, $this->parser->getInt('TEST_LOG_INT1'));
        $this->assertEquals(0, $this->parser->getReal('TEST_LOG_REAL1'));
        
        // Check Modbus input data
        $this->assertEquals(0, $this->parser->getBit('MB_TEST_LOG_BIT1'));
        $this->assertEquals(0, $this->parser->getByte('MB_TEST_LOG_BYTE1'));
        $this->assertEquals(0, $this->parser->getWord('MB_TEST_LOG_WORD1'));
        $this->assertEquals(0, $this->parser->getDWord('MB_TEST_LOG_DWORD1'));
        $this->assertEquals(0, $this->parser->getInt('MB_TEST_LOG_INT1'));
        $this->assertEquals(0, $this->parser->getReal('MB_TEST_LOG_REAL1'));
        
        // Activate first data set
        $tags1 = array('TEST_LOG_SIM1', 'MB_TEST_LOG_SIM1');
        
        $pret1 = $this->parser->setBits($tags1);
        $this->assertEquals(ParserReplyCodes::OK, $pret1);
        
        $this->waitOnProcessDataSync(false);
        
        // Check SHM input data
        $this->assertEquals(0, $this->parser->getBit('TEST_LOG_BIT2'));
        $this->assertEquals(55, $this->parser->getByte('TEST_LOG_BYTE1'));
        $this->assertEquals(12120, $this->parser->getWord('TEST_LOG_WORD1'));
        $this->assertEquals(558654, $this->parser->getDWord('TEST_LOG_DWORD1'));
        $this->assertEquals(-1201, $this->parser->getInt('TEST_LOG_INT1'));
        $this->assertEquals(3.79, $this->parser->getReal('TEST_LOG_REAL1'));
        
        // Check Modbus input data
        $this->assertEquals(1, $this->parser->getBit('MB_TEST_LOG_BIT1'));
        $this->assertEquals(210, $this->parser->getByte('MB_TEST_LOG_BYTE1'));
        $this->assertEquals(20478, $this->parser->getWord('MB_TEST_LOG_WORD1'));
        $this->assertEquals(784566, $this->parser->getDWord('MB_TEST_LOG_DWORD1'));
        $this->assertEquals(-410, $this->parser->getInt('MB_TEST_LOG_INT1'));
        $this->assertEquals(3.07, $this->parser->getReal('MB_TEST_LOG_REAL1'));
        
        // Activate second data set
        $tags2 = array('TEST_LOG_DATA1', 'MB_TEST_LOG_DATA1');
        
        $pret2 = $this->parser->setBits($tags2);
        $this->assertEquals(ParserReplyCodes::OK, $pret2);
        
        $this->waitOnProcessDataSync(false);
        
        // Check SHM input data
        $this->assertEquals(1, $this->parser->getBit('TEST_LOG_BIT2'));
        $this->assertEquals(150, $this->parser->getByte('TEST_LOG_BYTE1'));
        $this->assertEquals(35120, $this->parser->getWord('TEST_LOG_WORD1'));
        $this->assertEquals(158654, $this->parser->getDWord('TEST_LOG_DWORD1'));
        $this->assertEquals(-1200, $this->parser->getInt('TEST_LOG_INT1'));
        $this->assertEquals(3.78, $this->parser->getReal('TEST_LOG_REAL1'));
        
        // Check Modbus input data
        $this->assertEquals(0, $this->parser->getBit('MB_TEST_LOG_BIT1'));
        $this->assertEquals(37, $this->parser->getByte('MB_TEST_LOG_BYTE1'));
        $this->assertEquals(42001, $this->parser->getWord('MB_TEST_LOG_WORD1'));
        $this->assertEquals(158653, $this->parser->getDWord('MB_TEST_LOG_DWORD1'));
        $this->assertEquals(-1300, $this->parser->getInt('MB_TEST_LOG_INT1'));
        $this->assertEquals(9.78, $this->parser->getReal('MB_TEST_LOG_REAL1'));
    }
}
