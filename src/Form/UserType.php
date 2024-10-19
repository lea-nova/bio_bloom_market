<?php

namespace App\Form;

use App\Entity\User;
use Doctrine\DBAL\Types\TextType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType as TypeTextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

class UserType extends AbstractType
{

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email');
        if ($this->security->isGranted('ROLE_ADMIN')) {
            $builder->add('roles', ChoiceType::class, [
                'choices' => [
                    // 'User' => 'ROLE_USER',
                    'Admin' => 'ROLE_ADMIN',
                    'Super Admin' => 'ROLE_SUPER_ADMIN'
                    // Ajoutez d'autres rôles si nécessaire
                ],
                'multiple' => true, // Si les utilisateurs peuvent avoir plusieurs rôles
                'expanded' => true, // Pour afficher les choix sous forme de cases à cocher ou de boutons radio
            ]);
        }
        $builder->add('plainPassword', PasswordType::class, [
            // instead of being set onto the object directly,
            // this is read and encoded in the controller
            'label' => 'Mot de passe : ',
            'mapped' => false,
            'attr' => [
                'autocomplete' => 'new-password',
                'placeholder' => 'Entrez un mot de passe'
            ],
            'constraints' => [
                new NotBlank([
                    'message' => 'Please enter a password',
                ]),
                new Length([
                    'min' => 6,
                    'minMessage' => 'Your password should be at least {{ limit }} characters',
                    // max length allowed by Symfony for security reasons
                    'max' => 4096,
                ]),
                new Regex([
                    'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/',
                    'message' => 'Votre mot de passe doit contenir au moins une lettre majuscule, une lettre minuscule, un chiffre et un caractère spécial',
                ]),
            ],
        ])
            ->add('checkPassword', PasswordType::class, [
                'mapped' => false, // champs pas lié à une prop de l'entité.
                'label' => 'Confirmez le mot de passe :',
                'attr' => [
                    'placeholder' => "Confirmez le mot de passe",
                    'class' => 'text-xs',
                ]
            ])
            ->add('nom', TypeTextType::class, [
                // 'disabled' => true,
            ])
            ->add('prenom', TypeTextType::class, [
                // 'disabled' => true,
            ])
            ->add('telephone')
            ->add('dateNaissance', null, [
                'widget' => 'single_text',
                // 'disabled' => true,
            ])
            ->add('fideliteClient');
        // ->add('prefAchat');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
