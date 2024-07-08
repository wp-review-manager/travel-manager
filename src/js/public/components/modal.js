const renderModal = ($) => {
    $("#tm_openModal").click(function(){
        $("#tm_modal").show();
    });

    $("#tm_close").click(function(){
        $("#tm_modal").hide();
    });

    $(window).click(function(event){
        if (event.target.id === "tm_modal") {
            $("#tm_modal").hide();
        }
    });
};

export  {renderModal};