// vite.config.js
import { defineConfig } from "vite"
import react from "@vitejs/plugin-react"
import tailwindcss from "@tailwindcss/vite"

// ✅ devolvemos una función para tener { mode }
export default defineConfig(({ mode }) => ({
    plugins: [react(), tailwindcss()],
    server: {
        port: 5173,
        proxy: { "/api": "http://localhost:8000" },
    },
    build: {
        outDir: "dist",
        assetsDir: "assets",
        manifest: true,
        emptyOutDir: true,
    },
    // En dev sirve en "/", en build genera rutas hacia "/static/"
    base: mode === "development" ? "/" : "/static/",
}))
