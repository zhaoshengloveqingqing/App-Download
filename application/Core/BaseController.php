<?php namespace Pinet\AppDownload\Core; in_array(__FILE__, get_included_files()) or exit("No direct script access allowed");

use Clips\Controller;
use Clips\Interfaces\Initializable;

/**
 * The Base controller for all controllers
 *
 * @author Jake
 * @since 2015-07-03
 */

class BaseController extends Controller implements Initializable {
	public function init() {
		// Add the UA Compatible
		\Clips\context('html_meta', array('http-equiv' => 'X-UA-Compatible',
			'content' => 'IE=edge'), true);
		// Add the view port for phones
		\Clips\context('html_meta', array('name' => 'viewport',
			'content' => 'width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no'), true);
		// Add the iPhone support
		\Clips\context('html_meta', array('name' => 'renderer',
			'content' => 'webkit'), true);
		// Add the cache control
		\Clips\context('html_meta', array('http-equiv' => 'Cache-Control',
			'content' => 'no-siteapp'), true);
		// Add the cache control
		\Clips\context('html_meta', array('name' => 'mobile-web-app-capable',
			'content' => 'yes'), true);
		// Add the cache control
		\Clips\context('html_meta', array('name' => 'apple-mobile-web-app-capable',
			'content' => 'yes'), true);
		// Add the cache control
		\Clips\context('html_meta', array('name' => 'apple-mobile-web-app-status-bar-style',
			'content' => 'black'), true);
		// Add the cache control
		\Clips\context('html_meta', array('name' => 'apple-mobile-web-app-title',
			'content' => 'Pinet AppDownload'), true);
	}

	protected function render($template, $args = array(), $engine = null, $headers = array()) {
        $meta = \Clips\browser_meta();
		if($meta->device_type == 'Desktop'){
//			return parent::render('error/notice', $args, $engine, $headers);
		}
		if(isset($args['set_dl'])){
			$args['dl'] = \Clips\static_url('app/download');
			$this->context('ds_names', array_merge(['dl'], $this->context('ds_names') ?:[]));
			if($this->request->get('overtime')){
				$this->context('ds_names', array_merge(['filterHottestList'], $this->context('ds_names') ?:[]));
				$args['filterHottestList'] = $this->app->getTopApps(null, 0, 6);
			}
		}

		\Clips\context('ds_names', 'app_loc', true);

		if($this->request->session('app_loc') && $this->request->getType() == 'http') {
			$args['app_loc'] = $this->request->session('app');
		}
		$this->title('Pinet APP下载');
		return parent::render($template, $args, $engine, $headers);
	}
}
