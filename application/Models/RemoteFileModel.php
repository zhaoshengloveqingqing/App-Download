<?php namespace Pinet\AppDownload\Models; in_array(__FILE__, get_included_files()) or exit("No direct script access allowed");

use Clips\Libraries\DBModelV2;

/**
 * Model to manipulate table remote_files
 *
 * @author Jake
 * @version 1.0
 * @date Mon Jul  6 18:10:29 2015
 *
 * @Clips\Model
 */
class RemoteFileModel extends DBModelV2 {

	public function saveImage(){
		$files = $this->limit(0, 50)->get('type', 'image');
		foreach($files as $file){
			$temp = explode('/', $file);
			$filename = array_pop($temp);
			$in=    fopen($file->url, "rb");
			$out=   fopen(__DIR__.'/../static/img/apps/'.$file->filename.'/'.$filename, "wb");
			while ($chunk = fread($in,4096))
			{
				fwrite($out, $chunk, 4096);
			}
			fclose($in);
			fclose($out);
			$this->delete($file->id);
		}
	}

	public function saveApp(){
		$files = $this->limit(0, 50)->get('type', 'app');
		foreach($files as $file){
			$temp = explode('/', $file);
			$filename = array_pop($temp);
			$in=    fopen($file->url, "rb");
			$out=   fopen(__DIR__.'/../static/img/apps/'.$file->filename.'/'.$filename, "wb");
			while ($chunk = fread($in,4096))
			{
				fwrite($out, $chunk, 4096);
			}
			fclose($in);
			fclose($out);
			$this->delete($file->id);
		}
	}
}
