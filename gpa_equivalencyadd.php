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

$gpa_equivalency_add = NULL; // Initialize page object first

class cgpa_equivalency_add extends cgpa_equivalency {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{F8E6AD82-57C4-4BEE-8975-8D386184467A}";

	// Table name
	var $TableName = 'gpa equivalency';

	// Page object name
	var $PageObjName = 'gpa_equivalency_add';

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
	var $AuditTrailOnAdd = TRUE;

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

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'gpa equivalency', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();
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
		if (!$Security->CanAdd()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate("gpa_equivalencylist.php");
		}

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

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

		// Page Unload event
		$this->Page_Unload();

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
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $Priv = 0;
	var $OldRecordset;
	var $CopyRecord;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Process form if post back
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
			$this->CopyRecord = $this->LoadOldRecord(); // Load old recordset
			$this->LoadFormValues(); // Load form values
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["ID"] != "") {
				$this->ID->setQueryStringValue($_GET["ID"]);
				$this->setKey("ID", $this->ID->CurrentValue); // Set up key
			} else {
				$this->setKey("ID", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
				$this->LoadDefaultValues(); // Load default values
			}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Validate form if post back
		if (@$_POST["a_add"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues(); // Restore form values
				$this->setFailureMessage($gsFormError);
			}
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "C": // Copy an existing record
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("gpa_equivalencylist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "gpa_equivalencyview.php")
						$sReturnUrl = $this->GetViewUrl(); // View paging, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values
				}
		}

		// Render row based on row type
		$this->RowType = EW_ROWTYPE_ADD;  // Render add type

		// Render row
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm;

		// Get upload data
	}

	// Load default values
	function LoadDefaultValues() {
		$this->Name->CurrentValue = NULL;
		$this->Name->OldValue = $this->Name->CurrentValue;
		$this->Country->CurrentValue = NULL;
		$this->Country->OldValue = $this->Country->CurrentValue;
		$this->Civil_ID->CurrentValue = NULL;
		$this->Civil_ID->OldValue = $this->Civil_ID->CurrentValue;
		$this->Passport_No2E->CurrentValue = NULL;
		$this->Passport_No2E->OldValue = $this->Passport_No2E->CurrentValue;
		$this->Sector->CurrentValue = NULL;
		$this->Sector->OldValue = $this->Sector->CurrentValue;
		$this->Job_Title->CurrentValue = NULL;
		$this->Job_Title->OldValue = $this->Job_Title->CurrentValue;
		$this->Program->CurrentValue = NULL;
		$this->Program->OldValue = $this->Program->CurrentValue;
		$this->College->CurrentValue = NULL;
		$this->College->OldValue = $this->College->CurrentValue;
		$this->Department->CurrentValue = NULL;
		$this->Department->OldValue = $this->Department->CurrentValue;
		$this->Bachelors_Title->CurrentValue = NULL;
		$this->Bachelors_Title->OldValue = $this->Bachelors_Title->CurrentValue;
		$this->Bachelor_University->CurrentValue = NULL;
		$this->Bachelor_University->OldValue = $this->Bachelor_University->CurrentValue;
		$this->Bachelors_Major->CurrentValue = NULL;
		$this->Bachelors_Major->OldValue = $this->Bachelors_Major->CurrentValue;
		$this->Bachelors_GPA->CurrentValue = NULL;
		$this->Bachelors_GPA->OldValue = $this->Bachelors_GPA->CurrentValue;
		$this->Bachelors_MGPA->CurrentValue = NULL;
		$this->Bachelors_MGPA->OldValue = $this->Bachelors_MGPA->CurrentValue;
		$this->Other_Bachelors_Title->CurrentValue = NULL;
		$this->Other_Bachelors_Title->OldValue = $this->Other_Bachelors_Title->CurrentValue;
		$this->Other_Bachelors_University->CurrentValue = NULL;
		$this->Other_Bachelors_University->OldValue = $this->Other_Bachelors_University->CurrentValue;
		$this->Other_Bachelors_Major->CurrentValue = NULL;
		$this->Other_Bachelors_Major->OldValue = $this->Other_Bachelors_Major->CurrentValue;
		$this->Other_Bachelors_GPA->CurrentValue = NULL;
		$this->Other_Bachelors_GPA->OldValue = $this->Other_Bachelors_GPA->CurrentValue;
		$this->Other_Bachelors_MGPA->CurrentValue = NULL;
		$this->Other_Bachelors_MGPA->OldValue = $this->Other_Bachelors_MGPA->CurrentValue;
		$this->Masters_Degree_Title->CurrentValue = NULL;
		$this->Masters_Degree_Title->OldValue = $this->Masters_Degree_Title->CurrentValue;
		$this->Master_University->CurrentValue = NULL;
		$this->Master_University->OldValue = $this->Master_University->CurrentValue;
		$this->Masters_Degree_Major->CurrentValue = NULL;
		$this->Masters_Degree_Major->OldValue = $this->Masters_Degree_Major->CurrentValue;
		$this->Masters_GPA->CurrentValue = NULL;
		$this->Masters_GPA->OldValue = $this->Masters_GPA->CurrentValue;
		$this->Other_Masters_Degree_Title->CurrentValue = NULL;
		$this->Other_Masters_Degree_Title->OldValue = $this->Other_Masters_Degree_Title->CurrentValue;
		$this->Other_Masters_University->CurrentValue = NULL;
		$this->Other_Masters_University->OldValue = $this->Other_Masters_University->CurrentValue;
		$this->Other_Masters_Major->CurrentValue = NULL;
		$this->Other_Masters_Major->OldValue = $this->Other_Masters_Major->CurrentValue;
		$this->Other_Masters_GPA->CurrentValue = NULL;
		$this->Other_Masters_GPA->OldValue = $this->Other_Masters_GPA->CurrentValue;
		$this->PhD_Title->CurrentValue = NULL;
		$this->PhD_Title->OldValue = $this->PhD_Title->CurrentValue;
		$this->Phd_University->CurrentValue = NULL;
		$this->Phd_University->OldValue = $this->Phd_University->CurrentValue;
		$this->PhD_Major->CurrentValue = NULL;
		$this->PhD_Major->OldValue = $this->PhD_Major->CurrentValue;
		$this->Phd_Degree_Equivalency->CurrentValue = NULL;
		$this->Phd_Degree_Equivalency->OldValue = $this->Phd_Degree_Equivalency->CurrentValue;
		$this->Committee_Meeting->CurrentValue = NULL;
		$this->Committee_Meeting->OldValue = $this->Committee_Meeting->CurrentValue;
		$this->Committee_Meeting_Number->CurrentValue = NULL;
		$this->Committee_Meeting_Number->OldValue = $this->Committee_Meeting_Number->CurrentValue;
		$this->Committee_Date->CurrentValue = NULL;
		$this->Committee_Date->OldValue = $this->Committee_Date->CurrentValue;
		$this->Notes->CurrentValue = NULL;
		$this->Notes->OldValue = $this->Notes->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->Name->FldIsDetailKey) {
			$this->Name->setFormValue($objForm->GetValue("x_Name"));
		}
		if (!$this->Country->FldIsDetailKey) {
			$this->Country->setFormValue($objForm->GetValue("x_Country"));
		}
		if (!$this->Civil_ID->FldIsDetailKey) {
			$this->Civil_ID->setFormValue($objForm->GetValue("x_Civil_ID"));
		}
		if (!$this->Passport_No2E->FldIsDetailKey) {
			$this->Passport_No2E->setFormValue($objForm->GetValue("x_Passport_No2E"));
		}
		if (!$this->Sector->FldIsDetailKey) {
			$this->Sector->setFormValue($objForm->GetValue("x_Sector"));
		}
		if (!$this->Job_Title->FldIsDetailKey) {
			$this->Job_Title->setFormValue($objForm->GetValue("x_Job_Title"));
		}
		if (!$this->Program->FldIsDetailKey) {
			$this->Program->setFormValue($objForm->GetValue("x_Program"));
		}
		if (!$this->College->FldIsDetailKey) {
			$this->College->setFormValue($objForm->GetValue("x_College"));
		}
		if (!$this->Department->FldIsDetailKey) {
			$this->Department->setFormValue($objForm->GetValue("x_Department"));
		}
		if (!$this->Bachelors_Title->FldIsDetailKey) {
			$this->Bachelors_Title->setFormValue($objForm->GetValue("x_Bachelors_Title"));
		}
		if (!$this->Bachelor_University->FldIsDetailKey) {
			$this->Bachelor_University->setFormValue($objForm->GetValue("x_Bachelor_University"));
		}
		if (!$this->Bachelors_Major->FldIsDetailKey) {
			$this->Bachelors_Major->setFormValue($objForm->GetValue("x_Bachelors_Major"));
		}
		if (!$this->Bachelors_GPA->FldIsDetailKey) {
			$this->Bachelors_GPA->setFormValue($objForm->GetValue("x_Bachelors_GPA"));
		}
		if (!$this->Bachelors_MGPA->FldIsDetailKey) {
			$this->Bachelors_MGPA->setFormValue($objForm->GetValue("x_Bachelors_MGPA"));
		}
		if (!$this->Other_Bachelors_Title->FldIsDetailKey) {
			$this->Other_Bachelors_Title->setFormValue($objForm->GetValue("x_Other_Bachelors_Title"));
		}
		if (!$this->Other_Bachelors_University->FldIsDetailKey) {
			$this->Other_Bachelors_University->setFormValue($objForm->GetValue("x_Other_Bachelors_University"));
		}
		if (!$this->Other_Bachelors_Major->FldIsDetailKey) {
			$this->Other_Bachelors_Major->setFormValue($objForm->GetValue("x_Other_Bachelors_Major"));
		}
		if (!$this->Other_Bachelors_GPA->FldIsDetailKey) {
			$this->Other_Bachelors_GPA->setFormValue($objForm->GetValue("x_Other_Bachelors_GPA"));
		}
		if (!$this->Other_Bachelors_MGPA->FldIsDetailKey) {
			$this->Other_Bachelors_MGPA->setFormValue($objForm->GetValue("x_Other_Bachelors_MGPA"));
		}
		if (!$this->Masters_Degree_Title->FldIsDetailKey) {
			$this->Masters_Degree_Title->setFormValue($objForm->GetValue("x_Masters_Degree_Title"));
		}
		if (!$this->Master_University->FldIsDetailKey) {
			$this->Master_University->setFormValue($objForm->GetValue("x_Master_University"));
		}
		if (!$this->Masters_Degree_Major->FldIsDetailKey) {
			$this->Masters_Degree_Major->setFormValue($objForm->GetValue("x_Masters_Degree_Major"));
		}
		if (!$this->Masters_GPA->FldIsDetailKey) {
			$this->Masters_GPA->setFormValue($objForm->GetValue("x_Masters_GPA"));
		}
		if (!$this->Other_Masters_Degree_Title->FldIsDetailKey) {
			$this->Other_Masters_Degree_Title->setFormValue($objForm->GetValue("x_Other_Masters_Degree_Title"));
		}
		if (!$this->Other_Masters_University->FldIsDetailKey) {
			$this->Other_Masters_University->setFormValue($objForm->GetValue("x_Other_Masters_University"));
		}
		if (!$this->Other_Masters_Major->FldIsDetailKey) {
			$this->Other_Masters_Major->setFormValue($objForm->GetValue("x_Other_Masters_Major"));
		}
		if (!$this->Other_Masters_GPA->FldIsDetailKey) {
			$this->Other_Masters_GPA->setFormValue($objForm->GetValue("x_Other_Masters_GPA"));
		}
		if (!$this->PhD_Title->FldIsDetailKey) {
			$this->PhD_Title->setFormValue($objForm->GetValue("x_PhD_Title"));
		}
		if (!$this->Phd_University->FldIsDetailKey) {
			$this->Phd_University->setFormValue($objForm->GetValue("x_Phd_University"));
		}
		if (!$this->PhD_Major->FldIsDetailKey) {
			$this->PhD_Major->setFormValue($objForm->GetValue("x_PhD_Major"));
		}
		if (!$this->Phd_Degree_Equivalency->FldIsDetailKey) {
			$this->Phd_Degree_Equivalency->setFormValue($objForm->GetValue("x_Phd_Degree_Equivalency"));
		}
		if (!$this->Committee_Meeting->FldIsDetailKey) {
			$this->Committee_Meeting->setFormValue($objForm->GetValue("x_Committee_Meeting"));
		}
		if (!$this->Committee_Meeting_Number->FldIsDetailKey) {
			$this->Committee_Meeting_Number->setFormValue($objForm->GetValue("x_Committee_Meeting_Number"));
		}
		if (!$this->Committee_Date->FldIsDetailKey) {
			$this->Committee_Date->setFormValue($objForm->GetValue("x_Committee_Date"));
			$this->Committee_Date->CurrentValue = ew_UnFormatDateTime($this->Committee_Date->CurrentValue, 0);
		}
		if (!$this->Notes->FldIsDetailKey) {
			$this->Notes->setFormValue($objForm->GetValue("x_Notes"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->Name->CurrentValue = $this->Name->FormValue;
		$this->Country->CurrentValue = $this->Country->FormValue;
		$this->Civil_ID->CurrentValue = $this->Civil_ID->FormValue;
		$this->Passport_No2E->CurrentValue = $this->Passport_No2E->FormValue;
		$this->Sector->CurrentValue = $this->Sector->FormValue;
		$this->Job_Title->CurrentValue = $this->Job_Title->FormValue;
		$this->Program->CurrentValue = $this->Program->FormValue;
		$this->College->CurrentValue = $this->College->FormValue;
		$this->Department->CurrentValue = $this->Department->FormValue;
		$this->Bachelors_Title->CurrentValue = $this->Bachelors_Title->FormValue;
		$this->Bachelor_University->CurrentValue = $this->Bachelor_University->FormValue;
		$this->Bachelors_Major->CurrentValue = $this->Bachelors_Major->FormValue;
		$this->Bachelors_GPA->CurrentValue = $this->Bachelors_GPA->FormValue;
		$this->Bachelors_MGPA->CurrentValue = $this->Bachelors_MGPA->FormValue;
		$this->Other_Bachelors_Title->CurrentValue = $this->Other_Bachelors_Title->FormValue;
		$this->Other_Bachelors_University->CurrentValue = $this->Other_Bachelors_University->FormValue;
		$this->Other_Bachelors_Major->CurrentValue = $this->Other_Bachelors_Major->FormValue;
		$this->Other_Bachelors_GPA->CurrentValue = $this->Other_Bachelors_GPA->FormValue;
		$this->Other_Bachelors_MGPA->CurrentValue = $this->Other_Bachelors_MGPA->FormValue;
		$this->Masters_Degree_Title->CurrentValue = $this->Masters_Degree_Title->FormValue;
		$this->Master_University->CurrentValue = $this->Master_University->FormValue;
		$this->Masters_Degree_Major->CurrentValue = $this->Masters_Degree_Major->FormValue;
		$this->Masters_GPA->CurrentValue = $this->Masters_GPA->FormValue;
		$this->Other_Masters_Degree_Title->CurrentValue = $this->Other_Masters_Degree_Title->FormValue;
		$this->Other_Masters_University->CurrentValue = $this->Other_Masters_University->FormValue;
		$this->Other_Masters_Major->CurrentValue = $this->Other_Masters_Major->FormValue;
		$this->Other_Masters_GPA->CurrentValue = $this->Other_Masters_GPA->FormValue;
		$this->PhD_Title->CurrentValue = $this->PhD_Title->FormValue;
		$this->Phd_University->CurrentValue = $this->Phd_University->FormValue;
		$this->PhD_Major->CurrentValue = $this->PhD_Major->FormValue;
		$this->Phd_Degree_Equivalency->CurrentValue = $this->Phd_Degree_Equivalency->FormValue;
		$this->Committee_Meeting->CurrentValue = $this->Committee_Meeting->FormValue;
		$this->Committee_Meeting_Number->CurrentValue = $this->Committee_Meeting_Number->FormValue;
		$this->Committee_Date->CurrentValue = $this->Committee_Date->FormValue;
		$this->Committee_Date->CurrentValue = ew_UnFormatDateTime($this->Committee_Date->CurrentValue, 0);
		$this->Notes->CurrentValue = $this->Notes->FormValue;
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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// Name
			$this->Name->EditCustomAttributes = "";
			$this->Name->EditValue = ew_HtmlEncode($this->Name->CurrentValue);
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
			$this->Civil_ID->EditValue = ew_HtmlEncode($this->Civil_ID->CurrentValue);
			$this->Civil_ID->PlaceHolder = ew_RemoveHtml($this->Civil_ID->FldCaption());

			// Passport No.
			$this->Passport_No2E->EditCustomAttributes = "";
			$this->Passport_No2E->EditValue = ew_HtmlEncode($this->Passport_No2E->CurrentValue);
			$this->Passport_No2E->PlaceHolder = ew_RemoveHtml($this->Passport_No2E->FldCaption());

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
			$this->Program->EditValue = ew_HtmlEncode($this->Program->CurrentValue);
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

			// Bachelors Title
			$this->Bachelors_Title->EditCustomAttributes = "";
			$this->Bachelors_Title->EditValue = ew_HtmlEncode($this->Bachelors_Title->CurrentValue);
			$this->Bachelors_Title->PlaceHolder = ew_RemoveHtml($this->Bachelors_Title->FldCaption());

			// Bachelor University
			$this->Bachelor_University->EditCustomAttributes = "";
			$this->Bachelor_University->EditValue = ew_HtmlEncode($this->Bachelor_University->CurrentValue);
			$this->Bachelor_University->PlaceHolder = ew_RemoveHtml($this->Bachelor_University->FldCaption());

			// Bachelors Major
			$this->Bachelors_Major->EditCustomAttributes = "";
			$this->Bachelors_Major->EditValue = ew_HtmlEncode($this->Bachelors_Major->CurrentValue);
			$this->Bachelors_Major->PlaceHolder = ew_RemoveHtml($this->Bachelors_Major->FldCaption());

			// Bachelors GPA
			$this->Bachelors_GPA->EditCustomAttributes = "";
			$this->Bachelors_GPA->EditValue = ew_HtmlEncode($this->Bachelors_GPA->CurrentValue);
			$this->Bachelors_GPA->PlaceHolder = ew_RemoveHtml($this->Bachelors_GPA->FldCaption());

			// Bachelors MGPA
			$this->Bachelors_MGPA->EditCustomAttributes = "";
			$this->Bachelors_MGPA->EditValue = ew_HtmlEncode($this->Bachelors_MGPA->CurrentValue);
			$this->Bachelors_MGPA->PlaceHolder = ew_RemoveHtml($this->Bachelors_MGPA->FldCaption());

			// Other Bachelors Title
			$this->Other_Bachelors_Title->EditCustomAttributes = "";
			$this->Other_Bachelors_Title->EditValue = ew_HtmlEncode($this->Other_Bachelors_Title->CurrentValue);
			$this->Other_Bachelors_Title->PlaceHolder = ew_RemoveHtml($this->Other_Bachelors_Title->FldCaption());

			// Other Bachelors University
			$this->Other_Bachelors_University->EditCustomAttributes = "";
			$this->Other_Bachelors_University->EditValue = ew_HtmlEncode($this->Other_Bachelors_University->CurrentValue);
			$this->Other_Bachelors_University->PlaceHolder = ew_RemoveHtml($this->Other_Bachelors_University->FldCaption());

			// Other Bachelors Major
			$this->Other_Bachelors_Major->EditCustomAttributes = "";
			$this->Other_Bachelors_Major->EditValue = ew_HtmlEncode($this->Other_Bachelors_Major->CurrentValue);
			$this->Other_Bachelors_Major->PlaceHolder = ew_RemoveHtml($this->Other_Bachelors_Major->FldCaption());

			// Other Bachelors GPA
			$this->Other_Bachelors_GPA->EditCustomAttributes = "";
			$this->Other_Bachelors_GPA->EditValue = ew_HtmlEncode($this->Other_Bachelors_GPA->CurrentValue);
			$this->Other_Bachelors_GPA->PlaceHolder = ew_RemoveHtml($this->Other_Bachelors_GPA->FldCaption());

			// Other Bachelors MGPA
			$this->Other_Bachelors_MGPA->EditCustomAttributes = "";
			$this->Other_Bachelors_MGPA->EditValue = ew_HtmlEncode($this->Other_Bachelors_MGPA->CurrentValue);
			$this->Other_Bachelors_MGPA->PlaceHolder = ew_RemoveHtml($this->Other_Bachelors_MGPA->FldCaption());

			// Masters Degree Title
			$this->Masters_Degree_Title->EditCustomAttributes = "";
			$this->Masters_Degree_Title->EditValue = ew_HtmlEncode($this->Masters_Degree_Title->CurrentValue);
			$this->Masters_Degree_Title->PlaceHolder = ew_RemoveHtml($this->Masters_Degree_Title->FldCaption());

			// Master University
			$this->Master_University->EditCustomAttributes = "";
			$this->Master_University->EditValue = ew_HtmlEncode($this->Master_University->CurrentValue);
			$this->Master_University->PlaceHolder = ew_RemoveHtml($this->Master_University->FldCaption());

			// Masters Degree Major
			$this->Masters_Degree_Major->EditCustomAttributes = "";
			$this->Masters_Degree_Major->EditValue = ew_HtmlEncode($this->Masters_Degree_Major->CurrentValue);
			$this->Masters_Degree_Major->PlaceHolder = ew_RemoveHtml($this->Masters_Degree_Major->FldCaption());

			// Masters GPA
			$this->Masters_GPA->EditCustomAttributes = "";
			$this->Masters_GPA->EditValue = ew_HtmlEncode($this->Masters_GPA->CurrentValue);
			$this->Masters_GPA->PlaceHolder = ew_RemoveHtml($this->Masters_GPA->FldCaption());

			// Other Masters Degree Title
			$this->Other_Masters_Degree_Title->EditCustomAttributes = "";
			$this->Other_Masters_Degree_Title->EditValue = ew_HtmlEncode($this->Other_Masters_Degree_Title->CurrentValue);
			$this->Other_Masters_Degree_Title->PlaceHolder = ew_RemoveHtml($this->Other_Masters_Degree_Title->FldCaption());

			// Other Masters University
			$this->Other_Masters_University->EditCustomAttributes = "";
			$this->Other_Masters_University->EditValue = ew_HtmlEncode($this->Other_Masters_University->CurrentValue);
			$this->Other_Masters_University->PlaceHolder = ew_RemoveHtml($this->Other_Masters_University->FldCaption());

			// Other Masters Major
			$this->Other_Masters_Major->EditCustomAttributes = "";
			$this->Other_Masters_Major->EditValue = ew_HtmlEncode($this->Other_Masters_Major->CurrentValue);
			$this->Other_Masters_Major->PlaceHolder = ew_RemoveHtml($this->Other_Masters_Major->FldCaption());

			// Other Masters GPA
			$this->Other_Masters_GPA->EditCustomAttributes = "";
			$this->Other_Masters_GPA->EditValue = ew_HtmlEncode($this->Other_Masters_GPA->CurrentValue);
			$this->Other_Masters_GPA->PlaceHolder = ew_RemoveHtml($this->Other_Masters_GPA->FldCaption());

			// PhD Title
			$this->PhD_Title->EditCustomAttributes = "";
			$this->PhD_Title->EditValue = ew_HtmlEncode($this->PhD_Title->CurrentValue);
			$this->PhD_Title->PlaceHolder = ew_RemoveHtml($this->PhD_Title->FldCaption());

			// Phd University
			$this->Phd_University->EditCustomAttributes = "";
			$this->Phd_University->EditValue = ew_HtmlEncode($this->Phd_University->CurrentValue);
			$this->Phd_University->PlaceHolder = ew_RemoveHtml($this->Phd_University->FldCaption());

			// PhD Major
			$this->PhD_Major->EditCustomAttributes = "";
			$this->PhD_Major->EditValue = ew_HtmlEncode($this->PhD_Major->CurrentValue);
			$this->PhD_Major->PlaceHolder = ew_RemoveHtml($this->PhD_Major->FldCaption());

			// Phd Degree Equivalency
			$this->Phd_Degree_Equivalency->EditCustomAttributes = "";
			$this->Phd_Degree_Equivalency->EditValue = ew_HtmlEncode($this->Phd_Degree_Equivalency->CurrentValue);
			$this->Phd_Degree_Equivalency->PlaceHolder = ew_RemoveHtml($this->Phd_Degree_Equivalency->FldCaption());

			// Committee Meeting
			$this->Committee_Meeting->EditCustomAttributes = "";
			$this->Committee_Meeting->EditValue = ew_HtmlEncode($this->Committee_Meeting->CurrentValue);
			$this->Committee_Meeting->PlaceHolder = ew_RemoveHtml($this->Committee_Meeting->FldCaption());

			// Committee Meeting Number
			$this->Committee_Meeting_Number->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->Committee_Meeting_Number->FldTagValue(1), $this->Committee_Meeting_Number->FldTagCaption(1) <> "" ? $this->Committee_Meeting_Number->FldTagCaption(1) : $this->Committee_Meeting_Number->FldTagValue(1));
			$arwrk[] = array($this->Committee_Meeting_Number->FldTagValue(2), $this->Committee_Meeting_Number->FldTagCaption(2) <> "" ? $this->Committee_Meeting_Number->FldTagCaption(2) : $this->Committee_Meeting_Number->FldTagValue(2));
			$arwrk[] = array($this->Committee_Meeting_Number->FldTagValue(3), $this->Committee_Meeting_Number->FldTagCaption(3) <> "" ? $this->Committee_Meeting_Number->FldTagCaption(3) : $this->Committee_Meeting_Number->FldTagValue(3));
			$arwrk[] = array($this->Committee_Meeting_Number->FldTagValue(4), $this->Committee_Meeting_Number->FldTagCaption(4) <> "" ? $this->Committee_Meeting_Number->FldTagCaption(4) : $this->Committee_Meeting_Number->FldTagValue(4));
			$arwrk[] = array($this->Committee_Meeting_Number->FldTagValue(5), $this->Committee_Meeting_Number->FldTagCaption(5) <> "" ? $this->Committee_Meeting_Number->FldTagCaption(5) : $this->Committee_Meeting_Number->FldTagValue(5));
			$arwrk[] = array($this->Committee_Meeting_Number->FldTagValue(6), $this->Committee_Meeting_Number->FldTagCaption(6) <> "" ? $this->Committee_Meeting_Number->FldTagCaption(6) : $this->Committee_Meeting_Number->FldTagValue(6));
			$arwrk[] = array($this->Committee_Meeting_Number->FldTagValue(7), $this->Committee_Meeting_Number->FldTagCaption(7) <> "" ? $this->Committee_Meeting_Number->FldTagCaption(7) : $this->Committee_Meeting_Number->FldTagValue(7));
			$arwrk[] = array($this->Committee_Meeting_Number->FldTagValue(8), $this->Committee_Meeting_Number->FldTagCaption(8) <> "" ? $this->Committee_Meeting_Number->FldTagCaption(8) : $this->Committee_Meeting_Number->FldTagValue(8));
			$arwrk[] = array($this->Committee_Meeting_Number->FldTagValue(9), $this->Committee_Meeting_Number->FldTagCaption(9) <> "" ? $this->Committee_Meeting_Number->FldTagCaption(9) : $this->Committee_Meeting_Number->FldTagValue(9));
			$arwrk[] = array($this->Committee_Meeting_Number->FldTagValue(10), $this->Committee_Meeting_Number->FldTagCaption(10) <> "" ? $this->Committee_Meeting_Number->FldTagCaption(10) : $this->Committee_Meeting_Number->FldTagValue(10));
			$arwrk[] = array($this->Committee_Meeting_Number->FldTagValue(11), $this->Committee_Meeting_Number->FldTagCaption(11) <> "" ? $this->Committee_Meeting_Number->FldTagCaption(11) : $this->Committee_Meeting_Number->FldTagValue(11));
			$arwrk[] = array($this->Committee_Meeting_Number->FldTagValue(12), $this->Committee_Meeting_Number->FldTagCaption(12) <> "" ? $this->Committee_Meeting_Number->FldTagCaption(12) : $this->Committee_Meeting_Number->FldTagValue(12));
			$arwrk[] = array($this->Committee_Meeting_Number->FldTagValue(13), $this->Committee_Meeting_Number->FldTagCaption(13) <> "" ? $this->Committee_Meeting_Number->FldTagCaption(13) : $this->Committee_Meeting_Number->FldTagValue(13));
			$arwrk[] = array($this->Committee_Meeting_Number->FldTagValue(14), $this->Committee_Meeting_Number->FldTagCaption(14) <> "" ? $this->Committee_Meeting_Number->FldTagCaption(14) : $this->Committee_Meeting_Number->FldTagValue(14));
			$arwrk[] = array($this->Committee_Meeting_Number->FldTagValue(15), $this->Committee_Meeting_Number->FldTagCaption(15) <> "" ? $this->Committee_Meeting_Number->FldTagCaption(15) : $this->Committee_Meeting_Number->FldTagValue(15));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->Committee_Meeting_Number->EditValue = $arwrk;

			// Committee Date
			$this->Committee_Date->EditCustomAttributes = "";
			$this->Committee_Date->EditValue = ew_HtmlEncode($this->Committee_Date->CurrentValue);
			$this->Committee_Date->PlaceHolder = ew_RemoveHtml($this->Committee_Date->FldCaption());

			// Notes
			$this->Notes->EditCustomAttributes = "";
			$this->Notes->EditValue = $this->Notes->CurrentValue;
			$this->Notes->PlaceHolder = ew_RemoveHtml($this->Notes->FldCaption());

			// Edit refer script
			// Name

			$this->Name->HrefValue = "";

			// Country
			$this->Country->HrefValue = "";

			// Civil ID
			$this->Civil_ID->HrefValue = "";

			// Passport No.
			$this->Passport_No2E->HrefValue = "";

			// Sector
			$this->Sector->HrefValue = "";

			// Job Title
			$this->Job_Title->HrefValue = "";

			// Program
			$this->Program->HrefValue = "";

			// College
			$this->College->HrefValue = "";

			// Department
			$this->Department->HrefValue = "";

			// Bachelors Title
			$this->Bachelors_Title->HrefValue = "";

			// Bachelor University
			$this->Bachelor_University->HrefValue = "";

			// Bachelors Major
			$this->Bachelors_Major->HrefValue = "";

			// Bachelors GPA
			$this->Bachelors_GPA->HrefValue = "";

			// Bachelors MGPA
			$this->Bachelors_MGPA->HrefValue = "";

			// Other Bachelors Title
			$this->Other_Bachelors_Title->HrefValue = "";

			// Other Bachelors University
			$this->Other_Bachelors_University->HrefValue = "";

			// Other Bachelors Major
			$this->Other_Bachelors_Major->HrefValue = "";

			// Other Bachelors GPA
			$this->Other_Bachelors_GPA->HrefValue = "";

			// Other Bachelors MGPA
			$this->Other_Bachelors_MGPA->HrefValue = "";

			// Masters Degree Title
			$this->Masters_Degree_Title->HrefValue = "";

			// Master University
			$this->Master_University->HrefValue = "";

			// Masters Degree Major
			$this->Masters_Degree_Major->HrefValue = "";

			// Masters GPA
			$this->Masters_GPA->HrefValue = "";

			// Other Masters Degree Title
			$this->Other_Masters_Degree_Title->HrefValue = "";

			// Other Masters University
			$this->Other_Masters_University->HrefValue = "";

			// Other Masters Major
			$this->Other_Masters_Major->HrefValue = "";

			// Other Masters GPA
			$this->Other_Masters_GPA->HrefValue = "";

			// PhD Title
			$this->PhD_Title->HrefValue = "";

			// Phd University
			$this->Phd_University->HrefValue = "";

			// PhD Major
			$this->PhD_Major->HrefValue = "";

			// Phd Degree Equivalency
			$this->Phd_Degree_Equivalency->HrefValue = "";

			// Committee Meeting
			$this->Committee_Meeting->HrefValue = "";

			// Committee Meeting Number
			$this->Committee_Meeting_Number->HrefValue = "";

			// Committee Date
			$this->Committee_Date->HrefValue = "";

			// Notes
			$this->Notes->HrefValue = "";
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

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!ew_CheckEuroDate($this->Committee_Date->FormValue)) {
			ew_AddMessage($gsFormError, $this->Committee_Date->FldErrMsg());
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

		// Country
		$this->Country->SetDbValueDef($rsnew, $this->Country->CurrentValue, NULL, FALSE);

		// Civil ID
		$this->Civil_ID->SetDbValueDef($rsnew, $this->Civil_ID->CurrentValue, NULL, FALSE);

		// Passport No.
		$this->Passport_No2E->SetDbValueDef($rsnew, $this->Passport_No2E->CurrentValue, NULL, FALSE);

		// Sector
		$this->Sector->SetDbValueDef($rsnew, $this->Sector->CurrentValue, NULL, FALSE);

		// Job Title
		$this->Job_Title->SetDbValueDef($rsnew, $this->Job_Title->CurrentValue, NULL, FALSE);

		// Program
		$this->Program->SetDbValueDef($rsnew, $this->Program->CurrentValue, NULL, FALSE);

		// College
		$this->College->SetDbValueDef($rsnew, $this->College->CurrentValue, NULL, FALSE);

		// Department
		$this->Department->SetDbValueDef($rsnew, $this->Department->CurrentValue, NULL, FALSE);

		// Bachelors Title
		$this->Bachelors_Title->SetDbValueDef($rsnew, $this->Bachelors_Title->CurrentValue, NULL, FALSE);

		// Bachelor University
		$this->Bachelor_University->SetDbValueDef($rsnew, $this->Bachelor_University->CurrentValue, NULL, FALSE);

		// Bachelors Major
		$this->Bachelors_Major->SetDbValueDef($rsnew, $this->Bachelors_Major->CurrentValue, NULL, FALSE);

		// Bachelors GPA
		$this->Bachelors_GPA->SetDbValueDef($rsnew, $this->Bachelors_GPA->CurrentValue, NULL, FALSE);

		// Bachelors MGPA
		$this->Bachelors_MGPA->SetDbValueDef($rsnew, $this->Bachelors_MGPA->CurrentValue, NULL, FALSE);

		// Other Bachelors Title
		$this->Other_Bachelors_Title->SetDbValueDef($rsnew, $this->Other_Bachelors_Title->CurrentValue, NULL, FALSE);

		// Other Bachelors University
		$this->Other_Bachelors_University->SetDbValueDef($rsnew, $this->Other_Bachelors_University->CurrentValue, NULL, FALSE);

		// Other Bachelors Major
		$this->Other_Bachelors_Major->SetDbValueDef($rsnew, $this->Other_Bachelors_Major->CurrentValue, NULL, FALSE);

		// Other Bachelors GPA
		$this->Other_Bachelors_GPA->SetDbValueDef($rsnew, $this->Other_Bachelors_GPA->CurrentValue, NULL, FALSE);

		// Other Bachelors MGPA
		$this->Other_Bachelors_MGPA->SetDbValueDef($rsnew, $this->Other_Bachelors_MGPA->CurrentValue, NULL, FALSE);

		// Masters Degree Title
		$this->Masters_Degree_Title->SetDbValueDef($rsnew, $this->Masters_Degree_Title->CurrentValue, NULL, FALSE);

		// Master University
		$this->Master_University->SetDbValueDef($rsnew, $this->Master_University->CurrentValue, NULL, FALSE);

		// Masters Degree Major
		$this->Masters_Degree_Major->SetDbValueDef($rsnew, $this->Masters_Degree_Major->CurrentValue, NULL, FALSE);

		// Masters GPA
		$this->Masters_GPA->SetDbValueDef($rsnew, $this->Masters_GPA->CurrentValue, NULL, FALSE);

		// Other Masters Degree Title
		$this->Other_Masters_Degree_Title->SetDbValueDef($rsnew, $this->Other_Masters_Degree_Title->CurrentValue, NULL, FALSE);

		// Other Masters University
		$this->Other_Masters_University->SetDbValueDef($rsnew, $this->Other_Masters_University->CurrentValue, NULL, FALSE);

		// Other Masters Major
		$this->Other_Masters_Major->SetDbValueDef($rsnew, $this->Other_Masters_Major->CurrentValue, NULL, FALSE);

		// Other Masters GPA
		$this->Other_Masters_GPA->SetDbValueDef($rsnew, $this->Other_Masters_GPA->CurrentValue, NULL, FALSE);

		// PhD Title
		$this->PhD_Title->SetDbValueDef($rsnew, $this->PhD_Title->CurrentValue, NULL, FALSE);

		// Phd University
		$this->Phd_University->SetDbValueDef($rsnew, $this->Phd_University->CurrentValue, NULL, FALSE);

		// PhD Major
		$this->PhD_Major->SetDbValueDef($rsnew, $this->PhD_Major->CurrentValue, NULL, FALSE);

		// Phd Degree Equivalency
		$this->Phd_Degree_Equivalency->SetDbValueDef($rsnew, $this->Phd_Degree_Equivalency->CurrentValue, NULL, FALSE);

		// Committee Meeting
		$this->Committee_Meeting->SetDbValueDef($rsnew, $this->Committee_Meeting->CurrentValue, NULL, FALSE);

		// Committee Meeting Number
		$this->Committee_Meeting_Number->SetDbValueDef($rsnew, $this->Committee_Meeting_Number->CurrentValue, NULL, FALSE);

		// Committee Date
		$this->Committee_Date->SetDbValueDef($rsnew, $this->Committee_Date->CurrentValue, NULL, FALSE);

		// Notes
		$this->Notes->SetDbValueDef($rsnew, $this->Notes->CurrentValue, NULL, FALSE);

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

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$Breadcrumb->Add("list", $this->TableVar, "gpa_equivalencylist.php", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, ew_CurrentUrl());
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'gpa equivalency';
	  $usr = CurrentUserName();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (add page)
	function WriteAuditTrailOnAdd(&$rs) {
		if (!$this->AuditTrailOnAdd) return;
		$table = 'gpa equivalency';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['ID'];

		// Write Audit Trail
		$dt = ew_StdCurrentDateTime();
		$id = ew_ScriptName();
	  $usr = CurrentUserName();
		foreach (array_keys($rs) as $fldname) {
			if ($this->fields[$fldname]->FldDataType <> EW_DATATYPE_BLOB) { // Ignore BLOB fields
				if ($this->fields[$fldname]->FldDataType == EW_DATATYPE_MEMO) {
					if (EW_AUDIT_TRAIL_TO_DATABASE)
						$newvalue = $rs[$fldname];
					else
						$newvalue = "[MEMO]"; // Memo Field
				} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_XML) {
					$newvalue = "[XML]"; // XML Field
				} else {
					$newvalue = $rs[$fldname];
				}
				ew_WriteAuditTrail("log", $dt, $id, $usr, "A", $table, $fldname, $key, "", $newvalue);
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
}
?>
<?php ew_Header(TRUE) ?>
<?php

// Create page object
if (!isset($gpa_equivalency_add)) $gpa_equivalency_add = new cgpa_equivalency_add();

// Page init
$gpa_equivalency_add->Page_Init();

// Page main
$gpa_equivalency_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$gpa_equivalency_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var gpa_equivalency_add = new ew_Page("gpa_equivalency_add");
gpa_equivalency_add.PageID = "add"; // Page ID
var EW_PAGE_ID = gpa_equivalency_add.PageID; // For backward compatibility

// Form object
var fgpa_equivalencyadd = new ew_Form("fgpa_equivalencyadd");

// Validate form
fgpa_equivalencyadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_Committee_Date");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($gpa_equivalency->Committee_Date->FldErrMsg()) ?>");

			// Set up row object
			ew_ElementsToRow(fobj);

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
fgpa_equivalencyadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fgpa_equivalencyadd.ValidateRequired = true;
<?php } else { ?>
fgpa_equivalencyadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fgpa_equivalencyadd.Lists["x_Country"] = {"LinkField":"x_NID","Ajax":null,"AutoFill":false,"DisplayFields":["x_Nationality","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fgpa_equivalencyadd.Lists["x_Sector"] = {"LinkField":"x_ID","Ajax":null,"AutoFill":false,"DisplayFields":["x_Sector","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fgpa_equivalencyadd.Lists["x_Job_Title"] = {"LinkField":"x_ID","Ajax":null,"AutoFill":false,"DisplayFields":["x_Job_Title","","",""],"ParentFields":["x_Sector"],"FilterFields":["x_Sector_ID"],"Options":[]};
fgpa_equivalencyadd.Lists["x_College"] = {"LinkField":"x_CollegeID","Ajax":null,"AutoFill":false,"DisplayFields":["x_College_Name_AR","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fgpa_equivalencyadd.Lists["x_Department"] = {"LinkField":"x_DID","Ajax":null,"AutoFill":false,"DisplayFields":["x_DepartmentName2DAR","","",""],"ParentFields":["x_College"],"FilterFields":["x_CID"],"Options":[]};
fgpa_equivalencyadd.Lists["x_Bachelors_GPA"] = {"LinkField":"x_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_Grade","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fgpa_equivalencyadd.Lists["x_Bachelors_MGPA"] = {"LinkField":"x_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_Grade","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fgpa_equivalencyadd.Lists["x_Other_Bachelors_GPA"] = {"LinkField":"x_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_Grade","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fgpa_equivalencyadd.Lists["x_Other_Bachelors_MGPA"] = {"LinkField":"x_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_Grade","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fgpa_equivalencyadd.Lists["x_Masters_GPA"] = {"LinkField":"x_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_Grade","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fgpa_equivalencyadd.Lists["x_Other_Masters_GPA"] = {"LinkField":"x_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_Grade","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $gpa_equivalency_add->ShowPageHeader(); ?>
<?php
$gpa_equivalency_add->ShowMessage();
?>
<form name="fgpa_equivalencyadd" id="fgpa_equivalencyadd" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="gpa_equivalency">
<input type="hidden" name="a_add" id="a_add" value="A">
<table class="ewGrid"><tr><td>
<table id="tbl_gpa_equivalencyadd" class="table table-bordered table-striped">
<?php if ($gpa_equivalency->Name->Visible) { // Name ?>
	<tr id="r_Name">
		<td><span id="elh_gpa_equivalency_Name"><?php echo $gpa_equivalency->Name->FldCaption() ?></span></td>
		<td<?php echo $gpa_equivalency->Name->CellAttributes() ?>>
<span id="el_gpa_equivalency_Name" class="control-group">
<input type="text" data-field="x_Name" name="x_Name" id="x_Name" size="50" maxlength="100" placeholder="<?php echo ew_HtmlEncode($gpa_equivalency->Name->PlaceHolder) ?>" value="<?php echo $gpa_equivalency->Name->EditValue ?>"<?php echo $gpa_equivalency->Name->EditAttributes() ?>>
</span>
<?php echo $gpa_equivalency->Name->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($gpa_equivalency->Country->Visible) { // Country ?>
	<tr id="r_Country">
		<td><span id="elh_gpa_equivalency_Country"><?php echo $gpa_equivalency->Country->FldCaption() ?></span></td>
		<td<?php echo $gpa_equivalency->Country->CellAttributes() ?>>
<span id="el_gpa_equivalency_Country" class="control-group">
<select data-field="x_Country" id="x_Country" name="x_Country"<?php echo $gpa_equivalency->Country->EditAttributes() ?>>
<?php
if (is_array($gpa_equivalency->Country->EditValue)) {
	$arwrk = $gpa_equivalency->Country->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($gpa_equivalency->Country->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fgpa_equivalencyadd.Lists["x_Country"].Options = <?php echo (is_array($gpa_equivalency->Country->EditValue)) ? ew_ArrayToJson($gpa_equivalency->Country->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $gpa_equivalency->Country->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($gpa_equivalency->Civil_ID->Visible) { // Civil ID ?>
	<tr id="r_Civil_ID">
		<td><span id="elh_gpa_equivalency_Civil_ID"><?php echo $gpa_equivalency->Civil_ID->FldCaption() ?></span></td>
		<td<?php echo $gpa_equivalency->Civil_ID->CellAttributes() ?>>
<span id="el_gpa_equivalency_Civil_ID" class="control-group">
<input type="text" data-field="x_Civil_ID" name="x_Civil_ID" id="x_Civil_ID" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($gpa_equivalency->Civil_ID->PlaceHolder) ?>" value="<?php echo $gpa_equivalency->Civil_ID->EditValue ?>"<?php echo $gpa_equivalency->Civil_ID->EditAttributes() ?>>
</span>
<?php echo $gpa_equivalency->Civil_ID->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($gpa_equivalency->Passport_No2E->Visible) { // Passport No. ?>
	<tr id="r_Passport_No2E">
		<td><span id="elh_gpa_equivalency_Passport_No2E"><?php echo $gpa_equivalency->Passport_No2E->FldCaption() ?></span></td>
		<td<?php echo $gpa_equivalency->Passport_No2E->CellAttributes() ?>>
<span id="el_gpa_equivalency_Passport_No2E" class="control-group">
<input type="text" data-field="x_Passport_No2E" name="x_Passport_No2E" id="x_Passport_No2E" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($gpa_equivalency->Passport_No2E->PlaceHolder) ?>" value="<?php echo $gpa_equivalency->Passport_No2E->EditValue ?>"<?php echo $gpa_equivalency->Passport_No2E->EditAttributes() ?>>
</span>
<?php echo $gpa_equivalency->Passport_No2E->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($gpa_equivalency->Sector->Visible) { // Sector ?>
	<tr id="r_Sector">
		<td><span id="elh_gpa_equivalency_Sector"><?php echo $gpa_equivalency->Sector->FldCaption() ?></span></td>
		<td<?php echo $gpa_equivalency->Sector->CellAttributes() ?>>
<span id="el_gpa_equivalency_Sector" class="control-group">
<?php $gpa_equivalency->Sector->EditAttrs["onchange"] = "ew_UpdateOpt.call(this, ['x_Job_Title']); " . @$gpa_equivalency->Sector->EditAttrs["onchange"]; ?>
<select data-field="x_Sector" id="x_Sector" name="x_Sector"<?php echo $gpa_equivalency->Sector->EditAttributes() ?>>
<?php
if (is_array($gpa_equivalency->Sector->EditValue)) {
	$arwrk = $gpa_equivalency->Sector->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($gpa_equivalency->Sector->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php if (AllowAdd(CurrentProjectID() . "sectors")) { ?>
&nbsp;<a id="aol_x_Sector" class="ewAddOptLink" href="javascript:void(0);" onclick="ew_AddOptDialogShow({lnk:this,el:'x_Sector',url:'sectorsaddopt.php'});"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $gpa_equivalency->Sector->FldCaption() ?></a>
<?php } ?>
<script type="text/javascript">
fgpa_equivalencyadd.Lists["x_Sector"].Options = <?php echo (is_array($gpa_equivalency->Sector->EditValue)) ? ew_ArrayToJson($gpa_equivalency->Sector->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $gpa_equivalency->Sector->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($gpa_equivalency->Job_Title->Visible) { // Job Title ?>
	<tr id="r_Job_Title">
		<td><span id="elh_gpa_equivalency_Job_Title"><?php echo $gpa_equivalency->Job_Title->FldCaption() ?></span></td>
		<td<?php echo $gpa_equivalency->Job_Title->CellAttributes() ?>>
<span id="el_gpa_equivalency_Job_Title" class="control-group">
<select data-field="x_Job_Title" id="x_Job_Title" name="x_Job_Title"<?php echo $gpa_equivalency->Job_Title->EditAttributes() ?>>
<?php
if (is_array($gpa_equivalency->Job_Title->EditValue)) {
	$arwrk = $gpa_equivalency->Job_Title->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($gpa_equivalency->Job_Title->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php if (AllowAdd(CurrentProjectID() . "job titles")) { ?>
&nbsp;<a id="aol_x_Job_Title" class="ewAddOptLink" href="javascript:void(0);" onclick="ew_AddOptDialogShow({lnk:this,el:'x_Job_Title',url:'job_titlesaddopt.php'});"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $gpa_equivalency->Job_Title->FldCaption() ?></a>
<?php } ?>
<script type="text/javascript">
fgpa_equivalencyadd.Lists["x_Job_Title"].Options = <?php echo (is_array($gpa_equivalency->Job_Title->EditValue)) ? ew_ArrayToJson($gpa_equivalency->Job_Title->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $gpa_equivalency->Job_Title->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($gpa_equivalency->Program->Visible) { // Program ?>
	<tr id="r_Program">
		<td><span id="elh_gpa_equivalency_Program"><?php echo $gpa_equivalency->Program->FldCaption() ?></span></td>
		<td<?php echo $gpa_equivalency->Program->CellAttributes() ?>>
<span id="el_gpa_equivalency_Program" class="control-group">
<input type="text" data-field="x_Program" name="x_Program" id="x_Program" size="50" maxlength="100" placeholder="<?php echo ew_HtmlEncode($gpa_equivalency->Program->PlaceHolder) ?>" value="<?php echo $gpa_equivalency->Program->EditValue ?>"<?php echo $gpa_equivalency->Program->EditAttributes() ?>>
</span>
<?php echo $gpa_equivalency->Program->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($gpa_equivalency->College->Visible) { // College ?>
	<tr id="r_College">
		<td><span id="elh_gpa_equivalency_College"><?php echo $gpa_equivalency->College->FldCaption() ?></span></td>
		<td<?php echo $gpa_equivalency->College->CellAttributes() ?>>
<span id="el_gpa_equivalency_College" class="control-group">
<?php $gpa_equivalency->College->EditAttrs["onchange"] = "ew_UpdateOpt.call(this, ['x_Department']); " . @$gpa_equivalency->College->EditAttrs["onchange"]; ?>
<select data-field="x_College" id="x_College" name="x_College"<?php echo $gpa_equivalency->College->EditAttributes() ?>>
<?php
if (is_array($gpa_equivalency->College->EditValue)) {
	$arwrk = $gpa_equivalency->College->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($gpa_equivalency->College->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fgpa_equivalencyadd.Lists["x_College"].Options = <?php echo (is_array($gpa_equivalency->College->EditValue)) ? ew_ArrayToJson($gpa_equivalency->College->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $gpa_equivalency->College->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($gpa_equivalency->Department->Visible) { // Department ?>
	<tr id="r_Department">
		<td><span id="elh_gpa_equivalency_Department"><?php echo $gpa_equivalency->Department->FldCaption() ?></span></td>
		<td<?php echo $gpa_equivalency->Department->CellAttributes() ?>>
<span id="el_gpa_equivalency_Department" class="control-group">
<select data-field="x_Department" id="x_Department" name="x_Department"<?php echo $gpa_equivalency->Department->EditAttributes() ?>>
<?php
if (is_array($gpa_equivalency->Department->EditValue)) {
	$arwrk = $gpa_equivalency->Department->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($gpa_equivalency->Department->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fgpa_equivalencyadd.Lists["x_Department"].Options = <?php echo (is_array($gpa_equivalency->Department->EditValue)) ? ew_ArrayToJson($gpa_equivalency->Department->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $gpa_equivalency->Department->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($gpa_equivalency->Bachelors_Title->Visible) { // Bachelors Title ?>
	<tr id="r_Bachelors_Title">
		<td><span id="elh_gpa_equivalency_Bachelors_Title"><?php echo $gpa_equivalency->Bachelors_Title->FldCaption() ?></span></td>
		<td<?php echo $gpa_equivalency->Bachelors_Title->CellAttributes() ?>>
<span id="el_gpa_equivalency_Bachelors_Title" class="control-group">
<input type="text" data-field="x_Bachelors_Title" name="x_Bachelors_Title" id="x_Bachelors_Title" size="50" maxlength="100" placeholder="<?php echo ew_HtmlEncode($gpa_equivalency->Bachelors_Title->PlaceHolder) ?>" value="<?php echo $gpa_equivalency->Bachelors_Title->EditValue ?>"<?php echo $gpa_equivalency->Bachelors_Title->EditAttributes() ?>>
</span>
<?php echo $gpa_equivalency->Bachelors_Title->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($gpa_equivalency->Bachelor_University->Visible) { // Bachelor University ?>
	<tr id="r_Bachelor_University">
		<td><span id="elh_gpa_equivalency_Bachelor_University"><?php echo $gpa_equivalency->Bachelor_University->FldCaption() ?></span></td>
		<td<?php echo $gpa_equivalency->Bachelor_University->CellAttributes() ?>>
<span id="el_gpa_equivalency_Bachelor_University" class="control-group">
<input type="text" data-field="x_Bachelor_University" name="x_Bachelor_University" id="x_Bachelor_University" size="50" maxlength="100" placeholder="<?php echo ew_HtmlEncode($gpa_equivalency->Bachelor_University->PlaceHolder) ?>" value="<?php echo $gpa_equivalency->Bachelor_University->EditValue ?>"<?php echo $gpa_equivalency->Bachelor_University->EditAttributes() ?>>
</span>
<?php echo $gpa_equivalency->Bachelor_University->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($gpa_equivalency->Bachelors_Major->Visible) { // Bachelors Major ?>
	<tr id="r_Bachelors_Major">
		<td><span id="elh_gpa_equivalency_Bachelors_Major"><?php echo $gpa_equivalency->Bachelors_Major->FldCaption() ?></span></td>
		<td<?php echo $gpa_equivalency->Bachelors_Major->CellAttributes() ?>>
<span id="el_gpa_equivalency_Bachelors_Major" class="control-group">
<input type="text" data-field="x_Bachelors_Major" name="x_Bachelors_Major" id="x_Bachelors_Major" size="30" maxlength="56" placeholder="<?php echo ew_HtmlEncode($gpa_equivalency->Bachelors_Major->PlaceHolder) ?>" value="<?php echo $gpa_equivalency->Bachelors_Major->EditValue ?>"<?php echo $gpa_equivalency->Bachelors_Major->EditAttributes() ?>>
</span>
<?php echo $gpa_equivalency->Bachelors_Major->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($gpa_equivalency->Bachelors_GPA->Visible) { // Bachelors GPA ?>
	<tr id="r_Bachelors_GPA">
		<td><span id="elh_gpa_equivalency_Bachelors_GPA"><?php echo $gpa_equivalency->Bachelors_GPA->FldCaption() ?></span></td>
		<td<?php echo $gpa_equivalency->Bachelors_GPA->CellAttributes() ?>>
<span id="el_gpa_equivalency_Bachelors_GPA" class="control-group">
<?php
	$wrkonchange = trim(" " . @$gpa_equivalency->Bachelors_GPA->EditAttrs["onchange"]);
	if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
	$gpa_equivalency->Bachelors_GPA->EditAttrs["onchange"] = "";
?>
<span id="as_x_Bachelors_GPA" style="white-space: nowrap; z-index: 8860">
	<input type="text" name="sv_x_Bachelors_GPA" id="sv_x_Bachelors_GPA" value="<?php echo $gpa_equivalency->Bachelors_GPA->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($gpa_equivalency->Bachelors_GPA->PlaceHolder) ?>"<?php echo $gpa_equivalency->Bachelors_GPA->EditAttributes() ?>>&nbsp;<span id="em_x_Bachelors_GPA" class="ewMessage" style="display: none"><?php echo str_replace("%f", "phpimages/", $Language->Phrase("UnmatchedValue")) ?></span>
	<div id="sc_x_Bachelors_GPA" style="display: inline; z-index: 8860"></div>
</span>
<input type="hidden" data-field="x_Bachelors_GPA" name="x_Bachelors_GPA" id="x_Bachelors_GPA" value="<?php echo ew_HtmlEncode($gpa_equivalency->Bachelors_GPA->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT `ID`, `Grade` AS `DispFld` FROM `gpa_list`";
$sWhereWrk = "`Grade` LIKE '{query_value}%'";

// Call Lookup selecting
$gpa_equivalency->Lookup_Selecting($gpa_equivalency->Bachelors_GPA, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x_Bachelors_GPA" id="q_x_Bachelors_GPA" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>">
<script type="text/javascript">
var oas = new ew_AutoSuggest("x_Bachelors_GPA", fgpa_equivalencyadd, false, EW_AUTO_SUGGEST_MAX_ENTRIES);
oas.formatResult = function(ar) {
	var dv = ar[1];
	for (var i = 2; i <= 4; i++)
		dv += (ar[i]) ? ew_ValueSeparator(i - 1, "x_Bachelors_GPA") + ar[i] : "";
	return dv;
}
fgpa_equivalencyadd.AutoSuggests["x_Bachelors_GPA"] = oas;
</script>
</span>
<?php echo $gpa_equivalency->Bachelors_GPA->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($gpa_equivalency->Bachelors_MGPA->Visible) { // Bachelors MGPA ?>
	<tr id="r_Bachelors_MGPA">
		<td><span id="elh_gpa_equivalency_Bachelors_MGPA"><?php echo $gpa_equivalency->Bachelors_MGPA->FldCaption() ?></span></td>
		<td<?php echo $gpa_equivalency->Bachelors_MGPA->CellAttributes() ?>>
<span id="el_gpa_equivalency_Bachelors_MGPA" class="control-group">
<?php
	$wrkonchange = trim(" " . @$gpa_equivalency->Bachelors_MGPA->EditAttrs["onchange"]);
	if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
	$gpa_equivalency->Bachelors_MGPA->EditAttrs["onchange"] = "";
?>
<span id="as_x_Bachelors_MGPA" style="white-space: nowrap; z-index: 8850">
	<input type="text" name="sv_x_Bachelors_MGPA" id="sv_x_Bachelors_MGPA" value="<?php echo $gpa_equivalency->Bachelors_MGPA->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($gpa_equivalency->Bachelors_MGPA->PlaceHolder) ?>"<?php echo $gpa_equivalency->Bachelors_MGPA->EditAttributes() ?>>&nbsp;<span id="em_x_Bachelors_MGPA" class="ewMessage" style="display: none"><?php echo str_replace("%f", "phpimages/", $Language->Phrase("UnmatchedValue")) ?></span>
	<div id="sc_x_Bachelors_MGPA" style="display: inline; z-index: 8850"></div>
</span>
<input type="hidden" data-field="x_Bachelors_MGPA" name="x_Bachelors_MGPA" id="x_Bachelors_MGPA" value="<?php echo ew_HtmlEncode($gpa_equivalency->Bachelors_MGPA->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT `ID`, `Grade` AS `DispFld` FROM `gpa_list`";
$sWhereWrk = "`Grade` LIKE '{query_value}%'";

// Call Lookup selecting
$gpa_equivalency->Lookup_Selecting($gpa_equivalency->Bachelors_MGPA, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x_Bachelors_MGPA" id="q_x_Bachelors_MGPA" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>">
<script type="text/javascript">
var oas = new ew_AutoSuggest("x_Bachelors_MGPA", fgpa_equivalencyadd, false, EW_AUTO_SUGGEST_MAX_ENTRIES);
oas.formatResult = function(ar) {
	var dv = ar[1];
	for (var i = 2; i <= 4; i++)
		dv += (ar[i]) ? ew_ValueSeparator(i - 1, "x_Bachelors_MGPA") + ar[i] : "";
	return dv;
}
fgpa_equivalencyadd.AutoSuggests["x_Bachelors_MGPA"] = oas;
</script>
</span>
<?php echo $gpa_equivalency->Bachelors_MGPA->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($gpa_equivalency->Other_Bachelors_Title->Visible) { // Other Bachelors Title ?>
	<tr id="r_Other_Bachelors_Title">
		<td><span id="elh_gpa_equivalency_Other_Bachelors_Title"><?php echo $gpa_equivalency->Other_Bachelors_Title->FldCaption() ?></span></td>
		<td<?php echo $gpa_equivalency->Other_Bachelors_Title->CellAttributes() ?>>
<span id="el_gpa_equivalency_Other_Bachelors_Title" class="control-group">
<input type="text" data-field="x_Other_Bachelors_Title" name="x_Other_Bachelors_Title" id="x_Other_Bachelors_Title" size="50" maxlength="100" placeholder="<?php echo ew_HtmlEncode($gpa_equivalency->Other_Bachelors_Title->PlaceHolder) ?>" value="<?php echo $gpa_equivalency->Other_Bachelors_Title->EditValue ?>"<?php echo $gpa_equivalency->Other_Bachelors_Title->EditAttributes() ?>>
</span>
<?php echo $gpa_equivalency->Other_Bachelors_Title->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($gpa_equivalency->Other_Bachelors_University->Visible) { // Other Bachelors University ?>
	<tr id="r_Other_Bachelors_University">
		<td><span id="elh_gpa_equivalency_Other_Bachelors_University"><?php echo $gpa_equivalency->Other_Bachelors_University->FldCaption() ?></span></td>
		<td<?php echo $gpa_equivalency->Other_Bachelors_University->CellAttributes() ?>>
<span id="el_gpa_equivalency_Other_Bachelors_University" class="control-group">
<input type="text" data-field="x_Other_Bachelors_University" name="x_Other_Bachelors_University" id="x_Other_Bachelors_University" size="50" maxlength="100" placeholder="<?php echo ew_HtmlEncode($gpa_equivalency->Other_Bachelors_University->PlaceHolder) ?>" value="<?php echo $gpa_equivalency->Other_Bachelors_University->EditValue ?>"<?php echo $gpa_equivalency->Other_Bachelors_University->EditAttributes() ?>>
</span>
<?php echo $gpa_equivalency->Other_Bachelors_University->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($gpa_equivalency->Other_Bachelors_Major->Visible) { // Other Bachelors Major ?>
	<tr id="r_Other_Bachelors_Major">
		<td><span id="elh_gpa_equivalency_Other_Bachelors_Major"><?php echo $gpa_equivalency->Other_Bachelors_Major->FldCaption() ?></span></td>
		<td<?php echo $gpa_equivalency->Other_Bachelors_Major->CellAttributes() ?>>
<span id="el_gpa_equivalency_Other_Bachelors_Major" class="control-group">
<input type="text" data-field="x_Other_Bachelors_Major" name="x_Other_Bachelors_Major" id="x_Other_Bachelors_Major" size="50" maxlength="100" placeholder="<?php echo ew_HtmlEncode($gpa_equivalency->Other_Bachelors_Major->PlaceHolder) ?>" value="<?php echo $gpa_equivalency->Other_Bachelors_Major->EditValue ?>"<?php echo $gpa_equivalency->Other_Bachelors_Major->EditAttributes() ?>>
</span>
<?php echo $gpa_equivalency->Other_Bachelors_Major->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($gpa_equivalency->Other_Bachelors_GPA->Visible) { // Other Bachelors GPA ?>
	<tr id="r_Other_Bachelors_GPA">
		<td><span id="elh_gpa_equivalency_Other_Bachelors_GPA"><?php echo $gpa_equivalency->Other_Bachelors_GPA->FldCaption() ?></span></td>
		<td<?php echo $gpa_equivalency->Other_Bachelors_GPA->CellAttributes() ?>>
<span id="el_gpa_equivalency_Other_Bachelors_GPA" class="control-group">
<?php
	$wrkonchange = trim(" " . @$gpa_equivalency->Other_Bachelors_GPA->EditAttrs["onchange"]);
	if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
	$gpa_equivalency->Other_Bachelors_GPA->EditAttrs["onchange"] = "";
?>
<span id="as_x_Other_Bachelors_GPA" style="white-space: nowrap; z-index: 8810">
	<input type="text" name="sv_x_Other_Bachelors_GPA" id="sv_x_Other_Bachelors_GPA" value="<?php echo $gpa_equivalency->Other_Bachelors_GPA->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($gpa_equivalency->Other_Bachelors_GPA->PlaceHolder) ?>"<?php echo $gpa_equivalency->Other_Bachelors_GPA->EditAttributes() ?>>&nbsp;<span id="em_x_Other_Bachelors_GPA" class="ewMessage" style="display: none"><?php echo str_replace("%f", "phpimages/", $Language->Phrase("UnmatchedValue")) ?></span>
	<div id="sc_x_Other_Bachelors_GPA" style="display: inline; z-index: 8810"></div>
</span>
<input type="hidden" data-field="x_Other_Bachelors_GPA" name="x_Other_Bachelors_GPA" id="x_Other_Bachelors_GPA" value="<?php echo ew_HtmlEncode($gpa_equivalency->Other_Bachelors_GPA->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT `ID`, `Grade` AS `DispFld` FROM `gpa_list`";
$sWhereWrk = "`Grade` LIKE '{query_value}%'";

// Call Lookup selecting
$gpa_equivalency->Lookup_Selecting($gpa_equivalency->Other_Bachelors_GPA, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x_Other_Bachelors_GPA" id="q_x_Other_Bachelors_GPA" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>">
<script type="text/javascript">
var oas = new ew_AutoSuggest("x_Other_Bachelors_GPA", fgpa_equivalencyadd, false, EW_AUTO_SUGGEST_MAX_ENTRIES);
oas.formatResult = function(ar) {
	var dv = ar[1];
	for (var i = 2; i <= 4; i++)
		dv += (ar[i]) ? ew_ValueSeparator(i - 1, "x_Other_Bachelors_GPA") + ar[i] : "";
	return dv;
}
fgpa_equivalencyadd.AutoSuggests["x_Other_Bachelors_GPA"] = oas;
</script>
</span>
<?php echo $gpa_equivalency->Other_Bachelors_GPA->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($gpa_equivalency->Other_Bachelors_MGPA->Visible) { // Other Bachelors MGPA ?>
	<tr id="r_Other_Bachelors_MGPA">
		<td><span id="elh_gpa_equivalency_Other_Bachelors_MGPA"><?php echo $gpa_equivalency->Other_Bachelors_MGPA->FldCaption() ?></span></td>
		<td<?php echo $gpa_equivalency->Other_Bachelors_MGPA->CellAttributes() ?>>
<span id="el_gpa_equivalency_Other_Bachelors_MGPA" class="control-group">
<?php
	$wrkonchange = trim(" " . @$gpa_equivalency->Other_Bachelors_MGPA->EditAttrs["onchange"]);
	if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
	$gpa_equivalency->Other_Bachelors_MGPA->EditAttrs["onchange"] = "";
?>
<span id="as_x_Other_Bachelors_MGPA" style="white-space: nowrap; z-index: 8800">
	<input type="text" name="sv_x_Other_Bachelors_MGPA" id="sv_x_Other_Bachelors_MGPA" value="<?php echo $gpa_equivalency->Other_Bachelors_MGPA->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($gpa_equivalency->Other_Bachelors_MGPA->PlaceHolder) ?>"<?php echo $gpa_equivalency->Other_Bachelors_MGPA->EditAttributes() ?>>&nbsp;<span id="em_x_Other_Bachelors_MGPA" class="ewMessage" style="display: none"><?php echo str_replace("%f", "phpimages/", $Language->Phrase("UnmatchedValue")) ?></span>
	<div id="sc_x_Other_Bachelors_MGPA" style="display: inline; z-index: 8800"></div>
</span>
<input type="hidden" data-field="x_Other_Bachelors_MGPA" name="x_Other_Bachelors_MGPA" id="x_Other_Bachelors_MGPA" value="<?php echo ew_HtmlEncode($gpa_equivalency->Other_Bachelors_MGPA->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT `ID`, `Grade` AS `DispFld` FROM `gpa_list`";
$sWhereWrk = "`Grade` LIKE '{query_value}%'";

// Call Lookup selecting
$gpa_equivalency->Lookup_Selecting($gpa_equivalency->Other_Bachelors_MGPA, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x_Other_Bachelors_MGPA" id="q_x_Other_Bachelors_MGPA" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>">
<script type="text/javascript">
var oas = new ew_AutoSuggest("x_Other_Bachelors_MGPA", fgpa_equivalencyadd, false, EW_AUTO_SUGGEST_MAX_ENTRIES);
oas.formatResult = function(ar) {
	var dv = ar[1];
	for (var i = 2; i <= 4; i++)
		dv += (ar[i]) ? ew_ValueSeparator(i - 1, "x_Other_Bachelors_MGPA") + ar[i] : "";
	return dv;
}
fgpa_equivalencyadd.AutoSuggests["x_Other_Bachelors_MGPA"] = oas;
</script>
</span>
<?php echo $gpa_equivalency->Other_Bachelors_MGPA->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($gpa_equivalency->Masters_Degree_Title->Visible) { // Masters Degree Title ?>
	<tr id="r_Masters_Degree_Title">
		<td><span id="elh_gpa_equivalency_Masters_Degree_Title"><?php echo $gpa_equivalency->Masters_Degree_Title->FldCaption() ?></span></td>
		<td<?php echo $gpa_equivalency->Masters_Degree_Title->CellAttributes() ?>>
<span id="el_gpa_equivalency_Masters_Degree_Title" class="control-group">
<input type="text" data-field="x_Masters_Degree_Title" name="x_Masters_Degree_Title" id="x_Masters_Degree_Title" size="50" maxlength="100" placeholder="<?php echo ew_HtmlEncode($gpa_equivalency->Masters_Degree_Title->PlaceHolder) ?>" value="<?php echo $gpa_equivalency->Masters_Degree_Title->EditValue ?>"<?php echo $gpa_equivalency->Masters_Degree_Title->EditAttributes() ?>>
</span>
<?php echo $gpa_equivalency->Masters_Degree_Title->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($gpa_equivalency->Master_University->Visible) { // Master University ?>
	<tr id="r_Master_University">
		<td><span id="elh_gpa_equivalency_Master_University"><?php echo $gpa_equivalency->Master_University->FldCaption() ?></span></td>
		<td<?php echo $gpa_equivalency->Master_University->CellAttributes() ?>>
<span id="el_gpa_equivalency_Master_University" class="control-group">
<input type="text" data-field="x_Master_University" name="x_Master_University" id="x_Master_University" size="50" maxlength="100" placeholder="<?php echo ew_HtmlEncode($gpa_equivalency->Master_University->PlaceHolder) ?>" value="<?php echo $gpa_equivalency->Master_University->EditValue ?>"<?php echo $gpa_equivalency->Master_University->EditAttributes() ?>>
</span>
<?php echo $gpa_equivalency->Master_University->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($gpa_equivalency->Masters_Degree_Major->Visible) { // Masters Degree Major ?>
	<tr id="r_Masters_Degree_Major">
		<td><span id="elh_gpa_equivalency_Masters_Degree_Major"><?php echo $gpa_equivalency->Masters_Degree_Major->FldCaption() ?></span></td>
		<td<?php echo $gpa_equivalency->Masters_Degree_Major->CellAttributes() ?>>
<span id="el_gpa_equivalency_Masters_Degree_Major" class="control-group">
<input type="text" data-field="x_Masters_Degree_Major" name="x_Masters_Degree_Major" id="x_Masters_Degree_Major" size="50" maxlength="100" placeholder="<?php echo ew_HtmlEncode($gpa_equivalency->Masters_Degree_Major->PlaceHolder) ?>" value="<?php echo $gpa_equivalency->Masters_Degree_Major->EditValue ?>"<?php echo $gpa_equivalency->Masters_Degree_Major->EditAttributes() ?>>
</span>
<?php echo $gpa_equivalency->Masters_Degree_Major->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($gpa_equivalency->Masters_GPA->Visible) { // Masters GPA ?>
	<tr id="r_Masters_GPA">
		<td><span id="elh_gpa_equivalency_Masters_GPA"><?php echo $gpa_equivalency->Masters_GPA->FldCaption() ?></span></td>
		<td<?php echo $gpa_equivalency->Masters_GPA->CellAttributes() ?>>
<span id="el_gpa_equivalency_Masters_GPA" class="control-group">
<?php
	$wrkonchange = trim(" " . @$gpa_equivalency->Masters_GPA->EditAttrs["onchange"]);
	if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
	$gpa_equivalency->Masters_GPA->EditAttrs["onchange"] = "";
?>
<span id="as_x_Masters_GPA" style="white-space: nowrap; z-index: 8760">
	<input type="text" name="sv_x_Masters_GPA" id="sv_x_Masters_GPA" value="<?php echo $gpa_equivalency->Masters_GPA->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($gpa_equivalency->Masters_GPA->PlaceHolder) ?>"<?php echo $gpa_equivalency->Masters_GPA->EditAttributes() ?>>&nbsp;<span id="em_x_Masters_GPA" class="ewMessage" style="display: none"><?php echo str_replace("%f", "phpimages/", $Language->Phrase("UnmatchedValue")) ?></span>
	<div id="sc_x_Masters_GPA" style="display: inline; z-index: 8760"></div>
</span>
<input type="hidden" data-field="x_Masters_GPA" name="x_Masters_GPA" id="x_Masters_GPA" value="<?php echo ew_HtmlEncode($gpa_equivalency->Masters_GPA->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT `ID`, `Grade` AS `DispFld` FROM `gpa_list`";
$sWhereWrk = "`Grade` LIKE '{query_value}%'";

// Call Lookup selecting
$gpa_equivalency->Lookup_Selecting($gpa_equivalency->Masters_GPA, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x_Masters_GPA" id="q_x_Masters_GPA" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>">
<script type="text/javascript">
var oas = new ew_AutoSuggest("x_Masters_GPA", fgpa_equivalencyadd, false, EW_AUTO_SUGGEST_MAX_ENTRIES);
oas.formatResult = function(ar) {
	var dv = ar[1];
	for (var i = 2; i <= 4; i++)
		dv += (ar[i]) ? ew_ValueSeparator(i - 1, "x_Masters_GPA") + ar[i] : "";
	return dv;
}
fgpa_equivalencyadd.AutoSuggests["x_Masters_GPA"] = oas;
</script>
</span>
<?php echo $gpa_equivalency->Masters_GPA->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($gpa_equivalency->Other_Masters_Degree_Title->Visible) { // Other Masters Degree Title ?>
	<tr id="r_Other_Masters_Degree_Title">
		<td><span id="elh_gpa_equivalency_Other_Masters_Degree_Title"><?php echo $gpa_equivalency->Other_Masters_Degree_Title->FldCaption() ?></span></td>
		<td<?php echo $gpa_equivalency->Other_Masters_Degree_Title->CellAttributes() ?>>
<span id="el_gpa_equivalency_Other_Masters_Degree_Title" class="control-group">
<input type="text" data-field="x_Other_Masters_Degree_Title" name="x_Other_Masters_Degree_Title" id="x_Other_Masters_Degree_Title" size="50" maxlength="100" placeholder="<?php echo ew_HtmlEncode($gpa_equivalency->Other_Masters_Degree_Title->PlaceHolder) ?>" value="<?php echo $gpa_equivalency->Other_Masters_Degree_Title->EditValue ?>"<?php echo $gpa_equivalency->Other_Masters_Degree_Title->EditAttributes() ?>>
</span>
<?php echo $gpa_equivalency->Other_Masters_Degree_Title->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($gpa_equivalency->Other_Masters_University->Visible) { // Other Masters University ?>
	<tr id="r_Other_Masters_University">
		<td><span id="elh_gpa_equivalency_Other_Masters_University"><?php echo $gpa_equivalency->Other_Masters_University->FldCaption() ?></span></td>
		<td<?php echo $gpa_equivalency->Other_Masters_University->CellAttributes() ?>>
<span id="el_gpa_equivalency_Other_Masters_University" class="control-group">
<input type="text" data-field="x_Other_Masters_University" name="x_Other_Masters_University" id="x_Other_Masters_University" size="50" maxlength="100" placeholder="<?php echo ew_HtmlEncode($gpa_equivalency->Other_Masters_University->PlaceHolder) ?>" value="<?php echo $gpa_equivalency->Other_Masters_University->EditValue ?>"<?php echo $gpa_equivalency->Other_Masters_University->EditAttributes() ?>>
</span>
<?php echo $gpa_equivalency->Other_Masters_University->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($gpa_equivalency->Other_Masters_Major->Visible) { // Other Masters Major ?>
	<tr id="r_Other_Masters_Major">
		<td><span id="elh_gpa_equivalency_Other_Masters_Major"><?php echo $gpa_equivalency->Other_Masters_Major->FldCaption() ?></span></td>
		<td<?php echo $gpa_equivalency->Other_Masters_Major->CellAttributes() ?>>
<span id="el_gpa_equivalency_Other_Masters_Major" class="control-group">
<input type="text" data-field="x_Other_Masters_Major" name="x_Other_Masters_Major" id="x_Other_Masters_Major" size="50" maxlength="100" placeholder="<?php echo ew_HtmlEncode($gpa_equivalency->Other_Masters_Major->PlaceHolder) ?>" value="<?php echo $gpa_equivalency->Other_Masters_Major->EditValue ?>"<?php echo $gpa_equivalency->Other_Masters_Major->EditAttributes() ?>>
</span>
<?php echo $gpa_equivalency->Other_Masters_Major->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($gpa_equivalency->Other_Masters_GPA->Visible) { // Other Masters GPA ?>
	<tr id="r_Other_Masters_GPA">
		<td><span id="elh_gpa_equivalency_Other_Masters_GPA"><?php echo $gpa_equivalency->Other_Masters_GPA->FldCaption() ?></span></td>
		<td<?php echo $gpa_equivalency->Other_Masters_GPA->CellAttributes() ?>>
<span id="el_gpa_equivalency_Other_Masters_GPA" class="control-group">
<?php
	$wrkonchange = trim(" " . @$gpa_equivalency->Other_Masters_GPA->EditAttrs["onchange"]);
	if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
	$gpa_equivalency->Other_Masters_GPA->EditAttrs["onchange"] = "";
?>
<span id="as_x_Other_Masters_GPA" style="white-space: nowrap; z-index: 8720">
	<input type="text" name="sv_x_Other_Masters_GPA" id="sv_x_Other_Masters_GPA" value="<?php echo $gpa_equivalency->Other_Masters_GPA->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($gpa_equivalency->Other_Masters_GPA->PlaceHolder) ?>"<?php echo $gpa_equivalency->Other_Masters_GPA->EditAttributes() ?>>&nbsp;<span id="em_x_Other_Masters_GPA" class="ewMessage" style="display: none"><?php echo str_replace("%f", "phpimages/", $Language->Phrase("UnmatchedValue")) ?></span>
	<div id="sc_x_Other_Masters_GPA" style="display: inline; z-index: 8720"></div>
</span>
<input type="hidden" data-field="x_Other_Masters_GPA" name="x_Other_Masters_GPA" id="x_Other_Masters_GPA" value="<?php echo ew_HtmlEncode($gpa_equivalency->Other_Masters_GPA->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT `ID`, `Grade` AS `DispFld` FROM `gpa_list`";
$sWhereWrk = "`Grade` LIKE '{query_value}%'";

// Call Lookup selecting
$gpa_equivalency->Lookup_Selecting($gpa_equivalency->Other_Masters_GPA, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x_Other_Masters_GPA" id="q_x_Other_Masters_GPA" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>">
<script type="text/javascript">
var oas = new ew_AutoSuggest("x_Other_Masters_GPA", fgpa_equivalencyadd, false, EW_AUTO_SUGGEST_MAX_ENTRIES);
oas.formatResult = function(ar) {
	var dv = ar[1];
	for (var i = 2; i <= 4; i++)
		dv += (ar[i]) ? ew_ValueSeparator(i - 1, "x_Other_Masters_GPA") + ar[i] : "";
	return dv;
}
fgpa_equivalencyadd.AutoSuggests["x_Other_Masters_GPA"] = oas;
</script>
</span>
<?php echo $gpa_equivalency->Other_Masters_GPA->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($gpa_equivalency->PhD_Title->Visible) { // PhD Title ?>
	<tr id="r_PhD_Title">
		<td><span id="elh_gpa_equivalency_PhD_Title"><?php echo $gpa_equivalency->PhD_Title->FldCaption() ?></span></td>
		<td<?php echo $gpa_equivalency->PhD_Title->CellAttributes() ?>>
<span id="el_gpa_equivalency_PhD_Title" class="control-group">
<input type="text" data-field="x_PhD_Title" name="x_PhD_Title" id="x_PhD_Title" size="50" maxlength="100" placeholder="<?php echo ew_HtmlEncode($gpa_equivalency->PhD_Title->PlaceHolder) ?>" value="<?php echo $gpa_equivalency->PhD_Title->EditValue ?>"<?php echo $gpa_equivalency->PhD_Title->EditAttributes() ?>>
</span>
<?php echo $gpa_equivalency->PhD_Title->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($gpa_equivalency->Phd_University->Visible) { // Phd University ?>
	<tr id="r_Phd_University">
		<td><span id="elh_gpa_equivalency_Phd_University"><?php echo $gpa_equivalency->Phd_University->FldCaption() ?></span></td>
		<td<?php echo $gpa_equivalency->Phd_University->CellAttributes() ?>>
<span id="el_gpa_equivalency_Phd_University" class="control-group">
<input type="text" data-field="x_Phd_University" name="x_Phd_University" id="x_Phd_University" size="50" maxlength="100" placeholder="<?php echo ew_HtmlEncode($gpa_equivalency->Phd_University->PlaceHolder) ?>" value="<?php echo $gpa_equivalency->Phd_University->EditValue ?>"<?php echo $gpa_equivalency->Phd_University->EditAttributes() ?>>
</span>
<?php echo $gpa_equivalency->Phd_University->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($gpa_equivalency->PhD_Major->Visible) { // PhD Major ?>
	<tr id="r_PhD_Major">
		<td><span id="elh_gpa_equivalency_PhD_Major"><?php echo $gpa_equivalency->PhD_Major->FldCaption() ?></span></td>
		<td<?php echo $gpa_equivalency->PhD_Major->CellAttributes() ?>>
<span id="el_gpa_equivalency_PhD_Major" class="control-group">
<input type="text" data-field="x_PhD_Major" name="x_PhD_Major" id="x_PhD_Major" size="50" maxlength="100" placeholder="<?php echo ew_HtmlEncode($gpa_equivalency->PhD_Major->PlaceHolder) ?>" value="<?php echo $gpa_equivalency->PhD_Major->EditValue ?>"<?php echo $gpa_equivalency->PhD_Major->EditAttributes() ?>>
</span>
<?php echo $gpa_equivalency->PhD_Major->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($gpa_equivalency->Phd_Degree_Equivalency->Visible) { // Phd Degree Equivalency ?>
	<tr id="r_Phd_Degree_Equivalency">
		<td><span id="elh_gpa_equivalency_Phd_Degree_Equivalency"><?php echo $gpa_equivalency->Phd_Degree_Equivalency->FldCaption() ?></span></td>
		<td<?php echo $gpa_equivalency->Phd_Degree_Equivalency->CellAttributes() ?>>
<span id="el_gpa_equivalency_Phd_Degree_Equivalency" class="control-group">
<input type="text" data-field="x_Phd_Degree_Equivalency" name="x_Phd_Degree_Equivalency" id="x_Phd_Degree_Equivalency" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($gpa_equivalency->Phd_Degree_Equivalency->PlaceHolder) ?>" value="<?php echo $gpa_equivalency->Phd_Degree_Equivalency->EditValue ?>"<?php echo $gpa_equivalency->Phd_Degree_Equivalency->EditAttributes() ?>>
</span>
<?php echo $gpa_equivalency->Phd_Degree_Equivalency->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($gpa_equivalency->Committee_Meeting->Visible) { // Committee Meeting ?>
	<tr id="r_Committee_Meeting">
		<td><span id="elh_gpa_equivalency_Committee_Meeting"><?php echo $gpa_equivalency->Committee_Meeting->FldCaption() ?></span></td>
		<td<?php echo $gpa_equivalency->Committee_Meeting->CellAttributes() ?>>
<span id="el_gpa_equivalency_Committee_Meeting" class="control-group">
<input type="text" data-field="x_Committee_Meeting" name="x_Committee_Meeting" id="x_Committee_Meeting" size="50" maxlength="100" placeholder="<?php echo ew_HtmlEncode($gpa_equivalency->Committee_Meeting->PlaceHolder) ?>" value="<?php echo $gpa_equivalency->Committee_Meeting->EditValue ?>"<?php echo $gpa_equivalency->Committee_Meeting->EditAttributes() ?>>
</span>
<?php echo $gpa_equivalency->Committee_Meeting->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($gpa_equivalency->Committee_Meeting_Number->Visible) { // Committee Meeting Number ?>
	<tr id="r_Committee_Meeting_Number">
		<td><span id="elh_gpa_equivalency_Committee_Meeting_Number"><?php echo $gpa_equivalency->Committee_Meeting_Number->FldCaption() ?></span></td>
		<td<?php echo $gpa_equivalency->Committee_Meeting_Number->CellAttributes() ?>>
<span id="el_gpa_equivalency_Committee_Meeting_Number" class="control-group">
<select data-field="x_Committee_Meeting_Number" id="x_Committee_Meeting_Number" name="x_Committee_Meeting_Number"<?php echo $gpa_equivalency->Committee_Meeting_Number->EditAttributes() ?>>
<?php
if (is_array($gpa_equivalency->Committee_Meeting_Number->EditValue)) {
	$arwrk = $gpa_equivalency->Committee_Meeting_Number->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($gpa_equivalency->Committee_Meeting_Number->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
</span>
<?php echo $gpa_equivalency->Committee_Meeting_Number->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($gpa_equivalency->Committee_Date->Visible) { // Committee Date ?>
	<tr id="r_Committee_Date">
		<td><span id="elh_gpa_equivalency_Committee_Date"><?php echo $gpa_equivalency->Committee_Date->FldCaption() ?></span></td>
		<td<?php echo $gpa_equivalency->Committee_Date->CellAttributes() ?>>
<span id="el_gpa_equivalency_Committee_Date" class="control-group">
<input type="text" data-field="x_Committee_Date" name="x_Committee_Date" id="x_Committee_Date" size="30" maxlength="8" placeholder="<?php echo ew_HtmlEncode($gpa_equivalency->Committee_Date->PlaceHolder) ?>" value="<?php echo $gpa_equivalency->Committee_Date->EditValue ?>"<?php echo $gpa_equivalency->Committee_Date->EditAttributes() ?>>
<?php if (!$gpa_equivalency->Committee_Date->ReadOnly && !$gpa_equivalency->Committee_Date->Disabled && @$gpa_equivalency->Committee_Date->EditAttrs["readonly"] == "" && @$gpa_equivalency->Committee_Date->EditAttrs["disabled"] == "") { ?>
<button id="cal_x_Committee_Date" name="cal_x_Committee_Date" class="btn" type="button"><img src="phpimages/calendar.png" alt="<?php echo $Language->Phrase("PickDate") ?>" title="<?php echo $Language->Phrase("PickDate") ?>" style="border: 0;"></button><script type="text/javascript">
ew_CreateCalendar("fgpa_equivalencyadd", "x_Committee_Date", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $gpa_equivalency->Committee_Date->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($gpa_equivalency->Notes->Visible) { // Notes ?>
	<tr id="r_Notes">
		<td><span id="elh_gpa_equivalency_Notes"><?php echo $gpa_equivalency->Notes->FldCaption() ?></span></td>
		<td<?php echo $gpa_equivalency->Notes->CellAttributes() ?>>
<span id="el_gpa_equivalency_Notes" class="control-group">
<textarea data-field="x_Notes" class="editor" name="x_Notes" id="x_Notes" cols="100" rows="5" placeholder="<?php echo ew_HtmlEncode($gpa_equivalency->Notes->PlaceHolder) ?>"<?php echo $gpa_equivalency->Notes->EditAttributes() ?>><?php echo $gpa_equivalency->Notes->EditValue ?></textarea>
</span>
<?php echo $gpa_equivalency->Notes->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
</form>
<script type="text/javascript">
fgpa_equivalencyadd.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$gpa_equivalency_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$gpa_equivalency_add->Page_Terminate();
?>
