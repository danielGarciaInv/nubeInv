-- --------------------------------------------------------

CREATE TABLE `archivo_cargado` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `ruta` varchar(270) DEFAULT NULL,
  `tamano` varchar(100) DEFAULT NULL,
  `fecha` varchar(12) DEFAULT NULL,
  `tipo` tinyint(1) DEFAULT NULL,
  `fileType` varchar(4) DEFAULT NULL,
  `id_categoria` int(11) NOT NULL,
  `id_carpeta` tinyint(1) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

CREATE TABLE `carpeta` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `ruta` varchar(255) NOT NULL,
  `id_categoria` tinyint(1) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

CREATE TABLE `categoria` (
  `id` int(11) NOT NULL,
  `descripcion` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

CREATE TABLE `rol` (
  `id` int(11) NOT NULL,
  `descr` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

CREATE TABLE `rol_ve_categoria` (
  `id_rol` int(11) NOT NULL,
  `id_cat` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

CREATE TABLE `usuario` (
  `idusuario` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `pwd` varchar(255) DEFAULT NULL,
  `correo` varchar(255) DEFAULT NULL,
  `id_rol` int(11) DEFAULT NULL,
  `caducidad` datetime DEFAULT NULL,
  `subir` tinyint(1) NOT NULL,
  `eliminar` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

CREATE TABLE `recuperar_pwd` (
  `id` int NOT NULL,
  `idusuario` int NOT NULL,
  `caducidad` varchar(22) NOT NULL
) ;

ALTER TABLE `archivo_cargado`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_categoria` (`id_categoria`);

ALTER TABLE `carpeta`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `rol`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `rol_ve_categoria`
  ADD PRIMARY KEY (`id_rol`,`id_cat`),
  ADD KEY `id_cat` (`id_cat`);

ALTER TABLE `usuario`
  ADD PRIMARY KEY (`idusuario`),
  ADD KEY `id_rol` (`id_rol`);

ALTER TABLE `recuperar_pwd`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `archivo_cargado`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `carpeta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `categoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `rol`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `usuario`
  MODIFY `idusuario` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `recuperar_pwd`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;
COMMIT;
