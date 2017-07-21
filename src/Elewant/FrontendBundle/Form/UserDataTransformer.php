<?php

namespace Elewant\FrontendBundle\Form;

use Elewant\FrontendBundle\Entity\User;
use Symfony\Component\Form\Exception;
use Symfony\Component\Form\Extension\Core\DataMapper\PropertyPathMapper;
use Symfony\Component\Form\FormInterface;

final class UserDataTransformer extends PropertyPathMapper
{
    /**
     * Maps the data of a list of forms into the properties of some data.
     *
     * @param FormInterface[]|\Traversable $forms A list of {@link FormInterface} instances
     * @param mixed                        $data  Structured data
     *
     * @throws Exception\UnexpectedTypeException if the type of the data parameter is not supported.
     */
    public function mapFormsToData($forms, &$data)
    {
        $userData = [];
        foreach ($forms as $form) {
            $userData[$form->getName()] = $form->getData();
        }

        /** @var User $user */
        $user = $data;
        $user->createFromForm($userData['displayname'], $userData['username']);
    }
}
