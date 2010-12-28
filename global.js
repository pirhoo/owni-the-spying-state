
function yearLabel(value) {
    var l = $(".ui-slider-handle", "#range").position().left;
    if(value == 2001 || value == 2010)
        $("#year-label").stop().animate({left: l, opacity: 0}, 200);
    else {
        $("#year-label").stop().animate({left: l, opacity: 1}, 200).html(value);
    }

    lib = value;
    force.reset();
    vis.render();
}


$(document).ready(function () {


    $(function() {
        var slider = $("#range").slider({
                                        animate: false,
                                        min  :2001,
                                        max  :2010,
                                        step :1,
                                        value:2001,
                                        stop: function (event, ui) {
                                            yearLabel(ui.value);
                                        }
                                    });
    });

    $("#layout").click(function () {
        alert($("#layout > span").html());
        $("#ie-render").html( $("#layout > span").html() );

            // reset the display mode
            setMode(0);

            // remove any SVG OBJECTs already on the page
            removeObjects();

    });
    
    //yearLabel(2001);
});