/**
*
*  AJAX IFRAME METHOD (AIM)
*  http://www.webtoolkit.info/
*
**/
var AIM = {
	frame : function(settings) {
		var id = 'f' + Math.floor(Math.random() * 99999);

		var iframe = document.createElement('iframe');
		iframe.style.display = "none";
		iframe.id = iframe.name = id;
		iframe.onComplete = settings.onComplete || function(){};
		iframe.onError = settings.onError || function(){};

		iframe.setAttribute("src", "about:blank");
		iframe.setAttribute("pid", setInterval(function(){
			var doc = AIM.getDocument(iframe);

			if(doc == null){
				clearInterval(iframe.getAttribute("pid"));
				iframe.onError();
			}
			else{
				if(doc.location.href != "about:blank") {
					clearInterval(iframe.getAttribute("pid"));

					iframe.onComplete(doc);
				}
			}
		}, 1000));

		document.body.appendChild(iframe);

		return id;
	},

	submit : function(form, settings) {
		form.setAttribute("target", AIM.frame(settings));

		if (settings && typeof(settings.onStart) == 'function') {
			return settings.onStart();
		}
		else {
			return true;
		}
	},

	getDocument: function(iframe){
		try{
			if (iframe.contentDocument) {
				var doc = iframe.contentDocument;
			}
			else if (iframe.contentWindow) {
				var doc = iframe.contentWindow.document;
			}
			else {
				var doc = iframe.document;
			}

			doc.location;

			return doc;
		}
		catch(error){
			return null;
		}
	}
};