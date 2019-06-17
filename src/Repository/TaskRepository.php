<?php
namespace App\Repository;
use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
/**
 * @method Task|null find($id, $lockMode = null, $lockVersion = null)
 * @method Task|null findOneBy(array $criteria, array $orderBy = null)
 * @method Task[]    findAll()
 * @method Task[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Task::class);
    }

    public function findDone()
    {
        return $this->createQueryBuilder('t')
            ->select('t')
            ->where('t.isDone = 1')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findNotDone()
    {
        return $this->createQueryBuilder('t')
            ->select('t')
            ->where('t.isDone = 0')
            ->getQuery()
            ->getResult()
            ;
    }

}
