(function( $ ) {
	'use strict';
	
	jQuery(document).ready(function(){

		/*
		* Optimize data base 'Delete All' Button
		*/
		jQuery(document).on('submit','.clean_table form',function(event){
			event.preventDefault();
			var typeName = jQuery(this).find('[name="type"]').data('name');
			if (confirm('Are you sure you want to delete the '+typeName+' ?')) {
				var data = jQuery(this).serialize()  + '&action=aiowc_revision_cleaner';
				var typeValue = jQuery(this).find('[name="type"]').val();
				var loder = jQuery(this).find('.box');	
				loder.show();		
				jQuery.ajax({
			      	type: 'POST', 
			      	url: ajaxObj.ajaxurl, 
			      	data: data, 
			      	success: function(response) {
				        response = JSON.parse(response);
				        if(response.status == 200){
					        jQuery('#clean_and_optimizer_wrapper .ajax_response').show().text(response.message);
					        jQuery('#clean_and_optimizer_wrapper .ajax_response').hide().text('');
					        loder.hide();
							location.reload();
						}else{
							alert(response.message);
						}
				    },
				    error: function(xhr, status, error) {
						console.error(error);
				    }
			    });
			}
		});

		/*
		* Delete inActive Plugin AJAX
		*/
		jQuery(document).on('click', '#deletePluginBtn', function() {
		    var selectedPlugin = jQuery('input[name="selected_plugin[]"]:checked');
		    var pluginIds = selectedPlugin.map(function() {
		      	return jQuery(this).val();
		    }).get();

		    if (pluginIds.length === 0) {
		      	alert('Please select Plugin to delete.');
		      	return;
		    }

		    if (confirm('Are you sure you want to delete the selected Plugin?')) {
		    	var nonce = jQuery('#aiowc_plugin_nonce').val();
		      	jQuery.ajax({
			        url: ajaxObj.ajaxurl,
			        type: 'POST',
			        data: {
			          action: 'aiowc_delete_selected_plugin',
			          pluginIds: pluginIds,
			          nonce : nonce
			        },
			        success: function(response) {
			        	if(response.status == 200){
			        		location.reload();
			        	}else{
			        		alert(response.message);
			        	}
			        }
		      	});
		    }
		});		
		
		/*
		* Delete inActive Theme AJAX
		*/
		jQuery(document).on('click', '#deleteThemeBtn', function() {
		    var selectedTheme = jQuery('input[name="selected_theme[]"]:checked');
		    var themeIds = selectedTheme.map(function() {
		      	return jQuery(this).val();
		    }).get();

		    if (themeIds.length === 0) {
		      	alert('Please select theme to delete.');
		      	return;
		    }

		    if (confirm('Are you sure you want to delete the selected Theme?')) {
		    	var nonce = jQuery('#aiowc_theme_nonce').val();
			    jQuery.ajax({
			        url: ajaxObj.ajaxurl,
			        type: 'POST',
			        data: {
			          	action: 'aiowc_delete_selected_theme',
						themeIds: themeIds,
						nonce : nonce
			        },
			        success: function(response) {
			        	if(response.status == 200){
			        		location.reload();
			        	}else{
			        		alert(response.message);
			        	}
			        }
			    });
			}
		});	

		
		/*
		* Plugin Chart Details
		*/
		if(jQuery('.plugin_chart_data, .media_chart_data, .theme_chart_data, .wordpress_chart_data').length){

			// Plugin Chart
			var plugin_array = [];
			jQuery('.plugin_chart_data').each(function(index) {
			    var label = jQuery(this).find('.title').text();
			    var yValue = parseFloat(jQuery(this).find('.value').text());
			    var color = jQuery(this).find('.color').data('color');
			    plugin_array.push({
			        label: label,
			        yValue: yValue,
			        color: color
			    });
			});

			pieChart(plugin_array, 'pluginChart');

			// Media Chart
			var media_array = [];
			jQuery('.media_chart_data').each(function(index) {
			    var label = jQuery(this).find('.title').text();
			    var yValue = parseFloat(jQuery(this).find('.value').text());
			    var color = jQuery(this).find('.color').data('color');
			    media_array.push({
			        label: label,
			        yValue: yValue,
			        color: color
			    });
			});

			pieChart(media_array, 'mediaChart');

			// Theme Chart
			var theme_array = [];
			jQuery('.theme_chart_data').each(function(index) {
			    var label = jQuery(this).find('.title').text();
			    var yValue = parseFloat(jQuery(this).find('.value').text());
			    var color = jQuery(this).find('.color').data('color');
			    theme_array.push({
			        label: label,
			        yValue: yValue,
			        color: color
			    });
			});
			pieChart(theme_array, 'themeChart');

			

			function pieChart(dynamicChartData, classname) {
				var oilCanvas = document.getElementById(classname);
				Chart.defaults.global.defaultFontSize = 14;
				var oilData = {
				    labels: dynamicChartData.map(function(data) {
				        return data.label;
				    }),
				    datasets: [{
				        data: dynamicChartData.map(function(data) {
				            return data.yValue;
				        }),
				        backgroundColor: dynamicChartData.map(function(data) {
				            return data.color;
				        })
				    }]
				};
				var pieChart = new Chart(oilCanvas, {
				    type: 'pie',
				    data: oilData,
				    options: {
				        legend: {
				            display: false
				        }
				    }
				});
			}
	  	}
	  	
		/*
		* Optimize Data Table Chart JS
		*/
	  	if(jQuery('.table_list_data').length){  			
	  			
	  		// Theme Chart
			var table_array = [];
			jQuery('.table_list_data').each(function(index) {
			   	var label = jQuery(this).find('.title').text();
				var yValue = parseFloat(jQuery(this).find('.value').text());
				var color = generateRandomColor();
				if(yValue != 0){
				    table_array.push({
				        label: label,
				        yValue: yValue,
				        color: color
				    });
				}
			});

			allChart(table_array, 'allChart'); 

			function allChart(dynamicChartData, classname) {			
				var oilCanvas = document.getElementById(classname);
				Chart.defaults.global.defaultFontSize = 14;
				var oilData = {
				    labels: dynamicChartData.map(function(data) {
				        return data.label;
				    }),
				    datasets: [{
				        data: dynamicChartData.map(function(data) {
				            return data.yValue;
				        }),
				        backgroundColor: dynamicChartData.map(function(data) {
				            return data.color;
				        })
				    }]
				};
				var pieChart = new Chart(oilCanvas, {
				    type: 'bar',
				    data: oilData,
				    options: {
				        legend: {
				            display: false
				        }
				    }
				});
			}
		}

  		function generateRandomColor() {
  			return '#' + Math.floor(Math.random()*16777215).toString(16);
  		}

  		/*
  		* Media View page Single media delete button
  		*/
		jQuery('.delete-item-button').on('click', function () {
	        var postID = jQuery(this).data('media_id');
	        if (confirm('Are you sure you want to delete this image?')) {
	        	var nonce = jQuery('#aiowc_media_single_delete_nonce').val();
	            jQuery.ajax({
	                type: 'POST',
	                url: ajaxObj.ajaxurl, 
	                data: {
	                    action: 'single_attachment_delete',
	                    post_id: postID,
	                    nonce : nonce
	                },
	                success: function (response) {
	                	response = jQuery.parseJSON(response);
	                	if(response.status == 200){
			        		location.reload();
			        	}else{
			        		alert(response.message);
			        	}
	                }
	            });
	        }
	    });

  		/*
  		* Media View page Multiple media delete button
  		*/
jQuery(document).ready(function($) {
    const selectAllCheckbox = $('#select_all_cleaning');
    const deleteAllBtn = $('#delete_all_selected_btn');

    function toggleDeleteAllButton() {
        const anyChecked = $('#media_list tbody .det_checkbox').is(':checked');
        deleteAllBtn.toggle(anyChecked);
    }

    selectAllCheckbox.on('change', function() {
        const isChecked = this.checked;
        $('#media_list tbody .det_checkbox').prop('checked', isChecked);
        toggleDeleteAllButton();
        if (isChecked) {
            $(".all_delete_media").css("display", "inline-block");
        } else {
            $(".all_delete_media").css("display", "none");
        }
    });

    $('#media_list').on('change', '.det_checkbox', function() {
        const allChecked = $('#media_list tbody .det_checkbox').length === $('#media_list tbody .det_checkbox:checked').length;
        selectAllCheckbox.prop('checked', allChecked);
        toggleDeleteAllButton();
    });

    deleteAllBtn.on('click', function() {
        const selectedIds = $('#media_list tbody .det_checkbox:checked').map(function() {
            return this.value;
        }).get();

        if (selectedIds.length > 0) {
            const nonce = $('#aiowc_media_multiple_delete_nonce').val();
            const data = {
                action: 'aiowc_delete_multiple_media',
                media_ids: selectedIds,
                nonce: nonce,
            };

            $.post(ajaxurl, data, function(response) {
                location.reload();
            });
        }
    });

    if ($('#media_list').length) {
        if ($.fn.DataTable.isDataTable('#media_list')) {
            $('#media_list').DataTable().destroy();
        }

        const table = $('#media_list').DataTable({
            paging: true,
            lengthChange: true,
            pageLength: 10,
        });

        // Functionality for selectAllCheckbox on table redraw
        table.on('draw', function() {
            const isChecked = selectAllCheckbox.is(':checked');
            $('#media_list tbody .det_checkbox').prop('checked', isChecked);
            toggleDeleteAllButton();
        });

        selectAllCheckbox.on('click', function() {
            const isChecked = $(this).is(':checked');
            $('#media_list tbody .det_checkbox').prop('checked', isChecked);
            toggleDeleteAllButton();
        });
    }
});



		/*
	    * Create data table for the media list view
	    */
		if(jQuery('#media_list').length){
	    	new DataTable('#media_list', {
		        ordering: true,
		        columnDefs: [
		            { targets: [1,3,7], orderable: false }
		        ]
		    });
	    }

	    /*
	    * Gallery List page check box click event
	    */
	    if(jQuery('.det_checkbox').length){
	    	jQuery(".det_checkbox").on("change", function() {
		        var atLeastOneChecked = false;
		        var checkedCount = 0;

		        jQuery(".det_checkbox").each(function() {
		            if (jQuery(this).prop("checked")) {
		                checkedCount++;
		                atLeastOneChecked = true;

		                /*if (checkedCount > 2) {
		                    alert("You can delete a maximum of two images.");
		                    jQuery(this).prop("checked", false);
		                    return false;
		                }*/
		            }
		        });

		        if (atLeastOneChecked) {
		            jQuery(".all_delete_media").css("display", "inline-block");
		        } else {
		            jQuery(".all_delete_media").css("display", "none");
		        }
		    });
		}
	    /*
	    * Multiple image delete functionlity
	    */
		if(jQuery('.all_delete_media').length){
		    jQuery('.all_delete_media').on('click', function () {
	        	var checkedItems = [];
		        jQuery('.det_checkbox:checked').each(function() {
		            checkedItems.push(jQuery(this).val());
		        });

		        if (confirm('Are you sure you want to delete these images?')) {
		        	var nonce = jQuery('#aiowc_media_multiple_delete_nonce').val();
		            jQuery.ajax({
		                type: 'POST',
		                url: ajaxObj.ajaxurl,
		                data: {
		                    action: 'multiple_attachment_delete',
		                    postIDs: checkedItems,
		                    nonce : nonce
		                },
		                success: function (response) {
		                    response = jQuery.parseJSON(response);
		                	if(response.status == 200){
				        		location.reload();
				        	}else{
				        		alert(response.message);
				        	}
		                }
	            	});
		        }
		    });

		}
		if(jQuery('.w_all_data').length){
			getDirectorySizes();
		}
	});

function getDirectorySizes() {
    jQuery('.w_all_data .box').show();

    wp.apiRequest({
        path: '/wp-site-health/v1/directory-sizes'
    }).done(function(response) {
        jQuery('.w_all_data .box').hide();
        var html = ''; 
        // WordPress size
        html += '<div class="col-auto d-flex align-items-center pe-3 wordpress_chart_data"><span class="dot bg-primary color" data-color="#e67c73" style="background-color: #e67c73 !important;"></span>';
        html += '<span class="title">WordPress Directory Size&nbsp;</span><span class="d-none d-md-inline-block d-lg-none d-xxl-inline-block value" data-val="' + response.wordpress_size.raw + '">' + response.wordpress_size.size + '</span></div>';

        // Themes size
        html += '<div class="col-auto d-flex align-items-center pe-3 wordpress_chart_data"><span class="dot bg-primary color" data-color="#f7cb4d" style="background-color: #f7cb4d !important;"></span>';
        html += '<span class="title">Themes Directory Size&nbsp;</span><span class="d-none d-md-inline-block d-lg-none d-xxl-inline-block value" data-val="' + response.themes_size.raw + '">' + response.themes_size.size + '</span></div>';

        // Plugins size
        html += '<div class="col-auto d-flex align-items-center pe-3 wordpress_chart_data"><span class="dot bg-primary color" data-color="#41b375" style="background-color: #41b375 !important;"></span>';
        html += '<span class="title">Plugins Directory Size&nbsp;</span><span class="d-none d-md-inline-block d-lg-none d-xxl-inline-block value" data-val="' + response.plugins_size.raw + '">' + response.plugins_size.size + '</span></div>';

        // Uploads size
        html += '<div class="col-auto d-flex align-items-center pe-3 wordpress_chart_data"><span class="dot bg-primary color" data-color="#7baaf7" style="background-color: #7baaf7 !important;"></span>';
        html += '<span class="title">Uploads Directory Size&nbsp;</span><span class="d-none d-md-inline-block d-lg-none d-xxl-inline-block value" data-val="' + response.uploads_size.raw + '">' + response.uploads_size.size + '</span></div>';

        // Database size
        html += '<div class="col-auto d-flex align-items-center pe-3 wordpress_chart_data"><span class="dot bg-primary color" data-color="#ba67c8" style="background-color: #ba67c8 !important;"></span>';
        html += '<span class="title">Database Size&nbsp;</span><span class="d-none d-md-inline-block d-lg-none d-xxl-inline-block value" data-val="' + response.database_size.raw + '">' + response.database_size.size + '</span></div>';

        jQuery('.w_chart').html(html);
        jQuery('.total_installation').remove();
        jQuery('<span class="total_installation">Total installation size ' + response.total_size.size + '</span>').insertAfter(".w_all_data h6");

        var wordpress_array = [];
        jQuery('.wordpress_chart_data').each(function(index) {
            var label = jQuery(this).find('.title').text();
            var val = jQuery(this).find('.value').text();
            var color = jQuery(this).find('.color').data('color');
            var raw = parseFloat(jQuery(this).find('.value').data('val'));
            wordpress_array.push({
                label: label,
                raw: raw,
                val: val,
                color: color
            });
        });

        var oilCanvas = document.getElementById('wordpressChart');
        var oilData = {
            labels: wordpress_array.map(function(data) { return data.label; }),
            datasets: [{
                data: wordpress_array.map(function(data) { return data.raw; }),
                backgroundColor: wordpress_array.map(function(data) { return data.color; }),
                customLabel: wordpress_array.map(function(data) { return data.val; })
            }]
        };
        var pieChart = new Chart(oilCanvas, {
            type: 'pie',
            data: oilData,
            options: {
                legend: { display: false },
                tooltips: {
                    callbacks: {
                        label: function(tooltipItem, data) {
                            var label = data.labels[tooltipItem.index] || '';
                            var value = data.datasets[0].customLabel[tooltipItem.index];
                            return label + ': ' + value;
                        }
                    }
                }
            }
        });

    }).fail(function(jqXHR, textStatus, errorThrown) {
        jQuery('.w_all_data .box').hide();
        console.error('API Request Failed:', textStatus, errorThrown);

        var errorMessage = 'Directory sizes could not be returned.';
        if (jqXHR.responseJSON && jqXHR.responseJSON.message) {
            errorMessage += ' ' + jqXHR.responseJSON.message;
        }

        jQuery('.w_chart').html(errorMessage);
    });
}



})( jQuery );
