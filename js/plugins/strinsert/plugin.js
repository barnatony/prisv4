/**
 * @license Copyright © 2013 Stuart Sillitoe <stuart@vericode.co.uk>
 * This work is mine, and yours. You can modify it as you wish.
 *
 * Stuart Sillitoe
 * stuartsillitoe.co.uk
 *
 */

CKEDITOR.plugins.add('strinsert',
{
	requires : ['richcombo'],
	init : function( editor )
	{
		
		// add the menu to the editor
		editor.ui.addRichCombo('strinsert',
		{
			label: 		'Employee Details',
			title: 		'Insert Content',
			voiceLabel: 'Insert Content',
			className: 	'cke_format',
			multiSelect:false,
			panel:
			{
				css: [ editor.config.contentsCss, CKEDITOR.skin.getPath('editor') ],
				voiceLabel: editor.lang.panelVoiceLabel
			},

			init: function() {    
                this.startGroup( 'Employee Details' );
                this.add( '{employee_id}', 'Employee ID' );
                this.add(  '{employee_name}', 'Employee Name' );
                this.add(  '{employee_full_name}', 'Employee Full Name' );
                this.add( '{employee_dob}', 'Employee DOB'  ); 
                this.add( '{employee_image}', 'Employee Image'); 
                this.add( '{employee_gender}', 'Employee Gender'); 
                this.add( '{employee_marital_status}', 'Marital Status'); 
                this.add( '{employee_marriagedate}', 'Marrital Date');
                this.add( '{employee_single_line_address}', 'Employee Address(One Lined)'); 
                this.add( '{multi_line_address}', 'Employee Address(Multi Lined)'); 
                this.add( '{emp_single_line_Permaddress}', 'Employee Permanent Address(One Lined)'); 
                this.add( '{emp_multi_line_Permaddress}', 'Employee Permanent Address(Multi Lined)'); 
                
                //this.add( '{designation_id}', 'Designation ID');   
                //this.add( '{department_id}', 'Department ID' );
                //this.add( '{branch_id}', 'Branch ID'); 
                this.add( '{designation_name}', 'Designation Name');   
                this.add( '{department_name}', 'Department Name' );
                this.add( '{branch_name}', 'Branch Name'); 
               
                this.add( '{team_name}', 'Team Name'); 
                this.add( '{date}', 'Date');
                this.add( '{employee_mobile}', 'Mobile No (Official)');
                this.add( '{employee_personal_mobile}', 'Mobile No (Personal)');
                this.add( '{employee_email}', 'Email ID (Official)'); 
                this.add( '{employee_personal_email}', 'Email ID (Personal)'); 
                this.startGroup( 'company Details' );
                this.add( '{company_name}', 'Company Name'); 
                this.add( '{company_area}', 'Company Area');
                this.add( '{company_address_single_line}', 'Company Address (Single Lined)'); 
                this.add( '{company_address_multi_line}', 'Company Address (Multi Lined)'); 
                this.startGroup( 'Joining Details' );
                this.add( '{confirmation_date}', 'Confirmation Date');
                this.add( '{employee_doj}', 'Employee DOJ');
                this.add( '{effects_from}', 'Effects From');
                
              //  this.add(  '{employee_name}', 'Second Name' );
                //this.add( '{employee_build_name}', 'Building Name'); 
                //this.add( '{employee_street}', 'Street Name');
                //this.add( '{employee_area}', 'Area'); 
               // this.add( '{employee_district}', 'District'); 
                //this.add( '{employee_city}', 'City'); 
                //this.add( '{employee_pin_code}', 'PIN Code'); 
               //  this.add( '{permanent_emp_bulidname}', 'Permanent Building Name'); 
               // this.add( '{permanent_emp_area}', 'Permanent Area'); 
               // this.add( '{permanent_emp_dist}', 'Permanent District'); 
               // this.add( '{permanent_emp_pincode}', 'Permanent PIN Code'); 
                this.startGroup( 'Transfer Details' );
                this.add( '{old_branch}', 'Old Branch');
                this.add( '{new_branch}', 'New Branch');
                this.add( '{effective_from}', 'Effective From');
                this.startGroup( 'Promotion Details' );
                this.add( '{old_designation}', 'Old Designation');
                this.add( '{new_designation}', 'New Designation'); 
                this.add( '{increment}', 'Increment');
                this.startGroup( 'Increment Details' );
                this.add( '{old_salary}', 'Old Salary');
                this.add( '{new_salary}', 'New Salary');
                this.add( '{new_salary_words}', 'New salary (in words)');
                this.add( '{old_salary_words}', 'Old salary (in words)');
                this.add( '{increment_percentage}', 'Increment Percentage');
                this.startGroup( 'Relieving Details' );
                this.add( '{last_working_date}', 'Last Working Date'); 
                this.add( '{off_ltr_issue_dt}', 'Offer Letter issue Date'); 
              
            },

			onClick: function( value )
			{
				editor.focus();
				editor.fire( 'saveSnapshot' );
				editor.insertHtml(value);
				editor.fire( 'saveSnapshot' );
			}
		});
	}
});