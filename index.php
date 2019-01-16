<?php

/**
 * Shortens link application.
 *
 * Written in PHP 7.2.4 / Windows 10
 * Author: Alvin Tang
 * Date Created: 2018-05-17
 * Date Last Modified: 2018-05-22
 *
*/

// Require necessary files
require_once("include/defaults.php");
require_once("include/db-connect.php");
require_once("include/errors.php");

// If the URL parameter 'shorten' is defined
if (isset($_GET['name'])) {

	// Fetch every row of the database until the shorten link is found
	$query = $db_connection->query('SELECT * FROM tb_links');
	while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
		if (strcmp($row['name'], $_GET['name']) == 0) {
			$expired = new DateTime($row['expire']);
			// If not expired, then redirect
			if ($expired > $today) {
				header("Location: " . $row['link']);
				echo "<a href=\"" . $row['link'] ."\">Click here.</a>";
				die();
			} else {
				// Delete this row if expired
				$db_connection->prepare('DELETE FROM tb_links WHERE id = ?')->execute(array($row['id']));
			}
		}
	}

	// Define to provide error message
	$err = true;

}

?>
<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Shorten.</title>
		<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no">
		<meta name="keywords" content="atng, alvin, tang, shorten, save, share, links">
		<meta name="description" content="Shorten a link and share it freely.">
		<meta name="image" content="https://atng.me/static/images/opengraph.jpg">
		<!-- Schema.org for Google -->
		<meta itemprop="name" content="Shorten.">
		<meta itemprop="description" content="Shorten a link and share it freely.">
		<meta itemprop="image" content="https://atng.me/static/images/opengraph.jpg">
		<!-- Open Graph general (Facebook, Pinterest & Google+) -->
		<meta name="og:title" content="Shorten.">
		<meta name="og:description" content="Shorten a link and share it freely.">
		<meta name="og:image" content="https://atng.me/static/images/opengraph.jpg">
		<meta name="og:url" content="https://atng.me">
		<meta name="og:site_name" content="Shorten.">
		<meta name="og:locale" content="en_CA">
		<meta name="og:type" content="website">
		<link rel="apple-touch-icon" sizes="180x180" href="/static/images/icons/apple-touch-icon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="/static/images/icons/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="/static/images/icons/favicon-16x16.png">
		<link rel="manifest" href="/static/images/icons/site.webmanifest">
		<link rel="mask-icon" href="/static/images/icons/safari-pinned-tab.svg" color="#000000">
		<link rel="shortcut icon" href="/static/images/icons/favicon.ico">
		<meta name="msapplication-TileColor" content="#da532c">
		<meta name="msapplication-config" content="/static/images/icons/browserconfig.xml">
		<meta name="theme-color" content="#ffffff">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous" />
		<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,700" rel="stylesheet">
		<link rel="stylesheet" href="static/css/shorten-theme.css" />
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/brands.css" integrity="sha384-VGCZwiSnlHXYDojsRqeMn3IVvdzTx5JEuHgqZ3bYLCLUBV8rvihHApoA1Aso2TZA" crossorigin="anonymous">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/fontawesome.css" integrity="sha384-GVa9GOgVQgOk+TNYXu7S/InPTfSDTtBalSgkgqQ7sCik56N9ztlkoTr2f/T44oKV" crossorigin="anonymous">
	</head>
	<body>
		<?php
		// Displays a message as the name was not found in the database earlier
		if (isset($_GET['name'])) {
			echo "<div class=\"p-3 bg-danger text-white\">\n";
			echo "			<p><strong>Sorry!</strong> The shortened link may have been expired, removed, or never even existed.</p>\n";
			echo "		</div>\n";
		}
		?>
		<header class="head-bg">
			<div class="text-center head-txt-wrap">
				<h1 class="font-weight-bold">Shorten.</h1>
				<h2 class="h3">Remember less, do more.</h2>
			</div>
		</header>
		<div class="body-content-wrap">
		<noscript>
			<div class="p-3 bg-warning text-dark">
				<strong>Please enable javascript to use this tool. <a href="http://activatejavascript.org">Learn more.</a></strong>
			</div>
		</noscript>
			<section id="shorten" style="display:none;">
				<div class="mb-4 alert alert-danger" role="alert" style="display:none" id="errorSection">
					<p><strong id="errorTitle"></strong></p>
					<p id="errorMessage"></p>
				</div>
				<div class="mb-4 alert alert-success" role="alert" style="display:none" id="successSection">
					<p><strong>Success!</strong>&nbsp;The shortened link is <span id="successShortened"></span>.</p>
					<button id="copyUrl" class="btn btn-success mt-2" data-clipboard-text="null" style="display:none"><i class="far fa-clipboard"></i>&nbsp;&nbsp;&nbsp;Copy link to clipboard</button>
				</div>
				<form method="post" action="shorten-action.php" id="shortenForm">
					<div class="input-group">
						<input type="url" name="shortenThis" id="shortenThis" placeholder="Enter a link to shorten." class="form-control" autofocus />
						<div class="input-group-append">
							<input type="submit" value="Shorten" name="submitLongUrl" id="submitLongUrl" class="btn btn-secondary" disabled>
						</div>
					</div>
					<small class="form-text text-muted">Enter a valid link with http, https or ftp at the beginning. By default, the shortened link will expire in one year.</small>
					<div class="card mt-4">
						<div class="card-header">
    						Additional Options
 						</div>
  						<div class="card-body">
  							<div class="form-row">
    							<div class="form-group col-md-6">
      								<label for="expirationOfLink">Expiration</label>
      								<input type="datetime-local" class="form-control" name="expirationOfLink" id="expirationOfLink" value="<?php echo $default_expiry->format('Y-m-d') ?>T<?php echo $default_expiry->format('H:i') ?>">
      								<small class="form-text text-muted">Choose a date (EST) when the shortened link will expire.<br/>5 year limit.</small>
    							</div>
    							<div class="form-group col-md-6">
      								<label for="customName">Custom Name</label>
      								<input type="text" class="form-control" name="customName" id="customName" placeholder="example of https://atng.me/example">
      								<small class="form-text text-muted">Choose a custom name for your shortened link or leave it blank.<br/>10 character limit.</small>
    						</div>
  						</div>
  					</div>
  				</div>
				</form>
			</section>
		</div>
		<footer class="text-center">
			<p><strong>Share this tool.</strong></p>
			<div class="foot-share">
				<a href="https://www.facebook.com/sharer/sharer.php?u=https%3A//atng.me" title="Share on Facebook" target="_blank"><i class="fab fa-facebook-f"></i></a>&nbsp;
				<a href="https://twitter.com/home?status=https%3A//atng.me" title="Share on Twitter" target="_blank"><i class="fab fa-twitter"></i></a>
			</div>
			<small>Made with love from <a href="http://alvintang.me">Alvin Tang</a>.</small>
		</footer>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.0/clipboard.min.js"></script>
		<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
		<script src="static/js/validate-url.js"></script>
		<script src="static/js/errors.js"></script>
		<script>
		$(document).ready(function(){$("#shorten").show();});$("#shortenForm").submit(function(event) {event.preventDefault();$.post("shorten-action.php", $("#shortenForm").serialize(), function( data ) {if(data.status == "200") {$("#errorSection").hide();$("#successSection").show();$("#successShortened").html(data.name);$("#copyUrl").removeAttr("data-clipboard-text");$("#copyUrl").attr("data-clipboard-text", data.name);} else {$("#errorSection").show();$("#successSection").hide();$("#errorTitle").html(errors[data.status][0]);$("#errorMessage").html(errors[data.status][1]);}}, "json");});
			new ClipboardJS('#copyUrl');if (ClipboardJS.isSupported()) {$("#copyUrl").show();}
		</script>
	</body>
</html>