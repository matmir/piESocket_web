<?php

namespace App\Tests\Entity\Admin;

use App\Entity\Admin\TagLoggerInterval;
use PHPUnit\Framework\TestCase;

/**
 * Unit test for TagLoggerInterval class
 *
 * @author Mateusz Mirosławski
 */
class TagLoggerIntervalTest extends TestCase {
    
    /**
     * Test getName method
     */
    public function testGetName1() {
        
        $this->assertEquals('100ms', TagLoggerInterval::getName(TagLoggerInterval::I_100MS));
        $this->assertEquals('200ms', TagLoggerInterval::getName(TagLoggerInterval::I_200MS));
        $this->assertEquals('500ms', TagLoggerInterval::getName(TagLoggerInterval::I_500MS));
        $this->assertEquals('1s', TagLoggerInterval::getName(TagLoggerInterval::I_1S));
        $this->assertEquals('Xs', TagLoggerInterval::getName(TagLoggerInterval::I_XS));
        $this->assertEquals('On change', TagLoggerInterval::getName(TagLoggerInterval::I_ON_CHANGE));
    }
    
    public function testGetNameWrong1() {
        
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('TagLoggerInterval::getName: Invalid interval identifier');
        
        TagLoggerInterval::getName(150);
    }
    
    /**
     * Test check method
     */
    public function testCheck() {
        
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('TagLoggerInterval::check: Invalid interval identifier');
        
        TagLoggerInterval::check(10);
    }
}
