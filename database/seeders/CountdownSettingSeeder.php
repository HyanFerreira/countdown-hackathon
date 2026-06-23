<?php

namespace Database\Seeders;

use App\Models\CountdownSetting;
use Illuminate\Database\Seeder;

class CountdownSettingSeeder extends Seeder
{
    public function run(): void
    {
        CountdownSetting::query()->where('id', '!=', 1)->delete();

        CountdownSetting::query()->updateOrCreate(
            ['id' => 1],
            CountdownSetting::defaults(),
        );
    }
}
