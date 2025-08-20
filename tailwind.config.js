const defaultTheme = require('tailwindcss/defaultTheme');
const colors = require('tailwindcss/colors');

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
        './resources/js/**/*.js',
        './resources/**/*.html',
    ],
    
    theme: {
        extend: {
            // Couleurs de marque StudiosDB
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
                secondary: {
                    50: '#f9fafb',
                    100: '#f3f4f6',
                    200: '#e5e7eb',
                    300: '#d1d5db',
                    400: '#9ca3af',
                    500: '#6b7280',
                    600: '#4b5563',
                    700: '#374151',
                    800: '#1f2937',
                    900: '#111827',
                    950: '#030712',
                },
                danger: colors.red,
                warning: colors.amber,
                success: colors.emerald,
                info: colors.sky,
                
                // Couleurs spécifiques karaté/arts martiaux
                belt: {
                    white: '#ffffff',
                    yellow: '#fbbf24',
                    orange: '#fb923c',
                    green: '#22c55e',
                    blue: '#3b82f6',
                    brown: '#a16207',
                    black: '#1f2937',
                },
            },
            
            // Polices personnalisées
            fontFamily: {
                sans: ['Inter var', ...defaultTheme.fontFamily.sans],
                mono: ['JetBrains Mono', ...defaultTheme.fontFamily.mono],
                display: ['Lexend', 'Inter var', ...defaultTheme.fontFamily.sans],
            },
            
            // Espacements personnalisés
            spacing: {
                '18': '4.5rem',
                '88': '22rem',
                '128': '32rem',
            },
            
            // Animations personnalisées
            animation: {
                'fade-in': 'fadeIn 0.5s ease-in-out',
                'slide-up': 'slideUp 0.3s ease-out',
                'slide-down': 'slideDown 0.3s ease-out',
                'bounce-light': 'bounceLight 1s infinite',
                'pulse-slow': 'pulse 3s infinite',
            },
            
            keyframes: {
                fadeIn: {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                slideUp: {
                    '0%': { transform: 'translateY(10px)', opacity: '0' },
                    '100%': { transform: 'translateY(0)', opacity: '1' },
                },
                slideDown: {
                    '0%': { transform: 'translateY(-10px)', opacity: '0' },
                    '100%': { transform: 'translateY(0)', opacity: '1' },
                },
                bounceLight: {
                    '0%, 100%': { transform: 'translateY(-5%)' },
                    '50%': { transform: 'translateY(0)' },
                },
            },
            
            // Bordures et rayons
            borderRadius: {
                'xl': '1rem',
                '2xl': '1.5rem',
                '3xl': '2rem',
            },
            
            // Ombres personnalisées
            boxShadow: {
                'soft': '0 2px 15px 0 rgba(0, 0, 0, 0.08)',
                'medium': '0 4px 25px 0 rgba(0, 0, 0, 0.15)',
                'strong': '0 10px 40px 0 rgba(0, 0, 0, 0.25)',
                'glow': '0 0 20px rgba(59, 130, 246, 0.35)',
            },
            
            // Tailles d'écran personnalisées
            screens: {
                'xs': '475px',
                '3xl': '1600px',
            },
            
            // Configuration pour les tableaux et composants
            maxWidth: {
                '8xl': '88rem',
                '9xl': '96rem',
            },
            
            // Heights personnalisées
            height: {
                'screen-1/2': '50vh',
                'screen-2/3': '66.666667vh',
                'screen-3/4': '75vh',
            },
        },
    },
    
    plugins: [
        require('@tailwindcss/forms')({
            strategy: 'class', // or 'base'
        }),
        require('@tailwindcss/typography'),
        require('@tailwindcss/aspect-ratio'),
        
        // Plugin personnalisé pour les utilitaires StudiosDB
        function({ addUtilities, theme }) {
            const newUtilities = {
                '.text-balance': {
                    'text-wrap': 'balance',
                },
                '.text-pretty': {
                    'text-wrap': 'pretty',
                },
                '.glass-effect': {
                    'backdrop-filter': 'blur(16px) saturate(180%)',
                    'background-color': 'rgba(255, 255, 255, 0.75)',
                    'border': '1px solid rgba(209, 213, 219, 0.3)',
                },
                '.glass-dark': {
                    'backdrop-filter': 'blur(16px) saturate(180%)',
                    'background-color': 'rgba(17, 24, 39, 0.75)',
                    'border': '1px solid rgba(75, 85, 99, 0.3)',
                },
            };
            
            addUtilities(newUtilities, ['responsive', 'hover']);
        },
    ],
    
    // Configuration mode sombre
    darkMode: 'class',
    
    // Optimisations
    corePlugins: {
        preflight: true,
    },
    
    // Variables CSS personnalisées
    experimental: {
        optimizeUniversalDefaults: true,
    },
};
