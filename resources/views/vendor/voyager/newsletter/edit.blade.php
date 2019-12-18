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
    <link href="{{ asset('admin/assets/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{ asset('admin/assets/vendor/lobicard/css/lobicard.css')}}" rel="stylesheet">
    <link href="{{ asset('admin/assets/vendor/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">
    <link href="{{ asset ('admin/assets/textboxio/resources/css/textboxio.css') }}" rel="stylesheet">

    <link href="{{ asset('admin/assets/vendor/themify-icons/css/themify-icons.css')}}" rel="stylesheet">
    <!--data table stylesheet-->
    <link href="{{ asset('admin/assets/vendor/data-tables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{ asset('admin/assets/vendor/data-tables/select.dataTables.min.css')}}" rel="stylesheet">
    <!--sweetalert stylesheet-->
    <link href="{{ asset('admin/assets/css/sweetalert2.min.css')}}" rel="stylesheet">
    <!--custom css-->
    <link href="{{ asset('admin/assets/css/main.css')}}" rel="stylesheet">
    <link href="{{ asset('admin/assets/css/custom.css')}}" rel="stylesheet">
</head>
<body>

<header class="app-header navbar">
        <div class="navbar-brand">
            <a class="" href="http://travvapi.herokuapp.com/admin">
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

 	<main class="main-content" id="app">
            <!--page title start-->
            <div class="row">
                <div class="col-md-6">
                    <div class="page-title">
                        <h4 class="mb-0"> Create New Email
                        </h4>
                       @include('vendor.voyager.newsletter.includes.messages')
                    </div>
                </div>
            </div>


            <!--page title end-->
            <div class="container-fluid">
            <form class="category-form" id="form" action="route('mails.update', $mail->id)" method="POST" enctype="multipart/form-data">
                {{ method_field('PUT') }}
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-sm-8">
                        <div class="card card-shadow mb-4">

                            <div class="card-body">
                                
                                    <div class="form-group">
                                        <label for="Post Title" class="sr-only">From</label>
                                        <input type="text" id="PostTitle" name="from" class="form-control" placeholder="From" required="" autocomplete="off" style="height: 48px;" value="{{$mail->from}}">                                      
                                    </div>
                                    <div class="form-group">
                                        <label for="Post Title" class="sr-only">Subject</label>
                                        <input type="text" id="PostTitle" name="subject" class="form-control" placeholder="Subject" required="" autocomplete="off" style="height: 48px;" value="{{$mail->subject}}">
                                    </div>

                                    <div>
                                        <textarea id="textbox" name="body" style="width: 100%; height: 400px;">
                                            {{$mail->body}}
                                        </textarea>
                                    </div>

                                    <div class="col-12 mt-3">
                                        <button id="publish" class="btn btn-info"> Send to All </button>
                                        
                                    </div>

                            </div>
                              
                        </div>
                    </div>

                </div>
            </form>
            </div>

        </main>

        <script src="{{ asset('admin/assets/vendor/jquery/jquery.min.js')}} "></script>
<script src="{{ asset('admin/assets/vendor/jquery-ui-1.12.1/jquery-ui.min.js')}} "></script>
<script src="{{ asset('admin/assets/vendor/popper.min.js')}} "></script>
<script src="{{ asset('admin/assets/vendor/bootstrap/js/bootstrap.min.js ')}}"></script>
<script src="{{ asset('admin/assets/vendor/lobicard/js/lobicard.js')}} "></script>
<script class="include " type="text/javascript " src="{{ asset('admin/assets/vendor/jquery.dcjqaccordion.2.7.js')}} "></script>

<!-- Data table Js Plugin -->
<script src="{{asset('admin/assets/vendor/data-tables/jquery.dataTables.min.js')}} "></script>
<script src="{{asset('admin/assets/vendor/data-tables/dataTables.bootstrap4.min.js')}}"></script>

<script src="{{ asset('admin/assets/textboxio/textboxio.js')}}"></script>
<!-- Dsweet alert Js Plugin -->
<script src="{{asset('admin/assets/js/sweetalert2.min.js')}}"></script>
<!--init scripts-->
<script src="{{asset('admin/assets/js/scripts.min.js')}} "></script>
<script src="{{asset('admin/assets/js/custom.js')}} "></script>
<script>
$(document).ready(function() {
    $('#bs4-table').DataTable({
        
        "ordering": false,
        "info": false
        
    });

    var editor = textboxio.replace('#textbox');

});
</script>

<script>
  $('ul.nav li.dropdown').hover(function() {
  $(this).find('.dropdown-menu').stop(true, true).delay(200).fadeIn(500);
}, function() {
  $(this).find('.dropdown-menu').stop(true, true).delay(200).fadeOut(500);
});
</script>

<script type="text/javascript">
    $('#delete-post').click(function(e){
        e.preventDefault() // Don't post the form, unless confirmed
        if (confirm('Are you sure?')) {
            // Post the form
            $(e.target).closest('form').submit() // Post the surrounding form
        }
    });
</script>


</body>
</html>