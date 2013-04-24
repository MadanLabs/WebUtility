<?php
function getBitlyURL($url) {
	$shortUrl = file_get_contents('http://api.bit.ly/shorten?version=2.0.1&longUrl='.urlencode($url).'&login=slimboard&apiKey=R_b801e7d2911175fcde4037c7adbb0c75&format=json');
	$json = @json_decode($shortUrl,true);
	return $json['results'][$url]['shortUrl'];
}
?>
