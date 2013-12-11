$(document).ready(function()
{
    $(function(){
        switch (window.location.hash) {
           case '#sportsmens':
              $('#sportsmens').trigger('click');
              break;
           case '#treners':
              $('#treners').trigger('click');
              break;
           case '#events':
              $('#events').trigger('click');
              break;
        }
    });
        
    $('a.edit_row').on('click', function()
    {
        if($(this).parent().parent().children(".sheet").children("input").length)
            return;
        
        $(this).hide();
        $(this).parent().children('.save_row').show();
        
        var parent = $(this).parent().parent();
        var val = '', str = '';
        parent.children('.sheet').each(function(index, element)
        {
            val = $(element).html();
            str = '<input type="text" class="short_input" name="'+$(element).attr("rel")+'" value="'+val+'"/>';
            $(element).html(str);
        });
        parent.children('.sheet_select').each(function(index, element)
        {
            str = '<select name="category" class="list"> <option value="1">МСМК</option> <option value="2">МС</option> <option value="3">КМС</option> <option value="4">| Ю</option> <option value="5">|| Ю</option> <option value="6">||| Ю</option> <option value="7">|</option> <option value="8">||</option> <option value="9">|||</option> <option value="10">ЮСС</option> <option value="11">СС|||</option> <option value="12">CC||</option> <option value="13">CC|</option> <option value="14">CCBK</option> </select>';
            $(element).html(str);
        })
        
    })
    
    $('a.save_row').on('click', function()
    {
        var parent = $(this).parent().parent();
        var id = parent.attr("id");
        var role_id = $(this).attr("rel");
        var type = parent.attr("rel");
        var post = parent.children(".sheet").children("input").serialize();
        post += post+'&type='+type+'&id='+id+'&category='+parent.children(".sheet_select").children("select").children("option:selected").attr("value");
        if(parseInt(role_id) > 0)
            post += '&role='+role_id;
        $.ajax({
            url: '?action=ajax',
            type: 'post',
            data: post,
            dataType: 'json',
            success: function(data)
            {
                if(data.status == 'success')
                    window.location.reload();
                else
                    alert(data.message);
            },
            error: function(data)
            {
                console.log(data);
            }
        });
    })
    
    $("#sportsmens").on("click", function()
    {
        window.location.hash = 'sportsmens';
    });
    $("#treners").on("click", function()
    {
        window.location.hash = 'treners';
    });
    $("#events").on("click", function()
    {
        window.location.hash = 'events';
    });

})