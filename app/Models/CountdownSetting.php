<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;

class CountdownSetting extends Model
{
    protected $fillable = [
        'id',
        'event_name',
        'starts_at',
        'ends_at',
        'timezone',
        'before_start_text',
        'running_text',
        'finished_text',
        'sync_version',
        'last_synced_at',
    ];

    protected function casts(): array
    {
        return [
            'sync_version' => 'integer',
        ];
    }

    public static function defaults(): array
    {
        return [
            'event_name' => 'Hackathon 2026',
            'starts_at' => '2026-07-04 09:00:00',
            'ends_at' => '2026-07-05 15:00:00',
            'timezone' => 'America/Sao_Paulo',
            'before_start_text' => 'Faltam para o início do Hackathon 2026',
            'running_text' => 'Hackathon 2026 em andamento',
            'finished_text' => 'Hackathon 2026 encerrado',
            'sync_version' => 1,
            'last_synced_at' => CarbonImmutable::now('America/Sao_Paulo')->format('Y-m-d H:i:s'),
        ];
    }

    public static function current(): self
    {
        $setting = self::query()->orderBy('id')->first();

        if ($setting) {
            return $setting;
        }

        return self::query()->create(['id' => 1, ...self::defaults()]);
    }

    public function startInTimezone(): CarbonImmutable
    {
        return CarbonImmutable::parse($this->starts_at, $this->timezone);
    }

    public function endInTimezone(): CarbonImmutable
    {
        return CarbonImmutable::parse($this->ends_at, $this->timezone);
    }

    public function lastSyncInTimezone(): CarbonImmutable
    {
        return CarbonImmutable::parse($this->last_synced_at, $this->timezone);
    }
}
