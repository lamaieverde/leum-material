 <?php

define("GetGraph", "http://vitali.web.cs.unibo.it/raschietto/graph/ltw1516");
define("Insert", "GRAPH <http://vitali.web.cs.unibo.it/raschietto/graph/ltw1516>");


function Update($Query){
	try{
		$client = new EasyRdf_Sparql_Client(UpdateEndPoint());
		$client->update($Query);
		return null;
	}
	catch(Exception $ex){
		return $ex;
	}
}
function SELECT($Query){
	try{
		$client = new EasyRdf_Sparql_Client(QueryEndPoint());
		return $client->query($Query);
	}
	catch(Exception $ex){
		return null;
	}
}
function DirectSELECT($query){
	$query = urlencode($query);
	$query = QueryEndPoint()."?query=".$query;
	return get_remote_data($query);
}
function getMail(){
	return isset($_COOKIE['person']) ? $_COOKIE['person'] : 'anonymus';
}
function getTime(){
	return date("d-m-Y H:i:s", time());
}

function QueryEndPoint(){
	return "http://tweb2015.cs.unibo.it:8080/data/query";
}

function UpdateEndPoint(){
	return "http://tweb2015.cs.unibo.it:8080/data/update?user=ltw1516&pass=retEEf6!";
}

function getURL($fullPath){
    $path = explode('/',$fullPath);
    $ret = "";
    for($i = 0; $i<count($path)-1; $i++){
        $ret = $ret.$path[$i].'/';
    }
    return $ret;
}

function GetUrlName($url){
	$path = explode(".", $url);
	$return = false;
	foreach($path as $name){
		if($return) return $name;
		if(strpos($name, "www") !== false) $return = true;
	}
	return substr($path[0], 7);
}

function html_encode($var){
	return htmlentities($var, ENT_QUOTES, 'UTF-8') ;
}

function PrintNode(DOMNode $node, $u) {
	if ($node->nodeName == "img" || $node->nodeName == "a"){
		switch($node->nodeName){
			case "img":
				$indirizzo = $node->getAttribute('src');
				$absolute = strpos($indirizzo, "http://");
				$indirizzo = $absolute === false ? $u.$indirizzo : $indirizzo;
				echo "<img src='".$indirizzo."' />";  //se img prende l'indirizzo assoluto
			break;
			case "a":
				$indirizzo = $node->getAttribute('href');
				$absolute = strpos($indirizzo, "http://");
				$indirizzo = $absolute === false ? $u.$indirizzo : $indirizzo;
				echo "<a href='".$indirizzo."' target='_blank'>".$node->nodeValue."</a>";
			break;
		}
	}
	else
		echo "<".$node->nodeName.">".html_encode($node->nodeValue)."</".$node->nodeName.">";

}

function XmlReplaceFullPath($xml, $Path){
	$xmlArray = explode(" ", $xml);
	$len = count($xmlArray);
	for($i=0; $i<$len; $i++)
	{
		if(strpos($xmlArray[$i], "href=\"") !== false && strpos($xmlArray[$i], "http://") === false && strpos($xmlArray[$i], "www.") === false && strpos($xmlArray[$i], "href=\"#") === false){
			$miniArray = str_split($xmlArray[$i], 6);
			$xmlArray[$i] = "target=_blank href=\"".$Path;
			for($x=1; $x < count($miniArray); $x++){
				$xmlArray[$i] .= $miniArray[$x];
			}
			$xmlArray[$i] .= "\"";
		}
		elseif(strpos($xmlArray[$i], "src=\"") !== false && strpos($xmlArray[$i], "http://") === false && strpos($xmlArray[$i], "www.") === false){
			$miniArray = str_split($xmlArray[$i], 5);
			$xmlArray[$i] = "src=\"".$Path;
			for($x=1; $x < count($miniArray); $x++){
				$xmlArray[$i] .= $miniArray[$x];
			}
			$xmlArray[$i] .= "\"";
		}
		elseif(strpos($xmlArray[$i], "href=\"") !== false && (strpos($xmlArray[$i], "http://") !== false || strpos($xmlArray[$i], "www.") !== false)){
			$xmlArray[$i] = "target=_blank ".$xmlArray[$i];
		}
	}
        return implode(" ", $xmlArray);
}

