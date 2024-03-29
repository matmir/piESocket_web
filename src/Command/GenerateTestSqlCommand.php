<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Console\Question\Question;
use App\Service\Admin\ConfigGeneralMapper;

/**
 * Command Class for generate test DB SQL file
 *
 * @author Mateusz Mirosławski
 */
class GenerateTestSqlCommand extends Command
{
    /**
     * Command name
     */
    protected static $defaultName = 'app:generate-test-sql';
    
    /**
     * Config general mapper object
     */
    private $cfg;
    
    public function __construct(ConfigGeneralMapper $cfg)
    {
        $this->cfg = $cfg;

        parent::__construct();
    }
    
    protected function configure()
    {
        // Help
        $this->setDescription('Generate test DB SQL file.')
                ->setHelp('This command generates test DB SQL file.');
        
        $this->addOption(
            'ask',
            null,
            InputOption::VALUE_OPTIONAL,
            'Ask user about paths',
            false
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $ret = 0;
        
        // Get ask flag
        $ask = ($input->getOption('ask') == 'yes') ? (true) : (false);
        
        // Get system configuration
        $sysCfg = $this->cfg->getConfig();
        
        $helper = $this->getHelper('question');
        
        $commandPath = dirname(__FILE__);
        
        // Detect web app path
        $webAppDetect = $sysCfg->getWebAppPath();
        
        // Detect service path
        $servicePathDetect = str_replace('openNetworkHMI_web/src/Command', 'tests/bin/onh/', $commandPath);
        
        // Detect user scripts path
        $scriptsPathDetect = str_replace('openNetworkHMI_web/src/Command', 'tests/scripts/', $commandPath);
        
        try {
            $webAppPath = '';
            $servicePath = '';
            $scriptsPath = '';
            $servicePort = 0;
            
            if ($ask) {
                $question1 = new Question("Please enter full path to the web application directory\nDefault [" .
                                            $webAppDetect . "]:", $webAppDetect);
                $webAppPath = $helper->ask($input, $output, $question1);
            } else {
                $webAppPath = $webAppDetect;
            }
            
            if (!is_dir($webAppPath)) {
                throw new Exception("Web application directory does not exist");
            }

            if ($ask) {
                $question2 = new Question(
                    "Please enter full test path to the service application" .
                                            " directory\nDefault [" . $servicePathDetect . "]:",
                    $servicePathDetect
                );
                $servicePath = $helper->ask($input, $output, $question2);
            } else {
                $servicePath = $servicePathDetect;
            }
            
            if (!is_dir($servicePath)) {
                throw new Exception("Service application directory does not exist");
            }
            
            if ($ask) {
                $question3 = new Question("Please enter full test path to the user scripts directory\nDefault [" .
                                            $scriptsPathDetect . "]:", $scriptsPathDetect);
                $scriptsPath = $helper->ask($input, $output, $question3);
            } else {
                $scriptsPath = $scriptsPathDetect;
            }
            
            if (!is_dir($scriptsPath)) {
                throw new Exception("User scripts directory does not exist");
            }
            
            if ($ask) {
                $question4 = new Question("Please set service socket port number:\nDefault [8080]:", "8080");
                $servicePort = $helper->ask($input, $output, $question4);
            } else {
                $servicePort = 8080;
            }
            
            // Get path to test sql dist file
            $distFile = str_replace('src/Command', 'distFiles/testDB/db.sql.dist', $commandPath);
            $sqlFile = str_replace('.dist', '', $distFile);
            
            if (!file_exists($distFile) || is_dir($distFile)) {
                throw new Exception("Test DB Sql distribution file (" . $distFile . ") does not exist");
            }

            // Open dist file
            $f = file_get_contents($distFile);
            if ($f === false) {
                throw new Exception("Can not open Test DB Sql distribution file: " . $distFile);
            }
            // Prepare SQL content
            $cnt1 = str_replace('[webAppPath]', $webAppPath, $f);
            $cnt2 = str_replace('[serverAppPath]', $servicePath, $cnt1);
            $cnt3 = str_replace('[userScriptsPath]', $scriptsPath, $cnt2);
            $cnt = str_replace('[socketPrt]', "'" . $servicePort . "'", $cnt3);
            
            // Write service file
            $r = file_put_contents($sqlFile, $cnt);
            if ($r === false) {
                throw new Exception("Can not write Test DB Sql file: " . $sqlFile);
            }
            
            $output->writeln("Done");
        } catch (Exception $ex) {
            $output->writeln($ex->getMessage());
            $ret = 1;
        }
        
        return $ret;
    }
}
