<?php

namespace App\Http\Controllers;

use App\Models\CountdownSetting;
use Carbon\CarbonImmutable;
use Illuminate\Http\JsonResponse;

class CountdownStateController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $setting = CountdownSetting::current();
        $now = CarbonImmutable::now($setting->timezone);

        return response()->json([
            'event_name' => $setting->event_name,
            'timezone' => $setting->timezone,
            'server_now' => $now->toIso8601String(),
            'start_at' => $setting->startInTimezone()->toIso8601String(),
            'end_at' => $setting->endInTimezone()->toIso8601String(),
            'before_start_text' => $setting->before_start_text,
            'running_text' => $setting->running_text,
            'finished_text' => $setting->finished_text,
            'sync_version' => $setting->sync_version,
            'last_synced_at' => $setting->lastSyncInTimezone()->toIso8601String(),
        ]);
    }
}
