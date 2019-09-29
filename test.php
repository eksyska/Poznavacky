<?php
	$redirectIn = false;
	$redirectOut = true;
	require 'verification.php';    //Obsahuje session_start();
    
	if (!isset($_SESSION['current']))	//Poznávačka nenastavena --> přesměrování na stránku s výběrem
	{
		echo "<script type='text/javascript'>location.href = 'list.php';</script>";
	}
?>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width" />
		<link rel="stylesheet" type="text/css" href="css.css">
		<script type="text/javascript" src="test.js"></script>
		<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
		<link rel="icon" href="favicon.ico">
		<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="/icon-32x32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="/icon-16x16.png">
		<link rel="manifest" href="/manifest.json">
		<link rel="mask-icon" href="/safari-pinned-tab.svg" color="#ffc835">
		<meta name="theme-color" content="#ffffff">
		<title>Testing</title>
	</head>
	<body>
    <div class="container">
        <header>
            <h1>Testing</h1>
        </header>
    	<main class="basic_main">
    		<fieldset>
    			<img id="image" class="img" src="imagePreview.png">
    			<div id="inputOutput">
    				<form onsubmit="answer(event)" id="answerForm">
    					<input type=text class="text" id="textfield" autocomplete="off" placeholder="Your answer">
    					<input type=submit class="button" value="OK" />
    				</form>
    				<span id="correctAnswer">Correct!</span>
    				<div id="wrongAnswer">
    					<span id="wrong1">Wrong!</span><br>
    					<span id="wrong2">The correct answer is </span>
    					<span id="serverResponse"></span>
    			    </div>
    			<button onclick="next()" class="button" id="nextButton">Next</button>
    			</div>
    			<button onclick="reportImg(event)" id="reportButton" class="button">Report</button>
    			<select id="reportMenu" class="text">
    				<option>The picture doesn't load properly</option>
    				<option>The picture displays a different organism</option>
    				<option>The picture contains the name of the organism</option>
    				<option>The picture has bad resolution</option>
    				<option>The picture infringes copyright</option>
    			</select>
    			<button onclick="submitReport(event)" id="submitReport" class="button">Send</button>
    			<button onclick="cancelReport(event)" id="cancelReport" class="button">Cancel</button>
    		</fieldset>
    		<a href="menu.php"><button class="button">Back</button></a>
		</main>
    </div>
		<footer>
			<div id="help" class="footerOption"><a target='_blank' href="https://github.com/HonzaSTECH/Poznavacky/wiki">Help</a></div>
			<div id="issues" class="footerOption" onclick="showLogin()"><a target='_blank' href="https://github.com/HonzaSTECH/Poznavacky/issues/new/choose">Have you found a problem?</a></div>
			<div class="footerOption"><a target='_blank' href='https://github.com/HonzaSTECH/Poznavacky/blob/master/TERMS_OF_SERVICE.md'>Terms of use</a></div>
			<div id="about" class="footerOption">&copy Štěchy and Eksyska, 2019 - <a target='_blank' href="https://www.itnetwork.cz/">ITnetwork</a> summer competition 2019</div>
         	<script>
             	function showLogin()
             	{
             		alert("Account login for bug reports:\nName: gjvj\nPassword: poznavacky71");
             	}
         	</script>
        </footer>
	</body>
	<script>
		getRequest("getRandomPic.php", showPic);
	</script>
</html>