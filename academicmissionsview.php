<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "ewmysql10.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "academicmissionsinfo.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$academicmissions_view = NULL; // Initialize page object first

class cacademicmissions_view extends cacademicmissions {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{F8E6AD82-57C4-4BEE-8975-8D386184467A}";

	// Table name
	var $TableName = 'academicmissions';

	// Page object name
	var $PageObjName = 'academicmissions_view';

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

		// Table object (academicmissions)
		if (!isset($GLOBALS["academicmissions"]) || get_class($GLOBALS["academicmissions"]) == "cacademicmissions") {
			$GLOBALS["academicmissions"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["academicmissions"];
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
			define("EW_TABLE_NAME", 'academicmissions', TRUE);

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
			$this->Page_Terminate("academicmissionslist.php");
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
				$sReturnUrl = "academicmissionslist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "academicmissionslist.php"; // No matching record, return to list
					}
			}

			// Export data only
			if (in_array($this->Export, array("html","word","excel","xml","csv","email","pdf"))) {
				$this->ExportData();
				$this->Page_Terminate(); // Terminate response
				exit();
			}
		} else {
			$sReturnUrl = "academicmissionslist.php"; // Not page request, return to list
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

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->ID->DbValue = $row['ID'];
		$this->Name->DbValue = $row['Name'];
		$this->UniversityID->DbValue = $row['UniversityID'];
		$this->College->DbValue = $row['College'];
		$this->Department->DbValue = $row['Department'];
		$this->StartDate->DbValue = $row['StartDate'];
		$this->EndDate->DbValue = $row['EndDate'];
		$this->PlaceVisited->DbValue = $row['PlaceVisited'];
		$this->NatureOfVisit->DbValue = $row['NatureOfVisit'];
		$this->AttendanceOnly->DbValue = $row['AttendanceOnly'];
		$this->PresentAPaper->DbValue = $row['PresentAPaper'];
		$this->Others->DbValue = $row['Others'];
		$this->Participation->DbValue = $row['Participation'];
		$this->Summary->DbValue = $row['Summary'];
		$this->SuggestionRecommendation->DbValue = $row['SuggestionRecommendation'];
		$this->FacultyMemberSign->DbValue = $row['FacultyMemberSign'];
		$this->DepChairmanSign->DbValue = $row['DepChairmanSign'];
		$this->DeanSign->DbValue = $row['DeanSign'];
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

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

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
		$item->Body = "<a id=\"emf_academicmissions\" href=\"javascript:void(0);\" class=\"ewExportLink ewEmail\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_academicmissions',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.facademicmissionsview,key:" . ew_ArrayToJsonAttr($this->RecKey) . ",sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
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
		$Breadcrumb->Add("list", $this->TableVar, "academicmissionslist.php", $this->TableVar, TRUE);
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
if (!isset($academicmissions_view)) $academicmissions_view = new cacademicmissions_view();

// Page init
$academicmissions_view->Page_Init();

// Page main
$academicmissions_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$academicmissions_view->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($academicmissions->Export == "") { ?>
<script type="text/javascript">

// Page object
var academicmissions_view = new ew_Page("academicmissions_view");
academicmissions_view.PageID = "view"; // Page ID
var EW_PAGE_ID = academicmissions_view.PageID; // For backward compatibility

// Form object
var facademicmissionsview = new ew_Form("facademicmissionsview");

// Form_CustomValidate event
facademicmissionsview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
facademicmissionsview.ValidateRequired = true;
<?php } else { ?>
facademicmissionsview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
facademicmissionsview.Lists["x_College"] = {"LinkField":"x_CollegeID","Ajax":null,"AutoFill":false,"DisplayFields":["x_College_Name_EN","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
facademicmissionsview.Lists["x_Department"] = {"LinkField":"x_DID","Ajax":null,"AutoFill":false,"DisplayFields":["x_DepartmentName","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($academicmissions->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($academicmissions->Export == "") { ?>
<div class="ewViewExportOptions">
<?php $academicmissions_view->ExportOptions->Render("body") ?>
<?php if (!$academicmissions_view->ExportOptions->UseDropDownButton) { ?>
</div>
<div class="ewViewOtherOptions">
<?php } ?>
<?php
	foreach ($academicmissions_view->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<?php } ?>
<?php $academicmissions_view->ShowPageHeader(); ?>
<?php
$academicmissions_view->ShowMessage();
?>
<form name="facademicmissionsview" id="facademicmissionsview" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="academicmissions">
<table class="ewGrid"><tr><td>
<table id="tbl_academicmissionsview" class="table table-bordered table-striped">
<?php if ($academicmissions->ID->Visible) { // ID ?>
	<tr id="r_ID">
		<td><span id="elh_academicmissions_ID"><?php echo $academicmissions->ID->FldCaption() ?></span></td>
		<td<?php echo $academicmissions->ID->CellAttributes() ?>>
<span id="el_academicmissions_ID" class="control-group">
<span<?php echo $academicmissions->ID->ViewAttributes() ?>>
<?php echo $academicmissions->ID->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($academicmissions->Name->Visible) { // Name ?>
	<tr id="r_Name">
		<td><span id="elh_academicmissions_Name"><?php echo $academicmissions->Name->FldCaption() ?></span></td>
		<td<?php echo $academicmissions->Name->CellAttributes() ?>>
<span id="el_academicmissions_Name" class="control-group">
<span<?php echo $academicmissions->Name->ViewAttributes() ?>>
<?php echo $academicmissions->Name->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($academicmissions->UniversityID->Visible) { // UniversityID ?>
	<tr id="r_UniversityID">
		<td><span id="elh_academicmissions_UniversityID"><?php echo $academicmissions->UniversityID->FldCaption() ?></span></td>
		<td<?php echo $academicmissions->UniversityID->CellAttributes() ?>>
<span id="el_academicmissions_UniversityID" class="control-group">
<span<?php echo $academicmissions->UniversityID->ViewAttributes() ?>>
<?php echo $academicmissions->UniversityID->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($academicmissions->College->Visible) { // College ?>
	<tr id="r_College">
		<td><span id="elh_academicmissions_College"><?php echo $academicmissions->College->FldCaption() ?></span></td>
		<td<?php echo $academicmissions->College->CellAttributes() ?>>
<span id="el_academicmissions_College" class="control-group">
<span<?php echo $academicmissions->College->ViewAttributes() ?>>
<?php echo $academicmissions->College->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($academicmissions->Department->Visible) { // Department ?>
	<tr id="r_Department">
		<td><span id="elh_academicmissions_Department"><?php echo $academicmissions->Department->FldCaption() ?></span></td>
		<td<?php echo $academicmissions->Department->CellAttributes() ?>>
<span id="el_academicmissions_Department" class="control-group">
<span<?php echo $academicmissions->Department->ViewAttributes() ?>>
<?php echo $academicmissions->Department->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($academicmissions->StartDate->Visible) { // StartDate ?>
	<tr id="r_StartDate">
		<td><span id="elh_academicmissions_StartDate"><?php echo $academicmissions->StartDate->FldCaption() ?></span></td>
		<td<?php echo $academicmissions->StartDate->CellAttributes() ?>>
<span id="el_academicmissions_StartDate" class="control-group">
<span<?php echo $academicmissions->StartDate->ViewAttributes() ?>>
<?php echo $academicmissions->StartDate->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($academicmissions->EndDate->Visible) { // EndDate ?>
	<tr id="r_EndDate">
		<td><span id="elh_academicmissions_EndDate"><?php echo $academicmissions->EndDate->FldCaption() ?></span></td>
		<td<?php echo $academicmissions->EndDate->CellAttributes() ?>>
<span id="el_academicmissions_EndDate" class="control-group">
<span<?php echo $academicmissions->EndDate->ViewAttributes() ?>>
<?php echo $academicmissions->EndDate->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($academicmissions->PlaceVisited->Visible) { // PlaceVisited ?>
	<tr id="r_PlaceVisited">
		<td><span id="elh_academicmissions_PlaceVisited"><?php echo $academicmissions->PlaceVisited->FldCaption() ?></span></td>
		<td<?php echo $academicmissions->PlaceVisited->CellAttributes() ?>>
<span id="el_academicmissions_PlaceVisited" class="control-group">
<span<?php echo $academicmissions->PlaceVisited->ViewAttributes() ?>>
<?php echo $academicmissions->PlaceVisited->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($academicmissions->NatureOfVisit->Visible) { // NatureOfVisit ?>
	<tr id="r_NatureOfVisit">
		<td><span id="elh_academicmissions_NatureOfVisit"><?php echo $academicmissions->NatureOfVisit->FldCaption() ?></span></td>
		<td<?php echo $academicmissions->NatureOfVisit->CellAttributes() ?>>
<span id="el_academicmissions_NatureOfVisit" class="control-group">
<span<?php echo $academicmissions->NatureOfVisit->ViewAttributes() ?>>
<?php echo $academicmissions->NatureOfVisit->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($academicmissions->AttendanceOnly->Visible) { // AttendanceOnly ?>
	<tr id="r_AttendanceOnly">
		<td><span id="elh_academicmissions_AttendanceOnly"><?php echo $academicmissions->AttendanceOnly->FldCaption() ?></span></td>
		<td<?php echo $academicmissions->AttendanceOnly->CellAttributes() ?>>
<span id="el_academicmissions_AttendanceOnly" class="control-group">
<span<?php echo $academicmissions->AttendanceOnly->ViewAttributes() ?>>
<?php echo $academicmissions->AttendanceOnly->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($academicmissions->PresentAPaper->Visible) { // PresentAPaper ?>
	<tr id="r_PresentAPaper">
		<td><span id="elh_academicmissions_PresentAPaper"><?php echo $academicmissions->PresentAPaper->FldCaption() ?></span></td>
		<td<?php echo $academicmissions->PresentAPaper->CellAttributes() ?>>
<span id="el_academicmissions_PresentAPaper" class="control-group">
<span<?php echo $academicmissions->PresentAPaper->ViewAttributes() ?>>
<?php echo $academicmissions->PresentAPaper->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($academicmissions->Others->Visible) { // Others ?>
	<tr id="r_Others">
		<td><span id="elh_academicmissions_Others"><?php echo $academicmissions->Others->FldCaption() ?></span></td>
		<td<?php echo $academicmissions->Others->CellAttributes() ?>>
<span id="el_academicmissions_Others" class="control-group">
<span<?php echo $academicmissions->Others->ViewAttributes() ?>>
<?php echo $academicmissions->Others->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($academicmissions->Participation->Visible) { // Participation ?>
	<tr id="r_Participation">
		<td><span id="elh_academicmissions_Participation"><?php echo $academicmissions->Participation->FldCaption() ?></span></td>
		<td<?php echo $academicmissions->Participation->CellAttributes() ?>>
<span id="el_academicmissions_Participation" class="control-group">
<span<?php echo $academicmissions->Participation->ViewAttributes() ?>>
<?php echo $academicmissions->Participation->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($academicmissions->Summary->Visible) { // Summary ?>
	<tr id="r_Summary">
		<td><span id="elh_academicmissions_Summary"><?php echo $academicmissions->Summary->FldCaption() ?></span></td>
		<td<?php echo $academicmissions->Summary->CellAttributes() ?>>
<span id="el_academicmissions_Summary" class="control-group">
<span<?php echo $academicmissions->Summary->ViewAttributes() ?>>
<?php echo $academicmissions->Summary->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($academicmissions->SuggestionRecommendation->Visible) { // SuggestionRecommendation ?>
	<tr id="r_SuggestionRecommendation">
		<td><span id="elh_academicmissions_SuggestionRecommendation"><?php echo $academicmissions->SuggestionRecommendation->FldCaption() ?></span></td>
		<td<?php echo $academicmissions->SuggestionRecommendation->CellAttributes() ?>>
<span id="el_academicmissions_SuggestionRecommendation" class="control-group">
<span<?php echo $academicmissions->SuggestionRecommendation->ViewAttributes() ?>>
<?php echo $academicmissions->SuggestionRecommendation->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($academicmissions->FacultyMemberSign->Visible) { // FacultyMemberSign ?>
	<tr id="r_FacultyMemberSign">
		<td><span id="elh_academicmissions_FacultyMemberSign"><?php echo $academicmissions->FacultyMemberSign->FldCaption() ?></span></td>
		<td<?php echo $academicmissions->FacultyMemberSign->CellAttributes() ?>>
<span id="el_academicmissions_FacultyMemberSign" class="control-group">
<span<?php echo $academicmissions->FacultyMemberSign->ViewAttributes() ?>>
<?php echo $academicmissions->FacultyMemberSign->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($academicmissions->DepChairmanSign->Visible) { // DepChairmanSign ?>
	<tr id="r_DepChairmanSign">
		<td><span id="elh_academicmissions_DepChairmanSign"><?php echo $academicmissions->DepChairmanSign->FldCaption() ?></span></td>
		<td<?php echo $academicmissions->DepChairmanSign->CellAttributes() ?>>
<span id="el_academicmissions_DepChairmanSign" class="control-group">
<span<?php echo $academicmissions->DepChairmanSign->ViewAttributes() ?>>
<?php echo $academicmissions->DepChairmanSign->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($academicmissions->DeanSign->Visible) { // DeanSign ?>
	<tr id="r_DeanSign">
		<td><span id="elh_academicmissions_DeanSign"><?php echo $academicmissions->DeanSign->FldCaption() ?></span></td>
		<td<?php echo $academicmissions->DeanSign->CellAttributes() ?>>
<span id="el_academicmissions_DeanSign" class="control-group">
<span<?php echo $academicmissions->DeanSign->ViewAttributes() ?>>
<?php echo $academicmissions->DeanSign->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
</form>
<script type="text/javascript">
facademicmissionsview.Init();
</script>
<?php
$academicmissions_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($academicmissions->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$academicmissions_view->Page_Terminate();
?>
