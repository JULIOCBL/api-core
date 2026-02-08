<?php

namespace Src\Core\Infrastructure\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class GenerateKeyPair extends Command
{
    protected $signature = 'generate:keys';
    protected $description = 'Generate RSA key pair for JWT';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $config = [
            "digest_alg" => "sha256",
            "private_key_bits" => 2048,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
        ];

        // Generar la clave privada
        $res = openssl_pkey_new($config);

        // Extraer la clave privada del par generado
        openssl_pkey_export($res, $privKey);

        // Obtener el detalle de la clave para extraer la clave pública
        $pubKey = openssl_pkey_get_details($res);
        $pubKey = $pubKey["key"];

        // Asegurarse de que la carpeta "keys" exista
        $keysPath = 'keys'; // La ruta relativa dentro del disco 'local'
        if (!Storage::disk('local')->exists($keysPath)) {
            Storage::disk('local')->makeDirectory($keysPath);
        }

        // Almacenar las claves en el sistema de archivos en la carpeta especificada
        Storage::disk('local')->put($keysPath . '/private.key', $privKey);
        Storage::disk('local')->put($keysPath . '/public.key', $pubKey);

        $this->info('RSA key pair generated successfully. [storage/app/keys/private.key, storage/app/keys/public.key]');
    }
}
