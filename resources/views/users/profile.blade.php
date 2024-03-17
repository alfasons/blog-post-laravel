<x-profile   :sharedData="$sharedData" doctitle=" profile">
     <div class="list-group">
        @foreach ($posts as $post)
        <x-post :post="$post" showAuthor/>
        @endforeach
    </div>
</x-profile>
