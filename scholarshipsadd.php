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

$scholarships_add = NULL; // Initialize page object first

class cscholarships_add extends cscholarships {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{F8E6AD82-57C4-4BEE-8975-8D386184467A}";

	// Table name
	var $TableName = 'scholarships';

	// Page object name
	var $PageObjName = 'scholarships_add';

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

		// Table object (scholarships)
		if (!isset($GLOBALS["scholarships"]) || get_class($GLOBALS["scholarships"]) == "cscholarships") {
			$GLOBALS["scholarships"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["scholarships"];
		}

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'scholarships', TRUE);

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
			$this->Page_Terminate("scholarshipslist.php");
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
					$this->Page_Terminate("scholarshipslist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "scholarshipsview.php")
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
		$this->English_Name->CurrentValue = NULL;
		$this->English_Name->OldValue = $this->English_Name->CurrentValue;
		$this->Arabic_Name->CurrentValue = NULL;
		$this->Arabic_Name->OldValue = $this->Arabic_Name->CurrentValue;
		$this->College->CurrentValue = NULL;
		$this->College->OldValue = $this->College->CurrentValue;
		$this->Department->CurrentValue = NULL;
		$this->Department->OldValue = $this->Department->CurrentValue;
		$this->Major->CurrentValue = NULL;
		$this->Major->OldValue = $this->Major->CurrentValue;
		$this->GPA->CurrentValue = NULL;
		$this->GPA->OldValue = $this->GPA->CurrentValue;
		$this->Graduated_From->CurrentValue = NULL;
		$this->Graduated_From->OldValue = $this->Graduated_From->CurrentValue;
		$this->Acceptance_Counrty->CurrentValue = NULL;
		$this->Acceptance_Counrty->OldValue = $this->Acceptance_Counrty->CurrentValue;
		$this->Acceptance_University->CurrentValue = NULL;
		$this->Acceptance_University->OldValue = $this->Acceptance_University->CurrentValue;
		$this->Program_Degree->CurrentValue = NULL;
		$this->Program_Degree->OldValue = $this->Program_Degree->CurrentValue;
		$this->Notes->CurrentValue = NULL;
		$this->Notes->OldValue = $this->Notes->CurrentValue;
		$this->Committee_Date->CurrentValue = NULL;
		$this->Committee_Date->OldValue = $this->Committee_Date->CurrentValue;
		$this->Status->CurrentValue = NULL;
		$this->Status->OldValue = $this->Status->CurrentValue;
		$this->Justification->CurrentValue = NULL;
		$this->Justification->OldValue = $this->Justification->CurrentValue;
		$this->LastModifiedUser->CurrentValue = NULL;
		$this->LastModifiedUser->OldValue = $this->LastModifiedUser->CurrentValue;
		$this->LastModifiedTime->CurrentValue = NULL;
		$this->LastModifiedTime->OldValue = $this->LastModifiedTime->CurrentValue;
		$this->LastModifiedIP->CurrentValue = NULL;
		$this->LastModifiedIP->OldValue = $this->LastModifiedIP->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->English_Name->FldIsDetailKey) {
			$this->English_Name->setFormValue($objForm->GetValue("x_English_Name"));
		}
		if (!$this->Arabic_Name->FldIsDetailKey) {
			$this->Arabic_Name->setFormValue($objForm->GetValue("x_Arabic_Name"));
		}
		if (!$this->College->FldIsDetailKey) {
			$this->College->setFormValue($objForm->GetValue("x_College"));
		}
		if (!$this->Department->FldIsDetailKey) {
			$this->Department->setFormValue($objForm->GetValue("x_Department"));
		}
		if (!$this->Major->FldIsDetailKey) {
			$this->Major->setFormValue($objForm->GetValue("x_Major"));
		}
		if (!$this->GPA->FldIsDetailKey) {
			$this->GPA->setFormValue($objForm->GetValue("x_GPA"));
		}
		if (!$this->Graduated_From->FldIsDetailKey) {
			$this->Graduated_From->setFormValue($objForm->GetValue("x_Graduated_From"));
		}
		if (!$this->Acceptance_Counrty->FldIsDetailKey) {
			$this->Acceptance_Counrty->setFormValue($objForm->GetValue("x_Acceptance_Counrty"));
		}
		if (!$this->Acceptance_University->FldIsDetailKey) {
			$this->Acceptance_University->setFormValue($objForm->GetValue("x_Acceptance_University"));
		}
		if (!$this->Program_Degree->FldIsDetailKey) {
			$this->Program_Degree->setFormValue($objForm->GetValue("x_Program_Degree"));
		}
		if (!$this->Notes->FldIsDetailKey) {
			$this->Notes->setFormValue($objForm->GetValue("x_Notes"));
		}
		if (!$this->Committee_Date->FldIsDetailKey) {
			$this->Committee_Date->setFormValue($objForm->GetValue("x_Committee_Date"));
			$this->Committee_Date->CurrentValue = ew_UnFormatDateTime($this->Committee_Date->CurrentValue, 7);
		}
		if (!$this->Status->FldIsDetailKey) {
			$this->Status->setFormValue($objForm->GetValue("x_Status"));
		}
		if (!$this->Justification->FldIsDetailKey) {
			$this->Justification->setFormValue($objForm->GetValue("x_Justification"));
		}
		if (!$this->LastModifiedUser->FldIsDetailKey) {
			$this->LastModifiedUser->setFormValue($objForm->GetValue("x_LastModifiedUser"));
		}
		if (!$this->LastModifiedTime->FldIsDetailKey) {
			$this->LastModifiedTime->setFormValue($objForm->GetValue("x_LastModifiedTime"));
		}
		if (!$this->LastModifiedIP->FldIsDetailKey) {
			$this->LastModifiedIP->setFormValue($objForm->GetValue("x_LastModifiedIP"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->English_Name->CurrentValue = $this->English_Name->FormValue;
		$this->Arabic_Name->CurrentValue = $this->Arabic_Name->FormValue;
		$this->College->CurrentValue = $this->College->FormValue;
		$this->Department->CurrentValue = $this->Department->FormValue;
		$this->Major->CurrentValue = $this->Major->FormValue;
		$this->GPA->CurrentValue = $this->GPA->FormValue;
		$this->Graduated_From->CurrentValue = $this->Graduated_From->FormValue;
		$this->Acceptance_Counrty->CurrentValue = $this->Acceptance_Counrty->FormValue;
		$this->Acceptance_University->CurrentValue = $this->Acceptance_University->FormValue;
		$this->Program_Degree->CurrentValue = $this->Program_Degree->FormValue;
		$this->Notes->CurrentValue = $this->Notes->FormValue;
		$this->Committee_Date->CurrentValue = $this->Committee_Date->FormValue;
		$this->Committee_Date->CurrentValue = ew_UnFormatDateTime($this->Committee_Date->CurrentValue, 7);
		$this->Status->CurrentValue = $this->Status->FormValue;
		$this->Justification->CurrentValue = $this->Justification->FormValue;
		$this->LastModifiedUser->CurrentValue = $this->LastModifiedUser->FormValue;
		$this->LastModifiedTime->CurrentValue = $this->LastModifiedTime->FormValue;
		$this->LastModifiedIP->CurrentValue = $this->LastModifiedIP->FormValue;
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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// English Name
			$this->English_Name->EditCustomAttributes = "";
			$this->English_Name->EditValue = ew_HtmlEncode($this->English_Name->CurrentValue);
			$this->English_Name->PlaceHolder = ew_RemoveHtml($this->English_Name->FldCaption());

			// Arabic Name
			$this->Arabic_Name->EditCustomAttributes = "";
			$this->Arabic_Name->EditValue = ew_HtmlEncode($this->Arabic_Name->CurrentValue);
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
			$this->Major->EditValue = ew_HtmlEncode($this->Major->CurrentValue);
			$this->Major->PlaceHolder = ew_RemoveHtml($this->Major->FldCaption());

			// GPA
			$this->GPA->EditCustomAttributes = "";
			$this->GPA->EditValue = ew_HtmlEncode($this->GPA->CurrentValue);
			$this->GPA->PlaceHolder = ew_RemoveHtml($this->GPA->FldCaption());
			if (strval($this->GPA->EditValue) <> "" && is_numeric($this->GPA->EditValue)) $this->GPA->EditValue = ew_FormatNumber($this->GPA->EditValue, -2, -1, -2, 0);

			// Graduated From
			$this->Graduated_From->EditCustomAttributes = "";
			$this->Graduated_From->EditValue = ew_HtmlEncode($this->Graduated_From->CurrentValue);
			$this->Graduated_From->PlaceHolder = ew_RemoveHtml($this->Graduated_From->FldCaption());

			// Acceptance Counrty
			$this->Acceptance_Counrty->EditCustomAttributes = "";
			$this->Acceptance_Counrty->EditValue = ew_HtmlEncode($this->Acceptance_Counrty->CurrentValue);
			$this->Acceptance_Counrty->PlaceHolder = ew_RemoveHtml($this->Acceptance_Counrty->FldCaption());

			// Acceptance University
			$this->Acceptance_University->EditCustomAttributes = "";
			$this->Acceptance_University->EditValue = $this->Acceptance_University->CurrentValue;
			$this->Acceptance_University->PlaceHolder = ew_RemoveHtml($this->Acceptance_University->FldCaption());

			// Program Degree
			$this->Program_Degree->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->Program_Degree->FldTagValue(1), $this->Program_Degree->FldTagCaption(1) <> "" ? $this->Program_Degree->FldTagCaption(1) : $this->Program_Degree->FldTagValue(1));
			$arwrk[] = array($this->Program_Degree->FldTagValue(2), $this->Program_Degree->FldTagCaption(2) <> "" ? $this->Program_Degree->FldTagCaption(2) : $this->Program_Degree->FldTagValue(2));
			$arwrk[] = array($this->Program_Degree->FldTagValue(3), $this->Program_Degree->FldTagCaption(3) <> "" ? $this->Program_Degree->FldTagCaption(3) : $this->Program_Degree->FldTagValue(3));
			$arwrk[] = array($this->Program_Degree->FldTagValue(4), $this->Program_Degree->FldTagCaption(4) <> "" ? $this->Program_Degree->FldTagCaption(4) : $this->Program_Degree->FldTagValue(4));
			$this->Program_Degree->EditValue = $arwrk;

			// Notes
			$this->Notes->EditCustomAttributes = "";
			$this->Notes->EditValue = $this->Notes->CurrentValue;
			$this->Notes->PlaceHolder = ew_RemoveHtml($this->Notes->FldCaption());

			// Committee Date
			$this->Committee_Date->EditCustomAttributes = "";
			$this->Committee_Date->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->Committee_Date->CurrentValue, 7));
			$this->Committee_Date->PlaceHolder = ew_RemoveHtml($this->Committee_Date->FldCaption());

