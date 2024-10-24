<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Service\ApiService;
use App\Entity\User;


#[Route('/api', name: 'api_')]
class ApiController extends AbstractController
{

    private EntityManagerInterface $em;
    private ApiService $apiSer;

    public function __construct(EntityManagerInterface $entityManager, ApiService $apiService)
    {
        $this->em = $entityManager;
        $this->apiSer = $apiService;
    }

    #[Route('/test', name: 'test', methods: ['get'])]
    public function test(Request $request): JsonResponse
    {

        $data = [
            'result' => 'ok',
        ];

        return $this->json($data);
    }

    #[Route('/user/add', name: 'user_add', methods: ['post'])]
    public function userAdd(Request $request): JsonResponse
    {

        $email = (string) mb_strtolower(
            trim($request->get('email'))
        );

        $password = (string) trim($request->get('password'));

        $user = null;

        $errors = [];

        if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Укажите правильный адрес электронной почты!';
        }

        if (!$password) {
            $errors['password'] = 'Укажите пароль!';
        }

        $user = $this->em->getRepository(User::class)->findOneBy([
            'email' => $email
        ]);
        if ($user) {
            $errors['user'] = 'Пользователь с таким email уже существует!';
        }

        if ($errors) { 
            $errors['result'] ='error';
            return $this->json($errors);
        }

        if (!$user) {
            $this->em->persist(
                $user = $this->apiSer->createUserObj($email, $password)
            );
        }

        $this->em->flush($user);

        return $this->json([
            'result' => 'ok',
            'user' => [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'roles' => $user->getRoles()
            ]
        ]);
    }

    #[Route('/user/get', name: 'user_get', methods: ['get'])]
    public function userGet(Request $request): JsonResponse
    {
        $email = (string) mb_strtolower(
            trim($request->get('email'))
        );

        $user = null;

        $errors = [];

        if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Укажите правильный адрес электронной почты!';
        }

        $user = $this->em->getRepository(User::class)->findOneBy([
            'email' => $email
        ]);
        if (!$user) {
            $errors['user'] = 'Пользователь с таким email не существует!';
        }

        if ($errors) { 
            $errors['result'] ='error';
            return $this->json($errors);
        }

        return $this->json([
            'result' => 'ok',
            'user' => [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'roles' => $user->getRoles()
            ]
        ]);
    }

    #[Route('/user/edit', name: 'user_edit', methods: ['post'])]
    public function userEdit(Request $request): JsonResponse
    {

        return $this->json([
            'result' => 'ok'
        ]);
    }

    #[Route('/user/remove', name: 'user_remove', methods: ['post'])]
    public function userRemove(Request $request): JsonResponse
    {
        $id = (int) $request->get('id');

        $user = null;

        $errors = [];

        if (!$id) {
            $errors['id'] = 'Укажите идентификатор пользователя!';
        }

        $user = $this->em->getRepository(User::class)->find($id);
        if (!$user) {
            $errors['user'] = 'Пользователь с таким идентификатором не существует!';
        }

        if ($errors) { 
            $errors['result'] ='error';
            return $this->json($errors);
        }

        $this->em->remove($user);
        $this->em->flush();

        return $this->json([
            'result' => 'ok'
        ]);
    }

}
?>