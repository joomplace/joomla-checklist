<?php
/**
* Checklist component for Joomla 3.0
* @package Checklist
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted Access');
JHtml::_('bootstrap.tooltip', '.hasTooltip', array('viewport'=>'body'));

$user = JFactory::getUser();
$document = JFactory::getDocument();

$document->addStyleSheet(JURI::root()."components/com_checklist/assets/css/default_template/checklist.css");
$document->addScript(JURI::root()."components/com_checklist/assets/js/modernizr-2.6.2.js");
$document->addScript(JURI::root()."components/com_checklist/assets/js/default_template/script.js");
$document->addScript(JURI::root()."components/com_checklist/assets/js/jquery-scrolltofixed-min.js");

?>

<style>
	
	@font-face {
		font-family: 'halflings';
		src:url('<?php echo JURI::root();?>components/com_checklist/assets/css/default_template/fonts/glyphicons-halflings-regular.eot');
		src:url('<?php echo JURI::root();?>components/com_checklist/assets/css/default_template/fonts/glyphicons-halflings-regular.eot?#iefix') format('embedded-opentype'),
			url('<?php echo JURI::root();?>components/com_checklist/assets/css/default_template/fonts/glyphicons-halflings-regular.woff') format('woff'),
			url('<?php echo JURI::root();?>components/com_checklist/assets/css/default_template/fonts/glyphicons-halflings-regular.ttf') format('truetype'),
			url('<?php echo JURI::root();?>components/com_checklist/assets/css/default_template/fonts/glyphicons-halflings-regular.svg#halflings') format('svg');
		font-weight: normal;
		font-style: normal;
	}

	<?php if($this->allow_edit && $this->edit_mode){?>
	.li-handle label:hover{
		cursor:move;
	}

	.checklist-section-header:hover{
		cursor: move;
	}
	
	<?php } ?>
	
</style>

<?php

$lang = JFactory::getLanguage();
$tag = $lang->getTag();
$tag = str_replace("-", "_", $tag);

?>

<?php if ($this->config->social_facebook_use == 1) {?>
<div id="fb-root"></div>
<script type="text/javascript">
	(function(d, s, id) {
		var js, fjs = d.getElementsByTagName(s)[0];
		if (d.getElementById(id)) return;
		js = d.createElement(s); js.id = id;
		js.src = "//connect.facebook.net/<?php echo $tag;?>/all.js#xfbml=1";
		fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));
</script>
<?php } ?>

<div style="margin-left:5px;" id="checklist-root">

<?php
$checklistUserName = JFactory::getUser($this->checklist->user_id)->name;
$checklistUserName = !empty($checklistUserName) ? ' (' . $checklistUserName . ')' : '';
?>

<?php if($this->checklist):?>
<div id="checklist">
	<header itemscope="" itemtype="http://schema.org/WebApplication">
        <h1 itemprop="name"><?php echo $this->checklist->title /*. $checklistUserName*/ ; ?></h1>
	</header>
	
    <div class="progress progress-striped">
	      <span id="keep-going" class="label label-warning" style="opacity:1;"><?php echo JText::_('COM_JOOMLAQUIZ_KEEP_GOING');?></span>
		  <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100">
			<span class="sr-only"></span>
		  </div>
		  <span id="well-done" class="label label-success"><?php echo JText::_('COM_JOOMLAQUIZ_WELL_DONE');?></span>
	      <a href="javascript:void(0);" id="reset"><span class="label label-default"><?php echo JText::_('COM_CHECKLIST_RESET')?></span></a>
    </div>
	
	<p><?php echo $this->checklist->description_before;?></p>
	
	<div id="chk-main" class="checklist-container">
		<?php if(count($this->groups)):?>
		<?php foreach($this->groups as $group):?>
		<section class="checklist-section" id="<?php echo $group->section_id;?>" groupid="<?php echo $group->id;?>">
			<h2 class="checklist-section-header"><?php echo $group->title;?>
				<a class="accordion-toggle active" data-toggle="collapse" data-parent="#<?php echo $group->section_id;?>" href="#collapse<?php echo $group->id;?>">
				<span class="caret"></span></a>				
			</h2>		
			<ul class="checklist-section-list accordion-body collapse in" id="collapse<?php echo $group->id;?>">
				<?php if(count($group->items)):?>
				<?php foreach($group->items as $i => $item):?>
				<li <?php if($item->optional){?> class="li-handle optional" <?php } else {?> class="li-handle" <?php }?> itemid="<?php echo $item->id;?>">
					<input type="checkbox" id="<?php echo $item->input_id;?>" tabindex="<?php echo ($i+1);?>" class="chk-checkbox">
					<label for="<?php echo $item->label_for;?>"><?php echo $item->task;?>
                        <?php if(trim(strip_tags($item->tips))){ ?>
                            <a data-toggle="collapse" data-parent="#chk-main" class="checklist-info-icon" id="details-<?php echo $item->input_id;?>"><span class="glyphicon glyphicon-info-sign"></span></a>
                        <?php } ?>
                    </label>
                    <?php if(trim(strip_tags($item->tips))){ ?>
                        <ul class="panel-collapse collapse">
                            <?php echo $item->tips;?>
                        </ul>
                    <?php } ?>
                </li>
				<?php endforeach;?>
				<?php endif;?>
			</ul>
		</section>
		<?php endforeach;?>
		<?php endif;?>
	</div>
	<p><?php echo $this->checklist->description_after;?></p>
