<?php

namespace Oz\NotificationBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MethodCommand extends Command
{

    protected function configure()
    {
        $this
            ->setName('oz:method:create')
            ->setDescription('Create a new method')
            ->addArgument(
                'method',
                InputArgument::REQUIRED,
                'What is the name of the method you would like to create?'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $method = $input->getArgument('method');
        if ($method) {
            $text = 'Creating the following method: '.$method;
        } else {
            $text = 'There is no method to create.';
        }

        $output->writeln($text);
    }
}
