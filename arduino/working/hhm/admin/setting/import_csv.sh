#!/bin/bash
mysql -uroot -ppajeropajero4572 pos --execute="
LOAD DATA LOCAL INFILE '../../csv/file.csv' 
INTO TABLE service_order
CHARACTER SET utf8
FIELDS TERMINATED BY ',' 
OPTIONALLY ENCLOSED BY '\"' 
LINES TERMINATED BY '\n'  
IGNORE 1 LINES 
(
@Time_Order,
MenuID,
Quantity,
Price
)
SET
Time_Order=UNIX_TIMESTAMP(STR_TO_DATE(@Time_Order,'%Y%m%d%k%i%s')),
Time_Cook=UNIX_TIMESTAMP(STR_TO_DATE(@Time_Order,'%Y%m%d%k%i%s')),
Time_Serve=UNIX_TIMESTAMP(STR_TO_DATE(@Time_Order,'%Y%m%d%k%i%s')),
Time_Deliver=UNIX_TIMESTAMP(STR_TO_DATE(@Time_Order,'%Y%m%d%k%i%s')),
BranchID='21'
;";
echo "Import Completed!"