/** @ Author: Hitesh Thakur **/
/** @ Date: 6 Jan 2014 **/
/** @ Plugin Name: Simple Loader plugin  **/

var TheLoadImg;
/** Create overlay div with loading image: set TheLoadImg='false' if no loading image needed  **/
function initSpinnerFunction( TheLoadImg ) {
	
	( $('#custom-Overlay').length > 0 )? $('#custom-Overlay').remove() : "" ; // remove overlay if already exists
	
	$("<div />").css({
		position: "absolute",
		display: "none",
		left: 0,
		top: 0,
		zIndex: 1000000,  // to be on the safe side
		"background-color": 'rgba(0,0,0,0.5)'
	}).attr('id','custom-Overlay').appendTo($("body"));
	
	if( TheLoadImg!='false' )
		$("#custom-Overlay").append("<div id ='PleaseWait' ><img src='"+TheLoadImg+"' alt='plaese Wait' /></div>");
		
}

/** Plugin function to pull a element to center of screen: Uses - $('SELECTOR').center() **/
jQuery.fn.center = function () {
	this.css("position", "absolute");
	//this.css("top", ($(window).height() - this.height())/ 2 + $(window).scrollTop() + "%");
	this.css("top", "25%");
	//this.css("left", ($(window).width() - this.width()) / 2 + $(window).scrollLeft() + "%");
	this.css("left", "40%");
	return this;
}




/** Pull content to centre and Display spinner **/
function showSpinner(){

	$('#PleaseWait').center(); // Pull content to centre
	
	$('#custom-Overlay').css({
								width: $(document).width(), // width same as that of document
								height: $(document).height() // height same as that of document
							}).show(); // Display spinner
}

/** simply hide the Spinner **/
function hideSpinner(){
	$('#custom-Overlay').hide();
}

/** fadeout Spinner **/
function fadeoutSpinner(){
	$('#custom-Overlay').fadeOut( "slow" );
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