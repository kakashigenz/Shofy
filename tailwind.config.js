/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.{vue,js,ts,jsx,tsx}",
    ],
    theme: {
        extend: {},
        screens: {
            xl: { max: "1920px" },
            lg: { max: "1280px" },
            md: { max: "768px" },
            sm: { max: "375px" },
        },
    },
    plugins: [],
    darkMode: "class",
};
