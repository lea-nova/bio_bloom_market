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
        // ->add('password', PasswordType::class)
        $builder->add('nom', TypeTextType::class, [
            'disabled' => true,
        ])
            ->add('prenom', TypeTextType::class, [
                'disabled' => true,
            ])
            ->add('telephone')
            ->add('dateNaissance', null, [
                'widget' => 'single_text',
                'disabled' => true,
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
