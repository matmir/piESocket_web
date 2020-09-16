<?php

namespace App\Tests\Func;

use App\Tests\TagLoggerFunctionTestCase;
use App\Entity\Admin\TagLoggerInterval;
use App\Entity\Admin\TagType;

/**
 * Function tests for Tag logger
 *
 * @author Mateusz Mirosławski
 */
class TagLoggerTest extends TagLoggerFunctionTestCase
{
    /**
     * Test BIT logger
     */
    public function testBit1()
    {
        // Get logger
        $logger = $this->getLogger(TagType::BIT);
        
        // Get logger data
        $params = array(
            'loggerID' => $logger->getId(),
            'sortMode' => 'lastData',
            'sortData' => array()
        );
        
        $data1 = $this->chartReader->getData($params);
        
        // Activate logger
        $this->loggerMapper->enableLogger($logger->getId());
        // Wait on data
        usleep(300000);
        // Deactivate logger
        $this->loggerMapper->enableLogger($logger->getId(), false);
        
        $data2 = $this->chartReader->getData($params);
        
        $this->assertEquals(TagLoggerInterval::I_100MS, $logger->getInterval());
        $this->assertEquals(TagType::BIT, $logger->getTag()->getType());
        
        $this->assertEquals('stepped', $data1['chartType']);
        $this->assertEquals('TEST_LOG_BIT1', $data1['dataTitle']);
        $this->assertTrue(empty($data1['data']['x']));
        $this->assertTrue(empty($data1['data']['y']));
        
        $this->assertEquals('stepped', $data2['chartType']);
        $this->assertEquals('TEST_LOG_BIT1', $data2['dataTitle']);
        $this->assertTrue((count($data2['data']['x']) >= 1) ? (true) : (false));
        $this->assertTrue((count($data2['data']['y']) >= 1) ? (true) : (false));
    }
    
    public function testBit2()
    {
        // Get logger - 'on change'
        $logger = $this->getLogger(TagType::BIT, false);
        
        // Get logger data
        $params = array(
            'loggerID' => $logger->getId(),
            'sortMode' => 'lastData',
            'sortData' => array()
        );
        
        $data1 = $this->chartReader->getData($params);
        
        // Activate logger
        $this->loggerMapper->enableLogger($logger->getId());
        // Trigger data change
        $this->parser->setBit('TEST_LOG_DATA1');
        $this->waitOnProcessDataSync();
        // Wait on data
        usleep(200000);
        
        $data2 = $this->chartReader->getData($params);
        
        // Trigger data change
        $this->parser->resetBit('TEST_LOG_DATA1');
        $this->waitOnProcessDataSync();
        // Wait on data
        usleep(200000);
        // Deactivate logger
        $this->loggerMapper->enableLogger($logger->getId(), false);
        
        $data3 = $this->chartReader->getData($params);
        
        $this->assertEquals(TagLoggerInterval::I_ON_CHANGE, $logger->getInterval());
        $this->assertEquals(TagType::BIT, $logger->getTag()->getType());
        
        $this->assertEquals('stepped', $data1['chartType']);
        $this->assertEquals('TEST_LOG_BIT2', $data1['dataTitle']);
        $this->assertTrue(empty($data1['data']['x']));
        $this->assertTrue(empty($data1['data']['y']));
        
        $this->assertEquals('stepped', $data2['chartType']);
        $this->assertEquals('TEST_LOG_BIT2', $data2['dataTitle']);
        $this->assertEquals(1, count($data2['data']['x']));
        $this->assertEquals(1, count($data2['data']['y']));
        $this->assertEquals(1, $data2['data']['y'][0]);
        
        $this->assertEquals('stepped', $data3['chartType']);
        $this->assertEquals('TEST_LOG_BIT2', $data3['dataTitle']);
        $this->assertEquals(2, count($data3['data']['x']));
        $this->assertEquals(2, count($data3['data']['y']));
        $this->assertEquals(1, $data3['data']['y'][0]);
        $this->assertEquals(0, $data3['data']['y'][1]);
    }
    
