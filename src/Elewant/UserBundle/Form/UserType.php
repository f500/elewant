<?php

declare(strict_types=1);

namespace Elewant\UserBundle\Form;

use Elewant\UserBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType implements DataMapperInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, ['disabled' => true])
            ->add('displayName', TextType::class)
            ->add('country', TextType::class)
            ->setDataMapper($this);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => User::class,
                'empty_data' => null,
            ]
        );
    }

    public function mapDataToForms($user, $forms): void
    {
        if (!$user instanceof User) {
            return;
        }

        /** @var FormInterface[] $form */
        $form = iterator_to_array($forms);

        $form['username']->setData($user->username());
        $form['displayName']->setData($user->displayName());
        $form['country']->setData($user->country());
    }

    public function mapFormsToData($forms, &$user): void
    {
        /** @var FormInterface[] $form */
        $form = iterator_to_array($forms);

        $user->changeDisplayName((string) $form['displayName']->getData());
        $user->changeCountry((string) $form['country']->getData());
    }
}
