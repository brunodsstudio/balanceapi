-- --------------------------------------------------------
-- Servidor:                     localhost
-- Versão do servidor:           10.4.11-MariaDB - mariadb.org binary distribution
-- OS do Servidor:               Win64
-- HeidiSQL Versão:              11.0.0.5919
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Copiando estrutura do banco de dados para balance_api
CREATE DATABASE IF NOT EXISTS `balance_api` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `balance_api`;

-- Copiando estrutura para tabela balance_api.balance
CREATE TABLE IF NOT EXISTS `balance` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL,
  `action_type` varchar(12) COLLATE latin1_general_ci NOT NULL,
  `action_amount` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- Copiando dados para a tabela balance_api.balance: ~13 rows (aproximadamente)
DELETE FROM `balance`;
/*!40000 ALTER TABLE `balance` DISABLE KEYS */;
/*!40000 ALTER TABLE `balance` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
