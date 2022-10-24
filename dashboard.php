<?php
function customized_header(){
	global $lang, $dashboard_permission;
	$token = isset($_GET["token"]) ? "&token=" . $_GET["token"] : "";
?>
<link rel="stylesheet" href="./css/tip.css">
<link rel="stylesheet" href="./css/gridstack.css" />
<link rel="stylesheet" href="./css/tinymce.css" />
<link rel="stylesheet" href="./css/checkbox.css" />
<link rel="stylesheet" href="./css/overlay.css" />
<link rel="stylesheet" href="./css/widget.button.css" />
<link rel="stylesheet" href="./css/widget.camera.css" />
<link rel="stylesheet" href="./css/widget.value-table.css" />
<link rel="stylesheet" href="./css/widget.countdown_timer.css" />
<link rel="stylesheet" href="./css/widget.map.css" />
<link rel="stylesheet" href="./css/widget.slider.css" />
<link rel="stylesheet" href="./css/picker.css" />
<link rel="stylesheet" href="./js/leaflet/leaflet.css" />
<link rel="stylesheet" href="./js/leaflet/geosearch/l.geosearch.css" />
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<style type="text/css">
body.fullscreen{
	background-image: none;
}

body.fullscreen #side-bar-container{
	display:none;
}

body #content{
	--widget-text: #444444;
	--widget-background: #FFFFFF;
	--widget-border: #CCCCCC;
	--widget-border-hover: #AAAAAA;
	--widget-resize-icon: url("data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHN0eWxlPSJiYWNrZ3JvdW5kLWNvbG9yOiNmZmZmZmYwMCIgd2lkdGg9IjYiIGhlaWdodD0iNiI+PHBhdGggZD0iTTYgNkgwVjQuMmg0LjJWMEg2djZ6IiBvcGFjaXR5PSIuMzAyIi8+PC9zdmc+");
	--widget-setting-border: #888888;

	--widget-setting-icon: #707070;
	--widget-setting-icon-hover: #2A2A2A;

	--widget-setting-text: #606060;
	--widget-setting-text-hover: #FFFFFF;
	--widget-setting-background: #FFFFFF;
	--widget-setting-background-hover: #555555;
	--widget-loader-background: rgba(255, 255, 255, 0.8);
	--widget-loader-icon: url('../image/ajax-loader.gif');

	--add-widget-button-border: #EEEEEE;
	--add-widget-button-border-hover: #CCCCCC;
	--add-widget-button-border-active: #AAAAAA;
}

body.dark #content {
	--widget-text: #CCCCCC;
	--widget-background: #252525;
	--widget-border: #555555;
	--widget-border-hover: #777777;
	--widget-resize-icon: url("data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHN0eWxlPSJiYWNrZ3JvdW5kLWNvbG9yOiNmZmZmZmYwMCIgd2lkdGg9IjYiIGhlaWdodD0iNiI+PHBhdGggZD0iTTYgNkgwVjQuMmg0LjJWMEg2djZ6IiBmaWxsPSIjZmZmZmZmIiBvcGFjaXR5PSIuMiIvPjwvc3ZnPg==");
	--widget-setting-border: #999999;

	--widget-setting-icon: #B1B1B1;
	--widget-setting-icon-hover: #F7F7F7;

	--widget-setting-text: #C4C4C4;
	--widget-setting-text-hover: #252525;
	--widget-setting-background: #252525;
	--widget-setting-background-hover: #CFCFCF;
	--widget-loader-background: rgba(37, 37, 37, 0.8);
	--widget-loader-icon: url('../image/ajax-loader.gif');

	--add-widget-button-border: #464646;
	--add-widget-button-border-hover: #575757;
	--add-widget-button-border-active: #686868;
}

.create-button{
	padding-right:10px !important;
	padding-left:10px !important;
}

/* no exist/expired */
#dashboard-no-exist-container,
#dashboard-expired-container{
	text-align:center;
	color:#707070;
	padding:100px 0;
}

.dashboard-no-exist-title,
.dashboard-expired-title{
	margin-bottom:10px;
	font-weight:bold;
}

.dashboard-no-exist-content,
.dashboard-expired-content{
	font-size:13px;
}

/* header */
#dashboard-control-buttons{
	position:absolute;
	right: 0;
	bottom: 10px;
}

#dashboard-control-buttons button{
	padding:4px;
	font-size:0;
	float:right;
}

#dashboard-control-buttons button svg{
	fill:#FFF;
	width:21px;
	height:21px;
}

.dsahboard-switch-wrapper{
	float:right;
	/*margin-right:5px;*/
	position: relative;
	color: #606060;
}

.dsahboard-switch{
	border: 1px solid #B3B3B3;
	background-color: #fefefe;
	background-image: linear-gradient(to bottom, #fefefe, #f2f2f2);
	border-radius:3px;
	cursor: pointer;
	width:250px;
	line-height:17px;
}

.dsahboard-switch:hover, .dsahboard-switch.active{
	border-color:#888;
}

.dsahboard-switch.active{
	background-color: #fff;
	background-image: none;
}

.dsahboard-switch:active{
	background-color: #eeeeee;
	background-image: linear-gradient(to bottom, #eeeeee, #f0f0f0);
	box-shadow: inset 0px 0px 5px rgba(0, 0, 0, 0.2);
}

.dsahboard-switch-selector{
	position:absolute;
	border: 1px solid #888;
	border-radius:3px;
	/*padding:6px 0;*/
	margin-top:-1px;
	background-color: #fff;
	z-index:10;
	display:flex;
	flex-direction: column;
	width:250px;
	max-height:400px;
	overflow-x:hidden;
	overflow-y:auto;
	z-index:91;
}

.dsahboard-switch-option{
	cursor: pointer;
	position:relative;
}

.dsahboard-switch-option:hover{
	background-color: #555;
	color:#FFF;
}

.dsahboard-switch-option-name{
	padding:6px;
	white-space:nowrap;
}

.dsahboard-switch-option-icon{
	position:absolute;
	right:4px;
	top:50%;
	margin-top:-9px;
}

.dsahboard-switch-option-icon div{
	float:left;
	margin-left:5px;
}

.dsahboard-switch-selector .dsahboard-switch-option .dsahboard-switch-option-icon svg{
	fill:#606060;
}

.dsahboard-switch-selector .dsahboard-switch-option:hover .dsahboard-switch-option-icon svg{
	fill:#FFFFFF;
}

#dashboard-control-buttons button:active svg{
	position:relative;
	top:1px;
	left:1px;
}

/* body */
.grid-stack {
    /*background: lightgoldenrodyellow;*/
	margin: -5px;
	z-index:1;
}

.grid-stack .grid-stack-placeholder > .placeholder-content,
.grid-stack > .grid-stack-item > .grid-stack-item-content {
	right:5px;
	left:5px;
	top:5px;
	bottom:5px;
}

.grid-stack > .grid-stack-item > .grid-stack-item-content {
    color: var(--widget-text);
	border-color: var(--widget-border);
	border-width: 1px;
	border-style: solid;
	background-color: var(--widget-background);
	padding:5px;
	overflow:visible;
}

.grid-stack > .grid-stack-item > .grid-stack-item-content.active,
.grid-stack > .grid-stack-item > .grid-stack-item-content:hover{
    border-color: var(--widget-border-hover);
}

.grid-stack .grid-stack-placeholder div{
	border-color: var(--widget-border-hover) !important;
}

.grid-stack > .grid-stack-item > .ui-resizable-se{
	background-image: var(--widget-resize-icon);
	-webkit-transform: rotate(0deg);
	-moz-transform: rotate(0deg);
	-ms-transform: rotate(0deg);
	-o-transform: rotate(0deg);
	transform: rotate(0deg);
	right: 0px;
}

.grid-stack > .grid-stack-item.ui-draggable-dragging > .grid-stack-item-content,
.grid-stack > .grid-stack-item.ui-resizable-resizing > .grid-stack-item-content {
	box-shadow: none;
}

.widget-title{
	line-height:20px;
    text-align: center;
	display:block;
	text-overflow: ellipsis;
    white-space: nowrap;
    overflow: hidden;
}

.widget-content{
	position: relative;
/*	color:#444;*/
	height: calc(100% - 20px);
	width: 100%;
/*
	background-color:#ccc;
*/
	overflow:hidden;
}

.widget-loader{
	position: absolute;
	top: 0px;
	bottom: 0px;
	left: 0px;
	right: 0px;
	background-color: var(--widget-loader-background);
	background-image: var(--widget-loader-icon);
	background-repeat: no-repeat;
	background-position: center;
    z-index: 1;
}

.item-setting{
	position:absolute;
	top:-1px;
	left:-1px;
	display:none;
	z-index: 1;
}

.grid-stack-item-content.active .item-setting,
.grid-stack-item-content:hover .item-setting{
	display:block;
}

.item-setting-icon{
	position:absolute;
	top:0;
	left:0;
	padding:5px;
	font-size:0;
	cursor: pointer;
	border: 1px solid transparent;
}

.grid-stack-item-content.active .item-setting-icon{
	background-color: var(--widget-setting-background);
	border-color: var(--widget-setting-border);
	border-bottom-width:0px;
}

.grid-stack-item-content:hover .item-setting-icon svg{
	fill: var(--widget-setting-icon);
}

.grid-stack-item-content:hover .item-setting-icon:hover svg,
.grid-stack-item-content.active .item-setting-icon svg{
	fill: var(--widget-setting-icon-hover);
}

.item-setting-menu{
	position:relative;
	top:28px;
	left:0;
	border-color: var(--widget-setting-border);
	border-width: 1px;
	border-style: solid;
	background-color: var(--widget-setting-background);
	color: var(--widget-setting-text);
	display:none;
}

.grid-stack-item-content.active .item-setting-menu{
	display:block;
}

.item-setting-menu > div{
	padding:5px 10px 5px 5px;
	cursor: pointer;
}

.item-setting-menu > div svg{
	fill: var(--widget-setting-icon);
}

.item-setting-menu > div:hover{
	padding:5px 10px 5px 5px;
	color: var(--widget-setting-text-hover);
	background-color: var(--widget-setting-background-hover);
}

.item-setting-menu > div *{
	vertical-align: middle;
}

.item-setting-menu > div:hover svg{
	fill: var(--widget-setting-text-hover);
}

/* footer */
#item-add{
	border-color: var(--add-widget-button-border);
	border-width: 4px;
	border-style: dashed;
	border-radius:0px;
	margin-top:20px;
	height:100px;
	position:relative;
	cursor:pointer;
}

#cross {
	width: 50px;
	height: 50px;
	position: relative;
	top:50%;
	left:50%;
	margin: -25px 0 0 -25px;
}

#cross:before, #cross:after {
	content: "";
	position: absolute;
/*	z-index: -1;*/
	background: var(--add-widget-button-border);
}

#cross:before {
	left: 50%;
	width: 20%;
	margin-left: -10%;
	height: 100%;
}

#cross:after {
	top: 50%;
	height: 20%;
	margin-top: -10%;
	width: 100%;
}

#item-add:hover{
	border-color: var(--add-widget-button-border-hover);
}

#item-add:hover #cross:before,
#item-add:hover #cross:after{
	background: var(--add-widget-button-border-hover);
}

#item-add:active{
/*
	position:relative;
	top:1px;
	left:1px;
*/
	border-color: var(--add-widget-button-border-active);
}

#item-add:active #cross:before,
#item-add:active #cross:after{
	background: var(--add-widget-button-border-active);
}

/* window */
.window-dashboard-container{
	text-align:center;
	padding:20px 10px;
	box-sizing: border-box;
}

#window-create-dashboard .table-row{
	display:table-row;
}

#window-create-dashboard .table-cell-title{
	display:table-cell;
	padding-right:15px;
	text-align:right;
}

#window-create-dashboard .table-cell-content{
	display:table-cell;
	text-align:left;
}

#window-create-dashboard .table-cell-hr{
	display:table-cell;
	height:7px;
}

#window-create-dashboard .help-icon{
	display:inline-block;
}

/**/
#menu-container{
	width:150px;
	height:100%;
	border-right:#d6d6d6 1px solid;
	background-color:#FFF;
	box-sizing:border-box;
	position:absolute;
	z-index:1;
}

.menu-item{
	padding:10px 15px;
	background-color:transparent;
	cursor:pointer;
}

.menu-item.active{
	background-color:#ececec;
	cursor:default;
	font-weight:bold;
}

#content-container{
	width:100%;
	height:100%;
	overflow:hidden;
	top:0;
	left:0;
	position:absolute;
	padding-left:150px;
    box-sizing: border-box;
}

#content-container > div{
	height:100%;
	overflow-y:auto;
	position:relative;
}

.content-title{
	font-weight:bolder;
	color:#2a2a2a;
	margin-bottom:10px;
}

/* Channel List*/
#channle-list-container{
	height:500px;
	overflow-x:hidden;
	overflow-y:auto;
	border: 1px solid #AAA;
}

#channle-list-container .channel-text{
	position:relative;
	padding-left:39px;
}

#channle-list-container .icon-container, #channel-icon-dummy{
	position:absolute;
	top:50%;
	margin-top:-12px;
	left:10px;
	height:24px;
	width:24px;
	/*background-color:red;*/
	background-size: contain;
	background-repeat: no-repeat;
	background-position: center center;
/*	background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAACXBIWXMAAAsTAAALEwEAmpwYAAAKTWlDQ1BQaG90b3Nob3AgSUNDIHByb2ZpbGUAAHjanVN3WJP3Fj7f92UPVkLY8LGXbIEAIiOsCMgQWaIQkgBhhBASQMWFiApWFBURnEhVxILVCkidiOKgKLhnQYqIWotVXDjuH9yntX167+3t+9f7vOec5/zOec8PgBESJpHmomoAOVKFPDrYH49PSMTJvYACFUjgBCAQ5svCZwXFAADwA3l4fnSwP/wBr28AAgBw1S4kEsfh/4O6UCZXACCRAOAiEucLAZBSAMguVMgUAMgYALBTs2QKAJQAAGx5fEIiAKoNAOz0ST4FANipk9wXANiiHKkIAI0BAJkoRyQCQLsAYFWBUiwCwMIAoKxAIi4EwK4BgFm2MkcCgL0FAHaOWJAPQGAAgJlCLMwAIDgCAEMeE80DIEwDoDDSv+CpX3CFuEgBAMDLlc2XS9IzFLiV0Bp38vDg4iHiwmyxQmEXKRBmCeQinJebIxNI5wNMzgwAABr50cH+OD+Q5+bk4eZm52zv9MWi/mvwbyI+IfHf/ryMAgQAEE7P79pf5eXWA3DHAbB1v2upWwDaVgBo3/ldM9sJoFoK0Hr5i3k4/EAenqFQyDwdHAoLC+0lYqG9MOOLPv8z4W/gi372/EAe/tt68ABxmkCZrcCjg/1xYW52rlKO58sEQjFu9+cj/seFf/2OKdHiNLFcLBWK8ViJuFAiTcd5uVKRRCHJleIS6X8y8R+W/QmTdw0ArIZPwE62B7XLbMB+7gECiw5Y0nYAQH7zLYwaC5EAEGc0Mnn3AACTv/mPQCsBAM2XpOMAALzoGFyolBdMxggAAESggSqwQQcMwRSswA6cwR28wBcCYQZEQAwkwDwQQgbkgBwKoRiWQRlUwDrYBLWwAxqgEZrhELTBMTgN5+ASXIHrcBcGYBiewhi8hgkEQcgIE2EhOogRYo7YIs4IF5mOBCJhSDSSgKQg6YgUUSLFyHKkAqlCapFdSCPyLXIUOY1cQPqQ28ggMor8irxHMZSBslED1AJ1QLmoHxqKxqBz0XQ0D12AlqJr0Rq0Hj2AtqKn0UvodXQAfYqOY4DRMQ5mjNlhXIyHRWCJWBomxxZj5Vg1Vo81Yx1YN3YVG8CeYe8IJAKLgBPsCF6EEMJsgpCQR1hMWEOoJewjtBK6CFcJg4Qxwicik6hPtCV6EvnEeGI6sZBYRqwm7iEeIZ4lXicOE1+TSCQOyZLkTgohJZAySQtJa0jbSC2kU6Q+0hBpnEwm65Btyd7kCLKArCCXkbeQD5BPkvvJw+S3FDrFiOJMCaIkUqSUEko1ZT/lBKWfMkKZoKpRzame1AiqiDqfWkltoHZQL1OHqRM0dZolzZsWQ8ukLaPV0JppZ2n3aC/pdLoJ3YMeRZfQl9Jr6Afp5+mD9HcMDYYNg8dIYigZaxl7GacYtxkvmUymBdOXmchUMNcyG5lnmA+Yb1VYKvYqfBWRyhKVOpVWlX6V56pUVXNVP9V5qgtUq1UPq15WfaZGVbNQ46kJ1Bar1akdVbupNq7OUndSj1DPUV+jvl/9gvpjDbKGhUaghkijVGO3xhmNIRbGMmXxWELWclYD6yxrmE1iW7L57Ex2Bfsbdi97TFNDc6pmrGaRZp3mcc0BDsax4PA52ZxKziHODc57LQMtPy2x1mqtZq1+rTfaetq+2mLtcu0W7eva73VwnUCdLJ31Om0693UJuja6UbqFutt1z+o+02PreekJ9cr1Dund0Uf1bfSj9Rfq79bv0R83MDQINpAZbDE4Y/DMkGPoa5hpuNHwhOGoEctoupHEaKPRSaMnuCbuh2fjNXgXPmasbxxirDTeZdxrPGFiaTLbpMSkxeS+Kc2Ua5pmutG003TMzMgs3KzYrMnsjjnVnGueYb7ZvNv8jYWlRZzFSos2i8eW2pZ8ywWWTZb3rJhWPlZ5VvVW16xJ1lzrLOtt1ldsUBtXmwybOpvLtqitm63Edptt3xTiFI8p0in1U27aMez87ArsmuwG7Tn2YfYl9m32zx3MHBId1jt0O3xydHXMdmxwvOuk4TTDqcSpw+lXZxtnoXOd8zUXpkuQyxKXdpcXU22niqdun3rLleUa7rrStdP1o5u7m9yt2W3U3cw9xX2r+00umxvJXcM970H08PdY4nHM452nm6fC85DnL152Xlle+70eT7OcJp7WMG3I28Rb4L3Le2A6Pj1l+s7pAz7GPgKfep+Hvqa+It89viN+1n6Zfgf8nvs7+sv9j/i/4XnyFvFOBWABwQHlAb2BGoGzA2sDHwSZBKUHNQWNBbsGLww+FUIMCQ1ZH3KTb8AX8hv5YzPcZyya0RXKCJ0VWhv6MMwmTB7WEY6GzwjfEH5vpvlM6cy2CIjgR2yIuB9pGZkX+X0UKSoyqi7qUbRTdHF09yzWrORZ+2e9jvGPqYy5O9tqtnJ2Z6xqbFJsY+ybuIC4qriBeIf4RfGXEnQTJAntieTE2MQ9ieNzAudsmjOc5JpUlnRjruXcorkX5unOy553PFk1WZB8OIWYEpeyP+WDIEJQLxhP5aduTR0T8oSbhU9FvqKNolGxt7hKPJLmnVaV9jjdO31D+miGT0Z1xjMJT1IreZEZkrkj801WRNberM/ZcdktOZSclJyjUg1plrQr1zC3KLdPZisrkw3keeZtyhuTh8r35CP5c/PbFWyFTNGjtFKuUA4WTC+oK3hbGFt4uEi9SFrUM99m/ur5IwuCFny9kLBQuLCz2Lh4WfHgIr9FuxYji1MXdy4xXVK6ZHhp8NJ9y2jLspb9UOJYUlXyannc8o5Sg9KlpUMrglc0lamUycturvRauWMVYZVkVe9ql9VbVn8qF5VfrHCsqK74sEa45uJXTl/VfPV5bdra3kq3yu3rSOuk626s91m/r0q9akHV0IbwDa0b8Y3lG19tSt50oXpq9Y7NtM3KzQM1YTXtW8y2rNvyoTaj9nqdf13LVv2tq7e+2Sba1r/dd3vzDoMdFTve75TsvLUreFdrvUV99W7S7oLdjxpiG7q/5n7duEd3T8Wej3ulewf2Re/ranRvbNyvv7+yCW1SNo0eSDpw5ZuAb9qb7Zp3tXBaKg7CQeXBJ9+mfHvjUOihzsPcw83fmX+39QjrSHkr0jq/dawto22gPaG97+iMo50dXh1Hvrf/fu8x42N1xzWPV56gnSg98fnkgpPjp2Snnp1OPz3Umdx590z8mWtdUV29Z0PPnj8XdO5Mt1/3yfPe549d8Lxw9CL3Ytslt0utPa49R35w/eFIr1tv62X3y+1XPK509E3rO9Hv03/6asDVc9f41y5dn3m978bsG7duJt0cuCW69fh29u0XdwruTNxdeo94r/y+2v3qB/oP6n+0/rFlwG3g+GDAYM/DWQ/vDgmHnv6U/9OH4dJHzEfVI0YjjY+dHx8bDRq98mTOk+GnsqcTz8p+Vv9563Or59/94vtLz1j82PAL+YvPv655qfNy76uprzrHI8cfvM55PfGm/K3O233vuO+638e9H5ko/ED+UPPR+mPHp9BP9z7nfP78L/eE8/sl0p8zAAAAIGNIUk0AAHolAACAgwAA+f8AAIDpAAB1MAAA6mAAADqYAAAXb5JfxUYAAAD7SURBVHja7JkxDoMwDEVJ1VOUM9A9Us+RuSM3SkZmzlEpezlDeo10gnqxGlRo4vA9mSDB/8qTY4OKMTZzOOc+FwVH3/dqzk+N8BBv4MzduN/aooQOj1DnDog3oKy1ETsAAxtVIWPMko/juOTXrssi7jlNX7UBIRiAgVp7oZS4tNv0S68QgFDdCNEDhR5qv2x9yruAkGiEuN6Dw2kPbKgGICS6CnE47REp2AAhGICBI1Qh7/2Sa62ziEvRAISKRSgXNms1ACEYgIEjtNNrW2j6vYgO/tw69y5MZKIR4rBJGeQ5PLh1+kw64KfgBIREVKF//iPjcEIVKjXeAwAhoVKckVKoSAAAAABJRU5ErkJggg==');*/
/*	background-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgd2lkdGg9IjI0Ij48cGF0aCBkPSJNMCAwaDI0djI0SDB6IiBmaWxsPSJub25lIi8+PHBhdGggZD0iTTMgNXY0aDJWNWg0VjNINWMtMS4xIDAtMiAuOS0yIDJ6bTIgMTBIM3Y0YzAgMS4xLjkgMiAyIDJoNHYtMkg1di00em0xNCA0aC00djJoNGMxLjEgMCAyLS45IDItMnYtNGgtMnY0em0wLTE2aC00djJoNHY0aDJWNWMwLTEuMS0uOS0yLTItMnoiIGZpbGw9IiNBQUFBQUEiLz48L3N2Zz4=');*/
	background-image: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTM0IiBoZWlnaHQ9IjEzNCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0ibTExOS40MjM2NCw5Ni45NTMwOGwxNC44ODExNywwbC0wLjM3NTY0LDAuMDc0ODZsMCwyMS43OTU1YzAsMC4yNTcwNCAwLjE4MTI4LDAuNTEzMTcgMC4xNjg2NSwwLjc2NjYxYy0wLjAyNTcsMC41MDczMSAwLjA3MzUsMS4wMDg3NiAtMC4wMDE4LDEuNTAxMTljLTAuMTg4MDQsMS4yMzE1MyAtMC41MDg2NywyLjQxNTI2IC0wLjk3OSwzLjUyNzc0Yy0wLjE4ODA0LDAuNDQ1MDggLTAuMzg4MjYsMC44ODI1IC0wLjYxNzM0LDEuMzA0MTNjLTAuMTE0NTQsMC4yMTEwNCAtMC4yMjk1MywwLjQyNTY5IC0wLjM1MzU0LDAuNjMwNDJjLTAuNzQ1ODYsMS4yMjc0NyAtMS42NjE3MywyLjM1MjEzIC0yLjcxODc0LDMuMzEzMDljLTAuMTc2MzIsMC4xNjAwOSAtMC4zNTQ4OSwwLjM0NDA3IC0wLjUzODg4LDAuNDk2MDRjLTAuNTUxMDUsMC40NTUgLTEuMTM1MDMsMC45MjcxNCAtMS43NDg3NiwxLjMwMDA3Yy0yLjI1MDIxLDEuMzY3MjYgLTQuODkwNDksMi4yNjczNSAtNy43MTU2NiwyLjI2NzM1bC0yMi40NzIzNywwbDAsLTE0Ljg4MTE3bDIyLjA5NjI4LDBsMCwtMjIuMDIwOThsMC4zNzU2NCwtMC4wNzQ4NnptMCwtOTYuOTUzMDhsLTIyLjQ3MTkyLDBsMCwxNC44ODExN2wyMi4wOTYyOCwwbDAsMjIuMTcxNTlsMC4zNzU2NCwtMC4wNzUzMWwxNC44ODExNywwbC0wLjM3NTY0LDAuMDc0ODZsMCwtMjIuMzk2NjFjMCwtOC4yMTg5MiAtNi4yODY2MiwtMTQuNjU1NyAtMTQuNTA1NTMsLTE0LjY1NTd6bS02Ny41NjUwMiwxNC44ODExN2wzMC4yMTMyOCwwbDAsLTE0Ljg4MTE3bC0zMC4yMTMyOCwwbDAsMTQuODgxMTd6bS01MS44NTg2MiwtMC4yMjU0N2wwLDIyLjM5NzA2bDAuMzc1NjQsLTAuMDc1MzFsMTQuODgxMTcsMGwtMC4zNzU2NCwwLjA3NDg2bDAsLTIyLjE3MTE0bDIyLjA5NjI4LDBsMCwtMTQuODgxMTdsLTIxLjcyMDY1LDBjLTguMjE4NDYsMCAtMTUuMjU2ODEsNi40MzY3OCAtMTUuMjU2ODEsMTQuNjU1N3ptMTE5LjA0OTM2LDY3LjQxNjIxbDE0Ljg4MTE3LDBsMCwtMzAuMjEzMjhsLTE0Ljg4MTE3LDBsMCwzMC4yMTMyOHptLTEwNC4xNjgxOSwtMzAuMjEzMjhsLTE0Ljg4MTE3LDBsMCwzMC4yMTMyOGwxNC44ODExNywwbDAsLTMwLjIxMzI4em0wLDQ1LjE3MTEybDAuMzc1NjQsLTAuMDc1MzFsLTE0Ljg4MTE3LDBsLTAuMzc1NjQsMC4wNzUzMWwwLDIxLjc5NTVjMCwwLjI1NzA0IDAuMTk0MzYsMC41MTIyNyAwLjIwNzQzLDAuNzY1N2MwLjAyNTcsMC41MDczMSAwLjE3MDkxLDEuMDA3ODYgMC4yNDYyMiwxLjUwMDI5YzAuMTg4MDQsMS4yMzE1MyAwLjU3NDUsMi40MTM0NiAxLjA0NDg0LDMuNTI2MzljMC4xODgwNCwwLjQ0NTA4IDAuNDIxMTgsMC44Nzg4OSAwLjY1MDI2LDEuMzAwOThjMC4xMTQ1NCwwLjIxMTA0IDAuMjQ1NzYsMC40MTg0OCAwLjM3MDIzLDAuNjIzMjFjMC43NDU4NiwxLjIyNzQ3IDEuNjY5ODUsMi4zMzgxNSAyLjcyNjg2LDMuMjk4NjZjMC4xNzYzMiwwLjE2MDA5IDAuMzU4OTUsMC4zMTYxMSAwLjU0Mjk0LDAuNDY3NjNjMC41NTEwNSwwLjQ1NSAxLjEzNzI4LDAuOTgzNTEgMS43NTEwMiwxLjM1NjQ0YzIuMjUwMjEsMS4zNjcyNiA0Ljg5Mjc1LDIuMjY3MzUgNy43MTc5MiwyLjI2NzM1bDIxLjcxOTc0LDBsMCwtMTQuODgxMTdsLTIyLjA5NjI4LDBsMCwtMjIuMDIwOTh6bTM2Ljk3NzQ1LDM2LjkwMjZsMzAuMjEzMjgsMGwwLC0xNC44ODExN2wtMzAuMjEzMjgsMGwwLDE0Ljg4MTE3eiIgZmlsbD0iI2NjYyIvPgo8L3N2Zz4=');
	cursor:pointer;
}

