$(".file-uploader").each(function( key, value ) {
			
	let button = $(this).find(".file-upload");
	let input = $(this).find(".file-uploader-input");
	let name = $(this).find(".file-uploader-name");
	let preview = $(this).find(".file-uploader-preview");

	button.click(function(){
		input.trigger('click');
	});

	input.change(function(){
		name.html($(this).val().split('\\').pop() );
		preview.empty();
		let image = $('<img src="" alt="">');

		image.attr('src', URL.createObjectURL(event.target.files[0])); 
		preview.append(image);

	});

});

/*
HTML MODEL

<div class="file-uploader">
	<label for="file-upload" class="file-upload">
		<i class="fas fa-cloud-upload-alt"></i> Destacada
	</label>

	<input name="imagen_destacada" class="file-uploader-input" type="file" accept="image/*">
	<div class="file-uploader-name"></div>

	<div class="file-uploader-preview">

	</div>
</div>

*/
		