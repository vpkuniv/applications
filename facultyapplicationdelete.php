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

$facultyapplication_delete = NULL; // Initialize page object first

class cfacultyapplication_delete extends cfacultyapplication {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{F8E6AD82-57C4-4BEE-8975-8D386184467A}";

	// Table name
	var $TableName = 'facultyapplication';

	// Page object name
	var $PageObjName = 'facultyapplication_delete';

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

		// Table object (facultyapplication)
		if (!isset($GLOBALS["facultyapplication"]) || get_class($GLOBALS["facultyapplication"]) == "cfacultyapplication") {
			$GLOBALS["facultyapplication"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["facultyapplication"];
		}

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

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
		if (!$Security->CanDelete()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate("facultyapplicationlist.php");
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
			$this->Page_Terminate("facultyapplicationlist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in facultyapplication class, facultyapplicationinfo.php

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

			// ID
			$this->ID->LinkCustomAttributes = "";
			$this->ID->HrefValue = "";
			$this->ID->TooltipValue = "";

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
		$Breadcrumb->Add("list", $this->TableVar, "facultyapplicationlist.php", $this->TableVar, TRUE);
		$PageId = "delete";
		$Breadcrumb->Add("delete", $PageId, ew_CurrentUrl());
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'facultyapplication';
	  $usr = CurrentUserName();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (delete page)
	function WriteAuditTrailOnDelete(&$rs) {
		if (!$this->AuditTrailOnDelete) return;
		$table = 'facultyapplication';

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
if (!isset($facultyapplication_delete)) $facultyapplication_delete = new cfacultyapplication_delete();

// Page init
$facultyapplication_delete->Page_Init();

// Page main
$facultyapplication_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$facultyapplication_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var facultyapplication_delete = new ew_Page("facultyapplication_delete");
facultyapplication_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = facultyapplication_delete.PageID; // For backward compatibility

// Form object
var ffacultyapplicationdelete = new ew_Form("ffacultyapplicationdelete");

// Form_CustomValidate event
ffacultyapplicationdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ffacultyapplicationdelete.ValidateRequired = true;
<?php } else { ?>
ffacultyapplicationdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ffacultyapplicationdelete.Lists["x_Department"] = {"LinkField":"x_DID","Ajax":null,"AutoFill":false,"DisplayFields":["x_DepartmentName","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($facultyapplication_delete->Recordset = $facultyapplication_delete->LoadRecordset())
	$facultyapplication_deleteTotalRecs = $facultyapplication_delete->Recordset->RecordCount(); // Get record count
if ($facultyapplication_deleteTotalRecs <= 0) { // No record found, exit
	if ($facultyapplication_delete->Recordset)
		$facultyapplication_delete->Recordset->Close();
	$facultyapplication_delete->Page_Terminate("facultyapplicationlist.php"); // Return to list
}
?>
<?php $Breadcrumb->Render(); ?>
<?php $facultyapplication_delete->ShowPageHeader(); ?>
<?php
$facultyapplication_delete->ShowMessage();
?>
<form name="ffacultyapplicationdelete" id="ffacultyapplicationdelete" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="facultyapplication">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($facultyapplication_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<table class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_facultyapplicationdelete" class="ewTable ewTableSeparate">
<?php echo $facultyapplication->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($facultyapplication->ID->Visible) { // ID ?>
		<td><span id="elh_facultyapplication_ID" class="facultyapplication_ID"><?php echo $facultyapplication->ID->FldCaption() ?></span></td>
<?php } ?>
<?php if ($facultyapplication->Name->Visible) { // Name ?>
		<td><span id="elh_facultyapplication_Name" class="facultyapplication_Name"><?php echo $facultyapplication->Name->FldCaption() ?></span></td>
<?php } ?>
<?php if ($facultyapplication->Nationality->Visible) { // Nationality ?>
		<td><span id="elh_facultyapplication_Nationality" class="facultyapplication_Nationality"><?php echo $facultyapplication->Nationality->FldCaption() ?></span></td>
<?php } ?>
<?php if ($facultyapplication->College->Visible) { // College ?>
		<td><span id="elh_facultyapplication_College" class="facultyapplication_College"><?php echo $facultyapplication->College->FldCaption() ?></span></td>
<?php } ?>
<?php if ($facultyapplication->Department->Visible) { // Department ?>
		<td><span id="elh_facultyapplication_Department" class="facultyapplication_Department"><?php echo $facultyapplication->Department->FldCaption() ?></span></td>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$facultyapplication_delete->RecCnt = 0;
$i = 0;
while (!$facultyapplication_delete->Recordset->EOF) {
	$facultyapplication_delete->RecCnt++;
	$facultyapplication_delete->RowCnt++;

	// Set row properties
	$facultyapplication->ResetAttrs();
	$facultyapplication->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$facultyapplication_delete->LoadRowValues($facultyapplication_delete->Recordset);

	// Render row
	$facultyapplication_delete->RenderRow();
?>
	<tr<?php echo $facultyapplication->RowAttributes() ?>>
<?php if ($facultyapplication->ID->Visible) { // ID ?>
		<td<?php echo $facultyapplication->ID->CellAttributes() ?>>
<span id="el<?php echo $facultyapplication_delete->RowCnt ?>_facultyapplication_ID" class="control-group facultyapplication_ID">
<span<?php echo $facultyapplication->ID->ViewAttributes() ?>>
<?php echo $facultyapplication->ID->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($facultyapplication->Name->Visible) { // Name ?>
		<td<?php echo $facultyapplication->Name->CellAttributes() ?>>
<span id="el<?php echo $facultyapplication_delete->RowCnt ?>_facultyapplication_Name" class="control-group facultyapplication_Name">
<span<?php echo $facultyapplication->Name->ViewAttributes() ?>>
<?php echo $facultyapplication->Name->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($facultyapplication->Nationality->Visible) { // Nationality ?>
		<td<?php echo $facultyapplication->Nationality->CellAttributes() ?>>
<span id="el<?php echo $facultyapplication_delete->RowCnt ?>_facultyapplication_Nationality" class="control-group facultyapplication_Nationality">
<span<?php echo $facultyapplication->Nationality->ViewAttributes() ?>>
<?php echo $facultyapplication->Nationality->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($facultyapplication->College->Visible) { // College ?>
		<td<?php echo $facultyapplication->College->CellAttributes() ?>>
<span id="el<?php echo $facultyapplication_delete->RowCnt ?>_facultyapplication_College" class="control-group facultyapplication_College">
<span<?php echo $facultyapplication->College->ViewAttributes() ?>>
<?php echo $facultyapplication->College->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($facultyapplication->Department->Visible) { // Department ?>
		<td<?php echo $facultyapplication->Department->CellAttributes() ?>>
<span id="el<?php echo $facultyapplication_delete->RowCnt ?>_facultyapplication_Department" class="control-group facultyapplication_Department">
<span<?php echo $facultyapplication->Department->ViewAttributes() ?>>
<?php echo $facultyapplication->Department->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$facultyapplication_delete->Recordset->MoveNext();
}
$facultyapplication_delete->Recordset->Close();
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
ffacultyapplicationdelete.Init();
</script>
<?php
$facultyapplication_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$facultyapplication_delete->Page_Terminate();
?>
