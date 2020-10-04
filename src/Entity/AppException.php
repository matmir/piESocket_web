<?php

namespace App\Entity;

use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * Application exception class
 *
 * @author Mateusz Mirosławski
 */
class AppException extends Exception
{
    public const ALARM_TAG_EXIST = 1;
    public const ALARM_TRIGGER_WRONG_TYPE = 2;
    
    public const SCRIPT_TAG_EXIST = 10;
    public const SCRIPT_FILE_EXIST = 11;
    public const SCRIPT_FILE_NOT_EXIST = 12;
    
    public const SCRIPT_DIRECTORY_NOT_EXIST = 20;
    public const SCRIPT_DIRECTORY_NOT_VALID = 21;
    public const SCRIPT_MISSING = 22;
    public const SCRIPT_WRONG_REPLY = 23;
    
    public const SOCKET_CREATE = 30;
    public const SOCKET_CONNECT = 31;
    public const SOCKET_SEND = 32;
    public const SOCKET_READ = 33;
    public const SOCKET_ERROR = 34;
    
    public const LOGGER_TAG_EXIST = 40;
    public const LOGGER_INTERVALS_WRONG = 41;
    
    public const TAG_NAME_EXIST = 50;
    public const TAG_ADDRESS_EXIST = 51;
    public const TAG_IS_USED = 52;
    public const TAG_NOT_EXIST = 53;
    public const TAG_READ_ACCESS_DENIED = 54;
    public const TAG_WRITE_ACCESS_DENIED = 55;
    public const TAG_ACCESS_ARRAY_WRONG = 56;
    public const TAG_BYTE_ADDRESS_WRONG = 57;
    public const TAG_WRONG_TYPE = 58;
    public const TAG_WRONG_AREA = 59;
    
    public const USER_NAME_EXIST = 60;
    public const USER_ADDRESS_EXIST = 61;
    public const USER_NOT_EXIST = 62;
    public const USER_PASSWORD_NOT_EQUAL = 62;
    public const USER_OLD_PASSWORD_WRONG = 63;
    
    public const SPECIAL_FUNCTION_DENIED = 70;
    
    public const SHM_EXIST = 80;
    public const DRIVER_EXIST = 81;
    public const MODBUS_ADDRESS_EXIST = 82;
    public const DRIVER_LIMIT = 83;
    public const DRIVER_USED = 84;
}
