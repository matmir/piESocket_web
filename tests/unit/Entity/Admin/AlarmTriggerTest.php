<?php

namespace App\Tests\Entity\Admin;

use App\Entity\Admin\AlarmTrigger;
use PHPUnit\Framework\TestCase;

/**
 * Unit test for AlarmTrigger class
 *
 * @author Mateusz Mirosławski
 */
class AlarmTriggerTest extends TestCase
{
    /**
     * Test getName method
     */
    public function testGetName1()
    {
        $this->assertEquals('BIN', AlarmTrigger::getName(AlarmTrigger::TR_BIN));
        $this->assertEquals('Tag>value', AlarmTrigger::getName(AlarmTrigger::TR_TAG_GT_VAL));
        $this->assertEquals('Tag<value', AlarmTrigger::getName(AlarmTrigger::TR_TAG_LT_VAL));
        $this->assertEquals('Tag>=value', AlarmTrigger::getName(AlarmTrigger::TR_TAG_GTE_VAL));
        $this->assertEquals('Tag<=value', AlarmTrigger::getName(AlarmTrigger::TR_TAG_LTE_VAL));
        $this->assertEquals('Tag=value', AlarmTrigger::getName(AlarmTrigger::TR_TAG_EQ_VAL));
        $this->assertEquals('Tag!=value', AlarmTrigger::getName(AlarmTrigger::TR_TAG_NEQ_VAL));
    }
    
    public function testGetNameWrong1()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('AlarmTrigger::getName: Invalid trigger identifier');
        
        AlarmTrigger::getName(150);
    }
    
    /**
     * Test check method
     */
    public function testCheck()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('AlarmTrigger::check: Invalid trigger identifier');
        
        AlarmTrigger::check(10);
    }
}
