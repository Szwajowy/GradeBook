<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Subject;
use App\Entity\Subjectname;
use App\Entity\Subjectgroup;

class GroupController extends AbstractController
{
    /**
     * @Route("/group/{subjectGroupID}/edit", name="editGroup")
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
     * @Route("/group/{subjectGroupID}/remove", name="removeGroup")
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
     * @Route("/groups", name="displayGroups")
     */
    public function displayGroups()
    {
        $existingGroups = $this->getDoctrine()->getRepository(Subjectgroup::class)->findAll();

        return $this->render('group/index.html.twig', [
            'groups' => $existingGroups,
        ]);
    }
}
