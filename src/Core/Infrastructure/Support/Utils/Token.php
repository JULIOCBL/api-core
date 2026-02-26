<?php


namespace Src\Core\Infrastructure\Support\Utils;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Crypt;
use RuntimeException;
use stdClass;

/**
 * Utilidad para codificar y decodificar tokens JWT cifrados.
 */
class Token
{

    /**
     * Firma un payload JWT con llave privada y cifra el resultado.
     *
     * @param array<string, mixed> $data
     * @return string
     */
    public static function encode(array $data): string
    {
        $private_key = file_get_contents(storage_path('app/private/keys/private.key'));
        if ($private_key === false) {
            throw new RuntimeException('Unable to read private key.');
        }

        $token = JWT::encode($data, $private_key, 'RS256');

        return Crypt::encrypt($token);
    }

    /**
     * Descifra un token y valida su firma con llave pública.
     *
     * @param string $data
     * @return stdClass
     */
    public static function decode(string $data): stdClass
    {
        $data = Crypt::decrypt($data);
        $public_key = file_get_contents(storage_path('app/private/keys/public.key'));
        if ($public_key === false) {
            throw new RuntimeException('Unable to read public key.');
        }

        return JWT::decode($data, new Key($public_key, 'RS256'));
    }
}
