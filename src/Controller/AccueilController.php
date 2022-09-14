<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Repository\ImageRepository;
use App\Repository\VilleRepository;
use App\Controller\AccueilController;
use App\Repository\AnnonceRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UtilisateurRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccueilController extends AbstractController
{
    #[Route('/', name: 'app_accueil')]
    public function index(AnnonceRepository $annonceRepo, ImageRepository $imageRepo, UtilisateurRepository $utilisateurRepo, VilleRepository $villeRepo): Response
    {
    //   $user = $utilisateur->getIdUtilisateur();
        $annonces = $annonceRepo->findAll();
        $imageAnnonce = $imageRepo->obtenirImageParAnnonce();
        //dd( $imageAnnonce);
        $utilisateurAnnonce = $utilisateurRepo->findAll();
        $villeAnnonce = $villeRepo->obtenirVilleParAnnonce();
        $derniere = $annonceRepo->getHuitDernieresAnnonces();
        //dd($derniere);
        
        return $this->render('Accueil/index.html.twig', [
            'controller_name' => 'AccueilController',
            // 'pseudo' => $utilisateur->getPseudo(),
            'contenuAnnonce' => $annonces,
            'imageAnnonce' => $imageAnnonce,
            'utilisateurAnnonce' => $utilisateurAnnonce,
            'villeAnnonce' => $villeAnnonce,
            'derniereAnnonce' => $derniere,
        ]);
    }
}









