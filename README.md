# PurpurWebsite

The website used for https://purpurmc.org/

## Setting up

Make sure you have the required node version installed.

Rename `.env.example` to `.env` and add the required values.

Run `npm install` or `pnpm install` to install the required dependencies.

## Running in Development

Make sure to run `git update-index --skip-worktree public/data/**` to avoid staging data file changes while in dev.
This only needs to be done once; It does not need to be done everytime you want to run the project in dev mode.

Run `npm run dev` to load a development version of the project.

## Running on Production

Run `npm run build`, then go into the `dist` directory and run `node server/entry.mjs` to start the production server.
