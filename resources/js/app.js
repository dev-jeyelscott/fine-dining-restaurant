import Alpine from "alpinejs";
import inquiryForm from "./forms/inquiry-form";

window.Alpine = Alpine;

Alpine.data("inquiryForm", inquiryForm);

Alpine.start();

const homeMotionRoot = document.querySelector("[data-home-motion]");

if (homeMotionRoot) {
    import("./public-animations")
        .then(({ initPublicAnimations }) => initPublicAnimations(homeMotionRoot))
        .catch((error) => {
            console.error("Unable to initialize Home page animations.", error);
        });
}
