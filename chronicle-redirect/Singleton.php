<?php

namespace ChronicleRedirect;

trait Singleton {

  protected static $instance = null;

  /**
  * Provides access to a single instance of this class.
  * @return	object	A single instance of this class.
  */
  public static function get_instance() {

    // If the single instance hasn't been set, set it now.
    if ( null == self::$instance ) {
      self::$instance = new self;
    }

    return self::$instance;
  }

}
