<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Config\Definition\Exception\Exception;
use App\Service\Admin\ScriptItemMapper;

/**
 * Command Class for clear run flag of the execute script
 *
 * @author Mateusz Mirosławski
 */
class ClearScriptRunFlagCommand extends Command
{
    /**
     * Command name
     */
    protected static $defaultName = 'app:clear-run-flag';
    
    /**
     * Script item mapper object
     */
    private $scriptItemMapper;
    
    public function __construct(ScriptItemMapper $scriptMapper)
    {
        $this->scriptItemMapper = $scriptMapper;

        parent::__construct();
    }
    
    protected function configure()
    {
        // Help
        $this->setDescription('Clear run flag of the external script.')
                ->setHelp('This command clear run flag of the external script.');
        
        // Parameters
        $this->addArgument('script', InputArgument::REQUIRED, 'Script name');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $ret = 0;
        
        $output->writeln("Clear run flag for " . $input->getArgument('script'));
        
        try {
            // Check if script exists in DB
            if ($this->scriptItemMapper->exist($input->getArgument('script'))) {
                // Clear run flag
                $this->scriptItemMapper->clearRunFlag($input->getArgument('script'));

                $output->writeln("Run flag cleared.");
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
