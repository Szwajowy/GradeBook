<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\User;
use App\Entity\Subject;
use App\Entity\Subjectname;
use App\Entity\Subjectgroup;
use App\Entity\Classblock;

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
     * @Route("/subject/{subjectID}/group/{groupID}", name="subject_group")
     */
    public function index(int $subjectID, int $groupID)
    {
        $subjectName = $this->getDoctrine()->getRepository(Subjectname::class)->find($subjectID);
        $subjectGroup = $this->getDoctrine()->getRepository(Subjectgroup::class)->find($groupID);
        
        $subject = $this->getDoctrine()->getRepository(Subject::class)->findBy([ 'subjectname' => $subjectID, 'subjectgroup' => $groupID ]);

        // TODO: Pobierz zajęcia z danego tygodnia
        $activities = $this->getDoctrine()->getRepository(Classblock::class)->findBy([ 'subject' => $subject[0]->getId() ]);
        $students = $this->getDoctrine()->getRepository(User::class)->findBy([ 'subjectgroup' => $groupID ]);

        return $this->render('subject_group/index.html.twig', [
            'subjectName' => $subjectName,
            'subjectGroup' => $subjectGroup,
            'students' =>  $students,
            'activities' =>  $activities,
        ]);
    }
}
