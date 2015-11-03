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
 * Description of ImportGeorgiaCommand
 *
 * @author james
 */
class ImportGeorgiaCommand extends Command {
    private $georgiaVoterService;
    protected function configure() {
        $this->setName("import:georgia")
            ->setDescription("Import Georgia Voter File from Zip")
            ->addArgument('zipFile',InputArgument::REQUIRED,'What is the identifier to look up?')
            ->addArgument('dbname',InputArgument::REQUIRED,'What is the database connection name?')
            ->addOption('config',null,InputOption::VALUE_REQUIRED,'What is config folder?',false)
            ->setHelp("Usage: <info>php console.php import:georgia <env></info>");
        $this->georgiaVoterService = new \CF\gavoters\GeorgiaVoterService();
    }
    protected function execute(InputInterface $input, OutputInterface $output) {
        $this->georgiaVoterService->setConnectionName($input->getArgument('dbname'));
        if($input->getOption('config')) {
            $this->georgiaVoterService->setConfigFolder($input->getOption('config'));
        }
        // setConfigFolder
        $zip = new \ZipArchive;
        $res = $zip->open($input->getArgument('zipFile'));
        if ($res === TRUE) {
            $this->georgiaVoterService->initializeGeorgiaVoterTable();
            $compressedfiles = [];
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $compressedfiles[] = $zip->statIndex( $i );
            }
            usort($compressedfiles, function($a, $b) {
                if ($a['size'] == $b['size']) {
                    return 0;
                }
                return $a['size'] < $b['size'] ? -1 : 1;
            });
            $maxentry = array_pop($compressedfiles);
            $exportDate = \date("Y-m-d", $maxentry['mtime']);
            $fp = $zip->getStream($maxentry['name']);
            while (($buffer = fgets($fp, 4096)) !== false) {
                $output->writeln($buffer);
            }
            $this->georgiaVoterService->initializeGeorgiaVoterTable();
        } else {
            $output->writeln("Zip File Problem");
        }
    }
}
