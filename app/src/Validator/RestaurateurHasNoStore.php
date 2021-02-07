<?php

namespace App\Validator;

use Webmozart\Assert\Assert;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Assert
 */
class RestaurateurHasNoStore extends Constraint
{
    public $message = "store.restaurateur.has.no.store";
}
