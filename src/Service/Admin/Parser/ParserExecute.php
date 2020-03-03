<?php

namespace App\Service\Admin\Parser;

use App\Service\Admin\Parser\ParserCommands;
use App\Service\Admin\Parser\ParserQuery;
use App\Service\Admin\Parser\ParserResponse;
use App\Service\Admin\ConfigGeneralMapper;
use App\Service\Admin\SystemSocket;

/**
 * Parser execute - Class for execute commands (query for C++ and respose)
 *
 * @author Mateusz Mirosławski
 */
class ParserExecute {
    
    /**
     * Parser query object
     */
    private $query;
    
    /**
     * Parser response object
     */
    private $response;
    
    /**
     * System configuration object
     */
    private $cfg;
    
    public function __construct(ConfigGeneralMapper $cfg, ParserQuery $pQuery, ParserResponse $pResponse) {
        
        $this->query = $pQuery;
        $this->response = $pResponse;
        $this->cfg = $cfg;
    }
    
    /**
     * Send command to the server
     * 
     * @param array $cmd Command
     * @return array Reply from server
     */
    private function sendCommand(array $cmd): array {
        
        // Prepare query
        $qStr = $this->query->query($cmd);
        
        // Send command
        $socket = new SystemSocket($this->cfg->getSystemSocketPort());
        $sResponse = $socket->send($qStr);
        
        // Parse reply
        return $this->response->response($sResponse);
    }
    
    /**
     * Get array with GET_BIT command
     * 
     * @param string $tagName Tag name
     * @return array
     */
    public function getBitCMD(string $tagName): array {
        
        return array(
            'cmd' => ParserCommands::GET_BIT,
            'tag' => $tagName
        );
    }
    
    /**
     * Get bit value
     * 
     * @param string $tagName Tag name
     * @return bool
     */
    public function getBit(string $tagName): bool {
        
        // Prepare command
        $command = $this->getBitCMD($tagName);
        
        // Send command
        $reply = $this->sendCommand($command);
        
        return $reply['value'];
    }
    
    /**
     * Get array with GET_BITS command
     * 
     * @param array $tagNames Tag names
     * @return array
     */
    public function getBitsCMD(array $tagNames): array {
        
        return array(
            'cmd' => ParserCommands::GET_BITS,
            'tags' => $tagNames
        );
    }
    
    /**
     * Get bits value
     * 
     * @param array $tagNames Tag names
     * @return array
     */
    public function getBits(array $tagNames): array {
        
        // Prepare command
        $command = $this->getBitsCMD($tagNames);
        
        // Send command
        $reply = $this->sendCommand($command);
        
        return $reply['values'];
    }
    
    /**
     * Get array with SET_BIT command
     * 
     * @param string $tagName Tag name
     * @return array
     */
    public function setBitCMD(string $tagName): array {
        
        return array(
            'cmd' => ParserCommands::SET_BIT,
            'tag' => $tagName
        );
    }
    
    /**
     * Set bit value
     * 
     * @param string $tagName Tag name
     * @return bool
     */
    public function setBit(string $tagName): bool {
        
        // Prepare command
        $command = $this->setBitCMD($tagName);
        
        // Send command
        $reply = $this->sendCommand($command);
        
        return $reply['value'];
    }
    
    /**
     * Get array with RESET_BIT command
     * 
     * @param string $tagName Tag name
     * @return array
     */
    public function resetBitCMD(string $tagName): array {
        
        return array(
            'cmd' => ParserCommands::RESET_BIT,
            'tag' => $tagName
        );
    }
    
    /**
     * Reset bit value
     * 
     * @param string $tagName Tag name
     * @return bool
     */
    public function resetBit(string $tagName): bool {
        
        // Prepare command
        $command = $this->resetBitCMD($tagName);
        
        // Send command
        $reply = $this->sendCommand($command);
        
        return $reply['value'];
    }
    
    /**
     * Get array with SET_BITS command
     * 
     * @param array $tagNames Tag names
     * @return array
     */
    public function setBitsCMD(array $tagNames): array {
        
        return array(
            'cmd' => ParserCommands::SET_BITS,
            'tags' => $tagNames
        );
    }
    
