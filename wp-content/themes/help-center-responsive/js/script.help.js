$(document).ready(function() {

    // search
    $('.searchSupport .searchBtn').on(ev, function(e) {
        if ($('#searchform .searchInput').val() != "") {
            $('#searchform').submit();
        }
        e.preventDefault();
    
    });
    
    $('#searchform').on('submit',function(){
        if ($.trim($('#searchform .searchInput').val()) == "" || $('#searchform .searchInput').val() == $('#searchform .searchInput').attr('placeholder')) {
            return false;
        }
    })
    
    if(typeof(cType)!="undefined" && cType!=""){
     $('.optionContainer .check').removeClass('check');
     $('.optionContainer .type-'+cType).addClass('check');
    }
    
    if ($('.landingMenu').length > 0) {
        $('.landingMenu .child .child').each(function() {
            $('li:last', $(this)).addClass('last');
        });
    
    }
    
    //topic page banner
    if($('.sectionBanner .banner').length>0 && typeof(bannerURL)!='undefined'){
        $('.sectionBanner .banner').css('background','url("'+bannerURL+'") no-repeat');
    }
    
    if ($('.pagedList').length > 0) {
        var pl = $(this);
        var len = $('article', pl).length;
        if(len <= post_per_page){
            $('.dataChanges', pl).hide();
        }
        
        if(page == 0){
            $('.pagedList .prevLink').hide();
        }
        $('article:gt(' + (post_per_page) + ')', pl).hide();
        
        $('.pagedList .viewAll').on(ev, function() {
            $('article', pl).fadeIn();
            $('.dataChanges', pl).hide();
        });
        
        $('.pagedList .nextLink').on(ev, function(e) {
            $('.pagedList .prevLink').show();
            if ((page + 1) * post_per_page < len) {
                page += 1;
                var se = page * post_per_page+1;
                var en = page * post_per_page + post_per_page;
                if (en >= len - 1) {
                    $(this).hide();
                }
                $('article', pl).hide();
                $('article:gt(' + se + ')', pl).stop().fadeIn('slow');
                $('article:eq(' + se + ')', pl).stop().fadeIn('slow');
                $('article:gt(' + en + ')', pl).hide();
            }
            e.preventDefault();
        });
        
        $('.pagedList .prevLink').on(ev, function(e) {
            $('.pagedList .nextLink').show();
            if ((page - 1) * post_per_page >= 0) {
                page -= 1;
                var se = page * post_per_page;
                var en = page * post_per_page + post_per_page;
                if (se <= 0) {
                    $(this).hide();
                }
                $('article', pl).hide();
                $('article:gt(' + se + ')', pl).stop().fadeIn('slow');
                $('article:eq(' + se + ')', pl).stop().fadeIn('slow');
                $('article:gt(' + en + ')', pl).hide();
            }
            e.preventDefault();
        });
    }
    
    //append menu
    if($('.landingMenu .current-menu-item .communitymembers').length <= 0 ){
        if($('.landingMenu .current-menu-ancestor .communitymembers').length > 0 || $('.landingMenu .current-page-ancestor .communitymembers').length > 0){
            return false; 
        }
        if($('.leftMenu .section.topcoderuniversity li.current_page_item > a, .leftMenu .section.topcoderuniversity li.current-menu-parent > a, .leftMenu .section.topcoderuniversity li.current-menu-ancestor > a').length>0){
            return false; 
        }
        
         $('.landingMenu > li:eq(0) .child li:first').addClass('splitted');           
        $('.landingMenu .menu-item:visible:first').append($('.landingMenu > li:eq(0) .child li'));       
    }

    //video modal
    $('.ytVid').on(ev, function(e) {
        $('#playVideo #ytFrame').attr('src', $(this).attr('href'));
        showModal('playVideo');
        e.preventDefault();
    });

    /*init search input placeholder*/
    app.setPlaceholder($('.search .searchInput'));
    app.setPlaceholder($('.modal input, .modal textarea'));

    /*faqs banner function*/
    $('.faqs .banners .bannerIcons a').on(ev, function() {
        if ($(this).hasClass('active')) {
            return false;
        } else {
            var type = $.trim($(this).attr('class'));
            $(this).addClass('active').parent().siblings().children().removeClass('active');
            $(this).closest('.bannerIcons').siblings('.banner').removeClass('active').siblings('.banner.' + type).addClass('active');
            $(this).closest('.bannerIcons').siblings('.bannerNames').children('.' + type).addClass('active').siblings().removeClass('active');
        }
    });
    /*show hide email popup*/
    $('.emailSupport').on(ev, function(e) {
        $('.emailPopup').stop().slideToggle('fast');
        e.stopPropagation();
    })
    $('body').on(ev, function() {
        $('.emailPopup').stop().slideUp('fast');
        if ($('body').width() < 1020) {
            $('.leftMenu ul.section').stop().slideUp('fast');
        }
    });

    /*init search auto complete*/
    var availableTags;
    jQuery.post(
    ajaxUrl, 
    {
        'action': 'searchHints',
        'datatype': 'json'
    }, 
    function(data) {
        availableTags = data;
        $("#helpSearch1").autocomplete({
            
            
            source: function(request, response) {
                jQuery.post(ajaxUrl, {
                    'action': 'searchHints',
                    'datatype': 'json',
                    'data': {action: 'searchHints', term : request.term}
                
                }, 
                function(newData) {
                    newData = JSON.parse(newData);
                    response($.map(newData, function(item) {
                        return {label: item};
                    }));
                    
                    $('.ui-autocomplete').width($('.searchSupport .search').width()).show();
                    $('.search .loading').remove();
                }
                );
            },
            
            open: function(event, ui) {
                $('.ui-autocomplete').hide();
            },
            search: function(event, ui) {
                $('#helpSearch').parent().append('<div class="loading" style="position: absolute">Loading...</div>');
            }
        });
    }
    );

    /* $.getJSON('data/searchList.json', function(data) {
        availableTags = data;
        $( "#helpSearch" ).autocomplete({
            source: 'data/searchList.json',
            open: function(event, ui){
                $('.ui-autocomplete').hide();
                setTimeout(function(){
                    $('.ui-autocomplete').width($('.searchSupport .search').width()).show();
                },1000);
            },
            search: function( event, ui ) {
                $('#helpSearch').parent().append('<div class="loading" style="position: absolute">Loading...</div>');
                setTimeout(function(){
                    $('.search .loading').remove();
                },1000);
            }
        });
    });
    $.ui.autocomplete.prototype._renderItem = function(ul, item) {
        
        var reg = new RegExp('(' + this.term + ')', 'gi');
        setTimeout(function() {
            $('.ui-autocomplete li').each(function(i, elem) {
                var text = $(this).children().text();
                new_val = text.replace(reg, '<span style="font-weight: bold">$1<\/span>');
                $(this).children().html(new_val);
            });
        }, 100);
        return $("<li></li>")
        .data("item.autocomplete", item)
        .append("<a>" + item.label + "</a>")
        .appendTo(ul);
    };
    */ 
    $('.leftMenu ul:first-child li a').on('click', function() {
        if ($('body').width() < 1020 && $('body').hasClass('home')) {
            var section = $(this).attr('class');
            $(this).closest('.leftMenu').find('ul.' + section).siblings('.section').hide();
            $(this).closest('.leftMenu').find('ul.' + section).stop().slideToggle();
            return false
        }
    });
    
    $(window).resize(function() {
        if ($('body').width() < 1020) {
            $('.leftMenu .section').removeClass('desk');
        } else {
            $('.leftMenu .section').addClass('desk');
        }
        setModalPosition();
    }).resize();

    /*mobile section show hide*/
    /*faqs banner function*/
    $('.sectionMenu li a').on(ev, function() {
        if ($(this).hasClass('active')) {
            var type = $.trim($(this).attr('class').split(" ")[0]);
            if (!$('.section.' + type).hasClass('desk')) {
                $('.section.' + type).stop().slideToggle('fast');
            }
            return false;
        }
    });

    /*views change*/
    $('.views a').on(ev, function(e) {
        if ($(this).hasClass('isActive')) {
            return false;
        }
        $('.viewTab').hide();
        id = $(this).attr('href');
        $(id).fadeIn('fast');
        $('.isActive', $(this).parent()).removeClass('isActive');
        $(this).addClass('isActive');
        return false;
    });

    /*option button*/
    $('.optionContainer .optionBtn').on(ev, function() {
        $(this).addClass('check').siblings().removeClass('check');
    });

    /*ask Qeustion popup*/
    $('.faqList .question').on(ev, function() {
        showModal("askQuestionPopup");
    });
    $('.banner .submitTip').on(ev, function() {
        showModal("submitTip");
    });
    /*close modal*/
    $('.modalClose, .modalOverlay').on(ev, function() {
        closeModal();
    });
    /*submit modal*/
    $('.modal .submitBtn').on(ev, function() {
        
        if (validateModal($(this).closest('.modal'))) {
            app.setLoading();
            setTimeout(function() {
                $('.loading').hide();
                showModal('submitSuccess');
            }, 2000);
            closeModal();
        }
    });

    /*initialize custom select*/
    if ($('.modal select').length > 0) {
        $('.modal select').selectbox();
    }

    /*textarea character count*/
    $('.inputCnt textarea').keyup(function() {
        var charLength = $(this).val().length;
        $(this).siblings('.charLeft').find('.count').html(255 - charLength);
        $(this).closest('.inputCnt').removeClass('error');
    }).keydown(function(e) {
        if (e.keyCode == 8 || e.keyCode == 46) {
            return;
        }
        if ($(this).val().length > 254) {
            return false;
        }
    }).bind('paste', function(e) {
        var element = this;
        setTimeout(function() {
            var text = $(element).val();
            if (text.length > 255) {
                $(element).val(text.substr(0, 255));
            }
        }, 100);
    });
    $('.inputCnt input[type=text]').keyup(function() {
        $(this).closest('.inputCnt').removeClass('error');
    });

    /*add zebra style*/
    $('.subSection.designPage #tableView article:odd,.subSection.subTopics #tableView article:odd, .subSection.sectionCopilot #tableView article:odd, .subSection.faqDetails #tableView article:odd, .subSection.memberTip #tableView article:odd, .dataTable.university tbody tr:odd, .dataTable.topics tbody tr:odd, .sectionBanner .dataTable tbody tr:odd').addClass('alt');
    $('.subSection.faqsSection #tableView article:even, .subSection.searchResult #tableView article:even').addClass('alt');

    if($(".current-menu-item").length == 0 && $(".landingMenu").length > 0){
      $(".landingMenu").addClass("page-not-included-in-menu");
    }
});

