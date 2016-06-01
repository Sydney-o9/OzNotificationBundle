<?php

namespace Oz\NotificationBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class NotificationKeyCommand extends Command
{

    protected $notificationKeyManager;

    protected $methodManager;


    public function __construct($notificationKeyManager, $methodManager)
    {
        $this->notificationKeyManager = $notificationKeyManager;
        $this->methodManager = $methodManager;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('oz_notification:notification_key:create')
            ->setDescription('Create a new method')
            ->addArgument(
                'name',
                InputArgument::OPTIONAL,
                'What is the name of the notificationKey you would like to create?'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');

        if (!$name) {
            $helper = $this->getHelper('question');
            $question = new Question('Please enter the name of the notification key (e.g user.newsletter): ', 'user.newsletter');
            $name = $helper->ask($input, $output, $question);
        }

        $beginText = '--> Creating the following notification key: '.$name;
        $output->writeln($beginText);

        $notificationKeyObj = $this->notificationKeyManager->create();
        $notificationKeyObj->setNotificationKey($name);
        //ladybug_dump_die($notificationKeyObj);

        $methods = $this->methodManager->findAll();
        ladybug_dump_die($methods);
        //$this->methodManager->update($methodObj);

        //$endText = '--> Created method: '.$method;
        //$output->writeln($endText);
    }
}
