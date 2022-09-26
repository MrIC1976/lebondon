<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\form\UtilisateurType;
use App\Service\SendMailService;
use App\Form\ResetPasswordFormType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UtilisateurRepository;
use App\Form\ResetPasswordRequestFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class ConnexionController extends AbstractController
{
    #[Route('/connexion', name: 'app_connexion')]
    public function index(): Response
    {
        return $this->render('/connexion/index.html.twig', [
            'controller_name' => 'ConnexionController',
            
        ]);
    }

#[Route('/deconnexion', name: 'app_logout')]
public function deconnexion()
{
}

#[Route('/oubli-pass', name:'forgotten_password')]
public function forgottenPassword(
    Request $request,
    UtilisateurRepository $UtilisateurRepo,
    TokenGeneratorInterface $tokenGenerator,
    EntityManagerInterface $entityManager,
    SendMailService $mail
): Response
{
    $form = $this->createForm(ResetPasswordRequestFormType::class);

    $form->handleRequest($request);


    if($form->isSubmitted() && $form->isValid()){
        //On va chercher l'utilisateur par son email
        $user = $UtilisateurRepo->findOneByMailUtilisateur($form->get('mailUtilisateur')->getData());
        // On vérifie si on a un utilisateur
        if($user){
            // On génère un token de réinitialisation
            $token = $tokenGenerator->generateToken();
            $user->setResetToken($token);
            $entityManager->persist($user);
            $entityManager->flush();

            // On génère un lien de réinitialisation du mot de passe
            $url = $this->generateUrl('reset_pass', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);
            
            // On crée les données du mail
            $context = compact('url', 'user');

            // Envoi du mail
            $mail->send(
                'no-reply@lebondon.fr',
                $user->getMailUtilisateur(),
                'Réinitialisation de mot de passe',
                'password_reset',
                $context
            );

                $this->addFlash('success', "<script>Swal.fire({
                    title: 'Email envoyé avec succès.',
                    text: 'Vous allez le reçevoir dans quelques instants sur votre boite mail.',
                    imageUrl: ('/assets/img/logoComplet.png'),
                    imageWidth: 300,
                    imageHeight: 200,
                    imageAlt: 'logo Lebondon',
                    })</script>");
                return $this->redirectToRoute('app_connexion');
        }
        // $user est null
        $this->addFlash(
            'message',
            "<script> Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'l'adresse mail n\'a pas été reconnue',
                    showConfirmButton: true,
            })</script>"
        );
        
        return $this->redirectToRoute('app_connexion');
    }

    return $this->render('accueil/reset_password_request.html.twig', [
        'requestPassForm' => $form->createView()
    ]);
}

#[Route('/oubli-pass/{token}', name:'reset_pass')]
public function resetPass(
    string $token,
    Request $request,
    UtilisateurRepository $UtilisateurRepository,
    EntityManagerInterface $entityManager,
    UserPasswordHasherInterface $passwordHasher
): Response
{
    // On vérifie si on a ce token dans la base
    $user = $UtilisateurRepository->findOneByResetToken($token);
    
    if($user){
        $form = $this->createForm(ResetPasswordFormType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            // On efface le token
            $user->setResetToken('');
            $user->setMdpUtilisateur(
                $passwordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash(
                'message',
                "<script> Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: 'le mot de passe a été modifié avec succès',
                        showConfirmButton: true,
                        
                })</script>"
            );
            return $this->redirectToRoute('app_connexion');
        }
//dd($user);
        return $this->render('accueil/reset_password.html.twig', [
            'passForm' => $form->createView(),
        ]);
    }
    $this->addFlash('danger', 'Jeton invalide');
    return $this->redirectToRoute('app_connexion');
}



}
