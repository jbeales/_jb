<?php

/**
 * Add multiple image sizes at once - useful for setting intermediate image sizes
 * that can be used by srcset/sizes.
 * 
 * @param  string $basename    The base size name, (will be appended with the width of each size).
 * @param  int    $base_width  The base width, (to determine the width/height ratio). Will be added to the $widths array.
 * @param  int    $base_height The base height, (to determine the width/height ratio ).
 * @param  bool   $crop        If the image should be cropped or not. @see https://developer.wordpress.org/reference/functions/add_image_size/
 * @param  int[]  $widths      An array of image widths that will be created as WP image sizes.
 * @return void
 */
function _jb_add_image_sizes( $basename, $base_width, $base_height, $crop, $widths ) {

	$ratio = $base_width / $base_height;

	// Add $base_width to $widths if it's not already there.
	if( !in_array( $base_width, $widths ) ) {
		array_push( $widths, $base_width );
	}

	// Create an image size for each value in $widths
	foreach( $widths as $width ) {
		add_image_size( $basename . $width, $width, round( $width / $ratio ), $crop );
	}
}

/**
 * By default WordPress only includes image files up to 1600px wide in a srcset 
 * attribute. For wider images we need to do better than that, so we're filtering
 * that value here.
 *
 * This could be overridden again for individual images by setting a higher 
 * priority for the filter.
 * 
 * @param  int    $width The width, in pixels, that WordPress thinks we should limit the srcset to.
 * @return int           The modified width, in pixels, (5000, in our case).
 */
function _jb_allow_wide_srcset( $width ) {
	return 5000;
}
add_filter( 'max_srcset_image_width', '_jb_allow_wide_srcset' );