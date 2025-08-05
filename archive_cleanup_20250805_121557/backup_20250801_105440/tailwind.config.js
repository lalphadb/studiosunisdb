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
    
    // ✅ SAFELIST pour garantir les classes critiques
    safelist: [
        'px-4', 'py-2', 'px-6', 'py-3', 'px-8', 'py-4',
        'bg-blue-500', 'bg-green-500', 'bg-red-500', 'bg-yellow-500',
        'text-white', 'text-gray-900', 'text-gray-600', 'text-gray-700',
        'rounded', 'rounded-lg', 'shadow', 'shadow-lg',
        'border', 'border-gray-300', 'hover:bg-blue-600',
        'flex', 'items-center', 'justify-center', 'space-x-2',
        'w-full', 'max-w-md', 'mx-auto', 'mt-4', 'mb-4',
        'font-medium', 'font-bold', 'text-sm', 'text-lg'
    ],
    
    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },
    
    plugins: [forms],
};
