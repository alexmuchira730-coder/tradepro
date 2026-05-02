/* =====================================================
   TradePro — Global JavaScript
   ===================================================== */

'use strict';

/* =====================================================
   UTILITY: Toast notification
   ===================================================== */
function showToast(msg, type = 'success') {
  const t = document.getElementById('toast');
  if (!t) return;
  t.textContent = msg;
  t.className = 'toast ' + type + ' show';
  setTimeout(() => { t.className = 'toast'; }, 3200);
}

/* =====================================================
   UTILITY: Modal open / close
   ===================================================== */
function openModal(id)  { const m = document.getElementById(id); if (m) m.classList.add('open'); }
function closeModal(id) { const m = document.getElementById(id); if (m) m.classList.remove('open'); }

// Close on overlay click
document.addEventListener('click', function (e) {
  if (e.target.classList.contains('modal-bg')) {
    e.target.classList.remove('open');
  }
});

// Close on X buttons / cancel buttons with data-close attribute
document.addEventListener('click', function (e) {
  const target = e.target.closest('[data-close]');
  if (target) closeModal(target.dataset.close);
});

// ESC to close
document.addEventListener('keydown', function (e) {
  if (e.key === 'Escape') {
    document.querySelectorAll('.modal-bg.open').forEach(m => m.classList.remove('open'));
  }
});

/* =====================================================
   LANDING PAGE SCRIPTS
   ===================================================== */
(function landingPage() {
  if (!document.querySelector('.landing-page')) return;

  // ---- Sticky nav shadow ----
  const nav = document.getElementById('lp-nav');
  if (nav) {
    window.addEventListener('scroll', () => {
      nav.classList.toggle('scrolled', window.scrollY > 20);
    });
  }

  // ---- Mobile hamburger ----
  const hamburger = document.getElementById('hamburger');
  const navLinks  = document.getElementById('lp-nav-links');
  if (hamburger && navLinks) {
    hamburger.addEventListener('click', () => {
      const open = navLinks.style.display === 'flex';
      navLinks.style.cssText = open
        ? ''
        : 'display:flex;flex-direction:column;position:absolute;top:64px;left:0;right:0;background:var(--bg1);padding:20px;gap:16px;border-bottom:1px solid var(--border);z-index:999;';
    });
  }

  // ---- Hero TradingView chart ----
  if (typeof TradingView !== 'undefined' && document.getElementById('hero-chart')) {
    new TradingView.widget({
      container_id:      'hero-chart',
      symbol:            'FX:EURUSD',
      interval:          '15',
      timezone:          'Etc/UTC',
      theme:             'dark',
      style:             '1',
      locale:            'en',
      toolbar_bg:        '#0b1120',
      hide_top_toolbar:  true,
      hide_side_toolbar: true,
      allow_symbol_change: false,
      enable_publishing: false,
      autosize:          true,
    });
  }

  // ---- Live Marquee Ticker ----
  const tickers = [
    { sym: 'EUR/USD', price: 1.08542, pip: 0.0001 },
    { sym: 'GBP/USD', price: 1.26780, pip: 0.0001 },
    { sym: 'USD/JPY', price: 149.320, pip: 0.01   },
    { sym: 'AUD/USD', price: 0.65280, pip: 0.0001 },
    { sym: 'USD/CHF', price: 0.90150, pip: 0.0001 },
    { sym: 'NZD/USD', price: 0.59640, pip: 0.0001 },
    { sym: 'BTC/USD', price: 67450.0, pip: 1      },
    { sym: 'ETH/USD', price: 3420.50, pip: 0.01   },
    { sym: 'GOLD',    price: 2325.50, pip: 0.01   },
    { sym: 'OIL',     price: 81.340,  pip: 0.001  },
    { sym: 'S&P 500', price: 5242.50, pip: 0.01   },
    { sym: 'NASDAQ',  price: 18340.0, pip: 0.01   },
  ];

  const track = document.getElementById('marquee-track');
  if (track) {
    function buildMarquee() {
      const double = [...tickers, ...tickers]; // duplicate for seamless loop
      track.innerHTML = double.map(t => {
        const chg = ((Math.random() - 0.48) * 0.5).toFixed(2);
        const dir = chg >= 0 ? 'up' : 'down';
        const arrow = chg >= 0 ? '+' : '';
        return `<div class="ticker-item">
          <span class="ticker-symbol">${t.sym}</span>
          <span class="ticker-price">${t.price.toFixed(t.pip < 0.01 ? 5 : 2)}</span>
          <span class="ticker-chg ${dir}">${arrow}${chg}%</span>
        </div>`;
      }).join('');
    }
    buildMarquee();
    setInterval(buildMarquee, 4000);
  }

  // ---- Testimonial Slider ----
  const testiTrack = document.getElementById('testi-track');
  const dotsWrap   = document.getElementById('testi-dots');

  if (testiTrack && dotsWrap) {
    const cards        = testiTrack.querySelectorAll('.testi-card');
    const perView      = window.innerWidth < 600 ? 1 : window.innerWidth < 900 ? 2 : 3;
    let   currentSlide = 0;
    const totalSlides  = Math.ceil(cards.length / perView);

    // Build dots
    for (let i = 0; i < totalSlides; i++) {
      const d = document.createElement('div');
      d.className = 'testi-dot' + (i === 0 ? ' active' : '');
      d.addEventListener('click', () => goTo(i));
      dotsWrap.appendChild(d);
    }

    function goTo(idx) {
      currentSlide = idx;
      const pct    = idx * (100 / perView);
      testiTrack.style.transform = `translateX(-${pct}%)`;
      dotsWrap.querySelectorAll('.testi-dot').forEach((d, i) => {
        d.classList.toggle('active', i === idx);
      });
    }

    // Auto-advance
    setInterval(() => {
      goTo((currentSlide + 1) % totalSlides);
    }, 4500);
  }

  // ---- Scroll reveal for sections ----
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.style.animation = 'fadeUp 0.6s ease both';
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.1 });

  document.querySelectorAll('.feat-card, .step-card, .testi-card').forEach(el => {
    el.style.opacity = '0';
    observer.observe(el);
  });

})();

