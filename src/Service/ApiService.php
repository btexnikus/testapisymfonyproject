<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use App\Entity\User;

class ApiService
{

    /**
     * @var EntityManagerInterface
     */
    protected EntityManagerInterface $em;


    /**
     * @param EntityManagerInterface $entityManager
     * @param ContainerInterface $container
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @param $email
     * @param $password
     * @return User
     */
    public function createUserObj($email, $password = ''): User
    {
        $user = (new User())
            ->setEmail($email ?: null)
            ->setRoles(['ROLE_USER']);

        if ($password) {
            $user->setPassword($password);
        }

        return $user;
    }

}