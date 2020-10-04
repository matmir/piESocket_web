<?php

namespace App\Service\Admin;

use Doctrine\DBAL\Driver\Connection;
use Doctrine\DBAL\ParameterType;
use Symfony\Component\Config\Definition\Exception\Exception;
use App\Service\Admin\TagLoggerMapper;
use App\Entity\Admin\TagLogger;
use App\Entity\Admin\TagType;

/**
 * Class to read chart data from tag loggers
 *
 * @author Mateusz Mirosławski
 */
class ChartDataReader
{
    private Connection $dbConn;
    
    private TagLoggerMapper $tagLoggerMapper;
    
    public function __construct(Connection $connection)
    {
        $this->dbConn = $connection;
        
        $this->tagLoggerMapper = new TagLoggerMapper($connection);
    }
    
    /**
     * Check data for dateRange sorting mode
     *
     * @param array $data Data for dateRange sorting mode
     * @throws Exception
     */
    private function checkDateRange($data)
    {
        // Check if data is array
        if (!is_array($data)) {
            throw new Exception('Data for dateRange is not array');
        }
        
        // Check keys in array
        if (!array_key_exists('dateFrom', $data)) {
            throw new Exception('Missing dateFrom in dateRange data');
        }
        if (!array_key_exists('dateTo', $data)) {
            throw new Exception('Missing dateTo in dateRange data');
        }
        if (!array_key_exists('timeFrom', $data)) {
            throw new Exception('Missing timeFrom in dateRange data');
        }
        if (!array_key_exists('timeTo', $data)) {
            throw new Exception('Missing timeTo in dateRange data');
        }
    }
    
    /**
     * Check data for currentData sorting mode
     *
     * @param array $data Data for currentData sorting mode
     * @throws Exception
     */
    private function checkCurrentData($data)
    {
        // Check if data is array
        if (!is_array($data)) {
            throw new Exception('Data for currentData is not array');
        }
        
        // Check keys in array
        if (!array_key_exists('dateFrom', $data)) {
            throw new Exception('Missing dateFrom in currentData data');
        }
        if (!array_key_exists('timeFrom', $data)) {
            throw new Exception('Missing timeFrom in currentData data');
        }
    }
    
    /**
     * Check data with chart sorting information
     *
     * @param array $data Data with chart sorting information
     * @throws Exception
     */
    private function checkData($data)
    {
        // Check if data is array
        if (!is_array($data)) {
            throw new Exception('Data is not array');
        }
        
        // Check keys in array
        if (!array_key_exists('loggerID', $data)) {
            throw new Exception('Missing loggerID in data');
        }
        if (!array_key_exists('sortMode', $data)) {
            throw new Exception('Missing sortMode in data');
        }
        if (!array_key_exists('sortData', $data)) {
            throw new Exception('Missing sortData in data');
        }
        
        if ($data['sortMode'] == 'rangeData') {
            $this->checkDateRange($data['sortData']);
        }
        
        if ($data['sortMode'] == 'currentData') {
            $this->checkCurrentData($data['sortData']);
        }
    }
    
    /**
     * Get tag logger table column names
     *
     * @param TagLogger $logger Tag logger object
     * @return array
     */
    private function getLoggerTableColumnNames(TagLogger $logger): array
    {
        $tbl = 'log_';
        $sort = 'l';
        $timeStamp = 'l';
        $val = 'l';
        
        // Type
        switch ($logger->getTag()->getType()) {
            case TagType::BIT:
                $tbl .= 'BIT';
                $sort .= 'b';
                $timeStamp .= 'b';
                $val .= 'b';
                break;
            case TagType::BYTE:
                $tbl .= 'BYTE';
                $sort .= 'by';
                $timeStamp .= 'by';
                $val .= 'by';
                break;
            case TagType::WORD:
                $tbl .= 'WORD';
                $sort .= 'w';
                $timeStamp .= 'w';
                $val .= 'w';
                break;
            case TagType::DWORD:
                $tbl .= 'DWORD';
                $sort .= 'd';
                $timeStamp .= 'd';
                $val .= 'd';
                break;
            case TagType::INT:
                $tbl .= 'INT';
                $sort .= 'int';
                $timeStamp .= 'int';
                $val .= 'int';
                break;
            case TagType::REAL:
                $tbl .= 'REAL';
                $sort .= 'r';
                $timeStamp .= 'r';
                $val .= 'r';
                break;
        }
        
        // Table
        $tbl .= "_" . $logger->getId();
        
        // Sort identifier
        $sort .= 'TimeStamp';
        
        // Timestamp
        $timeStamp .= 'TimeStamp';
        
        // Value
        $val .= 'Value';
        
        return array(
            'table' => $tbl,
            'sortId' => $sort,
            'timestamp' => $timeStamp,
            'value' => $val
        );
    }
    
