INSERT INTO midshipmen (alpha,firstname,lastname,password) VALUES
(201368,'Lani','Davis','$2y$10$dmPSQNc8h1uZ6l6eMeSu.evriBgAL3Cr7ImkPX9ahK2OVJ0LXUue.'),
(200540,'Drake','Bodine','$2y$10$DY7labTm5aBygQqxWHgcjeF0few4G5KmOM/a2ak68RbTwviyqd7ea'),
(200000,'US','NA','$2y$10$UqEJ/ybL4LZ55bbbjI5K6ue/P75t7/d68jWeQv.9brCbq8KlNPvay');
INSERT INTO weekends_left(alpha,weekends_left) VALUES
(201368,99),
(200540,99),
(200000,20);
INSERT INTO incentives_available(incentives_available,rewarddescrip,adds_weekend) VALUES
('Army-Navy Football Win','1 Beat Army Weekend',1),
('Midshipmen of the Month-1/C','An extra upperclass weekend and extended EOL',1);
INSERT INTO incentives(alpha,incentive_id) VALUES
(201368,1),
(200000,2);
INSERT INTO Company(alpha,Company) VALUES
(201368,13),
(200540,9),
(200000,1);
INSERT INTO weekendplans(address,description) VALUES
('Bancroft Hall','Sleeping In and Hanging out in the Wardroom'),
('1223 Makeup Dr. Annapolis,MD 21412','Partying and going sailing');
INSERT INTO midweekend(wID,alpha,buddyname,buddyphone) VALUES
(1,201368,'Caroline Sears','7317778090'),
(1,200540,'Joe Mahmah','1234567890'),
(2,200000,'W.T Door','7908888293');
INSERT INTO sponsors(alpha,sponsoraddress) VALUES
(200000,'1234 Spacer Dr. Annapolis,MD 21412'),
(201368,'5432 Graduation Lane Annapolis,MD 21412');
INSERT INTO Cell(alpha,phone_number) VALUES
(201368,'6788909456'),
(200540,'9119119111'),
(200000,'3562783847');
INSERT INTO approved(wID,alpha,approved) VALUES
(1,201368,1),
(2,200000,0);
INSERT INTO weekendextra(wID,incentive_id,alpha) VALUES
(1,1,201368),
(2,2,200000);
