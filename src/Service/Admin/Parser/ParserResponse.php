<?php

namespace App\Service\Admin\Parser;

use App\Service\Admin\Parser\ParserCommands;
use App\Service\Admin\Parser\ParserReplyCodes;
use App\Service\Admin\Parser\ParserSeparators;
use App\Service\Admin\Parser\ParserException;

/**
 * Response parser - Class for parse response from C++ application
 *
 * @author Mateusz Mirosławski
 */
class ParserResponse
{
    /**
     * Prepare OK reply
     *
     * @param int $cmd Command number
     * @param int $data Command data
     * @return array
     * @throws ParserException
     */
    private function resOk($cmd, $data): array
    {
        // Check data
        if (!is_numeric($data)) {
            throw new ParserException('res_ok: Data response need to be numeric!');
        }
        
        // Check values
        if ($data != ParserReplyCodes::OK) {
            throw new ParserException('res_ok: Data response has invalid value!');
        }

        return array(
            'cmd' => $cmd,
            'value' => ParserReplyCodes::OK
        );
    }
    
    /**
     * Prepare reply with GET_BIT command
     *
     * @param int $data Command data
     * @return array
     * @throws ParserException
     */
    private function resGetBit($data): array
    {
        // Check data
        if (!is_numeric($data)) {
            throw new ParserException('RES_GET_BIT: Data response need to be numeric!');
        }
        // Check values
        if (!($data == 0 || $data == 1)) {
            throw new ParserException('RES_GET_BIT: Data response has invalid values!');
        }

        return array(
            'cmd' => ParserCommands::GET_BIT,
            'value' => $data
        );
    }
    
    /**
     * Prepare reply with SET_BIT command
     *
     * @param int $data Command data
     * @return array
     */
    private function resSetBit($data): array
    {
        return $this->resOk(ParserCommands::SET_BIT, $data);
    }
    
    /**
     * Prepare reply with RESET_BIT command
     *
     * @param int $data Command data
     * @return array
     */
    private function resResetBit($data): array
    {
        return $this->resOk(ParserCommands::RESET_BIT, $data);
    }
    
    /**
     * Prepare reply with INVERT_BIT command
     *
     * @param int $data Command data
     * @return array
     */
    private function resInvertBit($data): array
    {
        return $this->resOk(ParserCommands::INVERT_BIT, $data);
    }
    
    /**
     * Prepare reply with GET_BITS command
     *
     * @param string $data Command data
     * @return array
     * @throws ParserException
     */
    private function resGetBits(string $data): array
    {
        // Check data
        if (empty($data)) {
            throw new ParserException('RES_GET_BITS: Data is empty!');
        }

        // Explode bit values
        $dt = explode(ParserSeparators::VS, $data);

        if (empty($dt) || count($dt) < 2) {
            throw new ParserException('RES_GET_BITS: Error during data explode!');
        }

        $vals = array();
        $cn = count($dt);
        // Check values
        for ($i = 0; $i < $cn; ++$i) {
            if (!is_numeric($dt[$i])) {
                throw new ParserException('RES_GET_BITS: Bit value is not numeric!');
            }
            if (!($dt[$i] == 0 || $dt[$i] == 1)) {
                throw new ParserException('RES_GET_BITS: Bit has invalid value!');
            }

            $vals[$i] = $dt[$i];
        }

        return array(
            'cmd' => ParserCommands::GET_BITS,
            'values' => $vals
        );
    }
    
    /**
     * Prepare reply with SET_BITS command
     *
     * @param int $data Command data
     * @return array
     */
    private function resSetBits($data): array
    {
        return $this->resOk(ParserCommands::SET_BITS, $data);
    }
    
    /**
     * Prepare reply with GET_BYTE command
     *
     * @param type $data Command data
     * @return array
     * @throws ParserException
     */
    private function resGetByte($data): array
    {
        // Check data
        if (trim($data) == '') {
            throw new ParserException('RES_GET_BYTE: Data is empty!');
        }
        if (!is_numeric($data)) {
            throw new ParserException('RES_GET_BYTE: Data value is not numeric!');
        }
        if ($data > 255 || $data < 0) {
            throw new ParserException('RES_GET_BYTE: Data value is out of range!');
        }

        return array(
            'cmd' => ParserCommands::GET_BYTE,
            'value' => $data
        );
    }
    
    /**
     * Prepare reply with WRITE_BYTE command
     *
     * @param type $data Command data
     * @return array
     * @throws ParserException
     */
    private function resWriteByte($data): array
    {
        return $this->resOk(ParserCommands::WRITE_BYTE, $data);
    }
    
