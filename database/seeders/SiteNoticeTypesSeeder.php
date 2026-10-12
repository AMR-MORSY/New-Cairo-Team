<?php

namespace Database\Seeders;

use App\Enums\SiteNoticeTypes;
use App\Models\Site\SiteNoticeType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SiteNoticeTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $siteNoticeTypes = array_column(SiteNoticeTypes::cases(), 'value');

        foreach ($siteNoticeTypes as $siteNoticeType) {
            SiteNoticeType::firstOrCreate(['name' => $siteNoticeType]);
        }
    }
}
