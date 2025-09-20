-- 
CREATE TABLE `ticket_purchases` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'チケット購入ID',
  `email` varbinary(254) NOT NULL COMMENT 'メールアドレス',
  `purchaser_name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '購入者名',
  `quantity` int unsigned NOT NULL COMMENT '購入枚数',
  `token` varbinary(255) NOT NULL COMMENT '識別用トークン',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_ticket_purchases_email` (`email`),
  KEY `idx_ticket_purchases_token` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='チケット購入';

-- 
CREATE TABLE `email_send_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'メール送信履歴ID',
  `ticket_purchase_id` bigint unsigned NOT NULL COMMENT 'チケット購入ID（参照用・FKなし）',
  `email` varbinary(254) NOT NULL COMMENT 'メールアドレス',
  `purchaser_name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '購入者名',
  `quantity` int unsigned NOT NULL COMMENT '購入枚数',
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'メールtitle',
  `body` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'メール本文',
  `sent_at` datetime NOT NULL COMMENT '送信日時',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_email_logs_purchase_ref` (`ticket_purchase_id`),
  KEY `idx_email_logs_email` (`email`),
  KEY `idx_email_logs_sent_at` (`sent_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='メール送信履歴';

-- 
CREATE TABLE `ticket_token_usages` (
  `token` varbinary(255) NOT NULL COMMENT '識別用トークン',
  `created_at` datetime NOT NULL COMMENT '作成日時',
  `updated_at` datetime NOT NULL COMMENT '更新日時',
  PRIMARY KEY (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='チケットトークン使用履歴';
