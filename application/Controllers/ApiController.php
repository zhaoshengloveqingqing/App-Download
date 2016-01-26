<?php namespace Pinet\AppDownload\Controllers;

in_array(__FILE__, get_included_files()) or exit("No direct script access allowed");

use Clips\Controller;

/**
 *
 * @Clips\Model({"device", "app", "categorie", "download", "downloadPeriod", "remoteFile"})
 */
class ApiController extends Controller {

	public function hot() {
		return $this->jsonp($this->app->getTopAppsF());
	}

	public function index(){
		$dp = $this->downloadperiod->load(1);
		if(!$dp){
			$date_from = '20150701000000';
			$date_to = '20150702000000';
			$this->downloadperiod->insert((object)[
				'start_date'=>$date_from,
				'end_date'=>$date_to
			]);
		}else{
			$date_from = (new \DateTime($dp->end_date))->format('YmdHis');
			$date_to = new \DateTime($dp->end_date);
			$date_to->add(\DateInterval::createFromDateString("1 day"));
			$date_to = $date_to->format('YmdHis');
			$now_m = (new \DateTime())->format('m');
			$date_from_m = (new \DateTime($dp->end_date))->format('m');
			if(intval($now_m) < intval($date_from_m)){
				echo 'Current Month is done!!!';
				return;
			}
		}
		$page_num = 1;
		while($return = $this->saveApp($date_from, $date_to, $page_num)){
			if($return === true){
				$page_num ++;
			}
		}
		if($return !== null){
			$this->downloadperiod->update((object)[
				'id'=>1,
				'start_date'=>$date_from,
				'end_date'=>$date_to
			]);
		}
		echo 'Done!!!';
	}

	private function saveApp($date_from, $date_to, $page_num, $page_size=50){
		$uri='http://hm.play.cn/api/v1/data_sync/get_data';//product url
		$caller='egame_hm_pinet';
		$signKey='5061dba928f2ec437a6f9078d153be4c';//product key
		$sequence = (new \DateTime())->getTimestamp();
		$content = "date_from=$date_from"."date_to=$date_to"."page_num=$page_num"."page_size=$page_size"."sync_entity=gamesync_type=1";
		$md5 = strtolower(md5($caller.$content.$signKey));
		$post = [
			'sequence'=>$sequence,
			'client'=>[
				'caller'=>$caller,
				'ex'=>null
			],
			'data'=>[
				'sync_type'=>1,
				'sync_entity'=>'game',
				'date_from'=>$date_from,
				'date_to'=>$date_to,
				'page_size'=>$page_size,
				'page_num'=>$page_num
			],
			'sign'=>$md5,
			'encrypt'=>'base64'
		];
		$post = json_encode($post);
		exec("curl -H 'Content-Type: application/json' -X POST -d '".$post."' $uri", $return);
		$return = json_decode($return[0]);
		if($return->state->code == 200){
			$data = json_decode(base64_decode($return->data));
			var_dump($data->total);
			if(count($data->list)>0){
				foreach($data->list as $game){
					$this->app->addTempApp($game);
				}
			}
			return $page_num*$page_size < $data->total ?:false;
		}
		return null;
	}

	public function saveImages(){
		$this->remotefile->saveImage();
	}

	public function saveApps(){
		$this->remotefile->saveApp();
	}

	public function test() {
		$page_size=10;
		$page_num=1;
//		$uri='http://218.94.99.204:18105/api/v1/data_sync/get_data';//test url
		$uri='http://hm.play.cn/api/v1/data_sync/get_data';//product url
		$caller='egame_hm_pinet';
//		$signKey='5552daa92832ee437aaf9a88d153be4c';//test key
		$signKey='5061dba928f2ec437a6f9078d153be4c';//product key
		$sequence = (new \DateTime())->getTimestamp();
		$date_from = new \DateTime();
		$date_from->sub(\DateInterval::createFromDateString("20 days"));
		$date_from = $date_from->format('YmdHis');
		$date_to = new \DateTime();
		$date_to->sub(\DateInterval::createFromDateString("1 days"));
		$date_to = $date_to->format('YmdHis');
		var_dump($date_from);
		var_dump($date_to);
		$content = "date_from=$date_from"."date_to=$date_to"."page_num=$page_num"."page_size=$page_size"."sync_entity=gamesync_type=1";
		$md5 = strtolower(md5($caller.$content.$signKey));
		$post = [
			'sequence'=>$sequence,
			'client'=>[
				'caller'=>$caller,
				'ex'=>null
			],
			'data'=>[
				'sync_type'=>1,
				'sync_entity'=>'game',
				'date_from'=>$date_from,
				'date_to'=>$date_to,
				'page_size'=>$page_size,
				'page_num'=>$page_num
			],
			'sign'=>$md5,
			'encrypt'=>'base64'
		];
		$post = json_encode($post);
		exec("curl -H 'Content-Type: application/json' -X POST -d '".$post."' $uri", $return);
		$return = json_decode($return[0]);
		if($return->state->code == 200){
			$data = json_decode(base64_decode($return->data));
			var_dump($data);die;
			if($data->total>0){
				foreach($data->list as $game){
					$this->app->addTempApp($game);
				}
			}
//			var_dump($data);die;
		}
//		$ch = curl_init ();
//		curl_setopt ( $ch, CURLOPT_URL, $uri );
//		curl_setopt ( $ch, CURLOPT_CUSTOMREQUEST, 'POST' );
//		curl_setopt ( $ch, CURLOPT_HEADER, [
//				'Content-Type: application/json',
//				'Content-Length: ' . strlen($post)]
//		);
//		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
//		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $post );
//		$return = curl_exec ( $ch );
//		curl_close ( $ch );
//		var_dump($return);
	}
}
