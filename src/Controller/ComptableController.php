<?php

namespace App\Controller;

use App\Entity\FicheFrais;
use App\Entity\FraisForfait;
use App\Form\AllUserType;
use App\Form\FicheFraisType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;


class ComptableController extends AbstractController
{
    #[Route('/comptable', name: 'app_comptable')]
    public function index(Request $request, EntityManagerInterface $entity): Response
    {
        //$User = $entity->getRepository(User::class)->findAll();
        $fichesFraisForm = $this->createForm(AllUserType::class);
        $fichesFraisForm->handleRequest($request);

        $fichesFrais = null;
        if ($fichesFraisForm->isSubmitted() && $fichesFraisForm->isValid()) {
            $selectedUser = $fichesFraisForm->get('user')->getData();
            $fichesFrais = $entity->getRepository(FicheFrais::class)->findBy(['user' => $selectedUser]);
        }

        return $this->render('comptable/index.html.twig', [
            'controller_name' => 'ComptableController',
            'fichesFraisForm' => $fichesFraisForm,
            'fichesFrais' => $fichesFrais,
        ]);
    }


}