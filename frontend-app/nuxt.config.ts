import tailwindcss from "@tailwindcss/vite";

export default defineNuxtConfig({
  compatibilityDate: "2025-07-15",
  devtools: { enabled: true },
  modules: [
    "@pinia/nuxt",
  ],
  css: [
    "~/assets/css/main.css",
  ],
  runtimeConfig: {
    public: {
      apiBase: "http://127.0.0.1:8000/api",
    },
  },
  vite: {
    plugins: [
      tailwindcss(),
    ],
  },
});