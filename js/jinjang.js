//stick header to top on scroll
var stickTheHeader =  function(evt) {
	var doc = document.documentElement;
	var top = (window.pageYOffset || doc.scrollTop)  - (doc.clientTop || 0);
	var nav = document.getElementById("nav");

	if (top>0){
		nav.parentElement.style.height=nav.clientHeight+'px';
		nav.parentElement.classList='stickTheHeader';
	}else{
		nav.parentElement.style.height='';
		nav.parentElement.classList='';
	}
};

//when menu button clicked on mobile, correct header placement after 0.5 seconds
function stickTheHeaderBtn(evt){
	window.setTimeout('stickTheHeader("");',500);

}

//bind height calculator for sticky scroll header effect to window and menu button for mobile
document.getElementById("tabMobile").addEventListener("click",stickTheHeaderBtn);
window.addEventListener("scroll", stickTheHeader);
window.addEventListener("resize", stickTheHeader);

//choose trainer button on registration form
function btnregJP(){
	document.getElementById("btnregtrainer").classList="regtypebtn btn btn-sm btn-default col-xs-6 noround btnregtrainer";
	document.getElementById("btnregmember").classList="regtypebtn btn btn-sm btn-success col-xs-6 noround btnregmember";
	document.getElementById("bigicon").classList="fa fa-slideshare";
	document.getElementById("bigicon1").classList="fa fa-user-circle-o";
	document.getElementById("userType").value="jp";
	document.getElementById("reg-form").style.display="block";
	document.getElementById("trainerelement").style.display="table";
	document.getElementById("memberelement").style.display="none";
}

//choose member button on registration form
function btnregJF(){
	document.getElementById("btnregtrainer").classList="regtypebtn btn btn-sm btn-primary col-xs-6 noround btnregtrainer";
	document.getElementById("btnregmember").classList="regtypebtn btn btn-sm btn-default col-xs-6 noround btnregmember";
	document.getElementById("bigicon").classList="fa fa-slideshare";
	document.getElementById("bigicon1").classList="fa fa-user-circle-o";
	document.getElementById("userType").value="jf";
	document.getElementById("reg-form").style.display="block";
	document.getElementById("memberelement").style.display="table";
	document.getElementById("trainerelement").style.display="none";
}


//choose make sure sign up form is filled up correctly
function validateSignupForm(){
	if (document.getElementById("psw").value!=document.getElementById("psw2").value){
			bootbox.alert("Password and new password is different They must be same.");
			return false;
	}
	if (document.getElementById("userType").value=='jf')
	{
		if (document.getElementById("experienceHistory").value=='')
		{
			bootbox.alert("You need to type your experience history.");
			return false;
		}
		if (document.getElementById("expectedSalary").value=='')
		{
			bootbox.alert("You need to type your expected salary.");
			return false;
		}
		if (document.getElementById("skills").value=='')
		{
			bootbox.alert("You need to type your skills.");
			return false;
		}
		if (document.getElementById("educationLevel").value=='')
		{
			bootbox.alert("You need to choose your education level.");
			return false;
		}

	}else{
		if (document.getElementById("companyName").value=='')
		{
			bootbox.alert("You need to enter company name.");
			return false;
		}
		if (document.getElementById("companyAddress").value=='')
		{
			bootbox.alert("You need to enter company address.");
			return false;
		}
		if (document.getElementById("position").value=='')
		{
			bootbox.alert("You need to enter your position in your company.");
			return false;
		}
	}
	return true;
}

//make sure member edit form is filled up correctly
function validateMemberForm(){
	if (document.getElementById("psw").value!=document.getElementById("psw2").value){
			bootbox.alert("Password and new password is different, They must be same.");
			return false;
	}

	if (document.getElementById("memberlevel").value=='')
	{
		bootbox.alert("You need to choose member level first.");
		return false;
	}
	
	return true;
}

//make sure trainer edit form is filled up correctly
function validateTrainerForm(){
	if (document.getElementById("psw").value!=document.getElementById("psw2").value){
			bootbox.alert("Password and new password is different, They must be same.");
			return false;
	}

	if (document.getElementById("trainerspecialty").value=='')
	{
		bootbox.alert("You need to choose trainer specialty first.");
		return false;
	}
	

	return true;
}

//on edit session and add new session pages, show participation field based on client choice
function applyTrainingType(){
	if (document.getElementById("trainingmode").value=='Group'){
		document.getElementById("grouptraining1").style.display="none";
		document.getElementById("grouptraining2").style.display="block";

	}else{
		document.getElementById("grouptraining1").style.display="block";
		document.getElementById("grouptraining2").style.display="none";
	}
}




//Using additional plugins for: date and time picker and slider in add new training page and some other pages (edit training)
if(typeof $.fn.datetimepicker !== 'undefined')
$('.datetimepicker').datetimepicker();
if(typeof $.fn.slider !== 'undefined')
$(".slider").slider({tooltip: 'always',tooltip_position: 'bottom'});
if(typeof $.fn.rating !== 'undefined')
$(".ratinginput").rating({step: 1});


function forgotpassword(){
	if ($("#username").val()==""){
		bootbox.alert("Please enter your username above and click forgot password button again.");
	}else{
		window.location.href="?forgot="+$("#username").val();
	}
}