function Normalize($s) {
    $replace = array(
        'ъ'=>'-', 'Ь'=>'-', 'Ъ'=>'-', 'ь'=>'-',
        'Ă'=>'A', 'Ą'=>'A', 'À'=>'A', 'Ã'=>'A', 'Á'=>'A', 'Æ'=>'A', 'Â'=>'A', 'Å'=>'A', 'Ä'=>'A',
        'Þ'=>'B',
        'Ć'=>'C', 'ץ'=>'C', 'Ç'=>'C',
        'È'=>'E', 'Ę'=>'E', 'É'=>'E', 'Ë'=>'E', 'Ê'=>'E',
        'Ğ'=>'G',
        'İ'=>'I', 'Ï'=>'I', 'Î'=>'I', 'Í'=>'I', 'Ì'=>'I',
        'Ł'=>'L',
        'Ñ'=>'N', 'Ń'=>'N',
        'Ø'=>'O', 'Ó'=>'O', 'Ò'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'Oe',
        'Ş'=>'S', 'Ś'=>'S', 'Ș'=>'S', 'Š'=>'S',
        'Ț'=>'T',
        'Ù'=>'U', 'Û'=>'U', 'Ú'=>'U', 'Ü'=>'U',
        'Ý'=>'Y',
        'Ź'=>'Z', 'Ž'=>'Z', 'Ż'=>'Z',
        'â'=>'a', 'ǎ'=>'a', 'ą'=>'a', 'á'=>'a', 'ă'=>'a', 'ã'=>'a', 'Ǎ'=>'a', 'а'=>'a', 'А'=>'a', 'å'=>'a', 'à'=>'a', 'א'=>'a', 'Ǻ'=>'a', 'Ā'=>'a', 'ǻ'=>'a', 'ā'=>'a', 'ä'=>'a', 'æ'=>'a', 'Ǽ'=>'a', 'ǽ'=>'a',
        'б'=>'b', 'ב'=>'b', 'Б'=>'b', 'þ'=>'b',
        'ĉ'=>'c', 'Ĉ'=>'c', 'Ċ'=>'c', 'ć'=>'c', 'ç'=>'c', 'ц'=>'c', 'צ'=>'c', 'ċ'=>'c', 'Ц'=>'c', 'Č'=>'c', 'č'=>'c', 'Ч'=>'c', 'ч'=>'c',
        'ד'=>'d', 'ď'=>'d', 'Đ'=>'d', 'Ď'=>'d', 'đ'=>'d', 'д'=>'d', 'Д'=>'d', 'ð'=>'d',
        'є'=>'e', 'ע'=>'e', 'е'=>'e', 'Е'=>'e', 'Ə'=>'e', 'ę'=>'e', 'ĕ'=>'e', 'ē'=>'e', 'Ē'=>'e', 'Ė'=>'e', 'ė'=>'e', 'ě'=>'e', 'Ě'=>'e', 'Є'=>'e', 'Ĕ'=>'e', 'ê'=>'e', 'ə'=>'e', 'è'=>'e', 'ë'=>'e', 'é'=>'e',
        'ф'=>'f', 'ƒ'=>'f', 'Ф'=>'f',
        'ġ'=>'g', 'Ģ'=>'g', 'Ġ'=>'g', 'Ĝ'=>'g', 'Г'=>'g', 'г'=>'g', 'ĝ'=>'g', 'ğ'=>'g', 'ג'=>'g', 'Ґ'=>'g', 'ґ'=>'g', 'ģ'=>'g',
        'ח'=>'h', 'ħ'=>'h', 'Х'=>'h', 'Ħ'=>'h', 'Ĥ'=>'h', 'ĥ'=>'h', 'х'=>'h', 'ה'=>'h',
        'î'=>'i', 'ï'=>'i', 'í'=>'i', 'ì'=>'i', 'į'=>'i', 'ĭ'=>'i', 'ı'=>'i', 'Ĭ'=>'i', 'И'=>'i', 'ĩ'=>'i', 'ǐ'=>'i', 'Ĩ'=>'i', 'Ǐ'=>'i', 'и'=>'i', 'Į'=>'i', 'י'=>'i', 'Ї'=>'i', 'Ī'=>'i', 'І'=>'i', 'ї'=>'i', 'і'=>'i', 'ī'=>'i', 'ĳ'=>'i', 'Ĳ'=>'i',
        'й'=>'j', 'Й'=>'j', 'Ĵ'=>'j', 'ĵ'=>'j', 'я'=>'j', 'Я'=>'a', 'Э'=>'e', 'э'=>'e', 'ё'=>'e', 'Ё'=>'E', 'ю'=>'u', 'Ю'=>'u',
        'ĸ'=>'k', 'כ'=>'k', 'Ķ'=>'k', 'К'=>'k', 'к'=>'k', 'ķ'=>'k', 'ך'=>'k',
        'Ŀ'=>'l', 'ŀ'=>'l', 'Л'=>'l', 'ł'=>'l', 'ļ'=>'l', 'ĺ'=>'l', 'Ĺ'=>'l', 'Ļ'=>'l', 'л'=>'l', 'Ľ'=>'l', 'ľ'=>'l', 'ל'=>'l',
        'מ'=>'m', 'М'=>'m', 'ם'=>'m', 'м'=>'m',
        'ñ'=>'n', 'н'=>'n', 'Ņ'=>'n', 'ן'=>'n', 'ŋ'=>'n', 'נ'=>'n', 'Н'=>'n', 'ń'=>'n', 'Ŋ'=>'n', 'ņ'=>'n', 'ŉ'=>'n', 'Ň'=>'n', 'ň'=>'n',
		'ö' => 'o', 'о'=>'o', 'О'=>'o', 'ő'=>'o', 'õ'=>'o', 'ô'=>'o', 'Ő'=>'o', 'ŏ'=>'o', 'Ŏ'=>'o', 'Ō'=>'o', 'ō'=>'o', 'ø'=>'o', 'ǿ'=>'o', 'ǒ'=>'o', 'ò'=>'o', 'Ǿ'=>'o', 'Ǒ'=>'o', 'ơ'=>'o', 'ó'=>'o', 'Ơ'=>'o', 'œ'=>'o', 'Œ'=>'e',
        'פ'=>'p', 'ף'=>'p', 'п'=>'p', 'П'=>'p',
        'ק'=>'q',
        'ŕ'=>'r', 'ř'=>'r', 'Ř'=>'r', 'ŗ'=>'r', 'Ŗ'=>'r', 'ר'=>'r', 'Ŕ'=>'r', 'Р'=>'r', 'р'=>'r',
        'ș'=>'s', 'с'=>'s', 'Ŝ'=>'s', 'š'=>'s', 'ś'=>'s', 'ס'=>'s', 'ş'=>'s', 'С'=>'s', 'ŝ'=>'s', 'Щ'=>'s', 'щ'=>'s', 'ш'=>'s', 'Ш'=>'s', 'ß'=>'s',
        'т'=>'t', 'ט'=>'t', 'ŧ'=>'t', 'ת'=>'t', 'ť'=>'t', 'ţ'=>'t', 'Ţ'=>'t', 'Т'=>'t', 'ț'=>'t', 'Ŧ'=>'t', 'Ť'=>'t', '™'=>'t',
        'ū'=>'u', 'у'=>'u', 'Ũ'=>'u', 'ũ'=>'u', 'Ư'=>'u', 'ư'=>'u', 'Ū'=>'u', 'Ǔ'=>'u', 'ų'=>'u', 'Ų'=>'u', 'ŭ'=>'u', 'Ŭ'=>'u', 'Ů'=>'u', 'ů'=>'u', 'ű'=>'u', 'Ű'=>'u', 'Ǖ'=>'u', 'ǔ'=>'u', 'Ǜ'=>'u', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'У'=>'u', 'ǚ'=>'u', 'ǜ'=>'u', 'Ǚ'=>'u', 'Ǘ'=>'u', 'ǖ'=>'u', 'ǘ'=>'u', 'ü'=>'u',
        'в'=>'v', 'ו'=>'v', 'В'=>'v',
        'ש'=>'w', 'ŵ'=>'w', 'Ŵ'=>'w',
        'ы'=>'y', 'ŷ'=>'y', 'ý'=>'y', 'ÿ'=>'y', 'Ÿ'=>'y', 'Ŷ'=>'y',
        'Ы'=>'y', 'ž'=>'z', 'З'=>'z', 'з'=>'z', 'ź'=>'z', 'ז'=>'z', 'ż'=>'z', 'ſ'=>'z', 'Ж'=>'j', 'ж'=>'j',
		"\t" => ' ', "\n" => ' ', "\r" => ' ', '"'=>' '
    );
    return strtr($s, $replace);
}

