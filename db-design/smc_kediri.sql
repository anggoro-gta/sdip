-- MySQL Script generated by MySQL Workbench
-- Sat Jan 12 09:47:12 2019
-- Model: smc_kediri    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema smc_kediri
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema smc_kediri
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `smc_kediri` DEFAULT CHARACTER SET utf8mb4 ;
USE `smc_kediri` ;

-- -----------------------------------------------------
-- Table `smc_kediri`.`smc_conf_options`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `smc_kediri`.`smc_conf_options` (
  `id` MEDIUMINT(6) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL DEFAULT '',
  `description` VARCHAR(255) NULL DEFAULT NULL,
  `value` MEDIUMTEXT NULL DEFAULT NULL,
  `level` TINYINT(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `option_name` USING BTREE (`name`, `level`))
ENGINE = InnoDB
AUTO_INCREMENT = 4
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_bin;


-- -----------------------------------------------------
-- Table `smc_kediri`.`smc_conf_role`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `smc_kediri`.`smc_conf_role` (
  `id_role` BIGINT(4) NOT NULL AUTO_INCREMENT,
  `keterangan` VARCHAR(100) NULL DEFAULT NULL,
  `is_aktif` TINYINT(20) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_role`),
  UNIQUE INDEX `unik` USING BTREE (`keterangan`, `is_aktif`))
ENGINE = InnoDB
AUTO_INCREMENT = 4
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_bin;


-- -----------------------------------------------------
-- Table `smc_kediri`.`smc_elemen`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `smc_kediri`.`smc_elemen` (
  `id_elemen` VARCHAR(50) NOT NULL,
  `keterangan` VARCHAR(200) NULL DEFAULT NULL,
  `singkat` CHAR(20) NULL DEFAULT NULL,
  `singkat_keterangan` CHAR(20) NULL DEFAULT NULL,
  `kategori` CHAR(20) NULL DEFAULT NULL,
  `urut` INT(11) NOT NULL DEFAULT '1',
  `status` CHAR(20) NOT NULL DEFAULT 'pilihan',
  `id_parent` VARCHAR(50) NOT NULL DEFAULT '0',
  `is_aktif` TINYINT(3) UNSIGNED NOT NULL DEFAULT '1',
  `has_unit_elemen` TINYINT(4) NOT NULL DEFAULT '0',
  `is_home` TINYINT(4) NOT NULL DEFAULT '0',
  `satuan` CHAR(20) NULL DEFAULT NULL,
  `warna` CHAR(20) NULL DEFAULT NULL,
  `is_total` TINYINT(4) NOT NULL DEFAULT '0',
  `created_date` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_elemen`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_bin;


-- -----------------------------------------------------
-- Table `smc_kediri`.`smc_user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `smc_kediri`.`smc_user` (
  `id_user` VARCHAR(20) NOT NULL,
  `id_role` BIGINT(4) NOT NULL,
  `id_unit_kerja` VARCHAR(20) NOT NULL,
  `nama` VARCHAR(100) NULL DEFAULT NULL,
  `user_name` CHAR(20) NULL DEFAULT NULL,
  `password` VARCHAR(60) NULL DEFAULT NULL,
  `no_ktp` CHAR(20) NULL DEFAULT NULL,
  `no_hp` CHAR(20) NULL DEFAULT NULL,
  `no_telp` CHAR(20) NULL DEFAULT NULL,
  `email` VARCHAR(30) NULL DEFAULT NULL,
  `alamat` TEXT NULL DEFAULT NULL,
  `is_aktif` TINYINT(3) UNSIGNED NULL DEFAULT NULL,
  `lat` VARCHAR(100) NULL DEFAULT NULL,
  `lon` VARCHAR(100) NULL DEFAULT NULL,
  `gcm_id` VARCHAR(300) NULL DEFAULT NULL,
  `imei` VARCHAR(300) NULL DEFAULT NULL,
  `mobile_id` VARCHAR(48) NULL DEFAULT NULL,
  `desktop_id` VARCHAR(48) NULL DEFAULT NULL,
  `foto` VARCHAR(100) NULL DEFAULT NULL,
  `created_date` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_user`),
  INDEX `smc_user_FKIndex1` USING BTREE (`id_unit_kerja`),
  INDEX `fk_smc_user_smc_conf_role1_idx` (`id_role` ASC),
  CONSTRAINT `fk_smc_user_smc_conf_role1`
    FOREIGN KEY (`id_role`)
    REFERENCES `smc_kediri`.`smc_conf_role` (`id_role`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_bin;


-- -----------------------------------------------------
-- Table `smc_kediri`.`smc_konten`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `smc_kediri`.`smc_konten` (
  `id_konten` CHAR(20) NOT NULL,
  `id_user` VARCHAR(20) NULL,
  `id_kategori` CHAR(20) NULL DEFAULT NULL,
  `judul` VARCHAR(300) NULL DEFAULT NULL,
  `kategori_konten` CHAR(20) NULL DEFAULT NULL,
  `id_parent` CHAR(20) NOT NULL DEFAULT '0',
  `keterangan` TEXT NULL DEFAULT NULL,
  `is_publish` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
  `img_thumb` VARCHAR(200) NULL DEFAULT NULL,
  `hit` INT(10) UNSIGNED NOT NULL DEFAULT '0',
  `video_loc` VARCHAR(200) NULL DEFAULT NULL,
  `video_url` VARCHAR(200) NULL DEFAULT NULL,
  `created_date` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_konten`),
  INDEX `kom_konten_FKIndex1` USING BTREE (`id_kategori`),
  INDEX `fk_smc_konten_smc_user1_idx` (`id_user` ASC),
  CONSTRAINT `fk_smc_konten_smc_user1`
    FOREIGN KEY (`id_user`)
    REFERENCES `smc_kediri`.`smc_user` (`id_user`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_bin;


-- -----------------------------------------------------
-- Table `smc_kediri`.`smc_unit`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `smc_kediri`.`smc_unit` (
  `id_unit` VARCHAR(50) NOT NULL,
  `nama` VARCHAR(100) NULL DEFAULT NULL,
  `singkat` CHAR(20) NULL DEFAULT NULL,
  `id_parent` VARCHAR(50) NULL DEFAULT NULL,
  `kategori` CHAR(20) NULL DEFAULT NULL,
  `lat` TEXT NULL DEFAULT NULL,
  `lon` TEXT NULL DEFAULT NULL,
  `koordinat` TEXT NULL DEFAULT NULL,
  `id_user` VARCHAR(50) NULL DEFAULT NULL,
  `created_date` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_unit`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_bin;


-- -----------------------------------------------------
-- Table `smc_kediri`.`smc_rekap`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `smc_kediri`.`smc_rekap` (
  `id_rekap` VARCHAR(50) NOT NULL,
  `id_unit` VARCHAR(50) NOT NULL,
  `id_elemen` VARCHAR(50) NOT NULL,
  `id_skpd` VARCHAR(50) NULL DEFAULT '0',
  `periode` INT(10) UNSIGNED NULL DEFAULT NULL,
  `jumlah` FLOAT NULL DEFAULT NULL,
  `satuan` CHAR(20) NULL DEFAULT NULL,
  `jenis_data` CHAR(20) NULL DEFAULT NULL,
  `id_user` VARCHAR(50) NULL DEFAULT NULL,
  `created_date` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `is_valid` TINYINT(3) UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id_rekap`),
  INDEX `fk_smc_rekap_smc_unit1_idx` (`id_unit` ASC),
  INDEX `fk_smc_rekap_smc_elemen1_idx` (`id_elemen` ASC),
  CONSTRAINT `fk_smc_rekap_smc_unit1`
    FOREIGN KEY (`id_unit`)
    REFERENCES `smc_kediri`.`smc_unit` (`id_unit`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_smc_rekap_smc_elemen1`
    FOREIGN KEY (`id_elemen`)
    REFERENCES `smc_kediri`.`smc_elemen` (`id_elemen`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_bin;


-- -----------------------------------------------------
-- Table `smc_kediri`.`smc_survey`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `smc_kediri`.`smc_survey` (
  `id_survey` VARCHAR(50) NOT NULL,
  `id_unit` VARCHAR(50) NOT NULL,
  `id_elemen` VARCHAR(50) NOT NULL,
  `id_user` VARCHAR(20) NULL,
  `id_desa` CHAR(20) NULL,
  `id_skpd` VARCHAR(50) NULL,
  `tgl_survey` DATETIME NULL DEFAULT NULL,
  `nama_surveyor` VARCHAR(100) NULL DEFAULT NULL,
  `nama_ppl` VARCHAR(100) NULL DEFAULT NULL,
  `nama_narasumber` VARCHAR(100) NULL DEFAULT NULL,
  `telp` VARCHAR(20) NULL DEFAULT NULL,
  `lat` TEXT NULL DEFAULT NULL,
  `lon` TEXT NULL DEFAULT NULL,
  `is_potensial` TINYINT(3) UNSIGNED NULL DEFAULT NULL,
  `catatan` TEXT NULL DEFAULT NULL,
  `created_date` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `is_valid` TINYINT(3) UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id_survey`),
  INDEX `fk_smc_survey_smc_unit_idx` (`id_unit` ASC),
  INDEX `fk_smc_survey_smc_user1_idx` (`id_user` ASC),
  INDEX `fk_smc_survey_smc_elemen1_idx` (`id_elemen` ASC),
  CONSTRAINT `fk_smc_survey_smc_unit`
    FOREIGN KEY (`id_unit`)
    REFERENCES `smc_kediri`.`smc_unit` (`id_unit`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_smc_survey_smc_user1`
    FOREIGN KEY (`id_user`)
    REFERENCES `smc_kediri`.`smc_user` (`id_user`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_smc_survey_smc_elemen1`
    FOREIGN KEY (`id_elemen`)
    REFERENCES `smc_kediri`.`smc_elemen` (`id_elemen`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_bin;


-- -----------------------------------------------------
-- Table `smc_kediri`.`smc_survey_detail`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `smc_kediri`.`smc_survey_detail` (
  `id_survey_detail` VARCHAR(50) NOT NULL,
  `id_survey` VARCHAR(50) NOT NULL,
  `id_user` VARCHAR(20) NOT NULL,
  `created_date` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_survey_detail`),
  INDEX `fk_smc_survey_detail_smc_survey1_idx` (`id_survey` ASC),
  INDEX `fk_smc_survey_detail_smc_user1_idx` (`id_user` ASC),
  CONSTRAINT `fk_smc_survey_detail_smc_survey1`
    FOREIGN KEY (`id_survey`)
    REFERENCES `smc_kediri`.`smc_survey` (`id_survey`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_smc_survey_detail_smc_user1`
    FOREIGN KEY (`id_user`)
    REFERENCES `smc_kediri`.`smc_user` (`id_user`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_bin;


-- -----------------------------------------------------
-- Table `smc_kediri`.`smc_field`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `smc_kediri`.`smc_field` (
  `id_field` VARCHAR(50) NOT NULL,
  `key_field` VARCHAR(200) NULL,
  `nama_field` VARCHAR(200) NULL,
  `id_parent` VARCHAR(50) NULL,
  `satuan` CHAR(20) NULL,
  `created_date` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_field`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_bin;


-- -----------------------------------------------------
-- Table `smc_kediri`.`smc_survey_detail_field_value`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `smc_kediri`.`smc_survey_detail_field_value` (
  `id_survey_detail_field` VARCHAR(50) NOT NULL,
  `id_survey_detail` VARCHAR(50) NOT NULL,
  `id_field` VARCHAR(50) NOT NULL,
  `value` VARCHAR(200) NULL,
  `created_date` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_survey_detail_field`),
  INDEX `fk_survey_detail_field_smc_survey_detail1_idx` (`id_survey_detail` ASC),
  INDEX `fk_survey_detail_field_value_smc_field1_idx` (`id_field` ASC),
  CONSTRAINT `fk_survey_detail_field_smc_survey_detail1`
    FOREIGN KEY (`id_survey_detail`)
    REFERENCES `smc_kediri`.`smc_survey_detail` (`id_survey_detail`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_survey_detail_field_value_smc_field1`
    FOREIGN KEY (`id_field`)
    REFERENCES `smc_kediri`.`smc_field` (`id_field`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_bin;


-- -----------------------------------------------------
-- Table `smc_kediri`.`smc_survey_field_value`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `smc_kediri`.`smc_survey_field_value` (
  `id_survey_field` VARCHAR(50) NOT NULL,
  `id_survey` VARCHAR(50) NOT NULL,
  `id_field` VARCHAR(50) NOT NULL,
  `value` VARCHAR(200) NULL,
  `created_date` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_survey_field`),
  INDEX `fk_survey_has_elemen_smc_survey1_idx` (`id_survey` ASC),
  INDEX `fk_survey_field_value_smc_field1_idx` (`id_field` ASC),
  CONSTRAINT `fk_survey_has_elemen_smc_survey1`
    FOREIGN KEY (`id_survey`)
    REFERENCES `smc_kediri`.`smc_survey` (`id_survey`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_survey_field_value_smc_field1`
    FOREIGN KEY (`id_field`)
    REFERENCES `smc_kediri`.`smc_field` (`id_field`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_bin;


-- -----------------------------------------------------
-- Table `smc_kediri`.`smc_cube_desa`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `smc_kediri`.`smc_cube_desa` (
  `cube_key` VARCHAR(64) NOT NULL,
  `periode` INT NULL,
  `id_kecamatan` VARCHAR(45) NULL,
  `id_desa` VARCHAR(45) NULL,
  `total` INT NULL,
  `created_date` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
