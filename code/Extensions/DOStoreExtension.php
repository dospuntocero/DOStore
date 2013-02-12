<?php

class DOStoreExtension extends Extension {
	
	function onAfterInit(){
		Requirements::javascript(THIRDPARTY_DIR."/jquery/jquery.min.js");
		Requirements::javascript(THIRDPARTY_DIR."/jquery-cookie/jquery.cookie.js");
		Requirements::javascript("DOStore/javascript/store.js");
		Requirements::themedCSS("DOStore");
	}
	
	
}