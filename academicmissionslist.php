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

$academicmissions_list = NULL; // Initialize page object first

class cacademicmissions_list extends cacademicmissions {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{F8E6AD82-57C4-4BEE-8975-8D386184467A}";

	// Table name
	var $TableName = 'academicmissions';

	// Page object name
	var $PageObjName = 'academicmissions_list';

	// Grid form hidden field names
	var $FormName = 'facademicmissionslist';
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

		// Table object (academicmissions)
		if (!isset($GLOBALS["academicmissions"]) || get_class($GLOBALS["academicmissions"]) == "cacademicmissions") {
			$GLOBALS["academicmissions"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["academicmissions"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "academicmissionsadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "academicmissionsdelete.php";
		$this->MultiUpdateUrl = "academicmissionsupdate.php";

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'academicmissions', TRUE);

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

			// Get basic search values
			$this->LoadBasicSearchValues();

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

			// Get basic search criteria
			if ($gsSearchError == "")
				$sSrchBasic = $this->BasicSearchWhere();

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

			// Load basic search from default
			$this->BasicSearch->LoadDefault();
			if ($this->BasicSearch->Keyword != "")
				$sSrchBasic = $this->BasicSearchWhere();

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
		$this->BuildSearchSql($sWhere, $this->Name, FALSE); // Name
		$this->BuildSearchSql($sWhere, $this->UniversityID, FALSE); // UniversityID
		$this->BuildSearchSql($sWhere, $this->College, FALSE); // College
		$this->BuildSearchSql($sWhere, $this->Department, FALSE); // Department
		$this->BuildSearchSql($sWhere, $this->StartDate, FALSE); // StartDate
		$this->BuildSearchSql($sWhere, $this->EndDate, FALSE); // EndDate
		$this->BuildSearchSql($sWhere, $this->PlaceVisited, FALSE); // PlaceVisited
		$this->BuildSearchSql($sWhere, $this->NatureOfVisit, FALSE); // NatureOfVisit
		$this->BuildSearchSql($sWhere, $this->AttendanceOnly, FALSE); // AttendanceOnly
		$this->BuildSearchSql($sWhere, $this->PresentAPaper, FALSE); // PresentAPaper
		$this->BuildSearchSql($sWhere, $this->Others, FALSE); // Others
		$this->BuildSearchSql($sWhere, $this->Participation, FALSE); // Participation
		$this->BuildSearchSql($sWhere, $this->Summary, FALSE); // Summary
		$this->BuildSearchSql($sWhere, $this->SuggestionRecommendation, FALSE); // SuggestionRecommendation
		$this->BuildSearchSql($sWhere, $this->FacultyMemberSign, FALSE); // FacultyMemberSign
		$this->BuildSearchSql($sWhere, $this->DepChairmanSign, FALSE); // DepChairmanSign
		$this->BuildSearchSql($sWhere, $this->DeanSign, FALSE); // DeanSign

		// Set up search parm
		if ($sWhere <> "") {
			$this->Command = "search";
		}
		if ($this->Command == "search") {
			$this->ID->AdvancedSearch->Save(); // ID
			$this->Name->AdvancedSearch->Save(); // Name
			$this->UniversityID->AdvancedSearch->Save(); // UniversityID
			$this->College->AdvancedSearch->Save(); // College
			$this->Department->AdvancedSearch->Save(); // Department
			$this->StartDate->AdvancedSearch->Save(); // StartDate
			$this->EndDate->AdvancedSearch->Save(); // EndDate
			$this->PlaceVisited->AdvancedSearch->Save(); // PlaceVisited
			$this->NatureOfVisit->AdvancedSearch->Save(); // NatureOfVisit
			$this->AttendanceOnly->AdvancedSearch->Save(); // AttendanceOnly
			$this->PresentAPaper->AdvancedSearch->Save(); // PresentAPaper
			$this->Others->AdvancedSearch->Save(); // Others
			$this->Participation->AdvancedSearch->Save(); // Participation
			$this->Summary->AdvancedSearch->Save(); // Summary
			$this->SuggestionRecommendation->AdvancedSearch->Save(); // SuggestionRecommendation
			$this->FacultyMemberSign->AdvancedSearch->Save(); // FacultyMemberSign
			$this->DepChairmanSign->AdvancedSearch->Save(); // DepChairmanSign
			$this->DeanSign->AdvancedSearch->Save(); // DeanSign
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

	// Return basic search SQL
	function BasicSearchSQL($Keyword) {
		$sKeyword = ew_AdjustSql($Keyword);
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->Name, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->College, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->Department, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->PlaceVisited, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->NatureOfVisit, $Keyword);
		return $sWhere;
	}

	// Build basic search SQL
	function BuildBasicSearchSql(&$Where, &$Fld, $Keyword) {
		if ($Keyword == EW_NULL_VALUE) {
			$sWrk = $Fld->FldExpression . " IS NULL";
		} elseif ($Keyword == EW_NOT_NULL_VALUE) {
			$sWrk = $Fld->FldExpression . " IS NOT NULL";
		} else {
			$sFldExpression = ($Fld->FldVirtualExpression <> $Fld->FldExpression) ? $Fld->FldVirtualExpression : $Fld->FldBasicSearchExpression;
			$sWrk = $sFldExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING));
		}
		if ($Where <> "") $Where .= " OR ";
		$Where .= $sWrk;
	}

	// Return basic search WHERE clause based on search keyword and type
	function BasicSearchWhere() {
		global $Security;
		$sSearchStr = "";
		if (!$Security->CanSearch()) return "";
		$sSearchKeyword = $this->BasicSearch->Keyword;
		$sSearchType = $this->BasicSearch->Type;
		if ($sSearchKeyword <> "") {
			$sSearch = trim($sSearchKeyword);
			if ($sSearchType <> "=") {
				while (strpos($sSearch, "  ") !== FALSE)
					$sSearch = str_replace("  ", " ", $sSearch);
				$arKeyword = explode(" ", trim($sSearch));
				foreach ($arKeyword as $sKeyword) {
					if ($sSearchStr <> "") $sSearchStr .= " " . $sSearchType . " ";
					$sSearchStr .= "(" . $this->BasicSearchSQL($sKeyword) . ")";
				}
			} else {
				$sSearchStr = $this->BasicSearchSQL($sSearch);
			}
			$this->Command = "search";
		}
		if ($this->Command == "search") {
			$this->BasicSearch->setKeyword($sSearchKeyword);
			$this->BasicSearch->setType($sSearchType);
		}
		return $sSearchStr;
	}

	// Check if search parm exists
	function CheckSearchParms() {

		// Check basic search
		if ($this->BasicSearch->IssetSession())
			return TRUE;
		if ($this->ID->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Name->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->UniversityID->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->College->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Department->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->StartDate->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->EndDate->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->PlaceVisited->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->NatureOfVisit->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->AttendanceOnly->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->PresentAPaper->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Others->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Participation->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Summary->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->SuggestionRecommendation->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->FacultyMemberSign->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->DepChairmanSign->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->DeanSign->AdvancedSearch->IssetSession())
			return TRUE;
		return FALSE;
	}