    /**
     * Set bits value
     * 
     * @param array $tagNames Tag names
     * @return bool
     */
    public function setBits(array $tagNames): bool {
        
        // Prepare command
        $command = $this->setBitsCMD($tagNames);
        
        // Send command
        $reply = $this->sendCommand($command);
        
        return $reply['value'];
    }
    
    /**
     * Get array with INVERT_BIT command
     * 
     * @param string $tagName Tag name
     * @return array
     */
    public function invertBitCMD(string $tagName): array {
        
        return array(
            'cmd' => ParserCommands::INVERT_BIT,
            'tag' => $tagName
        );
    }
    
    /**
     * Invert bit value
     * 
     * @param string $tagName Tag name
     * @return bool
     */
    public function invertBit(string $tagName): bool {
        
        // Prepare command
        $command = $this->invertBitCMD($tagName);
        
        // Send command
        $reply = $this->sendCommand($command);
        
        return $reply['value'];
    }
    
    /**
     * Get array with GET_BYTE command
     * 
     * @param string $tagName Tag name
     * @return array
     */
    public function getByteCMD(string $tagName): array {
        
        return array(
            'cmd' => ParserCommands::GET_BYTE,
            'tag' => $tagName
        );
    }
    
    /**
     * Get BYTE value
     * 
     * @param string $tagName Tag name
     * @return int
     */
    public function getByte(string $tagName): int {
        
        // Prepare command
        $command = $this->getByteCMD($tagName);
        
        // Send command
        $reply = $this->sendCommand($command);
        
        return $reply['value'];
    }
    
    /**
     * Get array with WRITE_BYTE command
     * 
     * @param string $tagName Tag name
     * @param int $val Value to write
     * @return array
     */
    public function writeByteCMD(string $tagName, int $val): array {
        
        return array(
            'cmd' => ParserCommands::WRITE_BYTE,
            'tag' => $tagName,
            'value' => $val
        );
    }
    
    /**
     * Write BYTE value
     * 
     * @param string $tagName Tag name
     * @param int $val Value to write
     * @return bool
     */
    public function writeByte(string $tagName, int $val): bool {
        
        // Prepare command
        $command = $this->writeByteCMD($tagName, $val);
        
        // Send command
        $reply = $this->sendCommand($command);
        
        return $reply['value'];
    }
    
    /**
     * Get array with GET_WORD command
     * 
     * @param string $tagName Tag name
     * @return array
     */
    public function getWordCMD(string $tagName): array {
        
        return array(
            'cmd' => ParserCommands::GET_WORD,
            'tag' => $tagName
        );
    }
    
    /**
     * Get WORD value
     * 
     * @param string $tagName Tag name
     * @return int
     */
    public function getWord(string $tagName): int {
        
        // Prepare command
        $command = $this->getWordCMD($tagName);
        
        // Send command
        $reply = $this->sendCommand($command);
        
        return $reply['value'];
    }
    
    /**
     * Get array with WRITE_WORD command
     * 
     * @param string $tagName Tag name
     * @param int $val Value to write
     * @return array
     */
    public function writeWordCMD(string $tagName, int $val): array {
        
        return array(
            'cmd' => ParserCommands::WRITE_WORD,
            'tag' => $tagName,
            'value' => $val
        );
    }
    
    /**
     * Write WORD value
     * 
     * @param string $tagName Tag name
     * @param int $val Value to write
     * @return bool
     */
    public function writeWord(string $tagName, int $val): bool {
        
        // Prepare command
        $command = $this->writeWordCMD($tagName, $val);
        
        // Send command
        $reply = $this->sendCommand($command);
        
        return $reply['value'];
    }
    
    /**
     * Get array with GET_DWORD command
     * 
     * @param string $tagName Tag name
     * @return array
     */
    public function getDWordCMD(string $tagName): array {
        
        return array(
            'cmd' => ParserCommands::GET_DWORD,
            'tag' => $tagName
        );
    }
    
    /**
     * Get DWORD value
     * 
     * @param string $tagName Tag name
     * @return int
     */
    public function getDWord(string $tagName): int {
        
        // Prepare command
        $command = $this->getDWordCMD($tagName);
        
        // Send command
        $reply = $this->sendCommand($command);
        
        return $reply['value'];
    }
    
    /**
     * Get array with WRITE_DWORD command
     * 
     * @param string $tagName Tag name
     * @param int $val Value to write
     * @return array
     */
    public function writeDWordCMD(string $tagName, int $val): array {
        
        return array(
            'cmd' => ParserCommands::WRITE_DWORD,
            'tag' => $tagName,
            'value' => $val
        );
    }
    
