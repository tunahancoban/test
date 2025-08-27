<?php
// --- Simple contact form handler (same-page POST) ---
$success = false; $error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contact_form'])) {
    $name = trim($_POST['name'] ?? '');
    $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
    $message = trim($_POST['message'] ?? '');

    if ($name && $email && $message) {
        // TODO: change this to your email address on cPanel
        $to = 'you@example.com';
        $subject = "Portfolio Contact • $name";
        $headers = "From: $name <{$email}>\r\n" .
                   "Reply-To: {$email}\r\n" .
                   "Content-Type: text/plain; charset=UTF-8\r\n";
        $body = "Name: $name\nEmail: $email\n\nMessage:\n$message\n\n--\nSent from portfolio site";

        if (@mail($to, $subject, $body, $headers)) {
            $success = true;
        } else {
            $error = 'Mesaj gönderilemedi. Sunucuda mail() devre dışı olabilir. cPanel > PHP mail ayarlarını kontrol edin veya formu mailto ile yönlendirin.';
        }
    } else {
        $error = 'Lütfen tüm alanları geçerli doldurun.';
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Portföy • Ad Soyad</title>
  <meta name="description" content="Kişisel portföy – projeler, iletişim ve sosyal bağlantılar" />
  <style>
    :root{
      --bg:#0b1020;        /* arka plan */
      --surface:#111831;   /* kart arka planı */
      --muted:#9aa3b2;     /* ikincil metin */
      --text:#EAF0FF;      /* birincil metin */
      --accent:#6CA3FF;    /* vurgu rengi */
      --accent-2:#22d3ee;  /* ek vurgu */
      --ok:#22c55e;        /* başarı */
      --danger:#ef4444;    /* hata */
      --radius:18px;
      --shadow:0 15px 35px rgba(0,0,0,.35), inset 0 1px 0 rgba(255,255,255,.03);
      --max:1100px;
    }
    *{box-sizing:border-box}
    html,body{margin:0;height:100%;scroll-behavior:smooth}
    body{font-family:system-ui,Segoe UI,Roboto,Ubuntu,Inter,Arial,sans-serif;background:radial-gradient(1200px 700px at 10% -10%,rgba(108,163,255,.15),transparent 60%),radial-gradient(800px 600px at 90% 0,rgba(34,211,238,.15),transparent 60%),var(--bg);color:var(--text)}
    a{color:inherit;text-decoration:none}
    .container{width:min(var(--max), 100% - 32px);margin-inline:auto}

    /* Header */
    header{position:sticky;top:0;z-index:50;background:linear-gradient(180deg, rgba(11,16,32,.9), rgba(11,16,32,.6));backdrop-filter: blur(10px);border-bottom:1px solid rgba(255,255,255,.06)}
    .nav{display:flex;align-items:center;justify-content:space-between;padding:14px 0}
    .brand{display:flex;gap:10px;align-items:center}
    .brand .logo{width:36px;height:36px;border-radius:10px;display:grid;place-items:center;background:linear-gradient(135deg,var(--accent),var(--accent-2));box-shadow:var(--shadow);font-weight:700}
    .brand span{font-weight:700;letter-spacing:.3px}
    .menu{display:flex;gap:12px;align-items:center}
    .menu a{padding:10px 14px;border-radius:10px;color:var(--muted)}
    .menu a:hover{background:rgba(255,255,255,.06);color:var(--text)}

    /* Social buttons */
    .social{display:flex;gap:10px}
    .btn{display:inline-flex;gap:8px;align-items:center;border:1px solid rgba(255,255,255,.1);padding:9px 12px;border-radius:12px;background:rgba(255,255,255,.03);box-shadow:var(--shadow);font-weight:600}
    .btn:hover{transform:translateY(-1px);background:rgba(255,255,255,.06)}
    .btn svg{width:18px;height:18px}

    /* Hero */
    .hero{padding:56px 0 34px}
    .hero-grid{display:grid;grid-template-columns:1.2fr .8fr;gap:28px}
    .card{background:linear-gradient(180deg, rgba(255,255,255,.04), rgba(255,255,255,.02));border:1px solid rgba(255,255,255,.08);box-shadow:var(--shadow);border-radius:var(--radius);padding:22px}
    h1{font-size:clamp(24px,3.4vw,40px);margin:0 0 10px}
    p.lead{color:var(--muted);font-size:clamp(14px,1.5vw,16px);line-height:1.65}

    /* Tags */
    .filters{display:flex;flex-wrap:wrap;gap:10px;margin:12px 0 0}
    .chip{border:1px solid rgba(255,255,255,.15);background:rgba(255,255,255,.04);padding:8px 12px;border-radius:999px;font-size:14px;cursor:pointer;user-select:none}
    .chip.active{background:linear-gradient(90deg,var(--accent),var(--accent-2));border-color:transparent;color:#031225}
    .chip.outline{background:transparent}

    /* Projects */
    section{scroll-margin-top:82px}
    .section-title{display:flex;align-items:center;justify-content:space-between;margin:8px 0 14px}
    .section-title h2{margin:0;font-size:24px}
    .projects{display:grid;grid-template-columns:repeat(12,1fr);gap:16px}
    .project{grid-column:span 4;background:linear-gradient(180deg, rgba(255,255,255,.03), rgba(255,255,255,.01));border:1px solid rgba(255,255,255,.08);border-radius:16px;overflow:hidden;display:flex;flex-direction:column;transition:.2s}
    .project:hover{transform:translateY(-4px)}
    .thumb{aspect-ratio:16/9;background:radial-gradient(600px 180px at -10% -30%, rgba(108,163,255,.25), transparent),radial-gradient(300px 120px at 110% 0, rgba(34,211,238,.25), transparent);display:grid;place-items:center;font-weight:800;letter-spacing:.6px}
    .project .body{padding:14px 14px 10px;display:flex;flex-direction:column;gap:8px}
    .project h3{margin:0;font-size:18px}
    .project p{margin:0;color:var(--muted);font-size:14px;line-height:1.5}
    .tagrow{display:flex;flex-wrap:wrap;gap:6px;margin-top:6px}
    .tag{font-size:12px;border:1px solid rgba(255,255,255,.15);padding:4px 8px;border-radius:999px;color:var(--muted)}
    .project .actions{display:flex;gap:10px;margin:10px 0 4px}
    .ghost{border:1px solid rgba(255,255,255,.15);padding:8px 10px;border-radius:10px;font-size:14px}

    /* Contact */
    .form{display:grid;gap:10px}
    .row{display:grid;grid-template-columns:1fr 1fr;gap:10px}
    .input, textarea{width:100%;background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.12);border-radius:12px;padding:12px 14px;color:var(--text);font:inherit}
    textarea{min-height:130px;resize:vertical}
    .submit{justify-self:start;background:linear-gradient(90deg,var(--accent),var(--accent-2));border:none;color:#031225;padding:12px 16px;border-radius:12px;font-weight:700;cursor:pointer}
    .msg{margin-top:10px;font-size:14px}
    .ok{color:var(--ok)} .fail{color:var(--danger)}

    /* Footer */
    footer{margin-top:40px;padding:22px 0;color:var(--muted);border-top:1px solid rgba(255,255,255,.08)}

    /* Responsive */
    @media (max-width: 920px){
      .hero-grid{grid-template-columns:1fr}
      .project{grid-column:span 6}
    }
    @media (max-width: 640px){
      .projects{grid-template-columns:repeat(6,1fr)}
      .project{grid-column:span 6}
      .row{grid-template-columns:1fr}
      .menu{display:none}
    }
  </style>
</head>
<body>
  <header>
    <div class="container nav">
      <div class="brand">
        <div class="logo">ME</div>
        <span>Ad Soyad</span>
      </div>
      <nav class="menu" aria-label="Ana menü">
        <a href="#about">Hakkımda</a>
        <a href="#projects">Projeler</a>
        <a href="#contact">İletişim</a>
      </nav>
      <div class="social">
        <a class="btn" href="https://www.linkedin.com/in/USERNAME" target="_blank" rel="noopener">
          <!-- LinkedIn icon -->
          <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M4.98 3.5C4.98 4.88 3.86 6 2.5 6S0 4.88 0 3.5 1.12 1 2.5 1s2.48 1.12 2.48 2.5zM.5 8.5h4V23h-4zM8.5 8.5h3.8v2h.06c.53-1 1.83-2.06 3.77-2.06C20.5 8.44 23 10.5 23 14.44V23h-4v-7.2c0-1.72-.03-3.94-2.4-3.94-2.4 0-2.77 1.87-2.77 3.81V23h-4z"/></svg>
          LinkedIn
        </a>
        <a class="btn" href="https://USERNAME.itch.io/" target="_blank" rel="noopener">
          <!-- itch.io icon -->
          <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M3 3h18l3 5-3 13H3L0 8l3-5zm2.6 4L3.9 8.7l1.6 9.6h13l1.6-9.6L18.4 7H5.6zm2 .8h8.8l1.3 1.3-.9 5.5c-2.1-.3-3.7 1.3-3.7 3.3H10.9c0-2-1.6-3.6-3.7-3.3L6.3 9.1 7.6 7.8z"/></svg>
          itch.io
        </a>
      </div>
    </div>
  </header>

  <main class="container">
    <!-- Hero / About -->
    <section id="about" class="hero">
      <div class="hero-grid">
        <div class="card">
          <h1>Merhaba, ben <span style="color:var(--accent)">Ad Soyad</span> 👋</h1>
          <p class="lead">Bilgisayar Mühendisliği öğrencisi / Geliştirici. Yapay zekâ, oyun geliştirme ve gömülü sistemler ile ilgileniyorum. Bu sayfada seçtiğim projeleri, etiketlere göre filtreleyerek inceleyebilir ve benimle iletişime geçebilirsiniz.</p>
          <div class="filters" aria-label="Öne çıkan alanlar">
            <div class="chip active" data-preset="ai">AI / ML</div>
            <div class="chip active" data-preset="game">Game Dev</div>
            <div class="chip active" data-preset="web">Web</div>
          </div>
        </div>
        <div class="card">
          <h2 style="margin:0 0 6px">Hızlı Bilgiler</h2>
          <ul style="margin:0;padding-left:18px;color:var(--muted);line-height:1.75">
            <li>🎓 3. sınıf Bilgisayar Mühendisliği öğrencisi</li>
            <li>🧠 İlgi alanları: AI/ML, Unity 2D, Backend (PHP, Node)</li>
            <li>🛠️ Araçlar: PHP, JavaScript, Python, Unity, MySQL</li>
            <li>📍 Ankara / Türkiye</li>
          </ul>
        </div>
      </div>
    </section>

    <!-- Projects -->
    <section id="projects">
      <div class="section-title">
        <h2>Projeler</h2>
        <div class="filters" role="group" aria-label="Projeleri etiketlere göre filtrele">
          <div class="chip outline" data-filter="all">Hepsi</div>
          <div class="chip" data-filter="ai">AI/ML</div>
          <div class="chip" data-filter="game">Game</div>
          <div class="chip" data-filter="web">Web</div>
          <div class="chip" data-filter="embedded">Embedded</div>
          <div class="chip" data-filter="tool">Tooling</div>
          <div class="chip" data-filter="school">School</div>
          <div class="chip" data-clear>Temizle</div>
        </div>
      </div>

      <div class="projects" id="projectGrid">
        <!-- Project Card Template examples -->
        <article class="project" data-tags="ai,web,tool">
          <div class="thumb">AI Notes Summarizer</div>
          <div class="body">
            <h3>AI Notes Summarizer</h3>
            <p>Lecture PDF’lerini OCR ile metne çevirip bölüm bölüm özetleyen bir web aracı (PHP + Python backend).</p>
            <div class="tagrow">
              <span class="tag">AI</span><span class="tag">Web</span><span class="tag">Tool</span>
            </div>
            <div class="actions">
              <a class="ghost" href="#" target="_blank" rel="noopener">Github</a>
              <a class="ghost" href="#" target="_blank" rel="noopener">Demo</a>
            </div>
          </div>
        </article>

        <article class="project" data-tags="game,web">
          <div class="thumb">Cat Dungeon (Unity)</div>
          <div class="body">
            <h3>Cat Dungeon</h3>
            <p>2D pixel‑art, hikâye odaklı dungeon oyunu. Itch.io sayfasından indirilebilir build.</p>
            <div class="tagrow">
              <span class="tag">Game</span><span class="tag">Unity</span>
            </div>
            <div class="actions">
              <a class="ghost" href="https://USERNAME.itch.io/" target="_blank" rel="noopener">itch.io</a>
            </div>
          </div>
        </article>

        <article class="project" data-tags="embedded,tool,school">
          <div class="thumb">STM32 Joystick</div>
          <div class="body">
            <h3>STM32 Joystick</h3>
            <p>STM32 ile gürültü filtreleme ve USB HID joystick emülasyonu; oyun kontrolcüsü prototipi.</p>
            <div class="tagrow">
              <span class="tag">Embedded</span><span class="tag">Tool</span><span class="tag">School</span>
            </div>
            <div class="actions">
              <a class="ghost" href="#" target="_blank" rel="noopener">Rapor</a>
            </div>
          </div>
        </article>

        <article class="project" data-tags="web,school">
          <div class="thumb">Bank App (React)</div>
          <div class="body">
            <h3>Premium Bank</h3>
            <p>React ile modern bankacılık arayüzü; component tabanlı mimari ve responsive tasarım.</p>
            <div class="tagrow"><span class="tag">Web</span><span class="tag">School</span></div>
            <div class="actions">
              <a class="ghost" href="#" target="_blank" rel="noopener">Github</a>
              <a class="ghost" href="#" target="_blank" rel="noopener">Canlı</a>
            </div>
          </div>
        </article>

        <article class="project" data-tags="ai,school">
          <div class="thumb">GDA Classifier</div>
          <div class="body">
            <h3>Gaussian Discriminant Analysis</h3>
            <p>Matematiksel türetimler ve Python uygulaması ile GDA sınıflandırıcı.</p>
            <div class="tagrow"><span class="tag">AI</span><span class="tag">School</span></div>
            <div class="actions">
              <a class="ghost" href="#" target="_blank" rel="noopener">Notebook</a>
            </div>
          </div>
        </article>

        <article class="project" data-tags="web,tool">
          <div class="thumb">Blog Component</div>
          <div class="body">
            <h3>SCSS Blog Grid</h3>
            <p>SCSS ile kart grid, hover gölgeleri ve layout yardımcıları. Modern UI denemesi.</p>
            <div class="tagrow"><span class="tag">Web</span><span class="tag">Tool</span></div>
            <div class="actions">
              <a class="ghost" href="#" target="_blank" rel="noopener">Kod</a>
            </div>
          </div>
        </article>
      </div>
    </section>

    <!-- Contact -->
    <section id="contact" style="margin-top:28px">
      <div class="section-title">
        <h2>İletişim</h2>
        <div class="filters">
          <a class="btn" href="mailto:you@example.com">E‑posta</a>
          <a class="btn" href="https://www.linkedin.com/in/USERNAME" target="_blank" rel="noopener">LinkedIn</a>
          <a class="btn" href="https://USERNAME.itch.io/" target="_blank" rel="noopener">itch.io</a>
        </div>
      </div>

      <div class="card">
        <?php if ($success): ?>
          <div class="msg ok">Teşekkürler! Mesajınız alındı. En kısa sürede dönüş yapacağım.</div>
        <?php else: ?>
          <?php if ($error): ?><div class="msg fail"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div><?php endif; ?>
          <form class="form" method="post" action="#contact">
            <input type="hidden" name="contact_form" value="1" />
            <div class="row">
              <input class="input" type="text" name="name" placeholder="Adınız" required />
              <input class="input" type="email" name="email" placeholder="E‑posta" required />
            </div>
            <textarea name="message" placeholder="Mesajınız" required></textarea>
            <button class="submit" type="submit">Gönder</button>
            <div class="msg" style="color:var(--muted)">Not: Sunucuda <code>mail()</code> devre dışı ise üstteki E‑posta butonunu kullanabilirsiniz.</div>
          </form>
        <?php endif; ?>
      </div>
    </section>

    <footer class="container">
      © <script>document.write(new Date().getFullYear())</script> • Ad Soyad
    </footer>
  </main>

  <script>
    // Tag filtering system (multi-select)
    (function(){
      const grid = document.getElementById('projectGrid');
      const cards = Array.from(grid.querySelectorAll('.project'));
      const filterChips = Array.from(document.querySelectorAll('[data-filter]'));
      const clearChip = document.querySelector('[data-clear]');
      const selected = new Set();

      function apply(){
        if (selected.size === 0 || selected.has('all')) {
          cards.forEach(c => c.style.display = 'flex');
          return;
        }
        cards.forEach(card => {
          const tags = (card.getAttribute('data-tags')||'').split(',').map(t=>t.trim());
          const ok = Array.from(selected).every(tag => tags.includes(tag));
          card.style.display = ok ? 'flex' : 'none';
        });
      }

      filterChips.forEach(chip => {
        chip.addEventListener('click', () => {
          const tag = chip.getAttribute('data-filter');
          if (tag === 'all') {
            selected.clear();
            selected.add('all');
            filterChips.forEach(c=>c.classList.remove('active'));
            chip.classList.add('active');
            apply();
            return;
          }
          // normal toggle
          if (selected.has('all')) selected.delete('all');
          chip.classList.toggle('active');
          if (chip.classList.contains('active')) selected.add(tag); else selected.delete(tag);
          apply();
        });
      });

      clearChip?.addEventListener('click', () => {
        selected.clear();
        filterChips.forEach(c=>c.classList.remove('active'));
        apply();
      });

      // Optional: preload presets in hero (cosmetic)
      document.querySelectorAll('[data-preset]')?.forEach(el=>{
        el.addEventListener('click', ()=>{
          const t = el.getAttribute('data-preset');
          // toggle a single tag quickly
          const btn = document.querySelector(`.filters [data-filter="${t}"]`);
          if (btn){ btn.click(); }
        });
      });
    })();
  </script>
</body>
</html>
