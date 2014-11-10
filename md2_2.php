<?php
error_reporting(E_ALL);
ini_set('display_errors', 'on');
/** 
 * Otrais neobligātais mājasdarbs
 */
function pr($arg) {
	echo "<pre>". print_r($arg, true);
}

$productions = [
		'S' => [
			// 'aaAbb', 
			'Sbb', 
			'aabb', 
			'bb' ,
			//'FFaFc', 
			//'FaFc' ,
			//'aFc',
			//'ac',
			//'FFac'
		],
		'A' => [
			'aaA', 'aa', 'aaS'
		],
		'F' => [
			'bbb','aaA', 'aa','aaS'
		]
	];

$nonterms = 'S|A|F';

$found = false;
$symbol_pos;
$search_word = 'aabbbb';
$search_length = 6;

function checkword($word, $level = 1, $maxlevel) {
	global $productions;
	global $nonterms;
	global $search_word;
	global $search_length;
	global $found;

		if($level == $maxlevel) return;
		//echo $word.'-'.(int)$this->found."<br/>";

		if($found) return;
		if(strlen($word) >= $search_length && $word != $search_word) {
			echo $word.'- garaaks<br/>';
			return;
		} 
		$matches = preg_split('[' . $nonterms .']', $word);
		// pr($matches);
		// echo "<hr/>";
		// if(count($matches) == 1 && $matches[0] == $this->search_word) {
		// 	$this->found = true;
		// 	return;
		// }
		if(needlesInWord($matches)) { // vārds ir derīgs
			$symbol_pos = (count($matches) > 1) ? strlen($matches[0]) : -1;
			if($symbol_pos < 0) return;
			$symbol = isset($word[$symbol_pos]) ? $word[$symbol_pos] : null;
			
			if(!$symbol && $search_word == $matches[0]) {
				$found = true;
			}

			if($found || !$symbol) return;
			foreach($productions[$symbol] as $p) {
				echo $word.'- vaards';
				// echo $this->symbol_pos;
				echo $p;echo "<br/>";
				if($found) return;
				mb_substr($word,0,$symbol_pos).$p.mb_substr($word,$symbol_pos+1);
				//echo $word;
				checkword(mb_substr($word,0,$symbol_pos).$p.mb_substr($word,$symbol_pos+1),$level +1, $level);

			}
		}


		return false;
	}

function needlesInWord(&$needles) {
global $search_word;
	foreach($needles as $n) {
		if (!empty($n) && strpos($search_word, $n) === false) {
			return false;
		}
	}
	return true;
}

function found() {
	global $found;
		return $found;
	}
checkword('aaSbb',1,3);
var_dump(found());