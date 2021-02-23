<?php

namespace App\Form;

use App\Entity\Skill;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class SkillFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('skillNumber', NumberType::class, ['required' => true])
            ->add('name', TextType::class, [
                'required' => true,
                'constraints' => [new Length(['min' => 1, 'max' => 255])]
            ])
            ->add('section', TextType::class, [
                'required' => true,
                'constraints' => [new Length(['min' => 1, 'max' => 255])]
            ])
            ->add('comments', TextType::class, [
                'required' => false,
                'constraints' => [new Length(['min' => 0, 'max' => 255])]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Skill::class,
        ]);
    }
}
