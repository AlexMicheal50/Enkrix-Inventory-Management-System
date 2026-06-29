-- Add selling_price to inventory_items (safe: ignore error if column already exists)
ALTER TABLE inventory_items ADD COLUMN selling_price DECIMAL(12,2) NOT NULL DEFAULT 0.00 AFTER cost;

-- Sales table
CREATE TABLE IF NOT EXISTS sales (
  id             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  item_id        INT UNSIGNED NOT NULL,
  item_name      VARCHAR(255) NOT NULL,
  quantity_sold  INT UNSIGNED NOT NULL DEFAULT 1,
  cost_price     DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  selling_price  DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  total_cost     DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  total_revenue  DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  profit         DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  sold_by        INT UNSIGNED NOT NULL,
  sale_date      DATE NOT NULL,
  notes          TEXT NULL,
  created_at     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_item_id (item_id),
  INDEX idx_sale_date (sale_date),
  INDEX idx_sold_by (sold_by)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Expenses table
CREATE TABLE IF NOT EXISTS expenses (
  id             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title          VARCHAR(255) NOT NULL,
  category       VARCHAR(100) NOT NULL DEFAULT 'General',
  amount         DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  expense_date   DATE NOT NULL,
  description    TEXT NULL,
  recorded_by    INT UNSIGNED NOT NULL,
  created_at     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_expense_date (expense_date),
  INDEX idx_recorded_by (recorded_by)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Stock Movements table
CREATE TABLE IF NOT EXISTS stock_movements (
  id               INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  item_id          INT UNSIGNED NOT NULL,
  item_name        VARCHAR(255) NOT NULL,
  movement_type    ENUM('stock_in','stock_out','sale','assignment','return','adjustment') NOT NULL,
  quantity_change  INT NOT NULL,
  quantity_before  INT NOT NULL DEFAULT 0,
  quantity_after   INT NOT NULL DEFAULT 0,
  reference_type   VARCHAR(50) NULL,
  reference_id     INT UNSIGNED NULL,
  notes            TEXT NULL,
  created_by       INT UNSIGNED NULL,
  created_at       TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_item_id (item_id),
  INDEX idx_movement_type (movement_type),
  INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
