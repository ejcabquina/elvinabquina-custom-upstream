<?php
/*
 * Function to manipulate all Gravity Form submissions before submitting to the database. $_POST array is available
 */
function enercare_gform_pre_submission( $form ) {
  // find the hidden form fields and copy them to their gform field
  foreach ($form['fields'] as $field_key => $field) {
    if (isset($_POST[$field->label])) {
      $_POST['input_'. $field->id] = $_POST[$field->label];
    } elseif (isset($_POST[$field->inputName])) {
      $_POST['input_'. $field->id] = $_POST[$field->inputName];
    } else if (isset($_POST[$field->adminLabel])) {
      $_POST['input_'. $field->id] = $_POST[$field->adminLabel];
    }
  }
}
add_action('gform_pre_submission', 'enercare_gform_pre_submission');

/* 
----------------------------------------------------------
CUSTOM GRAVITY FORMS ADD ON CLASS
----------------------------------------------------------
*/
define( 'ENERCARE_GF_ADDON_VERSION', '1.0' );
//add_action( 'gform_loaded', array( 'Enercare_GF_AddOn_Bootstrap', 'load' ), 5 );
add_action( 'init', array( 'Enercare_GF_AddOn_Bootstrap', 'load' ), 5 );
 
class Enercare_GF_AddOn_Bootstrap {
  public static function load() {
    if ( ! method_exists( 'GFForms', 'include_addon_framework' ) ) {
      return;
    }
    require_once( 'class-enercare-gfaddon.php' );
    GFAddOn::register( 'EnercareGFAddOn' );
  }
}
 
function enercare_gf_addon() {
  return EnercareGFAddOn::get_instance();
}

function enercare_gform_webhooks_post_request($response, $feed, $entry, $form) {
  $updated_entry = array();
  $updated_entry['webhookStatus_field_id'] = null;
  $updated_entry['webhookDate_field_id'] = null;
  
  // get instance of EnercareGFAddOn class for writing entry notes
  $enercareGFAddOn = enercare_gf_addon();
  
  /* uncomment to debug
  error_log(print_r($form,true));
  error_log(print_r($entry,true));
  error_log(print_r($feed,true));
  error_log(print_r($response,true));
  */
  
  foreach ($form['fields'] as $field_key => $field) {
    /* uncomment to debug
    error_log(print_r($field,true));
    error_log("field id: " . $field['id']);
    error_log("field->adminLabel: " . $field['adminLabel']);
    error_log("field->inputName: " . $field['inputName']);
    */
    // grab the MoveType field id for populating
    if ($field['adminLabel'] == "webhookStatus" || $field['inputName'] == "webhookStatus") {
      $updated_entry['webhookStatus_field_id'] = $field['id'];
    } elseif ($field['adminLabel'] == "webhookDate" || $field['inputName'] == "webhookDate") {
      $updated_entry['webhookDate_field_id'] = $field['id'];
    }
  }
  
  date_default_timezone_set('America/Toronto');
  $webhookDate = date('m/d/Y h:i:s A', time());
  // do something here if the request failed.
  if (is_wp_error($response)) {
    $webhookStatus = 'Fail';
    $enercareGFAddOn->add_note( $entry['id'], sprintf( esc_html__( 'Webhook was not successfully executed. %s (%d)', 'gravityformswebhooks' ), $response->get_error_message(), $response->get_error_code() ), 'error' );
    
  } else {
    if (isset($response['response']) && $response['response']['code'] == 200) {
      $webhookStatus = 'Success';
      $response_body = str_replace("<BR>","\n",$response['body']);
      $enercareGFAddOn->add_note( $entry['id'], sprintf( esc_html__( 'Webhook was successfully executed. Response details: %s', 'gravityformswebhooks' ), $response_body ), null );
    } elseif (isset($response['response'])) {
      $webhookStatus = 'Pending';
      $enercareGFAddOn->add_note( $entry['id'], sprintf( esc_html__( 'Webhook response returned HTTP code %d (%s)', 'gravityformswebhooks' ),$response['response']['code'],$response['response']['message'] ), null );
    }
  }
  
  /* uncomment to debug
  error_log("entryid: " . $entry['id']);
  error_log("webhookStatus: " . $webhookStatus);
  error_log("webhookStatus_field_id: " . $updated_entry['webhookStatus_field_id']);
  error_log("webhookDate: " . $webhookDate);
  error_log("webhookDate_field_id: " . $updated_entry['webhookDate_field_id']);
  */
  
  // update the webhook fields for the entry
  GFAPI::update_entry_field( $entry['id'], $updated_entry['webhookStatus_field_id'], $webhookStatus );
  GFAPI::update_entry_field( $entry['id'], $updated_entry['webhookDate_field_id'], $webhookDate );
}
add_action('gform_webhooks_post_request', 'enercare_gform_webhooks_post_request', 10, 4);