    /**
     * Prepare reply with GET_WORD command
     *
     * @param type $data Command data
     * @return array
     * @throws ParserException
     */
    private function resGetWord($data): array
    {
        // Check data
        if (trim($data) == '') {
            throw new ParserException('RES_GET_WORD: Data is empty!');
        }
        if (!is_numeric($data)) {
            throw new ParserException('RES_GET_WORD: Data value is not numeric!');
        }
        if ($data > 65535 || $data < 0) {
            throw new ParserException('RES_GET_WORD: Data value is out of range!');
        }

        return array(
            'cmd' => ParserCommands::GET_WORD,
            'value' => $data
        );
    }
    
    /**
     * Prepare reply with WRITE_WORD command
     *
     * @param type $data Command data
     * @return array
     * @throws ParserException
     */
    private function resWriteWord($data): array
    {
        return $this->resOk(ParserCommands::WRITE_WORD, $data);
    }
    
    /**
     * Prepare reply with GET_DWORD command
     *
     * @param type $data Command data
     * @return array
     * @throws ParserException
     */
    private function resGetDWord($data): array
    {
        // Check data
        if (trim($data) == '') {
            throw new ParserException('RES_GET_DWORD: Data is empty!');
        }
        if (!is_numeric($data)) {
            throw new ParserException('RES_GET_DWORD: Data value is not numeric!');
        }
        if ($data > 4294967295 || $data < 0) {
            throw new ParserException('RES_GET_DWORD: Data value is out of range!');
        }

        return array(
            'cmd' => ParserCommands::GET_DWORD,
            'value' => $data
        );
    }
    
    /**
     * Prepare reply with WRITE_DWORD command
     *
     * @param type $data Command data
     * @return array
     * @throws ParserException
     */
    private function resWriteDWord($data): array
    {
        return $this->resOk(ParserCommands::WRITE_DWORD, $data);
    }
    
    /**
     * Prepare reply with GET_INT command
     *
     * @param type $data Command data
     * @return array
     * @throws ParserException
     */
    private function resGetInt($data): array
    {
        // Check data
        if (trim($data) == '') {
            throw new ParserException('RES_GET_INT: Data is empty!');
        }
        if (!is_numeric($data)) {
            throw new ParserException('RES_GET_INT: Data value is not numeric!');
        }
        if ($data > 2147483647 || $data < -2147483648) {
            throw new ParserException('RES_GET_INT: Data value is out of range!');
        }

        return array(
            'cmd' => ParserCommands::GET_INT,
            'value' => $data
        );
    }
    
    /**
     * Prepare reply with WRITE_INT command
     *
     * @param type $data Command data
     * @return array
     * @throws ParserException
     */
    private function resWriteInt($data): array
    {
        return $this->resOk(ParserCommands::WRITE_INT, $data);
    }
    
    /**
     * Prepare reply with GET_REAL command
     *
     * @param type $data Command data
     * @return array
     * @throws ParserException
     */
    private function resGetReal($data): array
    {
        // Check data
        if (trim($data) == '') {
            throw new ParserException('RES_GET_REAL: Data is empty!');
        }

        return array(
            'cmd' => ParserCommands::GET_REAL,
            'value' => floatval($data)
        );
    }
    
    /**
     * Prepare reply with WRITE_REAL command
     *
     * @param type $data Command data
     * @return array
     * @throws ParserException
     */
    private function resWriteReal($data): array
    {
        return $this->resOk(ParserCommands::WRITE_REAL, $data);
    }
    
