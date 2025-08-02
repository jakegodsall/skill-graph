import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
    ],
    darkMode: "class",
    theme: {
        extend: {
            colors: {
                "primary-blue": "var(--primary-blue)",
                "secondary-blue": "var(--secondary-blue)",
                "primary-grey:": "var(--primary-grey)",
                "secondary-grey": "var(--secondary-grey)",
                "tertiary-grey": "var(--tertiary-grey)",
            },
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
