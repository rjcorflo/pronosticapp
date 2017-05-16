<?php

namespace RJ\PronosticApp\Util\Validation;

use RJ\PronosticApp\Util\Validation\Validator\AbstractValidator;
use RJ\PronosticApp\Util\Validation\Validator\ExistenceValidator;
use RJ\PronosticApp\Util\Validation\Validator\PlayerValidator;

interface ValidatorInterface
{
    public function playerValidator() : PlayerValidator;

    public function communityValidator() : AbstractValidator;

    public function existenceValidator() : ExistenceValidator;
}