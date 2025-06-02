-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 11, 2025 at 05:46 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `assignment1`
--

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(10) UNSIGNED DEFAULT NULL,
  `product_name` varchar(20) DEFAULT NULL,
  `unit_price` float(8,2) DEFAULT NULL,
  `unit_quantity` varchar(15) DEFAULT NULL,
  `in_stock` int(10) UNSIGNED DEFAULT NULL,
  `product_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `unit_price`, `unit_quantity`, `in_stock`, `product_image`) VALUES
(1000, 'Fish Fingers', 2.55, '500 gram', 1499, 'images/products/fish_fingers.jpg'),
(1001, 'Fish Fingers', 5.00, '1000 gram', 749, 'images/products/fish_fingers.jpg'),
(1002, 'Hamburger Patties', 2.35, 'Pack 10', 1200, 'images/products/hamb_patties.jpg'),
(1003, 'Shelled Prawns', 6.90, '250 gram', 300, 'images/products/prawns.jpg'),
(1004, 'Tub Ice Cream', 1.80, 'I Litre', 800, 'images/products/ice_cream.jpg'),
(1005, 'Tub Ice Cream', 3.40, '2 Litre', 1200, 'images/products/ice_cream.jpg'),
(2000, 'Panadol', 3.00, 'Pack 24', 2000, 'images/products/panadol.jpg'),
(2001, 'Panadol', 5.50, 'Bottle 50', 998, 'images/products/panadol.jpg'),
(2002, 'Bath Soap', 2.60, 'Pack 6', 500, 'images/products/bath_soap.jpg'),
(2003, 'Garbage Bags Small', 1.50, 'Pack 10', 500, 'images/products/garbage_bag.jpg'),
(2004, 'Garbage Bags Large', 5.00, 'Pack 50', 300, 'images/products/garbage_bag.jpg'),
(2005, 'Washing Powder', 4.00, '1000 gram', 800, 'images/products/washing_powder.jpg'),
(3000, 'Cheddar Cheese', 8.00, '500 gram', 1000, 'images/products/cheddar_cheese.jpg'),
(3001, 'Cheddar Cheese', 15.00, '1000 gram', 1000, 'images/products/cheddar_cheese.jpg'),
(3002, 'T Bone Steak', 7.00, '1000 gram', 200, 'images/products/T_bone.jpg'),
(3003, 'Navel Oranges', 3.99, 'Bag 20', 200, 'images/products/oranges.jpg'),
(3004, 'Bananas', 1.49, 'Kilo', 399, 'images/products/banana.jpg'),
(3005, 'Peaches', 2.99, 'Kilo', 500, 'images/products/peaches.jpg'),
(3006, 'Grapes', 3.50, 'Kilo', 200, 'images/products/grapes.jpg'),
(3007, 'Apples', 1.99, 'Kilo', 500, 'images/products/apples.jpg'),
(4000, 'Earl Grey Tea Bags', 2.49, 'Pack 25', 1200, 'images/products/tea_bags.jpg'),
(4001, 'Earl Grey Tea Bags', 7.25, 'Pack 100', 1200, 'images/products/tea_bags.jpg'),
(4002, 'Earl Grey Tea Bags', 13.00, 'Pack 200', 800, 'images/products/tea_bags.jpg'),
(4003, 'Instant Coffee', 2.89, '200 gram', 500, 'images/products/instant_coffee.jpg'),
(4004, 'Instant Coffee', 5.10, '500 gram', 500, 'images/products/instant_coffee.jpg'),
(4005, 'Chocolate Bar', 2.50, '500 gram', 300, 'images/products/choco_bar.jpg'),
(5000, 'Dry Dog Food', 5.95, '5 kg Pack', 400, 'images/products/dog_food.jpg'),
(5001, 'Dry Dog Food', 1.95, '1 kg Pack', 400, 'images/products/dog_food.jpg'),
(5002, 'Bird Food', 3.99, '500g packet', 200, 'images/products/bird_food.jpg'),
(5003, 'Cat Food', 2.00, '500g tin', 199, 'images/products/cat_food.jpg'),
(5004, 'Fish Food', 3.00, '500g packet', 200, 'images/products/fish_food.jpg'),
(2006, 'Laundry Bleach', 3.55, '2 Litre Bottle', 500, 'images/products/laundry_bleach.jpg');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
