<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "ewmysql10.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "languageinstructorsinfo.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$languageinstructors_view = NULL; // Initialize page object first

class clanguageinstructors_view extends clanguageinstructors {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{F8E6AD82-57C4-4BEE-8975-8D386184467A}";

	// Table name
	var $TableName = 'languageinstructors';

	// Page object name
	var $PageObjName = 'languageinstructors_view';

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

		// Table object (languageinstructors)
		if (!isset($GLOBALS["languageinstructors"]) || get_class($GLOBALS["languageinstructors"]) == "clanguageinstructors") {
			$GLOBALS["languageinstructors"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["languageinstructors"];
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
			define("EW_TABLE_NAME", 'languageinstructors', TRUE);

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
			$this->Page_Terminate("languageinstructorslist.php");
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
				$sReturnUrl = "languageinstructorslist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "languageinstructorslist.php"; // No matching record, return to list
					}
			}

			// Export data only
			if (in_array($this->Export, array("html","word","excel","xml","csv","email","pdf"))) {
				$this->ExportData();
				$this->Page_Terminate(); // Terminate response
				exit();
			}
		} else {
			$sReturnUrl = "languageinstructorslist.php"; // Not page request, return to list
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

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->ID->DbValue = $row['ID'];
		$this->ApplicantName->DbValue = $row['ApplicantName'];
		$this->Nationality->DbValue = $row['Nationality'];
		$this->_Language->DbValue = $row['Language'];
		$this->College1->DbValue = $row['College1'];
		$this->College1SentDate->DbValue = $row['College1SentDate'];
		$this->College1Status->DbValue = $row['College1Status'];
		$this->College1ReplyDate->DbValue = $row['College1ReplyDate'];
		$this->College2->DbValue = $row['College2'];
		$this->College2SentDate->DbValue = $row['College2SentDate'];
		$this->College2Status->DbValue = $row['College2Status'];
		$this->College2ReplyDate->DbValue = $row['College2ReplyDate'];
		$this->College3->DbValue = $row['College3'];
		$this->College3SentDate->DbValue = $row['College3SentDate'];
		$this->College3Status->DbValue = $row['College3Status'];
		$this->College3ReplyDate->DbValue = $row['College3ReplyDate'];
		$this->CommitteDecision->DbValue = $row['CommitteDecision'];
		$this->CommitteDecisionDate->DbValue = $row['CommitteDecisionDate'];
		$this->CommitteRefNo->DbValue = $row['CommitteRefNo'];
		$this->PreidentsDecision->DbValue = $row['PreidentsDecision'];
		$this->PreidentsDecisionDate->DbValue = $row['PreidentsDecisionDate'];
		$this->PreidentsRefNo->DbValue = $row['PreidentsRefNo'];
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

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

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
		$item->Body = "<a id=\"emf_languageinstructors\" href=\"javascript:void(0);\" class=\"ewExportLink ewEmail\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_languageinstructors',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.flanguageinstructorsview,key:" . ew_ArrayToJsonAttr($this->RecKey) . ",sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
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
		$Breadcrumb->Add("list", $this->TableVar, "languageinstructorslist.php", $this->TableVar, TRUE);
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
if (!isset($languageinstructors_view)) $languageinstructors_view = new clanguageinstructors_view();

// Page init
$languageinstructors_view->Page_Init();

// Page main
$languageinstructors_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$languageinstructors_view->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($languageinstructors->Export == "") { ?>
<script type="text/javascript">

// Page object
var languageinstructors_view = new ew_Page("languageinstructors_view");
languageinstructors_view.PageID = "view"; // Page ID
var EW_PAGE_ID = languageinstructors_view.PageID; // For backward compatibility

// Form object
var flanguageinstructorsview = new ew_Form("flanguageinstructorsview");

// Form_CustomValidate event
flanguageinstructorsview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
flanguageinstructorsview.ValidateRequired = true;
<?php } else { ?>
flanguageinstructorsview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
flanguageinstructorsview.Lists["x_College1"] = {"LinkField":"x_CollegeID","Ajax":null,"AutoFill":false,"DisplayFields":["x_College_Name_EN","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
flanguageinstructorsview.Lists["x_College2"] = {"LinkField":"x_CollegeID","Ajax":null,"AutoFill":false,"DisplayFields":["x_College_Name_EN","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
flanguageinstructorsview.Lists["x_College3"] = {"LinkField":"x_CollegeID","Ajax":null,"AutoFill":false,"DisplayFields":["x_College_Name_EN","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($languageinstructors->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($languageinstructors->Export == "") { ?>
<div class="ewViewExportOptions">
<?php $languageinstructors_view->ExportOptions->Render("body") ?>
<?php if (!$languageinstructors_view->ExportOptions->UseDropDownButton) { ?>
</div>
<div class="ewViewOtherOptions">
<?php } ?>
<?php
	foreach ($languageinstructors_view->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<?php } ?>
<?php $languageinstructors_view->ShowPageHeader(); ?>
<?php
$languageinstructors_view->ShowMessage();
?>
<form name="flanguageinstructorsview" id="flanguageinstructorsview" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="languageinstructors">
<table class="ewGrid"><tr><td>
<table id="tbl_languageinstructorsview" class="table table-bordered table-striped">
<?php if ($languageinstructors->ID->Visible) { // ID ?>
	<tr id="r_ID">
		<td><span id="elh_languageinstructors_ID"><?php echo $languageinstructors->ID->FldCaption() ?></span></td>
		<td<?php echo $languageinstructors->ID->CellAttributes() ?>>
<span id="el_languageinstructors_ID" class="control-group">
<span<?php echo $languageinstructors->ID->ViewAttributes() ?>>
<?php echo $languageinstructors->ID->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($languageinstructors->ApplicantName->Visible) { // ApplicantName ?>
	<tr id="r_ApplicantName">
		<td><span id="elh_languageinstructors_ApplicantName"><?php echo $languageinstructors->ApplicantName->FldCaption() ?></span></td>
		<td<?php echo $languageinstructors->ApplicantName->CellAttributes() ?>>
<span id="el_languageinstructors_ApplicantName" class="control-group">
<span<?php echo $languageinstructors->ApplicantName->ViewAttributes() ?>>
<?php echo $languageinstructors->ApplicantName->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($languageinstructors->Nationality->Visible) { // Nationality ?>
	<tr id="r_Nationality">
		<td><span id="elh_languageinstructors_Nationality"><?php echo $languageinstructors->Nationality->FldCaption() ?></span></td>
		<td<?php echo $languageinstructors->Nationality->CellAttributes() ?>>
<span id="el_languageinstructors_Nationality" class="control-group">
<span<?php echo $languageinstructors->Nationality->ViewAttributes() ?>>
<?php echo $languageinstructors->Nationality->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($languageinstructors->_Language->Visible) { // Language ?>
	<tr id="r__Language">
		<td><span id="elh_languageinstructors__Language"><?php echo $languageinstructors->_Language->FldCaption() ?></span></td>
		<td<?php echo $languageinstructors->_Language->CellAttributes() ?>>
<span id="el_languageinstructors__Language" class="control-group">
<span<?php echo $languageinstructors->_Language->ViewAttributes() ?>>
<?php echo $languageinstructors->_Language->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($languageinstructors->College1->Visible) { // College1 ?>
	<tr id="r_College1">
		<td><span id="elh_languageinstructors_College1"><?php echo $languageinstructors->College1->FldCaption() ?></span></td>
		<td<?php echo $languageinstructors->College1->CellAttributes() ?>>
<span id="el_languageinstructors_College1" class="control-group">
<span<?php echo $languageinstructors->College1->ViewAttributes() ?>>
<?php echo $languageinstructors->College1->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($languageinstructors->College1SentDate->Visible) { // College1SentDate ?>
	<tr id="r_College1SentDate">
		<td><span id="elh_languageinstructors_College1SentDate"><?php echo $languageinstructors->College1SentDate->FldCaption() ?></span></td>
		<td<?php echo $languageinstructors->College1SentDate->CellAttributes() ?>>
<span id="el_languageinstructors_College1SentDate" class="control-group">
<span<?php echo $languageinstructors->College1SentDate->ViewAttributes() ?>>
<?php echo $languageinstructors->College1SentDate->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($languageinstructors->College1Status->Visible) { // College1Status ?>
	<tr id="r_College1Status">
		<td><span id="elh_languageinstructors_College1Status"><?php echo $languageinstructors->College1Status->FldCaption() ?></span></td>
		<td<?php echo $languageinstructors->College1Status->CellAttributes() ?>>
<span id="el_languageinstructors_College1Status" class="control-group">
<span<?php echo $languageinstructors->College1Status->ViewAttributes() ?>>
<?php echo $languageinstructors->College1Status->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($languageinstructors->College1ReplyDate->Visible) { // College1ReplyDate ?>
	<tr id="r_College1ReplyDate">
		<td><span id="elh_languageinstructors_College1ReplyDate"><?php echo $languageinstructors->College1ReplyDate->FldCaption() ?></span></td>
		<td<?php echo $languageinstructors->College1ReplyDate->CellAttributes() ?>>
<span id="el_languageinstructors_College1ReplyDate" class="control-group">
<span<?php echo $languageinstructors->College1ReplyDate->ViewAttributes() ?>>
<?php echo $languageinstructors->College1ReplyDate->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($languageinstructors->College2->Visible) { // College2 ?>
	<tr id="r_College2">
		<td><span id="elh_languageinstructors_College2"><?php echo $languageinstructors->College2->FldCaption() ?></span></td>
		<td<?php echo $languageinstructors->College2->CellAttributes() ?>>
<span id="el_languageinstructors_College2" class="control-group">
<span<?php echo $languageinstructors->College2->ViewAttributes() ?>>
<?php echo $languageinstructors->College2->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($languageinstructors->College2SentDate->Visible) { // College2SentDate ?>
	<tr id="r_College2SentDate">
		<td><span id="elh_languageinstructors_College2SentDate"><?php echo $languageinstructors->College2SentDate->FldCaption() ?></span></td>
		<td<?php echo $languageinstructors->College2SentDate->CellAttributes() ?>>
<span id="el_languageinstructors_College2SentDate" class="control-group">
<span<?php echo $languageinstructors->College2SentDate->ViewAttributes() ?>>
<?php echo $languageinstructors->College2SentDate->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($languageinstructors->College2Status->Visible) { // College2Status ?>
	<tr id="r_College2Status">
		<td><span id="elh_languageinstructors_College2Status"><?php echo $languageinstructors->College2Status->FldCaption() ?></span></td>
		<td<?php echo $languageinstructors->College2Status->CellAttributes() ?>>
<span id="el_languageinstructors_College2Status" class="control-group">
<span<?php echo $languageinstructors->College2Status->ViewAttributes() ?>>
<?php echo $languageinstructors->College2Status->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($languageinstructors->College2ReplyDate->Visible) { // College2ReplyDate ?>
	<tr id="r_College2ReplyDate">
		<td><span id="elh_languageinstructors_College2ReplyDate"><?php echo $languageinstructors->College2ReplyDate->FldCaption() ?></span></td>
		<td<?php echo $languageinstructors->College2ReplyDate->CellAttributes() ?>>
<span id="el_languageinstructors_College2ReplyDate" class="control-group">
<span<?php echo $languageinstructors->College2ReplyDate->ViewAttributes() ?>>
<?php echo $languageinstructors->College2ReplyDate->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($languageinstructors->College3->Visible) { // College3 ?>
	<tr id="r_College3">
		<td><span id="elh_languageinstructors_College3"><?php echo $languageinstructors->College3->FldCaption() ?></span></td>
		<td<?php echo $languageinstructors->College3->CellAttributes() ?>>
<span id="el_languageinstructors_College3" class="control-group">
<span<?php echo $languageinstructors->College3->ViewAttributes() ?>>
<?php echo $languageinstructors->College3->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($languageinstructors->College3SentDate->Visible) { // College3SentDate ?>
	<tr id="r_College3SentDate">
		<td><span id="elh_languageinstructors_College3SentDate"><?php echo $languageinstructors->College3SentDate->FldCaption() ?></span></td>
		<td<?php echo $languageinstructors->College3SentDate->CellAttributes() ?>>
<span id="el_languageinstructors_College3SentDate" class="control-group">
<span<?php echo $languageinstructors->College3SentDate->ViewAttributes() ?>>
<?php echo $languageinstructors->College3SentDate->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($languageinstructors->College3Status->Visible) { // College3Status ?>
	<tr id="r_College3Status">
		<td><span id="elh_languageinstructors_College3Status"><?php echo $languageinstructors->College3Status->FldCaption() ?></span></td>
		<td<?php echo $languageinstructors->College3Status->CellAttributes() ?>>
<span id="el_languageinstructors_College3Status" class="control-group">
<span<?php echo $languageinstructors->College3Status->ViewAttributes() ?>>
<?php echo $languageinstructors->College3Status->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($languageinstructors->College3ReplyDate->Visible) { // College3ReplyDate ?>
	<tr id="r_College3ReplyDate">
		<td><span id="elh_languageinstructors_College3ReplyDate"><?php echo $languageinstructors->College3ReplyDate->FldCaption() ?></span></td>
		<td<?php echo $languageinstructors->College3ReplyDate->CellAttributes() ?>>
<span id="el_languageinstructors_College3ReplyDate" class="control-group">
<span<?php echo $languageinstructors->College3ReplyDate->ViewAttributes() ?>>
<?php echo $languageinstructors->College3ReplyDate->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($languageinstructors->CommitteDecision->Visible) { // CommitteDecision ?>
	<tr id="r_CommitteDecision">
		<td><span id="elh_languageinstructors_CommitteDecision"><?php echo $languageinstructors->CommitteDecision->FldCaption() ?></span></td>
		<td<?php echo $languageinstructors->CommitteDecision->CellAttributes() ?>>
<span id="el_languageinstructors_CommitteDecision" class="control-group">
<span<?php echo $languageinstructors->CommitteDecision->ViewAttributes() ?>>
<?php echo $languageinstructors->CommitteDecision->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($languageinstructors->CommitteDecisionDate->Visible) { // CommitteDecisionDate ?>
	<tr id="r_CommitteDecisionDate">
		<td><span id="elh_languageinstructors_CommitteDecisionDate"><?php echo $languageinstructors->CommitteDecisionDate->FldCaption() ?></span></td>
		<td<?php echo $languageinstructors->CommitteDecisionDate->CellAttributes() ?>>
<span id="el_languageinstructors_CommitteDecisionDate" class="control-group">
<span<?php echo $languageinstructors->CommitteDecisionDate->ViewAttributes() ?>>
<?php echo $languageinstructors->CommitteDecisionDate->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($languageinstructors->CommitteRefNo->Visible) { // CommitteRefNo ?>
	<tr id="r_CommitteRefNo">
		<td><span id="elh_languageinstructors_CommitteRefNo"><?php echo $languageinstructors->CommitteRefNo->FldCaption() ?></span></td>
		<td<?php echo $languageinstructors->CommitteRefNo->CellAttributes() ?>>
<span id="el_languageinstructors_CommitteRefNo" class="control-group">
<span<?php echo $languageinstructors->CommitteRefNo->ViewAttributes() ?>>
<?php echo $languageinstructors->CommitteRefNo->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($languageinstructors->PreidentsDecision->Visible) { // PreidentsDecision ?>
	<tr id="r_PreidentsDecision">
		<td><span id="elh_languageinstructors_PreidentsDecision"><?php echo $languageinstructors->PreidentsDecision->FldCaption() ?></span></td>
		<td<?php echo $languageinstructors->PreidentsDecision->CellAttributes() ?>>
<span id="el_languageinstructors_PreidentsDecision" class="control-group">
<span<?php echo $languageinstructors->PreidentsDecision->ViewAttributes() ?>>
<?php echo $languageinstructors->PreidentsDecision->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($languageinstructors->PreidentsDecisionDate->Visible) { // PreidentsDecisionDate ?>
	<tr id="r_PreidentsDecisionDate">
		<td><span id="elh_languageinstructors_PreidentsDecisionDate"><?php echo $languageinstructors->PreidentsDecisionDate->FldCaption() ?></span></td>
		<td<?php echo $languageinstructors->PreidentsDecisionDate->CellAttributes() ?>>
<span id="el_languageinstructors_PreidentsDecisionDate" class="control-group">
<span<?php echo $languageinstructors->PreidentsDecisionDate->ViewAttributes() ?>>
<?php echo $languageinstructors->PreidentsDecisionDate->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($languageinstructors->PreidentsRefNo->Visible) { // PreidentsRefNo ?>
	<tr id="r_PreidentsRefNo">
		<td><span id="elh_languageinstructors_PreidentsRefNo"><?php echo $languageinstructors->PreidentsRefNo->FldCaption() ?></span></td>
		<td<?php echo $languageinstructors->PreidentsRefNo->CellAttributes() ?>>
<span id="el_languageinstructors_PreidentsRefNo" class="control-group">
<span<?php echo $languageinstructors->PreidentsRefNo->ViewAttributes() ?>>
<?php echo $languageinstructors->PreidentsRefNo->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
</form>
<script type="text/javascript">
flanguageinstructorsview.Init();
</script>
<?php
$languageinstructors_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($languageinstructors->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$languageinstructors_view->Page_Terminate();
?>
