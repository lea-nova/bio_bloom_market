/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          default: '#4CAF50',
          light: '#81C784',
          dark: '#388E3C',
          accent: '#8E9A52',
        },
        secondary: {
          default: '#795548',
          light: '#D7CCC8',
          dark: '#5D4037',
          accent: '#A1887F',
        },
        tertiary: {
          light: '#e0e0e0', // gris clair
          default: '#BDBDBD',
          accent: '#CFD8DC',
          dark: '#757575',
          darker: '#0A0A0A', // A UTILISER POUR LE TEXTE FONCER
        },
        light: {
          default: '#FFFFFF',
          dark: '#FFFAF0',
          muted: '#F5F5F5',
          accent: '#E3F2FD',
        },
        warning: {
          default: '#FFB74D',
          light: '#FFF9C4'
        }
      },
    },
    plugins: [],
  }
}