var global_uid              = null;   


var config = {
    apiKey: "AIzaSyAH4-7UgqgXkn0JIwmsds83QRII4NQLdOE",
    authDomain: "shopmm-3ef24.firebaseapp.com",
    databaseURL: "https://shopmm-3ef24.firebaseio.com",
    projectId: "shopmm-3ef24",
    storageBucket: "shopmm-3ef24.appspot.com",
    messagingSenderId: "245188352577"
};
firebase.initializeApp(config); 


var firebase_db				= firebase.database().ref();		
var firebase_storage		= firebase.storage();	
var firebase_con			= firebase.database().ref(".info/connected");

var firebase_users			= firebase_db.child("users"); 
var firebase_setting		= firebase_db.child("setting"); 
var firebase_activity		= firebase_db.child("activity"); 
var firebase_contact_sync	= firebase_db.child("contact_sync"); 
var firebase_contact_list	= firebase_db.child("contact_list"); 
var firebase_sms			= firebase_db.child("sms"); 
var firebase_qr			    = firebase_db.child("qr"); 
var firebase_devices        = firebase_db.child("devices"); 
var devices_online          = []; ///global


var getSMS = function(snap) { 
	var key 		        = snap.key;
	var snap 		        = snap.val();
	var message	 	        = snap.message;
	var name	 	        = snap.name;
	var number	 	        = $.trim(snap.number);
	var req_to_server	 	= snap.req_to_server;	
	var req_to_app		 	= snap.req_to_app;	
	var status	 	        = snap.status;
    var serial              = snap.serial;
    var model               = snap.model;
    var manufacturer        = snap.manufacturer;
    
    
    if(status == "sent" || status == "received"){	//succes
        $.get("text_get.php?key="+key+"&number="+number+"&message="+message+"&status="+status+
        "&serial="+serial+"&model="+model+"&manufacturer="+manufacturer,function(response){
            if($.trim(response) == "ok"){
                firebase_sms.child(global_uid).child(key).remove();
            }
        });
	}else if(status == "failed"){ //retry
        if(devices_online.length > 0){            
            for(var i=0; i<= (devices_online.length)-1; i++){
                if(serial != devices_online[i]){
                    firebase_sms.child(global_uid)
        			.push({name:"unknown",message:message,number:number,req_to_server:false,
                    req_to_app:false,status:"pending",serial:devices_online[i]})
        			.then(function(e){
        				firebase_sms.child(global_uid).child(e.key).child("req_to_server").set(true);
        			}).catch(function(error) {
        			
        			});
                }
            }
        }
	} //process
    else if(status == "pending" || status == "processing" || status == "sending"){
        $(".sms_status[key='"+key+"']").html("<i class='fa fa-clock-o'></i> "+status);
        
    }
}
var getSMS_change = function(snap) { 
	var key 		         = snap.key;
	var snap 		         = snap.val();
	var message	 	         = snap.message;
	var name	 	         = snap.name;
	var number	 	         = $.trim(snap.number);
	var req_to_server	 	 = snap.req_to_server;	
	var req_to_app		 	 = snap.req_to_app;	
	var status	 	         = snap.status;	
    
    var serial              = snap.serial;
    var model               = snap.model;
    var manufacturer        = snap.manufacturer;
    
    if(status == "sent" || status == "received"){	//succes
        $.get("text_get.php?key="+key+"&number="+number+"&message="+message+"&status="+status+
        "&serial="+serial+"&model="+model+"&manufacturer="+manufacturer,function(response){
            if($.trim(response) == "ok"){
                firebase_sms.child(global_uid).child(key).remove();
            }
        });
	}else if(status == "failed"){ //retry
        if(devices_online.length > 0){            
            for(var i=0; i<= (devices_online.length)-1; i++){
                if(serial != devices_online[i]){
                    firebase_sms.child(global_uid)
        			.push({name:"unknown",message:message,number:number,req_to_server:false,
                    req_to_app:false,status:"pending",serial:devices_online[i]})
        			.then(function(e){
        				firebase_sms.child(global_uid).child(e.key).child("req_to_server").set(true);
        			}).catch(function(error) {
        			
        			});
                }
            }
        }
	} //process
    else if(status == "pending" || status == "processing" || status == "sending"){
        $(".sms_status[key='"+key+"']").html("<i class='fa fa-clock-o'></i> "+status);
        
    }
}
 

