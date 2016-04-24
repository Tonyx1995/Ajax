-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Apr 22, 2016 at 11:26 PM
-- Server version: 5.5.39
-- PHP Version: 5.4.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ajax`
--

-- --------------------------------------------------------

--
-- Table structure for table `food_sale_category`
--

CREATE TABLE IF NOT EXISTS `food_sale_category` (
`category_id` int(8) NOT NULL,
  `category_description` varchar(250) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

--
-- Dumping data for table `food_sale_category`
--

INSERT INTO `food_sale_category` (`category_id`, `category_description`) VALUES
(1, 'Appetizer'),
(2, 'Entree'),
(3, 'Dessert'),
(8, 'Drink'),
(15, 'Snack'),
(17, 'Test'),
(18, 'Asdf');

-- --------------------------------------------------------

--
-- Table structure for table `food_sale_food`
--

CREATE TABLE IF NOT EXISTS `food_sale_food` (
`food_id` int(8) NOT NULL,
  `food_name` varchar(125) NOT NULL,
  `food_description` varchar(250) DEFAULT NULL,
  `regular_price` float NOT NULL,
  `sale_price` float DEFAULT NULL,
  `sale_start_date` date DEFAULT NULL,
  `sale_end_date` date DEFAULT NULL,
  `on_sale` int(1) NOT NULL,
  `category_id` int(8) NOT NULL,
  `pic` varchar(250) NOT NULL,
  `cyclone_card_item` tinyint(1) NOT NULL,
  `cyclone_card_price` float DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=176 ;

--
-- Dumping data for table `food_sale_food`
--

INSERT INTO `food_sale_food` (`food_id`, `food_name`, `food_description`, `regular_price`, `sale_price`, `sale_start_date`, `sale_end_date`, `on_sale`, `category_id`, `pic`, `cyclone_card_item`, `cyclone_card_price`) VALUES
(8, 'Burger & Fries', 'Delicious burger & fries. ', 3.55, 0, '1970-01-01', '1970-01-01', 0, 2, '8.jpg', 0, 0),
(33, 'Apple', 'Healthy and delicious.', 1.5, 1, '2016-04-05', '2016-04-29', 1, 15, '33.jpg', 0, 0),
(35, 'Double Cheeseburger', 'Classic, delicious, filling.', 4.99, 0, '1970-01-01', '1970-01-01', 0, 2, '35.png', 1, 1),
(36, 'Fry Basket', 'A basket of our golden-fried french fries.', 1.99, 0, '1970-01-01', '1970-01-01', 0, 1, '36.jpg', 1, 1.5),
(38, 'Assorted Candies', 'Have a sweet tooth? Try our sweet, flavorful, assortments of candies.', 3.99, 0, '1970-01-01', '1970-01-01', 0, 15, '38.jpg', 0, 0),
(95, 'Omelette', 'A creamy and fluffy cooked omelette made just the way you like it.', 4.99, 2.5, '2016-03-12', '2016-03-31', 0, 2, '95.jpg', 1, 2.5),
(173, 'Fountain Drink', 'A fountain drink.', 1.99, 0, '1970-01-01', '1970-01-01', 0, 0, '173.jpg', 0, NULL),
(174, 'Coke', 'Ice cold.', 1.5, 0.99, '2016-04-01', '2016-04-30', 1, 0, '174.jpg', 0, NULL),
(175, 'asdfjlkasl', 'jkjlk', 98, 0, '1970-01-01', '1970-01-01', 0, 0, '175.jpg', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `food_sale_food_type_match`
--

CREATE TABLE IF NOT EXISTS `food_sale_food_type_match` (
  `food_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `food_sale_food_type_match`
--

INSERT INTO `food_sale_food_type_match` (`food_id`, `type_id`) VALUES
(38, 3),
(37, 4),
(34, 4),
(32, 2),
(35, 2),
(36, 2),
(36, 4),
(95, 1),
(97, 2),
(98, 1),
(99, 1),
(99, 2),
(100, 1),
(100, 2),
(101, 3),
(102, 1),
(38, 4),
(103, 2),
(104, 2),
(96, 2),
(97, 5),
(96, 1),
(36, 3),
(8, 4),
(0, 3),
(0, 3),
(0, 4),
(0, 1),
(0, 2),
(171, 3),
(171, 4),
(171, 2),
(172, 1),
(172, 4),
(33, 4),
(173, 4),
(174, 4),
(35, 4),
(33, 1),
(8, 2),
(33, 3),
(175, 4),
(175, 2),
(175, 3);

-- --------------------------------------------------------

--
-- Table structure for table `food_sale_sales_history`
--

