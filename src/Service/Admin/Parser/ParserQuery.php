<?php

namespace App\Service\Admin\Parser;

use App\Service\Admin\Parser\ParserCommands;
use App\Service\Admin\Parser\ParserSeparators;
use App\Service\Admin\Parser\ParserException;
use App\Entity\Admin\User;

/**
 * Query parser - Class for parse query for C++ application
 *
 * @author Mateusz Mirosławski
 */
class ParserQuery
{
    /**
     * Array with access rights to query.
     *
     * Eg:
     * array(
     *  [0] => array('tagName' => 'TestTag1', 'read' => true),
     *  [1] => array('tagName' => 'TestTag2', 'read' => false),
     *  [2] => array('specialFunc' => 'function name', 'role' => 'ROLE_ADMIN'),
     * )
     */
    private $accessRights = [];
    
    /**
     * Alarm acknowledgement rights
     */
    private $ackRights;
    
    /**
     * Default constructor
     */
    public function __construct()
    {
        $this->accessRights = [];
        $this->ackRights = 'ROLE_USER';
    }
    
    /**
     * Set alarm acknowledgement rights
     *
     * @param string $role Role name
     */
    public function setAckRights(string $role)
    {
        // Check role name
        User::checkRole($role);
        
        $this->ackRights = $role;
    }
    
    /**
     * Get alarm acknowledgement rights
     *
     * @return string Role name
     */
    public function getAckRights(): string
    {
        return $this->ackRights;
    }
    
    /**
     * Clear access rights array
     */
    private function clearAccessRights()
    {
        $this->accessRights = [];
    }
    
    /**
     * Add tag to tag access rights array
     *
     * @param string $tagName Tag name
     * @param bool $cmd Command number
     */
    private function addTagAccess(string $tagName, int $cmd)
    {
        $ar = array(
            'tagName' => $tagName,
            'read' => $this->getReadAccessFlag($cmd)
        );
        
        // Add to the array
        array_push($this->accessRights, $ar);
    }
    
    /**
     * Add special access rights to array
     *
     * @param string $roleName Role name
     * @param string $funcName Function name
     */
    private function addSpecialAccess(string $roleName, string $funcName)
    {
        $ar = array(
            'specialFunc' => $funcName,
            'role' => $roleName
        );
        
        // Add to the array
        array_push($this->accessRights, $ar);
    }
    
    /**
     * Get read access flag from command number
     *
     * @param int $cmd Command number
     * @return bool Read access flag
     */
    private function getReadAccessFlag(int $cmd): bool
    {
        // true - read access/ false - write access
        $ret = true;
        
        switch ($cmd) {
            case ParserCommands::GET_BIT:
                $ret = true;
                break;
            case ParserCommands::SET_BIT:
                $ret = false;
                break;
            case ParserCommands::RESET_BIT:
                $ret = false;
                break;
            case ParserCommands::INVERT_BIT:
                $ret = false;
                break;
            case ParserCommands::GET_BITS:
                $ret = true;
                break;
            case ParserCommands::SET_BITS:
                $ret = false;
                break;
            case ParserCommands::GET_BYTE:
                $ret = true;
                break;
            case ParserCommands::WRITE_BYTE:
                $ret = false;
                break;
            case ParserCommands::GET_WORD:
                $ret = true;
                break;
            case ParserCommands::WRITE_WORD:
                $ret = false;
                break;
            case ParserCommands::GET_DWORD:
                $ret = true;
                break;
            case ParserCommands::WRITE_DWORD:
                $ret = false;
                break;
            case ParserCommands::GET_INT:
                $ret = true;
                break;
            case ParserCommands::WRITE_INT:
                $ret = false;
                break;
            case ParserCommands::GET_REAL:
                $ret = true;
                break;
            case ParserCommands::WRITE_REAL:
                $ret = false;
                break;
        }
        
        return $ret;
    }
    
    /**
     * Get access rights to query
     *
     * @return array Array with access rights to query
     */
    public function getAccessRights(): array
    {
        return $this->accessRights;
    }
    
