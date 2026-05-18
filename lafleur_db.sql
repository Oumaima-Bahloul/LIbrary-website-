-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 18, 2026 at 12:41 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lafleur_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `quantity`, `added_at`) VALUES
(35, 10, 31, 1, '2026-05-15 15:58:03'),
(57, 9, 5, 1, '2026-05-17 08:23:08');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `user_email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('Pending','Shipped','Delivered','Cancelled') DEFAULT 'Pending',
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_name`, `user_email`, `phone`, `address`, `total_price`, `status`, `order_date`, `created_at`) VALUES
(1, 'oumaima', 'contact@example.com', NULL, NULL, 105700.00, 'Pending', '2026-05-15 17:39:04', '2026-05-15 19:30:20');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price_at_purchase` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price_at_purchase`) VALUES
(1, 1, 38, 1, 38500.00),
(2, 1, 53, 1, 28500.00),
(3, 1, 31, 1, 38700.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(150) DEFAULT NULL,
  `category` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `original_price` decimal(10,2) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `stock` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `format` varchar(50) DEFAULT NULL,
  `material` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `title`, `author`, `category`, `price`, `original_price`, `image`, `stock`, `created_at`, `format`, `material`) VALUES
(1, 'Des hommes sans femmes', 'Haruki Murakami', 'books', 63000.00, 84000.00, 'images/des-hommes-sans-femmes.jpg', 10, '2026-05-08 14:55:19', NULL, NULL),
(2, 'L\'atlas des explorations', NULL, 'books', 59700.00, 79600.00, 'images/l-atlas-des-explorations-les-hommes-et-les-femmes-à-la-découverte-du-monde-et-de-l-univers.jpg', 10, '2026-05-08 14:55:19', NULL, NULL),
(3, 'Slammed', 'Colleen Hoover', 'books', 35150.00, 37000.00, 'images/slammed-colleen-hoover.webp', 10, '2026-05-08 14:55:19', NULL, NULL),
(4, 'Different Strokes By Different Folks', NULL, 'books', 33250.00, 35000.00, 'images/different-strokes-by-different-folks.webp', 10, '2026-05-08 14:55:19', NULL, NULL),
(5, 'Atonement', 'Ian McEwan', 'books', 37525.00, 39500.00, 'images/atonement-ian-mcewan.webp', 10, '2026-05-08 14:55:19', NULL, NULL),
(6, 'The Mad Women\'s Ball', 'Victoria Mas', 'books', 33630.00, 35400.00, 'images/the-mad-women-s-ball-victoria-mas.webp', 10, '2026-05-08 14:55:19', NULL, NULL),
(7, 'Philosophicae Historica', NULL, 'books', 94200.00, 125600.00, 'images/philosophicae-historica.jpg', 10, '2026-05-08 14:55:19', NULL, NULL),
(8, 'The Old Man and the Sea', 'Ernest Hemingway', 'books', 27990.00, 39990.00, 'images/The old man and the seaa.jpg', 10, '2026-05-08 14:55:19', NULL, NULL),
(9, 'Agenda Quotidien – Planner journalier structuré & élégant', NULL, 'stationery', 18500.00, 24500.00, 'images/agenda.webp', 10, '2026-05-08 14:55:19', NULL, NULL),
(10, 'Coffret 4 Surligneurs Fluorescents', NULL, 'stationery', 12040.00, 15900.00, 'images/fluo.webp', 10, '2026-05-08 14:55:19', NULL, NULL),
(11, 'Coffret en bois 60 Crayons fusains pastel CarbOthello Stabilo', NULL, 'stationery', 147375.00, 327500.00, 'images/coffret stylo.webp', 10, '2026-05-08 14:55:19', NULL, NULL),
(12, 'STABILO POINT 88 ROLLERSET', NULL, 'stationery', 48750.00, 75000.00, 'images/stylos.webp', 10, '2026-05-08 14:55:19', NULL, NULL),
(13, 'Agrafeuse STD Popular S-80 – Full Strip plastique', NULL, 'supp', 9000.00, 10000.00, 'images/aggra.webp', 10, '2026-05-08 14:55:19', NULL, NULL),
(14, 'Calculatrice graphique avec Python GRAPH 35+E II - CASIO', NULL, 'supp', 315000.00, 450000.00, 'images/cal.webp', 10, '2026-05-08 14:55:19', NULL, NULL),
(15, 'LOUPE METAL 100MM G03-06 FOSKA', NULL, 'supp', 10000.00, 13550.00, 'images/loupe.webp', 10, '2026-05-08 14:55:19', NULL, NULL),
(16, 'Calculatrice scientifique Casio fx-991 ES PLUS 2nd edition', NULL, 'supp', 65000.00, 93000.00, 'images/calculatrice-scientifique-casio-fx-991-es-plus-2nd-edition-bureautique.webp', 10, '2026-05-08 14:55:19', NULL, NULL),
(17, 'Tapis Puzzle en Mousse EVA – Chiffres 1 à 9', NULL, 'edu', 25420.00, 29900.00, 'images/tapis 1 9.webp', 10, '2026-05-08 14:55:19', NULL, NULL),
(18, 'Projecteur de Dessin Enfant – Table de Projection', NULL, 'edu', 36510.00, 42900.00, 'images/projecy.webp', 10, '2026-05-08 14:55:19', NULL, NULL),
(19, 'Little Cute Duck Magnetic Drawing Board', NULL, 'edu', 24510.00, 28900.00, 'images/little-cute-duck-magnetic-drawing-board-age-3-jouets.webp', 10, '2026-05-08 14:55:19', NULL, NULL),
(20, 'Tapis Puzzle en Mousse EVA – Formes & Couleurs', NULL, 'edu', 25420.00, 29900.00, 'images/tapis shapes.webp', 10, '2026-05-08 14:55:19', NULL, NULL),
(21, 'Pack Mandalas + Crayons Graphic Marker – Small', NULL, 'gifs', 45000.00, 70000.00, 'images/pack_kalino_mandalas_12_sea.webp', 10, '2026-05-08 14:55:19', NULL, NULL),
(22, 'Pack Mandalas + Crayons Graphic Marker – Medium', NULL, 'gifs', 129000.00, 175000.00, 'images/mandakas_wild_life_kalino_blanc_pack.webp', 10, '2026-05-08 14:55:19', NULL, NULL),
(23, 'Pack Mandalas + Crayons Graphic Marker – Large', NULL, 'gifs', 223000.00, 292500.00, 'images/pack_kalino_mandalas_120_sea.webp', 10, '2026-05-08 14:55:19', NULL, NULL),
(24, 'TROUSSE-COOL SCHOOL La Fleur', NULL, 'gifs', 9950.00, 23000.00, 'images/trousse-cool-school-bagagerie-2.webp', 10, '2026-05-08 14:55:19', NULL, NULL),
(25, 'Trousse Rond La Fleur', NULL, 'gifs', 12950.00, 22500.00, 'images/trousse rond.webp', 10, '2026-05-08 14:55:19', NULL, NULL),
(26, 'Pack Sketch & Dessin – Crayons Artistiques 24 Couleurs', NULL, 'gifs', 50000.00, 68900.00, 'images/pack_1.webp', 10, '2026-05-08 14:55:19', NULL, NULL),
(27, 'Tente de Jeu Enfant – Frozen', NULL, 'gifs', 26800.00, 33500.00, 'images/80012672_froozen_2.webp', 10, '2026-05-08 14:55:19', NULL, NULL),
(28, 'Chess – Magnetic Game', NULL, 'gifs', 62900.00, 69900.00, 'images/chess-magnetic-game-age-6-jouets.webp', 10, '2026-05-08 14:55:19', NULL, NULL),
(29, 'L\'Étranger', 'Albert Camus', 'books', 31920.00, 31920.00, 'images/etrangercamus.jpg', 10, '2026-05-08 14:55:19', NULL, NULL),
(30, 'The Picture of Dorian Gray', 'Oscar Wilde', 'books', 32200.00, 32200.00, 'images/doriangray.jpg', 10, '2026-05-08 14:55:19', NULL, NULL),
(31, 'Beyond Good and Evil', 'Friedrich Nietzsche', 'books', 38700.00, 38700.00, 'images/beyond-good-and-evil-friedrich-nietzsche.jpg', 10, '2026-05-08 14:55:19', NULL, NULL),
(32, 'Mouse\'s Night Before Christmas', 'Tracey Corderoy', 'books', 22000.00, 22000.00, 'images/mouse-s-night-before-christmas-.jpg', 10, '2026-05-08 14:55:19', NULL, NULL),
(33, 'Fiesta', 'Ernest Hemingway', 'books', 35400.00, 35400.00, 'images/fiesta-ernest-hemingway.jpg', 10, '2026-05-08 14:55:19', NULL, NULL),
(34, 'Harry Potter and the Half-Blood Prince', 'J. K. Rowling', 'books', 43500.00, 43500.00, 'images/harry-potter-and-the-half-blood-prince-book-6.jpg', 10, '2026-05-08 14:55:19', NULL, NULL),
(35, 'First Person Singular', 'Haruki Murakami', 'books', 38500.00, 38500.00, 'images/first-person-singular-haruki-murakami.jpg', 10, '2026-05-08 14:55:19', NULL, NULL),
(36, 'Fire and blood', 'Martin George R.R', 'books', 51600.00, 51600.00, 'images/fire-and-blood-martin-george-rr.jpg', 10, '2026-05-08 14:55:19', NULL, NULL),
(37, 'Harry Potter Box Set: The Complete Collection (Children’s Paperback)', 'J. K. Rowling', 'books', 243800.00, 243800.00, 'images/harry-potter-boxed-set-the-complete-collection-7-paperbacks.jpg', 10, '2026-05-08 14:55:19', NULL, NULL),
(38, 'The Architect\'s Apprentice', 'Elif Shafak', 'books', 38500.00, 38500.00, 'images/the-architect-s-apprentice-elif-shafak-9780241970942.jpg', 10, '2026-05-08 14:55:19', NULL, NULL),
(39, 'Macbeth - Conrad Mason - Young Reading Series 2', 'Shakespeare', 'books', 20000.00, 20000.00, 'pics/macbeth-conrad-mason-young-reading-series-2.jpg', 10, '2026-05-08 14:55:19', NULL, NULL),
(40, 'The Lion King', 'Disney MOVIES', 'books', 34300.00, 34300.00, 'pics/the-lion-king-disney-movies.jpg', 10, '2026-05-08 14:55:19', NULL, NULL),
(41, 'Oxford Wordpower Dictionary 4th edition', 'the 4th edition', 'books', 36000.00, 36000.00, 'pics/oxford-wordpower-dictionary-4th-edition.jpg', 10, '2026-05-08 14:55:19', NULL, NULL),
(42, 'Français et Maths CE2 - Nouveau programme', 'Maître Lucas', 'books', 31500.00, 31500.00, 'pics/français-et-maths-ce2-nouveau-programme-cahier-de-soutien-avec-des-videos.jpg', 10, '2026-05-08 14:55:19', NULL, NULL),
(43, 'الأعمال الكاملة', 'جبران خليل جبران', 'books', 89000.00, 89000.00, 'pics/جبران-خليل-جبران-الأعمال-الكاملة.jpg', 10, '2026-05-08 14:55:19', NULL, NULL),
(44, 'Anna Karenina', 'Leo Tolstoy', 'books', 17500.00, 17500.00, 'pics/anna-karenina-leo-tolstoy.jpg', 10, '2026-05-08 14:55:19', NULL, NULL),
(45, 'Grimm\'s Fairy Tales', 'the Brothers Grimm', 'books', 31720.00, 31720.00, 'pics/grimm-s-fairy-tales.jpg', 10, '2026-05-08 14:55:19', NULL, NULL),
(46, 'The Essential Kafka', 'Franz Kafka', 'books', 17500.00, 17500.00, 'pics/the-essential-kafka-franz-kafka.jpg', 10, '2026-05-08 14:55:19', NULL, NULL),
(47, 'امرأة مثلها', 'مارك ليفي', 'books', 46500.00, 46500.00, 'pics/امرأة-مثلها-مارك-ليفي-9786144693391.jpg', 10, '2026-05-08 14:55:19', NULL, NULL),
(48, 'Alice\'s Adventures in Wonderland', 'Lewis Carroll', 'books', 17500.00, 17500.00, 'pics/alice-s-adventures-in-wonderland-lewis-carroll.jpg', 10, '2026-05-08 14:55:19', NULL, NULL),
(49, 'The Scarlet Letter', 'Nathaniel Hawthorne', 'books', 17500.00, 17500.00, 'pics/the-scarlet-letter-nathaniel-hawthorne (1).jpg', 10, '2026-05-08 14:55:19', NULL, NULL),
(50, 'Harry Potter & the Deathly Hallows', 'J.K. Rowling', 'books', 39600.00, 39600.00, 'pics/harry-potter-the-deathly-hallows-harry-potter-9781408894743.jpg', 10, '2026-05-08 14:55:19', NULL, NULL),
(51, 'Hamlet', 'William Shakespeare', 'books', 12000.00, 12000.00, 'pics/hamlet-william-shakespeare.jpg', 10, '2026-05-08 14:55:19', NULL, NULL),
(52, 'Bonjour tristesse', 'Françoise Sagan', 'books', 22150.00, 22150.00, 'pics/bonjour-tristesse-françoise-sagan.jpg', 10, '2026-05-08 14:55:19', NULL, NULL),
(53, 'Jujutsu Kaisen Tome 15', 'Gege Akutami', 'books', 28500.00, 28500.00, 'pics/jujutsu-kaisen-tome-15-le-drame-de-shibuya-transformation.jpg', 10, '2026-05-08 14:55:19', NULL, NULL),
(54, 'Jujutsu Kaisen Tome 18', 'Gege Akutami', 'books', 28500.00, 28500.00, 'pics/jujutsu-kaisen-tome-18-la-passion-pack-avec-un-extrait-gratuit-de-valhallian-the-black-iron.jpg', 10, '2026-05-08 14:55:19', NULL, NULL),
(55, 'Atomic Habits (E-Book)', 'James Clear', 'ebooks', 45000.00, 45000.00, 'https://m.media-amazon.com/images/I/81kg51XRc1L._AC_UF1000,1000_QL80_.jpg', 999, '2026-05-08 14:55:19', NULL, NULL),
(56, 'It Ends With Us (E-Book)', 'Colleen Hoover', 'ebooks', 38000.00, 38000.00, 'https://m.media-amazon.com/images/I/91CqNElQaKL._AC_UF1000,1000_QL80_.jpg', 999, '2026-05-08 14:55:19', NULL, NULL),
(57, 'The Silent Patient (E-Book)', 'Alex Michaelides', 'ebooks', 32500.00, 32500.00, 'https://m.media-amazon.com/images/I/81JJPDNlxSL.jpg', 999, '2026-05-08 14:55:19', NULL, NULL),
(58, 'The Alchemist (E-Book)', 'Paulo Coelho', 'ebooks', 22000.00, 22000.00, 'https://m.media-amazon.com/images/I/71aFt4+OTOL.jpg', 999, '2026-05-08 14:55:19', NULL, NULL),
(59, 'Psychology of Money (E-Book)', 'Morgan Housel', 'ebooks', 42000.00, 42000.00, 'https://m.media-amazon.com/images/I/81gC3mdNi5L._AC_UF350,350_QL50_.jpg', 999, '2026-05-08 14:55:19', NULL, NULL),
(60, 'The Women', 'Kristin Hannah', 'trending', 34000.00, 34000.00, 'https://m.media-amazon.com/images/I/81oydfRzeBL._AC_UF894,1000_QL80_.jpg', 20, '2026-05-08 14:55:19', NULL, NULL),
(61, 'The Familiar', 'Leigh Bardugo', 'trending', 39500.00, 39500.00, 'https://images-eu.ssl-images-amazon.com/images/I/71RPKZO2KTL._AC_UL210_SR210,210_.jpg', 20, '2026-05-08 14:55:19', NULL, NULL),
(62, 'Funny Story', 'Emily Henry', 'trending', 31000.00, 31000.00, 'https://m.media-amazon.com/images/I/71BwgHvRwsL._AC_UF894,1000_QL80_.jpg', 20, '2026-05-08 14:55:19', NULL, NULL),
(63, 'The Night We Met', 'Abby Jimenez', 'trending', 36000.00, 36000.00, 'https://m.media-amazon.com/images/I/71yocQXZOeL._AC_UF1000,1000_QL80_.jpg', 20, '2026-05-08 14:55:19', NULL, NULL),
(64, 'In Her Own League', 'Liz Tomforde', 'Books', 32500.00, 32500.00, 'https://m.media-amazon.com/images/I/816aj03hJgL._AC_UF894,1000_QL80_.jpg', 20, '2026-05-08 14:55:19', NULL, NULL),
(65, 'The Keeper', 'Tana French', 'trending', 42000.00, 42000.00, 'https://m.media-amazon.com/images/I/81f1WIQgP+L._UF1000,1000_QL80_.jpg', 20, '2026-05-08 14:55:19', NULL, NULL),
(66, 'Cool Machine', 'Colson Whitehead', 'trending', 45000.00, 45000.00, 'https://m.media-amazon.com/images/I/7152BofVnpL._AC_UF1000,1000_QL80_.jpg', 20, '2026-05-08 14:55:19', NULL, NULL),
(67, 'National Geographic', 'April 2026 Edition', 'magazines', 50500.00, 50500.00, 'https://ngsingleissues.nationalgeographic.com/media/catalog/product/cache/d24a34bded6d2df740ef66d66d2112c9/n/g/ngm_apr_2026.jpg', 15, '2026-05-08 14:55:19', NULL, NULL),
(68, 'Vogue', 'Spring Fashion Issue', 'magazines', 150000.00, 150000.00, 'https://assets.vogue.com/photos/698a07550541cfec002af4a3/master/w_1600%2Cc_limit/VO0326_SocialCover.jpg', 10, '2026-05-08 14:55:19', NULL, NULL),
(69, 'Wired', 'Future of AI Special', 'magazines', 14200.00, 14200.00, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ1491Ykzfjaj8TiMP7p1RzfPJmztlkSxOB0g&s', 25, '2026-05-08 14:55:19', NULL, NULL),
(70, 'Rechargeable LED Book Light', 'Adjustable Warmth & Brightness', 'supplies', 28000.00, 28000.00, 'https://m.media-amazon.com/images/I/71uNUqlJ8jL._AC_SL1500_.jpg', 50, '2026-05-08 14:55:19', NULL, NULL),
(71, 'Golden Feather Bookmark', 'Handcrafted Brass', 'supplies', 12500.00, 12500.00, 'https://m.media-amazon.com/images/I/81cbZMwzvJL._AC_UF894,1000_QL80_.jpg', 100, '2026-05-08 14:55:19', NULL, NULL),
(72, '\"Just One More Chapter\" Tote', '100% Organic Cotton', 'supplies', 22000.00, 22000.00, 'https://outofprint.com/cdn/shop/files/TOTE-1131-Tote-Just-One-More-Chapter-01_1200x1600.jpg?v=1710775544', 30, '2026-05-08 14:55:19', NULL, NULL),
(73, 'Minimalist Daily Planner', 'Linen Cover • 120gsm Paper', 'stationery', 45000.00, 45000.00, 'https://www.graphicimage.com/cdn/shop/files/AJL-TIM-BCH-OPEN-26.jpg?v=1751998774', 25, '2026-05-08 14:55:19', NULL, NULL),
(74, 'Matte Black Fountain Pen', 'Fine Nib • Refillable Ink', 'stationery', 32500.00, 32500.00, 'https://images-na.ssl-images-amazon.com/images/I/61JU4aWt0YL._SS400_.jpg', 15, '2026-05-08 14:55:19', NULL, NULL),
(75, 'Botanical Washi Tape Set', '3 Rolls of Decorative Tape', 'stationery', 22000.00, 22000.00, 'https://ap.filofax.com/cdn/shop/products/132814__open.webp?v=1673286284', 40, '2026-05-08 14:55:19', NULL, NULL),
(76, 'Robotics Workshop Pro', 'STEM Education', 'edu', 145000.00, 145000.00, 'https://content.jdmagicbox.com/v2/comp/delhi/s1/011pxx11.xx11.250521110026.u6s1/catalogue/robogenius-pro-tilak-nagar-delhi-tutorials-dxzgyz9zhk.jpg', 10, '2026-05-08 14:55:19', NULL, NULL),
(77, 'Solar Energy Explorer', 'Green Science', 'edu', 680000.00, 680000.00, 'https://drtechpr.com/cdn/shop/files/IMG-9619.jpg?v=1751988184&width=1214', 10, '2026-05-08 14:55:19', NULL, NULL),
(79, 'test1', NULL, 'Trending', 99.99, NULL, 'https://img.freepik.com/premium-vector/icon-design-test-paper_362714-11527.jpg', 0, '2026-05-15 17:16:42', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `address` text DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `address`, `email`, `password`, `role`, `created_at`) VALUES
(2, 'omar', 'tounsi', NULL, 'tounsiomat@gmail.com', '$2y$10$R6mfpOHTLZFa5ce43gGRlOqfFvIFagFWUTNrbP13B0ohmrjtKiIN.', 'user', '2026-05-08 15:40:42'),
(8, 'admin', '1', NULL, 'admin@gmail.com', '$2y$10$xx/Gs6nBizdKiwIsK.SV2uPc9ifOOz.5T6K3GsDTzDm9sepS5wZNy', 'admin', '2026-05-13 13:31:23'),
(9, 'oumaima', 'bahloul', '', 'oumaibahloul@gmail.com', '$2y$10$lYJskbsmGulo2dj16eHgu.uVCWR24UFup1VoO6yDzhnWtdQZBcGkm', 'user', '2026-05-13 13:34:31'),
(10, 'mimi', 'mou', NULL, 'mimou@gmail.com', '$2y$10$z.vEQUvmCsGsCKsfwobL2./9lbrPuPTklk04lwz0kQcQCcG5ckKNW', 'user', '2026-05-15 15:57:46');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wishlist`
--

INSERT INTO `wishlist` (`id`, `user_id`, `product_id`, `added_at`) VALUES
(33, 10, 31, '2026-05-15 15:58:01'),
(35, 9, 2, '2026-05-17 08:16:35'),
(36, 9, 1, '2026-05-17 08:19:43');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_cart_item` (`user_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
