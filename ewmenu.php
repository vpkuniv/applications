<!-- Begin Main Menu -->
<?php
if (@MS_MENU_HORIZONTAL) {
	define("EW_MENUBAR_ID", "ewHorizMenu", FALSE);
	define("EW_MENUBAR_BRAND", "", FALSE);
	define("EW_MENUBAR_BRAND_HYPERLINK", "", FALSE);
	define("EW_MENUBAR_CLASSNAME", "navbar", FALSE);
	define("EW_MENUBAR_INNER_CLASSNAME", "navbar-inner", FALSE);
	define("EW_MENU_CLASSNAME", "nav", FALSE);
	define("EW_SUBMENU_CLASSNAME", "dropdown-menu", FALSE);
	define("EW_SUBMENU_DROPDOWN_IMAGE", " <b class=\"caret\"></b>", FALSE);

	//define("EW_MENU_DIVIDER_CLASSNAME", "divider-vertical", FALSE);
	define("EW_MENU_DIVIDER_CLASSNAME", "divider", FALSE);
	define("EW_MENU_ITEM_CLASSNAME", "dropdown", FALSE);
	define("EW_SUBMENU_ITEM_CLASSNAME", "dropdown-submenu", FALSE);
	define("EW_MENU_ACTIVE_ITEM_CLASS", "active", FALSE);
	define("EW_SUBMENU_ACTIVE_ITEM_CLASS", "disabled", FALSE);
	define("EW_MENU_ROOT_GROUP_TITLE_AS_SUBMENU", TRUE, FALSE);
} else {

	// Menu
	define("EW_MENUBAR_ID", "RootMenu", FALSE);
	define("EW_MENUBAR_BRAND", "", FALSE);
	define("EW_MENUBAR_BRAND_HYPERLINK", "", FALSE);
	define("EW_MENUBAR_CLASSNAME", "", FALSE);
	define("EW_MENUBAR_INNER_CLASSNAME", "", FALSE);

	//define("EW_MENU_CLASSNAME", "nav nav-list", FALSE);
	define("EW_MENU_CLASSNAME", "dropdown-menu", FALSE);
	define("EW_SUBMENU_CLASSNAME", "dropdown-menu", FALSE);
	define("EW_SUBMENU_DROPDOWN_IMAGE", "", FALSE);
	define("EW_MENU_DIVIDER_CLASSNAME", "divider", FALSE);
	define("EW_MENU_ITEM_CLASSNAME", "dropdown-submenu", FALSE);
	define("EW_SUBMENU_ITEM_CLASSNAME", "dropdown-submenu", FALSE);
	define("EW_MENU_ACTIVE_ITEM_CLASS", "disabled", FALSE);
	define("EW_SUBMENU_ACTIVE_ITEM_CLASS", "disabled", FALSE);
	define("EW_MENU_ROOT_GROUP_TITLE_AS_SUBMENU", FALSE, FALSE);
}
?>
<div class="ewMenu">
<?php $RootMenu = new cMenu(EW_MENUBAR_ID) ?>
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
</div>
<!-- End Main Menu -->
