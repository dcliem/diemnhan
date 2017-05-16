<?php
// markup layout option
/* Modify the Bootstrap Classes being applied
 * based on the Genesis template chosen
 */

// modify bootstrap classes based on genesis_site_layout
add_filter('bsg-classes-to-add', 'dn_modify_classes_based_on_template', 10, 3);

// remove unused layouts

function dn_layout_options_modify_classes_to_add( $classes_to_add ) {

    $layout = genesis_site_layout();

    // full-width-content       // supported
    if ( 'full-width-content' === $layout ) {
        $classes_to_add['content'] = 'col-sm-12';
    }

    // sidebar-content          // supported
    if ( 'sidebar-content' === $layout ) {
        $classes_to_add['content'] = 'col-sm-9 col-sm-push-3';
        $classes_to_add['sidebar-primary'] = 'col-sm-3 col-sm-pull-9';
    }

    // content-sidebar-sidebar  // supported
    if ( 'content-sidebar-sidebar' === $layout ) {
        $classes_to_add['content'] = 'col-sm-7';
        $classes_to_add['sidebar-primary'] = 'col-sm-3';
        $classes_to_add['sidebar-secondary'] = 'col-sm-2';
    }

    // sidebar-sidebar-content  // supported
    if ( 'sidebar-sidebar-content' === $layout ) {
        $classes_to_add['content'] = 'col-sm-7 col-sm-push-5';
        $classes_to_add['sidebar-primary'] = 'col-sm-3 col-sm-pull-5';
        $classes_to_add['sidebar-secondary'] = 'col-sm-2 col-sm-pull-10';
    }


    // sidebar-content-sidebar  // supported
    if ( 'sidebar-content-sidebar' === $layout ) {
        $classes_to_add['content'] = 'col-sm-7 col-sm-push-2';
        $classes_to_add['sidebar-primary'] = 'col-sm-3 col-sm-push-2';
        $classes_to_add['sidebar-secondary'] = 'col-sm-2 col-sm-pull-10';
    }

    return $classes_to_add;
};

function dn_modify_classes_based_on_template( $classes_to_add, $context, $attr ) {
    $classes_to_add = dn_layout_options_modify_classes_to_add( $classes_to_add );

    return $classes_to_add;
}

// add bootstrap classes
add_filter( 'genesis_attr_nav-primary',         'dn_add_markup_class', 10, 2 );
add_filter( 'genesis_attr_nav-secondary',       'dn_add_markup_class', 10, 2 );
add_filter( 'genesis_attr_site-header',         'dn_add_markup_class', 10, 2 );
add_filter( 'genesis_attr_site-inner',          'dn_add_markup_class', 10, 2 );
add_filter( 'genesis_attr_site-footer',         'dn_add_markup_class', 10, 2 );
add_filter( 'genesis_attr_structural-wrap',     'dn_add_markup_class', 10, 2 );
add_filter( 'genesis_attr_content-sidebar-wrap','dn_add_markup_class', 10, 2 );
add_filter( 'genesis_attr_content',             'dn_add_markup_class', 10, 2 );
add_filter( 'genesis_attr_sidebar-primary',     'dn_add_markup_class', 10, 2 );
add_filter( 'genesis_attr_sidebar-secondary',   'dn_add_markup_class', 10, 2 );
add_filter( 'genesis_attr_archive-pagination',  'dn_add_markup_class', 10, 2 );
add_filter( 'genesis_attr_entry-content',       'dn_add_markup_class', 10, 2 );
add_filter( 'genesis_attr_entry-pagination',    'dn_add_markup_class', 10, 2 );


function dn_add_markup_class( $attr, $context ) {
    // default classes to add
    $classes_to_add = apply_filters ('bsg-classes-to-add',
        // default bootstrap markup values
        array(
            'nav-primary'               => 'navbar navbar-default navbar-static-top',
            'nav-secondary'             => 'navbar navbar-inverse navbar-static-top',
            'site-header'               => 'container-fluid',
            'site-inner'                => 'container-fluid',
            'site-footer'               => 'container-fluid',
            'structural-wrap'           => 'row',
            'content-sidebar-wrap'      => 'row',
            'content'                   => 'col-sm-9',
            'sidebar-primary'           => 'col-sm-3',
            'sidebar-secondary'         => '',
            'archive-pagination'        => 'clearfix',
            'entry-content'             => 'clearfix',
            'entry-pagination'          => 'clearfix bsg-pagination-numeric',
        ),
        $context,
        $attr
    );

    // populate $classes_array based on $classes_to_add
    $value = isset( $classes_to_add[ $context ] ) ? $classes_to_add[ $context ] : array();

    if ( is_array( $value ) ) {
        $classes_array = $value;
    } else {
        $classes_array = explode( ' ', (string) $value );
    }

    // apply any filters to modify the class
    $classes_array = apply_filters( 'bsg-add-class', $classes_array, $context, $attr );

    $classes_array = array_map( 'sanitize_html_class', $classes_array );

    // append the class(es) string (e.g. 'span9 custom-class1 custom-class2')
    $attr['class'] .= ' ' . implode( ' ', $classes_array );

    return $attr;
}


// Move sidebar secondary inside content wrap
remove_action( 'genesis_after_content_sidebar_wrap', 'genesis_get_sidebar_alt' );
add_action(    'genesis_after_content',              'genesis_get_sidebar_alt' );

/**
 * Add class "bsg-pagination-numeric" or "bsg-pagination-prev-next" depending on
 * the pagination style selected in the Genesis theme options
 *
 * @since 0.7.0
 */
remove_filter( 'genesis_attr_archive-pagination', 'genesis_attributes_pagination' );

add_filter( 'bsg-add-class', 'dn_prev_next_or_numeric_archive_pagination', 10, 2 );

function dn_prev_next_or_numeric_archive_pagination( $classes_array, $context ) {
    if ( 'archive-pagination' !== $context ) {
        return $classes_array;
    }

    if ( 'numeric' === genesis_get_option( 'posts_nav' ) ) {
        $classes_array[] = 'bsg-pagination-numeric';
    } else {
        $classes_array[] = 'bsg-pagination-prev-next';
    }

    return $classes_array;

}

//Remove Form Allowed Tags Box
add_filter( 'comment_form_defaults', 'dn_comment_form_modifications' );

function dn_comment_form_modifications( $fields ) {

    $fields['comment_notes_after'] = '';

    return $fields;
}