CREATE TABLE IF NOT EXISTS `food_sale_sales_history` (
`sales_history_id` int(8) NOT NULL,
  `food_id` int(8) NOT NULL,
  `sale_start_date` date NOT NULL,
  `sale_end_date` date NOT NULL,
  `sale_price` float NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=98 ;

--
-- Dumping data for table `food_sale_sales_history`
--

INSERT INTO `food_sale_sales_history` (`sales_history_id`, `food_id`, `sale_start_date`, `sale_end_date`, `sale_price`) VALUES
(1, 33, '2016-02-01', '2016-02-22', 0.5),
(2, 33, '2016-02-03', '2016-02-29', 0),
(3, 33, '2016-02-04', '2016-02-15', 0.75),
(4, 37, '2016-02-03', '2016-02-29', 0.65),
(5, 37, '2016-02-03', '2016-02-29', 0.65),
(6, 34, '2016-02-03', '2016-02-21', 1),
(7, 34, '2016-02-03', '2016-02-21', 0.85),
(8, 90, '2016-02-04', '2016-02-29', 1),
(9, 35, '2016-02-01', '2016-02-29', 1.99),
(10, 0, '2016-02-06', '2016-02-22', 1),
(11, 0, '2016-02-05', '2016-02-10', 0.8),
(12, 94, '2016-02-09', '2016-02-09', 1),
(13, 93, '2016-02-05', '2016-02-10', 0.8),
(14, 93, '2016-02-05', '2016-02-10', 0.8),
(15, 93, '2016-02-05', '2016-02-10', 0.8),
(16, 92, '2016-02-06', '2016-02-22', 1),
(17, 111, '2016-03-05', '2016-03-19', 123),
(18, 111, '2016-03-05', '2016-03-19', 23),
(19, 0, '2016-03-03', '2016-03-31', 0),
(20, 33, '2016-03-02', '2016-03-26', 2.99),
(21, 33, '2016-03-03', '2016-03-26', 2.99),
(22, 33, '2016-03-03', '2016-03-26', 2.99),
(23, 33, '2016-03-03', '2016-03-26', 2.99),
(24, 33, '2016-03-03', '2016-03-26', 2.99),
(25, 33, '2016-03-03', '2016-03-26', 2.99),
(26, 33, '2016-03-03', '2016-03-26', 2.99),
(27, 33, '2016-03-03', '2016-03-26', 2.99),
(28, 33, '2016-03-03', '2016-03-26', 2.99),
(29, 33, '2016-03-03', '2016-03-26', 2.99),
(30, 33, '2016-03-03', '2016-03-26', 2.99),
(31, 33, '2016-03-03', '2016-03-26', 2.99),
(32, 34, '2016-03-01', '2016-03-19', 0),
(33, 34, '2016-03-01', '2016-03-26', 0.75),
(34, 34, '2016-03-01', '2016-03-31', 0.75),
(35, 34, '2016-03-01', '2016-03-31', 0.75),
(36, 34, '2016-03-01', '2016-03-31', 0.75),
(37, 34, '2016-03-02', '2016-03-31', 0.95),
(38, 33, '2016-03-01', '2016-03-31', 1.98),
(40, 99, '2016-03-01', '2016-03-25', 0),
(41, 100, '2016-03-01', '2016-03-25', 0),
(42, 101, '2016-03-01', '2016-03-25', 0),
(43, 33, '2016-03-01', '2016-03-31', 1.95),
(44, 37, '2016-03-01', '2016-03-24', 0.99),
(45, 37, '2016-03-01', '2016-03-24', 0.99),
(46, 37, '2016-03-01', '2016-03-24', 0.98),
(47, 37, '2016-03-01', '2016-03-24', 0.99),
(48, 105, '2016-03-01', '2016-03-31', 0),
(49, 106, '2016-03-01', '2016-03-31', 0),
(50, 35, '2016-03-01', '2016-03-31', 4.89),
(51, 35, '2016-03-01', '2016-03-31', 4),
(52, 35, '2016-03-01', '2016-03-31', 4),
(53, 33, '2016-03-01', '2016-03-31', 1.98),
(54, 33, '2016-03-01', '2016-03-31', 1.98),
(55, 33, '2016-03-01', '2016-03-31', 1.98),
(56, 33, '2016-03-01', '2016-03-31', 1.98),
(59, 33, '2016-03-01', '2016-03-31', 1.98),
(60, 33, '2016-03-01', '2016-03-31', 1.98),
(71, 33, '2016-03-01', '2016-03-31', 0.99),
(72, 33, '2016-03-01', '2016-03-31', 0.99),
(93, 95, '2016-03-12', '2016-03-31', 2.5),
(94, 8, '1970-01-01', '1970-01-01', 0),
(95, 36, '1970-01-01', '1970-01-01', 0),
(96, 33, '1970-01-01', '1970-01-01', 0),
(97, 38, '1970-01-01', '1970-01-01', 0);

-- --------------------------------------------------------

--
-- Table structure for table `food_sale_specials`
--

CREATE TABLE IF NOT EXISTS `food_sale_specials` (
`special_id` int(20) NOT NULL,
  `special_name` varchar(255) NOT NULL,
  `special_description` varchar(1000) DEFAULT NULL,
  `special_date` date NOT NULL,
  `special_price` float NOT NULL,
  `pic` varchar(250) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=22 ;

--
-- Dumping data for table `food_sale_specials`
--

INSERT INTO `food_sale_specials` (`special_id`, `special_name`, `special_description`, `special_date`, `special_price`, `pic`) VALUES
(2, 'Pasta bar', 'Cooked in front of you', '2016-03-28', 6.75, '2.jpg'),
(3, 'Chicken breast ', 'w/ peanut sauce, rice noodles, and a beverage.', '2016-03-29', 6.25, '3.jpg'),
(4, 'ballotine of chicken', 'mushrooms, onion, beverage, and a salad', '2016-03-30', 6.25, '4.jpg'),
(5, 'cornish game hen', 'mustard crust, potatoes, vegetable, salad, and a beverage', '2016-03-31', 6.25, '5.jpg'),
(6, 'New york strip steak', 'mushroom sauce, baked potato, vegetable, bread, beverage', '2016-04-01', 7.5, '6.jpg'),
(9, 'Apple', 'Keeps the doctor away if you eat them every day.', '2016-03-28', 0.75, '9.jpg'),
(10, 'asdf', '123', '2016-03-01', 1, ''),
(11, 'asdf', '123', '2016-03-01', 1, ''),
(12, 'asdf', '123', '2016-03-01', 1, ''),
(13, 'asdf', '1', '2016-03-01', 15, ''),
(14, 'asdf', '1', '2016-03-01', 15, ''),
(15, 'asdf', '1', '2016-03-01', 15, ''),
(16, 'asdf', '1', '2016-03-01', 15, ''),
(17, 'asdf', '1', '2016-03-01', 15, ''),
(18, 'asdf', '1', '2016-03-01', 15, ''),
(19, 'asdf', '1', '2016-03-01', 15, ''),
(20, 'asdf', '1', '2016-03-01', 15, ''),
(21, 'asdf', '1', '2016-03-01', 15, '');

-- --------------------------------------------------------

--
-- Table structure for table `food_sale_type`
--

CREATE TABLE IF NOT EXISTS `food_sale_type` (
`type_id` int(11) NOT NULL,
  `type_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `food_sale_type`
--

INSERT INTO `food_sale_type` (`type_id`, `type_name`) VALUES
(1, 'Breakfast'),
(2, 'Lunch'),
(3, 'Snack'),
(4, 'Grab&Go');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
`id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `admin` tinyint(1) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=38 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `admin`) VALUES
(35, 'test', '$2y$10$fohE6tTxjiD6Qmf1r5OJIuJPN0TbN98gDFZJS/sVYQ6FC4njvAqb.', 1),
(36, 'blha', '$2y$10$xGEzQbfgbbbpxVHiP8byYOUbPrCHknfs/8zxZNSengRwDvdpCzm9y', 0),
(37, '3', '$2y$10$UfBvsRjUCFhUAAckqNXYsuuW9uXsFgCkY3YKpofNyxzDRR0mB3fzK', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `food_sale_category`
--
ALTER TABLE `food_sale_category`
 ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `food_sale_food`
--
ALTER TABLE `food_sale_food`
 ADD PRIMARY KEY (`food_id`);

--
-- Indexes for table `food_sale_sales_history`
--
ALTER TABLE `food_sale_sales_history`
 ADD PRIMARY KEY (`sales_history_id`);

--
-- Indexes for table `food_sale_specials`
--
ALTER TABLE `food_sale_specials`
 ADD PRIMARY KEY (`special_id`);

--
-- Indexes for table `food_sale_type`
--
ALTER TABLE `food_sale_type`
 ADD PRIMARY KEY (`type_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `food_sale_category`
--
ALTER TABLE `food_sale_category`
MODIFY `category_id` int(8) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `food_sale_food`
--
ALTER TABLE `food_sale_food`
MODIFY `food_id` int(8) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=176;
--
-- AUTO_INCREMENT for table `food_sale_sales_history`
--
ALTER TABLE `food_sale_sales_history`
MODIFY `sales_history_id` int(8) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=98;
--
-- AUTO_INCREMENT for table `food_sale_specials`
--
ALTER TABLE `food_sale_specials`
MODIFY `special_id` int(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT for table `food_sale_type`
--
ALTER TABLE `food_sale_type`
MODIFY `type_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=38;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
