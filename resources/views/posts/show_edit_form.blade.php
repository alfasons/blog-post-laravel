<x-layout>
    <div class="container py-md-5 container--narrow">

        <p> <strong><a href="/posts/{{$post->id}}">&blacktriangleright; Back</a></strong></p>
        <form action="/posts/{{$post->id}}" method="POST">
            @csrf
            @method('PUT')
          <div class="form-group">
            <label for="post-title" class="text-muted mb-1"><small>Title</small></label>
            <input  name="title" id="post-title" class="form-control form-control-lg form-control-title" value="{{old('title',$post->title)}}" type="text" placeholder="" autocomplete="off" />
            @error('title')
                <div class='container constant'>
                    <div class="text-danger">{{$message}}</div>

                </div>
            @enderror
          </div>

          <div class="form-group">
            <label for="post-body" class="text-muted mb-1"><small>Body Content</small></label>
            <textarea  name="body" id="post-body" class="body-content tall-textarea form-control" type="text">{{old('body',$post->body)}}</textarea>
            @error('body')
            <div class='container constant'>
                <div class="text-danger">{{$message}}</div>

            </div>
            @enderror
          </div>

          <button class="btn btn-primary">Save New Post</button>
        </form>
      </div>
</x-layout>
