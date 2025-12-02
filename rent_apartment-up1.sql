-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 30 Nov 2025 pada 04.53
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rent_apartment`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `apartments`
--

CREATE TABLE `apartments` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `address` varchar(255) NOT NULL,
  `price_per_month` decimal(10,2) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `status` enum('available','rented') NOT NULL DEFAULT 'available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data untuk tabel `apartments`
--

INSERT INTO `apartments` (`id`, `name`, `description`, `image`, `address`, `price_per_month`, `image_url`, `status`) VALUES
(2, 'Green Fill', 'Pemandangan Indah. Apartment yang dikelilingi oleh taman serta kolam renang dihalaman belakang.', NULL, 'Jl. batu naga', 500000.00, '', 'rented'),
(4, 'Green Hill', '3 kaman, 0 kamar mandi', NULL, 'Jl. Naga Sakti', 50000.00, '', 'available'),
(6, 'Cozyy Desperate', '0 kamar', NULL, 'Naga Sakti', 25000.00, '', 'available');

-- --------------------------------------------------------

--
-- Struktur dari tabel `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `apartment_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('pending','confirmed','completed','cancelled') NOT NULL DEFAULT 'pending',
  `transaction_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data untuk tabel `transactions`
--

INSERT INTO `transactions` (`id`, `user_id`, `apartment_id`, `start_date`, `end_date`, `total_price`, `status`, `transaction_date`) VALUES
(1, 4, 2, '2025-11-29', '2025-11-30', 16666.67, 'cancelled', '2025-11-30 02:15:27'),
(2, 4, 2, '2025-11-30', '2025-12-01', 500000.00, 'completed', '2025-11-30 02:27:47'),
(3, 9, 4, '2025-11-30', '2026-01-30', 3050000.00, 'cancelled', '2025-11-30 03:26:23'),
(4, 9, 4, '2025-11-30', '2025-12-30', 1500000.00, 'completed', '2025-11-30 03:26:50'),
(5, 4, 4, '2025-11-30', '2025-12-30', 1500000.00, 'pending', '2025-11-30 03:30:38'),
(6, 4, 4, '2025-11-30', '2025-12-30', 50000.00, 'pending', '2025-11-30 03:45:55'),
(7, 4, 4, '2025-11-30', '2026-01-30', 100000.00, 'pending', '2025-11-30 03:46:14');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` enum('user','admin') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `full_name`, `created_at`, `role`) VALUES
(4, 'Fynnaly', '$2y$10$KvBDUOrJLgsD6yfkdxA8ROxUNCi.oerEJNYTBoijGOSt.XenIOco6', 'fynn@admin.id', 'Fynnaly', '2025-11-25 14:53:10', 'user'),
(8, 'admin', '$2y$10$3xXOqDvQJZ.dCF4DF1hd2unpgKXCQbPzNB.0wJJc0cDuKRlLAHQBK', 'admin@apartment.id', 'Admin', '2025-11-30 00:25:33', 'admin'),
(9, 'Arya', '$2y$10$g9nyi.bRi4/vbe4ftFJjguVYkDjn5e0l.4IZjbrMIRN6QemxikTE2', 'arya@gmail.com', 'Arya Tedja Arum', '2025-11-30 03:24:31', 'user');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `apartments`
--
ALTER TABLE `apartments`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `apartment_id` (`apartment_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username_unique` (`username`),
  ADD UNIQUE KEY `email_unique` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `apartments`
--
ALTER TABLE `apartments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`apartment_id`) REFERENCES `apartments` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
