<?php

namespace App\Infrastructure\Doctrine\DataFixtures;

use App\Domain\Entity\Game;
use App\Domain\Entity\Player;
use App\Domain\Entity\Team;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Uuid;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $team1 = new Team(Uuid::fromString('89f3ba99-ed47-405d-b62b-59fb90fa9095'), 'AJ Auxerre');
        $team2 = new Team(Uuid::fromString('9e09a3d5-dcd9-4fec-b3fe-b3f08e3a6aed'), 'Real Madrid');
        $team3 = new Team(Uuid::fromString('024f80e9-02e3-4678-9b16-dca7551d2d68'), 'Liverpool');
        $team4 = new Team(Uuid::fromString('15bf71c0-d31e-4ff9-889a-33e80a888c55'), 'Bayern Munich');
        $readyPlayerOne = new Player(Uuid::fromString('f2c34ecf-db88-40cb-99fd-45bb1ce3424f'), 'Wade Watts');
        $manager->persist($team1);
        $manager->persist($team2);
        $manager->persist($team3);
        $manager->persist($team4);
        $manager->persist($readyPlayerOne);
        $manager->flush();

        $game = new Game(
            Uuid::fromString('03434394-6e67-4917-8b34-a1f52a35dac4'),
            'Champions League - aller',
            $team1,
            $team2
        );
        $manager->persist($game);

        $game = new Game(
            Uuid::fromString('7d016410-4914-4aef-94cd-927987c1775c'),
            'Champions League - retour',
            $team2,
            $team1
        );
        $manager->persist($game);

        $game = new Game(
            Uuid::fromString('0bdf9865-ce79-4464-9a92-19d81d1f3bf3'),
            'Ligue imaginaire - journée 1',
            $team1,
            $team3
        );
        $manager->persist($game);

        $manager->flush();
    }
}
