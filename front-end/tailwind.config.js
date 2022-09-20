/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './src/**/*.{js,ts,html,vue}',
    './public/index.html'
  ],
  theme: {
    extend: {},
  },
  plugins: [
    require('tailwindcss-debug-screens')
  ],
}