    /**
     * Write DWORD value
     * 
     * @param string $tagName Tag name
     * @param int $val Value to write
     * @return bool
     */
    public function writeDWord(string $tagName, int $val): bool {
        
        // Prepare command
        $command = $this->writeDWordCMD($tagName, $val);
        
        // Send command
        $reply = $this->sendCommand($command);
        
        return $reply['value'];
    }
    
    /**
     * Get array with GET_INT command
     * 
     * @param string $tagName Tag name
     * @return array
     */
    public function getIntCMD(string $tagName): array {
        
        return array(
            'cmd' => ParserCommands::GET_INT,
            'tag' => $tagName
        );
    }
    
    /**
     * Get INT value
     * 
     * @param string $tagName Tag name
     * @return bool
     */
    public function getInt(string $tagName): int {
        
        // Prepare command
        $command = $this->getIntCMD($tagName);
        
        // Send command
        $reply = $this->sendCommand($command);
        
        return $reply['value'];
    }
    
    /**
     * Get array with WRITE_INT command
     * 
     * @param string $tagName Tag name
     * @param int $val Value to write
     * @return array
     */
    public function writeIntCMD(string $tagName, int $val): array {
        
        return array(
            'cmd' => ParserCommands::WRITE_INT,
            'tag' => $tagName,
            'value' => $val
        );
    }
    
    /**
     * Write INT value
     * 
     * @param string $tagName Tag name
     * @param int $val Value to write
     * @return bool
     */
    public function writeInt(string $tagName, int $val): bool {
        
        // Prepare command
        $command = $this->writeIntCMD($tagName, $val);
        
        // Send command
        $reply = $this->sendCommand($command);
        
        return $reply['value'];
    }
    
    /**
     * Get array with GET_REAL command
     * 
     * @param string $tagName Tag name
     * @return array
     */
    public function getRealCMD(string $tagName): array {
        
        return array(
            'cmd' => ParserCommands::GET_REAL,
            'tag' => $tagName
        );
    }
    
    /**
     * Get REAL value
     * 
     * @param string $tagName Tag name
     * @return float
     */
    public function getReal(string $tagName): float {
        
        // Prepare command
        $command = $this->getRealCMD($tagName);
        
        // Send command
        $reply = $this->sendCommand($command);
        
        return $reply['value'];
    }
    
    /**
     * Get array with WRITE_REAL command
     * 
     * @param string $tagName Tag name
     * @param float $val Value to write
     * @return array
     */
    public function writeRealCMD(string $tagName, float $val): array {
        
        return array(
            'cmd' => ParserCommands::WRITE_REAL,
            'tag' => $tagName,
            'value' => $val
        );
    }
    
    /**
     * Write REAL value
     * 
     * @param string $tagName Tag name
     * @param float $val Value to write
     * @return bool
     */
    public function writeReal(string $tagName, float $val): bool {
        
        // Prepare command
        $command = $this->writeRealCMD($tagName, $val);
        
        // Send command
        $reply = $this->sendCommand($command);
        
        return $reply['value'];
    }
    
    /**
     * Execute multi command in controller
     * 
     * @param array $commands Commands to execute
     * @return array
     */
    public function executeMultiCMD(array $commands): array {
        
        // Prepare command
        $command = array(
            'cmd' => ParserCommands::MULTI_CMD,
            'value' => $commands
        );
        
        // Send command
        $reply = $this->sendCommand($command);
        
        return $reply['value'];
    }
    
    /**
     * Exit application command
     * 
     * @return bool
     */
    public function exit(): bool {
        
        // Prepare command
        $command = array(
            'cmd' => ParserCommands::EXIT_APP
        );
        
        // Send command
        $reply = $this->sendCommand($command);
        
        return $reply['value'];
    }
    
    /**
     * Acknowledge alarm
     * 
     * @param int $alarmId Alarm definition identifier
     * @return bool
     */
    public function ackAlarm(int $alarmId=0): bool {
        
        // Prepare command
        $command = array(
            'cmd' => ParserCommands::ACK_ALARM,
            'alarm_id' => $alarmId
        );
        
        // Send command
        $reply = $this->sendCommand($command);
        
        return $reply['value'];
    }
}