    /**
     * Prepare data from DB for Chart
     *
     * @param type $items Data from DB
     * @param string $valueCol Value column name
     * @param string $timeCol Timestamp column name
     * @return array
     */
    private function prepareChartData($items, string $valueCol, string $timeCol): array
    {
        // Return array
        $ret = array(
            'x' => array(),
            'y' => array()
        );
        
        $x = array();
        $y = array();
        
        foreach ($items as $item) {
            // Value
            array_push($y, $item[$valueCol]);
            
            // Date
            array_push($x, $item[$timeCol]);
        }
        
        $ret['x'] = array_reverse($x);
        $ret['y'] = array_reverse($y);
        
        return $ret;
    }
    
    /**
     * Get last data
     *
     * @param array $data Sorting params
     * @param TagLogger $logger Tag Logger object
     * @return array
     */
    private function getLastData(array $data, TagLogger $logger): array
    {
        // Prepare sql query columns
        $cols = $this->getLoggerTableColumnNames($logger);
        
        // Prepare query
        $sql = "SELECT * FROM " . $cols['table'] . " ORDER BY " . $cols['sortId'] . " DESC LIMIT 10;";
        
        $statement = $this->dbConn->prepare($sql);
        $statement->execute();
        $items = $statement->fetchAll();
        
        return $this->prepareChartData($items, $cols['value'], $cols['timestamp']);
    }
    
    /**
     * Check Date/Time parameters
     *
     * @param string $date Date
     * @param string $time Time
     * @return string Formatted date/time
     * @throws Exception
     */
    private function checkDateTimeParam(string $date, string $time): string
    {
        // Create one string
        $timeStamp = $date . " " . $time;
        
        // Create DateTime
        $dt = \DateTime::createFromFormat('Y-m-d H:i:s', $timeStamp);
        
        if ($dt === false) {
            throw new Exception("Wrong date/time format");
        }
        
        return $dt->format('Y-m-d H:i:s');
    }
    
    /**
     * Get range data
     *
     * @param array $data Sorting params
     * @param TagLogger $logger Tag Logger object
     */
    private function getRangeData(array $data, TagLogger $logger): array
    {
        // Prepare sql query columns
        $cols = $this->getLoggerTableColumnNames($logger);
        
        // From date
        $dtFrom = $this->checkDateTimeParam($data['dateFrom'], $data['timeFrom']);
        
        // To date
        $dtTo = $this->checkDateTimeParam($data['dateTo'], $data['timeTo']);
        
        // Prepare query
        $sql = "SELECT * FROM " . $cols['table'];
        $sql .= " WHERE " . $cols['timestamp'] . " BETWEEN ? AND ?";
        $sql .= " ORDER BY " . $cols['sortId'] . " DESC;";
        
        $statement = $this->dbConn->prepare($sql);
        $statement->bindValue(1, $dtFrom, ParameterType::STRING);
        $statement->bindValue(2, $dtTo, ParameterType::STRING);
        
        $statement->execute();
        $items = $statement->fetchAll();
        
        return $this->prepareChartData($items, $cols['value'], $cols['timestamp']);
    }
    
    /**
     * Get current data
     *
     * @param array $data Sorting params
     * @param TagLogger $logger Tag Logger object
     */
    private function getCurrentData(array $data, TagLogger $logger): array
    {
        // Prepare sql query columns
        $cols = $this->getLoggerTableColumnNames($logger);
        
        // From date
        $dtFrom = $this->checkDateTimeParam($data['dateFrom'], $data['timeFrom']);
        
        // Prepare query
        $sql = "SELECT * FROM " . $cols['table'];
        $sql .= " WHERE " . $cols['timestamp'] . " >= ?";
        $sql .= " ORDER BY " . $cols['sortId'] . " DESC;";
        
        $statement = $this->dbConn->prepare($sql);
        $statement->bindValue(1, $dtFrom, ParameterType::STRING);
        
        $statement->execute();
        $items = $statement->fetchAll();
        
        return $this->prepareChartData($items, $cols['value'], $cols['timestamp']);
    }
    
    /**
     * Get data for chart
     *
     * @param array $data Data with chart sorting information
     * @return array
     */
    public function getData($data): array
    {
        // Return array
        $ret = array(
            'chartType' => 'line',
            'chartTitle' => 'Line chart',
            'dataTitle' => 'none',
            'data' => array(
                'x' => array(),
                'y' => array()
            )
        );
        
        // Check data
        $this->checkData($data);
        
        // Get Tag logger data
        $tagLogger = $this->tagLoggerMapper->getLogger(intval($data['loggerID']));
        
        // Data title
        $ret['dataTitle'] = $tagLogger->getTag()->getName();
        
        // Chart type and title
        if ($tagLogger->getTag()->getType() == TagType::BIT) {
            $ret['chartType'] = 'stepped';
            $ret['chartTitle'] = 'Stepped chart';
        } else {
            $ret['chartType'] = 'line';
            $ret['chartTitle'] = 'Line chart';
        }
        
        // Get data according to the sorting mode
        switch ($data['sortMode']) {
            case 'lastData':
                $ret['data'] = $this->getLastData($data['sortData'], $tagLogger);
                break;
            case 'rangeData':
                $ret['data'] = $this->getRangeData($data['sortData'], $tagLogger);
                break;
            case 'currentData':
                $ret['data'] = $this->getCurrentData($data['sortData'], $tagLogger);
                break;
        }
        
        return $ret;
    }
}
