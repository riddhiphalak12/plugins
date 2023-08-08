<?php
/**
 * 'employee-biography' Shortcode
 * 
 * @package Employee Biography Plugin
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Function to handle the 'employee-biography' shortcode
 * 
 * @since 1.0.0
 */
function empl_biography_render( $atts, $content ) {

    global $post;

	// Shortcode Parameters
	$atts  = shortcode_atts(array(
			'id'             => array(),
            'limit'          => 15,
            'orderby'        => 'date',
            'order'          => 'DESC',
            'extra_class'    => '',
	), $atts, 'employee_biography');

    $atts['limit']      = empl_biography_clean_number( $atts['limit'], 15, 'number' );
    $atts['orderby']    = !empty( $atts['orderby'] )                    ? $atts['orderby']              : 'date';
    $atts['order']      = ( strtolower( $atts['order'] ) == 'asc' )     ? 'ASC'                         : 'DESC';
    $atts['id']         = ! empty( $atts['id'] )                        ? explode( ',', $atts['id'] )   : array();
    
    extract( $atts );

    // Public Style enqueue
    wp_enqueue_style( 'empl-public-css' );

    // WP Query Parameters
    $query_args = array(
            'post_type'             => EMPL_BIOGRAPHY_POST_TYPE,
            'post_status'           => array( 'publish' ),
            'posts_per_page'        => $limit,
            'order'                 => $order,
            'orderby'               => $orderby,
        );

    // WP Query
    $post_query = new WP_Query( $query_args );

    // Output the employee biography HTML
    ob_start(); // Start output buffering

    // If post is there
    if ( $post_query->have_posts() ) { ?>

        <div class="empl-biography-main-container">
            <?php
            while ( $post_query->have_posts() ) : $post_query->the_post();
                
                // Retrieve the ACF field values for the specified employee ID
                $person_name 	    = get_field('person_name', $post->ID);
                $person_image 	    = get_field('person_image', $post->ID);
                $position_title     = get_field('position_title', $post->ID);
                $division_title     = get_field('division_title', $post->ID);
                $division_logo 	    = get_field('division_logo', $post->ID);
                $company_duration 	= get_field('company_duration', $post->ID);
                $bios_text 		    = get_field('bios_text', $post->ID);
                ?>
        
                <div class="empl-biography-container">
                    <div class="empl-biography-img-wrap">
                        <?php if ($person_image) : 
                            echo wp_get_attachment_image($person_image, 'medium', false, array('class' => 'person-image'));
                        endif; ?>
                    </div>
                    <div class="empl-biography-content-wrap">
                        <h2 class="person-name"><?php echo esc_html($person_name); ?></h2>
                        <?php if ($division_logo) :
                            echo wp_get_attachment_image($division_logo, 'medium', false, array('class' => 'division-logo'));
                        endif; ?>
                        <p class="position-title"><?php echo esc_html($position_title); ?> - <?php echo esc_html($division_title); ?></p>
                        <p>
                        	<span class="description company-duration"><?php _e('How long with the company?', 'employee-biography'); ?></span>
                        	<?php echo esc_html($company_duration); ?>
                        </p>
                        <div class="bios-text">
                            <?php echo wp_kses_post($bios_text); ?>
                        </div>
                    </div>
                </div>
            
            <?php endwhile; ?>
        </div> <!-- .empl-biography-container -->
    <?php 
    } else {
    
        echo sprintf( "<p>".__('No employee data found', 'employee-biography' )."</p>" );
    }    
    return ob_get_clean(); // Return the buffered content
    
}

add_shortcode('employee_biography', 'empl_biography_render');