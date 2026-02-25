<div>
    {{-- If you look to others for fulfillment, you will never truly be fulfilled. --}}
    <div class="w-full h-[600px] rounded-2xl overflow-hidden border border-gray-200" id="alumni-map"></div>

{{-- 
@assets
@vite(['resources/js/alumni-map.js'])
@endassets
 --}}

@script
<script>
// Pass server data to JS in a safe way
window.__alumniPoints = @json($points, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
// Let the JS boot file know the data is here
document.dispatchEvent(new Event('alumni-map-data-ready'));
</script>
@endscript
</div>
