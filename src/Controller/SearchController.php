<?php

namespace App\Controller;

use App\Repository\AnnonceRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SearchController extends AbstractController
{
    #[Route('/recherche', name: 'app_search')]
    public function index(AnnonceRepository $annonceRepo): Response
    {
        $InformationAnnonce = $annonceRepo->infoAnnonce();
        //dd($InformationAnnonce);



        return $this->render('search/index.html.twig', [
            'controller_name' => 'SearchController',
            'infoAnnonce' => $InformationAnnonce
        ]);
    }
}