</div>

<input type="hidden" name="checklist_id" id="checklist_id" value="<?php echo $this->checklist->id;?>" />
<?php endif;?>

<div style="clear: both;"></div>
<div class="checklist-social">
<?php
	$itemid = JFactory::getApplication()->input->get('Itemid', 0);
	$itemid = ($itemid) ? $itemid : '';

	$userid = JFactory::getApplication()->input->get('userid', 0);
	$userid = ($userid) ? $userid : $user->id;

    $custom_metatags = json_decode($this->checklist->custom_metatags);

	$pageLink = JRoute::_('index.php?option=com_checklist&view=checklist&id='.$this->checklist->id.'&userid='.$userid.'&Itemid='.$itemid, false, -1);

?>
	<div class="checklist-social-btn">

		<?php if($this->config->social_google_plus_use):?>
		<!-- Google plus -->
		<div class="checklist-social-btn">
			<div class="g-plusone" data-width="70" data-size="<?php echo $this->config->social_google_plus_size?>" data-annotation="<?php echo $this->config->social_google_plus_annotation ?>" href="<?php echo $pageLink ?>">
			</div>
		</div>
		<?php endif;?>

		<?php if($this->config->social_twitter_use):?>
		<!-- Twitter -->
        <div class="checklist-social-btn">
            <a href="https://twitter.com/intent/tweet" class="twitter-share-button"
               data-text="<?php echo $custom_metatags->{'twitter:title'}; ?>"
               data-url="<?php echo $pageLink ?>"
               data-size="<?php echo $this->config->social_twitter_size ?>"
               data-count="<?php echo $this->config->social_twitter_annotation?>"
               data-lang="<?php echo $this->config->social_twitter_language ?>">Tweet</a>
        </div>
		<?php endif;?>

		<?php if($this->config->social_linkedin_use):?>
		<!-- LinkedIn -->
		<div class="checklist-social-btn">
			<script type="IN/Share" data-url="<?php echo $pageLink ?>" data-counter="<?php echo $this->config->social_linkedin_annotation ?>"></script>
		</div>
		<?php endif;?>

		<?php if($this->config->social_facebook_use):?>
		<!-- Facebook -->
		<div class="checklist-social-btn">
			<div class="fb-like" data-show-faces="false" data-width="50" data-colorscheme="light" data-share="false" data-action="<?php echo $this->config->social_facebook_verb ?>" data-layout="<?php echo $this->config->social_facebook_layout ?>" data-href="<?php echo $pageLink ?>">
			</div>
		</div>
		<?php endif;?>
	</div>
</div>
<div style="clear: both;"><br/><br/></div>

