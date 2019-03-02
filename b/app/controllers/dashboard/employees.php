<?php
function _employees($action=null){
	
	$employee=new Employee();

	switch ($action){
	
	case "getBirthdayData":
	$birthdays=$employee->joined_select("p.employee_id ,p.employee_email,(CASE WHEN DATE_FORMAT(p.employee_dob,'%d') = DATE_FORMAT(NOW(),'%d')THEN 'Today'
				      WHEN DATE_FORMAT(p.employee_dob,'%d') = DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 1 DAY),'%d') THEN 'Tomorrow'
				      ELSE DATE_FORMAT(p.employee_dob,'%b %d')
				      END
				 ) AS employee_dob,DATE_FORMAT(p.employee_dob,'%d') as day
				,p.employee_image ,w.employee_name ,p.employee_gender, EXTRACT(YEAR FROM NOW()) - EXTRACT(YEAR FROM  p.employee_dob) age","employee_personal_details p
				INNER JOIN  employee_work_details w ON p.employee_id=w.employee_id  AND w.enabled=1
				WHERE p.employee_dob + INTERVAL EXTRACT(YEAR FROM NOW()) - EXTRACT(YEAR FROM p.employee_dob) YEAR
				BETWEEN CURRENT_DATE() AND CURRENT_DATE() + INTERVAL 10 DAY ORDER BY day ASC");
	$employees=array("birthdays"=>$birthdays);
	break;
	
	case "getAnniversaryData":
		$anniversary=$employee->joined_select("p.employee_id ,p.employee_email,(CASE WHEN DATE_FORMAT(w.employee_doj,'%d') = DATE_FORMAT(NOW(),'%d')THEN 'Today'
					      WHEN DATE_FORMAT(w.employee_doj,'%d') = DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 1 DAY),'%d') THEN 'Tomorrow'
					      ELSE DATE_FORMAT(w.employee_doj,'%b %d')
					      END
					 ) AS doj,DATE_FORMAT(p.employee_dob,'%d') as day
					,p.employee_image ,w.employee_name ,p.employee_gender, EXTRACT(YEAR FROM NOW()) - EXTRACT(YEAR FROM  w.employee_doj) years","employee_personal_details p
					INNER JOIN  employee_work_details w ON p.employee_id=w.employee_id  AND w.enabled=1
					WHERE  w.employee_doj + INTERVAL EXTRACT(YEAR FROM NOW()) - EXTRACT(YEAR FROM w.employee_doj) YEAR
					BETWEEN CURRENT_DATE() AND CURRENT_DATE() + INTERVAL 99 DAY ORDER BY DATE_FORMAT(employee_doj,'%m%d') ASC");
	$employees=array("anniversary"=>$anniversary);
	break;
	
	case "getHolidayData":
		
		$holidays=$employee->joined_select("CONCAT(crq.employee_id,'-',crq.status) employee_id ,
				DATE_FORMAT(h.start_date,'%d %b,%Y') sdate,h.holiday_id,h.category, h.title ,(CASE WHEN DATE_FORMAT(h.start_date,'%d%m') = DATE_FORMAT(NOW(),'%d%m')THEN 'Today'
					WHEN DATE_FORMAT(h.start_date,'%d%m') = DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 1 DAY),'%d%m') THEN 'Tomorrow'
					ELSE DATE_FORMAT(h.start_date,'%b %d')
					END
					) AS event_date,
					DATE_FORMAT(h.start_date,'%d/%m/%Y') start_date,h.end_date","holidays_event h
					LEFT JOIN compensation_requests crq
			        ON h.start_date=crq.date AND employee_id='RCI001' AND crq.status!='R'
				    WHERE h.start_date >= CURRENT_DATE()  AND h.category IN ( 'HOLIDAY','EVENT') 
			       AND h.start_date <= NOW()+INTERVAL 10 Day
			      ORDER BY h.start_date ");
		$employees=array("holidays"=>$holidays);
	break;	
	
	
	}
echo json_encode($employees);	
}