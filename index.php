<!doctype html>

<?php // ok
function url_exists($url) {
    $hdrs = @get_headers($url);
    return is_array($hdrs) ? preg_match('/^HTTP\\/\\d+\\.\\d+\\s+2\\d\\d\\s+.*$/',$hdrs[0]) : false;
}
?>
<?php // not used
/**
 * Return unicode char by its code
 * http://www.php.net/manual/en/function.chr.php#88611
 * @param int $u
 * @return char
 */
function unichr($u) {    return mb_convert_encoding('&#' . intval($u) . ';', 'UTF-8', 'HTML-ENTITIES');}

?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>ergokey</title>
<style type="text/css">
body { background:#fff; color:#333; text-align:center;font-size:small;line-height:1.8em;}
a {color:#369;}
#wrap {margin:0 auto;width:780px; text-align:left;padding:20px 0;}
#labels {height:6em; border-bottom:1px solid #aaa;float:right;width:10em;margin-top:-10em; margin-right:-10em;}
$graphdiv {background:#f9f9f9; padding:10px; border:1px solid #ccc;}
table.details { width:300px;background:#fff;border-collapse:collapse;float:left; margin:10px 20px;}
table.details th {text-align:right; color:#369; border-bottom:2px solid #369; font-weight:bold; padding-right:1em;}
table.details td {background:#fff;text-align:right;border-bottom:1px solid #ccc;padding-right:1em;}
</style>
<script type="text/javascript" src="dygraph-combined.js"></script>
</head>
<body>
<div id="wrap">
<h1><a href="http://demo.ximplex.net/keyboard/">Home</a></h1>
<?php
/* Converts any HTML-entities into characters */
/* http://www.php.net/manual/en/function.mb-decode-numericentity.php#37547 */
function my_numeric2character($t)
{
    $convmap = array(0x0, 0x2FFFF, 0, 0xFFFF);
    return mb_decode_numericentity($t, $convmap, 'UTF-8');
}
/* Converts any characters into HTML-entities */
function my_character2numeric($t)
{
    $convmap = array(0x0, 0x2FFFF, 0, 0xFFFF);
    return htmlentities(mb_encode_numericentity($t, $convmap, 'UTF-8'));
}
// print my_numeric2character('&#8217; &#7936; &#226;');
// print my_character2numeric(' � � ');
	
?>
<form action="index.php" method="get" style="text-align:center">
URL: <input type="text" style="width:350px;" name="url" value="http://" />
<input type="submit" value="submit" style="" />
</form>

<?php 
// display submitted url
$thisUrl = $_GET["url"];
if ($thisUrl){echo "Submitted url : ". $_GET["url"];}else { echo "Submit a webpage containing khmer unicode to see the magic";}
?>

<?php
if ($thisUrl)
{
echo ("<br/>");
if(!filter_var($thisUrl, FILTER_VALIDATE_URL))  {  echo "URL is not valid";  }
else
  {  
  echo "URL is valid"; 
  $urlContent = file_get_contents($thisUrl);
 if(url_exists($thisUrl)) 
 	{echo ("<br/> Url exist");} 
 else 
 	{echo ("<br/> Url does not exist, sorry ...");
 	}
  	
// echo $urlContent; will display complete HTML source
// Khmer unicode &#6016; to &#6121; or 1780 to 17E9
// Reference http://www.php.net/manual/en/function.preg-replace.php#101510
// Reference http://theorem.ca/~mvcorks/cgi-bin/unicode.pl.cgi?start=1780&end=17FF
// echo "Khmer -- ";
?>

<div id="graphdiv">Loading...</div>
<div id="labels">label...</div>
<?php
/* DISPLAY ORIGINAL STRING IN A TEXT BOX */ 
		
	$pattern ='/[^\x{1780}-\x{17E9}]+/u';
	$s=preg_replace($pattern,"",$urlContent);
/*
	echo ("<br/><textarea readonly='readonly' style='width:100%; height:200px;' id='text1'>");
	echo $s; 
  	echo ("</textarea>");
*/  	

/* DISPLAY HTML ENTITIES IN DECIMAL NUMBER */  
/* http://www.php.net/manual/en/function.mb-decode-numericentity.php#37547 */
//	echo ("<br/><textarea readonly='readonly' style='width:100%; height:200px;' id='text2'>");
//	$convmap = array(0x0, 0x2FFFF, 0, 0xFFFF);
//	$s2 = htmlentities(mb_encode_numericentity($s, $convmap, 'UTF-8'));
//	echo $s2;
//	echo ("</textarea>");
//	echo "Total characters: ".strlen($s)."<br/>";
	
/* READ STRING INTO AN ARRAY */
/*	$count = new array();
	for (i=0; i<strlen($s); i++)
	{		$chr = htmlentities(mb_encode_numericentity($s, $convmap, 'UTF-8'));	}	
*/
	$arr1 = str_split($s);
//	echo (array_count_values($arr1));	
	$arr2 = array_count_values($arr1);
	ksort($arr2);
	
	echo "Total characters: ".strlen($s)."<br/>";
	echo "Size of arr1: ".sizeof($arr1)."<br/>";
	echo "Size of arr2: ".sizeof($arr2)."<br/>";
	echo "Count(arr2): ".count($arr2)." characters used<br/>";
	
	echo "<h2>Original text</h2>";
	echo ("<br/><textarea readonly='readonly' style='width:100%; height:200px;' id='text1'>");
	echo $s; 
  	echo ("</textarea>");

$arr3 = array();
for ($i=6016; $i<6121; $i++){	
	$t = unichr($i);
	$arr3 [$i] = substr_count($s, $t);
	}




/*
$arr3 = array();
for ($i=6016; $i<6121; $i++){	
	//$t = '&#'.$i.';';
	$t = unichr($i);
	if (array_key_exists($t,$arr2)) {$arr3[$i] = $arr2[$t]; }
	else {$arr3[$i] = 0; }
	echo $i.', '.$t.', '.$arr2[$t].', '. chr($i).'; ';
	}
	print_r($arr3);
*/
?>

<div><!-- style="width: 100%; margin: 20px auto; padding:10px; font-family:sans-serif; background:#ccc; text-align:center;" -->
<?php
/** Include class */
// include( 'GoogChart.class.php' );
/** Create chart */
//$chart = new GoogChart();
// $color = array(                        '#99C754'                );

/* # Chart  # */
// echo '<h2>Timeline</h2>';
// $arr2 = array('mon'=>1, 'tue'=>2, 'wed'=>4, 'thu'=>7);
//	$chart->setChartAttrs( array(
//        'type' => 'sparkline',
//        'title' => 'Khmer Character Frequency',
//        'data' => $dataTimeline,
//        'data' => $arr3,
//        'size' => array( 600, 500 ),
//        'color' => '#cc0000',
//        'labelsXY' => y,
//        ));
// Print chart
//echo $chart;
?>
</div>


<?php
echo "<h2>Details</h2> " ;
//print_r ($arr3);
// PRINT IN TABLE
$sum = array_sum($arr3);
/*
echo "<table id='details'><tr><th>Code</th><th>Char</th><th>Count</th><th>Freq</th></tr>";
  foreach ($arr3 as $k => $v) 
  	{
	$freq = round($v * 100 / $sum,4); 
	echo "<tr><td>".$k."</td><td>".unichr($k)."</td><td>".$v."</td><td>".$freq."%</td></tr>";

	}
echo "</table>";
*/
$arr3a = array_slice($arr3, 0, 52,true );
$arr3b = array_slice($arr3, 52,-1,true);


echo "<table class='details'><tr><th>Code</th><th>Char</th><th>Count</th><th>Freq</th></tr>";
  foreach ($arr3a as $k => $v) 
  	{
	$freq = round($v * 100 / $sum,4); 
	echo "<tr><td>".$k."</td><td>".unichr($k)."</td><td>".$v."</td><td>".$freq."%</td></tr>";

	}
echo "</table>";

echo "<table class='details' style='float:right'><tr><th>Code</th><th>Char</th><th>Count</th><th>Freq</th></tr>";
  foreach ($arr3b as $k => $v) 
  	{
	$freq = round($v * 100 / $sum,4); 
	echo "<tr><td>".$k."</td><td>".unichr($k)."</td><td>".$v."</td><td>".$freq."%</td></tr>";

	}
echo "</table>";
 
 
   }
}
?> 
<h2 style="clear:both;">Readings</h2>
<ul>
<li><a href="http://docs.pykhmer.org/index.php?title=Design_of_an_ergonomic_keyboard_layout_for_Khmer_language">Project description</a></li>
<li><a href="http://www.ssec.wisc.edu/~tomw/java/unicode.html#x1780">Khmer Unicode Table reference</a></li>
<li><a href="http://www.php.net/">PHP reference</a></li>
<li><a href="http://dygraphs.com/index.html">dygraphs</a></li>
</ul>
<p style="border-top:1px solid #ccc; text-align:center;font-size:.9em">Coding in Progress, All Wrongs Reserved by <a href="mailto:vireax@ximplex.info">Vireax</a></p>
</div>

<script type="text/javascript">

  var el =  document.getElementById("graphdiv");
  var data= [
  <?php   
  $sum = array_sum($arr3);
  foreach ($arr3 as $k => $v) 
  	{
	//echo "\$a[$k] => $v.\n";
	$freq = round($v * 100 / $sum,4); 
	echo "[".$k.", ".$v.", ".$freq."],";
	}
  ?>];

  g = new Dygraph(el, data, 
  	{ 
  	 labels: [ "Char code", "Count", "Frequency" ],
  	 width:780,
  	 height:400,
  	 'Frequency': {axis: { }},
  	 axes: {
              x: {
                valueFormatter: function(ms) { return '&#' + ms + '; ('+'&amp;#'+ms+';)';},
                axisLabelFormatter: function(d) { return d;},
                pixelsPerLabel: 100,
               },
              y1: {
                valueFormatter: function(y) {return y;},
                axisLabelFormatter: function(y) {return y; }
               },
              y2: {
                valueFormatter: function(y2) {return y2+'%';},
                axisLabelFormatter: function(y2) {return y2.toPrecision(2)+'%'; }
               }
              },
//          fillGraph: true,
	title: 'Number of occurence',
          colors:['#3c3','#3a3'],
          xlabel: 'char code',
          // ylabel: 'Count',
         strokeWidth: 1,
          labelsSeparateLines: true,
          labelsDiv: document.getElementById("labels"),
          axisLabelFontSize:12
  	
  	}); 
  
 </script>
</body>
</html>
