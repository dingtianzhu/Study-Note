var http= require('http');
http.createServer(function  (req,res){
	res.writeHead(200,{'Content-Type':'text/html; charset=utf-8'});
	if(req.url!=='/favicon.ico'){//清除二次访问
		console.log('访问');
		res.write('hello world');
		res.end('你好，世界');
	}
}).listen(8000);
console.log('The server');
