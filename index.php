<?php
	$redirectIn = true;
	$redirectOut = false;
	include 'verification.php';    //Obsahuje session_start();
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width" />
		<link rel="stylesheet" type="text/css" href="css.css">
		<script type="text/javascript" src="index.js"></script>
		<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
		<link rel="icon" href="favicon.ico">
		<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="/icon-32x32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="/icon-16x16.png">
		<link rel="manifest" href="/manifest.json">
		<link rel="mask-icon" href="/safari-pinned-tab.svg" color="#ffc835">
		<meta name="theme-color" content="#ffffff">
		<title>Verification</title>
	</head>
	<body id="root">
		<div class="container">
			<main>
				<?php
    			//Zjistit, zda se již na tomto počítači někdo nedávno přihlašoval, nebo zda existují chyby registrace k zobrazení
			    if (isset($_SESSION['registerErrors']) || (!isset($_COOKIE['lastChangelog'])) && !isset($_SESSION['loginError']) && !isset($_SESSION['passwordRecoveryError']))
					{
						//Podmínka splněna --> nechat zobrazený registrační formulář
						echo "<div id='registrace' style='display:block'>";    				    }
					else
					{
						//Podmínka nesplněna --> skrýt registrační formulář
						echo "<div id='registrace' style='display:none'>";
					}
				?>
					<h2>Register</h2>
					<div class="udaje">
						<input id='register_name' type='text' name='name_input' maxlength=15 placeholder='Username' required=true class='text'>
						<br>
						<input id='register_pass' type='password' name='pass_input' maxlength=31 placeholder='Password' required=true class='text'>
						<br>    				    	
						<input id='register_repass' type='password' name='repass_input' maxlength=31 placeholder='Password' required=true class='text'>
						<br>
						<input id='register_email' type='email' name='email_input' maxlength=255 placeholder='E-mail (optional)' class='text'>
						<br>
						<span id='span_terms'>By registering you agree with the <a target='_blank' href='https://github.com/HonzaSTECH/Poznavacky/blob/master/TERMS_OF_SERVICE.md'>terms of use</a>.</span>
						<br>
						<button id='register_submit' onclick='register()' class='button' class='confirm button'>Create an account</button>
					</div>
					<span class='toggleForms'>Already registered? <a href="javascript:showLogin()">Log in</a>.</span>
					<div id='registerErrors'>
					</div>
				</div>
    		
				<?php
					//Zjistit, zda se již na tomto počítači někdo nedávno přihlašoval
					if (isset($_COOKIE['lastChangelog']))
					{
						//Podmínka splněna --> nechat zobrazený přihlašovací formulář
						echo "<div id='prihlaseni' style='display:block'>";
					}
					else
					{
						//Podmínka nesplněna --> skrýt přihlašovací formulář
						echo "<div id='prihlaseni' style='display:none'>";
					}
				?>
					<h2>Log in</h2>
					<div class="udaje">
						<input id='login_name' type='text' name='name_input' maxlength=15 placeholder='Username' class='text'>
						<br>
						<input id='login_pass' type='password' name='pass_input' maxlength=31 placeholder='Password' class='text'>
						<br>
						<div class="checkbox">
							<input type="checkbox" id="login_keep" name='stay_logged'/>
							<label for="login_keep">Stay logged in</label>
						</div>
						<br>
						<button id='login_submit' onclick='login()' class='button' class='confirm button'>Log in</button>
					</div>
					<span class='recoverPass'><a href="javascript:showPasswordRecovery()">Have you forgotten your password?</a></span>
					<br>
					<span class='toggleForms'>Don't have an account yet? <a href="javascript:showRegister()">Register</a>.</span>
					<div id='loginErrors'>
					</div>
				</div>

				<div id="obnoveniHesla" style="display: none;">
					<span>Enter your e-mail adress. If an account with this adress exists, we will send an e-mail with instructions for password recovery.</span>
					<div>
						<input class="text" id='passRecovery_input' type=text name="email" maxlength=255 required=true />
						<button id='passRecovery_submit' onclick="recoverPassword()" class="button">Send</button> 
					</div>
					<span>Don't you remember, which e-mail adress you entered when you were creating an account or you haven't entered any? Contact us at <i style="font-style: italic;">poznavacky@email.com</i> and we will help you with recovery.</span>
					<br>
					<button class="button"onclick="showLogin()">Back</button>
					<div id='passwordRecoveryErrors'>
					</div>
				</div>
			</main>
		</div>
		<footer id="cookiesAlert">
			<div>This website is using cookies. By using the website you agree with saving cookie files on your device.</div>
			<div id="cookiesAlertCloser" onclick="hideCookies()">x</div>
		</footer>
	</body>
</html>
