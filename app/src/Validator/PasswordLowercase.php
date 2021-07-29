<?php

namespace App\Validator;

use Webmozart\Assert\Assert;
use Symfony\Component\Validator\Constraint;

/**
* @Annotation
* @Assert
*/
class PasswordLowercase extends Constraint
{
    public $message = "user.password.lowercase";
}