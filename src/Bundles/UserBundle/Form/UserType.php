<?php

declare(strict_types=1);

namespace Bundles\UserBundle\Form;

use Bundles\UserBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Traversable;

final class UserType extends AbstractType implements DataMapperInterface
{
    /**
     * @param FormBuilderInterface $builder
     * @param mixed[] $options
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
     */
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

    /**
     * @param mixed $user
     * @param FormInterface[]|Traversable $forms
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
     */
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

    /**
     * @param FormInterface[]|Traversable $forms
     * @param User $user
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
     */
    public function mapFormsToData($forms, &$user): void
    {
        /** @var FormInterface[] $form */
        $form = iterator_to_array($forms);

        $user->changeDisplayName((string) $form['displayName']->getData());
        $user->changeCountry((string) $form['country']->getData());
    }
}
