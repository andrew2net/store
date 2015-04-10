<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php $elfinderPath = Yii::app()->assetManager->publish(Yii::getPathOfAlias('ext.elFinder2.assets')); ?>
<html>
    <head>
        <meta charset="utf-8">
        <title>elFinder 2.0</title>

        <!-- jQuery and jQuery UI (REQUIRED) -->
        <link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/themes/smoothness/jquery-ui.css">
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
        <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js"></script>

        <script type="text/javascript" src="<?php echo $elfinderPath; ?>/js/tiny_mce_popup.js"></script>

        <!-- elFinder CSS (REQUIRED) -->
        <link rel="stylesheet" type="text/css" href="<?php echo $elfinderPath; ?>/css/elfinder.min.css">
        <link rel="stylesheet" type="text/css" href="<?php echo $elfinderPath; ?>/css/theme.css">

        <!-- elFinder JS (REQUIRED) -->
        <script src="<?php echo $elfinderPath; ?>/js/elfinder.min.js"></script>

        <!-- elFinder translation (OPTIONAL) -->
        <script src="<?php echo $elfinderPath; ?>/js/i18n/elfinder.ru.js"></script>

        <!-- elFinder initialization (REQUIRED) -->
        <script type="text/javascript" charset="utf-8">
          var FileBrowserDialogue = {
              init: function () {
                  // Here goes your code for setting your custom things onLoad.
              },
              mySubmit: function (URL) {
                  var win = tinyMCEPopup.getWindowArg('window');

                  // pass selected file path to TinyMCE
                  win.document.getElementById(tinyMCEPopup.getWindowArg('input')).value = URL;

                  // are we an image browser?
                  if (typeof (win.ImageDialog) != 'undefined') {
                      // update image dimensions
                      if (win.ImageDialog.getImageData) {
                          win.ImageDialog.getImageData();
                      }
                      // update preview if necessary
                      if (win.ImageDialog.showPreviewImage) {
                          win.ImageDialog.showPreviewImage(URL);
                      }
                  }

                  // close popup window
                  tinyMCEPopup.close();
              }
          }

          tinyMCEPopup.onInit.add(FileBrowserDialogue.init, FileBrowserDialogue);
          // Documentation for client options:
          // https://github.com/Studio-42/elFinder/wiki/Client-configuration-options
          $(document).ready(function () {
              $('#elfinder').elfinder({
                  url: '/admin/elfinder/connector'  // connector URL (REQUIRED)
                  , lang: 'ru', // language (OPTIONAL)
                  closeOnEditorCallback: true,
                  getFileCallback: function (files, fm) {
                      FileBrowserDialogue.mySubmit(files.url); // pass selected file path to TinyMCE 
                  }
              });
          });
        </script>
    </head>
    <body>
        <!-- Element where elFinder will be created (REQUIRED) -->
        <div id="elfinder"></div>
    </body>
</html>
