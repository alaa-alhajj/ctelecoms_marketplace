tinymce.PluginManager.add('bootstraptabs', function(editor, url) {


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

        var _define = "Bootstrap_TinyMCE_" + $makeID + "_";

        var TabID = "FRE"+$makeID;
        var cssJsFile = '<link rel="stylesheet" href="../../includes/bootstrap/css/bootstrap.min.css" />';
        cssJsFile += '<script src="../../includes/plugins/jQuery/configTinymce.js"></script>';
        cssJsFile += '<script src="../../includes/plugins/jQuery/jQuery-2.1.3.min.js"></script>';
        cssJsFile += '<script src="../../includes/plugins/fontawesome-iconpicker/dist/js/fontawesome-iconpicker.min.js"></script>';
        cssJsFile += '<link rel="stylesheet" href="../../includes/plugins/fontawesome-iconpicker/dist/css/fontawesome-iconpicker.min.css" />';
        cssJsFile += '<link rel="stylesheet" href="../../includes/font_awesome/css/font-awesome.min.css" />';
        var html = "<button type='button' class='btn btn-danger' id='" + _define + "btn1' name='" + _define + "btn1'>Add New Tab</button>";
        html += "<div id='" + _define + "tabs' style='width:100%;overflow-y:scroll;height:350px;overflow-x:hidden;border:1px solid #ccc' class='form-horizontal'></div>";

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
                        var ulTabs = "";
                        var divTabs = "";
                        var counterTabs = 1;
                        $('.' + _define + 'TTabDiv ' + '.' + _define + 'tabTitle').each(function() {
                            $idV = $(this).data('id');
                            if (counterTabs === 1) {
                                $classActive = "active";
                            } else {
                                $classActive = "";
                            }
                            var TabName = TabID + "_li_" + counterTabs;
                            var Icon = $("#" + _define + "tabIcon" + $idV).val();

                            var TabText = $(this).val();
                            if (Icon !== undefined && Icon !== "") {
                                TabText += "<span class='fa " + Icon + "'></span>";
                            }
                            var TabContent = $("#" + _define + "ContentTab" + $idV + "_ifr").contents().find('body').html();
                            ulTabs += "<li role='presentation' class='" + $classActive + "'><a href='#" + TabName + "' aria-controls='" + TabName + "' role='tab' data-toggle='tab'>" + TabText + "</a></li>";
                            divTabs += "<div role='tabpanel' class='tab-pane " + $classActive + "' id='" + TabName + "'>" + TabContent + "</div>";
                            counterTabs++;
                        });
                        selection.setContent('');
                        if (ulTabs !== undefined && ulTabs !== "") {
                            ulTabs = '<ul class="nav nav-tabs" role="tablist">' + ulTabs + "</ul>";
                            divTabs = '<div class="tab-content">' + divTabs + '</div>';
                            editor.insertContent("<div class='mceNonEditable TabsNoneEditable'>" + ulTabs + divTabs + "</div><br /><br />");
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

        var tabCounter = 1;
        var tabArray = [];

        //alert(editor.getContent().replace(/(\r\n|\n|\r)/gm, "")+"\n"+selectedElm.innerHTML.replace(/ data-mce-contenteditable="false"/gi, "").replace(/(\ data-mce-href=".*?\")/gi, "").replace(/<br>/gi,"<br />"));
        if (editor.getContent().replace(/(\r\n|\n|\r)/gm, "") !== selectedElm.innerHTML.replace(/ data-mce-contenteditable="false"/gi, "").replace(/(\ data-mce-href=".*?\")/gi, "").replace(/<br>/gi, "<br />")) {
            $(selectedElm).find('ul:nth-child(1)>li').each(function(index) {

                $index = index + 1;
                $textTab = $(this).text();
                $contentTab = $(selectedElm).find('div.tab-content>div:nth-child(' + $index + ')').html();
                $Icon = $(this).children('a').children('span.fa').attr('class');

                if ($Icon !== undefined && $Icon !== "") {
                    $Icon = $Icon.replace("fa ", "");
                } else {
                    $Icon = "";
                }


                if ($contentTab !== undefined) {
                    var htmlTabContainer = "<div class='form-horizontal " + _define + "TTabDiv' id='" + _define + "TTabDiv" + tabCounter + "' style='margin-top:20px;border-bottom:1px solid #ccc;padding:5px;'  id='" + _define + "tab' class='form-group' data-id='" + tabCounter + "'>";
                    htmlTabContainer += "<div class='form-group'>";
                    htmlTabContainer += "<label class='col-xs-2'>Tab Title</label>";
                    htmlTabContainer += "<div class='col-xs-8'><input type='text' value='" + $textTab + "' data-id='" + tabCounter + "' class='" + _define + "tabTitle title' id='" + _define + "tabTitle" + tabCounter + "' style='width:100%;height:30px;padding:2px;border:1px solid #ccc' /></div>";
                    htmlTabContainer += "<button type='button' class='removeTabBo btn btn-default' data-target='" + _define + "TTabDiv" + tabCounter + "'>x</button></div>";
                    htmlTabContainer += "<div class='form-group'>";
                    htmlTabContainer += "<label class='col-xs-2'>Select Icon</label>";
                    htmlTabContainer += "<div class='col-xs-8'><input type='text' id='" + _define + "tabIcon" + tabCounter + "' value='" + $Icon + "' class='iconpicker' style='width:100%;height:30px;padding:2px;border:1px solid #ccc'  /></div>";
                    htmlTabContainer += "</div>";
                    htmlTabContainer += "<div class='form-group'>";
                    htmlTabContainer += "<label class='col-xs-12'>Tab Description</label>";
                    htmlTabContainer += "<div class='col-xs-12'><textarea  id='" + _define + "ContentTab" + tabCounter + "' class='FullTextEditor' >" + $contentTab + "</textarea></div>";
                    htmlTabContainer += "</div>";
                    htmlTabContainer += "</div>";
                    document.getElementById(_define + 'tabs').insertAdjacentHTML('beforeend', htmlTabContainer);
                    TineMceLoadCMS();
                    $('.iconpicker').iconpicker();
                    tabCounter++;
                }
            });
        }

        TineMceLoadCMS();
        $('.removeTabBo').click(function() {
            $target = $(this).data('target');
            $('#' + $target).remove();
        });
        document.getElementById(_define + 'btn1').addEventListener("click", function() {
            var htmlTabContainer = "<div id='" + _define + "TTabDiv" + tabCounter + "' class='form-horizontal " + _define + "TTabDiv' style='margin-top:20px;border-bottom:1px solid #ccc;padding:5px;'  id='" + _define + "tab' class='form-group' data-id='" + tabCounter + "'>";
            htmlTabContainer += "<div class='form-group'>";
            htmlTabContainer += "<label class='col-xs-2'>Tab Title</label>";
            htmlTabContainer += "<div class='col-xs-8'><input type='text' data-id='" + tabCounter + "' class='" + _define + "tabTitle title' id='" + _define + "tabTitle" + tabCounter + "'style='width:100%;height:30px;padding:2px;border:1px solid #ccc' /></div>";
            htmlTabContainer += "<button class='removeTabBo btn btn-default' type='button'  data-target='" + _define + "TTabDiv" + tabCounter + "'>x</button></div>";
            htmlTabContainer += "<div class='form-group'>";
            htmlTabContainer += "<label class='col-xs-2'>Select Icon</label>";
            htmlTabContainer += "<div class='col-xs-8'><input type='text' id='" + _define + "tabIcon" + tabCounter + "' class='iconpicker' style='width:100%;height:30px;padding:2px;border:1px solid #ccc' /></div>";
            htmlTabContainer += "</div>";
            htmlTabContainer += "<div class='form-group'>";
            htmlTabContainer += "<label class='col-xs-12'>Tab Description</label>";
            htmlTabContainer += "<div class='col-xs-12'><textarea  id='" + _define + "ContentTab" + tabCounter + "' class='FullTextEditor' ></textarea></div>";
            htmlTabContainer += "</div>";
            htmlTabContainer += "</div>";
            document.getElementById(_define + 'tabs').insertAdjacentHTML('beforeend', htmlTabContainer);
            TineMceLoadCMS();
            $('.iconpicker').iconpicker();
            tabCounter++;
        });

    }
    editor.addButton('bootstraptabs', {
        text: "Tabs",
        tooltip: "Tabs",
        onclick: showDialog,
        stateSelector: 'div.TabsNoneEditable'
    });
});