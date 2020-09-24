<?php

namespace App\Entity\Admin;

use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * Class represents driver type
 *
 * @author Mateusz Mirosławski
 */
abstract class DriverType
{
    /**
     * Type identifiers
     */
    public const SHM = 0;
    public const MODBUS = 1;
    
    /**
     * Type names
     */
    public const N_SHM = 'SHM';
    public const N_MODBUS = 'Modbus';
    
    /**
     * Check driver type identifier
     *
     * @param int $typeId driver type identifier
     * @throws Exception if type identifier is invalid
     */
    public static function check(int $typeId)
    {
        if ($typeId < 0 || $typeId > 1) {
            throw new Exception('DriverType::check: Invalid driver type identifier');
        }
    }
    
    /**
     * Get Driver type name
     *
     * @param int $typeId Driver type identifier
     * @return String Driver type name
     * @throws Exception if type identifier is invalid
     */
    public static function getName(int $typeId)
    {
        $ret = '';
        
        switch ($typeId) {
            case self::SHM:
                $ret = self::N_SHM;
                break;
            case self::MODBUS:
                $ret = self::N_MODBUS;
                break;
            default:
                throw new Exception('DriverType::getName: Invalid driver type identifier');
        }
        
        return $ret;
    }
}
