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

$facultyapplication_add = NULL; // Initialize page object first

class cfacultyapplication_add extends cfacultyapplication {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{F8E6AD82-57C4-4BEE-8975-8D386184467A}";

	// Table name
	var $TableName = 'facultyapplication';

	// Page object name
	var $PageObjName = 'facultyapplication_add';

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

		// Table object (facultyapplication)
		if (!isset($GLOBALS["facultyapplication"]) || get_class($GLOBALS["facultyapplication"]) == "cfacultyapplication") {
			$GLOBALS["facultyapplication"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["facultyapplication"];
		}

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'facultyapplication', TRUE);

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
			$this->Page_Terminate("facultyapplicationlist.php");
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
					$this->Page_Terminate("facultyapplicationlist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "facultyapplicationview.php")
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
		$this->Nationality->CurrentValue = NULL;
		$this->Nationality->OldValue = $this->Nationality->CurrentValue;
		$this->College->CurrentValue = NULL;
		$this->College->OldValue = $this->College->CurrentValue;
		$this->Department->CurrentValue = NULL;
		$this->Department->OldValue = $this->Department->CurrentValue;
		$this->FacultyAffairsDate->CurrentValue = NULL;
		$this->FacultyAffairsDate->OldValue = $this->FacultyAffairsDate->CurrentValue;
		$this->FacultyAffairsRef->CurrentValue = NULL;
		$this->FacultyAffairsRef->OldValue = $this->FacultyAffairsRef->CurrentValue;
		$this->CollegeDecision->CurrentValue = NULL;
		$this->CollegeDecision->OldValue = $this->CollegeDecision->CurrentValue;
		$this->CollegeDecisionDate->CurrentValue = NULL;
		$this->CollegeDecisionDate->OldValue = $this->CollegeDecisionDate->CurrentValue;
		$this->CollegeDecisionRef->CurrentValue = NULL;
		$this->CollegeDecisionRef->OldValue = $this->CollegeDecisionRef->CurrentValue;
		$this->CommitteeDecision->CurrentValue = NULL;
		$this->CommitteeDecision->OldValue = $this->CommitteeDecision->CurrentValue;
		$this->CommitteeDecisionDate->CurrentValue = NULL;
		$this->CommitteeDecisionDate->OldValue = $this->CommitteeDecisionDate->CurrentValue;
		$this->CommitteeDecisionRef->CurrentValue = NULL;
		$this->CommitteeDecisionRef->OldValue = $this->CommitteeDecisionRef->CurrentValue;
		$this->PresidentDecision->CurrentValue = NULL;
		$this->PresidentDecision->OldValue = $this->PresidentDecision->CurrentValue;
		$this->PresidentDecisionDate->CurrentValue = NULL;
		$this->PresidentDecisionDate->OldValue = $this->PresidentDecisionDate->CurrentValue;
		$this->PresidentDecisionRef->CurrentValue = NULL;
		$this->PresidentDecisionRef->OldValue = $this->PresidentDecisionRef->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->Name->FldIsDetailKey) {
			$this->Name->setFormValue($objForm->GetValue("x_Name"));
		}
		if (!$this->Nationality->FldIsDetailKey) {
			$this->Nationality->setFormValue($objForm->GetValue("x_Nationality"));
		}
		if (!$this->College->FldIsDetailKey) {
			$this->College->setFormValue($objForm->GetValue("x_College"));
		}
		if (!$this->Department->FldIsDetailKey) {
			$this->Department->setFormValue($objForm->GetValue("x_Department"));
		}
		if (!$this->FacultyAffairsDate->FldIsDetailKey) {
			$this->FacultyAffairsDate->setFormValue($objForm->GetValue("x_FacultyAffairsDate"));
			$this->FacultyAffairsDate->CurrentValue = ew_UnFormatDateTime($this->FacultyAffairsDate->CurrentValue, 7);
		}
		if (!$this->FacultyAffairsRef->FldIsDetailKey) {
			$this->FacultyAffairsRef->setFormValue($objForm->GetValue("x_FacultyAffairsRef"));
		}
		if (!$this->CollegeDecision->FldIsDetailKey) {
			$this->CollegeDecision->setFormValue($objForm->GetValue("x_CollegeDecision"));
		}
		if (!$this->CollegeDecisionDate->FldIsDetailKey) {
			$this->CollegeDecisionDate->setFormValue($objForm->GetValue("x_CollegeDecisionDate"));
			$this->CollegeDecisionDate->CurrentValue = ew_UnFormatDateTime($this->CollegeDecisionDate->CurrentValue, 7);
		}
		if (!$this->CollegeDecisionRef->FldIsDetailKey) {
			$this->CollegeDecisionRef->setFormValue($objForm->GetValue("x_CollegeDecisionRef"));
		}
		if (!$this->CommitteeDecision->FldIsDetailKey) {
			$this->CommitteeDecision->setFormValue($objForm->GetValue("x_CommitteeDecision"));
		}
		if (!$this->CommitteeDecisionDate->FldIsDetailKey) {
			$this->CommitteeDecisionDate->setFormValue($objForm->GetValue("x_CommitteeDecisionDate"));
			$this->CommitteeDecisionDate->CurrentValue = ew_UnFormatDateTime($this->CommitteeDecisionDate->CurrentValue, 7);
		}
		if (!$this->CommitteeDecisionRef->FldIsDetailKey) {
			$this->CommitteeDecisionRef->setFormValue($objForm->GetValue("x_CommitteeDecisionRef"));
		}
		if (!$this->PresidentDecision->FldIsDetailKey) {
			$this->PresidentDecision->setFormValue($objForm->GetValue("x_PresidentDecision"));
		}
		if (!$this->PresidentDecisionDate->FldIsDetailKey) {
			$this->PresidentDecisionDate->setFormValue($objForm->GetValue("x_PresidentDecisionDate"));
			$this->PresidentDecisionDate->CurrentValue = ew_UnFormatDateTime($this->PresidentDecisionDate->CurrentValue, 7);
		}
		if (!$this->PresidentDecisionRef->FldIsDetailKey) {
			$this->PresidentDecisionRef->setFormValue($objForm->GetValue("x_PresidentDecisionRef"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->Name->CurrentValue = $this->Name->FormValue;
		$this->Nationality->CurrentValue = $this->Nationality->FormValue;
		$this->College->CurrentValue = $this->College->FormValue;
		$this->Department->CurrentValue = $this->Department->FormValue;
		$this->FacultyAffairsDate->CurrentValue = $this->FacultyAffairsDate->FormValue;
		$this->FacultyAffairsDate->CurrentValue = ew_UnFormatDateTime($this->FacultyAffairsDate->CurrentValue, 7);
		$this->FacultyAffairsRef->CurrentValue = $this->FacultyAffairsRef->FormValue;
		$this->CollegeDecision->CurrentValue = $this->CollegeDecision->FormValue;
		$this->CollegeDecisionDate->CurrentValue = $this->CollegeDecisionDate->FormValue;
		$this->CollegeDecisionDate->CurrentValue = ew_UnFormatDateTime($this->CollegeDecisionDate->CurrentValue, 7);
		$this->CollegeDecisionRef->CurrentValue = $this->CollegeDecisionRef->FormValue;
		$this->CommitteeDecision->CurrentValue = $this->CommitteeDecision->FormValue;
		$this->CommitteeDecisionDate->CurrentValue = $this->CommitteeDecisionDate->FormValue;
		$this->CommitteeDecisionDate->CurrentValue = ew_UnFormatDateTime($this->CommitteeDecisionDate->CurrentValue, 7);
		$this->CommitteeDecisionRef->CurrentValue = $this->CommitteeDecisionRef->FormValue;
		$this->PresidentDecision->CurrentValue = $this->PresidentDecision->FormValue;
		$this->PresidentDecisionDate->CurrentValue = $this->PresidentDecisionDate->FormValue;
		$this->PresidentDecisionDate->CurrentValue = ew_UnFormatDateTime($this->PresidentDecisionDate->CurrentValue, 7);
		$this->PresidentDecisionRef->CurrentValue = $this->PresidentDecisionRef->FormValue;
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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// Name
			$this->Name->EditCustomAttributes = "";
			$this->Name->EditValue = ew_HtmlEncode($this->Name->CurrentValue);
			$this->Name->PlaceHolder = ew_RemoveHtml($this->Name->FldCaption());

			// Nationality
			$this->Nationality->EditCustomAttributes = "";
			$this->Nationality->EditValue = ew_HtmlEncode($this->Nationality->CurrentValue);
			$this->Nationality->PlaceHolder = ew_RemoveHtml($this->Nationality->FldCaption());

			// College
			$this->College->EditCustomAttributes = "";
			$this->College->EditValue = ew_HtmlEncode($this->College->CurrentValue);
			$this->College->PlaceHolder = ew_RemoveHtml($this->College->FldCaption());

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

			// FacultyAffairsDate
			$this->FacultyAffairsDate->EditCustomAttributes = "";
			$this->FacultyAffairsDate->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->FacultyAffairsDate->CurrentValue, 7));
			$this->FacultyAffairsDate->PlaceHolder = ew_RemoveHtml($this->FacultyAffairsDate->FldCaption());

			// FacultyAffairsRef
			$this->FacultyAffairsRef->EditCustomAttributes = "";
			$this->FacultyAffairsRef->EditValue = ew_HtmlEncode($this->FacultyAffairsRef->CurrentValue);
			$this->FacultyAffairsRef->PlaceHolder = ew_RemoveHtml($this->FacultyAffairsRef->FldCaption());

			// CollegeDecision
			$this->CollegeDecision->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->CollegeDecision->FldTagValue(1), $this->CollegeDecision->FldTagCaption(1) <> "" ? $this->CollegeDecision->FldTagCaption(1) : $this->CollegeDecision->FldTagValue(1));
			$arwrk[] = array($this->CollegeDecision->FldTagValue(2), $this->CollegeDecision->FldTagCaption(2) <> "" ? $this->CollegeDecision->FldTagCaption(2) : $this->CollegeDecision->FldTagValue(2));
			$this->CollegeDecision->EditValue = $arwrk;

