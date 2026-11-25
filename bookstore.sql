-- phpMyAdmin SQL Dump
-- version 5.2.2deb1+deb13u1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 25, 2026 at 01:55 PM
-- Server version: 11.8.9-MariaDB-0+deb13u1 from Debian
-- PHP Version: 8.4.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bookstore`
--

-- --------------------------------------------------------

--
-- Table structure for table `author`
--

CREATE TABLE `author` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `author`
--

INSERT INTO `author` (`id`, `name`) VALUES
(7, 'George Orwell'),
(8, 'Jane Austen'),
(9, 'Alexandre Dumas'),
(10, 'sadas'),
(11, 'Ray Bradbury'),
(12, 'lkjljkl'),
(13, 'SpOngEboB SqUaREpaNtS'),
(14, 'Funny cat'),
(15, 'Test'),
(16, 'Boom'),
(17, 'HAKA'),
(18, 'Hmmmm'),
(19, 'sadasd'),
(20, 'jk;lsad;jsadj'),
(21, 'fsadas'),
(22, 'Author');

-- --------------------------------------------------------

--
-- Table structure for table `book`
--

CREATE TABLE `book` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `publisher_id` int(11) DEFAULT NULL,
  `year_published` date DEFAULT NULL,
  `cover_name` varchar(255) DEFAULT NULL,
  `available` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `book`
--

INSERT INTO `book` (`id`, `title`, `publisher_id`, `year_published`, `cover_name`, `available`) VALUES
(82, 'Animal Farms', 34, '2026-11-28', 'HilariousCatMemes55-1795614415.jpg', 1),
(83, 'Nineteen Eighty-Four', 23, '2026-11-22', 'default.jpeg', 1),
(84, 'A Clergyman\'s Daughter', 22, '2026-11-12', 'default.jpeg', 0),
(85, 'Pride and Prejudice', 22, '2026-10-30', 'default.jpeg', 1),
(86, 'The Count of Monte Cristo', 23, '2026-11-14', 'images-1795614404.jpg', 1),
(87, 'Funny cat', 21, '2026-11-29', 'catmemewhitecatmeme-1795614715.jpg', 1),
(88, 'Fahrenheit 451', 21, '2026-10-28', 'default.jpeg', 0),
(89, 'mnbm', 22, '2026-11-29', 'default.jpeg', 0),
(90, 'NEw Book', 22, '2026-11-07', 'images1-1795100422.jpg', 1),
(91, 'New funny cat book', 26, '2026-11-05', 'stronk-1795614741.jpg', 1),
(92, 'Testing CoNST', 22, '2026-12-31', 'default.jpeg', 1),
(93, 'Const test2', 27, '2026-12-31', 'default.jpeg', 1),
(94, 'TEST 3 Boom', 28, '2026-01-31', 'default.jpeg', 1),
(95, 'CMON', 29, '2026-01-01', 'default.jpeg', 1),
(96, 'TEsting image type?', 30, '2026-01-31', 'default.jpeg', 0),
(97, 'asdas', 31, '0211-01-11', 'default.jpeg', 0),
(98, 'salkjdsakldj', 32, '1111-11-11', 'default.jpeg', 0),
(99, 'dsadsa', 33, '1231-12-12', '7rf5z-1795101962.jpg', 0),
(100, 'Cool Book #2', 34, '2026-01-01', 'photo20261031193854-1795155610.jpg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `book_author`
--

CREATE TABLE `book_author` (
  `book_id` int(11) DEFAULT NULL,
  `author_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `book_author`
--

INSERT INTO `book_author` (`book_id`, `author_id`) VALUES
(82, 7),
(83, 7),
(84, 7),
(85, 8),
(86, 9),
(87, 20),
(88, 11),
(89, 12),
(90, 13),
(91, 14),
(92, 9),
(93, 15),
(94, 16),
(95, 17),
(96, 18),
(97, 19),
(98, 20),
(99, 21),
(100, 22);

-- --------------------------------------------------------

--
-- Table structure for table `book_genres`
--

CREATE TABLE `book_genres` (
  `book_id` int(11) DEFAULT NULL,
  `genres_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `book_genres`
--

INSERT INTO `book_genres` (`book_id`, `genres_id`) VALUES
(82, 2),
(83, 1),
(84, 1),
(85, 16),
(86, 4),
(87, 2),
(88, 20),
(89, 19),
(90, 16),
(91, 2),
(92, 3),
(93, 4),
(94, 1),
(95, 5),
(96, 1),
(97, 1),
(98, 1),
(99, 1),
(100, 16);

-- --------------------------------------------------------

--
-- Table structure for table `book_languages`
--

CREATE TABLE `book_languages` (
  `book_id` int(11) DEFAULT NULL,
  `languages_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `book_languages`
--

INSERT INTO `book_languages` (`book_id`, `languages_id`) VALUES
(82, 1),
(83, 1),
(84, 1),
(85, 1),
(86, 1),
(87, 1),
(88, 12),
(89, 3),
(90, 7),
(91, 3),
(92, 4),
(93, 6),
(94, 1),
(95, 6),
(96, 1),
(97, 1),
(98, 1),
(99, 1),
(100, 8);

-- --------------------------------------------------------

--
-- Table structure for table `genres`
--

CREATE TABLE `genres` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `genres`
--

INSERT INTO `genres` (`id`, `name`) VALUES
(1, 'sci-fi'),
(2, 'Fantasy'),
(3, 'Science Fiction'),
(4, 'Dystopian'),
(5, 'Action & Adventure'),
(6, 'Mystery'),
(7, 'Horror'),
(8, 'Thriller & Suspense'),
(9, 'Historical Fiction'),
(10, 'Romance'),
(11, 'Women’s Fiction'),
(12, 'LGBTQ+'),
(13, 'Contemporary Fiction'),
(14, 'Literary Fiction'),
(15, 'Magical Realism'),
(16, 'Graphic Novel'),
(17, 'Short Story'),
(18, 'Young Adult'),
(19, 'New Adult'),
(20, 'Children’s'),
(21, 'Memoir & Autobiography'),
(22, 'Biography'),
(23, 'Food & Drink'),
(24, 'Art & Photography'),
(25, 'Self-help'),
(26, 'History'),
(27, 'Travel'),
(28, 'True Crime'),
(29, 'Humor'),
(30, 'Essays'),
(31, 'Guide/How-to'),
(32, 'Religion & Spirituality'),
(33, 'Humanities & Social Sciences'),
(34, 'Parenting & Families'),
(35, 'Science & Technology');

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `name`) VALUES
(1, 'English'),
(2, 'Dzongkha'),
(3, 'Dhivehi'),
(4, 'German'),
(5, 'Spanish'),
(6, 'Finnish'),
(7, 'Filipino'),
(8, 'French'),
(9, 'Persian'),
(10, 'Bulgarian'),
(11, 'Urdu'),
(12, 'Chinese');

