import { defineConfig } from "astro/config";

import node from "@astrojs/node";

// https://astro.build/config
export default defineConfig({
  site: 'https://purpurmc.org',
  output: 'hybrid',
  adapter: node({
    mode: 'standalone'
  }),
  experimental: {
    serverIslands: true,
  },
});
