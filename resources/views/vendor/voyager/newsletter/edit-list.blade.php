<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="description" content="">
    <meta name="csrf_token" content="{{ csrf_token() }}">
    <meta name="author" content="CMS">
    <title>Dashboard</title>

    <!--google font-->
    <link href="https://fonts.googleapis.com/css?family=Lato:400,700,900" rel="stylesheet">
    <!--style sheets-->
    <link href="{{ asset('mail/assets/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{ asset('mail/assets/vendor/lobicard/css/lobicard.css')}}" rel="stylesheet">
    <link href="{{ asset('mail/assets/vendor/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">

    <link href="{{ asset('mail/assets/vendor/themify-icons/css/themify-icons.css')}}" rel="stylesheet">
    <!--data table stylesheet-->
    <link href="{{ asset('mail/assets/vendor/data-tables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{ asset('mail/assets/vendor/data-tables/select.dataTables.min.css')}}" rel="stylesheet">
    <!--sweetalert stylesheet-->
    <link href="{{ asset('mail/assets/css/sweetalert2.min.css')}}" rel="stylesheet">
    <!--custom css-->
    <link href="{{ asset('mail/assets/css/main.css')}}" rel="stylesheet">
    <link href="{{ asset('mail/assets/css/custom.css')}}" rel="stylesheet">
</head>
<body>

<header class="app-header navbar">
        <div class="navbar-brand">
            <a class="" href="/admin">
                <h4>TravCP</h4>
            </a>
        </div>
        <ul class="nav navbar-nav ml-auto" style="margin-right: 50px;">

            <!--Profile section-->
            <li class="nav-item dropdown dropdown-slide">
                <a class="nav-link nav-pill user-avatar" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                    <img src="/admin/avatars/{{Auth::user()->image}}" alt="">
                    {{Auth::user()->name}}
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-accout">
                    <div class="dropdown-header pb-3">
                        <div class="media d-user">
                            <img class="align-self-center mr-3" src="/admin/avatars/{{Auth::user()->image}}" alt="{{Auth::user()->name}}">
                            <div class="media-body">
                                <h5 class="mt-0 mb-0">{{Auth::user()->name}}</h5>
                                <span>{{Auth::user()->email}}</span>
                            </div>
                        </div>
                    </div>

                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"><i class=" ti-unlock"></i> Logout</a>
                                                     <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                </div>
            </li>
            <!--End of Profile section-->
        </ul>
    </header>

 	<main class="main-content">
            <!--page title start-->
            <div class="page-title">
                <h4 class="mb-0"> Email Newsletters</h4>
                
            </div>
            <!--page title end-->


            <div class="container-fluid">
             @include('vendor.voyager.newsletter.includes.messages')
             
                <!-- state start-->
                <div class="row">
                    <div class=" col-sm-12">
                        <div class="card card-shadow mb-4">
                            <div class="card-header">
                                <div class="addnew-btn">
                                    <a href="{{route('mail.create')}}" class=" btn btn-primary New_post">Create New Emails</a>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="bs4-table" class="table table-bordered table-striped" cellspacing="0 " width="100% ">
                                    <thead>
                                        <tr>
                                            <th>From</th>
                                            <th>Subject</th>
                                            <th>Date Posted</th>
                                            <th>Actions</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($mails as $mail)
                                        <tr>
                                            <td>{{$mail->from}}</td>
                                            <!--category foreach-->
                                            <td>{{$mail->subject}}</td>
                                            <!--endforeach-->
                                            <td>{{ date('F d, Y',  strtotime($mail->created_at)) }}</td>
                                            <td>
                                            <a href="{{ route('mail.edit', $mail->id) }}"><button class="btn Edit success"> Edit </button></a>
                                                <button class="btn Delete danger" >
                                                 <form id="delete-form-{{ $mail->id }}" method="POST" action="{{ route('mail.destroy', $mail->id) }}">
                                                	{{method_field('DELETE')}}
                                                	{{csrf_field()}}
                                                	<input type="submit" class="btn Delete danger" value="Delete"/>
                                            	</form>
                                            </td>
                                        </tr>
                                    @endforeach
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- state end-->

            </div>
        </main>

        <script src="{{ asset('mail/assets/vendor/jquery/jquery.min.js')}} "></script>
	<script src="{{ asset('mail/assets/vendor/jquery-ui-1.12.1/jquery-ui.min.js')}} "></script>
	<script src="{{ asset('mail/assets/vendor/popper.min.js')}} "></script>
	<script src="{{ asset('mail/assets/vendor/bootstrap/js/bootstrap.min.js ')}}"></script>
	<script src="{{ asset('mail/assets/vendor/lobicard/js/lobicard.js')}} "></script>
	<script class="include " type="text/javascript " src="{{ asset('mail/assets/vendor/jquery.dcjqaccordion.2.7.js')}} "></script>

<!-- Data table Js Plugin -->
<script src="{{asset('mail/assets/vendor/data-tables/jquery.dataTables.min.js')}} "></script>
<script src="{{asset('mail/assets/vendor/data-tables/dataTables.bootstrap4.min.js')}}"></script>

<!-- Dsweet alert Js Plugin -->
<script src="{{asset('mail/assets/js/sweetalert2.min.js')}}"></script>
<!--init scripts-->
<script src="{{asset('mail/assets/js/scripts.min.js')}} "></script>
<script src="{{asset('mail/assets/js/custom.js')}} "></script>
<script>
$(document).ready(function() {
    $('#bs4-table').DataTable({
        
        "ordering": false,
        "info": false
        
    });
});
</script>

<script>
  $('ul.nav li.dropdown').hover(function() {
  $(this).find('.dropdown-menu').stop(true, true).delay(200).fadeIn(500);
}, function() {
  $(this).find('.dropdown-menu').stop(true, true).delay(200).fadeOut(500);
});
</script>


</body>
</html>