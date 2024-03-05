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

        primaryColor: '#d7e5e7',   // azul claro
        secundaryColor: '#2e4c5f',      // azul arten
        thirdColor: '#37393a',       // amarillo kircof
        'primaryColor-light': '#3e6871',
        'primaryColor-dark': '#172833',  
        'text1': '#2e4c5f' 
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