-- --------------------------------------------------------

--
-- Table structure for table `publisher`
--

CREATE TABLE `publisher` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `publisher`
--

INSERT INTO `publisher` (`id`, `name`) VALUES
(21, 'Penguin'),
(22, 'WILLY WONKA'),
(23, 'Dancho'),
(24, 'asda'),
(25, ';l\';'),
(26, 'Koncho'),
(27, 'OK'),
(28, '21'),
(29, 'CAT'),
(30, 'No'),
(31, 'fsafasfas'),
(32, ';alkjdlsajdlak'),
(33, 'safdsafdsa'),
(34, 'Goofy ahh uncle production');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `rwd` int(3) NOT NULL COMMENT 'Integer representation of the boolean states for Read/Write/Delete permission structure\r\n'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `rwd`) VALUES
(1, 'admin', 111),
(2, 'user', 100),
(3, 'editor', 110);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `name`, `password`) VALUES
(4, 'kim@bookstore.com', 'Kimberly', '$2y$12$qz7i0hk9mc7rjwNREZIGeub0BWdEehmBwmRvt13.TBAoKmaz1uqd.'),
(7, 'admin@pytek.bg', 'Hristo', '$2y$12$SVZOK.Vbtr1XZjfvdhmnveQEE84KdXSQC7qwdK5bJR8NF9TInFj2u'),
(9, 'editor@pytek.bg', 'Goshko', '$2y$12$30t.VL6hoFamUnG/xrSdC.Wjzq2Goh8YNi5BfAX0anJmbFr3IQIiW');

-- --------------------------------------------------------

--
-- Table structure for table `users_roles`
--

CREATE TABLE `users_roles` (
  `users_id` int(11) DEFAULT NULL,
  `roles_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users_roles`
--

INSERT INTO `users_roles` (`users_id`, `roles_id`) VALUES
(4, 3),
(7, 1),
(9, 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `author`
--
ALTER TABLE `author`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `book`
--
ALTER TABLE `book`
  ADD PRIMARY KEY (`id`),
  ADD KEY `publisher_id` (`publisher_id`);

--
-- Indexes for table `book_author`
--
ALTER TABLE `book_author`
  ADD KEY `book_id` (`book_id`),
  ADD KEY `author_id` (`author_id`);

--
-- Indexes for table `book_genres`
--
ALTER TABLE `book_genres`
  ADD KEY `book_id` (`book_id`),
  ADD KEY `genres_id` (`genres_id`);

--
-- Indexes for table `book_languages`
--
ALTER TABLE `book_languages`
  ADD KEY `book_id` (`book_id`),
  ADD KEY `languages_id` (`languages_id`);

--
-- Indexes for table `genres`
--
ALTER TABLE `genres`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `publisher`
--
ALTER TABLE `publisher`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_roles`
--
ALTER TABLE `users_roles`
  ADD KEY `roles_id` (`roles_id`),
  ADD KEY `users_id` (`users_id`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `author`
--
ALTER TABLE `author`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `book`
--
ALTER TABLE `book`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT for table `genres`
--
ALTER TABLE `genres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `publisher`
--
ALTER TABLE `publisher`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `book`
--
ALTER TABLE `book`
  ADD CONSTRAINT `book_ibfk_1` FOREIGN KEY (`publisher_id`) REFERENCES `publisher` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `book_author`
--
ALTER TABLE `book_author`
  ADD CONSTRAINT `book_author_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `book` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `book_author_ibfk_2` FOREIGN KEY (`author_id`) REFERENCES `author` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `book_genres`
--
ALTER TABLE `book_genres`
  ADD CONSTRAINT `book_genres_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `book` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `book_genres_ibfk_2` FOREIGN KEY (`genres_id`) REFERENCES `genres` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `book_languages`
--
ALTER TABLE `book_languages`
  ADD CONSTRAINT `book_languages_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `book` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `book_languages_ibfk_2` FOREIGN KEY (`languages_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users_roles`
--
ALTER TABLE `users_roles`
  ADD CONSTRAINT `users_roles_ibfk_1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `users_roles_ibfk_2` FOREIGN KEY (`roles_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
