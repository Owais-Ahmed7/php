<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="style.css">
	
	<script type = "text/javascript">

	function onConnectionLost(){
	console.log("connection lost");
	connected_flag=0;
	}
	function onFailure(message) {
		console.log("Failed");
		document.getElementById("messages").innerHTML = "Connection Failed- Retrying";
        setTimeout(MQTTconnect, reconnectTimeout);
        }
			
	function onConnected(recon,url){
	console.log(" in onConnected " +reconn);
	}
	function onConnect() {
	  // Once a connection has been made, make a subscription and send a message.
	document.getElementById("messages").innerHTML ="Connected to "+host +"on port "+port;
	connected_flag=1;
	console.log("on Connect "+connected_flag);

	  }
	  function disconnect()
	  {
		if (connected_flag==1)
			mqtt.disconnect();
	  }

	// Called when a message arrives

    function onMessageArrived(message) {
    console.log("onMessageArrived: " + message.payloadString);
    document.getElementById("messages").innerHTML += '<span>Topic: ' + message.destinationName + '  | ' + message.payloadString + '</span><br/>';
    updateScroll(); // Scroll to bottom of window
	}
// Updates #messages div to auto-scroll
	function updateScroll() {
    var element = document.getElementById("messages");
    element.scrollTop = element.scrollHeight;
	}	

	
	
		
	function MQTTconnect() {
	var clean_sessions="true";
	var user_name="ubuntu";
	console.log("clean= "+clean_sessions);
	var password="somepassord";
	
	document.getElementById("messages").innerHTML ="";
	var s = "server.com";
	var p = "3033";
	if (p!="")
	{
		port=parseInt(p);
		}
	if (s!="")
	{
		//host=s;
		console.log("host");
		}

	console.log("connecting to "+ host +" "+ port +"clean session="+clean_sessions);
	console.log("user "+user_name);
	document.getElementById("messages").innerHTML='connecting';
	var randLetter = String.fromCharCode(65 + Math.floor(Math.random() * 26));
	var uniqid = randLetter + Date.now();	
	mqtt = new Paho.MQTT.Client(host,port,uniqid);
	//document.write("connecting to "+ host);
	var options = {
        timeout: 3,
		cleanSession: clean_sessions,
		onSuccess: onConnect,
		onFailure: onFailure,
      
     };
	 if (user_name !="")
		options.userName=document.forms["connform"]["username"].value;
	if (password !="")
		options.password=document.forms["connform"]["password"].value;
	
        mqtt.onConnectionLost = onConnectionLost;
        mqtt.onMessageArrived = onMessageArrived;
		mqtt.onConnected = onConnected;

	mqtt.connect(options);
	return false;
  
 
	}
	function sub_topics(){
		document.getElementById("messages").innerHTML ="";
		if (connected_flag==0){
		out_msg="<b>Not Connected so can't subscribe</b>"
		console.log(out_msg);
		document.getElementById("status_messages").innerHTML = out_msg;
		return false;
		}
	var stopic= "EC:94:CB:49:A3:D0/result";
	console.log("here");
	var sqos="2";
	if (sqos>2)
		sqos=0;
	console.log("Subscribing to topic ="+stopic +" QOS " +sqos);
	document.getElementById("status_messages").innerHTML = "Subscribing to topic ="+stopic;
	var soptions={
	qos:sqos,
	};
	mqtt.subscribe(stopic,soptions);
	return false;
	}
	function send_message(buttonElement){
		document.getElementById("status_messages").innerHTML ="";
//		alert(buttonElement.id);
		if (connected_flag==0){
		out_msg="<b>Not Connected so can't send</b>"
		console.log(out_msg);
		document.getElementById("status_messages").innerHTML = out_msg;
		return false;
		}
	var pqos = 2;
	var topic = document.forms["smessage"]["Ptopic"].value;
	var retain_flag=false;
	var buttonClickedId = buttonElement.id;
		if( buttonClickedId === 'btn1' ){
//			alert(buttonElement.id);
			var msg2 = '<dd>';
     			console.log(topic+"sending message   "+msg2+" to "+topic);
			message = new Paho.MQTT.Message(msg2);
			message.destinationName = topic;
			message.qos=pqos;
			message.retained=retain_flag;
			mqtt.send(message);
		return false;
		};
		 if( buttonClickedId === 'btn2' ){
//                      alert(buttonElement.id);
			var msg2 = "<?php echo date('Y-m-d', strtotime("+1 day"));?>";
			var fmsg2 = "<fd,"+msg2+">"; 
			console.log(topic+"sending message   "+msg2+" to "+topic);
                        message = new Paho.MQTT.Message(fmsg2);
                        message.destinationName = topic;
                        message.qos=pqos;
                        message.retained=retain_flag;
                        mqtt.send(message);
                return false;
                };
		 if( buttonClickedId === 'btn3' ){
//                      alert(buttonElement.id);
                        var msg2 = "<ef>";
                        console.log(topic+"sending message   "+msg2+" to "+topic);
                        message = new Paho.MQTT.Message(msg2);
                        message.destinationName = topic;
                        message.qos=pqos;
                        message.retained=retain_flag;
                        mqtt.send(message);
                return false;
		};
		if( buttonClickedId === 'btn4' ){
//                      alert(buttonElement.id);
                        var msg2 = "<pc>";
                        console.log(topic+"sending message   "+msg2+" to "+topic);
                        message = new Paho.MQTT.Message(msg2);
                        message.destinationName = topic;
                        message.qos=pqos;
                        message.retained=retain_flag;
                        mqtt.send(message);
                return false;
                };

	}


	
    </script>


</head>
	

<body>
    <div class="row">
        <h3>Therap<span style="color: #b0b872">eutics</span> Controlled Substance Management</h3>
        <div class="button-area">
            <button id="btn1" class="glow-on-hover" type="button" onclick="return send_message(this)">Open Unit</button><br/>
			<button id="btn2" class="glow-on-hover" type="button" onclick="return send_message(this)">Refill</button><br/>
			<button id="btn3" class="glow-on-hover" type="button" onclick="return send_message(this)">Enroll Finger</button><br/>
			<button id="btn4" class="glow-on-hover" type="button" onclick="return send_message(this)">Show Info</button><br/>

      </div>
         <div id="messages"></div>

    </div>
	<script>
	var connected_flag=0	
	var mqtt;
    var reconnectTimeout = 2000;
	var host="someserver.com";
	var port=1883;
	var row=25;
	var out_msg="";
	var mcount=0;
	</script>
	<script type = "text/javascript">
		MQTTconnect();
	</script>
</body>
<script>
    setInterval(function() {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("text-area").innerHTML = this.responseText;
            }
        };
       
    }, 3000);
</script>

</html>