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

function initializeHeroMotion(root, { desktop }) {
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
}

function setMenuFinalStates(root) {
    gsap.set(root.querySelectorAll('[data-menu-motion="hero-image"]'), { clipPath: "inset(0 0 0% 0)", scale: 1 });
    gsap.set(root.querySelectorAll('[data-menu-motion="hero-item"], [data-menu-motion="full-heading"], [data-menu-motion="closing-item"], [data-menu-motion="closing-actions"]'), { autoAlpha: 1, x: 0, y: 0 });
    gsap.set(root.querySelectorAll('[data-menu-motion="course-number"], [data-menu-motion="course-title"], [data-menu-motion="course-description"], [data-menu-motion="course-rule"], [data-menu-motion="card"], [data-menu-motion="card-copy"], [data-menu-motion="card-image"]'), { autoAlpha: 1, x: 0, y: 0, clipPath: "inset(0 0 0% 0)", scale: 1, scaleX: 1 });

    const links = [...root.querySelectorAll('[data-menu-category-link]')];
    const activeLink = links.find((link) => link.getAttribute("href") === window.location.hash) ?? links[0];

    links.forEach((link) => link.setAttribute("aria-current", link === activeLink ? "true" : "false"));
}

function initializeMenuMotion(root, { reducedMotion }) {
    const hero = root.querySelector('[data-menu-motion="hero"]');
    const categoryNav = root.querySelector('[data-menu-motion="category-nav"]');
    const fullHeading = root.querySelector('[data-menu-motion="full-heading"]');
    const courses = [...root.querySelectorAll('[data-menu-motion="course"]')];
    const closingCta = root.querySelector('[data-menu-motion="closing-cta"]');
    const cleanup = [];

    if (reducedMotion) {
        setMenuFinalStates(root);

        return;
    }

    if (hero) {
        const image = hero.querySelector('[data-menu-motion="hero-image"]');
        const items = hero.querySelectorAll('[data-menu-motion="hero-item"]');

        if (image) {
            gsap.fromTo(image, { clipPath: "inset(0 0 100% 0)", scale: 1.05 }, { clipPath: "inset(0 0 0% 0)", scale: 1, duration: 1.4, ease: "power4.out" });
        }

        gsap.set(items, { autoAlpha: 0, y: 20 });
        gsap.timeline({ defaults: { ease: "power4.out" } }).to(items, {
            autoAlpha: 1,
            duration: 0.9,
            stagger: 0.11,
            y: 0,
        });
    }

    if (categoryNav) {
        const links = [...categoryNav.querySelectorAll('[data-menu-category-link]')];
        const indicator = categoryNav.querySelector('[data-menu-category-indicator]');
        const scroller = categoryNav.querySelector(":scope > div");
        let activeLink = links.find((link) => link.getAttribute("href") === window.location.hash)
            ?? links.find((link) => link.getAttribute("aria-current") === "true")
            ?? links[0];

        links.forEach((link) => link.setAttribute("aria-current", link === activeLink ? "true" : "false"));

        const positionIndicator = () => {
            if (!indicator || !scroller || !activeLink) {
                return;
            }

            gsap.to(indicator, {
                duration: 0.45,
                ease: "power3.out",
                width: activeLink.offsetWidth,
                x: activeLink.offsetLeft,
            });
        };

        const setActiveLink = (link) => {
            if (!link || link === activeLink) {
                return;
            }

            links.forEach((candidate) => candidate.setAttribute("aria-current", candidate === link ? "true" : "false"));
            activeLink = link;
            positionIndicator();
        };

        links.forEach((link) => {
            const handleClick = () => setActiveLink(link);

            link.addEventListener("click", handleClick);
            cleanup.push(() => link.removeEventListener("click", handleClick));
        });
        positionIndicator();
        ScrollTrigger.addEventListener("refresh", positionIndicator);
        cleanup.push(() => ScrollTrigger.removeEventListener("refresh", positionIndicator));

        courses.forEach((course, index) => {
            const link = links[index];

            ScrollTrigger.create({
                end: "bottom 42%",
                onEnter: () => setActiveLink(link),
                onEnterBack: () => setActiveLink(link),
                start: "top 42%",
                trigger: course,
            });
        });
    }

    if (fullHeading) {
        const headingItems = fullHeading.querySelectorAll('p, h2, div[aria-hidden="true"]');
        gsap.set(headingItems, { autoAlpha: 0, y: 18 });
        gsap.timeline({ scrollTrigger: { once: true, start: "top 82%", trigger: fullHeading } }).to(headingItems, {
            autoAlpha: 1,
            duration: 0.75,
            ease: "power3.out",
            stagger: 0.1,
            y: 0,
        });
    }

    courses.forEach((course) => {
        const courseItems = course.querySelectorAll('[data-menu-motion="course-number"], [data-menu-motion="course-title"], [data-menu-motion="course-description"]');
        const courseRule = course.querySelector('[data-menu-motion="course-rule"]');
        const images = course.querySelectorAll('[data-menu-motion="card-image"]');
        const copies = course.querySelectorAll('[data-menu-motion="card-copy"]');

        gsap.set(courseItems, { autoAlpha: 0, y: 20 });
        if (courseRule) {
            gsap.set(courseRule, { autoAlpha: 1, scaleX: 0, transformOrigin: "left center", y: 0 });
        }
        gsap.set(images, { clipPath: "inset(0 0 100% 0)" });
        gsap.set(copies, { autoAlpha: 0, y: 18 });

        const timeline = gsap.timeline({
            scrollTrigger: { once: true, start: "top 78%", trigger: course },
        });

        timeline.to(course.querySelector('[data-menu-motion="course-number"]'), { autoAlpha: 1, duration: 0.55, ease: "power3.out", y: 0 })
            .to(course.querySelectorAll('[data-menu-motion="course-title"], [data-menu-motion="course-description"]'), { autoAlpha: 1, duration: 0.7, ease: "power3.out", stagger: 0.08, y: 0 }, "<0.12");

        if (courseRule) {
            timeline.to(courseRule, { autoAlpha: 1, duration: 0.55, ease: "power3.out", scaleX: 1, y: 0 }, "<0.12");
        }

        timeline.to(images, { clipPath: "inset(0 0 0% 0)", duration: 0.9, ease: "power4.out", stagger: { each: 0.08, from: "start" } }, "<0.18")
            .to(copies, { autoAlpha: 1, duration: 0.65, ease: "power3.out", stagger: { each: 0.08, from: "start" }, y: 0 }, "<0.16");
    });

    if (closingCta) {
        const items = closingCta.querySelectorAll('[data-menu-motion="closing-item"], [data-menu-motion="closing-actions"]');
        gsap.set(items, { autoAlpha: 0, y: 20 });
        gsap.timeline({ scrollTrigger: { once: true, start: "top 82%", trigger: closingCta } }).to(items, {
            autoAlpha: 1,
            duration: 0.8,
            ease: "power3.out",
            stagger: 0.1,
            y: 0,
        });
    }

    return () => cleanup.forEach((callback) => callback());
}

