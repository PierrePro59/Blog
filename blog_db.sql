-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Mer 05 Février 2025 à 19:28
-- Version du serveur :  5.6.20-log
-- Version de PHP :  7.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `blog_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `articles`
--

CREATE TABLE IF NOT EXISTS `articles` (
`id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `author_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` int(11) NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=22 ;

--
-- Contenu de la table `articles`
--

INSERT INTO `articles` (`id`, `title`, `content`, `author_id`, `created_at`, `user_id`) VALUES
(1, 'HolÃ  ! Como fiestas', 'Hola les pequinos', NULL, '2025-02-04 21:21:53', 0),
(2, 'fairy', 'lol', NULL, '2025-02-04 21:52:35', 0),
(3, 'rfjfy', 'kjhyukkh', NULL, '2025-02-04 21:52:50', 0),
(4, 'hrhj', 'yjyhj', NULL, '2025-02-04 21:59:11', 0),
(5, 'thrth', 'thrth', NULL, '2025-02-04 22:00:14', 0),
(6, 'htrjh', 'jhyjhy', NULL, '2025-02-04 22:01:22', 0),
(7, 'ytjytjtyujk', 'jythjytj', NULL, '2025-02-04 22:02:39', 0),
(8, 'rthrhj', 'yrjhyrjh', NULL, '2025-02-04 22:04:55', 0),
(21, ',ghj,;', ';gj;,j;', NULL, '2025-02-05 19:18:33', 8),
(11, 'nhgfjnjn', 'hjnhj', NULL, '2025-02-05 14:40:18', 6),
(12, 'yrjyrjyj', 'yujktkyu', NULL, '2025-02-05 16:11:35', 6),
(13, 'hjrj', 'jyut', NULL, '2025-02-05 16:14:13', 6),
(14, 'tyuijktÃ¨ik', 'kuikylk', NULL, '2025-02-05 16:17:00', 6),
(15, 'tkuyu', 'kuklut', NULL, '2025-02-05 16:18:39', 6),
(16, 'fjk,jfk,jfy', 'kjguktyuk', NULL, '2025-02-05 16:42:19', 6),
(17, 'thjrtyfjk', 'kjyrtktuy', NULL, '2025-02-05 16:42:54', 6),
(18, 'force toi', 'loli', NULL, '2025-02-05 16:45:04', 6),
(20, 'Longue Vie', 'fairy', NULL, '2025-02-05 17:15:28', 7);

-- --------------------------------------------------------

--
-- Structure de la table `friends`
--

CREATE TABLE IF NOT EXISTS `friends` (
`id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `friend_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Contenu de la table `friends`
--

INSERT INTO `friends` (`id`, `user_id`, `friend_id`, `created_at`) VALUES
(1, 6, 7, '2025-02-04 21:40:03'),
(2, 6, 7, '2025-02-04 22:24:35'),
(3, 6, 6, '2025-02-04 22:24:57'),
(4, 6, 7, '2025-02-05 14:36:36'),
(5, 6, 7, '2025-02-05 16:55:23'),
(6, 6, 7, '2025-02-05 17:31:14');

-- --------------------------------------------------------

--
-- Structure de la table `inscriptions`
--

CREATE TABLE IF NOT EXISTS `inscriptions` (
`id` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `pseudo` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Contenu de la table `inscriptions`
--

INSERT INTO `inscriptions` (`id`, `nom`, `prenom`, `pseudo`, `password`, `email`) VALUES
(8, 'fjjfjkyk', 'htrfdjhr', 'natsud', '$2y$10$HEeoVUNK4sRgy2xfiwB1C.sWCXV5rh0ukXBihhoLMOabdKfPEynaS', 'heiji@gmail.com'),
(7, 'Vasse', 'Pierre', 'sting', '$2y$10$vyS87owYomJL0zKrRTysQe7Pq/WgYGJkFHYzViX4wyeFQVsOGL9jS', 'pierre.vasseur0406@gmail.com'),
(6, 'duponnd', 'jffjyhj', 'natsu', '$2y$10$jJXcK5rncG0IEV8tTmfgj.dRnmnjQgPkAgjgem98dpgvauGFvW2SC', 'pierre.vasseur04060@gmail.com');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `articles`
--
ALTER TABLE `articles`
 ADD PRIMARY KEY (`id`), ADD KEY `author_id` (`author_id`);

--
-- Index pour la table `friends`
--
ALTER TABLE `friends`
 ADD PRIMARY KEY (`id`), ADD KEY `user_id` (`user_id`), ADD KEY `friend_id` (`friend_id`);

--
-- Index pour la table `inscriptions`
--
ALTER TABLE `inscriptions`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `pseudo` (`pseudo`), ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `articles`
--
ALTER TABLE `articles`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT pour la table `friends`
--
ALTER TABLE `friends`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT pour la table `inscriptions`
--
ALTER TABLE `inscriptions`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
