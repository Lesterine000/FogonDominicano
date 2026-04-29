<x-guest-layout>
    <x-auth-session-status class="mb-4 rounded-2xl border border-brand-sage/30 bg-brand-sage/10 px-4 py-3 text-sm text-brand-ink" :status="session('status')" />

    <div>
        <p class="brand-kicker text-brand-ink/60">Panel administrativo</p>
        <h1 class="mt-2 text-4xl text-brand-ink">Iniciar sesion</h1>
        <p class="mt-2 text-sm leading-relaxed text-brand-ink/70">
            Accede para gestionar menus, pedidos y ventas.
        </p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-4">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Correo o usuario')" />
            <x-text-input id="email" class="block mt-1 w-full" type="text" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Contrasena')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="block">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-brand-mist text-brand-ember shadow-sm focus:ring-brand-ember" name="remember">
                <span class="ms-2 text-sm text-brand-ink/70">{{ __('Recordarme') }}</span>
            </label>
        </div>

        <div class="flex flex-col gap-4 pt-2">
            @if (Route::has('password.request'))
                <a class="text-sm font-semibold text-brand-ember transition hover:text-brand-ember-dark" href="{{ route('password.request') }}">
                    {{ __('Olvidaste tu contrasena?') }}
                </a>
            @endif

            <x-primary-button class="w-full justify-center">
                {{ __('Entrar') }}
            </x-primary-button>
        </div>

        <div class="mt-6 border-t border-brand-ink/10 pt-6">
            <a href="{{ route('google.login') }}" class="inline-flex w-full items-center justify-center rounded-full border border-brand-ink/10 bg-white px-4 py-3 text-xs font-semibold uppercase tracking-[0.18em] text-brand-ink shadow-sm transition hover:border-brand-ember/30 hover:bg-brand-cream/80">
                <img src="https://www.gstatic.com/images/branding/product/1x/gsa_512dp.png" alt="Google" class="mr-2 h-4 w-4">
                Entrar con Google
            </a>
        </div>
    </form>
</x-guest-layout>
