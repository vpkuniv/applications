<?php

// Global variable for table object
$facultyapplication = NULL;

//
// Table class for facultyapplication
//
class cfacultyapplication extends cTable {
	var $ID;
	var $Name;
	var $Nationality;
	var $College;
	var $Department;
	var $FacultyAffairsDate;
	var $FacultyAffairsRef;
	var $CollegeDecision;
	var $CollegeDecisionDate;
	var $CollegeDecisionRef;
	var $CommitteeDecision;
	var $CommitteeDecisionDate;
	var $CommitteeDecisionRef;
	var $PresidentDecision;
	var $PresidentDecisionDate;
	var $PresidentDecisionRef;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'facultyapplication';
		$this->TableName = 'facultyapplication';
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
		$this->ID = new cField('facultyapplication', 'facultyapplication', 'x_ID', 'ID', '`ID`', '`ID`', 3, -1, FALSE, '`ID`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->ID->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['ID'] = &$this->ID;

		// Name
		$this->Name = new cField('facultyapplication', 'facultyapplication', 'x_Name', 'Name', '`Name`', '`Name`', 200, -1, FALSE, '`Name`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Name'] = &$this->Name;

		// Nationality
		$this->Nationality = new cField('facultyapplication', 'facultyapplication', 'x_Nationality', 'Nationality', '`Nationality`', '`Nationality`', 200, -1, FALSE, '`Nationality`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Nationality'] = &$this->Nationality;

		// College
		$this->College = new cField('facultyapplication', 'facultyapplication', 'x_College', 'College', '`College`', '`College`', 200, -1, FALSE, '`College`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['College'] = &$this->College;

		// Department
		$this->Department = new cField('facultyapplication', 'facultyapplication', 'x_Department', 'Department', '`Department`', '`Department`', 200, -1, FALSE, '`Department`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Department'] = &$this->Department;

		// FacultyAffairsDate
		$this->FacultyAffairsDate = new cField('facultyapplication', 'facultyapplication', 'x_FacultyAffairsDate', 'FacultyAffairsDate', '`FacultyAffairsDate`', 'DATE_FORMAT(`FacultyAffairsDate`, \'%d/%m/%Y\')', 133, 7, FALSE, '`FacultyAffairsDate`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->FacultyAffairsDate->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['FacultyAffairsDate'] = &$this->FacultyAffairsDate;

		// FacultyAffairsRef
		$this->FacultyAffairsRef = new cField('facultyapplication', 'facultyapplication', 'x_FacultyAffairsRef', 'FacultyAffairsRef', '`FacultyAffairsRef`', '`FacultyAffairsRef`', 200, -1, FALSE, '`FacultyAffairsRef`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['FacultyAffairsRef'] = &$this->FacultyAffairsRef;

		// CollegeDecision
		$this->CollegeDecision = new cField('facultyapplication', 'facultyapplication', 'x_CollegeDecision', 'CollegeDecision', '`CollegeDecision`', '`CollegeDecision`', 202, -1, FALSE, '`CollegeDecision`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['CollegeDecision'] = &$this->CollegeDecision;

		// CollegeDecisionDate
		$this->CollegeDecisionDate = new cField('facultyapplication', 'facultyapplication', 'x_CollegeDecisionDate', 'CollegeDecisionDate', '`CollegeDecisionDate`', 'DATE_FORMAT(`CollegeDecisionDate`, \'%d/%m/%Y\')', 133, 7, FALSE, '`CollegeDecisionDate`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->CollegeDecisionDate->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['CollegeDecisionDate'] = &$this->CollegeDecisionDate;

		// CollegeDecisionRef
		$this->CollegeDecisionRef = new cField('facultyapplication', 'facultyapplication', 'x_CollegeDecisionRef', 'CollegeDecisionRef', '`CollegeDecisionRef`', '`CollegeDecisionRef`', 200, -1, FALSE, '`CollegeDecisionRef`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['CollegeDecisionRef'] = &$this->CollegeDecisionRef;

		// CommitteeDecision
		$this->CommitteeDecision = new cField('facultyapplication', 'facultyapplication', 'x_CommitteeDecision', 'CommitteeDecision', '`CommitteeDecision`', '`CommitteeDecision`', 202, -1, FALSE, '`CommitteeDecision`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['CommitteeDecision'] = &$this->CommitteeDecision;

		// CommitteeDecisionDate
		$this->CommitteeDecisionDate = new cField('facultyapplication', 'facultyapplication', 'x_CommitteeDecisionDate', 'CommitteeDecisionDate', '`CommitteeDecisionDate`', 'DATE_FORMAT(`CommitteeDecisionDate`, \'%d/%m/%Y\')', 133, 7, FALSE, '`CommitteeDecisionDate`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->CommitteeDecisionDate->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['CommitteeDecisionDate'] = &$this->CommitteeDecisionDate;

		// CommitteeDecisionRef
		$this->CommitteeDecisionRef = new cField('facultyapplication', 'facultyapplication', 'x_CommitteeDecisionRef', 'CommitteeDecisionRef', '`CommitteeDecisionRef`', '`CommitteeDecisionRef`', 200, -1, FALSE, '`CommitteeDecisionRef`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['CommitteeDecisionRef'] = &$this->CommitteeDecisionRef;

		// PresidentDecision
		$this->PresidentDecision = new cField('facultyapplication', 'facultyapplication', 'x_PresidentDecision', 'PresidentDecision', '`PresidentDecision`', '`PresidentDecision`', 202, -1, FALSE, '`PresidentDecision`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['PresidentDecision'] = &$this->PresidentDecision;

		// PresidentDecisionDate
		$this->PresidentDecisionDate = new cField('facultyapplication', 'facultyapplication', 'x_PresidentDecisionDate', 'PresidentDecisionDate', '`PresidentDecisionDate`', 'DATE_FORMAT(`PresidentDecisionDate`, \'%d/%m/%Y\')', 133, 7, FALSE, '`PresidentDecisionDate`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->PresidentDecisionDate->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['PresidentDecisionDate'] = &$this->PresidentDecisionDate;

		// PresidentDecisionRef
		$this->PresidentDecisionRef = new cField('facultyapplication', 'facultyapplication', 'x_PresidentDecisionRef', 'PresidentDecisionRef', '`PresidentDecisionRef`', '`PresidentDecisionRef`', 200, -1, FALSE, '`PresidentDecisionRef`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['PresidentDecisionRef'] = &$this->PresidentDecisionRef;
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
		return "`facultyapplication`";
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
		return "";
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
	var $UpdateTable = "`facultyapplication`";

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
			return "facultyapplicationlist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "facultyapplicationlist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("facultyapplicationview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("facultyapplicationview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "facultyapplicationadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("facultyapplicationedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("facultyapplicationadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("facultyapplicationdelete.php", $this->UrlParm());
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
		$this->Nationality->setDbValue($rs->fields('Nationality'));
		$this->College->setDbValue($rs->fields('College'));
		$this->Department->setDbValue($rs->fields('Department'));
		$this->FacultyAffairsDate->setDbValue($rs->fields('FacultyAffairsDate'));
		$this->FacultyAffairsRef->setDbValue($rs->fields('FacultyAffairsRef'));
		$this->CollegeDecision->setDbValue($rs->fields('CollegeDecision'));
		$this->CollegeDecisionDate->setDbValue($rs->fields('CollegeDecisionDate'));
		$this->CollegeDecisionRef->setDbValue($rs->fields('CollegeDecisionRef'));
		$this->CommitteeDecision->setDbValue($rs->fields('CommitteeDecision'));
		$this->CommitteeDecisionDate->setDbValue($rs->fields('CommitteeDecisionDate'));
		$this->CommitteeDecisionRef->setDbValue($rs->fields('CommitteeDecisionRef'));
		$this->PresidentDecision->setDbValue($rs->fields('PresidentDecision'));
		$this->PresidentDecisionDate->setDbValue($rs->fields('PresidentDecisionDate'));
		$this->PresidentDecisionRef->setDbValue($rs->fields('PresidentDecisionRef'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security, $gsLanguage;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// ID
		// Name
		// Nationality
		// College
		// Department
		// FacultyAffairsDate
		// FacultyAffairsRef
		// CollegeDecision
		// CollegeDecisionDate
		// CollegeDecisionRef
		// CommitteeDecision
		// CommitteeDecisionDate
		// CommitteeDecisionRef
		// PresidentDecision
		// PresidentDecisionDate
		// PresidentDecisionRef
		// ID

		$this->ID->ViewValue = $this->ID->CurrentValue;
		$this->ID->ViewCustomAttributes = "";

		// Name
		$this->Name->ViewValue = $this->Name->CurrentValue;
		$this->Name->ViewCustomAttributes = "";

		// Nationality
		$this->Nationality->ViewValue = $this->Nationality->CurrentValue;
		$this->Nationality->ViewCustomAttributes = "";

		// College
		$this->College->ViewValue = $this->College->CurrentValue;
		$this->College->ViewCustomAttributes = "";

		// Department
		if (strval($this->Department->CurrentValue) <> "") {
			$sFilterWrk = "`DID`" . ew_SearchString("=", $this->Department->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT `DID`, `DepartmentName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `departments`";
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
		$this->Department->ViewCustomAttributes = "";

		// FacultyAffairsDate
		$this->FacultyAffairsDate->ViewValue = $this->FacultyAffairsDate->CurrentValue;
		$this->FacultyAffairsDate->ViewValue = ew_FormatDateTime($this->FacultyAffairsDate->ViewValue, 7);
		$this->FacultyAffairsDate->ViewCustomAttributes = "";

		// FacultyAffairsRef
		$this->FacultyAffairsRef->ViewValue = $this->FacultyAffairsRef->CurrentValue;
		$this->FacultyAffairsRef->ViewCustomAttributes = "";

		// CollegeDecision
		if (strval($this->CollegeDecision->CurrentValue) <> "") {
			switch ($this->CollegeDecision->CurrentValue) {
				case $this->CollegeDecision->FldTagValue(1):
					$this->CollegeDecision->ViewValue = $this->CollegeDecision->FldTagCaption(1) <> "" ? $this->CollegeDecision->FldTagCaption(1) : $this->CollegeDecision->CurrentValue;
					break;
				case $this->CollegeDecision->FldTagValue(2):
					$this->CollegeDecision->ViewValue = $this->CollegeDecision->FldTagCaption(2) <> "" ? $this->CollegeDecision->FldTagCaption(2) : $this->CollegeDecision->CurrentValue;
					break;
				default:
					$this->CollegeDecision->ViewValue = $this->CollegeDecision->CurrentValue;
			}
		} else {
			$this->CollegeDecision->ViewValue = NULL;
		}
		$this->CollegeDecision->ViewCustomAttributes = "";

		// CollegeDecisionDate
		$this->CollegeDecisionDate->ViewValue = $this->CollegeDecisionDate->CurrentValue;
		$this->CollegeDecisionDate->ViewValue = ew_FormatDateTime($this->CollegeDecisionDate->ViewValue, 7);
		$this->CollegeDecisionDate->ViewCustomAttributes = "";

		// CollegeDecisionRef
		$this->CollegeDecisionRef->ViewValue = $this->CollegeDecisionRef->CurrentValue;
		$this->CollegeDecisionRef->ViewCustomAttributes = "";

		// CommitteeDecision
		if (strval($this->CommitteeDecision->CurrentValue) <> "") {
			switch ($this->CommitteeDecision->CurrentValue) {
				case $this->CommitteeDecision->FldTagValue(1):
					$this->CommitteeDecision->ViewValue = $this->CommitteeDecision->FldTagCaption(1) <> "" ? $this->CommitteeDecision->FldTagCaption(1) : $this->CommitteeDecision->CurrentValue;
					break;
				case $this->CommitteeDecision->FldTagValue(2):
					$this->CommitteeDecision->ViewValue = $this->CommitteeDecision->FldTagCaption(2) <> "" ? $this->CommitteeDecision->FldTagCaption(2) : $this->CommitteeDecision->CurrentValue;
					break;
				default:
					$this->CommitteeDecision->ViewValue = $this->CommitteeDecision->CurrentValue;
			}
		} else {
			$this->CommitteeDecision->ViewValue = NULL;
		}
		$this->CommitteeDecision->ViewCustomAttributes = "";

		// CommitteeDecisionDate
		$this->CommitteeDecisionDate->ViewValue = $this->CommitteeDecisionDate->CurrentValue;
		$this->CommitteeDecisionDate->ViewValue = ew_FormatDateTime($this->CommitteeDecisionDate->ViewValue, 7);
		$this->CommitteeDecisionDate->ViewCustomAttributes = "";

		// CommitteeDecisionRef
		$this->CommitteeDecisionRef->ViewValue = $this->CommitteeDecisionRef->CurrentValue;
		$this->CommitteeDecisionRef->ViewCustomAttributes = "";

		// PresidentDecision
		if (strval($this->PresidentDecision->CurrentValue) <> "") {
			switch ($this->PresidentDecision->CurrentValue) {
				case $this->PresidentDecision->FldTagValue(1):
					$this->PresidentDecision->ViewValue = $this->PresidentDecision->FldTagCaption(1) <> "" ? $this->PresidentDecision->FldTagCaption(1) : $this->PresidentDecision->CurrentValue;
					break;
				case $this->PresidentDecision->FldTagValue(2):
					$this->PresidentDecision->ViewValue = $this->PresidentDecision->FldTagCaption(2) <> "" ? $this->PresidentDecision->FldTagCaption(2) : $this->PresidentDecision->CurrentValue;
					break;
				default:
					$this->PresidentDecision->ViewValue = $this->PresidentDecision->CurrentValue;
			}
		} else {
			$this->PresidentDecision->ViewValue = NULL;
		}
		$this->PresidentDecision->ViewCustomAttributes = "";

		// PresidentDecisionDate
		$this->PresidentDecisionDate->ViewValue = $this->PresidentDecisionDate->CurrentValue;
		$this->PresidentDecisionDate->ViewValue = ew_FormatDateTime($this->PresidentDecisionDate->ViewValue, 7);
		$this->PresidentDecisionDate->ViewCustomAttributes = "";

		// PresidentDecisionRef
		$this->PresidentDecisionRef->ViewValue = $this->PresidentDecisionRef->CurrentValue;
		$this->PresidentDecisionRef->ViewCustomAttributes = "";

		// ID
		$this->ID->LinkCustomAttributes = "";
		$this->ID->HrefValue = "";
		$this->ID->TooltipValue = "";

		// Name
		$this->Name->LinkCustomAttributes = "";
		$this->Name->HrefValue = "";
		$this->Name->TooltipValue = "";

		// Nationality
		$this->Nationality->LinkCustomAttributes = "";
		$this->Nationality->HrefValue = "";
		$this->Nationality->TooltipValue = "";

		// College
		$this->College->LinkCustomAttributes = "";
		$this->College->HrefValue = "";
		$this->College->TooltipValue = "";

		// Department
		$this->Department->LinkCustomAttributes = "";
		$this->Department->HrefValue = "";
		$this->Department->TooltipValue = "";

		// FacultyAffairsDate
		$this->FacultyAffairsDate->LinkCustomAttributes = "";
		$this->FacultyAffairsDate->HrefValue = "";
		$this->FacultyAffairsDate->TooltipValue = "";

		// FacultyAffairsRef
		$this->FacultyAffairsRef->LinkCustomAttributes = "";
		$this->FacultyAffairsRef->HrefValue = "";
		$this->FacultyAffairsRef->TooltipValue = "";

		// CollegeDecision
		$this->CollegeDecision->LinkCustomAttributes = "";
		$this->CollegeDecision->HrefValue = "";
		$this->CollegeDecision->TooltipValue = "";

		// CollegeDecisionDate
		$this->CollegeDecisionDate->LinkCustomAttributes = "";
		$this->CollegeDecisionDate->HrefValue = "";
		$this->CollegeDecisionDate->TooltipValue = "";

		// CollegeDecisionRef
		$this->CollegeDecisionRef->LinkCustomAttributes = "";
		$this->CollegeDecisionRef->HrefValue = "";
		$this->CollegeDecisionRef->TooltipValue = "";

		// CommitteeDecision
		$this->CommitteeDecision->LinkCustomAttributes = "";
		$this->CommitteeDecision->HrefValue = "";
		$this->CommitteeDecision->TooltipValue = "";

		// CommitteeDecisionDate
		$this->CommitteeDecisionDate->LinkCustomAttributes = "";
		$this->CommitteeDecisionDate->HrefValue = "";
		$this->CommitteeDecisionDate->TooltipValue = "";

		// CommitteeDecisionRef
		$this->CommitteeDecisionRef->LinkCustomAttributes = "";
		$this->CommitteeDecisionRef->HrefValue = "";
		$this->CommitteeDecisionRef->TooltipValue = "";

		// PresidentDecision
		$this->PresidentDecision->LinkCustomAttributes = "";
		$this->PresidentDecision->HrefValue = "";
		$this->PresidentDecision->TooltipValue = "";

		// PresidentDecisionDate
		$this->PresidentDecisionDate->LinkCustomAttributes = "";
		$this->PresidentDecisionDate->HrefValue = "";
		$this->PresidentDecisionDate->TooltipValue = "";

		// PresidentDecisionRef
		$this->PresidentDecisionRef->LinkCustomAttributes = "";
		$this->PresidentDecisionRef->HrefValue = "";
		$this->PresidentDecisionRef->TooltipValue = "";

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
				if ($this->Nationality->Exportable) $Doc->ExportCaption($this->Nationality);
				if ($this->College->Exportable) $Doc->ExportCaption($this->College);
				if ($this->Department->Exportable) $Doc->ExportCaption($this->Department);
				if ($this->FacultyAffairsDate->Exportable) $Doc->ExportCaption($this->FacultyAffairsDate);
				if ($this->FacultyAffairsRef->Exportable) $Doc->ExportCaption($this->FacultyAffairsRef);
				if ($this->CollegeDecision->Exportable) $Doc->ExportCaption($this->CollegeDecision);
				if ($this->CollegeDecisionDate->Exportable) $Doc->ExportCaption($this->CollegeDecisionDate);
				if ($this->CollegeDecisionRef->Exportable) $Doc->ExportCaption($this->CollegeDecisionRef);
				if ($this->CommitteeDecision->Exportable) $Doc->ExportCaption($this->CommitteeDecision);
				if ($this->CommitteeDecisionDate->Exportable) $Doc->ExportCaption($this->CommitteeDecisionDate);
				if ($this->CommitteeDecisionRef->Exportable) $Doc->ExportCaption($this->CommitteeDecisionRef);
				if ($this->PresidentDecision->Exportable) $Doc->ExportCaption($this->PresidentDecision);
				if ($this->PresidentDecisionDate->Exportable) $Doc->ExportCaption($this->PresidentDecisionDate);
				if ($this->PresidentDecisionRef->Exportable) $Doc->ExportCaption($this->PresidentDecisionRef);
			} else {
				if ($this->ID->Exportable) $Doc->ExportCaption($this->ID);
				if ($this->Name->Exportable) $Doc->ExportCaption($this->Name);
				if ($this->Nationality->Exportable) $Doc->ExportCaption($this->Nationality);
				if ($this->College->Exportable) $Doc->ExportCaption($this->College);
				if ($this->Department->Exportable) $Doc->ExportCaption($this->Department);
				if ($this->FacultyAffairsDate->Exportable) $Doc->ExportCaption($this->FacultyAffairsDate);
				if ($this->FacultyAffairsRef->Exportable) $Doc->ExportCaption($this->FacultyAffairsRef);
				if ($this->CollegeDecision->Exportable) $Doc->ExportCaption($this->CollegeDecision);
				if ($this->CollegeDecisionDate->Exportable) $Doc->ExportCaption($this->CollegeDecisionDate);
				if ($this->CollegeDecisionRef->Exportable) $Doc->ExportCaption($this->CollegeDecisionRef);
				if ($this->CommitteeDecision->Exportable) $Doc->ExportCaption($this->CommitteeDecision);
				if ($this->CommitteeDecisionDate->Exportable) $Doc->ExportCaption($this->CommitteeDecisionDate);
				if ($this->CommitteeDecisionRef->Exportable) $Doc->ExportCaption($this->CommitteeDecisionRef);
				if ($this->PresidentDecision->Exportable) $Doc->ExportCaption($this->PresidentDecision);
				if ($this->PresidentDecisionDate->Exportable) $Doc->ExportCaption($this->PresidentDecisionDate);
				if ($this->PresidentDecisionRef->Exportable) $Doc->ExportCaption($this->PresidentDecisionRef);
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
					if ($this->Nationality->Exportable) $Doc->ExportField($this->Nationality);
					if ($this->College->Exportable) $Doc->ExportField($this->College);
					if ($this->Department->Exportable) $Doc->ExportField($this->Department);
					if ($this->FacultyAffairsDate->Exportable) $Doc->ExportField($this->FacultyAffairsDate);
					if ($this->FacultyAffairsRef->Exportable) $Doc->ExportField($this->FacultyAffairsRef);
					if ($this->CollegeDecision->Exportable) $Doc->ExportField($this->CollegeDecision);
					if ($this->CollegeDecisionDate->Exportable) $Doc->ExportField($this->CollegeDecisionDate);
					if ($this->CollegeDecisionRef->Exportable) $Doc->ExportField($this->CollegeDecisionRef);
					if ($this->CommitteeDecision->Exportable) $Doc->ExportField($this->CommitteeDecision);
					if ($this->CommitteeDecisionDate->Exportable) $Doc->ExportField($this->CommitteeDecisionDate);
					if ($this->CommitteeDecisionRef->Exportable) $Doc->ExportField($this->CommitteeDecisionRef);
					if ($this->PresidentDecision->Exportable) $Doc->ExportField($this->PresidentDecision);
					if ($this->PresidentDecisionDate->Exportable) $Doc->ExportField($this->PresidentDecisionDate);
					if ($this->PresidentDecisionRef->Exportable) $Doc->ExportField($this->PresidentDecisionRef);
				} else {
					if ($this->ID->Exportable) $Doc->ExportField($this->ID);
					if ($this->Name->Exportable) $Doc->ExportField($this->Name);
					if ($this->Nationality->Exportable) $Doc->ExportField($this->Nationality);
					if ($this->College->Exportable) $Doc->ExportField($this->College);
					if ($this->Department->Exportable) $Doc->ExportField($this->Department);
					if ($this->FacultyAffairsDate->Exportable) $Doc->ExportField($this->FacultyAffairsDate);
					if ($this->FacultyAffairsRef->Exportable) $Doc->ExportField($this->FacultyAffairsRef);
					if ($this->CollegeDecision->Exportable) $Doc->ExportField($this->CollegeDecision);
					if ($this->CollegeDecisionDate->Exportable) $Doc->ExportField($this->CollegeDecisionDate);
					if ($this->CollegeDecisionRef->Exportable) $Doc->ExportField($this->CollegeDecisionRef);
					if ($this->CommitteeDecision->Exportable) $Doc->ExportField($this->CommitteeDecision);
					if ($this->CommitteeDecisionDate->Exportable) $Doc->ExportField($this->CommitteeDecisionDate);
					if ($this->CommitteeDecisionRef->Exportable) $Doc->ExportField($this->CommitteeDecisionRef);
					if ($this->PresidentDecision->Exportable) $Doc->ExportField($this->PresidentDecision);
					if ($this->PresidentDecisionDate->Exportable) $Doc->ExportField($this->PresidentDecisionDate);
					if ($this->PresidentDecisionRef->Exportable) $Doc->ExportField($this->PresidentDecisionRef);
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
