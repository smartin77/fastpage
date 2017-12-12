function enlarge(image, width, height){
	var width = (width == null) ? 800 : width;
	var height = (height == null) ? 600 : height;
	var large = window.open('','large','width='+width+',height='+height+',top=20,left=50');
	with(large.document){
		writeln('<html><head><title>::: 101 Penzion :::</title></head>');
		writeln('<body ######="self.focus()" style="margin:0; padding:0;">');
		writeln('<div style="text-align:center;">');
		writeln('<a href="javascript:close()">');
		writeln('<img src="'+image+'" border="0">');
		writeln('</a>');
		writeln('</div></body></html>');
		close();
	}
}



