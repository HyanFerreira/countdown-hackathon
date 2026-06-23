<div class="dark min-h-screen bg-[#101820] text-[#F8FAFC]">
    @guest
        <div class="flex min-h-screen items-center justify-center bg-[#101820] px-4">
            <div class="w-full max-w-sm rounded-lg border border-white/10 bg-[#0f2230] p-6 shadow-2xl shadow-black/40">
                <p class="text-sm font-bold uppercase text-[#2EC4B6]">Admin</p>
                <h1 class="mt-2 text-3xl font-black text-white">Contador Hackathon 2026</h1>
                <p class="mt-2 text-sm text-slate-300">Entre para configurar o contador oficial.</p>

                <form wire:submit="login" class="mt-8 space-y-5">
                    <div>
                        <x-ts-input id="email" label="E-mail" type="email" wire:model="email" icon="envelope" placeholder="user@mail.com" autocomplete="off" />
                    </div>

                    <div>
                        <x-ts-password id="password" label="Senha" wire:model="password" icon="lock-closed" placeholder="Digite sua senha" autocomplete="off" />
                    </div>

                    <x-ts-checkbox id="remember_email" label="Lembrar e-mail" wire:model="remember_email" />

                    <x-ts-button type="submit" text="Entrar no painel" color="primary" class="w-full cursor-pointer justify-center font-semibold" />
                </form>
            </div>
        </div>
    @else
        <div
            x-data="{
                modal: null,
                menuOpen: false,
                settingsOpen: false,
                darkTheme: true,
                init() {
                    this.darkTheme = localStorage.getItem('admin-theme') !== 'light';
                    this.syncTheme();
                },
                syncTheme() {
                    localStorage.setItem('admin-theme', this.darkTheme ? 'dark' : 'light');
                    document.body.classList.toggle('admin-light-theme', !this.darkTheme);
                },
                open(action, title, description, confirmText) {
                    this.modal = { action, title, description, confirmText };
                },
                openSettings() {
                    this.settingsOpen = true;
                    this.menuOpen = false;
                },
                closeFloatingSelects() {
                    window.dispatchEvent(new CustomEvent('select:timezone-settings'));
                    document.querySelectorAll('[data-floating]').forEach((floating) => {
                        if (floating.querySelector('[dusk=\'tallstackui_select_options\']')) {
                            floating.style.display = 'none';
                        }
                    });
                },
                closeSettings() {
                    this.closeFloatingSelects();
                    setTimeout(() => {
                        this.settingsOpen = false;
                    }, 60);
                },
                close() {
                    this.modal = null;
                },
                closeAll() {
                    this.closeFloatingSelects();
                    this.modal = null;
                    this.menuOpen = false;
                    this.settingsOpen = false;
                },
                confirm() {
                    const action = this.modal.action;
                    this.close();
                    this.$wire.call(action);
                }
            }"
            x-on:keydown.escape.window="closeAll()"
            class="admin-panel min-h-screen"
            x-bind:class="darkTheme ? 'dark admin-dark' : 'admin-light'"
        >
            <header class="border-b border-white/10 bg-[#0f2230]">
                <div class="mx-auto flex max-w-7xl flex-col gap-4 px-4 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8">
                    <div>
                        <p class="text-sm font-bold uppercase text-[#2EC4B6]">Painel administrativo</p>
                        <h1 class="text-3xl font-black text-white">Configurar contador</h1>
                    </div>

                    <div class="relative" x-on:click.outside="menuOpen = false">
                        <button
                            type="button"
                            x-on:click="menuOpen = !menuOpen"
                            class="inline-flex cursor-pointer items-center gap-3 rounded-md border border-white/15 px-3 py-2 text-sm font-semibold text-slate-100 transition hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-[#2ec4b6] focus:ring-offset-2 focus:ring-offset-[#0f2230]"
                            aria-haspopup="menu"
                            x-bind:aria-expanded="menuOpen"
                        >
                            <span class="inline-flex size-8 items-center justify-center rounded-full bg-[#2ec4b6]/15 text-[#2ec4b6]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M20 21a8 8 0 0 0-16 0" />
                                    <circle cx="12" cy="7" r="4" />
                                </svg>
                            </span>
                            <span>{{ auth()->user()->name }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-slate-400 transition" x-bind:class="{ 'rotate-180': menuOpen }" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="m6 9 6 6 6-6" />
                            </svg>
                        </button>

                        <div
                            x-cloak
                            x-show="menuOpen"
                            class="absolute right-0 z-40 mt-2 w-64 rounded-lg border border-white/10 bg-[#0f2230] p-2 shadow-2xl shadow-black/40"
                            role="menu"
                        >
                            <button
                                type="button"
                                x-on:click="openSettings()"
                                class="flex w-full cursor-pointer items-center gap-3 rounded-md px-3 py-2 text-left text-sm font-semibold text-slate-100 transition hover:bg-white/10"
                                role="menuitem"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-[#2ec4b6]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.38a2 2 0 0 0-.73-2.73l-.15-.09a2 2 0 0 1-1-1.74v-.51a2 2 0 0 1 1-1.72l.15-.1a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2Z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                                Configurações
                            </button>

                            <button
                                type="button"
                                x-on:click="darkTheme = !darkTheme; syncTheme()"
                                class="flex w-full cursor-pointer items-center justify-between gap-3 rounded-md px-3 py-2 text-left text-sm font-semibold text-slate-100 transition hover:bg-white/10"
                                role="menuitem"
                            >
                                <span class="inline-flex items-center gap-3">
                                    <svg x-show="darkTheme" xmlns="http://www.w3.org/2000/svg" class="size-4 text-[#2ec4b6]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M12 3a6 6 0 0 0 9 7 9 9 0 1 1-9-7Z" />
                                    </svg>
                                    <svg x-cloak x-show="!darkTheme" xmlns="http://www.w3.org/2000/svg" class="size-4 text-[#f4e3c1]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <circle cx="12" cy="12" r="4" />
                                        <path d="M12 2v2" />
                                        <path d="M12 20v2" />
                                        <path d="m4.93 4.93 1.41 1.41" />
                                        <path d="m17.66 17.66 1.41 1.41" />
                                        <path d="M2 12h2" />
                                        <path d="M20 12h2" />
                                        <path d="m6.34 17.66-1.41 1.41" />
                                        <path d="m19.07 4.93-1.41 1.41" />
                                    </svg>
                                    Tema
                                </span>
                                <span class="flex h-6 w-11 items-center rounded-full border border-white/10 bg-white/10 p-0.5 transition">
                                    <span class="size-5 rounded-full bg-[#2ec4b6] transition" x-bind:class="{ 'translate-x-5': !darkTheme }"></span>
                                </span>
                            </button>

                            <button
                                type="button"
                                x-on:click="open('logout', 'Sair do painel?', 'Sua sessão administrativa será encerrada.', 'Sair'); menuOpen = false"
                                class="mt-1 flex w-full cursor-pointer items-center gap-3 rounded-md border border-red-400/20 bg-red-500/10 px-3 py-2 text-left text-sm font-semibold text-red-200 transition hover:border-red-400/30 hover:bg-red-500/15 hover:text-red-100"
                                role="menuitem"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-red-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="m16 17 5-5-5-5" />
                                    <path d="M21 12H9" />
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                                </svg>
                                Sair
                            </button>
                        </div>
                    </div>
                </div>
            </header>

            <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
                <section
                    wire:key="admin-clock-{{ $setting?->sync_version }}-{{ $setting?->updated_at?->timestamp }}"
                    class="grid gap-4 md:grid-cols-4"
                    x-data="{
                        timezone: @js($setting?->timezone),
                        serverNow: Date.parse(@js($serverNow?->toIso8601String())),
                        syncedAt: performance.now(),
                        startAt: Date.parse(@js($setting?->startInTimezone()?->toIso8601String())),
                        endAt: Date.parse(@js($setting?->endInTimezone()?->toIso8601String())),
                        lastSyncedAt: Date.parse(@js($setting?->lastSyncInTimezone()?->toIso8601String())),
                        nowLabel: '',
                        lastSyncLabel: '',
                        status: @js($status),
                        progress: @js($progress),
                        timer: null,
                        formatter: null,
                        init() {
                            this.formatter = new Intl.DateTimeFormat('pt-BR', {
                                timeZone: this.timezone,
                                day: '2-digit',
                                month: '2-digit',
                                year: 'numeric',
                                hour: '2-digit',
                                minute: '2-digit',
                                second: '2-digit',
                                hour12: false,
                            });
                            this.tick();
                            this.timer = setInterval(() => this.tick(), 1000);
                        },
                        destroy() {
                            clearInterval(this.timer);
                        },
                        currentTime() {
                            return this.serverNow + (performance.now() - this.syncedAt);
                        },
                        format(timestamp) {
                            if (!timestamp || Number.isNaN(timestamp)) {
                                return '--';
                            }

                            return this.formatter.format(new Date(timestamp)).replace(',', '');
                        },
                        tick() {
                            const now = this.currentTime();

                            this.nowLabel = this.format(now);
                            this.lastSyncLabel = this.format(this.lastSyncedAt);

                            if (now < this.startAt) {
                                this.status = 'Antes do início';
                                this.progress = 0;
                                return;
                            }

                            if (now < this.endAt) {
                                const duration = Math.max(1, this.endAt - this.startAt);

                                this.status = 'Em andamento';
                                this.progress = Math.min(100, Math.max(0, Math.round(((now - this.startAt) / duration) * 100)));
                                return;
                            }

                            this.status = 'Encerrado';
                            this.progress = 100;
                        }
                    }"
                >
                    <div class="rounded-lg border border-white/10 bg-white/[0.06] p-5 shadow-sm shadow-black/20">
                        <p class="text-sm font-bold uppercase text-slate-400">Status</p>
                        <p class="mt-2 text-2xl font-black text-white" x-text="status">{{ $status }}</p>
                    </div>
                    <div class="rounded-lg border border-white/10 bg-white/[0.06] p-5 shadow-sm shadow-black/20">
                        <p class="text-sm font-bold uppercase text-slate-400">Servidor</p>
                        <p class="mt-2 text-xl font-black text-white" x-text="nowLabel">{{ $serverNow?->format('d/m/Y H:i:s') }}</p>
                        <p class="text-sm text-slate-400">{{ $setting?->timezone }}</p>
                    </div>
                    <div class="rounded-lg border border-white/10 bg-white/[0.06] p-5 shadow-sm shadow-black/20">
                        <p class="text-sm font-bold uppercase text-slate-400">Última sincronização</p>
                        <p class="mt-2 text-xl font-black text-white" x-text="lastSyncLabel">{{ $setting?->lastSyncInTimezone()->format('d/m/Y H:i:s') }}</p>
                        <p class="text-sm text-slate-400">Versão {{ $setting?->sync_version }}</p>
                    </div>
                    <div class="rounded-lg border border-white/10 bg-white/[0.06] p-5 shadow-sm shadow-black/20">
                        <p class="text-sm font-bold uppercase text-slate-400">Progresso</p>
                        <p class="mt-2 text-2xl font-black text-white"><span x-text="progress">{{ $progress }}</span>%</p>
                        <div class="mt-3 h-2 overflow-hidden rounded-full bg-white/10">
                            <div class="h-full rounded-full bg-[#2EC4B6]" x-bind:style="`width: ${progress}%`" style="width: {{ $progress }}%"></div>
                        </div>
                    </div>
                </section>

                <section class="mt-8 grid gap-6 lg:grid-cols-[1fr_360px]">
                    <form class="rounded-lg border border-white/10 bg-white/[0.06] p-6 shadow-sm shadow-black/20">
                        <h2 class="text-xl font-black text-white">Textos do contador</h2>

                        <div class="mt-6 grid gap-5 md:grid-cols-2">
                            <div class="md:col-span-2">
                                <x-ts-input id="before_start_text" label="Texto antes do início" wire:model="before_start_text" placeholder="Faltam para o início do Hackathon 2026" />
                            </div>

                            <div class="md:col-span-2">
                                <x-ts-input id="running_text" label="Texto durante o evento" wire:model="running_text" placeholder="Hackathon 2026 em andamento" />
                            </div>

                            <div class="md:col-span-2">
                                <x-ts-input id="finished_text" label="Texto após o encerramento" wire:model="finished_text" placeholder="Hackathon 2026 encerrado" />
                            </div>
                        </div>

                        <div class="mt-20 flex flex-col gap-3 sm:flex-row sm:justify-end">
                            <button
                                type="button"
                                x-on:click="open('restoreDefaults', 'Restaurar padrões?', 'As datas e textos atuais serão substituídos pelos valores oficiais do Hackathon 2026.', 'Restaurar')"
                                class="inline-flex cursor-pointer items-center justify-center rounded-md border border-[#2ec4b6]/70 px-4 py-2 text-sm font-semibold text-[#2ec4b6] transition-colors hover:bg-[#2ec4b6]/10 focus:outline-none focus:ring-2 focus:ring-[#2ec4b6] focus:ring-offset-2 focus:ring-offset-[#101820]"
                            >
                                Restaurar datas padrão
                            </button>
                            <button
                                type="button"
                                x-on:click="open('save', 'Salvar configurações?', 'As alterações serão publicadas no contador em até alguns segundos.', 'Salvar')"
                                class="inline-flex cursor-pointer items-center justify-center rounded-md bg-[#169d93] px-4 py-2 text-sm font-semibold text-white transition-colors hover:bg-[#2ec4b6] focus:outline-none focus:ring-2 focus:ring-[#2ec4b6] focus:ring-offset-2 focus:ring-offset-[#101820]"
                            >
                                Salvar configurações
                            </button>
                        </div>
                    </form>

                    <aside class="space-y-6">
                        <div class="rounded-lg border border-white/10 bg-white/[0.06] p-6 shadow-sm shadow-black/20">
                            <h2 class="text-xl font-black text-white">Sincronização</h2>
                            <p class="mt-3 text-sm text-slate-300">
                                Atualiza a versão de sincronização e o horário de referência sem alterar início ou término.
                            </p>
                            <button
                                type="button"
                                x-on:click="open('syncClock', 'Sincronizar horário?', 'Isso força os clientes abertos a recalcular o contador com o horário do servidor.', 'Sincronizar')"
                                class="mt-5 inline-flex w-full cursor-pointer items-center justify-center rounded-md bg-[#169d93] px-4 py-2 text-sm font-semibold text-white transition-colors hover:bg-[#2ec4b6] focus:outline-none focus:ring-2 focus:ring-[#2ec4b6] focus:ring-offset-2 focus:ring-offset-[#101820]"
                            >
                                Sincronizar horário
                            </button>
                        </div>

                        <div class="rounded-lg border border-white/10 bg-white/[0.06] p-6 shadow-sm shadow-black/20">
                            <h2 class="text-xl font-black text-white">Preview</h2>
                            <dl class="mt-4 space-y-3 text-sm">
                                <div>
                                    <dt class="font-bold text-slate-400">Antes</dt>
                                    <dd class="text-slate-100">{{ $before_start_text }}</dd>
                                </div>
                                <div>
                                    <dt class="font-bold text-slate-400">Durante</dt>
                                    <dd class="text-slate-100">{{ $running_text }}</dd>
                                </div>
                                <div>
                                    <dt class="font-bold text-slate-400">Depois</dt>
                                    <dd class="text-slate-100">{{ $finished_text }}</dd>
                                </div>
                            </dl>
                        </div>
                    </aside>
                </section>
            </main>

            <div
                x-cloak
                x-show="settingsOpen"
                x-on:click.self="closeSettings()"
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 px-4"
            >
                <div
                    class="max-h-[calc(100vh-2rem)] w-full max-w-lg overflow-y-auto rounded-lg border border-white/10 bg-[#0f2230] p-6 shadow-2xl shadow-black/50"
                    role="dialog"
                    aria-modal="true"
                >
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h2 class="text-2xl font-black text-white">Configurações</h2>
                            <p class="mt-2 text-sm leading-6 text-slate-300">Ajustes gerais do contador oficial.</p>
                        </div>

                        <button
                            type="button"
                            x-on:click="closeSettings()"
                            class="inline-flex cursor-pointer items-center justify-center rounded-md p-2 text-slate-400 transition hover:bg-white/10 hover:text-white focus:outline-none focus:ring-2 focus:ring-[#2ec4b6] focus:ring-offset-2 focus:ring-offset-[#0f2230]"
                            aria-label="Fechar configurações"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M18 6 6 18" />
                                <path d="m6 6 12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="mt-6 grid gap-5 md:grid-cols-2">
                        <div class="md:col-span-2">
                            <x-ts-input id="event_name" label="Nome do evento" wire:model="event_name" placeholder="Hackathon 2026" />
                        </div>

                        <x-ts-date id="starts_date" label="Data de início" wire:model.live="starts_date" format="YYYY-MM-DD" placeholder="Selecione a data de início" />

                        <x-ts-time id="starts_time" label="Hora de início" wire:model.live="starts_time" format="24" placeholder="09:00" />

                        <x-ts-date id="ends_date" label="Data de término" wire:model.live="ends_date" format="YYYY-MM-DD" placeholder="Selecione a data de término" />

                        <x-ts-time id="ends_time" label="Hora de término" wire:model.live="ends_time" format="24" placeholder="15:00" />

                        <div class="md:col-span-2">
                            <x-ts-select.styled
                                id="timezone"
                                label="Fuso horário"
                                wire:model="timezone"
                                :options="$timezones"
                                placeholder="Selecione o fuso horário"
                                :placeholders="['search' => 'Buscar fuso horário', 'empty' => 'Nenhum fuso encontrado']"
                                required
                                close="timezone-settings"
                            />
                        </div>
                    </div>

                    <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                        <button
                            type="button"
                            x-on:click="closeSettings()"
                            class="inline-flex cursor-pointer items-center justify-center rounded-md border border-white/25 px-4 py-2 text-sm font-semibold text-slate-100 transition-colors hover:border-white/35 hover:bg-white/10 hover:text-white focus:outline-none focus:ring-2 focus:ring-[#2ec4b6] focus:ring-offset-2 focus:ring-offset-[#0f2230]"
                        >
                            Cancelar
                        </button>
                        <button
                            type="button"
                            x-on:click="$wire.save(); closeSettings()"
                            class="inline-flex cursor-pointer items-center justify-center rounded-md bg-[#169d93] px-4 py-2 text-sm font-semibold text-white transition-colors hover:bg-[#2ec4b6] focus:outline-none focus:ring-2 focus:ring-[#2ec4b6] focus:ring-offset-2 focus:ring-offset-[#0f2230]"
                        >
                            Salvar configurações
                        </button>
                    </div>
                </div>
            </div>

            <div
                x-cloak
                x-show="modal"
                x-on:click.self="close()"
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 px-4"
            >
                <div
                    class="w-full max-w-md rounded-lg border border-white/10 bg-[#0f2230] p-6 shadow-2xl shadow-black/50"
                    role="dialog"
                    aria-modal="true"
                >
                    <h2 class="text-2xl font-black text-white" x-text="modal?.title"></h2>
                    <p class="mt-3 text-sm leading-6 text-slate-300" x-text="modal?.description"></p>

                    <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                        <button
                            type="button"
                            x-on:click="close()"
                            class="inline-flex cursor-pointer items-center justify-center rounded-md border border-white/25 px-4 py-2 text-sm font-semibold text-slate-100 transition-colors hover:border-white/35 hover:bg-white/10 hover:text-white focus:outline-none focus:ring-2 focus:ring-[#2ec4b6] focus:ring-offset-2 focus:ring-offset-[#0f2230]"
                        >
                            Cancelar
                        </button>
                        <button
                            type="button"
                            x-on:click="confirm()"
                            class="inline-flex cursor-pointer items-center justify-center rounded-md bg-[#169d93] px-4 py-2 text-sm font-semibold text-white transition-colors hover:bg-[#2ec4b6] focus:outline-none focus:ring-2 focus:ring-[#2ec4b6] focus:ring-offset-2 focus:ring-offset-[#0f2230]"
                        >
                            <span x-text="modal?.confirmText"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endguest
</div>
