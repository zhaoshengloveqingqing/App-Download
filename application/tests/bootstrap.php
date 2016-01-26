<?php in_array(__FILE__, get_included_files()) or exit("No direct script access allowed");

require_once(__DIR__.'/../../vendor/autoload.php');

Clips\context('bundle_dir', 'messages', true);

$request = new \Clips\HttpRequest;
$request->session('os', 'android');
Clips\context('request', $request);


function sameValueOrder($arr1, $arr2, $equals=null)
{
	$n1 = count($arr1);
	$n2 = count($arr2);
	if ($n1 > $n2) {	// swap $arr1, $arr2
		list($arr1, $n1, $arr2, $n2) = [ $arr2, $n2, $arr1, $n1 ];
	}

	if (! is_callable($equals)) {
		$equals = function ($a, $b) { return $a === $b; };
	}

	for ($i = $j = 0; $i < $n1; $i++) {
		$e1 = $arr1[$i];

		while ($j < $n2) {
			if ($equals($e1, $arr2[$j++])) {
				break;
			}
			if ($j === $n2) {	// let it overflow
				$j++;
			}
		} 
		if ($j > $n2) {			// check overflow
			throw new Exception("Array element [{$i}] mis-matching ");
			return false;
		}
	}

	return true;
}