    /**
     * Prepare reply with MULTI_CMD command
     *
     * @param type $data Command data
     * @return array
     * @throws ParserException
     */
    private function resMultiCmd($data): array
    {
        // Check data
        if (empty($data)) {
            throw new ParserException('RES_MULTI_CMD: Data is empty!');
        }

        // Explode commands to the array
        $cmds = explode(ParserSeparators::CMS, $data);

        if (empty($cmds) || count($cmds) < 2) {
            throw new ParserException('RES_MULTI_CMD: Error during data explode!');
        }

        $cn = count($cmds);

        $retVal = array();
        for ($i = 0; $i < $cn; ++$i) {
            // Explode command and value from command string
            $dt = explode(ParserSeparators::CVMS, $cmds[$i]);

            // Check data count
            if (count($dt) != 2) {
                throw new ParserException('RES_MULTI_CMD: Server response is invalid!');
            }

            // Command
            $cmd = $dt[0];

            // Values
            $values = $dt[1];

            if (!is_numeric($cmd)) {
                throw new ParserException('RES_MULTI_CMD: Command response need to be numeric!');
            }

            // Check if response is error
            if ($cmd == ParserReplyCodes::NOK) {
                // Create error reply
                $res = $this->responseERROR($values);
            } else { // Normal reply
                // Check command
                if (!ParserCommands::checkCMD($cmd)) {
                    throw new ParserException('RES_MULTI_CMD: Wrong command number!');
                }
                if ($cmd == ParserCommands::MULTI_CMD) {
                    throw new ParserException('RES_MULTI_CMD: Can not call MULTI_CMD inside MULTI_CMD!');
                }
                if ($cmd == ParserCommands::GET_THREAD_CYCLE_TIME) {
                    throw new ParserException('RES_MULTI_CMD: Can not call GET_THREAD_CYCLE_TIME inside MULTI_CMD!');
                }

                // Parse reply
                switch ($cmd) {
                    case ParserCommands::GET_BIT:
                        $res = $this->resGetBit($values);
                        break;
                    case ParserCommands::SET_BIT:
                        $res = $this->resSetBit($values);
                        break;
                    case ParserCommands::RESET_BIT:
                        $res = $this->resResetBit($values);
                        break;
                    case ParserCommands::INVERT_BIT:
                        $res = $this->resInvertBit($values);
                        break;
                    case ParserCommands::GET_BITS:
                        $res = $this->resGetBits($values);
                        break;
                    case ParserCommands::SET_BITS:
                        $res = $this->resSetBits($values);
                        break;
                    case ParserCommands::GET_BYTE:
                        $res = $this->resGetByte($values);
                        break;
                    case ParserCommands::WRITE_BYTE:
                        $res = $this->resWriteByte($values);
                        break;
                    case ParserCommands::GET_WORD:
                        $res = $this->resGetWord($values);
                        break;
                    case ParserCommands::WRITE_WORD:
                        $res = $this->resWriteWord($values);
                        break;
                    case ParserCommands::GET_DWORD:
                        $res = $this->resGetDWord($values);
                        break;
                    case ParserCommands::WRITE_DWORD:
                        $res = $this->resWriteDWord($values);
                        break;
                    case ParserCommands::GET_INT:
                        $res = $this->resGetInt($values);
                        break;
                    case ParserCommands::WRITE_INT:
                        $res = $this->resWriteInt($values);
                        break;
                    case ParserCommands::GET_REAL:
                        $res = $this->resGetReal($values);
                        break;
                    case ParserCommands::WRITE_REAL:
                        $res = $this->resWriteReal($values);
                        break;
                    case ParserCommands::ACK_ALARM:
                        $res = $this->resAckAlarm($values);
                        break;
                    case ParserCommands::EXIT_APP:
                        $res = $this->resExitApp($values);
                        break;
                }
            }

            $retVal[$i] = $res;
        }

        return array(
            'cmd' => ParserCommands::MULTI_CMD,
            'value' => $retVal
        );
    }
    
    /**
     * Prepare reply with ACK_ALARM command
     *
     * @param type $data Command data
     * @return array
     * @throws ParserException
     */
    private function resAckAlarm($data): array
    {
        return $this->resOk(ParserCommands::ACK_ALARM, $data);
    }
    
    /**
     * Prepare reply with EXIT_APP command
     *
     * @param type $data Command data
     * @return array
     * @throws ParserException
     */
    private function resExitApp($data): array
    {
        return $this->resOk(ParserCommands::EXIT_APP, $data);
    }
    
    /**
     * Get cycle times from thread data
     *
     * @param type $threadData Thread data
     * @return array
     * @throws ParserException
     */
    private function getCycleTime($threadData): array
    {
        // Updater cycle time values
        $ctVals = explode(ParserSeparators::CTVAL, $threadData);
        if (count($ctVals) != 3) {
            throw new ParserException('RES_GET_THREAD_CYCLE_TIME: Error during thread values explode!');
        }
        if (!is_numeric($ctVals[0]) || !is_numeric($ctVals[1]) || !is_numeric($ctVals[2])) {
            throw new ParserException('RES_GET_THREAD_CYCLE_TIME: Error during thread values parsing!');
        }
        
        return array(
            'min' => $ctVals[0],
            'max' => $ctVals[1],
            'current' => $ctVals[2]
        );
    }
    
