SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


CREATE DATABASE IF NOT EXISTS `testwork` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `testwork`;

CREATE TABLE `reminders` (
  `id` int(10) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `content` varchar(300) NOT NULL,
  `unix_time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `reminders`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `reminders`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
