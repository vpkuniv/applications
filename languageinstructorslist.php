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

$languageinstructors_list = NULL; // Initialize page object first

class clanguageinstructors_list extends clanguageinstructors {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{F8E6AD82-57C4-4BEE-8975-8D386184467A}";

	// Table name
	var $TableName = 'languageinstructors';

	// Page object name
	var $PageObjName = 'languageinstructors_list';

	// Grid form hidden field names
	var $FormName = 'flanguageinstructorslist';
	var $FormActionName = 'k_action';
	var $FormKeyName = 'k_key';
	var $FormOldKeyName = 'k_oldkey';
	var $FormBlankRowName = 'k_blankrow';
	var $FormKeyCountName = 'key_count';

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
	var $AuditTrailOnEdit = TRUE;
	var $AuditTrailOnDelete = TRUE;

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

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "languageinstructorsadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "languageinstructorsdelete.php";
		$this->MultiUpdateUrl = "languageinstructorsupdate.php";

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'languageinstructors', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();

		// List options
		$this->ListOptions = new cListOptions();
		$this->ListOptions->TableVar = $this->TableVar;

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['addedit'] = new cListOptions();
		$this->OtherOptions['addedit']->Tag = "div";
		$this->OtherOptions['addedit']->TagClassName = "ewAddEditOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";
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
		if (!$Security->CanList()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate("login.php");
		}

		// Create form object
		$objForm = new cFormObj();

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
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();

		// Setup export options
		$this->SetupExportOptions();
		$this->ID->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Setup other options
		$this->SetupOtherOptions();

		// Set "checkbox" visible
		if (count($this->CustomActions) > 0)
			$this->ListOptions->Items["checkbox"]->Visible = TRUE;

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

	// Class variables
	var $ListOptions; // List options
	var $ExportOptions; // Export options
	var $OtherOptions = array(); // Other options
	var $DisplayRecs = 100;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $SearchWhere = ""; // Search WHERE clause
	var $RecCnt = 0; // Record count
	var $EditRowCnt;
	var $StartRowCnt = 1;
	var $RowCnt = 0;
	var $Attrs = array(); // Row attributes and cell attributes
	var $RowIndex = 0; // Row index
	var $KeyCount = 0; // Key count
	var $RowAction = ""; // Row action
	var $RowOldKey = ""; // Row old key (for copy)
	var $RecPerRow = 0;
	var $ColCnt = 0;
	var $DbMasterFilter = ""; // Master filter
	var $DbDetailFilter = ""; // Detail filter
	var $MasterRecordExists;	
	var $MultiSelectKey;
	var $Command;
	var $RestoreSearch = FALSE;
	var $Recordset;
	var $OldRecordset;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError, $gsSearchError, $Security;

		// Search filters
		$sSrchAdvanced = ""; // Advanced search filter
		$sSrchBasic = ""; // Basic search filter
		$sFilter = "";

		// Get command
		$this->Command = strtolower(@$_GET["cmd"]);
		if ($this->IsPageRequest()) { // Validate request

			// Process custom action first
			$this->ProcessCustomAction();

			// Handle reset command
			$this->ResetCmd();

			// Set up Breadcrumb
			$this->SetupBreadcrumb();

			// Check QueryString parameters
			if (@$_GET["a"] <> "") {
				$this->CurrentAction = $_GET["a"];

				// Clear inline mode
				if ($this->CurrentAction == "cancel")
					$this->ClearInlineMode();

				// Switch to inline edit mode
				if ($this->CurrentAction == "edit")
					$this->InlineEditMode();
			} else {
				if (@$_POST["a_list"] <> "") {
					$this->CurrentAction = $_POST["a_list"]; // Get action

					// Inline Update
					if (($this->CurrentAction == "update" || $this->CurrentAction == "overwrite") && @$_SESSION[EW_SESSION_INLINE_MODE] == "edit")
						$this->InlineUpdate();
				}
			}

			// Hide list options
			if ($this->Export <> "") {
				$this->ListOptions->HideAllOptions(array("sequence"));
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			} elseif ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
				$this->ListOptions->HideAllOptions();
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			}

			// Hide export options
			if ($this->Export <> "" || $this->CurrentAction <> "")
				$this->ExportOptions->HideAllOptions();

			// Hide other options
			if ($this->Export <> "") {
				foreach ($this->OtherOptions as &$option)
					$option->HideAllOptions();
			}

			// Get and validate search values for advanced search
			$this->LoadSearchValues(); // Get search values
			if (!$this->ValidateSearch())
				$this->setFailureMessage($gsSearchError);

			// Restore search parms from Session if not searching / reset
			if ($this->Command <> "search" && $this->Command <> "reset" && $this->Command <> "resetall" && $this->CheckSearchParms())
				$this->RestoreSearchParms();

			// Call Recordset SearchValidated event
			$this->Recordset_SearchValidated();

			// Set up sorting order
			$this->SetUpSortOrder();

