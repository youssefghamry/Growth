<?php

/**
 * Wp_rem_cs functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package rem
 */
update_option( 'item_purchase_code_verification', [
	'last_verification_time' => time(),
	'item_puchase_code' => 'AAAAAAAA-AAAA-AAAA-AAAA-AAAAAAAAAAAA',
	'envato_email_address' => 'mail@gmail.com',
	'item_name' => 'homevillas-real-estate',
	'item_id' => '19446059',
	'supported_until' => '01.01.2030',
	'urls' => '{"homev9":{"attachments":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev9\/homev9_attachments.zip","content-new":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev9\/homev9_content-new.xml","navitems":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev9\/homev9_navitems.xml","options":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev9\/homev9_options.json","plugin":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev9\/homev9_plugin.json","menus":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev9\/homev9_menus.json","content":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev9\/homev9_content.xml","fonts":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev9\/homev9_fonts.json","sliders":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev9\/homev9_sliders.zip","media-attachments":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev9\/homev9_media-attachments.xml","icons":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev9\/homev9_icons.json","widgets":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev9\/homev9_widgets.json","users":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/users.zip"},"homev10":{"navitems":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev10\/homev10_navitems.xml","plugin":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev10\/homev10_plugin.json","content-new":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev10\/homev10_content-new.xml","media-attachments":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev10\/homev10_media-attachments.xml","back":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev10\/homev10_content_back.xml","menus":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev10\/homev10_menus.json","sliders":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev10\/homev10_sliders.zip","fonts":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev10\/homev10_fonts.json","icons":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev10\/homev10_icons.json","options":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev10\/homev10_options.json","content":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev10\/homev10_content.xml","widgets":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev10\/homev10_widgets.json","attachments":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev10\/homev10_attachments.zip","users":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/users.zip"},"homevillas-real-estate":{"back":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homevillas-real-estate\/homevillas_content_back.xml","widgets":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homevillas-real-estate\/homevillas_widgets.json","plugin":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homevillas-real-estate\/homevillas_plugin.json","media-attachments":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homevillas-real-estate\/homevillas_media-attachments.xml","menus":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homevillas-real-estate\/homevillas_menus.json","fonts":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homevillas-real-estate\/homevillas_fonts.json","content":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homevillas-real-estate\/homevillas_content.xml","navitems":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homevillas-real-estate\/homevillas_navitems.xml","content-new":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homevillas-real-estate\/homevillas_content-new.xml","sliders":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homevillas-real-estate\/homevillas_sliders.zip","icons":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homevillas-real-estate\/homevillas_icons.json","attachments":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homevillas-real-estate\/homevillas_attachments.zip","options":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homevillas-real-estate\/homevillas_options.json","users":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/users.zip"},"homev6":{"widgets":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev6\/homev6_widgets.json","sliders":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev6\/homev6_sliders.zip","attachments":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev6\/homev6_attachments.zip","fonts":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev6\/homev6_fonts.json","content":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev6\/homev6_content.xml","content-new":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev6\/homev6_content-new.xml","plugin":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev6\/homev6_plugin.json","media-attachments":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev6\/homev6_media-attachments.xml","options":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev6\/homev6_options.json","menus":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev6\/homev6_menus.json","icons":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev6\/homev6_icons.json","navitems":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev6\/homev6_navitems.xml","users":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/users.zip"},"homev2":{"options":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev2\/homev2_options.json","icons":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev2\/homev2_icons.json","sliders":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev2\/homev2_sliders.zip","plugin":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev2\/homev2_plugin.json","content-new":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev2\/homev2_content-new.xml","fonts":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev2\/homev2_fonts.json","menus":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev2\/homev2_menus.json","media-attachments":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev2\/homev2_media-attachments.xml","content":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev2\/homev2_content.xml","widgets":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev2\/homev2_widgets.json","navitems":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev2\/homev2_navitems.xml","attachments":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev2\/homev2_attachments.zip","users":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/users.zip"},"rtl":{"content":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/rtl\/rtl_content.xml","sliders":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/rtl\/rtl_sliders.zip","plugin":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/rtl\/rtl_plugin.json","menus":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/rtl\/rtl_menus.json","options":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/rtl\/rtl_options.json","attachments":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/rtl\/rtl_attachments.zip","widgets":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/rtl\/rtl_widgets.json","users":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/users.zip"},"homev14":{"media-attachments":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev14\/homev14_media-attachments.xml","content-new":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev14\/homev14_content-new.xml","menus":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev14\/homev14_menus.json","plugin":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev14\/homev14_plugin.json","navitems":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev14\/homev14_navitems.xml","sliders":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev14\/homev14_sliders.zip","attachments":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev14\/homev14_attachments.zip","options":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev14\/homev14_options.json","widgets":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev14\/homev14_widgets.json","content":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev14\/homev14_content.xml","fonts":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev14\/homev14_fonts.json","icons":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev14\/homev14_icons.json","users":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/users.zip"},"homev12":{"navitems":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev12\/homev12_navitems.xml","options":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev12\/homev12_options.json","media-attachments":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev12\/homev12_media-attachments.xml","plugin":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev12\/homev12_plugin.json","fonts":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev12\/homev12_fonts.json","icons":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev12\/homev12_icons.json","attachments":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev12\/homev12_attachments.zip","content-new":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev12\/homev12_content-new.xml","widgets":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev12\/homev12_widgets.json","sliders":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev12\/homev12_sliders.zip","menus":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev12\/homev12_menus.json","content":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev12\/homev12_content.xml","users":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/users.zip"},"homev4":{"content":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev4\/homev4_content.xml","widgets":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev4\/homev4_widgets.json","plugin":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev4\/homev4_plugin.json","fonts":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev4\/homev4_fonts.json","sliders":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev4\/homev4_sliders.zip","menus":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev4\/homev4_menus.json","icons":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev4\/homev4_icons.json","media-attachments":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev4\/homev4_media-attachments.xml","content-new":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev4\/homev4_content-new.xml","attachments":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev4\/homev4_attachments.zip","options":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev4\/homev4_options.json","navitems":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev4\/homev4_navitems.xml","users":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/users.zip"},"homev15":{"media-attachments":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev15\/homev15_media-attachments.xml","attachments":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev15\/homev15_attachments.zip","plugin":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev15\/homev15_plugin.json","content":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev15\/homev15_content.xml","widgets":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev15\/homev15_widgets.json","content-new":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev15\/homev15_content-new.xml","sliders":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev15\/homev15_sliders.zip","options":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev15\/homev15_options.json","fonts":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev15\/homev15_fonts.json","icons":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev15\/homev15_icons.json","menus":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev15\/homev15_menus.json","navitems":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev15\/homev15_navitems.xml","users":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/users.zip"},"homev7":{"menus":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev7\/homev7_menus.json","navitems":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev7\/homev7_navitems.xml","plugin":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev7\/homev7_plugin.json","widgets":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev7\/homev7_widgets.json","fonts":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev7\/homev7_fonts.json","media-attachments":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev7\/homev7_media-attachments.xml","icons":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev7\/homev7_icons.json","attachments":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev7\/homev7_attachments.zip","sliders":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev7\/homev7_sliders.zip","content":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev7\/homev7_content.xml","options":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev7\/homev7_options.json","content-new":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev7\/homev7_content-new.xml","users":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/users.zip"},"homev17":{"icons":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev17\/homev17_icons.json","menus":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev17\/homev17_menus.json","widgets":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev17\/homev17_widgets.json","plugin":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev17\/homev17_plugin.json","media-attachments":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev17\/homev17_media-attachments.xml","content":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev17\/homev17_content.xml","fonts":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev17\/homev17_fonts.json","options":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev17\/homev17_options.json","content-new":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev17\/homev17_content-new.xml","navitems":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev17\/homev17_navitems.xml","attachments":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev17\/homev17_attachments.zip","sliders":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev17\/homev17_sliders.zip","users":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/users.zip"},"homev8":{"sliders":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev8\/homev8_sliders","content":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev8\/homev8_content.xml","attachments":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev8\/homev8_attachments.zip","icons":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev8\/homev8_icons.json","plugin":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev8\/homev8_plugin.json","fonts":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev8\/homev8_fonts.json","options":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev8\/homev8_options.json","content-new":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev8\/homev8_content-new.xml","media-attachments":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev8\/homev8_media-attachments.xml","widgets":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev8\/homev8_widgets.json","menus":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev8\/homev8_menus.json","navitems":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev8\/homev8_navitems.xml","users":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/users.zip"},"homev3":{"menus":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev3\/homev3_menus.json","sliders":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev3\/homev3_sliders.zip","options":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev3\/homev3_options.json","plugin":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev3\/homev3_plugin.json","content":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev3\/homev3_content.xml","icons":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev3\/homev3_icons.json","attachments":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev3\/homev3_attachments.zip","navitems":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev3\/homev3_navitems.xml","media-attachments":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev3\/homev3_media-attachments.xml","content-new":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev3\/homev3_content-new.xml","widgets":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev3\/homev3_widgets.json","fonts":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev3\/homev3_fonts.json","users":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/users.zip"},"homev11":{"options":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev11\/homev11_options.json","attachments":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev11\/homev11_attachments.zip","content":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev11\/homev11_content.xml","navitems":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev11\/homev11_navitems.xml","content-new":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev11\/homev11_content-new.xml","fonts":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev11\/homev11_fonts.json","sliders":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev11\/homev11_sliders.zip","widgets":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev11\/homev11_widgets.json","media-attachments":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev11\/homev11_media-attachments.xml","menus":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev11\/homev11_menus.json","icons":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev11\/homev11_icons.json","plugin":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev11\/homev11_plugin.json","users":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/users.zip"},"homev5":{"icons":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev5\/homev5_icons.json","plugin":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev5\/homev5_plugin.json","content":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev5\/homev5_content.xml","options":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev5\/homev5_options.json","attachments":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev5\/homev5_attachments.zip","sliders":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev5\/homev5_sliders.zip","navitems":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev5\/homev5_navitems.xml","content-new":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev5\/homev5_content-new.xml","menus":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev5\/homev5_menus.json","widgets":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev5\/homev5_widgets.json","media-attachments":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev5\/homev5_media-attachments.xml","fonts":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev5\/homev5_fonts.json","users":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/users.zip"},"homev16":{"sliders":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev16\/homev16_sliders.zip","content":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev16\/homev16_content.xml","media-attachments":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev16\/homev16_media-attachments.xml","options":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev16\/homev16_options.json","content-new":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev16\/homev16_content-new.xml","fonts":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev16\/homev16_fonts.json","menus":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev16\/homev16_menus.json","plugin":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev16\/homev16_plugin.json","widgets":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev16\/homev16_widgets.json","icons":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev16\/homev16_icons.json","navitems":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev16\/homev16_navitems.xml","attachments":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev16\/homev16_attachments.zip","users":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/users.zip"},"homev13":{"content":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev13\/homev13_content.xml","menus":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev13\/homev13_menus.json","options":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev13\/homev13_options.json","icons":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev13\/homev13_icons.json","navitems":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev13\/homev13_navitems.xml","widgets":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev13\/homev13_widgets.json","plugin":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev13\/homev13_plugin.json","content-new":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev13\/homev13_content-new.xml","sliders":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev13\/homev13_sliders.zip","media-attachments":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev13\/homev13_media-attachments.xml","fonts":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev13\/homev13_fonts.json","attachments":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/homev13\/homev13_attachments.zip","users":"http:\/\/chimpgroup.com\/wp-demo\/webservice\/demo\/homevillas-real-estate\/users.zip"}}',
] );

