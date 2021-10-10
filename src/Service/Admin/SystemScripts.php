<?php

namespace App\Service\Admin;

use App\Service\Admin\ConfigGeneralMapper;
use App\Entity\AppException;

/**
 * Class to execute system scripts (system services)
 *
 * @author Mateusz Mirosławski
 */
class SystemScripts
{
    /**
     * System scripts directory
     */
    private string $systemScriptsPath;
    
    /**
     * Path to the server application
     */
    private string $serverAppPath;
    
    /**
     * Required scripts
     */
    public const REQ_SCRIPTS = array(
        'onh_start.sh',
        'onh_stop.sh',
        'disable_services.sh',
        'enable_services.sh',
        'status.sh',
        'clear_logs.sh',
        'archive_logs.sh'
    );
    
    public function __construct(ConfigGeneralMapper $cfgGeneral)
    {
        // Web app path
        $webApp = $cfgGeneral->getWebAppPath();
        
        // System scripts directory
        $this->systemScriptsPath = $this->buildScriptPath($webApp, 'shScripts/');
        
        // Server application path
        $this->serverAppPath = $this->checkSlash($cfgGeneral->getServerAppPath());
    }
    
    /**
     * Build full path to the script
     *
     * @param string $scriptDir Script directory path
     * @param string $script Script name
     * @return string Full path to the script
     */
    public static function buildScriptPath(string $scriptDir, string $script): string
    {
        $scriptPath = "";
        
        // Script directory
        $size = strlen($scriptDir);
        if ($scriptDir[$size - 1] != '/') {
            $scriptPath = $scriptDir . "/" . $script;
        } else {
            $scriptPath = $scriptDir . $script;
        }
        
        return $scriptPath;
    }
    
    /**
     * Check last slash in string
     *
     * @param string $str String to check
     * @return string String with '/' at the end
     */
    private function checkSlash(string $str): string
    {
        $ret = '';
        
        // Check last char in string
        $ch = $str[strlen($str) - 1];
        
        if ($ch != '/') {
            $ret = $str . '/';
        } else {
            $ret = $str;
        }
        
        return $ret;
    }
    
    /**
     * Check if system script path exist
     *
     * @return bool True if system script directory exist
     * @throws AppException
     */
    private function checkScriptsPath(): bool
    {
        $ret = true;
        
        // Check last char in path
        $ch = $this->systemScriptsPath[strlen($this->systemScriptsPath) - 1];
        
        if ($ch != '/') {
            throw new AppException("Script path need to end with '/'", AppException::SCRIPT_DIRECTORY_NOT_VALID);
        }
        
        if (!is_dir($this->systemScriptsPath)) {
            throw new AppException("Script directory does not exist", AppException::SCRIPT_DIRECTORY_NOT_EXIST);
        }
        
        return $ret;
    }
    
    /**
     * Check if all necessary scripts exists
     *
     * @return bool True if all scripts exists
     * @throws AppException
     */
    private function checkScripts(): bool
    {
        $ret = true;
        
        $scripts = array();
        exec("find " . $this->systemScriptsPath . " -name '*.sh' | sed 's!.*/!!'", $scripts);
        
        foreach (self::REQ_SCRIPTS as $script) {
            if (!in_array($script, $scripts)) {
                throw new AppException("Missing " . $script . " in script directory", AppException::SCRIPT_MISSING);
            }
        }
        
        return $ret;
    }
    
    /**
     * Get service state
     *
     * @param string $serviceName Service name
     * @param string $cmd Service status string
     * @return string
     * @throws AppException
     */
    private function getServiceState(string $serviceName, string $cmd): string
    {
        $tmp = explode(':', $cmd);
        if ($tmp[0] != $serviceName || !($tmp[1] == 'active' || $tmp[1] == 'inactive' || $tmp[1] == 'failed')) {
            throw new AppException("Wrong " . $serviceName . " service status", AppException::SCRIPT_WRONG_REPLY);
        }
        return $tmp[1];
    }
    
    /**
     * Get service autoloading status
     *
     * @param string $serviceName Service name
     * @param string $cmd Service autoloading status string
     * @return bool
     * @throws AppException
     */
    private function getServiceAutoloading(string $serviceName, string $cmd): bool
    {
        $tmp = explode(':', $cmd);
        if ($tmp[0] != $serviceName || !($tmp[1] == 'ok' || $tmp[1] == 'nok')) {
            throw new AppException("Wrong " . $serviceName .
                        " service autoload reply", AppException::SCRIPT_WRONG_REPLY);
        }
        
        return ($tmp[1] == 'ok') ? (true) : (false);
    }
    
    /**
     * Get status (active/inactive) of system services
     *
     * @return array Array with service status
     * @throws AppException
     */
    public function getServiceStatus(): array
    {
        $cmd_reply = array();
        $services = array();
        
        // Check path and scripts
        $this->checkScriptsPath();
        $this->checkScripts();
        
        // Get services status
        exec("sudo sh " . $this->systemScriptsPath . "status.sh", $cmd_reply);
        
        // Check reply
        if (count($cmd_reply) != 4) {
            throw new AppException("Wrong services number", AppException::SCRIPT_WRONG_REPLY);
        }
                
        // openNetworkHMI service check
        $services['openNetworkHMI'] = $this->getServiceState('openNetworkHMI', $cmd_reply[0]);
        
        // Apache2 service check
        $services['Apache2'] = $this->getServiceState('Apache2', $cmd_reply[1]);
        
        // MySQL service check
        $services['MySQL'] = $this->getServiceState('MySQL', $cmd_reply[2]);
        
        // Autoload openNetworkHMI
        $autoClient = $this->getServiceAutoloading('AutoloadONH', $cmd_reply[3]);
        
        // Service autoload field
        $services['Autoload'] = $autoClient;
        
        return $services;
    }
    
    /**
     * Enable/Disable openNetworkHMI service autoloading
     *
     * @param bool $flag True - autoload, false - no autoload
     * @return bool True if command executed
     */
    public function setServicesAutoload(bool $flag): bool
    {
        // Check path and scripts
        $this->checkScriptsPath();
        $this->checkScripts();
        
        $cmd_reply = array();
        
        if ($flag) {
            exec("sudo sh " . $this->systemScriptsPath . "enable_services.sh", $cmd_reply);
        } else {
            exec("sudo sh " . $this->systemScriptsPath . "disable_services.sh", $cmd_reply);
        }
        
        return true;
    }
    
    /**
     * Start openNetworkHMI service
     *
     * @param bool $start Start flag
     * @return bool True if command executed
     */
    public function startONH(bool $start): bool
    {
        // Check path and scripts
        $this->checkScriptsPath();
        $this->checkScripts();
                
        if ($start) {
            exec("sudo sh " . $this->systemScriptsPath . "onh_start.sh");
        } else {
            exec("sudo sh " . $this->systemScriptsPath . "onh_stop.sh");
        }
        
        return true;
    }
    
    /**
     * Clear system logs
     */
    public function clearLogs()
    {
        // Check path and scripts
        $this->checkScriptsPath();
        $this->checkScripts();
                        
        exec("sudo sh " . $this->systemScriptsPath . "clear_logs.sh " . $this->serverAppPath);
    }
    
    /**
     * Archive system logs
     */
    public function archiveLogs()
    {
        // Check path and scripts
        $this->checkScriptsPath();
        $this->checkScripts();
        
        exec("sudo sh " . $this->systemScriptsPath . "archive_logs.sh " . $this->serverAppPath);
    }
}
