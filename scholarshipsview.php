<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "ewmysql10.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "scholarshipsinfo.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$scholarships_view = NULL; // Initialize page object first

class cscholarships_view extends cscholarships {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{F8E6AD82-57C4-4BEE-8975-8D386184467A}";

	// Table name
	var $TableName = 'scholarships';

	// Page object name
	var $PageObjName = 'scholarships_view';

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

		// Table object (scholarships)
		if (!isset($GLOBALS["scholarships"]) || get_class($GLOBALS["scholarships"]) == "cscholarships") {
			$GLOBALS["scholarships"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["scholarships"];
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
			define("EW_TABLE_NAME", 'scholarships', TRUE);

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
			$this->Page_Terminate("scholarshipslist.php");
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
				$sReturnUrl = "scholarshipslist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "scholarshipslist.php"; // No matching record, return to list
					}
			}

			// Export data only
			if (in_array($this->Export, array("html","word","excel","xml","csv","email","pdf"))) {
				$this->ExportData();
				$this->Page_Terminate(); // Terminate response
				exit();
			}
		} else {
			$sReturnUrl = "scholarshipslist.php"; // Not page request, return to list
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

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->ID->DbValue = $row['ID'];
		$this->English_Name->DbValue = $row['English Name'];
		$this->Arabic_Name->DbValue = $row['Arabic Name'];
		$this->College->DbValue = $row['College'];
		$this->Department->DbValue = $row['Department'];
		$this->Major->DbValue = $row['Major'];
		$this->GPA->DbValue = $row['GPA'];
		$this->Graduated_From->DbValue = $row['Graduated From'];
		$this->Acceptance_Counrty->DbValue = $row['Acceptance Counrty'];
		$this->Acceptance_University->DbValue = $row['Acceptance University'];
		$this->Program_Degree->DbValue = $row['Program Degree'];
		$this->Notes->DbValue = $row['Notes'];
		$this->Committee_Date->DbValue = $row['Committee Date'];
		$this->Status->DbValue = $row['Status'];
		$this->Justification->DbValue = $row['Justification'];
		$this->LastModifiedUser->DbValue = $row['LastModifiedUser'];
		$this->LastModifiedTime->DbValue = $row['LastModifiedTime'];
		$this->LastModifiedIP->DbValue = $row['LastModifiedIP'];
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

		// Convert decimal values if posted back
		if ($this->GPA->FormValue == $this->GPA->CurrentValue && is_numeric(ew_StrToFloat($this->GPA->CurrentValue)))
			$this->GPA->CurrentValue = ew_StrToFloat($this->GPA->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
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

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

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
		$item->Body = "<a id=\"emf_scholarships\" href=\"javascript:void(0);\" class=\"ewExportLink ewEmail\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_scholarships',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.fscholarshipsview,key:" . ew_ArrayToJsonAttr($this->RecKey) . ",sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
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
		$Breadcrumb->Add("list", $this->TableVar, "scholarshipslist.php", $this->TableVar, TRUE);
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
if (!isset($scholarships_view)) $scholarships_view = new cscholarships_view();

// Page init
$scholarships_view->Page_Init();

// Page main
$scholarships_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$scholarships_view->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($scholarships->Export == "") { ?>
<script type="text/javascript">

// Page object
var scholarships_view = new ew_Page("scholarships_view");
scholarships_view.PageID = "view"; // Page ID
var EW_PAGE_ID = scholarships_view.PageID; // For backward compatibility

// Form object
var fscholarshipsview = new ew_Form("fscholarshipsview");

// Form_CustomValidate event
fscholarshipsview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fscholarshipsview.ValidateRequired = true;
<?php } else { ?>
fscholarshipsview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fscholarshipsview.Lists["x_College"] = {"LinkField":"x_CollegeID","Ajax":null,"AutoFill":false,"DisplayFields":["x_College_Name_AR","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fscholarshipsview.Lists["x_Department"] = {"LinkField":"x_DID","Ajax":null,"AutoFill":false,"DisplayFields":["x_DepartmentName2DAR","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($scholarships->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($scholarships->Export == "") { ?>
<div class="ewViewExportOptions">
<?php $scholarships_view->ExportOptions->Render("body") ?>
<?php if (!$scholarships_view->ExportOptions->UseDropDownButton) { ?>
</div>
<div class="ewViewOtherOptions">
<?php } ?>
<?php
	foreach ($scholarships_view->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<?php } ?>
<?php $scholarships_view->ShowPageHeader(); ?>
<?php
$scholarships_view->ShowMessage();
?>
<form name="fscholarshipsview" id="fscholarshipsview" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="scholarships">
<table class="ewGrid"><tr><td>
<table id="tbl_scholarshipsview" class="table table-bordered table-striped">
<?php if ($scholarships->ID->Visible) { // ID ?>
	<tr id="r_ID">
		<td><span id="elh_scholarships_ID"><?php echo $scholarships->ID->FldCaption() ?></span></td>
		<td<?php echo $scholarships->ID->CellAttributes() ?>>
<span id="el_scholarships_ID" class="control-group">
<span<?php echo $scholarships->ID->ViewAttributes() ?>>
<?php echo $scholarships->ID->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($scholarships->English_Name->Visible) { // English Name ?>
	<tr id="r_English_Name">
		<td><span id="elh_scholarships_English_Name"><?php echo $scholarships->English_Name->FldCaption() ?></span></td>
		<td<?php echo $scholarships->English_Name->CellAttributes() ?>>
<span id="el_scholarships_English_Name" class="control-group">
<span<?php echo $scholarships->English_Name->ViewAttributes() ?>>
<?php echo $scholarships->English_Name->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($scholarships->Arabic_Name->Visible) { // Arabic Name ?>
	<tr id="r_Arabic_Name">
		<td><span id="elh_scholarships_Arabic_Name"><?php echo $scholarships->Arabic_Name->FldCaption() ?></span></td>
		<td<?php echo $scholarships->Arabic_Name->CellAttributes() ?>>
<span id="el_scholarships_Arabic_Name" class="control-group">
<span<?php echo $scholarships->Arabic_Name->ViewAttributes() ?>>
<?php echo $scholarships->Arabic_Name->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($scholarships->College->Visible) { // College ?>
	<tr id="r_College">
		<td><span id="elh_scholarships_College"><?php echo $scholarships->College->FldCaption() ?></span></td>
		<td<?php echo $scholarships->College->CellAttributes() ?>>
<span id="el_scholarships_College" class="control-group">
<span<?php echo $scholarships->College->ViewAttributes() ?>>
<?php echo $scholarships->College->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($scholarships->Department->Visible) { // Department ?>
	<tr id="r_Department">
		<td><span id="elh_scholarships_Department"><?php echo $scholarships->Department->FldCaption() ?></span></td>
		<td<?php echo $scholarships->Department->CellAttributes() ?>>
<span id="el_scholarships_Department" class="control-group">
<span<?php echo $scholarships->Department->ViewAttributes() ?>>
<?php echo $scholarships->Department->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($scholarships->Major->Visible) { // Major ?>
	<tr id="r_Major">
		<td><span id="elh_scholarships_Major"><?php echo $scholarships->Major->FldCaption() ?></span></td>
		<td<?php echo $scholarships->Major->CellAttributes() ?>>
<span id="el_scholarships_Major" class="control-group">
<span<?php echo $scholarships->Major->ViewAttributes() ?>>
<?php echo $scholarships->Major->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($scholarships->GPA->Visible) { // GPA ?>
	<tr id="r_GPA">
		<td><span id="elh_scholarships_GPA"><?php echo $scholarships->GPA->FldCaption() ?></span></td>
		<td<?php echo $scholarships->GPA->CellAttributes() ?>>
<span id="el_scholarships_GPA" class="control-group">
<span<?php echo $scholarships->GPA->ViewAttributes() ?>>
<?php echo $scholarships->GPA->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($scholarships->Graduated_From->Visible) { // Graduated From ?>
	<tr id="r_Graduated_From">
		<td><span id="elh_scholarships_Graduated_From"><?php echo $scholarships->Graduated_From->FldCaption() ?></span></td>
		<td<?php echo $scholarships->Graduated_From->CellAttributes() ?>>
<span id="el_scholarships_Graduated_From" class="control-group">
<span<?php echo $scholarships->Graduated_From->ViewAttributes() ?>>
<?php echo $scholarships->Graduated_From->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($scholarships->Acceptance_Counrty->Visible) { // Acceptance Counrty ?>
	<tr id="r_Acceptance_Counrty">
		<td><span id="elh_scholarships_Acceptance_Counrty"><?php echo $scholarships->Acceptance_Counrty->FldCaption() ?></span></td>
		<td<?php echo $scholarships->Acceptance_Counrty->CellAttributes() ?>>
<span id="el_scholarships_Acceptance_Counrty" class="control-group">
<span<?php echo $scholarships->Acceptance_Counrty->ViewAttributes() ?>>
<?php echo $scholarships->Acceptance_Counrty->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($scholarships->Acceptance_University->Visible) { // Acceptance University ?>
	<tr id="r_Acceptance_University">
		<td><span id="elh_scholarships_Acceptance_University"><?php echo $scholarships->Acceptance_University->FldCaption() ?></span></td>
		<td<?php echo $scholarships->Acceptance_University->CellAttributes() ?>>
<span id="el_scholarships_Acceptance_University" class="control-group">
<span<?php echo $scholarships->Acceptance_University->ViewAttributes() ?>>
<?php echo $scholarships->Acceptance_University->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($scholarships->Program_Degree->Visible) { // Program Degree ?>
	<tr id="r_Program_Degree">
		<td><span id="elh_scholarships_Program_Degree"><?php echo $scholarships->Program_Degree->FldCaption() ?></span></td>
		<td<?php echo $scholarships->Program_Degree->CellAttributes() ?>>
<span id="el_scholarships_Program_Degree" class="control-group">
<span<?php echo $scholarships->Program_Degree->ViewAttributes() ?>>
<?php echo $scholarships->Program_Degree->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($scholarships->Notes->Visible) { // Notes ?>
	<tr id="r_Notes">
		<td><span id="elh_scholarships_Notes"><?php echo $scholarships->Notes->FldCaption() ?></span></td>
		<td<?php echo $scholarships->Notes->CellAttributes() ?>>
<span id="el_scholarships_Notes" class="control-group">
<span<?php echo $scholarships->Notes->ViewAttributes() ?>>
<?php echo $scholarships->Notes->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($scholarships->Committee_Date->Visible) { // Committee Date ?>
	<tr id="r_Committee_Date">
		<td><span id="elh_scholarships_Committee_Date"><?php echo $scholarships->Committee_Date->FldCaption() ?></span></td>
		<td<?php echo $scholarships->Committee_Date->CellAttributes() ?>>
<span id="el_scholarships_Committee_Date" class="control-group">
<span<?php echo $scholarships->Committee_Date->ViewAttributes() ?>>
<?php echo $scholarships->Committee_Date->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($scholarships->Status->Visible) { // Status ?>
	<tr id="r_Status">
		<td><span id="elh_scholarships_Status"><?php echo $scholarships->Status->FldCaption() ?></span></td>
		<td<?php echo $scholarships->Status->CellAttributes() ?>>
<span id="el_scholarships_Status" class="control-group">
<span<?php echo $scholarships->Status->ViewAttributes() ?>>
<?php echo $scholarships->Status->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($scholarships->Justification->Visible) { // Justification ?>
	<tr id="r_Justification">
		<td><span id="elh_scholarships_Justification"><?php echo $scholarships->Justification->FldCaption() ?></span></td>
		<td<?php echo $scholarships->Justification->CellAttributes() ?>>
<span id="el_scholarships_Justification" class="control-group">
<span<?php echo $scholarships->Justification->ViewAttributes() ?>>
<?php echo $scholarships->Justification->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($scholarships->LastModifiedUser->Visible) { // LastModifiedUser ?>
	<tr id="r_LastModifiedUser">
		<td><span id="elh_scholarships_LastModifiedUser"><?php echo $scholarships->LastModifiedUser->FldCaption() ?></span></td>
		<td<?php echo $scholarships->LastModifiedUser->CellAttributes() ?>>
<span id="el_scholarships_LastModifiedUser" class="control-group">
<span<?php echo $scholarships->LastModifiedUser->ViewAttributes() ?>>
<?php echo $scholarships->LastModifiedUser->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($scholarships->LastModifiedTime->Visible) { // LastModifiedTime ?>
	<tr id="r_LastModifiedTime">
		<td><span id="elh_scholarships_LastModifiedTime"><?php echo $scholarships->LastModifiedTime->FldCaption() ?></span></td>
		<td<?php echo $scholarships->LastModifiedTime->CellAttributes() ?>>
<span id="el_scholarships_LastModifiedTime" class="control-group">
<span<?php echo $scholarships->LastModifiedTime->ViewAttributes() ?>>
<?php echo $scholarships->LastModifiedTime->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($scholarships->LastModifiedIP->Visible) { // LastModifiedIP ?>
	<tr id="r_LastModifiedIP">
		<td><span id="elh_scholarships_LastModifiedIP"><?php echo $scholarships->LastModifiedIP->FldCaption() ?></span></td>
		<td<?php echo $scholarships->LastModifiedIP->CellAttributes() ?>>
<span id="el_scholarships_LastModifiedIP" class="control-group">
<span<?php echo $scholarships->LastModifiedIP->ViewAttributes() ?>>
<?php echo $scholarships->LastModifiedIP->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
</form>
<script type="text/javascript">
fscholarshipsview.Init();
</script>
<?php
$scholarships_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($scholarships->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$scholarships_view->Page_Terminate();
?>