/* no exist */
#channle-list-no-exist{
	width:100%;
	height:100%;
	text-align:center;
	color:#707070;
}

.channle-list-no-exist-title{
	margin-bottom:10px;
	font-weight:bold;
}

.channle-list-no-exist-content{
	font-size:13px;
}

/*******************/
.scroll-table {
    /*width: 100%;*/
	/*height:100px;*/
    border-collapse: separate;
    border-spacing: 0;
}

.scroll-table thead tr th { 
    text-align: left;
    background: linear-gradient(to bottom, #ffffff, #efefef);
    font-size: 13px;
    font-weight: bold;
	border-bottom: 1px solid #ccc;
}

.scroll-table tbody {
    border-top: 1px solid #ccc;
}

.scroll-table tbody td, .scroll-table thead th {
    border-right: 1px solid #ccc;
	padding: 3px 14px;
}

.scroll-table thead th{
	padding: 6px 10px;
}

.scroll-table tbody td{
	padding: 3px 10px;
	border-bottom: 1px solid #ccc;
	background-color: #fff;
}

.scroll-table tbody td:last-child, .scroll-table thead th:last-child {
    border-right: none;
}

.scroll-table tbody tr:last-child td{
/*	border-bottom: none;*/
}

.scroll-table.sticky thead tr th{
	position: sticky;
	top: 0;
/*	box-shadow: 1px 1px #ccc, inset 0 1px #ccc;*/
    z-index: 1;
}

/* window */
.window-group-container{
	text-align:center;
	padding:20px 10px;
	box-sizing: border-box;
}

#window-group-name{
	width:200px;
}

.window-add-channel-search{
	background-color:#e5e9eb;
	padding:5px;
	border-bottom: 1px solid #bababa;
}

.window-add-channel-filter{
	position:relative;
	display:inline-block;
}

.window-add-channel-filter .window-add-channel-filter-icon{
	position:absolute;
	left:6px;
	top:50%;
	margin-top:-9px;
	border-right: 1px solid #ccc;
	padding-right:4px;
    font-size: 0;
}

.window-add-channel-filter #window-add-channel-filter-input{
	width:150px;
	padding-left:36px;
	padding-right:25px;
}

.window-add-channel-filter #window-add-channel-filter-clear{
	position:absolute;
	right:6px;
	top:50%;
	margin-top:-9px;
	cursor: pointer;
    font-size: 0;
}

.window-add-channel-wrapper{
	position:relative;
}

#window-add-channel-containers{
	color:#707070;
	background-color:#FFF;
	overflow-x:hidden;
	overflow-y:scroll;
	height:500px;
}

#window-add-channel-containers input:disabled + label{
	opacity:0.5;
}

.window-add-channel-interface{
	padding:0px 8px;
	font-size:11px;
	font-weight:bolder;
	background-color:#e5e9eb;
	border-bottom: 1px solid #ccc;
	text-align:center;
}

.window-add-channel{
	padding:8px 8px;
	border-bottom: 1px solid #ccc;
}

.window-add-channel:after{
    content: "";
	display: block;
	clear:both;
}

.window-add-channel-name{
	padding:3px 0;
	color:#2a2a2a;
}

.window-add-channel-name > *{
	vertical-align:middle;
}

.window-add-channel-table{
	display:table;
	table-layout: fixed;
	width:100%;
}

.window-add-channel-row{
	display: table-row;
}

.window-add-channel-cell{
	display:table-cell;
	width:25%;
	padding:3px 0;
	white-space: nowrap;
	text-overflow: ellipsis;
	overflow: hidden;
}

.window-add-channel-cell > *{
	vertical-align:middle;
}

.window-add-channel-left{
	float:left;
}

.window-add-channel-left > *{
	vertical-align:middle;
}

.window-add-channel-checkbox{
	margin-right:8px;
}

.window-add-channel-right{
	float:right;
}

.window-add-channel-right > *{
	vertical-align:middle;
}

#window-add-channel-loader{
	position:absolute;
	top:50%;
	left:50%;
	margin-top:-16px;
	margin-left:-16px;
	display:none;
}

#window-add-device-is-empty,
#window-add-module-no-match,
#window-add-module-is-empty{
	text-align:center;
	position:absolute;
	line-height:400px;
	width:100%;
	display:none;
	background-color:#FFF;
}

#window-add-channel-containers > div > div.window-add-channel > div.window-add-channel-table > div.window-add-channel-name,
#window-add-channel-containers > div > div.window-add-channel > div.window-add-channel-table > div.window-add-channel-table{
	padding-left: 25px;
	background-image: url("./image/icon_hierarchy.svg");
	background-repeat: no-repeat;
	background-position: 0 0;
    box-sizing: border-box;
}

#window-add-channel-containers > div > div.window-add-channel > div.window-add-channel-table > div.window-add-channel-name + div.window-add-channel-table{
	padding-left: 50px;
	background-image: url("./image/icon_hierarchy.svg");
	background-repeat: no-repeat;
	background-position: 25px 0;
	box-sizing: border-box;
}

#window-add-channel-containers > div > div.window-add-channel > div.window-add-channel-table > div.window-add-channel-name + div.window-add-channel-table > div.window-add-channel-name + div.window-add-channel-table{
	padding-left: 25px;
	background-image: url("./image/icon_hierarchy.svg");
	background-repeat: no-repeat;
	background-position: 0 0;
    box-sizing: border-box;
}

#window-link-settings{
	width:880px;
/*	display:none;*/
}

#link-settings-container{
	display:block;
	height:100%;
	position:relative;
	overflow-x: hidden;
    overflow-y: scroll;
}

#link-settings-add-button{
	position:absolute;
	bottom:10px;
	right:25px;
	background-image: linear-gradient(to bottom, #4d90fe, #4787ed);
	box-shadow: 0 0 4px rgba(0,0,0,.14),0 4px 8px rgba(0,0,0,.28);
	border-radius: 50%;
	font-size:0;
	padding:10px;
	cursor: pointer;
	z-index:1;
	margin-right:-15px;
}

#link-settings-add-button:hover{
    background-image: linear-gradient(to bottom, #4d90fe, #357ae8);
}

#link-settings-add-button:active{
    box-shadow: inset 1px 1px 5px rgba(0, 0, 0, 0.2), 0 0 4px rgba(0,0,0,.14),0 4px 8px rgba(0,0,0,.28);
}

#link-settings-add-button > svg{
	fill:#FFF;
	width:24px;
	height:24px;
}

#link-settings-add-button:active > svg{
	position:relative;
	top:1px;
	left:1px;
}

#link-settings-cover,
#link-settings-loader{
	position:absolute;
	height:100%;
	top:0;
	right:0;
	bottom:0;
	left:0;
	background-color: rgba(255,255,255,0.8);
}

#link-settings-cover{
    z-index:1;
}

#link-settings-loader{
    z-index:3;
}

#link-settings-loader img{
	position:absolute;
	top:50%;
	left:50%;
	margin-top:-16px;
	margin-left:-16px;
}

#link-settings-empty-container{
	position:absolute;
	height:100%;
	width:100%;
	top:0;
	right:0;
	bottom:0;
	left:0;
	background-color: #FFF;
    z-index:1;
	display:table;
}

#link-settings-empty{
	display:table-cell;
	vertical-align:middle;
	text-align:center;
	text-align: center;
    color: #707070;
}

#link-settings-table{
	width:100%;
	top:0;
	left:0;
}

#link-settings-table-body > tr > td{
	padding:3px;
}

#link-settings-table-body > tr > td:nth-last-child(1),
#link-settings-table-body > tr > td:nth-last-child(2){
	width:1%;
}

#link-settings-table-body > tr > td:nth-last-child(2){
	text-align:center;
}

.link-settings-url-container{
	width:100%;
	border-spacing:0;
}

.link-settings-url-container td{
	border:none !important;
	padding:0 !important;
}

.link-settings-url-container td:last-child{
	width:1%;
}

.link-settings-url-container button{
	padding:5.5px;
	border-radius:0px;
}

.link-settings-url-text{
	box-shadow:none !important;
	border-right-width:0 !important;
	border-color: #B3B3B3 !important;
	width: 100%;
}

.link-settings-expiration-date-container{
	border:1px transparent solid;
	position:relative;
	cursor:pointer;
}

.link-settings-expiration-date-container:hover{
	border-color: #B3B3B3;
}

.link-settings-expiration-date-container.active{
	border-color: #888;
	z-index:2;
}

.link-settings-expiration-date-calendar-icon{
	vertical-align: middle;
	padding:5px
}

.link-settings-expiration-date-text{
	vertical-align: middle;
}

.link-settings-expiration-date-clear-icon{
	vertical-align: middle;
	position:absolute;
	right:3px;
	top:50%;
	margin-top:-9px;
/*	display:none;*/
}

.link-settings-controlable-conatiner{
	display:inline-block;
	vertical-align: middle;
}

.link-settings-remove-button{
/*	font-size: 13px !important;
	height:25px !important;
	width:70px !important;
	padding:0 !important;*/
}

.date-picker{
	margin-top: 0px;
	margin-left: -1px;
	border-radius: 0px;
	width:100%;
	z-index:2;
	top:0;
/*	inset:auto;*/
}

.date-picker-table{
	width:100%;
}

.widget-type{
	float:left;
	width:170px;
	height:170px;
	border: 1px solid #ccc;
	cursor:pointer;
	text-align:center;
	line-height:150px;
	margin:0 10px 45px 0;
    position: relative;
}

.widget-type img{
	position:absolute;
	top:50%;
	left:50%;
	margin-top:-80px;
	margin-left:-80px;
}

.widget-type span{
	position:absolute;
	display:block;
	bottom:-25px;
	width:100%;
	line-height:15px;
	font-size:15px;
	font-weight:bold;
}

.widget-type:hover{
	border: 1px solid #AAA;
}

.widget-type.active,
.widget-type:active{
    border: 1px solid #0070e1;
    box-shadow: 0 1px 6px rgba(0, 112, 225, 0.5);
}

.widget-type:hover span{

}

.widget-type.active span,
.widget-type:active span{
	color:#0070e1;
}
</style>
<script language="javascript" src='./js/jquery-ui.min.js'></script>
<script language="javascript" src="./js/jquery.floatThead.min.js"></script>
<script language="javascript" src="./js/jquery.flot.min.js"></script>
<script language="javascript" src="./js/jquery.flot.pie.min.js"></script>
<script language="javascript" src="./js/jquery.flot.tooltip.min.js"></script>
<script language="javascript" src="./js/jquery.flot.time.min.js"></script>
<script language="javascript" src="./js/jquery.flot.resize.min.js"></script>
<script language="javascript" src="./js/jquery.flot.stack.min.js"></script>
<script language="javascript" src="./js/jquery.flot.gauge.js"></script>
<script language="javascript" src="./js/jquery.tip.js"></script>
<script language="javascript" src="./js/jquery.livequery.min.js"></script>
<script language="javascript" src='./js/lodash.min.js'></script>
<script language="javascript" src='./js/gridstack.js'></script>
<script language="javascript" src='./js/gridstack.jQueryUI.js'></script>
<script language="javascript" src='./js/Moment/moment.js'></script>
<script language="javascript" src='./js/heatmap/heatmap.js'></script>
<script language="javascript" src="./js/TinyMCE/tinymce.min.js" referrerpolicy="origin"></script>
<script language="javascript" src="./js/TinyMCE/themes/silver/theme.min.js" referrerpolicy="origin"></script>
<script language="javascript" src="./js/TinyMCE/langs/zh_TW.js" referrerpolicy="origin"></script>
<script language="javascript" src="./js/TinyMCE/langs/zh_CN.js" referrerpolicy="origin"></script>
<script language="javascript" src="./js/TinyMCE/icons/default/icons.min.js" referrerpolicy="origin"></script>
<script language="javascript" src="./js/TinyMCE/plugins/advlist/plugin.min.js" referrerpolicy="origin"></script>
<script language="javascript" src="./js/TinyMCE/plugins/autolink/plugin.min.js" referrerpolicy="origin"></script>
<script language="javascript" src="./js/TinyMCE/plugins/lists/plugin.min.js" referrerpolicy="origin"></script>
<script language="javascript" src="./js/TinyMCE/plugins/link/plugin.min.js" referrerpolicy="origin"></script>
<script language="javascript" src="./js/TinyMCE/plugins/image/plugin.min.js" referrerpolicy="origin"></script>
<script language="javascript" src="./js/TinyMCE/plugins/code/plugin.min.js" referrerpolicy="origin"></script>
<script language="javascript" src="./js/TinyMCE/plugins/fullscreen/plugin.min.js" referrerpolicy="origin"></script>
<script language="javascript" src="./js/TinyMCE/plugins/table/plugin.min.js" referrerpolicy="origin"></script>
<script language="javascript" src='./js/leaflet/leaflet.js'></script>
<script language="javascript" src='./js/leaflet/leaflet.activearea.js'></script>
<script language="javascript" src='./js/leaflet/geosearch/l.control.geosearch.js'></script>
<script language="javascript" src='./js/leaflet/geosearch/l.geosearch.provider.openstreetmap.js'></script>
<script language="JavaScript">
var widget = {};
<?php
	if($dashboard_permission !== null){
		echo "var guestLevel = " . $dashboard_permission . ";";
	}
	else{
		echo "var guestLevel;";
	}

	require_once("./js/widget.line.php");
	require_once("./js/widget.bar.php");
	require_once("./js/widget.pie.php");
	require_once("./js/widget.gauge.php");
	require_once("./js/widget.value.php");
	require_once("./js/widget.wysiwyg_tinymce.php");
	require_once("./js/widget.bar-gauge.php");
	require_once("./js/widget.clock.php");
	require_once("./js/widget.camera.php");
	require_once("./js/widget.value-table.php");
	require_once("./js/widget.button.php");
	require_once("./js/widget.overlay.php");
	require_once("./js/widget.countdown_timer.php");
	require_once("./js/widget.map.php");
	require_once("./js/widget.slider.php");
?>
</script>
<script language="JavaScript">
var gridstack = null;
var cellHeight = 40;
var verticalMargin = 0;
var energyTypes = {
	"V": "V",
	"I": "I",
	"KW": "kW",
	"KVAR": "kvar",
	"KVA": "kVA",
	"PF": "PF",
	"KWH": "kWh",
	"KVARH": "kvarh",
	"KVAH": "kVAh"
};

var energyUnits = {
	"V": "V",
	"I": "A",
	"KW": "kW",
	"KVAR": "kvar",
	"KVA": "kVA",
	"PF": "",
	"KWH": "kWh",
	"KVARH": "kvarh",
	"KVAH": "kVAh"
};

var accounts = {};
var channels = {};
var links = {}

function showCreateDashboardWindow(){
	$("#window-create-dashboard div.popup-title").text("<?=$lang['DASHBOARD']['CREATE_DASHBOARD']['CREATE_DASHBOARD'];?>");

	// clear the filed
	$("#window-create-dashboard-name").val("");
	$("#window-create-dashboard-lock").attr("checked", false);
	$("#window-create-dashboard-as-default").attr("checked", false);
	$("#window-create-dashboard-data-length").val(60);
	$("#window-create-dashboard-dark-mode").attr("checked", false);

	showWindow($("#window-create-dashboard"), function(result){
		if(result == "ok"){
			// Input check
			if($("#window-create-dashboard-name").val() == ""){
				popupErrorWindow("<?=$lang['DASHBOARD']['CREATE_DASHBOARD']['POPUP']['NAME_IS_EMPTY'];?>");
				return false;
			}

			var accountUID = '<?=$_SESSION["account_uid"]?>';
			var dashboardUID = null;

			var name = $("#window-create-dashboard-name").val();
			var lock = $("#window-create-dashboard-lock").attr("checked") ? true : false;
			var asDefault = $("#window-create-dashboard-as-default").attr("checked") ? true : false;
			var dataLength = parseInt($("#window-create-dashboard-data-length").val(), 10);
			var darkMode = $("#window-create-dashboard-dark-mode").attr("checked") ? true : false;

			$("#wait-loader").show();
			$.ajax({
				url: "dashboard_ajax.php?act=add_dashboard<?=$token?>",
				type: "POST",
				dataType: "xml",
				data: {
					"name": name,
					"lock": lock ? "1" : "0",
					"default": asDefault ? "1" : "0",
					"data-length": dataLength,
					"dark-mode": darkMode ? "1" : "0"
				},
				success: function(data, textStatus, jqXHR){
					dashboardUID = $(data).find("result").attr("uid");

					window.accounts[accountUID].dashboards[dashboardUID] = {
						name: name,
						lock: lock,
						asDefault: asDefault,
						dataLength: dataLength,
						darkMode: darkMode,
						widgets: {}
					};

					$("#dsahboard-switch-selector").append(
						createSwitchOption(name, accountUID, dashboardUID)
					);

					updateSwitchOptionOrder();

					$("#dsahboard-switch-selector .dsahboard-switch-option[account_uid='" + accountUID + "'][dashboard_uid='" + dashboardUID + "']").triggerHandler("click");
					$("#dashboard-container").show();
				},
				error: function(jqXHR, textStatus, errorThrown){
					if(jqXHR.status === 0 && textStatus != "timeout"){ return; }// not include timeout

					alert(jqXHR.responseText);
				},
				complete: function(){
					$("#wait-loader").hide();
				}
			});
		}
		else if(result == "cancel"){}

		hideWindow();
	});

	$("#window-create-dashboard-name").focus().select();
}

