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
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



class AccueilController extends AbstractController
{
    #[Route('/', name: 'app_accueil')]
    public function index(request $request, EntityManagerInterface $manager, AnnonceRepository $annonceRepo, ImageRepository $imageRepo, UtilisateurRepository $utilisateurRepo, VilleRepository $villeRepo, CategorieRepository $categorieRepo, MailerInterface $mailer): Response
    {
        $annonces = $annonceRepo->findAll();
        $derniereAnnonce = $annonceRepo->getHuitDernieresAnnonces();
        $categorieParAnnonce = $annonceRepo->categorieSelonAnnonce();

        //dd($derniereAnnonce);
        //dd($categorieParAnnonce);
        //$annoncesSelonCat=$categorieParAnnonce->findBy(array('nomCategorie'=>'Animaux'));
        //dd('$annoncesSelonCat');
        
        $form = $this->createForm(AnnonceContactType::class);
        $contact = $form->handleRequest($request);

        if($form->isSubmitted() &&  $form->isValid()){
            //on crée le mail
            $email = (new TemplatedEmail())
                ->from($contact->get('email')->getData())
                ->to($annonces[0]->getIdUtilisateur()->getMailUtilisateur())
                ->subject('Contact au sujet de votre annonce "' . $annonces[0]->getTitreAnnonce() . '"')
                ->htmlTemplate('emails/contact_annonce.html.twig')
                ->context([
                    'annonce' => $annonces,
                    'mail' => $contact->get('email')->getData(),
                    'message' => $contact->get('message')->getData()
                ]);
            //on envoie le mail
                $mailer->send($email);

            //on confirme et on redirige
                $this->addFlash('message', 'Votre email a bien été envoyé');
                return $this->redirectToRoute('app_accueil');
        }


        return $this->render('Accueil/index.html.twig', [
            'controller_name' => 'AccueilController',
            'derniereAnnonce' => $derniereAnnonce,
            'NbreAnnonceParCategorie' => $categorieParAnnonce,
            'annonce' => $annonces,
            'form' => $form->createView()
        ]);
    }
}









