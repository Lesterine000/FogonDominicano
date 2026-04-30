<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'El Fogon Dominicano') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="brand-shell font-sans antialiased">
        @if (session('error'))
            <div
                x-data="{ show: true }"
                x-init="setTimeout(() => (show = false), 6000)"
                x-show="show"
                x-cloak
                x-transition.opacity.duration.200ms
                class="fixed inset-x-0 top-6 z-50 flex justify-center px-4"
                role="alert"
                aria-live="assertive"
            >
                <div class="flex w-full max-w-xl items-start gap-4 rounded-[1.75rem] border border-brand-ember/40 bg-brand-night/90 px-5 py-4 text-brand-cream shadow-glow backdrop-blur">
                    <div class="mt-0.5 inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-brand-ember/20 text-brand-ember">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86l-7.4 12.81A2 2 0 004.62 20h14.76a2 2 0 001.73-3.33l-7.4-12.81a2 2 0 00-3.46 0z" />
                        </svg>
                    </div>

                    <div class="flex-1">
                        <p class="text-xs font-semibold uppercase tracking-[0.28em] text-brand-mist">
                            {{ session('error_title', 'Aviso') }}
                        </p>
                        <p class="mt-2 text-sm font-semibold leading-relaxed text-brand-cream">
                            {{ session('error') }}
                        </p>
                    </div>

                    <button
                        type="button"
                        class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-white/10 bg-white/5 text-brand-cream transition hover:border-brand-ember/40 hover:text-brand-ember"
                        @click="show = false"
                        aria-label="Cerrar notificacion"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        @endif

        <div class="relative flex min-h-screen items-center justify-center px-4 py-10">
            <div class="pointer-events-none absolute inset-0 brand-hero-haze opacity-70"></div>

            <div class="relative grid w-full max-w-5xl gap-8 lg:grid-cols-[1.05fr_0.95fr] lg:items-center">
                <section class="hidden lg:block">
                    <div class="brand-panel p-10 xl:p-12">
                        <p class="brand-kicker">Acceso privado</p>
                        <h1 class="mt-4 text-6xl leading-none text-brand-cream xl:text-7xl">
                            El Fogon Dominicano
                        </h1>
                        <p class="mt-6 max-w-xl text-lg leading-relaxed text-brand-mist">
                            Menus del dia, reservas y ventas.
                        </p>

                        <div class="mt-8 flex flex-wrap gap-3">
                            <span class="brand-badge border-brand-ember/40 bg-brand-ember/10 text-brand-cream">Cocina criolla</span>
                            <span class="brand-badge border-brand-gold/40 bg-brand-gold/10 text-brand-cream">Estilo editorial</span>
                            <span class="brand-badge border-brand-cream/20 bg-white/5 text-brand-cream">Euro pricing</span>
                        </div>
                    </div>
                </section>

                <section class="brand-panel-light mx-auto w-full max-w-md px-8 py-8 sm:px-10">
                    <div class="flex justify-center">
                        <div class="brand-logo-mark rounded-full px-8 py-7">
                            <x-application-logo class="text-brand-ink" />
                        </div>
                    </div>

                    <div class="mt-8">
                        {{ $slot }}
                    </div>
                </section>
            </div>
        </div>
    </body>
</html>
