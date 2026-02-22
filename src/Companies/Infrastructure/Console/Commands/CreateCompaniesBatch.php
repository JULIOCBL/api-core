<?php

namespace Src\Companies\Infrastructure\Console\Commands;

use Illuminate\Console\Command;
use Src\Shared\Infrastructure\Persistence\Eloquent\Models\Company;

class CreateCompaniesBatch extends Command
{
    protected $signature = 'companies:create-batch {count=32}';
    protected $description = 'Create or update a batch of companies';

    public function handle(): int
    {
        $count = (int) $this->argument('count');

        if ($count < 1) {
            $this->error('The count must be greater than 0.');
            return Command::FAILURE;
        }

        for ($index = 1; $index <= $count; $index++) {
            $commercial_name = 'Commercial Company ' . $index;

            $company = Company::withTrashed()
                ->where('commercial_name', $commercial_name)
                ->first();

            $company_data = [
                'name' => 'Company ' . $index,
                'commercial_name' => $commercial_name,
                'bussiness_name' => 'Business Company ' . $index . ' SA de CV',
                'rfc' => sprintf('RFC%08d', $index),
                'contact_phone' => '999100' . str_pad((string) $index, 4, '0', STR_PAD_LEFT),
                'email' => 'company' . $index . '@example.com',
                'primary_color' => '#0A84FF',
                'secondary_color' => '#111111',
                'tertiary_color' => '#FFFFFF',
                'image_logo' => 'logos/company-' . $index . '.png',
                'status' => true,
            ];

            if ($company !== null) {
                $company->fill($company_data);
                $company->deleted_at = null;
                $company->save();
                continue;
            }

            Company::query()->create($company_data);
        }

        $this->info('Companies created/updated: ' . $count);

        return Command::SUCCESS;
    }
}