    /**
     * Parse tag name (remove white spaces and special characters)
     *
     * @param string $tag Tag name
     * @return string Tag name
     * @throws ParserException
     */
    private function parseTagName(string $tag): string
    {
        // Check tag name
        if (empty($tag)) {
            throw new ParserException('parseTagName: Tag name is empty!');
        }

        // Remove white spaces
        $ret = preg_replace('/\s+/', '', $tag);

        // Remove special characters (allow _ )
        $ret = preg_replace('/[^A-Za-z0-9_]/', '', $ret);

        return $ret;
    }
    
    /**
     * Prepare string with CMD|Tag
     *
     * @param array $data data
     * @param string $func Function name
     * @return string
     * @throws ParserException
     */
    private function cmdTagC1(array $data, string $func): string
    {
        // Check function name
        if (empty($func)) {
            throw new ParserException('cmd_TAG_C1: Function name is empty!');
        }

        $cmd = $data['cmd'];

        // Check if array has 'tag' field
        if (!array_key_exists('tag', $data)) {
            throw new ParserException($func . ': Missing tag field in array!');
        }

        // Get tag name
        $tag = $this->parseTagName($data['tag']);
        
        // Tag access
        $this->addTagAccess($tag, $cmd);

        return $cmd . ParserSeparators::CVS . $tag;
    }
    
    /**
     * Prepare string with CMD|Tag1,Tag2,Tag3,...
     *
     * @param array $data data
     * @param string $func Function name
     * @return string
     * @throws ParserException
     */
    private function cmdTagC2(array $data, string $func): string
    {
        // Check function name
        if (empty($func)) {
            throw new ParserException('cmd_TAG_C2: Function name is empty!');
        }

        $cmd = $data['cmd'];

        // Check if array has 'tags' field
        if (!array_key_exists('tags', $data)) {
            throw new ParserException($func . ': Missing tags field in array!');
        }

        // Tags need to be array
        if (!is_array($data['tags'])) {
            throw new ParserException($func . ': Tags field is not array!');
        }

        $tags = '';
        $cn = count($data['tags']);

        // Get tag names
        for ($i = 0; $i < $cn; ++$i) {
            $tags = $tags . $this->parseTagName($data['tags'][$i]);
            
            // Tag access
            $this->addTagAccess($data['tags'][$i], $cmd);

            // Add ',' to the string
            if ($i != ($cn - 1)) {
                $tags = $tags . ParserSeparators::VS;
            }
        }

        return $cmd . ParserSeparators::CVS . $tags;
    }
    
    /**
     * Prepare string with CMD|Tag,value
     *
     * @param array $data data
     * @param string $func Function name
     * @return string
     * @throws ParserException
     */
    private function cmdTagC3(array $data, string $func): string
    {
        // Check function name
        if (empty($func)) {
            throw new ParserException('cmd_TAG_C3: Function name is empty!');
        }

        $cmd = $data['cmd'];

        // Check if array has 'tag' field
        if (!array_key_exists('tag', $data)) {
            throw new ParserException($func . ': Missing tag field in array!');
        }

        // Check if array has 'value' field
        if (!array_key_exists('value', $data)) {
            throw new ParserException($func . ': Missing value field in array!');
        }

        // Check if value is number
        if (!is_numeric($data['value'])) {
            throw new ParserException($func . ': Value need to be numeric!');
        }

        // Check value range BYTE
        if ($func == 'CMD_WRITE_BYTE') {
            if (!($data['value'] >= 0 && $data['value'] < 256 && is_int($data['value']))) {
                throw new ParserException($func . ': Value is out of range!');
            }
        }
        // Check value range WORD
        if ($func == 'CMD_WRITE_WORD') {
            if (!($data['value'] >= 0 && $data['value'] < 65535 && is_int($data['value']))) {
                throw new ParserException($func . ': Value is out of range!');
            }
        }
        // Check value range DWORD
        if ($func == 'CMD_WRITE_DWORD') {
            if (!($data['value'] >= 0 && is_numeric($data['value']))) {
                throw new ParserException($func . ': Value is out of range!');
            }
        }
        // Check value range INT
        if ($func == 'CMD_WRITE_INT') {
            if (!($data['value'] >= -2147483648 && $data['value'] < 2147483647 && is_int($data['value']))) {
                throw new ParserException($func . ': Value is out of range!');
            }
        }
        // Check value range REAL
        if ($func == 'CMD_WRITE_REAL') {
            if (!(is_float($data['value']))) {
                throw new ParserException($func . ': Value is out of range!');
            }
        }

        // Get tag name
        $tag = $this->parseTagName($data['tag']);
        
        // Tag access
        $this->addTagAccess($tag, $cmd);

        // Get value
        $value = $data['value'];

        return $cmd . ParserSeparators::CVS . $tag . ParserSeparators::VS . $value;
    }
    
