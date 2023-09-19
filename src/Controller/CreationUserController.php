<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use function Symfony\Component\Clock\now;

class CreationUserController extends AbstractController
{
    #[Route('/createuser', name: 'app_register')]
    public function index(EntityManagerInterface $entity, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $password = "1234";
        $user->setEmail("hamlaoui1@test");
        $user->setCp("74000");
        $user->setDateEmbauche(new \DateTime('now'));
        $user->setNom("inzoudine");
        $user->setPrenom("hamlaoui");
        $user->setAdresse("chez ma maman");
        $user->setVille("Annecy");
        $user->setRoles(['ROLE_SUPER_ADMIN']);

        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $password
        );
        $user->setPassword($hashedPassword);

        $entity->persist($user);
        $entity->flush();
        return $this->render('creation_user/index.html.twig', [
            'controller_name' => 'CreationUserController',
        ]);
    }
}
