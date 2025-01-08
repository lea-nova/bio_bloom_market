<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Produit;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class CategorieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom de la catégorie :',
                'constraints' => [
                    new NotBlank([
                        'message' => "Le nom de la catégorie ne peut pas être vide."
                    ]),
                    new Length([
                        'min' => 2,
                        'max' => 255,
                        'minMessage' => "Le nom de la catégorie doit être au moins de 2.",
                        'maxMessage' => "Le nom de la catégorie est trop grand. (max 255)"
                    ]),
                ],
                'attr' => [
                    'maxLength' => 255,
                    'placeholder' => "Insérer nom de la catégorie",
                ]
            ])
            // ->add('slug', TextType::class, [
            //     'label' => 'Slug à afficher :',
            //     'required' => false,
            //     'constraints' => [
            //         new Length([
            //             'max' => 255,
            //             'maxMessage' => "Le slug de la catégorie est trop grand. (max 255)"
            //         ]),
            //     ],
            //     'attr' => [
            //         'maxLength' => 255,
            //         'placeholder' => "Slug pour l'URL",
            //     ]
            // ])
            ->add('description', TextareaType::class, [
                'label' => 'Description de la catégorie :',
                'required' => false,
                'constraints' => [
                    new Length([
                        'min' => 50,
                        'minMessage' => "La description de la catégorie doit faire plus de 50 caractères",
                        'max' => 500,
                        'maxMessage' => "La description de la catégorie est trop grand. (max 500)"
                    ]),
                    new Regex([
                        'pattern' => '/^[a-zA-Z0-9\s\p{L}\p{P}]+$/u',
                        'message' => "Ce champ contient des caractères non autorisés pour une description",
                    ])
                ],
                'attr' => [

                    'rows' => 5,
                    'cols' => 50,
                    'minLength' => 50,
                    'maxLength' => 500,
                    'placeholder' => "Décrire la catégorie, sera affiché à l'utilisateur",
                ]
            ])
            // ->add('createdAt', null, [
            //     'widget' => 'single_text',
            // ])
            // ->add('updatedAt', null, [
            //     'widget' => 'single_text',
            // ])
            // ->add('isVisible')
            // ->add('produits', EntityType::class, [
            //     'class' => Produit::class,
            //     'choice_label' => 'id',
            //     'multiple' => true,
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Categorie::class,
            'csrf_protection' => true,
            // the name of the hidden HTML field that stores the token
            'csrf_field_name' => '_token',
            // an arbitrary string used to generate the value of the token
            // using a different string for each form improves its security
            'csrf_token_id'   => 'categorie_item',
        ]);
    }
}
