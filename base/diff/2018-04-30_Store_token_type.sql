ALTER TABLE `evemyadmin`.`oauth2_users`
	ADD COLUMN `token_type` VARCHAR(255) NOT NULL DEFAULT 'CHARACTER' AFTER `expire_time`;
