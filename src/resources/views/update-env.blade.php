<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>ENV UPDATER :: UPDATE</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<link href='https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.42.2/codemirror.min.css' rel='stylesheet'>
	<link href='https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.42.2/theme/duotone-light.min.css' rel='stylesheet'>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.42.2/addon/merge/merge.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap-confirmation2/dist/bootstrap-confirmation.min.js"></script>

    <script src='https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.42.2/codemirror.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.42.2/mode/clike/clike.min.js'></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/diff_match_patch/20121119/diff_match_patch.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.42.2/addon/merge/merge.js"></script>

    <style>
		.CodeMirror {
			height: calc(100vh - 200px);
		}
		span.clicky {
			cursor: pointer;
			background: #d70;
			color: white;
			padding: 0 3px;
			border-radius: 3px;
		}
		.change_histories {
			height: calc(100vh - 200px);
			overflow: auto;
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
		.highlight {background-color: #B4D5FF}
	</style>
</head>
<body>
	<div class="login-page bg-light">
		<div class="container">
			<div class="row">
				<div class="col-lg-10 offset-lg-1">
					@if(session('error'))
					<div class="alert alert-danger">{{ session('error') }}</div>
					@endif
					@if(session('success'))
					<div class="alert alert-success">{{ session('success') }}</div>
					@endif
					<h3 class="mb-3">Update {{ $file_name }}</h3>

					<div class="accordion" id="accordionExample">
						<button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">Edit {{ $file_name }}</button>
						<button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">Change Histories</button>
						
						<a href="/env-updater/logout" class="btn btn-sm btn-danger float-right">Logout</a>
					
						<div class="card">
							<div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
								<div class="card-body">
									<form action="/env-updater/update" method="post">
										@csrf
										<button class="btn btn-sm btn-outline-success w-100" data-toggle="confirmation" data-btn-ok-label="Yes" data-btn-ok-class="btn-success p-2" data-btn-ok-icon-class="fa fa-check pr-2" data-btn-cancel-label="No!!!" data-btn-cancel-class="btn-danger p-2" data-btn-cancel-icon-class="fa fa-close pr-2" data-title="Are you sure?" data-content="This might be dangerous">Save File</button>
										<div class="code-container">
											<textarea id="editorData" name="edited_data" style="border:solid 1px silver">{{ $file_content }}</textarea>
										</div>
									</form>
								</div>
							</div>
						
							<div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
								<div class="card-body">
									@if(!empty($permissionHistoryFileData))
										<div id="accordion" class="change_histories">
											@foreach($permissionHistoryFileData as $k => $datum)
												<div class="card">
													<div class="card-header p-0" id="heading_{{$k}}">
													<h5 class="mb-0 d-flex justify-content-between align-items-center">
														<button class="btn btn-link" style="font-size:70%" data-toggle="collapse" data-target="#collapse_{{$k}}" onclick="initMergerView('merge-view', '{{$k}}')" aria-expanded="true" aria-controls="collapse_{{$k}}">
														{{ $datum['email'] }} | {{ $datum['mobile'] }}
														</button>
														<small class="text-monospace text-muted pr-2" style="font-size:70%">{{ $datum['changed_at'] }}</small>
													</h5>
													</div>

													<div id="collapse_{{$k}}" class="collapse collapsed" aria-labelledby="heading_{{$k}}" data-parent="#accordion">
														<div class="card-body">
															<div id="prevData_{{$k}}" class="prevData d-none" data-key="{{$k}}">{!! (base64_decode($datum['prev_data'])) !!}</div>
															<div id="newData_{{$k}}" class="newData d-none" data-key="{{$k}}">{!! (base64_decode($datum['new_data'])) !!}</div>
															<div id="merge-view_{{$k}}"></div>
														</div>
													</div>
												</div>
											@endforeach
										</div>
									@else
										<div class="alert alert-info">No changes found yet</div>
									@endif
								</div>
							</div>
						</div>
					</div>
					<div class="mt-3 d-flex justify-content-between">
						<div>ENV Updater <small class="text-muted">{{ $version }}</small></div>
						<a class="text-end text-secondary" href="https://github.com/jbhasan">&copy; Sayeed</a>
					</div>
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
		});

		var value, orig1, orig2, dv, panes = 2, highlight = true, connect = "align", collapse = false;
		function initUI(viewId) {
			if (value == null) return;
			var target = document.getElementById(viewId);
			target.innerHTML = "";
			dv = CodeMirror.MergeView(target, {
				value: value,
				origLeft: panes == 3 ? orig1 : null,
				orig: orig2,
				lineNumbers: false,
				mode: "text/x-csrc",
				highlightDifferences: highlight,
				showDifferences: true,
				connect: connect,
				collapseIdentical: true,
				revertButtons: false,
				allowEditingOriginals: false,
				disableInput: true,
				dragDrop: false,
				fixedGutter: false,
				theme: 'duotone-light'
			});
		}
		function toggleDifferences() {
			dv.setShowDifferences(highlight = !highlight);
		}
		function initMergerView(viewId, index) {
			value = document.getElementById('prevData_'+index).innerHTML;
			value2 = document.getElementById('newData_'+index).innerHTML;
			orig1 = value;
			orig2 = value2;
			initUI(viewId+'_'+index);
			let d = document.createElement("div"); d.style.cssText = "width: 50px; margin: 7px; height: 14px"; dv.editor().addLineWidget(57, d)
			setTimeout(function() {
				$(".CodeMirror-merge-collapsed-widget").trigger('click');
			}, 500);
		};

		function mergeViewHeight(mergeView) {
			function editorHeight(editor) {
				if (!editor) return 0;
				return editor.getScrollInfo().height;
			}
			return Math.max(editorHeight(mergeView.leftOriginal()),
						editorHeight(mergeView.editor()),
						editorHeight(mergeView.rightOriginal()));
		}
		function resize(mergeView) {
			var height = mergeViewHeight(mergeView);
			for(;;) {
				if (mergeView.leftOriginal())
				mergeView.leftOriginal().setSize(null, height);
				mergeView.editor().setSize(null, height);
				if (mergeView.rightOriginal())
				mergeView.rightOriginal().setSize(null, height);

				var newHeight = mergeViewHeight(mergeView);
				if (newHeight >= height) break;
				else height = newHeight;
			}
			mergeView.wrap.style.height = height + "px";
		}
	</script>
</body>
</html>