function showEditDashboardWindow(){
	var accountUID = $("#dashboard-switch-handler").attr("account_uid");
	var dashboardUID = $("#dashboard-switch-handler").attr("dashboard_uid");

	$("#window-create-dashboard div.popup-title").text("<?=$lang['DASHBOARD']['CREATE_DASHBOARD']['EDIT_DASHBOARD'];?>");

	// fill out the filed
	$("#window-create-dashboard-name").val(window.accounts[accountUID].dashboards[dashboardUID].name);
	$("#window-create-dashboard-lock").attr("checked", window.accounts[accountUID].dashboards[dashboardUID].lock);
	$("#window-create-dashboard-as-default").attr("checked", window.accounts[accountUID].dashboards[dashboardUID].asDefault);
	$("#window-create-dashboard-data-length").val(window.accounts[accountUID].dashboards[dashboardUID].dataLength);
	$("#window-create-dashboard-dark-mode").attr("checked", window.accounts[accountUID].dashboards[dashboardUID].darkMode);

	showWindow($("#window-create-dashboard"), function(result){
		if(result == "ok"){
			// Input check
			if($("#window-create-dashboard-name").val() == ""){
				popupErrorWindow("<?=$lang['DASHBOARD']['CREATE_DASHBOARD']['POPUP']['NAME_IS_EMPTY'];?>");
				return false;
			}

			$("#wait-loader").show();

			var name = $("#window-create-dashboard-name").val();
			var lock = $("#window-create-dashboard-lock").attr("checked") ? true : false;
			var asDefault = $("#window-create-dashboard-as-default").attr("checked") ? true : false;
			var dataLength = parseInt($("#window-create-dashboard-data-length").val(), 10);
			var darkMode = $("#window-create-dashboard-dark-mode").attr("checked") ? true : false;

			$.ajax({
				url: "dashboard_ajax.php?act=edit_dashboard<?=$token?>",
				type: "POST",
				dataType: "xml",
				data: {
					"uid": dashboardUID,
					"name": name,
					"lock": lock ? "1" : "0",
					"default": asDefault ? "1" : "0",
					"data-length": dataLength,
					"dark-mode": darkMode ? "1" : "0"
				},
				success: function(data, textStatus, jqXHR){
					if(asDefault == true){
						for(var uid in window.accounts[accountUID].dashboards){
							window.accounts[accountUID].dashboards[uid].asDefault = false;
						}
					}

					window.accounts[accountUID].dashboards[dashboardUID].name = name;
					window.accounts[accountUID].dashboards[dashboardUID].lock = lock;
					window.accounts[accountUID].dashboards[dashboardUID].asDefault = asDefault;
					window.accounts[accountUID].dashboards[dashboardUID].dataLength = dataLength;
					window.accounts[accountUID].dashboards[dashboardUID].darkMode = darkMode;

					$("#dashboard-title").text(name);
					$("#dashboard-switch-handler .dsahboard-switch-option-name").text(name);
					$("#dsahboard-switch-selector .dsahboard-switch-option[account_uid='" + accountUID + "'][dashboard_uid='" + dashboardUID + "'] .dsahboard-switch-option-name").text(name);
					$("#dsahboard-switch-selector .dsahboard-switch-option[account_uid='" + accountUID + "'][dashboard_uid='" + dashboardUID + "'] .dsahboard-switch-option-icon div.lock").toggle(lock);
					updateSwitchOptionOrder();

					$("#dsahboard-switch-selector .dsahboard-switch-option[account_uid='" + accountUID + "'][dashboard_uid='" + dashboardUID + "']").triggerHandler("click");// Reload widget for dark mode
				},
				error: function(jqXHR, textStatus, errorThrown){
					if(jqXHR.status === 0 && textStatus != "timeout"){ return; }// not include timeout

					alert(jqXHR.responseText);
				},
				complete: function(){
					$("#wait-loader").hide();
				}
			});
		}
		else if(result == "cancel"){}

		hideWindow();
	});

	$("#window-create-dashboard-name").focus().select();
}

function showRemoveDashboardWindow(){
	popupConfirmWindow("<?=$lang['DASHBOARD']['POPUP']['ARE_YOU_SURE_REMOVE_DASHBOARD'];?>", function(){
		var accountUID = $("#dashboard-switch-handler").attr("account_uid");
		var dashboardUID = $("#dashboard-switch-handler").attr("dashboard_uid");

		$("#wait-loader").show();

		$.ajax({
			url: "dashboard_ajax.php?act=remove_dashboard<?=$token?>",
			type: "POST",
			dataType: "xml",
			data: {
				"uid": dashboardUID
			},
			success: function(data, textStatus, jqXHR){
				$("#dsahboard-switch-selector .dsahboard-switch-option[account_uid='" + accountUID + "'][dashboard_uid='" + dashboardUID + "']").remove();

				var $option = $("#dsahboard-switch-selector .dsahboard-switch-option").first();
				if($option.length > 0){
					$option.triggerHandler("click");
				}
				else{
					clearDashboard();
					$("#dashboard-container").hide();
					$("#dashboard-no-exist-container").show();
				}

				delete window.accounts[accountUID].dashboards[dashboardUID];
				trimChannelData();
			},
			error: function(jqXHR, textStatus, errorThrown){
				if(jqXHR.status === 0 && textStatus != "timeout"){ return; }// not include timeout

				alert(jqXHR.responseText);
			},
			complete: function(){
				$("#wait-loader").hide();
			}
		});
	}, function(){
	});
}

function showaddLinkWindow(){
	showWindow($("#window-link-settings"), function(result){
		if(result == "ok"){
	
		}
		else if(result == "cancel"){}

		hideWindow();
	});
}

function loadDashboard(){
	$("#wait-loader").show();

	return $.Deferred(function(deferred){
		$.ajax({
			url: "dashboard_ajax.php?act=get_dashboard<?=$token?>",
			type: "POST",
			dataType: "xml",
			success: function(data, textStatus, jqXHR){
				var $xmlCMD = $(data).find("cmd");
				if($xmlCMD.attr("result") == "ERROR"){
					deferred.reject($xmlCMD.attr("error_code"));
					return;
				}

				// Module infomation
				var infos = {};
				var $xmlAccounts = $xmlCMD.find("> info > account");
				for(var i = 0; i < $xmlAccounts.length; i++){
					var $xmlAccount = $($xmlAccounts[i]);
					var accountUID = $xmlAccount.attr("uid");
					infos[accountUID] = {
						"username": $xmlAccount.attr("username"),
						"nickname": $xmlAccount.attr("nickname")
					};

					var $xmlDevices = $xmlAccount.find("> device");
					for(var j = 0; j < $xmlDevices.length; j++){
						var $xmlDevice = $($xmlDevices[j]);
						var deviceUID = $xmlDevice.attr("uid");
						infos[accountUID][deviceUID] = {
							"modelName": $xmlDevice.attr("model_name"),
							"nickname": $xmlDevice.attr("nickname")
						};

						var $xmlModules = $xmlDevice.find("> module");
						for(var k = 0; k < $xmlModules.length; k++){
							var $xmlModule = $($xmlModules[k]);
							var moduleUID = $xmlModule.attr("uid");

							infos[accountUID][deviceUID][moduleUID] = {
								"interface": $xmlModule.attr("interface"),
								"number": $xmlModule.attr("number"),
								"manufacturer": $xmlModule.attr("manufacturer"),
								"modelName": $xmlModule.attr("model_name"),
								"nickname": $xmlModule.attr("nickname"),
								"type": $xmlModule.attr("type"),
								"loop": parseInt($xmlModule.attr("loop"), 10),
								"phase": parseInt($xmlModule.attr("phase"), 10)
							};

							var $xmlChannels = $xmlModule.find("> channel");
							for(var l = 0; l < $xmlChannels.length; l++){
								var $xmlChannel = $($xmlChannels[l]);
								var channel = $xmlChannel.attr("uid");

								infos[accountUID][deviceUID][moduleUID][channel] = {
									"nickname": $xmlChannel.attr("nickname"),
									"unit": $xmlChannel.attr("unit")
								}
							}
						}
					}
				}

				// Dashboard data
				var $xmlAccounts = $xmlCMD.find("> account");
				for(var i = 0; i < $xmlAccounts.length; i++){
					var $xmlAccount = $($xmlAccounts[i]);
					var accountUID = $xmlAccount.attr("uid");

					window.accounts[accountUID] = {
						username: $xmlAccount.attr("username"),
						nickname: $xmlAccount.attr("nickname"),
						dashboards: (function($xmlDashboards){
							var retObj = {};

							for(var i = 0; i < $xmlDashboards.length; i++){
								var $xmlDashboard = $($xmlDashboards[i]);

								retObj[$xmlDashboard.attr("uid")] = {
									name: $xmlDashboard.attr("name"),
									lock: $xmlDashboard.attr("lock") == "1" ? true : false,
									asDefault: $xmlDashboard.attr("default") == "1" ? true : false,
									dataLength: parseInt($xmlDashboard.attr("data-length"), 10),
									darkMode: $xmlDashboard.attr("dark-mode") == "1" ? true : false,
									share: accountUID != "<?=$_SESSION['account_uid']?>",
									widgets: (function($xmlWidgets){
										var retObj = {};

										for(var i = 0; i < $xmlWidgets.length; i++){
											var $xmlWidget = $($xmlWidgets[i]);

											retObj[$xmlWidget.attr("uid")] = {
												x: parseInt($xmlWidget.attr("x"), 10),
												y: parseInt($xmlWidget.attr("y"), 10),
												width: parseInt($xmlWidget.attr("width"), 10),
												height: parseInt($xmlWidget.attr("height"), 10),

												settings: JSON.parse($xmlWidget.find("> settings").text()),
												channels: (function($xmlAccounts){
													var retObj = {};

													Object.defineProperty(retObj, "order", {
														enumerable : false,
														value : []
													});

													for(var i = 0; i < $xmlAccounts.length; i++){
														var $xmlAccount = $($xmlAccounts[i]);
														var accountUID = $xmlAccount.attr("uid");
														retObj[accountUID] = {};

														var $xmlDevices = $xmlAccount.find("> device");
														for(var j = 0; j < $xmlDevices.length; j++){
															var $xmlDevice = $($xmlDevices[j]);
															var deviceUID = $xmlDevice.attr("uid");
															retObj[accountUID][deviceUID] = {};

															var $xmlModules = $xmlDevice.find("> module");
															for(var k = 0; k < $xmlModules.length; k++){
																var $xmlModule = $($xmlModules[k]);
																var moduleUID = $xmlModule.attr("uid");
																retObj[accountUID][deviceUID][moduleUID] = {};

																var $xmlChannels = $xmlModule.find("> channel");
																for(var l = 0; l < $xmlChannels.length; l++){
																	var $xmlChannel = $($xmlChannels[l]);
																	var channel = $xmlChannel.attr("name");

																	retObj[accountUID][deviceUID][moduleUID][channel] = (function(){
																		// Nickname
																		var nickname = "";
																		var splitArray = channel.split("-");
																		if(splitArray.length > 1){// Power Meter
																			nickname = infos[accountUID][deviceUID][moduleUID][splitArray[0] + "-" + splitArray[1]].nickname;
																		}
																		else{
																			nickname = infos[accountUID][deviceUID][moduleUID][channel].nickname;
																		}

																		// Full name
																		var fullName = infos[accountUID][deviceUID].modelName;

																		if(infos[accountUID][deviceUID].nickname != ""){
																			fullName += "(" + infos[accountUID][deviceUID].nickname + ")";
																		}

																		fullName += " > ";

																		if(infos[accountUID][deviceUID][moduleUID].interface != "IR"){
																			if(infos[accountUID][deviceUID][moduleUID].modelName == ""){
																				fullName += infos[accountUID][deviceUID][moduleUID].nickname;
																			}
																			else{
																				if(infos[accountUID][deviceUID][moduleUID].manufacturer != ""){
																					fullName += infos[accountUID][deviceUID][moduleUID].manufacturer + " ";
																				}

																				fullName += infos[accountUID][deviceUID][moduleUID].modelName;

																				if(infos[accountUID][deviceUID][moduleUID].nickname != ""){
																					fullName += "(" + infos[accountUID][deviceUID][moduleUID].nickname + ")";
																				}
																			}
																		}
																		else{
																			fullName += "<?=$lang['DASHBOARD']['INTERNAL_REGISTER'];?>";
																		}

																		fullName += " > " + channelToName(channel, infos[accountUID][deviceUID][moduleUID].loop, infos[accountUID][deviceUID][moduleUID].phase, nickname);

																		// Short name & Unit
																		var shortName = "", unit = "";
																		var splitArray = channel.split("-");
																		if(splitArray.length > 1){// Power Meter
																			shortName = (nickname ? nickname + " > " : "") + energyTypes[splitArray[2]];
																			unit = energyUnits[splitArray[2]];
																		}
																		else{
																			shortName = nickname || channel;
																			unit = infos[accountUID][deviceUID][moduleUID][channel].unit;
																		}

																		return {
																			shortName: shortName,
																			fullName: fullName,
																			unit: unit,
																			icon: $xmlChannel.attr("icon") && $xmlChannel.attr("icon") != "-" ? $xmlChannel.attr("icon") : null
																		};
																	})();

																	retObj.order[parseInt($xmlChannel.attr("order"), 10) - 1] = {
																		accountUID: accountUID,
																		deviceUID: deviceUID,
																		moduleUID: moduleUID,
																		channel: channel
																	};
																}

																// Remove empty element in order array
																for(var l = retObj.order.length - 1; l >= 0; l--){
																	if(retObj.order[l] === undefined){
																		retObj.order.splice(l, 1);
																	}
																}
															}
														}
													}

													return retObj;
												})($xmlWidget.find("> channels > account"))
											}
										}

										return retObj;
									})($xmlDashboard.find("> widget"))
								}
							}

							return retObj;
						})($xmlAccount.find("> dashboard"))
					};
				}

				// Link
				var $xmlLinks = $xmlCMD.find("> link > setting");
				for(var i = 0; i < $xmlLinks.length; i++){
					var $xmlLink = $($xmlLinks[i]);

					links[$xmlLink.attr("token")] = {
						controllable: $xmlLink.attr("controllable") == "0" ? false : true,
						expirationDate: (function(){
							if($xmlLink.attr("expiration_date")){
								var expDate = new Date(parseInt($xmlLink.attr("expiration_date"), 10) * 1000);
								expDate.setDate(expDate.getDate() - 1);
								return expDate;
							}
							else{
								return null;
							}
						})()
					}
				}

				for(var accountUID in window.accounts){
					var account = window.accounts[accountUID];

					for(var dashboardUID in account.dashboards){
						var dashboard = account.dashboards[dashboardUID];

						$("#dsahboard-switch-selector").append(
							createSwitchOption(dashboard.name, accountUID, dashboardUID)
						);
					}
				}

				for(var token in window.links){
					addLink(token);
				}

				updateSwitchOptionOrder();

				// Channel data
				trimChannelData();

				startUpdate();
			},
			error: function(jqXHR, textStatus, errorThrown){
				if(jqXHR.status === 0 && textStatus != "timeout"){ return; }// not include timeout

				alert(jqXHR.responseText);
			},
			complete: function(){
				$("#wait-loader").hide();
				deferred.resolve();
			}
		})
	}).promise();
}

function channelToName(channel, loop, phase, nickname){
	var splitArray = channel.split("-");
	if(splitArray.length > 1){// Power Meter
		if(phase == 3){
			var channelName = (loop > 1 ? "<?=$lang['DASHBOARD']['LOOP_WITH_NO'];?>".replace("%loop%", splitArray[0]) + " > " : "") + (phase == 3 ? ["<?=$lang['DASHBOARD']['PHASE_A'];?>", "<?=$lang['DASHBOARD']['PHASE_B'];?>", "<?=$lang['DASHBOARD']['PHASE_C'];?>", "<?=$lang['DASHBOARD']['TOTAL_AVERAGE'];?>"][parseInt(splitArray[1], 10) - 1] + (nickname ? "(" + nickname + ")" : "") + " > " : "") + energyTypes[splitArray[2]];
		}
		else{
			var channelName = "<?=$lang['DASHBOARD']['LOOP_WITH_NO'];?>".replace("%loop%", splitArray[0]) + (nickname ? "(" + nickname + ")" : "") + " > " + energyTypes[splitArray[2]];
		}
	}
	else{
		var splitArray = channel.match(/(\D+)(\d+)/);
		var channelName = {
			"DI": "DI%channel%",
			"DIC": "<?=$lang['DASHBOARD']['DI_COUNTER_WITH_NO'];?>",
			"DO": "DO%channel%",
			"DOC": "<?=$lang['DASHBOARD']['DO_COUNTER_WITH_NO'];?>",
			"AI": "AI%channel%",
			"AO": "AO%channel%",
			"CI": "Discrete Input %channel%",
			"CO": "Coil Output %channel%",
			"RI": "Input Register %channel%",
			"RO": "Holding Register %channel%",
			"IR": "<?=$lang['DASHBOARD']['INTERNAL_REGISTER_WITH_NO'];?>"
		}[splitArray[1]].replace("%channel%", splitArray[2]);

		channelName += nickname ? "(" + nickname + ")": "";
	}

	return channelName;
}

function createChannelRow(accountUID, deviceUID, moduleUID, channel, shortName, fullName, unit, icon){
	return $("<tr></tr>").attr({
		"account_uid": accountUID,
		"device_uid": deviceUID,
		"module_uid": moduleUID,
		"channel": channel,
		"shortName": shortName,
		"fullName": fullName,
		"unit": unit,
		"icon": icon
	}).append(
		$("<td></td>").addClass("channel-text").css("cursor", "ns-resize").text(fullName).append(
			$("<div></div>").attr("class", "icon-container").css("backgroundImage", icon ? "url(" + icon + ")" : null).append(
				$("<input>").attr({
					"type": "file",
					"accept": "image/*"
				}).css("display", "none").bind("change", function(){
					if($(this).val() == "") {
						// Cancel
						$(this).closest("div.icon-container").css("backgroundImage", "").closest("tr").removeAttr("icon");
					}
					else{
						var that = this;
						var reader = new FileReader();
						reader.onload = function(){
							$(that).closest("div.icon-container").css("backgroundImage", "url(\"" + reader.result + "\")").closest("tr").attr("icon", reader.result);
						};
						reader.readAsDataURL(this.files[0]);
					}
				}).bind("click", function(event){
					event.stopPropagation();
				})
			).bind("click.once", function(){
				// the change event of input element will not trigger when user first time click the cancel button
				$(window).bind("focus", $(this), function(event){
					$(window).unbind("focus");
					$(event.data).unbind("click.once");
					$(event.data).find("input").triggerHandler("change");
				});
			}).bind("click", function(){
				$(this).find("input").trigger("click");
			})
		)
	).append(
		$("<td></td>").css({
			"width": "1%",
			"padding": "3px",
			"cursor": "ns-resize"
		}).append(
			$("<input type='button'/>").addClass("red channle-list-restore-button").val("<?=$lang['DASHBOARD']['CREATE_WIDGET']['REMOVE'];?>").bind("click", function(){
				$(this).closest("tr").remove();
				showChannelNoExist();
				showAddChannelButton();
			})
		)
	);
}

function showAddChannelWindow(){
	loadDeviceList();

	showWindow($("#window-add-channel"), function(result){
		if(result == "ok"){
			// Mark has new setting device
			$("#window-add-channel-containers > div").each(function(){
				var splitArray = $(this).attr("id").split("-");
				$("#channle-list-table-body > tr[account_uid='" + splitArray[0] + "'][device_uid='" + splitArray[1] + "']").attr("remove", true);
			});

			$("#window-add-channel-containers input[is_channel]:checked").each(function(){
				var shortName = $(this).attr("shortName");
				var fullName = $("#window-add-channel-selector > option[account_uid='" + $(this).attr("account_uid") + "'][device_uid='" + $(this).attr("device_uid") + "']").text() + " > " + $(this).closest(".window-add-channel").find("> .window-add-channel-name label").text() + " > " + channelToName($(this).attr("channel"), parseInt($(this).attr("_loop"), 10), parseInt($(this).attr("phase"), 10), $(this).attr("nickname"));
				var unit = $(this).attr("unit");
				var $row = $("#channle-list-table-body > tr[account_uid='" + $(this).attr("account_uid") + "'][device_uid='" + $(this).attr("device_uid") + "'][module_uid='" + $(this).attr("module_uid") + "'][channel='" + $(this).attr("channel") + "']");

				if($row.length <= 0){
					// New row
					$("#channle-list-table-body").append(
						createChannelRow(
							$(this).attr("account_uid"),
							$(this).attr("device_uid"),
							$(this).attr("module_uid"),
							$(this).attr("channel"),
							shortName,
							fullName,
							unit,
							null
						)
					);
				}
				else{
					// Update row
					$row.attr("shortName", shortName);
					$row.attr("fullName", fullName);
					$row.attr("unit", unit);
					$row.removeAttr("remove");
				}
			});

			// Remove has new setting row
			$("#channle-list-table-body > tr[remove]").remove();

//			// Sort
//		    var $wrapper = $('#channle-list-table-body'),
//		        $art = $wrapper.children('tr');
//
//		    $art.sort(function(a, b) {
//				var accountUIDa = $(a).attr("account_uid") == '<?=$_SESSION["account_uid"]?>' ? 0 : parseInt($(a).attr("account_uid"), 10);
//				var accountUIDb = $(b).attr("account_uid") == '<?=$_SESSION["account_uid"]?>' ? 0 : parseInt($(b).attr("account_uid"), 10);
//
//		        return (
//					((accountUIDa * (parseInt("FFFFFFFFFFFFFFFF", 16) + 1)) + parseInt($(a).attr("device_uid"), 16)) -
//					((accountUIDb * (parseInt("FFFFFFFFFFFFFFFF", 16) + 1)) + parseInt($(b).attr("device_uid"), 16))
//				);
//		    }).each(function() {
//		        $wrapper.append(this);
//		    });

			showChannelNoExist();
			showAddChannelButton();
		}
		else if(result == "cancel"){}

		hideWindow();
	});
}

