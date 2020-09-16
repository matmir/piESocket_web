<?php

namespace App\Tests\Entity\Admin;

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
        $this->assertEquals('none', $cfg->getServerAppPath());
        $this->assertEquals('none', $cfg->getWebAppPath());
        $this->assertEquals('none', $cfg->getScriptSystemExecuteScript());
        $this->assertEquals('none', $cfg->getUserScriptsPath());
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
    
    /**
     * Test setSocketPort method
     */
    public function testSetSocketPort()
    {
        $cfg = new ConfigGeneral();
        $cfg->setSocketPort(5060);
        
        $this->assertEquals(5060, $cfg->getSocketPort());
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
    
    /**
     * Test setWebAppPath method
     */
    public function testSetWebAppPath()
    {
        $cfg = new ConfigGeneral();
        $cfg->setWebAppPath('testPathWeb');
        
        $this->assertEquals('testPathWeb', $cfg->getWebAppPath());
    }
    
    /**
     * Test setSystemScriptsPath method
     */
    public function testSetScriptSystemExecuteScript()
    {
        $cfg = new ConfigGeneral();
        $cfg->setScriptSystemExecuteScript('testPath');
        
        $this->assertEquals('testPath', $cfg->getScriptSystemExecuteScript());
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
    
    /**
     * Test setAckAccessRole method
     */
    public function testSetAckAccessRole()
    {
        $cfg = new ConfigGeneral();
        $cfg->setAckAccessRole('ROLE_ADMIN');
        
        $this->assertEquals('ROLE_ADMIN', $cfg->getAckAccessRole());
    }
}
