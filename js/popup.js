function popupConfirmWindow(message, onClickOK, onClickCancel){
	popupOK = function(){
		$("#popup-background").hide();

		if(typeof(onClickOK) == "function"){
			onClickOK();
		}
	}

	popupCancel = function(){
		$("#popup-background").hide();

		if(typeof(onClickCancel) == "function"){
			onClickCancel();
		}
	}

	$("#popup-message").html(message);
	$("#popup-icon").removeClass("warning error ok").addClass("warning");
	$("#popup-cancel-button").show();
	$("#popup-background").show();
	$("#popup-wrapper").css("marginTop", ($(window).height() / 2 - $("#popup-wrapper").outerHeight() / 2 + $(window).scrollTop()) + "px");
}

function popupSuccessWindow(message, onClickOK){
	popupOK = function(){
		$("#popup-background").hide();

		if(typeof(onClickOK) == "function"){
			onClickOK();
		}
	}

	$("#popup-message").html(message);
	$("#popup-icon").removeClass("warning error ok").addClass("ok");
	$("#popup-cancel-button").hide();
	$("#popup-background").show();
	$("#popup-wrapper").css("marginTop", ($(window).height() / 2 - $("#popup-wrapper").outerHeight() / 2 + $(window).scrollTop()) + "px");
}

function popupErrorWindow(message, onClickOK){
	popupOK = function(){
		$("#popup-background").hide();

		if(typeof(onClickOK) == "function"){
			onClickOK();
		}
	}

	$("#popup-message").html(message);
	$("#popup-icon").removeClass("warning error ok").addClass("error");
	$("#popup-cancel-button").hide();
	$("#popup-background").show();
	$("#popup-wrapper").css("marginTop", ($(window).height() / 2 - $("#popup-wrapper").outerHeight() / 2 + $(window).scrollTop()) + "px");
}
