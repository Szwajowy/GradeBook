<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Subject;
use App\Entity\Subjectname;
use App\Entity\Subjectgroup;
use App\Entity\Subjectteacher;

class SubjectController extends AbstractController
{
    /**
     * @Route("/subject/{subjectID}", name="subject")
     */
    public function index(int $subjectID)
    {
        $groups = [];

        $subjectName = $this->getDoctrine()->getRepository(Subjectname::class)->find($subjectID);
        
        // Znajdź przedmiot
        $subjects = $this->getDoctrine()->getRepository(Subject::class)->findBy(['subjectname' => $subjectID]);
        
        // if($subjects) {
        //     foreach($subjects as $subject) {
        //         $groups[] = $this->getDoctrine()->getRepository(Subjectgroup::class)->find( $subject->getSubjectgroup() );
        //         $subjectTeachers = $this->getDoctrine()->getRepository(Subjectteacher::class)->findBy([ 'subject' => $subjectID ]);
        //     }
        // }
        
        return $this->render('subject/index.html.twig', [
            'subjectName' => $subjectName,
            'subjectGroups' => $subjects,
        ]);
    }
}
