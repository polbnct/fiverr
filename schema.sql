CREATE TABLE `fiverr_clone_users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` text DEFAULT NULL,
  `is_client` tinyint(1) DEFAULT NULL,
  `user_role` varchar(50) NOT NULL DEFAULT 'client',
  `bio_description` text DEFAULT NULL,
  `display_picture` text DEFAULT NULL,
  `contact_number` varchar(255) DEFAULT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(255) NOT NULL,
  PRIMARY KEY (`category_id`),
  UNIQUE KEY `category_name` (`category_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `subcategories` (
  `subcategory_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) DEFAULT NULL,
  `subcategory_name` varchar(255) NOT NULL,
  PRIMARY KEY (`subcategory_id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `subcategories_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `proposals` (
  `proposal_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` text DEFAULT NULL,
  `min_price` int(11) DEFAULT NULL,
  `max_price` int(11) DEFAULT NULL,
  `view_count` int(11) DEFAULT 0,
  `category_id` int(11) DEFAULT NULL,
  `subcategory_id` int(11) DEFAULT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`proposal_id`),
  KEY `user_id` (`user_id`),
  KEY `category_id` (`category_id`),
  KEY `subcategory_id` (`subcategory_id`),
  CONSTRAINT `proposals_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `fiverr_clone_users` (`user_id`),
  CONSTRAINT `proposals_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE SET NULL,
  CONSTRAINT `proposals_ibfk_3` FOREIGN KEY (`subcategory_id`) REFERENCES `subcategories` (`subcategory_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `offers` (
  `offer_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `proposal_id` int(11) DEFAULT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`offer_id`),
  KEY `user_id` (`user_id`),
  KEY `proposal_id` (`proposal_id`),
  CONSTRAINT `offers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `fiverr_clone_users` (`user_id`),
  CONSTRAINT `offers_ibfk_2` FOREIGN KEY (`proposal_id`) REFERENCES `proposals` (`proposal_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;