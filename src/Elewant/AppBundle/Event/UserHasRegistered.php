<?php

declare(strict_types=1);

namespace Elewant\AppBundle\Event;

use Elewant\AppBundle\Entity\User;
use Symfony\Component\EventDispatcher\Event;

final class UserHasRegistered extends Event
{
    const NAME = 'user.has.registered';

    /** @var User */
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function user()
    {
        return $this->user;
    }
}
