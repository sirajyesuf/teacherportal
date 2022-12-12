ALTER TABLE `users` CHANGE `role_type` `role_type` TINYINT(4) NOT NULL DEFAULT '2' COMMENT '1=>admin,2=>teacher';

ALTER TABLE `lessons` CHANGE `user_id` `student_id` INT(11) NOT NULL;

ALTER TABLE `casenotes` CHANGE `user_id` `student_id` INT(11) NOT NULL;

#08-12-2022
ALTER TABLE `tls` CHANGE `date` `date` DATE NULL DEFAULT NULL;