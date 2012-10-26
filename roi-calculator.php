<?php
/*
Plugin Name: ROI Calculator
Description: ROI Calculator can be added to a page or post using a shortcode [roi_calc]
Version: 2.0
Author: Gregg Franklin	
Contributor: Stephen Carroll
*/

// Set-up Action and Filter Hooks
register_activation_hook(__FILE__, 'roi_add_defaults');
register_uninstall_hook(__FILE__, 'roi_delete_plugin_options');
add_action('admin_init', 'roi_init' );
add_action('admin_menu', 'roi_add_options_page');
add_filter( 'plugin_action_links', 'roi_plugin_action_links', 10, 2 );
add_action( 'wp_enqueue_scripts', 'roi_wp_enqueue_scripts' );
add_action( 'init', 'roi_ajax_response' );

global $roi_ajax;
$roi_ajax = false; // flag to just output ajax response

function roi_ajax_response(){
   
    // Check if this is an ajax response and validate nonce
    if (isset($_REQUEST['_wpnonce'])){
        if (! wp_verify_nonce( $_REQUEST['_wpnonce'] , 'roi-calculator') ){
            //die("Security check");
        }else{
            
            // Invoke our short code to output our table calculations
            global $roi_ajax;
            $roi_ajax = true; // flag to just output ajax response
            roi_shortcode(''); // Invoke the shortcode to do calculations
            exit();
        }
    }    
}

// Check if this is an ajax response

function roi_wp_enqueue_scripts(){
    wp_deregister_script( 'jquery' );
    wp_register_script( 'jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js');
    wp_enqueue_script( 'jquery' );
    wp_enqueue_script('roi_validation', plugin_dir_url(__FILE__) . 'scripts/roi_validation.js', array('jquery'), '1.0.0');
    wp_enqueue_script('roi_bootstrap', plugin_dir_url(__FILE__) . 'scripts/bootstrap.min.js', array('jquery'), '1.0.0', true);
    
    wp_register_style( 'prefix-style', plugins_url('css/bootstrap.min.css', __FILE__) );
    wp_enqueue_style( 'prefix-style' );
    
    wp_enqueue_style( 'roi-style', plugins_url('css/style.css', __FILE__),'1.2' );
}

// Display a Settings link on the main Plugins page
function roi_plugin_action_links( $links, $file ) {

	if ( $file == plugin_basename( __FILE__ ) ) {
		$roi_links = '<a href="'.get_admin_url().'options-general.php?page=roi-calculator12/roi-calculator.php">'.__('Settings').'</a>';
		// make the 'Settings' link appear first
		array_unshift( $links, $roi_links );
	}

	return $links;
}

// Delete options table entries ONLY when plugin deactivated AND deleted
function roi_delete_plugin_options() {
	delete_option('roi_options');
}

// Define default option settings
function roi_add_defaults() {

		$arr = array(
					'annual_revenue'							=>	'40000000',
					'monthly_ebills'							=>	'2000',
					'annual_no_new_clients'						=>	'20',	
					'no_attorneys'								=>	'200',
					'attorney_hourly_rate'						=>	'350.00',
					'no_collectors'								=>	'6',
					'percentage_collectors_no_ebh'				=>	'10',
					'percent_total_receivables'					=>	'85',
					'collectors_hourly_rate'					=>	'30.00',
					'no_billers'								=>	'12',
					'billers_hourly_rate'						=>	'30.00',
					'hours_template_new_client'					=>	'4',
					'it_employee_hourly_rate'					=>	'45.00',
					'it_template_creation'						=> 	'500',
					'it_no_templates'							=> 	'10',
					'percentage_write_off_no_ebh'				=>	'2.70',
					'percentage_write_off_ebh'					=>	'1.62',
					'total_receivables'							=>	'5000000',
					'average_daily_sales'						=>	'109589',
					'days_sales_outstanding_before_ebh'			=>	'46',
					'days_sales_outstanding_reduced_ebh'		=>	'11',
					'cost_borrowing_ar'							=>	'4',
					'attorney_hours_per_month_no_ebh'			=>	'2',
					'percentage_reduction_attorney_time_ebh'	=>	'70',
					'annual_hours_collector'					=>	'1750',
					'percentage_reduction_collector_time_ebh'	=>	'80',
					'annual_hours_biller'						=>	'1750',
					'percent_time_ebilling'						=>	'90',
					'percentage_reduction_biller_time_ebh'		=>	'80',
					'ebh_cost'									=>	'0',
					'page1_title'								=>	'Firm Finances',
					'page1_paragraph'							=>	'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable.',
					'page2_title'								=>	'Biller Time',
					'page2_paragraph'							=>	'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable. ',
					'page3_title'								=>	'Collector Time',
					'page3_paragraph'							=>	'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable. ',
					'page4_title'								=>	'Attorney Time',
					'page4_paragraph'							=>	'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable. ',
					'page5_title'								=>	'IT Time',
					'page5_paragraph'							=>	'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable. ',
					'page6_title'								=>	'eBillingHub Cost',
					'page6_paragraph'							=>	'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable. ',
					'disclaimer'								=>	'Disclaimer text goes here',
					'it_savings_description'					=> 'By making a continuously updated library of clients available to all our firms, eBillingHub eliminates the burden of individually creating and supporting client formats.',
					'write_offs_savings_description'			=> 'By checking the bills against the client guidelines before they leave the system, we ensure that bills pass through your client?s approval system sooner, and you get paid faster. There are fewer questioned or challenged invoices and fewer write-downs or write-offs.',
					'interest_savings_due_description'			=> 'On average, eBillingHub users report diary-to-cash improvements of 11 days, ultimately lowering your interest payments.',
					'attorney_time_savings_description'			=> 'When we reduce rejections, we also reduce the amount of time your attorneys have to spend redoing bills. Our clients report up to 70% savings in attorney time spent on these tasks, allowing attorneys to focus on revenue generating activities. ',
					'biller_time_savings_description'			=> 'eBillingHub increases the biller throughput dramatically, allowing your firm to keep up with the growth in electronic billing without additional hires. Billers complete their work faster and error free with unique operational tooling including batch processing, advanced filtering capabilities, and invoice editing abilities.',	
					'collector_time_savings_description'		=> 'By offering a complete, up-to-date status reporting functionality, your collectors don?t need to research bill status across vendors, reducing their research time by up to 80%.'
		);
		update_option('roi_options', $arr);
}


// Init plugin options to white list our options
function roi_init(){
	register_setting( 'roi_plugin_options', 'roi_options', 'roi_validate_options' );
}

// Add menu page calls roi_render_form()
function roi_add_options_page() {
	add_options_page('ROI Calc Settings', 'ROI Calc Settings', 'manage_options', __FILE__, 'roi_render_form');
}

