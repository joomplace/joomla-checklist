<?php defined('_JEXEC') or die('Restricted access');
/*
* Checklist Component
* @package Checklist
* @author JoomPlace Team
* @copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

class ChecklistModelSampledata extends JModelAdmin
{
	//----------------------------------------------------------------------------------------------------
	public function __construct($config = array())
	{
		parent::__construct($config);
	}
	
	public function installSampledata($post)
	{
		$db = JFactory::getDBO();

		if($post['sampledata'] == 'sample1'){

			$db->setQuery("INSERT INTO `#__checklist_lists` (`id`, `user_id`, `title`, `alias`, `description_before`, `description_after`, `default`, `published`, `publish_date`, `list_access`, `comment_access`, `meta_keywords`, `meta_description`, `custom_metatags`, `language`, `template`) VALUES ('', 0, 'Successful Joomla! Site Checklist', 'successful-joomla!-site-checklist', '<p>There are a number of things to consider when creating or maintaining Joomla! website. To remember all the important aspects making your site popular and convenient for users, and securing its flawless operation, we have compiled a <strong>Successful Joomla! Site Checklist</strong>. It’s a guide providing developers and site administrators with important steps to be completed when creating or maintaining Joomla! website (starting from security on out to SEO and Social Media). This Joomla! Site Checklist allows you not only remember all the important things but helps to do them successfully, giving references to useful resources.</p>\r\n<p><strong>Check the points to be taken into account when creating or maintaining your Joomla! website right now! Make your site successful!</strong></p>\r\n<p style=\"color: #777;\"><small>
			<span style=\"text-decoration: underline;\">How to use:</span> Joomla! Site Check list consists of various sections devoted to development and site maintenance aspects (Security, Accessibility, Analytics, etc.). Each section has its own compulsory or optional steps with references to the detailed description and solutions. Check the points (mark the things you have already done), and the progress bar above will display your success in development and site maintenance. After all the compulsory steps will have been marked, the progress bar will be completed.</small></p>\r\n<p>We understand that this list cannot cover all aspects of development and site maintenance. So, if you know some other great tools or best practices helping Joomla! developers to produce quality websites, please, tell us about them in comments below.</p>', '<p>Do you use other cool tools in Joomla! development and maintenance? Share your experience — we\'ll be glad to extend our checklist.</p>', 1, 0, '2014-05-02', 9, 9, '', '', '{\"og:description\":\"\",\"og:title\":\"Successful Joomla! Site Checklist\",\"twitter:description\":\"\",\"twitter:title\":\"Successful Joomla! Site Checklist\"}', 'en-GB', '2')");
			$db->execute();

			$chkid = $db->insertid();

			//1 group
			$db->setQuery("INSERT INTO `#__checklist_groups` (`id`, `checklist_id`, `title`, `ordering`) VALUES ('', '".$chkid."', 'Best practices', 0)");
			$db->execute();
			$gid = $db->insertid();

			$db->setQuery("INSERT INTO `#__checklist_items` (`id`, `checklist_id`, `group_id`, `task`, `tips`, `optional`, `ordering`, `checked`) VALUES ('', '".$chkid."', '".$gid."', 'Fix broken links', '<p>Broken links are bad both for user experience and SEO. Users can leave your site if they get stuck with a broken link. Use link checkers to find such links and remove them or redirect to correct pages.<br/>\n<a href=\"http://validator.w3.org/checklink\" target=\"_blank\">W3C link checker</a><br/>\n<a href=\"http://iwebtool.com/broken_link_checker\" target=\"_blank\">iWebTool Broken Link Checker</a></p>', 0, 0, 0), ('', '".$chkid."', '".$gid."', 'Spelling and grammar', '<p>Your website will never look professional with spelling and grammar mistakes. Learn best practices to improve your writing.<br/>\n<a href=\"http://en.wikipedia.org/wiki/Capitalization\" target=\"_blank\">Capitalization</a><br/>\n<a href=\"http://en.wikipedia.org/wiki/Writing_style\" target=\"_blank\">Writing Style</a><br/>\n<a href=\"http://en.wikipedia.org/wiki/American_and_British_English_spelling_differences\" target=\"_blank\">Word Variations e.g. UK vs US</a></p>', 0, 2, 0), ('', '".$chkid."', '".$gid."', 'Check website in all browsers', '<p>Imagine you have developed great responsive template that looks awesome in Safari, but what about those who use Firefox, Chrome or even IE? Have you tested on other OS platforms?<br/>\n<a href=\"http://browsershots.org/\" target=\"_blank\">BrowserShots.org</a><br/>\n<a href=\"https://browserling.com/\" target=\"_blank\">Browserling.com</a><br/>\n<a href=\"http://spoon.net/browsers\" target=\"_blank\">Spoon.net</a></p>', 0, 4, 0), ('', '".$chkid."', '".$gid."', 'Redirect \'\'non-www\'\' to \'\'www\'\' subdomain', '<p>You should make sure that all site users land to the same URL whether or not they use www or not. This can be achieved by 301 redirect.<br/>\n<a href=\"http://www.yes-www.org/\" target=\"_blank\">Why use www?</a><br/>\n<a href=\"http://www.webconfs.com/htaccess-redirect-generator.php\" target=\"_blank\">.htaccess Redirect Generator</a></p>', 0, 6, 0)");
				$db->execute();
			
			//2 group
			$db->setQuery("INSERT INTO `#__checklist_groups` (`id`, `checklist_id`, `title`, `ordering`) VALUES ('', '".$chkid."', 'Accessibility', 1)");
			$db->execute();
			$gid = $db->insertid();

			$db->setQuery("INSERT INTO `#__checklist_items` (`id`, `checklist_id`, `group_id`, `task`, `tips`, `optional`, `ordering`, `checked`) VALUES ('', '".$chkid."', '".$gid."', 'Accessibility validation', '<p>Think of those who do not use mouse to browse pages or use screen readerS. How comfortable will they feel on your website?<br/>\n<a href=\"http://accessibility.oit.ncsu.edu/accessibleu/problems.html\" target=\"_blank\">Common problems and solutions</a><br/>\n<a href=\"http://achecker.ca/checker/index.php\" target=\"_blank\">IDI Web Accessibility Checker</a><br/>\n<a href=\"http://www.nvda-project.org/\" target=\"_blank\">Test using a screen reader</a></p>', 0, 0, 0), ('', '".$chkid."', '".$gid."', 'WAI-ARIA Landmarks', '<p>Use special markup to add attributes to elements on the web page to create semantically defined sections.<br/>\n<a href=\"http://accessibility.oit.ncsu.edu/blog/2011/06/30/using-aria-landmarks-a-demonstration/\" target=\"_blank\">Using WAI-ARIA Landmarks</a><br/>\n<a href=\"http://www.alistapart.com/articles/the-accessibility-of-wai-aria/\" target=\"_blank\">Guide to WAI-ARIA</a><br/>\n<a href=\"http://www.punkchip.com/2010/11/aria-basic-findings/\" target=\"_blank\">Practical examples</a></p>', 1, 4, 0), ('', '".$chkid."', '".$gid."', 'Color contrast', '<p>Is it easy to read information on your pages? Check your pages for color contrast.<br/>\n<a href=\"http://www.checkmycolours.com/\" target=\"_blank\">Check color contrast online</a></p>', 0, 2, 0)");
				$db->execute();

			//3 group
			$db->setQuery("INSERT INTO `#__checklist_groups` (`id`, `checklist_id`, `title`, `ordering`) VALUES ('', '".$chkid."', 'Analytics', 2)");
			$db->execute();
			$gid = $db->insertid();

			$db->setQuery("INSERT INTO `#__checklist_items` (`id`, `checklist_id`, `group_id`, `task`, `tips`, `optional`, `ordering`, `checked`) VALUES ('', '".$chkid."', '".$gid."', 'Uptime monitoring', '<p>Add your site to uptime monitors to track whether your site is still alive.<br/>\n<a href=\"http://www.uptimerobot.com/\" target=\"_blank\">Uptime robot</a><br/>\n<a href=\"http://www.gotsitemonitor.com/\" target=\"_blank\">GotSiteMonitor.com</a></p>', 0, 0, 0), ('', '".$chkid."', '".$gid."', 'Traffic analysis', '<p>Add tracking code to learn your audience and try to understand how they communicate with the site to provide them better service.<br/>\n<a href=\"http://www.google.com/analytics\" target=\"_blank\">Google Analytics</a><br/>\n<a href=\"http://statcounter.com/\" target=\"_blank\">StatCounter</a><br/>\n<a href=\"http://clicky.com/\" target=\"_blank\">Clicky</a></p>', 0, 2, 0)");
				$db->execute();

			//4 group
			$db->setQuery("INSERT INTO `#__checklist_groups` (`id`, `checklist_id`, `title`, `ordering`) VALUES ('', '".$chkid."', 'Code quality', 3)");
			$db->execute();
			$gid = $db->insertid();

			$db->setQuery("INSERT INTO `#__checklist_items` (`id`, `checklist_id`, `group_id`, `task`, `tips`, `optional`, `ordering`, `checked`) VALUES ('', '".$chkid."', '".$gid."', 'HTML validation', '<a href=\"http://validator.w3.org/\" target=\"_blank\">W3C HTML validator</a><br/>\n<a href=\"http://watson.addy.com/\" target=\"_blank\">Dr. Watson</a>', 0, 2, 0), ('', '".$chkid."', '".$gid."', 'CSS validation', '<a href=\"http://jigsaw.w3.org/css-validator/\" target=\"_blank\">W3C CSS validator</a>', 0, 0, 0), ('', '".$chkid."', '".$gid."', 'Run CSS Lint', '<a href=\"http://csslint.net/\" target=\"_blank\">Run CSS Lint online</a>', 0, 4, 0), ('', '".$chkid."', '".$gid."', 'Run JSLint/JSHint', '<a href=\"http://jslint.org/\" target=\"_blank\">Run JSLint online</a><br/>\n<a href=\"http://jshint.com/\" target=\"_blank\">Run JSHint online</a>', 0, 6, 0), ('', '".$chkid."', '".$gid."', 'World ready', '<a href=\"http://validator.w3.org/i18n-checker/\" target=\"_blank\">W3C i18n checker</a>', 0, 8, 0), ('', '".$chkid."', '".$gid."', 'Automated testing', '<a href=\"http://www.testomato.com/\" target=\"_blank\">Testomato</a>', 0, 10, 0)");
				$db->execute();

			//5 group
			$db->setQuery("INSERT INTO `#__checklist_groups` (`id`, `checklist_id`, `title`, `ordering`) VALUES ('', '".$chkid."', 'Mobile', 4)");
			$db->execute();
			$gid = $db->insertid();

			$db->setQuery("INSERT INTO `#__checklist_items` (`id`, `checklist_id`, `group_id`, `task`, `tips`, `optional`, `ordering`, `checked`) VALUES ('', '".$chkid."', '".$gid."', 'MobileOK score of 75+', '<p>Number of mobile users is growing each day, so make sure that your website is friendly enough for mobile devices.<br/>\n<a href=\"http://validator.w3.org/mobile/\" target=\"_blank\">W3C mobile checker</a></p>', 0, 0, 0), ('', '".$chkid."', '".$gid."', 'Use \'\'viewport\'\' meta-tag', '<p>Viewport meta-tag and @viewport rule control layout on mobile browsers. Be sure to use them if you need predictabe behavior.<br/>\n<a href=\"http://webdesign.tutsplus.com/tutorials/htmlcss-tutorials/quick-tip-dont-forget-the-viewport-meta-tag/\" target=\"_blank\">Don\'t Forget the Viewport Meta Tag</a><br/>\n<a href=\"http://www.hanselman.com/blog/MakeYourWebsiteMobileAndIPhoneFriendlyAddHomeScreenIPhoneIconsAndAdjustTheViewPort.aspx\" target=\"_blank\">Make your website mobile friendly</a></p>', 0, 2, 0), ('', '".$chkid."', '".$gid."', 'Use correct input types', '<p>Correct field type will result in correct behavior across platforms and devices. E.g. special characters on screen keyboard for email, or digit keyboard for phone number field.<br/>\n<a href=\"http://html5tutorial.info/html5-contact.php\" target=\"_blank\">Input type: Email, Url, Phone</a><br/>\n<a href=\"http://diveintohtml5.info/forms.html\" target=\"_blank\">Diving in to HTML5 forms</a></p>', 0, 4, 0), ('', '".$chkid."', '".$gid."', 'Test layout using emulators', '<p>You do not need to own different types of devices to test your application. Use emulators instead.<br/>\n<a href=\"http://screenqueri.es/\" target=\"_blank\">Online media query tester</a><br/>\n<a href=\"http://www.webdesignerdepot.com/2012/11/6-free-mobile-device-emulators-for-testing-your-site/\" target=\"_blank\">6 free mobile emulators</a><br/>\n<a href=\"http://www.opera.com/developer/tools/mobile/\" target=\"_blank\">Opera Mobile Emulator</a></p>', 0, 6, 0)");
				$db->execute();

			//6 group
			$db->setQuery("INSERT INTO `#__checklist_groups` (`id`, `checklist_id`, `title`, `ordering`) VALUES ('', '".$chkid."', 'Performance', 5)");
			$db->execute();
			$gid = $db->insertid();

			$db->setQuery("INSERT INTO `#__checklist_items` (`id`, `checklist_id`, `group_id`, `task`, `tips`, `optional`, `ordering`, `checked`) VALUES ('', '".$chkid."', '".$gid."', 'Google Page Speed score of 90+', '<p>Google provides a number of rules to optimize your page load. And be sure they use it while ranking your website in search results.<br/>\n<a href=\"https://developers.google.com/speed/pagespeed/\" target=\"blank\">Google Page Speed</a></p>', 0, 0, 0), ('', '".$chkid."', '".$gid."', 'Yahoo YSlow score of 85+', '<p>There is also a number of parameters you may optimize with tool from Yahoo. Unlike Goole they have documentation that shows how score is calculated.<br/>\n<a href=\"http://yslow.org/\" target=\"_blank\">Yahoo\'s YSlow</a></p>', 0, 2, 0), ('', '".$chkid."', '".$gid."', 'Optimize HTTP headers', '<p>Use the service to find out what can you fix in server response headers to optimize page load.<br/>\n<a href=\"http://redbot.org/\" target=\"_blank\">redbot.org</a></p>', 0, 4, 0), ('', '".$chkid."', '".$gid."', 'Optimize images', '<p>Both Google and Yahoo recommend to optimize your images. Use following tools to reduce image size without loosing quality.<br/>\n<a href=\"http://www.smushit.com/ysmush.it/\" target=\"_blank\">SmushIt.com</a><br/>\n<a href=\"http://punypng.com/\" target=\"_blank\">PunyPNG.com</a><br/>\n<a href=\"http://pnggauntlet.com/\" target=\"_blank\">PNGGauntlet for Windows</a><br/>\n<a href=\"http://imageoptim.com/\" target=\"_blank\">Image Optim for Mac</a></p>', 0, 6, 0)");
				$db->execute();

			//7 group
			$db->setQuery("INSERT INTO `#__checklist_groups` (`id`, `checklist_id`, `title`, `ordering`) VALUES ('', '".$chkid."', 'Semantics', 6)");
			$db->execute();
			$gid = $db->insertid();

			$db->setQuery("INSERT INTO `#__checklist_items` (`id`, `checklist_id`, `group_id`, `task`, `tips`, `optional`, `ordering`, `checked`) VALUES ('', '".$chkid."', '".$gid."', 'Add meaning with Microdata', '<p>Markup pages in ways recognized by major search providers to improve the display of search results.<br/>\n<a href=\"http://schema.org/\" target=\"_blank\">Schema.org reference</a><br/>\n<a href=\"http://schema-creator.org/\" target=\"_blank\">Schema-Creator.org</a></p>', 0, 1, 0), ('', '".$chkid."', '".$gid."', 'Check the semantics', '<p>Check what data is extracted from your pages.<br/>\n<a href=\"http://www.w3.org/2003/12/semantic-extractor.html\" target=\"_bank\">W3C semantic extractor</a></p>', 0, 2, 0)");
				$db->execute();

			//8 group
			$db->setQuery("INSERT INTO `#__checklist_groups` (`id`, `checklist_id`, `title`, `ordering`) VALUES ('', '".$chkid."', 'Security', 7)");
			$db->execute();
			$gid = $db->insertid();

			$db->setQuery("INSERT INTO `#__checklist_items` (`id`, `checklist_id`, `group_id`, `task`, `tips`, `optional`, `ordering`, `checked`) VALUES ('', '".$chkid."', '".$gid."', 'Update to the latest Joomla! version', '<p>Make sure you have the latest version of Joomla! If not, update it!<br/>\n<a href=\"http://www.joomla.org/download.html\" target=\"_blank\">Download latest Joomla!</a></p>', 0, 0, 0), ('', '".$chkid."', '".$gid."', 'Follow best practices', '<a href=\"http://docs.joomla.org/Security_Checklist\" target=\"_blank\">Joomla! administrator security checklist</a>', 0, 2, 0), ('', '".$chkid."', '".$gid."', 'Check for known malware', '<p>You may perform periodic checks to be sure that site is clean of malware.<br/>\n<a href=\"http://sitecheck.sucuri.net/scanner/\" target=\"_blank\">Sucuri SiteCheck</a></p>', 0, 4, 0), ('', '".$chkid."', '".$gid."', 'Cross-site request forgery', '<p>When you develop or customize an extension be sure to protect your site from CSRF attacks.<br/>\n<a href=\"http://www.codinghorror.com/blog/2008/09/cross-site-request-forgeries-and-you.html\" target=\"_blank\">Explanation and walkthrough</a></p>', 0, 6, 0), ('', '".$chkid."', '".$gid."', 'Change super admin username', '<p>Create a new user, give it super admin permissions, then delete old super admin user for changing ID of the admin user. Since Joomla! 2.5.5 you may change admin name during installation.<br/>\n<a href=\"http://docs.joomla.org/Why_should_you_immediately_change_the_name_of_the_default_admin_user%3F\" target=\"_blank\">Joomla! directions</a><br/>\n<a href=\"http://docs.joomla.org/How_do_you_recover_or_reset_your_admin_password%3F\" target=\"_blank\">Recover super admin password</a></p>', 0, 8, 0), ('', '".$chkid."', '".$gid."', 'Set strong super admin password', '<p>Do not use easy-to-guess passwords. But make them simple to remember.<br/>\n<a href=\"http://eksith.wordpress.com/2008/03/04/ultra-secure-passwords/\" target=\"_blank\">How to make mnemonic password</a><br/>\n<a href=\"http://eksith.com/experiments/passwordencoder/\" target=\"_blank\">Generate mnemonic password</a></p>', 0, 10, 0), ('', '".$chkid."', '".$gid."', 'Rename htaccess.txt file', '<p>Rename htaccess.txt to .htaccess and make sure RewriteEngine is set to \"On\".</p>', 0, 12, 0), ('', '".$chkid."', '".$gid."', 'Block access to admin panel', '<p>Do not let automatic scanners find out that you use Joomla by hidding or protecting /administrator/ folder.<br/>\n<a href=\"http://www.addedbytes.com/blog/code/password-protect-a-directory-with-htaccess/\" target=\"_blank\">Protect a directory with .htaccess</a><br/>\n<a href=\"http://extensions.joomla.org/extensions/access-a-security/site-security/login-protection/12271\" target=\"_blank\">kSecure plugin</a><br/>\n<a href=\"http://extensions.joomla.org/extensions/access-a-security/site-access/backend-a-full-access-control/18288\" target=\"_blank\">JLSecure My Site plugin</a></p>', 0, 14, 0), ('', '".$chkid."', '".$gid."', 'Use advanced .htaccess rules', '<p>To block most common attacks it is suggested to establish advanced security using .htaccess file.<br/>\n<a href=\"http://docs.joomla.org/Htaccess_examples_(security)\" target=\"_blank\">Suggested master .htaccess file</a><br/>\n<a href=\"http://www.htaccessredirect.net/\" target=\"_blank\">.htaccess redirect generator</a></p>', 0, 16, 0), ('', '".$chkid."', '".$gid."', 'Set permissions for configuration.php', '<p>Check configuration.php file permissions. Set it to 644, or much better to 444.</p>', 0, 18, 0), ('', '".$chkid."', '".$gid."', 'Change jos_ prefix', '<p>If you still on Joomla 1.5.x, do not use the standard jos_ table prefix.</p>', 0, 20, 0), ('', '".$chkid."', '".$gid."', 'Remove \'\'generator\'\' meta-tag', '<p>Do not promote that website is built on Joomla! and its version for security reasons.<br/>\n<a href=\"http://www.ijoomla.com/blog/remove-joomla-meta-name-generator-plugin/\" target=\"_blank\">Remove Joomla generator tag</a></p>', 0, 22, 0), ('', '".$chkid."', '".$gid."', 'Modify \'\'log\'\' and \'\'tmp\'\' paths', '<p>If you go live with the site being established on development environment, a good idea would be to check /log and /tmp folders paths in \"configuration.php\"</p>', 0, 24, 0), ('', '".$chkid."', '".$gid."', 'Delete useless extensions', '<p>You do not use them and do not update them. So they are potentially dangerous.<br/>\n<a href=\"http://docs.joomla.org/Vulnerable_Extensions_List\" target=\"_blank\">Vulnerable extensions list</a></p>', 0, 26, 0), ('', '".$chkid."', '".$gid."', 'Remove useless files', '<p>Look in your website structure for staging/development files and delete them (.psd, .bak, IDE files, copies etc.)</p>', 0, 28, 0), ('', '".$chkid."', '".$gid."', 'Secure connection (SSL)', '<a href=\"http://www.digicert.com/ssl-certificate-installation-apache.htm\" target=\"_blank\">Setup SSL on Apache</a><br/>\n<a href=\"http://certlogik.com/ssl-checker/\" target=\"_blank\">Online SSL checker</a><br/>\n<a href=\"http://www.html5rocks.com/en/tutorials/security/content-security-policy/\" target=\"_blank\">Content Security Policy</a>', 1, 30, 0)");
				$db->execute();

			//9 group
			$db->setQuery("INSERT INTO `#__checklist_groups` (`id`, `checklist_id`, `title`, `ordering`) VALUES ('', '".$chkid."', 'SEO', 8)");
			$db->execute();
			$gid = $db->insertid();

			$db->setQuery("INSERT INTO `#__checklist_items` (`id`, `checklist_id`, `group_id`, `task`, `tips`, `optional`, `ordering`, `checked`) VALUES ('', '".$chkid."', '".$gid."', 'robots.txt', '<p>Show search crawlers what content should be indexed and what should be removed from index.<br/>\n<a href=\"http://tools.seobook.com/robots-txt/\" target=\"_blank\">robots.txt tutorial</a><br/>\n<a href=\"http://www.robotsgenerator.com/\" target=\"_blank\">Create robots.txt online</a></p>', 0, 0, 0), ('', '".$chkid."', '".$gid."', 'XML sitemap', '<a href=\"http://www.xml-sitemaps.com/\" target=\"_blank\">Create sitemap online</a>', 0, 2, 0), ('', '".$chkid."', '".$gid."', 'Proofread content', '<p>Meta data (title, description) should be unique for each page. Use text formatting tags (h1, h2, h3; ul, li, etc). Include keywords into texts – unique for each page if possible. Use images with unique names and fill in alt tags.<br/>\n<a href=\"http://searchenginewatch.com/article/2067564/How-To-Use-HTML-Meta-Tags\" target=\"_blank\">How to use HTML meta tags</a><br/>\n<a href=\"http://www.tutorialspoint.com/html/html_formatting.htm\" target=\"_blank\">HTML Formatting Tags</a><br/>\n<a href=\"http://en.wikipedia.org/wiki/Alt_attribute\" target=\"_blank\">Alt attribute</a></p>', 0, 4, 0), ('', '".$chkid."', '".$gid."', 'Google Rich Snippets', '<p>Once your page is correctly marked up, Rich Snippets will be displayed in Google search results.<br/>\n<a href=\"http://www.google.com/webmasters/tools/richsnippets\" target=\"_blank\">Online tester</a><br/>\n<a href=\"http://schema.org/docs/gs.html\" target=\"_blank\">Getting started</a><br/>\n<a href=\"https://plus.google.com/authorship\" target=\"_blank\">Add authorship</a></p>', 0, 6, 0), ('', '".$chkid."', '".$gid."', 'SenSEO score of 85+', '<p>Check most important SEO criteria and calculate your grade for every page of website.<br/>\n<a href=\"https://addons.mozilla.org/en-US/firefox/addon/senseo/\" target=\"_blank\">SenSEO for Firefox</a></p>', 1, 8, 0), ('', '".$chkid."', '".$gid."', 'Add humans.txt', '<a href=\"http://humanstxt.org/\" target=\"_blank\">We are humans, not machines!</a>', 1, 10, 0)");
				$db->execute();

			//10 group
			$db->setQuery("INSERT INTO `#__checklist_groups` (`id`, `checklist_id`, `title`, `ordering`) VALUES ('', '".$chkid."', 'Social Media', 9)");
			$db->execute();
			$gid = $db->insertid();

			$db->setQuery("INSERT INTO `#__checklist_items` (`id`, `checklist_id`, `group_id`, `task`, `tips`, `optional`, `ordering`, `checked`) VALUES ('', '".$chkid."', '".$gid."', 'Open Graph protocol', '<a href=\"http://ogp.me/\" target=\"_blank\">Open Graph protocol reference</a>', 0, 1, 0), ('', '".$chkid."', '".$gid."', 'Facebook Insights', '<a href=\"https://developers.facebook.com/docs/insights/\" target=\"_blank\">Facebook Insights</a>', 0, 2, 0), ('', '".$chkid."', '".$gid."', 'Twitter Cards', '<a href=\"https://dev.twitter.com/docs/cards\" target=\"_blank\">Documentation</a><br/>\n<a href=\"https://dev.twitter.com/docs/cards/preview\" target=\"_blank\">Preview Tool</a>', 1, 3, 0)");
				$db->execute();

			//11 group
			$db->setQuery("INSERT INTO `#__checklist_groups` (`id`, `checklist_id`, `title`, `ordering`) VALUES ('', '".$chkid."', 'Usability', 10)");
			$db->execute();
			$gid = $db->insertid();

			$db->setQuery("INSERT INTO `#__checklist_items` (`id`, `checklist_id`, `group_id`, `task`, `tips`, `optional`, `ordering`, `checked`) VALUES ('', '".$chkid."', '".$gid."', 'HTML5 compatibility check', '<a href=\"http://ie.microsoft.com/testdrive/HTML5/CompatInspector/\" target=\"_blank\">Compat Inspector</a><br/>\n<a href=\"http://www.modern.ie/report\" target=\"_blank\">modern.IE</a><br/>\n<a href=\"http://modernizr.com/\" target=\"_blank\">Modernizr JS library</a>', 0, 0, 0), ('', '".$chkid."', '".$gid."', 'Custom 404 page', '<p>Make use of 404 page by offering useful links and site structure.<br/>\n<a href=\"http://www.alistapart.com/articles/amoreuseful404/\" target=\"_blank\">A more useful 404</a></p>', 0, 2, 0), ('', '".$chkid."', '".$gid."', 'Favicon', '<p>Nowadays favicon is widely used, so be sure to change default Joomla!/template icon to your own one.<br/>\n<a href=\"http://www.xiconeditor.com/\" target=\"_blank\">Online generator</a><br/>\n<a href=\"http://www.thesitewizard.com/archive/favicon.shtml\" target=\"_blank\">How to properly link a favicon</a><br/>\n<a href=\"http://www.jonathantneal.com/blog/understand-the-favicon/\" target=\"_blank\">Understand favicons</a></p>', 0, 4, 0), ('', '".$chkid."', '".$gid."', 'Use friendly URLs', '<p>Use SEF extension to get human-readable URLs instead of address line with parameters.<br/>\n<a href=\"http://www.seomoz.org/blog/11-best-practices-for-urls\" target=\"_blank\">11 best practices for URLs</a><br/>\n<a href=\"http://httpd.apache.org/docs/current/mod/mod_rewrite.html\" target=\"_blank\">URL rewrite in Apache</a></p>', 0, 6, 0), ('', '".$chkid."', '".$gid."', 'Add search feature', '<p>Power up your site search with specialized services.<br/>\n<a href=\"http://www.google.com/cse/\" target=\"_blank\">Google Custom Search</a><br/>\n<a href=\"http://www.opensearch.org/Home\" target=\"_blank\">Consider \'\'Open Search\'\'</a></p>', 1, 8, 0), ('', '".$chkid."', '".$gid."', 'Environment Integration', '<p>Use browser/OS specific features to make your website look nice anywhere.<br/>\n<a href=\"http://developer.apple.com/library/ios/#documentation/AppleApplications/Reference/SafariWebContent/ConfiguringWebApplications/ConfiguringWebApplications.html\" target=\"_blank\">Safari on iOS</a><br/>\n<a href=\"http://msdn.microsoft.com/en-us/library/ie/hh781490%28v=vs.85%29.aspx\" target=\"_blank\">Internet Explorer on Windows</a><br/>\n<a href=\"http://www.buildmypinnedsite.com/\" target=\"_blank\">Windows 8 Tiles</a></p>', 1, 10, 0)");
				$db->execute();


			//add tags
			$db->setQuery("INSERT INTO `#__checklist_tags` (`id`, `name`, `default`, `slug`) VALUES ('', 'joomla', 0, '')");
			$db->execute();

			$tag_id = $db->insertid();

			$db->setQuery("INSERT INTO `#__checklist_list_tags` (`id`, `checklist_id`, `tag_id`, `isnew`) VALUES ('', '".$chkid."', '".$tag_id."', 1)");
			$db->execute();

		}

		if($post['sampledata'] == 'sample2'){

			$db->setQuery("INSERT INTO `#__checklist_lists` (`id`, `user_id`, `title`, `alias`, `description_before`, `description_after`, `default`, `published`, `publish_date`, `list_access`, `comment_access`, `meta_keywords`, `meta_description`, `custom_metatags`, `language`, `template`) VALUES ('', 0, 'Wedding Checklist', 'wedding-checklist', '<p> </p>\n<p lang=\"en-US\" style=\"margin-left: 0pt; margin-right: 0pt; margin-top: 0pt; margin-bottom: 10pt; font-family: Calibri; font-size: 11.0pt;\">Wedding day is one of the most important days in every woman’s life and it is vitally important to have everything well-prepared.<span style=\"mso-spacerun: yes;\">  </span>A wedding checklist helps you not to miss a thing and have everything ready for <span style=\"background: #FFC000;\">THE BIG DAY</span>.</p>\n<p lang=\"en-US\" style=\"margin-left: 0pt; margin-right: 0pt; margin-top: 0pt; margin-bottom: 10pt; font-family: Calibri; font-size: 11.0pt;\">Are you a wedding planner, a wedding salon owner or a wedding blogger? Embed Checklist software into your website and add  phones and emails of  business partners (flower designers, cake makers, bands, caterers or stylists) as checkbox tips. Make your wedding business grow!</p>\n<p> </p>', '', 1, 1, '2014-05-03', 9, 9, '', '', '{\"og:description\":\"\\u00a0Wedding day is one of the most important days in every woman\\u2019s li\",\"og:title\":\"Wedding Checklist\",\"twitter:description\":\"\\u00a0Wedding day is one of the most important days in every woman\\u2019s li\",\"twitter:title\":\"Wedding Checklist\"}', 'en-GB', '2')");
			$db->execute();

			$chkid = $db->insertid();

			//1 group
			$db->setQuery("INSERT INTO `#__checklist_groups` (`id`, `checklist_id`, `title`, `ordering`) VALUES ('', '".$chkid."', '12 -10 months before the wedding', 0)");
			$db->execute();
			$gid = $db->insertid();

			$db->setQuery("INSERT INTO `#__checklist_items` (`id`, `checklist_id`, `group_id`, `task`, `tips`, `optional`, `ordering`, `checked`) VALUES ('', '".$chkid."', '".$gid."', 'Fall in Love', '<p>Fall in Love</p>', 0, 0, 0), ('', '".$chkid."', '".$gid."', 'Choose a wedding style and theme', '<p>Choose colors & a wedding style</p>', 0, 6, 0), ('', '".$chkid."', '".$gid."', 'Make a guest list', '<p>Prepare a list of potential guests</p>', 0, 8, 0), ('', '".$chkid."', '".$gid."', 'Define a wedding budget', '<p>Wedding is an expensive event. You need to discuss with your partner your financial possibilities and plan the ceremony in line with them</p>', 0, 4, 0), ('', '".$chkid."', '".$gid."', 'Decide on a wedding date & time', '<p>Wedding date needs to be determined in advance as all future preparations will be organized depending on the date and time of the wedding</p>', 0, 2, 0), ('', '".$chkid."', '".$gid."', 'Search for available venues', '<p>The choice should be based on the ceremony date, budget and personal preferences</p>', 0, 10, 0), ('', '".$chkid."', '".$gid."', 'Choose a place for the ceremony and reception', '<p>Both you and your partner are supposed to like the place</p>', 0, 12, 0), ('', '".$chkid."', '".$gid."', 'Hire a wedding planner, if desired', '<p>Hiring a professional will considerably simplify the process of the wedding preparation</p>', 0, 14, 0), ('', '".$chkid."', '".$gid."', 'Start reading wedding, food, design magazines and blogs', '<p>A great source to find inspiration for the upcoming event</p>', 0, 16, 0)");
				$db->execute();

			//2 group
			$db->setQuery("INSERT INTO `#__checklist_groups` (`id`, `checklist_id`, `title`, `ordering`) VALUES ('', '".$chkid."', '9-7 months before the wedding', 1)");
			$db->execute();
			$gid = $db->insertid();

			$db->setQuery("INSERT INTO `#__checklist_items` (`id`, `checklist_id`, `group_id`, `task`, `tips`, `optional`, `ordering`, `checked`) VALUES ('', '".$chkid."', '".$gid."', 'Start searching for a photographer, florist, band and caterers', '<p>Good specialists are often booked months in advance that is why you had better take care of finding them 9-7 months before the wedding day</p>', 0, 1, 0), ('', '".$chkid."', '".$gid."', 'Choose maid of honor, best man, bridesmaids, a ring bearer & flower girl', '<p>The wedding will be incomplete without above mentioned participants</p>', 0, 2, 0), ('', '".$chkid."', '".$gid."', 'Discuss honeymoon options with your partner', '<p>Choose a destination and start preparing documents and making reservations</p>', 0, 3, 0), ('', '".$chkid."', '".$gid."', 'Find a cake maker', '<p>Remember to taste the cakes before approving the master''s candidacy</p>', 0, 4, 0), ('', '".$chkid."', '".$gid."', 'Book your wedding transportation', '<p>To get your wedding on time and in style with your wedding party</p>', 0, 5, 0), ('', '".$chkid."', '".$gid."', 'Book rental equipment', '<p>Tables, chairs, linens, arches, silk plants, sound system &amp; glassware</p>', 0, 6, 0), ('', '".$chkid."', '".$gid."', 'Start looking for a wedding dress and accessories', '<p>Find the dress you will be comfortable in</p>', 0, 7, 0), ('', '".$chkid."', '".$gid."', 'Get into shape for a wedding', '<p>Join gym or fitness classes</p>', 0, 8, 0)");
				$db->execute();

			//3 group
			$db->setQuery("INSERT INTO `#__checklist_groups` (`id`, `checklist_id`, `title`, `ordering`) VALUES ('', '".$chkid."', '6-5 months before the wedding', 2)");
			$db->execute();
			$gid = $db->insertid();

			$db->setQuery("INSERT INTO `#__checklist_items` (`id`, `checklist_id`, `group_id`, `task`, `tips`, `optional`, `ordering`, `checked`) VALUES ('', '".$chkid."', '".$gid."', 'Make invitations', '<p>If you don’t want to spend a lot of money on invitations, you can make your own handmade cards</p>', 0, 1, 0), ('', '".$chkid."', '".$gid."', 'Choose and buy wedding rings', '<p>Be sure that the rings match the bands</p>', 0, 2, 0), ('', '".$chkid."', '".$gid."', 'Buy a wedding dress and accessories', '<p>After a careful search, choose the dress and accessories including a veil, jewelry, bouquet, shoes & etc.</p>', 0, 3, 0), ('', '".$chkid."', '".$gid."', 'Choose venue decorations', '<p> the decorations are supposed to create a special mood and atmosphere</p>', 0, 4, 0), ('', '".$chkid."', '".$gid."', 'Choose a wedding photographer', '<p>remember to discuss your wishes</p>', 0, 5, 0), ('', '".$chkid."', '".$gid."', 'Choose a band and discuss music preferences', '<p>the type of music you choose will set the tone for your wedding</p>', 0, 6, 0)");
				$db->execute();

			//4 group
			$db->setQuery("INSERT INTO `#__checklist_groups` (`id`, `checklist_id`, `title`, `ordering`) VALUES ('', '".$chkid."', '4-3 months before the wedding', 3)");
			$db->execute();
			$gid = $db->insertid();

			$db->setQuery("INSERT INTO `#__checklist_items` (`id`, `checklist_id`, `group_id`, `task`, `tips`, `optional`, `ordering`, `checked`) VALUES ('', '".$chkid."', '".$gid."', 'Order a wedding cake', '<p>The cake is supposed to look wonderfully and taste great</p>', 0, 1, 0), ('', '".$chkid."', '".$gid."', 'Start writing wedding vows', '<p>Vows is a great means of making your wedding personalized and showing the guests how much you love each other</p>', 0, 2, 0), ('', '".$chkid."', '".$gid."', 'Send wedding invitations', '<p>The invitations should be send in advance to give the guests a chance to clear their schedule</p>', 0, 3, 0), ('', '".$chkid."', '".$gid."', 'Meet with the maid of honor, best man, bridesmaids, ring bearer & flower girl', '<p>To discuss their duties and organize wedding procession</p>', 0, 4, 0), ('', '".$chkid."', '".$gid."', 'Plan the wedding shower', '<p>Pick up the date, a theme, compile a guest list, choose a location</p>', 0, 5, 0), ('', '".$chkid."', '".$gid."', 'Take dancing lessons', '<p>Make your first dance memorable</p>', 0, 6, 0), ('', '".$chkid."', '".$gid."', 'Shop for bridesmaids’ dresses', '<p>Think about the color, style and different sizes</p>', 0, 7, 0)");
				$db->execute();

			//5 group
			$db->setQuery("INSERT INTO `#__checklist_groups` (`id`, `checklist_id`, `title`, `ordering`) VALUES ('', '".$chkid."', '2-1 months before the wedding', 4)");
			$db->execute();
			$gid = $db->insertid();

			$db->setQuery("INSERT INTO `#__checklist_items` (`id`, `checklist_id`, `group_id`, `task`, `tips`, `optional`, `ordering`, `checked`) VALUES ('', '".$chkid."', '".$gid."', 'Finalize the list of guests', '<p>Exclude those who cannot attend the wedding ceremony</p>', 0, 0, 0), ('', '".$chkid."', '".$gid."', 'Create a gift registry', '<p>Wedding registry  prevents gifts’ duplicating</p>', 0, 2, 0), ('', '".$chkid."', '".$gid."', 'Prepare thank you notes for the gifts you receive on a wedding day', '<p>A must-do for every wedding gift you receive</p>', 0, 4, 0), ('', '".$chkid."', '".$gid."', 'Choose a song for your first dance as wife and husband', '<p>Choose the song that is unique, meaningful and reflects your \"personality\" as a couple</p>', 0, 6, 0), ('', '".$chkid."', '".$gid."', 'Finalize all wedding and honeymoon arrangements', '<p>Make sure everything goes according to the plan</p>', 0, 8, 0), ('', '".$chkid."', '".$gid."', 'Schedule hair & make up stylists appointments', '<p>Find the best wedding hair stylist and a wedding makeup artist in your area</p>', 0, 10, 0), ('', '".$chkid."', '".$gid."', 'Create a seating chart', '<p>A good seating chart will assure the reception goes smoothly</p>', 0, 12, 0), ('', '".$chkid."', '".$gid."', 'Have something old, new, borrowed and blue', '<p>In case you follow the traditions</p>', 0, 14, 0), ('', '".$chkid."', '".$gid."', 'Have a final dress fitting', '<p>Have a t least 2 fittings to make sure the dress fits perfectly</p>', 0, 16, 0), ('', '".$chkid."', '".$gid."', 'Have a wedding shower', '<p>Share joy with your friends</p>', 0, 18, 0), ('', '".$chkid."', '".$gid."', 'Get a marriage license', '<p>The document confirms that you are legally allowed to get married</p>', 0, 20, 0)");
				$db->execute();

			//6 group
			$db->setQuery("INSERT INTO `#__checklist_groups` (`id`, `checklist_id`, `title`, `ordering`) VALUES ('', '".$chkid."', ' A few days before the wedding', 5)");
			$db->execute();
			$gid = $db->insertid();

			$db->setQuery("INSERT INTO `#__checklist_items` (`id`, `checklist_id`, `group_id`, `task`, `tips`, `optional`, `ordering`, `checked`) VALUES ('', '".$chkid."', '".$gid."', 'Have a rehearsal dinner', '<p>A rehearsal dinner is a great way for the guest to know each other</p>', 0, 0, 0), ('', '".$chkid."', '".$gid."', 'Have bachelor, bachelorette parties', '<p>Hang out with your friends and celebrate the  upcoming marriage</p>', 0, 2, 0), ('', '".$chkid."', '".$gid."', 'Pack for the honeymoon', '<p>Pack the documents, money and necessary clothing</p>', 0, 4, 0), ('', '".$chkid."', '".$gid."', 'Make a manicure & pedicure', '<p>Everything is supposed to be perfect on your wedding day</p>', 0, 6, 0), ('', '".$chkid."', '".$gid."', 'Confirm all made reservations, including honeymoon bookings', '<p>Not to have misunderstandings when you arrive</p>', 0, 8, 0), ('', '".$chkid."', '".$gid."', 'Finalize vows', '<p>Make sure they are emotional and personal</p>', 0, 10, 0), ('', '".$chkid."', '".$gid."', 'Go to bed early', '<p>You need to look great on your wedding day</p>', 0, 12, 0)");
				$db->execute();

			//7 group
			$db->setQuery("INSERT INTO `#__checklist_groups` (`id`, `checklist_id`, `title`, `ordering`) VALUES ('', '".$chkid."', 'At the wedding day', 6)");
			$db->execute();
			$gid = $db->insertid();

			$db->setQuery("INSERT INTO `#__checklist_items` (`id`, `checklist_id`, `group_id`, `task`, `tips`, `optional`, `ordering`, `checked`) VALUES ('', '".$chkid."', '".$gid."', 'Have your hair and make-up done', '<p>Make sure that the stylist know the appointment time</p>', 0, 1, 0), ('', '".$chkid."', '".$gid."', 'Get dressed', '<p>Get yourself at least 45 minutes to put the dress on</p>', 0, 2, 0), ('', '".$chkid."', '".$gid."', 'Take a deep breath and remember that it is one of the most memorable days in your life', '<p>No Comments</p>', 0, 3, 0)");
				$db->execute();

			//add tags
			$db->setQuery("INSERT INTO `#__checklist_tags` (`id`, `name`, `default`, `slug`) VALUES ('', 'wedding', 0, '')");
			$db->execute();

			$tag_id = $db->insertid();

			$db->setQuery("INSERT INTO `#__checklist_list_tags` (`id`, `checklist_id`, `tag_id`, `isnew`) VALUES ('', '".$chkid."', '".$tag_id."', 1)");
			$db->execute();

		}

		if($post['sampledata'] == 'sample3'){

			$db->setQuery("INSERT INTO `#__checklist_lists` (`id`, `user_id`, `title`, `alias`, `description_before`, `description_after`, `default`, `published`, `publish_date`, `list_access`, `comment_access`, `meta_keywords`, `meta_description`, `custom_metatags`, `language`, `template`) VALUES ('', 0, 'Social Media Marketing Campaign Checklist', 'social-media-marketing-campaign-checklist', '<p> </p>\n<p lang=\"en-US\" style=\"margin-left: 0pt; margin-right: 0pt; margin-top: 0pt; margin-bottom: 10pt; font-family: Calibri; font-size: 11.0pt;\">Social Medias are known as a cost effective means of driving traffic to the website and engaging with the audience. A lot of website owners pay particular attention to social media marketing (SMM) to manage social accounts with the best possible results. Checklist maker is a suitable tool for creating and managing SMM campaigns effectively. Create a checklist with consecutive steps and see results!</p>\n<p> </p>', '', 1, 1, '2014-05-05', 9, 2, '', '', '{\"og:description\":\"u00a0Social Medias are known as a cost effective means of driving traffi\",\"og:title\":\"Social Media Marketing Campaign Checklist\",\"twitter:description\":\"u00a0Social Medias are known as a cost effective means of driving traffi\",\"twitter:title\":\"Social Media Marketing Campaign Checklist\"}', 'en-GB', '2')");
			$db->execute();

			$chkid = $db->insertid();

			//1 group
			$db->setQuery("INSERT INTO `#__checklist_groups` (`id`, `checklist_id`, `title`, `ordering`) VALUES ('', '".$chkid."', 'SMM Checklist', 0)");
			$db->execute();
			$gid = $db->insertid();

			$db->setQuery("INSERT INTO `#__checklist_items` (`id`, `checklist_id`, `group_id`, `task`, `tips`, `optional`, `ordering`, `checked`) VALUES ('', '".$chkid."', '".$gid."', 'Create a plan', '<p>The plan is necessary to save time, money and efforts</p>', 0, 0, 0), ('', '".$chkid."', '".$gid."', 'Define goals', '<p>PR, traffic, sales, brand awareness</p>', 0, 2, 0), ('', '".$chkid."', '".$gid."', 'Know your audience', '<p>General preferences, preferred social networks, interests</p>', 0, 4, 0), ('', '".$chkid."', '".$gid."', 'Analyze competitors', '<p>50 Top tools for Social media Monitoring <a href=\"http://socialmediatoday.com/pamdyer/1458746/50-top-tools-social-media-monitoring-analytics-and-management-2013\" target=\"_blank\">socialmediatoday.com</a></p>', 0, 6, 0), ('', '".$chkid."', '".$gid."', 'Set up Social Media accounts', '<p>Facebook, Twitter, Google+, LinkedIn, Pinterest, StumbleUpon, YouTube, SlideShare etc. depending on where your clients are</p>', 0, 8, 0), ('', '".$chkid."', '".$gid."', 'Add Social buttons to your website or blog', '<p>If you have Joomla! you can find available modules in JED directory <a href=\"http://extensions.joomla.org/extensions/social-web/social-share/social-multi-share\" target=\"_blank\">extensions.joomla.org</a></p>', 0, 10, 0), ('', '".$chkid."', '".$gid."', 'Place social media buttons inside emails', '<p>To encourage sharing</p>', 0, 12, 0), ('', '".$chkid."', '".$gid."', 'Get employees participating', '<p>Ask employees \"to like\", \"to follow\", comment and recommend</p>', 0, 14, 0), ('', '".$chkid."', '".$gid."', 'Ask friends to like the page', '<p>To expand your reach across social platforms</p>', 0, 16, 0), ('', '".$chkid."', '".$gid."', 'Write qualitative content', '<p>5 habits of successful content marketers <a href=\"http://www.socialmediaexaminer.com/5-habits-of-successful-content-marketers-new-research/\" target=\"_blank\">socialmediaexaminer.com</a></p>', 0, 18, 0), ('', '".$chkid."', '".$gid."', 'Write “Great Headlines”', '<p>How to create headlines that go viral with Social Media <a href=\"http://www.socialmediaexaminer.com/how-to-create-headlines-that-go-viral-with-social-media/\" target=\"_blank\">socialmediaexaminer.com</a></p>', 0, 20, 0), ('', '".$chkid."', '".$gid."', 'Make sharing easy', '<p>Embed social media buttons and optimize the content</p>', 0, 22, 0), ('', '".$chkid."', '".$gid."', 'Think visually (include images)', '<p>How to crate visual social contact <a href=\"http://www.socialmediaexaminer.com/visual-content-for-social-media/\" target=\"_blank\">socialmediaexaminer.com</a></p>', 0, 24, 0), ('', '".$chkid."', '".$gid."', 'Share content via multiple social networks every time a new post appears', '<p>To expand your reach across various platforms</p>', 0, 26, 0), ('', '".$chkid."', '".$gid."', 'Use Facebook, Twitter, LinkedIn, StumbleUpon advertisements', '<p>How to use Facebook Ads <a href=\"http://www.socialmediaexaminer.com/how-to-use-facebook-ads-an-introduction/\" target=\"_blank\">socialmediaexaminer.com</a></p>', 0, 28, 0), ('', '".$chkid."', '".$gid."', 'Use short provocative tweets', '<p>To evoke interest and encourage sharing</p>', 0, 30, 0), ('', '".$chkid."', '".$gid."', 'Use hashtags', '<p>5 hashtag tracking tools for Twitter, Facebook and beyond <a href=\"http://www.socialmediaexaminer.com/hashtag-tracking/\" target=\"_blank\">socialmediaexaminer.com</a></p>', 0, 32, 0), ('', '".$chkid."', '".$gid."', 'Run polls, surveys, competitions', '<p>To increase users\' engagement</p>', 0, 34, 0), ('', '".$chkid."', '".$gid."', 'Offer specials for followers', '<p>To increase loyalty</p>', 0, 36, 0), ('', '".$chkid."', '".$gid."', 'Respond to comments', '<p>To engage with followers</p>', 0, 38, 0), ('', '".$chkid."', '".$gid."', 'Participate in LinkedIn groups', '<p>5 steps to building quality LinkedIn connections <a href=\"http://www.socialmediaexaminer.com/5-steps-building-network-quality-connections-linkedin/\" target=\"_blank\">socialmediaexaminer.com</a></p>', 0, 40, 0), ('', '".$chkid."', '".$gid."', 'Join conversations with industry leaders and share their content', '<p>LinkedIn, Google+ groups</p>', 0, 42, 0), ('', '".$chkid."', '".$gid."', 'Build contacts', '<p>Participate in discussions, share your expertise</p>', 0, 44, 0), ('', '".$chkid."', '".$gid."', 'Forum/blog commenting', '<p>Find relevant blogs and forums that are updated on a regular basis</p>', 0, 46, 0), ('', '".$chkid."', '".$gid."', 'Submit  blog posts to Social bookmarking sites', '<p>StumbleUpon, Reddit, Delicious  etc.</p>', 0, 48, 0), ('', '".$chkid."', '".$gid."', 'Engage with your audience', '<p>Run pools, answer to comments, participate in discussions</p>', 0, 50, 0), ('', '".$chkid."', '".$gid."', 'Diversify the content you share: articles, video, infographics', '<p>5 habits of successful content marketers <a href=\"http://www.socialmediaexaminer.com/5-habits-of-successful-content-marketers-new-research/\" target=\"_blank\">socialmediaexaminer.com</a></p>', 0, 52, 0), ('', '".$chkid."', '".$gid."', 'Optimize the content', '<p>To encourage sharing and commenting</p>', 0, 54, 0), ('', '".$chkid."', '".$gid."', 'Specify OG parameters', '<p>What is Open Graph and how to integarte it easily into Joomla <a href=\"http://www.joomplace.com/blog/what-is-open-graph-and-how-to-integrate-it-easily-into-joomla.html\" target=\"_blank\">joomplace.com</a></p>', 0, 56, 0), ('', '".$chkid."', '".$gid."', 'Specify post’s Meta Title & Description', '<p>SEO tactics</p>', 0, 58, 0), ('', '".$chkid."', '".$gid."', 'Constantly measure & monitor marketing results', '<p>No Comments</p>', 0, 60, 0), ('', '".$chkid."', '".$gid."', 'Modify the strategy', '<p>No Comments</p>', 0, 62, 0)");
				$db->execute();

			//add tags
			$db->setQuery("INSERT INTO `#__checklist_tags` (`id`, `name`, `default`, `slug`) VALUES ('', 'social', 0, '')");
			$db->execute();

			$tag_id = $db->insertid();

			$db->setQuery("INSERT INTO `#__checklist_list_tags` (`id`, `checklist_id`, `tag_id`, `isnew`) VALUES ('', '".$chkid."', '".$tag_id."', 1)");
			$db->execute();

			$db->setQuery("INSERT INTO `#__checklist_tags` (`id`, `name`, `default`, `slug`) VALUES ('', 'media', 0, '')");
			$db->execute();

			$tag_id = $db->insertid();

			$db->setQuery("INSERT INTO `#__checklist_list_tags` (`id`, `checklist_id`, `tag_id`, `isnew`) VALUES ('', '".$chkid."', '".$tag_id."', 1)");
			$db->execute();

		}
	}

	public function getForm($data = array(), $loadData = true)
	{
		$app = JFactory::getApplication();
		
		$form = $this->loadForm('com_checklist.sampledata', 'sampledata', array('control' => 'jform', 'load_data' => $loadData));
		
		if (empty($form)) return false;
		
		return $form;
	}
	//----------------------------------------------------------------------------------------------------
	protected function loadFormData()
	{
		$data = JFactory::getApplication()->getUserState('com_checklist.edit.sampledata.data', array());
		
		if (empty($data))
		{
			$data = $this->getItem();
		}
		
		return $data;
	}
}