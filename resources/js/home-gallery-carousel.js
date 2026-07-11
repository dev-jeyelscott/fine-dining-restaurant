import gsap from "gsap";
import { Draggable } from "gsap/Draggable";

gsap.registerPlugin(Draggable);

const wrap = (value, length) => (value + length) % length;

export function initHomeGalleryCarousel(root, { reducedMotion = false } = {}) {
    const viewport = root.querySelector("[data-home-gallery-viewport]");
    const track = root.querySelector("[data-home-gallery-track]");
    const slides = [...root.querySelectorAll("[data-home-gallery-slide]")];
    const previous = root.querySelector("[data-home-gallery-previous]");
    const next = root.querySelector("[data-home-gallery-next]");
    const current = root.querySelector("[data-home-gallery-current]");

    if (!viewport || !track || slides.length === 0) {
        return;
    }

    if (slides.length === 1) {
        slides[0].setAttribute("aria-hidden", "false");
        slides[0].querySelector("[data-home-gallery-visual]")?.style.removeProperty("opacity");
        slides[0].querySelector("[data-home-gallery-visual]")?.style.removeProperty("transform");
        return;
    }

    const originals = [...slides];
    const clones = [slides[slides.length - 1].cloneNode(true), slides[0].cloneNode(true)];
    clones.forEach((clone) => {
        clone.dataset.homeGalleryClone = "true";
        clone.setAttribute("aria-hidden", "true");
        clone.setAttribute("inert", "");
    });
    track.prepend(clones[0]);
    track.append(clones[1]);

    let activeIndex = 0;
    let draggable;
    let transition;
    let resizeObserver;

    const allSlides = [clones[0], ...originals, clones[1]];
    const visual = (slide) => slide.querySelector("[data-home-gallery-visual]");
    const caption = (slide) => slide.querySelector("[data-home-gallery-caption] > div");

    const offsetFor = (index) => -allSlides[index].offsetLeft + (viewport.clientWidth - allSlides[index].offsetWidth) / 2;

    const updateState = () => {
        originals.forEach((slide, index) => {
            const active = index === activeIndex;
            slide.setAttribute("aria-current", active ? "true" : "false");
            slide.setAttribute("aria-hidden", active ? "false" : "true");
            if (!active) {
                slide.querySelectorAll("a, button, [tabindex]").forEach((element) => element.setAttribute("tabindex", "-1"));
            } else {
                slide.querySelectorAll("a, button, [tabindex]").forEach((element) => element.removeAttribute("tabindex"));
            }
        });
        if (current) {
            current.textContent = String(activeIndex + 1).padStart(2, "0");
        }
    };

    const setVisualStates = () => {
        allSlides.forEach((slide, index) => {
            if (reducedMotion) {
                gsap.set(visual(slide), { opacity: 1, scale: 1 });
                return;
            }
            const distance = Math.min(Math.abs(index - (activeIndex + 1)), originals.length - Math.abs(index - (activeIndex + 1)));
            gsap.set(visual(slide), { opacity: distance === 0 ? 1 : 0.55, scale: distance === 0 ? 1 : 0.9 });
        });
    };

    const snapToActive = (animate = true) => {
        const targetIndex = activeIndex + 1;
        const targetX = offsetFor(targetIndex);
        const vars = { x: targetX, duration: reducedMotion || !animate ? 0 : 0.95, ease: "power4.inOut", overwrite: true };
        transition?.kill();
        transition = gsap.to(track, vars);
        setVisualStates();
        updateState();
    };

    const normalizeLoop = () => {
        if (activeIndex < 0) {
            activeIndex = originals.length - 1;
            gsap.set(track, { x: offsetFor(originals.length) });
        } else if (activeIndex >= originals.length) {
            activeIndex = 0;
            gsap.set(track, { x: offsetFor(1) });
        }
        updateState();
        setVisualStates();
    };

    const goTo = (index) => {
        activeIndex = index;
        snapToActive(true);
        if (index < 0 || index >= originals.length) {
            gsap.delayedCall(reducedMotion ? 0 : 0.96, normalizeLoop);
        }
    };

    const handlePrevious = () => goTo(activeIndex - 1);
    const handleNext = () => goTo(activeIndex + 1);
    const handleKeydown = (event) => {
        if (event.key === "ArrowLeft") { event.preventDefault(); handlePrevious(); }
        if (event.key === "ArrowRight") { event.preventDefault(); handleNext(); }
        if (event.key === "Home") { event.preventDefault(); goTo(0); }
        if (event.key === "End") { event.preventDefault(); goTo(originals.length - 1); }
    };

    const refresh = () => snapToActive(false);

    previous?.addEventListener("click", handlePrevious);
    next?.addEventListener("click", handleNext);
    track.addEventListener("keydown", handleKeydown);

    if (reducedMotion) {
        track.classList.add("snap-x", "snap-mandatory", "overflow-x-auto");
        allSlides.forEach((slide) => slide.classList.add("snap-center"));
    } else {
        draggable = Draggable.create(track, {
            allowNativeTouchScrolling: "y",
            cursor: "grab",
            dragResistance: 0.08,
            onDragStart: () => transition?.kill(),
            onDragEnd: function () {
                const delta = this.endX - this.startX;
                if (Math.abs(delta) > 36) {
                    delta < 0 ? handleNext() : handlePrevious();
                } else {
                    snapToActive(true);
                }
            },
            type: "x",
        })[0];
    }

    resizeObserver = new ResizeObserver(refresh);
    resizeObserver.observe(viewport);
    window.addEventListener("load", refresh, { once: true });
    snapToActive(false);

    const entrance = reducedMotion ? undefined : gsap.timeline({ scrollTrigger: { trigger: root, start: "top 80%", once: true } })
        .fromTo(viewport, { clipPath: "inset(0 0 100% 0)" }, { clipPath: "inset(0 0 0% 0)", duration: 1.1, ease: "power4.out" })
        .fromTo(visual(originals[0]), { scale: 1.04 }, { scale: 1, duration: 1.2, ease: "power3.out" }, "<0.1")
        .fromTo(root.querySelector("[data-home-gallery-controls]"), { autoAlpha: 0, y: 14 }, { autoAlpha: 1, y: 0, duration: 0.55 }, "<0.45");

    return () => {
        entrance?.kill();
        transition?.kill();
        draggable?.kill();
        resizeObserver?.disconnect();
        previous?.removeEventListener("click", handlePrevious);
        next?.removeEventListener("click", handleNext);
        track.removeEventListener("keydown", handleKeydown);
        clones.forEach((clone) => clone.remove());
    };
}
