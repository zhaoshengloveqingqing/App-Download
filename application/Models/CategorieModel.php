<?php namespace Pinet\AppDownload\Models; in_array(__FILE__, get_included_files()) or exit("No direct script access allowed");

use Clips\Libraries\DBModelV2;

/**
 * Model to manipulate table categories
 *
 * @author Jake
 * @version 1.0
 * @date Mon Jul  6 18:10:29 2015
 *
 * @Clips\Model
 */
class CategorieModel extends DBModelV2 {

	const ACTIVE = 'ACTIVE';

	public function getGamePath($id=null){
		if($id){
			$category = $this->load($id);
			if(isset($category->id))
				return $category->path;
			return '';
		}
		return '/1/';
	}

	public function getOrCreatePath($id, $name){
		$category = $this->load($id);
		if(!isset($category->id)){
			$now = new \DateTime();
			$id = $this->insert([
				'id'=>$id,
				'path'=>"/{$id}/",
				'name'=>$name,
				'type'=>'app',
				'status'=>'ACTIVE',
				'create_date'=>$now->format('Y-m-d H:i:s')
			]);
			$category = $this->load($id);
		}
		return $category->path;
	}

	public function getGameCategories(){
		$categories = $this->select('categories.id, categories.name, categories.config')->from('categories')->where([
			'categories.status'=> self::ACTIVE,
			'categories.path like ?'=> $this->getGamePath().'%',
			'categories.path <> ?'=> $this->getGamePath()
		])->orderBy('categories.id')->result();
		return array_map(function($category){
			$category->config = json_decode($category->config);
			if(isset($category->config->icon))
				$category->icon = $category->config->icon;
			$category->href = \Clips\static_url('app/lists/'.$category->id);
			unset($category->config);
			return $category;
		}, $categories);
	}
}
