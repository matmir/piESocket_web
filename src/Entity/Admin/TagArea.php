<?php

namespace App\Entity\Admin;

use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * Class represents PLC tag area
 * 
 * @author Mateusz Mirosławski
 */
abstract class TagArea {
    
    /**
     * Area identifiers
     */
    const input = 1;
    const output = 2;
    const memory = 3;
    
    /**
     * Area names
     */
    const nInput = 'Input';
    const nOutput = 'Output';
    const nMemory = 'Memory';
    
    /**
     * Area prefixes
     */
    const pInput = 'I';
    const pOutput = 'Q';
    const pMemory = 'M';
    
    /**
     * Check Tag area identifier
     * 
     * @param int $areaId Tag area identifier
     * @throws Exception if area identifier is invalid
     */
    public static function check(int $areaId) {
        
        if ($areaId<1 || $areaId>3) {
            throw new Exception('TagArea::check: Invalid Tag area identifier');
        }
    }
    
    /**
     * Get Tag area name
     * 
     * @param int $areaId Tag area identifier
     * @return String Tag area name
     * @throws Exception if Tag area identifier is invalid
     */
    public static function getName(int $areaId) {
        
        $ret = '';
        
        switch ($areaId) {
            case self::input: $ret = self::nInput; break;
            case self::output: $ret = self::nOutput; break;
            case self::memory: $ret = self::nMemory; break;
            default: throw new Exception('TagArea::getName: Invalid Tag area identifier');
        }
        
        return $ret;
    }
    
    /**
     * Get Tag prefix
     * 
     * @param int $areaId Tag area identifier
     * @return String Tag area prefix
     * @throws Exception if Tag area identifier is invalid
     */
    public static function getPrefix(int $areaId) {
        
        $ret = '';
        
        switch ($areaId) {
            case self::input: $ret = self::pInput; break;
            case self::output: $ret = self::pOutput; break;
            case self::memory: $ret = self::pMemory; break;
            default: throw new Exception('TagArea::getPrefix: Invalid Tag area identifier');
        }
        
        return $ret;
    }
}
