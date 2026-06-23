<?php

namespace App\Livewire\Admin;

use App\Models\CountdownSetting;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

class AdminPanel extends Component
{
    use Interactions;

    public string $email = '';

    public string $password = '';

    public bool $remember_email = false;

    public string $event_name = '';

    public string $starts_date = '';

    public string $starts_time = '';

    public string $ends_date = '';

    public string $ends_time = '';

    public string $timezone = 'America/Sao_Paulo';

    public string $before_start_text = '';

    public string $running_text = '';

    public string $finished_text = '';

    public array $timezones = [
        'America/Sao_Paulo',
        'UTC',
        'America/Fortaleza',
        'America/Manaus',
        'America/Rio_Branco',
    ];

    public function mount(): void
    {
        if (Auth::check()) {
            $this->authorizeAdmin();
            $this->loadSettings();

            return;
        }

        $rememberedEmail = request()->cookie('admin_email');

        if ($rememberedEmail) {
            $this->email = $rememberedEmail;
            $this->remember_email = true;
        }
    }

    public function login()
    {
        $this->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password], true)) {
            throw ValidationException::withMessages([
                'email' => 'As credenciais informadas não conferem.',
            ]);
        }

        session()->regenerate();

        if (! Auth::user()->is_admin) {
            Auth::logout();
            session()->invalidate();
            session()->regenerateToken();

            throw ValidationException::withMessages([
                'email' => 'Este usuário não possui acesso administrativo.',
            ]);
        }

        $this->authorizeAdmin();

        if ($this->remember_email) {
            Cookie::queue(cookie('admin_email', $this->email, 60 * 24 * 30));
        } else {
            Cookie::queue(Cookie::forget('admin_email'));
        }

        return redirect()->route('admin');
    }

    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect()->route('admin');
    }

    public function save(): void
    {
        $this->authorizeAdmin();

        $validated = $this->validate($this->rules());

        CountdownSetting::current()->update([
            'event_name' => $validated['event_name'],
            'starts_at' => CarbonImmutable::parse($validated['starts_date'].' '.$validated['starts_time'], $validated['timezone'])->format('Y-m-d H:i:s'),
            'ends_at' => CarbonImmutable::parse($validated['ends_date'].' '.$validated['ends_time'], $validated['timezone'])->format('Y-m-d H:i:s'),
            'timezone' => $validated['timezone'],
            'before_start_text' => $validated['before_start_text'],
            'running_text' => $validated['running_text'],
            'finished_text' => $validated['finished_text'],
            'sync_version' => CountdownSetting::current()->sync_version + 1,
            'last_synced_at' => CarbonImmutable::now($validated['timezone'])->format('Y-m-d H:i:s'),
        ]);

        $this->loadSettings();
        $this->toast()->success('Configurações salvas', 'O contador público será atualizado em alguns segundos.')->send();
    }

    public function syncClock(): void
    {
        $this->authorizeAdmin();

        $setting = CountdownSetting::current();
        $setting->update([
            'sync_version' => $setting->sync_version + 1,
            'last_synced_at' => CarbonImmutable::now($setting->timezone)->format('Y-m-d H:i:s'),
        ]);

        $this->loadSettings();
        $this->toast()->success('Horário sincronizado', 'A versão de sincronização foi atualizada.')->send();
    }

    public function restoreDefaults(): void
    {
        $this->authorizeAdmin();

        CountdownSetting::current()->update([
            ...CountdownSetting::defaults(),
            'sync_version' => CountdownSetting::current()->sync_version + 1,
        ]);

        $this->loadSettings();
        $this->toast()->success('Padrões restaurados', 'As datas e textos oficiais foram restaurados.')->send();
    }

    public function render()
    {
        $setting = Auth::check() && Auth::user()->is_admin ? CountdownSetting::current() : null;

        return view('livewire.admin.admin-panel', [
            'setting' => $setting,
            'serverNow' => $setting ? CarbonImmutable::now($setting->timezone) : null,
            'status' => $setting ? $this->statusFor($setting) : null,
            'progress' => $setting ? $this->progressFor($setting) : 0,
        ])->layout('components.layouts.guest', [
            'title' => 'Painel Administrativo | Contador Oficial',
        ]);
    }

    protected function rules(): array
    {
        return [
            'event_name' => ['required', 'string', 'max:120'],
            'starts_date' => ['required', 'date_format:Y-m-d'],
            'starts_time' => ['required', 'date_format:H:i'],
            'ends_date' => ['required', 'date_format:Y-m-d'],
            'ends_time' => ['required', 'date_format:H:i', function (string $attribute, mixed $value, \Closure $fail): void {
                if (! $this->starts_date || ! $this->starts_time || ! $this->ends_date || ! $value) {
                    return;
                }

                $start = CarbonImmutable::parse($this->starts_date.' '.$this->starts_time, $this->timezone);
                $end = CarbonImmutable::parse($this->ends_date.' '.$value, $this->timezone);

                if ($end->lte($start)) {
                    $fail('A data e hora de término deve ser posterior ao início.');
                }
            }],
            'timezone' => ['required', Rule::in($this->timezones)],
            'before_start_text' => ['required', 'string', 'max:160'],
            'running_text' => ['required', 'string', 'max:160'],
            'finished_text' => ['required', 'string', 'max:160'],
        ];
    }

    protected function loadSettings(): void
    {
        $setting = CountdownSetting::current();

        $this->event_name = $setting->event_name;
        $this->starts_date = $setting->startInTimezone()->format('Y-m-d');
        $this->starts_time = $setting->startInTimezone()->format('H:i');
        $this->ends_date = $setting->endInTimezone()->format('Y-m-d');
        $this->ends_time = $setting->endInTimezone()->format('H:i');
        $this->timezone = $setting->timezone;
        $this->before_start_text = $setting->before_start_text;
        $this->running_text = $setting->running_text;
        $this->finished_text = $setting->finished_text;
    }

    protected function authorizeAdmin(): void
    {
        abort_unless(Auth::check() && Auth::user()->is_admin, 403);
    }

    protected function statusFor(CountdownSetting $setting): string
    {
        $now = CarbonImmutable::now($setting->timezone);

        if ($now->lt($setting->startInTimezone())) {
            return 'Antes do início';
        }

        if ($now->lt($setting->endInTimezone())) {
            return 'Em andamento';
        }

        return 'Encerrado';
    }

    protected function progressFor(CountdownSetting $setting): int
    {
        $start = $setting->startInTimezone();
        $end = $setting->endInTimezone();
        $now = CarbonImmutable::now($setting->timezone);

        if ($now->lte($start)) {
            return 0;
        }

        if ($now->gte($end)) {
            return 100;
        }

        return (int) round(($start->diffInSeconds($now) / max(1, $start->diffInSeconds($end))) * 100);
    }
}
