<?php

namespace App\Entity\Admin;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class for general configuration
 * 
 * @author Mateusz Mirosławski
 */
class ConfigGeneral {
    
    /**
     * Minimal update interval time (milliseconds)
     */
    const updateIntervalMin = 10;
    
    /**
     * Maximal update interval time (milliseconds)
     */
    const updateIntervalMax = 10000;
    
    /**
     * Alarmin system update interval (milliseconds)
     * 
     * @Assert\NotBlank()
     * @Assert\Type("integer")
     * @Assert\Range(
     *      min = 10,
     *      max = 10000
     * )
     */
    private $alarmingUpdateInterval;
    
    /**
     * Process data update interval (milliseconds)
     * 
     * @Assert\NotBlank()
     * @Assert\Type("integer")
     * @Assert\Range(
     *      min = 10,
     *      max = 10000
     * )
     */
    private $processUpdateInterval;
    
    /**
     * Network socket maximal number of connections
     * 
     * @Assert\NotBlank()
     * @Assert\Type("integer")
     * @Assert\Range(
     *      min = 1,
     *      max = 100
     * )
     */
    private $socketMaxConn;
    
    /**
     * Network socket port
     * 
     * @Assert\NotBlank()
     * @Assert\Type("integer")
     * @Assert\Range(
     *      min = 1,
     *      max = 65535
     * )
     */
    private $socketPort;
    
    /**
     * Tag logger system update interval (milliseconds)
     * 
     * @Assert\NotBlank()
     * @Assert\Type("integer")
     * @Assert\Range(
     *      min = 10,
     *      max = 10000
     * )
     */
    private $tagLoggerUpdateInterval;
    
    /**
     * Server application path
     * 
     * @Assert\NotBlank()
     * @Assert\Length(max=200)
     */
    private $serverAppPath;
    
    /**
     * Web application path
     * 
     * @Assert\NotBlank()
     * @Assert\Length(max=200)
     */
    private $webAppPath;
    
    /**
     * Script system execute script
     * 
     * @Assert\NotBlank()
     * @Assert\Length(max=200)
     */
    private $scriptSystemExecuteScript;
    
    /**
     * Script system update interval (milliseconds)
     * 
     * @Assert\NotBlank()
     * @Assert\Type("integer")
     * @Assert\Range(
     *      min = 10,
     *      max = 10000
     * )
     */
    private $scriptSystemUpdateInterval;
    
    /**
     * User scripts path
     * 
     * @Assert\NotBlank()
     * @Assert\Length(max=200)
     */
    private $userScriptsPath;
    
    /**
     * Alarm acknowledgement rights role
     * 
     * @Assert\NotBlank()
     * @Assert\Length(max=20)
     */
    private $ackAccessRole;
    
    /**
     * Default constructor
     */
    public function __construct() {
        
        $this->alarmingUpdateInterval = self::updateIntervalMax;
        $this->processUpdateInterval = self::updateIntervalMax;
        $this->tagLoggerUpdateInterval = self::updateIntervalMax;
        $this->scriptSystemUpdateInterval = self::updateIntervalMax;
        
        $this->socketMaxConn = 3;
        $this->socketPort = 8080;
        
        $this->serverAppPath = 'none';
        $this->webAppPath = 'none';
        $this->scriptSystemExecuteScript = 'none';
        $this->userScriptsPath = 'none';
        $this->ackAccessRole = 'ROLE_USER';
    }
    
    /**
     * Get alarm system update interval
     * 
     * @return int Alarming update interval (milliseconds)
     */
    public function getAlarmingUpdateInterval() {
        
        return $this->alarmingUpdateInterval;
    }
    
    /**
     * Set alarm system update interval
     * 
     * @param int $val Update interval (milliseconds)
     */
    public function setAlarmingUpdateInterval(int $val) {
        
        $this->alarmingUpdateInterval = $val;
    }
    
