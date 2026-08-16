import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                brand: {
                    50: '#FDF8F9',
                    100: '#FADCE0',
                    200: '#F5B9C2',
                    300: '#EF96A3',
                    400: '#E97285',
                    500: '#CF5C70',
                    600: '#B84E61',
                    700: '#943E4E',
                    800: '#712E3A',
                    900: '#4D1F27',
                },
                cream: {
                    50: '#FFFFFF',
                    100: '#FCF9F5',
                    200: '#F5EBE6',
                    300: '#EAD6CC',
                    400: '#DBC0B2',
                },
                brown: {
                    500: '#A68A7D',
                    600: '#7A6B62',
                    800: '#4A3B32',
                    900: '#2D231E',
                }
            }
        },
    },

    plugins: [forms],
};