require_once trailingslashit(get_template_directory()) . 'assets/frontend/translate/cs-strings.php';
require_once trailingslashit(get_template_directory()) . 'assets/frontend/translate/cs-strings-2.php';
require_once trailingslashit(get_template_directory()) . 'assets/frontend/translate/cs-strings-3.php';
require_once trailingslashit(get_template_directory()) . 'include/cs-global-functions.php';
require_once trailingslashit(get_template_directory()) . 'include/backend/cs-global-variables.php';
require_once trailingslashit(get_template_directory()) . 'include/cs-theme-functions.php';
require_once trailingslashit(get_template_directory()) . 'include/cs-helpers.php';

if( !is_admin()){
    if( !empty( $_REQUEST ) ){
        foreach( $_REQUEST as $requestparam => $paramValue){
             $_REQUEST[$requestparam] = filter_var($paramValue, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
             $_GET[$requestparam] = filter_var($paramValue, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        }
    }
}

/**
 * Sets up theme defaults and registers support for various WordPress features.     *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
if (!function_exists('wp_rem_cs_setup')) {

    function wp_rem_cs_setup() {
        /*
         * Make theme available for translation.
         * Translations can be filed in the /languages/ wp_rem_cs.
         * If you're building a theme based on wp_rem_cs, use a find and replace
         * to change 'wp-rem' to the name of your theme in all the template files.
         */
        load_theme_textdomain('homevillas-real-estate', get_template_directory() . '/languages');

        // Add default posts and comments RSS feed links to head.
        add_theme_support('automatic-feed-links');

        /*
         * Let WordPress manage the document title.
         * By adding theme support, we declare that this theme does not use a
         * hard-coded <title> tag in the document head, and expect WordPress to
         * provide it for us.
         */
        add_theme_support('title-tag');

        /*
         * Enable support for Post Thumbnails on posts and pages.
         *
         * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
         */
        add_theme_support('post-thumbnails');

        // This theme uses wp_nav_menu() in one location.

        register_nav_menus(array(
            'primary' => esc_html__('Primary', 'homevillas-real-estate'),
            'footer_menu' => esc_html__('Footer Menu', 'homevillas-real-estate'),
        ));



        /*
         * Switch default core markup for search form, comment form, and comments
         * to output valid HTML5.
         */
        add_theme_support('html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
        ));
        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');
        // Set up the WordPress core custom background feature.
        add_theme_support('custom-background', apply_filters('wp_rem_cs_custom_background_args', array(
            'default-color' => 'ffffff',
            'default-image' => '',
        )));

        /*
         * This theme styles the visual editor to resemble the theme style,
         * specifically font, colors, icons, and column width.
         */
        add_editor_style('assets/backend/css/editor-style.css');
        add_filter('the_password_form', 'wp_rem_cs_password_form');

        define('ICL_DONT_LOAD_LANGUAGE_SELECTOR_CSS', true);

        // theme unique identifier
        if (class_exists('wp_rem_real_estate_framework')) {
            wp_rem_real_estate_framework::$theme_identify = 'wp-rem-real-estate';
        }
    }

    add_action('after_setup_theme', 'wp_rem_cs_setup');
}
/*
 * Include file function
 */
