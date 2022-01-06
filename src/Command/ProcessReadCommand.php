<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Config\Definition\Exception\Exception;
use App\Service\Admin\TagsMapper;
use App\Service\Admin\Parser\ParserExecute;
use App\Entity\Admin\Tag;
use App\Entity\Admin\TagType;

/**
 * Command Class for read from process data
 *
 * @author Mateusz Mirosławski
 */
class ProcessReadCommand extends Command
{
    /**
     * Command name
     */
    protected static $defaultName = 'app:process-read';
    
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
        $this->setDescription('Read data from controller process data.')
                ->setHelp('This command reads data from controller process data.');
        
        // Parameters
        $this->addArgument('tag', InputArgument::REQUIRED, 'Tag name');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $ret = 0;
        try {
            // Get tag data
            $tag = $this->tagsMapper->getTagByName($input->getArgument('tag'));
            
            // Get data and write output
            $output->writeln($this->getValue($tag));
        } catch (Exception $ex) {
            $output->writeln($ex->getMessage());
            $ret = 1;
        }
        
        return $ret;
    }
    
    /**
     * Get tag value from controller process data
     *
     * @param Tag $tag Tag object
     * @return Tag value
     */
    private function getValue(Tag $tag)
    {
        $ret = 0;
        
        switch ($tag->getType()) {
            case TagType::BIT:
                $ret = ($this->parser->getBit($tag->getName())) ? ('true') : ('false');
                break;
            case TagType::BYTE:
                $ret = $this->parser->getByte($tag->getName());
                break;
            case TagType::WORD:
                $ret = $this->parser->getWord($tag->getName());
                break;
            case TagType::DWORD:
                $ret = $this->parser->getDWord($tag->getName());
                break;
            case TagType::INT:
                $ret = $this->parser->getInt($tag->getName());
                break;
            case TagType::REAL:
                $ret = $this->parser->getReal($tag->getName());
                break;
        }
        
        return $ret;
    }
}
