$(function () {
    attachFu($('input[type="file"]'));
    initMCE($('textarea'));

    function elFinderBrowser(field_name, url, type, win) {
        var elfinder_url = '/elfinder/elfinder.html';    // use an absolute path!
        tinyMCE.activeEditor.windowManager.open({
            file: elfinder_url,
            title: 'elFinder 2.0',
            width: 900,
            height: 450,
            resizable: 'yes',
            inline: 'yes', // This parameter only has an effect if you use the inlinepopups plugin!
            popup_css: false, // Disable TinyMCE's default popup CSS
            close_previous: 'no'
        }, {
            window: win,
            input: field_name
        });
        return false;
    }

    function initMCE(el) {
        el.each(function () {
            tinyMCE.settings = {
                mode: 'exact',
                elements: this.id,
                theme: "advanced",
                language: 'ru',
                plugins: "inlinepopups,fullscreen",
                dialog_type: "modal",
                file_browser_callback: function (field_name, url, type, win) {
                    var elfinder_url = '/admin/elfinder/elfinder';    // use an absolute path!
                    tinyMCE.activeEditor.windowManager.open({
                        file: elfinder_url,
                        title: 'elFinder 2.0',
                        width: 900,
                        height: 450,
                        resizable: 'yes',
                        inline: 'yes', // This parameter only has an effect if you use the inlinepopups plugin!
                        popup_css: false, // Disable TinyMCE's default popup CSS
                        close_previous: 'no'
                    }, {
                        window: win,
                        input: field_name
                    });
                    return false;
                },
                theme_advanced_buttons1: 'formatselect,fontselect,fontsizeselect,forecolor,backcolor,italic,underline,strikethrough,sub,sup,justifyleft,justifycenter,justifyright,justifyfull,bullist,numlist,outdent,indent,undo,redo,link,unlink,cleanup,hr,fullscreen'
            };
            tinyMCE.execCommand('mceAddControl', false, this.id);
        });
        return false;
    }

    function attachFu(el) {
        el.each(function () {
            $(this).fileupload({
                disableImageResize: /Android(?!.*Chrome)|Opera/.test(window.navigator && navigator.userAgent),
                previewMaxWidth: 190,
                previewMaxHeight: 135,
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
                    e.preventDefault();
                    data.submit().complete(function () {
                        if (!$('input[type="file"]').fileupload('active')) {
                            $('form').submit();
                        }
                    });
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

    $('button[type="submit"]').click(function (event) {
        $('div.loading').show();
    });

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
        newBlock.find('span.mceEditor').remove();
        newBlock.find('textarea, input').attr({id: nextId, name: nextId});
        newBlock.find('textarea').val('').show();
        newBlock.find('.image-thumbnail').html('Перенесите файл сюда');
        newBlock.find('.remove-img').hide();
        newBlock.find('.fileinput-button').css('display', 'inline-block');
        newBlock.appendTo(blocks);
        attachFu(newBlock.find('input[type="file"]'));
        initMCE(newBlock.find('textarea'));
        $('.remove-block').show();
    });

    $('div#blocks').on('click', '.remove-block', function () {
        var taid = $(this).parent().find('textarea').attr('id');
        tinyMCE.execCommand('mceFocus', false, taid);
        tinyMCE.EditorManager.execCommand('mceRemoveControl', false, taid);
        $(this).parent().remove();
        if ($('div#blocks > div.inline-blocks').length < 2) {
            $('.remove-block').hide();
        }
    });

    $(document).bind('drop dragover', function (e) {
        e.preventDefault();
    });
});