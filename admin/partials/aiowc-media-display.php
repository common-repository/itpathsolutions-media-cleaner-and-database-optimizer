<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<div id="clean_and_optimizer_wrapper" class="aiowc">
    <div class="wrap">
        <h2><?php esc_html_e('Advanced Media Cleaner', 'aiowc'); ?></h2>
        <div class='media-section'>
            <div class="card overflow-hidden mb-3">
                <?php 
                wp_nonce_field('aiowc_media_single_delete_nonce','aiowc_media_single_delete_nonce' );
                wp_nonce_field('aiowc_media_multiple_delete_nonce','aiowc_media_multiple_delete_nonce' );
                $args = array(
                    'post_type' => 'attachment',
                    'numberposts' => -1,
                    'post_status' => null,
                    'post_parent' => null,
                    'post_mime_type' => 'image',
                );
                $attachments = get_posts($args);

                if ($attachments) { ?>
                    <div class="card-body p-0">
                        <div class="table-responsive scrollbar">

                            <table class="table fs-10 mb-0 overflow-hidden" id="media_list">
                                <thead class="bg-body-tertiary">
                                    <tr class="font-sans-serif">
                                        <th class="text-900 text-center-data">
                                            <input type="checkbox" id="select_all_cleaning" />

                                            <a href="javascript:;" class="all_delete_media" id="delete_all_selected_btn" title="Delete" style="display: none;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </a>
                                        </th>
                                        <th class="text-900 fw-medium align-middle sort-disabled text-center-data"><?php esc_html_e('Media','aiowc'); ?></th>
                                        <th class="text-900 fw-medium sort align-middle file_name_title text-center-data"><?php esc_html_e('Media Name','aiowc'); ?></th>

                                        <th class="text-900 fw-medium sort align-middle media-space text-center-data"><?php esc_html_e('Media Size','aiowc'); ?>
                                        </th>

                                        <th class="text-900 media-space fw-medium sort align-middle text-center-data">
                                            <?php esc_html_e('Media Count','aiowc'); ?></th>
                                        <th class="text-900 aiowc-width-fix-small fw-medium align-middle img_size_list sort text-center-data"><?php esc_html_e('Media Size Lists','aiowc'); ?></th>
                                        <th class="text-900 aiowc-width-fix-small fw-medium align-middle sort text-center-data"><?php esc_html_e('Used In','aiowc'); ?></th>
                                        <th class="text-900 fw-medium align-middle data-table-row-action sort-disabled text-center-data"><?php esc_html_e('Action','aiowc'); ?></th>
                                    </tr>
                                </thead>
                                <tbody class="list">
                                    <?php
                                    foreach ($attachments as $attachment) {
                                        $usage_info = [];
                                        $custom_post_type = get_post_types();
                                        $id = $attachment->ID;
                                        $usage_media = get_posts(
                                            array(
                                                'numberposts' => -1,
                                                'post_type' => $custom_post_type,
                                                'post_status' => array('publish', 'draft'),
                                                'meta_query' => array(
                                                    array(
                                                        'key' => '_thumbnail_id',
                                                        'value' => $id,
                                                        'compare' => '=',
                                                    )
                                                )
                                            )
                                        );
                                        if( !empty($usage_media)){
                                            foreach ($usage_media as $usage_post) {
                                                $usage_info[] = '<p><b>' . esc_html(get_post_type($usage_post)) . '</b>: <a href="' . esc_url(get_the_permalink($usage_post)) . '" target="_blank">' . esc_html(get_the_title($usage_post)) . '</a></p>';
                                            }
                                        }

                                        $content_media = get_posts(
                                            array(
                                                'numberposts' => -1,
                                                'post_type' => $custom_post_type,
                                                'post_status' => array('publish', 'draft'),
                                            )
                                        );

                                        if( !empty($content_media)){
                                            foreach ($content_media as $post_data) {
                                                $page_builder = '';
                                                $post_content = $post_data->post_content;
                                                if (strpos($post_content, 'wp-image-' . $id ) !== false) {
                                                    // Identify the page builder
                                                    $page_builder = $this->identify_page_builder($post_content);
                                                    $usage_info[] = '<p><b>' . esc_html(get_post_type($post_data->ID)) . '</b>: <a href="' . esc_url(get_the_permalink($post_data->ID)) . '" target="_blank">' . esc_html(get_the_title($post_data->ID) .' '. $page_builder) . '</a></p>';
                                                }

                                                if(!empty($page_builder)){
                                                    $usage_info[] = '<p><b>' . esc_html(get_post_type($post_data->ID)) . '</b>: <a href="' . esc_url(get_the_permalink($post_data->ID)) . '" target="_blank">' . esc_html(get_the_title($post_data->ID) .' '. $page_builder) . '</a></p>';
                                                }
                                            }
                                        }

                                        // Check the usage of images through ACF in any post.
                                        $post_ids = $this->get_post_from_attachment_id($id);
                                        if(isset($post_ids) && !empty($post_ids) && is_array($post_ids)){
                                            foreach ($post_ids as $post_id) {
                                                $usage_info[] = '<p><b>' . get_post_type($post_id) . '</b>: <a href="' . esc_url(get_the_permalink($post_id)) . '" target="_blank">' . esc_html(get_the_title($post_id)) . '</a></p>';
                                            }
                                        }

                                        $usage_info = array_unique($usage_info);

                                        $usage_info_string = implode(' ', $usage_info);

                                        $aiowc_get_media_list = $this->aiowc_get_media_list($id); ?>
                                        <tr class="btn-reveal-trigger fw-semi-bold">
                                            <td class="align-middle title text-center-data">
                                                <input type="checkbox" value="<?php echo esc_attr($id); ?>" class="det_checkbox" />
                                            </td>
                                            <td class="align-middle title text-center-data">
                                                <img src="<?php echo esc_url(wp_get_attachment_image_url($id)); ?>" alt="<?php echo esc_attr(get_the_title($id)); ?>" height="50" width="50" />
                                            </td>
                                            <td class="align-middle title desc text-center-data">
                                                <a href="<?php echo esc_url(wp_get_attachment_image_url($id)); ?>" target="_blank"><?php echo esc_attr(get_the_title($id)); ?></a>
                                            </td>
                                            <td class="align-middle title desc media-size-data text-center-data text-center-data" data-order="<?php echo filesize(get_attached_file($id)); ?>">
                                                <?php echo esc_html(size_format(filesize(get_attached_file($id)))); ?>
                                            </td>

                                            <td class="align-middle title desc media-count-data text-center-data text-center-data">
                                                <?php echo esc_html($this->aiowc_get_media_count($id)); ?>
                                            </td>
                                            <td class="align-middle title desc text-center-data">
                                                <?php 
                                                if (!empty($aiowc_get_media_list) && is_array($aiowc_get_media_list)) {
                                                    $i = 0;
                                                    $count = count($aiowc_get_media_list);
                                                    foreach ($aiowc_get_media_list as $filelist) {
                                                        echo wp_kses_post($filelist);
                                                        $i++;
                                                        if ( $i < $count) {
                                                            echo ', ';
                                                        }
                                                    }
                                                } else {
                                                    echo '-';
                                                } ?>
                                            </td>
                                            <td class="align-middle title desc text-center-data">
                                                <?php 
                                                if (!empty($usage_info_string)) {
                                                    echo wp_kses_post($usage_info_string);
                                                } else {
                                                   echo '-'; 
                                                }
                                                ?>
                                            </td>
                                            <td class="align-middle title desc text-center-data">
                                                <a href="javascript:void(0)" class="delete-item-button" data-media_id="<?php echo esc_attr( $id ); ?>"><svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg></a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php } else { ?>
                    <div class="alert alert-primary" role="alert"><?php esc_html_e('No media files found.', 'aiowc'); ?></div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
