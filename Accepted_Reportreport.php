<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "ewmysql10.php" ?>
<?php include_once "phpfn10.php" ?>
<?php

// Global variable for table object
$Accepted_Report = NULL;

//
// Table class for Accepted Report
//
class cAccepted_Report extends cTableBase {
	var $Arabic_Name;
	var $College;
	var $Department;
	var $Major;
	var $Acceptance_University;
	var $Program_Degree;
	var $Status;
	var $Committee_Date;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'Accepted_Report';
		$this->TableName = 'Accepted Report';
		$this->TableType = 'REPORT';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->PrinterFriendlyForPdf = TRUE;
		$this->UserIDAllowSecurity = 0; // User ID Allow

		// Arabic Name
		$this->Arabic_Name = new cField('Accepted_Report', 'Accepted Report', 'x_Arabic_Name', 'Arabic Name', '`Arabic Name`', '`Arabic Name`', 200, -1, FALSE, '`Arabic Name`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Arabic Name'] = &$this->Arabic_Name;

		// College
		$this->College = new cField('Accepted_Report', 'Accepted Report', 'x_College', 'College', '`College`', '`College`', 200, -1, FALSE, '`College`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['College'] = &$this->College;

		// Department
		$this->Department = new cField('Accepted_Report', 'Accepted Report', 'x_Department', 'Department', '`Department`', '`Department`', 200, -1, FALSE, '`Department`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Department'] = &$this->Department;

		// Major
		$this->Major = new cField('Accepted_Report', 'Accepted Report', 'x_Major', 'Major', '`Major`', '`Major`', 200, -1, FALSE, '`Major`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Major'] = &$this->Major;

		// Acceptance University
		$this->Acceptance_University = new cField('Accepted_Report', 'Accepted Report', 'x_Acceptance_University', 'Acceptance University', '`Acceptance University`', '`Acceptance University`', 200, -1, FALSE, '`Acceptance University`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Acceptance University'] = &$this->Acceptance_University;

		// Program Degree
		$this->Program_Degree = new cField('Accepted_Report', 'Accepted Report', 'x_Program_Degree', 'Program Degree', '`Program Degree`', '`Program Degree`', 202, -1, FALSE, '`Program Degree`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Program Degree'] = &$this->Program_Degree;

		// Status
		$this->Status = new cField('Accepted_Report', 'Accepted Report', 'x_Status', 'Status', '`Status`', '`Status`', 202, -1, FALSE, '`Status`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Status'] = &$this->Status;

		// Committee Date
		$this->Committee_Date = new cField('Accepted_Report', 'Accepted Report', 'x_Committee_Date', 'Committee Date', '`Committee Date`', 'DATE_FORMAT(`Committee Date`, \'%d/%m/%Y\')', 133, 7, FALSE, '`Committee Date`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->Committee_Date->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['Committee Date'] = &$this->Committee_Date;
	}

	// Report group level SQL
	function SqlGroupSelect() { // Select
		return "SELECT DISTINCT `College` FROM `accepted`";
	}

	function SqlGroupWhere() { // Where
		return "";
	}

	function SqlGroupGroupBy() { // Group By
		return "";
	}

	function SqlGroupHaving() { // Having
		return "";
	}

	function SqlGroupOrderBy() { // Order By
		return "`College` ASC";
	}

	// Report detail level SQL
	function SqlDetailSelect() { // Select
		return "SELECT * FROM `accepted`";
	}

	function SqlDetailWhere() { // Where
		return "";
	}

	function SqlDetailGroupBy() { // Group By
		return "";
	}

	function SqlDetailHaving() { // Having
		return "";
	}

	function SqlDetailOrderBy() { // Order By
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

	// Report group SQL
	function GroupSQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = "";
		return ew_BuildSelectSql($this->SqlGroupSelect(), $this->SqlGroupWhere(),
			 $this->SqlGroupGroupBy(), $this->SqlGroupHaving(),
			 $this->SqlGroupOrderBy(), $sFilter, $sSort);
	}

	// Report detail SQL
	function DetailSQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = "";
		return ew_BuildSelectSql($this->SqlDetailSelect(), $this->SqlDetailWhere(),
			$this->SqlDetailGroupBy(), $this->SqlDetailHaving(),
			$this->SqlDetailOrderBy(), $sFilter, $sSort);
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
			return "Accepted_Reportreport.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "Accepted_Reportreport.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("", $this->UrlParm($parm));
		else
			return $this->KeyUrl("", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
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

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		foreach ($arKeys as $key) {
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
<?php include_once "usersinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$Accepted_Report_report = NULL; // Initialize page object first

class cAccepted_Report_report extends cAccepted_Report {

	// Page ID
	var $PageID = 'report';

	// Project ID
	var $ProjectID = "{F8E6AD82-57C4-4BEE-8975-8D386184467A}";

	// Table name
	var $TableName = 'Accepted Report';

	// Page object name
	var $PageObjName = 'Accepted_Report_report';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		return $PageUrl;
	}

	// Page URLs
	var $AddUrl;
	var $EditUrl;
	var $CopyUrl;
	var $DeleteUrl;
	var $ViewUrl;
	var $ListUrl;

	// Export URLs
	var $ExportPrintUrl;
	var $ExportHtmlUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportXmlUrl;
	var $ExportCsvUrl;
	var $ExportPdfUrl;

	// Update URLs
	var $InlineAddUrl;
	var $InlineCopyUrl;
	var $InlineEditUrl;
	var $GridAddUrl;
	var $GridEditUrl;
	var $MultiDeleteUrl;
	var $MultiUpdateUrl;

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-error ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<table class=\"ewStdTable\"><tr><td><div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div></td></tr></table>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p>" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		return TRUE;
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language;
		$GLOBALS["Page"] = &$this;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (Accepted_Report)
		if (!isset($GLOBALS["Accepted_Report"]) || get_class($GLOBALS["Accepted_Report"]) == "cAccepted_Report") {
			$GLOBALS["Accepted_Report"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["Accepted_Report"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'report', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'Accepted Report', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";
	}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if (!$Security->IsLoggedIn()) {
			$Security->SaveLastUrl();
			$this->Page_Terminate("login.php");
		}
		$Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		$Security->TablePermission_Loaded();
		if (!$Security->IsLoggedIn()) {
			$Security->SaveLastUrl();
			$this->Page_Terminate("login.php");
		}
		if (!$Security->CanReport()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate("login.php");
		}

		// Get export parameters
		if (@$_GET["export"] <> "") {
			$this->Export = $_GET["export"];
		}
		$gsExport = $this->Export; // Get export parameter, used in header
		$gsExportFile = $this->TableVar; // Get export file, used in header
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Setup export options
		$this->SetupExportOptions();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $conn;
		global $EW_EXPORT_REPORT;

		// Page Unload event
		$this->Page_Unload();

		// Export
		if ($this->Export <> "" && array_key_exists($this->Export, $EW_EXPORT_REPORT)) {
			$sContent = ob_get_contents();
			$fn = $EW_EXPORT_REPORT[$this->Export];
			$this->$fn($sContent);
			if ($this->Export == "email") { // Email
				ob_end_clean();
				$conn->Close(); // Close connection
				header("Location: " . ew_CurrentPage());
				exit();
			}
		}

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();
		$this->Page_Redirecting($url);

		 // Close connection
		$conn->Close();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			header("Location: " . $url);
		}
		exit();
	}
	var $ExportOptions; // Export options
	var $RecCnt = 0;
	var $ReportSql = "";
	var $ReportFilter = "";
	var $DefaultFilter = "";
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $MasterRecordExists;
	var $Command;
	var $DtlRecordCount;
	var $ReportGroups;
	var $ReportCounts;
	var $LevelBreak;
	var $ReportTotals;
	var $ReportMaxs;
	var $ReportMins;
	var $Recordset;
	var $DetailRecordset;
	var $RecordExists;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;
		$this->ReportGroups = &ew_InitArray(2, NULL);
		$this->ReportCounts = &ew_InitArray(2, 0);
		$this->LevelBreak = &ew_InitArray(2, FALSE);
		$this->ReportTotals = &ew_Init2DArray(2, 7, 0);
		$this->ReportMaxs = &ew_Init2DArray(2, 7, 0);
		$this->ReportMins = &ew_Init2DArray(2, 7, 0);

		// Set up Breadcrumb
		$this->SetupBreadcrumb();
	}

	// Check level break
	function ChkLvlBreak() {
		$this->LevelBreak[1] = FALSE;
		if ($this->RecCnt == 0) { // Start Or End of Recordset
			$this->LevelBreak[1] = TRUE;
		} else {
			if (!ew_CompareValue($this->College->CurrentValue, $this->ReportGroups[0])) {
				$this->LevelBreak[1] = TRUE;
			}
		}
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// Arabic Name
		// College
		// Department
		// Major
		// Acceptance University
		// Program Degree
		// Status
		// Committee Date

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// Arabic Name
			$this->Arabic_Name->ViewValue = $this->Arabic_Name->CurrentValue;
			$this->Arabic_Name->CellCssStyle .= "text-align: right;";
			$this->Arabic_Name->ViewCustomAttributes = "";

			// College
			$this->College->ViewValue = $this->College->CurrentValue;
			$this->College->CellCssStyle .= "text-align: right;";
			$this->College->ViewCustomAttributes = "";

			// Department
			$this->Department->ViewValue = $this->Department->CurrentValue;
			$this->Department->CellCssStyle .= "text-align: right;";
			$this->Department->ViewCustomAttributes = "";

			// Major
			$this->Major->ViewValue = $this->Major->CurrentValue;
			$this->Major->ViewCustomAttributes = "";

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

			// Committee Date
			$this->Committee_Date->ViewValue = $this->Committee_Date->CurrentValue;
			$this->Committee_Date->ViewValue = ew_FormatDateTime($this->Committee_Date->ViewValue, 7);
			$this->Committee_Date->ViewCustomAttributes = "";

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

			// Acceptance University
			$this->Acceptance_University->LinkCustomAttributes = "";
			$this->Acceptance_University->HrefValue = "";
			$this->Acceptance_University->TooltipValue = "";

			// Program Degree
			$this->Program_Degree->LinkCustomAttributes = "";
			$this->Program_Degree->HrefValue = "";
			$this->Program_Degree->TooltipValue = "";

			// Committee Date
			$this->Committee_Date->LinkCustomAttributes = "";
			$this->Committee_Date->HrefValue = "";
			$this->Committee_Date->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Set up export options
	function SetupExportOptions() {
		global $Language;

		// Printer friendly
		$item = &$this->ExportOptions->Add("print");
		$item->Body = "<a href=\"" . $this->ExportPrintUrl . "\" class=\"ewExportLink ewPrint\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\">" . $Language->Phrase("PrinterFriendly") . "</a>";
		$item->Visible = TRUE;

		// Export to Excel
		$item = &$this->ExportOptions->Add("excel");
		$item->Body = "<a href=\"" . $this->ExportExcelUrl . "\" class=\"ewExportLink ewExcel\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\">" . $Language->Phrase("ExportToExcel") . "</a>";
		$item->Visible = TRUE;

		// Export to Word
		$item = &$this->ExportOptions->Add("word");
		$item->Body = "<a href=\"" . $this->ExportWordUrl . "\" class=\"ewExportLink ewWord\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\">" . $Language->Phrase("ExportToWord") . "</a>";
		$item->Visible = TRUE;

		// Drop down button for export
		$this->ExportOptions->UseDropDownButton = FALSE;
		$this->ExportOptions->DropDownButtonPhrase = $Language->Phrase("ButtonExport");

		// Add group option item
		$item = &$this->ExportOptions->Add($this->ExportOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Hide options for export
		if ($this->Export <> "")
			$this->ExportOptions->HideAllOptions();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = ew_CurrentUrl();
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$Breadcrumb->Add("report", $this->TableVar, $url, $this->TableVar, TRUE);
	}

	// Export report to HTML
	function ExportReportHtml($html) {

		//global $gsExportFile;
		//header('Content-Type: text/html' . (EW_CHARSET <> '' ? ';charset=' . EW_CHARSET : ''));
		//header('Content-Disposition: attachment; filename=' . $gsExportFile . '.html');
		//echo $html;

	}

	// Export report to WORD
	function ExportReportWord($html) {
		global $gsExportFile;
		header('Content-Type: application/vnd.ms-word' . (EW_CHARSET <> '' ? ';charset=' . EW_CHARSET : ''));
		header('Content-Disposition: attachment; filename=' . $gsExportFile . '.doc');
		echo $html;
	}

	// Export report to EXCEL
	function ExportReportExcel($html) {
		global $gsExportFile;
		header('Content-Type: application/vnd.ms-excel' . (EW_CHARSET <> '' ? ';charset=' . EW_CHARSET : ''));
		header('Content-Disposition: attachment; filename=' . $gsExportFile . '.xls');
		echo $html;
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}
}
?>
<?php ew_Header(TRUE) ?>
<?php

// Create page object
if (!isset($Accepted_Report_report)) $Accepted_Report_report = new cAccepted_Report_report();

// Page init
$Accepted_Report_report->Page_Init();

// Page main
$Accepted_Report_report->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$Accepted_Report_report->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($Accepted_Report->Export == "") { ?>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($Accepted_Report->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php
$Accepted_Report_report->DefaultFilter = "";
$Accepted_Report_report->ReportFilter = $Accepted_Report_report->DefaultFilter;
if (!$Security->CanReport()) {
	if ($Accepted_Report_report->ReportFilter <> "") $Accepted_Report_report->ReportFilter .= " AND ";
	$Accepted_Report_report->ReportFilter .= "(0=1)";
}
if ($Accepted_Report_report->DbDetailFilter <> "") {
	if ($Accepted_Report_report->ReportFilter <> "") $Accepted_Report_report->ReportFilter .= " AND ";
	$Accepted_Report_report->ReportFilter .= "(" . $Accepted_Report_report->DbDetailFilter . ")";
}

// Set up filter and load Group level sql
$Accepted_Report->CurrentFilter = $Accepted_Report_report->ReportFilter;
$Accepted_Report_report->ReportSql = $Accepted_Report->GroupSQL();

// Load recordset
$Accepted_Report_report->Recordset = $conn->Execute($Accepted_Report_report->ReportSql);
$Accepted_Report_report->RecordExists = !$Accepted_Report_report->Recordset->EOF;
?>
<?php if ($Accepted_Report->Export == "") { ?>
<?php if ($Accepted_Report_report->RecordExists) { ?>
<div class="ewViewExportOptions"><?php $Accepted_Report_report->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php } ?>
<?php $Accepted_Report_report->ShowPageHeader(); ?>
<form method="post">
<table class="ewReportTable">
<?php

// Get First Row
if ($Accepted_Report_report->RecordExists) {
	$Accepted_Report->College->setDbValue($Accepted_Report_report->Recordset->fields('College'));
	$Accepted_Report_report->ReportGroups[0] = $Accepted_Report->College->DbValue;
}
$Accepted_Report_report->RecCnt = 0;
$Accepted_Report_report->ReportCounts[0] = 0;
$Accepted_Report_report->ChkLvlBreak();
while (!$Accepted_Report_report->Recordset->EOF) {

	// Render for view
	$Accepted_Report->RowType = EW_ROWTYPE_VIEW;
	$Accepted_Report->ResetAttrs();
	$Accepted_Report_report->RenderRow();

	// Show group headers
	if ($Accepted_Report_report->LevelBreak[1]) { // Reset counter and aggregation
?>
	<tr><td class="ewGroupField"><?php echo $Accepted_Report->College->FldCaption() ?></td>
	<td colspan=6 class="ewGroupName">
<span<?php echo $Accepted_Report->College->ViewAttributes() ?>>
<?php echo $Accepted_Report->College->ViewValue ?></span>
</td></tr>
<?php
	}

	// Get detail records
	$Accepted_Report_report->ReportFilter = $Accepted_Report_report->DefaultFilter;
	if ($Accepted_Report_report->ReportFilter <> "") $Accepted_Report_report->ReportFilter .= " AND ";
	if (is_null($Accepted_Report->College->CurrentValue)) {
		$Accepted_Report_report->ReportFilter .= "(`College` IS NULL)";
	} else {
		$Accepted_Report_report->ReportFilter .= "(`College` = '" . ew_AdjustSql($Accepted_Report->College->CurrentValue) . "')";
	}
	if ($Accepted_Report_report->DbDetailFilter <> "") {
		if ($Accepted_Report_report->ReportFilter <> "")
			$Accepted_Report_report->ReportFilter .= " AND ";
		$Accepted_Report_report->ReportFilter .= "(" . $Accepted_Report_report->DbDetailFilter . ")";
	}
	if (!$Security->CanReport()) {
		if ($sFilter <> "") $sFilter .= " AND ";
		$sFilter .= "(0=1)";
	}

	// Set up detail SQL
	$Accepted_Report->CurrentFilter = $Accepted_Report_report->ReportFilter;
	$Accepted_Report_report->ReportSql = $Accepted_Report->DetailSQL();

	// Load detail records
	$Accepted_Report_report->DetailRecordset = $conn->Execute($Accepted_Report_report->ReportSql);
	$Accepted_Report_report->DtlRecordCount = $Accepted_Report_report->DetailRecordset->RecordCount();

	// Initialize aggregates
	if (!$Accepted_Report_report->DetailRecordset->EOF) {
		$Accepted_Report_report->RecCnt++;
	}
	if ($Accepted_Report_report->RecCnt == 1) {
		$Accepted_Report_report->ReportCounts[0] = 0;
	}
	for ($i = 1; $i <= 1; $i++) {
		if ($Accepted_Report_report->LevelBreak[$i]) { // Reset counter and aggregation
			$Accepted_Report_report->ReportCounts[$i] = 0;
		}
	}
	$Accepted_Report_report->ReportCounts[0] += $Accepted_Report_report->DtlRecordCount;
	$Accepted_Report_report->ReportCounts[1] += $Accepted_Report_report->DtlRecordCount;
	if ($Accepted_Report_report->RecordExists) {
?>
	<tr>
		<td><div class="ewGroupIndent"></div></td>
		<td class="ewGroupHeader"><?php echo $Accepted_Report->Arabic_Name->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Accepted_Report->Department->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Accepted_Report->Major->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Accepted_Report->Acceptance_University->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Accepted_Report->Program_Degree->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Accepted_Report->Committee_Date->FldCaption() ?></td>
	</tr>
<?php
	}
	while (!$Accepted_Report_report->DetailRecordset->EOF) {
		$Accepted_Report->Arabic_Name->setDbValue($Accepted_Report_report->DetailRecordset->fields('Arabic Name'));
		$Accepted_Report->Department->setDbValue($Accepted_Report_report->DetailRecordset->fields('Department'));
		$Accepted_Report->Major->setDbValue($Accepted_Report_report->DetailRecordset->fields('Major'));
		$Accepted_Report->Acceptance_University->setDbValue($Accepted_Report_report->DetailRecordset->fields('Acceptance University'));
		$Accepted_Report->Program_Degree->setDbValue($Accepted_Report_report->DetailRecordset->fields('Program Degree'));
		$Accepted_Report->Committee_Date->setDbValue($Accepted_Report_report->DetailRecordset->fields('Committee Date'));

		// Render for view
		$Accepted_Report->RowType = EW_ROWTYPE_VIEW;
		$Accepted_Report->ResetAttrs();
		$Accepted_Report_report->RenderRow();
?>
	<tr>
		<td><div class="ewGroupIndent"></div></td>
		<td<?php echo $Accepted_Report->Arabic_Name->CellAttributes() ?>>
<span<?php echo $Accepted_Report->Arabic_Name->ViewAttributes() ?>>
<?php echo $Accepted_Report->Arabic_Name->ViewValue ?></span>
</td>
		<td<?php echo $Accepted_Report->Department->CellAttributes() ?>>
<span<?php echo $Accepted_Report->Department->ViewAttributes() ?>>
<?php echo $Accepted_Report->Department->ViewValue ?></span>
</td>
		<td<?php echo $Accepted_Report->Major->CellAttributes() ?>>
<span<?php echo $Accepted_Report->Major->ViewAttributes() ?>>
<?php echo $Accepted_Report->Major->ViewValue ?></span>
</td>
		<td<?php echo $Accepted_Report->Acceptance_University->CellAttributes() ?>>
<span<?php echo $Accepted_Report->Acceptance_University->ViewAttributes() ?>>
<?php echo $Accepted_Report->Acceptance_University->ViewValue ?></span>
</td>
		<td<?php echo $Accepted_Report->Program_Degree->CellAttributes() ?>>
<span<?php echo $Accepted_Report->Program_Degree->ViewAttributes() ?>>
<?php echo $Accepted_Report->Program_Degree->ViewValue ?></span>
</td>
		<td<?php echo $Accepted_Report->Committee_Date->CellAttributes() ?>>
<span<?php echo $Accepted_Report->Committee_Date->ViewAttributes() ?>>
<?php echo $Accepted_Report->Committee_Date->ViewValue ?></span>
</td>
	</tr>
<?php
		$Accepted_Report_report->DetailRecordset->MoveNext();
	}
	$Accepted_Report_report->DetailRecordset->Close();

	// Save old group data
	$Accepted_Report_report->ReportGroups[0] = $Accepted_Report->College->CurrentValue;

	// Get next record
	$Accepted_Report_report->Recordset->MoveNext();
	if ($Accepted_Report_report->Recordset->EOF) {
		$Accepted_Report_report->RecCnt = 0; // EOF, force all level breaks
	} else {
		$Accepted_Report->College->setDbValue($Accepted_Report_report->Recordset->fields('College'));
	}
	$Accepted_Report_report->ChkLvlBreak();

	// Show footers
	if ($Accepted_Report_report->LevelBreak[1]) {
		$Accepted_Report->College->CurrentValue = $Accepted_Report_report->ReportGroups[0];

		// Render row for view
		$Accepted_Report->RowType = EW_ROWTYPE_VIEW;
		$Accepted_Report->ResetAttrs();
		$Accepted_Report_report->RenderRow();
		$Accepted_Report->College->CurrentValue = $Accepted_Report->College->DbValue;
?>
	<tr><td colspan=7 class="ewGroupSummary"><?php echo $Language->Phrase("RptSumHead") ?>&nbsp;<?php echo $Accepted_Report->College->FldCaption() ?>:&nbsp;<?php echo $Accepted_Report->College->ViewValue ?> (<?php echo ew_FormatNumber($Accepted_Report_report->ReportCounts[1],0) ?> <?php echo $Language->Phrase("RptDtlRec") ?>)</td></tr>
	<tr><td colspan=7>&nbsp;<br></td></tr>
<?php
}
}

// Close recordset
$Accepted_Report_report->Recordset->Close();
?>
<?php if ($Accepted_Report_report->RecordExists) { ?>
	<tr><td colspan=7>&nbsp;<br></td></tr>
	<tr><td colspan=7 class="ewGrandSummary"><?php echo $Language->Phrase("RptGrandTotal") ?>&nbsp;(<?php echo ew_FormatNumber($Accepted_Report_report->ReportCounts[0], 0) ?>&nbsp;<?php echo $Language->Phrase("RptDtlRec") ?>)</td></tr>
<?php } ?>
<?php if ($Accepted_Report_report->RecordExists) { ?>
	<tr><td colspan=7>&nbsp;<br></td></tr>
<?php } else { ?>
	<tr><td><?php echo $Language->Phrase("NoRecord") ?></td></tr>
<?php } ?>
</table>
</form>
<?php
$Accepted_Report_report->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($Accepted_Report->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$Accepted_Report_report->Page_Terminate();
?>
