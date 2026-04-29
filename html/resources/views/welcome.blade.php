@php
    use Illuminate\Support\Str;

    $activeDish = $dish ?? $currentMenu ?? null;
    $activeDishImage = $activeDish?->image;
    $activeDishName = $activeDish?->name;

    $localMediaUrl = function (?string $path, string $fallback): string {
        $source = filled($path) ? ltrim($path, '/') : $fallback;

        if (Str::startsWith($source, ['images/', 'storage/'])) {
            return asset($source);
        }

        return asset('images/' . $source);
    };

    $focusClass = function (?string $focus): string {
        $normalized = Str::of((string) $focus)
            ->replace(',', ' ')
            ->lower()
            ->squish()
            ->value();

        return match ($normalized) {
            'left top' => 'object-left-top',
            'left center', 'left' => 'object-left',
            'left bottom' => 'object-left-bottom',
            'center top', 'top center', 'top' => 'object-top',
            'center center', 'center' => 'object-center',
            'center bottom', 'bottom center', 'bottom' => 'object-bottom',
            'right top' => 'object-right-top',
            'right center', 'right' => 'object-right',
            'right bottom' => 'object-right-bottom',
            default => 'object-center',
        };
    };

    $heroImage = $localMediaUrl(
        config('restaurant.home_hero_image'),
        'images/Gemini_Generated_Image_eklv9oeklv9oeklv.png'
    );

    $menuDishImage = $activeDishImage
        ? asset('storage/' . $activeDishImage)
        : 'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?q=80&w=1400&auto=format&fit=crop';

    $chefImage = $localMediaUrl(
        config('restaurant.home_chef_image'),
        'images/veronica.jpeg'
    );

    $heroImageFocusClass = $focusClass(config('restaurant.home_hero_focus', 'center center'));
    $chefImageFocusClass = $focusClass(config('restaurant.home_chef_focus', 'center top'));
    $customOrderUrl = config('restaurant.custom_order_url');
    $chefName = config('restaurant.home_chef_name', 'Veronica Guerrero');
    $chefRole = config('restaurant.home_chef_role', 'Veronica Guerrero');
