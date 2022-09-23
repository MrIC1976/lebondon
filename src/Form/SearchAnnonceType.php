<?php

namespace App\Form;

use App\Entity\Ville;
use App\Entity\Annonce;
use App\Entity\Categorie;
use App\Entity\EtatObjet;
use App\Entity\Departement;
use App\Entity\SousCategorie;
use Symfony\Component\Form\Forms;
use App\Entity\DisponibiliteObjet;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use App\Repository\CategorieRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use App\Repository\SousCategorieRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationExtension;


class SearchAnnonceType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('mots', SearchType::class, [
                'label'=>false,
                'attr'=>[
                    'class' => 'form-control rounded',
                    'placeholder' => 'Entrez un ou plusieurs mots-clés!'
                ]
            ])
            //->add('titreAnnonce')
            //->add('slugAnnonce')
            //->add('descriptionAnnonce')
            /*->add('titreAnnonce', TextType::class,[
                //'mapped' => false,
                'label' => false,
                'required' => false,
                //'placeholder' => 'Entrez le numéro et le nom de rue ici !',
                'attr' => ['class' => 'form-control rounded']
                ])*/
            ->add('adresse', TextType::class,[
                'label' => false,
                'required' => false,
                'help' => 'rue ...',
                //'placeholder' => 'Entrez le numéro et le nom de rue ici !',
                'attr' => ['class' => 'form-control rounded']
                ])
            ->add('ville', TextType::class,[
                //'class' => Ville::class,
                //'mapped' => false,
                //'choice_label' => 'nomVille',
                'label' => false,
                'required' => false,
                'help' => 'Paris',
                'attr' => ['class' => 'form-control rounded']
                ])
            ->add('departement', EntityType::class,[
                'class' => Departement::class,
                //'mapped' => false,
                'choice_label' => 'nomDepartement',
                'label' => false,
                'help' => 'Nord',
                'required' => false,
                'attr' => ['class' => 'form-control rounded']
                ])

           // ->add('dateCreationAnnonce')
            //->add('idUtilisateur')
            //->add('idVille')
            /*->add('idEtat', ChoiceType::class,[
                'choices' => [
                    array_combine(EtatObjet::class->findbynomEtat, EtatObjet::class)
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '6' => '6',
                    '7' => '7'
                    'Neuf' => 'Neuf',
                    'Comme neuf' => 'Comme neuf',
                    'Bon état' => 'Bon état',
                    'Etat correct' => 'Etat correct',
                    'Abimé' => 'Abimé',
                    'Très abimé' => 'Très abimé'

                ],
                'label' => false,
                'multiple' => true,
                'attr'=> ['class'=> 'form-select text-center mt-1'],
                ])
                */
                ->add('idEtat', EntityType::class,[
                    'class' => EtatObjet::class,
                    'placeholder' => 'Etat de l\'objet',
                    'choice_label' => 'nomEtat', 
                    'label' => false,
                    //'multiple' => true,
                    'required' => false,
                    'attr'=> ['class'=> 'form-select text-center mt-1'],
                    ])
                //'class' => EtatObjet::class,
                //'placeholder' => 'Dans quel état est votre objet ?',
                //'choice_label' => 'nomEtat', 
                
                ->add('idDisponibilite', EntityType::class,[
                    'class' => DisponibiliteObjet::class,
                    'placeholder' => 'Disponibilité de l\'objet',
                    'choice_label' => 'nomDisponibilite', 
                    'label' => false,
                    //'mapped' => false,
                    'multiple' => false,
                    'required' => false,
                    'attr'=> ['class'=> 'form-select text-center mt-1'],
                    ])

                /*->add('disponibilite', ChoiceType::class,
                    array(
                        'choices' => array(
                            'Licence 1'    => $this->getUser()->setid_niveau(1),
                            'Licence 2' => '',
                            'Licence 3' => '',
                            'Master 1' => '',
                            'Master 2' => '',
                    )));*/
                

            ->add('rechercher', SubmitType::class,[
                'attr'=> ['class'=> 'btn theme-bg text-light rounded full-width'],
                'label' => 'Valider le don'
                ])

            ->add('idCategorie', EntityType::class, [
                'class' => Categorie::class,
                'mapped' => false,
                'choice_label' => "nomCategorie",
                'placeholder' => 'Choisissez la catégorie',            
                // 'expanded' => true,
                'label' => false,
                'attr'=> ['class'=> 'form-select text-center mt-1'],
                'required' => false,
                ])
                //;

                //$formModifier = function(FormInterface $form, Categorie $categorie=null){ 
            
                //$sousCategories = (null === $categorie ? [] : $this->SousCategorieRepository->findByIdCategorie($categorie));
                
                //$form
                ->add('idSousCategorie', EntityType::class, [
                'class' => SousCategorie::class,
                'placeholder' => 'Choisissez la sous-catégorie',
                'choice_label' => 'nomSousCategorie', 
                //'disabled' => true,
                //'choices' => $sousCategories,//$this->SousCategorieRepository->findAllOrderByAsc(),
                'multiple' => false,
                // 'expanded' => true,
                'label' => false,
                'required' => false,
                'attr'=> ['class'=> 'form-select text-center mt-1'
                ] ] );
       // };
        /*$builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($formModifier) {
                $data = $event->getData();
                $formModifier($event->getForm(), $data->getIdSousCategorie());
            }
        );

        $builder->get('idCategorie')->addEventListener( //permet d'écouter l'évènement du choix de la catégorie
            FormEvents::POST_SUBMIT,
            function(FormEvent $event) use ($formModifier){ //on récupère l'évènement dans event
                $categorie = $event->getForm()->getData();
                $formModifier($event->getForm()->getParent(), $categorie);
            }
        );*/
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            
        ]);
    }
}
