<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Config\Definition\Exception\Exception;
use App\Service\Admin\ConfigGeneralMapper;
use App\Service\Admin\SystemScripts;

/**
 * Command Class for generate test DB SQL file
 *
 * @author Mateusz Mirosławski
 */
class GenerateSudoersCommand extends Command
{
    /**
     * Command name
     */
    protected static $defaultName = 'app:generate-sudoers';
    
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
        $this->setDescription('Generate sudoers premissions file.')
                ->setHelp('This command generates sudoers premissions file.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $ret = 0;
        
        try {
            // Get web app path
            $webApp = $this->cfg->getWebAppPath();
            
            // Generate shScript path
            $shDir = SystemScripts::buildScriptPath($webApp, 'shScripts/');
            
            // Check if path exist
            if (!is_dir($shDir)) {
                throw new Exception("Web application sh script directory does not exist");
            }
            
            // Get path to sudoers dist file
            $commandPath = dirname(__FILE__);
            $distFile = str_replace('src/Command', 'distFiles/sudoers/openNetworkHMI_premissions.dist', $commandPath);
            $sudoersFile = str_replace('.dist', '', $distFile);
            
            if (!file_exists($distFile) || is_dir($distFile)) {
                throw new Exception("Sudoers premissions distribution file (" . $distFile . ") does not exist");
            }

            // Open dist file
            $f = file_get_contents($distFile);
            if ($f === false) {
                throw new Exception("Can not open sudoers premissions distribution file: " . $distFile);
            }
            // Prepare sudoers content
            $cnt = str_replace('[onh_shScripts]', $shDir, $f);
            // Write sudoers file
            $r = file_put_contents($sudoersFile, $cnt);
            if ($r === false) {
                throw new Exception("Can not write sudoers premissions file: " . $sudoersFile);
            }
            
            $output->writeln("Done");
        } catch (Exception $ex) {
            $output->writeln($ex->getMessage());
            $ret = 1;
        }
        
        return $ret;
    }
}
