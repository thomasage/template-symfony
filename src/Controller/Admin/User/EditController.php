<?php

declare(strict_types=1);

namespace App\Controller\Admin\User;

use App\Entity\User;
use App\Form\UserEditData;
use App\Form\UserEditType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

final class EditController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly TranslatorInterface $translator,
    ) {}

    #[Route('/admin/user/{uuid:user}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function __invoke(Request $request, User $user): Response
    {
        $data = new UserEditData();
        $data->email = $user->getEmail();
        $data->roles = $user->getRoles();
        $data->twoFactorsAuthentication = $user->hasTwoFactorsAuthentication();

        $formEdit = $this->createForm(UserEditType::class, $data);
        $formEdit->handleRequest($request);

        if ($formEdit->isSubmitted() && $formEdit->isValid()) {
            $user
                ->setEmail($data->email)
                ->setRoles($data->roles)
            ->setTwoFactorsAuthentication($data->twoFactorsAuthentication);

            $this->entityManager->flush();

            $this->addFlash('success', $this->translator->trans('notifications.user_updated', domain: 'admin'));

            return $this->redirectToRoute('app_user_index');
        }

        return $this->render('admin/user/edit.html.twig', [
            'formEdit' => $formEdit,
        ]);
    }
}