    /**
     * Prepare reply with GET_THREAD_CYCLE_TIME command
     *
     * @param type $data Command data
     * @return array
     * @throws ParserException
     */
    private function resGetThreadCycleTime($data): array
    {
        // Check data
        if (empty($data)) {
            throw new ParserException('RES_GET_THREAD_CYCLE_TIME: Data is empty!');
        }

        // Explode thread cycle times
        $threadData = explode(ParserSeparators::CTTH, $data);

        if (empty($threadData) || count($threadData) < 5) {
            throw new ParserException('RES_GET_THREAD_CYCLE_TIME: Error during data explode!');
        }
        
        // Get number of updaters and buffers
        $countDT = explode(ParserSeparators::CTVAL, $threadData[0]);
        if (count($countDT) != 2) {
            throw new ParserException('RES_GET_THREAD_CYCLE_TIME: Error during count data explode!');
        }
        if (!is_numeric($countDT[0]) || !is_numeric($countDT[1])) {
            throw new ParserException('RES_GET_THREAD_CYCLE_TIME: Error during count data parsing!');
        }
        
        $UpdaterCTVals = array();
        // Get Cycle times from Process Updater
        for ($i = 0; $i < $countDT[0]; ++$i) {
            $UpdaterCT = explode(ParserSeparators::CTTHVAL, $threadData[$i + 1]);
            if (count($UpdaterCT) != 2 || strpos($UpdaterCT[0], 'Updater_') === false) {
                throw new ParserException('RES_GET_THREAD_CYCLE_TIME: Error during Process Updater data explode!');
            }
            
            $UpdaterCTVals[$UpdaterCT[0]] = $this->getCycleTime($UpdaterCT[1]);
        }
            
        $PollingCTVals = array();
        // Get Cycle times from Driver polling
        for ($i = 0; $i < $countDT[1]; ++$i) {
            $PollingCT = explode(ParserSeparators::CTTHVAL, $threadData[$i + 1 + $countDT[0]]);
            if (count($PollingCT) != 2 || strpos($PollingCT[0], 'DriverBuffer_') === false) {
                throw new ParserException('RES_GET_THREAD_CYCLE_TIME: Error during Driver polling data explode!');
            }
            
            $PollingCTVals[$PollingCT[0]] = $this->getCycleTime($PollingCT[1]);
        }
        
        // Data offset
        $dtOffset = 1 + $countDT[0] + $countDT[1];
        
        // Get Cycle times from Tag Logger
        $LoggerCT = explode(ParserSeparators::CTTHVAL, $threadData[$dtOffset]);
        if (count($LoggerCT) != 2 || $LoggerCT[0] != "Logger") {
            throw new ParserException('RES_GET_THREAD_CYCLE_TIME: Error during Tag logger data explode!');
        }
        $LoggerCTVals = $this->getCycleTime($LoggerCT[1]);
        
        // Get Cycle times from Tag Logger Writer
        $LoggerWriterCT = explode(ParserSeparators::CTTHVAL, $threadData[$dtOffset + 1]);
        if (count($LoggerWriterCT) != 2 || $LoggerWriterCT[0] != "LoggerWriter") {
            throw new ParserException('RES_GET_THREAD_CYCLE_TIME: Error during Tag logger writer data explode!');
        }
        $LoggerWriterCTVals = $this->getCycleTime($LoggerWriterCT[1]);
        
        // Get Cycle times from Alarming
        $AlarmingCT = explode(ParserSeparators::CTTHVAL, $threadData[$dtOffset + 2]);
        if (count($AlarmingCT) != 2 || $AlarmingCT[0] != "Alarming") {
            throw new ParserException('RES_GET_THREAD_CYCLE_TIME: Error during Alarming data explode!');
        }
        $AlarmingCTVals = $this->getCycleTime($AlarmingCT[1]);
        
        // Get Cycle times from Script system
        $ScriptCT = explode(ParserSeparators::CTTHVAL, $threadData[$dtOffset + 3]);
        if (count($ScriptCT) != 2 || $ScriptCT[0] != "Script") {
            throw new ParserException('RES_GET_THREAD_CYCLE_TIME: Error during Script system data explode!');
        }
        $ScriptCTVals = $this->getCycleTime($ScriptCT[1]);

        // Cycle times array
        $CT = array(
            'UpdaterCnt' => $countDT[0],
            'PollingCnt' => $countDT[1],
            'Updater' => $UpdaterCTVals,
            'Polling' => $PollingCTVals,
            'Logger' => $LoggerCTVals,
            'LoggerWriter' => $LoggerWriterCTVals,
            'Alarming' => $AlarmingCTVals,
            'Script' => $ScriptCTVals
        );
        
        return array(
            'cmd' => ParserCommands::GET_THREAD_CYCLE_TIME,
            'values' => $CT
        );
    }
    
