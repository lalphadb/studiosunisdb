import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
        './resources/js/**/*.js',
        './resources/js/**/*.ts',
    ],
    
    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    50: '#eff6ff',
                    100: '#dbeafe',
                    200: '#bfdbfe',
                    300: '#93c5fd',
                    400: '#60a5fa',
                    500: '#3b82f6',
                    600: '#2563eb',
                    700: '#1d4ed8',
                    800: '#1e40af',
                    900: '#1e3a8a',
                    950: '#172554',
                },
            },
        },
    },

    plugins: [forms],
    
    // ✅ SAFELIST: Classes pour éviter purge en production
    safelist: [
        // Paddings/Margins essentiels
        'px-1', 'px-2', 'px-3', 'px-4', 'px-5', 'px-6', 'px-8', 'px-10', 'px-12',
        'py-1', 'py-2', 'py-3', 'py-4', 'py-5', 'py-6', 'py-8', 'py-10', 'py-12',
        'mx-1', 'mx-2', 'mx-3', 'mx-4', 'mx-5', 'mx-6', 'mx-8', 'mx-auto',
        'my-1', 'my-2', 'my-3', 'my-4', 'my-5', 'my-6', 'my-8',
        
        // Couleurs de statut dynamiques
        'bg-green-100', 'bg-green-500', 'bg-green-600', 'text-green-600', 'text-green-800',
        'bg-red-100', 'bg-red-500', 'bg-red-600', 'text-red-600', 'text-red-800',
        'bg-yellow-100', 'bg-yellow-500', 'bg-yellow-600', 'text-yellow-600', 'text-yellow-800',
        'bg-blue-100', 'bg-blue-500', 'bg-blue-600', 'text-blue-600', 'text-blue-800',
        'bg-gray-100', 'bg-gray-500', 'bg-gray-600', 'bg-gray-800', 'bg-gray-900',
        'text-gray-600', 'text-gray-800', 'text-gray-900', 'text-white',
        
        // Patterns pour classes générées dynamiquement
        {
            pattern: /^(bg|text|border)-(red|green|blue|yellow|gray)-(100|200|300|400|500|600|700|800|900)$/,
        },
        {
            pattern: /^(p|m)(x|y|t|r|b|l)?-(0|1|2|3|4|5|6|8|10|12|16|20|24)$/,
        },
    ],
};
