<!DOCTYPE html>
<html>
	<head>
		@include('admin.layout.main_header')

		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
		<title>{{$link}}</title>
    	<link rel="shortcut icon" href="/assets/back/assets/images/logo.png">
		@csrf
		<style type="text/css">
			body{
				background-color: #F7F7F7!important;
			}
			html, body, .main, .tabs, .tabbed-content { float: none !important; }
			.button{
				background: #006BE6;
				box-shadow: 0px 4px 8px rgba(0, 107, 230, 0.15);
				border-radius: 4px;
				color: #FFFFFF;
			}
			.button:hover{
				background: #0052b0;
				color: #FFFFFF;
				transition: .5s
				color: #FFFFFF;
			}
			.contract_editor::-webkit-scrollbar {
				width: 18px;
			}
			.contract_editor::-webkit-scrollbar-thumb {
				background: #006BE6;
				background-clip: content-box;
				border-right: 16px solid white;
				border-top: 20px solid transparent;
				border-bottom: 20px solid transparent;
				border-radius: 0;
				outline: none;
			}
			.contract_editor::-webkit-scrollbar-track {
				-webkit-box-shadow: none;
				background: #F0F7FF;
				border-right: 16px solid white;
				border-top: 20px solid white;
				border-bottom: 20px solid white;
				border-radius: 12px;
			}
			.contract_editor{
				scroll-margin-right: 5px;
				scroll-margin-left: 5px;
				overflow: auto;
				/* margin-right: 5px; */
				padding-bottom: 2px;
				height: calc(100% - 56px - 80px);
			}
			.contract_editor{
				position: relative;
				display: block;
				width: 50%;
				margin: 20px auto 20px auto;
				background: #fff;
				padding: 20px 40px;
				background: #FFFFFF;
				box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.05);
				overflow: auto;
				border-radius: 12px;
				height: 95vh;
			}
			.buttons-group{
				position: fixed;
				z-index: 99;
				top: 20px !important;
				right: 20px !important;
			}
			.contract_editor P {
				/*font-size:11px!important;*/
			}
			
			@font-face{  
				font-family: GHEA grapalat; 
				src: url('/fonts/gheagrpalatreg-webfont.woff') format('woff'), 
			}
			#content *{
				color: black !important;
				font-family: GHEA grapalat; 
			}
			#content table tr td template span span{
				font-size: 8pt;
			}
			#content table tr td template span{
				font-size: 8pt;
			}
			@media only screen and (max-width: 500px) {
				.contract_editor{
					position: relative;
					display: block;
					width: 90vw;
					margin: 100px auto 20px auto;
					background: #fff;
					padding: 20px 40px;
					height: 85vh;
					overflow: auto;
					padding: 10px 20px
				}
				.buttons-group{
					top: 20px !important;
					right: 0px !important;
					left: 0px !important;
					margin: auto;
					text-align: center;
				}
			}
		
		</style>
	</head>
	<body>
		<div class="buttons-group">
			<div data-name="{{ $link }}" is-with-footer="{{ $is_with_footer }}" class="btn button btn-lg pdf_save mr-2">
				<i class="mr-2 fa fa-file-pdf" aria-hidden="true"></i>
				Բեռնել PDF
			</div>
			<div data-name="{{ $link }}" is-with-footer="{{ $is_with_footer }}" class="btn button btn-lg doc_save">
				<i class="mr-2 fas fa-file-word"></i>
				Բեռնել DOC
			</div>
		</div>
		<div class ="contract_editor">
			<div id="content">
				{!! $html !!}
			</div>
			<a href="#" id="download-link" download target="_blank" hidden></a>
		</div>
	</body>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script type="text/javascript">
		$(".pdf_save").click(function(){
			$("#content").css("text-align", "justify");
			var name  = $(this).attr("data-name");
			var isWithFooter  = $(this).attr("is-with-footer");
			var file  = "pdf";
			var html  = $("#content").html();
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('input[name="_token"]').val()
				}
			});
			$.ajax({
				type: "POST",
				url: "/download/document",
				dataType: "json",
				data: {name: name, file: file, html: html, is_with_footer: isWithFooter},
				success: function(data){
						if(data.link != undefined){
							$("#download-link").attr("href",data.link);
							document.getElementById('download-link').click();
							$("#content").css("text-align", "left");
						}
				},
			});
		})	

		$(".doc_save").click(function(){
			$("#content").css("text-align", "justify");
			var name = $(this).attr("data-name");
			var isWithFooter  = $(this).attr("is-with-footer");
			var file  = "doc";
			var html  = $("#content").html(); 
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('input[name="_token"]').val()
				}
			});
			$.ajax({
				type: "POST",
				url: "/download/document",
				dataType: "json",
				data: {name: name, file: file, html: html, is_with_footer: isWithFooter},
				success: function(data){
						if(data.link != undefined){
							$("#download-link").attr("href",data.link);
							document.getElementById('download-link').click();
							$("#content").css("text-align", "left");
						} 
				},
			});
		})
		$("#content").find("*").css("font-size", "11pt");
		$("#content").find("*").css("color", "black");
		$("#content").find("table tr td").css("font-size", "8pt");
		$("#content").find("table tr th").css("font-size", "8pt");

		$("#content").find("table tr td span").css("font-size", "8pt");
		$("#content").find("table tr th span").css("font-size", "8pt");

		$("#content").find(".announce_conc_table tr td span").css("font-size", "6pt");
		$("#content").find(".announce_conc_table tr th span").css("font-size", "6pt");
		$("#content").find(".announce_conc_table tr td").css("font-size", "6pt");
		$("#content").find(".announce_conc_table tr th").css("font-size", "6pt");

		$("#content").find("table tr td span span").css("font-size", "8pt");
		$("#content").find("table tr td p").css("font-size", "8pt");
		$("#content").find("*").css("font-weight", "400");
		$("#content").find("*").css("font-family", "GHEA grapalat");
		$("#content").find(".ft-11").css("font-size", "11pt");
		$("#content").find(".ft-table").find('tr td').css("font-size", "8pt");
		$("#content").find(".ft-table").find('tr th').css("font-size", "8pt");
		$("#content").find(".ft-table").find('tr td').css("padding", "3px 2px");
		$("#content").find(".ft-table").find('tr th').css("padding", "3px 2px");
		$("#content").find(".ft-table").find('tr th span').css("font-size", "8pt");
		$("#content").find(".ft-table").find('tr td span').css("font-size", "8pt");
		$("#content").find(".ft-table").find('tr th p').css("font-size", "8pt");
		$("#content").find(".ft-table").find('tr td p').css("font-size", "8pt");
		$("#content").find(".ft-6").css("font-size", "6pt");
		$("#content").find(".ft-11").css("font-size", "11pt");
		$("#content").find(".ft-11 span").css("font-size", "11pt");

		$("#content").find(".ft-7").css("font-size", "7pt");
		$("#content").find(".ft-7 tr td").css("font-size", "7pt");
		$("#content").find(".ft-7 tr th").css("font-size", "7pt");
		$("#content").find(".ft-7 tr th span").css("font-size", "7pt");
		$("#content").find(".ft-7 tr td span").css("font-size", "7pt");

		$("#content").find(".ft-5").css("font-size", "5pt");
		$("#content").find(".ft-5 tr td").css("font-size", "5pt");
		$("#content").find(".ft-5 tr th").css("font-size", "5pt");
		$("#content").find(".ft-5 tr th span").css("font-size", "5pt");
		$("#content").find(".ft-5 tr td span").css("font-size", "5pt");

		$("#content").find(".page-break").attr('style', 
			`display: block !important;
			page-break-before: always !important;
			position: relative !important;`
		);
		$("#content").find("p").css("margin-bottom", "0");
		$("#content").find(".rotatedText").css("mso-rotate", "90");
	</script>
</html>