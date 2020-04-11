-- MySQL Script generated by MySQL Workbench
-- Fri Apr 10 12:24:34 2020
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

-- -----------------------------------------------------
-- Schema it360_weekend_tracker
-- -----------------------------------------------------

USE it360_weekend_tracker ;

-- -----------------------------------------------------
-- Table it360_weekend_tracker.midshipmen
-- -----------------------------------------------------
DROP TABLE IF EXISTS it360_weekend_tracker.midshipmen ;

CREATE TABLE IF NOT EXISTS it360_weekend_tracker.midshipmen (
  alpha INT NOT NULL,
  firstname VARCHAR(16) NOT NULL,
  lastname VARCHAR(16) NOT NULL,
  password VARCHAR(45) NOT NULL,
  CONSTRAINT PK_midshipmen_alpha PRIMARY KEY (alpha));


-- -----------------------------------------------------
-- Table it360_weekend_tracker.weekends_left
-- -----------------------------------------------------
DROP TABLE IF EXISTS it360_weekend_tracker.weekends_left ;

CREATE TABLE IF NOT EXISTS it360_weekend_tracker.weekends_left (
  alpha INT NOT NULL,
  weekends_left INT NOT NULL DEFAULT 0,
  CONSTRAINT PK_weekends_left_alpha PRIMARY KEY (alpha),
  CONSTRAINT FK_weekendsleft_midshipmen FOREIGN KEY (alpha)
    REFERENCES it360_weekend_tracker.midshipmen (alpha)
    ON DELETE CASCADE
    ON UPDATE NO ACTION);


-- -----------------------------------------------------
-- Table it360_weekend_tracker.incentives_available
-- -----------------------------------------------------
DROP TABLE IF EXISTS it360_weekend_tracker.incentives_available ;

CREATE TABLE IF NOT EXISTS it360_weekend_tracker.incentives_available (
  incentive_id INT NOT NULL AUTO_INCREMENT,
  incentives_available VARCHAR(45) NOT NULL,
  rewarddescrip VARCHAR(45) NOT NULL,
  CONSTRAINT PK_incentives_available_incentive_id PRIMARY KEY (incentive_id),
  CONSTRAINT AK_incentives_available_incentives_available UNIQUE (incentives_available));


-- -----------------------------------------------------
-- Table it360_weekend_tracker.incentives
-- -----------------------------------------------------
DROP TABLE IF EXISTS it360_weekend_tracker.incentives ;

CREATE TABLE IF NOT EXISTS it360_weekend_tracker.incentives (
  alpha INT NOT NULL,
  incentive_id INT NOT NULL,
  CONSTRAINT PK_incentives PRIMARY KEY (alpha, incentive_id),
  CONSTRAINT FK_incentives_midshipmen FOREIGN KEY (alpha)
    REFERENCES it360_weekend_tracker.midshipmen (alpha)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT FK_incentives_incentivesavail FOREIGN KEY (incentive_id)
    REFERENCES it360_weekend_tracker.incentives_available (incentive_id)
    ON DELETE CASCADE
    ON UPDATE CASCADE);


-- -----------------------------------------------------
-- Table it360_weekend_tracker.Company
-- -----------------------------------------------------
DROP TABLE IF EXISTS it360_weekend_tracker.Company ;

CREATE TABLE IF NOT EXISTS it360_weekend_tracker.Company (
  alpha INT NOT NULL,
  Company INT NOT NULL,
  CONSTRAINT PK_Company PRIMARY KEY (alpha),
  CONSTRAINT FK_Company_midshipmen FOREIGN KEY (alpha)
    REFERENCES it360_weekend_tracker.midshipmen (alpha)
    ON DELETE CASCADE
    ON UPDATE CASCADE);


-- -----------------------------------------------------
-- Table it360_weekend_tracker.weekendplans
-- -----------------------------------------------------
DROP TABLE IF EXISTS it360_weekend_tracker.weekendplans ;

CREATE TABLE IF NOT EXISTS it360_weekend_tracker.weekendplans (
  wID INT NOT NULL AUTO_INCREMENT,
  address VARCHAR(100) NOT NULL,
  description VARCHAR(200) NOT NULL,
  CONSTRAINT PK_weekendplans PRIMARY KEY (wID));


