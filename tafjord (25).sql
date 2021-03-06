-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: 27. Mai, 2017 00:29 a.m.
-- Server-versjon: 5.5.54
-- PHP Version: 5.6.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tafjord`
--

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `categories`
--

CREATE TABLE `categories` (
  `categoryID` int(11) UNSIGNED NOT NULL,
  `categoryName` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dataark for tabell `categories`
--

INSERT INTO `categories` (`categoryID`, `categoryName`) VALUES
(2, 'Internett'),
(3, 'TV');

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `group_members`
--

CREATE TABLE `group_members` (
  `memberID` int(11) UNSIGNED NOT NULL,
  `userID` int(11) UNSIGNED NOT NULL,
  `groupID` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Triggere `group_members`
--
DELIMITER $$
CREATE TRIGGER `createGroupMember_Logg` AFTER INSERT ON `group_members` FOR EACH ROW BEGIN
IF ((SELECT loggtype.typeCheck FROM loggtype WHERE loggtype.typeID = 3) > 0 ) THEN
    INSERT INTO logg (logg.typeID, logg.desc, logg.groupID, logg.userID, logg.onUserID, logg.date) VALUES (3, 'Innmeldt bruker', NEW.groupID, @sessionUserID, NEW.userID, NOW());
END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `deleteGroupMember_Logg` BEFORE DELETE ON `group_members` FOR EACH ROW BEGIN
IF ((SELECT loggtype.typeCheck FROM loggtype WHERE loggtype.typeID = 3) > 0 ) THEN
    INSERT INTO logg (logg.typeID, logg.desc, logg.groupID, logg.userID, logg.onUserID, logg.date) VALUES (3, 'Utmeldt bruker', OLD.groupID, @sessionUserID, OLD.userID, NOW());
END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `inventory`
--

CREATE TABLE `inventory` (
  `inventoryID` int(11) UNSIGNED NOT NULL,
  `storageID` int(11) UNSIGNED NOT NULL,
  `productID` int(11) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `emailWarning` int(11) NOT NULL DEFAULT '5',
  `inventoryWarning` int(11) NOT NULL DEFAULT '10',
  `emailStatus` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Triggere `inventory`
--
DELIMITER $$
CREATE TRIGGER `removeProFromStorage_Logg` BEFORE DELETE ON `inventory` FOR EACH ROW BEGIN
IF ((SELECT loggtype.typeCheck FROM loggtype WHERE loggtype.typeID = 9) > 0 ) THEN
    INSERT INTO logg (logg.typeID, logg.desc, logg.fromStorageID, logg.userID, logg.productID, logg.quantity, logg.date) VALUES (9, 'Fjernet produkt fra', OLD.storageID, @sessionUserID, OLD.productID, OLD.quantity, NOW());
END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `updateEmailStatus` BEFORE UPDATE ON `inventory` FOR EACH ROW BEGIN
IF NEW.quantity <> OLD.quantity AND NEW.quantity > NEW.inventoryWarning THEN
  SET NEW.emailStatus = 0 ;
END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `logg`
--

CREATE TABLE `logg` (
  `loggID` int(11) UNSIGNED NOT NULL,
  `typeID` int(11) NOT NULL,
  `desc` varchar(255) NOT NULL,
  `storageID` int(11) UNSIGNED DEFAULT NULL,
  `fromStorageID` int(11) UNSIGNED DEFAULT NULL,
  `toStorageID` int(11) UNSIGNED DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `oldQuantity` int(11) DEFAULT NULL,
  `newQuantity` int(11) DEFAULT NULL,
  `differential` int(11) DEFAULT NULL,
  `groupID` int(11) UNSIGNED DEFAULT NULL,
  `userID` int(11) UNSIGNED DEFAULT NULL,
  `onUserID` int(11) UNSIGNED DEFAULT NULL,
  `productID` int(11) UNSIGNED DEFAULT NULL,
  `date` datetime NOT NULL,
  `customerNr` int(11) DEFAULT NULL,
  `deletedUser` varchar(255) DEFAULT NULL,
  `deletedStorage` varchar(255) DEFAULT NULL,
  `deletedProduct` varchar(255) DEFAULT NULL,
  `deletedGroup` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `loggtype`
--

CREATE TABLE `loggtype` (
  `typeID` int(11) NOT NULL,
  `typeName` varchar(255) NOT NULL,
  `typeCheck` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dataark for tabell `loggtype`
--

INSERT INTO `loggtype` (`typeID`, `typeName`, `typeCheck`) VALUES
(1, 'Redigering', 1),
(2, 'Innlogging', 0),
(3, 'Tilgang', 1),
(4, 'Opprettelse', 1),
(5, 'Varelevering', 1),
(6, 'Uttak', 1),
(7, 'Retur', 1),
(8, 'Overføring', 1),
(9, 'Sletting', 1),
(10, 'Varetelling', 1);

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `macadresse`
--

CREATE TABLE `macadresse` (
  `macAdresseID` int(11) UNSIGNED NOT NULL,
  `macAdresse` varchar(255) NOT NULL,
  `inventoryID` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `media`
--

CREATE TABLE `media` (
  `mediaID` int(11) UNSIGNED NOT NULL,
  `mediaName` varchar(255) NOT NULL,
  `categoryID` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dataark for tabell `media`
--

INSERT INTO `media` (`mediaID`, `mediaName`, `categoryID`) VALUES
(21, 'defaultUser.png', 3),
(22, 'Wifi-pluss.png', 2),
(23, 'Zyxel.jpg', 2),
(24, 'dekoder.png', 3);

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `products`
--

CREATE TABLE `products` (
  `productID` int(11) UNSIGNED NOT NULL,
  `productName` varchar(255) NOT NULL,
  `price` decimal(25,2) DEFAULT NULL,
  `categoryID` int(11) UNSIGNED NOT NULL,
  `mediaID` int(11) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `macAdresse` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dataark for tabell `products`
--

INSERT INTO `products` (`productID`, `productName`, `price`, `categoryID`, `mediaID`, `date`, `macAdresse`) VALUES
(62, 'FMG', '999.00', 2, 21, '2017-04-25', 0),
(63, 'TestMac', '444.00', 2, 21, '2017-04-26', 1),
(64, 'testMac2', '4444.00', 2, 21, '2017-04-26', 1),
(65, 'testMac3', '111.00', 2, 21, '2017-04-26', 1);

--
-- Triggere `products`
--
DELIMITER $$
CREATE TRIGGER `createProduct_Logg` AFTER INSERT ON `products` FOR EACH ROW BEGIN
IF ((SELECT loggtype.typeCheck FROM loggtype WHERE loggtype.typeID = 4) > 0 ) THEN
    INSERT INTO logg (logg.typeID, logg.desc, logg.userID, logg.productID, logg.date) VALUES (4, 'Nytt produkt', @sessionUserID, NEW.productID, NOW());
END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `deleteProduct_Logg` BEFORE DELETE ON `products` FOR EACH ROW BEGIN 
DELETE FROM inventory WHERE inventory.productID = OLD.productID;
IF ((SELECT loggtype.typeCheck FROM loggtype WHERE loggtype.typeID = 9) > 0 ) THEN INSERT INTO logg (logg.typeID, logg.desc, logg.userID, logg.date, logg.deletedProduct) VALUES (9, 'Av produkt', @sessionUserID, NOW(), OLD.productName); END IF; 
UPDATE sales SET sales.deletedProduct = OLD.productName WHERE sales.productID = OLD.productID;
UPDATE returns SET returns.deletedProduct = OLD.productName WHERE returns.productID = OLD.productID;
UPDATE logg SET logg.deletedProduct = OLD.productName WHERE logg.productID = OLD.productID;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `editProduct_Logg` AFTER UPDATE ON `products` FOR EACH ROW BEGIN
IF ((SELECT loggtype.typeCheck FROM loggtype WHERE loggtype.typeID = 1) > 0 ) THEN
    INSERT INTO logg (logg.typeID, logg.desc, logg.UserID, logg.productID, logg.date) VALUES (1, 'Av produkt', @sessionUserID, NEW.productID, NOW());
END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `restrictions`
--

CREATE TABLE `restrictions` (
  `resID` int(11) UNSIGNED NOT NULL,
  `userID` int(11) UNSIGNED DEFAULT NULL,
  `storageID` int(11) UNSIGNED NOT NULL,
  `groupID` int(11) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Triggere `restrictions`
--
DELIMITER $$
CREATE TRIGGER `createRestriction_Logg` AFTER INSERT ON `restrictions` FOR EACH ROW BEGIN
IF ((SELECT loggtype.typeCheck FROM loggtype WHERE loggtype.typeID = 3) > 0 ) THEN
    INSERT INTO logg (logg.typeID, logg.desc, logg.storageID, logg.userID, logg.onUserID, logg.groupID, logg.date) VALUES (3, 'Gav tilgang til', NEW.storageID, @sessionUserID, NEW.userID, NEW.groupID, NOW());
END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `removeRestriction_Logg` BEFORE DELETE ON `restrictions` FOR EACH ROW BEGIN
IF ((SELECT loggtype.typeCheck FROM loggtype WHERE loggtype.typeID = 3) > 0 ) THEN
    INSERT INTO logg (logg.typeID, logg.desc, logg.storageID, logg.userID, logg.onUserID, logg.groupID, logg.date) VALUES (3, 'Fjernet tilgang til', OLD.storageID, @sessionUserID, OLD.userID, OLD.groupID, NOW());
END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `returns`
--

CREATE TABLE `returns` (
  `returnID` int(11) UNSIGNED NOT NULL,
  `productID` int(11) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `customerNr` int(11) NOT NULL,
  `comment` text,
  `userID` int(11) UNSIGNED NOT NULL,
  `storageID` int(11) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `deletedStorage` varchar(255) DEFAULT NULL,
  `deletedProduct` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Triggere `returns`
--
DELIMITER $$
CREATE TRIGGER `newReturn_Logg` AFTER INSERT ON `returns` FOR EACH ROW BEGIN
IF ((SELECT loggtype.typeCheck FROM loggtype WHERE loggtype.typeID = 7) > 0 ) THEN
    INSERT INTO logg (logg.typeID, logg.desc, logg.toStorageID, logg.quantity, logg.productID, logg.userID, logg.customerNr, logg.date) VALUES (7, 'Tok inn produkt til', NEW.storageID, NEW.quantity, NEW.productID, NEW.userID, NEW.customerNr, NOW());
END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `returns_macadresse`
--

CREATE TABLE `returns_macadresse` (
  `returnMacID` int(11) UNSIGNED NOT NULL,
  `returnID` int(11) UNSIGNED NOT NULL,
  `macAdresse` varchar(255) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `sales`
--

CREATE TABLE `sales` (
  `salesID` int(11) UNSIGNED NOT NULL,
  `productID` int(11) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `customerNr` int(11) NOT NULL,
  `comment` text,
  `userID` int(11) UNSIGNED NOT NULL,
  `storageID` int(11) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `deletedStorage` varchar(255) DEFAULT NULL,
  `deletedProduct` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Triggere `sales`
--
DELIMITER $$
CREATE TRIGGER `newSale_Logg` AFTER INSERT ON `sales` FOR EACH ROW BEGIN
IF ((SELECT loggtype.typeCheck FROM loggtype WHERE loggtype.typeID = 6) > 0 ) THEN
    INSERT INTO logg (logg.typeID, logg.desc, logg.fromStorageID, logg.quantity, logg.productID, logg.userID, logg.customerNr, logg.date) VALUES (6, 'Tok ut produkt fra', NEW.storageID, NEW.quantity, NEW.productID, NEW.userID, NEW.customerNr, NOW());
END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `sales_macadresse`
--

CREATE TABLE `sales_macadresse` (
  `saleMacID` int(11) UNSIGNED NOT NULL,
  `salesID` int(11) UNSIGNED NOT NULL,
  `macAdresse` varchar(255) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `storage`
--

CREATE TABLE `storage` (
  `storageID` int(11) UNSIGNED NOT NULL,
  `storageName` varchar(60) NOT NULL,
  `negativeSupport` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dataark for tabell `storage`
--

INSERT INTO `storage` (`storageID`, `storageName`, `negativeSupport`) VALUES
(1, 'Hovedlager', 0),
(2, 'Returlager', 0),
(63, 'Kundesenter', 1);

--
-- Triggere `storage`
--
DELIMITER $$
CREATE TRIGGER `createStorage_Logg` AFTER INSERT ON `storage` FOR EACH ROW BEGIN
IF ((SELECT loggtype.typeCheck FROM loggtype WHERE loggtype.typeID = 4) > 0 ) THEN
    INSERT INTO logg (logg.typeID, logg.desc, logg.storageID, logg.userID, logg.date) VALUES (4, 'Nytt lager', NEW.storageID, @sessionUserID, NOW());
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `deleteStorage_Logg` BEFORE DELETE ON `storage` FOR EACH ROW BEGIN
IF ((SELECT loggtype.typeCheck FROM loggtype WHERE loggtype.typeID = 9) > 0 ) THEN
	INSERT INTO logg (logg.typeID, logg.desc, logg.userID, logg.date, logg.deletedStorage) VALUES (9, 'Av lager', @sessionUserID, NOW(), OLD.storageName);
    END IF;
    UPDATE sales SET sales.deletedStorage = OLD.storageName WHERE 	sales.storageID = OLD.storageID;
    UPDATE returns SET returns.deletedStorage = OLD.storageName WHERE 	returns.storageID = OLD.storageID;
    UPDATE logg SET logg.deletedStorage = OLD.storageName WHERE logg.storageID = OLD.storageID OR logg.fromStorageID = OLD.storageID;
    END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `editStorage_Logg` AFTER UPDATE ON `storage` FOR EACH ROW BEGIN 
IF ((SELECT loggtype.typeCheck FROM loggtype WHERE loggtype.typeID = 1) > 0 ) THEN
	INSERT INTO logg (logg.typeID, logg.desc, logg.UserID, logg.storageID, logg.date) VALUES (1, 'Av lager', @sessionUserID, NEW.storageID, NOW()); 
END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `users`
--

CREATE TABLE `users` (
  `userID` int(11) UNSIGNED NOT NULL,
  `name` varchar(60) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `userLevel` varchar(50) NOT NULL,
  `mediaID` int(11) UNSIGNED NOT NULL,
  `lastLogin` date DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dataark for tabell `users`
--

INSERT INTO `users` (`userID`, `name`, `username`, `password`, `userLevel`, `mediaID`, `lastLogin`, `email`) VALUES
(68, 'Roger Kolseth', 'rogkol', '$2y$10$j6T8Ds15Df/0Vr4cQqw5Q.efaGepSmUcAGK4GmyVKA8QtLVLFwweK', 'Administrator', 21, '2017-05-26', 'roger.kolseth@gmail.com'),
(90, 'Anders', 'andhag', '$2y$10$pAsV.du9jDPV/S6gR999j.C8r2bqN/qVQog74QLOfBpWkGzgyj4gO', 'User', 21, NULL, 'epost@epost.no'),
(91, 'Test', 'Test', '$2y$10$hSgQdQZEzLB/XE63/9RwFu92xj7Xwc8KOor9gQfqGC6K2Mpbgnfqa', 'User', 21, '2017-05-22', 'Test');

--
-- Triggere `users`
--
DELIMITER $$
CREATE TRIGGER `createUser_Logg` AFTER INSERT ON `users` FOR EACH ROW BEGIN
IF ((SELECT loggtype.typeCheck FROM loggtype WHERE loggtype.typeID = 4) > 0 ) THEN
    INSERT INTO logg (logg.typeID, logg.desc, logg.userID, logg.onUserID, logg.date) VALUES (4, 'Ny bruker', @sessionUserID, NEW.userID, NOW());
END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `deleteUser_Logg` BEFORE DELETE ON `users` FOR EACH ROW BEGIN
DELETE FROM group_members WHERE OLD.userID = group_members.userID;
IF ((SELECT loggtype.typeCheck FROM loggtype WHERE loggtype.typeID = 9) > 0 ) THEN
    INSERT INTO logg (logg.typeID, logg.desc, logg.userID, logg.date, logg.deletedUser) VALUES (9, 'Av bruker', @sessionUserID, NOW(), OLD.username);
END IF;
UPDATE logg SET logg.deletedUser = OLD.username WHERE logg.userID = OLD.userID OR logg.onUserID = OLD.userID;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `editUser_Logg` AFTER UPDATE ON `users` FOR EACH ROW BEGIN
IF ((SELECT loggtype.typeCheck FROM loggtype WHERE loggtype.typeID = 1) > 0 ) THEN
	IF NEW.name <> OLD.name OR NEW.username <> OLD.username OR NEW.password <> OLD.password OR NEW.userLevel <> OLD.userLevel OR NEW.email <> OLD.email THEN
    INSERT INTO logg (logg.typeID, logg.desc, logg.userID, logg.onUserID, logg.date) VALUES (1, 'Av bruker', @sessionUserID, NEW.userID, NOW());
    END IF;
END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `user_group`
--

CREATE TABLE `user_group` (
  `groupID` int(11) UNSIGNED NOT NULL,
  `groupName` varchar(255) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Triggere `user_group`
--
DELIMITER $$
CREATE TRIGGER `createGroup_Logg` AFTER INSERT ON `user_group` FOR EACH ROW BEGIN
IF ((SELECT loggtype.typeCheck FROM loggtype WHERE loggtype.typeID = 4) > 0 ) THEN
    INSERT INTO logg (logg.typeID, logg.desc, logg.groupID, logg.userID, logg.date) VALUES (4, 'Ny gruppe', NEW.groupID, @sessionUserID, NOW());
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `deleteGroup` BEFORE DELETE ON `user_group` FOR EACH ROW BEGIN
DELETE FROM group_members WHERE OLD.groupID = group_members.groupID;
DELETE FROM restrictions WHERE OLD.groupID = restrictions.groupID;
UPDATE logg SET logg.deletedGroup = OLD.groupName WHERE logg.groupID = OLD.groupID;
IF ((SELECT loggtype.typeCheck FROM loggtype WHERE loggtype.typeID = 9) > 0 ) THEN
	INSERT INTO logg (logg.typeID, logg.desc, logg.userID, logg.date, logg.deletedGroup) VALUES (9, 'Av grupper', @sessionUserID, NOW(), OLD.groupName);
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `editGroup_Logg` AFTER UPDATE ON `user_group` FOR EACH ROW BEGIN 
IF ((SELECT loggtype.typeCheck FROM loggtype WHERE loggtype.typeID = 1) > 0 ) THEN
	INSERT INTO logg (logg.typeID, logg.desc, logg.UserID, logg.groupID, logg.date) VALUES (1, 'Av gruppe', @sessionUserID, NEW.groupID, NOW()); 
END IF;
END
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`categoryID`),
  ADD UNIQUE KEY `categoryName` (`categoryName`);

--
-- Indexes for table `group_members`
--
ALTER TABLE `group_members`
  ADD PRIMARY KEY (`memberID`),
  ADD KEY `user_memb_ibfk_2` (`userID`),
  ADD KEY `user_memb_ibfk_3` (`groupID`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`inventoryID`),
  ADD KEY `storageID` (`storageID`),
  ADD KEY `productID` (`productID`);

--
-- Indexes for table `logg`
--
ALTER TABLE `logg`
  ADD PRIMARY KEY (`loggID`),
  ADD KEY `logg_ibfk_1` (`userID`),
  ADD KEY `logg_ibfk_2` (`storageID`),
  ADD KEY `logg_ibfk_3` (`fromStorageID`),
  ADD KEY `logg_ibfk_4` (`toStorageID`),
  ADD KEY `logg_ibfk_5` (`onUserID`),
  ADD KEY `logg_ibfk_6` (`productID`),
  ADD KEY `logg_ibfk_7` (`typeID`),
  ADD KEY `logg_ibfk_8` (`groupID`);

--
-- Indexes for table `loggtype`
--
ALTER TABLE `loggtype`
  ADD PRIMARY KEY (`typeID`);

--
-- Indexes for table `macadresse`
--
ALTER TABLE `macadresse`
  ADD PRIMARY KEY (`macAdresseID`),
  ADD KEY `macadresse_ibfk_2` (`inventoryID`);

--
-- Indexes for table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`mediaID`),
  ADD UNIQUE KEY `mediaName` (`mediaName`),
  ADD KEY `media_ibfk_2` (`categoryID`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`productID`),
  ADD UNIQUE KEY `productName` (`productName`),
  ADD KEY `categoryID` (`categoryID`),
  ADD KEY `mediaID` (`mediaID`);

--
-- Indexes for table `restrictions`
--
ALTER TABLE `restrictions`
  ADD PRIMARY KEY (`resID`),
  ADD KEY `userID` (`userID`),
  ADD KEY `storageID` (`storageID`),
  ADD KEY `restrictions_ibfk_3` (`groupID`);

--
-- Indexes for table `returns`
--
ALTER TABLE `returns`
  ADD PRIMARY KEY (`returnID`),
  ADD KEY `productID` (`productID`),
  ADD KEY `returns_ibfk_3` (`storageID`),
  ADD KEY `returns_ibfk_2` (`userID`);

--
-- Indexes for table `returns_macadresse`
--
ALTER TABLE `returns_macadresse`
  ADD PRIMARY KEY (`returnMacID`),
  ADD KEY `returns_macadresse_ibfk_2` (`returnID`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`salesID`),
  ADD KEY `productID` (`productID`),
  ADD KEY `sales_ibfk_3` (`storageID`),
  ADD KEY `sales_ibfk_2` (`userID`);

--
-- Indexes for table `sales_macadresse`
--
ALTER TABLE `sales_macadresse`
  ADD PRIMARY KEY (`saleMacID`),
  ADD KEY `sales_macadresse_ibfk_2` (`salesID`);

--
-- Indexes for table `storage`
--
ALTER TABLE `storage`
  ADD PRIMARY KEY (`storageID`),
  ADD UNIQUE KEY `storageName` (`storageName`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `users_ibfk_2` (`mediaID`),
  ADD KEY `username_2` (`username`);

--
-- Indexes for table `user_group`
--
ALTER TABLE `user_group`
  ADD PRIMARY KEY (`groupID`),
  ADD UNIQUE KEY `groupID` (`groupID`),
  ADD UNIQUE KEY `groupName` (`groupName`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `categoryID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `group_members`