// Render the Plugin options form
function roi_render_form() {
	?>
	<div class="wrap">
		
		<!-- Display Plugin Icon, Header, and Description -->
		<?php screen_icon(); ?>
		<h2>ROI Calculator Settings</h2>

		<div class="postbox-container" style="width:65%;">
			<div class="metabox-holder">	
				<div class="meta-box-sortables">
				
		<!-- Beginning of the Plugin Options Form -->
		<form method="post" action="options.php">
			<?php settings_fields('roi_plugin_options');
			
					$options = get_option('roi_options'); 
			?>
			
					<div class="postbox">
						<div class="handlediv"></div>
						<h3 class="hndle"><span>ROI Calculator Options</span></h3>
						<div class="inside">				
							<table class="form-table" width="100%">
								<tr>
									<td colspan="2"><p>The ROI Calculator can be displayed on any page or post by using the shortcode <code>[roi_calc]</code>.</p> 
									<p>Enter the default amounts in each field below.</p></td>
								</tr>
								<tr>
									<th valign="top" scope="row">
										<label for="annual_revenue" style="font-weight:bold;">Annual Revenue:</label>
									</th>
									<td valign="top">
										<input type="text" name="roi_options[annual_revenue]" id="annual_revenue" value="<?php echo $options['annual_revenue']; ?>" />									
									</td>
								</tr>
								<tr>
									<th valign="top" scope="row">
										<label for="monthly_e-bills" style="font-weight:bold;">Monthly E-Bills:</label>
									</th>
									<td valign="top">
										<input type="text" name="roi_options[monthly_ebills]" id="monthly_ebills"  value="<?php echo $options['monthly_ebills']; ?>" />
									</td>
								</tr>
								<tr>
									<th valign="top" scope="row">
										<label for="annual_no_new_clients" style="font-weight:bold;">Annual # of New Clients:</label>
									</th>
									<td valign="top">
										<input type="text" name="roi_options[annual_no_new_clients]" id="annual_no_new_clients"  value="<?php echo $options['annual_no_new_clients']; ?>" />
									</td>
								</tr>
								<tr>
									<th valign="top" scope="row">
										<label for="no_attorneys" style="font-weight:bold;"># of Attorneys with clients that e-bill:</label>
									</th>
									<td valign="top">
										<input type="text" name="roi_options[no_attorneys]" id="no_attorneys"  value="<?php echo $options['no_attorneys']; ?>" />
									</td
								</tr>
								<tr>
									<th valign="top" scope="row">
										<label for="attorney_hourly_rate" style="font-weight:bold;">Average Attorney Hourly Rate:</label>
									</th>
									<td valign="top">
										<input type="text" name="roi_options[attorney_hourly_rate]" id="attorney_hourly_rate"  value="<?php echo $options['attorney_hourly_rate']; ?>" />
									</td>
								</tr>
								<tr>
									<th valign="top" scope="row">
										<label for="no_collectors" style="font-weight:bold;"># of Collectors:</label>
									</th>
									<td valign="top">
										<input type="text" name="roi_options[no_collectors]" id="no_collectors"  value="<?php echo $options['no_collectors']; ?>" />
									</td>
								</tr>
								<tr>
									<th valign="top" scope="row">
										<label for="percentage_collectors_no_ebh" style="font-weight:bold;">% of Collectors Time Spent E-Billing:</label>
									</th>
									<td valign="top">
										<input type="text" name="roi_options[percentage_collectors_no_ebh]" id="percentage_collectors_no_ebh"  value="<?php echo $options['percentage_collectors_no_ebh']; ?>" />
									</td>
								</tr>
								<tr>
									<th valign="top" scope="row">
										<label for="collectors_hourly_rate" style="font-weight:bold;">Average Collectors Hourly Rate:</label>
									</th>
									<td valign="top">
										<input type="text" name="roi_options[collectors_hourly_rate]" id="collectors_hourly_rate"  value="<?php echo $options['collectors_hourly_rate']; ?>" />
									</td>
								</tr>	
								<tr>
									<th valign="top" scope="row">
										<label for="no_billers" style="font-weight:bold;"># of Billers:</label>
									</th>
									<td valign="top">
										<input type="text" name="roi_options[no_billers]" id="no_billers"  value="<?php echo $options['no_billers']; ?>" />
									</td>
								</tr>	
								<tr>
									<th valign="top" scope="row">
										<label for="billers_hourly_rate" style="font-weight:bold;">Average Billers Hourly Rate:</label>
									</th>
									<td valign="top">
										<input type="text" name="roi_options[billers_hourly_rate]" id="billers_hourly_rate"  value="<?php echo $options['billers_hourly_rate']; ?>" />
									</td>
								</tr>
								<tr>
									<td colspan="2"><hr /></td>
								</tr>
								<tr>
									<th valign="top" scope="row">
										<label for="hours_template_new_client" style="font-weight:bold;">Hours to Generate Template for New Client:</label>
									</th>
									<td valign="top">
										<input type="text" name="roi_options[hours_template_new_client]" id="hours_template_new_client"  value="<?php echo $options['hours_template_new_client']; ?>" />
									</td>
								</tr>
								<tr>
									<th valign="top" scope="row">
										<label for="it_employee_hourly_rate" style="font-weight:bold;">Average IT Employee Hourly Rate:</label>
									</th>
									<td valign="top">
										<input type="text" name="roi_options[it_employee_hourly_rate]" id="it_employee_hourly_rate"  value="<?php echo $options['it_employee_hourly_rate']; ?>" />
									</td>
								</tr>
								<tr>
									<th valign="top" scope="row">
										<label for="it_template_creation" style="font-weight:bold;">Consultant Fee for Template Creation:</label>
									</th>
									<td valign="top">
										<input type="text" name="roi_options[it_template_creation]" id="it_template_creation"  value="<?php echo $options['it_template_creation']; ?>" />
									</td>
								</tr>
								<tr>
									<th valign="top" scope="row">
										<label for="it_no_templates" style="font-weight:bold;">Number of Annual Consultant Created Templates:</label>
									</th>
									<td valign="top">
										<input type="text" name="roi_options[it_no_templates]" id="it_no_templates"  value="<?php echo $options['it_no_templates']; ?>" />
									</td>
								</tr>																
								<tr>
									<th valign="top" scope="row">
										<label for="percentage_write_off_no_ebh" style="font-weight:bold;">% Write-offs:</label>
									</th>
									<td valign="top">
										<input type="text" name="roi_options[percentage_write_off_no_ebh]" id="percentage_write_off_no_ebh"  value="<?php echo $options['percentage_write_off_no_ebh']; ?>" />
									</td>
								</tr>																																																			<tr>
									<th valign="top" scope="row">
										<label for="percentage_write_off_ebh" style="font-weight:bold;">% Write-off using eBillingHub:</label>
									</th>
									<td valign="top">
										<input type="text" name="roi_options[percentage_write_off_ebh]" id="percentage_write_off_ebh"  value="<?php echo $options['percentage_write_off_ebh']; ?>" />
									</td>
								</tr>	
								<tr>
									<th valign="top" scope="row">
										<label for="total_receivables" style="font-weight:bold;">Total Annual Receivables:</label>
									</th>
									<td valign="top">
										<input type="text" name="roi_options[total_receivables]" id="total_receivables"  value="<?php echo $options['total_receivables']; ?>" />
									</td>
								</tr>	
								<tr>
									<th valign="top" scope="row">
										<label for="percent_total_receivables" style="font-weight:bold;">% of Receivables that are e-billed:</label>
									</th>
									<td valign="top">
										<input type="text" name="roi_options[percent_total_receivables]" id="percent_total_receivables"  value="<?php echo $options['percent_total_receivables']; ?>" />
									</td>
								</tr>
								<tr>
									<th valign="top" scope="row">
										<label for="average_daily_sales" style="font-weight:bold;">Average Daily Sales:</label>
									</th>
									<td valign="top">
										<input type="text" name="roi_options[average_daily_sales]" id="average_daily_sales"  value="<?php echo $options['average_daily_sales']; ?>" />
									</td>
								</tr>
								<tr>
									<th valign="top" scope="row">
										<label for="days_sales_outstanding_before_ebh" style="font-weight:bold;">Average Days to Pay:</label>
									</th>
									<td valign="top">
										<input type="text" name="roi_options[days_sales_outstanding_before_ebh]" id="days_sales_outstanding_before_ebh"  value="<?php echo $options['days_sales_outstanding_before_ebh']; ?>" />
									</td>
								</tr>
								<tr>
									<th valign="top" scope="row">
										<label for="days_sales_outstanding_reduced_ebh" style="font-weight:bold;">Reduction in Days to Pay by using eBillingHub:</label>
									</th>
									<td valign="top">
										<input type="text" name="roi_options[days_sales_outstanding_reduced_ebh]" id="days_sales_outstanding_reduced_ebh"  value="<?php echo $options['days_sales_outstanding_reduced_ebh']; ?>" />
									</td>
								</tr>
								<tr>
									<th valign="top" scope="row">
										<label for="cost_borrowing_ar" style="font-weight:bold;">Cost of Borrowing on Accounts Receivables:</label>
									</th>
									<td valign="top">
										<input type="text" name="roi_options[cost_borrowing_ar]" id="cost_borrowing_ar"  value="<?php echo $options['cost_borrowing_ar']; ?>" />
									</td>
								</tr>	
								<tr>
									<th valign="top" scope="row">
										<label for="attorney_hours_per_month_no_ebh" style="font-weight:bold;">Attorney Hours per Month Spent on E-Billing Issues:</label>
									</th>
									<td valign="top">
										<input type="text" name="roi_options[attorney_hours_per_month_no_ebh]" id="attorney_hours_per_month_no_ebh"  value="<?php echo $options['attorney_hours_per_month_no_ebh']; ?>" />
									</td>
								</tr>																																										
								<tr>
									<th valign="top" scope="row">
										<label for="percentage_reduction_attorney_time_ebh" style="font-weight:bold;">% Reduction in Attorney Time Spent E-Billing by using eBillingHub:</label>
									</th>
									<td valign="top">
										<input type="text" name="roi_options[percentage_reduction_attorney_time_ebh]" id="percentage_reduction_attorney_time_ebh"  value="<?php echo $options['percentage_reduction_attorney_time_ebh']; ?>" />
									</td>
								</tr>
								<tr>
									<th valign="top" scope="row">
										<label for="annual_hours_collector" style="font-weight:bold;">Annual Hours Worked per Collector:</label>
									</th>
									<td valign="top">
										<input type="text" name="roi_options[annual_hours_collector]" id="annual_hours_collector"  value="<?php echo $options['annual_hours_collector']; ?>" />
									</td>
								</tr>	
								<tr>
									<th valign="top" scope="row">
										<label for="percentage_reduction_collector_time_ebh" style="font-weight:bold;">% Reduction in Collector Time Spent E-Billing by using eBillingHub:</label>
									</th>
									<td valign="top">
										<input type="text" name="roi_options[percentage_reduction_collector_time_ebh]" id="percentage_reduction_collector_time_ebh"  value="<?php echo $options['percentage_reduction_collector_time_ebh']; ?>" />
									</td>
								</tr>
								<tr>
									<th valign="top" scope="row">
										<label for="annual_hours_biller" style="font-weight:bold;">Annual Hours Worked per Biller:</label>
									</th>
									<td valign="top">
										<input type="text" name="roi_options[annual_hours_biller]" id="annual_hours_biller"  value="<?php echo $options['annual_hours_biller']; ?>" />
									</td>
								</tr>	
								<tr>
									<th valign="top" scope="row">
										<label for="percent_time_ebilling" style="font-weight:bold;">% of Time spent e-billing:</label>
									</th>
									<td valign="top">
										<input type="text" name="roi_options[percent_time_ebilling]" id="percent_time_ebilling"  value="<?php echo $options['percent_time_ebilling']; ?>" />
									</td>
								</tr>	
								<tr>
									<th valign="top" scope="row">
										<label for="percentage_reduction_biller_time_ebh" style="font-weight:bold;">% Reduction in Billers Time Spent E-Billing by using eBillingHub:</label>
									</th>
									<td valign="top">
										<input type="text" name="roi_options[percentage_reduction_biller_time_ebh]" id="percentage_reduction_biller_time_ebh"  value="<?php echo $options['percentage_reduction_biller_time_ebh']; ?>" />
									</td>
								</tr>
								<tr>
									<td colspan="2"><hr /></td>
								</tr>
								<tr>
									<th valign="top" scope="row">
										<label for="page1_title" style="font-weight:bold;">Tab 1 Title:</label>
									</th>
									<td valign="top">
										<input type="text" name="roi_options[page1_title]" id="page1_title"  value="<?php echo $options['page1_title']; ?>" size="100" />
									</td>
								</tr>	
								<tr>
									<th valign="top" scope="row">
										<label for="page1_paragraph" style="font-weight:bold;">Tab 1 paragraph:</label>
									</th>
									<td valign="top">
										<textarea rows="4" cols="100" name="roi_options[page1_paragraph]" id="page1_paragraph"><?php echo $options['page1_paragraph']; ?></textarea>
									</td>
								</tr>	
								<tr>
									<th valign="top" scope="row">
										<label for="page2_title" style="font-weight:bold;">Tab 2 Title:</label>
									</th>
									<td valign="top">
										<input type="text" name="roi_options[page2_title]" id="page2_title"  value="<?php echo $options['page2_title']; ?>" size="100" />
									</td>
								</tr>	
								<tr>
									<th valign="top" scope="row">
										<label for="page2_paragraph" style="font-weight:bold;">Tab 2 paragraph:</label>
									</th>
									<td valign="top">
										<textarea rows="4" cols="100" name="roi_options[page2_paragraph]" id="page2_paragraph"><?php echo $options['page2_paragraph']; ?></textarea>
									</td>
								</tr>
								<tr>
								<th valign="top" scope="row">
										<label for="page2_title" style="font-weight:bold;">Tab 3 Title:</label>
									</th>
									<td valign="top">
										<input type="text" name="roi_options[page3_title]" id="page3_title"  value="<?php echo $options['page3_title']; ?>" size="100" />
									</td>
								</tr>	
								<tr>
									<th valign="top" scope="row">
										<label for="page3_paragraph" style="font-weight:bold;">Tab 3 paragraph:</label>
									</th>
									<td valign="top">
										<textarea rows="4" cols="100" name="roi_options[page3_paragraph]" id="page3_paragraph"><?php echo $options['page3_paragraph']; ?></textarea>
									</td>
								</tr>	
								<tr>
								<th valign="top" scope="row">
										<label for="page4_title" style="font-weight:bold;">Tab 4 Title:</label>
									</th>
									<td valign="top">
										<input type="text" name="roi_options[page4_title]" id="page4_title"  value="<?php echo $options['page4_title']; ?>" size="100" />
									</td>
								</tr>	
								<tr>
									<th valign="top" scope="row">
										<label for="page4_paragraph" style="font-weight:bold;">Tab 4 paragraph:</label>
									</th>
									<td valign="top">
										<textarea rows="4" cols="100" name="roi_options[page4_paragraph]" id="page4_paragraph"><?php echo $options['page4_paragraph']; ?></textarea>
									</td>
								</tr>
								<tr>
								<th valign="top" scope="row">
										<label for="page5_title" style="font-weight:bold;">Tab 5 Title:</label>
									</th>
									<td valign="top">
										<input type="text" name="roi_options[page5_title]" id="page5_title"  value="<?php echo $options['page5_title']; ?>" size="100" />
									</td>
								</tr>	
								<tr>
									<th valign="top" scope="row">
										<label for="page5_paragraph" style="font-weight:bold;">Tab 5 paragraph:</label>
									</th>
									<td valign="top">
										<textarea rows="4" cols="100" name="roi_options[page5_paragraph]" id="page5_paragraph"><?php echo $options['page5_paragraph']; ?></textarea>
									</td>
								</tr>
								<tr>
								<th valign="top" scope="row">
										<label for="page6_title" style="font-weight:bold;">Tab 6 Title:</label>
									</th>
									<td valign="top">
										<input type="text" name="roi_options[page6_title]" id="page6_title"  value="<?php echo $options['page6_title']; ?>" size="100" />
									</td>
								</tr>	
								<tr>
									<th valign="top" scope="row">
										<label for="page6_paragraph" style="font-weight:bold;">Tab 6 paragraph:</label>
									</th>
									<td valign="top">
										<textarea rows="4" cols="100" name="roi_options[page6_paragraph]" id="page6_paragraph"><?php echo $options['page6_paragraph']; ?></textarea>
									</td>
								</tr>
								<tr>
									<th valign="top" scope="row">
										<label for="page5_paragraph" style="font-weight:bold;">Disclaimer:</label>
									</th>
									<td valign="top">
										<textarea rows="4" cols="100" name="roi_options[disclaimer]" id="disclaimer"><?php echo $options['disclaimer']; ?></textarea>
									</td>
								</tr>			
																																													
							</table>
							</div>
							</div>

					</div>
			</div>
		</div>
</div>
			
				<!-- Total Descriptions -->
				<div class="postbox-container" style="margin-left:20px;width:30%;">
					<div class="metabox-holder">	
						<div class="meta-box-sortables">
							<div class="postbox">
								<div class="handlediv"><br /></div>
								<h3 class="hndle"><span>Total Descriptions</span></h3>
								<div class="inside" style="padding:10px; padding-top:0;">
								<table>
									<tr>
										<th><label for="it_savings_description" style="font-weight:bold;">IT Savings Description:</label></th>
									</tr>
									<tr>
										<td valign="top">
											<textarea rows="4" cols="50" name="roi_options[it_savings_description]" id="it_savings_description"><?php echo $options['it_savings_description']; ?></textarea>
										</td>
									</tr>
									<tr>
										<th><label for="write_offs_savings_description" style="font-weight:bold;">Write-offs Savings Description:</label></th>
									</tr>
									<tr>
										<td valign="top">
											<textarea rows="4" cols="50" name="roi_options[write_offs_savings_description]" id="write_offs_savings_description"><?php echo $options['write_offs_savings_description']; ?></textarea>
										</td>
									</tr>
									<tr>
										<th><label for="interest_savings_due_description" style="font-weight:bold;">Interest Savings Description:</label></th>
									</tr>
									<tr>
										<td valign="top">
											<textarea rows="4" cols="50" name="roi_options[interest_savings_due_description]" id="interest_savings_due_description"><?php echo $options['interest_savings_due_description']; ?></textarea>
										</td>
									</tr>
									<tr>
										<th><label for="attorney_time_savings_description" style="font-weight:bold;">Attorney Savings Description:</label></th>
									</tr>
									<tr>
										<td valign="top">
											<textarea rows="4" cols="50" name="roi_options[attorney_time_savings_description]" id="attorney_time_savings_description"><?php echo $options['attorney_time_savings_description']; ?></textarea>
										</td>
									</tr>
									<tr>
										<th><label for="biller_time_savings_description" style="font-weight:bold;">Biller Savings Description:</label></th>
									</tr>
									<tr>
										<td valign="top">
											<textarea rows="4" cols="50" name="roi_options[biller_time_savings_description]" id="biller_time_savings_description"><?php echo $options['biller_time_savings_description']; ?></textarea>
										</td>
									</tr>
									<tr>
										<th><label for="collector_time_savings_description" style="font-weight:bold;">Collector Savings Description:</label></th>
									</tr>
									<tr>
										<td valign="top">
											<textarea rows="4" cols="50" name="roi_options[collector_time_savings_description]" id="collector_time_savings_description"><?php echo $options['collector_time_savings_description']; ?></textarea>
										</td>
									</tr>
									<tr>
										<th><label for="cost_of_ebh_description" style="font-weight:bold;">Cost of eBillingHub:</label></th>
									</tr>
									<tr>
										<td valign="top">
											<textarea rows="4" cols="50" name="roi_options[cost_of_ebh_description]" id="cost_of_ebh_description"><?php echo $options['cost_of_ebh_description']; ?></textarea>
										</td>
									</tr>																						
								</table>
							</div>
						</div>
					</div>
				</div>
								
				<p class="submit">
					<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
				</p>	
		</form>
	</div>
	<?php	
}

