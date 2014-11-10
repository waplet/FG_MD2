<?php
error_reporting(E_ALL);
ini_set('display_errors', 'on');
/** 
 * Otrais neobligātais mājasdarbs
 */
function pr($arg) {
	echo "<pre>". print_r($arg, true);
}

class FindWord {
	private $search_word;
	private $search_length;

	private $productions = [
		'S' => [
			'aaAbb', 
			'Sbb', 
			'aabb', 
			'bb' ,
			'FFaFc', 
			'FaFc' ,
			'aFc',
			'ac',
			'FFac',
			'Fac'
		],
		'A' => [
			'aaA', 'aa', 'aaS'
		],
		'F' => [
			'bbb','aaA', 'aa','aaS'
		]
	];

	private $nonterms = 'S|A|F';
	private $terms = 'a|b|c';
	private $found = false;
	private $symbol_pos;

	function __construct($w) {
		$this->search_word = $w;
		$this->search_length = strlen($w);
	}

	public function checkword($word, $level = 1, $maxlevel) {
		//if($level == $maxlevel) return;
		if($word == $this->search_word) {
			$this->found = true;
			return;
		}
		if($this->found) return;
		if(strlen($word) >= $this->search_length && $word != $this->search_word) {
			//echo $word.'- garaaks<br/>';
			return;
		} 
		$matches = preg_split('[' . $this->nonterms .']', $word);
		// pr($matches);	
		//echo "<hr/>";
		// if(count($matches) == 1 && $matches[0] == $this->search_word) {
		// 	$this->found = true;
		// 	return;
		// }
		if($this->needlesInWord($matches)) { // vārds ir derīgs
			$symbol_pos = (count($matches) > 1) ? strlen($matches[0]) : -1;
			if($symbol_pos < 0) return;
			$symbol = isset($word[$symbol_pos]) ? $word[$symbol_pos] : null;
			
			if(!$symbol && $this->search_word == $matches[0]) {
				$this->found = true;
				return;
			}

			if(!$symbol) return;
			// die(pr($this->symbol));
			foreach($this->productions[$symbol] as $p) {
				// echo $word.'- vaards';
				// // echo $this->symbol_pos;
				// echo $p;echo "<br/>";
				if($this->found) return;

				$newword = mb_substr($word,0,$symbol_pos).$p.mb_substr($word,$symbol_pos+1);
				echo $level.'-'.$word.'-'.$newword.'-'.$symbol_pos."<br/>";
				//echo $word;
				$this->checkword($newword,$level +1, $level);

			}
		}
		return false;
	}

	public function needlesInWord($needles) {
		foreach($needles as $n) {
			if (!empty($n) && strpos($this->search_word, $n) === false) {
				return false;
			}
		}
		return true;
	}

	public function found() {
		return $this->found;
	}
}
// aaabbaabbbacbbacac
// bbbaaacaaaacbbbbbbc
// aaaabbaaaaaaaacaaac
// $s = 'aabb';
// $sx = substr('Sbb',0,0 ).$s.substr('Sbb',1);
// var_dump($sx); die;
$x = new FindWord('aaaabbaaaaaaaacaaac');
$x->checkword('S',1,10);
var_dump($x->found());