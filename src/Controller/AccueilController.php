<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Repository\ImageRepository;
use App\Repository\VilleRepository;
use App\Controller\AccueilController;
use App\Repository\AnnonceRepository;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UtilisateurRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccueilController extends AbstractController
{
    #[Route('/', name: 'app_accueil')]
    public function index(EntityManagerInterface $manager, AnnonceRepository $annonceRepo, ImageRepository $imageRepo, UtilisateurRepository $utilisateurRepo, VilleRepository $villeRepo, CategorieRepository $categorieRepo): Response
    {
        $annonces = $annonceRepo->findAll();
        $utilisateurAnnonce = $utilisateurRepo->findAll();
       
        $imageAnnonce = $imageRepo->obtenirImageParAnnonce();
        //dd( $imageAnnonce);
        
        $villeAnnonce = $villeRepo->obtenirVilleParAnnonce();
        $derniere = $annonceRepo->getHuitDernieresAnnonces();
        //dd($derniere);
        $categories = $categorieRepo->findAll();
        $photoUser = $utilisateurRepo->findAll();
        //dd($photoUser);
        //dd($derniere);
        
        return $this->render('Accueil/index.html.twig', [
            'controller_name' => 'AccueilController',
            // 'pseudo' => $utilisateur->getPseudo(),
            'contenuAnnonce' => $annonces,
            'imageAnnonce' => $imageAnnonce,
            'utilisateurAnnonce' => $utilisateurAnnonce,
            'villeAnnonce' => $villeAnnonce,
            'derniereAnnonce' => $derniere,
            'categorieAnnonce' => $categories,
            'photoProfil' => $photoUser,
        ]);









        
    }
}









