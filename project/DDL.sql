/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

CREATE TABLE IF NOT EXISTS `cart` (
  `cart_id` int(11) NOT NULL AUTO_INCREMENT,
  `total_price` int(11) DEFAULT NULL,
  PRIMARY KEY (`cart_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `customer` (
  `customer_id` int(11) NOT NULL AUTO_INCREMENT,
  `img_id` varchar(32) DEFAULT NULL,
  `account` varchar(30) DEFAULT NULL,
  `password` varchar(30) DEFAULT NULL,
  `role` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`customer_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `item` (
  `item_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_name` varchar(64) DEFAULT NULL,
  `desc` varchar(1024) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `rrp` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `img` varchar(32) DEFAULT NULL,
  `date_added` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `availability` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`item_id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `orders` (
  `order_id` int(11) NOT NULL AUTO_INCREMENT,
  `total_price` int(11) DEFAULT NULL,
  `shipping_name` varchar(64) DEFAULT NULL,
  `shipping_address` varchar(64) DEFAULT NULL,
  `shipping_phone` varchar(16) DEFAULT NULL,
  `shipping_email` varchar(30) DEFAULT NULL,
  `created` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `buy` (
  `order_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  PRIMARY KEY (`order_id`),
  KEY `customer_id` (`customer_id`),
  CONSTRAINT `buy_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`),
  CONSTRAINT `buy_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `payment_info` (
  `cardnum` varchar(16) NOT NULL,
  `expire_date` date DEFAULT NULL,
  `cardholder` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`cardnum`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `cart_contain` (
  `cart_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL DEFAULT '0',
  `quantity` int(11) DEFAULT NULL,
  PRIMARY KEY (`cart_id`,`item_id`),
  KEY `item_FK` (`item_id`),
  CONSTRAINT `cart_FK` FOREIGN KEY (`cart_id`) REFERENCES `cart` (`cart_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `item_FK` FOREIGN KEY (`item_id`) REFERENCES `item` (`item_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `cart_store` (
  `cart_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  PRIMARY KEY (`cart_id`,`customer_id`),
  KEY `customer_id` (`customer_id`),
  CONSTRAINT `cart_store_ibfk_1` FOREIGN KEY (`cart_id`) REFERENCES `cart` (`cart_id`),
  CONSTRAINT `cart_store_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `contain` (
  `cart_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  PRIMARY KEY (`cart_id`,`order_id`,`item_id`),
  KEY `order_id` (`order_id`),
  CONSTRAINT `contain_ibfk_1` FOREIGN KEY (`cart_id`) REFERENCES `cart` (`cart_id`),
  CONSTRAINT `contain_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `item_category` (
  `item_id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(64) DEFAULT NULL,
  KEY `item_id` (`item_id`),
  CONSTRAINT `item_category_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `item` (`item_id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `item_tags` (
  `item_id` int(11) NOT NULL AUTO_INCREMENT,
  `tag` varchar(64) DEFAULT NULL,
  KEY `item_id` (`item_id`),
  CONSTRAINT `item_tags_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `item` (`item_id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `order_contain` (
  `order_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  PRIMARY KEY (`order_id`,`item_id`),
  KEY `item_foreign` (`item_id`),
  CONSTRAINT `item_foreign` FOREIGN KEY (`item_id`) REFERENCES `item` (`item_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `order_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `own` (
  `customer_id` int(11) NOT NULL,
  `cardnum` varchar(16) NOT NULL,
  PRIMARY KEY (`customer_id`,`cardnum`),
  KEY `cardnum` (`cardnum`),
  CONSTRAINT `own_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`),
  CONSTRAINT `own_ibfk_2` FOREIGN KEY (`cardnum`) REFERENCES `payment_info` (`cardnum`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `pay` (
  `order_id` int(11) NOT NULL,
  `cardnum` varchar(16) DEFAULT NULL,
  PRIMARY KEY (`order_id`),
  KEY `cardnum` (`cardnum`),
  CONSTRAINT `pay_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  CONSTRAINT `pay_ibfk_2` FOREIGN KEY (`cardnum`) REFERENCES `payment_info` (`cardnum`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO `customer` (`customer_id`, `img_id`, `account`, `password`, `role`) VALUES
	(1, '1.jpg', 'a@email.com', '12345678', 'member'),
	(2, '2.jfif', 'b', '0001', NULL),
	(3, '3.jpg', 'c', '0010', NULL),
	(4, NULL, 'admin@email.com', 'admin', 'admin'),
	(5, NULL, 'example@email.com', 'example', 'member'),
	(6, NULL, 'abc@gmail.com', '12345678', 'member'),
	(7, NULL, '40947010S@ntnu.edu.tw', '40947010S', 'member');



INSERT INTO `item` (`item_id`, `item_name`, `desc`, `price`, `rrp`, `quantity`, `img`, `date_added`, `availability`) VALUES
	(1, 'Smart Watch', '<p>Unique watch made with stainless steel, ideal for those that prefer interative watches.</p>\r\n<h3>Features</h3>\r\n<ul>\r\n<li>Powered by Android with built-in apps.</li>\r\n<li>Adjustable to fit most.</li>\r\n<li>Long battery life, continuous wear for up to 2 days.</li>\r\n<li>Lightweight design, comfort on your wrist.</li>\r\n</ul>', 30, 0, 15, 'watch.jpg', '2019-03-13 17:55:22', NULL),
	(2, 'Wallet', '', 15, 20, 10, 'wallet.jpg', '2019-03-13 18:52:49', NULL),
	(3, 'Headphones', '', 20, 0, 23, 'headphones.jpg', '2019-03-13 18:47:56', NULL),
	(4, 'Digital Camera', '', 70, 0, 7, 'camera.jpg', '2019-03-13 17:42:04', NULL),
	(5, 'white T-shirt', NULL, 20, 0, 20, 'white_T-shirt.jpg', '2022-06-06 14:06:22', NULL),
	(6, 'Men\'s Jogger Pants', NULL, 50, 0, 15, 'JoggerPants.jpg', '2022-06-06 14:14:53', NULL),
	(7, 'black T-shirt', NULL, 20, 0, 20, 'black_T-shirt.jpg', '2022-06-06 14:20:51', NULL),
	(8, 'Woman\'s jeans', NULL, 45, 0, 20, 'jeans.jpg', '2022-06-06 14:25:00', NULL),
	(9, 'Men\'s Straight Pants', NULL, 50, 0, 30, 'straightpants.jpg', '2022-06-06 14:27:56', NULL),
	(10, 'Men\'s Pencil Pants', NULL, 38, 0, 10, 'pencilpantsmen.jpg', '2022-06-06 14:36:54', NULL),
	(11, 'Men\'s Sport Pants', NULL, 43, 0, 26, 'sportpantsmen.jpg', '2022-06-06 14:37:01', NULL),
	(12, 'Men\'s Cropped Pants', NULL, 50, 0, 18, 'croppedpantsmen.jpg', '2022-06-06 14:37:07', NULL),
	(13, 'knicker', NULL, 29, 0, 27, 'knicker.jpg', '2022-06-06 14:37:19', NULL),
	(14, 'Thong', NULL, 25, 0, 39, 'thong.jpg', '2022-06-06 14:37:23', NULL),
	(15, 'Boxers', NULL, 25, 0, 15, 'boxer.jpg', '2022-06-06 14:37:27', NULL),
	(16, 'leggings', NULL, 20, 0, 18, 'leggings.jpg', '2022-06-06 14:37:32', NULL),
	(17, 'men white down jacket ', NULL, 20, 0, 13, 'white_down_jacket_men.jpg', '2022-06-06 14:37:37', NULL),
	(18, 'men blue down jacket', NULL, 20, 0, 9, 'blue_down_jacket_men.jpg', '2022-06-06 14:37:42', NULL),
	(19, 'men blue blazer', NULL, 30, 0, 15, 'blue_blazer_men.jpg', '2022-06-06 14:37:50', NULL),
	(20, 'men black suit jacket', NULL, 30, 0, 10, 'black_suit_jacket_men.jpg', '2022-06-06 14:37:56', NULL),
	(21, 'Men\'s jeans', NULL, 48, 0, 10, 'jeans_men.jpg', '2022-06-06 14:54:35', NULL),
	(22, 'Woman\'s Pencil Pants', NULL, 38, 0, 23, 'pencilpantswoman.jpg', '2022-06-06 15:12:57', NULL),
	(23, 'women white down jacket', NULL, 36, 0, 18, 'white_down_jacket_women.jpg', '2022-06-06 15:14:48', NULL),
	(24, 'woman\'s Sport Pants', NULL, 43, 0, 24, 'sportpantswoman.jpg', '2022-06-06 15:17:19', NULL),
	(25, 'women blue down jacket', NULL, 20, 0, 21, 'blue_down_jacket_women.jpeg', '2022-06-06 15:18:27', NULL),
	(26, 'women blue blazer', NULL, 31, 0, 10, 'blue_blazer_women.png', '2022-06-06 15:18:55', NULL),
	(27, 'Woman\'s Cropped Pants', NULL, 50, 0, 30, 'croppedpantswoman.jpg', '2022-06-06 15:21:12', NULL),
	(28, 'Woman\'s Jogger Pants', NULL, 53, 0, 20, 'joggerpantswoman.jpg', '2022-06-06 15:29:11', NULL),
	(29, 'Woman\'s Straight Pants', NULL, 50, 0, 0, 'straightpantswoman.jpg', '2022-06-06 15:37:10', NULL);



INSERT INTO `item_category` (`item_id`, `category`) VALUES
	(5, 'T-shirt'),
	(5, 'women'),
	(5, 'men'),
	(7, 'T-shirt'),
	(7, 'women'),
	(7, 'men'),
	(17, 'jacket'),
	(18, 'jacket'),
	(17, 'men'),
	(18, 'men'),
	(19, 'men'),
	(19, 'jacket'),
	(20, 'men'),
	(20, 'jacket'),
	(23, 'women'),
	(6, 'pants'),
	(25, 'women'),
	(26, 'women'),
	(6, 'men'),
	(23, 'jacket'),
	(25, 'jacket'),
	(26, 'jacket'),
	(28, 'woman'),
	(28, 'pants'),
	(8, 'jeans'),
	(8, 'women'),
	(9, 'pants'),
	(9, 'men'),
	(10, 'pants'),
	(11, 'men'),
	(10, 'men'),
	(11, 'pants'),
	(12, 'men'),
	(12, 'pants'),
	(13, 'underwear'),
	(14, 'underwear'),
	(15, 'underwear'),
	(16, 'underwear'),
	(21, 'jeans'),
	(21, 'men'),
	(22, 'pants'),
	(22, 'women'),
	(24, 'women'),
	(24, 'pants'),
	(27, 'women'),
	(27, 'pants'),
	(29, 'woman'),
	(29, 'pants'),
	(13, 'women'),
	(14, 'men'),
	(15, 'men'),
	(16, 'women');



INSERT INTO `item_tags` (`item_id`, `tag`) VALUES
	(5, 'white'),
	(7, 'black'),
	(20, 'black'),
	(19, 'blue'),
	(17, 'white'),
	(18, 'blue'),
	(23, 'white'),
	(25, 'blue'),
	(26, 'blue'),
	(6, 'brown'),
	(8, 'blue'),
	(9, 'black'),
	(10, 'black'),
	(11, 'black'),
	(12, 'black'),
	(13, 'pink'),
	(14, 'blue'),
	(15, 'gray'),
	(16, 'black'),
	(21, 'blue'),
	(22, 'green'),
	(24, 'black'),
	(27, 'white'),
	(28, 'green');



INSERT INTO `orders` (`order_id`, `total_price`, `shipping_name`, `shipping_address`, `shipping_phone`, `shipping_email`, `created`) VALUES
	(1, 103, 'lisa', 'U.K.', '0123456789', 'monalisa@gmail.com', '2022-06-12 00:22:50'),
	(2, 70, 'Evan', 'Taiwan', '0111111119', 'evan123@gmail.com', '2022-06-12 00:22:50'),
	(3, 100, 'vivi', 'MarsStreet', '0912345678', 'avs@gmail.com', '2022-06-12 00:22:50'),
	(8, 200, '海綿寶寶', '深海大鳳梨裡', '0800092000', 'jijijiji@kkk.com', '2022-06-12 00:22:50'),
	(9, 200, '謝沅廷', 'road', '12345678', '40947010S@ntnu.edu.tw', '2022-06-12 00:22:50'),
	(10, 200, '老鐵', 'road', '110', '123@gmail.com', '2022-06-12 00:22:50'),
	(11, 200, '牛逼', 'abs', '113', 'gooooooood123', '2022-06-12 00:22:50'),
	(12, 100, 'vivi', 'MarsStreet', '0912345678', 'avs@gmail.com', '2022-06-13 18:15:22'),
	(13, 200, '1', '1', '1', '1', '2022-06-13 18:15:30'),
	(14, 221, 'abcd', 'efg', '12345', 'hijk', '2022-06-13 18:18:03'),
	(15, 51, 'pipipi', 'asdgh', '123', 'jijijij@gmail.com', '2022-06-13 21:25:49'),
	(16, 103, 'Sean', 'Yeet', '0912345678', 'a@email.com', '2022-06-13 21:37:41'),
	(17, 31, 'Egg', 'street', '0909199299', '40947010S@ntnu.edu.tw', '2022-06-14 14:56:16'),
	(35, 70, 'test2', 'test2 town', '12345', 'aaa', '2022-06-14 21:11:30'),
	(37, 70, 'test100', 'aaa', '23', 'asd', '2022-06-14 21:13:52'),
	(38, 75, 'QQQ', 'haha town', '123', 'asd@gmail.com', '2022-06-14 22:31:11'),
	(39, 50, 'Potato', 'SuperBowlstreet', '412-5252', 'a@email.com', '2022-06-15 09:13:13'),
	(40, 31, 'Bubble', '汀洲路', '0800', 'example@email.com', '2022-06-15 09:18:28'),
	(41, 31, 'Potato', 'abs', '110', 'example@email.com', '2022-06-15 09:22:29'),
	(42, 31, '呱呱', '荷葉上', '666', 'example@email.com', '2022-06-15 09:24:39'),
	(43, 31, '呱呱呱', '草地', '666', 'example@email.com', '2022-06-15 09:26:11');


INSERT INTO `order_contain` (`order_id`, `item_id`, `quantity`) VALUES
	(1, 27, 1),
	(1, 28, 1),
	(37, 25, 1),
	(37, 27, 1),
	(38, 14, 1),
	(38, 27, 1),
	(39, 27, 1),
	(40, 26, 1),
	(41, 26, 1),
	(42, 26, 1),
	(43, 26, 1);

INSERT INTO `payment_info` (`cardnum`, `expire_date`, `cardholder`) VALUES
	('123', '2025-06-06', 'monalisa'),
	('456', '2072-06-06', 'Lady with an Ermine'),
	('789', '2030-06-06', 'Evan');

INSERT INTO `own` (`customer_id`, `cardnum`) VALUES
	(1, '123'),
	(2, '456'),
	(3, '789');



INSERT INTO `pay` (`order_id`, `cardnum`) VALUES
	(1, '123'),
	(2, '789');





INSERT INTO `buy` (`order_id`, `customer_id`) VALUES
	(1, 1),
	(39, 1),
	(2, 3),
	(43, 5);

INSERT INTO `cart` (`cart_id`, `total_price`) VALUES
	(1, 103),
	(2, 0),
	(3, 70);



INSERT INTO `cart_store` (`cart_id`, `customer_id`) VALUES
	(1, 1),
	(2, 2),
	(3, 3);



INSERT INTO `contain` (`cart_id`, `order_id`, `item_id`, `quantity`) VALUES
	(1, 1, 8, 1),
	(1, 1, 25, 2),
	(3, 2, 4, 1);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
