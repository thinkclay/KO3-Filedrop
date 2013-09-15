$(function () {
	/*
	var $link = $('<link>');
	$link.attr({
        type: 'text/css',
        rel: 'stylesheet',
        href: '/styles/filedrop/filedrop.less'
	});
	$('head').append($link); 

	var $filedrop = $('#module-filedrop');
	var $folder_list = $('#folder-list');
	var $folders = $folder_list.find('li.parent ul li');
	var $folder_delete = $folders.find('.delete');
	var $folder_new = $('#folder-new');
	var $file_list = $('#file-list'),
		$status = $filedrop.find('#file-status'),
		$upload = $filedrop.find('#file-upload');
	
	$folders.click(function () {
		if ($(this).attr('id') != 'folder-new') {
			$folders.removeClass('selected');
			$(this).addClass('selected');
		
			$.fn.filedrop.read($(this).attr('data-hash'), $file_list);
		}
	});
	
	$folder_list.find('li.parent ul li:first').click();
	
	$folder_delete.click(function(){
		$.fn.filedrop.remove($(this).parent().attr('data-hash'));
	});
	
	$folder_new.click( function () {
		var $input = $('<input type="text" value="Untitled Folder" />'),
			$new = $('<li class="editing"></li>').append($input);
		
		$(this).before($new);
		$input.focus();
		
		$input.blur( function () {
			$.fn.filedrop.create($(this).val());
		});
		
		return false;
	});
	
	$filedrop.filedrop({
	    fallback_id: 'file-upload-field',    // an identifier of a standard file input element
	    data: { 
	    	directory : $folder_list.find('li.selected').attr('data-directory'),
	    	hash : $folder_list.find('li.selected').attr('data-hash')
	    },
	    url: '/filedrop/upload',              // upload handler, handles each file separately
	    paramname: 'files',          // POST parameter name used on serverside to reference file
	    error: function(err, file) {
	        switch(err) {
	            case 'BrowserNotSupported':
	                alert('browser does not support html5 drag and drop');
	                break;
	                
	            case 'TooManyFiles':
	                // user uploaded more than 'maxfiles'
	                alert('too many files');
	                break;
	                
	            case 'FileTooLarge':
	                // program encountered a file whose size is greater than 'maxfilesize'
	                // FileTooLarge also has access to the file which was too large
	                // use file.name to reference the filename of the culprit file
	                alert('files too large');
	                break;
	                
	            default:
	            	alert('something went wrong');
	                break;
	        }
	    },
	    maxfiles: 25,
	    maxfilesize: 20,    // max file size in MBs
	    dragOver: function() {
	        // user dragging files over #dropzone
	    },
	    dragLeave: function() {
	        // user dragging files out of #dropzone
	    },
	    docOver: function() {
	        // user dragging files anywhere inside the browser document window
	        $status.addClass('success');
	        $file_list.animate({ 'width' : '53.9%' });
			$upload.css({ 'display' : 'block' }).animate({ 'width' : '20%' });
			return false;
	    },
	    docLeave: function() {
	        // user dragging files out of the browser document window
	    },
	    drop: function() {
	        // user drops file
	    },
	    uploadStarted: function(i, file, len){
	        // a file began uploading
	        // i = index => 0, 1, 2, 3, 4 etc
	        // file is the actual file of the index
	        // len = total files user dropped
	    },
	    uploadFinished: function(i, file, response, time) {
			// response is the data you got back from server in JSON format.
	        // console.log(i);
			// console.log(file);
			//console.log(response);
			// console.log(time);
			$folder_list.find('li.selected').click();
	    },
	    progressUpdated: function(i, file, progress) {
	        // this function is used for large files and updates intermittently
	        // progress is the integer value of file being uploaded percentage to completion
	    },
	    speedUpdated: function(i, file, speed) {
	        // speed in kb/s
	    },
	    rename: function(name) {
	        // name in string format
	        // must return alternate name as string
	    },
	    beforeEach: function(file) {
	        // file is a file object
	        // return false to cancel upload
	    },
	    afterAll: function() {
	        // runs after all files have been uploaded or otherwise dealt with
	    }
	});
	*/
	
	//this is all Bryan Galli from below here
	
	$(document).ready(function() {
		$('#folder-new').click(function () {
			alert('clicked');
			var $input = $('<input type="text" value="Untitled Folder" />');
			$('<li class="editing"></li>').append($input);
		});
		
		$('.editing').live('submit', function (event) {
			event.preventDefault();
			alert('submitted');
		});
	});
});