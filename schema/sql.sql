USE agricoop;

-- MySQL dump 10.13  Distrib 8.0.34, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: agricoop
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `cooperativa`
--

DROP TABLE IF EXISTS `cooperativa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cooperativa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) DEFAULT NULL,
  `endereco` varchar(150) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `cnpj` char(14) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `cnpj` (`cnpj`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cooperativa`
--

LOCK TABLES `cooperativa` WRITE;
/*!40000 ALTER TABLE `cooperativa` DISABLE KEYS */;
INSERT INTO `cooperativa` VALUES (10,'Agricolar','Caratinga - Mg','agricolar@gmail.com','$2y$10$tdnS/tKW.N98BW8awu5iJ.TwYADWILDFrYEoC7rrlX4e5hPOYB2.i','00045678913234'),(34,'Cooperação','Caratinga - Mg','cooperacao@gmail.com','$2y$10$b4pLEBv2fnz64cXmX.0O4.MRoJG/fZ9Sy.xnVK4UM87/6QrjgzLOG','63127456000182'),(36,'Agrotec','Rua Dos Cavaco - Caratinga','agrotec@gmail.com','$2y$10$jeKkx800QqNdhcJubBkxf.7/.uTzMih8lseVtoD/UDCwv56pLsPhG','12345678914000');
/*!40000 ALTER TABLE `cooperativa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lojas`
--

DROP TABLE IF EXISTS `lojas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lojas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(150) DEFAULT NULL,
  `endereco` varchar(150) NOT NULL,
  `descricao` text DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `cooperativa_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cooperativa_id` (`cooperativa_id`),
  CONSTRAINT `cooperativa_id` FOREIGN KEY (`cooperativa_id`) REFERENCES `cooperativa` (`id`) ON DELETE CASCADE,
  CONSTRAINT `lojas_ibfk_1` FOREIGN KEY (`cooperativa_id`) REFERENCES `cooperativa` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lojas`
--

LOCK TABLES `lojas` WRITE;
/*!40000 ALTER TABLE `lojas` DISABLE KEYS */;
INSERT INTO `lojas` VALUES (22,'Casa do mel','Olegário Maciel, 243 - Centro, Caratinga','Loja boa de vender mel.','assets/img/imagens/img_6850332d5497d1.50953569.png',10),(24,'Casa de carne frangolar','Cap. Paiva 187 - Ctga','Açougue','assets/img/imagens/img_685037d67d1be9.37691623.png',34),(25,'Casa do fazendeiro','Pedro Martins, 76 - Caratinga','Tudo em produtos agropecuários e agrícolas','assets/img/imagens/img_685035d2b961f5.26719347.png',34),(26,'Sacolão do serginho','Moacir De Matos, 206','De Hortifruti a mercearia, encontra-se de tudo neste lugar. Com bons preços e atendimento de primeira.','assets/img/imagens/img_68503722942485.99535409.png',10),(27,'Sacolão do sorriso','Operários, 16 Caratinga','De Hortifruti a mercearia muito top','assets/img/imagens/img_6850379cdb76d6.73359387.png',10),(28,'Armazem do queijo ctga','R. Lamartine, 160 - Esplanada, Caratinga','Loja de queijos e derivados','assets/img/imagens/img_685048870707b1.50661159.png',36);
/*!40000 ALTER TABLE `lojas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `produto_loja`
--

DROP TABLE IF EXISTS `produto_loja`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `produto_loja` (
  `produto_id` int(11) NOT NULL,
  `loja_id` int(11) NOT NULL,
  PRIMARY KEY (`produto_id`,`loja_id`),
  KEY `loja_id` (`loja_id`),
  CONSTRAINT `produto_loja_ibfk_1` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `produto_loja_ibfk_2` FOREIGN KEY (`loja_id`) REFERENCES `lojas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `produto_loja`
--

LOCK TABLES `produto_loja` WRITE;
/*!40000 ALTER TABLE `produto_loja` DISABLE KEYS */;
INSERT INTO `produto_loja` VALUES (26,26),(26,27),(27,26),(27,27),(28,25),(29,25),(30,24),(31,24),(32,28),(33,28),(34,28),(35,22),(36,26),(36,27),(37,25);
/*!40000 ALTER TABLE `produto_loja` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `produtor`
--

DROP TABLE IF EXISTS `produtor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `produtor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(25) NOT NULL,
  `sobrenome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefone` varchar(20) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `cpf` char(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `telefone` (`telefone`),
  UNIQUE KEY `cpf` (`cpf`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `produtor`
--

LOCK TABLES `produtor` WRITE;
/*!40000 ALTER TABLE `produtor` DISABLE KEYS */;
INSERT INTO `produtor` VALUES (41,'João','Carlitos','joao@gmail.com','1478523652','123123','14785236987'),(42,'José','Antonio','jose@gmail.com','3398745215','$2y$10$gd5LmElMsY3MMnVqBCbdBOGid1jpodr0tcTOK4BpabmlSKxpuYOx6','13258479632'),(43,'Maria','Da aparecida','maria@gmail.com','3155221144','$2y$10$XU.BRm8fNwrdHbnt527PNelI0csX5VgWdbSXPR233JnAbvbDBY2NK','85214796354'),(44,'Tião','Sebastian','tiao@teste.com','1654785410','$2y$10$AqlNGoCMSsErWNVMEMIaNeT2NlzziBHBrGo/C8jvLzvUeXWUEtyrS','14785236541');
/*!40000 ALTER TABLE `produtor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `produtos`
--

DROP TABLE IF EXISTS `produtos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `produtos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(150) NOT NULL,
  `categoria` varchar(20) NOT NULL CHECK (`categoria` in ('Grão','Laticínio','Hortaliça','Fruta','Café','Carne','Outros')),
  `medida` varchar(20) NOT NULL CHECK (`medida` in ('KG','Sacas','Litros')),
  `quantidade` decimal(10,2) NOT NULL,
  `preco` decimal(10,2) NOT NULL,
  `produtor_id` int(11) NOT NULL,
  `imagem` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `produtor_id` (`produtor_id`),
  CONSTRAINT `produtor_id` FOREIGN KEY (`produtor_id`) REFERENCES `produtor` (`id`) ON DELETE CASCADE,
  CONSTRAINT `produtos_ibfk_2` FOREIGN KEY (`produtor_id`) REFERENCES `produtor` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `produtos`
--

LOCK TABLES `produtos` WRITE;
/*!40000 ALTER TABLE `produtos` DISABLE KEYS */;
INSERT INTO `produtos` VALUES (26,'Banana','Fruta','KG',15.00,2.98,41,'assets/img/imagens/img_685044fee79ac9.73890712.jpg'),(27,'Coconut verde','Fruta','KG',3.00,5.60,41,'assets/img/imagens/img_68504525c3ebf2.49413364.jpg'),(28,'Feijão vermelho','Grão','Sacas',4.00,60.99,42,'assets/img/imagens/img_685045bd2e4762.41661798.jpeg'),(29,'Arroz','Grão','Sacas',5.00,49.99,42,'assets/img/imagens/img_6850461a1da7d0.17024118.jpg'),(30,'Contra-filé angus','Carne','KG',20.00,59.99,42,'assets/img/imagens/img_68504647a360a1.97052170.jpg'),(31,'Picanha','Carne','KG',10.00,70.00,42,'assets/img/imagens/img_68504662f1d0e6.20091121.jpg'),(32,'Requeijão de corte','Laticínio','KG',10.00,15.80,43,'assets/img/imagens/img_685048ab3ccc50.07785181.jpeg'),(33,'Leite','Laticínio','Litros',1.00,10.30,43,'assets/img/imagens/img_685048c682dde7.20339149.jpg'),(34,'Queijo minas frescal','Laticínio','KG',15.00,30.00,43,'assets/img/imagens/img_685048e30f9374.91573668.jpg'),(35,'Mel','Outros','Litros',20.00,10.00,43,'assets/img/imagens/img_6850491c5e80b8.32770830.png'),(36,'Mih verde','Hortaliça','Sacas',4.00,48.00,42,'assets/img/imagens/img_685072d36ed2c3.06887111.jpeg'),(37,'Cafézin','Café','Sacas',2.00,102.00,42,'assets/img/imagens/img_685074cf4a59b4.66353037.jpg');
/*!40000 ALTER TABLE `produtos` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-06-16 17:00:02
