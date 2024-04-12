<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\FicheFrais;
use App\Entity\FraisForfait;
use App\Entity\LigneFraisForfait;
use App\Entity\LigneFraisHorsForfait;
use App\Entity\User;
use DateTime;
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

        foreach ($users as $user) {

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
            $nvUser->setEmail(strtolower($user['prenom'].".".$user['nom']."@gsb.fr"));

            $nvUser->setDateembauche(new DateTime( $user['dateEmbauche']));


            $entity->persist($nvUser);
            $entity->flush();
        }



        return $this->render('data_import/index.html.twig', [
            'controller_name' => 'DataImportController',
        ]);
    }

    #[Route('/import/fiche')]
    public function fiche(EntityManagerInterface $entity, UserPasswordHasherInterface $passwordHasher): Response
    {
        $usersjson = file_get_contents('./fichefrais.json');
        $fichefrais = json_decode($usersjson,true);

        foreach ($fichefrais as $fiche) {

            $nvFiche = new FicheFrais();

            switch ($fiche['idEtat']){
                case "CL":
                    $etat = $entity->getRepository(Etat::class)->find(1);
                    break;
                case "CR":
                    $etat = $entity->getRepository(Etat::class)->find(2);
                    break;
                case "RB":
                    $etat = $entity->getRepository(Etat::class)->find(3);
                    break;
                case "VA":
                    $etat = $entity->getRepository(Etat::class)->find(4);
                    break;
            }

            $nvFiche->setUser($entity->getRepository(User::class)->findOneBy(['oldId'=>$fiche['idVisiteur']]));
            $nvFiche->setMois($fiche['mois']);
            $nvFiche->setNbJustificatifs($fiche['nbJustificatifs']);
            $nvFiche->setMontantValid($fiche['montantValide']);
            $nvFiche->setDateModif(new DateTime($fiche['mois']));
            $nvFiche->setEtat($etat);



            $entity->persist($nvFiche);
            $entity->flush();
        }



        return $this->render('data_import/index.html.twig', [
            'controller_name' => 'DataImportController',
        ]);
    }

    #[Route('/import/ligneFraisForfais')]
    public function forfait(EntityManagerInterface $entity, UserPasswordHasherInterface $passwordHasher): Response
    {
        $forfaitjson = file_get_contents('./lignefraisforfait.json');
        $fraisforfait = json_decode($forfaitjson,true);

        foreach ($fraisforfait as $fforfait) {

            $nvForfait = new LigneFraisForfait();

            switch ($fforfait['idFraisForfait']){
                case "ETP":
                    $ff= $entity->getRepository(FraisForfait::class)->find(1);
                    break;
                case "KM":
                    $ff = $entity->getRepository(FraisForfait::class)->find(2);
                    break;
                case "NUI":
                    $ff = $entity->getRepository(FraisForfait::class)->find(3);
                    break;
                case "REP":
                    $ff = $entity->getRepository(FraisForfait::class)->find(4);
                    break;
            }

            $user = $entity->getRepository(User::class)->findOneBy(['oldId'=>$fforfait['idVisiteur']]);
            $fiche = $entity->getRepository(FicheFrais::class)->findOneBy(['mois' => $fforfait['mois'],'user' => $user]);
            $nvForfait->setFichesfrais($fiche);
            $nvForfait->setFraisForfait($ff);
            $nvForfait->setQuantite($fforfait['quantite']);




            $entity->persist($nvForfait);
            $entity->flush();
        }



        return $this->render('data_import/index.html.twig', [
            'controller_name' => 'DataImportController',
        ]);
    }


    #[Route('/import/ligneFraisHorsForfais')]
    public function horsforfait(EntityManagerInterface $entity, UserPasswordHasherInterface $passwordHasher): Response
    {
        $horsforfaitjson = file_get_contents('./lignefraishorsforfait.json');
        $fraishorsforfait = json_decode($horsforfaitjson,true);

        foreach ($fraishorsforfait as $fhforfait) {

            $nvHorsForfait = new LigneFraisHorsForfait();

            switch ($fhforfait['id']){
                case "ETP":
                    $fhf= $entity->getRepository(FraisForfait::class)->find(1);
                    break;
                case "KM":
                    $fhf = $entity->getRepository(FraisForfait::class)->find(2);
                    break;
                case "NUI":
                    $fhf = $entity->getRepository(FraisForfait::class)->find(3);
                    break;
                case "REP":
                    $fhf = $entity->getRepository(FraisForfait::class)->find(4);
                    break;
            }

            $user = $entity->getRepository(User::class)->findOneBy(['oldId'=>$fhforfait['idVisiteur']]);
            $fiche = $entity->getRepository(FicheFrais::class)->findOneBy(['mois' => $fhforfait['mois'],'user' => $user]);
            $nvHorsForfait->setFichesfrais($fiche);
            $nvHorsForfait->setLibelle($fhforfait['libelle']);
            $nvHorsForfait->setDate(new DateTime( $fhforfait['date']));
            $nvHorsForfait->setMontant($fhforfait['montant']);




            $entity->persist($nvHorsForfait);
            $entity->flush();
        }



        return $this->render('data_import/index.html.twig', [
            'controller_name' => 'DataImportController',
        ]);
    }
}
