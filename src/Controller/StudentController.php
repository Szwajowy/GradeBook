<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\User;
use App\Entity\Grade;
use App\Entity\Gradetype;
use App\Entity\Subjectname;
use App\Entity\Subjectgroup;

class StudentController extends AbstractController
{
    /**
     * @Route("/subject/{subjectID}/group/{groupID}/student/{studentID}", name="student")
     */
    public function index(int $subjectID, int $groupID, int $studentID)
    {
        $subjectName = $this->getDoctrine()->getRepository(Subjectname::class)->find($subjectID);
        $subjectGroup = $this->getDoctrine()->getRepository(Subjectgroup::class)->find($groupID);

        $student = $this->getDoctrine()->getRepository(User::class)->find($studentID);

        $grades = $this->getDoctrine()->getRepository(Grade::class)->findBy([ 'student' => $studentID, 'subject' => $subjectID ]);

        return $this->render('student/index.html.twig', [
            'subjectName' => $subjectName,
            'subjectGroup' => $subjectGroup,
            'student' => $student,
            'grades' => $grades,
        ]);
    }
}
