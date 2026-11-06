<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $defaults = [
            'rent.default_monthly_rate' => '1500',
            'rent.top_bunk_rate' => null,
            'rent.bottom_bunk_rate' => null,
            'rent.prorate' => '0',
            'due.grace_period_days' => '3',
            'due.due_soon_days' => '5',
            'device_surcharges' => '[]',
        ];

        foreach ($defaults as $key => $value) {
            DB::table('settings')->insertOrIgnore(['key' => $key, 'value' => $value]);
        }
    }

    public function down(): void
    {
        // Keep administrator-edited settings when rolling back.
    }
};
