<?php

namespace App\Infrastructure\Symfony\Command\Team\Query;

use App\UseCase\Team\Query\GetTeams\Request;
use App\UseCase\Team\Query\GetTeams\Response;
use App\UseCase\Team\Query\GetTeams\UseCase;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: self::COMMAND_NAME,
    description: 'Display teams.',
    hidden: false
)]
final class GetTeamsCommand extends Command
{
    public const COMMAND_NAME = 'app:team:list';

    public function __construct(private readonly UseCase $useCase)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var Response $response */
        $response = ($this->useCase)(new Request());

        $table = new Table($output);
        $rows = [];
        foreach ($response->getTeams() as $team) {
            $rows[] = [$team->getId(), $team->getName()];
        }
        $table
            ->setHeaders(['Id', 'Name'])
            ->setRows($rows);
        $table->render();

        return Command::SUCCESS;
    }
}
