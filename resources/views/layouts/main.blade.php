<!doctype html>
<html lang="en">
  <head>
  	<title>KB</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" href="{{ asset('/css/favicon.png') }}" type="image/x-icon"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>


    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">

    <script src="{{asset('js/jquery.treetable.js')}}"></script>
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Latest compiled and minified CSS -->
    
    <script src="{{asset('js/app.js')}}"></script>
    <link rel="stylesheet" href="{{asset('css/jquery.treetable.theme.default.css') }}" rel="stylesheet" type="text/css"/>
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">
    <link href="{{ asset ('css/jquery.treetable.css') }}" rel="stylesheet" type="text/css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.0.0/animate.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    


    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
            .card-img-top {
            width: 100%;
            height: 15vw;
            object-fit: cover;}
            .avatar {
        width: 52px;
        height: 52px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-family: sans-serif;
        color: #fff;
        font-weight: bold;
        font-size: 16px;
  }

    </style>
  </head>
  <body>


    <div id="app" class="wrapper back d-flex align-items-stretch">
			<nav class="" id="sidebar">
				<div class="custom-menu">
					<button type="button" id="sidebarCollapse" class="btn btn-primary">
                    <i class="fa fa-bars"></i>
                    <span class="sr-only">Toggle Menu</span>
                    </button>
                </div>
                <div class="p-2 d-flex justify-content-center">
                    @if(Auth::user()->avatar)
                    <div><a href="/profile"><div><img style="width: 52px; height: 52px;" class="rounded-circle" src="/storage/avatars/{{ Auth::user()->avatar }}"></img></div></a></div>
                    @else
                        <div><a href="/profile"><div class="avatar bg-@php echo(Session::get('avatarColour')); @endphp">@php echo(Session::get('avatarI')); @endphp</div></a></div>
                    @endif
                </div>
        <ul class="list-unstyled components mb-5">
          
          <li class="{{ (request()->is('articles_index')) ? 'active' : '' }}">
              <a href="{{ url('articles_index') }}"><span class="fa fa-newspaper-o mr-3"></span>Articles</a>
          </li>

          <li class="{{ (request()->is('articles/create')) ? 'active' : '' }}">
            <a href="{{ route('articles.create') }}"><span class="fa fa-plus mr-3"></span>New Article</a>
          </li>

          @if(Gate::check('stealth') && (Gate::check('isEditor') || Gate::check('isViewer')))
          @else
          <li class="{{ (request()->is('sections*')) ? 'active' : '' }}">
            <a href="{{ route('sections.index') }}"><span class="fa fa-sticky-note mr-3"></span>Sections</a>
          </li>
          @endif

          <li class="{{ (request()->is('searches*')) ? 'active' : '' }}" >
            <a href="{{ route('searches.index') }}"><span class="fa fa-search mr-3"></span>Search</a>
          </li>

          <li class="{{ (request()->is('settings*')) ? 'active' : '' }}">
            <a href="{{ route('settings.index') }}"><span class="fa fa-cog mr-3"></span>Settings</a>
          </li>

          @can('isAdmin')
          <li class="{{ (request()->is('admin*')) ? 'active' : '' }}">
            <a href="{{ route('admin.index') }}"><span class="fa fa-user mr-3"></span>Admin</a>
          </li>
          @endcan
          <li class="{{ (request()->is('drafts*')) ? 'active' : '' }}">
             <a href="{{ route('drafts.index') }}"><span class="fa fa-save mr-3"></span>Drafts
                @if (Session::get('count') < 5)
                    <span class="badge badge-pill badge-primary ml-2">@php echo(Session::get('count')); @endphp</span>
                @else
                    <span class="badge badge-pill badge-danger ml-2">@php echo(Session::get('count')); @endphp</span>
                @endif
            </a>
          </li>
          
        
            <li>
            <a href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                        document.getElementById('logout-form').submit();"><span class="fa fa-sign-out mr-3"></span>{{ __('Logout') }}</a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>


            </li>
        </ul>

    	</nav>

        <!-- Page Content  -->
            <div  id="content" class="pt-1 px-5">
                @yield('content')
            </div>
        </div>
    <script src="{{ asset('js/popper.js') }}"></script>


    <script>

        (function($) {

"use strict";

var fullHeight = function() {

    $('.js-fullheight').css('height', $(window).height());
    $(window).resize(function(){
        $('.js-fullheight').css('height', $(window).height());
    });

};
fullHeight();

$('#sidebarCollapse').on('click', function () {
  $('#sidebar').toggleClass('active');
});

})(jQuery);
    </script>


  </body>
</html>
