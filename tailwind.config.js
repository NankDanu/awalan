import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

const withOpacityValue = (variable) => ({ opacityValue } = {}) => {
    if (opacityValue === undefined) {
        return `oklch(var(${variable}))`;
    }

    return `oklch(var(${variable}) / ${opacityValue})`;
};

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.blade.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],
    safelist: [
        'chip-compact',
        'bg-blue-100',
        'text-blue-800',
        'bg-green-100',
        'text-green-800',
        'bg-red-100',
        'text-red-800',
        'bg-indigo-100',
        'text-indigo-800',
        'bg-emerald-100',
        'text-emerald-800',
        'bg-orange-100',
        'text-orange-800',
        'bg-gray-100',
        'text-gray-700',
        'text-gray-800',
    ],

    theme: {
        extend: {
            spacing: {
                '8.5': '2.125rem',
            },
            fontFamily: {
                sans: ['Inter var', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                background: withOpacityValue('--background-raw'),
                foreground: withOpacityValue('--foreground-raw'),
                card: withOpacityValue('--card-raw'),
                'card-foreground': withOpacityValue('--card-foreground-raw'),
                popover: withOpacityValue('--popover-raw'),
                'popover-foreground': withOpacityValue('--popover-foreground-raw'),
                primary: {
                    DEFAULT: withOpacityValue('--primary-raw'),
                    foreground: withOpacityValue('--primary-foreground-raw'),
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
                },
                secondary: {
                    DEFAULT: withOpacityValue('--secondary-raw'),
                    foreground: withOpacityValue('--secondary-foreground-raw'),
                },
                muted: {
                    DEFAULT: withOpacityValue('--muted-raw'),
                    foreground: withOpacityValue('--muted-foreground-raw'),
                },
                accent: {
                    DEFAULT: withOpacityValue('--accent-raw'),
                    foreground: withOpacityValue('--accent-foreground-raw'),
                },
                destructive: {
                    DEFAULT: withOpacityValue('--destructive-raw'),
                    foreground: withOpacityValue('--destructive-foreground-raw'),
                },
                mono: {
                    DEFAULT: withOpacityValue('--mono-raw'),
                    foreground: withOpacityValue('--mono-foreground-raw'),
                },
                border: withOpacityValue('--border-raw'),
                input: withOpacityValue('--input-raw'),
                ring: withOpacityValue('--ring-raw'),
            },
        },
    },

    plugins: [
        forms,
        ({ addVariant }) => {
            addVariant('aria-invalid', '&[aria-invalid="true"], &[aria-invalid="1"], &[aria-invalid="invalid"], &[aria-invalid]');
        },
    ],
};
