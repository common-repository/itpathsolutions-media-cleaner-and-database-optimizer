<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.itpathsolutions.com
 * @since      1.0.0
 *
 * @package    All_In_One_Wp_Cleaner_And_Optimizer
 * @subpackage All_In_One_Wp_Cleaner_And_Optimizer/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) exit;
$cleaning_tbl = $this->cleaning_tables;
$all_themes = count(wp_get_themes());
$inactive_themes_count = $this->inactive_themes_count(); 
$all_plugins = count(get_plugins());
$inactive_plugins_count = $this->inactive_plugins_count();
$activePlugins = $all_plugins - $inactive_plugins_count; ?>

<div id="clean_and_optimizer_wrapper" class="aiowc">
	<div class="wrap">
		<h2><?php esc_html_e('Advanced Database Optimizer', 'aiowc'); ?></h2>
		<div class="row mt-4">
			<div class="col-lg-3 col-md-3 col-sm-12 mb-3">
				<div class="card widget dashboard-widget h-100">
					<div class="card-body-personal">
						<div class="time-list">
							<div class="dash-stats-list">
								<h4><?php echo esc_html($all_plugins); ?></h4>
								<p><?php esc_html_e('Total Plugins','aiowc');?></p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-3 col-md-3 col-sm-12 mb-3">
				<div class="card widget dashboard-widget h-100">
					<div class="card-body-personal">
						<div class="time-list">
							<div class="dash-stats-list">
								<h4><?php echo esc_html($inactive_plugins_count); ?></h4>
								<p><?php esc_html_e('Inactive Plugins','aiowc');?></p>
								<a href="javascript:;" data-bs-toggle="modal" data-bs-target="#plugin_modal"><?php esc_html_e('View More', 'aiowc');?></a>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-3 col-md-3 col-sm-12 mb-3">
				<div class="card widget dashboard-widget h-100">
					<div class="card-body-personal">
						<div class="time-list">
							<div class="dash-stats-list">
								<h4><?php echo esc_html($all_themes); ?></h4>
								<p><?php esc_html_e('Total Themes','aiowc');?></p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-3 col-md-3 col-sm-12 mb-3">
				<div class="card widget dashboard-widget h-100">
					<div class="card-body-personal">
						<div class="time-list">
							<div class="dash-stats-list">
								<h4><?php echo esc_html($inactive_themes_count); ?></h4>
								<p><?php esc_html_e('Inactive Themes','aiowc');?></p>
								<a href="javascript:;" data-bs-target="#theme_modal" data-bs-toggle="modal"><?php esc_html_e('View More', 'aiowc');?></a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row mt-4">
			<div class="d-flex justify-content-between align-items-center">
				<h5><?php esc_html_e('Database Insight', 'aiowc');?></h5>
				<div class="cleaner_optimize_wrapper">
					<div class="clean_table">
						<h4 class="ajax_response" style="display:none;"></h4>
						<p></p>
						<form  method="post">
							<?php wp_nonce_field('aiowc_all_nonce','aiowc_all_nonce' ); ?>
							<input type="hidden" name="type" value="aiowc_revision_cleaner_all" data-name="All"/>
							<input type="submit" class="button-primary" value="<?php esc_attr_e('Delete All', 'aiowc'); ?>" />
							<div class="box" style="display: none;">
								<div class="loader-02"></div>
							</div>
						</form>
						<p></p>
						<h4 class="ajax_response" style="display:none;"></h4>
					</div>
				</div>
			</div>
		</div>
		<div class="cleaner_optimize_wrapper row mt-4">
			<?php  
			foreach ( $cleaning_tbl as $cleaning_key => $cleaning_value ) { 
				$cleanig_count = self::aiowc_revision_cleaner_count( $cleaning_key ); ?>
				<div class="col-lg-2 col-md-2 col-sm-12 mb-2">
					<div class="card widget dashboard-widget h-100">
						<div class="card-body-personal clean_table">
							<div class="time-list">
								<div class="dash-stats-list">
									<h4><?php echo esc_html( $cleanig_count );?></h4>
									<p><?php echo esc_html( $cleaning_value ); ?></p>
									<form method="post">
										<?php wp_nonce_field('aiowc_all_nonce','aiowc_all_nonce' ); ?>
										<input type="hidden" name="type" value="<?php echo esc_attr($cleaning_key); ?>" data-name="<?php echo esc_attr($cleaning_value); ?>" />
										<input type="submit" class="<?php echo esc_attr($cleanig_count > 0 ? 'button-red' : 'button-disable');  ?>" <?php echo $cleanig_count > 0 ? '' : 'disabled';  ?> value="<?php esc_attr_e('Delete', 'aiowc'); ?>" />
										<div class="box" style="display: none;">
											<div class="loader-02"></div>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			<?php } ?>
		</div>

		<div class="hidden_models">
			<!-- ********************** Inactive Plugin List Model ********************** -->
			<div class="modal fade" id="plugin_modal" tabindex="-1" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel"><?php esc_html_e('Inactive Plugins','aiowc'); ?></h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
							<?php
							$installed_plugins = get_plugins();
							$active_plugins = get_option('active_plugins');
							$inactive_plugins = array_diff_key($installed_plugins, array_flip($active_plugins));
							if (!empty($inactive_plugins)) {
								echo '<ul>';
								foreach ($inactive_plugins as $plugin_file => $plugin_info) {
									echo '<li><label><input type="checkbox" name="selected_plugin[]" value="' . esc_attr($plugin_file) . '">'.esc_html($plugin_info['Name']).'</label></li>';
								}
								echo '</ul>';
							} else {
								esc_html_e('No plugin found.','aiowc');
							}?>
						</div>
						<div class="modal-footer">
							<?php wp_nonce_field('aiowc_plugin_nonce','aiowc_plugin_nonce' ); ?>
							<button type="button" class="button-primary" id="deletePluginBtn"><?php esc_html_e('Delete Plugins','aiowc'); ?></button>
						</div>
					</div>
				</div>
			</div>
			<!-- ********************** Inactive Theme List Model ********************** -->	
			<div class="modal fade" id="theme_modal" tabindex="-1" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel"><?php esc_html_e('Inactive Themes','aiowc'); ?></h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
							<?php
							$installed_themes = wp_get_themes();
							$active_theme = wp_get_theme();
							$inactive_themes = array_filter($installed_themes, function ($theme) use ($active_theme) {
							    return $theme->get_stylesheet() !== $active_theme->get_stylesheet() &&
							           $theme->get_template() !== $active_theme->get_stylesheet() &&
							           $theme->get_stylesheet() !== $active_theme->get_template();
							});
							if (!empty($inactive_themes)) {
								echo '<ul>';
								foreach ($inactive_themes as $theme_file => $theme_info) {
									echo '<li><label><input type="checkbox" name="selected_theme[]" value="' . esc_attr($theme_info->get_stylesheet_directory()) . '"> ' . esc_html($theme_info['Name']) . '</label></li>';
								}
								echo '</ul>';
							} else {
								esc_html_e('No Theme found.','aiowc');
							}?>
						</div>
						<div class="modal-footer">
							<?php wp_nonce_field('aiowc_theme_nonce','aiowc_theme_nonce' ); ?>
							<button type="button" class="button-primary" id="deleteThemeBtn"><?php esc_html_e('Delete Themes','aiowc'); ?></button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
