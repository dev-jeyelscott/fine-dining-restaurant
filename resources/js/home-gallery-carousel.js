import gsap from "gsap";
const wrap = (value, length) => ((value % length) + length) % length;

export function initHomeGalleryCarousel(root, { reducedMotion = false } = {}) {
    if (!root) return;
    const slides = [...root.querySelectorAll("[data-home-gallery-slide]")];
    const viewport = root.querySelector("[data-home-gallery-viewport]");
    const track = root.querySelector("[data-home-gallery-track]");
    const previous = root.querySelector("[data-home-gallery-previous]");
    const next = root.querySelector("[data-home-gallery-next]");
    const current = root.querySelector("[data-home-gallery-current]");
    if (!viewport || !track || !slides.length) return;
    let currentIndex = 0;
    let isAnimating = false;
    let queuedTarget = null;
    let activeTimeline = null;
    let resizeObserver;
    const count = slides.length;
    const visual = (slide) => slide.querySelector("[data-home-gallery-visual]");
    const caption = (slide) => slide.querySelector("[data-home-gallery-caption]");
    const assignStates = () => {
        const previousIndex = wrap(currentIndex - 1, count);
        const nextIndex = wrap(currentIndex + 1, count);
        slides.forEach((slide, index) => {
            const state = count === 1 ? "current" : index === currentIndex ? "current" : index === previousIndex ? "previous" : index === nextIndex ? "next" : "hidden";
            slide.dataset.state = state;
            slide.setAttribute("aria-current", state === "current" ? "true" : "false");
            slide.setAttribute("aria-hidden", state === "current" ? "false" : "true");
            if (state === "current") slide.removeAttribute("inert");
            else slide.setAttribute("inert", "");
        });
        if (current) current.textContent = String(currentIndex + 1).padStart(2, "0");
    };
    const stateVars = (state) => ({ xPercent: state === "previous" ? -72 : state === "next" ? -28 : -50, scale: state === "current" ? 1 : 0.9, autoAlpha: state === "current" ? 1 : state === "hidden" ? 0 : 0.48, zIndex: state === "current" ? 20 : state === "hidden" ? 0 : 10 });
    const renderImmediate = () => { assignStates(); slides.forEach((slide) => { gsap.set(slide, stateVars(slide.dataset.state)); gsap.set(caption(slide), { autoAlpha: slide.dataset.state === "current" ? 1 : 0, y: slide.dataset.state === "current" ? 0 : 12 }); }); };
    const finish = () => {
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
        if (target === currentIndex) return;
        if (isAnimating) { queuedTarget = target; return; }
        currentIndex = target;
        assignStates();
        if (reducedMotion) { renderImmediate(); return; }
        isAnimating = true;
        activeTimeline?.kill();
        activeTimeline = gsap.timeline({ defaults: { ease: "power3.inOut" }, onComplete: finish });
        slides.forEach((slide) => { const state = slide.dataset.state; activeTimeline.to(slide, { ...stateVars(state), duration: 0.88 }, 0); activeTimeline.to(caption(slide), { autoAlpha: state === "current" ? 1 : 0, y: state === "current" ? 0 : 12, duration: state === "current" ? 0.5 : 0.3, ease: "power3.out" }, state === "current" ? 0.42 : 0); });
    };
    const handleKeydown = (event) => {
        if (!["ArrowLeft", "ArrowRight", "Home", "End"].includes(event.key) || count === 1) return;
        event.preventDefault();
        if (event.key === "ArrowLeft") transitionTo(currentIndex - 1);
        if (event.key === "ArrowRight") transitionTo(currentIndex + 1);
        if (event.key === "Home") transitionTo(0);
        if (event.key === "End") transitionTo(count - 1);
    };
    const handlePrevious = () => transitionTo(currentIndex - 1);
    const handleNext = () => transitionTo(currentIndex + 1);
    let pointerStartX = null;
    const handlePointerDown = (event) => { pointerStartX = event.clientX; };
    const handlePointerUp = (event) => {
        if (pointerStartX === null || count === 1) return;
        const delta = event.clientX - pointerStartX;
        pointerStartX = null;
        if (Math.abs(delta) >= 48) transitionTo(currentIndex + (delta < 0 ? 1 : -1));
    };
    if (count > 1) {
        previous?.addEventListener("click", handlePrevious); next?.addEventListener("click", handleNext);
        track.addEventListener("keydown", handleKeydown); track.addEventListener("pointerdown", handlePointerDown);
        track.addEventListener("pointerup", handlePointerUp);
    }
    resizeObserver = typeof ResizeObserver === "undefined" ? null : new ResizeObserver(renderImmediate);
    resizeObserver?.observe(viewport); renderImmediate();
    return () => {
        activeTimeline?.kill(); resizeObserver?.disconnect(); previous?.removeEventListener("click", handlePrevious);
        next?.removeEventListener("click", handleNext); track.removeEventListener("keydown", handleKeydown);
        track.removeEventListener("pointerdown", handlePointerDown); track.removeEventListener("pointerup", handlePointerUp);
        queuedTarget = null; gsap.killTweensOf(slides); gsap.killTweensOf(slides.map(caption));
        slides.forEach((slide) => { slide.removeAttribute("inert"); gsap.set(slide, { clearProps: "all" }); gsap.set(caption(slide), { clearProps: "all" }); });
    };
}
