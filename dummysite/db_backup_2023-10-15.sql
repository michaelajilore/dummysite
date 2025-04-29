-- MySQL dump 
-- Database: company_db

-- Table structure for table `users`
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data for table `users`
INSERT INTO `users` VALUES 
(1, 'admin', 'admin123', 'admin@example.com', '2023-01-01 00:00:00'),
(2, 'john', 'pass123', 'john@example.com', '2023-01-02 10:30:00'),
(3, 'jane', 'jane456', 'jane@example.com', '2023-01-03 14:45:00');

-- Table structure for table `products`
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data for table `products`
INSERT INTO `products` VALUES 
(1, 'Laptop', 'High-performance laptop', 999.99, 15),
(2, 'Smartphone', '128GB smartphone', 599.99, 25),
(3, 'Tablet', '10-inch tablet', 299.99, 10);