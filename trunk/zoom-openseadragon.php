<?php
/* 
Plugin Name: Zoom OpenSeadragon
Plugin URI: http://altert.net
Author URI: http://altert.net
Version: 1.0
Text Domain: zoom-openseadragon
Domain Path: /lang
Author:Alexey Tikhonov
Description: OpenSeadragon Zoom is an implementation of [OpenSeadragon](http://openseadragon.github.io//), an open-source, web-based viewer for high-resolution zoomable images, implemented in pure JavaScript, for desktop and mobile.

It allows to create zoomable galleries from standart wordpress images as well as from deepzoom images.

*/



add_action( 'plugins_loaded', 'zoom_openseadragon_load_textdomain' );


function zoom_openseadragon_load_textdomain() {
  load_plugin_textdomain( 'zoom-openseadragon', false, dirname( plugin_basename( __FILE__ ) ) . '/lang' ); 
}

add_action('wp_enqueue_scripts', 'zoom_openseadragon_scripts_styles');

// Scripts initialization

function zoom_openseadragon_scripts_styles() {
	if(!is_admin()) {
	wp_register_style( 'zoom_openseadragon', plugins_url( 'zoom-openseadragon/css/style.css' ) );
	wp_enqueue_style( 'zoom_openseadragon' );
	wp_enqueue_script ('jquery');
	wp_enqueue_script('openseadragon', plugin_dir_url( __FILE__ ) .'js/openseadragon.min.js', array('jquery'), '', true);
	}
}

// Add custom settings via settings API

function zoom_openseadragon_settings_api_init() {
	// Add the section to media settings so we can add our
	// fields to it
	add_settings_section(
		'zoom_openseadragon_setting_section',
		__('OpenSeadragon Zoom Options', 'zoom-openseadragon'),
		'zoom_openseadragon_setting_section_callback_function',
		'media'
	);
	

	add_settings_field(
		'zoom_openseadragon_setting_replace_gallery',
		__('Replace wordpress gallery', 'zoom-openseadragon'),
		'zoom_openseadragon_setting_callback_function_replace_gallery',
		'media',
		'zoom_openseadragon_setting_section'
	);
	
	
	
	// Register our setting so that $_POST handling is done for us and
	// our callback function just has to echo the <input>
    register_setting( 'media', 'zoom_openseadragon_setting_replace_gallery' );
 
}


add_action( 'admin_init', 'zoom_openseadragon_settings_api_init' );

// OpenSeadragon Zoom options on media settings

function zoom_openseadragon_setting_section_callback_function() {
	echo '<p>'.__('Options related to OpenSeadragon plugin', 'zoom-openseadragon').'</p>';
}



function zoom_openseadragon_setting_callback_function_replace_gallery() {
	echo '<input name="zoom_openseadragon_setting_replace_gallery" id="zoom_openseadragon_setting_replace_gallery" type="checkbox" value="1" class="code" ' . checked( 1, get_option( 'zoom_openseadragon_setting_replace_gallery' ), false ) . ' /> '.__('Replaces all galleries with OpenSeadragon Zoom galleries', 'zoom-openseadragon');

}