    /**
     * Get process update interval
     * 
     * @return int Process update interval (milliseconds)
     */
    public function getProcessUpdateInterval() {
        
        return $this->processUpdateInterval;
    }
    
    /**
     * Set process update interval
     * 
     * @param int $val Update interval (milliseconds)
     */
    public function setProcessUpdateInterval(int $val) {
        
        $this->processUpdateInterval = $val;
    }
    
    /**
     * Get maximum number of connections to the socket
     * 
     * @return int Maximum number of connections to the socket
     */
    public function getSocketMaxConn() {
        
        return $this->socketMaxConn;
    }
    
    /**
     * Set maximum number of connections to the socket
     * 
     * @param int $val Maximum number of connections to the socket
     */
    public function setSocketMaxConn(int $val) {
        
        $this->socketMaxConn = $val;
    }
    
    /**
     * Get socket connection port
     * 
     * @return int Socket connection port
     */
    public function getSocketPort() {
        
        return $this->socketPort;
    }
    
    /**
     * Set socket connection port
     * 
     * @param int $val Socket connection port
     */
    public function setSocketPort(int $val) {
        
        $this->socketPort = $val;
    }
    
    /**
     * Get tag logger update interval
     * 
     * @return int Tag logger update interval (milliseconds)
     */
    public function getTagLoggerUpdateInterval() {
        
        return $this->tagLoggerUpdateInterval;
    }
    
    /**
     * Set tag logger update interval
     * 
     * @param int $val Tag logger update interval (milliseconds)
     */
    public function setTagLoggerUpdateInterval(int $val) {
        
        $this->tagLoggerUpdateInterval = $val;
    }
    
    /**
     * Get server application path
     * 
     * @return string Path to the server application
     */
    public function getServerAppPath() {
        
        return $this->serverAppPath;
    }
    
    /**
     * Set server application path
     * 
     * @param string $val Server application path
     */
    public function setServerAppPath(string $val) {
        
        $this->serverAppPath = $val;
    }
    
    /**
     * Get web application path
     * 
     * @return string Web application path
     */
    public function getWebAppPath() {
        
        return $this->webAppPath;
    }
    
    /**
     * Set web application path
     * 
     * @param string $val Web application path
     */
    public function setWebAppPath(string $val) {
        
        $this->webAppPath = $val;
    }
    
    /**
     * Get script system update interval
     * 
     * @return int Script system update interval (milliseconds)
     */
    public function getScriptSystemUpdateInterval() {
        
        return $this->scriptSystemUpdateInterval;
    }
    
    /**
     * Set script system update interval
     * 
     * @param int $val Update interval (milliseconds)
     */
    public function setScriptSystemUpdateInterval(int $val) {
        
        $this->scriptSystemUpdateInterval = $val;
    }
    
    /**
     * Get script system execute script
     * 
     * @return string Script system execute script
     */
    public function getScriptSystemExecuteScript() {
        
        return $this->scriptSystemExecuteScript;
    }
    
    /**
     * Set script system execute script
     * 
     * @param string $val Script system execute script
     */
    public function setScriptSystemExecuteScript(string $val) {
        
        $this->scriptSystemExecuteScript = $val;
    }
    
    /**
     * Get user scripts path
     * 
     * @return string User scripts path
     */
    public function getUserScriptsPath() {
        
        return $this->userScriptsPath;
    }
    
    /**
     * Set user scripts path
     * 
     * @param string $val User scripts path
     */
    public function setUserScriptsPath(string $val) {
        
        $this->userScriptsPath = $val;
    }
    
    /**
     * Get alarm acknowledgement rights role
     * 
     * @return string Alarm acknowledgement rights role
     */
    public function getAckAccessRole() {
        
        return $this->ackAccessRole;
    }
    
    /**
     * Set alarm acknowledgement rights role
     * 
     * @param string $role Role name
     */
    public function setAckAccessRole(string $role) {
        
        $this->ackAccessRole = $role;
    }
}