	// Clear all search parameters
	function ResetSearchParms() {

		// Clear search WHERE clause
		$this->SearchWhere = "";
		$this->setSearchWhere($this->SearchWhere);

		// Clear basic search parameters
		$this->ResetBasicSearchParms();

		// Clear advanced search parameters
		$this->ResetAdvancedSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all basic search parameters
	function ResetBasicSearchParms() {
		$this->BasicSearch->UnsetSession();
	}

	// Clear all advanced search parameters
	function ResetAdvancedSearchParms() {
		$this->ID->AdvancedSearch->UnsetSession();
		$this->Name->AdvancedSearch->UnsetSession();
		$this->UniversityID->AdvancedSearch->UnsetSession();
		$this->College->AdvancedSearch->UnsetSession();
		$this->Department->AdvancedSearch->UnsetSession();
		$this->StartDate->AdvancedSearch->UnsetSession();
		$this->EndDate->AdvancedSearch->UnsetSession();
		$this->PlaceVisited->AdvancedSearch->UnsetSession();
		$this->NatureOfVisit->AdvancedSearch->UnsetSession();
		$this->AttendanceOnly->AdvancedSearch->UnsetSession();
		$this->PresentAPaper->AdvancedSearch->UnsetSession();
		$this->Others->AdvancedSearch->UnsetSession();
		$this->Participation->AdvancedSearch->UnsetSession();
		$this->Summary->AdvancedSearch->UnsetSession();
		$this->SuggestionRecommendation->AdvancedSearch->UnsetSession();
		$this->FacultyMemberSign->AdvancedSearch->UnsetSession();
		$this->DepChairmanSign->AdvancedSearch->UnsetSession();
		$this->DeanSign->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();

		// Restore advanced search values
		$this->ID->AdvancedSearch->Load();
		$this->Name->AdvancedSearch->Load();
		$this->UniversityID->AdvancedSearch->Load();
		$this->College->AdvancedSearch->Load();
		$this->Department->AdvancedSearch->Load();
		$this->StartDate->AdvancedSearch->Load();
		$this->EndDate->AdvancedSearch->Load();
		$this->PlaceVisited->AdvancedSearch->Load();
		$this->NatureOfVisit->AdvancedSearch->Load();
		$this->AttendanceOnly->AdvancedSearch->Load();
		$this->PresentAPaper->AdvancedSearch->Load();
		$this->Others->AdvancedSearch->Load();
		$this->Participation->AdvancedSearch->Load();
		$this->Summary->AdvancedSearch->Load();
		$this->SuggestionRecommendation->AdvancedSearch->Load();
		$this->FacultyMemberSign->AdvancedSearch->Load();
		$this->DepChairmanSign->AdvancedSearch->Load();
		$this->DeanSign->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->ID); // ID
			$this->UpdateSort($this->Name); // Name
			$this->UpdateSort($this->UniversityID); // UniversityID
			$this->UpdateSort($this->College); // College
			$this->UpdateSort($this->Department); // Department
			$this->UpdateSort($this->StartDate); // StartDate
			$this->UpdateSort($this->EndDate); // EndDate
			$this->UpdateSort($this->PlaceVisited); // PlaceVisited
			$this->UpdateSort($this->NatureOfVisit); // NatureOfVisit
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
				$this->Name->setSort("");
				$this->UniversityID->setSort("");
				$this->College->setSort("");
				$this->Department->setSort("");
				$this->StartDate->setSort("");
				$this->EndDate->setSort("");
				$this->PlaceVisited->setSort("");
				$this->NatureOfVisit->setSort("");
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
		$item->Body = "<a class=\"ewAction ewMultiDelete\" href=\"\" onclick=\"ew_SubmitSelected(document.facademicmissionslist, '" . $this->MultiDeleteUrl . "');return false;\">" . $Language->Phrase("DeleteSelectedLink") . "</a>";
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.facademicmissionslist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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
		$this->Name->CurrentValue = NULL;
		$this->Name->OldValue = $this->Name->CurrentValue;
		$this->UniversityID->CurrentValue = NULL;
		$this->UniversityID->OldValue = $this->UniversityID->CurrentValue;
		$this->College->CurrentValue = NULL;
		$this->College->OldValue = $this->College->CurrentValue;
		$this->Department->CurrentValue = NULL;
		$this->Department->OldValue = $this->Department->CurrentValue;
		$this->StartDate->CurrentValue = NULL;
		$this->StartDate->OldValue = $this->StartDate->CurrentValue;
		$this->EndDate->CurrentValue = NULL;
		$this->EndDate->OldValue = $this->EndDate->CurrentValue;
		$this->PlaceVisited->CurrentValue = NULL;
		$this->PlaceVisited->OldValue = $this->PlaceVisited->CurrentValue;
		$this->NatureOfVisit->CurrentValue = NULL;
		$this->NatureOfVisit->OldValue = $this->NatureOfVisit->CurrentValue;
	}

	// Load basic search values
	function LoadBasicSearchValues() {
		$this->BasicSearch->Keyword = @$_GET[EW_TABLE_BASIC_SEARCH];
		if ($this->BasicSearch->Keyword <> "") $this->Command = "search";
		$this->BasicSearch->Type = @$_GET[EW_TABLE_BASIC_SEARCH_TYPE];
	}

