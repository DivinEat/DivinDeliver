<?php

namespace App\Validator;

use Webmozart\Assert\Assert;
use Symfony\Component\Validator\Constraint;

/**
* @Annotation
* @Assert
*/
class UniqueMenuTitle extends Constraint
{
    public $message = "user.unique_title";
}