// triggers webhook(s) when an entry is updated
add_action( 'gform_after_update_entry', function ( $form, $entry_id ) {
  if ( function_exists( 'gf_webhooks' ) ) {
    $entry = GFAPI::get_entry( $entry_id );
    gf_webhooks()->maybe_process_feed( $entry, $form );
    gf_feed_processor()->save()->dispatch();
  }
}, 10, 2 );

// customize the progress bar
add_filter( 'gform_progress_bar', function( $progress_bar, $form, $confirmation_message ) {
  $progress_bar = str_replace("class='gf_progressbar gf_progressbar_custom'", "class='gf_progressbar gf_progressbar_custom' data-state='closed'", $progress_bar);
  return $progress_bar;
}, 10, 3 );

// move progress bar to bottom of form if 'progress-bar-bottom' is put in top page custom css
// https://gist.github.com/n7studios/f0b3ce229fa686ea0184
function gravity_forms_move_progress_bar( $form_string, $form ) {
  // Check if Pagination is enabled on this form
  if ( ! is_array( $form['pagination'] ) ) {
    return $form_string;
  } 
  if ( empty( $form['pagination']['type'] ) ) {
    return $form_string;
  }

  // Check if the first page CSS class is progress-bar-bottom
  if ( ! isset( $form['firstPageCssClass'] ) ) {
    return $form_string;
  }
  if ( strpos($form['firstPageCssClass'], 'progress-bar-bottom' ) === false) {
    return $form_string;
  }

  // If here, the progress bar needs to be at the end of the form
  // Load form string into DOMDocument
  $dom = new DOMDocument;
  @$dom->loadHTML( $form_string );

  // Load Xpath
  $xpath = new DOMXPath( $dom );

  // Find Progress Bar
  $progress_bar = $xpath->query( '//div[@class="gf_progressbar_wrapper"]' )->item(0);

  // Find Form
  $form = $xpath->query( '//form' )->item(0);

  // Move Progress Bar to end of the Form
  $form->appendChild( $progress_bar );

  // Get HTML string
  $form_string = $dom->saveHTML();

  // Return modified HTML string
  return $form_string;
}
add_filter( 'gform_get_form_filter', 'gravity_forms_move_progress_bar', 10, 3 );

function enercare_gform_address_types( $address_types, $form_id ) {
  $address_types['canada_ontario'] = array(
    'label'       => 'Canadian (Ontario only)',
    'zip_label'   => 'Postal Code',
    'state_label' => 'Province',
    'country'     => 'Canada',
    'states'      => array(
      'Ontario'
    )
  );
  $address_types['canada_alberta'] = array(
    'label'       => 'Canadian (Alberta only)',
    'zip_label'   => 'Postal Code',
    'state_label' => 'Province',
    'country'     => 'Canada',
    'states'      => array(
      'Alberta'
    )
  );
  return $address_types;
}
add_filter( 'gform_address_types', 'enercare_gform_address_types', 10, 2 );

/* 
----------------------------------------------------------
GRAVITY LITE EXPORTS CUSTOMIZATIONS
----------------------------------------------------------
*/
// separate all address type fields into multiple columns
add_filter( 'gfexcel_field_separated_address', function() { return true; } );

function enercare_gfexcel_field_label($label, GF_Field $field) {
  $labels_to_change = array(
    "Entry Id" => "Submission ID",
    "Entry Date" => "Created",
    "User IP" => "Remote IP Address",
    "Email Address" => "Email",
    "Street Address" => "Address: Street Address",
    "City" => "Address: City",
    "State / Province" => "Address: Province",
    "ZIP / Postal Code" => "Address: Postal Code",
    "Best Time to Call" => "Best Time To Call (Optional)",
    "Source Url" => "Submission URI",
  );
  // "Serial number","Submission ID","Submission URI",Created,Completed,Changed,"Is draft","Current page","Remote IP address","Submitted by: ID","Submitted by: Title","Submitted by: URL",Language,"Submitted to: Entity type","Submitted to: Entity ID",Locked,Sticky,Notes,"Submitted to: Entity title","Submitted to: Entity URL",Name,Email,"Address: Street Address","Address: City","Address: Province","Address: Postal Code","Phone Number","Best Time To Call (Optional)"
  // "Entry Id","Entry Date","User IP","Name","Phone Number","Best Time to Call","Email Address","Street Address","City","ZIP / Postal Code","Select Equipment","Equipment currently not working","How can we help you?","utm_source","utm_medium","utm_campaign","ruid","webhookStatus","webhookDate","Created By (User Id)","Source Url","Transaction Id","Payment Amount","Payment Date","Payment Status","Post Id","User Agent"
  
  foreach ($labels_to_change as $old_label => $new_label) {
    if ($label == $old_label)
      $label = $new_label;
  }

  return $label;
}
add_filter('gfexcel_field_label', 'enercare_gfexcel_field_label', 10, 2);
/* 
----------------------------------------------------------
END GRAVITY LITE EXPORTS CUSTOMIZATIONS
----------------------------------------------------------
*/

