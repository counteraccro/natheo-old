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
Upload.DragAndDrop = function () {

    Upload.dragAndDropid = '#block-upload';

    // preventing page from redirecting
    $("html").on("dragover", function (e) {
        e.preventDefault();
        e.stopPropagation();
        $(Upload.dragAndDropid + " .text-muted").text($(Upload.dragAndDropid + " #block-upload-file").data('dragover'));
    });

    $("html").on("drop", function (e) {
        e.preventDefault();
        e.stopPropagation();
    });

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
    $(Upload.dragAndDropid + " #block-upload-file").click(function () {
        $("#file").click();
    });

    // file selected
    $(Upload.dragAndDropid + " #file").change(function () {
        var fd = new FormData();

        var files = $('#file')[0].files[0];

        fd.append('file', files);

        uploadData(fd);
    });
}

// Sending AJAX request and upload file
function uploadData(formdata) {

    $.ajax({
        url: $(Upload.dragAndDropid + " #block-upload-file").data('url'),
        type: 'post',
        data: formdata,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function (response) {
            addThumbnail(response);
            $(Upload.dragAndDropid + " .text-muted").text($(Upload.dragAndDropid + " #block-upload-file").data('msg'));
        }
    });
}

// Added thumbnail
function addThumbnail(data) {
    $(Upload.dragAndDropid + " #block-show-img div.text-muted").remove();

    let msg = $(Upload.dragAndDropid + " #block-show-img").data('info');
    let len = $(Upload.dragAndDropid + " #block-show-img div.min-img").length;

    let num = Number(len);
    num = num + 1;

    msg = msg.replace('$nb', num);

    $(Upload.dragAndDropid + " #block-show-img #msg-nb-img-add").html(msg);

    let src = data.path;

    // Creating an thumbnail
    $(Upload.dragAndDropid + " #block-show-img").append('<div id="thumbnail_' + num + '" class="min-img thumbnail img-thumbnail me-2" data-bs-toggle="tooltip" data-bs-placement="right" title="' + data.name + '"></div>');
    $("#thumbnail_" + num).append('<img src="' + src + '" class="img-fluid">');

}

// Bytes conversion
function convertSize(size) {
    var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
    if (size == 0) return '0 Byte';
    var i = parseInt(Math.floor(Math.log(size) / Math.log(1024)));
    return Math.round(size / Math.pow(1024, i), 2) + ' ' + sizes[i];
}