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

$scholarships_delete = NULL; // Initialize page object first

class cscholarships_delete extends cscholarships {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{F8E6AD82-57C4-4BEE-8975-8D386184467A}";

	// Table name
	var $TableName = 'scholarships';

	// Page object name
	var $PageObjName = 'scholarships_delete';

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

		// Table object (scholarships)
		if (!isset($GLOBALS["scholarships"]) || get_class($GLOBALS["scholarships"]) == "cscholarships") {
			$GLOBALS["scholarships"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["scholarships"];
		}

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

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
		if (!$Security->CanDelete()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate("scholarshipslist.php");
		}
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
			$this->Page_Terminate("scholarshipslist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in scholarships class, scholarshipsinfo.php

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

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
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

			// Acceptance University
			$this->Acceptance_University->LinkCustomAttributes = "";
			$this->Acceptance_University->HrefValue = "";
			$this->Acceptance_University->TooltipValue = "";

			// Program Degree
			$this->Program_Degree->LinkCustomAttributes = "";
			$this->Program_Degree->HrefValue = "";
			$this->Program_Degree->TooltipValue = "";

			// Committee Date
			$this->Committee_Date->LinkCustomAttributes = "";
			$this->Committee_Date->HrefValue = "";
			$this->Committee_Date->TooltipValue = "";

			// Status
			$this->Status->LinkCustomAttributes = "";
			$this->Status->HrefValue = "";
			$this->Status->TooltipValue = "";
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
		$Breadcrumb->Add("list", $this->TableVar, "scholarshipslist.php", $this->TableVar, TRUE);
		$PageId = "delete";
		$Breadcrumb->Add("delete", $PageId, ew_CurrentUrl());
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'scholarships';
	  $usr = CurrentUserName();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (delete page)
	function WriteAuditTrailOnDelete(&$rs) {
		if (!$this->AuditTrailOnDelete) return;
		$table = 'scholarships';

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
if (!isset($scholarships_delete)) $scholarships_delete = new cscholarships_delete();

// Page init
$scholarships_delete->Page_Init();

// Page main
$scholarships_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$scholarships_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var scholarships_delete = new ew_Page("scholarships_delete");
scholarships_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = scholarships_delete.PageID; // For backward compatibility

// Form object
var fscholarshipsdelete = new ew_Form("fscholarshipsdelete");

// Form_CustomValidate event
fscholarshipsdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fscholarshipsdelete.ValidateRequired = true;
<?php } else { ?>
fscholarshipsdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fscholarshipsdelete.Lists["x_College"] = {"LinkField":"x_CollegeID","Ajax":null,"AutoFill":false,"DisplayFields":["x_College_Name_AR","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fscholarshipsdelete.Lists["x_Department"] = {"LinkField":"x_DID","Ajax":null,"AutoFill":false,"DisplayFields":["x_DepartmentName2DAR","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($scholarships_delete->Recordset = $scholarships_delete->LoadRecordset())
	$scholarships_deleteTotalRecs = $scholarships_delete->Recordset->RecordCount(); // Get record count
if ($scholarships_deleteTotalRecs <= 0) { // No record found, exit
	if ($scholarships_delete->Recordset)
		$scholarships_delete->Recordset->Close();
	$scholarships_delete->Page_Terminate("scholarshipslist.php"); // Return to list
}
?>
<?php $Breadcrumb->Render(); ?>
<?php $scholarships_delete->ShowPageHeader(); ?>
<?php
$scholarships_delete->ShowMessage();
?>
<form name="fscholarshipsdelete" id="fscholarshipsdelete" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="scholarships">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($scholarships_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<table class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_scholarshipsdelete" class="ewTable ewTableSeparate">
<?php echo $scholarships->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($scholarships->English_Name->Visible) { // English Name ?>
		<td><span id="elh_scholarships_English_Name" class="scholarships_English_Name"><?php echo $scholarships->English_Name->FldCaption() ?></span></td>
<?php } ?>
<?php if ($scholarships->Arabic_Name->Visible) { // Arabic Name ?>
		<td><span id="elh_scholarships_Arabic_Name" class="scholarships_Arabic_Name"><?php echo $scholarships->Arabic_Name->FldCaption() ?></span></td>
<?php } ?>
<?php if ($scholarships->College->Visible) { // College ?>
		<td><span id="elh_scholarships_College" class="scholarships_College"><?php echo $scholarships->College->FldCaption() ?></span></td>
<?php } ?>
<?php if ($scholarships->Department->Visible) { // Department ?>
		<td><span id="elh_scholarships_Department" class="scholarships_Department"><?php echo $scholarships->Department->FldCaption() ?></span></td>
<?php } ?>
<?php if ($scholarships->Major->Visible) { // Major ?>
		<td><span id="elh_scholarships_Major" class="scholarships_Major"><?php echo $scholarships->Major->FldCaption() ?></span></td>
<?php } ?>
<?php if ($scholarships->Acceptance_University->Visible) { // Acceptance University ?>
		<td><span id="elh_scholarships_Acceptance_University" class="scholarships_Acceptance_University"><?php echo $scholarships->Acceptance_University->FldCaption() ?></span></td>
<?php } ?>
<?php if ($scholarships->Program_Degree->Visible) { // Program Degree ?>
		<td><span id="elh_scholarships_Program_Degree" class="scholarships_Program_Degree"><?php echo $scholarships->Program_Degree->FldCaption() ?></span></td>
<?php } ?>
<?php if ($scholarships->Committee_Date->Visible) { // Committee Date ?>
		<td><span id="elh_scholarships_Committee_Date" class="scholarships_Committee_Date"><?php echo $scholarships->Committee_Date->FldCaption() ?></span></td>
<?php } ?>
<?php if ($scholarships->Status->Visible) { // Status ?>
		<td><span id="elh_scholarships_Status" class="scholarships_Status"><?php echo $scholarships->Status->FldCaption() ?></span></td>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$scholarships_delete->RecCnt = 0;
$i = 0;
while (!$scholarships_delete->Recordset->EOF) {
	$scholarships_delete->RecCnt++;
	$scholarships_delete->RowCnt++;

	// Set row properties
	$scholarships->ResetAttrs();
	$scholarships->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$scholarships_delete->LoadRowValues($scholarships_delete->Recordset);

	// Render row
	$scholarships_delete->RenderRow();
?>
	<tr<?php echo $scholarships->RowAttributes() ?>>
<?php if ($scholarships->English_Name->Visible) { // English Name ?>
		<td<?php echo $scholarships->English_Name->CellAttributes() ?>>
<span id="el<?php echo $scholarships_delete->RowCnt ?>_scholarships_English_Name" class="control-group scholarships_English_Name">
<span<?php echo $scholarships->English_Name->ViewAttributes() ?>>
<?php echo $scholarships->English_Name->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($scholarships->Arabic_Name->Visible) { // Arabic Name ?>
		<td<?php echo $scholarships->Arabic_Name->CellAttributes() ?>>
<span id="el<?php echo $scholarships_delete->RowCnt ?>_scholarships_Arabic_Name" class="control-group scholarships_Arabic_Name">
<span<?php echo $scholarships->Arabic_Name->ViewAttributes() ?>>
<?php echo $scholarships->Arabic_Name->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($scholarships->College->Visible) { // College ?>
		<td<?php echo $scholarships->College->CellAttributes() ?>>
<span id="el<?php echo $scholarships_delete->RowCnt ?>_scholarships_College" class="control-group scholarships_College">
<span<?php echo $scholarships->College->ViewAttributes() ?>>
<?php echo $scholarships->College->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($scholarships->Department->Visible) { // Department ?>
		<td<?php echo $scholarships->Department->CellAttributes() ?>>
<span id="el<?php echo $scholarships_delete->RowCnt ?>_scholarships_Department" class="control-group scholarships_Department">
<span<?php echo $scholarships->Department->ViewAttributes() ?>>
<?php echo $scholarships->Department->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($scholarships->Major->Visible) { // Major ?>
		<td<?php echo $scholarships->Major->CellAttributes() ?>>
<span id="el<?php echo $scholarships_delete->RowCnt ?>_scholarships_Major" class="control-group scholarships_Major">
<span<?php echo $scholarships->Major->ViewAttributes() ?>>
<?php echo $scholarships->Major->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($scholarships->Acceptance_University->Visible) { // Acceptance University ?>
		<td<?php echo $scholarships->Acceptance_University->CellAttributes() ?>>
<span id="el<?php echo $scholarships_delete->RowCnt ?>_scholarships_Acceptance_University" class="control-group scholarships_Acceptance_University">
<span<?php echo $scholarships->Acceptance_University->ViewAttributes() ?>>
<?php echo $scholarships->Acceptance_University->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($scholarships->Program_Degree->Visible) { // Program Degree ?>
		<td<?php echo $scholarships->Program_Degree->CellAttributes() ?>>
<span id="el<?php echo $scholarships_delete->RowCnt ?>_scholarships_Program_Degree" class="control-group scholarships_Program_Degree">
<span<?php echo $scholarships->Program_Degree->ViewAttributes() ?>>
<?php echo $scholarships->Program_Degree->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($scholarships->Committee_Date->Visible) { // Committee Date ?>
		<td<?php echo $scholarships->Committee_Date->CellAttributes() ?>>
<span id="el<?php echo $scholarships_delete->RowCnt ?>_scholarships_Committee_Date" class="control-group scholarships_Committee_Date">
<span<?php echo $scholarships->Committee_Date->ViewAttributes() ?>>
<?php echo $scholarships->Committee_Date->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($scholarships->Status->Visible) { // Status ?>
		<td<?php echo $scholarships->Status->CellAttributes() ?>>
<span id="el<?php echo $scholarships_delete->RowCnt ?>_scholarships_Status" class="control-group scholarships_Status">
<span<?php echo $scholarships->Status->ViewAttributes() ?>>
<?php echo $scholarships->Status->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$scholarships_delete->Recordset->MoveNext();
}
$scholarships_delete->Recordset->Close();
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
fscholarshipsdelete.Init();
</script>
<?php
$scholarships_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$scholarships_delete->Page_Terminate();
?>
