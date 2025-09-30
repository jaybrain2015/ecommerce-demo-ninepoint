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
            colors: {
                brand: {
                    gray: '#7A7D7F', 
                    red: '#E5332A',
                    'red-dark': '#C72A22',
                    dark: '#1F2937',
                    blue: '#3B82F6',
                    purple: '#8B5CF6',
                    green: '#10B981',
                    orange: '#F59E0B',
                    pink: '#EC4899',
                },
            },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            container: {
                center: true,
                padding: {
                    DEFAULT: '1rem',
                    sm: '1.5rem',
                    lg: '2rem',
                    xl: '2.5rem',
                },
            },
        },
    },

    plugins: [forms],
};
