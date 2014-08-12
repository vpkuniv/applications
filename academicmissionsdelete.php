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

$academicmissions_delete = NULL; // Initialize page object first

class cacademicmissions_delete extends cacademicmissions {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{F8E6AD82-57C4-4BEE-8975-8D386184467A}";

	// Table name
	var $TableName = 'academicmissions';

	// Page object name
	var $PageObjName = 'academicmissions_delete';

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

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

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
		if (!$Security->CanDelete()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate("academicmissionslist.php");
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->ID->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $StartRowCnt = 1;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("academicmissionslist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in academicmissions class, academicmissionsinfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} else {
			$this->CurrentAction = "I"; // Display record
		}
		switch ($this->CurrentAction) {
			case "D": // Delete
				$this->SendEmail = TRUE; // Send email on delete success
				if ($this->DeleteRows()) { // Delete rows
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
					$this->Page_Terminate($this->getReturnUrl()); // Return to caller
				}
		}
	}

// No functions
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
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $conn, $Language, $Security;
		if (!$Security->CanDelete()) {
			$this->setFailureMessage($Language->Phrase("NoDeletePermission")); // No delete permission
			return FALSE;
		}
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = 'ew_ErrorFn';
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;

		//} else {
		//	$this->LoadRowValues($rs); // Load row values

		}
		$conn->BeginTrans();
		if ($this->AuditTrailOnDelete) $this->WriteAuditTrailDummy($Language->Phrase("BatchDeleteBegin")); // Batch delete begin

		// Clone old rows
		$rsold = ($rs) ? $rs->GetRows() : array();
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['ID'];
				$this->LoadDbValues($row);
				$conn->raiseErrorFn = 'ew_ErrorFn';
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		} else {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			$conn->CommitTrans(); // Commit the changes
			if ($DeleteRows) {
				foreach ($rsold as $row)
					$this->WriteAuditTrailOnDelete($row);
			}
			if ($this->AuditTrailOnDelete) $this->WriteAuditTrailDummy($Language->Phrase("BatchDeleteSuccess")); // Batch delete success
		} else {
			$conn->RollbackTrans(); // Rollback changes
			if ($this->AuditTrailOnDelete) $this->WriteAuditTrailDummy($Language->Phrase("BatchDeleteRollback")); // Batch delete rollback
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$Breadcrumb->Add("list", $this->TableVar, "academicmissionslist.php", $this->TableVar, TRUE);
		$PageId = "delete";
		$Breadcrumb->Add("delete", $PageId, ew_CurrentUrl());
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'academicmissions';
	  $usr = CurrentUserName();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
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
}
?>
<?php ew_Header(TRUE) ?>
<?php

// Create page object
if (!isset($academicmissions_delete)) $academicmissions_delete = new cacademicmissions_delete();

// Page init
$academicmissions_delete->Page_Init();

// Page main
$academicmissions_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$academicmissions_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var academicmissions_delete = new ew_Page("academicmissions_delete");
academicmissions_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = academicmissions_delete.PageID; // For backward compatibility

// Form object
var facademicmissionsdelete = new ew_Form("facademicmissionsdelete");

