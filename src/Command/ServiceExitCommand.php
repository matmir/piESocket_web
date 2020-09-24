<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Config\Definition\Exception\Exception;
use App\Service\Admin\Parser\ParserExecute;
use App\Service\Admin\Parser\ParserReplyCodes;

/**
 * Command Class for exit onh service
 *
 * @author Mateusz Mirosławski
 */
class ServiceExitCommand extends Command
{
    /**
     * Command name
     */
    protected static $defaultName = 'app:onh-exit';
    
    /**
     * Parser object
     */
    private $parser;
    
    public function __construct(ParserExecute $parser)
    {
        $this->parser = $parser;

        parent::__construct();
    }
    
    protected function configure()
    {
        // Help
        $this->setDescription('Send exit command to the openNetworkHMI service.')
                ->setHelp('This command send exit command to the openNetworkHMI service.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $ret = 0;
        
        try {
            // Exit service
            $retc = $this->parser->exit();
            
            $output->writeln(($retc == ParserReplyCodes::OK) ? ('OK') : ('NOK'));
        } catch (Exception $ex) {
            $output->writeln($ex->getMessage());
            $ret = 1;
        }
        
        return $ret;
    }
}
