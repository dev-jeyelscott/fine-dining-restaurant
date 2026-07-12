import Alpine from "alpinejs";
import inquiryForm from "./forms/inquiry-form";

window.Alpine = Alpine;

Alpine.data("inquiryForm", inquiryForm);

Alpine.start();

const homeMotionRoot = document.querySelector("[data-home-motion]");

if (homeMotionRoot) {
    Promise.all([
        import("./home-gallery-carousel"),
        import("./public-animations"),
    ])
        .then(([{ initHomeGalleryCarousel }, { initPublicAnimations }]) => {
            const carouselCleanup = initHomeGalleryCarousel(
                homeMotionRoot.querySelector('[data-gsap="gallery"]'),
                { reducedMotion: window.matchMedia("(prefers-reduced-motion: reduce)").matches },
            );
            const motionCleanup = initPublicAnimations(homeMotionRoot);
            const cleanup = () => {
                motionCleanup();
                carouselCleanup();
            };

            if (import.meta.hot) {
                import.meta.hot.dispose(cleanup);
            }
        })
        .catch((error) => {
            console.error("Unable to initialize public page interactions.", error);
        });
}
