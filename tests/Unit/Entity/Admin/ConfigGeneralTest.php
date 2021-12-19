<?php

namespace App\Tests\Unit\Entity\Admin;

use App\Entity\Admin\ConfigGeneral;
use PHPUnit\Framework\TestCase;

/**
 * Unit test for ConfigGeneral class
 *
 * @author Mateusz Mirosławski
 */
class ConfigGeneralTest extends TestCase
{
    /**
     * Test Default constructor
     */
    public function testDefaultConstructor()
    {
        $cfg = new ConfigGeneral();
        
        $this->assertEquals(ConfigGeneral::UPDATE_INTERVAL_MAX, $cfg->getAlarmingUpdateInterval());
        $this->assertEquals(ConfigGeneral::UPDATE_INTERVAL_MAX, $cfg->getProcessUpdateInterval());
        $this->assertEquals(ConfigGeneral::UPDATE_INTERVAL_MAX, $cfg->getTagLoggerUpdateInterval());
        $this->assertEquals(ConfigGeneral::UPDATE_INTERVAL_MAX, $cfg->getScriptSystemUpdateInterval());
        
        $this->assertEquals(3, $cfg->getSocketMaxConn());
        $this->assertEquals(8080, $cfg->getSocketPort());
        $this->assertEquals('', $cfg->getServerAppPath());
        $this->assertEquals('', $cfg->getWebAppPath());
        $this->assertEquals('', $cfg->getUserScriptsPath());
        $this->assertEquals('ROLE_USER', $cfg->getAckAccessRole());
    }
    
    /**
     * Test setAlarmingUpdateInterval method
     */
    public function testSetAlarmingUpdateInterval()
    {
        $cfg = new ConfigGeneral();
        $cfg->setAlarmingUpdateInterval(45);
        
        $this->assertEquals(45, $cfg->getAlarmingUpdateInterval());
    }
    
    public function testSetAlarmingUpdateIntervalWrong()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('Wrong update interval value');
        
        $cfg = new ConfigGeneral();
        $cfg->setAlarmingUpdateInterval(9);
    }
    
    /**
     * Test setProcessUpdateInterval method
     */
    public function testSetProcessUpdateInterval()
    {
        $cfg = new ConfigGeneral();
        $cfg->setProcessUpdateInterval(800);
        
        $this->assertEquals(800, $cfg->getProcessUpdateInterval());
    }
    
    /**
     * Test setScriptSystemUpdateInterval method
     */
    public function testSetScriptSystemUpdateInterval()
    {
        $cfg = new ConfigGeneral();
        $cfg->setScriptSystemUpdateInterval(800);
        
        $this->assertEquals(800, $cfg->getScriptSystemUpdateInterval());
    }
    
    /**
     * Test setSocketMaxConn method
     */
    public function testSetSocketMaxConn()
    {
        $cfg = new ConfigGeneral();
        $cfg->setSocketMaxConn(5);
        
        $this->assertEquals(5, $cfg->getSocketMaxConn());
    }
    
    public function testSetSocketMaxConnWrong()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('Socket max connection wrong value');
        
        $cfg = new ConfigGeneral();
        $cfg->setSocketMaxConn(0);
    }
    
    /**
     * Test setSocketPort method
     */
    public function testSetSocketPort()
    {
        $cfg = new ConfigGeneral();
        $cfg->setSocketPort(5060);
        
        $this->assertEquals(5060, $cfg->getSocketPort());
    }
    
    public function testSetSocketPortWrong()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('Socket port wrong value');
        
        $cfg = new ConfigGeneral();
        $cfg->setSocketPort(0);
    }
    
    /**
     * Test setTagLoggerUpdateInterval method
     */
    public function testSetTagLoggerUpdateInterval()
    {
        $cfg = new ConfigGeneral();
        $cfg->setTagLoggerUpdateInterval(600);
        
        $this->assertEquals(600, $cfg->getTagLoggerUpdateInterval());
    }
    
    /**
     * Test setServerAppPath method
     */
    public function testSetServerAppPath()
    {
        $cfg = new ConfigGeneral();
        $cfg->setServerAppPath('path3');
        
        $this->assertEquals('path3', $cfg->getServerAppPath());
    }
    
    public function testSetServerAppPathWrong()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('Server application path can not be empty');
        
        $cfg = new ConfigGeneral();
        $cfg->setServerAppPath(' ');
    }
    
    /**
     * Test setWebAppPath method
     */
    public function testSetWebAppPath()
    {
        $cfg = new ConfigGeneral();
        $cfg->setWebAppPath('testPathWeb');
        
        $this->assertEquals('testPathWeb', $cfg->getWebAppPath());
    }
    
    public function testSetWebAppPathWrong()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('Web application path can not be empty');
        
        $cfg = new ConfigGeneral();
        $cfg->setWebAppPath(' ');
    }
    
    /**
     * Test setUserScriptsPath method
     */
    public function testSetUserScriptsPath()
    {
        $cfg = new ConfigGeneral();
        $cfg->setUserScriptsPath('testScriptPth');
        
        $this->assertEquals('testScriptPth', $cfg->getUserScriptsPath());
    }
    
    public function testSetUserScriptsPathWrong()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('User scripts path can not be empty');
        
        $cfg = new ConfigGeneral();
        $cfg->setUserScriptsPath(' ');
    }
    
    /**
     * Test setAckAccessRole method
     */
    public function testSetAckAccessRole()
    {
        $cfg = new ConfigGeneral();
        $cfg->setAckAccessRole('ROLE_ADMIN');
        
        $this->assertEquals('ROLE_ADMIN', $cfg->getAckAccessRole());
    }
    
    public function testSetAckAccessRoleWrong()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('Role name is invalid');
        
        $cfg = new ConfigGeneral();
        $cfg->setAckAccessRole('adm');
    }
    
    /**
     * Test isValid method
     */
    public function testIsValid()
    {
        $cfg = new ConfigGeneral();
        $cfg->setServerAppPath('serverApp');
        $cfg->setUserScriptsPath('usrScripts');
        $cfg->setWebAppPath('webApp');
        
        $this->assertTrue($cfg->isValid(true));
    }
    
    public function testIsValidWrong()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('User scripts path can not be empty');
        
        $cfg = new ConfigGeneral();
        $cfg->setServerAppPath('serverApp');
        $cfg->setWebAppPath('webApp');
        
        $this->assertTrue($cfg->isValid(true));
    }
}
