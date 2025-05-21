-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Hôte : db
-- Généré le : mer. 21 mai 2025 à 19:05
-- Version du serveur : 5.7.44
-- Version de PHP : 8.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `ecoride`
--

-- --------------------------------------------------------

--
-- Structure de la table `brands`
--

CREATE TABLE `brands` (
  `brand_id` int(11) NOT NULL,
  `label` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `brands`
--

INSERT INTO `brands` (`brand_id`, `label`) VALUES
(1, 'Tesla'),
(2, 'Renault'),
(3, 'Peugeot'),
(4, 'BMW'),
(5, 'Mercedes-Benz'),
(6, 'Toyota'),
(7, 'Volkswagen'),
(8, 'Hyundai'),
(9, 'Ford'),
(10, 'Audi'),
(28, 'E'),
(31, 'Mercedes'),
(27, 'Jaguar'),
(26, 'Lexus'),
(25, 'Dodge');

-- --------------------------------------------------------

--
-- Structure de la table `carpools`
--

CREATE TABLE `carpools` (
  `carpooling_id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL,
  `departure_date` date NOT NULL,
  `departure_time` varchar(50) NOT NULL,
  `departure_place` varchar(50) NOT NULL,
  `arrival_date` date NOT NULL,
  `arrival_time` varchar(50) NOT NULL,
  `arrival_place` varchar(50) NOT NULL,
  `status` varchar(50) NOT NULL,
  `nb_place` int(11) NOT NULL,
  `person_price` float NOT NULL,
  `driver_id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `carpools`
--

INSERT INTO `carpools` (`carpooling_id`, `car_id`, `departure_date`, `departure_time`, `departure_place`, `arrival_date`, `arrival_time`, `arrival_place`, `status`, `nb_place`, `person_price`, `driver_id`) VALUES
(1, 0, '2025-12-10', '08:00:00', 'Paris', '2025-12-10', '12:00:00', 'Lyon', 'Disponible', 2, 25, NULL),
(2, 0, '2025-12-12', '09:30:00', 'Lyon', '2025-12-12', '13:30:00', 'Marseille', 'Disponible', 2, 30, NULL),
(3, 0, '2025-12-15', '07:00:00', 'Marseille', '2025-12-15', '11:00:00', 'Toulouse', 'Disponible', 4, 20, NULL),
(4, 0, '2025-12-18', '06:00:00', 'Toulouse', '2025-12-18', '18:00:00', 'Bordeaux', 'Annulé', 0, 0, NULL),
(5, 0, '2025-12-20', '08:30:00', 'Bordeaux', '2025-12-20', '14:00:00', 'Nantes', 'Disponible', 5, 18, NULL),
(6, 0, '2025-12-25', '10:00:00', 'Paris', '2025-12-25', '20:00:00', 'Lille', 'Disponible', 1, 15, NULL),
(7, 0, '2025-12-28', '07:00:00', 'Lille', '2025-12-28', '10:30:00', 'Bruxelles', 'Annulé', 0, 0, NULL),
(8, 0, '2025-12-11', '08:00:00', 'Paris', '2025-12-11', '12:00:00', 'Lyon', 'Disponible', 2, 28, NULL),
(11, 1, '2025-12-10', '08:00:00', 'Paris', '2025-12-10', '12:00:00', 'Lyon', 'Disponible', 5, 25, NULL),
(12, 2, '2025-12-10', '09:30:00', 'Paris', '2025-12-10', '13:30:00', 'Lyon', 'Disponible', 0, 28, NULL),
(13, 3, '2025-12-10', '11:00:00', 'Paris', '2025-12-10', '15:00:00', 'Lyon', 'Disponible', 0, 30, NULL),
(14, 4, '2025-12-10', '13:00:00', 'Paris', '2025-12-10', '17:00:00', 'Lyon', 'Disponible', 0, 20, NULL),
(15, 5, '2025-12-10', '15:00:00', 'Paris', '2025-12-10', '19:00:00', 'Lyon', 'Disponible', 1, 18, NULL),
(16, 1, '2025-12-10', '17:00:00', 'Paris', '2025-12-10', '21:00:00', 'Lyon', 'Disponible', 2, 22, NULL),
(17, 2, '2025-12-10', '19:30:00', 'Paris', '2025-12-10', '23:30:00', 'Lyon', 'Disponible', 3, 25, NULL),
(18, 3, '2025-12-11', '09:00:00', 'Paris', '2025-12-11', '13:00:00', 'Lyon', 'Disponible', 1, 28, NULL),
(19, 4, '2025-12-12', '07:30:00', 'Paris', '2025-12-12', '11:30:00', 'Lyon', 'Disponible', 4, 25, NULL),
(20, 5, '2024-12-31', '12:00:00', 'Paris', '2025-01-01', '16:00:00', 'Lyon', 'Disponible', 1, 30, NULL),
(21, 1, '2025-12-14', '14:00:00', 'Paris', '2025-12-14', '18:00:00', 'Lyon', 'Disponible', 6, 20, NULL),
(22, 2, '2025-12-15', '08:00:00', 'Paris', '2025-12-15', '12:00:00', 'Lyon', 'Disponible', 2, 22, NULL),
(23, 3, '2025-12-16', '17:30:00', 'Paris', '2025-12-16', '21:30:00', 'Lyon', 'Disponible', 4, 18, NULL),
(24, 4, '2025-12-17', '06:00:00', 'Paris', '2025-12-17', '10:00:00', 'Lyon', 'Disponible', 1, 24, NULL),
(25, 18, '2025-02-18', '21:00', 'Paris', '2025-02-21', '20:00', 'Milan', 'Disponible', 3, 30, NULL),
(26, 18, '2025-02-19', '20:00', 'Paris', '2025-02-20', '21:00', 'Lisbonne', 'Disponible', 5, 30, NULL),
(27, 18, '2025-03-02', '10:02', 'Paris', '2025-03-02', '10:00', 'Marseille', 'Disponible', 6, 40, NULL),
(51, 47, '2025-02-10', '23:02', 'Paris', '2025-02-12', '10:02', 'Bruxelles', 'Disponible', 3, 50, NULL),
(63, 56, '2025-04-15', '12:07', 'Paris', '2025-04-15', '14:50', 'Milan', 'Disponible', 5, 35, 4),
(55, 56, '2025-03-10', '20:00', 'Nantes', '2025-03-11', '21:00', 'Paris', 'Disponible', 2, 40, NULL),
(62, 56, '2025-04-12', '10:45', 'Paris', '2025-04-13', '04:30', 'Marseille', 'Disponible', 3, 40, 4),
(60, 57, '2025-04-12', '09:40', 'Paris', '2025-04-13', '22:00', 'Lyon', 'Disponible', 3, 35, 14),
(64, 56, '2025-04-20', '17:58', 'Lyon', '2025-04-21', '12:00', 'Marseille', 'Terminé', 3, 40, 4),
(65, 56, '2025-04-21', '03:50', 'Marseille', '2025-04-21', '13:00', 'Barcelone', 'Disponible', 3, 35, 4),
(66, 56, '2025-04-22', '18:20', 'Paris', '2025-04-23', '20:25', 'Lyon', 'Disponible', 4, 40, 4),
(67, 56, '2025-05-02', '15:00', 'Paris', '2025-05-03', '16:00', 'Milan', 'Disponible', 4, 50, 4),
(68, 1, '2025-05-02', '14:00:00', 'Lyon', '2025-05-02', '20:00:00', 'Milan', 'Disponible', 3, 40, NULL),
(69, 2, '2025-05-03', '09:30:00', 'Nice', '2025-05-03', '17:30:00', 'Milan', 'Disponible', 2, 55, NULL),
(70, 3, '2025-05-05', '06:45:00', 'Paris', '2025-05-05', '13:15:00', 'Milan', 'Disponible', 4, 35, 4),
(71, 4, '2025-05-07', '11:00:00', 'Marseille', '2025-05-07', '18:30:00', 'Milan', 'Disponible', 1, 60, NULL),
(72, 5, '2025-05-08', '07:30:00', 'Lille', '2025-05-08', '14:00:00', 'Milan', 'Disponible', 5, 42, NULL),
(73, 58, '2025-05-05', '17:00', 'Paris', '2025-05-07', '20:00', 'Milan', 'Annulé', 9, 40, 20),
(74, 58, '2025-05-06', '08:00', 'Paris', '2025-05-07', '12:00', 'Milan', 'Disponible', 3, 50, 20),
(76, 58, '2025-05-06', '20:00', 'Paris', '2025-05-07', '00:00', 'Limoges', 'Annulé', 5, 50, 20),
(75, 58, '2025-05-05', '12:28', 'Paris', '2025-05-08', '20:00', 'Milan', 'Disponible', 3, 40, 20),
(77, 58, '2025-05-07', '00:00', 'Lyon', '2025-05-08', '12:00', 'Barcelone', 'Disponible', 4, 35, 20),
(78, 58, '2025-05-14', '10:02', 'Marseille', '2025-05-09', '20:00', 'Naples', 'Disponible', 8, 25, 20),
(79, 58, '2025-05-08', '10:20', 'Paris', '2025-05-09', '20:00', 'Lyon', 'Disponible', 4, 50, 20),
(80, 58, '2025-05-16', '08:00', 'Paris', '2025-05-17', '20:00', 'Naples', 'Disponible', 4, 50, 20),
(81, 58, '2025-05-16', '08:00', 'Paris', '2025-05-17', '20:00', 'Naples', 'Disponible', 4, 50, 20),
(82, 58, '2025-05-16', '20:00', 'Paris', '2025-05-17', '20:30', 'Naples', 'Disponible', 4, 50, 20),
(83, 58, '2025-05-17', '08:00', 'Paris', '2025-05-18', '20:30', 'Naples', 'Disponible', 4, 50, 20),
(84, 58, '2025-05-17', '08:00', 'Paris', '2025-05-18', '20:30', 'Naples', 'Disponible', 4, 50, 20),
(85, 58, '2025-05-17', '20:00', 'Paris', '2025-05-18', '08:30', 'Naples', 'Disponible', 4, 50, 20),
(86, 58, '2025-05-17', '20:00', 'Paris', '2025-05-18', '18:00', 'Naples', 'Disponible', 4, 50, 20),
(87, 56, '2025-05-17', '20:00', 'Paris', '2025-05-19', '00:00', 'Rome', 'Disponible', 4, 50, 4),
(88, 56, '2025-05-17', '20:00', 'Paris', '2025-05-19', '00:00', 'Rome', 'Disponible', 4, 50, 4),
(89, 56, '2025-05-17', '20:00', 'Paris', '2025-05-19', '00:00', 'Rome', 'Disponible', 3, 50, 4),
(90, 59, '2025-05-22', '20:00', 'Paris', '2025-05-23', '19:00', 'Naples', 'Disponible', 4, 50, 83),
(91, 56, '2025-05-22', '18:00', 'Paris', '2025-05-23', '19:00', 'Naples', 'Disponible', 4, 45, 4);

-- --------------------------------------------------------

--
-- Structure de la table `carpools_as_users`
--

CREATE TABLE `carpools_as_users` (
  `carpools_as_users_id` int(11) NOT NULL,
  `carpooling_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `completed` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `carpools_as_users`
--

INSERT INTO `carpools_as_users` (`carpools_as_users_id`, `carpooling_id`, `user_id`, `completed`) VALUES
(29, 62, 44, 1),
(2, 20, 4, 1),
(25, 60, 44, 1),
(4, 51, 18, 1),
(15, 55, 4, 1),
(24, 60, 14, 1),
(28, 62, 4, 1),
(30, 63, 4, 1),
(31, 63, 44, 1),
(32, 64, 4, 1),
(33, 64, 44, 1),
(34, 65, 4, 1),
(35, 66, 4, 1),
(36, 67, 4, 1),
(63, 74, 20, 1),
(65, 75, 20, 1),
(64, 74, 4, 1),
(66, 75, 4, 1),
(68, 77, 20, 1),
(69, 78, 20, 1),
(70, 86, 20, 0),
(71, 89, 4, 1),
(72, 89, 20, 0),
(73, 90, 83, 0),
(74, 91, 4, 0);

-- --------------------------------------------------------

--
-- Structure de la table `cars`
--

CREATE TABLE `cars` (
  `car_id` int(11) NOT NULL,
  `car_brand_id` int(11) NOT NULL,
  `car_user_id` int(11) NOT NULL,
  `car_model` varchar(50) NOT NULL,
  `car_registration` varchar(50) NOT NULL,
  `car_energy` varchar(50) NOT NULL,
  `car_color` varchar(50) NOT NULL,
  `first_registration_date` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `cars`
--

INSERT INTO `cars` (`car_id`, `car_brand_id`, `car_user_id`, `car_model`, `car_registration`, `car_energy`, `car_color`, `first_registration_date`) VALUES
(1, 0, 0, 'Tesla Model 3', 'AB-123-CD', 'Électrique', 'Noir', '2020-01-15'),
(2, 0, 0, 'Renault Clio', 'CD-456-EF', 'Essence', 'Bleu', '2018-03-10'),
(3, 0, 0, 'Peugeot 208', 'GH-789-IJ', 'Diesel', 'Blanc', '2019-07-20'),
(4, 0, 0, 'Toyota Prius', 'KL-123-MN', 'Hybride', 'Vert', '2021-05-05'),
(5, 0, 0, 'Volkswagen Golf', 'OP-456-QR', 'Essence', 'Rouge', '2017-09-25'),
(6, 0, 0, 'BMW Série 3', 'ST-789-UV', 'Diesel', 'Gris', '2016-11-18'),
(7, 0, 0, 'Mercedes-Benz Classe A', 'WX-123-YZ', 'Essence', 'Noir', '2019-04-01'),
(8, 0, 0, 'Hyundai Kona', 'ZA-456-BC', 'Électrique', 'Bleu', '2022-06-10'),
(39, 23, 0, 'Série 3', 'AB-123-CD', 'Essence', 'Noir', '5200-02-10'),
(38, 22, 0, 'Série 3', 'AB-123-CD', 'Essence', 'Noir', '5520-02-10'),
(37, 21, 0, 'Série 3', 'AB-123-CD', 'Essence', 'Noir', '2005-02-12'),
(36, 20, 0, 'Série 3', 'AB-123-CD', 'Essence', 'Noir', '2005-12-10'),
(35, 19, 0, 'Série 3', 'AB-123-CD', 'Essence', 'Noir', '2005-12-10'),
(34, 18, 0, 'Série 3', 'AB-123-CD', 'Essence', 'Noir', '2005-12-10'),
(33, 17, 0, 'Série 3', 'AB-123-CD', 'Essence', 'Noir', '2008-02-10'),
(40, 24, 0, 'Série 3', 'AB-123-CD', 'Essence', 'Noir', '2005-02-10'),
(41, 4, 0, 'Série 3', 'AB-123-CD', 'Essence', 'Noir', '2007-02-10'),
(42, 25, 0, 'Challenge', '7Y-839-82', 'Essence', 'Chrome', '1978-02-12'),
(43, 4, 0, 'Série 3', 'EZ-RRE-32', 'Essence', 'NOIR', '2000-02-05'),
(44, 26, 0, 'RX', '73-BME-83', 'Diesel', 'Gris', '2009-12-22'),
(45, 27, 0, 'XE', 'EU-UYB-83', 'Diesel', 'Noir', '2019-02-12'),
(46, 8, 0, 'Santa FE', 'HE-732-BH', 'Électrique', 'Cramoisi', '2024-12-20'),
(47, 25, 18, 'Charger', '25-EZR-HE', 'Essence', 'Bleu', '2024-02-12'),
(56, 4, 4, 'Série 5', 'OP-737-BE', 'Essence', 'Noir', '2025-02-10'),
(57, 31, 14, 'Classe S', 'BS-783-JS', 'Essence', 'Noir', '2015-10-02'),
(58, 3, 20, '207', 'PM-837-NE', 'Diesel', 'Grise', '2007-05-06'),
(59, 6, 83, 'Yaris', 'YU-838-BN', 'Hybride', 'Noir', '2025-05-09');

-- --------------------------------------------------------

--
-- Structure de la table `configurations`
--

CREATE TABLE `configurations` (
  `configuration_id` int(11) NOT NULL,
  `configuration_user_id` int(11) NOT NULL,
  `configuration_name` varchar(50) NOT NULL,
  `configuration_value` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `configurations`
--

INSERT INTO `configurations` (`configuration_id`, `configuration_user_id`, `configuration_name`, `configuration_value`) VALUES
(28, 14, 'accepts_pets', 0),
(27, 14, 'accepts_smokers', 0),
(26, 4, 'accepts_pets', 1),
(25, 4, 'accepts_smokers', 1),
(24, 18, 'accepts_pets', 0),
(23, 18, 'accepts_smokers', 1),
(29, 20, 'accepts_smokers', 1),
(30, 20, 'accepts_pets', 1),
(31, 4, 'has_ac', 1),
(32, 4, 'has_usb', 1),
(33, 4, 'has_reclining_seats', 1),
(34, 4, 'has_wifi', 1),
(35, 4, 'has_large_trunk', 1),
(36, 20, 'has_ac', 1),
(37, 20, 'has_usb', 1),
(38, 20, 'has_reclining_seats', 1),
(39, 20, 'has_wifi', 1),
(40, 20, 'has_large_trunk', 1),
(41, 83, 'accepts_smokers', 1),
(42, 83, 'accepts_pets', 1),
(43, 83, 'has_ac', 1),
(44, 83, 'has_usb', 1),
(45, 83, 'has_reclining_seats', 1),
(46, 83, 'has_wifi', 1),
(47, 83, 'has_large_trunk', 1);

-- --------------------------------------------------------

--
-- Structure de la table `parameters`
--

CREATE TABLE `parameters` (
  `parameter_id` int(11) NOT NULL,
  `config_id` int(11) NOT NULL,
  `property` varchar(50) NOT NULL,
  `value` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `parameters`
--

INSERT INTO `parameters` (`parameter_id`, `config_id`, `property`, `value`) VALUES
(1, 0, 'site_name', 'Covoiturage Express'),
(2, 0, 'default_language', 'fr'),
(3, 0, 'max_reservations', '5'),
(4, 0, 'cancellation_fee', '2'),
(5, 0, 'support_email', 'support@example.com'),
(6, 0, 'min_password_length', '8'),
(7, 0, 'default_currency', 'EUR'),
(8, 0, 'max_vehicles_per_user', '3'),
(9, 0, 'timezone', 'Europe/Paris'),
(10, 0, 'ride_rating_scale', '1-5');

-- --------------------------------------------------------

--
-- Structure de la table `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reviewer_id` int(11) NOT NULL,
  `carpooling_id` int(11) DEFAULT NULL,
  `comment` varchar(50) NOT NULL,
  `rating` varchar(50) NOT NULL,
  `status` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `reviews`
--

INSERT INTO `reviews` (`review_id`, `user_id`, `reviewer_id`, `carpooling_id`, `comment`, `rating`, `status`) VALUES
(1, 0, 0, NULL, 'Excellent trajet, conducteur très sympathique.', '5', 'Validé'),
(2, 0, 0, NULL, 'Bonne expérience, mais un peu en retard.', '4', 'Validé'),
(3, 0, 0, NULL, 'Mauvaise communication avec le conducteur.', '2', 'Validé'),
(27, 16, 44, 63, 'Le chauffeur conduisait trop vite, je ne me suis p', '1', 'En attente'),
(5, 0, 0, NULL, 'Trajet parfait, rapide et écologique !', '5', 'Validé'),
(6, 0, 0, NULL, 'Conducteur très gentil mais la voiture n’était pas', '3', 'Validé'),
(8, 1, 101, NULL, 'Excellent trajet, chauffeur très professionnel.', '5', 'Validé'),
(9, 2, 102, NULL, 'Bonne expérience, mais un peu de retard.', '4', 'Validé'),
(10, 3, 103, NULL, 'Le conducteur était courtois, mais communication m', '3', 'Validé'),
(11, 4, 104, NULL, 'Super expérience, je recommande vivement !', '5', 'Validé'),
(12, 5, 105, NULL, 'Chauffeur sympathique, voyage agréable.', '4', 'Validé'),
(13, 1, 106, NULL, 'La voiture n’était pas impeccable, mais bon trajet', '3', 'Validé'),
(14, 2, 107, 63, 'Un trajet efficace, conducteur ponctuel.', '5', 'Validé'),
(15, 4, 44, 63, 'Super trajet.', '5', 'Validé'),
(16, 4, 4, 63, 'Super trajet, ils étaient très empathique', '5', 'Validé'),
(28, 19, 44, 63, '45 minutes de retard, aucune excuse. Très mauvaise', '2', 'En attente'),
(25, 14, 44, 60, 'Paul est super', '5', 'Validé'),
(26, 4, 44, 62, 'Super trajet', '5', 'En attente'),
(29, 20, 44, 63, 'La voiture sentait très mauvais, siège sale. ', '1', 'En attente'),
(30, 18, 44, 63, 'Le conducteur a tenu des propos déplacés', '1', 'En attente'),
(31, 13, 44, 63, 'Trajet annulé au dernier moment sans prévenir', '1', 'En attente'),
(32, 4, 4, 65, 'zef', '5', 'En attente'),
(33, 4, 1, 70, 'Très bon conducteur, agréable trajet.', '5', 'Validé'),
(34, 4, 44, 70, 'Bonne conduite, voiture propre, bon trajet.', '4', 'Validé'),
(36, 4, 45, 70, 'Chauffeur ponctuel, très agréable discussion.', '5', 'Validé'),
(37, 4, 46, 70, 'Très bon trajet, je recommande.', '4', 'Validé');

-- --------------------------------------------------------

--
-- Structure de la table `roles`
--

CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL,
  `label` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `roles`
--

INSERT INTO `roles` (`role_id`, `label`) VALUES
(6, 'Passager & Chauffeur'),
(2, 'Employé'),
(3, 'Utilisateur'),
(4, 'Chauffeur'),
(5, 'Passager');

-- --------------------------------------------------------

--
-- Structure de la table `settings`
--

CREATE TABLE `settings` (
  `setting_id` int(10) UNSIGNED NOT NULL,
  `title` varchar(70) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `description` text,
  `email1` varchar(100) DEFAULT NULL,
  `email2` varchar(100) DEFAULT NULL,
  `phone1` varchar(100) DEFAULT NULL,
  `phone2` varchar(100) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `settings`
--

INSERT INTO `settings` (`setting_id`, `title`, `slug`, `description`, `email1`, `email2`, `phone1`, `phone2`, `address`) VALUES
(1, 'EcoRide', 'www.ecoride.com', '', 'ecoride@gmail.com', 'ecoride@gmail.fr', '0648488978', '0648488979', '');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `address` varchar(50) NOT NULL,
  `date_of_birth` varchar(50) DEFAULT NULL,
  `photo` blob,
  `nickname` varchar(50) NOT NULL,
  `suspended` tinyint(1) NOT NULL DEFAULT '0',
  `driver_id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`user_id`, `name`, `firstname`, `email`, `password`, `phone`, `address`, `date_of_birth`, `photo`, `nickname`, `suspended`, `driver_id`) VALUES
(4, 'Durand', 'Paul', 'paul.durand@example.com', 'wa86yUWaA6Z4u6', '0512345678', '8 avenue de la Paix, Clermont-Ferrand', '1993-01-12', 0x757365725f342e6a7067, 'PaulD', 0, NULL),
(1, 'REISS', 'José', 'admin@example.com', 'adminpassword', '0645678901', '13 place des Vosges, Nancy', '1997-03-21', 0x757365725f312e706e67, 'ThomasC', 0, NULL),
(44, 'DIOR', 'Ruben', 'aneero.bat@gmail.com', 'G3ZMhdg2w236yC', '0512345679', '', '', 0x757365725f34342e6a7067, 'RubenP', 0, NULL),
(45, 'Martin', 'Claire', 'claire.martin@example.com', 'clairepass123', '0678901234', '10 rue de Nantes, Rennes', '1992-05-10', NULL, 'ClaireM', 0, NULL),
(46, 'Moreau', 'Lucie', 'lucie.moreau@example.com', 'luciepass123', '0678905678', '12 avenue Victor Hugo, Lille', '1993-08-25', NULL, 'LucieM', 0, NULL),
(47, 'Benali', 'Yassine', 'yassine.benali@example.com', 'yassinepass123', '0678912345', '8 rue de Rabat, Marseille', '1990-01-15', NULL, 'YassineB', 0, NULL),
(48, 'Durand', 'Claire', 'claire.durand@example.com', '$2y$10$Z7AFEkBOBa5cj6OCOADTquHZUscFjVeZEXNHNqoNNnobxZPb0IMIS', '0678123456', '15 rue de la Liberté, Lyon', '1990-05-20', 0x757365725f34382e6a7067, 'ClaireD', 0, NULL),
(49, 'STATHAM', 'Frank', 'frank.martin@example.com', '$2y$10$Uv.JMMk5GuZeTP.taA86eeop9YbZvcHF267EP.CXhzuZD6jx6PbMK', '0601020304', '12 boulevard de la Méditerranée, Nice', '1972-07-26', 0x757365725f34392e6a7067, 'TheTransporter', 0, NULL),
(54, '', '', 'parisH@gmail.com', '$2y$10$WxTszFtN0YLO25rXawPdO.RDY8zVEx.m6rhmyuEDKLtF9cX2NXGSS', '', '', NULL, NULL, 'ParisH', 0, NULL),
(83, 'DOZ', 'Rilès', 'doz.riles@gmail.com', 'a8w6WaUyZA4u66', '0601020304', '82 Avenue des Coquelicots', '1992-05-16', 0x757365725f38332e6a7067, 'RilesD', 0, 83),
(72, '', '', 'NasserU@gmail.com', '$2y$10$PSn5T28M062DYW7/bnodiu/JkiLvePaNZ5t1HDYE6KwvGSJjbhB2a', '', '', '', '', 'NasserU', 0, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `user_as_role`
--

CREATE TABLE `user_as_role` (
  `user_as_role_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `role_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `user_as_role`
--

INSERT INTO `user_as_role` (`user_as_role_id`, `user_id`, `role_id`) VALUES
(1, 1, 2),
(5, 18, 6),
(4, 4, 6),
(6, 1, 2),
(46, 20, 4),
(45, 13, 5),
(44, 19, 5),
(43, 43, 2),
(42, 14, 6),
(41, 44, 5),
(47, 48, 4),
(48, 49, 4),
(49, 64, 5),
(50, 73, 5),
(51, 78, 6),
(52, 79, 6),
(53, 80, 6),
(54, 81, 6),
(55, 83, 6);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`brand_id`);

--
-- Index pour la table `carpools`
--
ALTER TABLE `carpools`
  ADD PRIMARY KEY (`carpooling_id`);

--
-- Index pour la table `carpools_as_users`
--
ALTER TABLE `carpools_as_users`
  ADD PRIMARY KEY (`carpools_as_users_id`);

--
-- Index pour la table `cars`
--
ALTER TABLE `cars`
  ADD PRIMARY KEY (`car_id`);

--
-- Index pour la table `configurations`
--
ALTER TABLE `configurations`
  ADD PRIMARY KEY (`configuration_id`);

--
-- Index pour la table `parameters`
--
ALTER TABLE `parameters`
  ADD PRIMARY KEY (`parameter_id`);

--
-- Index pour la table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`);

--
-- Index pour la table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_id`);

--
-- Index pour la table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`setting_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Index pour la table `user_as_role`
--
ALTER TABLE `user_as_role`
  ADD PRIMARY KEY (`user_as_role_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `brands`
--
ALTER TABLE `brands`
  MODIFY `brand_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT pour la table `carpools`
--
ALTER TABLE `carpools`
  MODIFY `carpooling_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT pour la table `carpools_as_users`
--
ALTER TABLE `carpools_as_users`
  MODIFY `carpools_as_users_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT pour la table `cars`
--
ALTER TABLE `cars`
  MODIFY `car_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT pour la table `configurations`
--
ALTER TABLE `configurations`
  MODIFY `configuration_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT pour la table `parameters`
--
ALTER TABLE `parameters`
  MODIFY `parameter_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT pour la table `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `settings`
--
ALTER TABLE `settings`
  MODIFY `setting_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT pour la table `user_as_role`
--
ALTER TABLE `user_as_role`
  MODIFY `user_as_role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
