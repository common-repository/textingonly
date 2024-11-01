jQuery.noConflict();

function toSetUpColorPickers() {
  if ( jQuery( ".to--colorpicker" )[0] ) {
    window.setTimeout(function(){
      jQuery( ".to--colorpicker" ).wpColorPicker();
    }, 900);
  }
}

jQuery( document ).ready(function($) {

  toSetUpColorPickers();

  $(document).on( "click", "#to-update-qrcs", function(e) {
    e.preventDefault();
		var the_axa = 'textingonly_qq';
		var ajaxurl = forajax.ajaxurl;
  	var to_non  = forajax.security;
    var ttodata = {
      'action'   : the_axa,
      'security' : to_non
    };
    $.get(ajaxurl, ttodata, function(json){ 
      var res = JSON.parse( json );
      if ( 'success' == res.status ) {
        $('#text-us-now-response').addClass('py-5 block text-sky-900').css('color','#006699').text(res.reason + ' Reloading ...');
      } else {
        $('#text-us-now-response').addClass('py-5 block text-red-900').text(res.reason + ' Reloading ...');
      }
    });
    window.setTimeout(function(){location.reload()},2700);
  });

  window.setTimeout(function(){
    $( ".to--colorpicker" ).wpColorPicker(
      'option',
      'change',
      function(event, ui) {
        let theid = $(this).attr('id');
        let newcolor = ui.color.toString();
        let input = $('#' + theid)[0]; 
        let lastValue = input.value;
        input.value = newcolor;
        let eventr = new Event('input', { bubbles: true });
        eventr.simulated = true;
        let tracker = input._valueTracker;
        if (tracker) {
          tracker.setValue(lastValue);
        }
        input.dispatchEvent(eventr);
      }
    );
    $( "li#toplevel_page_textingonly ul.wp-submenu a[href='https://www.textingonly.com/upgrade']" ).attr( 'target', '_blank' ).attr( 'rel', 'noopener' );
    $( "li#toplevel_page_textingonly ul.wp-submenu a[href='https://www.textingonly.com/wordpress-plugin']" ).attr( 'target', '_blank' ).attr( 'rel', 'noopener' );
  }, 1800);

});
