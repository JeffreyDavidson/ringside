/** @type {import('tailwindcss').Config} */

const defaultTheme = require('tailwindcss/defaultTheme')
const colors = require('tailwindcss/colors')

module.exports = {
    important: true,
    plugins: [
        require('@tailwindcss/typography'),
        require('@tailwindcss/forms'),
        require('@tailwindcss/aspect-ratio')
    ],
    content: [
        "./resources/views/**/*.blade.php",
        "./resources/js/*.js",
        "./vendor/rappasoft/laravel-livewire-tables/resources/views/*.blade.php",
        "./vendor/rappasoft/laravel-livewire-tables/resources/views/**/*.blade.php",
        "./app/Livewire/*.php",
        "./app/Livewire/**/*.php",    ],
    safelist: [
        {
            pattern: /max-w-(sm|md|lg|xl|2xl|3xl|4xl|5xl|6xl|7xl|full)/,
            variants: ['sm', 'md', 'lg', 'xl', '2xl', '3xl', '4xl']
        },
        {
            pattern: /w-(screen|full)/,
            variants: ['sm', 'md', 'lg', 'xl', '2xl', '3xl', '4xl']
        }
    ],
    theme: {
        extend: {}
    }
}
