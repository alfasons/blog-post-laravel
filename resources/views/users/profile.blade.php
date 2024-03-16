<x-profile   :sharedData="$sharedData">
     <div class="list-group">
        @foreach ($posts as $post)

        <a href="/posts/{{$post->id}}" class="list-group-item list-group-item-action">
            <img class="avatar-tiny" src="{{$post->user->avartar}}" />
            <strong>{{$post->title}}</strong> on {{$post->created_at->format('n/j/m/Y')}}
          </a>

        @endforeach


    </div>
</x-profile>
