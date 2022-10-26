<?php
if(!isset($_GET["act"])){
	$_GET["act"] = "control";//default page
}

// check user status
try {
	$dbh = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);
	$sth = $dbh->prepare(
		DB_IS_MSSQL  ? "USE " . DB_NAME : (
		DB_IS_MYSQL  ? "USE " . DB_NAME : (
		DB_IS_ORACLE ? "ALTER SESSION SET CURRENT_SCHEMA = " . DB_NAME : null)));
	$sth->execute();

	$sth = $dbh->prepare(
		DB_IS_MSSQL  ? "SELECT * FROM account WHERE UID = ? AND Status = 1 AND (ExpirationDate IS NULL OR ExpirationDate > getdate())" : (
		DB_IS_MYSQL  ? "SELECT * FROM account WHERE UID = ? AND Status = 1 AND (ExpirationDate IS NULL OR ExpirationDate > CURRENT_TIMESTAMP)" : (
		DB_IS_ORACLE ? "SELECT * FROM account WHERE \"UID\" = ? AND Status = 1 AND (ExpirationDate IS NULL OR ExpirationDate > LOCALTIMESTAMP)" : null)));
	$sth->execute(array($_SESSION["account_uid"]));

	$row = $sth->fetch(PDO::FETCH_ASSOC);
	$sth->closeCursor();
	$dbh = null;
}
catch(PDOException $e) {
	echo "Database exception!\n" . $e->getMessage();
	exit;
}

if($row == null){// the user already remove
	session_destroy();
	header("Location: ./");
	exit;
}

if(file_exists($_GET["act"] . ".php")){
    require_once($_GET["act"] . ".php");
}
else{
	header("HTTP/1.0 500 Internal Server Error");
	echo "Invalid action name.";
	exit;
}

function active_check($active){
	return ($active == $_GET["act"] ? " active" : "");
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<title><?=WEB_NAME?></title>
<link rel="stylesheet" type="text/css" href="./css/general.css">
<link rel="stylesheet" type="text/css" href="./css/popup.css">
<style type="text/css">
html{
	height:100%;
}

body{
	--default-background: #FDFDFD;
}

body.dark{
	--default-background: #272727;
}

body{
	padding:0;
	margin:0;
	overflow-y: scroll;
	position: relative;
	min-height:100%;
<?php
	if(!isset($dashboard_permission)){
?>
	background-image: url("../image/bg.png");
<?php
	}
?>
	background-repeat:repeat-y;
	background-color: var(--default-background);
}

svg{
	width:18px;
	height:18px;
/*	font-size:0;*/
	fill:#fff;
	vertical-align:top;
}

svg use svg { 
    fill: inherit !important;
}

svg, svg * {
 	/*pointer-events: all;*/
	pointer-events: none;
}

#top-bar{
	position:fixed;
	top:0;
	height:50px;
	width:100%;
	background-color:#035002;
	z-index: 5;
	/* border-bottom: solid 1px #1b1b1b; */
}

.top-bar-logo{
	float:left;
	line-height:50px;
	padding:5px;
	padding-left:25px;
}

.top-bar-text{
	float: left;
	line-height:50px;
	color:#DCDCDC;
	font-weight:bold;
	text-shadow:1px 1px #000;
	font-size:16px;
}

#popup-window-content{
	width:0px;
/*	z-index:1000;*/
/*	position:relative;*/
}

#popup-window-content > div{
	z-index:998;
    position:relative;
}

#popup-window-content > div:last-child{
	z-index:1000;
}

#side-bar{
	width:230px;
	background-color:#eee;
}

#container{
	padding-top:50px;
	display:table;
	width:100%;
}

#container > div{
	display:table-cell;
	vertical-align:top;
}

body #content{
	--default-text: #035002e3;
}

body.dark #content{
	--default-text: #D5D5D5;
}

#content{
	display:table-cell;
	width:100%;
	vertical-align:top;
}

#content > div:not(.popup-background){
	color: var(--default-text);
}

.ics-nav-item {
    background-image: url('../image/ics_top_bar.png');
    background-repeat: no-repeat;
    display: inline-block;
	margin-right:3px;
    width: 18px;
    height: 18px;
}

