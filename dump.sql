-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Apr 20, 2018 at 03:08 PM
-- Server version: 5.6.38
-- PHP Version: 7.2.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `features`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` int(11) NOT NULL,
  `token` varchar(32) NOT NULL,
  `purchase` varchar(64) NOT NULL,
  `email` varchar(255) NOT NULL,
  `requests` int(10) NOT NULL,
  `package` int(1) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `token`, `purchase`, `email`, `requests`, `package`, `timestamp`) VALUES
(9, '8b5ca01e87800bafbabd409e068cf996', 'ASV-QAP3-LNXJ-E7WJ-YACH-NMQ7', 'commention@gmail.com', 0, 0, '2018-04-11 12:12:25');

-- --------------------------------------------------------

--
-- Table structure for table `queries`
--

CREATE TABLE `queries` (
  `id` int(11) NOT NULL,
  `keyword` varchar(64) NOT NULL,
  `metaphone` varchar(64) NOT NULL,
  `content` text NOT NULL,
  `result` text NOT NULL,
  `account` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `queries`
--

INSERT INTO `queries` (`id`, `keyword`, `metaphone`, `content`, `result`, `account`, `timestamp`) VALUES
(1, 'iphone x', 'IFNKS', '[\"https:\\/\\/rover.ebay.com\\/rover\\/1\\/711-53200-19255-0\\/1?icep_ff3=2&amp;pub=5574933636&amp;toolid=10001&amp;campid=5336728181&amp;customid=&amp;mpre=https:\\/\\/www.ebay.com\\/p\\/Apple-iPhone-X-256GB-Space-Gray-Unlocked-A1901-GSM\\/239160993\",\"https:\\/\\/rover.ebay.com\\/rover\\/1\\/711-53200-19255-0\\/1?icep_ff3=2&amp;pub=5574933636&amp;toolid=10001&amp;campid=5336728181&amp;customid=&amp;mpre=https:\\/\\/www.ebay.com\\/p\\/Apple-iPhone-X-256GB-Silver-Unlocked-A1901-GSM\\/239160803\",\"https:\\/\\/rover.ebay.com\\/rover\\/1\\/711-53200-19255-0\\/1?icep_ff3=2&amp;pub=5574933636&amp;toolid=10001&amp;campid=5336728181&amp;customid=&amp;mpre=https:\\/\\/www.ebay.com\\/p\\/Apple-iPhone-X-64GB-Space-Grey-Unlocked\\/239057380\",\"https:\\/\\/rover.ebay.com\\/rover\\/1\\/711-53200-19255-0\\/1?icep_ff3=2&amp;pub=5574933636&amp;toolid=10001&amp;campid=5336728181&amp;customid=&amp;mpre=https:\\/\\/www.ebay.com\\/p\\/Apple-iPhone-X-256GB-Silver-Unlocked-A1865-CDMA-GSM\\/240377022\",\"https:\\/\\/rover.ebay.com\\/rover\\/1\\/711-53200-19255-0\\/1?icep_ff3=2&amp;pub=5574933636&amp;toolid=10001&amp;campid=5336728181&amp;customid=&amp;mpre=https:\\/\\/www.ebay.com\\/p\\/Apple-iPhone-X-64GB-Silver-Unlocked-A1901-GSM\\/238941171\",\"https:\\/\\/rover.ebay.com\\/rover\\/1\\/711-53200-19255-0\\/1?icep_ff3=2&amp;pub=5574933636&amp;toolid=10001&amp;campid=5336728181&amp;customid=&amp;mpre=https:\\/\\/www.ebay.com\\/p\\/Apple-iPhone-X-256GB-Space-Gray-AT-T-A1901-GSM\\/239009332\",\"https:\\/\\/rover.ebay.com\\/rover\\/1\\/711-53200-19255-0\\/1?icep_ff3=2&amp;pub=5574933636&amp;toolid=10001&amp;campid=5336728181&amp;customid=&amp;mpre=https:\\/\\/www.ebay.com\\/p\\/Apple-iPhone-X-256GB-Space-Grey-Unlocked\\/239165288\",\"https:\\/\\/rover.ebay.com\\/rover\\/1\\/711-53200-19255-0\\/1?icep_ff3=2&amp;pub=5574933636&amp;toolid=10001&amp;campid=5336728181&amp;customid=&amp;mpre=https:\\/\\/www.ebay.com\\/p\\/Apple-iPhone-X-64GB-Silver-Unlocked-A1865-CDMA-GSM\\/240455169\",\"https:\\/\\/rover.ebay.com\\/rover\\/1\\/711-53200-19255-0\\/1?icep_ff3=2&amp;pub=5574933636&amp;toolid=10001&amp;campid=5336728181&amp;customid=&amp;mpre=https:\\/\\/www.ebay.com\\/p\\/Apple-iPhone-X-256GB-Space-Gray-Verizon-A1865-CDMA-GSM\\/239068454\",\"https:\\/\\/rover.ebay.com\\/rover\\/1\\/711-53200-19255-0\\/1?icep_ff3=2&amp;pub=5574933636&amp;toolid=10001&amp;campid=5336728181&amp;customid=&amp;mpre=https:\\/\\/www.ebay.com\\/p\\/Apple-iPhone-X-64GB-Space-Gray-AT-T-A1901-GSM\\/239009342\"]', '[{\"key\":\"Brand\",\"values\":\"Apple\"},{\"key\":\"MPN\",\"values\":\"MQAM2LL\\/A\"},{\"key\":\"Matching MPN\",\"values\":[\"MQAM2LL\\/A\",\"MQAU2LL\\/A\"]},{\"key\":\"Model Number\",\"values\":\"A1901 (GSM Only)\"},{\"key\":\"Network\",\"values\":\"Unlocked\"},{\"key\":\"Family Line\",\"values\":\"Apple iPhone\"},{\"key\":\"Model\",\"values\":\"X\"},{\"key\":\"UPC\",\"values\":\"019019845670\"},{\"key\":\"Type\",\"values\":\"Smartphone\"},{\"key\":\"Storage Capacity\",\"values\":\"256GB\"},{\"key\":\"Color\",\"values\":\"Space Gray\"},{\"key\":\"Network Generation\",\"values\":[\"2G\",\"3G\",\"4G\"]},{\"key\":\"Network Technology\",\"values\":\"GSM \\/ EDGE \\/ UMTS \\/ HSPA+ \\/ DC-HSDPA \\/ FDD-LTE \\/ TD-LTE\"},{\"key\":\"Style\",\"values\":\"Bar\"},{\"key\":\"Camera Resolution\",\"values\":\"12.0MP\"},{\"key\":\"Connectivity\",\"values\":[\"Bluetooth\",\"NFC\",\"USB\",\"WiFi\"]},{\"key\":\"Supported Flash Memory Cards\",\"values\":\"Built-In Memory\"},{\"key\":\"Battery Type\",\"values\":\"Lithium Ion\"},{\"key\":\"Battery Capacity\",\"values\":\"2716 mAh\"},{\"key\":\"Display Technology\",\"values\":\"Super Retina HD display\"},{\"key\":\"Screen Size\",\"values\":\"5.8\\\"\"},{\"key\":\"Display Resolution\",\"values\":\"2436 x 1125\"},{\"key\":\"Touch Screen\",\"values\":\"Yes\"},{\"key\":\"Bluetooth\",\"values\":\"Yes\"},{\"key\":\"Digital Camera\",\"values\":\"Yes\"},{\"key\":\"GPS\",\"values\":\"Yes\"},{\"key\":\"QWERTY Physical Keyboard\",\"values\":\"No\"},{\"key\":\"Email Access\",\"values\":\"Yes\"},{\"key\":\"Internet Browser\",\"values\":\"Yes\"},{\"key\":\"Speakerphone\",\"values\":\"Yes\"},{\"key\":\"Height\",\"values\":\"5.65 in.\"},{\"key\":\"Depth\",\"values\":\"0.3 in.\"},{\"key\":\"Width\",\"values\":\"2.79 in.\"},{\"key\":\"Weight\",\"values\":\"6.14 oz\"},{\"key\":\"EAN\",\"values\":\"0190198456960\"},{\"key\":\"Operating System\",\"values\":\"Apple iOS\"},{\"key\":\"Battery Talk Time\",\"values\":\"Up to 1260 min\"},{\"key\":\"Battery Standby Time\",\"values\":\"Up to 242 hr\"}]', 9, '2018-04-20 13:46:43');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `purchase` (`purchase`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `token_2` (`token`);

--
-- Indexes for table `queries`
--
ALTER TABLE `queries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `metaphone` (`metaphone`),
  ADD KEY `metaphone_2` (`metaphone`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `queries`
--
ALTER TABLE `queries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
