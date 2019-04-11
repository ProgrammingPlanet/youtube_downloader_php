

<!DOCTYPE html>
<html>
<head>
	<title>Youtube Downloader</title>

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

</head>
<body><br>
	<div class="container-fluid">
		<div class="bg-warning py-5 rounded">
			<h3 class="text-center">Youtube Downloader in PHP</h3><br>
			<h5 class="text-center">Like Us on Facebook <a href="https://www.facebook.com/codingplanet1"> Programming Planet</a></h5>
		</div><br><br>
		<div class="row mx-auto">
		    <div class="col-10 mt-5">
		        <input type="text" class="form-control form-control-lg" id="link" value="https://www.youtube.com/watch?v=bGutI6CNgII">
		    </div>
		    <div class="col-1 mt-5">
		        <button type="submit" class="btn btn-success btn-lg" onclick="return fetch();">Submit</button>
		    </div>
		</div><br><br>
		<div id="video_result" class="border border-info rounded p-5">
			<!-- content for youtube video result -->
			<div class="container">
				<ul class="list-group">
					<li>Enter URL of a youtube video and click on submit</li>
					<li>Video will be fetch from url and show here with all quality availble</li>
					<li>Like Us on Facebook <a href="https://www.facebook.com/codingplanet1"> Programming Planet</a></li>
				</ul>
			</div>
		</div>
	</div>
</body>
</html>

<script type="text/javascript">

	function getUrlVars(url) {
		var str = url.substring( url.indexOf('?') + 1 );
		var params = [];
		var vars = str.split('&');
		$.each(vars, function (key, val) {
			var x = val.split('=');
			params[x[0]] = x[1];
		});
		return params;
	}

	function fetch(){
		var link_id = getUrlVars($('#link').val())['v'];
		$('#video_result').html('<br><center>Loading...<br><img src="loader.gif" width="70"></center><br>');
		if (link_id!=undefined){
			$.ajax({
				url: 'opreations.php', type: 'POST', data:{ opreation: 'fetch_video', video_id: link_id },
				success:function(result){
					$('#video_result').html(result);
				}
			});
		} else{
			alert('Not A valid Video URL, Try Again..');
		}
	}

	function download(url,customName=''){

		/*var link = document.createElement("a");
	    link.download = customName;
	    link.href = url;
	    alert();
	    link.click();*/
		console.log(url+' => '+customName);
		window.open(url);
	}
</script>