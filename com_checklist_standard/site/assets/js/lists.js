var checklist_id, inputTitle, inputAlias, inputDescription_before, inputDescription_after, publish_date, list_access, comment_access, meta_keywords, meta_description, cm_names, cm_values, tags, chk_default, current_checklist_object, language, template;


var Checklist = {
	
	ajaxSaveChecklist: function(){
		
		Checklist.resetErrorMsg();
		if(Checklist.pickData()){
			if(checklist_id == ''){
				var feedback = Checklist.addNewChecklist;
			} else {
				var feedback = Checklist.updateChecklist;
			}

			Checklist.doAjax('lists.save', {checklist_id: checklist_id, title: inputTitle, alias:inputAlias, description_before: inputDescription_before, description_after:inputDescription_after, publish_date: publish_date, tags: tags, list_access: list_access, comment_access:comment_access, meta_keywords: meta_keywords, meta_description: meta_description, cm_names: cm_names, cm_values: cm_values, chk_default: chk_default, lg: language, template: template}, feedback);		
		}
	},

	pickData: function()
	{
		cm_names = new Array();
		cm_values = new Array();
		get_content();

		checklist_id = jQuery("#checklist_id").val();
		inputTitle = jQuery("#inputTitle").val();
		inputAlias = jQuery("#inputAlias").val();		
		
		chk_default = jQuery("#chk_default").val();

		publish_date = jQuery("#publish_date").val();
		list_access = jQuery("#list_access").val();
		comment_access = jQuery("#comment_access").val();
		meta_keywords = jQuery("#meta_keywords").val();
		meta_description = jQuery("#meta_description").val();
		language = jQuery("#language").val();
		template = jQuery("#template").val();
		tags = jQuery('input[name="tags"]').val();

		var ct_names = jQuery('input[name="cm_names"]');
		var ct_values = jQuery('input[name="cm_values"]');
		
		if(ct_names.length){
			for(var i = 0; i < ct_names.length; i++){
				cm_names.push(jQuery(ct_names[i]).val());
			}
		}

		if(ct_values.length){
			for(var i = 0; i < ct_values.length; i++){
				cm_values.push(jQuery(ct_values[i]).val());
			}
		}

		if(inputTitle == '') {
			Checklist.setErrorMsg("danger", "Input title of checklist");
			jQuery("#inputTitle").focus();
			return false;
		}

		jQuery("#ajax-loader").show();
		return true;

	},
		
	addNewChecklist: function(html){
		
		jQuery("#ajax-loader").hide();
		parent.jQuery(".my-checklist").append(html);
		
		
		parent.Checklist.bindShowTools();
		parent.Checklist.bindChecklistsTools();
		
		//Alert
		parent.jQuery(".alert").hide("slow");
		parent.jQuery(".alert-success").html('Checklist succsessfully added!');
		parent.jQuery(".alert-success").slideDown("slow");

		parent.jQuery.fancybox.close();
		
		return true;
	},

	updateChecklist: function(text){

		jQuery("#ajax-loader").hide();
		parent.jQuery(parent.current_checklist_object).find('.list-group-item-heading').text(inputTitle);
		
		parent.jQuery(parent.current_checklist_object).find('.list-group-item-text').text(text);

		//Alert
		parent.jQuery(".alert").hide("slow");
		parent.jQuery(".alert-success").html('Checklist succsessfully changed!');
		parent.jQuery(".alert-success").slideDown("slow");

		parent.jQuery.fancybox.close();
		
		return true;
	},	
		
	ajaxDeleteChecklist: function(object, checklist_id){
		
		Checklist.resetErrorMsg();
		
		jQuery.Zebra_Dialog(are_you_sure_to_remove_checklist, {
			'type':     'question',
			'title':    chk_confirm_window,
			 width:		295,
			'buttons':  [chk_confirm, chk_cancel],
			'onClose':  function(caption) {
				if(caption == chk_confirm){
					Checklist.doAjax('lists.remove', {id: checklist_id}, Checklist.removeChecklist, object);
				} else {
					return true;
				}
			}
		});
		
	},
	
	removeChecklist: function(html, object){
		
		if(html == 'success')
		jQuery(object).parent().parent().remove();
			
		Checklist.setErrorMsg('success', checklist_was_successfully_removed);
		return true;
		
	},
	
	doAjax: function(task, params, feedback, object){
				
		var object = (typeof(object) != 'undefined') ? object : null;
		jQuery.ajax({
			url: "index.php?option=com_checklist&task=" + task,
			type: "POST",
			data: params,
			dataType: "html",
			success: function(html){
				feedback(html, object);
			}
		});
		
	},
	
	resetErrorMsg: function (){
		jQuery(".alert").hide("slow");
	},
	
	setErrorMsg: function(type, msg){
		
		if(type == 'danger'){
			jQuery(".alert-danger").html(msg);
			jQuery(".alert-danger").slideDown("slow");
		}
		
		if(type == 'success'){
			jQuery(".alert-success").html(msg);
			jQuery(".alert-success").slideDown("slow");
		}
		
	},
	
	bindChecklistsTools: function(){
		
		//Delete checklist
		jQuery(".chk-delete-checklist").bind("click", function(event){
						
			var checklist_id = jQuery(this).parent().attr("checklistid");
			Checklist.ajaxDeleteChecklist(this, checklist_id);
		});
	},

	editChecklist: function(img){

		var checklistid = jQuery(img).parent().attr('checklistid');
		current_checklist_object = jQuery(img).parent().parent();

		var content_iframe = jQuery("#content_iframe").attr('src','index.php?option=com_checklist&view=edit_checklist&checklist_id=' + checklistid + '&tmpl=component');


		jQuery("#content_iframe").ready(function(){

			jQuery.fancybox.open(content_iframe, {
				maxWidth	: 800,
				maxHeight	: 600,
				fitToView	: true,
				width		: '70%',
				height		: '70%',
				autoSize	: false,
				closeClick	: false,
				openEffect	: 'none',
				closeEffect	: 'none'
			});
		});				

	},
	
	inputPreventDefault: function(event){
		event = event || window.event;
		
		if(event.preventDefault){
			event.preventDefault();
		} else {
			event.cancelBubble = true;
		}
		
		if(event.stopPropagation){
			event.stopPropagation();
		} else {
			event.returnValue = false;
		}
	},

	bindChecklistsTools: function(){
		
		//Edit checklist
		jQuery(".chk-edit-checklist").bind("click", function(event){
			Checklist.inputPreventDefault(event);
			Checklist.editChecklist(this);
		});
		
		//Delete checklist
		jQuery(".chk-delete-checklist").bind("click", function(event){
			Checklist.inputPreventDefault(event);
			
			var checklist_id = jQuery(this).parent().attr("checklistid");
			Checklist.ajaxDeleteChecklist(this, checklist_id);
		});
	},
	
	bindShowTools: function(){
		jQuery(".list-group-item").bind("mouseenter", function(){
			jQuery(this).find(".chk-remove-chklist").css("visibility", "visible");
		});
		jQuery(".list-group-item").bind("mouseleave", function(){
			jQuery(this).find(".chk-remove-chklist").css("visibility", "hidden");
		});
	},
	
	onInitApplication: function(){

		jQuery("#chk-ajax-save").bind("click", function(){
			Checklist.ajaxSaveChecklist();
		});

	}
}