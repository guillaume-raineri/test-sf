<?php

namespace App\Infrastructure\Doctrine\DataFixtures;

use App\Domain\Entity\Game;
use App\Domain\Entity\Team;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Uuid;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $team1 = new Team(Uuid::v4(), 'AJ Auxerre');
        $team2 = new Team(Uuid::v4(), 'Real Madrid');
        $team3 = new Team(Uuid::v4(), 'Liverpool');
        $team4 = new Team(Uuid::v4(), 'Bayern Munich');
        $manager->persist($team1);
        $manager->persist($team2);
        $manager->persist($team3);
        $manager->persist($team4);
        $manager->flush();

        $game = new Game(
            Uuid::v4(),
            'Champions League - aller',
            $team1,
            $team2
        );
        $manager->persist($game);

        $game = new Game(
            Uuid::v4(),
            'Champions League - retour',
            $team2,
            $team1
        );
        $manager->persist($game);

        $game = new Game(
            Uuid::v4(),
            'Ligue imaginaire - journée 1',
            $team1,
            $team3
        );
        $manager->persist($game);

        $manager->flush();
    }
}
