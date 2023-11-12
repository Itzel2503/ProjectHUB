/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    screens: {
      sm: '480px',
      md: '768px',
      lg: '976px',
      xl: '1440px',
    },
    colors: {
      'white': '#ffffff',
      'black': '#000000',
      'main': '#4faead',
      'main-fund': '#dff1f2',
      'secondary': '#0062cc',
      'secondary-fund': '#324d57',
      'yellow': '#f6c03e',
      'red': '#dd4231',
      'gray-500': '#6b7280',
    },
    /*fontFamily: {
      sans: ['Graphik', 'sans-serif'],
      serif: ['Merriweather', 'serif'],
    }, */
    extend: {
      spacing: {
        '128': '32rem',
        '144': '36rem',
      },
      borderRadius: {
        '4xl': '2rem',
      }
    }
  },
  plugins: [],
}

