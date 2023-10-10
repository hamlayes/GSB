<?php

namespace App\Controller;

use App\Entity\FicheFrais;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class DataImportController extends AbstractController
{
    #[Route('/data/import', name: 'app_data_import')]
    public function index(EntityManagerInterface $entity, UserPasswordHasherInterface $passwordHasher): Response
    {
        $usersjson = file_get_contents('./visiteur.json');
        $users = json_decode($usersjson,true);
        var_dump($users);
        $newFF = new FicheFrais();

        foreach ($users as $user) {
            dd($user);
            $nvUser = new User();

            $nvUser->setOldId($user['id']);
            $nvUser->setNom($user['nom']);
            $nvUser->setPrenom($user['prenom']);
            $hashedPassword = $passwordHasher->hashPassword(
                $nvUser,
                $user['mdp']
            );
            $nvUser->setPassword($hashedPassword);
            $nvUser->setAdresse($user['adresse']);
            $nvUser->setCp($user['cp']);
            $nvUser->setVille($user['ville']);
            $nvUser->setDateEmbauche($user['dateEmbauche']);


            $entityManager->getRepository(User::class)->findOneBy($usersjson['oldId']);
            $newFF->getUser($nvUser);

            $entity->persist($user);
            $entity->flush();
        }

        return $this->render('data_import/index.html.twig', [
            'controller_name' => 'DataImportController',
        ]);
    }
}
