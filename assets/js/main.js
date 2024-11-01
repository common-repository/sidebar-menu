; (function ($) {
     $.sidebarMenu = function (el, options) {
 
         var defaults = {
            offsetTop: 90,
            offsetFooter:300,
            scrollTime: 500,
            docHeight: document.body.scrollHeight
         }
 
         var plugin = this;
 
         plugin.settings = {}
 
         var init = function () {
            plugin.settings = $.extend({}, defaults, options);
            plugin.el = el;
            plugin.el.parent().css({position : 'relative'});

            menuClick();
            onScroll();
            window.onload=function(){
                $(window).trigger('scroll');
            };
            $(window).resize(function(){
                $(window).trigger('scroll');
            });

            $('.sidebarmenu-side-icon .menu-item-has-children > a').append('<span class="submenu-icon"></span>');
         }

         var getSections = function() {
            var sections = [];
            plugin.el.find('a').each(function(i){
				var href = $(this).attr('href');
				if( 0 === href.indexOf('#') && $(href).length){
					sections[i] = href;
				}
			})

            sections = sections.filter(function (s) {return s && s.trim();});
            return sections;
         }
		 
		 var menuClick = function() {
    
            plugin.el.find('a').click(function(){
                var traget = $(this).attr('href')
                var scrollTop = $(traget).offset().top;
                if($('#wpadminbar').length) scrollTop = scrollTop - $('#wpadminbar').outerHeight();

                plugin.el.find('li').removeClass('active')
                $(this).parent('li').addClass('active')
                if( 0 === traget.indexOf('#') && $(traget).length){
                    $('html, body').animate({
                    scrollTop: scrollTop
                    }, plugin.settings.scrollTime);
                }
            })
		 }

        var activeMenuOnScroll = function() {
            var sections = getSections();
            var WindowTop = $(window).scrollTop();
            plugin.el.find('li').removeClass('active')
            if (sections.length){
                sections.forEach(function(item){

                  if(WindowTop > $(item).offset().top - 50 && 
                       WindowTop < $(item).offset().top + $(item).outerHeight(true)
                      ){
                        plugin.el.find('li').removeClass('active')
                        plugin.el.find('a[href="'+item+'"]').parent('li').addClass('active');
                    }
                })
              }
        }

        var onScroll = function (){
            $(window).scroll(function() {                
                var WindowTop = $(window).scrollTop();
                var clientHeight = document.body.clientHeight;
                var fixHeight = clientHeight > plugin.el.outerHeight()? plugin.el.outerHeight() : clientHeight;
                var offsetTop = plugin.settings.offsetTop;

                var endHeight = plugin.settings.docHeight - offsetTop - plugin.settings.offsetFooter - fixHeight;

                activeMenuOnScroll();
                
                if(el.hasClass('sidebarmenu-sticky')){
                    if (WindowTop > offsetTop && WindowTop < endHeight) {
                        plugin.el.css({
                          position : 'absolute',
                          top: (WindowTop - offsetTop)+'px' 
                        });
                    } else if( WindowTop < offsetTop){
                        plugin.el.css({
                          position: 'static',
                          top: '0px' 
                        });
                    }
                }
                
              });
         }
 
         init();
 
     }
   
     $.sidebarMenu.prototype = {
         init: function () {
         },
     };
 

     $.fn.sidebarMenu = function (options) {
         var sidebar = new $.sidebarMenu(this, options);
         return this;
     }
 
 })(jQuery);

 jQuery(document).ready(function ($) {
    $('.sidebar-menu').sidebarMenu(sidebarmenuConfig);
});