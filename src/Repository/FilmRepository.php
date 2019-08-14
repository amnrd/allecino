<?php

namespace App\Repository;

use App\Entity\Film;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Film|null find($id, $lockMode = null, $lockVersion = null)
 * @method Film|null findOneBy(array $criteria, array $orderBy = null)
 * @method Film[]    findAll()
 * @method Film[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FilmRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Film::class);
    }

     /**
      * @return Film[] Returns an array of Film objects
      */

    public function filmRanking()
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT f.name,
                COUNT(v.id) as vote_count
                FROM film f
                INNER JOIN vote v
                ON f.id = v.films_id
                WHERE v.value = true
                GROUP BY f.id
                ORDER BY vote_count DESC
                LIMIT 10';


            $stmt = $conn->prepare($sql);
            $stmt->execute();

        return $stmt->fetchAll();
    }

}
