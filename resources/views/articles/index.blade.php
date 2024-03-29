@extends('layouts.mainDash')

@section('content')

<div class="container-fluid">
@if (Session::has('success'))
               <div class="alert alert-warning alert-block">
                  <button type="button" class="close" data-dismiss="alert">×</button>
                  <strong>{{ Session::get('success') }}</strong>
               </div>
               <br>
           @php
               Session::forget('success');
           @endphp
@endif
@php
    session(['previous' => url()->current()]);
@endphp

<div class="smokey mt-5 p-3 p-md-5 border shadow">
<h1 class="d-flex align-items-center justify-content-between"><div><span class="text-primary fa fa-newspaper-o fa-1x mr-3"></span><span>Articles</span></div> @can('isViewer')
    @else <span class="d-none d-md-inline"><a href="{{ route('articles.create') }}" class="btn btn-primary">New Article</a></span>
        <span class="d-md-none d-inline"><a href="{{ route('articles.create') }}" class="btn btn-primary btn-sm">+</a></span>
@endcan
</h1>

<hr>
   @if (count($articles) > 0)
    <div class="row w-100">
        <div class="d-none d-md-block col-md-3 order-last">
            <div class="border-dark row">

                    <div class="card w-100">
                        <div style="font-size:18px;" class="bg-info text-center p-1 card-header text-white"><i class="px-1 fa fa-line-chart mr-1"></i>Top rated</div>
                            <div class="card-body">
                                @foreach($ratings as $rating)
                                    <div>
                                        <a href="{{ route('articles.show', $rating->id) }}"> {{ $rating->title }}</a>
                                        {{ $rating->rating }}
                                    </div>

                                @endforeach
                            </div>
                    </div>
            </div>

            <div class="mt-5 border-dark row">
                <div class="card w-100">
                    <div style="font-size:18px;" class="bg-warning text-center p-1 card-header text-white"><i class="px-1 fa fa-eye mr-1"></i>Most viewed</div>
                        <div class="card-body">
                            @foreach($views as $view)
                            <div>
                            <a href="{{ route('articles.show', $view->id) }}">{{ $view->title }}</a>
                            <i class="fa fa-eye mr-1"></i>{{ $view->views }}
                            </div>
                            @endforeach
                        </div>
                </div>
            </div>

            <div class="mt-5 border-dark row">
                <div class="card w-100">
                    <div style="font-size:18px;" class="bg-success text-center p-1 card-header text-white">Most recent</div>
                        <div class="card-body">
                            @foreach($recents as $recent)
                            <div>
                            <a href="{{ route('articles.show', $recent->id) }}">{{ $recent->title }}</a> - {{ \Carbon\Carbon::parse($recent->created_at)->diffForHumans() }}
                            </div>
                            @endforeach
                        </div>
                </div>
            </div>

            <div class="mt-5 border-dark row">
                <div class="card w-100">
                    <div style="font-size:18px;" class="bg-primary text-center p-1 card-header text-white">Top authors</div>
                        <div class="card-body">
                            @foreach($authors as $author)
                            <div>
                            <a href="#">{{ $author->name}}</a> - {{ $author->count }}&nbspContribution(s)
                            </div>
                            @endforeach
                        </div>
                </div>
            </div>

        </div>

        <div class="col-md-8 order-first">
            @foreach($articles as $article)
            <div class="py-2">
                    <div class="title-header"><a href="{{ route('articles.show', $article->id) }}" class="">{{ $article->title }} -</a>
                        <span>@if( Gate::check('isAdmin') || Gate::check('isEditor') )
                            <a href="{{ route('alter.alter', $article->id) }}" class="mr-1 px-1 py-0 btn btn-info btn-sm"><i class="fa fa-edit" aria-hidden="true"></i></a>
                            @endif
                            @if(Gate::check('isAdmin') || (Gate::check('isEditor') && Gate::check('canDelete')))
                                <a href="javascript:void(0)" onclick="remove(this)" class="mr-1 px-1 py-0 btn btn-danger btn-sm"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                <form action="{{ route('articles.destroy', $article->id) }}" method="post">
                                    @method('DELETE')
                                    {{ csrf_field() }}
                                </form>
                            @endif

                        </div>
                        </span>

                    <div class="pt-2">
                        <span class="fa fa-user mr-1">&nbspAuthored by&nbsp{{ $article->users->name }} - </span>
                        <span class="fa fa-eye mr-1">&nbsp Views&nbsp{{ $article->views }} - </span>
                        <span class="fa fa-calendar mr-1">&nbsp{{ \Carbon\Carbon::parse($article->created_at)->diffForHumans() }}</span>
                        <span>&nbsp-&nbsp{{ $article->kb}}</span>
            
                    </div>
            </div>
            @endforeach
        </div>

    </div>
    <br>
    <div style="" class="pagination">
        {{ $articles->links() }}
    </div>
</div>


@else
<hr>
<div class="w-100 border-top p-5 d-flex justify-content-center">
    <h3>Looks like you need to add an article!</h3>
</div>
@endif
</div>


<script src="{{asset('js/rater.min.js')}}"></script>
<script>

var options = {
    max_value: 5,
    step_size: 0.5,
    readonly: true,
}
$(".rating").rate(options);


function remove(e)
{
  Swal.fire({
    showClass: {
    popup: 'animate__animated animate__fadeInDown'
    },
  title: 'Are you sure?',
  text: "You won't be able to revert this!",
  icon: 'warning',
  showCancelButton: true,
  confirmButtonColor: '#3085d6',
  cancelButtonColor: '#d33',
  confirmButtonText: 'Yes, delete it!'
}).then((result) => {
  if (result.value) {
    $(e).parent().find('form').submit();
  } else {return}
})

}


</script>

@endsection
