<?php

require "config.php";

date_default_timezone_set( CLOCKBACK_TIMEZONE );

?><!doctype html>
<html>
	<head>
		<title>Turn the Clockback</title>
		<meta charset="UTF-8" />
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
		<meta name="viewport" content="user-scalable=no,width=device-width" />
		<link rel="icon" sizes="180x180" href="icon-180.png?mtime=<?php echo filemtime( "icon-180.png"); ?>" />
		<link rel="apple-touch-icon" sizes="180x180" href="icon-180.png?mtime=<?php echo filemtime( "icon-180.png"); ?>" />
		<style type="text/css">
			body {
				margin: 0;
				padding: 0;
				font-family: Futura, Helvetica, sans-serif;
				background-color: #fbd000;
			}
			
			h1 {
				background-color: #fbd000;
				text-transform: lowercase;
				text-align: center;
				padding: 1rem;
				color: #fff;
				font-size: 120%;
				margin: 0;
			}

			h2 {
				font-size: 75%;
				display: inline-block;
				padding: .5rem 2rem;
				color: #fff;
			}

			h2:nth-of-type(6n-5) {
				background-color: #f9613e;
			}

			h2:nth-of-type(6n-4) {
				background-color: #86d8d9;
			}

			h2:nth-of-type(6n-3) {
				background-color: #26b8ed;
			}

			h2:nth-of-type(6n-2) {
				background-color: #d971a3;
			}

			h2:nth-of-type(6n-1) {
				background-color: #2e2f16;
			}

			h2:nth-of-type(6n) {
				background-color: #fbd000;
			}
			
			.feed {
				background-color: #fff;
			}

			img {
				box-sizing: border-box;

				width: 100%;
				padding: 10px 0px;
				display: block;
				border: 1px solid #ddd;
				border-width: 0 0 1px 0;
			}
			
			@media (min-width: 1000px) {
				body {
					width: 500px;
					margin-left: auto;
					margin-right: auto;
				}
			}
		</style>
	</head>
	<body>
		<h1>Clockback</h1>
		<div class="feed">
			<?php

			$photos = glob( "photos/*.*" );
			$photos = array_reverse( $photos );

			$years_ago = 0;
		
			$this_year = date( "Y" );
			$day = date( "m-d" );
		
			foreach ( $photos as $photo ) {
				if ( ! preg_match( '/photos\/([0-9]{4})-' . $day . '/', $photo, $year ) ) {
					continue;
				}

				$year = $year[1];

				if ( $this_year - $year != $years_ago ) {
					$years_ago = $this_year - $year;

					echo '<h2>' . $years_ago . ' Year' . ( $years_ago > 1 ? 's' : '' ) . ' Ago (' . $year . ')</h2>';
				}

				echo '<img src="' . $photo . '" alt="' . htmlspecialchars( $photo ) . '" title="' . htmlspecialchars( $photo ) . '" />';
			}

			?>
		</div>
	</body>
</html>

