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

use App\Form\Type\UserType;

class StudentController extends AbstractController
{
    /**
     * @Route("/subject/{subjectNameID}/group/{subjectGroupID}/student/create", name="createStudent")
     */
    public function createStudent(Request $request, int $subjectNameID, int $subjectGroupID) {
        $subjectName = $this->getDoctrine()->getRepository(Subjectname::class)->find($subjectNameID);
        $subjectGroup = $this->getDoctrine()->getRepository(Subjectgroup::class)->find($subjectGroupID);
        $student = new User();
        
        $form = $this->createForm(UserType::class, $student);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $student = $form->getData();
            $student->setSubjectgroup($subjectGroup);
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($student);
            $entityManager->flush();

            return $this->redirectToRoute('main');
        }
            
        return $this->render('/student/create-student.html.twig', [
            'form' => $form->createView(),
            'subjectName' => $subjectName,
            'subjectGroup' => $subjectGroup
            ]);
    }

    /**
     * @Route("/subject/{subjectNameID}/group/{subjectGroupID}/student/{studentID}/edit", name="editStudent")
     */  
    public function editStudent(Request $request, int $subjectNameID, int $subjectGroupID, int $studentID) {
        $subjectName = $this->getDoctrine()->getRepository(Subjectname::class)->find($subjectNameID);
        $subjectGroup = $this->getDoctrine()->getRepository(Subjectgroup::class)->find($subjectGroupID);
        $student = $this->getDoctrine()->getRepository(User::class)->find($studentID);
        
        $form = $this->createForm(UserType::class, $student);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $student = $form->getData();
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($student);
            $entityManager->flush();

            return $this->redirectToRoute('main');
        }
            
        return $this->render('/student/edit-student.html.twig', [
            'form' => $form->createView(),
            'subjectName' => $subjectName,
            'subjectGroup' => $subjectGroup,
            'student' => $student
            ]);
    }

    /**
     * @Route("/subject/{subjectNameID}/group/{subjectGroupID}/student/{studentID}/remove", name="removeStudent")
     */  
    public function removeStudent(int $subjectNameID, int $subjectGroupID, int $studentID) {
        $student = $this->getDoctrine()->getRepository(User::class)->find($studentID);
        $studentGrades = $this->getDoctrine()->getRepository(Grade::class)->findBy(['student' => $studentID]);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($student);
        foreach($studentGrades as $grade) {
            $entityManager->remove($grade);
        }
        $entityManager->flush();

        return $this->redirect('/subject/'.$subjectNameID.'/group/'.$subjectGroupID);
    }

    /**
     * @Route("/subject/{subjectID}/group/{groupID}/student/{studentID}", name="student")
     */
    public function index(int $subjectID, int $groupID, int $studentID)
    {
        $subjectName = $this->getDoctrine()->getRepository(Subjectname::class)->find($subjectID);
        $subjectGroup = $this->getDoctrine()->getRepository(Subjectgroup::class)->find($groupID);
        $subject = $this->getDoctrine()->getRepository(Subject::class)->findBy(['subjectname' => $subjectName, 'subjectgroup' => $subjectGroup]);
        $subject = $subject[0];

        $student = $this->getDoctrine()->getRepository(User::class)->find($studentID);

        $grades = $this->getDoctrine()->getRepository(Grade::class)->findBy(['student' => $studentID, 'subject' => $subject->getId()], ['gradetype' => 'ASC']);

        return $this->render('student/index.html.twig', [
            'subjectName' => $subjectName,
            'subjectGroup' => $subjectGroup,
            'student' => $student,
            'grades' => $grades,
        ]);
    }
}
