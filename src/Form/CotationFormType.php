<?php

namespace App\Form;

use App\Entity\Cotation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

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
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Cotation::class,
        ]);
    }
}
