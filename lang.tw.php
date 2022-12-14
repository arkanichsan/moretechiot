<?php
$lang = array(
	"SUBMIT" => "送出",
	"OK" => "確定",
	"CANCEL" => "取消",
	"EMAIL" => array(
		"DEAR_USER" => "親愛的使用者：",
		"DISABLE_USER_NOTIFICATION" => "若您要關閉此事件通知，請登入至IoTstar後於『系統設定與資訊』->『資料庫 & 事件通知設定』中進行事件通知設定變更。",
		"DEAR_ADMIN" => "親愛的管理者：",
		"DISABLE_ADMIN_NOTIFICATION" => "若您要關閉系統事件通知，請啟動IoTstar GUI程式後於『Settings > Notification』中變更設定。",
		"BEST_REGARDS" => "祝安康"
	),
	"LOGIN" => array(
		"MISSING_SOCKETS_EXTENSION" => "缺少Sockets擴充功能",
		"MISSING_SOCKETS_EXTENSION_DESCRIPTION" => "由於缺少Sockets擴充功能，系統將無法正常運作。請您先參照使用者手冊將Sockets模組啟用後，即可開始使用系統。",
		"LOGIN_TITLE" => "登入",
		"REMEMBER_ME" => "記住我",
		"FORGOT_PASSWORD" => "忘記密碼？",
		"USERNAME" => "帳號",
		"PASSWORD" => "密碼",
		"SIGNUP_NOW" => "立即申請！",
		"FORGOT_PASSWORD_TITLE" => "忘記密碼",
		"FORGOT_PASSWORD_DESCRIPTION" => "輸入您的帳號並送出，系統將會寄發一封電子郵件至您的信箱中。請點擊電子郵件中的連結即可重置您的密碼。",
		"BACKTOLOGIN" => "返回系統登入介面",
		"POPUP" => array(
			"CONFIRM_EMAIL_HAS_SENT" => "系統已經發送一封電子郵件至您的信箱中。請點擊電子郵件中的連結以重置您的密碼。",
			"NEW_PASSWORD_HAS_BEEN_SENT" => "新的密碼已經發送至您的電子郵件信箱中。請使用新密碼登入。",
			"PASSWORD_HAS_BEEN_RESET" => "您的密碼已經完成重置程序。"
		),
		"AJAX" => array(
			"INVALID_USERNAME" => "無此帳號。",
			"INVALID_PASSWORD" => "密碼錯誤。",
			"ACCOUNT_NOT_ACTIVE" => "此帳號尚未啟用。",
			"ACCOUNT_EXPIRE" => "此帳號使用期限已到期。",
			"EMPTY_USERNAME_OR_PASSWORD" => "帳號或密碼欄位為空白。",
			"EMPTY_USERNAME" => "帳號欄位為空白。"
		),
		"EMAIL" => array(
			"EVENT_NOTIFICATION" => "系統事件通知",
			"ENTER_INCORRECT_PASSWORD_OVER_TEN_TIMES" => "您的帳號已經被錯誤的密碼連續嘗試登入超過十次，特此通知。",
			"CONFIRM_EMAIL" => "確認信件",
			"CLICK_LINK_TO_RESET_PASSWORD" => "請點擊以下連結重置密碼。",
			"NEW_PASSWORD" => "新密碼",
			"NEW_PASSWORD_AS_FOLLOWS" => "下列為您的新密碼。請在登入後修改密碼。"
		)
	),
	"SIGNUP" => array(
		"SIGNUP_TITLE" => "帳號申請",
		"EMAIL_VALIDATION" => "電子郵件信箱驗證",
		"REGISTRATION" => "註冊",
		"DONE" => "完成",
		"EMAIL" => array(
			"USER_SIGN_UP_ACCOUNT" => "有使用者申請名為 %username% 的帳號，若您要啟用請啟動IoTstar GUI程式後於『Account Management』中變更設定。"
		)
	),
	"EMAIL_VALIDATION" => array(
		"ENTER_EMAIL_ADDRESS" => "請輸入您的電子郵件信箱。為了確認您的電子郵件信箱是否正確，系統將發送一封電子郵件至您所設定的信箱中。",
		"EMAIL_ADDRESS" => "電子郵件：",
		"VERIFY" => "驗證",
		"EMAIL_HAS_SENT" => "系統已經發送了一封確認信至%email%，請點擊信中的連結完成註冊程序。",
		"CHECK_EMAIL" => "檢查電子郵件",
		"POPUP" => array(
			"VALIDATION_FAILED" => "驗證失敗。請檢查輸入的資料是否正確。",
		),
		"AJAX" => array(
			"EMPTY_FIELD" => "此欄位不能為空白。",
			"LENGTH_LONGER_THEN_100" => "此欄位長度不能超過100個字元。",
			"ILLEGAL_FORMAT" => "電子郵件位址格式不正確。",
			"EXIST_EMAIL" => "此電子郵件已經被使用過。"
		),
		"EMAIL" => array(
			"SUBJECT" => "電子郵件驗證",
			"CONTENT" => "請點擊以下連結，完成註冊程序。"
		)
	),
	"REGISTRATION" => array(
		"USERNAME" => "帳號：",
		"PASSWORD" => "密碼：",
		"RETYPE_PASSWORD" => "再次輸入密碼：",
		"NICKNAME" => "名稱：",
		"EMAIL_ADDRESS" => "電子郵件：",
		"COMPANY" => "公司：",
		"COUNTRY" => "國家 / 地區：",
		"POPUP" => array(
			"REGISTRATION_FAILED" => "註冊失敗。請檢查輸入的資料是否正確。",
			"VALIDATION_URL_ERROR" => "註冊失敗。認證網址錯誤，請檢查認證網址是否正確。",
			"EXIST_EMAIL" => "註冊失敗。此電子郵件已經被使用過或您已經完成註冊。",
			"ACCOUNT_AMOUNT_REACH_MAXIMUM" => "註冊失敗。系統可容納的使用者帳號數量已達上限。"
		),
		"AJAX" => array(
			"EMPTY_FIELD" => "此欄位不能為空白。",
			"EMPTY_OPTION" => "請選擇一個選項。",
			"ONLY_ALLOW_LOWERCASE_ENGLISH_AND_NUMBER_ONLY" => "此欄位只能輸入小寫英文字母與數字。",
			"ILLEGAL_CHARACTER" => "此欄位不允許特殊字元。",
			"LENGTH_LONGER_THEN_16_OR_SHORTER_THEN_6" => "此欄位長度必須介於6-16的字元間。",
			"USERNAME_TAKEN" => "此帳號已經被使用。",
			"NOT_MATCH_PASSWORD" => "此欄位與密碼欄位不符。",
			"LENGTH_LONGER_THEN_100" => "此欄位長度不能超過100個字元。",
			"LENGTH_LONGER_THEN_50" => "此欄位長度不能超過50個字元。",
			"EXIST_EMAIL" => "此電子郵件已經被使用過或您已經註冊完成。"
		)
	),
	"DONE" => array(
		"ACTIVE_SUCCESS" => "謝謝您的註冊，您的帳號雖已建立，但仍須等管理員啟用後才可開始使用。",
		"LOGIN" => "登入"
	),
	"MANAGER" => array(
		"LOGOUT" => "登出",
		"REMOTE_ACCESS_SERVICE" => "遠端存取服務",
		"DEVICE_MAINTENANCE" => "裝置監控與設定",
		"DATA_DISPLAY_AND_ANALYSIS" => "資訊顯示與分析",
		"DASHBOARD_SERVICE" => "儀表板服務",
		"REALTIME_IO_DATA" => "即時I/O資訊",
		"REALTIME_POWER_DATA" => "即時電力資訊",
		"HISTORICAL_IO_DATA" => "歷史I/O資訊",
		"HISTORICAL_POWER_DATA" => "歷史電力資訊",
		"REPORT_SERVICE" => "報表服務",
		"HISTORICAL_POWER_REPORT" => "歷史電力報表",
		"VIDEO_EVENT_DATA" => "影像事件資訊",
		"GROUPING_SETTING" => "分群設定",
		"IO_CHANNEL" => "I/O通道",
		"POWER_METER_LOOP" => "電錶迴路",
		"SYSTEM_INFORMATION_AND_SETTING" => "系統資訊與設定",
		"ACCOUNT_MAINTENANCE" => "帳號設定",
		"DATABASE_AND_EVENT_SETTING" => "資料庫與事件設定",
		"EVENT_LIST" => "事件列表",
		"DATABASE_TABLE_LIST" => "資料庫表格對照表",
		"POPUP" => array(
			"LOGOUT_CONFIRM" => "您確定要登出系統？"
		)
	),
	"CONTROL" => array(
		"ONLINE_DEVICE_LIST" => "上線裝置列表",
		"SEARCH" => "搜尋",
		"LOADING" => "讀取中...",
		"NONE" => "無裝置",
		"NOT_FOUND" => "無符合裝置",
		"OFFLINE_DEVICE_LIST" => "離線裝置列表",
		"SETTING_FILE_RESTORE" => "設定檔還原",
		"NO_SETTING_FILE_AVAILABLE_FOR_RESTORE" => "暫無設定檔可供還原。",
		"TIME" => "時間",
		"SIZE" => "大小",
		"ACTION" => "動作",
		"RESTORE" => "還原",
		"CLOSE" => "關閉",
		"FIRMWARE_UPDATE" => "韌體更新",
		"AUTO_SEARCH_AND_DOWNLOAD_LASTEST_FIRMWARE" => "自動搜尋並下載最新韌體檔",
		"SEARCH_AND_DOWNLOAD_FIRMWARE_AUTO" => "IoTstar將自動在官網上搜尋並下載最新的韌體檔，您必須確認您的IoTstar是可以對外連線。",
		"SELECT_FIRMWARE_ON_THIS_COMPUTER" => "選擇此電腦上的韌體檔",
		"DOWNLOAD_AND_SELECT_FIRMWARE_MANUAL" => "手動選擇此電腦上的韌體檔，您必須自行先下載韌體檔。",
		"PLEASE_SELECT_HEX_FORMAT_FIRMWARE_ON_THIS_COMPUTER" => "請選擇在您電腦中的韌體檔(HEX格式)：",
		"FIRMWARE" => "韌體檔",
		"BROWSE" => "瀏覽...",
		"SELECT_CORRECT_FIRMWARE_TO_AVOID_UPDATE_FAIL" => "請選擇裝置所對應的韌體檔，以免韌體更新時發生錯誤。",
		"MODEL_NAME_AND_NICKNAME" => "型號 / 名稱",
		"VERSION" => "韌體版本",
		"PROGRESS" => "更新進度",
		"NO_ONLINE_DEVICE" => "無上線裝置。",
		"UPLOAD" => "上傳",
		"UPDATE" => "更新",
		"ADMIN_ALREADY_LOGIN" => "管理員已登入。",
		"FIRMWARE_IS_LATEST_VERSION" => "已經是最新版韌體。",
		"DOWNLOAD_FIRMWARE_FAILED" => "下載韌體檔至IoTstar時發生錯誤。",
		"UPLOAD_FIRMWARE_FAILED" => "上傳韌體檔至IoTstar時發生錯誤。",
		"SEND_FIRMWARE_FAILED" => "傳送韌體檔至裝置時發生錯誤。",
		"FIRMWARE_NOT_CORRECT" => "韌體檔不正確。",
		"FREE_SPACE_NOT_ENOUGHT" => "磁碟空間不足。",
		"UNZIP_FIRMWARE_FAILED" => "解壓縮韌體檔失敗。",
		"UPDATE_FIRMWARE_FAILED" => "韌體更新失敗。",
		"UPDATE_FIRMWARE_SUCCESS" => "韌體更新成功。",
		"TIP" => array(
			"SHARE_BY_USER" => "此裝置由 %username% 與您分享。",
			"REMOTE_CONTROL" => "遠端裝置維護",
			"SETTING_FILE_RESTORE" => "設定檔還原",
			"REMOVE_DEVICE_FROM_OFFLINE_LIST" => "從離線裝置列表中移除此裝置(含所有備份的設定檔)"
		),
		"POPUP" => array(
			"ARE_YOU_SURE_RESTORE_SETTING_FILE" => "您確定要還原此設定檔至所選擇的裝置嗎？",
			"RESTORE_SUCCESSFULLY" => "還原成功。",
			"PERMISSION_DENIED" => "還原失敗。您沒有控制此裝置的權限。",
			"DEVICE_OFFLINE" => "還原失敗。此裝置可能已經離線。",
			"SETTING_FILE_REMOVED" => "還原失敗。設定檔可能已經被移除。",
			"REMOVE_SUCCESSFULLY" => "移除成功。",
			"ARE_YOU_SURE_REMOVE_DEVICE_FROM_OFFLINE_LIST" => "您確定要從離線裝置列表中移除此裝置？此動作會一併移除所有備份的設定檔。",
			"ARE_YOU_SURE_UPDATE_FIRMWARE" => "您確定要進行韌體更新？更新時請勿關閉或離開本頁面，以免造成更新失敗。"
		)
	),
	"SETTING" => array(
		"SETTING" => "設定",
		"PASSWORD" => "密碼",
		"CURRENT_PASSOWRD" => "目前密碼：",
		"NEW_PASSOWRD" => "新密碼：",
		"RETYPE_NEW_PASSOWRD" => "再次輸入新密碼：",
		"INFORMATION" => "資訊",
		"NICKNAME" => "名稱：",
		"EMAIL_ADDRESS" => "電子郵件：",
		"COMPANY" => "公司：",
		"COUNTRY" => "國家 / 地區：",
		"DEVICE_SHARE" => "裝置分享",
		"DEVICE_SHARE_DESCRIPTION" => "將您管控的裝置分享給其他帳號使用，但該帳號只擁有對裝置的I/O資料或電力資料進行查詢的權限。",
		"SHARE_USERNAME" => "帳號",
		"SHARE_NICKNAME" => "名稱",
		"SHARE_ACTION" => "動作",
		"NO_SHARE_ACCOUNT" => "無設定分享帳號。",
		"ADD" => "加入",
		"REMOVE" => "移除",
		"LINE_BOT_DESCRIPTION" => "Bot Service功能讓您可以透過LINE App與您的控制器互動。以下列出允許與您的控制器互動的LINE帳號。",
		"LINE_BOT_STATUS" => "狀態",
		"LINE_BOT_NICKNAME" => "名稱",
		"LINE_BOT_ACTION" => "動作",
		"NO_LINE_BOT_ACCOUNT" => "無LINE帳號加入此Bot。",
		"CLOSE" => "關閉",
		"POPUP" => array(
			"SAVE_SUCCESS" => "儲存成功。",
			"SAVE_FAILED" => "儲存失敗。請重新檢查設定。",
			"SAVE_SUCCESS_AND_VALIDATE_EMAIL" => "儲存成功。若您有修改電子郵件位址，請您點擊我們寄給您的電子郵件中的連結來完成修改。",
			"EXIST_EMAIL" => "修改電子郵件位址失敗。此電子郵件已經被使用過。",
			"MODIFY_EMAIL_SUCCESS" => "修改電子郵件位址成功。",
			"MODIFY_EMAIL_FAILED" => "修改電子郵件位址失敗。也許您已經修改過了。",
			"USERNAME_IS_EMPTY" => "加入失敗。帳號不能為空白。",
			"ARE_YOU_SURE_ADD_SHARE_WITH_USERNAME" => "您確定要將裝置分享給 %username% 嗎？",
			"ADD_SUCCESSFULLY" => "加入成功。",
			"ARE_YOU_SURE_REMOVE_SHARE_WITH_USERNAME" => "您確定要移除與 %username% 間的分享嗎？",
			"REMOVE_SUCCESSFULLY" => "移除成功。",
			"ENABLE_SUCCESS" => "啟用成功。",
			"DISABLE_SUCCESS" => "停用成功。",
			"MODIFY_SUCCESS" => "修改成功。",
			"NICKNAME_IS_EMPTY" => "修改失敗。名稱不能為空白。",
			"ARE_YOU_SURE_REMOVE_ACCOUNT" => "您確定要移除此帳號嗎？",
			"NICKNAME_SETTING" => "名稱設定",
			"PLEASE_ENTER_THE_NEW_NICKNAME" => "請輸入新的名稱：",
			"SCAN_QR_CODE" => "使用LINE App掃描"
		),
		"AJAX" => array(
			"EMPTY_FIELD" => "此欄位不能為空白。",
			"ILLEGAL_CHARACTER" => "此欄位不允許特殊字元。",
			"LENGTH_LONGER_THEN_16_OR_SHORTER_THEN_6" => "此欄位長度必須介於6-16的字元間。",
			"NOT_MATCH_NEW_PASSWORD" => "此欄位與新密碼欄位不符。",
			"INVALID_PASSWORD" => "密碼不正確。",
			"LENGTH_LONGER_THEN_100" => "此欄位長度不能超過100個字元。",
			"ILLEGAL_FORMAT" => "電子郵件位址格式不正確。",
			"LENGTH_LONGER_THEN_50" => "此欄位長度不能超過50個字元。",
			"EXIST_EMAIL" => "此電子郵件已經被使用過。",
			"USERNAME_IS_EMPTY" => "加入失敗。帳號不能為空白。",
			"USERNAME_IS_YOURSELF" => "加入失敗。此帳號為您自己的帳號。",
			"USERNAME_NOT_EXIST" => "加入失敗。查無此帳號。",
			"ALREADY_SHARE_WITH_THIS_USERNAME" => "加入失敗。您已經分享給此帳號過了。"
		),
		"EMAIL" => array(
			"SUBJECT" => "電子郵件驗證",
			"CONTENT" => "請點擊以下連結完成電子郵件位址的修改。"
		)
	),
	"REALTIME_IO" => array(
		"SELECT_CHANNEL" => "選擇通道",
		"DEVICE" => "裝置",
		"GROUP" => "群組",
		"BUILD_IN" => "內建",
		"OTHER" => "其他",
		"INTERNAL_REGISTER" => "內部暫存器",
		"CHANNEL" => "通道",
		"DI_COUNTER_WITH_NO" => "DI計數器%channel%",
		"DO_COUNTER_WITH_NO" => "DO計數器%channel%",
		"INTERNAL_REGISTER_WITH_NO" => "內部暫存器%channel%",
		"CANCEL" => "取消",
		"REALTIME_IO_DATA" => "即時I/O通道資訊",
		"MODULE" => "模組",
		"MAX" => "最大值",
		"MIN" => "最小值",
		"VALUE" => "數值",
		"ACTION" => "動作",
		"ADD" => "新增",
		"REMOVE" => "移除",
		"NO_DATA" => "無資料",
		"CHANNEL_WITH_COLON" => "通道:",
		"TIME_WITH_COLON" => "時間:",
		"VALUE_WITH_COLON" => "數值:",
		"TIP" => array(
			"SHARE_BY_USER" => "此裝置由 %username% 與您分享。",
			"SETUP_SENDING_SETTING_FIRST" => "無法點選？請先將裝置韌體版本升級至%version%以上，並在『%sending_setting%』頁面設定欲回送資料即可。",
			"REALTIME_DATA_SENDING_SETTING" => "即時資料傳送設定"
		),
		"POPUP" => array(
			"VALUE_SETTING" => "數值設定",
			"PLEASE_ENTER_THE_VALUE" => "請輸入數值：",
			"VALUE_IS_EMPTY" => "修改失敗。數值不能為空白。",
			"VALUE_MUST_BE_NUMBER" => "修改失敗。數值必須是數字。",
			"MODIFIED_SUCCESSFULLY" => "修改成功。"
		)
	),
	"REALTIME_ENERGY" => array(
		"SELECT_A_LOOP" => "選擇迴路",
		"DEVICE" => "裝置",
		"LOOP" => "迴路",
		"REALTIME_POWER_DATA" => "即時電力資訊",
		"V" => "電壓",
		"I" => "電流",
		"KW" => "實功率",
		"KVAR" => "無效功率",
		"KVA" => "視在功率",
		"PF" => "功率因素",
		"MAX" => "最大值",
		"MIN" => "最小值",
		"PHASE_A" => "相位A",
		"PHASE_B" => "相位B",
		"PHASE_C" => "相位C",
		"TOTAL" => "三相總和",
		"NO_DATA" => "無資料",
		"TIME_WITH_COLON" => "時間:",
		"VALUE_WITH_COLON" => "數值:",
		"TIP" => array(
			"SHARE_BY_USER" => "此裝置由 %username% 與您分享。",
			"SETUP_SENDING_SETTING_FIRST" => "無法點選？請先將裝置韌體版本升級至%version%以上，並在『%sending_setting%』頁面設定欲回送資料即可。",
			"REALTIME_DATA_SENDING_SETTING" => "即時資料傳送設定"
		)
	),
	"HISTORY_IO" => array(
		"DEVICE" => "裝置",
		"GROUP" => "群組",
		"BUILD_IN" => "內建",
		"OTHER" => "其他",
		"INTERNAL_REGISTER" => "內部暫存器",
		"CHANNEL" => "通道",
		"DI_COUNTER_WITH_NO" => "DI計數器%channel%",
		"DO_COUNTER_WITH_NO" => "DO計數器%channel%",
		"INTERNAL_REGISTER_WITH_NO" => "內部暫存器%channel%",
		"REMOVE" => "移除",
		"TODAY" => "今日",
		"YESTERDAY" => "昨日",
		"TODAY_LAST_WEEK" => "上週同日",
		"SPECIFY_DATE" => "自訂日期",
		"SUN" => "日",
		"MON" => "一",
		"TUE" => "二",
		"WED" => "三",
		"THU" => "四",
		"FRI" => "五",
		"SAT" => "六",
		"MODULE_ANALYSIS" => "I/O通道資訊分析",
		"NO_DATA" => "無資料",
		"MODULE" => "模組",
		"TIME" => "時間",
		"MAX" => "最大值",
		"MIN" => "最小值",
		"ACTION" => "動作",
		"SYNC_SELECTER" => "選擇同步",
		"ADD" => "新增",
		"SELECT_CHANNEL" => "選擇通道",
		"CANCEL" => "取消",
		"CHANNEL_WITH_COLON" => "通道:",
		"TIME_WITH_COLON" => "時間:",
		"VALUE_WITH_COLON" => "數值:",
		"TIP" => array(
			"SHARE_BY_USER" => "此裝置由 %username% 與您分享。"
		)
	),
	"HISTORY_ENERGY" => array(
		"DEVICE" => "裝置",
		"GROUP" => "群組",
		"LOOP" => "迴路",
		"1ST_MONTH" => "第一個月",
		"2ND_MONTH" => "第二個月",
		"3RD_MONTH" => "第三個月",
		"JAN" => "一月",
		"FEB" => "二月",
		"MAR" => "三月",
		"APR" => "四月",
		"MAY" => "五月",
		"JUN" => "六月",
		"JUL" => "七月",
		"AUG" => "八月",
		"SEP" => "九月",
		"OCT" => "十月",
		"NOV" => "十一月",
		"DEC" => "十二月",
		"SUN" => "日",
		"MON" => "一",
		"TUE" => "二",
		"WED" => "三",
		"THU" => "四",
		"FRI" => "五",
		"SAT" => "六",
		"SUN_LONG" => "星期日",
		"MON_LONG" => "星期一",
		"TUE_LONG" => "星期二",
		"WED_LONG" => "星期三",
		"THU_LONG" => "星期四",
		"FRI_LONG" => "星期五",
		"SAT_LONG" => "星期六",
		"TODAY" => "今日",
		"YESTERDAY" => "昨日",
		"TODAY_LAST_WEEK" => "上週同日",
		"SPECIFY_DATE" => "自訂日期",
		"THIS_WEEK" => "本週",
		"LAST_WEEK" => "上週",
		"THIS_MONTH" => "本月",
		"LAST_MONTH" => "上月",
		"THIS_MONTH_LAST_YEAR" => "去年同月",
		"THIS_QUARTER" => "本季",
		"LAST_QUARTER" => "上季",
		"THIS_QUARTER_LAST_YEAR" => "去年同季",
		"THIS_YEAR" => "今年",
		"LAST_YEAR" => "去年",
		"SELECT_A_LOOP_OR_GROUP" => "選擇迴路/群組",
		"ENERGY_ANALYSIS" => "用電量分析",
		"DAY" => "日",
		"WEEK" => "週",
		"MONTH" => "月",
		"QUARTER" => "季",
		"YEAR" => "年",
		"TIME" => "時間",
		"NO_DATA" => "無資料",
		"ENERGY_CONSUMPTION" => "用電度數",
		"KWH" => "度",
		"COMPARED" => "對比",
		"CARBON_EMISSIONS" => "碳排放量",
		"KG" => "公斤",
		"GROWTH_RATE" => "增減幅度",
		"POWER_DATA_ANALYSIS" => "電力資料分析",
		"V" => "電壓",
		"I" => "電流",
		"KW" => "實功率",
		"KVAR" => "無效功率",
		"KVA" => "視在功率",
		"PF" => "功率因素",
		"MAX" => "最大值",
		"MIN" => "最小值",
		"PHASE_A" => "相位A",
		"PHASE_B" => "相位B",
		"PHASE_C" => "相位C",
		"TOTAL" => "三相總和",
		"CARBON_EMISSIONS_SETTING" => "碳排放量設定",
		"FACTOR" => "係數",
		"TIME_WITH_COLON" => "時間:",
		"VALUE_WITH_COLON" => "數值:",
		"TIP" => array(
			"SHARE_BY_USER" => "此裝置由 %username% 與您分享。"
		),
		"POPUP" => array(
			"FACTOR_IS_EMPTY" => "修改失敗。係數不能為空白。",
			"FACTOR_MUST_BE_NUMBER" => "修改失敗。係數必須是數字。",
			"FACTOR_MUST_BE_GREATER_THAN_ZERO" => "修改失敗。係數必須大於0。"
		)
	),
	"REPORT_SERVICE" => array(
		"DAILY_REPORT" => "日報表",
		"WEEKLY_REPORT" => "週報表",
		"MONTHLY_REPORT" => "月報表",
		"QUARTERLY_REPORT" => "季報表",
		"ANNUAL_REPORT" => "年報表",
		"REPORT_DATE" => "資料日期",
		"COMPARE_DATE" => "對比日期",
		"PRINT_DATE" => "列印日期",
		"TIME" => "時間",
		"DATE" => "日期",
		"MONTH_TITLE" => "月份",
		"MAX_DEMAND" => "最高需量(kW)",
		"KWH_TITLE" => "用電量(度)",
		"PF" => "平均功率因數(%)",
		"I" => "平均電流(A)",
		"I_A" => "平均電流 A相(A)",
		"I_B" => "平均電流 B相(A)",
		"I_C" => "平均電流 C相(A)",
		"V" => "平均電壓(V)",
		"V_A" => "平均電壓 A相(V)",
		"V_B" => "平均電壓 B相(V)",
		"V_C" => "平均電壓 C相(V)",
		"KVA" => "平均視在功率(kVA)",
		"KVAR" => "平均無效功率(kvar)",
		"NO_DATA" => "無資料",
		"DAILY_MAXIMUM_DEMAND" => "本日最高需量",
		"WEEKLY_MAXIMUM_DEMAND" => "本週最高需量",
		"MONTHLY_MAXIMUM_DEMAND" => "本月最高需量",
		"QUARTERLY_MAXIMUM_DEMAND" => "本季最高需量",
		"ANNUAL_MAXIMUM_DEMAND" => "本年最高需量",
		"EVEN_TIME" => "發生時間",
		"KWH" => "度",
		"DAY" => "日",
		"WEEK" => "週",
		"WEEKDAY" => "星期",
		"MONTH" => "月",
		"QUARTER" => "季",
		"YEAR" => "年",
		"DEVICE" => "裝置",
		"GROUP" => "群組",
		"LOOP" => "迴路",
		"SUN" => "日",
		"MON" => "一",
		"TUE" => "二",
		"WED" => "三",
		"THU" => "四",
		"FRI" => "五",
		"SAT" => "六",
		"header_SUN" => "日",
		"header_MON" => "一",
		"header_TUE" => "二",
		"header_WED" => "三",
		"header_THU" => "四",
		"header_FRI" => "五",
		"header_SAT" => "六",
		"JAN" => "一月",
		"FEB" => "二月",
		"MAR" => "三月",
		"APR" => "四月",
		"MAY" => "五月",
		"JUN" => "六月",
		"JUL" => "七月",
		"AUG" => "八月",
		"SEP" => "九月",
		"OCT" => "十月",
		"NOV" => "十一月",
		"DEC" => "十二月",
		"TIP" => array(
			"SHARE_BY_USER" => "此裝置由 %USERNAME% 與您分享。",
			"CREATE" => "新增",
			"EDIT" => "編輯",
			"COPY" => "複製",
			"REMOVE" => "移除"
		),
		"SELECT_A_LOOP_OR_GROUP" => "選擇電錶迴路、I/O通道或群組",
		"POWER_METER_LOOP_REPORT" => "電錶迴路報表",
		"POWER_METER_GROUP_REPORT" => "電錶迴路群組報表",
		"IO_CHANNEL_REPORT" => "I/O通道報表",
		"IO_CHANNEL_GROUP_REPORT" => "I/O通道群組報表",
		"IO_CHANNEL" => "I/O通道",
		"TEMPLATE_MANAGEMENT" => "範本管理",
		"DOWNLOAD_REPORT_PDF" => "下載PDF",
		"DOWNLOAD_REPORT" => "下載EXCEL",
		"DOWNLOAD" => "下載",
		"TEMPLATE_SETTING"=> "設定範本",
		"TEMPLATE_NAME"=> "範本名稱",
		"SELECT_TEMPLATE"=> "範本選擇",
		"HEADER" => "頁首",
		"FOOTER" => "頁尾",
		"NO_TEMPLATE" => "無範本",
		"DELETE_THIS_TEMPLATE" => "您確定要刪除此範本？",
		"DEFAULT" => "預設",
		"APPLY_TEMPLATE" => "套用範本",
		"CLEAR_TEMPLATE" => "清除範本",
		"CLOSE" => "關閉",
		"GROUP_LOOP_STATISTICS" => "迴路統計",
		"GROUP_LOOP_COMPARISON" => "迴路比較",
		"VALUE_TYPE" => "數值種類",
		"SUMMARY" => "摘要",
		"REPORT_TYPE" => "報表種類",
		"DAILY_TOTAL_MODULE_ENERGY_CONSUMPTION" => "本日各別用電量",
		"THIS_WEEK_MODULE_ENERGY_CONSUMPTION" => "本週各別用電量",
		"THIS_MONTH_MODULE_ENERGY_CONSUMPTION" => "本月各別用電量",
		"THIS_QUARTER_MODULE_ENERGY_CONSUMPTION" => "本季各別用電量",
		"THIS_YEAR_MODULE_ENERGY_CONSUMPTION" => "本年各別用電量",
		"DAILY_TOTAL_ENERGY_CONSUMPTION" => "本日總用電量",
		"WEEKLY_TOTAL_ENERGY_CONSUMPTION" => "本週總用電量",
		"MONTHLY_TOTAL_ENERGY_CONSUMPTION" => "本月總用電量",
		"QUARTERLY_TOTAL_ENERGY_CONSUMPTION" => "本季總用電量",
		"ANNUAL_TOTAL_ENERGY_CONSUMPTION" => "本年總用電量",
		"TODAY_MAXIMUM" => "本日最大值",
		"TODAY_MINIMUM" => "本日最小值",
		"TODAY_AVERAGE" => "本日平均值",
		"TODAY_TOTAL_VALUE" => "本日總和值",
		"THIS_WEEK_MAXIMUM" => "本週最大值",
		"THIS_WEEK_MINIMUM" => "本週最小值",
		"THIS_WEEK_AVERAGE" => "本週平均值",
		"THIS_WEEK_TOTAL_VALUE" => "本週總和值",
		"THIS_MONTH_MAXIMUM" => "本月最大值",
		"THIS_MONTH_MINIMUM" => "本月最小值",
		"THIS_MONTH_AVERAGE" => "本月平均值",
		"THIS_MONTH_TOTAL_VALUE" => "本月總和值",
		"THIS_QUARTER_MAXIMUM" => "本季最大值",
		"THIS_QUARTER_MINIMUM" => "本季最小值",
		"THIS_QUARTER_AVERAGE" => "本季平均值",
		"THIS_QUARTER_TOTAL_VALUE" => "本季總和值",
		"THIS_YEAR_MAXIMUM" => "本年最大值",
		"THIS_YEAR_MINIMUM" => "本年最小值",
		"THIS_YEAR_AVERAGE" => "本年平均值",
		"THIS_YEAR_TOTAL_VALUE" => "本年總和值",
		"TODAY_MAX_TIME_OCCURRENCE" => "本日最大值發生時間",
		"THIS_WEEK_MAX_TIME_OCCURRENCE" => "本週最大值發生時間",
		"THIS_MONTH_MAX_TIME_OCCURRENCE" => "本月最大值發生時間",
		"THIS_QUARTER_MAX_TIME_OCCURRENCE" => "本季最大值發生時間",
		"THIS_YEAR_MAX_TIME_OCCURRENCE" => "本年最大值發生時間",
		"TODAY_MIN_TIME_OCCURRENCE" => "本日最小值發生時間",
		"THIS_WEEK_MIN_TIME_OCCURRENCE" => "本週最小值發生時間",
		"THIS_MONTH_MIN_TIME_OCCURRENCE" => "本月最小值發生時間",
		"THIS_QUARTER_MIN_TIME_OCCURRENCE" => "本季最小值發生時間",
		"THIS_YEAR_MIN_TIME_OCCURRENCE" => "本年最小值發生時間",
		"MAX" => "最大值",
		"MIN" => "最小值",
		"AVERAGE" => "平均值",
		"FINAL" => "最後值",
		"TOTAL" => "總和值",
		"SINGLE_PERIOD" => "單一時段",
		"COMPARE_PERIOD" => "對比時段",
		"COLUMN_DISPLAY" => "欄位顯示",
		"ORIENTATION" => "方向",
		"PORTRAIT" => "直向",
		"LANDSCAPE" => "橫向",
		"MARGINS" => "邊界",
		"REPORT_SERVICE_NOT_AVAILABLE_OR_EXPIRED" => "報表服務尚未啟用或試用期已結束",
		"CONTACT_SYSTEM_ADMINISTRATOR" => "若您要使用此服務，請聯絡管理員。",
		"LENGTH_LONGER_THEN_50" => "此欄位長度不能超過50個字元。"
	),
	"GROUP" => array(
		"NO_GROUP_SETTING" => "沒有任何群組存在",
		"ADD_GROUP_CLICK" => "如要新增群組，請點 <input type='button' value='群組新增' class='control-button' onClick='onClickNewGroupButton();'> 按鈕",
		"NO_LOOP" => "此群組中沒有迴路",
		"ADD_LOOP_CLICK" => "如要新增迴路，請點選右下角的"." '+' "."按鈕",
		"GROUP" => "群組",
		"NEW" => "新增",
		"EDIT" => "編輯",
		"REMOVE" => "移除",
		"BACK" => "返回",
		"REMOVE_LOOP" => "移除迴路",
		"ADD_GROUP" => "群組新增",
		"GROUP_NAME" => "群組名稱",
		"EDIT_GROUP" => "群組編輯",
		"DELETE_GROUP" => "群組刪除",
		"DELETE_GROUP_SURE" => "您確定要刪除群組嗎?",
		"REMOVE_LOOP_SURE" => "您確定要移除已選擇的迴路嗎?",
		"ADD_LOOP" => "迴路新增",
		"SEARCH" => "搜尋",
		"NO_DEVICE" => "無裝置",
		"NO_MODULE" => "無模組",
		"NO_MATCH_MODULE" => "無符合模組",
		"SELECTED_LOOP" => "已選擇<label id='ck_num'></label>個迴路",
		"LOOP" => "迴路",
		"GROUP_NAME_NOT_EMPTY" => "群組名稱不能為空白。",
		"TIP" => array(
			"SHARE_BY_USER" => "此裝置由 %username% 與您分享。"
		)
	),
	"IO_GROUP" => array(
		"INTERNAL_REGISTER_WITH_NO" => "內部暫存器%channel%",	
		"INTERNAL_REGISTER" => "內部暫存器",
		"REMOVE_CHANNEL" => "移除通道",
		"REMOVE_CHANNEL_SURE" => "您確定要移除已選擇的通道嗎?",
		"SELECTED_CHANNEL" => "已選擇<label id='ck_num'></label>個通道",
		"ADD_CHANNEL" => "通道新增",
		"NO_CHANNEL" => "此群組中沒有通道",
		"ADD_CHANNEL_CLICK" => "如要新增通道，請點選右下角的"." '+' "."按鈕",
		"CHANNEL_COUNTER_WITH_NO" => "%channel_type%計數器%channel%",
		"CHANNEL_COUNTER" => "%channel_type%計數器",
		"OTHER" => "其他",
		"BUILD_IN" => "內建"
	),
	"NOTIFICATION" => array(
		"ERROR" => array(
			"10000" => "系統資訊：%variable0%",
			"10001" => "使用者自 %variable0% 登入成功。",
			"10002" => "使用者自 %variable0% 登出成功。",
			"10003" => "裝置 %variable0% 序號：%variable1% 上線。",
			"10004" => "裝置 %variable0% 序號：%variable1% 離線。",
			"10100" => "設定資訊：%variable0%.",
			"10101" => "使用者修改密碼。",
			"10102" => "使用者更新帳號資訊。",
			"10103" => "使用者修改事件通知設定。",
			"10104" => "裝置 %variable0% 序號：%variable1% 上傳設定檔。",
			"10105" => "使用者清除資料庫成功。",
			"10106" => "使用者啟用歷史資料匯入資料庫功能。",
			"10107" => "使用者停用歷史資料匯入資料庫功能。",
			"10108" => "傳送事件通知電子郵件成功。",
			"10109" => "使用者移除裝置 %variable0% 序號：%variable1% 相關資料表成功。",
			"10110" => "使用者清除裝置 %variable0% 序號：%variable1% 的模組 %variable2% UID：%variable3% 資料表成功。",
			"10111" => "使用者移除裝置 %variable0% 序號：%variable1% 的模組 %variable2% UID：%variable3% 資料表成功。",
			"10112" => "使用者啟用即時資料匯入資料庫功能。",
			"10113" => "使用者停用即時資料匯入資料庫功能。",
			"10114" => "使用者清除裝置資料表成功。",
			"10115" => "使用者清除事件成功。",
			"10116" => "使用者開始更新裝置 %variable0% 序號：%variable1% 韌體。",
			"10301" => "裝置 %variable0% 序號：%variable1% 上傳照片並附加了一段訊息：%variable3%。",
			"10302" => "裝置 %variable0% 序號：%variable1% 上傳影片並附加了一段訊息：%variable3%。",
			"30000" => "警告：%variable0%",
			"30001" => "磁碟空間不足（%variable0% MB）。",
			"30002" => "使用者自 %variable0% 登入失敗。",
			"30003" => "模組 %variable2% UID：%variable3% 已從裝置 %variable0% 序號：%variable1% 上移除。",
			"50000" => "例外：%variable1%（錯誤代碼：%variable0%）",
			"50001" => "解析裝置 %variable1% 序號：%variable2% 的模組 %variable3% UID：%variable4% 資料失敗。（錯誤代碼：%variable0%）",
			"50002" => "裝置 %variable1% 序號：%variable2% 自 %variable0% 登入失敗。",
			"50006" => "傳送事件通知電子郵件失敗。",
			"50103" => "解析裝置 %variable0% 序號：%variable1% 設定檔失敗。",
			"50200" => "資料庫運作例外：%variable1%（錯誤代碼：%variable0%）",
			"50202" => "資料庫沒有足夠的空間。",
			"50203" => "資料庫空間已使用超過90%。",
			"50211" => "修改資料庫表格 %variable1% 失敗。（錯誤代碼：%variable0%）",
			"50212" => "使用者清除資料庫失敗。",
			"50213" => "使用者移除裝置 %variable0% 序號：%variable1% 相關資料表失敗。",
			"50214" => "使用者清除裝置 %variable0% 序號：%variable1% 的模組 %variable2% UID：%variable3% 資料表失敗。",
			"50215" => "使用者移除裝置 %variable0% 序號：%variable1% 的模組 %variable2% UID：%variable3% 資料表失敗。"
		),
		"EVENT_LIST" => "事件列表",
		"NO_EVENT_NOTIFICATION" => "沒有任何事件通知",
		"TIME" => "時間",
		"EVENT" => "事件",
		"EXPORT" => "匯出",
		"CLEAR" => "清除",
		"READ" => "全設為已讀",
		"LOADING" => "讀取中...",
		"SYSTEM_EVENT" => "系統事件",
		"VIDEO_EVENT" => "影像事件",
		"WHICH_EVENT_TYPE_YOU_WANT_TO_CLEAR" => "您想要清除哪些事件種類？",
		"TIME_RANGE" => "時間範圍",
		"ALL" => "全部",
		"OLDER_THEN_1_MON" => "1個月前",
		"OLDER_THEN_3_MON" => "3個月前",
		"OLDER_THEN_6_MON" => "6個月前",
		"OLDER_THEN_1_YEAR" => "1年前",
		"OLDER_THEN_2_YEAR" => "2年前",
		"OLDER_THEN_3_YEAR" => "3年前",
		"MAKE_SURE_YOU_WANT_TO_CLEAR_EVENT_TYPE_YOU_SELECTED" => "您確定要清除您所選擇的事件種類？",
		"MAKE_SURE_YOU_WANT_TO_MAKE_ALL_AS_READ" => "您確定要將所有事件設定為已讀取？"
	),
	"DB_SETTING" => array(
		"NOTIFICATION_SETTINGS" => "事件通知設定",
		"EVENT" => "事件項目",
		"INSUFFICIENT_DATABASE_SPACE" => "資料庫空間不足",
		"DATABASE_PROCESSING_ERROR_OR_TRANSACTION" => "無法與資料庫建立連線",
		"MODULE_SETTINGS_CHANGE" => "模組設定異動",
		"DEVICE_DISCONNECTED" => "裝置離線",
		"UNKNOWN_TO_TRY_TO_LOG_IN_MORE_THAN_TEN_TIMES" => "使用錯誤的密碼嘗試登入超過十次",
		"CLEAR_DATABASE_DATA" => "清除資料庫資料",
		"ALL_MODULE_DATA_WILL_BE_REMOVED_AND_CAN_NOT_BE_RECOVERED_AFTER_EXECUTION" => "指令執行後所有模組與群組儲存於資料庫的資料都將被移除且無法復原。",
		"PASSWORD" => "密碼：",
		"SAVE" => "儲存",
		"SAVE_COMPLETE" => "儲存成功。",
		"SAVE_FAILED" => "儲存失敗。",
		"PLEASE_ENTER_A_PASSWORD" => "請輸入密碼。",
		"WHETHER_TO_CLEAR_THE_DATABASE_DATA" => "您確定要清除資料庫資料嗎？",
		"CLEAR" => "清除",
		"CLEAR_COMPLETE" => "清除成功。",
		"FAILED_TO_CLEAR" => "清除失敗。",
		"PASSWORD_ERROR" => "清除失敗。因密碼輸入錯誤。",
		"DATA_IMPORT" => "資料庫匯入",
		"SET_WHETHER_IO_MODULES_AND_METER_DATA_ARE_IMPORTED_INTO_THE_DATABASE" => "設定是否將I/O模組資料及電錶電力資料匯入資料庫。",
		"ENABLED" => "啟用",
		"HISTORY_IMPORT_ENABLED" => "歷史資料",
		"REALTIME_IMPORT_ENABLED" => "即時資料",
		"SELECT_THE_EVENT_NOTIFICATION_YOU_LIKE_TO_RECEIVE_VIA_EMAIL_BELOW" => "在下方勾選您想要透過電子郵件收到的事件通知項目。"
	),
	"DB_INFO" => array(
		"DEVICE" => "裝置",
		"MODULE" => "模組",
		"TABLE_DESCRIPTION" => "表格描述",
		"TABLE_NAME" => "表格名稱",
		"ACTION" => "動作",
		"BUILD_IN" => "內建",
		"OTHER" => "其他",
		"INTERNAL_REGISTER" => "內部暫存器",
		"CLEAR" => "清除",
		"REMOVE" => "移除",
		"CLEAR_DATABASE" => "清除資料庫",
		"CLEAR_DATABASE_DESCRIPTION" => "此功能能幫助您清除過舊的資料，以釋放資料庫空間。請選擇您要清除多久以前的舊資料後按下清除按鈕。",
		"OLDER_THEN_1_MON" => "1個月前",
		"OLDER_THEN_3_MON" => "3個月前",
		"OLDER_THEN_6_MON" => "6個月前",
		"OLDER_THEN_1_YEAR" => "1年前",
		"OLDER_THEN_2_YEAR" => "2年前",
		"OLDER_THEN_3_YEAR" => "3年前",
		"DATABASE_USAGE" => "您已使用了 %size% 的資料庫空間",
		"DATABASE_USAGE_WITH_MAXSIZE" => "您已使用了 %max_size% 中的 %size% (%percent%%) 的資料庫空間",
		"NO_DATA_SHEET_EXISTS" => "無任何資料表存在",
		"THE_INFORMATION_WILL_BE_DISPLAYED_WHEN_THE_DEVICE_RETURNS_DATA" => "待裝置回傳資料後即會顯示相關資訊",
		"MODULE_HAS_BEEN_REMOVED_FROM_THE_DEVICE_AND_THE_TABLE_WILL_NO_LONGER_BE_UPDATED" => "模組已從裝置上移除，此表格不會再持續更新。",
		"MODULE_HAS_BEEN_REMOVED_FROM_THIS_DEVICE_AND_SOME_TABLES_WILL_NOT_BE_UPDATED" => "有模組已從此裝置上移除，部分表格不會再持續更新。",
		"REMOVE_ALL_RELEVANT_INFORMATION_ABOUT_THIS_DEVICE_INCLUDING_GROUPING" => "移除此裝置所有相關資料，包含I/O通道與電錶迴路分群資料等。",
		"MAKE_SURE_YOU_WANT_TO_CLEAR_DATABASE_DATA" => "您確定要清除資料庫資料？",
		"MAKE_SURE_YOU_WANT_TO_CLEAR_THIS_MODULE_DATA" => "您確定要清除此模組資料？",
		"CLEAR_COMPLETE" => "清除成功。",
		"FAILED_TO_CLEAR" => "清除失敗。",
		"MAKE_SURE_YOU_WANT_TO_REMOVE_THIS_MODULE" => "您確定要移除此模組資料？",
		"MAKE_SURE_YOU_WANT_TO_REMOVE_THIS_DEVICE_DATA" => "您確定要移除此裝置資料？",
		"REMOVE_COMPLETE" => "移除成功。",
		"FAILED_TO_REMOVE" => "移除失敗。",
		"REAL_TIME_DATA" => "即時資料",
		"HISTORICAL_DATA" => "歷史資訊",
		"COPY_DATA_TABLE" => "複製資料表",
		"SOURCE" => "來源",
		"DESTINATION" => "目標",
		"COPY" => "複製",
		"CLOSE" => "關閉",
		"SOURCE_AND_DESTINATION_MODULES_MUST_BE_SAME_MODEL" => "來源與目標模組必須是相同型號。",
		"COPY_SUCCESSFULLY" => "複製成功。",
		"COPY_FAILED_SOURCE_AND_DESTINATION_MODULES_NOT_SAME_MODEL" => "複製失敗，來源與目標模組型號不相同。",
		"COPY_FAILED_DATA_ALREADY_EXIST_IN_DESTINATION_MODULE_TABLE" => "複製失敗，資料已經存在於目標模組中。",
		"COPY_FAILED_NOT_ENOUGH_FREE_SPACE" => "複製失敗，資料庫空間不足。",
		"COPY_FAILED_UNHANDLED_EXCEPTION" => "複製失敗，發生非預期的錯誤。"
	),
	"COUNTRY" => array(
		"1" => "阿布哈茲",
		"2" => "阿富汗",
		"3" => "阿爾巴尼亞",
		"4" => "阿爾及利亞",
		"5" => "美屬薩摩亞(美國)",
		"6" => "安道爾",
		"7" => "安哥拉",
		"8" => "安圭拉(英國)",
		"9" => "安地卡及巴布達",
		"10" => "阿根廷",
		"11" => "亞美尼亞",
		"12" => "阿魯巴(荷蘭)",
		"13" => "澳洲",
		"14" => "奧地利",
		"15" => "亞塞拜然",
		"16" => "巴哈馬",
		"17" => "巴林",
		"18" => "孟加拉",
		"19" => "巴貝多",
		"20" => "白俄羅斯",
		"21" => "比利時",
		"22" => "貝里斯",
		"23" => "貝南",
		"24" => "百慕達(英國)",
		"25" => "不丹",
		"26" => "玻利維亞",
		"27" => "波士尼亞與赫塞哥維納",
		"28" => "波札那",
		"29" => "巴西",
		"30" => "英屬印度洋領地(英國)",
		"31" => "汶萊",
		"32" => "保加利亞",
		"33" => "布吉納法索",
		"34" => "蒲隆地",
		"35" => "柬埔寨",
		"36" => "喀麥隆",
		"37" => "加拿大",
		"38" => "維德角",
		"39" => "開曼群島(英國)",
		"40" => "中非共和國中非",
		"41" => "查德",
		"42" => "智利",
		"43" => "中國",
		"44" => "聖誕島(澳洲)",
		"45" => "科科斯(基林)群島(澳洲)",
		"46" => "哥倫比亞",
		"47" => "葛摩",
		"48" => "剛果(布)",
		"49" => "剛果(金)",
		"50" => "庫克群島(紐西蘭)",
		"51" => "哥斯大黎加",
		"52" => "象牙海岸",
		"53" => "克羅埃西亞",
		"54" => "古巴",
		"55" => "古拉索(荷蘭)",
		"56" => "賽普勒斯",
		"57" => "捷克",
		"58" => "丹麥",
		"59" => "吉布地",
		"60" => "多米尼克",
		"61" => "多明尼加",
		"62" => "厄瓜多",
		"63" => "埃及",
		"64" => "薩爾瓦多",
		"65" => "赤道幾內亞",
		"66" => "厄利垂亞",
		"67" => "愛沙尼亞",
		"68" => "衣索比亞",
		"69" => "福克蘭群島(英國、阿根廷爭議)",
		"70" => "法羅群島(丹麥)",
		"71" => "斐濟",
		"72" => "芬蘭",
		"73" => "法國",
		"74" => "法屬玻里尼西亞(法國)",
		"75" => "加彭",
		"76" => "甘比亞",
		"77" => "喬治亞",
		"78" => "德國",
		"79" => "加納",
		"80" => "直布羅陀(英國)",
		"81" => "希臘",
		"82" => "格陵蘭(丹麥)",
		"83" => "格瑞那達",
		"84" => "關島(美國)",
		"85" => "瓜地馬拉",
		"86" => "耿西(英國)",
		"87" => "幾內亞",
		"88" => "幾內亞比索",
		"89" => "蓋亞那",
		"90" => "海地",
		"91" => "宏都拉斯",
		"92" => "香港(中國)",
		"93" => "匈牙利",
		"94" => "冰島",
		"95" => "印度",
		"96" => "印尼",
		"97" => "伊朗",
		"98" => "伊拉克",
		"99" => "愛爾蘭",
		"100" => "曼島(英國)",
		"101" => "以色列",
		"102" => "義大利",
		"103" => "牙買加",
		"104" => "日本",
		"105" => "澤西(英國)",
		"106" => "約旦",
		"107" => "哈薩克",
		"108" => "肯亞",
		"109" => "吉里巴斯",
		"110" => "科索沃",
		"111" => "科威特",
		"112" => "吉爾吉斯",
		"113" => "寮國",
		"114" => "拉脫維亞",
		"115" => "黎巴嫩",
		"116" => "賴索托",
		"117" => "賴比瑞亞",
		"118" => "利比亞",
		"119" => "列支敦斯登",
		"120" => "立陶宛",
		"121" => "盧森堡",
		"122" => "澳門(中國)",
		"123" => "馬其頓",
		"124" => "馬達加斯加",
		"125" => "馬拉威",
		"126" => "馬來西亞",
		"127" => "馬爾地夫",
		"128" => "馬利",
		"129" => "馬爾他",
		"130" => "馬紹爾群島",
		"131" => "茅利塔尼亞",
		"132" => "模里西斯",
		"133" => "馬約特(法國)",
		"134" => "墨西哥",
		"135" => "密克羅尼西亞聯邦密克羅尼西亞聯邦",
		"136" => "摩爾多瓦摩爾多瓦",
		"137" => "摩納哥",
		"138" => "蒙古",
		"139" => "蒙特內哥羅",
		"140" => "蒙哲臘(英國)",
		"141" => "摩洛哥",
		"142" => "莫三比克",
		"143" => "緬甸",
		"144" => "納哥諾卡拉巴克",
		"145" => "納米比亞",
		"146" => "諾魯",
		"147" => "尼泊爾",
		"148" => "荷蘭",
		"149" => "新喀里多尼亞(法國)",
		"150" => "紐西蘭",
		"151" => "尼加拉瓜",
		"152" => "尼日",
		"153" => "奈及利亞",
		"154" => "紐埃(紐西蘭)",
		"155" => "北賽普勒斯",
		"156" => "北馬利亞納群島(美國)",
		"157" => "挪威",
		"158" => "北韓",
		"159" => "阿曼",
		"160" => "巴基斯坦",
		"161" => "帛琉",
		"162" => "巴勒斯坦",
		"163" => "巴拿馬",
		"164" => "巴布亞紐幾內亞",
		"165" => "巴拉圭",
		"166" => "秘魯",
		"167" => "菲律賓",
		"168" => "皮特肯群島(英國)",
		"169" => "波蘭",
		"170" => "葡萄牙",
		"171" => "聶斯特河沿岸",
		"172" => "波多黎各(美國)",
		"173" => "卡達",
		"174" => "留尼旺(法國)",
		"175" => "羅馬尼亞",
		"176" => "俄羅斯",
		"177" => "盧安達",
		"178" => "聖克里斯多福與尼維斯",
		"179" => "聖海蓮娜、阿森松和特里斯坦-達庫尼亞(英國)",
		"180" => "聖露西亞",
		"181" => "聖皮耶與密克隆(法國)",
		"182" => "聖文森及格瑞那丁",
		"183" => "薩摩亞",
		"184" => "聖馬利諾",
		"185" => "聖多美普林西比",
		"186" => "沙烏地阿拉伯",
		"187" => "塞內加爾",
		"188" => "塞爾維亞",
		"189" => "塞席爾",
		"190" => "獅子山",
		"191" => "新加坡",
		"192" => "荷屬聖馬丁(荷蘭)",
		"193" => "斯洛伐克",
		"194" => "斯洛維尼亞",
		"195" => "索羅門群島",
		"196" => "索馬利亞",
		"197" => "索馬利蘭",
		"198" => "南非",
		"199" => "韓國",
		"200" => "南奧塞提亞",
		"201" => "南蘇丹",
		"202" => "西班牙",
		"203" => "斯里蘭卡",
		"204" => "蘇丹",
		"205" => "蘇利南",
		"206" => "斯瓦爾巴(挪威)",
		"207" => "史瓦濟蘭",
		"208" => "瑞典",
		"209" => "瑞士",
		"210" => "敘利亞",
		"211" => "臺灣",
		"212" => "塔吉克",
		"213" => "坦尚尼亞",
		"214" => "泰國",
		"215" => "梵蒂岡",
		"216" => "東帝汶",
		"217" => "多哥",
		"218" => "托克勞(紐西蘭)",
		"219" => "東加",
		"220" => "千里達及托巴哥",
		"221" => "突尼西亞",
		"222" => "土耳其",
		"223" => "土庫曼",
		"224" => "特克斯與凱科斯群島(英國)",
		"225" => "吐瓦魯",
		"226" => "烏干達",
		"227" => "烏克蘭",
		"228" => "阿聯",
		"229" => "英國",
		"230" => "美國",
		"231" => "烏拉圭",
		"232" => "烏茲別克",
		"233" => "萬那杜",
		"234" => "委內瑞拉",
		"235" => "越南",
		"236" => "英屬維京群島(英國)",
		"237" => "美屬維京群島(美國)",
		"238" => "瓦利斯和富圖納(法國)",
		"239" => "西撒哈拉",
		"240" => "葉門",
		"241" => "尚比亞",
		"242" => "辛巴威"
	),
	"DASHBOARD" => array(
		"ENABLE" => "啟用",
		"DISABLE" => "停用",
		"CLOSE" => "關閉",
		"DASHBOARD_NOT_EXIST" => "無任何儀表板存在",
		"CLICK_BUTTON_TO_CREATE_DASHBOARD" => "如要新增儀表板，請點 <input type='button' value='新增' class='create-button' onClick='showCreateDashboardWindow();'> 按鈕。",
		"DASHBOARD_NOT_AVAILABLE_OR_EXPIRED" => "儀表板服務尚未啟用或試用期已結束",
		"CONTACT_SYSTEM_ADMINISTRATOR" => "若您要使用此服務，請聯絡管理員。",
		"CREATE" => "新增",
		"EDIT" => "編輯",
		"COPY" => "複製",
		"FULLSCREEN" => "全螢幕",
		"SHARE" => "分享",
		"REMOVE" => "移除",
		"INTERNAL_REGISTER" => "內部暫存器",
		"DI_COUNTER_WITH_NO" => "DI計數器%channel%",
		"DO_COUNTER_WITH_NO" => "DO計數器%channel%",
		"INTERNAL_REGISTER_WITH_NO" => "內部暫存器%channel%",
		"LOOP_WITH_NO" => "迴路%loop%",
		"PHASE_A" => "相位A",
		"PHASE_B" => "相位B",
		"PHASE_C" => "相位C",
		"TOTAL_AVERAGE" => "總和/平均",
		"POPUP" => array(
			"DASHBOARD_NOT_EXIST" => "無此儀表板。",
			"ARE_YOU_SURE_REMOVE_DASHBOARD" => "您確定要移除此儀表板嗎？",
			"ARE_YOU_SURE_REMOVE_WIDGET" => "您確定要移除此元件嗎？"
		),
		"CREATE_DASHBOARD" => array(
			"CREATE_DASHBOARD" => "新增儀表板",
			"EDIT_DASHBOARD" => "編輯儀表板",
			"NAME" => "名稱",
			"LOCK_WIDGET" => "鎖定元件",
			"AS_FIRST_PAGE" => "設為主頁",
			"DATA_LENGTH" => "資料保留",
			"LAST_30_SECONDS" => "最近 30 秒",
			"LAST_1_MINUTE" => "最近 1 分鐘",
			"LAST_3_MINUTES" => "最近 3 分鐘",
			"LAST_5_MINUTES" => "最近 5 分鐘",
			"LAST_10_MINUTES" => "最近 10 分鐘",
			"DARK_MODE" => "夜間模式",
			"POPUP" => array(
				"NAME_IS_EMPTY" => "名稱欄位不能為空。"
			),
			"TIP" => array(
				"AS_FIRST_PAGE" => "此儀表板將會是『儀表板服務』的主頁。",
				"DATA_LENGTH" => "保留最近一段時間所收集的資料。"
			)
		),
		"CREATE_WIDGET" => array(
			"ADD_WIDGET" => "新增元件",
			"EDIT_WIDGET" => "編輯元件",
			"TYPE" => "類型",
			"PROPERTY" => "參數",
			"CHANNEL" => "通道",
			"LINE_CHART" => "折線圖",
			"BAR_CHART" => "長條圖",
			"PIE_CHART" => "圓餅圖",
			"GAUGE" => "計量錶",
			"PLOT_BAR" => "柱狀圖",
			"VALUE" => "數值",
			"VALUE_TABLE" => "數值表格",
			"VALUE_LABEL_OVERLAY" => "數值標籤疊加",
			"VALUE_OUTPUT" => "數值輸出(按鈕)",
			"VALUE_OUTPUT_SLIDER" => "數值輸出(滑桿)",
			"VIDEO_EVENT_LIST" => "影像事件紀錄",
			"TIME_CLOCK" => "時間顯示",
			"COUNTDOWN_TIMER" => "倒數計時",
			"MAP" => "地圖",
			"RICH_CONTENT" => "自訂內容",
			"TITLE" => "標題",
			"SIZE" => "大小",
			"DESCRIPTION" => "備註",
			"LIST" => "列表",
			"ACTION" => "動作",
			"ADD" => "新增",
			"REMOVE" => "移除",
			"NO_CHANNEL_EXIST" => "無任何通道存在",
			"CLICK_BUTTON_TO_ADD_CHANNEL" => "如要新增通道，請點『新增』按鈕。",
			"POPUP" => array(
				"CHANNEL_NUMBER_CAN_NOT_LESS_THEN_X" => "通道數量不能小於 %number% 個。",
				"CHANNEL_NUMBER_CAN_NOT_GREATER_THEN_X" => "通道數量不能大於 %number% 個。"
			)
		),
		"ADD_CHANNEL" => array(
			"ADD_CHANNEL" => "新增通道",
			"SEARCH" => "搜尋",
			"BUILD_IN" => "內建",
			"DI_COUNTER" => "DI計數器",
			"DO_COUNTER" => "DO計數器",
			"OTHER" => "其他",
			"INTERNAL_REGISTER" => "內部暫存器",
			"NO_DEVICE_EXIST" => "無裝置",
			"NO_ONLINE_DEVICE_EXIST" => "無上線裝置",
			"NO_MODULE_EXIST" => "無模組",
			"MODULE_NOT_FOUND" => "無符合模組"
		),
		"SHARE_LINK" => array(
			"SHARE_LINK" => "分享連結",
			"URL" => "網址",
			"EXPIRATION_DATE" => "到期日期",
			"CONTROLLABLE" => "可控制",
			"ACTION" => "動作",
			"SHARE_LINK_NOT_EXIST" => "無任何分享連結",
			"CLICK_BUTTON_TO_CREATE_SHARE_LINK" => "如要新增分享連結，請點選右下角的 '+' 按鈕。",
			"SET_EXPIRATION_DATE" => "設定到期日期",
			"REMOVE" => "移除",
			"POPUP" => array(
				"ARE_YOU_SURE_REMOVE_SHARE_LINK" => "您確定要移除此分享連結嗎？"
			)
		)
	),
	"VIDEO" => array(
		"VIDEO_EVENT_DATA" => "影像事件資訊",
		"NO_VIDEO_EVENT_EXIST" => "無影像事件",
		"THE_EVENT_WILL_SHOW_WHEN_VIDEO_INCOMING" => "當裝置回送影像資料至IoTstar後即會顯示於此頁面上。",
		"THERE_IS_NO_VIDEO_EVENT_SELECT_OTHER_DEVICE" => "此裝置無任何影像事件，請選擇其他裝置。",
		"SUN" => "日",
		"MON" => "一",
		"TUE" => "二",
		"WED" => "三",
		"THU" => "四",
		"FRI" => "五",
		"SAT" => "六",
		"NO_VIDEO_EVENT_EXIST_ON_SELECT_DATE" => "選擇之日期無影像事件。",
		"NO_VIDEO_CAN_PLAY_ON_SELECT_DATE" => "選擇之日期無影像可播放。",
		"TIP" => array(
			"SHARE_BY_USER" => "此事件由 %username% 與您分享。",
			"PHOTO" => "照片",
			"VIDEO" => "影片",
			"SELECT_ALL_OR_UNSELECT_ALL" => "全選 / 取消全選",
			"REMOVE" => "移除"
		),
		"POPUP" => array(
			"ARE_YOU_SURE_REMOVE_EVENT_YOU_SELECT" => "您確定要移除您所選擇的事件嗎？"
		),
		"DEVICE_FILTER" => array(
			"DEVICE_FILTER" => "裝置篩選",
			"MODEL_NAME_AND_NICKNAME" => "型號 / 名稱",
			"SERIAL_NUMBER" => "序號"
		)
	),
	"ERROR" => array(
		"SHARE_LINK_UNAVAILABLE" => "連結已失效",
		"YOUR_SHARE_LINK_MAY_EXPIRED_OR_NOT_EXIST" => "您輸入的連結可能已經過期或是不存在。"
	)
);
?>