<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Console\Question\Question;

use App\Service\Admin\ConfigGeneralMapper;

/**
 * Command Class for update system paths in DB
 * 
 * @author Mateusz Mirosławski
 */
class UpdateSystemPathCommand extends Command {
    
    /**
     * Command name
     */
    protected static $defaultName = 'app:update-paths';
    
    /**
     * Config general mapper object
     */
    private $cfg;
    
    public function __construct(ConfigGeneralMapper $cfg) {
        
        $this->cfg = $cfg;

        parent::__construct();
    }
    
    protected function configure() {
        
        // Help
        $this->setDescription('Update system paths in DB.')
                ->setHelp('This command updates system paths in DB.');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        
        $helper = $this->getHelper('question');
        
        // Detect web app path
        $commandPath = dirname(__FILE__);
        $webAppDetect = str_replace('src/Command', '', $commandPath);
        
        // Detect console path
        $consolePath = str_replace('src/Command', 'bin/console', $commandPath);
        
        // Detect service path
        $servicePathDetect = str_replace('openNetworkHMI_web/src/Command', 'openNetworkHMI_service/build/app/', $commandPath);
        
        // Detect user scripts path
        $scriptsPathDetect = str_replace('openNetworkHMI_web/src/Command', 'userScripts/', $commandPath);
        
        try {
            
            $question1 = new Question("Please enter full path to the web application directory\nDefault [".$webAppDetect."]:", $webAppDetect);
            $webAppPath = $helper->ask($input, $output, $question1);

            if (!is_dir($webAppPath)) {
                throw new Exception("Web application directory does not exist");
            }
            
            if (!file_exists($consolePath) || is_dir($consolePath)) {
                throw new Exception("Web console file does not exist");
            }

            $question2 = new Question("Please enter full path to the service application directory\nDefault [".$servicePathDetect."]:", $servicePathDetect);
            $servicePath = $helper->ask($input, $output, $question2);

            if (!is_dir($servicePath)) {
                throw new Exception("Service application directory does not exist");
            }
            
            $question3 = new Question("Please enter full path to the user scripts directory\nDefault [".$scriptsPathDetect."]:", $scriptsPathDetect);
            $scriptsPath = $helper->ask($input, $output, $question3);
            
            if (!is_dir($scriptsPath)) {
                throw new Exception("User scripts directory does not exist");
            }
            
            $question4 = new Question("Please set service socket port number:\nDefault [8080]:", "8080");
            $servicePort = $helper->ask($input, $output, $question4);

            // Get system configuration
            $sysCfg = $this->cfg->getConfig();
            
            // Update paths
            $sysCfg->setWebAppPath($webAppPath);
            $sysCfg->setScriptSystemExecuteScript("php ".$consolePath." app:run-script");
            $sysCfg->setServerAppPath($servicePath);
            $sysCfg->setUserScriptsPath($scriptsPath);
            $sysCfg->setSocketPort($servicePort);
            
            // Write data to DB
            $this->cfg->setConfig($sysCfg);
            
            $output->writeln("Done");
            
        } catch (Exception $ex) {
            
            $output->writeln($ex->getMessage());
        }
        
        return 0;
    }
}
