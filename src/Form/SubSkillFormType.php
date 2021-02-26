<?php

namespace App\Form;

use App\Entity\SubSkill;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class SubSkillFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('number', NumberType::class, ['required' => true])
            ->add('name', TextType::class, ['required' => true])
            ->add('comments', TextType::class, [
                'required' => false,
                'constraints' => [new Length(['min' => 0, 'max' => 255])]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SubSkill::class,
        ]);
    }
}
