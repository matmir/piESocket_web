<?php

namespace App\Service\Admin\Parser;

/**
 * Class contains parser command numbers
 *
 * @author Mateusz Mirosławski
 */
abstract class ParserCommands
{
    public const GET_BIT = 10;
    public const SET_BIT = 11;
    public const RESET_BIT = 12;
    public const INVERT_BIT = 13;

    public const GET_BITS = 20;
    public const SET_BITS = 21;

    public const GET_BYTE = 30;
    public const WRITE_BYTE = 31;

    public const GET_WORD = 32;
    public const WRITE_WORD = 33;

    public const GET_DWORD = 34;
    public const WRITE_DWORD = 35;

    public const GET_INT = 36;
    public const WRITE_INT = 37;

    public const GET_REAL = 38;
    public const WRITE_REAL = 39;

    public const MULTI_CMD = 50;

    public const ACK_ALARM = 90;
    
    public const GET_THREAD_CYCLE_TIME = 500;

    public const EXIT_APP = 600;
    
    /**
     * Check if command number is valid
     *
     * @param int $cmd Command number
     * @return bool
     */
    public static function checkCMD(int $cmd): bool
    {
        $ret = false;

        switch ($cmd) {
            case ParserCommands::GET_BIT:
                $ret = true;
                break;
            case ParserCommands::SET_BIT:
                $ret = true;
                break;
            case ParserCommands::RESET_BIT:
                $ret = true;
                break;
            case ParserCommands::INVERT_BIT:
                $ret = true;
                break;
            case ParserCommands::GET_BITS:
                $ret = true;
                break;
            case ParserCommands::SET_BITS:
                $ret = true;
                break;
            case ParserCommands::GET_BYTE:
                $ret = true;
                break;
            case ParserCommands::WRITE_BYTE:
                $ret = true;
                break;
            case ParserCommands::GET_WORD:
                $ret = true;
                break;
            case ParserCommands::WRITE_WORD:
                $ret = true;
                break;
            case ParserCommands::GET_DWORD:
                $ret = true;
                break;
            case ParserCommands::WRITE_DWORD:
                $ret = true;
                break;
            case ParserCommands::GET_INT:
                $ret = true;
                break;
            case ParserCommands::WRITE_INT:
                $ret = true;
                break;
            case ParserCommands::GET_REAL:
                $ret = true;
                break;
            case ParserCommands::WRITE_REAL:
                $ret = true;
                break;
            case ParserCommands::MULTI_CMD:
                $ret = true;
                break;
            case ParserCommands::ACK_ALARM:
                $ret = true;
                break;
            case ParserCommands::GET_THREAD_CYCLE_TIME:
                $ret = true;
                break;
            case ParserCommands::EXIT_APP:
                $ret = true;
                break;
        }

        return $ret;
    }
}
