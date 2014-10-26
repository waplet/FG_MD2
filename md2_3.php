<?php
/** 
 * Otrais neobligātais mājasdarbs
 * Māris Jankovskis, mj12015
 * aaaabbaabbbacbbacac - pieder
 * bbbaaacaaaacbbbbbbc - pieder
 * aaaabbaaaaaaaacaaac - nepieder
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
			'aabb', 
			'bb' ,
			'Sbb', 
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
	private $found    = false;
	/**
	 * Inicializē un pieglabā meklējamo vārdu un tā garumu
	 * @param string $w meklējamais vārds
	 */
	function __construct($w) {
		$this->search_word = $w;
		$this->search_length = strlen($w);
	}

	/**
	 * Meklē jaunus vārdus un salīdzina ar meklējamo
	 * @param  string $word jaunais vārds
	 * @return void       does nothing
	 */
	public function checkword($word) {
		/**
		 * Ja vārds atrasts, ejam ārā
		 */
		if($word == $this->search_word) {
			$this->found = true; return;
		}
		/**
		 * Ja vārda garums pārsniedz, metam nost vārdu
		 */
		if(strlen($word) >= $this->search_length) {
			return;
		}

		/**
		 * Atrodam visas termināļu virknītes
		 */
		$matches = (preg_split('/[' . $this->nonterms .']+/', $word));

		/**
		 * Pārbaudām vai visas virknes ir vārdā
		 */
		if($this->needlesInWord($matches)) {
			/** 
			 * Kreisējā netermināļa atrašana
			 */
			$symbol_pos = (count($matches) > 1) ? strlen($matches[0]) : -1;
			if($symbol_pos < 0) return;
			$symbol = isset($word[$symbol_pos]) ? $word[$symbol_pos] : null;
			if(!$symbol) return;
			/**
			 * Produkciju pielietošana vārdam
			 */
			foreach($this->productions[$symbol] as $p) {
				if($this->found) return;
				$this->checkword(mb_substr($word,0,$symbol_pos).$p.mb_substr($word,$symbol_pos+1));
			}
		}
		return;
	}

	/**
	 * Skatās vai visas ntermināļu virknītes ir meklējamajā vārdā
	 * @param  array $needles array of nonterminals strings
	 * @return boolean          true/false
	 */
	private function needlesInWord($needles) {
		foreach($needles as $n) {
			if (!empty($n) && strpos($this->search_word, $n) === false) {
				return false;
			}
		}
		return true;
	}

	/**
	 * Galvenā rezultāta atgriešana
	 * @return boolean true/false
	 */
	public function found() {
		return $this->found;
	}

	/**
	 * atgriež meklējamo vārdu
	 * @return [type] [description]
	 */
	public function getWord() {
		return $this->search_word;
	}
}
$start = microtime(true);
$x = new FindWord('aaaabbaaaaaaaacaaac');
$x->checkword('S');
echo $x->getWord() . ' '. ($x->found() ? "Pieder" : "Nepieder");
echo "<br/>" . (microtime(true)-$start);