@endphp

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>El Fogon Dominicano | Sabor Auténtico en cada ración</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="brand-shell home-reference-copy antialiased">
    <div class="relative">
        <nav class="home-topbar">
            <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-3 px-4 py-3 sm:px-6 lg:px-8">
                <a href="#inicio" class="home-topbar-brand" aria-label="Ir al inicio">
                    <span class="home-topbar-brand-main">fogon dominicano</span>
                    <span class="home-topbar-brand-sub">Cocina criolla</span>
                </a>

                <div class="home-topbar-nav">
                    <a href="#inicio" class="home-topbar-link">Inicio</a>
                    <a href="#menu-del-dia" class="home-topbar-link">Menu del dia</a>
                    <a href="#pedidos-personalizados" class="home-topbar-link home-topbar-link-cta">Pedidos personalizados</a>
                </div>
            </div>
        </nav>

        <section id="inicio" class="home-anchor-offset relative min-h-screen overflow-hidden">
            <img
                src="{{ $heroImage }}"
                alt="{{ $activeDishName ? 'Imagen principal de ' . $activeDishName : 'Imagen principal de El Fogon Dominicano' }}"
                class="absolute inset-0 h-full w-full object-cover {{ $heroImageFocusClass }}"
            >
            <div class="absolute inset-0 bg-gradient-to-b from-black/35 via-black/10 to-transparent"></div>
            <div class="absolute inset-0 home-screen-fade"></div>

            <div class="relative flex min-h-screen flex-col">
                <header class="px-4 pb-6 pt-24 sm:px-6 sm:pt-28 lg:px-10 lg:pt-32">
                    <div class="mx-auto flex max-w-7xl items-center justify-between">
                        <a href="{{ route('home') }}">
                            <x-application-logo class="text-brand-cream" />
                        </a>

                    </div>
                </header>

                <div class="mt-auto px-4 pb-16 sm:px-6 sm:pb-20 lg:px-10 lg:pb-24">
                    <div class="mx-auto max-w-7xl">
                        <div class="max-w-4xl">
                            <p class="brand-kicker text-white/75"></p>
                            <h1 class="home-reference-heading mt-5 text-5xl leading-[0.9] text-white sm:text-6xl lg:text-7xl">
                                Sabor auténtico en cada ración.
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="menu-del-dia" class="home-anchor-offset relative home-stage-cream py-16 sm:py-20 lg:min-h-screen lg:flex lg:items-center">
            <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">
                @if (session('success'))
                    <div class="mx-auto mb-6 max-w-5xl rounded-[1.75rem] border border-brand-forest/20 bg-white px-6 py-5 text-brand-ink shadow-[0_16px_35px_rgba(32,40,58,0.06)]">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error') || $errors->any())
                    <div class="mx-auto mb-6 max-w-5xl rounded-[1.75rem] border border-brand-ember/20 bg-white px-6 py-5 text-brand-ink shadow-[0_16px_35px_rgba(32,40,58,0.06)]">
                        <p class="font-semibold">{{ session('error', 'Revisa los datos del formulario para continuar.') }}</p>
                        @if ($errors->any())
                            <ul class="mt-3 space-y-1 text-sm text-brand-ink/70">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @endif

                <div class="mx-auto max-w-6xl text-center">
                    <p class="brand-kicker text-brand-forest"></p>
                    <h2 class="home-reference-heading mt-3 text-4xl text-brand-ink sm:text-5xl lg:text-6xl">
                        Menú del día
                    </h2>
                </div>

                @if($activeDish && isset($activeDish->available_servings) && $activeDish->available_servings > 0)
                    <div class="home-showcase-card mx-auto mt-10 max-w-6xl">
                        <div class="grid lg:grid-cols-[0.95fr_1.05fr]">
                            <div class="home-showcase-media min-h-[22rem] lg:min-h-full">
                                <img
                                    src="{{ $menuDishImage }}"
                                    alt="{{ $activeDish->name }}"
                                    class="h-full w-full object-cover"
                                >

                                <div class="absolute inset-x-0 bottom-0 p-5 sm:p-6">
                                    <div class="home-glass-note rounded-[1.5rem] border border-white/55 px-5 py-4 text-left text-brand-ink">
                                        <p class="brand-kicker text-brand-ink/55">Menu de hoy</p>
                                        <p class="mt-2 text-2xl font-semibold text-brand-ink">{{ $activeDish->name }}</p>
                                        <p class="mt-1 text-sm text-brand-ink/70">{{ $activeDish->service_date->format('d/m/Y') }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="p-8 sm:p-10 lg:p-12">
                                <p class="brand-kicker text-brand-forest">Reserva</p>
                                <h3 class="home-reference-heading mt-4 text-4xl text-brand-ink sm:text-5xl">
                                    {{ $activeDish->name }}
                                </h3>
                                <p class="mt-4 max-w-2xl text-base leading-relaxed sm:text-lg" style="color: black !important;">
                                    {{ $activeDish->description ?: 'Cocina criolla...' }}
                                </p>

                                <div class="mt-8 grid gap-3 sm:grid-cols-3">
                                    <div class="home-stat">
                                        <p class="brand-kicker text-brand-ink/55">Precio</p>
                                        <p class="mt-2 text-3xl font-semibold text-brand-ink">@euro($activeDish->price)</p>
                                    </div>
                                    <div class="home-stat">
                                        <p class="brand-kicker text-brand-ink/55">Raciones</p>
                                        <p class="mt-2 text-3xl font-semibold text-brand-forest">{{ $activeDish->available_servings }}</p>
                                    </div>
                                    <div class="home-stat">
                                        <p class="brand-kicker text-brand-ink/55">Recogida</p>
                                        <p class="mt-2 text-xl font-semibold text-brand-ink">
                                            {{ config('restaurant.pickup_time_start') }} - {{ config('restaurant.pickup_time_end') }}
                                        </p>
                                    </div>
                                </div>

                                <div class="mt-8 h-px home-soft-rule"></div>

                                <form action="{{ route('reserve') }}" method="POST" class="mt-8 space-y-4 text-left">
                                    @csrf
                                    <input type="hidden" name="dish_id" value="{{ $activeDish->id }}">

                                    <div class="grid gap-4 sm:grid-cols-2">
                                        <div>
                                            <label class="brand-label text-brand-ink/60" for="customer_name">Nombre completo</label>
                                            <input id="customer_name" type="text" name="customer_name" value="{{ old('customer_name') }}" placeholder="Tu nombre" required class="brand-input border-brand-ink/10 bg-brand-cream/70 text-brand-ink placeholder:text-brand-ink/35 focus:border-brand-forest focus:ring-brand-forest">
                                        </div>
                                        <div>
                                            <label class="brand-label text-brand-ink/60" for="customer_email">Correo electronico</label>
                                            <input id="customer_email" type="email" name="customer_email" value="{{ old('customer_email') }}" placeholder="tu@email.com" required class="brand-input border-brand-ink/10 bg-brand-cream/70 text-brand-ink placeholder:text-brand-ink/35 focus:border-brand-forest focus:ring-brand-forest">
                                        </div>
                                    </div>

                                    <div class="grid gap-4 sm:grid-cols-2">
                                        <div>
                                            <label class="brand-label text-brand-ink/60" for="customer_phone">Telefono de contacto</label>
                                            <input id="customer_phone" type="tel" name="customer_phone" value="{{ old('customer_phone') }}" placeholder="+34 600 000 000" required class="brand-input border-brand-ink/10 bg-brand-cream/70 text-brand-ink placeholder:text-brand-ink/35 focus:border-brand-forest focus:ring-brand-forest">
                                        </div>
                                        <div>
                                            <label class="brand-label text-brand-ink/60" for="pickup_time">Hora de recogida</label>
                                            <input id="pickup_time" type="time" name="pickup_time" value="{{ old('pickup_time', config('restaurant.pickup_time_start')) }}" min="{{ config('restaurant.pickup_time_start') }}" max="{{ config('restaurant.pickup_time_end') }}" required class="brand-input border-brand-ink/10 bg-brand-cream/70 text-brand-ink placeholder:text-brand-ink/35 focus:border-brand-forest focus:ring-brand-forest">
                                        </div>
                                    </div>

                                    <div>
                                        <label class="brand-label text-brand-ink/60" for="quantity">Cantidad</label>
                                        <input id="quantity" type="number" name="quantity" value="{{ old('quantity', 1) }}" min="1" max="{{ $activeDish->available_servings }}" class="brand-input border-brand-ink/10 bg-brand-cream/70 text-brand-ink placeholder:text-brand-ink/35 focus:border-brand-forest focus:ring-brand-forest">
                                    </div>

                                    <label class="flex items-start gap-3 rounded-[1.5rem] border border-brand-ink/10 bg-brand-cream/60 p-4 text-sm text-brand-ink">
                                        <input type="checkbox" name="privacy" value="1" @checked(old('privacy')) required class="mt-1 rounded border-brand-ink/20 bg-white text-brand-forest focus:ring-brand-forest">
                                        <span class="text-brand-ink">Acepto el tratamiento de mis datos para gestionar esta reserva y confirmar el pedido.</span>
                                    </label>

                                    <div class="pt-2">
                                        <button type="submit" class="home-accent-button w-full sm:w-auto">
                                            Reservar ahora
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="home-showcase-card mx-auto mt-10 max-w-6xl">
                        <div class="grid lg:grid-cols-[0.95fr_1.05fr]">
                            <div class="home-showcase-media min-h-[22rem] lg:min-h-full">
                                <img
                                    src="{{ $menuDishImage }}"
                                    alt="{{ $activeDish?->name ?: 'Menu del dia' }}"
                                    class="h-full w-full object-cover"
                                >
                            </div>

                            <div class="flex items-center p-8 sm:p-10 lg:p-12">
                                <div>
                                    <p class="brand-kicker text-brand-forest">Menu del dia</p>
                                    <h3 class="home-reference-heading mt-4 text-4xl text-brand-ink sm:text-5xl">
                                        No mas raciones por hoy
                                    </h3>
                                        <p class="mt-5 max-w-2xl text-base leading-relaxed text-black sm:text-lg">
                                             Vuelve mañana para descubrir el siguiente menú.
                                        </p>

                                    @if ($activeDish)
                                        <div class="mt-7 rounded-[1.75rem] bg-brand-cream/75 px-5 py-4 text-brand-ink">
                                            <p class="brand-kicker text-brand-ink/55">{{ $activeDish->name }}</p>
                                            <p class="mt-2 text-base leading-relaxed text-brand-ink/70">
                                                {{ $activeDish->description ?: 'La ficha del plato seguira visible aunque el servicio del dia se haya agotado.' }}
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </section>

        <section id="pedidos-personalizados" class="home-anchor-offset relative home-stage-soft py-16 sm:py-20 lg:min-h-screen lg:flex lg:items-center">
            <div class="mx-auto w-full max-w-6xl px-4 sm:px-6 lg:px-8">
                <div class="mx-auto max-w-5xl">
                    <div class="home-showcase-card">
                        <div class="grid lg:grid-cols-[0.82fr_1.18fr]">
                            <div class="home-showcase-media min-h-[20rem] lg:min-h-full">
                                <img
                                    src="{{ $chefImage }}"
                                    alt="Chef de El Fogon Dominicano"
                                    class="h-full w-full object-cover {{ $chefImageFocusClass }}"
                                >
                            </div>

                            <div class="flex flex-col justify-center p-8 sm:p-10 lg:p-12">
                                <p class="brand-kicker text-brand-forest"></p>
                                <h2 class="home-reference-heading mt-4 text-4xl leading-[0.92] text-brand-ink sm:text-5xl">
                                    Quiero un plato personalizado
                                </h2>
                                <p class="mt-5 max-w-2xl text-base leading-relaxed text-brand-ink sm:text-lg">
                                    En caso de que quieras encargar un plato fuera del menu del dia, puedes solicitar un pedido personalizado para ocasiones especiales, celebraciones o simplemente para darte un capricho con tu plato criollo favorito.
                                </p>
                                <p class="mt-4 max-w-2xl text-base leading-relaxed text-brand-ink">
                                    Ideal para celebraciones, menus especiales o pedidos con cantidades concretas.
                                </p>

                                <div class="mt-8 rounded-[1.75rem] bg-brand-cream/75 px-5 py-4">
                                    <p class="text-sm font-semibold uppercase tracking-[0.22em] text-brand-forest">{{ $chefName }}</p>
                                    <p class="mt-2 text-base leading-relaxed text-brand-ink">{{ $chefRole }}</p>
                                </div>

                                <div class="mt-8">
                                    <a href="{{ $customOrderUrl }}" class="home-accent-button w-full sm:w-auto">
                                        Solicitar encargo
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</body>
</html>
