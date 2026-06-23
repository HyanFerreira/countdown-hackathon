<?php

namespace App\Livewire\Countdown;

use App\Models\CountdownSetting;
use Livewire\Component;

class PublicCountdown extends Component
{
    public function render()
    {
        return view('livewire.countdown.public-countdown', [
            'setting' => CountdownSetting::current(),
        ])->layout('components.layouts.guest', [
            'title' => 'Contador Oficial | Hackathon 2026',
        ]);
    }
}
