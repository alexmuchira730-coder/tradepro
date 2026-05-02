<?php
/**
 * index.php — Public Landing Page
 */
session_start();
$loggedIn = isset($_SESSION['user_id']);
$username = $loggedIn ? htmlspecialchars($_SESSION['username']) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>TradePro | Trade Forex, Crypto & Stocks</title>
  <link rel="stylesheet" href="style.css" />
  <script src="https://s3.tradingview.com/tv.js"></script>
</head>
<body class="landing-page">

<!-- ============================
     STICKY NAVBAR
     ============================ -->
<nav class="lp-nav" id="lp-nav">
  <a class="lp-logo" href="index.php">TRADE<span>PRO</span></a>

  <ul class="lp-nav-links" id="lp-nav-links">
    <li><a href="#home">Home</a></li>
    <li><a href="#markets">Markets</a></li>
    <li><a href="#about">About</a></li>
    <li><a href="#testimonials">Testimonials</a></li>
    <li><a href="#contact">Contact</a></li>
  </ul>

  <div class="lp-nav-actions">
    <?php if ($loggedIn): ?>
      <a href="dashboard.php" class="btn-lp-primary">Dashboard</a>
      <a href="logout.php"    class="btn-lp-ghost">Logout</a>
    <?php else: ?>
      <a href="login.php"    class="btn-lp-ghost">Login</a>
      <a href="register.php" class="btn-lp-primary">Register</a>
    <?php endif; ?>
    <button class="hamburger" id="hamburger" aria-label="Menu">
      <span></span><span></span><span></span>
    </button>
  </div>
</nav>

<!-- ============================
     HERO SECTION
     ============================ -->
<section class="hero-section" id="home">
  <div class="hero-bg-grid"></div>
  <div class="hero-content">
    <div class="hero-badge">REGULATED TRADING PLATFORM</div>
    <h1 class="hero-headline">
      Trade Forex,<br/>
      <span>Crypto</span> &amp; Stocks
    </h1>
    <p class="hero-desc">
      Access 500+ instruments with lightning-fast execution,
      tight spreads, and institutional-grade security.
    </p>
    <div class="hero-ctas">
      <a href="<?= $loggedIn ? 'dashboard.php' : 'register.php' ?>" class="btn-lp-primary btn-large">
        Start Trading
      </a>
      <a href="register.php" class="btn-lp-ghost btn-large">Create Free Account</a>
    </div>
    <div class="hero-stats">
      <div class="hs-item"><strong>500+</strong><span>Instruments</span></div>
      <div class="hs-sep"></div>
      <div class="hs-item"><strong>0.1ms</strong><span>Execution</span></div>
      <div class="hs-sep"></div>
      <div class="hs-item"><strong>120k+</strong><span>Traders</span></div>
      <div class="hs-sep"></div>
      <div class="hs-item"><strong>24/7</strong><span>Support</span></div>
    </div>
  </div>
  <div class="hero-chart-side">
    <div id="hero-chart"></div>
  </div>
</section>

<!-- ============================
     LIVE MARKET MARQUEE
     ============================ -->
<section class="marquee-section" id="markets">
  <div class="marquee-track" id="marquee-track">
    <!-- Populated by JavaScript -->
  </div>
</section>

<!-- ============================
     FEATURES SECTION
     ============================ -->
<section class="features-section" id="about">
  <div class="section-header">
    <div class="section-tag">WHY CHOOSE US</div>
    <h2>Built for Serious Traders</h2>
    <p>Everything you need to trade smarter, faster, and safer.</p>
  </div>
  <div class="features-grid">
    <div class="feat-card">
      <div class="feat-icon icon-bolt">&#9889;</div>
      <h3>Fast Execution</h3>
      <p>Orders executed in under 0.1ms with zero requotes. Institutional-grade infrastructure.</p>
    </div>
    <div class="feat-card">
      <div class="feat-icon icon-shield">&#9632;</div>
      <h3>Secure Platform</h3>
      <p>Bank-level SSL encryption, 2FA authentication, and segregated client funds.</p>
    </div>
    <div class="feat-card">
      <div class="feat-icon icon-fee">%</div>
      <h3>Ultra-Low Fees</h3>
      <p>Spreads from 0.0 pips on major pairs. Zero hidden commissions, transparent pricing.</p>
    </div>
    <div class="feat-card">
      <div class="feat-icon icon-support">&#9678;</div>
      <h3>24/7 Support</h3>
      <p>Dedicated support team available around the clock via live chat, email, and phone.</p>
    </div>
    <div class="feat-card">
      <div class="feat-icon icon-chart">&#9650;</div>
      <h3>Advanced Charts</h3>
      <p>Powered by TradingView with 100+ technical indicators and drawing tools.</p>
    </div>
    <div class="feat-card">
      <div class="feat-icon icon-mobile">&#9635;</div>
      <h3>Mobile Trading</h3>
      <p>Full-featured iOS and Android apps so you never miss a trade opportunity.</p>
    </div>
  </div>
</section>

<!-- ============================
     HOW TO START
     ============================ -->
