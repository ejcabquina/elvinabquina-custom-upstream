<?php

GFForms::include_addon_framework();

class EnercareGFAddOn extends GFAddOn {
  protected $_version = ENERCARE_GF_ADDON_VERSION;
  protected $_min_gravityforms_version = '1.9';
  protected $_slug = 'enercaregfaddon';
  protected $_path = 'enercaregfaddon/enercaregfaddon.php';
  protected $_full_path = __FILE__;
  protected $_title = 'Enercare Gravity Forms Add-On';
  protected $_short_title = 'Enercare Add-On';

  private static $_instance = null;

  public static function get_instance() {
    if ( self::$_instance == null ) {
      self::$_instance = new EnercareGFAddOn();
    }

    return self::$_instance;
  }

  public function init() {
    parent::init();
  }
}