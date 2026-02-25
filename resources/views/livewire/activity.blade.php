<div>
  <livewire:post-form />

  @foreach($posts as $post)
    <livewire:post-card :post="$post" :wire:key="'post-'.$post->id" />
      
  @endforeach

  <div class="text-center mt-3">
    @if($hasMore)
      <button wire:click="loadMore" class="btn btn-outline-secondary">Load more</button>
    @endif
  </div>
</div>
