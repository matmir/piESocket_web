<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Config\Definition\Exception\Exception;
use App\Service\Admin\ScriptItemMapper;
use App\Service\Admin\ConfigGeneralMapper;

/**
 * Command Class for run external script
 *
 * @author Mateusz Mirosławski
 */
class RunScriptCommand extends Command
{
    /**
     * Command name
     */
    protected static $defaultName = 'app:run-script';
    
    /**
     * Script item mapper object
     */
    private $scriptItemMapper;
    
    /**
     * User scripts path
     */
    private $userScriptsPath;
    
    public function __construct(ScriptItemMapper $scriptMapper, ConfigGeneralMapper $cfgMapper)
    {
        $this->scriptItemMapper = $scriptMapper;
        $this->userScriptsPath = $cfgMapper->getUserScriptsPath();

        parent::__construct();
    }
    
    protected function configure()
    {
        // Help
        $this->setDescription('Execute external bash script.')
                ->setHelp('This command execute user defined external bash script.');
        
        // Parameters
        $this->addArgument('script', InputArgument::REQUIRED, 'Script name');
    }
    
    /**
     * Build full path to the script
     *
     * @param string $scriptDir User script directory path
     * @param string $script Script name
     * @return string Full path to the script
     */
    public static function buildScriptPath(string $scriptDir, string $script)
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

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $ret = 0;
        
        try {
            // Check if script definition exists in DB
            if ($this->scriptItemMapper->exist($input->getArgument('script'))) {
                // Get script definition
                $scriptDef = $this->scriptItemMapper->getScriptByName($input->getArgument('script'));
                
                // Check lock flags
                if (!$scriptDef->isRunning() && !$scriptDef->isLocked()) {
                    $this->scriptItemMapper->setFlags($scriptDef->getName());
                }
                
                // Script path
                $script = $this->buildScriptPath($this->userScriptsPath, $input->getArgument('script'));
                
                // Check if file exist in disk
                if (!file_exists($script)) {
                    throw new Exception("Script: " . $script . " does not exist on disk!");
                }
                
                // Prepare command
                $cmd = "sh " . $script;
                
                $output->writeln("Run script " . $script . "...");
                
                // Start time of the script
                $startTime = microtime(true);
                
                // Run command
                $cmd_out = shell_exec($cmd);
                
                // Stop time of the script
                $stopTime = microtime(true);
                
                // Time execution
                $timeExec = $stopTime - $startTime;
                
                // Clear run flag
                $this->scriptItemMapper->clearRunFlag($input->getArgument('script'));

                $output->writeln("Script execution (" . $timeExec . " sec) finished. Run flag cleared.");
                $output->writeln("Script output: ");
                $output->writeln($cmd_out);
            } else {
                $output->writeln("Script " . $input->getArgument('script') . " does not exist in DB!");
            }
        } catch (Exception $ex) {
            $output->writeln($ex->getMessage());
            $ret = 1;
        }
        
        return $ret;
    }
}
