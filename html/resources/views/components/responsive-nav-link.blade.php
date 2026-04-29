@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full rounded-2xl border border-brand-ember bg-brand-ember/15 px-4 py-3 text-start text-sm font-semibold uppercase tracking-[0.18em] text-brand-cream transition'
            : 'block w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-start text-sm font-semibold uppercase tracking-[0.18em] text-brand-mist transition hover:border-brand-ember/60 hover:text-brand-cream';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
