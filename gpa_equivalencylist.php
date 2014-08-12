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

$gpa_equivalency_list = NULL; // Initialize page object first

class cgpa_equivalency_list extends cgpa_equivalency {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{F8E6AD82-57C4-4BEE-8975-8D386184467A}";

	// Table name
	var $TableName = 'gpa equivalency';

	// Page object name
	var $PageObjName = 'gpa_equivalency_list';

	// Grid form hidden field names
	var $FormName = 'fgpa_equivalencylist';
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

		// Table object (gpa_equivalency)
		if (!isset($GLOBALS["gpa_equivalency"]) || get_class($GLOBALS["gpa_equivalency"]) == "cgpa_equivalency") {
			$GLOBALS["gpa_equivalency"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["gpa_equivalency"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "gpa_equivalencyadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "gpa_equivalencydelete.php";
		$this->MultiUpdateUrl = "gpa_equivalencyupdate.php";

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'gpa equivalency', TRUE);

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
		$this->BuildSearchSql($sWhere, $this->Name, FALSE); // Name
		$this->BuildSearchSql($sWhere, $this->Country, FALSE); // Country
		$this->BuildSearchSql($sWhere, $this->Civil_ID, FALSE); // Civil ID
		$this->BuildSearchSql($sWhere, $this->Passport_No2E, FALSE); // Passport No.
		$this->BuildSearchSql($sWhere, $this->Sector, FALSE); // Sector
		$this->BuildSearchSql($sWhere, $this->Job_Title, FALSE); // Job Title
		$this->BuildSearchSql($sWhere, $this->Program, FALSE); // Program
		$this->BuildSearchSql($sWhere, $this->College, FALSE); // College
		$this->BuildSearchSql($sWhere, $this->Department, FALSE); // Department
		$this->BuildSearchSql($sWhere, $this->Bachelors_Title, FALSE); // Bachelors Title
		$this->BuildSearchSql($sWhere, $this->Bachelor_University, FALSE); // Bachelor University
		$this->BuildSearchSql($sWhere, $this->Bachelors_Major, FALSE); // Bachelors Major
		$this->BuildSearchSql($sWhere, $this->Bachelors_GPA, FALSE); // Bachelors GPA
		$this->BuildSearchSql($sWhere, $this->Bachelors_MGPA, FALSE); // Bachelors MGPA
		$this->BuildSearchSql($sWhere, $this->Other_Bachelors_Title, FALSE); // Other Bachelors Title
		$this->BuildSearchSql($sWhere, $this->Other_Bachelors_University, FALSE); // Other Bachelors University
		$this->BuildSearchSql($sWhere, $this->Other_Bachelors_Major, FALSE); // Other Bachelors Major
		$this->BuildSearchSql($sWhere, $this->Other_Bachelors_GPA, FALSE); // Other Bachelors GPA
		$this->BuildSearchSql($sWhere, $this->Other_Bachelors_MGPA, FALSE); // Other Bachelors MGPA
		$this->BuildSearchSql($sWhere, $this->Masters_Degree_Title, FALSE); // Masters Degree Title
		$this->BuildSearchSql($sWhere, $this->Master_University, FALSE); // Master University
		$this->BuildSearchSql($sWhere, $this->Masters_Degree_Major, FALSE); // Masters Degree Major
		$this->BuildSearchSql($sWhere, $this->Masters_GPA, FALSE); // Masters GPA
		$this->BuildSearchSql($sWhere, $this->Other_Masters_Degree_Title, FALSE); // Other Masters Degree Title
		$this->BuildSearchSql($sWhere, $this->Other_Masters_University, FALSE); // Other Masters University
		$this->BuildSearchSql($sWhere, $this->Other_Masters_Major, FALSE); // Other Masters Major
		$this->BuildSearchSql($sWhere, $this->Other_Masters_GPA, FALSE); // Other Masters GPA
		$this->BuildSearchSql($sWhere, $this->PhD_Title, FALSE); // PhD Title
		$this->BuildSearchSql($sWhere, $this->Phd_University, FALSE); // Phd University
		$this->BuildSearchSql($sWhere, $this->PhD_Major, FALSE); // PhD Major
		$this->BuildSearchSql($sWhere, $this->Phd_Degree_Equivalency, FALSE); // Phd Degree Equivalency
		$this->BuildSearchSql($sWhere, $this->Committee_Meeting, FALSE); // Committee Meeting
		$this->BuildSearchSql($sWhere, $this->Committee_Meeting_Number, FALSE); // Committee Meeting Number
		$this->BuildSearchSql($sWhere, $this->Committee_Date, FALSE); // Committee Date
		$this->BuildSearchSql($sWhere, $this->Notes, FALSE); // Notes

		// Set up search parm
		if ($sWhere <> "") {
			$this->Command = "search";
		}
		if ($this->Command == "search") {
			$this->ID->AdvancedSearch->Save(); // ID
			$this->Name->AdvancedSearch->Save(); // Name
			$this->Country->AdvancedSearch->Save(); // Country
			$this->Civil_ID->AdvancedSearch->Save(); // Civil ID
			$this->Passport_No2E->AdvancedSearch->Save(); // Passport No.
			$this->Sector->AdvancedSearch->Save(); // Sector
			$this->Job_Title->AdvancedSearch->Save(); // Job Title
			$this->Program->AdvancedSearch->Save(); // Program
			$this->College->AdvancedSearch->Save(); // College
			$this->Department->AdvancedSearch->Save(); // Department
			$this->Bachelors_Title->AdvancedSearch->Save(); // Bachelors Title
			$this->Bachelor_University->AdvancedSearch->Save(); // Bachelor University
			$this->Bachelors_Major->AdvancedSearch->Save(); // Bachelors Major
			$this->Bachelors_GPA->AdvancedSearch->Save(); // Bachelors GPA
			$this->Bachelors_MGPA->AdvancedSearch->Save(); // Bachelors MGPA
			$this->Other_Bachelors_Title->AdvancedSearch->Save(); // Other Bachelors Title
			$this->Other_Bachelors_University->AdvancedSearch->Save(); // Other Bachelors University
			$this->Other_Bachelors_Major->AdvancedSearch->Save(); // Other Bachelors Major
			$this->Other_Bachelors_GPA->AdvancedSearch->Save(); // Other Bachelors GPA
			$this->Other_Bachelors_MGPA->AdvancedSearch->Save(); // Other Bachelors MGPA
			$this->Masters_Degree_Title->AdvancedSearch->Save(); // Masters Degree Title
			$this->Master_University->AdvancedSearch->Save(); // Master University
			$this->Masters_Degree_Major->AdvancedSearch->Save(); // Masters Degree Major
			$this->Masters_GPA->AdvancedSearch->Save(); // Masters GPA
			$this->Other_Masters_Degree_Title->AdvancedSearch->Save(); // Other Masters Degree Title
			$this->Other_Masters_University->AdvancedSearch->Save(); // Other Masters University
			$this->Other_Masters_Major->AdvancedSearch->Save(); // Other Masters Major
			$this->Other_Masters_GPA->AdvancedSearch->Save(); // Other Masters GPA
			$this->PhD_Title->AdvancedSearch->Save(); // PhD Title
			$this->Phd_University->AdvancedSearch->Save(); // Phd University
			$this->PhD_Major->AdvancedSearch->Save(); // PhD Major
			$this->Phd_Degree_Equivalency->AdvancedSearch->Save(); // Phd Degree Equivalency
			$this->Committee_Meeting->AdvancedSearch->Save(); // Committee Meeting
			$this->Committee_Meeting_Number->AdvancedSearch->Save(); // Committee Meeting Number
			$this->Committee_Date->AdvancedSearch->Save(); // Committee Date
			$this->Notes->AdvancedSearch->Save(); // Notes
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
		if ($this->Name->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Country->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Civil_ID->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Passport_No2E->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Sector->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Job_Title->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Program->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->College->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Department->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Bachelors_Title->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Bachelor_University->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Bachelors_Major->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Bachelors_GPA->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Bachelors_MGPA->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Other_Bachelors_Title->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Other_Bachelors_University->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Other_Bachelors_Major->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Other_Bachelors_GPA->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Other_Bachelors_MGPA->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Masters_Degree_Title->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Master_University->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Masters_Degree_Major->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Masters_GPA->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Other_Masters_Degree_Title->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Other_Masters_University->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Other_Masters_Major->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Other_Masters_GPA->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->PhD_Title->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Phd_University->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->PhD_Major->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Phd_Degree_Equivalency->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Committee_Meeting->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Committee_Meeting_Number->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Committee_Date->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Notes->AdvancedSearch->IssetSession())
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
		$this->Name->AdvancedSearch->UnsetSession();
		$this->Country->AdvancedSearch->UnsetSession();
		$this->Civil_ID->AdvancedSearch->UnsetSession();
		$this->Passport_No2E->AdvancedSearch->UnsetSession();
		$this->Sector->AdvancedSearch->UnsetSession();
		$this->Job_Title->AdvancedSearch->UnsetSession();
		$this->Program->AdvancedSearch->UnsetSession();
		$this->College->AdvancedSearch->UnsetSession();
		$this->Department->AdvancedSearch->UnsetSession();
		$this->Bachelors_Title->AdvancedSearch->UnsetSession();
		$this->Bachelor_University->AdvancedSearch->UnsetSession();
		$this->Bachelors_Major->AdvancedSearch->UnsetSession();
		$this->Bachelors_GPA->AdvancedSearch->UnsetSession();
		$this->Bachelors_MGPA->AdvancedSearch->UnsetSession();
		$this->Other_Bachelors_Title->AdvancedSearch->UnsetSession();
		$this->Other_Bachelors_University->AdvancedSearch->UnsetSession();
		$this->Other_Bachelors_Major->AdvancedSearch->UnsetSession();
		$this->Other_Bachelors_GPA->AdvancedSearch->UnsetSession();
		$this->Other_Bachelors_MGPA->AdvancedSearch->UnsetSession();
		$this->Masters_Degree_Title->AdvancedSearch->UnsetSession();
		$this->Master_University->AdvancedSearch->UnsetSession();
		$this->Masters_Degree_Major->AdvancedSearch->UnsetSession();
		$this->Masters_GPA->AdvancedSearch->UnsetSession();
		$this->Other_Masters_Degree_Title->AdvancedSearch->UnsetSession();
		$this->Other_Masters_University->AdvancedSearch->UnsetSession();
		$this->Other_Masters_Major->AdvancedSearch->UnsetSession();
		$this->Other_Masters_GPA->AdvancedSearch->UnsetSession();
		$this->PhD_Title->AdvancedSearch->UnsetSession();
		$this->Phd_University->AdvancedSearch->UnsetSession();
		$this->PhD_Major->AdvancedSearch->UnsetSession();
		$this->Phd_Degree_Equivalency->AdvancedSearch->UnsetSession();
		$this->Committee_Meeting->AdvancedSearch->UnsetSession();
		$this->Committee_Meeting_Number->AdvancedSearch->UnsetSession();
		$this->Committee_Date->AdvancedSearch->UnsetSession();
		$this->Notes->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore advanced search values
		$this->ID->AdvancedSearch->Load();
		$this->Name->AdvancedSearch->Load();
		$this->Country->AdvancedSearch->Load();
		$this->Civil_ID->AdvancedSearch->Load();
		$this->Passport_No2E->AdvancedSearch->Load();
		$this->Sector->AdvancedSearch->Load();
		$this->Job_Title->AdvancedSearch->Load();
		$this->Program->AdvancedSearch->Load();
		$this->College->AdvancedSearch->Load();
		$this->Department->AdvancedSearch->Load();
		$this->Bachelors_Title->AdvancedSearch->Load();
		$this->Bachelor_University->AdvancedSearch->Load();
		$this->Bachelors_Major->AdvancedSearch->Load();
		$this->Bachelors_GPA->AdvancedSearch->Load();
		$this->Bachelors_MGPA->AdvancedSearch->Load();
		$this->Other_Bachelors_Title->AdvancedSearch->Load();
		$this->Other_Bachelors_University->AdvancedSearch->Load();
		$this->Other_Bachelors_Major->AdvancedSearch->Load();
		$this->Other_Bachelors_GPA->AdvancedSearch->Load();
		$this->Other_Bachelors_MGPA->AdvancedSearch->Load();
		$this->Masters_Degree_Title->AdvancedSearch->Load();
		$this->Master_University->AdvancedSearch->Load();
		$this->Masters_Degree_Major->AdvancedSearch->Load();
		$this->Masters_GPA->AdvancedSearch->Load();
		$this->Other_Masters_Degree_Title->AdvancedSearch->Load();
		$this->Other_Masters_University->AdvancedSearch->Load();
		$this->Other_Masters_Major->AdvancedSearch->Load();
		$this->Other_Masters_GPA->AdvancedSearch->Load();
		$this->PhD_Title->AdvancedSearch->Load();
		$this->Phd_University->AdvancedSearch->Load();
		$this->PhD_Major->AdvancedSearch->Load();
		$this->Phd_Degree_Equivalency->AdvancedSearch->Load();
		$this->Committee_Meeting->AdvancedSearch->Load();
		$this->Committee_Meeting_Number->AdvancedSearch->Load();
		$this->Committee_Date->AdvancedSearch->Load();
		$this->Notes->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->ID); // ID
			$this->UpdateSort($this->Name); // Name
			$this->UpdateSort($this->Country); // Country
			$this->UpdateSort($this->Civil_ID); // Civil ID
			$this->UpdateSort($this->Sector); // Sector
			$this->UpdateSort($this->Job_Title); // Job Title
			$this->UpdateSort($this->Program); // Program
			$this->UpdateSort($this->College); // College
			$this->UpdateSort($this->Department); // Department
			$this->UpdateSort($this->Committee_Date); // Committee Date
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
				$this->ID->setSort("ASC");
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
				$this->setSessionOrderByList($sOrderBy);
				$this->ID->setSort("");
				$this->Name->setSort("");
				$this->Country->setSort("");
				$this->Civil_ID->setSort("");
				$this->Sector->setSort("");
				$this->Job_Title->setSort("");
				$this->Program->setSort("");
				$this->College->setSort("");
				$this->Department->setSort("");
				$this->Committee_Date->setSort("");
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
		$item->Visible = ($Security->CanDelete() || $Security->CanEdit());
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
		$item->Body = "<a class=\"ewAction ewMultiDelete\" href=\"\" onclick=\"ew_SubmitSelected(document.fgpa_equivalencylist, '" . $this->MultiDeleteUrl . "');return false;\">" . $Language->Phrase("DeleteSelectedLink") . "</a>";
		$item->Visible = ($Security->CanDelete());

		// Add multi update
		$item = &$option->Add("multiupdate");
		$item->Body = "<a class=\"ewAction ewMultiUpdate\" href=\"\" onclick=\"ew_SubmitSelected(document.fgpa_equivalencylist, '" . $this->MultiUpdateUrl . "');return false;\">" . $Language->Phrase("UpdateSelectedLink") . "</a>";
		$item->Visible = ($Security->CanEdit());

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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.fgpa_equivalencylist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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

		// Name
		$this->Name->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Name"]);
		if ($this->Name->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Name->AdvancedSearch->SearchOperator = @$_GET["z_Name"];

		// Country
		$this->Country->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Country"]);
		if ($this->Country->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Country->AdvancedSearch->SearchOperator = @$_GET["z_Country"];

		// Civil ID
		$this->Civil_ID->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Civil_ID"]);
		if ($this->Civil_ID->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Civil_ID->AdvancedSearch->SearchOperator = @$_GET["z_Civil_ID"];

		// Passport No.
		$this->Passport_No2E->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Passport_No2E"]);
		if ($this->Passport_No2E->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Passport_No2E->AdvancedSearch->SearchOperator = @$_GET["z_Passport_No2E"];

		// Sector
		$this->Sector->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Sector"]);
		if ($this->Sector->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Sector->AdvancedSearch->SearchOperator = @$_GET["z_Sector"];

		// Job Title
		$this->Job_Title->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Job_Title"]);
		if ($this->Job_Title->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Job_Title->AdvancedSearch->SearchOperator = @$_GET["z_Job_Title"];

		// Program
		$this->Program->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Program"]);
		if ($this->Program->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Program->AdvancedSearch->SearchOperator = @$_GET["z_Program"];

		// College
		$this->College->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_College"]);
		if ($this->College->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->College->AdvancedSearch->SearchOperator = @$_GET["z_College"];

		// Department
		$this->Department->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Department"]);
		if ($this->Department->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Department->AdvancedSearch->SearchOperator = @$_GET["z_Department"];

		// Bachelors Title
		$this->Bachelors_Title->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Bachelors_Title"]);
		if ($this->Bachelors_Title->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Bachelors_Title->AdvancedSearch->SearchOperator = @$_GET["z_Bachelors_Title"];

		// Bachelor University
		$this->Bachelor_University->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Bachelor_University"]);
		if ($this->Bachelor_University->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Bachelor_University->AdvancedSearch->SearchOperator = @$_GET["z_Bachelor_University"];

		// Bachelors Major
		$this->Bachelors_Major->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Bachelors_Major"]);
		if ($this->Bachelors_Major->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Bachelors_Major->AdvancedSearch->SearchOperator = @$_GET["z_Bachelors_Major"];

		// Bachelors GPA
		$this->Bachelors_GPA->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Bachelors_GPA"]);
		if ($this->Bachelors_GPA->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Bachelors_GPA->AdvancedSearch->SearchOperator = @$_GET["z_Bachelors_GPA"];

		// Bachelors MGPA
		$this->Bachelors_MGPA->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Bachelors_MGPA"]);
		if ($this->Bachelors_MGPA->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Bachelors_MGPA->AdvancedSearch->SearchOperator = @$_GET["z_Bachelors_MGPA"];

		// Other Bachelors Title
		$this->Other_Bachelors_Title->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Other_Bachelors_Title"]);
		if ($this->Other_Bachelors_Title->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Other_Bachelors_Title->AdvancedSearch->SearchOperator = @$_GET["z_Other_Bachelors_Title"];

		// Other Bachelors University
		$this->Other_Bachelors_University->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Other_Bachelors_University"]);
		if ($this->Other_Bachelors_University->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Other_Bachelors_University->AdvancedSearch->SearchOperator = @$_GET["z_Other_Bachelors_University"];

		// Other Bachelors Major
		$this->Other_Bachelors_Major->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Other_Bachelors_Major"]);
		if ($this->Other_Bachelors_Major->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Other_Bachelors_Major->AdvancedSearch->SearchOperator = @$_GET["z_Other_Bachelors_Major"];

		// Other Bachelors GPA
		$this->Other_Bachelors_GPA->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Other_Bachelors_GPA"]);
		if ($this->Other_Bachelors_GPA->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Other_Bachelors_GPA->AdvancedSearch->SearchOperator = @$_GET["z_Other_Bachelors_GPA"];

		// Other Bachelors MGPA
		$this->Other_Bachelors_MGPA->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Other_Bachelors_MGPA"]);
		if ($this->Other_Bachelors_MGPA->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Other_Bachelors_MGPA->AdvancedSearch->SearchOperator = @$_GET["z_Other_Bachelors_MGPA"];

		// Masters Degree Title
		$this->Masters_Degree_Title->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Masters_Degree_Title"]);
		if ($this->Masters_Degree_Title->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Masters_Degree_Title->AdvancedSearch->SearchOperator = @$_GET["z_Masters_Degree_Title"];

		// Master University
		$this->Master_University->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Master_University"]);
		if ($this->Master_University->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Master_University->AdvancedSearch->SearchOperator = @$_GET["z_Master_University"];

		// Masters Degree Major
		$this->Masters_Degree_Major->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Masters_Degree_Major"]);
		if ($this->Masters_Degree_Major->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Masters_Degree_Major->AdvancedSearch->SearchOperator = @$_GET["z_Masters_Degree_Major"];

		// Masters GPA
		$this->Masters_GPA->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Masters_GPA"]);
		if ($this->Masters_GPA->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Masters_GPA->AdvancedSearch->SearchOperator = @$_GET["z_Masters_GPA"];

		// Other Masters Degree Title
		$this->Other_Masters_Degree_Title->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Other_Masters_Degree_Title"]);
		if ($this->Other_Masters_Degree_Title->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Other_Masters_Degree_Title->AdvancedSearch->SearchOperator = @$_GET["z_Other_Masters_Degree_Title"];

		// Other Masters University
		$this->Other_Masters_University->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Other_Masters_University"]);
		if ($this->Other_Masters_University->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Other_Masters_University->AdvancedSearch->SearchOperator = @$_GET["z_Other_Masters_University"];

		// Other Masters Major
		$this->Other_Masters_Major->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Other_Masters_Major"]);
		if ($this->Other_Masters_Major->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Other_Masters_Major->AdvancedSearch->SearchOperator = @$_GET["z_Other_Masters_Major"];

		// Other Masters GPA
		$this->Other_Masters_GPA->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Other_Masters_GPA"]);
		if ($this->Other_Masters_GPA->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Other_Masters_GPA->AdvancedSearch->SearchOperator = @$_GET["z_Other_Masters_GPA"];

		// PhD Title
		$this->PhD_Title->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_PhD_Title"]);
		if ($this->PhD_Title->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->PhD_Title->AdvancedSearch->SearchOperator = @$_GET["z_PhD_Title"];

		// Phd University
		$this->Phd_University->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Phd_University"]);
		if ($this->Phd_University->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Phd_University->AdvancedSearch->SearchOperator = @$_GET["z_Phd_University"];

		// PhD Major
		$this->PhD_Major->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_PhD_Major"]);
		if ($this->PhD_Major->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->PhD_Major->AdvancedSearch->SearchOperator = @$_GET["z_PhD_Major"];

		// Phd Degree Equivalency
		$this->Phd_Degree_Equivalency->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Phd_Degree_Equivalency"]);
		if ($this->Phd_Degree_Equivalency->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Phd_Degree_Equivalency->AdvancedSearch->SearchOperator = @$_GET["z_Phd_Degree_Equivalency"];

		// Committee Meeting
		$this->Committee_Meeting->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Committee_Meeting"]);
		if ($this->Committee_Meeting->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Committee_Meeting->AdvancedSearch->SearchOperator = @$_GET["z_Committee_Meeting"];

		// Committee Meeting Number
		$this->Committee_Meeting_Number->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Committee_Meeting_Number"]);
		if ($this->Committee_Meeting_Number->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Committee_Meeting_Number->AdvancedSearch->SearchOperator = @$_GET["z_Committee_Meeting_Number"];

		// Committee Date
		$this->Committee_Date->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Committee_Date"]);
		if ($this->Committee_Date->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Committee_Date->AdvancedSearch->SearchOperator = @$_GET["z_Committee_Date"];

		// Notes
		$this->Notes->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Notes"]);
		if ($this->Notes->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Notes->AdvancedSearch->SearchOperator = @$_GET["z_Notes"];
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

			// Committee Date
			$this->Committee_Date->LinkCustomAttributes = "";
			$this->Committee_Date->HrefValue = "";
			$this->Committee_Date->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// ID
			$this->ID->EditCustomAttributes = "";
			$this->ID->EditValue = ew_HtmlEncode($this->ID->AdvancedSearch->SearchValue);
			$this->ID->PlaceHolder = ew_RemoveHtml($this->ID->FldCaption());

			// Name
			$this->Name->EditCustomAttributes = "";
			$this->Name->EditValue = ew_HtmlEncode($this->Name->AdvancedSearch->SearchValue);
			$this->Name->PlaceHolder = ew_RemoveHtml($this->Name->FldCaption());

			// Country
			$this->Country->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT `NID`, `Nationality` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `countries`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->Country, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->Country->EditValue = $arwrk;

			// Civil ID
			$this->Civil_ID->EditCustomAttributes = "";
			$this->Civil_ID->EditValue = ew_HtmlEncode($this->Civil_ID->AdvancedSearch->SearchValue);
			$this->Civil_ID->PlaceHolder = ew_RemoveHtml($this->Civil_ID->FldCaption());

			// Sector
			$this->Sector->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT `ID`, `Sector` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `sectors`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->Sector, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->Sector->EditValue = $arwrk;

			// Job Title
			$this->Job_Title->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT `ID`, `Job Title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, `Sector ID` AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `job titles`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->Job_Title, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->Job_Title->EditValue = $arwrk;

			// Program
			$this->Program->EditCustomAttributes = "";
			$this->Program->EditValue = ew_HtmlEncode($this->Program->AdvancedSearch->SearchValue);
			$this->Program->PlaceHolder = ew_RemoveHtml($this->Program->FldCaption());

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

			// Committee Date
			$this->Committee_Date->EditCustomAttributes = "";
			$this->Committee_Date->EditValue = ew_HtmlEncode(ew_UnFormatDateTime($this->Committee_Date->AdvancedSearch->SearchValue, 0));
			$this->Committee_Date->PlaceHolder = ew_RemoveHtml($this->Committee_Date->FldCaption());
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
		$this->Name->AdvancedSearch->Load();
		$this->Country->AdvancedSearch->Load();
		$this->Civil_ID->AdvancedSearch->Load();
		$this->Passport_No2E->AdvancedSearch->Load();
		$this->Sector->AdvancedSearch->Load();
		$this->Job_Title->AdvancedSearch->Load();
		$this->Program->AdvancedSearch->Load();
		$this->College->AdvancedSearch->Load();
		$this->Department->AdvancedSearch->Load();
		$this->Bachelors_Title->AdvancedSearch->Load();
		$this->Bachelor_University->AdvancedSearch->Load();
		$this->Bachelors_Major->AdvancedSearch->Load();
		$this->Bachelors_GPA->AdvancedSearch->Load();
		$this->Bachelors_MGPA->AdvancedSearch->Load();
		$this->Other_Bachelors_Title->AdvancedSearch->Load();
		$this->Other_Bachelors_University->AdvancedSearch->Load();
		$this->Other_Bachelors_Major->AdvancedSearch->Load();
		$this->Other_Bachelors_GPA->AdvancedSearch->Load();
		$this->Other_Bachelors_MGPA->AdvancedSearch->Load();
		$this->Masters_Degree_Title->AdvancedSearch->Load();
		$this->Master_University->AdvancedSearch->Load();
		$this->Masters_Degree_Major->AdvancedSearch->Load();
		$this->Masters_GPA->AdvancedSearch->Load();
		$this->Other_Masters_Degree_Title->AdvancedSearch->Load();
		$this->Other_Masters_University->AdvancedSearch->Load();
		$this->Other_Masters_Major->AdvancedSearch->Load();
		$this->Other_Masters_GPA->AdvancedSearch->Load();
		$this->PhD_Title->AdvancedSearch->Load();
		$this->Phd_University->AdvancedSearch->Load();
		$this->PhD_Major->AdvancedSearch->Load();
		$this->Phd_Degree_Equivalency->AdvancedSearch->Load();
		$this->Committee_Meeting->AdvancedSearch->Load();
		$this->Committee_Meeting_Number->AdvancedSearch->Load();
		$this->Committee_Date->AdvancedSearch->Load();
		$this->Notes->AdvancedSearch->Load();
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
		$item->Body = "<a id=\"emf_gpa_equivalency\" href=\"javascript:void(0);\" class=\"ewExportLink ewEmail\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_gpa_equivalency',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.fgpa_equivalencylist,sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
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
		$table = 'gpa equivalency';
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
if (!isset($gpa_equivalency_list)) $gpa_equivalency_list = new cgpa_equivalency_list();

// Page init
$gpa_equivalency_list->Page_Init();

// Page main
$gpa_equivalency_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$gpa_equivalency_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($gpa_equivalency->Export == "") { ?>
<script type="text/javascript">

// Page object
var gpa_equivalency_list = new ew_Page("gpa_equivalency_list");
gpa_equivalency_list.PageID = "list"; // Page ID
var EW_PAGE_ID = gpa_equivalency_list.PageID; // For backward compatibility

// Form object
var fgpa_equivalencylist = new ew_Form("fgpa_equivalencylist");
fgpa_equivalencylist.FormKeyCountName = '<?php echo $gpa_equivalency_list->FormKeyCountName ?>';

// Form_CustomValidate event
fgpa_equivalencylist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fgpa_equivalencylist.ValidateRequired = true;
<?php } else { ?>
fgpa_equivalencylist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fgpa_equivalencylist.Lists["x_Country"] = {"LinkField":"x_NID","Ajax":null,"AutoFill":false,"DisplayFields":["x_Nationality","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fgpa_equivalencylist.Lists["x_Sector"] = {"LinkField":"x_ID","Ajax":null,"AutoFill":false,"DisplayFields":["x_Sector","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fgpa_equivalencylist.Lists["x_Job_Title"] = {"LinkField":"x_ID","Ajax":null,"AutoFill":false,"DisplayFields":["x_Job_Title","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fgpa_equivalencylist.Lists["x_College"] = {"LinkField":"x_CollegeID","Ajax":null,"AutoFill":false,"DisplayFields":["x_College_Name_AR","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fgpa_equivalencylist.Lists["x_Department"] = {"LinkField":"x_DID","Ajax":null,"AutoFill":false,"DisplayFields":["x_DepartmentName2DAR","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
var fgpa_equivalencylistsrch = new ew_Form("fgpa_equivalencylistsrch");

// Validate function for search
fgpa_equivalencylistsrch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	this.PostAutoSuggest();
	var infix = "";
	elm = this.GetElements("x" + infix + "_Committee_Date");
	if (elm && !ew_CheckEuroDate(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($gpa_equivalency->Committee_Date->FldErrMsg()) ?>");

	// Set up row object
	ew_ElementsToRow(fobj);

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate event
fgpa_equivalencylistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fgpa_equivalencylistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
fgpa_equivalencylistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
fgpa_equivalencylistsrch.Lists["x_Country"] = {"LinkField":"x_NID","Ajax":null,"AutoFill":false,"DisplayFields":["x_Nationality","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fgpa_equivalencylistsrch.Lists["x_Sector"] = {"LinkField":"x_ID","Ajax":null,"AutoFill":false,"DisplayFields":["x_Sector","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fgpa_equivalencylistsrch.Lists["x_Job_Title"] = {"LinkField":"x_ID","Ajax":null,"AutoFill":false,"DisplayFields":["x_Job_Title","","",""],"ParentFields":["x_Sector"],"FilterFields":["x_Sector_ID"],"Options":[]};
fgpa_equivalencylistsrch.Lists["x_College"] = {"LinkField":"x_CollegeID","Ajax":null,"AutoFill":false,"DisplayFields":["x_College_Name_AR","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fgpa_equivalencylistsrch.Lists["x_Department"] = {"LinkField":"x_DID","Ajax":null,"AutoFill":false,"DisplayFields":["x_DepartmentName2DAR","","",""],"ParentFields":["x_College"],"FilterFields":["x_CID"],"Options":[]};
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($gpa_equivalency->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($gpa_equivalency_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $gpa_equivalency_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$gpa_equivalency_list->TotalRecs = $gpa_equivalency->SelectRecordCount();
	} else {
		if ($gpa_equivalency_list->Recordset = $gpa_equivalency_list->LoadRecordset())
			$gpa_equivalency_list->TotalRecs = $gpa_equivalency_list->Recordset->RecordCount();
	}
	$gpa_equivalency_list->StartRec = 1;
	if ($gpa_equivalency_list->DisplayRecs <= 0 || ($gpa_equivalency->Export <> "" && $gpa_equivalency->ExportAll)) // Display all records
		$gpa_equivalency_list->DisplayRecs = $gpa_equivalency_list->TotalRecs;
	if (!($gpa_equivalency->Export <> "" && $gpa_equivalency->ExportAll))
		$gpa_equivalency_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$gpa_equivalency_list->Recordset = $gpa_equivalency_list->LoadRecordset($gpa_equivalency_list->StartRec-1, $gpa_equivalency_list->DisplayRecs);
$gpa_equivalency_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($gpa_equivalency->Export == "" && $gpa_equivalency->CurrentAction == "") { ?>
<form name="fgpa_equivalencylistsrch" id="fgpa_equivalencylistsrch" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>">
<div class="accordion ewDisplayTable ewSearchTable" id="fgpa_equivalencylistsrch_SearchGroup">
	<div class="accordion-group">
		<div class="accordion-heading">
<a class="accordion-toggle" data-toggle="collapse" data-parent="#fgpa_equivalencylistsrch_SearchGroup" href="#fgpa_equivalencylistsrch_SearchBody"><?php echo $Language->Phrase("Search") ?></a>
		</div>
		<div id="fgpa_equivalencylistsrch_SearchBody" class="accordion-body collapse in">
			<div class="accordion-inner">
<div id="fgpa_equivalencylistsrch_SearchPanel">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="gpa_equivalency">
<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$gpa_equivalency_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$gpa_equivalency->RowType = EW_ROWTYPE_SEARCH;

// Render row
$gpa_equivalency->ResetAttrs();
$gpa_equivalency_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($gpa_equivalency->Name->Visible) { // Name ?>
	<span id="xsc_Name" class="ewCell">
		<span class="ewSearchCaption"><?php echo $gpa_equivalency->Name->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_Name" id="z_Name" value="LIKE"></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_Name" name="x_Name" id="x_Name" size="50" maxlength="100" placeholder="<?php echo ew_HtmlEncode($gpa_equivalency->Name->PlaceHolder) ?>" value="<?php echo $gpa_equivalency->Name->EditValue ?>"<?php echo $gpa_equivalency->Name->EditAttributes() ?>>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
<?php if ($gpa_equivalency->Country->Visible) { // Country ?>
	<span id="xsc_Country" class="ewCell">
		<span class="ewSearchCaption"><?php echo $gpa_equivalency->Country->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_Country" id="z_Country" value="="></span>
		<span class="control-group ewSearchField">
<select data-field="x_Country" id="x_Country" name="x_Country"<?php echo $gpa_equivalency->Country->EditAttributes() ?>>
<?php
if (is_array($gpa_equivalency->Country->EditValue)) {
	$arwrk = $gpa_equivalency->Country->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($gpa_equivalency->Country->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fgpa_equivalencylistsrch.Lists["x_Country"].Options = <?php echo (is_array($gpa_equivalency->Country->EditValue)) ? ew_ArrayToJson($gpa_equivalency->Country->EditValue, 1) : "[]" ?>;
</script>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_3" class="ewRow">
<?php if ($gpa_equivalency->Civil_ID->Visible) { // Civil ID ?>
	<span id="xsc_Civil_ID" class="ewCell">
		<span class="ewSearchCaption"><?php echo $gpa_equivalency->Civil_ID->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_Civil_ID" id="z_Civil_ID" value="LIKE"></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_Civil_ID" name="x_Civil_ID" id="x_Civil_ID" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($gpa_equivalency->Civil_ID->PlaceHolder) ?>" value="<?php echo $gpa_equivalency->Civil_ID->EditValue ?>"<?php echo $gpa_equivalency->Civil_ID->EditAttributes() ?>>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_4" class="ewRow">
<?php if ($gpa_equivalency->Sector->Visible) { // Sector ?>
	<span id="xsc_Sector" class="ewCell">
		<span class="ewSearchCaption"><?php echo $gpa_equivalency->Sector->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_Sector" id="z_Sector" value="="></span>
		<span class="control-group ewSearchField">
<?php $gpa_equivalency->Sector->EditAttrs["onchange"] = "ew_UpdateOpt.call(this, ['x_Job_Title']); " . @$gpa_equivalency->Sector->EditAttrs["onchange"]; ?>
<select data-field="x_Sector" id="x_Sector" name="x_Sector"<?php echo $gpa_equivalency->Sector->EditAttributes() ?>>
<?php
if (is_array($gpa_equivalency->Sector->EditValue)) {
	$arwrk = $gpa_equivalency->Sector->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($gpa_equivalency->Sector->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fgpa_equivalencylistsrch.Lists["x_Sector"].Options = <?php echo (is_array($gpa_equivalency->Sector->EditValue)) ? ew_ArrayToJson($gpa_equivalency->Sector->EditValue, 1) : "[]" ?>;
</script>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_5" class="ewRow">
<?php if ($gpa_equivalency->Job_Title->Visible) { // Job Title ?>
	<span id="xsc_Job_Title" class="ewCell">
		<span class="ewSearchCaption"><?php echo $gpa_equivalency->Job_Title->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_Job_Title" id="z_Job_Title" value="="></span>
		<span class="control-group ewSearchField">
<select data-field="x_Job_Title" id="x_Job_Title" name="x_Job_Title"<?php echo $gpa_equivalency->Job_Title->EditAttributes() ?>>
<?php
if (is_array($gpa_equivalency->Job_Title->EditValue)) {
	$arwrk = $gpa_equivalency->Job_Title->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($gpa_equivalency->Job_Title->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fgpa_equivalencylistsrch.Lists["x_Job_Title"].Options = <?php echo (is_array($gpa_equivalency->Job_Title->EditValue)) ? ew_ArrayToJson($gpa_equivalency->Job_Title->EditValue, 1) : "[]" ?>;
</script>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_6" class="ewRow">
<?php if ($gpa_equivalency->College->Visible) { // College ?>
	<span id="xsc_College" class="ewCell">
		<span class="ewSearchCaption"><?php echo $gpa_equivalency->College->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_College" id="z_College" value="="></span>
		<span class="control-group ewSearchField">
<?php $gpa_equivalency->College->EditAttrs["onchange"] = "ew_UpdateOpt.call(this, ['x_Department']); " . @$gpa_equivalency->College->EditAttrs["onchange"]; ?>
<select data-field="x_College" id="x_College" name="x_College"<?php echo $gpa_equivalency->College->EditAttributes() ?>>
<?php
if (is_array($gpa_equivalency->College->EditValue)) {
	$arwrk = $gpa_equivalency->College->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($gpa_equivalency->College->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fgpa_equivalencylistsrch.Lists["x_College"].Options = <?php echo (is_array($gpa_equivalency->College->EditValue)) ? ew_ArrayToJson($gpa_equivalency->College->EditValue, 1) : "[]" ?>;
</script>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_7" class="ewRow">
<?php if ($gpa_equivalency->Department->Visible) { // Department ?>
	<span id="xsc_Department" class="ewCell">
		<span class="ewSearchCaption"><?php echo $gpa_equivalency->Department->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_Department" id="z_Department" value="LIKE"></span>
		<span class="control-group ewSearchField">
<select data-field="x_Department" id="x_Department" name="x_Department"<?php echo $gpa_equivalency->Department->EditAttributes() ?>>
<?php
if (is_array($gpa_equivalency->Department->EditValue)) {
	$arwrk = $gpa_equivalency->Department->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($gpa_equivalency->Department->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fgpa_equivalencylistsrch.Lists["x_Department"].Options = <?php echo (is_array($gpa_equivalency->Department->EditValue)) ? ew_ArrayToJson($gpa_equivalency->Department->EditValue, 1) : "[]" ?>;
</script>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_8" class="ewRow">
<?php if ($gpa_equivalency->Committee_Date->Visible) { // Committee Date ?>
	<span id="xsc_Committee_Date" class="ewCell">
		<span class="ewSearchCaption"><?php echo $gpa_equivalency->Committee_Date->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_Committee_Date" id="z_Committee_Date" value="LIKE"></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_Committee_Date" name="x_Committee_Date" id="x_Committee_Date" size="30" maxlength="8" placeholder="<?php echo ew_HtmlEncode($gpa_equivalency->Committee_Date->PlaceHolder) ?>" value="<?php echo $gpa_equivalency->Committee_Date->EditValue ?>"<?php echo $gpa_equivalency->Committee_Date->EditAttributes() ?>>
<?php if (!$gpa_equivalency->Committee_Date->ReadOnly && !$gpa_equivalency->Committee_Date->Disabled && @$gpa_equivalency->Committee_Date->EditAttrs["readonly"] == "" && @$gpa_equivalency->Committee_Date->EditAttrs["disabled"] == "") { ?>
<button id="cal_x_Committee_Date" name="cal_x_Committee_Date" class="btn" type="button"><img src="phpimages/calendar.png" alt="<?php echo $Language->Phrase("PickDate") ?>" title="<?php echo $Language->Phrase("PickDate") ?>" style="border: 0;"></button><script type="text/javascript">
ew_CreateCalendar("fgpa_equivalencylistsrch", "x_Committee_Date", "%d/%m/%Y");
</script>
<?php } ?>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_9" class="ewRow">
	<div class="btn-group ewButtonGroup">
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	<div class="btn-group ewButtonGroup">
	<a class="btn ewShowAll" href="<?php echo $gpa_equivalency_list->PageUrl() ?>cmd=reset"><?php echo $Language->Phrase("ShowAll") ?></a>
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
<?php $gpa_equivalency_list->ShowPageHeader(); ?>
<?php
$gpa_equivalency_list->ShowMessage();
?>
<table class="ewGrid"><tr><td class="ewGridContent">
<?php if ($gpa_equivalency->Export == "") { ?>
<div class="ewGridUpperPanel">
<?php if ($gpa_equivalency->CurrentAction <> "gridadd" && $gpa_equivalency->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager">
<tr><td>
<?php if (!isset($gpa_equivalency_list->Pager)) $gpa_equivalency_list->Pager = new cPrevNextPager($gpa_equivalency_list->StartRec, $gpa_equivalency_list->DisplayRecs, $gpa_equivalency_list->TotalRecs) ?>
<?php if ($gpa_equivalency_list->Pager->RecordCount > 0) { ?>
<table class="ewStdTable"><tbody><tr><td>
	<?php echo $Language->Phrase("Page") ?>&nbsp;
<div class="input-prepend input-append">
<!--first page button-->
	<?php if ($gpa_equivalency_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $gpa_equivalency_list->PageUrl() ?>start=<?php echo $gpa_equivalency_list->Pager->FirstButton->Start ?>"><i class="icon-step-backward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-step-backward"></i></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($gpa_equivalency_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $gpa_equivalency_list->PageUrl() ?>start=<?php echo $gpa_equivalency_list->Pager->PrevButton->Start ?>"><i class="icon-prev"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-prev"></i></a>
	<?php } ?>
<!--current page number-->
	<input class="input-mini" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $gpa_equivalency_list->Pager->CurrentPage ?>">
<!--next page button-->
	<?php if ($gpa_equivalency_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $gpa_equivalency_list->PageUrl() ?>start=<?php echo $gpa_equivalency_list->Pager->NextButton->Start ?>"><i class="icon-play"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-play"></i></a>
	<?php } ?>
<!--last page button-->
	<?php if ($gpa_equivalency_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $gpa_equivalency_list->PageUrl() ?>start=<?php echo $gpa_equivalency_list->Pager->LastButton->Start ?>"><i class="icon-step-forward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-step-forward"></i></a>
	<?php } ?>
</div>
	&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $gpa_equivalency_list->Pager->PageCount ?>
</td>
<td>
	&nbsp;&nbsp;&nbsp;&nbsp;
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $gpa_equivalency_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $gpa_equivalency_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $gpa_equivalency_list->Pager->RecordCount ?>
</td>
</tr></tbody></table>
<?php } else { ?>
	<?php if ($Security->CanList()) { ?>
	<?php if ($gpa_equivalency_list->SearchWhere == "0=101") { ?>
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
	foreach ($gpa_equivalency_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
</div>
<?php } ?>
<form name="fgpa_equivalencylist" id="fgpa_equivalencylist" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="gpa_equivalency">
<div id="gmp_gpa_equivalency" class="ewGridMiddlePanel">
<?php if ($gpa_equivalency_list->TotalRecs > 0) { ?>
<table id="tbl_gpa_equivalencylist" class="ewTable ewTableSeparate">
<?php echo $gpa_equivalency->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$gpa_equivalency_list->RenderListOptions();

// Render list options (header, left)
$gpa_equivalency_list->ListOptions->Render("header", "left");
?>
<?php if ($gpa_equivalency->ID->Visible) { // ID ?>
	<?php if ($gpa_equivalency->SortUrl($gpa_equivalency->ID) == "") { ?>
		<td><div id="elh_gpa_equivalency_ID" class="gpa_equivalency_ID"><div class="ewTableHeaderCaption"><?php echo $gpa_equivalency->ID->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $gpa_equivalency->SortUrl($gpa_equivalency->ID) ?>',1);"><div id="elh_gpa_equivalency_ID" class="gpa_equivalency_ID">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $gpa_equivalency->ID->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($gpa_equivalency->ID->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($gpa_equivalency->ID->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($gpa_equivalency->Name->Visible) { // Name ?>
	<?php if ($gpa_equivalency->SortUrl($gpa_equivalency->Name) == "") { ?>
		<td><div id="elh_gpa_equivalency_Name" class="gpa_equivalency_Name"><div class="ewTableHeaderCaption"><?php echo $gpa_equivalency->Name->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $gpa_equivalency->SortUrl($gpa_equivalency->Name) ?>',1);"><div id="elh_gpa_equivalency_Name" class="gpa_equivalency_Name">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $gpa_equivalency->Name->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($gpa_equivalency->Name->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($gpa_equivalency->Name->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($gpa_equivalency->Country->Visible) { // Country ?>
	<?php if ($gpa_equivalency->SortUrl($gpa_equivalency->Country) == "") { ?>
		<td><div id="elh_gpa_equivalency_Country" class="gpa_equivalency_Country"><div class="ewTableHeaderCaption"><?php echo $gpa_equivalency->Country->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $gpa_equivalency->SortUrl($gpa_equivalency->Country) ?>',1);"><div id="elh_gpa_equivalency_Country" class="gpa_equivalency_Country">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $gpa_equivalency->Country->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($gpa_equivalency->Country->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($gpa_equivalency->Country->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($gpa_equivalency->Civil_ID->Visible) { // Civil ID ?>
	<?php if ($gpa_equivalency->SortUrl($gpa_equivalency->Civil_ID) == "") { ?>
		<td><div id="elh_gpa_equivalency_Civil_ID" class="gpa_equivalency_Civil_ID"><div class="ewTableHeaderCaption"><?php echo $gpa_equivalency->Civil_ID->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $gpa_equivalency->SortUrl($gpa_equivalency->Civil_ID) ?>',1);"><div id="elh_gpa_equivalency_Civil_ID" class="gpa_equivalency_Civil_ID">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $gpa_equivalency->Civil_ID->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($gpa_equivalency->Civil_ID->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($gpa_equivalency->Civil_ID->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($gpa_equivalency->Sector->Visible) { // Sector ?>
	<?php if ($gpa_equivalency->SortUrl($gpa_equivalency->Sector) == "") { ?>
		<td><div id="elh_gpa_equivalency_Sector" class="gpa_equivalency_Sector"><div class="ewTableHeaderCaption"><?php echo $gpa_equivalency->Sector->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $gpa_equivalency->SortUrl($gpa_equivalency->Sector) ?>',1);"><div id="elh_gpa_equivalency_Sector" class="gpa_equivalency_Sector">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $gpa_equivalency->Sector->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($gpa_equivalency->Sector->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($gpa_equivalency->Sector->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($gpa_equivalency->Job_Title->Visible) { // Job Title ?>
	<?php if ($gpa_equivalency->SortUrl($gpa_equivalency->Job_Title) == "") { ?>
		<td><div id="elh_gpa_equivalency_Job_Title" class="gpa_equivalency_Job_Title"><div class="ewTableHeaderCaption"><?php echo $gpa_equivalency->Job_Title->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $gpa_equivalency->SortUrl($gpa_equivalency->Job_Title) ?>',1);"><div id="elh_gpa_equivalency_Job_Title" class="gpa_equivalency_Job_Title">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $gpa_equivalency->Job_Title->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($gpa_equivalency->Job_Title->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($gpa_equivalency->Job_Title->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($gpa_equivalency->Program->Visible) { // Program ?>
	<?php if ($gpa_equivalency->SortUrl($gpa_equivalency->Program) == "") { ?>
		<td><div id="elh_gpa_equivalency_Program" class="gpa_equivalency_Program"><div class="ewTableHeaderCaption"><?php echo $gpa_equivalency->Program->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $gpa_equivalency->SortUrl($gpa_equivalency->Program) ?>',1);"><div id="elh_gpa_equivalency_Program" class="gpa_equivalency_Program">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $gpa_equivalency->Program->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($gpa_equivalency->Program->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($gpa_equivalency->Program->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($gpa_equivalency->College->Visible) { // College ?>
	<?php if ($gpa_equivalency->SortUrl($gpa_equivalency->College) == "") { ?>
		<td><div id="elh_gpa_equivalency_College" class="gpa_equivalency_College"><div class="ewTableHeaderCaption"><?php echo $gpa_equivalency->College->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $gpa_equivalency->SortUrl($gpa_equivalency->College) ?>',1);"><div id="elh_gpa_equivalency_College" class="gpa_equivalency_College">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $gpa_equivalency->College->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($gpa_equivalency->College->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($gpa_equivalency->College->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($gpa_equivalency->Department->Visible) { // Department ?>
	<?php if ($gpa_equivalency->SortUrl($gpa_equivalency->Department) == "") { ?>
		<td><div id="elh_gpa_equivalency_Department" class="gpa_equivalency_Department"><div class="ewTableHeaderCaption"><?php echo $gpa_equivalency->Department->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $gpa_equivalency->SortUrl($gpa_equivalency->Department) ?>',1);"><div id="elh_gpa_equivalency_Department" class="gpa_equivalency_Department">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $gpa_equivalency->Department->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($gpa_equivalency->Department->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($gpa_equivalency->Department->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($gpa_equivalency->Committee_Date->Visible) { // Committee Date ?>
	<?php if ($gpa_equivalency->SortUrl($gpa_equivalency->Committee_Date) == "") { ?>
		<td><div id="elh_gpa_equivalency_Committee_Date" class="gpa_equivalency_Committee_Date"><div class="ewTableHeaderCaption"><?php echo $gpa_equivalency->Committee_Date->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $gpa_equivalency->SortUrl($gpa_equivalency->Committee_Date) ?>',1);"><div id="elh_gpa_equivalency_Committee_Date" class="gpa_equivalency_Committee_Date">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $gpa_equivalency->Committee_Date->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($gpa_equivalency->Committee_Date->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($gpa_equivalency->Committee_Date->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$gpa_equivalency_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($gpa_equivalency->ExportAll && $gpa_equivalency->Export <> "") {
	$gpa_equivalency_list->StopRec = $gpa_equivalency_list->TotalRecs;
} else {

	// Set the last record to display
	if ($gpa_equivalency_list->TotalRecs > $gpa_equivalency_list->StartRec + $gpa_equivalency_list->DisplayRecs - 1)
		$gpa_equivalency_list->StopRec = $gpa_equivalency_list->StartRec + $gpa_equivalency_list->DisplayRecs - 1;
	else
		$gpa_equivalency_list->StopRec = $gpa_equivalency_list->TotalRecs;
}
$gpa_equivalency_list->RecCnt = $gpa_equivalency_list->StartRec - 1;
if ($gpa_equivalency_list->Recordset && !$gpa_equivalency_list->Recordset->EOF) {
	$gpa_equivalency_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $gpa_equivalency_list->StartRec > 1)
		$gpa_equivalency_list->Recordset->Move($gpa_equivalency_list->StartRec - 1);
} elseif (!$gpa_equivalency->AllowAddDeleteRow && $gpa_equivalency_list->StopRec == 0) {
	$gpa_equivalency_list->StopRec = $gpa_equivalency->GridAddRowCount;
}

// Initialize aggregate
$gpa_equivalency->RowType = EW_ROWTYPE_AGGREGATEINIT;
$gpa_equivalency->ResetAttrs();
$gpa_equivalency_list->RenderRow();
while ($gpa_equivalency_list->RecCnt < $gpa_equivalency_list->StopRec) {
	$gpa_equivalency_list->RecCnt++;
	if (intval($gpa_equivalency_list->RecCnt) >= intval($gpa_equivalency_list->StartRec)) {
		$gpa_equivalency_list->RowCnt++;

		// Set up key count
		$gpa_equivalency_list->KeyCount = $gpa_equivalency_list->RowIndex;

		// Init row class and style
		$gpa_equivalency->ResetAttrs();
		$gpa_equivalency->CssClass = "";
		if ($gpa_equivalency->CurrentAction == "gridadd") {
		} else {
			$gpa_equivalency_list->LoadRowValues($gpa_equivalency_list->Recordset); // Load row values
		}
		$gpa_equivalency->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$gpa_equivalency->RowAttrs = array_merge($gpa_equivalency->RowAttrs, array('data-rowindex'=>$gpa_equivalency_list->RowCnt, 'id'=>'r' . $gpa_equivalency_list->RowCnt . '_gpa_equivalency', 'data-rowtype'=>$gpa_equivalency->RowType));

		// Render row
		$gpa_equivalency_list->RenderRow();

		// Render list options
		$gpa_equivalency_list->RenderListOptions();
?>
	<tr<?php echo $gpa_equivalency->RowAttributes() ?>>
<?php

// Render list options (body, left)
$gpa_equivalency_list->ListOptions->Render("body", "left", $gpa_equivalency_list->RowCnt);
?>
	<?php if ($gpa_equivalency->ID->Visible) { // ID ?>
		<td<?php echo $gpa_equivalency->ID->CellAttributes() ?>>
<span<?php echo $gpa_equivalency->ID->ViewAttributes() ?>>
<?php echo $gpa_equivalency->ID->ListViewValue() ?></span>
<a id="<?php echo $gpa_equivalency_list->PageObjName . "_row_" . $gpa_equivalency_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($gpa_equivalency->Name->Visible) { // Name ?>
		<td<?php echo $gpa_equivalency->Name->CellAttributes() ?>>
<span<?php echo $gpa_equivalency->Name->ViewAttributes() ?>>
<?php echo $gpa_equivalency->Name->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($gpa_equivalency->Country->Visible) { // Country ?>
		<td<?php echo $gpa_equivalency->Country->CellAttributes() ?>>
<span<?php echo $gpa_equivalency->Country->ViewAttributes() ?>>
<?php echo $gpa_equivalency->Country->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($gpa_equivalency->Civil_ID->Visible) { // Civil ID ?>
		<td<?php echo $gpa_equivalency->Civil_ID->CellAttributes() ?>>
<span<?php echo $gpa_equivalency->Civil_ID->ViewAttributes() ?>>
<?php echo $gpa_equivalency->Civil_ID->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($gpa_equivalency->Sector->Visible) { // Sector ?>
		<td<?php echo $gpa_equivalency->Sector->CellAttributes() ?>>
<span<?php echo $gpa_equivalency->Sector->ViewAttributes() ?>>
<?php echo $gpa_equivalency->Sector->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($gpa_equivalency->Job_Title->Visible) { // Job Title ?>
		<td<?php echo $gpa_equivalency->Job_Title->CellAttributes() ?>>
<span<?php echo $gpa_equivalency->Job_Title->ViewAttributes() ?>>
<?php echo $gpa_equivalency->Job_Title->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($gpa_equivalency->Program->Visible) { // Program ?>
		<td<?php echo $gpa_equivalency->Program->CellAttributes() ?>>
<span<?php echo $gpa_equivalency->Program->ViewAttributes() ?>>
<?php echo $gpa_equivalency->Program->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($gpa_equivalency->College->Visible) { // College ?>
		<td<?php echo $gpa_equivalency->College->CellAttributes() ?>>
<span<?php echo $gpa_equivalency->College->ViewAttributes() ?>>
<?php echo $gpa_equivalency->College->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($gpa_equivalency->Department->Visible) { // Department ?>
		<td<?php echo $gpa_equivalency->Department->CellAttributes() ?>>
<span<?php echo $gpa_equivalency->Department->ViewAttributes() ?>>
<?php echo $gpa_equivalency->Department->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($gpa_equivalency->Committee_Date->Visible) { // Committee Date ?>
		<td<?php echo $gpa_equivalency->Committee_Date->CellAttributes() ?>>
<span<?php echo $gpa_equivalency->Committee_Date->ViewAttributes() ?>>
<?php echo $gpa_equivalency->Committee_Date->ListViewValue() ?></span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$gpa_equivalency_list->ListOptions->Render("body", "right", $gpa_equivalency_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($gpa_equivalency->CurrentAction <> "gridadd")
		$gpa_equivalency_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($gpa_equivalency->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($gpa_equivalency_list->Recordset)
	$gpa_equivalency_list->Recordset->Close();
?>
<?php if ($gpa_equivalency_list->TotalRecs > 0) { ?>
<?php if ($gpa_equivalency->Export == "") { ?>
<div class="ewGridLowerPanel">
<?php if ($gpa_equivalency->CurrentAction <> "gridadd" && $gpa_equivalency->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager">
<tr><td>
<?php if (!isset($gpa_equivalency_list->Pager)) $gpa_equivalency_list->Pager = new cPrevNextPager($gpa_equivalency_list->StartRec, $gpa_equivalency_list->DisplayRecs, $gpa_equivalency_list->TotalRecs) ?>
<?php if ($gpa_equivalency_list->Pager->RecordCount > 0) { ?>
<table class="ewStdTable"><tbody><tr><td>
	<?php echo $Language->Phrase("Page") ?>&nbsp;
<div class="input-prepend input-append">
<!--first page button-->
	<?php if ($gpa_equivalency_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $gpa_equivalency_list->PageUrl() ?>start=<?php echo $gpa_equivalency_list->Pager->FirstButton->Start ?>"><i class="icon-step-backward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-step-backward"></i></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($gpa_equivalency_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $gpa_equivalency_list->PageUrl() ?>start=<?php echo $gpa_equivalency_list->Pager->PrevButton->Start ?>"><i class="icon-prev"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-prev"></i></a>
	<?php } ?>
<!--current page number-->
	<input class="input-mini" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $gpa_equivalency_list->Pager->CurrentPage ?>">
<!--next page button-->
	<?php if ($gpa_equivalency_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $gpa_equivalency_list->PageUrl() ?>start=<?php echo $gpa_equivalency_list->Pager->NextButton->Start ?>"><i class="icon-play"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-play"></i></a>
	<?php } ?>
<!--last page button-->
	<?php if ($gpa_equivalency_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $gpa_equivalency_list->PageUrl() ?>start=<?php echo $gpa_equivalency_list->Pager->LastButton->Start ?>"><i class="icon-step-forward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small disabled"><i class="icon-step-forward"></i></a>
	<?php } ?>
</div>
	&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $gpa_equivalency_list->Pager->PageCount ?>
</td>
<td>
	&nbsp;&nbsp;&nbsp;&nbsp;
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $gpa_equivalency_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $gpa_equivalency_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $gpa_equivalency_list->Pager->RecordCount ?>
</td>
</tr></tbody></table>
<?php } else { ?>
	<?php if ($Security->CanList()) { ?>
	<?php if ($gpa_equivalency_list->SearchWhere == "0=101") { ?>
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
	foreach ($gpa_equivalency_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
</div>
<?php } ?>
<?php } ?>
</td></tr></table>
<?php if ($gpa_equivalency->Export == "") { ?>
<script type="text/javascript">
fgpa_equivalencylistsrch.Init();
fgpa_equivalencylist.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$gpa_equivalency_list->ShowPageFooter();
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
$gpa_equivalency_list->Page_Terminate();
?>
