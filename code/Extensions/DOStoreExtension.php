<?php

class DOStoreExtension extends Extension {
	
	function onAfterInit(){
		Requirements::javascript("dospuntoceroCMS/js/jquery.js");
		Requirements::javascript("dospuntoceroCMS/js/jquery.cookie.js");
		Requirements::javascript("DOStore/javascript/store.js");
		Requirements::css("DOStore/css/DOStore.css");
	}
	
}