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

$academicmissions_add = NULL; // Initialize page object first

class cacademicmissions_add extends cacademicmissions {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{F8E6AD82-57C4-4BEE-8975-8D386184467A}";

	// Table name
	var $TableName = 'academicmissions';

	// Page object name
	var $PageObjName = 'academicmissions_add';

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

		// Table object (academicmissions)
		if (!isset($GLOBALS["academicmissions"]) || get_class($GLOBALS["academicmissions"]) == "cacademicmissions") {
			$GLOBALS["academicmissions"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["academicmissions"];
		}

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'academicmissions', TRUE);

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
			$this->Page_Terminate("academicmissionslist.php");
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
					$this->Page_Terminate("academicmissionslist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "academicmissionsview.php")
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
		$this->AttendanceOnly->CurrentValue = NULL;
		$this->AttendanceOnly->OldValue = $this->AttendanceOnly->CurrentValue;
		$this->PresentAPaper->CurrentValue = NULL;
		$this->PresentAPaper->OldValue = $this->PresentAPaper->CurrentValue;
		$this->Others->CurrentValue = NULL;
		$this->Others->OldValue = $this->Others->CurrentValue;
		$this->Participation->CurrentValue = NULL;
		$this->Participation->OldValue = $this->Participation->CurrentValue;
		$this->Summary->CurrentValue = NULL;
		$this->Summary->OldValue = $this->Summary->CurrentValue;
		$this->SuggestionRecommendation->CurrentValue = NULL;
		$this->SuggestionRecommendation->OldValue = $this->SuggestionRecommendation->CurrentValue;
		$this->FacultyMemberSign->CurrentValue = NULL;
		$this->FacultyMemberSign->OldValue = $this->FacultyMemberSign->CurrentValue;
		$this->DepChairmanSign->CurrentValue = NULL;
		$this->DepChairmanSign->OldValue = $this->DepChairmanSign->CurrentValue;
		$this->DeanSign->CurrentValue = NULL;
		$this->DeanSign->OldValue = $this->DeanSign->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
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
		if (!$this->AttendanceOnly->FldIsDetailKey) {
			$this->AttendanceOnly->setFormValue($objForm->GetValue("x_AttendanceOnly"));
		}
		if (!$this->PresentAPaper->FldIsDetailKey) {
			$this->PresentAPaper->setFormValue($objForm->GetValue("x_PresentAPaper"));
		}
		if (!$this->Others->FldIsDetailKey) {
			$this->Others->setFormValue($objForm->GetValue("x_Others"));
		}
		if (!$this->Participation->FldIsDetailKey) {
			$this->Participation->setFormValue($objForm->GetValue("x_Participation"));
		}
		if (!$this->Summary->FldIsDetailKey) {
			$this->Summary->setFormValue($objForm->GetValue("x_Summary"));
		}
		if (!$this->SuggestionRecommendation->FldIsDetailKey) {
			$this->SuggestionRecommendation->setFormValue($objForm->GetValue("x_SuggestionRecommendation"));
		}
		if (!$this->FacultyMemberSign->FldIsDetailKey) {
			$this->FacultyMemberSign->setFormValue($objForm->GetValue("x_FacultyMemberSign"));
		}
		if (!$this->DepChairmanSign->FldIsDetailKey) {
			$this->DepChairmanSign->setFormValue($objForm->GetValue("x_DepChairmanSign"));
		}
		if (!$this->DeanSign->FldIsDetailKey) {
			$this->DeanSign->setFormValue($objForm->GetValue("x_DeanSign"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
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
		$this->AttendanceOnly->CurrentValue = $this->AttendanceOnly->FormValue;
		$this->PresentAPaper->CurrentValue = $this->PresentAPaper->FormValue;
		$this->Others->CurrentValue = $this->Others->FormValue;
		$this->Participation->CurrentValue = $this->Participation->FormValue;
		$this->Summary->CurrentValue = $this->Summary->FormValue;
		$this->SuggestionRecommendation->CurrentValue = $this->SuggestionRecommendation->FormValue;
		$this->FacultyMemberSign->CurrentValue = $this->FacultyMemberSign->FormValue;
		$this->DepChairmanSign->CurrentValue = $this->DepChairmanSign->FormValue;
		$this->DeanSign->CurrentValue = $this->DeanSign->FormValue;
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

			// AttendanceOnly
			$this->AttendanceOnly->LinkCustomAttributes = "";
			$this->AttendanceOnly->HrefValue = "";
			$this->AttendanceOnly->TooltipValue = "";

			// PresentAPaper
			$this->PresentAPaper->LinkCustomAttributes = "";
			$this->PresentAPaper->HrefValue = "";
			$this->PresentAPaper->TooltipValue = "";

			// Others
			$this->Others->LinkCustomAttributes = "";
			$this->Others->HrefValue = "";
			$this->Others->TooltipValue = "";

			// Participation
			$this->Participation->LinkCustomAttributes = "";
			$this->Participation->HrefValue = "";
			$this->Participation->TooltipValue = "";

			// Summary
			$this->Summary->LinkCustomAttributes = "";
			$this->Summary->HrefValue = "";
			$this->Summary->TooltipValue = "";

			// SuggestionRecommendation
			$this->SuggestionRecommendation->LinkCustomAttributes = "";
			$this->SuggestionRecommendation->HrefValue = "";
			$this->SuggestionRecommendation->TooltipValue = "";

			// FacultyMemberSign
			$this->FacultyMemberSign->LinkCustomAttributes = "";
			$this->FacultyMemberSign->HrefValue = "";
			$this->FacultyMemberSign->TooltipValue = "";

			// DepChairmanSign
			$this->DepChairmanSign->LinkCustomAttributes = "";
			$this->DepChairmanSign->HrefValue = "";
			$this->DepChairmanSign->TooltipValue = "";

			// DeanSign
			$this->DeanSign->LinkCustomAttributes = "";
			$this->DeanSign->HrefValue = "";
			$this->DeanSign->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

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

			// AttendanceOnly
			$this->AttendanceOnly->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->AttendanceOnly->FldTagValue(1), $this->AttendanceOnly->FldTagCaption(1) <> "" ? $this->AttendanceOnly->FldTagCaption(1) : $this->AttendanceOnly->FldTagValue(1));
			$arwrk[] = array($this->AttendanceOnly->FldTagValue(2), $this->AttendanceOnly->FldTagCaption(2) <> "" ? $this->AttendanceOnly->FldTagCaption(2) : $this->AttendanceOnly->FldTagValue(2));
			$this->AttendanceOnly->EditValue = $arwrk;

			// PresentAPaper
			$this->PresentAPaper->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->PresentAPaper->FldTagValue(1), $this->PresentAPaper->FldTagCaption(1) <> "" ? $this->PresentAPaper->FldTagCaption(1) : $this->PresentAPaper->FldTagValue(1));
			$arwrk[] = array($this->PresentAPaper->FldTagValue(2), $this->PresentAPaper->FldTagCaption(2) <> "" ? $this->PresentAPaper->FldTagCaption(2) : $this->PresentAPaper->FldTagValue(2));
			$this->PresentAPaper->EditValue = $arwrk;

			// Others
			$this->Others->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->Others->FldTagValue(1), $this->Others->FldTagCaption(1) <> "" ? $this->Others->FldTagCaption(1) : $this->Others->FldTagValue(1));
			$arwrk[] = array($this->Others->FldTagValue(2), $this->Others->FldTagCaption(2) <> "" ? $this->Others->FldTagCaption(2) : $this->Others->FldTagValue(2));
			$this->Others->EditValue = $arwrk;

			// Participation
			$this->Participation->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->Participation->FldTagValue(1), $this->Participation->FldTagCaption(1) <> "" ? $this->Participation->FldTagCaption(1) : $this->Participation->FldTagValue(1));
			$arwrk[] = array($this->Participation->FldTagValue(2), $this->Participation->FldTagCaption(2) <> "" ? $this->Participation->FldTagCaption(2) : $this->Participation->FldTagValue(2));
			$this->Participation->EditValue = $arwrk;

