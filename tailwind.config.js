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
        'text1': '#2e4c5f',
        'text2': '#575454' 
      },
      backgroundImage: {
        'coma-gradient': `radial-gradient(90% 90% at 27% 84%, #1F3544D4 0%, #073AFF00 99%),
                           radial-gradient(35% 56% at 91% 74%, #3e6871 0%, #073AFF00 76%),
                           radial-gradient(90% 90% at 89% 17%, #2e4c5f 0%, #073AFF00 95%),
                           linear-gradient(139deg, #67B7BAFF 1%, #2e4c5f 100%)`
      },
      backgroundSize: {
        'full': '100% 100%',
      },
      backgroundPosition: {
        'full': '0px 0px, 0px 0px, 0px 0px, 0px 0px',
      }
    },

  },
  plugins: [
    require('@tailwindcss/typography'),
    require('@tailwindcss/forms'),
    require('@tailwindcss/aspect-ratio'),
    require('@tailwindcss/container-queries'),
  ],
}

