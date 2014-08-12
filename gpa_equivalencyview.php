<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "ewmysql10.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "gpa_equivalencyinfo.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$gpa_equivalency_view = NULL; // Initialize page object first

class cgpa_equivalency_view extends cgpa_equivalency {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{F8E6AD82-57C4-4BEE-8975-8D386184467A}";

	// Table name
	var $TableName = 'gpa equivalency';

	// Page object name
	var $PageObjName = 'gpa_equivalency_view';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
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
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
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

		// Table object (gpa_equivalency)
		if (!isset($GLOBALS["gpa_equivalency"]) || get_class($GLOBALS["gpa_equivalency"]) == "cgpa_equivalency") {
			$GLOBALS["gpa_equivalency"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["gpa_equivalency"];
		}
		$KeyUrl = "";
		if (@$_GET["ID"] <> "") {
			$this->RecKey["ID"] = $_GET["ID"];
			$KeyUrl .= "&amp;ID=" . urlencode($this->RecKey["ID"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'gpa equivalency', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
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
		if (!$Security->CanView()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate("gpa_equivalencylist.php");
		}

		// Get export parameters
		if (@$_GET["export"] <> "") {
			$this->Export = $_GET["export"];
		} elseif (ew_IsHttpPost()) {
			if (@$_POST["exporttype"] <> "")
				$this->Export = $_POST["exporttype"];
		} else {
			$this->setExportReturnUrl(ew_CurrentUrl());
		}
		$gsExport = $this->Export; // Get export parameter, used in header
		$gsExportFile = $this->TableVar; // Get export file, used in header
		if (@$_GET["ID"] <> "") {
			if ($gsExportFile <> "") $gsExportFile .= "_";
			$gsExportFile .= ew_StripSlashes($_GET["ID"]);
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Setup export options
		$this->SetupExportOptions();
		$this->ID->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Update url if printer friendly for Pdf
		if ($this->PrinterFriendlyForPdf)
			$this->ExportOptions->Items["pdf"]->Body = str_replace($this->ExportPdfUrl, $this->ExportPrintUrl . "&pdf=1", $this->ExportOptions->Items["pdf"]->Body);
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $conn;

		// Page Unload event
		$this->Page_Unload();
		if ($this->Export == "print" && @$_GET["pdf"] == "1") { // Printer friendly version and with pdf=1 in URL parameters
			$pdf = new cExportPdf($GLOBALS["Table"]);
			$pdf->Text = ob_get_contents(); // Set the content as the HTML of current page (printer friendly version)
			ob_end_clean();
			$pdf->Export();
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
	var $OtherOptions = array(); // Other options
	var $DisplayRecs = 1;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $RecCnt;
	var $RecKey = array();
	var $Recordset;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Load current record
		$bLoadCurrentRecord = FALSE;
		$sReturnUrl = "";
		$bMatchRecord = FALSE;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET["ID"] <> "") {
				$this->ID->setQueryStringValue($_GET["ID"]);
				$this->RecKey["ID"] = $this->ID->QueryStringValue;
			} else {
				$sReturnUrl = "gpa_equivalencylist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "gpa_equivalencylist.php"; // No matching record, return to list
					}
			}

			// Export data only
			if (in_array($this->Export, array("html","word","excel","xml","csv","email","pdf"))) {
				$this->ExportData();
				$this->Page_Terminate(); // Terminate response
				exit();
			}
		} else {
			$sReturnUrl = "gpa_equivalencylist.php"; // Not page request, return to list
		}
		if ($sReturnUrl <> "")
			$this->Page_Terminate($sReturnUrl);

		// Render row
		$this->RowType = EW_ROWTYPE_VIEW;
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = &$options["action"];

		// Add
		$item = &$option->Add("add");
		$item->Body = "<a class=\"ewAction ewAdd\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("ViewPageAddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->CanAdd());

		// Edit
		$item = &$option->Add("edit");
		$item->Body = "<a class=\"ewAction ewEdit\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("ViewPageEditLink") . "</a>";
		$item->Visible = ($this->EditUrl <> "" && $Security->CanEdit());

		// Delete
		$item = &$option->Add("delete");
		$item->Body = "<a class=\"ewAction ewDelete\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("ViewPageDeleteLink") . "</a>";
		$item->Visible = ($this->DeleteUrl <> "" && $Security->CanDelete());

		// Set up options default
		foreach ($options as &$option) {
			$option->UseDropDownButton = FALSE;
			$option->UseButtonGroup = TRUE;
			$item = &$option->Add($option->GroupOptionName);
			$item->Body = "";
			$item->Visible = FALSE;
		}
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$options["action"]->DropDownButtonPhrase = $Language->Phrase("ButtonActions");
	}

	// Set up starting record parameters
	function SetUpStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {
		global $conn;

		// Call Recordset Selecting event
		$this->Recordset_Selecting($this->CurrentFilter);

		// Load List page SQL
		$sSql = $this->SelectSQL();
		if ($offset > -1 && $rowcnt > -1)
			$sSql .= " LIMIT $rowcnt OFFSET $offset";

		// Load recordset
		$rs = ew_LoadRecordset($sSql);

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
	}

	// Load row based on key values
	function LoadRow() {
		global $conn, $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		global $conn;
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
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
		if (array_key_exists('EV__Bachelors_GPA', $rs->fields)) {
			$this->Bachelors_GPA->VirtualValue = $rs->fields('EV__Bachelors_GPA'); // Set up virtual field value
		} else {
			$this->Bachelors_GPA->VirtualValue = ""; // Clear value
		}
		$this->Bachelors_MGPA->setDbValue($rs->fields('Bachelors MGPA'));
		if (array_key_exists('EV__Bachelors_MGPA', $rs->fields)) {
			$this->Bachelors_MGPA->VirtualValue = $rs->fields('EV__Bachelors_MGPA'); // Set up virtual field value
		} else {
			$this->Bachelors_MGPA->VirtualValue = ""; // Clear value
		}
		$this->Other_Bachelors_Title->setDbValue($rs->fields('Other Bachelors Title'));
		$this->Other_Bachelors_University->setDbValue($rs->fields('Other Bachelors University'));
		$this->Other_Bachelors_Major->setDbValue($rs->fields('Other Bachelors Major'));
		$this->Other_Bachelors_GPA->setDbValue($rs->fields('Other Bachelors GPA'));
		if (array_key_exists('EV__Other_Bachelors_GPA', $rs->fields)) {
			$this->Other_Bachelors_GPA->VirtualValue = $rs->fields('EV__Other_Bachelors_GPA'); // Set up virtual field value
		} else {
			$this->Other_Bachelors_GPA->VirtualValue = ""; // Clear value
		}
		$this->Other_Bachelors_MGPA->setDbValue($rs->fields('Other Bachelors MGPA'));
		if (array_key_exists('EV__Other_Bachelors_MGPA', $rs->fields)) {
			$this->Other_Bachelors_MGPA->VirtualValue = $rs->fields('EV__Other_Bachelors_MGPA'); // Set up virtual field value
		} else {
			$this->Other_Bachelors_MGPA->VirtualValue = ""; // Clear value
		}
		$this->Masters_Degree_Title->setDbValue($rs->fields('Masters Degree Title'));
		$this->Master_University->setDbValue($rs->fields('Master University'));
		$this->Masters_Degree_Major->setDbValue($rs->fields('Masters Degree Major'));
		$this->Masters_GPA->setDbValue($rs->fields('Masters GPA'));
		if (array_key_exists('EV__Masters_GPA', $rs->fields)) {
			$this->Masters_GPA->VirtualValue = $rs->fields('EV__Masters_GPA'); // Set up virtual field value
		} else {
			$this->Masters_GPA->VirtualValue = ""; // Clear value
		}
		$this->Other_Masters_Degree_Title->setDbValue($rs->fields('Other Masters Degree Title'));
		$this->Other_Masters_University->setDbValue($rs->fields('Other Masters University'));
		$this->Other_Masters_Major->setDbValue($rs->fields('Other Masters Major'));
		$this->Other_Masters_GPA->setDbValue($rs->fields('Other Masters GPA'));
		if (array_key_exists('EV__Other_Masters_GPA', $rs->fields)) {
			$this->Other_Masters_GPA->VirtualValue = $rs->fields('EV__Other_Masters_GPA'); // Set up virtual field value
		} else {
			$this->Other_Masters_GPA->VirtualValue = ""; // Clear value
		}
		$this->PhD_Title->setDbValue($rs->fields('PhD Title'));
		$this->Phd_University->setDbValue($rs->fields('Phd University'));
		$this->PhD_Major->setDbValue($rs->fields('PhD Major'));
		$this->Phd_Degree_Equivalency->setDbValue($rs->fields('Phd Degree Equivalency'));
		$this->Committee_Meeting->setDbValue($rs->fields('Committee Meeting'));
		$this->Committee_Meeting_Number->setDbValue($rs->fields('Committee Meeting Number'));
		$this->Committee_Date->setDbValue($rs->fields('Committee Date'));
		$this->Notes->setDbValue($rs->fields('Notes'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->ID->DbValue = $row['ID'];
		$this->Name->DbValue = $row['Name'];
		$this->Country->DbValue = $row['Country'];
		$this->Civil_ID->DbValue = $row['Civil ID'];
		$this->Passport_No2E->DbValue = $row['Passport No.'];
		$this->Sector->DbValue = $row['Sector'];
		$this->Job_Title->DbValue = $row['Job Title'];
		$this->Program->DbValue = $row['Program'];
		$this->College->DbValue = $row['College'];
		$this->Department->DbValue = $row['Department'];
		$this->Bachelors_Title->DbValue = $row['Bachelors Title'];
		$this->Bachelor_University->DbValue = $row['Bachelor University'];
		$this->Bachelors_Major->DbValue = $row['Bachelors Major'];
		$this->Bachelors_GPA->DbValue = $row['Bachelors GPA'];
		$this->Bachelors_MGPA->DbValue = $row['Bachelors MGPA'];
		$this->Other_Bachelors_Title->DbValue = $row['Other Bachelors Title'];
		$this->Other_Bachelors_University->DbValue = $row['Other Bachelors University'];
		$this->Other_Bachelors_Major->DbValue = $row['Other Bachelors Major'];
		$this->Other_Bachelors_GPA->DbValue = $row['Other Bachelors GPA'];
		$this->Other_Bachelors_MGPA->DbValue = $row['Other Bachelors MGPA'];
		$this->Masters_Degree_Title->DbValue = $row['Masters Degree Title'];
		$this->Master_University->DbValue = $row['Master University'];
		$this->Masters_Degree_Major->DbValue = $row['Masters Degree Major'];
		$this->Masters_GPA->DbValue = $row['Masters GPA'];
		$this->Other_Masters_Degree_Title->DbValue = $row['Other Masters Degree Title'];
		$this->Other_Masters_University->DbValue = $row['Other Masters University'];
		$this->Other_Masters_Major->DbValue = $row['Other Masters Major'];
		$this->Other_Masters_GPA->DbValue = $row['Other Masters GPA'];
		$this->PhD_Title->DbValue = $row['PhD Title'];
		$this->Phd_University->DbValue = $row['Phd University'];
		$this->PhD_Major->DbValue = $row['PhD Major'];
		$this->Phd_Degree_Equivalency->DbValue = $row['Phd Degree Equivalency'];
		$this->Committee_Meeting->DbValue = $row['Committee Meeting'];
		$this->Committee_Meeting_Number->DbValue = $row['Committee Meeting Number'];
		$this->Committee_Date->DbValue = $row['Committee Date'];
		$this->Notes->DbValue = $row['Notes'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		$this->AddUrl = $this->GetAddUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();
		$this->ListUrl = $this->GetListUrl();
		$this->SetupOtherOptions();

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
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

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

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

		// Export to Html
		$item = &$this->ExportOptions->Add("html");
		$item->Body = "<a href=\"" . $this->ExportHtmlUrl . "\" class=\"ewExportLink ewHtml\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtmlText")) . "\">" . $Language->Phrase("ExportToHtml") . "</a>";
		$item->Visible = FALSE;

		// Export to Xml
		$item = &$this->ExportOptions->Add("xml");
		$item->Body = "<a href=\"" . $this->ExportXmlUrl . "\" class=\"ewExportLink ewXml\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\">" . $Language->Phrase("ExportToXml") . "</a>";
		$item->Visible = FALSE;

		// Export to Csv
		$item = &$this->ExportOptions->Add("csv");
		$item->Body = "<a href=\"" . $this->ExportCsvUrl . "\" class=\"ewExportLink ewCsv\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsvText")) . "\">" . $Language->Phrase("ExportToCsv") . "</a>";
		$item->Visible = FALSE;

		// Export to Pdf
		$item = &$this->ExportOptions->Add("pdf");
		$item->Body = "<a href=\"" . $this->ExportPdfUrl . "\" class=\"ewExportLink ewPdf\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\">" . $Language->Phrase("ExportToPDF") . "</a>";
		$item->Visible = TRUE;

		// Export to Email
		$item = &$this->ExportOptions->Add("email");
		$item->Body = "<a id=\"emf_gpa_equivalency\" href=\"javascript:void(0);\" class=\"ewExportLink ewEmail\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_gpa_equivalency',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.fgpa_equivalencyview,key:" . ew_ArrayToJsonAttr($this->RecKey) . ",sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
		$item->Visible = FALSE;

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

	// Export data in HTML/CSV/Word/Excel/XML/Email/PDF format
	function ExportData() {
		$utf8 = (strtolower(EW_CHARSET) == "utf-8");
		$bSelectLimit = FALSE;

		// Load recordset
		if ($bSelectLimit) {
			$this->TotalRecs = $this->SelectRecordCount();
		} else {
			if ($rs = $this->LoadRecordset())
				$this->TotalRecs = $rs->RecordCount();
		}
		$this->StartRec = 1;
		$this->SetUpStartRec(); // Set up start record position

		// Set the last record to display
		if ($this->DisplayRecs <= 0) {
			$this->StopRec = $this->TotalRecs;
		} else {
			$this->StopRec = $this->StartRec + $this->DisplayRecs - 1;
		}
		if (!$rs) {
			header("Content-Type:"); // Remove header
			header("Content-Disposition:");
			$this->ShowMessage();
			return;
		}
		$ExportDoc = ew_ExportDocument($this, "v");
		$ParentTable = "";
		if ($bSelectLimit) {
			$StartRec = 1;
			$StopRec = $this->DisplayRecs <= 0 ? $this->TotalRecs : $this->DisplayRecs;
		} else {
			$StartRec = $this->StartRec;
			$StopRec = $this->StopRec;
		}
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		$ExportDoc->Text .= $sHeader;
		$this->ExportDocument($ExportDoc, $rs, $StartRec, $StopRec, "view");
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		$ExportDoc->Text .= $sFooter;

		// Close recordset
		$rs->Close();

		// Export header and footer
		$ExportDoc->ExportHeaderAndFooter();

		// Clean output buffer
		if (!EW_DEBUG_ENABLED && ob_get_length())
			ob_end_clean();

		// Write debug message if enabled
		if (EW_DEBUG_ENABLED)
			echo ew_DebugMsg();

		// Output data
		$ExportDoc->Export();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$Breadcrumb->Add("list", $this->TableVar, "gpa_equivalencylist.php", $this->TableVar, TRUE);
		$PageId = "view";
		$Breadcrumb->Add("view", $PageId, ew_CurrentUrl());
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
if (!isset($gpa_equivalency_view)) $gpa_equivalency_view = new cgpa_equivalency_view();

// Page init
$gpa_equivalency_view->Page_Init();

// Page main
$gpa_equivalency_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$gpa_equivalency_view->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($gpa_equivalency->Export == "") { ?>
<script type="text/javascript">

// Page object
var gpa_equivalency_view = new ew_Page("gpa_equivalency_view");
gpa_equivalency_view.PageID = "view"; // Page ID
var EW_PAGE_ID = gpa_equivalency_view.PageID; // For backward compatibility

// Form object
var fgpa_equivalencyview = new ew_Form("fgpa_equivalencyview");

// Form_CustomValidate event
fgpa_equivalencyview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fgpa_equivalencyview.ValidateRequired = true;
<?php } else { ?>
fgpa_equivalencyview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fgpa_equivalencyview.Lists["x_Country"] = {"LinkField":"x_NID","Ajax":null,"AutoFill":false,"DisplayFields":["x_Nationality","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fgpa_equivalencyview.Lists["x_Sector"] = {"LinkField":"x_ID","Ajax":null,"AutoFill":false,"DisplayFields":["x_Sector","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fgpa_equivalencyview.Lists["x_Job_Title"] = {"LinkField":"x_ID","Ajax":null,"AutoFill":false,"DisplayFields":["x_Job_Title","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fgpa_equivalencyview.Lists["x_College"] = {"LinkField":"x_CollegeID","Ajax":null,"AutoFill":false,"DisplayFields":["x_College_Name_AR","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fgpa_equivalencyview.Lists["x_Department"] = {"LinkField":"x_DID","Ajax":null,"AutoFill":false,"DisplayFields":["x_DepartmentName2DAR","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fgpa_equivalencyview.Lists["x_Bachelors_GPA"] = {"LinkField":"x_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_Grade","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fgpa_equivalencyview.Lists["x_Bachelors_MGPA"] = {"LinkField":"x_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_Grade","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fgpa_equivalencyview.Lists["x_Other_Bachelors_GPA"] = {"LinkField":"x_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_Grade","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fgpa_equivalencyview.Lists["x_Other_Bachelors_MGPA"] = {"LinkField":"x_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_Grade","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fgpa_equivalencyview.Lists["x_Masters_GPA"] = {"LinkField":"x_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_Grade","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fgpa_equivalencyview.Lists["x_Other_Masters_GPA"] = {"LinkField":"x_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_Grade","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($gpa_equivalency->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($gpa_equivalency->Export == "") { ?>
<div class="ewViewExportOptions">
<?php $gpa_equivalency_view->ExportOptions->Render("body") ?>
<?php if (!$gpa_equivalency_view->ExportOptions->UseDropDownButton) { ?>
</div>
<div class="ewViewOtherOptions">
<?php } ?>
<?php
	foreach ($gpa_equivalency_view->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<?php } ?>
<?php $gpa_equivalency_view->ShowPageHeader(); ?>
<?php
$gpa_equivalency_view->ShowMessage();
?>
<form name="fgpa_equivalencyview" id="fgpa_equivalencyview" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="gpa_equivalency">
<table class="ewGrid"><tr><td>
<table id="tbl_gpa_equivalencyview" class="table table-bordered table-striped">
<?php if ($gpa_equivalency->ID->Visible) { // ID ?>
	<tr id="r_ID">
		<td><span id="elh_gpa_equivalency_ID"><?php echo $gpa_equivalency->ID->FldCaption() ?></span></td>
		<td<?php echo $gpa_equivalency->ID->CellAttributes() ?>>
<span id="el_gpa_equivalency_ID" class="control-group">
<span<?php echo $gpa_equivalency->ID->ViewAttributes() ?>>
<?php echo $gpa_equivalency->ID->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($gpa_equivalency->Name->Visible) { // Name ?>
	<tr id="r_Name">
		<td><span id="elh_gpa_equivalency_Name"><?php echo $gpa_equivalency->Name->FldCaption() ?></span></td>
		<td<?php echo $gpa_equivalency->Name->CellAttributes() ?>>
<span id="el_gpa_equivalency_Name" class="control-group">
<span<?php echo $gpa_equivalency->Name->ViewAttributes() ?>>
<?php echo $gpa_equivalency->Name->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($gpa_equivalency->Country->Visible) { // Country ?>
	<tr id="r_Country">
		<td><span id="elh_gpa_equivalency_Country"><?php echo $gpa_equivalency->Country->FldCaption() ?></span></td>
		<td<?php echo $gpa_equivalency->Country->CellAttributes() ?>>
<span id="el_gpa_equivalency_Country" class="control-group">
<span<?php echo $gpa_equivalency->Country->ViewAttributes() ?>>
<?php echo $gpa_equivalency->Country->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($gpa_equivalency->Civil_ID->Visible) { // Civil ID ?>
	<tr id="r_Civil_ID">
		<td><span id="elh_gpa_equivalency_Civil_ID"><?php echo $gpa_equivalency->Civil_ID->FldCaption() ?></span></td>
		<td<?php echo $gpa_equivalency->Civil_ID->CellAttributes() ?>>
<span id="el_gpa_equivalency_Civil_ID" class="control-group">
<span<?php echo $gpa_equivalency->Civil_ID->ViewAttributes() ?>>
<?php echo $gpa_equivalency->Civil_ID->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($gpa_equivalency->Passport_No2E->Visible) { // Passport No. ?>
	<tr id="r_Passport_No2E">
		<td><span id="elh_gpa_equivalency_Passport_No2E"><?php echo $gpa_equivalency->Passport_No2E->FldCaption() ?></span></td>
		<td<?php echo $gpa_equivalency->Passport_No2E->CellAttributes() ?>>
<span id="el_gpa_equivalency_Passport_No2E" class="control-group">
<span<?php echo $gpa_equivalency->Passport_No2E->ViewAttributes() ?>>
<?php echo $gpa_equivalency->Passport_No2E->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($gpa_equivalency->Sector->Visible) { // Sector ?>
	<tr id="r_Sector">
		<td><span id="elh_gpa_equivalency_Sector"><?php echo $gpa_equivalency->Sector->FldCaption() ?></span></td>
		<td<?php echo $gpa_equivalency->Sector->CellAttributes() ?>>
<span id="el_gpa_equivalency_Sector" class="control-group">
<span<?php echo $gpa_equivalency->Sector->ViewAttributes() ?>>
<?php echo $gpa_equivalency->Sector->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($gpa_equivalency->Job_Title->Visible) { // Job Title ?>
	<tr id="r_Job_Title">
		<td><span id="elh_gpa_equivalency_Job_Title"><?php echo $gpa_equivalency->Job_Title->FldCaption() ?></span></td>
		<td<?php echo $gpa_equivalency->Job_Title->CellAttributes() ?>>
<span id="el_gpa_equivalency_Job_Title" class="control-group">
<span<?php echo $gpa_equivalency->Job_Title->ViewAttributes() ?>>
<?php echo $gpa_equivalency->Job_Title->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($gpa_equivalency->Program->Visible) { // Program ?>
	<tr id="r_Program">
		<td><span id="elh_gpa_equivalency_Program"><?php echo $gpa_equivalency->Program->FldCaption() ?></span></td>
		<td<?php echo $gpa_equivalency->Program->CellAttributes() ?>>
<span id="el_gpa_equivalency_Program" class="control-group">
<span<?php echo $gpa_equivalency->Program->ViewAttributes() ?>>
<?php echo $gpa_equivalency->Program->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($gpa_equivalency->College->Visible) { // College ?>
	<tr id="r_College">
		<td><span id="elh_gpa_equivalency_College"><?php echo $gpa_equivalency->College->FldCaption() ?></span></td>
		<td<?php echo $gpa_equivalency->College->CellAttributes() ?>>
<span id="el_gpa_equivalency_College" class="control-group">
<span<?php echo $gpa_equivalency->College->ViewAttributes() ?>>
<?php echo $gpa_equivalency->College->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($gpa_equivalency->Department->Visible) { // Department ?>
	<tr id="r_Department">
		<td><span id="elh_gpa_equivalency_Department"><?php echo $gpa_equivalency->Department->FldCaption() ?></span></td>
		<td<?php echo $gpa_equivalency->Department->CellAttributes() ?>>
<span id="el_gpa_equivalency_Department" class="control-group">
<span<?php echo $gpa_equivalency->Department->ViewAttributes() ?>>
<?php echo $gpa_equivalency->Department->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($gpa_equivalency->Bachelors_Title->Visible) { // Bachelors Title ?>
	<tr id="r_Bachelors_Title">
		<td><span id="elh_gpa_equivalency_Bachelors_Title"><?php echo $gpa_equivalency->Bachelors_Title->FldCaption() ?></span></td>
		<td<?php echo $gpa_equivalency->Bachelors_Title->CellAttributes() ?>>
<span id="el_gpa_equivalency_Bachelors_Title" class="control-group">
<span<?php echo $gpa_equivalency->Bachelors_Title->ViewAttributes() ?>>
<?php echo $gpa_equivalency->Bachelors_Title->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($gpa_equivalency->Bachelor_University->Visible) { // Bachelor University ?>
	<tr id="r_Bachelor_University">
		<td><span id="elh_gpa_equivalency_Bachelor_University"><?php echo $gpa_equivalency->Bachelor_University->FldCaption() ?></span></td>
		<td<?php echo $gpa_equivalency->Bachelor_University->CellAttributes() ?>>
<span id="el_gpa_equivalency_Bachelor_University" class="control-group">
<span<?php echo $gpa_equivalency->Bachelor_University->ViewAttributes() ?>>
<?php echo $gpa_equivalency->Bachelor_University->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($gpa_equivalency->Bachelors_Major->Visible) { // Bachelors Major ?>
	<tr id="r_Bachelors_Major">
		<td><span id="elh_gpa_equivalency_Bachelors_Major"><?php echo $gpa_equivalency->Bachelors_Major->FldCaption() ?></span></td>
		<td<?php echo $gpa_equivalency->Bachelors_Major->CellAttributes() ?>>
<span id="el_gpa_equivalency_Bachelors_Major" class="control-group">
<span<?php echo $gpa_equivalency->Bachelors_Major->ViewAttributes() ?>>
<?php echo $gpa_equivalency->Bachelors_Major->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($gpa_equivalency->Bachelors_GPA->Visible) { // Bachelors GPA ?>
	<tr id="r_Bachelors_GPA">
		<td><span id="elh_gpa_equivalency_Bachelors_GPA"><?php echo $gpa_equivalency->Bachelors_GPA->FldCaption() ?></span></td>
		<td<?php echo $gpa_equivalency->Bachelors_GPA->CellAttributes() ?>>
<span id="el_gpa_equivalency_Bachelors_GPA" class="control-group">
<span<?php echo $gpa_equivalency->Bachelors_GPA->ViewAttributes() ?>>
<?php echo $gpa_equivalency->Bachelors_GPA->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($gpa_equivalency->Bachelors_MGPA->Visible) { // Bachelors MGPA ?>
	<tr id="r_Bachelors_MGPA">
		<td><span id="elh_gpa_equivalency_Bachelors_MGPA"><?php echo $gpa_equivalency->Bachelors_MGPA->FldCaption() ?></span></td>
		<td<?php echo $gpa_equivalency->Bachelors_MGPA->CellAttributes() ?>>
<span id="el_gpa_equivalency_Bachelors_MGPA" class="control-group">
<span<?php echo $gpa_equivalency->Bachelors_MGPA->ViewAttributes() ?>>
<?php echo $gpa_equivalency->Bachelors_MGPA->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($gpa_equivalency->Other_Bachelors_Title->Visible) { // Other Bachelors Title ?>
	<tr id="r_Other_Bachelors_Title">
		<td><span id="elh_gpa_equivalency_Other_Bachelors_Title"><?php echo $gpa_equivalency->Other_Bachelors_Title->FldCaption() ?></span></td>
		<td<?php echo $gpa_equivalency->Other_Bachelors_Title->CellAttributes() ?>>
<span id="el_gpa_equivalency_Other_Bachelors_Title" class="control-group">
<span<?php echo $gpa_equivalency->Other_Bachelors_Title->ViewAttributes() ?>>
<?php echo $gpa_equivalency->Other_Bachelors_Title->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($gpa_equivalency->Other_Bachelors_University->Visible) { // Other Bachelors University ?>
	<tr id="r_Other_Bachelors_University">
		<td><span id="elh_gpa_equivalency_Other_Bachelors_University"><?php echo $gpa_equivalency->Other_Bachelors_University->FldCaption() ?></span></td>
		<td<?php echo $gpa_equivalency->Other_Bachelors_University->CellAttributes() ?>>
<span id="el_gpa_equivalency_Other_Bachelors_University" class="control-group">
<span<?php echo $gpa_equivalency->Other_Bachelors_University->ViewAttributes() ?>>
<?php echo $gpa_equivalency->Other_Bachelors_University->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($gpa_equivalency->Other_Bachelors_Major->Visible) { // Other Bachelors Major ?>
	<tr id="r_Other_Bachelors_Major">
		<td><span id="elh_gpa_equivalency_Other_Bachelors_Major"><?php echo $gpa_equivalency->Other_Bachelors_Major->FldCaption() ?></span></td>
		<td<?php echo $gpa_equivalency->Other_Bachelors_Major->CellAttributes() ?>>
<span id="el_gpa_equivalency_Other_Bachelors_Major" class="control-group">
<span<?php echo $gpa_equivalency->Other_Bachelors_Major->ViewAttributes() ?>>
<?php echo $gpa_equivalency->Other_Bachelors_Major->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($gpa_equivalency->Other_Bachelors_GPA->Visible) { // Other Bachelors GPA ?>
	<tr id="r_Other_Bachelors_GPA">
		<td><span id="elh_gpa_equivalency_Other_Bachelors_GPA"><?php echo $gpa_equivalency->Other_Bachelors_GPA->FldCaption() ?></span></td>
		<td<?php echo $gpa_equivalency->Other_Bachelors_GPA->CellAttributes() ?>>
<span id="el_gpa_equivalency_Other_Bachelors_GPA" class="control-group">
<span<?php echo $gpa_equivalency->Other_Bachelors_GPA->ViewAttributes() ?>>
<?php echo $gpa_equivalency->Other_Bachelors_GPA->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($gpa_equivalency->Other_Bachelors_MGPA->Visible) { // Other Bachelors MGPA ?>
	<tr id="r_Other_Bachelors_MGPA">
		<td><span id="elh_gpa_equivalency_Other_Bachelors_MGPA"><?php echo $gpa_equivalency->Other_Bachelors_MGPA->FldCaption() ?></span></td>
		<td<?php echo $gpa_equivalency->Other_Bachelors_MGPA->CellAttributes() ?>>
<span id="el_gpa_equivalency_Other_Bachelors_MGPA" class="control-group">
<span<?php echo $gpa_equivalency->Other_Bachelors_MGPA->ViewAttributes() ?>>
<?php echo $gpa_equivalency->Other_Bachelors_MGPA->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($gpa_equivalency->Masters_Degree_Title->Visible) { // Masters Degree Title ?>
	<tr id="r_Masters_Degree_Title">
		<td><span id="elh_gpa_equivalency_Masters_Degree_Title"><?php echo $gpa_equivalency->Masters_Degree_Title->FldCaption() ?></span></td>
		<td<?php echo $gpa_equivalency->Masters_Degree_Title->CellAttributes() ?>>
<span id="el_gpa_equivalency_Masters_Degree_Title" class="control-group">
<span<?php echo $gpa_equivalency->Masters_Degree_Title->ViewAttributes() ?>>
<?php echo $gpa_equivalency->Masters_Degree_Title->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($gpa_equivalency->Master_University->Visible) { // Master University ?>
	<tr id="r_Master_University">
		<td><span id="elh_gpa_equivalency_Master_University"><?php echo $gpa_equivalency->Master_University->FldCaption() ?></span></td>
		<td<?php echo $gpa_equivalency->Master_University->CellAttributes() ?>>
<span id="el_gpa_equivalency_Master_University" class="control-group">
<span<?php echo $gpa_equivalency->Master_University->ViewAttributes() ?>>
<?php echo $gpa_equivalency->Master_University->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($gpa_equivalency->Masters_Degree_Major->Visible) { // Masters Degree Major ?>
	<tr id="r_Masters_Degree_Major">
		<td><span id="elh_gpa_equivalency_Masters_Degree_Major"><?php echo $gpa_equivalency->Masters_Degree_Major->FldCaption() ?></span></td>
		<td<?php echo $gpa_equivalency->Masters_Degree_Major->CellAttributes() ?>>
<span id="el_gpa_equivalency_Masters_Degree_Major" class="control-group">
<span<?php echo $gpa_equivalency->Masters_Degree_Major->ViewAttributes() ?>>
<?php echo $gpa_equivalency->Masters_Degree_Major->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($gpa_equivalency->Masters_GPA->Visible) { // Masters GPA ?>
	<tr id="r_Masters_GPA">
		<td><span id="elh_gpa_equivalency_Masters_GPA"><?php echo $gpa_equivalency->Masters_GPA->FldCaption() ?></span></td>
		<td<?php echo $gpa_equivalency->Masters_GPA->CellAttributes() ?>>
<span id="el_gpa_equivalency_Masters_GPA" class="control-group">
<span<?php echo $gpa_equivalency->Masters_GPA->ViewAttributes() ?>>
<?php echo $gpa_equivalency->Masters_GPA->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($gpa_equivalency->Other_Masters_Degree_Title->Visible) { // Other Masters Degree Title ?>
	<tr id="r_Other_Masters_Degree_Title">
		<td><span id="elh_gpa_equivalency_Other_Masters_Degree_Title"><?php echo $gpa_equivalency->Other_Masters_Degree_Title->FldCaption() ?></span></td>
		<td<?php echo $gpa_equivalency->Other_Masters_Degree_Title->CellAttributes() ?>>
<span id="el_gpa_equivalency_Other_Masters_Degree_Title" class="control-group">
<span<?php echo $gpa_equivalency->Other_Masters_Degree_Title->ViewAttributes() ?>>
<?php echo $gpa_equivalency->Other_Masters_Degree_Title->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($gpa_equivalency->Other_Masters_University->Visible) { // Other Masters University ?>
	<tr id="r_Other_Masters_University">
		<td><span id="elh_gpa_equivalency_Other_Masters_University"><?php echo $gpa_equivalency->Other_Masters_University->FldCaption() ?></span></td>
		<td<?php echo $gpa_equivalency->Other_Masters_University->CellAttributes() ?>>
<span id="el_gpa_equivalency_Other_Masters_University" class="control-group">
<span<?php echo $gpa_equivalency->Other_Masters_University->ViewAttributes() ?>>
<?php echo $gpa_equivalency->Other_Masters_University->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($gpa_equivalency->Other_Masters_Major->Visible) { // Other Masters Major ?>
	<tr id="r_Other_Masters_Major">
		<td><span id="elh_gpa_equivalency_Other_Masters_Major"><?php echo $gpa_equivalency->Other_Masters_Major->FldCaption() ?></span></td>
		<td<?php echo $gpa_equivalency->Other_Masters_Major->CellAttributes() ?>>
<span id="el_gpa_equivalency_Other_Masters_Major" class="control-group">
<span<?php echo $gpa_equivalency->Other_Masters_Major->ViewAttributes() ?>>
<?php echo $gpa_equivalency->Other_Masters_Major->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($gpa_equivalency->Other_Masters_GPA->Visible) { // Other Masters GPA ?>
	<tr id="r_Other_Masters_GPA">
		<td><span id="elh_gpa_equivalency_Other_Masters_GPA"><?php echo $gpa_equivalency->Other_Masters_GPA->FldCaption() ?></span></td>
		<td<?php echo $gpa_equivalency->Other_Masters_GPA->CellAttributes() ?>>
<span id="el_gpa_equivalency_Other_Masters_GPA" class="control-group">
<span<?php echo $gpa_equivalency->Other_Masters_GPA->ViewAttributes() ?>>
<?php echo $gpa_equivalency->Other_Masters_GPA->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($gpa_equivalency->PhD_Title->Visible) { // PhD Title ?>
	<tr id="r_PhD_Title">
		<td><span id="elh_gpa_equivalency_PhD_Title"><?php echo $gpa_equivalency->PhD_Title->FldCaption() ?></span></td>
		<td<?php echo $gpa_equivalency->PhD_Title->CellAttributes() ?>>
<span id="el_gpa_equivalency_PhD_Title" class="control-group">
<span<?php echo $gpa_equivalency->PhD_Title->ViewAttributes() ?>>
<?php echo $gpa_equivalency->PhD_Title->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($gpa_equivalency->Phd_University->Visible) { // Phd University ?>
	<tr id="r_Phd_University">
		<td><span id="elh_gpa_equivalency_Phd_University"><?php echo $gpa_equivalency->Phd_University->FldCaption() ?></span></td>
		<td<?php echo $gpa_equivalency->Phd_University->CellAttributes() ?>>
<span id="el_gpa_equivalency_Phd_University" class="control-group">
<span<?php echo $gpa_equivalency->Phd_University->ViewAttributes() ?>>
<?php echo $gpa_equivalency->Phd_University->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($gpa_equivalency->PhD_Major->Visible) { // PhD Major ?>
	<tr id="r_PhD_Major">
		<td><span id="elh_gpa_equivalency_PhD_Major"><?php echo $gpa_equivalency->PhD_Major->FldCaption() ?></span></td>
		<td<?php echo $gpa_equivalency->PhD_Major->CellAttributes() ?>>
<span id="el_gpa_equivalency_PhD_Major" class="control-group">
<span<?php echo $gpa_equivalency->PhD_Major->ViewAttributes() ?>>
<?php echo $gpa_equivalency->PhD_Major->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($gpa_equivalency->Phd_Degree_Equivalency->Visible) { // Phd Degree Equivalency ?>
	<tr id="r_Phd_Degree_Equivalency">
		<td><span id="elh_gpa_equivalency_Phd_Degree_Equivalency"><?php echo $gpa_equivalency->Phd_Degree_Equivalency->FldCaption() ?></span></td>
		<td<?php echo $gpa_equivalency->Phd_Degree_Equivalency->CellAttributes() ?>>
<span id="el_gpa_equivalency_Phd_Degree_Equivalency" class="control-group">
<span<?php echo $gpa_equivalency->Phd_Degree_Equivalency->ViewAttributes() ?>>
<?php echo $gpa_equivalency->Phd_Degree_Equivalency->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($gpa_equivalency->Committee_Meeting->Visible) { // Committee Meeting ?>
	<tr id="r_Committee_Meeting">
		<td><span id="elh_gpa_equivalency_Committee_Meeting"><?php echo $gpa_equivalency->Committee_Meeting->FldCaption() ?></span></td>
		<td<?php echo $gpa_equivalency->Committee_Meeting->CellAttributes() ?>>
<span id="el_gpa_equivalency_Committee_Meeting" class="control-group">
<span<?php echo $gpa_equivalency->Committee_Meeting->ViewAttributes() ?>>
<?php echo $gpa_equivalency->Committee_Meeting->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($gpa_equivalency->Committee_Meeting_Number->Visible) { // Committee Meeting Number ?>
	<tr id="r_Committee_Meeting_Number">
		<td><span id="elh_gpa_equivalency_Committee_Meeting_Number"><?php echo $gpa_equivalency->Committee_Meeting_Number->FldCaption() ?></span></td>
		<td<?php echo $gpa_equivalency->Committee_Meeting_Number->CellAttributes() ?>>
<span id="el_gpa_equivalency_Committee_Meeting_Number" class="control-group">
<span<?php echo $gpa_equivalency->Committee_Meeting_Number->ViewAttributes() ?>>
<?php echo $gpa_equivalency->Committee_Meeting_Number->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($gpa_equivalency->Committee_Date->Visible) { // Committee Date ?>
	<tr id="r_Committee_Date">
		<td><span id="elh_gpa_equivalency_Committee_Date"><?php echo $gpa_equivalency->Committee_Date->FldCaption() ?></span></td>
		<td<?php echo $gpa_equivalency->Committee_Date->CellAttributes() ?>>
<span id="el_gpa_equivalency_Committee_Date" class="control-group">
<span<?php echo $gpa_equivalency->Committee_Date->ViewAttributes() ?>>
<?php echo $gpa_equivalency->Committee_Date->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($gpa_equivalency->Notes->Visible) { // Notes ?>
	<tr id="r_Notes">
		<td><span id="elh_gpa_equivalency_Notes"><?php echo $gpa_equivalency->Notes->FldCaption() ?></span></td>
		<td<?php echo $gpa_equivalency->Notes->CellAttributes() ?>>
<span id="el_gpa_equivalency_Notes" class="control-group">
<span<?php echo $gpa_equivalency->Notes->ViewAttributes() ?>>
<?php echo $gpa_equivalency->Notes->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
</form>
<script type="text/javascript">
fgpa_equivalencyview.Init();
</script>
<?php
$gpa_equivalency_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($gpa_equivalency->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$gpa_equivalency_view->Page_Terminate();
?>
