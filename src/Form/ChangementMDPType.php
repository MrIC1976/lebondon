<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangementMDPType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('old_password', PasswordType::class,[
                'label'=>'Mot de passe actuel',
                'mapped'=>false,
                'attr'=>[
                'placeholder'=>'Veuillez saisir votre mot de passe actuel',
                'class' =>'form-control mt-1 mb-3','label' => false,
                ]
            ])
            ->add('new_password', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped'=>false,
                'invalid_message' => "Le mot de passe et la confirmation doivent être identiques.",
                'label' => "Votre nouveau mot de passe",
                'required' => true,
                'first_options' => ['label' => "Mon nouveau de passe",
                    'attr'=>[
                        'placeholder'=>'Merci de saisir votre nouveau mot de passe',
                        'class' =>'form-control mt-1 mb-3','label' => false,
                    ]],
                'second_options' => ['label' => "Confirmation de votre nouveau mot de passe",
                    'attr'=>[
                        'placeholder'=> 'Merci de confirmer votre nouveau mot de passe',
                        'class' =>'form-control mt-1 mb-5','label' => false,
                    ]]
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Changement du mot de passe",
                'attr'=>[
                'class' => 'btn btn-sm ft-medium text-light rounded theme-bg',
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}