<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Config\Definition\Exception\Exception;
use App\Service\Admin\TagsMapper;
use App\Service\Admin\Parser\ParserExecute;
use App\Service\Admin\Parser\ParserReplyCodes;
use App\Entity\Admin\Tag;
use App\Entity\Admin\TagType;

/**
 * Command Class for write into process data
 *
 * @author Mateusz Mirosławski
 */
class ProcessWriteCommand extends Command
{
    /**
     * Command name
     */
    protected static $defaultName = 'app:process-write';
    
    /**
     * Tags mapper object
     */
    private $tagsMapper;
    
    /**
     * Parser object
     */
    private $parser;
    
    public function __construct(TagsMapper $tm, ParserExecute $parser)
    {
        $this->tagsMapper = $tm;
        $this->parser = $parser;

        parent::__construct();
    }
    
    protected function configure()
    {
        // Help
        $this->setDescription('Write into controller process data.')
                ->setHelp('This command writes into controller process data.');
        
        // Parameters
        $this->addArgument('tag', InputArgument::REQUIRED, 'Tag name');
        
        $this->addOption(
            'value',
            null,
            InputOption::VALUE_REQUIRED,
            'Tag value',
            null
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $ret = 0;
        
        try {
            // Get tag data
            $tag = $this->tagsMapper->getTagByName($input->getArgument('tag'));
            
            // Check input value
            if ($input->getOption('value') === null) {
                throw new Exception('Tag value option missing!');
            }
            
            // Get data and write output
            $output->writeln($this->writeValue($tag, $input->getOption('value')));
        } catch (Exception $ex) {
            $output->writeln($ex->getMessage());
            $ret = 1;
        }
        
        return $ret;
    }
    
    /**
     * Write value into controller process data
     *
     * @param Tag $tag Tag object
     * @param string $val Value to write
     * @return Tag value
     */
    private function writeValue(Tag $tag, string $val)
    {
        $ret = 0;
        
        switch ($tag->getType()) {
            case TagType::BIT:
                if ($val == '0') {
                    $ret = $this->parser->resetBit($tag->getName());
                } else {
                    $ret = $this->parser->setBit($tag->getName());
                }
                break;
            case TagType::BYTE:
                $ret = $this->parser->writeByte($tag->getName(), $val);
                break;
            case TagType::WORD:
                $ret = $this->parser->writeWord($tag->getName(), $val);
                break;
            case TagType::DWORD:
                $ret = $this->parser->writeDWord($tag->getName(), $val);
                break;
            case TagType::INT:
                $ret = $this->parser->writeInt($tag->getName(), $val);
                break;
            case TagType::REAL:
                $ret = $this->parser->writeReal($tag->getName(), $val);
                break;
        }
        
        return ($ret == ParserReplyCodes::OK) ? ('OK') : ('NOK');
    }
}
