//состояния объектов, в частности инпутов
//var stations = {"warning", "error", "info", "success"};
var success = "<span class=\"label label-success\">Тренировка записана</span>";
var danger = "<span class=\"label label-danger\">Произошла ошибка</span>";
function setWorkout()
{
    var date = $("#inputDate").val();
    var work = $("#inputWork").val();
    var result = $("#inputResult").val();

    alert("типа записали");

    $("#button-set-workout").parent().append(success);
}

function getXmlHttp(){
    try {
        return new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) {
        try {
            return new ActiveXObject("Microsoft.XMLHTTP");
        } catch (ee) {
        }
    }
    if (typeof XMLHttpRequest!='undefined') {
        return new XMLHttpRequest();
    }
}

// Получить данные с url и вызывать cb - коллбэк c ответом сервера
function getUrl(url, cb) {
    var xmlhttp = getXmlHttp();
    // IE кэширует XMLHttpRequest запросы, так что добавляем случайный параметр к URL
    // (хотя можно обойтись правильными заголовками на сервере)
    xmlhttp.open("GET", url+'?r='+Math.random());
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4) {
            cb(
                xmlhttp.status,
                xmlhttp.getAllResponseHeaders(),
                xmlhttp.responseText
            );
        }
    }
    xmlhttp.send(null);
}


//авторизация пользователя
function signIn() {
    //проверка на то, что пользователь существует
    //запись в куки айди его роли
    var login = $(".inputLogin").val();
    var password = $(".inputPassword").val();
    //вообще-то я хотел аджаксом проверять наличие пользователя в системе
    /*$.ajax({
            type: "POST",
            url: "http://localhost/index.php",
            dataType: 'json',
            data: "article_id=" + article_id
                + "&article_title=" + article_title
                + "&article_text=" + article_text
                + "&article_author=" + article_author,
            // success - это обработчик удачного выполнения событий
            // кроме него есть другие варианты, например error
            // об этом вы можете прочесть на соответствующих сайтах
            success: function() {
                alert("Статья обновлена!");
            }
        }
    );*/
}

//регистрация пользователя
function signUp() {
    //alert("sign up");
}

//при потере фокуса логином
function loginBlurFocus() {
    //$("#inputLogin").attr('id', 'inputSuccess');
    $("#helpLogin").text('Привет, ' + $("#inputLogin").val());
}

//при поторе фокуса паролем
function passBlurFocus() {
    var length = $("#inputPassword").val().length;
    var tooltip = "";
    if (length <= 4) tooltip = "Такой даже школьницу не остановит...";
    else if (length > 4 && length <= 8) tooltip = "На один раз сойдет";
    else if (length > 8 && length <= 12) tooltip = "Неплохой такой";
    else if (length > 12 && length <= 16) tooltip = "Хорош";
    else if (length > 16 && length <= 20) tooltip = "Шикарно!";
    else if (length > 20 && length <= 24) tooltip = "Издеваешься?!";
    else if (length > 24 && length <= 30) tooltip = "Думаю, достаточно...";
    else if (length > 30 && length <= 40) tooltip = "У Вас определенно паранойя...";
    else if (length > 40) tooltip = "Вы удивительный человек...";
    $("#helpPassword").text(tooltip);
}

//переключение полов
function boyChecked(){
    //alert($("#checkboxBoy").attr('checked'));
    if ($("#checkboxBoy").attr('checked') == "checked") {
        $("#checkboxGirl").attr('checked', false);
    }
    else {
        $("#checkboxGirl").attr('checked', true);
    }
}

function girlChecked(){
    if ($("#checkboxGirl").attr('checked') == "checked") {
        $("#checkboxBoy").attr('checked', false);
    }
    else {
        $("#checkboxBoy").attr('checked', true);
    }

}