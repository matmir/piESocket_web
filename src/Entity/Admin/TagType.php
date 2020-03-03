<?php

namespace App\Entity\Admin;

use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * Class represents PLC tag type
 * 
 * @author Mateusz Mirosławski
 */
abstract class TagType {
    
    /**
     * Type identifiers
     */
    const Bit = 1;
    const Byte = 2;
    const Word = 3;
    const DWord = 4;
    const INT = 5;
    const REAL = 6;
    
    /**
     * Type names
     */
    const nBit = 'Bit';
    const nByte = 'Byte';
    const nWord = 'Word';
    const nDWord = 'DWord';
    const nINT = 'INT';
    const nREAL = 'REAL';
    
    /**
     * Check Tag type identifier
     * 
     * @param int $typeId Tag type identifier
     * @throws Exception if type identifier is invalid
     */
    public static function check(int $typeId) {
        
        if ($typeId<0 || $typeId>6) {
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
    public static function getName(int $typeId) {
        
        $ret = '';
        
        switch ($typeId) {
            case self::Bit: $ret = self::nBit; break;
            case self::Byte: $ret = self::nByte; break;
            case self::Word: $ret = self::nWord; break;
            case self::DWord: $ret = self::nDWord; break;
            case self::INT: $ret = self::nINT; break;
            case self::REAL: $ret = self::nREAL; break;
            default: throw new Exception('TagType::getName: Invalid Tag type identifier');
        }
        
        return $ret;
    }
}
