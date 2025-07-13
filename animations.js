import { animate, stagger } from "https://cdn.skypack.dev/framer-motion";
import { ScrollTrigger } from "https://cdn.skypack.dev/gsap/ScrollTrigger";
gsap.registerPlugin(ScrollTrigger);

// Navbar animation
gsap.from("nav", { y: -100, opacity: 0, duration: 1, ease: "bounce.out" });

// Hero
gsap.from(".hero-content h1", { y: 100, opacity: 0, duration: 1, ease: "power3.out" });
gsap.from(".hero-content p", { y: 80, opacity: 0, duration: 1, delay: 0.3, ease: "power2.out" });
animate(document.querySelectorAll(".hero-buttons .btn"), { opacity: [0, 1], scale: [0.8, 1] }, { delay: stagger(0.2, { start: 0.8 }), duration: 0.4 });

// About
gsap.from(".about img", { x: -100, opacity: 0, duration: 1, scrollTrigger: { trigger: ".about", start: "top 80%" } });
gsap.from(".about h2, .about p, .about blockquote", { x: 100, opacity: 0, duration: 1, stagger: 0.2, scrollTrigger: { trigger: ".about", start: "top 80%" } });

// Menu cards
["#card1", "#card2", "#card3"].forEach((id, i) => {
  gsap.from(id, { opacity: 0, y: 50, delay: 0.2 + i * 0.2, duration: 1, scrollTrigger: { trigger: id, start: "top 80%" } });
});

// Deals
["#deal1", "#deal2"].forEach((id, i) => {
  gsap.from(id, {
    opacity: 0,
    y: 100,
    duration: 1,
    delay: i * 0.3,
    ease: "back.out(1.7)",
    scrollTrigger: { trigger: id, start: "top 90%" }
  });
});

// Testimonials
["#review1", "#review2", "#review3"].forEach((id, i) => {
  const x = i === 0 ? -100 : i === 2 ? 100 : 0;
  const y = i === 1 ? 100 : 0;
  gsap.from(id, { opacity: 0, x, y, duration: 1, scrollTrigger: { trigger: id, start: "top 90%" } });
});

// Contact
gsap.from("#orderForm", {
  opacity: 0, x: -100, duration: 1,
  scrollTrigger: { trigger: "#orderForm", start: "top 90%" }
});
gsap.from(".map-wrapper", {
  opacity: 0, scale: 0.8, duration: 1, delay: 0.3,
  scrollTrigger: { trigger: ".map-wrapper", start: "top 90%" }
});

// Footer
gsap.from("#footer h2", {
  opacity: 0, x: -100, duration: 1,
  scrollTrigger: { trigger: "#footer", start: "top 90%" }
});
gsap.from("#footer ul li", {
  opacity: 0, x: -30, duration: 0.5, stagger: 0.1,
  scrollTrigger: { trigger: "#footer", start: "top 90%" }
});
gsap.from("#footer .col-md-4:last-child img", {
  scale: 0, opacity: 0, duration: 0.6, stagger: 0.2,
  scrollTrigger: { trigger: "#footer", start: "top 90%" }
});

// Newsletter
gsap.from(".newsletter h3, .newsletter p", {
  opacity: 0, y: 50, duration: 1,
  scrollTrigger: { trigger: ".newsletter", start: "top 90%" }
});
gsap.from(".newsletter input, .newsletter button", {
  opacity: 0, y: 30, duration: 1, delay: 0.3, stagger: 0.2,
  scrollTrigger: { trigger: ".newsletter", start: "top 90%" }
});
gsap.from(".card", {
  opacity: 0,
  y: 50,
  duration: 0.8,
  stagger: 0.2,
  scrollTrigger: {
    trigger: ".card",
    start: "top 80%"
  }
});
