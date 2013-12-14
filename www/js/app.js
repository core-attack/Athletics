﻿//состояния объектов, в частности инпутов
//var stations = {"warning", "error", "info", "success"};

function myOnLoad()
{
    var currentDate = new Date();
    var bday = currentDate.getDate();
    var bmonth = currentDate.getMonth() + 1;
    var byear = currentDate.getFullYear();
    var endDate = new Date();
    endDate.setDate(currentDate.getDate() + 7);
    var eday = endDate.getDate();
    var emonth = endDate.getMonth() + 1;
    var eyear = endDate.getFullYear();
    $("#inputBeginDate").val(bday + "." + bmonth + "." + byear);
    $("#inputEndDate").val(eday + "." + emonth + "." + eyear);

    $("#inputWork").val("800");
    $("#inputResult").val("2.00.04");
}

var success = "<span class=\"label label-success\">Тренировка записана</span>";
var danger = "<span class=\"label label-danger\">Произошла ошибка</span>";
function setWorkout()
{
    var date = $("#inputDate").val();
    var work = $("#inputWork").val();
    var result = $("#inputResult").val();
    $.ajax({
        url : "/?action=setWorkout",
        type: "POST",
        data : { "date" : date, "work" : work, "result" : result },
        dataType: "application/json; charset=utf-8",
        success: function(data){
            //alert(data);
            $("#button-set-workout").parent().append(success);
        },
        error: function(data){
            console.log(data);
            $("#button-set-workout").parent().append(danger);
        }
    });
}

function setWorkoutPlan(){
    success = "<span class=\"label label-success\">Недельный план тренировок создан</span>";
    danger = "<span class=\"label label-danger\">Произошла ошибка в создании недельного плана тренировок</span>";
    var divs = $(".day");
    var beginDate = $("#inputBeginDate").val();
    var endDate = $("#inputEndDate").val();
    var comments = $("#inputComments").val();
    $.ajax({
        url : "/?action=setWorkoutWeekPlan",
        type: "POST",
        data : { "beginDate" : beginDate, "endDate" : endDate, "comments" : comments },
        dataType: "application/json; charset=utf-8",
        success: function(data){
            //alert(data);
            $("#button-set-workout").parent().append(success);
        },
        error: function(data){
            console.log(data);
            $("#button-set-workout").parent().append(danger);
        }
    });
    success = "<span class=\"label label-success\">План тренировки создан</span>";
    danger = "<span class=\"label label-danger\">Произошла ошибка в создании плана тренировки</span>";
    for(var i = 0; i < divs.length; i++){
        var div = divs[i];
        var id = $(div).attr("id");
        var day = $("#workout-day" + (i + 1)).val();
        var work = $("#inputWork" + (i + 1)).val();
        var result = $("#inputResult" + (i + 1)).val();
        var day_comments = $("#inputComments" + (i + 1)).val();
        //alert(day + work + result + comments);
        $.ajax({
            url : "/?action=setWorkoutPlan",
            type: "POST",
            data : { "beginDate" : beginDate, "endDate" : endDate, "comments" : comments,
                "day" : day, "work" : work, "result" : result, "day_comments" : day_comments},
            dataType: "application/json; charset=utf-8",
            success: function(data){
                //alert(data);
                $("#button-set-workout").parent().append(success);
            },
            error: function(data){
                console.log(data);
                $("#button-set-workout").parent().append(danger);
            }
        });
    }
}

function addNewDay(el){
    var day = $("#firstDay").clone().addClass("newDay");
    var count = $(".day").length;
    if (count < 7)
    {
        $(day).attr("id", "day" + (count + 1));
        day.find('input, select').each(function(){
            if ($(this).attr("id").indexOf("workout-day") != -1)
                $(this).attr("id", "workout-day" + (count + 1));
            else if ($(this).attr("id").indexOf("inputWork") != -1)
                $(this).attr("id", "inputWork" + (count + 1));
            else if ($(this).attr("id").indexOf("inputResult") != -1)
                $(this).attr("id", "inputResult" + (count + 1));
            else if ($(this).attr("id").indexOf("inputComments") != -1)
                $(this).attr("id", "inputComments" + (count + 1));
        });
        $(day).appendTo("form#setWeek").slideDown();
    }
    //$(el).add("div #day").addClass("adfasdf");
    ///var div = $("#day").clone();
    //$("body").appendTo("asdfsadf");
    //var days = $("#set-workout-plan .form-horizontal");
    //days.appendTo("<p>asdfasdf</p>");//appendTo(div).slideDown();
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

//при вводе пароля
function passBlurFocus() {
    var length = $("#inputPassword").val().length;
    var tooltip = "";
    if (length <= 4) tooltip = "Такой даже школьницу не остановит...";
    else if (length > 4 && length <= 8) tooltip = "На один раз сойдет";
    else if (length > 8 && length <= 12) tooltip = "Неплохой такой";
    else if (length > 12 && length <= 16) tooltip = "Хорош";
    else if (length > 16 && length <= 20) tooltip = "Шикарно!";
    else if (length > 20 && length <= 24) tooltip = "Издеваетесь?!";
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

//обработчик ошибки
function handleError(mes){
    alert("Произошла ошибка: " +mes);
}
