// $(document).ready(function(){
   
// });
function paginate(idTarget, targetName,rowsShown) {
    
    $(idTarget).before('<div id="nav-'+targetName+'" class="scrollmenu" ></div>');
    // var rowsShown = 4;
    var rowsTotal = $(idTarget+' tbody tr').length;
    var numPages = rowsTotal/rowsShown;
    // console.log(numPages);
    $('#nav-'+targetName).append('<a href="#" class="tab-btn btn btn-outline-primer teks-primer prev disabled" style="width:unset; text-decoration: none !important;" rel="0">Prev</a> ');
    for(i = 0;i < numPages;i++) {
        var pageNum = i + 1;
        $('#nav-'+targetName).append('<a href="#" class="tab-btn btn btn-outline-primer teks-primer" style="width:unset; text-decoration: none !important;" rel="'+i+'">'+pageNum+'</a> ');
    }
    console.log($(idTarget).before);
    $('#nav-'+targetName).append('<a href="#" class="tab-btn btn btn-outline-primer teks-primer next" style="width:unset; text-decoration: none !important;" rel="1">Next</a> ');
    $(idTarget+' tbody tr').hide();
    $(idTarget+' tbody tr').slice(0, rowsShown).show();
    $('#nav-'+targetName+' a:nth-child(2)').removeClass('teks-primer');
    $('#nav-'+targetName+' a:nth-child(2)').addClass('bg-primer');
    $('#nav-'+targetName+' a').bind('click', function(){
        var currPage = $(this).attr('rel');
        $('#nav-'+targetName+' a').removeClass('bg-primer');
        $('#nav-'+targetName+' a').removeClass('teks-primer');
        $('#nav-'+targetName+' a').addClass('teks-primer');
        $('[rel='+currPage+']').addClass('bg-primer');
        $('[rel='+currPage+']').removeClass('teks-primer');
        if(currPage != '0'){
            $('.prev').removeClass('disabled');
            $('.prev').attr('rel', parseInt(currPage)-1);
        }else{
            $('.prev').addClass('disabled');
            $('.prev').attr('rel', 0);
        }
        if(currPage <= (parseInt(numPages)-2) ){
            $('.next').removeClass('disabled');
            $('.next').attr('rel', parseInt(currPage)+1);
        }else{
            $('.next').addClass('disabled');
            $('.next').attr('rel', currPage);
        }
        var startItem = currPage * rowsShown;
        var endItem = startItem + rowsShown;
        $(idTarget+' tbody tr').css('opacity','0.0').hide().slice(startItem, endItem).
        css('display','table-row').animate({opacity:1}, 300);
    });
}