if (!function_exists('wp_rem_cs_include_file')) {

    function wp_rem_cs_include_file($file_path = '', $inc = false) {
        if ($file_path != '') {
            if ($inc == true) {
                include $file_path;
            } else {
                require_once $file_path;
            }
        }
    }

}

/*
 * Add images sizes for 
 * complete site
 */
add_image_size('wp_rem_cs_media_1', 750, 422, true); //Blog Detail
add_image_size('wp_rem_cs_media_2', 750, 312, true); //Blog Large 
add_image_size('wp_rem_cs_media_3', 360, 223, true); //Blog Gride 
add_image_size('wp_rem_cs_media_4', 252, 189, true); //Blog Medium
add_image_size('wp_rem_cs_media_5', 409, 250, true); //Properties Grid
add_image_size('wp_rem_cs_media_6', 320, 180, true); //Properties Medium
add_image_size('wp_rem_cs_media_7', 750, 397, true); //Properties Detail
add_image_size('wp_rem_cs_media_8', 352, 264, true); //Properties featured
add_image_size('wp_rem_cs_media_9', 255, 252, true); //Properties list modern

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
if (!function_exists('wp_rem_cs_content_width')) {

    function wp_rem_cs_content_width() {
        $GLOBALS['content_width'] = apply_filters('wp_rem_cs_content_width', 640);
    }

    add_action('after_setup_theme', 'wp_rem_cs_content_width', 0);
}
/**
 * Registers a widget area.
 *
 * @link https://developer.wordpress.org/reference/functions/register_sidebar/
 *
 * @since Auto Mobile 1.0
 */
if (!function_exists('wp_rem_cs_widgets_init')) {

    function wp_rem_cs_widgets_init() {

        global $wp_rem_cs_var_options, $wp_rem_cs_var_static_text;

        /**
         * @If Theme Activated
         */
        if (get_option('wp_rem_cs_var_options')) {
            if (isset($wp_rem_cs_var_options['wp_rem_cs_var_sidebar']) && !empty($wp_rem_cs_var_options['wp_rem_cs_var_sidebar'])) {
                foreach ($wp_rem_cs_var_options['wp_rem_cs_var_sidebar'] as $sidebar) {
                    $sidebar_id = sanitize_title($sidebar);

                    $wp_rem_cs_widget_start = '<div class="widget %2$s">';
                    $wp_rem_cs_widget_end = '</div>';
                    if (isset($wp_rem_cs_var_options['wp_rem_cs_var_footer_widget_sidebar']) && $wp_rem_cs_var_options['wp_rem_cs_var_footer_widget_sidebar'] == $sidebar) {

                        $wp_rem_cs_widget_start = '<aside class="widget col-lg-4 col-md-4 col-sm-6 col-xs-12 %2$s">';
                        $wp_rem_cs_widget_end = '</aside>';
                    }
                    register_sidebar(array(
                        'name' => wp_rem_cs_allow_special_char($sidebar),
                        'id' => wp_rem_cs_allow_special_char($sidebar_id),
                        'description' => esc_html(wp_rem_cs_var_theme_text_srt('wp_rem_cs_var_widget_display_text')),
                        'before_widget' => wp_rem_cs_allow_special_char($wp_rem_cs_widget_start),
                        'after_widget' => wp_rem_cs_allow_special_char($wp_rem_cs_widget_end),
                        'before_title' => '<div class="widget-title"><h5>',
                        'after_title' => '</h5></div>'
                    ));
                }
            }

            $sidebar_name = array();
            if (isset($wp_rem_cs_var_options['wp_rem_cs_var_footer_sidebar']) && !empty($wp_rem_cs_var_options['wp_rem_cs_var_footer_sidebar'])) {
                $i = 0;
                foreach ($wp_rem_cs_var_options['wp_rem_cs_var_footer_sidebar'] as $wp_rem_cs_var_footer_sidebar) {

                    $footer_sidebar_id = sanitize_title($wp_rem_cs_var_footer_sidebar);
                    $sidebar_name = isset($wp_rem_cs_var_options['wp_rem_cs_var_footer_width']) ? $wp_rem_cs_var_options['wp_rem_cs_var_footer_width'] : '';
                    $wp_rem_cs_sidebar_name = isset($sidebar_name[$i]) ? $sidebar_name[$i] : '';
                    $custom_width = str_replace('(', ' - ', $wp_rem_cs_sidebar_name);
                    $wp_rem_cs_widget_start = '<div class="widget %2$s">';
                    $wp_rem_cs_widget_end = '</div>';

                    if (isset($wp_rem_cs_var_options['wp_rem_cs_var_footer_widget_sidebar']) && $wp_rem_cs_var_options['wp_rem_cs_var_footer_widget_sidebar'] == $wp_rem_cs_var_footer_sidebar) {

                        $wp_rem_cs_widget_start = '<aside class="widget col-lg-4 col-md-4 col-sm-6 col-xs-12 %2$s">';
                        $wp_rem_cs_widget_end = '</aside>';
                    }

                    register_sidebar(array(
                        'name' => wp_rem_cs_var_theme_text_srt('wp_rem_cs_var_footer') . $wp_rem_cs_var_footer_sidebar . ' ' . '(' . $custom_width . ' ',
                        'id' => wp_rem_cs_allow_special_char($footer_sidebar_id),
                        'description' => wp_rem_cs_var_theme_text_srt('wp_rem_cs_var_widget_display_text'),
                        'before_widget' => wp_rem_cs_allow_special_char($wp_rem_cs_widget_start),
                        'after_widget' => wp_rem_cs_allow_special_char($wp_rem_cs_widget_end),
                        'before_title' => '<div class="widget-title"><h5>',
                        'after_title' => '</h5></div>'
                    ));
                    $i ++;
                }
            }
        } else {
            register_sidebar(array(
                'name' => wp_rem_cs_var_theme_text_srt('wp_rem_cs_var_widgets'),
                'id' => 'sidebar-1',
                'description' => wp_rem_cs_var_theme_text_srt('wp_rem_cs_var_widget_display_right_text'),
                'before_widget' => '<div class="widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<div class="widget-title"><h5>',
                'after_title' => '</h5></div>'
            ));
        }
    }

    add_action('widgets_init', 'wp_rem_cs_widgets_init');
}
/**
 * Add meta tag in head.
 */