/* =====================================================
   AUTH FORM VALIDATION
   ===================================================== */
(function authForms() {
  const regForm   = document.getElementById('reg-form');
  const loginForm = document.getElementById('login-form');

  if (regForm) {
    regForm.addEventListener('submit', function (e) {
      let valid = true;
      const fields = regForm.querySelectorAll('input[required]');
      fields.forEach(f => {
        f.classList.remove('invalid');
        if (!f.value.trim()) { f.classList.add('invalid'); valid = false; }
      });
      const email = regForm.querySelector('input[name="email"]');
      if (email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
        email.classList.add('invalid');
        valid = false;
      }
      const pw  = regForm.querySelector('input[name="password"]');
      const con = regForm.querySelector('input[name="confirm"]');
      if (pw && con && pw.value !== con.value) {
        pw.classList.add('invalid');
        con.classList.add('invalid');
        valid = false;
      }
      if (!valid) e.preventDefault();
    });
  }

  if (loginForm) {
    loginForm.addEventListener('submit', function (e) {
      let valid = true;
      loginForm.querySelectorAll('input[required]').forEach(f => {
        f.classList.remove('invalid');
        if (!f.value.trim()) { f.classList.add('invalid'); valid = false; }
      });
      if (!valid) e.preventDefault();
    });
  }
})();

/* =====================================================
   DASHBOARD
   ===================================================== */
