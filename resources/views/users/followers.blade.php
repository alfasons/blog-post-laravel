<x-profile   :sharedData="$sharedData">
     <div class="list-group">
        @foreach ($followers as $follower)

        <a href="/profile/{{$follower->followedByUser->username}}" class="list-group-item list-group-item-action">
            <img class="avatar-tiny" src="{{$follower->followedByUser->avartar}}" />
            <strong>{{$follower->followedByUser->username}}</strong>
          </a>

        @endforeach


    </div>
</x-profile>
