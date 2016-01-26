<?php

/**
 *
 * @Clips\Model({"categorie"})
 *
 */
class CategorieModelTest extends \Clips\TestCase
{
	public function testGetGamePath()
	{
		$cat = $this->categorie;
		$list = $cat->get();
		foreach (array_rand($list,3) as $k) {
			$this->assertEquals($cat->getGamePath($list[$k]->id), $list[$k]->path);
		}
		$this->assertEquals($cat->getGamePath(), "/1/");
	}

	public function testGetCategories()
	{
		$result = $this->categorie->getGameCategories();
		$list = $this->categorie
			->select("id")
			->from("categories")
			->where([
				"status" => "ACTIVE",
				"id <> ?" => "1",
				"path like ?" => "/1/%"
			])
			->orderBy('id')
			->result();

		$ret = count($result) === $n = count($list);
		if ($ret) {
			/*
			for ($i = 0; $i < $n; $i++) {
				if ($result[$i]->id !== $list[$i]->id) {
					$ret = false;
					break;
				}
			}
			 */
			$ret = sameValueOrder($result, $list, function($obj1, $obj2) {
				return $obj1->id == $obj2->id;
			});

		}
		$this->assertTrue($ret);
	}

}

