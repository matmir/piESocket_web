<?php

namespace App\Tests;

use App\Tests\BaseFunctionTestCase;
use App\Entity\Admin\TagLogger;
use App\Service\Admin\TagLoggerMapper;
use App\Entity\Admin\TagLoggerInterval;
use App\Service\Admin\ChartDataReader;

/**
 * Base function tests for Tag logger
 *
 * @author Mateusz Mirosławski
 */
abstract class TagLoggerFunctionTestCase extends BaseFunctionTestCase
{
    /**
     * TagLoggerMapper object
     */
    protected $loggerMapper;
    
    /**
     * Chart data reader
     */
    protected $chartReader;
    
    public function setUp(): void
    {
        parent::setUp();
        
        $this->loggerMapper = self::$container->get(TagLoggerMapper::class);
        $this->chartReader = self::$container->get(ChartDataReader::class);
        
        // Start logger simultaion
        $this->parser->setBit('TEST_LOG_SIM1');
        $this->parser->resetBit('TEST_LOG_DATA1');
        $this->waitOnProcessDataSync();
    }
    
    public function tearDown(): void
    {
        $this->loggerMapper = null;
        $this->chartReader = null;
        
        parent::tearDown();
    }
    
    /**
     * Get logger
     *
     * @param int $type Logger type
     * @param bool $time Time triggered flag
     * @return TagLogger
     */
    public function getLogger(int $type, bool $time = true): TagLogger
    {
        // Get loggers
        $loggers = $this->loggerMapper->getLoggers();
        
        $logger = null;
        
        for ($i = 0; $i < count($loggers); ++$i) {
            if ($loggers[$i]->getTag()->getType() == $type) {
                if ($time) {
                    if ($loggers[$i]->getInterval() != TagLoggerInterval::I_ON_CHANGE) {
                        $logger = $loggers[$i];
                        // stop searching
                        break;
                    }
                } else {
                    if ($loggers[$i]->getInterval() == TagLoggerInterval::I_ON_CHANGE) {
                        $logger = $loggers[$i];
                        // stop searching
                        break;
                    }
                }
            }
        }
        
        return $logger;
    }
}
