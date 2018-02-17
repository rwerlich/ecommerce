-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 17-Fev-2018 às 02:38
-- Versão do servidor: 10.1.26-MariaDB
-- PHP Version: 7.1.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_ecommerce`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_addresses`
--

CREATE TABLE `tb_addresses` (
  `idaddress` int(11) NOT NULL,
  `iduser` int(11) NOT NULL,
  `idcart` int(11) NOT NULL,
  `address` varchar(128) NOT NULL,
  `complement` varchar(32) DEFAULT NULL,
  `number` varchar(10) NOT NULL,
  `district` varchar(50) NOT NULL,
  `city` varchar(32) NOT NULL,
  `state` varchar(32) NOT NULL,
  `country` varchar(32) NOT NULL DEFAULT 'Brasil',
  `zipcode` char(8) NOT NULL,
  `dtregister` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `tb_addresses`
--

INSERT INTO `tb_addresses` (`idaddress`, `iduser`, `idcart`, `address`, `complement`, `number`, `district`, `city`, `state`, `country`, `zipcode`, `dtregister`) VALUES
(7, 1, 20, 'SC 407', '', '', 'Rio das Antas', 'Angelina', 'SC', 'Brasil', '88460000', '2018-02-17 01:01:33'),
(8, 1, 20, 'SC 407', '', '', 'Rio das Antas', 'Angelina', 'SC', 'Brasil', '88460000', '2018-02-17 01:02:13'),
(9, 1, 20, 'SC 407', '', '', 'Rio das Antas', 'Angelina', 'SC', 'Brasil', '88460000', '2018-02-17 01:04:01'),
(10, 1, 20, 'Rua Coletor Irineu Comelli', 'até 1750/1751', '', 'Centro', 'São José', 'SC', 'Brasil', '88103050', '2018-02-17 01:06:09'),
(11, 1, 20, 'Rua Coletor Irineu Comelli', 'até 1750/1751', '', 'Centro', 'São José', 'SC', 'Brasil', '88103050', '2018-02-17 01:07:29'),
(12, 1, 20, 'Rua Coletor Irineu Comelli', 'até 1750/1751', '', 'Centro', 'São José', 'SC', 'Brasil', '88103050', '2018-02-17 01:08:59'),
(13, 1, 20, 'Rua Coletor Irineu Comelli', 'até 1750/1751', '', 'Centro', 'São José', 'SC', 'Brasil', '88103050', '2018-02-17 01:10:25'),
(14, 1, 20, 'Rua Coletor Irineu Comelli', 'até 1750/1751', '', 'Centro', 'São José', 'SC', 'Brasil', '88103050', '2018-02-17 01:12:42'),
(15, 1, 20, 'Rua Coletor Irineu Comelli', 'até 1750/1751', '', 'Centro', 'São José', 'SC', 'Brasil', '88103050', '2018-02-17 01:14:33'),
(16, 1, 20, 'Rua Coletor Irineu Comelli', 'até 1750/1751', '', 'Centro', 'São José', 'SC', 'Brasil', '88103050', '2018-02-17 01:15:25'),
(17, 1, 20, 'Rua Coletor Irineu Comelli', 'até 1750/1751', '', 'Centro', 'São José', 'SC', 'Brasil', '88103050', '2018-02-17 01:15:45'),
(18, 1, 20, 'Rua Coletor Irineu Comelli', 'até 1750/1751', '', 'Centro', 'São José', 'SC', 'Brasil', '88103050', '2018-02-17 01:28:32');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_carts`
--

CREATE TABLE `tb_carts` (
  `idcart` int(11) NOT NULL,
  `sessionid` varchar(64) NOT NULL,
  `iduser` int(11) DEFAULT NULL,
  `zipcode` char(8) DEFAULT NULL,
  `vlfreight` decimal(10,2) DEFAULT '0.00',
  `nrdays` int(11) DEFAULT NULL,
  `dtregister` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `vltotal` float NOT NULL,
  `vlsubtotal` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `tb_carts`
--

INSERT INTO `tb_carts` (`idcart`, `sessionid`, `iduser`, `zipcode`, `vlfreight`, `nrdays`, `dtregister`, `vltotal`, `vlsubtotal`) VALUES
(7, 'fcgtsv4gc7g1etbkeo1ek5lsr1', NULL, NULL, NULL, NULL, '2018-01-29 19:01:10', 0, 0),
(8, 'dshqbnd9bi8f2cuj2jfdt2eidl', NULL, NULL, NULL, NULL, '2018-01-29 19:01:31', 0, 0),
(9, 'glncfji2j6bcheervnd8m5q8ce', 1, NULL, NULL, NULL, '2018-01-29 19:02:00', 0, 0),
(10, '90trm89loo6thbq25v7dsv4ba6', NULL, NULL, NULL, NULL, '2018-01-30 15:48:27', 0, 0),
(11, 'ig5r0retepbqv1fjru7ih11jvs', NULL, '88460000', '40.75', 2, '2018-01-31 17:05:30', 1400.55, 1359.8),
(12, 'ru75pocankmkjl5k9r09tqs8ns', NULL, '88460000', '58.87', 2, '2018-01-31 18:49:04', 2626.55, 2567.68),
(13, '9fopt71nn962mptloq5aa38lta', NULL, NULL, '0.00', NULL, '2018-02-06 18:45:10', 0, 0),
(14, 'r4cgrictjjdj0oj30jjjv74atf', 1, NULL, '0.00', NULL, '2018-02-06 19:42:25', 0, 0),
(15, 'amaiv31ukkp7prv3rg3d5eptt4', 1, NULL, '0.00', NULL, '2018-02-07 19:01:38', 0, 0),
(16, 'vvd0bjh30o26isi6f7qv9pp4lv', 1, NULL, '0.00', NULL, '2018-02-08 19:25:57', 0, 0),
(17, 'u1eq3m5miou19jinm5idvppofq', NULL, NULL, '0.00', NULL, '2018-02-15 16:36:34', 0, 0),
(18, 'k9r7dhsjo2dr574qqg483msna8', 1, '88460000', '90.18', 2, '2018-02-15 17:46:10', 4672.41, 4582.23),
(19, 'smm5e3k26gvenbh6u41k022l4i', NULL, '88103050', '37.59', 1, '2018-02-16 11:14:56', 1186.59, 1149),
(20, '6nori1jcp7j8s815nnhnir8udl', 1, '88103050', '30.55', 1, '2018-02-17 00:17:09', 710.45, 679.9);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_cartsproducts`
--

CREATE TABLE `tb_cartsproducts` (
  `idcartproduct` int(11) NOT NULL,
  `idcart` int(11) NOT NULL,
  `idproduct` int(11) NOT NULL,
  `dtremoved` datetime NOT NULL,
  `dtregister` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `tb_cartsproducts`
--

INSERT INTO `tb_cartsproducts` (`idcartproduct`, `idcart`, `idproduct`, `dtremoved`, `dtregister`) VALUES
(1, 10, 10, '2018-01-30 16:10:51', '2018-01-30 17:07:17'),
(2, 10, 10, '2018-01-30 16:10:51', '2018-01-30 17:12:47'),
(3, 10, 10, '2018-01-30 16:10:51', '2018-01-30 17:13:04'),
(4, 10, 10, '2018-01-30 16:10:51', '2018-01-30 17:13:05'),
(5, 10, 10, '2018-01-30 16:10:51', '2018-01-30 17:14:04'),
(6, 10, 10, '2018-01-30 16:10:51', '2018-01-30 17:14:37'),
(7, 10, 10, '2018-01-30 16:10:51', '2018-01-30 17:14:37'),
(8, 10, 10, '2018-01-30 16:10:51', '2018-01-30 17:18:29'),
(9, 10, 10, '2018-01-30 16:10:51', '2018-01-30 17:18:29'),
(10, 10, 10, '2018-01-30 16:10:51', '2018-01-30 17:18:29'),
(11, 10, 10, '2018-01-30 16:10:51', '2018-01-30 17:18:29'),
(12, 10, 10, '2018-01-30 16:10:51', '2018-01-30 17:18:29'),
(13, 10, 9, '0000-00-00 00:00:00', '2018-01-30 17:18:48'),
(14, 10, 9, '0000-00-00 00:00:00', '2018-01-30 18:10:45'),
(15, 10, 9, '0000-00-00 00:00:00', '2018-01-30 18:10:46'),
(16, 11, 10, '2018-01-31 16:19:49', '2018-01-31 17:05:35'),
(17, 11, 8, '2018-01-31 16:20:00', '2018-01-31 17:05:40'),
(18, 11, 8, '2018-01-31 16:20:00', '2018-01-31 17:05:42'),
(19, 11, 8, '2018-01-31 16:20:00', '2018-01-31 18:18:47'),
(20, 11, 10, '2018-01-31 16:35:16', '2018-01-31 18:19:18'),
(21, 11, 10, '2018-01-31 16:35:58', '2018-01-31 18:23:58'),
(22, 11, 10, '2018-01-31 16:48:52', '2018-01-31 18:35:42'),
(23, 11, 10, '0000-00-00 00:00:00', '2018-01-31 18:48:08'),
(24, 11, 10, '0000-00-00 00:00:00', '2018-01-31 18:48:41'),
(25, 12, 10, '0000-00-00 00:00:00', '2018-01-31 18:49:11'),
(26, 12, 7, '0000-00-00 00:00:00', '2018-01-31 18:49:22'),
(27, 18, 9, '0000-00-00 00:00:00', '2018-02-15 18:28:14'),
(28, 18, 9, '0000-00-00 00:00:00', '2018-02-15 18:51:55'),
(29, 18, 9, '0000-00-00 00:00:00', '2018-02-15 18:51:56'),
(30, 18, 6, '0000-00-00 00:00:00', '2018-02-15 18:52:06'),
(31, 19, 9, '0000-00-00 00:00:00', '2018-02-16 11:24:09'),
(32, 20, 10, '0000-00-00 00:00:00', '2018-02-17 00:34:19');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_categories`
--

CREATE TABLE `tb_categories` (
  `idcategory` int(11) NOT NULL,
  `category` varchar(32) NOT NULL,
  `dtregister` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `tb_categories`
--

INSERT INTO `tb_categories` (`idcategory`, `category`, `dtregister`) VALUES
(1, 'Smartphone', '2018-01-20 02:09:19'),
(2, 'Android', '2018-01-20 02:24:34'),
(3, 'IOS', '2018-01-20 02:24:41');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_orders`
--

CREATE TABLE `tb_orders` (
  `idorder` int(11) NOT NULL,
  `idcart` int(11) NOT NULL,
  `iduser` int(11) NOT NULL,
  `idstatus` int(11) NOT NULL DEFAULT '1',
  `vltotal` decimal(10,2) NOT NULL,
  `dtregister` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `idaddress` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `tb_orders`
--

INSERT INTO `tb_orders` (`idorder`, `idcart`, `iduser`, `idstatus`, `vltotal`, `dtregister`, `idaddress`) VALUES
(6, 20, 1, 1, '710.45', '2018-02-17 01:06:09', 0),
(7, 20, 1, 1, '710.45', '2018-02-17 01:07:30', 0),
(8, 20, 1, 1, '710.45', '2018-02-17 01:09:00', 0),
(9, 20, 1, 1, '710.45', '2018-02-17 01:10:25', 7),
(10, 20, 1, 1, '710.45', '2018-02-17 01:12:42', 0),
(11, 20, 1, 1, '710.45', '2018-02-17 01:14:33', 0),
(12, 20, 1, 1, '710.45', '2018-02-17 01:15:25', 0),
(13, 20, 1, 1, '710.45', '2018-02-17 01:15:45', 17),
(14, 20, 1, 1, '710.45', '2018-02-17 01:28:32', 18);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_ordersstatus`
--

CREATE TABLE `tb_ordersstatus` (
  `idstatus` int(11) NOT NULL,
  `status` varchar(32) NOT NULL,
  `dtregister` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `tb_ordersstatus`
--

INSERT INTO `tb_ordersstatus` (`idstatus`, `status`, `dtregister`) VALUES
(1, 'Em Aberto', '2017-03-13 06:00:00'),
(2, 'Aguardando Pagamento', '2017-03-13 06:00:00'),
(3, 'Pago', '2017-03-13 06:00:00'),
(4, 'Entregue', '2017-03-13 06:00:00');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_products`
--

CREATE TABLE `tb_products` (
  `idproduct` int(11) NOT NULL,
  `product` varchar(64) NOT NULL,
  `vlprice` decimal(10,2) NOT NULL,
  `vlwidth` decimal(10,2) NOT NULL,
  `vlheight` decimal(10,2) NOT NULL,
  `vllength` decimal(10,2) NOT NULL,
  `vlweight` decimal(10,2) NOT NULL,
  `url` varchar(128) NOT NULL,
  `dtregister` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `imgproduct` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `tb_products`
--

INSERT INTO `tb_products` (`idproduct`, `product`, `vlprice`, `vlwidth`, `vlheight`, `vllength`, `vlweight`, `url`, `dtregister`, `imgproduct`) VALUES
(6, 'Smartphone Motorola Moto G5 Plus', '1135.23', '15.20', '7.40', '0.70', '0.16', 'smartphone-motorola-moto-g5-plus', '2018-01-20 00:53:05', '1516409761.jpg'),
(7, 'Smartphone Moto Z Play', '1887.78', '14.10', '0.90', '1.16', '0.13', 'smartphone-moto-z-play', '2018-01-20 00:53:05', '1516409746.jpg'),
(8, 'Smartphone Samsung Galaxy J5 Pro', '1299.00', '14.60', '7.10', '0.80', '0.16', 'smartphone-samsung-galaxy-j5', '2018-01-20 00:53:05', '1516409734.jpg'),
(9, 'Smartphone Samsung Galaxy J7 Prime', '1149.00', '15.10', '7.50', '0.80', '0.16', 'smartphone-samsung-galaxy-j7', '2018-01-20 00:53:05', '1516409727.jpg'),
(10, 'Smartphone Samsung Galaxy J3 Dual', '679.90', '14.20', '7.10', '0.70', '0.14', 'smartphone-samsung-galaxy-j3', '2018-01-20 00:53:05', '1516409713.jpg');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_productscategories`
--

CREATE TABLE `tb_productscategories` (
  `idcategory` int(11) NOT NULL,
  `idproduct` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `tb_productscategories`
--

INSERT INTO `tb_productscategories` (`idcategory`, `idproduct`) VALUES
(2, 6),
(2, 7),
(3, 9),
(3, 10),
(3, 6),
(3, 7),
(3, 8);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_users`
--

CREATE TABLE `tb_users` (
  `iduser` int(11) NOT NULL,
  `login` varchar(64) NOT NULL,
  `password` varchar(256) NOT NULL,
  `admin` tinyint(4) NOT NULL DEFAULT '0',
  `nome` varchar(64) NOT NULL,
  `email` varchar(128) DEFAULT NULL,
  `phone` bigint(20) DEFAULT NULL,
  `dtregister` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `tb_users`
--

INSERT INTO `tb_users` (`iduser`, `login`, `password`, `admin`, `nome`, `email`, `phone`, `dtregister`) VALUES
(1, 'rodrigo.werlich@gmail.com', '21232f297a57a5a743894a0e4a801fc3', 0, 'admin', 'rodrigo.werlich@gmail.com', 123456, '2018-01-20 00:00:21'),
(2, 'teste123@teste123.com', 'e10adc3949ba59abbe56e057f20f883e', 0, 'teste', 'teste123@teste123.com', 0, '2018-02-07 19:40:04');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_userslogs`
--

CREATE TABLE `tb_userslogs` (
  `idlog` int(11) NOT NULL,
  `iduser` int(11) NOT NULL,
  `log` varchar(128) NOT NULL,
  `ip` varchar(45) NOT NULL,
  `useragent` varchar(128) NOT NULL,
  `sessionid` varchar(64) NOT NULL,
  `url` varchar(128) NOT NULL,
  `dtregister` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_userspasswordsrecoveries`
--

CREATE TABLE `tb_userspasswordsrecoveries` (
  `idrecovery` int(11) NOT NULL,
  `iduser` int(11) NOT NULL,
  `desip` varchar(45) NOT NULL,
  `dtrecovery` datetime DEFAULT NULL,
  `dtregister` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `token` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `tb_userspasswordsrecoveries`
--

INSERT INTO `tb_userspasswordsrecoveries` (`idrecovery`, `iduser`, `desip`, `dtrecovery`, `dtregister`, `token`) VALUES
(13, 1, '::1', '2018-02-08 17:25:54', '2018-02-08 19:21:17', 'hjsv8l8iynEHdgVpB9GKYTFEmRX7XCsU');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_addresses`
--
ALTER TABLE `tb_addresses`
  ADD PRIMARY KEY (`idaddress`);

--
-- Indexes for table `tb_carts`
--
ALTER TABLE `tb_carts`
  ADD PRIMARY KEY (`idcart`),
  ADD KEY `FK_carts_users_idx` (`iduser`);

--
-- Indexes for table `tb_cartsproducts`
--
ALTER TABLE `tb_cartsproducts`
  ADD PRIMARY KEY (`idcartproduct`),
  ADD KEY `FK_cartsproducts_carts_idx` (`idcart`),
  ADD KEY `FK_cartsproducts_products_idx` (`idproduct`);

--
-- Indexes for table `tb_categories`
--
ALTER TABLE `tb_categories`
  ADD PRIMARY KEY (`idcategory`);

--
-- Indexes for table `tb_orders`
--
ALTER TABLE `tb_orders`
  ADD PRIMARY KEY (`idorder`),
  ADD KEY `FK_orders_carts_idx` (`idcart`),
  ADD KEY `FK_orders_users_idx` (`iduser`),
  ADD KEY `fk_orders_ordersstatus_idx` (`idstatus`);

--
-- Indexes for table `tb_ordersstatus`
--
ALTER TABLE `tb_ordersstatus`
  ADD PRIMARY KEY (`idstatus`);

--
-- Indexes for table `tb_products`
--
ALTER TABLE `tb_products`
  ADD PRIMARY KEY (`idproduct`);

--
-- Indexes for table `tb_users`
--
ALTER TABLE `tb_users`
  ADD PRIMARY KEY (`iduser`);

--
-- Indexes for table `tb_userslogs`
--
ALTER TABLE `tb_userslogs`
  ADD PRIMARY KEY (`idlog`);

--
-- Indexes for table `tb_userspasswordsrecoveries`
--
ALTER TABLE `tb_userspasswordsrecoveries`
  ADD PRIMARY KEY (`idrecovery`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_addresses`
--
ALTER TABLE `tb_addresses`
  MODIFY `idaddress` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `tb_carts`
--
ALTER TABLE `tb_carts`
  MODIFY `idcart` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT for table `tb_cartsproducts`
--
ALTER TABLE `tb_cartsproducts`
  MODIFY `idcartproduct` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
--
-- AUTO_INCREMENT for table `tb_categories`
--
ALTER TABLE `tb_categories`
  MODIFY `idcategory` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `tb_orders`
--
ALTER TABLE `tb_orders`
  MODIFY `idorder` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `tb_ordersstatus`
--
ALTER TABLE `tb_ordersstatus`
  MODIFY `idstatus` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `tb_products`
--
ALTER TABLE `tb_products`
  MODIFY `idproduct` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `tb_users`
--
ALTER TABLE `tb_users`
  MODIFY `iduser` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `tb_userslogs`
--
ALTER TABLE `tb_userslogs`
  MODIFY `idlog` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tb_userspasswordsrecoveries`
--
ALTER TABLE `tb_userspasswordsrecoveries`
  MODIFY `idrecovery` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `tb_carts`
--
ALTER TABLE `tb_carts`
  ADD CONSTRAINT `fk_carts_users` FOREIGN KEY (`iduser`) REFERENCES `tb_users` (`iduser`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
