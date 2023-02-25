<?php

namespace App\Infrastructure\Symfony\Command\Team\Command;

use App\Domain\Exception\ValidationException;
use App\UseCase\Team\Command\CreateTeam\Request;
use App\UseCase\Team\Command\CreateTeam\UseCase;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: self::COMMAND_NAME,
    description: 'Creates a new team.',
    hidden: false
)]
final class CreateTeamCommand extends Command
{
    public const COMMAND_NAME = 'app:team:create';
    public const ARGUMENT_TEAM_NAME = 'name';

    public function __construct(private readonly UseCase $useCase)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument(self::ARGUMENT_TEAM_NAME, InputArgument::REQUIRED, 'The name of the team.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        try {
            $response = ($this->useCase)(new Request($input->getArgument(self::ARGUMENT_TEAM_NAME)));
        } catch (ValidationException $validation) {
            $io->error($validation->getMessage());

            return Command::FAILURE;
        }
        $io->success('Team has been created. Id is : '.$response->getId());

        return Command::SUCCESS;
    }
}
