<?php

namespace App\Entity\Admin;

use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * Class represents modbus driver mode
 * 
 * @author Mateusz Mirosławski
 */
abstract class DriverModbusMode {
    
    /**
     * Type identifiers
     */
    const RTU = 0;
    const TCP = 1;
    
    /**
     * Type names
     */
    const nRTU = 'RTU';
    const nTCP = 'TCP';
    
    /**
     * Check modbus driver mode identifier
     * 
     * @param int $modeId modbus driver mode identifier
     * @throws Exception if modbus driver mode identifier is invalid
     */
    public static function check(int $modeId) {
        
        if ($modeId<0 || $modeId>1) {
            throw new Exception('DriverModbusMode::check: Invalid modbus driver mode identifier');
        }
    }
    
    /**
     * Get Modbus driver mode name
     * 
     * @param int $modeId Modbus driver mode identifier
     * @return String Modbus driver mode name
     * @throws Exception if Modbus driver mode identifier is invalid
     */
    public static function getName(int $modeId) {
        
        $ret = '';
        
        switch ($modeId) {
            case self::RTU: $ret = self::nRTU; break;
            case self::TCP: $ret = self::nTCP; break;
            default: throw new Exception('DriverModbusMode::getName: Invalid modbus driver mode identifier');
        }
        
        return $ret;
    }
}
