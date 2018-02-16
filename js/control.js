var xmlHttp

function ascend(){
    publish_msg("test","AA10########################################################10ZZ",0)
    $('#commandSet').slideDown().html("Ascended 1mm")
}

function descend(){
    publish_msg("test","AA11########################################################10ZZ",0)
    $('#commandSet').slideDown().html("Decended 1mm")
}

function deleteRow(obj){
   var tr=obj.parentNode.parentNode;//得到按钮[obj]的父元素[td]的父元素[tr]
   tr.parentNode.removeChild(tr);//从tr的父元素[tbody]移除tr
}

$(document).ready(function(){
    $("#addTable").click(function(){
       var row = document.getElementsByName("delbtn");
       var tr="<tr>"+
       "<td><input type='text' name='position' placeholder='mm' class='form-control'/></td>"+
    //    "<td>&#12288</td>"+
       "<td><input type='text' name='delay' placeholder='s' class='form-control'/></td>"+
       "<td><button type='button' class='btn btn-danger deleteTable' name='delbtn' onclick='deleteRow(this)'>Delete</button></td></tr>";
       if (row.length < 6) {
           $("#timertable").append(tr);　　
       }
    });

    $("#submitTable").click(function(){
        var command="AASS"
        var timerSet = ""
        var position=document.getElementsByName("position");
        var delay=document.getElementsByName("delay");
        var len=position.length;
        for(var i = 0; i < len; i++){
            if (i != 0) {
                command += "&"
            }
            if (isPositiveInteger(position[i].value) && isPositiveInteger(delay[i].value)) {
                command += position[i].value + "=" + delay[i].value
                timerSet += "Stay at position " + position[i].value + " mm for " + delay[i].value + " seconds <br/>"
            }else {
                alert("Invalid Input!");
                return;
            }
        }
        while(command.length<60) {
            command += "#"
        }
        command += "10ZZ"
        publish_msg("test",command,0);
        $('#myModal').modal('hide')
        $('#commandSet').html(timerSet)
        $('#commandSet').show()
    });
});

function publish_msg(topic,message,Qos){
    if (topic=="") {
        alert('Topic has to be specified!')
        return
    }
    if (message=="") {
        alert('Message has to be specified!')
        return
    }
    xmlHttp=GetXmlHttpObject()
    if (xmlHttp==null){
        alert ("Browser does not support HTTP Request")
        return
    }
    var url="php/publish.php"
    message=escape(message)
    url=url+"?topic="+topic+"&message="+message+"&Qos="+Qos
    url=url+"&sid="+Math.random()
    xmlHttp.onreadystatechange=sentStatus
    xmlHttp.open("GET",url,true)
    xmlHttp.send(null)
}

function sentStatus(){
    if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete"){
        // document.getElementById("status").innerHTML="<div id='hint' class='alert alert-success center-block' role='alert' style='width:100%;'>Successfully Published!</div>"
        // setTimeout("$('#hint').fadeOut(1500);",1000);
    }
}

function GetXmlHttpObject(){
    var xmlHttp=null;
    try{
     // Firefox, Opera 8.0+, Safari
        xmlHttp=new XMLHttpRequest();
    }
    catch (e){
     //Internet Explorer
        try{
            xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
        }
        catch (e){
            xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
    }
    return xmlHttp;
}