--
ALTER TABLE `group_members`
  MODIFY `memberID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;
--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `inventoryID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=189;
--
-- AUTO_INCREMENT for table `logg`
--
ALTER TABLE `logg`
  MODIFY `loggID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=780;
--
-- AUTO_INCREMENT for table `macadresse`
--
ALTER TABLE `macadresse`
  MODIFY `macAdresseID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
  MODIFY `mediaID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `productID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;
--
-- AUTO_INCREMENT for table `restrictions`
--
ALTER TABLE `restrictions`
  MODIFY `resID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=147;
--
-- AUTO_INCREMENT for table `returns`
--
ALTER TABLE `returns`
  MODIFY `returnID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;
--
-- AUTO_INCREMENT for table `returns_macadresse`
--
ALTER TABLE `returns_macadresse`
  MODIFY `returnMacID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `salesID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=144;
--
-- AUTO_INCREMENT for table `sales_macadresse`
--
ALTER TABLE `sales_macadresse`
  MODIFY `saleMacID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `storage`
--
ALTER TABLE `storage`
  MODIFY `storageID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;
--
-- AUTO_INCREMENT for table `user_group`
--
ALTER TABLE `user_group`
  MODIFY `groupID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- Begrensninger for dumpede tabeller
--

--
-- Begrensninger for tabell `group_members`
--
ALTER TABLE `group_members`
  ADD CONSTRAINT `user_memb_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`),
  ADD CONSTRAINT `user_memb_ibfk_3` FOREIGN KEY (`groupID`) REFERENCES `user_group` (`groupID`);

--
-- Begrensninger for tabell `inventory`
--
ALTER TABLE `inventory`
  ADD CONSTRAINT `inventory_ibfk_1` FOREIGN KEY (`storageID`) REFERENCES `storage` (`storageID`),
  ADD CONSTRAINT `inventory_ibfk_2` FOREIGN KEY (`productID`) REFERENCES `products` (`productID`);

--
-- Begrensninger for tabell `logg`
--
ALTER TABLE `logg`
  ADD CONSTRAINT `logg_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`),
  ADD CONSTRAINT `logg_ibfk_2` FOREIGN KEY (`storageID`) REFERENCES `storage` (`storageID`),
  ADD CONSTRAINT `logg_ibfk_3` FOREIGN KEY (`fromStorageID`) REFERENCES `storage` (`storageID`),
  ADD CONSTRAINT `logg_ibfk_4` FOREIGN KEY (`toStorageID`) REFERENCES `storage` (`storageID`),
  ADD CONSTRAINT `logg_ibfk_5` FOREIGN KEY (`onUserID`) REFERENCES `users` (`userID`),
  ADD CONSTRAINT `logg_ibfk_6` FOREIGN KEY (`productID`) REFERENCES `products` (`productID`),
  ADD CONSTRAINT `logg_ibfk_7` FOREIGN KEY (`typeID`) REFERENCES `loggtype` (`typeID`),
  ADD CONSTRAINT `logg_ibfk_8` FOREIGN KEY (`groupID`) REFERENCES `user_group` (`groupID`);

--
-- Begrensninger for tabell `macadresse`
--
ALTER TABLE `macadresse`
  ADD CONSTRAINT `macadresse_ibfk_2` FOREIGN KEY (`inventoryID`) REFERENCES `inventory` (`inventoryID`);

--
-- Begrensninger for tabell `media`
--
ALTER TABLE `media`
  ADD CONSTRAINT `media_ibfk_2` FOREIGN KEY (`categoryID`) REFERENCES `categories` (`categoryID`);

--
-- Begrensninger for tabell `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`categoryID`) REFERENCES `categories` (`categoryID`),
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`mediaID`) REFERENCES `media` (`mediaID`);