// Form_CustomValidate event
facademicmissionsdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
facademicmissionsdelete.ValidateRequired = true;
<?php } else { ?>
facademicmissionsdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
facademicmissionsdelete.Lists["x_College"] = {"LinkField":"x_CollegeID","Ajax":null,"AutoFill":false,"DisplayFields":["x_College_Name_EN","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
facademicmissionsdelete.Lists["x_Department"] = {"LinkField":"x_DID","Ajax":null,"AutoFill":false,"DisplayFields":["x_DepartmentName","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($academicmissions_delete->Recordset = $academicmissions_delete->LoadRecordset())
	$academicmissions_deleteTotalRecs = $academicmissions_delete->Recordset->RecordCount(); // Get record count
if ($academicmissions_deleteTotalRecs <= 0) { // No record found, exit
	if ($academicmissions_delete->Recordset)
		$academicmissions_delete->Recordset->Close();
	$academicmissions_delete->Page_Terminate("academicmissionslist.php"); // Return to list
}
?>
<?php $Breadcrumb->Render(); ?>
<?php $academicmissions_delete->ShowPageHeader(); ?>
<?php
$academicmissions_delete->ShowMessage();
?>
<form name="facademicmissionsdelete" id="facademicmissionsdelete" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="academicmissions">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($academicmissions_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<table class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_academicmissionsdelete" class="ewTable ewTableSeparate">
<?php echo $academicmissions->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($academicmissions->ID->Visible) { // ID ?>
		<td><span id="elh_academicmissions_ID" class="academicmissions_ID"><?php echo $academicmissions->ID->FldCaption() ?></span></td>
<?php } ?>
<?php if ($academicmissions->Name->Visible) { // Name ?>
		<td><span id="elh_academicmissions_Name" class="academicmissions_Name"><?php echo $academicmissions->Name->FldCaption() ?></span></td>
<?php } ?>
<?php if ($academicmissions->UniversityID->Visible) { // UniversityID ?>
		<td><span id="elh_academicmissions_UniversityID" class="academicmissions_UniversityID"><?php echo $academicmissions->UniversityID->FldCaption() ?></span></td>
<?php } ?>
<?php if ($academicmissions->College->Visible) { // College ?>
		<td><span id="elh_academicmissions_College" class="academicmissions_College"><?php echo $academicmissions->College->FldCaption() ?></span></td>
<?php } ?>
<?php if ($academicmissions->Department->Visible) { // Department ?>
		<td><span id="elh_academicmissions_Department" class="academicmissions_Department"><?php echo $academicmissions->Department->FldCaption() ?></span></td>
<?php } ?>
<?php if ($academicmissions->StartDate->Visible) { // StartDate ?>
		<td><span id="elh_academicmissions_StartDate" class="academicmissions_StartDate"><?php echo $academicmissions->StartDate->FldCaption() ?></span></td>
<?php } ?>
<?php if ($academicmissions->EndDate->Visible) { // EndDate ?>
		<td><span id="elh_academicmissions_EndDate" class="academicmissions_EndDate"><?php echo $academicmissions->EndDate->FldCaption() ?></span></td>
<?php } ?>
<?php if ($academicmissions->PlaceVisited->Visible) { // PlaceVisited ?>
		<td><span id="elh_academicmissions_PlaceVisited" class="academicmissions_PlaceVisited"><?php echo $academicmissions->PlaceVisited->FldCaption() ?></span></td>
<?php } ?>
<?php if ($academicmissions->NatureOfVisit->Visible) { // NatureOfVisit ?>
		<td><span id="elh_academicmissions_NatureOfVisit" class="academicmissions_NatureOfVisit"><?php echo $academicmissions->NatureOfVisit->FldCaption() ?></span></td>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$academicmissions_delete->RecCnt = 0;
$i = 0;
while (!$academicmissions_delete->Recordset->EOF) {
	$academicmissions_delete->RecCnt++;
	$academicmissions_delete->RowCnt++;

	// Set row properties
	$academicmissions->ResetAttrs();
	$academicmissions->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$academicmissions_delete->LoadRowValues($academicmissions_delete->Recordset);

	// Render row
	$academicmissions_delete->RenderRow();
?>
	<tr<?php echo $academicmissions->RowAttributes() ?>>
<?php if ($academicmissions->ID->Visible) { // ID ?>
		<td<?php echo $academicmissions->ID->CellAttributes() ?>>
<span id="el<?php echo $academicmissions_delete->RowCnt ?>_academicmissions_ID" class="control-group academicmissions_ID">
<span<?php echo $academicmissions->ID->ViewAttributes() ?>>
<?php echo $academicmissions->ID->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($academicmissions->Name->Visible) { // Name ?>
		<td<?php echo $academicmissions->Name->CellAttributes() ?>>
<span id="el<?php echo $academicmissions_delete->RowCnt ?>_academicmissions_Name" class="control-group academicmissions_Name">
<span<?php echo $academicmissions->Name->ViewAttributes() ?>>
<?php echo $academicmissions->Name->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($academicmissions->UniversityID->Visible) { // UniversityID ?>
		<td<?php echo $academicmissions->UniversityID->CellAttributes() ?>>
<span id="el<?php echo $academicmissions_delete->RowCnt ?>_academicmissions_UniversityID" class="control-group academicmissions_UniversityID">
<span<?php echo $academicmissions->UniversityID->ViewAttributes() ?>>
<?php echo $academicmissions->UniversityID->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($academicmissions->College->Visible) { // College ?>
		<td<?php echo $academicmissions->College->CellAttributes() ?>>
<span id="el<?php echo $academicmissions_delete->RowCnt ?>_academicmissions_College" class="control-group academicmissions_College">
<span<?php echo $academicmissions->College->ViewAttributes() ?>>
<?php echo $academicmissions->College->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($academicmissions->Department->Visible) { // Department ?>
		<td<?php echo $academicmissions->Department->CellAttributes() ?>>
<span id="el<?php echo $academicmissions_delete->RowCnt ?>_academicmissions_Department" class="control-group academicmissions_Department">
<span<?php echo $academicmissions->Department->ViewAttributes() ?>>
<?php echo $academicmissions->Department->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($academicmissions->StartDate->Visible) { // StartDate ?>
		<td<?php echo $academicmissions->StartDate->CellAttributes() ?>>
<span id="el<?php echo $academicmissions_delete->RowCnt ?>_academicmissions_StartDate" class="control-group academicmissions_StartDate">
<span<?php echo $academicmissions->StartDate->ViewAttributes() ?>>
<?php echo $academicmissions->StartDate->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($academicmissions->EndDate->Visible) { // EndDate ?>
		<td<?php echo $academicmissions->EndDate->CellAttributes() ?>>
<span id="el<?php echo $academicmissions_delete->RowCnt ?>_academicmissions_EndDate" class="control-group academicmissions_EndDate">
<span<?php echo $academicmissions->EndDate->ViewAttributes() ?>>
<?php echo $academicmissions->EndDate->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($academicmissions->PlaceVisited->Visible) { // PlaceVisited ?>
		<td<?php echo $academicmissions->PlaceVisited->CellAttributes() ?>>
<span id="el<?php echo $academicmissions_delete->RowCnt ?>_academicmissions_PlaceVisited" class="control-group academicmissions_PlaceVisited">
<span<?php echo $academicmissions->PlaceVisited->ViewAttributes() ?>>
<?php echo $academicmissions->PlaceVisited->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($academicmissions->NatureOfVisit->Visible) { // NatureOfVisit ?>
		<td<?php echo $academicmissions->NatureOfVisit->CellAttributes() ?>>
<span id="el<?php echo $academicmissions_delete->RowCnt ?>_academicmissions_NatureOfVisit" class="control-group academicmissions_NatureOfVisit">
<span<?php echo $academicmissions->NatureOfVisit->ViewAttributes() ?>>
<?php echo $academicmissions->NatureOfVisit->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$academicmissions_delete->Recordset->MoveNext();
}
$academicmissions_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</td></tr></table>
<div class="btn-group ewButtonGroup">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
</div>
</form>
<script type="text/javascript">
facademicmissionsdelete.Init();
</script>
<?php
$academicmissions_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$academicmissions_delete->Page_Terminate();
?>
