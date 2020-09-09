<?php

namespace App\Tests\Entity\Admin;

use App\Entity\Admin\Tag;
use App\Entity\Admin\TagType;
use App\Entity\Admin\TagArea;
use App\Entity\Admin\TagEntity;
use PHPUnit\Framework\TestCase;

/**
 * Unit test for TagEntity class
 *
 * @author Mateusz Mirosławski
 */
class TagEntityTest extends TestCase {
    
    /**
     * Test default constructor
     */
    public function testDefaultConstructor() {
        
        $te = new TagEntity();
        
        $this->assertEquals(0, $te->gettid());
        $this->assertEquals(0, $te->gettConnId());
        $this->assertEquals('', $te->gettName());
        $this->assertEquals(0, $te->gettArea());
        $this->assertEquals(0, $te->gettType());
        $this->assertEquals(0, $te->gettBitAddress());
        $this->assertEquals(0, $te->gettByteAddress());
        $this->assertEquals('ROLE_USER', $te->gettReadAccess());
        $this->assertEquals('ROLE_USER', $te->gettWriteAccess());
    }
    
    public function testSetId() {
        
        $te = new TagEntity();
        $te->settid(654);
        
        $this->assertEquals(654, $te->gettid());
    }
    
    public function testSetConnId() {
        
        $te = new TagEntity();
        $te->settConnId(654);
        
        $this->assertEquals(654, $te->gettConnId());
    }
    
    public function testSetName() {
        
        $te = new TagEntity();
        $te->settName('TestTag');
        
        $this->assertEquals('TestTag', $te->gettName());
    }
    
    public function testSetArea() {
        
        $te = new TagEntity();
        $te->settArea(2);
        
        $this->assertEquals(2, $te->gettArea());
    }
    
    public function testSetType() {
        
        $te = new TagEntity();
        $te->settType(2);
        
        $this->assertEquals(2, $te->gettType());
    }
    
    public function testSetBitAddress() {
        
        $te = new TagEntity();
        $te->settBitAddress(5);
        
        $this->assertEquals(5, $te->gettBitAddress());
    }
    
    public function testSetByteAddress() {
        
        $te = new TagEntity();
        $te->settByteAddress(100);
        
        $this->assertEquals(100, $te->gettByteAddress());
    }
    
    /**
     * Test getFullTagObject method
     */
    public function testGetFullTagObject() {
        
        $te = new TagEntity();
        $te->settid(50);
        $te->settName('TestTag');
        $te->settArea(TagArea::memory);
        $te->settType(TagType::Byte);
        $te->settByteAddress(100);
        $te->settBitAddress(0);
        $te->settReadAccess('ROLE_GUEST');
        $te->settWriteAccess('ROLE_ADMIN');
        
        $tag = $te->getFullTagObject();
        
        $this->assertInstanceOf(Tag::class, $tag);
        $this->assertEquals(TagArea::memory, $tag->getArea());
        $this->assertEquals(TagType::Byte, $tag->getType());
        $this->assertEquals(50, $tag->getId());
        $this->assertEquals(100, $tag->getByteAddress());
        $this->assertEquals(0, $tag->getBitAddress());
        $this->assertEquals('TestTag', $tag->getName());
        $this->assertEquals('ROLE_GUEST', $tag->getReadAccess());
        $this->assertEquals('ROLE_ADMIN', $tag->getWriteAccess());
    }
    
    /**
     * Test initFromTagObject
     */
    public function testInitFromTagObject() {
        
        $tag = new Tag();
        $tag->setId(40);
        $tag->setConnId(5);
        $tag->setName('TestTag');
        $tag->setArea(TagArea::input);
        $tag->setType(TagType::Word);
        $tag->setByteAddress(30);
        $tag->setBitAddress(0);
        $tag->setReadAccess('ROLE_GUEST');
        $tag->setWriteAccess('ROLE_USER');
        
        $te = new TagEntity();
        $te->initFromTagObject($tag);
        
        $this->assertEquals(40, $te->gettid());
        $this->assertEquals(5, $te->gettConnId());
        $this->assertEquals('TestTag', $te->gettName());
        $this->assertEquals(TagArea::input, $te->gettArea());
        $this->assertEquals(TagType::Word, $te->gettType());
        $this->assertEquals(0, $te->gettBitAddress());
        $this->assertEquals(30, $te->gettByteAddress());
        $this->assertEquals('ROLE_GUEST', $te->gettReadAccess());
        $this->assertEquals('ROLE_USER', $te->gettWriteAccess());
    }
    
    public function testInitFromTagObjectWrong() {
        
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('Tag name can not be empty');
        
        $tag = new Tag();
        $tag->setArea(TagArea::input);
        $tag->setType(TagType::Word);
        
        $te = new TagEntity();
        $te->initFromTagObject($tag);
    }
}
