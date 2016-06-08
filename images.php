<?php
// Start the session
session_start();
include 'php/imageupload.php';
?>
<!DOCTYPE html>
<html>
<head>
	<title>Images</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="author" content="Corey Koval, K3CPK">
	<meta name="application-name" content="Field Day Logging Database" />
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<script type="text/javascript" src="/js/fdlog.js"></script>
	<script type="text/javascript" src="js/jquery.js"></script>
</head>
<body  onload="startTime()">
	<div id="outer_wrapper" class="grid">
		<div class="row">
			<div id="menu" class="col-2">
				<?php include 'navbar.php'; ?>
			</div>
			<div id="header2" class="col-10">
				<?php include 'header.php'; ?>
				<div id="imgcontent">
					<?php
						if (isset($_SESSION['priv'])){
							echo'
							<div class="row">
								<form method="POST" action="'.$_SERVER["PHP_SELF"].'" enctype="multipart/form-data">
									<span>Choose an image to upload:
										<input type="file" name="imageupload" id="imageupload"><br>
										Limit: 8MB<br>
									</span><br>
									<span>Description:<br>
										<textarea name="description" rows="5" cols="45"></textarea><br>
									</span><br>
									<input type="submit" name="submit" value="Upload Image"/><br>
								</form>
								<span class="error">
									'.$error.'<br>
									'.$error2.'
								</span>
							</div>
							<hr>';
							include 'php/displayimages.php';
						} else {
							echo '<h2>Sign in to use this page</h2>';
						} 
					?>			
				</div>
			</div>
		</div>
	</div>
	
<script>
	console.log("Sigh");
	console.log("Loading");
	jQuery(document).ready(function($) {
		console.log("jQuery Loading");
		$(".lightbox_trigger").click(function(e) {
			//prevent default action (hyperlink)
			e.preventDefault();
			//Get clicked link href
			var image_href = $(this).attr("href");
			/* 	
			If the lightbox window HTML already exists in document, 
			change the img src to to match the href of whatever link was clicked
			
			If the lightbox window HTML doesn't exists, create it and insert it.
			(This will only happen the first time around)
			*/
			if ($('#lightbox').length > 0) { // #lightbox exists
				
				//place href as img src value
				$('#frame').html('<img src="' + image_href + '" />');
				
				//show lightbox window - you could use .show('fast') for a transition
				$('#lightbox').show();
			}
			
			else { //#lightbox does not exist - create and insert (runs 1st time only)
				
				//create HTML markup for lightbox window
				var lightbox = 
				'<div id="lightbox">' +
					'<p>Click to close</p>' +
					'<div id="frame">' + //insert clicked link's href into img src
						'<img src="' + image_href +'" />' +
					'</div>' +	
				'</div>';
					
				//insert lightbox HTML into page
				$('body').append(lightbox);
			}
		});
		
		//Click anywhere on the page to get rid of lightbox window
		$('body').on('click', '#lightbox', function(){ //must use live, as the lightbox element is inserted into the DOM
			$('#lightbox').hide();
		});

	});
</script>
</body>
</html>