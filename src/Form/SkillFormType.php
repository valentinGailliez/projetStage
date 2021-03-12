<?php

namespace App\Form;

use App\Entity\Skill;
use App\Entity\ApplicationField;
use Symfony\Component\Form\AbstractType;
use App\Repository\ApplicationFieldRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
            ->add('domain', EntityType::class, [
                'class' => ApplicationField::class,
                'query_builder' => function (ApplicationFieldRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->andWhere('u.type=:val')
                        ->setParameter('val', 'category');
                },
                'choice_label' => function ($domain) {
                    return $domain->getName();
                }
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Skill::class,
        ]);
    }
}
