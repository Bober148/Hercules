document.addEventListener("DOMContentLoaded", () => {
  const track = document.getElementById("motivationTrack");
  const dotsWrap = document.getElementById("motivationDots");
  const prevBtn = document.querySelector(".slider-btn.prev");
  const nextBtn = document.querySelector(".slider-btn.next");

  if (!track || !dotsWrap) return;

  const slides = Array.from(track.querySelectorAll(".slide"));
  if (slides.length === 0) return;

  let index = 0;
  let scrollTimer = null;

  // dots
  const dots = slides.map((_, i) => {
    const b = document.createElement("button");
    b.type = "button";
    b.className = "slider-dot" + (i === 0 ? " active" : "");
    b.setAttribute("aria-label", `Go to slide ${i + 1}`);
    b.addEventListener("click", () => goTo(i));
    dotsWrap.appendChild(b);
    return b;
  });

  function slideWidth() {
    return track.clientWidth;
  }

  function updateDots() {
    dots.forEach((d, i) => d.classList.toggle("active", i === index));
  }

  function goTo(i) {
    index = Math.max(0, Math.min(slides.length - 1, i));
    track.scrollTo({ left: index * slideWidth(), behavior: "smooth" });
    updateDots();
  }

  function syncIndex() {
    const w = slideWidth();
    if (!w) return;
    index = Math.round(track.scrollLeft / w);
    updateDots();
  }

  if (prevBtn) prevBtn.addEventListener("click", () => goTo(index - 1));
  if (nextBtn) nextBtn.addEventListener("click", () => goTo(index + 1));

  track.addEventListener("scroll", () => {
    if (scrollTimer) clearTimeout(scrollTimer);
    scrollTimer = setTimeout(syncIndex, 80);
  });

  window.addEventListener("resize", () => {
    track.scrollTo({ left: index * slideWidth(), behavior: "auto" });
    updateDots();
  });

  goTo(0);
});


  document.addEventListener("DOMContentLoaded", () => {
    const burger = document.getElementById("burgerBtn");
    const mobileNav = document.getElementById("mobileNav");
    if (!burger || !mobileNav) return;

    burger.addEventListener("click", () => {
      mobileNav.classList.toggle("is-open");
    });

    mobileNav.querySelectorAll("a").forEach(a => {
      a.addEventListener("click", () => mobileNav.classList.remove("is-open"));
    });
  });

