var ws = require("nodejs-websocket");

var port = 3000;

var clientCount = 0;

var server = ws.createServer(function (conn) {
	// clientCount++;
	// var data = {};
  // data.type = "enter";
  // data.username = username;
	// broadcast(JSON.stringify(data));

	conn.on("text", function (data) {
    var msg = JSON.parse(data);
    str = msg["str"];
		console.log("Received "+str);
		var data = {};
    data.type = "message";
    data.username = msg["username"];
		data.msg = str;
		broadcast(JSON.stringify(data));
	})

	// conn.on("close", function (code, reason) {
	// 	console.log("Connection closed");
	// 	var data = {};
  //   data.type = "leave";
  //   data.username = username;
	// 	data.msg = ' left';
	// 	broadcast(JSON.stringify(data));
	// })

	conn.on("error",function(err){
		console.log("handle err");
		console.log(err);
	})
}).listen(port)

console.log("websocket server listening on port " + port);

function broadcast(str){
	server.connections.forEach(function(connection){
		connection.sendText(str);
	})
}