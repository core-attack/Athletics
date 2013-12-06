var first_adr = 'http://mysite/?'; 

function CheckLogin()
{
    var val = md5($(".login input[name=login]").val() + ":" + $(".login input[name=passwd]").val());
    $.ajax({
        type: "GET",
        url: first_adr + "action=checklogin",
        data: {
            "val": val
        },
        dataType: "json",
        success: function(res)
        {
            if (res == '1')
                document.location.href = first_adr + 'action=admin';
			else
			{
				// очистить поля ввода
                $(".login input[name=login]").val("");
                $(".login input[name=passwd]").val("");
				alert('Неверный логин или пароль!');
			}
        }
    });

}

function AddProduct()
{
    $.ajax({
        type: "GET",
        url: first_adr + "action=addproduct",
        data: {
            "name": $("#add_product_form input[name=name]").val(),
            "descr": $("#add_product_form input[name=descr]").val(),
            "description": $("#add_product_form textarea[name=description]").val(),
            "article": $("#add_product_form input[name=article]").val(),
            "cat_id": $("#add_product_form input[name=category]").val(),
            "gen_id": $("#add_product_form input[name=genre]").val(),
            "price": $("#add_product_form input[name=price]").val(),
            "count": $("#add_product_form input[name=count]").val()
        },
        dataType: "json",
        success: function(res)
        {
            if (res == '1')
                alert("Добавлено!");

			// очистить поля ввода

        }
    });
}
//загружаем категории в селект 
function LoadCategoryIntoSelect()
{
    
}

function Logout()
{

}
// повесить клик на кнопку
$(document).ready(function()
{
    $("#loginbutton").click(CheckLogin);
    $("#logout").click(Logout);
    $("#add_product").click(AddProduct);
    LoadCategoryIntoSelect();
});