    /**
     * Test BYTE logger
     */
    public function testByte1()
    {
        // Get logger
        $logger = $this->getLogger(TagType::BYTE);
        
        // Get logger data
        $params = array(
            'loggerID' => $logger->getId(),
            'sortMode' => 'lastData',
            'sortData' => array()
        );
        
        $data1 = $this->chartReader->getData($params);
        
        // Activate logger
        $this->loggerMapper->enableLogger($logger->getId());
        // Wait on data
        usleep(300000);
        // Read data
        $data2 = $this->chartReader->getData($params);
        
        // Trigger data change
        $this->parser->setBit('TEST_LOG_DATA1');
        $this->waitOnProcessDataSync();
        // Wait on data
        usleep(300000);
        
        // Read data
        $data3 = $this->chartReader->getData($params);
        
        // Deactivate logger
        $this->loggerMapper->enableLogger($logger->getId(), false);
        
        $this->assertEquals(TagLoggerInterval::I_100MS, $logger->getInterval());
        $this->assertEquals(TagType::BYTE, $logger->getTag()->getType());
        
        $this->assertEquals('line', $data1['chartType']);
        $this->assertEquals('TEST_LOG_BYTE1', $data1['dataTitle']);
        $this->assertTrue(empty($data1['data']['x']));
        $this->assertTrue(empty($data1['data']['y']));
        
        $this->assertEquals('line', $data2['chartType']);
        $this->assertEquals('TEST_LOG_BYTE1', $data2['dataTitle']);
        $this->assertTrue((count($data2['data']['x']) >= 1) ? (true) : (false));
        $this->assertTrue((count($data2['data']['y']) >= 1) ? (true) : (false));
        $this->assertEquals(55, $data2['data']['y'][0]);
        $this->assertEquals(55, $data2['data']['y'][1]);
        
        $this->assertEquals('line', $data3['chartType']);
        $this->assertEquals('TEST_LOG_BYTE1', $data3['dataTitle']);
        $this->assertTrue((count($data3['data']['x']) >= 2) ? (true) : (false));
        $this->assertTrue((count($data3['data']['y']) >= 2) ? (true) : (false));
        // Get last value
        $dtVal = count($data3['data']['y']) - 1;
        $this->assertEquals(150, $data3['data']['y'][$dtVal]);
    }
    
    public function testByte2()
    {
        // Get logger - 'on change'
        $logger = $this->getLogger(TagType::BYTE, false);
        
        // Get logger data
        $params = array(
            'loggerID' => $logger->getId(),
            'sortMode' => 'lastData',
            'sortData' => array()
        );
        
        $data1 = $this->chartReader->getData($params);
        
        // Activate logger
        $this->loggerMapper->enableLogger($logger->getId());
        // Wait on first data (byte has default 0, afrter activate 210)
        usleep(100000);
        // Trigger data change
        $this->parser->setBit('TEST_LOG_DATA1');
        $this->waitOnProcessDataSync();
        // Wait on data
        usleep(100000);
        
        $data2 = $this->chartReader->getData($params);
        
        // Trigger data change
        $this->parser->resetBit('TEST_LOG_DATA1');
        $this->waitOnProcessDataSync();
        // Wait on data
        usleep(200000);
        // Deactivate logger
        $this->loggerMapper->enableLogger($logger->getId(), false);
        
        $data3 = $this->chartReader->getData($params);
        
        $this->assertEquals(TagLoggerInterval::I_ON_CHANGE, $logger->getInterval());
        $this->assertEquals(TagType::BYTE, $logger->getTag()->getType());
        
        $this->assertEquals('line', $data1['chartType']);
        $this->assertEquals('TEST_LOG_BYTE2', $data1['dataTitle']);
        $this->assertTrue(empty($data1['data']['x']));
        $this->assertTrue(empty($data1['data']['y']));
        
        $this->assertEquals('line', $data2['chartType']);
        $this->assertEquals('TEST_LOG_BYTE2', $data2['dataTitle']);
        $this->assertEquals(2, count($data2['data']['x']));
        $this->assertEquals(2, count($data2['data']['y']));
        $this->assertEquals(210, $data2['data']['y'][0]);
        $this->assertEquals(250, $data2['data']['y'][1]);
        
        $this->assertEquals('line', $data3['chartType']);
        $this->assertEquals('TEST_LOG_BYTE2', $data3['dataTitle']);
        $this->assertEquals(3, count($data3['data']['x']));
        $this->assertEquals(3, count($data3['data']['y']));
        $this->assertEquals(210, $data3['data']['y'][0]);
        $this->assertEquals(250, $data3['data']['y'][1]);
        $this->assertEquals(210, $data3['data']['y'][2]);
    }
    
