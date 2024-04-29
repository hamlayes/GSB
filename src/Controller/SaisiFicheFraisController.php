<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\FicheFrais;
use App\Entity\FraisForfait;
use App\Entity\LigneFraisForfait;
use App\Entity\LigneFraisHorsForfait;
use App\Form\FicheFraisType;
use App\Form\SaisirLigneFraisForfaitType;
use App\Form\SaisirLigneFraisHorsForfaitType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SaisiFicheFraisController extends AbstractController
{
    #[Route('/saisifichefrais', name: 'app_saisi_fiche_frais')]
    public function index(Request $request, EntityManagerInterface $entity): Response
    {

        $ficheFrais = $entity->getRepository(FicheFrais::class)->findOneBy(['user' => $this->getUser(),
            'mois' => date('Ym')]);



        if ($ficheFrais == null) {

            $ficheFrais = new FicheFrais();
            $ficheFrais->setMois(date('Ym'));
            $ficheFrais->setNbJustificatifs(0);
            $ficheFrais->setMontantValid(0);
            $ficheFrais->setDateModif(new \DateTime('now'));
            $ficheFrais->setUser($this->getUser());
            $ficheFrais->setEtat($entity->getRepository(Etat::class)->find(2));


            $fraisForfaitRepository = $entity->getRepository(FraisForfait::class);
            $fraisForfaitEtape = $fraisForfaitRepository->find(1);
            $fraisForfaitKm = $fraisForfaitRepository->find(2);
            $fraisForfaitNuitee = $fraisForfaitRepository->find(3);
            $fraisForfaitRepas = $fraisForfaitRepository->find(4);

            $ligneFraisForfaitEtape = new LigneFraisForfait();
            $ligneFraisForfaitEtape->setFichesFrais($ficheFrais);
            $ligneFraisForfaitEtape->setFraisForfait($fraisForfaitEtape);
            $ligneFraisForfaitEtape->setQuantite(0);
            $entity->persist($ligneFraisForfaitEtape);

            $ligneFraisForfaitKm = new LigneFraisForfait();
            $ligneFraisForfaitKm->setFichesFrais($ficheFrais);
            $ligneFraisForfaitKm->setFraisForfait($fraisForfaitKm);
            $ligneFraisForfaitKm->setQuantite(0);
            $entity->persist($ligneFraisForfaitKm);

            $ligneFraisForfaitNuitee = new LigneFraisForfait();
            $ligneFraisForfaitNuitee->setFichesFrais($ficheFrais);
            $ligneFraisForfaitNuitee->setFraisForfait($fraisForfaitNuitee);
            $ligneFraisForfaitNuitee->setQuantite(0);
            $entity->persist($ligneFraisForfaitNuitee);

            $ligneFraisForfaitRepas = new LigneFraisForfait();
            $ligneFraisForfaitRepas->setFichesFrais($ficheFrais);
            $ligneFraisForfaitRepas->setFraisForfait($fraisForfaitRepas);
            $ligneFraisForfaitRepas->setQuantite(0);
            $entity->persist($ligneFraisForfaitRepas);

            $ficheFrais->addLigneFraisForfait($ligneFraisForfaitEtape);
            $ficheFrais->addLigneFraisForfait($ligneFraisForfaitKm);
            $ficheFrais->addLigneFraisForfait($ligneFraisForfaitNuitee);
            $ficheFrais->addLigneFraisForfait($ligneFraisForfaitRepas);

            $entity->persist($ficheFrais);
            $entity->flush();
        }
        foreach ($ficheFrais->getLigneFraisForfait() as $ligneFraisForfait) {
            if ($ligneFraisForfait->getFraisForfait()->getId() == 1) {
                $ligneFraisForfaitEtape = $ligneFraisForfait;
            }
            if ($ligneFraisForfait->getFraisForfait()->getId() == 2) {
                $ligneFraisForfaitKm = $ligneFraisForfait;
            }
            if ($ligneFraisForfait->getFraisForfait()->getId() == 3) {
                $ligneFraisForfaitNuitee = $ligneFraisForfait;
            }
            if ($ligneFraisForfait->getFraisForfait()->getId() == 4) {
                $ligneFraisForfaitRepas = $ligneFraisForfait;
            }
        }
        $formLigneFF = $this->createForm(SaisirLigneFraisForfaitType::class);
        $formLigneFF->handleRequest($request);



        if($formLigneFF->isSubmitted() && $formLigneFF->isValid()) {


            $ligneFraisForfaitEtape->setQuantite($formLigneFF->get('etape')->getData());
            $ligneFraisForfaitKm->setQuantite($formLigneFF->get('nombreKm')->getData());
            $ligneFraisForfaitNuitee->setQuantite($formLigneFF->get('nuitee')->getData());
            $ligneFraisForfaitRepas->setQuantite($formLigneFF->get('repas')->getData());

            $entity->persist($ligneFraisForfaitEtape);
            $entity->persist($ligneFraisForfaitKm);
            $entity->persist($ligneFraisForfaitNuitee);
            $entity->persist($ligneFraisForfaitRepas);
            $entity->flush();

            return $this->redirectToRoute('app_saisi_fiche_frais');
        }


        $formLigneFF->get('etape')->setData($ligneFraisForfaitEtape->getQuantite());
        $formLigneFF->get('nombreKm')->setData($ligneFraisForfaitKm->getQuantite());
        $formLigneFF->get('nuitee')->setData($ligneFraisForfaitNuitee->getQuantite());
        $formLigneFF->get('repas')->setData($ligneFraisForfaitRepas->getQuantite());


        $formLigneFHF = $this->createForm(SaisirLigneFraisHorsForfaitType::class);
        $formLigneFHF->handleRequest($request);

        if ($formLigneFHF->isSubmitted() && $formLigneFHF->isValid()) {

            $ligneFraisHorsForfait = new LigneFraisHorsForfait();
            $ligneFraisHorsForfait->setFichesFrais($ficheFrais);

            // Récupération des valeurs du formulaire
            $ligneFraisHorsForfaitDate = $formLigneFHF->get('date')->getData();
            $ligneFraisHorsForfaitLibelle = $formLigneFHF->get('libelle')->getData();
            $ligneFraisHorsForfaitMontant = $formLigneFHF->get('montant')->getData();

            // Attribution des valeurs récupérées
            $ligneFraisHorsForfait->setDate($ligneFraisHorsForfaitDate);
            $ligneFraisHorsForfait->setLibelle($ligneFraisHorsForfaitLibelle);
            $ligneFraisHorsForfait->setMontant($ligneFraisHorsForfaitMontant);


            $entity->persist($ligneFraisHorsForfait);
            $entity->flush();

            // Redirection après l'envoi du formulaire
            return $this->redirectToRoute('app_saisi_fiche_frais');
        }

        $affichageLigneFraisHorsForfait = $entity->getRepository(LigneFraisHorsForfait::class)->findBy(['fichesFrais' => $ficheFrais]);




        return $this->render('saisi_fiche_frais/index.html.twig', [
            'formLFF' => $formLigneFF->createView(),
            'formFHF' => $formLigneFHF->createView(),
            'fraisHorsForfait' => $affichageLigneFraisHorsForfait,
        ]);
    }

    public function delete(Request $request, LigneFraisHorsForfait $fraisHorsForfait): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($fraisHorsForfait);
        $entityManager->flush();

        return $this->redirectToRoute('app_saisi_fiche_frais');
    }
}
