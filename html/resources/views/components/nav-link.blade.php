@props(['active'])

@php
$classes = ($active ?? false)
            ? 'brand-nav-link border-brand-ember bg-brand-ember/15 text-brand-cream'
            : 'brand-nav-link border-white/10 bg-white/5 text-brand-mist hover:border-brand-ember/60 hover:text-brand-cream';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
