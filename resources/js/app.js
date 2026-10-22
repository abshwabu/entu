import './bootstrap';
import Alpine from 'alpinejs';
import intersect from '@alpinejs/intersect';

Alpine.plugin(intersect);
window.Alpine = Alpine;
Alpine.start();

// Custom subtle cursor (strictly enabled on fine pointers and respecting reduced motion)
document.addEventListener('DOMContentLoaded', () => {
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const isFinePointer = window.matchMedia('(pointer: fine)').matches;

    if (!prefersReducedMotion && isFinePointer) {
        const cursor = document.getElementById('custom-cursor');
        const cursorDot = document.getElementById('custom-cursor-dot');

        if (cursor && cursorDot) {
            let mouseX = -100, mouseY = -100;
            let cursorX = -100, cursorY = -100;
            let isHovered = false;

            window.addEventListener('mousemove', (e) => {
                mouseX = e.clientX;
                mouseY = e.clientY;
                cursorDot.style.transform = `translate3d(${mouseX}px, ${mouseY}px, 0)`;
            }, { passive: true });

            const animate = () => {
                cursorX += (mouseX - cursorX) * 0.18;
                cursorY += (mouseY - cursorY) * 0.18;
                cursor.style.transform = `translate3d(${cursorX}px, ${cursorY}px, 0) translate(-50%, -50%)`;
                requestAnimationFrame(animate);
            };
            requestAnimationFrame(animate);

            const addHoverListeners = () => {
                document.querySelectorAll('a, button, [role="button"], input, textarea, select, .cursor-interactive').forEach((el) => {
                    el.addEventListener('mouseenter', () => cursor.classList.add('cursor-hover'));
                    el.addEventListener('mouseleave', () => cursor.classList.remove('cursor-hover'));
                });
            };

            addHoverListeners();
            // Re-apply listeners if DOM dynamically updates
            const observer = new MutationObserver(addHoverListeners);
            observer.observe(document.body, { childList: true, subtree: true });
        }
    }
});
