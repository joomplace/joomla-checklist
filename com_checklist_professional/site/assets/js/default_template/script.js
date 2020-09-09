/// <reference path="modernizr-2.6.2.js" />
    var checkboxes = [],
        checkboxesRandom = [],
        details = [],
        detailsRandom = [],
        progress, bonus, fallback, prefix, menu;

    function findCheckboxes() {

        var inputs = document.getElementsByTagName('input');
        for (var i = 0; i < inputs.length; i++) {
            if (inputs[i].type === 'checkbox' && inputs[i].className == 'chk-checkbox' && inputs[i].className != 'chk-optional') {
                checkboxes.push(inputs[i]);
            }
        }

        details = jQuery('a.checklist-info-icon');
        checkboxesRandom = checkboxes.slice(0);
        randomizeArray(checkboxesRandom);
    }

    function initialize() {

        bonus = document.getElementsByTagName("mark")[0];
        progress = jQuery(".progress");
        
        fallback = jQuery(".progress-bar");
        prefix = location.pathname.substr(1);
       
        var max = 0;
        for (var i = 0; i < checkboxes.length; i++) {

            var checkbox = checkboxes[i];
            checkbox.onchange = calculateProgress;
            
            if(!chk_authorized){
                if (Modernizr.localstorage) {

                    var value = localStorage.getItem(prefix + checkbox.id) === "true";
                    checkbox.checked = value;
                }
            } else {
            	
				if(checkedArray.length && typeof checkedArray[i] != 'undefined'){
				    checkbox.checked = checkedArray[i];
                }				
			}

            if (checkbox.parentNode.className !== "optional")
                max++;
        }

        for (var d = 0; d < details.length; d++) {
            details[d].onclick = openDetails;
        }

        for (var j = 0; j < details.length; j++) {
            var detail = details[j];
            if (Modernizr.localstorage && localStorage.getItem(prefix + detail.id)){
                openDetailsElement(detail);
            } else {
            	jQuery(".panel-collapse").css("height", "auto");
                jQuery(".panel-collapse").css("display", "none");
            }
        }

        progress.data("max", max);
    }

    function openDetails(e) {

        if (!e) e = window.event;
        var detail = (e.target || e.srcElement);
        if(jQuery(detail).parent().parent().next().css('display') == 'block'){
        	jQuery(detail).parent().parent().next().slideUp(300);
        } else {
        	jQuery(".panel-collapse").css("height", "auto");
        	jQuery(".panel-collapse").css("display", "none");
        	openDetailsElement(detail);
        }
    }

    function openDetailsElement(detail) {
       	jQuery(detail).parent().parent().next().slideDown(300);	        
    }

    function calculateProgress() {

        var count = 0,
            optional = 0;

        for (var i = 0; i < checkboxes.length; i++) {
            var checkbox = checkboxes[i];
            if (checkbox.checked)
                localStorage && localStorage.setItem(prefix + checkbox.id, checkbox.checked);
            else
                localStorage && localStorage.removeItem(prefix + checkbox.id);
            if (checkbox.parentNode.className !== "optional") {
                if (checkbox.checked) {
                    count++;
                }
            }
            else {
                if (checkbox.checked) {
                    optional++;
                }
            }
        }

        setProgressValue(count);
    }

    function setProgressValue(value) {

        progress.value = value;
        var max = parseInt(progress.data("max"));
        var width = Math.round(value * 100 / max);

        fallback.css("width", width + "%");
        
        document.getElementById("keep-going").style.opacity = 1 - (value / max);
        document.getElementById("well-done").style.opacity = (value / max);
    }

    function reset() {
        document.getElementById("reset").onclick = function () {
            resetInner();
            return false;
        };
    }

    function resetInner() {
        for (var i = 0; i < checkboxes.length; i++) {
            checkboxes[i].checked = false;
            if (Modernizr.localstorage) localStorage.removeItem(prefix + checkboxes[i].id);
        }

        for (var j = 0; j < details.length; j++) {
            if (Modernizr.localstorage) localStorage.removeItem(prefix + details[j].id);
        }

        calculateProgress();
    }

    window.onload = function () {

        findCheckboxes();
        initialize();
        calculateProgress();
        //reset();

        if (localStorage.length === 0){
            if ( /Safari/i.test(navigator.userAgent)){
				var a = details[0];
				var evObj = document.createEvent("MouseEvents");
				evObj.initMouseEvent("click", true, true, window);
			} else{
				details[0].click();
			}
        }
		
    };

    // demo related items below this
    function randomizeArray(myArray) {

        // Taken from: http://sedition.com/perl/javascript-fy.html

        var i = myArray.length, j, tempi, tempj;
        if (i == 0) return false;
        while (--i) {
            j = Math.floor(Math.random() * (i + 1));
            tempi = myArray[i];
            tempj = myArray[j];
            myArray[i] = tempj;
            myArray[j] = tempi;
        }
    }


    function animateChecboxes(index, items) {

        if (index == 0) { resetInner(); }
        if (index > items.length + 5) {
            // going over the length on purpose, so that it pauses at the end
            index = 0; resetInner(); items = checkboxesRandom;
        }

        if (items[index]) {
            items[index].checked = true;
            calculateProgress();
        }

        setTimeout(function() {
            animateChecboxes(++index, items);
        }, 200);
    }

    
    String.prototype.endsWith = function (suffix) {
        return this.indexOf(suffix, this.length - suffix.length) !== -1;
    };