if (!function_exists('wp_rem_add_meta_tags')) {

    function wp_rem_add_meta_tags() {

        echo '<meta http-equiv="X-UA-Compatible" content="IE=edge" />';
        $wp_rem_hide_icon = '.wp-rem-icon-hidden i{visibility: hidden !important;}';
        wp_rem_inline_enqueue_style($wp_rem_hide_icon);
        $wp_rem_hide_icon_js = '$(window).load(function () { 
                $("body").removeClass("wp-rem-icon-hidden");
            }); ';
        wp_rem_cs_inline_enqueue_script($wp_rem_hide_icon_js, 'wp-rem-hidden-icon-script');
    }

    add_action('wp_head', 'wp_rem_add_meta_tags', 2);
}

/**
 * Enqueue scripts and styles.
 */
if (!function_exists('wp_rem_cs_scripts_1')) {

    function wp_rem_cs_scripts_1() {
        global $wp_rem_cs_var_options;
        $wp_rem_cs_responsive_site = isset($wp_rem_cs_var_options['wp_rem_cs_var_responsive']) ? $wp_rem_cs_var_options['wp_rem_cs_var_responsive'] : '';


        $theme_version = wp_rem_cs_get_theme_version();
        wp_enqueue_style('bootstrap_css', get_template_directory_uri() . '/assets/frontend/css/bootstrap.css');
        wp_enqueue_style('bootstrap-theme', get_template_directory_uri() . '/assets/frontend/css/bootstrap-theme.css', '', $theme_version);
        wp_enqueue_style('chosen', trailingslashit(get_template_directory_uri()) . 'assets/frontend/css/chosen.css');
        wp_enqueue_style('swiper', trailingslashit(get_template_directory_uri()) . 'assets/frontend/css/swiper.css');
        wp_enqueue_style('wp-rem-style', get_stylesheet_uri(), '', $theme_version);




        wp_enqueue_style('wp-rem-widget', get_template_directory_uri() . '/assets/frontend/css/widget.css', '', $theme_version);
        wp_enqueue_style('iconmoon', (get_template_directory_uri()) . '/assets/common/icomoon/css/iconmoon.css', '', $theme_version);
        wp_register_style('wp-rem-prettyPhoto', trailingslashit(get_template_directory_uri()) . 'assets/frontend/css/prettyPhoto.css');

        if (is_singular() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }
        wp_enqueue_script('bootstrap-min', get_template_directory_uri() . '/assets/common/js/bootstrap.min.js', array('jquery'), $theme_version);
        wp_enqueue_script('modernizr', get_template_directory_uri() . '/assets/frontend/js/modernizr.js', array('jquery'), $theme_version, true);

        wp_register_script('wp-rem-counter', get_template_directory_uri() . '/assets/frontend/js/counter.js', array('jquery'), $theme_version, true);

        wp_enqueue_script('responsive-menu', get_template_directory_uri() . '/assets/frontend/js/responsive.menu.js', '', $theme_version, true);
        wp_enqueue_script('chosen', get_template_directory_uri() . '/assets/common/js/chosen.select.js', '', $theme_version);
        wp_enqueue_script('swiper', get_template_directory_uri() . '/assets/frontend/js/swiper.min.js', '', $theme_version, true);

        //wp_enqueue_script('fliters', get_template_directory_uri() . '/assets/frontend/js/fliters.js', '', $theme_version, true);
        //wp_enqueue_script('wp-rem-maps-styles', trailingslashit(get_template_directory_uri()) . 'assets/backend/js/cs-map_styles.js', '', $theme_version, true);
        wp_enqueue_script('fitvids', get_template_directory_uri() . '/assets/frontend/js/fitvids.js', '', $theme_version, true);

        wp_register_script('skills-progress', get_template_directory_uri() . '/assets/frontend/js/skills-progress.js', '', $theme_version, true);

        wp_enqueue_script('wp-rem-functions', get_template_directory_uri() . '/assets/frontend/js/functions.js', '', $theme_version, true);
        wp_register_script('wp-rem-lightbox', trailingslashit(get_template_directory_uri()) . 'assets/frontend/js/lightbox.js', '', '', true);
        wp_register_script('wp-rem-prettyPhoto', trailingslashit(get_template_directory_uri()) . 'assets/frontend/js/jquery.prettyPhoto.js', '', '', true);
        wp_register_script('wp-rem-growls', get_template_directory_uri() . '/assets/frontend/js/jquery.growl.js', '', $theme_version, true);
        if (class_exists('WooCommerce')) {
            wp_enqueue_style('custom-woocommerce', trailingslashit(get_template_directory_uri()) . 'assets/frontend/css/woocommerce.css', '', $theme_version);
        }
        if (!function_exists('wp_rem_cs_addthis_script_init_method')) {

            function wp_rem_cs_addthis_script_init_method() {
                wp_enqueue_script('addthis', trailingslashit(get_template_directory_uri()) . 'assets/frontend/js/addthis_widget.js#pubid=xa-4e4412d954dccc64', '', '', true);
            }

        }
        if (!function_exists('wp_rem_cs_inline_enqueue_script')) {

            function wp_rem_cs_inline_enqueue_script($script = '', $script_handler = 'wp-rem-custom-inline') {
                wp_register_script($script_handler, trailingslashit(get_template_directory_uri()) . 'assets/common/js/custom-inline.js', '', '', true);
                wp_enqueue_script($script_handler);
                wp_add_inline_script($script_handler, $script);
            }

        }
        if (!function_exists('wp_rem_cs_var_dynamic_scripts')) {

            function wp_rem_cs_var_dynamic_scripts($wp_rem_cs_js_key, $wp_rem_cs_arr_key, $wp_rem_cs_js_code) {
                // Register the script
                wp_register_script('wp-rem-dynamic-scripts', trailingslashit(get_template_directory_uri()) . 'assets/frontend/js/inline-scripts-functions.js', '', '', true);
                // Localize the script
                $wp_rem_cs_code_array = array(
                    $wp_rem_cs_arr_key => $wp_rem_cs_js_code
                );
                wp_localize_script('wp-rem-dynamic-scripts', $wp_rem_cs_js_key, $wp_rem_cs_code_array);
                wp_enqueue_script('wp-rem-dynamic-scripts');
            }

        }
        if (!function_exists('wp_rem_inline_enqueue_style')) {

            function wp_rem_inline_enqueue_style($script = '', $script_handler = 'inline-style-functions') {
                wp_register_style('inline-style-functions', trailingslashit(get_template_directory_uri()) . 'assets/frontend/css/inline-style-functions.css', '', '', 'all');
                wp_enqueue_style($script_handler);
                wp_add_inline_style($script_handler, $script);
            }

        }
    }

    add_action('wp_enqueue_scripts', 'wp_rem_cs_scripts_1', 1);
}

