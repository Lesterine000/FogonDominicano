import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                brand: {
                    night: '#0f1115',
                    panel: '#181c22',
                    ink: '#20283a',
                    ember: '#a1483d',
                    'ember-dark': '#7c342d',
                    cream: '#f2ebde',
                    mist: '#c2b7a6',
                    gold: '#c7a06b',
                    sage: '#6f8075',
                    forest: '#448848',
                },
            },
            fontFamily: {
                sans: ['"Source Sans 3"', ...defaultTheme.fontFamily.sans],
                display: ['"Cormorant Garamond"', ...defaultTheme.fontFamily.serif],
                script: ['Allura', 'cursive'],
            },
            boxShadow: {
                glow: '0 25px 80px rgba(0, 0, 0, 0.28)',
            },
        },
    },

    plugins: [forms],
};
