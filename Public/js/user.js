$(document).ready(function(){
    $(document.body).on("submit","#formUser",function(prevent){
        prevent.preventDefault();
        insert("User/insert","",new FormData($(this)[0]))
    })

    selectImg("#photo");
})