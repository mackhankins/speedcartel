/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  darkMode: 'class',
  theme: {
    extend: {
      colors: {
        'cartel-red': '#ff0000',
        'dark-gray': '#121212',
        'darker-gray': '#0a0a0a',
        'light-gray': '#2a2a2a',
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
  plugins: [],
}

