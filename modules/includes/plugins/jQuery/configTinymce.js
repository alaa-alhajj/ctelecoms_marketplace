var _tinyMceOptions={
        selector: "textarea.FullTextEditor", width: "500", height: 300,
        verify_html: false,
        valid_elements: "*[*]",
        extended_valid_elements: "*[*],script[charset|defer|language|src|type],style",
        custom_elements: "*[*],script[charset|defer|language|src|type],style",
        valid_children: "+body[style],+body[script]",
        script_url: "../../includes/plugins/jQuery/jQuery-2.1.3.min.js,../../includes/plugins/tinymce/tinymce.min.js,../../includes/plugins/jQuery/config.js",
        forced_root_block: false,
        force_br_newlines: true,
        force_p_newlines: true,
        font_formats: "Andale Mono=andale mono,times;" +
                "Arial=arial,helvetica,sans-serif;" +
                "Arial Black=arial black,avant garde;" +
                "Book Antiqua=book antiqua,palatino;" +
                "Comic Sans MS=comic sans ms,sans-serif;" +
                "Courier New=courier new,courier;" +
                "Georgia=georgia,palatino;" +
                "Helvetica=helvetica;" +
                "Impact=impact,chicago;" +
                "Symbol=symbol;" +
                "Tahoma=tahoma,arial,helvetica,sans-serif;" +
                "Terminal=terminal,monaco;" +
                "Times New Roman=times new roman,times;" +
                "Trebuchet MS=trebuchet ms,geneva;" +
                "Verdana=verdana,geneva;" +
                "Webdings=webdings;" +
                "Wingdings=wingdings,zapf dingbats"
        , fontsize_formats: "8pt 10pt 12pt 14pt 18pt 24pt 36pt",
        plugins: [
            "advlist autolink link image lists charmap save print preview hr anchor pagebreak ",
            "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
            "table contextmenu directionality emoticons paste textcolor colorpicker responsivefilemanager", "directionality"
                    , "code", "widgets", "noneditable", "bootstraptabs","bootstrapaccordion"
        ], toolbar: "save",
        save_enablewhendirty: true,
        
        toolbar1: " undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist | outdent indent | ltr  rtl | blockquote | removeformat |  fontsizeselect | styleselect | formatselect |  subscript superscript ",
        toolbar2: " link unlink anchor | image media | forecolor backcolor  | print preview code | widgets | cols-table | bootstraptabs | bootstrapaccordion",
        image_advtab: true,
        link_list: "../../views/ajax/getLinksTinyMCE.php",
        content_css: "../../includes/bootstrap/css/bootstrap.min.css , ../../includes/plugins/tinymce/skins/lightgray/tiny.css ",
        external_filemanager_path: _PREF + _MODULES_FOLDER + "/includes/File_Manager/filemanager/",
        filemanager_title: "Voila Filemanager",
        external_plugins: {"filemanager": _PREF + _MODULES_FOLDER + "/includes/File_Manager/filemanager/plugin.min.js"},
        contextmenu: "cut copy paste",
        menubar: true,
        statusbar: true,
        toolbar_item_size: "small",
        setup: function(editor) {
         
            editor.addButton('cols-table', {
                type: 'listbox',
                text: 'Cols',
                icon: false,
                onselect: function(e) {
                    editor.insertContent(this.value());
                },
                values: [
                    {text: 'Table 1 Col', value: '<div class="row"><div class="col-sm-12">\n\
                                                   </div></div></br></br>'},
                    {text: 'Table 2 Col', value: '<div class="row">\n\
                                                      <div class="col-sm-6"></div>\n\
                                                      <div class="col-sm-6"></div>\n\
                                                    </br></br>'},
                    {text: 'Table 3 Col', value: '<div class="row">\n\
                                                      <div class="col-sm-4"></div>\n\
                                                      <div class="col-sm-4"></div>\n\
                                                      <div class="col-sm-4"></div>\n\
                                                    </div></br></br>'},
                    {text: 'Table 4 Col', value: '<div class="row">\n\
                                                      <div class="col-sm-3"></div>\n\
                                                      <div class="col-sm-3"></div>\n\
                                                      <div class="col-sm-3"></div>\n\
                                                      <div class="col-sm-3"></div>\n\
                                                    </div></br></br>'}
                ],
                onPostRender: function() {
                    this.value('Some text 2');
                }
            });
        }
    };
function TineMceLoadCMS() {
    tinymce.init(_tinyMceOptions);
    tinymce.init({
        selector: "textarea.SimpleTextEditor", width: "100%", height: 100,
        plugins: [
        ],
        toolbar1: "bold italic underline | alignleft aligncenter alignright alignjustify",
        toolbar2: " link unlink anchor |  forecolor backcolor  | print preview code "
    });

}
TineMceLoadCMS();