function showAddChannelButton(){
	var type = $("#widget-type div.widget-type.active").attr("widget-type");
	var $channelRows = $("#channle-list-table-body > tr");
	if(window.widget[type].maxChannelNumber != null && $channelRows.length >= window.widget[type].maxChannelNumber){
		$("#button-add-channel").attr("disabled", true);
	}
	else{
		$("#button-add-channel").attr("disabled", false);
	}
}

function showChannelNoExist(){
	if($("#channle-list-table-body").children().length > 0){
		$("#channle-list-container").css("overflowY", "scroll");
		$("#channle-list-table").show().floatThead({
			scrollContainer: function($table){
				return $("#channle-list-container");
			}
		});
		$("#channle-list-no-exist").hide();

		// Sortable
		$("#channle-list-table-body").sortable({
			opacity: 0.8,
			placeholder: "channel-placeholder",
			start: function(e, ui){
				ui.item.find("td").css("border", "1px solid #ccc");
				ui.item.css("zIndex", "1002");
				ui.placeholder.height(ui.item.height());
			},
			stop: function(e, ui){
				ui.item.find("td").css("border", "");
			}
		});
	}
	else{
		$("#channle-list-container").css("overflowY", "auto");
		$("#channle-list-table").hide().floatThead('destroy');
		$("#channle-list-no-exist").show();
	}
}

function callChannelUpdateEvent(){
	var channels = {};
	$("#channle-list-table-body > tr").each(function(){
		var accountUID = $(this).attr("account_uid");
		var deviceUID = $(this).attr("device_uid");
		var moduleUID = $(this).attr("module_uid");
		var channel = $(this).attr("channel");
		channels[accountUID] = channels[accountUID] || {};
		channels[accountUID][deviceUID] = channels[accountUID][deviceUID] || {};
		channels[accountUID][deviceUID][moduleUID] = channels[accountUID][deviceUID][moduleUID] || {};
		channels[accountUID][deviceUID][moduleUID][channel] = {
			shortName: $(this).attr("shortName"),
			fullName: $(this).attr("fullName"),
			unit: $(this).attr("unit"),
			icon: $(this).attr("icon") || null
		};
	});

	window.widget[$("#widget-type .widget-type.active").attr("widget-type")].channelUpdated($("#widget-setting-container"), channels);
}

function createSwitchOption(name, accountUID, dashboardUID){
	var dashboard = window.accounts[accountUID].dashboards[dashboardUID];

	return $("<div></div>").attr({
		"account_uid": accountUID,
		"dashboard_uid": dashboardUID
	}).addClass("dsahboard-switch-option").append(
		$("<div></div>").addClass("dsahboard-switch-option-name").text(name)
	).append(
		$("<div></div>").addClass("dsahboard-switch-option-icon").append(
			(function(){
				var $div = $("<div></div>").attr({
					"class": "share",
					"tip": "This dashboard is shared by %username%.".replace("%username%", window.accounts[accountUID].nickname + "(" + window.accounts[accountUID].username + ")")
				}).append(
					$(createSVGIcon("image/ics.svg", "share"))
				);

				bindTipEvent($div);

				if(dashboard.share == true){
					$div.show();
				}
				else{
					$div.hide();
				}

				return $div;
			})()
		).append(
			(function(){
				var $div = $("<div></div>").attr({
					"class": "lock"
				}).append(
					$(createSVGIcon("image/ics.svg", "lock"))
				);

				if(dashboard.lock == true || dashboard.share == true){
					$div.show();
				}
				else{
					$div.hide();
				}

				return $div;
			})()
		)
	).bind("click", function(){
		clearDashboard();

		var accountUID = $(this).attr("account_uid");
		var dashboardUID = $(this).attr("dashboard_uid");
		var name = $(this).find(".dsahboard-switch-option-name").text();

		$("#dashboard-title").text(name);
		$("#dashboard-switch-handler .dsahboard-switch-option-name").text(name);
		$("#dashboard-switch-handler").attr("account_uid", accountUID);
		$("#dashboard-switch-handler").attr("dashboard_uid", dashboardUID);

		if(window.accounts[accountUID].dashboards[dashboardUID].darkMode == true){
			$("body").addClass("dark");
		}
		else{
			$("body").removeClass("dark");
		}

		if(window.accounts[accountUID].dashboards[dashboardUID].share == true){// Share
			gridstack.setStatic(true);

			$("#item-add").hide();
			$("#dashboard-edit, #dashboard-remove, #dashboard-copy").attr("disabled", true);
			$("#dashboard-switch-handler .dsahboard-switch-option-icon div.share").attr("tip", $(this).find(".dsahboard-switch-option-icon div.share").attr("tip")).show();
			$("#dashboard-switch-handler .dsahboard-switch-option-icon div.lock").show();
		}
		else{
			if(window.accounts[accountUID].dashboards[dashboardUID].lock == true){
				gridstack.setStatic(true);
				$("#dashboard-switch-handler .dsahboard-switch-option-icon div.lock").show();
			}
			else{
				gridstack.setStatic(false);
				$("#dashboard-switch-handler .dsahboard-switch-option-icon div.lock").hide();
			}

			$("#item-add").show();
			$("#dashboard-edit, #dashboard-remove, #dashboard-copy").attr("disabled", false);
			$("#dashboard-switch-handler .dsahboard-switch-option-icon div.share").hide();
		}

		$("#dashboard-container").show();
		$("#dashboard-no-exist-container").hide();

		// Create widget
		ignoreChangeEvent = true;
		gridstack.grid.float = true;

		for(var widgetUID in window.accounts[accountUID].dashboards[dashboardUID].widgets){
			var widget = window.accounts[accountUID].dashboards[dashboardUID].widgets[widgetUID];

			var $widget = createWidget(widgetUID, widget.x, widget.y, widget.width, widget.height, false);
			var $widgetTitle = $widget.find("> div.widget-title");
			var $widgetContent = $widget.find("> div.widget-content");

			$widgetTitle.text(widget.settings.title.text);

			if(!widget.settings.title.text){
				$widgetContent.css("height", "100%");
			}
			else{
				$widgetTitle.css({
					"fontSize": widget.settings.title.size + "px",
					"lineHeight": (widget.settings.title.size + 5) + "px"
				});
				$widgetContent.css("height", "calc(100% - " + (widget.settings.title.size + 5) + "px)");
			}

			if(window.accounts[accountUID].dashboards[dashboardUID].share == true){
				$widget.find("> div.item-setting").remove();
			}

			// Create widget event
			window.widget[widget.settings.type].widgetCreated($widgetContent, widget.settings, widget.channels);
		}

		ignoreChangeEvent = false;
		gridstack.grid.float = false;

		$("html, body").scrollTop(0);

		window.location.hash = "#" + accountUID + "-" + dashboardUID;
	});
}

function updateSwitchOptionOrder(){
	$("#dsahboard-switch-selector .dsahboard-switch-option").each(function(){
		var accountUID = $(this).attr("account_uid");
		var dashboardUID = $(this).attr("dashboard_uid");
		var dashboard = window.accounts[accountUID].dashboards[dashboardUID];

		$(this).css("order", ((accountUID == <?=$_SESSION["account_uid"]?> ? 0 : parseInt(accountUID, 10) * 10000) + (dashboard.asDefault == true ? 0 : parseInt(dashboardUID, 10))).toString());
	});
}

function loadDeviceList(){
	$("#window-add-channel-selector, #window-add-channel-containers").empty();
	$("#window-add-device-is-empty, #window-add-module-is-empty, #window-add-module-no-match").hide();
	$("#window-add-channel-loader").show();

	$.ajax({
		url: "dashboard_ajax.php?act=get_device_list<?=$token?>",
		type: "POST",
		dataType: "xml",
		success: function(data, textStatus, jqXHR){
			// Process myself device
			var $devices = $(data).find("list > device");
			for(var i = 0; i < $devices.length; i++){
				var $device = $($devices[i]);

				var $option = $("<option></option>").attr({
					"account_uid": $device.attr("account_uid"),
					"account_username": $device.attr("account_username"),
					"account_nickname": $device.attr("account_nickname"),
					"device_uid": $device.attr("uid"),
					"device_model_name": $device.attr("model_name"),
					"admin_password": $device.attr("admin_password"),
					"disabled": $device.attr("online") == "0" ? true : null
				}).text($device.attr("model_name") + ($device.attr("nickname") != "" ? "(" + $device.attr("nickname") + ")" : ""));

				if(!$device.attr("account_username")){
					$option.appendTo("#window-add-channel-selector");
				}
				else{
					var $shareUserOption = $("#window-add-channel-selector > option[account_uid='" + $device.attr("account_uid") + "']:not([device_uid]):disabled");

					if($shareUserOption.length <= 0){
						$shareUserOption = $("<option></option>").attr({
							"disabled": true,
							"account_uid": $device.attr("account_uid")
						}).css({
							"background": "#e5e9eb",
							"fontWeight": "bold",
							"color": "#707070"
						}).text($device.attr("account_nickname") + "(" + $device.attr("account_username") + ")").appendTo("#window-add-channel-selector");
					}

					$option.insertAfter($shareUserOption);
				}
			}

			if($("#window-add-channel-selector > option").length <= 0){
				$("#window-add-channel-selector").append(
					$("<option></option>").text("<?=$lang['ADD_CHANNEL']['NO_DEVICE_EXIST'];?>")
				);
			}
			else{
				$("#window-add-channel-selector").triggerHandler("change");
			}
		},
		error: function(jqXHR, textStatus, errorThrown){
			if(jqXHR.status === 0){ return; }// include timeout

			alert(jqXHR.responseText);
		},
		complete: function(){
			$("#window-add-channel-loader").hide();
		}
	});
}

function loadModuleList(accountUID, deviceUID){
	$("#window-add-channel-loader").show();

	return $.ajax({
		url: "dashboard_ajax.php?act=get_module_list<?=$token?>",
		type: "POST",
		data: "account_uid=" + accountUID + "&device_uid=" + deviceUID,
		dataType: "xml",
		success: function(data, textStatus, jqXHR){
			var $container = $("<div></div>").attr("id", accountUID + "-" + deviceUID);

			var $modules = $(data).find("list > module[interface!='IR']");
			if($modules.length > 0){
				var interfaces = {
					"onboard": [],
					"comport": [],
					"network": []
				};

				for(var i = 0; i < $modules.length; i++){
					var $module = $($modules[i]);

					var match = $module.attr("interface").match(/COM(\d+)/);
					if(match && match[1]){
						if(typeof(interfaces.comport[parseInt(match[1], 10)]) == "undefined"){
							interfaces.comport[parseInt(match[1], 10)] = {
								name: $module.attr("interface"),
								modules: []
							}
						}

						interfaces.comport[parseInt(match[1], 10)].modules.push({
							"uid": $module.attr("uid"),
							"manufacturer": $module.attr("manufacturer"),
							"modelName": $module.attr("model_name"),
							"nickname": $module.attr("nickname"),
							"type": $module.attr("type"),
							"loop": parseInt($module.attr("loop"), 10),
							"phase": parseInt($module.attr("phase"), 10),
							"channel": $module.children()
						});
					}
					else if($module.attr("interface") == "LAN"){
						if(typeof(interfaces.network[0]) == "undefined"){
							interfaces.network[0] = {
								name: "LAN",
								modules: []
							}
						}

						interfaces.network[0].modules.push({
							"uid": $module.attr("uid"),
							"manufacturer": $module.attr("manufacturer"),
							"modelName": $module.attr("model_name"),
							"nickname": $module.attr("nickname"),
							"type": $module.attr("type"),
							"loop": parseInt($module.attr("loop"), 10),
							"phase": parseInt($module.attr("phase"), 10),
							"channel": $module.children()
						});
					}
					else if($module.attr("interface") == "XV" || $module.attr("interface") == "XW" || $module.attr("interface") == "XU"){
						if(typeof(interfaces.onboard[0]) == "undefined"){
							interfaces.onboard[0] = {
								name: $module.attr("interface") == "XU" ? "<?=$lang['DASHBOARD']['ADD_CHANNEL']['BUILD_IN'];?>" : $module.attr("interface") + "-Board",
								modules: []
							}
						}

						interfaces.onboard[0].modules.push({
							"uid": $module.attr("uid"),
							"manufacturer": $module.attr("manufacturer"),
							"modelName": $module.attr("model_name"),
							"nickname": $module.attr("nickname"),
							"type": $module.attr("type"),
							"loop": parseInt($module.attr("loop"), 10),
							"phase": parseInt($module.attr("phase"), 10),
							"channel": $module.children()
						});
					}
				}

				for(var sourceTypeIndex = 0, sourceTypeArray = ["onboard", "comport", "network"]; sourceTypeIndex < sourceTypeArray.length; sourceTypeIndex++){
					var sourceType = sourceTypeArray[sourceTypeIndex];

					for(var sourceIndex = 0; sourceIndex < interfaces[sourceType].length; sourceIndex++){
						if(typeof(interfaces[sourceType][sourceIndex]) == "undefined"){continue;}

						var modules = interfaces[sourceType][sourceIndex].modules;
						if(modules.length > 0){
							$container.append(
								$("<div></div>").attr("class", "window-add-channel-interface").text(interfaces[sourceType][sourceIndex].name)
							);
						}

						for(var moduleIndex = 0; moduleIndex < modules.length; moduleIndex++){
							if(typeof(modules[moduleIndex]) == "undefined"){continue;}

							var module = modules[moduleIndex];
							var id = createID(5);

							$("<div></div>").attr("class", "window-add-channel").append(
								$("<div></div>").attr("class", "window-add-channel-name").append(
									$("<input type='checkbox'/>").attr({
										"id": id,
										"disabled": module.channel.length <= 0 ? true : false
									}).bind("click", function(){
										$(this).closest(".window-add-channel-name").next(".window-add-channel-table").find("> .window-add-channel-name input:not(:disabled)").attr("checked", $(this).attr("checked") ? true : false).each(function(){
											$(this).triggerHandler("click");
										});
									}).bind("click.childrenCheck", function(){
										var $name = $(this).closest(".window-add-channel-name");
										var $table = $name.next(".window-add-channel-table");
										var $inputs = $table.find("> .window-add-channel-name input:not(:disabled)");

										if($inputs.length <= 0){
											$(this).attr("disabled", true);
										}
										else{
											$(this).attr("disabled", false);

											if($inputs.filter(":not(:checked)").length <= 0){
												$(this).attr("checked", true);
											}
											else{
												$(this).attr("checked", false);
											}
										}
									})
								).append(
									$("<label></label>").attr("for", id).css("fontWeight", "bold").append((function(){
										if(!module.modelName){
											return module.nickname;
										}
										else{
											return (module.manufacturer != "" ? module.manufacturer + " " : "") + module.modelName + (module.nickname != "" ? "(" + module.nickname + ")" : "");
										}
									})())
								)
							).append((function(){
								var $retObj = $("<div></div>").attr("class", "window-add-channel-table");

								if((module.type == "0" || module.type == "2") && module.channel.length > 0){
									var channelType = "";

									for(var i = 0; i < module.channel.length; i++){
										var $channel = $(module.channel[i]);

										var splitArray = $channel.attr("name").match(/(\D+)(\d+)/);

										var typeName = {
											"DI": "DI",
											"DIC": "<?=$lang['DASHBOARD']['ADD_CHANNEL']['DI_COUNTER'];?>",
											"DO": "DO",
											"DOC": "<?=$lang['DASHBOARD']['ADD_CHANNEL']['DO_COUNTER'];?>",
											"AI": "AI",
											"AO": "AO",
											"CI": "Discrete Input",
											"CO": "Coil Output",
											"RI": "Input Register",
											"RO": "Holding Register",
											"IR": "<?=$lang['DASHBOARD']['ADD_CHANNEL']['INTERNAL_REGISTER'];?>"
										}[splitArray[1]];

										var channelName = {
											"DI": "DI%channel%",
											"DIC": "<?=$lang['DASHBOARD']['DI_COUNTER_WITH_NO'];?>",
											"DO": "DO%channel%",
											"DOC": "<?=$lang['DASHBOARD']['DO_COUNTER_WITH_NO'];?>",
											"AI": "AI%channel%",
											"AO": "AO%channel%",
											"CI": "Discrete Input %channel%",
											"CO": "Coil Output %channel%",
											"RI": "Input Register %channel%",
											"RO": "Holding Register %channel%",
											"IR": "<?=$lang['DASHBOARD']['INTERNAL_REGISTER_WITH_NO'];?>"
										}[splitArray[1]].replace("%channel%", splitArray[2]);

										var $table, $row, counter;

										if(splitArray[1] != channelType){
											var id = createID(5);

											$("<div></div>").attr("class", "window-add-channel-name").append(
												$("<input type='checkbox'/>").attr({
													"id": id
												}).bind("click", function(){
													$(this).closest(".window-add-channel-name").next(".window-add-channel-table").find("input:not(:disabled)").attr("checked", $(this).attr("checked") ? true : false);
												}).bind("click.childrenCheck", function(){
													var $name = $(this).closest(".window-add-channel-name");
													var $table = $name.next(".window-add-channel-table");
													var $inputs = $table.find("input:not(:disabled)");

													if($inputs.length <= 0){
														$(this).attr("disabled", true);
													}
													else{
														$(this).attr("disabled", false);

														if($inputs.filter(":not(:checked)").length <= 0){
															$(this).attr("checked", true);
														}
														else{
															$(this).attr("checked", false);
														}
													}

													var $table = $(this).closest(".window-add-channel-table");
													var $name = $table.prev(".window-add-channel-name");
													$name.find("input").each(function(){
														$(this).triggerHandler("click.childrenCheck");
													});
												})
											).append(
												$("<label></label>").attr("for", id).text(typeName)
											).appendTo($retObj);

											$table = $("<div></div>").attr("class", "window-add-channel-table").appendTo($retObj);

											counter = 0;

											channelType = splitArray[1];
										}
										else{
											counter++;
										}

										if(counter % 4 == 0){
											$row = $("<div></div>").attr("class", "window-add-channel-row").appendTo($table);
										}

										var id = createID(5);
										var $option = $("#window-add-channel-selector > option:selected");
										var $input = $("<input type='checkbox'/>").attr({
											"id": id,
											"checked": $("#channle-list-table-body > tr[account_uid='" + accountUID + "'][device_uid='" + deviceUID + "'][module_uid='" + module.uid + "'][channel='" + $channel.attr("name") + "']").length > 0,
											"disabled": $channel.attr("available") != "1",
											"is_channel": true,

											"account_uid": accountUID,
											"device_uid": deviceUID,
											"module_uid": module.uid,
											"channel": $channel.attr("name"),
											"nickname": $channel.attr("nickname"),
											"shortName": $channel.attr("nickname") || $channel.attr("name"),
											"unit": $channel.attr("unit")
										}).bind("click", function(){
											var $table = $(this).closest(".window-add-channel-table");
											var $name = $table.prev(".window-add-channel-name");

											$name.find("input").each(function(){
												$(this).triggerHandler("click.childrenCheck");
											});
										});

										$("<div></div>").attr("class", "window-add-channel-cell").append(
											$input
										).append(
											$("<label></label>").attr("for", id).text(channelName + ($channel.attr("nickname") ? "(" + $channel.attr("nickname") + ")" : ""))
										).appendTo($row);
									}
								}
								else if(module.type == "1"){// Power Meter
									var $table, $table2, $row, counter;

									var $loops = $(module.channel);
									for(var loopIndex = 0; loopIndex < $loops.length; loopIndex++){
										var $loop = $($loops[loopIndex]);
										var id = createID(5);

										$("<div></div>").attr("class", "window-add-channel-name").append(
											$("<input type='checkbox'/>").attr("id", id).bind("click", function(){
												$(this).closest(".window-add-channel-name").next(".window-add-channel-table").find("input:not(:disabled)").attr("checked", $(this).attr("checked") ? true : false);
											}).bind("click.childrenCheck", function(){
												var $name = $(this).closest(".window-add-channel-name");
												var $table = $name.next(".window-add-channel-table");
												var $inputs = $table.find("input:not(:disabled)");

												if($inputs.length <= 0){
													$(this).attr("disabled", true);
												}
												else{
													$(this).attr("disabled", false);

													if($inputs.filter(":not(:checked)").length <= 0){
														$(this).attr("checked", true);
													}
													else{
														$(this).attr("checked", false);
													}
												}

												var $table = $(this).closest(".window-add-channel-table");
												var $name = $table.prev(".window-add-channel-name");
												$name.find("input").each(function(){
													$(this).triggerHandler("click.childrenCheck");
												});
											})
										).append(
											$("<label></label>").attr("for", id).text("<?=$lang['DASHBOARD']['LOOP_WITH_NO'];?>".replace("%loop%", loopIndex + 1) + ($loop.attr("nickname") ? "(" + $loop.attr("nickname") + ")" : ""))
										).appendTo($retObj);

										$table = $("<div></div>").attr("class", "window-add-channel-table").appendTo($retObj);

										var $phases = $loop.children();
										for(var phaseIndex = 0; phaseIndex < $phases.length; phaseIndex++){
											var $phase = $($phases[phaseIndex]);
											var id = createID(5);

											if(module.phase == 3){
												$("<div></div>").attr("class", "window-add-channel-name").append(
													$("<input type='checkbox'/>").attr({
														"id": id
													}).bind("click", function(){
														$(this).closest(".window-add-channel-name").next(".window-add-channel-table").find("input:not(:disabled)").attr("checked", $(this).attr("checked") ? true : false);
													}).bind("click.childrenCheck", function(){
														var $name = $(this).closest(".window-add-channel-name");
														var $table = $name.next(".window-add-channel-table");
														var $inputs = $table.find("input:not(:disabled)");

														if($inputs.length <= 0){
															$(this).attr("disabled", true);
														}
														else{
															$(this).attr("disabled", false);

															if($inputs.filter(":not(:checked)").length <= 0){
																$(this).attr("checked", true);
															}
															else{
																$(this).attr("checked", false);
															}
														}

														var $table = $(this).closest(".window-add-channel-table");
														var $name = $table.prev(".window-add-channel-name");
														$name.find("input").each(function(){
															$(this).triggerHandler("click.childrenCheck");
														});
													})
												).append(
													$("<label></label>").attr("for", id).text(["<?=$lang['DASHBOARD']['PHASE_A'];?>", "<?=$lang['DASHBOARD']['PHASE_B'];?>", "<?=$lang['DASHBOARD']['PHASE_C'];?>", "<?=$lang['DASHBOARD']['TOTAL_AVERAGE'];?>"][phaseIndex] + ($phase.attr("nickname") ? "(" + $phase.attr("nickname") + ")" : ""))
												).appendTo($table || $retObj);

												$table2 = $("<div></div>").attr("class", "window-add-channel-table").appendTo($table || $retObj);
											}

											counter = 0;

											var $channels = $phase.children();
											for(var i = 0; i < $channels.length; i++){
												var $channel = $($channels[i]);

												if(counter++ % 4 == 0){
													$row = $("<div></div>").attr("class", "window-add-channel-row").appendTo($table2 || $table || $retObj);
												}

												var id = createID(5);
												var channel = (loopIndex + 1) + "-" + (phaseIndex + 1) + "-" + $channel.attr("name");
												var $input = $("<input type='checkbox'/>").attr({
													"id": id,
													"checked": $("#channle-list-table-body > tr[account_uid='" + accountUID + "'][device_uid='" + deviceUID + "'][module_uid='" + module.uid + "'][channel='" + channel + "']").length > 0,
													"disabled": $channel.attr("available") != "1",
													"is_channel": true,

													"account_uid": accountUID,
													"device_uid": deviceUID,
													"module_uid": module.uid,
													"channel": channel,
													"nickname": $phase.attr("nickname"),
													"_loop": module.loop,
													"phase": module.phase,
													"shortName": ($phase.attr("nickname") ? $phase.attr("nickname") + " > " : "") + energyTypes[$channel.attr("name")],
													"unit": energyUnits[$channel.attr("name")]
												}).bind("click.parentCheck", function(){
													var $table = $(this).closest(".window-add-channel-table");
													var $name = $table.prev(".window-add-channel-name");

													$name.find("input").each(function(){
														$(this).triggerHandler("click.childrenCheck");
													});
												});

												$("<div></div>").attr("class", "window-add-channel-cell").append(
													$input
												).append(
													$("<label></label>").attr("for", id).text(energyTypes[$channel.attr("name")])
												).appendTo($row);
											}
										}
									}
								}

								return $retObj;
							})()).appendTo($container);
						}
					}
				}
			}

			// Internal Register
			var $irs = $(data).find("list > module[interface='IR'] > *");
			if($irs.length > 0){
				$container.append(
					$("<div></div>").attr("class", "window-add-channel-interface").text("<?=$lang['DASHBOARD']['ADD_CHANNEL']['OTHER'];?>")
				);

				var id = createID(5);
				$("<div></div>").attr("class", "window-add-channel").append(
					$("<div></div>").attr("class", "window-add-channel-name").append(
						$("<input type='checkbox'/>").attr("id", id).bind("click", function(){
							$(this).closest(".window-add-channel-name").next(".window-add-channel-table").find("input:not(:disabled)").attr("checked", $(this).attr("checked") ? true : false).each(function(){
								$(this).triggerHandler("click");
							});
						}).bind("click.childrenCheck", function(){
							var $name = $(this).closest(".window-add-channel-name");
							var $table = $name.next(".window-add-channel-table");
							var $inputs = $table.find("input:not(:disabled)");

							if($inputs.length <= 0){
								$(this).attr("disabled", true);
							}
							else{
								$(this).attr("disabled", false);

								if($inputs.filter(":not(:checked)").length <= 0){
									$(this).attr("checked", true);
								}
								else{
									$(this).attr("checked", false);
								}
							}
						})
					).append(
						$("<label></label>").attr("for", id).css("fontWeight", "bold").text("<?=$lang['DASHBOARD']['ADD_CHANNEL']['INTERNAL_REGISTER'];?>")
					)
				).append((function(){
					var $retObj = $("<div></div>").attr("class", "window-add-channel-table");

					var $table = $("<div></div>").attr("class", "window-add-channel-table").appendTo($retObj);

					for(var i = 0; i < $irs.length; i++){
						var $ir = $($irs[i]);

						var splitArray = $ir.attr("name").match(/\D+(\d+)/);
						var irName = "<?=$lang['DASHBOARD']['INTERNAL_REGISTER_WITH_NO'];?>".replace("%channel%", splitArray[1]);

						var $row;
						if(i % 4 == 0){
							$row = $("<div></div>").attr("class", "window-add-channel-row").appendTo($table);
						}

						var id = createID(5);
						$("<div></div>").attr("class", "window-add-channel-cell").append(
							$("<input type='checkbox'/>").attr({
								"id": id,
								"checked": $("#channle-list-table-body > tr[account_uid='" + accountUID + "'][device_uid='" + deviceUID + "'][module_uid='ir'][channel='" + $ir.attr("name") + "']").length > 0,
								"disabled": $ir.attr("available") != "1",
								"is_channel": true,

								"account_uid": accountUID,
								"device_uid": deviceUID,
								"module_uid": "ir",
								"channel": $ir.attr("name"),
								"nickname": $ir.attr("nickname"),
								"shortName": $ir.attr("nickname") || $ir.attr("name"),
								"unit": $ir.attr("unit")
							}).bind("click", function(){
								var $table = $(this).closest(".window-add-channel-table").parent(".window-add-channel-table");
								var $name = $table.prev(".window-add-channel-name");

								$name.find("input").each(function(){
									$(this).triggerHandler("click.childrenCheck");
								});
							})
						).append(
							$("<label></label>").attr("for", id).text(irName + ($ir.attr("nickname") ? "(" + $ir.attr("nickname") + ")" : ""))
						).appendTo($row);
					}

					return $retObj;
				})()).appendTo($container);
			}

			$container.appendTo("#window-add-channel-containers");

			// Parent check
			$(".window-add-channel > .window-add-channel-table > .window-add-channel-table .window-add-channel-row:nth-child(1) .window-add-channel-cell:nth-child(1) input").each(function(){
				$(this).triggerHandler("click");
			});
		},
		error: function(jqXHR, textStatus, errorThrown){
			if(jqXHR.status === 0 && textStatus != "timeout"){ return; }// not include timeout

			alert(jqXHR.responseText);
		},
		complete: function(){
			$("#window-add-channel-loader").hide();
		}
	});
}

