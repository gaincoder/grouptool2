var current = 100;

hideOut = function(el){
    $.ajax({
        url: '/company/random'
    }).done(function( data ) {
        $(el).animate({"opacity": 0},
            {
                duration: 1000, complete: function () {
                   current = 100;
                   $(el).html(data);
                    $(el).animate({"opacity": 1},
                        {
                            duration: 1000, complete: function () {
                                window.setTimeout("stepDown()",1000);
                            }
                        });
                }
            }
        );
    });
}


stepDown = function()
{
    current = current - 1;
    document.getElementById('networkprogress').style.width =  current + "%";
    if(current == 0){
        hideOut( document.getElementById('networkcontent') );
    }else{
        window.setTimeout("stepDown()",100);
    }
}

$(document).ready(function() {
    if( document.getElementById('networkprogress') !== null){
        stepDown();
    }
});