<?php
	
	function curl_it($url){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		return curl_exec($ch);
	}

	if(isset($_REQUEST['opreation'])) $opreation = $_REQUEST['opreation'];
	else $opreation = '';

	

if($opreation=='fetch_video'){
	//$id = 'bGutI6CNgII';
	$id = $_REQUEST['video_id'];

	$link = "http://www.youtube.com/get_video_info?video_id=$id";
	
	parse_str(curl_it($link),$data);

	if ($data['status']=='ok') {
		$title = $data['title'];
		$thumbnail_img = $data['thumbnail_url'];
		$length_sec = $data['length_seconds'];
		$channel = $data['author'];
		
	}
	else{
		die('Error: Video Url is incorrect or video not found!');
	}

	$play_resp = json_decode($data['player_response']);

	$expire_in_sec = $play_resp->streamingData->expiresInSeconds;
	$core_formats = $play_resp->streamingData->formats;		//array of objects
	$other_formats = $play_resp->streamingData->adaptiveFormats;	//array of objects

	$info = ['title'=>$title,'thumbnail'=>$thumbnail_img,'length_sec'=>$length_sec,'channel_name'=>$channel,'expire_sec'=>$expire_in_sec];
	
	$tmp = $core_formats;
	$core_formats = array();

	foreach ($tmp as $key => $obj) {

		$itag = $obj->itag;
		$url = urldecode($obj->url);
		$type = $obj->mimeType;
		$width = $obj->width;
		$height = $obj->height;
		$quality = $obj->quality;
		$pixel = $obj->qualityLabel;

		if (isset($obj->contentLength)) $size = $obj->contentLength;
		else $size = '';
		
		$core_formats[$itag] = ['url'=>$url,'type'=>$type,'width'=>$width,'height'=>$height,'quality'=>$quality,'pixel'=>$pixel,'size'=>$size];
	}

	$tmp = $other_formats;
	$other_formats = array();

	foreach ($tmp as $key => $obj) {

		if (isset($obj->contentLength)) $size = $obj->contentLength;
		else $size = '';

		$itag = $obj->itag;
		$url = urldecode($obj->url);
		$type = $obj->mimeType;
		$quality = $obj->quality;
		if (substr($type,0,5)!='audio'){
			$width = $obj->width;
			$height = $obj->height;
			$pixel = $obj->qualityLabel;
		}else{
			$width = $height = $pixel = '';
		}
		
		$other_formats[$itag] = ['url'=>$url,'type'=>$type,'width'=>$width,'height'=>$height,'quality'=>$quality,'pixel'=>$pixel,'size'=>$size];
	}

	$formats = ['core'=>$core_formats,'other'=>$other_formats];
/*echo '<pre>';
	print_r($info);
	print_r($formats);
echo '</pre>';*/

?>
	<br>
	<div class="row">
		<div class="col-4 mx-auto"><img src="<?= $info['thumbnail'] ?>" style="width:100%;height:90%;"></div>
		<div class="col-6 mx-auto">
			<table class="table">
				<tr>
					<td>video Title</td>
					<td><?= $info['title'] ?></td>
				</tr>
				<tr>
					<td>Video length</td>
					<td><?= gmdate("H:i:s", $info['length_sec']) ?></td>
				</tr>
				<tr>
					<td>youtube channel</td>
					<td><?= $info['channel_name'] ?></td>
				</tr>
				<tr>
					<td>Expire time</td>
					<td><?= gmdate("H:i:s", $info['expire_sec'])?></td>
				</tr>
				
			</table>
		</div>
	</div><br><br>
	<div class="row">
		<h2 class="text-center text-success">Youtube standerd Formats (videos)</h2>
		<table class="table table-success">
			<thead class="bg-warning">
				<tr>
					<td>video code</td>
					<td>type</td>
					<td>Size</td>
					<td>Quality</td>
					<td>Download size</td>
					<td></td>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($formats['core'] as $key => $value){ 
				$dsize = round(intval($value['size'])/1000000,2);
				if ($dsize==0) $dsize=''; else $dsize .= ' MB'; ?>
				<tr>
					<td><?= $key ?></td>
					<td><?= $value['type'] ?></td>
					<td><?= $value['width'].'x'.$value['height'] ?></td>
					<td><?= $value['quality'].','.$value['pixel'] ?></td>
					<td><?= $dsize ?></td>
					<td><a download="WORLD WAR Z - 18 Minutes of Gameplay Demo (PS4, XBOX ONE, PC) Zombie Game 2019.mp4" class="btn btn-success" href="<?= $value['url'] ?>">Download</a></td>
				</tr>
			<?php } ?>
			</tbody>
		</table>
	</div><br><br>
	<div class="">
		<h2 class="text-center text-info">Youtube Other Formats (Splitted video and Audio)</h2>
		<table class="table table-info">
			<thead class="bg-warning">
				<tr>
					<td>video code</td>
					<td>type</td>
					<td>Size</td>
					<td>Quality</td>
					<td>Download size</td>
					<td></td>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($formats['other'] as $key => $value){ 
				$dsize = round(intval($value['size'])/1000000,2);
				if ($dsize==0) $dsize=''; else $dsize .= ' MB';
				if($value['width']=='') $size = 'mp3,Audio';
				else $size = $value['width'].'x'.$value['height']; ?>
				<tr>
					<td><?= $key ?></td>
					<td><?= $value['type'] ?></td>
					<td><?= $size ?></td>
					<td><?= $value['quality'].','.$value['pixel'] ?></td>
					<td><?= $dsize ?></td>
					<td><a download="WORLD WAR Z - 18 Minutes of Gameplay Demo (PS4, XBOX ONE, PC) Zombie Game 2019.mp4" class="btn btn-success" href="<?= $value['url'] ?>">Download</a></td>
				</tr>
			<?php } ?>
			</tbody>
		</table>
	</div>
<?php 

}  


















?>