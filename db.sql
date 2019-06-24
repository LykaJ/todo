-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Jun 24, 2019 at 07:19 AM
-- Server version: 5.7.23
-- PHP Version: 7.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `todo`
--

-- --------------------------------------------------------

--
-- Table structure for table `task`
--

CREATE TABLE `task` (
                      `id` int(11) NOT NULL,
                      `created_at` datetime NOT NULL,
                      `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                      `content` longtext COLLATE utf8_unicode_ci NOT NULL,
                      `is_done` tinyint(1) NOT NULL,
                      `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `task`
--

INSERT INTO `task` (`id`, `created_at`, `title`, `content`, `is_done`, `user_id`) VALUES
(4, '2019-06-05 15:27:03', 'Task', 'Task1.0', 1, 1),
(5, '2019-06-05 15:47:38', 'eee', 'eee', 1, 1),
(10, '2019-06-06 09:17:06', 'Ana\'s task 1', 'My task', 1, 2),
(11, '2019-06-06 09:17:23', 'Ana\'s task 2', 'my second task', 0, 2),
(13, '2019-06-12 17:30:08', 'Do the thing!', 'Julie, do the thing!', 0, 2),
(15, '2019-06-13 13:58:11', 'My new task :3', 'Important task!', 0, 1),
(16, '2019-06-13 16:17:07', 'Task no author', 'Test', 0, NULL),
(17, '2019-06-21 14:37:28', 'hzaiegz', 'ezhaoe', 0, NULL),
(18, '2019-06-21 14:39:05', 'ehrzr', 'rezr', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
                      `id` int(11) NOT NULL,
                      `username` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
                      `password` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
                      `email` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
                      `role` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `email`, `role`) VALUES
(1, 'Admin', '$2y$12$xd35dPf.MqwezgSCoCswlePR/GfFhVzTHYdxvJPd9iA1pf9aod482', 'webdesigner.form@gmail.com', 'ROLE_ADMIN'),
(2, 'Ana', '$2y$12$4ebHY.DeqUgkhls7/ZpQ3uuQd8qgov55lLFbFURvCYsOTW4cXWB2.', 'anonymous@todo.com', 'ROLE_USER'),
(3, 'James', '$2y$12$UZTw0NjcK9U229o1lHBA8O8ZL.6bhF4R7e7QHVLGjwu14Ew9VP7Rq', 'james@email.com', 'ROLE_USER');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `task`
--
ALTER TABLE `task`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_527EDB25A76ED395` (`user_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649F85E0677` (`username`),
  ADD UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `task`
--
ALTER TABLE `task`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `task`
--
ALTER TABLE `task`
  ADD CONSTRAINT `FK_527EDB25A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
