<?php

namespace App\Form;

use App\Entity\Fournisseur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class FournisseurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom fournisseur :',
            ])
            ->add('telephone', TextType::class, [
                'label' => 'Numéro de téléphone :',
                // 'attr' => [
                //     'placeholder' => 'Numéro de téléphone '
                // ],
                'constraints' => [new Length([
                    'min' => 10,
                    'max' => 10,
                    'exactMessage' => "Le champ doit contenir 10 caractères."
                ])]
            ])
            ->add('mail', EmailType::class, [
                'label' => 'Adresse e-mail :',
                'attr' => [
                    'placeholder' => 'Entrez mail du fournisseur'
                ]
            ])
            ->add('service', TextType::class, [
                'label' => 'Service à contacter :',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Fournisseur::class,
        ]);
    }
}
