SET NAMES utf8mb4;
SET foreign_key_checks = 0;

CREATE DATABASE IF NOT EXISTS `enkrix_inventory`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `enkrix_inventory`;

-- -------------------------------------------------------
-- ROLES
-- -------------------------------------------------------
CREATE TABLE IF NOT EXISTS `roles` (
  `id`          INT          NOT NULL AUTO_INCREMENT,
  `name`        VARCHAR(50)  NOT NULL,
  `description` VARCHAR(255) DEFAULT NULL,
  `created_at`  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_roles_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------
-- USERS
-- -------------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
  `id`         INT          NOT NULL AUTO_INCREMENT,
  `name`       VARCHAR(100) NOT NULL,
  `email`      VARCHAR(150) NOT NULL,
  `password`   VARCHAR(255) NOT NULL,
  `role_id`    INT          NOT NULL,
  `is_active`  TINYINT(1)   NOT NULL DEFAULT 1,
  `last_login` TIMESTAMP    NULL,
  `created_at` TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_users_email` (`email`),
  KEY `idx_users_role` (`role_id`),
  CONSTRAINT `fk_users_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------
-- CATEGORIES
-- -------------------------------------------------------
CREATE TABLE IF NOT EXISTS `categories` (
  `id`          INT          NOT NULL AUTO_INCREMENT,
  `name`        VARCHAR(100) NOT NULL,
  `description` TEXT         DEFAULT NULL,
  `color`       VARCHAR(7)   NOT NULL DEFAULT '#D4A853',
  `created_by`  INT          DEFAULT NULL,
  `created_at`  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_categories_name` (`name`),
  KEY `idx_categories_created_by` (`created_by`),
  CONSTRAINT `fk_categories_user` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------
-- INVENTORY ITEMS
-- -------------------------------------------------------
CREATE TABLE IF NOT EXISTS `inventory_items` (
  `id`                  INT            NOT NULL AUTO_INCREMENT,
  `name`                VARCHAR(200)   NOT NULL,
  `category_id`         INT            NOT NULL,
  `description`         TEXT           DEFAULT NULL,
  `quantity`            INT            NOT NULL DEFAULT 0,
  `quantity_assigned`   INT            NOT NULL DEFAULT 0,
  `unit`                VARCHAR(50)    DEFAULT NULL,
  `condition_status`    ENUM('New','Good','Fair','Damaged') NOT NULL DEFAULT 'Good',
  `location`            VARCHAR(100)   DEFAULT NULL,
  `purchase_date`       DATE           DEFAULT NULL,
  `cost`                DECIMAL(12,2)  NOT NULL DEFAULT 0.00,
  `low_stock_threshold` INT            NOT NULL DEFAULT 5,
  `image`               VARCHAR(255)   DEFAULT NULL,
  `barcode`             VARCHAR(100)   DEFAULT NULL,
  `created_by`          INT            DEFAULT NULL,
  `created_at`          TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`          TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_items_barcode` (`barcode`),
  KEY `idx_items_category` (`category_id`),
  KEY `idx_items_condition` (`condition_status`),
  KEY `idx_items_location` (`location`),
  KEY `idx_items_created_by` (`created_by`),
  CONSTRAINT `fk_items_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `fk_items_user` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------
-- ASSIGNMENTS
-- -------------------------------------------------------
CREATE TABLE IF NOT EXISTS `assignments` (
  `id`                   INT          NOT NULL AUTO_INCREMENT,
  `item_id`              INT          NOT NULL,
  `assigned_to_type`     ENUM('department','individual') NOT NULL,
  `assigned_to_name`     VARCHAR(150) NOT NULL,
  `quantity_assigned`    INT          NOT NULL,
  `assigned_by`          INT          NOT NULL,
  `assignment_date`      DATE         NOT NULL,
  `expected_return_date` DATE         DEFAULT NULL,
  `actual_return_date`   DATE         DEFAULT NULL,
  `status`               ENUM('active','returned','overdue') NOT NULL DEFAULT 'active',
  `notes`                TEXT         DEFAULT NULL,
  `created_at`           TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`           TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_assignments_item` (`item_id`),
  KEY `idx_assignments_status` (`status`),
  KEY `idx_assignments_assigned_by` (`assigned_by`),
  CONSTRAINT `fk_assignments_item` FOREIGN KEY (`item_id`) REFERENCES `inventory_items` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `fk_assignments_user` FOREIGN KEY (`assigned_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------
-- ACTIVITY LOGS
-- -------------------------------------------------------
CREATE TABLE IF NOT EXISTS `activity_logs` (
  `id`          INT          NOT NULL AUTO_INCREMENT,
  `user_id`     INT          DEFAULT NULL,
  `user_name`   VARCHAR(100) NOT NULL,
  `action`      VARCHAR(100) NOT NULL,
  `entity_type` VARCHAR(50)  NOT NULL,
  `entity_id`   INT          DEFAULT NULL,
  `entity_name` VARCHAR(200) DEFAULT NULL,
  `details`     TEXT         DEFAULT NULL,
  `ip_address`  VARCHAR(45)  DEFAULT NULL,
  `created_at`  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_logs_user` (`user_id`),
  KEY `idx_logs_entity` (`entity_type`, `entity_id`),
  KEY `idx_logs_created` (`created_at`),
  CONSTRAINT `fk_logs_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET foreign_key_checks = 1;

-- -------------------------------------------------------
-- SEED DATA
-- -------------------------------------------------------
INSERT INTO `roles` (`name`, `description`) VALUES
  ('Admin',             'Full system control, manage users, view audit logs'),
  ('Inventory Manager', 'Manage items & stock, assign/recover items'),
  ('Viewer',            'Read-only access, view reports only');

-- Default admin: admin@enkrix.local / Admin@123
INSERT INTO `users` (`name`, `email`, `password`, `role_id`) VALUES
  ('System Admin', 'admin@enkrix.local', '$2y$12$XuK8sewmjnjHn2nKWtu22OER3bRl806btQE4vSDcg5JZrgDZbGNym', 1);

INSERT INTO `categories` (`name`, `description`, `color`, `created_by`) VALUES
  ('Audio Equipment',     'Microphones, speakers, amplifiers, mixers',        '#D4A853', 1),
  ('Furniture',           'Chairs, tables, podiums, stands',                  '#22C55E', 1),
  ('Media Devices',       'Cameras, laptops, projectors, screens',            '#3B82F6', 1),
  ('Musical Instruments', 'Guitars, keyboards, drums, wind instruments',      '#A855F7', 1),
  ('Books & Materials',   'Books, manuals, training and reference materials',  '#F59E0B', 1),
  ('Supplies & Consumables', 'Office supplies, consumables and store items',  '#EC4899', 1);

INSERT INTO `inventory_items`
  (`name`, `category_id`, `description`, `quantity`, `unit`, `condition_status`, `location`, `purchase_date`, `cost`, `low_stock_threshold`, `created_by`)
VALUES
  ('Shure SM58 Microphone',   1, 'Cardioid dynamic vocal microphone',     8,  'pcs', 'Good',    'Audio Store',  '2023-01-15', 12000.00, 2, 1),
  ('Yamaha MG10 Mixer',       1, '10-input stereo mixer',                 2,  'pcs', 'Good',    'Audio Store',  '2023-03-10', 45000.00, 1, 1),
  ('Padded Chairs',           2, 'Cushioned seating for office and events', 200,'pcs', 'Good',   'Main Hall',    '2022-06-01',  3500.00, 20, 1),
  ('Folding Tables',          2, '6ft folding banquet tables',            20, 'pcs', 'Good',    'Warehouse',    '2022-06-01',  8000.00,  5, 1),
  ('Epson Projector EB-X51',  3, '3800 lumens LCD projector',             3,  'pcs', 'Good',    'Media Room',   '2023-02-20', 85000.00,  1, 1),
  ('Canon DSLR Camera 250D',  3, '24.1 MP DSLR with 18-55mm kit lens',   2,  'pcs', 'Good',    'Media Room',   '2023-04-05',120000.00,  1, 1),
  ('Yamaha P-125 Keyboard',   4, '88-key weighted digital piano',         1,  'pcs', 'New',     'Studio',       '2024-01-10',180000.00,  1, 1),
  ('Acoustic Guitar',         4, 'Full-size acoustic guitar',             4,  'pcs', 'Good',    'Studio',       '2022-11-15', 25000.00,  2, 1),
  ('Training Manuals',        5, 'Operational and staff training manuals',50,  'pcs', 'Good',    'Library',      '2021-09-01',  2500.00, 10, 1),
  ('Printer Paper (Ream)',    6, 'A4 printer paper reams – store stock',  30,'reams','Good',    'Supply Store', '2024-02-01',  3500.00,  5, 1);

INSERT INTO `activity_logs` (`user_id`, `user_name`, `action`, `entity_type`, `entity_name`, `details`, `ip_address`) VALUES
  (1, 'System Admin', 'system_init', 'system', 'Enkrix IMS', 'System initialized with seed data', '127.0.0.1');
