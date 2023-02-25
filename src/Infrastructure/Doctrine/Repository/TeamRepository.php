<?php

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\Entity\Team;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Domain\Repository\TeamRepository as DomainTeamRepository;

/**
 * @method Team|null find($id, $lockMode = null, $lockVersion = null)
 * @method Team|null findOneBy(array $criteria, array $orderBy = null)
 * @method Team[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeamRepository extends ServiceEntityRepository implements DomainTeamRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Team::class);
    }

    public function create(Team $team): void
    {
        $this->_em->persist($team);
        $this->_em->flush();
    }

    /**
     * @return Team[]
     */
    public function findAll(): array
    {
        return $this->findBy([]);
    }

    public function exists(string $name): bool
    {
        if (!empty($this->findOneBy(['name' => $name]))) {
            return true;
        }

        return false;
    }
}
