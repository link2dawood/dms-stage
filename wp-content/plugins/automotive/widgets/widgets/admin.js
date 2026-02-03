jQuery(document).ready(function($){
  window['flask'] = [];

  var mediaFrame = [];
  var currentThis;

  if(typeof $.fn.wpColorPicker !== 'undefined'){
    $( '.widget-color-picker' ).wpColorPicker();
  }

  $(document).on('click.mojoOpenMediaManager', '.widget-choose-image', function (e) {
    e.preventDefault();

    currentThis = $(this);

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

      var $container = currentThis.closest('.widget-select-image-container');

      $container.addClass('has-image');

      $container.find('img').attr('src', mediaAttachment[0].url);
      $container.find('input.image-value').val(mediaAttachment[0].id);
    });

    mediaFrame[0].open();
  });

  $(document).on('click', '.widget-remove-image', function(e){
    e.preventDefault();

    var $container = currentThis.closest('.widget-select-image-container');

    $container.removeClass('has-image');

    $container.find('img').attr('src', '');
    $container.find('input.image-value').val('');
  });

  $(document).on('click', '.widget-add-repeater-field', function(e){
    e.preventDefault();

    var $container = $(this).closest('.widget-repeater-container');
    var template   = wp.template('repeater-' + $(this).data('shortcode'));
    var fieldName  = $(this).data('field-name');

    $container.find('.single-repeater-row:last-of-type').after("<div class='single-repeater-row'>" + template({newID: '_' + Math.random().toString(36).substr(2, 9) }) + "</div>");

    $container.find('.single-repeater-row:last-of-type :input').each(function(){
      $(this).attr('name', fieldName.replace('__NAME__', $(this).data('name')));
    });

    initWidgetControls();
  });

  var wysiwygTimers = [];
  function initWidgetControls(){
    if(typeof $.fn.wpColorPicker !== 'undefined'){
      $( '.widget-color-picker' ).wpColorPicker();
    }
    // wp.editor.initialize('wysiwyg_test', true);

    // clear any previous timers
    if(wysiwygTimers.length){
      for (var i = 0; i < wysiwygTimers.length; i++) {
        clearInterval(wysiwygTimers[i]);
      }

      wysiwygTimers = [];
    }

    $(".widget.open .widget-wp-editor, .so-content .widget-wp-editor").each(function(){
      // if($(this).is(':visible')){
        var id = $(this).attr('id');

        wp.editor.remove(id);
        wp.editor.initialize(id);

        wysiwygTimers.push(setInterval(function(){
          // can we check if dirty?
          // console.log(wp.editor.getContent(id) != $('textarea#' + id).val());
          $('textarea#' + id).val(wp.editor.getContent(id));

          if(wp.editor.getContent(id) != $('textarea#' + id).val()){
          //   // console.log('trigger', id, wp.editor.getContent(id),  $('textarea#' + id).val())
          //   $('textarea#' + id).trigger('change');
          //   $('textarea#' + id).trigger('input');
          }
        }, 250));

        // wp.editor.setContent(id, $(this).val());
      // }
    });

    $(".widget-html-field").each(function(){
      var id = $(this).attr('id');

      const flask = new CodeFlask('#' + id, {
        language: 'html',
        lineNumbers: true,
        areaId: 'thing1',
        ariaLabelledby: 'header1',
        handleTabs: true
      });

      flask.updateCode($(this).data('value'));

      flask.onUpdate(function(code){
        // console.log('id', id, code)

        $('textarea.widget-html-field-code[data-id="' + id + '"]').val(code);
      });

      window['flask'].push(flask);

      // console.log('#' + id, $('#' + id).parent());
    });

    // todo: since flask doesn't set name attributes on textareas lets set this using JS
    //  but lets do a pull request and add this functionality ourselves

  }

  initWidgetControls();

  // widget-updated/widget-synced
  $(document).on('widget-updated', function(e, widget){
    console.log('widget-updated', widget);

    initWidgetControls();
  });

  $(document).on('widget-added', function(e, widget){
    initWidgetControls();
  });

  $(document).on('panelsopen', function(e){
    console.log('page builder panelsopen');

    initWidgetControls();
  });

  $(document).on('click', '.widget-top', function(e){
    console.log('widget-open');

    setTimeout(function(){
      console.log('widget-open');

      initWidgetControls();
    },500);
  });

  // for some reason the wp.editor isn't updating the textarea so we do this manually when the widget gets saved
  $(document).on('click', '.widget-control-save', function(e) {
      var $wpEditors    = $(this).closest('form').find('.widget-wp-editor');

      if($wpEditors.length){
        $wpEditors.each(function(){
          var wpEditorID = $(this).attr('id');

          $('textarea#' + wpEditorID).val(wp.editor.getContent(wpEditorID));
        })
      }
  });

  $(document).on('click', '.widget-choose-url', function(e){
    e.preventDefault();

    wpLink.open($(this).data('editor-id'));

    return false;
  });

  $(document).on('change', '.widget-url-textarea', function(e){
    // console.log($(this).val());
    // console.log(wpLink.getUrlFromSelection($(this).val()))


    // console.log('test', wpLink.getAttrs());

    // console.log();
    if(wpLink.getAttrs().href){
      $(this).val(wpLink.getAttrs().href + "," + (typeof wpLink.getAttrs().target !== 'undefined' ? wpLink.getAttrs().target : '_self'));

      $(this).closest('div').find('.widget-url-text').text(wpLink.getAttrs().href);

      wpLink.getAttrs().href = false;
      wpLink.getAttrs().target = false;
    }
  });

});