			// Status
			$this->Status->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->Status->FldTagValue(1), $this->Status->FldTagCaption(1) <> "" ? $this->Status->FldTagCaption(1) : $this->Status->FldTagValue(1));
			$arwrk[] = array($this->Status->FldTagValue(2), $this->Status->FldTagCaption(2) <> "" ? $this->Status->FldTagCaption(2) : $this->Status->FldTagValue(2));
			$arwrk[] = array($this->Status->FldTagValue(3), $this->Status->FldTagCaption(3) <> "" ? $this->Status->FldTagCaption(3) : $this->Status->FldTagValue(3));
			$this->Status->EditValue = $arwrk;

			// Justification
			$this->Justification->EditCustomAttributes = "";
			$this->Justification->EditValue = $this->Justification->CurrentValue;
			$this->Justification->PlaceHolder = ew_RemoveHtml($this->Justification->FldCaption());

			// LastModifiedUser
			// LastModifiedTime
			// LastModifiedIP
			// Edit refer script
			// English Name

			$this->English_Name->HrefValue = "";

			// Arabic Name
			$this->Arabic_Name->HrefValue = "";

			// College
			$this->College->HrefValue = "";

			// Department
			$this->Department->HrefValue = "";

			// Major
			$this->Major->HrefValue = "";

			// GPA
			$this->GPA->HrefValue = "";

			// Graduated From
			$this->Graduated_From->HrefValue = "";

			// Acceptance Counrty
			$this->Acceptance_Counrty->HrefValue = "";

			// Acceptance University
			$this->Acceptance_University->HrefValue = "";

			// Program Degree
			$this->Program_Degree->HrefValue = "";

			// Notes
			$this->Notes->HrefValue = "";

			// Committee Date
			$this->Committee_Date->HrefValue = "";

			// Status
			$this->Status->HrefValue = "";

			// Justification
			$this->Justification->HrefValue = "";

			// LastModifiedUser
			$this->LastModifiedUser->HrefValue = "";

			// LastModifiedTime
			$this->LastModifiedTime->HrefValue = "";

			// LastModifiedIP
			$this->LastModifiedIP->HrefValue = "";
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
		if (!ew_CheckInteger($this->GPA->FormValue)) {
			ew_AddMessage($gsFormError, $this->GPA->FldErrMsg());
		}
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

		// English Name
		$this->English_Name->SetDbValueDef($rsnew, $this->English_Name->CurrentValue, NULL, FALSE);

		// Arabic Name
		$this->Arabic_Name->SetDbValueDef($rsnew, $this->Arabic_Name->CurrentValue, NULL, FALSE);

		// College
		$this->College->SetDbValueDef($rsnew, $this->College->CurrentValue, NULL, FALSE);

		// Department
		$this->Department->SetDbValueDef($rsnew, $this->Department->CurrentValue, NULL, FALSE);

		// Major
		$this->Major->SetDbValueDef($rsnew, $this->Major->CurrentValue, NULL, FALSE);

		// GPA
		$this->GPA->SetDbValueDef($rsnew, $this->GPA->CurrentValue, NULL, FALSE);

		// Graduated From
		$this->Graduated_From->SetDbValueDef($rsnew, $this->Graduated_From->CurrentValue, NULL, FALSE);

		// Acceptance Counrty
		$this->Acceptance_Counrty->SetDbValueDef($rsnew, $this->Acceptance_Counrty->CurrentValue, NULL, FALSE);

		// Acceptance University
		$this->Acceptance_University->SetDbValueDef($rsnew, $this->Acceptance_University->CurrentValue, NULL, FALSE);

		// Program Degree
		$this->Program_Degree->SetDbValueDef($rsnew, $this->Program_Degree->CurrentValue, NULL, FALSE);

		// Notes
		$this->Notes->SetDbValueDef($rsnew, $this->Notes->CurrentValue, NULL, FALSE);

		// Committee Date
		$this->Committee_Date->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->Committee_Date->CurrentValue, 7), NULL, FALSE);

		// Status
		$this->Status->SetDbValueDef($rsnew, $this->Status->CurrentValue, NULL, FALSE);

		// Justification
		$this->Justification->SetDbValueDef($rsnew, $this->Justification->CurrentValue, NULL, FALSE);

		// LastModifiedUser
		$this->LastModifiedUser->SetDbValueDef($rsnew, CurrentUserName(), NULL);
		$rsnew['LastModifiedUser'] = &$this->LastModifiedUser->DbValue;

		// LastModifiedTime
		$this->LastModifiedTime->SetDbValueDef($rsnew, ew_CurrentDateTime(), NULL);
		$rsnew['LastModifiedTime'] = &$this->LastModifiedTime->DbValue;

		// LastModifiedIP
		$this->LastModifiedIP->SetDbValueDef($rsnew, ew_CurrentUserIP(), NULL);
		$rsnew['LastModifiedIP'] = &$this->LastModifiedIP->DbValue;

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
		$Breadcrumb->Add("list", $this->TableVar, "scholarshipslist.php", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, ew_CurrentUrl());
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'scholarships';
	  $usr = CurrentUserName();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (add page)
	function WriteAuditTrailOnAdd(&$rs) {
		if (!$this->AuditTrailOnAdd) return;
		$table = 'scholarships';

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
if (!isset($scholarships_add)) $scholarships_add = new cscholarships_add();

// Page init
$scholarships_add->Page_Init();

// Page main
$scholarships_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$scholarships_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var scholarships_add = new ew_Page("scholarships_add");
scholarships_add.PageID = "add"; // Page ID
var EW_PAGE_ID = scholarships_add.PageID; // For backward compatibility

// Form object
var fscholarshipsadd = new ew_Form("fscholarshipsadd");

// Validate form
fscholarshipsadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_GPA");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($scholarships->GPA->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_Committee_Date");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($scholarships->Committee_Date->FldErrMsg()) ?>");

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
fscholarshipsadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fscholarshipsadd.ValidateRequired = true;
<?php } else { ?>
fscholarshipsadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fscholarshipsadd.Lists["x_College"] = {"LinkField":"x_CollegeID","Ajax":null,"AutoFill":false,"DisplayFields":["x_College_Name_AR","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fscholarshipsadd.Lists["x_Department"] = {"LinkField":"x_DID","Ajax":null,"AutoFill":false,"DisplayFields":["x_DepartmentName2DAR","","",""],"ParentFields":["x_College"],"FilterFields":["x_CID"],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $scholarships_add->ShowPageHeader(); ?>
<?php
$scholarships_add->ShowMessage();
?>
<form name="fscholarshipsadd" id="fscholarshipsadd" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="scholarships">
<input type="hidden" name="a_add" id="a_add" value="A">
<table class="ewGrid"><tr><td>
<table id="tbl_scholarshipsadd" class="table table-bordered table-striped">
<?php if ($scholarships->English_Name->Visible) { // English Name ?>
	<tr id="r_English_Name">
		<td><span id="elh_scholarships_English_Name"><?php echo $scholarships->English_Name->FldCaption() ?></span></td>
		<td<?php echo $scholarships->English_Name->CellAttributes() ?>>
<span id="el_scholarships_English_Name" class="control-group">
<input type="text" data-field="x_English_Name" name="x_English_Name" id="x_English_Name" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($scholarships->English_Name->PlaceHolder) ?>" value="<?php echo $scholarships->English_Name->EditValue ?>"<?php echo $scholarships->English_Name->EditAttributes() ?>>
</span>
<?php echo $scholarships->English_Name->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($scholarships->Arabic_Name->Visible) { // Arabic Name ?>
	<tr id="r_Arabic_Name">
		<td><span id="elh_scholarships_Arabic_Name"><?php echo $scholarships->Arabic_Name->FldCaption() ?></span></td>
		<td<?php echo $scholarships->Arabic_Name->CellAttributes() ?>>
<span id="el_scholarships_Arabic_Name" class="control-group">
<input type="text" data-field="x_Arabic_Name" name="x_Arabic_Name" id="x_Arabic_Name" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($scholarships->Arabic_Name->PlaceHolder) ?>" value="<?php echo $scholarships->Arabic_Name->EditValue ?>"<?php echo $scholarships->Arabic_Name->EditAttributes() ?>>
</span>
<?php echo $scholarships->Arabic_Name->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($scholarships->College->Visible) { // College ?>
	<tr id="r_College">
		<td><span id="elh_scholarships_College"><?php echo $scholarships->College->FldCaption() ?></span></td>
		<td<?php echo $scholarships->College->CellAttributes() ?>>
<span id="el_scholarships_College" class="control-group">
<?php $scholarships->College->EditAttrs["onchange"] = "ew_UpdateOpt.call(this, ['x_Department']); " . @$scholarships->College->EditAttrs["onchange"]; ?>
<select data-field="x_College" id="x_College" name="x_College"<?php echo $scholarships->College->EditAttributes() ?>>
<?php
if (is_array($scholarships->College->EditValue)) {
	$arwrk = $scholarships->College->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($scholarships->College->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fscholarshipsadd.Lists["x_College"].Options = <?php echo (is_array($scholarships->College->EditValue)) ? ew_ArrayToJson($scholarships->College->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $scholarships->College->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($scholarships->Department->Visible) { // Department ?>
	<tr id="r_Department">
		<td><span id="elh_scholarships_Department"><?php echo $scholarships->Department->FldCaption() ?></span></td>
		<td<?php echo $scholarships->Department->CellAttributes() ?>>
<span id="el_scholarships_Department" class="control-group">
<select data-field="x_Department" id="x_Department" name="x_Department"<?php echo $scholarships->Department->EditAttributes() ?>>
<?php
if (is_array($scholarships->Department->EditValue)) {
	$arwrk = $scholarships->Department->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($scholarships->Department->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fscholarshipsadd.Lists["x_Department"].Options = <?php echo (is_array($scholarships->Department->EditValue)) ? ew_ArrayToJson($scholarships->Department->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $scholarships->Department->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($scholarships->Major->Visible) { // Major ?>
	<tr id="r_Major">
		<td><span id="elh_scholarships_Major"><?php echo $scholarships->Major->FldCaption() ?></span></td>
		<td<?php echo $scholarships->Major->CellAttributes() ?>>
<span id="el_scholarships_Major" class="control-group">
<input type="text" data-field="x_Major" name="x_Major" id="x_Major" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($scholarships->Major->PlaceHolder) ?>" value="<?php echo $scholarships->Major->EditValue ?>"<?php echo $scholarships->Major->EditAttributes() ?>>
</span>
<?php echo $scholarships->Major->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($scholarships->GPA->Visible) { // GPA ?>
	<tr id="r_GPA">
		<td><span id="elh_scholarships_GPA"><?php echo $scholarships->GPA->FldCaption() ?></span></td>
		<td<?php echo $scholarships->GPA->CellAttributes() ?>>
<span id="el_scholarships_GPA" class="control-group">
<input type="text" data-field="x_GPA" name="x_GPA" id="x_GPA" size="30" placeholder="<?php echo ew_HtmlEncode($scholarships->GPA->PlaceHolder) ?>" value="<?php echo $scholarships->GPA->EditValue ?>"<?php echo $scholarships->GPA->EditAttributes() ?>>
</span>
<?php echo $scholarships->GPA->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($scholarships->Graduated_From->Visible) { // Graduated From ?>
	<tr id="r_Graduated_From">
		<td><span id="elh_scholarships_Graduated_From"><?php echo $scholarships->Graduated_From->FldCaption() ?></span></td>
		<td<?php echo $scholarships->Graduated_From->CellAttributes() ?>>
<span id="el_scholarships_Graduated_From" class="control-group">
<input type="text" data-field="x_Graduated_From" name="x_Graduated_From" id="x_Graduated_From" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($scholarships->Graduated_From->PlaceHolder) ?>" value="<?php echo $scholarships->Graduated_From->EditValue ?>"<?php echo $scholarships->Graduated_From->EditAttributes() ?>>
</span>
<?php echo $scholarships->Graduated_From->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($scholarships->Acceptance_Counrty->Visible) { // Acceptance Counrty ?>
	<tr id="r_Acceptance_Counrty">
		<td><span id="elh_scholarships_Acceptance_Counrty"><?php echo $scholarships->Acceptance_Counrty->FldCaption() ?></span></td>
		<td<?php echo $scholarships->Acceptance_Counrty->CellAttributes() ?>>
<span id="el_scholarships_Acceptance_Counrty" class="control-group">
<input type="text" data-field="x_Acceptance_Counrty" name="x_Acceptance_Counrty" id="x_Acceptance_Counrty" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($scholarships->Acceptance_Counrty->PlaceHolder) ?>" value="<?php echo $scholarships->Acceptance_Counrty->EditValue ?>"<?php echo $scholarships->Acceptance_Counrty->EditAttributes() ?>>
</span>
<?php echo $scholarships->Acceptance_Counrty->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($scholarships->Acceptance_University->Visible) { // Acceptance University ?>
	<tr id="r_Acceptance_University">
		<td><span id="elh_scholarships_Acceptance_University"><?php echo $scholarships->Acceptance_University->FldCaption() ?></span></td>
		<td<?php echo $scholarships->Acceptance_University->CellAttributes() ?>>
<span id="el_scholarships_Acceptance_University" class="control-group">
<textarea data-field="x_Acceptance_University" class="editor" name="x_Acceptance_University" id="x_Acceptance_University" cols="70" rows="5" placeholder="<?php echo ew_HtmlEncode($scholarships->Acceptance_University->PlaceHolder) ?>"<?php echo $scholarships->Acceptance_University->EditAttributes() ?>><?php echo $scholarships->Acceptance_University->EditValue ?></textarea>
</span>
<?php echo $scholarships->Acceptance_University->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($scholarships->Program_Degree->Visible) { // Program Degree ?>
	<tr id="r_Program_Degree">
		<td><span id="elh_scholarships_Program_Degree"><?php echo $scholarships->Program_Degree->FldCaption() ?></span></td>
		<td<?php echo $scholarships->Program_Degree->CellAttributes() ?>>
<span id="el_scholarships_Program_Degree" class="control-group">
<div id="tp_x_Program_Degree" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_Program_Degree" id="x_Program_Degree" value="{value}"<?php echo $scholarships->Program_Degree->EditAttributes() ?>></div>
<div id="dsl_x_Program_Degree" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $scholarships->Program_Degree->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($scholarships->Program_Degree->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
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
<?php echo $scholarships->Program_Degree->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($scholarships->Notes->Visible) { // Notes ?>
	<tr id="r_Notes">
		<td><span id="elh_scholarships_Notes"><?php echo $scholarships->Notes->FldCaption() ?></span></td>
		<td<?php echo $scholarships->Notes->CellAttributes() ?>>
<span id="el_scholarships_Notes" class="control-group">
<textarea data-field="x_Notes" class="editor" name="x_Notes" id="x_Notes" cols="70" rows="6" placeholder="<?php echo ew_HtmlEncode($scholarships->Notes->PlaceHolder) ?>"<?php echo $scholarships->Notes->EditAttributes() ?>><?php echo $scholarships->Notes->EditValue ?></textarea>
</span>
<?php echo $scholarships->Notes->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($scholarships->Committee_Date->Visible) { // Committee Date ?>
	<tr id="r_Committee_Date">
		<td><span id="elh_scholarships_Committee_Date"><?php echo $scholarships->Committee_Date->FldCaption() ?></span></td>
		<td<?php echo $scholarships->Committee_Date->CellAttributes() ?>>
<span id="el_scholarships_Committee_Date" class="control-group">
<input type="text" data-field="x_Committee_Date" name="x_Committee_Date" id="x_Committee_Date" placeholder="<?php echo ew_HtmlEncode($scholarships->Committee_Date->PlaceHolder) ?>" value="<?php echo $scholarships->Committee_Date->EditValue ?>"<?php echo $scholarships->Committee_Date->EditAttributes() ?>>
<?php if (!$scholarships->Committee_Date->ReadOnly && !$scholarships->Committee_Date->Disabled && @$scholarships->Committee_Date->EditAttrs["readonly"] == "" && @$scholarships->Committee_Date->EditAttrs["disabled"] == "") { ?>
<button id="cal_x_Committee_Date" name="cal_x_Committee_Date" class="btn" type="button"><img src="phpimages/calendar.png" alt="<?php echo $Language->Phrase("PickDate") ?>" title="<?php echo $Language->Phrase("PickDate") ?>" style="border: 0;"></button><script type="text/javascript">
ew_CreateCalendar("fscholarshipsadd", "x_Committee_Date", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $scholarships->Committee_Date->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($scholarships->Status->Visible) { // Status ?>
	<tr id="r_Status">
		<td><span id="elh_scholarships_Status"><?php echo $scholarships->Status->FldCaption() ?></span></td>
		<td<?php echo $scholarships->Status->CellAttributes() ?>>
<span id="el_scholarships_Status" class="control-group">
<div id="tp_x_Status" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_Status" id="x_Status" value="{value}"<?php echo $scholarships->Status->EditAttributes() ?>></div>
<div id="dsl_x_Status" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $scholarships->Status->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($scholarships->Status->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
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
<?php echo $scholarships->Status->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($scholarships->Justification->Visible) { // Justification ?>
	<tr id="r_Justification">
		<td><span id="elh_scholarships_Justification"><?php echo $scholarships->Justification->FldCaption() ?></span></td>
		<td<?php echo $scholarships->Justification->CellAttributes() ?>>
<span id="el_scholarships_Justification" class="control-group">
<textarea data-field="x_Justification" class="editor" name="x_Justification" id="x_Justification" cols="70" rows="6" placeholder="<?php echo ew_HtmlEncode($scholarships->Justification->PlaceHolder) ?>"<?php echo $scholarships->Justification->EditAttributes() ?>><?php echo $scholarships->Justification->EditValue ?></textarea>
</span>
<?php echo $scholarships->Justification->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
</form>
<script type="text/javascript">
fscholarshipsadd.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$scholarships_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$scholarships_add->Page_Terminate();
?>