    /**
     * Test WORD logger
     */
    public function testWord1()
    {
        // Get logger
        $logger = $this->getLogger(TagType::WORD);
        
        // Get logger data
        $params = array(
            'loggerID' => $logger->getId(),
            'sortMode' => 'lastData',
            'sortData' => array()
        );
        
        $data1 = $this->chartReader->getData($params);
        
        // Activate logger
        $this->loggerMapper->enableLogger($logger->getId());
        // Wait on data
        usleep(300000);
        // Read data
        $data2 = $this->chartReader->getData($params);
        
        // Trigger data change
        $this->parser->setBit('TEST_LOG_DATA1');
        $this->waitOnProcessDataSync();
        // Wait on data
        usleep(300000);
        
        // Read data
        $data3 = $this->chartReader->getData($params);
        
        // Deactivate logger
        $this->loggerMapper->enableLogger($logger->getId(), false);
        
        $this->assertEquals(TagLoggerInterval::I_100MS, $logger->getInterval());
        $this->assertEquals(TagType::WORD, $logger->getTag()->getType());
        
        $this->assertEquals('line', $data1['chartType']);
        $this->assertEquals('TEST_LOG_WORD1', $data1['dataTitle']);
        $this->assertTrue(empty($data1['data']['x']));
        $this->assertTrue(empty($data1['data']['y']));
        
        $this->assertEquals('line', $data2['chartType']);
        $this->assertEquals('TEST_LOG_WORD1', $data2['dataTitle']);
        $this->assertTrue((count($data2['data']['x']) >= 1) ? (true) : (false));
        $this->assertTrue((count($data2['data']['y']) >= 1) ? (true) : (false));
        $this->assertEquals(12120, $data2['data']['y'][0]);
        $this->assertEquals(12120, $data2['data']['y'][1]);
        
        $this->assertEquals('line', $data3['chartType']);
        $this->assertEquals('TEST_LOG_WORD1', $data3['dataTitle']);
        $this->assertTrue((count($data3['data']['x']) >= 2) ? (true) : (false));
        $this->assertTrue((count($data3['data']['y']) >= 2) ? (true) : (false));
        // Get last value
        $dtVal = count($data3['data']['y']) - 1;
        $this->assertEquals(35120, $data3['data']['y'][$dtVal]);
    }
    
