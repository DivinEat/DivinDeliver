<?php

namespace App\Validator;

use Webmozart\Assert\Assert;
use Symfony\Component\Validator\Constraint;

/**
* @Annotation
* @Assert
*/
class UserShouldExist extends Constraint
{
    public $message = "user.email.user_should_exist";
}