<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Console\Question\Question;

use App\Service\Admin\ConfigGeneralMapper;

/**
 * Command Class for generation systemd service file
 * 
 * @author Mateusz Mirosławski
 */
class GenerateSystemdCommand extends Command {
    
    /**
     * Command name
     */
    protected static $defaultName = 'app:generate-systemd';
    
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
        $this->setDescription('Generate systemd service file.')
                ->setHelp('This command generates systemd service file.');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        
        try {
            
            // Get server application path
            $serverAppPath = $this->cfg->getServerAppPath();
            
            // Check '/' at the end of the path
            $size = strlen($serverAppPath);
            if ($serverAppPath[$size-1] != '/') {
                $serverAppPath .= "/";
            }
            
            // Check if path exist
            if (!is_dir($serverAppPath)) {
                throw new Exception("Service application directory does not exist");
            }
            
            // Get path to systemd dist file
            $commandPath = dirname(__FILE__);
            $distFile = str_replace('src/Command', 'distFiles/systemd/openNetworkHMI.service.dist', $commandPath);
            $serviceFile = str_replace('.dist', '', $distFile);
            
            if (!file_exists($distFile) || is_dir($distFile)) {
                throw new Exception("Systemd distribution file (".$distFile.") does not exist");
            }
            
            // Open dist file
            $f = file_get_contents($distFile);
            if ($f === false) {
                throw new Exception("Can not open systemd distribution file: ".$distFile);
            }
            // Prepare service content
            $srv = str_replace('[onh_full_path]', $serverAppPath, $f);
            // Write service file
            $r = file_put_contents($serviceFile, $srv);
            if ($r === false) {
                throw new Exception("Can not write systemd file: ".$serviceFile);
            }
            
            $output->writeln("Done");
            
        } catch (Exception $ex) {
            
            $output->writeln($ex->getMessage());
        }
        
        return 0;
    }
}
