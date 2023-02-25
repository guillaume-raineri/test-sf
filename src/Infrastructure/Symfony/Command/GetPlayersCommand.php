<?php

namespace App\Infrastructure\Symfony\Command;

use App\UseCase\Player\GetPlayers\Request;
use App\UseCase\Player\GetPlayers\UseCase;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: self::COMMAND_NAME,
    description: 'Display players.',
    hidden: false
)]
final class GetPlayersCommand extends Command
{
    public const COMMAND_NAME = 'app:player:list';

    public function __construct(private readonly UseCase $useCase)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $response = ($this->useCase)(new Request());

        $table = new Table($output);
        $rows = [];
        foreach ($response->getPlayers() as $player) {
            $rows[] = [$player->getId(), $player->getName()];
        }
        $table
            ->setHeaders(['Id', 'Name'])
            ->setRows($rows);
        $table->render();

        return Command::SUCCESS;
    }
}
