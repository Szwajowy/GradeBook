<?php

namespace App\Repository;

use App\Entity\Grade;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class GradeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Grade::class);
    }

    public function findAllNormalGrades($student, $subject) {
        
        $qb = $this->createQueryBuilder('g')
            ->where('g.student = :student')
            ->andWhere('g.subject = :subject')
            ->andWhere('g.gradetype != :endGradeType')
            ->setParameter('student', $student)
            ->setParameter('subject', $subject)
            ->setParameter('endGradeType', 5);

        $query = $qb->getQuery();

        return $query->execute();
    }
}