function filterModule(keyword){
	var $option = $("#window-add-channel-selector option:selected");
	var $container = $("#" + $option.attr("account_uid") + "-" + $option.attr("device_uid"));

	$container.find("> *").hide();

	$container.find("> .window-add-channel > .window-add-channel-name label").each(function(){
		var regex = new RegExp(keyword, "i");
		if($(this).text().search(regex) >= 0){
			var $module = $(this).closest(".window-add-channel");
			$module.show();
			$module.prevAll(".window-add-channel-interface").first().show();
		}
	});

	if($("#window-add-channel-filter-input").val().length > 0 && $container.find("> *").filter(":visible").length <= 0){
		$("#window-add-module-no-match").show();
	}
	else{
		$("#window-add-module-no-match").hide();
	}
}

function setGridWidth(width){
	gridstack.setGridWidth(width, true);

	//$('.grid-stack').css("width", ((cellHeight + verticalMargin) * width) + "px");

    var width = width;
	var style = "";

    for(var i = 1; i <= width; i++) {
        style += ".grid-stack-item[data-gs-width='" + i + "'] { width: " + ((100 / width) * i) + "%; }";
        style += ".grid-stack-item[data-gs-x='" + i + "'] { left: " + ((100 / width) * i) + "%; }";
        style += ".grid-stack-item[data-gs-min-width='" + i + "'] { min-width: " + ((100 / width) * i) + "%; }";
        style += ".grid-stack-item[data-gs-max-width='" + i + "'] { max-width: " + ((100 / width) * i) + "%; }";
    }

	$("<style>" + style + "</style>" ).appendTo("head");
}

function showAddWidgetWindow(title, callback){
	$("#window-add-widget div.popup-title").text(title);

	$("#widget-title-text").val("");
	$("#widget-title-size").val(15);
	$("#widget-description").val("");

	showWindow($("#window-add-widget"), function(result){
		var closeWindow = callback(result);

		if(closeWindow !== false){
			hideWindow();
		}

		return closeWindow;
	});
}

function createWidget(uid, x, y, width, height, autoPosition){
	var $wrapper = $("<div></div>").append(
		$("<div></div>").addClass("grid-stack-item-content").attr("uid", uid).append(
			$("<div></div>").addClass("widget-title")
		).append(
			$("<div></div>").addClass("widget-content")
		).append(
			$("<div></div>").addClass("item-setting").append(
				$("<div></div>").addClass("item-setting-menu").append(
					$("<div></div>").append(
						$(createSVGIcon("image/ics.svg", "edit"))
					).append("&nbsp;").append(
						$("<span></span>").text("<?=$lang['DASHBOARD']['EDIT'];?>")
					).bind("click", function(){
						var typePrev = null;
						var that = this;

						showAddWidgetWindow("<?=$lang['DASHBOARD']['CREATE_WIDGET']['EDIT_WIDGET'];?>", function(result){
							if(result == "ok"){
								var accountUID = $("#dashboard-switch-handler").attr("account_uid");
								var dashboardUID = $("#dashboard-switch-handler").attr("dashboard_uid");
								var widgetUID = $(that).closest(".grid-stack-item-content").attr("uid");
								var type = $("#widget-type div.widget-type.active").attr("widget-type");

								// Channel number check
								var $channelRows = $("#channle-list-table-body > tr");

								if(window.widget[type].minChannelNumber != null && $channelRows.length < window.widget[type].minChannelNumber){
									popupErrorWindow("<?=$lang['DASHBOARD']['CREATE_WIDGET']['POPUP']['CHANNEL_NUMBER_CAN_NOT_LESS_THEN_X'];?>".replace("%number%", window.widget[type].minChannelNumber));
									$("#menu-container > div.menu-item[target='channel']").triggerHandler("click");
									return false;
								}

								if(window.widget[type].maxChannelNumber != null && $channelRows.length > window.widget[type].maxChannelNumber){
									popupErrorWindow("<?=$lang['DASHBOARD']['CREATE_WIDGET']['POPUP']['CHANNEL_NUMBER_CAN_NOT_GREATER_THEN_X'];?>".replace("%number%", window.widget[type].maxChannelNumber));
									$("#menu-container > div.menu-item[target='channel']").triggerHandler("click");
									return false;
								}

								var settings = {
									title: {
										text: $("#widget-title-text").val(),
										size: parseFloat($("#widget-title-size").val())
									},
									description: $("#widget-description").val(),
									type: type
								};

								var channels = {};

								Object.defineProperty(channels, "order", {
									enumerable : false,
									value : []
								});

								$channelRows.each(function(){
									var accountUID = $(this).attr("account_uid");
									var deviceUID = $(this).attr("device_uid");
									var moduleUID = $(this).attr("module_uid");
									var channel = $(this).attr("channel");
									channels[accountUID] = channels[accountUID] || {};
									channels[accountUID][deviceUID] = channels[accountUID][deviceUID] || {};
									channels[accountUID][deviceUID][moduleUID] = channels[accountUID][deviceUID][moduleUID] || {};
									channels[accountUID][deviceUID][moduleUID][channel] = {
										shortName: $(this).attr("shortName"),
										fullName: $(this).attr("fullName"),
										unit: $(this).attr("unit"),
										icon: $(this).attr("icon") || null
									};

									channels.order.push({
										accountUID: accountUID,
										deviceUID: deviceUID,
										moduleUID: moduleUID,
										channel: channel
									});
								});

								// Call channel update event
								callChannelUpdateEvent();

								// Call setting saved event
								var errorMessage = window.widget[type].settingSaved(settings, channels);
								if(errorMessage){
									popupErrorWindow(errorMessage);
									return false;
								}

								// Save settings & channels to database
								$("#wait-loader").show();
								$.ajax({
									url: "dashboard_ajax.php?act=edit_widget<?=$token?>",
									type: "POST",
									dataType: "xml",
									data: {
										"uid": widgetUID,// Widget UID
										"settings": JSON.stringify(settings),
										"channels": (function(){
											var retStr = "";

											$channelRows.each(function(index){
												retStr += $(this).attr("account_uid") + "||" + $(this).attr("device_uid") + "||" + $(this).attr("module_uid") + "||" + $(this).attr("channel") + "||" + (index + 1) + "||" + ($(this).attr("icon") || "") + "@@";
											});

											return retStr.substring(0, retStr.length - 2);
										})()
									},
									success: function(data, textStatus, jqXHR){
										// Save setting
										window.accounts[accountUID].dashboards[dashboardUID].widgets[widgetUID].settings = settings;

										// Save channels
										window.accounts[accountUID].dashboards[dashboardUID].widgets[widgetUID].channels = channels;

										trimChannelData();

										// Update widget
										var $widget = $(".grid-stack-item-content[uid='" + widgetUID + "']");
										var $widgetTitle = $widget.find("> div.widget-title");
										var $widgetContent = $widget.find("> div.widget-content");

										$widgetTitle.text(window.accounts[accountUID].dashboards[dashboardUID].widgets[widgetUID].settings.title.text);

										if(!window.accounts[accountUID].dashboards[dashboardUID].widgets[widgetUID].settings.title.text){
											$widgetContent.css("height", "100%");
										}
										else{
											$widgetTitle.css({
												"fontSize": window.accounts[accountUID].dashboards[dashboardUID].widgets[widgetUID].settings.title.size + "px",
												"lineHeight": (window.accounts[accountUID].dashboards[dashboardUID].widgets[widgetUID].settings.title.size + 5) + "px"
											});
											$widgetContent.css("height", "calc(100% - " + (window.accounts[accountUID].dashboards[dashboardUID].widgets[widgetUID].settings.title.size + 5) + "px)");
										}

										// Callback event
										if(typePrev == type){
											window.widget[window.accounts[accountUID].dashboards[dashboardUID].widgets[widgetUID].settings.type].widgetUpdated($widgetContent, window.accounts[accountUID].dashboards[dashboardUID].widgets[widgetUID].settings, window.accounts[accountUID].dashboards[dashboardUID].widgets[widgetUID].channels);
										}
										else{
											window.widget[window.accounts[accountUID].dashboards[dashboardUID].widgets[widgetUID].settings.type].widgetCreated($widgetContent.empty(), window.accounts[accountUID].dashboards[dashboardUID].widgets[widgetUID].settings, window.accounts[accountUID].dashboards[dashboardUID].widgets[widgetUID].channels);
										}
									},
									error: function(jqXHR, textStatus, errorThrown){
										if(jqXHR.status === 0 && textStatus != "timeout"){ return; }// not include timeout

										alert(jqXHR.responseText);
									},
									complete: function(){
										$("#wait-loader").hide();
									}
								});
							}
							else if(result == "cancel"){}
						});

						// Clear
						$("#channle-list-table-body").empty();

						// Fill out value
						var $widget = $(this).closest("div.grid-stack-item-content");
						var accountUID = $("#dashboard-switch-handler").attr("account_uid");
						var dashboardUID = $("#dashboard-switch-handler").attr("dashboard_uid");
						var widgetUID = $widget.attr("uid");
						var widget = window.accounts[accountUID].dashboards[dashboardUID].widgets[widgetUID];

						$("#widget-title-text").val(widget.settings.title.text);
						$("#widget-title-size").val(widget.settings.title.size);
						$("#widget-description").val(widget.settings.description);
						$("#widget-setting-container").attr("uid", widgetUID);

						var channels = widget.channels;
						for(var i = 0; i < channels.order.length; i++){
							var accountUID = channels.order[i].accountUID;
							var deviceUID = channels.order[i].deviceUID;
							var moduleUID = channels.order[i].moduleUID;
							var channel = channels.order[i].channel;

							$("#channle-list-table-body").append(
								createChannelRow(accountUID, deviceUID, moduleUID, channel, channels[accountUID][deviceUID][moduleUID][channel].shortName, channels[accountUID][deviceUID][moduleUID][channel].fullName, channels[accountUID][deviceUID][moduleUID][channel].unit, channels[accountUID][deviceUID][moduleUID][channel].icon)
							);
						}

						showChannelNoExist();
						showAddChannelButton();

						$("#menu-container > div.menu-item[target='type']").triggerHandler("click");
						$("#widget-type > div[widget-type='" + widget.settings.type + "']").triggerHandler("mousedown", widget.settings);

						typePrev = widget.settings.type;
					})
				).append(
					$("<div></div>").append(
						$(createSVGIcon("image/ics.svg", "file_copy"))
					).append("&nbsp;").append(
						$("<span></span>").text("<?=$lang['DASHBOARD']['COPY']?>")
					).bind("click", function(){
						var accountUID = $("#dashboard-switch-handler").attr("account_uid");
						var dashboardUID = $("#dashboard-switch-handler").attr("dashboard_uid");
						var widgetUID = $(this).closest(".grid-stack-item-content").attr("uid");;

						var settings = $.extend(true, {}, window.accounts[accountUID].dashboards[dashboardUID].widgets[widgetUID].settings);
						var channels = $.extend(true, {}, window.accounts[accountUID].dashboards[dashboardUID].widgets[widgetUID].channels);

						Object.defineProperty(channels, "order", {
							enumerable : false,
							value : $.extend(true, [], window.accounts[accountUID].dashboards[dashboardUID].widgets[widgetUID].channels.order)
						});

						addWidget(accountUID, dashboardUID, settings, channels, window.accounts[accountUID].dashboards[dashboardUID].widgets[widgetUID].width, window.accounts[accountUID].dashboards[dashboardUID].widgets[widgetUID].height);
					})
				).append(
					$("<div></div>").append(
						$(createSVGIcon("image/ics.svg", "delete"))
					).append("&nbsp;").append(
						$("<span></span>").text("<?=$lang['DASHBOARD']['REMOVE'];?>")
					).bind("click", function(){
						var that = this;
						popupConfirmWindow("<?=$lang['DASHBOARD']['POPUP']['ARE_YOU_SURE_REMOVE_WIDGET'];?>", function(){
							$("#wait-loader").show();

							var accountUID = $("#dashboard-switch-handler").attr("account_uid");
							var dashboardUID = $("#dashboard-switch-handler").attr("dashboard_uid");
							var widgetUID = $(that).closest(".grid-stack-item-content").attr("uid");

							$.ajax({
								url: "dashboard_ajax.php?act=remove_widget<?=$token?>",
								type: "POST",
								dataType: "xml",
								data: {
									"uid": widgetUID
								},
								success: function(data, textStatus, jqXHR){
									// Remove widget
									var $widget = $(".grid-stack-item-content[uid='" + widgetUID + "']");
									var $widgetContent = $widget.find("> div.widget-content");

									window.widget[window.accounts[accountUID].dashboards[dashboardUID].widgets[widgetUID].settings.type].widgetRemoved($widgetContent, window.accounts[accountUID].dashboards[dashboardUID].widgets[widgetUID].settings, window.accounts[accountUID].dashboards[dashboardUID].widgets[widgetUID].channels);

									delete window.accounts[accountUID].dashboards[dashboardUID].widgets[widgetUID];
									trimChannelData();

									gridstack.removeWidget($widget.parent());
								},
								error: function(jqXHR, textStatus, errorThrown){
									if(jqXHR.status === 0 && textStatus != "timeout"){ return; }// not include timeout

									alert(jqXHR.responseText);
								},
								complete: function(){
									$("#wait-loader").hide();
								}
							});
						}, function(){
						});
					})
				)
			).append(
				$("<div></div>").addClass("item-setting-icon").append(
					$(createSVGIcon("image/ics.svg", "settings"))
				).bind("click", function(){
					if(!$(this).hasClass("active")){
						$(".grid-stack-item-content").removeClass("active");
						$(this).closest(".grid-stack-item-content").addClass("active");
						event.stopPropagation();
					}
				})
			)
		)
	);

	if(guestLevel !== undefined){
		$wrapper.find("div.item-setting").hide();
		$wrapper.attr('data-gs-no-resize', "true");
		$wrapper.attr('data-gs-no-move', "true");
	}

	gridstack.addWidget($wrapper, x, y, width, height, autoPosition);
	gridstack.minWidth($wrapper, 3);
	gridstack.minHeight($wrapper, 2);

	return $wrapper.find("div.grid-stack-item-content");
}

function clearDashboard(){
	var accountUID = $("#dashboard-switch-handler").attr("account_uid");
	var dashboardUID = $("#dashboard-switch-handler").attr("dashboard_uid");

	var nodes = gridstack.grid.nodes;
	for(var i = 0; i < nodes.length; i++){
		var $widget = $(nodes[i].el[0]).find("> .grid-stack-item-content[uid]");
		var $widgetContent = $widget.find("> div.widget-content");

		var widgetUID = $widget.attr("uid");
		var widget = window.accounts[accountUID].dashboards[dashboardUID].widgets[widgetUID];

		// Remove widget event
		window.widget[widget.settings.type].widgetRemoved($widgetContent, widget.settings, widget.channels);
	}

	ignoreChangeEvent = true;
	gridstack.removeAll();
	ignoreChangeEvent = false;

	$("body").removeClass("dark");
}

