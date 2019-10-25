<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\User;
use App\Entity\Grade;
use App\Entity\Subject;
use App\Entity\Subjectname;
use App\Entity\Subjectgroup;

use App\Form\Type\GradeType;

class GradeController extends AbstractController
{
    /**
     * @Route("/subject/{subjectNameID}/group/{subjectGroupID}/student/{studentID}/grade/add", name="createGrade")
     */
    public function createGrade(Request $request, int $subjectNameID, int $subjectGroupID, int $studentID) {
        $grade = new Grade();
        $student = $this->getDoctrine()->getRepository(User::class)->find($studentID);
        $subjectName = $this->getDoctrine()->getRepository(Subjectname::class)->find($subjectNameID);
        $subjectGroup = $this->getDoctrine()->getRepository(Subjectgroup::class)->find($subjectGroupID);
        $subject = $this->getDoctrine()->getRepository(Subject::class)->findBy(['subjectname' => $subjectName, 'subjectgroup' => $subjectGroup]);
        $subject = $subject[0];

        $form = $this->createForm(GradeType::class, $grade, [
            'teachers' => $subject->getTeacher()
        ]);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $grade = $form->getData();
            $grade->setStudent($student);
            $grade->setSubject($subject);
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($grade);
            $entityManager->flush();

            return $this->redirect('/subject/'.$subjectNameID.'/group/'.$subjectGroupID.'/student/'.$studentID);
        }
            
        return $this->render('/grade/create-grade.html.twig', [
            'form' => $form->createView(),
            'subjectName' => $subjectName,
            'subjectGroup' => $subjectGroup,
            'student' => $student
            ]);
    }

     /**
     * @Route("/subject/{subjectNameID}/group/{subjectGroupID}/student/{studentID}/grade/{gradeID}/edit", name="editGrade")
     */
    public function editGrade(Request $request, int $subjectNameID, int $subjectGroupID, int $studentID, int $gradeID) {
        $grade = $this->getDoctrine()->getRepository(Grade::class)->find($gradeID);
        $student = $this->getDoctrine()->getRepository(User::class)->find($studentID);
        $subjectName = $this->getDoctrine()->getRepository(Subjectname::class)->find($subjectNameID);
        $subjectGroup = $this->getDoctrine()->getRepository(Subjectgroup::class)->find($subjectGroupID);
        $subject = $this->getDoctrine()->getRepository(Subject::class)->findBy(['subjectname' => $subjectName, 'subjectgroup' => $subjectGroup]);
        $subject = $subject[0];

        $form = $this->createForm(GradeType::class, $grade, [
            'teachers' => $subject->getTeacher()
        ]);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $grade = $form->getData();
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($grade);
            $entityManager->flush();

            return $this->redirect('/subject/'.$subjectNameID.'/group/'.$subjectGroupID.'/student/'.$studentID);
        }
            
        return $this->render('/grade/edit-grade.html.twig', [
            'form' => $form->createView(),
            'subjectName' => $subjectName,
            'subjectGroup' => $subjectGroup,
            'student' => $student
            ]);
    }

    /**
     * @Route("/subject/{subjectNameID}/group/{subjectGroupID}/student/{studentID}/grade/{gradeID}/remove", name="removeGrade")
     */
    public function removeGrade(int $subjectNameID, int $subjectGroupID, int $studentID, int $gradeID) {
        $grade = $this->getDoctrine()->getRepository(Grade::class)->find($gradeID);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($grade);
        $entityManager->flush();

        return $this->redirect('/subject/'.$subjectNameID.'/group/'.$subjectGroupID.'/student/'.$studentID);
    }
}
