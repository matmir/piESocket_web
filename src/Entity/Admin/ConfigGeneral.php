<?php

namespace App\Entity\Admin;

use Symfony\Component\Config\Definition\Exception\Exception;
use App\Entity\Admin\User;

/**
 * Class for general configuration
 *
 * @author Mateusz Mirosławski
 */
class ConfigGeneral
{
    /**
     * Minimal update interval time (milliseconds)
     */
    public const UPDATE_INTERVAL_MIN = 10;
    
    /**
     * Maximal update interval time (milliseconds)
     */
    public const UPDATE_INTERVAL_MAX = 10000;
    
    /**
     * Alarmin system update interval (milliseconds)
     */
    private $alarmingUpdateInterval;
    
    /**
     * Process data update interval (milliseconds)
     */
    private $processUpdateInterval;
    
    /**
     * Network socket maximal number of connections
     */
    private $socketMaxConn;
    
    /**
     * Network socket port
     */
    private $socketPort;
    
    /**
     * Tag logger system update interval (milliseconds)
     */
    private $tagLoggerUpdateInterval;
    
    /**
     * Server application path
     */
    private $serverAppPath;
    
    /**
     * Web application path
     */
    private $webAppPath;
    
    /**
     * Script system execute script
     */
    private $scriptSystemExecuteScript;
    
    /**
     * Script system update interval (milliseconds)
     */
    private $scriptSystemUpdateInterval;
    
    /**
     * User scripts path
     */
    private $userScriptsPath;
    
    /**
     * Alarm acknowledgement rights role
     */
    private $ackAccessRole;
    
    /**
     * Default constructor
     *
     * @param int $alarmInt Alarm update interval
     * @param int $procInt Process updater interval
     * @param int $tagLoggerInt Tag logger update interval
     * @param int $scriptInt Script system update interval
     * @param int $sockMaxXonn Socket max connections
     * @param int $sockPort Socket port
     * @param string $serverApp Server app path
     * @param string $webApp Web app path
     * @param string $scriptExecPath Execute script path
     * @param string $usrScripts User scripts path
     * @param string $ack Ack permission role
     */
    public function __construct(
        int $alarmInt = self::UPDATE_INTERVAL_MAX,
        int $procInt = self::UPDATE_INTERVAL_MAX,
        int $tagLoggerInt = self::UPDATE_INTERVAL_MAX,
        int $scriptInt = self::UPDATE_INTERVAL_MAX,
        int $sockMaxXonn = 3,
        int $sockPort = 8080,
        string $serverApp = '',
        string $webApp = '',
        string $scriptExecPath = '',
        string $usrScripts = '',
        string $ack = 'ROLE_USER'
    ) {
        $this->alarmingUpdateInterval = $alarmInt;
        $this->processUpdateInterval = $procInt;
        $this->tagLoggerUpdateInterval = $tagLoggerInt;
        $this->scriptSystemUpdateInterval = $scriptInt;
        
        $this->socketMaxConn = $sockMaxXonn;
        $this->socketPort = $sockPort;
        
        $this->serverAppPath = $serverApp;
        $this->webAppPath = $webApp;
        $this->scriptSystemExecuteScript = $scriptExecPath;
        $this->userScriptsPath = $usrScripts;
        $this->ackAccessRole = $ack;
    }
    
    /**
     * Check Update interval
     *
     * @param int $interval Update interval value
     * @return bool True if Update interval is valid
     * @throws Exception if Update interval is invalid
     */
    public static function checkInterval(int $interval): bool
    {
        // Check values
        if (!($interval >= self::UPDATE_INTERVAL_MIN && $interval <= self::UPDATE_INTERVAL_MAX)) {
            throw new Exception("Wrong update interval value");
        }
        
        return true;
    }
    
    /**
     * Get alarm system update interval
     *
     * @return int Alarming update interval (milliseconds)
     */
    public function getAlarmingUpdateInterval(): int
    {
        return $this->alarmingUpdateInterval;
    }
    
