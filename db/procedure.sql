# Lani Davis m201368

# {1}

DELIMITER $$
DROP PROCEDURE IF EXISTS reset $$
CREATE PROCEDURE reset()
BEGIN
DROP TABLE IF EXISTS it360_weekend_tracker.approved;
DROP TABLE IF EXISTS it360_weekend_tracker.weekendextra;
DROP TABLE IF EXISTS it360_weekend_tracker.midweekend;
DROP TABLE IF EXISTS it360_weekend_tracker.weekendplans;
CREATE TABLE IF NOT EXISTS it360_weekend_tracker.weekendplans (
  wID INT NOT NULL AUTO_INCREMENT,
  address VARCHAR(100) NOT NULL,
  description VARCHAR(200) NOT NULL,
  CONSTRAINT PK_weekendplans PRIMARY KEY (wID));
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
CREATE TABLE IF NOT EXISTS it360_weekend_tracker.approved (
  wID INT NOT NULL,
  alpha INT NOT NULL,
  approved INT NOT NULL DEFAULT 0,
  CONSTRAINT PK_approved PRIMARY KEY (wID, alpha),
  CONSTRAINT FK_approved_midweekend FOREIGN KEY (wID , alpha)
    REFERENCES it360_weekend_tracker.midweekend (wID , alpha)
    ON DELETE CASCADE
    ON UPDATE CASCADE);
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
END $$
DELIMITER ;
