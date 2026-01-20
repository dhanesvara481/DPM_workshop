import "../css/app.css";

(() => {
    const reduceMotion = window.matchMedia(
        "(prefers-reduced-motion: reduce)",
    ).matches;

    // Elements
    const scene = document.getElementById("loginScene");
    const card = document.getElementById("loginCard");
    const glowA = document.getElementById("glowA");
    const glowB = document.getElementById("glowB");

    const form = document.getElementById("loginForm");
    const btn = document.getElementById("loginBtn");
    const btnText = document.getElementById("loginBtnText");
    const shimmer = document.getElementById("btnShimmer");

    const toggleBtn = document.getElementById("togglePassword");
    const passwordInput = document.getElementById("password");
    const eyeOpen = document.getElementById("eyeOpen");
    const eyeClosed = document.getElementById("eyeClosed");

    const loginError = document.getElementById("loginError");

    // Error shake (if server returns error)
    if (
        !reduceMotion &&
        (loginError || (form && form.querySelector(".text-red-600")))
    ) {
        // shake the card once on load when error exists
        requestAnimationFrame(() => {
            if (card) {
                card.classList.remove("shake");
                void card.offsetWidth; // reflow
                card.classList.add("shake");
            }
        });
    }

    // Show/Hide password
    if (toggleBtn && passwordInput) {
        toggleBtn.addEventListener("click", () => {
            const isHidden = passwordInput.type === "password";
            passwordInput.type = isHidden ? "text" : "password";
            toggleBtn.setAttribute(
                "aria-label",
                isHidden ? "Sembunyikan password" : "Tampilkan password",
            );

            if (eyeOpen && eyeClosed) {
                eyeOpen.classList.toggle("hidden", !isHidden);
                eyeClosed.classList.toggle("hidden", isHidden);
            }
        });
    }

    // Loading shimmer on submit (UX feel)
    if (form && btn && btnText && shimmer) {
        form.addEventListener("submit", () => {
            btn.disabled = true;
            btn.classList.add("opacity-95");
            btnText.textContent = "Memproses...";
            shimmer.classList.remove("hidden");
            shimmer.classList.add("animate-btn-shimmer");
        });
    }

    if (reduceMotion) return;
    if (!scene || !card) return;

    // Parallax + Tilt
    const clamp = (n, min, max) => Math.max(min, Math.min(max, n));
    let raf = null;
    let tx = 0,
        ty = 0;

    const onMove = (e) => {
        const rect = scene.getBoundingClientRect();
        const x = (e.clientX - rect.left) / rect.width - 0.5;
        const y = (e.clientY - rect.top) / rect.height - 0.5;

        tx = x;
        ty = y;

        if (!raf) {
            raf = requestAnimationFrame(() => {
                raf = null;

                // Tilt the whole block a bit
                const tiltX = clamp(-ty * 6, -6, 6);
                const tiltY = clamp(tx * 9, -9, 9);
                card.style.transform = `perspective(1200px) rotateX(${tiltX}deg) rotateY(${tiltY}deg) translateZ(0)`;

                // Parallax glows
                if (glowA)
                    glowA.style.transform = `translate(calc(-50% + ${tx * 28}px), ${ty * 18}px)`;
                if (glowB)
                    glowB.style.transform = `translate(${tx * -22}px, ${ty * -16}px)`;
            });
        }
    };

    const onLeave = () => {
        card.style.transform =
            "perspective(1200px) rotateX(0deg) rotateY(0deg) translateZ(0)";
        if (glowA) glowA.style.transform = "translate(-50%, 0)";
        if (glowB) glowB.style.transform = "translate(0, 0)";
    };

    scene.addEventListener("mousemove", onMove);
    scene.addEventListener("mouseleave", onLeave);
})();
