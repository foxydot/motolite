jQuery(document).ready(function($) {	
    $('*:first-child').addClass('first-child');
    $('*:last-child').addClass('last-child');
    $('*:nth-child(even)').addClass('even');
    $('*:nth-child(odd)').addClass('odd');
    
    $('.footer-widgets .widget.msd-connected .wrap').css('cursor','pointer').click(function(e){
        if(e.currentTarget == e.target){
        var siteRoot = window.location.origin?window.location.origin+'/':window.location.protocol+'/'+window.location.host+'/';
        //alert(siteRoot); //troubleshooting
        window.location.assign(siteRoot);
        }
    });
    
    $('.buddypress.media .entry-title').html(function(){
        var title = $('#buddypress #item-body .rtm-gallery-title');
        if(title.html() != "")
            title.hide();
            return title.html();
    });
	
    var numwidgets = $('.footer-widgets section.widget').length;
    $('.footer-widgets').addClass('cols-'+numwidgets);
    $('.footer-widgets widget-area').addClass('row');
    var cols = 12/numwidgets;
    $('.footer-widgets section.widget').addClass('col-sm-'+cols);
    $('.footer-widgets section.widget').addClass('col-xs-12');
    
	$.each(['show', 'hide'], function (i, ev) {
        var el = $.fn[ev];
        $.fn[ev] = function () {
          this.trigger(ev);
          return el.apply(this, arguments);
        };
      });

	$('.nav-footer ul.menu>li').after(function(){
		if(!$(this).hasClass('last-child') && $(this).hasClass('menu-item') && $(this).css('display')!='none'){
			return '<li class="separator">|</li>';
		}
	});
	
	$('.section.expandable .expand').click(function(){
	    var target = $(this).parents('.section-body').find('.content');
	    console.log(target);
	    if(target.hasClass('open')){
            target.removeClass('open');
            $(this).html('MORE <i class="fa fa-angle-down"></i>');
	    } else {
	        target.addClass('open');
	        $(this).html('LESS <i class="fa fa-angle-up"></i>');
	    }
	});
	
});