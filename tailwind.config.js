/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './templates/**/*.html.twig', // Scan all Twig templates
    './src/**/*.php',            // Optional: Scan PHP files
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}