<?php

namespace App\Form;

use App\Entity\ApplicationField;
use App\Entity\Intership;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class IntershipFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ectsCode', TextType::class)
            ->add('ansco', TextType::class)
            ->add('firstDay', DateType::class)
            ->add('lastDay', DateType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Intership::class,
        ]);
    }
}
