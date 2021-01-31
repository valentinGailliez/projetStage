<?php

namespace App\Form;

use App\Entity\UtilisateurConnexion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class ConnexionFormType extends AbstractType
{
    public function buildform(FormBuilderInterface $builder, array $option)
    {
        $builder
            ->add('email', EmailType::class)
            ->add('mot_de_passe', PasswordType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UtilisateurConnexion::class
        ]);
    }
}
