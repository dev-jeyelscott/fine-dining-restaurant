import gsap from "gsap";

const wrap = (value, length) => ((value % length) + length) % length;

export function initHomeGalleryCarousel(root, { reducedMotion = false } = {}) {
    if (!root) {
        return () => {};
    }

    const slides = [...root.querySelectorAll("[data-home-gallery-slide]")];
    const viewport = slides[0]?.parentElement;

    if (!viewport || slides.length === 0) {
        return () => {};
    }

    const count = slides.length;
    const originalGalleryMotion = root.getAttribute("data-gsap");
    const originalTabIndex = viewport.getAttribute("tabindex");
    const captions = slides
        .map((slide) => [...slide.children].find((child) => child.classList.contains("bottom-0") && child.classList.contains("inset-x-0")))
        .filter(Boolean);
    const controls = root.querySelector("[data-home-gallery-controls]");
    const previous = controls?.querySelector("[data-home-gallery-previous]");
    const next = controls?.querySelector("[data-home-gallery-next]");
    const current = controls?.querySelector("[data-home-gallery-current]");

    root.removeAttribute("data-gsap");
    root.dataset.homeGalleryEnhanced = "";
    root.setAttribute("aria-label", "Restaurant gallery preview");
    root.setAttribute("aria-roledescription", "carousel");

    viewport.dataset.homeGalleryViewport = "";
    viewport.setAttribute("tabindex", "0");

    slides.forEach((slide, index) => {
        slide.dataset.homeGallerySlide = "";
        slide.dataset.index = String(index);
        slide.setAttribute("aria-label", `${index + 1} of ${count}`);
        slide.querySelectorAll("img").forEach((image) => image.setAttribute("draggable", "false"));
    });

    let currentIndex = 0;
    let isAnimating = false;
    let queuedTarget = null;
    let activeTimeline = null;
    let pointerId = null;
    let pointerStartX = null;

    const captionFor = (slide) => captions.find((caption) => caption.parentElement === slide) ?? null;

    const stateVars = (state) => ({
        autoAlpha: state === "current" ? 1 : state === "hidden" ? 0 : 0.48,
        scale: state === "current" ? 1 : 0.9,
        xPercent: state === "previous" ? -72 : state === "next" ? -28 : -50,
        zIndex: state === "current" ? 20 : state === "hidden" ? 0 : 10,
    });

    const assignStates = () => {
        const previousIndex = wrap(currentIndex - 1, count);
        const nextIndex = wrap(currentIndex + 1, count);

        slides.forEach((slide, index) => {
            let state = "hidden";

            if (index === currentIndex) {
                state = "current";
            } else if (count === 2 && index === nextIndex) {
                state = "next";
            } else if (index === previousIndex) {
                state = "previous";
            } else if (index === nextIndex) {
                state = "next";
            }

            slide.dataset.state = state;
            slide.setAttribute("aria-current", state === "current" ? "true" : "false");
            slide.setAttribute("aria-hidden", state === "current" ? "false" : "true");

            if (state === "current") {
                slide.removeAttribute("inert");
            } else {
                slide.setAttribute("inert", "");
            }
        });

        if (current) {
            current.textContent = String(currentIndex + 1).padStart(2, "0");
        }

    };

    const renderImmediate = () => {
        assignStates();

        slides.forEach((slide) => {
            const state = slide.dataset.state;
            const caption = captionFor(slide);

            gsap.set(slide, stateVars(state));

            if (caption) {
                gsap.set(caption, {
                    autoAlpha: state === "current" ? 1 : 0,
                    y: state === "current" ? 0 : 12,
                });
            }
        });
    };

    const finishTransition = () => {
        isAnimating = false;
        activeTimeline = null;

        if (queuedTarget !== null) {
            const target = queuedTarget;
            queuedTarget = null;
            transitionTo(target);
        }
    };

    const transitionTo = (targetIndex) => {
        const target = wrap(targetIndex, count);

        if (target === currentIndex) {
            return;
        }

        if (isAnimating) {
            queuedTarget = target;
            return;
        }

        currentIndex = target;
        assignStates();

        if (reducedMotion) {
            renderImmediate();
            return;
        }

        isAnimating = true;
        activeTimeline?.kill();
        activeTimeline = gsap.timeline({
            defaults: { ease: "power3.inOut" },
            onComplete: finishTransition,
        });

        slides.forEach((slide) => {
            const state = slide.dataset.state;
            const caption = captionFor(slide);

            activeTimeline.to(slide, { ...stateVars(state), duration: 0.88 }, 0);

            if (caption) {
                activeTimeline.to(caption, {
                    autoAlpha: state === "current" ? 1 : 0,
                    duration: state === "current" ? 0.5 : 0.3,
                    ease: "power3.out",
                    y: state === "current" ? 0 : 12,
                }, state === "current" ? 0.42 : 0);
            }
        });
    };

    const handleKeydown = (event) => {
        if (count === 1 || !["ArrowLeft", "ArrowRight", "Home", "End"].includes(event.key)) {
            return;
        }

        event.preventDefault();

        if (event.key === "ArrowLeft") transitionTo(currentIndex - 1);
        if (event.key === "ArrowRight") transitionTo(currentIndex + 1);
        if (event.key === "Home") transitionTo(0);
        if (event.key === "End") transitionTo(count - 1);
    };

    const handlePrevious = () => transitionTo(currentIndex - 1);
    const handleNext = () => transitionTo(currentIndex + 1);

    const resetPointer = () => {
        pointerId = null;
        pointerStartX = null;
    };

    const handlePointerDown = (event) => {
        if (count === 1 || !event.isPrimary || event.button !== 0) {
            return;
        }

        pointerId = event.pointerId;
        pointerStartX = event.clientX;
        viewport.setPointerCapture?.(event.pointerId);
    };

    const handlePointerUp = (event) => {
        if (pointerStartX === null || pointerId !== event.pointerId) {
            return;
        }

        const delta = event.clientX - pointerStartX;
        viewport.releasePointerCapture?.(event.pointerId);
        resetPointer();

        if (Math.abs(delta) >= 48) {
            transitionTo(currentIndex + (delta < 0 ? 1 : -1));
        }
    };

    previous?.addEventListener("click", handlePrevious);
    next?.addEventListener("click", handleNext);
    viewport.addEventListener("keydown", handleKeydown);
    viewport.addEventListener("pointerdown", handlePointerDown);
    viewport.addEventListener("pointerup", handlePointerUp);
    viewport.addEventListener("pointercancel", resetPointer);

    const resizeObserver = typeof ResizeObserver === "undefined"
        ? null
        : new ResizeObserver(renderImmediate);

    resizeObserver?.observe(viewport);
    renderImmediate();

    return () => {
        activeTimeline?.kill();
        resizeObserver?.disconnect();
        previous?.removeEventListener("click", handlePrevious);
        next?.removeEventListener("click", handleNext);
        viewport.removeEventListener("keydown", handleKeydown);
        viewport.removeEventListener("pointerdown", handlePointerDown);
        viewport.removeEventListener("pointerup", handlePointerUp);
        viewport.removeEventListener("pointercancel", resetPointer);
        controls?.remove();
        queuedTarget = null;
        gsap.killTweensOf(slides);
        gsap.killTweensOf(captions);

        slides.forEach((slide) => {
            slide.removeAttribute("aria-current");
            slide.removeAttribute("aria-hidden");
            slide.removeAttribute("aria-label");
            slide.removeAttribute("data-home-gallery-slide");
            slide.removeAttribute("data-index");
            slide.removeAttribute("data-state");
            slide.removeAttribute("inert");
            gsap.set(slide, { clearProps: "all" });
        });

        captions.forEach((caption) => gsap.set(caption, { clearProps: "all" }));
        viewport.removeAttribute("data-home-gallery-viewport");

        if (originalTabIndex === null) {
            viewport.removeAttribute("tabindex");
        } else {
            viewport.setAttribute("tabindex", originalTabIndex);
        }

        root.removeAttribute("data-home-gallery-enhanced");
        root.removeAttribute("aria-label");
        root.removeAttribute("aria-roledescription");

        if (originalGalleryMotion !== null) {
            root.setAttribute("data-gsap", originalGalleryMotion);
        }
    };
}