    /**
     * Prepare error response
     *
     * @param type $errorDT Error data
     * @throws ParserException
     */
    private function responseERROR($errorDT)
    {
        if (!is_numeric($errorDT)) {
            throw new ParserException('responseERROR: Error code need to be numeric!');
        }
        
        $str = 'Unknown reply';
        
        switch ($errorDT) {
            case ParserReplyCodes::NOT_EXIST:
                $str = 'Tag does not exist!';
                break;
            case ParserReplyCodes::WRONG_VALUE:
                $str = 'Tag has wrong value!';
                break;
            case ParserReplyCodes::WRONG_TAG_TYPE:
                $str = 'Tag has wrong type!';
                break;
            case ParserReplyCodes::WRONG_TAG_AREA:
                $str = 'Tag has wrong area!';
                break;
            case ParserReplyCodes::WRONG_ADDR:
                $str = 'Tag has wrong address!';
                break;
            case ParserReplyCodes::INTERNAL_ERR:
                $str = 'Internal error!';
                break;
            case ParserReplyCodes::SQL_ERROR:
                $str = 'SQL error!';
                break;
            case ParserReplyCodes::UNKNOWN_CMD:
                $str = 'Unknown command!';
                break;
        }

        // Throw error
        throw new ParserException('ServerError: ' . $str, $errorDT);
    }
    
    /**
     * Prepare response from C++ application
     *
     * @param string $serverResponse Server response
     * @return array
     * @throws ParserException
     */
    public function response(string $serverResponse): array
    {
        // Check server response
        if (empty($serverResponse)) {
            throw new ParserException('response: Server response is empty!');
        }

        // Explode command and value from response
        $dt = explode(ParserSeparators::CVS, $serverResponse);

        // Check data count
        if (count($dt) != 2) {
            throw new ParserException('response: Server response is not valid!');
        }

        // Command
        $cmd = $dt[0];

        // Values
        $values = $dt[1];

        if (!is_numeric($cmd)) {
            throw new ParserException('response: Command response need to be numeric!');
        }

        $res = array();

        // Check if response is error
        if ($cmd == ParserReplyCodes::NOK) {
            // Create error reply
            $this->responseERROR($values);
        } else { // Normal reply
            // Check command
            if (!ParserCommands::checkCMD($cmd)) {
                throw new ParserException('response: Wrong command number!');
            }

            // Parse reply
            switch ($cmd) {
                case ParserCommands::GET_BIT:
                    $res = $this->resGetBit($values);
                    break;
                case ParserCommands::SET_BIT:
                    $res = $this->resSetBit($values);
                    break;
                case ParserCommands::RESET_BIT:
                    $res = $this->resResetBit($values);
                    break;
                case ParserCommands::INVERT_BIT:
                    $res = $this->resInvertBit($values);
                    break;
                case ParserCommands::GET_BITS:
                    $res = $this->resGetBits($values);
                    break;
                case ParserCommands::SET_BITS:
                    $res = $this->resSetBits($values);
                    break;
                case ParserCommands::GET_BYTE:
                    $res = $this->resGetByte($values);
                    break;
                case ParserCommands::WRITE_BYTE:
                    $res = $this->resWriteByte($values);
                    break;
                case ParserCommands::GET_WORD:
                    $res = $this->resGetWord($values);
                    break;
                case ParserCommands::WRITE_WORD:
                    $res = $this->resWriteWord($values);
                    break;
                case ParserCommands::GET_DWORD:
                    $res = $this->resGetDWord($values);
                    break;
                case ParserCommands::WRITE_DWORD:
                    $res = $this->resWriteDWord($values);
                    break;
                case ParserCommands::GET_INT:
                    $res = $this->resGetInt($values);
                    break;
                case ParserCommands::WRITE_INT:
                    $res = $this->resWriteInt($values);
                    break;
                case ParserCommands::GET_REAL:
                    $res = $this->resGetReal($values);
                    break;
                case ParserCommands::WRITE_REAL:
                    $res = $this->resWriteReal($values);
                    break;
                case ParserCommands::MULTI_CMD:
                    $res = $this->resMultiCmd($values);
                    break;
                case ParserCommands::ACK_ALARM:
                    $res = $this->resAckAlarm($values);
                    break;
                case ParserCommands::GET_THREAD_CYCLE_TIME:
                    $res = $this->resGetThreadCycleTime($values);
                    break;
                case ParserCommands::EXIT_APP:
                    $res = $this->resExitApp($values);
                    break;
            }
        }

        return $res;
    }
}
