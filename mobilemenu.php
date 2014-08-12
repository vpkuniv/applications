<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "ewmysql10.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "userfn10.php" ?>
<?php
	ew_Header(TRUE);
	$conn = ew_Connect();
	$Language = new cLanguage();

	// Security
	$Security = new cAdvancedSecurity();
	if (!$Security->IsLoggedIn()) $Security->AutoLogin();
	$Security->LoadUserLevel(); // Load User Level
?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $Language->Phrase("MobileMenu") ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="<?php echo ew_jQueryFile("jquery.mobile-%v.min.css") ?>">
<link rel="stylesheet" type="text/css" href="<?php echo EW_PROJECT_STYLESHEET_FILENAME ?>">
<link rel="stylesheet" type="text/css" href="phpcss/ewmobile.css">
<script type="text/javascript" src="<?php echo ew_jQueryFile("jquery-%v.min.js") ?>"></script>
<script type="text/javascript">

	//$(document).bind("mobileinit", function() {
	//	jQuery.mobile.ajaxEnabled = false;
	//	jQuery.mobile.ignoreContentEnabled = true;
	//});

</script>
<script type="text/javascript" src="<?php echo ew_jQueryFile("jquery.mobile-%v.min.js") ?>"></script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="generator" content="PHPMaker v10.0.4">
</head>
<body>
<div data-role="page">
	<div data-role="header">
		<h1><?php echo $Language->ProjectPhrase("BodyTitle") ?></h1>
	</div>
	<div data-role="content">
<?php $RootMenu = new cMenu("RootMenu", TRUE); ?>
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(40, $Language->MenuPhrase("40", "MenuText"), "", -1, "", IsLoggedIn(), FALSE, TRUE);
$RootMenu->AddMenuItem(16, $Language->MenuPhrase("16", "MenuText"), "Accepted_Reportreport.php", 40, "", AllowListMenu('{F8E6AD82-57C4-4BEE-8975-8D386184467A}Accepted Report'), FALSE);
$RootMenu->AddMenuItem(18, $Language->MenuPhrase("18", "MenuText"), "Rejected_Reportreport.php", 40, "", AllowListMenu('{F8E6AD82-57C4-4BEE-8975-8D386184467A}Rejected Report'), FALSE);
$RootMenu->AddMenuItem(13, $Language->MenuPhrase("13", "MenuText"), "scholarshipslist.php", -1, "", AllowListMenu('{F8E6AD82-57C4-4BEE-8975-8D386184467A}scholarships'), FALSE);
$RootMenu->AddMenuItem(44, $Language->MenuPhrase("44", "MenuText"), "gpa_equivalencylist.php", -1, "", AllowListMenu('{F8E6AD82-57C4-4BEE-8975-8D386184467A}gpa equivalency'), FALSE);
$RootMenu->AddMenuItem(1, $Language->MenuPhrase("1", "MenuText"), "academicmissionslist.php", -1, "", AllowListMenu('{F8E6AD82-57C4-4BEE-8975-8D386184467A}academicmissions'), TRUE);
$RootMenu->AddMenuItem(2, $Language->MenuPhrase("2", "MenuText"), "facultyapplicationlist.php", -1, "", AllowListMenu('{F8E6AD82-57C4-4BEE-8975-8D386184467A}facultyapplication'), TRUE);
$RootMenu->AddMenuItem(5, $Language->MenuPhrase("5", "MenuText"), "languageinstructorslist.php", -1, "", AllowListMenu('{F8E6AD82-57C4-4BEE-8975-8D386184467A}languageinstructors'), TRUE);
$RootMenu->AddMenuItem(12, $Language->MenuPhrase("12", "MenuText"), "", -1, "", IsLoggedIn(), TRUE, TRUE);
$RootMenu->AddMenuItem(11, $Language->MenuPhrase("11", "MenuText"), "countrieslist.php", 12, "", AllowListMenu('{F8E6AD82-57C4-4BEE-8975-8D386184467A}countries'), FALSE);
$RootMenu->AddMenuItem(47, $Language->MenuPhrase("47", "MenuText"), "collegeslist.php", 12, "", AllowListMenu('{F8E6AD82-57C4-4BEE-8975-8D386184467A}colleges'), FALSE);
$RootMenu->AddMenuItem(7, $Language->MenuPhrase("7", "MenuText"), "departmentslist.php", 12, "", AllowListMenu('{F8E6AD82-57C4-4BEE-8975-8D386184467A}departments'), FALSE);
$RootMenu->AddMenuItem(8, $Language->MenuPhrase("8", "MenuText"), "gpa_listlist.php", 12, "", AllowListMenu('{F8E6AD82-57C4-4BEE-8975-8D386184467A}gpa_list'), FALSE);
$RootMenu->AddMenuItem(4, $Language->MenuPhrase("4", "MenuText"), "userslist.php", 12, "", AllowListMenu('{F8E6AD82-57C4-4BEE-8975-8D386184467A}users'), FALSE);
$RootMenu->AddMenuItem(45, $Language->MenuPhrase("45", "MenuText"), "sectorslist.php", 12, "", AllowListMenu('{F8E6AD82-57C4-4BEE-8975-8D386184467A}sectors'), FALSE);
$RootMenu->AddMenuItem(46, $Language->MenuPhrase("46", "MenuText"), "job_titleslist.php", 12, "", AllowListMenu('{F8E6AD82-57C4-4BEE-8975-8D386184467A}job titles'), FALSE);
$RootMenu->AddMenuItem(10, $Language->MenuPhrase("10", "MenuText"), "auditlist.php", -1, "", AllowListMenu('{F8E6AD82-57C4-4BEE-8975-8D386184467A}audit'), FALSE);
$RootMenu->AddMenuItem(-2, $Language->Phrase("ChangePwd"), "changepwd.php", -1, "", IsLoggedIn() && !IsSysAdmin());
$RootMenu->AddMenuItem(-1, $Language->Phrase("Logout"), "logout.php", -1, "", IsLoggedIn());
$RootMenu->AddMenuItem(-1, $Language->Phrase("Login"), "login.php", -1, "", !IsLoggedIn() && substr(@$_SERVER["URL"], -1 * strlen("login.php")) <> "login.php");
$RootMenu->Render();
?>
	</div><!-- /content -->
</div><!-- /page -->
</body>
</html>
<?php

	 // Close connection
	$conn->Close();
?>
