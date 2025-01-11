<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Fournisseur;
use App\Entity\Marque;
use App\Entity\Produit;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom du produit :',
                'constraints' => [
                    new NotBlank([
                        'message' => "Le nom du produit ne peut pas être vide"
                    ]),
                    new Length([
                        'min' => 2,
                        'max' => 255,
                        'minMessage' => "Le nom du produit doit être plus grand que 2",
                        'maxMessage' => "Le nom du produit est trop grand ( max 255) "
                    ]),
                ],
                'attr' => [
                    'placeholder' => "Insérer le nom du produit"
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => "Description du produit",
                'required' => false,
                'constraints' => [
                    new Length([
                        'min' => 50,
                        'minMessage' => "La description du produit doit faire au moins 50 caractères",
                        'max' => 500,
                        'maxMessage' => "La description du produit est trop grande. (max 500)"
                    ]),
                    new Regex([
                        'pattern' => '/^[a-zA-Z0-9\s\p{L}\p{P}]+$/u',
                        'message' => "Ce champ contient des caractères non autorisés pour une description",
                    ]),
                ],
                'attr' => [
                    'rows' => 5,
                    'cols' => 50,
                    'placeholder' => "Décrire le produit, sera affiché à l'utilisateur",
                ]
            ])
            // ->add('slug')
            ->add('image', FileType::class, [
                'label' => "Image du produit(jpeg, jpg, png)",
                'data_class' => null,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpg',
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Type de fichier invalide. Types autorisés : jpg, jpeg, png ',
                    ]),
                ],
            ])
            // ->add('isVisible')
            ->add('stock', IntegerType::class, [
                'required' => false,
                'attr' => [
                    'min' => 0,
                    'max' => 999,
                ],
                'label' => "Gérer le stock ici",
            ])
            // ->add('createdAt', null, [
            //     'widget' => 'single_text',
            // ])
            // ->add('updatedAt', null, [
            //     'widget' => 'single_text',
            // ])
            ->add('marque', EntityType::class, [
                'required' => false,
                'class' => Marque::class,
                'choice_label' => 'nom',
            ])
            ->add('fournisseur', EntityType::class, [
                'required' => false,
                'class' => Fournisseur::class,
                'choice_label' => 'nom',
            ])
            ->add('categorie', EntityType::class, [
                'required' => false,
                'class' => Categorie::class,
                'choice_label' => 'nom',
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id'   => 'produit_item',
        ]);
    }
}
