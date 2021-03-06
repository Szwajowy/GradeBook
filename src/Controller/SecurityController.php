<?php
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use App\Entity\User;
use App\Entity\Subjectgroup;

class SecurityController extends AbstractController
{
    private $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder) {
        $this->encoder = $encoder;
    }
    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request, AuthenticationUtils $utils) {
        $user = new User();
            
        $form = $this->createFormBuilder($user)
            ->add('username', TextType::class, array(
                'label' => 'Login',
                'attr' => array('class' => 'form-control')
                ))
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options'  => array(
                    'label' => 'Hasło',
                    'attr' => array('class' => 'form-control')),
                'second_options' => array(
                    'label' => 'Powtórz hasło',
                    'attr' => array('class' => 'form-control')),
            ))
            ->add('forename', TextType::class, array(
                'attr' => array('class' => 'form-control')
            ))
            ->add('surname', TextType::class, array(
                'attr' => array('class' => 'form-control')
            ))
            ->add('email', TextType::class, array(
                'attr' => array('class' => 'form-control')
            ))
            ->add('subjectgroup', EntityType::class, [
                'class' => Subjectgroup::class,
                'label' => 'Grupa',
                'choice_label' => 'name'
            ])
            ->add('register', SubmitType::class, array(
                'label' => 'Zarejestruj',
                'attr' => array('class' => 'btn btn-primary mt-3')
            ))
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $user->setPassword(
                $this->encoder->encodePassword($user, $user->getPlainPassword())
            );
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('login');
        }

        $error = $utils->getLastAuthenticationError();

        return $this->render('security/register.html.twig', [
            'form' => $form->createView(),
            'error' => $error
        ]);
    }
    /**
     * @Route("/login", name="login")
     * Method({"GET", "POST"})
     */
    public function login(Request $request, AuthenticationUtils $utils) {
        $user = new User();
            
        $form = $this->createFormBuilder($user)
            ->add('username', TextType::class, array(
                'attr' => array('class' => 'form-control')
                ))
            ->add('password', PasswordType::class, array(
                'attr' => array('class' => 'form-control')
            ))
            ->add('login', SubmitType::class, array(
                'label' => 'Zaloguj',
                'attr' => array('class' => 'btn btn-primary mt-3')
            ))
            ->getForm();

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('post_list');
        }

        $error = $utils->getLastAuthenticationError();
        return $this->render('security/login.html.twig', [
            'form' => $form->createView(),
            'error' => $error
        ]);
    }
    /**
     * @Route("/logout", name="logout")
     */
    public function logout() {
        return $this->render('index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }
}