<?php

namespace Src\Companies\Domain\Exceptions;

use RuntimeException;

/**
 * Excepción de dominio para compañía inexistente o inactiva lógicamente.
 */
class CompanyNotFoundException extends RuntimeException
{
}
