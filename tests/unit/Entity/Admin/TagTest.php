<?php

namespace App\Tests\Entity\Admin;

use App\Entity\Admin\Tag;
use App\Entity\Admin\TagType;
use App\Entity\Admin\TagArea;
use PHPUnit\Framework\TestCase;

/**
 * Unit test for Tag class
 *
 * @author Mateusz Mirosławski
 */
class TagTest extends TestCase
{
    /**
     * Test Default constructor
     */
    public function testDefaultConstructor()
    {
        $tag = new Tag();
        
        $this->assertEquals(0, $tag->getId());
        $this->assertEquals(0, $tag->getConnId());
        $this->assertEquals('', $tag->getConnName());
        $this->assertEquals('', $tag->getName());
        $this->assertEquals(TagArea::INPUT, $tag->getArea());
        $this->assertEquals(TagType::BIT, $tag->getType());
        $this->assertEquals(0, $tag->getByteAddress());
        $this->assertEquals(0, $tag->getBitAddress());
        $this->assertEquals('ROLE_USER', $tag->getReadAccess());
        $this->assertEquals('ROLE_USER', $tag->getWriteAccess());
    }
    
    /**
     * Test setId method
     */
    public function testSetId()
    {
        $tag = new Tag();
        $tag->setId(65);
        
        $this->assertEquals(65, $tag->getId());
    }
    
    public function testSetIdWrong1()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('Tag identifier wrong value');
        
        $tag = new Tag();
        $tag->setId(-3);
    }
    
    /**
     * Test setConnId method
     */
    public function testSetConnId()
    {
        $tag = new Tag();
        $tag->setConnId(33);
        
        $this->assertEquals(33, $tag->getConnId());
    }
    
    public function testSetConnIdWrong1()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('Driver connection identifier wrong value');
        
        $tag = new Tag();
        $tag->setConnId(-4);
    }
    
    /**
     * Test setConnName method
     */
    public function testSetConnName()
    {
        $tag = new Tag();
        $tag->setConnName('testConn');
        
        $this->assertEquals('testConn', $tag->getConnName());
    }
    
    public function testSetConnNameWrong1()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('Driver connection name can not be empty');
        
        $tag = new Tag();
        $tag->setConnName(' ');
    }
    
    /**
     * Test setName method
     */
    public function testSetName()
    {
        $tag = new Tag();
        $tag->setName('TestTag');
        
        $this->assertEquals('TestTag', $tag->getName());
    }
    
    public function testSetNameWrong1()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('Tag name can not be empty');
        
        $tag = new Tag();
        $tag->setName('');
    }
    
    /**
     * Test setType method
     */
    public function testSetType()
    {
        $tag = new Tag();
        $tag->setType(TagType::BYTE);
        
        $this->assertEquals(TagType::BYTE, $tag->getType());
    }
    
    /**
     * Test setArea method
     */
    public function testSetArea()
    {
        $tag = new Tag();
        $tag->setArea(TagArea::MEMORY);
        
        $this->assertEquals(TagArea::MEMORY, $tag->getArea());
    }
    
    /**
     * Test setByteAddress method
     */
    public function testSetByteAddress()
    {
        $tag = new Tag();
        $tag->setByteAddress(400);
        
        $this->assertEquals(400, $tag->getByteAddress());
    }
    
    public function testSetByteAddressWrong()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('Tag byte address can not be lower than 0');
        
        $tag = new Tag();
        $tag->setByteAddress(-3);
    }
    
    /**
     * Test setBitAddress method
     */
    public function testSetBitAddress()
    {
        $tag = new Tag();
        $tag->setBitAddress(4);
        
        $this->assertEquals(4, $tag->getBitAddress());
    }
    
    public function testSetBitAddressWrong()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('Tag bit address is invalid');
        
        $tag = new Tag();
        $tag->setBitAddress(9);
    }
    
    /**
     * Test setReadAccess method
     */
    public function testSetReadAccess()
    {
        $tag = new Tag();
        $tag->setReadAccess('ROLE_GUEST');
        
        $this->assertEquals('ROLE_GUEST', $tag->getReadAccess());
    }
    
    /**
     * Test setWriteAccess method
     */
    public function testSetWriteAccess()
    {
        $tag = new Tag();
        $tag->setWriteAccess('ROLE_GUEST');
        
        $this->assertEquals('ROLE_GUEST', $tag->getWriteAccess());
    }
    
    /**
     * Test isValid method
     */
    public function testIsValidWithoutID()
    {
        $tag = new Tag();
        $tag->setName('TestTag');
        $tag->setType(TagType::BYTE);
        $tag->setArea(TagArea::MEMORY);
        $tag->setByteAddress(400);
        $tag->setBitAddress(0);
        
        $this->assertTrue($tag->isValid());
    }
    
    public function testIsValidWithID()
    {
        $tag = new Tag();
        $tag->setId(780);
        $tag->setName('TestTag');
        $tag->setType(TagType::BYTE);
        $tag->setArea(TagArea::MEMORY);
        $tag->setByteAddress(400);
        $tag->setBitAddress(0);
        
        $this->assertTrue($tag->isValid(true));
    }
    
    public function testIsValidWithTagType()
    {
        $this->expectException(\App\Entity\AppException::class);
        $this->expectExceptionMessage('Tag (TestTag) type is Byte but required is Word');
        
        $tag = new Tag();
        $tag->setId(780);
        $tag->setName('TestTag');
        $tag->setType(TagType::BYTE);
        $tag->setArea(TagArea::MEMORY);
        $tag->setByteAddress(400);
        $tag->setBitAddress(0);
        
        $tag->isValid(true, true, TagType::WORD);
    }
}
