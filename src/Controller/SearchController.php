<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Form\SearchAnnonceType;
use App\Form\AnnonceType;
use App\Repository\AnnonceRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SearchController extends AbstractController
{
    #[Route('/recherche', name: 'app_search')]//on crée la route
    public function index(AnnonceRepository $annonceRepo, Request $request): Response//request permet de recupérer les info saisi dans le formulaire
    {
        $InformationAnnonce = $annonceRepo->infoAnnonce();//permet de recuperer un tableau avec toutes les infos des annonces (cf annonceRepository)
        //dd($InformationAnnonce);
        
        $formRecherche = $this->createForm (SearchAnnonceType::class); //création du formulaire
        $search = $formRecherche->handleRequest($request);

        //dd($formRecherche);
        //permet de récupérer les données saisis dans le formulaire
        if ( $formRecherche->isSubmitted() && $formRecherche->isValid()) {
            //on recherceh les annonces correspondant aux mots clés
            //$critere = $formRecherche->getData();
            //dd($critere);
            $annonce = $annonceRepo->rechercheAnnonce($search->get('mots')->getData());
            dd($annonce);
            //return $this->redirectToRoute('app_dashboard');
        }


        return $this->render('searchAnnonce/index.html.twig', [ //on lie le controller à la vue
            'controller_name' => 'SearchController',
            'infoAnnonce' => $InformationAnnonce,
            'annonces' => $annonce,
            'formRecherche' => $formRecherche->createView(),// on envoie à la vue
        ]);
    }
}
