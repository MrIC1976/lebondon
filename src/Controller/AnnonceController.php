<?php

namespace App\Controller;

use id;
use DateTime;
use App\Entity\Image;
use App\Entity\Annonce;
use App\Form\AnnonceType;
use Cocur\Slugify\Slugify;
use App\Repository\ImageRepository;
use App\Repository\VilleRepository;
use App\Repository\AnnonceRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UtilisateurRepository;
use App\Repository\SousCategorieRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class AnnonceController extends AbstractController
{
    #[Route('annonce', name: 'app_annonce')]
    public function ajouterAnnonce(Request $request,EntityManagerInterface $manager, UserInterface $utilisateur, TokenStorageInterface $tokenStorage, VilleRepository $repoVille): Response
    {

        $annonce = new Annonce();

        $form = $this->createForm (AnnonceType::class, $annonce); //création du formulaire
        $form->handleRequest($request); //permet de récupérer les données saisis dans le formulaire

        if ($form->isSubmitted() && $form->isValid()) {
            
            //$utilisateur = $tokenStorage->getToken()->getUser(); //obtenir l'utilisateur connecté
            $slugify = new Slugify();
            $slug = $slugify->slugify($annonce->getTitreAnnonce()); 
            $annonce->setSlugAnnonce($slug);
            
            $annonce->setIdUtilisateur($utilisateur);
            
            $annonce->setDateCreation(new DateTime());

            $villeSaisieCodeInsee = $form->get('codeInsee')->getData(); //on a récupérer le code insee de la ville saisie ou code postal saisi
            $ville = $repoVille->findOneByCodeInsee($villeSaisieCodeInsee); //on va chercher les infos de cette ville dans notre base de données
            $annonce->setIdVille($ville);
            
            $manager->persist($annonce);
            $manager->flush();

            // On récupère les images transmises
            $images = $form->get('images')->getData();
            $manager->persist($annonce);
            $manager->flush();
            // On boucle sur les images
            foreach($images as $image){ //nombre d'image transmise non connu -> foreach
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()).'.'.$image->guessExtension();

                // On copie le fichier dans le dossier public image
                $image->move(
                    $this->getParameter('images_directory'),//images_directory se situe dans service.yaml parameters chemin de l'image
                    $fichier
                );
                // On crée l'image dans la base de données
                $img = new Image();
                $img->setNomImage($fichier);
                $img->setIdAnnonce($annonce);  
                $manager->persist($img);
            }
            
            $manager->persist($img);
            $manager->flush();

            $this->addFlash('notice', "<script>Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Bravo, votre annonce a bien été créée !',
                showConfirmButton: false,
                timer: 3000
                })</script>");

            return $this->redirectToRoute('app_dashboard'); //redirection vers dashboard  
        }



        return $this->render('annonce/index.html.twig', [ //voir methode php 'compact' pour alléger le code. Renderform inclut la methode createView

            'controller_name' => 'AnnonceController',
            'form' => $form->createView(),//création de la vue associée à notre formulaire, méthode renderForm supportée, elle appelle automatiquement la méthode createView
            'pseudo' => $utilisateur->getPseudoUtilisateur(),
            'photoUtilisateur' => $utilisateur->getPhotoUtilisateur(),
        ]);
    }

    //pour supprimer une annonce dans mes annonces du dashboard
    #[Route('/dashboard/delete-article/{id}', name: 'delete_annonce')]
    public function deleteAnnonce($id, EntityManagerInterface $entityManager, AnnonceRepository $repoAnnonce, ImageRepository $repoImage, UserInterface $utilisateur,): Response
    {

    //pour supprimer l'image d'une annonce
    $imageAnnonce = $repoImage->findOneByIdAnnonce($id);
    $entityManager->remove($imageAnnonce);
    $entityManager->flush();//supprime l'image de la bdd
    //pour supprimer l'annonce de mes annonces
    $annonce = $repoAnnonce->findOneByIdAnnonce($id);
    $entityManager->remove($annonce);
    $entityManager->flush();//supprime l'annonce de la bdd
    $this->addFlash(
        'message',
        "<script> Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Votre annonce a bien été supprimée!',
                showConfirmButton: false,
                timer: 2500
        })</script>"
    );
    return $this->redirectToRoute('app_dashboard');
}
    //pour afficher la vue de l'annonce dans mes annonces du dashboard
    #[Route('/view-annonce/{id}', name: 'view-annonce')]
    public function vueAnnnonce($id, AnnonceRepository $repoAnnonce, EntityManagerInterface $entityManager, ImageRepository $repoImage, UserInterface $utilisateur): Response
    {
        $viewAnnonce = $repoAnnonce->findOneByIdAnnonce($id);
        $imageAnnonce = $repoImage->findByIdAnnonce($id);

        return $this->render('annonce/view-annonce.html.twig', [
        'controller_name' => 'AnnonceController',
        "viewAnnonce" => $viewAnnonce,
        "imageAnnonce" => $imageAnnonce,
        //dd($viewAnnonce),
        //dd($imageAnnonce)
        ]);
    }

    
}
