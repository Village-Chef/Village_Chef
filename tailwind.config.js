/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [],
  theme: {
    extend: {
      screens: {
        'xs': '475px', // Custom breakpoint at 440px
      },
      animation :{
        'spin-slow':['spin 2s linear infinite']
      }
    },
  },
  plugins: [],
}

