/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
  ],
  theme: {
    extend: {
      colors: {
        'main': '#4faead',
        'main-fund': '#dff1f2',
        'secondary': '#0062cc',
        'secondary-fund': '#324d57',
        primaryColor: '#2e4c5f',
        secundaryColor: '#ffcc00',          
        'primaryColor-light': '#3e6871',
        'primaryColor-dark': '#172833',   
      },
    },
  },
  plugins: [
    require('@tailwindcss/typography'),
    require('@tailwindcss/forms'),
    require('@tailwindcss/aspect-ratio'),
    require('@tailwindcss/container-queries'),
  ],
}

