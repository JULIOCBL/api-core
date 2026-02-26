<?php


namespace Src\Core\Infrastructure\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

/**
 * Comando para generar par de llaves RSA usado por JWT.
 */
class GenerateKeyPair extends Command
{
    protected $signature = 'generate:keys';
    protected $description = 'Generate RSA key pair for JWT';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return int
     */
    public function handle(): int
    {
        $config = [
            'digest_alg' => 'sha256',
            'private_key_bits' => 2048,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
        ];

        $resource = openssl_pkey_new($config);

        if ($resource === false) {
            throw new RuntimeException('Unable to generate RSA key pair.');
        }

        $private_key = '';
        $exported = openssl_pkey_export($resource, $private_key);
        if ($exported === false || $private_key === '') {
            throw new RuntimeException('Unable to export private key.');
        }

        $public_key_details = openssl_pkey_get_details($resource);
        if ($public_key_details === false || !isset($public_key_details['key'])) {
            throw new RuntimeException('Unable to extract public key.');
        }

        $public_key = (string) $public_key_details['key'];
        $keys_path = 'keys';

        if (!Storage::disk('local')->exists($keys_path)) {
            Storage::disk('local')->makeDirectory($keys_path);
        }

        Storage::disk('local')->put($keys_path . '/private.key', $private_key);
        Storage::disk('local')->put($keys_path . '/public.key', $public_key);

        $this->info('RSA key pair generated successfully. [storage/app/keys/private.key, storage/app/keys/public.key]');

        return Command::SUCCESS;
    }
}
