<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class ProfilFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomUtilisateur', TextType::class, [
                    'attr' => ['class' => 'form-control mb-1'], 
                    'label'=> 'Nom'
                    ])
            ->add('prenomUtilisateur', TextType::class, [
                    'attr' => ['class' => 'form-control mb-1'],
                    'label'=> 'Prénom'
                    ])
            ->add('pseudoUtilisateur', TextType::class, [
                    'attr' => ['class' => 'form-control mb-1'], 
                    'label'=> 'Pseudo'
                    ])
            ->add('mailUtilisateur', EmailType::class, [
                'attr' => ['class' => 'form-control mb-1'], 
                'label'=> false,
                'disabled'=> true,
                ])
            //->add('dateNaissance')
            //->add('genre')
            //->add('photoUtilisateur')
            //->add('mdpUtilisateur')
            //->add('mailUtilisateur')
            //->add('ipInscription')
            //->add('roleUtilisateur')
            //->add('resetToken')
            //->add('isVerified')
            ->add('save', SubmitType::class, [
                    'label'=>'Modifier',
                    'attr' => ['class' => 'btn btn-md full-width bg-sky text-light rounded ft-medium mt-4']
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
