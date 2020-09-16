<?php

namespace App\Tests\Entity\Admin;

use App\Entity\Admin\Tag;
use App\Entity\Admin\TagType;
use App\Entity\Admin\ScriptItem;
use App\Entity\Admin\ScriptItemEntity;
use App\Tests\Entity\Admin\TagLoggerTest;
use PHPUnit\Framework\TestCase;

/**
 * Unit test for ScriptItem class
 *
 * @author Mateusz Mirosławski
 */
class ScriptItemEntityTest extends TestCase
{
    /**
     * Test Default constructor
     */
    public function testDefaultConstructor()
    {
        $scriptE = new ScriptItemEntity();
        
        $this->assertEquals(0, $scriptE->getscid());
        $this->assertEquals('', $scriptE->getscTagName());
        $this->assertEquals('', $scriptE->getscName());
        $this->assertEquals('', $scriptE->getscFeedbackRun());
    }
    
    /**
     * Test setscid method
     */
    public function testSetId()
    {
        $scriptE = new ScriptItemEntity();
        $scriptE->setscid(89);
        
        $this->assertEquals(89, $scriptE->getscid());
        $this->assertEquals('', $scriptE->getscTagName());
        $this->assertEquals('', $scriptE->getscName());
        $this->assertEquals('', $scriptE->getscFeedbackRun());
    }
    
    /**
     * Test setscTagName method
     */
    public function testSetscTagName()
    {
        $scriptE = new ScriptItemEntity();
        $scriptE->setscTagName('ttt');
        
        $this->assertEquals(0, $scriptE->getscid());
        $this->assertEquals('ttt', $scriptE->getscTagName());
        $this->assertEquals('', $scriptE->getscName());
        $this->assertEquals('', $scriptE->getscFeedbackRun());
    }
    
    /**
     * Test setscName method
     */
    public function testSetscName()
    {
        $scriptE = new ScriptItemEntity();
        $scriptE->setscName('script');
        
        $this->assertEquals(0, $scriptE->getscid());
        $this->assertEquals('', $scriptE->getscTagName());
        $this->assertEquals('script', $scriptE->getscName());
        $this->assertEquals('', $scriptE->getscFeedbackRun());
    }
    
    /**
     * Test setscFeedbackRun method
     */
    public function testSetscFeedbackRun()
    {
        $scriptE = new ScriptItemEntity();
        $scriptE->setscFeedbackRun('fbTag');
        
        $this->assertEquals(0, $scriptE->getscid());
        $this->assertEquals('', $scriptE->getscTagName());
        $this->assertEquals('', $scriptE->getscName());
        $this->assertEquals('fbTag', $scriptE->getscFeedbackRun());
    }
    
    /**
     * Test getFullScriptObject method
     */
    public function testGetFullScriptObject()
    {
        $tag = null;
        TagLoggerTest::createTag($tag);
        $tag->setType(TagType::BIT);
        $tag->setId(5);
        
        $tagF = null;
        TagLoggerTest::createTag($tagF);
        $tagF->setType(TagType::BIT);
        $tagF->setId(54);
        
        $scriptE = new ScriptItemEntity();
        $scriptE->setscid(56);
        $scriptE->setscName('test.sh');
        
        $script = $scriptE->getFullScriptObject($tag, $tagF);
        
        $this->assertEquals(56, $script->getId());
        $this->assertEquals('test.sh', $script->getName());
        
        $this->assertInstanceOf(Tag::class, $script->getTag());
        $this->assertEquals(5, $script->getTag()->getId());
        
        $this->assertInstanceOf(Tag::class, $script->getFeedbackRun());
        $this->assertEquals(54, $script->getFeedbackRun()->getId());
    }
    
    /**
     * Test initFromAlarmObject method
     */
    public function testInitFromScriptObject1()
    {
        $tag = null;
        TagLoggerTest::createTag($tag);
        $tag->setType(TagType::BIT);
        $tag->setId(3);
        
        $tagF = null;
        TagLoggerTest::createTag($tagF);
        $tagF->setType(TagType::BIT);
        $tagF->setName('testFB');
        $tagF->setId(34);
        
        $script = new ScriptItem($tag);
        $script->setId(40);
        $script->setName('test3.sh');
        $script->setFeedbackRun($tagF);
        
        $scriptE = new ScriptItemEntity();
        $scriptE->initFromScriptObject($script);
        
        $this->assertEquals(40, $scriptE->getscid());
        $this->assertEquals('TestTag', $scriptE->getscTagName());
        $this->assertEquals('test3.sh', $scriptE->getscName());
        $this->assertEquals('testFB', $scriptE->getscFeedbackRun());
    }
    
    public function testInitFromScriptObject2()
    {
        $tag = null;
        TagLoggerTest::createTag($tag);
        $tag->setType(TagType::BIT);
        $tag->setId(3);
        
        $script = new ScriptItem($tag);
        $script->setId(40);
        $script->setName('test3.sh');
        
        $scriptE = new ScriptItemEntity();
        $scriptE->initFromScriptObject($script);
        
        $this->assertEquals(40, $scriptE->getscid());
        $this->assertEquals('TestTag', $scriptE->getscTagName());
        $this->assertEquals('test3.sh', $scriptE->getscName());
        $this->assertEquals('', $scriptE->getscFeedbackRun());
    }
}
