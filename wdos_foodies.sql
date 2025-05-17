-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 17, 2025 at 05:50 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wdos_foodies`
--

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `status` enum('active','converted','abandoned') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `carts`
--

INSERT INTO `carts` (`cart_id`, `user_id`, `created_at`, `updated_at`, `status`) VALUES
(1, 27, '2025-03-23 19:51:03', NULL, 'active'),
(2, 28, '2025-03-24 15:28:25', NULL, 'active'),
(3, 30, '2025-04-05 17:16:19', NULL, 'active'),
(4, 31, '2025-04-05 17:16:19', NULL, 'active'),
(5, 32, '2025-04-05 17:16:19', NULL, 'active'),
(6, 33, '2025-04-05 17:16:19', NULL, 'active'),
(7, 34, '2025-04-05 17:16:19', NULL, 'active'),
(8, 35, '2025-04-05 17:16:19', NULL, 'active'),
(9, 36, '2025-04-05 17:16:19', NULL, 'active'),
(10, 37, '2025-04-05 17:16:19', NULL, 'active'),
(11, 38, '2025-04-05 17:16:19', NULL, 'active'),
(12, 39, '2025-04-05 17:16:19', NULL, 'active'),
(13, 40, '2025-04-05 17:16:19', NULL, 'active'),
(14, 41, '2025-04-05 17:16:19', NULL, 'active'),
(15, 42, '2025-04-05 17:16:19', NULL, 'active'),
(16, 43, '2025-04-05 17:16:19', NULL, 'active'),
(17, 44, '2025-04-05 17:16:19', NULL, 'active'),
(18, 45, '2025-04-05 17:16:19', NULL, 'active'),
(19, 46, '2025-04-05 17:16:19', NULL, 'active'),
(20, 47, '2025-04-05 17:16:19', NULL, 'active'),
(21, 48, '2025-04-05 17:16:19', NULL, 'active'),
(22, 49, '2025-04-05 17:16:19', NULL, 'active'),
(23, 50, '2025-04-14 00:02:46', NULL, 'active'),
(24, 51, '2025-04-14 11:30:49', NULL, 'active'),
(25, 54, '2025-04-17 21:35:43', NULL, 'active');

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `cart_item_id` int(11) NOT NULL,
  `cart_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart_items`
--

INSERT INTO `cart_items` (`cart_item_id`, `cart_id`, `item_id`, `quantity`, `price`) VALUES
(2, 2, 14, 2, 234.00),
(13, 3, 16, 1, 250.00),
(14, 4, 17, 1, 200.00),
(15, 5, 18, 1, 300.00),
(16, 6, 19, 1, 180.00),
(17, 7, 20, 1, 100.00),
(18, 8, 21, 1, 50.00),
(19, 9, 22, 1, 120.00),
(20, 10, 23, 1, 150.00),
(21, 11, 24, 1, 40.00),
(22, 12, 25, 1, 220.00),
(23, 13, 26, 1, 299.00),
(24, 14, 27, 1, 349.00),
(25, 15, 28, 1, 199.00),
(26, 16, 29, 1, 150.00),
(27, 17, 30, 1, 499.00),
(28, 18, 31, 1, 179.00),
(29, 19, 32, 1, 120.00),
(30, 20, 33, 1, 60.00),
(31, 21, 34, 1, 80.00),
(32, 22, 35, 1, 99.00),
(54, 25, 37, 1, 200.00),
(55, 25, 38, 1, 180.00);

-- --------------------------------------------------------

--
-- Table structure for table `cuisines`
--

CREATE TABLE `cuisines` (
  `cuisine_id` int(11) NOT NULL,
  `cuisine_name` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cuisines`
--

