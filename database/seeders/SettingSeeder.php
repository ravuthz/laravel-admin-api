<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Models\SettingType;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $general = SettingType::data('General', 'General Settings');
        $site = SettingType::data('Site', 'Site Settings', ['parent_id' => $general->id]);

        $type = ['type_id' => $site->id];

        Setting::data('Site Logo', '/logo.png', $type);
        $title = Setting::data('Site Title', 'API', $type);
        $author = Setting::data('Site Author', 'Adminz', $type);
        $keyword = Setting::data('Site Keywords', 'Laravel, PHP, Web', $type);
        $description = Setting::data('Site Description', 'The Laravel Framework For Web', $type);

        Setting::data('Site Meta', '', [
            'type_id' => $site->id,
            'options' => [
                'title' => $title->value,
                'author' => $author->value,
                'keyword' => $keyword->value,
                'description' => $description->value,
            ],
        ]);
    }
}
