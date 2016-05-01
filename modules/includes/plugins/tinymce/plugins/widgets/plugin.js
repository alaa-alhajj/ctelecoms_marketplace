
tinymce.PluginManager.add('widgets', function(editor, url) {




    function showDialog() {

        var win;
        var selection = editor.selection;
        var selectedElm;


        function getWidgetsAjax() {
            var return_data = [{"text": "2", "value": "1"}];
            var xmlhttp = new XMLHttpRequest();
            var url2 = url + '../../../../../../views/ajax/getWidgetsForTinyMCE.php';

            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {

                    var myArr = JSON.parse(xmlhttp.responseText);
                    var out = [];
                    var i;
                    for (i = 0; i < myArr.length; i++) {
                        var outIn = {};
                        outIn["text"] = myArr[i].text;
                        outIn["value"] = myArr[i].value;
                        out.push(outIn);
                    }
                    return_data = myArr;
                    return return_data;

                }
            };
            xmlhttp.open("GET", url2, false);
            xmlhttp.send();
            return return_data;




        }
        var select_widget = {type: 'listbox',
            name: 'widget_select',
            label: 'Select Widget',
            values: getWidgetsAjax()
        };
        var write_condition = {type: 'textbox',
            name: 'widget_condition',
            label: 'Conditions'
        };
        selectedElm = selection.getNode();
        var widgetCode = selectedElm.innerText;
        var widget_id = widgetCode.match("##wid_id_start##(.*)##wid_id_end##");
        if (widget_id !== undefined && widget_id !== null) {
            
            select_widget.value=widget_id[1];

        }
        var cond = widgetCode.match("##wid_con_start##(.*)##wid_con_end##");
        if (cond !== undefined && cond !== null) {
            
            write_condition.value=cond[1];
        }


        win = editor.windowManager.open({
            autoScroll: true,
            width: 500,
            height: 130,
            top:10,
            title: 'Widgets',
            spacing: 20,
            padding: 10,
            body: [select_widget, write_condition],
            onsubmit: function(e) {
                if (e.data.widget_select !== undefined && e.data.widget_select !== "") {
                    var condition = "##wid_con_start##" + e.data.widget_condition + "##wid_con_end##";
                }
                var code_widget = "<span class='mceNonEditable'>##wid_start####wid_id_start##" + e.data.widget_select + "##wid_id_end##" + condition + "##wid_end##</span><p></p>";
                editor.insertContent(code_widget);
            }
        });
    }
    editor.addButton('widgets', {
        text: "Widgets",
        tooltip: "Widgets",
        onclick: showDialog,
        stateSelector: 'span[class="mceNonEditable"]'
    });
});