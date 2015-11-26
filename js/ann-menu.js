/* $(document).ready(function() {
	$('select[name="inverse-dropdown"], select[name="inverse-dropdown-optgroup"], select[name="inverse-dropdown-disabled"]').select2({dropdownCssClass: 'select-inverse-dropdown'});

        $('select[name="searchfield"]').select2({dropdownCssClass: 'show-select-search'});
        $('select[name="inverse-dropdown-searchfield"]').select2({dropdownCssClass: 'select-inverse-dropdown show-select-search'});
        $('.tootlip.ann-shower').draggable();
}); */
function ToogleSlideCitazione(){
	var elemen = document.getElementById("gn-icon-open-main-cites");
	classie.toggle(elemen, 'gn-icon-opendown');
	classie.toggle(elemen, 'gn-icon-openup');
	$("#cites-checkbox").slideToggle('fast');
}
function ToogleSlideRetorica(){
  var elemen = document.getElementById("gn-icon-open-main-retoric");
  var elemen2 = document.getElementById("retoric-checkbox");
  classie.toggle(elemen, 'gn-icon-opendown');
  classie.toggle(elemen, 'gn-icon-openup');
  $("#retoric-checkbox").slideToggle('fast');
}
function ToggleFilter(){
  //$("#filter-menu").toggleClass("show");
  $("#filter-menu").toggle( "slide" );
}
  $(document).mousedown(function (e){
    var container = $('#filter-menu');
    var btnFilter = $('#filter');
    if (!container.is(e.target) && container.has(e.target).length === 0 && !btnFilter.is(e.target) && btnFilter.has(e.target).length === 0)
      if(!$('#filter').is(':hidden'))
        if(!$('#filter-menu').is(':hidden')){
          $("#filter-menu").toggle( "slide" );
        }
   });