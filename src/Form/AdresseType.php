<?php

namespace App\Form;

use App\Entity\Adresse;
use App\Entity\User;
// use Doctrine\DBAL\Types\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Blank;
use Symfony\Component\Validator\Constraints\BlankValidator;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class AdresseType extends AbstractType
{
    private $security;
    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ligne1', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Cette ligne ne peut pas être vide',
                    ])
                ]
            ])
            ->add('ligne2', TextType::class, [
                'required' => false,
            ])
            ->add('codePostal', TextType::class, [
                'constraints' => [
                    new Length([
                        'min' => 5,
                        'max' => 5,
                        'exactMessage' => "Le champ doit contenir 5 caractères."
                    ]),
                    new NotBlank([
                        'message' => 'Cette ligne ne peut pas être vide',
                    ])
                ]
            ])
            ->add('ville', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Cette ligne ne peut pas être vide',
                    ])
                ]
            ])
            ->add('pays', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Cette ligne ne peut pas être vide',
                    ])
                ]
            ])
            ->add('cedex', TextType::class, [
                'required' => false,
                'constraints' => [new Length([
                    'min' => 4,
                    'max' => 4,
                    'exactMessage' => "Le champ doit contenir 4 caractères."
                ])]
            ]);

        // PLUS TARD AJOTUER ROLE 
        // if ($this->security->isGranted('ROLE_ADMIN')) {
        //     $builder->add('users', EntityType::class, [/* On peut gérer cela avec role_admin etc... */
        //         'class' => User::class,
        //         'choice_label' => 'prenom',
        //         'expanded' => true,
        //         'multiple' => true,
        //         'required' => false,
        //     ]);
    }
    // ->add('users', EntityType::class, [/* On peut gérer cela avec role_admin etc... */
    //     'class' => User::class,
    //     'choice_label' => 'prenom',
    //     'expanded' => true,
    //     'multiple' => true,
    //     'required' => false,
    // ]);


    public function buildFormDelete(FormBuilderInterface $builder, array $options)
    {
        $builder
            // Ajoute un champ caché pour le jeton CSRF
            ->add('_token', HiddenType::class);
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Adresse::class,

        ]);
    }
}