    public function testWord2()
    {
        // Get logger - 'on change'
        $logger = $this->getLogger(TagType::WORD, false);
        
        // Get logger data
        $params = array(
            'loggerID' => $logger->getId(),
            'sortMode' => 'lastData',
            'sortData' => array()
        );
        
        $data1 = $this->chartReader->getData($params);
        
        // Activate logger
        $this->loggerMapper->enableLogger($logger->getId());
        // Wait on first data (word has default 0)
        usleep(100000);
        // Trigger data change
        $this->parser->setBit('TEST_LOG_DATA1');
        $this->waitOnProcessDataSync();
        // Wait on data
        usleep(100000);
        
        $data2 = $this->chartReader->getData($params);
        
        // Trigger data change
        $this->parser->resetBit('TEST_LOG_DATA1');
        $this->waitOnProcessDataSync();
        // Wait on data
        usleep(200000);
        // Deactivate logger
        $this->loggerMapper->enableLogger($logger->getId(), false);
        
        $data3 = $this->chartReader->getData($params);
        
        $this->assertEquals(TagLoggerInterval::I_ON_CHANGE, $logger->getInterval());
        $this->assertEquals(TagType::WORD, $logger->getTag()->getType());
        
        $this->assertEquals('line', $data1['chartType']);
        $this->assertEquals('TEST_LOG_WORD2', $data1['dataTitle']);
        $this->assertTrue(empty($data1['data']['x']));
        $this->assertTrue(empty($data1['data']['y']));
        
        $this->assertEquals('line', $data2['chartType']);
        $this->assertEquals('TEST_LOG_WORD2', $data2['dataTitle']);
        $this->assertEquals(2, count($data2['data']['x']));
        $this->assertEquals(2, count($data2['data']['y']));
        $this->assertEquals(35120, $data2['data']['y'][0]);
        $this->assertEquals(15120, $data2['data']['y'][1]);
        
        $this->assertEquals('line', $data3['chartType']);
        $this->assertEquals('TEST_LOG_WORD2', $data3['dataTitle']);
        $this->assertEquals(3, count($data3['data']['x']));
        $this->assertEquals(3, count($data3['data']['y']));
        $this->assertEquals(35120, $data3['data']['y'][0]);
        $this->assertEquals(15120, $data3['data']['y'][1]);
        $this->assertEquals(35120, $data3['data']['y'][2]);
    }
    
    /**
     * Test DWORD logger
     */
    public function testDWord1()
    {
        // Get logger
        $logger = $this->getLogger(TagType::DWORD);
        
        // Get logger data
        $params = array(
            'loggerID' => $logger->getId(),
            'sortMode' => 'lastData',
            'sortData' => array()
        );
        
        $data1 = $this->chartReader->getData($params);
        
        // Activate logger
        $this->loggerMapper->enableLogger($logger->getId());
        // Wait on data
        usleep(300000);
        // Read data
        $data2 = $this->chartReader->getData($params);
        
        // Trigger data change
        $this->parser->setBit('TEST_LOG_DATA1');
        $this->waitOnProcessDataSync();
        // Wait on data
        usleep(300000);
        
        // Read data
        $data3 = $this->chartReader->getData($params);
        
        // Deactivate logger
        $this->loggerMapper->enableLogger($logger->getId(), false);
        
        $this->assertEquals(TagLoggerInterval::I_100MS, $logger->getInterval());
        $this->assertEquals(TagType::DWORD, $logger->getTag()->getType());
        
        $this->assertEquals('line', $data1['chartType']);
        $this->assertEquals('TEST_LOG_DWORD1', $data1['dataTitle']);
        $this->assertTrue(empty($data1['data']['x']));
        $this->assertTrue(empty($data1['data']['y']));
        
        $this->assertEquals('line', $data2['chartType']);
        $this->assertEquals('TEST_LOG_DWORD1', $data2['dataTitle']);
        $this->assertTrue((count($data2['data']['x']) >= 1) ? (true) : (false));
        $this->assertTrue((count($data2['data']['y']) >= 1) ? (true) : (false));
        $this->assertEquals(558654, $data2['data']['y'][0]);
        $this->assertEquals(558654, $data2['data']['y'][1]);
        
        $this->assertEquals('line', $data3['chartType']);
        $this->assertEquals('TEST_LOG_DWORD1', $data3['dataTitle']);
        $this->assertTrue((count($data3['data']['x']) >= 2) ? (true) : (false));
        $this->assertTrue((count($data3['data']['y']) >= 2) ? (true) : (false));
        // Get last value
        $dtVal = count($data3['data']['y']) - 1;
        $this->assertEquals(158654, $data3['data']['y'][$dtVal]);
    }
    