function wp_rem_cs_scripts_dark() {
    global $wp_rem_cs_var_options;
    $wp_rem_theme_skin_style = isset($wp_rem_cs_var_options['wp_rem_cs_var_theme_skin_style']) ? $wp_rem_cs_var_options['wp_rem_cs_var_theme_skin_style'] : '';
    if (isset($wp_rem_theme_skin_style) && $wp_rem_theme_skin_style == 'dark') {
        wp_enqueue_style('theme-dark-colors', get_template_directory_uri() . '/assets/frontend/css/theme-dark-colors.css', '', $theme_version);
    }
}

add_action('wp_enqueue_scripts', 'wp_rem_cs_scripts_dark', 5);

if (!function_exists('wp_rem_gallery_masonry')) {

    function wp_rem_gallery_masonry() {
        wp_enqueue_script('wp_rem_init_js', trailingslashit(get_template_directory_uri()) . 'assets/frontend/js/init.js', '', '', true);
        wp_enqueue_script('wp_rem_freetile_js', trailingslashit(get_template_directory_uri()) . 'assets/frontend/js/jquery.freetile.js', '', '', true);
        wp_enqueue_script('wp_rem_masonry_pkgd_min_js', trailingslashit(get_template_directory_uri()) . 'assets/frontend/js/masonry.pkgd.min.js', '', '', true);
    }

}

if (!function_exists('wp_rem_cs_header_color_styles')) {

    function wp_rem_cs_header_color_styles() {
        global $wp_rem_cs_var_options;
        $custom_style_ver = (isset($wp_rem_cs_var_options['wp_rem_cs_var_theme_option_save_flag'])) ? $wp_rem_cs_var_options['wp_rem_cs_var_theme_option_save_flag'] : '';
        wp_enqueue_style('wp-rem-default-element-style', trailingslashit(get_template_directory_uri()) . '/assets/frontend/css/default-element.css', '', $custom_style_ver);
    }

    add_action('wp_enqueue_scripts', 'wp_rem_cs_header_color_styles', 9);
}

/**
 * Enqueue scripts and styles.
 */
if (!function_exists('wp_rem_cs_scripts_10')) {

    function wp_rem_cs_scripts_10() {
        global $wp_rem_cs_var_options;
        $wp_rem_cs_responsive_site = isset($wp_rem_cs_var_options['wp_rem_cs_var_responsive']) ? $wp_rem_cs_var_options['wp_rem_cs_var_responsive'] : '';
        if (is_rtl()) {
            wp_enqueue_style('wp_rem_cs-rtl', get_template_directory_uri() . '/assets/frontend/css/rtl.css');
        }
        if ($wp_rem_cs_responsive_site == 'on') {
            $theme_version = wp_rem_cs_get_theme_version();
            wp_enqueue_style('wp_rem_responsive_css', get_template_directory_uri() . '/assets/frontend/css/responsive.css', '', $theme_version);
        }
    }

    add_action('wp_enqueue_scripts', 'wp_rem_cs_scripts_10', 10);
}

/**
 * Custom Css
 */
if (!function_exists('wp_rem_custom_inline_styles_method')) {

    function wp_rem_custom_inline_styles_method() {
        global $wp_rem_cs_var_options;
        $wp_rem_custom_css = isset($wp_rem_cs_var_options['wp_rem_cs_var_custom_css']) ? $wp_rem_cs_var_options['wp_rem_cs_var_custom_css'] : '';
        if ($wp_rem_custom_css != '') {
            wp_enqueue_style('custom-style-inline', trailingslashit(get_template_directory_uri()) . 'assets/frontend/css/custom-script.css', '', '', true);
            wp_add_inline_style('custom-style-inline', $wp_rem_custom_css);
        }
    }

    add_action('wp_enqueue_scripts', 'wp_rem_custom_inline_styles_method', 30);
}

/**
 * Add Admin Page for 
 * Theme Options Menu
 */
