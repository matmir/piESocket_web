<?php

namespace App\Entity;
use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * Application exception class
 *
 * @author Mateusz Mirosławski
 */
class AppException extends Exception {
    
    const ALARM_TAG_EXIST = 1;
    const ALARM_TRIGGER_WRONG_TYPE = 2;
    
    const SCRIPT_TAG_EXIST = 10;
    const SCRIPT_FILE_EXIST = 11;
    const SCRIPT_FILE_NOT_EXIST = 12;
    
    const SCRIPT_DIRECTORY_NOT_EXIST = 20;
    const SCRIPT_DIRECTORY_NOT_VALID = 21;
    const SCRIPT_MISSING = 22;
    const SCRIPT_WRONG_REPLY = 23;
    
    const SOCKET_CREATE = 30;
    const SOCKET_CONNECT = 31;
    const SOCKET_SEND = 32;
    const SOCKET_READ = 33;
    const SOCKET_ERROR = 34;
    
    const LOGGER_TAG_EXIST = 40;
    
    const TAG_NAME_EXIST = 50;
    const TAG_ADDRESS_EXIST = 51;
    const TAG_IS_USED = 52;
    const TAG_NOT_EXIST = 53;
    const TAG_READ_ACCESS_DENIED = 54;
    const TAG_WRITE_ACCESS_DENIED = 55;
    const TAG_ACCESS_ARRAY_WRONG = 56;
    const TAG_BYTE_ADDRESS_WRONG = 57;
    
    const USER_NAME_EXIST = 60;
    const USER_ADDRESS_EXIST = 61;
    const USER_NOT_EXIST = 62;
    const USER_PASSWORD_NOT_EQUAL = 62;
    const USER_OLD_PASSWORD_WRONG = 63;
    
    const SPECIAL_FUNCTION_DENIED = 70;
}
