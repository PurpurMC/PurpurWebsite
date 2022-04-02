import "https://cdn.jsdelivr.net/npm/howler@2.2.3/dist/howler.core.min.js";

/**
 * The Howler instance that plays our music.
 *
 * @type {Howl}
 */
const howl = new Howl({
    "src": "/music/bgm.mp3",
    "loop": true,
    "volume": "0.5"
});

/**
 * Pseudo-random number generator.
 *
 * @param min Minimum number to generate
 * @param max Maximum number to generate
 * @return {number}
 */
function random(min, max) {
    return Math.floor(Math.random() * max + min);
}

/**
 * Utility function that returns a promise that resolves in passed milliseconds.
 *
 * @param ms Milliseconds to resolve
 * @return {Promise<unknown>}
 */
async function sleep(ms) {
    return new Promise(resolve => {
        setTimeout(resolve, ms);
    });
}

/**
 * Function to indefinitely "glitch" the music.
 * Made to simulate a struggling computer.
 *
 * @return {Promise<void>}
 */
async function glitch() {
    // Pause the music
    howl.pause();

    // Wait a few milliseconds
    await sleep(random(10, 50));

    // Play the music
    howl.play();

    // Chance to change the pitch
    if (random(0, 1.5)) {
        // Randomly change the pitch
        howl.rate(random(0.75, 1.25));

        // Wait a few milliseconds
        await sleep(random(10, 30));

        // Set the pitch back to normal
        howl.rate(1);
    }

    // Repeat!
    setTimeout(glitch, random(500, 15000));
}

glitch();