	//  Load search values for validation
	function LoadSearchValues() {
		global $objForm;

		// Load search values
		// ID

		$this->ID->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ID"]);
		if ($this->ID->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ID->AdvancedSearch->SearchOperator = @$_GET["z_ID"];

		// Name
		$this->Name->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Name"]);
		if ($this->Name->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Name->AdvancedSearch->SearchOperator = @$_GET["z_Name"];

		// UniversityID
		$this->UniversityID->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_UniversityID"]);
		if ($this->UniversityID->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->UniversityID->AdvancedSearch->SearchOperator = @$_GET["z_UniversityID"];

		// College
		$this->College->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_College"]);
		if ($this->College->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->College->AdvancedSearch->SearchOperator = @$_GET["z_College"];

		// Department
		$this->Department->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Department"]);
		if ($this->Department->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Department->AdvancedSearch->SearchOperator = @$_GET["z_Department"];

		// StartDate
		$this->StartDate->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_StartDate"]);
		if ($this->StartDate->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->StartDate->AdvancedSearch->SearchOperator = @$_GET["z_StartDate"];

		// EndDate
		$this->EndDate->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_EndDate"]);
		if ($this->EndDate->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->EndDate->AdvancedSearch->SearchOperator = @$_GET["z_EndDate"];

		// PlaceVisited
		$this->PlaceVisited->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_PlaceVisited"]);
		if ($this->PlaceVisited->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->PlaceVisited->AdvancedSearch->SearchOperator = @$_GET["z_PlaceVisited"];

		// NatureOfVisit
		$this->NatureOfVisit->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_NatureOfVisit"]);
		if ($this->NatureOfVisit->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->NatureOfVisit->AdvancedSearch->SearchOperator = @$_GET["z_NatureOfVisit"];

		// AttendanceOnly
		$this->AttendanceOnly->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_AttendanceOnly"]);
		if ($this->AttendanceOnly->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->AttendanceOnly->AdvancedSearch->SearchOperator = @$_GET["z_AttendanceOnly"];

		// PresentAPaper
		$this->PresentAPaper->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_PresentAPaper"]);
		if ($this->PresentAPaper->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->PresentAPaper->AdvancedSearch->SearchOperator = @$_GET["z_PresentAPaper"];

		// Others
		$this->Others->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Others"]);
		if ($this->Others->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Others->AdvancedSearch->SearchOperator = @$_GET["z_Others"];

		// Participation
		$this->Participation->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Participation"]);
		if ($this->Participation->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Participation->AdvancedSearch->SearchOperator = @$_GET["z_Participation"];

		// Summary
		$this->Summary->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Summary"]);
		if ($this->Summary->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Summary->AdvancedSearch->SearchOperator = @$_GET["z_Summary"];

		// SuggestionRecommendation
		$this->SuggestionRecommendation->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_SuggestionRecommendation"]);
		if ($this->SuggestionRecommendation->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->SuggestionRecommendation->AdvancedSearch->SearchOperator = @$_GET["z_SuggestionRecommendation"];

		// FacultyMemberSign
		$this->FacultyMemberSign->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_FacultyMemberSign"]);
		if ($this->FacultyMemberSign->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->FacultyMemberSign->AdvancedSearch->SearchOperator = @$_GET["z_FacultyMemberSign"];

		// DepChairmanSign
		$this->DepChairmanSign->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_DepChairmanSign"]);
		if ($this->DepChairmanSign->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->DepChairmanSign->AdvancedSearch->SearchOperator = @$_GET["z_DepChairmanSign"];

		// DeanSign
		$this->DeanSign->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_DeanSign"]);
		if ($this->DeanSign->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->DeanSign->AdvancedSearch->SearchOperator = @$_GET["z_DeanSign"];
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->ID->FldIsDetailKey && $this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->ID->setFormValue($objForm->GetValue("x_ID"));
		if (!$this->Name->FldIsDetailKey) {
			$this->Name->setFormValue($objForm->GetValue("x_Name"));
		}
		if (!$this->UniversityID->FldIsDetailKey) {
			$this->UniversityID->setFormValue($objForm->GetValue("x_UniversityID"));
		}
		if (!$this->College->FldIsDetailKey) {
			$this->College->setFormValue($objForm->GetValue("x_College"));
		}
		if (!$this->Department->FldIsDetailKey) {
			$this->Department->setFormValue($objForm->GetValue("x_Department"));
		}
		if (!$this->StartDate->FldIsDetailKey) {
			$this->StartDate->setFormValue($objForm->GetValue("x_StartDate"));
			$this->StartDate->CurrentValue = ew_UnFormatDateTime($this->StartDate->CurrentValue, 7);
		}
		if (!$this->EndDate->FldIsDetailKey) {
			$this->EndDate->setFormValue($objForm->GetValue("x_EndDate"));
			$this->EndDate->CurrentValue = ew_UnFormatDateTime($this->EndDate->CurrentValue, 7);
		}
		if (!$this->PlaceVisited->FldIsDetailKey) {
			$this->PlaceVisited->setFormValue($objForm->GetValue("x_PlaceVisited"));
		}
		if (!$this->NatureOfVisit->FldIsDetailKey) {
			$this->NatureOfVisit->setFormValue($objForm->GetValue("x_NatureOfVisit"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		if ($this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->ID->CurrentValue = $this->ID->FormValue;
		$this->Name->CurrentValue = $this->Name->FormValue;
		$this->UniversityID->CurrentValue = $this->UniversityID->FormValue;
		$this->College->CurrentValue = $this->College->FormValue;
		$this->Department->CurrentValue = $this->Department->FormValue;
		$this->StartDate->CurrentValue = $this->StartDate->FormValue;
		$this->StartDate->CurrentValue = ew_UnFormatDateTime($this->StartDate->CurrentValue, 7);
		$this->EndDate->CurrentValue = $this->EndDate->FormValue;
		$this->EndDate->CurrentValue = ew_UnFormatDateTime($this->EndDate->CurrentValue, 7);
		$this->PlaceVisited->CurrentValue = $this->PlaceVisited->FormValue;
		$this->NatureOfVisit->CurrentValue = $this->NatureOfVisit->FormValue;
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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// ID
			// Name

			$this->Name->EditCustomAttributes = "";
			$this->Name->EditValue = ew_HtmlEncode($this->Name->CurrentValue);
			$this->Name->PlaceHolder = ew_RemoveHtml($this->Name->FldCaption());

			// UniversityID
			$this->UniversityID->EditCustomAttributes = "";
			$this->UniversityID->EditValue = ew_HtmlEncode($this->UniversityID->CurrentValue);
			$this->UniversityID->PlaceHolder = ew_RemoveHtml($this->UniversityID->FldCaption());

			// College
			$this->College->EditCustomAttributes = "";

			// Department
			$this->Department->EditCustomAttributes = "";

			// StartDate
			$this->StartDate->EditCustomAttributes = "";
			$this->StartDate->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->StartDate->CurrentValue, 7));
			$this->StartDate->PlaceHolder = ew_RemoveHtml($this->StartDate->FldCaption());

			// EndDate
			$this->EndDate->EditCustomAttributes = "";
			$this->EndDate->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->EndDate->CurrentValue, 7));
			$this->EndDate->PlaceHolder = ew_RemoveHtml($this->EndDate->FldCaption());

			// PlaceVisited
			$this->PlaceVisited->EditCustomAttributes = "";
			$this->PlaceVisited->EditValue = ew_HtmlEncode($this->PlaceVisited->CurrentValue);
			$this->PlaceVisited->PlaceHolder = ew_RemoveHtml($this->PlaceVisited->FldCaption());

			// NatureOfVisit
			$this->NatureOfVisit->EditCustomAttributes = "";
			$this->NatureOfVisit->EditValue = ew_HtmlEncode($this->NatureOfVisit->CurrentValue);
			$this->NatureOfVisit->PlaceHolder = ew_RemoveHtml($this->NatureOfVisit->FldCaption());

			// Edit refer script
			// ID

			$this->ID->HrefValue = "";

			// Name
			$this->Name->HrefValue = "";

			// UniversityID
			$this->UniversityID->HrefValue = "";

			// College
			$this->College->HrefValue = "";

			// Department
			$this->Department->HrefValue = "";

			// StartDate
			$this->StartDate->HrefValue = "";

			// EndDate
			$this->EndDate->HrefValue = "";

			// PlaceVisited
			$this->PlaceVisited->HrefValue = "";

			// NatureOfVisit
			$this->NatureOfVisit->HrefValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// ID
			$this->ID->EditCustomAttributes = "";
			$this->ID->EditValue = $this->ID->CurrentValue;
			$this->ID->ViewCustomAttributes = "";

			// Name
			$this->Name->EditCustomAttributes = "";
			$this->Name->EditValue = ew_HtmlEncode($this->Name->CurrentValue);
			$this->Name->PlaceHolder = ew_RemoveHtml($this->Name->FldCaption());

			// UniversityID
			$this->UniversityID->EditCustomAttributes = "";
			$this->UniversityID->EditValue = ew_HtmlEncode($this->UniversityID->CurrentValue);
			$this->UniversityID->PlaceHolder = ew_RemoveHtml($this->UniversityID->FldCaption());

			// College
			$this->College->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT `CollegeID`, `College Name EN` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `colleges`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->College, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->College->EditValue = $arwrk;

			// Department
			$this->Department->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT `DID`, `DepartmentName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, `CID` AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `departments`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->Department, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->Department->EditValue = $arwrk;

			// StartDate
			$this->StartDate->EditCustomAttributes = "";
			$this->StartDate->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->StartDate->CurrentValue, 7));
			$this->StartDate->PlaceHolder = ew_RemoveHtml($this->StartDate->FldCaption());

			// EndDate
			$this->EndDate->EditCustomAttributes = "";
			$this->EndDate->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->EndDate->CurrentValue, 7));
			$this->EndDate->PlaceHolder = ew_RemoveHtml($this->EndDate->FldCaption());

			// PlaceVisited
			$this->PlaceVisited->EditCustomAttributes = "";
			$this->PlaceVisited->EditValue = ew_HtmlEncode($this->PlaceVisited->CurrentValue);
			$this->PlaceVisited->PlaceHolder = ew_RemoveHtml($this->PlaceVisited->FldCaption());

			// NatureOfVisit
			$this->NatureOfVisit->EditCustomAttributes = "";
			$this->NatureOfVisit->EditValue = ew_HtmlEncode($this->NatureOfVisit->CurrentValue);
			$this->NatureOfVisit->PlaceHolder = ew_RemoveHtml($this->NatureOfVisit->FldCaption());

			// Edit refer script
			// ID

			$this->ID->HrefValue = "";

			// Name
			$this->Name->HrefValue = "";

			// UniversityID
			$this->UniversityID->HrefValue = "";

			// College
			$this->College->HrefValue = "";

			// Department
			$this->Department->HrefValue = "";

			// StartDate
			$this->StartDate->HrefValue = "";

			// EndDate
			$this->EndDate->HrefValue = "";

			// PlaceVisited
			$this->PlaceVisited->HrefValue = "";

			// NatureOfVisit
			$this->NatureOfVisit->HrefValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// ID
			$this->ID->EditCustomAttributes = "";
			$this->ID->EditValue = ew_HtmlEncode($this->ID->AdvancedSearch->SearchValue);
			$this->ID->PlaceHolder = ew_RemoveHtml($this->ID->FldCaption());

			// Name
			$this->Name->EditCustomAttributes = "";
			$this->Name->EditValue = ew_HtmlEncode($this->Name->AdvancedSearch->SearchValue);
			$this->Name->PlaceHolder = ew_RemoveHtml($this->Name->FldCaption());

			// UniversityID
			$this->UniversityID->EditCustomAttributes = "";
			$this->UniversityID->EditValue = ew_HtmlEncode($this->UniversityID->AdvancedSearch->SearchValue);
			$this->UniversityID->PlaceHolder = ew_RemoveHtml($this->UniversityID->FldCaption());

			// College
			$this->College->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT `CollegeID`, `College Name EN` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `colleges`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->College, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->College->EditValue = $arwrk;

			// Department
			$this->Department->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT `DID`, `DepartmentName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, `CID` AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `departments`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->Department, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->Department->EditValue = $arwrk;

			// StartDate
			$this->StartDate->EditCustomAttributes = "";
			$this->StartDate->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->StartDate->AdvancedSearch->SearchValue, 7), 7));
			$this->StartDate->PlaceHolder = ew_RemoveHtml($this->StartDate->FldCaption());

			// EndDate
			$this->EndDate->EditCustomAttributes = "";
			$this->EndDate->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->EndDate->AdvancedSearch->SearchValue, 7), 7));
			$this->EndDate->PlaceHolder = ew_RemoveHtml($this->EndDate->FldCaption());

			// PlaceVisited
			$this->PlaceVisited->EditCustomAttributes = "";
			$this->PlaceVisited->EditValue = ew_HtmlEncode($this->PlaceVisited->AdvancedSearch->SearchValue);
			$this->PlaceVisited->PlaceHolder = ew_RemoveHtml($this->PlaceVisited->FldCaption());

			// NatureOfVisit
			$this->NatureOfVisit->EditCustomAttributes = "";
			$this->NatureOfVisit->EditValue = ew_HtmlEncode($this->NatureOfVisit->AdvancedSearch->SearchValue);
			$this->NatureOfVisit->PlaceHolder = ew_RemoveHtml($this->NatureOfVisit->FldCaption());
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
		if (!ew_CheckInteger($this->UniversityID->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->UniversityID->FldErrMsg());
		}

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
		if (!ew_CheckInteger($this->UniversityID->FormValue)) {
			ew_AddMessage($gsFormError, $this->UniversityID->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->StartDate->FormValue)) {
			ew_AddMessage($gsFormError, $this->StartDate->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->EndDate->FormValue)) {
			ew_AddMessage($gsFormError, $this->EndDate->FldErrMsg());
		}

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

			// Name
			$this->Name->SetDbValueDef($rsnew, $this->Name->CurrentValue, NULL, $this->Name->ReadOnly);

			// UniversityID
			$this->UniversityID->SetDbValueDef($rsnew, $this->UniversityID->CurrentValue, NULL, $this->UniversityID->ReadOnly);

			// College
			$this->College->SetDbValueDef($rsnew, $this->College->CurrentValue, NULL, $this->College->ReadOnly);

			// Department
			$this->Department->SetDbValueDef($rsnew, $this->Department->CurrentValue, NULL, $this->Department->ReadOnly);

			// StartDate
			$this->StartDate->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->StartDate->CurrentValue, 7), NULL, $this->StartDate->ReadOnly);

			// EndDate
			$this->EndDate->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->EndDate->CurrentValue, 7), NULL, $this->EndDate->ReadOnly);

			// PlaceVisited
			$this->PlaceVisited->SetDbValueDef($rsnew, $this->PlaceVisited->CurrentValue, NULL, $this->PlaceVisited->ReadOnly);

			// NatureOfVisit
			$this->NatureOfVisit->SetDbValueDef($rsnew, $this->NatureOfVisit->CurrentValue, NULL, $this->NatureOfVisit->ReadOnly);

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

		// Name
		$this->Name->SetDbValueDef($rsnew, $this->Name->CurrentValue, NULL, FALSE);

		// UniversityID
		$this->UniversityID->SetDbValueDef($rsnew, $this->UniversityID->CurrentValue, NULL, FALSE);

		// College
		$this->College->SetDbValueDef($rsnew, $this->College->CurrentValue, NULL, FALSE);

		// Department
		$this->Department->SetDbValueDef($rsnew, $this->Department->CurrentValue, NULL, FALSE);

		// StartDate
		$this->StartDate->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->StartDate->CurrentValue, 7), NULL, FALSE);

		// EndDate
		$this->EndDate->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->EndDate->CurrentValue, 7), NULL, FALSE);

		// PlaceVisited
		$this->PlaceVisited->SetDbValueDef($rsnew, $this->PlaceVisited->CurrentValue, NULL, FALSE);

		// NatureOfVisit
		$this->NatureOfVisit->SetDbValueDef($rsnew, $this->NatureOfVisit->CurrentValue, NULL, FALSE);

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
		$this->Name->AdvancedSearch->Load();
		$this->UniversityID->AdvancedSearch->Load();
		$this->College->AdvancedSearch->Load();
		$this->Department->AdvancedSearch->Load();
		$this->StartDate->AdvancedSearch->Load();
		$this->EndDate->AdvancedSearch->Load();
		$this->PlaceVisited->AdvancedSearch->Load();
		$this->NatureOfVisit->AdvancedSearch->Load();
		$this->AttendanceOnly->AdvancedSearch->Load();
		$this->PresentAPaper->AdvancedSearch->Load();
		$this->Others->AdvancedSearch->Load();
		$this->Participation->AdvancedSearch->Load();
		$this->Summary->AdvancedSearch->Load();
		$this->SuggestionRecommendation->AdvancedSearch->Load();
		$this->FacultyMemberSign->AdvancedSearch->Load();
		$this->DepChairmanSign->AdvancedSearch->Load();
		$this->DeanSign->AdvancedSearch->Load();
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
		$item->Body = "<a id=\"emf_academicmissions\" href=\"javascript:void(0);\" class=\"ewExportLink ewEmail\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_academicmissions',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.facademicmissionslist,sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
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
		$table = 'academicmissions';
	  $usr = CurrentUserName();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (edit page)
	function WriteAuditTrailOnEdit(&$rsold, &$rsnew) {
		if (!$this->AuditTrailOnEdit) return;
		$table = 'academicmissions';

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
		$table = 'academicmissions';

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
if (!isset($academicmissions_list)) $academicmissions_list = new cacademicmissions_list();

// Page init
$academicmissions_list->Page_Init();

// Page main
$academicmissions_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$academicmissions_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($academicmissions->Export == "") { ?>
<script type="text/javascript">

// Page object
var academicmissions_list = new ew_Page("academicmissions_list");
academicmissions_list.PageID = "list"; // Page ID
var EW_PAGE_ID = academicmissions_list.PageID; // For backward compatibility

// Form object
var facademicmissionslist = new ew_Form("facademicmissionslist");
facademicmissionslist.FormKeyCountName = '<?php echo $academicmissions_list->FormKeyCountName ?>';

// Validate form
facademicmissionslist.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_UniversityID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($academicmissions->UniversityID->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_StartDate");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($academicmissions->StartDate->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_EndDate");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($academicmissions->EndDate->FldErrMsg()) ?>");

			// Set up row object
			ew_ElementsToRow(fobj);

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}
	return true;
}

// Form_CustomValidate event
facademicmissionslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
facademicmissionslist.ValidateRequired = true;
<?php } else { ?>
facademicmissionslist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
facademicmissionslist.Lists["x_College"] = {"LinkField":"x_CollegeID","Ajax":null,"AutoFill":false,"DisplayFields":["x_College_Name_EN","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
facademicmissionslist.Lists["x_Department"] = {"LinkField":"x_DID","Ajax":null,"AutoFill":false,"DisplayFields":["x_DepartmentName","","",""],"ParentFields":["x_College"],"FilterFields":["x_CID"],"Options":[]};

// Form object for search
var facademicmissionslistsrch = new ew_Form("facademicmissionslistsrch");

// Validate function for search
facademicmissionslistsrch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	this.PostAutoSuggest();
	var infix = "";
	elm = this.GetElements("x" + infix + "_UniversityID");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($academicmissions->UniversityID->FldErrMsg()) ?>");

	// Set up row object
	ew_ElementsToRow(fobj);

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate event
facademicmissionslistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
facademicmissionslistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
facademicmissionslistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
facademicmissionslistsrch.Lists["x_College"] = {"LinkField":"x_CollegeID","Ajax":null,"AutoFill":false,"DisplayFields":["x_College_Name_EN","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
facademicmissionslistsrch.Lists["x_Department"] = {"LinkField":"x_DID","Ajax":null,"AutoFill":false,"DisplayFields":["x_DepartmentName","","",""],"ParentFields":["x_College"],"FilterFields":["x_CID"],"Options":[]};
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($academicmissions->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($academicmissions_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $academicmissions_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$academicmissions_list->TotalRecs = $academicmissions->SelectRecordCount();
	} else {
		if ($academicmissions_list->Recordset = $academicmissions_list->LoadRecordset())
			$academicmissions_list->TotalRecs = $academicmissions_list->Recordset->RecordCount();
	}
	$academicmissions_list->StartRec = 1;
	if ($academicmissions_list->DisplayRecs <= 0 || ($academicmissions->Export <> "" && $academicmissions->ExportAll)) // Display all records
		$academicmissions_list->DisplayRecs = $academicmissions_list->TotalRecs;
	if (!($academicmissions->Export <> "" && $academicmissions->ExportAll))
		$academicmissions_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$academicmissions_list->Recordset = $academicmissions_list->LoadRecordset($academicmissions_list->StartRec-1, $academicmissions_list->DisplayRecs);
$academicmissions_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($academicmissions->Export == "" && $academicmissions->CurrentAction == "") { ?>
<form name="facademicmissionslistsrch" id="facademicmissionslistsrch" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>">
<div class="accordion ewDisplayTable ewSearchTable" id="facademicmissionslistsrch_SearchGroup">
	<div class="accordion-group">
		<div class="accordion-heading">
<a class="accordion-toggle" data-toggle="collapse" data-parent="#facademicmissionslistsrch_SearchGroup" href="#facademicmissionslistsrch_SearchBody"><?php echo $Language->Phrase("Search") ?></a>
		</div>
		<div id="facademicmissionslistsrch_SearchBody" class="accordion-body collapse in">
			<div class="accordion-inner">
<div id="facademicmissionslistsrch_SearchPanel">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="academicmissions">
<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$academicmissions_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$academicmissions->RowType = EW_ROWTYPE_SEARCH;

// Render row
$academicmissions->ResetAttrs();
$academicmissions_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($academicmissions->Name->Visible) { // Name ?>
	<span id="xsc_Name" class="ewCell">
		<span class="ewSearchCaption"><?php echo $academicmissions->Name->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_Name" id="z_Name" value="LIKE"></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_Name" name="x_Name" id="x_Name" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($academicmissions->Name->PlaceHolder) ?>" value="<?php echo $academicmissions->Name->EditValue ?>"<?php echo $academicmissions->Name->EditAttributes() ?>>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
<?php if ($academicmissions->UniversityID->Visible) { // UniversityID ?>
	<span id="xsc_UniversityID" class="ewCell">
		<span class="ewSearchCaption"><?php echo $academicmissions->UniversityID->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_UniversityID" id="z_UniversityID" value="="></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_UniversityID" name="x_UniversityID" id="x_UniversityID" size="30" placeholder="<?php echo ew_HtmlEncode($academicmissions->UniversityID->PlaceHolder) ?>" value="<?php echo $academicmissions->UniversityID->EditValue ?>"<?php echo $academicmissions->UniversityID->EditAttributes() ?>>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_3" class="ewRow">
<?php if ($academicmissions->College->Visible) { // College ?>
	<span id="xsc_College" class="ewCell">
		<span class="ewSearchCaption"><?php echo $academicmissions->College->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_College" id="z_College" value="LIKE"></span>
		<span class="control-group ewSearchField">
<?php $academicmissions->College->EditAttrs["onchange"] = "ew_UpdateOpt.call(this, ['x_Department']); " . @$academicmissions->College->EditAttrs["onchange"]; ?>
<select data-field="x_College" id="x_College" name="x_College"<?php echo $academicmissions->College->EditAttributes() ?>>
<?php
if (is_array($academicmissions->College->EditValue)) {
	$arwrk = $academicmissions->College->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($academicmissions->College->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
facademicmissionslistsrch.Lists["x_College"].Options = <?php echo (is_array($academicmissions->College->EditValue)) ? ew_ArrayToJson($academicmissions->College->EditValue, 1) : "[]" ?>;
</script>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_4" class="ewRow">
<?php if ($academicmissions->Department->Visible) { // Department ?>
	<span id="xsc_Department" class="ewCell">
		<span class="ewSearchCaption"><?php echo $academicmissions->Department->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_Department" id="z_Department" value="LIKE"></span>
		<span class="control-group ewSearchField">
<select data-field="x_Department" id="x_Department" name="x_Department"<?php echo $academicmissions->Department->EditAttributes() ?>>
<?php
if (is_array($academicmissions->Department->EditValue)) {
	$arwrk = $academicmissions->Department->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($academicmissions->Department->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
facademicmissionslistsrch.Lists["x_Department"].Options = <?php echo (is_array($academicmissions->Department->EditValue)) ? ew_ArrayToJson($academicmissions->Department->EditValue, 1) : "[]" ?>;
</script>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_5" class="ewRow">
	<div class="btn-group ewButtonGroup">
	<div class="input-append">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="input-large" value="<?php echo ew_HtmlEncode($academicmissions_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	</div>
	<div class="btn-group ewButtonGroup">
	<a class="btn ewShowAll" href="<?php echo $academicmissions_list->PageUrl() ?>cmd=reset"><?php echo $Language->Phrase("ShowAll") ?></a>
	</div>
</div>
<div id="xsr_6" class="ewRow">
	<label class="inline radio ewRadio" style="white-space: nowrap;"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="="<?php if ($academicmissions_list->BasicSearch->getType() == "=") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("ExactPhrase") ?></label>
	<label class="inline radio ewRadio" style="white-space: nowrap;"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="AND"<?php if ($academicmissions_list->BasicSearch->getType() == "AND") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("AllWord") ?></label>
	<label class="inline radio ewRadio" style="white-space: nowrap;"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="OR"<?php if ($academicmissions_list->BasicSearch->getType() == "OR") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("AnyWord") ?></label>
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
<?php $academicmissions_list->ShowPageHeader(); ?>
<?php
$academicmissions_list->ShowMessage();
?>
<table class="ewGrid"><tr><td class="ewGridContent">
<?php if ($academicmissions->Export == "") { ?>
<div class="ewGridUpperPanel">
<?php if ($academicmissions->CurrentAction <> "gridadd" && $academicmissions->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager">
<tr><td>
<?php if (!isset($academicmissions_list->Pager)) $academicmissions_list->Pager = new cPrevNextPager($academicmissions_list->StartRec, $academicmissions_list->DisplayRecs, $academicmissions_list->TotalRecs) ?>
<?php if ($academicmissions_list->Pager->RecordCount > 0) { ?>
<table class="ewStdTable"><tbody><tr><td>
	<?php echo $Language->Phrase("Page") ?>&nbsp;
<div class="input-prepend input-append">
<!--first page button-->
	<?php if ($academicmissions_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $academicmissions_list->PageUrl() ?>start=<?php echo $academicmissions_list->Pager->FirstButton->Start ?>"><i class="icon-step-backward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-step-backward"></i></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($academicmissions_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $academicmissions_list->PageUrl() ?>start=<?php echo $academicmissions_list->Pager->PrevButton->Start ?>"><i class="icon-prev"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-prev"></i></a>
	<?php } ?>
<!--current page number-->
	<input class="input-mini" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $academicmissions_list->Pager->CurrentPage ?>">
<!--next page button-->
	<?php if ($academicmissions_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $academicmissions_list->PageUrl() ?>start=<?php echo $academicmissions_list->Pager->NextButton->Start ?>"><i class="icon-play"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-play"></i></a>
	<?php } ?>
<!--last page button-->
	<?php if ($academicmissions_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $academicmissions_list->PageUrl() ?>start=<?php echo $academicmissions_list->Pager->LastButton->Start ?>"><i class="icon-step-forward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-step-forward"></i></a>
	<?php } ?>
</div>
	&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $academicmissions_list->Pager->PageCount ?>
</td>
<td>
	&nbsp;&nbsp;&nbsp;&nbsp;
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $academicmissions_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $academicmissions_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $academicmissions_list->Pager->RecordCount ?>
</td>
</tr></tbody></table>
<?php } else { ?>
	<?php if ($Security->CanList()) { ?>
	<?php if ($academicmissions_list->SearchWhere == "0=101") { ?>
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
	foreach ($academicmissions_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
</div>
<?php } ?>
<form name="facademicmissionslist" id="facademicmissionslist" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="academicmissions">
<div id="gmp_academicmissions" class="ewGridMiddlePanel">
<?php if ($academicmissions_list->TotalRecs > 0) { ?>
<table id="tbl_academicmissionslist" class="ewTable ewTableSeparate">
<?php echo $academicmissions->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$academicmissions_list->RenderListOptions();

// Render list options (header, left)
$academicmissions_list->ListOptions->Render("header", "left");
?>
<?php if ($academicmissions->ID->Visible) { // ID ?>
	<?php if ($academicmissions->SortUrl($academicmissions->ID) == "") { ?>
		<td><div id="elh_academicmissions_ID" class="academicmissions_ID"><div class="ewTableHeaderCaption"><?php echo $academicmissions->ID->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $academicmissions->SortUrl($academicmissions->ID) ?>',1);"><div id="elh_academicmissions_ID" class="academicmissions_ID">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $academicmissions->ID->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($academicmissions->ID->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($academicmissions->ID->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($academicmissions->Name->Visible) { // Name ?>
	<?php if ($academicmissions->SortUrl($academicmissions->Name) == "") { ?>
		<td><div id="elh_academicmissions_Name" class="academicmissions_Name"><div class="ewTableHeaderCaption"><?php echo $academicmissions->Name->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $academicmissions->SortUrl($academicmissions->Name) ?>',1);"><div id="elh_academicmissions_Name" class="academicmissions_Name">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $academicmissions->Name->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($academicmissions->Name->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($academicmissions->Name->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($academicmissions->UniversityID->Visible) { // UniversityID ?>
	<?php if ($academicmissions->SortUrl($academicmissions->UniversityID) == "") { ?>
		<td><div id="elh_academicmissions_UniversityID" class="academicmissions_UniversityID"><div class="ewTableHeaderCaption"><?php echo $academicmissions->UniversityID->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $academicmissions->SortUrl($academicmissions->UniversityID) ?>',1);"><div id="elh_academicmissions_UniversityID" class="academicmissions_UniversityID">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $academicmissions->UniversityID->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($academicmissions->UniversityID->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($academicmissions->UniversityID->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($academicmissions->College->Visible) { // College ?>
	<?php if ($academicmissions->SortUrl($academicmissions->College) == "") { ?>
		<td><div id="elh_academicmissions_College" class="academicmissions_College"><div class="ewTableHeaderCaption"><?php echo $academicmissions->College->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $academicmissions->SortUrl($academicmissions->College) ?>',1);"><div id="elh_academicmissions_College" class="academicmissions_College">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $academicmissions->College->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($academicmissions->College->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($academicmissions->College->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($academicmissions->Department->Visible) { // Department ?>
	<?php if ($academicmissions->SortUrl($academicmissions->Department) == "") { ?>
		<td><div id="elh_academicmissions_Department" class="academicmissions_Department"><div class="ewTableHeaderCaption"><?php echo $academicmissions->Department->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $academicmissions->SortUrl($academicmissions->Department) ?>',1);"><div id="elh_academicmissions_Department" class="academicmissions_Department">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $academicmissions->Department->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($academicmissions->Department->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($academicmissions->Department->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($academicmissions->StartDate->Visible) { // StartDate ?>
	<?php if ($academicmissions->SortUrl($academicmissions->StartDate) == "") { ?>
		<td><div id="elh_academicmissions_StartDate" class="academicmissions_StartDate"><div class="ewTableHeaderCaption"><?php echo $academicmissions->StartDate->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $academicmissions->SortUrl($academicmissions->StartDate) ?>',1);"><div id="elh_academicmissions_StartDate" class="academicmissions_StartDate">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $academicmissions->StartDate->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($academicmissions->StartDate->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($academicmissions->StartDate->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($academicmissions->EndDate->Visible) { // EndDate ?>
	<?php if ($academicmissions->SortUrl($academicmissions->EndDate) == "") { ?>
		<td><div id="elh_academicmissions_EndDate" class="academicmissions_EndDate"><div class="ewTableHeaderCaption"><?php echo $academicmissions->EndDate->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $academicmissions->SortUrl($academicmissions->EndDate) ?>',1);"><div id="elh_academicmissions_EndDate" class="academicmissions_EndDate">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $academicmissions->EndDate->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($academicmissions->EndDate->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($academicmissions->EndDate->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($academicmissions->PlaceVisited->Visible) { // PlaceVisited ?>
	<?php if ($academicmissions->SortUrl($academicmissions->PlaceVisited) == "") { ?>
		<td><div id="elh_academicmissions_PlaceVisited" class="academicmissions_PlaceVisited"><div class="ewTableHeaderCaption"><?php echo $academicmissions->PlaceVisited->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $academicmissions->SortUrl($academicmissions->PlaceVisited) ?>',1);"><div id="elh_academicmissions_PlaceVisited" class="academicmissions_PlaceVisited">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $academicmissions->PlaceVisited->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($academicmissions->PlaceVisited->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($academicmissions->PlaceVisited->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($academicmissions->NatureOfVisit->Visible) { // NatureOfVisit ?>
	<?php if ($academicmissions->SortUrl($academicmissions->NatureOfVisit) == "") { ?>
		<td><div id="elh_academicmissions_NatureOfVisit" class="academicmissions_NatureOfVisit"><div class="ewTableHeaderCaption"><?php echo $academicmissions->NatureOfVisit->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $academicmissions->SortUrl($academicmissions->NatureOfVisit) ?>',1);"><div id="elh_academicmissions_NatureOfVisit" class="academicmissions_NatureOfVisit">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $academicmissions->NatureOfVisit->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($academicmissions->NatureOfVisit->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($academicmissions->NatureOfVisit->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$academicmissions_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($academicmissions->ExportAll && $academicmissions->Export <> "") {
	$academicmissions_list->StopRec = $academicmissions_list->TotalRecs;
} else {

	// Set the last record to display
	if ($academicmissions_list->TotalRecs > $academicmissions_list->StartRec + $academicmissions_list->DisplayRecs - 1)
		$academicmissions_list->StopRec = $academicmissions_list->StartRec + $academicmissions_list->DisplayRecs - 1;
	else
		$academicmissions_list->StopRec = $academicmissions_list->TotalRecs;
}

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($academicmissions_list->FormKeyCountName) && ($academicmissions->CurrentAction == "gridadd" || $academicmissions->CurrentAction == "gridedit" || $academicmissions->CurrentAction == "F")) {
		$academicmissions_list->KeyCount = $objForm->GetValue($academicmissions_list->FormKeyCountName);
		$academicmissions_list->StopRec = $academicmissions_list->StartRec + $academicmissions_list->KeyCount - 1;
	}
}
$academicmissions_list->RecCnt = $academicmissions_list->StartRec - 1;
if ($academicmissions_list->Recordset && !$academicmissions_list->Recordset->EOF) {
	$academicmissions_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $academicmissions_list->StartRec > 1)
		$academicmissions_list->Recordset->Move($academicmissions_list->StartRec - 1);
} elseif (!$academicmissions->AllowAddDeleteRow && $academicmissions_list->StopRec == 0) {
	$academicmissions_list->StopRec = $academicmissions->GridAddRowCount;
}

// Initialize aggregate
$academicmissions->RowType = EW_ROWTYPE_AGGREGATEINIT;
$academicmissions->ResetAttrs();
$academicmissions_list->RenderRow();
$academicmissions_list->EditRowCnt = 0;
if ($academicmissions->CurrentAction == "edit")
	$academicmissions_list->RowIndex = 1;
while ($academicmissions_list->RecCnt < $academicmissions_list->StopRec) {
	$academicmissions_list->RecCnt++;
	if (intval($academicmissions_list->RecCnt) >= intval($academicmissions_list->StartRec)) {
		$academicmissions_list->RowCnt++;

		// Set up key count
		$academicmissions_list->KeyCount = $academicmissions_list->RowIndex;

		// Init row class and style
		$academicmissions->ResetAttrs();
		$academicmissions->CssClass = "";
		if ($academicmissions->CurrentAction == "gridadd") {
			$academicmissions_list->LoadDefaultValues(); // Load default values
		} else {
			$academicmissions_list->LoadRowValues($academicmissions_list->Recordset); // Load row values
		}
		$academicmissions->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($academicmissions->CurrentAction == "edit") {
			if ($academicmissions_list->CheckInlineEditKey() && $academicmissions_list->EditRowCnt == 0) { // Inline edit
				$academicmissions->RowType = EW_ROWTYPE_EDIT; // Render edit
			}
		}
		if ($academicmissions->CurrentAction == "edit" && $academicmissions->RowType == EW_ROWTYPE_EDIT && $academicmissions->EventCancelled) { // Update failed
			$objForm->Index = 1;
			$academicmissions_list->RestoreFormValues(); // Restore form values
		}
		if ($academicmissions->RowType == EW_ROWTYPE_EDIT) // Edit row
			$academicmissions_list->EditRowCnt++;

		// Set up row id / data-rowindex
		$academicmissions->RowAttrs = array_merge($academicmissions->RowAttrs, array('data-rowindex'=>$academicmissions_list->RowCnt, 'id'=>'r' . $academicmissions_list->RowCnt . '_academicmissions', 'data-rowtype'=>$academicmissions->RowType));

		// Render row
		$academicmissions_list->RenderRow();

		// Render list options
		$academicmissions_list->RenderListOptions();
?>
	<tr<?php echo $academicmissions->RowAttributes() ?>>
<?php

// Render list options (body, left)
$academicmissions_list->ListOptions->Render("body", "left", $academicmissions_list->RowCnt);
?>
	<?php if ($academicmissions->ID->Visible) { // ID ?>
		<td<?php echo $academicmissions->ID->CellAttributes() ?>>
<?php if ($academicmissions->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $academicmissions_list->RowCnt ?>_academicmissions_ID" class="control-group academicmissions_ID">
<span<?php echo $academicmissions->ID->ViewAttributes() ?>>
<?php echo $academicmissions->ID->EditValue ?></span>
</span>
<input type="hidden" data-field="x_ID" name="x<?php echo $academicmissions_list->RowIndex ?>_ID" id="x<?php echo $academicmissions_list->RowIndex ?>_ID" value="<?php echo ew_HtmlEncode($academicmissions->ID->CurrentValue) ?>">
<?php } ?>
<?php if ($academicmissions->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $academicmissions->ID->ViewAttributes() ?>>
<?php echo $academicmissions->ID->ListViewValue() ?></span>
<?php } ?>
<a id="<?php echo $academicmissions_list->PageObjName . "_row_" . $academicmissions_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($academicmissions->Name->Visible) { // Name ?>
		<td<?php echo $academicmissions->Name->CellAttributes() ?>>
<?php if ($academicmissions->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $academicmissions_list->RowCnt ?>_academicmissions_Name" class="control-group academicmissions_Name">
<input type="text" data-field="x_Name" name="x<?php echo $academicmissions_list->RowIndex ?>_Name" id="x<?php echo $academicmissions_list->RowIndex ?>_Name" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($academicmissions->Name->PlaceHolder) ?>" value="<?php echo $academicmissions->Name->EditValue ?>"<?php echo $academicmissions->Name->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($academicmissions->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $academicmissions->Name->ViewAttributes() ?>>
<?php echo $academicmissions->Name->ListViewValue() ?></span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($academicmissions->UniversityID->Visible) { // UniversityID ?>
		<td<?php echo $academicmissions->UniversityID->CellAttributes() ?>>
<?php if ($academicmissions->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $academicmissions_list->RowCnt ?>_academicmissions_UniversityID" class="control-group academicmissions_UniversityID">
<input type="text" data-field="x_UniversityID" name="x<?php echo $academicmissions_list->RowIndex ?>_UniversityID" id="x<?php echo $academicmissions_list->RowIndex ?>_UniversityID" size="30" placeholder="<?php echo ew_HtmlEncode($academicmissions->UniversityID->PlaceHolder) ?>" value="<?php echo $academicmissions->UniversityID->EditValue ?>"<?php echo $academicmissions->UniversityID->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($academicmissions->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $academicmissions->UniversityID->ViewAttributes() ?>>
<?php echo $academicmissions->UniversityID->ListViewValue() ?></span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($academicmissions->College->Visible) { // College ?>
		<td<?php echo $academicmissions->College->CellAttributes() ?>>
<?php if ($academicmissions->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $academicmissions_list->RowCnt ?>_academicmissions_College" class="control-group academicmissions_College">
<?php $academicmissions->College->EditAttrs["onchange"] = "ew_UpdateOpt.call(this, ['x" . $academicmissions_list->RowIndex . "_Department']); " . @$academicmissions->College->EditAttrs["onchange"]; ?>
<select data-field="x_College" id="x<?php echo $academicmissions_list->RowIndex ?>_College" name="x<?php echo $academicmissions_list->RowIndex ?>_College"<?php echo $academicmissions->College->EditAttributes() ?>>
<?php
if (is_array($academicmissions->College->EditValue)) {
	$arwrk = $academicmissions->College->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($academicmissions->College->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
facademicmissionslist.Lists["x_College"].Options = <?php echo (is_array($academicmissions->College->EditValue)) ? ew_ArrayToJson($academicmissions->College->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php } ?>
<?php if ($academicmissions->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $academicmissions->College->ViewAttributes() ?>>
<?php echo $academicmissions->College->ListViewValue() ?></span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($academicmissions->Department->Visible) { // Department ?>
		<td<?php echo $academicmissions->Department->CellAttributes() ?>>
<?php if ($academicmissions->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $academicmissions_list->RowCnt ?>_academicmissions_Department" class="control-group academicmissions_Department">
<select data-field="x_Department" id="x<?php echo $academicmissions_list->RowIndex ?>_Department" name="x<?php echo $academicmissions_list->RowIndex ?>_Department"<?php echo $academicmissions->Department->EditAttributes() ?>>
<?php
if (is_array($academicmissions->Department->EditValue)) {
	$arwrk = $academicmissions->Department->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($academicmissions->Department->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
facademicmissionslist.Lists["x_Department"].Options = <?php echo (is_array($academicmissions->Department->EditValue)) ? ew_ArrayToJson($academicmissions->Department->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php } ?>
<?php if ($academicmissions->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $academicmissions->Department->ViewAttributes() ?>>
<?php echo $academicmissions->Department->ListViewValue() ?></span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($academicmissions->StartDate->Visible) { // StartDate ?>
		<td<?php echo $academicmissions->StartDate->CellAttributes() ?>>
<?php if ($academicmissions->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $academicmissions_list->RowCnt ?>_academicmissions_StartDate" class="control-group academicmissions_StartDate">
<input type="text" data-field="x_StartDate" name="x<?php echo $academicmissions_list->RowIndex ?>_StartDate" id="x<?php echo $academicmissions_list->RowIndex ?>_StartDate" placeholder="<?php echo ew_HtmlEncode($academicmissions->StartDate->PlaceHolder) ?>" value="<?php echo $academicmissions->StartDate->EditValue ?>"<?php echo $academicmissions->StartDate->EditAttributes() ?>>
<?php if (!$academicmissions->StartDate->ReadOnly && !$academicmissions->StartDate->Disabled && @$academicmissions->StartDate->EditAttrs["readonly"] == "" && @$academicmissions->StartDate->EditAttrs["disabled"] == "") { ?>
<button id="cal_x<?php echo $academicmissions_list->RowIndex ?>_StartDate" name="cal_x<?php echo $academicmissions_list->RowIndex ?>_StartDate" class="btn" type="button"><img src="phpimages/calendar.png" alt="<?php echo $Language->Phrase("PickDate") ?>" title="<?php echo $Language->Phrase("PickDate") ?>" style="border: 0;"></button><script type="text/javascript">
ew_CreateCalendar("facademicmissionslist", "x<?php echo $academicmissions_list->RowIndex ?>_StartDate", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($academicmissions->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $academicmissions->StartDate->ViewAttributes() ?>>
<?php echo $academicmissions->StartDate->ListViewValue() ?></span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($academicmissions->EndDate->Visible) { // EndDate ?>
		<td<?php echo $academicmissions->EndDate->CellAttributes() ?>>
<?php if ($academicmissions->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $academicmissions_list->RowCnt ?>_academicmissions_EndDate" class="control-group academicmissions_EndDate">
<input type="text" data-field="x_EndDate" name="x<?php echo $academicmissions_list->RowIndex ?>_EndDate" id="x<?php echo $academicmissions_list->RowIndex ?>_EndDate" placeholder="<?php echo ew_HtmlEncode($academicmissions->EndDate->PlaceHolder) ?>" value="<?php echo $academicmissions->EndDate->EditValue ?>"<?php echo $academicmissions->EndDate->EditAttributes() ?>>
<?php if (!$academicmissions->EndDate->ReadOnly && !$academicmissions->EndDate->Disabled && @$academicmissions->EndDate->EditAttrs["readonly"] == "" && @$academicmissions->EndDate->EditAttrs["disabled"] == "") { ?>
<button id="cal_x<?php echo $academicmissions_list->RowIndex ?>_EndDate" name="cal_x<?php echo $academicmissions_list->RowIndex ?>_EndDate" class="btn" type="button"><img src="phpimages/calendar.png" alt="<?php echo $Language->Phrase("PickDate") ?>" title="<?php echo $Language->Phrase("PickDate") ?>" style="border: 0;"></button><script type="text/javascript">
ew_CreateCalendar("facademicmissionslist", "x<?php echo $academicmissions_list->RowIndex ?>_EndDate", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($academicmissions->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $academicmissions->EndDate->ViewAttributes() ?>>
<?php echo $academicmissions->EndDate->ListViewValue() ?></span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($academicmissions->PlaceVisited->Visible) { // PlaceVisited ?>
		<td<?php echo $academicmissions->PlaceVisited->CellAttributes() ?>>
<?php if ($academicmissions->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $academicmissions_list->RowCnt ?>_academicmissions_PlaceVisited" class="control-group academicmissions_PlaceVisited">
<input type="text" data-field="x_PlaceVisited" name="x<?php echo $academicmissions_list->RowIndex ?>_PlaceVisited" id="x<?php echo $academicmissions_list->RowIndex ?>_PlaceVisited" size="30" maxlength="200" placeholder="<?php echo ew_HtmlEncode($academicmissions->PlaceVisited->PlaceHolder) ?>" value="<?php echo $academicmissions->PlaceVisited->EditValue ?>"<?php echo $academicmissions->PlaceVisited->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($academicmissions->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $academicmissions->PlaceVisited->ViewAttributes() ?>>
<?php echo $academicmissions->PlaceVisited->ListViewValue() ?></span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($academicmissions->NatureOfVisit->Visible) { // NatureOfVisit ?>
		<td<?php echo $academicmissions->NatureOfVisit->CellAttributes() ?>>
<?php if ($academicmissions->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $academicmissions_list->RowCnt ?>_academicmissions_NatureOfVisit" class="control-group academicmissions_NatureOfVisit">
<input type="text" data-field="x_NatureOfVisit" name="x<?php echo $academicmissions_list->RowIndex ?>_NatureOfVisit" id="x<?php echo $academicmissions_list->RowIndex ?>_NatureOfVisit" size="30" maxlength="200" placeholder="<?php echo ew_HtmlEncode($academicmissions->NatureOfVisit->PlaceHolder) ?>" value="<?php echo $academicmissions->NatureOfVisit->EditValue ?>"<?php echo $academicmissions->NatureOfVisit->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($academicmissions->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $academicmissions->NatureOfVisit->ViewAttributes() ?>>
<?php echo $academicmissions->NatureOfVisit->ListViewValue() ?></span>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$academicmissions_list->ListOptions->Render("body", "right", $academicmissions_list->RowCnt);
?>
	</tr>
<?php if ($academicmissions->RowType == EW_ROWTYPE_ADD || $academicmissions->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
facademicmissionslist.UpdateOpts(<?php echo $academicmissions_list->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	if ($academicmissions->CurrentAction <> "gridadd")
		$academicmissions_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($academicmissions->CurrentAction == "edit") { ?>
<input type="hidden" name="<?php echo $academicmissions_list->FormKeyCountName ?>" id="<?php echo $academicmissions_list->FormKeyCountName ?>" value="<?php echo $academicmissions_list->KeyCount ?>">
<?php } ?>
<?php if ($academicmissions->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($academicmissions_list->Recordset)
	$academicmissions_list->Recordset->Close();
?>
<?php if ($academicmissions_list->TotalRecs > 0) { ?>
<?php if ($academicmissions->Export == "") { ?>
<div class="ewGridLowerPanel">
<?php if ($academicmissions->CurrentAction <> "gridadd" && $academicmissions->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager">
<tr><td>
<?php if (!isset($academicmissions_list->Pager)) $academicmissions_list->Pager = new cPrevNextPager($academicmissions_list->StartRec, $academicmissions_list->DisplayRecs, $academicmissions_list->TotalRecs) ?>
<?php if ($academicmissions_list->Pager->RecordCount > 0) { ?>
<table class="ewStdTable"><tbody><tr><td>
	<?php echo $Language->Phrase("Page") ?>&nbsp;
<div class="input-prepend input-append">
<!--first page button-->
	<?php if ($academicmissions_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $academicmissions_list->PageUrl() ?>start=<?php echo $academicmissions_list->Pager->FirstButton->Start ?>"><i class="icon-step-backward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-step-backward"></i></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($academicmissions_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $academicmissions_list->PageUrl() ?>start=<?php echo $academicmissions_list->Pager->PrevButton->Start ?>"><i class="icon-prev"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-prev"></i></a>
	<?php } ?>
<!--current page number-->
	<input class="input-mini" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $academicmissions_list->Pager->CurrentPage ?>">
<!--next page button-->
	<?php if ($academicmissions_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $academicmissions_list->PageUrl() ?>start=<?php echo $academicmissions_list->Pager->NextButton->Start ?>"><i class="icon-play"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-play"></i></a>
	<?php } ?>
<!--last page button-->
	<?php if ($academicmissions_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $academicmissions_list->PageUrl() ?>start=<?php echo $academicmissions_list->Pager->LastButton->Start ?>"><i class="icon-step-forward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-step-forward"></i></a>
	<?php } ?>
</div>
	&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $academicmissions_list->Pager->PageCount ?>
</td>
<td>
	&nbsp;&nbsp;&nbsp;&nbsp;
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $academicmissions_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $academicmissions_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $academicmissions_list->Pager->RecordCount ?>
</td>
</tr></tbody></table>
<?php } else { ?>
	<?php if ($Security->CanList()) { ?>
	<?php if ($academicmissions_list->SearchWhere == "0=101") { ?>
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
	foreach ($academicmissions_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
</div>
<?php } ?>
<?php } ?>
</td></tr></table>
<?php if ($academicmissions->Export == "") { ?>
<script type="text/javascript">
facademicmissionslistsrch.Init();
facademicmissionslist.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$academicmissions_list->ShowPageFooter();
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
$academicmissions_list->Page_Terminate();
?>