if (!function_exists('wp_rem_cs_var_options')) {
    add_action('admin_menu', 'wp_rem_cs_var_options');

    function wp_rem_cs_var_options() {
        global $wp_rem_cs_var_static_text;
        $wp_rem_cs_var_theme_options = wp_rem_cs_var_theme_text_srt('wp_rem_cs_var_theme_options');
        if (current_user_can('administrator')) {
            add_theme_page($wp_rem_cs_var_theme_options, $wp_rem_cs_var_theme_options, 'manage_options', 'wp_rem_settings_page', 'wp_rem_settings_page');
        }
    }

}

/**
 * Enqueue Google Fonts.
 */
if (!function_exists('wp_rem_cs_var_load_google_font_families')) {

    function wp_rem_cs_var_load_google_font_families() {
        if (wp_rem_cs_var_is_fonts_loaded()) {
            $fonts_array = wp_rem_cs_var_is_fonts_loaded();
            $fonts_url = wp_rem_cs_var_get_font_families($fonts_array);
            if ($fonts_url) {
                $font_url = add_query_arg('family', urlencode($fonts_url), "//fonts.googleapis.com/css");
                wp_enqueue_style('wp-rem-google-fonts', $font_url, array(), '');
            }
        }
    }

    add_action('wp_enqueue_scripts', 'wp_rem_cs_var_load_google_font_families', 0);
}

/*
 * admin Enque Scripts
 */
if (!function_exists('wp_rem_cs_var_admin_scripts_enqueue')) {

    function wp_rem_cs_var_admin_scripts_enqueue() {
        $theme_version = wp_rem_cs_get_theme_version();
        if (is_admin()) {
            global $wp_rem_cs_var_template_path;
            $wp_rem_cs_var_template_path = trailingslashit(get_template_directory_uri()) . 'assets/backend/js/cs-media-upload.js';
            wp_enqueue_style('fonticonpicker', trailingslashit(get_template_directory_uri()) . 'assets/common/icomoon/css/jquery.fonticonpicker.min.css', array(), $theme_version);
            wp_enqueue_style('fonticonpicker');
            wp_enqueue_style('iconmoon', trailingslashit(get_template_directory_uri()) . 'assets/common/icomoon/css/iconmoon.css');
            wp_enqueue_style('bootstrap', trailingslashit(get_template_directory_uri()) . 'assets/common/icomoon/theme/bootstrap-theme/jquery.fonticonpicker.bootstrap.css');
            wp_enqueue_style('chosen', trailingslashit(get_template_directory_uri()) . 'assets/backend/css/chosen.css');
            wp_enqueue_style('wp-rem-bootstrap', trailingslashit(get_template_directory_uri()) . 'assets/backend/css/bootstrap.css');
            wp_enqueue_style('wp_rem_cs-admin-lightbox', trailingslashit(get_template_directory_uri()) . 'assets/backend/css/lightbox.css');

            wp_enqueue_style('wp-rem-google-fonts', 'http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600&subset=latin,cyrillic-ext');
            wp_enqueue_style('wp-rem-social-network', trailingslashit(get_template_directory_uri()) . 'assets/backend/css/social-network.css');
            wp_enqueue_style('wp-rem-admin-style', trailingslashit(get_template_directory_uri()) . 'assets/backend/css/admin-style.css');

            wp_enqueue_style('wp-color-picker');
            wp_enqueue_script('admin-upload', $wp_rem_cs_var_template_path, array('jquery', 'media-upload', 'thickbox', 'jquery-ui-droppable', 'jquery-ui-datepicker', 'jquery-ui-slider', 'wp-color-picker'));
            wp_enqueue_script('fonticonpicker', trailingslashit(get_template_directory_uri()) . 'assets/common/icomoon/js/jquery.fonticonpicker.min.js');
            wp_enqueue_script('bootstrap', trailingslashit(get_template_directory_uri()) . 'assets/common/js/bootstrap.min.js', '', '', true);
            wp_enqueue_style('jquery_datetimepicker', trailingslashit(get_template_directory_uri()) . 'assets/backend/css/jquery_datetimepicker.css');
            wp_enqueue_style('datepicker', trailingslashit(get_template_directory_uri()) . 'assets/backend/css/datepicker.css');
            wp_enqueue_style('jquery_ui_datepicker_theme', trailingslashit(get_template_directory_uri()) . 'assets/backend/css/jquery_ui_datepicker_theme.css');
            wp_enqueue_script('jquery_datetimepicker', trailingslashit(get_template_directory_uri()) . 'assets/backend/js/jquery_datetimepicker.js');
            wp_enqueue_script('wp_rem_cs-light-box-js', trailingslashit(get_template_directory_uri()) . 'assets/backend/js/light-box.js', '', '', true);
            wp_enqueue_script('wp_rem_cs-theme-options', trailingslashit(get_template_directory_uri()) . 'assets/backend/js/cs-theme-option-fucntions.js', '', '', true);
            $wp_rem_theme_options_vars = array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'theme_url' => get_template_directory_uri(),
                'verify_blank_error' => wp_rem_cs_var_theme_text_srt('wp_rem_pur_code_cannot_be_blank'),
                'verify_code_incorrect' => wp_rem_cs_var_theme_text_srt('wp_rem_pur_code_is_not_correct'),
                'purchase_verifiying' => wp_rem_cs_var_theme_text_srt('wp_rem_pur_code_verifiying'),
                'client_logo_error' => wp_rem_cs_var_theme_text_srt('wp_rem_theme_option_client_logo_error'),
                'social_sharing_error' => wp_rem_cs_var_theme_text_srt('wp_rem_theme_option_social_sharing_error'),
                'sidebar_error' => wp_rem_cs_var_theme_text_srt('wp_rem_theme_option_sidebar_error'),
            );
            wp_localize_script('wp_rem_cs-theme-options', 'wp_rem_theme_options_vars', $wp_rem_theme_options_vars);
            wp_enqueue_script('chosen', trailingslashit(get_template_directory_uri()) . 'assets/common/js/chosen.select.js', '', '');
            wp_enqueue_script('wp_rem_cs-custom-functions', trailingslashit(get_template_directory_uri()) . 'assets/backend/js/cs-fucntions.js', '', $theme_version, true);
            ////editor script
            wp_enqueue_script('jquery-te', trailingslashit(get_template_directory_uri()) . 'assets/backend/editor/js/jquery-te-1.4.0.min.js');
            wp_enqueue_style('jquery-te', trailingslashit(get_template_directory_uri()) . 'assets/backend/editor/css/jquery-te-1.4.0.css');

            if (!function_exists('wp_rem_cs_admin_inline_enqueue_script')) {

                function wp_rem_cs_admin_inline_enqueue_script($script = '', $script_handler = 'custom') {
                    wp_enqueue_script($script_handler);
                    wp_add_inline_script($script_handler, $script);
                }

            }
        }
    }

    add_action('admin_enqueue_scripts', 'wp_rem_cs_var_admin_scripts_enqueue');
}


