-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 10, 2021 at 12:39 PM
-- Server version: 8.0.23
-- PHP Version: 8.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cyptoexch`
--

-- --------------------------------------------------------

--
-- Table structure for table `kryptowaluty`
--

CREATE TABLE `kryptowaluty` (
  `id_krypto` int NOT NULL,
  `nazwa` varchar(255) NOT NULL,
  `kurs` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kryptowaluty`
--

INSERT INTO `kryptowaluty` (`id_krypto`, `nazwa`, `kurs`) VALUES
(1, 'Bitcoin', 31593),
(2, 'Ethereum', 2144.58),
(3, 'Binance Coin', 305.26),
(4, 'XRP', 0.738773),
(5, 'Tether', 0.830121),
(6, 'Dogecoin', 0.282507),
(7, 'Cardano', 1.31),
(8, 'Polkadot', 19.97),
(9, 'Uniswap', 20.6),
(10, 'Bitcoin Cash', 518.85),
(11, 'Litecoin', 142.45),
(12, 'Chainlink', 20.48),
(13, 'USD Coin', 0.829384),
(14, 'VeChain', 0.0973),
(15, 'Solana', 35.05),
(16, 'Stellar', 0.294469),
(17, 'Theta Network', 7.57),
(18, 'Filecoin', 63.39),
(19, 'OKB', 11.88),
(20, 'TRON', 0.062908),
(21, 'Wrapped Bitcoin', 31408),
(24, 'EOS', 4.35),
(26, 'Aave', 275.76),
(27, 'Shiba Inu', 0.00000595),
(28, 'Monero', 230.27),
(29, 'Dai', 0.828199),
(30, 'NEO', 43.89),
(32, 'cETH', 42.84),
(33, 'Klaytn', 0.895641),
(34, 'Maker', 2708.6),
(35, 'cUSDC', 0.0185392),
(36, 'Cosmos', 11.35),
(37, 'Bitcoin SV', 141.45),
(38, 'Crypto.com Coin', 0.102325),
(39, 'Tezos', 2.82),
(40, 'Algorand', 0.842659),
(41, 'cDAI', 0.0177742),
(42, 'THORChain', 8.03),
(43, 'PancakeSwap', 15.24),
(44, 'Kusama', 421.74),
(45, 'Huobi Token', 12.03),
(46, 'IOTA', 0.95354),
(47, 'Celsius Network', 6.08),
(48, 'FTX Token', 27.79),
(49, 'SafeMoon', 0.0000032),
(50, 'BitTorrent', 0.0031216),
(51, 'Terra', 4.85),
(52, 'Avalanche', 12.27),
(53, 'LEO Token', 2.04),
(54, 'Hedera Hashgraph', 0.174454),
(55, 'Synthetix Network Token', 8.91),
(56, 'Compound', 292.41),
(57, 'TerraUSD', 0.829074),
(58, 'Dash', 146.2),
(59, 'Sushi', 8.44),
(60, 'Elrond', 76.37),
(61, 'NEM', 0.145407),
(62, 'Decred', 115.8),
(63, 'yearn.finance', 32931),
(64, 'Telcoin', 0.0274046),
(65, 'Holo', 0.00681473),
(66, 'Zcash', 119.47),
(67, 'Waves', 11.94),
(68, 'Theta Fuel', 0.471951),
(69, 'Amp', 0.0472373),
(70, 'Paxos Standard', 0.828401),
(71, 'Chiliz', 0.197497),
(72, 'Zilliqa', 0.093628),
(73, 'Near', 2.65),
(74, 'TrueUSD', 0.826991),
(75, 'Huobi BTC', 31522),
(76, 'Qtum', 8.51),
(77, 'Helium', 11.62),
(78, 'NEXO', 1.79),
(79, 'Enjin Coin', 1.12),
(80, 'Horizen', 74.97),
(81, 'Lido Staked Ether', 2139.69),
(82, 'Bitcoin Gold', 59.84),
(83, 'Basic Attention Token', 0.574102),
(84, 'Stacks', 0.7809),
(85, 'HUSD', 0.848329),
(86, 'Fei Protocol', 0.819484),
(87, 'Decentraland', 0.605564),
(88, 'The Graph', 0.581491),
(89, 'Harmony', 0.072773),
(90, 'DigiByte', 0.053515),
(91, 'Nano', 5.84),
(92, 'Ontology', 0.840324),
(93, 'SwissBorg', 0.851575),
(94, 'Bancor Network Token', 3.52),
(95, 'UMA', 10.67),
(96, 'OMG Network', 4.33),
(97, 'Siacoin', 0.0144092),
(98, '0x', 0.806952),
(99, 'Pirate Chain', 4.2),
(100, 'Fantom', 0.248517),
(101, 'Internet Computer', 64.11),
(102, 'Polygon', 1.2),
(103, 'Ethereum Classic', 50.29),
(104, 'Binance USD', 0.824891),
(105, 'xSUSHI', 11.85),
(106, 'Ravencoin', 0.082729),
(107, 'Liquity USD', 0.8469),
(108, 'Curve DAO Token', 2.03),
(109, 'BakerySwap', 2.68),
(110, 'Mdex', 1.77),
(111, 'Bitcoin Diamond', 3.34),
(112, 'Arweave', 15);

