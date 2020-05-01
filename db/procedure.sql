# Lani Davis m201368

# {1}

DELIMITER $$
DROP PROCEDURE IF EXISTS reset $$
CREATE PROCEDURE reset()
BEGIN
DELETE incentives FROM incentives LEFT JOIN weekendextra ON incentives.incentive_id=weekendextra.incentive_id WHERE incentives.alpha=weekendextra.alpha;
DELETE FROM it360_weekend_tracker.approved;
DELETE FROM  it360_weekend_tracker.weekendextra;
DELETE FROM  it360_weekend_tracker.midweekend;
DELETE FROM  it360_weekend_tracker.weekendplans;
END $$
DELIMITER ;
