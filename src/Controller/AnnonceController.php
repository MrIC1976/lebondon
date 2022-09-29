<?php

namespace App\Controller;

use id;
use DateTime;
use App\Entity\Image;
use App\Entity\Annonce;
use App\Form\AnnonceType;
use Cocur\Slugify\Slugify;
use App\Entity\Reservation;
use App\Entity\DisponibiliteObjet;
use App\Repository\ImageRepository;
use App\Repository\VilleRepository;
use App\Repository\AnnonceRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UtilisateurRepository;
use App\Repository\SousCategorieRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\DisponibiliteObjetRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class AnnonceController extends AbstractController
{
    #[Route('annonce', name: 'app_annonce')]
    public function ajouterAnnonce(Request $request,EntityManagerInterface $manager, UserInterface $utilisateur, TokenStorageInterface $tokenStorage, VilleRepository $repoVille, DisponibiliteObjetRepository $dispoRepo): Response
    {
        $annonce = new Annonce();
        $form = $this->createForm (AnnonceType::class, $annonce); //création du formulaire
        $form->handleRequest($request); //permet de récupérer les données saisis dans le formulaire

        if ($form->isSubmitted() && $form->isValid()) {
            
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
                $manager->flush();

                /*$ticket= new Reservation();
                //$ticket->setDateReservation(new DateTime());
                $ticket->setIdAnnonce($annonce);
                
                //dd($ticket);
                $manager->persist($ticket);

                //dd($ticket);
                /*$nomDispo=new DisponibiliteObjet;
                $nomDispo->setIdReservation($ticket);
                //$nomDispo->$dispoRepo->getNomDisponibilite('Disponible');
                //$nomDispo->getNomDisponibilite()->getData('Disponible');
                dd($nomDispo);
                $manager->persist($nomDispo);
                //$manager->flush();
                //dd($ticket);
                
                //$disponibilite->setIdDisponibilite('1');
                
                //$dispoRepo->;*/

            }
            $manager->persist($annonce);
            $manager->flush(); 

            $this->addFlash('notice', "<script>Swal.fire({
                text: 'Ton annonce a été créée.',
                imageUrl: ('/assets/img/logoComplet.png'),
                imageWidth: 300,
                imageHeight: 200,
                imageAlt: 'logo Lebondon',
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
    #[Route('/dashboard/delete-annonce/{id}', name: 'delete_annonce')]
    public function deleteAnnonce($id, EntityManagerInterface $entityManager, AnnonceRepository $repoAnnonce, ImageRepository $repoImage, UserInterface $utilisateur,): Response
    {
    //pour supprimer l'image d'une annonce

    $imageAnnonce = $repoImage->findByIdAnnonce($id);
    $annonce = $repoAnnonce->findOneByIdAnnonce($id);
    //dd($imageAnnonce);
    if(!empty($imageAnnonce)){
        foreach ($imageAnnonce as $image) {
            $entityManager->remove($image);
            $entityManager->flush();
        }
    }
    //dd($annonce);
    $entityManager->remove($annonce);
    $entityManager->flush();

    $this->addFlash('message', "<script>Swal.fire({
                text: 'Ton annonce a bien été supprimée.',
                imageUrl: ('/assets/img/logoComplet.png'),
                imageWidth: 300,
                imageHeight: 200,
                imageAlt: 'logo Lebondon',
                })</script>");
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
        ]);
    }

    //pour afficher la vue de l'annonce dans mes annonces du dashboard
    #[Route('/update-annonce/{id}', name: 'update-article')]
    public function modificationAnnonce(UserInterface $utilisateur, Request $request, $id, AnnonceRepository $repoAnnonce, ImageRepository $repoImage, EntityManagerInterface $entityManager): Response
    {
        
        $annonce = $repoAnnonce->findOneByIdAnnonce($id);
        $form = $this->createForm (AnnonceType::class,$annonce);

        $form ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $annonce->setDateCreationAnnonce(new DateTime());

            if ($annonce) {
                $annonce->setDateCreationAnnonce(new DateTime());
            }

            $images = $form->get('images')->getData();

            foreach($images as $image){
            
            //on génère un nouveau nom de fichier
            $fichier = md5(uniqid()) . '.' . $image->guessExtension();

            //on copie le fichier dans le dossier uploads
            $image->move(
                $this->getParameter('images_directory'),  $fichier );

                $img = new Image();
                $img->setNomImage($fichier);
                $img->setIdAnnonce($article);
                $entityManager->persist($img);
                $entityManager->flush();

            } 
            
            $entityManager->persist($annonce);
            $entityManager->flush();

            
            $this->addFlash('message', "<script>Swal.fire({
                text: 'Ton annonce a bien été modifiée.',
                imageUrl: ('/assets/img/logoComplet.png'),
                imageWidth: 300,
                imageHeight: 200,
                imageAlt: 'logo Lebondon',
                })</script>");

            return $this->redirectToRoute("app_dashboard");
            }

            return $this->render('annonce/update-annonce.html.twig', [
            'annonce' => $annonce,
            'form' => $form->createView(),
            'pseudo' => $utilisateur->getPseudoUtilisateur(),
            'photoUtilisateur' => $utilisateur->getPhotoUtilisateur(),
            ]);
    }
}