function animateDeliveryAddress(root, fulfillmentType, reducedMotion, { animate = true } = {}) {
    const panel = root.querySelector('[data-gsap="delivery-address"]');

    if (!panel) {
        return;
    }

    const isDelivery = fulfillmentType === "delivery";

    if (reducedMotion || !animate) {
        gsap.killTweensOf(panel);
        gsap.set(panel, {
            autoAlpha: isDelivery ? 1 : 0,
            clearProps: "height,transform",
            display: isDelivery ? "block" : "none",
        });
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

function initializeOrderInquiryMotion(root, { desktop, reducedMotion }) {
    const sidebar = root.querySelector("aside[data-gsap-reveal]");
    const process = root.querySelector('[data-gsap="process"]');
    const form = root.querySelector('[data-gsap="form-column"] form');
    const cards = root.querySelector('[data-gsap="fulfillment-cards"]');
    const feedback = root.querySelectorAll('[data-gsap="feedback"]');
    const deliveryPanel = root.querySelector('[data-gsap="delivery-address"]');

    if (reducedMotion) {
        root.querySelectorAll("[data-gsap-reveal]").forEach((element) => gsap.set(element, { clearProps: "all" }));
        root.querySelectorAll('[data-gsap="card"]').forEach((element) => gsap.set(element, { clearProps: "all" }));
        feedback.forEach((element) => gsap.set(element, { clearProps: "all" }));
    } else {
        initializeHeroMotion(root, { desktop });

        if (sidebar) {
            gsap.fromTo(sidebar, { autoAlpha: 0, x: desktop ? -32 : 0, y: desktop ? 0 : revealDistance }, {
                autoAlpha: 1,
                duration: 0.9,
                ease: "power3.out",
                scrollTrigger: { trigger: sidebar.parentElement, start: "top 78%", once: true },
                x: 0,
                y: 0,
            });
        }

        if (process) {
            revealTimeline(process, { stagger: 0.14 });
        }

        if (form) {
            revealTimeline(form, { start: "top 84%", stagger: 0.08 });
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
        { animate: false },
    );

    return () => {
        root.removeEventListener("order-inquiry:fulfillment-change", handleFulfillmentChange);
        if (deliveryPanel) {
            gsap.killTweensOf(deliveryPanel);
        }
    };
}

function initializeHomeMotion(root, { desktop, reducedMotion }) {
    if (reducedMotion) {
        return;
    }

    initializeHeroMotion(root, { desktop });

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

        if (root.dataset.publicMotion === "menu") {
            return initializeMenuMotion(root, { reducedMotion });
        }

        if (root.matches("[data-order-inquiry-motion]")) {
            return initializeOrderInquiryMotion(root, { desktop, reducedMotion });
        }

        initializeHomeMotion(root, { desktop, reducedMotion });
    });

    const cleanup = () => media.revert();

    if (import.meta.hot) {
        import.meta.hot.dispose(cleanup);
    }

    return cleanup;
}