-- --------------------------------------------------------

--
-- Table structure for table `lista_walut`
--

CREATE TABLE `lista_walut` (
  `id_listy` int NOT NULL,
  `id_portfela` int NOT NULL,
  `id_krypto` int DEFAULT NULL,
  `ilość_krypto` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `lista_walut`
--

INSERT INTO `lista_walut` (`id_listy`, `id_portfela`, `id_krypto`, `ilość_krypto`) VALUES
(1, 1, 2, 0.13301),
(2, 1, 1, 1.1506),
(3, 1, 3, 4.91051),
(4, 1, 4, 0.0000281153),
(6, 1, 6, 776),
(7, 1, 8, 0),
(8, 1, 9, 0),
(9, 1, 5, 0),
(10, 1, 7, 0),
(11, 2, 1, 1.14744),
(12, 2, 3, 4.91051),
(13, 2, 4, 0.0000281153),
(14, 1, 39, 0),
(15, 1, 43, 0),
(16, 1, 103, 0),
(17, 1, 101, 8),
(18, 1, 102, 5),
(19, 1, 28, 0),
(20, 1, 13, 0),
(21, 1, 65, 0),
(22, 1, 11, 0.2),
(23, 1, 83, 0),
(24, 3, 5, 0.22497),
(25, 1, 44, 0);

-- --------------------------------------------------------

--
-- Table structure for table `portfele`
--

CREATE TABLE `portfele` (
  `id_portfela` int NOT NULL,
  `id_użytkownika` int NOT NULL,
  `ilość_euro` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `portfele`
--

INSERT INTO `portfele` (`id_portfela`, `id_użytkownika`, `ilość_euro`) VALUES
(1, 1, 1000.03),
(2, 2, 22604.1),
(3, 3, 26.1439),
(4, 4, 0);

-- --------------------------------------------------------

--
-- Table structure for table `transakcje`
--

CREATE TABLE `transakcje` (
  `id_transakcji` int NOT NULL,
  `id_krypto` int NOT NULL,
  `id_portfela` int NOT NULL,
  `data_transakcji` date NOT NULL,
  `czas_zawarcia` time NOT NULL,
  `ilosc` float NOT NULL,
  `status` varchar(255) NOT NULL,
  `kurs_transakcji` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `transakcje`
--

INSERT INTO `transakcje` (`id_transakcji`, `id_krypto`, `id_portfela`, `data_transakcji`, `czas_zawarcia`, `ilosc`, `status`, `kurs_transakcji`) VALUES
(4, 4, 1, '2021-05-05', '23:26:00', 1, 'SOLD', 1.31),
(5, 6, 1, '2021-05-05', '23:35:00', 2.75, 'BOUGHT', 0.832852),
(6, 6, 1, '2021-05-05', '23:42:00', 2.34, 'BOUGHT', 0.517879),
(7, 6, 1, '2021-05-05', '23:43:00', 1.16, 'BOUGHT', 0.517879),
(8, 6, 1, '2021-05-05', '23:45:00', 5, 'BOUGHT', 0.53168),
(9, 7, 1, '2021-05-05', '23:47:00', 1, 'BOUGHT', 1.22),
(11, 6, 1, '2021-05-05', '23:53:00', 10, 'SWAPPED OF Ethereum', 0.526456),
(12, 6, 1, '2021-05-05', '23:57:00', 10, 'SWAPPED OF Ethereum', 0.526234),
(13, 6, 1, '2021-05-06', '14:40:00', 30, 'BOUGHT', 0.503208),
(14, 1, 1, '2021-05-06', '15:05:00', 0.5, 'SOLD', 47921),
(15, 6, 1, '2021-05-06', '15:07:00', 10, 'BOUGHT', 0.500735),
(16, 4, 1, '2021-05-06', '15:07:00', 1, 'SWAPPED OF Bitcoin', 1.37),
(17, 6, 1, '2021-05-06', '15:32:00', 1, 'BOUGHT', 0.493538),
(18, 6, 1, '2021-05-06', '15:32:00', 1, 'BOUGHT', 0.493538),
(19, 1, 2, '2021-05-06', '15:42:00', 0.1, 'BOUGHT', 47549),
(20, 3, 2, '2021-05-06', '15:42:00', 5, 'BOUGHT', 532.29),
(21, 4, 2, '2021-05-06', '15:42:00', 30, 'SWAPPED OF Binance Coin', 1.36),
(22, 4, 2, '2021-05-06', '15:44:00', 15, 'SOLD', 1.36),
(23, 1, 1, '2021-05-15', '18:19:00', 0.5, 'BOUGHT', 39737),
(24, 2, 1, '2021-05-15', '18:27:00', 1, 'BOUGHT', 3141.15),
(25, 2, 1, '2021-05-15', '18:27:00', 0.2, 'BOUGHT', 3141.15),
(26, 1, 1, '2021-05-16', '13:14:00', 0, 'BOUGHT', 40720),
(27, 4, 1, '2021-05-16', '14:36:00', 1, 'BOUGHT', 1.28),
(28, 4, 1, '2021-05-16', '14:37:00', 5, 'BOUGHT', 1.27),
(29, 7, 1, '2021-05-16', '15:02:00', 1, 'SOLD', 1.92),
(30, 4, 1, '2021-05-16', '15:04:00', 5, 'SWAPPED OF Binance Coin', 1.25),
(31, 5, 1, '2021-05-16', '17:23:00', 2, 'SOLD', 0.82427),
(32, 5, 1, '2021-05-16', '17:23:00', 4, 'BOUGHT', 0.82427),
(33, 5, 1, '2021-05-16', '17:23:00', 4, 'SOLD', 0.82427),
(34, 8, 1, '2021-05-16', '17:25:00', 3, 'SOLD', 35.97),
(35, 6, 1, '2021-05-16', '21:49:00', 3, 'BOUGHT', 0.417593),
(36, 8, 1, '2021-05-24', '16:08:00', 1, 'BOUGHT', 16.66),
(37, 4, 1, '2021-05-24', '16:19:00', 5, 'BOUGHT', 0.709712),
(38, 4, 1, '2021-05-24', '22:14:00', 12, 'SOLD', 0.764359),
(39, 4, 1, '2021-05-24', '22:15:00', 5, 'BOUGHT', 0.764359),
(40, 4, 1, '2021-05-25', '20:57:00', 5, 'BOUGHT', 0.772479),
(41, 4, 1, '2021-05-26', '17:56:00', 5, 'SWAPPED OF Ethereum', 0.8101),
(42, 5, 1, '2021-05-26', '19:48:00', 100, 'BOUGHT', 0.814346),
(43, 4, 1, '2021-05-26', '20:03:00', 15, 'SWAPPED OF Ethereum', 0.820457),
(44, 5, 1, '2021-05-26', '20:03:00', 100, 'BOUGHT', 0.829673),
(45, 5, 1, '2021-05-26', '20:04:00', 100, 'SWAPPED OF Ethereum', 0.829673),
(46, 5, 1, '2021-05-26', '20:06:00', 100, 'BOUGHT', 0.829673),
(47, 5, 1, '2021-05-27', '10:13:00', 30, 'BOUGHT', 0.817287),
(48, 5, 1, '2021-05-27', '10:13:00', 30, 'BOUGHT', 0.817287),
(49, 5, 1, '2021-05-27', '10:15:00', 6, 'BOUGHT', 0.817287),
(50, 5, 1, '2021-05-27', '10:16:00', 5, 'BOUGHT', 0.817287),
(51, 39, 1, '2021-05-27', '10:16:00', 1, 'BOUGHT', 3.06),
(52, 43, 1, '2021-05-27', '10:17:00', 1, 'BOUGHT', 14.75),
(53, 103, 1, '2021-05-27', '10:18:00', 1, 'BOUGHT', 61.55),
(54, 2, 1, '2021-05-27', '10:22:00', 1, 'SOLD', 2252.35),
(55, 2, 1, '2021-05-27', '10:23:00', 1, 'BOUGHT', 2252.35),
(56, 39, 1, '2021-05-27', '10:39:00', 1, 'SOLD', 3.13),
(57, 103, 1, '2021-05-27', '10:40:00', 1, 'SOLD', 62.36),
(58, 43, 1, '2021-05-27', '10:40:00', 1, 'SOLD', 14.97),
(59, 43, 1, '2021-05-27', '10:41:00', 4, 'BOUGHT', 14.97),
(60, 43, 1, '2021-05-27', '10:41:00', 2, 'SOLD', 14.97),
(61, 2, 1, '2021-05-27', '10:43:00', 1, 'SOLD', 2272.68),
(63, 101, 1, '2021-05-27', '10:43:00', 1, 'SOLD', 110.75),
(64, 43, 1, '2021-05-27', '10:44:00', 1, 'BOUGHT', 14.99),
(65, 102, 1, '2021-05-27', '11:00:00', 5, 'BOUGHT', 1.84),
(66, 43, 1, '2021-05-27', '11:01:00', 5, 'BOUGHT', 15.01),
(67, 101, 1, '2021-05-27', '11:07:00', 1, 'SOLD', 110.76),
(68, 43, 1, '2021-05-27', '11:08:00', 1, 'BOUGHT', 15.02),
(69, 43, 1, '2021-05-27', '11:12:00', 1, 'SOLD', 15.04),
(70, 43, 1, '2021-05-27', '11:12:00', 3, 'SOLD', 15.04),
(71, 28, 1, '2021-05-27', '11:13:00', 1, 'BOUGHT', 211.77),
(72, 13, 1, '2021-05-27', '11:21:00', 50, 'BOUGHT', 0.82084),
(73, 65, 1, '2021-05-27', '11:22:00', 100, 'BOUGHT', 0.00824296),
(74, 43, 1, '2021-05-27', '11:24:00', 5, 'SOLD', 15.08),
(75, 11, 1, '2021-05-27', '12:20:00', 0.2, 'BOUGHT', 162.05),
(76, 6, 1, '2021-05-27', '12:48:00', 666, 'BOUGHT', 0.280739),
(77, 83, 1, '2021-05-27', '12:50:00', 100, 'BOUGHT', 0.711555),
(78, 5, 1, '2021-05-27', '12:52:00', 2, 'SWAPPED OF XRP', 0.8203),
(79, 4, 1, '2021-05-27', '12:52:00', 333, 'BOUGHT', 0.826812),
(80, 83, 1, '2021-05-27', '13:25:00', 100, 'SOLD', 0.717159),
(81, 65, 1, '2021-05-27', '13:26:00', 100, 'SOLD', 0.00837434),
(82, 5, 1, '2021-05-27', '13:52:00', 73, 'SOLD', 0.822124),
(83, 5, 3, '2021-05-27', '14:45:00', 150, 'BOUGHT', 0.825707),
(84, 4, 1, '2021-06-09', '13:27:00', 5, 'BOUGHT', 0.714248),
(85, 2, 1, '2021-06-09', '13:27:00', 1.19072, 'SOLD', 2062.4),
(86, 4, 1, '2021-06-09', '13:28:00', 4, 'BOUGHT', 0.714301),
(87, 2, 1, '2021-06-09', '13:29:00', 1.4, 'BOUGHT', 2062.04),
(88, 4, 1, '2021-06-09', '13:32:00', 5, 'BOUGHT', 0.714065),
(89, 1, 1, '2021-06-09', '14:27:00', 0.0127414, 'BOUGHT', 28998),
(90, 1, 1, '2021-06-09', '14:34:00', 0.00049, 'BOUGHT', 28944),
(91, 4, 1, '2021-06-09', '15:11:00', 4660.86, 'SWAPPED OF Ethereum', 0.710671),
(92, 5, 1, '2021-06-09', '15:25:00', 400, 'SOLD', 0.819792),
(93, 5, 1, '2021-06-09', '15:32:00', 0.00000000000000421885, 'SWAPPED OF Bitcoin', 0.819792),
(94, 4, 1, '2021-06-09', '15:36:00', 40824.2, 'SWAPPED OF Bitcoin', 0.712219),
(95, 1, 1, '2021-06-09', '15:38:00', 1.11329, 'SWAPPED OF XRP', 28672),
(96, 1, 1, '2021-06-09', '15:39:00', 0.01144, 'BOUGHT', 28666),
(97, 1, 1, '2021-06-09', '15:39:00', 0.02271, 'SWAPPED OF XRP', 28666),
(98, 1, 1, '2021-06-09', '20:37:00', 1.14744, 'SOLD', 29970),
(99, 1, 1, '2021-06-09', '20:39:00', 1.00648, 'BOUGHT', 29777),
(100, 1, 1, '2021-06-09', '20:50:00', 1.15392, 'SOLD', 29630),
(101, 1, 1, '2021-06-09', '21:05:00', 1, 'BOUGHT', 29617),
(102, 1, 1, '2021-06-09', '21:46:00', 1.15392, 'SOLD', 29809),
(103, 1, 1, '2021-06-09', '21:46:00', 1.00034, 'BOUGHT', 29809),
(104, 1, 1, '2021-06-09', '21:54:00', 1.15426, 'SOLD', 29698),
(105, 1, 1, '2021-06-09', '22:04:00', 0.99634, 'BOUGHT', 29807),
(106, 13, 1, '2021-06-10', '00:22:00', 50, 'SOLD', 0.822706),
(107, 28, 1, '2021-06-10', '00:23:00', 1, 'SOLD', 221.71),
(108, 5, 1, '2021-06-10', '00:24:00', 0.22497, 'SWAPPED OF XRP', 0.825006),
(109, 4, 1, '2021-06-10', '00:31:00', 0.0000281153, 'SOLD', 0.742436),
(110, 4, 1, '2021-06-10', '00:32:00', 0.0000281153, 'SOLD', 0.742436),
(111, 4, 1, '2021-06-10', '00:32:00', 0.0000281153, 'SOLD', 0.743612),
(112, 5, 1, '2021-06-10', '00:36:00', 0.22497, 'SOLD', 0.825006),
(113, 5, 1, '2021-06-10', '00:39:00', 0.22497, 'SOLD', 0.825006),
(114, 5, 1, '2021-06-10', '00:41:00', 0.22497, 'SOLD', 0.825006),
(115, 5, 1, '2021-06-10', '00:43:00', 0.22497, 'SOLD', 0.825006),
(116, 5, 1, '2021-06-10', '00:49:00', 0.22497, 'SOLD', 0.825006),
(117, 8, 1, '2021-06-10', '00:49:00', 1, 'SOLD', 19),
(118, 5, 1, '2021-06-10', '00:50:00', 0.22497, 'SOLD', 0.825006),
(119, 5, 1, '2021-06-10', '00:52:00', 0.22496, 'SOLD', 0.825006),
(120, 5, 1, '2021-06-10', '00:53:00', 0.22497, 'SOLD', 0.825006),
(121, 5, 1, '2021-06-10', '00:55:00', 0.22497, 'SOLD', 0.825006),
(122, 2, 1, '2021-06-10', '00:56:00', 0.13301, 'BOUGHT', 2142.65),
(123, 44, 1, '2021-06-10', '10:58:00', 2.47092, 'BOUGHT', 404.68),
(124, 44, 1, '2021-06-10', '10:59:00', 2.47092, 'SOLD', 404.68);

-- --------------------------------------------------------

--
-- Table structure for table `użytkownicy`
--

CREATE TABLE `użytkownicy` (
  `id_użytkownika` int NOT NULL,
  `imię` varchar(255) NOT NULL,
  `nazwisko` varchar(255) NOT NULL,
  `nr_telefonu` int NOT NULL,
  `adres_email` varchar(255) NOT NULL,
  `haslo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `użytkownicy`
--

INSERT INTO `użytkownicy` (`id_użytkownika`, `imię`, `nazwisko`, `nr_telefonu`, `adres_email`, `haslo`) VALUES
(1, 'Bartosz', 'Koscielniak', 555111555, 'g@gmail.com', '$2y$10$JNIXd0urLbXy2Xp.sJHTHOklUDc4cCWBZktR7TqWDO7HjBs.6AmuO'),
(2, 'test', 'test', 111222333, 'test@gmail.com', '$2y$10$/Q6FCoHjYj9iVn84Eid7hezbDEz0w0WrIedsj7TdyBuCpNbrgcL3W'),
(3, 'Andrzej', 'Tuliglowa', 111252333, 'mail@mail.com', '$2y$10$hPhq7xokzLtuttP.mzgcOOS6ODLEG/H0GoyGDCQi03me5uoPs/0Fq'),
(4, 'Bartek', 'Koscielniak', 123123123, 'tes1t@gmail.com', '$2y$10$SYb..yIhiTRycapproRaQOVPu4zcHBgNcSxTlHVOGUsQlo8.9hjK.');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `kryptowaluty`
--
ALTER TABLE `kryptowaluty`
  ADD PRIMARY KEY (`id_krypto`);

--
-- Indexes for table `lista_walut`
--
ALTER TABLE `lista_walut`
  ADD PRIMARY KEY (`id_listy`),
  ADD KEY `id_krypto` (`id_krypto`),
  ADD KEY `id_portfela` (`id_portfela`);

--
-- Indexes for table `portfele`
--
ALTER TABLE `portfele`
  ADD PRIMARY KEY (`id_portfela`),
  ADD KEY `id_użytkownika` (`id_użytkownika`);

--
-- Indexes for table `transakcje`
--
ALTER TABLE `transakcje`
  ADD PRIMARY KEY (`id_transakcji`),
  ADD KEY `id_krypto` (`id_krypto`),
  ADD KEY `id_portfela` (`id_portfela`);

--
-- Indexes for table `użytkownicy`
--
ALTER TABLE `użytkownicy`
  ADD PRIMARY KEY (`id_użytkownika`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `lista_walut`
--
ALTER TABLE `lista_walut`
  MODIFY `id_listy` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `transakcje`
--
ALTER TABLE `transakcje`
  MODIFY `id_transakcji` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=125;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `lista_walut`
--
ALTER TABLE `lista_walut`
  ADD CONSTRAINT `lista_walut_ibfk_1` FOREIGN KEY (`id_krypto`) REFERENCES `kryptowaluty` (`id_krypto`),
  ADD CONSTRAINT `lista_walut_ibfk_2` FOREIGN KEY (`id_portfela`) REFERENCES `portfele` (`id_portfela`);

--
-- Constraints for table `portfele`
--
ALTER TABLE `portfele`
  ADD CONSTRAINT `portfele_ibfk_1` FOREIGN KEY (`id_użytkownika`) REFERENCES `użytkownicy` (`id_użytkownika`);

--
-- Constraints for table `transakcje`
--
ALTER TABLE `transakcje`
  ADD CONSTRAINT `transakcje_ibfk_1` FOREIGN KEY (`id_krypto`) REFERENCES `kryptowaluty` (`id_krypto`),
  ADD CONSTRAINT `transakcje_ibfk_2` FOREIGN KEY (`id_portfela`) REFERENCES `portfele` (`id_portfela`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
