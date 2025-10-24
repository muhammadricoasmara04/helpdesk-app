import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",
                "resources/js/auth.js",
                "resources/js/dashboard.js",
                "resources/js/dashboard/application.js",
                "resources/js/dashboard/application-problem.js",
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
