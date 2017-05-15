var Drivr = (function($) {
    'use strict';
    var drivrdoc,
        pluginurl = '',
        $handle,
        picker = 'file',
        doctype,
        serviceid,
        mimetype,
        pickerApiLoaded = false,
        filetype,
        oauthToken,
        userCallback,
        localfile = false,
        driveLink = 'https://drive.google.com/uc?export=download&id=',
        $mediainsert = $('#wpdrv-drop-insert'),
        $linkselect = $('#drivr-link-select'),
        $popuphandle = $('#wpdrop-popup'),
        $linkhandle = $('#wpdrv-embed-link'),
        $embedtype = $('#drivr-insert-type'),
        driveclientId = drivr.drivr_clientid,
        driveapiKey = drivr.drivr_apikey,
        service_order = drivr.drivr_service_order.split(","),
        service_list = drivr.drivr_service_list,

        init = function() {
            reset();
            bind_functions();
            drive_support();
            $(window).resize(popup_position);
        },
        bind_functions = function() {

            $("#drivr-form-tabs .wpdrv-acc-title input").on("change", popup_tabs);

            $('#wpdrivr-popup').on('click', '.drivr-cancel-button', remove_popup);

            $('#wpdrivr-popup').on('click', '#wpdrv-drop-insert', insert_item);

            $('#wpdrivr-popup').on('change', '#drivr-custom-link', custom_link);

            $(document).on('click', '#drivr-featured', featured_image);

            $(document).on('click', '#add-drivr', open_file_picker);

            $(document).on('click', '#add-drivr-no-api', no_api_handle);

        },
        open_file_picker = function() {
            picker = 'file';
            load_gapi();
        },
        featured_image = function() {
            picker = 'image';
            load_gapi();
        },
        image_upload = function(data) {
            if (data[google.picker.Response.ACTION] == google.picker.Action.PICKED) {
                drivrdoc = data[google.picker.Response.DOCUMENTS][0];
                $("#drivr-holder").html("");
                $('.drivr-loader').show();
                $('#drivr-featured').addClass('drivr-loading');
                $.ajax({
                        url: drivr.ajaxurl,
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            action: 'drivr_featured_image',
                            drivr_nonce: drivr.drivr_nonce,
                            oauthToken: oauthToken,
                            drivrdoc: drivrdoc,
                            postid: $('#post_ID').val()
                        },
                    })
                    .done(function(data) {
                        if (data.status) {
                            $(".inside", "#postimagediv").html(data.html);
                        } else {
                            featured_error(data.message);
                        }
                    })
                    .fail(function(data) {
                        featured_error("failed");
                    });
            }
        },
        featured_error = function(message) {
            $("#drivr-holder").html(message);
            $("#drivr-featured").removeClass("drivr-loading").show();
            $('.drivr-loader').hide();
        },
        custom_link = function() {
            if ($(this).val() == 'custom') {
                $handle.find('.drive-custom-url').show();
            } else {
                $handle.find('.drive-custom-url').hide();
            }
        },
        popup_tabs = function() {
            var curitem = jQuery(this).data("item");
            $('#drivr-form-tabs li').removeClass('active');
            $(this).parent().addClass('active');
            var currenttab = jQuery(this).attr('href');
            $('div.wpdrv-tab-item').addClass('hidden');
            $(curitem).removeClass('hidden');
        },
        remove_popup = function(e) {
            e.preventDefault();
            tb_remove();
            setTimeout(function() {
                $('body').removeClass('drivr-popup-on');
            }, 800);
        },
        drive_support = function() {
            var DrivrEl = document.createElement('script');
            DrivrEl.setAttribute('src', 'https://apis.google.com/js/api.js');
            DrivrEl.setAttribute('gapi_processed', 'true');
            document.head.appendChild(DrivrEl);
        },
        no_api_handle =function(){
            $('#wpdrivr-nokey').removeClass('hidden');
            $('#wpdrivr-popup').addClass('api-key-pop');
            $('#wpdrivr-popup-settings').addClass('hidden');
            tb_show("Drivr", "#TB_inline?inlineId=wpdrivr-popup-wrap&amp;width=1030&amp;modal=true", null);
            popup_position();
            return false; 
        },
        load_gapi = function() {
            reset();
            if (!oauthToken) {
                gapi.load('auth', {
                    'callback': onAuthApiLoad
                });
                gapi.load('picker', 1);
            } else {
                create_picker();
            }
        },
        onauth_api_load = function() {
            window.gapi.auth.authorize({
                'client_id': driveclientId,
                'scope': ['https://www.googleapis.com/auth/drive.readonly.metadata']
            }, handle_auth_result);
        },
        handle_auth_result = function(authResult) {
            if (authResult && !authResult.error) {
                oauthToken = authResult.access_token;
                create_picker();
            }
        },
        create_picker = function() {
            if (picker == 'image') {
                image_picker();
            } else {
                file_picker();
            }
        },
        image_picker = function() {
            var view = new google.picker.View(google.picker.ViewId.DOCS);
            view.setMimeTypes("image/png,image/jpeg,image/jpg");
            var picker = new google.picker.PickerBuilder()
                .enableFeature(google.picker.Feature.NAV_HIDDEN)
                .setOAuthToken(oauthToken)
                .addView(view)
                .addView(new google.picker.DocsUploadView())
                .setDeveloperKey(driveapiKey)
                .setCallback(image_upload)
                .build();
            picker.setVisible(true);
        },
        file_picker = function() {
            var views = {
                drive: new google.picker.DocsView().setIncludeFolders(true),
                upload: new google.picker.DocsUploadView().setIncludeFolders(true),
                video: google.picker.ViewId.DOCS_VIDEOS,
                spreadsheets: google.picker.ViewId.SPREADSHEETS,
                pdfs: google.picker.ViewId.PDFS,
                presentations: google.picker.ViewId.PRESENTATIONS,
                docs: google.picker.ViewId.DOCUMENTS,
                photos: google.picker.ViewId.DOCS_IMAGES,
                recent: google.picker.ViewId.RECENTLY_PICKED,
                youtube: new google.picker.VideoSearchView().setSite('www.youtube.com'),
            };
            var picker = new google.picker.PickerBuilder()
                .setOAuthToken(oauthToken)
                .setDeveloperKey(driveapiKey)
                .setCallback(picker_callback);
            $.each(service_order, function(index, view) {
                if (service_list[view]) {
                    picker.addView(views[view]);
                }
            });
            picker.build().setVisible(true);
        },
        popup_position = function() {
            var tbWindow = $('#TB_window');
            var width = $(window).width();
            var H = $(window).height();
            var W = (1080 < width) ? 1080 : width;
            if (tbWindow.size()) {
                tbWindow.width(W - 50).height(H - 45);
                $('#TB_ajaxContent').css({ 'width': '100%', 'height': '100%', 'padding': '0' });
                tbWindow.css({ 'margin-left': '-' + parseInt(((W - 50) / 2), 10) + 'px' });
                if (typeof document.body.style.maxWidth != 'undefined')
                    tbWindow.css({ 'top': '20px', 'margin-top': '0' });
                $('#TB_title').css({ 'background-color': '#fff', 'color': '#cfcfcf' });
            };
        },
        picker_callback = function(data) {
            if (data[google.picker.Response.ACTION] == google.picker.Action.PICKED) {
                drivrdoc = data[google.picker.Response.DOCUMENTS][0];
                tb_show("Drivr", "#TB_inline?inlineId=wpdrivr-popup-wrap&amp;width=1030&amp;modal=true", null);
                popup_position();
                file_handle();
            }
        },
        get_file_data = function(id){
            var request = new XMLHttpRequest();
            request.open('GET', 'https://www.googleapis.com/drive/v2/files/' + id);
            request.setRequestHeader('Authorization', 'Bearer ' + oauthToken);
            request.addEventListener('load', function() {
                var item = JSON.parse(request.responseText);
            });
            request.send();
        },
        file_handle = function() {
            var handle = "document";
            serviceid = drivrdoc.serviceId, doctype = drivrdoc.type,mimetype=drivrdoc.mimeType;
            $('#drivr-filename').html(drivrdoc.name);
            if (drivrdoc.sizeBytes) $('#drivr-filesize').html(human_file_size(drivrdoc.sizeBytes));
            filetype = 'document';
            if ($.inArray(doctype, ['document', 'photo', 'video', 'audio']) != -1) {
                filetype = doctype;
                if (filetype == 'photo') { handle = "photo" };
            }
            $handle = $('#wpdrv-embed-' + handle);
            $('#drivr-insert-type-embed').val(filetype);
            $('#drivr-icon').removeClass().addClass(filetype);
            if(!drivrdoc.hasOwnProperty('isShared')){
                showerror();
            }
            file_prepare();
        },
        file_prepare = function() {
            if (filetype == 'photo') {
                $handle.removeClass('hidden');
                var imgdata = new Image();
                $mediainsert.val($mediainsert.data('loading'));
                $mediainsert.attr('disabled', true);
                imgdata.src = driveLink + drivrdoc.id;
                imgdata.onload = function() {
                    $handle.find('.drivr_width').val(imgdata.width);
                    $handle.find('.drivr_height').val(imgdata.height);
                    $mediainsert.val($mediainsert.data('txt'));
                    $mediainsert.removeAttr('disabled');
                }
            }
            if (filetype == 'video' && serviceid == 'web') {
                $linkselect.val('preview').attr('disabled', true);
            }
            if(mimetype.indexOf('vnd.google-apps')!= -1){
                $linkselect.val('preview').attr('disabled', true);
            }
        },
        insert_item = function(e) {
            e.preventDefault();
            var embeditem = $("#wpdrivr-popup input[name='drivr-insert-type']:checked").val();
            switch (embeditem) {
                case 'document':
                    embed_handle();
                    break;
                case 'photo':
                    image_handle();
                    break;
                case 'video':
                    video_handle();
                    break;
                default:
                    $handle = $linkhandle;
                    link_handle();
            }
            var drivrembed = $handle.find('.drivrinsert').val();
            wp.media.editor.insert(drivrembed);
        },
        embed_handle = function() {
            var documenthtml = "",
                filemime = drivrdoc.mimeType.split("/");
            if (drivrdoc) {
                var embedtype = ' type="' + filemime[0] + '"';
                documenthtml = '[drivr id="' + drivrdoc.id + '"' + embedtype + ']';
                $handle.find('.drivrinsert').val(documenthtml);
            }
        },
        video_handle = function() {
            var videohtml = "";
            if (drivrdoc && serviceid == 'web') {
                videohtml = '[embed]' + drivrdoc.url + '[/embed]';
                $handle.find('.drivrinsert').val(videohtml);
            } else {
                embed_handle()
            }
        },
        link_handle = function() {
            var linkhtml = "",
                mc = false,
                linktxt = drivrdoc.url,
                linkinurl = drivrdoc.url;
            if (linkinurl) {
                var Linkattr = "";
                $.each($handle.find('[data-setting]'), function(node) {
                    if ($(this).attr('type') == 'checkbox') { mc = $(this).is(':checked'); } else { mc = $(this).val(); }
                    if (mc) {
                        Linkattr += ' ' + $(this).data('setting') + '="' + $(this).val() + '"';
                    }
                });
                if ($handle.find('.link-url').val() == 'download') {
                    if (localfile) {
                        Linkattr = " download='" + drivrdoc.name + "'";
                    } else {
                        linkinurl = driveLink + drivrdoc.id;
                    }
                }
                if ($handle.find('.awsm-link').val()) {
                    linktxt = $handle.find('.awsm-link').val();
                } else {
                    linktxt = linkinurl;
                }
                linkhtml = '<a href="' + linkinurl + '"' + Linkattr + '>' + linktxt + '</a>';
                $handle.find('.drivrinsert').val(linkhtml);
            }
        },
        image_handle = function() {
            var imagehtml = "",
                setting = '',
                linkurl = "",
                linkTo = "",
                imgsrc = driveLink + drivrdoc.id;
            if (imgsrc) {
                var captioninclude = ["width", "align"],
                    Imageattr = "",
                    captionattr = "";
                $.each($handle.find('[data-setting]'), function(node) {
                    if ($(this).val()) {
                        setting = $(this).data('setting')
                        if ($(this).data('setting') == 'align') setting = 'class';
                        Imageattr += setting + '="' + $(this).val() + '" ';
                        if ($.inArray($(this).data('setting'), captioninclude) !== -1) {
                            captionattr += $(this).data('setting') + '="' + $(this).val() + '" ';
                        }
                    }
                });
                linkTo = $('#drivr-custom-link').val();
                if (linkTo == 'custom') {
                    var linkurl = $('#drivr-curl').val();
                } else if (linkTo == 'file') {
                    var linkurl = imgsrc;
                }
                if ($handle.find('#drivr-custom-link').val() == 'custom') {
                    linkurl = $('#drivr-curl').val();
                }
                imagehtml = '<img src="' + imgsrc + '" ' + Imageattr + '/>';
                if (linkurl) {
                    imagehtml = '<a href="' + linkurl + '">' + imagehtml + '</a>';
                }
                if ($('#drivr_caption').val()) {
                    imagehtml = '[caption id="" ' + captionattr + ']' + imagehtml;
                    imagehtml += $('#drivr_caption').val() + '[/caption]';
                }
                $handle.find('.drivrinsert').val(imagehtml);
            }
        },
        human_file_size = function(bytes) {
            var thresh = 1024;
            if (bytes < thresh) return bytes + ' B';
            var units = ['KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
            var u = -1;
            do { bytes /= thresh; ++u; } while (bytes >= thresh);
            return bytes.toFixed(1) + ' ' + units[u];
        },
        onAuthApiLoad = function() {
            window.gapi.auth.authorize({
                'client_id': driveclientId,
                'scope': ['https://www.googleapis.com/auth/drive']
            }, handle_auth_result);
        },
        reset = function() {
            hiderror();
            drivrdoc = '', localfile = false;
            $handle = "",mimetype="";
            $linkhandle.addClass('hidden');
            $('#drivr-filesize').html('');
            $('#drivr-filename').html('');
            $('#drivr-form-tabs li').removeClass('active first-item');
            $embedtype.prop('checked', true);
            $('#embeditem').addClass('active first-item');
            $('#wpdrv-embed-photo').addClass('hidden');
            $('.wpdrv-advanced-options .drivrest').each(function() {
                $(this).val('');
            });
            $('.wpdrv-advanced-options input:checkbox').removeAttr('checked');
            $linkselect.removeAttr('disabled');
            $('#drivr-file-link').attr('href', '#');
            $('#drivr-insert-type-embed').val('document').attr('checked', true);
        },
        showerror = function(message) {
            $('.wpdvr-file-private').removeClass('hidden');
            $('#drivr-file-link').attr('href', drivrdoc.url);
        },
        hiderror = function() {
            $('.wpdvr-file-private').addClass('hidden');
        };
    return {
        init: init
    };
})(jQuery);
jQuery(Drivr.init());
