<?php

namespace App\Form;

use App\Entity\Ville;
use App\Entity\Annonce;
use App\Entity\Categorie;
use App\Entity\EtatObjet;
use App\Entity\SousCategorie;
use Symfony\Component\Form\Forms;
use App\Repository\AnnonceRepository;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use App\Repository\CategorieRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use App\Repository\SousCategorieRepository;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationExtension;

class AnnonceType extends AbstractType
{

    private $categorieRepository;

    public function __construct( CategorieRepository $categorieRepository, SousCategorieRepository $sousCategorieRepository)

    {
        $this->CategorieRepository = $categorieRepository;
        $this->SousCategorieRepository = $sousCategorieRepository;
    }
   //formbuilder permet de creer le formulaire 

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $formFactory = Forms::createFormFactoryBuilder()
    ->addExtension(new HttpFoundationExtension())
    ->getFormFactory();
    
        $builder
        
            ->add('titreAnnonce', TextType::class, [
                'attr'=> ['class'=> 'form-control text-center mt-1'], 
                'label' => false,
                'help' => 'Notez le titre que vous souhaitez faire apparaitre.',
                'constraints' =>
                    //new NotBlank(['message' => 'Choisissez un titre']), 
                    new Length([
                        'min' => 5,
                        'minMessage' => 'La titre ne peux pas faire moins de {{ limit }} caractères',
                        'max' => 30,
                        'maxMessage' => 'La titre ne peux pas faire plus de {{ limit }} caractères',  
                ]),
                ])

            ->add('descriptionAnnonce', CKEditorType::class, [
                'attr'=> ['class'=> 'form-control text-center mt-1'], 
                'label' => false,
                'constraints' =>
                    new Length([
                        'min' => 10,
                        'minMessage' => 'La description ne peux pas faire moins de {{ limit }} caractères',
                        'max' => 2500,
                        'maxMessage' => 'La description ne peux pas faire plus de {{ limit }} caractères',  
                ]),
                ])

            ->add('images', FileType::class,[
                'label' => "Ajoutez 1 ou plusieurs photos de votre don. Pour selectionner plusieurs photos, restez appuyer sur ctrl puis clic gauche sur chaque photo puis ouvrir." ,
                "mapped" => false, // permet d'indiquer à symfony que cet input ne correspond à aucun champ de notre entité
                "multiple" => true, // permet d'uploader plusieurs fichiers à la fois 
                'required' => true,// permet de rendre non obligatoire l'ajout d'image
                /*'constraints' => [
                    new Image([
                        'maxSize' => '5M',
                    ])
                    ],*/
                'attr' => ['class' => 'form-control rounded dropzone dz-clickable'],
                ])

            ->add('adresse', TextType::class,[
                'label' => false,
                'required' => false,
                //'placeholder' => 'Entrez le numéro et le nom de rue ici !',
                'attr' => ['class' => 'form-control rounded']
                ])

            ->add('ville', TextType::class,[
                //'class' => Ville::class,
                'mapped' => false,
                //'choice_label' => 'nomVille',
                'label' => false,
                'required' => true,
                'attr' => ['class' => 'form-control rounded']
                ])


            ->add('cp', TextType::class,[
                //'class' => Ville::class,
                'mapped' => false,
                //'choice_label' => 'nomVille',
                'label' => false,
                'required' => true,
                'attr' => ['class' => 'form-control rounded']
                ])
                
            ->add('codeInsee', HiddenType::class,[
                'mapped' => false,
                'label' => false,
                'required' => false,
                'attr' => ['class' => 'form-control rounded']
                ])

            ->add('idEtat', EntityType::class,[
                'class' => EtatObjet::class,
                'placeholder' => 'Dans quel état est votre objet ?',
                'choice_label' => 'nomEtat', 
                'label' => false,
                'multiple' => false,
                'attr'=> ['class'=> 'form-select text-center mt-1'],
                ])

            ->add('save', SubmitType::class,[
                'attr'=> ['class'=> 'btn theme-bg rounded text-light'],
                'label' => 'Valider le don'
                ])

            ->add('idCategorie', EntityType::class, [
                'class' => Categorie::class,
                'mapped' => false,
                'choice_label' => "nomCategorie",
                'placeholder' => 'Choisissez la catégorie',                
                // 'expanded' => true,
                'label' => false,
                'attr'=> ['class'=> 'form-select text-center mt-1']
                ]);

                $formModifier = function(FormInterface $form, Categorie $categorie = null){ 
            
                $sousCategories = null === $categorie ? [] : $this->SousCategorieRepository->findByIdCategorie($categorie);
                
                $form->add('idSousCategorie', EntityType::class, [
                'class' => SousCategorie::class,
                'placeholder' => 'Choisissez la sous-catégorie',
                'choice_label' => 'nomSousCategorie', 
                //'disabled' => true,
                'choices' => $sousCategories,//$this->SousCategorieRepository->findAllOrderByAsc(),
                'multiple' => false,
                // 'expanded' => true,
                'label' => false,
                'attr'=> ['class'=> 'form-select text-center mt-1'
                ] ] );
        };

        $builder->addEventListener( 
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($formModifier) {
                $data = $event->getData();
                //dd($data);
                $formModifier($event->getForm(), $data->getIdSousCategorie());
            }
        );
        $builder->get('idCategorie')->addEventListener( //permet d'écouter l'évènement du choix de la catégorie
            FormEvents::POST_SUBMIT,
            function(FormEvent $event) use ($formModifier){ //on récupère l'évènement dans event
                $categorie = $event->getForm()->getData();
                $formModifier($event->getForm()->getParent(), $categorie);
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Annonce::class,
        ]);
    }
}