// Custom filter function to modify default gallery shortcode output
$zoom_openseadragon_galleryid=0;
function zoom_openseadragon_gallery( $output, $attr ) {

	// Initialize
	global $post, $wp_locale,$zoom_openseadragon_galleryid;
    $return = $output;


	if ( isset( $attr['orderby'] ) ) {
		$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
		if ( ! $attr['orderby'] ) unset( $attr['orderby'] );
	}

	// Get attributes from shortcode
	extract( shortcode_atts( array(
		'order'      => 'ASC',
		'orderby'    => 'menu_order ID',
		'id'         => $post->ID,
		'itemtag'    => 'dl',
		'icontag'    => 'dt',
		'captiontag' => 'dd',
		'columns'    => 3,
		'sizes'       => 'thumbnail,medium,medium_large,large',
		'include'    => '',
		'exclude'    => '',
		'width' => '600',
		'height' => '600',	
		'openseadragon' => '',
		'size' => '',
		'shownavigationcontrol' => 'true',
		'showzoomcontrol' => 'true',
		'showhomecontrol' => 'true',
		'showfullpagecontrol' => 'true',
		'showrotationcontrol' => 'false',
		'showsequencecontrol' => 'true',
		'sequencemode' => 'false',
		'showreferencestrip' => 'false',
		'shownavigator' => 'false',
		'navigatorid' => '""',
		'navigatorposition' => 'TOP_RIGHT',
		'referencestripsizeratio' => '0.2',
		'referencestripposition' => 'BOTTOM_LEFT',
		'referencestripscroll' => 'horizontal',
		'zoomimages' => '',
		'noattachments' => false,
		'captions' => true,
		'caption' => ''
		
		
		
		
		
		
	), $attr ) );
	
	
	


	if (!get_option( 'zoom_openseadragon_setting_replace_gallery' , false ) && !(($openseadragon=="true")||($openseadragon=="1"))) return $return;
	
	$images=0;
	
    if (!$noattachments) {
        
        $sizes=explode(",", $sizes);
    	$id = intval( $id );
    	$attachments = array();
    	if ( $order == 'RAND' ) $orderby = 'none';
    
    	if ( ! empty( $include ) ) {
    
    		// Include attribute is present
    		$include = preg_replace( '/[^0-9,]+/', '', $include );
    		$_attachments = get_posts( array( 'include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby ) );
    
    		// Setup attachments array
    		foreach ( $_attachments as $key => $val ) {
    			$attachments[ $val->ID ] = $_attachments[ $key ];
    		}
    
    	} else if ( ! empty( $exclude ) ) {
    
    		// Exclude attribute is present 
    		$exclude = preg_replace( '/[^0-9,]+/', '', $exclude );
    
    		// Setup attachments array
    		$attachments = get_children( array( 'post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby ) );
    	} else {
    		// Setup attachments array
    		$attachments = get_children( array( 'post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby ) );
    	}
    
    	if ( empty( $attachments ) ) return $return;
    
    	// Filter gallery differently for feeds
    	if ( is_feed() ) {
    		$output = "\n";
    		foreach ( $attachments as $att_id => $attachment ) $output .= wp_get_attachment_link( $att_id, $size, true ) . "\n";
    		return $output;
    	}
    
    
        global $_wp_additional_image_sizes;
    
    	$imsizes = array();
    
    	foreach ( get_intermediate_image_sizes() as $_size ) {
    	    if ( in_array($_size, $sizes)) {
        		if ( in_array( $_size, array('thumbnail', 'medium', 'medium_large', 'large') ) ) {
        			$imsizes[ $_size ]['width']  = get_option( "{$_size}_size_w" );
        			$imsizes[ $_size ]['height'] = get_option( "{$_size}_size_h" );
        			$imsizes[ $_size ]['crop']   = (bool) get_option( "{$_size}_crop" );
        		} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
        			$imsizes[ $_size ] = array(
        				'width'  => $_wp_additional_image_sizes[ $_size ]['width'],
        				'height' => $_wp_additional_image_sizes[ $_size ]['height'],
        				'crop'   => $_wp_additional_image_sizes[ $_size ]['crop'],
        			);
        		} 
    		}
    	}
        $numsizes=count($imsizes);
    
    	// Iterate through the attachments 
    	
        $images=count($attachments);
        if ($images<1) return;
    
        }
    
    $i = 0;

	$output="<script type='text/javascript'>jQuery(document).ready(function(){var viewer=OpenSeadragon({";
	$output.="id: 'deepzoomContainer".$zoom_openseadragon_galleryid."',";
	$output.="prefixUrl:'".plugins_url( 'images/', __FILE__ )."',";
	if ($images>1) $output.="collectionMode:true,";
	$output.="collectionRows:false,";
	$output.="collectionColumns:".$columns.",";
    $output.="showNavigationControl:".$shownavigationcontrol.",";
    $output.="showZoomControl:".$showzoomcontrol.",";
    $output.="showHomeControl:".$showhomecontrol.",";
    $output.="showFullPageControl:".$showfullpagecontrol.",";
    $output.="showRotationControl:".$showrotationcontrol.",";
    $output.="showSequenceControl:".$showsequencecontrol.",";
    $output.="sequenceMode:".$sequencemode.",";
    $output.="showReferenceStrip:".$showreferencestrip.",";
    $output.="showNavigator:".$shownavigator.",";
    $output.="navigatorId:".$navigatorid.",";
    $output.="navigatorPosition:'".$navigatorposition."',";
    $output.="referenceStripSizeRatio:".$referencestripsizeratio.",";
    $output.="referenceStripScroll:'".$referencestripscroll."',";
    $output.="referenceStripPosition:'".$referencestripposition."',";
    $output.="tileSources: [";

	foreach ( $attachments as $id => $attachment ) {
        $output.="{title: '".addslashes(htmlspecialchars($attachment->post_excerpt))."', type: 'legacy-image-pyramid',
                levels:[";
	    $j=0;
		foreach ( $imsizes as $size ) {
            $image_attributes = wp_get_attachment_image_src( $id, $sizes[ $size ] );
            $output .= "{url: '".$image_attributes[0]."',width: ".$image_attributes[1].",height:".$image_attributes[2]."}";
            $j++;
		    if ($j<$numsizes) $output.=","; 
		}
		$i++;
		$output .= "]}";
        if ($i<$images) $output.=",";
     }
    if ($zoomimages!="") {
        $zoomimages=explode(",",$zoomimages);
        $numzoomimages=count($zoomimages);
    
        if ($numzoomimages>0) {
            $i=0;
            if ($images>0) $output.=",";
            foreach ($zoomimages as $zoomimage) {
                $output.="{title:'test',tileSource:'".$zoomimage."'}";
                $i++;
                if ($i<$numzoomimages) $output.=",";
            }
        }
    }
	

	$output .= "]});";
	$output .="
	viewer.curItem=viewer.initialPage;
	viewer.curZoom=viewer.initialPage;
	
	viewer.goTo = function(index) {  // zoom on specific resource from those on screen             
	     this.curItem=index;    
	     if (this.sequenceMode) this.goToPage(this.curItem);
	     else this.viewport.fitBounds(this.world.getItemAt(this.curItem).getBounds());
	     this.curZoom=this.curItem;  
       
	}
	viewer.hitTest = function(position) { // return resource under cursor
	    var box;
	    var count = this.world.getItemCount();
	    for (var i = 0; i < count; i++) {
	        box = this.world.getItemAt(i).getBounds();
	        if (position.x > box.x && 
	                position.y > box.y && 
	                position.x < box.x + box.width &&
	                position.y < box.y + box.height) {
	            return i;
	        }
	    }
	
	    return -1;
	}
	viewer.showMeta = function (i) 
	       {                        
	       if (i!=-1) {
	           var title=this.tileSources[i].title;
	           if (title!='') jQuery('#deepzoomCaption".$zoom_openseadragon_galleryid."').html(title);	          
	          }
	      
	       }
	viewer.addHandler('page', function (data) {
	   viewer.showMeta(data.page);
	  
	});
	viewer.addHandler('canvas-click', function(event) {
	    if (viewer.sequenceMode==true) return;
	    if (!event.quick) {
	        return;
	    }                     
	    var index = viewer.hitTest(viewer.viewport.pointFromPixel(event.position));   
	    if ((index !== -1)&&(index!=viewer.curZoom)) {
	        viewer.goTo(index); 
	        viewer.showMeta(index);                         
	    }
	
	});
	
    
        if (viewer.sequenceMode==true) viewer.showMeta(viewer.initialPage);
   
	
	
	";
	$output .= "});"; // end of document ready
    $output .= "</script>";
    if ($captions) $output.="<figure  style='width: ".$width."px' class='wp-caption alignnone'>";
    $output .= "<div class='deepzoomContainer' id='deepzoomContainer".$zoom_openseadragon_galleryid."' style='width: ".$width."px; height: ".$height."px;'></div>";
    if ($captions) $output.="<figcaption id='deepzoomCaption".$zoom_openseadragon_galleryid."' class='wp-caption-text'>".$caption."</figcaption></figure>";
    $zoom_openseadragon_galleryid++;
	return $output;

}

// Apply filter to default gallery shortcode
add_filter( 'post_gallery', 'zoom_openseadragon_gallery', 10, 2 );
