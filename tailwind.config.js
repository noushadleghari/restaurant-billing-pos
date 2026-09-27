/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
  ],
  theme: {
    extend: {
      colors: {
        brand: {
          50: '#eefdf3',
          100: '#d6f9e2',
          200: '#b0f0c9',
          300: '#79e2a8',
          400: '#3ecb82',
          500: '#17b167',
          600: '#0d8f53',
          700: '#0c7145',
          800: '#0d5a39',
          900: '#0c4a31',
        },
      },
      fontSize: {
        'xxs': '0.7rem',
      }
    },
  },
  plugins: [],
}
