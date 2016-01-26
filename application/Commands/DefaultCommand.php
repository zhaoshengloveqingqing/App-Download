<?php namespace Pinet\AppDownload\Commands; in_array(__FILE__, get_included_files()) or exit("No direct script access allowed");

use Clips\Command;

/**
 * @Clips\Library("csv")
 * @Clips\Model({"gateway", "categorie", "device"})
 */
class DefaultCommand extends Command {
	public function execute($args) {
		$gateways = $this->csv->read("src://tests/data/gateway.csv");
		foreach($gateways as $gateway){
			$this->gateway->insert($gateway);
		}
		$devices = $this->csv->read("src://tests/data/device.csv");
		foreach($devices as $device){
			$this->device->insert($device);
		}
		$categories = $this->csv->read("src://tests/data/categories.csv");
		foreach($categories as $category){
			$this->categorie->insert($category);
		}
		echo "Done!";
	}
}
