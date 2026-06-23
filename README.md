# Contador Oficial Hackathon 2026

Sistema Laravel para exibir e administrar o contador oficial do Hackathon 2026.

## Objetivo

Entregar uma tela pública de contador em tempo real, adequada para telão/projetor, e um painel administrativo simples para ajustar datas, textos e sincronização do horário.

## Tecnologias

- Laravel 12
- Livewire 3
- Blade
- Tailwind CSS
- TallStackUI
- MySQL ou SQLite, conforme o `.env`
- Autenticacao Laravel/Fortify com sessao

## Instalar e Rodar

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
npm run build
php artisan serve
```

Durante desenvolvimento, voce tambem pode usar:

```bash
npm run dev
php artisan serve
```

## Credenciais Admin

- URL: `/admin`
- E-mail: `admin@contador.com`
- Senha: `Contador1!`

O seeder usa `updateOrCreate`, então rodar os seeders novamente não cria admins duplicados.

## Rotas

- `GET /`: tela publica do contador, sem login.
- `GET /admin`: login administrativo ou painel, se autenticado.
- `GET /api/countdown-state`: estado oficial do contador para sincronização.

## Datas Padrao

- Inicio: `2026-07-04 09:00:00`
- Termino: `2026-07-05 15:00:00`
- Fuso horário: `America/Sao_Paulo`

## Sincronizacao de Horario

A tela pública busca `/api/countdown-state` ao carregar e recebe o horário atual do servidor, início, término, fuso horário, versão de sincronização e última sincronização.

No navegador, o contador calcula um offset entre o horário do servidor e `Date.now()`. A cada segundo, ele usa `Date.now() + offset` para atualizar o contador. A cada 5 segundos, busca novamente o endpoint para corrigir drift e refletir alterações feitas no admin sem recarregar a página.

O botão "Sincronizar horário" no painel incrementa `sync_version` e atualiza `last_synced_at`, sem alterar as datas do evento.

## Estrutura Tecnica

- `app/Models/CountdownSetting.php`: modelo e defaults do contador.
- `app/Livewire/Countdown/PublicCountdown.php`: tela publica.
- `app/Livewire/Admin/AdminPanel.php`: login, painel e acoes administrativas.
- `app/Http/Controllers/CountdownStateController.php`: endpoint JSON de sincronização.
- `database/migrations/*countdown_settings*`: tabela de configuracoes.
- `database/migrations/*add_is_admin*`: flag administrativa em `users`.
- `database/seeders/AdminUserSeeder.php`: usuário administrador.
- `database/seeders/CountdownSettingSeeder.php`: configuração padrão do evento.

## Validacao

Depois de instalar, valide:

```bash
php artisan migrate:fresh --seed
php artisan test
npm run build
```

Abra `/` para a tela publica e `/admin` para entrar com as credenciais acima.

## Melhorias Futuras

- Historico de alteracoes do contador.
- Multiplo perfil administrativo com auditoria.
- Temas visuais alternativos para diferentes eventos.
- Testes browser com Laravel Dusk ou Playwright para validar telao e console.
"# countdown-hackathon" 
