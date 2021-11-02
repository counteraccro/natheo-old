/**
 *  JS global pour l'upload
 *  @author Gourdon Aymeric
 *  @version 1.0
 **/
let Upload = {};

/**
 * Upload sous la forme de drag and drop
 * @constructor
 */
Upload.DragAndDrop = function() {

    Upload.dragAndDropid = '#block-upload';

    // preventing page from redirecting
    $("html").on("dragover", function(e) {
        e.preventDefault();
        e.stopPropagation();
        $(Upload.dragAndDropid + " .text-muted").text($(Upload.dragAndDropid + " #block-upload-file").data('dragover'));
    });

    $("html").on("drop", function(e) { e.preventDefault(); e.stopPropagation(); });

    // Drag enter
    $(Upload.dragAndDropid + ' .upload-area').on('dragenter', function (e) {
        e.stopPropagation();
        e.preventDefault();
        $(Upload.dragAndDropid + " .text-muted").text($(Upload.dragAndDropid + " #block-upload-file").data('drop'));
    });

    // Drag over
    $(Upload.dragAndDropid + ' .upload-area').on('dragover', function (e) {
        e.stopPropagation();
        e.preventDefault();
        $(Upload.dragAndDropid + " .text-muted").text($(Upload.dragAndDropid + " #block-upload-file").data('drop'));
    });

    // Drop
    $(Upload.dragAndDropid + ' .upload-area').on('drop', function (e) {
        e.stopPropagation();
        e.preventDefault();

        $(Upload.dragAndDropid + " .text-muted").text($(Upload.dragAndDropid + " #block-upload-file").data('upload'));

        var file = e.originalEvent.dataTransfer.files;
        var fd = new FormData();

        fd.append('file', file[0]);

        uploadData(fd);
    });

    // Open file selector on div click
    $(Upload.dragAndDropid + " #block-upload-file").click(function(){
        $("#file").click();
    });

    // file selected
    $(Upload.dragAndDropid + " #file").change(function(){
        var fd = new FormData();

        var files = $('#file')[0].files[0];

        fd.append('file',files);

        uploadData(fd);
    });
}

// Sending AJAX request and upload file
function uploadData(formdata){

    $.ajax({
        url: $(Upload.dragAndDropid + " #block-upload-file").data('url'),
        type: 'post',
        data: formdata,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function(response){
            addThumbnail(response);
        }
    });
}

// Added thumbnail
function addThumbnail(data){
    $(Upload.dragAndDropid + " #block-upload-file div.text-muted").remove();
    var len = $(Upload.dragAndDropid + " #block-upload-file div.thumbnail").length;

    var num = Number(len);
    num = num + 1;

    var name = data.name;
    var src = data.src + '/' + name;

    // Creating an thumbnail
    $(Upload.dragAndDropid + " #block-upload-file").append('<div id="thumbnail_'+num+'" class="thumbnail img-fluid me-2"></div>');
    $("#thumbnail_"+num).append('<img src="'+src+'" width="100%" height="78%">');

}

// Bytes conversion
function convertSize(size) {
    var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
    if (size == 0) return '0 Byte';
    var i = parseInt(Math.floor(Math.log(size) / Math.log(1024)));
    return Math.round(size / Math.pow(1024, i), 2) + ' ' + sizes[i];
}