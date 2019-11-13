<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Subject;
use App\Entity\Subjectname;
use App\Entity\Subjectgroup;
use App\Entity\Subjectteacher;

use App\Form\Type\SubjectType;

class SubjectController extends AbstractController
{

    /**
     * @Route("/subjectName/new", name="createSubjectName")
     * Method({"GET", "POST"})
     */
    public function createSubjectName(Request $request) {
        $subject = new Subjectname();
        
        $form = $this->createForm(SubjectnameType::class, $subject);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $subject = $form->getData();
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($subject);
            $entityManager->flush();

            return $this->redirectToRoute('main');
        }
            
        return $this->render('/subject/createSubjectName.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/subjectGroup/{subjectID}/edit", name="editSubject")
     * Method({"GET", "POST"})
     */
    public function editSubject(Request $request, int $subjectID) {
        $subject = $this->getDoctrine()->getRepository(Subject::class)->find($subjectID);
        
        $form = $this->createForm(SubjectType::class, $subject);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $subject = $form->getData();

            // Usuń aktualnych nauczycieli
            foreach($subject->getTeacher() as $existingTeacher) {
                $subject->removeTeacher($existingTeacher['teacher']);
            }

            // Dodaj nowych
            foreach($form->getData()->getTeacher() as $teacher) {
                $subject->addTeacher($teacher['teacher']);
            }

            dump($subject->getTeacher());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($subject);
            $entityManager->flush();

            return $this->redirectToRoute('main');
        }
            
        return $this->render('/subject/edit.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/subjectGroup/{subjectID}/delete", name="removeSubject")
     * Method({"GET", "POST"})
     */
    public function removeSubject(Request $request, int $subjectID) {
        $subject = $this->getDoctrine()->getRepository(Subject::class)->find($subjectID);
        $subjectName = $subject->getSubjectName();

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($subject);
        $entityManager->flush();

            return $this->redirect('/subject/'.$subjectName->getId());
    }

    /**
     * @Route("/subject/{subjectID}", name="subject")
     */
    public function index(int $subjectID)
    {
        $groups = [];

        $subjectName = $this->getDoctrine()->getRepository(Subjectname::class)->find($subjectID);
        
        // Znajdź przedmiot
        $subjects = $this->getDoctrine()->getRepository(Subject::class)->findBy(['subjectname' => $subjectID]);
        
        return $this->render('subject/index.html.twig', [
            'subjectName' => $subjectName,
            'subjectGroups' => $subjects,
        ]);
    }

}
