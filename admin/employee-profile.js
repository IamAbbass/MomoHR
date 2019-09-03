$(document).ready(function(){

    setTimeout(function(){
        //$("#map").css('height',($(window).height()-200)+'px');
          $("#map").css('height','600px');
        initMap();
    },250);


    $(".render_map").click(function(){
        setTimeout(function(){
            $("#map").css('height','600px');
            initMap();
        },250);
     });

     $('#time_off_table').DataTable();
     $('#tbl_documents').DataTable();
     $('#compensation_table').DataTable();
     $('#expense_table').DataTable();
     $("#table-contacts").DataTable();
     $("#call_logs_table").DataTable();

     $('a.edit-document').on('click',function(){
      	document_id = $(this).attr('data-document-id');
        $('.modal-loading').show();
      	$.ajax({
      		method:'POST',
      		url:'ajax/employee-ajax.php',
      		data:{update_document:true,document_id:document_id},
      		success:function(response){
      			$('.modal-loading').hide();
      			data = JSON.parse(response);
      			file_path = data[0].file_path;
      			notes = data[0].notes;
      			file_name = data[0].filename;

      			$('input[name=txt_edited_document_notes]').val(notes);
      			$('input[name=txt_edited_document_file_name]').val(file_name);
      			$('#modal_edit_document a.url').attr('href',file_path);
      			$('input[name=txt_file_pervious_path]').val(file_path);
      			$('input[name=txt_file_document_id]').val(document_id);
      			// $('#txt_edited_document_notes').value(notes);
      			// asd = JSON.parse(response);
      			// console.log(asd.file_path);
      			// console.log(asd['file_path']);
      		}
      	});
    });

    //document deleting
    $(".delete_document").click(function(e){
        e.preventDefault();
        //x = confirm("Are you sure you want to delete the document?");
       	//if (x == true) {
          var id = $(this).attr("document_id");

          $(this).html("<i class='fa fa-refresh fa-spin'></i>");
          $.ajax({
           		method:'POST',
           		url:'ajax/employee-ajax.php',
           		data:{delete_document:true,document_id:id},
           		success:function(response){
                $('tr#rowDocument'+id).fadeOut();
           		}
       	    });
       	//}
    });

    //cancel compensation
    $(".delete_compensation").click(function(e){
        e.preventDefault();
        //x = confirm("Are you sure you want to delete this compensation?");
        var id = $(this).attr("compensation_id");
        $(this).html("<i class='fa fa-refresh fa-spin'></i>");
        var this_ = $(this);
       	//if (x == true) {
           		$.ajax({
           		method:'POST',
           		url:'ajax/employee-ajax.php',
           		data:{delete_compensation:true,compensation_id:id},
           		success:function(response){
           			  $(this_).parent().html("<span class='text-muted'>Cancelled</span>");
               }
       	    })
       	//}
    });

    //cancel advance salary
    $(".delete_avdanve_salary").click(function(e){
        e.preventDefault();
        //x = confirm("Are you sure you want to delete this advance salary?");
        var id = $(this).attr("advance_id");
        $(this).html("<i class='fa fa-refresh fa-spin'></i>");
        var this_ = $(this);
       	//if (x == true) {
           		$.ajax({
           		method:'POST',
           		url:'ajax/employee-ajax.php',
           		data:{delete_advance:true,advance_id:id},
           		success:function(response){
           			  $(this_).parent().html("<span class='text-muted'>Cancelled</span>");
               }
       	    })
       	//}
    });


      $('a.assests-history-btn').on('click',function(){
        $('.modal-loading').show();
            $this = $(this);
            $assets_id = $(this).attr('data-assets-id');
            $.ajax({
                method:'POST',
                url:'ajax/employee-ajax.php',
                data:{get_assets_history:true,assets_id:$assets_id},
                success:function(response){
                    $('#tbl_assets_history').html(response);
                    $('.modal-loading').hide();
                    //$('#assets_table_history').DataTable();
                }
            })
      });
     $('button.btn-approve').on('click',function(){
         $time_off_id = $(this).attr('data-time-off-id');
         $this = $(this);
         $('#tf-status-'+$time_off_id).html("<i class='fa fa-spin fa-circle-o-notch'></i> Updating..");
         $.ajax({
             method:'POST',
             url:'ajax/employee-ajax.php',
             data:{time_off_update_request:true,status_approve:true,time_off_id:$time_off_id},
             success:function(response){
                 $('#btn-approve-'+$time_off_id).attr('disabled','disabled');
                 $('#btn-pending-'+$time_off_id).removeAttr('disabled');
                 $('#btn-decline-'+$time_off_id).removeAttr('disabled');
                 $('#tf-status-'+$time_off_id).html('Approved');
             }
         });
     });

    $('button.btn-pending').on('click',function(){
         $time_off_id = $(this).attr('data-time-off-id');
         $this = $(this);
         $('#tf-status-'+$time_off_id).html("<i class='fa fa-spin fa-circle-o-notch'></i> Updating..");
         $.ajax({
             method:'POST',
             url:'ajax/employee-ajax.php',
             data:{time_off_update_request:true,status_pending:true,time_off_id:$time_off_id},
             success:function(response){
                 $('#btn-pending-'+$time_off_id).attr('disabled','disabled');
                 $('#btn-approve-'+$time_off_id).removeAttr('disabled');
                 $('#btn-decline-'+$time_off_id).removeAttr('disabled');
                 $('#tf-status-'+$time_off_id).html('Pending');
             }
         });
    });
    $('button.btn-decline').on('click',function(){
         $time_off_id = $(this).attr('data-time-off-id');
         $this = $(this);
         $('#tf-status-'+$time_off_id).html("<i class='fa fa-spin fa-circle-o-notch'></i> Updating..");
         $.ajax({
             method:'POST',
             url:'ajax/employee-ajax.php',
             data:{time_off_update_request:true,status_decline:true,time_off_id:$time_off_id},
             success:function(response){
                 $('#btn-decline-'+$time_off_id).attr('disabled','disabled');
                 $('#btn-pending-'+$time_off_id).removeAttr('disabled');
                 $('#btn-approve-'+$time_off_id).removeAttr('disabled');
                 $('#tf-status-'+$time_off_id).html('Declined');
             }
         });
    });

    function change_status(expense_id,action,old_html,x){

            $(".action_btn[expense_id='"+expense_id+"'][action='"+action+"']").html("<i class='fa fa-spinner fa-spin'></i> Loading..");
            var reason = x; //rejection reason

            $.get("ajax/expense_status.php?expense_id="+expense_id+"&action="+action+"&reason="+reason,function(data){
                data = $.parseJSON(data);
                var expense_id = data.expense_id;
                //var old_status = data.old_status;
                var new_status  = data.new_status;
                var error       = data.error;
                var date_time   = data.date_time;

                if(error == "false"){
                    if(new_status == "Approved"){
                        //status
                        $(".expense_status[expense_id='"+expense_id+"']").attr("title","Approved");
                        $(".expense_status[expense_id='"+expense_id+"']").attr("data-original-title","Approved");
                        $(".expense_status[expense_id='"+expense_id+"']").attr("data-content",date_time);
                        $(".expense_status[expense_id='"+expense_id+"']").removeClass("badge-default badge-warning badge-danger badge-success");
                        $(".expense_status[expense_id='"+expense_id+"']").addClass("badge-warning");
                        $(".expense_status[expense_id='"+expense_id+"']").html('<i class="fa fa-check"></i> Approved');

                        //action
                        $(".action_opt[expense_id='"+expense_id+"'][action='approve']").hide();
                        $(".action_opt[expense_id='"+expense_id+"'][action='reject']").hide();
                        $(".action_opt[expense_id='"+expense_id+"'][action='paid']").fadeIn();
                        $(".action_opt[expense_id='"+expense_id+"'][action='no_action']").hide();

                    }else if(new_status == "Rejected"){
                        $(".expense_status[expense_id='"+expense_id+"']").attr("title","Rejected");
                        $(".expense_status[expense_id='"+expense_id+"']").attr("data-original-title","Rejected");
                        $(".expense_status[expense_id='"+expense_id+"']").attr("data-content",date_time);
                        $(".expense_status[expense_id='"+expense_id+"']").removeClass("badge-default badge-warning badge-danger badge-success");
                        $(".expense_status[expense_id='"+expense_id+"']").addClass("badge-danger");
                        $(".expense_status[expense_id='"+expense_id+"']").html('<i class="fa fa-times"></i> Rejected');

                        //action
                        $(".action_opt[expense_id='"+expense_id+"'][action='approve']").hide();
                        $(".action_opt[expense_id='"+expense_id+"'][action='reject']").hide();
                        $(".action_opt[expense_id='"+expense_id+"'][action='paid']").hide();
                        $(".action_opt[expense_id='"+expense_id+"'][action='no_action']").html("Rejected: <b>"+data.reason+"</b>").fadeIn();

                    }else if(new_status == "Pay"){
                        $(".expense_status[expense_id='"+expense_id+"']").attr("title","Paid");
                        $(".expense_status[expense_id='"+expense_id+"']").attr("data-original-title","Paid");
                        $(".expense_status[expense_id='"+expense_id+"']").attr("data-content",date_time);
                        $(".expense_status[expense_id='"+expense_id+"']").removeClass("badge-default badge-warning badge-danger badge-success");
                        $(".expense_status[expense_id='"+expense_id+"']").addClass("badge-success");
                        $(".expense_status[expense_id='"+expense_id+"']").html('<i class="fa fa-usd"></i> Paid');

                        //action
                        $(".action_opt[expense_id='"+expense_id+"'][action='approve']").hide();
                        $(".action_opt[expense_id='"+expense_id+"'][action='reject']").hide();
                        $(".action_opt[expense_id='"+expense_id+"'][action='paid']").hide();
                        $(".action_opt[expense_id='"+expense_id+"'][action='no_action']").html("Paid").fadeIn();
                    }
                }

                $(".action_btn[expense_id='"+expense_id+"'][action='"+action+"']").html(old_html);
            });
    }

    var asset_action    = null;
    var assets_id       = null;

    $('a.assests-return-btn').on('click',function(){
        $this = $(this);
      	assets_id        = $(this).attr('data-assets-id');
        asset_action     = "return";
        var emp_id       = $(this).attr('data-emp-id');
        $("select[name='asset_user_id']").val(emp_id);
        $("#modal_asset_give").modal("show");
        $(".btn_submit_asset").html("Return Asset");
        $("select[name='asset_user_id']").parent().hide();
        $("select[name='asset_user_id']").parent().siblings().removeClass("col-md-6").addClass("col-md-12");
    })

    $('a.assests-give-btn').on('click',function(){
        $this           = $(this);
        assets_id       = $(this).attr('data-assets-id');
        asset_action    = "give";
        var emp_id       = $(this).attr('data-emp-id');
        $("select[name='asset_user_id']").val(emp_id);
        $("#modal_asset_give").modal("show");
        $(".btn_submit_asset").html("Give Asset");
        $("select[name='asset_user_id']").parent().show();
        $("select[name='asset_user_id']").parent().siblings().removeClass("col-md-12").addClass("col-md-6");
    });

    $("form#asset_form").submit(function(e){
        e.preventDefault();
        var asset_user_id   = $("select[name='asset_user_id']").val();
        var asset_comments  = $("textarea[name='asset_comments']").val();

        $(".status[data-assets-id='"+assets_id+"']").html("<i class='fa fa-circle-o-notch fa-spin'></i> Updating..");
        $("#modal_asset_give").modal("hide");


        var default_emp = $("a.assests-give-btn[data-assets-id='"+assets_id+"']").attr('data-emp-id');
        if(default_emp != asset_user_id){
            $(".status[data-assets-id='"+assets_id+"']").parent().css("box-shadow","0 0 100px #f00 inset");
            $(".status[data-assets-id='"+assets_id+"']").parent().css("color","#fff");
            $(".status[data-assets-id='"+assets_id+"']").parent().css("background","#f00");
            setTimeout(function(){
                $(".status[data-assets-id='"+assets_id+"']").parent().fadeOut();
            },1000);
        }

        $.ajax({
      		method:'GET',
      		url:'ajax/employee-ajax.php',
      		data:{asset_action:asset_action,assets_id:assets_id,comment:asset_comments,asset_user_id:asset_user_id},
      		success:function(response){

              $("textarea[name='asset_comments']").val("");

              if(asset_action == "return"){
                $(".status[data-assets-id='"+assets_id+"']").html("Returned");
                $('a.assests-return-btn[data-assets-id="'+assets_id+'"]').hide();
                $('a.assests-give-btn[data-assets-id="'+assets_id+'"]').show();
              }else{
                $(".status[data-assets-id='"+assets_id+"']").html("Given");
                $('a.assests-give-btn[data-assets-id="'+assets_id+'"]').hide();
                $('a.assests-return-btn[data-assets-id="'+assets_id+'"]').show();
              }
      		}
      	});
    });

    $('a.btn-edit-compensation').on('click',function(){
          $compensation_id = $(this).attr('data-compensation-id');
          $('.modal-loading').show();
          $.ajax({
            method:'POST',
            url:'ajax/employee-ajax.php',
            data:{update_compensation:true,compensation_id:$compensation_id},
            success:function(response){
              $('.modal-loading').hide();
              data = JSON.parse(response);
              $('input[name=txt_edit_compensation_full_name]').val(data[0].full_name);
              $('input[name=txt_edit_compensation_amount]').val(data[0].amount);
              $('input[name=txt_edit_compensation_id]').val(data[0].id);
              $('select[name=txt_edit_compensation_category] option').each(function() {
                if ($(this).text() == data[0].category ) {
                  $(this).attr("selected","selected");
                }
              })
            }
          })
    })


    $('a.btn-advance-salary').on('click',function() {
    	$this =  $(this);
    	$advance_salary_id = $this.attr('data-salary-id');
    	 $('#advance_salary_ajax_loader').css('visibility','visible');
    	 $.ajax({
               method:'POST',
               url:'ajax/employee-ajax.php',
               data:{get_advance_salary:true,salary_id:$advance_salary_id},
               success:function(response){
               	data = JSON.parse(response);
                   $('#advance_salary_ajax_loader').css('visibility','hidden');
                   $('input[name=txt_advance_salary_id]').val($advance_salary_id);
                   $('input[name=txt_edit_advance_salary_date]').val(data[0].date)
				              $('input[name=txt_edit_advance_salary_amount]').val(data[0].amount);
               }
           })
    })







    setTimeout(function(){
        $(".remove_after_5").slideUp();
    },5000);

    /*
    setTimeout(function(){
				<?php
					$date_from 	= $_GET['date_from'];
					$date_to 	= $_GET['date_to'];
					if(strlen($date_from) > 0 && strlen($date_to) > 0){
						$show_date_from 	= date("M d, Y",strtotime($_GET['date_from']));
						$show_date_to 		= date("M d, Y",strtotime($_GET['date_to']));
					}else{
					    $show_date_from 	= date("M d, Y",time());
						$show_date_to 		= date("M d, Y",time());
					}
				?>

                  var date_from 	= "<?php echo $show_date_from; ?>";
        					var date_to		= "<?php echo $show_date_to; ?>";

        					if(date_from == "" && date_to == ""){
        						$("#reportrange span").text("<?php echo $showing_last_hours_xml ?>");
        					}else if(date_from == "" && date_to == ""){
        						$("#reportrange span").text(date_from+" - "+date_to);
        					}
        					$("#reportrange span").show();
    },50);



			$("#filter").submit(function(){
				var date_from 	= $("input[name='daterangepicker_start']").val();
				var date_to		= $("input[name='daterangepicker_end']").val();
				$("input[name='date_from']").val(date_from);
				$("input[name='date_to']").val(date_to);
				if(date_from == "" && date_to == ""){
					$("#reportrange span").text("<?php echo $showing_last_hours_xml; ?>");
				}
			});
            */

    $('.attachment[data-toggle="popover"]').popover({
        html: true,
        trigger: 'hover',
        placement: 'right',
        content: function(){return '<img style="width:100%;" src="'+$(this).data('img') + '" />';}
    });

    $('.expense_status[data-toggle="popover"]').popover({
        html: true,
        trigger: 'hover',
        placement: 'left'
    });

    $(".ranges .active").removeClass("active");


  $("select[name='bulk_status_change']").change(function(){
     var total_checked = $(".bulk_check").filter(':checked').length;
     var new_status    = $(this).val();
     if(new_status != ""){
         if(total_checked == 0){
              alert("Please select an expense!");
              $("select[name='bulk_status_change']").val("");
         }else{
            //if(new_status == "reject"){
            //	var x = prompt("Please specify a reason for rejection of "+total_checked+" selected expense(s)!");
            //}else{
            //	var x = confirm("Are you sure you want to change the status of "+total_checked+" selected expense(s) to '"+new_status+"' ?");
            //}

              //if(x){
                  $(".bulk_check:checked").each(function(){
                      var expense_id  = $(this).val();
                      var old_html    = $(".action_btn[expense_id='"+expense_id+"'][action='"+new_status+"']").html();
                      change_status(expense_id,new_status,old_html,x);
                  });
                  $("select[name='bulk_status_change']").val("");
              //}else{
              //    $("select[name='bulk_status_change']").val("");
              //}
         }
     }
  });

  $(".select_all").click(function(){
      $(".bulk_check").each(function(){
          if($(this).is(":visible")){
              $(this).prop("checked",true);
          }
      });
  });

  $(".unselect_all").click(function(){
      $(".bulk_check").each(function(){
          if($(this).is(":visible")){
              $(this).prop("checked",false);
          }
      });
  });


    $(".action_btn").click(function(){
          var expense_id  = $(this).attr("expense_id");
          var action      = $(this).attr("action");
          var old_html    = $(this).html();

          if(expense_id.length > 0 && action.length > 0){
                if(action == "reject"){
                	var x = prompt("Please specify a reason for rejection of this expense!");
                }else{
                //	var x = confirm("Are you sure you want to change the status of this expense to '"+action+"' ?");
                var x = true;
                }


              //if(x){
                  change_status(expense_id,action,old_html,x);
              //}
          }else{
              alert("Whoops, please refresh your browser and try again!");
          }
      });



        /*
        setTimeout(function(){
    				<?php
    					$date_from 	= $_GET['date_from'];
    					$date_to 	= $_GET['date_to'];
    					if(strlen($date_from) > 0 && strlen($date_to) > 0){
    						$show_date_from 	= date("M d, Y",strtotime($_GET['date_from']));
    						$show_date_to 		= date("M d, Y",strtotime($_GET['date_to']));
    					}
    				?>

          var date_from 	= "<?php echo $show_date_from; ?>";
    				var date_to		= "<?php echo $show_date_to; ?>";

    				if(date_from == "" && date_to == ""){
    					$("#reportrange span").text("<?php echo $showing_last_hours_xml ?>");
    				}else if(date_from == "" && date_to == ""){
    					$("#reportrange span").text(date_from+" - "+date_to);
    				}
    				$("#reportrange span").show();
        },50);

    			$("#filter").submit(function(){
    				var date_from 	= $("input[name='daterangepicker_start']").val();
    				var date_to		= $("input[name='daterangepicker_end']").val();
    				$("input[name='date_from']").val(date_from);
    				$("input[name='date_to']").val(date_to);
    				if(date_from == "" && date_to == ""){
    					$("#reportrange span").text("<?php echo $showing_last_hours_xml; ?>");
    				}
    			});
                */

    var right_height = $(".right-tab-content-data").height();
    if(right_height > 500){
      //alert(right_height);
    }


    $(".screenshot, .my-photos").click(function() {
        var src = $(this).attr("image");
        $("#view_picture").modal("show");
        $(".view_picture").attr("src",src);
        $(".view_picture").height((window.innerHeight-125)+"px");
    });










    $('[data-toggle="tooltip"]').tooltip();
    $("#single").change(function(){
        var id = $(this).val();
        window.location.href = "select_device.php?id="+id;
    });

   setTimeout(function(){
        $(".remove_after_5").slideUp();
    },5000);
    function device_update_time(){
        $.get("ajax/device_update_time.php",function(data){
        $("#device_update_time").html(data);
        setTimeout(function(){
            device_update_time();
        },3000);
        });
    }

    device_update_time();

    setTimeout(function(){
        $(".remove_after_5").slideUp();
   },5000);

   $("#send_message_form").submit(function(e){
        e.preventDefault();
        var message_title   = $("input[name='message_title']").val();
        var message_body    = $("textarea[name='message_body']").val();
        $("#send_message_btn").html('<i class="fa fa-refresh fa-spin"></i> Sending Message..');
        $.get("ajax/remote_device_actions.php?action=send_message&title="+message_title+"&body="+message_body,function(){
            $("#send_message_btn").html('<i class="fa fa-check"></i> Message Sent!');
        });
   });
   $("#remote_lock").click(function(){
        $("#remote_lock").html('<i class="fa fa-refresh fa-spin"></i> Sending Command..');
        $.get("ajax/remote_device_actions.php?action=remote_lock",function(){
            $("#remote_lock").html('<i class="fa fa-spinner"></i> Locking Device..');
        });
   });
   $("#remote_wipe").click(function(){
        $("#remote_wipe").html('<i class="fa fa-refresh fa-spin"></i> Sending Command..');
        $.get("ajax/remote_device_actions.php?action=remote_wipe",function(){
            $("#remote_wipe").html('<i class="fa fa-spinner"></i> Wiping Phone..');
        });
   });
   $("#play_sound").click(function(e){
        e.preventDefault();
        $("#play_sound").hide();
        $("#stop_sound").fadeIn();
        $.get($(this).attr("href"),function(data){});
   });
   $("#stop_sound").click(function(e){
        e.preventDefault();
        $("#stop_sound").hide();
        $("#play_sound").fadeIn();
        $.get($(this).attr("href"),function(data){});
   });

   function remote_update_time(){
        $.get("ajax/remote_update_time.php",function(data){
        $("#remote_update_time").html(data);
        setTimeout(function(){
            remote_update_time();
        },3000);
        });
    }

    remote_update_time();

    $("#single").change(function(){
        var id = $(this).val();
        window.location.href = "select_device.php?id="+id;
    });
    $('[data-toggle="tooltip"]').tooltip();




});