function saveWidgetPosition(items){
//	if(typeof(items) == "undefined"){
//		items = [];
//
//		$(".grid-stack > .grid-stack-item:not(.grid-stack-placeholder)").each(function(){
//			items.push({
//				el: this
//			});
//		});
//	}

	var data = "";

    for (var i = 0; i < items.length; i++) {
		var $wrapper = $(items[i].el);
		var x = $wrapper.attr("data-gs-x");
		var y = $wrapper.attr("data-gs-y");
		var width = $wrapper.attr("data-gs-width");
		var height = $wrapper.attr("data-gs-height");

		var accountUID = $("#dashboard-switch-handler").attr("account_uid");
		var dashboardUID = $("#dashboard-switch-handler").attr("dashboard_uid");
		var widgetUID = $wrapper.find("> .grid-stack-item-content[uid]").attr("uid");
		var widget = window.accounts[accountUID].dashboards[dashboardUID].widgets[widgetUID];

		widget.x = x;
		widget.y = y;
		widget.width = width;
		widget.height = height;
		data += widgetUID + "|" + x + "|" + y + "|" + width + "|" + height + ",";
    }

	data = data.substring(0, data.length - 1);

	$.ajax({
		url: "dashboard_ajax.php?act=update_widget_position<?=$token?>",
		type: "POST",
		dataType: "xml",
		data: {
			"data": data
		},
		success: function(data, textStatus, jqXHR){

		},
		error: function(jqXHR, textStatus, errorThrown){
			if(jqXHR.status === 0 && textStatus != "timeout"){ return; }// not include timeout

			alert(jqXHR.responseText);
		}
	});
}

function copyDashboard(){
	var accountUID = $("#dashboard-switch-handler").attr("account_uid");
	var dashboardUID = $("#dashboard-switch-handler").attr("dashboard_uid");

	$("#wait-loader").show();

	return $.ajax({
		url: "dashboard_ajax.php?act=copy_dashboard<?=$token?>",
		type: "POST",
		dataType: "xml",
		data: {
			"uid": dashboardUID
		},
		success: function(data, textStatus, jqXHR){
			var dashboardNewUID = $(data).find("result > dashboard").attr("new-uid");

			window.accounts[accountUID].dashboards[dashboardNewUID] = $.extend(true, {}, window.accounts[accountUID].dashboards[dashboardUID]);
			window.accounts[accountUID].dashboards[dashboardNewUID].asDefault = false;
			window.accounts[accountUID].dashboards[dashboardNewUID].name += "(<?=$lang['DASHBOARD']['COPY']?>)";

			for(var widgetUID in window.accounts[accountUID].dashboards[dashboardNewUID].widgets){
				var widgetNewUID = $(data).find("result > dashboard > widget[uid='" + widgetUID + "']").attr("new-uid");

				window.accounts[accountUID].dashboards[dashboardNewUID].widgets[widgetNewUID] = window.accounts[accountUID].dashboards[dashboardNewUID].widgets[widgetUID];

				Object.defineProperty(window.accounts[accountUID].dashboards[dashboardNewUID].widgets[widgetNewUID].channels, "order", {
					enumerable : false,
					value : $.extend(true, [], window.accounts[accountUID].dashboards[dashboardUID].widgets[widgetUID].channels.order)
				});

				delete window.accounts[accountUID].dashboards[dashboardNewUID].widgets[widgetUID];
			}

			$("#dsahboard-switch-selector").append(
				createSwitchOption(window.accounts[accountUID].dashboards[dashboardNewUID].name, accountUID, dashboardNewUID)
			);

			updateSwitchOptionOrder();

			$("#dsahboard-switch-selector .dsahboard-switch-option[account_uid='" + accountUID + "'][dashboard_uid='" + dashboardNewUID + "']").triggerHandler("click");
			$("#dashboard-container").show();
		},
		error: function(jqXHR, textStatus, errorThrown){
			if(jqXHR.status === 0 && textStatus != "timeout"){ return; }// not include timeout

			alert(jqXHR.responseText);
		},
		complete: function(){
			$("#wait-loader").hide();
		}
	});
}

function addWidget(accountUID, dashboardUID, settings, channels, width, height){
	$("#wait-loader").show();

	return $.ajax({
		url: "dashboard_ajax.php?act=add_widget<?=$token?>",
		type: "POST",
		dataType: "xml",
		data: {
			"uid": dashboardUID,// Dashboard UID
			"settings": JSON.stringify(settings),
			"channels": (function(){
				var retStr = "";

				for(var i = 0; i < channels.order.length; i++){
					var accountUID = channels.order[i].accountUID;
					var deviceUID = channels.order[i].deviceUID;
					var moduleUID = channels.order[i].moduleUID;
					var channel = channels.order[i].channel;

					retStr += accountUID + "||" + deviceUID + "||" + moduleUID + "||" + channel + "||" + (i + 1) + "||" + (channels[accountUID][deviceUID][moduleUID][channel].icon || "") + "@@";
				}

				return retStr.substring(0, retStr.length - 2);
			})()
		},
		success: function(data, textStatus, jqXHR){
			widgetUID = $(data).find("result").attr("uid");
			window.accounts[accountUID].dashboards[dashboardUID].widgets[widgetUID] = {};

			// Save setting
			window.accounts[accountUID].dashboards[dashboardUID].widgets[widgetUID].settings = settings;

			// Save channels
			window.accounts[accountUID].dashboards[dashboardUID].widgets[widgetUID].channels = channels;

			trimChannelData();

			// Create widget
			var $widget = createWidget(widgetUID, 0, 0, width || window.widget[window.accounts[accountUID].dashboards[dashboardUID].widgets[widgetUID].settings.type].defaultWidth || 8, height || window.widget[window.accounts[accountUID].dashboards[dashboardUID].widgets[widgetUID].settings.type].defaultHeight || 6, true);
			var $widgetTitle = $widget.find("> div.widget-title");
			var $widgetContent = $widget.find("> div.widget-content");

			window.accounts[accountUID].dashboards[dashboardUID].widgets[widgetUID].x = parseInt($widget.parent().attr("data-gs-x"), 10);
			window.accounts[accountUID].dashboards[dashboardUID].widgets[widgetUID].y = parseInt($widget.parent().attr("data-gs-y"), 10);
			window.accounts[accountUID].dashboards[dashboardUID].widgets[widgetUID].width = parseInt($widget.parent().attr("data-gs-width"), 10);
			window.accounts[accountUID].dashboards[dashboardUID].widgets[widgetUID].height = parseInt($widget.parent().attr("data-gs-height"), 10);

			$widgetTitle.text(window.accounts[accountUID].dashboards[dashboardUID].widgets[widgetUID].settings.title.text);

			if(!window.accounts[accountUID].dashboards[dashboardUID].widgets[widgetUID].settings.title.text){
				$widgetContent.css("height", "100%");
			}
			else{
				$widgetTitle.css({
					"fontSize": window.accounts[accountUID].dashboards[dashboardUID].widgets[widgetUID].settings.title.size + "px",
					"lineHeight": (window.accounts[accountUID].dashboards[dashboardUID].widgets[widgetUID].settings.title.size + 5) + "px"
				});
				$widgetContent.css("height", "calc(100% - " + (window.accounts[accountUID].dashboards[dashboardUID].widgets[widgetUID].settings.title.size + 5) + "px)");
			}

			// Callback event
			window.widget[window.accounts[accountUID].dashboards[dashboardUID].widgets[widgetUID].settings.type].widgetCreated($widgetContent, window.accounts[accountUID].dashboards[dashboardUID].widgets[widgetUID].settings, window.accounts[accountUID].dashboards[dashboardUID].widgets[widgetUID].channels);
		},
		error: function(jqXHR, textStatus, errorThrown){
			if(jqXHR.status === 0 && textStatus != "timeout"){ return; }// not include timeout

			alert(jqXHR.responseText);
		},
		complete: function(){
			$("#wait-loader").hide();
		}
	});
}

var maxTimestamp = (new Date()).getTime();
var pid, pid2, pid3, ajax = null;

function stopUpdate(){
	clearTimeout(pid);
	clearInterval(pid2);
	clearInterval(pid3);

	if(ajax != null){
		ajax.abort();
		ajax = null;
	}
}

function startUpdate(){
	var date = new Date();
	var second = date.getSeconds();
	var ms = second * 1000 + date.getMilliseconds()
	var startUpdateAfter = ((Math.ceil(second / 5) * 5000) - ms);
	pid = setTimeout(function(){
		updateData();
		pid2 = setInterval(updateData, 5000);
		pid3 = setInterval(trimData, 600000);
	}, startUpdateAfter);
}

function updateData(){
	var date = new Date();
	date.setMilliseconds(0);
	maxTimestamp = date.getTime();
	loadData(maxTimestamp);
}

function getData(){
	var channels = {};
	for(var accountUID in window.channels){
		channels[accountUID] = {};
		for(var deviceUID in window.channels[accountUID]){
			channels[accountUID][deviceUID] = {};
			for(var moduleUID in window.channels[accountUID][deviceUID]){
				channels[accountUID][deviceUID][moduleUID] = {};
				for(var channel in window.channels[accountUID][deviceUID][moduleUID]){
					channels[accountUID][deviceUID][moduleUID][channel] = [];
				}
			}
		}
	}

	return $.ajax({
		url: "dashboard_ajax.php?act=get_data<?=$token?>",
		type: "POST",
		data: "data=" + JSON.stringify(channels),
		cache: false,
		dataType: "xml",
		timeout: 15000
	});
}

function loadData(timestamp){
	if(ajax != null){
		return;
	}

	ajax = getData().done(function(data){
		// Channel data
		var $xmlAccounts = $(data).find("list > data[type='channel'] > account");
		for(var i = 0; i < $xmlAccounts.length; i++){
			var $xmlAccount = $($xmlAccounts[i]);

			var $xmlDevices = $xmlAccount.find("> device");
			for(var j = 0; j < $xmlDevices.length; j++){
				var $xmlDevice = $($xmlDevices[j]);

				var $xmlModules = $xmlDevice.find("> module");
				for(var k = 0; k < $xmlModules.length; k++){
					var $xmlModule = $($xmlModules[k]);

					var $xmlChannels = $xmlModule.find("> channel");
					for(var l = 0; l < $xmlChannels.length; l++){
						var $xmlChannel = $($xmlChannels[l]);

						processChannelData(timestamp, $xmlAccount.attr("uid"), $xmlDevice.attr("uid"), $xmlModule.attr("uid"), $xmlChannel.attr("name"), parseFloat($xmlChannel.text()));
					}
				}
			}
		}

		// Event data
		window.events = {};
		var $xmlAccounts = $(data).find("list > data[type='event'] > account");
		for(var i = 0; i < $xmlAccounts.length; i++){
			var $xmlAccount = $($xmlAccounts[i]);

			var $xmlDevices = $xmlAccount.find("> device");
			for(var j = 0; j < $xmlDevices.length; j++){
				var $xmlDevice = $($xmlDevices[j]);

				var $xmlLogs = $xmlDevice.find("> log");
				for(var k = 0; k < $xmlLogs.length; k++){
					var $xmlLog = $($xmlLogs[k]);

					processEventData($xmlAccount.attr("uid"), $xmlDevice.attr("uid"), $xmlLog.attr("uid"), Date.parse($xmlLog.attr("datetime") + " GMT"), $xmlLog.attr("type"), {
						"deviceNickname": $xmlLog.attr("device_nickname"),
						"message": $xmlLog.text(),
						"path": $xmlLog.attr("path")
					});
				}
			}
		}

		// Call dataUpdated event for every widget
		$(".grid-stack-item-content").each(function(){
			var accountUID = $("#dashboard-switch-handler").attr("account_uid");
			var dashboardUID = $("#dashboard-switch-handler").attr("dashboard_uid");
			var widgetUID = $(this).attr("uid");
			var widget = window.accounts[accountUID].dashboards[dashboardUID].widgets[widgetUID];
			window.widget[widget.settings.type].dataUpdated($(this).find("> div.widget-content"), widget.settings, widget.channels);
		});
	}).always(function(){
		ajax = null;
	});
}

function trimData(){
	// Remove 10 mins ago data
	var date = new Date();
	var timestamp = date.getTime() - 600000;

	for(var accountUID in window.channels){
		for(var deviceUID in window.channels[accountUID]){
			for(var moduleUID in window.channels[accountUID][deviceUID]){
				for(var channel in window.channels[accountUID][deviceUID][moduleUID]){
					var data = channels[accountUID][deviceUID][moduleUID][channel];
					for(var i = 0; i < data.length; i++){
						if(data[i][0] > timestamp){
							data = data.splice(0, i);
							break;
						}
					}
				}
			}
		}
	}
}

function processChannelData(timestamp, accountUID, deviceUID, moduleUID, channel, value){
	if(isNaN(value)){
		value = null;
	}

	try{
		window.channels[accountUID][deviceUID][moduleUID][channel].push([timestamp, value]);
	}
	catch{}
}

function processEventData(accountUID, deviceUID, eventUID, timestamp, type, parameters){
	window.events[accountUID] = window.events[accountUID] || {};
	window.events[accountUID][deviceUID] = window.events[accountUID][deviceUID] || [];
	window.events[accountUID][deviceUID].push({
		uid: eventUID,
		timestamp: timestamp,
		type: type,
		deviceUID: deviceUID,// ?!
		deviceNickname: parameters.deviceNickname,
		message: parameters.message,
		path: parameters.path// Available when type is 1 or 2
	});
}

function trimChannelData(){
	var channels = {};

	for(var accountUID in window.accounts){
		var account = window.accounts[accountUID];

		for(var dashboardUID in account.dashboards){
			var dashboard = account.dashboards[dashboardUID];

			for(var widgetUID in dashboard.widgets){
				var widget = dashboard.widgets[widgetUID];

				$.extend(true, channels, widget.channels);
			}
		}
	}

	for(var accountUID in channels){
		for(var deviceUID in channels[accountUID]){
			for(var moduleUID in channels[accountUID][deviceUID]){
				for(var channel in channels[accountUID][deviceUID][moduleUID]){
					try{
						channels[accountUID][deviceUID][moduleUID][channel] = window.channels[accountUID][deviceUID][moduleUID][channel] || [];
					}
					catch(err){
						channels[accountUID][deviceUID][moduleUID][channel] = [];
					}
				}
			}
		}
	}

	window.channels = channels;
}

function setChannelData(accountUID, deviceUID, moduleUID, channel, value){// sourceType, sourceIndex and moduleIndex no use in local IR
	return $.ajax({
		url: "dashboard_ajax.php?act=set_channel_data<?=$token?>",
		type: "POST",
		data: "account_uid=" + accountUID + "&device_uid=" + deviceUID + "&module_uid=" + moduleUID + "&channel=" + channel + "&value=" + value,
		cache: false,
		dataType: "xml",
		timeout: 7000
	});
}

function addLink(token){
	var link = window.links[token];

	$("#link-settings-table-body").append(
		$("<tr></tr>").append(
			$("<td></td>").append(
				$("<table></table>").attr("class", "link-settings-url-container").append(
					$("<tr></tr>").append(
						$("<td></td>").append(
							$("<input type='text'/>").attr({
								"class": "link-settings-url-text",
								"readonly": true
							}).val(location.href.replace(/#.+$/i, "") + "&token=" + token).bind("click", function(){
								$(this).focus().select();
							})
						)
					).append(
						$("<td></td>").append(
							$("<button></button>").attr("class", "gray").append(
								$(createSVGIcon("image/ics.svg", "file_copy"))
							).bind("click", function(){
								var $input = $(this).closest("tr").find("input.link-settings-url-text").focus().select();

								if(navigator.clipboard){
									navigator.clipboard.writeText($input.val());
								}
								else if(document.execCommand){
									document.execCommand('copy');
								}
							})
						)
					)
				)
			)
		).append(
			$("<td></td>").append(
				$("<div></div>").attr("class", "link-settings-expiration-date-container").append(
					$(createSVGIcon("image/ics.svg", "calendar")).attr("class", "link-settings-expiration-date-calendar-icon")
				).append(
					$("<span></span>").attr("class", "link-settings-expiration-date-text").text((function(){
						if(link.expirationDate == null){
							return "<?=$lang['DASHBOARD']['SHARE_LINK']['SET_EXPIRATION_DATE'];?>";
						}
						else{
							return link.expirationDate.getFullYear() + "-" + padding(link.expirationDate.getMonth() + 1, 2) + "-" + padding(link.expirationDate.getDate(), 2)
						}
					})())
				).append(
					$("<div></div>").attr("class", "link-settings-expiration-date-clear-icon").css("display", link.expirationDate != null ? "block": "none").append(
						$(createSVGIcon("image/ics.svg", "clear"))
					).bind("click", function(event){
						var $that = $(this);

						$("#link-settings-loader").show();

						$.ajax({
							url: "dashboard_ajax.php?act=edit_link",
							type: "POST",
							dataType: "xml",
							data: {
								token: token,
								expiration_date: null
							},
							success: function(data, textStatus, jqXHR){
								$that.closest("div.link-settings-expiration-date-container").find("span.link-settings-expiration-date-text").text("<?=$lang['DASHBOARD']['SHARE_LINK']['SET_EXPIRATION_DATE'];?>");
								$that.hide();
								$(document).triggerHandler("click.calendar");
								window.links[token].expirationDate = null;
							},
							error: function(jqXHR, textStatus, errorThrown){
								if(jqXHR.status === 0 && textStatus != "timeout"){ return; }// not include timeout

								alert(jqXHR.responseText);
							},
							complete: function(){
								$("#link-settings-loader").hide();
							}
						});

						event.stopPropagation();
					})
				).append(createDatePicker(token)).bind("click", function(){
					if($(this).hasClass("active")){
						return;
					}

					var $container = $(this).closest("div.link-settings-expiration-date-container");
					var dateArray = $(this).find("span.link-settings-expiration-date-text").text().match(/(\d{4})-(\d{2})-(\d{2})/);

					$(document).triggerHandler("click.calendar");

					var $calendar = $container.find("div.date-picker");

					if(dateArray){
						$calendar.show().triggerHandler("set", [dateArray[1], dateArray[2], dateArray[3]]);
					}
					else{
						var now = new Date();
						now.setHours(0);
						now.setMinutes(0);
						now.setSeconds(0);
						now.setMilliseconds(0)

						$calendar.show().removeAttr("date").data("date", now).triggerHandler("create", now);
					}
					$calendar.css("inset", "auto");

					$("#link-settings-cover").show();
					$(this).addClass("active");

					if($calendar.offset().top + $calendar.height() > $("#link-settings-cover").offset().top + $("#link-settings-cover").height()){
						$calendar.css("top", "-" + ($calendar.height() + 1) + "px");
					}

					event.stopPropagation();
					event.stopImmediatePropagation();
				})
			)
		).append(
			$("<td></td>").append(
				$("<div></div>").attr("class", "checkbox link-settings-controlable-conatiner").append(
					$("<input type='checkbox'/>").attr({
						"id": "controlable-" + token,
						"name": "controlable-" + token,
						"checked": link.controllable == 0 ? false : true
					}).bind("click", function(event){
						event.preventDefault()

						var $that = $(this);
						var checked = $that.prop("checked");
						$("#link-settings-loader").show();

						$.ajax({
							url: "dashboard_ajax.php?act=edit_link",
							type: "POST",
							dataType: "xml",
							data: {
								token: token,
								permission: checked ? "1" : "0"
							},
							success: function(data, textStatus, jqXHR){
								$that.prop("checked", checked);
								window.links[token].controllable = checked;
							},
							error: function(jqXHR, textStatus, errorThrown){
								if(jqXHR.status === 0 && textStatus != "timeout"){ return; }// not include timeout

								alert(jqXHR.responseText);
							},
							complete: function(){
								$("#link-settings-loader").hide();
							}
						});
					})
				).append(
					$("<label></label>").attr("for", "controlable-" + token)
				)
			)
		).append(
			$("<td></td>").append(
				$("<input type='button'/>").attr("class", "red link-settings-remove-button").val("<?=$lang['DASHBOARD']['SHARE_LINK']['REMOVE'];?>").bind("click", function(){
					var $row = $(this).closest("tr");

					popupConfirmWindow("<?=$lang['DASHBOARD']['SHARE_LINK']['POPUP']['ARE_YOU_SURE_REMOVE_SHARE_LINK'];?>", function(){
						$("#link-settings-loader").show();

						$.ajax({
							url: "dashboard_ajax.php?act=remove_link",
							type: "POST",
							dataType: "xml",
							data: {
								token: token
							},
							success: function(data, textStatus, jqXHR){
								$row.remove();

								if($("#link-settings-table-body > tr").length == 0){
									$("#link-settings-empty-container").show();
									$("#link-settings-add-button").css("marginRight", "");
								}

								delete window.links[token];
							},
							error: function(jqXHR, textStatus, errorThrown){
								if(jqXHR.status === 0 && textStatus != "timeout"){ return; }// not include timeout

								alert(jqXHR.responseText);
							},
							complete: function(){
								$("#link-settings-loader").hide();
							}
						});
					}, function(){
					});
				})
			)
		)
	);

	$("#link-settings-empty-container").hide();
	$("#link-settings-add-button").css("marginRight", "0px");
}

