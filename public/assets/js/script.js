$(document).on('click', '.closeIcon', function () {
    $(this).children('i').toggleClass('fa-circle-o').toggleClass('fa-dot-circle-o');
    $('.sidebar').toggleClass('active');
    $('.main').toggleClass('active');
});
$(document).on('click', '.closeIcon2', function () {
    $('.closeIcon').children('i').toggleClass('fa-circle-o').toggleClass('fa-dot-circle-o');
    $('.sidebar').toggleClass('active');
    $('.main').toggleClass('active');
});
$(document).on('click', '.closeBtn3', function () {
    $('.exprotBtn').trigger('click');
});
$(document).on('click', '.searchBtn', function () {
    $(this).siblings('.search_container').slideToggle(200);
});
$(document).on('click', '.closeBtn', function () {
    $('.search_container').slideUp();
});

$(document).on('click', '.dropBtn', function () {
    $('.dropdown_container').fadeOut(300);
    var dropdownContainer = $(this).siblings('.dropdown_container');

    if (dropdownContainer.css('display') == "none") {
        dropdownContainer.fadeIn(500);
    }
    else if (dropdownContainer.css('display') == 'block') {
        $(this).find("i").addClass("fa-close");
        dropdownContainer.fadeOut(300);
    }
});

$(document).on('click', '#drop-down', function () {
    $('.dropdown_container').fadeOut(300);
    var dropdownContainer = $(this).siblings('.dropdown_container');

    if (dropdownContainer.css('display') == "none") {
        dropdownContainer.fadeIn(500);
    }
    else if (dropdownContainer.css('display') == 'block') {
        $(this).find("i").addClass("fa-close");
        dropdownContainer.fadeOut(300);
    }
});

$(document).on('click', '.tableAction>.dropBtn', function () {
    $(this).children('i').toggleClass('fa-ellipsis-v').toggleClass('fa-close');
});


$(document).on('click', '.navdownBtn', function () {
    $(this).toggleClass('active');
    $(this).siblings('.navdown_container').slideToggle(300);
});
// --------------------Export--------------
$(document).on('click', '.exprotBtn', function () {
    $(this).siblings('.export_container').slideToggle(300);
});
// --------------------Export--------------

// --------------------filterBox--------------
$(document).on('click', '.filterIcon', function () {
    $(this).parent('.flex').siblings('.filterBox_container').slideToggle(300);
});
// --------------------filterBox--------------


// -----------select box-----------//
// $(document).on('click', '.selectBoxBtn > .drops',function(){
//     $('.drops').removeClass('active');
//     $('.selectBox_Container').slideUp(300);
//     var selectBoxContainer =  $(this).parent('.selectBoxBtn').siblings('.selectBox_Container');
//     if(selectBoxContainer.css('display') == "none"){
//         selectBoxContainer.slideDown(300);
//         $(this).addClass('active');
//     }
//     else if(selectBoxContainer.css('display') == 'block'){
//         selectBoxContainer.slideUp(0);
//         $(this).removeClass('active');
//     }

// });
// var  inputValue = $('.selectBoxBtn > input');
// var selectBoxLabel = $('.selectBox_Container > li');

// selectBoxLabel.on('click', function(){
//     selectBoxLabel.removeClass('active');
//     $(this).toggleClass('active');
//     $(this).parent('.selectBox_Container').siblings('.selectBoxBtn').children('.textFlow').text($(this).text());
//     $(this).parent('.selectBox_Container').siblings('.selectBoxBtn').children('input').val($(this).text());
//     $(this).parent('.selectBox_Container').siblings('.selectBoxBtn').children('.drops').removeClass('active');
//     $(this).parent('.selectBox_Container').siblings('.selectBoxBtn').children('.close').fadeIn();
//     $(this).parent('.selectBox_Container').slideUp(300);
// });

// $(document).on('click', '.selectBoxBtn .close',function(){
//     selectBoxLabel.removeClass('active');
//     $(this).fadeOut();
//     $(this).parent('.selectBoxBtn').siblings('.selectBox_Container').slideUp(300);
//     $(this).parent('.selectBoxBtn').siblings('.selectBox_Container').children('li').removeClass('active');
//     $(this).siblings('.drops').removeClass('active');
//     $(this).siblings('.textFlow').text($(this).siblings('.textFlow').attr('data-default'));
//     $(this).siblings('input').val('');
// });