    /**
     * Prepare string with GET_BIT command
     *
     * @param array $data Command data
     * @return string
     */
    private function cmdGetBit(array $data): string
    {
        return $this->cmdTagC1($data, 'CMD_GET_BIT');
    }
    
    /**
     * Prepare string with SET_BIT command
     *
     * @param array $data Command data
     * @return string
     */
    private function cmdSetBit(array $data): string
    {
        return $this->cmdTagC1($data, 'CMD_SET_BIT');
    }
    
    /**
     * Prepare string with RESET_BIT command
     *
     * @param array $data Command data
     * @return string
     */
    private function cmdResetBit(array $data): string
    {
        return $this->cmdTagC1($data, 'CMD_RESET_BIT');
    }
    
    /**
     * Prepare string with INVERT_BIT command
     *
     * @param array $data Command data
     * @return string
     */
    private function cmdInvertBit(array $data): string
    {
        return $this->cmdTagC1($data, 'CMD_INVERT_BIT');
    }
    
    /**
     * Prepare string with GET_BITS command
     *
     * @param array $data Command data
     * @return string
     */
    private function cmdGetBits(array $data): string
    {
        return $this->cmdTagC2($data, 'CMD_GET_BITS');
    }
    
    /**
     * Prepare string with SET_BITS command
     *
     * @param array $data Command data
     * @return string
     */
    private function cmdSetBits(array $data): string
    {
        return $this->cmdTagC2($data, 'CMD_SET_BITS');
    }
    
    /**
     * Prepare string with GET_BYTE command
     *
     * @param array $data Command data
     * @return string
     */
    private function cmdGetByte(array $data): string
    {
        return $this->cmdTagC1($data, 'CMD_GET_BYTE');
    }
    
    /**
     * Prepare string with WRITE_BYTE command
     *
     * @param array $data Command data
     * @return string
     */
    private function cmdWriteByte(array $data): string
    {
        return $this->cmdTagC3($data, 'CMD_WRITE_BYTE');
    }
    
    /**
     * Prepare string with GET_WORD command
     *
     * @param array $data Command data
     * @return string
     */
    private function cmdGetWord(array $data): string
    {
        return $this->cmdTagC1($data, 'CMD_GET_WORD');
    }
    
    /**
     * Prepare string with WRITE_WORD command
     *
     * @param array $data Command data
     * @return string
     */
    private function cmdWriteWord(array $data): string
    {
        return $this->cmdTagC3($data, 'CMD_WRITE_WORD');
    }
    
    /**
     * Prepare string with GET_DWORD command
     *
     * @param array $data Command data
     * @return string
     */
    private function cmdGetDWord(array $data): string
    {
        return $this->cmdTagC1($data, 'CMD_GET_DWORD');
    }
    
    /**
     * Prepare string with WRITE_DWORD command
     *
     * @param array $data Command data
     * @return string
     */
    private function cmdWriteDWord(array $data): string
    {
        return $this->cmdTagC3($data, 'CMD_WRITE_DWORD');
    }
    
    /**
     * Prepare string with GET_INT command
     *
     * @param array $data Command data
     * @return string
     */
    private function cmdGetInt(array $data): string
    {
        return $this->cmdTagC1($data, 'CMD_GET_INT');
    }
    
    /**
     * Prepare string with WRITE_INT command
     *
     * @param array $data Command data
     * @return string
     */
    private function cmdWriteInt(array $data): string
    {
        return $this->cmdTagC3($data, 'CMD_WRITE_INT');
    }
    
    /**
     * Prepare string with GET_REAL command
     *
     * @param array $data Command data
     * @return string
     */
    private function cmdGetReal(array $data): string
    {
        return $this->cmdTagC1($data, 'CMD_GET_REAL');
    }
    
    /**
     * Prepare string with WRITE_REAL command
     *
     * @param array $data Command data
     * @return string
     */
    private function cmdWriteReal(array $data): string
    {
        return $this->cmdTagC3($data, 'CMD_WRITE_REAL');
    }
    
