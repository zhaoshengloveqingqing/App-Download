<?php namespace Pinet\AppDownload\Models; in_array(__FILE__, get_included_files()) or exit("No direct script access allowed");

use Clips\Libraries\DBModelV2;

/**
 * Model to manipulate table downloads
 *
 * @author Jake
 * @version 1.0
 * @date Mon Jul  6 18:10:29 2015
 *
 * @Clips\Model({"device", "gateway"})
 */
class DownloadModel extends DBModelV2 {

	public function getDownloadedIDs($device_id){
		$apps = $this->select('distinct downloads.app_id')
			->from('downloads')
			->where([
				"downloads.device_id" => $device_id
			])
			->result();
		return array_map(function($app){return $app->app_id;}, $apps);
	}

	public function getWithInDay($id, $days = 3) {
		$request = \Clips\context('request');
		$now = new \DateTime();
		$now->sub(\DateInterval::createFromDateString("$days days"));
		$downloads = $this->select('downloads.id')
		->from('downloads')
		->where([
				'downloads.create_date > ?' => $now->format('Y-m-d') . ' 00:00:00',
				"downloads.app_id" => $id,
				"downloads.device_id" => $request->session("device_id")
			])
		->result();
		if(count($downloads))
			return false;
		return true;
	}

	public function getAppOnlineTime($device_id, $apps, $days=3) {
		if(!$apps){
			return $apps;
		}
		$ids = [];
		foreach($apps as $app){
			$ids[] = $app->id;
		}
		$now = new \DateTime();
		$now->sub(\DateInterval::createFromDateString("$days days"));
		$where = [
			'status'=>'ACTIVE',
			'downloads.create_date > ?' => $now->format('Y-m-d') . ' 00:00:00',
			"downloads.device_id" => $device_id
		];
		if($ids)
			$where['downloads.app_id in ('.substr(str_pad('', count($ids)*2, ',?'), 1).')'] = $ids;
		$downloads = $this->select('downloads.app_id')
			->from('downloads')
			->where($where)
			->groupBy('downloads.app_id')
			->result();
		$app_ids = [];
		foreach($downloads as $download){
			$app_ids[$download->app_id] = $download->app_id;
		}
		foreach($apps as $app){
			if(isset($app_ids[$app->id])){
				$app->online_time = 0;
				$app->online_time_action = '0小时';
			}
		}
		return $apps;
	}

	public function getMac() {
		$request = \Clips\context('request');
		$mac = $request->session('client_mac');
		if(!$mac) {
			$ip = $request->getIp();
			if($ip !== '0.0.0.0' && $ip != '127.0.0.1') {
				$mac = \Clips\ip2mac($ip);
			}
			else {
				$mac = 'local';
			}
			$request->session('client_mac', $mac);
		}
		return $mac;
	}

	public function updateLog($id) {
		return $this->update((object)[
			'id'=>$id,
			'status'=>'ACTIVE'
		]);
	}

	public function addLog($app_id, $info='') {
		$request = \Clips\context('request');

		$mac = $this->getMac();
		$gateway = \Clips\config('gateway');
		if($gateway) {
			$gateway = $gateway[0];
		}
		else {
			$gateway = 'none name';
		}

		$gateway_id = $this->gateway->getOrCreate($gateway);

		if($mac) {
			$device = $this->device->getOrCreate($mac, $request->browserMeta());
			$device_id = $device->id;
		}
		else {
			$device_id = null;// We don't found any device
		}
		$data = array(
			"app_id" => $app_id,
			"device_id" => $device_id,
			"gateway_id" => $gateway_id,
			"info" => $info,
			"create_date" => date("Y-m-d H:i:s")
		);

		$id = $this->insert('downloads', $data);
		$this->requestSync($app_id);
		return $id;
	}

	public function increase($app_id) {
		$sql = "update apps set pv = 1 + pv where id = ?";
		return $this->query($sql, [ $app_id ]);
	}

	public function syncCounts($app_id) {
		$sql = "
			select app_id, count(1) as pv, count(distinct device_id) as uv
			from downloads
			where app_id = ? and status = ?
			group by app_id
		";
		$result = $this->query($sql, [ $app_id , 'ACTIVE' ]);

		$sql = "update apps set pv = ?, uv = ? where id = ? ";
		if ($result) foreach ($result as $row) {
			$this->query($sql, [ $row->pv, $row->uv, $row->app_id ]);
		}
	}


	/**
	 * delayed task, sync pv & uv
	 */
	public function requestSync($app_id) {
		$this->syncCounts($app_id);
	}
}
