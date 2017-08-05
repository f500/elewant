<?php

namespace Elewant\FrontendBundle\Form;

use HWI\Bundle\OAuthBundle\Form\RegistrationFormHandlerInterface;
use HWI\Bundle\OAuthBundle\OAuth\Response\PathUserResponse;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

class RegistrationFormHandler implements RegistrationFormHandlerInterface
{
    public function process(Request $request, Form $form, UserResponseInterface $userInformation)
    {
        if (!$userInformation instanceof PathUserResponse) {
            throw new \LogicException(
                sprintf(
                    'Expected UserInformation to be class "%s" but got class "%s"',
                    PathUserResponse::class,
                    get_class($userInformation)
                )
            );
        }

        $form->get('username')->setData($userInformation->getNickname());
        $form->get('displayName')->setData($userInformation->getRealName());
        $form->get('country')->setData($userInformation->getResponse()['location'] ?? '');

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            return $form->isValid();
        }

        return false;
    }
}
