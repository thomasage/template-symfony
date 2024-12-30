<?php

declare(strict_types=1);

namespace App\Controller\Admin\User;

use App\Entity\User;
use App\Form\UserAddData;
use App\Form\UserAddType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

final class AddController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly TranslatorInterface $translator,
    ) {}

    #[Route('/admin/user/add', name: 'app_user_add', methods: ['GET', 'POST'])]
    public function __invoke(Request $request): Response
    {
        $data = new UserAddData();

        $formEdit = $this->createForm(UserAddType::class, $data);
        $formEdit->handleRequest($request);

        if ($formEdit->isSubmitted() && $formEdit->isValid()) {
            $user = new User();
            $user
                ->setEmail($data->email)
                ->setPassword($this->passwordHasher->hashPassword($user, $data->password));

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $this->addFlash('success', $this->translator->trans('notifications.user_added', domain: 'admin'));

            return $this->redirectToRoute('app_user_index');
        }

        return $this->render('admin/user/add.html.twig', [
            'formEdit' => $formEdit,
        ]);
    }
}
