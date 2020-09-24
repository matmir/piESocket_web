<?php

namespace App\Entity\Admin;

use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * Class represents PLC tag area
 *
 * @author Mateusz Mirosławski
 */
abstract class TagArea
{
    /**
     * Area identifiers
     */
    public const INPUT = 1;
    public const OUTPUT = 2;
    public const MEMORY = 3;
    
    /**
     * Area names
     */
    public const N_INPUT = 'Input';
    public const N_OUTPUT = 'Output';
    public const N_MEMORY = 'Memory';
    
    /**
     * Area prefixes
     */
    public const P_INPUT = 'I';
    public const P_OUTPUT = 'Q';
    public const P_MEMORY = 'M';
    
    /**
     * Check Tag area identifier
     *
     * @param int $areaId Tag area identifier
     * @throws Exception if area identifier is invalid
     */
    public static function check(int $areaId)
    {
        if ($areaId < 1 || $areaId > 3) {
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
    public static function getName(int $areaId)
    {
        $ret = '';
        
        switch ($areaId) {
            case self::INPUT:
                $ret = self::N_INPUT;
                break;
            case self::OUTPUT:
                $ret = self::N_OUTPUT;
                break;
            case self::MEMORY:
                $ret = self::N_MEMORY;
                break;
            default:
                throw new Exception('TagArea::getName: Invalid Tag area identifier');
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
    public static function getPrefix(int $areaId)
    {
        $ret = '';
        
        switch ($areaId) {
            case self::INPUT:
                $ret = self::P_INPUT;
                break;
            case self::OUTPUT:
                $ret = self::P_OUTPUT;
                break;
            case self::MEMORY:
                $ret = self::P_MEMORY;
                break;
            default:
                throw new Exception('TagArea::getPrefix: Invalid Tag area identifier');
        }
        
        return $ret;
    }
}
