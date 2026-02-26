<?php

namespace Src\Core\Infrastructure\Traits;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;

/**
 * Trait reutilizable para validar contraseñas cifradas con Crypt.
 */
trait AttemptsEncryptedPassword
{
    /**
     * Valida la contraseña plana contra el valor cifrado del atributo `password`.
     *
     * @param string $password
     * @return bool
     */
    public function attempt(string $password): bool
    {
        if (!isset($this->password) || $this->password === null || $this->password === '') {
            return false;
        }

        try {
            return hash_equals((string) Crypt::decrypt($this->password), $password);
        } catch (DecryptException $decrypt_exception) {
            return false;
        }
    }
}
