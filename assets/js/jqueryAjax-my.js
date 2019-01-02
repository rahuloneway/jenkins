/** @ Author: Hitesh Thakur **/
/** @ Date: 6 Jan 2014 **/
/** @ Plugin Name: Simple Loader plugin  **/

/*
$.ajax({
	url: "someurl",
	type: "POST",
	data: {  comp_name : $("#comp_name").val() ,
			 contact_name : $("#contact_name").val() ,
			 f_name : $("#f_name").val() ,
			 l_name : $("#l_name").val() ,
			 phone : $("#phone").val(), 
			 fax : $("#fax").val(), 
			 email : $("#email").val(), 
			 user_phone : $("#altphn").val(), 
			 address1 : $("#address1").val(), 
			 address2 : $("#address2").val() 
			},
	dataType: "html",
	beforeSend: function(){
		// showSpinner();
	},
	success: function(){
	
	},
	complete: function( result ){
		// hideSpinner(); || fadeoutSpinner();
	},
	error: function(){
		showErrorHide();
	}
});
*/


/** Create overlay div with loading image: set TheLoadImg='false' if no loading image needed  **/
function initSpinnerFunction( TheLoadImg ) {

	var LoadingImg = TheLoadImg || true;
	
	( $('#custom-Overlay').length > 0 )? $('#custom-Overlay').remove() : "" ; // remove overlay if already exists
	
	$("<div />").css({
		position: "absolute",
		display: "none",
		left: 0,
		top: 0,
		zIndex: 1000000,  // to be on the safe side
		"background-color": 'rgba(0,0,0,0.5)'
	}).attr('id','custom-Overlay').appendTo($("body"));
	
	if( LoadingImg!='false' )
		$("#custom-Overlay").append("<div id ='PleaseWait' ><img src='loading.gif' alt='plaese Wait' /></div>");
		
}
// <img src='"+BASEURL+"/app/webroot/images/loading.gif' alt='plaese Wait' />

/*
function center( selector ) {
	selector.css("position", "absolute");
	selector.css("top", ($(window).height() - selector.height())/ 2 + $(window).scrollTop() + "px");
	selector.css("left", ($(window).width() - selector.width()) / 2 + $(window).scrollLeft() + "px");
	return this;
}
*/

/** Plugin function to pull a element to center of screen: Uses - $('SELECTOR').center() **/
jQuery.fn.center = function () {
	this.css("position", "absolute");
	this.css("top", ($(window).height() - this.height())/ 2 + $(window).scrollTop() + "px");
	this.css("left", ($(window).width() - this.width()) / 2 + $(window).scrollLeft() + "px");
	return this;
}

/** Only for testing **/
$(document).ready(function () {

	initSpinnerFunction( );   
	
	setTimeout(function(){
		showSpinner();
	}, 3000);
	
	// center( $('#PleaseWait') );
	setTimeout(function(){
		showErrorHide();
		
	}, 6000);
	
	
});
/** Only for testing **/


/** Pull content to centre and Display spinner **/
function showSpinner(){

	$('#PleaseWait').center(); // Pull content to centre
	
	$('#custom-Overlay').css({
								width: $(document).width(), // width same as that of document
								height: $(document).height() // height same as that of document
							}).show(); // Display spinner
}

/** Show error message('Msg') in div('DivId') inside Overlay('ID') And Hide after time('tym') using animation('fType': slow/fast ) **/
function showErrorHide( ID, DivId, Msg, tym , fType ){

	var El_ID = ID || 'custom-Overlay'; // if set Overlay then Ok else set default overlay:'custom-Overlay'
	
	var El_DivId = DivId || 'PleaseWait'; // if set div then Ok else set default div:'PleaseWait'
	
	var Err_Msg = Msg || ' Sorry, Error occured please try again after some time!! '; // if set message then Ok else set default message
	
	var Fade_tym = tym || 5000; // if set time then Ok else set default time:'5000'
	
	var Fade_fType = fType || "slow"; // if set animation then Ok else set default animation:'slow'
	
	$('#'+El_DivId ).html( Err_Msg ).center(); // Pull div containing error message to centre
	
	setTimeout(function(){ // Hide error div after given tym using animation
		$('#'+El_ID ).fadeOut( Fade_fType , function() {
			// Animation complete.
		});
	}, Fade_tym);
}

/** fadeout Spinner **/
function fadeoutSpinner(){
	$('#custom-Overlay').fadeOut( "slow" );
}

/** simply hide the Spinner **/
function hideSpinner(){
	$('#custom-Overlay').hide();
}
