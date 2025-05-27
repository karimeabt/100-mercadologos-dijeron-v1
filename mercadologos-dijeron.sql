-- MySQL Workbench Synchronization
-- Generated: 2024-06-22 00:44
-- Model: New Model
-- Version: 1.0
-- Project: Name of the project
-- Author: Karime

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

CREATE SCHEMA IF NOT EXISTS `mercadologos_dijeron` DEFAULT CHARACTER SET utf8 ;

CREATE TABLE IF NOT EXISTS `mercadologos_dijeron`.`ronda` (
  `idronda` INT(11) NOT NULL AUTO_INCREMENT,
  `descripcion_ronda` VARCHAR(350) NOT NULL,
  PRIMARY KEY (`idronda`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `mercadologos_dijeron`.`equipo` (
  `idequipo` INT(11) NOT NULL AUTO_INCREMENT,
  `nombre_equipo` VARCHAR(100) NOT NULL,
  `secuencia_equipo` INT(11) NOT NULL,
  PRIMARY KEY (`idequipo`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `mercadologos_dijeron`.`respuesta` (
  `idrespuesta` INT(11) NOT NULL AUTO_INCREMENT,
  `descripcion_respuesta` VARCHAR(100) NOT NULL,
  `popularidad_respuesta` INT(11) NOT NULL,
  `ronda_idronda` INT(11) NOT NULL,
  PRIMARY KEY (`idrespuesta`),
  INDEX `fk_respuesta_ronda_idx` (`ronda_idronda` ASC),
  CONSTRAINT `fk_respuesta_ronda`
    FOREIGN KEY (`ronda_idronda`)
    REFERENCES `mydb`.`ronda` (`idronda`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `mercadologos_dijeron`.`resultado` (
  `idresultado` INT(11) NOT NULL AUTO_INCREMENT,
  `score_resultado` INT(11) NOT NULL,
  `ronda_idronda` INT(11) NOT NULL,
  `equipo_idequipo` INT(11) NOT NULL,
  PRIMARY KEY (`idresultado`),
  INDEX `fk_resultado_ronda1_idx` (`ronda_idronda` ASC),
  INDEX `fk_resultado_equipo1_idx` (`equipo_idequipo` ASC),
  CONSTRAINT `fk_resultado_ronda1`
    FOREIGN KEY (`ronda_idronda`)
    REFERENCES `mydb`.`ronda` (`idronda`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_resultado_equipo1`
    FOREIGN KEY (`equipo_idequipo`)
    REFERENCES `mydb`.`equipo` (`idequipo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
