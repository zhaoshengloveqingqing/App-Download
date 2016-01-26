<?php namespace Pinet\AppDownload\Models; in_array(__FILE__, get_included_files()) or exit("No direct script access allowed");

use Clips\Libraries\DBModelV2;

/**
 * Model to manipulate table gateways
 *
 * @author Jake
 * @version 1.0
 * @date Mon Jul  6 18:10:29 2015
 *
 * @Clips\Model
 */
class GatewayModel extends DBModelV2 {

	public function getOrCreate($serial){
		$gateway = $this->one('serial', $serial);
		if(isset($gateway->id)){
			return $gateway->id;
		}
		$now = new \DateTime();
		return $this->insert([
			'serial' => $serial,
			'name' => $serial,
			'create_date' => $now->format('Y-m-d H:i:s')
		]);
	}
}
