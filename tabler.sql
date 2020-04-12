-- --------------------------------------------------------
-- Hôte :                        localhost
-- Version du serveur:           5.7.24 - MySQL Community Server (GPL)
-- SE du serveur:                Win64
-- HeidiSQL Version:             10.2.0.5599
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Listage de la structure de la base pour apidb
DROP DATABASE IF EXISTS `apidb`;
CREATE DATABASE IF NOT EXISTS `apidb` /*!40100 DEFAULT CHARACTER SET ucs2 */;
USE `apidb`;

-- Listage de la structure de la table apidb. person
DROP TABLE IF EXISTS `person`;
CREATE TABLE IF NOT EXISTS `person` (
  `token` varchar(50) NOT NULL,
  `tokenuser` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `surname` varchar(50) NOT NULL,
  `deleted` bit(1) DEFAULT b'0'
) ENGINE=InnoDB DEFAULT CHARSET=ucs2;

-- Listage des données de la table apidb.person : ~4 rows (environ)
/*!40000 ALTER TABLE `person` DISABLE KEYS */;
INSERT INTO `person` (`token`, `tokenuser`, `name`, `surname`, `deleted`) VALUES
	('e8cc008c2fcc3d32f5e64a653b92e654', '505ae95711751ab95b71738eb7b882cc', 'test', 'test', b'1'),
	('463fd32c12838c77f06a42b3907b4647', '505ae95711751ab95b71738eb7b882cc', 'sylvain', 'gandini', b'0'),
	('773cb8ab9929a74a02b92dd34a8a973b', '505ae95711751ab95b71738eb7b882cc', 'sirijan', 'wittmer', b'0');
/*!40000 ALTER TABLE `person` ENABLE KEYS */;

-- Listage de la structure de la table apidb. user
DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `token` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(250) NOT NULL,
  `name` varchar(50) NOT NULL,
  `surname` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=ucs2 COMMENT='test';

-- Listage des données de la table apidb.user : ~2 rows (environ)
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` (`token`, `email`, `password`, `name`, `surname`) VALUES
	('ef442de1f98773ca6422444e3a43fd66', 'sylvain.gandiwni@cpnv.ch', 'ee26b0dd4af7e749aa1a8ee3c10ae9923f618980772e473f8819a5d4940e0db27ac185f8a0e1d5f84f88bc887fd67b143732c304cc5fa9ad8e6f57f50028a8ff', 'gandini', NULL),
	('505ae95711751ab95b71738eb7b882cc', 'sylvain.gandini@cpnv.ch', '8e226a494410609365b0029f40b6c7a98bed8db5cb7889d207d727cd9f71a76b710a3f767cc3a775e4cdf181b78f53ea5699f153e10df0c33f138094c88100b3', 'gandini', NULL);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
