/** @type {import('tailwindcss').Config} */
const defaultTheme = require('tailwindcss/defaultTheme')
const { generateRootCSSVars, generateTailwindColors } = require('./twColorGenerator.js')

export default {
    content: [
        "./resources/**/*.{vue,js,ts,blade.php}",
    ],
    darkMode: 'class', // or 'media' or 'class'
    colors: generateTailwindColors(),
    theme: {
        extend: {
            fontFamily: { sans: ['Nunito Sans', ...defaultTheme.fontFamily.sans] },
            fontSize: { xxs: '11px' },
            maxWidth: { xxs: '15rem' },
        },
    },
    plugins: [
        require('@tailwindcss/container-queries'),
        require('@tailwindcss/typography'),
        function ({ addBase }) {
            addBase({ ':root': generateRootCSSVars() })
        },
    ],
}

