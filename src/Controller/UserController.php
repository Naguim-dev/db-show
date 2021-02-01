<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserCreateType;
use App\Form\UserPasswordUpdateType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/user")
 * 
 */

class UserController extends AbstractController
{
    /**
     * @Route("/create_account", name="user_account_create")
     */
    public function createAccount(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new User();
        $userForm = $this->createForm(UserCreateType::class, $user);
        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {

            $plainPassword = $userForm->get('password')->getData();
            $encodedPassword = $passwordEncoder->encodePassword($user, $plainPassword);
            $user->setPassword($encodedPassword);

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($user);
            $manager->flush();

            $this->addFlash("success", "Votre compte a bien été crée ! Merci de vous authentifier !");
            return $this->redirectToRoute('app_login');
        }
        return $this->render(
            'user/create_account.html.twig',
            [
                "userForm" => $userForm->createView()
            ]
        );
    }

    /**
     * @Route("/change_password", name="user_password_change")
     * @IsGranted("ROLE_USER")
     */

    public function changePassword(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {

        $user = $this->getUser();
        $form = $this->createForm(UserPasswordUpdateType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $plainPassword = $form->get('newPassword')->getData();
            $encodedPassword = $passwordEncoder->encodePassword($user, $plainPassword);
            $user->setPassword($encodedPassword);

            $manager = $this->getDoctrine()->getManager();
            $manager->flush();

            $this->addFlash("success", "Votre mot de passe a bien été modifié");
            return $this->redirectToRoute('homepage');
        }


        return $this->render(
            'user/change_password.html.twig',
            [
                "form" => $form->createView()
            ]
        );
    }

    /**
     * @Route("/{id}", name="user_view", requirements={"id"="\d+"})
     * @IsGranted("ROLE_USER")
     */

    public function view($id)
    {
        $repository = $this->getDoctrine()->getRepository(User::class);
        $user = $repository->find($id);

        return $this->render(
            'user/view.html.twig',
            [
                "user" => $user
            ]
        );
    }
}