/* 
----------------------------------------------------------
BUILDER FORMS CUSTOMIZATIONS -- 8/11/2022: Client not moving forward with repeater field customizations. Saving for future if need be.
----------------------------------------------------------

// add repeater fields for the Pre-Inspection Checklist on the Commissioning Form
function enercare_gform_repeater_fields_19( $form ) {
  
  $lot = GF_Fields::create( array(
      'type'   => 'text',
      'id'     => 1001, // The Field ID must be unique on the form
      'formId' => $form['id'],
      'label'  => 'Lot #',
      'pageNumber'  => 1, // Ensure this is correct
      'size' => 'small',
  ) );
  $unit = GF_Fields::create( array(
      'type'   => 'text',
      'id'     => 1002, // The Field ID must be unique on the form
      'formId' => $form['id'],
      'label'  => 'Unit #',
      'pageNumber'  => 1, // Ensure this is correct
      'size' => 'small',
  ) );
  $street = GF_Fields::create( array(
      'type'   => 'text',
      'id'     => 1003, // The Field ID must be unique on the form
      'formId' => $form['id'],
      'label'  => 'Civic Street #',
      'pageNumber'  => 1, // Ensure this is correct
      'isRequired' => true,
      'size' => 'small',
  ) );
  $address = GF_Fields::create( array(
      'type'   => 'text',
      'id'     => 1004, // The Field ID must be unique on the form
      'formId' => $form['id'],
      'label'  => 'Civic Street Name',
      'pageNumber'  => 1, // Ensure this is correct
      'isRequired' => true,
      'size' => 'small',
  ) );
  $block = GF_Fields::create( array(
      'type'   => 'text',
      'id'     => 1005, // The Field ID must be unique on the form
      'formId' => $form['id'],
      'label'  => 'Block #',
      'pageNumber'  => 1, // Ensure this is correct
      'size' => 'small',
  ) );
  $commissioning_date = GF_Fields::create( array(
      'type'   => 'date',
      'id'     => 1006, // The Field ID must be unique on the form
      'formId' => $form['id'],
      'label'  => 'Requested Commissioning Date',
      'pageNumber'  => 1, // Ensure this is correct
      'isRequired' => true,
      'size' => 'small',
      'visibility' => 'visible',
      'inputs' => [[
          'id' => '1006.1',
          'label' => 'Month',
          'name' => '',
          'placeholder' => 'Month',
          'defaultValue' => ''
        ], [
          'id' => '1006.2',
          'label' => 'Day',
          'name' => '',
          'placeholder' => 'Day',
          'defaultValue' => ''
        ], [
          'id' => '1006.3',
          'label' => 'Year',
          'name' => '',
          'placeholder' => 'Year',
          'defaultValue' => ''
        ]
      ],
      'dateType' => 'datedropdown',
      //'dateType' => 'datepicker',
      'calendarIconType' => 'none',
      'errorMessage' => 'Please enter a valid weekday date, Monday through Friday, in the future.'
  ) );
  $inspection_completed = GF_Fields::create( array(
      'type'   => 'checkbox',
      'id'     => 1007, // The Field ID must be unique on the form
      'formId' => $form['id'],
      'label'  => 'Pre-Inspection Completed?',
      'pageNumber' => 1, // Ensure this is correct
      'isRequired' => true,
			'size' => 'small',
      'choices' => [[
          'text' => 'Yes',
          'value' => 'Yes',
          'isSelected' => false,
          'price' => ''
        ]
      ],
      'inputs' => [[
          'id' => '1007.1',
          'label' => 'Yes',
          'name' => ''
        ]
      ],
      
  ) );

  // Create a repeater for the team members and add the name and email fields as the fields to display inside the repeater.
  $inspection_checklist = GF_Fields::create( array(
      'type'             => 'repeater',
      'description'      => '',
      'id'               => 1000, // The Field ID must be unique on the form
      'formId'           => $form['id'],
      'label'            => 'Civic Addresses to be Commissioned',
      'addButtonText'    => 'Add Address', // Optional
      'removeButtonText' => 'Remove Address', // Optional
      'maxItems'         => 30, // Optional
      'pageNumber'       => 1, // Ensure this is correct
      'fields'           => array( $lot, $unit, $street, $address, $block, $commissioning_date, $inspection_completed ), // Add the fields here
  ) );

  $form['fields'][] = $inspection_checklist;

  return $form;
}
add_filter( 'gform_form_post_get_meta_19', 'enercare_gform_repeater_fields_19' ); 

// Remove the field before the form is saved. Adjust your form ID
function enercare_gform_remove_repeater_19( $form_meta, $form_id, $meta_name ) {
  if ( $meta_name == 'display_meta' ) {
    // Remove the Repeater field: ID 1000
    $form_meta['fields'] = wp_list_filter( $form_meta['fields'], array( 'id' => 1000 ), 'NOT' );
  }
  return $form_meta;
}
add_filter( 'gform_form_update_meta_19', 'enercare_gform_remove_repeater_19', 10, 3 );
*/

