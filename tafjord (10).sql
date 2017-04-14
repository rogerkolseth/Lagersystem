-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: 13. Apr, 2017 19:00 p.m.
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
(4, 'testing'),
(3, 'TV');

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `checkout`
--

CREATE TABLE `checkout` (
  `checkOutID` int(11) UNSIGNED NOT NULL,
  `userID` int(11) UNSIGNED NOT NULL,
  `macAdresseID` int(11) UNSIGNED NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `inventory`
--

CREATE TABLE `inventory` (
  `inventoryID` int(11) UNSIGNED NOT NULL,
  `storageID` int(11) UNSIGNED NOT NULL,
  `productID` int(11) UNSIGNED NOT NULL,
  `quantity` int(11) DEFAULT NULL
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
  `userID` int(11) UNSIGNED DEFAULT NULL,
  `onUserID` int(11) UNSIGNED DEFAULT NULL,
  `productID` int(11) UNSIGNED DEFAULT NULL,
  `date` datetime NOT NULL,
  `customerNr` int(11) DEFAULT NULL
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
(9, 'Sletting', 0),
(10, 'Varetelling', 1);

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `macadresse`
--

CREATE TABLE `macadresse` (
  `macAdresseID` int(11) UNSIGNED NOT NULL,
  `storageID` int(11) UNSIGNED NOT NULL,
  `productID` int(11) UNSIGNED NOT NULL,
  `macAdresse` varchar(50) DEFAULT NULL
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
(21, 'defaultUser.png', 4),
(41, 'Costa Rican Frog.jpg', 2);

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
  `macAdresse` varchar(8) DEFAULT 'FALSE'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
CREATE TRIGGER `editProduct_Logg` AFTER UPDATE ON `products` FOR EACH ROW BEGIN
IF ((SELECT loggtype.typeCheck FROM loggtype WHERE loggtype.typeID = 1) > 0 ) THEN
    INSERT INTO logg (logg.typeD, logg.desc, logg.UserID, logg.productID, logg.date) VALUES (1, 'Av produkt', @sessionUserID, NEW.productID, NOW());
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
  `userID` int(11) UNSIGNED NOT NULL,
  `storageID` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Triggere `restrictions`
--
DELIMITER $$
CREATE TRIGGER `createRestriction_Logg` AFTER INSERT ON `restrictions` FOR EACH ROW BEGIN
IF ((SELECT loggtype.typeCheck FROM loggtype WHERE loggtype.typeID = 3) > 0 ) THEN
    INSERT INTO logg (logg.typeID, logg.desc, logg.storageID, logg.userID, logg.onUserID, logg.date) VALUES (3, 'Gav tilgang til', NEW.storageID, @sessionUserID, NEW.userID, NOW());
END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `removeRestriction_Logg` BEFORE DELETE ON `restrictions` FOR EACH ROW BEGIN
IF ((SELECT loggtype.typeCheck FROM loggtype WHERE loggtype.typeID = 3) > 0 ) THEN
    INSERT INTO logg (logg.typeID, logg.desc, logg.storageID, logg.userID, logg.onUserID, logg.date) VALUES (3, 'Fjernet tilgang til', OLD.storageID, @sessionUserID, OLD.userID, NOW());
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
  `quantity` int(11) NOT NULL
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
  `quantity` int(11) NOT NULL
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
-- Tabellstruktur for tabell `storage`
--

CREATE TABLE `storage` (
  `storageID` int(11) UNSIGNED NOT NULL,
  `storageName` varchar(60) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dataark for tabell `storage`
--

INSERT INTO `storage` (`storageID`, `storageName`) VALUES
(1, 'Hovedlager'),
(63, 'Kundesenter');

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
CREATE TRIGGER `editStorage_Logg` AFTER UPDATE ON `storage` FOR EACH ROW BEGIN 
IF ((SELECT loggtype.typeCheck FROM loggtype WHERE loggtype.typeID = 1) > 0 ) THEN
	INSERT INTO logg (logg.typeID, logg.desc, logg.UserID, logg.storageID, logg.date) VALUES (1, 'Av produkt', @sessionUserID, NEW.storageID, NOW()); 
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
  `mediaID` int(11) UNSIGNED DEFAULT NULL,
  `lastLogin` date DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dataark for tabell `users`
--

INSERT INTO `users` (`userID`, `name`, `username`, `password`, `userLevel`, `mediaID`, `lastLogin`, `email`) VALUES
(68, 'Roger Kolseth', 'rogkol', '$2y$10$Yp0duv9IfmC8MSpanG60XuEljLEO0KOJsUrPH45EROrzcJ1Dyxdfm', 'Administrator', 21, '2017-04-13', 'test123'),
(83, 'test', 'test', '$2y$10$JfoDe1xBH5U8nnjFmqGIY.Nx.xxVBLbLmNNOfZYpd4YxDbbRPJ2ey', 'User', 21, '2017-04-06', 'test'),
(84, 'afd', 'sdf', '$2y$10$vaaxIJQRNvdbJI8r8gJRceEmFDrlZqExTVRnNwhuQ5w4FFzLYiPLW', 'Administrator', 21, NULL, 'dsf');

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
IF ((SELECT loggtype.typeCheck FROM loggtype WHERE loggtype.typeID = 9) > 0 ) THEN
    INSERT INTO logg (logg.typeID, logg.desc, logg.onUserID, logg.date) VALUES (9, 'Av bruker', '10', NOW());
END IF;
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
-- Indexes for table `checkout`
--
ALTER TABLE `checkout`
  ADD PRIMARY KEY (`checkOutID`),
  ADD KEY `macAdresseID` (`macAdresseID`),
  ADD KEY `userID` (`userID`);

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
  ADD KEY `logg_ibfk_7` (`typeID`);

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
  ADD UNIQUE KEY `macAdresse` (`macAdresse`),
  ADD KEY `storageID` (`storageID`),
  ADD KEY `productID` (`productID`);

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
  ADD KEY `storageID` (`storageID`);

--
-- Indexes for table `returns`
--
ALTER TABLE `returns`
  ADD PRIMARY KEY (`returnID`),
  ADD KEY `productID` (`productID`),
  ADD KEY `returns_ibfk_3` (`storageID`),
  ADD KEY `returns_ibfk_2` (`userID`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`salesID`),
  ADD KEY `productID` (`productID`),
  ADD KEY `sales_ibfk_3` (`storageID`),
  ADD KEY `sales_ibfk_2` (`userID`);

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
  ADD KEY `users_ibfk_2` (`mediaID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `categoryID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `checkout`
--
ALTER TABLE `checkout`
  MODIFY `checkOutID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `inventoryID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=127;
--
-- AUTO_INCREMENT for table `logg`
--
ALTER TABLE `logg`
  MODIFY `loggID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
--
-- AUTO_INCREMENT for table `macadresse`
--
ALTER TABLE `macadresse`
  MODIFY `macAdresseID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
  MODIFY `mediaID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;
--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `productID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;
--
-- AUTO_INCREMENT for table `restrictions`
--
ALTER TABLE `restrictions`
  MODIFY `resID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;
--
-- AUTO_INCREMENT for table `returns`
--
ALTER TABLE `returns`
  MODIFY `returnID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `salesID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;
--
-- AUTO_INCREMENT for table `storage`
--
ALTER TABLE `storage`
  MODIFY `storageID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;
--
-- Begrensninger for dumpede tabeller
--

--
-- Begrensninger for tabell `checkout`
--
ALTER TABLE `checkout`
  ADD CONSTRAINT `checkout_ibfk_1` FOREIGN KEY (`macAdresseID`) REFERENCES `macadresse` (`macAdresseID`),
  ADD CONSTRAINT `checkout_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`);

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
  ADD CONSTRAINT `logg_ibfk_7` FOREIGN KEY (`typeID`) REFERENCES `loggtype` (`typeID`);

--
-- Begrensninger for tabell `macadresse`
--
ALTER TABLE `macadresse`
  ADD CONSTRAINT `macadresse_ibfk_1` FOREIGN KEY (`storageID`) REFERENCES `storage` (`storageID`),
  ADD CONSTRAINT `macadresse_ibfk_2` FOREIGN KEY (`productID`) REFERENCES `products` (`productID`);

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
  ADD CONSTRAINT `restrictions_ibfk_2` FOREIGN KEY (`storageID`) REFERENCES `storage` (`storageID`);

--
-- Begrensninger for tabell `returns`
--
ALTER TABLE `returns`
  ADD CONSTRAINT `returns_ibfk_1` FOREIGN KEY (`productID`) REFERENCES `products` (`productID`),
  ADD CONSTRAINT `returns_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`),
  ADD CONSTRAINT `returns_ibfk_3` FOREIGN KEY (`storageID`) REFERENCES `storage` (`storageID`);

--
-- Begrensninger for tabell `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_ibfk_1` FOREIGN KEY (`productID`) REFERENCES `products` (`productID`),
  ADD CONSTRAINT `sales_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`),
  ADD CONSTRAINT `sales_ibfk_3` FOREIGN KEY (`storageID`) REFERENCES `storage` (`storageID`);

--
-- Begrensninger for tabell `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`mediaID`) REFERENCES `media` (`mediaID`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
