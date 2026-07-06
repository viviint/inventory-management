import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    darkMode: 'class',

    theme: {
        extend: {
            fontFamily: {
                sans: ['"Plus Jakarta Sans"', 'Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                brand: {
                    blue: '#24C4F4',
                    'blue-hover': '#18AEE2',
                    purple: '#8B5CF6',
                    'purple-hover': '#7C3AED',
                    pink: '#EC4899',
                    dark: '#5B21B6',
                },
                semantic: {
                    success: '#22C55E',
                    warning: '#F59E0B',
                    danger: '#EF4444',
                    info: '#0EA5E9',
                },
            },
            boxShadow: {
                'brand': '0 8px 20px -4px rgba(36, 196, 244, 0.35)',
                'soft': '0 4px 20px -2px rgba(15, 23, 42, 0.05), 0 2px 6px -1px rgba(15, 23, 42, 0.03)',
            },
            borderRadius: {
                'sm': '8px',
                'md': '12px',
                'lg': '16px',
                'xl': '20px',
            },
        },
    },

    plugins: [forms],
};
