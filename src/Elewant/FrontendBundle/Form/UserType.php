<?php

declare(strict_types=1);

namespace Elewant\FrontendBundle\Form;

use Elewant\FrontendBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) : void
    {
        $builder
            ->add('username', TextType::class, ['disabled' => true])
            ->add('displayName', TextType::class)
            ->add('country', TextType::class)
            ->setDataMapper(new UserDataTransformer());
    }

    public function configureOptions(OptionsResolver $resolver) : void
    {
        $resolver->setDefaults(['data_class' => User::class]);
    }
}
