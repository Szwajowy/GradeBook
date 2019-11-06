<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use App\Entity\User;
use App\Entity\Subject;
use App\Entity\Subjectname;
use App\Entity\Subjectgroup;
use App\Entity\Classblock;
use App\Entity\Presence;
use App\Entity\Presencevalue;

use App\Form\Type\SubjectType;
use App\Form\Type\SubjectgroupType;

class SubjectGroupController extends AbstractController
{

    /**
     * @Route("/subject/{subjectNameID}/group/new", name="createGroup")
     * Method({"GET", "POST"})
     */
    public function createGroup(Request $request, int $subjectNameID) {
        $subjectName = $this->getDoctrine()->getRepository(Subjectname::class)->find($subjectNameID);
        $existingGroups = $this->getDoctrine()->getRepository(Subjectgroup::class)->findAll();

        $subjectGroup = new Subjectgroup();

        $form = $this->createForm(SubjectgroupType::class, $subjectGroup);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $subjectGroup = $form->getData();
            $subject = new Subject();
            $subject->setSubjectname($subjectName);
            $subject->setSubjectgroup($subjectGroup);
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($subjectGroup);
            $entityManager->persist($subject);
            $entityManager->flush();

            return $this->redirect('/subject/'.$subjectName->getId());
        }

        return $this->render('/subject_group/add.html.twig', [
            'groups' => $existingGroups,
            'subjectName' => $subjectName,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/subject/{subjectNameID}/group/{subjectGroupID}/edit", name="editGroup")
     * Method({"GET", "POST"})
     */
    public function editGroup(Request $request, int $subjectNameID, int $subjectGroupID) {
        $subjectName = $this->getDoctrine()->getRepository(Subjectname::class)->find($subjectNameID);
        $subjectGroup = $this->getDoctrine()->getRepository(Subjectgroup::class)->find($subjectGroupID);

        $form = $this->createForm(SubjectgroupType::class, $subjectGroup);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $subjectGroup = $form->getData();
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($subjectGroup);
            $entityManager->flush();

            return $this->redirect('/subject/'.$subjectName->getId().'/group/new');
        }

        return $this->render('/subject_group/edit.html.twig', [
            'subjectName' => $subjectName,
            'subjectGroup' => $subjectGroup,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/subject/{subjectNameID}/group/{subjectGroupID}/remove", name="removeGroup")
     * Method({"GET", "POST"})
     */
    public function removeGroup(int $subjectNameID, int $subjectGroupID) {
        $subjectName = $this->getDoctrine()->getRepository(Subjectname::class)->find($subjectNameID);
        $subjectGroup = $this->getDoctrine()->getRepository(Subjectgroup::class)->find($subjectGroupID);

        // Find everyone subject where subjectGroup is related
        $subjects = $this->getDoctrine()->getRepository(Subject::class)->findBy(['subjectgroup' => $subjectGroup]);

        $entityManager = $this->getDoctrine()->getManager();
        foreach($subjects as $subject) {
            $entityManager->remove($subject);
        }

        $entityManager->remove($subjectGroup);
        $entityManager->flush();

        return $this->redirect('/subject/'.$subjectName->getId());
    }

    /**
     * @Route("/subject/{subjectNameID}/group/{subjectGroupID}/add", name="addGroupToSubject")
     * Method({"GET", "POST"})
     */
    public function addGroupToSubject(int $subjectNameID, int $subjectGroupID) {
        $subjectName = $this->getDoctrine()->getRepository(Subjectname::class)->find($subjectNameID);
        $subjectGroup = $this->getDoctrine()->getRepository(Subjectgroup::class)->find($subjectGroupID);

        $subject = new Subject();
        $subject->setSubjectname($subjectName);
        $subject->setSubjectgroup($subjectGroup);
        
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($subjectGroup);
        $entityManager->persist($subject);
        $entityManager->flush();

        return $this->redirect('/subject/'.$subjectName->getId());
    }

    /**
     * @Route("/groups", name="displayGroups")
     */
    public function displayGroups()
    {
        $existingGroups = $this->getDoctrine()->getRepository(Subjectgroup::class)->findAll();

        return $this->render('group/index.html.twig', [
            'groups' => $existingGroups,
        ]);
    }

    /**
     * @Route("/subject/{subjectNameID}/group/{subjectGroupID}/presence", name="displayPresenceOfGroup")
     */
    public function displayPresenceOfGroup(int $subjectNameID, int $subjectGroupID)
    {
        $subjectName = $this->getDoctrine()->getRepository(Subjectname::class)->find($subjectNameID);
        $subjectGroup = $this->getDoctrine()->getRepository(Subjectgroup::class)->find($subjectGroupID);
    
        $subject = $this->getDoctrine()->getRepository(Subject::class)->findBy([ 'subjectname' => $subjectNameID, 'subjectgroup' => $subjectGroupID ]);
        $students = $this->getDoctrine()->getRepository(User::class)->findBy([ 'subjectgroup' => $subjectGroupID ]);
        $classBlocks = $this->getDoctrine()->getRepository(Classblock::class)->findBy(['subject' => $subject[0]->getId()]);
        $presenceValues = $this->getDoctrine()->getRepository(Presencevalue::class)->findAll();

        $presences = [];
        foreach($students as $student) {
            foreach($classBlocks as $classBlock) {
                $presences[$student->getId()][$classBlock->getId()] = new Presence();

                $existingPresence = $this->getDoctrine()->getRepository(Presence::class)->findBy(['classblock' => $classBlock, 'student' => $student]);
                
                if(count($existingPresence) != 0) {
                    $presences[$student->getId()][$classBlock->getId()] = $existingPresence[0];
                } else {
                    $presences[$student->getId()][$classBlock->getId()]->setClassblock($classBlock); 
                    $presences[$student->getId()][$classBlock->getId()]->setPresencevalue($presenceValues[4]); 
                    $presences[$student->getId()][$classBlock->getId()]->setStudent($student);
    
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($presences[$student->getId()][$classBlock->getId()]);
                    $entityManager->flush();
                }
            }
        }

        return $this->render('subject_group/check-presence.html.twig', [
            'subjectName' => $subjectName,
            'subjectGroup' => $subjectGroup,
            'students' =>  $students,
            'classBlocks' =>  $classBlocks,
            'presenceValues' => $presenceValues,
            'presences' => $presences,
        ]);
    }

    /**
     * @Route("/student/{studentID}/classBlock/{classBlockID}/presence/{presenceValueID}", name="changePresenceOfStudent")
     */
    public function changePresenceOfStudent(int $studentID, int $classBlockID, int $presenceValueID) {
        $student = $this->getDoctrine()->getRepository(User::class)->find($studentID);
        $classBlock = $this->getDoctrine()->getRepository(Classblock::class)->find($classBlockID);
        $presenceValue = $this->getDoctrine()->getRepository(Presencevalue::class)->find($presenceValueID);

        $existingPresence = $this->getDoctrine()->getRepository(Presence::class)->findBy(['classblock' => $classBlock, 'student' => $student]);
        
        $presence = new Presence();
        if(count($existingPresence) != 0) {
            $presence = $existingPresence[0]; 
        } else {
            $presence->setClassblock($classBlock);  
            $presence->setStudent($student);
        }

        $presence->setPresencevalue($presenceValue);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($presence);
        $entityManager->flush();

        return new JsonResponse(['data' => 'Succesfully added!']);
    }

    /**
     * @Route("/subject/{subjectNameID}/group/{subjectGroupID}", name="subject_group")
     */
    public function index(int $subjectNameID, int $subjectGroupID)
    {
        $subjectName = $this->getDoctrine()->getRepository(Subjectname::class)->find($subjectNameID);
        $subjectGroup = $this->getDoctrine()->getRepository(Subjectgroup::class)->find($subjectGroupID);
        
        $subject = $this->getDoctrine()->getRepository(Subject::class)->findBy([ 'subjectname' => $subjectNameID, 'subjectgroup' => $subjectGroupID ]);

        // TODO: Pobierz trzy najbliższe zajęcia zamiast tego
        $classBlocks = $this->getDoctrine()->getRepository(Classblock::class)->findBy([ 'subject' => $subject[0]->getId() ], [], 3, 0);
        $students = $this->getDoctrine()->getRepository(User::class)->findBy([ 'subjectgroup' => $subjectGroupID ]);

        return $this->render('subject_group/index.html.twig', [
            'subjectName' => $subjectName,
            'subjectGroup' => $subjectGroup,
            'students' =>  $students,
            'activities' =>  $classBlocks,
        ]);
    }
}
