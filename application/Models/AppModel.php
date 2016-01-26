<?php namespace Pinet\AppDownload\Models; in_array(__FILE__, get_included_files()) or exit("No direct script access allowed");

use Clips\Libraries\DBModelV2;

/**
 * Model to manipulate table apps
 *
 * @author Jake
 * @version 1.0
 * @date Mon Jul  6 18:10:29 515
 *
 * @Clips\Model({"categorie","download","remoteFile"})
 */
class AppModel extends DBModelV2 {

	const ACTIVE = 'ACTIVE';
	const INACTIVE = 'INACTIVE';

	public function getMonthApps(\DateTime $now, $category_id=null, $offset=0, $limit=10){
		$last = clone $now;
		$last = $last->sub(\DateInterval::createFromDateString("1 month"));
		return $this->getPushApps($category_id, $last->format('Y-m-01') . ' 00:00:00', $now->format('Y-m-01') . ' 00:00:00', $offset, $limit);
	}

	public function getMonthAppsF(\DateTime $now, $category_id=null, $offset=0, $limit=10){
		$last = clone $now;
		$last = $last->sub(\DateInterval::createFromDateString("1 month"));
		return $this->getPushApps($category_id, $last->format('Y-m-01') . ' 00:00:00', $now->format('Y-m-01') . ' 00:00:00', $offset, $limit, true);
	}

	public function getWeekApps(\DateTime $now, $category_id=null, $offset=0, $limit=10){
		$week = $now->format('w');
		if($week == 0)
			$week = 6;
		$end = $now->sub(\DateInterval::createFromDateString("$week days"));
		$start = clone $end;
		$end = $now->add(\DateInterval::createFromDateString("1 days"));
		$start = $start->sub(\DateInterval::createFromDateString("6 days"));
		return $this->getPushApps($category_id, $start->format('Y-m-d') . ' 00:00:00', $end->format('Y-m-d') . ' 00:00:00', $offset, $limit);
	}

	protected function getPushApps($category_id, $startTime, $endTime, $offset=0, $limit=10, $filter=false){
		$where = [
			'downloads.create_date >= ? and downloads.create_date < ?' => [$startTime, $endTime],
			'apps.status' => self::ACTIVE,
			'categories.path like ?' => $this->categorie->getGamePath($category_id) . '%'
		];
		$apps = $this->select('apps.id, categories.name as type, apps.name as title, apps.config, uv as number, count(1) as count')
			->from('apps')
			->join('categories', 'categories.id = apps.category_id')
			->join('downloads', 'apps.id = downloads.app_id')
			->where($this->buildWhere($where, $filter))
			->groupBy('apps.id')
			->orderBy('count desc,apps.pv desc,apps.create_date desc')
			->limit($offset, $limit)
			->result();
		return $this->buildApps($apps);
	}

	private function buildWhere($where, $filter=false){
		$request = \Clips\context('request');
		if($filter){
			$appIDs = $this->download->getDownloadedIDs($request->session('device_id'));
			if($appIDs)
				$where = array_merge(['apps.id not in ('.substr(str_pad('', count($appIDs)*2, ',?'), 1).')' => $appIDs], $where);
		}
		if($request->session('os')){
			return array_merge(['apps.type' => $request->session('os')], $where);
		}
		return $where;
	}

	private function buildApps($apps){
		return array_map(function($app){
			$app->config = json_decode($app->config);
			$app->href = \Clips\static_url('app/detail/'.$app->id);
			if(isset($app->config->icon))
				$app->icon = $app->config->icon;
			if(isset($app->config->poster))
				$app->poster = $app->config->poster;
			if(isset($app->config->content))
				$app->content = $app->config->content;
			if(isset($app->config->size))
				$app->size = $app->config->size;
			if(isset($app->config->company))
				$app->company = $app->config->company;
			if(isset($app->config->filename))
				$app->filename = $app->config->filename;
			if(isset($app->config->online_time))
				$app->online_time = $app->config->online_time;
			else
				$app->online_time = 30;
			$app->online_time_action = round($app->online_time/60, 2).'小时';
			unset($app->config);
			if (isset($app->number))
				$app->number = $this->formatDownloadNumber($app->number);
			return $app;
		}, $apps);
	}

