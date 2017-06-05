<?php

namespace RJ\PronosticApp\Util\Validation\Exception;

use RJ\PronosticApp\Model\Exception\PronosticAppException;

/**
 * Exception when there is an error in data validation.
 *
 * @package RJ\PronosticApp\Util\Validation\Exception
 */
class ValidationException extends PronosticAppException
{
    protected $responseCode = 400;

    protected $responseStatus = 'Error en la validacion de los datos';

    protected $message = 'Error validando los datos';
}
