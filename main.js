/* main.js – Portfolio interactions & animations */
'use strict';

/* ══════════════════════════════════════════════════════
   1. NAV – scroll state + mobile toggle + active link
   ══════════════════════════════════════════════════════ */
(function initNav() {
  const nav    = document.getElementById('navbar');
  const toggle = document.getElementById('navToggle');
  const links  = document.getElementById('navLinks');
  const navAs  = links.querySelectorAll('a');

  window.addEventListener('scroll', () => {
    nav.classList.toggle('scrolled', window.scrollY > 40);
  }, { passive: true });

  toggle.addEventListener('click', () => {
    toggle.classList.toggle('open');
    links.classList.toggle('open');
  });

  navAs.forEach(a => a.addEventListener('click', () => {
    toggle.classList.remove('open');
    links.classList.remove('open');
  }));

  const sections = document.querySelectorAll('section[id]');
  const observer = new IntersectionObserver(entries => {
    entries.forEach(e => {
      if (e.isIntersecting) {
        navAs.forEach(a => a.classList.remove('active'));
        const active = links.querySelector(`a[href="#${e.target.id}"]`);
        if (active) active.classList.add('active');
      }
    });
  }, { rootMargin: '-40% 0px -55% 0px' });

  sections.forEach(s => observer.observe(s));
})();


/* ══════════════════════════════════════════════════════
   2. SCROLL REVEAL
   ══════════════════════════════════════════════════════ */
(function initReveal() {
  const els = document.querySelectorAll('.reveal');
  const obs = new IntersectionObserver(entries => {
    entries.forEach(e => {
      if (e.isIntersecting) {
        e.target.classList.add('visible');
        obs.unobserve(e.target);
      }
    });
  }, { threshold: 0.12 });
  els.forEach(el => obs.observe(el));
})();


/* ══════════════════════════════════════════════════════
   3. SKILL BARS – animate on reveal
   ══════════════════════════════════════════════════════ */
(function initSkillBars() {
  const fills = document.querySelectorAll('.skill-fill');
  const obs = new IntersectionObserver(entries => {
    entries.forEach(e => {
      if (e.isIntersecting) {
        const fill = e.target;
        const level = fill.dataset.level;
        setTimeout(() => { fill.style.width = level + '%'; }, 200);
        obs.unobserve(fill);
      }
    });
  }, { threshold: 0.3 });
  fills.forEach(f => obs.observe(f));
})();


/* ══════════════════════════════════════════════════════
   4. COUNTER ANIMATION (hero stats)
   ══════════════════════════════════════════════════════ */
(function initCounters() {
  const counters = document.querySelectorAll('.stat-num');
  const obs = new IntersectionObserver(entries => {
    entries.forEach(e => {
      if (!e.isIntersecting) return;
      const el     = e.target;
      const target = parseInt(el.dataset.target, 10);
      const dur    = 1600;
      const start  = performance.now();

      function tick(now) {
        const elapsed  = now - start;
        const progress = Math.min(elapsed / dur, 1);
        const eased    = 1 - Math.pow(1 - progress, 4);
        el.textContent = Math.round(eased * target);
        if (progress < 1) requestAnimationFrame(tick);
        else el.textContent = target;
      }
      requestAnimationFrame(tick);
      obs.unobserve(el);
    });
  }, { threshold: 0.5 });
  counters.forEach(c => obs.observe(c));
})();


/* ══════════════════════════════════════════════════════
   5. CIRCUIT CANVAS BACKGROUND (hero)
   ══════════════════════════════════════════════════════ */
