function window_resized()
{
    if (window.matchMedia('(max-width: 767px)').matches){
        // header
        $('header nav .sidebar-toggler').click(function(){
          var sbar = $(this).attr('data-sidebar');
          var clside = (sbar == 'left') ? 'right' : 'left';
          
          $('header nav .sidebar-toggler.'+clside+'-bar-toggle').find('i').removeClass('fa-angle-double-'+clside).addClass('fa-angle-double-'+sbar);
          $('main .'+clside+'-side').addClass('d-none');
          
          $(this).toggleClass('prec-hdn');
          $(this).find('i').toggleClass('fa-angle-double-right fa-angle-double-left');
          
          $('main .' + sbar + '-side').toggleClass('d-none');
        });

        // close others
        $('header .search-toggler .search-box').addClass('dropdown-menu');
    } else{
      $('header .search-toggler .search-box').removeClass('dropdown-menu');
    }
}

$(document).ready(function() {
  // resize
  window_resized();
  var resizeTimer;
  $(window).on('resize', function(e) {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(function() {
      window_resized();
    }, 500);
  });

  // ------ click outside ------
  // header
  $('.navbar .search-toggler, .navbar .profile').on('show.bs.dropdown', function () {
    $('#navbarSupportedContent').collapse('hide'); 
  });
  $(document).click(function(e) {
    if (!$(".navbar-toggler").is(e.target) && $(".navbar-toggler").has(e.target).length === 0 && !$(e.target).closest('#navbarSupportedContent').length) {
        $('#navbarSupportedContent').collapse('hide');      
    }
  });
});
