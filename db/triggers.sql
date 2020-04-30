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
