<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class RegistryType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("email", TextType::class)
            ->add("name", TextType::class)
            ->add("postcode", TextType::class)
            ->add("telefon", TextType::class)
            ->add("place", TextType::class)
            ->add("terms", TextType::class);
    }

    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
        ]);
    }

}
