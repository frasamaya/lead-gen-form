<?php
/*
Plugin Name: Lead Gen Form
Plugin URI: http://my-awesomeness-emporium.com
description: Plugin to do simple submission
Version: 1.0
Author: Faqih Amruddin
Author URI: http://faqihamruddin.com
License: GPL2
*/

class LgaGenForm {

  private $screens = array('submission');

  private $fields = array(
    array(
      'label' => 'Phone Number',
      'id' => 'lgf_phone',
      'type' => 'number',
     ),
    array(
      'label' => 'Email Address',
      'id' => 'lgf_email',
      'type' => 'email',
     ),
    array(
      'label' => 'Desired Budget',
      'id' => 'lgf_budget',
      'type' => 'number',
     ),
    array(
      'label' => 'Expected Project Duration',
      'id' => 'lgf_duration',
      'type' => 'text',
     ),
    array(
      'label' => 'Project Reference',
      'id' => 'lgf_references',
      'type' => 'text',
     ),
    array(
      'label' => 'Message',
      'id' => 'lgf_message',
      'type' => 'textarea',
     )  
  );

  
  public function __construct() {
    add_action( 'init', array( $this, 'lgf_submission_init' ) );
    add_action( 'add_meta_boxes', array( $this, 'lgf_meta_boxes' ) );
    add_action( 'save_post', array( $this, 'lgf_save_fields' ) );
    add_action('admin_print_footer_scripts',  array( $this,'lgf_add_quicktags'));
    add_shortcode( 'lgf_shortcode', array( $this, 'lgf_init_shortcode') );
    add_shortcode( 'lgf_field', array( $this,'lgf_field_shortcode' ));
  }

  /**
   * Register custom post type submission.
   *
   * @since 1.0
   *
   * @see register_post_type()
   * @link https://developer.wordpress.org/plugins/post-types/registering-custom-post-types/
   */
  public function lgf_submission_init() {
     $labels = array(
        'name'                  => _x( 'Submissions', 'Post Type General Name', 'submission' ),
        'singular_name'         => _x( 'Submission', 'Post Type Singular Name', 'submission' ),
        'menu_name'             => __( 'Submission', 'submission' ),
        'name_admin_bar'        => __( 'Submission', 'submission' ),
        'archives'              => __( 'Item Archives', 'submission' ),
        'attributes'            => __( 'Item Attributes', 'submission' ),
        'parent_item_colon'     => __( 'Parent Item:', 'submission' ),
        'all_items'             => __( 'All Items', 'submission' ),
        'add_new_item'          => __( 'Add New Item', 'submission' ),
        'add_new'               => __( 'Add New', 'submission' ),
        'new_item'              => __( 'New Item', 'submission' ),
        'edit_item'             => __( 'Edit Item', 'submission' ),
        'update_item'           => __( 'Update Item', 'submission' ),
        'view_item'             => __( 'View Item', 'submission' ),
        'view_items'            => __( 'View Items', 'submission' ),
        'search_items'          => __( 'Search Item', 'submission' ),
        'not_found'             => __( 'Not found', 'submission' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'submission' ),
        'featured_image'        => __( 'Featured Image', 'submission' ),
        'set_featured_image'    => __( 'Set featured image', 'submission' ),
        'remove_featured_image' => __( 'Remove featured image', 'submission' ),
        'use_featured_image'    => __( 'Use as featured image', 'submission' ),
        'insert_into_item'      => __( 'Insert into item', 'submission' ),
        'uploaded_to_this_item' => __( 'Uploaded to this item', 'submission' ),
        'items_list'            => __( 'Items list', 'submission' ),
        'items_list_navigation' => __( 'Items list navigation', 'submission' ),
        'filter_items_list'     => __( 'Filter items list', 'submission' ),
     );
     $args = array(
        'label'                 => __( 'Submission', 'submission' ),
        'description'           => __( 'Result of simple submission', 'submission' ),
        'labels'                => $labels,
        'supports'              => array( 'title' ),
        'taxonomies'            => array( 'submission_category', 'submission__tag' ),
        'hierarchical'          => false,
        'public'                => false,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'page',
        'show_in_rest'          => true,
     );
     register_post_type( 'submission', $args );

  }

  /**
   * Register custom post meta
   *
   * @since 1.0
   *
   * @link https://developer.wordpress.org/plugins/metadata/custom-meta-boxes/
   */
  public function lgf_meta_boxes() {
    foreach ( $this->screens as $s ) {
      add_meta_box(
        'additional_data',
        __( 'Additional Data', 'lgf_additional_data' ),
        array( $this, 'lgf_meta_box_callback' ),
        $s,
        'normal',
        'high'
      );
    }
  }

  /**
   * Custom post meta callback nonce
   *
   * @since 1.0
   *
   * @link https://developer.wordpress.org/plugins/metadata/custom-meta-boxes/
   */
  public function lgf_meta_box_callback( $post ) {
    wp_nonce_field( 'additional_data_data', 'additional_data_nonce' ); 
    $this->lgf_field_generator( $post );
  }

