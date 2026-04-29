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