/*show modal*/
function showModal(id) {
    resetModalForm(id);
    $('.modal').hide();
    $('.modalOverlay').show().css({opacity: .4});
    $('#' + id).show();
    setModalPosition();
}

/*modal positioning*/
function setModalPosition() {
    var activeModal = $('.modal:visible');
    var scrollTop = $(window).scrollTop();
    var width = $(activeModal).width();
    var height = $(activeModal).height();
    $(activeModal).css({marginLeft: -(width / 2),marginTop: -1 * (height / 2)});
}
/*close modal*/
function closeModal() {
    $('.modal:visible #ytFrame').removeAttr('src');
    $('.modal, .modalOverlay').hide();
}

/*validate modal*/
function validateModal(modal) {
    var valid = true;
    $(modal).find('input, textarea').each(function(i, elem) {
        if (($.trim($(this).val()) == "") || ($(this).val() == $(this).attr('placeholder'))) {
            $(this).closest('.inputCnt').addClass('error');
            valid = false;
        } else {
            $(this).closest('.inputCnt').removeClass('error');
        }
    });
    $(modal).find('select').each(function(i, elem) {
        if ($(this).prop("selectedIndex") == 0) {
            $(this).next('.sbHolder').addClass('error');
            valid = false;
        } else {
            $(this).next('.sbHolder').removeClass('error');
        }
    });
    return valid;
}

/*reset modal form*/
function resetModalForm(id) {
    $('#' + id).find('input, textarea').each(function(i, elem) {
        $(this).val('').focus();
    });
    $('#' + id).find('select').each(function() {
        $(this).val($(this).find('option:first').val());
        $(this).selectbox('detach').selectbox({
            onChange: function(val, inst) {
                inst.input.next('.sbHolder').addClass('changed').removeClass('error');
            }
        });
        /*z-index fix*/
        $(function() {
            setTimeout(function() {
                var zIndexNumber = 10000;
                $('.modal div').each(function() {
                    $(this).css('zIndex', zIndexNumber);
                    zIndexNumber -= 10;
                });
            }, 1000)
        });
    });
    $('#' + id).find('.inputCnt').removeClass('error');
    app.setPlaceholder($('.modal input, .modal textarea'));
}
