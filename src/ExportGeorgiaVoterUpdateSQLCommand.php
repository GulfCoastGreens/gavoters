<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CF\gavoters;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
/**
 * Description of ExportGeorgiaVoterUpdateSQLCommand
 *
 * @author james
 */
class ExportGeorgiaVoterUpdateSQLCommand extends Command {
    private $georgiaVoterService;
    protected function configure() {
        $this->setName("export:georgiaupdatesql")
            ->setDescription("Export Georgia Voter SQL from voterID file")
            ->addArgument('jsonVoterIDFile',InputArgument::REQUIRED,'What is the JSON file with voter ids?')
            ->addArgument('dbname',InputArgument::REQUIRED,'What is the database connection name?')
            ->addOption('config',null,InputOption::VALUE_REQUIRED,'What is config folder?',false)
            ->setHelp("Usage: <info>php console.php export:georgiaupdatesql <env></info>");
        $this->georgiaVoterService = new \CF\gavoters\GeorgiaVoterService();
    }
    protected function execute(InputInterface $input, OutputInterface $output) {
        $this->georgiaVoterService->setConnectionName($input->getArgument('dbname'));
        if($input->getOption('config')) {
            $this->georgiaVoterService->setConfigFolder($input->getOption('config'));
        } else {
            $this->georgiaVoterService->setConfigFolder('/usr/local/etc/gcg/default');
        }
        $voterIds = \array_map(function($obj) {
            return $obj['ga_voter_id_2'];
        }, \json_decode(\file_get_contents($input->getArgument('jsonVoterIDFile')), true));
        $result = $this->georgiaVoterService->buildUpdateSQL($voterIds);
        $output->writeln($result);
        
    }
}
