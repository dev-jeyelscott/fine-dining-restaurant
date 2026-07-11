import gsap from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";

gsap.registerPlugin(ScrollTrigger);

const revealDistance = 28;

function revealTimeline(target, options = {}) {
    const elements = target.querySelectorAll("[data-gsap-reveal]");

    if (!elements.length) {
        return;
    }

    gsap.set(elements, { autoAlpha: 0, y: revealDistance });
    gsap.timeline({
        scrollTrigger: { trigger: target, start: options.start ?? "top 82%", once: true },
    }).to(elements, {
        autoAlpha: 1,
        duration: options.duration ?? 0.85,
        ease: "power3.out",
        stagger: options.stagger ?? 0.1,
        y: 0,
    });
}

function revealImage(target) {
    const image = target.querySelector("img");

    if (!image) {
        return;
    }

    gsap.set(target, { clipPath: "inset(0 0 100% 0)" });
    gsap.set(image, { scale: 1.04 });
    gsap.timeline({
        scrollTrigger: { trigger: target, start: "top 82%", once: true },
    }).to(target, {
        clipPath: "inset(0 0 0% 0)", duration: 1, ease: "power4.out",
    }).to(image, {
        scale: 1, duration: 1.2, ease: "power3.out",
    }, "<");
}

function addParallax(target, amount = 5) {
    const image = target.querySelector("img");

    if (!image) {
        return;
    }

    gsap.fromTo(image, { yPercent: -amount / 2 }, {
        yPercent: amount / 2,
        ease: "none",
        scrollTrigger: { trigger: target, start: "top bottom", end: "bottom top", scrub: true },
    });
}

function addGoldFrame(target) {
    if (!target) {
        return;
    }

    gsap.fromTo(target, { autoAlpha: 0, scale: 0.98 }, {
        autoAlpha: 1,
        duration: 0.9,
        ease: "power3.out",
        scale: 1,
        scrollTrigger: { trigger: target.parentElement, start: "top 78%", once: true },
    });
}

function animateDeliveryAddress(root, fulfillmentType, reducedMotion) {
    const panel = root.querySelector('[data-gsap="delivery-address"]');

    if (!panel) {
        return;
    }

    const isDelivery = fulfillmentType === "delivery";

    if (reducedMotion) {
        gsap.set(panel, { clearProps: "height,opacity,transform", display: isDelivery ? "block" : "none" });
        return;
    }

    gsap.killTweensOf(panel);

    if (isDelivery) {
        gsap.set(panel, { display: "block", height: 0, autoAlpha: 0, y: -10 });
        gsap.to(panel, { autoAlpha: 1, duration: 0.25, ease: "power2.out", height: "auto", y: 0 });
        return;
    }

    gsap.set(panel, { display: "block", height: "auto", autoAlpha: 1, y: 0 });
    const currentHeight = panel.offsetHeight;

    gsap.set(panel, { height: currentHeight });
    gsap.to(panel, {
        autoAlpha: 0,
        duration: 0.2,
        ease: "power2.in",
        height: 0,
        y: -10,
        onComplete: () => gsap.set(panel, { display: "none" }),
    });
}

function initializeOrderInquiryMotion(root, { reducedMotion }) {
    const process = root.querySelector('[data-gsap="process"]');
    const cards = root.querySelector('[data-gsap="fulfillment-cards"]');
    const feedback = root.querySelectorAll('[data-gsap="feedback"]');

    if (reducedMotion) {
        root.querySelectorAll("[data-gsap-reveal]").forEach((element) => gsap.set(element, { clearProps: "all" }));
        feedback.forEach((element) => gsap.set(element, { clearProps: "all" }));
    } else {
        if (process) {
            revealTimeline(process, { stagger: 0.14 });
        }

        if (cards) {
            const cardItems = cards.querySelectorAll('[data-gsap="card"]');
            gsap.set(cardItems, { autoAlpha: 0, y: 18 });
            gsap.timeline({ scrollTrigger: { trigger: cards, start: "top 82%", once: true } }).to(cardItems, {
                autoAlpha: 1,
                duration: 0.65,
                ease: "power3.out",
                stagger: 0.1,
                y: 0,
            });
        }

        feedback.forEach((element) => gsap.fromTo(element, { autoAlpha: 0, y: 8 }, {
            autoAlpha: 1,
            duration: 0.3,
            ease: "power2.out",
            y: 0,
        }));
    }

    const handleFulfillmentChange = (event) => {
        animateDeliveryAddress(root, event.detail?.fulfillmentType, reducedMotion);
    };

    root.addEventListener("order-inquiry:fulfillment-change", handleFulfillmentChange);
    animateDeliveryAddress(
        root,
        root.querySelector('input[name="fulfillment_type"]:checked')?.value ?? "pickup",
        reducedMotion,
    );

    return () => root.removeEventListener("order-inquiry:fulfillment-change", handleFulfillmentChange);
}

