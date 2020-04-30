# Lani Davis m201368

# {1}
DROP TRIGGER IF EXISTS checkWeekendPlanOriginality;

DELIMITER $$
CREATE TRIGGER checkWeekendPlanOriginality
BEFORE INSERT ON weekendplans
FOR EACH ROW
BEGIN
declare msg varchar(128);  
if EXISTS (SELECT wid
             FROM weekendplans
             WHERE address=new.address
             AND description=new.description) then
    set msg = concat('checkWeekendPlanOriginality: This weekend plan already exists. Adding you now to this premade plan.');
    signal sqlstate '45000' set message_text = msg;
end if;
END; $$
DELIMITER ;

# {2}
DROP TRIGGER IF EXISTS checkCompanyRange;

DELIMITER $$
CREATE TRIGGER checkCompanyRange
BEFORE UPDATE ON Company
FOR EACH ROW
BEGIN
  declare msg varchar(128);
  if (new.company < 0 || new.company>30) then
    set msg = concat('checkCompanyRangeError: The company number you inputted is not in the acceptable range of 1-30.');
    signal sqlstate '45000' set message_text = msg;
end if;

END; $$
DELIMITER ;

# {3}
DROP TRIGGER IF EXISTS incentiveAddWeekend;

DELIMITER $$
CREATE TRIGGER incentiveAddWeekend
AFTER INSERT ON incentives
FOR EACH ROW
BEGIN
  DECLARE weekendcount INT;
  IF ( NEW.alpha IS NOT NULL ) THEN
  SET weekendcount =  (SELECT (incentives_available.adds_weekend+weekends_left.weekends_left)
                          FROM weekends_left,incentives_available
                          WHERE weekends_left.alpha = NEW.alpha
                          AND incentives_available.incentive_id=NEW.incentive_id);

  /* update the Students table with the new GPA value */
  UPDATE weekends_left SET weekends_left = weekendcount WHERE alpha = NEW.alpha;
  END IF;

END; $$
DELIMITER ;