			// CollegeDecisionDate
			$this->CollegeDecisionDate->EditCustomAttributes = "";
			$this->CollegeDecisionDate->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->CollegeDecisionDate->CurrentValue, 7));
			$this->CollegeDecisionDate->PlaceHolder = ew_RemoveHtml($this->CollegeDecisionDate->FldCaption());

			// CollegeDecisionRef
			$this->CollegeDecisionRef->EditCustomAttributes = "";
			$this->CollegeDecisionRef->EditValue = ew_HtmlEncode($this->CollegeDecisionRef->CurrentValue);
			$this->CollegeDecisionRef->PlaceHolder = ew_RemoveHtml($this->CollegeDecisionRef->FldCaption());

			// CommitteeDecision
			$this->CommitteeDecision->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->CommitteeDecision->FldTagValue(1), $this->CommitteeDecision->FldTagCaption(1) <> "" ? $this->CommitteeDecision->FldTagCaption(1) : $this->CommitteeDecision->FldTagValue(1));
			$arwrk[] = array($this->CommitteeDecision->FldTagValue(2), $this->CommitteeDecision->FldTagCaption(2) <> "" ? $this->CommitteeDecision->FldTagCaption(2) : $this->CommitteeDecision->FldTagValue(2));
			$this->CommitteeDecision->EditValue = $arwrk;

			// CommitteeDecisionDate
			$this->CommitteeDecisionDate->EditCustomAttributes = "";
			$this->CommitteeDecisionDate->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->CommitteeDecisionDate->CurrentValue, 7));
			$this->CommitteeDecisionDate->PlaceHolder = ew_RemoveHtml($this->CommitteeDecisionDate->FldCaption());

			// CommitteeDecisionRef
			$this->CommitteeDecisionRef->EditCustomAttributes = "";
			$this->CommitteeDecisionRef->EditValue = ew_HtmlEncode($this->CommitteeDecisionRef->CurrentValue);
			$this->CommitteeDecisionRef->PlaceHolder = ew_RemoveHtml($this->CommitteeDecisionRef->FldCaption());

			// PresidentDecision
			$this->PresidentDecision->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->PresidentDecision->FldTagValue(1), $this->PresidentDecision->FldTagCaption(1) <> "" ? $this->PresidentDecision->FldTagCaption(1) : $this->PresidentDecision->FldTagValue(1));
			$arwrk[] = array($this->PresidentDecision->FldTagValue(2), $this->PresidentDecision->FldTagCaption(2) <> "" ? $this->PresidentDecision->FldTagCaption(2) : $this->PresidentDecision->FldTagValue(2));
			$this->PresidentDecision->EditValue = $arwrk;

			// PresidentDecisionDate
			$this->PresidentDecisionDate->EditCustomAttributes = "";
			$this->PresidentDecisionDate->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->PresidentDecisionDate->CurrentValue, 7));
			$this->PresidentDecisionDate->PlaceHolder = ew_RemoveHtml($this->PresidentDecisionDate->FldCaption());

			// PresidentDecisionRef
			$this->PresidentDecisionRef->EditCustomAttributes = "";
			$this->PresidentDecisionRef->EditValue = ew_HtmlEncode($this->PresidentDecisionRef->CurrentValue);
			$this->PresidentDecisionRef->PlaceHolder = ew_RemoveHtml($this->PresidentDecisionRef->FldCaption());

			// Edit refer script
			// Name

			$this->Name->HrefValue = "";

			// Nationality
			$this->Nationality->HrefValue = "";

			// College
			$this->College->HrefValue = "";

			// Department
			$this->Department->HrefValue = "";

			// FacultyAffairsDate
			$this->FacultyAffairsDate->HrefValue = "";

			// FacultyAffairsRef
			$this->FacultyAffairsRef->HrefValue = "";

			// CollegeDecision
			$this->CollegeDecision->HrefValue = "";

			// CollegeDecisionDate
			$this->CollegeDecisionDate->HrefValue = "";

			// CollegeDecisionRef
			$this->CollegeDecisionRef->HrefValue = "";

			// CommitteeDecision
			$this->CommitteeDecision->HrefValue = "";

			// CommitteeDecisionDate
			$this->CommitteeDecisionDate->HrefValue = "";

			// CommitteeDecisionRef
			$this->CommitteeDecisionRef->HrefValue = "";

			// PresidentDecision
			$this->PresidentDecision->HrefValue = "";

			// PresidentDecisionDate
			$this->PresidentDecisionDate->HrefValue = "";

			// PresidentDecisionRef
			$this->PresidentDecisionRef->HrefValue = "";
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
		if (!ew_CheckEuroDate($this->FacultyAffairsDate->FormValue)) {
			ew_AddMessage($gsFormError, $this->FacultyAffairsDate->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->CollegeDecisionDate->FormValue)) {
			ew_AddMessage($gsFormError, $this->CollegeDecisionDate->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->CommitteeDecisionDate->FormValue)) {
			ew_AddMessage($gsFormError, $this->CommitteeDecisionDate->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->PresidentDecisionDate->FormValue)) {
			ew_AddMessage($gsFormError, $this->PresidentDecisionDate->FldErrMsg());
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

		// Nationality
		$this->Nationality->SetDbValueDef($rsnew, $this->Nationality->CurrentValue, NULL, FALSE);

		// College
		$this->College->SetDbValueDef($rsnew, $this->College->CurrentValue, NULL, FALSE);

		// Department
		$this->Department->SetDbValueDef($rsnew, $this->Department->CurrentValue, NULL, FALSE);

		// FacultyAffairsDate
		$this->FacultyAffairsDate->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->FacultyAffairsDate->CurrentValue, 7), NULL, FALSE);

		// FacultyAffairsRef
		$this->FacultyAffairsRef->SetDbValueDef($rsnew, $this->FacultyAffairsRef->CurrentValue, NULL, FALSE);

		// CollegeDecision
		$this->CollegeDecision->SetDbValueDef($rsnew, $this->CollegeDecision->CurrentValue, NULL, FALSE);

		// CollegeDecisionDate
		$this->CollegeDecisionDate->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->CollegeDecisionDate->CurrentValue, 7), NULL, FALSE);

		// CollegeDecisionRef
		$this->CollegeDecisionRef->SetDbValueDef($rsnew, $this->CollegeDecisionRef->CurrentValue, NULL, FALSE);

		// CommitteeDecision
		$this->CommitteeDecision->SetDbValueDef($rsnew, $this->CommitteeDecision->CurrentValue, NULL, FALSE);

		// CommitteeDecisionDate
		$this->CommitteeDecisionDate->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->CommitteeDecisionDate->CurrentValue, 7), NULL, FALSE);

		// CommitteeDecisionRef
		$this->CommitteeDecisionRef->SetDbValueDef($rsnew, $this->CommitteeDecisionRef->CurrentValue, NULL, FALSE);

		// PresidentDecision
		$this->PresidentDecision->SetDbValueDef($rsnew, $this->PresidentDecision->CurrentValue, NULL, FALSE);

		// PresidentDecisionDate
		$this->PresidentDecisionDate->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->PresidentDecisionDate->CurrentValue, 7), NULL, FALSE);

		// PresidentDecisionRef
		$this->PresidentDecisionRef->SetDbValueDef($rsnew, $this->PresidentDecisionRef->CurrentValue, NULL, FALSE);

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
		$Breadcrumb->Add("list", $this->TableVar, "facultyapplicationlist.php", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, ew_CurrentUrl());
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'facultyapplication';
	  $usr = CurrentUserName();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (add page)
	function WriteAuditTrailOnAdd(&$rs) {
		if (!$this->AuditTrailOnAdd) return;
		$table = 'facultyapplication';

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
if (!isset($facultyapplication_add)) $facultyapplication_add = new cfacultyapplication_add();

// Page init
$facultyapplication_add->Page_Init();

// Page main
$facultyapplication_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$facultyapplication_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var facultyapplication_add = new ew_Page("facultyapplication_add");
facultyapplication_add.PageID = "add"; // Page ID
var EW_PAGE_ID = facultyapplication_add.PageID; // For backward compatibility

// Form object
var ffacultyapplicationadd = new ew_Form("ffacultyapplicationadd");

// Validate form
ffacultyapplicationadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_FacultyAffairsDate");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($facultyapplication->FacultyAffairsDate->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_CollegeDecisionDate");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($facultyapplication->CollegeDecisionDate->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_CommitteeDecisionDate");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($facultyapplication->CommitteeDecisionDate->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_PresidentDecisionDate");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($facultyapplication->PresidentDecisionDate->FldErrMsg()) ?>");

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
ffacultyapplicationadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ffacultyapplicationadd.ValidateRequired = true;
<?php } else { ?>
ffacultyapplicationadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ffacultyapplicationadd.Lists["x_Department"] = {"LinkField":"x_DID","Ajax":null,"AutoFill":false,"DisplayFields":["x_DepartmentName","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $facultyapplication_add->ShowPageHeader(); ?>
<?php
$facultyapplication_add->ShowMessage();
?>
<form name="ffacultyapplicationadd" id="ffacultyapplicationadd" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="facultyapplication">
<input type="hidden" name="a_add" id="a_add" value="A">
<table class="ewGrid"><tr><td>
<table id="tbl_facultyapplicationadd" class="table table-bordered table-striped">
<?php if ($facultyapplication->Name->Visible) { // Name ?>
	<tr id="r_Name">
		<td><span id="elh_facultyapplication_Name"><?php echo $facultyapplication->Name->FldCaption() ?></span></td>
		<td<?php echo $facultyapplication->Name->CellAttributes() ?>>
<span id="el_facultyapplication_Name" class="control-group">
<input type="text" data-field="x_Name" name="x_Name" id="x_Name" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($facultyapplication->Name->PlaceHolder) ?>" value="<?php echo $facultyapplication->Name->EditValue ?>"<?php echo $facultyapplication->Name->EditAttributes() ?>>
</span>
<?php echo $facultyapplication->Name->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($facultyapplication->Nationality->Visible) { // Nationality ?>
	<tr id="r_Nationality">
		<td><span id="elh_facultyapplication_Nationality"><?php echo $facultyapplication->Nationality->FldCaption() ?></span></td>
		<td<?php echo $facultyapplication->Nationality->CellAttributes() ?>>
<span id="el_facultyapplication_Nationality" class="control-group">
<input type="text" data-field="x_Nationality" name="x_Nationality" id="x_Nationality" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($facultyapplication->Nationality->PlaceHolder) ?>" value="<?php echo $facultyapplication->Nationality->EditValue ?>"<?php echo $facultyapplication->Nationality->EditAttributes() ?>>
</span>
<?php echo $facultyapplication->Nationality->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($facultyapplication->College->Visible) { // College ?>
	<tr id="r_College">
		<td><span id="elh_facultyapplication_College"><?php echo $facultyapplication->College->FldCaption() ?></span></td>
		<td<?php echo $facultyapplication->College->CellAttributes() ?>>
<span id="el_facultyapplication_College" class="control-group">
<input type="text" data-field="x_College" name="x_College" id="x_College" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($facultyapplication->College->PlaceHolder) ?>" value="<?php echo $facultyapplication->College->EditValue ?>"<?php echo $facultyapplication->College->EditAttributes() ?>>
</span>
<?php echo $facultyapplication->College->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($facultyapplication->Department->Visible) { // Department ?>
	<tr id="r_Department">
		<td><span id="elh_facultyapplication_Department"><?php echo $facultyapplication->Department->FldCaption() ?></span></td>
		<td<?php echo $facultyapplication->Department->CellAttributes() ?>>
<span id="el_facultyapplication_Department" class="control-group">
<select data-field="x_Department" id="x_Department" name="x_Department"<?php echo $facultyapplication->Department->EditAttributes() ?>>
<?php
if (is_array($facultyapplication->Department->EditValue)) {
	$arwrk = $facultyapplication->Department->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($facultyapplication->Department->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php echo $facultyapplication->Department->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($facultyapplication->FacultyAffairsDate->Visible) { // FacultyAffairsDate ?>
	<tr id="r_FacultyAffairsDate">
		<td><span id="elh_facultyapplication_FacultyAffairsDate"><?php echo $facultyapplication->FacultyAffairsDate->FldCaption() ?></span></td>
		<td<?php echo $facultyapplication->FacultyAffairsDate->CellAttributes() ?>>
<span id="el_facultyapplication_FacultyAffairsDate" class="control-group">
<input type="text" data-field="x_FacultyAffairsDate" name="x_FacultyAffairsDate" id="x_FacultyAffairsDate" placeholder="<?php echo ew_HtmlEncode($facultyapplication->FacultyAffairsDate->PlaceHolder) ?>" value="<?php echo $facultyapplication->FacultyAffairsDate->EditValue ?>"<?php echo $facultyapplication->FacultyAffairsDate->EditAttributes() ?>>
<?php if (!$facultyapplication->FacultyAffairsDate->ReadOnly && !$facultyapplication->FacultyAffairsDate->Disabled && @$facultyapplication->FacultyAffairsDate->EditAttrs["readonly"] == "" && @$facultyapplication->FacultyAffairsDate->EditAttrs["disabled"] == "") { ?>
<button id="cal_x_FacultyAffairsDate" name="cal_x_FacultyAffairsDate" class="btn" type="button"><img src="phpimages/calendar.png" alt="<?php echo $Language->Phrase("PickDate") ?>" title="<?php echo $Language->Phrase("PickDate") ?>" style="border: 0;"></button><script type="text/javascript">
ew_CreateCalendar("ffacultyapplicationadd", "x_FacultyAffairsDate", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $facultyapplication->FacultyAffairsDate->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($facultyapplication->FacultyAffairsRef->Visible) { // FacultyAffairsRef ?>
	<tr id="r_FacultyAffairsRef">
		<td><span id="elh_facultyapplication_FacultyAffairsRef"><?php echo $facultyapplication->FacultyAffairsRef->FldCaption() ?></span></td>
		<td<?php echo $facultyapplication->FacultyAffairsRef->CellAttributes() ?>>
<span id="el_facultyapplication_FacultyAffairsRef" class="control-group">
<input type="text" data-field="x_FacultyAffairsRef" name="x_FacultyAffairsRef" id="x_FacultyAffairsRef" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($facultyapplication->FacultyAffairsRef->PlaceHolder) ?>" value="<?php echo $facultyapplication->FacultyAffairsRef->EditValue ?>"<?php echo $facultyapplication->FacultyAffairsRef->EditAttributes() ?>>
</span>
<?php echo $facultyapplication->FacultyAffairsRef->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($facultyapplication->CollegeDecision->Visible) { // CollegeDecision ?>
	<tr id="r_CollegeDecision">
		<td><span id="elh_facultyapplication_CollegeDecision"><?php echo $facultyapplication->CollegeDecision->FldCaption() ?></span></td>
		<td<?php echo $facultyapplication->CollegeDecision->CellAttributes() ?>>
<span id="el_facultyapplication_CollegeDecision" class="control-group">
<div id="tp_x_CollegeDecision" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_CollegeDecision" id="x_CollegeDecision" value="{value}"<?php echo $facultyapplication->CollegeDecision->EditAttributes() ?>></div>
<div id="dsl_x_CollegeDecision" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $facultyapplication->CollegeDecision->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($facultyapplication->CollegeDecision->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_CollegeDecision" name="x_CollegeDecision" id="x_CollegeDecision_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $facultyapplication->CollegeDecision->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $facultyapplication->CollegeDecision->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($facultyapplication->CollegeDecisionDate->Visible) { // CollegeDecisionDate ?>
	<tr id="r_CollegeDecisionDate">
		<td><span id="elh_facultyapplication_CollegeDecisionDate"><?php echo $facultyapplication->CollegeDecisionDate->FldCaption() ?></span></td>
		<td<?php echo $facultyapplication->CollegeDecisionDate->CellAttributes() ?>>
<span id="el_facultyapplication_CollegeDecisionDate" class="control-group">
<input type="text" data-field="x_CollegeDecisionDate" name="x_CollegeDecisionDate" id="x_CollegeDecisionDate" placeholder="<?php echo ew_HtmlEncode($facultyapplication->CollegeDecisionDate->PlaceHolder) ?>" value="<?php echo $facultyapplication->CollegeDecisionDate->EditValue ?>"<?php echo $facultyapplication->CollegeDecisionDate->EditAttributes() ?>>
<?php if (!$facultyapplication->CollegeDecisionDate->ReadOnly && !$facultyapplication->CollegeDecisionDate->Disabled && @$facultyapplication->CollegeDecisionDate->EditAttrs["readonly"] == "" && @$facultyapplication->CollegeDecisionDate->EditAttrs["disabled"] == "") { ?>
<button id="cal_x_CollegeDecisionDate" name="cal_x_CollegeDecisionDate" class="btn" type="button"><img src="phpimages/calendar.png" alt="<?php echo $Language->Phrase("PickDate") ?>" title="<?php echo $Language->Phrase("PickDate") ?>" style="border: 0;"></button><script type="text/javascript">
ew_CreateCalendar("ffacultyapplicationadd", "x_CollegeDecisionDate", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $facultyapplication->CollegeDecisionDate->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($facultyapplication->CollegeDecisionRef->Visible) { // CollegeDecisionRef ?>
	<tr id="r_CollegeDecisionRef">
		<td><span id="elh_facultyapplication_CollegeDecisionRef"><?php echo $facultyapplication->CollegeDecisionRef->FldCaption() ?></span></td>
		<td<?php echo $facultyapplication->CollegeDecisionRef->CellAttributes() ?>>
<span id="el_facultyapplication_CollegeDecisionRef" class="control-group">
<input type="text" data-field="x_CollegeDecisionRef" name="x_CollegeDecisionRef" id="x_CollegeDecisionRef" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($facultyapplication->CollegeDecisionRef->PlaceHolder) ?>" value="<?php echo $facultyapplication->CollegeDecisionRef->EditValue ?>"<?php echo $facultyapplication->CollegeDecisionRef->EditAttributes() ?>>
</span>
<?php echo $facultyapplication->CollegeDecisionRef->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($facultyapplication->CommitteeDecision->Visible) { // CommitteeDecision ?>
	<tr id="r_CommitteeDecision">
		<td><span id="elh_facultyapplication_CommitteeDecision"><?php echo $facultyapplication->CommitteeDecision->FldCaption() ?></span></td>
		<td<?php echo $facultyapplication->CommitteeDecision->CellAttributes() ?>>
<span id="el_facultyapplication_CommitteeDecision" class="control-group">
<div id="tp_x_CommitteeDecision" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_CommitteeDecision" id="x_CommitteeDecision" value="{value}"<?php echo $facultyapplication->CommitteeDecision->EditAttributes() ?>></div>
<div id="dsl_x_CommitteeDecision" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $facultyapplication->CommitteeDecision->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($facultyapplication->CommitteeDecision->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_CommitteeDecision" name="x_CommitteeDecision" id="x_CommitteeDecision_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $facultyapplication->CommitteeDecision->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $facultyapplication->CommitteeDecision->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($facultyapplication->CommitteeDecisionDate->Visible) { // CommitteeDecisionDate ?>
	<tr id="r_CommitteeDecisionDate">
		<td><span id="elh_facultyapplication_CommitteeDecisionDate"><?php echo $facultyapplication->CommitteeDecisionDate->FldCaption() ?></span></td>
		<td<?php echo $facultyapplication->CommitteeDecisionDate->CellAttributes() ?>>
<span id="el_facultyapplication_CommitteeDecisionDate" class="control-group">
<input type="text" data-field="x_CommitteeDecisionDate" name="x_CommitteeDecisionDate" id="x_CommitteeDecisionDate" placeholder="<?php echo ew_HtmlEncode($facultyapplication->CommitteeDecisionDate->PlaceHolder) ?>" value="<?php echo $facultyapplication->CommitteeDecisionDate->EditValue ?>"<?php echo $facultyapplication->CommitteeDecisionDate->EditAttributes() ?>>
<?php if (!$facultyapplication->CommitteeDecisionDate->ReadOnly && !$facultyapplication->CommitteeDecisionDate->Disabled && @$facultyapplication->CommitteeDecisionDate->EditAttrs["readonly"] == "" && @$facultyapplication->CommitteeDecisionDate->EditAttrs["disabled"] == "") { ?>
<button id="cal_x_CommitteeDecisionDate" name="cal_x_CommitteeDecisionDate" class="btn" type="button"><img src="phpimages/calendar.png" alt="<?php echo $Language->Phrase("PickDate") ?>" title="<?php echo $Language->Phrase("PickDate") ?>" style="border: 0;"></button><script type="text/javascript">
ew_CreateCalendar("ffacultyapplicationadd", "x_CommitteeDecisionDate", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $facultyapplication->CommitteeDecisionDate->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($facultyapplication->CommitteeDecisionRef->Visible) { // CommitteeDecisionRef ?>
	<tr id="r_CommitteeDecisionRef">
		<td><span id="elh_facultyapplication_CommitteeDecisionRef"><?php echo $facultyapplication->CommitteeDecisionRef->FldCaption() ?></span></td>
		<td<?php echo $facultyapplication->CommitteeDecisionRef->CellAttributes() ?>>
<span id="el_facultyapplication_CommitteeDecisionRef" class="control-group">
<input type="text" data-field="x_CommitteeDecisionRef" name="x_CommitteeDecisionRef" id="x_CommitteeDecisionRef" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($facultyapplication->CommitteeDecisionRef->PlaceHolder) ?>" value="<?php echo $facultyapplication->CommitteeDecisionRef->EditValue ?>"<?php echo $facultyapplication->CommitteeDecisionRef->EditAttributes() ?>>
</span>
<?php echo $facultyapplication->CommitteeDecisionRef->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($facultyapplication->PresidentDecision->Visible) { // PresidentDecision ?>
	<tr id="r_PresidentDecision">
		<td><span id="elh_facultyapplication_PresidentDecision"><?php echo $facultyapplication->PresidentDecision->FldCaption() ?></span></td>
		<td<?php echo $facultyapplication->PresidentDecision->CellAttributes() ?>>
<span id="el_facultyapplication_PresidentDecision" class="control-group">
<div id="tp_x_PresidentDecision" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_PresidentDecision" id="x_PresidentDecision" value="{value}"<?php echo $facultyapplication->PresidentDecision->EditAttributes() ?>></div>
<div id="dsl_x_PresidentDecision" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $facultyapplication->PresidentDecision->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($facultyapplication->PresidentDecision->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_PresidentDecision" name="x_PresidentDecision" id="x_PresidentDecision_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $facultyapplication->PresidentDecision->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $facultyapplication->PresidentDecision->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($facultyapplication->PresidentDecisionDate->Visible) { // PresidentDecisionDate ?>
	<tr id="r_PresidentDecisionDate">
		<td><span id="elh_facultyapplication_PresidentDecisionDate"><?php echo $facultyapplication->PresidentDecisionDate->FldCaption() ?></span></td>
		<td<?php echo $facultyapplication->PresidentDecisionDate->CellAttributes() ?>>
<span id="el_facultyapplication_PresidentDecisionDate" class="control-group">
<input type="text" data-field="x_PresidentDecisionDate" name="x_PresidentDecisionDate" id="x_PresidentDecisionDate" placeholder="<?php echo ew_HtmlEncode($facultyapplication->PresidentDecisionDate->PlaceHolder) ?>" value="<?php echo $facultyapplication->PresidentDecisionDate->EditValue ?>"<?php echo $facultyapplication->PresidentDecisionDate->EditAttributes() ?>>
<?php if (!$facultyapplication->PresidentDecisionDate->ReadOnly && !$facultyapplication->PresidentDecisionDate->Disabled && @$facultyapplication->PresidentDecisionDate->EditAttrs["readonly"] == "" && @$facultyapplication->PresidentDecisionDate->EditAttrs["disabled"] == "") { ?>
<button id="cal_x_PresidentDecisionDate" name="cal_x_PresidentDecisionDate" class="btn" type="button"><img src="phpimages/calendar.png" alt="<?php echo $Language->Phrase("PickDate") ?>" title="<?php echo $Language->Phrase("PickDate") ?>" style="border: 0;"></button><script type="text/javascript">
ew_CreateCalendar("ffacultyapplicationadd", "x_PresidentDecisionDate", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $facultyapplication->PresidentDecisionDate->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($facultyapplication->PresidentDecisionRef->Visible) { // PresidentDecisionRef ?>
	<tr id="r_PresidentDecisionRef">
		<td><span id="elh_facultyapplication_PresidentDecisionRef"><?php echo $facultyapplication->PresidentDecisionRef->FldCaption() ?></span></td>
		<td<?php echo $facultyapplication->PresidentDecisionRef->CellAttributes() ?>>
<span id="el_facultyapplication_PresidentDecisionRef" class="control-group">
<input type="text" data-field="x_PresidentDecisionRef" name="x_PresidentDecisionRef" id="x_PresidentDecisionRef" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($facultyapplication->PresidentDecisionRef->PlaceHolder) ?>" value="<?php echo $facultyapplication->PresidentDecisionRef->EditValue ?>"<?php echo $facultyapplication->PresidentDecisionRef->EditAttributes() ?>>
</span>
<?php echo $facultyapplication->PresidentDecisionRef->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
</form>
<script type="text/javascript">
ffacultyapplicationadd.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$facultyapplication_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$facultyapplication_add->Page_Terminate();
?>
