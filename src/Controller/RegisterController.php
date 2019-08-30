<?php

namespace App\Controller;

use App\Entity\ProfileUser;
use App\Form\ProfileUserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends AbstractController
{
    /**
     * @Route("/register", name="user_register")
     */
    public function index(Request $request,  UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $profileUser = new ProfileUser();

        $form = $this->createForm(ProfileUserType::class, $profileUser);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $userPasswordEncoder->encodePassword($profileUser, $profileUser->getPassword());
            $profileUser->setPassword($password);
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($profileUser);
            $entityManager->flush();
            $this->redirectToRoute('micro_post');
        }

        return $this->render('register/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