function createDatePicker(token){
	return $("<div></div>").bind("set", function(event, year, month, day){
		var date = new Date(year, month - 1, day);
		$(this).attr("date", date.getTime()).data("date", date).triggerHandler("create", [date]);
	}).bind("prev", function(){
		var date = $(this).data("date");
		date.setDate(1);
		date.setMonth(date.getMonth() - 1);
		$(this).triggerHandler("create", [date]);
	}).bind("next", function(){
		var date = $(this).data("date");
		date.setDate(1);
		date.setMonth(date.getMonth() + 1);
		$(this).triggerHandler("create", [date]);
	}).bind("create", function(event, date){
		var $group = $(this).find("div.date-picker-row-group").empty();

		var now = new Date();
		now.setHours(0);
		now.setMinutes(0);
		now.setSeconds(0);
		now.setMilliseconds(0)

		var init = new Date(parseInt($(this).attr("date"), 10));

		var temp = new Date(date);
		temp.setDate(1);
		temp.setMonth(temp.getMonth() + 1);
		temp.setDate(0);
		var lastDate = temp.getDate();
		var loopEnd = lastDate + 6 - temp.getDay();
		temp.setDate(1);
		var loopStart = (temp.getDay() - 1) * - 1;

		temp.setDate(loopStart);
		for(var loopDate = loopStart, week = 0; loopDate <= loopEnd; loopDate++, week = (week + 1) % 7){
			if(week == 0){
				var $row = $("<div></div>").attr("class", "date-picker-row").appendTo($group);
			}

			var $cell = $("<div></div>").attr("class", "date-picker-cell").appendTo($row);

			if(loopDate < 1 || loopDate > lastDate){
				$cell.addClass("out");
			}

			if(temp.getTime() == init.getTime()){
				$cell.addClass("active");
			}

			if(temp.getTime() == now.getTime()){
				$cell.addClass("today")
			}

			$cell.data({
				"date": new Date(temp.getTime())
			}).text(temp.getDate()).bind("click", function(){
				var $that = $(this);
				var date = $that.data("date");
				$("#link-settings-loader").show();

				$.ajax({
					url: "dashboard_ajax.php?act=edit_link",
					type: "POST",
					dataType: "xml",
					data: {
						token: token,
						expiration_date: (function(){
							var expDate = new Date(date.getTime());
							expDate.setDate(expDate.getDate() + 1);
							return expDate.getUTCFullYear() + "-" + padding(expDate.getUTCMonth() + 1, 2) + "-" + padding(expDate.getUTCDate(), 2) + " " + padding(expDate.getUTCHours(), 2) + ":" + padding(expDate.getUTCMinutes(), 2) + ":00"
						})()
					},
					success: function(data, textStatus, jqXHR){

						var $container = $that.closest("div.link-settings-expiration-date-container");
						$container.find("span.link-settings-expiration-date-text").text(date.getFullYear() + "-" + padding(date.getMonth() + 1, 2) + "-" + padding(date.getDate(), 2));
						$container.find("div.link-settings-expiration-date-clear-icon").show();
						$(document).triggerHandler("click.calendar");

						window.links[token].expirationDate = date;
					},
					error: function(jqXHR, textStatus, errorThrown){
						if(jqXHR.status === 0 && textStatus != "timeout"){ return; }// not include timeout

						alert(jqXHR.responseText);
					},
					complete: function(){
						$("#link-settings-loader").hide();
					}
				});
			});

			temp.setDate(temp.getDate() + 1);
		}

		$(this).find("div.date-picker-title span").text(date.getFullYear() + "/" + padding(date.getMonth() + 1, 2));

		// adjust position
		$(this).css({
			"top": "auto",
			"right": "auto",
			"bottom": "auto",
			"left": "auto"
		});

		if($(this).offset().left + $(this).outerWidth() > $(window).scrollLeft() + $(window).width() - 20){// out screen range
			$(this).css("right", 0);
		}

		if($(this).offset().top + $(this).outerHeight() > $(window).scrollTop() + $(window).height() - 20){
			$(this).css("bottom", ($(this).siblings("div.time-button").outerHeight() - 1) + "px");
		}
	}).attr("class", "date-picker").append(
		$("<div></div>").attr("class", "date-picker-title").append(
			$("<span></span>")
		).append(
			$("<div></div>").attr("class", "date-picker-switch").css("left", "1px").append(
				createSVGIcon("image/ics.svg", "chevron_left")
			).bind("click", function(){
				$(this).closest("div.date-picker").triggerHandler("prev");
			})
		).append(
			$("<div></div>").attr("class", "date-picker-switch").css("right", "1px").append(
				createSVGIcon("image/ics.svg", "chevron_right")
			).bind("click", function(){
				$(this).closest("div.date-picker").triggerHandler("next");
			})
		)
	).append(
		$("<div></div>").attr("class", "date-picker-table").append(
			$("<div></div>").attr("class", "date-picker-row day").append(
				$("<div></div>").attr("class", "date-picker-cell").text("<?=$lang['HISTORY_IO']['SUN'];?>")
			).append(
				$("<div></div>").attr("class", "date-picker-cell").text("<?=$lang['HISTORY_IO']['MON'];?>")
			).append(
				$("<div></div>").attr("class", "date-picker-cell").text("<?=$lang['HISTORY_IO']['TUE'];?>")
			).append(
				$("<div></div>").attr("class", "date-picker-cell").text("<?=$lang['HISTORY_IO']['WED'];?>")
			).append(
				$("<div></div>").attr("class", "date-picker-cell").text("<?=$lang['HISTORY_IO']['THU'];?>")
			).append(
				$("<div></div>").attr("class", "date-picker-cell").text("<?=$lang['HISTORY_IO']['FRI'];?>")
			).append(
				$("<div></div>").attr("class", "date-picker-cell").text("<?=$lang['HISTORY_IO']['SAT'];?>")
			)
		).append(
			$("<div></div>").attr("class", "date-picker-row-group date")
		)
	).bind("click", function(event){
		event.stopPropagation();
		return;
	});
}

function bindTipEvent($element){
	$element.hover(
		function(){
			var that = this;
			$(this).attr(
				"pid",
				setTimeout(function(){
					$(that).showTip({"tip": $(that).attr("tip"), "position": $(that).attr("tip_position") ? $(that).attr("tip_position") : "top", "color": $(that).attr("tip_color") ? $(that).attr("tip_color") : "black"});
				}, 100)
			);
		},
		function(){
			clearTimeout($(this).attr("pid"));
			$(this).hideTip();
		}
	);

//	clearTimeout($(this).attr("pid"));
//	$("#" + $(this).attr("tip_id")).remove();
}

function padding(number, length, paddingChar){
	paddingChar = typeof(paddingChar) == "undefined" ? "0" : paddingChar;

    var str = "" + number;
    while (str.length < length) {
        str = paddingChar + str;
    }

    return str;
}

function formating(value){
	if(isNaN(value)){
		return null;
	}
	else{
		var retObject = {
			"value": "",
			"unit": "",
			"join": function(string){
				return this.value + string + this.unit;
			},
			"toString": function(){
				return this.join("");
			}
		};

		if(value < 1000){
			var valueString = parseFloat((value).toPrecision(12)).toString();

			for(var i = 0, counter = 0; counter < 4 && i < valueString.length; i++){
				if(valueString[i].match(/[0-9]/)){
					counter++;
				}

				retObject.value += valueString[i];
			}
		}
		else{
		    var i = -1;
		    var unitArray = ["K", "M", "B", "T"];

		    do {
		        value /= 1000;
		        i++;
		    } while (value >= 1000);

			retObject.value = (Math.floor(value * 10) / 10).toFixed(1);
			retObject.unit = unitArray[i];
		}

		return retObject;
	}
};

function formating2(value){
	if(isNaN(value)){
		return null;
	}
	else{
	    var unitIndex = 0;
	    var unitArray = ["", "K", "M", "B", "T"];

		while (value >= 1000){
	        value /= 1000;
	        unitIndex++;
		}

		var valueArray = [];
		var valueString = value.toString();
		for(var i = 0, counter = unitArray[unitIndex].length; counter < 5 && i < valueString.length; i++){
			if(valueString[i].match(/[0-9.]/)){
				counter++;
			}

			valueArray.push(valueString[i]);
		}

		//return parseFloat(valueArray.join("")).toString() + unitArray[unitIndex];

		if(valueArray[valueArray.length - 1] == "."){
			valueArray.pop();
		}

		return valueArray.join("") + unitArray[unitIndex];
	}
}

function numberWithCommas(x) {
	return x;

//    var parts = x.toString().split(".");
//    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
//    return parts.join(".");
}

function createID(length) {
   var result = '';
   var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
   var charactersLength = characters.length;

   for (var i = 0; i < length; i++) {
      result += characters.charAt(Math.floor(Math.random() * charactersLength));
   }

   return result;
}

function createSVGIcon(path, name){
	var svg = document.createElementNS("http://www.w3.org/2000/svg", "svg");
	var use = document.createElementNS("http://www.w3.org/2000/svg", 'use');
	use.setAttributeNS("http://www.w3.org/1999/xlink", 'xlink:href', path + '#' + name);
	svg.appendChild(use);
	return svg;
}

function showWindow($popup, callback){
	var $popup = $popup.detach().appendTo("#popup-window-content");
	$("#popup-window-content, #popup-window-background").show();

	$popup.css({"marginTop": "", "marginLeft": ""});

	// Margin top & left
	var marginTop = $(window).height() / 2 - $popup.outerHeight() / 2;
	$popup.css("marginTop", ((marginTop < 0 ? $(window).scrollTop() : marginTop + $(window).scrollTop()) - $popup.offset().top) + "px");

	var marginLeft = $(window).width() / 2 - $popup.outerWidth() / 2;
	$popup.css("marginLeft", ((marginLeft < 0 ? $(window).scrollLeft() : marginLeft + $(window).scrollLeft()) - $popup.offset().left) + "px");

	// Margin bottom
	var maxHeight = 0;
	$("#popup-window-content > *").each(function(){
		var height = $(this).offset().top + $(this).outerHeight();
		if(height > maxHeight){
			maxHeight = height;
		}
	});

	var height = $popup.offset().top + $popup.outerHeight();
	if(height < maxHeight){
		$popup.css("marginBottom", (maxHeight - height) + "px");
	}

	if(typeof(windowCallback) == "undefined"){
		windowCallback = [];
	}

	if(typeof(callback) == "function"){
		windowCallback.push(callback);
	}
}

function hideWindow(){
	$("#popup-window-content > *:last").detach().appendTo("#popup-container");

	if($("#popup-window-content > *").length <= 0){
		$("#popup-window-content, #popup-window-background").hide();
	}
}

function onClickWindowButton(button){
	var callback = windowCallback[windowCallback.length - 1];
	var closeWindow = true;

	if(typeof(callback) == "function"){
		closeWindow = callback(button);
	}

	if(closeWindow !== false){
		windowCallback.pop();
	}
}

function generateColor(neededColors, isDark){
	var c, colors = [],
	    colorPool = isDark ? ["#7EB26D", "#EAB839", "#6ED0E0", "#EF843C", "#E24D42", "#1F78C1", "#BA43A9", "#705DA0", "#508642", "#CCA300", "#447EBC"] : ["#edc240", "#afd8f8", "#cb4b4b", "#4da74d", "#9440ed"],
	    colorPoolSize = colorPool.length,
	    variation = 0;

	for (i = 0; i < neededColors; i++) {
	    c = $.color.parse(colorPool[i % colorPoolSize] || "#666");

	    if (i % colorPoolSize === 0 && i) {
	        if (variation >= 0) {
	            if (variation < 0.5) {
	                variation = -variation - 0.2;
	            } else variation = 0;
	        } else variation = -variation;
	    }

	    colors[i] = c.scale('rgb', 1 + variation);
	}

	return colors;
}

function setCookie(name, value, settings){
	if(typeof(settings) != "object"){
		settings = {
			expiresTime: settings//expiresTime in minutes
		};
	}
	else{
		settings = $.extend(true, {}, settings);
	}

	var expiresTimeString = "";
	if(typeof(settings.expiresTime) != "undefined"){
		var date = new Date();
		date.setTime(date.getTime() + settings.expiresTime * 60 * 1000);
		expiresTimeString = ";expires=" + date.toGMTString();
	}

	var pathString = "";
	if(typeof(settings.path) != "undefined"){
		pathString = ";path=" + settings.path;
	}

	document.cookie = name + "=" + escape(value) + expiresTimeString + pathString;
}

function getCookie(name){
	if (document.cookie.length > 0){
		var cookies = document.cookie.split("\;");
		for(var key in cookies){
			var cookie = cookies[key].split("=");
			if(cookie[0].replace(/^\s+|\s+$/g, "") == name){
				return typeof(cookie[1]) != "undefined" ? unescape(cookie[1]) : null;
			}
		} 
	}

	return null;
}

