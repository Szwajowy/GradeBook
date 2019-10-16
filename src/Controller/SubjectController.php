<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Subject;
use App\Entity\Subjectname;
use App\Entity\Subjectgroup;

class SubjectController extends AbstractController
{
    /**
     * @Route("/subject/{subjectID}", name="subject")
     */
    public function index(int $subjectID)
    {
        $groups = [];

        $subjectName = $this->getDoctrine()->getRepository(Subjectname::class)->find($subjectID);
        
        // Znajdz id przedmiotu
        $subjects = $this->getDoctrine()->getRepository(Subject::class)->findBy(['subjectname' => $subjectID]);
        
        if($subjects) {
            foreach($subjects as $subject) {
                $groups[] = $this->getDoctrine()->getRepository(Subjectgroup::class)->find( $subject->getSubjectgroup() );
            }

        }
        
        return $this->render('subject/index.html.twig', [
            'subjectName' => $subjectName,
            'groups' => $groups,
        ]);
    }
}
