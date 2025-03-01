<?php

declare(strict_types=1);

namespace App\Controller\Profile;

use App\Entity\User;
use App\Form\ProfileData;
use App\Form\ProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Contracts\Translation\TranslatorInterface;

final class IndexController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly TranslatorInterface $translator,
    ) {}

    #[Route('/profile', name: 'app_profile', methods: ['GET', 'POST'])]
    public function __invoke(#[CurrentUser] User $user, Request $request): Response
    {
        $data = new ProfileData();
        $data->email = $user->getEmail();

        $form = $this->createForm(ProfileType::class, $data);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setEmail($data->email);

            $this->entityManager->flush();

            $this->addFlash('success', $this->translator->trans('notifications.profile_updated', domain: 'profile'));

            return $this->redirectToRoute('app_profile');
        }

        return $this->render('profile/index.html.twig', [
            'form' => $form,
        ]);
    }
}
