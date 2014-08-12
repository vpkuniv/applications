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

$scholarships_list = NULL; // Initialize page object first

class cscholarships_list extends cscholarships {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{F8E6AD82-57C4-4BEE-8975-8D386184467A}";

	// Table name
	var $TableName = 'scholarships';

	// Page object name
	var $PageObjName = 'scholarships_list';

	// Grid form hidden field names
	var $FormName = 'fscholarshipslist';
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

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "scholarshipsadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "scholarshipsdelete.php";
		$this->MultiUpdateUrl = "scholarshipsupdate.php";

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'scholarships', TRUE);

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
		$this->BuildSearchSql($sWhere, $this->English_Name, FALSE); // English Name
		$this->BuildSearchSql($sWhere, $this->Arabic_Name, FALSE); // Arabic Name
		$this->BuildSearchSql($sWhere, $this->College, FALSE); // College
		$this->BuildSearchSql($sWhere, $this->Department, FALSE); // Department
		$this->BuildSearchSql($sWhere, $this->Major, FALSE); // Major
		$this->BuildSearchSql($sWhere, $this->GPA, FALSE); // GPA
		$this->BuildSearchSql($sWhere, $this->Graduated_From, FALSE); // Graduated From
		$this->BuildSearchSql($sWhere, $this->Acceptance_Counrty, FALSE); // Acceptance Counrty
		$this->BuildSearchSql($sWhere, $this->Acceptance_University, FALSE); // Acceptance University
		$this->BuildSearchSql($sWhere, $this->Program_Degree, FALSE); // Program Degree
		$this->BuildSearchSql($sWhere, $this->Notes, FALSE); // Notes
		$this->BuildSearchSql($sWhere, $this->Committee_Date, FALSE); // Committee Date
		$this->BuildSearchSql($sWhere, $this->Status, FALSE); // Status
		$this->BuildSearchSql($sWhere, $this->Justification, FALSE); // Justification
		$this->BuildSearchSql($sWhere, $this->LastModifiedUser, FALSE); // LastModifiedUser
		$this->BuildSearchSql($sWhere, $this->LastModifiedTime, FALSE); // LastModifiedTime
		$this->BuildSearchSql($sWhere, $this->LastModifiedIP, FALSE); // LastModifiedIP

		// Set up search parm
		if ($sWhere <> "") {
			$this->Command = "search";
		}
		if ($this->Command == "search") {
			$this->ID->AdvancedSearch->Save(); // ID
			$this->English_Name->AdvancedSearch->Save(); // English Name
			$this->Arabic_Name->AdvancedSearch->Save(); // Arabic Name
			$this->College->AdvancedSearch->Save(); // College
			$this->Department->AdvancedSearch->Save(); // Department
			$this->Major->AdvancedSearch->Save(); // Major
			$this->GPA->AdvancedSearch->Save(); // GPA
			$this->Graduated_From->AdvancedSearch->Save(); // Graduated From
			$this->Acceptance_Counrty->AdvancedSearch->Save(); // Acceptance Counrty
			$this->Acceptance_University->AdvancedSearch->Save(); // Acceptance University
			$this->Program_Degree->AdvancedSearch->Save(); // Program Degree
			$this->Notes->AdvancedSearch->Save(); // Notes
			$this->Committee_Date->AdvancedSearch->Save(); // Committee Date
			$this->Status->AdvancedSearch->Save(); // Status
			$this->Justification->AdvancedSearch->Save(); // Justification
			$this->LastModifiedUser->AdvancedSearch->Save(); // LastModifiedUser
			$this->LastModifiedTime->AdvancedSearch->Save(); // LastModifiedTime
			$this->LastModifiedIP->AdvancedSearch->Save(); // LastModifiedIP
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
		if ($this->English_Name->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Arabic_Name->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->College->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Department->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Major->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->GPA->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Graduated_From->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Acceptance_Counrty->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Acceptance_University->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Program_Degree->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Notes->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Committee_Date->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Status->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Justification->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->LastModifiedUser->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->LastModifiedTime->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->LastModifiedIP->AdvancedSearch->IssetSession())
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
		$this->English_Name->AdvancedSearch->UnsetSession();
		$this->Arabic_Name->AdvancedSearch->UnsetSession();
		$this->College->AdvancedSearch->UnsetSession();
		$this->Department->AdvancedSearch->UnsetSession();
		$this->Major->AdvancedSearch->UnsetSession();
		$this->GPA->AdvancedSearch->UnsetSession();
		$this->Graduated_From->AdvancedSearch->UnsetSession();
		$this->Acceptance_Counrty->AdvancedSearch->UnsetSession();
		$this->Acceptance_University->AdvancedSearch->UnsetSession();
		$this->Program_Degree->AdvancedSearch->UnsetSession();
		$this->Notes->AdvancedSearch->UnsetSession();
		$this->Committee_Date->AdvancedSearch->UnsetSession();
		$this->Status->AdvancedSearch->UnsetSession();
		$this->Justification->AdvancedSearch->UnsetSession();
		$this->LastModifiedUser->AdvancedSearch->UnsetSession();
		$this->LastModifiedTime->AdvancedSearch->UnsetSession();
		$this->LastModifiedIP->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore advanced search values
		$this->ID->AdvancedSearch->Load();
		$this->English_Name->AdvancedSearch->Load();
		$this->Arabic_Name->AdvancedSearch->Load();
		$this->College->AdvancedSearch->Load();
		$this->Department->AdvancedSearch->Load();
		$this->Major->AdvancedSearch->Load();
		$this->GPA->AdvancedSearch->Load();
		$this->Graduated_From->AdvancedSearch->Load();
		$this->Acceptance_Counrty->AdvancedSearch->Load();
		$this->Acceptance_University->AdvancedSearch->Load();
		$this->Program_Degree->AdvancedSearch->Load();
		$this->Notes->AdvancedSearch->Load();
		$this->Committee_Date->AdvancedSearch->Load();
		$this->Status->AdvancedSearch->Load();
		$this->Justification->AdvancedSearch->Load();
		$this->LastModifiedUser->AdvancedSearch->Load();
		$this->LastModifiedTime->AdvancedSearch->Load();
		$this->LastModifiedIP->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->English_Name); // English Name
			$this->UpdateSort($this->Arabic_Name); // Arabic Name
			$this->UpdateSort($this->College); // College
			$this->UpdateSort($this->Department); // Department
			$this->UpdateSort($this->Major); // Major
			$this->UpdateSort($this->Acceptance_University); // Acceptance University
			$this->UpdateSort($this->Program_Degree); // Program Degree
			$this->UpdateSort($this->Committee_Date); // Committee Date
			$this->UpdateSort($this->Status); // Status
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
				$this->College->setSort("ASC");
				$this->Department->setSort("ASC");
				$this->Status->setSort("ASC");
				$this->Arabic_Name->setSort("ASC");
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
				$this->English_Name->setSort("");
				$this->Arabic_Name->setSort("");
				$this->College->setSort("");
				$this->Department->setSort("");
				$this->Major->setSort("");
				$this->Acceptance_University->setSort("");
				$this->Program_Degree->setSort("");
				$this->Committee_Date->setSort("");
				$this->Status->setSort("");
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
		$item->Body = "<a class=\"ewAction ewMultiDelete\" href=\"\" onclick=\"ew_SubmitSelected(document.fscholarshipslist, '" . $this->MultiDeleteUrl . "');return false;\">" . $Language->Phrase("DeleteSelectedLink") . "</a>";
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.fscholarshipslist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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

	//  Load search values for validation
	function LoadSearchValues() {
		global $objForm;

		// Load search values
		// ID

		$this->ID->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ID"]);
		if ($this->ID->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ID->AdvancedSearch->SearchOperator = @$_GET["z_ID"];

		// English Name
		$this->English_Name->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_English_Name"]);
		if ($this->English_Name->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->English_Name->AdvancedSearch->SearchOperator = @$_GET["z_English_Name"];

		// Arabic Name
		$this->Arabic_Name->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Arabic_Name"]);
		if ($this->Arabic_Name->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Arabic_Name->AdvancedSearch->SearchOperator = @$_GET["z_Arabic_Name"];

		// College
		$this->College->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_College"]);
		if ($this->College->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->College->AdvancedSearch->SearchOperator = @$_GET["z_College"];

		// Department
		$this->Department->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Department"]);
		if ($this->Department->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Department->AdvancedSearch->SearchOperator = @$_GET["z_Department"];

		// Major
		$this->Major->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Major"]);
		if ($this->Major->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Major->AdvancedSearch->SearchOperator = @$_GET["z_Major"];

		// GPA
		$this->GPA->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_GPA"]);
		if ($this->GPA->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->GPA->AdvancedSearch->SearchOperator = @$_GET["z_GPA"];

		// Graduated From
		$this->Graduated_From->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Graduated_From"]);
		if ($this->Graduated_From->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Graduated_From->AdvancedSearch->SearchOperator = @$_GET["z_Graduated_From"];

		// Acceptance Counrty
		$this->Acceptance_Counrty->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Acceptance_Counrty"]);
		if ($this->Acceptance_Counrty->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Acceptance_Counrty->AdvancedSearch->SearchOperator = @$_GET["z_Acceptance_Counrty"];

		// Acceptance University
		$this->Acceptance_University->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Acceptance_University"]);
		if ($this->Acceptance_University->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Acceptance_University->AdvancedSearch->SearchOperator = @$_GET["z_Acceptance_University"];

		// Program Degree
		$this->Program_Degree->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Program_Degree"]);
		if ($this->Program_Degree->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Program_Degree->AdvancedSearch->SearchOperator = @$_GET["z_Program_Degree"];

		// Notes
		$this->Notes->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Notes"]);
		if ($this->Notes->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Notes->AdvancedSearch->SearchOperator = @$_GET["z_Notes"];

		// Committee Date
		$this->Committee_Date->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Committee_Date"]);
		if ($this->Committee_Date->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Committee_Date->AdvancedSearch->SearchOperator = @$_GET["z_Committee_Date"];

		// Status
		$this->Status->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Status"]);
		if ($this->Status->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Status->AdvancedSearch->SearchOperator = @$_GET["z_Status"];

		// Justification
		$this->Justification->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Justification"]);
		if ($this->Justification->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Justification->AdvancedSearch->SearchOperator = @$_GET["z_Justification"];

		// LastModifiedUser
		$this->LastModifiedUser->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_LastModifiedUser"]);
		if ($this->LastModifiedUser->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->LastModifiedUser->AdvancedSearch->SearchOperator = @$_GET["z_LastModifiedUser"];

		// LastModifiedTime
		$this->LastModifiedTime->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_LastModifiedTime"]);
		if ($this->LastModifiedTime->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->LastModifiedTime->AdvancedSearch->SearchOperator = @$_GET["z_LastModifiedTime"];

		// LastModifiedIP
		$this->LastModifiedIP->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_LastModifiedIP"]);
		if ($this->LastModifiedIP->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->LastModifiedIP->AdvancedSearch->SearchOperator = @$_GET["z_LastModifiedIP"];
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

			// Status
			$this->Status->LinkCustomAttributes = "";
			$this->Status->HrefValue = "";
			$this->Status->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// English Name
			$this->English_Name->EditCustomAttributes = "";
			$this->English_Name->EditValue = ew_HtmlEncode($this->English_Name->AdvancedSearch->SearchValue);
			$this->English_Name->PlaceHolder = ew_RemoveHtml($this->English_Name->FldCaption());

			// Arabic Name
			$this->Arabic_Name->EditCustomAttributes = "";
			$this->Arabic_Name->EditValue = ew_HtmlEncode($this->Arabic_Name->AdvancedSearch->SearchValue);
			$this->Arabic_Name->PlaceHolder = ew_RemoveHtml($this->Arabic_Name->FldCaption());

			// College
			$this->College->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT `CollegeID`, `College Name AR` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `colleges`";
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
			$sSqlWrk = "SELECT `DID`, `DepartmentName-AR` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, `CID` AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `departments`";
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

			// Major
			$this->Major->EditCustomAttributes = "";
			$this->Major->EditValue = ew_HtmlEncode($this->Major->AdvancedSearch->SearchValue);
			$this->Major->PlaceHolder = ew_RemoveHtml($this->Major->FldCaption());

			// Acceptance University
			$this->Acceptance_University->EditCustomAttributes = "";
			$this->Acceptance_University->EditValue = $this->Acceptance_University->AdvancedSearch->SearchValue;
			$this->Acceptance_University->PlaceHolder = ew_RemoveHtml($this->Acceptance_University->FldCaption());

			// Program Degree
			$this->Program_Degree->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->Program_Degree->FldTagValue(1), $this->Program_Degree->FldTagCaption(1) <> "" ? $this->Program_Degree->FldTagCaption(1) : $this->Program_Degree->FldTagValue(1));
			$arwrk[] = array($this->Program_Degree->FldTagValue(2), $this->Program_Degree->FldTagCaption(2) <> "" ? $this->Program_Degree->FldTagCaption(2) : $this->Program_Degree->FldTagValue(2));
			$arwrk[] = array($this->Program_Degree->FldTagValue(3), $this->Program_Degree->FldTagCaption(3) <> "" ? $this->Program_Degree->FldTagCaption(3) : $this->Program_Degree->FldTagValue(3));
			$arwrk[] = array($this->Program_Degree->FldTagValue(4), $this->Program_Degree->FldTagCaption(4) <> "" ? $this->Program_Degree->FldTagCaption(4) : $this->Program_Degree->FldTagValue(4));
			$this->Program_Degree->EditValue = $arwrk;

			// Committee Date
			$this->Committee_Date->EditCustomAttributes = "";
			$this->Committee_Date->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->Committee_Date->AdvancedSearch->SearchValue, 7), 7));
			$this->Committee_Date->PlaceHolder = ew_RemoveHtml($this->Committee_Date->FldCaption());

			// Status
			$this->Status->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->Status->FldTagValue(1), $this->Status->FldTagCaption(1) <> "" ? $this->Status->FldTagCaption(1) : $this->Status->FldTagValue(1));
			$arwrk[] = array($this->Status->FldTagValue(2), $this->Status->FldTagCaption(2) <> "" ? $this->Status->FldTagCaption(2) : $this->Status->FldTagValue(2));
			$arwrk[] = array($this->Status->FldTagValue(3), $this->Status->FldTagCaption(3) <> "" ? $this->Status->FldTagCaption(3) : $this->Status->FldTagValue(3));
			$this->Status->EditValue = $arwrk;
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
		if (!ew_CheckEuroDate($this->Committee_Date->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->Committee_Date->FldErrMsg());
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

	// Load advanced search
	function LoadAdvancedSearch() {
		$this->ID->AdvancedSearch->Load();
		$this->English_Name->AdvancedSearch->Load();
		$this->Arabic_Name->AdvancedSearch->Load();
		$this->College->AdvancedSearch->Load();
		$this->Department->AdvancedSearch->Load();
		$this->Major->AdvancedSearch->Load();
		$this->GPA->AdvancedSearch->Load();
		$this->Graduated_From->AdvancedSearch->Load();
		$this->Acceptance_Counrty->AdvancedSearch->Load();
		$this->Acceptance_University->AdvancedSearch->Load();
		$this->Program_Degree->AdvancedSearch->Load();
		$this->Notes->AdvancedSearch->Load();
		$this->Committee_Date->AdvancedSearch->Load();
		$this->Status->AdvancedSearch->Load();
		$this->Justification->AdvancedSearch->Load();
		$this->LastModifiedUser->AdvancedSearch->Load();
		$this->LastModifiedTime->AdvancedSearch->Load();
		$this->LastModifiedIP->AdvancedSearch->Load();
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
		$item->Body = "<a id=\"emf_scholarships\" href=\"javascript:void(0);\" class=\"ewExportLink ewEmail\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_scholarships',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.fscholarshipslist,sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
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
		$table = 'scholarships';
	  $usr = CurrentUserName();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
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
if (!isset($scholarships_list)) $scholarships_list = new cscholarships_list();

// Page init
$scholarships_list->Page_Init();

// Page main
$scholarships_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$scholarships_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($scholarships->Export == "") { ?>
<script type="text/javascript">

// Page object
var scholarships_list = new ew_Page("scholarships_list");
scholarships_list.PageID = "list"; // Page ID
var EW_PAGE_ID = scholarships_list.PageID; // For backward compatibility

// Form object
var fscholarshipslist = new ew_Form("fscholarshipslist");
fscholarshipslist.FormKeyCountName = '<?php echo $scholarships_list->FormKeyCountName ?>';

// Form_CustomValidate event
fscholarshipslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fscholarshipslist.ValidateRequired = true;
<?php } else { ?>
fscholarshipslist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fscholarshipslist.Lists["x_College"] = {"LinkField":"x_CollegeID","Ajax":null,"AutoFill":false,"DisplayFields":["x_College_Name_AR","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fscholarshipslist.Lists["x_Department"] = {"LinkField":"x_DID","Ajax":null,"AutoFill":false,"DisplayFields":["x_DepartmentName2DAR","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
var fscholarshipslistsrch = new ew_Form("fscholarshipslistsrch");

// Validate function for search
fscholarshipslistsrch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	this.PostAutoSuggest();
	var infix = "";
	elm = this.GetElements("x" + infix + "_Committee_Date");
	if (elm && !ew_CheckEuroDate(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($scholarships->Committee_Date->FldErrMsg()) ?>");

	// Set up row object
	ew_ElementsToRow(fobj);

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate event
fscholarshipslistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fscholarshipslistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
fscholarshipslistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
fscholarshipslistsrch.Lists["x_College"] = {"LinkField":"x_CollegeID","Ajax":null,"AutoFill":false,"DisplayFields":["x_College_Name_AR","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fscholarshipslistsrch.Lists["x_Department"] = {"LinkField":"x_DID","Ajax":null,"AutoFill":false,"DisplayFields":["x_DepartmentName2DAR","","",""],"ParentFields":["x_College"],"FilterFields":["x_CID"],"Options":[]};
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($scholarships->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($scholarships_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $scholarships_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$scholarships_list->TotalRecs = $scholarships->SelectRecordCount();
	} else {
		if ($scholarships_list->Recordset = $scholarships_list->LoadRecordset())
			$scholarships_list->TotalRecs = $scholarships_list->Recordset->RecordCount();
	}
	$scholarships_list->StartRec = 1;
	if ($scholarships_list->DisplayRecs <= 0 || ($scholarships->Export <> "" && $scholarships->ExportAll)) // Display all records
		$scholarships_list->DisplayRecs = $scholarships_list->TotalRecs;
	if (!($scholarships->Export <> "" && $scholarships->ExportAll))
		$scholarships_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$scholarships_list->Recordset = $scholarships_list->LoadRecordset($scholarships_list->StartRec-1, $scholarships_list->DisplayRecs);
$scholarships_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($scholarships->Export == "" && $scholarships->CurrentAction == "") { ?>
<form name="fscholarshipslistsrch" id="fscholarshipslistsrch" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>">
<div class="accordion ewDisplayTable ewSearchTable" id="fscholarshipslistsrch_SearchGroup">
	<div class="accordion-group">
		<div class="accordion-heading">
<a class="accordion-toggle" data-toggle="collapse" data-parent="#fscholarshipslistsrch_SearchGroup" href="#fscholarshipslistsrch_SearchBody"><?php echo $Language->Phrase("Search") ?></a>
		</div>
		<div id="fscholarshipslistsrch_SearchBody" class="accordion-body collapse in">
			<div class="accordion-inner">
<div id="fscholarshipslistsrch_SearchPanel">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="scholarships">
<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$scholarships_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$scholarships->RowType = EW_ROWTYPE_SEARCH;

// Render row
$scholarships->ResetAttrs();
$scholarships_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($scholarships->English_Name->Visible) { // English Name ?>
	<span id="xsc_English_Name" class="ewCell">
		<span class="ewSearchCaption"><?php echo $scholarships->English_Name->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_English_Name" id="z_English_Name" value="LIKE"></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_English_Name" name="x_English_Name" id="x_English_Name" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($scholarships->English_Name->PlaceHolder) ?>" value="<?php echo $scholarships->English_Name->EditValue ?>"<?php echo $scholarships->English_Name->EditAttributes() ?>>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
<?php if ($scholarships->Arabic_Name->Visible) { // Arabic Name ?>
	<span id="xsc_Arabic_Name" class="ewCell">
		<span class="ewSearchCaption"><?php echo $scholarships->Arabic_Name->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_Arabic_Name" id="z_Arabic_Name" value="LIKE"></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_Arabic_Name" name="x_Arabic_Name" id="x_Arabic_Name" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($scholarships->Arabic_Name->PlaceHolder) ?>" value="<?php echo $scholarships->Arabic_Name->EditValue ?>"<?php echo $scholarships->Arabic_Name->EditAttributes() ?>>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_3" class="ewRow">
<?php if ($scholarships->College->Visible) { // College ?>
	<span id="xsc_College" class="ewCell">
		<span class="ewSearchCaption"><?php echo $scholarships->College->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_College" id="z_College" value="LIKE"></span>
		<span class="control-group ewSearchField">
<?php $scholarships->College->EditAttrs["onchange"] = "ew_UpdateOpt.call(this, ['x_Department']); " . @$scholarships->College->EditAttrs["onchange"]; ?>
<select data-field="x_College" id="x_College" name="x_College"<?php echo $scholarships->College->EditAttributes() ?>>
<?php
if (is_array($scholarships->College->EditValue)) {
	$arwrk = $scholarships->College->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($scholarships->College->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fscholarshipslistsrch.Lists["x_College"].Options = <?php echo (is_array($scholarships->College->EditValue)) ? ew_ArrayToJson($scholarships->College->EditValue, 1) : "[]" ?>;
</script>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_4" class="ewRow">
<?php if ($scholarships->Department->Visible) { // Department ?>
	<span id="xsc_Department" class="ewCell">
		<span class="ewSearchCaption"><?php echo $scholarships->Department->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_Department" id="z_Department" value="LIKE"></span>
		<span class="control-group ewSearchField">
<select data-field="x_Department" id="x_Department" name="x_Department"<?php echo $scholarships->Department->EditAttributes() ?>>
<?php
if (is_array($scholarships->Department->EditValue)) {
	$arwrk = $scholarships->Department->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($scholarships->Department->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fscholarshipslistsrch.Lists["x_Department"].Options = <?php echo (is_array($scholarships->Department->EditValue)) ? ew_ArrayToJson($scholarships->Department->EditValue, 1) : "[]" ?>;
</script>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_5" class="ewRow">
<?php if ($scholarships->Program_Degree->Visible) { // Program Degree ?>
	<span id="xsc_Program_Degree" class="ewCell">
		<span class="ewSearchCaption"><?php echo $scholarships->Program_Degree->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_Program_Degree" id="z_Program_Degree" value="="></span>
		<span class="control-group ewSearchField">
<div id="tp_x_Program_Degree" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_Program_Degree" id="x_Program_Degree" value="{value}"<?php echo $scholarships->Program_Degree->EditAttributes() ?>></div>
<div id="dsl_x_Program_Degree" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $scholarships->Program_Degree->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($scholarships->Program_Degree->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_Program_Degree" name="x_Program_Degree" id="x_Program_Degree_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $scholarships->Program_Degree->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
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
<div id="xsr_6" class="ewRow">
<?php if ($scholarships->Committee_Date->Visible) { // Committee Date ?>
	<span id="xsc_Committee_Date" class="ewCell">
		<span class="ewSearchCaption"><?php echo $scholarships->Committee_Date->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_Committee_Date" id="z_Committee_Date" value="="></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_Committee_Date" name="x_Committee_Date" id="x_Committee_Date" placeholder="<?php echo ew_HtmlEncode($scholarships->Committee_Date->PlaceHolder) ?>" value="<?php echo $scholarships->Committee_Date->EditValue ?>"<?php echo $scholarships->Committee_Date->EditAttributes() ?>>
<?php if (!$scholarships->Committee_Date->ReadOnly && !$scholarships->Committee_Date->Disabled && @$scholarships->Committee_Date->EditAttrs["readonly"] == "" && @$scholarships->Committee_Date->EditAttrs["disabled"] == "") { ?>
<button id="cal_x_Committee_Date" name="cal_x_Committee_Date" class="btn" type="button"><img src="phpimages/calendar.png" alt="<?php echo $Language->Phrase("PickDate") ?>" title="<?php echo $Language->Phrase("PickDate") ?>" style="border: 0;"></button><script type="text/javascript">
ew_CreateCalendar("fscholarshipslistsrch", "x_Committee_Date", "%d/%m/%Y");
</script>
<?php } ?>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_7" class="ewRow">
<?php if ($scholarships->Status->Visible) { // Status ?>
	<span id="xsc_Status" class="ewCell">
		<span class="ewSearchCaption"><?php echo $scholarships->Status->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_Status" id="z_Status" value="="></span>
		<span class="control-group ewSearchField">
<div id="tp_x_Status" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_Status" id="x_Status" value="{value}"<?php echo $scholarships->Status->EditAttributes() ?>></div>
<div id="dsl_x_Status" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $scholarships->Status->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($scholarships->Status->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_Status" name="x_Status" id="x_Status_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $scholarships->Status->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
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
<div id="xsr_8" class="ewRow">
	<div class="btn-group ewButtonGroup">
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	<div class="btn-group ewButtonGroup">
	<a class="btn ewShowAll" href="<?php echo $scholarships_list->PageUrl() ?>cmd=reset"><?php echo $Language->Phrase("ShowAll") ?></a>
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
<?php $scholarships_list->ShowPageHeader(); ?>
<?php
$scholarships_list->ShowMessage();
?>
<table class="ewGrid"><tr><td class="ewGridContent">
<?php if ($scholarships->Export == "") { ?>
<div class="ewGridUpperPanel">
<?php if ($scholarships->CurrentAction <> "gridadd" && $scholarships->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager">
<tr><td>
<?php if (!isset($scholarships_list->Pager)) $scholarships_list->Pager = new cPrevNextPager($scholarships_list->StartRec, $scholarships_list->DisplayRecs, $scholarships_list->TotalRecs) ?>
<?php if ($scholarships_list->Pager->RecordCount > 0) { ?>
<table class="ewStdTable"><tbody><tr><td>
	<?php echo $Language->Phrase("Page") ?>&nbsp;
<div class="input-prepend input-append">
<!--first page button-->
	<?php if ($scholarships_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $scholarships_list->PageUrl() ?>start=<?php echo $scholarships_list->Pager->FirstButton->Start ?>"><i class="icon-step-backward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-step-backward"></i></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($scholarships_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $scholarships_list->PageUrl() ?>start=<?php echo $scholarships_list->Pager->PrevButton->Start ?>"><i class="icon-prev"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-prev"></i></a>
	<?php } ?>
<!--current page number-->
	<input class="input-mini" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $scholarships_list->Pager->CurrentPage ?>">
<!--next page button-->
	<?php if ($scholarships_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $scholarships_list->PageUrl() ?>start=<?php echo $scholarships_list->Pager->NextButton->Start ?>"><i class="icon-play"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-play"></i></a>
	<?php } ?>
<!--last page button-->
	<?php if ($scholarships_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $scholarships_list->PageUrl() ?>start=<?php echo $scholarships_list->Pager->LastButton->Start ?>"><i class="icon-step-forward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-step-forward"></i></a>
	<?php } ?>
</div>
	&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $scholarships_list->Pager->PageCount ?>
</td>
<td>
	&nbsp;&nbsp;&nbsp;&nbsp;
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $scholarships_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $scholarships_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $scholarships_list->Pager->RecordCount ?>
</td>
</tr></tbody></table>
<?php } else { ?>
	<?php if ($Security->CanList()) { ?>
	<?php if ($scholarships_list->SearchWhere == "0=101") { ?>
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
	foreach ($scholarships_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
</div>
<?php } ?>
<form name="fscholarshipslist" id="fscholarshipslist" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="scholarships">
<div id="gmp_scholarships" class="ewGridMiddlePanel">
<?php if ($scholarships_list->TotalRecs > 0) { ?>
<table id="tbl_scholarshipslist" class="ewTable ewTableSeparate">
<?php echo $scholarships->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$scholarships_list->RenderListOptions();

// Render list options (header, left)
$scholarships_list->ListOptions->Render("header", "left");
?>
<?php if ($scholarships->English_Name->Visible) { // English Name ?>
	<?php if ($scholarships->SortUrl($scholarships->English_Name) == "") { ?>
		<td><div id="elh_scholarships_English_Name" class="scholarships_English_Name"><div class="ewTableHeaderCaption"><?php echo $scholarships->English_Name->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $scholarships->SortUrl($scholarships->English_Name) ?>',1);"><div id="elh_scholarships_English_Name" class="scholarships_English_Name">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $scholarships->English_Name->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($scholarships->English_Name->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($scholarships->English_Name->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($scholarships->Arabic_Name->Visible) { // Arabic Name ?>
	<?php if ($scholarships->SortUrl($scholarships->Arabic_Name) == "") { ?>
		<td><div id="elh_scholarships_Arabic_Name" class="scholarships_Arabic_Name"><div class="ewTableHeaderCaption"><?php echo $scholarships->Arabic_Name->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $scholarships->SortUrl($scholarships->Arabic_Name) ?>',1);"><div id="elh_scholarships_Arabic_Name" class="scholarships_Arabic_Name">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $scholarships->Arabic_Name->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($scholarships->Arabic_Name->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($scholarships->Arabic_Name->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($scholarships->College->Visible) { // College ?>
	<?php if ($scholarships->SortUrl($scholarships->College) == "") { ?>
		<td><div id="elh_scholarships_College" class="scholarships_College"><div class="ewTableHeaderCaption"><?php echo $scholarships->College->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $scholarships->SortUrl($scholarships->College) ?>',1);"><div id="elh_scholarships_College" class="scholarships_College">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $scholarships->College->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($scholarships->College->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($scholarships->College->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($scholarships->Department->Visible) { // Department ?>
	<?php if ($scholarships->SortUrl($scholarships->Department) == "") { ?>
		<td><div id="elh_scholarships_Department" class="scholarships_Department"><div class="ewTableHeaderCaption"><?php echo $scholarships->Department->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $scholarships->SortUrl($scholarships->Department) ?>',1);"><div id="elh_scholarships_Department" class="scholarships_Department">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $scholarships->Department->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($scholarships->Department->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($scholarships->Department->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($scholarships->Major->Visible) { // Major ?>
	<?php if ($scholarships->SortUrl($scholarships->Major) == "") { ?>
		<td><div id="elh_scholarships_Major" class="scholarships_Major"><div class="ewTableHeaderCaption"><?php echo $scholarships->Major->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $scholarships->SortUrl($scholarships->Major) ?>',1);"><div id="elh_scholarships_Major" class="scholarships_Major">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $scholarships->Major->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($scholarships->Major->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($scholarships->Major->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($scholarships->Acceptance_University->Visible) { // Acceptance University ?>
	<?php if ($scholarships->SortUrl($scholarships->Acceptance_University) == "") { ?>
		<td><div id="elh_scholarships_Acceptance_University" class="scholarships_Acceptance_University"><div class="ewTableHeaderCaption"><?php echo $scholarships->Acceptance_University->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $scholarships->SortUrl($scholarships->Acceptance_University) ?>',1);"><div id="elh_scholarships_Acceptance_University" class="scholarships_Acceptance_University">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $scholarships->Acceptance_University->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($scholarships->Acceptance_University->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($scholarships->Acceptance_University->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($scholarships->Program_Degree->Visible) { // Program Degree ?>
	<?php if ($scholarships->SortUrl($scholarships->Program_Degree) == "") { ?>
		<td><div id="elh_scholarships_Program_Degree" class="scholarships_Program_Degree"><div class="ewTableHeaderCaption"><?php echo $scholarships->Program_Degree->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $scholarships->SortUrl($scholarships->Program_Degree) ?>',1);"><div id="elh_scholarships_Program_Degree" class="scholarships_Program_Degree">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $scholarships->Program_Degree->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($scholarships->Program_Degree->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($scholarships->Program_Degree->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($scholarships->Committee_Date->Visible) { // Committee Date ?>
	<?php if ($scholarships->SortUrl($scholarships->Committee_Date) == "") { ?>
		<td><div id="elh_scholarships_Committee_Date" class="scholarships_Committee_Date"><div class="ewTableHeaderCaption"><?php echo $scholarships->Committee_Date->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $scholarships->SortUrl($scholarships->Committee_Date) ?>',1);"><div id="elh_scholarships_Committee_Date" class="scholarships_Committee_Date">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $scholarships->Committee_Date->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($scholarships->Committee_Date->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($scholarships->Committee_Date->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($scholarships->Status->Visible) { // Status ?>
	<?php if ($scholarships->SortUrl($scholarships->Status) == "") { ?>
		<td><div id="elh_scholarships_Status" class="scholarships_Status"><div class="ewTableHeaderCaption"><?php echo $scholarships->Status->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $scholarships->SortUrl($scholarships->Status) ?>',1);"><div id="elh_scholarships_Status" class="scholarships_Status">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $scholarships->Status->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($scholarships->Status->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($scholarships->Status->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$scholarships_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($scholarships->ExportAll && $scholarships->Export <> "") {
	$scholarships_list->StopRec = $scholarships_list->TotalRecs;
} else {

	// Set the last record to display
	if ($scholarships_list->TotalRecs > $scholarships_list->StartRec + $scholarships_list->DisplayRecs - 1)
		$scholarships_list->StopRec = $scholarships_list->StartRec + $scholarships_list->DisplayRecs - 1;
	else
		$scholarships_list->StopRec = $scholarships_list->TotalRecs;
}
$scholarships_list->RecCnt = $scholarships_list->StartRec - 1;
if ($scholarships_list->Recordset && !$scholarships_list->Recordset->EOF) {
	$scholarships_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $scholarships_list->StartRec > 1)
		$scholarships_list->Recordset->Move($scholarships_list->StartRec - 1);
} elseif (!$scholarships->AllowAddDeleteRow && $scholarships_list->StopRec == 0) {
	$scholarships_list->StopRec = $scholarships->GridAddRowCount;
}

// Initialize aggregate
$scholarships->RowType = EW_ROWTYPE_AGGREGATEINIT;
$scholarships->ResetAttrs();
$scholarships_list->RenderRow();
while ($scholarships_list->RecCnt < $scholarships_list->StopRec) {
	$scholarships_list->RecCnt++;
	if (intval($scholarships_list->RecCnt) >= intval($scholarships_list->StartRec)) {
		$scholarships_list->RowCnt++;

		// Set up key count
		$scholarships_list->KeyCount = $scholarships_list->RowIndex;

		// Init row class and style
		$scholarships->ResetAttrs();
		$scholarships->CssClass = "";
		if ($scholarships->CurrentAction == "gridadd") {
		} else {
			$scholarships_list->LoadRowValues($scholarships_list->Recordset); // Load row values
		}
		$scholarships->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$scholarships->RowAttrs = array_merge($scholarships->RowAttrs, array('data-rowindex'=>$scholarships_list->RowCnt, 'id'=>'r' . $scholarships_list->RowCnt . '_scholarships', 'data-rowtype'=>$scholarships->RowType));

		// Render row
		$scholarships_list->RenderRow();

		// Render list options
		$scholarships_list->RenderListOptions();
?>
	<tr<?php echo $scholarships->RowAttributes() ?>>
<?php

// Render list options (body, left)
$scholarships_list->ListOptions->Render("body", "left", $scholarships_list->RowCnt);
?>
	<?php if ($scholarships->English_Name->Visible) { // English Name ?>
		<td<?php echo $scholarships->English_Name->CellAttributes() ?>>
<span<?php echo $scholarships->English_Name->ViewAttributes() ?>>
<?php echo $scholarships->English_Name->ListViewValue() ?></span>
<a id="<?php echo $scholarships_list->PageObjName . "_row_" . $scholarships_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($scholarships->Arabic_Name->Visible) { // Arabic Name ?>
		<td<?php echo $scholarships->Arabic_Name->CellAttributes() ?>>
<span<?php echo $scholarships->Arabic_Name->ViewAttributes() ?>>
<?php echo $scholarships->Arabic_Name->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($scholarships->College->Visible) { // College ?>
		<td<?php echo $scholarships->College->CellAttributes() ?>>
<span<?php echo $scholarships->College->ViewAttributes() ?>>
<?php echo $scholarships->College->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($scholarships->Department->Visible) { // Department ?>
		<td<?php echo $scholarships->Department->CellAttributes() ?>>
<span<?php echo $scholarships->Department->ViewAttributes() ?>>
<?php echo $scholarships->Department->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($scholarships->Major->Visible) { // Major ?>
		<td<?php echo $scholarships->Major->CellAttributes() ?>>
<span<?php echo $scholarships->Major->ViewAttributes() ?>>
<?php echo $scholarships->Major->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($scholarships->Acceptance_University->Visible) { // Acceptance University ?>
		<td<?php echo $scholarships->Acceptance_University->CellAttributes() ?>>
<span<?php echo $scholarships->Acceptance_University->ViewAttributes() ?>>
<?php echo $scholarships->Acceptance_University->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($scholarships->Program_Degree->Visible) { // Program Degree ?>
		<td<?php echo $scholarships->Program_Degree->CellAttributes() ?>>
<span<?php echo $scholarships->Program_Degree->ViewAttributes() ?>>
<?php echo $scholarships->Program_Degree->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($scholarships->Committee_Date->Visible) { // Committee Date ?>
		<td<?php echo $scholarships->Committee_Date->CellAttributes() ?>>
<span<?php echo $scholarships->Committee_Date->ViewAttributes() ?>>
<?php echo $scholarships->Committee_Date->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($scholarships->Status->Visible) { // Status ?>
		<td<?php echo $scholarships->Status->CellAttributes() ?>>
<span<?php echo $scholarships->Status->ViewAttributes() ?>>
<?php echo $scholarships->Status->ListViewValue() ?></span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$scholarships_list->ListOptions->Render("body", "right", $scholarships_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($scholarships->CurrentAction <> "gridadd")
		$scholarships_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($scholarships->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($scholarships_list->Recordset)
	$scholarships_list->Recordset->Close();
?>
<?php if ($scholarships_list->TotalRecs > 0) { ?>
<?php if ($scholarships->Export == "") { ?>
<div class="ewGridLowerPanel">
<?php if ($scholarships->CurrentAction <> "gridadd" && $scholarships->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager">
<tr><td>
<?php if (!isset($scholarships_list->Pager)) $scholarships_list->Pager = new cPrevNextPager($scholarships_list->StartRec, $scholarships_list->DisplayRecs, $scholarships_list->TotalRecs) ?>
<?php if ($scholarships_list->Pager->RecordCount > 0) { ?>
<table class="ewStdTable"><tbody><tr><td>
	<?php echo $Language->Phrase("Page") ?>&nbsp;
<div class="input-prepend input-append">
<!--first page button-->
	<?php if ($scholarships_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $scholarships_list->PageUrl() ?>start=<?php echo $scholarships_list->Pager->FirstButton->Start ?>"><i class="icon-step-backward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-step-backward"></i></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($scholarships_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $scholarships_list->PageUrl() ?>start=<?php echo $scholarships_list->Pager->PrevButton->Start ?>"><i class="icon-prev"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-prev"></i></a>
	<?php } ?>
<!--current page number-->
	<input class="input-mini" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $scholarships_list->Pager->CurrentPage ?>">
<!--next page button-->
	<?php if ($scholarships_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $scholarships_list->PageUrl() ?>start=<?php echo $scholarships_list->Pager->NextButton->Start ?>"><i class="icon-play"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-play"></i></a>
	<?php } ?>
<!--last page button-->
	<?php if ($scholarships_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $scholarships_list->PageUrl() ?>start=<?php echo $scholarships_list->Pager->LastButton->Start ?>"><i class="icon-step-forward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-step-forward"></i></a>
	<?php } ?>
</div>
	&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $scholarships_list->Pager->PageCount ?>
</td>
<td>
	&nbsp;&nbsp;&nbsp;&nbsp;
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $scholarships_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $scholarships_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $scholarships_list->Pager->RecordCount ?>
</td>
</tr></tbody></table>
<?php } else { ?>
	<?php if ($Security->CanList()) { ?>
	<?php if ($scholarships_list->SearchWhere == "0=101") { ?>
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
	foreach ($scholarships_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
</div>
<?php } ?>
<?php } ?>
</td></tr></table>
<?php if ($scholarships->Export == "") { ?>
<script type="text/javascript">
fscholarshipslistsrch.Init();
fscholarshipslist.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$scholarships_list->ShowPageFooter();
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
$scholarships_list->Page_Terminate();
?>
