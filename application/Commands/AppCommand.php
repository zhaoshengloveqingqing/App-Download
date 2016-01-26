<?php namespace Pinet\AppDownload\Commands; in_array(__FILE__, get_included_files()) or exit("No direct script access allowed");

use Clips\Command;

/**
 * @Clips\Context(key = 'test_data', value = 'app')
 * @Clips\Object("testData")
 * @Clips\Model({"app", "categorie"})
 */
class AppCommand extends Command {
	public function execute($args) {
		$images = ['11'=>'relax', '12'=>'checkpoint', '13'=>'chess', '14'=>'shot', '15'=>'manage', '16'=>'role', '17'=>'strategy', '17'=>'other'];
		for($i=1;$i<=60;$i++){
			$app = $this->app->load($i);
			$app->config = json_encode([
				'company' => '苏州派尔科技网络有限公司',
				'icon' => 'application/static/img/game1.png',
				'online_time' => 30,
				'filename' => '5007846_1_20041605.apk',
				'size' => rand(35, 300).'MB',
				'poster' => [
					'application/static/img/detail_img.png',
					'application/static/img/detail_img.png',
					'application/static/img/detail_img.png',
					'application/static/img/detail_img.png'
				],
				'content' => '赛博朋克（cyberpunk）是控制论（cybernetics）和朋克（punk）结合而造出来的一个专用名词，也是科幻领域里常见“不明觉厉”的一个重要概念，要具体给完全没有概念的人解释这一词语的起源发展和具体含义颇有些难度，不过对于影迷及玩家，对应说出几个代表性的名字倒是很容易。电影来说，就是《银翼杀手》、《黑客帝国》、《少数派报告》和刚刚翻拍过的《机械战警》等，而游戏方面则包括《杀出重围》、《镜之边缘》以及宣传多时的《看门狗》和顾名思义的《赛博朋克2077》。'
			]);
			$this->app->update($app);
			$category = $this->categorie->load($i);
			if(isset($category->id)){
				$category->config = json_encode([
					'icon' => 'application/static/img/category/'.$images[$category->id].'.png'
				]);
				$this->categorie->update($category);
			}
		}
		echo "Done!";
	}
}
