import Alpine from "alpinejs";

window.Alpine = Alpine;

Alpine.start();

const homeMotionRoot = document.querySelector("[data-home-motion]");

if (homeMotionRoot) {
    import("./public-animations")
        .then(({ initPublicAnimations }) => initPublicAnimations(homeMotionRoot))
        .catch((error) => {
            console.error("Unable to initialize Home page animations.", error);
        });
}
