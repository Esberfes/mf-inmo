let errorName = "Image-loader error >>>> ";
let path = currentFolder();

document.addEventListener("DOMContentLoaded", function(event) {
    loadJSON(init);
});

function init(config) {
	
	if(!config) console.warn(errorName + "config file miss");	
    config = JSON.parse(config); 

    let inputFile = Array.from(document.getElementsByClassName("image-uploader"));
	
    for(let i = 0; i < inputFile.length; i++) {
        let element = inputFile[i];
		
        let nameInput = element.getAttribute("name");
		let accept = element.getAttribute("accept");
		let nameButton = element.getAttribute("name-button");
		
		accept = accept !== null ? accept : 'image/*';
		nameInput = nameInput !== null ? nameInput : '';
		nameButton = nameButton !== null ? nameButton : '';
		
		let input = createInputElement(nameInput, accept);
        element.appendChild(input);
		
		let button  = createButton(nameButton)
		element.appendChild(button);
		
		let nameContainer = createNameContainer();		
		element.appendChild(nameContainer);
		
		let previewContainer = createPreviewContainer();
		element.appendChild(previewContainer);
		
		let defaultImage = element.getAttribute("default");
		
		if(defaultImage !== null) createPreview(previewContainer, defaultImage);
		
		let maxSize = element.getAttribute("max-size") !== null ? element.getAttribute("max-size") : null;
		let limits = {
			maxWidth: element.getAttribute("max-width"),
			maxHeight: element.getAttribute("max-height"),
			minWidth: element.getAttribute("min-width") || 0,
			minHeight: element.getAttribute("min-height") || 0,
			maxSize: parseInt(maxSize)
		};
		
		button.onclick = function() {
			triggerEvent(input, 'click');
			console.log("click");
		};
		
		input.onchange = function(event) {
			nameContainer.innerHTML = input.value.split('\\').pop();
			previewContainer.innerHTML = "";
			let src = URL.createObjectURL(event.target.files[0]);
			let size = event.target.files[0].size / 1024;
			console.log(size, limits.maxSize)
			if(limits.maxSize) {
				if(size > limits.maxSize) {
					alert(`You have exceeded the maximum size ${limits.maxSize}kb`);
					nameContainer.innerHTML = "";
					return;
				}
			}
			createPreview(previewContainer, src);
			
			getImgSize(src, createInfoContainer, previewContainer, nameContainer, limits, input);
		}
		
    }
}

function createInfoContainer(dimension = {}, container, nameContainer, limits = {}, input ) {
	let infoContainer = document.createElement("DIV");
	infoContainer.style.padding = "5px;";
	infoContainer.style.width = "100%";
	let dimensionElement = document.createElement("span");
	dimensionElement.innerHTML = `Width: ${dimension.width} - Height: ${dimension.height}`;
	infoContainer.appendChild(dimensionElement);
	container.insertBefore(infoContainer, container.firstChild);
	
	if(limits.maxWidth) {
		if(dimension.height > limits.maxHeight ) {
			input.value = "";
			container.innerHTML = "";
			nameContainer.innerHTML = "";
			alert(`La imagen excede el ancho maximo ${limits.maxWidth}px`);
	   	}
	}
	
	if(limits.maxHeight) {
		if(dimension.width > limits.maxWidth ) {
			input.value = "";
			container.innerHTML = "";
			nameContainer.innerHTML = "";
			alert(`La imagen excede el alto maximo ${limits.maxHeight}px`);
	   	}
	}
	
	if(dimension.height < limits.minHeight ) {
		input.value = "";
		container.innerHTML = "";
		nameContainer.innerHTML = "";
		alert(`El alto minimo es ${limits.minHeight}px`);
	}
	
	if(dimension.width < limits.minWidth ) {
		input.value = "";
		container.innerHTML = "";
		nameContainer.innerHTML = "";
		alert(`El ancho minimo es ${limits.minWidth}px`);
	}
}

//image/gif, image/jpeg, image/png"
function createInputElement(name, accepts) {
    let inputElement = document.createElement("INPUT");
	
    inputElement.setAttribute("type", "file");   
    inputElement.setAttribute("name", name);
    inputElement.setAttribute("accept", accepts);
	inputElement.style.display = "none";
    return inputElement;
}

function createPreview(container, src){
	let image =  document.createElement("img");
	image.setAttribute("src", src);
	image.style.maxWidth = "100%";
	container.appendChild(image);
}

function createButton(name) {
	let buttonContainer = document.createElement("DIV");
	buttonContainer.classList.add("image-uploader-button");
	let image = document.createElement("img");
	image.setAttribute("src", path + "/css/image-uploader-icon.png"); 
	image.style.width = "25px";
	let tag = document.createElement("span");
	tag.innerHTML = name;
	if(name != '')
		tag.style.marginLeft = "5px";
	
	buttonContainer.appendChild(image);
	buttonContainer.appendChild(tag);
	
	return buttonContainer;
}
function createNameContainer() {
	let nameElement = document.createElement("DIV");
	nameElement.classList.add("image-uploader-name");
	return nameElement;
}

function createPreviewContainer() {
	let previewElement = document.createElement("DIV");
	previewElement.classList.add("image-uploader-preview");
	previewElement.style.position = "relative";
	previewElement.style.marginTop = "15px";
	return previewElement;
}

function loadJSON(callback) {   

    var xobj = new XMLHttpRequest();
        xobj.overrideMimeType("application/json");
    xobj.open('GET', path + '/config.json', true);
    xobj.onreadystatechange = function () {
          if (xobj.readyState == 4 && xobj.status == "200") {
            callback(xobj.responseText);
          }
    };
    xobj.send(null);  
}

function currentFolder() {
	let folder = document.currentScript.src.split("/");
	folder.pop();
	return folder.join("/");
}

function triggerEvent(el, etype){
 	if (el.fireEvent) {
    	el.fireEvent('on' + etype);
  	} else {
    	var evObj = document.createEvent('MouseEvents');
    	evObj.initEvent(etype, true, false);
    	var canceled = !el.dispatchEvent(evObj);
    	if (canceled) {
      		console.log("automatic click canceled");
    	} 
  	}
}

function getImgSize(imgSrc, callback, container, nameContainer, limits, input) {
    var newImg = new Image();

    newImg.onload = function () {
        if (callback != undefined)
            callback({width: newImg.width, height: newImg.height}, container, nameContainer, limits, input)
    }

    newImg.src = imgSrc;
}