    public function testDWord2()
    {
        // Get logger - 'on change'
        $logger = $this->getLogger(TagType::DWORD, false);
        
        // Get logger data
        $params = array(
            'loggerID' => $logger->getId(),
            'sortMode' => 'lastData',
            'sortData' => array()
        );
        
        $data1 = $this->chartReader->getData($params);
        
        // Activate logger
        $this->loggerMapper->enableLogger($logger->getId());
        // Wait on first data (dword has default 0)
        usleep(100000);
        // Trigger data change
        $this->parser->setBit('TEST_LOG_DATA1');
        $this->waitOnProcessDataSync();
        // Wait on data
        usleep(100000);
        
        $data2 = $this->chartReader->getData($params);
        
        // Trigger data change
        $this->parser->resetBit('TEST_LOG_DATA1');
        $this->waitOnProcessDataSync();
        // Wait on data
        usleep(200000);
        // Deactivate logger
        $this->loggerMapper->enableLogger($logger->getId(), false);
        
        $data3 = $this->chartReader->getData($params);
        
        $this->assertEquals(TagLoggerInterval::I_ON_CHANGE, $logger->getInterval());
        $this->assertEquals(TagType::DWORD, $logger->getTag()->getType());
        
        $this->assertEquals('line', $data1['chartType']);
        $this->assertEquals('TEST_LOG_DWORD2', $data1['dataTitle']);
        $this->assertTrue(empty($data1['data']['x']));
        $this->assertTrue(empty($data1['data']['y']));
        
        $this->assertEquals('line', $data2['chartType']);
        $this->assertEquals('TEST_LOG_DWORD2', $data2['dataTitle']);
        $this->assertEquals(2, count($data2['data']['x']));
        $this->assertEquals(2, count($data2['data']['y']));
        $this->assertEquals(658654, $data2['data']['y'][0]);
        $this->assertEquals(258654, $data2['data']['y'][1]);
        
        $this->assertEquals('line', $data3['chartType']);
        $this->assertEquals('TEST_LOG_DWORD2', $data3['dataTitle']);
        $this->assertEquals(3, count($data3['data']['x']));
        $this->assertEquals(3, count($data3['data']['y']));
        $this->assertEquals(658654, $data3['data']['y'][0]);
        $this->assertEquals(258654, $data3['data']['y'][1]);
        $this->assertEquals(658654, $data3['data']['y'][2]);
    }
    
    /**
     * Test INT logger
     */
    public function testInt1()
    {
        // Get logger
        $logger = $this->getLogger(TagType::INT);
        
        // Get logger data
        $params = array(
            'loggerID' => $logger->getId(),
            'sortMode' => 'lastData',
            'sortData' => array()
        );
        
        $data1 = $this->chartReader->getData($params);
        
        // Activate logger
        $this->loggerMapper->enableLogger($logger->getId());
        // Wait on data
        usleep(300000);
        // Read data
        $data2 = $this->chartReader->getData($params);
        
        // Trigger data change
        $this->parser->setBit('TEST_LOG_DATA1');
        $this->waitOnProcessDataSync();
        // Wait on data
        usleep(300000);
        
        // Read data
        $data3 = $this->chartReader->getData($params);
        
        // Deactivate logger
        $this->loggerMapper->enableLogger($logger->getId(), false);
        
        $this->assertEquals(TagLoggerInterval::I_100MS, $logger->getInterval());
        $this->assertEquals(TagType::INT, $logger->getTag()->getType());
        
        $this->assertEquals('line', $data1['chartType']);
        $this->assertEquals('TEST_LOG_INT1', $data1['dataTitle']);
        $this->assertTrue(empty($data1['data']['x']));
        $this->assertTrue(empty($data1['data']['y']));
        
        $this->assertEquals('line', $data2['chartType']);
        $this->assertEquals('TEST_LOG_INT1', $data2['dataTitle']);
        $this->assertTrue((count($data2['data']['x']) >= 1) ? (true) : (false));
        $this->assertTrue((count($data2['data']['y']) >= 1) ? (true) : (false));
        $this->assertEquals(-1201, $data2['data']['y'][0]);
        $this->assertEquals(-1201, $data2['data']['y'][1]);
        
        $this->assertEquals('line', $data3['chartType']);
        $this->assertEquals('TEST_LOG_INT1', $data3['dataTitle']);
        $this->assertTrue((count($data3['data']['x']) >= 2) ? (true) : (false));
        $this->assertTrue((count($data3['data']['y']) >= 2) ? (true) : (false));
        // Get last value
        $dtVal = count($data3['data']['y']) - 1;
        $this->assertEquals(-1200, $data3['data']['y'][$dtVal]);
    }
    