  /**
   * Generate custom post meta html form
   *
   * @since 1.0
   *
   */
  public function lgf_field_generator( $post ) {
    $output = '';
    foreach ( $this->fields as $field ) {
      $label = '<label for="' . $field['id'] . '">' . $field['label'] . '</label>';
      $meta_value = get_post_meta( $post->ID, $field['id'], true );
      if ( empty( $meta_value ) ) {
        if ( isset( $field['default'] ) ) {
          $meta_value = $field['default'];
        }
      }
      switch ( $field['type'] ) {
        case 'textarea':
          $input = sprintf(
            '<textarea style="width: 100%%" id="%s" name="%s" rows="5">%s</textarea>',
            $field['id'],
            $field['id'],
            $meta_value
          );
          break;
  
        default:
          $input = sprintf(
          '<input %s id="%s" name="%s" type="%s" value="%s">',
          $field['type'] !== 'color' ? 'style="width: 100%"' : '',
          $field['id'],
          $field['id'],
          $field['type'],
          $meta_value
        );
      }
      $output .= $this->lgf_format_rows( $label, $input );
    }
    echo '<table class="form-table"><tbody>' . $output . '</tbody></table>';
  }

  /**
   * Generate custom post meta form wrapper
   *
   * @since 1.0
   *
   * @return HTML
   */
  public function lgf_format_rows( $label, $input ) {
    return '<div style="margin-top: 10px;"><strong>'.$label.'</strong></div><div>'.$input.'</div>';
  }

  
  /**
   * Save custom post meta
   *
   * @since 1.0
   *
   */
  public function lgf_save_fields( $post_id ) {
    if ( !isset( $_POST['additional_data_nonce'] ) ) {
      return $post_id;
    }
    $nonce = $_POST['additional_data_nonce'];
    if ( !wp_verify_nonce( $nonce, 'additional_data_data' ) ) {
      return $post_id;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
      return $post_id;
    }
    foreach ( $this->fields as $field ) {
      if ( isset( $_POST[ $field['id'] ] ) ) {
        switch ( $field['type'] ) {
          case 'email':
            $_POST[ $field['id'] ] = sanitize_email( $_POST[ $field['id'] ] );
            break;
          case 'text':
            $_POST[ $field['id'] ] = sanitize_text_field( $_POST[ $field['id'] ] );
            break;
        }
        update_post_meta( $post_id, $field['id'], $_POST[ $field['id'] ] );
      } else if ( $field['type'] === 'checkbox' ) {
        update_post_meta( $post_id, $field['id'], '0' );
      }
    }
  }


  /**
   * Register parent shortcode.
   * 
   * [lgf_shortcode][/lgf_shortcode]
   *
   * @since 1.0
   *
   * @see add_shortcode()
   * @link https://codex.wordpress.org/Shortcode_API
   */
  public function lgf_init_shortcode($atts, $content = null) {
    $atts = shortcode_atts(
      array(
      ),
      $atts,
      'lgf_shortcode'
    );
    $form = "<form>";
    $form .= do_shortcode($content);
    $form .= '<div class="lgf_control"><button class="lgf_primary">Submit</button></div>';
    $form .= "</form>";
    return $form;
  }

  /**
   * Register child shortcode.
   * 
   * [lgf_field]
   *
   * @since 1.0
   *
   * @see add_shortcode()
   * @link https://codex.wordpress.org/Shortcode_API
   */
  public function lgf_field_shortcode($atts) {
    $atts = shortcode_atts(
      array(
        'name' => '',
        'label' => 'value',
        'required' => 'value',
        'maxlength' => 'value',
        'rows' => 'value'
      ),
      $atts,
      'lgf_field'
    );
    $name = $atts['name'];
    $label = $atts['label'];
    $required = $atts['required'];
    $maxlength = $atts['maxlength'];
    $rows = $atts['rows'];
    $type = 'text';
    foreach ( $this->fields as $field ) {
      if($name == $field['id']){
        $type = $field['type'];
      }
    }
    switch ( $name ) {
      case 'lgf_message':
        $input = sprintf(
          '<label for="%s">%s <span>%s</span></label>',
          $name,
          $label,
          ($required == true) ? '*' : ''
        );
        $input .= sprintf(
          '<textarea style="width: 100%%" id="%s" name="%s" rows="5"></textarea>',
          $name,
          $name,
        );
        break;

      default:
        $input = sprintf(
          '<label for="%s">%s <span>%s</span></label>',
          $name,
          $label,
          ($required == true) ? '*' : ''
        );
        $input .= sprintf(
          '<input id="%s" name="%s" type="%s">',
          $name,
          $name,
          $type,
        );
    }
    return '<div class="lgf_control">'.$input.'</div>';
  }
  
  /**
   * Add shortcode button to wp editor
   *
   * @since 1.0
   *
   */
  public function lgf_add_quicktags()
    { ?>
        <script type="text/javascript">
        QTags.addButton( 'lgf', 'LGF Shortcode', '[lgf_shortcode][lgf_field name="lgf_name" label="Name" required="true" maxlength="-1"][lgf_field name="lgf_phone" label="Phone Number" required="true" maxlength="-1"][lgf_field name="lgf_email" label="Email Address" required="true" maxlength="-1"][lgf_field name="lgf_budget" label="Desired Budget" required="true" maxlength="-1"][lgf_field name="lgf_duration" label="Expected Project Duration" required="true" maxlength="-1"][lgf_field name="lgf_references" label="Project Reference" required="true" maxlength="-1"][lgf_field name="lgf_message" label="Message" required="true" maxlength="-1" rows="3"][/lgf_shortcode]' );
        </script>
    <?php }
}

if (class_exists('LgaGenForm')) {
  new LgaGenForm;
};

