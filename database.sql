-- ================================================
--  TradePro Platform — Database Schema
-- ================================================

CREATE DATABASE IF NOT EXISTS tradepro_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE tradepro_db;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id         INT           AUTO_INCREMENT PRIMARY KEY,
    username   VARCHAR(50)   NOT NULL UNIQUE,
    email      VARCHAR(100)  NOT NULL UNIQUE,
    password   VARCHAR(255)  NOT NULL,
    balance    DECIMAL(15,2) NOT NULL DEFAULT 1000.00,
    created_at TIMESTAMP     DEFAULT CURRENT_TIMESTAMP
);

-- Trades table (history)
CREATE TABLE IF NOT EXISTS trades (
    id         INT           AUTO_INCREMENT PRIMARY KEY,
    user_id    INT           NOT NULL,
    symbol     VARCHAR(20)   NOT NULL,
    direction  ENUM('BUY','SELL') NOT NULL,
    amount     DECIMAL(15,2) NOT NULL,
    tp_pips    DECIMAL(10,2) NOT NULL,
    sl_pips    DECIMAL(10,2) NOT NULL,
    pnl        DECIMAL(15,2) DEFAULT NULL,
    status     ENUM('open','closed') DEFAULT 'open',
    opened_at  TIMESTAMP     DEFAULT CURRENT_TIMESTAMP,
    closed_at  TIMESTAMP     NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Deposits table
CREATE TABLE IF NOT EXISTS deposits (
    id         INT           AUTO_INCREMENT PRIMARY KEY,
    user_id    INT           NOT NULL,
    amount     DECIMAL(15,2) NOT NULL,
    method     VARCHAR(50)   DEFAULT 'Bank Transfer',
    status     ENUM('pending','approved','rejected') DEFAULT 'pending',
    created_at TIMESTAMP     DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Demo seed user  (password: Demo@1234)
INSERT INTO users (username, email, password, balance) VALUES
(
  'demo_trader',
  'demo@tradepro.com',
  '$2y$12$exampleHashPlaceholderOnlyXXXXXXXXXXXXXXXXXXXXXXXXXXXX',
  24580.50
);
