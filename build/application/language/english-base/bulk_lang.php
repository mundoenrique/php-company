<?php
defined('BASEPATH') or exit('No direct script access allowed');

$lang['BULK_TITLE'] = 'Load bulk';
$lang['BULK_TITLE_PAGE'] = 'Bulk loading';
$lang['BULK_NEW'] = 'New bulks';
$lang['BULK_BRANCH_OFFICE'] = 'Branch office';
$lang['BULK_GET_BRANCHOFFICE'] = '';
$lang['BULK_AUTHORIZE'] = 'Bulk authorization';
$lang['BULK_SELECT_BRANCH_OFFICE'] = 'Select a branch';
$lang['BULK_SELECT_BULK_TYPE'] = 'Select a bulk type';
$lang['BULK_SELECT_BULK_FILE'] = 'Click here to select the bulk file.';
$lang['BULK_PENDING'] = 'Pending bulks';
$lang['BULK_PENDING_SIGN'] = 'Bulks pending to be signed';
$lang['BULK_PENDING_AUTH'] = 'Bulks pending to authorize';
$lang['BULK_ENTERPRISE_NAME'] = 'Company name';
$lang['BULK_PROCESS_BY_BULK'] = 'Process by bulk';
$lang['BULK_PROCESS_TYPE_BULK'] = 'Process by bulk type';
$lang['BULK_VALIDATING'] = 'Validating';
$lang['BULK_VALID'] = 'Valid';
$lang['BULK_NO_VALID'] = 'Not valid';
$lang['BULK_VALIDATING_RECORDS'] = 'The records are being validated.';
$lang['BULK_ALL_RECORDS'] = 'All records will be processed.';
$lang['BULK_SOME_RECORDS'] = 'There are records that will not be processed.';
$lang['BULK_NO_RECORDS'] = 'No record will be processed.';
$lang['BULK_NO_RENDER'] = 'It was not possible to obtain the pending bulks.';
$lang['BULK_SUCCESS'] = 'Bulk loaded successfully. The validation of the records is in progress.';
$lang['BULK_NO_LOAD'] = 'The bulk could not be loaded, please try again.';
$lang['BULK_FILE_NO_MOVE'] = 'It was not possible to move the file to the server, please try again.';
$lang['BULK_INCOMPATIBLE_FILE'] = 'The file type is not supported by the product.';
$lang['BULK_DELETE_SUCCESS'] = 'The bulk <strong>%s</strong> dated: <strong>%s</strong> was successfully removed';
$lang['BULK_CONFIRM_TITLE'] = 'Bulk confirmation';
$lang['BULK_CONFIRM_FAIL_COST'] = 'Unable to get bulk (%s) costs.';
$lang['BULK_CONFIRM_SUCCESS'] = 'Bulk No.: <strong>%s</strong> was confirmed successfully.';
$lang['BULK_CONFIRM_NO_DEAIL'] = 'The bulk detail was not found. Delete it and reload it.';
$lang['BULK_CONFIRM_FAIL'] = 'It was not possible to confirm the bulk, please try again later.';
$lang['BULK_CONFIRM_FAIL_DULPICATE'] = 'It was not possible to confirm the batch, it already exists in the bank records, please delete it and reload it';
$lang['BULK_CONFIRM_EXCEED_LIMIT'] = 'The amount exceeds the payment limit for national account.';
$lang['BULK_CONFIRM_DUPLICATE'] = 'It was not possible to confirm the batch, there are confirmed records in another bulk.';
$lang['BULK_CONFIRM_EXCEEDED_LIMIT'] = 'The bulk amount exceeds the allowed limit.';
$lang['BULK_CONFIRM_FAIL_BANK_RESPONSE'] = 'The bulk could not be confirmed, no authorization was obtained.';
$lang['BULK_CONFIRM_INACTIVE_ACCOUNT'] = 'The bulk could not be confirmed, please contact your account executive.';
$lang['BULK_AUTH_SUCCESS'] = '%s, your authorization was included successfully.';
$lang['BULK_CONFIRM'] = 'Confirmation';
$lang['BULK_SIGN_TITLE'] = 'Bulk signature';
$lang['BULK_AUTH_TITLE'] = 'Authorize bulk';
$lang['BULK_DELETE_TITLE'] = 'Delete bulk';
$lang['BULK_DISASS_TITLE'] = 'Detach bulk';
$lang['BULK_TOTAL_RECORDS'] = 'Number of records';
$lang['BULK_AMOUNT'] = 'Total amount';
$lang['BULK_OBSERVATIONS'] = 'Observations';
$lang['BULK_SEE'] = 'See bulk';
$lang['BULK_DETAIL'] = 'Bulk detail';
$lang['BULK_CONFIRM_MSG'] = '"The total value corresponds to the reissue commission of your pay + prepaid cards according to the conditions negotiated with the Bank, the cost may not apply to all your cards"';
$lang['BULK_DETAIL_FAIL_COST'] = 'At this time the cost inquiry is not available, please try again later.';
$lang['BULK_DELETE'] = 'Please enter the password to delete the bulk';
$lang['BULK_DISASS'] = 'Please enter the password to disassociate the bulk';
$lang['BULK_DELETE_NAME'] = 'Please enter the password to delete the bulk';
$lang['BULK_SELECT'] = 'Select at least one bulk';
$lang['BULK_WITAOUT_TAX'] = 'The company does not have a tax associated with the product.';
$lang['BULK_CALCULATE_ORDER'] = 'Calculate Service Order';
$lang['BULK_SIGNEDS'] = 'Successfully signed bulks';
$lang['BULK_SIGNED'] = 'Successfully signed bulk';
$lang['BULK_DELETED'] = 'Bulk removed successfully.';
$lang['BULK_DISASSOCIATED'] = 'Bulk disassociated successfully.';
$lang['BULK_NOT_DELETED'] = 'Could not delete bulk: %d, please try again.';
$lang['BULK_NOT_DISASS'] = 'Could not detach bulk: %d, please try again.';
$lang['BULK_UNNA_REQUEST'] = 'Request';
$lang['BULK_UNNA_EXPIRED_DATE'] = 'Expiration date';
$lang['BULK_UNNA_MAX_CARDS'] = 'Number of cards';
$lang['BULK_UNNA_STARTING_LINE1'] = 'Starting line 1';
$lang['BULK_UNNA_STARTING_LINE2'] = 'Starting line 2';
$lang['BULK_UNNA_ACCOUNT'] = 'Unnamed cards';
$lang['BULK_UNNA_PROCESS_OK'] = 'The bulk was successfully processed.';
$lang['BULK_UNNA_PROCESS'] = 'Processed bulks';
$lang['BULK_UNNA_REQ_NONCARDS'] = 'All cards in the bulk have been affiliated.';
$lang['BULK_SO_CREATE_TITLE'] = 'Generate service order';
$lang['BULK_SO_CREATE_FAILED'] = 'It was not possible to generate the service order, please try again.';
$lang['BULK_WITHOUT_AUTH_PENDING'] = 'The transaction has already completed its authorization cycle.';
$lang['BULK_DAILY_AMOUNT_EXCEEDED'] = 'You have exceeded the maximum daily amount.';
$lang['BULK_MONTHLY_AMOUNT_EXCEEDED'] = 'You have exceeded the maximum monthly amount.';
$lang['BULK_AMOUNT_EXCEEDED'] = 'You have exceeded the maximum amount per transaction.';
$lang['BULK_FILE_NOT_EXIST_ICBS'] = 'Transaction not found in ICBS portal.';
$lang['BULK_AUTH_ALREADY_PERFORMED_BY_USER'] = 'User already made approval.';
$lang['BULK_EXPIRED_TIME'] = 'The period for the bulk to be approved has expired.';
$lang['BULK_IMPORTANT'] = 'Important!';
$lang['BULK_REQUEST_500_CARDS'] = 'If your request is for issuance and exceeds 500 cards, it will be processed 20 minutes after the next card creation time.';
$lang['BULK_CREATION_SCHEDULE'] = 'The hours for creating cards are as follows:';
$lang['BULK_FILE_ROW_LIMIT_EXCEEDED'] = 'The content of the file exceeds the limit of allowed rows. Must contain a maximum of %s records';
$lang["BULK_LIMIT_EXCEEDED_DAILY_EMISSIONS"] = 'Your batch exceeds the daily emission limit, there are only %s left and you are loading %s.';
$lang["BULK_PROCESS_FUND_REGISTRY"] = 'Balance funding cannot be executed at this time. Please try again later.';
$lang["BULK_PROCESS_RETURN_REGISTRY"] = 'The balance refund cannot be executed at this time. Please try again later.';
