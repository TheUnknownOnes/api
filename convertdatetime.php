<?php
/*
  Developed by TheUnkownOnes.net
  for more information look at TheUnknownOnes.net
*/

define('HEADER_CONTENT_TYPE', 'Content-Type: application/json');

define('RESULT_OK', 'ok');
define('RESULT_ERROR', 'error');

define('HTTP_STATUS_ERROR', 400);

define('PARAM_DT_FROM', 'from_datetime');
define('PARAM_TZ_FROM', 'from_timezone');
define('PARAM_TZ_TO', 'to_timezone');
define('PARAM_FMT_FROM', 'from_format');
define('PARAM_FMT_TO', 'to_format');
define('PARAM_MOD', 'modify');

define('RESULT_FIELD_RESULT', 'result');
define('RESULT_FIELD_MESSAGE', 'message');
define('RESULT_GROUP_FROM', 'from');
define('RESULT_GROUP_TO', 'to');
define('RESULT_FIELD_DT', 'datetime');
define('RESULT_FIELD_DT_MOD', 'datetime_modified');

define('DEFAULT_FORMAT', 'Y.m.d H:i:s (T)');

header(HEADER_CONTENT_TYPE);

$_REQUEST = array_change_key_case($_REQUEST, CASE_LOWER);

function exitError($result) {
  http_response_code(HTTP_STATUS_ERROR);
  echo json_encode($result);
  exit;
}

$result[RESULT_FIELD_RESULT] = RESULT_OK;

if (! key_exists(PARAM_DT_FROM, $_REQUEST)) {
  $result[RESULT_FIELD_RESULT] = RESULT_ERROR;
  $result[RESULT_FIELD_MESSAGE] = sprintf("No parameter '%s' supplied.", PARAM_DT_FROM);
  exitError($result);
}

if (! key_exists(PARAM_TZ_FROM, $_REQUEST)) {
  $result[RESULT_FIELD_RESULT] = RESULT_ERROR;
  $result[RESULT_FIELD_MESSAGE] = sprintf("No parameter '%s' supplied.", PARAM_TZ_FROM);
  exitError($result);
}

try {
  $tzFrom = new DateTimeZone($_REQUEST[PARAM_TZ_FROM]);
}
catch(Exception $E) {
  $result[RESULT_FIELD_RESULT] = RESULT_ERROR;
  $result[RESULT_FIELD_MESSAGE] = sprintf("Can not parse timezone '%s'.", $_REQUEST[PARAM_TZ_FROM]);  
  exitError($result);
} 

try {
  $dtFrom = new DateTime($_REQUEST[PARAM_DT_FROM], $tzFrom);
}
catch(Exception $E) {
  $result[RESULT_FIELD_RESULT] = RESULT_ERROR;
  $result[RESULT_FIELD_MESSAGE] = sprintf("Can not parse datetime '%s'.", $_REQUEST[PARAM_DT_FROM]);  
  exitError($result);
} 

if (! key_exists(PARAM_TZ_TO, $_REQUEST)) {
  $result[RESULT_FIELD_RESULT] = RESULT_ERROR;
  $result[RESULT_FIELD_MESSAGE] = sprintf("No parameter '%s' supplied.", PARAM_TZ_TO);
  exitError($result);
}

try {
  $tzTo = new DateTimeZone($_REQUEST[PARAM_TZ_TO]);
}
catch(Exception $E) {
  $result[RESULT_FIELD_RESULT] = RESULT_ERROR;
  $result[RESULT_FIELD_MESSAGE] = sprintf("Can not parse timezone '%s'.", $_REQUEST[PARAM_TZ_TO]);  
  exitError($result);
} 

$dtTo = clone $dtFrom;
$dtTo->setTimezone($tzTo);

if (key_exists(PARAM_FMT_FROM, $_REQUEST))
  $fmtFrom = $_REQUEST[PARAM_FMT_FROM];
else
  $fmtFrom = DEFAULT_FORMAT;
  
if (key_exists(PARAM_FMT_TO, $_REQUEST))
  $fmtTo = $_REQUEST[PARAM_FMT_TO];
else
  $fmtTo = DEFAULT_FORMAT;

$result[RESULT_GROUP_FROM][RESULT_FIELD_DT] = $dtFrom->format($fmtFrom);
$result[RESULT_GROUP_TO][RESULT_FIELD_DT] = $dtTo->format($fmtTo);


//https://www.php.net/manual/en/datetime.formats.relative.php
if (key_exists(PARAM_MOD, $_REQUEST)) {
  if (($dtFrom->modify($_REQUEST[PARAM_MOD]) === false) | ($dtTo->modify($_REQUEST[PARAM_MOD]) === false)) {
    $result[RESULT_FIELD_RESULT] = RESULT_ERROR;
    $result[RESULT_FIELD_MESSAGE] = sprintf("Can not apply modifier '%s'.", $_REQUEST[PARAM_MOD]);  
    exitError($result);
  }

  $result[RESULT_GROUP_FROM][RESULT_FIELD_DT_MOD] = $dtFrom->format($fmtFrom);
  $result[RESULT_GROUP_TO][RESULT_FIELD_DT_MOD] = $dtTo->format($fmtTo);
}

echo json_encode($result);
?>