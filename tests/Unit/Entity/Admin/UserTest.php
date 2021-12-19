<?php

namespace App\Tests\Unit\Entity\Admin;

use App\Entity\Admin\User;
use PHPUnit\Framework\TestCase;

/**
 * Unit test for User class
 *
 * @author Mateusz Mirosławski
 */
class UserTest extends TestCase
{
    
    /**
     * Test Default constructor
     */
    public function testDefaultConstructor()
    {
        
        $usr = new User();
        
        $this->assertEquals(0, $usr->getId());
        $this->assertEquals('', $usr->getUsername());
        $this->assertEquals('', $usr->getPassword());
        $this->assertEquals('', $usr->getEmail());
        $this->assertEquals('', $usr->getRoles()[0]);
        $this->assertFalse($usr->isActive());
    }
    
    /**
     * Test setId method
     */
    public function testSetId()
    {
        
        $usr = new User();
        $usr->setId(85);
        
        $this->assertEquals(85, $usr->getId());
        $this->assertEquals('', $usr->getUsername());
        $this->assertEquals('', $usr->getPassword());
        $this->assertEquals('', $usr->getEmail());
        $this->assertEquals('', $usr->getRoles()[0]);
        $this->assertFalse($usr->isActive());
    }
    
    public function testSetIdWrong1()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('User identifier wrong value');
        
        $tag = new User();
        $tag->setId(-3);
    }
    
    /**
     * Test setUsername method
     */
    public function testSetUsername()
    {
        
        $usr = new User();
        $usr->setUsername('testU');
        
        $this->assertEquals(0, $usr->getId());
        $this->assertEquals('testU', $usr->getUsername());
        $this->assertEquals('', $usr->getPassword());
        $this->assertEquals('', $usr->getEmail());
        $this->assertEquals('', $usr->getRoles()[0]);
        $this->assertFalse($usr->isActive());
    }
    
    public function testSetUsernameWrong1()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('User name can not be empty');
        
        $usr = new User();
        $usr->setUsername(' ');
    }
    
    /**
     * Test setPassword method
     */
    public function testSetPassword()
    {
        
        $usr = new User();
        $usr->setPassword('pass1');
        
        $this->assertEquals(0, $usr->getId());
        $this->assertEquals('', $usr->getUsername());
        $this->assertEquals('pass1', $usr->getPassword());
        $this->assertEquals('', $usr->getEmail());
        $this->assertEquals('', $usr->getRoles()[0]);
        $this->assertFalse($usr->isActive());
    }
    
    public function testSetPasswordWrong1()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('User password can not be empty');
        
        $usr = new User();
        $usr->setPassword(' ');
    }
    
    /**
     * Test setEmail method
     */
    public function testSetEmail()
    {
        
        $usr = new User();
        $usr->setEmail('mailtest');
        
        $this->assertEquals(0, $usr->getId());
        $this->assertEquals('', $usr->getUsername());
        $this->assertEquals('', $usr->getPassword());
        $this->assertEquals('mailtest', $usr->getEmail());
        $this->assertEquals('', $usr->getRoles()[0]);
        $this->assertFalse($usr->isActive());
    }
    
    public function testSetEmailWrong1()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('User email can not be empty');
        
        $usr = new User();
        $usr->setEmail(' ');
    }
    
    /**
     * Test setUserRole method
     */
    public function testSetUserRole()
    {
        
        $usr = new User();
        $usr->setRoles('ROLE_test');
        
        $this->assertEquals(0, $usr->getId());
        $this->assertEquals('', $usr->getUsername());
        $this->assertEquals('', $usr->getPassword());
        $this->assertEquals('', $usr->getEmail());
        $this->assertEquals('ROLE_test', $usr->getRoles()[0]);
        $this->assertFalse($usr->isActive());
    }
    
    public function testSetUserRoleWrong1()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('Role name can not be empty');
        
        $usr = new User();
        $usr->setRoles(' ');
    }
    
    public function testSetUserRoleWrong2()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('Role name is invalid');
        
        $usr = new User();
        $usr->setRoles('test_auth');
    }
    
    /**
     * Test setActive method
     */
    public function testSetActive()
    {
        $usr = new User();
        $usr->setActive(true);
        
        $this->assertEquals(0, $usr->getId());
        $this->assertEquals('', $usr->getUsername());
        $this->assertEquals('', $usr->getPassword());
        $this->assertEquals('', $usr->getEmail());
        $this->assertEquals('', $usr->getRoles()[0]);
        $this->assertTrue($usr->isActive());
    }
    
    /**
     * Test isValid method
     */
    public function testIsValid()
    {
        $usr = new User();
        $usr->setId(45);
        $usr->setUsername('user1');
        $usr->setEmail('test@test.tt');
        $usr->setPassword('pass');
        $usr->setRoles('ROLE_test');
        
        $this->assertTrue($usr->isValid(true));
    }
}
