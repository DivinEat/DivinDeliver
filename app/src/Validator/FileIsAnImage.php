<?php

namespace App\Validator;

use Webmozart\Assert\Assert;
use Symfony\Component\Validator\Constraint;

/**
* @Annotation
* @Assert
*/
class FileIsAnImage extends Constraint
{
    public $message = "image.not_valid";
}