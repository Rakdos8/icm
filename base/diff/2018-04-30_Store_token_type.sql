ALTER TABLE `evemyadmin`.`oauth2_users`
	CHANGE COLUMN `id_character` `id_entity` INT(11) NOT NULL,
	ADD COLUMN `token_type` VARCHAR(255) NOT NULL DEFAULT 'CHARACTER' AFTER `expire_time`;
