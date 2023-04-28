/** @type {import('tailwindcss').Config} */
module.exports = {
  darkMode: 'media',
  darkMode: 'class',
  content: [
    './templates/**/*.html.twig',
    './assets/**/*.js',
    './node_modules/tw-elements/dist/js/**/*.js'
  ],
  theme: {
    extend: {},
  },
  plugins: [
    require('tw-elements/dist/plugin'),
    require('flowbite/plugin'),
    // require('@tailwindcss/aspect-ratio'),
    // require('tailwind-scrollbar-hide')
  ],
}

