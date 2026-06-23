<div
    id="countdown-screen"
    class="relative min-h-screen overflow-hidden bg-[#101820] text-[#F8FAFC]"
    data-endpoint="{{ url('/api/countdown-state') }}"
>
    <div class="absolute inset-0 opacity-25"
        style="background-image: linear-gradient(rgba(46, 196, 182, .20) 1px, transparent 1px), linear-gradient(90deg, rgba(46, 196, 182, .16) 1px, transparent 1px); background-size: 52px 52px;">
    </div>

    <main class="relative z-10 flex min-h-screen flex-col px-5 py-5 sm:px-8 lg:px-12">
        <header class="flex items-center justify-between gap-4">
            <div>
                <p class="text-sm font-semibold uppercase text-[#2EC4B6]">Contador oficial</p>
                <h1 id="event-name" class="mt-2 text-2xl font-black sm:text-4xl">{{ $setting->event_name }}</h1>
            </div>

            <button
                type="button"
                id="fullscreen-button"
                class="inline-flex cursor-pointer items-center gap-2 rounded-md border border-white/15 bg-white/10 px-4 py-2 text-sm font-semibold text-white transition hover:bg-white/15"
            >
                <svg id="fullscreen-enter-icon" xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M8 3H5a2 2 0 0 0-2 2v3" />
                    <path d="M21 8V5a2 2 0 0 0-2-2h-3" />
                    <path d="M3 16v3a2 2 0 0 0 2 2h3" />
                    <path d="M16 21h3a2 2 0 0 0 2-2v-3" />
                </svg>
                <svg id="fullscreen-exit-icon" xmlns="http://www.w3.org/2000/svg" class="hidden size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M8 3v3a2 2 0 0 1-2 2H3" />
                    <path d="M21 8h-3a2 2 0 0 1-2-2V3" />
                    <path d="M3 16h3a2 2 0 0 1 2 2v3" />
                    <path d="M16 21v-3a2 2 0 0 1 2-2h3" />
                </svg>
                <span id="fullscreen-label">Tela cheia</span>
            </button>
        </header>

        <section class="flex flex-1 flex-col items-center justify-center py-10 text-center">
            <div id="status-pill" class="mb-6 inline-flex items-center gap-3 rounded-full border border-[#2EC4B6]/40 bg-[#073B4C]/80 px-5 py-2 text-sm font-black uppercase text-[#2EC4B6] shadow-lg shadow-[#2EC4B6]/10">
                Sincronizando
            </div>

            <p id="headline" class="max-w-5xl text-3xl font-black leading-tight text-white sm:text-5xl lg:text-7xl">
                {{ $setting->before_start_text }}
            </p>

            <p id="subheadline" class="mt-5 text-xl font-semibold text-[#F4E3C1] sm:text-2xl">
                Horário oficial de Brasília
            </p>

            <div class="mt-10 grid w-full max-w-6xl grid-cols-2 gap-3 sm:grid-cols-4 sm:gap-5">
                <div class="rounded-lg border border-white/10 bg-white/[0.08] p-5 shadow-2xl shadow-black/20 backdrop-blur">
                    <div id="days" class="font-mono text-5xl font-black leading-none text-white sm:text-7xl lg:text-8xl">00</div>
                    <div class="mt-3 text-sm font-bold uppercase text-[#2EC4B6] sm:text-base">Dias</div>
                </div>
                <div class="rounded-lg border border-white/10 bg-white/[0.08] p-5 shadow-2xl shadow-black/20 backdrop-blur">
                    <div id="hours" class="font-mono text-5xl font-black leading-none text-white sm:text-7xl lg:text-8xl">00</div>
                    <div class="mt-3 text-sm font-bold uppercase text-[#2EC4B6] sm:text-base">Horas</div>
                </div>
                <div class="rounded-lg border border-white/10 bg-white/[0.08] p-5 shadow-2xl shadow-black/20 backdrop-blur">
                    <div id="minutes" class="font-mono text-5xl font-black leading-none text-white sm:text-7xl lg:text-8xl">00</div>
                    <div class="mt-3 text-sm font-bold uppercase text-[#2EC4B6] sm:text-base">Minutos</div>
                </div>
                <div class="rounded-lg border border-white/10 bg-white/[0.08] p-5 shadow-2xl shadow-black/20 backdrop-blur">
                    <div id="seconds" class="font-mono text-5xl font-black leading-none text-white sm:text-7xl lg:text-8xl">00</div>
                    <div class="mt-3 text-sm font-bold uppercase text-[#2EC4B6] sm:text-base">Segundos</div>
                </div>
            </div>

            <div id="progress-area" class="mt-9 hidden w-full max-w-5xl">
                <div class="mb-3 flex items-center justify-between text-sm font-bold uppercase text-[#F4E3C1]">
                    <span>Progresso do evento</span>
                    <span id="progress-label">0%</span>
                </div>
                <div class="h-4 overflow-hidden rounded-full bg-white/10">
                    <div id="progress-bar" class="h-full rounded-full bg-gradient-to-r from-[#2EC4B6] to-[#FF6B35] transition-all duration-700" style="width: 0%"></div>
                </div>
            </div>

            <p id="final-message" class="mt-8 hidden max-w-3xl text-2xl font-semibold text-[#F4E3C1]">
                Obrigado por fazer parte dessa maratona de inovação.
            </p>
        </section>

        <footer class="mx-auto grid w-full max-w-3xl gap-2 text-xs text-[#F8FAFC]/80 sm:grid-cols-2">
            <div class="rounded-md border border-white/10 bg-white/[0.06] px-3 py-2">
                <span class="block font-semibold text-[#F4E3C1]">Início</span>
                <span id="start-label">--</span>
            </div>
            <div class="rounded-md border border-white/10 bg-white/[0.06] px-3 py-2">
                <span class="block font-semibold text-[#F4E3C1]">Término</span>
                <span id="end-label">--</span>
            </div>
        </footer>
    </main>

    <script>
        (() => {
            const root = document.getElementById('countdown-screen');
            if (!root || root.dataset.ready === 'true') return;
            root.dataset.ready = 'true';

            const endpoint = root.dataset.endpoint;
            const els = {
                eventName: document.getElementById('event-name'),
                headline: document.getElementById('headline'),
                subheadline: document.getElementById('subheadline'),
                status: document.getElementById('status-pill'),
                days: document.getElementById('days'),
                hours: document.getElementById('hours'),
                minutes: document.getElementById('minutes'),
                seconds: document.getElementById('seconds'),
                progressArea: document.getElementById('progress-area'),
                progressBar: document.getElementById('progress-bar'),
                progressLabel: document.getElementById('progress-label'),
                finalMessage: document.getElementById('final-message'),
                startLabel: document.getElementById('start-label'),
                endLabel: document.getElementById('end-label'),
                fullscreen: document.getElementById('fullscreen-button'),
                fullscreenLabel: document.getElementById('fullscreen-label'),
                fullscreenEnterIcon: document.getElementById('fullscreen-enter-icon'),
                fullscreenExitIcon: document.getElementById('fullscreen-exit-icon'),
            };

            let state = null;
            let serverOffset = 0;

            const pad = (value) => String(Math.max(0, Math.floor(value))).padStart(2, '0');
            const officialNow = () => new Date(Date.now() + serverOffset);
            const formatDate = (iso) => new Intl.DateTimeFormat('pt-BR', {
                weekday: 'long',
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                timeZone: state?.timezone || 'America/Sao_Paulo',
            }).format(new Date(iso));

            function setCountdown(ms) {
                const total = Math.max(0, Math.floor(ms / 1000));
                const days = Math.floor(total / 86400);
                const hours = Math.floor((total % 86400) / 3600);
                const minutes = Math.floor((total % 3600) / 60);
                const seconds = total % 60;

                els.days.textContent = pad(days);
                els.hours.textContent = pad(hours);
                els.minutes.textContent = pad(minutes);
                els.seconds.textContent = pad(seconds);
            }

            function render() {
                if (!state) return;

                const now = officialNow();
                const start = new Date(state.start_at);
                const end = new Date(state.end_at);
                const totalDuration = Math.max(1, end - start);

                els.eventName.textContent = state.event_name;
                els.startLabel.textContent = formatDate(state.start_at);
                els.endLabel.textContent = formatDate(state.end_at);
                els.progressArea.classList.add('hidden');
                els.finalMessage.classList.add('hidden');

                if (now < start) {
                    els.headline.textContent = state.before_start_text;
                    els.subheadline.textContent = 'Horário oficial de Brasília';
                    els.status.textContent = 'Aguardando início';
                    els.status.className = 'mb-6 inline-flex items-center gap-3 rounded-full border border-[#2EC4B6]/40 bg-[#073B4C]/80 px-5 py-2 text-sm font-black uppercase text-[#2EC4B6] shadow-lg shadow-[#2EC4B6]/10';
                    setCountdown(start - now);
                    return;
                }

                if (now < end) {
                    const progress = Math.min(100, Math.max(0, ((now - start) / totalDuration) * 100));
                    els.headline.textContent = state.running_text;
                    els.subheadline.textContent = 'Faltam para o encerramento';
                    els.status.textContent = 'AO VIVO';
                    els.status.className = 'mb-6 inline-flex items-center gap-3 rounded-full border border-[#FF6B35]/50 bg-[#FF6B35]/15 px-5 py-2 text-sm font-black uppercase text-[#FF6B35] shadow-lg shadow-[#FF6B35]/10';
                    els.progressArea.classList.remove('hidden');
                    els.progressBar.style.width = `${progress.toFixed(1)}%`;
                    els.progressLabel.textContent = `${Math.round(progress)}%`;
                    setCountdown(end - now);
                    return;
                }

                els.headline.textContent = state.finished_text;
                els.subheadline.textContent = 'Contador encerrado';
                els.status.textContent = 'Encerrado';
                els.status.className = 'mb-6 inline-flex items-center gap-3 rounded-full border border-white/20 bg-white/10 px-5 py-2 text-sm font-black uppercase text-white shadow-lg shadow-black/10';
                els.finalMessage.classList.remove('hidden');
                setCountdown(0);
            }

            async function sync() {
                try {
                    const response = await fetch(endpoint, { headers: { Accept: 'application/json' } });
                    state = await response.json();
                    serverOffset = new Date(state.server_now).getTime() - Date.now();
                    render();
                } catch (error) {
                    console.error('Falha ao sincronizar o contador.', error);
                }
            }

            function updateFullscreenButton() {
                const active = Boolean(document.fullscreenElement);

                els.fullscreenLabel.textContent = active ? 'Sair da tela cheia' : 'Tela cheia';
                els.fullscreenEnterIcon.classList.toggle('hidden', active);
                els.fullscreenExitIcon.classList.toggle('hidden', !active);
            }

            els.fullscreen.addEventListener('click', async () => {
                if (!document.fullscreenElement) {
                    await document.documentElement.requestFullscreen();
                } else {
                    await document.exitFullscreen();
                }
            });
            document.addEventListener('fullscreenchange', updateFullscreenButton);

            sync();
            updateFullscreenButton();
            setInterval(render, 1000);
            setInterval(sync, 5000);
        })();
    </script>
</div>
