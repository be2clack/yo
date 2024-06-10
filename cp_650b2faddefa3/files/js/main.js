$(document).ready(function() {
    
    var url_path = $("body").data("prefix");

    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $(window).on("load", function () {
        $(".loader").fadeOut();
        $("#preloader").fadeOut("slow")
    });
    $("#toggle-btn").on("click", function (b) {
        b.preventDefault();
        $(this).toggleClass("active");
        $(".side-navbar").toggleClass("shrinked");
        $(".content-inner").toggleClass("active");
        if ($(window).outerWidth() > 1183) {
            if ($("#toggle-btn").hasClass("active")) {
                $(".navbar-header .brand-small").hide();
                $(".navbar-header .brand-big").show()
            } else {
                $(".navbar-header .brand-small").show();
                $(".navbar-header .brand-big").hide()
            }
        }
        if ($(window).outerWidth() < 1183) {
            $(".navbar-header .brand-small").show()
        }
    });
    $(function () {
        $(".side-navbar > ul > li > a").click(function (b) {
            $(".collapse").collapse("hide")
        })
    });

    $(function () {
        $(window).scroll(function () {
            if ($(this).scrollTop() > 350) {
                $(".go-top").fadeIn(100)
            } else {
                $(".go-top").fadeOut(200)
            }
        });
        $(".go-top").click(function (b) {
            b.preventDefault();
            $("html, body").animate({
                scrollTop: 0
            }, 800)
        })
    });
    $(".checkbox").click(function () {
        $(this).toggleClass("is-checked")
    });
    $("#check-all").change(function () {
        $("input:checkbox").prop("checked", $(this).prop("checked"))
    });

    $(".table-scroll").niceScroll({
        railpadding: {
            top: 0,
            right: 0,
            left: 0,
            bottom: 0
        },
        scrollspeed: 100,
        zindex: "auto",
        autohidemode: "leave",
        cursorwidth: "4px",
        cursorcolor: "rgb$(52, 40, 104, 0.1)",
        cursorborder: "rgb$(52, 40, 104, 0.1)"
    });
    $(".offcanvas-scroll").niceScroll({
        railpadding: {
            top: 0,
            right: 2,
            left: 0,
            bottom: 0
        },
        scrollspeed: 100,
        zindex: "auto",
        hidecursordelay: 800,
        cursorwidth: "3px",
        cursorcolor: "rgb$(52, 40, 104, 0.1)",
        cursorborder: "rgb$(52, 40, 104, 0.1)",
        preservenativescrolling: true,
        boxzoom: false
    });

    $(function () {
        $(".open-sidebar").on("click", function (b) {
            b.preventDefault();
            $(".off-sidebar").addClass("is-visible")
        });
        $(".off-sidebar").on("click", function (b) {
            if ($(b.target).is(".off-sidebar") || $(b.target).is(".off-sidebar-close")) {
                $(".off-sidebar").removeClass("is-visible");
                b.preventDefault()
            }
        })
    });
    $(function () {
        $("#delay-modal").on("show.bs.modal", function () {
            var b = $(this);
            clearTimeout(b.dat$("hideInterval"));
            b.dat$("hideInterval", setTimeout(function () {
                b.modal("hide")
            }, 2500))
        })
    })

  $(".cash-clear").click(function () {
    $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "action=admin/cash-clear",dataType: "html",cache: false,
        success: function (data) {
          location.reload();
        }
    });        
  });


 $(document).on('change','.toolbat-toggle', function () {      
    $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: $(".quick-settings-form").serialize()+"&action=admin/quick-settings",dataType: "html",cache: false,
        success: function (data) {}
    });   
 });

  $(".widget-scroll").niceScroll({
    railpadding: {
      top: 0,
      right: 3,
      left: 0,
      bottom: 0
    },
    scrollspeed: 100,
    zindex: "auto",
    autohidemode: "leave",
    cursorwidth: "4px",
    cursorcolor: "rgba(52, 40, 104, 0.1)",
    cursorborder: "rgba(52, 40, 104, 0.1)"
  });
  
 $(document).on('click','.setting-tab-toggle', function () {      
    $(".setting-tab-content[data-tab="+$(this).data("tab")+"]").toggle();
 });




});     