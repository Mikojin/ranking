-- phpMyAdmin SQL Dump
-- version 4.7.3
-- https://www.phpmyadmin.net/
--
-- Hôte : tesatnsimbtesadb.mysql.db
-- Généré le :  mar. 12 fév. 2019 à 01:49
-- Version du serveur :  5.6.39-log
-- Version de PHP :  7.0.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `tesatnsimbtesadb`
--

-- --------------------------------------------------------

--
-- Structure de la table `character`
--

CREATE TABLE `character` (
  `id` int(11) NOT NULL,
  `id_game` int(11) NOT NULL,
  `name` varchar(100) CHARACTER SET latin1 NOT NULL,
  `css_class` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `filename` varchar(250) CHARACTER SET latin1 DEFAULT NULL,
  `tstp_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tstp_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Déchargement des données de la table `character`
--

INSERT INTO `character` (`id`, `id_game`, `name`, `css_class`, `filename`, `tstp_create`, `tstp_update`) VALUES
(1, 1, 'Ryu', 'ryu', 'Ryu.png', '2018-08-30 22:12:45', '2018-08-30 23:03:39'),
(2, 1, 'Ken', 'ken', 'Ken.png', '2018-08-30 22:12:45', '2018-08-30 23:03:50'),
(3, 1, 'ChunLi', 'chunli', 'Chun-Li.png', '2018-08-30 22:12:45', '2018-08-30 23:05:55'),
(4, 1, 'Akuma', 'akuma', 'Gouki.png', '2018-08-30 22:12:45', '2018-08-30 23:03:16'),
(5, 1, 'Abigail', 'abigail', 'Abigail.png', '2018-08-30 22:12:45', '2018-08-30 23:04:24'),
(6, 1, 'Alex', 'alex', 'Alex.png', '2018-08-30 22:12:45', '2018-08-30 23:04:37'),
(7, 1, 'Balrog', 'balrog', 'Balrog.png', '2018-08-30 22:12:45', '2018-08-30 23:04:47'),
(8, 1, 'Birdie', 'birdie', 'Birdie.png', '2018-08-30 22:12:45', '2018-08-30 23:05:01'),
(9, 1, 'Bison', 'bison', 'M.Bison.png', '2018-08-30 22:12:45', '2018-08-30 23:05:17'),
(10, 1, 'Blanka', 'blanka', 'Blanka.png', '2018-08-30 22:12:45', '2018-08-30 23:05:27'),
(11, 1, 'Cammy', 'cammy', 'Cammy.png', '2018-08-30 22:12:45', '2018-08-30 23:05:40'),
(12, 1, 'Dhalsim', 'dhalsim', 'Dhalsim.png', '2018-08-30 22:12:45', '2018-08-30 23:19:10'),
(13, 1, 'Ed', 'ed', 'ED.png', '2018-08-30 22:12:45', '2018-08-30 23:19:23'),
(14, 1, 'Fang', 'fang', 'F.A.N.G.png', '2018-08-30 22:12:45', '2018-08-30 23:19:50'),
(15, 1, 'Guile', 'guile', 'Guile.png', '2018-08-30 22:12:45', '2018-08-30 23:21:04'),
(16, 1, 'Ibuki', 'ibuki', 'Ibuki.png', '2018-08-30 22:12:45', '2018-08-30 23:21:20'),
(17, 1, 'Juri', 'juri', 'Juri.png', '2018-08-30 22:12:45', '2018-08-30 23:21:33'),
(18, 1, 'Karin', 'karin', 'Karin.png', '2018-08-30 22:12:45', '2018-08-30 23:21:44'),
(19, 1, 'Kolin', 'kolin', 'Kolin.png', '2018-08-30 22:12:45', '2018-08-30 23:21:56'),
(20, 1, 'Laura', 'laura', 'Laura.png', '2018-08-30 22:12:45', '2018-08-30 23:22:05'),
(21, 1, 'Menat', 'menat', 'Menat.png', '2018-08-30 22:12:45', '2018-08-30 23:22:14'),
(22, 1, 'Mika', 'mika', 'Mika.png', '2018-08-30 22:12:45', '2018-08-30 23:22:23'),
(23, 1, 'Nash', 'nash', 'Nash.png', '2018-08-30 22:12:45', '2018-08-30 23:22:32'),
(24, 1, 'Necali', 'nekali', 'Necali.png', '2018-08-30 22:12:45', '2018-08-30 23:22:42'),
(25, 1, 'Rashid', 'rashid', 'Rashid.png', '2018-08-30 22:12:45', '2018-08-30 23:22:53'),
(26, 1, 'Sakura', 'sakura', 'Sakura.png', '2018-08-30 22:12:45', '2018-08-30 23:23:09'),
(27, 1, 'Vega', 'vega', 'Vega.png', '2018-08-30 22:12:45', '2018-08-30 23:23:58'),
(28, 1, 'Urien', 'urien', 'Urien.png', '2018-08-30 22:12:45', '2018-08-30 23:23:40'),
(29, 1, 'Zangief', 'zangief', 'Zangief', '2018-08-30 22:12:45', '2018-08-30 23:24:09'),
(30, 1, 'Zeku', 'zeku', 'Zeku.png', '2018-08-30 22:12:45', '2018-08-30 23:24:17'),
(31, 1, 'Falke', 'falke', 'Falke.png', '2018-08-30 22:12:45', '2018-08-30 23:19:34'),
(32, 1, '0Unknown', 'unknown', 'Unknown.png', '2018-08-30 22:12:45', '2018-08-30 23:02:15'),
(33, 1, 'Cody', 'cody', 'Cody.png', '2018-08-30 22:12:45', '2018-08-30 23:06:45'),
(34, 1, 'G', 'g', 'G.png', '2018-08-30 22:12:45', '2018-08-30 23:20:09'),
(35, 1, 'Sagat', 'sagat', 'Sagat.png', '2018-08-30 22:12:45', '2018-08-30 23:23:02'),
(36, 1, 'Kage', 'kage', 'Kage.png', '2019-01-30 15:21:14', '2019-01-30 15:22:29'),
(37, 2, '0Unknown', 'unknown', 'Unknown.png', '2019-02-11 20:22:06', '2019-02-11 20:22:06'),
(38, 2, 'Alisa', 'alisa', 'Alisa.png', '2019-02-11 20:22:06', '2019-02-11 20:22:06'),
(39, 2, 'Devil Jin', 'deviljin', 'DevilJin.png', '2019-02-11 20:22:06', '2019-02-11 20:22:06');

-- --------------------------------------------------------

--
-- Structure de la table `game`
--

CREATE TABLE `game` (
  `id` int(11) NOT NULL,
  `code` varchar(20) CHARACTER SET latin1 NOT NULL,
  `name` varchar(150) CHARACTER SET latin1 NOT NULL,
  `id_char_unknown` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Déchargement des données de la table `game`
--

INSERT INTO `game` (`id`, `code`, `name`, `id_char_unknown`) VALUES
(1, 'SFV', 'Street Fighter V', 32),
(2, 'T7', 'Tekken 7', 37),
(3, 'DBFZ', 'Dragon Ball FighterZ', NULL),
(4, 'KOFXIV', 'The King of Fighters XIV', NULL),
(5, 'MVCI', 'Marvel vs Capcom Infinite', NULL),
(6, 'GGXR', 'Guilty Gear Xrd Revelator', NULL),
(7, 'SFIV', 'Street Fighter IV', NULL),
(8, 'MVC3', 'Marvel vs Capcom 3', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `historanking`
--

CREATE TABLE `historanking` (
  `id_update` int(11) NOT NULL,
  `id_player` int(11) NOT NULL,
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `points` int(11) NOT NULL,
  `rank` int(11) NOT NULL,
  `id_game` int(11) NOT NULL,
  `id_char` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Déchargement des données de la table `historanking`
--

INSERT INTO `historanking` (`id_update`, `id_player`, `date_creation`, `points`, `rank`, `id_game`, `id_char`) VALUES
(1, 7, '2018-05-05 16:14:08', 220, 2, 1, 29),
(1, 2, '2018-05-05 16:14:08', 170, 1, 1, 4),
(1, 6, '2018-05-05 16:14:08', 115, 3, 1, 9),
(1, 5, '2018-05-05 16:14:08', 80, 4, 1, 15),
(1, 3, '2018-05-05 16:14:08', 70, 5, 1, 22),
(1, 12, '2018-05-05 16:14:08', 55, 6, 1, 8),
(1, 14, '2018-05-05 16:14:09', 45, 100, 1, 15),
(1, 4, '2018-05-05 16:14:09', 40, 7, 1, 21),
(1, 9, '2018-05-05 16:14:09', 35, 8, 1, 6),
(1, 10, '2018-05-05 16:14:09', 25, 9, 1, 2),
(1, 15, '2018-05-05 16:14:09', 25, 100, 1, 2),
(1, 11, '2018-05-05 16:14:09', 10, 10, 1, 7),
(1, 1, '2018-05-05 16:14:09', 10, 10, 1, 20),
(1, 16, '2018-05-05 16:14:09', 10, 11, 1, 26),
(1, 13, '2018-05-05 16:14:09', 5, 100, 1, 5),
(1, 17, '2018-05-05 16:14:09', 5, 100, 1, 1),
(1, 18, '2018-05-05 16:14:09', 1, 12, 1, 24),
(2, 7, '2018-05-06 12:40:20', 220, 1, 1, 29),
(2, 2, '2018-05-06 12:40:20', 170, 2, 1, 4),
(2, 6, '2018-05-06 12:40:20', 115, 3, 1, 9),
(2, 5, '2018-05-06 12:40:20', 80, 4, 1, 15),
(2, 3, '2018-05-06 12:40:21', 70, 5, 1, 22),
(2, 12, '2018-05-06 12:40:21', 55, 6, 1, 8),
(2, 14, '2018-05-06 12:40:21', 45, 7, 1, 15),
(2, 4, '2018-05-06 12:40:21', 40, 8, 1, 21),
(2, 9, '2018-05-06 12:40:21', 35, 9, 1, 6),
(2, 10, '2018-05-06 12:40:21', 25, 10, 1, 2),
(2, 15, '2018-05-06 12:40:21', 25, 10, 1, 2),
(2, 11, '2018-05-06 12:40:21', 10, 12, 1, 7),
(2, 1, '2018-05-06 12:40:21', 10, 12, 1, 20),
(2, 16, '2018-05-06 12:40:21', 10, 12, 1, 26),
(2, 13, '2018-05-06 12:40:21', 5, 15, 1, 5),
(2, 17, '2018-05-06 12:40:21', 5, 15, 1, 1),
(2, 18, '2018-05-06 12:40:21', 1, 17, 1, 24),
(3, 7, '2018-05-06 12:53:25', 220, 1, 1, 29),
(3, 2, '2018-05-06 12:53:25', 170, 2, 1, 4),
(3, 6, '2018-05-06 12:53:25', 115, 3, 1, 9),
(3, 5, '2018-05-06 12:53:25', 80, 4, 1, 15),
(3, 3, '2018-05-06 12:53:25', 70, 5, 1, 22),
(3, 12, '2018-05-06 12:53:25', 55, 6, 1, 8),
(3, 14, '2018-05-06 12:53:25', 45, 7, 1, 15),
(3, 4, '2018-05-06 12:53:26', 40, 8, 1, 21),
(3, 9, '2018-05-06 12:53:26', 35, 9, 1, 6),
(3, 10, '2018-05-06 12:53:26', 25, 10, 1, 2),
(3, 15, '2018-05-06 12:53:26', 25, 10, 1, 2),
(3, 11, '2018-05-06 12:53:26', 10, 12, 1, 7),
(3, 1, '2018-05-06 12:53:26', 10, 12, 1, 20),
(3, 16, '2018-05-06 12:53:26', 10, 12, 1, 26),
(3, 13, '2018-05-06 12:53:26', 5, 15, 1, 5),
(3, 17, '2018-05-06 12:53:26', 5, 15, 1, 1),
(3, 18, '2018-05-06 12:53:26', 1, 17, 1, 24),
(1, 7, '2018-05-05 16:14:08', 220, 2, 1, 29),
(1, 2, '2018-05-05 16:14:08', 170, 1, 1, 4),
(1, 6, '2018-05-05 16:14:08', 115, 3, 1, 9),
(1, 5, '2018-05-05 16:14:08', 80, 4, 1, 15),
(1, 3, '2018-05-05 16:14:08', 70, 5, 1, 22),
(1, 12, '2018-05-05 16:14:08', 55, 6, 1, 8),
(1, 14, '2018-05-05 16:14:09', 45, 100, 1, 15),
(1, 4, '2018-05-05 16:14:09', 40, 7, 1, 21),
(1, 9, '2018-05-05 16:14:09', 35, 8, 1, 6),
(1, 10, '2018-05-05 16:14:09', 25, 9, 1, 2),
(1, 15, '2018-05-05 16:14:09', 25, 100, 1, 2),
(1, 11, '2018-05-05 16:14:09', 10, 10, 1, 7),
(1, 1, '2018-05-05 16:14:09', 10, 10, 1, 20),
(1, 16, '2018-05-05 16:14:09', 10, 11, 1, 26),
(1, 13, '2018-05-05 16:14:09', 5, 100, 1, 5),
(1, 17, '2018-05-05 16:14:09', 5, 100, 1, 1),
(1, 18, '2018-05-05 16:14:09', 1, 12, 1, 24),
(2, 7, '2018-05-06 12:40:20', 220, 1, 1, 29),
(2, 2, '2018-05-06 12:40:20', 170, 2, 1, 4),
(2, 6, '2018-05-06 12:40:20', 115, 3, 1, 9),
(2, 5, '2018-05-06 12:40:20', 80, 4, 1, 15),
(2, 3, '2018-05-06 12:40:21', 70, 5, 1, 22),
(2, 12, '2018-05-06 12:40:21', 55, 6, 1, 8),
(2, 14, '2018-05-06 12:40:21', 45, 7, 1, 15),
(2, 4, '2018-05-06 12:40:21', 40, 8, 1, 21),
(2, 9, '2018-05-06 12:40:21', 35, 9, 1, 6),
(2, 10, '2018-05-06 12:40:21', 25, 10, 1, 2),
(2, 15, '2018-05-06 12:40:21', 25, 10, 1, 2),
(2, 11, '2018-05-06 12:40:21', 10, 12, 1, 7),
(2, 1, '2018-05-06 12:40:21', 10, 12, 1, 20),
(2, 16, '2018-05-06 12:40:21', 10, 12, 1, 26),
(2, 13, '2018-05-06 12:40:21', 5, 15, 1, 5),
(2, 17, '2018-05-06 12:40:21', 5, 15, 1, 1),
(2, 18, '2018-05-06 12:40:21', 1, 17, 1, 24),
(3, 7, '2018-05-06 12:53:25', 220, 1, 1, 29),
(3, 2, '2018-05-06 12:53:25', 170, 2, 1, 4),
(3, 6, '2018-05-06 12:53:25', 115, 3, 1, 9),
(3, 5, '2018-05-06 12:53:25', 80, 4, 1, 15),
(3, 3, '2018-05-06 12:53:25', 70, 5, 1, 22),
(3, 12, '2018-05-06 12:53:25', 55, 6, 1, 8),
(3, 14, '2018-05-06 12:53:25', 45, 7, 1, 15),
(3, 4, '2018-05-06 12:53:26', 40, 8, 1, 21),
(3, 9, '2018-05-06 12:53:26', 35, 9, 1, 6),
(3, 10, '2018-05-06 12:53:26', 25, 10, 1, 2),
(3, 15, '2018-05-06 12:53:26', 25, 10, 1, 2),
(3, 11, '2018-05-06 12:53:26', 10, 12, 1, 7),
(3, 1, '2018-05-06 12:53:26', 10, 12, 1, 20),
(3, 16, '2018-05-06 12:53:26', 10, 12, 1, 26),
(3, 13, '2018-05-06 12:53:26', 5, 15, 1, 5),
(3, 17, '2018-05-06 12:53:26', 5, 15, 1, 1),
(3, 18, '2018-05-06 12:53:26', 1, 17, 1, 24);

-- --------------------------------------------------------

--
-- Structure de la table `param`
--

CREATE TABLE `param` (
  `group_name` varchar(50) CHARACTER SET latin1 NOT NULL,
  `variable` varchar(50) CHARACTER SET latin1 NOT NULL,
  `value` varchar(200) CHARACTER SET latin1 NOT NULL,
  `tstp_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tstp_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Déchargement des données de la table `param`
--

INSERT INTO `param` (`group_name`, `variable`, `value`, `tstp_create`, `tstp_update`) VALUES
('FONT', 'font_name', 'hvd_comic_serif_pro', '2018-08-30 21:48:04', '2018-08-30 21:48:04'),
('FONT', 'font_pseudo', 'hvd_comic_serif_pro', '2018-08-30 21:48:04', '2018-09-21 12:13:19'),
('FONT', 'font_rank', 'painting_with_chocolate', '2018-08-30 21:48:04', '2018-09-21 12:12:28'),
('FONT', 'font_score', 'levirebrushed', '2018-08-30 21:48:04', '2018-08-30 21:48:04'),
('PATH', 'character', '/ranking/Images/Character', '2018-09-01 21:05:15', '2019-02-09 15:03:37');

-- --------------------------------------------------------

--
-- Structure de la table `participant`
--

CREATE TABLE `participant` (
  `id` int(11) NOT NULL,
  `id_tournament` int(11) NOT NULL,
  `id_player` int(11) NOT NULL,
  `ranking` int(11) NOT NULL DEFAULT '0',
  `tstp_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tstp_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Déchargement des données de la table `participant`
--

INSERT INTO `participant` (`id`, `id_tournament`, `id_player`, `ranking`, `tstp_create`, `tstp_update`) VALUES
(1, 3, 2, 1, '2018-09-13 18:49:57', '2018-09-13 22:11:46'),
(2, 3, 6, 2, '2018-09-13 18:49:57', '2018-09-13 22:11:46'),
(3, 3, 18, 7, '2018-09-13 22:10:34', '2018-09-13 22:11:46'),
(4, 3, 5, 4, '2018-09-13 22:10:34', '2018-09-13 22:11:46'),
(5, 3, 4, 5, '2018-09-13 22:10:34', '2018-09-13 22:11:46'),
(6, 3, 9, 3, '2018-09-13 22:10:34', '2018-09-13 22:11:46'),
(7, 3, 11, 6, '2018-09-13 22:10:34', '2018-09-13 22:11:46'),
(13, 4, 5, 2, '2018-09-13 22:25:48', '2018-09-13 22:26:24'),
(14, 4, 4, 3, '2018-09-13 22:25:48', '2018-09-13 22:26:24'),
(15, 4, 3, 1, '2018-09-13 22:25:48', '2018-09-13 22:26:24'),
(16, 4, 11, 5, '2018-09-13 22:25:48', '2018-09-13 22:27:26'),
(17, 4, 20, 6, '2018-09-13 22:25:48', '2018-09-13 22:27:26'),
(18, 4, 9, 4, '2018-09-13 22:26:24', '2018-09-13 22:27:26'),
(20, 4, 35, 5, '2018-09-14 09:39:00', '2018-09-14 09:39:35'),
(21, 4, 36, 7, '2018-09-14 09:39:44', '2018-09-14 09:39:51'),
(22, 5, 7, 1, '2018-09-14 09:43:35', '2018-09-14 09:44:09'),
(23, 5, 14, 2, '2018-09-14 09:44:20', '2018-09-14 09:44:25'),
(24, 5, 15, 3, '2018-09-14 09:45:07', '2018-09-14 09:45:13'),
(25, 5, 12, 4, '2018-09-14 09:45:25', '2018-09-14 09:45:33'),
(26, 5, 17, 5, '2018-09-14 09:45:56', '2018-09-14 09:46:05'),
(27, 5, 4, 5, '2018-09-14 09:46:26', '2018-09-14 09:46:32'),
(28, 5, 26, 7, '2018-09-14 09:47:39', '2018-09-14 09:47:46'),
(29, 5, 27, 7, '2018-09-14 09:48:02', '2018-09-14 09:48:12'),
(30, 5, 37, 9, '2018-09-14 09:49:22', '2018-09-14 09:49:29'),
(31, 5, 38, 9, '2018-09-14 09:50:07', '2018-09-14 09:50:15'),
(32, 6, 15, 1, '2018-09-14 09:53:21', '2018-09-14 09:54:06'),
(33, 6, 4, 2, '2018-09-14 09:53:29', '2018-09-14 09:54:06'),
(34, 6, 21, 3, '2018-09-14 09:53:41', '2018-09-14 09:54:06'),
(35, 6, 20, 4, '2018-09-14 09:53:54', '2018-09-14 09:54:06'),
(36, 6, 22, 5, '2018-09-14 09:54:20', '2018-09-14 09:55:30'),
(37, 6, 23, 5, '2018-09-14 09:54:40', '2018-09-14 09:55:30'),
(38, 6, 24, 7, '2018-09-14 09:55:04', '2018-09-14 09:55:30'),
(39, 6, 25, 7, '2018-09-14 09:55:48', '2018-09-14 09:55:55'),
(40, 7, 7, 1, '2018-09-14 10:00:30', '2018-09-14 10:01:40'),
(41, 7, 2, 2, '2018-09-14 10:00:37', '2018-09-14 10:01:40'),
(42, 7, 6, 3, '2018-09-14 10:00:41', '2018-09-14 10:01:40'),
(43, 7, 14, 5, '2018-09-14 10:00:47', '2018-09-14 10:02:14'),
(44, 7, 3, 7, '2018-09-14 10:00:57', '2018-09-14 10:02:23'),
(45, 7, 5, 5, '2018-09-14 10:01:18', '2018-09-14 10:02:14'),
(46, 7, 15, 4, '2018-09-14 10:01:40', '2018-09-14 10:02:14'),
(47, 7, 4, 7, '2018-09-14 10:02:14', '2018-09-14 10:02:23'),
(48, 8, 7, 1, '2018-09-14 10:12:21', '2018-09-14 10:15:55'),
(49, 8, 2, 2, '2018-09-14 10:12:27', '2018-09-14 10:15:55'),
(50, 8, 5, 5, '2018-09-14 10:12:34', '2018-09-14 10:15:55'),
(51, 8, 6, 3, '2018-09-14 10:12:46', '2018-09-14 10:15:55'),
(52, 8, 10, 5, '2018-09-14 10:12:59', '2018-09-14 10:15:55'),
(53, 8, 12, 4, '2018-09-14 10:13:09', '2018-09-14 10:15:55'),
(54, 8, 1, 7, '2018-09-14 10:13:16', '2018-09-14 10:15:55'),
(55, 8, 13, 9, '2018-09-14 10:13:36', '2018-09-14 10:15:55'),
(56, 8, 16, 7, '2018-09-14 10:13:48', '2018-09-14 10:15:55'),
(57, 8, 4, 9, '2018-09-14 10:13:59', '2018-09-14 10:15:55'),
(58, 8, 39, 9, '2018-09-14 10:23:40', '2018-09-14 10:23:51'),
(59, 9, 6, 2, '2018-09-14 10:28:28', '2018-09-14 10:29:44'),
(61, 9, 5, 4, '2018-09-14 10:28:28', '2018-09-14 10:29:44'),
(62, 9, 30, 5, '2018-09-14 10:28:28', '2018-09-14 10:29:44'),
(63, 9, 7, 1, '2018-09-14 10:28:28', '2018-09-14 10:29:44'),
(64, 9, 2, 3, '2018-09-14 10:28:28', '2018-09-14 10:29:44'),
(65, 9, 1, 5, '2018-09-14 10:28:28', '2018-09-14 10:29:44'),
(66, 9, 9, 7, '2018-09-14 10:34:32', '2018-09-14 10:35:49'),
(67, 9, 33, 9, '2018-09-14 10:35:49', '2018-09-14 10:44:12'),
(68, 9, 41, 9, '2018-09-14 10:41:24', '2018-09-14 10:44:12'),
(69, 9, 32, 13, '2018-09-14 10:41:24', '2018-09-14 10:44:12'),
(70, 9, 42, 9, '2018-09-14 10:41:24', '2018-09-14 10:44:12'),
(71, 9, 40, 9, '2018-09-14 10:41:24', '2018-09-14 10:44:12'),
(72, 9, 45, 13, '2018-09-14 10:41:24', '2018-09-14 10:44:12'),
(73, 9, 44, 13, '2018-09-14 10:41:24', '2018-09-14 10:44:12'),
(74, 9, 43, 13, '2018-09-14 10:41:24', '2018-09-14 10:44:12'),
(75, 9, 31, 7, '2018-09-14 15:12:17', '2018-09-14 15:16:06'),
(76, 6, 28, 3, '2018-09-16 20:11:30', '2018-09-16 20:11:38'),
(77, 10, 6, 3, '2018-12-24 09:01:55', '2018-12-24 09:08:42'),
(78, 10, 29, 5, '2018-12-24 09:03:14', '2018-12-24 09:08:42'),
(79, 10, 9, 4, '2018-12-24 09:03:14', '2018-12-24 09:08:42'),
(80, 10, 12, 7, '2018-12-24 09:03:14', '2018-12-24 09:08:42'),
(81, 10, 7, 1, '2018-12-24 09:03:14', '2018-12-24 09:08:42'),
(82, 10, 13, 7, '2018-12-24 09:03:14', '2018-12-24 09:08:42'),
(83, 10, 2, 5, '2018-12-24 09:03:14', '2018-12-24 09:08:42'),
(84, 10, 1, 9, '2018-12-24 09:03:14', '2018-12-24 09:08:42'),
(86, 10, 48, 2, '2018-12-24 14:53:52', '2018-12-24 14:54:00'),
(89, 12, 50, 2, '2019-02-12 00:03:55', '2019-02-12 00:04:33'),
(90, 12, 51, 1, '2019-02-12 00:04:13', '2019-02-12 00:04:33');

-- --------------------------------------------------------

--
-- Structure de la table `player`
--

CREATE TABLE `player` (
  `id` int(11) NOT NULL,
  `status` char(1) CHARACTER SET latin1 DEFAULT NULL,
  `pseudo` varchar(255) CHARACTER SET latin1 NOT NULL,
  `nom` varchar(255) CHARACTER SET latin1 NOT NULL,
  `prenom` varchar(255) CHARACTER SET latin1 NOT NULL,
  `mail` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `telephone` varchar(20) CHARACTER SET latin1 DEFAULT NULL,
  `tstp_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tstp_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Déchargement des données de la table `player`
--

INSERT INTO `player` (`id`, `status`, `pseudo`, `nom`, `prenom`, `mail`, `telephone`, `tstp_create`, `tstp_update`) VALUES
(1, NULL, 'tba77', 'Ben Achour', 'Tahar', 'tba777@gmail.com', '50011035', '2018-08-30 21:49:10', '2018-08-30 21:49:10'),
(2, NULL, 'T@z', 'Ben Abdallah', 'Aziz', 'aziz.B.A@hotmail.fr', '22858444', '2018-08-30 21:49:10', '2018-09-15 15:11:37'),
(3, NULL, 'NIHASOF', 'Chami', 'Sofiane', 'sofiane.chami@gmail.com', '50971746', '2018-08-30 21:49:10', '2018-08-30 21:49:10'),
(4, NULL, 'khaleel', 'Arfaoui', 'Khalil', 'khalil.arfaoui@gmail.com', '25990349', '2018-08-30 21:49:10', '2018-08-30 21:49:10'),
(5, NULL, 'Joulaks', 'Joulak', 'Walid', 'walid.joulak@gmail.com', '27317727', '2018-08-30 21:49:10', '2018-08-30 21:49:10'),
(6, NULL, 'Baalhamon', 'Sfaxi', 'Moez', 'moez.sfaxi@gmail.com', '20878483', '2018-08-30 21:49:10', '2018-08-30 21:49:10'),
(7, NULL, 'om_zar', 'Zarrad', 'Ahmed', 'ahmed.zarrad@gmail.com', NULL, '2018-08-30 21:49:10', '2018-08-30 21:49:10'),
(9, NULL, 'Marshall', 'Ghedamsi', 'Hedi', 'hedi.ghedamsi@gmail.com', '53187952', '2018-08-30 21:49:10', '2018-08-30 21:49:10'),
(10, NULL, 'Ussama', 'Ben Yaala', 'Oussama', 'oussama@gmail.com', NULL, '2018-08-30 21:49:10', '2018-08-30 21:49:10'),
(11, NULL, 'Rugal', 'Ben Moussa', 'Mehdi', 'mehdi.benmoussa@gmail.com', '52694352', '2018-08-30 21:49:10', '2018-08-30 21:49:10'),
(12, NULL, 'Minogami', 'Ferchichi', 'Amine', 'minogamiggpo@gmail.com', '23960613', '2018-08-30 21:49:10', '2018-08-30 21:49:10'),
(13, NULL, 'SupaHiro', 'Ben Ammar', 'Rabii', 'rabii.benammar@gmail.com', '55704904', '2018-08-30 21:49:10', '2018-12-13 08:10:46'),
(14, NULL, 'WaelFoxHound', 'Zaghouani', 'Wael', 'wael.zaghouani@gmail.com', NULL, '2018-08-30 21:49:10', '2018-08-30 21:49:10'),
(15, NULL, 'Chourou', 'Triki', 'Slim', 'slim.triki@gmail.com', NULL, '2018-08-30 21:49:10', '2018-08-30 21:49:10'),
(16, NULL, 'rednaksxrainbowdash', 'Laabidi', 'Skander', 'skander.laabidi@gmail.com', NULL, '2018-08-30 21:49:10', '2018-08-30 21:49:10'),
(17, NULL, 'Benjammo', 'Farhat', 'Haythem', 'haythem.farhat@gmail.com', NULL, '2018-08-30 21:49:10', '2018-08-30 21:49:10'),
(18, NULL, 'Fog', 'Hamdi', 'Amine', 'amine.hamdi@gmail.com', '97838290', '2018-08-30 21:49:10', '2018-08-30 21:49:10'),
(20, NULL, 'Wassus', 'Zammit', 'Wassim', 'wassim.zammit@gmail.com', NULL, '2018-08-30 21:49:10', '2018-08-30 21:49:10'),
(22, NULL, 'Fedi Mejri', 'Mejri', 'Fedi', '-', NULL, '2018-08-30 21:49:10', '2018-08-30 21:49:10'),
(23, NULL, 'Rabiekratos', 'Kratos', 'Rabi', '', NULL, '2018-08-30 21:49:10', '2018-08-30 21:49:10'),
(24, NULL, 'Amine Rekik', 'Rekik', 'Amine', '-', NULL, '2018-08-30 21:49:10', '2018-08-30 21:49:10'),
(25, NULL, 'Iheb Chatti', 'Chatti', 'Iheb', '-', NULL, '2018-08-30 21:49:10', '2018-08-30 21:49:10'),
(26, NULL, 'Ismail Guezguez', 'Guezguez', 'Ismail', '-', NULL, '2018-08-30 21:49:10', '2018-08-30 21:49:10'),
(27, NULL, 'Oussama Skhiri', 'Skhiri', 'Oussama', '-', NULL, '2018-08-30 21:49:10', '2018-08-30 21:49:10'),
(28, NULL, 'Klix', 'Soussi', 'Mohamed Ali', '', '', '2018-08-30 21:49:10', '2018-08-30 21:49:10'),
(29, NULL, 'Bethedeadmen', 'Mannai', 'Bassem', 'mannai.bessem@gmail.com', '22557634', '2018-08-30 21:49:10', '2018-08-30 21:49:10'),
(30, NULL, 'Nacef Lakhal', 'Lakhal', 'Nacef', '', '', '2018-08-30 21:49:10', '2018-08-30 21:49:10'),
(31, NULL, 'Ahmed Boudali', 'Boudali', 'Ahmed', '', '', '2018-08-30 21:49:10', '2018-09-14 10:37:03'),
(32, NULL, 'Baha Bdira', 'Bdira', 'Baha', '', '', '2018-08-30 21:49:10', '2018-08-30 21:49:10'),
(33, NULL, 'Oussama Mrissa', 'Mrissa', 'Oussama', '', '', '2018-08-30 21:49:10', '2018-08-30 21:49:10'),
(35, NULL, 'Jasser Romdhana', 'Romdhana', 'Jasser', '', '', '2018-09-14 09:36:53', '2018-09-14 09:36:53'),
(36, NULL, 'Raed Romdhana', 'Romadhana', 'Raed', '', '', '2018-09-14 09:37:15', '2018-09-14 09:37:15'),
(37, NULL, 'Youssef Jasser', 'Jasser', 'Youssef', '', '', '2018-09-14 09:49:04', '2018-09-14 09:49:04'),
(38, NULL, 'Yassine Amara', 'Amara', 'Yassine', '', '', '2018-09-14 09:49:51', '2018-09-14 09:49:51'),
(39, NULL, 'Aychus', 'Ayachus', 'Ayachus', '', '', '2018-09-14 10:16:21', '2018-09-14 10:16:21'),
(40, NULL, 'Heni Barhoumi', 'Barhoumi', 'Heni', '', '', '2018-09-14 10:37:37', '2018-09-14 10:37:37'),
(41, NULL, 'Amine Bahri', 'Bahri', 'Amine', '', '', '2018-09-14 10:37:54', '2018-09-14 10:37:54'),
(42, NULL, 'Ghaith Hichri', 'Hichri', 'Ghaith', '', '', '2018-09-14 10:38:10', '2018-09-14 10:38:10'),
(43, NULL, 'Youssef Sahmim', 'Sahmim', 'Youssef', '', '', '2018-09-14 10:38:47', '2018-09-14 10:38:47'),
(44, NULL, 'Mohamed Chiheb Ben Nasr', 'Ben Nasr', 'Mohamed Chiheb', '', '', '2018-09-14 10:39:13', '2018-09-14 10:39:13'),
(45, NULL, 'Mohamed Aziz Abderrahmen', 'Abderrahmen', 'Mohamed Aziz', '', '', '2018-09-14 10:39:32', '2018-09-14 10:39:32'),
(46, 'H', '02Test', 'TestNom2', 'TestPrenom2', 'test@test.test', 'test0123456789', '2018-09-15 20:30:11', '2018-09-25 13:51:16'),
(48, 'H', 'TGESTidusHarrachi', 'Harrachi', 'Tidus', '', '', '2018-12-24 14:51:26', '2018-12-24 15:38:25'),
(49, 'H', 'Mozikha', 'Traing', 'Tara', 'ici@la.com', '046874', '2019-01-30 15:33:40', '2019-01-30 15:33:59'),
(50, NULL, 'R1 God of Rage', 'Amdouni', 'Ahmed', '', '', '2019-02-11 23:54:35', '2019-02-11 23:54:35'),
(51, NULL, 'Nindo', 'BoulaÃ¢ba', 'Yosri', 'nindoreturn@hotmail.com', '', '2019-02-12 00:00:19', '2019-02-12 00:00:19');

-- --------------------------------------------------------

--
-- Structure de la table `player_game`
--

CREATE TABLE `player_game` (
  `id_player` int(11) NOT NULL,
  `id_game` int(11) NOT NULL,
  `id_character` int(11) NOT NULL DEFAULT '32'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Game played by player and their main character for this game';

--
-- Déchargement des données de la table `player_game`
--

INSERT INTO `player_game` (`id_player`, `id_game`, `id_character`) VALUES
(1, 1, 20),
(2, 1, 20),
(3, 1, 22),
(4, 1, 21),
(5, 1, 15),
(6, 1, 9),
(7, 1, 29),
(9, 1, 6),
(10, 1, 2),
(11, 1, 7),
(12, 1, 8),
(13, 1, 5),
(14, 1, 15),
(15, 1, 4),
(16, 1, 26),
(17, 1, 1),
(18, 1, 24),
(20, 1, 4),
(22, 1, 32),
(23, 1, 32),
(24, 1, 32),
(25, 1, 32),
(26, 1, 32),
(27, 1, 32),
(28, 1, 23),
(30, 1, 11),
(31, 1, 32),
(32, 1, 32),
(33, 1, 1),
(46, 1, 36),
(29, 1, 2),
(48, 1, 18),
(49, 1, 2),
(2, 2, 39),
(50, 2, 38),
(51, 2, 39);

-- --------------------------------------------------------

--
-- Structure de la table `ranking`
--

CREATE TABLE `ranking` (
  `id_game` int(11) NOT NULL,
  `id_player` int(11) UNSIGNED NOT NULL,
  `pseudo` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `points` int(11) NOT NULL,
  `new_points` int(11) NOT NULL,
  `game` enum('Street Fighter V','Tekken 7','Dragon Ball fighterZ','The King Of Fighters XIV','Marvel vs Capcom Infinite','Guilty Gears Xrd Revelator','Street Fighter IV','Marvel Vs Capcom 3') CHARACTER SET latin1 DEFAULT NULL,
  `character` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `id_char` int(11) NOT NULL,
  `current_rank` int(4) NOT NULL DEFAULT '0',
  `previous_rank` int(4) NOT NULL DEFAULT '9999',
  `update_date` date DEFAULT NULL,
  `tstp_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tstp_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Déchargement des données de la table `ranking`
--

INSERT INTO `ranking` (`id_game`, `id_player`, `pseudo`, `points`, `new_points`, `game`, `character`, `id_char`, `current_rank`, `previous_rank`, `update_date`, `tstp_create`, `tstp_update`) VALUES
(1, 1, 'tba77', 35, 35, 'Street Fighter V', 'Laura', 20, 11, 13, '2018-08-31', '2018-08-30 21:58:52', '2018-09-03 19:45:33'),
(1, 2, 'T@z', 340, 340, 'Street Fighter V', 'Akuma', 20, 2, 2, '2018-08-31', '2018-08-30 21:58:52', '2018-09-03 19:45:33'),
(1, 3, 'NIHASOF', 80, 80, 'Street Fighter V', 'Mika', 22, 6, 6, '2018-08-31', '2018-08-30 21:58:52', '2018-09-03 19:45:33'),
(1, 4, 'Khaleel', 75, 75, 'Street Fighter V', 'Menat', 21, 7, 7, '2018-08-31', '2018-08-30 21:58:52', '2018-09-03 19:45:33'),
(1, 5, 'Joulaks', 150, 150, 'Street Fighter V', 'Guile', 15, 4, 5, '2018-08-31', '2018-08-30 21:58:52', '2018-09-03 19:45:33'),
(1, 6, 'Baalhamon', 285, 285, 'Street Fighter V', 'Bison', 9, 3, 3, '2018-08-31', '2018-08-30 21:58:52', '2018-09-03 19:45:33'),
(1, 7, 'om_zar', 520, 520, 'Street Fighter V', 'Zangief', 29, 1, 1, '2018-08-31', '2018-08-30 21:58:52', '2018-09-03 19:45:33'),
(1, 9, 'Marshall', 35, 35, 'Street Fighter V', 'Alex', 6, 11, 11, '2018-08-31', '2018-08-30 21:58:52', '2018-09-03 19:45:33'),
(1, 10, 'Ussama', 25, 25, 'Street Fighter V', 'Ken', 2, 13, 12, '2018-08-31', '2018-08-30 21:58:52', '2018-09-03 19:45:33'),
(1, 11, 'Rugal', 10, 10, 'Street Fighter V', 'Balrog', 7, 15, 13, '2018-08-31', '2018-08-30 21:58:52', '2018-09-03 19:45:33'),
(1, 12, 'Minogami', 55, 55, 'Street Fighter V', 'Birdie', 8, 9, 9, '2018-08-31', '2018-08-30 21:58:52', '2018-09-03 19:45:33'),
(1, 13, 'Fullscreen', 5, 5, 'Street Fighter V', 'Abigail', 5, 19, 17, '2018-08-31', '2018-08-30 21:58:52', '2018-09-03 19:45:33'),
(1, 14, 'WaelFoxHound', 70, 70, 'Street Fighter V', 'Guile', 15, 8, 8, '2018-08-31', '2018-08-30 21:58:52', '2018-09-03 19:45:33'),
(1, 15, 'SlimTriki', 140, 140, 'Street Fighter V', 'Ken', 4, 5, 4, '2018-08-31', '2018-08-30 21:58:52', '2018-09-03 19:45:33'),
(1, 16, 'rednaksxrainbowdash', 10, 10, 'Street Fighter V', 'Sakura', 26, 15, 13, '2018-08-31', '2018-08-30 21:58:52', '2018-09-03 19:45:33'),
(1, 17, 'Benjammo', 5, 5, 'Street Fighter V', 'Ryu', 1, 19, 17, '2018-08-31', '2018-08-30 21:58:52', '2018-09-03 19:45:33'),
(1, 18, 'Fog', 1, 1, 'Street Fighter V', 'Necali', 24, 24, 21, '2018-08-31', '2018-08-30 21:58:52', '2018-09-03 19:45:33'),
(1, 20, NULL, 10, 10, NULL, NULL, 4, 15, 13, '2018-08-31', '2018-08-30 21:58:52', '2018-09-03 19:45:33'),
(1, 22, NULL, 5, 5, NULL, NULL, 32, 19, 17, '2018-08-31', '2018-08-30 21:58:52', '2018-09-03 19:45:33'),
(1, 23, NULL, 5, 5, NULL, NULL, 32, 19, 17, '2018-08-31', '2018-08-30 21:58:52', '2018-09-03 19:45:33'),
(1, 24, NULL, 1, 1, NULL, NULL, 32, 24, 21, '2018-08-31', '2018-08-30 21:58:52', '2018-09-03 19:45:33'),
(1, 25, NULL, 1, 1, NULL, NULL, 32, 24, 21, '2018-08-31', '2018-08-30 21:58:52', '2018-09-03 19:45:33'),
(1, 26, NULL, 1, 1, NULL, NULL, 32, 24, 21, '2018-08-31', '2018-08-30 21:58:52', '2018-09-03 19:45:33'),
(1, 27, NULL, 1, 1, NULL, NULL, 32, 24, 21, '2018-08-31', '2018-08-30 21:58:52', '2018-09-03 19:45:33'),
(1, 28, NULL, 45, 45, NULL, NULL, 23, 10, 10, '2018-08-31', '2018-08-30 21:58:52', '2018-09-03 19:45:33'),
(1, 30, NULL, 25, 25, NULL, NULL, 11, 13, 9999, '2018-08-31', '2018-08-30 21:58:52', '2018-09-03 19:45:33'),
(1, 31, NULL, 10, 10, NULL, NULL, 32, 15, 9999, '2018-08-31', '2018-08-30 21:58:52', '2018-09-03 19:45:33'),
(1, 32, NULL, 1, 1, NULL, NULL, 32, 24, 9999, '2018-08-31', '2018-08-30 21:58:52', '2018-09-03 19:45:33'),
(1, 33, NULL, 5, 5, NULL, NULL, 1, 19, 9999, '2018-08-31', '2018-08-30 21:58:52', '2018-09-03 19:45:33');

-- --------------------------------------------------------

--
-- Structure de la table `scoring`
--

CREATE TABLE `scoring` (
  `id` int(11) NOT NULL,
  `id_type_score` int(11) NOT NULL,
  `rank_top` int(11) NOT NULL,
  `rank_bottom` int(11) NOT NULL DEFAULT '999999',
  `score` int(11) NOT NULL,
  `tstp_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tstp_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Déchargement des données de la table `scoring`
--

INSERT INTO `scoring` (`id`, `id_type_score`, `rank_top`, `rank_bottom`, `score`, `tstp_create`, `tstp_update`) VALUES
(93, 15, 1, 1, 150, '2018-09-09 14:53:03', '2018-09-09 14:53:03'),
(94, 15, 2, 2, 100, '2018-09-09 14:53:03', '2018-09-09 14:53:03'),
(95, 15, 3, 3, 70, '2018-09-09 14:53:03', '2018-09-09 14:53:03'),
(96, 15, 4, 4, 45, '2018-09-09 14:53:03', '2018-09-09 14:53:03'),
(97, 15, 5, 6, 25, '2018-09-09 14:53:03', '2018-09-09 14:53:03'),
(98, 15, 7, 8, 10, '2018-09-09 14:53:03', '2018-09-09 14:53:03'),
(99, 15, 9, 12, 5, '2018-09-09 14:53:03', '2018-09-09 14:53:03'),
(100, 15, 13, 16, 1, '2018-09-09 14:53:03', '2018-09-09 14:53:03'),
(107, 16, 1, 1, 70, '2018-09-09 14:53:27', '2018-09-09 14:53:27'),
(108, 16, 2, 2, 45, '2018-09-09 14:53:27', '2018-09-09 14:53:27'),
(109, 16, 3, 3, 25, '2018-09-09 14:53:27', '2018-09-09 14:53:27'),
(110, 16, 4, 4, 10, '2018-09-09 14:53:27', '2018-09-09 14:53:27'),
(111, 16, 5, 6, 5, '2018-09-09 14:53:27', '2018-09-09 14:53:27'),
(112, 16, 7, 8, 1, '2018-09-09 14:53:27', '2018-09-09 14:53:27'),
(119, 17, 1, 1, 70, '2018-09-09 14:53:39', '2018-09-09 14:53:39'),
(120, 17, 2, 2, 45, '2018-09-09 14:53:39', '2018-09-09 14:53:39'),
(121, 17, 3, 3, 25, '2018-09-09 14:53:39', '2018-09-09 14:53:39'),
(122, 17, 4, 4, 10, '2018-09-09 14:53:39', '2018-09-09 14:53:39'),
(123, 17, 5, 6, 5, '2018-09-09 14:53:39', '2018-09-09 14:53:39'),
(124, 17, 7, 8, 1, '2018-09-09 14:53:39', '2018-09-09 14:53:39');

-- --------------------------------------------------------

--
-- Structure de la table `season`
--

CREATE TABLE `season` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `date_start` date NOT NULL,
  `date_end` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `season`
--

INSERT INTO `season` (`id`, `name`, `date_start`, `date_end`) VALUES
(1, 'All', '2000-01-01', '2100-01-01'),
(2, '2018', '2018-01-01', '2018-12-31'),
(3, '2017', '2017-01-01', '2017-12-31'),
(7, '2018-Q2', '2018-04-01', '2018-06-30'),
(5, '2018-Q1', '2018-01-01', '2018-03-31'),
(8, '2018-Q3', '2018-07-01', '2018-09-30'),
(9, '2018-Q4', '2018-10-01', '2018-12-31'),
(10, '2018-S1', '2018-01-01', '2018-06-30'),
(11, '2018-S2', '2018-07-01', '2018-12-31');

-- --------------------------------------------------------

--
-- Structure de la table `tournament`
--

CREATE TABLE `tournament` (
  `id` int(11) NOT NULL,
  `id_game` int(11) NOT NULL,
  `group_name` varchar(250) CHARACTER SET latin1 NOT NULL,
  `name` varchar(250) CHARACTER SET latin1 NOT NULL,
  `id_type_score` int(11) NOT NULL,
  `date_start` date NOT NULL,
  `date_end` date DEFAULT NULL,
  `tstp_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tstp_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Déchargement des données de la table `tournament`
--

INSERT INTO `tournament` (`id`, `id_game`, `group_name`, `name`, `id_type_score`, `date_start`, `date_end`, `tstp_create`, `tstp_update`) VALUES
(3, 1, 'Orange Tournament', 'OT Tunis 2018-Q2', 16, '2018-04-15', '2018-04-15', '2018-09-12 22:35:54', '2018-09-14 15:39:51'),
(4, 1, 'Orange Tournament', 'OT Bizerte 2018-Q2', 16, '2018-04-22', '2018-04-22', '2018-09-12 22:36:52', '2018-09-14 15:40:18'),
(5, 1, 'Orange Tournament', 'OT Sousse 2018-Q2', 16, '2018-04-29', '2018-04-29', '2018-09-14 09:43:12', '2018-09-14 15:40:31'),
(6, 1, 'Orange Tournament', 'OT Gabes 2018-Q2', 16, '2018-05-06', '2018-05-06', '2018-09-14 09:52:53', '2018-09-14 15:46:23'),
(7, 1, 'Orange Tournament', 'OT Comicon 2018', 15, '2018-07-03', '2018-07-03', '2018-09-14 10:00:05', '2018-09-14 15:46:53'),
(8, 1, 'Tunisians Gathering Fighters', 'TGF 2018-Q1', 15, '2018-02-25', '2018-02-25', '2018-09-14 10:10:14', '2018-09-14 15:47:21'),
(9, 1, 'Banzai Tournament', 'Banzai 2018', 15, '2018-08-11', '2018-08-11', '2018-09-14 10:25:49', '2018-09-14 10:28:28'),
(10, 1, 'RyujinCon Tournament', 'SF V Finals', 15, '2018-12-23', '2018-12-23', '2018-12-24 08:59:31', '2018-12-24 08:59:31'),
(12, 2, 'RyujinCon Tournament', 'Tekken7 Tournament', 15, '2018-12-23', '2018-12-23', '2019-02-12 00:03:29', '2019-02-12 00:42:19');

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `tournament_score`
-- (Voir ci-dessous la vue réelle)
--
CREATE TABLE `tournament_score` (
`id_game` int(11)
,`id_tournament` int(11)
,`tournament_group_name` varchar(250)
,`tournament_name` varchar(250)
,`date_start` date
,`date_end` date
,`id_type_score` int(11)
,`type_score_name` varchar(50)
,`id_player` int(11)
,`ranking` int(11)
,`score` int(11)
);

-- --------------------------------------------------------

--
-- Structure de la table `type_score`
--

CREATE TABLE `type_score` (
  `id` int(11) NOT NULL,
  `type_name` varchar(50) CHARACTER SET latin1 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Déchargement des données de la table `type_score`
--

INSERT INTO `type_score` (`id`, `type_name`) VALUES
(15, 'Global'),
(16, 'Regional'),
(17, 'Online');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `login` varchar(40) CHARACTER SET latin1 NOT NULL,
  `password` varchar(40) CHARACTER SET latin1 NOT NULL,
  `right` varchar(30) CHARACTER SET latin1 NOT NULL,
  `tstp_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tstp_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`login`, `password`, `right`, `tstp_create`, `tstp_update`) VALUES
('admin', '00b958977eebc9b18a11f08cbde7e810', 'admin', '2018-09-08 23:10:20', '2018-09-08 23:10:20');

-- --------------------------------------------------------

--
-- Structure de la vue `tournament_score`
--
DROP TABLE IF EXISTS `tournament_score`;

CREATE ALGORITHM=UNDEFINED DEFINER=`tesatnsimbtesadb`@`%` SQL SECURITY DEFINER VIEW `tournament_score`  AS  select `t`.`id_game` AS `id_game`,`t`.`id` AS `id_tournament`,`t`.`group_name` AS `tournament_group_name`,`t`.`name` AS `tournament_name`,`t`.`date_start` AS `date_start`,`t`.`date_end` AS `date_end`,`ts`.`id` AS `id_type_score`,`ts`.`type_name` AS `type_score_name`,`pp`.`id_player` AS `id_player`,`pp`.`ranking` AS `ranking`,`s`.`score` AS `score` from (((`tournament` `t` join `participant` `pp` on((`pp`.`id_tournament` = `t`.`id`))) join `type_score` `ts` on((`ts`.`id` = `t`.`id_type_score`))) left join `scoring` `s` on(((`s`.`id_type_score` = `ts`.`id`) and (`pp`.`ranking` >= `s`.`rank_top`) and (`pp`.`ranking` <= `s`.`rank_bottom`)))) ;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `character`
--
ALTER TABLE `character`
  ADD PRIMARY KEY (`id`),
  ADD KEY `character_fk1` (`id_game`);

--
-- Index pour la table `game`
--
ALTER TABLE `game`
  ADD PRIMARY KEY (`id`),
  ADD KEY `GAME_CHAR_UNKNOWN` (`id_char_unknown`);

--
-- Index pour la table `historanking`
--
ALTER TABLE `historanking`
  ADD KEY `id_user` (`id_player`);

--
-- Index pour la table `param`
--
ALTER TABLE `param`
  ADD PRIMARY KEY (`group_name`,`variable`);

--
-- Index pour la table `participant`
--
ALTER TABLE `participant`
  ADD PRIMARY KEY (`id`),
  ADD KEY `participant_fk1` (`id_tournament`);

--
-- Index pour la table `player`
--
ALTER TABLE `player`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `player_game`
--
ALTER TABLE `player_game`
  ADD KEY `player_game_fk01` (`id_game`),
  ADD KEY `player_game_fk02` (`id_player`),
  ADD KEY `player_game_fk03` (`id_character`);

--
-- Index pour la table `ranking`
--
ALTER TABLE `ranking`
  ADD PRIMARY KEY (`id_game`,`id_player`),
  ADD UNIQUE KEY `pseudo` (`pseudo`);

--
-- Index pour la table `scoring`
--
ALTER TABLE `scoring`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_type_score` (`id_type_score`,`rank_top`),
  ADD UNIQUE KEY `scoring_idx01` (`id_type_score`,`rank_top`,`rank_bottom`);

--
-- Index pour la table `season`
--
ALTER TABLE `season`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tournament`
--
ALTER TABLE `tournament`
  ADD PRIMARY KEY (`id`),
  ADD KEY `group_name` (`group_name`),
  ADD KEY `group_name_2` (`group_name`,`date_start`),
  ADD KEY `date_start` (`date_start`),
  ADD KEY `tournament_fk1` (`id_game`),
  ADD KEY `tournament_fk2` (`id_type_score`);

--
-- Index pour la table `type_score`
--
ALTER TABLE `type_score`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD UNIQUE KEY `login` (`login`),
  ADD KEY `login_2` (`login`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `character`
--
ALTER TABLE `character`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;
--
-- AUTO_INCREMENT pour la table `game`
--
ALTER TABLE `game`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT pour la table `participant`
--
ALTER TABLE `participant`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;
--
-- AUTO_INCREMENT pour la table `player`
--
ALTER TABLE `player`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;
--
-- AUTO_INCREMENT pour la table `scoring`
--
ALTER TABLE `scoring`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=125;
--
-- AUTO_INCREMENT pour la table `season`
--
ALTER TABLE `season`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT pour la table `tournament`
--
ALTER TABLE `tournament`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT pour la table `type_score`
--
ALTER TABLE `type_score`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `character`
--
ALTER TABLE `character`
  ADD CONSTRAINT `character_fk1` FOREIGN KEY (`id_game`) REFERENCES `game` (`id`);

--
-- Contraintes pour la table `game`
--
ALTER TABLE `game`
  ADD CONSTRAINT `GAME_CHAR_UNKNOWN` FOREIGN KEY (`id_char_unknown`) REFERENCES `character` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `participant`
--
ALTER TABLE `participant`
  ADD CONSTRAINT `participant_fk1` FOREIGN KEY (`id_tournament`) REFERENCES `tournament` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `player_game`
--
ALTER TABLE `player_game`
  ADD CONSTRAINT `player_game_fk01` FOREIGN KEY (`id_game`) REFERENCES `game` (`id`),
  ADD CONSTRAINT `player_game_fk02` FOREIGN KEY (`id_player`) REFERENCES `player` (`id`),
  ADD CONSTRAINT `player_game_fk03` FOREIGN KEY (`id_character`) REFERENCES `character` (`id`);

--
-- Contraintes pour la table `scoring`
--
ALTER TABLE `scoring`
  ADD CONSTRAINT `scoring_fk1` FOREIGN KEY (`id_type_score`) REFERENCES `type_score` (`id`);

--
-- Contraintes pour la table `tournament`
--
ALTER TABLE `tournament`
  ADD CONSTRAINT `tournament_fk1` FOREIGN KEY (`id_game`) REFERENCES `game` (`id`),
  ADD CONSTRAINT `tournament_fk2` FOREIGN KEY (`id_type_score`) REFERENCES `type_score` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
