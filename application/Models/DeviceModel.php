<?php namespace Pinet\AppDownload\Models; in_array(__FILE__, get_included_files()) or exit("No direct script access allowed");

use Clips\Libraries\DBModelV2;

/**
 * Model to manipulate table devices
 *
 * @author Jake
 * @version 1.0
 * @date Mon Jul  6 18:10:29 2015
 *
 * @Clips\Model({"gateway"})
 */
class DeviceModel extends DBModelV2 {

	public static $os = ['iOS'=>'ios', 'MacOSX'=>'ios', 'Android'=>'android'];

	public function getOs($meta){
		$platform = \Clips\get_default($meta, 'platform');

		if(!$platform)
			$platform = \Clips\get_default($meta, 'Platform');

		if(!$platform)
			return 'unknown';

		if($platform == 'unknown'){
			if(strpos($meta->browser_name, 'Android') !== false)
				return 'Android';
		}
		return $platform;
	}

	public function getDeviceType($meta){
		if($meta->device_type == 'unknown' || $meta->device_type == 'Desktop'){
			if(strpos($meta->browser_name, 'Android') !== false)
				return 'Android';
			else
				return 'iOS';
		}else if($meta->ismobiledevice || $meta->istablet){
			return $meta->platform;
		}
		return 'Android';
	}

	public function getOrCreate($mac, $meta){
		$device = $this->one('mac', $mac);
		if(isset($device->id)){
			return $device;
		}
		$now = new \DateTime();
		$browser = \Clips\get_default($meta, 'browser');
		if(!$browser)
			$browser = \Clips\get_default($meta, 'Browser');
		if(!$browser)
			$browser = 'unknown';

		$version = \Clips\get_default($meta, 'version');
		if(!$version) {
			$version = \Clips\get_default($meta, 'Version');
		}
		if(!$version) {
			$version = 'unknown';
		}

		$id = $this->insert([
			'mac' => $mac,
			'os' => $this->getOs($meta),
			'os_version' => '-',
			'browser' => $browser,
			'browser_version' => $version,
			'uagent' => $meta->browser_name,
			'create_date' => $now->format('Y-m-d H:i:s')
		]);
		return $this->load($id);
	}

	public function saveAndKeep2Session($get, $meta){
		$request = \Clips\context('request');
		$serial = $get['serial'];
		$mac = $get['mac'];
		$ip = $get['ip'];
		$device_mac = $get['cmac'];
		$device_ip = $get['cip'];
		$path = $get['path'];
		$request->session('ibox_ip', $ip);
		$request->session('ibox_path', $path);
		$request->session('cip', $device_ip);
		$request->session('cmac', $device_mac);
		$gateway_id = $this->gateway->getOrCreate($serial, $mac);
		$device = $this->getOrCreate($device_mac, $gateway_id, $meta);
		$request->session('serial', $serial);
		$request->session('device_id', $device->id);
		$request->session('gateway_id', $gateway_id);
		$request->session('os', self::$os[$this->getDeviceType($meta)]);
	}
}
