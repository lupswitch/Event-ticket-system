@extends('layouts.adminLayout.admin_design')

@section('content')

<!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Blog Posts</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="">Dashboard</a></li>
              <li class="breadcrumb-item active">Blog Posts</li>
            </ol>
          </div><!-- /.col -->

          <br><br>
         @include('layouts.errors2')
                   


        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

        <!-- Main content -->
        <div class="card">
            <div class="card-header">
            <p>
 			       <a href="{{ route('system-admin.posts.create') }}" class="btn btn-primary">Add New Post</a>
 	  	     </p>
            
            @if($posts->count() > 0)

              <h3 class="card-title">All Available Posts</h3>
             </div>
              <!-- /.card-header -->
              <div class="card-body">


                <table id="example1" class="table table-bordered table-striped table-responsive">
                  <thead>

                  <tr>
                    
                    <th>Image</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Body</th>
                    <th>Comments</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th>Action</th>

                  </tr>

                  </thead>

                  <tbody>
                  
                  @foreach($posts as $post)

                  <tr>

                    <td><a href="{{ asset($post->blogimage->imagename) }}"><img src="{{ asset($post->blogimage->imagename) }}" href="{{ asset($post->blogimage->imagename) }}" height="100px"/><a/></td> 
                    <td>{{ $post->title}}</td>
                    <td>{{ $post->description }}</td>
                    <td>{{ $post->body }}</td>
                          @foreach($post->postcomments as $comment)
                    <td>
                      <strong>Name</strong> {{ $comment->name }}
                      <strong>Message</strong> {{ $comment->message }}
                    </td>
                           @endforeach  
                    <td>{{ $post->created_at->toDayDateTimeString() }}</td>
                    <td>{{ $post->updated_at->toDayDateTimeString() }}</td>
                    <td>
                      <a href="{{ route('system-admin.posts.edit', $post->id) }}" class="btn btn-info">Edit</a>
                      {{-- <a href="" class="btn btn-success">View Comments</a> --}}
                      <a href="javascript:void(0)" onclick="$(this).parent().find('form').submit()" class="btn btn-danger">Delete</a>
                        <form action="{{ route('system-admin.posts.destroy', $post->id) }}" method="post">
                          @method('DELETE')
                          <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        </form>
                    </td>
                    
                  </tr>
                
                  @endforeach 
                  
                  </tbody>
                  <tfoot>

                  <tr>

                    <th>Image</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Body</th>
                    <th>Comments</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th>Action</th>
                  
                  </tr>
                  </tfoot>

                </table>
            
            @else
               <h3 class="card-title">No Available Post At The Moment</h3>
            @endif

          </div>
         
        </div>		

@endsection