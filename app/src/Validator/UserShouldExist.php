<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
* @Annotation
*/
class UserShouldExist extends Constraint
{
    public $message = "Any user exist with the email : {{ email }}. You may register.";
}