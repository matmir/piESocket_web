<?php

namespace App\Tests\Entity\Admin;

use App\Entity\Admin\UserEntity;
use PHPUnit\Framework\TestCase;

/**
 * Unit test for ScriptItem class
 *
 * @author Mateusz Mirosławski
 */
class UserEntityTest extends TestCase {
    
    /**
     * Test Default constructor
     */
    public function testDefaultConstructor() {
        
        $usr = new UserEntity();
        
        $this->assertEquals(0, $usr->getid());
        $this->assertEquals('', $usr->getusername());
        $this->assertEquals('', $usr->getoldPassword());
        $this->assertEquals('', $usr->getpassword1());
        $this->assertEquals('', $usr->getpassword2());
        $this->assertEquals('', $usr->getemail());
        $this->assertEquals('', $usr->getuserrole());
    }
    
    /**
     * Test setId method
     */
    public function testSetId() {
        
        $usr = new UserEntity();
        $usr->setid(85);
        
        $this->assertEquals(85, $usr->getid());
        $this->assertEquals('', $usr->getusername());
        $this->assertEquals('', $usr->getoldPassword());
        $this->assertEquals('', $usr->getpassword1());
        $this->assertEquals('', $usr->getpassword2());
        $this->assertEquals('', $usr->getemail());
        $this->assertEquals('', $usr->getuserrole());
    }
    
    /**
     * Test setUsername method
     */
    public function testSetUsername() {
        
        $usr = new UserEntity();
        $usr->setusername('testUsr');
        
        $this->assertEquals(0, $usr->getid());
        $this->assertEquals('testUsr', $usr->getusername());
        $this->assertEquals('', $usr->getoldPassword());
        $this->assertEquals('', $usr->getpassword1());
        $this->assertEquals('', $usr->getpassword2());
        $this->assertEquals('', $usr->getemail());
        $this->assertEquals('', $usr->getuserrole());
    }
    
    /**
     * Test setUsername method
     */
    public function testSetOldPassword() {
        
        $usr = new UserEntity();
        $usr->setoldPassword('oldTestPass');
        
        $this->assertEquals(0, $usr->getid());
        $this->assertEquals('', $usr->getusername());
        $this->assertEquals('oldTestPass', $usr->getoldPassword());
        $this->assertEquals('', $usr->getpassword1());
        $this->assertEquals('', $usr->getpassword2());
        $this->assertEquals('', $usr->getemail());
        $this->assertEquals('', $usr->getuserrole());
    }
    
    /**
     * Test setPassword1 method
     */
    public function testSetPassword1() {
        
        $usr = new UserEntity();
        $usr->setpassword1('pass1');
        
        $this->assertEquals(0, $usr->getid());
        $this->assertEquals('', $usr->getusername());
        $this->assertEquals('', $usr->getoldPassword());
        $this->assertEquals('pass1', $usr->getpassword1());
        $this->assertEquals('', $usr->getpassword2());
        $this->assertEquals('', $usr->getemail());
        $this->assertEquals('', $usr->getuserrole());
    }
    
    /**
     * Test setPassword2 method
     */
    public function testSetPassword2() {
        
        $usr = new UserEntity();
        $usr->setpassword2('pass2');
        
        $this->assertEquals(0, $usr->getid());
        $this->assertEquals('', $usr->getusername());
        $this->assertEquals('', $usr->getoldPassword());
        $this->assertEquals('', $usr->getpassword1());
        $this->assertEquals('pass2', $usr->getpassword2());
        $this->assertEquals('', $usr->getemail());
        $this->assertEquals('', $usr->getuserrole());
    }
    
    /**
     * Test setEmail method
     */
    public function testSetEmail() {
        
        $usr = new UserEntity();
        $usr->setemail('mailtest');
        
        $this->assertEquals(0, $usr->getid());
        $this->assertEquals('', $usr->getusername());
        $this->assertEquals('', $usr->getoldPassword());
        $this->assertEquals('', $usr->getpassword1());
        $this->assertEquals('', $usr->getpassword2());
        $this->assertEquals('mailtest', $usr->getemail());
        $this->assertEquals('', $usr->getuserrole());
    }
    
    /**
     * Test setUserRole method
     */
    public function testSetUserRole() {
        
        $usr = new UserEntity();
        $usr->setuserrole('test_role');
        
        $this->assertEquals(0, $usr->getid());
        $this->assertEquals('', $usr->getusername());
        $this->assertEquals('', $usr->getoldPassword());
        $this->assertEquals('', $usr->getpassword1());
        $this->assertEquals('', $usr->getpassword2());
        $this->assertEquals('', $usr->getemail());
        $this->assertEquals('test_role', $usr->getuserrole());
    }
}