<script type="text/javascript">
			
	var chk_base_URL = '<?php echo JURI::root();?>';
	var checkedArray = new Array();
	var chk_authorized = false;
	<?php if($this->authorized):?>
	chk_authorized = true;
	<?php endif;?>

	<?php if(count($this->groups) && $this->authorized):?>
	<?php foreach($this->groups as $group):?>
		
	<?php if(count($group->items) && count($this->checkedList)):?>
	<?php foreach($group->items as $i => $item):?>

	checkedArray.push(<?php echo ((isset($this->checkedList[$item->id]) && $this->checkedList[$item->id]) ? $this->checkedList[$item->id] : 0);?>);<?php echo "\n";?>

	<?php endforeach;?>
	<?php endif;?>
	<?php endforeach;?>
	<?php endif;?>

	var Checklist = {
				
		bindClickToTips: function(){
			
			findCheckboxes();
			initialize();
			calculateProgress();
			reset();
			
			if (localStorage.length === 0) {

				if ( /Safari/i.test(navigator.userAgent)){
					
					var a = details[0];
					var evObj = document.createEvent("MouseEvents");
					evObj.initMouseEvent("click", true, true, window);
					
				} else{
					details[0].click();
				}
			}
		},
				
		doAjax: function(task, params, feedback, object){
			
			var object = (typeof(object) != 'undefined') ? object : null;
			jQuery.ajax({
				url: chk_base_URL + "index.php?option=com_checklist&task=" + task + '&tmpl=component',
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
				
		successFunction: function(){
			return false;
		},
				
		bindChecked: function(){
			jQuery(".li-handle label").bind("click", function(){
				Checklist.ajaxCheckedItem(this);
			});

			jQuery("#reset").bind("click", function(){
				Checklist.ajaxResetChecked();
			});
		},
				
		ajaxResetChecked: function(){
			var checklist_id = jQuery("#checklist_id").val();
			Checklist.doAjax('checklist.resetchecked', {id: checklist_id}, Checklist.successFunction);
		},

		ajaxCheckedItem: function(element){
			
			var checklist_id = jQuery("#checklist_id").val();
			var itemid = jQuery(element).parent().attr("itemid");
			var groupid = jQuery(element).parent().parent().parent().attr("groupid");
			var checked = (jQuery(element).prev().prop('checked') == true) ? 0 : 1;
			
			Checklist.doAjax('checklist.checkeditem', {id: checklist_id, groupid: groupid, item: itemid, checked: checked}, Checklist.successFunction);
			
		},
						
		initApplication: function(){
		
		<?php if($this->authorized){?>
			Checklist.bindChecked();
		<?php }?>
			Checklist.bindClickToTips();
			jQuery('.progress').scrollToFixed({
				preFixed: function() { jQuery('.progress').css('marginTop', '0px'); },
        		postFixed: function() { jQuery('.progress').css('marginTop', '80px'); },
        		limit: jQuery('#chk-main').outerHeight() + 500
			});
		},
		
		PreventDefaultAction: function(event){
		
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
		}
	}
	
	Checklist.initApplication();

	jQuery(function ($) {
        //fix Purity iii (demo site)
        if($('#t3-content').length){
            $('#checklist').find('.caret').each(function(){
                $(this).on('click', function(e){
                    e.preventDefault();
                    e.stopPropagation();
                    $(this).closest('.checklist-section').find('.checklist-section-list').toggle();
                });
            });
        }

        $('.chr-dialog-window').on('click', function () {   //Edit with Markdown Editor
            var text = $(this).closest('form').find('textarea').val();
            setTimeout(function () {
                $('.markdown-editor').val(text);
            }, 500);
        });

    });

</script>

<?php if ($this->config->social_google_plus_use == 1) { ?>
	
<script type="text/javascript">
window.___gcfg = {lang: '<?php echo $this->config->social_google_plus_language; ?>'};
(function() {
	var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
	po.src = 'https://apis.google.com/js/plusone.js';
	var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
})();
</script>
	
<?php } ?>

<?php if ($this->config->social_twitter_use == 1) { ?>
	
<script type="text/javascript">
(function() {
    var twitterScriptTag = document.createElement('script');
    twitterScriptTag.type = 'text/javascript';
    twitterScriptTag.async = true;
    twitterScriptTag.src = 'http://platform.twitter.com/widgets.js';
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(twitterScriptTag, s);
    })();
</script>
	
<?php } ?>

<?php if ($this->config->social_linkedin_use == 1) { ?>
	
<script type="text/javascript" src="//platform.linkedin.com/in.js"></script>
	
<?php } ?>

</div>