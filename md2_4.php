<?php

/**
 * Otrais mājasdarbs ar bināro normālformu...
 * 
 */

$input = "aabb";

$r = [
	'S' => [['L','K'],['J','K'],['H','H'],['S','K'],['P','O'],['G','O'],['G','I'],['M','R'],['F','R']],
	'A' => [['J','A'],['G','G'],['J','S']],
	'F' => [['K','H'],['J','A'],['G','G'],['J','S']],
	'G' => 'a', // Na
	'H' => 'b', // Nb
	'I' => 'c', // Nc
	'J' => [['G','G']], // Naa
	'K' => [['H','H']], // Nbb
	'L' => [['J','G']], // Naaa
	'M' => [['F','F']], // Nff
	'N' => [['F','G']], // Nfna
	'O' => [['F','I']], // Nfnc
	'P' => [['M','G']], // Nffna
	'R' => [['G','I']]
];

$rs = ['S'];
/**
 * Inicializācija P masīvam
 * @var [type]
 */
$p = [];
for ($i = 0; $i < strlen($input); $i++) {
	$p[$i] = [];
	for ($j = 0; $j < strlen($input); $j++) {
		$p[$i][$j] = [];
		foreach($r as $k=>$v){
		//for ($k = 0; $k < count($r); $k++){
			$p[$i][$j][$k] = false;
			//echo (int)$p[$i][$j][$k];	
		}
	}
}

for($i = 0;$i < strlen($input);$i++) {
	for($j = 0; $j<strlen($input);$j++)
	foreach($r as $k=>$v) {
		if(!is_array($v) && $input[$i] == $v) {
			$p[$i][$j][$k] = true;
			echo $i.'0';
			echo $k;
			echo $v."<br/>";
		}
	}
}

for($i = 1; $i < strlen($input) ; $i++) {
	for($j= 0; $j < (strlen($input)-$i + 1);$j++) {
		$rr = $j + $i -1;
		for($k = $j+1; $k < $rr; $k++) {

			foreach($r as $y=> $prod ) {
				if(is_array($prod)) {
					foreach($prod as $prod_pair) {
						if(
							$p[$j][$k-1][$prod_pair[0]] 
								and 
							$p[$k][$rr][$prod_pair[1]]
						) {
							echo "shit"; 
							$p[$j][$rr][$y] = 1;
						}
						// if($p[$j][$k][$prod_pair[0]]) {
						// 	echo $i."";
						// 	echo $j."";
						// 	echo $k."<br/>";
						// }
						// if($p[$j+$k][$i-$k][$prod_pair[1]]) {
						// 	echo $i."";
						// 	echo $j."";
						// 	echo $k."<br/>";
						// }
						// echo $prod_pair[0]."<br/>";
						// echo $prod_pair[1]."<br/>";
						// die;
					}
				}
			}

		}
	}
}
echo "<pre>".print_r($p, true);

if($p[1][strlen($input)-1]['S']) {
	echo "madafaka";
}