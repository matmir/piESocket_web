<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Service\Admin\TagsMapper;
use App\Service\Admin\Parser\ParserExecute;

/**
 * Base function tests class
 *
 * @author Mateusz Mirosławski
 */
abstract class BaseFunctionTestCase extends WebTestCase
{
    /**
     * ParserExecute object
     */
    protected $parser;
    
    /**
     * TagsMapper object
     */
    protected $tagsMapper;
    
    public function setUp()
    {
        self::bootKernel();
        
        $this->tagsMapper = self::$container->get(TagsMapper::class);
        $this->parser = self::$container->get(ParserExecute::class);
        
        // Clear test server process data
        $this->parser->setBit('BIT_CLEAR_PROCESS');
        $this->parser->setBit('MB_BIT_CLEAR_PROCESS');
        $this->waitOnProcessDataSync(false);
    }
    
    public function tearDown()
    {
        $this->tagsMapper = null;
        $this->parser = null;
    }
    
    protected function waitOnProcessDataSync(bool $onlyShm = true)
    {
        $this->waitOnShmProcessDataSync();
        
        if (!$onlyShm) {
            $this->waitOnModbusProcessDataSync();
        }
    }
    
    /**
     * Wait until process data is updated in SHM driver
     */
    protected function waitOnShmProcessDataSync()
    {
        $this->parser->setBit('BIT_SYNC');
        while (!$this->parser->getBit('BIT_SYNC')) {
            usleep(10000);
        }
        
        $this->parser->resetBit('BIT_SYNC');
        while ($this->parser->getBit('BIT_SYNC')) {
            usleep(10000);
        }
    }
    
    /**
     * Wait until process data is updated in Modbus driver
     */
    protected function waitOnModbusProcessDataSync()
    {
        $this->parser->setBit('MB_BIT_SYNC');
        while (!$this->parser->getBit('MB_BIT_SYNC')) {
            usleep(10000);
        }
        
        $this->parser->resetBit('MB_BIT_SYNC');
        while ($this->parser->getBit('MB_BIT_SYNC')) {
            usleep(10000);
        }
    }
}
