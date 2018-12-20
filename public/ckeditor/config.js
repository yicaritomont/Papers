/**
 * @license Copyright (c) 2003-2018, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function( config ) {
  config . filebrowserBrowseUrl = ' /inspectors_application/public/kcfinder/browse.php?opener=ckeditor&type=files ' ;
   config . filebrowserImageBrowseUrl = ' /inspectors_application/public/kcfinder/browse.php?opener=ckeditor&type=images ' ;
   config . filebrowserFlashBrowseUrl = ' /inspectors_application/public/kcfinder/browse.php?opener=ckeditor&type=flash ' ;
   config . filebrowserUploadUrl = ' /inspectors_application/public/kcfinder/upload.php?opener=ckeditor&type=files ' ;
   config . filebrowserImageUploadUrl =' /inspectors_application/public/kcfinder/upload.php?opener=ckeditor&type=images ' ;
   config . filebrowserFlashUploadUrl = ' /inspectors_application/public/kcfinder/upload.php?opener=ckeditor&type=flash ' ;
};
