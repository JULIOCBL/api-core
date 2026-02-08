<?php

namespace Src\Core\Infrastructure\Support\Utils;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Crypt;

class Token
{

    public static function encode($data)
    {
        $private_key = file_get_contents(storage_path('app/private/keys/private.key'));
        $token = JWT::encode($data, $private_key, 'RS256');
        return  Crypt::encrypt($token);
    }

    public static function decode($data)
    {
        $data = Crypt::decrypt($data);
        $public_key = file_get_contents(storage_path('app/private/keys/public.key'));
        return JWT::decode($data, new Key($public_key, 'RS256'));
    }
}
