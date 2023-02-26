<?php

namespace App\Infrastructure\Symfony\Command\Game\Command;

use App\UseCase\Game\Command\CreateGame\Request;
use App\UseCase\Game\Command\CreateGame\UseCase;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

#[AsCommand(
    name: self::COMMAND_NAME,
    description: 'Creates a new game.',
    hidden: false
)]
final class CreateGameCommand extends Command
{
    public const COMMAND_NAME = 'app:game:create';
    public const ARGUMENT_GAME_NAME = 'name';
    public const ARGUMENT_GAME_HOME_TEAM_ID = 'home-team';
    public const ARGUMENT_GAME_AWAY_TEAM_ID = 'away-team';

    public function __construct(private readonly UseCase $useCase)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument(self::ARGUMENT_GAME_NAME, InputArgument::REQUIRED, 'The name of the game.');
        $this->addArgument(self::ARGUMENT_GAME_HOME_TEAM_ID, InputArgument::REQUIRED, 'The id of the home team.');
        $this->addArgument(self::ARGUMENT_GAME_AWAY_TEAM_ID, InputArgument::REQUIRED, 'The id of the away team.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        try {
            $response = ($this->useCase)(new Request(
                $input->getArgument(self::ARGUMENT_GAME_NAME),
                $input->getArgument(self::ARGUMENT_GAME_HOME_TEAM_ID),
                $input->getArgument(self::ARGUMENT_GAME_AWAY_TEAM_ID),
            ));
        } catch (Throwable $validation) {
            $io->error($validation->getMessage());

            return Command::FAILURE;
        }
        $io->success('Game has been created. Id is : '.$response->getId());

        return Command::SUCCESS;
    }
}