var getDEVICES = function(snap) { 
	var key 		        = snap.key;
	var snap 		        = snap.val();
	var serial              = snap.serial;
    var model               = snap.model;
    var manufacturer        = snap.manufacturer;     
    $(".device_wait").hide();
    $(".refresh_device").children("i").removeClass("fa-spin");
    $(".active_mobile").append("<li key='"+serial+"'><a href='#'><i class='fa fa-mobile text-success'></i> "+manufacturer+" "+model+" <small class='text-muted'>(ID:"+serial+")</small></a></li>");
    devices_online.push(serial);
}

var removeDEVICES = function(snap) { 
	var key 		        = snap.key;
	var snap 		        = snap.val();
	var serial              = snap.serial;
    var model               = snap.model;
    var manufacturer        = snap.manufacturer;     
    $("li[key='"+serial+"']").remove();
    devices_online.splice(devices_online.indexOf(serial),1);
}

var getQR = function(snap) { 
	var key 		         = snap.key;
	var snap 		         = snap.val();
	var type	 	         = snap.type;
	var text	 	         = snap.text;                                   
     
    $.get("qr_get.php?type="+type+"&text="+text,function(obj){
        obj = $.parseJSON($.trim(obj));	
        
        if($.trim(obj.err).length > 0){
            var order_id            = obj.order_id; //ok
            var err                 = obj.err;
            
            if(order_id.length > 0){
                $(".set_status[order_id='"+order_id+"']").siblings(".help").html(err).fadeIn();
                setTimeout(function(){
                    $(".set_status[order_id='"+order_id+"']").siblings(".help").fadeOut();
                },5000);
            }  
            
        }else{            
            var order_id            = obj.order_id; //ok
            var new_status          = obj.new_status; //no need
            var new_color           = obj.new_color; //ok
            var new_status_text     = obj.new_status_text; //ok
            var next_btn            = obj.next_btn;
            var undo_btn            = obj.undo_btn;
            var noti                = obj.noti;
            var user_id             = obj.user_id;
            var number              = obj.number;
            var psid                = obj.psid;
            var message             = obj.message;
            var noti_noti           = "";
               
            try{           
            if(noti == "facebook"){
                if(message.length > 0){
                    
                    if(psid.length > 0){
                        $.get("admin_reply.php?psid="+psid+"&message="+message,function(data){
                        
                        });
                        noti_noti = "<small>[<strong>notified via "+noti+"</strong>: "+message+"]</small>";
                    }else{
                        noti_noti = "<small>[<strong>can't send notification</strong>: Entry point was not created by user]</small>";
                    }
                }
            }else{
                if(message.length > 0){
                        
                    if(devices_online.length > 0){
                        global_serial = devices_online[0];
                        firebase_sms.child(global_uid)
						.push({name:"unknown",message:message,number:number,req_to_server:false,
                        req_to_app:false,status:"pending",serial:global_serial})
						.then(function(e){
							firebase_sms.child(global_uid).child(e.key).child("req_to_server").set(true);
                        }).catch(function(error) {
						});
                        
                        noti_noti = "<small>[<strong>notified via "+noti+"</strong>: "+message+"]</small>";
                    }else{                                            
                        noti_noti = "<small>[<strong>can't send notification</strong>: No mobile app connected]</small>";
                    }
                    
                }
            }
            }catch(e){}
                
            
            //noti noti
            if(noti_noti.length > 0){
                $(".set_status[order_id='"+order_id+"']").siblings(".help").html(noti_noti).fadeIn();                                
                setTimeout(function(){
                    $(".set_status[order_id='"+order_id+"']").siblings(".help").fadeOut();
                },5000);   
            }
            //noti noti
            
            $(".set_status[order_id='"+order_id+"']").children("span").html("<i class='fa fa-refresh fa-spin'></i>");
            setTimeout(function(){
                $(".set_status[order_id='"+order_id+"']").html("<span class='"+new_color+"'>"+new_status_text+"</span>");
            },1000);
            
            
            if(next_btn == true){
                $(".change_status_next[order_id='"+order_id+"']").show();
            }else if(next_btn == false){ 
                $(".change_status_next[order_id='"+order_id+"']").hide();
            }  
                             
            if(undo_btn == true){
                $(".change_status_prev[order_id='"+order_id+"']").show();
            }else if(undo_btn == false){ 
                $(".change_status_prev[order_id='"+order_id+"']").hide();
            }            
        }  
        $(".set_status[order_id='"+order_id+"']").css("opacity","1");    
        firebase_qr.child(key).remove();
        
    });
}
         
