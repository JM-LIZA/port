<?php
require_once 'config.php';

// ── Fetch all data ─────────────────────────────────────────────
$db = db();

$profile = $db->query("SELECT * FROM profile LIMIT 1")->fetch();

$skills_raw = $db->query("SELECT * FROM skills ORDER BY sort_order")->fetchAll();
$skills = [];
foreach ($skills_raw as $s) {
    $skills[$s['category']][] = $s;
}

$projects  = $db->query("SELECT * FROM projects  ORDER BY sort_order")->fetchAll();
$featured  = array_filter($projects, fn($p) => $p['featured']);
$other     = array_filter($projects, fn($p) => !$p['featured']);

$experience = $db->query("SELECT * FROM experience ORDER BY sort_order")->fetchAll();
$education  = $db->query("SELECT * FROM education  ORDER BY sort_order")->fetchAll();
$certs      = $db->query("SELECT * FROM certifications ORDER BY year DESC")->fetchAll();

// ── Contact form handler ───────────────────────────────────────
$form_msg = '';
$form_type = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_message'])) {
    $name    = trim(htmlspecialchars($_POST['name']    ?? ''));
    $email   = trim(htmlspecialchars($_POST['email']   ?? ''));
    $subject = trim(htmlspecialchars($_POST['subject'] ?? ''));
    $message = trim(htmlspecialchars($_POST['message'] ?? ''));
    $ip      = $_SERVER['REMOTE_ADDR'] ?? '';

    if ($name && $email && $message && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $stmt = $db->prepare(
            "INSERT INTO contact_messages (name,email,subject,message,ip_address)
             VALUES (:n,:e,:s,:m,:ip)"
        );
        $stmt->execute([':n'=>$name,':e'=>$email,':s'=>$subject,':m'=>$message,':ip'=>$ip]);
        $form_msg  = "Message sent! I'll get back to you soon.";
        $form_type = 'success';
    } else {
        $form_msg  = 'Please fill in all required fields with a valid email.';
        $form_type = 'error';
    }
}

