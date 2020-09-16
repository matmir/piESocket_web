<?php

namespace App\Entity\Admin;

use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * Class represents alarm trigger
 *
 * @author Mateusz Mirosławski
 */
abstract class AlarmTrigger
{
    /**
     * Trigger identifiers
     */
    public const TR_BIN = 1;
    /// Tag greater than value
    public const TR_TAG_GT_VAL = 2;
    /// Tag less than value
    public const TR_TAG_LT_VAL = 3;
    /// Tag greater or equal than value
    public const TR_TAG_GTE_VAL = 4;
    /// Tag less or equal than value
    public const TR_TAG_LTE_VAL = 5;
    /// Tag equal value
    public const TR_TAG_EQ_VAL = 6;
    /// Tag not equal value
    public const TR_TAG_NEQ_VAL = 7;
    
    /**
     * Trigger names
     */
    public const N_TR_BIN = 'BIN';
    /// Tag greater than value
    public const N_TR_TAG_GT_VAL = 'Tag>value';
    /// Tag less than value
    public const N_TR_TAG_LT_VAL = 'Tag<value';
    /// Tag greater or equal than value
    public const N_TR_TAG_GTE_VAL = 'Tag>=value';
    /// Tag less or equal than value
    public const N_TR_TAG_LTE_VAL = 'Tag<=value';
    /// Tag equal value
    public const N_TR_TAG_EQ_VAL = 'Tag=value';
    /// Tag not equal value
    public const N_TR_TAG_NEQ_VAL = 'Tag!=value';
    
    /**
     * Check Alarm trigger identifier
     *
     * @param int $triggerId Alarm trigger identifier
     * @throws Exception if trigger identifier is invalid
     */
    public static function check(int $triggerId)
    {
        if ($triggerId < 0 || $triggerId > 7) {
            throw new Exception('AlarmTrigger::check: Invalid trigger identifier');
        }
    }
    
    /**
     * Get Alarm trigger name
     *
     * @param int $triggerId Alarm trigger identifier
     * @return String Alarm trigger name
     * @throws Exception if trigger identifier is invalid
     */
    public static function getName(int $triggerId)
    {
        $ret = '';
        
        switch ($triggerId) {
            case self::TR_BIN:
                $ret = self::N_TR_BIN;
                break;
            case self::TR_TAG_GT_VAL:
                $ret = self::N_TR_TAG_GT_VAL;
                break;
            case self::TR_TAG_LT_VAL:
                $ret = self::N_TR_TAG_LT_VAL;
                break;
            case self::TR_TAG_GTE_VAL:
                $ret = self::N_TR_TAG_GTE_VAL;
                break;
            case self::TR_TAG_LTE_VAL:
                $ret = self::N_TR_TAG_LTE_VAL;
                break;
            case self::TR_TAG_EQ_VAL:
                $ret = self::N_TR_TAG_EQ_VAL;
                break;
            case self::TR_TAG_NEQ_VAL:
                $ret = self::N_TR_TAG_NEQ_VAL;
                break;
            default:
                throw new Exception('AlarmTrigger::getName: Invalid trigger identifier');
        }
        
        return $ret;
    }
}
