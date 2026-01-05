import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'art-button': '#DEC6D3',
                'art-coral': '#E76268',
                'art-navy': '#193948',
                'art-turquoise': '#4FADC0',
                'art-charcoal': '#1E1E1E',
                'art-beige': '#F3EBDD',
            },
        },
    },

    plugins: [forms],
};
