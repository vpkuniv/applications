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

$gpa_equivalency_delete = NULL; // Initialize page object first

class cgpa_equivalency_delete extends cgpa_equivalency {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{F8E6AD82-57C4-4BEE-8975-8D386184467A}";

	// Table name
	var $TableName = 'gpa equivalency';

	// Page object name
	var $PageObjName = 'gpa_equivalency_delete';

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

		// Table object (gpa_equivalency)
		if (!isset($GLOBALS["gpa_equivalency"]) || get_class($GLOBALS["gpa_equivalency"]) == "cgpa_equivalency") {
			$GLOBALS["gpa_equivalency"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["gpa_equivalency"];
		}

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

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
		if (!$Security->CanDelete()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate("gpa_equivalencylist.php");
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
			$this->Page_Terminate("gpa_equivalencylist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in gpa_equivalency class, gpa_equivalencyinfo.php

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
		$Breadcrumb->Add("list", $this->TableVar, "gpa_equivalencylist.php", $this->TableVar, TRUE);
		$PageId = "delete";
		$Breadcrumb->Add("delete", $PageId, ew_CurrentUrl());
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'gpa equivalency';
	  $usr = CurrentUserName();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (delete page)
	function WriteAuditTrailOnDelete(&$rs) {
		if (!$this->AuditTrailOnDelete) return;
		$table = 'gpa equivalency';

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
if (!isset($gpa_equivalency_delete)) $gpa_equivalency_delete = new cgpa_equivalency_delete();

// Page init
$gpa_equivalency_delete->Page_Init();

// Page main
$gpa_equivalency_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$gpa_equivalency_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var gpa_equivalency_delete = new ew_Page("gpa_equivalency_delete");
gpa_equivalency_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = gpa_equivalency_delete.PageID; // For backward compatibility

// Form object
var fgpa_equivalencydelete = new ew_Form("fgpa_equivalencydelete");

// Form_CustomValidate event
fgpa_equivalencydelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fgpa_equivalencydelete.ValidateRequired = true;
<?php } else { ?>
fgpa_equivalencydelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fgpa_equivalencydelete.Lists["x_Country"] = {"LinkField":"x_NID","Ajax":null,"AutoFill":false,"DisplayFields":["x_Nationality","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fgpa_equivalencydelete.Lists["x_Sector"] = {"LinkField":"x_ID","Ajax":null,"AutoFill":false,"DisplayFields":["x_Sector","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fgpa_equivalencydelete.Lists["x_Job_Title"] = {"LinkField":"x_ID","Ajax":null,"AutoFill":false,"DisplayFields":["x_Job_Title","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fgpa_equivalencydelete.Lists["x_College"] = {"LinkField":"x_CollegeID","Ajax":null,"AutoFill":false,"DisplayFields":["x_College_Name_AR","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fgpa_equivalencydelete.Lists["x_Department"] = {"LinkField":"x_DID","Ajax":null,"AutoFill":false,"DisplayFields":["x_DepartmentName2DAR","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($gpa_equivalency_delete->Recordset = $gpa_equivalency_delete->LoadRecordset())
	$gpa_equivalency_deleteTotalRecs = $gpa_equivalency_delete->Recordset->RecordCount(); // Get record count
if ($gpa_equivalency_deleteTotalRecs <= 0) { // No record found, exit
	if ($gpa_equivalency_delete->Recordset)
		$gpa_equivalency_delete->Recordset->Close();
	$gpa_equivalency_delete->Page_Terminate("gpa_equivalencylist.php"); // Return to list
}
?>
<?php $Breadcrumb->Render(); ?>
<?php $gpa_equivalency_delete->ShowPageHeader(); ?>
<?php
$gpa_equivalency_delete->ShowMessage();
?>
<form name="fgpa_equivalencydelete" id="fgpa_equivalencydelete" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="gpa_equivalency">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($gpa_equivalency_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<table class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_gpa_equivalencydelete" class="ewTable ewTableSeparate">
<?php echo $gpa_equivalency->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($gpa_equivalency->ID->Visible) { // ID ?>
		<td><span id="elh_gpa_equivalency_ID" class="gpa_equivalency_ID"><?php echo $gpa_equivalency->ID->FldCaption() ?></span></td>
<?php } ?>
<?php if ($gpa_equivalency->Name->Visible) { // Name ?>
		<td><span id="elh_gpa_equivalency_Name" class="gpa_equivalency_Name"><?php echo $gpa_equivalency->Name->FldCaption() ?></span></td>
<?php } ?>
<?php if ($gpa_equivalency->Country->Visible) { // Country ?>
		<td><span id="elh_gpa_equivalency_Country" class="gpa_equivalency_Country"><?php echo $gpa_equivalency->Country->FldCaption() ?></span></td>
<?php } ?>
<?php if ($gpa_equivalency->Civil_ID->Visible) { // Civil ID ?>
		<td><span id="elh_gpa_equivalency_Civil_ID" class="gpa_equivalency_Civil_ID"><?php echo $gpa_equivalency->Civil_ID->FldCaption() ?></span></td>
<?php } ?>
<?php if ($gpa_equivalency->Sector->Visible) { // Sector ?>
		<td><span id="elh_gpa_equivalency_Sector" class="gpa_equivalency_Sector"><?php echo $gpa_equivalency->Sector->FldCaption() ?></span></td>
<?php } ?>
<?php if ($gpa_equivalency->Job_Title->Visible) { // Job Title ?>
		<td><span id="elh_gpa_equivalency_Job_Title" class="gpa_equivalency_Job_Title"><?php echo $gpa_equivalency->Job_Title->FldCaption() ?></span></td>
<?php } ?>
<?php if ($gpa_equivalency->Program->Visible) { // Program ?>
		<td><span id="elh_gpa_equivalency_Program" class="gpa_equivalency_Program"><?php echo $gpa_equivalency->Program->FldCaption() ?></span></td>
<?php } ?>
<?php if ($gpa_equivalency->College->Visible) { // College ?>
		<td><span id="elh_gpa_equivalency_College" class="gpa_equivalency_College"><?php echo $gpa_equivalency->College->FldCaption() ?></span></td>
<?php } ?>
<?php if ($gpa_equivalency->Department->Visible) { // Department ?>
		<td><span id="elh_gpa_equivalency_Department" class="gpa_equivalency_Department"><?php echo $gpa_equivalency->Department->FldCaption() ?></span></td>
<?php } ?>
<?php if ($gpa_equivalency->Committee_Date->Visible) { // Committee Date ?>
		<td><span id="elh_gpa_equivalency_Committee_Date" class="gpa_equivalency_Committee_Date"><?php echo $gpa_equivalency->Committee_Date->FldCaption() ?></span></td>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$gpa_equivalency_delete->RecCnt = 0;
$i = 0;
while (!$gpa_equivalency_delete->Recordset->EOF) {
	$gpa_equivalency_delete->RecCnt++;
	$gpa_equivalency_delete->RowCnt++;

	// Set row properties
	$gpa_equivalency->ResetAttrs();
	$gpa_equivalency->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$gpa_equivalency_delete->LoadRowValues($gpa_equivalency_delete->Recordset);

	// Render row
	$gpa_equivalency_delete->RenderRow();
?>
	<tr<?php echo $gpa_equivalency->RowAttributes() ?>>
<?php if ($gpa_equivalency->ID->Visible) { // ID ?>
		<td<?php echo $gpa_equivalency->ID->CellAttributes() ?>>
<span id="el<?php echo $gpa_equivalency_delete->RowCnt ?>_gpa_equivalency_ID" class="control-group gpa_equivalency_ID">
<span<?php echo $gpa_equivalency->ID->ViewAttributes() ?>>
<?php echo $gpa_equivalency->ID->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($gpa_equivalency->Name->Visible) { // Name ?>
		<td<?php echo $gpa_equivalency->Name->CellAttributes() ?>>
<span id="el<?php echo $gpa_equivalency_delete->RowCnt ?>_gpa_equivalency_Name" class="control-group gpa_equivalency_Name">
<span<?php echo $gpa_equivalency->Name->ViewAttributes() ?>>
<?php echo $gpa_equivalency->Name->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($gpa_equivalency->Country->Visible) { // Country ?>
		<td<?php echo $gpa_equivalency->Country->CellAttributes() ?>>
<span id="el<?php echo $gpa_equivalency_delete->RowCnt ?>_gpa_equivalency_Country" class="control-group gpa_equivalency_Country">
<span<?php echo $gpa_equivalency->Country->ViewAttributes() ?>>
<?php echo $gpa_equivalency->Country->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($gpa_equivalency->Civil_ID->Visible) { // Civil ID ?>
		<td<?php echo $gpa_equivalency->Civil_ID->CellAttributes() ?>>
<span id="el<?php echo $gpa_equivalency_delete->RowCnt ?>_gpa_equivalency_Civil_ID" class="control-group gpa_equivalency_Civil_ID">
<span<?php echo $gpa_equivalency->Civil_ID->ViewAttributes() ?>>
<?php echo $gpa_equivalency->Civil_ID->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($gpa_equivalency->Sector->Visible) { // Sector ?>
		<td<?php echo $gpa_equivalency->Sector->CellAttributes() ?>>
<span id="el<?php echo $gpa_equivalency_delete->RowCnt ?>_gpa_equivalency_Sector" class="control-group gpa_equivalency_Sector">
<span<?php echo $gpa_equivalency->Sector->ViewAttributes() ?>>
<?php echo $gpa_equivalency->Sector->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($gpa_equivalency->Job_Title->Visible) { // Job Title ?>
		<td<?php echo $gpa_equivalency->Job_Title->CellAttributes() ?>>
<span id="el<?php echo $gpa_equivalency_delete->RowCnt ?>_gpa_equivalency_Job_Title" class="control-group gpa_equivalency_Job_Title">
<span<?php echo $gpa_equivalency->Job_Title->ViewAttributes() ?>>
<?php echo $gpa_equivalency->Job_Title->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($gpa_equivalency->Program->Visible) { // Program ?>
		<td<?php echo $gpa_equivalency->Program->CellAttributes() ?>>
<span id="el<?php echo $gpa_equivalency_delete->RowCnt ?>_gpa_equivalency_Program" class="control-group gpa_equivalency_Program">
<span<?php echo $gpa_equivalency->Program->ViewAttributes() ?>>
<?php echo $gpa_equivalency->Program->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($gpa_equivalency->College->Visible) { // College ?>
		<td<?php echo $gpa_equivalency->College->CellAttributes() ?>>
<span id="el<?php echo $gpa_equivalency_delete->RowCnt ?>_gpa_equivalency_College" class="control-group gpa_equivalency_College">
<span<?php echo $gpa_equivalency->College->ViewAttributes() ?>>
<?php echo $gpa_equivalency->College->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($gpa_equivalency->Department->Visible) { // Department ?>
		<td<?php echo $gpa_equivalency->Department->CellAttributes() ?>>
<span id="el<?php echo $gpa_equivalency_delete->RowCnt ?>_gpa_equivalency_Department" class="control-group gpa_equivalency_Department">
<span<?php echo $gpa_equivalency->Department->ViewAttributes() ?>>
<?php echo $gpa_equivalency->Department->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($gpa_equivalency->Committee_Date->Visible) { // Committee Date ?>
		<td<?php echo $gpa_equivalency->Committee_Date->CellAttributes() ?>>
<span id="el<?php echo $gpa_equivalency_delete->RowCnt ?>_gpa_equivalency_Committee_Date" class="control-group gpa_equivalency_Committee_Date">
<span<?php echo $gpa_equivalency->Committee_Date->ViewAttributes() ?>>
<?php echo $gpa_equivalency->Committee_Date->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$gpa_equivalency_delete->Recordset->MoveNext();
}
$gpa_equivalency_delete->Recordset->Close();
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
fgpa_equivalencydelete.Init();
</script>
<?php
$gpa_equivalency_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$gpa_equivalency_delete->Page_Terminate();
?>