    public function testINT2()
    {
        // Get logger - 'on change'
        $logger = $this->getLogger(TagType::INT, false);
        
        // Get logger data
        $params = array(
            'loggerID' => $logger->getId(),
            'sortMode' => 'lastData',
            'sortData' => array()
        );
        
        $data1 = $this->chartReader->getData($params);
        
        // Activate logger
        $this->loggerMapper->enableLogger($logger->getId());
        // Wait on first data (int has default 0)
        usleep(100000);
        // Trigger data change
        $this->parser->setBit('TEST_LOG_DATA1');
        $this->waitOnProcessDataSync();
        // Wait on data
        usleep(100000);
        
        $data2 = $this->chartReader->getData($params);
        
        // Trigger data change
        $this->parser->resetBit('TEST_LOG_DATA1');
        $this->waitOnProcessDataSync();
        // Wait on data
        usleep(200000);
        // Deactivate logger
        $this->loggerMapper->enableLogger($logger->getId(), false);
        
        $data3 = $this->chartReader->getData($params);
        
        $this->assertEquals(TagLoggerInterval::I_ON_CHANGE, $logger->getInterval());
        $this->assertEquals(TagType::INT, $logger->getTag()->getType());
        
        $this->assertEquals('line', $data1['chartType']);
        $this->assertEquals('TEST_LOG_INT2', $data1['dataTitle']);
        $this->assertTrue(empty($data1['data']['x']));
        $this->assertTrue(empty($data1['data']['y']));
        
        $this->assertEquals('line', $data2['chartType']);
        $this->assertEquals('TEST_LOG_INT2', $data2['dataTitle']);
        $this->assertEquals(2, count($data2['data']['x']));
        $this->assertEquals(2, count($data2['data']['y']));
        $this->assertEquals(-12, $data2['data']['y'][0]);
        $this->assertEquals(-10, $data2['data']['y'][1]);
        
        $this->assertEquals('line', $data3['chartType']);
        $this->assertEquals('TEST_LOG_INT2', $data3['dataTitle']);
        $this->assertEquals(3, count($data3['data']['x']));
        $this->assertEquals(3, count($data3['data']['y']));
        $this->assertEquals(-12, $data3['data']['y'][0]);
        $this->assertEquals(-10, $data3['data']['y'][1]);
        $this->assertEquals(-12, $data3['data']['y'][2]);
    }
    