	public function getLatestApps($category_id=null, $offset=0, $limit=10){
		$where = [
			'apps.status' => self::ACTIVE,
			'categories.path like ?' => $this->categorie->getGamePath($category_id) . '%'
		];
		$apps = $this->select('apps.id, categories.name as type, apps.name as title, apps.config, uv as number')
			->from('apps')
			->join('categories', 'categories.id = apps.category_id')
			->where($this->buildWhere($where))
			->groupBy('apps.id')
			->orderBy('apps.create_date desc')
			->limit($offset, $limit)
			->result();
		return $this->buildApps($apps);
	}

	public function getLatestAppsF($category_id=null, $offset=0, $limit=10){
		$where = [
			'apps.status' => self::ACTIVE,
			'categories.path like ?' => $this->categorie->getGamePath($category_id) . '%'
		];
		$apps = $this->select('apps.id, categories.name as type, apps.name as title, apps.config, uv as number')
			->from('apps')
			->join('categories', 'categories.id = apps.category_id')
			->where($this->buildWhere($where, true))
			->groupBy('apps.id')
			->orderBy('apps.create_date desc')
			->limit($offset, $limit)
			->result();
		return $this->buildApps($apps);
	}

	public function getRandApps($category_id=null, $offset=0, $limit=10){
		$where = [
			'apps.status' => self::ACTIVE,
			'categories.path like ?' => $this->categorie->getGamePath($category_id) . '%'
		];
		$apps = $this->select('apps.id, categories.name as type, apps.name as title, apps.config, uv as number')
			->from('apps')
			->join('categories', 'categories.id = apps.category_id')
			->where($this->buildWhere($where))
			->groupBy('apps.id')
			->orderBy('rand() desc')
			->limit($offset, $limit)
			->result();
		return $this->buildApps($apps);
	}

	public function getRandAppsF($category_id=null, $offset=0, $limit=10){
		$where = [
			'apps.status' => self::ACTIVE,
			'categories.path like ?' => $this->categorie->getGamePath($category_id) . '%'
		];
		$apps = $this->select('apps.id, categories.name as type, apps.name as title, apps.config, uv as number')
			->from('apps')
			->join('categories', 'categories.id = apps.category_id')
			->where($this->buildWhere($where, true))
			->groupBy('apps.id')
			->orderBy('rand() desc')
			->limit($offset, $limit)
			->result();
		return $this->buildApps($apps);
	}

	public function getTopApps($category_id=null, $offset=0, $limit=4){
		$where = [
			'apps.status' => self::ACTIVE,
			'categories.path like ?' => $this->categorie->getGamePath($category_id) . '%'
		];
		$apps = $this->select('apps.id, categories.name as type, apps.name as title, apps.config, uv as number')
			->from('apps')
			->join('categories', 'categories.id = apps.category_id')
			->where($this->buildWhere($where))
			->groupBy('apps.id')
			->orderBy('apps.pv desc,apps.create_date desc')
			->limit($offset, $limit)
			->result();
		return $this->buildApps($apps);
	}

	public function getTopAppsF($category_id=null, $offset=0, $limit=4){
		$where = [
			'apps.status' => self::ACTIVE,
			'categories.path like ?' => $this->categorie->getGamePath($category_id) . '%'
		];
		$apps = $this->select('apps.id, categories.name as type, apps.name as title, apps.config, uv as number')
			->from('apps')
			->join('categories', 'categories.id = apps.category_id')
			->where($this->buildWhere($where, true))
			->groupBy('apps.id')
			->orderBy('apps.pv desc,apps.create_date desc')
			->limit($offset, $limit)
			->result();
		return $this->buildApps($apps);
	}

	public function searchByName($name, $category_id=null, $offset=0, $limit=10){
		$where = [
			'apps.status' => self::ACTIVE,
			'categories.path like ?' => $this->categorie->getGamePath($category_id) . '%',
			'apps.name like ?' => "%$name%"
		];
		$apps = $this->select('apps.id, categories.name as type, apps.name as title, apps.config, uv as number')
			->from('apps')
			->join('categories', 'categories.id = apps.category_id')
			->where($this->buildWhere($where, false))
			->groupBy('apps.id')
			->orderBy('apps.pv desc,apps.create_date desc')
			->limit($offset, $limit)
			->result();
		return $this->buildApps($apps);
	}

