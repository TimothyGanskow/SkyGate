-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 01. Dez 2023 um 11:50
-- Server-Version: 10.4.28-MariaDB
-- PHP-Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `skydatasystemssymfony`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20231127122950', '2023-11-27 17:45:08', 31),
('DoctrineMigrations\\Version20231127144325', '2023-11-27 17:45:08', 302),
('DoctrineMigrations\\Version20231127151544', '2023-11-27 17:45:08', 158),
('DoctrineMigrations\\Version20231127152619', '2023-11-27 17:45:08', 66),
('DoctrineMigrations\\Version20231127153615', '2023-11-27 17:45:08', 145),
('DoctrineMigrations\\Version20231127154538', '2023-11-27 17:45:08', 44),
('DoctrineMigrations\\Version20231127155618', '2023-11-27 17:45:08', 417);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `registry`
--

CREATE TABLE `registry` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `telefon` varchar(255) NOT NULL,
  `postcode` int(11) NOT NULL,
  `place` varchar(255) NOT NULL,
  `terms` tinyint(1) NOT NULL,
  `mail_confirmed` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `registry`
--

INSERT INTO `registry` (`id`, `name`, `telefon`, `postcode`, `place`, `terms`, `mail_confirmed`) VALUES
(26, 'Timothy', '123123123', 12312, 'berlin', 1, 0),
(61, 'asdads', '12312322233', 12312, 'berlin', 1, 0),
(62, 'asdads', '12312322233', 12312, 'berlin', 1, 0),
(63, 'asdads', '12312322233', 12312, 'berlin', 1, 0),
(64, 'asdads', '12312322233', 12312, 'berlin', 1, 1),
(65, 'Timothy Ganskow', '12312312312', 12207, 'berlin', 1, 1),
(66, 'asdads', '12312322233', 12312, 'berlin', 1, 1),
(67, 'asdads', '12312322233', 12312, 'kolllllln', 1, 1),
(73, 'Benjamin', '0302623888', 22094, 'bonn', 1, 0),
(75, 'darius', '2222123113', 12312, 'Dortmund', 1, 0),
(76, 'Timothy Ganskow', '1234455677', 3333333, 'berlin', 1, 1),
(77, 'Timothy Ganskow', '123123123123', 12207, 'berlin', 1, 1),
(81, 'admin', '1234567891', 12345, 'Aachen', 1, 1),
(84, 'Daniel', '33333333333', 12203, 'Stockholm', 1, 0),
(85, 'Eric', '44444444444', 55012, 'Peking', 1, 0),
(87, 'Gustav', '6666666666', 71234, 'Muenchen', 1, 0),
(88, 'Gustav', '030-6666666', 11234, 'Jakarta', 1, 0),
(89, 'Timothy Ganskow', '0302623888', 30000, 'Berlin', 1, 1),
(90, 'Timothy Ganskow', '2342342342', 12207, 'ber', 1, 1),
(92, 'Timothy Ganskow', '123123123', 12207, 'Köln', 1, 1),
(93, 'Ina', '030-6666666', 11234, 'Ingenburg', 1, 0),
(94, 'Johan', '030-6666666', 11234, 'jena', 1, 0),
(95, 'Kev', '030-6666666', 11234, 'Koeln', 1, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `users`
--

CREATE TABLE `users` (
  `id` bigint(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `passwort` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `mail_token` text NOT NULL,
  `refresh_token` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `ended_at` date DEFAULT NULL,
  `registry_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `users`
--

INSERT INTO `users` (`id`, `email`, `passwort`, `mail_token`, `refresh_token`, `created_at`, `ended_at`, `registry_id`, `permission_id`) VALUES
(73, 'berlin@otmail.de', '$2y$13$7hxVfZ9jBl4CeUXAvap2sOM6ogaA.M9lqEa/EtAi6ykB97cFu5gQe', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJsb2NhbGhvc3Q6ODAwMCIsImF1ZCI6ImxvY2FsaG9zdDo4MDAwIiwianRpIjoiYmVybGluQG90bWFpbC5kZSIsImlhdCI6MTcwMTM0OTc3OS4wMTEzNSwibmJmIjoxNzAxMzQ5Nzc5LjAxMTM1LCJleHAiOjE3MDEzNTMzNzkuMDExMzUsInVzZXJuYW1lIjoiYmVybGluQG90bWFpbC5kZSJ9.vhnGZFkVZK--tzzrRm_5eTSc9lCSmtcDjRpFUl-nIcU', NULL, '2023-11-30 14:09:39', NULL, 73, 72),
(75, 'derik@otmail.de', '$2y$13$lkqmT8aH6zkxelVRmovhBu008vxN6Ji3cYejUASXPIYXwoSJ6pR6y', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJsb2NhbGhvc3Q6ODAwMCIsImF1ZCI6ImxvY2FsaG9zdDo4MDAwIiwianRpIjoiZGVyaWtAb3RtYWlsLmRlIiwiaWF0IjoxNzAxMzQ5ODc5LjEzNTQ2MSwibmJmIjoxNzAxMzQ5ODc5LjEzNTQ2MSwiZXhwIjoxNzAxMzUzNDc5LjEzNTQ2MSwidXNlcm5hbWUiOiJkZXJpa0BvdG1haWwuZGUifQ.jRdCZn-Ov4OVLeHwDBOXiA6_NEqMN8r1dBtWu1MGqHw', NULL, '2023-11-30 14:11:19', NULL, 75, 74),
(81, 'admin@admin.de', '$2y$13$NT.glPxD4rPL.ZYDOnPGiu5vnLAiD5cWiSdMhhP0mcpcHi4b6a83y', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJsb2NhbGhvc3Q6ODAwMCIsImF1ZCI6ImxvY2FsaG9zdDo4MDAwIiwianRpIjoiYWRtaW5AYWRtaW4uZGUiLCJpYXQiOjE3MDE0MTczMTEuNjg2MTU2LCJuYmYiOjE3MDE0MTczMTEuNjg2MTU2LCJleHAiOjE3MDE0MjA5MTEuNjg2MTU2LCJ1c2VybmFtZSI6ImFkbWluQGFkbWluLmRlIn0.vIp33TyMjKRhCkAOAxV6pwUHK1Ofs4nJE4jwqKZ_GYo', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJsb2NhbGhvc3Q6ODAwMCIsImF1ZCI6ImxvY2FsaG9zdDo4MDAwIiwianRpIjoiYWRtaW5AYWRtaW4uZGUiLCJpYXQiOjE3MDE0MTk3MzAuODk4NTQ1LCJuYmYiOjE3MDE0MTk3MzAuODk4NTQ1LCJleHAiOjE3MDE0MjMzMzAuODk4NTQ1LCJ1c2VybmFtZSI6ImFkbWluQGFkbW', '2023-12-01 08:55:11', NULL, 81, 80),
(84, 'daniel@gmail.de', '$2y$13$CHJaJbQ/ePiH3kzfyGQkEexyfvLB6Uz8GUtGVtuCcYEvPth1hI4CW', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJsb2NhbGhvc3Q6ODAwMCIsImF1ZCI6ImxvY2FsaG9zdDo4MDAwIiwianRpIjoiZGFuaWVsQGdtYWlsLmRlIiwiaWF0IjoxNzAxNDE3NDcxLjczNjcxMSwibmJmIjoxNzAxNDE3NDcxLjczNjcxMSwiZXhwIjoxNzAxNDIxMDcxLjczNjcxMSwidXNlcm5hbWUiOiJkYW5pZWxAZ21haWwuZGUifQ.aA7VYXu8CJ6qawcQY1NY1G4kyrGpiTRUIiYWUfqqGDI', NULL, '2023-12-01 08:57:51', NULL, 84, 83),
(85, 'erich@gmail.de', '$2y$13$Dv2YJpOzPaBYH.W2GXn7h.XN.GsriKHMaY3Ykz4CLrcpwAFSIRVHS', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJsb2NhbGhvc3Q6ODAwMCIsImF1ZCI6ImxvY2FsaG9zdDo4MDAwIiwianRpIjoiZXJpY2hAZ21haWwuZGUiLCJpYXQiOjE3MDE0MTc1MTUuODUyNjI5LCJuYmYiOjE3MDE0MTc1MTUuODUyNjI5LCJleHAiOjE3MDE0MjExMTUuODUyNjI5LCJ1c2VybmFtZSI6ImVyaWNoQGdtYWlsLmRlIn0.v3OFDzSWNOxb2H0_bhkKr1n_ZZmDh0SfEa7D98FESaM', NULL, '2023-12-01 08:58:35', NULL, 85, 84),
(87, 'gustav@arcor.de', '$2y$13$.wft4jo2PzuVVPe.EAU/suwOKh.t7eHuke0aZzX0Xkeg1LFwq7TCC', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJsb2NhbGhvc3Q6ODAwMCIsImF1ZCI6ImxvY2FsaG9zdDo4MDAwIiwianRpIjoiZ3VzdGF2QGFyY29yLmRlIiwiaWF0IjoxNzAxNDE3NjUyLjIxMDI3MywibmJmIjoxNzAxNDE3NjUyLjIxMDI3MywiZXhwIjoxNzAxNDIxMjUyLjIxMDI3MywidXNlcm5hbWUiOiJndXN0YXZAYXJjb3IuZGUifQ.QI3uUooq3HyoB5mVvfviZCGOxy99cFJvP2VOtjL-BMo', NULL, '2023-12-01 09:00:52', NULL, 87, 86),
(88, 'Harry@hotmail.de', '$2y$13$cFJe04DaDjSFJdzNkK2mEeZ/0J58q4GhDq/ok4YABUQsDLTdH47bS', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJsb2NhbGhvc3Q6ODAwMCIsImF1ZCI6ImxvY2FsaG9zdDo4MDAwIiwianRpIjoiSGFycnlAaG90bWFpbC5kZSIsImlhdCI6MTcwMTQxNzgyNC40MzUwODksIm5iZiI6MTcwMTQxNzgyNC40MzUwODksImV4cCI6MTcwMTQyMTQyNC40MzUwODksInVzZXJuYW1lIjoiSGFycnlAaG90bWFpbC5kZSJ9.BCdifZpo6wKr2goY1U7i9R7XVbOulbm_DchjqBXA3ig', NULL, '2023-12-01 09:03:44', NULL, 88, 87),
(92, 't.gee@hotmail.de', '$2y$13$ga./N1xEotsTxR7IF7nrqueZuD/N.3iv7KCB3CUoTtvFnAlFh0qdy', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJsb2NhbGhvc3Q6ODAwMCIsImF1ZCI6ImxvY2FsaG9zdDo4MDAwIiwianRpIjoidC5nZWVAaG90bWFpbC5kZSIsImlhdCI6MTcwMTQyNzMyMy43MDkzNzcsIm5iZiI6MTcwMTQyNzMyMy43MDkzNzcsImV4cCI6MTcwMTQzMDkyMy43MDkzNzcsInVzZXJuYW1lIjoidC5nZWVAaG90bWFpbC5kZSJ9.LM-FiJAct-OhL0sh1PStrW0S76u1CXuX-M6Avby_fAE', NULL, '2023-12-01 11:42:03', NULL, 92, 91),
(93, 'Ines@hotmail.de', '$2y$13$BhHy8aXWM2tHtLs8.5XQd.WeTaWQ44VsylcH/5Wxk6WnUytZYrDk2', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJsb2NhbGhvc3Q6ODAwMCIsImF1ZCI6ImxvY2FsaG9zdDo4MDAwIiwianRpIjoiSW5lc0Bob3RtYWlsLmRlIiwiaWF0IjoxNzAxNDI3NTY0LjE2NTEwNiwibmJmIjoxNzAxNDI3NTY0LjE2NTEwNiwiZXhwIjoxNzAxNDMxMTY0LjE2NTEwNiwidXNlcm5hbWUiOiJJbmVzQGhvdG1haWwuZGUifQ.IYQ0Qepa_If4-KqlGvEgPlzAIQz1tapnpsrKN4FzMzs', NULL, '2023-12-01 11:46:04', NULL, 93, 92),
(94, 'johan@hotmail.de', '$2y$13$CNQc9ADHJBDc7N2JXcr5k.jg8yy3FDnCHNNoDlHTaBPA.Ln7IdIKi', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJsb2NhbGhvc3Q6ODAwMCIsImF1ZCI6ImxvY2FsaG9zdDo4MDAwIiwianRpIjoiam9oYW5AaG90bWFpbC5kZSIsImlhdCI6MTcwMTQyNzYwNC45MDE4NTMsIm5iZiI6MTcwMTQyNzYwNC45MDE4NTMsImV4cCI6MTcwMTQzMTIwNC45MDE4NTMsInVzZXJuYW1lIjoiam9oYW5AaG90bWFpbC5kZSJ9.MXgCC3PtTTy5kHQxtXaPPYOe4tqZvAqO0uOnAl2pUXw', NULL, '2023-12-01 11:46:44', NULL, 94, 93),
(95, 'kevin@hotmail.de', '$2y$13$RDbKvDcl2sRz/bS5ZDjkt./zpJA32.v7vzogk3m0UjKtTu5aOtHwO', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJsb2NhbGhvc3Q6ODAwMCIsImF1ZCI6ImxvY2FsaG9zdDo4MDAwIiwianRpIjoia2V2aW5AaG90bWFpbC5kZSIsImlhdCI6MTcwMTQyNzY0NS41NTY5NjEsIm5iZiI6MTcwMTQyNzY0NS41NTY5NjEsImV4cCI6MTcwMTQzMTI0NS41NTY5NjEsInVzZXJuYW1lIjoia2V2aW5AaG90bWFpbC5kZSJ9.aD0D2CTMTfm6rSWZvLF6GjUdrSvbH6JOGDHAvtsuHDM', NULL, '2023-12-01 11:47:25', NULL, 95, 94);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `userspermission`
--

CREATE TABLE `userspermission` (
  `id` int(11) NOT NULL,
  `permission` int(11) NOT NULL,
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `userspermission`
--

INSERT INTO `userspermission` (`id`, `permission`, `updated_at`) VALUES
(25, 1, '2023-11-29 10:05:28'),
(60, 1, '2023-11-29 13:00:28'),
(61, 1, '2023-11-29 13:00:49'),
(62, 1, '2023-11-29 13:02:23'),
(63, 1, '2023-11-29 13:13:29'),
(64, 1, '2023-11-29 14:19:11'),
(65, 1, '2023-11-29 14:53:02'),
(66, 2, '2023-11-29 14:55:11'),
(72, 1, '2023-11-30 14:09:39'),
(74, 1, '2023-11-30 14:11:19'),
(75, 1, '2023-11-30 16:13:24'),
(76, 3, '2023-11-30 16:18:33'),
(80, 1, '2023-12-01 08:55:11'),
(83, 1, '2023-12-01 08:57:51'),
(84, 1, '2023-12-01 08:58:35'),
(86, 1, '2023-12-01 09:00:52'),
(87, 1, '2023-12-01 09:03:44'),
(88, 1, '2023-12-01 09:31:10'),
(91, 3, '2023-12-01 11:42:03'),
(92, 1, '2023-12-01 11:46:04'),
(93, 1, '2023-12-01 11:46:44'),
(94, 3, '2023-12-01 11:47:25');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Indizes für die Tabelle `registry`
--
ALTER TABLE `registry`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniqeEmail` (`email`) USING BTREE;

--
-- Indizes für die Tabelle `userspermission`
--
ALTER TABLE `userspermission`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `registry`
--
ALTER TABLE `registry`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- AUTO_INCREMENT für Tabelle `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- AUTO_INCREMENT für Tabelle `userspermission`
--
ALTER TABLE `userspermission`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
