<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\AvatarFormType;
use App\Form\ProfilFormType;
use App\Repository\ImageRepository;
use App\Repository\AnnonceRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UtilisateurRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]

    public function index(UserInterface $utilisateur, AnnonceRepository $repoAnnonce): Response

    {
        $style = 'active';
        $annonces = $repoAnnonce->findByIdUtilisateur($utilisateur);

        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
            'pseudo' => $utilisateur->getPseudoUtilisateur(),
            'contenuAnnonce' => $annonces,
            'style' => $style,
            'photoUtilisateur' => $utilisateur->getPhotoUtilisateur(),
        ]);
    }

    #[Route('/mon_profil', name: 'app_myProfile')]
    public function pageProfil(Request $request, UtilisateurRepository $repoUser,UserInterface $utilisateur, EntityManagerInterface $manager, TokenStorageInterface $tokenStorage): Response
    {

        $style8 = 'active';
        $formAvatar = $this->createForm (AvatarFormType::class, $utilisateur); //création du formulaire
        $formAvatar->handleRequest($request);



        /*if ($formProfil->isSubmitted() && $formProfil->isValid()) {

            dd($profil);
            $manager->persist($utilisateur);
            $manager->flush();
            $this->addFlash('notice3', "<script>Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Bravo, Avatar modifié !',
                showConfirmButton: false,
                timer: 2500
                })</script>");
        
            return $this->redirectToRoute('app_dashboard'); 
        }    */


        if ($formAvatar->isSubmitted() && $formAvatar->isValid()) {
        
        $avatar = $formAvatar->get('photoUtilisateur')->getData();  // On récupère l'image transmise
        $fichier = md5(uniqid()).'.'.$avatar->guessExtension(); // On génère un nouveau nom de fichier
         // On copie le fichier dans le dossier public image
        $avatar->move(
        $this->getParameter('images_directory'),//images_directory se situe dans service.yaml parameters chemin de l'image
        $fichier //il s'agit ici du nom de l'image
        );
        $utilisateur->setPhotoUtilisateur($fichier);
        $manager->persist($utilisateur );
        $manager->flush();

        $this->addFlash('notice3', "<script>Swal.fire({
            position: 'center',
            icon: 'success',
            title: 'Bravo, Avatar modifié !',
            showConfirmButton: false,
            timer: 2500
            })</script>");

        return $this->redirectToRoute('app_dashboard'); 

        }
        return $this->render('dashboard/profilUtilisateur.html.twig', [
            'controller_name' => 'DashboardController',
            'formAvatar' => $formAvatar->createView(),
            'pseudo' => $utilisateur->getPseudoUtilisateur(),
            'prenom' => $utilisateur->getPrenomUtilisateur(),
            'nom' => $utilisateur->getNomUtilisateur(),
            'mail' => $utilisateur->getMailUtilisateur(),
            'photoUtilisateur' => $utilisateur->getPhotoUtilisateur(),
            'style8' => $style8
        ]);
    }

    #[Route('/mes_annonces', name: 'app_mesAnnonces')]
    public function mesAnnonces(UserInterface $utilisateur, AnnonceRepository $repoAnnonce, ImageRepository $repoImage): Response
    {
        $style2 = 'active';
        $annonces = $repoAnnonce->findByIdUtilisateur($utilisateur);
        //dd($annonces);
        $imageAnnonces = $repoImage->findByIdAnnonce($annonces);
       // dd($imageAnnonces);
        return $this->render('dashboard/mesAnnonces.html.twig', [
            'controller_name' => 'DashboardController',
            'pseudo' => $utilisateur->getPseudoUtilisateur(),
            'prenom' => $utilisateur->getPrenomUtilisateur(),
            'nom' => $utilisateur->getNomUtilisateur(),
            'contenuAnnonce' => $annonces,     
            'imageAnnonce' => $imageAnnonces,
            'style2' => $style2,
            'photoUtilisateur' => $utilisateur->getPhotoUtilisateur(),
        ]);
    }
}