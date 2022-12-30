<?php

namespace Wpcommander\Artisan;
	
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Question\Question;

// the name of the command is what users type after "php bin/console"
#[AsCommand(
	name: 'app:setup',
	description: 'Setup wordpress plugin',
)]
class Setup extends Command
{

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // ... put here the code to create the user

		
		$question = new Question('Please enter the name of the bundle', 'AcmeDemoBundle');
		$helper = $this->getHelper('question');
		$bundleName = $helper->ask($input, $output, $question);
		$output->write($bundleName);

        // this method must return an integer number with the "exit status code"
        // of the command. You can also use these constants to make code more readable

        // return this if there was no problem running the command
        // (it's equivalent to returning int(0))
        return Command::SUCCESS;

        // or return this if some error happened during the execution
        // (it's equivalent to returning int(1))
        // return Command::FAILURE;

        // or return this to indicate incorrect command usage; e.g. invalid options
        // or missing arguments (it's equivalent to returning int(2))
        // return Command::INVALID
    }

	protected function configure(): void
    {
        $this->setHelp('This command allows you to create a user...');
		// $this->addArgument('password', InputArgument::REQUIRED, 'User password');
    }
}