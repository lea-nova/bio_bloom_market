<?php

namespace App\Form;

use App\Entity\Adresse;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

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
            ->add('ligne1')
            ->add('ligne2')
            ->add('codePostal')
            ->add('ville')
            ->add('pays')
            ->add('cedex');

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