			// Summary
			$this->Summary->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->Summary->FldTagValue(1), $this->Summary->FldTagCaption(1) <> "" ? $this->Summary->FldTagCaption(1) : $this->Summary->FldTagValue(1));
			$arwrk[] = array($this->Summary->FldTagValue(2), $this->Summary->FldTagCaption(2) <> "" ? $this->Summary->FldTagCaption(2) : $this->Summary->FldTagValue(2));
			$this->Summary->EditValue = $arwrk;

			// SuggestionRecommendation
			$this->SuggestionRecommendation->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->SuggestionRecommendation->FldTagValue(1), $this->SuggestionRecommendation->FldTagCaption(1) <> "" ? $this->SuggestionRecommendation->FldTagCaption(1) : $this->SuggestionRecommendation->FldTagValue(1));
			$arwrk[] = array($this->SuggestionRecommendation->FldTagValue(2), $this->SuggestionRecommendation->FldTagCaption(2) <> "" ? $this->SuggestionRecommendation->FldTagCaption(2) : $this->SuggestionRecommendation->FldTagValue(2));
			$this->SuggestionRecommendation->EditValue = $arwrk;

			// FacultyMemberSign
			$this->FacultyMemberSign->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->FacultyMemberSign->FldTagValue(1), $this->FacultyMemberSign->FldTagCaption(1) <> "" ? $this->FacultyMemberSign->FldTagCaption(1) : $this->FacultyMemberSign->FldTagValue(1));
			$arwrk[] = array($this->FacultyMemberSign->FldTagValue(2), $this->FacultyMemberSign->FldTagCaption(2) <> "" ? $this->FacultyMemberSign->FldTagCaption(2) : $this->FacultyMemberSign->FldTagValue(2));
			$this->FacultyMemberSign->EditValue = $arwrk;

			// DepChairmanSign
			$this->DepChairmanSign->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->DepChairmanSign->FldTagValue(1), $this->DepChairmanSign->FldTagCaption(1) <> "" ? $this->DepChairmanSign->FldTagCaption(1) : $this->DepChairmanSign->FldTagValue(1));
			$arwrk[] = array($this->DepChairmanSign->FldTagValue(2), $this->DepChairmanSign->FldTagCaption(2) <> "" ? $this->DepChairmanSign->FldTagCaption(2) : $this->DepChairmanSign->FldTagValue(2));
			$this->DepChairmanSign->EditValue = $arwrk;

			// DeanSign
			$this->DeanSign->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->DeanSign->FldTagValue(1), $this->DeanSign->FldTagCaption(1) <> "" ? $this->DeanSign->FldTagCaption(1) : $this->DeanSign->FldTagValue(1));
			$arwrk[] = array($this->DeanSign->FldTagValue(2), $this->DeanSign->FldTagCaption(2) <> "" ? $this->DeanSign->FldTagCaption(2) : $this->DeanSign->FldTagValue(2));
			$this->DeanSign->EditValue = $arwrk;

			// Edit refer script
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

			// AttendanceOnly
			$this->AttendanceOnly->HrefValue = "";

			// PresentAPaper
			$this->PresentAPaper->HrefValue = "";

			// Others
			$this->Others->HrefValue = "";

			// Participation
			$this->Participation->HrefValue = "";

			// Summary
			$this->Summary->HrefValue = "";

			// SuggestionRecommendation
			$this->SuggestionRecommendation->HrefValue = "";

			// FacultyMemberSign
			$this->FacultyMemberSign->HrefValue = "";

			// DepChairmanSign
			$this->DepChairmanSign->HrefValue = "";

			// DeanSign
			$this->DeanSign->HrefValue = "";
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

		// AttendanceOnly
		$this->AttendanceOnly->SetDbValueDef($rsnew, $this->AttendanceOnly->CurrentValue, NULL, FALSE);

		// PresentAPaper
		$this->PresentAPaper->SetDbValueDef($rsnew, $this->PresentAPaper->CurrentValue, NULL, FALSE);

		// Others
		$this->Others->SetDbValueDef($rsnew, $this->Others->CurrentValue, NULL, FALSE);

		// Participation
		$this->Participation->SetDbValueDef($rsnew, $this->Participation->CurrentValue, NULL, FALSE);

		// Summary
		$this->Summary->SetDbValueDef($rsnew, $this->Summary->CurrentValue, NULL, FALSE);

		// SuggestionRecommendation
		$this->SuggestionRecommendation->SetDbValueDef($rsnew, $this->SuggestionRecommendation->CurrentValue, NULL, FALSE);

		// FacultyMemberSign
		$this->FacultyMemberSign->SetDbValueDef($rsnew, $this->FacultyMemberSign->CurrentValue, NULL, FALSE);

		// DepChairmanSign
		$this->DepChairmanSign->SetDbValueDef($rsnew, $this->DepChairmanSign->CurrentValue, NULL, FALSE);

		// DeanSign
		$this->DeanSign->SetDbValueDef($rsnew, $this->DeanSign->CurrentValue, NULL, FALSE);

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
		$Breadcrumb->Add("list", $this->TableVar, "academicmissionslist.php", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, ew_CurrentUrl());
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'academicmissions';
	  $usr = CurrentUserName();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (add page)
	function WriteAuditTrailOnAdd(&$rs) {
		if (!$this->AuditTrailOnAdd) return;
		$table = 'academicmissions';

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
if (!isset($academicmissions_add)) $academicmissions_add = new cacademicmissions_add();

// Page init
$academicmissions_add->Page_Init();

// Page main
$academicmissions_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$academicmissions_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var academicmissions_add = new ew_Page("academicmissions_add");
academicmissions_add.PageID = "add"; // Page ID
var EW_PAGE_ID = academicmissions_add.PageID; // For backward compatibility

// Form object
var facademicmissionsadd = new ew_Form("facademicmissionsadd");

// Validate form
facademicmissionsadd.Validate = function() {
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
facademicmissionsadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
facademicmissionsadd.ValidateRequired = true;
<?php } else { ?>
facademicmissionsadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
facademicmissionsadd.Lists["x_College"] = {"LinkField":"x_CollegeID","Ajax":null,"AutoFill":false,"DisplayFields":["x_College_Name_EN","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
facademicmissionsadd.Lists["x_Department"] = {"LinkField":"x_DID","Ajax":null,"AutoFill":false,"DisplayFields":["x_DepartmentName","","",""],"ParentFields":["x_College"],"FilterFields":["x_CID"],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $academicmissions_add->ShowPageHeader(); ?>
<?php
$academicmissions_add->ShowMessage();
?>
<form name="facademicmissionsadd" id="facademicmissionsadd" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="academicmissions">
<input type="hidden" name="a_add" id="a_add" value="A">
<table class="ewGrid"><tr><td>
<table id="tbl_academicmissionsadd" class="table table-bordered table-striped">
<?php if ($academicmissions->Name->Visible) { // Name ?>
	<tr id="r_Name">
		<td><span id="elh_academicmissions_Name"><?php echo $academicmissions->Name->FldCaption() ?></span></td>
		<td<?php echo $academicmissions->Name->CellAttributes() ?>>
<span id="el_academicmissions_Name" class="control-group">
<input type="text" data-field="x_Name" name="x_Name" id="x_Name" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($academicmissions->Name->PlaceHolder) ?>" value="<?php echo $academicmissions->Name->EditValue ?>"<?php echo $academicmissions->Name->EditAttributes() ?>>
</span>
<?php echo $academicmissions->Name->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($academicmissions->UniversityID->Visible) { // UniversityID ?>
	<tr id="r_UniversityID">
		<td><span id="elh_academicmissions_UniversityID"><?php echo $academicmissions->UniversityID->FldCaption() ?></span></td>
		<td<?php echo $academicmissions->UniversityID->CellAttributes() ?>>
<span id="el_academicmissions_UniversityID" class="control-group">
<input type="text" data-field="x_UniversityID" name="x_UniversityID" id="x_UniversityID" size="30" placeholder="<?php echo ew_HtmlEncode($academicmissions->UniversityID->PlaceHolder) ?>" value="<?php echo $academicmissions->UniversityID->EditValue ?>"<?php echo $academicmissions->UniversityID->EditAttributes() ?>>
</span>
<?php echo $academicmissions->UniversityID->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($academicmissions->College->Visible) { // College ?>
	<tr id="r_College">
		<td><span id="elh_academicmissions_College"><?php echo $academicmissions->College->FldCaption() ?></span></td>
		<td<?php echo $academicmissions->College->CellAttributes() ?>>
<span id="el_academicmissions_College" class="control-group">
<?php $academicmissions->College->EditAttrs["onchange"] = "ew_UpdateOpt.call(this, ['x_Department']); " . @$academicmissions->College->EditAttrs["onchange"]; ?>
<select data-field="x_College" id="x_College" name="x_College"<?php echo $academicmissions->College->EditAttributes() ?>>
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
facademicmissionsadd.Lists["x_College"].Options = <?php echo (is_array($academicmissions->College->EditValue)) ? ew_ArrayToJson($academicmissions->College->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $academicmissions->College->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($academicmissions->Department->Visible) { // Department ?>
	<tr id="r_Department">
		<td><span id="elh_academicmissions_Department"><?php echo $academicmissions->Department->FldCaption() ?></span></td>
		<td<?php echo $academicmissions->Department->CellAttributes() ?>>
<span id="el_academicmissions_Department" class="control-group">
<select data-field="x_Department" id="x_Department" name="x_Department"<?php echo $academicmissions->Department->EditAttributes() ?>>
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
facademicmissionsadd.Lists["x_Department"].Options = <?php echo (is_array($academicmissions->Department->EditValue)) ? ew_ArrayToJson($academicmissions->Department->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $academicmissions->Department->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($academicmissions->StartDate->Visible) { // StartDate ?>
	<tr id="r_StartDate">
		<td><span id="elh_academicmissions_StartDate"><?php echo $academicmissions->StartDate->FldCaption() ?></span></td>
		<td<?php echo $academicmissions->StartDate->CellAttributes() ?>>
<span id="el_academicmissions_StartDate" class="control-group">
<input type="text" data-field="x_StartDate" name="x_StartDate" id="x_StartDate" placeholder="<?php echo ew_HtmlEncode($academicmissions->StartDate->PlaceHolder) ?>" value="<?php echo $academicmissions->StartDate->EditValue ?>"<?php echo $academicmissions->StartDate->EditAttributes() ?>>
<?php if (!$academicmissions->StartDate->ReadOnly && !$academicmissions->StartDate->Disabled && @$academicmissions->StartDate->EditAttrs["readonly"] == "" && @$academicmissions->StartDate->EditAttrs["disabled"] == "") { ?>
<button id="cal_x_StartDate" name="cal_x_StartDate" class="btn" type="button"><img src="phpimages/calendar.png" alt="<?php echo $Language->Phrase("PickDate") ?>" title="<?php echo $Language->Phrase("PickDate") ?>" style="border: 0;"></button><script type="text/javascript">
ew_CreateCalendar("facademicmissionsadd", "x_StartDate", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $academicmissions->StartDate->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($academicmissions->EndDate->Visible) { // EndDate ?>
	<tr id="r_EndDate">
		<td><span id="elh_academicmissions_EndDate"><?php echo $academicmissions->EndDate->FldCaption() ?></span></td>
		<td<?php echo $academicmissions->EndDate->CellAttributes() ?>>
<span id="el_academicmissions_EndDate" class="control-group">
<input type="text" data-field="x_EndDate" name="x_EndDate" id="x_EndDate" placeholder="<?php echo ew_HtmlEncode($academicmissions->EndDate->PlaceHolder) ?>" value="<?php echo $academicmissions->EndDate->EditValue ?>"<?php echo $academicmissions->EndDate->EditAttributes() ?>>
<?php if (!$academicmissions->EndDate->ReadOnly && !$academicmissions->EndDate->Disabled && @$academicmissions->EndDate->EditAttrs["readonly"] == "" && @$academicmissions->EndDate->EditAttrs["disabled"] == "") { ?>
<button id="cal_x_EndDate" name="cal_x_EndDate" class="btn" type="button"><img src="phpimages/calendar.png" alt="<?php echo $Language->Phrase("PickDate") ?>" title="<?php echo $Language->Phrase("PickDate") ?>" style="border: 0;"></button><script type="text/javascript">
ew_CreateCalendar("facademicmissionsadd", "x_EndDate", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $academicmissions->EndDate->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($academicmissions->PlaceVisited->Visible) { // PlaceVisited ?>
	<tr id="r_PlaceVisited">
		<td><span id="elh_academicmissions_PlaceVisited"><?php echo $academicmissions->PlaceVisited->FldCaption() ?></span></td>
		<td<?php echo $academicmissions->PlaceVisited->CellAttributes() ?>>
<span id="el_academicmissions_PlaceVisited" class="control-group">
<input type="text" data-field="x_PlaceVisited" name="x_PlaceVisited" id="x_PlaceVisited" size="30" maxlength="200" placeholder="<?php echo ew_HtmlEncode($academicmissions->PlaceVisited->PlaceHolder) ?>" value="<?php echo $academicmissions->PlaceVisited->EditValue ?>"<?php echo $academicmissions->PlaceVisited->EditAttributes() ?>>
</span>
<?php echo $academicmissions->PlaceVisited->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($academicmissions->NatureOfVisit->Visible) { // NatureOfVisit ?>
	<tr id="r_NatureOfVisit">
		<td><span id="elh_academicmissions_NatureOfVisit"><?php echo $academicmissions->NatureOfVisit->FldCaption() ?></span></td>
		<td<?php echo $academicmissions->NatureOfVisit->CellAttributes() ?>>
<span id="el_academicmissions_NatureOfVisit" class="control-group">
<input type="text" data-field="x_NatureOfVisit" name="x_NatureOfVisit" id="x_NatureOfVisit" size="30" maxlength="200" placeholder="<?php echo ew_HtmlEncode($academicmissions->NatureOfVisit->PlaceHolder) ?>" value="<?php echo $academicmissions->NatureOfVisit->EditValue ?>"<?php echo $academicmissions->NatureOfVisit->EditAttributes() ?>>
</span>
<?php echo $academicmissions->NatureOfVisit->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($academicmissions->AttendanceOnly->Visible) { // AttendanceOnly ?>
	<tr id="r_AttendanceOnly">
		<td><span id="elh_academicmissions_AttendanceOnly"><?php echo $academicmissions->AttendanceOnly->FldCaption() ?></span></td>
		<td<?php echo $academicmissions->AttendanceOnly->CellAttributes() ?>>
<span id="el_academicmissions_AttendanceOnly" class="control-group">
<div id="tp_x_AttendanceOnly" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_AttendanceOnly" id="x_AttendanceOnly" value="{value}"<?php echo $academicmissions->AttendanceOnly->EditAttributes() ?>></div>
<div id="dsl_x_AttendanceOnly" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $academicmissions->AttendanceOnly->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($academicmissions->AttendanceOnly->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_AttendanceOnly" name="x_AttendanceOnly" id="x_AttendanceOnly_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $academicmissions->AttendanceOnly->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $academicmissions->AttendanceOnly->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($academicmissions->PresentAPaper->Visible) { // PresentAPaper ?>
	<tr id="r_PresentAPaper">
		<td><span id="elh_academicmissions_PresentAPaper"><?php echo $academicmissions->PresentAPaper->FldCaption() ?></span></td>
		<td<?php echo $academicmissions->PresentAPaper->CellAttributes() ?>>
<span id="el_academicmissions_PresentAPaper" class="control-group">
<div id="tp_x_PresentAPaper" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_PresentAPaper" id="x_PresentAPaper" value="{value}"<?php echo $academicmissions->PresentAPaper->EditAttributes() ?>></div>
<div id="dsl_x_PresentAPaper" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $academicmissions->PresentAPaper->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($academicmissions->PresentAPaper->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_PresentAPaper" name="x_PresentAPaper" id="x_PresentAPaper_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $academicmissions->PresentAPaper->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $academicmissions->PresentAPaper->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($academicmissions->Others->Visible) { // Others ?>
	<tr id="r_Others">
		<td><span id="elh_academicmissions_Others"><?php echo $academicmissions->Others->FldCaption() ?></span></td>
		<td<?php echo $academicmissions->Others->CellAttributes() ?>>
<span id="el_academicmissions_Others" class="control-group">
<div id="tp_x_Others" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_Others" id="x_Others" value="{value}"<?php echo $academicmissions->Others->EditAttributes() ?>></div>
<div id="dsl_x_Others" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $academicmissions->Others->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($academicmissions->Others->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_Others" name="x_Others" id="x_Others_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $academicmissions->Others->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $academicmissions->Others->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($academicmissions->Participation->Visible) { // Participation ?>
	<tr id="r_Participation">
		<td><span id="elh_academicmissions_Participation"><?php echo $academicmissions->Participation->FldCaption() ?></span></td>
		<td<?php echo $academicmissions->Participation->CellAttributes() ?>>
<span id="el_academicmissions_Participation" class="control-group">
<div id="tp_x_Participation" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_Participation" id="x_Participation" value="{value}"<?php echo $academicmissions->Participation->EditAttributes() ?>></div>
<div id="dsl_x_Participation" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $academicmissions->Participation->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($academicmissions->Participation->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_Participation" name="x_Participation" id="x_Participation_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $academicmissions->Participation->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $academicmissions->Participation->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($academicmissions->Summary->Visible) { // Summary ?>
	<tr id="r_Summary">
		<td><span id="elh_academicmissions_Summary"><?php echo $academicmissions->Summary->FldCaption() ?></span></td>
		<td<?php echo $academicmissions->Summary->CellAttributes() ?>>
<span id="el_academicmissions_Summary" class="control-group">
<div id="tp_x_Summary" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_Summary" id="x_Summary" value="{value}"<?php echo $academicmissions->Summary->EditAttributes() ?>></div>
<div id="dsl_x_Summary" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $academicmissions->Summary->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($academicmissions->Summary->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_Summary" name="x_Summary" id="x_Summary_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $academicmissions->Summary->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $academicmissions->Summary->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($academicmissions->SuggestionRecommendation->Visible) { // SuggestionRecommendation ?>
	<tr id="r_SuggestionRecommendation">
		<td><span id="elh_academicmissions_SuggestionRecommendation"><?php echo $academicmissions->SuggestionRecommendation->FldCaption() ?></span></td>
		<td<?php echo $academicmissions->SuggestionRecommendation->CellAttributes() ?>>
<span id="el_academicmissions_SuggestionRecommendation" class="control-group">
<div id="tp_x_SuggestionRecommendation" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_SuggestionRecommendation" id="x_SuggestionRecommendation" value="{value}"<?php echo $academicmissions->SuggestionRecommendation->EditAttributes() ?>></div>
<div id="dsl_x_SuggestionRecommendation" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $academicmissions->SuggestionRecommendation->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($academicmissions->SuggestionRecommendation->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_SuggestionRecommendation" name="x_SuggestionRecommendation" id="x_SuggestionRecommendation_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $academicmissions->SuggestionRecommendation->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $academicmissions->SuggestionRecommendation->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($academicmissions->FacultyMemberSign->Visible) { // FacultyMemberSign ?>
	<tr id="r_FacultyMemberSign">
		<td><span id="elh_academicmissions_FacultyMemberSign"><?php echo $academicmissions->FacultyMemberSign->FldCaption() ?></span></td>
		<td<?php echo $academicmissions->FacultyMemberSign->CellAttributes() ?>>
<span id="el_academicmissions_FacultyMemberSign" class="control-group">
<div id="tp_x_FacultyMemberSign" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_FacultyMemberSign" id="x_FacultyMemberSign" value="{value}"<?php echo $academicmissions->FacultyMemberSign->EditAttributes() ?>></div>
<div id="dsl_x_FacultyMemberSign" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $academicmissions->FacultyMemberSign->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($academicmissions->FacultyMemberSign->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_FacultyMemberSign" name="x_FacultyMemberSign" id="x_FacultyMemberSign_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $academicmissions->FacultyMemberSign->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $academicmissions->FacultyMemberSign->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($academicmissions->DepChairmanSign->Visible) { // DepChairmanSign ?>
	<tr id="r_DepChairmanSign">
		<td><span id="elh_academicmissions_DepChairmanSign"><?php echo $academicmissions->DepChairmanSign->FldCaption() ?></span></td>
		<td<?php echo $academicmissions->DepChairmanSign->CellAttributes() ?>>
<span id="el_academicmissions_DepChairmanSign" class="control-group">
<div id="tp_x_DepChairmanSign" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_DepChairmanSign" id="x_DepChairmanSign" value="{value}"<?php echo $academicmissions->DepChairmanSign->EditAttributes() ?>></div>
<div id="dsl_x_DepChairmanSign" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $academicmissions->DepChairmanSign->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($academicmissions->DepChairmanSign->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_DepChairmanSign" name="x_DepChairmanSign" id="x_DepChairmanSign_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $academicmissions->DepChairmanSign->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $academicmissions->DepChairmanSign->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($academicmissions->DeanSign->Visible) { // DeanSign ?>
	<tr id="r_DeanSign">
		<td><span id="elh_academicmissions_DeanSign"><?php echo $academicmissions->DeanSign->FldCaption() ?></span></td>
		<td<?php echo $academicmissions->DeanSign->CellAttributes() ?>>
<span id="el_academicmissions_DeanSign" class="control-group">
<div id="tp_x_DeanSign" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_DeanSign" id="x_DeanSign" value="{value}"<?php echo $academicmissions->DeanSign->EditAttributes() ?>></div>
<div id="dsl_x_DeanSign" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $academicmissions->DeanSign->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($academicmissions->DeanSign->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_DeanSign" name="x_DeanSign" id="x_DeanSign_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $academicmissions->DeanSign->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $academicmissions->DeanSign->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
</form>
<script type="text/javascript">
facademicmissionsadd.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$academicmissions_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$academicmissions_add->Page_Terminate();
?>
