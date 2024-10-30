<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.itpathsolutions.com
 * @since      1.0.0
 *
 * @package    Aiowc
 * @subpackage Aiowc/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$cleaning_tbl = $this->cleaning_tables;
$all_themes = count(wp_get_themes());
$inactive_themes_count = $this->inactive_themes_count(); 

$all_plugins = count(get_plugins());
$inactive_plugins_count = $this->inactive_plugins_count();
$activePlugins = $all_plugins - $inactive_plugins_count; 

$use_media_count = $this->get_unuse_media_count();
$total_media_count = wp_count_attachments();?>

<div id="clean_and_optimizer_wrapper" class="aiowc">
  	<div class="wrap">
  		<div class="mailtitle">
  			<h2><?php esc_html_e('Media Cleaner and Database Optimizer', 'aiowc'); ?></h2>
  			<a href="<?php echo esc_url(menu_page_url('view-optimize', false)); ?>" class="btn-start button-wiggle"><?php esc_html_e('Start Optimization', 'aiowc'); ?></a>
  		</div>
  		<?php 
  		$wp_version = get_bloginfo('version');
  		$php_version = phpversion();?>
  		<p class="php_version"><span>WordPress Current Version: <strong><?php echo $wp_version;?></strong></span><span>PHP Current Version: <strong><?php echo $php_version;?></strong></span></p>
  		<div class="row g-4 mb-4">
  			<div class="col-md-3 col-xxl-3">
  				<div class="card h-md-100 ecommerce-card-min-width">
  					<div class="card-header pb-0">
  						<h6 class="mb-0 mt-2 d-flex align-items-center"><?php esc_html_e('Plugins Insight', 'aiowc'); ?></h6>
  					</div>
  					<div class="card-body d-flex flex-column">
  						<div class="row">
  							<canvas id="pluginChart" style="height: 300px; width: 100%;"></canvas>
  						</div>
  						<div class="row fs-10 fw-semi-bold text-500 g-0 pt-4 pie_chart">
	                      	<div class="col-auto d-flex align-items-center pe-3 plugin_chart_data">
		                      	<span class="dot bg-primary color" data-color="#a43820" style="background-color: #a43820 !important;"></span>
		                      	<span class="title"><?php esc_html_e('Inactive Plugins', 'aiowc'); ?>&nbsp;</span>
		                      	<span class="d-none d-md-inline-block d-lg-none d-xxl-inline-block value"><?php echo esc_html($inactive_plugins_count); ?></span>
	                      	</div>
	                      	<div class="col-auto d-flex align-items-center pe-3 plugin_chart_data">
	                      		<span class="dot bg-info color" data-color="#00a6ff" style="background-color: #00a6ff !important;"></span>
	                      		<span class="title"><?php esc_html_e('Active Plugins', 'aiowc'); ?>&nbsp;</span>
	                      		<span class="d-none d-md-inline-block d-lg-none d-xxl-inline-block value"><?php echo esc_html($activePlugins); ?></span>
	                      	</div>	                      
	                    </div>
  					</div>
  				</div>
  			</div>
  			<div class="col-md-3 col-xxl-3">
  				<div class="card h-md-100 ecommerce-card-min-width">
  					<div class="card-header pb-0">
  						<h6 class="mb-0 mt-2 d-flex align-items-center"><?php esc_html_e('Media Files Insight', 'aiowc'); ?></h6>
  					</div>
  					<div class="card-body d-flex flex-column">
  						<div class="row">
  							<canvas id="mediaChart" style="height: 300px; width: 100%;"></canvas>
  						</div>
  						<div class="row fs-10 fw-semi-bold text-500 g-0 pt-4 pie_chart">
	                      	<div class="col-auto d-flex align-items-center pe-3 media_chart_data">
		                      	<span class="dot bg-primary color" data-color="#523759" style="background-color: #523759 !important;"></span>
		                      	<span class="title"><?php esc_html_e('Used Media File', 'aiowc'); ?>&nbsp;</span>
		                      	<span class="d-none d-md-inline-block d-lg-none d-xxl-inline-block value"><?php echo esc_html($use_media_count); ?></span>
	                      	</div>
	                      	<div class="col-auto d-flex align-items-center pe-3 media_chart_data">
	                      		<span class="dot bg-info color" data-color="#9c5435" style="background-color: #9c5435 !important;"></span>
	                      		<span class="title"><?php esc_html_e('Unused Media File', 'aiowc'); ?>&nbsp;</span>
	                      		<span class="d-none d-md-inline-block d-lg-none d-xxl-inline-block value"><?php echo esc_html(array_sum((array)$total_media_count) - $use_media_count); ?></span>
	                      	</div>	                      
	                    </div>
  					</div>
  				</div>
  			</div>
  			<div class="col-md-3 col-xxl-3">
  				<div class="card h-md-100 ecommerce-card-min-width">
  					<div class="card-header pb-0">
  						<h6 class="mb-0 mt-2 d-flex align-items-center"><?php esc_html_e('Theme Insight', 'aiowc'); ?></h6>
  					</div>
  					<div class="card-body d-flex flex-column">
  						<div class="row">
  							<canvas id="themeChart" style="height: 300px; width: 100%;"></canvas>
  						</div>
  						<div class="row fs-10 fw-semi-bold text-500 g-0 pt-4 pie_chart">
	                      	<div class="col-auto d-flex align-items-center pe-3 theme_chart_data">
		                      	<span class="dot bg-primary color" data-color="#00539C" style="background-color: #00539C !important;"></span>
		                      	<span class="title"><?php esc_html_e('Inactive Themes', 'aiowc'); ?>&nbsp;</span>
		                      	<span class="d-none d-md-inline-block d-lg-none d-xxl-inline-block value"><?php echo esc_html($inactive_themes_count); ?></span>
	                      	</div>
	                      	<div class="col-auto d-flex align-items-center pe-3 theme_chart_data">
	                      		<span class="dot bg-info color" data-color="#ffd662" style="background-color: #ffd662 !important;"></span>
	                      		<span class="title"><?php esc_html_e('Active Theme', 'aiowc'); ?>&nbsp;</span>
	                      		<span class="d-none d-md-inline-block d-lg-none d-xxl-inline-block value"><?php echo esc_html($all_themes - $inactive_themes_count); ?></span>
	                      	</div>	                      
	                    </div>
  					</div>
  				</div>
  			</div>
  			<div class="col-md-3 col-xxl-3 w_all_data">
  				<div class="card h-md-100 ecommerce-card-min-width">
  					<div class="card-header pb-0 ">
  						<h6 class="mb-0 mt-2 d-flex align-items-center"><?php esc_html_e('Directories and Sizes', 'aiowc'); ?></h6>
  					</div>
  					<div class="card-body d-flex flex-column">
  						<div class="box"><div class="loader-02"></div></div>
  						<div class="row">
  							<canvas id="wordpressChart" style="height: 300px; width: 100%;"></canvas>
  						</div>
  						<div class="row fs-10 fw-semi-bold text-500 g-0 pt-4 pie_chart w_chart">
	                      	
	                    </div>
  					</div>
  				</div>
  			</div>
  		</div>
  		<div class="row g-12 mb-12">
  			<div class="col-md-12 col-xxl-12">
	  			<div class="card h-md-100 ecommerce-card-min-width">
	  				<div class="card-header pb-0">
						<h6 class="mb-0 mt-2 d-flex align-items-center"><?php esc_html_e('Database Insight', 'aiowc'); ?></h6>
					</div>
					<div class="card-body d-flex flex-column">
		  				<canvas id="allChart" style="height: 360px; width: 100%;"></canvas>
			  			<div class="table_list" style="display: none;">
							<?php  
							foreach ($cleaning_tbl as $cleaning_key => $cleaning_value) { 
								$cleanig_count = $this->aiowc_revision_cleaner_count($cleaning_key);?>
								<div class="table_list_data">
									<span class="title"><?php echo esc_html($cleaning_value); ?></span>
									<span class="value"><?php echo esc_html($cleanig_count); ?></span>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
  		</div>
  	</div>
</div>