function initializeHomeMotion(root, { desktop, reducedMotion }) {
    if (reducedMotion) {
        return;
    }

    const heroImage = root.querySelector("[data-gsap=hero-image]");
    if (heroImage?.querySelector("img")) {
        gsap.fromTo(heroImage.querySelector("img"), { scale: 1.06 }, {
            scale: 1, duration: 1.6, ease: "power4.out",
        });
        addParallax(heroImage, desktop ? 3 : 1.5);
    }

    const heroContent = root.querySelector("[data-gsap=hero-content]");
    if (heroContent) {
        const heroItems = heroContent.querySelectorAll("[data-gsap-reveal]");
        gsap.set(heroItems, { autoAlpha: 0, y: 24 });
        gsap.timeline({ defaults: { ease: "power4.out" } }).to(heroItems, {
            autoAlpha: 1, duration: 1.1, stagger: 0.12, y: 0,
        });
    }

    const discoverLine = root.querySelector("[data-gsap=discover-line]");
    if (discoverLine) {
        gsap.fromTo(discoverLine, { scaleY: 0, transformOrigin: "top center" }, {
            scaleY: 1, duration: 1, delay: 1.2, ease: "power3.out",
        });
    }

    root.querySelectorAll("[data-gsap=section]").forEach((section) => revealTimeline(section));
    root.querySelectorAll("[data-gsap=image]").forEach((image) => revealImage(image));
    root.querySelectorAll("[data-gsap=parallax]").forEach((image) => addParallax(image, desktop ? 4 : 2));
    root.querySelectorAll("[data-gsap=frame]").forEach((frame) => addGoldFrame(frame));

    const menu = root.querySelector("[data-gsap=menu]");
    if (menu) {
        const cards = menu.querySelectorAll("[data-gsap=card]");
        gsap.set(cards, { autoAlpha: 0, y: revealDistance });
        gsap.timeline({ scrollTrigger: { trigger: menu, start: "top 78%", once: true } }).to(cards, {
            autoAlpha: 1, duration: 0.8, ease: "power3.out", stagger: 0.12, y: 0,
        });
    }

    root.querySelectorAll("[data-gsap=panel]").forEach((panel, index) => {
        const offset = desktop
            ? { x: index % 2 === 0 ? -32 : 32, y: 0 }
            : { x: 0, y: revealDistance };

        gsap.fromTo(panel, { autoAlpha: 0, ...offset }, {
            autoAlpha: 1,
            duration: 0.9,
            ease: "power3.out",
            scrollTrigger: { trigger: panel.parentElement, start: "top 78%", once: true },
            x: 0,
            y: 0,
        });
    });

    const gallery = root.querySelector("[data-gsap=gallery]");
    if (gallery) {
        gsap.fromTo(gallery.querySelectorAll("[data-gsap=tile]"), { autoAlpha: 0, y: 24 }, {
            autoAlpha: 1, duration: 0.8, ease: "power3.out",
            scrollTrigger: { trigger: gallery, start: "top 80%", once: true }, stagger: 0.12, y: 0,
        });
    }
}

export function initPublicAnimations(root = document.querySelector("[data-home-motion]")) {
    if (!root) {
        return () => {};
    }

    const media = gsap.matchMedia();

    media.add({
        reducedMotion: "(prefers-reduced-motion: reduce)",
        desktop: "(min-width: 1024px)",
        mobile: "(max-width: 1023px)",
    }, (context) => {
        const { desktop = false, reducedMotion = false } = context.conditions;

        initializeHomeMotion(root, { desktop, reducedMotion });

        if (root.matches("[data-order-inquiry-motion]")) {
            initializeOrderInquiryMotion(root, { reducedMotion });
        }
    });

    const cleanup = () => media.revert();

    if (import.meta.hot) {
        import.meta.hot.dispose(cleanup);
    }

    return cleanup;
}
