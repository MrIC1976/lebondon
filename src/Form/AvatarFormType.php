<?php

namespace App\Form;

use App\Entity\Utilisateur;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AvatarFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pseudoUtilisateur', TextType::class, [
                'attr'=>['class' => 'form-control mb-1'], 
                'label'=>'Pseudo',
                'required'=>false,
                'disabled'=>true,
                ])

            ->add('nomUtilisateur', TextType::class, [
                'attr'=>['class' => 'form-control mb-1'], 
                'label'=>'Nom',
                'required'=>false,
                'disabled'=>true,
                ])
            
            ->add('prenomUtilisateur', TextType::class, [
                'attr'=>['class' => 'form-control mb-1'],
                'label'=>'Prénom',
                'required'=>false,
                'disabled'=>true,
                ])
            //->add('dateNaissance')
            //->add('genre')
            ->add('photoUtilisateur', FileType::class,[
                'label' => false , 
                'required' => false,// permet de rendre obligatoire l'ajout d'image
                'data_class' => null,
                /*'constraints' => [
                    new Image([
                        'maxSize' => '1M',
                    ])
                    ],*/ 
                'attr' => ['class' => 'card-img-top'],
                ])

            //->add('mdpUtilisateur')
            ->add('mailUtilisateur', EmailType::class, [
                'attr'=>['class' => 'form-control mb-1'], 
                'label'=>'Adresse mail',
                'disabled'=>true,
                
                ])
            //->add('ipInscription')
            //->add('roleUtilisateur')
            //->add('resetToken')
            //->add('isVerified')

            ->add('save', SubmitType::class,[
                'attr'=> ['class'=> 'btn theme-bg rounded text-light'],
                'label' => 'Valider'
                ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}