<h2 class="wise1text">Gallery</h2>
<p class="text-center">Click on images to see bigger versions.</p>
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 mt-2 venobox">
    @foreach($galleryImages as $image)
        <a href="{{ asset($image['original']) }}" class="venobox" data-gall="gallery">
            <img src="{{ asset($image['thumbnail']) }}" alt="Gallery Image" class="w-full h-auto rounded shadow-lg border border-gray-300 dark:border-gray-700">
        </a>
    @endforeach
</div>

<script> 
	new VenoBox({
	    selector: '.venobox',
	    border: '5px',
	    numeration: true,
	    infinigall: true,
	    navigation: true,
	    spinner: 'wave'
	});
</script>