<?php

/**
 *
 * @Clips\Model({"app"})
 *
 */
class AppModelTest extends \Clips\TestCase
{
	protected function getApps($from, $to, $limit=50)
	{
		return $this->app->query("
			select d.*
			from apps a
			inner join (
				select app_id as id,count(1) as pv, count(distinct device_id) as uv
				from downloads
				where create_date between ? and ?
				group by app_id
			) d
				on a.id = d.id
			order by d.pv DESC, a.pv DESC, a.create_date DESC
			limit ? 
			", [ "$from 00:00:00", "{$to} 23:59:59", $limit ]);
	}

	public function testGetMonthApps()
	{
		$now = "2015-07-08";
		$result = $this->app->getMonthApps(new \DateTime($now));
		$list = $this->getApps("2015-06-01", "2015-06-30");

		return $this->assertTrue(sameValueOrder($result, $list, function($obj1, $obj2) {
			return $obj1->id == $obj2->id;
		}));
	}

	public function testGetWeekApps()
	{
		$now = "2015-07-08";
		$result = $this->app->getWeekApps(new \DateTime($now));
		$list = $this->getApps("2015-06-29", "2015-07-05");
#print_r(array_map(function($o) { return $o->id; }, $result));
#print_r(array_map(function($o) { return $o->id; }, $list));

	        $this->assertTrue(sameValueOrder($result, $list, function($obj1, $obj2) {
			return $obj1->id == $obj2->id;
		}));
	}

	public function testGetLatestApps()
	{
		$result = $this->app->getLatestApps(null);
		$list = $this->app->query("
			select a.*
			from apps a
			inner join categories c
				on a.category_id = c.id
			where a.status = 'ACTIVE' and c.path like '/1/%'
			order by a.create_date DESC
			limit 20
		");

	        $this->assertTrue(sameValueOrder($result, $list, function($obj1, $obj2) {
			return $obj1->id == $obj2->id;
		}));
	}

	public function testGetRandApps()
	{
		// todo
	}

	public function testGetTopApps()
	{
		$result = $this->app->getTopApps(null);
		$list = $this->getApps("1900-01-01", date("Y-m-d",strtotime("next day")));

	        $this->assertTrue(sameValueOrder($result, $list, function($obj1, $obj2) {
			return $obj1->id == $obj2->id;
		}));
	}

	public function testSearchByName()
	{
		$list = $this->app->query("
			select id,name
			from apps
			where status = 'ACTIVE'
			limit 3
		");
		foreach ($list as $app) {
			$result = $this->app->searchByName($app->name);
			foreach ($result as $row) {
				$this->assertTrue(strpos($row->title, $app->name) !== false);
			}
		}
	}

	public function testSameTypeApps()
	{
		$list = $this->app->query("
			select a.id,c.path
			from apps a
			inner join categories c
				on a.category_id = c.id
			where a.status = 'ACTIVE'
			group by c.path
			limit 3
		");
		foreach ($list as $app) {
			$result = $this->app->sameTypeApps($app->id);
			foreach ($result as $row) {
				$this->assertTrue(strpos($row->path, $app->path) === 0);
			}
		}
	}

	public function testGetDetail()
	{
		// todo
	}

	public function testFormatDownloadNumber()
	{
		$app = $this->app;
		$this->assertEquals($app->formatDownloadNumber(800), '<1千');
		$this->assertEquals($app->formatDownloadNumber(8000), '8千');
		$this->assertEquals($app->formatDownloadNumber(80000), '8万');
		$this->assertEquals($app->formatDownloadNumber(800000), '80万');
		$this->assertEquals($app->formatDownloadNumber(8000000), '8百万');
		$this->assertEquals($app->formatDownloadNumber(80000000), '>1千万');
	}
}

