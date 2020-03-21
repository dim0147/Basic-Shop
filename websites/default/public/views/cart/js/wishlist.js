$('.add-product').click(function(){
    let numb = Number($(this).next().val());
    let idProd = $(this).attr('id-product');
    if(Number(numb) <= 0 || idProd == null){
        alert("Invalid number !!!");
        return;
    }
    const formSubbmit = `<form action="/cart/action" method="POST">` +
    `<input type="hidden" name="id" value="${idProd}">` +
    `<input type="hidden" name="action" value="add">` +
    `<input type="hidden" name="quantity" value="${numb}">` +
    `</form>`;
    $(formSubbmit).appendTo('body').submit();
});

$('.decrease-product').click(function(){
    let numb = Number($(this).prev().val());
    let idProd = $(this).attr('id-product');
    if(Number(numb) <= 0 || idProd == null){
        alert("Invalid number !!!");
        return;
    }
    const formSubbmit = `<form action="/cart/action" method="POST">` +
    `<input type="hidden" name="id" value="${idProd}">` +
    `<input type="hidden" name="action" value="decrease">` +
    `<input type="hidden" name="quantity" value="${numb}">` +
    `</form>`;
    $(formSubbmit).appendTo('body').submit();
});

$('.delete-product').click(function(){
    let idProd = $(this).attr('id-product');
    if(idProd == null){
        alert("Not found id product !!!");
        return;
    }
    const formSubbmit = `<form action="/cart/action" method="POST">` +
    `<input type="hidden" name="id" value="${idProd}">` +
    `<input type="hidden" name="action" value="remove">` +
    `<input type="hidden" name="quantity" value="1">` +
    `</form>`;
    $(formSubbmit).appendTo('body').submit();
});