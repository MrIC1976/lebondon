<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Form\AnnonceType;
use App\Form\SearchAnnonceType;
use App\Repository\ImageRepository;
use App\Repository\VilleRepository;
use App\Repository\AnnonceRepository;
use App\Repository\SousCategorieRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SearchController extends AbstractController
{
    #[Route('/recherche', name: 'app_search')]//on crée la route
    public function index(AnnonceRepository $annonceRepo, Request $request, VilleRepository $villeRepo, ImageRepository $imageRepo,SousCategorieRepository $sousCatRepo): Response//request permet de recupérer les info saisi dans le formulaire
    {
        
        $annonces = $annonceRepo-> findAll();
        //dd($annonces);
        //$informationAnnonce = $annonceRepo->infoAnnonceRecherche();//permet de recuperer un tableau avec toutes les infos des annonces (cf annonceRepository)
        //dd($informationAnnonce);
        $formRecherche = $this->createForm (SearchAnnonceType::class); //création du formulaire
        //dd($formRecherche);
        $search = $formRecherche->handleRequest($request);
        
        
        if ($formRecherche->isSubmitted() && $formRecherche->isValid()) { //si le formulaire est soumis
            //on recherche les annonces correspondant aux mots clés
            $villeRecherche=$formRecherche -> get('ville') -> getData(); //on recupere la ville saisi
            
            if ($villeRecherche != Null)
            {

            $distanceChoisi = $formRecherche -> get('distance') -> getData();
            //dd($distanceChoisi);
            
            $coordonneeVille=$villeRepo -> findCoordonneeByNomVille($villeRecherche); //on va chercher les coordonnée de cette ville saisi dans la BDD
            //dd($coordonneeVille);
            $latitude=$coordonneeVille[0]->getLatitude(); //dans le tableau $coordonneeVille on recupere la latitude
            //dd($latitudeVille);
            $longitude=$coordonneeVille[0]->getLongitude();//dans le tableau $coordonneeVille on recupere la longitude de la ville saisi par l'i=utilisateur
            $radEarth = 6371;  //rayon de la terre en km
            $rad = $distanceChoisi; 
            //first-cut bounding box (in degrees)
            $maxLat = $latitude + rad2deg($rad/$radEarth);
            $minLat = $latitude - rad2deg($rad/$radEarth);
//compensate for degrees longitude getting smaller with increasing latitude
            $maxLon = $longitude + rad2deg($rad/$radEarth/cos(deg2rad($latitude)));
            $minLon = $longitude - rad2deg($rad/$radEarth/cos(deg2rad($latitude)));
//dd($minLon);
            $maxLat=number_format((float)$maxLat, 6, '.', '');
            $minLat=number_format((float)$minLat, 6, '.', '');
            $maxLon=number_format((float)$maxLon, 6, '.', '');
            $minLon=number_format((float)$minLon, 6, '.', '');
            //dd($minLon);

            $annoncesAutour=$annonceRepo->findIdVilleSelonDistance2($minLon, $maxLon, $minLat, $maxLat);
            //dd($annoncesAutour);
            
//on obtient l'id des villes pour les annonces se situant à 10 miles de la ville dans la page de recherche
        }
        //dd($minLon);
            $annonces = $annonceRepo->rechercheAnnonce( 
                $search->get('mots')->getData(), 
                $search->get('categorie')->getData(),
                $search->get('idEtat')->getData(),
                $minLon, $maxLon, $minLat, $maxLat  
                //$annoncesAutour->getIdVille->getData()
                
            );
            //dd($annoncesAutour);
            $imag = $imageRepo -> obtenirImageParAnnonce();
            //dd($imag);
        }
        $imag = $imageRepo -> obtenirImageParAnnonce();
        
        
        //$image = $imageRepo-> findByIdAnnonce($annonces) ;
        
        //dd($image);
        /*if ( $formRecherche->isSubmitted()) {
        $nomCategorie = $search['idCategorie']->getData();
        $catSelectionnee=$nomCategorie->getIdCategorie();
        //dd($catSelectionnee);
        $sousCats=$sousCatRepo->findByIdCategorie($catSelectionnee);
        dd($sousCats);
        }*/
        /*foreach ($sousCats as $clef => $sousCat){
            dd($sousCats);
            foreach($sousCat as $caracteristique => $valeur){
                dd($caracteristique);
                return $nomSousCatSelectionnee= $valeur->getData("nomSousCategorie");
            }
            echo '<br>';
        }*/
        //dd($nomSousCatSelectionnee);

        return $this->render('searchAnnonce/index.html.twig', [ //on lie le controller à la vue
            'controller_name' => 'SearchController',
            //'infoAnnonce' => $informationAnnonce,
            'annonces' => $annonces,
            'formRecherche' => $formRecherche->createView(),// on envoie à la vue
            //'sousCategorie'=>$sousCats
            'image' => $imag
        ]);
    }
}