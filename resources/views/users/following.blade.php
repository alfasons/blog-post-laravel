<x-profile   :sharedData="$sharedData">
    <div class="list-group">
       @foreach ($following as $follow)

       <a href="/profile/{{$follow->followingUser->username}}" class="list-group-item list-group-item-action">
           <img class="avatar-tiny" src="{{$follow->followingUser->avartar}}" />
           <strong>{{$follow->followingUser->username}}</strong>
         </a>

       @endforeach


   </div>
</x-profile>