    /**
     * Prepare string with MULTI_CMD command
     *
     * @param array $data Command data
     * @return string
     */
    private function cmdMultiCmd(array $data): string
    {
        $cmd = $data['cmd'];

        // Check if array has 'value' field
        if (!array_key_exists('value', $data)) {
            throw new ParserException('CMD_MULTI_CMD: Missing value field in array!');
        }

        // Check if value is array
        if (!is_array($data['value'])) {
            throw new ParserException('CMD_MULTI_CMD: Value need to be an array!');
        }

        $commands = '';
        $cn = count($data['value']);

        // Parse commands
        for ($i = 0; $i < $cn; ++$i) {
            $item = $data['value'][$i];

            // Check if item is array
            if (!is_array($item)) {
                throw new ParserException('CMD_MULTI_CMD: Data is not array!');
            }

            // Check if item has 'cmd' field
            if (!array_key_exists('cmd', $item)) {
                throw new ParserException('CMD_MULTI_CMD: Missing command field in array!');
            }

            $icmd = $item['cmd'];
            // Check command number
            if (!ParserCommands::checkCMD($icmd)) {
                throw new ParserException('CMD_MULTI_CMD: Wrong command number!');
            }
            if ($icmd == ParserCommands::MULTI_CMD) {
                throw new ParserException('CMD_MULTI_CMD: Can not call MULTI_CMD inside MULTI_CMD!');
            }
            if ($icmd == ParserCommands::GET_THREAD_CYCLE_TIME) {
                throw new ParserException('CMD_MULTI_CMD: Can not call GET_THREAD_CYCLE_TIME inside MULTI_CMD!');
            }

            // String with query
            $ret = '';

            // Parse values
            switch ($icmd) {
                case ParserCommands::GET_BIT:
                    $ret = $this->cmdGetBit($item);
                    break;
                case ParserCommands::SET_BIT:
                    $ret = $this->cmdSetBit($item);
                    break;
                case ParserCommands::RESET_BIT:
                    $ret = $this->cmdResetBit($item);
                    break;
                case ParserCommands::INVERT_BIT:
                    $ret = $this->cmdInvertBit($item);
                    break;
                case ParserCommands::GET_BITS:
                    $ret = $this->cmdGetBits($item);
                    break;
                case ParserCommands::SET_BITS:
                    $ret = $this->cmdSetBits($item);
                    break;
                case ParserCommands::GET_BYTE:
                    $ret = $this->cmdGetByte($item);
                    break;
                case ParserCommands::WRITE_BYTE:
                    $ret = $this->cmdWriteByte($item);
                    break;
                case ParserCommands::GET_WORD:
                    $ret = $this->cmdGetWord($item);
                    break;
                case ParserCommands::WRITE_WORD:
                    $ret = $this->cmdWriteWord($item);
                    break;
                case ParserCommands::GET_DWORD:
                    $ret = $this->cmdGetDWord($item);
                    break;
                case ParserCommands::WRITE_DWORD:
                    $ret = $this->cmdWriteDWord($item);
                    break;
                case ParserCommands::GET_INT:
                    $ret = $this->cmdGetInt($item);
                    break;
                case ParserCommands::WRITE_INT:
                    $ret = $this->cmdWriteInt($item);
                    break;
                case ParserCommands::GET_REAL:
                    $ret = $this->cmdGetReal($item);
                    break;
                case ParserCommands::WRITE_REAL:
                    $ret = $this->cmdWriteReal($item);
                    break;
                case ParserCommands::ACK_ALARM:
                    $ret = $this->cmdAckAlarm($item);
                    break;
                case ParserCommands::EXIT_APP:
                    $ret = $this->cmdExitApp($item);
                    break;
            }

            // Add commands
            $commands = $commands . str_replace(ParserSeparators::CVS, ParserSeparators::CVMS, $ret);

            // Add '!' to the string
            if ($i != ($cn - 1)) {
                $commands = $commands . ParserSeparators::CMS;
            }
        }

        return $cmd . ParserSeparators::CVS . $commands;
    }
    
