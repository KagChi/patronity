import aspectRatio from "@tailwindcss/aspect-ratio";
import typography from "@tailwindcss/typography";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.tsx",
        "./app/Filament/**/*.php",
        "./resources/views/filament/**/*.blade.php",
        "./vendor/filament/**/*.blade.php",
        "./resources/views/components/**/*.blade.php"
    ],
    theme: {
        extend: {
            container: {
                center: true
            },
            objectPosition: {
                "85-15": "85% 15%"
            }
        }
    },
    corePlugins: {
        aspectRatio: false
    },
    plugins: [
        aspectRatio,
        typography
    ]
};
