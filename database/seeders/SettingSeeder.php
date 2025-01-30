<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ////////////////////// site /////////////////////////////

            [
                'key' => 'company_name',
                'value' => 'Drones',
                'group' => 'site'
            ],
            [
                'key' => 'rights_name',
                'value' => 'rights name',
                'group' => 'site'
            ],
            [
                'key' => 'website_name',
                'value' => 'Drones',
                'group' => 'site'
            ],
            [
                'key' => 'website_email',
                'value' => 'drones@google.com',
                'group' => 'site'
            ], [
                'key' => 'website_description',
                'value' => 'Drones',
                'group' => 'site'
            ], [
                'key' => 'website_meta_description',
                'value' => 'Drones',
                'group' => 'site'
            ],

            ////////////////////// properties /////////////////////////////

            [
                'key' => 'website_logo_large',
                'value' => null,
                'group' => 'properties'
            ],
            [
                'key' => 'website_logo_small',
                'value' => null,
                'group' => 'properties'
            ],
            [
                'key' => 'website_login_icon',
                'value' => null,
                'group' => 'properties'
            ],
            [
                'key' => 'website_favorite_place_icon',
                'value' => null,
                'group' => 'properties'
            ],

            ////////////////////// theme /////////////////////////////
            [
                'key' => 'primary_color',
                'value' => null,
                'group' => 'theme'
            ],
            [
                'key' => 'global_theme',
                'value' => null,
                'group' => 'theme'
            ],
            [
                'key' => 'toolbar_theme',
                'value' => null,
                'group' => 'theme'
            ],
            [
                'key' => 'toolbar_style',
                'value' => null,
                'group' => 'theme'
            ],
            [
                'key' => 'content_layout',
                'value' => null,
                'group' => 'theme'
            ],

            [
                'key' => 'menu_theme',
                'value' => null,
                'group' => 'theme'
            ],
            [
                'key' => 'rtl',
                'value' => null,
                'group' => 'theme'
            ],

            ////////////////////// mail_template /////////////////////////////
            [
                'key' => 'general_mail_template',
                'value' => null,
                'group' => 'mail_template'
            ],
            ////////////////////// mail_server /////////////////////////////
            [
                'key' => 'mail_server',
                'value' => null,
                'group' => 'mail_server',
            ],
            [
                'key' => 'mail_port',
                'value' => null,
                'group' => 'mail_server',
            ],
            [
                'key' => 'mail_user',
                'value' => null,
                'group' => 'mail_server',
            ],
            [
                'key' => 'mail_password',
                'value' => null,
                'group' => 'mail_server',
            ],
        ];

        foreach ($data as $array) {
            $setting = $this->findSetting($array['key']);
            if (!$setting->exists) {
                $setting->fill($array)->save();
            }
        }
    }


    protected function findSetting($key)
    {
        return Setting::firstOrNew(['key' => $key]);
    }
}