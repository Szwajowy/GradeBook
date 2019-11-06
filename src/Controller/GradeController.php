<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

use App\Entity\User;
use App\Entity\Grade;
use App\Entity\Gradetype as GradetypeEntity;
use App\Entity\Gradevalue;
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

    public function roundGrade($gradesSum, $weightsSum) {
        $result = floor(($gradesSum / $weightsSum) * 2) / 2;
        $stringResult = strval($result);

        return number_format($stringResult, 1, '.', '');
    }

    /**
     * @Route("/subject/{subjectNameID}/group/{subjectGroupID}/student/{studentID}/grade/calculateEndGrade", name="calculateEndGrade")
     */
    public function calculateEndGrade(UserInterface $user, int $subjectNameID, int $subjectGroupID, int $studentID) {
        $loggedTeacher = $this->getDoctrine()->getRepository(User::class)->find($user->getId());
        $student = $this->getDoctrine()->getRepository(User::class)->find($studentID);
        $subjectName = $this->getDoctrine()->getRepository(Subjectname::class)->find($subjectNameID);
        $subjectGroup = $this->getDoctrine()->getRepository(Subjectgroup::class)->find($subjectGroupID);
        $subject = $this->getDoctrine()->getRepository(Subject::class)->findBy(['subjectname' => $subjectName, 'subjectgroup' => $subjectGroup]);
        $subject = $subject[0];
        $endGradeType = $this->getDoctrine()->getRepository(GradetypeEntity::class)->find(5);

        // Get grades other than end grade
        $studentGrades = $this->getDoctrine()->getRepository(Grade::class)->findAllNormalGrades($student, $subject);

        $gradesSum = 0;
        $weightsSum = 0;

        foreach($studentGrades as $studentGrade) {
            $gradesSum += $studentGrade->getGradetype()->getWeight() * $studentGrade->getGradevalue()->getContent();
            $weightsSum += $studentGrade->getGradetype()->getWeight();
        }

        $gradeValue = $this->getDoctrine()->getRepository(Gradevalue::class)->findBy(['content' => strval($this->roundGrade($gradesSum, $weightsSum))]);
        $gradeValue = $gradeValue[0];

        $existingEndGrade = $this->getDoctrine()->getRepository(Grade::class)->findBy(['student' => $student, 'subject' => $subject, 'gradetype' => $endGradeType]);

        $endGrade = new Grade();
        if(count($existingEndGrade) == 1) {
            $endGrade = $existingEndGrade[0];
        }

        $endGrade->setGradetype($endGradeType);
        $endGrade->setStudent($student);
        $endGrade->setSubject($subject);
        $endGrade->setTeacher($loggedTeacher);
        $endGrade->setGradevalue($gradeValue);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($endGrade);
        $entityManager->flush();

        return $this->redirect('/subject/'.$subjectNameID.'/group/'.$subjectGroupID.'/student/'.$studentID);
    }
}
