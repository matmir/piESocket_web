<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Config\Definition\Exception\Exception;
use App\Service\Admin\ConfigGeneralMapper;

/**
 * Command Class for get onh server port
 *
 * @author Mateusz Mirosławski
 */
class ServerPortCommand extends Command
{
    /**
     * Command name
     */
    protected static $defaultName = 'app:onh-server-port';
    
    /**
     * Server app port number
     */
    private $port;
    
    public function __construct(ConfigGeneralMapper $cfg)
    {
        $this->port = $cfg->getSystemSocketPort();

        parent::__construct();
    }
    
    protected function configure()
    {
        // Help
        $this->setDescription('Get openNetworkHMI server port number.')
                ->setHelp('This command gets openNetworkHMI server port number.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $output->writeln($this->port);
        } catch (Exception $ex) {
            $output->writeln($ex->getMessage());
        }
        
        return 0;
    }
}
