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
  app: {
    head: {
      title: "SIMPEL - Sistem Informasi Manajemen Pelayanan",
      meta: [
        { charset: "utf-8" },
        { name: "viewport", content: "width=device-width, initial-scale=1" },
        { name: "description", content: "SIMPEL - Sistem Informasi Manajemen Pelayanan & Operasional Kantor" },
      ],
      link: [
        { rel: "icon", type: "image/png", href: "/favicon.png" },
        { rel: "shortcut icon", href: "/favicon.ico" },
        { rel: "preconnect", href: "https://fonts.googleapis.com" },
        { rel: "preconnect", href: "https://fonts.gstatic.com", crossorigin: "" },
        {
          rel: "stylesheet",
          href: "https://fonts.googleapis.com/css2?family=Orbitron:wght@600;700;800;900&family=Syne:wght@700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap",
        },
        {
          rel: "stylesheet",
          href: "https://unpkg.com/leaflet@1.9.4/dist/leaflet.css",
        },
      ],
      script: [
        {
          src: "https://unpkg.com/leaflet@1.9.4/dist/leaflet.js",
          defer: true,
        },
      ],
    },
  },
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