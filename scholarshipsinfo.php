<?php

// Global variable for table object
$scholarships = NULL;

//
// Table class for scholarships
//
class cscholarships extends cTable {
	var $ID;
	var $English_Name;
	var $Arabic_Name;
	var $College;
	var $Department;
	var $Major;
	var $GPA;
	var $Graduated_From;
	var $Acceptance_Counrty;
	var $Acceptance_University;
	var $Program_Degree;
	var $Notes;
	var $Committee_Date;
	var $Status;
	var $Justification;
	var $LastModifiedUser;
	var $LastModifiedTime;
	var $LastModifiedIP;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'scholarships';
		$this->TableName = 'scholarships';
		$this->TableType = 'TABLE';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "landscape"; // Page orientation (PDF only)
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
		$this->ID = new cField('scholarships', 'scholarships', 'x_ID', 'ID', '`ID`', '`ID`', 3, -1, FALSE, '`ID`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->ID->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['ID'] = &$this->ID;

		// English Name
		$this->English_Name = new cField('scholarships', 'scholarships', 'x_English_Name', 'English Name', '`English Name`', '`English Name`', 200, -1, FALSE, '`English Name`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['English Name'] = &$this->English_Name;

		// Arabic Name
		$this->Arabic_Name = new cField('scholarships', 'scholarships', 'x_Arabic_Name', 'Arabic Name', '`Arabic Name`', '`Arabic Name`', 200, -1, FALSE, '`Arabic Name`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Arabic Name'] = &$this->Arabic_Name;

		// College
		$this->College = new cField('scholarships', 'scholarships', 'x_College', 'College', '`College`', '`College`', 200, -1, FALSE, '`College`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['College'] = &$this->College;

		// Department
		$this->Department = new cField('scholarships', 'scholarships', 'x_Department', 'Department', '`Department`', '`Department`', 200, -1, FALSE, '`Department`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Department'] = &$this->Department;

		// Major
		$this->Major = new cField('scholarships', 'scholarships', 'x_Major', 'Major', '`Major`', '`Major`', 200, -1, FALSE, '`Major`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Major'] = &$this->Major;

		// GPA
		$this->GPA = new cField('scholarships', 'scholarships', 'x_GPA', 'GPA', '`GPA`', '`GPA`', 131, -1, FALSE, '`GPA`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->GPA->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['GPA'] = &$this->GPA;

		// Graduated From
		$this->Graduated_From = new cField('scholarships', 'scholarships', 'x_Graduated_From', 'Graduated From', '`Graduated From`', '`Graduated From`', 200, -1, FALSE, '`Graduated From`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Graduated From'] = &$this->Graduated_From;

		// Acceptance Counrty
		$this->Acceptance_Counrty = new cField('scholarships', 'scholarships', 'x_Acceptance_Counrty', 'Acceptance Counrty', '`Acceptance Counrty`', '`Acceptance Counrty`', 200, -1, FALSE, '`Acceptance Counrty`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Acceptance Counrty'] = &$this->Acceptance_Counrty;

		// Acceptance University
		$this->Acceptance_University = new cField('scholarships', 'scholarships', 'x_Acceptance_University', 'Acceptance University', '`Acceptance University`', '`Acceptance University`', 200, -1, FALSE, '`Acceptance University`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Acceptance University'] = &$this->Acceptance_University;

		// Program Degree
		$this->Program_Degree = new cField('scholarships', 'scholarships', 'x_Program_Degree', 'Program Degree', '`Program Degree`', '`Program Degree`', 202, -1, FALSE, '`Program Degree`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Program Degree'] = &$this->Program_Degree;

		// Notes
		$this->Notes = new cField('scholarships', 'scholarships', 'x_Notes', 'Notes', '`Notes`', '`Notes`', 201, -1, FALSE, '`Notes`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Notes'] = &$this->Notes;

		// Committee Date
		$this->Committee_Date = new cField('scholarships', 'scholarships', 'x_Committee_Date', 'Committee Date', '`Committee Date`', 'DATE_FORMAT(`Committee Date`, \'%d/%m/%Y\')', 133, 7, FALSE, '`Committee Date`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->Committee_Date->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['Committee Date'] = &$this->Committee_Date;

		// Status
		$this->Status = new cField('scholarships', 'scholarships', 'x_Status', 'Status', '`Status`', '`Status`', 202, -1, FALSE, '`Status`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Status'] = &$this->Status;

		// Justification
		$this->Justification = new cField('scholarships', 'scholarships', 'x_Justification', 'Justification', '`Justification`', '`Justification`', 201, -1, FALSE, '`Justification`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Justification'] = &$this->Justification;

		// LastModifiedUser
		$this->LastModifiedUser = new cField('scholarships', 'scholarships', 'x_LastModifiedUser', 'LastModifiedUser', '`LastModifiedUser`', '`LastModifiedUser`', 200, -1, FALSE, '`LastModifiedUser`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['LastModifiedUser'] = &$this->LastModifiedUser;

		// LastModifiedTime
		$this->LastModifiedTime = new cField('scholarships', 'scholarships', 'x_LastModifiedTime', 'LastModifiedTime', '`LastModifiedTime`', '`LastModifiedTime`', 200, -1, FALSE, '`LastModifiedTime`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['LastModifiedTime'] = &$this->LastModifiedTime;

		// LastModifiedIP
		$this->LastModifiedIP = new cField('scholarships', 'scholarships', 'x_LastModifiedIP', 'LastModifiedIP', '`LastModifiedIP`', '`LastModifiedIP`', 200, -1, FALSE, '`LastModifiedIP`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['LastModifiedIP'] = &$this->LastModifiedIP;
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
		} else {
			$ofld->setSort("");
		}
	}

	// Table level SQL
	function SqlFrom() { // From
		return "`scholarships`";
	}

	function SqlSelect() { // Select
		return "SELECT * FROM " . $this->SqlFrom();
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
		return "`College` ASC,`Department` ASC,`Status` ASC,`Arabic Name` ASC";
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
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->SqlSelect(), $this->SqlWhere(), $this->SqlGroupBy(),
			$this->SqlHaving(), $this->SqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->SqlOrderBy(), "", $sSort);
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
	var $UpdateTable = "`scholarships`";

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
			return "scholarshipslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "scholarshipslist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("scholarshipsview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("scholarshipsview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "scholarshipsadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("scholarshipsedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("scholarshipsadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("scholarshipsdelete.php", $this->UrlParm());
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
		$this->English_Name->setDbValue($rs->fields('English Name'));
		$this->Arabic_Name->setDbValue($rs->fields('Arabic Name'));
		$this->College->setDbValue($rs->fields('College'));
		$this->Department->setDbValue($rs->fields('Department'));
		$this->Major->setDbValue($rs->fields('Major'));
		$this->GPA->setDbValue($rs->fields('GPA'));
		$this->Graduated_From->setDbValue($rs->fields('Graduated From'));
		$this->Acceptance_Counrty->setDbValue($rs->fields('Acceptance Counrty'));
		$this->Acceptance_University->setDbValue($rs->fields('Acceptance University'));
		$this->Program_Degree->setDbValue($rs->fields('Program Degree'));
		$this->Notes->setDbValue($rs->fields('Notes'));
		$this->Committee_Date->setDbValue($rs->fields('Committee Date'));
		$this->Status->setDbValue($rs->fields('Status'));
		$this->Justification->setDbValue($rs->fields('Justification'));
		$this->LastModifiedUser->setDbValue($rs->fields('LastModifiedUser'));
		$this->LastModifiedTime->setDbValue($rs->fields('LastModifiedTime'));
		$this->LastModifiedIP->setDbValue($rs->fields('LastModifiedIP'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security, $gsLanguage;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// ID
		// English Name
		// Arabic Name
		// College
		// Department
		// Major
		// GPA
		// Graduated From
		// Acceptance Counrty
		// Acceptance University
		// Program Degree
		// Notes
		// Committee Date
		// Status
		// Justification
		// LastModifiedUser
		// LastModifiedTime
		// LastModifiedIP
		// ID

		$this->ID->ViewValue = $this->ID->CurrentValue;
		$this->ID->ViewCustomAttributes = "";

		// English Name
		$this->English_Name->ViewValue = $this->English_Name->CurrentValue;
		$this->English_Name->CellCssStyle .= "text-align: left;";
		$this->English_Name->ViewCustomAttributes = "";

		// Arabic Name
		$this->Arabic_Name->ViewValue = $this->Arabic_Name->CurrentValue;
		$this->Arabic_Name->CellCssStyle .= "text-align: right;";
		$this->Arabic_Name->ViewCustomAttributes = "";

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

		// Major
		$this->Major->ViewValue = $this->Major->CurrentValue;
		$this->Major->CellCssStyle .= "text-align: right;";
		$this->Major->ViewCustomAttributes = "";

		// GPA
		$this->GPA->ViewValue = $this->GPA->CurrentValue;
		$this->GPA->ViewCustomAttributes = "";

		// Graduated From
		$this->Graduated_From->ViewValue = $this->Graduated_From->CurrentValue;
		$this->Graduated_From->ViewCustomAttributes = "";

		// Acceptance Counrty
		$this->Acceptance_Counrty->ViewValue = $this->Acceptance_Counrty->CurrentValue;
		$this->Acceptance_Counrty->CellCssStyle .= "text-align: right;";
		$this->Acceptance_Counrty->ViewCustomAttributes = "";

		// Acceptance University
		$this->Acceptance_University->ViewValue = $this->Acceptance_University->CurrentValue;
		$this->Acceptance_University->ViewCustomAttributes = "";

		// Program Degree
		if (strval($this->Program_Degree->CurrentValue) <> "") {
			switch ($this->Program_Degree->CurrentValue) {
				case $this->Program_Degree->FldTagValue(1):
					$this->Program_Degree->ViewValue = $this->Program_Degree->FldTagCaption(1) <> "" ? $this->Program_Degree->FldTagCaption(1) : $this->Program_Degree->CurrentValue;
					break;
				case $this->Program_Degree->FldTagValue(2):
					$this->Program_Degree->ViewValue = $this->Program_Degree->FldTagCaption(2) <> "" ? $this->Program_Degree->FldTagCaption(2) : $this->Program_Degree->CurrentValue;
					break;
				case $this->Program_Degree->FldTagValue(3):
					$this->Program_Degree->ViewValue = $this->Program_Degree->FldTagCaption(3) <> "" ? $this->Program_Degree->FldTagCaption(3) : $this->Program_Degree->CurrentValue;
					break;
				case $this->Program_Degree->FldTagValue(4):
					$this->Program_Degree->ViewValue = $this->Program_Degree->FldTagCaption(4) <> "" ? $this->Program_Degree->FldTagCaption(4) : $this->Program_Degree->CurrentValue;
					break;
				default:
					$this->Program_Degree->ViewValue = $this->Program_Degree->CurrentValue;
			}
		} else {
			$this->Program_Degree->ViewValue = NULL;
		}
		$this->Program_Degree->ViewCustomAttributes = "";

		// Notes
		$this->Notes->ViewValue = $this->Notes->CurrentValue;
		$this->Notes->ViewCustomAttributes = "";

		// Committee Date
		$this->Committee_Date->ViewValue = $this->Committee_Date->CurrentValue;
		$this->Committee_Date->ViewValue = ew_FormatDateTime($this->Committee_Date->ViewValue, 7);
		$this->Committee_Date->ViewCustomAttributes = "";

		// Status
		if (strval($this->Status->CurrentValue) <> "") {
			switch ($this->Status->CurrentValue) {
				case $this->Status->FldTagValue(1):
					$this->Status->ViewValue = $this->Status->FldTagCaption(1) <> "" ? $this->Status->FldTagCaption(1) : $this->Status->CurrentValue;
					break;
				case $this->Status->FldTagValue(2):
					$this->Status->ViewValue = $this->Status->FldTagCaption(2) <> "" ? $this->Status->FldTagCaption(2) : $this->Status->CurrentValue;
					break;
				case $this->Status->FldTagValue(3):
					$this->Status->ViewValue = $this->Status->FldTagCaption(3) <> "" ? $this->Status->FldTagCaption(3) : $this->Status->CurrentValue;
					break;
				default:
					$this->Status->ViewValue = $this->Status->CurrentValue;
			}
		} else {
			$this->Status->ViewValue = NULL;
		}
		$this->Status->ViewCustomAttributes = "";

		// Justification
		$this->Justification->ViewValue = $this->Justification->CurrentValue;
		$this->Justification->CellCssStyle .= "text-align: right;";
		$this->Justification->ViewCustomAttributes = "";

		// LastModifiedUser
		$this->LastModifiedUser->ViewValue = $this->LastModifiedUser->CurrentValue;
		$this->LastModifiedUser->ViewCustomAttributes = "";

		// LastModifiedTime
		$this->LastModifiedTime->ViewValue = $this->LastModifiedTime->CurrentValue;
		$this->LastModifiedTime->ViewCustomAttributes = "";

		// LastModifiedIP
		$this->LastModifiedIP->ViewValue = $this->LastModifiedIP->CurrentValue;
		$this->LastModifiedIP->ViewCustomAttributes = "";

		// ID
		$this->ID->LinkCustomAttributes = "";
		$this->ID->HrefValue = "";
		$this->ID->TooltipValue = "";

		// English Name
		$this->English_Name->LinkCustomAttributes = "";
		$this->English_Name->HrefValue = "";
		$this->English_Name->TooltipValue = "";

		// Arabic Name
		$this->Arabic_Name->LinkCustomAttributes = "";
		$this->Arabic_Name->HrefValue = "";
		$this->Arabic_Name->TooltipValue = "";

		// College
		$this->College->LinkCustomAttributes = "";
		$this->College->HrefValue = "";
		$this->College->TooltipValue = "";

		// Department
		$this->Department->LinkCustomAttributes = "";
		$this->Department->HrefValue = "";
		$this->Department->TooltipValue = "";

		// Major
		$this->Major->LinkCustomAttributes = "";
		$this->Major->HrefValue = "";
		$this->Major->TooltipValue = "";

		// GPA
		$this->GPA->LinkCustomAttributes = "";
		$this->GPA->HrefValue = "";
		$this->GPA->TooltipValue = "";

		// Graduated From
		$this->Graduated_From->LinkCustomAttributes = "";
		$this->Graduated_From->HrefValue = "";
		$this->Graduated_From->TooltipValue = "";

		// Acceptance Counrty
		$this->Acceptance_Counrty->LinkCustomAttributes = "";
		$this->Acceptance_Counrty->HrefValue = "";
		$this->Acceptance_Counrty->TooltipValue = "";

		// Acceptance University
		$this->Acceptance_University->LinkCustomAttributes = "";
		$this->Acceptance_University->HrefValue = "";
		$this->Acceptance_University->TooltipValue = "";

		// Program Degree
		$this->Program_Degree->LinkCustomAttributes = "";
		$this->Program_Degree->HrefValue = "";
		$this->Program_Degree->TooltipValue = "";

		// Notes
		$this->Notes->LinkCustomAttributes = "";
		$this->Notes->HrefValue = "";
		$this->Notes->TooltipValue = "";

		// Committee Date
		$this->Committee_Date->LinkCustomAttributes = "";
		$this->Committee_Date->HrefValue = "";
		$this->Committee_Date->TooltipValue = "";

		// Status
		$this->Status->LinkCustomAttributes = "";
		$this->Status->HrefValue = "";
		$this->Status->TooltipValue = "";

		// Justification
		$this->Justification->LinkCustomAttributes = "";
		$this->Justification->HrefValue = "";
		$this->Justification->TooltipValue = "";

		// LastModifiedUser
		$this->LastModifiedUser->LinkCustomAttributes = "";
		$this->LastModifiedUser->HrefValue = "";
		$this->LastModifiedUser->TooltipValue = "";

		// LastModifiedTime
		$this->LastModifiedTime->LinkCustomAttributes = "";
		$this->LastModifiedTime->HrefValue = "";
		$this->LastModifiedTime->TooltipValue = "";

		// LastModifiedIP
		$this->LastModifiedIP->LinkCustomAttributes = "";
		$this->LastModifiedIP->HrefValue = "";
		$this->LastModifiedIP->TooltipValue = "";

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
				if ($this->English_Name->Exportable) $Doc->ExportCaption($this->English_Name);
				if ($this->Arabic_Name->Exportable) $Doc->ExportCaption($this->Arabic_Name);
				if ($this->College->Exportable) $Doc->ExportCaption($this->College);
				if ($this->Department->Exportable) $Doc->ExportCaption($this->Department);
				if ($this->Major->Exportable) $Doc->ExportCaption($this->Major);
				if ($this->GPA->Exportable) $Doc->ExportCaption($this->GPA);
				if ($this->Graduated_From->Exportable) $Doc->ExportCaption($this->Graduated_From);
				if ($this->Acceptance_Counrty->Exportable) $Doc->ExportCaption($this->Acceptance_Counrty);
				if ($this->Acceptance_University->Exportable) $Doc->ExportCaption($this->Acceptance_University);
				if ($this->Program_Degree->Exportable) $Doc->ExportCaption($this->Program_Degree);
				if ($this->Notes->Exportable) $Doc->ExportCaption($this->Notes);
				if ($this->Committee_Date->Exportable) $Doc->ExportCaption($this->Committee_Date);
				if ($this->Status->Exportable) $Doc->ExportCaption($this->Status);
				if ($this->Justification->Exportable) $Doc->ExportCaption($this->Justification);
				if ($this->LastModifiedUser->Exportable) $Doc->ExportCaption($this->LastModifiedUser);
				if ($this->LastModifiedTime->Exportable) $Doc->ExportCaption($this->LastModifiedTime);
				if ($this->LastModifiedIP->Exportable) $Doc->ExportCaption($this->LastModifiedIP);
			} else {
				if ($this->ID->Exportable) $Doc->ExportCaption($this->ID);
				if ($this->English_Name->Exportable) $Doc->ExportCaption($this->English_Name);
				if ($this->Arabic_Name->Exportable) $Doc->ExportCaption($this->Arabic_Name);
				if ($this->College->Exportable) $Doc->ExportCaption($this->College);
				if ($this->Department->Exportable) $Doc->ExportCaption($this->Department);
				if ($this->Major->Exportable) $Doc->ExportCaption($this->Major);
				if ($this->GPA->Exportable) $Doc->ExportCaption($this->GPA);
				if ($this->Graduated_From->Exportable) $Doc->ExportCaption($this->Graduated_From);
				if ($this->Acceptance_Counrty->Exportable) $Doc->ExportCaption($this->Acceptance_Counrty);
				if ($this->Acceptance_University->Exportable) $Doc->ExportCaption($this->Acceptance_University);
				if ($this->Program_Degree->Exportable) $Doc->ExportCaption($this->Program_Degree);
				if ($this->Committee_Date->Exportable) $Doc->ExportCaption($this->Committee_Date);
				if ($this->Status->Exportable) $Doc->ExportCaption($this->Status);
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
					if ($this->English_Name->Exportable) $Doc->ExportField($this->English_Name);
					if ($this->Arabic_Name->Exportable) $Doc->ExportField($this->Arabic_Name);
					if ($this->College->Exportable) $Doc->ExportField($this->College);
					if ($this->Department->Exportable) $Doc->ExportField($this->Department);
					if ($this->Major->Exportable) $Doc->ExportField($this->Major);
					if ($this->GPA->Exportable) $Doc->ExportField($this->GPA);
					if ($this->Graduated_From->Exportable) $Doc->ExportField($this->Graduated_From);
					if ($this->Acceptance_Counrty->Exportable) $Doc->ExportField($this->Acceptance_Counrty);
					if ($this->Acceptance_University->Exportable) $Doc->ExportField($this->Acceptance_University);
					if ($this->Program_Degree->Exportable) $Doc->ExportField($this->Program_Degree);
					if ($this->Notes->Exportable) $Doc->ExportField($this->Notes);
					if ($this->Committee_Date->Exportable) $Doc->ExportField($this->Committee_Date);
					if ($this->Status->Exportable) $Doc->ExportField($this->Status);
					if ($this->Justification->Exportable) $Doc->ExportField($this->Justification);
					if ($this->LastModifiedUser->Exportable) $Doc->ExportField($this->LastModifiedUser);
					if ($this->LastModifiedTime->Exportable) $Doc->ExportField($this->LastModifiedTime);
					if ($this->LastModifiedIP->Exportable) $Doc->ExportField($this->LastModifiedIP);
				} else {
					if ($this->ID->Exportable) $Doc->ExportField($this->ID);
					if ($this->English_Name->Exportable) $Doc->ExportField($this->English_Name);
					if ($this->Arabic_Name->Exportable) $Doc->ExportField($this->Arabic_Name);
					if ($this->College->Exportable) $Doc->ExportField($this->College);
					if ($this->Department->Exportable) $Doc->ExportField($this->Department);
					if ($this->Major->Exportable) $Doc->ExportField($this->Major);
					if ($this->GPA->Exportable) $Doc->ExportField($this->GPA);
					if ($this->Graduated_From->Exportable) $Doc->ExportField($this->Graduated_From);
					if ($this->Acceptance_Counrty->Exportable) $Doc->ExportField($this->Acceptance_Counrty);
					if ($this->Acceptance_University->Exportable) $Doc->ExportField($this->Acceptance_University);
					if ($this->Program_Degree->Exportable) $Doc->ExportField($this->Program_Degree);
					if ($this->Committee_Date->Exportable) $Doc->ExportField($this->Committee_Date);
					if ($this->Status->Exportable) $Doc->ExportField($this->Status);
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
