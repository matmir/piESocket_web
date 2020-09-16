<?php

namespace App\Entity\Admin;

use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * Class represents PLC tag type
 *
 * @author Mateusz Mirosławski
 */
abstract class TagType
{
    /**
     * Type identifiers
     */
    public const BIT = 1;
    public const BYTE = 2;
    public const WORD = 3;
    public const DWORD = 4;
    public const INT = 5;
    public const REAL = 6;
    
    /**
     * Type names
     */
    public const N_BIT = 'Bit';
    public const N_BYTE = 'Byte';
    public const N_WORD = 'Word';
    public const N_DWORD = 'DWord';
    public const N_INT = 'INT';
    public const N_REAL = 'REAL';
    
    /**
     * Check Tag type identifier
     *
     * @param int $typeId Tag type identifier
     * @throws Exception if type identifier is invalid
     */
    public static function check(int $typeId)
    {
        if ($typeId < 0 || $typeId > 6) {
            throw new Exception('TagType::check: Invalid Tag type identifier');
        }
    }
    
    /**
     * Get Tag type name
     *
     * @param int $typeId Tag type identifier
     * @return String Tag type name
     * @throws Exception if type identifier is invalid
     */
    public static function getName(int $typeId)
    {
        $ret = '';
        
        switch ($typeId) {
            case self::BIT:
                $ret = self::N_BIT;
                break;
            case self::BYTE:
                $ret = self::N_BYTE;
                break;
            case self::WORD:
                $ret = self::N_WORD;
                break;
            case self::DWORD:
                $ret = self::N_DWORD;
                break;
            case self::INT:
                $ret = self::N_INT;
                break;
            case self::REAL:
                $ret = self::N_REAL;
                break;
            default:
                throw new Exception('TagType::getName: Invalid Tag type identifier');
        }
        
        return $ret;
    }
}
