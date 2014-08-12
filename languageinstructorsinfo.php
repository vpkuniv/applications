<?php

// Global variable for table object
$languageinstructors = NULL;

//
// Table class for languageinstructors
//
class clanguageinstructors extends cTable {
	var $ID;
	var $ApplicantName;
	var $Nationality;
	var $_Language;
	var $College1;
	var $College1SentDate;
	var $College1Status;
	var $College1ReplyDate;
	var $College2;
	var $College2SentDate;
	var $College2Status;
	var $College2ReplyDate;
	var $College3;
	var $College3SentDate;
	var $College3Status;
	var $College3ReplyDate;
	var $CommitteDecision;
	var $CommitteDecisionDate;
	var $CommitteRefNo;
	var $PreidentsDecision;
	var $PreidentsDecisionDate;
	var $PreidentsRefNo;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'languageinstructors';
		$this->TableName = 'languageinstructors';
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
		$this->ID = new cField('languageinstructors', 'languageinstructors', 'x_ID', 'ID', '`ID`', '`ID`', 3, -1, FALSE, '`ID`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->ID->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['ID'] = &$this->ID;

		// ApplicantName
		$this->ApplicantName = new cField('languageinstructors', 'languageinstructors', 'x_ApplicantName', 'ApplicantName', '`ApplicantName`', '`ApplicantName`', 200, -1, FALSE, '`ApplicantName`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ApplicantName'] = &$this->ApplicantName;

		// Nationality
		$this->Nationality = new cField('languageinstructors', 'languageinstructors', 'x_Nationality', 'Nationality', '`Nationality`', '`Nationality`', 200, -1, FALSE, '`Nationality`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Nationality'] = &$this->Nationality;

		// Language
		$this->_Language = new cField('languageinstructors', 'languageinstructors', 'x__Language', 'Language', '`Language`', '`Language`', 202, -1, FALSE, '`Language`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Language'] = &$this->_Language;

		// College1
		$this->College1 = new cField('languageinstructors', 'languageinstructors', 'x_College1', 'College1', '`College1`', '`College1`', 200, -1, FALSE, '`College1`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['College1'] = &$this->College1;

		// College1SentDate
		$this->College1SentDate = new cField('languageinstructors', 'languageinstructors', 'x_College1SentDate', 'College1SentDate', '`College1SentDate`', 'DATE_FORMAT(`College1SentDate`, \'%d/%m/%Y\')', 133, 7, FALSE, '`College1SentDate`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->College1SentDate->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['College1SentDate'] = &$this->College1SentDate;

		// College1Status
		$this->College1Status = new cField('languageinstructors', 'languageinstructors', 'x_College1Status', 'College1Status', '`College1Status`', '`College1Status`', 202, -1, FALSE, '`College1Status`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['College1Status'] = &$this->College1Status;

		// College1ReplyDate
		$this->College1ReplyDate = new cField('languageinstructors', 'languageinstructors', 'x_College1ReplyDate', 'College1ReplyDate', '`College1ReplyDate`', 'DATE_FORMAT(`College1ReplyDate`, \'%d/%m/%Y\')', 133, 7, FALSE, '`College1ReplyDate`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->College1ReplyDate->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['College1ReplyDate'] = &$this->College1ReplyDate;

		// College2
		$this->College2 = new cField('languageinstructors', 'languageinstructors', 'x_College2', 'College2', '`College2`', '`College2`', 200, -1, FALSE, '`College2`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['College2'] = &$this->College2;

		// College2SentDate
		$this->College2SentDate = new cField('languageinstructors', 'languageinstructors', 'x_College2SentDate', 'College2SentDate', '`College2SentDate`', 'DATE_FORMAT(`College2SentDate`, \'%d/%m/%Y\')', 133, 7, FALSE, '`College2SentDate`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->College2SentDate->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['College2SentDate'] = &$this->College2SentDate;

		// College2Status
		$this->College2Status = new cField('languageinstructors', 'languageinstructors', 'x_College2Status', 'College2Status', '`College2Status`', '`College2Status`', 202, -1, FALSE, '`College2Status`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['College2Status'] = &$this->College2Status;

		// College2ReplyDate
		$this->College2ReplyDate = new cField('languageinstructors', 'languageinstructors', 'x_College2ReplyDate', 'College2ReplyDate', '`College2ReplyDate`', 'DATE_FORMAT(`College2ReplyDate`, \'%d/%m/%Y\')', 133, 7, FALSE, '`College2ReplyDate`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->College2ReplyDate->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['College2ReplyDate'] = &$this->College2ReplyDate;

		// College3
		$this->College3 = new cField('languageinstructors', 'languageinstructors', 'x_College3', 'College3', '`College3`', '`College3`', 200, -1, FALSE, '`College3`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['College3'] = &$this->College3;

		// College3SentDate
		$this->College3SentDate = new cField('languageinstructors', 'languageinstructors', 'x_College3SentDate', 'College3SentDate', '`College3SentDate`', 'DATE_FORMAT(`College3SentDate`, \'%d/%m/%Y\')', 133, 7, FALSE, '`College3SentDate`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->College3SentDate->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['College3SentDate'] = &$this->College3SentDate;

		// College3Status
		$this->College3Status = new cField('languageinstructors', 'languageinstructors', 'x_College3Status', 'College3Status', '`College3Status`', '`College3Status`', 202, -1, FALSE, '`College3Status`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['College3Status'] = &$this->College3Status;

		// College3ReplyDate
		$this->College3ReplyDate = new cField('languageinstructors', 'languageinstructors', 'x_College3ReplyDate', 'College3ReplyDate', '`College3ReplyDate`', 'DATE_FORMAT(`College3ReplyDate`, \'%d/%m/%Y\')', 133, 7, FALSE, '`College3ReplyDate`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->College3ReplyDate->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['College3ReplyDate'] = &$this->College3ReplyDate;

		// CommitteDecision
		$this->CommitteDecision = new cField('languageinstructors', 'languageinstructors', 'x_CommitteDecision', 'CommitteDecision', '`CommitteDecision`', '`CommitteDecision`', 202, -1, FALSE, '`CommitteDecision`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['CommitteDecision'] = &$this->CommitteDecision;

		// CommitteDecisionDate
		$this->CommitteDecisionDate = new cField('languageinstructors', 'languageinstructors', 'x_CommitteDecisionDate', 'CommitteDecisionDate', '`CommitteDecisionDate`', 'DATE_FORMAT(`CommitteDecisionDate`, \'%d/%m/%Y\')', 133, 7, FALSE, '`CommitteDecisionDate`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->CommitteDecisionDate->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['CommitteDecisionDate'] = &$this->CommitteDecisionDate;

		// CommitteRefNo
		$this->CommitteRefNo = new cField('languageinstructors', 'languageinstructors', 'x_CommitteRefNo', 'CommitteRefNo', '`CommitteRefNo`', '`CommitteRefNo`', 3, -1, FALSE, '`CommitteRefNo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->CommitteRefNo->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['CommitteRefNo'] = &$this->CommitteRefNo;

		// PreidentsDecision
		$this->PreidentsDecision = new cField('languageinstructors', 'languageinstructors', 'x_PreidentsDecision', 'PreidentsDecision', '`PreidentsDecision`', '`PreidentsDecision`', 202, -1, FALSE, '`PreidentsDecision`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['PreidentsDecision'] = &$this->PreidentsDecision;

		// PreidentsDecisionDate
		$this->PreidentsDecisionDate = new cField('languageinstructors', 'languageinstructors', 'x_PreidentsDecisionDate', 'PreidentsDecisionDate', '`PreidentsDecisionDate`', 'DATE_FORMAT(`PreidentsDecisionDate`, \'%d/%m/%Y\')', 133, 7, FALSE, '`PreidentsDecisionDate`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->PreidentsDecisionDate->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['PreidentsDecisionDate'] = &$this->PreidentsDecisionDate;

		// PreidentsRefNo
		$this->PreidentsRefNo = new cField('languageinstructors', 'languageinstructors', 'x_PreidentsRefNo', 'PreidentsRefNo', '`PreidentsRefNo`', '`PreidentsRefNo`', 3, -1, FALSE, '`PreidentsRefNo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->PreidentsRefNo->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['PreidentsRefNo'] = &$this->PreidentsRefNo;
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
		return "`languageinstructors`";
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
	var $UpdateTable = "`languageinstructors`";

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
			return "languageinstructorslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "languageinstructorslist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("languageinstructorsview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("languageinstructorsview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "languageinstructorsadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("languageinstructorsedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("languageinstructorsadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("languageinstructorsdelete.php", $this->UrlParm());
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
		$this->ApplicantName->setDbValue($rs->fields('ApplicantName'));
		$this->Nationality->setDbValue($rs->fields('Nationality'));
		$this->_Language->setDbValue($rs->fields('Language'));
		$this->College1->setDbValue($rs->fields('College1'));
		$this->College1SentDate->setDbValue($rs->fields('College1SentDate'));
		$this->College1Status->setDbValue($rs->fields('College1Status'));
		$this->College1ReplyDate->setDbValue($rs->fields('College1ReplyDate'));
		$this->College2->setDbValue($rs->fields('College2'));
		$this->College2SentDate->setDbValue($rs->fields('College2SentDate'));
		$this->College2Status->setDbValue($rs->fields('College2Status'));
		$this->College2ReplyDate->setDbValue($rs->fields('College2ReplyDate'));
		$this->College3->setDbValue($rs->fields('College3'));
		$this->College3SentDate->setDbValue($rs->fields('College3SentDate'));
		$this->College3Status->setDbValue($rs->fields('College3Status'));
		$this->College3ReplyDate->setDbValue($rs->fields('College3ReplyDate'));
		$this->CommitteDecision->setDbValue($rs->fields('CommitteDecision'));
		$this->CommitteDecisionDate->setDbValue($rs->fields('CommitteDecisionDate'));
		$this->CommitteRefNo->setDbValue($rs->fields('CommitteRefNo'));
		$this->PreidentsDecision->setDbValue($rs->fields('PreidentsDecision'));
		$this->PreidentsDecisionDate->setDbValue($rs->fields('PreidentsDecisionDate'));
		$this->PreidentsRefNo->setDbValue($rs->fields('PreidentsRefNo'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security, $gsLanguage;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// ID
		// ApplicantName
		// Nationality
		// Language
		// College1
		// College1SentDate
		// College1Status
		// College1ReplyDate
		// College2
		// College2SentDate
		// College2Status
		// College2ReplyDate
		// College3
		// College3SentDate
		// College3Status
		// College3ReplyDate
		// CommitteDecision
		// CommitteDecisionDate
		// CommitteRefNo
		// PreidentsDecision
		// PreidentsDecisionDate
		// PreidentsRefNo
		// ID

		$this->ID->ViewValue = $this->ID->CurrentValue;
		$this->ID->ViewCustomAttributes = "";

		// ApplicantName
		$this->ApplicantName->ViewValue = $this->ApplicantName->CurrentValue;
		$this->ApplicantName->ViewCustomAttributes = "";

		// Nationality
		$this->Nationality->ViewValue = $this->Nationality->CurrentValue;
		$this->Nationality->ViewCustomAttributes = "";

		// Language
		if (strval($this->_Language->CurrentValue) <> "") {
			switch ($this->_Language->CurrentValue) {
				case $this->_Language->FldTagValue(1):
					$this->_Language->ViewValue = $this->_Language->FldTagCaption(1) <> "" ? $this->_Language->FldTagCaption(1) : $this->_Language->CurrentValue;
					break;
				case $this->_Language->FldTagValue(2):
					$this->_Language->ViewValue = $this->_Language->FldTagCaption(2) <> "" ? $this->_Language->FldTagCaption(2) : $this->_Language->CurrentValue;
					break;
				case $this->_Language->FldTagValue(3):
					$this->_Language->ViewValue = $this->_Language->FldTagCaption(3) <> "" ? $this->_Language->FldTagCaption(3) : $this->_Language->CurrentValue;
					break;
				default:
					$this->_Language->ViewValue = $this->_Language->CurrentValue;
			}
		} else {
			$this->_Language->ViewValue = NULL;
		}
		$this->_Language->ViewCustomAttributes = "";

		// College1
		if (strval($this->College1->CurrentValue) <> "") {
			$sFilterWrk = "`CollegeID`" . ew_SearchString("=", $this->College1->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT `CollegeID`, `College Name EN` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `colleges`";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->College1, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->College1->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->College1->ViewValue = $this->College1->CurrentValue;
			}
		} else {
			$this->College1->ViewValue = NULL;
		}
		$this->College1->ViewCustomAttributes = "";

		// College1SentDate
		$this->College1SentDate->ViewValue = $this->College1SentDate->CurrentValue;
		$this->College1SentDate->ViewValue = ew_FormatDateTime($this->College1SentDate->ViewValue, 7);
		$this->College1SentDate->ViewCustomAttributes = "";

		// College1Status
		if (strval($this->College1Status->CurrentValue) <> "") {
			switch ($this->College1Status->CurrentValue) {
				case $this->College1Status->FldTagValue(1):
					$this->College1Status->ViewValue = $this->College1Status->FldTagCaption(1) <> "" ? $this->College1Status->FldTagCaption(1) : $this->College1Status->CurrentValue;
					break;
				case $this->College1Status->FldTagValue(2):
					$this->College1Status->ViewValue = $this->College1Status->FldTagCaption(2) <> "" ? $this->College1Status->FldTagCaption(2) : $this->College1Status->CurrentValue;
					break;
				default:
					$this->College1Status->ViewValue = $this->College1Status->CurrentValue;
			}
		} else {
			$this->College1Status->ViewValue = NULL;
		}
		$this->College1Status->ViewCustomAttributes = "";

		// College1ReplyDate
		$this->College1ReplyDate->ViewValue = $this->College1ReplyDate->CurrentValue;
		$this->College1ReplyDate->ViewValue = ew_FormatDateTime($this->College1ReplyDate->ViewValue, 7);
		$this->College1ReplyDate->ViewCustomAttributes = "";

		// College2
		if (strval($this->College2->CurrentValue) <> "") {
			$sFilterWrk = "`CollegeID`" . ew_SearchString("=", $this->College2->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT `CollegeID`, `College Name EN` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `colleges`";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->College2, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->College2->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->College2->ViewValue = $this->College2->CurrentValue;
			}
		} else {
			$this->College2->ViewValue = NULL;
		}
		$this->College2->ViewCustomAttributes = "";

		// College2SentDate
		$this->College2SentDate->ViewValue = $this->College2SentDate->CurrentValue;
		$this->College2SentDate->ViewValue = ew_FormatDateTime($this->College2SentDate->ViewValue, 7);
		$this->College2SentDate->ViewCustomAttributes = "";

		// College2Status
		if (strval($this->College2Status->CurrentValue) <> "") {
			switch ($this->College2Status->CurrentValue) {
				case $this->College2Status->FldTagValue(1):
					$this->College2Status->ViewValue = $this->College2Status->FldTagCaption(1) <> "" ? $this->College2Status->FldTagCaption(1) : $this->College2Status->CurrentValue;
					break;
				case $this->College2Status->FldTagValue(2):
					$this->College2Status->ViewValue = $this->College2Status->FldTagCaption(2) <> "" ? $this->College2Status->FldTagCaption(2) : $this->College2Status->CurrentValue;
					break;
				default:
					$this->College2Status->ViewValue = $this->College2Status->CurrentValue;
			}
		} else {
			$this->College2Status->ViewValue = NULL;
		}
		$this->College2Status->ViewCustomAttributes = "";

		// College2ReplyDate
		$this->College2ReplyDate->ViewValue = $this->College2ReplyDate->CurrentValue;
		$this->College2ReplyDate->ViewValue = ew_FormatDateTime($this->College2ReplyDate->ViewValue, 7);
		$this->College2ReplyDate->ViewCustomAttributes = "";

		// College3
		if (strval($this->College3->CurrentValue) <> "") {
			$sFilterWrk = "`CollegeID`" . ew_SearchString("=", $this->College3->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT `CollegeID`, `College Name EN` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `colleges`";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->College3, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->College3->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->College3->ViewValue = $this->College3->CurrentValue;
			}
		} else {
			$this->College3->ViewValue = NULL;
		}
		$this->College3->ViewCustomAttributes = "";

		// College3SentDate
		$this->College3SentDate->ViewValue = $this->College3SentDate->CurrentValue;
		$this->College3SentDate->ViewValue = ew_FormatDateTime($this->College3SentDate->ViewValue, 7);
		$this->College3SentDate->ViewCustomAttributes = "";

		// College3Status
		if (strval($this->College3Status->CurrentValue) <> "") {
			switch ($this->College3Status->CurrentValue) {
				case $this->College3Status->FldTagValue(1):
					$this->College3Status->ViewValue = $this->College3Status->FldTagCaption(1) <> "" ? $this->College3Status->FldTagCaption(1) : $this->College3Status->CurrentValue;
					break;
				case $this->College3Status->FldTagValue(2):
					$this->College3Status->ViewValue = $this->College3Status->FldTagCaption(2) <> "" ? $this->College3Status->FldTagCaption(2) : $this->College3Status->CurrentValue;
					break;
				default:
					$this->College3Status->ViewValue = $this->College3Status->CurrentValue;
			}
		} else {
			$this->College3Status->ViewValue = NULL;
		}
		$this->College3Status->ViewCustomAttributes = "";

		// College3ReplyDate
		$this->College3ReplyDate->ViewValue = $this->College3ReplyDate->CurrentValue;
		$this->College3ReplyDate->ViewValue = ew_FormatDateTime($this->College3ReplyDate->ViewValue, 7);
		$this->College3ReplyDate->ViewCustomAttributes = "";

		// CommitteDecision
		if (strval($this->CommitteDecision->CurrentValue) <> "") {
			switch ($this->CommitteDecision->CurrentValue) {
				case $this->CommitteDecision->FldTagValue(1):
					$this->CommitteDecision->ViewValue = $this->CommitteDecision->FldTagCaption(1) <> "" ? $this->CommitteDecision->FldTagCaption(1) : $this->CommitteDecision->CurrentValue;
					break;
				case $this->CommitteDecision->FldTagValue(2):
					$this->CommitteDecision->ViewValue = $this->CommitteDecision->FldTagCaption(2) <> "" ? $this->CommitteDecision->FldTagCaption(2) : $this->CommitteDecision->CurrentValue;
					break;
				default:
					$this->CommitteDecision->ViewValue = $this->CommitteDecision->CurrentValue;
			}
		} else {
			$this->CommitteDecision->ViewValue = NULL;
		}
		$this->CommitteDecision->ViewCustomAttributes = "";

		// CommitteDecisionDate
		$this->CommitteDecisionDate->ViewValue = $this->CommitteDecisionDate->CurrentValue;
		$this->CommitteDecisionDate->ViewValue = ew_FormatDateTime($this->CommitteDecisionDate->ViewValue, 7);
		$this->CommitteDecisionDate->ViewCustomAttributes = "";

		// CommitteRefNo
		$this->CommitteRefNo->ViewValue = $this->CommitteRefNo->CurrentValue;
		$this->CommitteRefNo->ViewCustomAttributes = "";

		// PreidentsDecision
		if (strval($this->PreidentsDecision->CurrentValue) <> "") {
			switch ($this->PreidentsDecision->CurrentValue) {
				case $this->PreidentsDecision->FldTagValue(1):
					$this->PreidentsDecision->ViewValue = $this->PreidentsDecision->FldTagCaption(1) <> "" ? $this->PreidentsDecision->FldTagCaption(1) : $this->PreidentsDecision->CurrentValue;
					break;
				case $this->PreidentsDecision->FldTagValue(2):
					$this->PreidentsDecision->ViewValue = $this->PreidentsDecision->FldTagCaption(2) <> "" ? $this->PreidentsDecision->FldTagCaption(2) : $this->PreidentsDecision->CurrentValue;
					break;
				default:
					$this->PreidentsDecision->ViewValue = $this->PreidentsDecision->CurrentValue;
			}
		} else {
			$this->PreidentsDecision->ViewValue = NULL;
		}
		$this->PreidentsDecision->ViewCustomAttributes = "";

		// PreidentsDecisionDate
		$this->PreidentsDecisionDate->ViewValue = $this->PreidentsDecisionDate->CurrentValue;
		$this->PreidentsDecisionDate->ViewValue = ew_FormatDateTime($this->PreidentsDecisionDate->ViewValue, 7);
		$this->PreidentsDecisionDate->ViewCustomAttributes = "";

		// PreidentsRefNo
		$this->PreidentsRefNo->ViewValue = $this->PreidentsRefNo->CurrentValue;
		$this->PreidentsRefNo->ViewCustomAttributes = "";

		// ID
		$this->ID->LinkCustomAttributes = "";
		$this->ID->HrefValue = "";
		$this->ID->TooltipValue = "";

		// ApplicantName
		$this->ApplicantName->LinkCustomAttributes = "";
		$this->ApplicantName->HrefValue = "";
		$this->ApplicantName->TooltipValue = "";

		// Nationality
		$this->Nationality->LinkCustomAttributes = "";
		$this->Nationality->HrefValue = "";
		$this->Nationality->TooltipValue = "";

		// Language
		$this->_Language->LinkCustomAttributes = "";
		$this->_Language->HrefValue = "";
		$this->_Language->TooltipValue = "";

		// College1
		$this->College1->LinkCustomAttributes = "";
		$this->College1->HrefValue = "";
		$this->College1->TooltipValue = "";

		// College1SentDate
		$this->College1SentDate->LinkCustomAttributes = "";
		$this->College1SentDate->HrefValue = "";
		$this->College1SentDate->TooltipValue = "";

		// College1Status
		$this->College1Status->LinkCustomAttributes = "";
		$this->College1Status->HrefValue = "";
		$this->College1Status->TooltipValue = "";

		// College1ReplyDate
		$this->College1ReplyDate->LinkCustomAttributes = "";
		$this->College1ReplyDate->HrefValue = "";
		$this->College1ReplyDate->TooltipValue = "";

		// College2
		$this->College2->LinkCustomAttributes = "";
		$this->College2->HrefValue = "";
		$this->College2->TooltipValue = "";

		// College2SentDate
		$this->College2SentDate->LinkCustomAttributes = "";
		$this->College2SentDate->HrefValue = "";
		$this->College2SentDate->TooltipValue = "";

		// College2Status
		$this->College2Status->LinkCustomAttributes = "";
		$this->College2Status->HrefValue = "";
		$this->College2Status->TooltipValue = "";

		// College2ReplyDate
		$this->College2ReplyDate->LinkCustomAttributes = "";
		$this->College2ReplyDate->HrefValue = "";
		$this->College2ReplyDate->TooltipValue = "";

		// College3
		$this->College3->LinkCustomAttributes = "";
		$this->College3->HrefValue = "";
		$this->College3->TooltipValue = "";

		// College3SentDate
		$this->College3SentDate->LinkCustomAttributes = "";
		$this->College3SentDate->HrefValue = "";
		$this->College3SentDate->TooltipValue = "";

		// College3Status
		$this->College3Status->LinkCustomAttributes = "";
		$this->College3Status->HrefValue = "";
		$this->College3Status->TooltipValue = "";

		// College3ReplyDate
		$this->College3ReplyDate->LinkCustomAttributes = "";
		$this->College3ReplyDate->HrefValue = "";
		$this->College3ReplyDate->TooltipValue = "";

		// CommitteDecision
		$this->CommitteDecision->LinkCustomAttributes = "";
		$this->CommitteDecision->HrefValue = "";
		$this->CommitteDecision->TooltipValue = "";

		// CommitteDecisionDate
		$this->CommitteDecisionDate->LinkCustomAttributes = "";
		$this->CommitteDecisionDate->HrefValue = "";
		$this->CommitteDecisionDate->TooltipValue = "";

		// CommitteRefNo
		$this->CommitteRefNo->LinkCustomAttributes = "";
		$this->CommitteRefNo->HrefValue = "";
		$this->CommitteRefNo->TooltipValue = "";

		// PreidentsDecision
		$this->PreidentsDecision->LinkCustomAttributes = "";
		$this->PreidentsDecision->HrefValue = "";
		$this->PreidentsDecision->TooltipValue = "";

		// PreidentsDecisionDate
		$this->PreidentsDecisionDate->LinkCustomAttributes = "";
		$this->PreidentsDecisionDate->HrefValue = "";
		$this->PreidentsDecisionDate->TooltipValue = "";

		// PreidentsRefNo
		$this->PreidentsRefNo->LinkCustomAttributes = "";
		$this->PreidentsRefNo->HrefValue = "";
		$this->PreidentsRefNo->TooltipValue = "";

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
				if ($this->ApplicantName->Exportable) $Doc->ExportCaption($this->ApplicantName);
				if ($this->Nationality->Exportable) $Doc->ExportCaption($this->Nationality);
				if ($this->_Language->Exportable) $Doc->ExportCaption($this->_Language);
				if ($this->College1->Exportable) $Doc->ExportCaption($this->College1);
				if ($this->College1SentDate->Exportable) $Doc->ExportCaption($this->College1SentDate);
				if ($this->College1Status->Exportable) $Doc->ExportCaption($this->College1Status);
				if ($this->College1ReplyDate->Exportable) $Doc->ExportCaption($this->College1ReplyDate);
				if ($this->College2->Exportable) $Doc->ExportCaption($this->College2);
				if ($this->College2SentDate->Exportable) $Doc->ExportCaption($this->College2SentDate);
				if ($this->College2Status->Exportable) $Doc->ExportCaption($this->College2Status);
				if ($this->College2ReplyDate->Exportable) $Doc->ExportCaption($this->College2ReplyDate);
				if ($this->College3->Exportable) $Doc->ExportCaption($this->College3);
				if ($this->College3SentDate->Exportable) $Doc->ExportCaption($this->College3SentDate);
				if ($this->College3Status->Exportable) $Doc->ExportCaption($this->College3Status);
				if ($this->College3ReplyDate->Exportable) $Doc->ExportCaption($this->College3ReplyDate);
				if ($this->CommitteDecision->Exportable) $Doc->ExportCaption($this->CommitteDecision);
				if ($this->CommitteDecisionDate->Exportable) $Doc->ExportCaption($this->CommitteDecisionDate);
				if ($this->CommitteRefNo->Exportable) $Doc->ExportCaption($this->CommitteRefNo);
				if ($this->PreidentsDecision->Exportable) $Doc->ExportCaption($this->PreidentsDecision);
				if ($this->PreidentsDecisionDate->Exportable) $Doc->ExportCaption($this->PreidentsDecisionDate);
				if ($this->PreidentsRefNo->Exportable) $Doc->ExportCaption($this->PreidentsRefNo);
			} else {
				if ($this->ID->Exportable) $Doc->ExportCaption($this->ID);
				if ($this->ApplicantName->Exportable) $Doc->ExportCaption($this->ApplicantName);
				if ($this->Nationality->Exportable) $Doc->ExportCaption($this->Nationality);
				if ($this->_Language->Exportable) $Doc->ExportCaption($this->_Language);
				if ($this->College1->Exportable) $Doc->ExportCaption($this->College1);
				if ($this->College1SentDate->Exportable) $Doc->ExportCaption($this->College1SentDate);
				if ($this->College1Status->Exportable) $Doc->ExportCaption($this->College1Status);
				if ($this->College1ReplyDate->Exportable) $Doc->ExportCaption($this->College1ReplyDate);
				if ($this->College2->Exportable) $Doc->ExportCaption($this->College2);
				if ($this->College2SentDate->Exportable) $Doc->ExportCaption($this->College2SentDate);
				if ($this->College2Status->Exportable) $Doc->ExportCaption($this->College2Status);
				if ($this->College2ReplyDate->Exportable) $Doc->ExportCaption($this->College2ReplyDate);
				if ($this->College3->Exportable) $Doc->ExportCaption($this->College3);
				if ($this->College3SentDate->Exportable) $Doc->ExportCaption($this->College3SentDate);
				if ($this->College3Status->Exportable) $Doc->ExportCaption($this->College3Status);
				if ($this->College3ReplyDate->Exportable) $Doc->ExportCaption($this->College3ReplyDate);
				if ($this->CommitteDecision->Exportable) $Doc->ExportCaption($this->CommitteDecision);
				if ($this->CommitteDecisionDate->Exportable) $Doc->ExportCaption($this->CommitteDecisionDate);
				if ($this->CommitteRefNo->Exportable) $Doc->ExportCaption($this->CommitteRefNo);
				if ($this->PreidentsDecision->Exportable) $Doc->ExportCaption($this->PreidentsDecision);
				if ($this->PreidentsDecisionDate->Exportable) $Doc->ExportCaption($this->PreidentsDecisionDate);
				if ($this->PreidentsRefNo->Exportable) $Doc->ExportCaption($this->PreidentsRefNo);
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
					if ($this->ApplicantName->Exportable) $Doc->ExportField($this->ApplicantName);
					if ($this->Nationality->Exportable) $Doc->ExportField($this->Nationality);
					if ($this->_Language->Exportable) $Doc->ExportField($this->_Language);
					if ($this->College1->Exportable) $Doc->ExportField($this->College1);
					if ($this->College1SentDate->Exportable) $Doc->ExportField($this->College1SentDate);
					if ($this->College1Status->Exportable) $Doc->ExportField($this->College1Status);
					if ($this->College1ReplyDate->Exportable) $Doc->ExportField($this->College1ReplyDate);
					if ($this->College2->Exportable) $Doc->ExportField($this->College2);
					if ($this->College2SentDate->Exportable) $Doc->ExportField($this->College2SentDate);
					if ($this->College2Status->Exportable) $Doc->ExportField($this->College2Status);
					if ($this->College2ReplyDate->Exportable) $Doc->ExportField($this->College2ReplyDate);
					if ($this->College3->Exportable) $Doc->ExportField($this->College3);
					if ($this->College3SentDate->Exportable) $Doc->ExportField($this->College3SentDate);
					if ($this->College3Status->Exportable) $Doc->ExportField($this->College3Status);
					if ($this->College3ReplyDate->Exportable) $Doc->ExportField($this->College3ReplyDate);
					if ($this->CommitteDecision->Exportable) $Doc->ExportField($this->CommitteDecision);
					if ($this->CommitteDecisionDate->Exportable) $Doc->ExportField($this->CommitteDecisionDate);
					if ($this->CommitteRefNo->Exportable) $Doc->ExportField($this->CommitteRefNo);
					if ($this->PreidentsDecision->Exportable) $Doc->ExportField($this->PreidentsDecision);
					if ($this->PreidentsDecisionDate->Exportable) $Doc->ExportField($this->PreidentsDecisionDate);
					if ($this->PreidentsRefNo->Exportable) $Doc->ExportField($this->PreidentsRefNo);
				} else {
					if ($this->ID->Exportable) $Doc->ExportField($this->ID);
					if ($this->ApplicantName->Exportable) $Doc->ExportField($this->ApplicantName);
					if ($this->Nationality->Exportable) $Doc->ExportField($this->Nationality);
					if ($this->_Language->Exportable) $Doc->ExportField($this->_Language);
					if ($this->College1->Exportable) $Doc->ExportField($this->College1);
					if ($this->College1SentDate->Exportable) $Doc->ExportField($this->College1SentDate);
					if ($this->College1Status->Exportable) $Doc->ExportField($this->College1Status);
					if ($this->College1ReplyDate->Exportable) $Doc->ExportField($this->College1ReplyDate);
					if ($this->College2->Exportable) $Doc->ExportField($this->College2);
					if ($this->College2SentDate->Exportable) $Doc->ExportField($this->College2SentDate);
					if ($this->College2Status->Exportable) $Doc->ExportField($this->College2Status);
					if ($this->College2ReplyDate->Exportable) $Doc->ExportField($this->College2ReplyDate);
					if ($this->College3->Exportable) $Doc->ExportField($this->College3);
					if ($this->College3SentDate->Exportable) $Doc->ExportField($this->College3SentDate);
					if ($this->College3Status->Exportable) $Doc->ExportField($this->College3Status);
					if ($this->College3ReplyDate->Exportable) $Doc->ExportField($this->College3ReplyDate);
					if ($this->CommitteDecision->Exportable) $Doc->ExportField($this->CommitteDecision);
					if ($this->CommitteDecisionDate->Exportable) $Doc->ExportField($this->CommitteDecisionDate);
					if ($this->CommitteRefNo->Exportable) $Doc->ExportField($this->CommitteRefNo);
					if ($this->PreidentsDecision->Exportable) $Doc->ExportField($this->PreidentsDecision);
					if ($this->PreidentsDecisionDate->Exportable) $Doc->ExportField($this->PreidentsDecisionDate);
					if ($this->PreidentsRefNo->Exportable) $Doc->ExportField($this->PreidentsRefNo);
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
