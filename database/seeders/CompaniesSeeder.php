<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompaniesSeeder extends Seeder
{
   protected $records = [
        [
            'id' => 1,
            'name'             => 'Lisytech',
            'commercial_name'  => 'Lisytech',
            'bussiness_name'   => 'Grupo Lisytech SA de CV',
            'rfc'              => 'GL123456AB1',
            'contact_phone'    => '9981341207',
            'email'            => 'contacto@lisytech.com',
            'primary_color'    => '#00FF00',
            'secondary_color'  => '#000000',
            'tertiary_color'   => '#FFFFFF',
            'image_logo'       => 'logos/billtag.png',
            'status'           => 1
        ]
    ];
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Company::upsert(
            $this->records,
            ['id'], // clave única
            [
                'name',
                'commercial_name',
                'bussiness_name',
                'rfc',
                'contact_phone',
                'email',
                'primary_color',
                'secondary_color',
                'tertiary_color',
                'image_logo',
                'status'
            ]
        );
    }
}
