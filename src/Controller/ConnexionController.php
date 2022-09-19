<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\form\UtilisateurType;
use App\Service\SendMailService;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UtilisateurRepository;
use App\Form\ResetPasswordRequestFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

    #[Route('/oubli-pass', name:'forgotten_password')]
    public function forgottenPassword(
        Request $request,
        UtilisateurRepository $usersRepository,
        TokenGeneratorInterface $tokenGenerator,
        EntityManagerInterface $entityManager,
        SendMailService $mail
    ): Response
    {
        $form = $this->createForm(ResetPasswordRequestFormType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            //On va chercher l'utilisateur par son email
            $user = $UtimisateurRepository->findOneByMailUtilisateur($form->get('mailUtilisateur')->getData());

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
                    'no-reply@lebondon.org',
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
            $this->addFlash('success', "<script>Swal.fire({
                title: 'Attention.',
                text: 'Un problème est survenu.',
                imageUrl: ('/assets/img/logoComplet.png'),
                imageWidth: 300,
                imageHeight: 200,
                imageAlt: 'logo Lebondon',
                })</script>");
            return $this->redirectToRoute('app_connexion');
        }

        return $this->render('security/reset_password_request.html.twig', [
            'requestPassForm' => $form->createView()
        ]);
    }


    #[Route('/oubli-pass/{token}', name:'reset_pass')]
    public function resetPass(
        string $token,
        Request $request,
        UsersRepository $usersRepository,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): Response
    {
        // On vérifie si on a ce token dans la base
        $user = $usersRepository->findOneByResetToken($token);
        
        // On vérifie si l'utilisateur existe

        if($user){
            $form = $this->createForm(ResetPasswordFormType::class);

            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){
                // On efface le token
                $user->setResetToken('');
                
                
// On enregistre le nouveau mot de passe en le hashant
                $user->setPassword(
                    $passwordHasher->hashPassword(
                        $user,
                        $form->get('password')->getData()
                    )
                );
                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash('success', 'Mot de passe changé avec succès');
                return $this->redirectToRoute('app_connexion');
            }

            return $this->render('security/reset_password_request.html.twig', [
                'passForm' => $form->createView()
            ]);
        }
        
        // Si le token est invalide on redirige vers le login
        $this->addFlash('danger', 'Jeton invalide');
        return $this->redirectToRoute('app_connexion');

    }
    #[Route('/deconnexion', name: 'app_logout')]
    public function deconnexion()
    {

    }
}
