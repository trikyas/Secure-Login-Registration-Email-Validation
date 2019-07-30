-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Jul 30, 2019 at 07:33 AM
-- Server version: 5.6.38
-- PHP Version: 7.2.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `registration`
--

-- --------------------------------------------------------

--
-- Table structure for table `remember_me`
--

CREATE TABLE `remember_me` (
  `id` int(11) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `selector` varchar(255) NOT NULL,
  `expire_date` datetime NOT NULL,
  `is_expired` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `remember_me`
--
ALTER TABLE `remember_me`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `remember_me`
--
ALTER TABLE `remember_me`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
