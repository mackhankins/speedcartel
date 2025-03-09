/** @type {import('tailwindcss').Config} */
const colors = require('tailwindcss/colors')

export default {
    presets: [
        require("./vendor/wireui/wireui/tailwind.config.js")
    ],
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "./vendor/wireui/wireui/src/*.php",
        "./vendor/wireui/wireui/ts/**/*.ts",
        "./vendor/wireui/wireui/src/WireUi/**/*.php",
        "./vendor/wireui/wireui/src/Components/**/*.php",
    ],
    darkMode: 'selector',
    theme: {
        extend: {
            colors: {
                'cartel-red': '#ff0000',
                'dark-gray': '#121212',
                'darker-gray': '#1a1a1a',
                'light-gray': '#2a2a2a',
                danger: colors.red,
                success: colors.emerald,
                warning: colors.amber,
                info: colors.blue,
                primary: {
                    50: colors.red[50],
                    100: colors.red[100],
                    200: colors.red[200],
                    300: colors.red[300],
                    400: colors.red[400],
                    500: colors.red[500],
                    600: colors.red[600],
                    700: colors.red[700],
                    800: colors.red[800],
                    900: colors.red[900],
                    950: colors.red[950],
                },
                secondary: {
                    50: colors.gray[50],
                    100: colors.gray[100],
                    200: colors.gray[200],
                    300: colors.gray[300],
                    400: colors.gray[400],
                    500: colors.gray[500],
                    600: colors.gray[600],
                    700: colors.gray[700],
                    800: colors.gray[800],
                    900: colors.gray[900],
                    950: colors.gray[950],
                },
                background: {
                    white: colors.white,
                    dark: '#1a1a1a'
                }
            },
            fontFamily: {
                'sans': ['Instrument Sans', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                'orbitron': ['Orbitron', 'sans-serif'],
            },
            keyframes: {
                fadeInUp: {
                    '0%': { opacity: '0', transform: 'translateY(20px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                widthGrow: {
                    '0%': { width: '0' },
                    '100%': { width: '100%' },
                },
            },
            animation: {
                fadeInUp: 'fadeInUp 0.5s ease-out',
                widthGrow: 'widthGrow 1.2s ease-out',
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms')({ strategy: 'class' }),
        require('@tailwindcss/line-clamp'),
        function({ addComponents }) {
            addComponents({
                '.dark .form-input, .dark .form-textarea, .dark .form-select, .dark .form-multiselect': {
                    backgroundColor: '#1a1a1a',
                    borderColor: 'rgb(75, 85, 99)',
                    color: '#ffffff',
                    '&:focus': {
                        backgroundColor: '#1a1a1a',
                        borderColor: 'rgb(239, 68, 68)',
                        '--tw-ring-color': 'rgb(239 68 68 / 0.3)',
                    },
                    '&:disabled': {
                        backgroundColor: '#1f1f1f',
                        borderColor: 'rgb(45, 55, 71)',
                        color: 'rgb(75, 85, 99)',
                        cursor: 'not-allowed',
                    }
                },
                '.dark .form-input-sm, .dark .form-textarea-sm, .dark .form-select-sm, .dark .form-multiselect-sm': {
                    backgroundColor: '#1a1a1a',
                    borderColor: 'rgb(75, 85, 99)',
                    color: '#ffffff',
                    '&:disabled': {
                        backgroundColor: '#1f1f1f',
                        borderColor: 'rgb(45, 55, 71)',
                        color: 'rgb(75, 85, 99)',
                        cursor: 'not-allowed',
                    }
                },
                '.dark .bg-white': {
                    backgroundColor: '#1a1a1a',
                    borderColor: 'rgb(75, 85, 99)',
                    borderWidth: '1px',
                },
                '.dark [x-ref="optionsContainer"]': {
                    backgroundColor: '#1a1a1a',
                    borderColor: 'rgb(75, 85, 99)',
                    '--tw-shadow': '0 4px 6px -1px rgb(0 0 0 / 0.3)',
                    '--tw-shadow-colored': '0 4px 6px -1px var(--tw-shadow-color)',
                },
                '.dark [role="listbox"]': {
                    backgroundColor: '#1a1a1a',
                    borderColor: 'rgb(75, 85, 99)',
                },
                '.dark [role="option"]': {
                    color: '#ffffff',
                    '&:hover': {
                        backgroundColor: '#2a2a2a',
                    },
                    '&[aria-selected="true"]': {
                        backgroundColor: '#2a2a2a',
                    },
                    '&:disabled': {
                        backgroundColor: '#2a2a2a',
                        color: 'rgb(107, 114, 128)',
                        cursor: 'not-allowed',
                    }
                },
                '.dark button:disabled': {
                    backgroundColor: '#2a2a2a',
                    borderColor: 'rgb(55, 65, 81)',
                    color: 'rgb(107, 114, 128)',
                    cursor: 'not-allowed',
                },
                '.dark .btn:disabled': {
                    backgroundColor: '#2a2a2a',
                    borderColor: 'rgb(55, 65, 81)',
                    color: 'rgb(107, 114, 128)',
                    cursor: 'not-allowed',
                }
            });
        },
    ],
}

