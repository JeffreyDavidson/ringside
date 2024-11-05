import forms from '@tailwindcss/forms';
const defaultTheme = import('tailwindcss/defaultTheme.js')

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
            base: {
                colors: {
					gray: {
						light: {
							100: '#F9F9F9',
							200: '#F1F1F4',
							300: '#DBDFE9',
							400: '#C4CADA',
							500: '#99A1B7',
							600: '#78829D',
							700: '#4B5675',
							800: '#252F4A',
							900: '#071437',
						},
                    },
                },
            },
            fontSize: {
                '2xs': '0.8125rem',
            },
            spacing: {
                '2.25': '.563rem',
                '7.5': '1.875rem',
            },
            lineHeight: {
                '4.25': '1.125rem'
            },
            fontFamily: {
                sans: ['Inter', 'system-ui', 'sans-serif'],
            },
            colors: {
				gray: {
					100: 'var(--tw-gray-100)',
					200: 'var(--tw-gray-200)',
					300: 'var(--tw-gray-300)',
					400: 'var(--tw-gray-400)',
					500: 'var(--tw-gray-500)',
					600: 'var(--tw-gray-600)',
					700: 'var(--tw-gray-700)',
					800: 'var(--tw-gray-800)',
					900: 'var(--tw-gray-900)',
				},
            },
        },
    },

    plugins: [forms],
};
