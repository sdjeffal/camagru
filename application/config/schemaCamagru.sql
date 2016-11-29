-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema camagru
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `camagru` ;

-- -----------------------------------------------------
-- Schema camagru
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `camagru` DEFAULT CHARACTER SET utf8 ;
USE `camagru` ;

-- -----------------------------------------------------
-- Table `camagru`.`users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `camagru`.`users` ;

CREATE TABLE IF NOT EXISTS `camagru`.`users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(64) CHARACTER SET 'utf8' NOT NULL,
  `email` VARCHAR(255) CHARACTER SET 'utf8' NOT NULL,
  `password` VARCHAR(128) CHARACTER SET 'utf8' NOT NULL,
  `key` VARCHAR(128) CHARACTER SET 'utf8' NOT NULL,
  `create_time` DATETIME NOT NULL DEFAULT NOW(),
  `is_active` TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `index_username` (`username`(64) ASC),
  UNIQUE INDEX `index_email` (`email`(255) ASC));


-- -----------------------------------------------------
-- Table `camagru`.`galleries`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `camagru`.`galleries` ;

CREATE TABLE IF NOT EXISTS `camagru`.`galleries` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(45) NOT NULL,
  `url` MEDIUMTEXT CHARACTER SET 'utf8' NOT NULL,
  `users_id` INT UNSIGNED NOT NULL,
  `create_time` DATETIME NOT NULL DEFAULT NOW(),
  PRIMARY KEY (`id`),
  INDEX `fk_galleries_users_idx` (`users_id` ASC),
  CONSTRAINT `fk_galleries_users`
    FOREIGN KEY (`users_id`)
    REFERENCES `camagru`.`users` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `camagru`.`comments`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `camagru`.`comments` ;

CREATE TABLE IF NOT EXISTS `camagru`.`comments` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `message` LONGTEXT NOT NULL,
  `create_time` DATETIME NOT NULL DEFAULT NOW(),
  `users_id` INT UNSIGNED NOT NULL,
  `galleries_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_comments_users_idx` (`users_id` ASC),
  INDEX `fk_comments_galleries_idx` (`galleries_id` ASC),
  CONSTRAINT `fk_comments_users`
    FOREIGN KEY (`users_id`)
    REFERENCES `camagru`.`users` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_comments_galleries`
    FOREIGN KEY (`galleries_id`)
    REFERENCES `camagru`.`galleries` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `camagru`.`likes`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `camagru`.`likes` ;

CREATE TABLE IF NOT EXISTS `camagru`.`likes` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `users_id` INT UNSIGNED NOT NULL,
  `galleries_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_likes_users_idx` (`users_id` ASC),
  INDEX `fk_likes_galleries_idx` (`galleries_id` ASC),
  CONSTRAINT `fk_likes_users1`
    FOREIGN KEY (`users_id`)
    REFERENCES `camagru`.`users` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_likes_galleries1`
    FOREIGN KEY (`galleries_id`)
    REFERENCES `camagru`.`galleries` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
