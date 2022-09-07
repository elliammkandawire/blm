-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 05, 2021 at 07:18 AM
-- Server version: 5.7.26
-- PHP Version: 7.3.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cvemhzzu_blm`
--

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

DROP TABLE IF EXISTS `item`;
CREATE TABLE IF NOT EXISTS `item` (
  `item_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_code` varchar(12) NOT NULL,
  `item_name` varchar(100) NOT NULL,
  `specification` varchar(250) NOT NULL,
  `type` varchar(100) NOT NULL,
  `category` varchar(100) NOT NULL,
  `unit` varchar(20) NOT NULL,
  `quantity` int(11) NOT NULL,
  `stock_used` int(11) NOT NULL DEFAULT '0',
  `daily_average_consumption` double GENERATED ALWAYS AS ((`stock_used` / 90)) STORED,
  `maximum_stock_level` int(11) GENERATED ALWAYS AS ((150 * `daily_average_consumption`)) STORED,
  `minimum_stock_level` int(11) GENERATED ALWAYS AS ((60 * `daily_average_consumption`)) STORED,
  `expiry_date` date NOT NULL,
  `expiry_remaining_days` int(11) GENERATED ALWAYS AS ((to_days(`expiry_date`) - to_days(`date_received`))) STORED,
  `expiry_status` varchar(120) NOT NULL DEFAULT 'none',
  `quantity_before_expiry` int(11) GENERATED ALWAYS AS ((`daily_average_consumption` * `expiry_remaining_days`)) STORED,
  `quantity_risk_expiry` int(11) GENERATED ALWAYS AS ((`quantity` - `quantity_before_expiry`)) STORED,
  `stock_value_expiry_risk` double GENERATED ALWAYS AS ((`quantity_risk_expiry` * `price`)) STORED,
  `date_received` datetime NOT NULL,
  `price` double NOT NULL,
  `total_price` double NOT NULL,
  `batch` varchar(20) NOT NULL,
  `GRN` int(11) NOT NULL,
  `team_code` int(11) NOT NULL,
  PRIMARY KEY (`item_id`),
  KEY `team_code` (`team_code`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`item_id`, `item_code`, `item_name`, `specification`, `type`, `category`, `unit`, `quantity`, `stock_used`, `expiry_date`, `expiry_status`, `date_received`, `price`, `total_price`, `batch`, `GRN`, `team_code`) VALUES
(2, '5454', 'Gloves', 'Gloves 45', 'Pack 30', 'Disposable Surgical Equipments - Surgical Dressings', '45', 140, 0, '2022-04-07', '114', '2021-09-23 03:29:35', 5460, 764400, '45', 456788444, 61205),
(3, 'KA000314', 'Acyclovir', 'Acyclovir Tables', 'Tablet/Capsule', 'Tablets/Capsules (Oral)', 'Tablets', 9613, 0, '2021-05-05', '', '2021-09-29 07:11:34', 4750, 0, '2818283', 96554, 10019),
(4, 'Ka0001', 'Acyclovir', 'Supplies for centers', 'Tablet/Capsule', 'Tablet/Capsule', 'Tablet', 67, 0, '2021-10-29', '114', '2021-09-29 08:55:52', 20, 1340, 'nb12', 5671, 61205),
(5, 'KA000010', 'Ampicillin', '250mg Capsule, 1000 caps', 'Tablet/Capsule', 'Tablet/Capsule', 'M(100)', 60, 0, '2022-11-15', '', '2021-10-09 12:09:22', 1059.99, 63599.4, 'BN150', 1469, 10001),
(7, 'KA000069', 'Zinc ', '20mg Dispersible Tablet, 100 tabs', 'Tablet/Capsule', 'Tablet/Capsule', 'M100', 308, 50, '2022-02-26', '114', '2021-10-26 14:23:55', 567.89, 174910.12, 'BXT1234', 4567, 61205),
(8, 'KA000056', 'Dihydroartemes 40mg/Piperaquine', '32mg tablet, 9 tabs', 'Tablet/Capsule', 'Tablet/Capsule', 'M(1000)', 238, 68, '2022-04-20', 'none', '2021-10-29 22:21:53', 878.99, 307646.5, 'BA346', 1234, 10001);

-- --------------------------------------------------------

--
-- Table structure for table `orderdetails`
--

DROP TABLE IF EXISTS `orderdetails`;
CREATE TABLE IF NOT EXISTS `orderdetails` (
  `order_detail_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_number` bigint(20) NOT NULL,
  `description` varchar(250) NOT NULL,
  `supplier_name` varchar(50) NOT NULL,
  `supplier_address` varchar(250) NOT NULL,
  `email` varchar(120) NOT NULL,
  `contact_details` varchar(250) DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'Pending',
  `tax` double NOT NULL,
  `tax_amount` double NOT NULL,
  `sub_total` double NOT NULL,
  `grand_total` double NOT NULL,
  `amount_paid` double NOT NULL DEFAULT '0',
  `balance` double GENERATED ALWAYS AS ((`amount_paid` - `grand_total`)) STORED,
  `payment_status` varchar(150) NOT NULL DEFAULT 'Not Paid',
  `team_code` int(11) NOT NULL,
  `order_date` datetime NOT NULL,
  PRIMARY KEY (`order_detail_id`),
  UNIQUE KEY `order_detail_id` (`order_detail_id`),
  UNIQUE KEY `order_number` (`order_number`),
  KEY `team_code` (`team_code`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `orderdetails`
--

INSERT INTO `orderdetails` (`order_detail_id`, `order_number`, `description`, `supplier_name`, `supplier_address`, `email`, `contact_details`, `status`, `tax`, `tax_amount`, `sub_total`, `grand_total`, `amount_paid`, `payment_status`, `team_code`, `order_date`) VALUES
(1, 210460, 'Please deliver them asap', 'Xparts', 'Post Office Box 30319\r\nBlantyre 3', 'mkandawiresangwani@gmail.com', NULL, 'Sent', 16.5, 825000, 5000000, 5825000, 5825000, 'Paid', 61205, '2021-09-22 10:55:35'),
(2, 727170, 'Sample 3', 'Sample 3', 'sample3', 'mkandawiresangwani@gmail.com', NULL, 'Pending', 10, 4000, 40000, 44000, 0, 'Not Paid', 61205, '2021-09-23 02:55:45'),
(3, 695923, 'Hygiene ', 'Globe Internet', '0998699333', 'lameck.mithi@banja.org.mw', NULL, 'Sent', 16.5, 2229.15, 13510, 15739.15, 0, 'Not Paid', 61205, '2021-09-23 03:04:52'),
(4, 369849, 'Sample by Chibwana', 'Sample by Chibwana', 'Sample by Chibwana', 'mkandawiresangwani@gmail.com', NULL, 'Pending', 16.5, 165, 1000, 1165, 0, 'Not Paid', 10019, '2021-09-23 03:25:57'),
(5, 315113, 'Sample by Chibwana', 'Sample by Chibwana', 'Sample by Chibwana', 'mkandawiresangwani@gmail.com', NULL, 'Sent', 16.5, 8167.5, 49500, 57667.5, 0, 'Not Paid', 10019, '2021-09-23 03:27:19'),
(6, 182675, 'ergh', 'CFAO', '0984664794', 'cfaoth@gmail.com', NULL, 'Pending', 16.5, 8584.29, 52026, 60610.29, 0, 'Not Paid', 10019, '2021-09-23 10:24:00'),
(7, 618287, 'order for centers', 'Pharmavet', 'Box 1854,', 'sanjay.singh@banja.org.mw', NULL, 'Pending', 1, 13.4, 1340, 1353.4, 0, 'Not Paid', 61205, '2021-09-29 08:34:54'),
(8, 355709, 'We need this as  soon as  possible', 'Pharma', 'Pharma med \r\nP.O Box 3456\r\nBlantyre 3', 'lindaninahumu@gmail.com', NULL, 'Sent', 2, 1096, 54800, 55896, 55896, 'Paid', 61205, '2021-10-25 09:30:34');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `order_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_name` varchar(50) NOT NULL,
  `specification` varchar(120) NOT NULL,
  `category` varchar(50) NOT NULL,
  `unit` varchar(50) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` double NOT NULL,
  `total_price` double NOT NULL,
  `order_detail_id` int(11) NOT NULL,
  PRIMARY KEY (`order_id`),
  KEY `order_detail_id` (`order_detail_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `item_name`, `specification`, `category`, `unit`, `quantity`, `price`, `total_price`, `order_detail_id`) VALUES
(1, 'Sample 1', 'just a sample test', 'Tablets/Capsules(Oral)', '10', 1000, 5000, 5000000, 1),
(2, 'Sample 3', 'Sample 3', 'Solutions', 'Tablet/Capsule', 100, 400, 40000, 2),
(3, 'Glovees', 'Detergent', 'Disposable Surgical Equipments - Surgical Dressing', '50', 60, 60, 3600, 3),
(4, 'Masks', 'Corona', 'Solutions', '55', 80, 67, 5360, 3),
(5, 'Sanitiser', '500ml', 'Pessaries and Suppositories', '60', 65, 70, 4550, 3),
(6, 'abc', 'abc', 'Inhalers', 'Tablet/Capsule', 2, 500, 1000, 4),
(7, 'abc', 'abc', 'Inhalers', 'Tablet/Capsule', 9, 5500, 49500, 5),
(8, 'Glovees', 'Detergent', 'Family Planning Products', '67', 78, 667, 52026, 6),
(9, 'acyclivir', '200mg,100tabs', 'Tablets/Capsules(Oral)', 'tablet', 67, 20, 1340, 7),
(10, 'Aspirin', '100 tablet', 'Tablets/Capsules(Oral)', '100M', 100, 548, 54800, 8);

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

DROP TABLE IF EXISTS `payment`;
CREATE TABLE IF NOT EXISTS `payment` (
  `paymet_id` int(11) NOT NULL AUTO_INCREMENT,
  `amount_paid` double NOT NULL,
  `date_paid` datetime NOT NULL,
  `order_detail_id` int(11) NOT NULL,
  PRIMARY KEY (`paymet_id`),
  KEY `order_detail_id` (`order_detail_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`paymet_id`, `amount_paid`, `date_paid`, `order_detail_id`) VALUES