(function initCircuit() {
  const canvas = document.getElementById('circuitCanvas');
  if (!canvas) return;
  const ctx = canvas.getContext('2d');

  let W, H, nodes = [], edges = [], pulses = [], raf;
  const NODE_COUNT  = 55;
  const EDGE_DIST   = 180;
  const PULSE_SPEED = 1.8;
  const ACCENT      = '#00e5ff';
  const ACCENT2     = '#7b61ff';
  const NODE_COLOR  = 'rgba(0,229,255,0.45)';
  const EDGE_COLOR  = 'rgba(0,229,255,0.08)';

  function resize() {
    W = canvas.width  = canvas.offsetWidth;
    H = canvas.height = canvas.offsetHeight;
    buildGraph();
  }

  function buildGraph() {
    nodes = []; edges = []; pulses = [];
    const cols = Math.ceil(Math.sqrt(NODE_COUNT * (W / H)));
    const rows = Math.ceil(NODE_COUNT / cols);
    for (let r = 0; r < rows; r++) {
      for (let c = 0; c < cols; c++) {
        if (nodes.length >= NODE_COUNT) break;
        nodes.push({
          x:  (c + .5 + (Math.random() - .5) * .8) * (W / cols),
          y:  (r + .5 + (Math.random() - .5) * .8) * (H / rows),
          r:  Math.random() * 2 + 1.5,
          vx: (Math.random() - .5) * .18,
          vy: (Math.random() - .5) * .18,
        });
      }
    }
    for (let i = 0; i < nodes.length; i++) {
      for (let j = i + 1; j < nodes.length; j++) {
        const dx = nodes[j].x - nodes[i].x;
        const dy = nodes[j].y - nodes[i].y;
        const d  = Math.hypot(dx, dy);
        if (d < EDGE_DIST) edges.push({ a: i, b: j, d });
      }
    }
    for (let k = 0; k < 8; k++) spawnPulse();
  }

  function spawnPulse() {
    if (!edges.length) return;
    const edge = edges[Math.floor(Math.random() * edges.length)];
    pulses.push({
      edge,
      t: 0,
      color: Math.random() > .5 ? ACCENT : ACCENT2,
      speed: PULSE_SPEED * (.7 + Math.random() * .8),
    });
  }

  function draw() {
    ctx.clearRect(0, 0, W, H);

    nodes.forEach(n => {
      n.x += n.vx; n.y += n.vy;
      if (n.x < 0 || n.x > W) n.vx *= -1;
      if (n.y < 0 || n.y > H) n.vy *= -1;
    });

    edges.forEach(e => {
      const a = nodes[e.a], b = nodes[e.b];
      const midX = (a.x + b.x) / 2;
      ctx.beginPath();
      ctx.moveTo(a.x, a.y);
      ctx.lineTo(midX, a.y);
      ctx.lineTo(midX, b.y);
      ctx.lineTo(b.x, b.y);
      ctx.strokeStyle = EDGE_COLOR;
      ctx.lineWidth = .8;
      ctx.stroke();
    });

    for (let i = pulses.length - 1; i >= 0; i--) {
      const p    = pulses[i];
      const e    = p.edge;
      const a    = nodes[e.a], b = nodes[e.b];
      const midX = (a.x + b.x) / 2;
      const total = Math.abs(midX - a.x) + Math.abs(b.y - a.y) + Math.abs(b.x - midX);
      const dist  = p.t * total;
      let px, py;
      const seg1 = Math.abs(midX - a.x);
      const seg2 = Math.abs(b.y - a.y);
      if (dist <= seg1) {
        px = a.x + (midX - a.x) * (dist / seg1);
        py = a.y;
      } else if (dist <= seg1 + seg2) {
        px = midX;
        py = a.y + (b.y - a.y) * ((dist - seg1) / seg2);
      } else {
        px = midX + (b.x - midX) * Math.min((dist - seg1 - seg2) / Math.abs(b.x - midX), 1);
        py = b.y;
      }
      ctx.beginPath();
      ctx.arc(px, py, 2.5, 0, Math.PI * 2);
      ctx.fillStyle = p.color;
      ctx.shadowColor = p.color;
      ctx.shadowBlur = 8;
      ctx.fill();
      ctx.shadowBlur = 0;
      p.t += p.speed / (total || 1) * .012;
      if (p.t >= 1) { pulses.splice(i, 1); spawnPulse(); }
    }

    while (pulses.length < 8) spawnPulse();

    nodes.forEach(n => {
      ctx.beginPath();
      ctx.arc(n.x, n.y, n.r, 0, Math.PI * 2);
      ctx.fillStyle = NODE_COLOR;
      ctx.fill();
    });

    raf = requestAnimationFrame(draw);
  }

  resize();
  window.addEventListener('resize', () => { cancelAnimationFrame(raf); resize(); draw(); });
  draw();
  document.addEventListener('visibilitychange', () => {
    if (document.hidden) cancelAnimationFrame(raf);
    else draw();
  });
})();


/* ══════════════════════════════════════════════════════
   6. CONTACT FORM – UX polish
   ══════════════════════════════════════════════════════ */
(function initForm() {
  const form = document.querySelector('.contact-form');
  if (!form) return;
  const btn = form.querySelector('button[type=submit]');
  form.addEventListener('submit', () => {
    btn.textContent = 'Sending…';
    btn.disabled = true;
  });
})();


/* ══════════════════════════════════════════════════════
   7. SMOOTH SCROLL
   ══════════════════════════════════════════════════════ */
document.querySelectorAll('a[href^="#"]').forEach(a => {
  a.addEventListener('click', e => {
    const id = a.getAttribute('href').slice(1);
    const target = document.getElementById(id);
    if (!target) return;
    e.preventDefault();
    const offset = parseInt(getComputedStyle(document.documentElement).getPropertyValue('--nav-h'), 10) || 70;
    window.scrollTo({ top: target.getBoundingClientRect().top + window.scrollY - offset, behavior: 'smooth' });
  });
});