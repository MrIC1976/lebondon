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
        $derniereAnnonce = $annonceRepo->getAnnonceByImage();
        $categorieParAnnonce = $annonceRepo->categorieSelonAnnonce();
       //dd($categorieParAnnonce);
        //$annoncesSelonCat=$categorieParAnnonce->findBy(array('nomCategorie'=>'Animaux'));
        //dd('$annoncesSelonCat');
        
        


        return $this->render('Accueil/index.html.twig', [
            'controller_name' => 'AccueilController',
            'derniereAnnonce' => $derniereAnnonce,
            'NbreAnnonceParCategorie' => $categorieParAnnonce
        ]);
    }
}