(7, 3425000, '2021-10-31 17:30:04', 1),
(8, 2400000, '2021-10-31 17:32:42', 1),
(9, 55896, '2021-10-31 17:48:42', 8);

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

DROP TABLE IF EXISTS `product`;
CREATE TABLE IF NOT EXISTS `product` (
  `product_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_code` varchar(20) NOT NULL,
  `product_name` varchar(50) DEFAULT NULL,
  `specification` varchar(50) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`product_id`),
  UNIQUE KEY `product_code` (`product_code`)
) ENGINE=InnoDB AUTO_INCREMENT=236 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`product_id`, `product_code`, `product_name`, `specification`, `type`, `category`) VALUES
(1, 'KA000314', 'Acyclovir', '200mg Tab 100', 'Tablet/Capsule', 'Tablet/Capsule'),
(2, 'KA000001', 'Acyclovir', '200mg tablet, 30 tabs', 'Tablet/Capsule', 'Tablet/Capsule'),
(3, 'KA000315', 'Acyclovir', '400mg Tab 10', 'Tablet/Capsule', 'Tablet/Capsule'),
(4, 'KA000002', 'Acyclovir', '400mg tablet, 100 tabs', 'Tablet/Capsule', 'Tablet/Capsule'),
(5, 'KA000003', 'Albendazole', '200mg ChewableTablet, 1000 tabs', 'Tablet/Capsule', 'Tablet/Capsule'),
(6, 'KA000324', 'Albendazole', '200mg Tab 40', 'Tablet/Capsule', 'Tablet/Capsule'),
(7, 'KA000004', 'Allopurinol', '100mg Tablet, 100 tabs', 'Tablet/Capsule', 'Tablet/Capsule'),
(8, 'KA000005', 'Aminophylline', '100mg Tablet, 1000 tabs', 'Tablet/Capsule', 'Tablet/Capsule'),
(9, 'KA000007', 'Amlodipine', '10mg Tablet, 100 tabs', 'Tablet/Capsule', 'Tablet/Capsule'),
(10, 'KA000006', 'Amlodipine', '5mg Tablet, 100 tabs', 'Tablet/Capsule', 'Tablet/Capsule'),
(11, 'KA000008', 'Amoxicillin (Amoxil)', '250mg Capsule, 1000 caps', 'Tablet/Capsule', 'Tablet/Capsule'),
(12, 'KA000009', 'Amoxiclav (Augmentin)', '375mg Tablet, 100 tabs', 'Tablet/Capsule', 'Tablet/Capsule'),
(13, 'KA000010', 'Ampicillin', '250mg Capsule, 1000 caps', 'Tablet/Capsule', 'Tablet/Capsule'),
(14, 'KA000012', 'Aspirin', '300mg Tablet, 1000 tabs', 'Tablet/Capsule', 'Tablet/Capsule'),
(15, 'KA000011', 'Aspirin (Junior)', '75mg tablet, 1000 tabs', 'Tablet/Capsule', 'Tablet/Capsule'),
(16, 'KA000332', 'Atenolol', '50mg Tab 100', 'Tablet/Capsule', 'Tablet/Capsule'),
(17, 'KA000013', 'Atenolol', '50mg Tablet, 140 tabs', 'Tablet/Capsule', 'Tablet/Capsule'),
(18, 'KA000014', 'Azithromycin', '250mg Tablet, 6 tabs', 'Tablet/Capsule', 'Tablet/Capsule'),
(19, 'KA000015', 'Azithromycin', '500mg Tablet, 3 tabs', 'Tablet/Capsule', 'Tablet/Capsule'),
(20, 'KA000016', 'Captopril', '25mg Tablet, 100 tabs', 'Tablet/Capsule', 'Tablet/Capsule'),
(21, 'KA000017', 'Cefixime ', '200mg tablet, 100 tabs', 'Tablet/Capsule', 'Tablet/Capsule'),
(22, 'KA000020', 'Chloramphenicol', '250mg Capsule, 1000 caps', 'Tablet/Capsule', 'Tablet/Capsule'),
(23, 'KA000021', 'Chlorpheniramine (Piriton)', '4mg Tablet, 1000 tabs', 'Tablet/Capsule', 'Tablet/Capsule'),
(24, 'KA000022', 'Ciprofloxacin', '250mg Tablet, 100 tabs', 'Tablet/Capsule', 'Tablet/Capsule'),
(25, 'KA000023', 'Ciprofloxacin', '500mg Tablet, 100 tabs', 'Tablet/Capsule', 'Tablet/Capsule'),
(26, 'KA000024', 'Clomifene', '50mg Tablet, 50 tabs', 'Tablet/Capsule', 'Tablet/Capsule'),
(27, 'KA000025', 'Cold&Flu ', 'Combination flu tablets, 500 tabs', 'Tablet/Capsule', 'Tablet/Capsule'),
(28, 'KA000285', 'Cold&Flu ', 'combination tablet, 100 tabs', 'Tablet/Capsule', 'Tablet/Capsule'),
(29, 'KA000026', 'Co-trimoxazole (Bactrim)', '80/400 mg Tablet, 1000 tabs', 'Tablet/Capsule', 'Tablet/Capsule'),
(30, 'KA000027', 'Dexamethasone', '0.5mg Tablet, 1000 tabs', 'Tablet/Capsule', 'Tablet/Capsule'),
(31, 'KA000028', 'Diazepam', '5mg Tablet, 1000 tabs', 'Tablet/Capsule', 'Tablet/Capsule'),
(32, 'KA000349', 'Diazepam', '5mg Tablet, 100 tabs', 'Tablet/Capsule', 'Tablet/Capsule'),
(33, 'KA000303', 'Diclofenac', '100mg SR Tabs 100', 'Tablet/Capsule', 'Tablet/Capsule'),
(34, 'KA000029', 'Diclofenac', '50mg Tablet, 1000 tabs', 'Tablet/Capsule', 'Tablet/Capsule'),
(35, 'KA000301', 'Diclofenac', '50mg Tabs 100', 'Tablet/Capsule', 'Tablet/Capsule'),
(36, 'KA000030', 'Diclofenac SR', '100mg SR Tablet, 200 tabs', 'Tablet/Capsule', 'Tablet/Capsule'),
(37, 'KA000056', 'Dihydroartemes 40mg/Piperaquine', '32mg tablet, 9 tabs', 'Tablet/Capsule', 'Tablet/Capsule'),
(38, 'KA000355', 'Dihydroartemes/Piperaquine', 'Dihydroartemes/Piperaquine 20/160mg 6', 'Tablet/Capsule', 'Tablet/Capsule'),
(39, 'KA000356', 'Dihydroartemes/Piperaquine', 'Dihydroartemes/Piperaquine 80/640mg 6', 'Tablet/Capsule', 'Tablet/Capsule'),
(40, 'KA000327', 'Doxycycline', '100mg Cap, 1000', 'Tablet/Capsule', 'Tablet/Capsule'),
(41, 'KA000031', 'Doxycycline', '100mg Capsule, 100 caps', 'Tablet/Capsule', 'Tablet/Capsule'),
(42, 'KA000032', 'Erythromycin', '250mg Tablet, 1000 tabs', 'Tablet/Capsule', 'Tablet/Capsule'),
(43, 'KA000344', 'Erythromycin', '250mg Tablet, 100 tabs', 'Tablet/Capsule', 'Tablet/Capsule'),
(44, 'KA000033', 'Ferrous Sulphate', '200mg Tablet, 1000 tabs', 'Tablet/Capsule', 'Tablet/Capsule'),
(45, 'KA000304', 'Ferrous Sulphate', '200mg Tabs 100', 'Tablet/Capsule', 'Tablet/Capsule'),
(46, 'KA000284', 'Ferrous Sulphate+Folic Acid', 'Tab 1000', 'Tablet/Capsule', 'Tablet/Capsule'),
(47, 'KA000034', 'Flucloxacillin ', '250mg Capsule, 100 caps', 'Tablet/Capsule', 'Tablet/Capsule'),
(48, 'KA000282', 'Fluconazole', '200mg Caps 100', 'Tablet/Capsule', 'Tablet/Capsule'),
(49, 'KA000019', 'Frusemide', '40mg Tablet, 100 tabs', 'Tablet/Capsule', 'Tablet/Capsule'),
(50, 'KA000329', 'Glibenclamide', '5mg Tab1000', 'Tablet/Capsule', 'Tablet/Capsule'),
(51, 'KA000035', 'Glibenclamide', '5mg Tablet, 100 tabs', 'Tablet/Capsule', 'Tablet/Capsule'),
(52, 'KA000036', 'Griseofulvin', '125mg Tablet, 100 tabs', 'Tablet/Capsule', 'Tablet/Capsule'),
(53, 'KA000037', 'Hydrochlorothiazide', '25mg Tablet, 1000 tabs', 'Tablet/Capsule', 'Tablet/Capsule'),
(54, 'KA000071', 'Hyoscine Butyl-Bromide (Busc)', '10mg Tab100', 'Tablet/Capsule', 'Tablet/Capsule'),
(55, 'KA000038', 'Ibuprofen (Brufen)', '200mg Tablet, 1000 tabs', 'Tablet/Capsule', 'Tablet/Capsule'),
(56, 'KA000039', 'Ibuprofen (Brufen) in blister packs', '200mg tablet, blister, 100 tabs', 'Tablet/Capsule', 'Tablet/Capsule'),
(57, 'KA000040', 'Indomethacin (Indocid)', '25mg Capsule, 100 caps', 'Tablet/Capsule', 'Tablet/Capsule'),
(58, 'KA000041', 'Ketoconazole', '200mg tablet, 100 tabs', 'Tablet/Capsule', 'Tablet/Capsule'),
(59, 'KA000073', 'LA (lumefan+artemether)', '20/120 mgTab240', 'Tablet/Capsule', 'Tablet/Capsule'),
(60, 'KA000072', 'LA (lumefan+artemether)', '40/240 mgTab120', 'Tablet/Capsule', 'Tablet/Capsule'),
(61, 'KA000074', 'LA (lumefantr/artemether)', '80/480mg Tab60', 'Tablet/Capsule', 'Tablet/Capsule'),
(62, 'KA000042', 'Lisinopril', '5mg Tablet, 100', 'Tablet/Capsule', 'Tablet/Capsule'),
(63, 'KA000043', 'Loperamide (Imodium)', '2mg Capsule, 100 caps', 'Tablet/Capsule', 'Tablet/Capsule'),
(64, 'KA000044', 'Magnesium Trisilicate Compound ', 'Combination of Mg and Al Chewable Tablet, 1000 tab', 'Tablet/Capsule', 'Tablet/Capsule'),
(65, 'KA000045', 'Mefenamic acid', '250mg Tablet, 100 tabs', 'Tablet/Capsule', 'Tablet/Capsule'),
(66, 'KA000048', 'Metformin ', '500mg Tablet, 100 tabs', 'Tablet/Capsule', 'Tablet/Capsule'),
(67, 'KA000046', 'Methyldopa (Aldomet)', '250mg Tablet, 100 tabs', 'Tablet/Capsule', 'Tablet/Capsule'),
(68, 'KA000047', 'Metronidazole (Flagy)', '200mg Tab 1000', 'Tablet/Capsule', 'Tablet/Capsule'),
(69, 'KA000049', 'Misoprostal', '200mg Tablet, 30 tabs', 'Tablet/Capsule', 'Tablet/Capsule'),
(70, 'KA000050', 'Multivitamin', 'Combination product, 1000 tabs', 'Tablet/Capsule', 'Tablet/Capsule'),
(71, 'KA000051', 'Nalidixic Acid', '500mg Tablet, 500 tabs', 'Tablet/Capsule', 'Tablet/Capsule'),
(72, 'KA000052', 'Nifedipine', '10mg Tablet, 100 tabs', 'Tablet/Capsule', 'Tablet/Capsule'),
(73, 'KA000331', 'Norethisterone (Primolut-N)', '5mg Tab 500', 'Tablet/Capsule', 'Tablet/Capsule'),
(74, 'KA000053', 'Omeprazole', '20mg Capsule, 100 caps', 'Tablet/Capsule', 'Tablet/Capsule'),
(75, 'KA000322', 'Paracetamol 500mg + Ibuprofen 400mg', '500mg/400mg  Tab150', 'Tablet/Capsule', 'Tablet/Capsule'),
(76, 'KA000075', 'Paracetamol 500mg + Ibuprofen 400mg in blister pac', '500/400mg Tablet, 600  ', 'Tablet/Capsule', 'Tablet/Capsule'),
(77, 'KA000054', 'Paracetamol (Panadol)', '500mg Tablet, 1000 tabs', 'Tablet/Capsule', 'Tablet/Capsule'),
(78, 'KA000317', 'Paracetamol (Panadol)', '500mg Tabs 100', 'Tablet/Capsule', 'Tablet/Capsule'),
(79, 'KA000055', 'Paracetamol blister pack', '500mg Tab100', 'Tablet/Capsule', 'Tablet/Capsule'),
(80, 'KA000057', 'Phenobarbitone', '30mg Tablet, 1000 tabs', 'Tablet/Capsule', 'Tablet/Capsule'),
(81, 'KA000058', 'Praziquantel', '600mg Tablet, 100 tabs', 'Tablet/Capsule', 'Tablet/Capsule'),
(82, 'KA000059', 'Prednisolone', '5mg Tablet, 100 tabs', 'Tablet/Capsule', 'Tablet/Capsule'),
(83, 'KA000350', 'Prednisolone', '5mg Tablet, 1000 tabs', 'Tablet/Capsule', 'Tablet/Capsule'),
(84, 'KA000060', 'Promethazine Hydrochloride', '25mg Tablet, 1000 tabs', 'Tablet/Capsule', 'Tablet/Capsule'),
(85, 'KA000061', 'Propranolol', '40mg Tablet, 1000 tabs', 'Tablet/Capsule', 'Tablet/Capsule'),
(86, 'KA000062', 'Quinine', '300mg Tablet, 1000 tabs', 'Tablet/Capsule', 'Tablet/Capsule'),
(87, 'KA000063', 'Ranitidine', '150mg Tablet, 100 tabs', 'Tablet/Capsule', 'Tablet/Capsule'),
(88, 'KA000064', 'Salbutamol', '4mg Tablet, 1000 tabs', 'Tablet/Capsule', 'Tablet/Capsule'),
(89, 'KA000065', 'Sulphadoxine + Pyrimethamine (SP)', '500mg +25mg Tablet, 500 tabs', 'Tablet/Capsule', 'Tablet/Capsule'),
(90, 'KA000066', 'Vitamin B Complex ', 'Combination product, 1000 tabs', 'Tablet/Capsule', 'Tablet/Capsule'),
(91, 'KA000067', 'Vitamin B6 (Pyridoxine)', '25mg Tablet, 1000 tabs', 'Tablet/Capsule', 'Tablet/Capsule'),
(92, 'KA000068', 'Vitamin C (Ascorbic acid)', '125 or 250mg ChewableTablet, 1000 tabs', 'Tablet/Capsule', 'Tablet/Capsule'),
(93, 'KA000069', 'Zinc ', '20mg Dispersible Tablet, 100 tabs', 'Tablet/Capsule', 'Tablet/Capsule'),
(94, 'KA000139', 'Acyclovir Cream', '5% 10g Tube', 'Tube', 'Tube'),
(95, 'KA000140', 'Betamethasone Cream', '0.1% 15g Tube', 'Tube', 'Tube'),
(96, 'KA000141', 'Calamine Lotion', '100ml', 'Mls', 'Mls'),
(97, 'KA000312', 'Clotrimazole Cream', '1% 15g Tube', 'Tube', 'Tube'),
(98, 'KA000142', 'Clotrimazole Cream', '1% 20g Tube', 'Tube', 'Tube'),
(99, 'KA000143', 'Diclofenac Gel', '1% 20g Tube', 'Tube', 'Tube'),
(100, 'KA000144', 'Hydrocortisone Ung', '1% 15g Tube', 'Tube', 'Tube'),
(101, 'KA000145', 'Ketoconazole Cream', '2% 20g Tube', 'Tube', 'Tube'),
(102, 'KA000146', 'Miconazole Cream', '2% 15g Tube', 'Tube', 'Tube'),
(103, 'KA000311', 'Miconazole Cream', '2% 20g Tube', 'Tube', 'Tube'),
(104, 'KA000147', 'Podophylline paint', '15-25% 10ml', 'Bottle', 'Bottle'),
(105, 'KA000148', 'Povidone Iodine', '10% 100ml', 'Mls', 'Mls'),
(106, 'KA000286', 'Povidone Iodine', '10% 500ml', 'Mls', 'Mls'),
(107, 'KA000149', 'Silver Sulphadiazine', '1% 15g Tube', 'Tube', 'Tube'),
(108, 'KA000305', 'Silver Sulphadiazine', '1% 25g Tube', 'Tube', 'Tube'),
(109, 'KA000150', 'Whitfield (compndbenzoic acid)', 'Ung 20g', 'Tube', 'Tube'),
(110, 'KA000152', 'Zinc Oxide Ointment', '100g tube', 'Tube', 'Tube'),
(111, 'KA000151', 'Zinc Oxide Ointment', '500g Tub', 'Jar/Pot', 'Jar/Pot'),
(112, 'KA000076', 'Amoxycillin', '125mg/5ml Suspension, 100ml', 'Mls', 'Mls'),
(113, 'KA000077', 'Amoxicillin 125mg + clavulanic acid 31.5 mg ', '125mg+31.5mg Suspension, 100ml', 'Mls', 'Mls'),
(114, 'KA000078', 'Ampicillin', '125mg/5ml Suspension, 100ml', 'Mls', 'Mls'),
(115, 'KA000079', 'Cefuroxime', '125mg/5ml Suspension, 100ml', 'Mls', 'Mls'),
(116, 'KA000348', 'Cefuroxime Axetil Susp ', '125mg/5ml Suspension, 60ml', 'Mls', 'Mls'),
(117, 'KA000080', 'Chloramphenicol', '125mg/5ml Suspension, 100ml', 'Mls', 'Mls'),
(118, 'KA000081', 'Cotrimoxazole Syrup (Bactrim)', '240mg/5ml Suspension, 100ml', 'Mls', 'Mls'),
(119, 'KA000082', 'Erythromycin', '125mg/5ml Suspension, 100ml', 'Mls', 'Mls'),
(120, 'KA000083', 'Expectorant cough syrup', 'Combination Syrup, 100ml', 'Mls', 'Mls'),
(121, 'KA000084', 'Ferrous Salt Mixture (Iron)', '50mg/5ml Syrup, 100ml', 'Mls', 'Mls'),
(122, 'KA000279', 'LA (lumefantrine/artemether) ', '1080/180mg Suspension, 60ml', 'Mls', 'Mls'),
(123, 'KA000086', 'Liquid paraffin', 'Liquid paraffin B.P, 100ml', 'Mls', 'Mls'),
(124, 'KA000316', 'Liquid paraffin', 'Liquid paraffin B.P, 500ml', 'Mls', 'Mls'),
(125, 'KA000087', 'Magnesium Triscillicate Sulphate', 'Combination of Mg and Al Suspension, 100ml', 'Mls', 'Mls'),
(126, 'KA000088', 'Mefenamic  acid', '100mg/5ml, 100ml', 'Mls', 'Mls'),
(127, 'KA000089', 'Metronidazole Syrup (Flagyl)', '200mg/5ml Suspension, 100ml', 'Mls', 'Mls'),
(128, 'KA000090', 'Multivitamin ', 'Combination Syrup, 100ml', 'Mls', 'Mls'),
(129, 'KA000091', 'Nystatin Oral Drops', '100 000 IU Suspension (30ML)', 'Mls', 'Mls'),
(130, 'KA000092', 'Oral Rehydration Salts', 'WHO formula for 1L solution, 50 sachets', 'sachet', 'sachet'),
(131, 'KA000093', 'Paracetamol (Panadol)', '120mg/5ml Syrup, 100ml', 'Mls', 'Mls'),
(132, 'KA000094', 'Promethazine', ' 5mg/5ml Syrup, 100ml', 'Mls', 'Mls'),
(133, 'KA000310', 'Quinine', '150mg/5ml Suspension, 100ml', 'Mls', 'Mls'),
(134, 'KA000095', 'Quinine', '150mg/5ml Suspension, 60ml', 'Mls', 'Mls'),
(135, 'KA000096', 'Salbutamol', '2mg/5ml Syrup, 100ml', 'Mls', 'Mls'),
(136, 'KA000107', 'Dexamethasone', '0.1%  Eye/ear Drops (5mls)', 'Bottle', 'Bottle'),
(137, 'KA000108', 'Dexamethasone + Neomycin (Dexaneo)', '(0.1 / 0.35) % Eye/ear Drops (5mls)', 'Bottle', 'Bottle'),
(138, 'KA000109', 'Gentamycin', '0.3% Eye/ear Drops (5mls)', 'Bottle', 'Bottle'),
(139, 'KA000110', 'Tetracycline', '1% Eye ointment (4g tube)', 'Bottle', 'Bottle'),
(140, 'KA000111', 'Salbutamol', '0.1mg/dose, 200 metered dose inhaler', 'Each', 'Each'),
(141, 'KA000112', 'Adrenaline', '1mg/ml, 1ml, 10 ampoules', 'Ampoule', 'Ampoule'),
(142, 'KA000113', 'Aminophylline', '25mg/ml, 10ml, 10 ampoules', 'Ampoule', 'Ampoule'),
(143, 'KA000115', 'Ampicillin', ' 500mg PFR, vial', 'Vial', 'Vial'),
(144, 'KA000114', 'Ampicillin', '1g PFR, vial', 'Vial', 'Vial'),
(145, 'KA000116', 'Artesunate ', '30mg PFR with 50mg/ml, 0.5 ml Sodium Bicarbonate a', 'Vial', 'Vial'),
(146, 'KA000117', 'Artesunate ', '60mg PFR with 50mg/ml, 1ml Sodium Bicarbonate and ', 'Vial', 'Vial'),
(147, 'KA000118', 'Artesunate ', '120mg PFR with 50mg/ml,2ml Sodium Bicarbonate and ', 'Vial', 'Vial'),
(148, 'KA000119', 'Atropine Sulphate', '1mg/ml, 1ml, 10 ampoules\r\n', 'Ampoule', 'Ampoule'),
(149, 'KA000120', 'Benzathine Penicillin', '2.4mu PFR, vial', 'Vial', 'Vial'),
(150, 'KA000121', 'Benzyl penicillin (Cristapen)', ' 5 Mega PFR, vial\r\n', 'Vial', 'Vial'),
(151, 'KA000122', 'Ceftriaxone ', '1g PFR, vial\r\n', 'Vial', 'Vial'),
(152, 'KA000123', 'Chloramphenicol', '1g PFR, vial\r\n', 'Vial', 'Vial'),
(153, 'KA000124', 'Dexamethasone', ' 4mg/ml, 1ml Inj. 10 ampoules\r\n', 'Ampoule', 'Ampoule'),
(154, 'KA000319', 'Dextrose 50% Inj 50ml', 'Dextrose 50% Inj 50ml', 'Vial', 'Vial'),
(155, 'KA000126', 'Diazepam (Valium)', '5mg/ml, 2ml inj, 10 ampoules\r\n', 'Ampoule', 'Ampoule'),
(156, 'KA000127', 'Diclofenac ', ' 75mg/3ml inj, 10 ampoules\r\n', 'Ampoule', 'Ampoule'),
(157, 'KA000259', 'Ergometrine maleate', '0.5mg/ml, 1 ml inj., 10 ampoules\r\n', 'Ampoule', 'Ampoule'),
(158, 'KA000128', 'Gentamycin', '40mg/ml, 2ml inj, 10 ampoules\r\n', 'Ampoule', 'Ampoule'),
(159, 'KA000260', 'Hepatitis B Vaccine', 'Injection, 10 ampoules\r\n', 'Dose', 'Dose'),
(160, 'KA000129', 'Hydrocortisone', '100mg inj, vial\r\n', 'Vial', 'Vial'),
(161, 'KA000130', 'Lignocaine Hydrochloride', '2%, 30ml inj, 10 vials\r\n', 'Vial', 'Vial'),
(162, 'KA000326', 'Lignocaine Hydrochloride ', '2%, 30ml Vial eaches\r\n', 'Vial', 'Vial'),
(163, 'KA000131', 'Oxytocin (Pitocin)', '10iu/ml, 1ml inj, 10 ampoules\r\n', 'Ampoule', 'Ampoule'),
(164, 'KA000132', 'Promethazine Hydrochloride', '25mg/ml, 2ml inj, 10 ampoules\r\n', 'Ampoule', 'Ampoule'),
(165, 'KA000133', 'Quinine Sulphate Dihydrochloride', '300mg/ml, 2ml inj, 10 ampoules\r\n', 'Ampoule', 'Ampoule'),
(166, 'KA000134', 'Water for Injection (Sterile)', '10ml, 50 ampoules\r\n', 'Ampoule', 'Ampoule'),
(167, 'KA000274', 'Cmpd Sodium Lactate (Ringers)', '500ml\r\n', 'IV Bottle', 'IV Bottle'),
(168, 'KA000357', 'Comp Sodium Lactate (Ringer\'s) 1000ml', 'Comp Sodium Lactate (Ringer\'s) 1000ml\r\n', 'IV Bottle', 'IV Bottle'),
(169, 'KA000275', 'Glucose', 'IV 50% 100ml\r\n', 'IV Bottle', 'IV Bottle'),
(170, 'KA000273', 'Glucose', 'IV 5% 500ml\r\n', 'IV Bottle', 'IV Bottle'),
(171, 'KA000136', 'Haemaccel (Gelatin)', 'IV inj 500ml\r\n', 'IV Bottle', 'IV Bottle'),
(172, 'KA000137', 'Normal Saline / Sodium Chloride', 'IV 0.9% 1L\r\n', 'IV Bottle', 'IV Bottle'),
(173, 'KA000276', 'Normal Saline / Sodium Chloride', 'IV 0.9% 500ml\r\n', 'IV Bottle', 'IV Bottle'),
(174, 'KA000097', 'Distilled Water', 'Distilled water, 5L Can\r\n', 'Mls', 'Mls'),
(175, 'KA000098', 'Eusol solution', 'Chlorinated Lime and Boric Acid Solution B.P, 5000', 'Mls', 'Mls'),
(176, 'KA000296', 'Eusol solution', 'Chlorinated Lime and Boric Acid Solution B.P, 100m', 'Mls', 'Mls'),
(177, 'KA000341', 'Eusol solution', 'Chlorinated Lime and Boric Acid Solution B.P, 1 li', 'Each', 'Each'),
(178, 'KA000099', 'Glutaraldehyde (Cidex)', '2% Solution, 5L Can', 'Mls', 'Mls'),
(179, 'KA000100', 'Methylated Spirit', '95% ethyl alcohol and 5% methyl alcohol, 5L Can', 'Mls', 'Mls'),
(180, 'KA000101', 'Sodium Hypochlorite (Bleach/Jik)', '3.5% Solution, 5L Can', 'Mls', 'Mls'),
(181, 'KA000102', 'Anti-haemorrhoidal suppositories', 'Combination product, 10 supps', 'Suppository', 'Suppository'),
(182, 'KA000323', 'Anti-haemorrhoidal suppositories', 'Combination product, 5 supps\r\n', 'Suppository', 'Suppository'),
(183, 'KA000103', 'Clindamycin 100mg + Clotrimazole 200mg ', 'Combination product, 3 pess', 'Pessary', 'Pessary'),
(184, 'KA000325', 'Clindamycin 100mg/Clotrimaz 200mg  ', 'Combination product, 7 pess\r\n', 'Pessary', 'Pessary'),
(185, 'KA000104', 'Clotrimazole pessaries', '100mg Vaginal Tablets, 6 pess', 'Pessary', 'Pessary'),
(186, 'KA000105', 'Clotrimazole pessaries', '500mg Vaginal Tablets, 1 pess', 'Pessary', 'Pessary'),
(187, 'KA000280', 'Diclofenac ', '50mg suppository, 10 supps', 'Suppository', 'Suppository'),
(188, 'KA000153', 'Autoclave Tapes', '25mm X 50m roll\r\n', 'Roll', 'Roll'),
(189, 'KA000155', 'Bandage  W.O.W', 'Cotton 10cm roll, 12 rolls', 'Roll', 'Roll'),
(190, 'KA000333', 'Catgut 0/0  ', 'Absor Chrom Sut Riound body ndl ', 'Each', 'Each'),
(191, 'KA000156', 'Catgut 0/0  ', 'Absorbable chromic suture with cutting needle, 12 ', 'Each', 'Each'),
(192, 'KA000157', 'Catgut 1/0', 'Absorbable chromic suture with cutting needle, 12 ', 'Each', 'Each'),
(194, 'KA000159', 'Catgut 2/0', 'Absorbable chromic suture with cutting needle, 12 ', 'Each', 'Each'),
(195, 'KA000158', 'Catgut 1/0', 'Absorbable chromic suture with round bodied needle', 'Each', 'Each'),
(196, 'KA000160', 'Catgut 2/0', 'Absorbable chromic suture with round bodied needle', 'Each', 'Each'),
(197, 'KA000161', 'Catgut 3/0', 'Absorbable chromic suture with cutting needle, 12 ', 'Each', 'Each'),
(198, 'KA000162', 'Catgut 3/0', 'Absorbable chromic suture with round bodied needle', 'Each', 'Each'),
(199, 'KA000268', 'Catgut 4/0 ', 'Absorbable chromic suture with round bodied needle', 'Each', 'Each'),
(200, 'KA000164', 'Cotton Swabs', 'Pack of cotton balls', 'Pack', 'Pack'),
(201, 'KA000163', 'Cotton Wool', '500gm Roll', 'Roll', 'Roll'),
(202, 'KA000269', 'CPR Mask', 'CPR Mask\r\n', 'Each', 'Each'),
(203, 'KA000165', 'Crepe Bandage', '15 cm each\r\n', 'Each', 'Each'),
(204, 'KA000318', 'Disposable Latex Gloves', 'Large 100 pieces\r\n', 'Box', 'Box'),
(205, 'KA000166', 'Disposable Latex Gloves', 'Medium 100 pieces\r\n', 'Box', 'Box'),
(206, 'KA000168', 'Folley Catheter 16 gauge', '16G, 10 pieces\r\n', 'Each', 'Each'),
(207, 'KA000169', 'Folley Catheter 18 gauge', '18G, 10 pieces', 'Each', 'Each'),
(208, 'KA000170', 'Gauze Swabs', 'Non-Sterile 10cm x 10cm, 100 pieces\r\n', 'Each', 'Each'),
(209, 'KA000171', 'Gauze Vaseline/Paraffin', 'Sterile 10cm x 10cm, 36 pieces per pack\r\n', 'Each', 'Each'),
(210, 'KA000287', 'Gynaecological Cannula ea', 'Gynaecological Cannula ea\r\n', 'Each', 'Each'),
(211, 'KA000172', 'I.V Giving Set', 'With air inlet and 21G needle\r\n', 'Each', 'Each'),
(212, 'KA000173', 'Intravenous cannula 16 ', '16G (grey colour)\r\n', 'Each', 'Each'),
(213, 'KA000174', 'Intravenous cannula 18 ', '18G (green colour)', 'Each', 'Each'),
(214, 'KA000175', 'Intravenous cannula 20', '20G (pink colour)\r\n', 'Each', 'Each'),
(215, 'KA000176', 'Intravenous cannula 22', '22G (blue colour)\r\n', 'Each', 'Each'),
(216, 'KA000270', 'Intravenous cannula 24G  ', '24G (yellow colour)\r\n', 'Each', 'Each'),
(217, 'KA000179', 'IPAS Cannula', '4mm Manual Vacuum Aspiration\r\n', 'Each', 'Each'),
(218, 'KA000180', 'IPAS Cannula', '5mm Manual Vacuum Aspiration', 'Each', 'Each'),
(219, 'KA000181', 'IPAS Cannula', '6mm Manual Vacuum Aspiration\r\n', 'Each', 'Each'),
(220, 'KA000182', 'IPAS Cannula', '7mm Manual Vacuum Aspiration\r\n', 'Each', 'Each'),
(221, 'KA000183', 'IPAS Cannula', '8mm Manual Vacuum Aspiration\r\n', 'Each', 'Each'),
(222, 'KA000184', 'IPAS Cannula', '9mm Manual Vacuum Aspiration\r\n', 'Each', 'Each'),
(223, 'KA000185', 'IPAS Cannula', '10mm Manual Vacuum Aspiration\r\n', 'Each', 'Each'),
(224, 'KA000178', 'IPAS Cannula', 'Double valve with 3cc bottle & adaptor\r\n', 'Each', 'Each'),
(225, 'KA000177', 'IPAS Syringe', 'Single valve with 3cc bottle & adaptor\r\n', 'Each', 'Each'),
(226, 'KA000186', 'Mackintosh', 'Disposable sheet, 30m roll\r\n', 'Each', 'Each'),
(227, 'KA000271', 'Manual Resuscitator Adult (Ambu bag)', 'Manual Resuscitator Adult (Ambu bag)\r\n', 'Each', 'Each'),
(228, 'KA000187', 'Maternity Towel/Pad', 'Disposable, pack of 12\r\n', 'Pack', 'Pack'),
(229, 'KA000354', 'MSI Implant Ea', 'MSI Implant Ea\r\n', 'Each', 'Each'),
(230, 'KA000353', 'MSI IUD Insertion Kit', 'MSI IUD Insertion Kit\r\n', 'Kit', 'Kit'),
(231, 'KA000352', 'MSI MSL Kit', 'MSI MSL Kit\r\n', 'Kit', 'Kit'),
(232, 'KA000188', 'Needles disposable 19 Gauge', '19G, 100 pieces\r\n', 'Each', 'Each'),
(233, 'KA000189', 'Needles disposable 21 Gauge', '21G, 100 pieces\r\n', 'Each', 'Each'),
(234, 'KA000190', 'Needles disposable 23 Gauge', '23G, 100 pieces\r\n', 'Each', 'Each'),
(235, 'KA000194', 'Oropharyn airway (Guedel) size 2   ', 'Size2\r\n', 'Each', 'Each');