function enercare_gform_date_min_year( $min_date, $form, $field ) {
  date_default_timezone_set('America/Toronto');
  if ($form['id'] == 18 || $form['id'] == 19 || $form['id'] == 21) {
    return date('Y', strtotime('+1 day'));
  } elseif ($form['id'] == 20) {
    return date('Y', strtotime('-1 year'));
  }
  return $min_date;
}
add_filter( 'gform_date_min_year', 'enercare_gform_date_min_year', 10, 3 );

function enercare_gform_date_max_year( $max_date, $form, $field ) {
  date_default_timezone_set('America/Toronto');
  if ($form['id'] == 20) {
    return date('Y');
  }
  return $max_date;
}
add_filter( 'gform_date_max_year', 'enercare_gform_date_max_year', 10, 3 );

function enercare_gform_field_validation( $result, $value, $form, $field ) {
  if ( ($form['id'] == 18 || $form['id'] == 19) && $field->get_input_type() == 'date' ) {
    date_default_timezone_set('America/Toronto');
    $date = GFCommon::parse_date( $value );
    $today = strtotime("00:00:00");
    $tomorrow = date($today + (60*60*24));
    $date_input = $date['year'] . '-' . $date['month'] . '-' . $date['day'] . ' 00:00:00';
    if (date(strtotime($date_input)) < $tomorrow) {
      $result['is_valid'] = false;
      $result['message'] = 'Please enter a valid date in the future.';
      $field->failed_validation = true;
      $field->validation_message = 'Please enter a valid date in the future.';
    } elseif (date('N', strtotime($date_input)) >= 6) {
      $result['is_valid'] = false;
      $result['message'] = 'Please enter a valid weekday date, Monday through Friday.';
      $field->failed_validation = true;
      $field->validation_message = 'Please enter a valid weekday date, Monday through Friday.';
    }
    //var_dump($result);
    //var_dump($field);
  } elseif ($form['id'] == 20 && $field->get_input_type() == 'date') {
    date_default_timezone_set('America/Toronto');
    $date = GFCommon::parse_date( $value );
    $today = strtotime("00:00:00");
    $date_input = $date['year'] . '-' . $date['month'] . '-' . $date['day'] . ' 00:00:00';
    if (date(strtotime($date_input)) > $today) {
      $result['is_valid'] = false;
      $result['message'] = 'Please enter a valid date in the past, or today.';
      $field->failed_validation = true;
      $field->validation_message = 'Please enter a valid date in the past, or today.';
    }
  } elseif ($form['id'] == 21 && $field->get_input_type() == 'date') {
    date_default_timezone_set('America/Toronto');
    $date = GFCommon::parse_date( $value );
    $today = strtotime("00:00:00");
    $date_input = $date['year'] . '-' . $date['month'] . '-' . $date['day'] . ' 00:00:00';
    if (date(strtotime($date_input)) < $today) {
      $result['is_valid'] = false;
      $result['message'] = 'Please enter a valid date that is today or in the future.';
      $field->failed_validation = true;
      $field->validation_message = 'Please enter a valid date that is today or in the future.';
    }
  }

  return $result;
}
add_filter( 'gform_field_validation', 'enercare_gform_field_validation', 10, 4 );

/* 
----------------------------------------------------------
END BUILDER FORMS CUSTOMIZATIONS
----------------------------------------------------------
*/