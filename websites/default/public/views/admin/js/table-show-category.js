$(document).ready( function () {
    const dataTable = $('#table-category').DataTable({});
    $('.rmvbtn').click(function(){
        const row = dataTable.row($(this).parents('tr'));
        const dataRow = row.data();
        if(!Array.isArray(dataRow) || dataRow.length !== 4){
            showError('Incorrect data!');
        }
        idCategory = dataRow[0];
        $.ajax({
            url: '/admin/post/delete-category',
            method: 'POST',
            data: {
                id: idCategory
            },
            success: function (response) {
                showSuccess(response);
                row.remove();
                row.draw();
            },
            error: function (response) {
                showError(response.responseText);
            }
        });
        console.log(idCategory);
    });
} );


function showError(errorMsg){
    var errorDiv = '<div class="alert alert-danger alert-dismissible fade show" role="alert">'+
'  <strong> ' + errorMsg + '</strong>'+
'  <button type="button" class="close" data-dismiss="alert" aria-label="Close">'+
'    <span aria-hidden="true">×</span>'+
'  </button>'+
'</div>';
    $('.nofi').empty();
    $('.nofi').append(errorDiv);
}

function showSuccess(successMsg){
    var errorDiv = '<div class="alert alert-success alert-dismissible fade show" role="alert">'+
'  <strong> ' + successMsg + '</strong>'+
'  <button type="button" class="close" data-dismiss="alert" aria-label="Close">'+
'    <span aria-hidden="true">×</span>'+
'  </button>'+
'</div>';
    $('.nofi').empty();
    $('.nofi').append(errorDiv);
}