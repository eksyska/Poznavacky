<?php 
    $redirectIn = false;
    $redirectOut = true;
    require 'verification.php';    //Obsahuje session_start();

    $userdata = $_SESSION['user'];
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width" />
		<link rel="stylesheet" type="text/css" href="css.css">
		<script type="text/javascript" src="accountSettings.js"></script>
		<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
		<link rel="icon" href="favicon.ico">
		<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="/icon-32x32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="/icon-16x16.png">
		<link rel="manifest" href="/manifest.json">
		<link rel="mask-icon" href="/safari-pinned-tab.svg" color="#ffc835">
		<meta name="theme-color" content="#ffffff">
		<title>Account settings</title>
		<style>
			#changeNameInput, #changePasswordInput1, #changePasswordInput2, #changePasswordInput3, #changeEmailInput1, #changeEmailInput2, #deleteAccountInput1, #deleteAccountInput2 {
    			display: none;
				}
		</style>
	</head>
	<body>
	<div class="container">
		<header>
            <h1>Account settings</h1>
        </header>
        <main class="basic_main">
			<table id="static_info">
				<tr>
					<td class='table_left'>ID</td>
					<td class='table_right'><?php echo $userdata['id']; ?></td>
					<td class='table_action'><!--<button disabled class="buttonDisabled">Nelze změnit</button>--></td>
				</tr>	
				<tr>
					<td class='table_left'>Username</td>
					<td class='table_right' id="username"><?php echo $userdata['name']; ?></td>
					<td class='table_action'>
						<button class="button" id="changeNameButton" onclick="changeName()">Request a change</button>
						<div id="changeNameInput">
							<input class="text" id="changeNameInputField" type=text placeholder="New username" maxlength=15 />
							<button class="button" id="changeNameConfirm" onclick="confirmNameChange()">Confirm</button>
						</div>
					</td>
				</tr>
				<tr>
					<td class='table_left'>Password</td>
					<td class='table_right'>[Hidden]</td>
					<td class='table_action'>
						<button class="button" id="changePasswordButton" onclick="changePassword()">Change</button>
						<div id="changePasswordInput1">
							<input class="text" id="changePasswordInputFieldOld" type=password placeholder="Old password" maxlength=31 />
							<button class="button" id="changePasswordNext1" onclick="changePasswordVerify()">Next</button>
						</div>
						<div id="changePasswordInput2">
							<input class="text" id="changePasswordInputFieldNew" type=password placeholder="New password" maxlength=31 />
							<button class="button" id="changePasswordNext2" onclick="changePasswordStage3()">Next</button>
							<button class="button" id="changePasswordBack2" onclick="changePassword()">Back</button>
						</div>
						<div id="changePasswordInput3">
							<input class="text" id="changePasswordInputFieldReNew" type=password placeholder="New password again" maxlength=31 />
							<button class="button" id="changePasswordConfirm" onclick="confirmPasswordChange()">Confirm</button>
							<button class="button" id="changePasswordBack3" onclick="changePasswordStage2()">Back</button>
						</div>
					</td>
				</tr>
				<tr>
					<td class='table_left'>E-mail</td>
					<td class='table_right' id="emailAddress"><?php echo $userdata['email']; ?></td>
					<td class='table_action'>
						<button class="button" id="changeEmailButton" onclick="changeEmail()">Change</button>
						<div id="changeEmailInput1">
							<input class="text" id="changeEmailPasswordInputField" type=password placeholder="Password" maxlength=31 />
							<button class="button" id="changeEmailNext" onclick="changeEmailVerify()">Next</button>
						</div>
						<div id="changeEmailInput2">
							<input class="text" id="changeEmailInputField" type=text placeholder="New e-mail" maxlength=255 />
							<button class="button" id="changeEmailConfirm" onclick="confirmEmailChange()">Confirm</button>
							<button class="button" id="changeEmailBack" onclick="changeEmail()">Back</button>
						</div>
					</td>
				</tr>
				<tr>
					<td class='table_left' title="Add pictures for increase">Added pictures</td>
					<td class='table_right'><?php echo $userdata['addedPics']; ?></td>
					<!--<td class='table_action'>Pro zvýšení přidávejte obrázky</td>-->
					<td class='table_action'></td>
				</tr>
				<tr>
					<td class='table_left' title="Test yourself for increase">Recognised organisms</td>
					<td class='table_right'><?php echo $userdata['guessedPics']; ?></td>
					<!--<td class='table_action'>Pro zvýšení se nechejte testovat</td>-->
					<td class='table_action'></td>
				</tr>
				<tr>
					<td class='table_left' title="You get karma for actions leading to application improvement">Karma</td>
					<td class='table_right'><?php echo $userdata['karma']; ?></td>
					<!--<td class='table_action'>Karmu získáte za činnost vedoucí ke zlepšení služby</td>-->
					<td class='table_action'></td>
				</tr>
				<tr id="tr_end">
					<td class='table_left'>Status</td>
					<td class='table_right'><?php echo $userdata['status']; ?></td>
					<!--<td class='table_action'>Zažádejte o status moderátora na poznavacky@email.com</td>-->
					<td class='table_action'></td>
				</tr>
			</table>
			
			<button class="button" id="deleteAccountButton" onclick="deleteAccount()">Delete account</button>
			<div id="deleteAccountInput1">
				<input class="text" id="deleteAccountInputField" type=password placeholder="Password" maxlength=31 />
				<button class="button" id="deleteAccountConfirm" onclick="deleteAccountVerify()">Next</button>
			</div>
			<div id="deleteAccountInput2">
				<span>This is a one-way action. Do you really want to remove your account for good?</span><br>
				<button class="button" id="deleteAccountFinalConfirm" onclick="deleteAccountFinal()">Yes, delete account</button>
				<button class="button" id="deleteAccountFinalCancel" onclick="deleteAccountCancel()">No, cancel account deletion</button>
			</div>
			<br>
			
			<a href="list.php"><button class="button">Back</button></a>
		</main>
	</div>
	<footer>
		<div id="help" class="footerOption"><a target='_blank' href="https://github.com/eksyska/Poznavacky/wiki/Help">Help</a></div>
		<div id="issues" class="footerOption" onclick="showLogin()"><a target='_blank' href="https://github.com/HonzaSTECH/Poznavacky/issues/new/choose">Have you found a problem?</a></div>
		<div class="footerOption"><a target='_blank' href='https://github.com/HonzaSTECH/Poznavacky/blob/master/TERMS_OF_SERVICE.md'>Terms of use</a></div>
		<div id="about" class="footerOption">&copy Štěchy and Eksyska, 2019</div>
        <script>
            function showLogin()
            {
             	alert("Account login for bug reports:\nName: gjvj\nPassword: poznavacky71");
            }
        </script>
     </footer>
	</body>
</html>