<?php

namespace App\Form;

use App\Entity\Cotation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CotationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cotation', ChoiceType::class, [
                'choices' => [
                    'Veuillez indiquer une cote' => 0,
                    'NA--' => 1,
                    'NA-' => 2,
                    'A+' => 3,
                    'A++' => 4,

                ]
            ])
            ->add('comments', TextareaType::class)
            ->add('save', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Cotation::class,
        ]);
    }
}
