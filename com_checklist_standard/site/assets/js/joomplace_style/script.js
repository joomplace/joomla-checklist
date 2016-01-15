﻿/// <reference path="modernizr-2.6.2.js" />
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

        details = document.getElementsByTagName('em');
        checkboxesRandom = checkboxes.slice(0);
        randomizeArray(checkboxesRandom);

        for(var n=0; n < details.length; n++){
            if(details[n].className == 'checklist-info-icon'){
                detailsRandom.push(details[n]);
            }
        }
        
        randomizeArray(detailsRandom);
    }

    function initialize() {

        bonus = document.getElementsByTagName("mark")[0];
        progress = document.getElementsByTagName("progress")[0];
        menu = document.getElementsByTagName("nav")[0];
        fallback = (progress.firstElementChild || progress.firstChild);
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
            	jQuery(".checklist-section-details").css("maxHeight", "0px");
            }
        }

        progress.max = max;
        if (prefix && prefix.endsWith('demo')) { startDemo(); }
    }

    function openDetails(e) {

        if (!e) e = window.event;
        var detail = (e.target || e.srcElement);
        openDetailsElement(detail);
        for (i = 0; i < details.length; i++) {
            var detail = details[i];
            if (detail.className === 'open') {
                localStorage && localStorage.setItem(prefix + detail.id, true);
            } else {
                localStorage && localStorage.removeItem(prefix + detail.id);
            }
        }
    }

    function openDetailsElement(detail) {

        var ul = (detail.nextElementSibling || detail.nextSibling);
        for (var i = 0; i < details.length; i++) {
            if (details[i] !== detail) {
                var d = (details[i].nextElementSibling || details[i].nextSibling);
                d.style.maxHeight = "0";
            }
            details[i].className = '';
        }        

        if (ul.style.maxHeight !== '500px') {
            ul.style.maxHeight = '500px';
            detail.className = 'open';
        }
        else {
            ul.style.maxHeight = '0';
        }
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

        bonus.innerHTML = optional.toString();
        setProgressValue(count);
    }

    function setProgressValue(value) {

        progress.value = value;
        var max = parseInt(progress.max, 10);
        fallback.style.width = (value * 100 / max) + "%";
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

    function menuClick(e) {

        if (!e) e = window.event;
        var element = (e.target || e.srcElement);

        if (menu.className !== "open") {
            menu.className = "open";
        }
        else {
            menu.className = "";
        }
    }

    window.onload = function () {

        findCheckboxes();
        initialize();
        calculateProgress();
        reset();

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

    function startDemo() {
        animateChecboxes(0, checkboxesRandom);
        animateDetails(0, detailsRandom);
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

    function animateDetails(index, items) {

        if (index > detailsRandom.length - 1) { index = 0; resetInner(); }
        openDetailsElement(items[index]);
        setTimeout(function () {
            animateDetails(++index, items);
        }, 200 * 10);
    }

    String.prototype.endsWith = function (suffix) {
        return this.indexOf(suffix, this.length - suffix.length) !== -1;
    };