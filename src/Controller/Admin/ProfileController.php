<?php

/**
 * Admin profile controller.
 */

namespace App\Controller\Admin;

use App\Form\Type\ChangePasswordType;
use App\Form\Type\ProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Class ProfileController.
 */
#[Route('/admin/profile')]
#[IsGranted('ROLE_ADMIN')]
class ProfileController extends AbstractController
{
    /**
     * Edit profile action.
     *
     * @param Request                $request HTTP request
     * @param EntityManagerInterface $em      Entity manager
     *
     * @return Response HTTP response
     */
    #[Route('/edit', name: 'admin_profile_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Dane administratora zostały zapisane.');

            return $this->redirectToRoute('admin_profile_edit');
        }

        return $this->render('admin/profile/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Change password action.
     *
     * @param Request                     $request HTTP request
     * @param EntityManagerInterface      $em      Entity manager
     * @param UserPasswordHasherInterface $hasher  User password hasher
     *
     * @return Response HTTP response
     */
    #[Route('/password', name: 'admin_profile_password', methods: ['GET', 'POST'])]
    public function changePassword(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $hasher): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(ChangePasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            if (!$hasher->isPasswordValid($user, $data['oldPassword'])) {
                $this->addFlash('error', 'Stare hasło jest niepoprawne.');

                return $this->redirectToRoute('admin_profile_password');
            }

            $user->setPassword(
                $hasher->hashPassword($user, $data['newPassword'])
            );

            $em->flush();

            $this->addFlash('success', 'Hasło zostało zmienione.');

            return $this->redirectToRoute('admin_profile_password');
        }

        return $this->render('admin/profile/password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
