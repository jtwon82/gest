$(document).ready(function() {! function() {
		function detectDevTool(allow) {
			if (isNaN(+allow))
				allow = 100;
			var start = +new Date();
			debugger;
			var end = +new Date();
			if (isNaN(start) || isNaN(end) || end - start > allow) {
				alert('개발자 도구 사용을 금지합니다. \n지속적인 시도 시 계정에 불이익을 받을 수 있습니다.');
				document.location.href = "https://jjansun.com/";
			}
		}

		if (window.attachEvent) {
			if (document.readyState === "complete" || document.readyState === "interactive") {
				detectDevTool();
				window.attachEvent('onresize', detectDevTool);
				window.attachEvent('onmousemove', detectDevTool);
				window.attachEvent('onfocus', detectDevTool);
				window.attachEvent('onblur', detectDevTool);
			} else {
				setTimeout(argument.callee, 0);
			}
		} else {
			window.addEventListener('load', detectDevTool);
			window.addEventListener('resize', detectDevTool);
			window.addEventListener('mousemove', detectDevTool);
			window.addEventListener('focus', detectDevTool);
			window.addEventListener('blur', detectDevTool);
		}
	}();

	$(document).keydown(function(e) {
		if (e.keyCode == 123 || e.keyCode == 73) {
			e.preventDefault();
			alert('개발자 도구 사용을 금지합니다. \n지속적인 시도 시 계정에 불이익을 받을 수 있습니다.');
			return false;
		}
	});

	document.onmousedown = rightClickCheck;
	function rightClickCheck(event) {
		if (event.button == 2) {
			alert("마우스 우클릭은 사용할 수 없습니다.");
			return false;
		}
	}
	
	document.oncontextmenu = function(){
		alert("마우스 우클릭은 사용할 수 없습니다.");
		return false;
	}

})