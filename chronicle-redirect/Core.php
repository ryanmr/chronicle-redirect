<?php

namespace ChronicleRedirect;

class Core {

	use \ChronicleRedirect\Singleton;

	const SLUG_CACHE_TRANSIENT = 'cr_slug_cache';
	const SLUG_CACHE_TIMEOUT = 604800;

	public function initialize() {

		add_action('template_redirect', array($this, 'capture_404'));

		// for testing
		add_filter('chronicle_source_url', function($src) {
			return 'http://chronicles.blog.ryanrampersad.com';
		});		

	}

	public function capture_404() {
		global $wp_query;

		if ($wp_query->is_404() == false) {
			return;
		}

		$slug = '';
		if ( isset($wp_query->query['pagename']) ) {
			$slug = $wp_query->query['pagename'];
		} elseif ( isset($wp_query->query['name']) ) {
			$slug = $wp_query->query['name'];
		} else {
			return;
		}
		
		$this->get_chronicle_redirect($slug);

	}

	public function get_chronicle_redirect($slug) {

		// assumes that the old blog runs WordPress still
		// and in addition, can resolve {domain}/{slug} URLs still
		$source_url = $this->get_chronicle_source();
		$url = "{$source_url}/{$slug}";

		$cache = get_transient(self::SLUG_CACHE_TRANSIENT);
		if ( false === $cache ) {
			$cache = array();
		}

		if ( array_key_exists($slug, $cache) ) {

			// redirect on 200, 301, and 302; reject 404s
			if ( in_array($cache[$slug], array('301', '302', '200')) ) {
				$this->do_redirect($url);
			} else {
				return false;
			}

		}

		$response = wp_remote_head($url);

		$code = $response['response']['code'];

		$cache[$slug] = $code;

		set_transient(self::SLUG_CACHE_TRANSIENT, $cache, self::SLUG_CACHE_TIMEOUT);

		if ( $code == '404' ) {
			return false;
		}

		$this->do_redirect($url);

		return false;
	}

	private function do_redirect($url) {
		$do_redirect = apply_filters('chronicle_redirect', true);

		if ( $do_redirect ) {
			wp_redirect($url, 301);
			exit();
		}
	}

	public function get_chronicle_source() {
		$source = '';
		$source = apply_filters('chronicle_source_url', $source);

		return $source;
	}

}