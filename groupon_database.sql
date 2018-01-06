-- phpMyAdmin SQL Dump
-- version 4.0.10.14
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Sep 17, 2016 at 03:33 PM
-- Server version: 5.5.49-cll-lve
-- PHP Version: 5.6.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `groupon_database`
--

-- --------------------------------------------------------

--
-- Table structure for table `deals`
--

CREATE TABLE IF NOT EXISTS `deals` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'primary key',
  `vendor_id` int(11) DEFAULT NULL,
  `img_rel_url` char(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'for image backdrop',
  `bold_text` text COLLATE utf8_unicode_ci COMMENT 'deal details',
  `highlight_text` text COLLATE utf8_unicode_ci COMMENT 'deal details',
  `valid_from` datetime DEFAULT NULL,
  `valid_until` datetime DEFAULT NULL,
  `fine_print_text` text COLLATE utf8_unicode_ci COMMENT 'deal details',
  `original_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `current_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `num_bought` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'number of times this deal was bought',
  `is_limited_time_offer` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'if true, then ignore expiration date',
  `num_thumbs_up` int(10) unsigned NOT NULL DEFAULT '0',
  `num_thumbs_down` int(10) unsigned NOT NULL DEFAULT '0',
  `is_limited_availability` tinyint(1) NOT NULL DEFAULT '0',
  `time_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'when this deal is created',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=49 ;

--
-- Dumping data for table `deals`
--

INSERT INTO `deals` (`id`, `vendor_id`, `img_rel_url`, `bold_text`, `highlight_text`, `valid_from`, `valid_until`, `fine_print_text`, `original_price`, `current_price`, `num_bought`, `is_limited_time_offer`, `num_thumbs_up`, `num_thumbs_down`, `is_limited_availability`, `time_stamp`) VALUES
(0, 0, 'jetpack.jpg', 'FREE Jet-Pack! While Supplies Last', 'Eat the jet pack and get another one for free! This is probably the weirdest thing you''ll see in awhile...', '2016-08-29 00:00:00', '2016-12-25 00:00:00', 'Jet Pack may be severely damaged. We are no longer liable for any damages and injuries caused by using this product.', '2500.00', '0.00', 67777, 1, 10000, 50, 1, '0000-00-00 00:00:00'),
(2, 0, 'beer.jpg', 'Free Beer', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '0.00', '0.00', 0, 0, 0, 0, 0, '0000-00-00 00:00:00'),
(3, 0, 'burger.jpg', 'burger burger burger burger burger', 'this is one good burger.... for you...', '2016-05-05 12:23:23', '2016-05-05 12:23:23', 'eat with care', '100.00', '10.00', 10, 1, 9, 1, 1, '0000-00-00 00:00:00'),
(4, 0, 'pizza.jpg', 'Good Pizza For Everyone!', 'pizza is good for you. so why wait! go buy it now we need money.', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'this is a delicate human pizza. please take good care of it', '10.00', '1.00', 100, 1, 98, 2, 1, '0000-00-00 00:00:00'),
(5, 0, 'coffee.jpg', 'coffee', NULL, NULL, NULL, NULL, '0.00', '0.00', 0, 1, 0, 0, 0, '2016-09-09 06:51:13'),
(6, 0, 'coke.jpg', 'coke', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '0.00', '0.00', 0, 0, 0, 0, 0, '0000-00-00 00:00:00'),
(7, 0, 'hotdog.jpg', 'hotdog', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '0.00', '0.00', 0, 0, 0, 0, 0, '0000-00-00 00:00:00'),
(8, 0, 'iphone.jpg', 'iphone', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '0.00', '0.00', 0, 0, 0, 0, 0, '0000-00-00 00:00:00'),
(9, 0, 'movie.jpg', 'movie', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '0.00', '0.00', 0, 0, 0, 0, 0, '0000-00-00 00:00:00'),
(10, 0, 'nexus.jpg', 'nexus', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '0.00', '0.00', 0, 0, 0, 0, 0, '0000-00-00 00:00:00'),
(11, 0, 'playstation.jpg', 'playstation', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '0.00', '0.00', 0, 0, 0, 0, 0, '0000-00-00 00:00:00'),
(12, 0, 'salmon.jpg', 'salmon', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '0.00', '0.00', 0, 0, 0, 0, 0, '0000-00-00 00:00:00'),
(13, 0, 'samsung.jpg', 'Samsung', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '0.00', '0.00', 0, 0, 0, 0, 0, '0000-00-00 00:00:00'),
(14, 0, 'water.png', 'water', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '0.00', '0.00', 0, 0, 0, 0, 0, '0000-00-00 00:00:00'),
(15, 0, 'xbox.jpg', 'xbox', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '0.00', '0.00', 0, 0, 0, 0, 0, '0000-00-00 00:00:00'),
(16, 0, 'bar_one.jpg', 'bar one', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '0.00', '0.00', 0, 0, 0, 0, 0, '0000-00-00 00:00:00'),
(17, 0, 'bar_two.jpg', 'bar', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '0.00', '0.00', 0, 0, 0, 0, 0, '0000-00-00 00:00:00'),
(18, 0, 'bar_three.jpg', 'bar', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '0.00', '0.00', 0, 0, 0, 0, 0, '0000-00-00 00:00:00'),
(19, 0, 'bar_four.jpg', 'bar', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '0.00', '0.00', 0, 0, 0, 0, 0, '0000-00-00 00:00:00'),
(20, 0, 'bar_five.jpg', 'bar', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '0.00', '0.00', 0, 0, 0, 0, 0, '0000-00-00 00:00:00'),
(21, 0, 'beef.jpg', 'Beef for You!', 'go get the beef. you know you want it....', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'it is possibly not fresh', '0.00', '0.00', 0, 0, 0, 0, 0, '0000-00-00 00:00:00'),
(22, 0, 'chicken.jpg', 'chicken', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '0.00', '0.00', 0, 0, 0, 0, 0, '0000-00-00 00:00:00'),
(23, 0, 'pepsi.png', 'pepsi', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '0.00', '0.00', 0, 0, 0, 0, 0, '0000-00-00 00:00:00'),
(24, 0, 'sierramist.jpg', 'Sierra mist', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '0.00', '0.00', 0, 0, 0, 0, 0, '0000-00-00 00:00:00'),
(26, 0, 'apple.jpg', 'Free Red Apples at Sprouts!', 'limit 10 per family and while supplies last. no rain checks.', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '1.00', '0.00', 100, 0, 99, 1, 0, '0000-00-00 00:00:00'),
(27, 0, 'hotel_one.jpg', 'hotel is hotel', '', '2001-01-01 00:00:00', '4000-01-01 00:00:00', '', '1000.00', '300.00', 10, 1, 8, 2, 1, '0000-00-00 00:00:00'),
(28, 0, 'hotel_two.jpg', 'hotel', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '0.00', '0.00', 0, 0, 0, 0, 0, '0000-00-00 00:00:00'),
(29, 0, 'hotel_three.jpg', 'hotel', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '0.00', '0.00', 0, 0, 0, 0, 0, '0000-00-00 00:00:00'),
(30, 0, 'hotel_four.jpg', 'hotel', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '0.00', '0.00', 0, 0, 0, 0, 0, '0000-00-00 00:00:00'),
(31, 0, 'hotel_five.jpg', 'hotel', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '0.00', '0.00', 0, 0, 0, 0, 0, '0000-00-00 00:00:00'),
(32, 0, 'hotel_six.jpg', 'hotel', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '0.00', '0.00', 0, 0, 0, 0, 0, '0000-00-00 00:00:00'),
(33, 0, 'bar_six.jpg', 'bar', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '0.00', '0.00', 0, 0, 0, 0, 0, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `deal_tag`
--

CREATE TABLE IF NOT EXISTS `deal_tag` (
  `deal_id` int(10) unsigned NOT NULL,
  `tag` char(100) COLLATE utf8_unicode_ci NOT NULL,
  UNIQUE KEY `deal_id` (`deal_id`,`tag`),
  KEY `deal_id_2` (`deal_id`),
  KEY `deal_id_3` (`deal_id`),
  KEY `deal_id_4` (`deal_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `deal_tag`
--

INSERT INTO `deal_tag` (`deal_id`, `tag`) VALUES
(0, 'entertainment'),
(0, 'retail'),
(2, 'drink'),
(3, 'food'),
(4, 'food'),
(6, 'drink'),
(7, 'food'),
(8, 'entertainment'),
(8, 'retail'),
(9, 'entertainment'),
(10, 'entertainment'),
(10, 'retail'),
(11, 'entertainment'),
(11, 'retail'),
(12, 'food'),
(12, 'retail'),
(13, 'entertainment'),
(13, 'retail'),
(14, 'drink'),
(15, 'entertainment'),
(15, 'retail'),
(16, 'bars'),
(17, 'bars'),
(18, 'bars'),
(19, 'bars'),
(20, 'bars'),
(21, 'food'),
(22, 'food'),
(23, 'drink'),
(24, 'drink'),
(26, 'food'),
(27, 'hotel'),
(28, 'hotel'),
(29, 'hotel'),
(30, 'hotel'),
(31, 'hotel'),
(32, 'hotel'),
(33, 'bars');

-- --------------------------------------------------------

--
-- Table structure for table `firebase_registration_tokens`
--

CREATE TABLE IF NOT EXISTS `firebase_registration_tokens` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `registration_token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `registration_token` (`registration_token`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `firebase_registration_tokens`
--

INSERT INTO `firebase_registration_tokens` (`id`, `registration_token`, `user_id`) VALUES
(0, 'ceoX2lB_I3c:APA91bFqXdU6lPjzJ5BjdNFsNmH5sIT7Q24DbNjuexZIeT2siQDeQVwRadtZJHkKvZfttMBUsleUq81-R8ZMAzyc_NOXnpvHdhvXHnFqiQiDFnfYo5ys5wpv_ROzRt9VV23iVfavAuM_', NULL),
(1, 'eApQq2m7aZY:APA91bHm0mwlDQjpORQj7mrUeF2fAZdiEDcN07QQ78wfHr3kYov2w8XMtDKPLta6ITnlQEhOLQO20VMGX1WFxvTgDJR__P6pw6WYEYImHG7CbupPV_7SOvenrCXuAg_HI9l5sgKbaVCv', NULL),
(2, 'f_9neogiw9I:APA91bEuwovofZCBxhpFhB1AeZQTzpU3V-G2JJENNOcd_l6QmIoTScw890ca0PBAwU4QVooVZRYgGpFTeTqPTYxCY8qV9lDAkIQR-r3wT6Dk9cE0qaYuj7oX6lFb3ery-eSDVfxemcsI', NULL),
(3, 'cEMxN6qBtYc:APA91bFfe1ECP4ZU5xJnkKAeuKpT4fL4RP3pSR4dTwZs2sjH_lEldve5Ng-3Fa539KAh5IoRL7uc8eZiwi18BarEiSypnTavoZaRnyJdXfd3R60Y5caqC7ZO0GlyxL0ZEhs3rYndYOhO', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` text COLLATE utf8_unicode_ci NOT NULL,
  `username` text COLLATE utf8_unicode_ci NOT NULL,
  `password` text COLLATE utf8_unicode_ci NOT NULL,
  `date_registered` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `verification_code` varchar(32) COLLATE utf8_unicode_ci NOT NULL COMMENT 'verification hash md5',
  `avatar_img_rel_url` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `active` int(1) NOT NULL DEFAULT '0' COMMENT 'tells whether user is activated',
  `api_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `firebase_registration_token` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `api_key` (`api_key`),
  KEY `id_2` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=21 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `username`, `password`, `date_registered`, `verification_code`, `avatar_img_rel_url`, `active`, `api_key`, `firebase_registration_token`) VALUES
(19, 'marcuschiu9@gmail.com', 'marcus chiu', 'please', '2016-09-05 23:02:54', '9b67ce21dfddbf4e64f7205b12ac3b3c', '19.jpg', 1, 'd6063be1116266c972246d0c06f09e89', NULL),
(20, 'marcuschiutwo@gmail.com', 'marcus chiu two', 'please', '2016-09-06 03:06:39', '3b167ed243d9824962eb20d1241fd263', '20.jpg', 0, '758b6b2e4a1f3e857f15ddda2104c237', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `vendor`
--

CREATE TABLE IF NOT EXISTS `vendor` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `vendor_name` char(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `account_num` int(10) unsigned DEFAULT NULL COMMENT 'account number. One login may be linked to multiple vendors',
  `addr_1` char(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `addr_2` char(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` char(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `state` char(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `zip` char(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country_code` char(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `price_range` char(5) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'price range of vendor wares/services represented by dollar signs',
  `vendor_website` char(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `num_thumbs_up` int(10) unsigned NOT NULL DEFAULT '0',
  `num_thumbs_down` int(10) unsigned NOT NULL DEFAULT '0',
  `image_rel_url` char(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'image back drop',
  `description` text COLLATE utf8_unicode_ci,
  `hours` text COLLATE utf8_unicode_ci COMMENT 'hours of operation, stored with format HHHH-HHHH:HHHH-HHHH:HHHH-HHHH:HHHH-HHHH:HHHH-HHHH:HHHH-HHHH:HHHH-HHHH',
  `phone_1` char(20) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'primary phone number',
  `email` char(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='vendor table' AUTO_INCREMENT=2 ;

--
-- Dumping data for table `vendor`
--

INSERT INTO `vendor` (`id`, `vendor_name`, `account_num`, `addr_1`, `addr_2`, `city`, `state`, `zip`, `country_code`, `price_range`, `vendor_website`, `num_thumbs_up`, `num_thumbs_down`, `image_rel_url`, `description`, `hours`, `phone_1`, `email`) VALUES
(0, 'Costco Wholesale', 0, '3800 North Central Expressway', '', 'Plano', 'TX', '75074-2221', 'US', '$$', 'www.costco.com', 9998, 2, 'costco.jpg', 'Costco Wholesale Corporation is an American membership-only warehouse club that provides a wide selection of merchandise. It is currently the largest membership-only warehouse club in the United States.', '1000-1800:1000-2030:1000-2030:1000-2030:1000-2030:1000-2030:0930-1800', '9722440003', '');

-- --------------------------------------------------------

--
-- Table structure for table `vendor_tag`
--

CREATE TABLE IF NOT EXISTS `vendor_tag` (
  `vendor_id` int(10) unsigned NOT NULL,
  `tag` char(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `deal_tag`
--
ALTER TABLE `deal_tag`
  ADD CONSTRAINT `tags_deal_fk` FOREIGN KEY (`deal_id`) REFERENCES `deals` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
