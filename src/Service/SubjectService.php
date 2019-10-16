<?php 

namespace App\Service;

use App\Entity\Subjectname;

class SubjectService
{
    private $em;

    public function __construct(\Doctrine\ORM\EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getAllSubjects() {
        $subjects = $this->em->getRepository(Subjectname::class)->findAll();
        
        return $subjects;
    }
}