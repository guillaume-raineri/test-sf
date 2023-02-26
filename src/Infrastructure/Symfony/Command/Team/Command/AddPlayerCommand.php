<?php

namespace App\Infrastructure\Symfony\Command\Team\Command;

use App\UseCase\Team\Command\AddPlayer\Request;
use App\UseCase\Team\Command\AddPlayer\UseCase;
use Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: self::COMMAND_NAME,
    description: 'Adds a player to a team.',
    hidden: false
)]
final class AddPlayerCommand extends Command
{
    public const COMMAND_NAME = 'app:team:player:add';
    public const ARGUMENT_TEAM_ID = 'team';
    public const ARGUMENT_PLAYER_ID = 'player';

    public function __construct(private readonly UseCase $useCase)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument(self::ARGUMENT_TEAM_ID, InputArgument::REQUIRED, 'The id of the team.');
        $this->addArgument(self::ARGUMENT_PLAYER_ID, InputArgument::REQUIRED, 'The id of the player.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        try {
            $response = ($this->useCase)(
                new Request(
                    $input->getArgument(self::ARGUMENT_TEAM_ID),
                    $input->getArgument(self::ARGUMENT_PLAYER_ID),
                )
            );
        } catch (Exception $validation) {
            $io->error($validation->getMessage());

            return Command::FAILURE;
        }
        $io->success('The player has been added.');

        return Command::SUCCESS;
    }
}
