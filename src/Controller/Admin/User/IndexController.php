<?php

declare(strict_types=1);

namespace App\Controller\Admin\User;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class IndexController extends AbstractController
{
    public function __construct(private readonly UserRepository $userRepository) {}

    #[Route('/admin/user', name: 'app_user_index', methods: ['GET'])]
    public function __invoke(): Response
    {
        return $this->render('admin/user/index.html.twig', [
            'users' => $this->userRepository->findAll(),
        ]);
    }
}
