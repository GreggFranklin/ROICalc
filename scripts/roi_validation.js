/* Do our vaildation logic */

$(function() {
	$('a.roi-info').tooltip()
})

function IsNumeric(strUserValue) {
	var strValidChars = '0123456789.';
	var intIsNumeric = true;
	for(var intCount = 0; intCount < strUserValue.length; intCount++) {
		if(strValidChars.indexOf(strUserValue.charAt(intCount)) == -1) intIsNumeric = false;
	}
	return(intIsNumeric) && (strUserValue != '');
}
	
function Calculate(){

	if(!(IsNumeric(document.information.annual_revenue.value) 
		&& IsNumeric(document.information.monthly_ebills.value) 
		&& IsNumeric(document.information.annual_no_new_clients.value) 
		&& IsNumeric(document.information.no_attorneys.value)
		&& IsNumeric(document.information.attorney_hourly_rate.value)
		&& IsNumeric(document.information.no_collectors.value)
		&& IsNumeric(document.information.percentage_collectors_no_ebh.value)
		&& IsNumeric(document.information.collectors_hourly_rate.value)
		&& IsNumeric(document.information.no_billers.value)
		&& IsNumeric(document.information.billers_hourly_rate.value)
		&& IsNumeric(document.information.hours_template_new_client.value)
		&& IsNumeric(document.information.it_employee_hourly_rate.value)
		&& IsNumeric(document.information.percentage_write_off_no_ebh.value)
		&& IsNumeric(document.information.percentage_write_off_ebh.value)
		&& IsNumeric(document.information.total_receivables.value)
		&& IsNumeric(document.information.average_daily_sales.value)
		&& IsNumeric(document.information.days_sales_outstanding_before_ebh.value)
		&& IsNumeric(document.information.days_sales_outstanding_reduced_ebh.value)
		&& IsNumeric(document.information.cost_borrowing_ar.value)
		&& IsNumeric(document.information.attorney_hours_per_month_no_ebh.value)
		&& IsNumeric(document.information.percentage_reduction_attorney_time_ebh.value)
		&& IsNumeric(document.information.annual_hours_collector.value)
		&& IsNumeric(document.information.percentage_reduction_collector_time_ebh.value)
		&& IsNumeric(document.information.annual_hours_biller.value)
		&& IsNumeric(document.information.percentage_reduction_biller_time_ebh.value)))
		alert('You must enter a valid number. (0-9.)');
}

$(function() {
    $("input#roi_calc_button").click(function(){
        
       // Submit the form via ajax 
       var data = $("form#roi_form").serialize();
       $.ajax({
            type: 'POST',
            cache: false,
            data: data,
<<<<<<< HEAD
            timeout: 8000,
            
            beforeSend:function(){
                // alert("before send!");
                $('#roi-error').hide();
                $('#roi-loading').show();
            },
            success:function(data){
                // Fill in the ajax response
                $('#roi-loading').hide();
=======
            beforeSend:function(){
                // alert("before send!");
            },
            success:function(data){
                // Fill in the ajax response
>>>>>>> da2db4d83e4639ed26490b905fa281149812b521
                $("#roi-results").html(data);
                $('a.roi-info').tooltip().bind;
                $('#savings').hide().bind;
                
<<<<<<< HEAD
                function showSavings(e) {
	                e.preventDefault();
	                $('#roi-details').click(function() {
	                $('#savings').show().bind;
	                return false;
	             });
                }
            },
            error:function(){
                //alert("Ajax Error!");
                $('#roi-error').show();
            }
        });
    });
});
=======
                $('#roi-details').click(function() {
	                $('#savings').show().bind;
	                return false;
	             });
            },
            error:function(){
                alert("Ajax Error!");
            }
        });
    });
})
>>>>>>> da2db4d83e4639ed26490b905fa281149812b521


$(document).ready(function() {
  $('#savings').hide();
<<<<<<< HEAD
=======
 
  $('#roi-details').click(function() {
    $('#savings').show('slow');
    return false;
  });
>>>>>>> da2db4d83e4639ed26490b905fa281149812b521
});