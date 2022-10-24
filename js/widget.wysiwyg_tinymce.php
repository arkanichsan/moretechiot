<?php
global $language;

$lang["WIDGET"]["WYSIWYG"] = array(
	"EDITOR" => array(
		"en" => "Editor",
		"tw" => "編輯器",
		"cn" => "编辑器"
	)
);
?>

widget.wysiwyg_tinymce = {
	minChannelNumber: 0,
	maxChannelNumber: 0,

	// Event function
	settingCreated: function($content, settings){// setting == undefined mean is create not modify
		var link_list=[];

		for(var accountUID in window.accounts){
			var account = window.accounts[accountUID];

			for(var dashboardUID in account.dashboards){
				var dashboard = account.dashboards[dashboardUID];

				link_list.push({title: dashboard.name, value: '#' + accountUID + "-" + dashboardUID});	
			}
		}

		$content.append(
			$("<div></div>").addClass("content-title").text("<?=$lang['WIDGET']['WYSIWYG']['EDITOR'][$language]?>")
		).append(
			$("<div></div>").attr("id","editor_div").append(
				$("<div></div>").attr("id", "editor")
			).append(
				$("<img>").css("margin", "30px").attr({"src":"./image/loader2.gif", "id":"tinymce_loader"})
			)
		);
		
		try{tinymce.remove();}
		catch(e){}
		
		tinymce.init(
		{
			selector: 'div#editor',
			skin:"mytinymceskin",
			content_css:['../js/TinyMCE/skins/ui/mytinymceskin/content.min.css','../css/general.css'],
			height : "640",
			menubar: false,
			table_toolbar: '',
			elementpath: false,
			resize: false,
			language: (function(){
				var c_lang = "<?php echo $_COOKIE[language]; ?>"; 
				if(c_lang == "tw")
					return "zh_TW";
				else if(c_lang=="cn")
					return "zh_CN";
				else
					return null;
			})(),
			
			//font style
			content_style: 'body { background-color:' + $("body").css("backgroundColor") + '; color:' + $("#dashboard-container").css("color") + '}',
			//content_css : ["../css/tinymce.css?"+new Date().getTime(),"../css/general.css?"+new Date().getTime()],
			
			fontsize_formats: '11px 13px 15px 17px 19px 20px 24px 36px 48px',
			
			
			font_formats: (function(){
				var c_lang = "<?php echo $_COOKIE[language]; ?>"; 
				if(c_lang == "tw")
					return "微軟正黑體=微軟正黑體,Microsoft JhengHei;新細明體=PMingLiU,新細明體;標楷體=標楷體,DFKai-SB,BiauKai;黑體=黑體,SimHei,Heiti TC; Andale Mono=andale mono,times; Arial=arial,helvetica,sans-serif; Arial Black=arial black,avant garde; Book Antiqua=book antiqua,palatino; Comic Sans MS=comic sans ms,sans-serif; Courier New=courier new,courier; Georgia=georgia,palatino; Helvetica=helvetica; Impact=impact,chicago; Symbol=symbol; Tahoma=tahoma,arial,helvetica,sans-serif; Terminal=terminal,monaco; Times New Roman=times new roman,times; Trebuchet MS=trebuchet ms,geneva; Verdana=verdana,geneva; Webdings=webdings; Wingdings=wingdings,zapf dingbats";
				else if(c_lang=="cn")
					return "微软雅黑='微软雅黑';宋体='宋体';黑体='黑体';仿宋='仿宋';楷体='楷体';隶书='隶书';幼圆='幼圆'; Andale Mono=andale mono,times; Arial=arial,helvetica,sans-serif; Arial Black=arial black,avant garde; Book Antiqua=book antiqua,palatino; Comic Sans MS=comic sans ms,sans-serif; Courier New=courier new,courier; Georgia=georgia,palatino; Helvetica=helvetica; Impact=impact,chicago; Symbol=symbol; Tahoma=tahoma,arial,helvetica,sans-serif; Terminal=terminal,monaco; Times New Roman=times new roman,times; Trebuchet MS=trebuchet ms,geneva; Verdana=verdana,geneva; Webdings=webdings; Wingdings=wingdings,zapf dingbats";
				else
					return "Andale Mono=andale mono,times; Arial=arial,helvetica,sans-serif; Arial Black=arial black,avant garde; Book Antiqua=book antiqua,palatino; Comic Sans MS=comic sans ms,sans-serif; Courier New=courier new,courier; Georgia=georgia,palatino; Helvetica=helvetica; Impact=impact,chicago; Symbol=symbol; Tahoma=tahoma,arial,helvetica,sans-serif; Terminal=terminal,monaco; Times New Roman=times new roman,times; Trebuchet MS=trebuchet ms,geneva; Verdana=verdana,geneva; Webdings=webdings; Wingdings=wingdings,zapf dingbats";
			})(),
			
			//plugins
			plugins: ['advlist autolink lists link image code fullscreen table'],
			toolbar1: ' fontselect | fontsizeselect | forecolor backcolor | bold italic underline strikethrough ',
			toolbar2: ' link image table | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | fullscreen code ',
			toolbar_mode: 'sliding',
			
			//Link
			typeahead_urls: false,
			link_list: link_list,
			
			
			//Image
			file_picker_types: 'image',
			file_picker_callback: function (cb, value, meta) {
				var input = document.createElement('input');
				input.setAttribute('type', 'file');
				input.setAttribute('accept', 'image/*');
				input.onchange = function () {
					var file = this.files[0];
					var reader = new FileReader();
					reader.onload = function () {
						var id = 'blobid' + (new Date()).getTime();
						var blobCache =  tinymce.activeEditor.editorUpload.blobCache;
						var base64 = reader.result.split(',')[1];
						var blobInfo = blobCache.create(id, file, base64);
						blobCache.add(blobInfo);
						cb(blobInfo.blobUri(), { title: file.name });
					};
					reader.readAsDataURL(file);
				};
				input.click();
			},
			
			init_instance_callback : function(){
				$("#tinymce_loader").hide();
				if(settings){
					tinymce.get("editor").setContent(settings.html);
				}
			},
			
			entity_encoding: 'raw',   
			force_br_newlines : true, 
			force_p_newlines : false, 
			forced_root_block : true,
			inline_boundaries: false
		});
	},
	channelUpdated: function($content, channels){
	},
	settingSaved: function(settings, channels){
		settings.html = tinymce.get("editor").getContent();
	},
	widgetCreated: function($content, settings){
		this.drawChart($content, settings);
	},
	widgetUpdated: function($content, settings){
		this.drawChart($content, settings);
	},
	widgetRemoved: function($content, settings, channels){
	},
	dataUpdated: function($content, settings){
	},
	dragstop: function($content, settings){
	},
	resizestart: function($content, settings){
	},
	resizing: function($content, settings){
	},
	resizestop: function($content, settings){
	},

	// Custom function
	drawChart: function($content, settings){
		$content.html('');
		$content.addClass("tinymce_content").html(settings.html);
	}
};