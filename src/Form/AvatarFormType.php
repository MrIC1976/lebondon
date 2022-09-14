<?php

namespace App\Form;

use App\Entity\Utilisateur;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AvatarFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            //->add('pseudoUtilisateur')
            //->add('nomUtilisateur')
            //->add('prenomUtilisateur')
            //->add('dateNaissance')
            //->add('genre')
            ->add('photoUtilisateur', FileType::class,[
                'label' => false , 
                'required' => true,// permet de rendre obligatoire l'ajout d'image
                'data_class' => null,
                'constraints' => [
                    new Image([
                        'maxSize' => '1M',
                    ])
                    ], 
                'attr' => ['class' => 'card-img-top'],
                ])

            ->add('save', SubmitType::class,[
                'attr'=> ['class'=> 'btn theme-bg rounded text-light'],
                'label' => 'Valider'
                ])

            //->add('mdpUtilisateur')
            //->add('mailUtilisateur')
            //->add('ipInscription')
            //->add('roleUtilisateur')
            //->add('resetToken')
            //->add('isVerified')
        ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}