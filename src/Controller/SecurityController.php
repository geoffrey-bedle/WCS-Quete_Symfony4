<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login_page.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/inscription",name="app_insc")
     */
    public function addUser(Request $request, UserPasswordEncoderInterface $passwordencoder, \Swift_Mailer $mailer): Response
    {

        $user = new User();

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $pass = $user->getPassword();
            $user->setRoles(['ROLE_USER']);
            $user->setPassword($passwordencoder->encodePassword(
                $user,
                $user->getPassword()
            ));
            $message = (new \Swift_Message('Confirmation inscription blog.'))
                ->setFrom($this->getParameter('mailer_from'))
                ->setTo($user->getEmail())
                ->setBody(
                    $this->renderView(
                        'email/confirm_insc.html.twig',['pass'=>$pass,'mail'=>$user->getEmail()]
                    ),
                    'text/html'
                );
            $mailer->send($message);
               $entityManager->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/inscription/_inscription.twig',
            ['form' => $form->createView()
            ]);
    }
}
