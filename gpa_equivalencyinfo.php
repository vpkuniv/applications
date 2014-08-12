<?php

// Global variable for table object
$gpa_equivalency = NULL;

//
// Table class for gpa equivalency
//
class cgpa_equivalency extends cTable {
	var $ID;
	var $Name;
	var $Country;
	var $Civil_ID;
	var $Passport_No2E;
	var $Sector;
	var $Job_Title;
	var $Program;
	var $College;
	var $Department;
	var $Bachelors_Title;
	var $Bachelor_University;
	var $Bachelors_Major;
	var $Bachelors_GPA;
	var $Bachelors_MGPA;
	var $Other_Bachelors_Title;
	var $Other_Bachelors_University;
	var $Other_Bachelors_Major;
	var $Other_Bachelors_GPA;
	var $Other_Bachelors_MGPA;
	var $Masters_Degree_Title;
	var $Master_University;
	var $Masters_Degree_Major;
	var $Masters_GPA;
	var $Other_Masters_Degree_Title;
	var $Other_Masters_University;
	var $Other_Masters_Major;
	var $Other_Masters_GPA;
	var $PhD_Title;
	var $Phd_University;
	var $PhD_Major;
	var $Phd_Degree_Equivalency;
	var $Committee_Meeting;
	var $Committee_Meeting_Number;
	var $Committee_Date;
	var $Notes;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'gpa_equivalency';
		$this->TableName = 'gpa equivalency';
		$this->TableType = 'TABLE';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->DetailAdd = FALSE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->DetailView = FALSE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// ID
		$this->ID = new cField('gpa_equivalency', 'gpa equivalency', 'x_ID', 'ID', '`ID`', '`ID`', 3, -1, FALSE, '`ID`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->ID->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['ID'] = &$this->ID;

		// Name
		$this->Name = new cField('gpa_equivalency', 'gpa equivalency', 'x_Name', 'Name', '`Name`', '`Name`', 200, -1, FALSE, '`Name`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Name'] = &$this->Name;

		// Country
		$this->Country = new cField('gpa_equivalency', 'gpa equivalency', 'x_Country', 'Country', '`Country`', '`Country`', 200, -1, FALSE, '`Country`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->Country->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['Country'] = &$this->Country;

		// Civil ID
		$this->Civil_ID = new cField('gpa_equivalency', 'gpa equivalency', 'x_Civil_ID', 'Civil ID', '`Civil ID`', '`Civil ID`', 200, -1, FALSE, '`Civil ID`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Civil ID'] = &$this->Civil_ID;

		// Passport No.
		$this->Passport_No2E = new cField('gpa_equivalency', 'gpa equivalency', 'x_Passport_No2E', 'Passport No.', '`Passport No.`', '`Passport No.`', 200, -1, FALSE, '`Passport No.`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Passport No.'] = &$this->Passport_No2E;

		// Sector
		$this->Sector = new cField('gpa_equivalency', 'gpa equivalency', 'x_Sector', 'Sector', '`Sector`', '`Sector`', 200, -1, FALSE, '`Sector`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->Sector->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['Sector'] = &$this->Sector;

		// Job Title
		$this->Job_Title = new cField('gpa_equivalency', 'gpa equivalency', 'x_Job_Title', 'Job Title', '`Job Title`', '`Job Title`', 200, -1, FALSE, '`Job Title`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->Job_Title->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['Job Title'] = &$this->Job_Title;

		// Program
		$this->Program = new cField('gpa_equivalency', 'gpa equivalency', 'x_Program', 'Program', '`Program`', '`Program`', 200, -1, FALSE, '`Program`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Program'] = &$this->Program;

		// College
		$this->College = new cField('gpa_equivalency', 'gpa equivalency', 'x_College', 'College', '`College`', '`College`', 200, -1, FALSE, '`College`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->College->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['College'] = &$this->College;

		// Department
		$this->Department = new cField('gpa_equivalency', 'gpa equivalency', 'x_Department', 'Department', '`Department`', '`Department`', 200, -1, FALSE, '`Department`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Department'] = &$this->Department;

		// Bachelors Title
		$this->Bachelors_Title = new cField('gpa_equivalency', 'gpa equivalency', 'x_Bachelors_Title', 'Bachelors Title', '`Bachelors Title`', '`Bachelors Title`', 200, -1, FALSE, '`Bachelors Title`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Bachelors Title'] = &$this->Bachelors_Title;

		// Bachelor University
		$this->Bachelor_University = new cField('gpa_equivalency', 'gpa equivalency', 'x_Bachelor_University', 'Bachelor University', '`Bachelor University`', '`Bachelor University`', 200, -1, FALSE, '`Bachelor University`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Bachelor University'] = &$this->Bachelor_University;

		// Bachelors Major
		$this->Bachelors_Major = new cField('gpa_equivalency', 'gpa equivalency', 'x_Bachelors_Major', 'Bachelors Major', '`Bachelors Major`', '`Bachelors Major`', 200, -1, FALSE, '`Bachelors Major`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Bachelors Major'] = &$this->Bachelors_Major;

		// Bachelors GPA
		$this->Bachelors_GPA = new cField('gpa_equivalency', 'gpa equivalency', 'x_Bachelors_GPA', 'Bachelors GPA', '`Bachelors GPA`', '`Bachelors GPA`', 200, -1, FALSE, '`EV__Bachelors_GPA`', TRUE, FALSE, TRUE, 'FORMATTED TEXT');
		$this->fields['Bachelors GPA'] = &$this->Bachelors_GPA;

		// Bachelors MGPA
		$this->Bachelors_MGPA = new cField('gpa_equivalency', 'gpa equivalency', 'x_Bachelors_MGPA', 'Bachelors MGPA', '`Bachelors MGPA`', '`Bachelors MGPA`', 200, -1, FALSE, '`EV__Bachelors_MGPA`', TRUE, FALSE, TRUE, 'FORMATTED TEXT');
		$this->fields['Bachelors MGPA'] = &$this->Bachelors_MGPA;

		// Other Bachelors Title
		$this->Other_Bachelors_Title = new cField('gpa_equivalency', 'gpa equivalency', 'x_Other_Bachelors_Title', 'Other Bachelors Title', '`Other Bachelors Title`', '`Other Bachelors Title`', 200, -1, FALSE, '`Other Bachelors Title`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Other Bachelors Title'] = &$this->Other_Bachelors_Title;

		// Other Bachelors University
		$this->Other_Bachelors_University = new cField('gpa_equivalency', 'gpa equivalency', 'x_Other_Bachelors_University', 'Other Bachelors University', '`Other Bachelors University`', '`Other Bachelors University`', 200, -1, FALSE, '`Other Bachelors University`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Other Bachelors University'] = &$this->Other_Bachelors_University;

		// Other Bachelors Major
		$this->Other_Bachelors_Major = new cField('gpa_equivalency', 'gpa equivalency', 'x_Other_Bachelors_Major', 'Other Bachelors Major', '`Other Bachelors Major`', '`Other Bachelors Major`', 200, -1, FALSE, '`Other Bachelors Major`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Other Bachelors Major'] = &$this->Other_Bachelors_Major;

		// Other Bachelors GPA
		$this->Other_Bachelors_GPA = new cField('gpa_equivalency', 'gpa equivalency', 'x_Other_Bachelors_GPA', 'Other Bachelors GPA', '`Other Bachelors GPA`', '`Other Bachelors GPA`', 200, -1, FALSE, '`EV__Other_Bachelors_GPA`', TRUE, FALSE, TRUE, 'FORMATTED TEXT');
		$this->Other_Bachelors_GPA->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['Other Bachelors GPA'] = &$this->Other_Bachelors_GPA;

		// Other Bachelors MGPA
		$this->Other_Bachelors_MGPA = new cField('gpa_equivalency', 'gpa equivalency', 'x_Other_Bachelors_MGPA', 'Other Bachelors MGPA', '`Other Bachelors MGPA`', '`Other Bachelors MGPA`', 200, -1, FALSE, '`EV__Other_Bachelors_MGPA`', TRUE, FALSE, TRUE, 'FORMATTED TEXT');
		$this->fields['Other Bachelors MGPA'] = &$this->Other_Bachelors_MGPA;

		// Masters Degree Title
		$this->Masters_Degree_Title = new cField('gpa_equivalency', 'gpa equivalency', 'x_Masters_Degree_Title', 'Masters Degree Title', '`Masters Degree Title`', '`Masters Degree Title`', 200, -1, FALSE, '`Masters Degree Title`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Masters Degree Title'] = &$this->Masters_Degree_Title;

		// Master University
		$this->Master_University = new cField('gpa_equivalency', 'gpa equivalency', 'x_Master_University', 'Master University', '`Master University`', '`Master University`', 200, -1, FALSE, '`Master University`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Master University'] = &$this->Master_University;

		// Masters Degree Major
		$this->Masters_Degree_Major = new cField('gpa_equivalency', 'gpa equivalency', 'x_Masters_Degree_Major', 'Masters Degree Major', '`Masters Degree Major`', '`Masters Degree Major`', 200, -1, FALSE, '`Masters Degree Major`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Masters Degree Major'] = &$this->Masters_Degree_Major;

		// Masters GPA
		$this->Masters_GPA = new cField('gpa_equivalency', 'gpa equivalency', 'x_Masters_GPA', 'Masters GPA', '`Masters GPA`', '`Masters GPA`', 200, -1, FALSE, '`EV__Masters_GPA`', TRUE, FALSE, TRUE, 'FORMATTED TEXT');
		$this->fields['Masters GPA'] = &$this->Masters_GPA;

		// Other Masters Degree Title
		$this->Other_Masters_Degree_Title = new cField('gpa_equivalency', 'gpa equivalency', 'x_Other_Masters_Degree_Title', 'Other Masters Degree Title', '`Other Masters Degree Title`', '`Other Masters Degree Title`', 200, -1, FALSE, '`Other Masters Degree Title`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Other Masters Degree Title'] = &$this->Other_Masters_Degree_Title;

		// Other Masters University
		$this->Other_Masters_University = new cField('gpa_equivalency', 'gpa equivalency', 'x_Other_Masters_University', 'Other Masters University', '`Other Masters University`', '`Other Masters University`', 200, -1, FALSE, '`Other Masters University`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Other Masters University'] = &$this->Other_Masters_University;

		// Other Masters Major
		$this->Other_Masters_Major = new cField('gpa_equivalency', 'gpa equivalency', 'x_Other_Masters_Major', 'Other Masters Major', '`Other Masters Major`', '`Other Masters Major`', 200, -1, FALSE, '`Other Masters Major`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Other Masters Major'] = &$this->Other_Masters_Major;

		// Other Masters GPA
		$this->Other_Masters_GPA = new cField('gpa_equivalency', 'gpa equivalency', 'x_Other_Masters_GPA', 'Other Masters GPA', '`Other Masters GPA`', '`Other Masters GPA`', 200, -1, FALSE, '`EV__Other_Masters_GPA`', TRUE, FALSE, TRUE, 'FORMATTED TEXT');
		$this->fields['Other Masters GPA'] = &$this->Other_Masters_GPA;

		// PhD Title
		$this->PhD_Title = new cField('gpa_equivalency', 'gpa equivalency', 'x_PhD_Title', 'PhD Title', '`PhD Title`', '`PhD Title`', 200, -1, FALSE, '`PhD Title`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['PhD Title'] = &$this->PhD_Title;

		// Phd University
		$this->Phd_University = new cField('gpa_equivalency', 'gpa equivalency', 'x_Phd_University', 'Phd University', '`Phd University`', '`Phd University`', 200, -1, FALSE, '`Phd University`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Phd University'] = &$this->Phd_University;

		// PhD Major
		$this->PhD_Major = new cField('gpa_equivalency', 'gpa equivalency', 'x_PhD_Major', 'PhD Major', '`PhD Major`', '`PhD Major`', 200, -1, FALSE, '`PhD Major`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['PhD Major'] = &$this->PhD_Major;

		// Phd Degree Equivalency
		$this->Phd_Degree_Equivalency = new cField('gpa_equivalency', 'gpa equivalency', 'x_Phd_Degree_Equivalency', 'Phd Degree Equivalency', '`Phd Degree Equivalency`', '`Phd Degree Equivalency`', 200, -1, FALSE, '`Phd Degree Equivalency`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Phd Degree Equivalency'] = &$this->Phd_Degree_Equivalency;

		// Committee Meeting
		$this->Committee_Meeting = new cField('gpa_equivalency', 'gpa equivalency', 'x_Committee_Meeting', 'Committee Meeting', '`Committee Meeting`', '`Committee Meeting`', 200, -1, FALSE, '`Committee Meeting`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Committee Meeting'] = &$this->Committee_Meeting;

		// Committee Meeting Number
		$this->Committee_Meeting_Number = new cField('gpa_equivalency', 'gpa equivalency', 'x_Committee_Meeting_Number', 'Committee Meeting Number', '`Committee Meeting Number`', '`Committee Meeting Number`', 3, -1, FALSE, '`Committee Meeting Number`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Committee Meeting Number'] = &$this->Committee_Meeting_Number;

		// Committee Date
		$this->Committee_Date = new cField('gpa_equivalency', 'gpa equivalency', 'x_Committee_Date', 'Committee Date', '`Committee Date`', 'DATE_FORMAT(`Committee Date`, \'%d/%m/%Y\')', 133, -1, FALSE, '`Committee Date`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->Committee_Date->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['Committee Date'] = &$this->Committee_Date;

		// Notes
		$this->Notes = new cField('gpa_equivalency', 'gpa equivalency', 'x_Notes', 'Notes', '`Notes`', '`Notes`', 201, -1, FALSE, '`Notes`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Notes'] = &$this->Notes;
	}

	// Single column sort
	function UpdateSort(&$ofld) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			$this->setSessionOrderBy($sSortField . " " . $sThisSort); // Save to Session
			$sSortFieldList = ($ofld->FldVirtualExpression <> "") ? $ofld->FldVirtualExpression : $sSortField;
			$this->setSessionOrderByList($sSortFieldList . " " . $sThisSort); // Save to Session
		} else {
			$ofld->setSort("");
		}
	}

	// Session ORDER BY for List page
	function getSessionOrderByList() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_ORDER_BY_LIST];
	}

	function setSessionOrderByList($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_ORDER_BY_LIST] = $v;
	}

	// Table level SQL
	function SqlFrom() { // From
		return "`gpa equivalency`";
	}

	function SqlSelect() { // Select
		return "SELECT * FROM " . $this->SqlFrom();
	}

	function SqlSelectList() { // Select for List page
		return "SELECT * FROM (" .
			"SELECT *, (SELECT `Grade` FROM `gpa_list` `EW_TMP_LOOKUPTABLE` WHERE `EW_TMP_LOOKUPTABLE`.`ID` = `gpa equivalency`.`Bachelors GPA` LIMIT 1) AS `EV__Bachelors_GPA`, (SELECT `Grade` FROM `gpa_list` `EW_TMP_LOOKUPTABLE` WHERE `EW_TMP_LOOKUPTABLE`.`ID` = `gpa equivalency`.`Bachelors MGPA` LIMIT 1) AS `EV__Bachelors_MGPA`, (SELECT `Grade` FROM `gpa_list` `EW_TMP_LOOKUPTABLE` WHERE `EW_TMP_LOOKUPTABLE`.`ID` = `gpa equivalency`.`Other Bachelors GPA` LIMIT 1) AS `EV__Other_Bachelors_GPA`, (SELECT `Grade` FROM `gpa_list` `EW_TMP_LOOKUPTABLE` WHERE `EW_TMP_LOOKUPTABLE`.`ID` = `gpa equivalency`.`Other Bachelors MGPA` LIMIT 1) AS `EV__Other_Bachelors_MGPA`, (SELECT `Grade` FROM `gpa_list` `EW_TMP_LOOKUPTABLE` WHERE `EW_TMP_LOOKUPTABLE`.`ID` = `gpa equivalency`.`Masters GPA` LIMIT 1) AS `EV__Masters_GPA`, (SELECT `Grade` FROM `gpa_list` `EW_TMP_LOOKUPTABLE` WHERE `EW_TMP_LOOKUPTABLE`.`ID` = `gpa equivalency`.`Other Masters GPA` LIMIT 1) AS `EV__Other_Masters_GPA` FROM `gpa equivalency`" .
			") `EW_TMP_TABLE`";
	}

	function SqlWhere() { // Where
		$sWhere = "";
		$this->TableFilter = "";
		ew_AddFilter($sWhere, $this->TableFilter);
		return $sWhere;
	}

	function SqlGroupBy() { // Group By
		return "";
	}

	function SqlHaving() { // Having
		return "";
	}

	function SqlOrderBy() { // Order By
		return "`ID` ASC";
	}

	// Check if Anonymous User is allowed
	function AllowAnonymousUser() {
		switch (@$this->PageID) {
			case "add":
			case "register":
			case "addopt":
				return FALSE;
			case "edit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return FALSE;
			case "delete":
				return FALSE;
			case "view":
				return FALSE;
			case "search":
				return FALSE;
			default:
				return FALSE;
		}
	}

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = EW_USER_ID_ALLOW;
		switch ($id) {
			case "add":
			case "copy":
			case "gridadd":
			case "register":
			case "addopt":
				return (($allow & 1) == 1);
			case "edit":
			case "gridedit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return (($allow & 4) == 4);
			case "delete":
				return (($allow & 2) == 2);
			case "view":
				return (($allow & 32) == 32);
			case "search":
				return (($allow & 64) == 64);
			default:
				return (($allow & 8) == 8);
		}
	}

	// Get SQL
	function GetSQL($where, $orderby) {
		return ew_BuildSelectSql($this->SqlSelect(), $this->SqlWhere(),
			$this->SqlGroupBy(), $this->SqlHaving(), $this->SqlOrderBy(),
			$where, $orderby);
	}

	// Table SQL
	function SQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->SqlSelect(), $this->SqlWhere(),
			$this->SqlGroupBy(), $this->SqlHaving(), $this->SqlOrderBy(),
			$sFilter, $sSort);
	}

	// Table SQL with List page filter
	function SelectSQL() {
		$sFilter = $this->getSessionWhere();
		ew_AddFilter($sFilter, $this->CurrentFilter);
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		if ($this->UseVirtualFields()) {
			$sSort = $this->getSessionOrderByList();
			return ew_BuildSelectSql($this->SqlSelectList(), $this->SqlWhere(), $this->SqlGroupBy(), 
				$this->SqlHaving(), $this->SqlOrderBy(), $sFilter, $sSort);
		} else {
			$sSort = $this->getSessionOrderBy();
			return ew_BuildSelectSql($this->SqlSelect(), $this->SqlWhere(), $this->SqlGroupBy(),
				$this->SqlHaving(), $this->SqlOrderBy(), $sFilter, $sSort);
		}
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = ($this->UseVirtualFields()) ? $this->getSessionOrderByList() : $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->SqlOrderBy(), "", $sSort);
	}

	// Check if virtual fields is used in SQL
	function UseVirtualFields() {
		$sWhere = $this->getSessionWhere();
		$sOrderBy = $this->getSessionOrderByList();
		if ($sWhere <> "")
			$sWhere = " " . str_replace(array("(",")"), array("",""), $sWhere) . " ";
		if ($sOrderBy <> "")
			$sOrderBy = " " . str_replace(array("(",")"), array("",""), $sOrderBy) . " ";
		if ($this->Bachelors_GPA->AdvancedSearch->SearchValue <> "" ||
			$this->Bachelors_GPA->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->Bachelors_GPA->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->Bachelors_GPA->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if ($this->Bachelors_MGPA->AdvancedSearch->SearchValue <> "" ||
			$this->Bachelors_MGPA->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->Bachelors_MGPA->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->Bachelors_MGPA->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if ($this->Other_Bachelors_GPA->AdvancedSearch->SearchValue <> "" ||
			$this->Other_Bachelors_GPA->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->Other_Bachelors_GPA->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->Other_Bachelors_GPA->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if ($this->Other_Bachelors_MGPA->AdvancedSearch->SearchValue <> "" ||
			$this->Other_Bachelors_MGPA->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->Other_Bachelors_MGPA->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->Other_Bachelors_MGPA->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if ($this->Masters_GPA->AdvancedSearch->SearchValue <> "" ||
			$this->Masters_GPA->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->Masters_GPA->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->Masters_GPA->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if ($this->Other_Masters_GPA->AdvancedSearch->SearchValue <> "" ||
			$this->Other_Masters_GPA->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->Other_Masters_GPA->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->Other_Masters_GPA->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		return FALSE;
	}

	// Try to get record count
	function TryGetRecordCount($sSql) {
		global $conn;
		$cnt = -1;
		if ($this->TableType == 'TABLE' || $this->TableType == 'VIEW') {
			$sSql = "SELECT COUNT(*) FROM" . substr($sSql, 13);
			$sOrderBy = $this->GetOrderBy();
			if (substr($sSql, strlen($sOrderBy) * -1) == $sOrderBy)
				$sSql = substr($sSql, 0, strlen($sSql) - strlen($sOrderBy)); // Remove ORDER BY clause
		} else {
			$sSql = "SELECT COUNT(*) FROM (" . $sSql . ") EW_COUNT_TABLE";
		}
		if ($rs = $conn->Execute($sSql)) {
			if (!$rs->EOF && $rs->FieldCount() > 0) {
				$cnt = $rs->fields[0];
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Get record count based on filter (for detail record count in master table pages)
	function LoadRecordCount($sFilter) {
		$origFilter = $this->CurrentFilter;
		$this->CurrentFilter = $sFilter;
		$this->Recordset_Selecting($this->CurrentFilter);

		//$sSql = $this->SQL();
		$sSql = $this->GetSQL($this->CurrentFilter, "");
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $this->LoadRs($this->CurrentFilter)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Get record count (for current List page)
	function SelectRecordCount() {
		global $conn;
		$origFilter = $this->CurrentFilter;
		$this->Recordset_Selecting($this->CurrentFilter);
		$sSql = $this->SelectSQL();
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $conn->Execute($sSql)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Update Table
	var $UpdateTable = "`gpa equivalency`";

	// INSERT statement
	function InsertSQL(&$rs) {
		global $conn;
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]))
				continue;
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= ew_QuotedValue($value, $this->fields[$name]->FldDataType) . ",";
		}
		while (substr($names, -1) == ",")
			$names = substr($names, 0, -1);
		while (substr($values, -1) == ",")
			$values = substr($values, 0, -1);
		return "INSERT INTO " . $this->UpdateTable . " ($names) VALUES ($values)";
	}

	// Insert
	function Insert(&$rs) {
		global $conn;
		return $conn->Execute($this->InsertSQL($rs));
	}

	// UPDATE statement
	function UpdateSQL(&$rs, $where = "") {
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]))
				continue;
			$sql .= $this->fields[$name]->FldExpression . "=";
			$sql .= ew_QuotedValue($value, $this->fields[$name]->FldDataType) . ",";
		}
		while (substr($sql, -1) == ",")
			$sql = substr($sql, 0, -1);
		$filter = $this->CurrentFilter;
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " WHERE " . $filter;
		return $sql;
	}

	// Update
	function Update(&$rs, $where = "", $rsold = NULL) {
		global $conn;
		return $conn->Execute($this->UpdateSQL($rs, $where));
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "") {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if ($rs) {
			if (array_key_exists('ID', $rs))
				ew_AddFilter($where, ew_QuotedName('ID') . '=' . ew_QuotedValue($rs['ID'], $this->ID->FldDataType));
		}
		$filter = $this->CurrentFilter;
		ew_AddFilter($filter, $where);
		if ($filter <> "")
			$sql .= $filter;
		else
			$sql .= "0=1"; // Avoid delete
		return $sql;
	}

	// Delete
	function Delete(&$rs, $where = "") {
		global $conn;
		return $conn->Execute($this->DeleteSQL($rs, $where));
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`ID` = @ID@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->ID->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@ID@", ew_AdjustSql($this->ID->CurrentValue), $sKeyFilter); // Replace key value
		return $sKeyFilter;
	}

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;

		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "gpa_equivalencylist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "gpa_equivalencylist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("gpa_equivalencyview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("gpa_equivalencyview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "gpa_equivalencyadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("gpa_equivalencyedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("gpa_equivalencyadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("gpa_equivalencydelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->ID->CurrentValue)) {
			$sUrl .= "ID=" . urlencode($this->ID->CurrentValue);
		} else {
			return "javascript:alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&amp;ordertype=" . $fld->ReverseSort());
			return ew_CurrentPage() . "?" . $sUrlParm;
		} else {
			return "";
		}
	}

	// Get record keys from $_POST/$_GET/$_SESSION
	function GetRecordKeys() {
		global $EW_COMPOSITE_KEY_SEPARATOR;
		$arKeys = array();
		$arKey = array();
		if (isset($_POST["key_m"])) {
			$arKeys = ew_StripSlashes($_POST["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = ew_StripSlashes($_GET["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET)) {
			$arKeys[] = @$_GET["ID"]; // ID

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		foreach ($arKeys as $key) {
			if (!is_numeric($key))
				continue;
			$ar[] = $key;
		}
		return $ar;
	}

	// Get key filter
	function GetKeyFilter() {
		$arKeys = $this->GetRecordKeys();
		$sKeyFilter = "";
		foreach ($arKeys as $key) {
			if ($sKeyFilter <> "") $sKeyFilter .= " OR ";
			$this->ID->CurrentValue = $key;
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($sFilter) {
		global $conn;

		// Set up filter (SQL WHERE clause) and get return SQL
		//$this->CurrentFilter = $sFilter;
		//$sSql = $this->SQL();

		$sSql = $this->GetSQL($sFilter, "");
		$rs = $conn->Execute($sSql);
		return $rs;
	}

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
		$this->ID->setDbValue($rs->fields('ID'));
		$this->Name->setDbValue($rs->fields('Name'));
		$this->Country->setDbValue($rs->fields('Country'));
		$this->Civil_ID->setDbValue($rs->fields('Civil ID'));
		$this->Passport_No2E->setDbValue($rs->fields('Passport No.'));
		$this->Sector->setDbValue($rs->fields('Sector'));
		$this->Job_Title->setDbValue($rs->fields('Job Title'));
		$this->Program->setDbValue($rs->fields('Program'));
		$this->College->setDbValue($rs->fields('College'));
		$this->Department->setDbValue($rs->fields('Department'));
		$this->Bachelors_Title->setDbValue($rs->fields('Bachelors Title'));
		$this->Bachelor_University->setDbValue($rs->fields('Bachelor University'));
		$this->Bachelors_Major->setDbValue($rs->fields('Bachelors Major'));
		$this->Bachelors_GPA->setDbValue($rs->fields('Bachelors GPA'));
		$this->Bachelors_MGPA->setDbValue($rs->fields('Bachelors MGPA'));
		$this->Other_Bachelors_Title->setDbValue($rs->fields('Other Bachelors Title'));
		$this->Other_Bachelors_University->setDbValue($rs->fields('Other Bachelors University'));
		$this->Other_Bachelors_Major->setDbValue($rs->fields('Other Bachelors Major'));
		$this->Other_Bachelors_GPA->setDbValue($rs->fields('Other Bachelors GPA'));
		$this->Other_Bachelors_MGPA->setDbValue($rs->fields('Other Bachelors MGPA'));
		$this->Masters_Degree_Title->setDbValue($rs->fields('Masters Degree Title'));
		$this->Master_University->setDbValue($rs->fields('Master University'));
		$this->Masters_Degree_Major->setDbValue($rs->fields('Masters Degree Major'));
		$this->Masters_GPA->setDbValue($rs->fields('Masters GPA'));
		$this->Other_Masters_Degree_Title->setDbValue($rs->fields('Other Masters Degree Title'));
		$this->Other_Masters_University->setDbValue($rs->fields('Other Masters University'));
		$this->Other_Masters_Major->setDbValue($rs->fields('Other Masters Major'));
		$this->Other_Masters_GPA->setDbValue($rs->fields('Other Masters GPA'));
		$this->PhD_Title->setDbValue($rs->fields('PhD Title'));
		$this->Phd_University->setDbValue($rs->fields('Phd University'));
		$this->PhD_Major->setDbValue($rs->fields('PhD Major'));
		$this->Phd_Degree_Equivalency->setDbValue($rs->fields('Phd Degree Equivalency'));
		$this->Committee_Meeting->setDbValue($rs->fields('Committee Meeting'));
		$this->Committee_Meeting_Number->setDbValue($rs->fields('Committee Meeting Number'));
		$this->Committee_Date->setDbValue($rs->fields('Committee Date'));
		$this->Notes->setDbValue($rs->fields('Notes'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security, $gsLanguage;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// ID
		// Name
		// Country
		// Civil ID
		// Passport No.
		// Sector
		// Job Title
		// Program
		// College
		// Department
		// Bachelors Title
		// Bachelor University
		// Bachelors Major
		// Bachelors GPA
		// Bachelors MGPA
		// Other Bachelors Title
		// Other Bachelors University
		// Other Bachelors Major
		// Other Bachelors GPA
		// Other Bachelors MGPA
		// Masters Degree Title
		// Master University
		// Masters Degree Major
		// Masters GPA
		// Other Masters Degree Title
		// Other Masters University
		// Other Masters Major
		// Other Masters GPA
		// PhD Title
		// Phd University
		// PhD Major
		// Phd Degree Equivalency
		// Committee Meeting
		// Committee Meeting Number
		// Committee Date
		// Notes
		// ID

		$this->ID->ViewValue = $this->ID->CurrentValue;
		$this->ID->ViewCustomAttributes = "";

		// Name
		$this->Name->ViewValue = $this->Name->CurrentValue;
		$this->Name->CellCssStyle .= "text-align: right;";
		$this->Name->ViewCustomAttributes = "";

		// Country
		if (strval($this->Country->CurrentValue) <> "") {
			$sFilterWrk = "`NID`" . ew_SearchString("=", $this->Country->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT `NID`, `Nationality` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `countries`";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->Country, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->Country->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->Country->ViewValue = $this->Country->CurrentValue;
			}
		} else {
			$this->Country->ViewValue = NULL;
		}
		$this->Country->ViewCustomAttributes = "";

		// Civil ID
		$this->Civil_ID->ViewValue = $this->Civil_ID->CurrentValue;
		$this->Civil_ID->ViewCustomAttributes = "";

		// Passport No.
		$this->Passport_No2E->ViewValue = $this->Passport_No2E->CurrentValue;
		$this->Passport_No2E->ViewCustomAttributes = "";

		// Sector
		if (strval($this->Sector->CurrentValue) <> "") {
			$sFilterWrk = "`ID`" . ew_SearchString("=", $this->Sector->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT `ID`, `Sector` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sectors`";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->Sector, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->Sector->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->Sector->ViewValue = $this->Sector->CurrentValue;
			}
		} else {
			$this->Sector->ViewValue = NULL;
		}
		$this->Sector->CellCssStyle .= "text-align: right;";
		$this->Sector->ViewCustomAttributes = "";

		// Job Title
		if (strval($this->Job_Title->CurrentValue) <> "") {
			$sFilterWrk = "`ID`" . ew_SearchString("=", $this->Job_Title->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT `ID`, `Job Title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `job titles`";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->Job_Title, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->Job_Title->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->Job_Title->ViewValue = $this->Job_Title->CurrentValue;
			}
		} else {
			$this->Job_Title->ViewValue = NULL;
		}
		$this->Job_Title->CellCssStyle .= "text-align: right;";
		$this->Job_Title->ViewCustomAttributes = "";

		// Program
		$this->Program->ViewValue = $this->Program->CurrentValue;
		$this->Program->CellCssStyle .= "text-align: right;";
		$this->Program->ViewCustomAttributes = "";

		// College
		if (strval($this->College->CurrentValue) <> "") {
			$sFilterWrk = "`CollegeID`" . ew_SearchString("=", $this->College->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT `CollegeID`, `College Name AR` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `colleges`";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->College, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->College->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->College->ViewValue = $this->College->CurrentValue;
			}
		} else {
			$this->College->ViewValue = NULL;
		}
		$this->College->CellCssStyle .= "text-align: right;";
		$this->College->ViewCustomAttributes = "";

		// Department
		if (strval($this->Department->CurrentValue) <> "") {
			$sFilterWrk = "`DID`" . ew_SearchString("=", $this->Department->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT `DID`, `DepartmentName-AR` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `departments`";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->Department, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->Department->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->Department->ViewValue = $this->Department->CurrentValue;
			}
		} else {
			$this->Department->ViewValue = NULL;
		}
		$this->Department->CellCssStyle .= "text-align: right;";
		$this->Department->ViewCustomAttributes = "";

		// Bachelors Title
		$this->Bachelors_Title->ViewValue = $this->Bachelors_Title->CurrentValue;
		$this->Bachelors_Title->ViewCustomAttributes = "";

		// Bachelor University
		$this->Bachelor_University->ViewValue = $this->Bachelor_University->CurrentValue;
		$this->Bachelor_University->ViewCustomAttributes = "";

		// Bachelors Major
		$this->Bachelors_Major->ViewValue = $this->Bachelors_Major->CurrentValue;
		$this->Bachelors_Major->ViewCustomAttributes = "";

		// Bachelors GPA
		if ($this->Bachelors_GPA->VirtualValue <> "") {
			$this->Bachelors_GPA->ViewValue = $this->Bachelors_GPA->VirtualValue;
		} else {
			$this->Bachelors_GPA->ViewValue = $this->Bachelors_GPA->CurrentValue;
		if (strval($this->Bachelors_GPA->CurrentValue) <> "") {
			$sFilterWrk = "`ID`" . ew_SearchString("=", $this->Bachelors_GPA->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT `ID`, `Grade` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `gpa_list`";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->Bachelors_GPA, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->Bachelors_GPA->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->Bachelors_GPA->ViewValue = $this->Bachelors_GPA->CurrentValue;
			}
		} else {
			$this->Bachelors_GPA->ViewValue = NULL;
		}
		}
		$this->Bachelors_GPA->ViewCustomAttributes = "";

		// Bachelors MGPA
		if ($this->Bachelors_MGPA->VirtualValue <> "") {
			$this->Bachelors_MGPA->ViewValue = $this->Bachelors_MGPA->VirtualValue;
		} else {
			$this->Bachelors_MGPA->ViewValue = $this->Bachelors_MGPA->CurrentValue;
		if (strval($this->Bachelors_MGPA->CurrentValue) <> "") {
			$sFilterWrk = "`ID`" . ew_SearchString("=", $this->Bachelors_MGPA->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT `ID`, `Grade` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `gpa_list`";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->Bachelors_MGPA, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->Bachelors_MGPA->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->Bachelors_MGPA->ViewValue = $this->Bachelors_MGPA->CurrentValue;
			}
		} else {
			$this->Bachelors_MGPA->ViewValue = NULL;
		}
		}
		$this->Bachelors_MGPA->ViewCustomAttributes = "";

		// Other Bachelors Title
		$this->Other_Bachelors_Title->ViewValue = $this->Other_Bachelors_Title->CurrentValue;
		$this->Other_Bachelors_Title->ViewCustomAttributes = "";

		// Other Bachelors University
		$this->Other_Bachelors_University->ViewValue = $this->Other_Bachelors_University->CurrentValue;
		$this->Other_Bachelors_University->ViewCustomAttributes = "";

		// Other Bachelors Major
		$this->Other_Bachelors_Major->ViewValue = $this->Other_Bachelors_Major->CurrentValue;
		$this->Other_Bachelors_Major->ViewCustomAttributes = "";

		// Other Bachelors GPA
		if ($this->Other_Bachelors_GPA->VirtualValue <> "") {
			$this->Other_Bachelors_GPA->ViewValue = $this->Other_Bachelors_GPA->VirtualValue;
		} else {
			$this->Other_Bachelors_GPA->ViewValue = $this->Other_Bachelors_GPA->CurrentValue;
		if (strval($this->Other_Bachelors_GPA->CurrentValue) <> "") {
			$sFilterWrk = "`ID`" . ew_SearchString("=", $this->Other_Bachelors_GPA->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT `ID`, `Grade` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `gpa_list`";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->Other_Bachelors_GPA, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->Other_Bachelors_GPA->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->Other_Bachelors_GPA->ViewValue = $this->Other_Bachelors_GPA->CurrentValue;
			}
		} else {
			$this->Other_Bachelors_GPA->ViewValue = NULL;
		}
		}
		$this->Other_Bachelors_GPA->ViewCustomAttributes = "";

		// Other Bachelors MGPA
		if ($this->Other_Bachelors_MGPA->VirtualValue <> "") {
			$this->Other_Bachelors_MGPA->ViewValue = $this->Other_Bachelors_MGPA->VirtualValue;
		} else {
			$this->Other_Bachelors_MGPA->ViewValue = $this->Other_Bachelors_MGPA->CurrentValue;
		if (strval($this->Other_Bachelors_MGPA->CurrentValue) <> "") {
			$sFilterWrk = "`ID`" . ew_SearchString("=", $this->Other_Bachelors_MGPA->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT `ID`, `Grade` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `gpa_list`";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->Other_Bachelors_MGPA, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->Other_Bachelors_MGPA->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->Other_Bachelors_MGPA->ViewValue = $this->Other_Bachelors_MGPA->CurrentValue;
			}
		} else {
			$this->Other_Bachelors_MGPA->ViewValue = NULL;
		}
		}
		$this->Other_Bachelors_MGPA->ViewCustomAttributes = "";

		// Masters Degree Title
		$this->Masters_Degree_Title->ViewValue = $this->Masters_Degree_Title->CurrentValue;
		$this->Masters_Degree_Title->ViewCustomAttributes = "";

		// Master University
		$this->Master_University->ViewValue = $this->Master_University->CurrentValue;
		$this->Master_University->ViewCustomAttributes = "";

		// Masters Degree Major
		$this->Masters_Degree_Major->ViewValue = $this->Masters_Degree_Major->CurrentValue;
		$this->Masters_Degree_Major->ViewCustomAttributes = "";

		// Masters GPA
		if ($this->Masters_GPA->VirtualValue <> "") {
			$this->Masters_GPA->ViewValue = $this->Masters_GPA->VirtualValue;
		} else {
			$this->Masters_GPA->ViewValue = $this->Masters_GPA->CurrentValue;
		if (strval($this->Masters_GPA->CurrentValue) <> "") {
			$sFilterWrk = "`ID`" . ew_SearchString("=", $this->Masters_GPA->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT `ID`, `Grade` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `gpa_list`";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->Masters_GPA, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->Masters_GPA->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->Masters_GPA->ViewValue = $this->Masters_GPA->CurrentValue;
			}
		} else {
			$this->Masters_GPA->ViewValue = NULL;
		}
		}
		$this->Masters_GPA->ViewCustomAttributes = "";

		// Other Masters Degree Title
		$this->Other_Masters_Degree_Title->ViewValue = $this->Other_Masters_Degree_Title->CurrentValue;
		$this->Other_Masters_Degree_Title->ViewCustomAttributes = "";

		// Other Masters University
		$this->Other_Masters_University->ViewValue = $this->Other_Masters_University->CurrentValue;
		$this->Other_Masters_University->ViewCustomAttributes = "";

		// Other Masters Major
		$this->Other_Masters_Major->ViewValue = $this->Other_Masters_Major->CurrentValue;
		$this->Other_Masters_Major->ViewCustomAttributes = "";

		// Other Masters GPA
		if ($this->Other_Masters_GPA->VirtualValue <> "") {
			$this->Other_Masters_GPA->ViewValue = $this->Other_Masters_GPA->VirtualValue;
		} else {
			$this->Other_Masters_GPA->ViewValue = $this->Other_Masters_GPA->CurrentValue;
		if (strval($this->Other_Masters_GPA->CurrentValue) <> "") {
			$sFilterWrk = "`ID`" . ew_SearchString("=", $this->Other_Masters_GPA->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT `ID`, `Grade` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `gpa_list`";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->Other_Masters_GPA, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->Other_Masters_GPA->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->Other_Masters_GPA->ViewValue = $this->Other_Masters_GPA->CurrentValue;
			}
		} else {
			$this->Other_Masters_GPA->ViewValue = NULL;
		}
		}
		$this->Other_Masters_GPA->ViewCustomAttributes = "";

		// PhD Title
		$this->PhD_Title->ViewValue = $this->PhD_Title->CurrentValue;
		$this->PhD_Title->ViewCustomAttributes = "";

		// Phd University
		$this->Phd_University->ViewValue = $this->Phd_University->CurrentValue;
		$this->Phd_University->ViewCustomAttributes = "";

		// PhD Major
		$this->PhD_Major->ViewValue = $this->PhD_Major->CurrentValue;
		$this->PhD_Major->ViewCustomAttributes = "";

		// Phd Degree Equivalency
		$this->Phd_Degree_Equivalency->ViewValue = $this->Phd_Degree_Equivalency->CurrentValue;
		$this->Phd_Degree_Equivalency->ViewCustomAttributes = "";

		// Committee Meeting
		$this->Committee_Meeting->ViewValue = $this->Committee_Meeting->CurrentValue;
		$this->Committee_Meeting->ViewCustomAttributes = "";

		// Committee Meeting Number
		if (strval($this->Committee_Meeting_Number->CurrentValue) <> "") {
			switch ($this->Committee_Meeting_Number->CurrentValue) {
				case $this->Committee_Meeting_Number->FldTagValue(1):
					$this->Committee_Meeting_Number->ViewValue = $this->Committee_Meeting_Number->FldTagCaption(1) <> "" ? $this->Committee_Meeting_Number->FldTagCaption(1) : $this->Committee_Meeting_Number->CurrentValue;
					break;
				case $this->Committee_Meeting_Number->FldTagValue(2):
					$this->Committee_Meeting_Number->ViewValue = $this->Committee_Meeting_Number->FldTagCaption(2) <> "" ? $this->Committee_Meeting_Number->FldTagCaption(2) : $this->Committee_Meeting_Number->CurrentValue;
					break;
				case $this->Committee_Meeting_Number->FldTagValue(3):
					$this->Committee_Meeting_Number->ViewValue = $this->Committee_Meeting_Number->FldTagCaption(3) <> "" ? $this->Committee_Meeting_Number->FldTagCaption(3) : $this->Committee_Meeting_Number->CurrentValue;
					break;
				case $this->Committee_Meeting_Number->FldTagValue(4):
					$this->Committee_Meeting_Number->ViewValue = $this->Committee_Meeting_Number->FldTagCaption(4) <> "" ? $this->Committee_Meeting_Number->FldTagCaption(4) : $this->Committee_Meeting_Number->CurrentValue;
					break;
				case $this->Committee_Meeting_Number->FldTagValue(5):
					$this->Committee_Meeting_Number->ViewValue = $this->Committee_Meeting_Number->FldTagCaption(5) <> "" ? $this->Committee_Meeting_Number->FldTagCaption(5) : $this->Committee_Meeting_Number->CurrentValue;
					break;
				case $this->Committee_Meeting_Number->FldTagValue(6):
					$this->Committee_Meeting_Number->ViewValue = $this->Committee_Meeting_Number->FldTagCaption(6) <> "" ? $this->Committee_Meeting_Number->FldTagCaption(6) : $this->Committee_Meeting_Number->CurrentValue;
					break;
				case $this->Committee_Meeting_Number->FldTagValue(7):
					$this->Committee_Meeting_Number->ViewValue = $this->Committee_Meeting_Number->FldTagCaption(7) <> "" ? $this->Committee_Meeting_Number->FldTagCaption(7) : $this->Committee_Meeting_Number->CurrentValue;
					break;
				case $this->Committee_Meeting_Number->FldTagValue(8):
					$this->Committee_Meeting_Number->ViewValue = $this->Committee_Meeting_Number->FldTagCaption(8) <> "" ? $this->Committee_Meeting_Number->FldTagCaption(8) : $this->Committee_Meeting_Number->CurrentValue;
					break;
				case $this->Committee_Meeting_Number->FldTagValue(9):
					$this->Committee_Meeting_Number->ViewValue = $this->Committee_Meeting_Number->FldTagCaption(9) <> "" ? $this->Committee_Meeting_Number->FldTagCaption(9) : $this->Committee_Meeting_Number->CurrentValue;
					break;
				case $this->Committee_Meeting_Number->FldTagValue(10):
					$this->Committee_Meeting_Number->ViewValue = $this->Committee_Meeting_Number->FldTagCaption(10) <> "" ? $this->Committee_Meeting_Number->FldTagCaption(10) : $this->Committee_Meeting_Number->CurrentValue;
					break;
				case $this->Committee_Meeting_Number->FldTagValue(11):
					$this->Committee_Meeting_Number->ViewValue = $this->Committee_Meeting_Number->FldTagCaption(11) <> "" ? $this->Committee_Meeting_Number->FldTagCaption(11) : $this->Committee_Meeting_Number->CurrentValue;
					break;
				case $this->Committee_Meeting_Number->FldTagValue(12):
					$this->Committee_Meeting_Number->ViewValue = $this->Committee_Meeting_Number->FldTagCaption(12) <> "" ? $this->Committee_Meeting_Number->FldTagCaption(12) : $this->Committee_Meeting_Number->CurrentValue;
					break;
				case $this->Committee_Meeting_Number->FldTagValue(13):
					$this->Committee_Meeting_Number->ViewValue = $this->Committee_Meeting_Number->FldTagCaption(13) <> "" ? $this->Committee_Meeting_Number->FldTagCaption(13) : $this->Committee_Meeting_Number->CurrentValue;
					break;
				case $this->Committee_Meeting_Number->FldTagValue(14):
					$this->Committee_Meeting_Number->ViewValue = $this->Committee_Meeting_Number->FldTagCaption(14) <> "" ? $this->Committee_Meeting_Number->FldTagCaption(14) : $this->Committee_Meeting_Number->CurrentValue;
					break;
				case $this->Committee_Meeting_Number->FldTagValue(15):
					$this->Committee_Meeting_Number->ViewValue = $this->Committee_Meeting_Number->FldTagCaption(15) <> "" ? $this->Committee_Meeting_Number->FldTagCaption(15) : $this->Committee_Meeting_Number->CurrentValue;
					break;
				default:
					$this->Committee_Meeting_Number->ViewValue = $this->Committee_Meeting_Number->CurrentValue;
			}
		} else {
			$this->Committee_Meeting_Number->ViewValue = NULL;
		}
		$this->Committee_Meeting_Number->ViewCustomAttributes = "";

		// Committee Date
		$this->Committee_Date->ViewValue = $this->Committee_Date->CurrentValue;
		$this->Committee_Date->ViewCustomAttributes = "";

		// Notes
		$this->Notes->ViewValue = $this->Notes->CurrentValue;
		$this->Notes->CellCssStyle .= "text-align: right;";
		$this->Notes->ViewCustomAttributes = "";

		// ID
		$this->ID->LinkCustomAttributes = "";
		$this->ID->HrefValue = "";
		$this->ID->TooltipValue = "";

		// Name
		$this->Name->LinkCustomAttributes = "";
		$this->Name->HrefValue = "";
		$this->Name->TooltipValue = "";

		// Country
		$this->Country->LinkCustomAttributes = "";
		$this->Country->HrefValue = "";
		$this->Country->TooltipValue = "";

		// Civil ID
		$this->Civil_ID->LinkCustomAttributes = "";
		$this->Civil_ID->HrefValue = "";
		$this->Civil_ID->TooltipValue = "";

		// Passport No.
		$this->Passport_No2E->LinkCustomAttributes = "";
		$this->Passport_No2E->HrefValue = "";
		$this->Passport_No2E->TooltipValue = "";

		// Sector
		$this->Sector->LinkCustomAttributes = "";
		$this->Sector->HrefValue = "";
		$this->Sector->TooltipValue = "";

		// Job Title
		$this->Job_Title->LinkCustomAttributes = "";
		$this->Job_Title->HrefValue = "";
		$this->Job_Title->TooltipValue = "";

		// Program
		$this->Program->LinkCustomAttributes = "";
		$this->Program->HrefValue = "";
		$this->Program->TooltipValue = "";

		// College
		$this->College->LinkCustomAttributes = "";
		$this->College->HrefValue = "";
		$this->College->TooltipValue = "";

		// Department
		$this->Department->LinkCustomAttributes = "";
		$this->Department->HrefValue = "";
		$this->Department->TooltipValue = "";

		// Bachelors Title
		$this->Bachelors_Title->LinkCustomAttributes = "";
		$this->Bachelors_Title->HrefValue = "";
		$this->Bachelors_Title->TooltipValue = "";

		// Bachelor University
		$this->Bachelor_University->LinkCustomAttributes = "";
		$this->Bachelor_University->HrefValue = "";
		$this->Bachelor_University->TooltipValue = "";

		// Bachelors Major
		$this->Bachelors_Major->LinkCustomAttributes = "";
		$this->Bachelors_Major->HrefValue = "";
		$this->Bachelors_Major->TooltipValue = "";

		// Bachelors GPA
		$this->Bachelors_GPA->LinkCustomAttributes = "";
		$this->Bachelors_GPA->HrefValue = "";
		$this->Bachelors_GPA->TooltipValue = "";

		// Bachelors MGPA
		$this->Bachelors_MGPA->LinkCustomAttributes = "";
		$this->Bachelors_MGPA->HrefValue = "";
		$this->Bachelors_MGPA->TooltipValue = "";

		// Other Bachelors Title
		$this->Other_Bachelors_Title->LinkCustomAttributes = "";
		$this->Other_Bachelors_Title->HrefValue = "";
		$this->Other_Bachelors_Title->TooltipValue = "";

		// Other Bachelors University
		$this->Other_Bachelors_University->LinkCustomAttributes = "";
		$this->Other_Bachelors_University->HrefValue = "";
		$this->Other_Bachelors_University->TooltipValue = "";

		// Other Bachelors Major
		$this->Other_Bachelors_Major->LinkCustomAttributes = "";
		$this->Other_Bachelors_Major->HrefValue = "";
		$this->Other_Bachelors_Major->TooltipValue = "";

		// Other Bachelors GPA
		$this->Other_Bachelors_GPA->LinkCustomAttributes = "";
		$this->Other_Bachelors_GPA->HrefValue = "";
		$this->Other_Bachelors_GPA->TooltipValue = "";

		// Other Bachelors MGPA
		$this->Other_Bachelors_MGPA->LinkCustomAttributes = "";
		$this->Other_Bachelors_MGPA->HrefValue = "";
		$this->Other_Bachelors_MGPA->TooltipValue = "";

		// Masters Degree Title
		$this->Masters_Degree_Title->LinkCustomAttributes = "";
		$this->Masters_Degree_Title->HrefValue = "";
		$this->Masters_Degree_Title->TooltipValue = "";

		// Master University
		$this->Master_University->LinkCustomAttributes = "";
		$this->Master_University->HrefValue = "";
		$this->Master_University->TooltipValue = "";

		// Masters Degree Major
		$this->Masters_Degree_Major->LinkCustomAttributes = "";
		$this->Masters_Degree_Major->HrefValue = "";
		$this->Masters_Degree_Major->TooltipValue = "";

		// Masters GPA
		$this->Masters_GPA->LinkCustomAttributes = "";
		$this->Masters_GPA->HrefValue = "";
		$this->Masters_GPA->TooltipValue = "";

		// Other Masters Degree Title
		$this->Other_Masters_Degree_Title->LinkCustomAttributes = "";
		$this->Other_Masters_Degree_Title->HrefValue = "";
		$this->Other_Masters_Degree_Title->TooltipValue = "";

		// Other Masters University
		$this->Other_Masters_University->LinkCustomAttributes = "";
		$this->Other_Masters_University->HrefValue = "";
		$this->Other_Masters_University->TooltipValue = "";

		// Other Masters Major
		$this->Other_Masters_Major->LinkCustomAttributes = "";
		$this->Other_Masters_Major->HrefValue = "";
		$this->Other_Masters_Major->TooltipValue = "";

		// Other Masters GPA
		$this->Other_Masters_GPA->LinkCustomAttributes = "";
		$this->Other_Masters_GPA->HrefValue = "";
		$this->Other_Masters_GPA->TooltipValue = "";

		// PhD Title
		$this->PhD_Title->LinkCustomAttributes = "";
		$this->PhD_Title->HrefValue = "";
		$this->PhD_Title->TooltipValue = "";

		// Phd University
		$this->Phd_University->LinkCustomAttributes = "";
		$this->Phd_University->HrefValue = "";
		$this->Phd_University->TooltipValue = "";

		// PhD Major
		$this->PhD_Major->LinkCustomAttributes = "";
		$this->PhD_Major->HrefValue = "";
		$this->PhD_Major->TooltipValue = "";

		// Phd Degree Equivalency
		$this->Phd_Degree_Equivalency->LinkCustomAttributes = "";
		$this->Phd_Degree_Equivalency->HrefValue = "";
		$this->Phd_Degree_Equivalency->TooltipValue = "";

		// Committee Meeting
		$this->Committee_Meeting->LinkCustomAttributes = "";
		$this->Committee_Meeting->HrefValue = "";
		$this->Committee_Meeting->TooltipValue = "";

		// Committee Meeting Number
		$this->Committee_Meeting_Number->LinkCustomAttributes = "";
		$this->Committee_Meeting_Number->HrefValue = "";
		$this->Committee_Meeting_Number->TooltipValue = "";

		// Committee Date
		$this->Committee_Date->LinkCustomAttributes = "";
		$this->Committee_Date->HrefValue = "";
		$this->Committee_Date->TooltipValue = "";

		// Notes
		$this->Notes->LinkCustomAttributes = "";
		$this->Notes->HrefValue = "";
		$this->Notes->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {
	}

	// Export data in HTML/CSV/Word/Excel/Email/PDF format
	function ExportDocument(&$Doc, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$Doc)
			return;

		// Write header
		$Doc->ExportTableHeader();
		if ($Doc->Horizontal) { // Horizontal format, write header
			$Doc->BeginExportRow();
			if ($ExportPageType == "view") {
				if ($this->ID->Exportable) $Doc->ExportCaption($this->ID);
				if ($this->Name->Exportable) $Doc->ExportCaption($this->Name);
				if ($this->Country->Exportable) $Doc->ExportCaption($this->Country);
				if ($this->Civil_ID->Exportable) $Doc->ExportCaption($this->Civil_ID);
				if ($this->Passport_No2E->Exportable) $Doc->ExportCaption($this->Passport_No2E);
				if ($this->Sector->Exportable) $Doc->ExportCaption($this->Sector);
				if ($this->Job_Title->Exportable) $Doc->ExportCaption($this->Job_Title);
				if ($this->Program->Exportable) $Doc->ExportCaption($this->Program);
				if ($this->College->Exportable) $Doc->ExportCaption($this->College);
				if ($this->Department->Exportable) $Doc->ExportCaption($this->Department);
				if ($this->Bachelors_Title->Exportable) $Doc->ExportCaption($this->Bachelors_Title);
				if ($this->Bachelor_University->Exportable) $Doc->ExportCaption($this->Bachelor_University);
				if ($this->Bachelors_Major->Exportable) $Doc->ExportCaption($this->Bachelors_Major);
				if ($this->Bachelors_GPA->Exportable) $Doc->ExportCaption($this->Bachelors_GPA);
				if ($this->Bachelors_MGPA->Exportable) $Doc->ExportCaption($this->Bachelors_MGPA);
				if ($this->Other_Bachelors_Title->Exportable) $Doc->ExportCaption($this->Other_Bachelors_Title);
				if ($this->Other_Bachelors_University->Exportable) $Doc->ExportCaption($this->Other_Bachelors_University);
				if ($this->Other_Bachelors_Major->Exportable) $Doc->ExportCaption($this->Other_Bachelors_Major);
				if ($this->Other_Bachelors_GPA->Exportable) $Doc->ExportCaption($this->Other_Bachelors_GPA);
				if ($this->Other_Bachelors_MGPA->Exportable) $Doc->ExportCaption($this->Other_Bachelors_MGPA);
				if ($this->Masters_Degree_Title->Exportable) $Doc->ExportCaption($this->Masters_Degree_Title);
				if ($this->Master_University->Exportable) $Doc->ExportCaption($this->Master_University);
				if ($this->Masters_Degree_Major->Exportable) $Doc->ExportCaption($this->Masters_Degree_Major);
				if ($this->Masters_GPA->Exportable) $Doc->ExportCaption($this->Masters_GPA);
				if ($this->Other_Masters_Degree_Title->Exportable) $Doc->ExportCaption($this->Other_Masters_Degree_Title);
				if ($this->Other_Masters_University->Exportable) $Doc->ExportCaption($this->Other_Masters_University);
				if ($this->Other_Masters_Major->Exportable) $Doc->ExportCaption($this->Other_Masters_Major);
				if ($this->Other_Masters_GPA->Exportable) $Doc->ExportCaption($this->Other_Masters_GPA);
				if ($this->PhD_Title->Exportable) $Doc->ExportCaption($this->PhD_Title);
				if ($this->Phd_University->Exportable) $Doc->ExportCaption($this->Phd_University);
				if ($this->PhD_Major->Exportable) $Doc->ExportCaption($this->PhD_Major);
				if ($this->Phd_Degree_Equivalency->Exportable) $Doc->ExportCaption($this->Phd_Degree_Equivalency);
				if ($this->Committee_Meeting->Exportable) $Doc->ExportCaption($this->Committee_Meeting);
				if ($this->Committee_Meeting_Number->Exportable) $Doc->ExportCaption($this->Committee_Meeting_Number);
				if ($this->Committee_Date->Exportable) $Doc->ExportCaption($this->Committee_Date);
				if ($this->Notes->Exportable) $Doc->ExportCaption($this->Notes);
			} else {
				if ($this->ID->Exportable) $Doc->ExportCaption($this->ID);
				if ($this->Country->Exportable) $Doc->ExportCaption($this->Country);
				if ($this->Civil_ID->Exportable) $Doc->ExportCaption($this->Civil_ID);
				if ($this->Passport_No2E->Exportable) $Doc->ExportCaption($this->Passport_No2E);
				if ($this->Sector->Exportable) $Doc->ExportCaption($this->Sector);
				if ($this->Job_Title->Exportable) $Doc->ExportCaption($this->Job_Title);
				if ($this->Program->Exportable) $Doc->ExportCaption($this->Program);
				if ($this->College->Exportable) $Doc->ExportCaption($this->College);
				if ($this->Department->Exportable) $Doc->ExportCaption($this->Department);
				if ($this->Bachelors_Title->Exportable) $Doc->ExportCaption($this->Bachelors_Title);
				if ($this->Bachelors_Major->Exportable) $Doc->ExportCaption($this->Bachelors_Major);
				if ($this->Bachelors_GPA->Exportable) $Doc->ExportCaption($this->Bachelors_GPA);
				if ($this->Bachelors_MGPA->Exportable) $Doc->ExportCaption($this->Bachelors_MGPA);
				if ($this->Other_Bachelors_Title->Exportable) $Doc->ExportCaption($this->Other_Bachelors_Title);
				if ($this->Other_Bachelors_University->Exportable) $Doc->ExportCaption($this->Other_Bachelors_University);
				if ($this->Other_Bachelors_Major->Exportable) $Doc->ExportCaption($this->Other_Bachelors_Major);
				if ($this->Other_Bachelors_GPA->Exportable) $Doc->ExportCaption($this->Other_Bachelors_GPA);
				if ($this->Other_Bachelors_MGPA->Exportable) $Doc->ExportCaption($this->Other_Bachelors_MGPA);
				if ($this->Masters_Degree_Title->Exportable) $Doc->ExportCaption($this->Masters_Degree_Title);
				if ($this->Master_University->Exportable) $Doc->ExportCaption($this->Master_University);
				if ($this->Masters_Degree_Major->Exportable) $Doc->ExportCaption($this->Masters_Degree_Major);
				if ($this->Masters_GPA->Exportable) $Doc->ExportCaption($this->Masters_GPA);
				if ($this->Other_Masters_Degree_Title->Exportable) $Doc->ExportCaption($this->Other_Masters_Degree_Title);
				if ($this->Other_Masters_University->Exportable) $Doc->ExportCaption($this->Other_Masters_University);
				if ($this->Other_Masters_Major->Exportable) $Doc->ExportCaption($this->Other_Masters_Major);
				if ($this->Other_Masters_GPA->Exportable) $Doc->ExportCaption($this->Other_Masters_GPA);
				if ($this->PhD_Title->Exportable) $Doc->ExportCaption($this->PhD_Title);
				if ($this->Phd_University->Exportable) $Doc->ExportCaption($this->Phd_University);
				if ($this->PhD_Major->Exportable) $Doc->ExportCaption($this->PhD_Major);
				if ($this->Phd_Degree_Equivalency->Exportable) $Doc->ExportCaption($this->Phd_Degree_Equivalency);
				if ($this->Committee_Meeting->Exportable) $Doc->ExportCaption($this->Committee_Meeting);
				if ($this->Committee_Meeting_Number->Exportable) $Doc->ExportCaption($this->Committee_Meeting_Number);
				if ($this->Committee_Date->Exportable) $Doc->ExportCaption($this->Committee_Date);
			}
			$Doc->EndExportRow();
		}

		// Move to first record
		$RecCnt = $StartRec - 1;
		if (!$Recordset->EOF) {
			$Recordset->MoveFirst();
			if ($StartRec > 1)
				$Recordset->Move($StartRec - 1);
		}
		while (!$Recordset->EOF && $RecCnt < $StopRec) {
			$RecCnt++;
			if (intval($RecCnt) >= intval($StartRec)) {
				$RowCnt = intval($RecCnt) - intval($StartRec) + 1;

				// Page break
				if ($this->ExportPageBreakCount > 0) {
					if ($RowCnt > 1 && ($RowCnt - 1) % $this->ExportPageBreakCount == 0)
						$Doc->ExportPageBreak();
				}
				$this->LoadListRowValues($Recordset);

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
				if ($ExportPageType == "view") {
					if ($this->ID->Exportable) $Doc->ExportField($this->ID);
					if ($this->Name->Exportable) $Doc->ExportField($this->Name);
					if ($this->Country->Exportable) $Doc->ExportField($this->Country);
					if ($this->Civil_ID->Exportable) $Doc->ExportField($this->Civil_ID);
					if ($this->Passport_No2E->Exportable) $Doc->ExportField($this->Passport_No2E);
					if ($this->Sector->Exportable) $Doc->ExportField($this->Sector);
					if ($this->Job_Title->Exportable) $Doc->ExportField($this->Job_Title);
					if ($this->Program->Exportable) $Doc->ExportField($this->Program);
					if ($this->College->Exportable) $Doc->ExportField($this->College);
					if ($this->Department->Exportable) $Doc->ExportField($this->Department);
					if ($this->Bachelors_Title->Exportable) $Doc->ExportField($this->Bachelors_Title);
					if ($this->Bachelor_University->Exportable) $Doc->ExportField($this->Bachelor_University);
					if ($this->Bachelors_Major->Exportable) $Doc->ExportField($this->Bachelors_Major);
					if ($this->Bachelors_GPA->Exportable) $Doc->ExportField($this->Bachelors_GPA);
					if ($this->Bachelors_MGPA->Exportable) $Doc->ExportField($this->Bachelors_MGPA);
					if ($this->Other_Bachelors_Title->Exportable) $Doc->ExportField($this->Other_Bachelors_Title);
					if ($this->Other_Bachelors_University->Exportable) $Doc->ExportField($this->Other_Bachelors_University);
					if ($this->Other_Bachelors_Major->Exportable) $Doc->ExportField($this->Other_Bachelors_Major);
					if ($this->Other_Bachelors_GPA->Exportable) $Doc->ExportField($this->Other_Bachelors_GPA);
					if ($this->Other_Bachelors_MGPA->Exportable) $Doc->ExportField($this->Other_Bachelors_MGPA);
					if ($this->Masters_Degree_Title->Exportable) $Doc->ExportField($this->Masters_Degree_Title);
					if ($this->Master_University->Exportable) $Doc->ExportField($this->Master_University);
					if ($this->Masters_Degree_Major->Exportable) $Doc->ExportField($this->Masters_Degree_Major);
					if ($this->Masters_GPA->Exportable) $Doc->ExportField($this->Masters_GPA);
					if ($this->Other_Masters_Degree_Title->Exportable) $Doc->ExportField($this->Other_Masters_Degree_Title);
					if ($this->Other_Masters_University->Exportable) $Doc->ExportField($this->Other_Masters_University);
					if ($this->Other_Masters_Major->Exportable) $Doc->ExportField($this->Other_Masters_Major);
					if ($this->Other_Masters_GPA->Exportable) $Doc->ExportField($this->Other_Masters_GPA);
					if ($this->PhD_Title->Exportable) $Doc->ExportField($this->PhD_Title);
					if ($this->Phd_University->Exportable) $Doc->ExportField($this->Phd_University);
					if ($this->PhD_Major->Exportable) $Doc->ExportField($this->PhD_Major);
					if ($this->Phd_Degree_Equivalency->Exportable) $Doc->ExportField($this->Phd_Degree_Equivalency);
					if ($this->Committee_Meeting->Exportable) $Doc->ExportField($this->Committee_Meeting);
					if ($this->Committee_Meeting_Number->Exportable) $Doc->ExportField($this->Committee_Meeting_Number);
					if ($this->Committee_Date->Exportable) $Doc->ExportField($this->Committee_Date);
					if ($this->Notes->Exportable) $Doc->ExportField($this->Notes);
				} else {
					if ($this->ID->Exportable) $Doc->ExportField($this->ID);
					if ($this->Country->Exportable) $Doc->ExportField($this->Country);
					if ($this->Civil_ID->Exportable) $Doc->ExportField($this->Civil_ID);
					if ($this->Passport_No2E->Exportable) $Doc->ExportField($this->Passport_No2E);
					if ($this->Sector->Exportable) $Doc->ExportField($this->Sector);
					if ($this->Job_Title->Exportable) $Doc->ExportField($this->Job_Title);
					if ($this->Program->Exportable) $Doc->ExportField($this->Program);
					if ($this->College->Exportable) $Doc->ExportField($this->College);
					if ($this->Department->Exportable) $Doc->ExportField($this->Department);
					if ($this->Bachelors_Title->Exportable) $Doc->ExportField($this->Bachelors_Title);
					if ($this->Bachelors_Major->Exportable) $Doc->ExportField($this->Bachelors_Major);
					if ($this->Bachelors_GPA->Exportable) $Doc->ExportField($this->Bachelors_GPA);
					if ($this->Bachelors_MGPA->Exportable) $Doc->ExportField($this->Bachelors_MGPA);
					if ($this->Other_Bachelors_Title->Exportable) $Doc->ExportField($this->Other_Bachelors_Title);
					if ($this->Other_Bachelors_University->Exportable) $Doc->ExportField($this->Other_Bachelors_University);
					if ($this->Other_Bachelors_Major->Exportable) $Doc->ExportField($this->Other_Bachelors_Major);
					if ($this->Other_Bachelors_GPA->Exportable) $Doc->ExportField($this->Other_Bachelors_GPA);
					if ($this->Other_Bachelors_MGPA->Exportable) $Doc->ExportField($this->Other_Bachelors_MGPA);
					if ($this->Masters_Degree_Title->Exportable) $Doc->ExportField($this->Masters_Degree_Title);
					if ($this->Master_University->Exportable) $Doc->ExportField($this->Master_University);
					if ($this->Masters_Degree_Major->Exportable) $Doc->ExportField($this->Masters_Degree_Major);
					if ($this->Masters_GPA->Exportable) $Doc->ExportField($this->Masters_GPA);
					if ($this->Other_Masters_Degree_Title->Exportable) $Doc->ExportField($this->Other_Masters_Degree_Title);
					if ($this->Other_Masters_University->Exportable) $Doc->ExportField($this->Other_Masters_University);
					if ($this->Other_Masters_Major->Exportable) $Doc->ExportField($this->Other_Masters_Major);
					if ($this->Other_Masters_GPA->Exportable) $Doc->ExportField($this->Other_Masters_GPA);
					if ($this->PhD_Title->Exportable) $Doc->ExportField($this->PhD_Title);
					if ($this->Phd_University->Exportable) $Doc->ExportField($this->Phd_University);
					if ($this->PhD_Major->Exportable) $Doc->ExportField($this->PhD_Major);
					if ($this->Phd_Degree_Equivalency->Exportable) $Doc->ExportField($this->Phd_Degree_Equivalency);
					if ($this->Committee_Meeting->Exportable) $Doc->ExportField($this->Committee_Meeting);
					if ($this->Committee_Meeting_Number->Exportable) $Doc->ExportField($this->Committee_Meeting_Number);
					if ($this->Committee_Date->Exportable) $Doc->ExportField($this->Committee_Date);
				}
				$Doc->EndExportRow();
			}
			$Recordset->MoveNext();
		}
		$Doc->ExportTableFooter();
	}

	// Table level events
	// Recordset Selecting event
	function Recordset_Selecting(&$filter) {

		// Enter your code here	
	}

	// Recordset Selected event
	function Recordset_Selected(&$rs) {

		//echo "Recordset Selected";
	}

	// Recordset Search Validated event
	function Recordset_SearchValidated() {

		// Example:
		//$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value

	}

	// Recordset Searching event
	function Recordset_Searching(&$filter) {

		// Enter your code here	
	}

	// Row_Selecting event
	function Row_Selecting(&$filter) {

		// Enter your code here	
	}

	// Row Selected event
	function Row_Selected(&$rs) {

		//echo "Row Selected";
	}

	// Row Inserting event
	function Row_Inserting($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
	}

	// Row Update Conflict event
	function Row_UpdateConflict($rsold, &$rsnew) {

		// Enter your code here
		// To ignore conflict, set return value to FALSE

		return TRUE;
	}

	// Row Deleting event
	function Row_Deleting(&$rs) {

		// Enter your code here
		// To cancel, set return value to False

		return TRUE;
	}

	// Row Deleted event
	function Row_Deleted(&$rs) {

		//echo "Row Deleted";
	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Lookup Selecting event
	function Lookup_Selecting($fld, &$filter) {

		// Enter your code here
	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here	
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>); 

	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