if (!function_exists('wp_rem_cs_var_date_picker')) {

    function wp_rem_cs_var_date_picker() {
        global $wp_rem_cs_var_template_path;
        wp_enqueue_script('wp_rem_cs-admin-upload', $wp_rem_cs_var_template_path, array('jquery', 'media-upload'));
    }

}

/*
 * Get current theme version
 */
if (!function_exists('wp_rem_cs_get_theme_version')) {

    function wp_rem_cs_get_theme_version() {
        $my_theme = wp_get_theme();
        $theme_version = $my_theme->get('Version');
        return $theme_version;
    }

}

/**
 * Default Pages title.
 *
 * @since Auto Mobile 1.0
 */
if (!function_exists('wp_rem_cs_post_page_title')) {

    function wp_rem_cs_post_page_title() {

        $wp_rem_cs_var_search_result = wp_rem_cs_var_theme_text_srt('wp_rem_cs_var_search_result');
        $wp_rem_cs_var_author = wp_rem_cs_var_theme_text_srt('wp_rem_cs_var_author');
        $wp_rem_cs_var_archives = wp_rem_cs_var_theme_text_srt('wp_rem_cs_var_archives');
        $wp_rem_cs_var_daily_archives = wp_rem_cs_var_theme_text_srt('wp_rem_cs_var_daily_archives');
        $wp_rem_cs_var_monthly_archives = wp_rem_cs_var_theme_text_srt('wp_rem_cs_var_monthly_archives');
        $wp_rem_cs_var_yearly_archives = wp_rem_cs_var_theme_text_srt('wp_rem_cs_var_yearly_archives');
        $wp_rem_cs_var_tags = wp_rem_cs_var_theme_text_srt('wp_rem_cs_var_tags');
        $wp_rem_cs_var_category = wp_rem_cs_var_theme_text_srt('wp_rem_cs_var_category');
        $wp_rem_cs_var_error_404 = wp_rem_cs_var_theme_text_srt('wp_rem_cs_var_error_404');
        $wp_rem_cs_var_home = wp_rem_cs_var_theme_text_srt('wp_rem_cs_var_home');

        if (function_exists('is_shop') && is_shop()) {
            $wp_rem_cs_var_post_ID = wc_get_page_id('shop');
            echo get_the_title($wp_rem_cs_var_post_ID);
        } else if (!is_page() && !is_singular() && !is_search() && !is_404() && !is_front_page()) {
            the_archive_title();
        } else if (is_search()) {
            printf($wp_rem_cs_var_search_result, '<span>' . get_search_query() . '</span>');
        } else if (is_404()) {
            echo esc_attr($wp_rem_cs_var_error_404);
        } else if (is_home()) {
            echo esc_html($wp_rem_cs_var_home);
        } else if (is_page() || is_singular()) {
            echo get_the_title();
        }
    }

}
add_filter('comment_form_field_comment', 'wp_rem_cs_form_field_comment', 10, 1);
add_action('comment_form_logged_in_after', 'wp_rem_cs_comment_tut_fields');
add_action('comment_form_after_fields', 'wp_rem_cs_comment_tut_fields');

function wp_rem_cs_form_field_comment($field) {

    return '';
}

function wp_rem_cs_comment_tut_fields() {

    $cs_msg_class = '';
    if (is_user_logged_in()) {
        $cs_msg_class = ' cs-message';
    }
    $comment_field = '<textarea name="comment" class="commenttextarea" rows="55" cols="15" placeholder="' . wp_rem_cs_var_theme_text_srt('wp_rem_cs_var_comment_text_here') . '"></textarea>';
    $html = '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12' . esc_html($cs_msg_class) . '"><div class="field-holder"><label>' . wp_rem_cs_allow_special_char($comment_field) . '</label></div></div>';
    echo wp_rem_cs_allow_special_char($html);
}

if (!function_exists('wp_rem_allow_special_char')) {

    function wp_rem_allow_special_char($input = '') {

        $output = $input;

        return $output;
    }

}


/**
 * Including the required files
 *
 * @since Wp_rem_cs 1.0
 */
if (!function_exists('wp_rem_cs_require_theme_files')) {

    function wp_rem_cs_require_theme_files($wp_rem_cs_path = '') {
        global $wp_filesystem;

        require_once ABSPATH . '/wp-admin/includes/file.php';

        $backup_url = '';
        if (false === ($creds = request_filesystem_credentials($backup_url, '', false, false, array()) )) {
            return true;
        }
        if (!WP_Filesystem($creds)) {
            request_filesystem_credentials($backup_url, '', true, false, array());
            return true;
        }
        $wp_rem_cs_sh_front_dir = trailingslashit(get_template_directory()) . $wp_rem_cs_path;


        $wp_rem_cs_all_f_list = $wp_filesystem->dirlist($wp_rem_cs_sh_front_dir);

        if (is_array($wp_rem_cs_all_f_list) && sizeof($wp_rem_cs_all_f_list) > 0) {
            foreach ($wp_rem_cs_all_f_list as $file_key => $file_val) {
                if (isset($file_val['name'])) {
                    $wp_rem_cs_file_name = $file_val['name'];
                    $wp_rem_cs_ext = pathinfo($wp_rem_cs_file_name, PATHINFO_EXTENSION);
                    if ($wp_rem_cs_ext == 'php') {
                        require_once trailingslashit(get_template_directory()) . $wp_rem_cs_path . $wp_rem_cs_file_name;
                    }
                }
            }
        }
    }

}
/**
 * stripslashes string
 *
 * @since Auto Mobile 1.0
 */
if (!function_exists('wp_rem_cs_var_stripslashes_htmlspecialchars')) {

    function wp_rem_cs_var_stripslashes_htmlspecialchars($value) {
        $value = is_array($value) ? array_map('wp_rem_cs_var_stripslashes_htmlspecialchars', $value) : stripslashes(htmlspecialchars($value));
        return $value;
    }

}

