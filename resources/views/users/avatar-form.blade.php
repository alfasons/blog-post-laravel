<x-layout>
<div class="container container--narrow">
<div class="row mt-5">
    <form action="/manage-avatar" method="POST" enctype="multipart/form-data" class="mb-3">
        @csrf
        <div class="col-md-12">
            <div class="form-group">
                <input type="file" name="avatar" id="avatar" >
                @error('avatar')
                  <p class="text-danger m-2">{{$message}}</p>
                @enderror
            </div>

        </div>
        <div class="col-md-12">
            <button type="submit" class="btn btn-success btn-sm"> save</button>
        </div>

      </form>
</div>

</div>
</x-layout>
