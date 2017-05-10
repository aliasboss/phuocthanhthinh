var sorts = '{}';
var perpage = 10;
$(document).ready(function () {

    $(".btn_go_page").live('click',function(){
        var page = $("#input_page").val();
        console.log(page);
        loadPage(page);
    });



    $('#modal_add').on('show', function () {
       $(".control-group").removeClass('error');
    });

    $("#btn_add").click(function(){        
        clearFrom();
        $("#errorModal").hide();
        $("#info_edit").html('');
        $("#myModalLabel").html(title_modal_add);
        $("#modal_add").modal('show');

    });

   $("#myForm").live('submit',function(){
       $("#errorModal").hide();
       var url_post = $(this).attr('action');
        $.post(url_post, $(this).serialize(), function(res){
            var json_obj = JSON.parse(res);
            
            if(json_obj.status == 'success'){
                if(json_obj.method == 'update'){
                    loadPage($("#current_page").val());
                }
                else{
                    loadPage(json_obj.page);
                }
                $("#modal_add").modal('hide');
            }else{
                $(".control-group").removeClass('error');
                if(json_obj.method == 'validate'){
                    $('#errorModal ul').html('');
                     for(var key in json_obj.error){
                         $("#"+key).parent().parent().addClass('error');
                        for(var index in json_obj.error[key]){
                            $('#errorModal ul').append("<li>"+json_obj.error[key][index]+"</li>");
                        }
                     }
                }else{
                    $('#errorModal ul').html("<li>"+json_obj.error+"</li>");
                }
                $("#errorModal").show();
            }
        });
        return false;
   });


    $(".sort").live("click",function(){
        var sort = $(this).attr('key');
        var order = $(this).attr('order');
        
        sorts = '{"sort_name":"'+sort+'","sort_type":"'+order+'"}';
        loadPage(1);
        if($(this).attr('order') == 'DESC'){
            $(this).attr('order','ASC');
            $(this).find('span').removeClass('caret_up').addClass('caret_down');
        }else{
            $(this).attr('order','DESC');
            $(this).find('span').removeClass('caret_down').addClass('caret_up');
        }
    });


    
  
    $("#btn_search").click(function(){
        loadPage(1);
    });

    func_edit();
    func_delete();
    func_delete_select();
    func_checkbox_selected();

    

    $("#dropdown_delete_all").click(function(){
        if(confirm("Bạn đồng ý xóa tất cả dữ liệu!")){
			$.post(url+"/delete_all", null, function(res){
					result = JSON.parse(res);
					if(result.status == 'success')
						loadPage(1);
					func_show_notice(result.status,result.message);
			 });
		 }
    });

    

    if($('.perpage').length > 0) perpage = $('.perpage').val();

    loadPage(1);
});

function loadPage(page) {
    $("#content").hide();
    $("#loading").show();
    $search = getParamsSearch();

    $.post(url+"/list", {page:page,search:$search,sort:sorts}, function(res){
      $("#data_table").html(res);
    });
};

function getParamsSearch(){
  $search = '{';
  $(".params_search").each(function(){
      $name = $(this).attr('key');
      $val = $(this).val();
      console.log("-------------");
      if($(this).attr('type') === "checkbox"){
          console.log("-----555--------");
          if($(this).is(":checked")){
              $val = $(this).val();
              
          }else{
              $val = "notcheck";
          }
      }
      $search+='"'+$name+'":"'+ $val+'",';
  });
  $search += '"noitem":""}';
  return $search;
}

func_delete_select = function(){
  $("#dropdown_delete_select").click(function(){
        var keys = '';
        if(confirm(msg_confirm_delete_select)){
            $(".cb_select").each(function(e){
                if($(this).attr("checked")){
                    keys += $(this).val()+',';
                }
            });

            if(keys !=''){
                keys = keys.substr(0, keys.length -1);
                $.post(url+"/delete_select", {keys:'['+keys+']'}, function(res){
                    result = JSON.parse(res);
                    if(result.status == 'success')
                        loadPage($("#current_page").val());
                    func_show_notice(result.status,result.message);

                });
            }else{
                alert(msg_notice_no_select);
            }
        }
    });
};

func_edit = function(){
  $(".group_btn_edit").live("click",function(){
        var id = $(this).attr("key");
        
        $.post(url+"/getinfo", {key:id}, function(res){
            
          var json_obj = JSON.parse(res);
          editForm(json_obj);
          $("#errorModal").hide();
          $("#id_edit").html('ID:'+json_obj.id);
          $("#myModalLabel").html(title_modal_edit);
          $("#modal_add").modal('show');
        });
    });
};

function func_delete(){
    $(".group_btn_delete").live("click",function(){
        var id = $(this).attr("key");
        $("#key_delete").val(id);

        $.post(url+"/getinfo", {key:id}, function(res){
           var json_obj = JSON.parse(res);
           $("#info_delete").html(confirmDelete(json_obj));
            $("#modal_delete").modal('show');
        });
    });
    $("#btnDeleteOk").click(function(){
        var id = $("#key_delete").val();
         $.post(url+"/delete", {key:id}, function(res){
            loadPage($("#current_page").val());
            $("#modal_delete").modal('hide');
          });
    });
}

/**
 * Show notice from reponse server
 */
function func_show_notice(status,message) {
    var msg='';    
    switch(status){
        case 'success':
            msg = '<strong>Success!</strong>'+message;
            $("#myModalNoticeContent").html(msg);
            $("#myModalNoticeContent").addClass('alert alert-success');            
            break;
        case 'error':
            msg = '<strong>Error!</strong>'+message;
            $("#myModalNoticeContent").html(msg);
            $("#myModalNoticeContent").addClass('alert alert-error');
            break;
    }
    $("#myModalNotice").modal('show');
}

/**
 * Checkbox selected
 */
function func_checkbox_selected() {
    $("#cb_all").live("click",function(){
        if($(this).attr('checked')){
            $('.cb_select').each(function(e){
                $(this).attr('checked','checked');
            });
        }else{
            $('.cb_select').each(function(e){
                $(this).removeAttr('checked');
            });
        }
    });
}



// Chi cho phep nhap so
jQuery.fn.nhapso =
function()
{
    return this.each(function()
    {
        $(this).keydown(function(e)
        {
            var key = e.charCode || e.keyCode || 0;
            // cho phep
            return (
                key == 8 || //phim backspace
                key == 9 || //phim tab
                key == 46 || //phim delete
				key == 109 || //phim delete
                (key >= 37 && key <= 40) || // arrows(cac phim mui ten)
                (key >= 48 && key <= 57) || // numbers(cac phim so)
                (key >= 96 && key <= 105)); // keypad numbers(cac phim so ben tay phai)
        })
    })
};