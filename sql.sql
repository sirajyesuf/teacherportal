ALTER TABLE `users` CHANGE `role_type` `role_type` TINYINT(4) NOT NULL DEFAULT '2' COMMENT '1=>admin,2=>teacher';