$(function () {
    var options = {
        float: true,
		cellHeight: cellHeight,
		verticalMargin: verticalMargin,
		disableOneColumnMode: true,
		alwaysShowResizeHandle: true,
		width: 24,
		//animate: true
    };

    gridstack = $('.grid-stack').gridstack(options).data('gridstack');

	$('.grid-stack').on('dragstart', function(event, ui) {
	    $($(ui)[0].helper.context).find("div.grid-stack-item-content > div.widget-content").attr("state", "draging");
	}).on('dragstop', function(event, ui) {
		var $widget = $(ui.helper.context).find("div.grid-stack-item-content");
		var accountUID = $("#dashboard-switch-handler").attr("account_uid");
		var dashboardUID = $("#dashboard-switch-handler").attr("dashboard_uid");
		var widgetUID = $widget.attr("uid");
		var widget = window.accounts[accountUID].dashboards[dashboardUID].widgets[widgetUID];
		window.widget[widget.settings.type].dragstop($widget.find("> div.widget-content").removeAttr("state"), widget.settings, widget.channels);
	}).on('change', function(event, items) {
		if(typeof(items) != "undefined" && ignoreChangeEvent == false){
	    	saveWidgetPosition(items);
		}
	}).on('resizestart', function(event, ui) {
	    var $widget = $(ui.element[0]).find("div.grid-stack-item-content");
		var accountUID = $("#dashboard-switch-handler").attr("account_uid");
		var dashboardUID = $("#dashboard-switch-handler").attr("dashboard_uid");
		var widgetUID = $widget.attr("uid");
		var widget = window.accounts[accountUID].dashboards[dashboardUID].widgets[widgetUID];
		window.widget[widget.settings.type].resizestart($widget.find("> div.widget-content").attr("state", "resizing"), widget.settings, widget.channels);
	}).on('gsresizestop', function(event, ui) {
		var $widget = $(ui).find("div.grid-stack-item-content");
		var accountUID = $("#dashboard-switch-handler").attr("account_uid");
		var dashboardUID = $("#dashboard-switch-handler").attr("dashboard_uid");
		var widgetUID = $widget.attr("uid");
		var widget = window.accounts[accountUID].dashboards[dashboardUID].widgets[widgetUID];
		window.widget[widget.settings.type].resizestop($widget.find("> div.widget-content").removeAttr("state"), widget.settings, widget.channels);
	}).on('resize', function(event, ui) {
		if(typeof(ui) != "number"){
			var $widget = $(ui.element[0]).find("> .grid-stack-item-content");
			var accountUID = $("#dashboard-switch-handler").attr("account_uid");
			var dashboardUID = $("#dashboard-switch-handler").attr("dashboard_uid");
			var widgetUID = $widget.attr("uid");
			var widget = window.accounts[accountUID].dashboards[dashboardUID].widgets[widgetUID];
			window.widget[widget.settings.type].resizing($widget.find("> div.widget-content"), widget.settings, widget.channels);
		}

		event.stopPropagation();
	});

	setGridWidth(24);

	$(document).bind("click", function(){
		$(".grid-stack-item-content").removeClass("active");
		$("#dashboard-switch-handler").removeClass("active").siblings("div").hide();;
	});

	$(document).bind("keydown", function(event) {
		if((event.keyCode || event.which) == 122){
			$("#dashboard-fullscreen").triggerHandler("click");
			event.preventDefault();
		}
	});

	$("html").bind("fullscreenchange", function(){
		if (document.fullscreenElement) {
			$("body").addClass("fullscreen");
			$("#dashboard-fullscreen svg use").attr("xlink:href", "image/ics.svg#fullscreen_exit");
		}
		else {
			$("body").removeClass("fullscreen");
			$("#dashboard-fullscreen svg use").attr("xlink:href", "image/ics.svg#fullscreen");
		}
	});

	$("#dashboard-switch-handler").bind("click", function(event){
		if(!$(this).hasClass("active")){
			$(this).addClass("active").siblings("div").show();
			event.stopPropagation();
		}
	});

	$("#dashboard-new").bind("click", function(){
		showCreateDashboardWindow();
	});

	$("#dashboard-edit").bind("click", function(){
		showEditDashboardWindow();
	});

	$("#dashboard-copy").bind("click", function(){
		copyDashboard();
	});

	$("#dashboard-fullscreen").bind("click", function(){
		if (document.fullscreenElement) {
			document.exitFullscreen();
		}
		else {
			$("html")[0].requestFullscreen();
		}
	});

	$("#dashboard-remove").bind("click", function(){
		showRemoveDashboardWindow();
	});

	$("#dashboard-link").bind("click", function(){
		showaddLinkWindow();
	});

	$("#item-add").bind("click", function(){
		showAddWidgetWindow("<?=$lang['DASHBOARD']['CREATE_WIDGET']['ADD_WIDGET'];?>", function(result){
			if(result == "ok"){
				var accountUID = $("#dashboard-switch-handler").attr("account_uid");
				var dashboardUID = $("#dashboard-switch-handler").attr("dashboard_uid");
				var widgetUID = null;
				var type = $("#widget-type div.widget-type.active").attr("widget-type");

				// Channel number check
				var $channelRows = $("#channle-list-table-body > tr");

				if(window.widget[type].minChannelNumber != null && $channelRows.length < window.widget[type].minChannelNumber){
					popupErrorWindow("<?=$lang['DASHBOARD']['CREATE_WIDGET']['POPUP']['CHANNEL_NUMBER_CAN_NOT_LESS_THEN_X'];?>".replace("%number%", window.widget[type].minChannelNumber));
					$("#menu-container > div.menu-item[target='channel']").triggerHandler("click");
					return false;
				}

				if(window.widget[type].maxChannelNumber != null && $channelRows.length > window.widget[type].maxChannelNumber){
					popupErrorWindow("<?=$lang['DASHBOARD']['CREATE_WIDGET']['POPUP']['CHANNEL_NUMBER_CAN_NOT_GREATER_THEN_X'];?>".replace("%number%", window.widget[type].maxChannelNumber));
					$("#menu-container > div.menu-item[target='channel']").triggerHandler("click");
					return false;
				}

				var settings = {
					title: {
						text: $("#widget-title-text").val(),
						size: parseFloat($("#widget-title-size").val()),
					},
					description: $("#widget-description").val(),
					type: type
				};

				var channels = {};

				Object.defineProperty(channels, "order", {
					enumerable : false,
					value : []
				});

				$channelRows.each(function(){
					var accountUID = $(this).attr("account_uid");
					var deviceUID = $(this).attr("device_uid");
					var moduleUID = $(this).attr("module_uid");
					var channel = $(this).attr("channel");
					channels[accountUID] = channels[accountUID] || {};
					channels[accountUID][deviceUID] = channels[accountUID][deviceUID] || {};
					channels[accountUID][deviceUID][moduleUID] = channels[accountUID][deviceUID][moduleUID] || {};
					channels[accountUID][deviceUID][moduleUID][channel] = {
						shortName: $(this).attr("shortName"),
						fullName: $(this).attr("fullName"),
						unit: $(this).attr("unit"),
						icon: $(this).attr("icon") || null
					}

					channels.order.push({
						accountUID: accountUID,
						deviceUID: deviceUID,
						moduleUID: moduleUID,
						channel: channel
					});
				});

				// Call channel update event
				callChannelUpdateEvent();

				// Save setting
				var errorMessage = widget[type].settingSaved(settings);
				if(errorMessage){
					popupErrorWindow(errorMessage);
					return false;
				}

				addWidget(accountUID, dashboardUID, settings, channels);
			}
			else if(result == "cancel"){}
		});

		$("#channle-list-table-body").empty();

		showChannelNoExist();
		showAddChannelButton();

		$("#menu-container > div.menu-item[target='type']").triggerHandler("click");
		$("#widget-type > div[widget-type]:first").triggerHandler("mousedown");
	});

	$("#menu-container > .menu-item").bind("click", function(){
		var target = $(this).attr("target");
		$("#content-container > div").hide();
		$("#content-" + target).show().scrollTop(0);
		$(this).addClass("active").siblings().removeClass("active");

		if(target == "property"){
			callChannelUpdateEvent();
		}
	});

	$("#widget-type .widget-type").bind("mousedown", function(event, settings){
		$(this).addClass("active").siblings().removeClass("active");

		window.widget[$(this).attr("widget-type")].settingCreated($("#widget-setting-container").empty(), settings);

		callChannelUpdateEvent();

		showChannelNoExist();
		showAddChannelButton();
	});

	$("#window-add-channel-selector").bind("change", function(){
		var $option = $(this).find("option:selected");

		$("#window-add-device-is-empty, #window-add-module-is-empty, #window-add-module-no-match").hide();

		if($option.length <= 0){
			$("#window-add-device-is-empty").show();
		}
		else{
			var $container = $("#" + $option.attr("account_uid") + "-" + $option.attr("device_uid"));

			$("#window-add-channel-containers > *").hide();

			if($container.length <= 0){
				loadModuleList($option.attr("account_uid"), $option.attr("device_uid")).done(function(){
					$container = $("#" + $option.attr("account_uid") + "-" + $option.attr("device_uid"));

					if($container.children().length <= 0){
						$("#window-add-module-is-empty").show();
					}

					$("#window-add-channel-filter-input").triggerHandler("keyup");
				});
			}
			else{
				$container.show();

				if($container.children().length <= 0){
					$("#window-add-module-is-empty").show();
				}

				$("#window-add-channel-filter-input").triggerHandler("keyup");
			}
		}
	});

	$("#window-add-channel-filter-input").bind("keyup", function(){
		filterModule($(this).val());
	});

	$("#window-add-channel-filter-clear").bind("click", function(){
		$("#window-add-channel-filter-input").val("").triggerHandler("keyup");
	});

	$("#link-settings-add-button").bind("click", function(){
		$("#link-settings-loader").show();

		$.ajax({
			url: "dashboard_ajax.php?act=add_link",
			type: "POST",
			dataType: "xml",
			data: {},
			success: function(data, textStatus, jqXHR){
				var token = $(data).find("result").attr("token");

				window.links[token] = {
					controllable: 0,
					expirationDate: null
				};

				addLink(token);

				$("#link-settings-container").scrollTop(9999);
			},
			error: function(jqXHR, textStatus, errorThrown){
				if(jqXHR.status === 0 && textStatus != "timeout"){ return; }// not include timeout

				alert(jqXHR.responseText);
			},
			complete: function(){
				$("#link-settings-loader").hide();
			}
		});
	});

	$(window).bind('hashchange', function(event) {
		var splitArray = window.location.hash.replace(/^#/, '').split("-");
		var accountUID = splitArray[0];
		var dashboardUID = splitArray[1];
		var $option = $("#dsahboard-switch-selector .dsahboard-switch-option[account_uid='" + accountUID + "'][dashboard_uid='" + dashboardUID + "']");

		if($option.length > 0){
			if(!($("#dashboard-switch-handler").attr("account_uid") == accountUID && $("#dashboard-switch-handler").attr("dashboard_uid") == dashboardUID)){
				$option.triggerHandler("click");
			}
		}
		else{
			popupErrorWindow("<?=$lang['DASHBOARD']['DASHBOARD_NOT_EXIST'];?>", function(){
				if(!($("#dashboard-switch-handler").attr("account_uid") && $("#dashboard-switch-handler").attr("dashboard_uid"))){
					$("#dsahboard-switch-selector .dsahboard-switch-option:first").triggerHandler("click");
				}
			});
		}
    });

	$(window).on('resize', function(event, ui){
		var accountUID = $("#dashboard-switch-handler").attr("account_uid");
		var dashboardUID = $("#dashboard-switch-handler").attr("dashboard_uid");
		var nodes = gridstack.grid.nodes;

		for(var i = 0; i < nodes.length; i++){
			var $widget = $(nodes[i].el[0]).find("> .grid-stack-item-content[uid]");

			if($widget.length > 0){// Someting grid-stack will provide placeholder element, not widget
				var $widgetContent = $widget.find("> div.widget-content");

				var widgetUID = $widget.attr("uid");
				var widget = window.accounts[accountUID].dashboards[dashboardUID].widgets[widgetUID];

				// Resize widget event
				window.widget[widget.settings.type].resizestart($widgetContent, widget.settings, widget.channels);
				window.widget[widget.settings.type].resizing($widgetContent, widget.settings, widget.channels);
				window.widget[widget.settings.type].resizestop($widgetContent, widget.settings, widget.channels);
			}
		}
	});

	$(document).bind("click.calendar", function(){
		$("div.link-settings-expiration-date-container").removeClass("active").find("div.date-picker").hide();
		$("#link-settings-cover").hide();
	});

	bindTipEvent($("#dashboard-switch-handler div.dsahboard-switch-option-icon div.share"));
	bindTipEvent($("#window-create-dashboard-as-default").closest("div.table-cell-content").find("div.help-icon"));
	bindTipEvent($("#window-create-dashboard-data-length").closest("div.table-cell-content").find("div.help-icon"));
	bindTipEvent($("#dashboard-remove"));
	bindTipEvent($("#dashboard-link"));
	bindTipEvent($("#dashboard-fullscreen"));
	bindTipEvent($("#dashboard-copy"));
	bindTipEvent($("#dashboard-edit"));
	bindTipEvent($("#dashboard-new"));

	windowAddWidgetContent = $("#window-add-widget > *").clone(true);

	defaultIcon = $("#channel-icon-dummy").css("backgroundImage").match(/url\(['"](.+)['"]\)/)[1];

	loadDashboard().done(function(){
		var defaultAccountUID = null, defaultDashboardUID = null;

		// Find default dashboard in my account
		var accountUID = '<?=$_SESSION["account_uid"]?>';
		var account = window.accounts[accountUID];

		for(var dashboardUID in account.dashboards){
			var dashboard = account.dashboards[dashboardUID];

			if(defaultAccountUID == null && defaultDashboardUID == null){
				defaultAccountUID = accountUID;
				defaultDashboardUID = dashboardUID;
			}

			if(dashboard.asDefault == true){
				defaultAccountUID = accountUID;
				defaultDashboardUID = dashboardUID;
				break;
			}
		}

		if(defaultAccountUID == null && defaultDashboardUID == null){
			// Find first dashboard in share account
			(function(){
				for(var accountUID in window.accounts){
					var account = window.accounts[accountUID];

					for(var dashboardUID in account.dashboards){
						defaultAccountUID = accountUID;
						defaultDashboardUID = dashboardUID;
						return;
					}
				}
			})();
		}

		if(defaultAccountUID == null && defaultDashboardUID == null){// Dashboard not exist
			$("#dashboard-no-exist-container").show();
		}
		else{
			if(window.location.hash.replace(/^#/, '') == ""){// No assign account & dashboard uid in hash
				window.location.hash = "#" + defaultAccountUID + "-" + defaultDashboardUID;
			}

			$(window).triggerHandler("hashchange");
			$("#dashboard-container").show();
		}
	}).fail(function(errorCode){
		if(errorCode == "1"){
			$("#dashboard-expired-container").show();
		}
	});
});
</script>
<?php
}
?>

<?php
function customized_body(){
	global $lang, $dashboard_permission;
?>
<div style="padding:20px;">
    <div id="dashboard-no-exist-container" style="display:none;">
        <div class="dashboard-no-exist-title"><?=$lang['DASHBOARD']['DASHBOARD_NOT_EXIST'];?></div>
        <div class="dashboard-no-exist-content"><?=$lang['DASHBOARD']['CLICK_BUTTON_TO_CREATE_DASHBOARD'];?></div>
    </div>

    <div id="dashboard-expired-container" style="display:none;">
        <div class="dashboard-expired-title"><?=$lang['DASHBOARD']['DASHBOARD_NOT_AVAILABLE_OR_EXPIRED'];?></div>
        <div class="dashboard-expired-content"><?=$lang['DASHBOARD']['CONTACT_SYSTEM_ADMINISTRATOR'];?></div>
    </div>

	<div id="dashboard-container" style="display:none;user-select: none;">
		<div style="position:relative;">
			<div class="title" id="dashboard-title"></div>

			<div id="dashboard-control-buttons">
<?php
	if(!isset($dashboard_permission)){
?>
				<button id="dashboard-remove" style="margin-left:5px;" class="red" tip="<?=$lang['DASHBOARD']['REMOVE'];?>" tip_position="bottom"><svg><use xlink:href="image/ics.svg#delete"></use></svg></button>
				<button id="dashboard-link" style="margin-left:5px;" tip="<?=$lang['DASHBOARD']['SHARE'];?>" tip_position="bottom"><svg><use xlink:href="image/ics.svg#link"></use></svg></button>
<?php
	}
?>
				<button id="dashboard-fullscreen" style="margin-left:5px;" tip="<?=$lang['DASHBOARD']['FULLSCREEN'];?>" tip_position="bottom"><svg><use xlink:href="image/ics.svg#fullscreen"></use></svg></button>
<?php
	if(!isset($dashboard_permission)){
?>
				<button id="dashboard-copy" style="margin-left:5px;" tip="<?=$lang['DASHBOARD']['COPY'];?>" tip_position="bottom"><svg><use xlink:href="image/ics.svg#file_copy"></use></svg></button>
				<button id="dashboard-edit" style="margin-left:5px;" tip="<?=$lang['DASHBOARD']['EDIT'];?>" tip_position="bottom"><svg><use xlink:href="image/ics.svg#settings"></use></svg></button>
				<button id="dashboard-new" style="margin-left:5px;" tip="<?=$lang['DASHBOARD']['CREATE'];?>" tip_position="bottom"><svg><use xlink:href="image/ics.svg#insert_drive_file"></use></svg></button>
<?php
	}
?>
				<div class="dsahboard-switch-wrapper">
			        <div class="dsahboard-switch" id="dashboard-switch-handler">
		                <div class="dsahboard-switch-option-name">&nbsp;</div>
			            <div class="dsahboard-switch-option-icon">
							<div class="share"><svg><use xlink:href="image/ics.svg#share"></use></svg></div>
							<div class="lock"><svg><use xlink:href="image/ics.svg#lock"></use></svg></div>
							<div class="down"><svg><use xlink:href="image/ics.svg#arrow_drop_down"></use></svg></div>
						</div>
			        </div>
			        <div class="dsahboard-switch-selector" id="dsahboard-switch-selector" style="display: none;"></div>
				</div>
			</div>
		</div>

		<div class="grid-stack"></div>
<?php
	if(!isset($dashboard_permission)){
?>
		<div id="item-add">
			<div id="cross"></div>
		</div>
<?php
	}
?>
	</div>
</div>

<div id="popup-container" style="display:none;">
	<div class="popup-wrapper" id="window-create-dashboard" style="width:430px;">
		<div class="popup-container">
			<div class="popup-title"></div>
			<div class="popup-content window-dashboard-container">
				<div style="display:table;margin:0 auto;">
					<div class="table-row">
						<div class="table-cell-title">*<?=$lang['DASHBOARD']['CREATE_DASHBOARD']['NAME'];?></div>
						<div class="table-cell-content"><input type="text" id="window-create-dashboard-name" style="width:200px;"></div>
					</div>

					<div class="table-row">
						<div class="table-cell-hr"></div>
					</div>

					<div class="table-row">
						<div class="table-cell-title"><?=$lang['DASHBOARD']['CREATE_DASHBOARD']['LOCK_WIDGET'];?></div>
						<div class="table-cell-content" style="padding:6px 0;">
							<div class="checkbox" style="float:left;">
								<input type="checkbox" id="window-create-dashboard-lock">
								<label for="window-create-dashboard-lock"></label>
				    		</div><label for="window-create-dashboard-lock" style="cursor: pointer;padding-left: 5px;"><?=$lang['DASHBOARD']['ENABLE'];?></label>
						</div>
					</div>

					<div class="table-row">
						<div class="table-cell-hr"></div>
					</div>

					<div class="table-row">
						<div class="table-cell-title"><?=$lang['DASHBOARD']['CREATE_DASHBOARD']['AS_FIRST_PAGE'];?></div>
						<div class="table-cell-content" style="padding:6px 0;">
							<div class="checkbox" style="float:left;">
								<input type="checkbox" id="window-create-dashboard-as-default">
								<label for="window-create-dashboard-as-default"></label>
				    		</div><label for="window-create-dashboard-as-default" style="cursor: pointer;padding-left: 5px;"><?=$lang['DASHBOARD']['ENABLE'];?></label>
							<div class="help-icon" tip="<?=$lang['DASHBOARD']['CREATE_DASHBOARD']['TIP']['AS_FIRST_PAGE'];?>" tip_position="right">
								<svg><use xlink:href="image/ics.svg#help"></use></svg>
							</div>
						</div>
					</div>

					<div class="table-row">
						<div class="table-cell-hr"></div>
					</div>

					<div class="table-row">
						<div class="table-cell-title"><?=$lang['DASHBOARD']['CREATE_DASHBOARD']['DATA_LENGTH'];?></div>
						<div class="table-cell-content">
							<select id="window-create-dashboard-data-length">
								<option value="30"><?=$lang['DASHBOARD']['CREATE_DASHBOARD']['LAST_30_SECONDS'];?></option>
								<option value="60"><?=$lang['DASHBOARD']['CREATE_DASHBOARD']['LAST_1_MINUTE'];?></option>
								<option value="180"><?=$lang['DASHBOARD']['CREATE_DASHBOARD']['LAST_3_MINUTES'];?></option>
								<option value="300"><?=$lang['DASHBOARD']['CREATE_DASHBOARD']['LAST_5_MINUTES'];?></option>
								<option value="600"><?=$lang['DASHBOARD']['CREATE_DASHBOARD']['LAST_10_MINUTES'];?></option>
							</select>
							<div class="help-icon" tip="<?=$lang['DASHBOARD']['CREATE_DASHBOARD']['TIP']['DATA_LENGTH'];?>" tip_position="right">
								<svg><use xlink:href="image/ics.svg#help"></use></svg>
							</div>
						</div>
					</div>

					<div class="table-row">
						<div class="table-cell-hr"></div>
					</div>

					<div class="table-row">
						<div class="table-cell-title"><?=$lang['DASHBOARD']['CREATE_DASHBOARD']['DARK_MODE'];?></div>
						<div class="table-cell-content" style="padding:6px 0;">
							<div class="checkbox" style="float:left;">
								<input type="checkbox" id="window-create-dashboard-dark-mode">
								<label for="window-create-dashboard-dark-mode"></label>
				    		</div><label for="window-create-dashboard-dark-mode" style="cursor: pointer;padding-left: 5px;"><?=$lang['DASHBOARD']['ENABLE'];?></label>
						</div>
					</div>
				</div>
			</div>
			<div class="popup-footer">
				<input type="button" value="<?=$lang['OK'];?>" onClick="onClickWindowButton('ok');">&nbsp;&nbsp;<input type="button" class="gray" value="<?=$lang['CANCEL'];?>" onClick="onClickWindowButton('cancel');"></span>
			</div>
		</div>
	</div>

	<div class="popup-wrapper" id="window-add-widget" style="width:1100px;">
		<div class="popup-container">
			<div class="popup-title"></div>
			<div class="popup-content window-dashboard-container" style="padding:0;text-align:left;height:700px;position:relative;">
				<div id="menu-container">
					<div class="menu-item active" target="type"><?=$lang['DASHBOARD']['CREATE_WIDGET']['TYPE'];?></div>
					<div class="menu-item" target="property"><?=$lang['DASHBOARD']['CREATE_WIDGET']['PROPERTY'];?></div>
					<div class="menu-item" target="channel"><?=$lang['DASHBOARD']['CREATE_WIDGET']['CHANNEL'];?></div>
				</div>
				<div id="content-container">
					<!-- Type -->
					<div id="content-type">
						<div style="padding:20px;">
							<div id="widget-type">
								<div class="widget-type active" widget-type="line"><img src="./image/widget_line.png"><span><?=$lang['DASHBOARD']['CREATE_WIDGET']['LINE_CHART'];?></span></div>
								<div class="widget-type" widget-type="bar"><img src="./image/widget_bar.png"><span><?=$lang['DASHBOARD']['CREATE_WIDGET']['BAR_CHART'];?></span></div>
								<div class="widget-type" widget-type="pie"><img src="./image/widget_pie.png"><span><?=$lang['DASHBOARD']['CREATE_WIDGET']['PIE_CHART'];?></span></div>
								<div class="widget-type" widget-type="gauge"><img src="./image/widget_gauge.png"><span><?=$lang['DASHBOARD']['CREATE_WIDGET']['GAUGE'];?></span></div>
								<div class="widget-type" widget-type="bar-gauge"><img src="./image/widget_bar_gauge.png"><span><?=$lang['DASHBOARD']['CREATE_WIDGET']['PLOT_BAR'];?></span></div>
								<div class="widget-type" widget-type="value"><img src="./image/widget_value.png"><span><?=$lang['DASHBOARD']['CREATE_WIDGET']['VALUE'];?></span></div>
								<div class="widget-type" widget-type="value-table"><img src="./image/widget_value_table.png"><span><?=$lang['DASHBOARD']['CREATE_WIDGET']['VALUE_TABLE'];?></span></div>
								<div class="widget-type" widget-type="overlay"><img src="./image/widget_overlay.png"><span><?=$lang['DASHBOARD']['CREATE_WIDGET']['VALUE_LABEL_OVERLAY'];?></span></div>
								<div class="widget-type" widget-type="button"><img src="./image/widget_button.png"><span><?=$lang['DASHBOARD']['CREATE_WIDGET']['VALUE_OUTPUT'];?></span></div>
								<div class="widget-type" widget-type="slider"><img src="./image/widget_slider.png"><span><?=$lang['DASHBOARD']['CREATE_WIDGET']['VALUE_OUTPUT_SLIDER'];?></span></div>
								<div class="widget-type" widget-type="camera"><img src="./image/widget_camera.png"><span><?=$lang['DASHBOARD']['CREATE_WIDGET']['VIDEO_EVENT_LIST'];?></span></div>
								<div class="widget-type" widget-type="clock"><img src="./image/widget_clock.png"><span><?=$lang['DASHBOARD']['CREATE_WIDGET']['TIME_CLOCK'];?></span></div>
								<div class="widget-type" widget-type="countdown_timer"><img src="./image/widget_countdown_timer.png"><span><?=$lang['DASHBOARD']['CREATE_WIDGET']['COUNTDOWN_TIMER'];?></span></div>
								<div class="widget-type" widget-type="map"><img src="./image/widget_map.png"><span><?=$lang['DASHBOARD']['CREATE_WIDGET']['MAP'];?></span></div>
								<div class="widget-type" widget-type="wysiwyg_tinymce"><img src="./image/widget_wysiwyg.png"><span><?=$lang['DASHBOARD']['CREATE_WIDGET']['RICH_CONTENT'];?></span></div>
								<div style="clear:both;"></div>
							</div>
						</div>
					</div>

					<div id="content-property">
						<div style="padding:20px;">
							<div style="width:calc(100% - 200px);float:left;">
								<div class="content-title"><?=$lang['DASHBOARD']['CREATE_WIDGET']['TITLE'];?></div>
								<div><input type="text" id="widget-title-text" style="width:calc(100% - 10px);"></div>
							</div>
							<div style="width:200px;float:left;">
								<div style="margin-bottom: 10px;"><?=$lang['DASHBOARD']['CREATE_WIDGET']['SIZE'];?></div>
								<div>
									<select id="widget-title-size" style="width:100%;">
										<option value="15">15px</option>
										<option value="17">17px</option>
										<option value="19">19px</option>
										<option value="20">20px</option>
										<option value="24">24px</option>
										<option value="36">36px</option>
										<option value="48">48px</option>
									</select>
								</div>
							</div>

							<div style="clear: both;">&nbsp;</div>

							<div class="content-title"><?=$lang['DASHBOARD']['CREATE_WIDGET']['DESCRIPTION'];?></div>
							<div><input type="text" id="widget-description" style="width:100%;box-sizing:border-box;"></div>

							<div>&nbsp;</div>

							<div id="widget-setting-container" style="/*display:inline-block;width:100%;*/"></div>
						</div>
					</div>

					<!-- Channel -->
					<div id="content-channel">
						<div style="padding:20px;">
							<div class="content-title"><?=$lang['DASHBOARD']['CREATE_WIDGET']['LIST'];?></div>
							<div id="channle-list-container">
								<table class="scroll-table" id="channle-list-table" style="display:none;">
								    <thead>
								        <tr>
								            <th><?=$lang['DASHBOARD']['CREATE_WIDGET']['CHANNEL'];?></th>
								            <th style="text-align: center;padding:3px;"><?=$lang['DASHBOARD']['CREATE_WIDGET']['ACTION'];?></th>
								        </tr>
								    </thead>
								    <tbody id="channle-list-table-body"></tbody>
								</table>

								<table id="channle-list-no-exist">
									<tr>
										<td>
											<div class="channle-list-no-exist-title"><?=$lang['DASHBOARD']['CREATE_WIDGET']['NO_CHANNEL_EXIST'];?></div>
											<div class="channle-list-no-exist-content"><?=$lang['DASHBOARD']['CREATE_WIDGET']['CLICK_BUTTON_TO_ADD_CHANNEL'];?></div>
										</td>
									</tr>
								</table>
							</div>
							<input type="button" class="bule" value="<?=$lang['DASHBOARD']['CREATE_WIDGET']['ADD'];?>" style="margin-top:10px;" onClick="showAddChannelWindow();" id="button-add-channel">
						</div>
					</div>
				</div>
			</div>
			<div class="popup-footer">
				<input type="button" value="<?=$lang['OK'];?>" onClick="onClickWindowButton('ok');">&nbsp;&nbsp;<input type="button" class="gray" value="<?=$lang['CANCEL'];?>" onClick="onClickWindowButton('cancel');">
			</div>
		</div>
	</div>

	<div class="popup-wrapper" id="window-add-channel" style="width:900px;">
		<div class="popup-container">
			<div class="popup-title"><?=$lang['DASHBOARD']['ADD_CHANNEL']['ADD_CHANNEL'];?></div>
			<div class="popup-content">
				<div class="window-add-channel-search">
					<table cellSpacing="0" cellPadding="0" border="0" width="100%">
						<tr>
							<td>
								<select id="window-add-channel-selector" style="min-width: 215px;max-width: 255px;"></select>
							</td>
							<td align="right">
								<div class="window-add-channel-filter">
									<div class="window-add-channel-filter-icon"><svg><use xlink:href="image/ics.svg#search"></use></svg></div>
									<input type="text" id="window-add-channel-filter-input" placeholder="<?=$lang['DASHBOARD']['ADD_CHANNEL']['SEARCH'];?>">
									<div id="window-add-channel-filter-clear"><svg><use xlink:href="image/ics.svg#clear"></use></svg></div>
								</div>
							</td>
						</tr>
					</table>
				</div>
				<div class="window-add-channel-wrapper">
					<div id="window-add-channel-loader"><img src="./image/loader.gif"></div>
					<div id="window-add-device-is-empty"><?=$lang['DASHBOARD']['ADD_CHANNEL']['NO_ONLINE_DEVICE_EXIST'];?></div>
					<div id="window-add-module-is-empty"><?=$lang['DASHBOARD']['ADD_CHANNEL']['NO_MODULE_EXIST'];?></div>
					<div id="window-add-module-no-match"><?=$lang['DASHBOARD']['ADD_CHANNEL']['MODULE_NOT_FOUND'];?></div>
					<div id="window-add-channel-containers"></div>
				</div>
			</div>
			<div class="popup-footer">
				<input type="button" value="<?=$lang['OK'];?>" onClick="onClickWindowButton('ok');">&nbsp;&nbsp;<input type="button" class="gray" value="<?=$lang['CANCEL'];?>" onClick="onClickWindowButton('cancel');">
			</div>
		</div>
	</div>

	<div class="popup-wrapper" id="window-link-settings">
		<div class="popup-container">
			<div class="popup-title"><?=$lang['DASHBOARD']['SHARE_LINK']['SHARE_LINK'];?></div>
			<div class="popup-content" style="height:500px;position:relative;">
				<div id="link-settings-container">
					<table class="scroll-table sticky" id="link-settings-table">
					    <thead>
					        <tr>
					            <th><?=$lang['DASHBOARD']['SHARE_LINK']['URL'];?></th>
					            <th style="width: 180px;"><?=$lang['DASHBOARD']['SHARE_LINK']['EXPIRATION_DATE'];?></th>
					            <th style="text-align: center;width: 1%;white-space: nowrap;"><?=$lang['DASHBOARD']['SHARE_LINK']['CONTROLLABLE'];?></th>
					            <th style="text-align: center;padding:3px;"><?=$lang['DASHBOARD']['SHARE_LINK']['ACTION'];?></th>
					        </tr>
					    </thead>
					    <tbody id="link-settings-table-body"></tbody>
						<tfoot>
							<tr>
								<td colSpan="4" style="height:60px;"></td>
							</tr>
						</tfoot>
					</table>
				</div>

				<div id="link-settings-empty-container">
					<div id="link-settings-empty">
						<div style="margin-bottom: 10px;font-weight: bold;"><?=$lang['DASHBOARD']['SHARE_LINK']['SHARE_LINK_NOT_EXIST'];?></div>
						<div><?=$lang['DASHBOARD']['SHARE_LINK']['CLICK_BUTTON_TO_CREATE_SHARE_LINK'];?></div>
					</div>
				</div>
				<div id="link-settings-add-button"><svg><use xlink:href="image/ics.svg#add"></use></svg></div>
				<div id="link-settings-cover" style="display:none;"></div>
				<div id="link-settings-loader" style="display:none;"><img src="./image/loader.gif"></div>
			</div>

			<div class="popup-footer">
				<input type="button" class="gray" value="<?=$lang['DASHBOARD']['CLOSE'];?>" onClick="onClickWindowButton('cancel');">
			</div>
		</div>
	</div>
</div>

<div id="channel-icon-dummy" style="display:none;"></div>
<?php
}
?>