<!DOCTYPE html>
<html lang="en">

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>@yield("title") | Akij Group Admin Panel</title>
	<!-- base:css -->
	
	<link rel="stylesheet" href="{{ asset('public/assets/vendors/mdi/css/materialdesignicons.min.css') }}">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link rel="stylesheet" href="https://cdn.materialdesignicons.com/3.6.95/css/materialdesignicons.min.css">

	<link rel="stylesheet" href="{{ asset('public/assets/vendors/flag-icon-css/css/flag-icon.min.css') }}">

	<!-- plugin css for this page -->
	<link rel="stylesheet" href="{{ asset('public/assets/vendors/summernote/dist/summernote-bs4.css') }}">
  	<link rel="stylesheet" href="{{ asset('public/assets/vendors/quill/quill.snow.css') }}">
  	<link rel="stylesheet" href="{{ asset('public/assets/vendors/simplemde/simplemde.min.css') }}">
  	<!-- End plugin css for this page -->

	<!-- Noty Css -->
	<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noty/3.1.4/noty.css"> -->
	<link rel="stylesheet" href="{{ asset('public/assets/css/noty.min.css') }}">
	<!-- End of Noty Css -->

	<link rel="stylesheet" href="{{ asset('public/assets/css/vertical-layout-light/style.css') }}">
	<!-- endinject -->
	<link rel="shortcut icon" href="{{ asset('public/assets/images/favicon.png') }}" />
</head>
<body>
	<div class="container-scroller">
		<!-- partial:partials/_navbar.html -->
		@include("layouts.partials.menu")
		<!-- partial -->
		<div class="container-fluid page-body-wrapper">

			<!-- partial:partials/_sidebar.html -->
			@include("layouts.partials.sidebar")
			<!-- partial -->
			<div class="main-panel">
				<div class="content-wrapper">
				<!-- Success/Error message -->
					<!-- <div id="confirmation-message">{!! session()->get("message") !!}</div> -->
					@include('messages')
				<!-- End of Success/Error message -->
				@yield("content")
				{{-- Delete Alert Modal --}}
				{{-- @include('delete-alert-modal') --}}
				{{-- End of Delete Alert Modal --}}
				</div>
				<!-- content-wrapper ends -->
				<!-- partial:partials/_footer.html -->
		<div class="footer-wrapper">
		<footer class="footer">
			<div class="d-sm-flex justify-content-center justify-content-sm-between">
			<span class="text-center text-sm-left d-block d-sm-inline-block">Copyright &copy; 2019. All rights reserved. </span>
			<span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Designed by: <a href="https://www.urbanui.com/" target="_blank">UrbanUI</a>. </span>
			</div>
		</footer>
		</div>
		<!-- partial -->
			<!-- main-panel ends -->
			</div>
		<!-- page-body-wrapper ends -->
		</div>
	</div>
	<!-- container-scroller -->
	<!-- base:js -->
	<script src="{{ asset('public/assets/vendors/js/vendor.bundle.base.js') }}"></script>
	<!-- endinject -->
	<!-- Plugin js for this page-->
	<script src="{{ asset('public/assets/vendors/progressbar.js/progressbar.min.js') }}"></script>
	<script src="{{ asset('public/assets/vendors/flot/jquery.flot.js') }}"></script>
	<script src="{{ asset('public/assets/vendors/flot/jquery.flot.resize.js') }}"></script>
	<script src="{{ asset('public/assets/vendors/flot/curvedLines.js') }}"></script>
	<script src="{{ asset('public/assets/vendors/chart.js/Chart.min.js') }}"></script>
	<!-- End plugin js for this page-->
	<!-- inject:js -->
	<script src="{{ asset('public/assets/js/off-canvas.js') }}"></script>
	<script src="{{ asset('public/assets/js/hoverable-collapse.js') }}"></script>
	<script src="{{ asset('public/assets/js/template.js') }}"></script>
	<script src="{{ asset('public/assets/js/settings.js') }}"></script>
	<script src="{{ asset('public/assets/js/todolist.js') }}"></script>
	<!-- endinject -->
	<!-- plugin js for this page -->
	<script src="{{ asset('public/assets/vendors/summernote/dist/summernote-bs4.min.js') }}"></script>
	<script src="{{ asset('public/assets/vendors/tinymce/tinymce.min.js') }}"></script>
	<script src="{{ asset('public/assets/vendors/quill/quill.min.js') }}"></script>
	<script src="{{ asset('public/assets/vendors/simplemde/simplemde.min.js') }}"></script>
	<!-- End plugin js for this page -->
	<!-- Custom js for this page-->
	<script src="{{ asset('public/assets/js/editorDemo.js') }}"></script>
	<!-- End custom js for this page-->
	<!-- Custom js for this page-->
	<script src="{{ asset('public/assets/js/dashboard.js') }}"></script>
	<!-- End custom js for this page-->
	<!-- Noty Js -->
	<script src="{{ asset('public/assets/js/noty.min.js') }}"></script>
	@if (Session::has('success'))
	<script>
		new Noty({
			theme: 'sunset',
			type: 'success',
			layout: 'topCenter',
			text: "{!! Session::get('success') !!}",
			timeout: 2000
		}).show();
	</script>
	@endif

	@if (Session::has('error'))
		<script>		
			var notify = $.notify("{!! Session::get('error') !!}", {
					type: 'theme',
					allow_dismiss: true,
					delay: 2000,
					timer: 300
				});
		</script>
	@endif
	<!-- End of Noty Js -->
	<script>
        $(document).ready(function(){

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            
            $('.alert-dialog').click(function(){
                var message = $(this).attr('data-message');
                $("#delete-alert-modal #deleteAlertFrm").attr('action',$(this).attr('data-action'));
                $("#delete-alert-modal #deleteAlertFrm .modal-body").html(message);
                $("input[name=hdnResource]").val($(this).attr('data-id'));                
                $("input[name=hdnMetaData]").val($(this).attr('data-meta'));

                var delete_button = $("#delete-alert-modal button[type=submit]")

                var counter = 3;
                
                delete_button.text("Yes ("+counter+")").prop('disabled', true);

                setInterval(function() {
                    counter--;
                    if (counter > 0) {
                        delete_button.text("Yes ("+counter+")");
                    }

                    if (counter === 0) {
                        delete_button.text("Yes").prop('disabled', false);
                    }
                    
                }, 1000);

                $("#delete-alert-modal").modal('show');
			});

        });
	</script>
	
	@yield('script')
</body>

</html>