			// Get search criteria for advanced search
			if ($gsSearchError == "")
				$sSrchAdvanced = $this->AdvancedSearchWhere();
		}

		// Restore display records
		if ($this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 100; // Load default
		}

		// Load Sorting Order
		$this->LoadSortOrder();

		// Load search default if no existing search criteria
		if (!$this->CheckSearchParms()) {

			// Load advanced search from default
			if ($this->LoadAdvancedSearchDefault()) {
				$sSrchAdvanced = $this->AdvancedSearchWhere();
			}
		}

		// Build search criteria
		ew_AddFilter($this->SearchWhere, $sSrchAdvanced);
		ew_AddFilter($this->SearchWhere, $sSrchBasic);

		// Call Recordset_Searching event
		$this->Recordset_Searching($this->SearchWhere);

		// Save search criteria
		if ($this->Command == "search" && !$this->RestoreSearch) {
			$this->setSearchWhere($this->SearchWhere); // Save to Session
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} else {
			$this->SearchWhere = $this->getSearchWhere();
		}

		// Build filter
		$sFilter = "";
		if (!$Security->CanList())
			$sFilter = "(0=1)"; // Filter all records
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Set up filter in session
		$this->setSessionWhere($sFilter);
		$this->CurrentFilter = "";

		// Export data only
		if (in_array($this->Export, array("html","word","excel","xml","csv","email","pdf"))) {
			$this->ExportData();
			$this->Page_Terminate(); // Terminate response
			exit();
		}
	}

	//  Exit inline mode
	function ClearInlineMode() {
		$this->setKey("ID", ""); // Clear inline edit key
		$this->LastAction = $this->CurrentAction; // Save last action
		$this->CurrentAction = ""; // Clear action
		$_SESSION[EW_SESSION_INLINE_MODE] = ""; // Clear inline mode
	}

	// Switch to Inline Edit mode
	function InlineEditMode() {
		global $Security, $Language;
		if (!$Security->CanEdit())
			$this->Page_Terminate("login.php"); // Go to login page
		$bInlineEdit = TRUE;
		if (@$_GET["ID"] <> "") {
			$this->ID->setQueryStringValue($_GET["ID"]);
		} else {
			$bInlineEdit = FALSE;
		}
		if ($bInlineEdit) {
			if ($this->LoadRow()) {
				$this->setKey("ID", $this->ID->CurrentValue); // Set up inline edit key
				$_SESSION[EW_SESSION_INLINE_MODE] = "edit"; // Enable inline edit
			}
		}
	}

	// Perform update to Inline Edit record
	function InlineUpdate() {
		global $Language, $objForm, $gsFormError;
		$objForm->Index = 1; 
		$this->LoadFormValues(); // Get form values

		// Validate form
		$bInlineUpdate = TRUE;
		if (!$this->ValidateForm()) {	
			$bInlineUpdate = FALSE; // Form error, reset action
			$this->setFailureMessage($gsFormError);
		} else {
			$bInlineUpdate = FALSE;
			$rowkey = strval($objForm->GetValue($this->FormKeyName));
			if ($this->SetupKeyValues($rowkey)) { // Set up key values
				if ($this->CheckInlineEditKey()) { // Check key
					$this->SendEmail = TRUE; // Send email on update success
					$bInlineUpdate = $this->EditRow(); // Update record
				} else {
					$bInlineUpdate = FALSE;
				}
			}
		}
		if ($bInlineUpdate) { // Update success
			if ($this->getSuccessMessage() == "")
				$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Set up success message
			$this->ClearInlineMode(); // Clear inline edit mode
		} else {
			if ($this->getFailureMessage() == "")
				$this->setFailureMessage($Language->Phrase("UpdateFailed")); // Set update failed message
			$this->EventCancelled = TRUE; // Cancel event
			$this->CurrentAction = "edit"; // Stay in edit mode
		}
	}

	// Check Inline Edit key
	function CheckInlineEditKey() {

		//CheckInlineEditKey = True
		if (strval($this->getKey("ID")) <> strval($this->ID->CurrentValue))
			return FALSE;
		return TRUE;
	}

	// Build filter for all keys
	function BuildKeyFilter() {
		global $objForm;
		$sWrkFilter = "";

		// Update row index and get row key
		$rowindex = 1;
		$objForm->Index = $rowindex;
		$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		while ($sThisKey <> "") {
			if ($this->SetupKeyValues($sThisKey)) {
				$sFilter = $this->KeyFilter();
				if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
				$sWrkFilter .= $sFilter;
			} else {
				$sWrkFilter = "0=1";
				break;
			}

			// Update row index and get row key
			$rowindex++; // Next row
			$objForm->Index = $rowindex;
			$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		}
		return $sWrkFilter;
	}

	// Set up key values
	function SetupKeyValues($key) {
		$arrKeyFlds = explode($GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"], $key);
		if (count($arrKeyFlds) >= 1) {
			$this->ID->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->ID->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere() {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->ID, FALSE); // ID
		$this->BuildSearchSql($sWhere, $this->ApplicantName, FALSE); // ApplicantName
		$this->BuildSearchSql($sWhere, $this->Nationality, FALSE); // Nationality
		$this->BuildSearchSql($sWhere, $this->_Language, FALSE); // Language
		$this->BuildSearchSql($sWhere, $this->College1, FALSE); // College1
		$this->BuildSearchSql($sWhere, $this->College1SentDate, FALSE); // College1SentDate
		$this->BuildSearchSql($sWhere, $this->College1Status, FALSE); // College1Status
		$this->BuildSearchSql($sWhere, $this->College1ReplyDate, FALSE); // College1ReplyDate
		$this->BuildSearchSql($sWhere, $this->College2, FALSE); // College2
		$this->BuildSearchSql($sWhere, $this->College2SentDate, FALSE); // College2SentDate
		$this->BuildSearchSql($sWhere, $this->College2Status, FALSE); // College2Status
		$this->BuildSearchSql($sWhere, $this->College2ReplyDate, FALSE); // College2ReplyDate
		$this->BuildSearchSql($sWhere, $this->College3, FALSE); // College3
		$this->BuildSearchSql($sWhere, $this->College3SentDate, FALSE); // College3SentDate
		$this->BuildSearchSql($sWhere, $this->College3Status, FALSE); // College3Status
		$this->BuildSearchSql($sWhere, $this->College3ReplyDate, FALSE); // College3ReplyDate
		$this->BuildSearchSql($sWhere, $this->CommitteDecision, FALSE); // CommitteDecision
		$this->BuildSearchSql($sWhere, $this->CommitteDecisionDate, FALSE); // CommitteDecisionDate
		$this->BuildSearchSql($sWhere, $this->CommitteRefNo, FALSE); // CommitteRefNo
		$this->BuildSearchSql($sWhere, $this->PreidentsDecision, FALSE); // PreidentsDecision
		$this->BuildSearchSql($sWhere, $this->PreidentsDecisionDate, FALSE); // PreidentsDecisionDate
		$this->BuildSearchSql($sWhere, $this->PreidentsRefNo, FALSE); // PreidentsRefNo

		// Set up search parm
		if ($sWhere <> "") {
			$this->Command = "search";
		}
		if ($this->Command == "search") {
			$this->ID->AdvancedSearch->Save(); // ID
			$this->ApplicantName->AdvancedSearch->Save(); // ApplicantName
			$this->Nationality->AdvancedSearch->Save(); // Nationality
			$this->_Language->AdvancedSearch->Save(); // Language
			$this->College1->AdvancedSearch->Save(); // College1
			$this->College1SentDate->AdvancedSearch->Save(); // College1SentDate
			$this->College1Status->AdvancedSearch->Save(); // College1Status
			$this->College1ReplyDate->AdvancedSearch->Save(); // College1ReplyDate
			$this->College2->AdvancedSearch->Save(); // College2
			$this->College2SentDate->AdvancedSearch->Save(); // College2SentDate
			$this->College2Status->AdvancedSearch->Save(); // College2Status
			$this->College2ReplyDate->AdvancedSearch->Save(); // College2ReplyDate
			$this->College3->AdvancedSearch->Save(); // College3
			$this->College3SentDate->AdvancedSearch->Save(); // College3SentDate
			$this->College3Status->AdvancedSearch->Save(); // College3Status
			$this->College3ReplyDate->AdvancedSearch->Save(); // College3ReplyDate
			$this->CommitteDecision->AdvancedSearch->Save(); // CommitteDecision
			$this->CommitteDecisionDate->AdvancedSearch->Save(); // CommitteDecisionDate
			$this->CommitteRefNo->AdvancedSearch->Save(); // CommitteRefNo
			$this->PreidentsDecision->AdvancedSearch->Save(); // PreidentsDecision
			$this->PreidentsDecisionDate->AdvancedSearch->Save(); // PreidentsDecisionDate
			$this->PreidentsRefNo->AdvancedSearch->Save(); // PreidentsRefNo
		}
		return $sWhere;
	}

	// Build search SQL
	function BuildSearchSql(&$Where, &$Fld, $MultiValue) {
		$FldParm = substr($Fld->FldVar, 2);
		$FldVal = $Fld->AdvancedSearch->SearchValue; // @$_GET["x_$FldParm"]
		$FldOpr = $Fld->AdvancedSearch->SearchOperator; // @$_GET["z_$FldParm"]
		$FldCond = $Fld->AdvancedSearch->SearchCondition; // @$_GET["v_$FldParm"]
		$FldVal2 = $Fld->AdvancedSearch->SearchValue2; // @$_GET["y_$FldParm"]
		$FldOpr2 = $Fld->AdvancedSearch->SearchOperator2; // @$_GET["w_$FldParm"]
		$sWrk = "";

		//$FldVal = ew_StripSlashes($FldVal);
		if (is_array($FldVal)) $FldVal = implode(",", $FldVal);

		//$FldVal2 = ew_StripSlashes($FldVal2);
		if (is_array($FldVal2)) $FldVal2 = implode(",", $FldVal2);
		$FldOpr = strtoupper(trim($FldOpr));
		if ($FldOpr == "") $FldOpr = "=";
		$FldOpr2 = strtoupper(trim($FldOpr2));
		if ($FldOpr2 == "") $FldOpr2 = "=";
		if (EW_SEARCH_MULTI_VALUE_OPTION == 1 || $FldOpr <> "LIKE" ||
			($FldOpr2 <> "LIKE" && $FldVal2 <> ""))
			$MultiValue = FALSE;
		if ($MultiValue) {
			$sWrk1 = ($FldVal <> "") ? ew_GetMultiSearchSql($Fld, $FldOpr, $FldVal) : ""; // Field value 1
			$sWrk2 = ($FldVal2 <> "") ? ew_GetMultiSearchSql($Fld, $FldOpr2, $FldVal2) : ""; // Field value 2
			$sWrk = $sWrk1; // Build final SQL
			if ($sWrk2 <> "")
				$sWrk = ($sWrk <> "") ? "($sWrk) $FldCond ($sWrk2)" : $sWrk2;
		} else {
			$FldVal = $this->ConvertSearchValue($Fld, $FldVal);
			$FldVal2 = $this->ConvertSearchValue($Fld, $FldVal2);
			$sWrk = ew_GetSearchSql($Fld, $FldVal, $FldOpr, $FldCond, $FldVal2, $FldOpr2);
		}
		ew_AddFilter($Where, $sWrk);
	}

	// Convert search value
	function ConvertSearchValue(&$Fld, $FldVal) {
		if ($FldVal == EW_NULL_VALUE || $FldVal == EW_NOT_NULL_VALUE)
			return $FldVal;
		$Value = $FldVal;
		if ($Fld->FldDataType == EW_DATATYPE_BOOLEAN) {
			if ($FldVal <> "") $Value = ($FldVal == "1" || strtolower(strval($FldVal)) == "y" || strtolower(strval($FldVal)) == "t") ? $Fld->TrueValue : $Fld->FalseValue;
		} elseif ($Fld->FldDataType == EW_DATATYPE_DATE) {
			if ($FldVal <> "") $Value = ew_UnFormatDateTime($FldVal, $Fld->FldDateTimeFormat);
		}
		return $Value;
	}

	// Check if search parm exists
	function CheckSearchParms() {
		if ($this->ID->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ApplicantName->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Nationality->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->_Language->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->College1->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->College1SentDate->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->College1Status->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->College1ReplyDate->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->College2->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->College2SentDate->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->College2Status->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->College2ReplyDate->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->College3->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->College3SentDate->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->College3Status->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->College3ReplyDate->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->CommitteDecision->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->CommitteDecisionDate->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->CommitteRefNo->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->PreidentsDecision->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->PreidentsDecisionDate->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->PreidentsRefNo->AdvancedSearch->IssetSession())
			return TRUE;
		return FALSE;
	}

	// Clear all search parameters
	function ResetSearchParms() {

		// Clear search WHERE clause
		$this->SearchWhere = "";
		$this->setSearchWhere($this->SearchWhere);

		// Clear advanced search parameters
		$this->ResetAdvancedSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all advanced search parameters
	function ResetAdvancedSearchParms() {
		$this->ID->AdvancedSearch->UnsetSession();
		$this->ApplicantName->AdvancedSearch->UnsetSession();
		$this->Nationality->AdvancedSearch->UnsetSession();
		$this->_Language->AdvancedSearch->UnsetSession();
		$this->College1->AdvancedSearch->UnsetSession();
		$this->College1SentDate->AdvancedSearch->UnsetSession();
		$this->College1Status->AdvancedSearch->UnsetSession();
		$this->College1ReplyDate->AdvancedSearch->UnsetSession();
		$this->College2->AdvancedSearch->UnsetSession();
		$this->College2SentDate->AdvancedSearch->UnsetSession();
		$this->College2Status->AdvancedSearch->UnsetSession();
		$this->College2ReplyDate->AdvancedSearch->UnsetSession();
		$this->College3->AdvancedSearch->UnsetSession();
		$this->College3SentDate->AdvancedSearch->UnsetSession();
		$this->College3Status->AdvancedSearch->UnsetSession();
		$this->College3ReplyDate->AdvancedSearch->UnsetSession();
		$this->CommitteDecision->AdvancedSearch->UnsetSession();
		$this->CommitteDecisionDate->AdvancedSearch->UnsetSession();
		$this->CommitteRefNo->AdvancedSearch->UnsetSession();
		$this->PreidentsDecision->AdvancedSearch->UnsetSession();
		$this->PreidentsDecisionDate->AdvancedSearch->UnsetSession();
		$this->PreidentsRefNo->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore advanced search values
		$this->ID->AdvancedSearch->Load();
		$this->ApplicantName->AdvancedSearch->Load();
		$this->Nationality->AdvancedSearch->Load();
		$this->_Language->AdvancedSearch->Load();
		$this->College1->AdvancedSearch->Load();
		$this->College1SentDate->AdvancedSearch->Load();
		$this->College1Status->AdvancedSearch->Load();
		$this->College1ReplyDate->AdvancedSearch->Load();
		$this->College2->AdvancedSearch->Load();
		$this->College2SentDate->AdvancedSearch->Load();
		$this->College2Status->AdvancedSearch->Load();
		$this->College2ReplyDate->AdvancedSearch->Load();
		$this->College3->AdvancedSearch->Load();
		$this->College3SentDate->AdvancedSearch->Load();
		$this->College3Status->AdvancedSearch->Load();
		$this->College3ReplyDate->AdvancedSearch->Load();
		$this->CommitteDecision->AdvancedSearch->Load();
		$this->CommitteDecisionDate->AdvancedSearch->Load();
		$this->CommitteRefNo->AdvancedSearch->Load();
		$this->PreidentsDecision->AdvancedSearch->Load();
		$this->PreidentsDecisionDate->AdvancedSearch->Load();
		$this->PreidentsRefNo->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->ID); // ID
			$this->UpdateSort($this->ApplicantName); // ApplicantName
			$this->UpdateSort($this->Nationality); // Nationality
			$this->UpdateSort($this->_Language); // Language
			$this->UpdateSort($this->College1); // College1
			$this->UpdateSort($this->College2); // College2
			$this->UpdateSort($this->College3); // College3
			$this->setStartRecordNumber(1); // Reset start position
		}
	}

	// Load sort order parameters
	function LoadSortOrder() {
		$sOrderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
		if ($sOrderBy == "") {
			if ($this->SqlOrderBy() <> "") {
				$sOrderBy = $this->SqlOrderBy();
				$this->setSessionOrderBy($sOrderBy);
			}
		}
	}

	// Reset command
	// - cmd=reset (Reset search parameters)
	// - cmd=resetall (Reset search and master/detail parameters)
	// - cmd=resetsort (Reset sort parameters)
	function ResetCmd() {

		// Check if reset command
		if (substr($this->Command,0,5) == "reset") {

			// Reset search criteria
			if ($this->Command == "reset" || $this->Command == "resetall")
				$this->ResetSearchParms();

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->ID->setSort("");
				$this->ApplicantName->setSort("");
				$this->Nationality->setSort("");
				$this->_Language->setSort("");
				$this->College1->setSort("");
				$this->College2->setSort("");
				$this->College3->setSort("");
			}

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

		// Add group option item
		$item = &$this->ListOptions->Add($this->ListOptions->GroupOptionName);
		$item->Body = "";
		$item->OnLeft = TRUE;
		$item->Visible = FALSE;

		// "view"
		$item = &$this->ListOptions->Add("view");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanView();
		$item->OnLeft = TRUE;

		// "edit"
		$item = &$this->ListOptions->Add("edit");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanEdit();
		$item->OnLeft = TRUE;

		// "checkbox"
		$item = &$this->ListOptions->Add("checkbox");
		$item->Visible = $Security->CanDelete();
		$item->OnLeft = TRUE;
		$item->Header = "<label class=\"checkbox\"><input type=\"checkbox\" name=\"key\" id=\"key\" onclick=\"ew_SelectAllKey(this);\"></label>";
		$item->MoveTo(0);
		$item->ShowInDropDown = FALSE;
		$item->ShowInButtonGroup = FALSE;

		// Drop down button for ListOptions
		$this->ListOptions->UseDropDownButton = FALSE;
		$this->ListOptions->DropDownButtonPhrase = $Language->Phrase("ButtonListOptions");
		$this->ListOptions->UseButtonGroup = FALSE;
		$this->ListOptions->ButtonClass = "btn-small"; // Class for button group

		// Call ListOptions_Load event
		$this->ListOptions_Load();
		$item = &$this->ListOptions->GetItem($this->ListOptions->GroupOptionName);
		$item->Visible = $this->ListOptions->GroupOptionVisible();
	}

	// Render list options
	function RenderListOptions() {
		global $Security, $Language, $objForm;
		$this->ListOptions->LoadDefault();

		// Set up row action and key
		if (is_numeric($this->RowIndex) && $this->CurrentMode <> "view") {
			$objForm->Index = $this->RowIndex;
			$ActionName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormActionName);
			$OldKeyName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormOldKeyName);
			$KeyName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormKeyName);
			$BlankRowName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormBlankRowName);
			if ($this->RowAction <> "")
				$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $ActionName . "\" id=\"" . $ActionName . "\" value=\"" . $this->RowAction . "\">";
			if ($this->RowAction == "delete") {
				$rowkey = $objForm->GetValue($this->FormKeyName);
				$this->SetupKeyValues($rowkey);
			}
			if ($this->RowAction == "insert" && $this->CurrentAction == "F" && $this->EmptyRow())
				$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $BlankRowName . "\" id=\"" . $BlankRowName . "\" value=\"1\">";
		}

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		if ($this->CurrentAction == "edit" && $this->RowType == EW_ROWTYPE_EDIT) { // Inline-Edit
			$this->ListOptions->CustomItem = "edit"; // Show edit column only
				$oListOpt->Body = "<div" . (($oListOpt->OnLeft) ? " style=\"text-align: right\"" : "") . ">" .
					"<a class=\"ewGridLink ewInlineUpdate\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("UpdateLink")) . "\" href=\"\" onclick=\"return ewForms(this).Submit('" . ew_GetHashUrl($this->PageName(), $this->PageObjName . "_row_" . $this->RowCnt) . "');\">" . $Language->Phrase("UpdateLink") . "</a>&nbsp;" .
					"<a class=\"ewGridLink ewInlineCancel\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("CancelLink")) . "\" href=\"" . $this->PageUrl() . "a=cancel\">" . $Language->Phrase("CancelLink") . "</a>" .
					"<input type=\"hidden\" name=\"a_list\" id=\"a_list\" value=\"update\"></div>";
			$oListOpt->Body .= "<input type=\"hidden\" name=\"k" . $this->RowIndex . "_key\" id=\"k" . $this->RowIndex . "_key\" value=\"" . ew_HtmlEncode($this->ID->CurrentValue) . "\">";
			return;
		}

		// "view"
		$oListOpt = &$this->ListOptions->Items["view"];
		if ($Security->CanView())
			$oListOpt->Body = "<a class=\"ewRowLink ewView\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewLink")) . "\" href=\"" . ew_HtmlEncode($this->ViewUrl) . "\">" . $Language->Phrase("ViewLink") . "</a>";
		else
			$oListOpt->Body = "";

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		if ($Security->CanEdit()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewEdit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("EditLink") . "</a>";
			$oListOpt->Body .= "<span class=\"ewSeparator\">&nbsp;|&nbsp;</span>";
			$oListOpt->Body .= "<a class=\"ewRowLink ewInlineEdit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("InlineEditLink")) . "\" href=\"" . ew_HtmlEncode(ew_GetHashUrl($this->InlineEditUrl, $this->PageObjName . "_row_" . $this->RowCnt)) . "\">" . $Language->Phrase("InlineEditLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "checkbox"
		$oListOpt = &$this->ListOptions->Items["checkbox"];
		$oListOpt->Body = "<label class=\"checkbox\"><input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->ID->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event, this);'></label>";
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = $options["addedit"];

		// Add
		$item = &$option->Add("add");
		$item->Body = "<a class=\"ewAddEdit ewAdd\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("AddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->CanAdd());
		$option = $options["action"];

		// Add multi delete
		$item = &$option->Add("multidelete");
		$item->Body = "<a class=\"ewAction ewMultiDelete\" href=\"\" onclick=\"ew_SubmitSelected(document.flanguageinstructorslist, '" . $this->MultiDeleteUrl . "');return false;\">" . $Language->Phrase("DeleteSelectedLink") . "</a>";
		$item->Visible = ($Security->CanDelete());

		// Set up options default
		foreach ($options as &$option) {
			$option->UseDropDownButton = FALSE;
			$option->UseButtonGroup = TRUE;
			$option->ButtonClass = "btn-small"; // Class for button group
			$item = &$option->Add($option->GroupOptionName);
			$item->Body = "";
			$item->Visible = FALSE;
		}
		$options["addedit"]->DropDownButtonPhrase = $Language->Phrase("ButtonAddEdit");
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$options["action"]->DropDownButtonPhrase = $Language->Phrase("ButtonActions");
	}

	// Render other options
	function RenderOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
			$option = &$options["action"];
			foreach ($this->CustomActions as $action => $name) {

				// Add custom action
				$item = &$option->Add("custom_" . $action);
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.flanguageinstructorslist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
			}

			// Hide grid edit, multi-delete and multi-update
			if ($this->TotalRecs <= 0) {
				$option = &$options["addedit"];
				$item = &$option->GetItem("gridedit");
				if ($item) $item->Visible = FALSE;
				$option = &$options["action"];
				$item = &$option->GetItem("multidelete");
				if ($item) $item->Visible = FALSE;
				$item = &$option->GetItem("multiupdate");
				if ($item) $item->Visible = FALSE;
			}
	}

	// Process custom action
	function ProcessCustomAction() {
		global $conn, $Language, $Security;
		$sFilter = $this->GetKeyFilter();
		$UserAction = @$_POST["useraction"];
		if ($sFilter <> "" && $UserAction <> "") {
			$this->CurrentFilter = $sFilter;
			$sSql = $this->SQL();
			$conn->raiseErrorFn = 'ew_ErrorFn';
			$rs = $conn->Execute($sSql);
			$conn->raiseErrorFn = '';
			$rsuser = ($rs) ? $rs->GetRows() : array();
			if ($rs)
				$rs->Close();

			// Call row custom action event
			if (count($rsuser) > 0) {
				$conn->BeginTrans();
				foreach ($rsuser as $row) {
					$Processed = $this->Row_CustomAction($UserAction, $row);
					if (!$Processed) break;
				}
				if ($Processed) {
					$conn->CommitTrans(); // Commit the changes
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage(str_replace('%s', $UserAction, $Language->Phrase("CustomActionCompleted"))); // Set up success message
				} else {
					$conn->RollbackTrans(); // Rollback changes

					// Set up error message
					if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

						// Use the message, do nothing
					} elseif ($this->CancelMessage <> "") {
						$this->setFailureMessage($this->CancelMessage);
						$this->CancelMessage = "";
					} else {
						$this->setFailureMessage(str_replace('%s', $UserAction, $Language->Phrase("CustomActionCancelled")));
					}
				}
			}
		}
	}

	function RenderListOptionsExt() {
		global $Security, $Language;
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

	// Load default values
	function LoadDefaultValues() {
		$this->ID->CurrentValue = NULL;
		$this->ID->OldValue = $this->ID->CurrentValue;
		$this->ApplicantName->CurrentValue = NULL;
		$this->ApplicantName->OldValue = $this->ApplicantName->CurrentValue;
		$this->Nationality->CurrentValue = NULL;
		$this->Nationality->OldValue = $this->Nationality->CurrentValue;
		$this->_Language->CurrentValue = NULL;
		$this->_Language->OldValue = $this->_Language->CurrentValue;
		$this->College1->CurrentValue = NULL;
		$this->College1->OldValue = $this->College1->CurrentValue;
		$this->College2->CurrentValue = NULL;
		$this->College2->OldValue = $this->College2->CurrentValue;
		$this->College3->CurrentValue = NULL;
		$this->College3->OldValue = $this->College3->CurrentValue;
	}

	//  Load search values for validation
	function LoadSearchValues() {
		global $objForm;

		// Load search values
		// ID

		$this->ID->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ID"]);
		if ($this->ID->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ID->AdvancedSearch->SearchOperator = @$_GET["z_ID"];

		// ApplicantName
		$this->ApplicantName->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ApplicantName"]);
		if ($this->ApplicantName->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ApplicantName->AdvancedSearch->SearchOperator = @$_GET["z_ApplicantName"];

		// Nationality
		$this->Nationality->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Nationality"]);
		if ($this->Nationality->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Nationality->AdvancedSearch->SearchOperator = @$_GET["z_Nationality"];

		// Language
		$this->_Language->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x__Language"]);
		if ($this->_Language->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->_Language->AdvancedSearch->SearchOperator = @$_GET["z__Language"];

		// College1
		$this->College1->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_College1"]);
		if ($this->College1->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->College1->AdvancedSearch->SearchOperator = @$_GET["z_College1"];

		// College1SentDate
		$this->College1SentDate->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_College1SentDate"]);
		if ($this->College1SentDate->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->College1SentDate->AdvancedSearch->SearchOperator = @$_GET["z_College1SentDate"];

		// College1Status
		$this->College1Status->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_College1Status"]);
		if ($this->College1Status->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->College1Status->AdvancedSearch->SearchOperator = @$_GET["z_College1Status"];

		// College1ReplyDate
		$this->College1ReplyDate->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_College1ReplyDate"]);
		if ($this->College1ReplyDate->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->College1ReplyDate->AdvancedSearch->SearchOperator = @$_GET["z_College1ReplyDate"];

		// College2
		$this->College2->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_College2"]);
		if ($this->College2->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->College2->AdvancedSearch->SearchOperator = @$_GET["z_College2"];

		// College2SentDate
		$this->College2SentDate->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_College2SentDate"]);
		if ($this->College2SentDate->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->College2SentDate->AdvancedSearch->SearchOperator = @$_GET["z_College2SentDate"];

		// College2Status
		$this->College2Status->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_College2Status"]);
		if ($this->College2Status->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->College2Status->AdvancedSearch->SearchOperator = @$_GET["z_College2Status"];

		// College2ReplyDate
		$this->College2ReplyDate->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_College2ReplyDate"]);
		if ($this->College2ReplyDate->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->College2ReplyDate->AdvancedSearch->SearchOperator = @$_GET["z_College2ReplyDate"];

		// College3
		$this->College3->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_College3"]);
		if ($this->College3->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->College3->AdvancedSearch->SearchOperator = @$_GET["z_College3"];

		// College3SentDate
		$this->College3SentDate->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_College3SentDate"]);
		if ($this->College3SentDate->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->College3SentDate->AdvancedSearch->SearchOperator = @$_GET["z_College3SentDate"];

		// College3Status
		$this->College3Status->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_College3Status"]);
		if ($this->College3Status->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->College3Status->AdvancedSearch->SearchOperator = @$_GET["z_College3Status"];

		// College3ReplyDate
		$this->College3ReplyDate->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_College3ReplyDate"]);
		if ($this->College3ReplyDate->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->College3ReplyDate->AdvancedSearch->SearchOperator = @$_GET["z_College3ReplyDate"];

		// CommitteDecision
		$this->CommitteDecision->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_CommitteDecision"]);
		if ($this->CommitteDecision->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->CommitteDecision->AdvancedSearch->SearchOperator = @$_GET["z_CommitteDecision"];

		// CommitteDecisionDate
		$this->CommitteDecisionDate->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_CommitteDecisionDate"]);
		if ($this->CommitteDecisionDate->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->CommitteDecisionDate->AdvancedSearch->SearchOperator = @$_GET["z_CommitteDecisionDate"];

		// CommitteRefNo
		$this->CommitteRefNo->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_CommitteRefNo"]);
		if ($this->CommitteRefNo->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->CommitteRefNo->AdvancedSearch->SearchOperator = @$_GET["z_CommitteRefNo"];

		// PreidentsDecision
		$this->PreidentsDecision->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_PreidentsDecision"]);
		if ($this->PreidentsDecision->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->PreidentsDecision->AdvancedSearch->SearchOperator = @$_GET["z_PreidentsDecision"];

		// PreidentsDecisionDate
		$this->PreidentsDecisionDate->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_PreidentsDecisionDate"]);
		if ($this->PreidentsDecisionDate->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->PreidentsDecisionDate->AdvancedSearch->SearchOperator = @$_GET["z_PreidentsDecisionDate"];

		// PreidentsRefNo
		$this->PreidentsRefNo->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_PreidentsRefNo"]);
		if ($this->PreidentsRefNo->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->PreidentsRefNo->AdvancedSearch->SearchOperator = @$_GET["z_PreidentsRefNo"];
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->ID->FldIsDetailKey && $this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->ID->setFormValue($objForm->GetValue("x_ID"));
		if (!$this->ApplicantName->FldIsDetailKey) {
			$this->ApplicantName->setFormValue($objForm->GetValue("x_ApplicantName"));
		}
		if (!$this->Nationality->FldIsDetailKey) {
			$this->Nationality->setFormValue($objForm->GetValue("x_Nationality"));
		}
		if (!$this->_Language->FldIsDetailKey) {
			$this->_Language->setFormValue($objForm->GetValue("x__Language"));
		}
		if (!$this->College1->FldIsDetailKey) {
			$this->College1->setFormValue($objForm->GetValue("x_College1"));
		}
		if (!$this->College2->FldIsDetailKey) {
			$this->College2->setFormValue($objForm->GetValue("x_College2"));
		}
		if (!$this->College3->FldIsDetailKey) {
			$this->College3->setFormValue($objForm->GetValue("x_College3"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		if ($this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->ID->CurrentValue = $this->ID->FormValue;
		$this->ApplicantName->CurrentValue = $this->ApplicantName->FormValue;
		$this->Nationality->CurrentValue = $this->Nationality->FormValue;
		$this->_Language->CurrentValue = $this->_Language->FormValue;
		$this->College1->CurrentValue = $this->College1->FormValue;
		$this->College2->CurrentValue = $this->College2->FormValue;
		$this->College3->CurrentValue = $this->College3->FormValue;
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

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("ID")) <> "")
			$this->ID->CurrentValue = $this->getKey("ID"); // ID
		else
			$bValidKey = FALSE;

		// Load old recordset
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$this->OldRecordset = ew_LoadRecordset($sSql);
			$this->LoadRowValues($this->OldRecordset); // Load row values
		} else {
			$this->OldRecordset = NULL;
		}
		return $bValidKey;
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

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

			// College2
			$this->College2->LinkCustomAttributes = "";
			$this->College2->HrefValue = "";
			$this->College2->TooltipValue = "";

			// College3
			$this->College3->LinkCustomAttributes = "";
			$this->College3->HrefValue = "";
			$this->College3->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// ID
			// ApplicantName

			$this->ApplicantName->EditCustomAttributes = "";
			$this->ApplicantName->EditValue = ew_HtmlEncode($this->ApplicantName->CurrentValue);
			$this->ApplicantName->PlaceHolder = ew_RemoveHtml($this->ApplicantName->FldCaption());

			// Nationality
			$this->Nationality->EditCustomAttributes = "";
			$this->Nationality->EditValue = ew_HtmlEncode($this->Nationality->CurrentValue);
			$this->Nationality->PlaceHolder = ew_RemoveHtml($this->Nationality->FldCaption());

			// Language
			$this->_Language->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->_Language->FldTagValue(1), $this->_Language->FldTagCaption(1) <> "" ? $this->_Language->FldTagCaption(1) : $this->_Language->FldTagValue(1));
			$arwrk[] = array($this->_Language->FldTagValue(2), $this->_Language->FldTagCaption(2) <> "" ? $this->_Language->FldTagCaption(2) : $this->_Language->FldTagValue(2));
			$arwrk[] = array($this->_Language->FldTagValue(3), $this->_Language->FldTagCaption(3) <> "" ? $this->_Language->FldTagCaption(3) : $this->_Language->FldTagValue(3));
			$this->_Language->EditValue = $arwrk;

			// College1
			$this->College1->EditCustomAttributes = "";

			// College2
			$this->College2->EditCustomAttributes = "";

			// College3
			$this->College3->EditCustomAttributes = "";

			// Edit refer script
			// ID

			$this->ID->HrefValue = "";

			// ApplicantName
			$this->ApplicantName->HrefValue = "";

			// Nationality
			$this->Nationality->HrefValue = "";

			// Language
			$this->_Language->HrefValue = "";

			// College1
			$this->College1->HrefValue = "";

			// College2
			$this->College2->HrefValue = "";

			// College3
			$this->College3->HrefValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// ID
			$this->ID->EditCustomAttributes = "";
			$this->ID->EditValue = $this->ID->CurrentValue;
			$this->ID->ViewCustomAttributes = "";

			// ApplicantName
			$this->ApplicantName->EditCustomAttributes = "";
			$this->ApplicantName->EditValue = ew_HtmlEncode($this->ApplicantName->CurrentValue);
			$this->ApplicantName->PlaceHolder = ew_RemoveHtml($this->ApplicantName->FldCaption());

			// Nationality
			$this->Nationality->EditCustomAttributes = "";
			$this->Nationality->EditValue = ew_HtmlEncode($this->Nationality->CurrentValue);
			$this->Nationality->PlaceHolder = ew_RemoveHtml($this->Nationality->FldCaption());

			// Language
			$this->_Language->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->_Language->FldTagValue(1), $this->_Language->FldTagCaption(1) <> "" ? $this->_Language->FldTagCaption(1) : $this->_Language->FldTagValue(1));
			$arwrk[] = array($this->_Language->FldTagValue(2), $this->_Language->FldTagCaption(2) <> "" ? $this->_Language->FldTagCaption(2) : $this->_Language->FldTagValue(2));
			$arwrk[] = array($this->_Language->FldTagValue(3), $this->_Language->FldTagCaption(3) <> "" ? $this->_Language->FldTagCaption(3) : $this->_Language->FldTagValue(3));
			$this->_Language->EditValue = $arwrk;

			// College1
			$this->College1->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT `CollegeID`, `College Name EN` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `colleges`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->College1, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->College1->EditValue = $arwrk;

			// College2
			$this->College2->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT `CollegeID`, `College Name EN` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `colleges`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->College2, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->College2->EditValue = $arwrk;

			// College3
			$this->College3->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT `CollegeID`, `College Name EN` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `colleges`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->College3, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->College3->EditValue = $arwrk;

			// Edit refer script
			// ID

			$this->ID->HrefValue = "";

			// ApplicantName
			$this->ApplicantName->HrefValue = "";

			// Nationality
			$this->Nationality->HrefValue = "";

			// Language
			$this->_Language->HrefValue = "";

			// College1
			$this->College1->HrefValue = "";

			// College2
			$this->College2->HrefValue = "";

			// College3
			$this->College3->HrefValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// ID
			$this->ID->EditCustomAttributes = "";
			$this->ID->EditValue = ew_HtmlEncode($this->ID->AdvancedSearch->SearchValue);
			$this->ID->PlaceHolder = ew_RemoveHtml($this->ID->FldCaption());

			// ApplicantName
			$this->ApplicantName->EditCustomAttributes = "";
			$this->ApplicantName->EditValue = ew_HtmlEncode($this->ApplicantName->AdvancedSearch->SearchValue);
			$this->ApplicantName->PlaceHolder = ew_RemoveHtml($this->ApplicantName->FldCaption());

			// Nationality
			$this->Nationality->EditCustomAttributes = "";
			$this->Nationality->EditValue = ew_HtmlEncode($this->Nationality->AdvancedSearch->SearchValue);
			$this->Nationality->PlaceHolder = ew_RemoveHtml($this->Nationality->FldCaption());

			// Language
			$this->_Language->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->_Language->FldTagValue(1), $this->_Language->FldTagCaption(1) <> "" ? $this->_Language->FldTagCaption(1) : $this->_Language->FldTagValue(1));
			$arwrk[] = array($this->_Language->FldTagValue(2), $this->_Language->FldTagCaption(2) <> "" ? $this->_Language->FldTagCaption(2) : $this->_Language->FldTagValue(2));
			$arwrk[] = array($this->_Language->FldTagValue(3), $this->_Language->FldTagCaption(3) <> "" ? $this->_Language->FldTagCaption(3) : $this->_Language->FldTagValue(3));
			$this->_Language->EditValue = $arwrk;

			// College1
			$this->College1->EditCustomAttributes = "";

			// College2
			$this->College2->EditCustomAttributes = "";

			// College3
			$this->College3->EditCustomAttributes = "";
		}
		if ($this->RowType == EW_ROWTYPE_ADD ||
			$this->RowType == EW_ROWTYPE_EDIT ||
			$this->RowType == EW_ROWTYPE_SEARCH) { // Add / Edit / Search row
			$this->SetupFieldTitles();
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate search
	function ValidateSearch() {
		global $gsSearchError;

		// Initialize
		$gsSearchError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return TRUE;

		// Return validate result
		$ValidateSearch = ($gsSearchError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateSearch = $ValidateSearch && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsSearchError, $sFormCustomError);
		}
		return $ValidateSearch;
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Update record based on key values
	function EditRow() {
		global $conn, $Security, $Language;
		$sFilter = $this->KeyFilter();
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = 'ew_ErrorFn';
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$EditRow = FALSE; // Update Failed
		} else {

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// ApplicantName
			$this->ApplicantName->SetDbValueDef($rsnew, $this->ApplicantName->CurrentValue, NULL, $this->ApplicantName->ReadOnly);

			// Nationality
			$this->Nationality->SetDbValueDef($rsnew, $this->Nationality->CurrentValue, NULL, $this->Nationality->ReadOnly);

			// Language
			$this->_Language->SetDbValueDef($rsnew, $this->_Language->CurrentValue, NULL, $this->_Language->ReadOnly);

			// College1
			$this->College1->SetDbValueDef($rsnew, $this->College1->CurrentValue, NULL, $this->College1->ReadOnly);

			// College2
			$this->College2->SetDbValueDef($rsnew, $this->College2->CurrentValue, NULL, $this->College2->ReadOnly);

			// College3
			$this->College3->SetDbValueDef($rsnew, $this->College3->CurrentValue, NULL, $this->College3->ReadOnly);

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = 'ew_ErrorFn';
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew, "", $rsold);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
				if ($EditRow) {
				}
			} else {
				if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

					// Use the message, do nothing
				} elseif ($this->CancelMessage <> "") {
					$this->setFailureMessage($this->CancelMessage);
					$this->CancelMessage = "";
				} else {
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		if ($EditRow) {
			$this->WriteAuditTrailOnEdit($rsold, $rsnew);
		}
		$rs->Close();
		return $EditRow;
	}

	// Add record
	function AddRow($rsold = NULL) {
		global $conn, $Language, $Security;

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// ApplicantName
		$this->ApplicantName->SetDbValueDef($rsnew, $this->ApplicantName->CurrentValue, NULL, FALSE);

		// Nationality
		$this->Nationality->SetDbValueDef($rsnew, $this->Nationality->CurrentValue, NULL, FALSE);

		// Language
		$this->_Language->SetDbValueDef($rsnew, $this->_Language->CurrentValue, NULL, FALSE);

		// College1
		$this->College1->SetDbValueDef($rsnew, $this->College1->CurrentValue, NULL, FALSE);

		// College2
		$this->College2->SetDbValueDef($rsnew, $this->College2->CurrentValue, NULL, FALSE);

		// College3
		$this->College3->SetDbValueDef($rsnew, $this->College3->CurrentValue, NULL, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = 'ew_ErrorFn';
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
			}
		} else {
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}

		// Get insert id if necessary
		if ($AddRow) {
			$this->ID->setDbValue($conn->Insert_ID());
			$rsnew['ID'] = $this->ID->DbValue;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
			$this->WriteAuditTrailOnAdd($rsnew);
		}
		return $AddRow;
	}

	// Load advanced search
	function LoadAdvancedSearch() {
		$this->ID->AdvancedSearch->Load();
		$this->ApplicantName->AdvancedSearch->Load();
		$this->Nationality->AdvancedSearch->Load();
		$this->_Language->AdvancedSearch->Load();
		$this->College1->AdvancedSearch->Load();
		$this->College1SentDate->AdvancedSearch->Load();
		$this->College1Status->AdvancedSearch->Load();
		$this->College1ReplyDate->AdvancedSearch->Load();
		$this->College2->AdvancedSearch->Load();
		$this->College2SentDate->AdvancedSearch->Load();
		$this->College2Status->AdvancedSearch->Load();
		$this->College2ReplyDate->AdvancedSearch->Load();
		$this->College3->AdvancedSearch->Load();
		$this->College3SentDate->AdvancedSearch->Load();
		$this->College3Status->AdvancedSearch->Load();
		$this->College3ReplyDate->AdvancedSearch->Load();
		$this->CommitteDecision->AdvancedSearch->Load();
		$this->CommitteDecisionDate->AdvancedSearch->Load();
		$this->CommitteRefNo->AdvancedSearch->Load();
		$this->PreidentsDecision->AdvancedSearch->Load();
		$this->PreidentsDecisionDate->AdvancedSearch->Load();
		$this->PreidentsRefNo->AdvancedSearch->Load();
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
		$item->Visible = FALSE;

		// Export to Email
		$item = &$this->ExportOptions->Add("email");
		$item->Body = "<a id=\"emf_languageinstructors\" href=\"javascript:void(0);\" class=\"ewExportLink ewEmail\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_languageinstructors',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.flanguageinstructorslist,sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
		$item->Visible = FALSE;

		// Drop down button for export
		$this->ExportOptions->UseDropDownButton = FALSE;
		$this->ExportOptions->DropDownButtonPhrase = $Language->Phrase("ButtonExport");

		// Add group option item
		$item = &$this->ExportOptions->Add($this->ExportOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
	}

	// Export data in HTML/CSV/Word/Excel/XML/Email/PDF format
	function ExportData() {
		$utf8 = (strtolower(EW_CHARSET) == "utf-8");
		$bSelectLimit = EW_SELECT_LIMIT;

		// Load recordset
		if ($bSelectLimit) {
			$this->TotalRecs = $this->SelectRecordCount();
		} else {
			if ($rs = $this->LoadRecordset())
				$this->TotalRecs = $rs->RecordCount();
		}
		$this->StartRec = 1;

		// Export all
		if ($this->ExportAll) {
			set_time_limit(EW_EXPORT_ALL_TIME_LIMIT);
			$this->DisplayRecs = $this->TotalRecs;
			$this->StopRec = $this->TotalRecs;
		} else { // Export one page only
			$this->SetUpStartRec(); // Set up start record position

			// Set the last record to display
			if ($this->DisplayRecs <= 0) {
				$this->StopRec = $this->TotalRecs;
			} else {
				$this->StopRec = $this->StartRec + $this->DisplayRecs - 1;
			}
		}
		if ($bSelectLimit)
			$rs = $this->LoadRecordset($this->StartRec-1, $this->DisplayRecs <= 0 ? $this->TotalRecs : $this->DisplayRecs);
		if (!$rs) {
			header("Content-Type:"); // Remove header
			header("Content-Disposition:");
			$this->ShowMessage();
			return;
		}
		$ExportDoc = ew_ExportDocument($this, "h");
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
		$this->ExportDocument($ExportDoc, $rs, $StartRec, $StopRec, "");
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
		$url = ew_CurrentUrl();
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$Breadcrumb->Add("list", $this->TableVar, $url, $this->TableVar, TRUE);
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'languageinstructors';
	  $usr = CurrentUserName();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (edit page)
	function WriteAuditTrailOnEdit(&$rsold, &$rsnew) {
		if (!$this->AuditTrailOnEdit) return;
		$table = 'languageinstructors';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rsold['ID'];

		// Write Audit Trail
		$dt = ew_StdCurrentDateTime();
		$id = ew_ScriptName();
	  $usr = CurrentUserName();
		foreach (array_keys($rsnew) as $fldname) {
			if ($this->fields[$fldname]->FldDataType <> EW_DATATYPE_BLOB) { // Ignore BLOB fields
				if ($this->fields[$fldname]->FldDataType == EW_DATATYPE_DATE) { // DateTime field
					$modified = (ew_FormatDateTime($rsold[$fldname], 0) <> ew_FormatDateTime($rsnew[$fldname], 0));
				} else {
					$modified = !ew_CompareValue($rsold[$fldname], $rsnew[$fldname]);
				}
				if ($modified) {
					if ($this->fields[$fldname]->FldDataType == EW_DATATYPE_MEMO) { // Memo field
						if (EW_AUDIT_TRAIL_TO_DATABASE) {
							$oldvalue = $rsold[$fldname];
							$newvalue = $rsnew[$fldname];
						} else {
							$oldvalue = "[MEMO]";
							$newvalue = "[MEMO]";
						}
					} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_XML) { // XML field
						$oldvalue = "[XML]";
						$newvalue = "[XML]";
					} else {
						$oldvalue = $rsold[$fldname];
						$newvalue = $rsnew[$fldname];
					}
					ew_WriteAuditTrail("log", $dt, $id, $usr, "U", $table, $fldname, $key, $oldvalue, $newvalue);
				}
			}
		}
	}

	// Write Audit Trail (delete page)
	function WriteAuditTrailOnDelete(&$rs) {
		if (!$this->AuditTrailOnDelete) return;
		$table = 'languageinstructors';

		// Get key value
		$key = "";
		if ($key <> "")
			$key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['ID'];

		// Write Audit Trail
		$dt = ew_StdCurrentDateTime();
		$id = ew_ScriptName();
	  $curUser = CurrentUserName();
		foreach (array_keys($rs) as $fldname) {
			if (array_key_exists($fldname, $this->fields) && $this->fields[$fldname]->FldDataType <> EW_DATATYPE_BLOB) { // Ignore BLOB fields
				if ($this->fields[$fldname]->FldDataType == EW_DATATYPE_MEMO) {
					if (EW_AUDIT_TRAIL_TO_DATABASE)
						$oldvalue = $rs[$fldname];
					else
						$oldvalue = "[MEMO]"; // Memo field
				} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_XML) {
					$oldvalue = "[XML]"; // XML field
				} else {
					$oldvalue = $rs[$fldname];
				}
				ew_WriteAuditTrail("log", $dt, $id, $curUser, "D", $table, $fldname, $key, $oldvalue, "");
			}
		}
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

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}

	// ListOptions Load event
	function ListOptions_Load() {

		// Example:
		//$opt = &$this->ListOptions->Add("new");
		//$opt->Header = "xxx";
		//$opt->OnLeft = TRUE; // Link on left
		//$opt->MoveTo(0); // Move to first column

	}

	// ListOptions Rendered event
	function ListOptions_Rendered() {

		// Example: 
		//$this->ListOptions->Items["new"]->Body = "xxx";

	}

	// Row Custom Action event
	function Row_CustomAction($action, $row) {

		// Return FALSE to abort
		return TRUE;
	}
}
?>
<?php ew_Header(TRUE) ?>
<?php

// Create page object
if (!isset($languageinstructors_list)) $languageinstructors_list = new clanguageinstructors_list();

// Page init
$languageinstructors_list->Page_Init();

// Page main
$languageinstructors_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$languageinstructors_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($languageinstructors->Export == "") { ?>
<script type="text/javascript">

// Page object
var languageinstructors_list = new ew_Page("languageinstructors_list");
languageinstructors_list.PageID = "list"; // Page ID
var EW_PAGE_ID = languageinstructors_list.PageID; // For backward compatibility

// Form object
var flanguageinstructorslist = new ew_Form("flanguageinstructorslist");
flanguageinstructorslist.FormKeyCountName = '<?php echo $languageinstructors_list->FormKeyCountName ?>';

// Validate form
flanguageinstructorslist.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	this.PostAutoSuggest();
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);

			// Set up row object
			ew_ElementsToRow(fobj);

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}
	return true;
}

// Form_CustomValidate event
flanguageinstructorslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
flanguageinstructorslist.ValidateRequired = true;
<?php } else { ?>
flanguageinstructorslist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
flanguageinstructorslist.Lists["x_College1"] = {"LinkField":"x_CollegeID","Ajax":null,"AutoFill":false,"DisplayFields":["x_College_Name_EN","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
flanguageinstructorslist.Lists["x_College2"] = {"LinkField":"x_CollegeID","Ajax":null,"AutoFill":false,"DisplayFields":["x_College_Name_EN","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
flanguageinstructorslist.Lists["x_College3"] = {"LinkField":"x_CollegeID","Ajax":null,"AutoFill":false,"DisplayFields":["x_College_Name_EN","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
var flanguageinstructorslistsrch = new ew_Form("flanguageinstructorslistsrch");

// Validate function for search
flanguageinstructorslistsrch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	this.PostAutoSuggest();
	var infix = "";

	// Set up row object
	ew_ElementsToRow(fobj);

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate event
flanguageinstructorslistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
flanguageinstructorslistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
flanguageinstructorslistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($languageinstructors->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($languageinstructors_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $languageinstructors_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$languageinstructors_list->TotalRecs = $languageinstructors->SelectRecordCount();
	} else {
		if ($languageinstructors_list->Recordset = $languageinstructors_list->LoadRecordset())
			$languageinstructors_list->TotalRecs = $languageinstructors_list->Recordset->RecordCount();
	}
	$languageinstructors_list->StartRec = 1;
	if ($languageinstructors_list->DisplayRecs <= 0 || ($languageinstructors->Export <> "" && $languageinstructors->ExportAll)) // Display all records
		$languageinstructors_list->DisplayRecs = $languageinstructors_list->TotalRecs;
	if (!($languageinstructors->Export <> "" && $languageinstructors->ExportAll))
		$languageinstructors_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$languageinstructors_list->Recordset = $languageinstructors_list->LoadRecordset($languageinstructors_list->StartRec-1, $languageinstructors_list->DisplayRecs);
$languageinstructors_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($languageinstructors->Export == "" && $languageinstructors->CurrentAction == "") { ?>
<form name="flanguageinstructorslistsrch" id="flanguageinstructorslistsrch" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>">
<div class="accordion ewDisplayTable ewSearchTable" id="flanguageinstructorslistsrch_SearchGroup">
	<div class="accordion-group">
		<div class="accordion-heading">
<a class="accordion-toggle" data-toggle="collapse" data-parent="#flanguageinstructorslistsrch_SearchGroup" href="#flanguageinstructorslistsrch_SearchBody"><?php echo $Language->Phrase("Search") ?></a>
		</div>
		<div id="flanguageinstructorslistsrch_SearchBody" class="accordion-body collapse in">
			<div class="accordion-inner">
<div id="flanguageinstructorslistsrch_SearchPanel">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="languageinstructors">
<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$languageinstructors_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$languageinstructors->RowType = EW_ROWTYPE_SEARCH;

// Render row
$languageinstructors->ResetAttrs();
$languageinstructors_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($languageinstructors->ApplicantName->Visible) { // ApplicantName ?>
	<span id="xsc_ApplicantName" class="ewCell">
		<span class="ewSearchCaption"><?php echo $languageinstructors->ApplicantName->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_ApplicantName" id="z_ApplicantName" value="LIKE"></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_ApplicantName" name="x_ApplicantName" id="x_ApplicantName" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($languageinstructors->ApplicantName->PlaceHolder) ?>" value="<?php echo $languageinstructors->ApplicantName->EditValue ?>"<?php echo $languageinstructors->ApplicantName->EditAttributes() ?>>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
<?php if ($languageinstructors->Nationality->Visible) { // Nationality ?>
	<span id="xsc_Nationality" class="ewCell">
		<span class="ewSearchCaption"><?php echo $languageinstructors->Nationality->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_Nationality" id="z_Nationality" value="LIKE"></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_Nationality" name="x_Nationality" id="x_Nationality" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($languageinstructors->Nationality->PlaceHolder) ?>" value="<?php echo $languageinstructors->Nationality->EditValue ?>"<?php echo $languageinstructors->Nationality->EditAttributes() ?>>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_3" class="ewRow">
<?php if ($languageinstructors->_Language->Visible) { // Language ?>
	<span id="xsc__Language" class="ewCell">
		<span class="ewSearchCaption"><?php echo $languageinstructors->_Language->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z__Language" id="z__Language" value="="></span>
		<span class="control-group ewSearchField">
<div id="tp_x__Language" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x__Language" id="x__Language" value="{value}"<?php echo $languageinstructors->_Language->EditAttributes() ?>></div>
<div id="dsl_x__Language" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $languageinstructors->_Language->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($languageinstructors->_Language->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x__Language" name="x__Language" id="x__Language_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $languageinstructors->_Language->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_4" class="ewRow">
	<div class="btn-group ewButtonGroup">
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	<div class="btn-group ewButtonGroup">
	<a class="btn ewShowAll" href="<?php echo $languageinstructors_list->PageUrl() ?>cmd=reset"><?php echo $Language->Phrase("ShowAll") ?></a>
	</div>
</div>
</div>
</div>
			</div>
		</div>
	</div>
</div>
</form>
<?php } ?>
<?php } ?>
<?php $languageinstructors_list->ShowPageHeader(); ?>
<?php
$languageinstructors_list->ShowMessage();
?>
<table class="ewGrid"><tr><td class="ewGridContent">
<?php if ($languageinstructors->Export == "") { ?>
<div class="ewGridUpperPanel">
<?php if ($languageinstructors->CurrentAction <> "gridadd" && $languageinstructors->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager">
<tr><td>
<?php if (!isset($languageinstructors_list->Pager)) $languageinstructors_list->Pager = new cPrevNextPager($languageinstructors_list->StartRec, $languageinstructors_list->DisplayRecs, $languageinstructors_list->TotalRecs) ?>
<?php if ($languageinstructors_list->Pager->RecordCount > 0) { ?>
<table class="ewStdTable"><tbody><tr><td>
	<?php echo $Language->Phrase("Page") ?>&nbsp;
<div class="input-prepend input-append">
<!--first page button-->
	<?php if ($languageinstructors_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $languageinstructors_list->PageUrl() ?>start=<?php echo $languageinstructors_list->Pager->FirstButton->Start ?>"><i class="icon-step-backward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-step-backward"></i></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($languageinstructors_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $languageinstructors_list->PageUrl() ?>start=<?php echo $languageinstructors_list->Pager->PrevButton->Start ?>"><i class="icon-prev"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-prev"></i></a>
	<?php } ?>
<!--current page number-->
	<input class="input-mini" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $languageinstructors_list->Pager->CurrentPage ?>">
<!--next page button-->
	<?php if ($languageinstructors_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $languageinstructors_list->PageUrl() ?>start=<?php echo $languageinstructors_list->Pager->NextButton->Start ?>"><i class="icon-play"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-play"></i></a>
	<?php } ?>
<!--last page button-->
	<?php if ($languageinstructors_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $languageinstructors_list->PageUrl() ?>start=<?php echo $languageinstructors_list->Pager->LastButton->Start ?>"><i class="icon-step-forward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-step-forward"></i></a>
	<?php } ?>
</div>
	&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $languageinstructors_list->Pager->PageCount ?>
</td>
<td>
	&nbsp;&nbsp;&nbsp;&nbsp;
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $languageinstructors_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $languageinstructors_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $languageinstructors_list->Pager->RecordCount ?>
</td>
</tr></tbody></table>
<?php } else { ?>
	<?php if ($Security->CanList()) { ?>
	<?php if ($languageinstructors_list->SearchWhere == "0=101") { ?>
	<p><?php echo $Language->Phrase("EnterSearchCriteria") ?></p>
	<?php } else { ?>
	<p><?php echo $Language->Phrase("NoRecord") ?></p>
	<?php } ?>
	<?php } else { ?>
	<p><?php echo $Language->Phrase("NoPermission") ?></p>
	<?php } ?>
<?php } ?>
</td>
</tr></table>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($languageinstructors_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
</div>
<?php } ?>
<form name="flanguageinstructorslist" id="flanguageinstructorslist" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="languageinstructors">
<div id="gmp_languageinstructors" class="ewGridMiddlePanel">
<?php if ($languageinstructors_list->TotalRecs > 0) { ?>
<table id="tbl_languageinstructorslist" class="ewTable ewTableSeparate">
<?php echo $languageinstructors->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$languageinstructors_list->RenderListOptions();

// Render list options (header, left)
$languageinstructors_list->ListOptions->Render("header", "left");
?>
<?php if ($languageinstructors->ID->Visible) { // ID ?>
	<?php if ($languageinstructors->SortUrl($languageinstructors->ID) == "") { ?>
		<td><div id="elh_languageinstructors_ID" class="languageinstructors_ID"><div class="ewTableHeaderCaption"><?php echo $languageinstructors->ID->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $languageinstructors->SortUrl($languageinstructors->ID) ?>',1);"><div id="elh_languageinstructors_ID" class="languageinstructors_ID">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $languageinstructors->ID->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($languageinstructors->ID->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($languageinstructors->ID->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($languageinstructors->ApplicantName->Visible) { // ApplicantName ?>
	<?php if ($languageinstructors->SortUrl($languageinstructors->ApplicantName) == "") { ?>
		<td><div id="elh_languageinstructors_ApplicantName" class="languageinstructors_ApplicantName"><div class="ewTableHeaderCaption"><?php echo $languageinstructors->ApplicantName->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $languageinstructors->SortUrl($languageinstructors->ApplicantName) ?>',1);"><div id="elh_languageinstructors_ApplicantName" class="languageinstructors_ApplicantName">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $languageinstructors->ApplicantName->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($languageinstructors->ApplicantName->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($languageinstructors->ApplicantName->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($languageinstructors->Nationality->Visible) { // Nationality ?>
	<?php if ($languageinstructors->SortUrl($languageinstructors->Nationality) == "") { ?>
		<td><div id="elh_languageinstructors_Nationality" class="languageinstructors_Nationality"><div class="ewTableHeaderCaption"><?php echo $languageinstructors->Nationality->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $languageinstructors->SortUrl($languageinstructors->Nationality) ?>',1);"><div id="elh_languageinstructors_Nationality" class="languageinstructors_Nationality">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $languageinstructors->Nationality->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($languageinstructors->Nationality->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($languageinstructors->Nationality->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($languageinstructors->_Language->Visible) { // Language ?>
	<?php if ($languageinstructors->SortUrl($languageinstructors->_Language) == "") { ?>
		<td><div id="elh_languageinstructors__Language" class="languageinstructors__Language"><div class="ewTableHeaderCaption"><?php echo $languageinstructors->_Language->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $languageinstructors->SortUrl($languageinstructors->_Language) ?>',1);"><div id="elh_languageinstructors__Language" class="languageinstructors__Language">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $languageinstructors->_Language->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($languageinstructors->_Language->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($languageinstructors->_Language->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($languageinstructors->College1->Visible) { // College1 ?>
	<?php if ($languageinstructors->SortUrl($languageinstructors->College1) == "") { ?>
		<td><div id="elh_languageinstructors_College1" class="languageinstructors_College1"><div class="ewTableHeaderCaption"><?php echo $languageinstructors->College1->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $languageinstructors->SortUrl($languageinstructors->College1) ?>',1);"><div id="elh_languageinstructors_College1" class="languageinstructors_College1">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $languageinstructors->College1->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($languageinstructors->College1->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($languageinstructors->College1->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($languageinstructors->College2->Visible) { // College2 ?>
	<?php if ($languageinstructors->SortUrl($languageinstructors->College2) == "") { ?>
		<td><div id="elh_languageinstructors_College2" class="languageinstructors_College2"><div class="ewTableHeaderCaption"><?php echo $languageinstructors->College2->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $languageinstructors->SortUrl($languageinstructors->College2) ?>',1);"><div id="elh_languageinstructors_College2" class="languageinstructors_College2">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $languageinstructors->College2->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($languageinstructors->College2->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($languageinstructors->College2->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($languageinstructors->College3->Visible) { // College3 ?>
	<?php if ($languageinstructors->SortUrl($languageinstructors->College3) == "") { ?>
		<td><div id="elh_languageinstructors_College3" class="languageinstructors_College3"><div class="ewTableHeaderCaption"><?php echo $languageinstructors->College3->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $languageinstructors->SortUrl($languageinstructors->College3) ?>',1);"><div id="elh_languageinstructors_College3" class="languageinstructors_College3">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $languageinstructors->College3->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($languageinstructors->College3->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($languageinstructors->College3->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$languageinstructors_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($languageinstructors->ExportAll && $languageinstructors->Export <> "") {
	$languageinstructors_list->StopRec = $languageinstructors_list->TotalRecs;
} else {

	// Set the last record to display
	if ($languageinstructors_list->TotalRecs > $languageinstructors_list->StartRec + $languageinstructors_list->DisplayRecs - 1)
		$languageinstructors_list->StopRec = $languageinstructors_list->StartRec + $languageinstructors_list->DisplayRecs - 1;
	else
		$languageinstructors_list->StopRec = $languageinstructors_list->TotalRecs;
}

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($languageinstructors_list->FormKeyCountName) && ($languageinstructors->CurrentAction == "gridadd" || $languageinstructors->CurrentAction == "gridedit" || $languageinstructors->CurrentAction == "F")) {
		$languageinstructors_list->KeyCount = $objForm->GetValue($languageinstructors_list->FormKeyCountName);
		$languageinstructors_list->StopRec = $languageinstructors_list->StartRec + $languageinstructors_list->KeyCount - 1;
	}
}
$languageinstructors_list->RecCnt = $languageinstructors_list->StartRec - 1;
if ($languageinstructors_list->Recordset && !$languageinstructors_list->Recordset->EOF) {
	$languageinstructors_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $languageinstructors_list->StartRec > 1)
		$languageinstructors_list->Recordset->Move($languageinstructors_list->StartRec - 1);
} elseif (!$languageinstructors->AllowAddDeleteRow && $languageinstructors_list->StopRec == 0) {
	$languageinstructors_list->StopRec = $languageinstructors->GridAddRowCount;
}

// Initialize aggregate
$languageinstructors->RowType = EW_ROWTYPE_AGGREGATEINIT;
$languageinstructors->ResetAttrs();
$languageinstructors_list->RenderRow();
$languageinstructors_list->EditRowCnt = 0;
if ($languageinstructors->CurrentAction == "edit")
	$languageinstructors_list->RowIndex = 1;
while ($languageinstructors_list->RecCnt < $languageinstructors_list->StopRec) {
	$languageinstructors_list->RecCnt++;
	if (intval($languageinstructors_list->RecCnt) >= intval($languageinstructors_list->StartRec)) {
		$languageinstructors_list->RowCnt++;

		// Set up key count
		$languageinstructors_list->KeyCount = $languageinstructors_list->RowIndex;

		// Init row class and style
		$languageinstructors->ResetAttrs();
		$languageinstructors->CssClass = "";
		if ($languageinstructors->CurrentAction == "gridadd") {
			$languageinstructors_list->LoadDefaultValues(); // Load default values
		} else {
			$languageinstructors_list->LoadRowValues($languageinstructors_list->Recordset); // Load row values
		}
		$languageinstructors->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($languageinstructors->CurrentAction == "edit") {
			if ($languageinstructors_list->CheckInlineEditKey() && $languageinstructors_list->EditRowCnt == 0) { // Inline edit
				$languageinstructors->RowType = EW_ROWTYPE_EDIT; // Render edit
			}
		}
		if ($languageinstructors->CurrentAction == "edit" && $languageinstructors->RowType == EW_ROWTYPE_EDIT && $languageinstructors->EventCancelled) { // Update failed
			$objForm->Index = 1;
			$languageinstructors_list->RestoreFormValues(); // Restore form values
		}
		if ($languageinstructors->RowType == EW_ROWTYPE_EDIT) // Edit row
			$languageinstructors_list->EditRowCnt++;

		// Set up row id / data-rowindex
		$languageinstructors->RowAttrs = array_merge($languageinstructors->RowAttrs, array('data-rowindex'=>$languageinstructors_list->RowCnt, 'id'=>'r' . $languageinstructors_list->RowCnt . '_languageinstructors', 'data-rowtype'=>$languageinstructors->RowType));

		// Render row
		$languageinstructors_list->RenderRow();

		// Render list options
		$languageinstructors_list->RenderListOptions();
?>
	<tr<?php echo $languageinstructors->RowAttributes() ?>>
<?php

// Render list options (body, left)
$languageinstructors_list->ListOptions->Render("body", "left", $languageinstructors_list->RowCnt);
?>
	<?php if ($languageinstructors->ID->Visible) { // ID ?>
		<td<?php echo $languageinstructors->ID->CellAttributes() ?>>
<?php if ($languageinstructors->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $languageinstructors_list->RowCnt ?>_languageinstructors_ID" class="control-group languageinstructors_ID">
<span<?php echo $languageinstructors->ID->ViewAttributes() ?>>
<?php echo $languageinstructors->ID->EditValue ?></span>
</span>
<input type="hidden" data-field="x_ID" name="x<?php echo $languageinstructors_list->RowIndex ?>_ID" id="x<?php echo $languageinstructors_list->RowIndex ?>_ID" value="<?php echo ew_HtmlEncode($languageinstructors->ID->CurrentValue) ?>">
<?php } ?>
<?php if ($languageinstructors->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $languageinstructors->ID->ViewAttributes() ?>>
<?php echo $languageinstructors->ID->ListViewValue() ?></span>
<?php } ?>
<a id="<?php echo $languageinstructors_list->PageObjName . "_row_" . $languageinstructors_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($languageinstructors->ApplicantName->Visible) { // ApplicantName ?>
		<td<?php echo $languageinstructors->ApplicantName->CellAttributes() ?>>
<?php if ($languageinstructors->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $languageinstructors_list->RowCnt ?>_languageinstructors_ApplicantName" class="control-group languageinstructors_ApplicantName">
<input type="text" data-field="x_ApplicantName" name="x<?php echo $languageinstructors_list->RowIndex ?>_ApplicantName" id="x<?php echo $languageinstructors_list->RowIndex ?>_ApplicantName" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($languageinstructors->ApplicantName->PlaceHolder) ?>" value="<?php echo $languageinstructors->ApplicantName->EditValue ?>"<?php echo $languageinstructors->ApplicantName->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($languageinstructors->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $languageinstructors->ApplicantName->ViewAttributes() ?>>
<?php echo $languageinstructors->ApplicantName->ListViewValue() ?></span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($languageinstructors->Nationality->Visible) { // Nationality ?>
		<td<?php echo $languageinstructors->Nationality->CellAttributes() ?>>
<?php if ($languageinstructors->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $languageinstructors_list->RowCnt ?>_languageinstructors_Nationality" class="control-group languageinstructors_Nationality">
<input type="text" data-field="x_Nationality" name="x<?php echo $languageinstructors_list->RowIndex ?>_Nationality" id="x<?php echo $languageinstructors_list->RowIndex ?>_Nationality" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($languageinstructors->Nationality->PlaceHolder) ?>" value="<?php echo $languageinstructors->Nationality->EditValue ?>"<?php echo $languageinstructors->Nationality->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($languageinstructors->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $languageinstructors->Nationality->ViewAttributes() ?>>
<?php echo $languageinstructors->Nationality->ListViewValue() ?></span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($languageinstructors->_Language->Visible) { // Language ?>
		<td<?php echo $languageinstructors->_Language->CellAttributes() ?>>
<?php if ($languageinstructors->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $languageinstructors_list->RowCnt ?>_languageinstructors__Language" class="control-group languageinstructors__Language">
<div id="tp_x<?php echo $languageinstructors_list->RowIndex ?>__Language" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $languageinstructors_list->RowIndex ?>__Language" id="x<?php echo $languageinstructors_list->RowIndex ?>__Language" value="{value}"<?php echo $languageinstructors->_Language->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $languageinstructors_list->RowIndex ?>__Language" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $languageinstructors->_Language->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($languageinstructors->_Language->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x__Language" name="x<?php echo $languageinstructors_list->RowIndex ?>__Language" id="x<?php echo $languageinstructors_list->RowIndex ?>__Language_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $languageinstructors->_Language->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php } ?>
<?php if ($languageinstructors->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $languageinstructors->_Language->ViewAttributes() ?>>
<?php echo $languageinstructors->_Language->ListViewValue() ?></span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($languageinstructors->College1->Visible) { // College1 ?>
		<td<?php echo $languageinstructors->College1->CellAttributes() ?>>
<?php if ($languageinstructors->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $languageinstructors_list->RowCnt ?>_languageinstructors_College1" class="control-group languageinstructors_College1">
<select data-field="x_College1" id="x<?php echo $languageinstructors_list->RowIndex ?>_College1" name="x<?php echo $languageinstructors_list->RowIndex ?>_College1"<?php echo $languageinstructors->College1->EditAttributes() ?>>
<?php
if (is_array($languageinstructors->College1->EditValue)) {
	$arwrk = $languageinstructors->College1->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($languageinstructors->College1->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
flanguageinstructorslist.Lists["x_College1"].Options = <?php echo (is_array($languageinstructors->College1->EditValue)) ? ew_ArrayToJson($languageinstructors->College1->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php } ?>
<?php if ($languageinstructors->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $languageinstructors->College1->ViewAttributes() ?>>
<?php echo $languageinstructors->College1->ListViewValue() ?></span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($languageinstructors->College2->Visible) { // College2 ?>
		<td<?php echo $languageinstructors->College2->CellAttributes() ?>>
<?php if ($languageinstructors->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $languageinstructors_list->RowCnt ?>_languageinstructors_College2" class="control-group languageinstructors_College2">
<select data-field="x_College2" id="x<?php echo $languageinstructors_list->RowIndex ?>_College2" name="x<?php echo $languageinstructors_list->RowIndex ?>_College2"<?php echo $languageinstructors->College2->EditAttributes() ?>>
<?php
if (is_array($languageinstructors->College2->EditValue)) {
	$arwrk = $languageinstructors->College2->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($languageinstructors->College2->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
flanguageinstructorslist.Lists["x_College2"].Options = <?php echo (is_array($languageinstructors->College2->EditValue)) ? ew_ArrayToJson($languageinstructors->College2->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php } ?>
<?php if ($languageinstructors->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $languageinstructors->College2->ViewAttributes() ?>>
<?php echo $languageinstructors->College2->ListViewValue() ?></span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($languageinstructors->College3->Visible) { // College3 ?>
		<td<?php echo $languageinstructors->College3->CellAttributes() ?>>
<?php if ($languageinstructors->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $languageinstructors_list->RowCnt ?>_languageinstructors_College3" class="control-group languageinstructors_College3">
<select data-field="x_College3" id="x<?php echo $languageinstructors_list->RowIndex ?>_College3" name="x<?php echo $languageinstructors_list->RowIndex ?>_College3"<?php echo $languageinstructors->College3->EditAttributes() ?>>
<?php
if (is_array($languageinstructors->College3->EditValue)) {
	$arwrk = $languageinstructors->College3->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($languageinstructors->College3->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
flanguageinstructorslist.Lists["x_College3"].Options = <?php echo (is_array($languageinstructors->College3->EditValue)) ? ew_ArrayToJson($languageinstructors->College3->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php } ?>
<?php if ($languageinstructors->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $languageinstructors->College3->ViewAttributes() ?>>
<?php echo $languageinstructors->College3->ListViewValue() ?></span>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$languageinstructors_list->ListOptions->Render("body", "right", $languageinstructors_list->RowCnt);
?>
	</tr>
<?php if ($languageinstructors->RowType == EW_ROWTYPE_ADD || $languageinstructors->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
flanguageinstructorslist.UpdateOpts(<?php echo $languageinstructors_list->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	if ($languageinstructors->CurrentAction <> "gridadd")
		$languageinstructors_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($languageinstructors->CurrentAction == "edit") { ?>
<input type="hidden" name="<?php echo $languageinstructors_list->FormKeyCountName ?>" id="<?php echo $languageinstructors_list->FormKeyCountName ?>" value="<?php echo $languageinstructors_list->KeyCount ?>">
<?php } ?>
<?php if ($languageinstructors->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($languageinstructors_list->Recordset)
	$languageinstructors_list->Recordset->Close();
?>
<?php if ($languageinstructors_list->TotalRecs > 0) { ?>
<?php if ($languageinstructors->Export == "") { ?>
<div class="ewGridLowerPanel">
<?php if ($languageinstructors->CurrentAction <> "gridadd" && $languageinstructors->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager">
<tr><td>
<?php if (!isset($languageinstructors_list->Pager)) $languageinstructors_list->Pager = new cPrevNextPager($languageinstructors_list->StartRec, $languageinstructors_list->DisplayRecs, $languageinstructors_list->TotalRecs) ?>
<?php if ($languageinstructors_list->Pager->RecordCount > 0) { ?>
<table class="ewStdTable"><tbody><tr><td>
	<?php echo $Language->Phrase("Page") ?>&nbsp;
<div class="input-prepend input-append">
<!--first page button-->
	<?php if ($languageinstructors_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $languageinstructors_list->PageUrl() ?>start=<?php echo $languageinstructors_list->Pager->FirstButton->Start ?>"><i class="icon-step-backward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-step-backward"></i></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($languageinstructors_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $languageinstructors_list->PageUrl() ?>start=<?php echo $languageinstructors_list->Pager->PrevButton->Start ?>"><i class="icon-prev"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-prev"></i></a>
	<?php } ?>
<!--current page number-->
	<input class="input-mini" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $languageinstructors_list->Pager->CurrentPage ?>">
<!--next page button-->
	<?php if ($languageinstructors_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $languageinstructors_list->PageUrl() ?>start=<?php echo $languageinstructors_list->Pager->NextButton->Start ?>"><i class="icon-play"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-play"></i></a>
	<?php } ?>
<!--last page button-->
	<?php if ($languageinstructors_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $languageinstructors_list->PageUrl() ?>start=<?php echo $languageinstructors_list->Pager->LastButton->Start ?>"><i class="icon-step-forward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-step-forward"></i></a>
	<?php } ?>
</div>
	&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $languageinstructors_list->Pager->PageCount ?>
</td>
<td>
	&nbsp;&nbsp;&nbsp;&nbsp;
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $languageinstructors_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $languageinstructors_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $languageinstructors_list->Pager->RecordCount ?>
</td>
</tr></tbody></table>
<?php } else { ?>
	<?php if ($Security->CanList()) { ?>
	<?php if ($languageinstructors_list->SearchWhere == "0=101") { ?>
	<p><?php echo $Language->Phrase("EnterSearchCriteria") ?></p>
	<?php } else { ?>
	<p><?php echo $Language->Phrase("NoRecord") ?></p>
	<?php } ?>
	<?php } else { ?>
	<p><?php echo $Language->Phrase("NoPermission") ?></p>
	<?php } ?>
<?php } ?>
</td>
</tr></table>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($languageinstructors_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
</div>
<?php } ?>
<?php } ?>
</td></tr></table>
<?php if ($languageinstructors->Export == "") { ?>
<script type="text/javascript">
flanguageinstructorslistsrch.Init();
flanguageinstructorslist.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$languageinstructors_list->ShowPageFooter();
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
$languageinstructors_list->Page_Terminate();
?>
