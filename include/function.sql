-- Functions
DROP FUNCTION IF EXISTS CAP_FIRST;
CREATE FUNCTION `CAP_FIRST`(input VARCHAR(255)) RETURNS varchar(255) CHARSET latin1
 DETERMINISTIC
BEGIN
DECLARE len INT;
DECLARE i INT;
 SET len = CHAR_LENGTH(input);
SET input = LOWER(input);
SET i = 0;
 WHILE (i < len) DO
IF (MID(input,i,1) = ' ' OR i = 0) THEN
IF (i < len) THEN
SET input = CONCAT(LEFT(input,i),UPPER(MID(input,i + 1,1)),
RIGHT(input,len - i - 1));
END IF;
END IF;
SET i = i + 1;
END WHILE;
 RETURN input;
END;
#
DROP FUNCTION IF EXISTS Calculate_OT;
CREATE FUNCTION `Calculate_OT`(dates DATE,work_hrs VARCHAR(20),check_in VARCHAR(20),check_out VARCHAR(20),start_time VARCHAR(20),end_time VARCHAR(20),shift_hrs VARCHAR(30),min_hrs_ot VARCHAR(30),late_end VARCHAR(10),is_day VARCHAR(2)) RETURNS varchar(255) CHARSET latin1
BEGIN
DECLARE late_In VARCHAR(30);
DECLARE early_out VARCHAR(30);
DECLARE ot VARCHAR(30);
DECLARE over_time VARCHAR(30);
DECLARE tot_worked VARCHAR(30);
DECLARE startTime VARCHAR(30);
DECLARE endDateTime VARCHAR(30);
DECLARE Check_outTime VARCHAR(30);
DECLARE shiftHRS VARCHAR(30);
DECLARE worked_hours VARCHAR(30);

SET Check_outTime = check_out;
SET startTime = CONCAT(dates,' ',start_time,':00'); 
SET shiftHRS = CONCAT(dates,' ',shift_hrs);

IF(is_day !=0) THEN
  SET endDateTime = CONCAT(dates,' ',end_time,':00');
ELSE
  -- IF(DATE_FORMAT(check_out,'%H:%i') BETWEEN '00:00' AND late_end) THEN 
  IF(end_time BETWEEN '00:00' AND '10:00') THEN 
    SET endDateTime = CONCAT(DATE_ADD(dates,INTERVAL 1 DAY),' ',end_time,':00');
  ELSE
    SET endDateTime = CONCAT(dates,' ',end_time,':00');
  END IF;
END IF;


-- SET workHrs = IF((is_day!=0 OR (is_day=0 AND DATE_FORMAT(check_in,'%H:%i') BETWEEN '00:00' AND end_time)),SUBSTRING_INDEX(TIMEDIFF(check_out,check_in),'.',1),SUBSTRING_INDEX(TIMEDIFF(DATE_ADD(check_out,INTERVAL 1 DAY),check_in),'.',1));
SET tot_worked = SUBSTRING_INDEX(TIMEDIFF(check_out,check_in),'.',1);

SELECT -- IF((is_day !=0 OR is_day=0 AND DATE_FORMAT(check_in,'%H:%i') NOT BETWEEN '00:00' AND end_time) AND check_in>startTime,SEC_TO_TIME(TIME_TO_SEC(TIMEDIFF(check_in,startTime))),
              -- IF(is_day=0 AND DATE_FORMAT(check_in,'%H:%i')  BETWEEN '00:00' AND end_time,SEC_TO_TIME(TIME_TO_SEC(TIMEDIFF(DATE_ADD(check_in,INTERVAL 1 DAY),startTime))),'-')) emp_late_In, 
       IF(startTime < check_in,IF(SEC_TO_TIME(TIME_TO_SEC(TIMEDIFF(check_in,startTime))) >'00:00:00',SEC_TO_TIME(TIME_TO_SEC(TIMEDIFF(check_in,startTime))),'-'),'-') emp_late_In, 
       IF(Check_outTime<endDateTime,IF(SEC_TO_TIME(TIME_TO_SEC(TIMEDIFF(endDateTime,Check_outTime)))>shift_hrs,shift_hrs,SEC_TO_TIME(TIME_TO_SEC(TIMEDIFF(endDateTime,Check_outTime)))),'-') emp_early_out,
       -- IF(workHrs<shift_hrs,'-',IF(min_hrs_ot='00:00',TIMEDIFF(workHrs,shift_hrs),IF(TIMEDIFF(workHrs,shift_hrs) >=min_hrs_ot,TIMEDIFF(workHrs,shift_hrs),'-'))) emp_ot,
       IF(Check_outTime<=endDateTime,SEC_TO_TIME(TIME_TO_SEC(TIMEDIFF(check_out,check_in))),SEC_TO_TIME(TIME_TO_SEC(TIMEDIFF(endDateTime,check_in)))) worked_hours, 
       IF(Check_outTime<=endDateTime,'-',SEC_TO_TIME(TIME_TO_SEC(TIMEDIFF(Check_outTime,endDateTime)))) emp_ot,
       /*(CASE WHEN (Check_outTime>endDateTime) AND (CONCAT(end_time,':00')>=min_hrs_ot) AND MINUTE(TIMEDIFF(workHrs,shift_hrs)) BETWEEN 1 AND 29 THEN  ADDTIME(SEC_TO_TIME((TIME_TO_SEC(TIMEDIFF(workHrs,shift_hrs)) DIV 3600) * 3600), '00:00:00')
             WHEN (Check_outTime>endDateTime) AND (CONCAT(end_time,':00')>=min_hrs_ot) AND MINUTE(TIMEDIFF(workHrs,shift_hrs)) BETWEEN 30 AND 59 THEN  ADDTIME(SEC_TO_TIME((TIME_TO_SEC(TIMEDIFF(workHrs,shift_hrs)) DIV 3600) * 3600), '00:30:00') 
	           WHEN (Check_outTime>endDateTime) AND (CONCAT(end_time,':00')>=min_hrs_ot) AND MINUTE(TIMEDIFF(workHrs,shift_hrs))='00' THEN   TIMEDIFF(workHrs,shift_hrs) END )emp_ot*/
             
         (CASE WHEN (Check_outTime>endDateTime) AND MINUTE(TIMEDIFF(TIMEDIFF(Check_outTime,check_in),shiftHRS)) BETWEEN 1 AND 29 THEN  ADDTIME(SEC_TO_TIME((TIME_TO_SEC(TIMEDIFF(TIMEDIFF(Check_outTime,check_in),shiftHRS)) DIV 3600) * 3600), '00:00:00')
             WHEN (Check_outTime>endDateTime) AND MINUTE(TIMEDIFF(TIMEDIFF(Check_outTime,check_in),shiftHRS)) BETWEEN 31 AND 59 THEN  ADDTIME(SEC_TO_TIME((TIME_TO_SEC(TIMEDIFF(TIMEDIFF(Check_outTime,check_in),shiftHRS)) DIV 3600) * 3600), '00:30:00') 
	           WHEN (Check_outTime>endDateTime) AND MINUTE(TIMEDIFF(TIMEDIFF(Check_outTime,check_in),shiftHRS))='00' THEN   TIMEDIFF(TIMEDIFF(Check_outTime,check_in),shiftHRS) END )emp_over_time INTO late_In,early_out,ot,worked_hours,over_time ;
RETURN CONCAT_WS('|', late_In,IF(DATE_FORMAT(dates,'%Y-%m-%d')=DATE_FORMAT(NOW(),'%Y-%m-%d'),'-',early_out),ot,worked_hours,over_time);  
-- RETURN over_time;
END;
#
DROP FUNCTION IF EXISTS number_to_words;
CREATE FUNCTION number_to_words(n INT) RETURNS varchar(100) CHARSET utf8
BEGIN
    -- This function returns the string representation of a number.
    -- It's just an example... I'll restrict it to hundreds, but
    -- it can be extended easily.
    -- The idea is:
    --      For each digit you need a position,
    --      For each position, you assign a string
    declare ans varchar(100);
    declare dig1, dig2, dig3, dig4, dig5, dig6 int;

set ans = '';

set dig6 = CAST(RIGHT(CAST(floor(n / 100000) as CHAR(8)), 1) as SIGNED);
set dig5 = CAST(RIGHT(CAST(floor(n / 10000) as CHAR(8)), 1) as SIGNED);
set dig4 = CAST(RIGHT(CAST(floor(n / 1000) as CHAR(8)), 1) as SIGNED);
set dig3 = CAST(RIGHT(CAST(floor(n / 100) as CHAR(8)), 1) as SIGNED);
set dig2 = CAST(RIGHT(CAST(floor(n / 10) as CHAR(8)), 1) as SIGNED);
set dig1 = CAST(RIGHT(floor(n), 1) as SIGNED);

if dig6 > 0 then
    case
        when dig6=1 then set ans=concat(ans, 'one lakh');
        when dig6=2 then set ans=concat(ans, 'two lakhs');
        when dig6=3 then set ans=concat(ans, 'three lakhs');
        when dig6=4 then set ans=concat(ans, 'four lakhs');
        when dig6=5 then set ans=concat(ans, 'five lakhs');
        when dig6=6 then set ans=concat(ans, 'six lakhs');
        when dig6=7 then set ans=concat(ans, 'seven lakhs');
        when dig6=8 then set ans=concat(ans, 'eight lakhs');
        when dig6=9 then set ans=concat(ans, 'nine lakhs');
        else set ans = ans;
    end case;
end if;

if dig5 = 1 then
    case
        when (dig5*10 + dig4) = 10 then set ans=concat(ans, ' ten thousand ');
        when (dig5*10 + dig4) = 11 then set ans=concat(ans, ' eleven thousand ');
        when (dig5*10 + dig4) = 12 then set ans=concat(ans, ' twelve thousand ');
        when (dig5*10 + dig4) = 13 then set ans=concat(ans, ' thirteen thousand ');
        when (dig5*10 + dig4) = 14 then set ans=concat(ans, ' fourteen thousand ');
        when (dig5*10 + dig4) = 15 then set ans=concat(ans, ' fifteen thousand ');
        when (dig5*10 + dig4) = 16 then set ans=concat(ans, ' sixteen thousand ');
        when (dig5*10 + dig4) = 17 then set ans=concat(ans, ' seventeen thousand ');
        when (dig5*10 + dig4) = 18 then set ans=concat(ans, ' eighteen thousand ');
        when (dig5*10 + dig4) = 19 then set ans=concat(ans, ' nineteen thousand ');
        else set ans=ans;
    end case;
else
    if dig5 > 0 then
        case
            when dig5=2 then set ans=concat(ans, ' twenty');
            when dig5=3 then set ans=concat(ans, ' thirty');
            when dig5=4 then set ans=concat(ans, ' fourty');
            when dig5=5 then set ans=concat(ans, ' fifty');
            when dig5=6 then set ans=concat(ans, ' sixty');
            when dig5=7 then set ans=concat(ans, ' seventy');
            when dig5=8 then set ans=concat(ans, ' eighty');
            when dig5=9 then set ans=concat(ans, ' ninety');
            else set ans=ans;
        end case;
    end if;
    if dig4 > 0 then
        case
            when dig4=1 then set ans=concat(ans, ' one thousand ');
            when dig4=2 then set ans=concat(ans, ' two thousand ');
            when dig4=3 then set ans=concat(ans, ' three thousand ');
            when dig4=4 then set ans=concat(ans, ' four thousand ');
            when dig4=5 then set ans=concat(ans, ' five thousand ');
            when dig4=6 then set ans=concat(ans, ' six thousand ');
            when dig4=7 then set ans=concat(ans, ' seven thousand ');
            when dig4=8 then set ans=concat(ans, ' eight thousand ');
            when dig4=9 then set ans=concat(ans, ' nine thousand ');
            else set ans=ans;
        end case;
    end if;
    if dig4 = 0 AND (dig5 != 0 || dig6 != 0) then
        set ans=concat(ans, ' thousand ');
    end if;
end if;

if dig3 > 0 then
    case
        when dig3=1 then set ans=concat(ans, 'one hundred ');
        when dig3=2 then set ans=concat(ans, 'two hundred ');
        when dig3=3 then set ans=concat(ans, 'three hundred ');
        when dig3=4 then set ans=concat(ans, 'four hundred ');
        when dig3=5 then set ans=concat(ans, 'five hundred ');
        when dig3=6 then set ans=concat(ans, 'six hundred ');
        when dig3=7 then set ans=concat(ans, 'seven hundred ');
        when dig3=8 then set ans=concat(ans, 'eight hundred ');
        when dig3=9 then set ans=concat(ans, 'nine hundred ');
        else set ans = ans;
    end case;
end if;

if dig2 = 1 then
    case
        when (dig2*10 + dig1) = 10 then set ans=concat(ans, 'ten ');
        when (dig2*10 + dig1) = 11 then set ans=concat(ans, 'eleven ');
        when (dig2*10 + dig1) = 12 then set ans=concat(ans, 'twelve ');
        when (dig2*10 + dig1) = 13 then set ans=concat(ans, 'thirteen ');
        when (dig2*10 + dig1) = 14 then set ans=concat(ans, 'fourteen ');
        when (dig2*10 + dig1) = 15 then set ans=concat(ans, 'fifteen ');
        when (dig2*10 + dig1) = 16 then set ans=concat(ans, 'sixteen ');
        when (dig2*10 + dig1) = 17 then set ans=concat(ans, 'seventeen ');
        when (dig2*10 + dig1) = 18 then set ans=concat(ans, 'eighteen ');
        when (dig2*10 + dig1) = 19 then set ans=concat(ans, 'nineteen ');
        else set ans=ans;
    end case;
else
    if dig2 > 0 then
        case
            when dig2=2 then set ans=concat(ans, 'twenty ');
            when dig2=3 then set ans=concat(ans, 'thirty ');
            when dig2=4 then set ans=concat(ans, 'fourty ');
            when dig2=5 then set ans=concat(ans, 'fifty ');
            when dig2=6 then set ans=concat(ans, 'sixty ');
            when dig2=7 then set ans=concat(ans, 'seventy ');
            when dig2=8 then set ans=concat(ans, 'eighty ');
            when dig2=9 then set ans=concat(ans, 'ninety ');
            else set ans=ans;
        end case;
    end if;
    if dig1 > 0 then
        case
            when dig1=1 then set ans=concat(ans, 'one ');
            when dig1=2 then set ans=concat(ans, 'two ');
            when dig1=3 then set ans=concat(ans, 'three ');
            when dig1=4 then set ans=concat(ans, 'four ');
            when dig1=5 then set ans=concat(ans, 'five ');
            when dig1=6 then set ans=concat(ans, 'six ');
            when dig1=7 then set ans=concat(ans, 'seven ');
            when dig1=8 then set ans=concat(ans, 'eight ');
            when dig1=9 then set ans=concat(ans, 'nine ');
            else set ans=ans;
        end case;
    end if;
end if;

return trim(CONCAT(ans,'only'));
END;
#
DROP FUNCTION IF EXISTS hierarchy_connect_by_parent_eq_prior_empid;
CREATE FUNCTION `hierarchy_connect_by_parent_eq_prior_empid`(`value` CHAR(50)) RETURNS char(50) CHARSET utf8
    READS SQL DATA
BEGIN
   DECLARE _id       CHAR(50);
   DECLARE _parent   CHAR(50);
   DECLARE _next     CHAR(50);
   DECLARE CONTINUE HANDLER FOR NOT FOUND
   SET @id = NULL;

   SET _parent = @id;
   SET _id = -1;

   IF @id IS NULL
   THEN
      RETURN NULL;
   END IF;

   LOOP
      SELECT MIN(employee_id)
        INTO @id
        FROM employee_work_details
       WHERE employee_reporting_person = _parent AND employee_id > _id;

      IF @id IS NOT NULL OR _parent = @start_with
      THEN
         SET @level = @level + 1;
         RETURN @id;
      END IF;

      SET @level := @level - 1;

      SELECT employee_id, employee_reporting_person
        INTO _id, _parent
        FROM employee_work_details
       WHERE employee_id = _parent;
   END LOOP;
END;
#

-- Procedures
DROP PROCEDURE IF EXISTS c1prisvdart.ATTENDANCE_SUMMARY_INSERT;
CREATE PROCEDURE c1prisvdart.`ATTENDANCE_SUMMARY_INSERT`(reff_id VARCHAR(20),dt DATETIME)
BEGIN
IF(DATE_FORMAT(dt,'%H:%i') BETWEEN '00:00' AND '08:55')  THEN
  SET dt = DATE_FORMAT(DATE_SUB(dt,INTERVAL 1 DAY),'%Y-%m-%d');
END IF;
 
SET @from_dt =  DATE_FORMAT(dt,'%Y-%m-01');
SET @to_dt = DATE_FORMAT(dt,'%Y-%m-%d');
/*
IF(WEEKDAY(dt)=0) THEN
  SET @to_dt = DATE_FORMAT(DATE_ADD(dt,INTERVAL 6 DAY),'%Y-%m-%d');
END IF;
*/
IF(DATE_FORMAT(dt,'%d')='01') THEN
  SET @to_dt = DATE_FORMAT(LAST_DAY(dt),'%Y-%m-%d');
END IF;

SET @getEmpId = CONCAT('SELECT employee_id INTO @emp_id FROM device_users WHERE ref_id=',reff_id);
PREPARE stm FROM @getEmpId;
EXECUTE stm;
DEALLOCATE PREPARE stm;

