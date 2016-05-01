tinymce.PluginManager.add('bootstrapaccordion', function(editor, url) {


    function showDialog() {

        function makeID()
        {
            var text = "";
            var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

            for (var i = 0; i < 5; i++)
                text += possible.charAt(Math.floor(Math.random() * possible.length));

            return text;
        }
        ;
        $makeID = makeID();
        var win;
        var selection = editor.selection;
        var selectedElm;

        selectedElm = selection.getNode();
        var htmlSelected = selectedElm.innerHTML;

        TineMceLoadCMS();

        var _define = "Bootstrap_TinyMCE_Accordion" + $makeID + "_";

        var TabID = "FRE";
        var cssJsFile = '<link rel="stylesheet" href="../../includes/bootstrap/css/bootstrap.min.css" />';
        cssJsFile += '<script src="../../includes/plugins/jQuery/configTinymce.js"></script>';
        cssJsFile += '<script src="../../includes/plugins/jQuery/jQuery-2.1.3.min.js"></script>';
        cssJsFile += '<script src="../../includes/plugins/fontawesome-iconpicker/dist/js/fontawesome-iconpicker.min.js"></script>';
        cssJsFile += '<link rel="stylesheet" href="../../includes/plugins/fontawesome-iconpicker/dist/css/fontawesome-iconpicker.min.css" />';
        cssJsFile += '<link rel="stylesheet" href="../../includes/font_awesome/css/font-awesome.min.css" />';
        var html = "<button type='button' class='btn btn-danger' id='" + _define + "btn1' name='" + _define + "btn1'>Add New Accordion</button>";
        html += "<div id='" + _define + "Accordion' style='width:100%;overflow-y:scroll;height:350px;overflow-x:hidden;border:1px solid #ccc' class='form-horizontal'></div>";

        html = cssJsFile + html;
        win = editor.windowManager.open({
            autoScroll: false,
            width: "900",
            height: 400,
            top: 10,
            title: 'Bootstrap Tabs',
            spacing: 20,
            padding: 10,
            items: [{type: 'container', minWidth: 885, minHeight: 350, html: html}],
            buttons: [
                {
                    text: 'Insert',
                    classes: 'widget btn primary first abs-layout-item',
                    onclick: function() {
                        var Accordions = "";

                        var counterTabs = 1;
                        $('.' + _define + 'TAccordionDiv ' + '.' + _define + 'AccordionTitle').each(function() {
                            $idV = $(this).data('id');
                            if (counterTabs === 1) {
                                $classActive = "";
                                $areaExpend = "true";
                                $classIn = "in";
                            } else {
                                $classActive = "collapsed";
                                $areaExpend = "false";
                                $classIn = "";
                            }
                            $collapseId = _define + counterTabs;
                            $collapseHeaderId = _define + "Heading" + counterTabs;
                            var AccordionContent = $("#" + _define + "ContentAccordion" + $idV + "_ifr").contents().find('body').html();
                            Accordions += "<div class='panel panel-default'>";
                            Accordions += "<div class='panel-heading' role='tab' id='" + $collapseHeaderId + "'>";
                            Accordions += "<h4 class='panel-title'>";
                            Accordions += "<a role='button' class='" + $classActive + "' data-toggle='collapse' data-parent='#" + _define + "' href='#" + $collapseId + "' aria-expanded='" + $areaExpend + "' aria-controls='" + $collapseId + "'>";
                            Accordions += $(this).val();
                            Accordions += "</a>";
                            Accordions += "</h4>";
                            Accordions += "</div>";
                            Accordions += "<div id='" + $collapseId + "' class='panel-collapse collapse " + $classIn + "' role='tabpanel' aria-labelledby='" + $collapseHeaderId + "'>";
                            Accordions += "<div class='panel-body'>";
                            Accordions += AccordionContent;
                            Accordions += "</div>";
                            Accordions += "</div>";
                            Accordions += "</div>";



                            counterTabs++;
                        });
                        selection.setContent('');
                        if (Accordions !== undefined && Accordions !== "") {

                            editor.insertContent("<div class='mceNonEditable AccordionNoneEditable panel-group' id='accordion' role='tablist' aria-multiselectable='true'>" + Accordions + "</div><br /><br />");
                        }
                        win.close();
                    }
                }, {
                    text: 'Close',
                    onclick: function() {
                        win.close();
                    }
                }]
        });

        var accordionCounter = 1;
        var tabArray = [];

        //alert(editor.getContent().replace(/(\r\n|\n|\r)/gm, "")+"\n"+selectedElm.innerHTML.replace(/ data-mce-contenteditable="false"/gi, "").replace(/(\ data-mce-href=".*?\")/gi, "").replace(/<br>/gi,"<br />"));
        if (editor.getContent().replace(/(\r\n|\n|\r)/gm, "") !== selectedElm.innerHTML.replace(/ data-mce-contenteditable="false"/gi, "").replace(/(\ data-mce-href=".*?\")/gi, "").replace(/<br>/gi, "<br />")) {
           // alert(selectedElm.innerHTML);
            $(selectedElm).find('div.panel.panel-default').each(function(index) {

                $index = index + 1;
                $textAccordion = $(this).children('div.panel-heading').text();
               // alert($textAccordion);
                $headerId = $(this).children('div.panel-heading').attr('id');
                $contentAccordion = $(selectedElm).find('div[aria-labelledby="' + $headerId + '"] .panel-body').html();
               // alert($contentAccordion);



                if ($contentAccordion !== undefined) {
                    var htmlTabContainer = "<div class='form-horizontal " + _define + "TAccordionDiv' id='" + _define + "TAccordionDiv" + accordionCounter + "' style='margin-top:20px;border-bottom:1px solid #ccc;padding:5px;'  id='" + _define + "tab' class='form-group' data-id='" + accordionCounter + "'>";
                    htmlTabContainer += "<div class='form-group'>";
                    htmlTabContainer += "<label class='col-xs-2'>Accordion Title</label>";
                    htmlTabContainer += "<div class='col-xs-8'><input type='text' value='" + $textAccordion + "' data-id='" + accordionCounter + "' class='" + _define + "AccordionTitle title' id='" + _define + "AccordionTitle" + accordionCounter + "' style='width:100%;height:30px;padding:2px;border:1px solid #ccc' /></div>";
                    htmlTabContainer += "<button class='removeAccordionBo btn btn-default' type='button'  data-target='" + _define + "TAccordionDiv" + accordionCounter + "'>x</button></div>";
                    htmlTabContainer += "<div class='form-group'>";
                    htmlTabContainer += "<label class='col-xs-12'>Accordion Description</label>";
                    htmlTabContainer += "<div class='col-xs-12'><textarea  id='" + _define + "ContentAccordion" + accordionCounter + "' class='FullTextEditor' >" + $contentAccordion + "</textarea></div>";
                    htmlTabContainer += "</div>";
                    htmlTabContainer += "</div>";
                    document.getElementById(_define + 'Accordion').insertAdjacentHTML('beforeend', htmlTabContainer);
                    TineMceLoadCMS();
                    $('.iconpicker').iconpicker();
                    accordionCounter++;
                }
            });
        }

        TineMceLoadCMS();
        $('.removeAccordionBo').click(function() {
            $target = $(this).data('target');
            $('#' + $target).remove();
        });
        document.getElementById(_define + 'btn1').addEventListener("click", function() {
            var htmlTabContainer = "<div id='" + _define + "TAccordionDiv" + accordionCounter + "' class='form-horizontal " + _define + "TAccordionDiv' style='margin-top:20px;border-bottom:1px solid #ccc;padding:5px;'  id='" + _define + "tab' class='form-group' data-id='" + accordionCounter + "'>";
            htmlTabContainer += "<div class='form-group'>";
            htmlTabContainer += "<label class='col-xs-2'>Accordion Title</label>";
            htmlTabContainer += "<div class='col-xs-8'><input type='text' data-id='" + accordionCounter + "' class='" + _define + "AccordionTitle title' id='" + _define + "AccordionTitle" + accordionCounter + "'style='width:100%;height:30px;padding:2px;border:1px solid #ccc' /></div>";
            htmlTabContainer += "<button class='removeAccordionBo btn btn-default' type='button'  data-target='" + _define + "TAccordionDiv" + accordionCounter + "'>x</button></div>";
            htmlTabContainer += "<div class='form-group'>";
            htmlTabContainer += "<label class='col-xs-12'>Accordion Description</label>";
            htmlTabContainer += "<div class='col-xs-12'><textarea  id='" + _define + "ContentAccordion" + accordionCounter + "' class='FullTextEditor' ></textarea></div>";
            htmlTabContainer += "</div>";
            htmlTabContainer += "</div>";
            document.getElementById(_define + 'Accordion').insertAdjacentHTML('beforeend', htmlTabContainer);
            TineMceLoadCMS();
            $('.iconpicker').iconpicker();
            accordionCounter++;
        });

    }
    editor.addButton('bootstrapaccordion', {
        text: "Accordion",
        tooltip: "Accordion",
        onclick: showDialog,
        stateSelector: 'div.AccordionNoneEditable'
    });
});