-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 25 Jan 2025 pada 14.22
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lucart`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `product`
--

CREATE TABLE `product` (
  `id_product` int(11) NOT NULL,
  `name_product` varchar(100) NOT NULL,
  `stok_product` int(11) NOT NULL,
  `harga_product` int(11) NOT NULL,
  `deskripsi_product` text NOT NULL,
  `kategori_product` varchar(100) NOT NULL,
  `gambar_product` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `product`
--

INSERT INTO `product` (`id_product`, `name_product`, `stok_product`, `harga_product`, `deskripsi_product`, `kategori_product`, `gambar_product`) VALUES
(1, 'Anime One Piece Zoro', 4, 75000, 'Kaos Lucart dibuat dari cotton combed 30s 100% cotton, menjamin kelembutan dan kenyamanan maksimal. Kami menggunakan teknologi sablon DFT dengan tinta premium untuk hasil yang halus dan ringan.', 'T-shirt Kartun', 'zoroo.jpg'),
(2, 'Custom Design Anime', 5, 75000, 'Kaos Lucart dibuat dari cotton combed 30s 100% cotton, menjamin kelembutan dan kenyamanan maksimal. Kami menggunakan teknologi sablon DFT dengan tinta premium untuk hasil yang halus dan ringan.', 'T-shirt Kartun', 'animeee.jpg'),
(3, 'Real Madrid European', 2, 75000, 'Kaos Lucart dibuat dari cotton combed 30s 100% cotton, menjamin kelembutan dan kenyamanan maksimal. Kami menggunakan teknologi sablon DFT dengan tinta premium untuk hasil yang halus dan ringan.', 'T-shirt Club', 'euro.jpg'),
(4, 'Custom Design Persib', 3, 75000, 'Kaos Lucart dibuat dari cotton combed 30s 100% cotton, menjamin kelembutan dan kenyamanan maksimal. Kami menggunakan teknologi sablon DFT dengan tinta premium untuk hasil yang halus dan ringan.', 'T-shirt Custom', 'persib.jpg'),
(5, 'Anime One Piece Nakama', 4, 75000, 'Kaos Lucart dibuat dari cotton combed 30s 100% cotton, menjamin kelembutan dan kenyamanan maksimal. Kami menggunakan teknologi sablon DFT dengan tinta premium untuk hasil yang halus dan ringan.', 'T-shirt Kartun', 'nakamaa.jpg'),
(6, 'Custom Design Monkey', 2, 75000, 'Kaos Lucart dibuat dari cotton combed 30s 100% cotton, menjamin kelembutan dan kenyamanan maksimal. Kami menggunakan teknologi sablon DFT dengan tinta premium untuk hasil yang halus dan ringan.', 'T-shirt Custom', 'mokeyy.jpg'),
(7, 'Real Madrid Champions', 5, 75000, 'Kaos Lucart dibuat dari cotton combed 30s 100% cotton, menjamin kelembutan dan kenyamanan maksimal. Kami menggunakan teknologi sablon DFT dengan tinta premium untuk hasil yang halus dan ringan.', 'T-shirt Club', 'madrid.jpg'),
(8, 'Travel with my family', 3, 75000, 'Kaos Lucart dibuat dari cotton combed 30s 100% cotton, menjamin kelembutan dan kenyamanan maksimal. Kami menggunakan teknologi sablon DFT dengan tinta premium untuk hasil yang halus dan ringan.', 'T-shirt Custom', 'family.jpg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tq`
--

CREATE TABLE `tq` (
  `id_tq` int(11) NOT NULL,
  `pesan` text NOT NULL,
  `jenis` enum('testimoni','question') NOT NULL,
  `pekerjaan` varchar(100) NOT NULL,
  `date` date NOT NULL,
  `rating` tinyint(4) NOT NULL,
  `status` int(11) NOT NULL,
  `id_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tq`
--

INSERT INTO `tq` (`id_tq`, `pesan`, `jenis`, `pekerjaan`, `date`, `rating`, `status`, `id_user`) VALUES
(1, 'Nyoba custom design sendiri disini ternyata kualitas produknya baguss, berfungsi dengan cukup baik juga, respon penjual cukup responsif, suka dengan produknya, semoga bisa bertahan lama', 'testimoni', 'Siswa SMA 1', '2025-01-25', 5, 1, 5),
(2, 'ready ga? ready', 'question', 'Siswa SMA 1', '2025-01-25', 0, 0, 5),
(3, 'bahannya adem, pas dibadan, bestt banget la pokoknyaa', 'testimoni', 'Wirausaha', '2025-01-25', 4, 0, 6),
(4, 'bisa tranfer ga?', 'question', 'Wirausaha', '2025-01-25', 0, 0, 6),
(5, 'Bagus banget sumpah sablonannya juga baguss bangt ga ngecewain cocok banget nih buat kado, bakal langganan nih, respon ownernya juga ramah dan pengirimann cepett, thankyou yah ', 'testimoni', 'Siswi SMK 1', '2025-01-25', 5, 1, 7),
(6, ' The best banget si ini kaos nya bahannya yahud gila parah lembut nyaman banget dipakai nya sablon nya rapih, design nya gokils. Recommended banget buat dipakai segala medan tempur. Good seller ', 'testimoni', 'Mahasiswi UGM', '2025-01-25', 5, 1, 8),
(7, ' Respon adminnya gercep + ramah bet dah. Sesuai ekspektasi design nya, bahan nya juga adem poll worth it banget buat harga segitu mah', 'testimoni', 'Siswi SMK 2', '2025-01-25', 4, 1, 9);

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `role` enum('pegawai','admin','customer') NOT NULL,
  `status` int(11) NOT NULL,
  `foto` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id_user`, `email`, `username`, `password`, `role`, `status`, `foto`) VALUES
(1, 'lipi@gmail.com', 'admin', '111', 'admin', 1, ''),
(2, 'uan@gmail.com', 'uan', 'u222', 'pegawai', 1, ''),
(3, 'puput@gmail.com', 'puput', 'p888', 'pegawai', 1, ''),
(4, 'awan@gmail.com', 'awan', 'a555', 'pegawai', 0, ''),
(5, 'zendra@gmail.com', 'Zendra Atmaja', 'ca421c0b6d0cbf737764f4c1956feed2', 'customer', 0, 'image/testienam.jpg'),
(6, 'fathir@gmail.com', 'Fathir Al Fariz', 'a2688c5f34c7483dc5b73b09c6575baf', 'customer', 0, 'image/testidua.jpg'),
(7, 'beby@gmail.com', 'Beby Syafira', '5ae09ec268f4634ef4882f5cda10308e', 'customer', 0, 'image/testisatu.jpg'),
(8, 'geral@gmail.com', 'Gerald Adinata', 'a99d070dafb478f31b21924b5e26e67d', 'customer', 0, 'image/testiempat.jpg'),
(9, 'khalila@gmail.com', 'Khalila', '2e2992392d2cdcd574094028cb6ad7b5', 'customer', 0, 'image/testilima.jpg');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id_product`);

--
-- Indeks untuk tabel `tq`
--
ALTER TABLE `tq`
  ADD PRIMARY KEY (`id_tq`),
  ADD KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `product`
--
ALTER TABLE `product`
  MODIFY `id_product` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `tq`
--
ALTER TABLE `tq`
  MODIFY `id_tq` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `tq`
--
ALTER TABLE `tq`
  ADD CONSTRAINT `tq_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
