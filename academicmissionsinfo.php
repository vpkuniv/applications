<?php

// Global variable for table object
$academicmissions = NULL;

//
// Table class for academicmissions
//
class cacademicmissions extends cTable {
	var $ID;
	var $Name;
	var $UniversityID;
	var $College;
	var $Department;
	var $StartDate;
	var $EndDate;
	var $PlaceVisited;
	var $NatureOfVisit;
	var $AttendanceOnly;
	var $PresentAPaper;
	var $Others;
	var $Participation;
	var $Summary;
	var $SuggestionRecommendation;
	var $FacultyMemberSign;
	var $DepChairmanSign;
	var $DeanSign;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'academicmissions';
		$this->TableName = 'academicmissions';
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
		$this->ID = new cField('academicmissions', 'academicmissions', 'x_ID', 'ID', '`ID`', '`ID`', 3, -1, FALSE, '`ID`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->ID->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['ID'] = &$this->ID;

		// Name
		$this->Name = new cField('academicmissions', 'academicmissions', 'x_Name', 'Name', '`Name`', '`Name`', 200, -1, FALSE, '`Name`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Name'] = &$this->Name;

		// UniversityID
		$this->UniversityID = new cField('academicmissions', 'academicmissions', 'x_UniversityID', 'UniversityID', '`UniversityID`', '`UniversityID`', 3, -1, FALSE, '`UniversityID`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->UniversityID->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['UniversityID'] = &$this->UniversityID;

		// College
		$this->College = new cField('academicmissions', 'academicmissions', 'x_College', 'College', '`College`', '`College`', 200, -1, FALSE, '`College`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['College'] = &$this->College;

		// Department
		$this->Department = new cField('academicmissions', 'academicmissions', 'x_Department', 'Department', '`Department`', '`Department`', 200, -1, FALSE, '`Department`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Department'] = &$this->Department;

		// StartDate
		$this->StartDate = new cField('academicmissions', 'academicmissions', 'x_StartDate', 'StartDate', '`StartDate`', 'DATE_FORMAT(`StartDate`, \'%d/%m/%Y\')', 133, 7, FALSE, '`StartDate`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->StartDate->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['StartDate'] = &$this->StartDate;

		// EndDate
		$this->EndDate = new cField('academicmissions', 'academicmissions', 'x_EndDate', 'EndDate', '`EndDate`', 'DATE_FORMAT(`EndDate`, \'%d/%m/%Y\')', 133, 7, FALSE, '`EndDate`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->EndDate->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['EndDate'] = &$this->EndDate;

		// PlaceVisited
		$this->PlaceVisited = new cField('academicmissions', 'academicmissions', 'x_PlaceVisited', 'PlaceVisited', '`PlaceVisited`', '`PlaceVisited`', 200, -1, FALSE, '`PlaceVisited`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['PlaceVisited'] = &$this->PlaceVisited;

		// NatureOfVisit
		$this->NatureOfVisit = new cField('academicmissions', 'academicmissions', 'x_NatureOfVisit', 'NatureOfVisit', '`NatureOfVisit`', '`NatureOfVisit`', 200, -1, FALSE, '`NatureOfVisit`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['NatureOfVisit'] = &$this->NatureOfVisit;

		// AttendanceOnly
		$this->AttendanceOnly = new cField('academicmissions', 'academicmissions', 'x_AttendanceOnly', 'AttendanceOnly', '`AttendanceOnly`', '`AttendanceOnly`', 202, -1, FALSE, '`AttendanceOnly`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['AttendanceOnly'] = &$this->AttendanceOnly;

		// PresentAPaper
		$this->PresentAPaper = new cField('academicmissions', 'academicmissions', 'x_PresentAPaper', 'PresentAPaper', '`PresentAPaper`', '`PresentAPaper`', 202, -1, FALSE, '`PresentAPaper`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['PresentAPaper'] = &$this->PresentAPaper;

		// Others
		$this->Others = new cField('academicmissions', 'academicmissions', 'x_Others', 'Others', '`Others`', '`Others`', 202, -1, FALSE, '`Others`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Others'] = &$this->Others;

		// Participation
		$this->Participation = new cField('academicmissions', 'academicmissions', 'x_Participation', 'Participation', '`Participation`', '`Participation`', 202, -1, FALSE, '`Participation`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Participation'] = &$this->Participation;

		// Summary
		$this->Summary = new cField('academicmissions', 'academicmissions', 'x_Summary', 'Summary', '`Summary`', '`Summary`', 202, -1, FALSE, '`Summary`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Summary'] = &$this->Summary;

		// SuggestionRecommendation
		$this->SuggestionRecommendation = new cField('academicmissions', 'academicmissions', 'x_SuggestionRecommendation', 'SuggestionRecommendation', '`SuggestionRecommendation`', '`SuggestionRecommendation`', 202, -1, FALSE, '`SuggestionRecommendation`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['SuggestionRecommendation'] = &$this->SuggestionRecommendation;

		// FacultyMemberSign
		$this->FacultyMemberSign = new cField('academicmissions', 'academicmissions', 'x_FacultyMemberSign', 'FacultyMemberSign', '`FacultyMemberSign`', '`FacultyMemberSign`', 202, -1, FALSE, '`FacultyMemberSign`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['FacultyMemberSign'] = &$this->FacultyMemberSign;

		// DepChairmanSign
		$this->DepChairmanSign = new cField('academicmissions', 'academicmissions', 'x_DepChairmanSign', 'DepChairmanSign', '`DepChairmanSign`', '`DepChairmanSign`', 202, -1, FALSE, '`DepChairmanSign`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['DepChairmanSign'] = &$this->DepChairmanSign;

		// DeanSign
		$this->DeanSign = new cField('academicmissions', 'academicmissions', 'x_DeanSign', 'DeanSign', '`DeanSign`', '`DeanSign`', 202, -1, FALSE, '`DeanSign`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['DeanSign'] = &$this->DeanSign;
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
		return "`academicmissions`";
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
	var $UpdateTable = "`academicmissions`";

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
			return "academicmissionslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "academicmissionslist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("academicmissionsview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("academicmissionsview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "academicmissionsadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("academicmissionsedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("academicmissionsadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("academicmissionsdelete.php", $this->UrlParm());
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
		$this->UniversityID->setDbValue($rs->fields('UniversityID'));
		$this->College->setDbValue($rs->fields('College'));
		$this->Department->setDbValue($rs->fields('Department'));
		$this->StartDate->setDbValue($rs->fields('StartDate'));
		$this->EndDate->setDbValue($rs->fields('EndDate'));
		$this->PlaceVisited->setDbValue($rs->fields('PlaceVisited'));
		$this->NatureOfVisit->setDbValue($rs->fields('NatureOfVisit'));
		$this->AttendanceOnly->setDbValue($rs->fields('AttendanceOnly'));
		$this->PresentAPaper->setDbValue($rs->fields('PresentAPaper'));
		$this->Others->setDbValue($rs->fields('Others'));
		$this->Participation->setDbValue($rs->fields('Participation'));
		$this->Summary->setDbValue($rs->fields('Summary'));
		$this->SuggestionRecommendation->setDbValue($rs->fields('SuggestionRecommendation'));
		$this->FacultyMemberSign->setDbValue($rs->fields('FacultyMemberSign'));
		$this->DepChairmanSign->setDbValue($rs->fields('DepChairmanSign'));
		$this->DeanSign->setDbValue($rs->fields('DeanSign'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security, $gsLanguage;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// ID
		// Name
		// UniversityID
		// College
		// Department
		// StartDate
		// EndDate
		// PlaceVisited
		// NatureOfVisit
		// AttendanceOnly
		// PresentAPaper
		// Others
		// Participation
		// Summary
		// SuggestionRecommendation
		// FacultyMemberSign
		// DepChairmanSign
		// DeanSign
		// ID

		$this->ID->ViewValue = $this->ID->CurrentValue;
		$this->ID->ViewCustomAttributes = "";

		// Name
		$this->Name->ViewValue = $this->Name->CurrentValue;
		$this->Name->ViewCustomAttributes = "";

		// UniversityID
		$this->UniversityID->ViewValue = $this->UniversityID->CurrentValue;
		$this->UniversityID->ViewCustomAttributes = "";

		// College
		if (strval($this->College->CurrentValue) <> "") {
			$sFilterWrk = "`CollegeID`" . ew_SearchString("=", $this->College->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT `CollegeID`, `College Name EN` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `colleges`";
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

		// StartDate
		$this->StartDate->ViewValue = $this->StartDate->CurrentValue;
		$this->StartDate->ViewValue = ew_FormatDateTime($this->StartDate->ViewValue, 7);
		$this->StartDate->ViewCustomAttributes = "";

		// EndDate
		$this->EndDate->ViewValue = $this->EndDate->CurrentValue;
		$this->EndDate->ViewValue = ew_FormatDateTime($this->EndDate->ViewValue, 7);
		$this->EndDate->ViewCustomAttributes = "";

		// PlaceVisited
		$this->PlaceVisited->ViewValue = $this->PlaceVisited->CurrentValue;
		$this->PlaceVisited->ViewCustomAttributes = "";

		// NatureOfVisit
		$this->NatureOfVisit->ViewValue = $this->NatureOfVisit->CurrentValue;
		$this->NatureOfVisit->ViewCustomAttributes = "";

		// AttendanceOnly
		if (strval($this->AttendanceOnly->CurrentValue) <> "") {
			switch ($this->AttendanceOnly->CurrentValue) {
				case $this->AttendanceOnly->FldTagValue(1):
					$this->AttendanceOnly->ViewValue = $this->AttendanceOnly->FldTagCaption(1) <> "" ? $this->AttendanceOnly->FldTagCaption(1) : $this->AttendanceOnly->CurrentValue;
					break;
				case $this->AttendanceOnly->FldTagValue(2):
					$this->AttendanceOnly->ViewValue = $this->AttendanceOnly->FldTagCaption(2) <> "" ? $this->AttendanceOnly->FldTagCaption(2) : $this->AttendanceOnly->CurrentValue;
					break;
				default:
					$this->AttendanceOnly->ViewValue = $this->AttendanceOnly->CurrentValue;
			}
		} else {
			$this->AttendanceOnly->ViewValue = NULL;
		}
		$this->AttendanceOnly->ViewCustomAttributes = "";

		// PresentAPaper
		if (strval($this->PresentAPaper->CurrentValue) <> "") {
			switch ($this->PresentAPaper->CurrentValue) {
				case $this->PresentAPaper->FldTagValue(1):
					$this->PresentAPaper->ViewValue = $this->PresentAPaper->FldTagCaption(1) <> "" ? $this->PresentAPaper->FldTagCaption(1) : $this->PresentAPaper->CurrentValue;
					break;
				case $this->PresentAPaper->FldTagValue(2):
					$this->PresentAPaper->ViewValue = $this->PresentAPaper->FldTagCaption(2) <> "" ? $this->PresentAPaper->FldTagCaption(2) : $this->PresentAPaper->CurrentValue;
					break;
				default:
					$this->PresentAPaper->ViewValue = $this->PresentAPaper->CurrentValue;
			}
		} else {
			$this->PresentAPaper->ViewValue = NULL;
		}
		$this->PresentAPaper->ViewCustomAttributes = "";

		// Others
		if (strval($this->Others->CurrentValue) <> "") {
			switch ($this->Others->CurrentValue) {
				case $this->Others->FldTagValue(1):
					$this->Others->ViewValue = $this->Others->FldTagCaption(1) <> "" ? $this->Others->FldTagCaption(1) : $this->Others->CurrentValue;
					break;
				case $this->Others->FldTagValue(2):
					$this->Others->ViewValue = $this->Others->FldTagCaption(2) <> "" ? $this->Others->FldTagCaption(2) : $this->Others->CurrentValue;
					break;
				default:
					$this->Others->ViewValue = $this->Others->CurrentValue;
			}
		} else {
			$this->Others->ViewValue = NULL;
		}
		$this->Others->ViewCustomAttributes = "";

		// Participation
		if (strval($this->Participation->CurrentValue) <> "") {
			switch ($this->Participation->CurrentValue) {
				case $this->Participation->FldTagValue(1):
					$this->Participation->ViewValue = $this->Participation->FldTagCaption(1) <> "" ? $this->Participation->FldTagCaption(1) : $this->Participation->CurrentValue;
					break;
				case $this->Participation->FldTagValue(2):
					$this->Participation->ViewValue = $this->Participation->FldTagCaption(2) <> "" ? $this->Participation->FldTagCaption(2) : $this->Participation->CurrentValue;
					break;
				default:
					$this->Participation->ViewValue = $this->Participation->CurrentValue;
			}
		} else {
			$this->Participation->ViewValue = NULL;
		}
		$this->Participation->ViewCustomAttributes = "";

		// Summary
		if (strval($this->Summary->CurrentValue) <> "") {
			switch ($this->Summary->CurrentValue) {
				case $this->Summary->FldTagValue(1):
					$this->Summary->ViewValue = $this->Summary->FldTagCaption(1) <> "" ? $this->Summary->FldTagCaption(1) : $this->Summary->CurrentValue;
					break;
				case $this->Summary->FldTagValue(2):
					$this->Summary->ViewValue = $this->Summary->FldTagCaption(2) <> "" ? $this->Summary->FldTagCaption(2) : $this->Summary->CurrentValue;
					break;
				default:
					$this->Summary->ViewValue = $this->Summary->CurrentValue;
			}
		} else {
			$this->Summary->ViewValue = NULL;
		}
		$this->Summary->ViewCustomAttributes = "";

		// SuggestionRecommendation
		if (strval($this->SuggestionRecommendation->CurrentValue) <> "") {
			switch ($this->SuggestionRecommendation->CurrentValue) {
				case $this->SuggestionRecommendation->FldTagValue(1):
					$this->SuggestionRecommendation->ViewValue = $this->SuggestionRecommendation->FldTagCaption(1) <> "" ? $this->SuggestionRecommendation->FldTagCaption(1) : $this->SuggestionRecommendation->CurrentValue;
					break;
				case $this->SuggestionRecommendation->FldTagValue(2):
					$this->SuggestionRecommendation->ViewValue = $this->SuggestionRecommendation->FldTagCaption(2) <> "" ? $this->SuggestionRecommendation->FldTagCaption(2) : $this->SuggestionRecommendation->CurrentValue;
					break;
				default:
					$this->SuggestionRecommendation->ViewValue = $this->SuggestionRecommendation->CurrentValue;
			}
		} else {
			$this->SuggestionRecommendation->ViewValue = NULL;
		}
		$this->SuggestionRecommendation->ViewCustomAttributes = "";

		// FacultyMemberSign
		if (strval($this->FacultyMemberSign->CurrentValue) <> "") {
			switch ($this->FacultyMemberSign->CurrentValue) {
				case $this->FacultyMemberSign->FldTagValue(1):
					$this->FacultyMemberSign->ViewValue = $this->FacultyMemberSign->FldTagCaption(1) <> "" ? $this->FacultyMemberSign->FldTagCaption(1) : $this->FacultyMemberSign->CurrentValue;
					break;
				case $this->FacultyMemberSign->FldTagValue(2):
					$this->FacultyMemberSign->ViewValue = $this->FacultyMemberSign->FldTagCaption(2) <> "" ? $this->FacultyMemberSign->FldTagCaption(2) : $this->FacultyMemberSign->CurrentValue;
					break;
				default:
					$this->FacultyMemberSign->ViewValue = $this->FacultyMemberSign->CurrentValue;
			}
		} else {
			$this->FacultyMemberSign->ViewValue = NULL;
		}
		$this->FacultyMemberSign->ViewCustomAttributes = "";

		// DepChairmanSign
		if (strval($this->DepChairmanSign->CurrentValue) <> "") {
			switch ($this->DepChairmanSign->CurrentValue) {
				case $this->DepChairmanSign->FldTagValue(1):
					$this->DepChairmanSign->ViewValue = $this->DepChairmanSign->FldTagCaption(1) <> "" ? $this->DepChairmanSign->FldTagCaption(1) : $this->DepChairmanSign->CurrentValue;
					break;
				case $this->DepChairmanSign->FldTagValue(2):
					$this->DepChairmanSign->ViewValue = $this->DepChairmanSign->FldTagCaption(2) <> "" ? $this->DepChairmanSign->FldTagCaption(2) : $this->DepChairmanSign->CurrentValue;
					break;
				default:
					$this->DepChairmanSign->ViewValue = $this->DepChairmanSign->CurrentValue;
			}
		} else {
			$this->DepChairmanSign->ViewValue = NULL;
		}
		$this->DepChairmanSign->ViewCustomAttributes = "";

		// DeanSign
		if (strval($this->DeanSign->CurrentValue) <> "") {
			switch ($this->DeanSign->CurrentValue) {
				case $this->DeanSign->FldTagValue(1):
					$this->DeanSign->ViewValue = $this->DeanSign->FldTagCaption(1) <> "" ? $this->DeanSign->FldTagCaption(1) : $this->DeanSign->CurrentValue;
					break;
				case $this->DeanSign->FldTagValue(2):
					$this->DeanSign->ViewValue = $this->DeanSign->FldTagCaption(2) <> "" ? $this->DeanSign->FldTagCaption(2) : $this->DeanSign->CurrentValue;
					break;
				default:
					$this->DeanSign->ViewValue = $this->DeanSign->CurrentValue;
			}
		} else {
			$this->DeanSign->ViewValue = NULL;
		}
		$this->DeanSign->ViewCustomAttributes = "";

		// ID
		$this->ID->LinkCustomAttributes = "";
		$this->ID->HrefValue = "";
		$this->ID->TooltipValue = "";

		// Name
		$this->Name->LinkCustomAttributes = "";
		$this->Name->HrefValue = "";
		$this->Name->TooltipValue = "";

		// UniversityID
		$this->UniversityID->LinkCustomAttributes = "";
		$this->UniversityID->HrefValue = "";
		$this->UniversityID->TooltipValue = "";

		// College
		$this->College->LinkCustomAttributes = "";
		$this->College->HrefValue = "";
		$this->College->TooltipValue = "";

		// Department
		$this->Department->LinkCustomAttributes = "";
		$this->Department->HrefValue = "";
		$this->Department->TooltipValue = "";

		// StartDate
		$this->StartDate->LinkCustomAttributes = "";
		$this->StartDate->HrefValue = "";
		$this->StartDate->TooltipValue = "";

		// EndDate
		$this->EndDate->LinkCustomAttributes = "";
		$this->EndDate->HrefValue = "";
		$this->EndDate->TooltipValue = "";

		// PlaceVisited
		$this->PlaceVisited->LinkCustomAttributes = "";
		$this->PlaceVisited->HrefValue = "";
		$this->PlaceVisited->TooltipValue = "";

		// NatureOfVisit
		$this->NatureOfVisit->LinkCustomAttributes = "";
		$this->NatureOfVisit->HrefValue = "";
		$this->NatureOfVisit->TooltipValue = "";

		// AttendanceOnly
		$this->AttendanceOnly->LinkCustomAttributes = "";
		$this->AttendanceOnly->HrefValue = "";
		$this->AttendanceOnly->TooltipValue = "";

		// PresentAPaper
		$this->PresentAPaper->LinkCustomAttributes = "";
		$this->PresentAPaper->HrefValue = "";
		$this->PresentAPaper->TooltipValue = "";

		// Others
		$this->Others->LinkCustomAttributes = "";
		$this->Others->HrefValue = "";
		$this->Others->TooltipValue = "";

		// Participation
		$this->Participation->LinkCustomAttributes = "";
		$this->Participation->HrefValue = "";
		$this->Participation->TooltipValue = "";

		// Summary
		$this->Summary->LinkCustomAttributes = "";
		$this->Summary->HrefValue = "";
		$this->Summary->TooltipValue = "";

		// SuggestionRecommendation
		$this->SuggestionRecommendation->LinkCustomAttributes = "";
		$this->SuggestionRecommendation->HrefValue = "";
		$this->SuggestionRecommendation->TooltipValue = "";

		// FacultyMemberSign
		$this->FacultyMemberSign->LinkCustomAttributes = "";
		$this->FacultyMemberSign->HrefValue = "";
		$this->FacultyMemberSign->TooltipValue = "";

		// DepChairmanSign
		$this->DepChairmanSign->LinkCustomAttributes = "";
		$this->DepChairmanSign->HrefValue = "";
		$this->DepChairmanSign->TooltipValue = "";

		// DeanSign
		$this->DeanSign->LinkCustomAttributes = "";
		$this->DeanSign->HrefValue = "";
		$this->DeanSign->TooltipValue = "";

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
				if ($this->UniversityID->Exportable) $Doc->ExportCaption($this->UniversityID);
				if ($this->College->Exportable) $Doc->ExportCaption($this->College);
				if ($this->Department->Exportable) $Doc->ExportCaption($this->Department);
				if ($this->StartDate->Exportable) $Doc->ExportCaption($this->StartDate);
				if ($this->EndDate->Exportable) $Doc->ExportCaption($this->EndDate);
				if ($this->PlaceVisited->Exportable) $Doc->ExportCaption($this->PlaceVisited);
				if ($this->NatureOfVisit->Exportable) $Doc->ExportCaption($this->NatureOfVisit);
				if ($this->AttendanceOnly->Exportable) $Doc->ExportCaption($this->AttendanceOnly);
				if ($this->PresentAPaper->Exportable) $Doc->ExportCaption($this->PresentAPaper);
				if ($this->Others->Exportable) $Doc->ExportCaption($this->Others);
				if ($this->Participation->Exportable) $Doc->ExportCaption($this->Participation);
				if ($this->Summary->Exportable) $Doc->ExportCaption($this->Summary);
				if ($this->SuggestionRecommendation->Exportable) $Doc->ExportCaption($this->SuggestionRecommendation);
				if ($this->FacultyMemberSign->Exportable) $Doc->ExportCaption($this->FacultyMemberSign);
				if ($this->DepChairmanSign->Exportable) $Doc->ExportCaption($this->DepChairmanSign);
				if ($this->DeanSign->Exportable) $Doc->ExportCaption($this->DeanSign);
			} else {
				if ($this->ID->Exportable) $Doc->ExportCaption($this->ID);
				if ($this->Name->Exportable) $Doc->ExportCaption($this->Name);
				if ($this->UniversityID->Exportable) $Doc->ExportCaption($this->UniversityID);
				if ($this->College->Exportable) $Doc->ExportCaption($this->College);
				if ($this->Department->Exportable) $Doc->ExportCaption($this->Department);
				if ($this->StartDate->Exportable) $Doc->ExportCaption($this->StartDate);
				if ($this->EndDate->Exportable) $Doc->ExportCaption($this->EndDate);
				if ($this->PlaceVisited->Exportable) $Doc->ExportCaption($this->PlaceVisited);
				if ($this->NatureOfVisit->Exportable) $Doc->ExportCaption($this->NatureOfVisit);
				if ($this->AttendanceOnly->Exportable) $Doc->ExportCaption($this->AttendanceOnly);
				if ($this->PresentAPaper->Exportable) $Doc->ExportCaption($this->PresentAPaper);
				if ($this->Others->Exportable) $Doc->ExportCaption($this->Others);
				if ($this->Participation->Exportable) $Doc->ExportCaption($this->Participation);
				if ($this->Summary->Exportable) $Doc->ExportCaption($this->Summary);
				if ($this->SuggestionRecommendation->Exportable) $Doc->ExportCaption($this->SuggestionRecommendation);
				if ($this->FacultyMemberSign->Exportable) $Doc->ExportCaption($this->FacultyMemberSign);
				if ($this->DepChairmanSign->Exportable) $Doc->ExportCaption($this->DepChairmanSign);
				if ($this->DeanSign->Exportable) $Doc->ExportCaption($this->DeanSign);
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
					if ($this->UniversityID->Exportable) $Doc->ExportField($this->UniversityID);
					if ($this->College->Exportable) $Doc->ExportField($this->College);
					if ($this->Department->Exportable) $Doc->ExportField($this->Department);
					if ($this->StartDate->Exportable) $Doc->ExportField($this->StartDate);
					if ($this->EndDate->Exportable) $Doc->ExportField($this->EndDate);
					if ($this->PlaceVisited->Exportable) $Doc->ExportField($this->PlaceVisited);
					if ($this->NatureOfVisit->Exportable) $Doc->ExportField($this->NatureOfVisit);
					if ($this->AttendanceOnly->Exportable) $Doc->ExportField($this->AttendanceOnly);
					if ($this->PresentAPaper->Exportable) $Doc->ExportField($this->PresentAPaper);
					if ($this->Others->Exportable) $Doc->ExportField($this->Others);
					if ($this->Participation->Exportable) $Doc->ExportField($this->Participation);
					if ($this->Summary->Exportable) $Doc->ExportField($this->Summary);
					if ($this->SuggestionRecommendation->Exportable) $Doc->ExportField($this->SuggestionRecommendation);
					if ($this->FacultyMemberSign->Exportable) $Doc->ExportField($this->FacultyMemberSign);
					if ($this->DepChairmanSign->Exportable) $Doc->ExportField($this->DepChairmanSign);
					if ($this->DeanSign->Exportable) $Doc->ExportField($this->DeanSign);
				} else {
					if ($this->ID->Exportable) $Doc->ExportField($this->ID);
					if ($this->Name->Exportable) $Doc->ExportField($this->Name);
					if ($this->UniversityID->Exportable) $Doc->ExportField($this->UniversityID);
					if ($this->College->Exportable) $Doc->ExportField($this->College);
					if ($this->Department->Exportable) $Doc->ExportField($this->Department);
					if ($this->StartDate->Exportable) $Doc->ExportField($this->StartDate);
					if ($this->EndDate->Exportable) $Doc->ExportField($this->EndDate);
					if ($this->PlaceVisited->Exportable) $Doc->ExportField($this->PlaceVisited);
					if ($this->NatureOfVisit->Exportable) $Doc->ExportField($this->NatureOfVisit);
					if ($this->AttendanceOnly->Exportable) $Doc->ExportField($this->AttendanceOnly);
					if ($this->PresentAPaper->Exportable) $Doc->ExportField($this->PresentAPaper);
					if ($this->Others->Exportable) $Doc->ExportField($this->Others);
					if ($this->Participation->Exportable) $Doc->ExportField($this->Participation);
					if ($this->Summary->Exportable) $Doc->ExportField($this->Summary);
					if ($this->SuggestionRecommendation->Exportable) $Doc->ExportField($this->SuggestionRecommendation);
					if ($this->FacultyMemberSign->Exportable) $Doc->ExportField($this->FacultyMemberSign);
					if ($this->DepChairmanSign->Exportable) $Doc->ExportField($this->DepChairmanSign);
					if ($this->DeanSign->Exportable) $Doc->ExportField($this->DeanSign);
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
