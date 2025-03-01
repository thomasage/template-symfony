<?php

declare(strict_types=1);

namespace App\Controller\Settings;

use App\Entity\User;
use App\Form\Settings\ProfileData;
use App\Form\Settings\ProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Contracts\Translation\TranslatorInterface;

final class ProfileController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly TranslatorInterface $translator,
    ) {}

    #[Route('/settings/profile', name: 'app_settings_profile', methods: ['GET', 'POST'])]
    public function __invoke(#[CurrentUser] User $user, Request $request): Response
    {
        $data = new ProfileData();
        $data->email = $user->getEmail();

        $form = $this->createForm(ProfileType::class, $data);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setEmail($data->email);

            $this->entityManager->flush();

            $this->addFlash('success', $this->translator->trans('notifications.profile_updated', domain: 'settings'));

            return $this->redirectToRoute('app_settings_profile');
        }

        return $this->render('settings/profile.html.twig', [
            'form' => $form,
        ]);
    }
}
