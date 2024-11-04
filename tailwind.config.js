import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/views/*.blade.php",
        "./resources/js/*.js",
        "./vendor/rappasoft/laravel-livewire-tables/resources/views/*.blade.php",
        "./vendor/rappasoft/laravel-livewire-tables/resources/views/**/*.blade.php",
        "./app/Livewire/*.php",
        "./app/Livewire/**/*.php",
        "./vendor/wire-elements/modal/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
    ],

    theme: {
        extend: {
            fontSize: {
                '2xs': '0.8125rem',
            },
            spacing: {
                '2.25': '.563rem',
                '7.5': '1.875rem;',
            }
        },
    },

    plugins: [forms],
};