INSERT INTO attendance_summary(employee_id,shift_id,shift_name,shift_st_time,shift_end_time,shift_hrs
				,days,checkIn,checkOut,tot_worked,work_hrs,pay_day,lateIn,earlyOut,ot,punches)      
						
      SELECT sp.employee_id,sp.shift_id,sp.shift_name,sp.start_time,sp.end_time,sp.shift_hrs,sp.dates,sp.check_in,sp.check_out,sp.tot_worked,sp.worked_hours,
            sp.pay_day,sp.late,sp.early_out,sp.ot,sp.all_punches
      FROM
            (SELECT DISTINCT employee_id,shift_id,shift_name,start_time,end_time,shift_hrs,dates,check_in,check_out,tot_worked,worked_hours,
		              pay_day,IF(day_type='FH','-',late) late,IF(day_type='SH','-',early_out) early_out,ot,all_punches
		        FROM (
		        SELECT employee_id,shift_id,shift_name,start_time,end_time,shift_hrs,dates,check_in,check_out,tot_worked,day_type,
				       IF(SUBSTRING_INDEX(SUBSTRING_INDEX(min_xtra_hrs,'|',3),'|',-1) >=min_hrs_full_day,1,IF((SUBSTRING_INDEX(SUBSTRING_INDEX(min_xtra_hrs,'|',3),'|',-1) BETWEEN min_hrs_half_day AND min_hrs_full_day),'0.5','0')) pay_day,SUBSTRING_INDEX(min_xtra_hrs,'|',1) late,
				       SUBSTRING_INDEX(SUBSTRING_INDEX(min_xtra_hrs,'|',2),'|',-1) early_out,SUBSTRING_INDEX(SUBSTRING_INDEX(min_xtra_hrs,'|',3),'|',-1) worked_hours,SUBSTRING_INDEX(min_xtra_hrs,'|',-1) ot,all_punches
				FROM (
				SELECT employee_id,ref_id,shift_id,dates,check_in,check_out,shift_hrs,day_type,
				      SUBSTRING_INDEX(TIMEDIFF(check_out,check_in),'.',1) tot_worked,late_end,
				      Calculate_OT(dates,SUBSTRING_INDEX(TIMEDIFF(check_out,check_in),'.',1),check_in,check_out,start_time,end_time,shift_hrs,min_hrs_ot,late_end,is_day) min_xtra_hrs,start_time,end_time,shift_name,min_hrs_half_day,min_hrs_full_day,all_punches
				FROM ( 
				SELECT * FROM (
								SELECT employee_id,ref_id,shift_id,(CASE WHEN is_day=1 THEN DATE_FORMAT(MIN(date_time),'%Y-%m-%d') 
				                                WHEN is_day=0  THEN DATE_FORMAT(work_day,'%Y-%m-%d') END) 'dates',
				                          (CASE WHEN is_day in(0,1) THEN DATE_FORMAT(MIN(date_time),'%Y-%m-%d %T') 
				                                -- WHEN is_day=0 THEN IF(DATE_FORMAT(work_day,'%H:%i') BETWEEN '00:00' AND late_end,DATE_FORMAT(DATE_ADD(MIN(work_day),INTERVAL 1 DAY),'%Y-%m-%d %T'),DATE_FORMAT(MIN(work_day),'%Y-%m-%d %T'))  
				                                 END) 'check_in',
				                          (CASE WHEN is_day=1 OR is_day=0 THEN DATE_FORMAT(MAX(date_time),'%Y-%m-%d %T') 
				                                END) 'check_out',from_date,to_date,
				                        employee_doj,is_day,shift_hrs,start_time,end_time,min_hrs_ot,shift_name,min_hrs_half_day,min_hrs_full_day,late_end
				FROM ( SELECT * FROM (
				      SELECT z.employee_id,z.ref_id,z.from_date,z.to_date,z.shift_id,s.is_day,s.early_start,s.late_end,s.start_time,s.end_time,b.date_time,
				      s.shift_hrs,s.min_hrs_half_day,s.min_hrs_full_day,s.min_hrs_ot,s.shift_name,
				            (CASE WHEN (s.is_day =0 AND DATE_FORMAT(date_time,'%H:%i') BETWEEN '00:00' AND s.late_end) 
				                  THEN DATE_SUB(date_time, INTERVAL 1 DAY) ELSE date_time END) work_day,z.employee_doj
				      FROM (
					      SELECT r.employee_id,u.ref_id,IF(r.shift_id IS NULL,w.shift_id,r.shift_id) shift_id,w.employee_doj,w.employee_name,w.employee_lastname,w.team_id,w.branch_id empBranch,
					      		IF(from_date<@from_dt,@from_dt,from_date) from_date,
					      		IF(to_date='0000-00-00' OR to_date IS NULL ,@to_dt,to_date) to_date
							FROM shift_roaster r
							INNER JOIN employee_work_details w
							ON r.employee_id = w.employee_id
							LEFT JOIN device_users u
							ON w.employee_id = u.employee_id
							WHERE  r.employee_id=@emp_id
							AND ((NOT (from_date > @to_dt OR to_date < @from_dt )) OR
							((to_date ='0000-00-00' OR to_date IS NULL) AND NOT from_date > @to_dt )) ) z
				      LEFT JOIN company_shifts s ON z.shift_id = s.shift_id 
				      LEFT JOIN employee_biometric b ON z.ref_id = b.employee_id 
				      AND DATE_FORMAT(date_time,'%Y-%m-%d') BETWEEN from_date AND DATE_ADD(to_date,INTERVAL 1 DAY)
				      WHERE s.is_day IS NOT NULL ORDER BY employee_id,date_time  )q WHERE DATE_FORMAT(work_day,'%Y-%m-%d') BETWEEN from_date AND to_date )t
				      GROUP BY employee_id,shift_id,DATE_FORMAT(work_day,'%Y-%m-%d'))w WHERE dates BETWEEN from_date AND to_date)c
              		  INNER JOIN (SELECT employee_id EMPID,Name,date,DATE_FORMAT(date,'%d %b,%Y') Date_Formatted,GROUP_CONCAT(punch ORDER BY date_time) all_punches 
                      FROM (
                      SELECT employee_id,Name,date_time,DATE_FORMAT(work_day,'%Y-%m-%d') date,DATE_FORMAT(work_day,'%H:%i') punch
                      FROM (
                           SELECT z.employee_id ,CONCAT(employee_name,' ',employee_lastname) Name,b.date_time,
                                 (CASE WHEN (s.is_day =0 AND DATE_FORMAT(date_time,'%H:%i') BETWEEN '00:00' AND s.late_end) THEN DATE_SUB(date_time, INTERVAL 1 DAY)
                                  ELSE date_time END) work_day
                           FROM (
	                            SELECT r.employee_id,u.ref_id,IF(r.shift_id IS NULL,w.shift_id,r.shift_id) shift_id,w.employee_doj,w.employee_name,w.employee_lastname,w.team_id,w.branch_id empBranch,
							        IF(from_date<@from_dt,@from_dt,from_date) from_date,
							        IF(to_date='0000-00-00' OR to_date IS NULL ,@to_dt,to_date) to_date
								FROM shift_roaster r
								INNER JOIN employee_work_details w
								ON r.employee_id = w.employee_id
								LEFT JOIN device_users u
								ON w.employee_id = u.employee_id
								WHERE  r.employee_id=@emp_id
								AND ((NOT (from_date > @to_dt OR to_date < @to_dt )) OR
								((to_date ='0000-00-00' OR to_date IS NULL) AND NOT from_date > @from_dt )) ) z
		                           LEFT JOIN company_shifts s
                           ON z.shift_id = s.shift_id
                           LEFT JOIN device_users du
                           ON z.employee_id = du.employee_id
                           LEFT JOIN employee_biometric b
                           ON du.ref_id = b.employee_id AND DATE_FORMAT(date_time,'%Y-%m-%d') BETWEEN from_date AND DATE_ADD(to_date,INTERVAL 1 DAY)
                           WHERE s.is_day IS NOT NULL
                           ORDER BY z.employee_id,work_day) q
                      GROUP BY employee_id,work_day
                      )w GROUP BY employee_id,date) t
                    ON c.employee_id = t.EMPID AND dates = t.date
                    LEFT JOIN (SELECT ab.employee_id absent_id,ab.absent_date,ab.day_type
					FROM emp_absences ab
					WHERE ab.day_type!='FD' AND ab.absent_date BETWEEN @from_dt AND @to_dt) p
					ON c.employee_id = p.absent_id AND dates = p.absent_date 
                    WHERE dates BETWEEN @from_dt AND @to_dt)w
			      UNION 
			      SELECT employee_id,shift_id,shift_name,start_time,end_time,shift_hrs,dates,check_in,check_out,'' tot_worked,worked_hrs,day_type,pay_day,late,early_out,ot,all_punches
            FROM (
			      SELECT employee_id,from_date,to_date,z.shift_id,shift_name,start_time,end_time,'' shift_hrs,dates,'' check_in,'' check_out,'' worked_hrs,'' day_type,'' pay_day,'' late,'' early_out,'' ot,'' all_punches
			FROM (
			SELECT r.employee_id,u.ref_id,IF(r.shift_id IS NULL,w.shift_id,r.shift_id) shift_id,w.employee_doj,w.employee_name,w.employee_lastname,w.team_id,w.branch_id empBranch,
					      IF(from_date<@from_dt,@from_dt,from_date) from_date,
					      IF(to_date='0000-00-00' OR to_date IS NULL ,@to_dt,to_date) to_date
						FROM shift_roaster r
						INNER JOIN employee_work_details w
						ON r.employee_id = w.employee_id
						LEFT JOIN device_users u
						ON w.employee_id = u.employee_id
						WHERE  r.employee_id=@emp_id
						AND ((NOT (from_date > @to_dt OR to_date < @from_dt )) OR
						((to_date ='0000-00-00' OR to_date IS NULL) AND NOT from_date > @to_dt )))z
	  		LEFT JOIN company_shifts s ON IF(z.shift_id = 'Nil' OR z.shift_id = '','SH00001',z.shift_id) = s.shift_id
	  		JOIN (SELECT date dates FROM 
              (SELECT adddate('1970-01-01',t4*10000 + t3*1000 + t2*100 + t1*10 + t0) date FROM
               (SELECT 0 t0 UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t0,
               (SELECT 0 t1 UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t1,
               (SELECT 0 t2 UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t2,
               (SELECT 0 t3 UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t3,
               (SELECT 0 t4 UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t4) v
              WHERE date BETWEEN @from_dt AND @to_dt) t
  			  WHERE dates NOT IN (SELECT dates FROM (
  			  					SELECT * FROM (
								SELECT employee_id,ref_id,shift_id,(CASE WHEN is_day=1 THEN DATE_FORMAT(MIN(date_time),'%Y-%m-%d') 
				                                WHEN is_day=0  THEN DATE_FORMAT(work_day,'%Y-%m-%d') END) 'dates',
				                          (CASE WHEN is_day=1 THEN DATE_FORMAT(MIN(date_time),'%Y-%m-%d %T') 
				                                WHEN is_day=0 THEN IF(DATE_FORMAT(work_day,'%H:%i') BETWEEN '00:00' AND late_end,DATE_FORMAT(DATE_ADD(work_day,INTERVAL 1 DAY),'%Y-%m-%d %T'),DATE_FORMAT(work_day,'%Y-%m-%d %T'))  
				                                 END) 'check_in',
				                          (CASE WHEN is_day=1 OR is_day=0 THEN DATE_FORMAT(MAX(date_time),'%Y-%m-%d %T') 
				                                END) 'check_out',from_date,to_date,
				                        employee_doj,is_day,shift_hrs,start_time,end_time,min_hrs_ot,shift_name,min_hrs_half_day,min_hrs_full_day,late_end
				FROM ( 
				      SELECT z.employee_id,z.ref_id,z.from_date,z.to_date,z.shift_id,s.is_day,s.early_start,s.late_end,s.start_time,s.end_time,b.date_time,
				      s.shift_hrs,s.min_hrs_half_day,s.min_hrs_full_day,s.min_hrs_ot,s.shift_name,
				            (CASE WHEN (s.is_day =0 AND DATE_FORMAT(date_time,'%H:%i') BETWEEN '00:00' AND s.late_end) 
				                  THEN DATE_SUB(date_time, INTERVAL 1 DAY) ELSE date_time END) work_day,z.employee_doj
				      FROM (
				      SELECT r.employee_id,u.ref_id,IF(r.shift_id IS NULL,w.shift_id,r.shift_id) shift_id,w.employee_doj,w.employee_name,w.employee_lastname,w.team_id,w.branch_id empBranch,
					      IF(from_date<@from_dt,@from_dt,from_date) from_date,
					      IF(to_date='0000-00-00' OR to_date IS NULL ,@to_dt,to_date) to_date
						FROM shift_roaster r
						INNER JOIN employee_work_details w
						ON r.employee_id = w.employee_id
						LEFT JOIN device_users u
						ON w.employee_id = u.employee_id
						WHERE  r.employee_id=@emp_id
						AND ((NOT (from_date > @to_dt OR to_date < @from_dt )) OR
						((to_date ='0000-00-00' OR to_date IS NULL) AND NOT from_date > @to_dt )) ) z
				      LEFT JOIN company_shifts s ON z.shift_id = s.shift_id 
				      LEFT JOIN employee_biometric b ON z.ref_id = b.employee_id 
				      AND DATE_FORMAT(date_time,'%Y-%m-%d') BETWEEN from_date AND DATE_ADD(to_date,INTERVAL 1 DAY)
				      WHERE s.is_day IS NOT NULL ORDER BY employee_id,date_time)q
              GROUP BY employee_id,shift_id,DATE_FORMAT(work_day,'%Y-%m-%d'))w )c)) p
      	WHERE dates BETWEEN from_date AND to_date 
        ORDER BY employee_id,dates)q) sp
        ON DUPLICATE KEY UPDATE shift_id=sp.shift_id,shift_name=sp.shift_name,shift_st_time=sp.start_time,
                shift_end_time=sp.end_time,shift_hrs=sp.shift_hrs,checkIn=sp.check_in,checkOut=sp.check_out,tot_worked=sp.tot_worked,
                work_hrs=sp.worked_hours,pay_day=sp.pay_day,lateIn=sp.late,earlyOut=sp.early_out,ot=sp.ot,punches=sp.all_punches;

  SET @updateWeekoff= CONCAT('UPDATE attendance_summary s JOIN ( SELECT employee_id,shift_id,days,is_weekday,is_holiday,is_leave,IF(is_weekday IS NOT NULL AND is_leave=\'\' AND is_holiday=\'\',is_weekday,IF(is_holiday!=\'\',is_holiday,IF(is_leave!=\'\',is_leave,\'W\'))) type FROM ( SELECT employee_id,shift_id,days,IF(is_weekday=\'WE\',1,IF(is_weekday=\'WE-FH\' OR is_weekday=\'WE-SH\',CONCAT(SUBSTRING_INDEX(is_weekday,\'-\',-1),\'-0.5\'),NULL)) is_weekday,is_holiday,is_leave FROM ( SELECT s.employee_id,s.shift_id,days,(CASE WHEN (weeks = IF((WEEK(days) - WEEK(DATE_FORMAT(days , \'%Y-%m-01\')) + 1)>5,(WEEK(days) - WEEK(DATE_FORMAT(days , \'%Y-%m-01\')) - 1),WEEK(days) - WEEK(DATE_FORMAT(days , \'%Y-%m-01\')) + 1)) THEN (CASE WHEN (DAYNAME(days)=\'sunday\') THEN IF((sunday = \'FH\' OR sunday = \'SH\' OR sunday = \'FD\' ),IF((sunday = \'FH\' OR sunday = \'SH\'),CONCAT(\'WE\',\'-\',sunday),\'WE\'),sunday) WHEN (DAYNAME(days)=\'Monday\') THEN IF((monday = \'FH\' OR monday = \'SH\' OR monday = \'FD\' ),IF((monday = \'FH\' OR monday = \'SH\'),CONCAT(\'WE\',\'-\',monday),\'WE\'),monday) WHEN (DAYNAME(days)=\'Tuesday\') THEN IF((tuesday = \'FH\' OR tuesday = \'SH\' OR tuesday = \'FD\' ),IF((Tuesday = \'FH\' OR Tuesday = \'SH\'),CONCAT(\'WE\',\'-\',Tuesday),\'WE\'),Tuesday) WHEN (DAYNAME(days)=\'Wednesday\') THEN IF((wednesday = \'FH\' OR wednesday = \'SH\' OR wednesday = \'FD\' ),IF((wednesday = \'FH\' OR wednesday = \'SH\'),CONCAT(\'WE\',\'-\',wednesday),\'WE\'),wednesday) WHEN (DAYNAME(days)=\'Thursday\') THEN IF((thursday = \'FH\' OR thursday = \'SH\' OR thursday = \'FD\' ),IF((thursday = \'FH\' OR thursday = \'SH\'),CONCAT(\'WE\',\'-\',thursday),\'WE\'),thursday) WHEN (DAYNAME(days)=\'Friday\') THEN IF((friday = \'FH\' OR friday = \'SH\' OR friday = \'FD\' ),IF((friday = \'FH\' OR friday = \'SH\'),CONCAT(\'WE\',\'-\',friday),\'WE\'),friday) WHEN (DAYNAME(days)=\'Saturday\') THEN IF((saturday = \'FH\' OR saturday = \'SH\' OR saturday = \'FD\' ),IF((saturday = \'FH\' OR saturday = \'SH\'),CONCAT(\'WE\',\'-\',saturday),\'WE\'),saturday) ELSE \'\' END) END) is_weekday,IFNULL((CASE WHEN ((start_date BETWEEN days AND days) OR (end_date BETWEEN days AND days)) THEN (CASE WHEN (category = \'OPTIONAL\' AND h.branch_id = wd.branch_id ) THEN \'RH\' WHEN (category = \'HOLIDAY\' AND h.branch_id = \'NA\' ) THEN \'GH\' END) END),\'\') is_holiday,IFNULL(CONCAT(leave_rule_type,\'-\',day_count),\'\') is_leave FROM attendance_summary s INNER JOIN employee_work_details wd ON s.employee_id = wd.employee_id LEFT JOIN weekend w ON s.shift_id = w.shift_id LEFT JOIN holidays_event h ON (h.start_date BETWEEN \'',@from_dt,'\' AND \'',@to_dt,'\') AND s.days BETWEEN h.start_date AND h.end_date LEFT JOIN emp_absences a ON a.employee_id = \'',@emp_id,'\' AND a.absent_date = s.days AND a.absent_date BETWEEN \'',@from_dt,'\' AND \'',@to_dt,'\' WHERE s.employee_id = \'',@emp_id,'\' AND days BETWEEN \'',@from_dt,'\' AND \'',@to_dt,'\' )z WHERE is_weekday IS NOT NULL ORDER BY employee_id,days)z WHERE is_weekday IS NOT NULL OR is_holiday IS NOT NULL OR is_leave IS NOT NULL  )t ON s.employee_id = t.employee_id AND s.days = t.days SET s.day_type = t.type;');
  PREPARE stm1 FROM @updateWeekoff;
  EXECUTE stm1;
  DEALLOCATE PREPARE stm1; 
  
  SET @updteRegularaization= CONCAT('UPDATE attendance_summary ar INNER JOIN attendance_regularization re ON ar.employee_id = re.employee_id AND re.day = ar.days SET late_approved = (CASE WHEN re.regularize_type=\'Late\' AND re.status = \'A\' AND re.day = ar.days THEN 1 ELSE 0 END),early_approved = (CASE WHEN re.regularize_type=\'EarlyOut\' AND re.status = \'A\' AND re.day = ar.days  THEN 1 ELSE 0 END) WHERE ar.employee_id =\'',@emp_id,'\' AND re.day BETWEEN \'',@from_dt,'\' AND \'',@to_dt,'\' AND ar.days BETWEEN \'',@from_dt,'\' AND \'',@to_dt,'\';');
  PREPARE stm2 FROM @updteRegularaization;
  EXECUTE stm2;
  DEALLOCATE PREPARE stm2;
  
END;
#
DROP PROCEDURE IF EXISTS LEAVEYEARLY_CREDIT;
CREATE PROCEDURE `LEAVEYEARLY_CREDIT`(IN lastPayrollDate VARCHAR(20),IN next_yeardate VARCHAR(20),IN current_year VARCHAR(20),IN g_employee_id VARCHAR(10000),
IN leave_rules_id VARCHAR(100),IN past_year VARCHAR(20),IN updated_by VARCHAR(20))
BEGIN
  DECLARE lalias_name VARCHAR(20);
  SET @leaveCount=0;
  SET @l_vars='';
  SET @alotted_vars = '';
  SET @leave_col='';
  SET @leave_id_in ='';
  SET @sqlPivot = '';
  SET @c_str=leave_rules_id;
 -- SET @c_str1=monthly_lr_ids;
  SET @e_str=g_employee_id;
  SET @leaveCount1=0;
  SET @l_vars1='';
  SET @alotted_vars1 = '';
  SET @leave_col1='';
  SET @leave_id_in1 =''; 
  -- for Yearly            
             WHILE @c_str != '' DO
                SET @currentValue = SUBSTRING_INDEX(@c_str, ',', 1);
                SET @leaveCount = @leaveCount + 1;
                SET @leave_col = CONCAT(@leave_col,',',@currentValue);
                SET @l_vars = CONCAT(@l_vars,',@l_',@currentValue);
                SET @alotted_vars = CONCAT(@alotted_vars,',@al_',@currentValue);
                SET @leave_id_in = CONCAT(@leave_id_in,',\'',@currentValue,'\'');
                SET @sqlPivot = CONCAT(@sqlPivot,',MAX(IF(leave_rule_id = \'', @currentValue, '\',(opening_bal + allotted ),NULL)) AS ', @currentValue);
                SET @c_str = SUBSTRING(@c_str, CHAR_LENGTH(@currentValue) + 1 + 1);
              END WHILE;
     -- split employee     
          WHILE @e_str != '' DO
          SET @currentEmp = SUBSTRING_INDEX(@e_str, ',', 1);
          -- Yearly leave rules so leaverule added into leave account table based on type=Y
           SET @c_str = leave_rules_id;
            WHILE @c_str != '' DO
            SET @currentValue = SUBSTRING_INDEX(@c_str, ',', 1);
          block1:BEGIN
            DECLARE leave_rule_id_ VARCHAR(20);
            DECLARE effects_from_ VARCHAR(20);
            DECLARE allot_from_ CHAR(5);
            DECLARE type_ CHAR(2);
            DECLARE days_count_ VARCHAR(20);
            DECLARE pro_rata_basis_ tinyint(1);
            DECLARE round_off_ CHAR(2);
            DECLARE carry_forward_ tinyint(1);
            DECLARE max_cf_days_ VARCHAR(20);
            DECLARE remain_cf_ CHAR(2);
            DECLARE remain_enc_ CHAR(2);
            DECLARE max_enc_days_ VARCHAR(20);            
            DECLARE done_j INT DEFAULT 0;
            DECLARE cursor_j CURSOR FOR SELECT leave_rule_id, effects_from, allot_from, type, days_count, pro_rata_basis,round_off,carry_forward,max_cf_days,remain_cf,max_enc_days,remain_enc FROM vw_employees;
            DECLARE CONTINUE HANDLER FOR NOT FOUND SET done_j = TRUE;
            SET @employee_doj ='';
            SET @next_yeardate ='';
            SET @allot_from_='';
            SET @type_='';
            SET @days_count_='';
            SET @round_off_='';
            SET @pro_rata_basis_='';
            SET @employee_doj =lastPayrollDate;
            SET @next_yeardate =next_yeardate;
            SET @select_s = concat('CREATE OR REPLACE VIEW vw_employees as SELECT leave_rule_id, effects_from, allot_from, type, days_count, pro_rata_basis,round_off,carry_forward,max_cf_days,remain_cf,max_enc_days,remain_enc FROM company_leave_rules where enabled=1');
            PREPARE stm FROM @select_s;
            EXECUTE stm;
            DEALLOCATE PREPARE stm;
            OPEN cursor_j;
              read_loop2:LOOP
                FETCH cursor_j INTO leave_rule_id_, effects_from_, allot_from_, type_, days_count_, pro_rata_basis_,round_off_,carry_forward_,max_cf_days_,remain_cf_,max_enc_days_,remain_enc_;
                IF done_j THEN
                LEAVE read_loop2;
                END IF;
                SET @allot_from_=allot_from_;
                SET @type_=type_;
                SET @days_count_=days_count_;
                SET @round_off_=round_off_;
                SET @pro_rata_basis_=pro_rata_basis_;
                IF  leave_rule_id_=@currentValue THEN 
                -- call procedure for current year totolallotment for all employees
                 CALL CREDIT_LEAVE(lastPayrollDate,current_year,@next_yeardate, @currentEmp,
                 @employee_doj, @employee_doj, @currentValue, 
                 @employee_doj, @allot_from_, @type_, @days_count_, @round_off_,
                 @pro_rata_basis_,updated_by);
             SET @update_query=CONCAT('SELECT (opening_bal+allotted-availed-encashed-adjusted-lapsed) INTO  @opening_bal FROM emp_leave_account where leave_rule_id =\'',@currentValue,'\' AND  employee_id=\'',@currentEmp,'\'  AND year =\'',past_year,'\'');
                PREPARE stm2 FROM @update_query;
                EXECUTE stm2;
                DEALLOCATE PREPARE stm2;
                -- carry forward if yes
                SET @flag='';
                SET @flag_sub='';
                SET @lapsed='';
                SET @encashable='';
                          IF carry_forward_=1 THEN
                            SET @max_days=max_cf_days_;
                            SET @enc_days=max_enc_days_;
                            SET @flag_sub=1;
                                      IF remain_cf_='L' THEN 
                                      SET @flag=0;
                                      ELSE
                                      SET @flag=1;
                                      END IF;
                            ELSE
                            SET @flag_sub=0;
                            -- encashable if yes
                            SET @max_days=max_enc_days_;
                                      IF remain_enc_='L' THEN 
                                      SET @flag=0;
                                      ELSE
                                      SET @flag=1;
                                      END IF;
                          END IF;
                SET @encashable=0;
                SET @lapsed=0;
                SET @opening_bal_s=LEAST(@max_days,@opening_bal);
                SET @condition=GREATEST((@opening_bal-@max_days),0);
                      IF  @condition > 0  THEN -- set value into lapsed
                          IF  @flag=1 THEN
                          -- ENCASHED AND OPENING BAL UPDATE
                          SET @encashable=LEAST(@enc_days,@condition);
                          SET @val=GREATEST((@condition-@encashable),0);
                              IF @val > 0 THEN 
                              SET @lapsed=@val;
                              END IF;
                          ELSE
                          -- LAPSED AND OPENING BAL UPDATE
                          SET @lapsed=@condition;
                          END IF;
                      END IF;
                          IF @flag_sub = 1 THEN 
                          SET @openinbalQuery=CONCAT('UPDATE emp_leave_account SET  opening_bal=\'',@opening_bal_s,'\' WHERE leave_rule_id = \'',@currentValue,'\' AND employee_id=\'',@currentEmp,'\'  AND year =\'',current_year,'\' ');   
                          SET @enclapQuery=CONCAT('UPDATE emp_leave_account SET  encashed=\'',@encashable,'\' ,lapsed=\'',@lapsed,'\'  WHERE leave_rule_id = \'',@currentValue,'\' AND employee_id=\'',@currentEmp,'\'  AND year =\'',past_year,'\' ');     
                          ELSE
                          SET @openinbalQuery=CONCAT('UPDATE emp_leave_account SET  opening_bal=\'',@encashable,'\' WHERE leave_rule_id = \'',@currentValue,'\' AND employee_id=\'',@currentEmp,'\'  AND year =\'',current_year,'\' ');   
                          SET @enclapQuery=CONCAT('UPDATE emp_leave_account SET  encashed=\'',@opening_bal_s,'\' ,lapsed=\'',@lapsed,'\'  WHERE leave_rule_id = \'',@currentValue,'\' AND employee_id=\'',@currentEmp,'\'  AND year =\'',past_year,'\' ');     
                          END IF;
            PREPARE stm1 FROM @openinbalQuery;
            EXECUTE stm1;
            DEALLOCATE PREPARE stm1;
            PREPARE stm2 FROM @enclapQuery;
            EXECUTE stm2;
            DEALLOCATE PREPARE stm2;
            END IF;
            END LOOP;
            CLOSE cursor_j;
            END block1;
            SET @c_str = SUBSTRING(@c_str, CHAR_LENGTH(@currentValue) + 1 + 1);
            END WHILE;
            SET @e_str = SUBSTRING(@e_str, CHAR_LENGTH(@currentEmp) + 1 + 1);
            END WHILE;     
END;
-- Leave Account Credit
#
DROP PROCEDURE IF EXISTS LEAVEACCOUNT_CREDIT;
CREATE PROCEDURE `LEAVEACCOUNT_CREDIT`(IN current_year VARCHAR(20),IN g_employee_id VARCHAR(10000), 
 IN leave_rules_id VARCHAR(100),monthly_lr_ids VARCHAR(100),IN updated_by VARCHAR(20))
BEGIN 
 DECLARE lalias_name VARCHAR(20); 
 SET @leaveCount=0; 
 SET @l_vars=''; 
 SET @alotted_vars = ''; 
 SET @leave_col=''; 
 SET @leave_id_in =''; 
 SET @sqlPivot = ''; 
 SET @c_str=leave_rules_id; 
 SET @c_str1=monthly_lr_ids; 
 SET @e_str=g_employee_id; 
 SET @leaveCount1=0; 
 SET @l_vars1=''; 
 SET @alotted_vars1 = ''; 
 SET @leave_col1=''; 
 SET @leave_id_in1 =''; 
 WHILE @c_str != '' DO 
 SET @currentValue = SUBSTRING_INDEX(@c_str, ',', 1); 
 SET @leaveCount = @leaveCount + 1; 
 SET @leave_col = CONCAT(@leave_col,',',@currentValue); 
 SET @l_vars = CONCAT(@l_vars,',@l_',@currentValue); 
 SET @alotted_vars = CONCAT(@alotted_vars,',@al_',@currentValue); 
 SET @leave_id_in = CONCAT(@leave_id_in,',\'',@currentValue,'\''); 
 SET @sqlPivot = CONCAT(@sqlPivot,',MAX(IF(leave_rule_id = \'', @currentValue, '\',(opening_bal + allotted ),NULL)) AS ', @currentValue); 
 SET @c_str = SUBSTRING(@c_str, CHAR_LENGTH(@currentValue) + 1 + 1); 
 END WHILE; 
 WHILE @c_str1 != '' DO 
 SET @currentValue1 = SUBSTRING_INDEX(@c_str1, ',', 1); 
 SET @leaveCount1 = @leaveCount1 + 1; 
 SET @leave_col1 = CONCAT(@leave_col1,',',@currentValue1); 
 SET @l_vars1 = CONCAT(@l_vars1,',@l_',@currentValue1); 
 SET @alotted_vars1 = CONCAT(@alotted_vars1,',@al_',@currentValue1); 
 SET @leave_id_in1 = CONCAT(@leave_id_in1,',\'',@currentValue1,'\''); 
 SET @c_str1 = SUBSTRING(@c_str1, CHAR_LENGTH(@currentValue1) + 1 + 1); 
 END WHILE; 
 WHILE @e_str != '' DO 
 SET @currentEmp = SUBSTRING_INDEX(@e_str, ',', 1); 
 -- select the availed leaves from payroll_preview_temp 
 SET @previewTemp = CONCAT('SELECT lop ',@leave_col,' INTO @lop ',@l_vars, ' FROM payroll_preview_temp WHERE employee_id =\'',@currentEmp,'\''); 
 PREPARE stm1 FROM @previewTemp; 
 EXECUTE stm1; 
 DEALLOCATE PREPARE stm1; 
 -- select the alotted leave as column from emp leave acc table 
 SET @stmt = CONCAT('SELECT ', SUBSTR(@sqlPivot,2) , ' INTO ',SUBSTR(@alotted_vars,2),' FROM emp_leave_account WHERE employee_id = \'',@currentEmp,'\' AND year= \'',current_year,'\''); 
 PREPARE stmt FROM @stmt; 
 EXECUTE stmt; 
 DEALLOCATE PREPARE stmt; 
 -- 
 -- all leave rules 
 SET @c_str = leave_rules_id; 
 SET @update_query = 'UPDATE emp_leave_account SET availed = (CASE '; 
 SET @allottedCases=''; 
 SET @availedCases=''; 
 WHILE @c_str != '' DO 
 SET @currentValue = SUBSTRING_INDEX(@c_str, ',', 1); 
 SET @availedCases = CONCAT(@availedCases,' WHEN leave_rule_id = \'',@currentValue,'\' THEN availed + @l_',@currentValue); 
 SET @c_str = SUBSTRING(@c_str, CHAR_LENGTH(@currentValue) + 1 + 1); 
 END WHILE; 
 SET @update_query = CONCAT(@update_query,@availedCases,' END) WHERE employee_id = \'',@currentEmp,'\' AND leave_rule_id IN (',SUBSTRING_INDEX(@leave_id_in,',', -(@leaveCount)),') AND year = \'',current_year,'\''); 
 -- MOnthly leave rules so alloted updated once again 
 SET @c_str1 = monthly_lr_ids; 
 SET @update_query1 = 'UPDATE emp_leave_account SET allotted = (CASE '; 
 SET @allottedCases1=''; 
 SET @availedCases1=''; 
 WHILE @c_str1 != '' DO 
 SET @currentValue1 = SUBSTRING_INDEX(@c_str1, ',', 1); 
 SET @availedCases1 = CONCAT(@availedCases1,' WHEN leave_rule_id = \'',@currentValue1,'\' THEN allotted + (SELECT days_count as allotted FROM company_leave_rules where leave_rule_id = \'',@currentValue1,'\')'); 
 SET @c_str1 = SUBSTRING(@c_str1, CHAR_LENGTH(@currentValue1) + 1 + 1); 
 END WHILE; 
 SET @update_query1 = CONCAT(@update_query1,@availedCases1,' END) WHERE employee_id = \'',@currentEmp,'\' AND leave_rule_id IN (',SUBSTRING_INDEX(@leave_id_in1,',', -(@leaveCount1)),') AND year = \'',current_year,'\''); 
 -- SET @update_query = CONCAT(@update_query,@availedCases,' END),bal=(CASE ',@allottedCases,' END) WHERE employee_id = \'',@currentEmp,'\' AND leave_rule_id IN (',SUBSTRING_INDEX(@leave_id_in,',', -(@leaveCount)),') AND year = \'',current_year,'\''); 
 SET @e_str = SUBSTRING(@e_str, CHAR_LENGTH(@currentEmp) + 1 + 1); 
 PREPARE stm1 FROM @update_query; 
 EXECUTE stm1; 
 DEALLOCATE PREPARE stm1; 
 PREPARE stm0 FROM @update_query1; 
 EXECUTE stm0; 
 DEALLOCATE PREPARE stm0; 
 END WHILE; 
 END;
#
DROP PROCEDURE IF EXISTS CREDIT_LEAVE;
CREATE PROCEDURE `CREDIT_LEAVE`(IN `lastPayrollDate` VARCHAR(20),IN current_year VARCHAR(20),IN next_yeardate VARCHAR(20),
IN employee_id VARCHAR(20),IN employee_doj VARCHAR(20),
IN employee_confirmation_date VARCHAR(20),IN leaverule_id VARCHAR(20),
IN effect_from VARCHAR(20),IN allot_from CHAR(5),IN type CHAR(1),IN days_count VARCHAR(20),
IN round_off CHAR(2),IN pro_rata_basis tinyint(1),IN updated_by VARCHAR(20))
BEGIN
  DECLARE dayscount VARCHAR(20);
  DECLARE alloted_leave VARCHAR(20);
  DECLARE month_ VARCHAR(20);
  DECLARE result VARCHAR(20);
  DECLARE leaverule_flag VARCHAR(1);
  DECLARE ruleQuery VARCHAR(200);
  DECLARE bal NUMERIC(15,2);
  DECLARE result_bal NUMERIC(15,2);
  DECLARE extct_deci DECIMAL(10,2);
     SET @leave=leaverule_id;
     SET @effect_from=effect_from;
     SET @join_date=employee_doj;
     SET @confirmation_date=employee_confirmation_date;
     SET @next_yeardate=next_yeardate;
     SET @date_='';
     SET @tot_month = '';
     SET @days_count_ = '';
     SET @value_int = '';
     SET @value_deci = '';
     SET dayscount = '';
     SET alloted_leave='';
     SET result='';
     SET leaverule_flag ='1'; 
     SET ruleQuery ='1'; 
      SET @after_days='0';
      -- set doj+days as d date
      SET @allot_from=substring_index(allot_from,'|',-1);
                IF @allot_from='AD' THEN
                SET @after_days=substring_index(allot_from,'|',1);
                END IF;
  IF @join_date <=  @effect_from  THEN
    -- doj less than wef - make wef as d date
    IF lastPayrollDate >= @effect_from  THEN
        SET @date_= lastPayrollDate;
    ELSE
     SET @date_=@effect_from;
    END IF;
  ELSEIF @join_date >= @effect_from THEN
    -- doj gt wef
    IF @allot_from ='JD'  THEN
      -- set doj as d date
        SET @date_=@join_date;
    ELSEIF @allot_from ='CD' THEN
      -- set conf as d date
       SET @date_=@confirmation_date;
    ELSEIF @allot_from= 'AD' THEN
      SET @date_= DATE_ADD(@join_date , INTERVAL @after_days DAY);
      SET leaverule_flag='0';
    END IF;
  END IF;
  -- find difference betw dates here (nxtyrdate,date)
                  SET @tot_month=(PERIOD_DIFF(DATE_FORMAT(@next_yeardate,'%Y%m'),DATE_FORMAT(@date_,'%Y%m')))-1; 
                  SET  @days_count_ =DATEDIFF(LAST_DAY(@date_),@date_)+1;
                  SET @ss=pro_rata_basis;
                  IF pro_rata_basis=1 THEN -- CHECK PRO RATE
                      IF type='M' THEN -- CHECK MONTH/YEAR
                      SET alloted_leave=days_count*(@days_count_/(DAY(LAST_DAY(@date_))));
                     -- SET alloted_leave=dayscount+(days_count);
                      ELSE
                      SET dayscount=(days_count/12)*(@days_count_/(DAY(LAST_DAY(@date_))));
                      SET alloted_leave=dayscount+((days_count*@tot_month)/12);
                      END IF; -- CHECK MONTH/YEAR END
                  ELSE  -- PRORATE ELSE PART
                      IF type='M' THEN -- CHECK MONTH/YEAR
                      SET alloted_leave=days_count;
                      ELSE
                      SET dayscount=(days_count/12)*(@days_count_/(DAY(LAST_DAY(@date_))));
                      SET alloted_leave=dayscount+((days_count*@tot_month)/12);
                      SET @alloted_leave=dayscount+((days_count*@tot_month)/12);
                      END IF; -- CHECK MONTH/YEAR END
                  END IF;-- PRORATE ELSE PART END
                  IF round_off='LH' THEN 
                      SET @value_int = TRUNCATE(alloted_leave,0); -- truncating the alloted_leave
                      SET extct_deci = (FORMAT(MOD(alloted_leave,1),2)); -- extracting the decimal value from alloted_leave
                     IF extct_deci >= 0.00 AND extct_deci <= 0.49 THEN
                      SET @value_deci = .0; -- allotment from if condition
                      SET result = @value_int+@value_deci;
                     ELSEIF  extct_deci >= 0.50 AND extct_deci <= 0.99 THEN
                      SET @value_deci = .5;
                      SET result = @value_int+@value_deci;
                      END IF;
                  ELSEIF round_off='HH' THEN -- missing
                      SET @value_int = TRUNCATE(alloted_leave,0);
                      SET extct_deci = (FORMAT(MOD(alloted_leave,1),2));
                     IF extct_deci = 0.0 THEN
                      SET result = ROUND(alloted_leave);
                     ELSEIF extct_deci >= 0.10 AND extct_deci <= 0.49 THEN
                      SET @value_deci = .5;
                      SET result = @value_int+@value_deci;
                     ELSEIF extct_deci >= 0.50 AND extct_deci <= 0.99 THEN
                      SET result = ROUND(alloted_leave);
                     END IF;
                  ELSEIF round_off='LF' THEN 
                  SET  result=ROUND(alloted_leave); -- missing
                  ELSEIF round_off= 'HF' THEN
                  SET  result=CEIL(alloted_leave);
                  ELSE
                  SET  result=ROUND(alloted_leave,2);
                  END IF;
                  SET result_bal=GREATEST(result,0);  
                  SET @ruleQuery1 = '';
                  SET @ruleQuery1 =CONCAT('INSERT INTO emp_leave_account (employee_id, `year`, leave_rule_id, allotted,is_leavecredited,updated_by) values(');
                  SET @ruleQuery1 = CONCAT(@ruleQuery1,'\'',employee_id,'\',\'',current_year,'\',\'',leaverule_id,'\',\'',result_bal,'\',',leaverule_flag,',\'','',updated_by,'','\')ON DUPLICATE KEY UPDATE allotted=\'',result,'\'');                
                  PREPARE stm FROM @ruleQuery1;
                  EXECUTE stm;
                  DEALLOCATE PREPARE stm;
                  /*SET @updateQuery = '';
                  SET @updateQuery =CONCAT('UPDATE emp_leave_account SET bal=(opening_bal+allotted)-(availed+encashed+lapsed+adjusted) WHERE employee_id=\'',employee_id,'\' AND year= \'',current_year,'\' AND leave_rule_id=\'',leaverule_id,'\'');
                  PREPARE stm2 FROM @updateQuery;
                  EXECUTE stm2;
                  DEALLOCATE PREPARE stm2;*/
END;
-- Credit leave Rule on leave add
#
DROP PROCEDURE IF EXISTS CREDITLEAVE_ONLEAVEADD;
CREATE PROCEDURE `CREDITLEAVE_ONLEAVEADD`(IN `payrollDate` VARCHAR(20),IN current_year VARCHAR(20),IN next_yeardate VARCHAR(20),
IN leaverule_id VARCHAR(20),IN effect_from VARCHAR(20),IN allot_from CHAR(5),IN type CHAR(1),IN days_count VARCHAR(20),
IN round_off CHAR(2),IN pro_rata_basis tinyint(1),IN applicable_to CHAR(30),IN updated_by VARCHAR(20))
BEGIN
            DECLARE employee_doj_ VARCHAR(20);
            DECLARE employee_confirmation_date_ VARCHAR(20);
            DECLARE employee_id_ VARCHAR(20);
            DECLARE done_j INT DEFAULT 0;
            DECLARE cursor_j CURSOR FOR SELECT employee_doj,employee_confirmation_date,employee_id FROM vw_employees;
            DECLARE CONTINUE HANDLER FOR NOT FOUND SET done_j = TRUE;
            SET @select = concat('CREATE OR REPLACE VIEW vw_employees as SELECT w.employee_doj,w.employee_confirmation_date,w.employee_id FROM employee_work_details w INNER JOIN employee_personal_details p ON p.employee_id=w.employee_id WHERE w.enabled= 1 AND  p.employee_gender IN (',applicable_to,')');
            PREPARE stm FROM @select;
            EXECUTE stm;
            DEALLOCATE PREPARE stm;
            OPEN cursor_j;
              read_loop2:LOOP
                FETCH cursor_j INTO employee_doj_,employee_confirmation_date_,employee_id_;
                IF done_j THEN
                LEAVE read_loop2;
                ELSE
              CALL CREDIT_LEAVE(payrollDate,current_year,next_yeardate, employee_id_,
employee_doj_, employee_confirmation_date_, leaverule_id, 
effect_from, allot_from, type, days_count, round_off,
pro_rata_basis,updated_by);
               END IF;
                 END LOOP;
                 CLOSE cursor_j;
                 DROP VIEW vw_employees;
END;
#
-- Credit leave on employee add
DROP PROCEDURE IF EXISTS CREDITLEAVE_ONEMPLOYEEADD;
CREATE PROCEDURE `CREDITLEAVE_ONEMPLOYEEADD`( IN `payrollDate` VARCHAR(20),IN `current_year` VARCHAR(20), IN `next_yeardate` VARCHAR(20), IN `employee_id` VARCHAR(20), IN `employee_doj` VARCHAR(20), IN `employee_confirmation_date` VARCHAR(20), IN `employee_gender` VARCHAR(10), IN `updated_by` VARCHAR(20))
BEGIN
            DECLARE leave_rule_id_ VARCHAR(20);
            DECLARE effects_from_ VARCHAR(20);
            DECLARE allot_from_ CHAR(5);
            DECLARE type_ CHAR(2);
            DECLARE days_count_ VARCHAR(20);
            DECLARE pro_rata_basis_ tinyint(1);
            DECLARE round_off_ CHAR(2);
            DECLARE done_j INT DEFAULT 0;
            DECLARE cursor_j CURSOR FOR SELECT leave_rule_id, effects_from, allot_from, type, days_count, pro_rata_basis,round_off  FROM vw_employees;
            DECLARE CONTINUE HANDLER FOR NOT FOUND SET done_j = TRUE;
            SET @select = concat('CREATE OR REPLACE VIEW vw_employees as SELECT leave_rule_id, effects_from, allot_from, type, days_count, pro_rata_basis,round_off  FROM company_leave_rules where enabled=1 and applicable_to LIKE (',employee_gender,') ');
            PREPARE stm FROM @select;
            EXECUTE stm;
            DEALLOCATE PREPARE stm;
            OPEN cursor_j;
              read_loop2:LOOP
                FETCH cursor_j INTO leave_rule_id_, effects_from_, allot_from_, type_, days_count_, pro_rata_basis_,round_off_;
                IF done_j THEN
                LEAVE read_loop2;
                ELSE
              CALL CREDIT_LEAVE(payrollDate,current_year,next_yeardate, employee_id,
             employee_doj, employee_confirmation_date, leave_rule_id_, 
             effects_from_, allot_from_, type_, days_count_, round_off_,
            pro_rata_basis_,updated_by);
               END IF;
                 END LOOP;
                 CLOSE cursor_j;
END;
-- PT calculation
#
DROP PROCEDURE IF EXISTS CALC_PT;
CREATE PROCEDURE `CALC_PT`(IN employee_id varchar(30),IN month_year VARCHAR(10),IN fin_year int(6),IN current_gross DECIMAL(15,2),IN gross DECIMAL(15,2),IN calculate_for VARCHAR(10),IN remaining_months_count TINYINT(2),OUT pt_rate DECIMAL(15,2))
BEGIN
 DECLARE query_statement VARCHAR(500);
 DECLARE start_date DATE;
 DECLARE end_date DATE;
 DECLARE current_year int(6);
 DECLARE stringChar VARCHAR(20);
 DECLARE ptMonthMatched int(1);
 DECLARE whichHalf INT(1);
 DECLARE remaining_months_monthly INT(5);
 -- For Set recursion Time
 -- SET @@GLOBAL.max_sp_recursion_depth = 255;
 -- SET @@session.max_sp_recursion_depth = 255; 
 SET ptMonthMatched=0;
 SET current_year= SUBSTRING(fin_year,1,4);
 SET @pt_rateForit=0;
 IF SUBSTRING(month_year,1,2) IN (04,05,06,07,08,09) THEN
 SET whichHalf = 1;
 SET stringChar='04,05,06,07,08,09'; 
 SET start_date=CONCAT(current_year,'-04-01');
 SET end_date=CONCAT(current_year,'-09-01');
 ELSEIF SUBSTRING(month_year,1,2) IN (10,11,12,01,02,03) THEN
 SET whichHalf = 2;
 SET stringChar='10,11,12,01,02,03';
 SET start_date=CONCAT(current_year,'-10-01');
 SET end_date=CONCAT(current_year+1,'-03-01');
 END IF;
 WHILE stringChar != '' DO
 SET @currentValue = SUBSTRING_INDEX(stringChar,',', 1);
 IF @currentValue=SUBSTRING(month_year,1,2) THEN
 SET ptMonthMatched=1;
 END IF;
 SET stringChar = SUBSTRING(stringChar, CHAR_LENGTH(@currentValue) + 1 + 1);
 END WHILE;
 /* IF whichHalf = 1 THEN 
 SET remaining_months_monthly = 6 + remaining_months_count;
 ELSE
 SET remaining_months_monthly = remaining_months_count;
 END IF; */
 IF remaining_months_count !=0 AND remaining_months_count <= 6 THEN
    SET remaining_months_count = 6 - remaining_months_count;
  ELSEIF remaining_months_count BETWEEN 7 AND 11 THEN
    SET remaining_months_count = remaining_months_count - 6;
  ELSEIF remaining_months_count = 12 THEN 
    SET remaining_months_count = 0;
 END IF;
SET @query_statement = CONCAT('SELECT pt.rate,pt.eligibility INTO @pt_rate, @pt_eligibility FROM employee_work_details w INNER JOIN company_branch br ON w.branch_id = br.branch_id INNER JOIN pt_slab pt ON br.pt_city_id =pt.pt_city WHERE w.employee_id =\'',employee_id,'\' AND pt.from_value <= (CASE WHEN pt.eligibility=\'M\' THEN ',current_gross,' WHEN pt.eligibility = \'HY\' THEN (SELECT IFNULL(SUM(gross_salary),0)+',current_gross,'+(',gross,"*",remaining_months_count,') FROM payroll p WHERE p.month_year BETWEEN \'',start_date,'\' AND \'',end_date,'\' AND employee_id=\'',employee_id,'\') END ) AND pt.to_value >= (CASE WHEN pt.eligibility=\'M\' THEN ',current_gross,' WHEN pt.eligibility = \'HY\' THEN (SELECT IFNULL(SUM(gross_salary),0)+',current_gross,'+(',gross,"*",remaining_months_count,') FROM payroll p WHERE p.month_year BETWEEN \'',start_date,'\' AND \'',end_date,'\' AND employee_id=\'',employee_id,'\') END ) AND fin_year=\'',fin_year,'\''); 
PREPARE stm FROM @query_statement;
EXECUTE stm;
DEALLOCATE PREPARE stm;
SET pt_rate = @pt_rate;
IF (calculate_for = 'IT') THEN
 IF (@pt_eligibility = 'HY') THEN 
 IF(whichHalf = 1) THEN
 IF remaining_months_count !=0 THEN
	CALL CALC_PT(employee_id,CONCAT('10',current_year),fin_year, gross,gross,'SELF',remaining_months_count,@pt_rateForit);
 END IF;
 ELSEIF (whichHalf = 2) THEN 
 SET @query_statementIt = CONCAT('SELECT IFNULL(SUM(c_pt),0) into @pt_rateForit FROM payroll p WHERE p.month_year BETWEEN \'',current_year,'-04-01\' AND \'',current_year,'-09-31\' AND employee_id =\'',employee_id,'\'');
 PREPARE stm FROM @query_statementIt;
 EXECUTE stm;
 DEALLOCATE PREPARE stm;
 END IF;
 SELECT ROUND(pt_rate+IFNULL(@pt_rateForit,0),2) INTO pt_rate;
 ELSEIF (@pt_eligibility = 'M') THEN
 -- past months calc
 SET @query_statementIt = CONCAT('SELECT IFNULL(SUM(c_pt),0) into @pt_rateForit FROM payroll p WHERE p.month_year BETWEEN \'',current_year,'-04-01\' AND \'',SUBSTRING(month_year,3),"-",SUBSTRING(month_year,1,2) - 1,'-31\' AND employee_id =\'',employee_id,'\'');
 PREPARE stm FROM @query_statementIt;
 EXECUTE stm;
 DEALLOCATE PREPARE stm;
 SET pt_rate = pt_rate + @pt_rateForit;
 -- future months calc
 IF remaining_months_monthly != 0 THEN
 CALL CALC_PT(employee_id,month_year,fin_year, gross,gross,'SELF',remaining_months_count,@pt_rateForit);
 SET @pt_rateForit = (@pt_rateForit * remaining_months_monthly);
 SET pt_rate = pt_rate + @pt_rateForit;
 END IF;
 END IF;
 SELECT pt_rate;
ELSEIF (calculate_for = 'PAYROLL') THEN
 IF (@pt_eligibility = 'HY' AND SUBSTRING(month_year,1,2) NOT IN (03,09)) THEN
  IF(remaining_months_count!=0) THEN 
    SET pt_rate = 0; 
  END IF;
 SELECT pt_rate INTO pt_rate;
 ELSEIF @pt_eligibility = 'M' THEN
 SELECT pt_rate INTO pt_rate;
 -- SELECT pt_rate;
 END IF;
END IF;
END;
#
DROP PROCEDURE IF EXISTS CALC_EPF;
CREATE PROCEDURE `CALC_EPF`(IN `deductableAmount` DECIMAL(15,2), IN `employee_share` VARCHAR(10), IN `max_employee_share` INT(4), IN `is_both_contribution` TINYINT(1), IN `employer_share` VARCHAR(10), IN `max_employer_share` INT(4), IN `is_admin_charges` TINYINT(1), IN `admin_charges` VARCHAR(10), IN `pf_limit` TINYINT(1), IN `employee_id` VARCHAR(20), IN `month_year` VARCHAR(10), IN `fin_year` INT(6), IN `remaining_months_count` TINYINT(2), IN `calculate_for` VARCHAR(10), OUT `epfValue` DECIMAL(15,2))
BEGIN
  DECLARE fin_year_start INT(6);
  DECLARE month_start INT(6);
  DECLARE isbothconTrue TINYINT(1);
  DECLARE employee_value DECIMAL(15,2);
  DECLARE employer_value DECIMAL(15,2);
  DECLARE totalPercent DECIMAL(15,2);
  DECLARE empValFactor DECIMAL(15,2);
  DECLARE emplrValFactor DECIMAL(15,2);
  DECLARE totval DECIMAL(15,2);
   -- For Set recursion Time
 -- SET @@GLOBAL.max_sp_recursion_depth = 255;
  SET @@session.max_sp_recursion_depth = 255;
  SET fin_year_start= SUBSTRING(fin_year,1,4);
  SET @month_start = CONCAT(SUBSTRING(month_year,3,6),'-',SUBSTRING(month_year,1,2),'-01');
  SET @year = SUBSTRING(month_year,3,6);
  SET @val_epf=0;
  SET @admin_val=0;
  SET @dedu_breakup ='';
  SET epfValue=0;
  SET employee_value=0;
  SET employer_value=0;
  SET isbothconTrue=1;
  SET totalPercent = (SUBSTRING_INDEX(employee_share,'|',1));
  SET empValFactor = (SUBSTRING_INDEX(employee_share,'|',1))/totalPercent;
  SET emplrValFactor = 0;
  IF(is_both_contribution = 1) THEN
    SET totalPercent = totalPercent + (SUBSTRING_INDEX(employer_share,'|',1));
    SET empValFactor = (SUBSTRING_INDEX(employee_share,'|',1))/totalPercent;
    SET emplrValFactor = (SUBSTRING_INDEX(employer_share,'|',1))/totalPercent;
  END IF;
  IF(admin_charges =1) THEN
    SET totalPercent = totalPercent + (SUBSTRING_INDEX(admin_charges,'|',1));
    SET empValFactor = (SUBSTRING_INDEX(employee_share,'|',1))/totalPercent;
    SET emplrValFactor = ((SUBSTRING_INDEX(employer_share,'|',1)) + (SUBSTRING_INDEX(admin_charges,'|',1)))/totalPercent;
  END IF;
  
  -- IF remaining_months_count!=0 AND pf_limit != -1 THEN
          IF max_employee_share !=0 THEN
               SET employee_value = LEAST((deductableAmount*(SUBSTRING_INDEX(employee_share,'|',1))/100),max_employee_share);
          ELSEIF max_employee_share = 0 THEN
            IF pf_limit = 0 THEN
               SET employee_value = (deductableAmount*(SUBSTRING_INDEX(employee_share,'|',1))/100);
            ELSEIF pf_limit = 1 THEN 
               SET employee_value = LEAST((deductableAmount*(SUBSTRING_INDEX(employee_share,'|',1))/100),15000);
            END IF;
          END IF;
          IF is_both_contribution = 1 AND max_employer_share !=0 THEN
              SET employer_value = LEAST(((deductableAmount*(SUBSTRING_INDEX(employer_share,'|',1))/100)),max_employer_share);
          ELSEIF is_both_contribution = 1 AND max_employer_share =0 THEN
              SET employer_value =  (deductableAmount*(SUBSTRING_INDEX(employer_share,'|',1))/100);
          ELSE
             SET employer_value = 0;
          END IF;
             SET @val_epf = employee_value + employer_value;
          IF is_admin_charges = 1 THEN
              SET @admin_val=(deductableAmount * ((SUBSTRING_INDEX(admin_charges,'|',1))/100));
          ELSE
              SET @admin_val=0;
          END IF;
        SELECT ROUND(@val_epf+@admin_val) INTO epfValue;
        SET @epfvalue = epfvalue;
   IF (pf_limit != -1 && calculate_for != 'IT' ) THEN 
     SET @dedu_breakup = CONCAT('UPDATE payroll_deduction_breakup SET c_epf_emp_share=',employee_value,',c_epf_elr_share=',employer_value,',c_epf_admin_ch=',@admin_val,' WHERE month_year=\'',@month_start,'\' AND employee_id =\'',employee_id,'\'');
     PREPARE epfbreakup FROM @dedu_breakup;
     EXECUTE epfbreakup;
     DEALLOCATE PREPARE epfbreakup;
   END IF;
IF(calculate_for = 'IT') THEN
  -- Past
  IF(pf_limit != -1) THEN
	  SET @querystmtepfpast = CONCAT('SELECT  IFNULL(SUM(p.c_epf),0),(IFNULL(SUM(p.c_epf),0)*',empValFactor,'),(IFNULL(SUM(p.c_epf),0)*',emplrValFactor,')  INTO @epfPast,@epfEmployeePast,@epfEmployerPast  FROM payroll p WHERE p.month_year BETWEEN \'',fin_year_start,'-04-01\' AND \'',SUBSTRING(month_year,3),'-',SUBSTRING(month_year,1,2)-1,'-31\' AND p.employee_id =\'',employee_id,'\'');
			  PREPARE stm FROM @querystmtepfpast;
			  EXECUTE stm;
			  DEALLOCATE PREPARE stm;
	 -- present
	 SET @queryepfpresent = CONCAT('SELECT pp.c_epf,(IFNULL(SUM(pp.c_epf),0)*',empValFactor,'),(IFNULL(SUM(pp.c_epf),0)*',emplrValFactor,') INTO @epfPresent,@epfEmployeePresent,@epfEmployerPresent FROM payroll_preview_temp pp WHERE pp.employee_id =\'',employee_id,'\'');
			  PREPARE stm FROM @queryepfpresent;
			  EXECUTE stm;
			  DEALLOCATE PREPARE stm;
	 SET epfValue = (@epfPast+@epfPresent+(epfValue*remaining_months_count));
	 SET employee_value =  @epfEmployeePast +@epfEmployeePresent+(IFNULL(employee_value,0)*remaining_months_count);
	 SET employer_value = @epfEmployerPast +@epfEmployerPresent+(IFNULL(employer_value,0)*remaining_months_count);
 ELSE
     SET epfValue = 0;
     SET employee_value = 0;
     SET employer_value =0;
  END IF;
SELECT employee_id,epfValue,employee_value,employer_value;
ELSEIF (calculate_for = 'PAYROLL') THEN
   SELECT epfValue INTO epfValue;  
END IF;        
END;
#
DROP PROCEDURE IF EXISTS CALC_ARREARS;
CREATE PROCEDURE CALC_ARREARS (IN employee_id VARCHAR(30),IN current_payroll_month VARCHAR(20),IN arrear_effects_from VARCHAR(20),IN fin_year INT(6))
BEGIN
  structure_block:BEGIN     
                DECLARE paystruct_id VARCHAR(100);
                DECLARE pay_type CHAR(2);
                DECLARE done_j INT DEFAULT 0;
                DECLARE cursor_j CURSOR FOR SELECT pay_structure_id,type FROM company_pay_structure WHERE display_flag = 1;
                DECLARE CONTINUE HANDLER FOR NOT FOUND SET done_j = TRUE;
                SET @alwColumns ='';
                SET @alwVars = '';
                SET @allowCount = 0;
                SET @alwVarsMaster ='';
                SET @alwVarsCurrent ='';
                SET @dedColumns ='';
                SET @dedVars = '';
                SET @dedCount = 0;
                SET @pf_limit = 0;
                SET @epftotal = 0.00;
                SET @esitotal = 0.00;
                SET @grosstotal =0;
                OPEN cursor_j;
                      read_loop:LOOP
                        FETCH cursor_j INTO paystruct_id,pay_type;
                        IF done_j THEN
                          LEAVE read_loop;
                        END IF; 
                    IF pay_type = 'A' THEN 
                      SET @allowCount = @allowCount + 1;
                      -- comma seperated columns ,basic,hra
                      SET @alwColumns = CONCAT(@alwColumns,',',paystruct_id);
                      -- comma seperated column variables ,@basic,@hra
                      SET @alwVars = CONCAT(@alwVars,',@',paystruct_id);
                      -- comma seperated master column variables ,@m_basic,@m_hra
                      SET @alwVarsMaster = CONCAT(@alwVarsMaster,',@m_',paystruct_id);
                      -- comma seperated column variables ,@c_basic,@c_hra
                      SET @alwVarsCurrent = CONCAT(@alwVarsCurrent,',@c_',paystruct_id);
                    END IF;
                    IF pay_type = 'D' THEN
                    IF paystruct_id='c_esi' OR  paystruct_id='c_epf' THEN
                      SET @dedCount = @dedCount + 1;
                      SET @dedColumns = CONCAT(@dedColumns,',',paystruct_id);
                      SET @dedVars = CONCAT(@dedVars,',@',paystruct_id);
                      SET @e_dedVars = CONCAT(@e_dedVars,',@e_',paystruct_id);
                    END IF;
                    END IF;
                    END LOOP;
                  CLOSE cursor_j;
     END structure_block;
  allowance_block:BEGIN 
                DECLARE diffAmt DECIMAL(10,2);   
                DECLARE difftot DECIMAL(10,2);   
                DECLARE valgross_salary DECIMAL(10,2);
                DECLARE emp_lop NUMERIC(15,2);
                DECLARE valmonth_year VARCHAR(20);
                DECLARE days_count INT(2);
                DECLARE c_esi DECIMAL(10,2);
                DECLARE c_epf DECIMAL(10,2);
                DECLARE done INT DEFAULT 0;
                DECLARE cursor_a CURSOR FOR SELECT gross_salary,lop,month_year FROM vw_payroll;
                DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
                SET @stmt = CONCAT('SELECT s.employee_salary_amount,s.pf_limit,s.effects_from,w.employee_doj ',@alwColumns,' INTO @master_gross,@pf_limit,@effects_from,@emp_doj',@alwVarsMaster,' FROM employee_salary_details s INNER JOIN employee_work_details w ON s.employee_id = w.employee_id WHERE s.employee_id =\'',employee_id,'\'');
                PREPARE stm FROM @stmt;
                EXECUTE stm;
                DEALLOCATE PREPARE stm;
                SET valmonth_year = 0;
                SET @Query = CONCAT('CREATE OR REPLACE VIEW vw_payroll as SELECT p.gross_salary,p.lop,p.month_year FROM payroll p WHERE p.month_year BETWEEN DATE_FORMAT(\'',arrear_effects_from,'\',\'%Y-%m-01\') AND \'',current_payroll_month,'\'  AND  p.employee_id =\'',employee_id,'\'');
                PREPARE stm FROM @Query;
                EXECUTE stm;
                DEALLOCATE PREPARE stm;
                OPEN cursor_a;
                read_loop2:LOOP
                FETCH cursor_a INTO valgross_salary,emp_lop,valmonth_year;
                IF done THEN
                  LEAVE read_loop2;
                END IF;
                  SET @diffUpdateQuery ='';
                  SET @valgross_salary = valgross_salary;
                  -- paid salary @c_basic ,
                  SET @stmt1 =CONCAT('SELECT p.month_year ',@dedColumns,@alwColumns,' INTO @month_year ',@dedVars,@alwVarsCurrent,'  FROM payroll p WHERE p.employee_id =\'',employee_id,'\' AND p.month_year = \'',valmonth_year,'\'');
                  PREPARE stm FROM @stmt1;
                  EXECUTE stm;
                  DEALLOCATE PREPARE stm;
                  SET @diffUpdateQuery =CONCAT('INSERT INTO arrears (employee_id,month_year,processed_on ',@alwColumns,',gross_salary) VALUES (');
                  SET @diffUpdateQuery = CONCAT(@diffUpdateQuery,'\'',employee_id,'\',\'',valmonth_year,'\',\'',current_payroll_month,'\',');
                  -- find actual pay amount based on new salary
                  -- general allowances calculation
                      SET @c_str = SUBSTRING_INDEX(@alwColumns,',',-(@allowCount));
                  SET @grossSal = 0;
                  SET difftot = 0;
                  -- check if the employee joined in the middle of the month and consider days as lop
                  SET @daysDiffer=DATEDIFF(@emp_doj,@month_year);
                  IF(@daysDiffer > 0 ) THEN
                    SET emp_lop = emp_lop + @daysDiffer;
                  END IF;
                  SET days_count = DATEDIFF(LAST_DAY(@month_year),@month_year)+1;
                  SET @count = days_count;
                     SET @workingDays = (days_count - emp_lop)/days_count ;
                      WHILE @c_str != '' DO
                          SET @allowAmount = 0;
                    SET @currentValue = SUBSTRING_INDEX(@c_str, ',', 1);
                          SET @allowQuerySub = CONCAT('SET @currentAmt = @m_',@currentValue);
                          PREPARE stm FROM @allowQuerySub;
                          EXECUTE stm;
                          DEALLOCATE PREPARE stm;
                          SET @allowAmount = @currentAmt * @workingDays;
                          SET @allowAmount = ROUND(@allowAmount,2);
                       SET @grossSal = @grossSal + @allowAmount;
                    SET @allowQuerySub = CONCAT('SET @oldAmtPaid = @c_',@currentValue);
                          PREPARE stm FROM @allowQuerySub;
                          EXECUTE stm;
                          DEALLOCATE PREPARE stm;
                    SET diffAmt = @allowAmount - @oldAmtPaid;
                    SET difftot = difftot + diffAmt;
                    SET @difftot = difftot;
                    SET @diffUpdateQuery = CONCAT(@diffUpdateQuery,diffAmt,',');
                          SET @c_str = SUBSTRING(@c_str, CHAR_LENGTH(@currentValue) + 1 + 1);
                          SET @allowQuerySub = CONCAT('SET @c_',@currentValue,'= @allowAmount');
                          PREPARE stm FROM @allowQuerySub;
                          EXECUTE stm;
                          DEALLOCATE PREPARE stm;
                      END WHILE;
                  SET @diffUpdateQuery = CONCAT(@diffUpdateQuery,difftot,') ON DUPLICATE KEY UPDATE employee_id=\'',employee_id,'\' ');
                  SET @grosstotal = @grosstotal + @grossdiff;
                  PREPARE stm FROM @diffUpdateQuery;
                    EXECUTE stm;
                    DEALLOCATE PREPARE stm;
                 -- Deductions
  deduction_block:BEGIN
                DECLARE deduction_id_var VARCHAR(40);
                DECLARE alias_name_var VARCHAR(10);
                DECLARE is_both_contribution_var INT;
                DECLARE is_admin_charges_var INT;
                DECLARE employee_share_var VARCHAR(20);
                DECLARE employer_share_var VARCHAR(20);
                DECLARE admin_charges_var VARCHAR(20);
                DECLARE deduce_in_var VARCHAR(100);
                DECLARE payment_to_var VARCHAR(20);
                DECLARE frequency_var CHAR(2);
                DECLARE due_in_var VARCHAR(20);
                DECLARE max_employee_share_var NUMERIC(15,2);
                DECLARE max_employer_share_var NUMERIC(15,2);
                DECLARE cal_exemption_var NUMERIC(15,2);
                DECLARE deductRate NUMERIC(15,2) DEFAULT 0.00;
                DECLARE epf_diff DECIMAL(10,2);
                DECLARE esi_diff DECIMAL(10,2);
                DECLARE pf_limits VARCHAR(2);
                DECLARE start_date DATE;
                DECLARE end_date DATE;
                DECLARE current_year int(6);
                DECLARE done_k INT DEFAULT 0;
                DECLARE cursor_k CURSOR FOR SELECT p.pay_structure_id,IFNULL(company_deductions.alias_name,'AliasName'),IFNULL(is_both_contribution,0),IFNULL(is_admin_charges,0),IFNULL(employee_share,0), IFNULL(employer_share,0), IFNULL(admin_charges,0),IFNULL(deduce_in,'Nil'), IFNULL(payment_to,'Nil'), IFNULL(frequency,'M'), IFNULL(due_in,'Nil'),IFNULL(max_employee_share,0),IFNULL(max_employer_share,0), IFNULL(cal_exemption,0) FROM company_deductions RIGHT JOIN company_pay_structure p ON p.pay_structure_id = company_deductions.deduction_id WHERE p.display_flag = 1 AND p.type = 'D' AND p.pay_structure_id !='c_it' AND p.pay_structure_id !='c_pt'  ORDER BY p.display_name DESC;
                DECLARE CONTINUE HANDLER FOR NOT FOUND SET done_k = TRUE;
              SET deductRate =0.00;
              SET @totalDedAmt = 0.00;
              SET @total_deduction = 0.00;
              SET @totepf = 0.00;
              SET @arrearsUpdateQuery='UPDATE arrears SET ';
              SET current_year= SUBSTRING(fin_year,1,4);
              IF DATE_FORMAT(current_payroll_month,'%m') IN (04,05,06,07,08,09) THEN
                SET start_date=CONCAT(current_year,'-04-01');
                SET end_date=CONCAT(current_year,'-09-01');
              ELSEIF DATE_FORMAT(current_payroll_month,'%m') IN (10,11,12,01,02,03) THEN
                SET start_date=CONCAT(current_year,'-10-01');
                SET end_date=CONCAT(current_year+1,'-03-01');
              END IF;
              OPEN cursor_k;
              read_loop1:LOOP
              -- deductions loop
              FETCH cursor_k INTO deduction_id_var, alias_name_var, is_both_contribution_var, is_admin_charges_var, employee_share_var, employer_share_var, admin_charges_var, deduce_in_var, payment_to_var, frequency_var, due_in_var, max_employee_share_var, max_employer_share_var, cal_exemption_var;
              IF done_k THEN
                LEAVE read_loop1;
              END IF;
             SET @totalMasterAmt = 0.00;
             SET @mastAmt = 0.00;
             SET pf_limits = @pf_limit;
            WHILE deduce_in_var != '' > 0 DO
                -- deduce in loop
                    SET @currentValue = SUBSTRING_INDEX(deduce_in_var, ',', 1);
                  IF @currentValue = 'GROSS' THEN
                      SET @mastAmt = @master_gross;
                      SET @dedAmt =  difftot; 
                  ELSE
                    -- master deduce in total cal
                      SET @allowQuerySub1 = CONCAT('SET @mastAmt = @m_',@currentValue);
                      PREPARE stm FROM @allowQuerySub1;
                      EXECUTE stm;
                      DEALLOCATE PREPARE stm;
                    -- current ded val
                      SET @allowQuerySub1 = CONCAT('SET @dedAmt = @c_',@currentValue);
                      PREPARE stm FROM @allowQuerySub1;
                      EXECUTE stm;
                      DEALLOCATE PREPARE stm;
                  END IF;
                    SET @totalMasterAmt = @totalMasterAmt + @mastAmt; -- Totol Grosss For this Month minus From lop
                    SET @totalDedAmt = @totalDedAmt + @dedAmt; -- deduce in like(BAsic+hra+medical) minus from lop
                    SET deduce_in_var = SUBSTRING(deduce_in_var, CHAR_LENGTH(@currentValue) + 1 + 1);
              SET @deduce_in_var = deduce_in_var;
                END WHILE;
                 -- check cal_excemption
           IF cal_exemption_var =0 OR cal_exemption_var !=0  AND deduction_id_var != 'c_pt' THEN
                      -- check deduction_id
                  IF deduction_id_var = 'c_epf'  THEN
                    IF pf_limits = -1 THEN
                      SET epf_diff = 0.00;
                    ELSE
                      -- Procedure Call For Find EPF
                        CALL CALC_EPF(@totalDedAmt,employee_share_var,max_employee_share_var,is_both_contribution_var,
                        employer_share_var,max_employer_share_var,is_admin_charges_var,admin_charges_var,@pf_limit,employee_id,
                        valmonth_year,fin_year,1,'PAYROLL',@totepf);
                      SET epf_diff = @totepf - @c_epf;
                      SET @epftotal = @epftotal + epf_diff;
                      SET @total_deduction=@total_deduction + @totepf;
                    END IF;
                    SET @arrearsUpdateQuery=CONCAT(@arrearsUpdateQuery,'c_epf=',epf_diff);
                  
                  ELSEIF deduction_id_var = 'c_esi'  THEN     -- Continue if Deduction id NOt EPF get ESI Value
                   SET @stmt = CONCAT('SELECT IFNULL(SUM(c_esi),0) INTO @previous_esi FROM payroll p WHERE p.month_year BETWEEN \'',start_date,'\' AND \'',end_date,'\' AND p.employee_id =\'',employee_id,'\'');
                       PREPARE stm FROM @stmt;
                       EXECUTE stm;
                       DEALLOCATE PREPARE stm;
                      IF @totalMasterAmt <= cal_exemption_var AND is_both_contribution_var = 1 THEN
                         SET @val_esi=@dedAmt * ((SUBSTRING_INDEX(employee_share_var,'|',1))/100)+@dedAmt * ((SUBSTRING_INDEX(employer_share_var,'|',1))/100);
                			ELSEIF @totalMasterAmt <= cal_exemption_var AND is_both_contribution_var = 0 THEN
                					SET @val_esi=@dedAmt * ((SUBSTRING_INDEX(employee_share_var,'|',1))/100);
                			END IF;
                      IF @totalMasterAmt > cal_exemption_var AND @previous_esi >0 AND is_both_contribution_var = 1 THEN
                          SET @val_esi= cal_exemption_var *((SUBSTRING_INDEX(employee_share_var,'|',1))/100)+ cal_exemption_var * ((SUBSTRING_INDEX(employer_share_var,'|',1))/100);
                      ELSEIF @totalMasterAmt > cal_exemption_var AND @previous_esi >0 AND is_both_contribution_var =0  THEN
                          SET @val_esi= cal_exemption_var * ((SUBSTRING_INDEX(employee_share_var,'|',1))/100);
                      ELSEIF @totalMasterAmt > cal_exemption_var AND @previous_esi = 0 THEN  
                          SET @val_esi=0;
                      END IF;
                         SET esi_diff = @val_esi - @c_esi;
                         SET @esitotal = @esitotal + esi_diff;
                         SET @total_deduction=@total_deduction + @esitotal;
                         SET @arrearsUpdateQuery=CONCAT(@arrearsUpdateQuery,'c_esi=',esi_diff,',');
                                 END IF;
                END IF;
                END LOOP;
                SET @arrearsUpdateQuery=CONCAT(@arrearsUpdateQuery,' WHERE employee_id =\'',employee_id,'\' AND month_year = \'',valmonth_year,'\'');
                IF(deduce_in_var != '') THEN
                    PREPARE stm FROM @arrearsUpdateQuery;
                    EXECUTE stm;
                    DEALLOCATE PREPARE stm;
                END IF;
                  CLOSE cursor_k;
  END deduction_block; 
           END LOOP;
  END allowance_block;
END;
#
DROP PROCEDURE IF EXISTS CALC_ALW_DED_PT;
CREATE PROCEDURE `CALC_ALW_DED_PT`(IN `payroll_for` CHAR(1), IN `affected_ids` TEXT(65535), IN `month_year` VARCHAR(10), IN `st_date` VARCHAR(20), IN `fin_year` INT(10), IN `days_count` INT(10), IN `salaryDay` VARCHAR(5), IN `isFnF` TINYINT(1), IN `ForceRun` TINYINT(1), IN `performed_by` VARCHAR(20), OUT `affected` CHAR(20), OUT `allColumns` VARCHAR(5000))
BEGIN
-- Executes only once
  block2:BEGIN
            DECLARE paystruct_id VARCHAR(100);
            DECLARE pay_type CHAR(2);
            DECLARE e_date VARCHAR(20);
            DECLARE done_j INT DEFAULT 0;
            DECLARE cursor_j CURSOR FOR SELECT pay_structure_id,type FROM company_pay_structure WHERE display_flag = 1 UNION ALL SELECT leave_rule_id,'L' type FROM company_leave_rules WHERE enabled=1;
            DECLARE CONTINUE HANDLER FOR NOT FOUND SET done_j = TRUE;
            SET @dedColumns1 ='';
            SET @e_dedVars =''; 
            SET @dedVars = '';
            SET @dedCount = 0;
            SET @columns1 ='';
            SET @columns2 ='';
            SET @e_vars ='';
            SET @arrersColums ='';
            SET @e_arrersColums ='';
            SET @vars = '';
            SET @sumvars ='';
            SET @payvars ='';
            SET @allowCount = 0;
            SET @queryAll ='';
            SET @pf_limit = 0;
            SET @miscalwCount = 0;
            SET @miscdedCount = 0;
            SET @miscalwcolumns = '';
            SET @miscdedcolumns = '';
            SET @dayDiff=0;
            SET @leavestmt = '';
            SET e_date=IF(DATE_FORMAT(st_date,'%d') = '01',LAST_DAY(st_date),DATE_ADD(DATE_SUB(st_date,INTERVAL 1 DAY), INTERVAL 1 MONTH));
            
            OPEN cursor_j;
              read_loop2:LOOP
                FETCH cursor_j INTO paystruct_id,pay_type;
                IF done_j THEN
                  LEAVE read_loop2;
                END IF;
                IF pay_type = 'A' THEN
                  SET @allowCount = @allowCount + 1;
                  SET @columns1 = CONCAT(@columns1,',',paystruct_id);
                  SET @sumvars = CONCAT(@sumvars,',SUM(',paystruct_id,')');
                  SET @payvars = CONCAT(@payvars,',IF(new_join=1,',paystruct_id,'*((DATEDIFF(effects_upto,effects_from)+1)/(DATEDIFF(\'',e_date,'\',\'',st_date,'\')+1)),',paystruct_id,') ',paystruct_id);
                  -- SET @payvars = CONCAT(@payvars,',',paystruct_id,'*',@dayDiff,'/',days_count,' ',paystruct_id);
                  SET @columns2 = CONCAT(@columns2,',s.',paystruct_id);
                  SET @e_vars = CONCAT(@e_vars,',@e_',paystruct_id);
                  SET @vars = CONCAT(@vars,',@',paystruct_id);
                END IF;
                
                IF pay_type = 'D' THEN
                  IF paystruct_id = 'c_esi' OR paystruct_id = 'c_epf' THEN
                     SET @arrersColums = CONCAT(@arrersColums,',SUM(',paystruct_id,')');
                     SET @e_arrersColums=CONCAT(@e_arrersColums,',@e_',paystruct_id);
                   END IF;
                  SET @dedCount = @dedCount + 1;
                  SET @dedColumns1 = CONCAT(@dedColumns1,',',paystruct_id);
                  SET @e_dedVars = CONCAT(@e_dedVars,',@e_',paystruct_id);
                  SET @dedVars = CONCAT(@dedVars,',@',paystruct_id);
                END IF;
                IF pay_type = 'MP' THEN
                  SET @miscalwCount = @miscalwCount +1;
                  -- SET @miscalwcolumns = CONCAT(@miscalwcolumns,paystruct_id,',');
                END IF;
                IF pay_type = 'MD' THEN
                  SET @miscdedCount = @miscdedCount +1;
                  SET @miscdedcolumns = CONCAT(@miscdedcolumns,paystruct_id,',');
                END IF;
                IF pay_type = 'L' THEN
                  SET @leavestmt = CONCAT(@leavestmt,'pp.',paystruct_id,'+');
                END IF;
              END LOOP;
              SET @sumvars = TRIM(LEADING '+' FROM (CONCAT(REPLACE(@sumvars,',','+'),@sumvars)));
            CLOSE cursor_j;
          END block2;
	-- Executes only once
block0:BEGIN
    DECLARE emp_doj VARCHAR(20);
    DECLARE emp_id VARCHAR(20);
    DECLARE desi_id VARCHAR(20);
    DECLARE depar_id VARCHAR(20);
    DECLARE bran_id VARCHAR(20);
    DECLARE emp_name VARCHAR(50);
    DECLARE emp_lop NUMERIC(15,2);
    DECLARE other_leave NUMERIC(15,2);
    DECLARE np_process_type CHAR(2);
    DECLARE np_status CHAR(2);
    DECLARE np_notice_date VARCHAR(20);
    DECLARE np_last_working_date VARCHAR(10);
    DECLARE 2x_count NUMERIC(15,2); 
    DECLARE current_year int(6);
    DECLARE e_date VARCHAR(20);
    DECLARE done boolean default false;
    -- End Of Employee Decalration
    DECLARE pay_affected_ids_var VARCHAR(500);
    DECLARE miscPaymentString VARCHAR(500);
    DECLARE miscPaymentQueryString VARCHAR(500);
  	DECLARE misPayapplicabledate VARCHAR(10);
  	DECLARE misPayDate VARCHAR(10);
  	DECLARE TotPayDate VARCHAR(10);
  	DECLARE currentMOnthDate VARCHAR(10);
  	DECLARE payment_id_var VARCHAR(10);
  	DECLARE payment_amount_var VARCHAR(10);
  	DECLARE payments_in_var VARCHAR(100);
  	DECLARE repetition_count_var int(10);
  	DECLARE effects_from_var VARCHAR(20);
    DECLARE effects_upto_var VARCHAR(20);
	  DECLARE pay_category_var VARCHAR(20); 
    DECLARE enabled_var VARCHAR(2);
     -- End Of MIcpayment Decalration
		DECLARE miscDeductionQueryString VARCHAR(200);
	  DECLARE dedu_affected_ids_var VARCHAR(500);
    DECLARE deduction_id_var VARCHAR(10);
    DECLARE misDeduDate VARCHAR(10);
    DECLARE TotDeduDate VARCHAR(10);
    DECLARE misDeduApplicble VARCHAR(10);
  	DECLARE deduction_amount_var VARCHAR(10);
  	DECLARE deductions_in_var VARCHAR(100);
  	DECLARE ded_repetition_count_var int(10);
  	DECLARE ded_effects_from_var VARCHAR(20);
    DECLARE ded_effects_upto_var VARCHAR(20);
  	DECLARE dedu_category_var VARCHAR(20);
    DECLARE lastMonthCondition VARCHAR(300);
     -- End Of MIcdeduction Decalration
     
-- MIsc Deduction Cursor
    DECLARE cursor_kMiscdedu CURSOR FOR SELECT  dedu_affected_ids,deduction_id, deduction_amount, deductions_in,repetition_count,effects_from,dedu_category,effects_upto,enabled FROM miscDeducView;
-- Select Employee Cursor
	  DECLARE cursor_i CURSOR FOR SELECT employee_doj,designation_id,department_id,branch_id,employee_id,employee_name,lop,other_leave,process_type,last_working_date,status,notice_date,2x_sal FROM vw_employees WHERE IF (ForceRun = 0, ( status_flag != 'P'), (status_flag IN('P','A','D','MD','MP'))) ;
-- MIsc Allowances Cursor
    DECLARE cursor_kMiscpay CURSOR FOR SELECT  pay_affected_ids,payment_id, payment_amount, payments_in,repetition_count,effects_from,pay_category,effects_upto,enabled FROM miscPayView;
		DECLARE CONTINUE HANDLER FOR NOT FOUND SET done := TRUE;
    
    SET current_year= SUBSTRING(month_year,3,6);
    SET e_date=IF(DATE_FORMAT(st_date,'%d') = '01',LAST_DAY(st_date),DATE_ADD(DATE_SUB(st_date,INTERVAL 1 DAY), INTERVAL 1 MONTH));
    SET @mon_date= DATE_FORMAT(e_date,'%Y-%m-01');
    
    
    SET lastMonthCondition='';
    IF isFnF = 0 THEN
      SET lastMonthCondition =CONCAT(' WHERE np.last_working_date NOT  BETWEEN  \'',st_date,'\' AND \'',st_date,'\' AND  np.last_working_date > \'',e_date,'\' OR ( np.last_working_date  BETWEEN  \'',st_date,'\' AND \'',e_date,'\' AND np.status!=\'A\') OR (np.last_working_date IS NULL) GROUP BY w.employee_id;'); 
    END IF;
    -- Query to Retrirve Employees and Data 
    IF payroll_for = 'E' THEN
      SET @select = CONCAT('CREATE OR REPLACE VIEW vw_employees as SELECT w.employee_id,w.employee_doj,w.designation_id,w.department_id,w.branch_id,w.employee_name,(pp.lop+pp.alop+pp.late_lop) lop,(',@leavestmt,'pp.other_leave) other_leave,pp.gross_salary,pp.total_deduction,pp.status_flag,np.last_working_date,np.process_type,np.status,np.notice_date,IFNULL(SUM(cr.day_count),0) 2x_sal FROM employee_work_details w INNER JOIN  payroll_preview_temp pp ON  w.employee_id = pp.employee_id AND w.employee_id IN (',affected_ids,')  AND w.enabled = 1 AND w.payroll_enabled = 1 AND pp.is_processed = 0 LEFT JOIN  emp_notice_period np ON pp.employee_id = np.employee_id LEFT JOIN compensation_requests cr ON pp.employee_id = cr.employee_id AND cr.status="2X" AND cr.is_processed="0" ' ,lastMonthCondition);
    END IF;
    IF payroll_for = 'D' THEN
      SET @select = CONCAT('CREATE OR REPLACE VIEW vw_employees as SELECT w.employee_id,w.employee_doj,w.designation_id,w.department_id,w.branch_id,w.employee_name,pp.lop,pp.gross_salary,pp.total_deduction,pp.status_flag,np.last_working_date,np.process_type,np.status,np.notice_date,IFNULL(SUM(cr.day_count),0) 2x_sal FROM employee_work_details w INNER JOIN  payroll_preview_temp pp ON  w.employee_id = pp.employee_id AND w.designation_id IN (',affected_ids,')  AND w.enabled = 1 AND w.payroll_enabled = 1 AND pp.is_processed = 0 LEFT JOIN  emp_notice_period np ON pp.employee_id = np.employee_id LEFT JOIN compensation_requests cr ON pp.employee_id = cr.employee_id AND cr.status="2X" AND cr.is_processed="0"  WHERE np.last_working_date NOT  BETWEEN   \'',st_date,'\' AND \'',e_date,'\'  AND  np.last_working_date > \'',e_date,'\'   OR ( np.last_working_date  BETWEEN   \'',st_date,'\' AND \'',e_date,'\'  AND np.status!=\'A\') OR (np.last_working_date IS NULL) GROUP BY w.employee_id;');
    END IF;
    IF payroll_for = 'F' THEN
      SET @select = CONCAT('CREATE OR REPLACE VIEW vw_employees as SELECT w.employee_id,w.employee_doj,w.designation_id,w.department_id,w.branch_id,w.employee_name,pp.lop,pp.gross_salary,pp.total_deduction,pp.status_flag,np.last_working_date,np.process_type,np.status,np.notice_date,IFNULL(SUM(cr.day_count),0) 2x_sal FROM employee_work_details w INNER JOIN  payroll_preview_temp pp ON  w.employee_id = pp.employee_id AND w.department_id IN (',affected_ids,')  AND w.enabled = 1 AND w.payroll_enabled = 1 AND pp.is_processed = 0 LEFT JOIN  emp_notice_period np ON pp.employee_id = np.employee_id LEFT JOIN compensation_requests cr ON pp.employee_id = cr.employee_id AND cr.status="2X" AND cr.is_processed="0" WHERE np.last_working_date NOT  BETWEEN   \'',st_date,'\' AND \'',e_date,'\'  AND  np.last_working_date > \'',e_date,'\'   OR ( np.last_working_date  BETWEEN   \'',st_date,'\' AND \'',e_date,'\'  AND np.status!=\'A\') OR (np.last_working_date IS NULL) GROUP BY w.employee_id;');
    END IF;
    IF payroll_for = 'B' THEN
      SET @select = CONCAT('CREATE OR REPLACE VIEW vw_employees as SELECT w.employee_id,w.employee_doj,w.designation_id,w.department_id,w.branch_id,w.employee_name,pp.lop,pp.gross_salary,pp.total_deduction,pp.status_flag,np.last_working_date,np.process_type,np.status,np.notice_date,IFNULL(SUM(cr.day_count),0) 2x_sal FROM employee_work_details w INNER JOIN  payroll_preview_temp pp ON  w.employee_id = pp.employee_id AND w.branch_id IN (',affected_ids,')  AND w.enabled = 1 AND w.payroll_enabled = 1 AND pp.is_processed = 0 LEFT JOIN  emp_notice_period np ON pp.employee_id = np.employee_id LEFT JOIN compensation_requests cr ON pp.employee_id = cr.employee_id AND cr.status="2X" AND cr.is_processed="0" WHERE np.last_working_date NOT  BETWEEN   \'',st_date,'\' AND \'',e_date,'\'  AND  np.last_working_date > \'',e_date,'\'   OR ( np.last_working_date  BETWEEN   \'',st_date,'\' AND \'',e_date,'\'  AND np.status!=\'A\') OR (np.last_working_date IS NULL) GROUP BY w.employee_id;');
    END IF;
    PREPARE stm FROM @select;
    EXECUTE stm;
    DEALLOCATE PREPARE stm;
  OPEN cursor_i;
      read_loop:LOOP -- Each Employee Will Loop Here
        FETCH cursor_i INTO emp_doj,desi_id,depar_id,bran_id,emp_id,emp_name,emp_lop,other_leave,np_process_type,np_last_working_date,np_status,np_notice_date,2x_count;
        IF done THEN
        CLOSE cursor_i;
          LEAVE read_loop;
        END IF;
       SET @id=emp_id;
        -- SET @miscPayView = CONCAT('CREATE OR REPLACE VIEW miscPayView AS SELECT  pay_affected_ids,payment_id, payment_amount, payments_in,  repetition_count,effects_from,pay_category,effects_upto FROM misc_payments where enabled=1 AND effects_upto >=\'',@monthDate,'\'');
        SET @miscPayView = CONCAT('CREATE OR REPLACE VIEW miscPayView AS SELECT  pay_affected_ids,payment_id, payment_amount, payments_in,  repetition_count,effects_from,pay_category,effects_upto,enabled FROM misc_payments WHERE  pay_affected_ids = (CASE WHEN payment_for = \'E\' THEN \'',emp_id,'\' WHEN payment_for = \'D\'  THEN \'',desi_id,'\' WHEN payment_for = \'F\' THEN \'',depar_id,'\' WHEN payment_for = \'B\' THEN \'',bran_id,'\'  END) AND effects_from <=\'',@mon_date,'\'  AND effects_upto >=\'',@mon_date,'\'');
        PREPARE stm4 FROM @miscPayView;
    		EXECUTE stm4;
    		DEALLOCATE PREPARE stm4;
        SET @miscDedcView = CONCAT('CREATE OR REPLACE VIEW miscDeducView AS SELECT  dedu_affected_ids,deduction_id, deduction_amount, deductions_in,  repetition_count,effects_from,dedu_category,effects_upto,enabled FROM misc_deduction WHERE dedu_affected_ids = (CASE WHEN deduction_for = \'E\' THEN \'',emp_id,'\' WHEN deduction_for = \'D\'  THEN \'',desi_id,'\' WHEN deduction_for = \'F\' THEN \'',depar_id,'\' WHEN deduction_for = \'B\' THEN \'',bran_id,'\'  END) AND effects_from <=\'',@mon_date,'\'  AND effects_upto >=\'',@mon_date,'\'');
      	PREPARE stm FROM @miscDedcView;
      	EXECUTE stm;
      	DEALLOCATE PREPARE stm;
        
		IF(salaryDay='wd') THEN
        -- calculating the no of hoidays b/w the st_date and e_date without considaration of weekends(i.e Saturday,Sunday)
        SET @getholidays = CONCAT('SELECT IFNULL(SUM(CASE WHEN end_date > start_date THEN  (5* (DATEDIFF(end_date, start_date) DIV 7) + MID(\'1234555512344445123333451222234511112345001234550\', 7 * WEEKDAY(start_date) + WEEKDAY(end_date)+1 , 1)) ELSE 0 END),0) holidays INTO @holiday_count FROM holidays_event h WHERE start_date BETWEEN \'',st_date,'\' AND \'',e_date,'\' AND end_date BETWEEN \'',st_date,'\' AND \'',e_date,'\' AND ((category = \'HOLIDAY\' AND branch_id =\'NA\') OR (category=\'OPTIONAL\' AND branch_id =\'',bran_id,'\'));');
        
        -- calculating the no of hoidays b/w the st_date and e_date with weekends(i.e Saturday,Sunday)
        -- SET @getholidays = CONCAT('SELECT IFNULL(SUM(CASE WHEN ((dates NOT BETWEEN start_date AND end_date) AND (weeks = IF((WEEK(dates) - WEEK(DATE_FORMAT(dates , \'%Y-%m-01\')) + 1)>5,(WEEK(dates) - WEEK(DATE_FORMAT(dates , \'%Y-%m-01\')) - 1),WEEK(dates) - WEEK(DATE_FORMAT(dates , \'%Y-%m-01\')) + 1))) THEN (CASE WHEN (DAYNAME(dates)=\'sunday\') THEN IF((sunday = \'FH\' OR sunday = \'SH\'),0.5,IF(sunday = \'FD\',1,0)) WHEN (DAYNAME(dates)=\'Monday\') THEN IF((monday = \'FH\' OR monday = \'SH\'),0.5,IF(monday = \'FD\',1,0)) WHEN (DAYNAME(dates)=\'Tuesday\') THEN IF((tuesday = \'FH\' OR tuesday = \'SH\'),0.5,IF(tuesday = \'FD\',1,0)) WHEN (DAYNAME(dates)=\'Wednesday\') THEN IF((wednesday = \'FH\' OR wednesday = \'SH\'),0.5,IF(wednesday = \'FD\',1,0)) WHEN (DAYNAME(dates)=\'Thursday\') THEN IF((thursday = \'FH\' OR thursday = \'SH\'),0.5,IF(thursday = \'FD\',1,0)) WHEN (DAYNAME(dates)=\'Friday\') THEN IF((friday = \'FH\' OR friday = \'SH\'),0.5,IF(friday = \'FD\',1,0)) WHEN (DAYNAME(dates)=\'Saturday\') THEN IF((saturday = \'FH\' OR saturday = \'SH\'),0.5,IF(saturday = \'FD\',1,0)) ELSE \'\' END) WHEN ((dates BETWEEN start_date AND end_date) AND (weeks=(WEEK(dates) - WEEK(DATE_FORMAT(dates , \'%Y-%m-01\'))))) THEN (CASE WHEN (category = \'OPTIONAL\' AND holBranch = empBranch) THEN 1 WHEN (category = \'HOLIDAY\' AND holBranch = \'NA\') THEN 1 ELSE \'\' END) ELSE \'\' END),0) \'holidays\' FROM ( SELECT  DATE_FORMAT(dates,\'%Y-%m-%d\') dates,z.shift_id,from_date,to_date,empBranch,start_date,end_date,category,holBranch,weeks,sunday,monday,tuesday,wednesday,thursday,friday,saturday FROM ( SELECT IF(r.shift_id IS NULL,IF(w.shift_id=\'Nil\' OR w.shift_id = \'\',\'SH00001\',w.shift_id),r.shift_id) shift_id,IF(r.shift_id IS NULL,\'',st_date,'\',IF(r.to_date IS NULL AND r.from_date <\'',st_date,'\',\'',e_date,'\',DATE_FORMAT(r.from_date,\'%Y-%m-%d\'))) from_date,IFNULL(r.to_date,\'',e_date,'\') to_date, IFNULL(d.status,0) device_status,w.branch_id empBranch,IFNULL(h.start_date,\'0000-00-00\') start_date,IFNULL(h.end_date,\'0000-00-00\') end_date,h.category,h.branch_id holBranch FROM employee_work_details w LEFT JOIN device_users d  ON w.employee_id = d.employee_id LEFT JOIN  shift_roaster r ON r.employee_id = w.employee_id AND (r.from_date BETWEEN \'',st_date,'\' AND \'',e_date,'\' OR r.from_date < \'',st_date,'\') AND (r.to_date BETWEEN \'',st_date,'\' AND \'',e_date,'\' OR r.to_date IS NULL) LEFT JOIN holidays_event h ON (h.start_date BETWEEN \'',st_date,'\' AND \'',e_date,'\') WHERE w.employee_id=\'',emp_id,'\' AND w.enabled=1 ) z LEFT JOIN company_shifts s ON z.shift_id = s.shift_id LEFT JOIN weekend w ON z.shift_id = w.shift_id LEFT JOIN dates d ON dates BETWEEN from_date AND to_date )q;');

        PREPARE stmt FROM @getholidays;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
      -- Subtract the holidays in days_count.
      SET days_count = days_count - @holiday_count;
    END IF;
    
    SET @total_deduction=0;            
		SET @payrollUpdateQuery ='UPDATE payroll_preview_temp SET ';
		-- set process_type =1 For Whoever Got noticeperiod type is settlement
    -- For settlement before lastworking date hit made isa-payable as 1     
    IF np_last_working_date != '' AND np_process_type= 'S'  AND np_last_working_date <= e_date  AND np_status='A' THEN
      SET @payrollUpdateQuery = CONCAT(@payrollUpdateQuery,'is_pay_pending=1,');
    END IF;
    IF((np_last_working_date != '' AND np_process_type= 'S'  AND np_last_working_date BETWEEN st_date AND e_date  AND np_status='A' ) OR (np_last_working_date != '' AND np_process_type= 'P'  AND np_status='A' AND np_last_working_date BETWEEN st_date AND e_date)) THEN
      IF(salaryDay='ad') THEN
        -- If salaryDay is actualDay then calculate the no of days remaining after last_working_days and assume it as Lop
        SET emp_lop=emp_lop + DATEDIFF(e_date,np_last_working_date);
      ELSEIF(salaryDay='wd') THEN
        -- If salaryDay is weekDay then calculate no of days b/w attn_stDate and last_woking_Dt and minus the weekends b/w the dates as working days then minus from days_count. i.e 20(days_count) - 6(working_days) = 14 as Lop.
        SET emp_lop=emp_lop + (days_count - (5* (DATEDIFF(np_last_working_date, st_date) DIV 7) + MID('1234555512344445123333451222234511112345001234550', 7 * WEEKDAY(st_date) + WEEKDAY(np_last_working_date) +1, 1)));
      ELSE
        -- If SalaryDay = 30 or 31 then calculate the no of working days and minus from days_count(30 or 31) i.e 30(days_count) - 2(working_days) = 28 as Lop
        SET emp_lop=emp_lop + (days_count - (DATEDIFF(DATE_ADD(np_last_working_date,INTERVAL 1 DAY),st_date)));
      END IF;
    END IF;
    
    -- Calc Remaining Months
    -- For notice period employees have to deduct PT so remaining month assumed to 0
    IF np_last_working_date != '' AND (st_date <= np_last_working_date AND e_date >= np_last_working_date)  AND np_status='A' THEN
      SET @remaining_months= 0;
     ELSE 
      -- Check first two numbers of month_year is < 04, if it is so month_year comes to next finyear i.e no need to add a year in PERIOD_DIFF
      SET @remaining_months = PERIOD_DIFF(IF(SUBSTRING(month_year,1,2)>03,CONCAT((SUBSTRING(month_year,3,6)+1),'03'),CONCAT(SUBSTRING(month_year,3,6),'03')),DATE_FORMAT(e_date,'%Y%m'));
    END IF;
    SET @payvars = TRIM(LEADING ',' FROM @payvars);
    -- Query to retrive salary details of employee
    -- SET @salaryDetailQuery = CONCAT('SELECT employee_salary_amount ',@columns1,',pf_limit,esi_limit INTO @grossSalAmt ',@e_vars, ',@pf_limit,@esi_limit FROM employee_salary_details WHERE employee_id =\'',emp_id,'\'');
    -- SET @salaryDetailQuery = CONCAT('SELECT ',@sumvars,',pf_limit,esi_limit INTO @grossSalAmt ',@e_vars, ',@pf_limit,@esi_limit FROM (SELECT ',@payvars,',pf_limit,esi_limit FROM (SELECT employee_salary_amount ',@columns1,',pf_limit,esi_limit,IF(effects_from<\'',st_date,'\',\'',st_date,'\',effects_from) effects_from,\'',e_date,'\' effects_upto FROM employee_salary_details WHERE employee_id =\'',emp_id,'\' UNION SELECT employee_salary_amount',@columns1,',\'\',\'\',IF(effects_from<\'',st_date,'\',\'',st_date,'\',effects_from) effects_from,effects_upto FROM employee_salary_details_history WHERE employee_id =\'',emp_id,'\' AND NOT (effects_from > \'',e_date,'\' OR effects_upto < \'',st_date,'\'))t)q;');
    SET @salaryDetailQuery = CONCAT('SELECT ',@sumvars,',pf_limit,esi_limit,slab_id INTO @grossSalAmt ',@e_vars, ',@pf_limit,@esi_limit,@slab_id FROM (SELECT ',@payvars,',pf_limit,esi_limit,slab_id FROM (SELECT employee_salary_amount ',@columns1,',pf_limit,esi_limit,slab_id,employee_doj,IF(employee_doj NOT BETWEEN \'',st_date,'\' AND \'',e_date,'\',1,0) new_join,IF(effects_from<\'',st_date,'\',\'',st_date,'\',effects_from) effects_from,\'',e_date,'\' effects_upto FROM employee_salary_details s INNER JOIN employee_work_details w ON s.employee_id = w.employee_id WHERE s.employee_id =\'',emp_id,'\' UNION SELECT employee_salary_amount',@columns1,',\'\',\'\',\'\',\'\',1,IF(effects_from<\'',st_date,'\',\'',st_date,'\',effects_from) effects_from,effects_upto FROM employee_salary_details_history WHERE employee_id =\'',emp_id,'\' AND NOT (effects_from > \'',e_date,'\' OR effects_upto < \'',st_date,'\'))t)q;');
		PREPARE stm1 FROM @salaryDetailQuery;
		EXECUTE stm1;
		DEALLOCATE PREPARE stm1;
    
		-- general allowances calculation
    SET @incArrersGross=0;
    SET @e_c_epf=0;
    SET @e_c_esi=0;
		SET @c_str = SUBSTRING_INDEX(@columns1,',',-(@allowCount));
    SET @grossSal = 0;
    -- LOP and Days Worked Calculation
    SET @days=days_count;
    SET @daysDiffer=0;
    SET @arrear_amount=0;
    
    -- Calcualte days b/w the emp_doj and st_date and consider as LOP (i.e not to pay salary for those days)
    
    IF(salaryDay='wd') THEN
      SET @daysDiffer = days_count - (5* (DATEDIFF(e_date,emp_doj) DIV 7) + MID('1234555512344445123333451222234511112345001234550', 7 * WEEKDAY(emp_doj) + WEEKDAY(e_date)+1 , 1));
    ELSE
      SET @daysDiffer=days_count - (DATEDIFF(e_date,emp_doj)+1);
    END IF;
    
    IF(@daysDiffer > 0) THEN
        SET emp_lop = emp_lop+@daysDiffer;
    END IF;
    
    SET  @emp_lop=emp_lop;
    -- calculating working days subtracting lop from days count 
    IF((salaryDay!='wd')AND ((DATEDIFF(DATE_ADD(e_date,INTERVAL 1 DAY),st_date))>=30)) OR (salaryDay='wd') THEN
      SET @workingDays = (days_count - emp_lop)/days_count ;
    ELSEIF(salaryDay!='wd')AND (DATEDIFF(e_date,st_date)<30) THEN
      SET @workingDays = ((DATEDIFF(DATE_ADD(e_date,INTERVAL 1 DAY),st_date)) - emp_lop)/days_count ;
    END IF;

    SET @comp_sal = ROUND((@grossSalAmt/days_count)*2x_count); -- calculating compoff double salary using 2x count
    -- Loop beteween each allowances
		  WHILE @c_str != '' DO
  			SET @allowAmount = 0;
  			SET @currentValue = SUBSTRING_INDEX(@c_str, ',', 1);
  			SET @allowQuerySub = CONCAT('SET @currentAmt = @e_',@currentValue);
  			PREPARE stm FROM @allowQuerySub;
  			EXECUTE stm;
  			DEALLOCATE PREPARE stm;
  			SET @allowAmount = @currentAmt * @workingDays;
  			SET @allowAmount = ROUND(@allowAmount,2);
       	SET @grossSal = @grossSal + @allowAmount;
  			SET @payrollUpdateQuery = CONCAT(@payrollUpdateQuery,@currentValue,' = ', @allowAmount,',');
  			SET @c_str = SUBSTRING(@c_str, CHAR_LENGTH(@currentValue) + 1 + 1);
  			SET @allowQuerySub = CONCAT('SET @m_',@currentValue,'= @allowAmount');
  			PREPARE stm FROM @allowQuerySub;
  			EXECUTE stm;
  			DEALLOCATE PREPARE stm;
		  END WHILE;
      -- End of Allowances Loop
      
		  -- Misc Payments Calculations for the individual employee
		   SET miscPaymentString = ' ';           
       SET miscPaymentQueryString = ' ';           
		   OPEN cursor_kMiscpay;
		   read_mispay:LOOP
			 FETCH cursor_kMiscpay INTO pay_affected_ids_var,payment_id_var,payment_amount_var, payments_in_var,repetition_count_var,effects_from_var,pay_category_var,effects_upto_var,enabled_var;
				IF done  THEN
        SET done := false;
        CLOSE cursor_kMiscpay;
				LEAVE read_mispay;
				END IF;
        
        SET @payAffectedIdsCheck =pay_affected_ids_var;
        -- SET @miscPayView = CONCAT('SELECT @payAffectedIdsCheck LIKE \'%',desi_id,'%\' OR @payAffectedIdsCheck LIKE \'%',depar_id,'%\' OR  @payAffectedIdsCheck LIKE \'%',bran_id,'%\' OR @payAffectedIdsCheck LIKE \'%',emp_id,'%\' AS valid into @payValid ' );
        -- PREPARE stm4 FROM @miscPayView;
		    -- EXECUTE stm4;
		    -- DEALLOCATE PREPARE stm4;
        SET @payAmount = 0;
        IF enabled_var =1 THEN 
  					-- Find Amount Or %
            IF  SUBSTRING(payment_amount_var,-1) = 'A' THEN -- its on paymnet amount as 12|A
							SET  @payAmount=SUBSTRING_INDEX(payment_amount_var,'|',1);
						ELSE -- if Payments its on Percentage
							SET @toatalPaymentInHeadValue = 0;
					    WHILE payments_in_var != '' DO
								SET @currentPaymentInHeadName = SUBSTRING_INDEX(payments_in_var, ',', 1);
                IF(@currentDeductionInHeadName = 'GROSS') THEN
                  SET @currentDeductionInHeadValue = @grossSal;
                ELSE 
								  SET @allowQuerySub = CONCAT('SET @currentPaymentInHeadValue = @m_',@currentPaymentInHeadName);
                END IF;
								PREPARE stm FROM @allowQuerySub;
								EXECUTE stm;
								DEALLOCATE PREPARE stm;
								SET @toatalPaymentInHeadValue = @toatalPaymentInHeadValue + @currentPaymentInHeadValue ;
								SET payments_in_var = SUBSTRING(payments_in_var, CHAR_LENGTH(@currentPaymentInHeadName) + 1 + 1);
						  END WHILE;
							SET @payAmount=@toatalPaymentInHeadValue*((SUBSTRING_INDEX(payment_amount_var,'|',1))/100);
					  END IF;
          END IF;
          SET @grossSal =@grossSal+ @payAmount;
          IF pay_category_var = 'm_other_salary' THEN 
					  SET miscPaymentString =CONCAT(miscPaymentString,pay_category_var,'=',ROUND((@payAmount+IFNULL(@comp_sal,0)),2),',');
          ELSE
            IF pay_category_var = 'm_arrears' THEN
              SET miscPaymentString =CONCAT(miscPaymentString,pay_category_var,'=',ROUND(@payAmount,2),',');
              SET @arrear_amount = ROUND(@payAmount,2);
            ELSE
              SET miscPaymentString =CONCAT(miscPaymentString,pay_category_var,'=',ROUND(@payAmount,2),',');
            END IF;
          END IF;
          SET @miscQuerySub = CONCAT('SET @misc_',pay_category_var,'=',ROUND(@payAmount,2));
          SET @miscalwcolumns = CONCAT(@miscalwcolumns,IFNULL(pay_category_var,0),',');
								PREPARE stm FROM @miscQuerySub;
								EXECUTE stm;
								DEALLOCATE PREPARE stm;
          END LOOP;
          IF FIND_IN_SET('m_other_salary',@miscalwcolumns) = 0 THEN
            SET miscPaymentQueryString = CONCAT(miscPaymentString,'m_other_salary=',ROUND(IFNULL(@comp_sal,0),2),',');
          ELSE
            SET miscPaymentQueryString = miscPaymentString;
          END IF;
          -- DROP VIEW miscPayView;
          SET @payrollUpdateQuery = CONCAT(@payrollUpdateQuery,miscPaymentQueryString);
          SET @arrersQuery = CONCAT('SELECT SUM(gross_salary)',@arrersColums,' INTO @incArrersGross ',@e_arrersColums,' FROM arrears WHERE  DATE_FORMAT(processed_on,\'%m%Y\') =\'',month_year,'\' AND employee_id=\'',emp_id,'\'');
					PREPARE stm FROM @arrersQuery;
					EXECUTE stm;
					DEALLOCATE PREPARE stm;
  --  End of Misc Payments
  
  -- general deductions
  SET @currentAmt = 0.00;
  block3:BEGIN
	DECLARE deduction_id_var VARCHAR(40);
	DECLARE alias_name_var VARCHAR(10);
	DECLARE is_both_contribution_var INT;
	DECLARE is_admin_charges_var INT;
	DECLARE employee_share_var VARCHAR(20);
	DECLARE employer_share_var VARCHAR(20);
	DECLARE admin_charges_var VARCHAR(20);
	DECLARE deduce_in_var VARCHAR(100);
	DECLARE payment_to_var VARCHAR(20);
	DECLARE frequency_var CHAR(2);
	DECLARE due_in_var VARCHAR(20);
	DECLARE max_employee_share_var NUMERIC(15,2);
	DECLARE max_employer_share_var NUMERIC(15,2);
	DECLARE cal_exemption_var NUMERIC(15,2);
	DECLARE deductRate NUMERIC(15,2) DEFAULT 0.00;
	DECLARE deductableAmount_employee NUMERIC(15,2) DEFAULT 0.00;
	DECLARE deductableAmount_employer NUMERIC(15,2) DEFAULT 0.00;
	DECLARE deductableAmount NUMERIC(15,2) DEFAULT 0.00;
	DECLARE deductionAmount NUMERIC(15,2) DEFAULT 0.00;
  DECLARE totalDeduction NUMERIC(15,2) DEFAULT 0.00;
  DECLARE start_date DATE;
  DECLARE end_date DATE;
  DECLARE current_year int(6);
  DECLARE stringChar VARCHAR(20);
  DECLARE pf_limits VARCHAR(2);
	DECLARE done_k INT DEFAULT 0;
	DECLARE cursor_k CURSOR FOR SELECT p.pay_structure_id,IFNULL(company_deductions.alias_name,'AliasName'),IFNULL(is_both_contribution,0),IFNULL(is_admin_charges,0),IFNULL(employee_share,0), IFNULL(employer_share,0), IFNULL(admin_charges,0),IFNULL(deduce_in,'Nil'), IFNULL(payment_to,'Nil'), IFNULL(frequency,'M'), IFNULL(due_in,'Nil'),IFNULL(max_employee_share,0),IFNULL(max_employer_share,0), IFNULL(cal_exemption,0) FROM company_deductions RIGHT JOIN company_pay_structure p ON p.pay_structure_id = company_deductions.deduction_id WHERE p.display_flag = 1 AND p.type = 'D' AND p.pay_structure_id !='c_it'  ORDER BY p.sort_order;
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET done_k = TRUE;
   SET current_year= SUBSTRING(fin_year,1,4);
  IF SUBSTRING(month_year,1,2) IN (04,05,06,07,08,09) THEN
    SET stringChar='04,05,06,07,08,09'; 
    SET start_date=CONCAT(current_year,'-04-01');
    SET end_date=CONCAT(current_year,'-09-01');
  ELSEIF SUBSTRING(month_year,1,2) IN (10,11,12,01,02,03) THEN
    SET stringChar='10,11,12,01,02,03';
    SET start_date=CONCAT(current_year,'-10-01');
    SET end_date=CONCAT(current_year+1,'-03-01');
   END IF;
  IF (FIND_IN_SET('c_epf',@dedColumns1)>0 || FIND_IN_SET('c_esi',@dedColumns1)>0) THEN
      SET @breakupInsert = CONCAT('INSERT INTO payroll_deduction_breakup (employee_id,month_year,year,c_epf_emp_share,c_epf_elr_share,c_epf_admin_ch) VALUES (\'',emp_id,'\'\,\'',@mon_date,'\'\,',fin_year,',',0.00,'\,',0.00,'\,',0.00,')ON DUPLICATE KEY UPDATE employee_id =\'',emp_id,'\',month_year =\'',@mon_date,'\',year =',fin_year,',c_epf_emp_share =',0.00,',c_epf_elr_share =',0.00,',c_epf_admin_ch =',0.00,';');
      PREPARE insertstmt FROM @breakupInsert;
      EXECUTE insertstmt;
      DEALLOCATE PREPARE insertstmt;
  END IF;
	OPEN cursor_k;
      read_loop1:LOOP
      SET deductionAmount = 0;
      SET totalDeduction = 0;
      SET deductableAmount = 0.00;
      SET pf_limits = @pf_limit;
      -- deductions loop
      FETCH cursor_k INTO deduction_id_var, alias_name_var, is_both_contribution_var, is_admin_charges_var, employee_share_var, employer_share_var, admin_charges_var, deduce_in_var, payment_to_var, frequency_var, due_in_var, max_employee_share_var, max_employer_share_var, cal_exemption_var;
                    IF done_k THEN
                      LEAVE read_loop1;
                    END IF;
                      SET deductRate =0.00;
                      SET @totalMasterAmt = 0.00;
                      SET @totalDedAmt = 0.00;
      
      WHILE deduce_in_var != '' > 0 DO
		  -- deduce in loop
			  SET @currentValue = SUBSTRING_INDEX(deduce_in_var, ',', 1);
			  IF @currentValue = 'GROSS' THEN 
				  SET @mastAmt = @grossSalAmt;
				  SET @dedAmt =  @grossSal-IFNULL(@misc_c_bonus,0); 
			  ELSE
				-- master deduce in total cal
				SET @allowQuerySub1 = CONCAT('SET @mastAmt = @e_',@currentValue);
				PREPARE stm FROM @allowQuerySub1;
				EXECUTE stm;
				DEALLOCATE PREPARE stm;
				-- current ded val
				SET @allowQuerySub1 = CONCAT('SET @dedAmt = @m_',@currentValue);
				PREPARE stm FROM @allowQuerySub1;
				EXECUTE stm;
				DEALLOCATE PREPARE stm;
			  END IF;
			  SET @totalMasterAmt = @totalMasterAmt + @mastAmt; -- Totol Grosss For this Month minus From lop
			  SET @totalDedAmt = @totalDedAmt + @dedAmt; -- deduce in like(BAsic+hra+medical) minus from lop
			  SET deduce_in_var = SUBSTRING(deduce_in_var, CHAR_LENGTH(@currentValue) + 1 + 1);
		  END WHILE;
     -- Total DeductionAMountStore For pt Because in loop excute deduce_in_var is empty wheb its fetch pt row
                 -- check cal_excemption
           IF cal_exemption_var =0 OR cal_exemption_var !=0  AND deduction_id_var != 'c_pt' THEN
                      -- check deduction_id
                IF deduction_id_var = 'c_epf'  THEN
                  IF pf_limits = -1 THEN
                      SET deductableAmount=deductableAmount;
                   ELSE
                -- Procedure Call For Find EPF
                      CALL CALC_EPF(@totalDedAmt,employee_share_var,max_employee_share_var,is_both_contribution_var,
                      employer_share_var,max_employer_share_var,is_admin_charges_var,admin_charges_var,@pf_limit,emp_id,
                      month_year,fin_year,1,'PAYROLL',@totepf);
                    SET deductableAmount=deductableAmount+ @totepf+IFNULL(@e_c_epf,0);
                   END IF;
                ELSEIF  deduction_id_var = 'c_esi'  THEN     -- Continue if Deduction id NOt EPF get ESI Value 
                   SET @stmt = CONCAT('SELECT IFNULL(SUM(c_esi),0) INTO @previous_esi FROM payroll p WHERE p.month_year BETWEEN \'',start_date,'\' AND \'',end_date,'\' AND p.employee_id =\'',emp_id,'\'');
                   PREPARE stm FROM @stmt;
                   EXECUTE stm;
                   DEALLOCATE PREPARE stm;
                IF @totalMasterAmt <= cal_exemption_var AND is_both_contribution_var = 1 THEN
                   SET @employee_value = @dedAmt * ((SUBSTRING_INDEX(employee_share_var,'|',1))/100);
                   SET @employer_value = @dedAmt * ((SUBSTRING_INDEX(employer_share_var,'|',1))/100);
                   -- SET @val_esi = @employee_value + @employer_value;
                   SET @val_esi = @employee_value;
                    ELSEIF @totalMasterAmt <= cal_exemption_var AND is_both_contribution_var = 0 THEN
                            SET @val_esi=@dedAmt * ((SUBSTRING_INDEX(employee_share_var,'|',1))/100);
                    SET @employee_value = @val_esi;
                    SET @employer_value = 0;
                END IF;
                IF @totalMasterAmt > cal_exemption_var AND @previous_esi >0 AND is_both_contribution_var = 1 THEN
                    SET @employee_value = cal_exemption_var *((SUBSTRING_INDEX(employee_share_var,'|',1))/100);
                    SET @employer_value = cal_exemption_var * ((SUBSTRING_INDEX(employer_share_var,'|',1))/100);
                    -- SET @val_esi = @employee_value + @employer_value;
                    SET @val_esi = @employee_value;
                ELSEIF @totalMasterAmt > cal_exemption_var AND @previous_esi >0 AND is_both_contribution_var =0  THEN
                    SET @val_esi= cal_exemption_var * ((SUBSTRING_INDEX(employee_share_var,'|',1))/100);
                    SET @employee_value = @val_esi;
                    SET @employer_value = 0;
                ELSEIF (@totalMasterAmt > cal_exemption_var AND @previous_esi = 0) OR @esi_limit= -1 THEN
                    SET @employee_value=0;
                    SET @employer_value=0;
                    SET @val_esi=0;
                END IF;
                IF @totalMasterAmt > cal_exemption_var AND @previous_esi >0 AND is_admin_charges_var = 1 THEN
                   SET @admin_charges = cal_exemption_var *((SUBSTRING_INDEX(admin_charges_var,'|',1))/100);
                ELSEIF @totalMasterAmt <= cal_exemption_var AND is_admin_charges_var = 1 THEN
                   SET @admin_charges = @dedAmt * ((SUBSTRING_INDEX(admin_charges_var,'|',1))/100);
                ELSE 
                   SET @admin_charges = 0;
                END IF;
                    SET @val_esi = @val_esi + @admin_charges;
                    
                    -- SET @total_deduction=@total_deduction + @val_esi;
							      SET deductableAmount = deductableAmount + @val_esi+IFNULL(@e_c_esi,0);
                  SET @breakupUpdate = 'UPDATE payroll_deduction_breakup SET ';
                  SET @breakupUpdate = CONCAT(@breakupUpdate,'c_esi_emp_share=',IFNULL(@employee_value,0),',c_esi_elr_share=',IFNULL(@employer_value,0),',c_esi_admin_ch=',IFNULL(@admin_charges,0),',updated_by=\'',performed_by,'\' WHERE month_year=\'',@mon_date,'\' AND employee_id =\'',emp_id,'\'');
                  PREPARE breakstmt FROM @breakupUpdate;
                  EXECUTE breakstmt;
                  DEALLOCATE PREPARE breakstmt;
                ELSE 
                  IF frequency_var = 'M' OR (frequency_var = 'Y' AND SUBSTRING(month_year,1,2) = '03') THEN
                      -- employee share
                        IF SUBSTRING(employee_share_var,-1) = 'A' THEN 
                          SET deductableAmount =  SUBSTRING_INDEX(employee_share_var,'|',1);
                        ELSE
                            SET deductableAmount_employee = (@totalDedAmt * ((SUBSTRING_INDEX(employee_share_var,'|',1))/100));
                            IF deductableAmount_employee > max_employee_share_var AND max_employee_share_var != 0 THEN  
                              SET deductableAmount =   max_employee_share_var;
                            ELSE
                                 SET deductableAmount = deductableAmount_employee;
                          END IF;
                          END IF;
                        IF is_both_contribution_var = 1 THEN
                        -- employer share
                          IF SUBSTRING(employer_share_var,-1) = 'A' THEN SET deductableAmount = deductableAmount +  SUBSTRING_INDEX(employer_share_var,'|',1);
                          ELSE
                            SET deductableAmount_employer = (@totalDedAmt * ((SUBSTRING_INDEX(employer_share_var,'|',1))/100));
                            IF deductableAmount_employer > max_employer_share_var AND max_employer_share_var != 0 THEN  
                              SET deductableAmount = deductableAmount + max_employer_share_var;
                            ELSE
                              SET deductableAmount = deductableAmount + deductableAmount_employer;
                            END IF;
                          END IF;
                        END IF;
                        IF is_admin_charges_var = 1 THEN
                        -- admin charges
                          IF SUBSTRING(admin_charges_var,-1) = 'A' THEN SET deductableAmount = deductableAmount + SUBSTRING_INDEX(admin_charges_var,'|',1);
                          ELSE
                            SET deductableAmount = deductableAmount + ( @totalDedAmt * ((SUBSTRING_INDEX(admin_charges_var,'|',1))/100));
                          END IF;
                        END IF;
                    END IF;
  					     END IF;-- end Of deduction id
            ELSE
							SET deductableAmount = 0;
						END IF;
            IF deduction_id_var = 'c_pt' THEN
               -- CALCULATE PT 
             CALL CALC_PT(emp_id,month_year,fin_year,@grossSal,@grossSalAmt,'PAYROLL',@remaining_months,deductableAmount);
            END IF;
	  SET deductableAmount = IFNULL(CEIL(deductableAmount),0);
      SET @payrollUpdateQuery = CONCAT(@payrollUpdateQuery,deduction_id_var,' = ',deductableAmount,',');
      SET @total_deduction= IFNULL(@total_deduction,0) + deductableAmount;
  END LOOP;
	CLOSE cursor_k;
	END block3;
    SET @breakupUpdate = 'UPDATE payroll_deduction_breakup SET ';
    SET @breakupUpdate = CONCAT(@breakupUpdate,'c_esi_emp_share=',IFNULL(@employee_value,0),',c_esi_elr_share=',IFNULL(@employer_value,0),',c_esi_admin_ch=',IFNULL(@admin_charges,0),',updated_by=\'',performed_by,'\' WHERE month_year=\'',@mon_date,'\' AND employee_id =\'',emp_id,'\'');
    PREPARE breakstmt FROM @breakupUpdate;
    EXECUTE breakstmt;
    DEALLOCATE PREPARE breakstmt;
 -- misc Deductions start
  SET miscDeductionQueryString ='';           
		   OPEN cursor_kMiscdedu;
		   read_misdud:LOOP
			 FETCH cursor_kMiscdedu INTO dedu_affected_ids_var,deduction_id_var,deduction_amount_var, deductions_in_var,ded_repetition_count_var,ded_effects_from_var,dedu_category_var,ded_effects_upto_var,enabled_var;
    	IF done  THEN
        SET done := false;
        CLOSE cursor_kMiscdedu;
				LEAVE read_misdud;
			END IF;
        SET @deduAffectedIdsCheck =dedu_affected_ids_var;
        -- SET @miscDeduView = CONCAT('SELECT @deduAffectedIdsCheck LIKE \'%',desi_id,'%\' OR @deduAffectedIdsCheck LIKE \'%',depar_id,'%\' OR  @deduAffectedIdsCheck LIKE \'%',bran_id,'%\' OR @deduAffectedIdsCheck LIKE \'%',emp_id,'%\' AS valid into @deduValid ' );
        -- PREPARE stm4 FROM @miscDeduView;
		    -- EXECUTE stm4;
		    -- DEALLOCATE PREPARE stm4;
        SET @deduAmount = 0;
        IF enabled_var=1 THEN 
              
  						IF SUBSTRING(deduction_amount_var,-1) = 'A' THEN -- its on Dedumnet amount as 12|A
                SET @deduAmount=SUBSTRING_INDEX(deduction_amount_var,'|',1);
      				    ELSE -- if Payments its on Percentage
      						-- SET @deductionInstr = SUBSTRING_INDEX(deductions_in_var,',',-(@allowCount));
      					SET @toatalDeductionInHeadValue = 0;
                WHILE deductions_in_var != '' DO
                SET @currentDeductionInHeadName = SUBSTRING_INDEX(deductions_in_var, ',', 1);
                IF(@currentDeductionInHeadName = 'GROSS') THEN
                  SET @currentDeductionInHeadValue = @grossSal;
                ELSE 
                  SET @allowQuerySub = CONCAT('SET @currentDeductionInHeadValue = @m_',@currentDeductionInHeadName);
                END IF;
                PREPARE stm FROM @allowQuerySub;
                EXECUTE stm;
                DEALLOCATE PREPARE stm;
                SET @toatalDeductionInHeadValue = @toatalDeductionInHeadValue + @currentDeductionInHeadValue ;
                SET deductions_in_var = SUBSTRING(deductions_in_var, CHAR_LENGTH(@currentDeductionInHeadName) + 1 + 1);
                END WHILE;
                 SET @deduAmount=@toatalDeductionInHeadValue*((SUBSTRING_INDEX(deduction_amount_var,'|',1))/100);
  						END IF;
            END IF;
                  SET @total_deduction=@total_deduction + ROUND(@deduAmount);
                  SET miscDeductionQueryString =CONCAT(miscDeductionQueryString,dedu_category_var,'=',@deduAmount,',');
       END LOOP;
                SET @payrollUpdateQuery = CONCAT(@payrollUpdateQuery,miscDeductionQueryString);
                -- End of Misc Deduction
                SET @payrollUpdateQuery = CONCAT(@payrollUpdateQuery,'gross_salary = ',ROUND((@grossSal+IFNULL(ROUND(@incArrersGross,2),0)+IFNULL(@comp_sal,0)),2),',inc_arrear=',IFNULL(ROUND(@incArrersGross,2),0),','); 
                SET @payrollUpdateQuery = CONCAT(@payrollUpdateQuery,'cal_days=',(DATEDIFF(e_date,st_date)+1),',worked_days=',ROUND(days_count-@emp_lop,2),',total_deduction=',@total_deduction,',updated_by=\'',performed_by,'\' WHERE employee_id =\'',emp_id,'\'  ');
                SET @queryAll = CONCAT(@queryAll,@payrollUpdateQuery);
                SET allColumns =@payrollUpdateQuery;
                PREPARE stm FROM @payrollUpdateQuery;
                EXECUTE stm;
                DEALLOCATE PREPARE stm;
  END LOOP;
   SELECT employee_id,gross_salary,total_deduction,status_flag FROM vw_employees;
   -- DROP VIEW vw_employees;
  END block0;
END;
#
DROP PROCEDURE IF EXISTS PROMOTE_INC;
CREATE PROCEDURE `PROMOTE_INC`( IN currentMonth VARCHAR(20),IN action_id VARCHAR(20),IN action_for char(1),
IN affected_ids varchar(100),IN action_effects_from varchar(30),IN is_promotion tinyint(1),
IN promoted_desig_id varchar(20),IN is_increment tinyint(1),IN incremented_amount varchar(30),
IN performed_by varchar(20),IN action_flag CHAR(1),IN oldEffects_from varchar(20),IN fin_year varchar(20),IN attn_st_dt varchar(20),OUT affected char(20),OUT allColumns varchar(500))
BEGIN
SET @joinstmt=' ';
SET @queryAddstmt=' ';
SET @conditionStmt=' ';
SET @querySelect=' ';
IF is_increment = 1 AND is_promotion=0  THEN
SET @joinstmt='INNER JOIN  employee_salary_details s ON s.employee_id = w.employee_id ';
SET @conditionStmt=CONCAT('AND MONTH(s.effects_from) != MONTH(\'',currentMonth,'\')');
SET @querySelect=CONCAT('INNER JOIN employee_salary_details s ON w.employee_id=s.employee_id  WHERE s.increment_id =\'',action_id,'\' AND w.enabled = 1');
ELSEIF  is_increment = 0 AND is_promotion=1 THEN
SET @joinstmt=' ';
SET @queryAddstmt=CONCAT(' AND MONTH(w.design_effects_from) != MONTH(\'',currentMonth,'\')');
SET @querySelect=CONCAT('WHERE w.promotion_id = \'',action_id,'\' AND w.enabled = 1');
ELSEIF  is_increment = 1 AND is_promotion=1 THEN
SET @joinstmt='INNER JOIN  employee_salary_details s ON s.employee_id = w.employee_id ';
SET @queryAddstmt=CONCAT(' AND MONTH(w.design_effects_from) != MONTH(\'',currentMonth,'\')');
SET @conditionStmt=CONCAT('AND MONTH(s.effects_from) != MONTH(\'',currentMonth,'\')');
SET @querySelect=CONCAT('WHERE w.promotion_id = \'',action_id,'\' AND w.enabled = 1');
END IF;
IF action_flag='D' THEN
SET @querySelect=CONCAT('INNER JOIN employee_salary_details s ON w.employee_id=s.employee_id  WHERE s.increment_id =\'',action_id,'\' AND w.enabled = 1');
END IF;
IF is_increment = 1 THEN
  block2:BEGIN
            DECLARE paystruct_id VARCHAR(100);
            DECLARE done_j INT DEFAULT 0;
            DECLARE cursor_j CURSOR FOR SELECT pay_structure_id FROM company_pay_structure WHERE type = 'A' AND display_flag = 1;
            DECLARE CONTINUE HANDLER FOR NOT FOUND SET done_j = TRUE;
            SET @columns1 ='';
            SET @e_vars ='';
            SET @vars = '';
            SET @allowCount = 0;
            OPEN cursor_j;
              read_loop2:LOOP
                FETCH cursor_j INTO paystruct_id;
                IF done_j THEN
                  LEAVE read_loop2;
                END IF;
                SET @allowCount = @allowCount + 1;
                SET @columns1 = CONCAT(@columns1,',',paystruct_id);
                SET @e_vars = CONCAT(@e_vars,',@e_',paystruct_id);
                SET @vars = CONCAT(@vars,',@',paystruct_id);
              END LOOP;
            CLOSE cursor_j;
          END block2;
 END IF;
block0:BEGIN
  DECLARE emp_id VARCHAR(20);
  DECLARE old_designation_id VARCHAR(20);
  DECLARE slb_id VARCHAR(20);
  DECLARE old_sal_amount NUMERIC(15,2);
  DECLARE done INT DEFAULT 0;
  DECLARE cursor_i CURSOR FOR SELECT employee_id,designation_id,slab_id FROM vw_employees;
  DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
  SET @select='';
  IF action_flag = 'C' THEN  
    IF action_for = 'E' THEN
      SET @select = concat('CREATE OR REPLACE VIEW vw_employees as SELECT w.employee_id,w.employee_name,w.designation_id,s.slab_id FROM employee_work_details w ',@joinstmt,' WHERE w.employee_id = \'',affected_ids,'\' AND w.enabled = 1 ',@conditionStmt,'',@queryAddstmt,'');
    END IF;
    IF action_for = 'D' THEN
      SET @select = concat('CREATE OR REPLACE VIEW vw_employees as SELECT w.employee_id,w.employee_name,w.designation_id,s.slab_id FROM employee_work_details w ',@joinstmt,' WHERE w.designation_id =\'',affected_ids,'\' AND w.enabled = 1 ',@conditionStmt,'',@queryAddstmt,'');
    END IF;
    IF action_for = 'F' THEN
      SET @select = concat('CREATE OR REPLACE VIEW vw_employees as SELECT w.employee_id,w.employee_name,w.designation_id,s.slab_id FROM employee_work_details w ',@joinstmt,' WHERE w.department_id =\'',affected_ids,'\' AND w.enabled = 1 ',@conditionStmt,'',@queryAddstmt,'');
    END IF;
    IF action_for = 'B' THEN
      SET @select = concat('CREATE OR REPLACE VIEW vw_employees as SELECT w.employee_id,w.employee_name,w.designation_id,s.slab_id FROM employee_work_details w ',@joinstmt,' WHERE w.branch_id =\'',affected_ids,'\' AND w.enabled = 1 ',@conditionStmt,'',@queryAddstmt,'');
    END IF;
  ELSEIF action_flag = 'D' THEN
   SET @select = concat('CREATE OR REPLACE VIEW vw_employees as SELECT w.employee_id,w.employee_name,w.designation_id,s.slab_id FROM employee_work_details w ',@querySelect,'');
  END IF;
  PREPARE stm FROM @select;
  EXECUTE stm;
  DEALLOCATE PREPARE stm;
  SELECT COUNT(employee_id) INTO affected FROM vw_employees;
   OPEN cursor_i;
      read_loop:LOOP
        FETCH cursor_i INTO emp_id, old_designation_id,slb_id;
        IF done THEN
          LEAVE read_loop;
        END IF;
        IF is_promotion = 0 THEN SET promoted_desig_id = 'NA';
        ELSE
       -- Start of Promotion
          IF action_flag ='D' THEN
          -- get old designation from history table and delete in history table ,move it to the work_details by deleting from history
          SET @updDesig = CONCAT('DELETE FROM emp_designation_history WHERE employee_id =\'',emp_id,'\' AND effects_upto =\'',oldEffects_from,'\'');
          ELSE 
            SET @updDesig = CONCAT('UPDATE employee_work_details SET promotion_id=\'',action_id,'\',design_effects_from=\'',action_effects_from,'\',designation_id =\'',promoted_desig_id,'\' WHERE employee_id=\'',emp_id,'\''); 
          END IF;
         PREPARE stm FROM @updDesig;
          EXECUTE stm;
          DEALLOCATE PREPARE stm;
          -- End of Promotion
        END IF;
       IF is_increment = 0 THEN SET incremented_amount = '0|A';
        ELSE
          -- Start of Increment
          IF action_flag != 'D' THEN 
            IF action_flag  = 'C' THEN 
            SET @salaryDetailQuery = CONCAT('SELECT employee_salary_amount ',@columns1,' INTO @old_sal_amount ',@e_vars, ' FROM employee_salary_details WHERE employee_id =\'',emp_id,'\'');
            ELSE
            SET @salaryDetailQuery = CONCAT('SELECT employee_salary_amount ',@columns1,' INTO @old_sal_amount ',@e_vars, ' FROM employee_salary_details_history WHERE employee_id =\'',emp_id,'\' AND effects_upto =\'',oldEffects_from,'\' ');
            END IF;
            PREPARE stm1 FROM @salaryDetailQuery;
            EXECUTE stm1;
            DEALLOCATE PREPARE stm1;
            SET @incQuery =CONCAT('UPDATE employee_salary_details SET increment_id = \'',action_id,'\', ');
            IF slb_id != 'Nil' THEN
              SET @slabQuery = CONCAT('SELECT slab_type ',@columns1,' INTO  @slab_type ',@vars,' FROM company_allowance_slabs WHERE slab_id =\'',slb_id,'\'');
              PREPARE stm FROM @slabQuery;
              EXECUTE stm;
              DEALLOCATE PREPARE stm;
              IF @slab_type = 'basic' THEN
                -- basic calc
                IF SUBSTRING(incremented_amount,-1) = 'A' THEN SET @newBasic = @e_basic + SUBSTRING_INDEX(incremented_amount,'|',1);
                ELSE
                  SET @newBasic = @e_basic + ( @e_basic * ((SUBSTRING_INDEX(incremented_amount,'|',1))/100));
                END IF;
                SET @incQuery = CONCAT(@incQuery, 'basic = ',@newBasic);
                SET @c_str = SUBSTRING_INDEX(@columns1,',',-(@allowCount - 1));
                SET @grossSal = @newBasic;
                WHILE @c_str != '' DO
                  SET @allowAmount = 0;
                  SET @currentValue = SUBSTRING_INDEX(@c_str, ',', 1);
                  SET @incQuery1 = CONCAT('SET @currentAmt = @',@currentValue);
                  PREPARE stm FROM @incQuery1;
                  EXECUTE stm;
                  DEALLOCATE PREPARE stm;
                  IF SUBSTRING(@currentAmt,-1) = 'A' THEN SET @allowAmount =  SUBSTRING_INDEX(@currentAmt,'|',1);
                  ELSE
                    SET @allowAmount = @newBasic * ((SUBSTRING_INDEX(@currentAmt,'|',1))/100);
                  END IF;
                  SET @grossSal = @grossSal + @allowAmount;
                  SET @incQuery = CONCAT(@incQuery,',',@currentValue,' = ', @allowAmount);
                  SET @c_str = SUBSTRING(@c_str, CHAR_LENGTH(@currentValue) + 1 + 1);
                END WHILE;
                SET @incQuery = CONCAT(@incQuery,',employee_salary_amount = ',@grossSal);
                -- SET allColumns = @incQuery;
              ELSE
                -- gross
                IF SUBSTRING(incremented_amount,-1) = 'A' THEN SET @newGross = @old_sal_amount + SUBSTRING_INDEX(incremented_amount,'|',1);
                ELSE
                  SET @newGross = @old_sal_amount + ( @old_sal_amount * ((SUBSTRING_INDEX(incremented_amount,'|',1))/100));
                END IF;
                SET @grossSal = 0;
                SET @incQuery = CONCAT(@incQuery, 'employee_salary_amount = ',@newGross);
                SET @c_str = SUBSTRING_INDEX(@columns1,',',-(@allowCount));
                WHILE @c_str != '' DO
                  SET @allowAmount = 0;
                  SET @currentValue = SUBSTRING_INDEX(@c_str, ',', 1);
                  SET @incQuery1 = CONCAT('SET @currentAmt = @',@currentValue);
                  PREPARE stm FROM @incQuery1;
                  EXECUTE stm;
                  DEALLOCATE PREPARE stm;
                  IF SUBSTRING(@currentAmt,-1) = 'A' THEN
                    IF SUBSTRING_INDEX(@currentAmt,'|',1) = 'R' THEN 
                      SET @allowAmount = @newGross - @grossSal;
                    ELSE
                      SET @allowAmount =  SUBSTRING_INDEX(@currentAmt,'|',1);
                    END IF;
                  ELSE
                    SET @allowAmount = @newGross * ((SUBSTRING_INDEX(@currentAmt,'|',1))/100);
                  END IF;
                  SET @grossSal = @grossSal + @allowAmount;
                  SET @incQuery = CONCAT(@incQuery,',',@currentValue,' = ', @allowAmount);
                  SET @c_str = SUBSTRING(@c_str, CHAR_LENGTH(@currentValue) + 1 + 1);
                END WHILE;
                -- SET allColumns = @incQuery;
              END IF;
              ELSE
                -- no slab calc
                SET @grossSal = 0;
                SET @c_str = SUBSTRING_INDEX(@columns1,',',-(@allowCount));
                WHILE @c_str != '' DO
                  SET @allowAmount = 0;
                  SET @currentValue = SUBSTRING_INDEX(@c_str, ',', 1);
                  SET @incQuery1 = CONCAT('SET @currentAmt = @e_',@currentValue);
                  PREPARE stm FROM @incQuery1;
                  EXECUTE stm;
                  DEALLOCATE PREPARE stm;
                  IF SUBSTRING(incremented_amount,-1) = 'A' THEN SET @allowAmount = @currentAmt + SUBSTRING_INDEX(incremented_amount,'|',1);
                  ELSE
                    SET @allowAmount = @currentAmt + ( @currentAmt * ((SUBSTRING_INDEX(incremented_amount,'|',1))/100));
                  END IF;
                  SET @grossSal = @grossSal + @allowAmount;
                  SET @incQuery = CONCAT(@incQuery,@currentValue,' = ', @allowAmount,',');
                  SET @c_str = SUBSTRING(@c_str, CHAR_LENGTH(@currentValue) + 1 + 1);
                END WHILE;
                SET @incQuery = CONCAT(@incQuery,'employee_salary_amount = ',@grossSal);
                -- SET allColumns =@incQuery;
            END IF;
            SET @incQuery = CONCAT(@incQuery,',effects_from =\'',action_effects_from,'\' WHERE employee_id =\'',emp_id,'\'');
            PREPARE stm FROM @incQuery;
            EXECUTE stm;
            DEALLOCATE PREPARE stm;
            IF action_effects_from < attn_st_dt THEN 
				CALL CALC_ARREARS(emp_id,currentMonth,action_effects_from,fin_year);
            END IF;
          ELSE
              -- delete from arrears table
            IF action_effects_from < currentMonth THEN 
              SET @deleteArrear = CONCAT('DELETE FROM arrears WHERE employee_id =\'',emp_id,'\' AND processed_on =\'',currentMonth,'\'');
              PREPARE stm FROM @deleteArrear;
              EXECUTE stm;
              DEALLOCATE PREPARE stm;
             SET @previrepayrollINc= CONCAT('UPDATE  payroll_preview_temp SET  inc_arrear=0 ,status_flag="I" WHERE employee_id =\'',emp_id,'\'');
             PREPARE stm FROM @previrepayrollINc;
             EXECUTE stm;
             DEALLOCATE PREPARE stm;
             END IF;
            -- delete from salary details history the max
         SET @deleteQuery = CONCAT('DELETE FROM employee_salary_details_history WHERE employee_id =\'',emp_id,'\' AND effects_upto =\'',oldEffects_from,'\'');
         PREPARE stm1 FROM @deleteQuery;
            EXECUTE stm1;
            DEALLOCATE PREPARE stm1;
            END IF;
          -- End of Increment
        END IF;
      END LOOP;
    CLOSE cursor_i;
    DROP VIEW vw_employees;
     IF action_flag != 'D' AND action_flag = 'C'  THEN 
  INSERT INTO comp_promotions_increments
  (action_id, action_for, affected_ids, action_effects_from, promoted_desig_id, incremented_amount,employees_affected, performed_by) 
  VALUES 
  (action_id, action_for, affected_ids, action_effects_from, promoted_desig_id, incremented_amount,affected, performed_by)
   ON DUPLICATE KEY UPDATE
  action_for     = VALUES(action_for),
  affected_ids = VALUES(affected_ids),
  action_effects_from     = VALUES(action_effects_from),
  promoted_desig_id = VALUES(promoted_desig_id), incremented_amount = VALUES(incremented_amount),
  employees_affected = VALUES(employees_affected), performed_by = VALUES(performed_by) ;
  ELSE
  SET @deletelogQuery = CONCAT('DELETE FROM comp_promotions_increments WHERE action_id =\'',action_id,'\' ');
 PREPARE stm1 FROM @deletelogQuery;
             EXECUTE stm1;
            DEALLOCATE PREPARE stm1;
  END IF;
END block0;
END;
#
DROP PROCEDURE IF EXISTS ATTENDANCE_SUMMARY_INSERT;
CREATE PROCEDURE `ATTENDANCE_SUMMARY_INSERT`(reff_id VARCHAR(20),dt DATETIME)
BEGIN
IF(DATE_FORMAT(dt,'%H:%i') BETWEEN '00:00' AND '08:55')  THEN
  SET dt = DATE_FORMAT(DATE_SUB(dt,INTERVAL 1 DAY),'%Y-%m-%d');
END IF;
 
SET @from_dt =  DATE_FORMAT(dt,'%Y-%m-01');
SET @to_dt = DATE_FORMAT(dt,'%Y-%m-%d');
/*
IF(WEEKDAY(dt)=0) THEN
  SET @to_dt = DATE_FORMAT(DATE_ADD(dt,INTERVAL 6 DAY),'%Y-%m-%d');
END IF;
*/
IF(DATE_FORMAT(dt,'%d')='01') THEN
  SET @to_dt = DATE_FORMAT(LAST_DAY(dt),'%Y-%m-%d');
END IF;

SET @getEmpId = CONCAT('SELECT employee_id INTO @emp_id FROM device_users WHERE ref_id=',reff_id);
PREPARE stm FROM @getEmpId;
EXECUTE stm;
DEALLOCATE PREPARE stm;

INSERT INTO attendance_summary(employee_id,shift_id,shift_name,shift_st_time,shift_end_time,shift_hrs
				,days,checkIn,checkOut,tot_worked,work_hrs,pay_day,lateIn,earlyOut,ot,punches)      
						
      SELECT sp.employee_id,sp.shift_id,sp.shift_name,sp.start_time,sp.end_time,sp.shift_hrs,sp.dates,sp.check_in,sp.check_out,sp.tot_worked,sp.worked_hours,
            sp.pay_day,sp.late,sp.early_out,sp.ot,sp.all_punches
      FROM
            (SELECT DISTINCT employee_id,shift_id,shift_name,start_time,end_time,shift_hrs,dates,check_in,check_out,tot_worked,worked_hours,
		              pay_day,IF(day_type='FH','-',late) late,IF(day_type='SH','-',early_out) early_out,ot,all_punches
		        FROM (
		        SELECT employee_id,shift_id,shift_name,start_time,end_time,shift_hrs,dates,check_in,check_out,tot_worked,day_type,
				       IF(SUBSTRING_INDEX(SUBSTRING_INDEX(min_xtra_hrs,'|',3),'|',-1) >=min_hrs_full_day,1,IF((SUBSTRING_INDEX(SUBSTRING_INDEX(min_xtra_hrs,'|',3),'|',-1) BETWEEN min_hrs_half_day AND min_hrs_full_day),'0.5','0')) pay_day,SUBSTRING_INDEX(min_xtra_hrs,'|',1) late,
				       SUBSTRING_INDEX(SUBSTRING_INDEX(min_xtra_hrs,'|',2),'|',-1) early_out,SUBSTRING_INDEX(SUBSTRING_INDEX(min_xtra_hrs,'|',3),'|',-1) worked_hours,SUBSTRING_INDEX(min_xtra_hrs,'|',-1) ot,all_punches
				FROM (
				SELECT employee_id,ref_id,shift_id,dates,check_in,check_out,shift_hrs,day_type,
				      SUBSTRING_INDEX(TIMEDIFF(check_out,check_in),'.',1) tot_worked,late_end,
				      Calculate_OT(dates,SUBSTRING_INDEX(TIMEDIFF(check_out,check_in),'.',1),check_in,check_out,start_time,end_time,shift_hrs,min_hrs_ot,late_end,is_day) min_xtra_hrs,start_time,end_time,shift_name,min_hrs_half_day,min_hrs_full_day,all_punches
				FROM ( 
				SELECT * FROM (
								SELECT employee_id,ref_id,shift_id,(CASE WHEN is_day=1 THEN DATE_FORMAT(MIN(date_time),'%Y-%m-%d') 
				                                WHEN is_day=0  THEN DATE_FORMAT(work_day,'%Y-%m-%d') END) 'dates',
				                          (CASE WHEN is_day in(0,1) THEN DATE_FORMAT(MIN(date_time),'%Y-%m-%d %T') 
				                                -- WHEN is_day=0 THEN IF(DATE_FORMAT(work_day,'%H:%i') BETWEEN '00:00' AND late_end,DATE_FORMAT(DATE_ADD(MIN(work_day),INTERVAL 1 DAY),'%Y-%m-%d %T'),DATE_FORMAT(MIN(work_day),'%Y-%m-%d %T'))  
				                                 END) 'check_in',
				                          (CASE WHEN is_day=1 OR is_day=0 THEN DATE_FORMAT(MAX(date_time),'%Y-%m-%d %T') 
				                                END) 'check_out',from_date,to_date,
				                        employee_doj,is_day,shift_hrs,start_time,end_time,min_hrs_ot,shift_name,min_hrs_half_day,min_hrs_full_day,late_end
				FROM ( 
				      SELECT z.employee_id,z.ref_id,z.from_date,z.to_date,z.shift_id,s.is_day,s.early_start,s.late_end,s.start_time,s.end_time,b.date_time,
				      s.shift_hrs,s.min_hrs_half_day,s.min_hrs_full_day,s.min_hrs_ot,s.shift_name,
				            (CASE WHEN (s.is_day =0 AND DATE_FORMAT(date_time,'%H:%i') BETWEEN '00:00' AND s.late_end) 
				                  THEN DATE_SUB(date_time, INTERVAL 1 DAY) ELSE date_time END) work_day,z.employee_doj
				      FROM (
					      SELECT r.employee_id,u.ref_id,IF(r.shift_id IS NULL,w.shift_id,r.shift_id) shift_id,w.employee_doj,w.employee_name,w.employee_lastname,w.team_id,w.branch_id empBranch,
					      		IF(from_date<@from_dt,@from_dt,from_date) from_date,
					      		IF(to_date='0000-00-00' OR to_date IS NULL ,@to_dt,to_date) to_date
							FROM shift_roaster r
							INNER JOIN employee_work_details w
							ON r.employee_id = w.employee_id
							LEFT JOIN device_users u
							ON w.employee_id = u.employee_id
							WHERE  r.employee_id=@emp_id
							AND ((NOT (from_date > @to_dt OR to_date < @from_dt )) OR
							((to_date ='0000-00-00' OR to_date IS NULL) AND NOT from_date > @to_dt )) ) z
				      LEFT JOIN company_shifts s ON z.shift_id = s.shift_id 
				      LEFT JOIN employee_biometric b ON z.ref_id = b.employee_id 
				      AND DATE_FORMAT(date_time,'%Y-%m-%d') BETWEEN from_date AND DATE_ADD(to_date,INTERVAL 1 DAY)
				      WHERE s.is_day IS NOT NULL ORDER BY employee_id,date_time  )q 
				      GROUP BY employee_id,shift_id,DATE_FORMAT(work_day,'%Y-%m-%d'))w WHERE dates BETWEEN from_date AND to_date)c
              		  INNER JOIN (SELECT employee_id EMPID,Name,date,DATE_FORMAT(date,'%d %b,%Y') Date_Formatted,GROUP_CONCAT(punch ORDER BY date_time) all_punches 
                      FROM (
                      SELECT employee_id,Name,date_time,DATE_FORMAT(work_day,'%Y-%m-%d') date,DATE_FORMAT(work_day,'%H:%i') punch
                      FROM (
                           SELECT z.employee_id ,CONCAT(employee_name,' ',employee_lastname) Name,b.date_time,
                                 (CASE WHEN (s.is_day =0 AND DATE_FORMAT(date_time,'%H:%i') BETWEEN '00:00' AND s.late_end) THEN DATE_SUB(date_time, INTERVAL 1 DAY)
                                  ELSE date_time END) work_day
                           FROM (
	                            SELECT r.employee_id,u.ref_id,IF(r.shift_id IS NULL,w.shift_id,r.shift_id) shift_id,w.employee_doj,w.employee_name,w.employee_lastname,w.team_id,w.branch_id empBranch,
							        IF(from_date<@from_dt,@from_dt,from_date) from_date,
							        IF(to_date='0000-00-00' OR to_date IS NULL ,@to_dt,to_date) to_date
								FROM shift_roaster r
								INNER JOIN employee_work_details w
								ON r.employee_id = w.employee_id
								LEFT JOIN device_users u
								ON w.employee_id = u.employee_id
								WHERE  r.employee_id=@emp_id
								AND ((NOT (from_date > @to_dt OR to_date < @to_dt )) OR
								((to_date ='0000-00-00' OR to_date IS NULL) AND NOT from_date > @from_dt )) ) z
		                           LEFT JOIN company_shifts s
                           ON z.shift_id = s.shift_id
                           LEFT JOIN device_users du
                           ON z.employee_id = du.employee_id
                           LEFT JOIN employee_biometric b
                           ON du.ref_id = b.employee_id AND DATE_FORMAT(date_time,'%Y-%m-%d') BETWEEN from_date AND DATE_ADD(to_date,INTERVAL 1 DAY)
                           WHERE s.is_day IS NOT NULL
                           ORDER BY z.employee_id,work_day) q
                      GROUP BY employee_id,work_day
                      )w GROUP BY employee_id,date) t
                    ON c.employee_id = t.EMPID AND dates = t.date
                    LEFT JOIN (SELECT ab.employee_id absent_id,ab.absent_date,ab.day_type
					FROM emp_absences ab
					WHERE ab.day_type!='FD' AND ab.absent_date BETWEEN @from_dt AND @to_dt) p
					ON c.employee_id = p.absent_id AND dates = p.absent_date 
                    WHERE dates BETWEEN @from_dt AND @to_dt)w
			      UNION 
			      SELECT employee_id,shift_id,shift_name,start_time,end_time,shift_hrs,dates,check_in,check_out,'' tot_worked,worked_hrs,day_type,pay_day,late,early_out,ot,all_punches
            FROM (
			      SELECT employee_id,from_date,to_date,z.shift_id,shift_name,start_time,end_time,'' shift_hrs,dates,'' check_in,'' check_out,'' worked_hrs,'' day_type,'' pay_day,'' late,'' early_out,'' ot,'' all_punches
			FROM (
			SELECT r.employee_id,u.ref_id,IF(r.shift_id IS NULL,w.shift_id,r.shift_id) shift_id,w.employee_doj,w.employee_name,w.employee_lastname,w.team_id,w.branch_id empBranch,
					      IF(from_date<@from_dt,@from_dt,from_date) from_date,
					      IF(to_date='0000-00-00' OR to_date IS NULL ,@to_dt,to_date) to_date
						FROM shift_roaster r
						INNER JOIN employee_work_details w
						ON r.employee_id = w.employee_id
						LEFT JOIN device_users u
						ON w.employee_id = u.employee_id
						WHERE  r.employee_id=@emp_id
						AND ((NOT (from_date > @to_dt OR to_date < @from_dt )) OR
						((to_date ='0000-00-00' OR to_date IS NULL) AND NOT from_date > @to_dt )))z
	  		LEFT JOIN company_shifts s ON IF(z.shift_id = 'Nil' OR z.shift_id = '','SH00001',z.shift_id) = s.shift_id
	  		JOIN (SELECT date dates FROM 
              (SELECT adddate('1970-01-01',t4*10000 + t3*1000 + t2*100 + t1*10 + t0) date FROM
               (SELECT 0 t0 UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t0,
               (SELECT 0 t1 UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t1,
               (SELECT 0 t2 UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t2,
               (SELECT 0 t3 UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t3,
               (SELECT 0 t4 UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t4) v
              WHERE date BETWEEN @from_dt AND @to_dt) t
  			  WHERE dates NOT IN (SELECT dates FROM (
  			  					SELECT * FROM (
								SELECT employee_id,ref_id,shift_id,(CASE WHEN is_day=1 THEN DATE_FORMAT(MIN(date_time),'%Y-%m-%d') 
				                                WHEN is_day=0  THEN DATE_FORMAT(work_day,'%Y-%m-%d') END) 'dates',
				                          (CASE WHEN is_day=1 THEN DATE_FORMAT(MIN(date_time),'%Y-%m-%d %T') 
				                                WHEN is_day=0 THEN IF(DATE_FORMAT(work_day,'%H:%i') BETWEEN '00:00' AND late_end,DATE_FORMAT(DATE_ADD(work_day,INTERVAL 1 DAY),'%Y-%m-%d %T'),DATE_FORMAT(work_day,'%Y-%m-%d %T'))  
				                                 END) 'check_in',
				                          (CASE WHEN is_day=1 OR is_day=0 THEN DATE_FORMAT(MAX(date_time),'%Y-%m-%d %T') 
				                                END) 'check_out',from_date,to_date,
				                        employee_doj,is_day,shift_hrs,start_time,end_time,min_hrs_ot,shift_name,min_hrs_half_day,min_hrs_full_day,late_end
				FROM ( 
				      SELECT z.employee_id,z.ref_id,z.from_date,z.to_date,z.shift_id,s.is_day,s.early_start,s.late_end,s.start_time,s.end_time,b.date_time,
				      s.shift_hrs,s.min_hrs_half_day,s.min_hrs_full_day,s.min_hrs_ot,s.shift_name,
				            (CASE WHEN (s.is_day =0 AND DATE_FORMAT(date_time,'%H:%i') BETWEEN '00:00' AND s.late_end) 
				                  THEN DATE_SUB(date_time, INTERVAL 1 DAY) ELSE date_time END) work_day,z.employee_doj
				      FROM (
				      SELECT r.employee_id,u.ref_id,IF(r.shift_id IS NULL,w.shift_id,r.shift_id) shift_id,w.employee_doj,w.employee_name,w.employee_lastname,w.team_id,w.branch_id empBranch,
					      IF(from_date<@from_dt,@from_dt,from_date) from_date,
					      IF(to_date='0000-00-00' OR to_date IS NULL ,@to_dt,to_date) to_date
						FROM shift_roaster r
						INNER JOIN employee_work_details w
						ON r.employee_id = w.employee_id
						LEFT JOIN device_users u
						ON w.employee_id = u.employee_id
						WHERE  r.employee_id=@emp_id
						AND ((NOT (from_date > @to_dt OR to_date < @from_dt )) OR
						((to_date ='0000-00-00' OR to_date IS NULL) AND NOT from_date > @to_dt )) ) z
				      LEFT JOIN company_shifts s ON z.shift_id = s.shift_id 
				      LEFT JOIN employee_biometric b ON z.ref_id = b.employee_id 
				      AND DATE_FORMAT(date_time,'%Y-%m-%d') BETWEEN from_date AND DATE_ADD(to_date,INTERVAL 1 DAY)
				      WHERE s.is_day IS NOT NULL ORDER BY employee_id,date_time)q
              GROUP BY employee_id,shift_id,DATE_FORMAT(work_day,'%Y-%m-%d'))w )c)) p
      	WHERE dates BETWEEN from_date AND to_date 
        ORDER BY employee_id,dates)q) sp
        ON DUPLICATE KEY UPDATE shift_id=sp.shift_id,shift_name=sp.shift_name,shift_st_time=sp.start_time,
                shift_end_time=sp.end_time,shift_hrs=sp.shift_hrs,checkIn=sp.check_in,checkOut=sp.check_out,tot_worked=sp.tot_worked,
                work_hrs=sp.worked_hours,pay_day=sp.pay_day,lateIn=sp.late,earlyOut=sp.early_out,ot=sp.ot,punches=sp.all_punches;

  SET @updateWeekoff= CONCAT('UPDATE attendance_summary s JOIN ( SELECT employee_id,shift_id,days,is_weekday,is_holiday,is_leave,IF(is_weekday IS NOT NULL AND is_leave=\'\' AND is_holiday=\'\',is_weekday,IF(is_holiday!=\'\',is_holiday,IF(is_leave!=\'\',is_leave,\'W\'))) type FROM ( SELECT employee_id,shift_id,days,IF(is_weekday=\'WE\',1,IF(is_weekday=\'WE-FH\' OR is_weekday=\'WE-SH\',CONCAT(SUBSTRING_INDEX(is_weekday,\'-\',-1),\'-0.5\'),NULL)) is_weekday,is_holiday,is_leave FROM ( SELECT s.employee_id,s.shift_id,days,(CASE WHEN (weeks = IF((WEEK(days) - WEEK(DATE_FORMAT(days , \'%Y-%m-01\')) + 1)>5,(WEEK(days) - WEEK(DATE_FORMAT(days , \'%Y-%m-01\')) - 1),WEEK(days) - WEEK(DATE_FORMAT(days , \'%Y-%m-01\')) + 1)) THEN (CASE WHEN (DAYNAME(days)=\'sunday\') THEN IF((sunday = \'FH\' OR sunday = \'SH\' OR sunday = \'FD\' ),IF((sunday = \'FH\' OR sunday = \'SH\'),CONCAT(\'WE\',\'-\',sunday),\'WE\'),sunday) WHEN (DAYNAME(days)=\'Monday\') THEN IF((monday = \'FH\' OR monday = \'SH\' OR monday = \'FD\' ),IF((monday = \'FH\' OR monday = \'SH\'),CONCAT(\'WE\',\'-\',monday),\'WE\'),monday) WHEN (DAYNAME(days)=\'Tuesday\') THEN IF((tuesday = \'FH\' OR tuesday = \'SH\' OR tuesday = \'FD\' ),IF((Tuesday = \'FH\' OR Tuesday = \'SH\'),CONCAT(\'WE\',\'-\',Tuesday),\'WE\'),Tuesday) WHEN (DAYNAME(days)=\'Wednesday\') THEN IF((wednesday = \'FH\' OR wednesday = \'SH\' OR wednesday = \'FD\' ),IF((wednesday = \'FH\' OR wednesday = \'SH\'),CONCAT(\'WE\',\'-\',wednesday),\'WE\'),wednesday) WHEN (DAYNAME(days)=\'Thursday\') THEN IF((thursday = \'FH\' OR thursday = \'SH\' OR thursday = \'FD\' ),IF((thursday = \'FH\' OR thursday = \'SH\'),CONCAT(\'WE\',\'-\',thursday),\'WE\'),thursday) WHEN (DAYNAME(days)=\'Friday\') THEN IF((friday = \'FH\' OR friday = \'SH\' OR friday = \'FD\' ),IF((friday = \'FH\' OR friday = \'SH\'),CONCAT(\'WE\',\'-\',friday),\'WE\'),friday) WHEN (DAYNAME(days)=\'Saturday\') THEN IF((saturday = \'FH\' OR saturday = \'SH\' OR saturday = \'FD\' ),IF((saturday = \'FH\' OR saturday = \'SH\'),CONCAT(\'WE\',\'-\',saturday),\'WE\'),saturday) ELSE \'\' END) END) is_weekday,IFNULL((CASE WHEN ((start_date BETWEEN days AND days) OR (end_date BETWEEN days AND days)) THEN (CASE WHEN (category = \'OPTIONAL\' AND h.branch_id = wd.branch_id ) THEN \'RH\' WHEN (category = \'HOLIDAY\' AND h.branch_id = \'NA\' ) THEN \'GH\' END) END),\'\') is_holiday,IFNULL(CONCAT(leave_rule_type,\'-\',day_count),\'\') is_leave FROM attendance_summary s INNER JOIN employee_work_details wd ON s.employee_id = wd.employee_id LEFT JOIN weekend w ON s.shift_id = w.shift_id LEFT JOIN holidays_event h ON (h.start_date BETWEEN \'',@from_dt,'\' AND \'',@to_dt,'\') AND s.days BETWEEN h.start_date AND h.end_date LEFT JOIN emp_absences a ON a.employee_id = \'',@emp_id,'\' AND a.absent_date = s.days AND a.absent_date BETWEEN \'',@from_dt,'\' AND \'',@to_dt,'\' WHERE s.employee_id = \'',@emp_id,'\' AND days BETWEEN \'',@from_dt,'\' AND \'',@to_dt,'\' )z WHERE is_weekday IS NOT NULL ORDER BY employee_id,days)z WHERE is_weekday IS NOT NULL OR is_holiday IS NOT NULL OR is_leave IS NOT NULL  )t ON s.employee_id = t.employee_id AND s.days = t.days SET s.day_type = t.type;');
  PREPARE stm1 FROM @updateWeekoff;
  EXECUTE stm1;
  DEALLOCATE PREPARE stm1; 
  
  SET @updteRegularaization= CONCAT('UPDATE attendance_summary ar INNER JOIN attendance_regularization re ON ar.employee_id = re.employee_id AND re.day = ar.days SET late_approved = (CASE WHEN re.regularize_type=\'Late\' AND re.status = \'A\' AND re.day = ar.days THEN 1 ELSE 0 END),early_approved = (CASE WHEN re.regularize_type=\'EarlyOut\' AND re.status = \'A\' AND re.day = ar.days  THEN 1 ELSE 0 END) WHERE ar.employee_id =\'',@emp_id,'\' AND re.day BETWEEN \'',@from_dt,'\' AND \'',@to_dt,'\' AND ar.days BETWEEN \'',@from_dt,'\' AND \'',@to_dt,'\';');
  PREPARE stm2 FROM @updteRegularaization;
  EXECUTE stm2;
  DEALLOCATE PREPARE stm2;
  
END;
#
CREATE  TRIGGER `emp_designation_history_before_delete` BEFORE DELETE ON  emp_designation_history FOR EACH ROW
BEGIN
 SET @disable_triggers = 1;
 UPDATE employee_work_details 
 SET designation_id = OLD.designation_id, 
 design_effects_from = OLD.effects_from, 
 promotion_id = OLD.promotion_id
WHERE employee_id=OLD.employee_id;
 SET @disable_triggers = NULL;
END;
#
CREATE  TRIGGER `employee_salary_details_after_update` AFTER UPDATE ON employee_salary_details FOR EACH ROW
BEGIN IF @disable_triggers IS NULL  THEN
	INSERT INTO employee_salary_details_shadow
	SET employee_id = OLD.employee_id,
	slab_id = OLD.slab_id,
	employee_salary_amount = OLD.employee_salary_amount,
	pf_limit = OLD.pf_limit,esi_limit = OLD.esi_limit,
	increment_id = OLD.increment_id,isAnnual = OLD.isAnnual,
	salary_type = OLD.salary_type,ctc = OLD.ctc,ctc_fixed_component = OLD.ctc_fixed_component,
	basic = OLD.basic,
	effects_from = OLD.effects_from,
	updated_by = OLD.updated_by;
	IF OLD.increment_id != NEW.increment_id OR OLD.slab_id !=NEW.slab_id THEN
	INSERT INTO employee_salary_details_history
	SET employee_id = OLD.employee_id,
	slab_id = OLD.slab_id,
	employee_salary_amount = OLD.employee_salary_amount,
	pf_limit = OLD.pf_limit,esi_limit = OLD.esi_limit,
	increment_id = OLD.increment_id,isAnnual = OLD.isAnnual,
	salary_type = OLD.salary_type,ctc = OLD.ctc,ctc_fixed_component = OLD.ctc_fixed_component,
	basic = OLD.basic,
	effects_from = OLD.effects_from,
	effects_upto = DATE_SUB(NEW.effects_from, INTERVAL 1 DAY),
	updated_on = OLD.updated_on,
	updated_by = OLD.updated_by;
	ELSEIF OLD.increment_id = NEW.increment_id AND OLD.effects_from != NEW.effects_from AND OLD.slab_id = NEW.slab_id THEN
	UPDATE employee_salary_details_history
	SET effects_upto = DATE_SUB(NEW.effects_from, INTERVAL 1 DAY),
	updated_on = OLD.updated_on,
	updated_by = OLD.updated_by
	WHERE employee_id = OLD.employee_id AND effects_upto =DATE_SUB(OLD.effects_from, INTERVAL 1 DAY);
	END IF;
	END IF;
	END;
#
CREATE  TRIGGER `employee_salary_details_history_before_delete` BEFORE DELETE ON employee_salary_details_history FOR EACH ROW
BEGIN
	SET @disable_triggers = 1;
	UPDATE employee_salary_details
	SET employee_id = OLD.employee_id,
	slab_id = OLD.slab_id,
	employee_salary_amount = OLD.employee_salary_amount,
	pf_limit = OLD.pf_limit,esi_limit = OLD.esi_limit,isAnnual = OLD.isAnnual,
	salary_type = OLD.salary_type,ctc = OLD.ctc,ctc_fixed_component = OLD.ctc_fixed_component,
	increment_id = OLD.increment_id,
	basic = OLD.basic,
	effects_from = OLD.effects_from
	WHERE employee_id = OLD.employee_id;
	SET @disable_triggers = NULL;
	END;
#
CREATE TRIGGER `employee_work_details_after_update` AFTER UPDATE ON employee_work_details FOR EACH ROW
BEGIN
  IF (OLD.promotion_id != NEW.promotion_id AND @disable_triggers IS NULL) THEN
    INSERT INTO emp_designation_history
      SET 
      employee_id=OLD.employee_id,
      designation_id=OLD.designation_id,
      promotion_id=OLD.promotion_id,
      effects_from=OLD.design_effects_from,
      effects_upto=DATE_SUB(NEW.design_effects_from, INTERVAL 1 DAY),
      designation_change_reason = OLD.designation_change_reason;
  ELSEIF (OLD.transfer_id != NEW.transfer_id AND @disable_triggers IS NULL) OR (OLD.branch_id != NEW.branch_id) THEN
			INSERT INTO emp_branch_history
        SET 
        employee_id=OLD.employee_id,
        transfer_id=OLD.transfer_id,
        branch_id=OLD.branch_id,
        effects_from=OLD.branch_effects_from,
        effects_upto=DATE_SUB(NEW.branch_effects_from, INTERVAL 1 DAY),
        branch_change_reason = OLD.branch_change_reason;
  ELSEIF OLD.promotion_id = NEW.promotion_id AND
         OLD.design_effects_from != NEW.design_effects_from AND @disable_triggers IS NULL  THEN
            UPDATE emp_designation_history            
				    SET 
            employee_id = OLD.employee_id
				    WHERE employee_id = OLD.employee_id AND 
            effects_upto =DATE_SUB(OLD.design_effects_from, INTERVAL 1 DAY);
  ELSEIF OLD.department_id != NEW.department_id THEN 
      INSERT INTO emp_department_history
        SET 
        employee_id = OLD.employee_id,
        promotion_id=OLD.promotion_id,
        department_id=OLD.department_id,
        effects_from=OLD.design_effects_from,
        effects_upto =DATE_SUB(NEW.depart_effects_from, INTERVAL 1 DAY),
        department_change_reason = OLD.department_change_reason;
  ELSEIF OLD.team_id != NEW.team_id THEN
      INSERT INTO emp_team_history
        SET 
        employee_id = OLD.employee_id,
        team_id=OLD.team_id,
        effects_from=OLD.team_effects_from,
        effects_upto =DATE_SUB(NEW.team_effects_from, INTERVAL 1 DAY),
        team_change_reason = OLD.team_change_reason;
  ELSEIF OLD.status_id != NEW.status_id THEN
      INSERT INTO emp_job_status_history
        SET 
        employee_id = OLD.employee_id,
        status_id=OLD.status_id,
        effects_from=OLD.job_status_effects_from,
        effects_upto =DATE_SUB(NEW.job_status_effects_from, INTERVAL 1 DAY),
        job_status_change_reason = OLD.job_status_change_reason;
  END IF; 
 END;
#
CREATE TRIGGER `asset_req_after_insert` AFTER INSERT ON asset_requests FOR EACH ROW
BEGIN
  
  IF NEW.status = 'I'
  THEN
  UPDATE assets 
  SET asset_status = 'locked';
  
  ELSE IF  NEW.status = 'R'
  THEN 
  UPDATE assets 
  SET asset_status = 'available';
  END IF;
  END IF;
END;
#
CREATE  TRIGGER `asset_req_after_update` AFTER UPDATE ON asset_requests FOR EACH ROW
BEGIN
  IF NEW.status = 'I'
  THEN
  UPDATE assets 
  SET asset_status = 'locked'
  where asset_id = old.asset_id;
  ELSE IF NEW.status = 'R'
  THEN 
  UPDATE assets 
  SET asset_status = 'available'
  where asset_id = old.asset_id;
  END IF;
  END IF;
END;
#
CREATE TRIGGER `notify_claimApprovedclaimDeclined` AFTER UPDATE ON claims FOR EACH ROW
BEGIN
IF(NEW.status != OLD.status AND NEW.status = 'D') THEN
INSERT INTO notifications (
       notification_id
      ,notification_type
      ,sender_id
      ,receiver_id
      ,action_id
      ,notif_text
      ,is_read
      ,create_date
    ) VALUES (CONCAT('NT',FLOOR(RAND() * 10000)),'claimDeclined',NEW.updated_by,NEW.employee_id,NEW.claim_id,CONCAT(' Your Claim for <b>',NEW.purpose,'</b> has been declined'),0,NOW());
ELSEIF (NEW.status != OLD.status AND NEW.status = 'A') THEN
INSERT INTO notifications (
       notification_id
      ,notification_type
      ,sender_id
      ,receiver_id
      ,action_id
      ,notif_text
      ,is_read
      ,create_date
    ) VALUES (CONCAT('NT',FLOOR(RAND() * 10000)),'claimApproved',NEW.updated_by,NEW.employee_id,NEW.claim_id,CONCAT(' Your Claim for <b>',NEW.purpose,'</b> has been Approved'),0,NOW());
 ELSEIF (NEW.status != OLD.status AND NEW.status = 'R') THEN
 INSERT INTO notifications (
       notification_id
      ,notification_type
      ,sender_id
      ,receiver_id
      ,action_id
      ,notif_text
      ,is_read
      ,create_date
    ) VALUES (CONCAT('NT',FLOOR(RAND() * 10000)),'claimProcessed',NEW.updated_by,NEW.employee_id,NEW.claim_id,CONCAT(' Your Claim for <b>',NEW.purpose,'</b> has been Processed.'),0,NOW());
 END IF;
 END;
#
CREATE TRIGGER `notify_projectAssigned` AFTER INSERT ON project_assignees FOR EACH ROW
BEGIN
    INSERT INTO notifications (
       notification_id
      ,notification_type
      ,sender_id
      ,receiver_id
      ,action_id
      ,notif_text
      ,is_read
      ,create_date
    ) VALUES(CONCAT('NT',FLOOR(RAND()*1000)),'projectAssigned',NEW.updated_by,NEW.employee_id,NEW.project_id,'You have been assigned to a project',0,NOW());
END;
#
CREATE TRIGGER `notify_taskAssigned` AFTER INSERT ON tasks_assignees FOR EACH ROW
BEGIN
    INSERT INTO notifications (
       notification_id
      ,notification_type
      ,sender_id
      ,receiver_id
      ,action_id
      ,notif_text
      ,is_read
      ,create_date
    ) VALUES(CONCAT('NT',FLOOR(RAND()*1000)),'taskAssigned',NEW.updated_by,NEW.employee_id,' ','You have been assigned to a task',0,NOW());
END;
#
CREATE TRIGGER `notify_leaveApprovedleaveRejected` AFTER UPDATE ON leave_requests FOR EACH ROW
BEGIN
IF(NEW.status != OLD.status AND NEW.status = 'A') THEN
INSERT INTO notifications (
       notification_id
      ,notification_type
      ,sender_id
      ,receiver_id
      ,action_id
      ,notif_text
      ,is_read
      ,create_date
    ) VALUES (CONCAT('NT',FLOOR(RAND() * 10000)),'leaveApproved','Admin',NEW.employee_id,NEW.request_id,'Your request for leave has been approved',0,NOW());
ELSEIF (NEW.status != OLD.status AND NEW.status = 'R') THEN
INSERT INTO notifications (
       notification_id
      ,notification_type
      ,sender_id
      ,receiver_id
      ,action_id
      ,notif_text
      ,is_read
      ,create_date
    ) VALUES (CONCAT('NT',FLOOR(RAND() * 10000)),'leaveRejected','Admin',NEW.employee_id,NEW.request_id,'Your request for leave  has been Rejected',0,NOW());
  END IF;
END;
#
CREATE TRIGGER `notify_compoffApprovedcompoffDeclined` AFTER UPDATE ON compensation_requests FOR EACH ROW
BEGIN
SET @msg='';
IF (NEW.status='CO') THEN
SET @msg=CONCAT('You have been Credited with comp-off for working on ',NEW.working_for,' on ',DATE_FORMAT(NEW.date,'%d/%m/%Y'));
ELSE
SET @msg=CONCAT('You have been awarded with 2x salary for working on ',NEW.working_for,' on ',DATE_FORMAT(NEW.date,'%d/%m/%Y'));
END IF;
IF(NEW.status != OLD.status AND NEW.status = 'CO' OR NEW.status = '2X') THEN
INSERT INTO notifications (
       notification_id
      ,notification_type
      ,sender_id
      ,receiver_id
      ,action_id
      ,notif_text
      ,is_read
      ,create_date
    ) VALUES (CONCAT('NT',FLOOR(RAND() * 10000)),'compoffRequested',NEW.approved_by,NEW.employee_id,DATE_FORMAT(NEW.date,'%d/%m/%Y'),@msg,0,NOW());
 ELSEIF(NEW.status != OLD.status AND NEW.status = 'R') THEN
 INSERT INTO notifications (
       notification_id
      ,notification_type
      ,sender_id
      ,receiver_id
      ,action_id
      ,notif_text
      ,is_read
      ,create_date
    ) VALUES (CONCAT('NT',FLOOR(RAND() * 10000)),'compoffRequested',NEW.approved_by,NEW.employee_id,DATE_FORMAT(NEW.date,'%d/%m/%Y'),CONCAT(' Your request for compoff  has been Rejected.'),0,NOW());
 END IF;
 END;
#
CREATE TRIGGER `company_details_after_update` AFTER UPDATE ON company_details FOR EACH ROW
BEGIN
  INSERT INTO company_details_shadow
     SET company_id = OLD.company_id, company_name = OLD.company_name, company_user_name = OLD.company_user_name, 
     current_payroll_month = OLD.current_payroll_month, company_type = OLD.company_type, company_logo = OLD.company_logo,
     company_build_name = OLD.company_build_name, company_street = OLD.company_street, company_area = OLD.company_area,
     company_city = OLD.company_city, company_pin_code = OLD.company_pin_code, company_website = OLD.company_website,
     company_state = OLD.company_state, company_phone = OLD.company_phone, company_mobile =OLD.company_mobile, company_email = OLD.company_email,
     company_pin = OLD.company_pin, company_pan_no =OLD.company_pan_no, company_tan_pattern = OLD.company_tan_pattern, company_tan_no =OLD.company_tan_no,
     company_doi =OLD.company_doi, company_cin_no =OLD.company_cin_no, company_epf_pattern = OLD.company_epf_pattern,
     company_epf_no = OLD.company_epf_no, company_esi_pattern =OLD.company_esi_pattern, company_esi_no =OLD.company_esi_no,
     company_emp_id_suffix =OLD.company_emp_id_suffix, company_emp_id_prefix =OLD.company_emp_id_prefix, company_resp1_name = OLD.company_resp1_name,
     hr_1username = OLD.hr_1username, company_resp1_desgn = OLD.company_resp1_desgn, company_resp1_phone = OLD.company_resp1_phone,
     company_resp1_email =OLD.company_resp1_email, company_resp2_name = OLD.company_resp2_name, company_resp2_desgn = OLD.company_resp2_desgn,
     hr_2username = OLD.hr_2username, company_resp2_phone = OLD.company_resp2_phone, company_resp2_email = OLD.company_resp2_email,
     approval_remarks = OLD.approval_remarks, info_flag = OLD.info_flag, leave_based_on = OLD.leave_based_on,
     email_method = OLD.email_method, email_notify = OLD.email_notify, salary_days =OLD.salary_days, attendance_period_sdate =OLD.attendance_period_sdate,
     updated_by =OLD.updated_by;
     
  END;
#