    /**
     * Test REAL logger
     */
    public function testReal1()
    {
        // Get logger
        $logger = $this->getLogger(TagType::REAL);
        
        // Get logger data
        $params = array(
            'loggerID' => $logger->getId(),
            'sortMode' => 'lastData',
            'sortData' => array()
        );
        
        $data1 = $this->chartReader->getData($params);
        
        // Activate logger
        $this->loggerMapper->enableLogger($logger->getId());
        // Wait on data
        usleep(300000);
        // Read data
        $data2 = $this->chartReader->getData($params);
        
        // Trigger data change
        $this->parser->setBit('TEST_LOG_DATA1');
        $this->waitOnProcessDataSync();
        // Wait on data
        usleep(300000);
        
        // Read data
        $data3 = $this->chartReader->getData($params);
        
        // Deactivate logger
        $this->loggerMapper->enableLogger($logger->getId(), false);
        
        $this->assertEquals(TagLoggerInterval::I_100MS, $logger->getInterval());
        $this->assertEquals(TagType::REAL, $logger->getTag()->getType());
        
        $this->assertEquals('line', $data1['chartType']);
        $this->assertEquals('TEST_LOG_REAL1', $data1['dataTitle']);
        $this->assertTrue(empty($data1['data']['x']));
        $this->assertTrue(empty($data1['data']['y']));
        
        $this->assertEquals('line', $data2['chartType']);
        $this->assertEquals('TEST_LOG_REAL1', $data2['dataTitle']);
        $this->assertTrue((count($data2['data']['x']) >= 1) ? (true) : (false));
        $this->assertTrue((count($data2['data']['y']) >= 1) ? (true) : (false));
        $this->assertEquals(3.79, $data2['data']['y'][0]);
        $this->assertEquals(3.79, $data2['data']['y'][1]);
        
        $this->assertEquals('line', $data3['chartType']);
        $this->assertEquals('TEST_LOG_REAL1', $data3['dataTitle']);
        $this->assertTrue((count($data3['data']['x']) >= 2) ? (true) : (false));
        $this->assertTrue((count($data3['data']['y']) >= 2) ? (true) : (false));
        // Get last value
        $dtVal = count($data3['data']['y']) - 1;
        $this->assertEquals(3.78, $data3['data']['y'][$dtVal]);
    }
    
    public function testReal2()
    {
        // Get logger - 'on change'
        $logger = $this->getLogger(TagType::REAL, false);
        
        // Get logger data
        $params = array(
            'loggerID' => $logger->getId(),
            'sortMode' => 'lastData',
            'sortData' => array()
        );
        
        $data1 = $this->chartReader->getData($params);
        
        // Activate logger
        $this->loggerMapper->enableLogger($logger->getId());
        // Wait on first data (real has default 0)
        usleep(100000);
        // Trigger data change
        $this->parser->setBit('TEST_LOG_DATA1');
        $this->waitOnProcessDataSync();
        // Wait on data
        usleep(100000);
        
        $data2 = $this->chartReader->getData($params);
        
        // Trigger data change
        $this->parser->resetBit('TEST_LOG_DATA1');
        $this->waitOnProcessDataSync();
        // Wait on data
        usleep(200000);
        // Deactivate logger
        $this->loggerMapper->enableLogger($logger->getId(), false);
        
        $data3 = $this->chartReader->getData($params);
        
        $this->assertEquals(TagLoggerInterval::I_ON_CHANGE, $logger->getInterval());
        $this->assertEquals(TagType::REAL, $logger->getTag()->getType());
        
        $this->assertEquals('line', $data1['chartType']);
        $this->assertEquals('TEST_LOG_REAL2', $data1['dataTitle']);
        $this->assertTrue(empty($data1['data']['x']));
        $this->assertTrue(empty($data1['data']['y']));
        
        $this->assertEquals('line', $data2['chartType']);
        $this->assertEquals('TEST_LOG_REAL2', $data2['dataTitle']);
        $this->assertEquals(2, count($data2['data']['x']));
        $this->assertEquals(2, count($data2['data']['y']));
        $this->assertEquals(2.16, $data2['data']['y'][0]);
        $this->assertEquals(2.15, $data2['data']['y'][1]);
        
        $this->assertEquals('line', $data3['chartType']);
        $this->assertEquals('TEST_LOG_REAL2', $data3['dataTitle']);
        $this->assertEquals(3, count($data3['data']['x']));
        $this->assertEquals(3, count($data3['data']['y']));
        $this->assertEquals(2.16, $data3['data']['y'][0]);
        $this->assertEquals(2.15, $data3['data']['y'][1]);
        $this->assertEquals(2.16, $data3['data']['y'][2]);
    }
}
