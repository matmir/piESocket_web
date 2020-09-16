<?php

namespace App\Tests\Entity\Admin;

use App\Entity\Admin\TagType;
use PHPUnit\Framework\TestCase;

/**
 * Unit test for TagType class
 *
 * @author Mateusz Mirosławski
 */
class TagTypeTest extends TestCase
{
    /**
     * Test getName method
     */
    public function testGetName1()
    {
        $this->assertEquals('Bit', TagType::getName(TagType::BIT));
        $this->assertEquals('Byte', TagType::getName(TagType::BYTE));
        $this->assertEquals('Word', TagType::getName(TagType::WORD));
        $this->assertEquals('DWord', TagType::getName(TagType::DWORD));
        $this->assertEquals('INT', TagType::getName(TagType::INT));
        $this->assertEquals('REAL', TagType::getName(TagType::REAL));
    }
    
    public function testGetNameWrong1()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('TagType::getName: Invalid Tag type identifier');
        
        TagType::getName(150);
    }
    
    /**
     * Test check method
     */
    public function testCheck()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('TagType::check: Invalid Tag type identifier');
        
        TagType::check(10);
    }
}
