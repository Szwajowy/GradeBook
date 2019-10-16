<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\User;
use App\Entity\Subjectname;
use App\Entity\Subjectgroup;

class SubjectGroupController extends AbstractController
{
    /**
     * @Route("/subject/{subjectID}/group/{groupID}", name="subject_group")
     */
    public function index(int $subjectID, int $groupID)
    {
        $subjectName = $this->getDoctrine()->getRepository(Subjectname::class)->find($subjectID);
        $subjectGroup = $this->getDoctrine()->getRepository(Subjectgroup::class)->find($groupID);

        $students = $this->getDoctrine()->getRepository(User::class)->findBy([ 'subjectgroup' => $groupID ]);

        return $this->render('subject_group/index.html.twig', [
            'subjectName' => $subjectName,
            'subjectGroup' => $subjectGroup,
            'students' =>  $students,
        ]);
    }
}
