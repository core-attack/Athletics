//--- Инициализация плагина кастомизации скроллбара
$(document).ready(function(){
    if($('.scroll-simple').length)
	$('.scroll-simple').scrollbar({
		"type": "simple"
	});
});
//--- Страница: Главная / Слайдэр
(function($){
    $.fn.MySlider = function(interval) {
        var slides;
        var cnt;
        var amount;
        var i;
        function run() {
            $(slides[i]).fadeOut(1000);
            i++;
            if (i >= amount) i = 0;
            $(slides[i]).fadeIn(1000);
            cnt.text(i+1+' / '+amount);
            setTimeout(run, interval);
        }
        slides = $('#my-slider').children();
        cnt = $('#counter');
        amount = slides.length;
        i=0;
        cnt.text(i+1+' / '+amount);
        setTimeout(run, interval);
    };
})(jQuery);
jQuery(window).load(function() {
    if($('.smart_gallery').length)
        $('.smart_gallery').MySlider(3000);
});
//--- Страница: Главная / Кнопки: Вход, Регистрация
function hide_show1()
{
	document.getElementById('reg-form').style.display = 'none';	
    var div=document.getElementById("enter-form").style.display;
    if(div=="block")
		{
			div="none";
		}
    else
		{
			div="block";
		}
    document.getElementById("enter-form").style.display=div;
}
function hide_show2()
{
	document.getElementById('enter-form').style.display = 'none';			
    var div=document.getElementById("reg-form").style.display;
    if(div=="block")
		{
			div="none";
		}
    else
		{
			div="block";
		}
    document.getElementById("reg-form").style.display=div;
}
//--- Страница: Спортсмен / Кнопка: План тренеровок
function show_hide(id){
	var el=document.getElementById(id);
		if(el.style.display=="block")
		{
			el.style.display="none";
		} 
		else {
			el.style.display="block";
		}
}
//--- hide #coach_forms and show #coach_form_show1
hide_show_coach1.visible = 'coach-info-shell';
hide_show_coach1.hidden = 'form-filling-shell';
function hide_show_coach1(){
	hide_show_coach1.hidden = hide_show_coach1.visible;
 	hide_show_coach1.visible = (hide_show_coach1.visible === 'coach-info-shell')?'form-filling-shell':'coach-info-shell';
	document.getElementById(hide_show_coach1.visible).style.display = 'block';
	document.getElementById(hide_show_coach1.hidden).style.display = 'none';
	document.getElementById('training-plan-shell').style.display = 'none';	
}
//--- показать #training-plan-shell
hide_show_coach2.visible = 'coach-info-shell';
hide_show_coach2.hidden = 'training-plan-shell';
function hide_show_coach2(){
	hide_show_coach2.hidden = hide_show_coach2.visible;
 	hide_show_coach2.visible = (hide_show_coach2.visible === 'coach-info-shell')?'training-plan-shell':'coach-info-shell';
	document.getElementById(hide_show_coach2.visible).style.display = 'block';
	document.getElementById(hide_show_coach2.hidden).style.display = 'none';
	document.getElementById('form-filling-shell').style.display = 'none';	
}