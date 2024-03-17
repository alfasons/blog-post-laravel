<a href="/posts/{{$post->id}}" class="list-group-item list-group-item-action">
    <img class="avatar-tiny" src="{{$post->user->avartar}}" />
    <strong>{{$post->title}}</strong>
    <span class="small text-muted">
        @if (!isset($showAuthor))
        by {{$post->user->username}}
        @endif

        on {{$post->created_at->format('n/j/Y')}}
        </span>
</a>
