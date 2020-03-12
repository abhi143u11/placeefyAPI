<?php
if (PHP_SAPI != 'cli') {
	echo "<pre>";
}
$review = "fucked";
$strings = array($review);



require_once __DIR__ . '/../autoload.php';
$sentiment = new \PHPInsight\Sentiment();
foreach ($strings as $string) {

	// calculations:
	$scores = $sentiment->score($string);
	$class = $sentiment->categorise($string);

	// output:
	
}
if($class=='neu')
{
	$Dominant = 'Neutral';
}else if($class=='pos')
{
	$Dominant = 'Positive';
}else{
	$Dominant = 'Negative';
}

//echo "String: $string\n";
	echo "Dominant: $Dominant";
	//print_r($scores);
	echo "\n";