INSERT INTO `cuisines` (`cuisine_id`, `cuisine_name`, `description`, `created_at`, `updated_at`) VALUES
(3, 'Chinese', 'Chinese Item List1                            ', '2025-03-23 00:46:44', '2025-04-05 13:54:41'),
(4, 'Mexican', 'Mexican Food\r\n', '2025-03-23 12:57:12', NULL),
(7, 'Indian', 'A diverse range of flavors from the Indian subcontinent.', '2025-04-05 17:15:58', NULL),
(8, 'Italian', 'Classic dishes from Italy, known for pasta and pizza.', '2025-04-05 17:15:58', NULL),
(9, 'Japanese', 'Sushi, ramen, and other delicacies from Japan.', '2025-04-05 17:15:58', NULL),
(10, 'Thai', 'Spicy and aromatic dishes from Thailand.', '2025-04-05 17:15:58', NULL),
(11, 'American', 'Burgers, fries, and other comfort foods from the USA.', '2025-04-05 17:15:58', NULL),
(12, 'Mediterranean', 'Healthy and flavorful dishes from the Mediterranean region.', '2025-04-05 17:15:58', NULL),
(13, 'Korean', 'Kimchi, bulgogi, and other spicy Korean specialties.', '2025-04-05 17:15:58', NULL),
(14, 'Vietnamese', 'Pho, spring rolls, and other fresh Vietnamese dishes.', '2025-04-05 17:15:58', NULL),
(15, 'French', 'Elegant and rich dishes from France.', '2025-04-05 17:15:58', NULL),
(16, 'Spanish', 'Tapas, paella, and other vibrant Spanish flavors.', '2025-04-05 17:15:58', NULL),
(17, 'German', 'Sausages, pretzels, and hearty German meals.', '2025-04-05 17:15:58', NULL),
(18, 'Brazilian', 'Churrasco, feijoada, and other Brazilian delights.', '2025-04-05 17:15:58', NULL),
(19, 'Ethiopian', 'Injera, wat, and other unique Ethiopian dishes.', '2025-04-05 17:15:58', NULL),
(20, 'Moroccan', 'Tagines, couscous, and spiced Moroccan cuisine.', '2025-04-05 17:15:58', NULL),
(21, 'Turkish', 'Kebabs, baklava, and other Turkish treats.', '2025-04-05 17:15:58', NULL),
(22, 'Russian', 'Borscht, pelmeni, and other hearty Russian fare.', '2025-04-05 17:15:58', NULL),
(23, 'Greek', 'Gyros, moussaka, and other Mediterranean Greek dishes.', '2025-04-05 17:15:58', NULL),
(24, 'Lebanese', 'Hummus, falafel, and other Middle Eastern Lebanese specialties.', '2025-04-05 17:15:58', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `menu_items`
--

CREATE TABLE `menu_items` (
  `item_id` int(11) NOT NULL,
  `restaurant_id` int(11) NOT NULL,
  `cuisine_id` int(11) DEFAULT NULL,
  `item_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `tags` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`tags`)),
  `image_url` varchar(255) DEFAULT NULL,
  `is_available` int(11) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu_items`
--

INSERT INTO `menu_items` (`item_id`, `restaurant_id`, `cuisine_id`, `item_name`, `description`, `price`, `tags`, `image_url`, `is_available`, `created_at`, `updated_at`) VALUES
(13, 4, 4, 'Mexican Burgur', 'Mexican Burgur', 399.00, '[\"asian\",\"burgur\",\"mexican\"]', 'uploads/burger.png', 1, '2025-03-23 13:00:52', '2025-04-05 21:10:57'),
(14, 3, 4, 'test', 'testing Purpose', 234.00, '[\"dinner\",\"gujarati-thali\"]', 'uploads/OIP.jpeg', 1, '2025-03-23 15:49:41', '2025-04-12 17:24:47'),
(15, 4, 4, 'test', 'dgh', 899.00, '[\"asian\",\"biryani\"]', 'uploads/images.png', 1, '2025-04-04 23:59:55', NULL),
(16, 4, 7, 'Butter Nan', 'Butter Nan with extra Butter', 250.00, '[\"spicy\",\"dinner\",\"north-indian\"]', 'uploads/item1.png', 1, '2025-04-05 17:16:12', '2025-04-05 18:15:47'),
(17, 7, 7, 'Paneer Tikka', 'Grilled cottage cheese with spices', 200.00, '[\"vegetarian\",\"appetizer\",\"north-indian\"]', 'uploads/item2.png', 1, '2025-04-05 17:16:12', NULL),
(18, 8, 7, 'Samosa', 'Bihari Spicy Samosa ', 300.00, '[\"spicy\",\"dinner\",\"north-indian\"]', 'uploads/item3.png', 1, '2025-04-05 17:16:12', '2025-04-05 18:22:47'),
(19, 9, 7, 'Veg Biryani', 'Fragrant rice with spicy masala', 180.00, '[\"biryani\",\"lunch\",\"north-indian\"]', 'uploads/item4.png', 1, '2025-04-05 17:16:12', '2025-04-05 18:24:22'),
(20, 10, 7, 'Masala Dosa', 'Crispy crepe with potato filling', 100.00, '[\"vegetarian\",\"breakfast\"]', 'uploads/item5.png', 1, '2025-04-05 17:16:12', NULL),
(21, 11, 7, 'Pani Puri', 'Tangy street food snack', 50.00, '[\"fresh\",\"snack\",\"north-indian\"]', 'uploads/item6.png', 1, '2025-04-05 17:16:12', NULL),
(22, 12, 7, 'Chole Bhature', 'Spicy chickpeas with fried bread', 120.00, '[\"vegetarian\",\"lunch\",\"north-indian\"]', 'uploads/item7.png', 1, '2025-04-05 17:16:12', NULL),
(23, 13, 7, 'Dal Makhani', 'Creamy lentil curry', 150.00, '[\"vegetarian\",\"dinner\",\"north-indian\"]', 'uploads/item8.png', 1, '2025-04-05 17:16:12', NULL),
(24, 14, 7, 'Garlic Naan', 'Soft bread with garlic', 40.00, '[\"vegetarian\",\"dinner\"]', 'uploads/item9.png', 1, '2025-04-05 17:16:12', NULL),
(25, 15, 7, 'Aloopuri', 'Spiced minced meat skewers', 220.00, '[\"spicy\",\"appetizer\",\"north-indian\"]', 'uploads/item10.png', 1, '2025-04-05 17:16:12', '2025-04-05 18:17:16'),
(26, 16, 8, 'Margherita Pizza', 'Classic cheese and tomato pizza', 299.00, '[\"italian\",\"pizza\",\"vegetarian\"]', 'uploads/item11.png', 1, '2025-04-05 17:16:12', NULL),
(27, 17, 8, 'Pepperoni Pizza', 'Pizza with pepperoni slices', 349.00, '[\"italian\",\"pizza\"]', 'uploads/item12.png', 1, '2025-04-05 17:16:12', NULL),
(28, 18, 11, 'Big Mac', 'Iconic burger with special sauce', 199.00, '[\"american\",\"burgur\"]', 'uploads/item13.png', 1, '2025-04-05 17:16:12', NULL),
(29, 19, 11, 'Veggie Delite', 'Fresh vegetable sandwich', 150.00, '[\"american\",\"sandwich\",\"vegetarian\"]', 'uploads/item14.png', 1, '2025-04-05 17:16:12', NULL),
(30, 20, 11, 'Fried French fries', 'Crispy fried French fries', 499.00, '[\"american\",\"lunch\"]', 'uploads/item15.png', 1, '2025-04-05 17:16:12', '2025-04-05 18:18:37'),
(31, 21, 11, 'Whopper', 'Flame-grilled burger with toppings', 179.00, '[\"american\",\"burgur\"]', 'uploads/item16.png', 1, '2025-04-05 17:16:12', NULL),
(32, 22, 11, 'Cappuccino', 'Espresso with steamed milk', 120.00, '[\"american\",\"breakfast\"]', 'uploads/item17.png', 1, '2025-04-05 17:16:12', NULL),
(33, 23, 11, 'Glazed Donut', 'Sweet ring-shaped pastry', 60.00, '[\"american\",\"dessert\"]', 'uploads/item18.png', 1, '2025-04-05 17:16:12', NULL),
(34, 24, 11, 'Chocolate Ice Cream', 'Rich chocolate flavored ice cream', 80.00, '[\"american\",\"dessert\",\"ice-cream\"]', 'uploads/item19.png', 1, '2025-04-05 17:16:12', NULL),
(35, 25, 4, 'Crunchy Taco', 'Crispy taco with seasoned beef', 99.00, '[\"mexican\",\"lunch\"]', 'uploads/item20.png', 1, '2025-04-05 17:16:12', NULL),
(36, 1, 7, 'Paneer Butter Masala', 'Creamy cottage cheese curry', 250.00, '[\"dinner\",\"north-indian\",\"vegetarian\"]', 'uploads/item36.png', 1, '2025-04-06 00:00:00', '2025-04-13 17:01:46'),
(37, 1, 7, 'Vegetable Biryani', 'Fragrant rice with mixed vegetables', 200.00, '[\"vegetarian\",\"lunch\",\"north-indian\"]', 'uploads/item37.png', 1, '2025-04-06 00:00:00', NULL),
(38, 3, 3, 'Vegetable Fried Rice', 'Stir-fried rice with vegetables', 180.00, '[\"vegetarian\",\"lunch\",\"chinese\"]', 'uploads/item38.png', 1, '2025-04-06 00:00:00', NULL),
(39, 3, 3, 'Gobi Manchurian', 'Crispy cauliflower in spicy sauce', 160.00, '[\"vegetarian\",\"appetizer\",\"indo-chinese\"]', 'uploads/item39.png', 1, '2025-04-06 00:00:00', NULL),
(40, 4, 11, 'Veggie Burger', 'Grilled vegetable patty with lettuce and tomato', 150.00, '[\"vegetarian\",\"lunch\",\"american\"]', 'uploads/item40.png', 1, '2025-04-06 00:00:00', NULL),
(41, 4, 11, 'Cheese Fries', 'Crispy fries topped with melted cheese', 120.00, '[\"vegetarian\",\"snack\",\"american\"]', 'uploads/item41.png', 1, '2025-04-06 00:00:00', NULL),
(42, 6, 7, 'Tandoori Roti', 'Whole wheat bread baked in tandoor', 30.00, '[\"vegetarian\",\"dinner\",\"north-indian\"]', 'uploads/item42.png', 1, '2025-04-06 00:00:00', NULL),
(43, 6, 7, 'Vegetable Pulao', 'Rice cooked with mixed vegetables and spices', 180.00, '[\"vegetarian\",\"lunch\",\"north-indian\"]', 'uploads/item43.png', 1, '2025-04-06 00:00:00', NULL),
(44, 7, 7, 'Dal Tadka', 'Yellow lentils tempered with spices', 150.00, '[\"vegetarian\",\"dinner\",\"north-indian\"]', 'uploads/item44.png', 1, '2025-04-06 00:00:00', NULL),
(45, 7, 7, 'Aloo Gobi', 'Potatoes and cauliflower in masala', 180.00, '[\"vegetarian\",\"lunch\",\"north-indian\"]', 'uploads/item45.png', 1, '2025-04-06 00:00:00', NULL),
(46, 8, 7, 'Chana Masala', 'Spicy chickpea curry', 160.00, '[\"vegetarian\",\"dinner\",\"north-indian\"]', 'uploads/item46.png', 1, '2025-04-06 00:00:00', NULL),
(47, 8, 7, 'Palak Paneer', 'Cottage cheese in spinach gravy', 220.00, '[\"vegetarian\",\"dinner\",\"north-indian\"]', 'uploads/item47.png', 1, '2025-04-06 00:00:00', NULL),
(48, 9, 7, 'Vegetable Dum Biryani', 'Layered rice with vegetables and spices', 250.00, '[\"vegetarian\",\"lunch\",\"north-indian\"]', 'uploads/item48.png', 1, '2025-04-06 00:00:00', NULL),
(49, 9, 7, 'Raita', 'Yogurt with cucumber and spices', 50.00, '[\"vegetarian\",\"side\",\"north-indian\"]', 'uploads/item49.png', 1, '2025-04-06 00:00:00', NULL),
(50, 10, 7, 'Idli Sambar', 'Steamed rice cakes with lentil soup', 80.00, '[\"vegetarian\",\"breakfast\",\"south-indian\"]', 'uploads/item50.png', 1, '2025-04-06 00:00:00', NULL),
(51, 10, 7, 'Uttapam', 'Thick pancake with vegetable toppings', 100.00, '[\"vegetarian\",\"lunch\",\"south-indian\"]', 'uploads/item51.png', 1, '2025-04-06 00:00:00', NULL),
(52, 11, 7, 'Bhel Puri', 'Puffed rice with vegetables and chutney', 60.00, '[\"vegetarian\",\"snack\",\"north-indian\"]', 'uploads/item52.png', 1, '2025-04-06 00:00:00', NULL),
(53, 11, 7, 'Sev Puri', 'Crispy puris with potatoes and chutney', 70.00, '[\"vegetarian\",\"snack\",\"north-indian\"]', 'uploads/item53.png', 1, '2025-04-06 00:00:00', NULL),
(54, 12, 7, 'Vegetable Korma', 'Mixed vegetables in creamy sauce', 200.00, '[\"vegetarian\",\"dinner\",\"north-indian\"]', 'uploads/item54.png', 1, '2025-04-06 00:00:00', NULL),
(55, 12, 7, 'Jeera Rice', 'Rice flavored with cumin seeds', 120.00, '[\"vegetarian\",\"lunch\",\"north-indian\"]', 'uploads/item55.png', 1, '2025-04-06 00:00:00', NULL),
(56, 13, 7, 'Aloo Paratha', 'Stuffed potato flatbread', 80.00, '[\"vegetarian\",\"breakfast\",\"north-indian\"]', 'uploads/item56.png', 1, '2025-04-06 00:00:00', NULL),
(57, 13, 7, 'Mixed Vegetable Curry', 'Assorted vegetables in spicy gravy', 180.00, '[\"vegetarian\",\"dinner\",\"north-indian\"]', 'uploads/item57.png', 1, '2025-04-06 00:00:00', NULL),
(58, 14, 7, 'Butter Naan', 'Soft bread with butter', 40.00, '[\"vegetarian\",\"dinner\",\"north-indian\"]', 'uploads/item58.png', 1, '2025-04-06 00:00:00', NULL),
(59, 14, 7, 'Paneer Kulcha', 'Stuffed cottage cheese bread', 60.00, '[\"vegetarian\",\"lunch\",\"north-indian\"]', 'uploads/item59.png', 1, '2025-04-06 00:00:00', NULL),
(60, 15, 7, 'Vegetable Seekh Kebab', 'Spiced vegetable skewers', 180.00, '[\"vegetarian\",\"appetizer\",\"north-indian\"]', 'uploads/item60.png', 1, '2025-04-06 00:00:00', NULL),
(61, 15, 7, 'Paneer Tikka', 'Grilled cottage cheese with spices', 220.00, '[\"vegetarian\",\"appetizer\",\"north-indian\"]', 'uploads/item61.png', 1, '2025-04-06 00:00:00', NULL),
(62, 16, 8, 'Vegetarian Pizza', 'Pizza with mixed vegetables', 299.00, '[\"vegetarian\",\"pizza\",\"italian\"]', 'uploads/item62.png', 1, '2025-04-06 00:00:00', NULL),
(63, 16, 8, 'Garlic Bread', 'Toasted bread with garlic butter', 99.00, '[\"vegetarian\",\"appetizer\",\"italian\"]', 'uploads/item63.png', 1, '2025-04-06 00:00:00', NULL),
(64, 17, 8, 'Margherita Pizza', 'Classic cheese and tomato pizza', 279.00, '[\"vegetarian\",\"pizza\",\"italian\"]', 'uploads/item64.png', 1, '2025-04-06 00:00:00', NULL),
(65, 17, 8, 'Cheese Sticks', 'Melted cheese on breadsticks', 129.00, '[\"vegetarian\",\"appetizer\",\"italian\"]', 'uploads/item65.png', 1, '2025-04-06 00:00:00', NULL),
(66, 18, 11, 'McVeggie Burger', 'Vegetarian patty with lettuce and mayo', 149.00, '[\"vegetarian\",\"lunch\",\"american\"]', 'uploads/item66.png', 1, '2025-04-06 00:00:00', NULL),
(67, 18, 11, 'French Fries', 'Crispy golden fries', 99.00, '[\"vegetarian\",\"snack\",\"american\"]', 'uploads/item67.png', 1, '2025-04-06 00:00:00', NULL),
(68, 19, 11, 'Veggie Delite Sub', 'Fresh vegetables with choice of bread', 199.00, '[\"vegetarian\",\"lunch\",\"american\"]', 'uploads/item68.png', 1, '2025-04-06 00:00:00', NULL),
(69, 19, 11, 'Garden Salad', 'Mixed greens with dressing', 149.00, '[\"vegetarian\",\"salad\",\"american\"]', 'uploads/item69.png', 1, '2025-04-06 00:00:00', NULL),
(70, 20, 11, 'Veg Strips', 'Crispy vegetable strips', 159.00, '[\"vegetarian\",\"snack\",\"american\"]', 'uploads/item70.png', 1, '2025-04-06 00:00:00', NULL),
(71, 20, 11, 'Corn on the Cob', 'Sweet corn with butter', 79.00, '[\"vegetarian\",\"side\",\"american\"]', 'uploads/item71.png', 1, '2025-04-06 00:00:00', NULL),
(72, 21, 11, 'Paneer King Burger', 'Grilled paneer patty with spices', 179.00, '[\"vegetarian\",\"lunch\",\"american\"]', 'uploads/item72.png', 1, '2025-04-06 00:00:00', NULL),
(73, 21, 11, 'Onion Rings', 'Crispy fried onion rings', 99.00, '[\"vegetarian\",\"snack\",\"american\"]', 'uploads/item73.png', 1, '2025-04-06 00:00:00', NULL),
(74, 22, 11, 'Veg Sandwich', 'Vegetable sandwich with cheese', 199.00, '[\"vegetarian\",\"breakfast\",\"american\"]', 'uploads/item74.png', 1, '2025-04-06 00:00:00', NULL),
(75, 22, 12, 'Croissant', 'Buttery flaky pastry', 129.00, '[\"breakfast\",\"vegetarian\"]', 'uploads/item75.png', 1, '2025-04-06 00:00:00', '2025-04-14 08:59:41'),
(76, 23, 9, 'Glazed Donut', 'Classic glazed ring donut', 59.00, '[\"dessert\",\"vegetarian\"]', 'uploads/item76.png', 1, '2025-04-06 00:00:00', '2025-04-14 08:57:17'),
(77, 23, 10, 'Chocolate Muffin', 'Moist chocolate muffin', 79.00, '[\"dessert\",\"vegetarian\"]', 'uploads/item77.png', 1, '2025-04-06 00:00:00', '2025-04-14 08:59:08'),
(78, 24, 11, 'Vanilla Ice Cream', 'Classic vanilla flavor', 99.00, '[\"vegetarian\",\"dessert\",\"ice-cream\"]', 'uploads/item78.png', 1, '2025-04-06 00:00:00', NULL),
(79, 24, 11, 'Strawberry Ice Cream', 'Fresh strawberry flavor', 109.00, '[\"vegetarian\",\"dessert\",\"ice-cream\"]', 'uploads/item79.png', 1, '2025-04-06 00:00:00', NULL),
(80, 25, 4, 'Bean Burrito', 'Soft tortilla with beans and cheese', 149.00, '[\"vegetarian\",\"lunch\",\"mexican\"]', 'uploads/item80.png', 1, '2025-04-06 00:00:00', NULL),
(81, 25, 4, 'Cheese Quesadilla', 'Grilled tortilla with melted cheese', 129.00, '[\"vegetarian\",\"snack\",\"mexican\"]', 'uploads/item81.png', 1, '2025-04-06 00:00:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` varchar(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `restaurant_id` int(11) NOT NULL,
  `order_date` datetime DEFAULT current_timestamp(),
  `delivery_address` varchar(255) DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','confirmed','preparing','out_for_delivery','delivered','cancelled') NOT NULL DEFAULT 'pending',
  `notes` varchar(255) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `restaurant_id`, `order_date`, `delivery_address`, `total_amount`, `status`, `notes`, `updated_at`) VALUES
('#ORD-001', 27, 3, '2025-03-10 14:30:00', '789 Oak St', 300.00, 'delivered', 'Call before delivery', '2025-04-14 01:46:00'),
('#ORD-003', 27, 1, '2025-03-12 12:45:00', '123 Pine St', 468.00, 'delivered', NULL, '2025-04-05 01:00:50'),
('#ORD-005', 21, 3, '2025-03-14 09:30:00', '321 Birch St', 597.00, 'delivered', NULL, '2025-03-30 16:47:08'),
('#ORD-006', 27, 3, '2025-03-15 16:20:00', '654 Oak St', 234.00, 'cancelled', 'Wrong items ordered', '2025-03-29 20:50:00'),
('#ORD-007', 27, 1, '2025-03-31 12:05:23', 'A-20 , G-4 , Om Township -3 , Pasodara ,Surat', 793.18, 'delivered', NULL, '2025-04-14 01:46:06'),
('#ORD-008', 27, 1, '2025-03-31 14:22:16', 'A-20 , G-4 , Om Township -3 , Pasodara ,Surat', 283.06, 'delivered', NULL, '2025-04-14 09:44:51'),
('#ORD-009', 27, 1, '2025-03-31 15:08:10', 'A-20 , G-4 , Om Township -3 , Pasodara ,Surat', 283.06, 'out_for_delivery', NULL, '2025-04-04 22:16:17'),
('#ORD-010', 27, 1, '2025-03-31 19:55:53', 'A-20 , G-4 , Om Township -3 , Pasodara ,Surat', 1152.88, 'out_for_delivery', NULL, '2025-04-04 22:16:07'),
('#ORD-011', 27, 3, '2025-04-04 20:19:53', 'A-20 , G-4 , Om Township -3 , Pasodara ,Surat', 1152.88, 'delivered', NULL, '2025-04-14 09:44:57'),
('#ORD-012', 30, 6, '2025-04-01 12:00:00', '123, Gandhi Road, Ahmedabad', 250.00, 'delivered', NULL, NULL),
('#ORD-013', 31, 7, '2025-04-02 13:00:00', '456, Shah Street, Surat', 200.00, 'delivered', 'No onions', NULL),
('#ORD-014', 32, 8, '2025-04-03 14:00:00', '789, Desai Lane, Vadodara', 300.00, 'cancelled', 'Changed mind', NULL),
('#ORD-015', 33, 9, '2025-04-04 15:00:00', '101, Mehta Marg, Rajkot', 180.00, 'pending', NULL, NULL),
('#ORD-016', 34, 10, '2025-04-05 16:00:00', '202, Joshi Colony, Bhavnagar', 100.00, 'cancelled', 'Leave at door', '2025-04-14 09:44:11'),
('#ORD-017', 35, 11, '2025-04-06 17:00:00', '303, Trivedi Towers, Jamnagar', 50.00, 'preparing', NULL, NULL),
('#ORD-018', 36, 12, '2025-04-07 18:00:00', '404, Pandya Path, Junagadh', 120.00, 'out_for_delivery', NULL, NULL),
('#ORD-019', 37, 13, '2025-04-08 19:00:00', '505, Gandhi Gardens, Gandhinagar', 150.00, 'delivered', NULL, NULL),
('#ORD-020', 38, 14, '2025-04-09 20:00:00', '606, Modi Mansion, Anand', 40.00, 'delivered', NULL, NULL),
('#ORD-021', 39, 15, '2025-04-10 21:00:00', '707, Chauhan Chowk, Bharuch', 220.00, 'cancelled', 'Late delivery', NULL),
('#ORD-022', 40, 16, '2025-04-11 22:00:00', '808, Parmar Park, Nadiad', 299.00, 'pending', NULL, NULL),
('#ORD-023', 41, 17, '2025-04-12 23:00:00', '909, Solanki Square, Mehsana', 349.00, 'confirmed', NULL, NULL),
('#ORD-024', 42, 18, '2025-04-12 10:00:00', '1010, Vaghela Villa, Porbandar', 199.00, 'delivered', NULL, '2025-04-17 09:19:25'),
('#ORD-025', 43, 19, '2025-04-01 11:00:00', '1111, Rathod Residency, Navsari', 150.00, 'out_for_delivery', NULL, '2025-04-14 09:35:39'),
('#ORD-026', 44, 20, '2025-04-01 12:00:00', '1212, Gohil Gardens, Vapi', 499.00, 'delivered', NULL, '2025-04-14 09:35:32'),
('#ORD-027', 45, 21, '2025-04-03 13:00:00', '1313, Jadeja Junction, Morbi', 179.00, 'delivered', NULL, '2025-04-14 09:35:59'),
('#ORD-028', 46, 22, '2025-04-08 14:00:00', '1414, Chavda Circle, Surendranagar', 120.00, 'cancelled', 'Wrong order', '2025-04-13 09:36:21'),
('#ORD-029', 47, 23, '2025-04-09 15:00:00', '1515, Zala Zone, Godhra', 60.00, 'pending', NULL, '2025-04-13 09:36:32'),
('#ORD-030', 48, 24, '2025-04-06 16:00:00', '1616, Makwana Market, Palanpur', 80.00, 'confirmed', NULL, '2025-04-13 09:36:40'),
('#ORD-031', 49, 25, '2025-04-08 17:00:00', '1717, Rathwa Road, Patan', 99.00, 'preparing', NULL, '2025-04-14 10:10:54'),
('#ORD-032', 27, 22, '2025-04-05 15:11:06', 'A-20 , G-4 , Om Township -3 , Pasodara ,Surat', 942.51, 'delivered', NULL, '2025-04-14 09:45:02'),
('#ORD-035', 27, 3, '2025-04-12 14:54:19', 'A-20 , G-4 , Om Township -3 , Pasodara ,Surat', 283.06, 'delivered', NULL, '2025-04-14 09:45:15'),
('#ORD-036', 50, 1, '2025-04-13 20:33:32', 'Om 3', 300.50, 'delivered', NULL, '2025-04-13 00:05:12'),
('#ORD-037', 27, 4, '2025-04-14 05:30:57', 'A-20 , G-4 , Om Township -3 , Pasodara ,Surat', 791.00, 'pending', NULL, NULL),
('#ORD-038', 27, 6, '2025-04-14 05:34:41', 'A-20 , G-4 , Om Township -3 , Pasodara ,Surat', 485.80, 'pending', NULL, NULL),
('#ORD-039', 27, 3, '2025-04-14 05:35:58', 'A-20 , G-4 , Om Township -3 , Pasodara ,Surat', 283.06, 'confirmed', NULL, '2025-04-14 09:44:05'),
('#ORD-040', 27, 6, '2025-04-14 05:56:07', 'A-20 , G-4 , Om Township -3 , Pasodara ,Surat', 60.70, 'out_for_delivery', NULL, '2025-04-14 09:43:55'),
('#ORD-041', 27, 1, '2025-04-14 05:57:58', 'A-20 , G-4 , Om Township -3 , Pasodara ,Surat', 246.00, 'pending', NULL, NULL),
('#ORD-042', 27, 1, '2025-04-13 20:33:32', 'A-20 , G-4 , Om Township -3 , Pasodara ,Surat', 300.50, 'confirmed', NULL, '2025-04-14 09:56:16'),
('#ORD-043', 27, 1, '2025-04-14 06:46:01', 'A-20 , G-4 , Om Township -3 , Pasodara ,Surat', 300.50, 'pending', NULL, NULL),
('#ORD-044', 27, 3, '2025-04-14 06:55:40', 'A-20 , G-4 , Om Township -3 , Pasodara ,Surat', 283.06, 'pending', NULL, NULL),
('#ORD-045', 27, 3, '2025-04-14 06:58:11', 'A-20 , G-4 , Om Township -3 , Pasodara ,Surat', 224.20, 'delivered', NULL, '2025-04-14 10:37:50'),
('#ORD-046', 51, 3, '2025-04-14 08:01:47', 'surat', 756.12, 'pending', NULL, NULL),
('#ORD-047', 27, 8, '2025-04-17 05:45:05', 'A-20 , G-4 , Om Township -3 , Pasodara ,Surat', 376.80, 'pending', NULL, NULL);

--
-- Triggers `orders`
--
DELIMITER $$
CREATE TRIGGER `before_order_insert` BEFORE INSERT ON `orders` FOR EACH ROW BEGIN
    DECLARE max_id INT DEFAULT 0;
    DECLARE next_id INT;

    -- Extract the maximum numeric part of the order_id
    SELECT CAST(SUBSTRING(order_id, 6) AS UNSIGNED) INTO max_id
    FROM orders
    WHERE order_id REGEXP '^#ORD-[0-9]+$'
    ORDER BY CAST(SUBSTRING(order_id, 6) AS UNSIGNED) DESC
    LIMIT 1;

    -- Increment the numeric part
    SET next_id = max_id + 1;

    -- Format the order_id as '#ORD-001', '#ORD-002', etc.
    SET NEW.order_id = CONCAT('#ORD-', LPAD(next_id, 3, '0'));
    
    -- Set session variable to capture the generated ID [[6]]
    SET @last_order_id = NEW.order_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` varchar(20) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `order_id`, `item_id`, `quantity`, `price`) VALUES
(3, '#ORD-003', 14, 2, 234.00),
(5, '#ORD-005', 14, 3, 234.00),
(6, '#ORD-005', 13, 1, 399.00),
(7, '#ORD-006', 14, 1, 234.00),
(8, '#ORD-007', 14, 3, 234.00),
(9, '#ORD-008', 14, 1, 234.00),
(10, '#ORD-009', 14, 1, 234.00),
(11, '#ORD-010', 14, 1, 234.00),
(12, '#ORD-010', 13, 2, 399.00),
(13, '#ORD-011', 13, 2, 399.00),
(14, '#ORD-011', 14, 1, 234.00),
(16, '#ORD-012', 16, 1, 250.00),
(17, '#ORD-013', 17, 1, 200.00),
(18, '#ORD-014', 18, 1, 300.00),
(19, '#ORD-015', 19, 1, 180.00),
(20, '#ORD-016', 20, 1, 100.00),
(21, '#ORD-017', 21, 1, 50.00),
(22, '#ORD-018', 22, 1, 120.00),
(23, '#ORD-019', 23, 1, 150.00),
(24, '#ORD-020', 24, 1, 40.00),
(25, '#ORD-021', 25, 1, 220.00),
(26, '#ORD-022', 26, 1, 299.00),
(27, '#ORD-023', 27, 1, 349.00),
(28, '#ORD-024', 28, 1, 199.00),
(29, '#ORD-025', 29, 1, 150.00),
(30, '#ORD-026', 30, 1, 499.00),
(31, '#ORD-027', 31, 1, 179.00),
(32, '#ORD-028', 32, 1, 120.00),
(33, '#ORD-029', 33, 1, 60.00),
(34, '#ORD-030', 34, 1, 80.00),
(35, '#ORD-031', 35, 1, 99.00),
(36, '#ORD-032', 32, 1, 120.00),
(37, '#ORD-032', 30, 1, 499.00),
(38, '#ORD-032', 25, 1, 220.00),
(41, '#ORD-035', 14, 1, 234.00),
(42, '#ORD-036', 36, 1, 250.00),
(43, '#ORD-037', 16, 2, 250.00),
(44, '#ORD-037', 37, 1, 200.00),
(45, '#ORD-038', 42, 4, 30.00),
(46, '#ORD-038', 18, 1, 300.00),
(47, '#ORD-039', 14, 1, 234.00),
(48, '#ORD-040', 42, 1, 30.00),
(49, '#ORD-041', 37, 1, 200.00),
(50, '#ORD-042', 36, 1, 250.00),
(51, '#ORD-043', 36, 1, 250.00),
(52, '#ORD-044', 14, 1, 234.00),
(53, '#ORD-045', 38, 1, 180.00),
(54, '#ORD-046', 14, 2, 234.00),
(55, '#ORD-046', 37, 1, 200.00),
(56, '#ORD-047', 46, 2, 160.00);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `order_id` varchar(20) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` enum('card','cash','online','wallet') NOT NULL,
  `transaction_id` varchar(100) DEFAULT NULL,
  `payment_date` datetime DEFAULT current_timestamp(),
  `status` enum('successful','failed','pending','refunded') NOT NULL DEFAULT 'successful'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`payment_id`, `order_id`, `amount`, `payment_method`, `transaction_id`, `payment_date`, `status`) VALUES
(2, '#ORD-003', 468.00, 'online', 'txn123456789', '2025-03-12 12:50:00', 'refunded'),
(4, '#ORD-005', 597.00, 'wallet', 'txn567890123', '2025-03-14 09:35:00', 'refunded'),
(5, '#ORD-006', 234.00, 'cash', NULL, '2025-03-15 16:25:00', 'refunded'),
(6, '#ORD-007', 793.18, 'cash', NULL, '2025-03-31 15:35:23', 'refunded'),
(7, '#ORD-008', 283.06, 'cash', NULL, '2025-03-31 17:52:16', 'refunded'),
(8, '#ORD-009', 283.06, 'cash', NULL, '2025-03-31 18:38:10', 'pending'),
(9, '#ORD-010', 1152.88, 'cash', NULL, '2025-03-31 23:25:53', 'pending'),
(10, '#ORD-011', 1152.88, 'cash', NULL, '2025-04-04 23:49:53', 'pending'),
(12, '#ORD-012', 250.00, 'card', 'txn1001', '2025-04-01 12:05:00', 'successful'),
(13, '#ORD-013', 200.00, 'cash', NULL, '2025-04-02 13:05:00', 'successful'),
(14, '#ORD-014', 300.00, 'online', 'txn1002', '2025-04-03 14:05:00', 'pending'),
(15, '#ORD-015', 180.00, 'wallet', 'txn1003', '2025-04-04 15:05:00', 'pending'),
(16, '#ORD-016', 100.00, 'card', 'txn1004', '2025-04-05 16:05:00', 'successful'),
(17, '#ORD-017', 50.00, 'cash', NULL, '2025-04-06 17:05:00', 'successful'),
(18, '#ORD-018', 120.00, 'online', 'txn1005', '2025-04-07 18:05:00', 'successful'),
(19, '#ORD-019', 150.00, 'wallet', 'txn1006', '2025-04-08 19:05:00', 'successful'),
(20, '#ORD-020', 40.00, 'card', 'txn1007', '2025-04-09 20:05:00', 'successful'),
(21, '#ORD-021', 220.00, 'cash', NULL, '2025-04-10 21:05:00', 'pending'),
(22, '#ORD-022', 299.00, 'online', 'txn1008', '2025-04-11 22:05:00', 'pending'),
(23, '#ORD-023', 349.00, 'wallet', 'txn1009', '2025-04-12 23:05:00', 'successful'),
(24, '#ORD-024', 199.00, 'card', 'txn1010', '2025-04-13 10:05:00', 'successful'),
(25, '#ORD-025', 150.00, 'cash', NULL, '2025-04-13 10:05:00', 'successful'),
(26, '#ORD-026', 499.00, 'online', 'txn1011', '2025-04-13 10:05:00', 'successful'),
(27, '#ORD-027', 179.00, 'wallet', 'txn1012', '2025-04-13 10:05:00', 'successful'),
(28, '#ORD-028', 120.00, 'card', 'txn1013', '2025-04-13 10:05:00', 'pending'),
(29, '#ORD-029', 60.00, 'cash', NULL, '2025-04-13 10:05:00', 'pending'),
(30, '#ORD-030', 80.00, 'online', 'txn1014', '2025-04-13 10:05:00', 'successful'),
(31, '#ORD-031', 99.00, 'wallet', 'txn1015', '2025-04-13 10:05:00', 'successful'),
(32, '#ORD-032', 942.51, 'card', 'pay_QFO60mx8TVADm1', '2025-04-05 18:41:06', 'successful'),
(35, '#ORD-035', 283.06, 'cash', NULL, '2025-04-12 18:24:19', 'pending'),
(36, '#ORD-036', 300.50, 'card', 'pay_QIds326IWISznH', '2025-04-14 00:03:32', 'successful'),
(37, '#ORD-037', 791.00, 'cash', NULL, '2025-04-14 09:00:57', 'pending'),
(38, '#ORD-038', 485.80, 'card', 'pay_QIn5odQJ06NH8t', '2025-04-14 09:04:41', 'successful'),
(39, '#ORD-039', 283.06, 'card', 'pay_QIn79VYnx7oYdd', '2025-04-14 09:05:58', 'successful'),
(40, '#ORD-040', 60.70, 'card', 'pay_QInSTKHTabNi0U', '2025-04-13 10:05:00', 'successful'),
(41, '#ORD-041', 246.00, 'card', 'pay_QInUQDnBTWQdWi', '2025-04-13 10:05:00', 'successful'),
(42, '#ORD-042', 300.50, 'card', 'pay_QInYiooh8T9VYY', '2025-04-13 10:05:00', 'successful'),
(43, '#ORD-043', 300.50, 'card', 'pay_QIoJ835TYUaNBM', '2025-04-14 10:16:01', 'successful'),
(44, '#ORD-044', 283.06, 'cash', NULL, '2025-04-14 10:25:40', 'pending'),
(45, '#ORD-045', 224.20, 'card', 'pay_QIoW0HJk5HTro9', '2025-04-14 10:28:11', 'successful'),
(46, '#ORD-046', 756.12, 'card', 'pay_QIpbGFEzYAfpEN', '2025-04-14 11:31:47', 'successful'),
(47, '#ORD-047', 376.80, 'card', 'pay_QJysDx9kYuoBct', '2025-04-17 09:15:05', 'successful');

-- --------------------------------------------------------

--
-- Table structure for table `restaurants`
--

CREATE TABLE `restaurants` (
  `restaurant_id` int(11) NOT NULL,
  `restaurant_pic` varchar(500) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `zip_code` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `status` enum('open','closed','inactive') NOT NULL DEFAULT 'open'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `restaurants`
--

INSERT INTO `restaurants` (`restaurant_id`, `restaurant_pic`, `name`, `phone`, `address`, `city`, `state`, `zip_code`, `created_at`, `updated_at`, `status`) VALUES
(1, 'uploads/cropped-logo.png', 'Village Chef', '9727181143', 'A-20 , G-4 ,Pasodara', 'Surat', 'Gujarat', '395008', '2025-03-22 16:34:57', '2025-04-13 13:11:04', 'open'),
(3, 'uploads/pizza1.png', 'Zomato', '9685741256', 'A-20 , G-4 ,Pasodara', 'Surat', 'Gujarat', '395008', '2025-03-22 17:53:45', '2025-03-29 20:24:44', 'open'),
(4, 'uploads/images.png', 'Burger King', '9685741256', 'A-20 , G-4 ,Pasodara', 'Surat', 'Gujarat', '395008', '2025-04-04 23:58:28', '2025-04-04 23:58:56', 'inactive'),
(6, 'uploads/restaurant1.png', 'Tandoori Nights', '9876543230', '100, Food Street', 'Ahmedabad', 'Gujarat', '380001', '2025-04-05 17:15:46', NULL, 'open'),
(7, 'uploads/restaurant2.png', 'Spice Villa', '9876543231', '200, Spice Lane', 'Surat', 'Gujarat', '395001', '2025-04-05 17:15:46', NULL, 'open'),
(8, 'uploads/restaurant3.png', 'Curry House', '9876543232', '300, Curry Corner', 'Vadodara', 'Gujarat', '390001', '2025-04-05 17:15:46', NULL, 'open'),
(9, 'uploads/restaurant4.png', 'Biryani Bliss', '9876543233', '400, Biryani Boulevard', 'Rajkot', 'Gujarat', '360001', '2025-04-05 17:15:46', NULL, 'open'),
(10, 'uploads/restaurant5.png', 'Dosa Delight', '9876543234', '500, Dosa Drive', 'Bhavnagar', 'Gujarat', '364001', '2025-04-05 17:15:46', NULL, 'open'),
(11, 'uploads/restaurant6.png', 'Chaat Corner', '9876543235', '600, Chaat Circle', 'Jamnagar', 'Gujarat', '361001', '2025-04-05 17:15:46', NULL, 'open'),
(12, 'uploads/restaurant7.png', 'Masala Magic', '9876543236', '700, Masala Market', 'Junagadh', 'Gujarat', '362001', '2025-04-05 17:15:46', NULL, 'open'),
(13, 'uploads/restaurant8.png', 'Roti & Curry', '9876543237', '800, Roti Road', 'Gandhinagar', 'Gujarat', '382001', '2025-04-05 17:15:46', NULL, 'open'),
(14, 'uploads/restaurant9.png', 'Naan Stop', '9876543238', '900, Naan Nagar', 'Anand', 'Gujarat', '388001', '2025-04-05 17:15:46', NULL, 'open'),
(15, 'uploads/restaurant10.png', 'Kebab King', '9876543239', '1000, Kebab Kingdom', 'Bharuch', 'Gujarat', '392001', '2025-04-05 17:15:46', NULL, 'open'),
(16, 'uploads/restaurant11.png', 'Domino\'s Pizza Hub', '9876543240', '1100, Pizza Place', 'Nadiad', 'Gujarat', '387001', '2025-04-05 17:15:46', NULL, 'open'),
(17, 'uploads/restaurant12.png', 'Pizza Hut Express', '9876543241', '1200, Hut Highway', 'Mehsana', 'Gujarat', '384001', '2025-04-05 17:15:46', NULL, 'open'),
(18, 'uploads/restaurant13.png', 'McDonald\'s Gujarat', '9876543242', '1300, McDrive', 'Porbandar', 'Gujarat', '360575', '2025-04-05 17:15:46', NULL, 'open'),
(19, 'uploads/restaurant14.png', 'Subway Sandwiches', '9876543243', '1400, Sub Street', 'Navsari', 'Gujarat', '396445', '2025-04-05 17:15:46', NULL, 'open'),
(20, 'uploads/restaurant15.png', 'KFC Veg Fried ', '9876543244', '1500, KFC Lane', 'Vapi', 'Gujarat', '396191', '2025-04-05 17:15:46', '2025-04-13 19:59:07', 'open'),
(21, 'uploads/restaurant16.png', 'Burger King India', '9876543245', '1600, Burger Boulevard', 'Morbi', 'Gujarat', '363641', '2025-04-05 17:15:46', NULL, 'open'),
(22, 'uploads/restaurant17.png', 'Starbucks Coffee House', '9876543246', '1700, Coffee Corner', 'Surendranagar', 'Gujarat', '363001', '2025-04-05 17:15:46', NULL, 'open'),
(23, 'uploads/restaurant18.png', 'Dunkin\' Donuts', '9876543247', '1800, Donut Drive', 'Godhra', 'Gujarat', '389001', '2025-04-05 17:15:46', NULL, 'open'),
(24, 'uploads/restaurant19.png', 'Baskin-Robbins Ice Cream', '9876543248', '1900, Ice Cream Island', 'Palanpur', 'Gujarat', '385001', '2025-04-05 17:15:46', NULL, 'open'),
(25, 'uploads/restaurant20.png', 'Taco Bell Mexican', '9876543249', '2000, Taco Town', 'Patan', 'Gujarat', '384265', '2025-04-05 17:15:46', NULL, 'open'),
(34, 'uploads/IMG_20240526_151909_198.jpg', 'tesing', '9685741256', 'A-20 , G-4 ,Pasodara', 'Surat', 'Gujarat', '395008', '2025-04-13 22:35:27', NULL, 'open'),
(35, 'uploads/IMG_20240526_151909_198.jpg', 'demo', '8765456789', 'A-20 , G-4 ,Pasodara', 'Surat', 'fsdv', '395008', '2025-04-14 00:13:49', NULL, 'open');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `restaurant_id` int(11) NOT NULL,
  `order_id` varchar(20) DEFAULT NULL,
  `rating` tinyint(4) NOT NULL,
  `review_text` text DEFAULT NULL,
  `status` enum('published','archived') NOT NULL DEFAULT 'archived',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`review_id`, `user_id`, `restaurant_id`, `order_id`, `rating`, `review_text`, `status`, `created_at`, `updated_at`) VALUES
(1, 27, 3, '#ORD-001', 5, 'Amazing food and quick delivery! Highly recommend.', 'published', '2025-03-11 09:00:00', '2025-04-12 21:51:19'),
(11, 30, 6, '#ORD-012', 5, 'Amazing Butter Chicken!', 'published', '2025-04-02 10:00:00', NULL),
(12, 31, 7, NULL, 4, 'Good service, but food was a bit cold.', 'archived', '2025-04-03 11:00:00', NULL),
(13, 32, 8, '#ORD-014', 3, 'Rogan Josh was okay, not very flavorful.', 'published', '2025-04-04 12:00:00', NULL),
(14, 33, 9, NULL, 5, 'Love the ambiance and the staff.', 'archived', '2025-04-05 13:00:00', NULL),
(15, 34, 10, '#ORD-016', 4, 'Masala Dosa was delicious, but portion small.', 'published', '2025-04-06 14:00:00', NULL),
(16, 35, 11, NULL, 2, 'Pani Puri was not fresh.', 'archived', '2025-04-07 15:00:00', NULL),
(17, 36, 12, '#ORD-018', 5, 'Chole Bhature was perfect!', 'published', '2025-04-08 16:00:00', NULL),
(18, 37, 13, NULL, 4, 'Dal Makhani was rich and creamy.', 'archived', '2025-04-09 17:00:00', NULL),
(19, 38, 14, '#ORD-020', 3, 'Garlic Naan was too dry.', 'published', '2025-04-10 18:00:00', NULL),
(20, 39, 15, NULL, 1, 'Seekh Kebab was burnt.', 'archived', '2025-04-11 19:00:00', NULL),
(21, 40, 16, '#ORD-022', 5, 'Margherita Pizza was excellent!', 'published', '2025-04-12 20:00:00', NULL),
(22, 41, 17, NULL, 4, 'Pepperoni Pizza was good, crust was soggy.', 'archived', '2025-04-13 21:00:00', NULL),
(23, 42, 18, '#ORD-024', 3, 'Big Mac was as expected.', 'published', '2025-04-14 22:00:00', NULL),
(24, 43, 19, NULL, 2, 'Veggie Delite lacked flavor.', 'archived', '2025-04-15 23:00:00', NULL),
(25, 44, 20, '#ORD-026', 5, 'Fried Chicken Bucket was crispy and juicy.', 'published', '2025-04-16 10:00:00', NULL),
(26, 45, 21, NULL, 4, 'Whopper was tasty, but too messy.', 'archived', '2025-04-17 11:00:00', NULL),
(27, 46, 22, '#ORD-028', 3, 'Cappuccino was lukewarm.', 'published', '2025-04-18 12:00:00', NULL),
(28, 47, 23, NULL, 5, 'Glazed Donut was fresh and sweet.', 'archived', '2025-04-19 13:00:00', NULL),
(29, 48, 24, '#ORD-030', 4, 'Chocolate Ice Cream was rich and creamy.', 'published', '2025-04-20 14:00:00', NULL),
(30, 49, 25, NULL, 3, 'Crunchy Taco was okay, not very filling.', 'archived', '2025-04-21 15:00:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `tag_id` int(11) NOT NULL,
  `tag` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`tag_id`, `tag`) VALUES
(10, 'asian'),
(21, 'biryani'),
(12, 'breakfast'),
(7, 'burgur'),
(5, 'chinese'),
(11, 'dessert'),
(14, 'dinner'),
(18, 'fresh'),
(20, 'gujarati-thali'),
(16, 'high-protein'),
(24, 'ice-cream'),
(3, 'italian'),
(15, 'low-carb'),
(13, 'lunch'),
(9, 'mexican'),
(22, 'north-indian'),
(17, 'organic'),
(2, 'pizza'),
(23, 'rolls'),
(8, 'sandwich'),
(1, 'spicy'),
(19, 'sugar-free'),
(6, 'vegan'),
(4, 'vegetarian');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `profile_pic` varchar(500) DEFAULT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(10) DEFAULT NULL,
  `address` varchar(500) DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('customer','admin','restaurant_owner','delivery_agent') NOT NULL DEFAULT 'customer',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `status` enum('active','inactive') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `profile_pic`, `first_name`, `last_name`, `email`, `phone`, `address`, `password_hash`, `role`, `created_at`, `updated_at`, `status`) VALUES
