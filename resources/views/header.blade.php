<html lang="tr-TR" xml:lang="tr">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, minimum-scale=1, user-scalable=no">
		<meta http-equiv="x-ua-compatible" content="ie=edge">
		<title>Webtures App - Pexels API</title>
		<meta name="description" content="Muharrem Etdöğer Webtures App - Pexels API Example">
		<link rel="shortcut icon" type="image/x-icon" href="https://www.webtures.com/assets/images/favicon.png" />
		<link rel="icon" href="https://www.webtures.com/assets/images/icons/webtures-32.png" sizes="32x32" />
		<link rel="icon" href="https://www.webtures.com/assets/images/icons/webtures-192.png" sizes="192x192" />
		<link rel="apple-touch-icon-precomposed" href="https://www.webtures.com/assets/images/icons/webtures-180.png" />
		<link rel="stylesheet" href="{{$resources_path}}/lity/lity.min.css">
		<link rel="stylesheet" href="{{$resources_path}}/bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" href="{{$resources_path}}/bootstrap/css/bootstrap-grid.min.css">
		<link rel="stylesheet" href="{{$resources_path}}/css/work.css">
	</head>
	<body <?php if(isset($body_class)){ echo 'class="'.$body_class.'"'; } ?>>
		<div class="webtures-app-header-area">
			<a class="webtures-logo-area" href="{{ url('')}}">
				<h1>PEXELS API</h1>
			</a>
			<div class="example-name-area">
				<h2>{{$pageTitle}}</h2>
			</div>
			@if (isset($showForm))
				<div class="webtures-app-pexels-form-area">
					<div class="webtures-app-pexels-form-area-container">
						<div class="webtures-app-pexels-form-area-form-box">
						    @if (isset($showWarningText))
								<div class="webtures-app-warning-label">{{$showWarningText}}</div>
							@endif
							<form action="" method="POST">
							    @csrf
								<input type="text" class="webtures-app-input-text" name="s" placeholder="Aramak İstediğiniz Kelimeyi Yazın (İngilizce)" required>
								<input type="submit" class="webtures-app-input-submit" value="GÖNDER">
							</form>
						</div>
					</div>
				</div>
			@else
	
			@endif			
		</div>