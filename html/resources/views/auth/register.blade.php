<x-guest-layout>
    <div>
        <p class="brand-kicker text-brand-ink/60">Alta de usuario</p>
        <h1 class="mt-2 text-4xl text-brand-ink">Crear acceso</h1>
        <p class="mt-2 text-sm leading-relaxed text-brand-ink/70">
            Registra un nuevo usuario para administrar menus, reservas y reporting.
        </p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="mt-8 space-y-4">
        @csrf

        <div>
            <x-input-label for="name" :value="__('Nombre')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Correo electronico')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Contrasena')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password_confirmation" :value="__('Confirmar contrasena')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex flex-col gap-4 pt-2">
            <a class="text-sm font-semibold text-brand-ember transition hover:text-brand-ember-dark" href="{{ route('login') }}">
                {{ __('Ya tienes una cuenta?') }}
            </a>

            <x-primary-button class="w-full justify-center">
                {{ __('Crear usuario') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