.nav-group{
	color:#dcdcdc;
	font-size:13px;
	/* margin-top:5px; */
	padding:12px 0 11px 15px;
    font-weight: bold;
	background:linear-gradient(#035002 0px, #fff 600%);
}

.nav-group:first-child{
	margin-top:0px;
}

/*exit*/
.nav-item .ics-nav-item.exit {
    background-position: 0px 0px;
}
.nav-item:hover .ics-nav-item.exit, .nav-item.active .ics-nav-item.exit {
    background-position: -28px 0px;
}

/*setting*/
.nav-item .ics-nav-item.setting {
    background-position: 0px -28px;
}
.nav-item:hover .ics-nav-item.setting, .nav-item.active .ics-nav-item.setting {
    background-position: -28px -28px;
}

/*group*/
.nav-item .ics-nav-item.group {
    background-position: 0px -56px;
}
.nav-item:hover .ics-nav-item.group, .nav-item.active .ics-nav-item.group {
    background-position: -28px -56px;
}

/*chart*/
.nav-item .ics-nav-item.chart {
    background-position: 0px -84px;
}
.nav-item:hover .ics-nav-item.chart, .nav-item.active .ics-nav-item.chart {
    background-position: -28px -84px;
}

/*cloud*/
.nav-item .ics-nav-item.cloud {
    background-position: -0px -112px;
}
.nav-item:hover .ics-nav-item.cloud, .nav-item.active .ics-nav-item.cloud {
    background-position: -28px -112px;
}

.nav-item{
	color:#035002d9;
	/* text-shadow: 0 1px 0 rgba(0, 0, 0, 0.8); */
	cursor: pointer;
    /* border-right: solid 5px transparent; */
}

.nav-item > *{
	vertical-align:middle;
}

.nav-item svg{
	fill:#9d9d9d;
}

.nav-item.top{
	float:right;
	line-height:50px;
	cursor: pointer;
	padding-right:10px;
}

.nav-item.side{
	padding:10px 0px 10px 15px;
	/* border-bottom: solid 1px #1b1b1b; */
	background: linear-gradient(#eee 0px, #eee 100%);
}

.nav-item.active{
	cursor:default;
	border-right-color: #b3b3b3;
}

.nav-item.active, .nav-item:hover{
	color:#fff;
}

.nav-item.active svg, .nav-item:hover svg{
	fill:#fff;
}

.nav-item.side.active, .nav-item.side:hover{
    background:linear-gradient(#a11e01 0px, #fefefe 300%);
}

.title{
	font-size: 22px;
	border-bottom: 1px solid #DBDBDB;
	padding-bottom: 10px;
	margin-bottom: 20px;
}

.dark .title{
	border-bottom: 1px solid #464646;
}

.count-block{
	border: 1px solid #eee;
    border-radius: 6px;
    background: linear-gradient(#a11e01 0px, #fefefe 300%);
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#dd5341', endColorstr='#a23d30', GradientType=0);
    color: #FFF;
    font-weight: bold;
    font-size: 12px;
    padding: 3px 4px;
}

#wait-loader-container{
	position:absolute;
	padding: 1px;
	background-color:rgba(255, 255, 255, 0.9);
	top:50%;
	left:50%;
	margin-top:-50px;
	margin-left:-50px;
	border-radius:10px;
	font-size:0;
}

img {
	width: 13;
	height: 13;
}

.sidebar {
  display: block;
  float: left;
  width: 250px;
  background: #333;
  
}
.content {
  display: block;
  overflow: hidden;
  width: auto;
}
.sidebar-nav {
 
}
.sidebar-nav ul {
  padding: 0;
  margin: 0;
  list-style: none;
}
.sidebar-nav a, .sidebar-nav a:hover, .sidebar-nav a:focus, .sidebar-nav a:active {
  outline: none;
}
.sidebar-nav ul li, .sidebar-nav ul a {
  display: block;
}
.sidebar-nav ul a {
  padding: 10px 20px;
  color: #aaa;
  border-top: 1px solid rgba(0, 0, 0, 0.3);
  box-shadow: 0px 1px 0px rgba(255, 255, 255, 0.05) inset;
  text-shadow: 0px 1px 0px rgba(0, 0, 0, 0.5);
}
.sidebar-nav ul a:hover, .sidebar-nav ul a:focus, .sidebar-nav ul a:active {
  color: #fff;
  text-decoration: none;
}
.sidebar-nav ul ul a {
  padding: 10px 30px;
  background-color: rgba(255, 255, 255, 0.1);
}
.sidebar-nav ul ul a:hover, .sidebar-nav ul ul a:focus, .sidebar-nav ul ul a:active {
  background-color: rgba(255, 255, 255, 0.2);
}
.sidebar-nav-item {
  padding-left: 5px;
}
.sidebar-nav-item-icon {
  padding-right: 5px;
}
#rtlh3 small {
    transform: rotateY(180deg);
    display: inline-block;
}



/* Flot */
.flotTip, .valueTip{
	border-radius: 3px !important;
    border-color: #545454 !important;
	box-shadow: 0px 0px 2px #888888;
	display: none;
	position: absolute;
	background: rgb(255, 255, 255);
	z-index: 1040;
	padding: 0.4em 0.6em;
	font-size: 0.8em;
	border-width: 1px;
	border-style: solid;
	white-space: nowrap;
}

</style>
<script src="./js/jquery.min.js"></script>
<script src="./js/popup.js"></script>
<script src="./js/svg4everybody.min.js"></script>
<script>svg4everybody();</script>
<script language="JavaScript">
function logout(){
	popupConfirmWindow("<?=$lang['MANAGER']['POPUP']['LOGOUT_CONFIRM'];?>", function(){
		location = "./?act=logout";
	});
}

</script>
<?php
$error_message = null;
try {
	ob_start();
	customized_header();
	ob_end_flush();
}
catch (Exception $e) {
	ob_end_clean();
	$error_message = $e->getMessage();
}
?>
</head>
<body>
	<div id="top-bar">
		<div class="top-bar-logo"><img src="./image/logo brand.svg" style="height:20px;"></div>
		<!-- <div class="top-bar-text"><?=WEB_NAME?></div> -->
<?php
	if(!isset($dashboard_permission)){
?>
		<!-- <div class="nav-item top" onClick="logout();"><img src="image/ic_username.png"></img>&nbsp;<span><?=$lang['MANAGER']['LOGOUT'];?></span></div>
		<div class="nav-item top" onClick="location='./?act=settings';"><svg><use xlink:href="image/ics.svg#account_box"></use></svg>&nbsp;<span id="account-username"><?=$row["Nickname"]?></span>(<?=$row["Username"]?>)</div> -->
<?php
	}
?>
		<div style="clear:both;"></div>
	</div>
	<div class="popup-background" id="popup-window-background"></div>
	<div id="container">
		<div>
			<div id="popup-window-content"></div>
		</div>
<?php
	if(!isset($dashboard_permission)){
?>
		<div id="side-bar">
			<div id="side-bar">
				<div class="nav-group"><?=$lang['MANAGER']['REMOTE_ACCESS_SERVICE'];?></div>
				<div class="nav-item side<?=active_check('control')?>" onClick="location='./?act=control';"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cpu" viewBox="0 0 16 16">
  					<path d="M5 0a.5.5 0 0 1 .5.5V2h1V.5a.5.5 0 0 1 1 0V2h1V.5a.5.5 0 0 1 1 0V2h1V.5a.5.5 0 0 1 1 0V2A2.5 2.5 0 0 1 14 4.5h1.5a.5.5 0 0 1 0 1H14v1h1.5a.5.5 0 0 1 0 1H14v1h1.5a.5.5 0 0 1 0 1H14v1h1.5a.5.5 0 0 1 0 1H14a2.5 2.5 0 0 1-2.5 2.5v1.5a.5.5 0 0 1-1 0V14h-1v1.5a.5.5 0 0 1-1 0V14h-1v1.5a.5.5 0 0 1-1 0V14h-1v1.5a.5.5 0 0 1-1 0V14A2.5 2.5 0 0 1 2 11.5H.5a.5.5 0 0 1 0-1H2v-1H.5a.5.5 0 0 1 0-1H2v-1H.5a.5.5 0 0 1 0-1H2v-1H.5a.5.5 0 0 1 0-1H2A2.5 2.5 0 0 1 4.5 2V.5A.5.5 0 0 1 5 0zm-.5 3A1.5 1.5 0 0 0 3 4.5v7A1.5 1.5 0 0 0 4.5 13h7a1.5 1.5 0 0 0 1.5-1.5v-7A1.5 1.5 0 0 0 11.5 3h-7zM5 6.5A1.5 1.5 0 0 1 6.5 5h3A1.5 1.5 0 0 1 11 6.5v3A1.5 1.5 0 0 1 9.5 11h-3A1.5 1.5 0 0 1 5 9.5v-3zM6.5 6a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3z"/>
					</svg>&nbsp;&nbsp;<span><?=$lang['MANAGER']['DEVICE_MAINTENANCE'];?></span></div>

				<div class="nav-group"><?=$lang['MANAGER']['DATA_DISPLAY_AND_ANALYSIS'];?></div>
				<div class="nav-item side<?=active_check('dashboard')?>" onClick="location='./?act=dashboard';"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-seam" viewBox="0 0 16 16">
  					<path d="M8.186 1.113a.5.5 0 0 0-.372 0L1.846 3.5l2.404.961L10.404 2l-2.218-.887zm3.564 1.426L5.596 5 8 5.961 14.154 3.5l-2.404-.961zm3.25 1.7-6.5 2.6v7.922l6.5-2.6V4.24zM7.5 14.762V6.838L1 4.239v7.923l6.5 2.6zM7.443.184a1.5 1.5 0 0 1 1.114 0l7.129 2.852A.5.5 0 0 1 16 3.5v8.662a1 1 0 0 1-.629.928l-7.185 2.874a.5.5 0 0 1-.372 0L.63 13.09a1 1 0 0 1-.63-.928V3.5a.5.5 0 0 1 .314-.464L7.443.184z"/>
					</svg>&nbsp;&nbsp;<span><?=$lang['MANAGER']['DASHBOARD_SERVICE'];?></span></div>
				<div class="nav-item side<?=active_check('realtime_io')?>" onClick="location='./?act=realtime_io';"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard-data" viewBox="0 0 16 16">
  					<path d="M4 11a1 1 0 1 1 2 0v1a1 1 0 1 1-2 0v-1zm6-4a1 1 0 1 1 2 0v5a1 1 0 1 1-2 0V7zM7 9a1 1 0 0 1 2 0v3a1 1 0 1 1-2 0V9z"/>
					<path d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1v-1z"/>
  					<path d="M9.5 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5h3zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3z"/>
					</svg>&nbsp;&nbsp;<span><?=$lang['MANAGER']['REALTIME_IO_DATA'];?></span></div>
				<!-- <div class="nav-item side<?=active_check('realtime_energy')?>" onClick="location='./?act=realtime_energy';"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-lightning-fill" viewBox="0 0 16 16">
  					<path d="M5.52.359A.5.5 0 0 1 6 0h4a.5.5 0 0 1 .474.658L8.694 6H12.5a.5.5 0 0 1 .395.807l-7 9a.5.5 0 0 1-.873-.454L6.823 9.5H3.5a.5.5 0 0 1-.48-.641l2.5-8.5z"/>
					</svg>&nbsp;&nbsp;<span><?=$lang['MANAGER']['REALTIME_POWER_DATA'];?></span></div> -->
				<div class="nav-item side<?=active_check('history_io')?>" onClick="location='./?act=history_io';"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clock-history" viewBox="0 0 16 16">
  					<path d="M8.515 1.019A7 7 0 0 0 8 1V0a8 8 0 0 1 .589.022l-.074.997zm2.004.45a7.003 7.003 0 0 0-.985-.299l.219-.976c.383.086.76.2 1.126.342l-.36.933zm1.37.71a7.01 7.01 0 0 0-.439-.27l.493-.87a8.025 8.025 0 0 1 .979.654l-.615.789a6.996 6.996 0 0 0-.418-.302zm1.834 1.79a6.99 6.99 0 0 0-.653-.796l.724-.69c.27.285.52.59.747.91l-.818.576zm.744 1.352a7.08 7.08 0 0 0-.214-.468l.893-.45a7.976 7.976 0 0 1 .45 1.088l-.95.313a7.023 7.023 0 0 0-.179-.483zm.53 2.507a6.991 6.991 0 0 0-.1-1.025l.985-.17c.067.386.106.778.116 1.17l-1 .025zm-.131 1.538c.033-.17.06-.339.081-.51l.993.123a7.957 7.957 0 0 1-.23 1.155l-.964-.267c.046-.165.086-.332.12-.501zm-.952 2.379c.184-.29.346-.594.486-.908l.914.405c-.16.36-.345.706-.555 1.038l-.845-.535zm-.964 1.205c.122-.122.239-.248.35-.378l.758.653a8.073 8.073 0 0 1-.401.432l-.707-.707z"/>
  					<path d="M8 1a7 7 0 1 0 4.95 11.95l.707.707A8.001 8.001 0 1 1 8 0v1z"/>
  					<path d="M7.5 3a.5.5 0 0 1 .5.5v5.21l3.248 1.856a.5.5 0 0 1-.496.868l-3.5-2A.5.5 0 0 1 7 9V3.5a.5.5 0 0 1 .5-.5z"/>
					</svg>&nbsp;&nbsp;<span><?=$lang['MANAGER']['HISTORICAL_IO_DATA'];?></span></div>
				<!-- <div class="nav-item side<?=active_check('history_energy')?>" onClick="location='./?act=history_energy';"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clock-history" viewBox="0 0 16 16">
  					<path d="M8.515 1.019A7 7 0 0 0 8 1V0a8 8 0 0 1 .589.022l-.074.997zm2.004.45a7.003 7.003 0 0 0-.985-.299l.219-.976c.383.086.76.2 1.126.342l-.36.933zm1.37.71a7.01 7.01 0 0 0-.439-.27l.493-.87a8.025 8.025 0 0 1 .979.654l-.615.789a6.996 6.996 0 0 0-.418-.302zm1.834 1.79a6.99 6.99 0 0 0-.653-.796l.724-.69c.27.285.52.59.747.91l-.818.576zm.744 1.352a7.08 7.08 0 0 0-.214-.468l.893-.45a7.976 7.976 0 0 1 .45 1.088l-.95.313a7.023 7.023 0 0 0-.179-.483zm.53 2.507a6.991 6.991 0 0 0-.1-1.025l.985-.17c.067.386.106.778.116 1.17l-1 .025zm-.131 1.538c.033-.17.06-.339.081-.51l.993.123a7.957 7.957 0 0 1-.23 1.155l-.964-.267c.046-.165.086-.332.12-.501zm-.952 2.379c.184-.29.346-.594.486-.908l.914.405c-.16.36-.345.706-.555 1.038l-.845-.535zm-.964 1.205c.122-.122.239-.248.35-.378l.758.653a8.073 8.073 0 0 1-.401.432l-.707-.707z"/>
  					<path d="M8 1a7 7 0 1 0 4.95 11.95l.707.707A8.001 8.001 0 1 1 8 0v1z"/>
  					<path d="M7.5 3a.5.5 0 0 1 .5.5v5.21l3.248 1.856a.5.5 0 0 1-.496.868l-3.5-2A.5.5 0 0 1 7 9V3.5a.5.5 0 0 1 .5-.5z"/>
					</svg>&nbsp;&nbsp;<span><?=$lang['MANAGER']['HISTORICAL_POWER_DATA'];?></span></div> -->
				<div class="nav-item side<?=active_check('energy_report')?>" onClick="location='./?act=report'"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-newspaper" viewBox="0 0 16 16">
  					<path d="M0 2.5A1.5 1.5 0 0 1 1.5 1h11A1.5 1.5 0 0 1 14 2.5v10.528c0 .3-.05.654-.238.972h.738a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 1 1 0v9a1.5 1.5 0 0 1-1.5 1.5H1.497A1.497 1.497 0 0 1 0 13.5v-11zM12 14c.37 0 .654-.211.853-.441.092-.106.147-.279.147-.531V2.5a.5.5 0 0 0-.5-.5h-11a.5.5 0 0 0-.5.5v11c0 .278.223.5.497.5H12z"/>
  					<path d="M2 3h10v2H2V3zm0 3h4v3H2V6zm0 4h4v1H2v-1zm0 2h4v1H2v-1zm5-6h2v1H7V6zm3 0h2v1h-2V6zM7 8h2v1H7V8zm3 0h2v1h-2V8zm-3 2h2v1H7v-1zm3 0h2v1h-2v-1zm-3 2h2v1H7v-1zm3 0h2v1h-2v-1z"/>
					</svg>&nbsp;&nbsp;<span><?=$lang['MANAGER']['REPORT_SERVICE'];?></span></div>
				<!--<div class="nav-item side<?=active_check('module_report')?>" onClick="location='./?act=module_report';"><svg><use xlink:href="image/ics.svg#assignment"></use></svg>&nbsp;&nbsp;<span>自訂模組報表</span></div>-->
				<!-- <div class="nav-item side<?=active_check('video_event')?>" onClick="location='./?act=video_event'"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-camera-reels-fill" viewBox="0 0 16 16">
  					<path d="M6 3a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
  					<path d="M9 6a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
  					<path d="M9 6h.5a2 2 0 0 1 1.983 1.738l3.11-1.382A1 1 0 0 1 16 7.269v7.462a1 1 0 0 1-1.406.913l-3.111-1.382A2 2 0 0 1 9.5 16H2a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h7z"/>
					</svg>&nbsp;&nbsp;<span><?=$lang['MANAGER']['VIDEO_EVENT_DATA'];?></span></div> -->

				<div class="nav-group"><?=$lang['MANAGER']['GROUPING_SETTING'];?></div>
				<div class="nav-item side<?=active_check('io_group')?>" onClick="location='./?act=io_group';"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-collection" viewBox="0 0 16 16">
  					<path d="M2.5 3.5a.5.5 0 0 1 0-1h11a.5.5 0 0 1 0 1h-11zm2-2a.5.5 0 0 1 0-1h7a.5.5 0 0 1 0 1h-7zM0 13a1.5 1.5 0 0 0 1.5 1.5h13A1.5 1.5 0 0 0 16 13V6a1.5 1.5 0 0 0-1.5-1.5h-13A1.5 1.5 0 0 0 0 6v7zm1.5.5A.5.5 0 0 1 1 13V6a.5.5 0 0 1 .5-.5h13a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-.5.5h-13z"/>
					</svg>&nbsp;&nbsp;<span><?=$lang['MANAGER']['IO_CHANNEL'];?></span></div>
				<div class="nav-item side<?=active_check('group')?>" onClick="location='./?act=group';"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-collection" viewBox="0 0 16 16">
  					<path d="M2.5 3.5a.5.5 0 0 1 0-1h11a.5.5 0 0 1 0 1h-11zm2-2a.5.5 0 0 1 0-1h7a.5.5 0 0 1 0 1h-7zM0 13a1.5 1.5 0 0 0 1.5 1.5h13A1.5 1.5 0 0 0 16 13V6a1.5 1.5 0 0 0-1.5-1.5h-13A1.5 1.5 0 0 0 0 6v7zm1.5.5A.5.5 0 0 1 1 13V6a.5.5 0 0 1 .5-.5h13a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-.5.5h-13z"/>
					</svg>&nbsp;&nbsp;<span><?=$lang['MANAGER']['POWER_METER_LOOP'];?></span></div>

				<div class="nav-group"><?=$lang['MANAGER']['SYSTEM_INFORMATION_AND_SETTING'];?></div>
				<div class="nav-item side<?=active_check('settings')?>" onClick="location='./?act=settings';"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-gear" viewBox="0 0 16 16">
			    	<path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492zM5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0z"/>
					<path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52l-.094-.319zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115l.094-.319z"/>
					</svg>&nbsp;&nbsp;<span><?=$lang['MANAGER']['ACCOUNT_MAINTENANCE'];?></span></div>
				<div class="nav-item side<?=active_check('db_setting')?>" onClick="location='./?act=db_setting';"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-gear" viewBox="0 0 16 16">
  					<path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492zM5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0z"/>
  					<path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52l-.094-.319zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115l.094-.319z"/>
					</svg>&nbsp;&nbsp;<span><?=$lang['MANAGER']['DATABASE_AND_EVENT_SETTING'];?></span></div>
				<div class="nav-item side<?=active_check('db_notification')?>" onClick="location='./?act=db_notification';"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-list-task" viewBox="0 0 16 16">
  					<path fill-rule="evenodd" d="M2 2.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5V3a.5.5 0 0 0-.5-.5H2zM3 3H2v1h1V3z"/>
  					<path d="M5 3.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zM5.5 7a.5.5 0 0 0 0 1h9a.5.5 0 0 0 0-1h-9zm0 4a.5.5 0 0 0 0 1h9a.5.5 0 0 0 0-1h-9z"/>
  					<path fill-rule="evenodd" d="M1.5 7a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5H2a.5.5 0 0 1-.5-.5V7zM2 7h1v1H2V7zm0 3.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5H2zm1 .5H2v1h1v-1z"/>
					</svg>&nbsp;&nbsp;<span><?=$lang['MANAGER']['EVENT_LIST'];?></span><div id="unread_notification" class="count-block" style="float:right;margin-right:10px; display:none;"></div></div>
				<div class="nav-item side<?=active_check('db_info')?>" onClick="location='./?act=db_info';"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-list-task" viewBox="0 0 16 16">
  					<path fill-rule="evenodd" d="M2 2.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5V3a.5.5 0 0 0-.5-.5H2zM3 3H2v1h1V3z"/>
  					<path d="M5 3.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zM5.5 7a.5.5 0 0 0 0 1h9a.5.5 0 0 0 0-1h-9zm0 4a.5.5 0 0 0 0 1h9a.5.5 0 0 0 0-1h-9z"/>
  					<path fill-rule="evenodd" d="M1.5 7a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5H2a.5.5 0 0 1-.5-.5V7zM2 7h1v1H2V7zm0 3.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5H2zm1 .5H2v1h1v-1z"/>
					</svg>&nbsp;&nbsp;<span><?=$lang['MANAGER']['DATABASE_TABLE_LIST'];?></span></div>
				<!-- <div class="nav-item side" onClick="location='./?act=settings';"><svg><use xlink:href="image/ics.svg#account_box"></use></svg>&nbsp;<span id="account-username"><?=$row["Nickname"]?></span>(<?=$row["Username"]?>)</div> -->
				<div class="nav-item side" onClick="logout();"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-power" viewBox="0 0 16 16">
  					<path d="M7.5 1v7h1V1h-1z"/>
					<path d="M3 8.812a4.999 4.999 0 0 1 2.578-4.375l-.485-.874A6 6 0 1 0 11 3.616l-.501.865A5 5 0 1 1 3 8.812z"/>
					</svg>&nbsp;&nbsp;<span><?=$lang['MANAGER']['LOGOUT'];?></span></div>
			</div>
		</div>

		
<?php
	}
?>
		<div id="content">
<?php
if($error_message == null){
	try {
		ob_start();
		customized_body();
		ob_end_flush();
	}
	catch (Exception $e) {
		ob_end_clean();
	    echo $e->getMessage();
	}
}
else{
	echo $error_message;
}
?>
		</div>
	</div>

	<div class="popup-background" id="wait-loader" style="z-index:1000;position:fixed;">
		<div  id="wait-loader-container">
			<img src="./image/loader2.gif">
		</div>
	</div>

	<div class="popup-background" id="popup-background" style="z-index:1000;">
		<div class="popup-wrapper" id="popup-wrapper">
			<div class="popup-container">
				<!--<div class="popup-title">Dialog</div>-->
				<div class="popup-content">
					<div class="popup-icon-wrapper"><span class="ics-popup warning" id="popup-icon"></span></div>
					<div id="popup-message"></div>
				</div>
				<div class="popup-footer">
					<input style="background-image: linear-gradient(#035002 0px, #fefefe 300%);" type="button" value="<?=$lang['OK'];?>" onClick="popupOK();"><span id="popup-cancel-button">&nbsp;&nbsp;<input type="button" class="gray" value="<?=$lang['CANCEL'];?>" onClick="popupCancel();"></span>
				</div>
			</div>
		</div>
	</div>

	<div class="flotTip"></div>
</body>
<?php
	if(!isset($dashboard_permission)){
?>
<script language="JavaScript">
notificatin_unread();
function notificatin_unread(){
	$.ajax({
		url: "db_notification_ajax.php?act=notificatin_unread",
		type: "POST",
		data: {},
		async: false,
		success: function(data, textStatus, jqXHR){
			var $xmlElement = $(data).find("notification > list");
			var unread_count = $($xmlElement[0]).attr("unread_count");
			if(unread_count != 0){
				$("#unread_notification").text(unread_count);
				$("#unread_notification").show();
			}
			else{
				$("#unread_notification").hide();
			}
			
		},
		error: function(jqXHR, textStatus, errorThrown){
			if(jqXHR.status === 0){ return; }// include timeout

			alert(jqXHR.responseText);
		}
	});
}
</script>
<?php
	}
?>
</html>

