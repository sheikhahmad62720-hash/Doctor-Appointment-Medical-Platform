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
                sans: ['"Plus Jakarta Sans"', ...defaultTheme.fontFamily.sans],
            },

            colors: {
                primary: {
                    50: '#eefaf8',
                    100: '#d6f2ef',
                    200: '#b0e5e1',
                    300: '#7dd2cd',
                    400: '#47b7b3',
                    500: '#2c9b98',
                    600: '#207d7c',
                    700: '#1e6565',
                    800: '#1d5252',
                    900: '#1b4445',
                    950: '#0a292b',
                },
                navy: {
                    50: '#f3f6fb',
                    100: '#e5ebf5',
                    200: '#cdd9ec',
                    300: '#a4bbdd',
                    400: '#7a9ccb',
                    500: '#5c7fb9',
                    600: '#48669c',
                    700: '#3b527e',
                    800: '#33466a',
                    900: '#22304e',
                    950: '#141d33',
                },
                amber: {
                    50: '#fffbeb',
                    100: '#fef3c7',
                    200: '#fde68a',
                    300: '#fcd34d',
                    400: '#fbbf24',
                    500: '#f59e0b',
                    600: '#d97706',
                    700: '#b45309',
                    800: '#92400e',
                    900: '#78350f',
                },
            },

            boxShadow: {
                soft: '0 1px 2px 0 rgb(15 23 42 / 0.04), 0 1px 3px 0 rgb(15 23 42 / 0.06)',
                card: '0 1px 2px 0 rgb(15 23 42 / 0.05), 0 4px 16px -2px rgb(15 23 42 / 0.06)',
                lifted:
                    '0 2px 4px -2px rgb(15 23 42 / 0.06), 0 12px 32px -8px rgb(15 23 42 / 0.14)',
                glow: '0 0 0 3px rgb(44 155 152 / 0.15)',
            },

            borderRadius: {
                '2xl': '1rem',
                '3xl': '1.25rem',
            },

            maxWidth: {
                '8xl': '88rem',
            },

            keyframes: {
                'fade-in': {
                    from: { opacity: '0' },
                    to: { opacity: '1' },
                },
                'fade-in-up': {
                    from: { opacity: '0', transform: 'translateY(8px)' },
                    to: { opacity: '1', transform: 'translateY(0)' },
                },
                'scale-in': {
                    from: { opacity: '0', transform: 'scale(0.96)' },
                    to: { opacity: '1', transform: 'scale(1)' },
                },
                'slide-in-right': {
                    from: { transform: 'translateX(100%)' },
                    to: { transform: 'translateX(0)' },
                },
                shimmer: {
                    from: { backgroundPosition: '200% 0' },
                    to: { backgroundPosition: '-200% 0' },
                },
                'toast-in': {
                    from: { opacity: '0', transform: 'translateY(12px) scale(0.97)' },
                    to: { opacity: '1', transform: 'translateY(0) scale(1)' },
                },
            },

            animation: {
                'fade-in': 'fade-in 0.2s ease-out both',
                'fade-in-up': 'fade-in-up 0.3s cubic-bezier(0.21, 1.02, 0.73, 1) both',
                'scale-in': 'scale-in 0.18s ease-out both',
                'slide-in-right': 'slide-in-right 0.3s cubic-bezier(0.21, 1.02, 0.73, 1) both',
                shimmer: 'shimmer 2s linear infinite',
                'toast-in': 'toast-in 0.25s cubic-bezier(0.21, 1.02, 0.73, 1) both',
            },

            transitionTimingFunction: {
                'out-soft': 'cubic-bezier(0.21, 1.02, 0.73, 1)',
            },
        },
    },

    plugins: [forms],
};
