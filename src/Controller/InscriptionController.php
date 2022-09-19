<?php

namespace App\Controller;

use DateTime;
use Twig\Environment;
use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use Twig\Loader\FilesystemLoader;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UtilisateurRepository;
use Symfony\Bridge\Twig\Mime\BodyRenderer;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class InscriptionController extends AbstractController
{

    private $verifyEmailHelper;
    private $mailer;
    
    #[Route('/inscription', name: 'app_inscription')]
    public function inscription(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $passwordHasher, VerifyEmailHelperInterface $helper, MailerInterface $mailer): Response
    {

        $this->verifyEmailHelper = $helper;
        $this->mailer = $mailer;

        $utilisateur = new Utilisateur();
        $form_utilisateur = $this->createForm(UtilisateurType::class, $utilisateur);

        $form_utilisateur->handleRequest($request);
        $error = $form_utilisateur->get('mdpUtilisateur')->getErrors();
        if($form_utilisateur->isSubmitted() && $form_utilisateur->isValid()){

            $data = $form_utilisateur->getData(); //pour récupérer les infos du formulaire

            $mdpEnClair = $data->getMdpUtilisateur(); //on récupère le mdp de l'utilisateur

            $mdpHashe = $passwordHasher->hashPassword($utilisateur, $mdpEnClair); //pour hasher le mdp en clair
            $utilisateur->setMdpUtilisateur($mdpHashe); //pour fixer le mdp hasché

            $utilisateur->setIpInscription($_SERVER['REMOTE_ADDR']);
            $utilisateur->setRoleUtilisateur('ROLE_TEMP'); // assigne à la creation le role temporaire dans la bdd

            $manager->persist($utilisateur); // si donnée persiste
            $manager->flush();  //envoi bbd

            $this->addFlash ('message1', "<script>Swal.fire({
                title: 'Génial !',
                text: 'Vous venez de recevoir un email. Merci de cliquer sur le lien pour confirmer votre inscription.',
                imageUrl: ('/assets/img/logoComplet.png'),
                imageWidth: 300,
                imageHeight: 200,
                imageAlt: 'logo Lebondon',
                })</script>");


            $signatureComponents = $this->verifyEmailHelper->generateSignature(
                'app_email_verifier',
                $utilisateur->getIdUtilisateur(),
                $utilisateur->getMailUtilisateur(),
                ['id' => $utilisateur->getIdUtilisateur()]
            );

            $email = new TemplatedEmail();
            $email->from('send@example.com');
            $email->to($utilisateur->getMailUtilisateur());
            $email->htmlTemplate('registration/confirmation_email.html.twig');
            $email->context(['signedUrl' => $signatureComponents->getSignedUrl()]);
            
            $this->mailer->send($email); 
            
            return $this->redirectToRoute('app_connexion');
        }
    {
        return $this->render('inscription/index.html.twig', [
            'controller_name' => 'InscriptionController',
            'form_utilisateur' => $form_utilisateur ->createView(),
            'error' => $error
        ]);
    }
    }
    
    #[Route('/verify/email', name: 'app_email_verifier')]
    public function verifyUserEmail(Request $request, EntityManagerInterface $manager, TranslatorInterface $translator, UtilisateurRepository $utilisateurRepository): Response
    {
        $id = $request->get('id'); 
        $utilisateur = $utilisateurRepository->findOneByIdUtilisateur($id);

        if (null === $id) {
            return $this->redirectToRoute('app_inscription');
        }

        if (null === $utilisateur) {
            return $this->redirectToRoute('app_inscription');
        }

        if (null !== $utilisateur) {
            $utilisateur->setIsVerified(1);
            $utilisateur->setRoleUtilisateur('ROLE_USER');
            $manager->persist($utilisateur);
            $manager->flush();
        }
        //dd($utilisateur);

        $this->addFlash ('message1', "<script>Swal.fire({
            title: 'Génial !',
            text: 'Votre inscription est confirmée! Vous pouvez désormais vous connecter.',
            imageUrl: ('/assets/img/logoComplet.png'),
            imageWidth: 300,
            imageHeight: 200,
            imageAlt: 'logo Lebondon',
            })</script>");

        return $this->redirectToRoute('app_connexion');
    }
}
