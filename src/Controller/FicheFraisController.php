<?php

namespace App\Controller;

use App\Entity\FicheFrais;
use App\Form\FicheFraisType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class FicheFraisController extends AbstractController
{
    #[Route('/mesfichesfrais', name: 'app_fiche_frais')]
    public function index(Request $request, EntityManagerInterface $entity): Response
    {
        $mesFichesFrais = $entity->getRepository(FicheFrais::class)->findBy(['user' => $this->getUser()]);
        $form = $this->createForm(FicheFraisType::class, $mesFichesFrais);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $entity->persist();
            $entity->flush();

        }


        return $this->render('fiche_frais/index.html.twig', [
            'form' => $form,

        ]);
    }
}
