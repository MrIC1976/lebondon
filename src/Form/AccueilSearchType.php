<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\EtatObjet;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AccueilSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('mots', SearchType::class, [
            'label'=>false,
            'required'=>false,
            'attr'=>[
                'class' => 'form-control radius font-size:18px',
                'placeholder' => 'Objet recherché'
            ]
        ])

        ->add('categorie', EntityType::class, [
            'class' => Categorie::class,
            'choice_label' => "nomCategorie",
            'placeholder' => 'Choisissez la catégorie',            
            // 'expanded' => true,
            'label' => false,
            'attr'=> ['class'=> 'form-select'],
            'required' => false,
            ])
            
        ->add('idEtat', HiddenType::class,[
            //'class' => EtatObjet::class,
            //'placeholder' => 'Etat de l\'objet',
            //'choice_label' => 'nomEtat', 
            'label' => false,
            //'multiple' => true,
            'required' => false,
            'attr'=> ['class'=> 'form-select'],
            ])
            
        ->add('adresse', HiddenType::class,[
            'label' => false,
            'required' => false,
            'help' => 'rue ...',
             //'placeholder' => 'Entrez le numéro et le nom de rue ici !',
            'attr' => ['class' => 'form-control rounded']
            ])
        
        ->add('ville', TextType::class,[
            //'class' => Ville::class,
            'mapped' => false,
            //'choice_label' => 'nomVille',
            'label' => false,
            'required' => false,
            'attr' => [
                'class' => 'form-control rounded font-size:18px',
                'placeholder' => 'Saisir la ville'            
            ]
            ])
        
        ->add('cp', HiddenType::class,[
            //'class' => Ville::class,                'mapped' => false,
           //'choice_label' => 'nomVille',
            'label' => false,
            'required' => false,                
            'attr' => ['class' => 'form-control rounded']
            ])
                
        ->add('codeInsee', HiddenType::class,[
            'mapped' => false,
            'label' => false,
            'required' => false,
            'attr' => ['class' => 'form-control rounded']
            ])

        ->add('distance', RangeType::class,[
                'mapped' => false,
                'label' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form-control rounded',
                    'min' => 5,
                    'max' => 200  
                ],
                ])
               
        ->add('departement', HiddenType::class,[
            //'class' => Departement::class,
            //'choice_label' => 'nomDepartement',
            'label' => false,
            'help' => 'Nord',
            'required' => false,
            'attr' => ['class' => 'form-control rounded']
            ])
        
        ->add('idDisponibilite', HiddenType::class,[
            //'class' => DisponibiliteObjet::class,
            //'placeholder' => 'Disponibilité de l\'objet',
            //'choice_label' => 'nomDisponibilite', 
            'label' => false,
            'attr'=> ['class'=> 'form-select text-center mt-1'],
            ])        

        ->add('rechercher', SubmitType::class,[
            'attr'=> ['class'=> 'btn full-width theme-bg text-white size font-size:18px'],
            'label' => 'Rechercher'
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