-- --------------------------------------------------------

--
-- Table structure for table `requisition`
--

DROP TABLE IF EXISTS `requisition`;
CREATE TABLE IF NOT EXISTS `requisition` (
  `requisition_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL DEFAULT 'Requisition to purchase Items',
  `status` varchar(60) NOT NULL DEFAULT 'Pending',
  `reply_status` varchar(50) NOT NULL DEFAULT 'Not Replied',
  `view_status` int(11) NOT NULL DEFAULT '0',
  `description` varchar(250) NOT NULL,
  `date_requested` datetime NOT NULL,
  `comment1` varchar(250) NOT NULL DEFAULT 'Waiting for Approval',
  `comment2` varchar(250) NOT NULL DEFAULT 'Waiting for Approval',
  `comment3` varchar(250) NOT NULL DEFAULT 'Waiting for Approval',
  `comment4` varchar(250) NOT NULL DEFAULT 'Waiting for Approval',
  `total_price` double NOT NULL,
  `date_replied` datetime DEFAULT NULL,
  `time_taken` double GENERATED ALWAYS AS ((to_days(`date_replied`) - to_days(`date_requested`))) STORED,
  `team_code` int(11) NOT NULL,
  PRIMARY KEY (`requisition_id`),
  KEY `team_code` (`team_code`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `requisition`
--

INSERT INTO `requisition` (`requisition_id`, `title`, `status`, `reply_status`, `view_status`, `description`, `date_requested`, `comment1`, `comment2`, `comment3`, `comment4`, `total_price`, `date_replied`, `team_code`) VALUES
(1, 'Requisition to purchase Items', 'Approved', 'Replied', 4, 'Amitrptyline \r\n25mg Tablet, 10 tablets	\r\nTablet/Capsule\r\nAND \r\n200mg Tab 100\r\n\r\n', '2021-09-29 12:17:12', 'Approved but waiting approvals from others', 'Approved waiting from my collegue', 'Approved waiting from Procurement for final approval', 'Final approve', 270000, '2021-10-25 00:05:55', 61205),
(2, 'Requisition to purchase Items', 'Denied', 'Replied', 2, 'New Stock', '2021-09-23 03:21:44', 'Requisition is denied', 'Requisition is denied as well', 'Denied waiting from Procurement for final approval', 'Denied for such such reason', 8548909, '2021-11-05 04:07:27', 61205),
(3, 'Requisition to purchase Items', 'Approved', 'Replied', 4, 'This is quarterly order', '2021-09-29 08:01:50', 'Approved but waiting approvals from others', 'Approved', 'Approved waiting from Procurement for final approval', 'Approved ', 8400, '2021-10-25 00:07:11', 61205),
(4, 'Requisition to purchase Items', 'Approved', 'Replied', 2, 'This is required ASAP', '2021-10-25 09:03:14', 'Approved but waiting from others to approve as well', 'Approve also waiting from others to approve as well', 'Waiting for Approval', 'Waiting for Approval', 54899, '2021-11-04 10:38:02', 61205);

-- --------------------------------------------------------

--
-- Table structure for table `requisition_details`
--

DROP TABLE IF EXISTS `requisition_details`;
CREATE TABLE IF NOT EXISTS `requisition_details` (
  `requisition_detail_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_code` varchar(10) NOT NULL,
  `item_name` varchar(40) NOT NULL,
  `category` varchar(120) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit` varchar(50) NOT NULL,
  `price` double NOT NULL,
  `specification` varchar(120) NOT NULL,
  `reply_status` varchar(50) NOT NULL DEFAULT 'Not Replied',
  `requisition_id` int(11) NOT NULL,
  PRIMARY KEY (`requisition_detail_id`),
  KEY `requisition_id` (`requisition_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `requisition_details`
--

INSERT INTO `requisition_details` (`requisition_detail_id`, `item_code`, `item_name`, `category`, `quantity`, `unit`, `price`, `specification`, `reply_status`, `requisition_id`) VALUES
(1, 'KA000362', 'Amitrptyline ', 'Tablets/Capsules(Oral)', 10, 'Tablet/Capsule', 4000, '25mg Tablet, 10 tablets', '4', 1),
(2, 'KA000314', 'Acyclovir', '', 100, 'Tablet/Capsule', 2300, '200mg Tab 100', '4', 1),
(3, '5656', 'Painkiller', 'Tablets/Capsules(Oral)', 700, '50', 5479, 'Drug', '2', 2),
(4, '7575', 'Gloves', 'Family Planning Products', 677, '100', 667, 'Detergent', '2', 2),
(5, '6778', 'Sanitiser', 'Other Laboratory Suppliers', 650, '500', 6557, '600ml', '2', 2),
(6, 'KA0001', 'Acyclovir', 'Tablets/Capsules(Oral)', 200, 'tablet', 17, '100mg tablet, 100 table', '4', 3),
(7, 'K0002', 'Albendazole', 'Tablets/Capsules(Oral)', 100, 'tablet', 50, '40mg tablet, 100 tabs', '4', 3),
(8, 'KA000339', 'Aspirin', 'Tablets/Capsules(Oral)', 100, '100M', 548.99, '100 tablet', 'Replied', 4);

-- --------------------------------------------------------

--
-- Table structure for table `stock_take`
--

DROP TABLE IF EXISTS `stock_take`;
CREATE TABLE IF NOT EXISTS `stock_take` (
  `stock_take_id` int(11) NOT NULL AUTO_INCREMENT,
  `opening_stock` int(11) NOT NULL DEFAULT '0',
  `closing_stock` int(11) NOT NULL DEFAULT '0',
  `physical_stock` int(11) NOT NULL DEFAULT '0',
  `variance` int(11) GENERATED ALWAYS AS ((`closing_stock` - `physical_stock`)) STORED,
  `remarks` varchar(250) NOT NULL DEFAULT 'None',
  `item_id` int(11) NOT NULL,
  PRIMARY KEY (`stock_take_id`),
  KEY `item_id` (`item_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `stock_take`
--

INSERT INTO `stock_take` (`stock_take_id`, `opening_stock`, `closing_stock`, `physical_stock`, `remarks`, `item_id`) VALUES
(2, 500, 140, 137, '', 2),
(3, 67, 67, 67, 'none', 4),
(4, 50, 0, 0, 'None', 5),
(5, 358, 0, 0, 'None', 7),
(6, 350, 0, 0, 'None', 8);

-- --------------------------------------------------------

--
-- Table structure for table `stock_usage`
--

DROP TABLE IF EXISTS `stock_usage`;
CREATE TABLE IF NOT EXISTS `stock_usage` (
  `stock_usage_id` int(11) NOT NULL AUTO_INCREMENT,
  `quantity_taken` int(11) NOT NULL,
  `description` varchar(250) NOT NULL,
  `item_id` int(11) NOT NULL,
  `date_taken` datetime NOT NULL,
  PRIMARY KEY (`stock_usage_id`),
  KEY `item_id` (`item_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `stock_usage`
--

INSERT INTO `stock_usage` (`stock_usage_id`, `quantity_taken`, `description`, `item_id`, `date_taken`) VALUES
(1, 10, 'Given to Mr Lameck to Distribute', 2, '2021-09-23 08:45:14'),
(2, 20, 'Usage for 20th September 2021', 3, '2021-09-29 07:14:20'),
(5, 50, 'Emergence ', 7, '2021-10-26 14:26:15'),
(6, 44, 'Emergency order', 8, '2021-10-29 22:44:30'),
(7, 26, 'Issued to clinic', 8, '2021-10-29 22:53:24'),
(8, 26, 'Issued to clinic', 8, '2021-10-29 22:53:28'),
(9, 26, 'Issued to clinic', 8, '2021-10-29 22:53:47'),
(10, 26, 'Issued to clinic', 8, '2021-10-29 22:53:50'),
(11, 42, 'Clinic issue', 8, '2021-10-29 23:05:39');

-- --------------------------------------------------------

--
-- Table structure for table `team`
--

DROP TABLE IF EXISTS `team`;
CREATE TABLE IF NOT EXISTS `team` (
  `team_id` int(11) NOT NULL AUTO_INCREMENT,
  `team_code` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`team_code`),
  UNIQUE KEY `team_code` (`team_code`),
  KEY `team_id` (`team_id`)
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `team`
--

INSERT INTO `team` (`team_id`, `team_code`, `name`) VALUES
(1, 10000, 'Lunzu Clinic'),
(2, 10001, 'Bangwe Clinic'),
(3, 10003, 'Ndirande Clinic'),
(4, 10004, 'N\'gabu Clinic'),
(5, 10005, 'Zingwangwa Clinic'),
(6, 10007, 'Sunnyside Clinic'),
(7, 10008, 'Bvumbwe Clinic'),
(8, 10009, 'Balaka Clinic'),
(9, 10011, 'Mangochi Clinic'),
(10, 10012, 'Mwanza Clinic'),
(11, 10013, 'Ntcheu Clinic'),
(12, 10014, 'Zomba Clinic'),
(13, 10016, 'Dedza Clinic'),
(14, 10017, 'Falls Clinic'),
(15, 10018, 'Kasungu Clinic'),
(16, 10019, 'Kawale Clinic'),
(17, 10020, 'Mchinji Clinic'),
(18, 10021, 'Mponela Clinic'),
(19, 10023, 'Salima Clinic'),
(20, 10024, 'Chitipa Centre'),
(21, 10025, 'Dwanga Clinic'),
(22, 10026, 'Karonga Clinic'),
(23, 10028, 'Mzuzu Clinic'),
(24, 10030, 'Nkhotakota Clinic'),
(25, 10031, 'Rumphi Clinic'),
(26, 10032, 'Area 25 Clinic'),
(72, 12000, 'Social Marketing'),
(27, 13010, 'N\'gabu dedicated Outreach'),
(28, 13011, 'Mangochi dedicated Outreach'),
(29, 13038, 'Salima Dedicated Outreach'),
(30, 13040, 'Area 25 Dedicated Outreach'),
(31, 13052, 'Thyolo Dedicated Outreach'),
(32, 13053, 'Dedza Dedicated Outreach'),
(33, 13054, 'Kasungu Dedicated Outreach'),
(34, 13055, 'Mzuzu Dedicated Outreach'),
(35, 13056, 'Ntcheu Dedicated Outreach'),
(36, 13057, 'Chiradzulu Dedicated Outreach'),
(37, 13072, 'Mponela Dedicated Outreach'),
(38, 13073, 'Nkhatabay Dedicated Outreach'),
(39, 13074, 'Karonga Dedicated Outreach'),
(40, 13075, 'Phalombe Dedicated Outreach Team'),
(41, 13076, 'Blantyre Dedicated Outreach Team'),
(42, 13077, 'Machinga Dedicated Outreach Team'),
(43, 13078, 'Balaka Dedicated Outreach Team'),
(44, 13079, 'Lilongwe Dedicated Outreach Team'),
(45, 13080, 'Mchinji Dedicated Outreach Team'),
(46, 13081, 'Dowa Dedicated Outreach Team'),
(47, 13082, 'Nkhotakota Dedicated Outreach Team'),
(48, 13084, 'Mzimba Dedicated Outreach Team'),
(50, 13085, 'ONSE 01 Karonga Dedicated Outreach Team'),
(51, 13086, 'ONSE 02 Lilongwe Dedicated Outreach Team'),
(52, 13087, 'ONSE 03 Kasungu Dedicated Outreach Team'),
(53, 13088, 'ONSE 04 Machinga Dedicated Outreach Team'),
(54, 13089, 'ONSE 05 Zomba Dedicated Outreach Team'),
(55, 13090, 'ONSE 06 Nkhotakota Dedicated Outreach Team'),
(56, 13091, 'ONSE 07 Salima Dedicated Outreach Team'),
(57, 13092, 'ONSE 08 Balaka Dedicated Outreach Team'),
(58, 13093, 'ONSE 09 Dowa Dedicated Outreach Team'),
(59, 13094, 'ONSE 10 Mulanje Dedicated Outreach Team'),
(60, 13095, 'ONSE 11 Chitipa Dedicated Outreach Team'),
(61, 13096, 'ONSE 12 Lilongwe Nested'),
(62, 13097, 'ONSE 13 Kasungu Nested'),
(63, 13098, 'ONSE 14 Machinga Nested'),
(64, 13099, 'ONSE 15 Zomba Nested'),
(65, 13100, 'ONSE 16 Karonga Nested'),
(66, 13101, 'ONSE 17 Mulanje Nested'),
(67, 13102, 'ONSE 18 Dowa Nested'),
(68, 13103, 'ONSE 19 Balaka Nested'),
(69, 13104, 'ONSE 20 Balaka Nested'),
(70, 13105, 'ONSE 21 Nkhotakota Nested'),
(71, 13106, 'ONSE 22 Chitipa Nested'),
(49, 61205, 'Warehouse');

-- --------------------------------------------------------

--
-- Table structure for table `transfer`
--

DROP TABLE IF EXISTS `transfer`;
CREATE TABLE IF NOT EXISTS `transfer` (
  `transfer_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_code` varchar(12) NOT NULL,
  `item_name` varchar(50) NOT NULL,
  `quantity` int(11) NOT NULL,
  `source` int(11) NOT NULL,
  `destination` int(11) NOT NULL,
  `specification` varchar(120) NOT NULL,
  `date_requested` datetime NOT NULL,
  PRIMARY KEY (`transfer_id`),
  KEY `item_code` (`item_code`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `transfer`
--

INSERT INTO `transfer` (`transfer_id`, `item_code`, `item_name`, `quantity`, `source`, `destination`, `specification`, `date_requested`) VALUES
(1, '5454', 'Gloves', 300, 61205, 10009, 'Gloves', '2021-09-23 03:32:47'),
(2, '5454', 'Gloves', 50, 61205, 10016, 'Gloves', '2021-09-23 10:26:13'),
(3, 'KA000314', 'Acyclovir', 200, 10019, 10025, 'Transfer to Kawale, GRN or stransfer note', '2021-09-29 07:15:55'),
(4, 'KA000314', 'Acyclovir', 167, 10019, 61205, 'needed urgently', '2021-09-29 15:13:59');

-- --------------------------------------------------------

--
-- Table structure for table `upload`
--

DROP TABLE IF EXISTS `upload`;
CREATE TABLE IF NOT EXISTS `upload` (
  `file_id` int(11) NOT NULL AUTO_INCREMENT,
  `file_name` varchar(255) NOT NULL,
  `size` int(11) NOT NULL,
  `date_uploaded` datetime NOT NULL,
  `team_code` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`file_id`),
  KEY `upload_ibfk_1` (`team_code`),
  KEY `upload_ibfk_2` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `upload`
--

INSERT INTO `upload` (`file_id`, `file_name`, `size`, `date_uploaded`, `team_code`, `user_id`) VALUES
(1, 'Sample of document upload.docx', 11389, '2021-09-22 11:16:17', 61205, 18),
(2, 'Sample of document upload 2.docx', 11505, '2021-09-22 11:18:51', 61205, 18),
(3, 'Sample of document upload 3.docx', 11505, '2021-09-23 01:25:11', 10019, 21),
(4, 'BLM.User.Guide.docx', 2272998, '2021-09-23 03:07:38', 61205, 18),
(5, 'Sample of document uploadD.docx', 11389, '2021-09-29 15:10:46', 10019, 21);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(40) NOT NULL,
  `surname` varchar(40) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(250) NOT NULL,
  `user_type` varchar(40) NOT NULL,
  `date_created` datetime NOT NULL,
  `team_code` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `team_code` (`team_code`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `firstname`, `surname`, `username`, `password`, `user_type`, `date_created`, `team_code`) VALUES
(5, 'John', 'Doe', 'JohnDoe', '123456', 'procurement', '2021-07-24 10:31:38', 61205),
(6, 'Jane', 'Doe', 'JaneDoe', '123456', 'warehouse', '2021-07-24 10:31:38', 61205),
(8, 'Mercy', 'Phiri', 'MercyPhiri', '123456', 'finance', '2021-07-24 10:31:38', 10028),
(9, 'Martin', 'Jones', 'Admin', '123456', 'admin', '2021-08-12 00:00:00', 61205),
(10, 'Kelvin', 'Phiri', 'Kelvin Phiri', '123456', 'user_team', '2021-08-12 01:39:33', 10028),
(11, 'Peter', 'Banda', 'Manager', '123456', 'line_manager', '2021-09-06 13:30:38', 61205),
(14, 'Stephen', 'Nkhoma', 'StephenNkhoma', '123456', 'user_team', '2021-09-20 17:05:23', 10001),
(15, 'Samuel ', 'Kapito', 'SKapito', 'BLM123*', 'line_manager', '2021-09-22 10:15:20', 61205),
(16, 'Evelyn', 'Chatsalira', 'EChatsalira', 'BLM123*', 'finance', '2021-09-22 10:18:13', 10028),
(17, 'Mayankho', 'Dana', 'MDana', 'BLM123*', 'warehouse', '2021-09-22 10:20:46', 61205),
(18, 'Jonathan', 'Mwafongo', 'JMwafongo', 'BLM123*', 'procurement', '2021-09-22 10:22:28', 61205),
(19, 'Sanjy', 'Singh', 'SSingh', 'BLM123*', 'line_manager', '2021-09-22 10:24:54', 61205),
(20, 'Chisomo', 'Chibwana', 'CChibwana', 'BLM123*', 'team_procurement', '2021-09-22 10:26:20', 10019),
(21, 'Kawale', 'Clinic', 'KClinic', 'BLM123*', 'user_team', '2021-09-22 10:30:52', 10019),
(22, 'Davie', 'Kambiya', 'DKambiya', 'BLM123*', 'team_finance', '2021-09-22 10:35:08', 61205),
(23, 'Timothy', 'Mlauzi', 'TMlauzi', 'BLM123*', 'line_manager', '2021-09-27 07:40:29', 10019),
(24, 'Ken', 'Tomoka', 'KTomoka', 'BLM123*', 'user_team', '2021-09-27 07:41:17', 10019),
(25, 'Budget', 'Holder', 'BudgetHolder', '123456', 'budget_holder', '2021-10-16 15:15:00', 61205),
(26, 'Finance', 'Director', 'FinanceDirector', '123456', 'finance_director', '2021-10-24 23:06:21', 61205),
(27, 'Procurement', 'Officer', 'ProcurementOfficer', '123456', 'procurement_officer', '2021-10-25 00:00:27', 61205);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `item`
--
ALTER TABLE `item`
  ADD CONSTRAINT `item_ibfk_1` FOREIGN KEY (`team_code`) REFERENCES `team` (`team_code`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `orderdetails`
--
ALTER TABLE `orderdetails`
  ADD CONSTRAINT `orderDetails_ibfk_1` FOREIGN KEY (`team_code`) REFERENCES `team` (`team_code`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`order_detail_id`) REFERENCES `orderdetails` (`order_detail_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`order_detail_id`) REFERENCES `orderdetails` (`order_detail_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `requisition`
--
ALTER TABLE `requisition`
  ADD CONSTRAINT `requisition_ibfk_1` FOREIGN KEY (`team_code`) REFERENCES `team` (`team_code`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `requisition_details`
--
ALTER TABLE `requisition_details`
  ADD CONSTRAINT `requisition_details_ibfk_1` FOREIGN KEY (`requisition_id`) REFERENCES `requisition` (`requisition_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `stock_take`
--
ALTER TABLE `stock_take`
  ADD CONSTRAINT `stock_take_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `item` (`item_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `stock_usage`
--
ALTER TABLE `stock_usage`
  ADD CONSTRAINT `stock_usage_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `item` (`item_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `upload`
--
ALTER TABLE `upload`
  ADD CONSTRAINT `upload_ibfk_1` FOREIGN KEY (`team_code`) REFERENCES `team` (`team_code`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `upload_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