--
-- Begrensninger for tabell `restrictions`
--
ALTER TABLE `restrictions`
  ADD CONSTRAINT `restrictions_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`),
  ADD CONSTRAINT `restrictions_ibfk_2` FOREIGN KEY (`storageID`) REFERENCES `storage` (`storageID`),
  ADD CONSTRAINT `restrictions_ibfk_3` FOREIGN KEY (`groupID`) REFERENCES `user_group` (`groupID`);

--
-- Begrensninger for tabell `returns`
--
ALTER TABLE `returns`
  ADD CONSTRAINT `returns_ibfk_1` FOREIGN KEY (`productID`) REFERENCES `products` (`productID`),
  ADD CONSTRAINT `returns_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`),
  ADD CONSTRAINT `returns_ibfk_3` FOREIGN KEY (`storageID`) REFERENCES `storage` (`storageID`);

--
-- Begrensninger for tabell `returns_macadresse`
--
ALTER TABLE `returns_macadresse`
  ADD CONSTRAINT `returns_macadresse_ibfk_2` FOREIGN KEY (`returnID`) REFERENCES `returns` (`returnID`);

--
-- Begrensninger for tabell `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_ibfk_1` FOREIGN KEY (`productID`) REFERENCES `products` (`productID`),
  ADD CONSTRAINT `sales_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`),
  ADD CONSTRAINT `sales_ibfk_3` FOREIGN KEY (`storageID`) REFERENCES `storage` (`storageID`);

--
-- Begrensninger for tabell `sales_macadresse`
--
ALTER TABLE `sales_macadresse`
  ADD CONSTRAINT `sales_macadresse_ibfk_2` FOREIGN KEY (`salesID`) REFERENCES `sales` (`salesID`);

--
-- Begrensninger for tabell `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`mediaID`) REFERENCES `media` (`mediaID`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
