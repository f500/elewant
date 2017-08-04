<?php

declare(strict_types=1);

namespace Elewant\FrontendBundle\Form;

use Elewant\FrontendBundle\Entity\User;
use Symfony\Component\Form\Extension\Core\DataMapper\PropertyPathMapper;
use Symfony\Component\Form\FormInterface;

final class UserDataTransformer extends PropertyPathMapper
{
    /**
     * @param FormInterface[] $forms
     * @param User            $user
     */
    public function mapFormsToData($forms, &$user) : void
    {
        foreach ($forms as $form) {
            switch ($form->getName()) {
                case 'displayName':
                    $user->changeDisplayName($form->getData());
                    break;
                case 'country':
                    $user->changeCountry($form->getData());
                    break;
            }
        }
    }
}