(21, 'assets/dp.png', 'Purv', 'Virpariya', 'purvvirpariya14@gmail.com', '2147483647', NULL, '$2y$10$23Qsz1lSLdMhY0oeI1fSuOKLZl9Tpr/u7PDVIOG2zaTEf7jWJ2y0a', 'admin', '2025-03-21 20:47:17', '2025-04-13 14:27:24', 'active'),
(27, 'uploads/IMG_20240526_151909_198.jpg', 'Parthiv', 'Shingala', 'parthivshingala@gmail.com', '9727181143', 'A-20 , G-4 , Om Township -3 , Pasodara ,Surat', '$2y$10$ZTDOqvaqScmqt/.AeJH1s.edDxe.7njIIW50SwveJTQ3gnqUnRo3y', 'admin', '2025-03-21 22:01:11', '2025-04-17 21:53:51', 'inactive'),
(28, 'assets/dp.png', 'demo', 'Shingala', 'parthivpatel30@gmail.com', '9876543212', 'chjgch', '$2y$10$eBUPr2dyWGmolw.ZlfvpgePrGErvZ0XY0sN8xS4784FkjVHkMc05G', 'customer', '2025-03-24 15:27:16', '2025-04-17 20:59:11', 'inactive'),
(30, 'assets/dp.png', 'Drashti', 'Patel', 'drashti.patel@example.com', '9876543210', '123, Gandhi Road, Ahmedabad', '$2y$10$placeholderhash', 'customer', '2025-04-05 17:15:38', '2025-04-05 21:16:31', 'active'),
(31, 'assets/dp.png', 'Bhargavi', 'Shah', 'bhargavi.shah@example.com', '9876543211', '456, Shah Street, Surat', '$2y$10$placeholderhash', 'customer', '2025-04-05 17:15:38', '2025-04-05 21:16:31', 'active'),
(32, 'assets/dp.png', 'Harsh', 'Desai', 'harsh.desai@example.com', '9876543212', '789, Desai Lane, Vadodara', '$2y$10$placeholderhash', 'customer', '2025-04-05 17:15:38', '2025-04-05 21:16:31', 'active'),
(33, 'assets/dp.png', 'Sumit', 'Mehta', 'sumit.mehta@example.com', '9876543213', '101, Mehta Marg, Rajkot', '$2y$10$placeholderhash', 'customer', '2025-04-05 17:15:38', '2025-04-05 21:16:31', 'active'),
(34, 'assets/dp.png', 'Megha', 'Joshi', 'megha.joshi@example.com', '9876543214', '202, Joshi Colony, Bhavnagar', '$2y$10$placeholderhash', 'customer', '2025-04-05 17:15:38', '2025-04-05 21:16:31', 'active'),
(35, 'assets/dp.png', 'Aarav', 'Trivedi', 'aarav.trivedi@example.com', '9876543215', '303, Trivedi Towers, Jamnagar', '$2y$10$placeholderhash', 'customer', '2025-04-05 17:15:38', '2025-04-05 21:16:31', 'active'),
(36, 'assets/dp.png', 'Vihaan', 'Pandya', 'vihaan.pandya@example.com', '9876543216', '404, Pandya Path, Junagadh', '$2y$10$placeholderhash', 'customer', '2025-04-05 17:15:38', '2025-04-05 21:16:31', 'active'),
(37, 'assets/dp.png', 'Aditi', 'Gandhi', 'aditi.gandhi@example.com', '9876543217', '505, Gandhi Gardens, Gandhinagar', '$2y$10$placeholderhash', 'customer', '2025-04-05 17:15:38', '2025-04-05 21:16:31', 'active'),
(38, 'assets/dp.png', 'Ananya', 'Modi', 'ananya.modi@example.com', '9876543218', '606, Modi Mansion, Anand', '$2y$10$placeholderhash', 'customer', '2025-04-05 17:15:38', '2025-04-05 21:16:31', 'active'),
(39, 'assets/dp.png', 'Ishaan', 'Chauhan', 'ishaan.chauhan@example.com', '9876543219', '707, Chauhan Chowk, Bharuch', '$2y$10$placeholderhash', 'customer', '2025-04-05 17:15:38', '2025-04-05 21:16:31', 'active'),
(40, 'assets/dp.png', 'Diya', 'Parmar', 'diya.parmar@example.com', '9876543220', '808, Parmar Park, Nadiad', '$2y$10$placeholderhash', 'customer', '2025-04-05 17:15:38', '2025-04-05 21:16:31', 'active'),
(41, 'assets/dp.png', 'Kavya', 'Solanki', 'kavya.solanki@example.com', '9876543221', '909, Solanki Square, Mehsana', '$2y$10$placeholderhash', 'customer', '2025-04-05 17:15:38', '2025-04-05 21:16:31', 'active'),
(42, 'assets/dp.png', 'Rohan', 'Vaghela', 'rohan.vaghela@example.com', '9876543222', '1010, Vaghela Villa, Porbandar', '$2y$10$placeholderhash', 'customer', '2025-04-05 17:15:38', '2025-04-05 21:16:31', 'active'),
(43, 'assets/dp.png', 'Arjun', 'Rathod', 'arjun.rathod@example.com', '9876543223', '1111, Rathod Residency, Navsari', '$2y$10$placeholderhash', 'customer', '2025-04-05 17:15:38', '2025-04-05 21:16:31', 'active'),
(44, 'assets/dp.png', 'Neha', 'Gohil', 'neha.gohil@example.com', '9876543224', '1212, Gohil Gardens, Vapi', '$2y$10$placeholderhash', 'customer', '2025-04-05 17:15:38', '2025-04-05 21:16:31', 'active'),
(45, 'assets/dp.png', 'Pooja', 'Jadeja', 'pooja.jadeja@example.com', '9876543225', '1313, Jadeja Junction, Morbi', '$2y$10$placeholderhash', 'customer', '2025-04-05 17:15:38', '2025-04-05 21:16:31', 'active'),
(46, 'assets/dp.png', 'Rahul', 'Chavda', 'rahul.chavda@example.com', '9876543226', '1414, Chavda Circle, Surendranagar', '$2y$10$placeholderhash', 'customer', '2025-04-05 17:15:38', '2025-04-05 21:16:31', 'active'),
(47, 'assets/dp.png', 'Sneha', 'Zala', 'sneha.zala@example.com', '9876543227', '1515, Zala Zone, Godhra', '$2y$10$placeholderhash', 'customer', '2025-04-05 17:15:38', '2025-04-05 21:16:31', 'active'),
(48, 'assets/dp.png', 'Vivek', 'Makwana', 'vivek.makwana@example.com', '9876543228', '1616, Makwana Market, Palanpur', '$2y$10$placeholderhash', 'customer', '2025-04-05 17:15:38', '2025-04-05 21:16:31', 'active'),
(49, 'assets/dp.png', 'Yash', 'Rathwa', 'yash.rathwa@example.com', '9876543229', '1717, Rathwa Road, Patan', '$2y$10$placeholderhash', 'customer', '2025-04-05 17:15:38', '2025-04-05 21:16:31', 'active'),
(50, NULL, 'Testing', 'demo', 'admin1@g.com', '9727181143', 'Om 3', '$2y$10$c/CUfVDLk8QjE/qy7Ou6peWJX9A/umJRulDIGk9lhQr6S3ToX1Yg2', 'customer', '2025-04-13 22:34:33', '2025-04-14 00:03:00', 'active'),
(51, NULL, 'Prasant', 'Patel', 'veervanani1983@gmail.com', '9737853206', 'surat', '$2y$10$jRHUBhSSMt/AtRRNtG0VU.LbZl3iv33AfGQoVs0BHIJkSNm/cC0Tm', 'customer', '2025-04-14 11:10:56', '2025-04-14 11:31:15', 'active'),
(52, NULL, 'veer', 'vanani', 'veervanani1201@gmail.com', '9737853206', NULL, '$2y$10$d/my2P4/ilEp9eNANU8KmO3vq0iNx13acKHqL1gPj1VfhnWfSI8fq', 'customer', '2025-04-14 11:24:30', NULL, 'active'),
(54, NULL, 'Parthiv', 'Shingala', 'parthivpatel3005@gmail.com', '9727181143', NULL, '$2y$10$U7NDCKrB0jjNZd4bgjqhdOEA9mpZGrlqdCatEOwbVESjP0WOGZbYy', 'customer', '2025-04-17 21:00:32', NULL, 'active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `fk_cart_user` (`user_id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`cart_item_id`),
  ADD KEY `fk_cartitem_cart` (`cart_id`),
  ADD KEY `fk_cartitem_item` (`item_id`);

--
-- Indexes for table `cuisines`
--
ALTER TABLE `cuisines`
  ADD PRIMARY KEY (`cuisine_id`);

--
-- Indexes for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `fk_menuitem_restaurant` (`restaurant_id`),
  ADD KEY `fk_menuitem_cuisine` (`cuisine_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD UNIQUE KEY `order_id` (`order_id`),
  ADD KEY `fk_order_user` (`user_id`),
  ADD KEY `fk_order_restaurant` (`restaurant_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `fk_orderitem_item` (`item_id`),
  ADD KEY `fk_orderitem_order` (`order_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `fk_payment_order` (`order_id`);

--
-- Indexes for table `restaurants`
--
ALTER TABLE `restaurants`
  ADD PRIMARY KEY (`restaurant_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `fk_review_user` (`user_id`),
  ADD KEY `fk_review_restaurant` (`restaurant_id`),
  ADD KEY `fk_review_order` (`order_id`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`tag_id`),
  ADD UNIQUE KEY `tag` (`tag`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `cart_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `cuisines`
--
ALTER TABLE `cuisines`
  MODIFY `cuisine_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `menu_items`
--
ALTER TABLE `menu_items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `restaurants`
--
ALTER TABLE `restaurants`
  MODIFY `restaurant_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `tag_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `fk_cart_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON UPDATE CASCADE;

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `fk_cartitem_cart` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`cart_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cartitem_item` FOREIGN KEY (`item_id`) REFERENCES `menu_items` (`item_id`) ON UPDATE CASCADE;

--
-- Constraints for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD CONSTRAINT `fk_menuitem_cuisine` FOREIGN KEY (`cuisine_id`) REFERENCES `cuisines` (`cuisine_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_menuitem_restaurant` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`restaurant_id`) ON UPDATE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_order_restaurant` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`restaurant_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_order_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON UPDATE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `fk_orderitem_item` FOREIGN KEY (`item_id`) REFERENCES `menu_items` (`item_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_orderitem_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `fk_payment_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `fk_review_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_review_restaurant` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`restaurant_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_review_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