// ── Helpers ────────────────────────────────────────────────────
function tags(string $str): array {
    return array_filter(array_map('trim', explode(',', $str)));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= SITE_TITLE ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&family=Syne:wght@700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="style.css">
</head>
<body>

<!-- ══════════════════════ NAV ══════════════════════ -->
<nav id="navbar">
  <div class="nav-inner">
    <a href="#hero" class="logo">
      <span class="logo-bracket">[</span>AE<span class="logo-bracket">]</span>
    </a>
    <button class="nav-toggle" id="navToggle" aria-label="Toggle menu">
      <span></span><span></span><span></span>
    </button>
    <ul class="nav-links" id="navLinks">
      <li><a href="#about">About</a></li>
      <li><a href="#skills">Skills</a></li>
      <li><a href="#projects">Projects</a></li>
      <li><a href="#experience">Experience</a></li>
      <li><a href="#contact">Contact</a></li>
    </ul>
  </div>
</nav>

<!-- ══════════════════════ HERO ══════════════════════ -->
<section id="hero">
  <div class="hero-bg">
    <canvas id="circuitCanvas"></canvas>
  </div>
  <div class="hero-content reveal">
    <p class="hero-eyebrow">⚡ Electrical Engineer</p>
    <h1 class="hero-name"><?= htmlspecialchars($profile['name'] ?? 'Alex Reyes') ?></h1>
    <p class="hero-tagline"><?= htmlspecialchars($profile['tagline'] ?? 'Designing circuits that power tomorrow.') ?></p>
    <div class="hero-cta">
      <a href="#projects" class="btn btn-primary">View Projects</a>
      <a href="#contact"  class="btn btn-outline">Get In Touch</a>
    </div>
    <div class="hero-stats">
      <div class="stat"><span class="stat-num" data-target="5">0</span><span class="stat-plus">+</span><small>Years Exp.</small></div>
      <div class="stat"><span class="stat-num" data-target="<?= count($projects) ?>">0</span><span class="stat-plus">+</span><small>Projects</small></div>
      <div class="stat"><span class="stat-num" data-target="<?= count($certs) ?>">0</span><small>Certs</small></div>
    </div>
  </div>
  <a href="#about" class="scroll-down" aria-label="Scroll down">
    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
  </a>
</section>

<!-- ══════════════════════ ABOUT ══════════════════════ -->
<section id="about" class="section">
  <div class="container">
    <div class="section-header reveal">
      <span class="section-tag">01 / About</span>
      <h2>Who I Am</h2>
    </div>
    <div class="about-grid">
      <div class="about-text reveal">
        <p class="lead"><?= nl2br(htmlspecialchars($profile['about'] ?? '')) ?></p>
        <div class="about-info">
          <?php if($profile['location']): ?>
          <div class="info-row">
            <svg class="info-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
            <?= htmlspecialchars($profile['location']) ?>
          </div>
          <?php endif; ?>
          <?php if($profile['email']): ?>
          <div class="info-row">
            <svg class="info-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
            <a href="mailto:<?= htmlspecialchars($profile['email']) ?>"><?= htmlspecialchars($profile['email']) ?></a>
          </div>
          <?php endif; ?>
        </div>
        <div class="about-links">
          <?php if($profile['github']): ?>
          <a href="<?= htmlspecialchars($profile['github']) ?>" target="_blank" class="btn btn-outline btn-sm">
            <svg viewBox="0 0 24 24" fill="currentColor" width="18" height="18"><path d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12"/></svg>
            GitHub
          </a>
          <?php endif; ?>
          <?php if($profile['linkedin']): ?>
          <a href="<?= htmlspecialchars($profile['linkedin']) ?>" target="_blank" class="btn btn-outline btn-sm">
            <svg viewBox="0 0 24 24" fill="currentColor" width="18" height="18"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
            LinkedIn
          </a>
          <?php endif; ?>
        </div>
      </div>
      <div class="about-certs reveal">
        <h3>Certifications</h3>
        <?php foreach($certs as $c): ?>
        <div class="cert-card">
          <div class="cert-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20"><circle cx="12" cy="8" r="6"/><path d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11"/></svg>
          </div>
          <div>
            <p class="cert-name"><?= htmlspecialchars($c['name']) ?></p>
            <small><?= htmlspecialchars($c['issuer']) ?> · <?= htmlspecialchars($c['year']) ?></small>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<!-- ══════════════════════ SKILLS ══════════════════════ -->
<section id="skills" class="section section-alt">
  <div class="container">
    <div class="section-header reveal">
      <span class="section-tag">02 / Skills</span>
      <h2>Technical Stack</h2>
    </div>
    <div class="skills-grid">
      <?php foreach($skills as $cat => $items): ?>
      <div class="skill-category reveal">
        <h3 class="skill-cat-title"><?= htmlspecialchars($cat) ?></h3>
        <?php foreach($items as $skill): ?>
        <div class="skill-item">
          <div class="skill-meta">
            <span><?= htmlspecialchars($skill['name']) ?></span>
            <span class="skill-pct"><?= $skill['level'] ?>%</span>
          </div>
          <div class="skill-bar">
            <div class="skill-fill" data-level="<?= $skill['level'] ?>" style="width:0%"></div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ══════════════════════ PROJECTS ══════════════════════ -->
<section id="projects" class="section">
  <div class="container">
    <div class="section-header reveal">
      <span class="section-tag">03 / Projects</span>
      <h2>Featured Work</h2>
    </div>

    <!-- Featured -->
    <div class="projects-featured">
      <?php foreach($featured as $i => $p): ?>
      <article class="project-card-lg reveal <?= $i % 2 === 1 ? 'reverse' : '' ?>">
        <div class="project-img-wrap">
          <div class="project-img-placeholder">
            <svg viewBox="0 0 80 60" fill="none" xmlns="http://www.w3.org/2000/svg">
              <rect width="80" height="60" rx="4" fill="currentColor" opacity=".06"/>
              <path d="M20 30h4l4-10 8 20 6-14 4 8h14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" opacity=".5"/>
              <circle cx="58" cy="20" r="4" fill="currentColor" opacity=".3"/>
              <path d="M10 42h60M10 48h40" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" opacity=".2"/>
            </svg>
            <span class="proj-cat-badge"><?= htmlspecialchars($p['category']) ?></span>
          </div>
        </div>
        <div class="project-info">
          <h3><?= htmlspecialchars($p['title']) ?></h3>
          <p><?= htmlspecialchars($p['description']) ?></p>
          <div class="tag-row">
            <?php foreach(tags($p['tags']) as $t): ?>
            <span class="tag"><?= htmlspecialchars($t) ?></span>
            <?php endforeach; ?>
          </div>
          <div class="project-links">
            <?php if($p['github_url'] && $p['github_url'] !== '#'): ?>
            <a href="<?= htmlspecialchars($p['github_url']) ?>" target="_blank" class="btn btn-outline btn-sm">
              <svg viewBox="0 0 24 24" fill="currentColor" width="16" height="16"><path d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12"/></svg>
              Source Code
            </a>
            <?php endif; ?>
          </div>
        </div>
      </article>
      <?php endforeach; ?>
    </div>

    <!-- Other projects -->
    <?php if(!empty($other)): ?>
    <h3 class="other-proj-title reveal">Other Projects</h3>
    <div class="projects-grid">
      <?php foreach($other as $p): ?>
      <article class="project-card reveal">
        <div class="card-top">
          <span class="proj-cat-badge"><?= htmlspecialchars($p['category']) ?></span>
          <?php if($p['github_url'] && $p['github_url'] !== '#'): ?>
          <a href="<?= htmlspecialchars($p['github_url']) ?>" target="_blank" class="card-link-icon" aria-label="GitHub">
            <svg viewBox="0 0 24 24" fill="currentColor" width="20" height="20"><path d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12"/></svg>
          </a>
          <?php endif; ?>
        </div>
        <h4><?= htmlspecialchars($p['title']) ?></h4>
        <p><?= htmlspecialchars(mb_substr($p['description'], 0, 140)) ?>…</p>
        <div class="tag-row">
          <?php foreach(array_slice(tags($p['tags']), 0, 4) as $t): ?>
          <span class="tag"><?= htmlspecialchars($t) ?></span>
          <?php endforeach; ?>
        </div>
      </article>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>
</section>

<!-- ══════════════════════ EXPERIENCE ══════════════════════ -->
<section id="experience" class="section section-alt">
  <div class="container">
    <div class="section-header reveal">
      <span class="section-tag">04 / Experience</span>
      <h2>Career Timeline</h2>
    </div>
    <div class="timeline-wrap">
      <div class="timeline-line"></div>
      <?php foreach($experience as $exp): ?>
      <div class="timeline-item reveal">
        <div class="timeline-dot <?= $exp['is_current'] ? 'active' : '' ?>"></div>
        <div class="timeline-card">
          <?php if($exp['is_current']): ?>
          <span class="current-badge">Current</span>
          <?php endif; ?>
          <p class="tl-period"><?= htmlspecialchars($exp['period']) ?></p>
          <h3><?= htmlspecialchars($exp['role']) ?></h3>
          <p class="tl-company"><?= htmlspecialchars($exp['company']) ?></p>
          <p class="tl-desc"><?= htmlspecialchars($exp['description']) ?></p>
        </div>
      </div>
      <?php endforeach; ?>
      <?php foreach($education as $edu): ?>
      <div class="timeline-item timeline-edu reveal">
        <div class="timeline-dot edu-dot"></div>
        <div class="timeline-card edu-card">
          <p class="tl-period"><?= htmlspecialchars($edu['period']) ?></p>
          <h3><?= htmlspecialchars($edu['degree']) ?> — <?= htmlspecialchars($edu['field']) ?></h3>
          <p class="tl-company"><?= htmlspecialchars($edu['institution']) ?><?= $edu['gpa'] ? ' · GPA ' . htmlspecialchars($edu['gpa']) : '' ?></p>
          <p class="tl-desc"><?= htmlspecialchars($edu['description']) ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ══════════════════════ CONTACT ══════════════════════ -->
<section id="contact" class="section">
  <div class="container">
    <div class="section-header reveal">
      <span class="section-tag">05 / Contact</span>
      <h2>Let's Connect</h2>
    </div>
    <div class="contact-grid">
      <div class="contact-left reveal">
        <p class="contact-intro">Have a project, collaboration, or just want to talk shop? Drop me a message — I'll usually respond within 24 hours.</p>
        <div class="contact-details">
          <?php if($profile['email']): ?>
          <a href="mailto:<?= htmlspecialchars($profile['email']) ?>" class="contact-detail-item">
            <div class="contact-detail-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
            </div>
            <span><?= htmlspecialchars($profile['email']) ?></span>
          </a>
          <?php endif; ?>
          <?php if($profile['phone']): ?>
          <div class="contact-detail-item">
            <div class="contact-detail-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.46 13a19.79 19.79 0 01-3.07-8.67A2 2 0 012.38 2h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L6.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/></svg>
            </div>
            <span><?= htmlspecialchars($profile['phone']) ?></span>
          </div>
          <?php endif; ?>
          <?php if($profile['location']): ?>
          <div class="contact-detail-item">
            <div class="contact-detail-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
            </div>
            <span><?= htmlspecialchars($profile['location']) ?></span>
          </div>
          <?php endif; ?>
        </div>
      </div>
      <div class="contact-right reveal">
        <?php if($form_msg): ?>
        <div class="form-alert form-<?= $form_type ?>">
          <?= htmlspecialchars($form_msg) ?>
        </div>
        <?php endif; ?>
        <form method="POST" action="#contact" class="contact-form" novalidate>
          <div class="form-row">
            <div class="form-group">
              <label for="name">Name <span class="req">*</span></label>
              <input type="text" id="name" name="name" placeholder="Your name" required>
            </div>
            <div class="form-group">
              <label for="email">Email <span class="req">*</span></label>
              <input type="email" id="email" name="email" placeholder="your@email.com" required>
            </div>
          </div>
          <div class="form-group">
            <label for="subject">Subject</label>
            <input type="text" id="subject" name="subject" placeholder="Project inquiry, collaboration…">
          </div>
          <div class="form-group">
            <label for="message">Message <span class="req">*</span></label>
            <textarea id="message" name="message" rows="5" placeholder="Tell me about your project…" required></textarea>
          </div>
          <button type="submit" name="send_message" class="btn btn-primary btn-full">
            Send Message
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
          </button>
        </form>
      </div>
    </div>
  </div>
</section>

<!-- ══════════════════════ FOOTER ══════════════════════ -->
<footer>
  <div class="container footer-inner">
    <p class="footer-logo"><span class="logo-bracket">[</span><?= htmlspecialchars($profile['name'] ?? 'Alex Reyes') ?><span class="logo-bracket">]</span></p>
    <p class="footer-copy">© <?= date('Y') ?> — Built with PHP & ⚡</p>
    <div class="footer-links">
      <?php if($profile['github']): ?>
      <a href="<?= htmlspecialchars($profile['github']) ?>" target="_blank" aria-label="GitHub">
        <svg viewBox="0 0 24 24" fill="currentColor" width="20" height="20"><path d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12"/></svg>
      </a>
      <?php endif; ?>
      <?php if($profile['linkedin']): ?>
      <a href="<?= htmlspecialchars($profile['linkedin']) ?>" target="_blank" aria-label="LinkedIn">
        <svg viewBox="0 0 24 24" fill="currentColor" width="20" height="20"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
      </a>
      <?php endif; ?>
    </div>
  </div>
</footer>

<script src="main.js"></script>
</body>
</html>