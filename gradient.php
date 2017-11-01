<?php
$filename = "gradients.png";
$size = getimagesize($filename);

/**
 * How many extractions?
 */
$points = 20;

$from = 0;
$to = $size[0] - ($size[0]%$points); // width-wise; trim extra/reminder
$every = $to/$points; // evenly distribute the distance
#echo "Running from {$from} - {$to} @every {$every} out of {$size[0]}px.";

$im = imagecreatefrompng($filename);

$colors = array();
for($x=0; $x<$points; ++$x)
{
	$rgb = imagecolorat($im, $x*$every, 0);
	$r = ($rgb >> 16) & 0xFF;
	$g = ($rgb >> 8) & 0xFF;
	$b = $rgb & 0xFF;
	
	$r=dechex($r);
	$g=dechex($g);
	$b=dechex($b);
	
	$color = strtoupper("#{$r}{$g}{$b}");
	$colors[] = $color;
}

$colors = array_reverse($colors); // reverse the gradients

$css = file_get_contents("ts/css.ts");
$scss = file_get_contents("ts/scss.ts");

$loop = 0;
$csses = array();
$scsses = array();
foreach($colors as $color)
{
	++$loop;

	$csses[] .= "
ul.menu li:nth-child({$loop}){
	background-color: {$color};
}
";

	$scsses[] .= "
		&:nth-child({$loop}) {
			background-color: {$color};
		}
";
}

file_put_contents("gradients.css", sprintf($css, implode("", $csses)));
file_put_contents("_gradients.scss", sprintf($scss, implode("", $scsses)));

header("Content-Type: text/plain");
echo $css;