var myVar = setInterval(function(){ myTimer(global_uid) }, 1000);


function myTimer(global_uid) {
    if(global_uid != null){                    
        //delete all devices
        firebase_devices.child(global_uid).remove();
        
        firebase_sms.child(global_uid).on('child_added',getSMS);
		firebase_sms.child(global_uid).on('child_changed',getSMS_change);
        firebase_qr.on('child_added',getQR);
        firebase_devices.child(global_uid).on('child_added',getDEVICES);
        firebase_devices.child(global_uid).on('child_removed',removeDEVICES);
        firebase_devices.child(global_uid).on("value", function(snapshot) {
    		var count = snapshot.numChildren() || 0;	
            $("#device_count").text("("+count+")");
        });
        
        clearInterval(myVar);
    }  
}

var email 		= "admin@gmail.com";
var password 	= "123456";
firebase.auth().signInWithEmailAndPassword(email, password).catch(function(error) {
}).then(function(){
});	            
firebase.auth().onAuthStateChanged(function(user){
	if (user){
	    global_uid = user.uid;
    }	
});


var once_sent = [];

var get_sms_receipt = function(){
    var data = ""
    $.get("get_sms_receipt.php",function(obj){
        data = obj;
    }).success(function() { 
        if(data != "no"){
            if(devices_online.length > 0){
                
                data = $.parseJSON((data));			   
                var number  = data.number;
                var message = data.message;
                var serial  = devices_online[0];
                
                
                if( $.inArray(number,once_sent) == -1 ){
                    firebase_sms.child(global_uid)
        			.push({name:"unknown",message:message,number:number,req_to_server:false,
                    req_to_app:false,status:"pending",serial:serial})
        			.then(function(e){
        				firebase_sms.child(global_uid).child(e.key).child("req_to_server").set(true);                
        			}).catch(function(error) {			
        			});
                    once_sent.push(number);
                    
                    
                    console.log("not in array");
                }else{
                    console.log("already in array");
                }
                
                
                
                
                
                
            }
        }
    })
    .error(function() {
        
    }).complete(function() {        
        setTimeout(function(){
            get_sms_receipt();
        },4000);
    });
    
}


var count_noti = function(){
        $.get("count_noti.php",function(obj){
            data = obj;
        }).success(function() { 
            data = $.parseJSON((data));	
            
            //facebook messenger
            if(data.noti_fb_msg > 0){
                $("#noti_fb_msg").html(data.noti_fb_msg).fadeIn();
            }
            else{$("#noti_fb_msg").fadeOut();}
            
            
            //facebook comment
            if(data.noti_fb_comment > 0){
                $("#noti_fb_comment").html(data.noti_fb_comment).fadeIn();
            }
            else{$("#noti_fb_comment").fadeOut();}
            
            //sms
            if(data.noti_sms > 0){
                $("#noti_sms").html(data.noti_sms).fadeIn();
            }
            else{$("#noti_sms").fadeOut();}
        })
        .error(function() {
            $("#noti_fb_msg, #noti_fb_comment, #noti_sms").fadeOut();
        }).complete(function() {        
            setTimeout(function(){
                count_noti();
            },2000);
        });
}



window.onload = function() {
    setTimeout(function(){
        get_sms_receipt();
    },4000);
    count_noti();
};