// Sanitize and validate input. Accepts an array, return a sanitized array.
function roi_validate_options($input) {
	 // strip html from textboxes
	 $input['annual_revenue'] 	=  numbers_period($input['annual_revenue']); 
	 return $input;
}

// Remove anthing except numbers
function numbers_only($str) {
	$clean = preg_replace('/[^0-9]/', '', $str);
	return $clean;
}
// Remove anthing except numbers and period
function numbers_period($str) {
	$clean = preg_replace('/[^0-9.]/', '', $str);
	return $clean;
}

// Create the shortcode
$shortcode = get_option('roi_shortcode');
if(!$shortcode) { $shortcode = 'roi_calc'; }
add_shortcode($shortcode, 'roi_shortcode');

// Create the shorcode
function roi_shortcode( $atts, $content = null ) {
   extract( shortcode_atts( array(
      ), $atts ) );
      

// Obtain submitted values or use defaults
$roi_options = wp_parse_args( get_option('roi_options'), $roic_default_settings );
extract( $roi_options );

if (isset($_REQUEST['_wpnonce'])){
    if (! wp_verify_nonce( $_REQUEST['_wpnonce'] , 'roi-calculator') ){
        die("Security check");
    }else{
        extract( $_POST, EXTR_OVERWRITE );
    }
}

/*=======================================================================
	Calculations
=========================================================================*/
global $roi_ajax;

// IT
$it_current_cost = $annual_no_new_clients * $hours_template_new_client * $it_employee_hourly_rate + $it_template_creation * $it_no_templates;
$it_cost_ebh = 0;
$it_savings = $it_current_cost - $it_cost_ebh;

// Write-offs
$write_off_current_cost = $annual_revenue * $percentage_write_off_no_ebh / 100;
$write_off_cost_ebh = $annual_revenue * $percentage_write_off_ebh / 100;
$write_off_savings = $write_off_current_cost - $write_off_cost_ebh ;

// Interest
$daily_revenue = $annual_revenue / 365;
$improved_cash_flow = round($daily_revenue * $days_sales_outstanding_reduced_ebh * $percent_total_receivables / 100);
$interest_savings_dso = $cost_borrowing_ar * $improved_cash_flow;

$interest_current_cost = $daily_revenue * $days_sales_outstanding_before_ebh * $cost_borrowing_ar / 100 * $percent_total_receivables / 100;
$interest_savings = $improved_cash_flow * $cost_borrowing_ar / 100;
$interest_cost_ebh =  $interest_current_cost - $interest_savings;


// Attorney
$attorney_hours_no_ebh = $attorney_hours_per_month_no_ebh * $no_attorneys * 12;
$attorney_hours_saved = $attorney_hours_no_ebh * $percentage_reduction_attorney_time_ebh / 100;
$attorney_time_savings = $attorney_hourly_rate * $attorney_hours_saved;

$attorney_current_cost = $attorney_hourly_rate * $attorney_hours_per_month_no_ebh * $no_attorneys * 12;
$attorney_savings = $attorney_hours_saved * $attorney_hourly_rate;
$attorney_cost_ebh = $attorney_current_cost - $attorney_savings;

// Biller
$biller_annual_hours_worked = $no_billers * $annual_hours_biller;
$biller_annual_saved = $biller_annual_hours_worked * $percentage_reduction_biller_time_ebh / 100;
$biller_annual_spent_ebilling = $no_billers * $percent_time_ebilling / 100 * $annual_hours_biller;
$biller_hours_saved = $percentage_reduction_biller_time_ebh / 100 * $biller_annual_spent_ebilling;
$biller_time_savings = $biller_hours_saved * $billers_hourly_rate;

$biller_current_cost = $billers_hourly_rate * $no_billers * $annual_hours_biller * $percent_time_ebilling / 100;
$biller_savings = $no_billers * $percent_time_ebilling / 100 * $annual_hours_biller * $percentage_reduction_biller_time_ebh / 100 * $billers_hourly_rate;
$biller_cost_ebh = $biller_current_cost - $biller_savings;

// Collector
$collector_annual_hours_spent = $no_collectors * $annual_hours_collector * $percentage_collectors_no_ebh / 100;
$collector_hours_per_year = $collector_annual_hours_spent * $percentage_reduction_collector_time_ebh / 100;
$collector_time_savings = $collectors_hourly_rate * $collector_hours_per_year;

$collector_current_cost = $collectors_hourly_rate * $no_collectors * $annual_hours_collector * $percentage_collectors_no_ebh / 100;
$collector_savings = $collector_hours_per_year * $collectors_hourly_rate;
$collector_cost_ebh = $collector_current_cost - $collector_savings;

// Cost of eBillingHub
$cost_current_cost = 0;
$cost_cost_ebh = $ebh_cost;
$cost_savings = $cost_current_cost - $ebh_cost;

//Total
$total_current_cost = $it_current_cost + $write_off_current_cost + $interest_current_cost + $attorney_current_cost + $biller_current_cost + $collector_current_cost + $cost_current_cost;
$total_cost_ebh = $it_cost_ebh + $write_off_cost_ebh + $interest_cost_ebh + $attorney_cost_ebh + $biller_cost_ebh + $collector_cost_ebh + $cost_cost_ebh;
$total_savings = $it_savings + $write_off_savings + $write_off_savings + $attorney_savings + $biller_savings + $collector_savings + $cost_savings;

/*=======================================================================
	Display Public ROI Form (tabbed)
  =======================================================================*/
?>   
                                
<? if ($roi_ajax == false ): // Output when this isn't an ajax response ?>
<div class="roi_calc">
<form method="POST" id="roi_form" name="information" action="" onsubmit="Calculate();">
	<?php  wp_nonce_field('roi-calculator'); ?>

	<div class="tabbable tabs-left">
		<ul class="nav nav-tabs">
			<li class="active"><a href="#1" data-toggle="tab"><?php echo $page1_title; ?></a></li>
			<li><a href="#2" data-toggle="tab"><?php echo $page2_title; ?></a></li>
			<li><a href="#3" data-toggle="tab"><?php echo $page3_title; ?></a></li>
			<li><a href="#4" data-toggle="tab"><?php echo $page4_title; ?></a></li>
			<li><a href="#5" data-toggle="tab"><?php echo $page5_title; ?></a></li>
			<li><a href="#6" data-toggle="tab"><?php echo $page6_title; ?></a></li>
		</ul>

		 <div class="tab-content">
		 	<div class="tab-pane active" id="1">
			<table id="user_inputs">
				<tr>
					<td colspan=3><p><?php echo $page1_paragraph; ?></p></td>
				</tr>
					<tr>
					<td><label>Annual Revenue</label></td>
					<td><input type="text" name="annual_revenue" id="annual_revenue" value="<?php echo $annual_revenue; ?>" /></td>
				</tr>
				<tr>
					<td><label>Monthly E-Bills</label></td>
					<td><input type="text" name="monthly_ebills" id="monthly_ebills" value="<?php echo $monthly_ebills; ?>" /></td>
				</tr>
				<tr>
					<td><label>Total Annual Receivables</label></td>
					<td><input type="text" name="total_receivables" id="total_receivables" value="<?php echo $total_receivables; ?>" /></td>
				</tr>
				<tr>
					<td><label>% of Receivables that are e-billed</label></td>
					<td><input type="text" name="percent_total_receivables" id="percent_total_receivables" value="<?php echo $percent_total_receivables; ?>" /></td>
				</tr>
				<tr>
					<td><label>Average Daily Sales</label></td>
					<td><input type="text" name="average_daily_sales" id="average_daily_sales" value="<?php echo $average_daily_sales; ?>" /></td>
				</tr>
				<tr>
					<td><label>Average Days to Pay</label></td>
					<td><input type="text" name="days_sales_outstanding_before_ebh" id="days_sales_outstanding_before_ebh" value="<?php echo $days_sales_outstanding_before_ebh; ?>" /></td>
				</tr>
				<tr>
					<td><label>Cost of Borrowing on Accounts Receivables</label></td>
					<td><input type="text" name="cost_borrowing_ar" id="cost_borrowing_ar" value="<?php echo $cost_borrowing_ar; ?>" /></td>
				</tr>
				<tr>
					<td><label>% Write-Offs</label></td>
					<td><input type="text" name="percentage_write_off_no_ebh" id="percentage_write_off_no_ebh" value="<?php echo $percentage_write_off_no_ebh; ?>" /></td>
					<td><a data-placement="top" rel="tooltip" href="#" data-original-title="There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable." class="roi-info"><img src="<?php echo plugin_dir_url(__FILE__); ?>images/info.png" /></a></td>
				</tr>						
			</table>
		 </div>
		 <!--=========================================================== #1 === FIRM FINANCES =====================================================-->
		 	 			
			<div class="tab-pane" id="2">
		 	<table>	
				<tr>
					<td colspan=3><p><?php echo $page2_paragraph; ?></p></td>
				</tr>
				<tr>
					<td><label># of Billers</label></td>
					<td><input type="text" name="no_billers" id="no_billers" value="<?php echo $no_billers; ?>" /></td>
				</tr>
				<tr>
					<td><label>Average Biller Hourly Rate</label></td>
					<td><input type="text" name="billers_hourly_rate" id="billers_hourly_rate" value="<?php echo $billers_hourly_rate; ?>" /></td>
				</tr>
				<tr>
					<td><label>Annual Hours Worked per Biller</label></td>
					<td><input type="text" name="annual_hours_biller" id="annual_hours_biller" value="<?php echo $annual_hours_biller; ?>" </td>
			</tr>				
			<tr>
				<td><label>% of time spent e-billing</label></td>
				<td><input type="text" name="percentage_reduction_biller_time_ebh" id="percentage_reduction_biller_time_ebh" value="<?php echo $percentage_reduction_biller_time_ebh; ?>" /></td>
			</tr>	
						
			</table>
		 	</div>
		 	<!--=========================================================== #2 ========= BILLER TIME ===============================================-->	
		 	
		 	<div class="tab-pane" id="3">			
			<table id="model_assumptions">
			<tr>
				<td colspan=3><p><?php echo $page3_paragraph; ?></p></td>
			</tr>
			<tr>
				<td><label># of Collectors</label></td>
				<td><input type="text" name="no_collectors" id="no_collectors" value="<?php echo $no_collectors; ?>" /></td>
			</tr>
			<tr>
				<td><label>Average Collectors Hourly Rate</label></td>
				<td><input type="text" name="collectors_hourly_rate" id="collectors_hourly_rate" value="<?php echo $collectors_hourly_rate; ?>" /></td>
			</tr>			
			<tr>
				<td><label>Annual Hours Worked per Collector</label></td>
				<td><input type="text" name="annual_hours_collector" id="annual_hours_collector" value="<?php echo $annual_hours_collector; ?>" /></td>
			</tr>
			<tr>
				<td><label>% of time spent e-billing</label></td>
				<td><input type="text" name="percentage_reduction_collector_time_ebh" id="percentage_reduction_collector_time_ebh" value="<?php echo $percentage_reduction_collector_time_ebh; ?>" /></td>
				<td><a data-placement="top" rel="tooltip" href="#" data-original-title="Tooltip on top" class="roi-info"><img src="<?php echo plugin_dir_url(__FILE__); ?>images/info.png" /></a></td>
			</tr>	

			</table>
		 </div>
		 <!--=========================================================== #3 ========= COLLECTOR TIME ===============================================-->	
		 
		 	<div class="tab-pane" id="4">			 
		 <table>
			<tr>
				<td colspan=3><p><?php echo $page4_paragraph; ?></p></td>
			</tr>
			<tr>
				<td><label># of Attorneys with clients that e-bill</label></td>
				<td><input type="text" name="no_attorneys" id="no_attorneys" value="<?php echo $no_attorneys; ?>" /></td>
			</tr>
			<tr>
				<td><label>Average Attorney Hourly Rate</label></td>
				<td><input type="text" name="attorney_hourly_rate" id="attorney_hourly_rate" value="<?php echo $attorney_hourly_rate; ?>" /></td>
			</tr>
			<tr>
				<td><label>Attorney Hours per Month Spent e-billing Issues</label></td>
				<td><input type="text" name="attorney_hours_per_month_no_ebh" id="attorney_hours_per_month_no_ebh" value="<?php echo $attorney_hours_per_month_no_ebh; ?>" /></td>
			</tr>			
		</table>
	</div>
	<!--=========================================================== #4 ========= ATTORNEY TIME ===============================================-->
	
	<div class="tab-pane" id="5">			 
		 <table>
			<tr>
				<td colspan=3><p><?php echo $page5_paragraph; ?></p></td>
			</tr>
			<tr>
				<td><label>Annual # of New Clients</label></td>
				<td><input type="text" name="annual_no_new_clients" id="annual_no_new_clients" value="<?php echo $annual_no_new_clients; ?>" /></td>
			</tr>
			<tr>
				<td><label># of Hours to Generate New Template for Client</label></td>
				<td><input type="text" name="hours_template_new_client" id="hours_template_new_client" value="<?php echo $hours_template_new_client; ?>" /></td>
				<td><a data-placement="top" rel="tooltip" href="#" data-original-title="Tooltip on top" class="roi-info"><img src="<?php echo plugin_dir_url(__FILE__); ?>images/info.png" /></a></td>
			</tr>		
			<tr>
				<td><label>Average IT Hourly Rate</label></td>
				<td><input type="text" name="it_employee_hourly_rate" id="it_employee_hourly_rate" value="<?php echo $it_employee_hourly_rate; ?>" /></td>
			</tr>
			<tr>
				<td><label>Consulting Fee for Template Creation</label></td>
				<td><input type="text" name="it_template_creation" id="it_template_creation" value="<?php echo $it_template_creation; ?>" /></td>
			</tr>
			<tr>
				<td><label>Number of Annual Consultant Created Template</label></td>
				<td><input type="text" name="it_no_templates" id="it_no_templates" value="<?php echo $it_no_templates; ?>" /></td>
			</tr>
		</table>
	</div>
	<!--=========================================================== #5 ========= IT TIME ===============================================-->	
	
		
	<div class="tab-pane" id="6">			 
		 <table>
			<tr>
				<td colspan=3><p><?php echo $page6_paragraph; ?></p></td>
			</tr>
			<tr>
				<td><label>eBillingHub Cost</label></td>
				<td><input type="text" name="ebh_cost" id="ebh_cost" value="<?php echo $ebh_cost; ?>" /></td>
			</tr>
			<tr>
				<td colspan="2"><input type="button" id="roi_calc_button" class="btn" value="Calculate"></td>
			</tr>
		</table>
	</div>
	<!--=========================================================== #6 ========= IT TIME ===============================================-->	
	
</form>
</div>
        
<?php endif; // if ( $roi_ajax == false ): ?>

<?php if ( $roi_ajax == false ) : ?>
	<div id="roi-results">
<?php endif; ?>
 
<?php if ( $roi_ajax == true ): // Send the calculations back from ajax request ?>

<?php 
/*=========================================================================
	Display Summary table
===========================================================================*/
?>

		<table id="summary">
			<tr>
				<th></th>
				<th>Current Cost</th>
				<th>Cost with eBillingHub</th>
				<th>Savings</th>
				<th></th>
			</tr>
			<tr>
				<td>IT</td>
				<td>$<?php echo number_format($it_current_cost, 2, '.', ',') ?></td>
				<td>$<?php echo number_format($it_cost_ebh, 2, '.', ',') ?></td>
				<td>$<?php echo number_format($it_savings, 2, '.', ',') ?></td>
				<td><?php if (!$it_savings_description == '') { ?><a data-placement="right" rel="tooltip" href="#" data-original-title="<?php echo $it_savings_description; ?>" class="roi-info"><img src="<?php echo plugin_dir_url(__FILE__); ?>images/info.png" /></a><?php } ?></td>
			</tr>
			<tr>
				<td>Write-offs</td>
				<td>$<?php echo number_format($write_off_current_cost, 2, '.', ',') ?></td>
				<td>$<?php echo number_format($write_off_cost_ebh, 2, '.', ',') ?></td>
				<td>$<?php echo number_format($write_off_savings, 2, '.', ',') ?></td>
				<td><?php if (!$write_offs_savings_description == '') { ?><a data-placement="right" rel="tooltip" href="#" data-original-title="<?php echo $write_offs_savings_description; ?>" class="roi-info"><img src="<?php echo plugin_dir_url(__FILE__); ?>images/info.png" /></a><?php } ?></td>
			</tr>
			<tr>
				<td>Interest</td>
				<td>$<?php echo number_format($interest_current_cost, 2, '.', ',') ?></td>
				<td>$<?php echo number_format($interest_cost_ebh, 2, '.', ',') ?></td>
				<td>$<?php echo number_format($interest_savings, 2, '.', ',') ?></td>
				<td><?php if (!$interest_savings_due_description == '') { ?><a data-placement="right" rel="tooltip" href="#" data-original-title="<?php echo $interest_savings_due_description; ?>" class="roi-info"><img src="<?php echo plugin_dir_url(__FILE__); ?>images/info.png" /></a><?php } ?></td>
			</tr>
			<tr>
				<td>Attorney</td>
				<td>$<?php echo number_format($attorney_current_cost, 2, '.', ',') ?></td>
				<td>$<?php echo number_format($attorney_cost_ebh, 2, '.', ',') ?></td>
				<td>$<?php echo number_format($attorney_savings, 2, '.', ',') ?></td>
				<td><?php if (!$attorney_time_savings_description == '') { ?><a data-placement="right" rel="tooltip" href="#" data-original-title="<?php echo $attorney_time_savings_description; ?>" class="roi-info"><img src="<?php echo plugin_dir_url(__FILE__); ?>images/info.png" /></a><?php } ?></td>
			</tr>
			<tr>
				<td>Biller</td>
				<td>$<?php echo number_format($biller_current_cost, 2, '.', ',') ?></td>
				<td>$<?php echo number_format($biller_cost_ebh, 2, '.', ',') ?></td>
				<td>$<?php echo number_format($biller_savings, 2, '.', ',') ?></td>
				<td><?php if (!$biller_time_savings_description == '') { ?><a data-placement="right" rel="tooltip" href="#" data-original-title="<?php echo $biller_time_savings_description; ?>" class="roi-info"><img src="<?php echo plugin_dir_url(__FILE__); ?>images/info.png" /></a><?php } ?></td>
			</tr>
			<tr>
				<td>Collector</td>
				<td>$<?php echo number_format($collector_current_cost, 2, '.', ',') ?></td>
				<td>$<?php echo number_format($collector_cost_ebh, 2, '.', ',') ?></td>
				<td>$<?php echo number_format($collector_savings, 2, '.', ',') ?></td>
				<td><?php if (!$collector_time_savings_description == '') { ?><a data-placement="right" rel="tooltip" href="#" data-original-title="<?php echo $collector_time_savings_description; ?>" class="roi-info"><img src="<?php echo plugin_dir_url(__FILE__); ?>images/info.png" /></a><?php } ?></td>
			</tr>
			<tr>
				<td>Cost of eBillingHub</td>
				<td>$<?php echo number_format($cost_current_cost, 2, '.', ',') ?></td>
				<td>$<?php echo number_format($cost_cost_ebh, 2, '.', ',') ?></td>
				<td>$<?php echo number_format($cost_savings, 2, '.', ',') ?></td>
				<td><?php if (!$cost_of_ebh_description == '') { ?><a data-placement="right" rel="tooltip" href="#" data-original-title="<?php echo $cost_of_ebh_description ?>" class="roi-info"><img src="<?php echo plugin_dir_url(__FILE__); ?>images/info.png" /></a><?php } ?></td>
			</tr>
			<tr class="total">
				<td>Total</td>
				<td>$<?php echo number_format($total_current_cost, 2, '.', ',') ?></td>
				<td>$<?php echo number_format($total_cost_ebh, 2, '.', ',') ?></td>
				<td>$<?php echo number_format($total_savings, 2, '.', ',') ?></td>
				<td><button class="btn btn-mini">Details</button></td>
			</tr>
		</table>
		<p><?php echo $disclaimer; ?></p>

<?php
    endif; // if ( roi_ajax == true ) 
?>
<?php if ( $roi_ajax == false ) : ?>
</div>
<?php endif; ?>
<?php 
/*=========================================================================
	Detail
===========================================================================*/
?>
<div id="savings">
	<table>
		<tr>
			<th colspan="2" class="header">Savings</th>
		</tr>
		<tr>
			<th colspan="3">IT</th>
		</tr>
		<tr>
			<td>Annual # of New Clients</td>
			<td><?php echo $annual_no_new_clients; ?></td>
		</tr>
		<tr>
			<td>Hours to Generate Template for New Client</td>
			<td><?php echo $hours_template_new_client; ?></td>
		</tr>
		<tr>
			<td>Average IT Hourly Rate</td>
			<td>$<?php echo $it_employee_hourly_rate; ?></td>
		</tr>
		<tr>
			<td>Consultant Fee for Template Creation</td>
			<td>$<?php echo $it_template_creation; ?></td>
		</tr>	
		<tr>
			<td>Number of Annual Consultant Created Templates</td>
			<td>$<?php echo $it_no_templates; ?></td>
		</tr>	
		<tr class="total">
			<td>IT Savings</td>
			<td>$<?php echo number_format($it_savings, 2, '.', ','); ?></td>
		</tr>
	</table>
	<table>
		<tr>
			<th colspan="2">Write-offs</th>
		</tr>
		<tr>
			<td>Annual Revenue</td>
			<td>$<?php echo number_format($annual_revenue, 2, '.', ','); ?></td>
		</tr>
		<tr>
			<td>% Write-offs</td>
			<td><?php echo $percentage_write_off_no_ebh; ?>%</td>
		</tr>
		<tr>
			<td>Cost of Write-offs</td>
			<td class="sub-total">$<?php echo number_format($write_off_current_cost, 2, '.', ','); ?></td>
		</tr>
		<tr>
			<td>% Write-off by using eBillingHub</td>
			<td><?php echo $percentage_write_off_ebh; ?>%</td>
		</tr>
		<tr>
			<td>Cost of Write-offs with eBillingHub</td>
			<td class="sub-total">$<?php echo number_format($write_off_cost_ebh, 2, '.', ','); ?></td>
		</tr>
		<tr class="total">
			<td>Write-offs Savings</td>
			<td>$<?php echo number_format($write_off_savings, 2, '.', ','); ?></td>
		</tr>
	</table>
	<table>
		<tr>
			<th colspan="2">Cash Cycle</th>
		</tr>
		<tr>
			<td>Annual Revenue</td>
			<td>$<?php echo number_format($annual_revenue, 2, '.', ','); ?></td>
		</tr>
		<tr>
			<td>Dailey Revenue</td>
			<td>$<?php echo number_format($daily_revenue, 2, '.', ','); ?></td>
		</tr>
		<tr>
			<td>Reduction in Days to Pay by using eBillingHub</td>
			<td><?php echo $days_sales_outstanding_reduced_ebh; ?></td>
		</tr>
		<tr>
			<td>Improved cash Flow due to reduced Days to Pay</td>
			<td class="sub-total">$<?php echo number_format($improved_cash_flow, 2, '.', ','); ?></td>
		</tr>
		<tr>
			<td>Cost of Borrowing on Account Receivables</td>
			<td><?php echo $cost_borrowing_ar; ?>%</td>
		</tr>
		<tr class="total">
			<td>Interest Savings due to reduced Days to Pay</td>
			<td>$<?php echo number_format($interest_savings_dso, 2, '.', ','); ?></td>
		</tr>
	</table>
	<table>
		<tr>
			<th colspan="3">Attorney Time</th>
		</tr>
		<tr>
			<td>Attorney Hours per Month Spent E-Billing without EBH</td>
			<td><?php echo $attorney_hours_per_month_no_ebh; ?></td>
		</tr>
		<tr>
			<td># of Attorneys</td><td><?php echo number_format($no_attorneys); ?></td>
		</tr>
		<tr>
			<td>Attorney Hours per Year Spent E-Billing</td>
			<td class="sub-total"><?php echo number_format($attorney_hours_no_ebh); ?></td>
		</tr>
		<tr>
			<td>% Reduction in Attorney Time Spent E-Billing by using eBillingHub</td>
			<td><?php echo $percentage_reduction_attorney_time_ebh; ?>%</td>
		</tr>
		<tr>
			<td>Attorney Hours saved per year</td>
			<td class="sub-total"><?php echo number_format($attorney_hours_saved); ?></td>
		</tr>
		<tr>
			<td>Average Attorney Hourly Rate</td>
			<td>$<?php echo number_format($attorney_hourly_rate, 2, '.', ','); ?></td>
		</tr>
		<tr class="total">
			<td>Attorney Time Savings</td>
			<td>$<?php echo number_format($attorney_time_savings, 2, '.', ','); ?></td>
		</tr>
	</table>
	<table>
		<tr>
			<th colspan="3">Collector Time</th></tr>
		<tr>
			<td># of Collectors</td>
			<td><?php echo number_format($no_collectors); ?></td>
		</tr>
		<tr>
			<td>Annual Hours Worked per Collector</td>
			<td><?php echo number_format($annual_hours_collector); ?></td>
		</tr>
		<tr>
			<td>% of Collectors Time Spent E-Billing without EBH</td>
			<td><?php echo $percentage_collectors_no_ebh; ?>%</td>
		</tr>
		<tr>
			<td>Annual Collectors Hours Spent e-billing</td>
			<td class="sub-total"><?php echo number_format($collector_annual_hours_spent); ?></td>
		</tr>
		<tr>
			<td>% Reduction in Collectors Time Spent e-billing by using eBillingHub</td>
			<td><?php echo $percentage_reduction_collector_time_ebh; ?>%</td>
		</tr>
		<tr>
			<td>Collector Hours per Year Saved using eBillingHub</td>
			<td class="sub-total"><?php echo number_format($collector_hours_per_year); ?></td>
		</tr>
		<tr>
			<td>Average Collector Hourly Rate</td>
			<td>$<?php echo number_format($collectors_hourly_rate, 2, '.', ','); ?></td>
		</tr>
		<tr class="total">
			<td>Collector Time Savings</td>
			<td>$<?php echo number_format($collector_time_savings, 2, '.', ','); ?></td>
		</tr>
	</table>
	<table>
		<tr>
			<th colspan="2">Biller Time</th></tr>
		<tr>
			<td># of Billers</td>
			<td><?php echo number_format($no_billers); ?></td>
		</tr>
		<tr>
			<td>% of Time spent e-billing</td>
			<td><?php echo $percent_time_ebilling; ?>%</td>
		</tr>
		<tr>
			<td>Annual Hours Worked per Biller</td>
			<td><?php echo number_format($annual_hours_biller); ?></td>
		</tr>
		<tr>
			<td>Annual Biller Hours spent e-billing</td>
			<td class="sub-total"><?php echo number_format($biller_annual_spent_ebilling); ?></td>
		</tr>
		<tr>
			<td>% Reduction in Billers Time Spent E-Billing with EBH</td>
			<td><?php echo $percentage_reduction_biller_time_ebh; ?>%</td>
		</tr>
		<tr>
			<td>Biller Hours per Year Saved with EBH</td>
			<td class="sub-total"><?php echo number_format($biller_hours_saved); ?></td>
		</tr>
		<tr>
			<td>Average Billers Hourly Rate</td>
			<td>$<?php echo number_format($billers_hourly_rate, 2, '.', ','); ?></td>
		</tr>
		<tr>
			<td class="total">Biller Time Savings</td>
			<td class="total">$<?php echo number_format($biller_time_savings, 2, '.', ','); ?></td>
		</tr>
	</table>  
</div>

<?php
}
