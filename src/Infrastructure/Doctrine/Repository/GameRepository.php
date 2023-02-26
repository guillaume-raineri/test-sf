<?php

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\Entity\Game;
use App\Domain\Repository\GameRepository as DomainGameRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Game|null find($id, $lockMode = null, $lockVersion = null)
 * @method Game|null findOneBy(array $criteria, array $orderBy = null)
 * @method Game[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameRepository extends ServiceEntityRepository implements DomainGameRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Game::class);
    }

    public function get(string $id): Game|null
    {
        return $this->find($id);
    }

    public function save(Game $game): void
    {
        $this->_em->persist($game);
        $this->_em->flush();
    }

    public function create(Game $game): void
    {
        $this->save($game);
    }

    /**
     * @return Game[]
     */
    public function findAll(): array
    {
        return parent::findAll();
    }
}
