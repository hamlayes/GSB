<?php

namespace App\Controller;

use App\Entity\FicheFrais;
use App\Entity\User;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InflationController extends AbstractController
{
    #[Route('/inflation', name: 'app_inflation')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $fichesfrais = $doctrine->getRepository(FicheFrais::class)->findByYear('2023');
        $montant = 0;
        foreach ($fichesfrais as $ficheFrais){
            $montant += $ficheFrais->getMontantValid();
        }
        $montantPrime = $montant*9.5/100;
        $user = $doctrine->getRepository(User::class)->findAll();
        $nbUser = count($user);
        $montantPrimeParUser = $montantPrime/$nbUser;

        return $this->render('inflation/index.html.twig', [
            'controller'=>'inflation',
            'montantPrime'=>$montantPrime,
            'montantUser'=>$montantPrimeParUser,

        ]);
    }

}
