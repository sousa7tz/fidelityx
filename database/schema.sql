-- FidelityX (SaaS de fidelidade) - MySQL 8.0+
-- Infra: InnoDB + utf8mb4 + utf8mb4_unicode_ci

SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci;
SET time_zone = '+00:00';

-- =========================
-- merchants (Lojistas)
-- =========================
CREATE TABLE IF NOT EXISTS merchants (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

  name VARCHAR(255) NOT NULL,
  store_name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  plan ENUM('free', 'pro') NOT NULL,
  status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',

  -- Future-proofing (NULL)
  phone VARCHAR(30) NULL,
  cnpj VARCHAR(14) NULL,
  address VARCHAR(255) NULL,
  city VARCHAR(100) NULL,
  state VARCHAR(50) NULL,
  logo_url VARCHAR(2048) NULL,

  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  UNIQUE KEY uq_merchants_email (email),
  UNIQUE KEY uq_merchants_cnpj (cnpj),
  KEY idx_merchants_status (status),
  KEY idx_merchants_plan (plan)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================
-- customers (Clientes)
-- =========================
CREATE TABLE IF NOT EXISTS customers (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

  name VARCHAR(255) NOT NULL,
  phone VARCHAR(30) NOT NULL,

  -- Future-proofing (NULL)
  cpf VARCHAR(11) NULL,
  email VARCHAR(255) NULL,
  birth_date DATE NULL,
  gender ENUM('male', 'female', 'other') NULL,

  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  UNIQUE KEY uq_customers_phone (phone),
  UNIQUE KEY uq_customers_cpf (cpf),
  KEY idx_customers_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================
-- loyalty_cards (Cartão digital)
-- =========================
CREATE TABLE IF NOT EXISTS loyalty_cards (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

  merchant_id BIGINT UNSIGNED NOT NULL,
  customer_id BIGINT UNSIGNED NOT NULL,

  current_points INT NOT NULL DEFAULT 0,
  total_accumulated INT NOT NULL DEFAULT 0,

  -- Future-proofing (NULL)
  last_use_at TIMESTAMP NULL,
  expiration_date TIMESTAMP NULL,

  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  UNIQUE KEY uq_loyalty_cards_merchant_customer (merchant_id, customer_id),
  KEY idx_loyalty_cards_customer (customer_id),
  KEY idx_loyalty_cards_merchant (merchant_id),

  CONSTRAINT fk_loyalty_cards_merchant
    FOREIGN KEY (merchant_id) REFERENCES merchants(id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT fk_loyalty_cards_customer
    FOREIGN KEY (customer_id) REFERENCES customers(id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================
-- points_log (Histórico / Auditoria)
-- =========================
CREATE TABLE IF NOT EXISTS points_log (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

  card_id BIGINT UNSIGNED NOT NULL,
  type ENUM('earn', 'redeem') NOT NULL,
  quantity INT NOT NULL,
  description VARCHAR(255) NOT NULL,

  -- Future-proofing (NULL)
  responsible_user VARCHAR(255) NULL,
  ip_address VARCHAR(45) NULL,

  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  KEY idx_points_log_card (card_id),
  KEY idx_points_log_type (type),

  CONSTRAINT fk_points_log_card
    FOREIGN KEY (card_id) REFERENCES loyalty_cards(id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

