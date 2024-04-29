<?php

namespace App\Controller;

use App\Entity\FicheFrais;
use App\Entity\FraisForfait;
use App\Form\FicheFraisType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VueFicheUserController extends AbstractController
{
    #[Route('/vue/fiche/user/{id}', name: 'app_vue_fiche_user')]
    public function index($id, EntityManagerInterface $entityManager): Response
    {
        $ficheFrais = $entityManager->getRepository(FicheFrais::class)->find($id);
        $fraisForfait = $entityManager->getRepository(FraisForfait::class)->findAll();

        if (!$ficheFrais) {
            throw $this->createNotFoundException('No fiche found for id '.$id);
        }

        return $this->render('vue_fiche_user/index.html.twig', [
            'controller_name' => 'VueFicheUserController',
            'ficheFrais' => $ficheFrais,
            'fraisForfait'=>$fraisForfait,
        ]);
    }
}