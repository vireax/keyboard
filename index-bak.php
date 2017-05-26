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
#wrap {margin:0 auto;width:680px; text-align:left;padding:20px 0;}
</style>
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
if ($thisUrl){echo "Submitted url : ". $_GET["url"];}
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

/* DISPLAY ORIGINAL STRING IN A TEXT BOX */ 
	echo ("<br/><textarea readonly='readonly' style='width:100%; height:200px;' id='text1'>");	
	$pattern ='/[^\x{1780}-\x{17E9}]+/u';
	$s=preg_replace($pattern,"",$urlContent);
	echo $s; 
  	echo ("</textarea>");

/* DISPLAY HTML ENTITIES IN DECIMAL NUMBER */  
/* http://www.php.net/manual/en/function.mb-decode-numericentity.php#37547 */
	echo ("<br/><textarea readonly='readonly' style='width:100%; height:200px;' id='text2'>");
	$convmap = array(0x0, 0x2FFFF, 0, 0xFFFF);
	echo htmlentities(mb_encode_numericentity($s, $convmap, 'UTF-8'));
	echo ("</textarea>");
	 echo "Total characters: ".strlen($s)."<br/>";
	
/* READ STRING INTO AN ARRAY */
/*	$count = new array();
	for (i=0; i<strlen($s); i++)
	{		$chr = htmlentities(mb_encode_numericentity($s, $convmap, 'UTF-8'));	}	
*/
	$arr1 = str_split($s);
//	echo (array_count_values($arr1));	
	$arr2 = array_count_values($arr1);
	echo "Size of arr1: ".sizeof($arr1)."<br/>";
	echo "Size of arr2: ".sizeof($arr2)."<br/>";
	echo "Count(arr2): ".count($arr2)."<br/>";
	print_r ($arr2);
//	print_r(array_count_values($arr1));
	
	
	
	
	
?>

<div style="width: 100%; margin: 20px auto; font-family:sans-serif; background:#ccc; text-align:center;">
<?php
/** Include class */
include( 'GoogChart.class.php' );
/** Create chart */
$chart = new GoogChart();
// $color = array(                        '#99C754'                );

/* # Chart  # */
echo '<h2>Timeline</h2>';
// $arr2 = array('mon'=>1, 'tue'=>2, 'wed'=>4, 'thu'=>7);
$chart->setChartAttrs( array(
        'type' => 'sparkline',
        'title' => 'Character Frequency',
//        'data' => $dataTimeline,
        'data' => $arr2,
        'size' => array( 600, 400 ),
        'color' => '#cc0000',
        'labelsXY' => true,

        ));
// Print chart
echo $chart;
?>
</div>

<!--
 <script type="text/javascritp">
	var i, b;
	var c= document.getElementById('text1').innerHTML;
	for(i=0; i<b.length; i++){
		if(b.charCodeAt(i)>127){ c += '&#' + b.charCodeAt(i) + ';'; }else{ c += b.charAt(i); }
  	}
  	document.getElementbyId('text1').innerHTML = c;
</script>
-->
<?php
  
   }
}
?> 

</div>

</body>
</html>
