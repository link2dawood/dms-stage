(function ($) {
  $(document).ready(function ($) {
    // preload the PDF images
    if(!$.fn.preloadPDFImages){
      $.preloadPDFImages = function preloadPDFImages(){
        var $printLogo     = $('.logo > img.pdf_print_logo');
        var $mainLogo      = $('.logo > img.main_logo');
        var $googleMapInit = $('.google_map_init');
        var $tabContent  = $('#myTabContent');
        var $sliderThumbs  = $('.home-slider-thumbs ul li[data-thumb]');

        // generate main logo base64
        if ($printLogo.length === 1 && $printLogo.attr('src')) {
          var imageLogoUrl = $printLogo.prop('src');
          $.convertImgToBase64(imageLogoUrl, function (base64) {
            $printLogo.data('base_64', base64);

            Listing.pdfPreloadActions.logo = true;
          });
        } else if ($mainLogo.length === 1 && $mainLogo.attr('src')) {
          var imageLogoUrl = $mainLogo.prop('src');
          $.convertImgToBase64(imageLogoUrl, function (base64) {
            $mainLogo.data('base_64', base64);

            Listing.pdfPreloadActions.logo = true;
          });
        } else {
          // if no image is found they are using text and we can proceed
          Listing.pdfPreloadActions.logo = true;
        }

        // google map image
        if ($googleMapInit.length) {
          var latitude = $googleMapInit.data('latitude');
          var longitude = $googleMapInit.data('longitude');
          var zoom = $googleMapInit.data('zoom');

          var httpPrefix = (listing_ajax.is_ssl ? 'https' : 'http');

          $.convertImgToBase64(httpPrefix + '://maps.google.com/maps/api/staticmap?center=' + latitude + ',' + longitude + '&zoom=' + zoom + '&size=700x170&maptype=roadmap&language=&markers=color:red|label:S|' + latitude + ',' + longitude + '&key=' + listing_ajax.google_maps_api, function (base64) {
            $googleMapInit.data('base_64', base64);

            Listing.pdfPreloadActions.map = true;
          });
        } else {
          Listing.pdfPreloadActions.map = true;
        }

        // any images within content
        if($tabContent.find('div img').length){
          var tabImages          = $tabContent.find('div img').length;
          var tabCompletedImages = 0;

          $tabContent.find('div').each(function () {
            // check for images
            $(this).find('img').each(function () {
              var $this = $(this);
              var thisSrc = $this.prop('src');

              $.convertImgToBase64(thisSrc, function (base64) {
                $this.data('base_64', base64);

                tabCompletedImages++;

                if(tabImages === tabCompletedImages){
                  Listing.pdfPreloadActions.tabs = true;
                }
              });
            });
          });
        } else {
          Listing.pdfPreloadActions.tabs = true;
        }

        if($sliderThumbs.length){
          var totalImages     = $sliderThumbs.length;
          var completedImages = 0;

          $sliderThumbs.each(function () {
            var thisI = $(this);
            var image = thisI.data('thumb');

            $.convertImgToBase64(image, function (base64) {
              thisI.data('base_64', base64);

              completedImages++;

              if(completedImages === totalImages){
                Listing.pdfPreloadActions.images = true;
              }
            });
          });
        } else {
          Listing.pdfPreloadActions.images = true;
        }
      }
    }

    // create the PDF and save
    if(!$.fn.printListingPDF){
      $.printListingPDF = function printdefaultListingPDF(doc){
        // get elements
        var $printLogo = $('header .logo > img.pdf_print_logo');
        var $mainLogo = $('header .logo > img.main_logo');
        var $primaryText = $('.logo .primary_text');
        var $secondaryText = $('.logo .secondary_text');
        var imageLogo = false;

        if ($printLogo.length && $printLogo.attr('src') !== '') {
          imageLogo = $printLogo.data('base_64');
        } else if ($mainLogo.length && $mainLogo.attr('src') !== '') {
          imageLogo = $mainLogo.data('base_64');
        } else {
          var title = ($primaryText.length ? $primaryText.text() : listing_ajax.pdf.primary_text);
          var secondary = ($secondaryText.length ? $secondaryText.text() : listing_ajax.pdf.secondary_text);
        }

        var telephone = $.trim($('.company_info li:first a').text());
        var address = $.trim($('.company_info li:eq(1) a').text());

        var carTitle = $('.inventory-heading .col-lg-9 h2:first').text();
        var carPrice = $('.inventory-heading h2:not(.original_price):eq(1)').text();

        // car_title length
        if (carTitle.length > 40) {
          carTitle = carTitle.substr(0, 40) + '...';
        }

        var carSecondary = $('.inventory-heading .col-lg-9 span').text();
        var carTaxes = $('.inventory-heading em').text();

        var leftMargin = 10;
        var rightMargin = 10;

        var topSpaceInt = 20;

        // write header
        if (typeof imageLogo === 'undefined' || !imageLogo) {
          doc.text(leftMargin, 15, title + ' ' + secondary);
        } else {
          doc.addImage(imageLogo, 'PNG', leftMargin, 15);
          topSpaceInt += (parseInt($('.logo > img').prop('naturalHeight')) * 0.26458333333333);
        }

        doc.setFontSize(12);
        doc.text(leftMargin, topSpaceInt, telephone);

        topSpaceInt += 5;

        doc.text(leftMargin, topSpaceInt, address);

        // set bigger font for listing title & price
        doc.setFontSize(20);
        topSpaceInt += 15;
        doc.setFontStyle('bold');
        doc.text(leftMargin, topSpaceInt, carTitle);

        doc.setFontStyle('bold');
        doc.myText(carPrice, {align: 'right'}, rightMargin, topSpaceInt);

        // smaller text for under
        doc.setFontStyle('normal');
        doc.setFontSize(10);
        topSpaceInt += 8;
        doc.text(leftMargin, topSpaceInt, carSecondary);
        doc.myText(carTaxes, {align: 'right'}, rightMargin, topSpaceInt);

        topSpaceInt += 15;

        // get first 6 images
        var i = 0;
        var inc = 0;
        var top = topSpaceInt;
        var tableTopSpacing = topSpaceInt;
        var left = leftMargin;

        var leftSpacing = 38;
        var topSpacing = 0;

        $('.home-slider-thumbs ul li[data-thumb]').each(function (index) {
          var base64 = $(this).data('base_64');

          doc.addImage(base64, 'PNG', left + (inc * leftSpacing), (top + topSpacing), 36, 26);

          inc = (inc === 1 ? 0 : inc + 1);

          if (inc === 0) {
            topSpacing += 28;
          }

          if (index === 5) {
            return false;
          }
        });

        // get table
        var height = tableTopSpacing;

        $('.car-info:first table tr').each(function (index) {
          var first = $(this).find('td:first').text();
          var second = $(this).find('td:eq(1)').text();

          doc.setFontStyle('bold');
          doc.text(120, height, first);

          var infoDim = doc.getTextDimensions(first);
          var infoWidth = infoDim.w;

          var textDim = doc.getTextDimensions(second);
          var textWidth = textDim.w;
          // var text_height = text_dim.h;

          doc.setFontStyle('normal');

          if ((textWidth + infoWidth) > 250) {
            var splitInfo = doc.splitTextToSize(second, 50);// info_width);

            var textHeight = splitInfo.length * 2.5;

            doc.myText(splitInfo.join('\r\n'), {align: 'right'}, rightMargin + 50, height);

            height += textHeight;
          } else {
            doc.myText(second, {align: 'right'}, rightMargin, height);
          }

          height += 5;

          return index < 17;
        });

        // process vehicle info tabs
        height = (topSpaceInt + 100);
        // height = (topSpaceInt += 100);
        var pageHeight = doc.internal.pageSize.height;
        var pageWidth = doc.internal.pageSize.width;

        var $tabPane = $('.tab-content .tab-pane');

        $('.nav-tabs li').each(function (index) {
          var theLink = $(this).find('a').attr('href');

          var title = $.trim($(this).text());
          var desc = $.automotiveQuoteReplace($.trim($tabPane.eq(index).text()));

          var textDim = doc.getTextDimensions(title);
          var textHeight = textDim.h;

          var splitDesc = doc.splitTextToSize(desc, 190);
          var descDim = doc.getTextDimensions(splitDesc);
          var descHeight = descDim.h;

          var $technical = $('#technical');
          var $technicalTable = $technical.find('table');
          var $features = $('#features');

          if (theLink === '#technical' && $technicalTable.length) {
            // 159
            var totalLines = 0;
            $technicalTable.each(function () {
              totalLines += $(this).find('tr').length;
            });

            descHeight = (totalLines * 4.5);
          } else if (theLink !== '#location') {
            totalLines = doc.splitTextToSize(desc, 190);
            // lineHeight = doc.internal.getLineHeight();

            descHeight = (totalLines.length * 4.5);
          }

          var toBeHeight = (height + textHeight + descHeight);

          // if goes off of page, make new one
          if (toBeHeight >= pageHeight) {
            doc.addPage();
            height = 20;
          }

          doc.setFontSize(20);
          doc.text(leftMargin, height, title);

          height += 5;

          doc.setFontSize(10);
          if (theLink === '#features') {
            desc = $features.find('ul').data('list');
            splitDesc = doc.splitTextToSize(desc, 190);
          } else if (theLink === '#technical' && $technicalTable.length) {
            var csv = '';

            $technicalTable.each(function () {
              $(this).find('tr').each(function () {
                $(this).find('td').each(function () {
                  csv += $(this).text() + ', ';
                });

                csv = csv.slice(0, -2);
                csv += '\n';
              });

              csv += '\n\n';
            });

            splitDesc = doc.splitTextToSize(csv, 190);
          }

          if (theLink !== '#location') {
            if (splitDesc instanceof Array) {
              for (i = 0; i < splitDesc.length; i++) {
                var descDimI = doc.getTextDimensions(splitDesc[i]);
                var descHeightI = descDimI.h;

                if ((height + descHeightI) >= pageHeight) {
                  doc.addPage();
                  height = 20;
                }

                height += 5;
                doc.text(leftMargin, height, splitDesc[i]);
              }
            } else {
              doc.text(leftMargin, height, splitDesc);
            }

            // add any images in content
            if ($tabPane.eq(index).find('img').length) {
              height += descHeight;

              $tabPane.eq(index).find('img').each(function () {
                var base64 = $(this).data('base_64');
                var imageHeight = ($(this).get(0).height * 0.26458333333333);
                var imageWidth = ($(this).get(0).width * 0.26458333333333);
                /* (page_width - (right_margin * 2)) */

                // scale down large images
                var totalPageWidth  = (pageWidth - (rightMargin * 2));
                var totalPageHeight = (pageHeight - (rightMargin * 2));

                // scale down image width
                if (imageWidth > totalPageWidth) {
                  var scaleDownRatio = (totalPageWidth / imageWidth);

                  imageWidth = (imageWidth * scaleDownRatio);
                  imageHeight = (imageHeight * scaleDownRatio);
                }

                // scale down image heights
                if(imageHeight > totalPageHeight){
                  var scaleDownRatio = (totalPageHeight / imageHeight);

                  imageWidth = (imageWidth * scaleDownRatio);
                  imageHeight = (imageHeight * scaleDownRatio);
                }

                if ((height + imageHeight) >= pageHeight){
                  doc.addPage();
                  height = 20;
                }

                doc.addImage(base64, 'PNG', leftMargin, height, imageWidth, imageHeight);

                height += imageHeight + (10);
              });
            } else {
              height += descHeight + (10);
            }
          } else {
            var $googleMapInit = $('.google_map_init');
            if ($googleMapInit.length && typeof $googleMapInit.data('base_64') !== 'undefined') {
              doc.addImage($googleMapInit.data('base_64'), 'PNG', leftMargin, height, (pageWidth - (rightMargin * 2)), 50);
              descHeight = 60;
            } else {
              console.log('Google Static Map API Could Not Be Accessed');
            }

            height += descHeight + (10);
          }
        });

        doc.save('listing.pdf');
      }
    }

  });
})(jQuery);