	public function sameTypeApps($app_id, $offset=0, $limit=5){
		$app = $this->load('apps', $app_id);
		if ($app === null) {
			return [ ];
		}

		$where = [
			'apps.status' => self::ACTIVE,
			'apps.id <> ?' => [ $app_id ],
			'categories.id' => $app->category_id
		];
		$apps = $this->select('apps.id, apps.name as title, apps.config, categories.path')
			->from('apps')
			->join('categories', 'categories.id = apps.category_id')
			->where($this->buildWhere($where))
			->groupBy('apps.id')
			->orderBy('apps.pv desc,apps.create_date desc')
			->limit($offset, $limit)
			->result();
		return $this->buildApps($apps);
	}

	public function getDetail($id){
		$request = \Clips\context('request');
		$where = [
			'apps.status'=> self::ACTIVE,
			'apps.id'=> $id
		];
		$apps = $this->select('apps.id, categories.name as type, apps.name as title, apps.config, apps.timestamp, uv as number, categories.path')
			->from('apps')
			->join('categories', 'categories.id = apps.category_id')
			->where($this->buildWhere($where))->result();
		$apps = $this->buildApps($apps);
		$apps = $this->download->getAppOnlineTime($request->session('device_id'), $apps);
		return count($apps) ? $apps[0] : [];
	}

	public function getLastMonthName(){
		$now = new \DateTime();
		$month = ['一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月'];
		return $month[(int)$now->format('m')-2];
	}

	public function formatDownloadNumber($num)
	{
		$units = [ '千', '万', '百万','千万' ];

		if ($num < 1000) {
			return "<1{$units[0]}";
		}
		else if ($num < 10000) {
			return intval($num / 1000). $units[0];
		}
		else if ($num < 100* 10000) {
			return intval($num / (10000)). $units[1];
		}
		else if ($num < 1000* 10000) {
			return intval($num / (100 * 10000)). $units[2];
		}
		else {
			return ">1{$units[3]}";
		}
	}

	public function checkAppExist($id){
		$app = $this->one('game_id', $id);
		return isset($app->id) ? true : false;
	}

	public function addTempApp($data){
		if($this->checkAppExist($data->game_id) || !$data->game_file->file_url){
			return;
		}
		$files = [[$data->game_file->file_url, 'app']];
		$icon = '';
		$poster = [];
		$banner = '';
		$app = explode('/', $data->game_file->file_url);
		$filename = array_pop($app);
		$tempTypes = explode('.', $filename);
		foreach($data->game_images as $game){
			if(!$game->image_url)
				continue;
			$images = explode('/', $game->image_url);
			$image = array_pop($images);
			$image = 'application/static/img/apps/'.$tempTypes[0].'/'.$image;
			switch($game->image_type){
				case 200:
					$icon = $image;
					break;
				case 203:
					$poster[] = $image;
					break;
				case 400:
					$banner = $image;
					break;
			}
			$files[] = [$game->image_url, 'image'];
		}
		$map = ['apk'=>'android','ipa'=>'ios'];
		$type = 'android';
		$tempType = array_pop($tempTypes);
		if(isset($map[$tempType])){
			$type = $map[$tempType];
		}
		$config = json_encode([
			'company' => $data->cp_name,
			'icon' => $icon,
			'online_time' => 30,
			'filename' => $filename,
			'size' => round($data->game_file->file_size/1000/1000, 2).'MB',
			'poster' => $poster,
			'banner' => $banner,
			'content' => $data->introduction,
			'files' => $files
		]);
		$id = $this->insert([
			'category_id'=>$data->game_tag,
			'game_id'=>$data->game_id,
			'name'=>$data->game_name,
			'type'=>$type,
			'status'=>self::INACTIVE,
			'pv'=>0,
			'uv'=>0,
			'config'=> json_encode($config),
			'info'=> json_encode($data),
			'create_date'=> (new \DateTime($data->create_time))->format('Y-m-d H:i:s')
		]);
		foreach($files as $file){
			$this->remotefile->insert([
				'url'=> $file[0],
				'type'=> $file[1],
				'game_id'=> $data->game_id,
				'filename'=> $tempTypes[0],
				'create_date'=> (new \DateTime())->format('Y-m-d H:i:s')
			]);
		}
		//200 icon 203 poster 400 banner
	}

	public function activeTempApp($id){
		$app = $this->one('game_id', $id);
		if(isset($app->id)){
			$app->status = self::ACTIVE;
			$this->update($app);
			$this->download->insert([
				"app_id" => $id,
				"device_id" => 1,
				"gateway_id" => 1,
				"create_date" => date("Y-m-d H:i:s"),
				"status" => self::INACTIVE
			]);
		}
	}
}