    /**
     * Set alarm system update interval
     *
     * @param int $val Update interval (milliseconds)
     */
    public function setAlarmingUpdateInterval(int $val)
    {
        $this->checkInterval($val);
        
        $this->alarmingUpdateInterval = $val;
    }
    
    /**
     * Get process update interval
     *
     * @return int Process update interval (milliseconds)
     */
    public function getProcessUpdateInterval(): int
    {
        return $this->processUpdateInterval;
    }
    
    /**
     * Set process update interval
     *
     * @param int $val Update interval (milliseconds)
     */
    public function setProcessUpdateInterval(int $val)
    {
        $this->checkInterval($val);
        
        $this->processUpdateInterval = $val;
    }
    
    /**
     * Check maximum number of connections to the socket
     *
     * @param int $conn Number of connections to the socket
     * @return bool True if Number of connections to the socket is valid
     * @throws Exception If Number of connections to the socket invalid
     */
    public static function checkSocketMaxConn(int $conn): bool
    {
        // Check values
        if ($conn <= 0) {
            throw new Exception("Socket max connection wrong value");
        }
        
        return true;
    }
    
    /**
     * Get maximum number of connections to the socket
     *
     * @return int Maximum number of connections to the socket
     */
    public function getSocketMaxConn(): int
    {
        return $this->socketMaxConn;
    }
    
    /**
     * Set maximum number of connections to the socket
     *
     * @param int $val Maximum number of connections to the socket
     */
    public function setSocketMaxConn(int $val)
    {
        $this->checkSocketMaxConn($val);
        
        $this->socketMaxConn = $val;
    }
    
    /**
     * Check socket connection port
     *
     * @param int $prt socket connection port
     * @return bool True if socket connection port is valid
     * @throws Exception If socket connection port invalid
     */
    public static function checkSocketPort(int $prt): bool
    {
        // Check values
        if ($prt < 1 || $prt > 65535) {
            throw new Exception("Socket port wrong value");
        }
        
        return true;
    }
    
    /**
     * Get socket connection port
     *
     * @return int Socket connection port
     */
    public function getSocketPort(): int
    {
        return $this->socketPort;
    }
    
    /**
     * Set socket connection port
     *
     * @param int $val Socket connection port
     */
    public function setSocketPort(int $val)
    {
        $this->checkSocketPort($val);
        
        $this->socketPort = $val;
    }
    
    /**
     * Get tag logger update interval
     *
     * @return int Tag logger update interval (milliseconds)
     */
    public function getTagLoggerUpdateInterval(): int
    {
        return $this->tagLoggerUpdateInterval;
    }
    
    /**
     * Set tag logger update interval
     *
     * @param int $val Tag logger update interval (milliseconds)
     */
    public function setTagLoggerUpdateInterval(int $val)
    {
        $this->checkInterval($val);
        
        $this->tagLoggerUpdateInterval = $val;
    }
    
    /**
     * Check server application path
     *
     * @param string $pth server application path
     * @return bool True if server application path valid
     * @throws Exception if server application path invalid
     */
    public static function checkServerAppPath(string $pth): bool
    {
        if (trim($pth) == false) {
            throw new Exception("Server application path can not be empty");
        }
        
        return true;
    }
    
    /**
     * Get server application path
     *
     * @return string Path to the server application
     */
    public function getServerAppPath(): string
    {
        return $this->serverAppPath;
    }
    
    /**
     * Set server application path
     *
     * @param string $val Server application path
     */
    public function setServerAppPath(string $val)
    {
        $this->checkServerAppPath($val);
        
        $this->serverAppPath = $val;
    }
    
    /**
     * Check web application path
     *
     * @param string $pth web application path
     * @return bool True if web application path valid
     * @throws Exception if web application path invalid
     */
    public static function checkWebAppPath(string $pth): bool
    {
        if (trim($pth) == false) {
            throw new Exception("Web application path can not be empty");
        }
        
        return true;
    }
    
