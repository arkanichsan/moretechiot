<?php
$lang = array(
	"SUBMIT" => "送出",
	"OK" => "确定",
	"CANCEL" => "取消",
	"EMAIL" => array(
		"DEAR_USER" => "亲爱的使用者：",
		"DISABLE_USER_NOTIFICATION" => "若您要关闭此事件通知，请登入至IoTstar后于『系统设定与信息』->『数据库 & 事件通知设定』中进行事件通知设定变更。",
		"DEAR_ADMIN" => "亲爱的管理者：",
		"DISABLE_ADMIN_NOTIFICATION" => "若您要关闭系统事件通知，请启动IoTstar GUI程式后于『Settings > Notification』中变更设定。",
		"BEST_REGARDS" => "祝安康"
	),
	"LOGIN" => array(
		"MISSING_SOCKETS_EXTENSION" => "缺少Sockets扩充功能",
		"MISSING_SOCKETS_EXTENSION_DESCRIPTION" => "由于缺少Sockets扩充功能，系统将无法正常运作。请您先参照用户手册将Sockets模块启用后，即可开始使用系统。",
		"LOGIN_TITLE" => "登入",
		"REMEMBER_ME" => "记住我",
		"FORGOT_PASSWORD" => "忘记密码？",
		"USERNAME" => "账号",
		"PASSWORD" => "密码",
		"SIGNUP_NOW" => "立即申请！",
		"FORGOT_PASSWORD_TITLE" => "忘记密码",
		"FORGOT_PASSWORD_DESCRIPTION" => "输入您的账号并送出，系统将会寄发一封电子邮件至您的信箱中。请点击电子邮件中的链接即可重置您的密码。",
		"BACKTOLOGIN" => "返回系统登入界面",
		"POPUP" => array(
			"CONFIRM_EMAIL_HAS_SENT" => "系统已经发送一封电子邮件至您的信箱中。请点击电子邮件中的链接以重置您的密码。",
			"NEW_PASSWORD_HAS_BEEN_SENT" => "新的密码已经发送至您的电子邮件信箱中。请使用新密码登入。",
			"PASSWORD_HAS_BEEN_RESET" => "您的密码已经完成重置程序。"
		),
		"AJAX" => array(
			"INVALID_USERNAME" => "无此账号。",
			"INVALID_PASSWORD" => "密码错误。",
			"ACCOUNT_NOT_ACTIVE" => "此账号尚未启用。",
			"ACCOUNT_EXPIRE" => "此账号使用期限已到期。",
			"EMPTY_USERNAME_OR_PASSWORD" => "账号或密码字段为空白。",
			"EMPTY_USERNAME" => "账号字段为空白。"
		),
		"EMAIL" => array(
			"EVENT_NOTIFICATION" => "系统事件通知",
			"ENTER_INCORRECT_PASSWORD_OVER_TEN_TIMES" => "您的账号已经被错误的密码连续尝试登入超过十次，特此通知。",
			"CONFIRM_EMAIL" => "确认信件",
			"CLICK_LINK_TO_RESET_PASSWORD" => "请点击以下链接重置密码。",
			"NEW_PASSWORD" => "新密码",
			"NEW_PASSWORD_AS_FOLLOWS" => "下列为您的新密码。请在登入后修改密码。"
		)
	),
	"SIGNUP" => array(
		"SIGNUP_TITLE" => "账号申请",
		"EMAIL_VALIDATION" => "电子邮件信箱验证",
		"REGISTRATION" => "注册",
		"DONE" => "完成",
		"EMAIL" => array(
			"USER_SIGN_UP_ACCOUNT" => "有使用者申请名为 %username% 的账号，若您要启用请启动IoTstar GUI程序后于『Account Management』中变更设定。"
		)
	),
	"EMAIL_VALIDATION" => array(
		"ENTER_EMAIL_ADDRESS" => "请输入您的电子邮件信箱。为了确认您的电子邮件信箱是否正确，系统将发送一封电子邮件至您的信箱中。",
		"EMAIL_ADDRESS" => "电子邮件：",
		"VERIFY" => "验证",
		"EMAIL_HAS_SENT" => "系统已经发送了一封确认信至%email%，请点击信中的连结完成注册程序。",
		"CHECK_EMAIL" => "检查电子邮件",
		"POPUP" => array(
			"VALIDATION_FAILED" => "验证失败。请检查输入的数据是否正确。",
		),
		"AJAX" => array(
			"EMPTY_FIELD" => "此字段不能为空白。",
			"LENGTH_LONGER_THEN_100" => "此字段长度不能超过100个字符。",
			"ILLEGAL_FORMAT" => "电子邮件地址格式不正确。",
			"EXIST_EMAIL" => "此电子邮件已经被使用过。"
		),
		"EMAIL" => array(
			"SUBJECT" => "电子邮件验证",
			"CONTENT" => "请点击以下连结，完成注册程序。"
		)
	),
	"REGISTRATION" => array(
		"USERNAME" => "账号：",
		"PASSWORD" => "密码：",
		"RETYPE_PASSWORD" => "再次输入密码：",
		"NICKNAME" => "名称：",
		"EMAIL_ADDRESS" => "电子邮件：",
		"COMPANY" => "公司：",
		"COUNTRY" => "国家 / 地区：",
		"POPUP" => array(
			"REGISTRATION_FAILED" => "注册失败。请检查输入的数据是否正确。",
			"VALIDATION_URL_ERROR" => "认证网址错误。请检查认证网址是否正确。",
			"EXIST_EMAIL" => "注册失败。此电子邮件已经被使用过或您已经完成注册。",
			"ACCOUNT_AMOUNT_REACH_MAXIMUM" => "注册失败。系统可容纳的用户账号数量已达上限。"
		),
		"AJAX" => array(
			"EMPTY_FIELD" => "此字段不能为空白。",
			"EMPTY_OPTION" => "请选择一个选项。",
			"ONLY_ALLOW_LOWERCASE_ENGLISH_AND_NUMBER_ONLY" => "此字段只能输入小写英文字母与数字。",
			"ILLEGAL_CHARACTER" => "此字段不允许特殊字符。",
			"LENGTH_LONGER_THEN_16_OR_SHORTER_THEN_6" => "此字段长度必须介于6-16的字符间。",
			"USERNAME_TAKEN" => "此账号已经被使用。",
			"NOT_MATCH_PASSWORD" => "此字段与密码字段不符。",
			"LENGTH_LONGER_THEN_100" => "此字段长度不能超过100个字符。",
			"LENGTH_LONGER_THEN_50" => "此字段长度不能超过50个字符。",
			"EXIST_EMAIL" => "此电子邮件已经被使用过或您已经注册完成。"
		)
	),
	"DONE" => array(
		"ACTIVE_SUCCESS" => "谢谢您的注册，您的账号虽已建立，但仍须等管理员启用后才可开始使用。",
		"LOGIN" => "登入"
	),
	"MANAGER" => array(
		"LOGOUT" => "注销",
		"REMOTE_ACCESS_SERVICE" => "远程访问服务",
		"DEVICE_MAINTENANCE" => "装置监控与设定",
		"DATA_DISPLAY_AND_ANALYSIS" => "信息显示与分析",
		"DASHBOARD_SERVICE" => "仪表板服务",
		"REALTIME_IO_DATA" => "实时I/O信息",
		"REALTIME_POWER_DATA" => "实时电力信息",
		"HISTORICAL_IO_DATA" => "历史I/O信息",
		"HISTORICAL_POWER_DATA" => "历史电力信息",
		"REPORT_SERVICE" => "报表服务",
		"HISTORICAL_POWER_REPORT" => "历史电力报表",
		"VIDEO_EVENT_DATA" => "影像事件信息",
		"GROUPING_SETTING" => "分群设定",
		"IO_CHANNEL" => "I/O通道",
		"POWER_METER_LOOP" => "电表回路",
		"SYSTEM_INFORMATION_AND_SETTING" => "系统信息与设定",
		"ACCOUNT_MAINTENANCE" => "账号设定",
		"DATABASE_AND_EVENT_SETTING" => "数据库与事件设定",
		"EVENT_LIST" => "事件列表",
		"DATABASE_TABLE_LIST" => "数据库表格对照表",
		"POPUP" => array(
			"LOGOUT_CONFIRM" => "您确定要注销系统？"
		)
	),
	"CONTROL" => array(
		"ONLINE_DEVICE_LIST" => "上线装置列表",
		"SEARCH" => "搜寻",
		"LOADING" => "读取中...",
		"NONE" => "无装置",
		"NOT_FOUND" => "无符合装置",
		"OFFLINE_DEVICE_LIST" => "脱机装置列表",
		"SETTING_FILE_RESTORE" => "配置文件还原",
		"NO_SETTING_FILE_AVAILABLE_FOR_RESTORE" => "暂无配置文件可供还原。",
		"TIME" => "时间",
		"SIZE" => "大小",
		"ACTION" => "动作",
		"RESTORE" => "还原",
		"CLOSE" => "关闭",
		"FIRMWARE_UPDATE" => "固件更新",
		"AUTO_SEARCH_AND_DOWNLOAD_LASTEST_FIRMWARE" => "自动搜寻并下载最新固件档",
		"SEARCH_AND_DOWNLOAD_FIRMWARE_AUTO" => "IoTstar将自动在官网上搜寻并下载最新的固件档，您必须确认您的IoTstar是可以对外联机。",
		"SELECT_FIRMWARE_ON_THIS_COMPUTER" => "选择此计算机上的固件文件",
		"DOWNLOAD_AND_SELECT_FIRMWARE_MANUAL" => "手动选择此计算机上的固件文件，您必须自行先下载固件档。",
		"PLEASE_SELECT_HEX_FORMAT_FIRMWARE_ON_THIS_COMPUTER" => "请选择在您计算机中的固件文件(HEX格式)：",
		"FIRMWARE" => "固件档",
		"BROWSE" => "浏览...",
		"SELECT_CORRECT_FIRMWARE_TO_AVOID_UPDATE_FAIL" => "请选择装置所对应的固件档，以免固件更新时发生错误。",
		"MODEL_NAME_AND_NICKNAME" => "型号 / 名称",
		"VERSION" => "固件版本",
		"PROGRESS" => "更新进度",
		"NO_ONLINE_DEVICE" => "无上线装置。",
		"UPLOAD" => "上传",
		"UPDATE" => "更新",
		"ADMIN_ALREADY_LOGIN" => "管理员已登入。",
		"FIRMWARE_IS_LATEST_VERSION" => "已经是最新版固件。",
		"DOWNLOAD_FIRMWARE_FAILED" => "下载固件档至IoTstar时发生错误。",
		"UPLOAD_FIRMWARE_FAILED" => "上传固件档至IoTstar时发生错误。",
		"SEND_FIRMWARE_FAILED" => "传送固件档文件至装置时发生错误。",
		"FIRMWARE_NOT_CORRECT" => "固件档不正确。",
		"FREE_SPACE_NOT_ENOUGHT" => "磁盘空间不足。",
		"UNZIP_FIRMWARE_FAILED" => "解压缩固件档失败。",
		"UPDATE_FIRMWARE_FAILED" => "固件更新失败。",
		"UPDATE_FIRMWARE_SUCCESS" => "固件更新成功。",
		"TIP" => array(
			"SHARE_BY_USER" => "此装置由 %username% 与您分享。",
			"REMOTE_CONTROL" => "装置远程维护",
			"SETTING_FILE_RESTORE" => "配置文件还原",
			"REMOVE_DEVICE_FROM_OFFLINE_LIST" => "从脱机装置列表中移除此装置(含所有备份的配置文件)"
		),
		"POPUP" => array(
			"ARE_YOU_SURE_RESTORE_SETTING_FILE" => "您确定要还原此配置文件至所选择的装置吗？",
			"RESTORE_SUCCESSFULLY" => "还原成功。",
			"PERMISSION_DENIED" => "还原失败。您没有控制此装置的权限。",
			"DEVICE_OFFLINE" => "还原失败。此装置可能已经脱机。",
			"SETTING_FILE_REMOVED" => "还原失败。配置文件可能已经被移除。",
			"REMOVE_SUCCESSFULLY" => "移除成功。",
			"ARE_YOU_SURE_REMOVE_DEVICE_FROM_OFFLINE_LIST" => "您确定要从脱机装置列表中移除此装置？此动作会一并移除所有备份的配置文件。",
			"ARE_YOU_SURE_UPDATE_FIRMWARE" => "您确定要进行固件更新？更新时请勿关闭或离开本页面，以免造成更新失败。"
		)
	),
	"SETTING" => array(
		"SETTING" => "设定",
		"PASSWORD" => "密码",
		"CURRENT_PASSOWRD" => "目前密码：",
		"NEW_PASSOWRD" => "新密码：",
		"RETYPE_NEW_PASSOWRD" => "再次输入新密码：",
		"INFORMATION" => "信息",
		"NICKNAME" => "名称：",
		"EMAIL_ADDRESS" => "电子邮件：",
		"COMPANY" => "公司：",
		"COUNTRY" => "国家 / 地区：",
		"DEVICE_SHARE" => "装置分享",
		"DEVICE_SHARE_DESCRIPTION" => "将您管控的装置分享给其他账号使用，但该账号只拥有对装置的I/O数据或电力数据进行查询的权限。",
		"SHARE_USERNAME" => "账号",
		"SHARE_NICKNAME" => "名称",
		"SHARE_ACTION" => "动作",
		"NO_SHARE_ACCOUNT" => "无设定分享账号。",
		"ADD" => "加入",
		"REMOVE" => "移除",
		"LINE_BOT_DESCRIPTION" => "Bot Service功能让您可以透过LINE App与您的控制器互动。以下列出允许与您的控制器互动的LINE账号。",
		"LINE_BOT_STATUS" => "状态",
		"LINE_BOT_NICKNAME" => "名称",
		"LINE_BOT_ACTION" => "动作",
		"NO_LINE_BOT_ACCOUNT" => "无LINE账号加入此Bot。",
		"CLOSE" => "关闭",
		"POPUP" => array(
			"SAVE_SUCCESS" => "储存成功。",
			"SAVE_FAILED" => "储存失败。请重新检查设定。",
			"SAVE_SUCCESS_AND_VALIDATE_EMAIL" => "储存成功。若您有修改电子邮件地址，请您点击我们寄给您的电子邮件中的链接来完成修改。",
			"EXIST_EMAIL" => "修改电子邮件地址失败。此电子邮件已经被使用过。",
			"MODIFY_EMAIL_SUCCESS" => "修改电子邮件地址成功。",
			"MODIFY_EMAIL_FAILED" => "修改电子邮件地址失败。也许您已经修改过了。",
			"USERNAME_IS_EMPTY" => "加入失败。账号不能为空白。",
			"ARE_YOU_SURE_ADD_SHARE_WITH_USERNAME" => "您确定要将装置分享给 %username% 吗？",
			"ADD_SUCCESSFULLY" => "加入成功。",
			"ARE_YOU_SURE_REMOVE_SHARE_WITH_USERNAME" => "您确定要移除与 %username% 间的分享吗？",
			"REMOVE_SUCCESSFULLY" => "移除成功。",
			"ENABLE_SUCCESS" => "启用成功。",
			"DISABLE_SUCCESS" => "停用成功。",
			"MODIFY_SUCCESS" => "修改成功。",
			"NICKNAME_IS_EMPTY" => "修改失败。名称不能为空白。",
			"ARE_YOU_SURE_REMOVE_ACCOUNT" => "您确定要移除此账号吗？",
			"NICKNAME_SETTING" => "名称设定",
			"PLEASE_ENTER_THE_NEW_NICKNAME" => "请输入新的名称：",
			"SCAN_QR_CODE" => "使用LINE App扫描"
		),
		"AJAX" => array(
			"EMPTY_FIELD" => "此字段不能为空白。",
			"ILLEGAL_CHARACTER" => "此字段不允许特殊字符。",
			"LENGTH_LONGER_THEN_16_OR_SHORTER_THEN_6" => "此字段长度必须介于6-16的字符间。",
			"NOT_MATCH_NEW_PASSWORD" => "此字段与新密码字段不符。",
			"INVALID_PASSWORD" => "密码不正确。",
			"LENGTH_LONGER_THEN_100" => "此字段长度不能超过100个字符。",
			"ILLEGAL_FORMAT" => "电子邮件地址格式不正确。",
			"LENGTH_LONGER_THEN_50" => "此字段长度不能超过50个字符。",
			"EXIST_EMAIL" => "此电子邮件已经被使用过。",
			"USERNAME_IS_EMPTY" => "加入失败。账号不能为空白。",
			"USERNAME_IS_YOURSELF" => "加入失败。此账号为您自己的账号。",
			"USERNAME_NOT_EXIST" => "加入失败。查无此账号。",
			"ALREADY_SHARE_WITH_THIS_USERNAME" => "加入失败。您已经分享给此账号过了。"
		),
		"EMAIL" => array(
			"SUBJECT" => "电子邮件验证",
			"CONTENT" => "请点击以下链接完成电子邮件地址的修改。"
		)
	),
	"REALTIME_IO" => array(
		"SELECT_CHANNEL" => "选择通道",
		"DEVICE" => "装置",
		"GROUP" => "群组",
		"BUILD_IN" => "內建",
		"OTHER" => "其他",
		"INTERNAL_REGISTER" => "内部缓存器",
		"CHANNEL" => "通道",
		"DI_COUNTER_WITH_NO" => "DI计数器%channel%",
		"DO_COUNTER_WITH_NO" => "DO计数器%channel%",
		"INTERNAL_REGISTER_WITH_NO" => "内部缓存器%channel%",
		"CANCEL" => "取消",
		"REALTIME_IO_DATA" => "实时I/O通道信息",
		"MODULE" => "模块",
		"MAX" => "最大值",
		"MIN" => "最小值",
		"VALUE" => "数值",
		"ACTION" => "动作",
		"ADD" => "新增",
		"REMOVE" => "移除",
		"NO_DATA" => "无资料",
		"CHANNEL_WITH_COLON" => "通道:",
		"TIME_WITH_COLON" => "时间:",
		"VALUE_WITH_COLON" => "数值:",
		"TIP" => array(
			"SHARE_BY_USER" => "此装置由 %username% 与您分享。",
			"SETUP_SENDING_SETTING_FIRST" => "无法点选？请先将装置固件版本升级至%version%以上，并在『%sending_setting%』页面设定欲回送数据即可。",
			"REALTIME_DATA_SENDING_SETTING" => "实时数据传送设定"
		),
		"POPUP" => array(
			"VALUE_SETTING" => "数值设定",
			"PLEASE_ENTER_THE_VALUE" => "请输入数值：",
			"VALUE_IS_EMPTY" => "修改失败。数值不能为空白。",
			"VALUE_MUST_BE_NUMBER" => "修改失败。数值必须是数字。",
			"MODIFIED_SUCCESSFULLY" => "修改成功。"
		)
	),
	"REALTIME_ENERGY" => array(
		"SELECT_A_LOOP" => "选择回路",
		"DEVICE" => "装置",
		"LOOP" => "回路",
		"REALTIME_POWER_DATA" => "实时电力信息",
		"V" => "电压",
		"I" => "电流",
		"KW" => "实功率",
		"KVAR" => "无效功率",
		"KVA" => "视在功率",
		"PF" => "功率因素",
		"MAX" => "最大值",
		"MIN" => "最小值",
		"PHASE_A" => "相位A",
		"PHASE_B" => "相位B",
		"PHASE_C" => "相位C",
		"TOTAL" => "三相总和",
		"NO_DATA" => "无资料",
		"TIME_WITH_COLON" => "时间:",
		"VALUE_WITH_COLON" => "数值:",
		"TIP" => array(
			"SHARE_BY_USER" => "此装置由 %username% 与您分享。",
			"SETUP_SENDING_SETTING_FIRST" => "无法点选？请先将装置固件版本升级至%version%以上，并在『%sending_setting%』页面设定欲回送数据即可。",
			"REALTIME_DATA_SENDING_SETTING" => "实时数据传送设定"
		)
	),
	"HISTORY_IO" => array(
		"DEVICE" => "装置",
		"GROUP" => "群组",
		"BUILD_IN" => "內建",
		"OTHER" => "其他",
		"INTERNAL_REGISTER" => "内部缓存器",
		"CHANNEL" => "通道",
		"DI_COUNTER_WITH_NO" => "DI计数器%channel%",
		"DO_COUNTER_WITH_NO" => "DO计数器%channel%",
		"INTERNAL_REGISTER_WITH_NO" => "内部缓存器%channel%",
		"REMOVE" => "移除",
		"TODAY" => "今日",
		"YESTERDAY" => "昨日",
		"TODAY_LAST_WEEK" => "上周同日",
		"SPECIFY_DATE" => "自定义日期",
		"SUN" => "日",
		"MON" => "一",
		"TUE" => "二",
		"WED" => "三",
		"THU" => "四",
		"FRI" => "五",
		"SAT" => "六",
		"MODULE_ANALYSIS" => "I/O信道信息分析",
		"NO_DATA" => "无资料",
		"MODULE" => "模块",
		"TIME" => "时间",
		"MAX" => "最大值",
		"MIN" => "最小值",
		"ACTION" => "动作",
		"SYNC_SELECTER" => "选择同步",
		"ADD" => "新增",
		"SELECT_CHANNEL" => "选择通道",
		"CANCEL" => "取消",
		"CHANNEL_WITH_COLON" => "通道:",
		"TIME_WITH_COLON" => "时间:",
		"VALUE_WITH_COLON" => "数值:",
		"TIP" => array(
			"SHARE_BY_USER" => "此装置由 %username% 与您分享。"
		)
	),
	"HISTORY_ENERGY" => array(
		"DEVICE" => "装置",
		"GROUP" => "群组",
		"LOOP" => "回路",
		"1ST_MONTH" => "第一个月",
		"2ND_MONTH" => "第二个月",
		"3RD_MONTH" => "第三个月",
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
		"TODAY_LAST_WEEK" => "上周同日",
		"SPECIFY_DATE" => "自定义日期",
		"THIS_WEEK" => "本周",
		"LAST_WEEK" => "上周",
		"THIS_MONTH" => "本月",
		"LAST_MONTH" => "上月",
		"THIS_MONTH_LAST_YEAR" => "去年同月",
		"THIS_QUARTER" => "本季",
		"LAST_QUARTER" => "上季",
		"THIS_QUARTER_LAST_YEAR" => "去年同季",
		"THIS_YEAR" => "今年",
		"LAST_YEAR" => "去年",
		"SELECT_A_LOOP_OR_GROUP" => "选择回路/群组",
		"ENERGY_ANALYSIS" => "用电量分析",
		"DAY" => "日",
		"WEEK" => "周",
		"MONTH" => "月",
		"QUARTER" => "季",
		"YEAR" => "年",
		"TIME" => "时间",
		"NO_DATA" => "无资料",
		"ENERGY_CONSUMPTION" => "用电度数",
		"KWH" => "度",
		"COMPARED" => "对比",
		"CARBON_EMISSIONS" => "碳排放量",
		"KG" => "千克",
		"GROWTH_RATE" => "增减幅度",
		"POWER_DATA_ANALYSIS" => "电力资料分析",
		"V" => "电压",
		"I" => "电流",
		"KW" => "实功率",
		"KVAR" => "无效功率",
		"KVA" => "视在功率",
		"PF" => "功率因素",
		"MAX" => "最大值",
		"MIN" => "最小值",
		"PHASE_A" => "相位A",
		"PHASE_B" => "相位B",
		"PHASE_C" => "相位C",
		"TOTAL" => "三相总和",
		"CARBON_EMISSIONS_SETTING" => "碳排放量设定",
		"FACTOR" => "系数",
		"TIME_WITH_COLON" => "时间:",
		"VALUE_WITH_COLON" => "数值:",
		"TIP" => array(
			"SHARE_BY_USER" => "此装置由 %username% 与您分享。"
		),
		"POPUP" => array(
			"FACTOR_IS_EMPTY" => "修改失败。系数不能为空白。",
			"FACTOR_MUST_BE_NUMBER" => "修改失败。系数必须是数字。",
			"FACTOR_MUST_BE_GREATER_THAN_ZERO" => "修改失败。系数必须大于0。"
		)
	),
	"REPORT_SERVICE" => array(
		"DAILY_REPORT" => "日报表",
		"WEEKLY_REPORT" => "周报表",
		"MONTHLY_REPORT" => "月报表",
		"QUARTERLY_REPORT" => "季报表",
		"ANNUAL_REPORT" => "年报表",
		"REPORT_DATE" => "数据日期",
		"COMPARE_DATE" => "对比日期",
		"PRINT_DATE" => "打印日期",
		"TIME" => "时间",
		"DATE" => "日期",
		"MONTH_TITLE" => "月份",
		"MAX_DEMAND" => "最高需量(kW)",
		"KWH_TITLE" => "用电量(度)",
		"PF" => "平均功率因子(%)",
		"I" => "平均电流(A)",
		"I_A" => "平均电流 A相(A)",
		"I_B" => "平均电流 B相(A)",
		"I_C" => "平均电流 C相(A)",
		"V" => "平均电压(V)",
		"V_A" => "平均电压 A相(V)",
		"V_B" => "平均电压 B相(V)",
		"V_C" => "平均电压 C相(V)",
		"KVA" => "平均视在功率(kVA)",
		"KVAR" => "平均无效功率(kvar)",
		"NO_DATA" => "无资料",
		"DAILY_MAXIMUM_DEMAND" => "本日最高需量",
		"WEEKLY_MAXIMUM_DEMAND" => "本周最高需量",
		"MONTHLY_MAXIMUM_DEMAND" => "本月最高需量",
		"QUARTERLY_MAXIMUM_DEMAND" => "本季最高需量",
		"ANNUAL_MAXIMUM_DEMAND" => "本年最高需量",
		"EVEN_TIME" => "发生时间",
		"KWH" => "度",
		"DAY" => "日",
		"WEEK" => "周",
		"WEEKDAY" => "星期",
		"MONTH" => "月",
		"QUARTER" => "季",
		"YEAR" => "年",
		"DEVICE" => "装置",
		"GROUP" => "群组",
		"LOOP" => "回路",
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
			"SHARE_BY_USER" => "此装置由 %USERNAME% 与您分享。",
			"CREATE" => "新增",
			"EDIT" => "编辑",
			"COPY" => "复制",
			"REMOVE" => "移除"
		),
		"SELECT_A_LOOP_OR_GROUP" => "选择电表回路、I/O通道或群组",
		"POWER_METER_LOOP_REPORT" => "电表回路报表",
		"POWER_METER_GROUP_REPORT" => "电表回路群组报表",
		"IO_CHANNEL_REPORT" => "I/O信道报表",
		"IO_CHANNEL_GROUP_REPORT" => "I/O信道群组报表",
		"IO_CHANNEL" => "I/O通道",
		"TEMPLATE_MANAGEMENT" => "范本管理",
		"DOWNLOAD_REPORT_PDF" => "下载PDF",
		"DOWNLOAD_REPORT" => "下载EXCEL",
		"DOWNLOAD" => "下载",
		"TEMPLATE_SETTING"=> "设定模板",
		"TEMPLATE_NAME"=> "模板名称",
		"SELECT_TEMPLATE"=> "模板选择",
		"HEADER" => "页首",
		"FOOTER" => "页尾",
		"NO_TEMPLATE" => "无范本",
		"DELETE_THIS_TEMPLATE" => "您确定要删除此范本？",
		"DEFAULT" => "预设",
		"APPLY_TEMPLATE" => "套用范本",
		"CLEAR_TEMPLATE" => "清除范本",
		"CLOSE" => "关闭",
		"GROUP_LOOP_STATISTICS" => "回路统计",
		"GROUP_LOOP_COMPARISON" => "回路比较",
		"VALUE_TYPE" => "数值种类",
		"SUMMARY" => "摘要",
		"REPORT_TYPE" => "报表种类",
		"DAILY_TOTAL_MODULE_ENERGY_CONSUMPTION" => "本日各别用电量",
		"THIS_WEEK_MODULE_ENERGY_CONSUMPTION" => "本周各别用电量",
		"THIS_MONTH_MODULE_ENERGY_CONSUMPTION" => "本月各别用电量",
		"THIS_QUARTER_MODULE_ENERGY_CONSUMPTION" => "本季各别用电量",
		"THIS_YEAR_MODULE_ENERGY_CONSUMPTION" => "本年各别用电量",
		"DAILY_TOTAL_ENERGY_CONSUMPTION" => "本日总用电量",
		"WEEKLY_TOTAL_ENERGY_CONSUMPTION" => "本周总用电量",
		"MONTHLY_TOTAL_ENERGY_CONSUMPTION" => "本月总用电量",
		"QUARTERLY_TOTAL_ENERGY_CONSUMPTION" => "本季总用电量",
		"ANNUAL_TOTAL_ENERGY_CONSUMPTION" => "本年总用电量",
		"TODAY_MAXIMUM" => "本日最大值",
		"TODAY_MINIMUM" => "本日最小值",
		"TODAY_AVERAGE" => "本日平均值",
		"TODAY_TOTAL_VALUE" => "本日总和值",
		"THIS_WEEK_MAXIMUM" => "本周最大值",
		"THIS_WEEK_MINIMUM" => "本周最小值",
		"THIS_WEEK_AVERAGE" => "本周平均值",
		"THIS_WEEK_TOTAL_VALUE" => "本周总和值",
		"THIS_MONTH_MAXIMUM" => "本月最大值",
		"THIS_MONTH_MINIMUM" => "本月最小值",
		"THIS_MONTH_AVERAGE" => "本月平均值",
		"THIS_MONTH_TOTAL_VALUE" => "本月总和值",
		"THIS_QUARTER_MAXIMUM" => "本季最大值",
		"THIS_QUARTER_MINIMUM" => "本季最小值",
		"THIS_QUARTER_AVERAGE" => "本季平均值",
		"THIS_QUARTER_TOTAL_VALUE" => "本季总和值",
		"THIS_YEAR_MAXIMUM" => "本年最大值",
		"THIS_YEAR_MINIMUM" => "本年最小值",
		"THIS_YEAR_AVERAGE" => "本年平均值",
		"THIS_YEAR_TOTAL_VALUE" => "本年总和值",
		"TODAY_MAX_TIME_OCCURRENCE" => "本日最大值发生时间",
		"THIS_WEEK_MAX_TIME_OCCURRENCE" => "本周最大值发生时间",
		"THIS_MONTH_MAX_TIME_OCCURRENCE" => "本月最大值发生时间",
		"THIS_QUARTER_MAX_TIME_OCCURRENCE" => "本季最大值发生时间",
		"THIS_YEAR_MAX_TIME_OCCURRENCE" => "本年最大值发生时间",
		"TODAY_MIN_TIME_OCCURRENCE" => "本日最小值发生时间",
		"THIS_WEEK_MIN_TIME_OCCURRENCE" => "本周最小值发生时间",
		"THIS_MONTH_MIN_TIME_OCCURRENCE" => "本月最小值发生时间",
		"THIS_QUARTER_MIN_TIME_OCCURRENCE" => "本季最小值发生时间",
		"THIS_YEAR_MIN_TIME_OCCURRENCE" => "本年最小值发生时间",
		"MAX" => "最大值",
		"MIN" => "最小值",
		"AVERAGE" => "平均值",
		"FINAL" => "最后值",
		"TOTAL" => "总和值",
		"SINGLE_PERIOD" => "单一时段",
		"COMPARE_PERIOD" => "对比时段",
		"COLUMN_DISPLAY" => "字段显示",
		"ORIENTATION" => "方向",
		"PORTRAIT" => "直向",
		"LANDSCAPE" => "横向",
		"MARGINS" => "边界",
		"REPORT_SERVICE_NOT_AVAILABLE_OR_EXPIRED" => "报表服务尚未启用或试用期已结束",
		"CONTACT_SYSTEM_ADMINISTRATOR" => "若您要使用此服务，请联络管理员。",
		"LENGTH_LONGER_THEN_50" => "此字段长度不能超过50个字符。"
	),
	"GROUP" => array(
		"NO_GROUP_SETTING" => "没有任何群组存在",
		"ADD_GROUP_CLICK" => "如要新增群组，请点 <input type='button' value='群组新增' class='control-button' onClick='onClickNewGroupButton();'> 按钮",
		"NO_LOOP" => "此群组中没有回路",
		"ADD_LOOP_CLICK" => "如要新增回路，请点选右下角的"." '+' "."按钮",
		"GROUP" => "群组",
		"NEW" => "新增",
		"EDIT" => "编辑",
		"REMOVE" => "移除",
		"BACK" => "返回",
		"REMOVE_LOOP" => "移除回路",
		"ADD_GROUP" => "群组新增",
		"GROUP_NAME" => "组名",
		"EDIT_GROUP" => "群组编辑",
		"DELETE_GROUP" => "群组删除",
		"DELETE_GROUP_SURE" => "您确定要删除群组吗?",
		"REMOVE_LOOP_SURE" => "您确定要移除已选择的回路吗?",
		"ADD_LOOP" => "回路新增",
		"SEARCH" => "搜寻",
		"NO_DEVICE" => "无装置",
		"NO_MODULE" => "无模块",
		"NO_MATCH_MODULE" => "无符合模块",
		"SELECTED_LOOP" => "已选择<label id='ck_num'></label>个回路",
		"LOOP" => "回路",
		"GROUP_NAME_NOT_EMPTY" => "组名不能为空白。",
		"TIP" => array(
			"SHARE_BY_USER" => "此装置由 %username% 与您分享。"
		)
	),
	"IO_GROUP" => array(
		"INTERNAL_REGISTER_WITH_NO" => "内部缓存器%channel%",	
		"INTERNAL_REGISTER" => "内部缓存器",
		"REMOVE_CHANNEL" => "移除通道",
		"REMOVE_CHANNEL_SURE" => "您确定要移除已选择的通道吗?",
		"SELECTED_CHANNEL" => "已选择<label id='ck_num'></label>个通道",
		"ADD_CHANNEL" => "通道新增",
		"NO_CHANNEL" => "此群组中没有通道",
		"ADD_CHANNEL_CLICK" => "如要新增通道，请点选右下角的"." '+' "."按钮",
		"CHANNEL_COUNTER_WITH_NO" => "%channel_type%计数器%channel%",
		"CHANNEL_COUNTER" => "%channel_type%计数器",
		"OTHER" => "其他",
		"BUILD_IN" => "內建"
	),
	"NOTIFICATION" => array(
		"ERROR" => array(
			"10000" => "系统信息：%variable0%",
			"10001" => "使用者自 %variable0% 登入成功。",
			"10002" => "使用者自 %variable0% 注销成功。",
			"10003" => "装置 %variable0% 序号：%variable1% 上线。",
			"10004" => "装置 %variable0% 序号：%variable1% 脱机。",
			"10100" => "设定信息：%variable0%.",
			"10101" => "用户修改密码。",
			"10102" => "使用者更新帐户信息。",
			"10103" => "使用者修改事件通知设定。",
			"10104" => "装置 %variable0% 序号：%variable1% 上传配置文件。",
			"10105" => "用户清除数据库成功。",
			"10106" => "用户启用历史数据汇入数据库功能。",
			"10107" => "用户停用历史数据汇入数据库功能。",
			"10108" => "传送事件通知电子邮件成功。",
			"10109" => "用户移除装置 %variable0% 序号：%variable1% 相关数据表成功。",
			"10110" => "用户清除装置 %variable0% 序号：%variable1% 的模块 %variable2% UID：%variable3% 数据表成功。",
			"10111" => "用户移除装置 %variable0% 序号：%variable1% 的模块 %variable2% UID：%variable3% 数据表成功。",
			"10112" => "用户启用实时数据汇入数据库功能。",
			"10113" => "用户停用实时数据汇入数据库功能。",
			"10114" => "用户清除装置数据表成功。",
			"10115" => "用户清除事件成功。",
			"10116" => "用户开始更新装置 %variable0% 序号：%variable1% 固件。",
			"10301" => "装置 %variable0% 序号：%variable1% 上传照片并附加了一段讯息：%variable3%。",
			"10302" => "装置 %variable0% 序号：%variable1% 上传影片并附加了一段讯息：%variable3%。",
			"30000" => "警告：%variable0%",
			"30001" => "磁盘空间不足（%variable0% MB）。",
			"30002" => "使用者自 %variable0% 登入失败。",
			"30003" => "模块 %variable2% UID：%variable3% 已从装置 %variable0% 序号：%variable1% 上移除。",
			"50000" => "例外：%variable1%（错误代码：%variable0%）",
			"50001" => "解析装置 %variable1% 序号：%variable2% 的模块 %variable3% UID：%variable4% 资料失败。（错误代码：%variable0%）",
			"50002" => "装置 %variable1% 序号：%variable2% 自 %variable0% 登入失败。",
			"50006" => "传送事件通知电子邮件失败。",
			"50103" => "解析装置 %variable0% 序号：%variable1% 配置文件失败。",
			"50200" => "数据库运作例外：%variable1%（错误代码：%variable0%）",
			"50202" => "数据库没有足够的空间。",
			"50203" => "数据库空间已使用超过90%。",
			"50211" => "修改数据库表格 %variable1% 失败。（错误代码：%variable0%）",
			"50212" => "用户清除数据库失败。",
			"50213" => "用户移除装置 %variable0% 序号：%variable1% 相关数据表失败。",
			"50214" => "用户清除装置 %variable0% 序号：%variable1% 的模块 %variable2% UID：%variable3% 数据表失败。",
			"50215" => "用户移除装置 %variable0% 序号：%variable1% 的模块 %variable2% UID：%variable3% 数据表失败。"
		),
		"EVENT_LIST" => "事件列表",
		"NO_EVENT_NOTIFICATION" => "没有任何事件通知",
		"TIME" => "时间",
		"EVENT" => "事件",
		"EXPORT" => "汇出",
		"CLEAR" => "清除",
		"READ" => "全设为已读",
		"LOADING" => "读取中...",
		"SYSTEM_EVENT" => "系统事件",
		"VIDEO_EVENT" => "影像事件",
		"WHICH_EVENT_TYPE_YOU_WANT_TO_CLEAR" => "您想要清除哪些事件种类？",
		"TIME_RANGE" => "时间范围",
		"ALL" => "全部",
		"OLDER_THEN_1_MON" => "1个月前",
		"OLDER_THEN_3_MON" => "3个月前",
		"OLDER_THEN_6_MON" => "6个月前",
		"OLDER_THEN_1_YEAR" => "1年前",
		"OLDER_THEN_2_YEAR" => "2年前",
		"OLDER_THEN_3_YEAR" => "3年前",
		"MAKE_SURE_YOU_WANT_TO_CLEAR_EVENT_TYPE_YOU_SELECTED" => "您确定要清除您所选择的事件种类？",
		"MAKE_SURE_YOU_WANT_TO_MAKE_ALL_AS_READ" => "您确定要将所有事件设定为已读取？"
	),
	"DB_SETTING" => array(
		"NOTIFICATION_SETTINGS" => "事件通知设定",
		"EVENT" => "事件项目",
		"INSUFFICIENT_DATABASE_SPACE" => "数据库空间不足",
		"DATABASE_PROCESSING_ERROR_OR_TRANSACTION" => "无法与数据库建立联机",
		"MODULE_SETTINGS_CHANGE" => "模块设定异动",
		"DEVICE_DISCONNECTED" => "装置脱机",
		"UNKNOWN_TO_TRY_TO_LOG_IN_MORE_THAN_TEN_TIMES" => "使用错误的密码尝试登入超过十次",
		"CLEAR_DATABASE_DATA" => "清除数据库数据",
		"ALL_MODULE_DATA_WILL_BE_REMOVED_AND_CAN_NOT_BE_RECOVERED_AFTER_EXECUTION" => "指令执行后所有模块与群组储存于数据库的数据都将被移除且无法复原。",
		"PASSWORD" => "密码：",
		"SAVE" => "储存",
		"SAVE_COMPLETE" => "储存成功。",
		"SAVE_FAILED" => "储存失败。",
		"PLEASE_ENTER_A_PASSWORD" => "请输入密码。",
		"WHETHER_TO_CLEAR_THE_DATABASE_DATA" => "您确定要清除数据库数据吗？",
		"CLEAR" => "清除",
		"CLEAR_COMPLETE" => "清除成功。",
		"FAILED_TO_CLEAR" => "清除失败。",
		"PASSWORD_ERROR" => "清除失败。因密码输入错误。",
		"DATA_IMPORT" => "数据库汇入",
		"SET_WHETHER_IO_MODULES_AND_METER_DATA_ARE_IMPORTED_INTO_THE_DATABASE" => "设定是否将I/O模块数据及电表电力数据汇入数据库。",
		"ENABLED" => "启用",
		"HISTORY_IMPORT_ENABLED" => "历史数据",
		"REALTIME_IMPORT_ENABLED" => "实时数据",
		"SELECT_THE_EVENT_NOTIFICATION_YOU_LIKE_TO_RECEIVE_VIA_EMAIL_BELOW" => "在下方勾选您想要透过电子邮件收到的事件通知项目。"
	),
	"DB_INFO" => array(
		"DEVICE" => "装置",
		"MODULE" => "模块",
		"TABLE_DESCRIPTION" => "表格描述",
		"TABLE_NAME" => "表格名称",
		"ACTION" => "动作",
		"BUILD_IN" => "內建",
		"OTHER" => "其他",
		"INTERNAL_REGISTER" => "内部缓存器",
		"CLEAR" => "清除",
		"REMOVE" => "移除",
		"CLEAR_DATABASE" => "清除数据库",
		"CLEAR_DATABASE_DESCRIPTION" => "此功能能帮助您清除过旧的数据，以释放数据库空间。请选择您要清除多久以前的旧数据后按下清除按钮。",
		"OLDER_THEN_1_MON" => "1个月前",
		"OLDER_THEN_3_MON" => "3个月前",
		"OLDER_THEN_6_MON" => "6个月前",
		"OLDER_THEN_1_YEAR" => "1年前",
		"OLDER_THEN_2_YEAR" => "2年前",
		"OLDER_THEN_3_YEAR" => "3年前",
		"DATABASE_USAGE" => "您已使用了 %size% 的数据库空间",
		"DATABASE_USAGE_WITH_MAXSIZE" => "您已使用了 %max_size% 中的 %size% (%percent%%) 的数据库空间",
		"NO_DATA_SHEET_EXISTS" => "无任何数据表存在",
		"THE_INFORMATION_WILL_BE_DISPLAYED_WHEN_THE_DEVICE_RETURNS_DATA" => "待装置回传数据后即会显示相关信息",
		"MODULE_HAS_BEEN_REMOVED_FROM_THE_DEVICE_AND_THE_TABLE_WILL_NO_LONGER_BE_UPDATED" => "模块已从装置上移除，此表格不会再持续更新。",
		"MODULE_HAS_BEEN_REMOVED_FROM_THIS_DEVICE_AND_SOME_TABLES_WILL_NOT_BE_UPDATED" => "有模块已从此装置上移除，部分表格不会再持续更新。",
		"REMOVE_ALL_RELEVANT_INFORMATION_ABOUT_THIS_DEVICE_INCLUDING_GROUPING" => "移除此装置所有相关数据，包含I/O通道与电表回路分群资料等。",
		"MAKE_SURE_YOU_WANT_TO_CLEAR_DATABASE_DATA" => "您确定要清除数据库数据？",
		"MAKE_SURE_YOU_WANT_TO_CLEAR_THIS_MODULE_DATA" => "您确定要清除此模块数据？",
		"CLEAR_COMPLETE" => "清除成功。",
		"FAILED_TO_CLEAR" => "清除失败。",
		"MAKE_SURE_YOU_WANT_TO_REMOVE_THIS_MODULE" => "您确定要移除此模块数据？",
		"MAKE_SURE_YOU_WANT_TO_REMOVE_THIS_DEVICE_DATA" => "您确定要移除此装置资料？",
		"REMOVE_COMPLETE" => "移除成功。",
		"FAILED_TO_REMOVE" => "移除失败。",
		"REAL_TIME_DATA" => "实时数据",
		"HISTORICAL_DATA" => "历史信息",
		"COPY_DATA_TABLE" => "复制数据表",
		"SOURCE" => "来源",
		"DESTINATION" => "目标",
		"COPY" => "复制",
		"CLOSE" => "关闭",
		"SOURCE_AND_DESTINATION_MODULES_MUST_BE_SAME_MODEL" => "来源与目标模块必须是相同型号。",
		"COPY_SUCCESSFULLY" => "复制成功。",
		"COPY_FAILED_SOURCE_AND_DESTINATION_MODULES_NOT_SAME_MODEL" => "复制失败，来源与目标模块型号不相同。",
		"COPY_FAILED_DATA_ALREADY_EXIST_IN_DESTINATION_MODULE_TABLE" => "复制失败，数据已经存在于目标模块中。",
		"COPY_FAILED_NOT_ENOUGH_FREE_SPACE" => "复制失败，数据库空间不足。",
		"COPY_FAILED_UNHANDLED_EXCEPTION" => "复制失败，发生非预期的错误。"
	),
	"COUNTRY" => array(
		"1" => "阿布哈兹",
		"2" => "阿富汗",
		"3" => "阿尔巴尼亚",
		"4" => "阿尔及利亚",
		"5" => "美属萨摩亚(美国)",
		"6" => "安道尔",
		"7" => "安哥拉",
		"8" => "安圭拉(英国)",
		"9" => "安提瓜和巴布达",
		"10" => "阿根廷",
		"11" => "亚美尼亚",
		"12" => "阿鲁巴(荷兰)",
		"13" => "澳大利亚",
		"14" => "奥地利",
		"15" => "阿塞拜疆",
		"16" => "巴哈马",
		"17" => "巴林",
		"18" => "孟加拉国",
		"19" => "巴巴多斯",
		"20" => "白俄罗斯",
		"21" => "比利时",
		"22" => "伯利兹",
		"23" => "贝宁",
		"24" => "百慕大(英国)",
		"25" => "不丹",
		"26" => "玻利维亚",
		"27" => "波斯尼亚和黑塞哥维那",
		"28" => "博茨瓦纳",
		"29" => "巴西",
		"30" => "英属印度洋领地(英国)",
		"31" => "文莱",
		"32" => "保加利亚",
		"33" => "布基纳法索",
		"34" => "布隆迪",
		"35" => "柬埔寨",
		"36" => "喀麦隆",
		"37" => "加拿大",
		"38" => "佛得角",
		"39" => "开曼群岛(英国)",
		"40" => "中非共和国中非",
		"41" => "乍得",
		"42" => "智利",
		"43" => "中国",
		"44" => "圣诞岛(澳大利亚)",
		"45" => "科科斯(基林)群岛(澳大利亚)",
		"46" => "哥伦比亚",
		"47" => "科摩罗",
		"48" => "刚果(布)",
		"49" => "刚果(金)",
		"50" => "库克群岛(新西兰)",
		"51" => "哥斯达黎加",
		"52" => "科特迪瓦",
		"53" => "克罗地亚",
		"54" => "古巴",
		"55" => "库拉索(荷兰)",
		"56" => "塞浦路斯",
		"57" => "捷克",
		"58" => "丹麦",
		"59" => "吉布提",
		"60" => "多米尼克",
		"61" => "多米尼加",
		"62" => "厄瓜多尔",
		"63" => "埃及",
		"64" => "萨尔瓦多",
		"65" => "赤道几内亚",
		"66" => "厄立特里亚",
		"67" => "爱沙尼亚",
		"68" => "埃塞俄比亚",
		"69" => "福克兰群岛(英国、阿根廷争议)",
		"70" => "法罗群岛(丹麦)",
		"71" => "斐济",
		"72" => "芬兰",
		"73" => "法国",
		"74" => "法属波利尼西亚(法国)",
		"75" => "加蓬",
		"76" => "冈比亚",
		"77" => "格鲁吉亚",
		"78" => "德国",
		"79" => "加纳",
		"80" => "直布罗陀(英国)",
		"81" => "希腊",
		"82" => "格陵兰(丹麦)",
		"83" => "格林纳达",
		"84" => "关岛(美国)",
		"85" => "危地马拉",
		"86" => "根西(英国)",
		"87" => "几内亚",
		"88" => "几内亚比绍",
		"89" => "圭亚那",
		"90" => "海地",
		"91" => "洪都拉斯",
		"92" => "香港(中国)",
		"93" => "匈牙利",
		"94" => "冰岛",
		"95" => "印度",
		"96" => "印尼",
		"97" => "伊朗",
		"98" => "伊拉克",
		"99" => "爱尔兰",
		"100" => "马恩岛(英国)",
		"101" => "以色列",
		"102" => "意大利",
		"103" => "牙买加",
		"104" => "日本",
		"105" => "泽西(英国)",
		"106" => "约旦",
		"107" => "哈萨克斯坦",
		"108" => "肯尼亚",
		"109" => "基里巴斯",
		"110" => "科索沃",
		"111" => "科威特",
		"112" => "吉尔吉斯斯坦",
		"113" => "老挝",
		"114" => "拉脱维亚",
		"115" => "黎巴嫩",
		"116" => "莱索托",
		"117" => "利比里亚",
		"118" => "利比亚",
		"119" => "列支敦士登",
		"120" => "立陶宛",
		"121" => "卢森堡",
		"122" => "澳门(中国)",
		"123" => "马其顿",
		"124" => "马达加斯加",
		"125" => "马拉维",
		"126" => "马来西亚",
		"127" => "马尔代夫",
		"128" => "马里",
		"129" => "马耳他",
		"130" => "马绍尔群岛",
		"131" => "毛里塔尼亚",
		"132" => "毛里求斯",
		"133" => "马约特(法国)",
		"134" => "墨西哥",
		"135" => "密克罗尼西亚联邦密克罗尼西亚联邦",
		"136" => "摩尔多瓦摩尔多瓦",
		"137" => "摩纳哥",
		"138" => "蒙古",
		"139" => "黑山",
		"140" => "蒙特塞拉特(英国)",
		"141" => "摩洛哥",
		"142" => "莫桑比克",
		"143" => "缅甸",
		"144" => "纳戈尔诺-卡拉巴赫",
		"145" => "纳米比亚",
		"146" => "瑙鲁",
		"147" => "尼泊尔",
		"148" => "荷兰",
		"149" => "新喀里多尼亚(法国)",
		"150" => "新西兰",
		"151" => "尼加拉瓜",
		"152" => "尼日尔",
		"153" => "尼日利亚",
		"154" => "纽埃(新西兰)",
		"155" => "北塞浦路斯",
		"156" => "北马里亚纳群岛(美国)",
		"157" => "挪威",
		"158" => "朝鲜",
		"159" => "阿曼",
		"160" => "巴基斯坦",
		"161" => "帕劳",
		"162" => "巴勒斯坦",
		"163" => "巴拿马",
		"164" => "巴布亚新几内亚",
		"165" => "巴拉圭",
		"166" => "秘鲁",
		"167" => "菲律宾",
		"168" => "皮特凯恩群岛(英国)",
		"169" => "波兰",
		"170" => "葡萄牙",
		"171" => "德涅斯特河沿岸",
		"172" => "波多黎各(美国)",
		"173" => "卡塔尔",
		"174" => "留尼汪(法国)",
		"175" => "罗马尼亚",
		"176" => "俄罗斯",
		"177" => "卢旺达",
		"178" => "圣基茨和尼维斯",
		"179" => "圣赫勒拿、阿森松和特里斯坦-达库尼亚(英国)",
		"180" => "圣卢西亚",
		"181" => "圣皮埃尔和密克隆(法国)",
		"182" => "圣文森特和格林纳丁斯",
		"183" => "萨摩亚",
		"184" => "圣马力诺",
		"185" => "圣多美和普林西比",
		"186" => "沙特阿拉伯",
		"187" => "塞内加尔",
		"188" => "塞尔维亚",
		"189" => "塞舌尔",
		"190" => "塞拉利昂",
		"191" => "新加坡",
		"192" => "荷属圣马丁(荷兰)",
		"193" => "斯洛伐克",
		"194" => "斯洛文尼亚",
		"195" => "所罗门群岛",
		"196" => "索马里",
		"197" => "索马里兰",
		"198" => "南非",
		"199" => "韩国",
		"200" => "南奥塞梯",
		"201" => "南苏丹",
		"202" => "西班牙",
		"203" => "斯里兰卡",
		"204" => "苏丹",
		"205" => "苏里南",
		"206" => "斯瓦尔巴(挪威)",
		"207" => "斯威士兰",
		"208" => "瑞典",
		"209" => "瑞士",
		"210" => "叙利亚",
		"211" => "台湾",
		"212" => "塔吉克斯坦",
		"213" => "坦桑尼亚",
		"214" => "泰国",
		"215" => "梵蒂冈",
		"216" => "东帝汶",
		"217" => "多哥",
		"218" => "托克劳(新西兰)",
		"219" => "汤加",
		"220" => "特立尼达和多巴哥",
		"221" => "突尼斯",
		"222" => "土耳其",
		"223" => "土库曼斯坦",
		"224" => "特克斯和凯科斯群岛(英国)",
		"225" => "图瓦卢",
		"226" => "乌干达",
		"227" => "乌克兰",
		"228" => "阿联酋",
		"229" => "英国",
		"230" => "美国",
		"231" => "乌拉圭",
		"232" => "乌兹别克斯坦",
		"233" => "瓦努阿图",
		"234" => "委内瑞拉",
		"235" => "越南",
		"236" => "英属维尔京群岛(英国)",
		"237" => "美属维尔京群岛(美国)",
		"238" => "瓦利斯和富图纳(法国)",
		"239" => "西撒哈拉",
		"240" => "也门",
		"241" => "赞比亚",
		"242" => "津巴布韦"
	),
	"DASHBOARD" => array(
		"ENABLE" => "启用",
		"DISABLE" => "停用",
		"CLOSE" => "关闭",
		"DASHBOARD_NOT_EXIST" => "无任何仪表板存在",
		"CLICK_BUTTON_TO_CREATE_DASHBOARD" => "如要新增仪表板，请点 <input type='button' value='新增' class='create-button' onClick='showCreateDashboardWindow();'> 按钮。",
		"DASHBOARD_NOT_AVAILABLE_OR_EXPIRED" => "仪表板服务尚未启用或试用期已结束",
		"CONTACT_SYSTEM_ADMINISTRATOR" => "若您要使用此服务，请联络管理员。",
		"CREATE" => "新增",
		"EDIT" => "编辑",
		"COPY" => "复制",
		"FULLSCREEN" => "全屏幕",
		"SHARE" => "分享",
		"REMOVE" => "移除",
		"INTERNAL_REGISTER" => "内部缓存器",
		"DI_COUNTER_WITH_NO" => "DI计数器%channel%",
		"DO_COUNTER_WITH_NO" => "DO计数器%channel%",
		"INTERNAL_REGISTER_WITH_NO" => "内部缓存器%channel%",
		"LOOP_WITH_NO" => "回路%loop%",
		"PHASE_A" => "相位A",
		"PHASE_B" => "相位B",
		"PHASE_C" => "相位C",
		"TOTAL_AVERAGE" => "总和/平均",
		"POPUP" => array(
			"DASHBOARD_NOT_EXIST" => "无此仪表板。",
			"ARE_YOU_SURE_REMOVE_DASHBOARD" => "您确定要移除此仪表板吗？",
			"ARE_YOU_SURE_REMOVE_WIDGET" => "您确定要移除此组件吗？"
		),
		"CREATE_DASHBOARD" => array(
			"CREATE_DASHBOARD" => "新增仪表板",
			"EDIT_DASHBOARD" => "编辑仪表板",
			"NAME" => "名称",
			"LOCK_WIDGET" => "锁定组件",
			"AS_FIRST_PAGE" => "设为主页",
			"DATA_LENGTH" => "数据保留",
			"LAST_30_SECONDS" => "最近 30 秒",
			"LAST_1_MINUTE" => "最近 1 分钟",
			"LAST_3_MINUTES" => "最近 3 分钟",
			"LAST_5_MINUTES" => "最近 5 分钟",
			"LAST_10_MINUTES" => "最近 10 分钟",
			"DARK_MODE" => "夜间模式",
			"POPUP" => array(
				"NAME_IS_EMPTY" => "名称字段不能为空。"
			),
			"TIP" => array(
				"AS_FIRST_PAGE" => "此仪表板将会是『仪表板服务』的主页。",
				"DATA_LENGTH" => "保留最近一段时间所收集的数据。"
			)
		),
		"CREATE_WIDGET" => array(
			"ADD_WIDGET" => "新增组件",
			"EDIT_WIDGET" => "编辑组件",
			"TYPE" => "类型",
			"PROPERTY" => "参数",
			"CHANNEL" => "通道",
			"LINE_CHART" => "折线图",
			"BAR_CHART" => "直方图",
			"PIE_CHART" => "圆饼图",
			"GAUGE" => "计量表",
			"PLOT_BAR" => "柱状图",
			"VALUE" => "数值",
			"VALUE_TABLE" => "数值表格",
			"VALUE_LABEL_OVERLAY" => "数值标签迭加",
			"VALUE_OUTPUT" => "数值输出(按钮)",
			"VALUE_OUTPUT_SLIDER" => "数值输出(滑杆)",
			"VIDEO_EVENT_LIST" => "影像事件纪录",
			"TIME_CLOCK" => "时间显示",
			"COUNTDOWN_TIMER" => "倒数计时",
			"MAP" => "地图",
			"RICH_CONTENT" => "自定义内容",
			"TITLE" => "标题",
			"SIZE" => "大小",
			"DESCRIPTION" => "备注",
			"LIST" => "列表",
			"ACTION" => "动作",
			"ADD" => "新增",
			"REMOVE" => "移除",
			"NO_CHANNEL_EXIST" => "无任何通道存在",
			"CLICK_BUTTON_TO_ADD_CHANNEL" => "如要新增通道，请点『新增』按钮。",
			"POPUP" => array(
				"CHANNEL_NUMBER_CAN_NOT_LESS_THEN_X" => "通道数量不能小于 %number% 个。",
				"CHANNEL_NUMBER_CAN_NOT_GREATER_THEN_X" => "通道数量不能大于 %number% 个。"
			)
		),
		"ADD_CHANNEL" => array(
			"ADD_CHANNEL" => "新增通道",
			"SEARCH" => "搜寻",
			"BUILD_IN" => "內建",
			"DI_COUNTER" => "DI计数器",
			"DO_COUNTER" => "DO计数器",
			"OTHER" => "其他",
			"INTERNAL_REGISTER" => "内部缓存器",
			"NO_DEVICE_EXIST" => "无装置",
			"NO_ONLINE_DEVICE_EXIST" => "无上线装置",
			"NO_MODULE_EXIST" => "无模块",
			"MODULE_NOT_FOUND" => "无符合模块"
		),
		"SHARE_LINK" => array(
			"SHARE_LINK" => "分享连结",
			"URL" => "网址",
			"EXPIRATION_DATE" => "到期日期",
			"CONTROLLABLE" => "可控制",
			"ACTION" => "动作",
			"SHARE_LINK_NOT_EXIST" => "无任何分享连结",
			"CLICK_BUTTON_TO_CREATE_SHARE_LINK" => "如要新增分享连结，请点选右下角的 '+' 按钮。",
			"SET_EXPIRATION_DATE" => "设定到期日期",
			"REMOVE" => "移除",
			"POPUP" => array(
				"ARE_YOU_SURE_REMOVE_SHARE_LINK" => "您确定要移除此分享连结吗？"
			)
		)
	),
	"VIDEO" => array(
		"VIDEO_EVENT_DATA" => "影像事件信息",
		"NO_VIDEO_EVENT_EXIST" => "无影像事件",
		"THE_EVENT_WILL_SHOW_WHEN_VIDEO_INCOMING" => "当装置回送影像数据至IoTstar后即会显示于此页面上。",
		"THERE_IS_NO_VIDEO_EVENT_SELECT_OTHER_DEVICE" => "此装置无任何影像事件，请选择其他装置。",
		"SUN" => "日",
		"MON" => "一",
		"TUE" => "二",
		"WED" => "三",
		"THU" => "四",
		"FRI" => "五",
		"SAT" => "六",
		"NO_VIDEO_EVENT_EXIST_ON_SELECT_DATE" => "选择之日期无影像事件。",
		"NO_VIDEO_CAN_PLAY_ON_SELECT_DATE" => "选择之日期无影像可播放。",
		"TIP" => array(
			"SHARE_BY_USER" => "此事件由 %username% 与您分享。",
			"PHOTO" => "照片",
			"VIDEO" => "影片",
			"SELECT_ALL_OR_UNSELECT_ALL" => "全选 / 取消全选",
			"REMOVE" => "移除"
		),
		"POPUP" => array(
			"ARE_YOU_SURE_REMOVE_EVENT_YOU_SELECT" => "您确定要移除您所选择的事件吗？"
		),
		"DEVICE_FILTER" => array(
			"DEVICE_FILTER" => "装置筛选",
			"MODEL_NAME_AND_NICKNAME" => "型号 / 名称",
			"SERIAL_NUMBER" => "序号"
		)
	),
	"ERROR" => array(
		"SHARE_LINK_UNAVAILABLE" => "连结已失效",
		"YOUR_SHARE_LINK_MAY_EXPIRED_OR_NOT_EXIST" => "您输入的连结可能已经过期或是不存在。"
	)
);
?>