(function dashboard() {
  if (!document.querySelector('.dash-body')) return;

  // ---- State ----
  const S = {
    balance:   window.PHP_BALANCE  || 1000.00,
    username:  window.PHP_USERNAME || 'Trader',
    symbol:    'FX:EURUSD',
    symLabel:  'EUR/USD',
    price:     1.08542,
    isJPY:     false,
    positions: [],
    nextId:    1,
    tradeSide: null,
  };

  // ---- Base prices per symbol ----
  const basePrices = {
    'FX:EURUSD':       { p: 1.08542, label: 'EUR/USD',  jpy: false },
    'FX:GBPUSD':       { p: 1.26780, label: 'GBP/USD',  jpy: false },
    'FX:USDJPY':       { p: 149.320, label: 'USD/JPY',  jpy: true  },
    'BITSTAMP:BTCUSD': { p: 67450.0, label: 'BTC/USD',  jpy: false },
    'CAPITALCOM:GOLD': { p: 2325.50, label: 'GOLD',     jpy: false },
  };

  // ---- DOM ----
  const balEl     = document.getElementById('balance-display');
  const dBalEl    = document.getElementById('drawer-balance');
  const priceEl   = document.getElementById('pb-price');
  const changeEl  = document.getElementById('pb-change');
  const symEl     = document.getElementById('pb-symbol');
  const amtIn     = document.getElementById('d-amount');
  const tpIn      = document.getElementById('d-tp');
  const slIn      = document.getElementById('d-sl');
  const estTpEl   = document.getElementById('est-tp');
  const estSlEl   = document.getElementById('est-sl');
  const estRrEl   = document.getElementById('est-rr');
  const posList   = document.getElementById('positions-list');

  // ---- Balance ----
  function renderBalance() {
    const fmt = '$' + S.balance.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    if (balEl) balEl.textContent = fmt;
    if (dBalEl) dBalEl.textContent = fmt;
  }

  // ---- TradingView Chart ----
  let tvWidget = null;

  function loadChart(symbol) {
    const el = document.getElementById('tv-chart-container');
    if (!el || typeof TradingView === 'undefined') return;
    el.innerHTML = '';
    tvWidget = new TradingView.widget({
      container_id:      'tv-chart-container',
      symbol:            symbol,
      interval:          '5',
      timezone:          'Etc/UTC',
      theme:             'dark',
      style:             '1',
      locale:            'en',
      toolbar_bg:        '#0b1120',
      enable_publishing: false,
      hide_side_toolbar: false,
      allow_symbol_change: true,
      autosize:          true,
    });
  }

  // ---- Pair buttons ----
  document.querySelectorAll('.pair-btn').forEach(btn => {
    btn.addEventListener('click', function () {
      document.querySelectorAll('.pair-btn').forEach(b => b.classList.remove('active'));
      this.classList.add('active');
      const sym  = this.dataset.symbol;
      const data = basePrices[sym];
      if (!data) return;
      S.symbol   = sym;
      S.symLabel = data.label;
      S.price    = data.p;
      S.isJPY    = data.jpy;
      if (symEl) symEl.textContent = data.label;
      loadChart(sym);
    });
  });

  // ---- Live price simulation ----
  function tickPrice() {
    const noise = (Math.random() - 0.5) * (S.isJPY ? 0.05 : 0.0005);
    S.price    += noise;
    const dec   = S.isJPY ? 3 : 5;
    const pips  = (noise / (S.isJPY ? 0.01 : 0.0001)).toFixed(1);
    if (priceEl)  priceEl.textContent  = S.price.toFixed(dec);
    if (changeEl) {
      changeEl.textContent = (pips >= 0 ? '+' : '') + pips + ' pips';
      changeEl.className   = 'pb-change ' + (pips >= 0 ? 'up' : 'down');
    }
    updateEstimates();
  }

  setInterval(tickPrice, 2000);

  // ---- Estimates ----
  function updateEstimates() {
    const amt = parseFloat(amtIn?.value) || 0;
    const tp  = parseFloat(tpIn?.value)  || 0;
    const sl  = parseFloat(slIn?.value)  || 0;
    const pv  = S.isJPY ? 0.01 : 0.0001;

    const tpUsd = (tp * pv * (amt / S.price)).toFixed(2);
    const slUsd = (sl * pv * (amt / S.price)).toFixed(2);
    const rr    = sl > 0 ? (tp / sl).toFixed(2) : '--';

    if (estTpEl) estTpEl.textContent = '$' + tpUsd;
    if (estSlEl) estSlEl.textContent = '$' + slUsd;
    if (estRrEl) estRrEl.textContent = '1 : ' + rr;
  }

  [amtIn, tpIn, slIn].forEach(el => { if (el) el.addEventListener('input', updateEstimates); });

  // ---- Deposit modal ----
  const btnDep = document.getElementById('btn-open-deposit');
  if (btnDep) btnDep.addEventListener('click', () => openModal('deposit-modal'));

  const depForm = document.getElementById('deposit-form');
  if (depForm) {
    depForm.addEventListener('submit', function (e) {
      e.preventDefault();
      const amt = parseFloat(document.getElementById('dep-amount')?.value);
      const method = document.getElementById('dep-method')?.value || 'banktransfer';
      if (!amt || amt < 10) { showToast('Minimum deposit is $10', 'error'); return; }

      // Simulate deposit approval
      S.balance += amt;
      renderBalance();
      closeModal('deposit-modal');
      depForm.reset();
      showToast('Deposit of $' + amt.toFixed(2) + ' via ' + (method === 'mpesa' ? 'M-Pesa' : method === 'paypal' ? 'PayPal' : 'Bank Transfer') + ' submitted successfully!', 'success');

      // In production: send POST request to deposit.php here
    });
  }

  // ---- Buy / Sell ----
  const btnBuy  = document.getElementById('btn-buy');
  const btnSell = document.getElementById('btn-sell');

  function openTradeModal(side) {
    const amt = parseFloat(amtIn?.value);
    const tp  = parseFloat(tpIn?.value);
    const sl  = parseFloat(slIn?.value);

    // Validate inputs
    let valid = true;
    [amtIn, tpIn, slIn].forEach(el => el?.classList.remove('invalid'));

    if (!amt || amt < 1)          { amtIn?.classList.add('invalid'); valid = false; }
    if (!tp  || tp  < 1)          { tpIn?.classList.add('invalid');  valid = false; }
    if (!sl  || sl  < 1)          { slIn?.classList.add('invalid');  valid = false; }
    if (amt  > S.balance)         { amtIn?.classList.add('invalid'); showToast('Insufficient balance', 'error'); valid = false; }

    if (!valid) { showToast('Please fill all fields correctly', 'error'); return; }

    S.tradeSide = side;

    const pv    = S.isJPY ? 0.01 : 0.0001;
    const tpUsd = (tp * pv * (amt / S.price)).toFixed(2);
    const slUsd = (sl * pv * (amt / S.price)).toFixed(2);

    const cmSide = document.getElementById('cm-side');
    if (cmSide) {
      cmSide.textContent = side;
      cmSide.className   = side === 'BUY' ? 'pos-buy' : 'pos-sell';
    }

    const setVal = (id, val) => { const el = document.getElementById(id); if (el) el.textContent = val; };
    setVal('cm-symbol', S.symLabel);
    setVal('cm-amount', '$' + amt.toFixed(2));
    setVal('cm-price',  S.price.toFixed(S.isJPY ? 3 : 5));
    setVal('cm-tp',     tp  + ' pips (~$' + tpUsd + ')');
    setVal('cm-sl',     sl  + ' pips (~$' + slUsd + ')');

    const confBtn = document.getElementById('btn-confirm-trade');
    if (confBtn) {
      confBtn.style.background = side === 'BUY' ? 'var(--green)' : 'var(--red)';
      confBtn.style.color      = side === 'BUY' ? '#000' : '#fff';
      confBtn.textContent      = 'Confirm ' + side;
    }

    openModal('trade-modal');
  }

  if (btnBuy)  btnBuy.addEventListener('click',  () => openTradeModal('BUY'));
  if (btnSell) btnSell.addEventListener('click', () => openTradeModal('SELL'));

  // ---- Confirm trade ----
  const confirmBtn = document.getElementById('btn-confirm-trade');
  if (confirmBtn) {
    confirmBtn.addEventListener('click', function () {
      const amt = parseFloat(amtIn?.value);
      const tp  = parseFloat(tpIn?.value);
      const sl  = parseFloat(slIn?.value);

      S.balance -= amt;
      renderBalance();

      const pos = {
        id:     S.nextId++,
        side:   S.tradeSide,
        symbol: S.symLabel,
        amount: amt,
        tp:     tp,
        sl:     sl,
        entry:  S.price,
        pnl:    0.00,
      };

      S.positions.push(pos);
      renderPositions();
      closeModal('trade-modal');
      showToast(pos.side + ' ' + pos.symbol + ' $' + amt.toFixed(2) + ' opened!', 'success');

      // In production: POST to trade.php via fetch()
    });
  }

  // ---- Positions ----
  function renderPositions() {
    if (!posList) return;

    if (S.positions.length === 0) {
      posList.innerHTML = '<p class="no-pos">No open positions</p>';
      return;
    }

    posList.innerHTML = S.positions.map(pos => `
      <div class="pos-item" id="pos-${pos.id}">
        <div class="pos-head">
          <span class="${pos.side === 'BUY' ? 'pos-buy' : 'pos-sell'}">${pos.side}</span>
          <span>${pos.symbol}</span>
          <button class="btn-close-pos" onclick="closePosition(${pos.id})">X</button>
        </div>
        <div class="pos-foot">
          <span>$${pos.amount.toFixed(2)}</span>
          <span class="pos-pnl" id="pnl-${pos.id}">$0.00</span>
        </div>
      </div>
    `).join('');
  }

  // Expose closePosition globally for onclick
  window.closePosition = function (id) {
    const pos = S.positions.find(p => p.id === id);
    if (!pos) return;

    const pnl = pos.pnl;
    S.balance += pos.amount + pnl;
    renderBalance();
    S.positions = S.positions.filter(p => p.id !== id);
    renderPositions();

    const sign = pnl >= 0 ? '+' : '';
    showToast('Closed. P&L: ' + sign + '$' + pnl.toFixed(2), pnl >= 0 ? 'success' : 'error');
  };

  // Floating P&L simulation
  setInterval(() => {
    S.positions.forEach(pos => {
      const el = document.getElementById('pnl-' + pos.id);
      if (!el) return;
      pos.pnl  += (Math.random() - 0.46) * pos.amount * 0.018;
      el.textContent = (pos.pnl >= 0 ? '+' : '') + '$' + pos.pnl.toFixed(2);
      el.className   = 'pos-pnl ' + (pos.pnl >= 0 ? 'profit' : 'loss');
    });
  }, 2000);

  // ---- Init ----
  renderBalance();
  renderPositions();
  loadChart(S.symbol);

})();
