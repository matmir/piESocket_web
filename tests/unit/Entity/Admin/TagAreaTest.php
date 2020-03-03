<?php

namespace App\Tests\Entity\Admin;

use App\Entity\Admin\TagArea;
use PHPUnit\Framework\TestCase;

/**
 * Unit test for TagArea class
 *
 * @author Mateusz Mirosławski
 */
class TagAreaTest extends TestCase {
    
    /**
     * Test getName method
     */
    public function testGetName1() {
        
        $this->assertEquals('Input', TagArea::getName(TagArea::input));
        $this->assertEquals('Output', TagArea::getName(TagArea::output));
        $this->assertEquals('Memory', TagArea::getName(TagArea::memory));
    }
    
    public function testGetNameWrong1() {
        
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('TagArea::getName: Invalid Tag area identifier');
        
        TagArea::getName(150);
    }
    
    /**
     * Test getPrefix method
     */
    public function testGetPrefix1() {
        
        $this->assertEquals('I', TagArea::getPrefix(TagArea::input));
        $this->assertEquals('Q', TagArea::getPrefix(TagArea::output));
        $this->assertEquals('M', TagArea::getPrefix(TagArea::memory));
    }
    
    public function testGetPrefixWrong1() {
        
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('TagArea::getPrefix: Invalid Tag area identifier');
        
        TagArea::getPrefix(150);
    }
    
    /**
     * Test check method
     */
    public function testCheck() {
        
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('TagArea::check: Invalid Tag area identifier');
        
        TagArea::check(10);
    }
}
