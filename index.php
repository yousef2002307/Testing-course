<?php


function groupAnagrams(array $strs) {
 $arr = [];
$charMap = [
    ""  =>   1,
    "a" =>   2, "b" =>   3, "c" =>   5, "d" =>   7, "e" =>  11,
    "f" =>  13, "g" =>  17, "h" =>  19, "i" =>  23, "j" =>  29,
    "k" =>  31, "l" =>  37, "m" =>  41, "n" =>  43, "o" =>  47,
    "p" =>  53, "q" =>  59, "r" =>  61, "s" =>  67, "t" =>  71,
    "u" =>  73, "v" =>  79, "w" =>  83, "x" =>  89, "y" =>  97,
    "z" => 101,
];

for($i =0; $i < count($strs); $i++){
    $str = $strs[$i];
    $strarr = str_split($str);
    $counter = 1;
    for($j=0; $j < count($strarr); $j++){
        $counter *= $charMap[$strarr[$j]];
    }
    if(!array_key_exists($counter,$arr)){
        $arr[$counter] = [];
    }
    array_push($arr[$counter],$str);
}

 $finalarr = [];
   foreach($arr as $item){$finalarr[] = $item;
    }
    return $finalarr;
}
$strs = ["eat", "tea", "tan", "ate", "nat", "bat","xyz","zyx"];
print_r(groupAnagrams($strs));

