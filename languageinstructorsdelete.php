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

$languageinstructors_delete = NULL; // Initialize page object first

class clanguageinstructors_delete extends clanguageinstructors {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{F8E6AD82-57C4-4BEE-8975-8D386184467A}";

	// Table name
	var $TableName = 'languageinstructors';

	// Page object name
	var $PageObjName = 'languageinstructors_delete';

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

		// Table object (languageinstructors)
		if (!isset($GLOBALS["languageinstructors"]) || get_class($GLOBALS["languageinstructors"]) == "clanguageinstructors") {
			$GLOBALS["languageinstructors"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["languageinstructors"];
		}

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

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
		if (!$Security->CanDelete()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate("languageinstructorslist.php");
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
			$this->Page_Terminate("languageinstructorslist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in languageinstructors class, languageinstructorsinfo.php

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
		$Breadcrumb->Add("list", $this->TableVar, "languageinstructorslist.php", $this->TableVar, TRUE);
		$PageId = "delete";
		$Breadcrumb->Add("delete", $PageId, ew_CurrentUrl());
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'languageinstructors';
	  $usr = CurrentUserName();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
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
}
?>
<?php ew_Header(TRUE) ?>
<?php

// Create page object
if (!isset($languageinstructors_delete)) $languageinstructors_delete = new clanguageinstructors_delete();

// Page init
$languageinstructors_delete->Page_Init();

// Page main
$languageinstructors_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$languageinstructors_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var languageinstructors_delete = new ew_Page("languageinstructors_delete");
languageinstructors_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = languageinstructors_delete.PageID; // For backward compatibility

// Form object
var flanguageinstructorsdelete = new ew_Form("flanguageinstructorsdelete");

// Form_CustomValidate event
flanguageinstructorsdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
flanguageinstructorsdelete.ValidateRequired = true;
<?php } else { ?>
flanguageinstructorsdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
flanguageinstructorsdelete.Lists["x_College1"] = {"LinkField":"x_CollegeID","Ajax":null,"AutoFill":false,"DisplayFields":["x_College_Name_EN","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
flanguageinstructorsdelete.Lists["x_College2"] = {"LinkField":"x_CollegeID","Ajax":null,"AutoFill":false,"DisplayFields":["x_College_Name_EN","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
flanguageinstructorsdelete.Lists["x_College3"] = {"LinkField":"x_CollegeID","Ajax":null,"AutoFill":false,"DisplayFields":["x_College_Name_EN","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($languageinstructors_delete->Recordset = $languageinstructors_delete->LoadRecordset())
	$languageinstructors_deleteTotalRecs = $languageinstructors_delete->Recordset->RecordCount(); // Get record count
if ($languageinstructors_deleteTotalRecs <= 0) { // No record found, exit
	if ($languageinstructors_delete->Recordset)
		$languageinstructors_delete->Recordset->Close();
	$languageinstructors_delete->Page_Terminate("languageinstructorslist.php"); // Return to list
}
?>
<?php $Breadcrumb->Render(); ?>
<?php $languageinstructors_delete->ShowPageHeader(); ?>
<?php
$languageinstructors_delete->ShowMessage();
?>
<form name="flanguageinstructorsdelete" id="flanguageinstructorsdelete" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="languageinstructors">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($languageinstructors_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<table class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_languageinstructorsdelete" class="ewTable ewTableSeparate">
<?php echo $languageinstructors->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($languageinstructors->ID->Visible) { // ID ?>
		<td><span id="elh_languageinstructors_ID" class="languageinstructors_ID"><?php echo $languageinstructors->ID->FldCaption() ?></span></td>
<?php } ?>
<?php if ($languageinstructors->ApplicantName->Visible) { // ApplicantName ?>
		<td><span id="elh_languageinstructors_ApplicantName" class="languageinstructors_ApplicantName"><?php echo $languageinstructors->ApplicantName->FldCaption() ?></span></td>
<?php } ?>
<?php if ($languageinstructors->Nationality->Visible) { // Nationality ?>
		<td><span id="elh_languageinstructors_Nationality" class="languageinstructors_Nationality"><?php echo $languageinstructors->Nationality->FldCaption() ?></span></td>
<?php } ?>
<?php if ($languageinstructors->_Language->Visible) { // Language ?>
		<td><span id="elh_languageinstructors__Language" class="languageinstructors__Language"><?php echo $languageinstructors->_Language->FldCaption() ?></span></td>
<?php } ?>
<?php if ($languageinstructors->College1->Visible) { // College1 ?>
		<td><span id="elh_languageinstructors_College1" class="languageinstructors_College1"><?php echo $languageinstructors->College1->FldCaption() ?></span></td>
<?php } ?>
<?php if ($languageinstructors->College2->Visible) { // College2 ?>
		<td><span id="elh_languageinstructors_College2" class="languageinstructors_College2"><?php echo $languageinstructors->College2->FldCaption() ?></span></td>
<?php } ?>
<?php if ($languageinstructors->College3->Visible) { // College3 ?>
		<td><span id="elh_languageinstructors_College3" class="languageinstructors_College3"><?php echo $languageinstructors->College3->FldCaption() ?></span></td>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$languageinstructors_delete->RecCnt = 0;
$i = 0;
while (!$languageinstructors_delete->Recordset->EOF) {
	$languageinstructors_delete->RecCnt++;
	$languageinstructors_delete->RowCnt++;

	// Set row properties
	$languageinstructors->ResetAttrs();
	$languageinstructors->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$languageinstructors_delete->LoadRowValues($languageinstructors_delete->Recordset);

	// Render row
	$languageinstructors_delete->RenderRow();
?>
	<tr<?php echo $languageinstructors->RowAttributes() ?>>
<?php if ($languageinstructors->ID->Visible) { // ID ?>
		<td<?php echo $languageinstructors->ID->CellAttributes() ?>>
<span id="el<?php echo $languageinstructors_delete->RowCnt ?>_languageinstructors_ID" class="control-group languageinstructors_ID">
<span<?php echo $languageinstructors->ID->ViewAttributes() ?>>
<?php echo $languageinstructors->ID->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($languageinstructors->ApplicantName->Visible) { // ApplicantName ?>
		<td<?php echo $languageinstructors->ApplicantName->CellAttributes() ?>>
<span id="el<?php echo $languageinstructors_delete->RowCnt ?>_languageinstructors_ApplicantName" class="control-group languageinstructors_ApplicantName">
<span<?php echo $languageinstructors->ApplicantName->ViewAttributes() ?>>
<?php echo $languageinstructors->ApplicantName->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($languageinstructors->Nationality->Visible) { // Nationality ?>
		<td<?php echo $languageinstructors->Nationality->CellAttributes() ?>>
<span id="el<?php echo $languageinstructors_delete->RowCnt ?>_languageinstructors_Nationality" class="control-group languageinstructors_Nationality">
<span<?php echo $languageinstructors->Nationality->ViewAttributes() ?>>
<?php echo $languageinstructors->Nationality->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($languageinstructors->_Language->Visible) { // Language ?>
		<td<?php echo $languageinstructors->_Language->CellAttributes() ?>>
<span id="el<?php echo $languageinstructors_delete->RowCnt ?>_languageinstructors__Language" class="control-group languageinstructors__Language">
<span<?php echo $languageinstructors->_Language->ViewAttributes() ?>>
<?php echo $languageinstructors->_Language->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($languageinstructors->College1->Visible) { // College1 ?>
		<td<?php echo $languageinstructors->College1->CellAttributes() ?>>
<span id="el<?php echo $languageinstructors_delete->RowCnt ?>_languageinstructors_College1" class="control-group languageinstructors_College1">
<span<?php echo $languageinstructors->College1->ViewAttributes() ?>>
<?php echo $languageinstructors->College1->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($languageinstructors->College2->Visible) { // College2 ?>
		<td<?php echo $languageinstructors->College2->CellAttributes() ?>>
<span id="el<?php echo $languageinstructors_delete->RowCnt ?>_languageinstructors_College2" class="control-group languageinstructors_College2">
<span<?php echo $languageinstructors->College2->ViewAttributes() ?>>
<?php echo $languageinstructors->College2->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($languageinstructors->College3->Visible) { // College3 ?>
		<td<?php echo $languageinstructors->College3->CellAttributes() ?>>
<span id="el<?php echo $languageinstructors_delete->RowCnt ?>_languageinstructors_College3" class="control-group languageinstructors_College3">
<span<?php echo $languageinstructors->College3->ViewAttributes() ?>>
<?php echo $languageinstructors->College3->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$languageinstructors_delete->Recordset->MoveNext();
}
$languageinstructors_delete->Recordset->Close();
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
flanguageinstructorsdelete.Init();
</script>
<?php
$languageinstructors_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$languageinstructors_delete->Page_Terminate();
?>
