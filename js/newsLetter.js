$(function () {
    attachFu($('input[type="file"]'));
    function attachFu(el) {
        el.each(function () {
            $(this).fileupload({
                disableImageResize: /Android(?!.*Chrome)|Opera/.test(window.navigator && navigator.userAgent),
                previewMaxWidth: 190,
                previewMaxHeight: 135,
//        previewCrop: true,
                dropZone: $(this).closest('div').find('.image-thumbnail'),
                acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
                url: '/admin/newsletter/upload',
                dataType: 'json',
                autoUpload: false,
                formData: $('form').serializeArray()
            }).on('fileuploadadd', function (e, data) {
                var p = $(this).closest('div');
                data.context = p.find('.image-thumbnail');
                p.find('input[type="hidden"]').val('');
                $('button[type="submit"]').on('click', function (e) {
//                    e.preventDefault();
                    data.submit();
//                    window.location = '/admin/newsletter'
                });
            }).on('fileuploadprocessalways', function (e, data) {
                var index = data.index,
                        file = data.files[index],
                        node = $(data.context[0]);
                if (file.preview) {
                    node.html(file.preview);
                    var p = node.parent();
                    p.find('.fileinput-button').hide();
                    p.find('.remove-img').css('display', 'inline-block');
                }
                if (file.error) {
                    node
                            .append('<br>')
                            .append($('<span class="text-danger"/>').text(file.error));
                }
                if (index + 1 === data.files.length) {
                    data.context.find('button')
                            .text('Upload')
                            .prop('disabled', !!data.files.error);
                }
            });
        });
    }

    $('form').on('click', '.remove-img', function () {
        var p = $(this).parent(), i = p.find('.fileinput-button');
        i.wrap('<form>').closest('form').get(0).reset();
        i.unwrap();
        p.find('.image-thumbnail').html('Перенесите файл сюда');
        p.find('.fileinput-button').show();
        p.find('.remove-img').hide();
        p.find('input[type="hidden"]').val('d');
    });

//    $('button[type="submit"]').click(function (event) {
//        event.preventDefault();
//    });

    var blocks = $('#blocks');
    var nextId = function (i, v) {
        return v.replace(/\d+/, function (x) {
            return parseInt(x) + 1;
        });
    }
    $('#add-block').click(function (e) {
        e.preventDefault();
        var newBlock = blocks.children('div').last().clone(false);
        newBlock.wrap('<form>').closest('form').get(0).reset();
        newBlock.unwrap();
        newBlock.find('textarea, input').attr({id: nextId, name: nextId});
        newBlock.find('textarea').val('');
        newBlock.find('.image-thumbnail').html('Перенесите файл сюда');
        newBlock.find('.remove-img').hide();
        newBlock.find('.fileinput-button').css('display', 'inline-block');
        newBlock.appendTo(blocks);
        attachFu(newBlock.find('input[type="file"]'));
        $('.remove-block').show();
    });

    $('div#blocks').on('click', '.remove-block', function () {
        $(this).parent().remove();
        if ($('div#blocks > div.inline-blocks').length < 2) {
            $('.remove-block').hide();
        }
    });

    $(document).bind('drop dragover', function (e) {
        e.preventDefault();
    });
});