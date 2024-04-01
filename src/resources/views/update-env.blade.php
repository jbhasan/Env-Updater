<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>ENV UPDATER :: UPDATE</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<link href='https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.42.2/codemirror.min.css' rel='stylesheet'>
	<link href='https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.42.2/theme/duotone-light.min.css' rel='stylesheet'>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap-confirmation2/dist/bootstrap-confirmation.min.js"></script>

    <script src='https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.42.2/codemirror.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.42.2/mode/clike/clike.min.js'></script>
    <style>
		.CodeMirror {
			height: calc(100vh - 200px);
		}
		.code-container {
			position: relative;
			margin-bottom: 1.5rem;
			overflow: hidden;
			border-radius: 3px;
			box-shadow: 3px 3px 6px rgba(0, 0, 0, .3);
		}
		.code-container:last-child {
			margin-bottom: 0;
		}
		.login-page {
			width: 100%;
			height: 100vh;
			display: flex;
			align-items: center;
		}
	</style>
</head>
<body>
	<div class="login-page bg-light">
		<div class="container">
			<div class="row">
				<div class="col-lg-10 offset-lg-1">
					<h3 class="mb-3">Update {{ $file_name }}</h3>
					<div class="shadow rounded">
						@if(session('error'))
						<div class="alert alert-danger">{{ session('error') }}</div>
						@endif
						@if(session('success'))
						<div class="alert alert-success">{{ session('success') }}</div>
						@endif
						
						<form action="/env-updater/update" method="post">
							@csrf
							<div class="d-flex justify-content-between p-3">
								<button class="btn btn-sm btn-success" data-toggle="confirmation" data-btn-ok-label="Yes" data-btn-ok-class="btn-success p-2" data-btn-ok-icon-class="fa fa-check pr-2" data-btn-cancel-label="No!!!" data-btn-cancel-class="btn-danger p-2" data-btn-cancel-icon-class="fa fa-close pr-2" data-title="Are you sure?" data-content="This might be dangerous">Save File</button>
								<a href="/env-updater/logout" class="btn btn-sm btn-danger">Logout</a>
							</div>
							<div class="code-container">
								<textarea id="editorData" name="edited_data" style="border:solid 1px silver">{{ $file_content }}</textarea>
							</div>
						</form>
					</div>
					<p class="text-end text-secondary mt-3"><a href="https://github.com/jbhasan">@ Sayeed</a></p>
				</div>
			</div>
		</div>
	</div>

	<script>
		var editorContainer = document.getElementById('editorData')
		CodeMirror.fromTextArea(document.getElementById('editorData'), {
			lineNumbers: true,
			mode: 'text/x-csrc',
			theme: 'duotone-light'
		})
		$(document).ready(function() {
			$('[data-toggle=confirmation]').confirmation({
				rootSelector: '[data-toggle=confirmation]'
			});
		})
	</script>
</body>
</html>