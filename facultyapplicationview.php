<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "ewmysql10.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "facultyapplicationinfo.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$facultyapplication_view = NULL; // Initialize page object first

class cfacultyapplication_view extends cfacultyapplication {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{F8E6AD82-57C4-4BEE-8975-8D386184467A}";

	// Table name
	var $TableName = 'facultyapplication';

	// Page object name
	var $PageObjName = 'facultyapplication_view';

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

		// Table object (facultyapplication)
		if (!isset($GLOBALS["facultyapplication"]) || get_class($GLOBALS["facultyapplication"]) == "cfacultyapplication") {
			$GLOBALS["facultyapplication"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["facultyapplication"];
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
			define("EW_TABLE_NAME", 'facultyapplication', TRUE);

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
			$this->Page_Terminate("facultyapplicationlist.php");
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
				$sReturnUrl = "facultyapplicationlist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "facultyapplicationlist.php"; // No matching record, return to list
					}
			}

			// Export data only
			if (in_array($this->Export, array("html","word","excel","xml","csv","email","pdf"))) {
				$this->ExportData();
				$this->Page_Terminate(); // Terminate response
				exit();
			}
		} else {
			$sReturnUrl = "facultyapplicationlist.php"; // Not page request, return to list
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

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->ID->DbValue = $row['ID'];
		$this->Name->DbValue = $row['Name'];
		$this->Nationality->DbValue = $row['Nationality'];
		$this->College->DbValue = $row['College'];
		$this->Department->DbValue = $row['Department'];
		$this->FacultyAffairsDate->DbValue = $row['FacultyAffairsDate'];
		$this->FacultyAffairsRef->DbValue = $row['FacultyAffairsRef'];
		$this->CollegeDecision->DbValue = $row['CollegeDecision'];
		$this->CollegeDecisionDate->DbValue = $row['CollegeDecisionDate'];
		$this->CollegeDecisionRef->DbValue = $row['CollegeDecisionRef'];
		$this->CommitteeDecision->DbValue = $row['CommitteeDecision'];
		$this->CommitteeDecisionDate->DbValue = $row['CommitteeDecisionDate'];
		$this->CommitteeDecisionRef->DbValue = $row['CommitteeDecisionRef'];
		$this->PresidentDecision->DbValue = $row['PresidentDecision'];
		$this->PresidentDecisionDate->DbValue = $row['PresidentDecisionDate'];
		$this->PresidentDecisionRef->DbValue = $row['PresidentDecisionRef'];
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

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

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
		$item->Body = "<a id=\"emf_facultyapplication\" href=\"javascript:void(0);\" class=\"ewExportLink ewEmail\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_facultyapplication',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.ffacultyapplicationview,key:" . ew_ArrayToJsonAttr($this->RecKey) . ",sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
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
		$Breadcrumb->Add("list", $this->TableVar, "facultyapplicationlist.php", $this->TableVar, TRUE);
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
if (!isset($facultyapplication_view)) $facultyapplication_view = new cfacultyapplication_view();

// Page init
$facultyapplication_view->Page_Init();

// Page main
$facultyapplication_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$facultyapplication_view->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($facultyapplication->Export == "") { ?>
<script type="text/javascript">

// Page object
var facultyapplication_view = new ew_Page("facultyapplication_view");
facultyapplication_view.PageID = "view"; // Page ID
var EW_PAGE_ID = facultyapplication_view.PageID; // For backward compatibility

// Form object
var ffacultyapplicationview = new ew_Form("ffacultyapplicationview");

// Form_CustomValidate event
ffacultyapplicationview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ffacultyapplicationview.ValidateRequired = true;
<?php } else { ?>
ffacultyapplicationview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ffacultyapplicationview.Lists["x_Department"] = {"LinkField":"x_DID","Ajax":null,"AutoFill":false,"DisplayFields":["x_DepartmentName","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($facultyapplication->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($facultyapplication->Export == "") { ?>
<div class="ewViewExportOptions">
<?php $facultyapplication_view->ExportOptions->Render("body") ?>
<?php if (!$facultyapplication_view->ExportOptions->UseDropDownButton) { ?>
</div>
<div class="ewViewOtherOptions">
<?php } ?>
<?php
	foreach ($facultyapplication_view->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<?php } ?>
<?php $facultyapplication_view->ShowPageHeader(); ?>
<?php
$facultyapplication_view->ShowMessage();
?>
<form name="ffacultyapplicationview" id="ffacultyapplicationview" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="facultyapplication">
<table class="ewGrid"><tr><td>
<table id="tbl_facultyapplicationview" class="table table-bordered table-striped">
<?php if ($facultyapplication->ID->Visible) { // ID ?>
	<tr id="r_ID">
		<td><span id="elh_facultyapplication_ID"><?php echo $facultyapplication->ID->FldCaption() ?></span></td>
		<td<?php echo $facultyapplication->ID->CellAttributes() ?>>
<span id="el_facultyapplication_ID" class="control-group">
<span<?php echo $facultyapplication->ID->ViewAttributes() ?>>
<?php echo $facultyapplication->ID->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($facultyapplication->Name->Visible) { // Name ?>
	<tr id="r_Name">
		<td><span id="elh_facultyapplication_Name"><?php echo $facultyapplication->Name->FldCaption() ?></span></td>
		<td<?php echo $facultyapplication->Name->CellAttributes() ?>>
<span id="el_facultyapplication_Name" class="control-group">
<span<?php echo $facultyapplication->Name->ViewAttributes() ?>>
<?php echo $facultyapplication->Name->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($facultyapplication->Nationality->Visible) { // Nationality ?>
	<tr id="r_Nationality">
		<td><span id="elh_facultyapplication_Nationality"><?php echo $facultyapplication->Nationality->FldCaption() ?></span></td>
		<td<?php echo $facultyapplication->Nationality->CellAttributes() ?>>
<span id="el_facultyapplication_Nationality" class="control-group">
<span<?php echo $facultyapplication->Nationality->ViewAttributes() ?>>
<?php echo $facultyapplication->Nationality->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($facultyapplication->College->Visible) { // College ?>
	<tr id="r_College">
		<td><span id="elh_facultyapplication_College"><?php echo $facultyapplication->College->FldCaption() ?></span></td>
		<td<?php echo $facultyapplication->College->CellAttributes() ?>>
<span id="el_facultyapplication_College" class="control-group">
<span<?php echo $facultyapplication->College->ViewAttributes() ?>>
<?php echo $facultyapplication->College->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($facultyapplication->Department->Visible) { // Department ?>
	<tr id="r_Department">
		<td><span id="elh_facultyapplication_Department"><?php echo $facultyapplication->Department->FldCaption() ?></span></td>
		<td<?php echo $facultyapplication->Department->CellAttributes() ?>>
<span id="el_facultyapplication_Department" class="control-group">
<span<?php echo $facultyapplication->Department->ViewAttributes() ?>>
<?php echo $facultyapplication->Department->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($facultyapplication->FacultyAffairsDate->Visible) { // FacultyAffairsDate ?>
	<tr id="r_FacultyAffairsDate">
		<td><span id="elh_facultyapplication_FacultyAffairsDate"><?php echo $facultyapplication->FacultyAffairsDate->FldCaption() ?></span></td>
		<td<?php echo $facultyapplication->FacultyAffairsDate->CellAttributes() ?>>
<span id="el_facultyapplication_FacultyAffairsDate" class="control-group">
<span<?php echo $facultyapplication->FacultyAffairsDate->ViewAttributes() ?>>
<?php echo $facultyapplication->FacultyAffairsDate->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($facultyapplication->FacultyAffairsRef->Visible) { // FacultyAffairsRef ?>
	<tr id="r_FacultyAffairsRef">
		<td><span id="elh_facultyapplication_FacultyAffairsRef"><?php echo $facultyapplication->FacultyAffairsRef->FldCaption() ?></span></td>
		<td<?php echo $facultyapplication->FacultyAffairsRef->CellAttributes() ?>>
<span id="el_facultyapplication_FacultyAffairsRef" class="control-group">
<span<?php echo $facultyapplication->FacultyAffairsRef->ViewAttributes() ?>>
<?php echo $facultyapplication->FacultyAffairsRef->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($facultyapplication->CollegeDecision->Visible) { // CollegeDecision ?>
	<tr id="r_CollegeDecision">
		<td><span id="elh_facultyapplication_CollegeDecision"><?php echo $facultyapplication->CollegeDecision->FldCaption() ?></span></td>
		<td<?php echo $facultyapplication->CollegeDecision->CellAttributes() ?>>
<span id="el_facultyapplication_CollegeDecision" class="control-group">
<span<?php echo $facultyapplication->CollegeDecision->ViewAttributes() ?>>
<?php echo $facultyapplication->CollegeDecision->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($facultyapplication->CollegeDecisionDate->Visible) { // CollegeDecisionDate ?>
	<tr id="r_CollegeDecisionDate">
		<td><span id="elh_facultyapplication_CollegeDecisionDate"><?php echo $facultyapplication->CollegeDecisionDate->FldCaption() ?></span></td>
		<td<?php echo $facultyapplication->CollegeDecisionDate->CellAttributes() ?>>
<span id="el_facultyapplication_CollegeDecisionDate" class="control-group">
<span<?php echo $facultyapplication->CollegeDecisionDate->ViewAttributes() ?>>
<?php echo $facultyapplication->CollegeDecisionDate->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($facultyapplication->CollegeDecisionRef->Visible) { // CollegeDecisionRef ?>
	<tr id="r_CollegeDecisionRef">
		<td><span id="elh_facultyapplication_CollegeDecisionRef"><?php echo $facultyapplication->CollegeDecisionRef->FldCaption() ?></span></td>
		<td<?php echo $facultyapplication->CollegeDecisionRef->CellAttributes() ?>>
<span id="el_facultyapplication_CollegeDecisionRef" class="control-group">
<span<?php echo $facultyapplication->CollegeDecisionRef->ViewAttributes() ?>>
<?php echo $facultyapplication->CollegeDecisionRef->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($facultyapplication->CommitteeDecision->Visible) { // CommitteeDecision ?>
	<tr id="r_CommitteeDecision">
		<td><span id="elh_facultyapplication_CommitteeDecision"><?php echo $facultyapplication->CommitteeDecision->FldCaption() ?></span></td>
		<td<?php echo $facultyapplication->CommitteeDecision->CellAttributes() ?>>
<span id="el_facultyapplication_CommitteeDecision" class="control-group">
<span<?php echo $facultyapplication->CommitteeDecision->ViewAttributes() ?>>
<?php echo $facultyapplication->CommitteeDecision->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($facultyapplication->CommitteeDecisionDate->Visible) { // CommitteeDecisionDate ?>
	<tr id="r_CommitteeDecisionDate">
		<td><span id="elh_facultyapplication_CommitteeDecisionDate"><?php echo $facultyapplication->CommitteeDecisionDate->FldCaption() ?></span></td>
		<td<?php echo $facultyapplication->CommitteeDecisionDate->CellAttributes() ?>>
<span id="el_facultyapplication_CommitteeDecisionDate" class="control-group">
<span<?php echo $facultyapplication->CommitteeDecisionDate->ViewAttributes() ?>>
<?php echo $facultyapplication->CommitteeDecisionDate->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($facultyapplication->CommitteeDecisionRef->Visible) { // CommitteeDecisionRef ?>
	<tr id="r_CommitteeDecisionRef">
		<td><span id="elh_facultyapplication_CommitteeDecisionRef"><?php echo $facultyapplication->CommitteeDecisionRef->FldCaption() ?></span></td>
		<td<?php echo $facultyapplication->CommitteeDecisionRef->CellAttributes() ?>>
<span id="el_facultyapplication_CommitteeDecisionRef" class="control-group">
<span<?php echo $facultyapplication->CommitteeDecisionRef->ViewAttributes() ?>>
<?php echo $facultyapplication->CommitteeDecisionRef->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($facultyapplication->PresidentDecision->Visible) { // PresidentDecision ?>
	<tr id="r_PresidentDecision">
		<td><span id="elh_facultyapplication_PresidentDecision"><?php echo $facultyapplication->PresidentDecision->FldCaption() ?></span></td>
		<td<?php echo $facultyapplication->PresidentDecision->CellAttributes() ?>>
<span id="el_facultyapplication_PresidentDecision" class="control-group">
<span<?php echo $facultyapplication->PresidentDecision->ViewAttributes() ?>>
<?php echo $facultyapplication->PresidentDecision->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($facultyapplication->PresidentDecisionDate->Visible) { // PresidentDecisionDate ?>
	<tr id="r_PresidentDecisionDate">
		<td><span id="elh_facultyapplication_PresidentDecisionDate"><?php echo $facultyapplication->PresidentDecisionDate->FldCaption() ?></span></td>
		<td<?php echo $facultyapplication->PresidentDecisionDate->CellAttributes() ?>>
<span id="el_facultyapplication_PresidentDecisionDate" class="control-group">
<span<?php echo $facultyapplication->PresidentDecisionDate->ViewAttributes() ?>>
<?php echo $facultyapplication->PresidentDecisionDate->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($facultyapplication->PresidentDecisionRef->Visible) { // PresidentDecisionRef ?>
	<tr id="r_PresidentDecisionRef">
		<td><span id="elh_facultyapplication_PresidentDecisionRef"><?php echo $facultyapplication->PresidentDecisionRef->FldCaption() ?></span></td>
		<td<?php echo $facultyapplication->PresidentDecisionRef->CellAttributes() ?>>
<span id="el_facultyapplication_PresidentDecisionRef" class="control-group">
<span<?php echo $facultyapplication->PresidentDecisionRef->ViewAttributes() ?>>
<?php echo $facultyapplication->PresidentDecisionRef->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
</form>
<script type="text/javascript">
ffacultyapplicationview.Init();
</script>
<?php
$facultyapplication_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($facultyapplication->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$facultyapplication_view->Page_Terminate();
?>