<section class="steps-section">
  <div class="section-header">
    <div class="section-tag">GET STARTED</div>
    <h2>Begin Trading in 3 Steps</h2>
    <p>Opening an account takes less than 2 minutes.</p>
  </div>
  <div class="steps-grid">
    <div class="step-card">
      <div class="step-num">01</div>
      <h3>Register Account</h3>
      <p>Fill out a quick registration form. Verify your email and you are ready to go.</p>
      <a href="register.php" class="step-link">Register Now</a>
    </div>
    <div class="step-connector"></div>
    <div class="step-card">
      <div class="step-num">02</div>
      <h3>Deposit Funds</h3>
      <p>Fund your account via bank transfer, card, or crypto. Minimum deposit just $10.</p>
    </div>
    <div class="step-connector"></div>
    <div class="step-card">
      <div class="step-num">03</div>
      <h3>Start Trading</h3>
      <p>Access 500+ instruments, open positions, and manage your portfolio in real time.</p>
    </div>
  </div>
</section>

<!-- ============================
     TESTIMONIALS
     ============================ -->
<section class="testimonials-section" id="testimonials">
  <div class="section-header">
    <div class="section-tag">TESTIMONIALS</div>
    <h2>What Our Traders Say</h2>
  </div>
  <div class="testi-slider" id="testi-slider">
    <div class="testi-track" id="testi-track">

      <div class="testi-card">
        <div class="testi-stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
        <p>"TradePro changed how I trade. The execution speed is unmatched and the spreads are incredibly tight. Best platform I have used in 5 years."</p>
        <div class="testi-user">
          <div class="testi-avatar">JM</div>
          <div>
            <strong>James Mitchell</strong>
            <span>Forex Trader, London</span>
          </div>
        </div>
      </div>

      <div class="testi-card">
        <div class="testi-stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
        <p>"The TradingView charts are beautiful and the mobile app works flawlessly. I can manage my crypto portfolio from anywhere."</p>
        <div class="testi-user">
          <div class="testi-avatar">SA</div>
          <div>
            <strong>Sofia Andersen</strong>
            <span>Crypto Investor, Stockholm</span>
          </div>
        </div>
      </div>

      <div class="testi-card">
        <div class="testi-stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
        <p>"I love the transparent fee structure. No hidden costs, no surprises. The 24/7 support team resolved my issue in under 5 minutes."</p>
        <div class="testi-user">
          <div class="testi-avatar">RK</div>
          <div>
            <strong>Ravi Kumar</strong>
            <span>Stocks Trader, Mumbai</span>
          </div>
        </div>
      </div>

      <div class="testi-card">
        <div class="testi-stars">&#9733;&#9733;&#9733;&#9733;&#9734;</div>
        <p>"As a beginner, the platform was easy to learn. The step-by-step guides and demo account helped me gain confidence before going live."</p>
        <div class="testi-user">
          <div class="testi-avatar">LP</div>
          <div>
            <strong>Laura Perez</strong>
            <span>New Trader, Madrid</span>
          </div>
        </div>
      </div>

    </div>
  </div>
  <div class="testi-dots" id="testi-dots"></div>
</section>

<!-- ============================
     CALL TO ACTION
     ============================ -->
<section class="cta-section">
  <div class="cta-inner">
    <h2>Ready to Start Trading?</h2>
    <p>Join over 120,000 traders worldwide. Open your free account today.</p>
    <a href="register.php" class="btn-lp-primary btn-large">Open Free Account</a>
  </div>
</section>

<!-- ============================
     FOOTER
     ============================ -->
<footer class="lp-footer" id="contact">
  <div class="footer-grid">
    <div class="footer-brand">
      <div class="lp-logo">TRADE<span>PRO</span></div>
      <p>A professional trading platform built for modern traders. Fast, secure, and transparent.</p>
      <div class="social-icons">
        <a href="#" class="sicon">in</a>
        <a href="#" class="sicon">tw</a>
        <a href="#" class="sicon">fb</a>
        <a href="#" class="sicon">yt</a>
      </div>
    </div>
    <div class="footer-col">
      <h4>Platform</h4>
      <a href="#">Markets</a>
      <a href="#">Instruments</a>
      <a href="#">Mobile App</a>
      <a href="#">API Access</a>
    </div>
    <div class="footer-col">
      <h4>Legal</h4>
      <a href="#">Privacy Policy</a>
      <a href="#">Terms of Service</a>
      <a href="#">Risk Disclosure</a>
      <a href="#">Cookie Policy</a>
    </div>
    <div class="footer-col">
      <h4>Contact</h4>
      <a href="mailto:support@tradepro.com">support@tradepro.com</a>
      <a href="tel:+1234567890">+1 (234) 567-890</a>
      <p style="margin-top:8px;font-size:11px;">24 Finance Street<br/>London, UK</p>
    </div>
  </div>
  <div class="footer-bottom">
    <p>&copy; <?= date('Y') ?> TradePro. All rights reserved. Trading involves risk.</p>
  </div>
</footer>

<script src="script.js"></script>
</body>
</html>