-- -----------------------------------------------------
-- Table it360_weekend_tracker.midweekend
-- -----------------------------------------------------
DROP TABLE IF EXISTS it360_weekend_tracker.midweekend ;

CREATE TABLE IF NOT EXISTS it360_weekend_tracker.midweekend (
  wID INT NOT NULL,
  alpha INT NOT NULL,
  buddyname VARCHAR(45) NOT NULL,
  buddyphone VARCHAR(10) NOT NULL,
  CONSTRAINT PK_midweekend PRIMARY KEY (wID, alpha),
  CONSTRAINT FK_midweekend_midshipmen FOREIGN KEY (alpha)
    REFERENCES it360_weekend_tracker.midshipmen (alpha)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT FK_midweekend_weekendplans FOREIGN KEY (wID)
    REFERENCES it360_weekend_tracker.weekendplans (wID)
    ON DELETE CASCADE
    ON UPDATE NO ACTION);


-- -----------------------------------------------------
-- Table it360_weekend_tracker.sponsors
-- -----------------------------------------------------
DROP TABLE IF EXISTS it360_weekend_tracker.sponsors ;

CREATE TABLE IF NOT EXISTS it360_weekend_tracker.sponsors (
  alpha INT NOT NULL,
  sponsoraddress VARCHAR(100) NOT NULL,
  CONSTRAINT PK_sponsors PRIMARY KEY (alpha),
  CONSTRAINT FK_sponsors_midshipmen FOREIGN KEY (alpha)
    REFERENCES it360_weekend_tracker.midshipmen (alpha)
    ON DELETE CASCADE
    ON UPDATE NO ACTION);


-- -----------------------------------------------------
-- Table it360_weekend_tracker.Cell
-- -----------------------------------------------------
DROP TABLE IF EXISTS it360_weekend_tracker.Cell ;

CREATE TABLE IF NOT EXISTS it360_weekend_tracker.Cell (
  alpha INT NOT NULL,
  phone_number VARCHAR(10) NOT NULL,
  CONSTRAINT PK_Cell PRIMARY KEY (alpha),
  CONSTRAINT AK_Cell_phonenumber UNIQUE (phone_number),
  CONSTRAINT FK_cell_mids FOREIGN KEY (alpha)
    REFERENCES it360_weekend_tracker.midshipmen (alpha)
    ON DELETE CASCADE
    ON UPDATE CASCADE);


-- -----------------------------------------------------
-- Table it360_weekend_tracker.approved
-- -----------------------------------------------------
DROP TABLE IF EXISTS it360_weekend_tracker.approved ;

CREATE TABLE IF NOT EXISTS it360_weekend_tracker.approved (
  wID INT NOT NULL,
  alpha INT NOT NULL,
  approved INT NOT NULL DEFAULT 0,
  CONSTRAINT PK_approved PRIMARY KEY (wID, alpha),
  CONSTRAINT FK_approved_midweekend FOREIGN KEY (wID , alpha)
    REFERENCES it360_weekend_tracker.midweekend (wID , alpha)
    ON DELETE CASCADE
    ON UPDATE CASCADE);


-- -----------------------------------------------------
-- Table it360_weekend_tracker.weekendextra
-- -----------------------------------------------------
DROP TABLE IF EXISTS it360_weekend_tracker.weekendextra ;

CREATE TABLE IF NOT EXISTS it360_weekend_tracker.weekendextra (
  wID INT NOT NULL,
  incentive_id INT NOT NULL,
  alpha INT NOT NULL,
  CONSTRAINT PK_weekendextra PRIMARY KEY (wID, incentive_id, alpha),
  CONSTRAINT FK_weekendextra_midweekend FOREIGN KEY (wID , alpha)
    REFERENCES it360_weekend_tracker.midweekend (wID , alpha)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT FK_weekendextra_incentive FOREIGN KEY (alpha, incentive_id)
    REFERENCES it360_weekend_tracker.incentives (alpha, incentive_id)
    ON DELETE CASCADE
    ON UPDATE CASCADE);
