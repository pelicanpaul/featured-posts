

function truncString(str, max, add){
    add = add || '...';
    return (typeof str === 'string' && str.length > max ? str.substring(0,max)+add : str);
}

jQuery(document).ready(function($) {

    jQuery('.post-title').each(function(){

        var str = jQuery(this).text();

        jQuery(this).text(truncString(str, 60, '...'));
    });



});

