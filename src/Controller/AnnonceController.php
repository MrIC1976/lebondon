<?php

namespace App\Controller;

use DateTime;
use App\Entity\Image;
use App\Entity\Annonce;
use App\Form\AnnonceType;
use Cocur\Slugify\Slugify;
use App\Repository\VilleRepository;
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

            $utilisateur = $tokenStorage->getToken()->getUser(); //obtenir l'utilisateur connecté
            
            $slugify = new Slugify();
            $slug = $slugify->slugify($annonce->getTitreAnnonce()); 
            $annonce->setSlugAnnonce($slug);
            
            $annonce->setIdUtilisateur($utilisateur);
            
            $annonce->setDateCreation(new DateTime());

            $villeSaisieCodeInsee = $form->get('codeInsee')->getData(); //on a récupérer le code insee de la ville saisi ou code postal saisi
            $ville = $repoVille->findOneByCodeInsee($villeSaisieCodeInsee); //on va chercher les info de cette ville dans notre base de donnée
            //dd($villeSaisieCodeInsee);
            //if $villeSaisieCodeInsee
            //$idVilleSaisie = ($ville->getIdVille());//on récupére seulement l'idVille de la ville saisi par l'utilisateur 
            //il faut maintenant set($idVilleSaisie) dans $annonce;
            //dd('')
            $annonce->setIdVille($ville);
            
            $manager->persist($annonce);
            $manager->flush();
            
            //si code insee = 75056 on prends le code postal plutot (on cast)

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
                title: 'Bravo, votre annonce a bien été créé !',
                showConfirmButton: false,
                timer: 2500
                })</script>");

            return $this->redirectToRoute('app_dashboard'); //redirection vers dashboard  
        }



        return $this->render('annonce/index.html.twig', [ //voir methode php 'compact' pour alléger le code. Rendreform inclut la methode createView

            'controller_name' => 'AnnonceController',
            'form' => $form->createView(),//création de la vue associé à notre formulaire, méthode renderForm supportée, elle appelle automatiquement la méthode createView
            'pseudo' => $utilisateur->getPseudoUtilisateur(),
            'photoUtilisateur' => $utilisateur->getPhotoUtilisateur(),
        ]);
    }
    #[Route('/annonce/view_annonce', name: 'view_annonce')]
    public function vueAnnnonce(UserInterface $utilisateur): Response
    {
        return $this->render('annonce/view_annonce.html.twig', [
            
        ]);
    }
}