<?php

namespace App\Form;

use App\Entity\Marque;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class MarqueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => "Nom de la marque",

                'constraints' => [
                    new NotBlank([
                        'message' => "Le nom de la marque ne peut pas être vide."
                    ]),
                    new Length([
                        'min' => 2,
                        'max' => 255,
                        'minMessage' => "Le nom de la marque doit être au moins de 2.",
                        'maxMessage' => "Le nom de la marque est trop grand. (max 255)"
                    ]),
                ],
                'attr' => [
                    'maxLength' => 255,
                    'placeholder' => "Insérer nom de la marque",
                ]
            ])
            // Pas obligé car généré automatiquement par le nom
            // ->add('slug')
            ->add('description', TextareaType::class, [
                'label' => 'Description de la marque :',
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
                    'placeholder' => "Décrire la marque, sera affiché à l'utilisateur",
                ]
            ])
            ->add('logo', FileType::class, [
                'label' => "Logo de la marque ( jpg, jpeg, png)",
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
                    ])
                ],
            ])
            // ->add('createdAt', null, [
            //     'widget' => 'single_text',
            // ])
            // ->add('updatedAt', null, [
            //     'widget' => 'single_text',
            // ])c 
            ->add('active')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Marque::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id'   => 'marque_item',
        ]);
    }
}
