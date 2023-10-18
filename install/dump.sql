CREATE TABLE `users` (
`user_id` bigint unsigned NOT NULL AUTO_INCREMENT,
`email` varchar(320) NOT NULL,
`password` varchar(128) DEFAULT NULL,
`name` varchar(64) NOT NULL,
`billing` text,
`api_key` varchar(32) DEFAULT NULL,
`token_code` varchar(32) DEFAULT NULL,
`twofa_secret` varchar(16) DEFAULT NULL,
`anti_phishing_code` varchar(8) DEFAULT NULL,
`one_time_login_code` varchar(32) DEFAULT NULL,
`pending_email` varchar(128) DEFAULT NULL,
`email_activation_code` varchar(32) DEFAULT NULL,
`lost_password_code` varchar(32) DEFAULT NULL,
`type` tinyint NOT NULL DEFAULT '0',
`status` tinyint NOT NULL DEFAULT '0',
`plan_id` varchar(16) NOT NULL DEFAULT '',
`plan_expiration_date` datetime DEFAULT NULL,
`plan_settings` text,
`plan_trial_done` tinyint DEFAULT '0',
`plan_expiry_reminder` tinyint DEFAULT '0',
`payment_subscription_id` varchar(64) DEFAULT NULL,
`payment_processor` varchar(16) DEFAULT NULL,
`payment_total_amount` float DEFAULT NULL,
`payment_currency` varchar(4) DEFAULT NULL,
`referral_key` varchar(32) DEFAULT NULL,
`referred_by` varchar(32) DEFAULT NULL,
`referred_by_has_converted` tinyint DEFAULT '0',
`language` varchar(32) DEFAULT 'english',
`timezone` varchar(32) DEFAULT 'UTC',
`datetime` datetime DEFAULT NULL,
`ip` varchar(64) DEFAULT NULL,
`country` varchar(32) DEFAULT NULL,
`last_activity` datetime DEFAULT NULL,
`last_user_agent` varchar(256) DEFAULT NULL,
`total_logins` int DEFAULT '0',
`user_deletion_reminder` tinyint(4) DEFAULT '0',
PRIMARY KEY (`user_id`),
KEY `plan_id` (`plan_id`),
KEY `api_key` (`api_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

INSERT INTO `users` (`user_id`, `email`, `password`, `api_key`, `referral_key`, `name`, `type`, `status`, `plan_id`, `plan_expiration_date`, `plan_settings`, `datetime`, `ip`, `last_activity`)
VALUES (1,'admin','$2y$10$uFNO0pQKEHSFcus1zSFlveiPCB3EvG9ZlES7XKgJFTAl5JbRGFCWy', md5(rand()), md5(rand()), 'AltumCode',1,1,'custom','2030-01-01 12:00:00', '{"vcards_limit":-1,"vcard_blocks_limit":-1,"projects_limit":-1,"pixels_limit":-1,"domains_limit":-1,"statistics_retention":365,"additional_domains_is_enabled":true,"analytics_is_enabled":true,"removable_branding_is_enabled":true,"custom_url_is_enabled":true,"password_protection_is_enabled":true,"search_engine_block_is_enabled":true,"custom_css_is_enabled":true,"custom_js_is_enabled":true,"email_reports_is_enabled":false,"api_is_enabled":true,"affiliate_is_enabled":false,"no_ads":true}', NOW(),'',NOW());

-- SEPARATOR --

CREATE TABLE `users_logs` (
`id` bigint unsigned NOT NULL AUTO_INCREMENT,
`user_id` bigint unsigned DEFAULT NULL,
`type` varchar(64) DEFAULT NULL,
`ip` varchar(64) DEFAULT NULL,
`device_type` varchar(16) DEFAULT NULL,
`os_name` varchar(16) DEFAULT NULL,
`country_code` varchar(8) DEFAULT NULL,
`datetime` datetime DEFAULT NULL,
PRIMARY KEY (`id`),
KEY `users_logs_user_id` (`user_id`),
CONSTRAINT `users_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `plans` (
`plan_id` int unsigned NOT NULL AUTO_INCREMENT,
`name` varchar(256) NOT NULL DEFAULT '',
`description` varchar(256) NOT NULL DEFAULT '',
`monthly_price` float DEFAULT NULL,
`annual_price` float DEFAULT NULL,
`lifetime_price` float DEFAULT NULL,
`trial_days` int unsigned NOT NULL DEFAULT '0',
`settings` text NOT NULL,
`taxes_ids` text,
`codes_ids` text,
`color` varchar(16) DEFAULT NULL,
`status` tinyint NOT NULL,
`order` int unsigned DEFAULT '0',
`datetime` datetime NOT NULL,
PRIMARY KEY (`plan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `pages_categories` (
`pages_category_id` bigint unsigned NOT NULL AUTO_INCREMENT,
`url` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
`title` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
`description` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT '',
`icon` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`order` int NOT NULL DEFAULT '0',
`language` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
PRIMARY KEY (`pages_category_id`),
KEY `url` (`url`),
KEY `pages_categories_url_language_index` (`url`,`language`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- SEPARATOR --

CREATE TABLE `pages` (
`page_id` bigint unsigned NOT NULL AUTO_INCREMENT,
`pages_category_id` bigint unsigned DEFAULT NULL,
`url` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
`title` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
`description` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`editor` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`content` longtext COLLATE utf8mb4_unicode_ci,
`type` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT '',
`position` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
`language` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`open_in_new_tab` tinyint DEFAULT '1',
`order` int DEFAULT '0',
`total_views` int DEFAULT '0',
`datetime` datetime DEFAULT NULL,
`last_datetime` datetime DEFAULT NULL,
PRIMARY KEY (`page_id`),
KEY `pages_pages_category_id_index` (`pages_category_id`),
KEY `pages_url_index` (`url`),
KEY `pages_url_language_index` (`url`,`language`),
CONSTRAINT `pages_ibfk_1` FOREIGN KEY (`pages_category_id`) REFERENCES `pages_categories` (`pages_category_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

INSERT INTO `pages` (`pages_category_id`, `url`, `title`, `description`, `content`, `type`, `position`, `order`, `total_views`, `datetime`, `last_datetime`) VALUES
(NULL, 'https://altumcode.com/', 'Software by AltumCode', '', '', 'external', 'bottom', 1, 0, NOW(), NOW()),
(NULL, 'https://altumco.de/66vcard', 'Built with 66vcard', '', '', 'external', 'bottom', 0, 0, NOW(), NOW());

-- SEPARATOR --

CREATE TABLE `settings` (
`id` int NOT NULL AUTO_INCREMENT,
`key` varchar(64) NOT NULL DEFAULT '',
`value` longtext NOT NULL,
PRIMARY KEY (`id`),
UNIQUE KEY `key` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- SEPARATOR --

SET @cron_key = MD5(RAND());

-- SEPARATOR --

INSERT INTO `settings` (`key`, `value`) VALUES
('main', '{"title": "Your title", "index_url": "", "se_indexing": true, "not_found_url": "", "default_language": "english", "default_timezone": "UTC", "privacy_policy_url": "", "default_theme_style": "light", "terms_and_conditions_url": ""}'),
('users', '{"email_confirmation":false,"register_is_enabled":true,"auto_delete_inactive_users":0,"user_deletion_reminder":0}'),
('ads', '{\"header\":\"\",\"footer\":\"\"}'),
('captcha', '{\"type\":\"basic\",\"recaptcha_public_key\":\"\",\"recaptcha_private_key\":\"\",\"login_is_enabled\":0,\"register_is_enabled\":0,\"lost_password_is_enabled\":0,\"resend_activation_is_enabled\":0}'),
('cron', concat('{\"key\":\"', @cron_key, '\"}')),
('email_notifications', '{\"emails\":\"\",\"new_user\":\"\",\"new_payment\":\"\"}'),
('facebook', '{\"is_enabled\":\"0\",\"app_id\":\"\",\"app_secret\":\"\"}'),
('google', '{\"is_enabled\":\"0\",\"client_id\":\"\",\"client_secret\":\"\"}'),
('twitter', '{\"is_enabled\":\"0\",\"consumer_api_key\":\"\",\"consumer_api_secret\":\"\"}'),
('discord', '{\"is_enabled\":\"0\"}'),
('plan_custom', '{\"plan_id\":\"custom\",\"name\":\"Custom\",\"description\":\"\",\"price\":\":)\",\"custom_button_url\":\"https:\\/\\/altumcode.com\\/\",\"color\":null,\"status\":2,\"settings\":{\"vcards_limit\":-1,\"vcard_blocks_limit\":-1,\"projects_limit\":-1,\"pixels_limit\":-1,\"domains_limit\":-1,\"statistics_retention\":-1,\"additional_domains_is_enabled\":true,\"analytics_is_enabled\":true,\"removable_branding_is_enabled\":true,\"custom_url_is_enabled\":true,\"password_protection_is_enabled\":true,\"search_engine_block_is_enabled\":true,\"custom_css_is_enabled\":true,\"custom_js_is_enabled\":true,\"api_is_enabled\":false,\"affiliate_is_enabled\":false,\"no_ads\":true}}'),
('plan_free', '{\"plan_id\":\"free\",\"name\":\"Free\",\"description\":\"\",\"price\":\"Free\",\"color\":null,\"status\":1,\"settings\":{\"vcards_limit\":-1,\"vcard_blocks_limit\":-1,\"projects_limit\":-1,\"pixels_limit\":-1,\"domains_limit\":-1,\"statistics_retention\":-1,\"additional_domains_is_enabled\":true,\"analytics_is_enabled\":true,\"removable_branding_is_enabled\":true,\"custom_url_is_enabled\":true,\"password_protection_is_enabled\":true,\"search_engine_block_is_enabled\":true,\"custom_css_is_enabled\":false,\"custom_js_is_enabled\":false,\"api_is_enabled\":false,\"affiliate_is_enabled\":false,\"no_ads\":true}}'),
('payment', '{\"is_enabled\":false,\"type\":\"both\",\"currency\":\"USD\",\"codes_is_enabled\":false,\"taxes_and_billing_is_enabled\":false,\"invoice_is_enabled\":false}'),
('paypal', '{\"is_enabled\":\"0\",\"mode\":\"sandbox\",\"client_id\":\"\",\"secret\":\"\"}'),
('stripe', '{\"is_enabled\":\"0\",\"publishable_key\":\"\",\"secret_key\":\"\",\"webhook_secret\":\"\"}'),
('offline_payment', '{\"is_enabled\":\"0\",\"instructions\":\"Your offline payment instructions go here..\"}'),
('coinbase', '{\"is_enabled\":\"0\"}'),
('payu', '{\"is_enabled\":\"0\"}'),
('paystack', '{\"is_enabled\":\"0\"}'),
('razorpay', '{\"is_enabled\":\"0\"}'),
('mollie', '{\"is_enabled\":\"0\"}'),
('yookassa', '{\"is_enabled\":\"0\"}'),
('crypto_com', '{\"is_enabled\":\"0\"}'),
('paddle', '{\"is_enabled\":\"0\"}'),
('smtp', '{\"host\":\"\",\"from\":\"\",\"from_name\":\"\",\"encryption\":\"tls\",\"port\":\"587\",\"auth\":\"1\",\"username\":\"\",\"password\":\"\"}'),
('custom', '{\"head_js\":\"\",\"head_css\":\"\"}'),
('socials', '{\"facebook\":\"\",\"instagram\":\"\",\"twitter\":\"\",\"youtube\":\"\"}'),
('announcements', '{\"id\":\"\",\"content\":\"\",\"show_logged_in\":\"\",\"show_logged_out\":\"\"}'),
('business', '{\"brand_name\":\"66vcard\",\"invoice_nr_prefix\":\"INVOICE-\",\"name\":\"\",\"address\":\"\",\"city\":\"\",\"county\":\"\",\"zip\":\"\",\"country\":\"AF\",\"email\":\"\",\"phone\":\"\",\"tax_type\":\"\",\"tax_id\":\"\",\"custom_key_one\":\"\",\"custom_value_one\":\"\",\"custom_key_two\":\"\",\"custom_value_two\":\"\"}'),
('webhooks', '{\"user_new\": \"\", \"user_delete\": \"\"}'),
('vcards', '{\"branding\":\"vcard by AltumCode \\ud83d\\udc8e\",\"domains_is_enabled\":true,\"additional_domains_is_enabled\":true,\"main_domain_is_enabled\":true,\"logo_size_limit\":2,\"favicon_size_limit\":2,\"opengraph_size_limit\":2,\"background_size_limit\":2,\"blacklisted_domains\":\"\",\"blacklisted_keywords\":\"\",\"google_safe_browsing_is_enabled\":false,\"google_safe_browsing_api_key\":\"\"}'),
('cookie_consent', '{}'),
('license', '{\"license\":\"gpllicense\",\"type\":\"Extended License\"}'),
('product_info', '{\"version\":\"9.0.0\", \"code\":\"900\"}');

-- SEPARATOR --

CREATE TABLE `projects` (
`project_id` bigint unsigned NOT NULL AUTO_INCREMENT,
`user_id` bigint unsigned DEFAULT NULL,
`name` varchar(128) DEFAULT NULL,
`color` varchar(16) DEFAULT '#000',
`last_datetime` datetime DEFAULT NULL,
`datetime` datetime DEFAULT NULL,
PRIMARY KEY (`project_id`),
UNIQUE KEY `project_id` (`project_id`),
KEY `user_id` (`user_id`),
CONSTRAINT `projects_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `domains` (
`domain_id` bigint unsigned NOT NULL AUTO_INCREMENT,
`vcard_id` bigint unsigned DEFAULT NULL,
`user_id` bigint unsigned DEFAULT NULL,
`scheme` varchar(8) NOT NULL DEFAULT '',
`host` varchar(256) NOT NULL DEFAULT '',
`custom_index_url` varchar(256) DEFAULT NULL,
`custom_not_found_url` varchar(256) DEFAULT NULL,
`type` tinyint DEFAULT '1',
`is_enabled` tinyint DEFAULT '0',
`datetime` datetime DEFAULT NULL,
`last_datetime` datetime DEFAULT NULL,
PRIMARY KEY (`domain_id`),
KEY `user_id` (`user_id`),
KEY `domains_host_index` (`host`),
KEY `domains_type_index` (`type`),
KEY `domains_ibfk_2` (`vcard_id`),
CONSTRAINT `domains_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `vcards` (
`vcard_id` bigint unsigned NOT NULL AUTO_INCREMENT,
`domain_id` bigint unsigned DEFAULT NULL,
`project_id` bigint unsigned DEFAULT NULL,
`user_id` bigint unsigned DEFAULT NULL,
`pixels_ids` text,
`url` varchar(128) DEFAULT NULL,
`name` varchar(256) DEFAULT NULL,
`description` varchar(512) DEFAULT NULL,
`settings` text,
`password` varchar(128) DEFAULT NULL,
`theme` varchar(16) DEFAULT NULL,
`custom_js` text,
`custom_css` text,
`pageviews` bigint unsigned DEFAULT '0',
`is_se_visible` tinyint unsigned DEFAULT '1',
`is_removed_branding` tinyint unsigned DEFAULT '0',
`is_enabled` tinyint unsigned DEFAULT '1',
`last_datetime` datetime DEFAULT NULL,
`datetime` datetime DEFAULT NULL,
PRIMARY KEY (`vcard_id`),
UNIQUE KEY `vcard_id` (`vcard_id`) USING BTREE,
KEY `user_id` (`user_id`),
KEY `domain_id` (`domain_id`),
KEY `project_id` (`project_id`),
CONSTRAINT `vcards_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `vcards_ibfk_2` FOREIGN KEY (`domain_id`) REFERENCES `domains` (`domain_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `vcards_ibfk_3` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

alter table domains  add constraint domains_vcards_vcard_id_fk foreign key (vcard_id) references vcards (vcard_id) on update cascade on delete set null;

-- SEPARATOR --

CREATE TABLE `vcards_blocks` (
`vcard_block_id` bigint unsigned NOT NULL AUTO_INCREMENT,
`vcard_id` bigint unsigned DEFAULT NULL,
`user_id` bigint unsigned DEFAULT NULL,
`type` varchar(32) DEFAULT NULL,
`name` varchar(128) DEFAULT NULL,
`value` varchar(1024) DEFAULT NULL,
`settings` text,
`order` int DEFAULT NULL,
`pageviews` bigint unsigned DEFAULT '0',
`is_enabled` tinyint NOT NULL DEFAULT '1',
`datetime` datetime NOT NULL,
`last_datetime` datetime DEFAULT NULL,
PRIMARY KEY (`vcard_block_id`),
UNIQUE KEY `vcard_block_id` (`vcard_block_id`),
KEY `vcard_id` (`vcard_id`),
KEY `user_id` (`user_id`),
CONSTRAINT `vcards_blocks_ibfk_1` FOREIGN KEY (`vcard_id`) REFERENCES `vcards` (`vcard_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `vcards_blocks_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `statistics` (
`id` bigint unsigned NOT NULL AUTO_INCREMENT,
`vcard_id` bigint unsigned DEFAULT NULL,
`vcard_block_id` bigint unsigned DEFAULT NULL,
`country_code` varchar(8) DEFAULT NULL,
`os_name` varchar(16) DEFAULT NULL,
`city_name` varchar(128) DEFAULT NULL,
`browser_name` varchar(32) DEFAULT NULL,
`referrer_host` varchar(256) DEFAULT NULL,
`referrer_path` varchar(1024) DEFAULT NULL,
`device_type` varchar(16) DEFAULT NULL,
`browser_language` varchar(16) DEFAULT NULL,
`utm_source` varchar(128) DEFAULT NULL,
`utm_medium` varchar(128) DEFAULT NULL,
`utm_campaign` varchar(128) DEFAULT NULL,
`is_unique` tinyint DEFAULT '0',
`datetime` datetime NOT NULL,
PRIMARY KEY (`id`),
KEY `vcard_id` (`vcard_id`),
KEY `datetime` (`datetime`),
KEY `vcard_block_id` (`vcard_block_id`),
CONSTRAINT `statistics_ibfk_1` FOREIGN KEY (`vcard_id`) REFERENCES `vcards` (`vcard_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `statistics_ibfk_4` FOREIGN KEY (`vcard_block_id`) REFERENCES `vcards_blocks` (`vcard_block_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `pixels` (
`pixel_id` bigint unsigned NOT NULL AUTO_INCREMENT,
`user_id` bigint unsigned NOT NULL,
`type` varchar(64) NOT NULL,
`name` varchar(64) NOT NULL,
`pixel` varchar(64) NOT NULL,
`last_datetime` datetime DEFAULT NULL,
`datetime` datetime NOT NULL,
PRIMARY KEY (`pixel_id`),
KEY `user_id` (`user_id`),
CONSTRAINT `pixels_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `payments` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `plan_id` int(11) DEFAULT NULL,
  `base_amount` float DEFAULT NULL,
  `processor` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `frequency` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount_amount` float DEFAULT NULL,
  `payment_id` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subscription_id` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payer_id` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `taxes_ids` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_amount` float DEFAULT NULL,
  `currency` varchar(4) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_proof` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(4) DEFAULT 1,
  `date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

INSERT INTO `vcards` (`vcard_id`, `domain_id`, `project_id`, `user_id`, `pixels_ids`, `url`, `name`, `description`, `settings`, `password`, `theme`, `custom_js`, `custom_css`, `pageviews`, `is_se_visible`, `is_removed_branding`, `is_enabled`, `last_datetime`, `datetime`) VALUES
(1, NULL, NULL, 1, '[]', 'example', 'Example', 'Occaecati ipsum quia est veritatis fugiat qui. Sunt aut aliquid nobis. Occaecati nemo in exercitationem nesciunt provident ut consequatur quia natus accusamus est.', '{\"is_share_button_visible\":1,\"is_download_button_visible\":1,\"background_type\":\"preset\",\"background_color\":\"#ffffff\",\"background_preset\":\"one\",\"background_gradient_one\":\"#ffffff\",\"background_gradient_two\":\"#ffffff\",\"background\":\"\",\"font_family\":\"default\",\"font_size\":16,\"logo\":null,\"favicon\":null,\"opengraph\":null}', NULL, 'new-york', '', '', 1, 1, 0, 1, '2021-10-12 15:47:56', '2021-10-12 15:47:48');
