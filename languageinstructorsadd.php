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

$languageinstructors_add = NULL; // Initialize page object first

class clanguageinstructors_add extends clanguageinstructors {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{F8E6AD82-57C4-4BEE-8975-8D386184467A}";

	// Table name
	var $TableName = 'languageinstructors';

	// Page object name
	var $PageObjName = 'languageinstructors_add';

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

		// Table object (languageinstructors)
		if (!isset($GLOBALS["languageinstructors"]) || get_class($GLOBALS["languageinstructors"]) == "clanguageinstructors") {
			$GLOBALS["languageinstructors"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["languageinstructors"];
		}

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'languageinstructors', TRUE);

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
			$this->Page_Terminate("languageinstructorslist.php");
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
					$this->Page_Terminate("languageinstructorslist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "languageinstructorsview.php")
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
		$this->ApplicantName->CurrentValue = NULL;
		$this->ApplicantName->OldValue = $this->ApplicantName->CurrentValue;
		$this->Nationality->CurrentValue = NULL;
		$this->Nationality->OldValue = $this->Nationality->CurrentValue;
		$this->_Language->CurrentValue = NULL;
		$this->_Language->OldValue = $this->_Language->CurrentValue;
		$this->College1->CurrentValue = NULL;
		$this->College1->OldValue = $this->College1->CurrentValue;
		$this->College1SentDate->CurrentValue = NULL;
		$this->College1SentDate->OldValue = $this->College1SentDate->CurrentValue;
		$this->College1Status->CurrentValue = NULL;
		$this->College1Status->OldValue = $this->College1Status->CurrentValue;
		$this->College1ReplyDate->CurrentValue = NULL;
		$this->College1ReplyDate->OldValue = $this->College1ReplyDate->CurrentValue;
		$this->College2->CurrentValue = NULL;
		$this->College2->OldValue = $this->College2->CurrentValue;
		$this->College2SentDate->CurrentValue = NULL;
		$this->College2SentDate->OldValue = $this->College2SentDate->CurrentValue;
		$this->College2Status->CurrentValue = NULL;
		$this->College2Status->OldValue = $this->College2Status->CurrentValue;
		$this->College2ReplyDate->CurrentValue = NULL;
		$this->College2ReplyDate->OldValue = $this->College2ReplyDate->CurrentValue;
		$this->College3->CurrentValue = NULL;
		$this->College3->OldValue = $this->College3->CurrentValue;
		$this->College3SentDate->CurrentValue = NULL;
		$this->College3SentDate->OldValue = $this->College3SentDate->CurrentValue;
		$this->College3Status->CurrentValue = NULL;
		$this->College3Status->OldValue = $this->College3Status->CurrentValue;
		$this->College3ReplyDate->CurrentValue = NULL;
		$this->College3ReplyDate->OldValue = $this->College3ReplyDate->CurrentValue;
		$this->CommitteDecision->CurrentValue = NULL;
		$this->CommitteDecision->OldValue = $this->CommitteDecision->CurrentValue;
		$this->CommitteDecisionDate->CurrentValue = NULL;
		$this->CommitteDecisionDate->OldValue = $this->CommitteDecisionDate->CurrentValue;
		$this->CommitteRefNo->CurrentValue = NULL;
		$this->CommitteRefNo->OldValue = $this->CommitteRefNo->CurrentValue;
		$this->PreidentsDecision->CurrentValue = NULL;
		$this->PreidentsDecision->OldValue = $this->PreidentsDecision->CurrentValue;
		$this->PreidentsDecisionDate->CurrentValue = NULL;
		$this->PreidentsDecisionDate->OldValue = $this->PreidentsDecisionDate->CurrentValue;
		$this->PreidentsRefNo->CurrentValue = NULL;
		$this->PreidentsRefNo->OldValue = $this->PreidentsRefNo->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
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
		if (!$this->College1SentDate->FldIsDetailKey) {
			$this->College1SentDate->setFormValue($objForm->GetValue("x_College1SentDate"));
			$this->College1SentDate->CurrentValue = ew_UnFormatDateTime($this->College1SentDate->CurrentValue, 7);
		}
		if (!$this->College1Status->FldIsDetailKey) {
			$this->College1Status->setFormValue($objForm->GetValue("x_College1Status"));
		}
		if (!$this->College1ReplyDate->FldIsDetailKey) {
			$this->College1ReplyDate->setFormValue($objForm->GetValue("x_College1ReplyDate"));
			$this->College1ReplyDate->CurrentValue = ew_UnFormatDateTime($this->College1ReplyDate->CurrentValue, 7);
		}
		if (!$this->College2->FldIsDetailKey) {
			$this->College2->setFormValue($objForm->GetValue("x_College2"));
		}
		if (!$this->College2SentDate->FldIsDetailKey) {
			$this->College2SentDate->setFormValue($objForm->GetValue("x_College2SentDate"));
			$this->College2SentDate->CurrentValue = ew_UnFormatDateTime($this->College2SentDate->CurrentValue, 7);
		}
		if (!$this->College2Status->FldIsDetailKey) {
			$this->College2Status->setFormValue($objForm->GetValue("x_College2Status"));
		}
		if (!$this->College2ReplyDate->FldIsDetailKey) {
			$this->College2ReplyDate->setFormValue($objForm->GetValue("x_College2ReplyDate"));
			$this->College2ReplyDate->CurrentValue = ew_UnFormatDateTime($this->College2ReplyDate->CurrentValue, 7);
		}
		if (!$this->College3->FldIsDetailKey) {
			$this->College3->setFormValue($objForm->GetValue("x_College3"));
		}
		if (!$this->College3SentDate->FldIsDetailKey) {
			$this->College3SentDate->setFormValue($objForm->GetValue("x_College3SentDate"));
			$this->College3SentDate->CurrentValue = ew_UnFormatDateTime($this->College3SentDate->CurrentValue, 7);
		}
		if (!$this->College3Status->FldIsDetailKey) {
			$this->College3Status->setFormValue($objForm->GetValue("x_College3Status"));
		}
		if (!$this->College3ReplyDate->FldIsDetailKey) {
			$this->College3ReplyDate->setFormValue($objForm->GetValue("x_College3ReplyDate"));
			$this->College3ReplyDate->CurrentValue = ew_UnFormatDateTime($this->College3ReplyDate->CurrentValue, 7);
		}
		if (!$this->CommitteDecision->FldIsDetailKey) {
			$this->CommitteDecision->setFormValue($objForm->GetValue("x_CommitteDecision"));
		}
		if (!$this->CommitteDecisionDate->FldIsDetailKey) {
			$this->CommitteDecisionDate->setFormValue($objForm->GetValue("x_CommitteDecisionDate"));
			$this->CommitteDecisionDate->CurrentValue = ew_UnFormatDateTime($this->CommitteDecisionDate->CurrentValue, 7);
		}
		if (!$this->CommitteRefNo->FldIsDetailKey) {
			$this->CommitteRefNo->setFormValue($objForm->GetValue("x_CommitteRefNo"));
		}
		if (!$this->PreidentsDecision->FldIsDetailKey) {
			$this->PreidentsDecision->setFormValue($objForm->GetValue("x_PreidentsDecision"));
		}
		if (!$this->PreidentsDecisionDate->FldIsDetailKey) {
			$this->PreidentsDecisionDate->setFormValue($objForm->GetValue("x_PreidentsDecisionDate"));
			$this->PreidentsDecisionDate->CurrentValue = ew_UnFormatDateTime($this->PreidentsDecisionDate->CurrentValue, 7);
		}
		if (!$this->PreidentsRefNo->FldIsDetailKey) {
			$this->PreidentsRefNo->setFormValue($objForm->GetValue("x_PreidentsRefNo"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->ApplicantName->CurrentValue = $this->ApplicantName->FormValue;
		$this->Nationality->CurrentValue = $this->Nationality->FormValue;
		$this->_Language->CurrentValue = $this->_Language->FormValue;
		$this->College1->CurrentValue = $this->College1->FormValue;
		$this->College1SentDate->CurrentValue = $this->College1SentDate->FormValue;
		$this->College1SentDate->CurrentValue = ew_UnFormatDateTime($this->College1SentDate->CurrentValue, 7);
		$this->College1Status->CurrentValue = $this->College1Status->FormValue;
		$this->College1ReplyDate->CurrentValue = $this->College1ReplyDate->FormValue;
		$this->College1ReplyDate->CurrentValue = ew_UnFormatDateTime($this->College1ReplyDate->CurrentValue, 7);
		$this->College2->CurrentValue = $this->College2->FormValue;
		$this->College2SentDate->CurrentValue = $this->College2SentDate->FormValue;
		$this->College2SentDate->CurrentValue = ew_UnFormatDateTime($this->College2SentDate->CurrentValue, 7);
		$this->College2Status->CurrentValue = $this->College2Status->FormValue;
		$this->College2ReplyDate->CurrentValue = $this->College2ReplyDate->FormValue;
		$this->College2ReplyDate->CurrentValue = ew_UnFormatDateTime($this->College2ReplyDate->CurrentValue, 7);
		$this->College3->CurrentValue = $this->College3->FormValue;
		$this->College3SentDate->CurrentValue = $this->College3SentDate->FormValue;
		$this->College3SentDate->CurrentValue = ew_UnFormatDateTime($this->College3SentDate->CurrentValue, 7);
		$this->College3Status->CurrentValue = $this->College3Status->FormValue;
		$this->College3ReplyDate->CurrentValue = $this->College3ReplyDate->FormValue;
		$this->College3ReplyDate->CurrentValue = ew_UnFormatDateTime($this->College3ReplyDate->CurrentValue, 7);
		$this->CommitteDecision->CurrentValue = $this->CommitteDecision->FormValue;
		$this->CommitteDecisionDate->CurrentValue = $this->CommitteDecisionDate->FormValue;
		$this->CommitteDecisionDate->CurrentValue = ew_UnFormatDateTime($this->CommitteDecisionDate->CurrentValue, 7);
		$this->CommitteRefNo->CurrentValue = $this->CommitteRefNo->FormValue;
		$this->PreidentsDecision->CurrentValue = $this->PreidentsDecision->FormValue;
		$this->PreidentsDecisionDate->CurrentValue = $this->PreidentsDecisionDate->FormValue;
		$this->PreidentsDecisionDate->CurrentValue = ew_UnFormatDateTime($this->PreidentsDecisionDate->CurrentValue, 7);
		$this->PreidentsRefNo->CurrentValue = $this->PreidentsRefNo->FormValue;
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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

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

			// College1SentDate
			$this->College1SentDate->EditCustomAttributes = "";
			$this->College1SentDate->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->College1SentDate->CurrentValue, 7));
			$this->College1SentDate->PlaceHolder = ew_RemoveHtml($this->College1SentDate->FldCaption());

			// College1Status
			$this->College1Status->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->College1Status->FldTagValue(1), $this->College1Status->FldTagCaption(1) <> "" ? $this->College1Status->FldTagCaption(1) : $this->College1Status->FldTagValue(1));
			$arwrk[] = array($this->College1Status->FldTagValue(2), $this->College1Status->FldTagCaption(2) <> "" ? $this->College1Status->FldTagCaption(2) : $this->College1Status->FldTagValue(2));
			$this->College1Status->EditValue = $arwrk;

			// College1ReplyDate
			$this->College1ReplyDate->EditCustomAttributes = "";
			$this->College1ReplyDate->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->College1ReplyDate->CurrentValue, 7));
			$this->College1ReplyDate->PlaceHolder = ew_RemoveHtml($this->College1ReplyDate->FldCaption());

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

			// College2SentDate
			$this->College2SentDate->EditCustomAttributes = "";
			$this->College2SentDate->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->College2SentDate->CurrentValue, 7));
			$this->College2SentDate->PlaceHolder = ew_RemoveHtml($this->College2SentDate->FldCaption());

			// College2Status
			$this->College2Status->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->College2Status->FldTagValue(1), $this->College2Status->FldTagCaption(1) <> "" ? $this->College2Status->FldTagCaption(1) : $this->College2Status->FldTagValue(1));
			$arwrk[] = array($this->College2Status->FldTagValue(2), $this->College2Status->FldTagCaption(2) <> "" ? $this->College2Status->FldTagCaption(2) : $this->College2Status->FldTagValue(2));
			$this->College2Status->EditValue = $arwrk;

			// College2ReplyDate
			$this->College2ReplyDate->EditCustomAttributes = "";
			$this->College2ReplyDate->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->College2ReplyDate->CurrentValue, 7));
			$this->College2ReplyDate->PlaceHolder = ew_RemoveHtml($this->College2ReplyDate->FldCaption());

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

			// College3SentDate
			$this->College3SentDate->EditCustomAttributes = "";
			$this->College3SentDate->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->College3SentDate->CurrentValue, 7));
			$this->College3SentDate->PlaceHolder = ew_RemoveHtml($this->College3SentDate->FldCaption());

			// College3Status
			$this->College3Status->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->College3Status->FldTagValue(1), $this->College3Status->FldTagCaption(1) <> "" ? $this->College3Status->FldTagCaption(1) : $this->College3Status->FldTagValue(1));
			$arwrk[] = array($this->College3Status->FldTagValue(2), $this->College3Status->FldTagCaption(2) <> "" ? $this->College3Status->FldTagCaption(2) : $this->College3Status->FldTagValue(2));
			$this->College3Status->EditValue = $arwrk;

			// College3ReplyDate
			$this->College3ReplyDate->EditCustomAttributes = "";
			$this->College3ReplyDate->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->College3ReplyDate->CurrentValue, 7));
			$this->College3ReplyDate->PlaceHolder = ew_RemoveHtml($this->College3ReplyDate->FldCaption());

			// CommitteDecision
			$this->CommitteDecision->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->CommitteDecision->FldTagValue(1), $this->CommitteDecision->FldTagCaption(1) <> "" ? $this->CommitteDecision->FldTagCaption(1) : $this->CommitteDecision->FldTagValue(1));
			$arwrk[] = array($this->CommitteDecision->FldTagValue(2), $this->CommitteDecision->FldTagCaption(2) <> "" ? $this->CommitteDecision->FldTagCaption(2) : $this->CommitteDecision->FldTagValue(2));
			$this->CommitteDecision->EditValue = $arwrk;

			// CommitteDecisionDate
			$this->CommitteDecisionDate->EditCustomAttributes = "";
			$this->CommitteDecisionDate->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->CommitteDecisionDate->CurrentValue, 7));
			$this->CommitteDecisionDate->PlaceHolder = ew_RemoveHtml($this->CommitteDecisionDate->FldCaption());

			// CommitteRefNo
			$this->CommitteRefNo->EditCustomAttributes = "";
			$this->CommitteRefNo->EditValue = ew_HtmlEncode($this->CommitteRefNo->CurrentValue);
			$this->CommitteRefNo->PlaceHolder = ew_RemoveHtml($this->CommitteRefNo->FldCaption());

			// PreidentsDecision
			$this->PreidentsDecision->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->PreidentsDecision->FldTagValue(1), $this->PreidentsDecision->FldTagCaption(1) <> "" ? $this->PreidentsDecision->FldTagCaption(1) : $this->PreidentsDecision->FldTagValue(1));
			$arwrk[] = array($this->PreidentsDecision->FldTagValue(2), $this->PreidentsDecision->FldTagCaption(2) <> "" ? $this->PreidentsDecision->FldTagCaption(2) : $this->PreidentsDecision->FldTagValue(2));
			$this->PreidentsDecision->EditValue = $arwrk;

			// PreidentsDecisionDate
			$this->PreidentsDecisionDate->EditCustomAttributes = "";
			$this->PreidentsDecisionDate->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->PreidentsDecisionDate->CurrentValue, 7));
			$this->PreidentsDecisionDate->PlaceHolder = ew_RemoveHtml($this->PreidentsDecisionDate->FldCaption());

			// PreidentsRefNo
			$this->PreidentsRefNo->EditCustomAttributes = "";
			$this->PreidentsRefNo->EditValue = ew_HtmlEncode($this->PreidentsRefNo->CurrentValue);
			$this->PreidentsRefNo->PlaceHolder = ew_RemoveHtml($this->PreidentsRefNo->FldCaption());

			// Edit refer script
			// ApplicantName

			$this->ApplicantName->HrefValue = "";

			// Nationality
			$this->Nationality->HrefValue = "";

			// Language
			$this->_Language->HrefValue = "";

			// College1
			$this->College1->HrefValue = "";

			// College1SentDate
			$this->College1SentDate->HrefValue = "";

			// College1Status
			$this->College1Status->HrefValue = "";

			// College1ReplyDate
			$this->College1ReplyDate->HrefValue = "";

			// College2
			$this->College2->HrefValue = "";

			// College2SentDate
			$this->College2SentDate->HrefValue = "";

			// College2Status
			$this->College2Status->HrefValue = "";

			// College2ReplyDate
			$this->College2ReplyDate->HrefValue = "";

			// College3
			$this->College3->HrefValue = "";

			// College3SentDate
			$this->College3SentDate->HrefValue = "";

			// College3Status
			$this->College3Status->HrefValue = "";

			// College3ReplyDate
			$this->College3ReplyDate->HrefValue = "";

			// CommitteDecision
			$this->CommitteDecision->HrefValue = "";

			// CommitteDecisionDate
			$this->CommitteDecisionDate->HrefValue = "";

			// CommitteRefNo
			$this->CommitteRefNo->HrefValue = "";

			// PreidentsDecision
			$this->PreidentsDecision->HrefValue = "";

			// PreidentsDecisionDate
			$this->PreidentsDecisionDate->HrefValue = "";

			// PreidentsRefNo
			$this->PreidentsRefNo->HrefValue = "";
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
		if (!ew_CheckEuroDate($this->College1SentDate->FormValue)) {
			ew_AddMessage($gsFormError, $this->College1SentDate->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->College1ReplyDate->FormValue)) {
			ew_AddMessage($gsFormError, $this->College1ReplyDate->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->College2SentDate->FormValue)) {
			ew_AddMessage($gsFormError, $this->College2SentDate->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->College2ReplyDate->FormValue)) {
			ew_AddMessage($gsFormError, $this->College2ReplyDate->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->College3SentDate->FormValue)) {
			ew_AddMessage($gsFormError, $this->College3SentDate->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->College3ReplyDate->FormValue)) {
			ew_AddMessage($gsFormError, $this->College3ReplyDate->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->CommitteDecisionDate->FormValue)) {
			ew_AddMessage($gsFormError, $this->CommitteDecisionDate->FldErrMsg());
		}
		if (!ew_CheckInteger($this->CommitteRefNo->FormValue)) {
			ew_AddMessage($gsFormError, $this->CommitteRefNo->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->PreidentsDecisionDate->FormValue)) {
			ew_AddMessage($gsFormError, $this->PreidentsDecisionDate->FldErrMsg());
		}
		if (!ew_CheckInteger($this->PreidentsRefNo->FormValue)) {
			ew_AddMessage($gsFormError, $this->PreidentsRefNo->FldErrMsg());
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

		// ApplicantName
		$this->ApplicantName->SetDbValueDef($rsnew, $this->ApplicantName->CurrentValue, NULL, FALSE);

		// Nationality
		$this->Nationality->SetDbValueDef($rsnew, $this->Nationality->CurrentValue, NULL, FALSE);

		// Language
		$this->_Language->SetDbValueDef($rsnew, $this->_Language->CurrentValue, NULL, FALSE);

		// College1
		$this->College1->SetDbValueDef($rsnew, $this->College1->CurrentValue, NULL, FALSE);

		// College1SentDate
		$this->College1SentDate->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->College1SentDate->CurrentValue, 7), NULL, FALSE);

		// College1Status
		$this->College1Status->SetDbValueDef($rsnew, $this->College1Status->CurrentValue, NULL, FALSE);

		// College1ReplyDate
		$this->College1ReplyDate->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->College1ReplyDate->CurrentValue, 7), NULL, FALSE);

		// College2
		$this->College2->SetDbValueDef($rsnew, $this->College2->CurrentValue, NULL, FALSE);

		// College2SentDate
		$this->College2SentDate->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->College2SentDate->CurrentValue, 7), NULL, FALSE);

		// College2Status
		$this->College2Status->SetDbValueDef($rsnew, $this->College2Status->CurrentValue, NULL, FALSE);

		// College2ReplyDate
		$this->College2ReplyDate->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->College2ReplyDate->CurrentValue, 7), NULL, FALSE);

		// College3
		$this->College3->SetDbValueDef($rsnew, $this->College3->CurrentValue, NULL, FALSE);

		// College3SentDate
		$this->College3SentDate->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->College3SentDate->CurrentValue, 7), NULL, FALSE);

		// College3Status
		$this->College3Status->SetDbValueDef($rsnew, $this->College3Status->CurrentValue, NULL, FALSE);

		// College3ReplyDate
		$this->College3ReplyDate->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->College3ReplyDate->CurrentValue, 7), NULL, FALSE);

		// CommitteDecision
		$this->CommitteDecision->SetDbValueDef($rsnew, $this->CommitteDecision->CurrentValue, NULL, FALSE);

		// CommitteDecisionDate
		$this->CommitteDecisionDate->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->CommitteDecisionDate->CurrentValue, 7), NULL, FALSE);

		// CommitteRefNo
		$this->CommitteRefNo->SetDbValueDef($rsnew, $this->CommitteRefNo->CurrentValue, NULL, FALSE);

		// PreidentsDecision
		$this->PreidentsDecision->SetDbValueDef($rsnew, $this->PreidentsDecision->CurrentValue, NULL, FALSE);

		// PreidentsDecisionDate
		$this->PreidentsDecisionDate->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->PreidentsDecisionDate->CurrentValue, 7), NULL, FALSE);

		// PreidentsRefNo
		$this->PreidentsRefNo->SetDbValueDef($rsnew, $this->PreidentsRefNo->CurrentValue, NULL, FALSE);

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
		$Breadcrumb->Add("list", $this->TableVar, "languageinstructorslist.php", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, ew_CurrentUrl());
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'languageinstructors';
	  $usr = CurrentUserName();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (add page)
	function WriteAuditTrailOnAdd(&$rs) {
		if (!$this->AuditTrailOnAdd) return;
		$table = 'languageinstructors';

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
if (!isset($languageinstructors_add)) $languageinstructors_add = new clanguageinstructors_add();

// Page init
$languageinstructors_add->Page_Init();

// Page main
$languageinstructors_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$languageinstructors_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var languageinstructors_add = new ew_Page("languageinstructors_add");
languageinstructors_add.PageID = "add"; // Page ID
var EW_PAGE_ID = languageinstructors_add.PageID; // For backward compatibility

// Form object
var flanguageinstructorsadd = new ew_Form("flanguageinstructorsadd");

// Validate form
flanguageinstructorsadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_College1SentDate");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($languageinstructors->College1SentDate->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_College1ReplyDate");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($languageinstructors->College1ReplyDate->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_College2SentDate");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($languageinstructors->College2SentDate->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_College2ReplyDate");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($languageinstructors->College2ReplyDate->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_College3SentDate");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($languageinstructors->College3SentDate->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_College3ReplyDate");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($languageinstructors->College3ReplyDate->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_CommitteDecisionDate");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($languageinstructors->CommitteDecisionDate->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_CommitteRefNo");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($languageinstructors->CommitteRefNo->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_PreidentsDecisionDate");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($languageinstructors->PreidentsDecisionDate->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_PreidentsRefNo");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($languageinstructors->PreidentsRefNo->FldErrMsg()) ?>");

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
flanguageinstructorsadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
flanguageinstructorsadd.ValidateRequired = true;
<?php } else { ?>
flanguageinstructorsadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
flanguageinstructorsadd.Lists["x_College1"] = {"LinkField":"x_CollegeID","Ajax":null,"AutoFill":false,"DisplayFields":["x_College_Name_EN","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
flanguageinstructorsadd.Lists["x_College2"] = {"LinkField":"x_CollegeID","Ajax":null,"AutoFill":false,"DisplayFields":["x_College_Name_EN","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
flanguageinstructorsadd.Lists["x_College3"] = {"LinkField":"x_CollegeID","Ajax":null,"AutoFill":false,"DisplayFields":["x_College_Name_EN","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $languageinstructors_add->ShowPageHeader(); ?>
<?php
$languageinstructors_add->ShowMessage();
?>
<form name="flanguageinstructorsadd" id="flanguageinstructorsadd" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="languageinstructors">
<input type="hidden" name="a_add" id="a_add" value="A">
<table class="ewGrid"><tr><td>
<table id="tbl_languageinstructorsadd" class="table table-bordered table-striped">
<?php if ($languageinstructors->ApplicantName->Visible) { // ApplicantName ?>
	<tr id="r_ApplicantName">
		<td><span id="elh_languageinstructors_ApplicantName"><?php echo $languageinstructors->ApplicantName->FldCaption() ?></span></td>
		<td<?php echo $languageinstructors->ApplicantName->CellAttributes() ?>>
<span id="el_languageinstructors_ApplicantName" class="control-group">
<input type="text" data-field="x_ApplicantName" name="x_ApplicantName" id="x_ApplicantName" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($languageinstructors->ApplicantName->PlaceHolder) ?>" value="<?php echo $languageinstructors->ApplicantName->EditValue ?>"<?php echo $languageinstructors->ApplicantName->EditAttributes() ?>>
</span>
<?php echo $languageinstructors->ApplicantName->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($languageinstructors->Nationality->Visible) { // Nationality ?>
	<tr id="r_Nationality">
		<td><span id="elh_languageinstructors_Nationality"><?php echo $languageinstructors->Nationality->FldCaption() ?></span></td>
		<td<?php echo $languageinstructors->Nationality->CellAttributes() ?>>
<span id="el_languageinstructors_Nationality" class="control-group">
<input type="text" data-field="x_Nationality" name="x_Nationality" id="x_Nationality" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($languageinstructors->Nationality->PlaceHolder) ?>" value="<?php echo $languageinstructors->Nationality->EditValue ?>"<?php echo $languageinstructors->Nationality->EditAttributes() ?>>
</span>
<?php echo $languageinstructors->Nationality->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($languageinstructors->_Language->Visible) { // Language ?>
	<tr id="r__Language">
		<td><span id="elh_languageinstructors__Language"><?php echo $languageinstructors->_Language->FldCaption() ?></span></td>
		<td<?php echo $languageinstructors->_Language->CellAttributes() ?>>
<span id="el_languageinstructors__Language" class="control-group">
<div id="tp_x__Language" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x__Language" id="x__Language" value="{value}"<?php echo $languageinstructors->_Language->EditAttributes() ?>></div>
<div id="dsl_x__Language" data-repeatcolumn="5" class="ewItemList">
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
<label class="radio"><input type="radio" data-field="x__Language" name="x__Language" id="x__Language_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $languageinstructors->_Language->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $languageinstructors->_Language->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($languageinstructors->College1->Visible) { // College1 ?>
	<tr id="r_College1">
		<td><span id="elh_languageinstructors_College1"><?php echo $languageinstructors->College1->FldCaption() ?></span></td>
		<td<?php echo $languageinstructors->College1->CellAttributes() ?>>
<span id="el_languageinstructors_College1" class="control-group">
<select data-field="x_College1" id="x_College1" name="x_College1"<?php echo $languageinstructors->College1->EditAttributes() ?>>
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
flanguageinstructorsadd.Lists["x_College1"].Options = <?php echo (is_array($languageinstructors->College1->EditValue)) ? ew_ArrayToJson($languageinstructors->College1->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $languageinstructors->College1->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($languageinstructors->College1SentDate->Visible) { // College1SentDate ?>
	<tr id="r_College1SentDate">
		<td><span id="elh_languageinstructors_College1SentDate"><?php echo $languageinstructors->College1SentDate->FldCaption() ?></span></td>
		<td<?php echo $languageinstructors->College1SentDate->CellAttributes() ?>>
<span id="el_languageinstructors_College1SentDate" class="control-group">
<input type="text" data-field="x_College1SentDate" name="x_College1SentDate" id="x_College1SentDate" placeholder="<?php echo ew_HtmlEncode($languageinstructors->College1SentDate->PlaceHolder) ?>" value="<?php echo $languageinstructors->College1SentDate->EditValue ?>"<?php echo $languageinstructors->College1SentDate->EditAttributes() ?>>
<?php if (!$languageinstructors->College1SentDate->ReadOnly && !$languageinstructors->College1SentDate->Disabled && @$languageinstructors->College1SentDate->EditAttrs["readonly"] == "" && @$languageinstructors->College1SentDate->EditAttrs["disabled"] == "") { ?>
<button id="cal_x_College1SentDate" name="cal_x_College1SentDate" class="btn" type="button"><img src="phpimages/calendar.png" alt="<?php echo $Language->Phrase("PickDate") ?>" title="<?php echo $Language->Phrase("PickDate") ?>" style="border: 0;"></button><script type="text/javascript">
ew_CreateCalendar("flanguageinstructorsadd", "x_College1SentDate", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $languageinstructors->College1SentDate->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($languageinstructors->College1Status->Visible) { // College1Status ?>
	<tr id="r_College1Status">
		<td><span id="elh_languageinstructors_College1Status"><?php echo $languageinstructors->College1Status->FldCaption() ?></span></td>
		<td<?php echo $languageinstructors->College1Status->CellAttributes() ?>>
<span id="el_languageinstructors_College1Status" class="control-group">
<div id="tp_x_College1Status" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_College1Status" id="x_College1Status" value="{value}"<?php echo $languageinstructors->College1Status->EditAttributes() ?>></div>
<div id="dsl_x_College1Status" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $languageinstructors->College1Status->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($languageinstructors->College1Status->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_College1Status" name="x_College1Status" id="x_College1Status_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $languageinstructors->College1Status->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $languageinstructors->College1Status->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($languageinstructors->College1ReplyDate->Visible) { // College1ReplyDate ?>
	<tr id="r_College1ReplyDate">
		<td><span id="elh_languageinstructors_College1ReplyDate"><?php echo $languageinstructors->College1ReplyDate->FldCaption() ?></span></td>
		<td<?php echo $languageinstructors->College1ReplyDate->CellAttributes() ?>>
<span id="el_languageinstructors_College1ReplyDate" class="control-group">
<input type="text" data-field="x_College1ReplyDate" name="x_College1ReplyDate" id="x_College1ReplyDate" placeholder="<?php echo ew_HtmlEncode($languageinstructors->College1ReplyDate->PlaceHolder) ?>" value="<?php echo $languageinstructors->College1ReplyDate->EditValue ?>"<?php echo $languageinstructors->College1ReplyDate->EditAttributes() ?>>
<?php if (!$languageinstructors->College1ReplyDate->ReadOnly && !$languageinstructors->College1ReplyDate->Disabled && @$languageinstructors->College1ReplyDate->EditAttrs["readonly"] == "" && @$languageinstructors->College1ReplyDate->EditAttrs["disabled"] == "") { ?>
<button id="cal_x_College1ReplyDate" name="cal_x_College1ReplyDate" class="btn" type="button"><img src="phpimages/calendar.png" alt="<?php echo $Language->Phrase("PickDate") ?>" title="<?php echo $Language->Phrase("PickDate") ?>" style="border: 0;"></button><script type="text/javascript">
ew_CreateCalendar("flanguageinstructorsadd", "x_College1ReplyDate", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $languageinstructors->College1ReplyDate->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($languageinstructors->College2->Visible) { // College2 ?>
	<tr id="r_College2">
		<td><span id="elh_languageinstructors_College2"><?php echo $languageinstructors->College2->FldCaption() ?></span></td>
		<td<?php echo $languageinstructors->College2->CellAttributes() ?>>
<span id="el_languageinstructors_College2" class="control-group">
<select data-field="x_College2" id="x_College2" name="x_College2"<?php echo $languageinstructors->College2->EditAttributes() ?>>
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
flanguageinstructorsadd.Lists["x_College2"].Options = <?php echo (is_array($languageinstructors->College2->EditValue)) ? ew_ArrayToJson($languageinstructors->College2->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $languageinstructors->College2->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($languageinstructors->College2SentDate->Visible) { // College2SentDate ?>
	<tr id="r_College2SentDate">
		<td><span id="elh_languageinstructors_College2SentDate"><?php echo $languageinstructors->College2SentDate->FldCaption() ?></span></td>
		<td<?php echo $languageinstructors->College2SentDate->CellAttributes() ?>>
<span id="el_languageinstructors_College2SentDate" class="control-group">
<input type="text" data-field="x_College2SentDate" name="x_College2SentDate" id="x_College2SentDate" placeholder="<?php echo ew_HtmlEncode($languageinstructors->College2SentDate->PlaceHolder) ?>" value="<?php echo $languageinstructors->College2SentDate->EditValue ?>"<?php echo $languageinstructors->College2SentDate->EditAttributes() ?>>
<?php if (!$languageinstructors->College2SentDate->ReadOnly && !$languageinstructors->College2SentDate->Disabled && @$languageinstructors->College2SentDate->EditAttrs["readonly"] == "" && @$languageinstructors->College2SentDate->EditAttrs["disabled"] == "") { ?>
<button id="cal_x_College2SentDate" name="cal_x_College2SentDate" class="btn" type="button"><img src="phpimages/calendar.png" alt="<?php echo $Language->Phrase("PickDate") ?>" title="<?php echo $Language->Phrase("PickDate") ?>" style="border: 0;"></button><script type="text/javascript">
ew_CreateCalendar("flanguageinstructorsadd", "x_College2SentDate", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $languageinstructors->College2SentDate->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($languageinstructors->College2Status->Visible) { // College2Status ?>
	<tr id="r_College2Status">
		<td><span id="elh_languageinstructors_College2Status"><?php echo $languageinstructors->College2Status->FldCaption() ?></span></td>
		<td<?php echo $languageinstructors->College2Status->CellAttributes() ?>>
<span id="el_languageinstructors_College2Status" class="control-group">
<div id="tp_x_College2Status" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_College2Status" id="x_College2Status" value="{value}"<?php echo $languageinstructors->College2Status->EditAttributes() ?>></div>
<div id="dsl_x_College2Status" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $languageinstructors->College2Status->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($languageinstructors->College2Status->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_College2Status" name="x_College2Status" id="x_College2Status_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $languageinstructors->College2Status->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $languageinstructors->College2Status->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($languageinstructors->College2ReplyDate->Visible) { // College2ReplyDate ?>
	<tr id="r_College2ReplyDate">
		<td><span id="elh_languageinstructors_College2ReplyDate"><?php echo $languageinstructors->College2ReplyDate->FldCaption() ?></span></td>
		<td<?php echo $languageinstructors->College2ReplyDate->CellAttributes() ?>>
<span id="el_languageinstructors_College2ReplyDate" class="control-group">
<input type="text" data-field="x_College2ReplyDate" name="x_College2ReplyDate" id="x_College2ReplyDate" placeholder="<?php echo ew_HtmlEncode($languageinstructors->College2ReplyDate->PlaceHolder) ?>" value="<?php echo $languageinstructors->College2ReplyDate->EditValue ?>"<?php echo $languageinstructors->College2ReplyDate->EditAttributes() ?>>
<?php if (!$languageinstructors->College2ReplyDate->ReadOnly && !$languageinstructors->College2ReplyDate->Disabled && @$languageinstructors->College2ReplyDate->EditAttrs["readonly"] == "" && @$languageinstructors->College2ReplyDate->EditAttrs["disabled"] == "") { ?>
<button id="cal_x_College2ReplyDate" name="cal_x_College2ReplyDate" class="btn" type="button"><img src="phpimages/calendar.png" alt="<?php echo $Language->Phrase("PickDate") ?>" title="<?php echo $Language->Phrase("PickDate") ?>" style="border: 0;"></button><script type="text/javascript">
ew_CreateCalendar("flanguageinstructorsadd", "x_College2ReplyDate", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $languageinstructors->College2ReplyDate->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($languageinstructors->College3->Visible) { // College3 ?>
	<tr id="r_College3">
		<td><span id="elh_languageinstructors_College3"><?php echo $languageinstructors->College3->FldCaption() ?></span></td>
		<td<?php echo $languageinstructors->College3->CellAttributes() ?>>
<span id="el_languageinstructors_College3" class="control-group">
<select data-field="x_College3" id="x_College3" name="x_College3"<?php echo $languageinstructors->College3->EditAttributes() ?>>
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
flanguageinstructorsadd.Lists["x_College3"].Options = <?php echo (is_array($languageinstructors->College3->EditValue)) ? ew_ArrayToJson($languageinstructors->College3->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $languageinstructors->College3->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($languageinstructors->College3SentDate->Visible) { // College3SentDate ?>
	<tr id="r_College3SentDate">
		<td><span id="elh_languageinstructors_College3SentDate"><?php echo $languageinstructors->College3SentDate->FldCaption() ?></span></td>
		<td<?php echo $languageinstructors->College3SentDate->CellAttributes() ?>>
<span id="el_languageinstructors_College3SentDate" class="control-group">
<input type="text" data-field="x_College3SentDate" name="x_College3SentDate" id="x_College3SentDate" placeholder="<?php echo ew_HtmlEncode($languageinstructors->College3SentDate->PlaceHolder) ?>" value="<?php echo $languageinstructors->College3SentDate->EditValue ?>"<?php echo $languageinstructors->College3SentDate->EditAttributes() ?>>
<?php if (!$languageinstructors->College3SentDate->ReadOnly && !$languageinstructors->College3SentDate->Disabled && @$languageinstructors->College3SentDate->EditAttrs["readonly"] == "" && @$languageinstructors->College3SentDate->EditAttrs["disabled"] == "") { ?>
<button id="cal_x_College3SentDate" name="cal_x_College3SentDate" class="btn" type="button"><img src="phpimages/calendar.png" alt="<?php echo $Language->Phrase("PickDate") ?>" title="<?php echo $Language->Phrase("PickDate") ?>" style="border: 0;"></button><script type="text/javascript">
ew_CreateCalendar("flanguageinstructorsadd", "x_College3SentDate", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $languageinstructors->College3SentDate->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($languageinstructors->College3Status->Visible) { // College3Status ?>
	<tr id="r_College3Status">
		<td><span id="elh_languageinstructors_College3Status"><?php echo $languageinstructors->College3Status->FldCaption() ?></span></td>
		<td<?php echo $languageinstructors->College3Status->CellAttributes() ?>>
<span id="el_languageinstructors_College3Status" class="control-group">
<div id="tp_x_College3Status" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_College3Status" id="x_College3Status" value="{value}"<?php echo $languageinstructors->College3Status->EditAttributes() ?>></div>
<div id="dsl_x_College3Status" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $languageinstructors->College3Status->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($languageinstructors->College3Status->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_College3Status" name="x_College3Status" id="x_College3Status_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $languageinstructors->College3Status->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $languageinstructors->College3Status->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($languageinstructors->College3ReplyDate->Visible) { // College3ReplyDate ?>
	<tr id="r_College3ReplyDate">
		<td><span id="elh_languageinstructors_College3ReplyDate"><?php echo $languageinstructors->College3ReplyDate->FldCaption() ?></span></td>
		<td<?php echo $languageinstructors->College3ReplyDate->CellAttributes() ?>>
<span id="el_languageinstructors_College3ReplyDate" class="control-group">
<input type="text" data-field="x_College3ReplyDate" name="x_College3ReplyDate" id="x_College3ReplyDate" placeholder="<?php echo ew_HtmlEncode($languageinstructors->College3ReplyDate->PlaceHolder) ?>" value="<?php echo $languageinstructors->College3ReplyDate->EditValue ?>"<?php echo $languageinstructors->College3ReplyDate->EditAttributes() ?>>
<?php if (!$languageinstructors->College3ReplyDate->ReadOnly && !$languageinstructors->College3ReplyDate->Disabled && @$languageinstructors->College3ReplyDate->EditAttrs["readonly"] == "" && @$languageinstructors->College3ReplyDate->EditAttrs["disabled"] == "") { ?>
<button id="cal_x_College3ReplyDate" name="cal_x_College3ReplyDate" class="btn" type="button"><img src="phpimages/calendar.png" alt="<?php echo $Language->Phrase("PickDate") ?>" title="<?php echo $Language->Phrase("PickDate") ?>" style="border: 0;"></button><script type="text/javascript">
ew_CreateCalendar("flanguageinstructorsadd", "x_College3ReplyDate", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $languageinstructors->College3ReplyDate->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($languageinstructors->CommitteDecision->Visible) { // CommitteDecision ?>
	<tr id="r_CommitteDecision">
		<td><span id="elh_languageinstructors_CommitteDecision"><?php echo $languageinstructors->CommitteDecision->FldCaption() ?></span></td>
		<td<?php echo $languageinstructors->CommitteDecision->CellAttributes() ?>>
<span id="el_languageinstructors_CommitteDecision" class="control-group">
<div id="tp_x_CommitteDecision" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_CommitteDecision" id="x_CommitteDecision" value="{value}"<?php echo $languageinstructors->CommitteDecision->EditAttributes() ?>></div>
<div id="dsl_x_CommitteDecision" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $languageinstructors->CommitteDecision->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($languageinstructors->CommitteDecision->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_CommitteDecision" name="x_CommitteDecision" id="x_CommitteDecision_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $languageinstructors->CommitteDecision->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $languageinstructors->CommitteDecision->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($languageinstructors->CommitteDecisionDate->Visible) { // CommitteDecisionDate ?>
	<tr id="r_CommitteDecisionDate">
		<td><span id="elh_languageinstructors_CommitteDecisionDate"><?php echo $languageinstructors->CommitteDecisionDate->FldCaption() ?></span></td>
		<td<?php echo $languageinstructors->CommitteDecisionDate->CellAttributes() ?>>
<span id="el_languageinstructors_CommitteDecisionDate" class="control-group">
<input type="text" data-field="x_CommitteDecisionDate" name="x_CommitteDecisionDate" id="x_CommitteDecisionDate" placeholder="<?php echo ew_HtmlEncode($languageinstructors->CommitteDecisionDate->PlaceHolder) ?>" value="<?php echo $languageinstructors->CommitteDecisionDate->EditValue ?>"<?php echo $languageinstructors->CommitteDecisionDate->EditAttributes() ?>>
<?php if (!$languageinstructors->CommitteDecisionDate->ReadOnly && !$languageinstructors->CommitteDecisionDate->Disabled && @$languageinstructors->CommitteDecisionDate->EditAttrs["readonly"] == "" && @$languageinstructors->CommitteDecisionDate->EditAttrs["disabled"] == "") { ?>
<button id="cal_x_CommitteDecisionDate" name="cal_x_CommitteDecisionDate" class="btn" type="button"><img src="phpimages/calendar.png" alt="<?php echo $Language->Phrase("PickDate") ?>" title="<?php echo $Language->Phrase("PickDate") ?>" style="border: 0;"></button><script type="text/javascript">
ew_CreateCalendar("flanguageinstructorsadd", "x_CommitteDecisionDate", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $languageinstructors->CommitteDecisionDate->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($languageinstructors->CommitteRefNo->Visible) { // CommitteRefNo ?>
	<tr id="r_CommitteRefNo">
		<td><span id="elh_languageinstructors_CommitteRefNo"><?php echo $languageinstructors->CommitteRefNo->FldCaption() ?></span></td>
		<td<?php echo $languageinstructors->CommitteRefNo->CellAttributes() ?>>
<span id="el_languageinstructors_CommitteRefNo" class="control-group">
<input type="text" data-field="x_CommitteRefNo" name="x_CommitteRefNo" id="x_CommitteRefNo" size="30" placeholder="<?php echo ew_HtmlEncode($languageinstructors->CommitteRefNo->PlaceHolder) ?>" value="<?php echo $languageinstructors->CommitteRefNo->EditValue ?>"<?php echo $languageinstructors->CommitteRefNo->EditAttributes() ?>>
</span>
<?php echo $languageinstructors->CommitteRefNo->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($languageinstructors->PreidentsDecision->Visible) { // PreidentsDecision ?>
	<tr id="r_PreidentsDecision">
		<td><span id="elh_languageinstructors_PreidentsDecision"><?php echo $languageinstructors->PreidentsDecision->FldCaption() ?></span></td>
		<td<?php echo $languageinstructors->PreidentsDecision->CellAttributes() ?>>
<span id="el_languageinstructors_PreidentsDecision" class="control-group">
<div id="tp_x_PreidentsDecision" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_PreidentsDecision" id="x_PreidentsDecision" value="{value}"<?php echo $languageinstructors->PreidentsDecision->EditAttributes() ?>></div>
<div id="dsl_x_PreidentsDecision" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $languageinstructors->PreidentsDecision->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($languageinstructors->PreidentsDecision->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_PreidentsDecision" name="x_PreidentsDecision" id="x_PreidentsDecision_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $languageinstructors->PreidentsDecision->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $languageinstructors->PreidentsDecision->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($languageinstructors->PreidentsDecisionDate->Visible) { // PreidentsDecisionDate ?>
	<tr id="r_PreidentsDecisionDate">
		<td><span id="elh_languageinstructors_PreidentsDecisionDate"><?php echo $languageinstructors->PreidentsDecisionDate->FldCaption() ?></span></td>
		<td<?php echo $languageinstructors->PreidentsDecisionDate->CellAttributes() ?>>
<span id="el_languageinstructors_PreidentsDecisionDate" class="control-group">
<input type="text" data-field="x_PreidentsDecisionDate" name="x_PreidentsDecisionDate" id="x_PreidentsDecisionDate" placeholder="<?php echo ew_HtmlEncode($languageinstructors->PreidentsDecisionDate->PlaceHolder) ?>" value="<?php echo $languageinstructors->PreidentsDecisionDate->EditValue ?>"<?php echo $languageinstructors->PreidentsDecisionDate->EditAttributes() ?>>
<?php if (!$languageinstructors->PreidentsDecisionDate->ReadOnly && !$languageinstructors->PreidentsDecisionDate->Disabled && @$languageinstructors->PreidentsDecisionDate->EditAttrs["readonly"] == "" && @$languageinstructors->PreidentsDecisionDate->EditAttrs["disabled"] == "") { ?>
<button id="cal_x_PreidentsDecisionDate" name="cal_x_PreidentsDecisionDate" class="btn" type="button"><img src="phpimages/calendar.png" alt="<?php echo $Language->Phrase("PickDate") ?>" title="<?php echo $Language->Phrase("PickDate") ?>" style="border: 0;"></button><script type="text/javascript">
ew_CreateCalendar("flanguageinstructorsadd", "x_PreidentsDecisionDate", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $languageinstructors->PreidentsDecisionDate->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($languageinstructors->PreidentsRefNo->Visible) { // PreidentsRefNo ?>
	<tr id="r_PreidentsRefNo">
		<td><span id="elh_languageinstructors_PreidentsRefNo"><?php echo $languageinstructors->PreidentsRefNo->FldCaption() ?></span></td>
		<td<?php echo $languageinstructors->PreidentsRefNo->CellAttributes() ?>>
<span id="el_languageinstructors_PreidentsRefNo" class="control-group">
<input type="text" data-field="x_PreidentsRefNo" name="x_PreidentsRefNo" id="x_PreidentsRefNo" size="30" placeholder="<?php echo ew_HtmlEncode($languageinstructors->PreidentsRefNo->PlaceHolder) ?>" value="<?php echo $languageinstructors->PreidentsRefNo->EditValue ?>"<?php echo $languageinstructors->PreidentsRefNo->EditAttributes() ?>>
</span>
<?php echo $languageinstructors->PreidentsRefNo->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
</form>
<script type="text/javascript">
flanguageinstructorsadd.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$languageinstructors_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$languageinstructors_add->Page_Terminate();
?>