// -----------select box-----------//

// --------multi select----------------

// $(document).on('click','.drops2', function(){
//      $(this).addClass('active');
//      $('.MultiselectBox_Container').fadeOut(300);
//      var MultiselectBoxContainer =  $(this).parents('.multiSelectBox').children('.MultiselectBox_Container');
//      if(MultiselectBoxContainer.css('display') == "none"){
//         MultiselectBoxContainer.slideDown(300);
//          $(this).addClass('active');
//      }
//      else if(MultiselectBoxContainer.css('display') == 'block'){
//         MultiselectBoxContainer.fadeOut(300);
//          $(this).removeClass('active');
//      }
// });

// $(document).on('click','.multiBox_label', function(event){

//     var valueOfID = $(this).children('input');
//     var txtContainer = $(this).parents('.multiSelectBox').find('.Multi_InputContainer');
//     var multiSelectInput = $(this).parents('.multiSelectBox').find('.multiSelectInput');
//     var arr = [];
//     if(!multiSelectInput.val() == " "){
//         arr = multiSelectInput.val().split(',');
//     }
//     if (this === event.target) {
//         event.stopPropagation();
//       } 
//       else if(valueOfID.prop('checked') == false){
//          var a =  $(this).text()+',';
//          var e =  $(this).children('input').attr('id');
//          txtContainer.children('span').each(function(){
//              if($(this).text() == a){
//                  $(this).remove();
//              }
//          });
//          for(i=0;i<arr.length;i++){
//             if(arr[i] === e){
//                 arr.splice(i, 1); 
//                 var c = arr.join(',');
//                 multiSelectInput.val(c);
//                 console.log(multiSelectInput.val(c));
//             }
//         }

//         $(this).removeClass('active');

//       }
//       else if(valueOfID.prop('checked') == true){
//          var mm = $(this).text();
//          var para = '<span>'+ mm +','+'</span>';
//          txtContainer.append(para);

//          var id = $(this).children('input').attr('id');
//          arr.push(id);
//          var c = arr.join(',');
//          multiSelectInput.val(c);

//          $(this).addClass('active');
//       }

// });

// --------multi select----------------
function toggleFullScreen() {
    if (!document.fullscreenElement) {
        // If not in fullscreen mode, request fullscreen
        if (document.documentElement.requestFullscreen) {
            document.documentElement.requestFullscreen();
        } else if (document.documentElement.mozRequestFullScreen) { // Firefox
            document.documentElement.mozRequestFullScreen();
        } else if (document.documentElement.webkitRequestFullscreen) { // Chrome, Safari and Opera
            document.documentElement.webkitRequestFullscreen();
        } else if (document.documentElement.msRequestFullscreen) { // IE/Edge
            document.documentElement.msRequestFullscreen();
        }
    } else {
        // If in fullscreen mode, exit fullscreen
        if (document.exitFullscreen) {
            document.exitFullscreen();
        } else if (document.mozCancelFullScreen) { // Firefox
            document.mozCancelFullScreen();
        } else if (document.webkitExitFullscreen) { // Chrome, Safari and Opera
            document.webkitExitFullscreen();
        } else if (document.msExitFullscreen) { // IE/Edge
            document.msExitFullscreen();
        }
    }
}

$(document).on('click', '.navLink', function () {
    $('.navLink').removeClass('active');
    $(this).toggleClass('active');
});

$(document).on('click', '.fullscreen', function () {    
    $('.fullscreen .fa').toggleClass('fa-arrows-alt');
    $('.fullscreen .fa').toggleClass('fa-compress');
    toggleFullScreen();
});

$(window).on('load bind resize', function () {
    var width = $(window).width();

    if (width > 992) {
        $('.closeIcon').children('i').removeClass('fa-circle-o').addClass('fa-dot-circle-o');
        $('.sidebar').addClass('active');
        $('.main').addClass('active');
    }
    else if (width <= 992) {
        $('.closeIcon').children('i').addClass('fa-circle-o').removeClass('fa-dot-circle-o');
        $('.sidebar').removeClass('active');
        $('.main').removeClass('active');
    }
});