function AddIDs($node, $father){
	if ($node->nodeType !== XML_ELEMENT_NODE) return;
	$arr  = array();
	foreach ($node->childNodes as $childNode){
		$index = CountInArray($arr, $childNode->nodeName);
		$arr[] = $childNode->nodeName;
        $childNode = AddIDs($childNode, $father."_".$childNode->nodeName.($index + 1));
	}
	$node->removeAttribute('class');
	$node->removeAttribute('id');
	$node->setAttribute('id', str_replace("_body1_", "", $father));
	return $node;
}

function CountInArray($arr, $string){
$count = 0;
foreach ($arr as $item)
	if ($item == $string)
		$count++;
return $count;
}
function get_remote_data($url, $post_paramtrs=false)    {   $c = curl_init();curl_setopt($c, CURLOPT_URL, $url);curl_setopt($c, CURLOPT_RETURNTRANSFER, 1); if($post_paramtrs){curl_setopt($c, CURLOPT_POST,TRUE);  curl_setopt($c, CURLOPT_POSTFIELDS, "var1=bla&".$post_paramtrs );}  curl_setopt($c, CURLOPT_SSL_VERIFYHOST,false);curl_setopt($c, CURLOPT_SSL_VERIFYPEER,false);curl_setopt($c, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; rv:33.0) Gecko/20100101 Firefox/33.0"); curl_setopt($c, CURLOPT_COOKIE, 'CookieName1=Value;'); curl_setopt($c, CURLOPT_MAXREDIRS, 10);  $follow_allowed= ( ini_get('open_basedir') || ini_get('safe_mode')) ? false:true;  if ($follow_allowed){curl_setopt($c, CURLOPT_FOLLOWLOCATION, 1);}curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 9);curl_setopt($c, CURLOPT_REFERER, $url);curl_setopt($c, CURLOPT_TIMEOUT, 60);curl_setopt($c, CURLOPT_AUTOREFERER, true);         curl_setopt($c, CURLOPT_ENCODING, 'gzip,deflate');$data=curl_exec($c);$status=curl_getinfo($c);curl_close($c);preg_match('/(http(|s)):\/\/(.*?)\/(.*\/|)/si',  $status['url'],$link);$data=preg_replace('/(src|href|action)=(\'|\")((?!(http|https|javascript:|\/\/|\/)).*?)(\'|\")/si','$1=$2'.$link[0].'$3$4$5', $data);$data=preg_replace('/(src|href|action)=(\'|\")((?!(http|https|javascript:|\/\/)).*?)(\'|\")/si','$1=$2'.$link[1].'://'.$link[3].'$3$4$5', $data);if($status['http_code']==200) {return $data;} elseif($status['http_code']==301 || $status['http_code']==302) { if (!$follow_allowed){if(empty($redirURL)){if(!empty($status['redirect_url'])){$redirURL=$status['redirect_url'];}}   if(empty($redirURL)){preg_match('/(Location:|URI:)(.*?)(\r|\n)/si', $data, $m);if (!empty($m[2])){ $redirURL=$m[2]; } } if(empty($redirURL)){preg_match('/href\=\"(.*?)\"(.*?)here\<\/a\>/si',$data,$m); if (!empty($m[1])){ $redirURL=$m[1]; } }   if(!empty($redirURL)){$t=debug_backtrace(); return call_user_func( $t[0]["function"], trim($redirURL), $post_paramtrs);}}} return "ERRORCODE22 with $url!!<br/>Last status codes<b/>:".json_encode($status)."<br/><br/>Last data got<br/>:$data";}

?>