    /**
     * Get web application path
     *
     * @return string Web application path
     */
    public function getWebAppPath(): string
    {
        return $this->webAppPath;
    }
    
    /**
     * Set web application path
     *
     * @param string $val Web application path
     */
    public function setWebAppPath(string $val)
    {
        $this->checkWebAppPath($val);
        
        $this->webAppPath = $val;
    }
    
    /**
     * Get script system update interval
     *
     * @return int Script system update interval (milliseconds)
     */
    public function getScriptSystemUpdateInterval(): int
    {
        return $this->scriptSystemUpdateInterval;
    }
    
    /**
     * Set script system update interval
     *
     * @param int $val Update interval (milliseconds)
     */
    public function setScriptSystemUpdateInterval(int $val)
    {
        $this->checkInterval($val);
        
        $this->scriptSystemUpdateInterval = $val;
    }
    
    /**
     * Check script system execute script
     *
     * @param string $scr script system execute script
     * @return bool True if script system execute script
     * @throws Exception if script system execute script
     */
    public static function checkScriptSystemExecuteScript(string $scr): bool
    {
        if (trim($scr) == false) {
            throw new Exception("Script system execute script can not be empty");
        }
        
        return true;
    }
    
    /**
     * Get script system execute script
     *
     * @return string Script system execute script
     */
    public function getScriptSystemExecuteScript(): string
    {
        return $this->scriptSystemExecuteScript;
    }
    
    /**
     * Set script system execute script
     *
     * @param string $val Script system execute script
     */
    public function setScriptSystemExecuteScript(string $val)
    {
        $this->checkScriptSystemExecuteScript($val);
        
        $this->scriptSystemExecuteScript = $val;
    }
    
    /**
     * Check user scripts path
     *
     * @param string $uscr user scripts path
     * @return bool True if user scripts path
     * @throws Exception if user scripts path
     */
    public static function checkUserScriptsPath(string $uscr): bool
    {
        if (trim($uscr) == false) {
            throw new Exception("User scripts path can not be empty");
        }
        
        return true;
    }
    
    /**
     * Get user scripts path
     *
     * @return string User scripts path
     */
    public function getUserScriptsPath(): string
    {
        return $this->userScriptsPath;
    }
    
    /**
     * Set user scripts path
     *
     * @param string $val User scripts path
     */
    public function setUserScriptsPath(string $val)
    {
        $this->checkUserScriptsPath($val);
        
        $this->userScriptsPath = $val;
    }
    
    /**
     * Get alarm acknowledgement rights role
     *
     * @return string Alarm acknowledgement rights role
     */
    public function getAckAccessRole(): string
    {
        return $this->ackAccessRole;
    }
    
    /**
     * Set alarm acknowledgement rights role
     *
     * @param string $role Role name
     */
    public function setAckAccessRole(string $role)
    {
        User::checkRole($role);
        
        $this->ackAccessRole = $role;
    }
    
    /**
     * Check if config object is valid
     *
     * @return bool True if config is valid
     * @throws Exception Throws when config is invalid
     */
    public function isValid(): bool
    {
        // Check update intervals
        $this->checkInterval($this->alarmingUpdateInterval);
        $this->checkInterval($this->tagLoggerUpdateInterval);
        $this->checkInterval($this->processUpdateInterval);
        $this->checkInterval($this->scriptSystemUpdateInterval);
        
        // Check cfg
        $this->checkScriptSystemExecuteScript($this->scriptSystemExecuteScript);
        $this->checkServerAppPath($this->serverAppPath);
        $this->checkSocketMaxConn($this->socketMaxConn);
        $this->checkSocketPort($this->socketPort);
        $this->checkUserScriptsPath($this->userScriptsPath);
        $this->checkWebAppPath($this->webAppPath);
        
        return true;
    }
}
