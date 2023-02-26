<?php

namespace App\Infrastructure\Symfony\Command\Game\Query;

use App\UseCase\Game\Query\GetGames\Request;
use App\UseCase\Game\Query\GetGames\Response;
use App\UseCase\Game\Query\GetGames\UseCase as UseCaseListAll;
use App\UseCase\Team\Query\GetTeamGames\Request as RequestForTeam;
use App\UseCase\Team\Query\GetTeamGames\UseCase as UseCaseListForTeam;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

#[AsCommand(
    name: self::COMMAND_NAME,
    description: 'Display list of all games or for a specific team',
    hidden: false
)]
final class GetGamesCommand extends Command
{
    public const COMMAND_NAME = 'app:game:list';
    public const ARGUMENT_TEAM_ID = 'team';

    public function __construct(
        private readonly UseCaseListAll $useCaseListAll,
        private readonly UseCaseListForTeam $useCaseListForTeam
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument(self::ARGUMENT_TEAM_ID, InputArgument::OPTIONAL, 'The id of the team.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $teamId = $input->getArgument(self::ARGUMENT_TEAM_ID);
        if (!empty($teamId)) {
            try {
                $response = ($this->useCaseListForTeam)(new RequestForTeam($teamId));
            } catch (Throwable $e) {
                $io->error($e->getMessage());

                return Command::FAILURE;
            }
        } else {
            $response = ($this->useCaseListAll)(new Request());
        }

        /** @var Response $response */
        $table = new Table($output);
        $rows = [];
        foreach ($response->getGames() as $game) {
            $rows[] = [$game->getId(), $game->getName(), $game->getHomeTeam()->getName(), $game->getAwayTeam()->getName()];
        }

        $output->writeln(sprintf('Found %s games.', count($response->getGames())));
        $table
            ->setHeaders(['Id', 'Name', 'Home Team', 'Away team'])
            ->setRows($rows);
        $table->render();

        return Command::SUCCESS;
    }
}
