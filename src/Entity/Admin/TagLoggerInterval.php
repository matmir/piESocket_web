<?php

namespace App\Entity\Admin;

use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * Class represents tag logger interval
 * 
 * @author Mateusz Mirosławski
 */
abstract class TagLoggerInterval {
    
    /**
     * Intervals identifiers
     */
    const I_100MS = 1;
    const I_200MS = 2;
    const I_500MS = 3;
    const I_1S = 4;
    const I_XS = 5;
    const I_ON_CHANGE = 6;
    
    /**
     * Intervals names
     */
    const nI_100MS = '100ms';
    const nI_200MS = '200ms';
    const nI_500MS = '500ms';
    const nI_1S = '1s';
    const nI_XS = 'Xs';
    const nI_ON_CHANGE = 'On change';
    
    /**
     * Check Logger interval identifier
     * 
     * @param int $intervalId Logger interval identifier
     * @throws Exception if interval identifier is invalid
     */
    public static function check(int $intervalId) {
        
        if ($intervalId<0 || $intervalId>6) {
            throw new Exception('TagLoggerInterval::check: Invalid interval identifier');
        }
    }
    
    /**
     * Get Logger interval name
     * 
     * @param int $intervalId Logger interval identifier
     * @return String Logger interval name
     * @throws Exception if interval identifier is invalid
     */
    public static function getName(int $intervalId) {
        
        $ret = '';
        
        switch ($intervalId) {
            case self::I_100MS: $ret = self::nI_100MS; break;
            case self::I_200MS: $ret = self::nI_200MS; break;
            case self::I_500MS: $ret = self::nI_500MS; break;
            case self::I_1S: $ret = self::nI_1S; break;
            case self::I_XS: $ret = self::nI_XS; break;
            case self::I_ON_CHANGE: $ret = self::nI_ON_CHANGE; break;
            default: throw new Exception('TagLoggerInterval::getName: Invalid interval identifier');
        }
        
        return $ret;
    }
}
