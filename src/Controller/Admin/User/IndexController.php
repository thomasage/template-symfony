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

    #[Route('/admin/user/{page<\d+>?1}', name: 'app_user_index', methods: ['GET'])]
    public function __invoke(int $page): Response
    {
        $users = $this->userRepository->findBySearch($page);

        return $this->render('admin/user/index.html.twig', [
            'users' => $users,
        ]);
    }
}
