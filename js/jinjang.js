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


//Using additional plugins for: date and time picker and slider in add new training page and some other pages (edit training)
if(typeof $.fn.datetimepicker !== 'undefined')
$('.datetimepicker').datetimepicker();
if(typeof $.fn.slider !== 'undefined')
$(".slider").slider({tooltip: 'always',tooltip_position: 'bottom'});
if(typeof $.fn.rating !== 'undefined')
$(".ratinginput").rating({step: 1});