require_once ABSPATH . '/wp-admin/includes/file.php';
/**
 * Mega Menu.
 */
require_once trailingslashit(get_template_directory()) . 'include/mega-menu/custom-walker.php';
require_once trailingslashit(get_template_directory()) . 'include/mega-menu/edit-custom-walker.php';
require_once trailingslashit(get_template_directory()) . 'include/mega-menu/menu-functions.php';
/**
 * Implement the Custom Header feature.
 */
require_once trailingslashit(get_template_directory()) . 'include/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require_once trailingslashit(get_template_directory()) . 'include/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require_once trailingslashit(get_template_directory()) . 'include/extras.php';

/*
 * Inlcude themes required files for theme options
 */
require_once trailingslashit(get_template_directory()) . 'include/backend/cs-custom-fields/cs-form-fields.php';
require_once trailingslashit(get_template_directory()) . 'include/backend/cs-custom-fields/cs-html-fields.php';
require_once trailingslashit(get_template_directory()) . 'include/backend/cs-admin-functions.php';
require_once trailingslashit(get_template_directory()) . 'include/backend/importer-hooks.php';
require_once trailingslashit(get_template_directory()) . 'include/backend/cs-googlefont/cs-fonts-array.php';
require_once trailingslashit(get_template_directory()) . 'include/backend/cs-googlefont/cs-fonts.php';
require_once trailingslashit(get_template_directory()) . 'include/backend/cs-googlefont/cs-fonts-functions.php';
require_once trailingslashit(get_template_directory()) . 'include/backend/cs-widgets/import/cs-class-widget-data.php';
require_once trailingslashit(get_template_directory()) . 'include/backend/cs-theme-options/cs-theme-options.php';
require_once trailingslashit(get_template_directory()) . 'include/backend/cs-theme-options/cs-theme-options-functions.php';
require_once trailingslashit(get_template_directory()) . 'include/backend/cs-theme-options/cs-theme-options-fields.php';
require_once trailingslashit(get_template_directory()) . 'include/backend/cs-theme-options/cs-theme-options-arrays.php';
require_once trailingslashit(get_template_directory()) . 'include/backend/cs-activation-plugins/cs-require-plugins.php';
require_once trailingslashit(get_template_directory()) . 'include/cs-class-parse.php';
require_once trailingslashit(get_template_directory()) . 'include/backend/theme-config.php';
require_once trailingslashit(get_template_directory()) . 'include/frontend/cs-header.php';
require_once trailingslashit(get_template_directory()) . 'include/frontend/class-pagination.php';
require_once trailingslashit(get_template_directory()) . 'template-parts/blog/blog_element.php';
require_once trailingslashit(get_template_directory()) . 'template-parts/blog/blog_functions.php';
/*
 * Inlcude theme classes files
 */
require_once trailingslashit(get_template_directory()) . 'include/backend/classes/class-category-meta.php';
/*
 * Inlcude theme required files for widgets
 */
require_once trailingslashit(get_template_directory()) . 'include/backend/cs-widgets/cs-custom-menu.php';
require_once trailingslashit(get_template_directory()) . 'include/backend/cs-widgets/cs-flickr.php';
require_once trailingslashit(get_template_directory()) . 'include/backend/cs-widgets/cs-author.php';
require_once trailingslashit(get_template_directory()) . 'include/backend/cs-widgets/cs-ads.php';
require_once trailingslashit(get_template_directory()) . 'include/backend/cs-widgets/cs-text.php';
require_once trailingslashit(get_template_directory()) . 'include/backend/cs-widgets/cs-mailchimp.php';
require_once trailingslashit(get_template_directory()) . 'include/backend/cs-widgets/cs-twitter.php';
require_once trailingslashit(get_template_directory()) . 'include/backend/cs-widgets/cs-facebook.php';
require_once trailingslashit(get_template_directory()) . 'include/backend/cs-widgets/cs-recent-posts.php';
require_once trailingslashit(get_template_directory()) . 'include/backend/cs-widgets/cs-contact-info.php';
require_once trailingslashit(get_template_directory()) . 'include/backend/cs-widgets/cs-fancymenu.php';
if (class_exists('woocommerce')) {
    require_once trailingslashit(get_template_directory()) . 'include/backend/cs-woocommerce/cs-config.php';
}

if (!function_exists('wp_rem_cs_include_shortcodes')) {

    /**
     * Include shortcodes backend and frontend as well.
     */
    function wp_rem_cs_include_shortcodes() {
        /* shortcodes files */
        wp_rem_cs_require_theme_files('include/backend/cs-shortcodes/');
        wp_rem_cs_require_theme_files('include/frontend/cs-shortcodes/');
    }

    add_action('init', 'wp_rem_cs_include_shortcodes', 1);
}

$active_plugins = get_option('active_plugins', array());
if (!in_array('wp-realestate-manager/wp-realestate-manager.php', $active_plugins)) {

    function wp_rem_theme_loader() {
        echo '<div class="wp_rem_loader" style="display: none;">';
        echo '<div class="loader-img"><i class="icon-spinner"></i></div></div>';
        echo '<div class="wp-rem-button-loader spinner">
			<div class="bounce1"></div>
			  <div class="bounce2"></div>
			  <div class="bounce3"></div>
		  </div>';
    }

    add_action('wp_footer', 'wp_rem_theme_loader');
}

// Add specific CSS class by filter.
//
//if (!function_exists('wp_rem_js_async_attr')) {
//
//    function wp_rem_js_async_attr($tag) {
//        $scripts_to_include = array(
//            'jquery_latlon_picker.js',
//            'counter.js',
//            'croppic.js',
//            'admin-bar.min.js',
//            'modernizr.js',
//            'fliters.js',
//            'map-styles.js',
//            'cs-connect.js',
//            'map-infobox.js',
//            'markerclusterer.js',
//            'property-top-map.js',
//            'inline-scripts-functions.js',
//            'property-functions.js',
//            'property-notifications.js',
//            'sortable.min.js',
//            'jquery.datetimepicker.js',
//            'functions.js',
//            'libraries=places',
//        );
//
//        foreach ($scripts_to_include as $include_script) {
//            if (true == strpos($tag, $include_script))
//                return str_replace(' src', ' defer async src', $tag);
//        }
//        return $tag;
//    }
//
//    add_filter('script_loader_tag', 'wp_rem_js_async_attr', 10);
//}


add_filter('body_class', function( $classes ) {
    return array_merge($classes, array('wp-rem-icon-hidden'));
});
