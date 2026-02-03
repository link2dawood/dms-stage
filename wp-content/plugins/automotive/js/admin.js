/* global myAjax, google, jQuery, wp, tinyMCE, YoastSEO */

/**
 * @param {string} myAjax.sold_listings
 * @param {string} myAjax.listing_dir
 */
(function ($) {
  'use strict';

  var gmapAutocomplete;

  jQuery(document).ready(function ($) {
    var entityMap = {
      '&': '&amp;',
      '<': '&lt;',
      '>': '&gt;',
      '"': '&quot;',
      "'": '&#39;',
      '/': '&#x2F;'
    };

    function escapeHtml(string) {
      return String(string).replace(/[&<>"'/]/g, function (s) {
        return entityMap[s];
      });
    }

    // we create a copy of the WP inline edit post function
    if (typeof inlineEditPost !== 'undefined') {
      var $wp_inline_edit = inlineEditPost.edit;
      var $wp_inline_save = inlineEditPost.save;

      // and then we overwrite the function with our own code
      inlineEditPost.edit = function (id) {

        // "call" the original WP edit function
        // we don't want to leave WordPress hanging
        $wp_inline_edit.apply(this, arguments);

        // now we take care of our business

        // get the post ID
        var $post_id = 0;
        if (typeof (id) == 'object') {
          $post_id = parseInt(this.getId(id));
        }

        if ($post_id > 0) {
          // define the edit row
          var $edit_row = $('#edit-' + $post_id);

          if (currentListingData[$post_id].price) {
            $edit_row.find(".current-price").val(currentListingData[$post_id].price);
          }

          if (currentListingData[$post_id].original_price) {
            $edit_row.find(".original-price").val(currentListingData[$post_id].original_price);
          }
        }
      };

      inlineEditPost.save = function (id) {
        $wp_inline_save.apply(this, arguments);


        var $post_id = 0;
        if (typeof (id) == 'object') {
          $post_id = parseInt(this.getId(id));
        }

        var $edit_row = $('#edit-' + $post_id);

        currentListingData[$post_id].price = $edit_row.find(".current-price").val();
        currentListingData[$post_id].original_price = $edit_row.find(".original-price").val();
      };
    }


    if (myAjax.sold_listings !== false) {
      var $postsFilter = $('#posts-filter');

      $postsFilter.find('.search-box').after('<input type="hidden" name="sold_listings" class="sold_listings_page" value="' + myAjax.sold_listings + '">');
    }

    var $automotiveMetaTabs = $('.automotive_meta_tabs');

    if ($automotiveMetaTabs.length) {
      $automotiveMetaTabs.tabs({
        create: function () {
          $(this).removeClass('loading_status_tabs');
          $(this).find('.loading_tabs_overlay').remove();

          $(this).find('.hidden_content').show();
        }
      }).addClass('ui-tabs-vertical ui-helper-clearfix');

      $automotiveMetaTabs.find('li').removeClass('ui-corner-top').addClass('ui-corner-left');
    }

    var $galleryImages = $('#gallery_images');

    // Functions
    function resetRows() {
      $galleryImages.find('.single-gallery-image').each(function (index) {
        var value = (index + 1);

        $(this).data('id', value);
        $(this).attr('data-id', value);
        $(this).find('.top_header').text($galleryImages.data('image') + ' #' + value);
      });
    }

    var $revPreviewOptions = $('#rev_preview_options');
    if ($revPreviewOptions.length) {
      $revPreviewOptions.tabs();
    }

    if ($('#address_autocomplete').length) {
      try {
        var autocompleteInput = document.getElementById('address_autocomplete');

        gmapAutocomplete = new google.maps.places.Autocomplete(
          (autocompleteInput),
          {types: ['geocode']}
        );

        gmapAutocomplete.addListener('place_changed', autocompleteGetDetails);

        autocompleteInput.addEventListener('keydown', function (event) {
          // google.maps.event.addDomListener(autocompleteInput, 'keydown', function (event) {
          if (event.keyCode === 13) {
            event.preventDefault();
          }
        });
      } catch (error) {
        console.log("Google Maps Autocomplete Error", error);
      }
    }

    function autocompleteGetDetails() {
      var place = gmapAutocomplete.getPlace();

      var latitude = place.geometry.location.lat();
      var longitude = place.geometry.location.lng();

      $(".location_value[data-location='latitude']").val(latitude);
      $(".location_value[data-location='longitude']").val(longitude);

      firstRun();
    }

    var map = '';

    function firstRun() {
      var latitude = $(".location_value[data-location='latitude']").val();
      var longitude = $(".location_value[data-location='longitude']").val();
      var zoom = parseInt($('.zoom_level').val());

      try {
        var myLatlng = new google.maps.LatLng(latitude, longitude);

        var myOptions = {
          zoom: zoom,
          center: myLatlng,
          popup: true,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        };

        map = new google.maps.Map(document.getElementById('google-map'), myOptions);

        var marker = new google.maps.Marker({
          position: myLatlng,
          map: map,
          title: 'Our Location'
        });

        google.maps.event.addListener(marker, 'click', function () {
          map.setZoom(myLatlng);
        });

        google.maps.event.addListener(map, 'rightclick', function (event) {
          var lat = event.latLng.lat();
          var lng = event.latLng.lng();

          $(".location_value[data-location='latitude']").val(lat);
          $(".location_value[data-location='longitude']").val(lng);

          marker.setMap(null);

          var newLatlng = new google.maps.LatLng(lat, lng);

          marker = new google.maps.Marker({
            position: newLatlng,
            map: map,
            title: 'Our Location'
          });

          google.maps.event.addListener(marker, 'click', function () {
            map.setZoom(zoom);
          });
        });

        google.maps.event.addListener(map, 'zoom_changed', function () {
          var zoomLevel = map.getZoom();

          $('.zoom_level').val(zoomLevel);
          $('.zoom_level_text').html(zoomLevel);
          $('#slider-vertical').slider('value', zoomLevel);
        });
      } catch (error) {
        console.log('Google Maps Error', error)
      }
    }

    var $sliderVertical = $('#slider-vertical');
    if ($sliderVertical.length) {
      var defaultValue = $sliderVertical.data('value');

      $sliderVertical.slider({
        orientation: 'vertical',
        range: 'min',
        min: 0,
        max: 19,
        value: defaultValue,
        slide: function (event, ui) {
          $('.zoom_level').val(ui.value);
          $('.zoom_level_text').html(ui.value);

          map.setZoom(parseInt(ui.value));
        }
      });

      $('.zoom_level').val($sliderVertical.slider('value'));
      $('.zoom_level_text').html($sliderVertical.slider('value'));
    }

    $('.chosen-dropdown').chosen({width: '300px'});
    $('.auto_info_tooltip').tooltip({container: 'body', html: true});
    $('.info').tooltip();

    $('select.multiselect').multiselect({
      selectedList: 10,
      open: function (ev, uii) {
        var $multiselect = $(this);

        $('.ui-multiselect-checkboxes').sortable({
          stop: function (event, ui) {
            // $("select.multiselect").multiselect('refresh');
            var index = $(this).data('ui-sortable').currentItem.index();
            var value = ui.item.find('input').val();

            var isSelected = $(ev.target).find("option[value='" + value + "']").prop('selected');

            $(ev.target).find("option[value='" + value + "']").remove();
            $(ev.target).find('option:eq(' + (index) + ')').before("<option value='" + value + "'" + (isSelected ? " selected='selected'" : '') + '>' + value + '</option>');

            $multiselect.closest('div').find('.ui-multiselect span:eq(1)').text($multiselect.val().join(', '));
          }
        });
      }
    });

    var $multiSelect = $('select.multi');
    if ($multiSelect.length) {
      $multiSelect.chosen();
    }

    $galleryImages.on('click', '.make_default_image', function (e) {
      e.preventDefault();

      var theRow = $(this).closest('.single-gallery-image');
      var html = theRow.clone();

      theRow.remove();

      $(html).prependTo('#gallery_images');

      if (!$galleryImages.hasClass('hotlink')) {
        resetRows();
      }
    });

    $(document).on('click', '.delete_image', function (e) {
      e.preventDefault();

      var $galleryImages = $('#gallery_images');

      var $this = $(this);
      var handle = $this.closest('.single-gallery-image');

      console.log(handle);

      handle.fadeOut(300, function () {
        if ($galleryImages.find('.single-gallery-image').length === 1) {
          $galleryImages.append("<div class='no_images'>" + $galleryImages.data('no-images') + '</div>')
        }

        handle.remove();

        if (!$('#gallery_images').hasClass('hotlink')) {
          resetRows();
        }
      });
    });

    // Prepare the variable that holds our custom media manager.
    var mediaFrame = [];
    var formLabel = 0;

    $(document).on('click.mojoOpenMediaManager', '.add_image_gallery', function (e) {
      e.preventDefault();

      formLabel = $(this).closest('.single-gallery-image').data('id');

      console.log(formLabel);

      $galleryImages.data('current_id', formLabel);

      if (mediaFrame[0]) {
        mediaFrame[0].open();
        return;
      }

      mediaFrame[0] = wp.media.frames.media_frame = wp.media({
        className: 'media-frame add-image-gallery',
        frame: 'select',
        multiple: false,
        library: {
          type: 'image'
        }
      });

      mediaFrame[0].on('select', function () {
        var mediaAttachment = mediaFrame[0].state().get('selection').toJSON();
        var id = $galleryImages.data('current_id');
        var html = singleGalleryImageTemplate(id, mediaAttachment[0], false);

        $galleryImages.find(".single-gallery-image[data-id='" + id + "']").html(html);

        resetRows();
      });

      mediaFrame[0].open();
    });

    $(document).on('click', '.gallery-image-view li', function (e) {
      e.preventDefault();

      $('.gallery-image-view li.active').removeClass('active');
      $(this).addClass('active');

      if ($(this).data('layout') === 'boxed_fullwidth') {
        $galleryImages.addClass('boxed');

        $.cookie('gallery_layout', 'boxed');
      } else {
        $galleryImages.removeClass('boxed');

        $.cookie('gallery_layout', 'full');
      }

      // fix for sortable style being added after drag
      $galleryImages.find('.single-gallery-image').each(function () {
        $(this).removeAttr('style');
      });
    });

    // portfolio upload

    // Bind to our click event in order to open up the new media experience.
    $(document.body).on('click.mojoOpenMediaManager', '.media-upload', function (e) {
      e.preventDefault();

      if ($(this).data('imageholder').length) {
        formLabel = $('#' + $(this).data('imageholder'));
      } else {
        formLabel = jQuery(this).parent();
      }

      if (mediaFrame[1]) {
        mediaFrame[1].open();
        return;
      }
      mediaFrame[1] = wp.media.frames.media_frame = wp.media({

        className: 'media-frame add-image-gallery',
        frame: 'select',
        multiple: false,
        library: {
          type: 'image'
        }
      });
      mediaFrame[1].on('select', function () {
        var mediaAttachment = mediaFrame[1].state().get('selection').first().toJSON();

        var html = "<img src='" + mediaAttachment.url + "' class='gallery_thumbnail'><br>";
        html += "<input type='hidden' name='portfolio_image' value='" + mediaAttachment.url + "'>";

        formLabel.html(html);
      });

      mediaFrame[1].open();
    });

    //
    $(document.body).on('click.mojoOpenMediaManager', '.pick_pdf_brochure', function (e) {
      e.preventDefault();

      if (mediaFrame[2]) {
        mediaFrame[2].open();
        return;
      }

      mediaFrame[2] = wp.media.frames.media_frame = wp.media({
        className: 'media-frame add-pdf-gallery',
        frame: 'select',
        multiple: false,
        library: {}
      });
      mediaFrame[2].on('select', function () {
        var mediaAttachment = mediaFrame[2].state().get('selection').first().toJSON();

        $('.pdf_brochure_input').val(mediaAttachment.id);
        $('.pdf_brochure_label').text(mediaAttachment.url);
      });

      mediaFrame[2].open();
    });

    $(document).on('click', '.help_area .triangle', function (e) {
      e.preventDefault();

      $(this).parent().toggleClass('open').find('.message_area').slideToggle();
    });

    $(document).on('click', '.remove_pdf_brochure', function (e) {
      e.preventDefault();

      $('.pdf_brochure_label').html('');
      $('.pdf_brochure_input').val('');
    });

    // add detail
    $('.add_detail').on('click', function () {
      $('.new_details').append("<input type='text' name='project_details[]' class='widefat' style='display: none'>");
      $("input[name='project_details[]']").last().slideDown();
    });

    // remove detail
    $('.remove_detail').on('click', function () {
      $("input[name='project_details[]']").last().slideUp(400, function () {
        $(this).remove();
      });
    });

    function galleryImagesSortable() {
      $('#gallery_images').sortable({
        helper: 'clone',
        cancel: '.button, .buttons input, input',
        placeholder: 'ui-state-highlight',
        stop: function () {
          if (!$('#gallery_images').hasClass('hotlink')) {
            resetRows();
          }
        }
      });
    }

    if ($galleryImages.length) {
      galleryImagesSortable();
    }

    /**
     * @param {string} val.sizes
     * @param {string} val.sizes.thumbnail
     * @param {string} val.sizes.auto_thumb
     * @param {string} val.sizes.full
     */
    function singleGalleryImageTemplate(id, val, container) {
      var html = '';

      var $galleryImages = $('#gallery_images');

      var imageText = $galleryImages.data('image');
      var changeImageText = $galleryImages.data('change-image');
      var setDefaultText = $galleryImages.data('set-default');
      var deleteImageText = $galleryImages.data('delete-image');

      var size = val.sizes.thumbnail;

      if (typeof val.sizes.auto_thumb !== 'undefined') {
        size = val.sizes.auto_thumb;
      }

      if (typeof val.sizes.thumbnail === 'undefined') {
        size = val.sizes.full;
      }

      html += (container ? "<div class='single-gallery-image' data-id='" + id + "'>" : '');
      html += "<div class='top_header'>" + imageText + ' #' + id + '</div>';
      html += "<div class='image_preview' data-alt='" + val.alt + "' data-full-image='" + val.sizes.full.url + "'><img style='max-width: 167px;' src='" + size.url + "'></div>";
      html += "<div class='buttons'><span class='button add_image_gallery' data-id='" + id + "'><span>" + changeImageText + '</span> <i class="fa fa-pencil"></i></span> ';
      html += "<span class='button make_default_image'><span>" + setDefaultText + '</span> <i class="fa fa-hand-pointer-o"></i></span> ';
      html += "<span class='button delete_image'><span>" + deleteImageText + '</span> <i class="fa fa-trash"></i></span> ';
      html += ($('body').hasClass('post-type-listings_portfolio') ? '<input type="text" name="portfolio_links[' + id + ']" value="" placeholder="Image link">' : '');
      html += "</div><input type='hidden' name='gallery_images[]' value='" + val.id + "'>";
      html += (container ? '</div>' : '');

      return html;
    }

    $(document.body).on('click.mojoOpenMediaManager', '.add_image', function (e) {
      e.preventDefault();

      var $galleryImages = $('#gallery_images');

      $galleryImages.find('.no_images').remove();

      var id = ($galleryImages.find('.single-gallery-image').length + 1);

      if (mediaFrame[3]) {
        mediaFrame[3].open();
        return;
      }

      mediaFrame[3] = wp.media.frames.media_frame = wp.media({
        className: 'media-frame add-image-gallery',
        frame: 'select',
        multiple: true,
        library: {
          type: 'image'
        }
      });

      mediaFrame[3].on('select', function () {
        var mediaAttachment = mediaFrame[3].state().get('selection').toJSON();

        $.each(mediaAttachment, function (i, val) {
          var html = singleGalleryImageTemplate(id, val, true);

          $galleryImages.append(html);

          id++;

          resetRows();
        });
      });

      mediaFrame[3].open();
    });

    function singleGalleryUrlImageTemplate() {
      var id = ($galleryImages.find('.single-gallery-image').length + 1);
      var deleteImageText = $galleryImages.data('delete-image');
      var urlText = $galleryImages.data('url');

      var html = '';

      html += "<div class='single-gallery-image' data-id='" + id + "'><div class='top_header'><input type='url' name='gallery_images[]' placeholder='" + urlText + "'></div>";
      html += "<div class='image_preview'><img src=''></div>";
      html += "<div class='buttons'><span class='button delete_image'><span>" + deleteImageText + "</span> <i class='fa fa-trash'></i></span></div> ";
      html += ($('body').hasClass('post-type-listings_portfolio') ? '<input type="text" name="portfolio_links[2]" value="" placeholder="Image link">' : '');
      html += '</div>';

      return html;
    }

    $(document).on('click', '.add_image_hotlink', function (e) {
      e.preventDefault();

      var html = singleGalleryUrlImageTemplate();

      $('#gallery_images').append(html);
    });

    $('#gallery_images.hotlink img').on('error', function () {
      this.src = myAjax.listing_dir + 'images/pixel.gif';
    });

    $(document).on('keyup input paste', '#gallery_images.hotlink .top_header input', function () {
      var imageUrl = $(this).val();

      $(this).closest('.single-gallery-image').find('.image_preview img').attr('src', imageUrl);
    });

    // check for valid video
    $('#listing_video_input').bind('keyup input paste', function () {
      var url = $(this).val();
      var output = '';
      var youtubeUrl = url.match(/.*(youtu.*be.*)\/(watch\?v=|embed\/|v|shorts|)(.*?((?=[&#?])|$)).*/);
      var vimeoUrl = url.match(/https?:\/\/(?:www\.)?vimeo.com\/(?:channels\/|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|)(\d+)(?:$|\/|\?)/);
      var rumble = url.match(/https:\/\/rumble.com\/embed\/([^#\&\?]*)\//);

      if (rumble) {
        output = '<br><br><iframe src="https://rumble.com/embed/' + rumble[1] + '" height="400" width="644" allowfullscreen="" style="display: none;" class="listing-video-preview"></iframe>';
      } else if (youtubeUrl) {
        output = '<br><br><iframe src="https://www.youtube.com/embed/' + youtubeUrl[3] + '?rel=0" height="400" width="644" allowfullscreen="" style="display: none;" class="listing-video-preview"></iframe>';
      } else if (vimeoUrl) {
        output = '<br><br><iframe src="https://player.vimeo.com/video/' + vimeoUrl[3] + '" height="400" width="644" allowfullscreen="" style="display: none;" class="listing-video-preview"></iframe>';
      } else {
        output = '<br><br><video height="400" width="644" style="display: none;" class="listing-video-preview" controls="true"><source src="' + url + '"></video>';
      }

      $('#listing_video').html(output);
      $('.listing-video-preview').slideDown();
    });

    $('.auto_toggle').each(function () {
      var $htmlCheckbox = $('#' + $(this).data('checkbox'));
      var options = {};

      if ($(this).data('checkbox')) {
        options['checkbox'] = $htmlCheckbox;

        if ($htmlCheckbox.is(':checked')) {
          options['on'] = true;
        }
      }

      $(this).toggles(options);
    });

    $('.auto_color_picker').each(function () {
      $(this).wpColorPicker();
    });

    $(document).on('click', '.add_new_badge', function (e) {
      e.preventDefault();

      var $name = $('.new_badge_name');
      var $color = $('.new_badge_color');
      var $font = $('.new_badge_font');

      var name = $name.val();
      var color = $color.val();
      var font = $font.val();
      var goodToGo = true;

      // validation...
      if (!name) {
        goodToGo = false;

        $name.css('border', '1px solid #F00');
      } else {
        $name.removeAttr('style');
      }

      if (!color) {
        goodToGo = false;

        $color.closest('.wp-picker-container').css('border', '1px solid #F00');
      } else {
        $color.closest('.wp-picker-container').removeAttr('style');
      }

      if (!font) {
        goodToGo = false;

        $font.closest('.wp-picker-container').css('border', '1px solid #F00');
      } else {
        $font.closest('.wp-picker-container').removeAttr('style');
      }

      if (goodToGo) {
        jQuery.ajax({
          type: 'post',
          url: myAjax.ajaxurl,
          data: {
            action: 'add_new_listing_badge',
            name: name,
            color: color,
            font: font
          },
          success: function () {
            var $tabBadge = $("#tab-badge");
            $tabBadge.find("select[name='options[custom_badge]']").append($('<option></option>').attr('value', name).text(name).prop('selected', true));

            $name.val('');
            $color.val('');
            $font.val('');
            $tabBadge.find('.auto_color_picker').wpColorPicker('color', '#F7F7F7');
          }
        });
      }
    });

    // shortcode
    function shortcodeGeneratorBack() {
      $('.shortcode_generator').hide({effect: 'fold', duration: 600}).html('');
      $('.shortcode_list').show({effect: 'fold', duration: 600});
      $('.shortcode_back').fadeOut(400, function () {
        $(this).remove();
      });
      $('.ui-dialog-title').html('Shortcode Manager');
    }

    $(document).on('click', '.shortcode_back', function () {
      shortcodeGeneratorBack();
    });

    $(document).on('click', '.mce-widget.mce-automotive-shortcode-button', function () {
      $('#shortcode-modal').dialog({
        height: 500,
        width: 550,
        resizable: false,
        modal: false,
        title: 'Shortcode Manager',
        dialogClass: 'shortcode_dialog_window',
        open: function () {
          $('body').css({overflow: 'hidden'});
        },
        close: function () {
          $('#shortcode-modal').dialog('destroy');
          $('.column_generator, .shortcode_generator').hide();
          $('ul.shortcode_list').show();

          $('body').css({overflow: 'auto'});
        },
        beforeClose: function () {
          $('body').css({overflow: 'auto'});
        }
      });
    });

    $(document).on('click', '.column_generator .column_display_container.insert', function () {
      var number = $(this).data('number');
      var $fullColumn = $('#full_column');
      var left = $fullColumn.data('number');
      var html = '';

      if ((number + left) < 13) {
        $fullColumn.find('div.empty').each(function (i) {
          // alert("i: "+(i+1)+"\nNumber: "+number);
          if ((i + 1) === number) {
            var fullWidth = (((number - 1) * 5) + (29 * number));

            html = "<div class='full' style='width: " + fullWidth + "px;' data-spaces='" + number + "'><i class='fa fa-times'></i></div>";

            $(this).before(html);
          }

          $(this).remove();// removeClass('empty').addClass('full');

          if ((i + 1) === number) {
            return false;
          }
        });

        $fullColumn.data('number', (number + left));

        if ($fullColumn.data('number') === 12) {
          $('.generate_columns').fadeIn();
        }
      } else {
        // alert('no room');
      }
    });

    $(document).on('click', '.column_generator .column_display_container div i', function () {
      var $fullColumn = $('#full_column');
      var spaces = parseInt($(this).parent().data('spaces'));
      var number = $fullColumn.data('number');

      $fullColumn.data('number', (number - spaces));

      $(this).parent().hide().remove();

      for (var i = 0; i < spaces; i++) {
        $fullColumn.append("<div class='empty one'></div>");
      }

      if ($fullColumn.data('number') <= 11) {
        $('.generate_columns').fadeOut();
      }
    });

    $(document).on('click', '.generate_columns', function () {
      var columnCode = "<div class='row'>";

      $('#full_column div').each(function () {
        var space = $(this).data('spaces');

        columnCode = columnCode + "<div class='col-lg-" + space + ' col-md-' + space + "'>column content</div>";
      });

      columnCode = columnCode + '</div><br>&nbsp;';

      $('#shortcode-modal').dialog().dialog('destroy');
      $('.column_generator, #shortcode-modal .shortcode_list ul.child_shortcodes').hide();
      $('#shortcode-modal .shortcode_list').show();
      $('body').css({overflow: 'auto'});

      tinyMCE.execCommand('mceInsertContent', false, columnCode);

      return false;
    });

    var thisBoxed;

    $(document).on('click', '.shortcode_boxed_item .hidden_click_event', function () {
      thisBoxed = $(this).closest('.shortcode_boxed_item');
      var clone;
      var $clonedDiv = $('#cloned_div');

      if (!thisBoxed.hasClass('open')) {
        thisBoxed.addClass('open');

        clone = thisBoxed.clone();
        clone.css({position: 'absolute', top: '-1000px', left: '-1000px'}).attr('id', 'cloned_div');
        $('body').append(clone);

        $clonedDiv.css('height', 'auto').addClass('open');

        var autoHeight = $clonedDiv.outerHeight();

        thisBoxed.animate({height: autoHeight}, 400);

        $clonedDiv.remove();
      } else {
        thisBoxed.removeClass('open');

        clone = thisBoxed.clone();
        clone.css({position: 'absolute', top: '-1000px', left: '-1000px'}).attr('id', 'cloned_div');
        $('body').append(clone);

        $clonedDiv.removeAttr('style');

        thisBoxed.animate({height: '30px'}, 400);

        $clonedDiv.remove();
      }
    });

    $(document).on('click', '.icon_selector', function () {
      var id = $(this).data('id');
      var $iconSelectorDialog = $('#icon_selector_dialog');

      $iconSelectorDialog.dialog({
        resizable: true,
        height: 600,
        width: 500,
        modal: true,
        buttons: {
          'Use Icon': function () {
            var id = $iconSelectorDialog.data('container');
            var value = $iconSelectorDialog.find('i.enlarge').attr('class').replace(' enlarge', '');

            var type = (value.indexOf('fa') !== -1 ? 'fa' : 'fontello');

            $(".icon_selector[data-id='" + id + "']").html("Icon: <i class='" + value + "' data-no_custom='js'></i>");
            $("input[name*='[icon]'][data-input='" + id + "']").val(value.replace(type, '').trim());
            $("input[name*='[type]'][data-input='" + id + "']").val(type);

            $(this).dialog('close');
          },
          Cancel: function () {
            $(this).dialog('close');
          }
        },
        open: function () {
          $(this).data('container', id);
          $('body').css({overflow: 'hidden'});
        },
        close: function () {
          $('body').css({overflow: 'inherit'});
          if ($iconSelectorDialog.find('i.enlarge').length) {
            $iconSelectorDialog.find('i.enlarge').each(function () {
              $(this).removeClass('enlarge');
            });
          }
        }
      });
    });

    $(document).on('click', '.sc_icon_selector', function () {
      var id = $(this).data('code');
      var thisSelector = $(this);

      $('#sc_icon_selector_dialog').dialog({
        resizable: true,
        height: 600,
        width: 500,
        modal: true,
        buttons: {
          'Use Icon': function () {
            var value = $('#sc_icon_selector_dialog i.enlarge').attr('class').replace(' enlarge', '');

            thisSelector.after("<input type='hidden' name='icon' value='" + value + "' class='ajax_created'>");
            thisSelector.html("Icon: <i class='" + value + "' data-no_custom='js'></i>");

            $(this).dialog('close');
          },
          Cancel: function () {
            $(this).dialog('close');
          }
        },
        open: function () {
          $(this).data('container', id);
        },
        close: function () {
          if ($('#sc_icon_selector_dialog i.enlarge').length) {
            $('#sc_icon_selector_dialog i.enlarge').each(function () {
              $(this).removeClass('enlarge');
            });
          }
        }
      });
    });

    $(document).on('click', '#icon_selector_dialog i', function () {
      if ($('#icon_selector_dialog i.enlarge').length) {
        $('#icon_selector_dialog i.enlarge').each(function () {
          $(this).removeClass('enlarge');
        });
      }

      if ($(this).hasClass('enlarge')) {
        $(this).removeClass('enlarge');
      } else {
        $(this).addClass('enlarge');
      }
    });

    $(document).on('click', '#sc_icon_selector_dialog i', function () {
      if ($('#sc_icon_selector_dialog i.enlarge').length) {
        $('#sc_icon_selector_dialog i.enlarge').each(function () {
          $(this).removeClass('enlarge');
        });
      }

      if ($(this).hasClass('enlarge')) {
        $(this).removeClass('enlarge');
      } else {
        $(this).addClass('enlarge');
      }
    });

    function getIconPrefix(theClass) {
      return (theClass.substring(0, 2) === 'fa' ? 'fa' : 'icon');
    }

    $('.icon_search').keyup(function () {
      var text = $(this).val();
      var letters = text.length;

      $('#icon_selector_dialog i.no_result').each(function () {
        $(this).removeClass('no_result');
      });

      $('#icon_selector_dialog i').each(function () {
        var theClass = $(this).attr('class');
        var icon = $(this).attr('class').replace(getIconPrefix(theClass) + '-', '');

        if (icon.substring(0, letters) !== text) {
          $(this).addClass('no_result');
        }
      });
    });

    $(document).on('keyup', '.icon_search', function () {
      var text = $(this).val();
      var letters = text.length;

      $('.shortcode_generator i.no_result').each(function () {
        $(this).removeClass('no_result');
      });

      $('.shortcode_generator i').each(function () {
        var theClass = $(this).attr('class');
        var icon = $(this).attr('class').replace(getIconPrefix(theClass) + '-', '');

        if (icon.substring(0, letters) !== text) {
          $(this).addClass('no_result');
        }
      });
    });

    $(document).on('click', '.generateModal', function () {
      if (!$('#shortcode_options').hasClass('modal_form')) {
        var html = '<tr class="modal_row"><td>Modal ID: </td><td> <input type="text" name="modal" /></td></tr>';
        $('#shortcode_options tr:last').after(html);
        $('#shortcode_options').addClass('modal_form');
        $(this).addClass('active');
      } else {
        $('#shortcode_options').removeClass('modal_form');
        $(this).removeClass('active');
        $('#shortcode_options tr.modal_row').hide().remove();
      }
    });

    $(document).on('click', '.generatePopover', function () {
      if (!$('#shortcode_options').hasClass('popover_form')) {
        var html = '<tr style="display: none" class="popover_form"><td></td><td><input type="hidden" name="popover" value="true"></td></tr>';
        html += '<tr class="popover_form"><td>Placement: </td><td> <select name="placement"><option value="top">Top</option><option value="bottom">Bottom</option><option value="right">Right</option><option value="left">Left</option></select></td></tr>';
        html += '<tr class="popover_form"><td>Title: </td><td> <input type="text" name="title"></td></tr>';
        html += '<tr class="popover_form"><td>Content: </td><td> <input type="text" name="popover_content"></td></tr>';

        $('#shortcode_options tr:last').after(html);
        $('#shortcode_options').addClass('popover_form');
        $(this).addClass('active');
      } else {
        $('#shortcode_options').removeClass('popover_form');
        $(this).removeClass('active');
        $('#shortcode_options tr.popover_form').hide().remove();
      }
    });

    $(document).on('click', '.shortcode_generator i', function () {
      if (!$(this).hasClass('no_custom') && !$(this).data('no_custom')) {
        var icon = $(this).attr('class');

        jQuery.ajax({
          type: 'post',
          url: myAjax.ajaxurl,
          data: {action: 'customize_icon', icon: icon},
          success: function (response) {
            $('.shortcode_generator').html(response).fadeIn();
          }
        });
      }
    });

    // add new term from new listing page
    $('.add_new_name').on('click', function (e) {
      e.preventDefault();

      var id = $(this).data('id');

      $("a[data-id='" + id + "']").slideUp(400, function () {
        $('.' + id + '_sh').slideToggle(400, function () {
          if ($(this).is(':visible')) {
            $(this).css('display', 'inline-block');
          }
        });
        $('.' + id + '_sh').find('input').focus();
      });
    });

    $('button.submit_new_name').on('click', function (e) {
      e.preventDefault();

      var type = $(this).data('type');
      var exact = $(this).data('exact');
      var value = $('input.' + type).val();
      var nonce = $(this).data('nonce');

      if (!value) {
        $('.' + type + '_sh').slideToggle(400, function () {
          $("a[data-id='" + type + "']").slideDown();
        });
        return false;
      }

      if (type === 'options') {
        var alreadyExists = false;

        $(".features-options-container .single-feature-option label span").each(function () {
          if ($(this).text() === value) {
            alreadyExists = true;

            $(this).closest('label').find('input').prop('checked', true);
            $('input.' + type).val('');
            $('.' + type + '_sh').slideToggle();
            $("a[data-id='" + type + "']").slideDown();

            return false;
          }
        });

        if(alreadyExists) {
          return false;
        }
      }

      jQuery.ajax({
        type: 'post',
        url: myAjax.ajaxurl,
        data: {action: 'add_name', type: type, value: value, exact: exact, nonce: nonce},
        dataType: 'json',
        success: function (response) {
          $('select#' + type).append($('<option>', {
            value: value,
            text: value
          }));

          $('select#' + type + ' option:last').prop('selected', 'true');

          if (type === 'options') {
            var featuresOptions = $('.features-options-container');

            featuresOptions.append('<div class="single-feature-option"><label><input type="checkbox" value="' + escapeHtml(response.name) + '" name="multi_options[]" checked="checked">' + escapeHtml(response.name) + '</label></div>');

            $('input.' + type).val('');
          }

          $('.' + type + '_sh').slideToggle();

          $("a[data-id='" + type + "']").slideDown();
        }
      });
    });

    $("li[data-action='map']").on('click', function () {
      firstRun();
    });

    $('.location_value').keydown(function () {
      firstRun();
    });

    if ($('#google-map').data('longitude') && $('#google-map').data('latitude')) {
      firstRun();
    }

    // header preview area
    $("select[name='header_image']").change(function () {
      var image = $(this).val();

      $('.header_preview_area').slideUp(function () {
        $(this).hide();
        $(this).html("<a href='" + image + "' target='_blank'><img src='" + image + "'  style='width: 100%; margin-top: 8px;'></a>");
        $(this).slideDown();
      });
    });

    function randomString() {
      return (((10 + Math.random()) * 0x10000) | 0).toString(16).substring(1);
    }

    $(document).on('click', '.title_toggle', function () {
      $('#shortcode_options :input').not('.title_toggle').each(function () {
        var randomClass = randomString();

        $(this).addClass(randomClass);
        $(this).before("Title: <input type='text' name='title' class='title " + randomClass + "'><select style='width: 100px;' class='title " + randomClass + "'><option>h1</option><option>h2</option><option>h3</option><option>h4</option><option>h5</option><option>h6</option></select><br>");
      });
    });

    $(document).on('click', '.shortcode_generator i', function () {
      if (!$(this).hasClass('no_custom') && !$(this).data('no_custom')) {
        var icon = $(this).attr('class');

        jQuery.ajax({
          type: 'post',
          url: myAjax.ajaxurl,
          data: {action: 'customize_icon', icon: icon},
          success: function (response) {
            $('.shortcode_generator').html(response).fadeIn();
          }
        });
      }
    });

    // Testimonial Modal
    $(document).on('click', '.edit_testimonials', function () {
      var id = $(this).data('id');
      var $idSelector = $('#' + id);
      var value = $idSelector.val();

      $('#testimonial_window').dialog({
        resizable: true,
        height: 'auto',
        width: 400,
        modal: true,
        open: function () {
          jQuery.ajax({
            type: 'post',
            url: myAjax.ajaxurl,
            data: {action: 'testimonial_widget_fields', value: value},
            success: function (response) {
              $('#testimonial_window .load').html(response).fadeIn();

              $('.remove_jquery_button_class').removeClass('ui-widget ui-state-default');
            }
          });
        },
        buttons: [
          {
            text: 'Add Testimonial',
            'class': 'button-primary remove_jquery_button_class',
            click: function () {
              var number = parseInt($('#testimonial_window textarea:last').attr('name').slice(-1)) + 1;
              var html = "<tr><td>Name:</td><td> <input type='text' name='testimonial_name_" + number + "'>&nbsp; <i class='fa fa-times remove_testimonial'></i></td></tr><tr><td>Text: </td><td> <textarea name='testimonial_text_" + number + "'></textarea></td></tr>";

              $('#testimonial_window .load tr:last').after(html);
            }
          },
          {
            text: 'Finish',
            'class': 'button-primary remove_jquery_button_class',
            click: function () {
              var serialized = $('#testimonial_form').serialize();
              $('.testimonial_fields').val(serialized);

              $(this).dialog('close');

              $idSelector.trigger('input'); // register change in widget to reveal save button
            }
          },
          {
            text: 'Cancel',
            'class': 'button-primary remove_jquery_button_class',
            click: function () {
              $(this).dialog('close');
            }
          }
        ]
      });
    });

    $(document).on('click', '.remove_testimonial', function () {
      var row1 = $(this).closest('td').parent()[0].sectionRowIndex;
      var row2 = row1 + 1;

      $('#testimonial_window .load tr').eq(row2).fadeOut(function () {
        $(this).remove();
      });
      $('#testimonial_window .load tr').eq(row1).fadeOut(function () {
        $(this).remove();
      });
    });

    // List Item Modal
    $(document).on('click', '.edit_list', function () {
      var id = $(this).data('id');
      var $idSelector = $('#' + id);
      var value = $idSelector.val();

      $('#list_window').dialog({
        resizable: true,
        height: 'auto',
        width: 320,
        modal: true,
        open: function () {
          jQuery.ajax({
            type: 'post',
            url: myAjax.ajaxurl,
            data: {action: 'list_widget_fields', value: value},
            success: function (response) {
              $('#list_window .load').html(response).fadeIn();
            }
          });

          $('.remove_jquery_button_class').removeClass('ui-widget ui-state-default');
        },
        buttons: [
          {
            text: 'Add List Item',
            'class': 'button-primary remove_jquery_button_class',
            click: function () {
              var html = "<tr><td>List Item: </td><td> <input type='text' name='list_item'>&nbsp; <i class='fa fa-times remove_list_item'></i></td></tr>";

              $('#list_window .load tr:last').after(html);
            }
          },
          {
            text: 'Finish',
            'class': 'button-primary remove_jquery_button_class',
            click: function () {
              var serialized = $('#list_form').serialize();
              $('.list_fields').val(serialized);

              $(this).dialog('close');

              $idSelector.trigger('input'); // register change in widget to reveal save button
            }
          },
          {
            text: 'Cancel',
            'class': 'button-primary remove_jquery_button_class',
            click: function () {
              $(this).dialog('close');
            }
          }
        ]
      });
    });

    $(document).on('click', '.remove_list_item', function () {
      var row = $(this).closest('td').parent()[0].sectionRowIndex;

      $('#list_window .load tr').eq(row).fadeOut(function () {
        $(this).remove();
      });
    });

    // portfolio format
    $('.portfolio-post-format').on('click', function () {
      var format = $(this).val();
      var contentArea = $('#portfolio_content-meta .inside');

      jQuery.ajax({
        type: 'post',
        url: myAjax.ajaxurl,
        data: {action: 'portfolio_editor', format: format, post_id: myAjax.post_id},
        success: function (response) {
          contentArea.html(response);

          if (format === 'gallery') {
            galleryImagesSortable();
          }
        }
      });
    });

    var sortableOptions = {
      helper: function (e, tr) {
        var $originals = tr.children();
        var $helper = tr.clone();
        $helper.children().each(function (index) {
          // Set helper cell sizes to match the original sizes
          $(this).width($originals.eq(index).width());
        });
        return $helper;
      },
      handle: $('.detail_handle'),
      items: 'tr:not(:last)',
      stop: function () {
        $('.addition_details tr:not(:last)').each(function (index) {
          $(this).find('input').each(function () {
            var name = $(this).attr('name');

            $(this).attr('name', name.replace(/(\d+)/g, index));
          });
        });
      }
    };

    $(document).on('click', '.add_itional_details', function (e) {
      e.preventDefault();

      var index = ($('.addition_details tr').length - 1);
      var label = $(".addition_details tr td input[name$='[label]']").attr('placeholder');
      var value = $(".addition_details tr td input[name$='[value]']").attr('placeholder');

      var html = "<tr><td><input type='text' name='additional_details[" + index + "][label]' placeholder='" + label + "'> </td> <td>: <input type='text' name='additional_details[" + index + "][value]' placeholder='" + value + "'> <i class='fa fa-times delete_detail'></i> <i class='fa fa-arrows detail_handle'></i></td></tr>";

      $('.addition_details tr:last').before(html);
      $('.addition_details').sortable(sortableOptions);
    });

    if ($('.additional_details').length) {
      $('.addition_details').sortable(sortableOptions);
    }

    $(document).on('click', '.delete_detail', function (e) {
      e.preventDefault();

      $(this).closest('tr').fadeOut(200, function () {
        $(this).remove();
      });
    });

    /* listing categories */
    // Return a helper with preserved width of cells
    var fixHelper = function (e, ui) {
      ui.children().each(function () {
        $(this).width($(this).width());
      });
      return ui;
    };

    if ($('table.listing_categories tbody').length) {
      $('table.listing_categories tbody').sortable({
        helper: fixHelper,
        handle: '.handle',
        containment: 'parent'
      });// .disableSelection();
    }

    $('.badge_color').change(function () {
      if ($(this).val() === 'custom') {
        $('.badge_hint').fadeIn();
      } else {
        $('.badge_hint').fadeOut();
      }
    });

    $('.import_listing_categories').on('click', function (e) {
      var thisElement = $(this);
      e.preventDefault();

      jQuery.ajax({
        url: myAjax.ajaxurl,
        type: 'POST',
        data: {action: 'import_listing_categories'},
        success: function (response) {
          alert(response); // eslint-disable-line no-undef

          thisElement.closest('tr').hide();
        }
      });
    });

    $(document).on('click', '.toggle_seo_options', function (e) {
      e.preventDefault();

      $("form[name='seo_listing']").slideToggle();

      if ($('table.listing_categories').hasClass('seo_active')) {
        $('table.listing_categories').removeClass('seo_active');
      } else {
        $('table.listing_categories').addClass('seo_active');
      }
    });

    $(document).on('click', 'table.seo_active i', function (e) {
      e.preventDefault();

      $('.seo_string_holder').val($('.seo_string_holder').val() + '%' + $(this).data('name') + '% ');
    });

    $('.remove_option_categories').on('click', function () {
      var thisElement = $(this);

      jQuery.ajax({
        url: myAjax.ajaxurl,
        type: 'POST',
        data: {action: 'hide_import_listing_categories'},
        success: function () {
          thisElement.closest('tr').hide();
        }
      });
    });

    $(document).on('click', '.save_import_categories', function (e) {
      e.preventDefault();

      var $csvImport = $('#csv_import');

      jQuery.ajax({
        url: myAjax.ajaxurl,
        type: 'POST',
        data: {
          action: 'save_import_categories',
          form: $csvImport.serialize(),
          nonce: $csvImport.data('nonce')
        },
        success: function (response) {
          alert(response); // eslint-disable-line no-undef
        }
      });
    });

    $(document).on('click', '.toggle_listing_features', function (e) {
      e.preventDefault();

      var $toggleListingFeatures = $('.toggle_listing_features');

      jQuery.ajax({
        url: myAjax.ajaxurl,
        type: 'POST',
        data: {action: 'toggle_listing_features'},
        success: function (response) {
          if (response === 'disabled') {
            $toggleListingFeatures.text('Enable Listing Features');
          } else if (response === 'enabled') {
            $toggleListingFeatures.text('Disabled Listing Features');
          }
        }
      });
    });

    /* File Import JS */
    var $csvItemCategories = $('#csv_items, .listing_category');

    if ($csvItemCategories.length) {
      $csvItemCategories.sortable({
        connectWith: '.connectedSortable',
        placeholder: 'ui-state-highlight',
        forcePlaceholderSize: true,
        revert: true,
        start: function (e, ui) {
          ui.placeholder.height(ui.item.height());
        },
        receive: function (event, ui) {
          var $this = $(this);
          if ($this.data('limit') === 1 && $this.children('li').length > 1 && $this.attr('id') !== 'csv_items') {
            alert($('#csv_import').data('one-per')); // eslint-disable-line no-undef

            $(ui.sender).sortable('cancel');
          }

          // set val
          var name = $this.data('name');
          ui.item.find('input[type="hidden"]').val(name);
        }
      });// .disableSelection();
    }

    $('.submit_csv').on('click', function () {
      $('#csv_import').submit();
    });

    /* Tax */
    $(document).on('change', '.add_tax_value', function () {
      var input = $(this).data('input');
      var $input = $('.' + input);
      var taxrate = $(this).data('taxrate');
      var inputValue = $input.val();

      var decimals = $(this).data('decimals');

      if (taxrate) {
        var newVal;

        if ($(this).is(':checked')) {
          newVal = (inputValue * taxrate);
        } else {
          newVal = (inputValue / taxrate);
        }

        $input.val(newVal.toFixed(decimals));
      }
    });

    var $currentPrice = $('input.current_price');
    var decimalChar = $currentPrice.data('decimal-char');
    var thousandChar = $currentPrice.data('thousand-char');

    $('input.current_price, input.original_price').numeric({
      allowThouSep: false,
      decimal_value: decimalChar,
      thousand_value: thousandChar
    });

    $(document).on('click', '.auto_message[data-id] .hide_message, .automotive-custom-notice[data-id] .hide_message', function () {
      var $this = $(this);
      var oldStyle = ($this.closest('.automotive-custom-notice').length);
      var $message = (oldStyle ? $this.closest('.automotive-custom-notice') : $this.closest('.auto_message'));
      var id = $message.data('id');

      if (oldStyle) {
        $message.addClass('is-loading');
      } else {
        $this.find('i').attr('class', 'fa fa-refresh fa-spin');
      }

      jQuery.ajax({
        url: myAjax.ajaxurl,
        type: 'POST',
        data: {action: 'hide_automotive_message', id: id},
        success: function () {
          // remove notification numbers
          if ($("body.toplevel_page_listing_wp").length) {
            var $updateEl = $("#toplevel_page_listing_wp a.wp-menu-open .update-count");
            var updateCount = parseInt($updateEl.text()) - 1;

            if (updateCount === 0) {
              $updateEl.parent('.update-plugins').remove();
            } else {
              $updateEl.text(updateCount);
            }
          }

          $message.slideUp(300, function () {
            $(this).remove();
          });
        }
      });
    });

    var $ajax_listings_import = $('.ajax_listings_import');
    var $progressbar = '';
    var progress_bar = '';
    if ($ajax_listings_import.length) {
      $progressbar = $('.ajax_listings_import .listing-import-progress');

      // start it at 1
      progress_bar = $progressbar.progressbar({
        value: 0
      });

      $progressbar.data('progress', 0);
      $progressbar.data('post', $ajax_listings_import.data('listing-post'));

      importNewListing($progressbar);
    }

    function importNewListing() {
      var current = parseInt($ajax_listings_import.find('span.current').text());

      $ajax_listings_import.find('span.current').text(current + 1);

      jQuery.ajax({
        url: myAjax.ajaxurl,
        type: 'POST',
        data: {action: 'automotive_import_listing', post: $progressbar.data('post')},
        dataType: 'json',
        success: function (response) {
          if (response.another_listing === true) {
            $progressbar.data('progress', ($progressbar.data('progress') + $ajax_listings_import.data('increment')));
            $progressbar.progressbar('value', $progressbar.data('progress'));

            $ajax_listings_import.find('.percent').text($progressbar.data('progress').toFixed(2) + '%');

            importNewListing();
          } else {
            $progressbar.progressbar('value', 100);
            $ajax_listings_import.find('.percent').text('100%');

            $ajax_listings_import.find('h2.importing_text').hide();
            $ajax_listings_import.find('.success.auto_message').removeClass('hide');

            $ajax_listings_import.find('.cancel-button').hide();
          }

          if (typeof response.message !== 'undefined') {
            if ($('.imported_listings').hasClass('hide')) {
              $('.imported_listings').removeClass('hide');
            }

            $('.imported_listings ul').append('<li style="display: none;">' + response.message + '</li>');
            $('.imported_listings ul li:last-of-type').slideDown();
          }

          if (typeof response.duplicates !== 'undefined') {
            if ($('.updated_listings').hasClass('hide')) {
              $('.updated_listings').removeClass('hide');
            }

            $.each(response.duplicates, function (key, message) {
              $('.updated_listings ul').append('<li style="display: none;">' + message + '</li>');
              $('.updated_listings ul li:last-of-type').slideDown();
            });
          }
        },
        error: function (jqXHR, textStatus) {
          alert($ajax_listings_import.data('error-messge').replace('%s', textStatus));
        }
      });
    }

    $('.delete_name').on('click', function (e) {
      e.preventDefault();

      var id = $(this).data('id');
      var type = $(this).data('type');
      var row = $(this).data('row');

      jQuery.ajax({
        type: 'post',
        url: myAjax.ajaxurl,
        data: {action: 'delete_name', id: id, type: type},
        success: function () {
          var table = $('#t_' + row).closest('table');

          $('#t_' + row).closest('tr').fadeOut(400, function () {
            $(this).remove();

            table.find('tr').each(function (index) {
              $(this).removeClass('alt');
              $(this).addClass((index % 2 !== 0 ? 'alt' : ''));
            });
          });
        }
      });
    });

    var changingTermAjaxInProgress = false;

    // quick edit listing category term functions
    $(document).on('click', '.edit_name_text', function (e) {
      e.preventDefault();

      var $thisRow = $(this).closest('tr');
      var $thisCell = $thisRow.find('td:eq(0)');
      var currentValue = $thisCell.text();

      // close other edits so system doesn't get confuzzled
      if ($('.current_edit_value').length) {
        var $oldCell = $('.current_edit_value').closest('td');
        var oldText = $oldCell.data('text');

        $oldCell.html(oldText);
        $('.edit_name_text').prop('disabled', false);
      }

      $(this).prop('disabled', true);

      $thisCell.data('text', currentValue);
      $thisCell.html("<input type='text' class='current_edit_value'> <button class='button-primary save_edited_value' data-original-text='" + escapeHtml(currentValue) + "'>" + $('.listing_table').data('save-text') + '</button>');

      $('.current_edit_value').focus().val(currentValue);
    });

    $(document).on('click', '.save_edited_value', function (e) {
      e.preventDefault();

      var $this = $(this);

      saveEditedValue($this);
    });

    // allow [ENTER] to save
    $(document).on('keydown', '.current_edit_value', function (e) {
      var $this = $(this).parent().find('.save_edited_value');

      if (e.which === 13) {
        saveEditedValue($this);
        return false;
      }
    });

    function saveEditedValue($this) {
      if (!changingTermAjaxInProgress) {
        changingTermAjaxInProgress = true;

        var currentValue = $this.data('original-text');
        var newValue = $('.current_edit_value').val();
        var category = $('table.listing_table').data('slug');

        var $thisRow = $this.closest('tr');
        var $thisCell = $thisRow.find('td:eq(0)');

        // dont run if current value and new value ==
        if (currentValue !== newValue) {
          $this.prop('disabled', true).html("<i class='fa fa-refresh fa-spin'></i>");

          jQuery.ajax({
            type: 'post',
            url: myAjax.ajaxurl,
            dataType: 'json',
            data: {
              action: 'edit_listing_category_value',
              category: category,
              current_value: currentValue,
              new_value: newValue
            },
            success: function (response) {
              if (response.status === 'true') {
                $thisCell.text(newValue).append(" <i class='fa fa-check'></i>");

                $thisCell.find('.fa.fa-check').delay(1000).fadeOut(300, function () {
                  $(this).remove();
                  $thisCell.text(newValue); // removes the " "
                });

                $thisRow.find('td:eq(1)').text(response.slug);

                // update buttons
                $thisRow.find('.delete_name').data('id', response.slug);
                $thisRow.find('.edit_name_text').data('id', response.slug);
              } else {
                alert(response.message); // eslint-disable-line no-undef
              }
            },
            complete: function () {
              changingTermAjaxInProgress = false;
            }
          });
        } else {
          $thisCell.text(currentValue);
        }

        $thisRow.find('.edit_name_text').prop('disabled', false);
      }
    }
  });

  $(document).on('click', '.refresh_listing_category_generate .regenerate', function (e) {
    e.preventDefault();

    var $this = $(this);
    var $icon = $this.find('.status_indicator');

    $icon.addClass('fa-spin');

    jQuery.ajax({
      type: 'post',
      url: myAjax.ajaxurl,
      dataType: 'json',
      data: {
        action: 'regenerate_listing_category_terms'
      },
      success: function (response) {
        if (response[0] === 'success') {
          $icon.removeClass('fa-spin fa-refresh').addClass('fa-check');
        }
      }
    });
  });

  $(document).on('click', '.refresh_wpml_listing_categories .regenerate_wpml', function (e) {
    e.preventDefault();

    var $this = $(this);
    var $icon = $this.find('.status_indicator_wpml');

    $icon.addClass('fa-spin');

    jQuery.ajax({
      type: 'post',
      url: myAjax.ajaxurl,
      dataType: 'json',
      data: {
        action: 'regenerate_listing_wpml_terms'
      },
      success: function (response) {
        if (response[0] === 'success') {
          $icon.removeClass('fa-spin fa-refresh').addClass('fa-check');
        }
      }
    });
  });

  $(document).on('click', '.delete_unused_terms_container .delete_unused_terms', function (e) {
    e.preventDefault();

    var $this = $(this);
    var $icon = $this.find('.status_indicator_trash');

    var confirmMessage = $(this).data('message');
    var confirming = confirm(confirmMessage);

    if (confirming) {
      $icon.addClass('fa-spin');

      jQuery.ajax({
        type: 'post',
        url: myAjax.ajaxurl,
        dataType: 'json',
        data: {
          action: 'delete_unused_listing_terms'
        },
        success: function (response) {
          if (response[0] === 'success') {
            $icon.removeClass('fa-spin fa-trash').addClass('fa-check');
          }
        }
      });
    }
  });

  $(document).on('click', '.resetListingTerms', function (e) {
    e.preventDefault();

    var alertMessage = $(this).data('alert-message');
    var deleteTerms = confirm(alertMessage); // eslint-disable-line no-undef

    if (deleteTerms) {
      jQuery.ajax({
        type: 'post',
        url: myAjax.ajaxurl,
        dataType: 'json',
        data: {
          action: 'delete_listing_category_terms'
        },
        success: function (response) {
          alert(response.text); // eslint-disable-line no-undef
        }
      });
    }
  });

  $(document).on('change', 'select[name="duplicate_check"]', function () {
    if ($(this).val() !== 'none') {
      $('.overwrite_existing_listing_images').slideDown();
    } else {
      $('.overwrite_existing_listing_images').slideUp();
    }
  });

  $(document).on('change', '#rev_slider_template', function () {
    var templateId = $(this).val();
    var $revShortcode = $('#rev_shortcode');

    $revShortcode.val("[auto_card id='" + $revShortcode.data('listing-id') + "' template='" + templateId + "']");
  });
})(jQuery);

(function ($) {
  /**
   * @param {string} YoastSEO.app
   */
  var ListingYoastPlugin = function () {
    YoastSEO.app.registerPlugin('listingYoastPlugin', {status: 'ready'});

    this.fetchData();
  };

  ListingYoastPlugin.prototype.fetchData = function () {
    YoastSEO.app.pluginReady('listingYoastPlugin');
    YoastSEO.app.registerModification('content', this.getContent, 'listingYoastPlugin', 5);
  };

  ListingYoastPlugin.prototype.getContent = function (content) {
    var extraContent = '';
    var $galleryImages = $('#gallery_images');
    var $singleGalleryImages = $galleryImages.find('.single-gallery-image');

    if ($singleGalleryImages.length) {
      $singleGalleryImages.each(function () {
        var $imagePreview = $(this).find('.image_preview');

        var alt = $imagePreview.data('alt');
        var img = $imagePreview.data('full-image');

        extraContent += "<img src='" + img + "' alt='" + alt + "'>";
      });
    }

    return extraContent ? (extraContent + content) : content;
  };

  /**
   * YoastSEO content analysis integration
   */
  $(window).on('YoastSEO:ready', function () {
    new ListingYoastPlugin(); // eslint-disable-line no-new
  });
})(jQuery);
