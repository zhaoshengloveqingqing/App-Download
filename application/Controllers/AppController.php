<?php namespace Pinet\AppDownload\Controllers; in_array(__FILE__, get_included_files()) or exit("No direct script access allowed");

use Pinet\AppDownload\Core\BaseController;

/**
 * @Clips\Widget({"html", "lang", "grid","reactjs","scaffold", "burbon","bootstrap", "lilium","appList","pageModule", "gamenotice","game","alertGame"})
 * @Clips\Model({"device", "app", "categorie", "download"})
 */
class AppController extends BaseController {

	public function index() {
	}

	/**
	 * @Clips\Scss("app/lists")
	 */
	public function lists($id){
		$this->context('ds_names', ['lists', 'category_name']);
		$this->request->session('list_type', 'lists');
		$this->request->session('list_id', $id);
		$category = $this->categorie->load($id);
		$category_name = '';
		if($category)
			$category_name = $category->name;
		return $this->render('app/lists', [
			'category_name' => $category_name,
			'lists' => [],
			'set_dl'=>true
		]);
	}

	/**
	 * @Clips\Scss("app/lists")
	 */
	public function all($type){
		$this->context('ds_names', ['lists', 'category_name']);
		$category_name = '';
		$list = [];
		switch($type){
			case 'last':
				$category_name = $this->app->getLastMonthName().'精选游戏';
				$this->request->session('list_type', $type);
				//$list = $this->app->getMonthApps(new \DateTime(), null, 0, 10000);
				break;
			case 'latest':
				$category_name = '最新发布游戏';
				$this->request->session('list_type', $type);
				//$list = $this->app->getLatestApps(null, 0, 10000);
				break;
			case 'top':
				$category_name = '最高人气游戏';
				$this->request->session('list_type', $type);
				//$list = $this->app->getTopApps(null, 0, 10000);
				break;
		}
		return $this->render('app/lists', [
			'category_name' => $category_name,
			'lists' => $list,
			'set_dl'=>true
		]);
	}


	/**
	 * @Clips\Scss("app/search")
	 */
	public function search(){
		$name = $this->request->post('app_name');
		$this->request->session('list_type', 'search');
		$this->request->session('list_search', $name);
		$this->logger->info('the list is ', $this->app->searchByName($name));
		$this->context('ds_names', ['lists', 'search_name']);
		return $this->render('app/search', [
			'search_name' => "搜索\"$name\"相关结果",
			'lists' => [],
			'set_dl'=>true
		]);
	}

	public function rand(){
		return $this->json($this->app->getRandApps());
	}

	public function more($page=1){
		$listType = $this->request->session('list_type') ?:'lists';
		$result = [];
		switch($listType){
			case 'last':
				$result = $this->app->getMonthApps(new \DateTime(), null, ($page-1)*8, 8);
				break;
			case 'latest':
				$result = $this->app->getLatestApps(null, ($page-1)*8, 8);
				break;
			case 'top':
				$result = $this->app->getTopApps(null, ($page-1)*8, 8);
				break;
			case 'search':
				$result = $this->app->searchByName($this->request->session('list_search'), null, ($page-1)*8, 8);
				break;
			case 'month':
				$result = $this->app->getMonthApps(new \DateTime(), null, ($page-1)*8, 8);
				break;
			case 'week':
				$result = $this->app->getWeekApps(new \DateTime(), null, ($page-1)*8, 8);
				break;
			case 'lists':
			default:
				$result = $this->app->getTopApps($this->request->session('list_id'), ($page-1)*8, 8);
				break;

		}
		$result = $this->download->getAppOnlineTime($this->request->session('device_id'), $result);
		return $this->json($result);
	}

	/**
	 * @Clips\Widget({'game','appList','swipeJs'})
	 * @Clips\Scss("app/detail")
	 */
	public function detail($id){
		$app = $this->app->getDetail($id);
		$this->context('ds_names', ['app', 'sames']);
		return $this->render('app/detail', [
			'app' => $app,
			'sames' => isset($app->id) ? $this->download->getAppOnlineTime($this->request->session('device_id'),$this->app->sameTypeApps($app->id, 0, 4)) : [],
			'set_dl'=>true
		]);
	}

	/**
	 * @Clips\Js("application/static/js/welcome/help.js")
	 * @Clips\Scss("app/help")
	 */
	public function help(){
		return $this->render('app/help', []);
	}

	/**
	 * @Clips\Scss("app/about")
	 */
	public function about() {
		return  $this->render('app/about');
	}

	/**
	 * @Clips\Scss("app/feedback")
	 */
	public function feedback() {
		return $this->render("app/feedback");
	}

	/**
	 * @Clips\Scss("app/state")
	 */
	public function state(){
		return $this->render("app/state");
	}

	public function download($app_id, $filename){
		$time = 0;
		if($this->download->getWithInDay($app_id)){
			$app = $this->app->getDetail($app_id);
			if(isset($app->online_time)){
				$time = $app->online_time;
			}
		}
		$id = $this->download->addLog($app_id, $this->request->get('info', ''));
		$uri = \Clips\static_url('/application/static/apps/'.$filename);
		return $this->redirect($uri);
	}

	public function done($id){
		$this->download->updateLog($id);
	}

}
