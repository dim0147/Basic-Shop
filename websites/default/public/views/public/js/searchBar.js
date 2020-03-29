$(document).on("keypress", "#searchBar", function(e){
    //  Hit enter
    if(e.which == 13){
        const val = $(this).val();
        if(val == ""){
            alert("Please enter some string to search");
            return;
        }
        window.location.href = "/product/search/string?s=" + val;
    }
});