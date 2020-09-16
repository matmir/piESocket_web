<?php

namespace App\Entity\Admin;

use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * Class represents tag logger interval
 *
 * @author Mateusz Mirosławski
 */
abstract class TagLoggerInterval
{
    /**
     * Intervals identifiers
     */
    public const I_100MS = 1;
    public const I_200MS = 2;
    public const I_500MS = 3;
    public const I_1S = 4;
    public const I_XS = 5;
    public const I_ON_CHANGE = 6;
    
    /**
     * Intervals names
     */
    public const N_I_100MS = '100ms';
    public const N_I_200MS = '200ms';
    public const N_I_500MS = '500ms';
    public const N_I_1S = '1s';
    public const N_I_XS = 'Xs';
    public const N_I_ON_CHANGE = 'On change';
    
    /**
     * Check Logger interval identifier
     *
     * @param int $intervalId Logger interval identifier
     * @throws Exception if interval identifier is invalid
     */
    public static function check(int $intervalId)
    {
        if ($intervalId < 0 || $intervalId > 6) {
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
    public static function getName(int $intervalId)
    {
        $ret = '';
        
        switch ($intervalId) {
            case self::I_100MS:
                $ret = self::N_I_100MS;
                break;
            case self::I_200MS:
                $ret = self::N_I_200MS;
                break;
            case self::I_500MS:
                $ret = self::N_I_500MS;
                break;
            case self::I_1S:
                $ret = self::N_I_1S;
                break;
            case self::I_XS:
                $ret = self::N_I_XS;
                break;
            case self::I_ON_CHANGE:
                $ret = self::N_I_ON_CHANGE;
                break;
            default:
                throw new Exception('TagLoggerInterval::getName: Invalid interval identifier');
        }
        
        return $ret;
    }
}
