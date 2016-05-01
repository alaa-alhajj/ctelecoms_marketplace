
function loadLibraryAll(name,value,type) {

    fildName = name;
var v=value+type;
    $.post('../../includes/File_Manager/index.php', {filedName: fildName,type:type,value:value}, function(data) {
      
        $('#sf_' + name).html(data);
    });
}