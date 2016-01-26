<?php namespace Pinet\AppDownload\Controllers; in_array(__FILE__, get_included_files()) or exit("No direct script access allowed");

use Pinet\AppDownload\Core\BaseController;

/**
 * @Clips\Widget({"html", "lang", "grid","reactjs","scaffold", "burbon","bootstrap", "lilium", "gamenotice","pageModule","game","alertGame"})
 * @Clips\Model({"device", "app","categorie"})
 * @Clips\Scss("welcome")
 */
class WelcomeController extends BaseController {

	/**
	 * @Clips\Scss("welcome/welcome")
	 * @Clips\Widget({"game", "home"})
	 */
	public function index() {
		$step = 'home';
		\Clips\add_jsx('application/static/jsx/home');
		$this->context('ds_names', ['lastMonthName', 'lastMonthList', 'latestList', 'randList', 'hottestList', 'page_tab', 'dl']);
		$args['latestList'] = $this->app->getLatestAppsF();
		$args['lastMonthList'] = $this->app->getMonthAppsF(new \DateTime());
		$args['randList'] = $this->app->getRandAppsF();
		$args['hottestList'] = $this->app->getTopAppsF();
		$args['lastMonthName'] = $this->app->getLastMonthName().'精选游戏';
		$args['page_tab'] = 'tab_recommend';
		$args['dl'] = \Clips\static_url('app/download');
		$this->request->session('step', $step);

		$app_url = $this->request->param('app');
		if($app_url) {
			$this->request->session('app_loc', $app_url);
		}
		return $this->render("welcome/$step", $args);
	}

	public function notice() {
		return $this->render('welcome/notice');
	}

	/**
	 * @Clips\Scss("welcome/welcome")
	 */
	public function welcome() {
		$this->request->session('step', 'home');
        \Clips\add_jsx('application/static/jsx/welcome');
		return $this->render('welcome/welcome');
	}

	/**
	 * @Clips\Widget({"game", "home"})
	 * @Clips\Scss("welcome/home")
	 */
	public function home(){
		$args = ['set_dl'=>true];
		$this->context('ds_names', ['lastMonthName', 'lastMonthList', 'latestList', 'randList', 'hottestList', 'page_tab', 'dl']);
		$args['latestList'] = $this->app->getLatestAppsF();
		$args['lastMonthList'] = $this->app->getMonthAppsF(new \DateTime());
		$args['randList'] = $this->app->getRandAppsF();
		$args['hottestList'] = $this->app->getTopAppsF();
		$args['lastMonthName'] = $this->app->getLastMonthName().'精选游戏';
		$args['page_tab'] = 'tab_recommend';
		$args['dl'] = \Clips\static_url('app/download');
		return $this->render('welcome/home', $args);
	}

    /**
    * @Clips\Widget({"pageModule",'appList','swiperImages','game'})
	* @Clips\Scss("detail/detail")
    */

    public function appdetail(){
        return $this->render('game/detail');
    }

	/**
	 * @Clips\Widget({"home","appList","swipeJs"})
	 * @Clips\Scss("welcome/ranking_list")
	 */
	public function ranking($time='total') {
		$args = ['set_dl'=>true];
		$this->context('ds_names', ['lastMonthName', 'page_tab', 'rank_tab', 'lists', 'banner']);
		$args['page_tab'] = 'tab_list';
		$apps = [];
		switch($time){
			case 'total':
				$this->request->session('list_type', 'top');
//				$apps = $this->download->getAppOnlineTime($this->request->session('device_id'), $this->app->getTopApps());
				break;
			case 'month':
				$this->request->session('list_type', 'month');
//				$apps = $this->app->getMonthApps(new \DateTime());
				break;
			case 'week':
				$this->request->session('list_type', 'week');
//				$apps = $this->app->getWeekApps(new \DateTime());
				break;
		}
		$args['banner'] = [
			'application/static/img/apps/danjidoudizhu(huanleban)/tuiguang.jpg',
			'application/static/img/apps/danjidoudizhu(huanleban)/tuiguang.jpg',
			'application/static/img/apps/danjidoudizhu(huanleban)/tuiguang.jpg'
		];
		$args['lists'] = $apps;
		$args['rank_tab'] = $time;
		return $this->render("welcome/ranking_list", $args);
	}

	/**
	 * @Clips\Widget({'ablums','pageModule'})
	 * @Clips\Scss('welcome/category')
	 */
	public function category(){
		$this->context('ds_names', ['categories']);
		return $this->render('welcome/category', [
				'categories' => $this->categorie->getGameCategories()
		]);
	}

}