// $(document).on("click", function(event){
//     var $trigger = $(".selectBox");
//     if($trigger !== event.target && !$trigger.has(event.target).length){
//         $(this).find('.selectBox_Container').slideUp(300);
//         $(this).find('.drops ').removeClass('active');
//     }  
//     var $dropdown = $('.dropdown');
//     if($dropdown !== event.target && !$dropdown.has(event.target).length){
//         $(this).find('.dropdown_container').fadeOut(300);
//         $(this).find('.tableAction').find('.dropBtn').children('i').addClass('fa-ellipsis-v').removeClass('fa-close');
//     }

//     var $dropdown = $('.multiSelectBox');
//     if($dropdown !== event.target && !$dropdown.has(event.target).length){
//         $(this).find('.MultiselectBox_Container').fadeOut(300);
//         $(this).find('.drops2').removeClass('active');
//     }
// });

$(window).on('scroll', function () {
    var position = $(window).scrollTop();

    if (position == 0) {
        $('.topNav').removeClass('active');
    }
    else if (position > 0) {
        $('.topNav').addClass('active');
    }
});

// $(window).on('load', function(){
//      var navlink = $('.navLink');
//      navlink.removeClass('active');
//      var urls = location.href;
//      var mm = urls.split('/');
//     var value = mm[mm.length-1];
//      navlink.each(function(){
//         if(value == $(this).attr('href')){
//             $(this).addClass('active');
//            $(this).parents('.navdown_container').slideDown(300);
//         }
//      });

// });

// -------modal--------

// $(document).on('click', '.modalClose',function(){
//     $(this).parents('.modal').fadeOut(300);
// });

// $(document).on('click','.modalBtn', function(){
//      var dataTarget = $(this).attr('data-target');
//      $('.modal').each(function(){
//         if($(this).attr('id') == dataTarget){
//             $(this).fadeIn(300);
//         }
//      });
// });

// $(document).mouseup(function (e) {
//     if ($(e.target).closest(".modalbody").length === 0) {
//         $(".modal").hide();
//     }
// });

// -------modal--------

$(document).on('click', '.password > a', function () {
    var input = $(this).siblings('input');
    if (input.attr('type') == 'password') {
        input.attr('type', 'text');
    }
    else if (input.attr('type') == 'text') {
        input.attr('type', 'password');
    }

    $(this).children('i').toggleClass('fa-eye').toggleClass('fa-eye-slash')
});


// $(document).on('click', '.ModalAlert > a', function(){
//     $(this).parents('.ModalAlert').fadeOut(300);
// });

// ----------summa-----------
$(document).on('click', '.alertBtn', function () {
    $('.ModalAlert').fadeIn(300);
});
// ----------summa-----------

// ----tab-------
$(document).on('click', '.tab_nav_item', function () {
    $('.tab_nav_item').removeClass('active');
    $(this).toggleClass('active');
    var src = $(this).attr('data-src');
    $('.tab_pane').each(function () {
        if ($(this).attr('id') == src) {
            $('.tab_pane').removeClass('active');
            $(this).addClass('active');
        }
    });
});

// ----tab-------
//----delete--confirm------------
$(document).on('click', '.del-confirm', function (evt) {
    let del_yes = window.confirm("Are you sure ? Attention: You're about to delete confidential data. This action is irreversible. Please confirm by Clicking 'OK' to proceed.");
    if (!del_yes) {
        evt.preventDefault();
        evt.stopImmediatePropagation();
        evt.stopPropagation();
    }
});

//----Search_option------------
$(document).on('click', '.searchBox', function (evt) {
    console.log("hello there !");
        evt.preventDefault();
        evt.stopImmediatePropagation();
        evt.stopPropagation();
});

$(document).on('click', '.timesheet_icon', function () {
    $('.timer_dropdown').toggleClass('show');
});

$(".notifier-invoker").on("click", function () {
    if ($(".fa-solid.fa-bell").hasClass('fa-close')) {
        setTimeout(function () {
            $(".fa-solid.fa-bell").removeClass("fa-close");
        }, 100);
    }
});
