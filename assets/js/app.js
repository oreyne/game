/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import '../css/app.css';

// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
import $ from 'jquery';
import 'bootstrap';

console.log('Hello Webpack Encore! Edit me in assets/js/app.js');

$(document).ready(function() {
   window.myTurn = function(route) {  
	  $.ajax({
		    url: route,
		    type: 'POST',		        
		    })
	  		.done (
	  			function (response) {
			    	if (response['winner'] === true){
				        $('.game-info')[0].innerHTML = 'Winner: ' + response['played'];
				        if (response['played'] !== response['player'])
				        	$('.' + response['component'])[0].innerHTML = response['played'];
			    	} else {
			    		if (response['played'] !== response['player']) {
			    			$('.game-info')[0].innerHTML = 'Next player: ' + response['player'];
				        	$('.' + response['component'])[0].innerHTML = response['played'];
			    		}
			    	}
		    })
		    .fail(function (response) {
		    	$('.game-info')[0].innerHTML = 'Ups!, reset the game please.';
		    }) ;
	}
});
