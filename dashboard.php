<?php
/**
 * dashboard.php — Protected trading dashboard
 */
session_start();

// Auth guard — redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require 'db.php';

// Refresh balance from DB on every load
$stmt = $pdo->prepare("SELECT balance, username FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

$_SESSION['balance']  = $user['balance'];
$_SESSION['username'] = $user['username'];

$balance  = number_format((float)$user['balance'], 2);
$username = htmlspecialchars($user['username']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard | TradePro</title>
  <link rel="stylesheet" href="style.css" />
  <script src="https://s3.tradingview.com/tv.js"></script>
</head>
<body class="dash-body">

<!-- ===== DASHBOARD NAVBAR ===== -->
<nav class="dash-nav">
  <a class="dash-logo" href="index.php">TRADE<span>PRO</span></a>

  <div class="dash-nav-pairs">
    <button class="pair-btn active" data-symbol="FX:EURUSD">EUR/USD</button>
    <button class="pair-btn"        data-symbol="FX:GBPUSD">GBP/USD</button>
    <button class="pair-btn"        data-symbol="FX:USDJPY">USD/JPY</button>
    <button class="pair-btn"        data-symbol="BITSTAMP:BTCUSD">BTC/USD</button>
    <button class="pair-btn"        data-symbol="CAPITALCOM:GOLD">GOLD</button>
  </div>

  <div class="dash-nav-right">
    <div class="balance-pill">
      <span class="bp-label">BALANCE</span>
      <span class="bp-value" id="balance-display">$<?= $balance ?></span>
    </div>
    <button class="btn-dep" id="btn-open-deposit">+ Deposit</button>
    <div class="user-pill">
      <?= strtoupper(substr($username, 0, 2)) ?>
    </div>
    <a href="logout.php" class="btn-logout">Logout</a>
  </div>
</nav>

<!-- ===== MAIN LAYOUT ===== -->
<div class="dash-layout">

  <!-- Chart Area -->
  <div class="dash-chart-wrap">
    <div id="tv-chart-container"></div>
  </div>

  <!-- Trade Panel -->
  <aside class="trade-panel">

    <!-- Live Price -->
    <div class="tp-section">
      <div class="tp-label">Live Market</div>
      <div class="price-box">
        <div class="pb-symbol" id="pb-symbol">EUR/USD</div>
        <div class="pb-price"  id="pb-price">1.08542</div>
        <div class="pb-change up" id="pb-change">+0.3 pips</div>
      </div>
    </div>

    <!-- Order Inputs -->
    <div class="tp-section">
      <div class="tp-label">New Order</div>

      <div class="tp-input-group">
        <label>Amount (USD)</label>
        <input type="number" id="d-amount"  placeholder="100.00" min="1" />
      </div>
      <div class="tp-input-group">
        <label>Take Profit (pips)</label>
        <input type="number" id="d-tp"      placeholder="50" min="1" />
      </div>
      <div class="tp-input-group">
        <label>Stop Loss (pips)</label>
        <input type="number" id="d-sl"      placeholder="30" min="1" />
      </div>

      <!-- Estimates -->
      <div class="est-grid">
        <div class="est-item"><span>Est. TP</span><strong id="est-tp">$0.00</strong></div>
        <div class="est-item"><span>Est. SL</span><strong id="est-sl">$0.00</strong></div>
        <div class="est-item"><span>R:R Ratio</span><strong id="est-rr">--</strong></div>
      </div>
    </div>

    <!-- Buy / Sell Buttons -->
    <div class="trade-btns">
      <button class="tbtn buy"  id="btn-buy">BUY</button>
      <button class="tbtn sell" id="btn-sell">SELL</button>
    </div>

    <!-- Open Positions -->
    <div class="tp-section" style="flex:1;overflow:hidden;display:flex;flex-direction:column;">
      <div class="tp-label">Open Positions</div>
      <div class="positions-wrap" id="positions-list">
        <p class="no-pos">No open positions</p>
      </div>
    </div>

  </aside>
</div>

<!-- ===== FOOTER BAR ===== -->
<footer class="dash-footer">
  <div><span class="live-dot"></span>Markets Live</div>
  <div>Welcome, <strong><?= $username ?></strong></div>
  <div>&copy; <?= date('Y') ?> TradePro. All rights reserved.</div>
</footer>

<!-- ===== DEPOSIT MODAL ===== -->
<div class="modal-bg" id="deposit-modal">
  <div class="modal-box">
    <button class="modal-x" data-close="deposit-modal">&#10005;</button>
    <h3 class="modal-title">Deposit Funds</h3>
    <form id="deposit-form">
      <div class="tp-input-group">
        <label>Amount (USD)</label>
        <input type="number" id="dep-amount" placeholder="Min. $10" min="10" />
      </div>
      <div class="tp-input-group">
        <label>Payment Method</label>
        <select id="dep-method" name="payment_method" class="tp-select">
          <option value="mpesa">M-Pesa</option>
          <option value="banktransfer">Bank Transfer</option>
          <option value="paypal">PayPal</option>
        </select>
      </div>
      <div class="modal-actions">
        <button type="button" class="mbtn cancel" data-close="deposit-modal">Cancel</button>
        <button type="submit" class="mbtn confirm">Submit</button>
      </div>
    </form>
  </div>
</div>

<!-- ===== TRADE CONFIRM MODAL ===== -->
<div class="modal-bg" id="trade-modal">
  <div class="modal-box">
    <button class="modal-x" data-close="trade-modal">&#10005;</button>
    <h3 class="modal-title">Confirm Order</h3>
    <div class="confirm-rows">
      <div class="cr"><span>Direction</span><strong id="cm-side">--</strong></div>
      <div class="cr"><span>Symbol</span>  <strong id="cm-symbol">--</strong></div>
      <div class="cr"><span>Amount</span>  <strong id="cm-amount">--</strong></div>
      <div class="cr"><span>Price</span>   <strong id="cm-price">--</strong></div>
      <div class="cr"><span>TP</span>      <strong id="cm-tp">--</strong></div>
      <div class="cr"><span>SL</span>      <strong id="cm-sl">--</strong></div>
    </div>
    <div class="modal-actions">
      <button class="mbtn cancel" data-close="trade-modal">Cancel</button>
      <button class="mbtn confirm" id="btn-confirm-trade">Confirm</button>
    </div>
  </div>
</div>

<!-- ===== TOAST ===== -->
<div class="toast" id="toast"></div>

<!-- Pass PHP balance to JS -->
<script>
  window.PHP_BALANCE  = <?= (float)$user['balance'] ?>;
  window.PHP_USERNAME = '<?= $username ?>';
</script>
<script src="script.js"></script>
</body>
</html>