    /**
     * Prepare string with ACK_ALARM command
     *
     * @param array $data Command data
     * @return string
     */
    private function cmdAckAlarm(array $data): string
    {
        $cmd = $data['cmd'];

        // Check if array has 'alarm_id' field
        if (!array_key_exists('alarm_id', $data)) {
            throw new ParserException('CMD_ACK_ALARM: Missing alarm_id field in array!');
        }

        // Check if alarm_id is number
        if (!is_numeric($data['alarm_id'])) {
            throw new ParserException('CMD_ACK_ALARM: alarm_id need to be numeric!');
        }
        
        // Special access rights
        $this->addSpecialAccess($this->ackRights, 'CMD_ACK_ALARM');

        return $cmd . ParserSeparators::CVS . $data['alarm_id'];
    }
    
    /**
     * Prepare string with GET_THREAD_CYCLE_TIME command
     *
     * @param array $data Command data
     * @return string
     */
    private function cmdGetThreadCycleTime(array $data): string
    {
        $cmd = $data['cmd'];
        
        // Special access rights
        $this->addSpecialAccess('ROLE_ADMIN', 'CMD_GET_THREAD_CYCLE_TIME');

        return $cmd . ParserSeparators::CVS . '1';
    }
    
    /**
     * Prepare string with EXIT_APP command
     *
     * @param array $data Command data
     * @return string
     */
    private function cmdExitApp(array $data): string
    {
        $cmd = $data['cmd'];
        
        // Special access rights
        $this->addSpecialAccess('ROLE_ADMIN', 'CMD_EXIT_APP');

        return $cmd . ParserSeparators::CVS . '1';
    }
    
    /**
     * Prepare query for C++ application
     *
     * @param array $data Data with query
     * @return string
     * @throws ParserException
     */
    public function query(array $data): string
    {
        // Check if array has 'cmd' field
        if (!array_key_exists('cmd', $data)) {
            throw new ParserException('query: Missing command field in array!');
        }

        $cmd = $data['cmd'];
        // Check command number
        if (!ParserCommands::checkCMD($cmd)) {
            throw new ParserException('query: Wrong command number!');
        }

        // String with query
        $ret = '';
        
        // Clear Tag access array
        $this->clearAccessRights();

        // Parse values
        switch ($cmd) {
            case ParserCommands::GET_BIT:
                $ret = $this->cmdGetBit($data);
                break;
            case ParserCommands::SET_BIT:
                $ret = $this->cmdSetBit($data);
                break;
            case ParserCommands::RESET_BIT:
                $ret = $this->cmdResetBit($data);
                break;
            case ParserCommands::INVERT_BIT:
                $ret = $this->cmdInvertBit($data);
                break;
            case ParserCommands::GET_BITS:
                $ret = $this->cmdGetBits($data);
                break;
            case ParserCommands::SET_BITS:
                $ret = $this->cmdSetBits($data);
                break;
            case ParserCommands::GET_BYTE:
                $ret = $this->cmdGetByte($data);
                break;
            case ParserCommands::WRITE_BYTE:
                $ret = $this->cmdWriteByte($data);
                break;
            case ParserCommands::GET_WORD:
                $ret = $this->cmdGetWord($data);
                break;
            case ParserCommands::WRITE_WORD:
                $ret = $this->cmdWriteWord($data);
                break;
            case ParserCommands::GET_DWORD:
                $ret = $this->cmdGetDWord($data);
                break;
            case ParserCommands::WRITE_DWORD:
                $ret = $this->cmdWriteDWord($data);
                break;
            case ParserCommands::GET_INT:
                $ret = $this->cmdGetInt($data);
                break;
            case ParserCommands::WRITE_INT:
                $ret = $this->cmdWriteInt($data);
                break;
            case ParserCommands::GET_REAL:
                $ret = $this->cmdGetReal($data);
                break;
            case ParserCommands::WRITE_REAL:
                $ret = $this->cmdWriteReal($data);
                break;
            case ParserCommands::MULTI_CMD:
                $ret = $this->cmdMultiCmd($data);
                break;
            case ParserCommands::ACK_ALARM:
                $ret = $this->cmdAckAlarm($data);
                break;
            case ParserCommands::GET_THREAD_CYCLE_TIME:
                $ret = $this->cmdGetThreadCycleTime($data);
                break;
            case ParserCommands::EXIT_APP:
                $ret = $this->cmdExitApp($data);
                break;
        }

        return $ret;
    }
}
