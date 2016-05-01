<?php
include("dbUtils_sec.php");
$menuGroups = array('Dashboard');

function imploded_fields($table, $field, $condition = Null) {
    if ($condition) {
        $condition = " WHERE $condition";
    }
    $sql = "SELECT * FROM $table  $condition";
    $result = MYSQL_QUERY($sql);
    $numberOfRows = MYSQL_NUMROWS($result);
    if ($numberOfRows > 0) {
        $i = 0;
        $fileds_arr = array();
        while ($i < $numberOfRows) {
            $field_val = MYSQL_RESULT($result, $i, $field);
            array_push($fileds_arr, $field_val);
            $i++;
        }
    }
    if ($fileds_arr) {
        return implode(',', $fileds_arr);
    } else {
        return 0;
    }
}

function get_system_message($def, $lang) {
    $query = "SELECT * FROM s_system_message WHERE define='$def' and lang='$lang'";
    $result = MYSQL_QUERY($query);
    $num = MYSQL_NUMROWS($result);
    $message = "";
    if ($num > 0) {
        $message = MYSQL_RESULT($result, 0, "details");
    }
    return $message;
}

function get_news_cats1($user, $p, $arr) {
    global $permissins;

    $per = $permissins[$p];
    $sql = "select type from subjects_permissions where `user`='$user' and `$per`=1 ";
    $res = mysql_query($sql);
    $rows = mysql_num_rows($res);
    while ($row = mysql_fetch_array($res)) {
        array_push($arr, $row['type']);
    }
    return $arr;
}

function get_client_lang($id_client) {
    $query = "SELECT * FROM s_client WHERE id=$id_client";
    $result = MYSQL_QUERY($query);
    $num = MYSQL_NUMROWS($result);
    $lang = "";
    if ($num > 0) {
        $lang = MYSQL_RESULT($result, 0, "lang");
    }
    return $lang;
}

function getMainPhoto($gallery, $width, $height, $photos_path, $thumb_prefex, $default_photo, $hasDefault) {
    $main_photo_id = 0;
    if ($gallery) {
        $main_photo_id = lookupField('galleries_galleries', 'id', 'main_photo', $gallery);
        if ($main_photo_id) {
            $main_photo = basename(lookupField('galleries_photos', 'id', 'photo', $main_photo_id));
            $thumb = basename(resizeToFile($photos_path . '/' . $main_photo, $width, $height, $photos_path . "/cash/$thumb_prefex" . $main_photo));
        }
    }
    if ($main_photo_id != 0) {
        if (file_exists("$photos_path/cash/$thumb")) {
            $src = "$photos_path/cash/$thumb";
        } else {
            $src = "$photos_path/$thumb";
        }
    } else {
        if ($hasDefault) {
            $src = $default_photo;
        } else {
            return false;
        }
    }
    return $src;
}

function returnImplode_ids($table, $condition, $imploded_field) {
    $condition = str_replace('where', '', $condition);
    $condition = str_replace('WHERE', '', $condition);
    $sql = "SELECT * FROM  $table  where $condition";
    $result = MYSQL_QUERY($sql);
    $numberOfRows = MYSQL_NUMROWS($result);

    $i = 0;
    $fields = '';
    while ($i < $numberOfRows) {
        $field = MYSQL_RESULT($result, $i, $imploded_field);
        if ($fields == '') {
            $fields = $field;
        } else {
            $fields .= ',' . $field;
        }
        $i++;
    }
    return $fields;
}

function get_current_path() {
    $out_of_chain_page = false;
    $file = getFileURI();
    $prev_file = getPrevFileURI();

    $current_item = getMenuId($file, $_SESSION['pageLang']);
    if (!$current_item) {

        $current_item = getMenuId($prev_file, $_SESSION['pageLang']);
        $out_of_chain_page = true;
    }
    $has_parent = lookupField('menus', 'menu_id', 'p_id', $current_item);
    $parent = lookupField('menus', 'menu_id', 'p_id', $current_item);

    echo '<a href="' . _PREF . '">' . home . '</a> ';

    if ($current_item) {
        if ($has_parent) {
            echo ' <span class="sep">></span> <a href="' . _PREF . lookupField('menus', 'menu_id', 'item_link', $parent) . '">' . lookupField('menus', 'menu_id', 'item_label', $parent) . '</a>';
            echo ' <span class="sep">></span> <a href="' . _PREF . lookupField('menus', 'menu_id', 'item_link', $current_item) . '">' . lookupField('menus', 'menu_id', 'item_label', $current_item) . '</a>';
        } else {
            echo ' <span class="sep">></span> <a href="' . _PREF . lookupField('menus', 'menu_id', 'item_link', $current_item) . '">' . lookupField('menus', 'menu_id', 'item_label', $current_item) . '</a>';
        }
    }
    if (!$current_item OR $out_of_chain_page) {
        $current_form = getCurrentFormName();
        $prev_form = getPrevFormName();
        $id = $_REQUEST['id'];
        if (!$id) {
            $id = $_REQUEST['gid'];
        }

        $current = get_current_form_label($current_form, $id);
        $prev = get_current_form_label($prev_form, $id);

        if (!$out_of_chain_page) {
            foreach ($prev as $label => $link) {
                echo '<span class="sep"> > </span><a href="' . $link . '">' . $label . '</a>';
            }
        }

        foreach ($current as $label => $link) {

            echo '<span class="sep"> > </span><a href="' . $link . '">' . $label . '</a>';
        }
    }
}

function get_current_form_label($current_form, $id) {

    switch ($current_form) {
        case ('viewForm'):
            return array(
                lookupField('query_forms', 'form_id', 'form_name', $id) => '#'
            );
            break;
        case ('contact'):
            return array(
                Contact => '#',
            );

            break;
        case ('viewFaqs'):
            return array(
                Faqs => '#',
            );
            break;
        case ('viewAllGalleries'):
            return array(
                photo_album => '#',
            );

            break;
        case ('viewGallery'):
            if ($_SESSION['pageLang'] == 'en') {
                $lang_prefex = '';
            } else {
                $lang_prefex = '_ar';
            }
            return array(
                Albums => _PREF . 'view/gallery/viewAllGalleries.php',
                lookupField('galleries_galleries', 'id', 'gallery_name' . $lang_prefex, $id) => '#'
            );

            break;
        case ('viewAllProducts'):
            return array(
                Products => '#',
            );
            break;
        case ('viewPlacements'):
            return array(
                student_page => _PREF . 'view/students/viewStudentPage.php',
                my_placements => '#'
            );
            break;

        case ('viewOldCourses'):
            return array(
                student_page => _PREF . 'view/students/viewStudentPage.php',
                old_courses => '#'
            );
            break;

        case ('viewAllCourses'):

            if ($_REQUEST['st']) {
                return array(
                    student_page => _PREF . 'view/students/viewStudentPage.php',
                    Courses => '#'
                );
            } else {
                return array(
                    Courses => '#'
                );
            }
            break;

        case ('viewStudentPage'):
            return array(
                student_page => '#'
            );
            break;

        case ('viewProduct'):
            return array(
                Books => _PREF . 'view/products/viewAllProducts.php',
                lookupField('ecom_products', 'pro_id', 'pro_name_' . $_SESSION['pageLang'], $id) => '#'
            );
            break;



        case ('viewNews'):
            return array(
                our_news => _PREF . 'view/news/viewAllNews.php',
                lookupField('news_news', 'id', 'subject', $id) => '#'
            );

            break;
        case ('viewAllNews'):
            return array(
                our_news => '#'
            );
            break;

        case ('viewServices'):
            return array(
                our_services => _PREF . 'view/services/viewAllServices.php',
                lookupField('serv_services', 'id', 'title', $id) => '#'
            );
            break;
        case ('viewAllServices'):
            return array(
                our_services => '#'
            );
            break;



        case ('viewArticles'):
            return array(
                our_articles => _PREF . 'view/articles/viewAllArticles.php',
                lookupField('articles_articles', 'id', 'subject', $id) => '#'
            );
            break;
        case ('viewAllArticles'):
            return array(
                our_articles => '#'
            );
            break;

        case ('viewCart'):
            return array(
                Books => _PREF . 'view/products/viewAllProducts.php',
                Shopping_Cart => '#'
            );
            break;

        case ('viewMaterials'):
            return array(
                Courses => _PREF . 'view/courses/viewAllCourses.php',
                Materials => '#'
            );
            break;

        case ('viewCourseDetails'):
            return array(
                Courses => _PREF . 'view/courses/viewAllCourses.php',
                course_details => '#'
            );
            break;

        case ('failed'):
            return array(
                login_failed => '#'
            );
            break;

        case ('viewCv'):
            return array(
                my_cv => '#'
            );

            break;

        case ('CareerApplication'):
            $id = $_REQUEST['car_id'];
            return array(
                our_careers => _PREF . 'view/careers/viewAllCareers.php',
                lookupField('careers', 'id', 'title', $id) => _PREF . 'view/careers/viewAllCareers.php',
                careerApplication => '#'
            );

            break;
        case ('viewCareer'):
            $id = $_REQUEST['car_id'];
            return array(
                our_careers => _PREF . 'view/careers/viewAllCareers.php',
                clookupField('careers', 'id', 'title', $id) => '#'
            );
            break;
        case ('viewAllCareers'):
            return array(
                our_careers => '#'
            );

            break;

        case ('viewSitemap'):
            return array(
                Sitemap => '#'
            );
            break;
        case ('viewSearch'):
            return array(
                search_result => '#'
            );

            break;

        case ('shipping_info'):
            return array(
                Cart => _PREF . 'view/products/viewCart.php',
                Shipping_information => '#'
            );
            break;

        case ('login'):
            return array(
                Login => '#'
            );
            break;

        case ('register'):
            return array(
                Registration_Form => '#'
            );
            break;

        case ('page'):
            $id = $_REQUEST['id'];
            $title = lookupField('pages', 'id', 'title', $id);
            return array(
                $title => '#'
            );
            break;
    }
}

function getMagOrder($mm) {
    $sql = "SELECT MAX(ord) AS max FROM magz_pages where magz_id='$mm'";
    $result = mysql_query($sql);
    if ($result)
        return $max = MYSQL_RESULT($result, 0, 'max') + 1;
    else
        return 1;
}

/**
 * Exports table specific data to excel file according to some condition   
 * 
 * @param string $table table from which the data will be exported
 * @param string $fields (Optional) A comma separated list of fields to export from the specified table
 * @param string $condition (Optional) Free condition according to which data will be retrieved
 * @param string $orderBy (Optional) A comma separated list of fields to order exported data according to it
 * @param string $outputFilename (Optional) The name of the excel file to be exported 
 */
function exportDataToExcel($table, $fields = "*", $condition = "", $orderBy = "", $outputFilename = "default.xls") {
    $sql = "SELECT $fields FROM `$table` ";
    $sql.=($condition != "") ? " WHERE " . $condition : "";
    $sql.=($orderBy != "") ? " ORDER BY " . $orderBy : "";
    if (exportSqlToExcel($sql, $outputFilename) == false) {
        return false;
    }
}

/**
 * Exports the result of the specified SQL query
 * 
 * @param string $sql the SQL statement according to which the data will be exported
 * @param string $outputFilename (Optional) The name of the excel file to be exported 
 */
function exportSqlToExcel($sql, $outputFilename = "default.xls") {
    $result = mysql_query($sql);
    $numRows = mysql_num_rows($result);
    $excelArray = array();
    if ($numRows > 0) {
        $i = 0;
        $headerRow = array();
        while ($i < mysql_num_fields($result)) {
            $meta = mysql_fetch_field($result, $i);
            $headerRow[] = $meta->name;
            $i++;
        }
        $excelArray[] = $headerRow;
        $i = 0;
        while ($i < $numRows) {
            $arr = array();
            $arr = mysql_fetch_array($result, MYSQL_NUM);
            $excelArray[] = $arr;
            $i++;
        }
        exportArrayToExcel($excelArray, $outputFilename);
    } else {
        return false;
    }
}

/**
 * Exports the passed array to an excel file
 * 
 * @param Array $arr a 2-D array to export to excel
 * @param string $outputFilename (Optional) The name of the excel file to be exported
 */
function exportArrayToExcel($arr, $outputFilename = "default.xls") {
    if (countDimensions($arr) < 2) {
        return false;
    }
    $rows = count($arr);
    $excelFileContents = "";
    for ($i = 0; $i < $rows; $i++) {
        $cols = count($arr[$i]);
        for ($j = 0; $j < $cols; $j++) {
            $excelFileContents.=$arr[$i][$j] . " \t ";
        }
        $excelFileContents.="\n";
    }
    // Send Header
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header('Content-type: application/ms-excel');
    header("Content-Type: application/force-download");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/download");
    header('Content-Disposition: attachment; filename=' . $outputFilename);
    header("Content-Transfer-Encoding: binary ");
    echo $excelFileContents;
}

/**
 * Counts number of dimensions in an array
 * 
 * @param Array $array the array to count its number of dimensions
 */
function countDimensions($array) {
    if (is_array(reset($array))) {
        $return = countDimensions(reset($array)) + 1;
    } else {
        $return = 1;
    }
    return $return;
}

/**
 * Get successors of a category
 * 
 * @param Number $cat_id The category to get its successors
 * @param Boolean (Optional) $withMe Determine wither to append $cat_id to the result or not. Default is true
 * @return string Coma separated list of category id's
 */
function getCatSuccessor($cat_id, $withMe = true) {
    $Successors = ($withMe) ? " '" . $cat_id . "' " : " ";
    if (isLeaf($cat_id)) {
        return $Successors;
    }
    $sql = "SELECT `cat_id` FROM `products_categories` WHERE `parent_cat`=$cat_id";
    $result = mysql_query($sql);
    $numRows = mysql_num_rows($result);
    $i = 0;
    while ($i < $numRows) {
        $son = mysql_result($result, $i, "cat_id");
        $Successors.=", " . getCatSuccessor($son) . "";
        $i++;
    }
    return $Successors;
}

/**
 * Check if a category is leaf or not
 * 
 * @param Number $cat_id the category to check if it is a leaf
 */
function isLeaf($cat_id) {
    $sql = "SELECT * FROM `products_categories` WHERE `parent_cat`='$cat_id';";
    $result = mysql_query($sql);
    $numRows = mysql_num_rows($result);
    if ($numRows > 0) {
        return false;
    } else {
        return true;
    }
}

/**
 * Gets the parent of the given category
 * 
 * @param Number $cat_id the category to get its parent
 */
function getCatParent($cat_id) {
    return lookupField("products_categories", "cat_id", "parent_cat", $cat_id);
}

//Get current page URL 
function getPageURL() {
    $pageURL = 'http';
    if ($_SERVER["HTTPS"] == "on") {
        $pageURL .= "s";
    }
    $pageURL .= "://";
    if ($_SERVER["SERVER_PORT"] != "80") {
        $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
    } else {
        $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
    }
    return $pageURL;
}

//To get the URL of the current page without language parameter :
function getFileURIWithoutLang() {
    //Form the URL
    $currentPageURL = getPageURL();
    //echo $currentPageURL."<br/>";
    //remove language parameters (for users pages)
    $currentPageURL = filterURL($currentPageURL, "pageLang");
    //echo $currentPageURL."<br/>";
    //remove language parameters (for admins pages)
    $currentPageURL = filterURL($currentPageURL, "lang");
    //echo $currentPageURL."<br/>";
    $currentPageURL = trim($currentPageURL, "&");
    //echo $currentPageURL."<br/>";
    //If last char is not ? return $currentPageURL
    //else : If the URI has ? then append & to it 
    //		 else append ?
    if ($currentPageURL[strlen($currentPageURL) - 1] != '?') {
        if (strstr($currentPageURL, '?')) {
            $currentPageURL .= "&";
        } else {
            $currentPageURL .= "?";
        }
    }
    return $currentPageURL;
}

function limit($text, $limit) {
    if (strlen($text) > $limit) {
        $to = strpos($text, " ", $limit);
        if ($to !== false) {
            $text = substr($text, 0, $to);
        }
        $text.= ' ....';
    }
    return $text;
}

function ShowGallery_pretty_photo($gid, $show_icon = Null) {
    global $HP;
    if ($HP == 1) {
        $Pref = "";
    } else {
        $Pref = "../";
    }
    $res = "";
    $sql2 = "SELECT * FROM galleries_galleries  where  id=$gid ";
    $result2 = MYSQL_QUERY($sql2);
    $numberOfRows2 = MYSQL_NUMROWS($result2);
    if ($numberOfRows2 > 0) {

        $photos = MYSQL_RESULT($result2, 0, "photos");
        $main_photo = MYSQL_RESULT($result2, 0, "main_photo");
        $sqlG = "SELECT * FROM galleries_photos  where  id in ($photos) ";
        $resultG = MYSQL_QUERY($sqlG);
        $numberOfRowsG = MYSQL_NUMROWS($resultG);
        if ($numberOfRowsG > 0) {
            $res.="<div class='slideshow-content'>
				<ul style ='list-style:none; display:inline;' id='gallery' class='gallery clearfix cer'>";
            $pxx = 1;
            $g = 0;
            while ($numberOfRowsG > $g) {
                $PHid = MYSQL_RESULT($resultG, $g, "id");
                $Photo = MYSQL_RESULT($resultG, $g, "photo");
                $photo2 = _PREF . "forms/uploads/cash/gal1_" . $Photo;
                /* CropSquare($Pref."uploads/".$Photo,100,$Pref."uploads/cash/gal1_".$Photo); */
                if ($show_icon == true) {
                    if ($PHid == $main_photo) {
                        /* $res.='<li style ="display:inline;"><a  href='._PREF.'forms/uploads/'.$Photo.'" id="thumb_'.$PHid.'" rel=prettyPhoto[gallery1]" >';
                          $res.='<img src='._PREF.'forms/uploads/'.$Photo.'" border="0" class="thumb">&nbsp;See full size</a></li>'; */

                        $res.='<li style ="display:inline;"><a class="startGal" href="' . _PREF . 'forms/uploads/' . $Photo . '" id="thumb_' . $PHid . '" rel=prettyPhoto[gallery1]" >';
                        $res.='<img src="' . _PREF . 'theme/images/see_all.png" border="0" class="thumb">&nbsp;See full size</a></li>' . PHP_EOL;
                    } else {
                        $res.='<li style ="display:none;"><a  href="' . _PREF . 'forms/uploads/' . $Photo . '" id="thumb_' . $PHid . '" rel=prettyPhoto[gallery1]" >';
                        $res.='<img src="' . $photo2 . '" border="0" class="thumb"></a></li>';
                    }
                } else {
                    $res.='<li style ="display:inline;"><a  href="' . _PREF . 'forms/uploads/' . $Photo . '" id="thumb_' . $PHid . '" rel=prettyPhoto[gallery1]" >';
                    $res.='<img src="' . $photo2 . '" border="0" class="thumb"></a></li>';
                }

                $g++;
            }
            $res.="</ul>
				</div>";
        }
    }

    return $res . "&nbsp;";
}

// Get the filename with parameters
function getFileURI() {
    $file = substr(strrchr($_SERVER['REQUEST_URI'], "/"), 1);
    return $file;
}

function getPrevFileURI() {
    $file = substr(strrchr($_SERVER['HTTP_REFERER'], "/"), 1);
    return $file;
}

// we didn't use this method alone but we  call it in getNewId method
//  
function getColumnMax($table, $column) {
    $sql_max = "SELECT MAX($column) AS 'max' FROM $table";
    $result_max = mysql_query($sql_max);
    checkError($result_max, $sql_max);
    $rows_max = mysql_num_rows($result_max);
    if ($rows_max == null || $rows_max = 0) {
        return 0;
    } else {
        return mysql_result($result_max, 0, "max");
    }
}

// this method will get the maximum number of this &coloumn  from this $table
// getNewId("agencies","id");

function getNewId($table, $coloumn) {
    $id = 0;
    $res = getColumnMax($table, $coloumn);
    if (($res > 0) || ($res == 0)) {
        $id = $res + 1;
    }
    return $id;
}

//----------------------------
//We use this method to get the order for the last item inserted
function getOrder($table, $field = null, $cond = null) {
    if ($cond)
        $cond = "WHERE " . $cond;
    if (!$field)
        $field = 'item_order';
    $sql = "SELECT MAX($field) AS max FROM $table $cond";
    $result = mysql_query($sql);
    if ($result)
        return $max = MYSQL_RESULT($result, 0, 'max') + 1;
    else
        return 1;
}

//---------------------------
//this method  returns the first $n words from this string $str
// NOTICE:  YO NEED INSTALL  4.4.0
function strToWords($str) {
    $word = "";
    $r = 0;
    $index = 0;
    $res_array;
    while ($r < strlen($str)) {
        $chr = substr($str, $r, 1);
        if ($chr != " ")
            $word = $word . $chr;
        elseif ($chr == " ") {
            $res_array[$index] = $word;
            $index = $index + 1;
            $word = "";
        }
        $r = $r + 1;
    }
    $res_array[$index] = $word;
    return $res_array;
}

function getNwords($str, $n) {
    $a = strToWords($str);
    $result = "";
    $i = 0;
    $wordsCount = $a . length;
    if ($n < $wordsCount)
        return $str;
    else {
        while ($i < $n) {
            $result = $result . " " . $a[$i];
            $i++;
        }
        return $result;
    }
}

/**
 * 
 * Get count of rows in the selected table
 * @param String $table the table of which the count of rows is required
 * @param String $condition free condition to get count on specific rows on the selected table
 */
function getObjectsCount($table, $condition = "1>0") {
    $sql = "SELECT COUNT(*) AS co FROM `$table` WHERE $condition";
    $resultCount = MYSQL_QUERY($sql);
    $rowsCount = MYSQL_NUM_ROWS($resultCount);
    if ($rowsCount == null || $rowsCount = 0) {
        return null;
    } else {
        $count = MYSQL_RESULT($resultCount, 0, "co");
        return trim($count);
    }
}

/**
 * 
 * Get maximum value of selected field in selected table
 * @param String $table the table of which the count of rows is required
 * @param Strin $field the field to get its maximum value
 * @param String $condition free condition to get maximum of selected field on specific rows of the selected table
 */
function getObjectsMax($table, $field, $condition = "1>0") {
    $sql = "SELECT MAX($field) AS max FROM `$table` WHERE $condition";
    $resultMax = MYSQL_QUERY($sql);
    $rowsMax = MYSQL_NUM_ROWS($resultMax);
    if ($rowsMax == null || $rowsMax = 0) {
        return null;
    } else {
        $Max = MYSQL_RESULT($resultMax, 0, "max");
        return trim($Max);
    }
}

///------------------------------------------------
function lookupField($table, $id_field, $lookup_field, $id_value) {
    $sql_lookup = "SELECT `$lookup_field` from `$table` where `$id_field` = '$id_value'";
    $result_lookup = MYSQL_QUERY($sql_lookup);
    checkError($result_lookup, $sql_lookup);
    $rows_lookup = MYSQL_NUM_ROWS($result_lookup);
    if ($rows_lookup == null || $rows_lookup = 0) {
        return "";
    } else {
        $filds = explode(",", $lookup_field);
        $ind = 0;
        $value = "";
        while ($ind < count($filds)) {
            $value.= stripslashes(MYSQL_RESULT($result_lookup, 0, $filds[$ind]) . " ");
            $ind++;
        }
        return trim($value);
    }
}

function lookupFieldFiltered($table, $id_field, $lookup_field, $id_value, $condition, $no_idValue) {
    if ($no_idValue == true) {
        $sql_lookup = "SELECT $lookup_field from $table where $condition";
    } else {
        $sql_lookup = "SELECT $lookup_field from $table where ($id_field =' $id_value ') and $condition";
    }
    $result_lookup = MYSQL_QUERY($sql_lookup);
    checkError($result_lookup, $sql_lookup);
    $rows_lookup = MYSQL_NUM_ROWS($result_lookup);
    if ($rows_lookup == null || $rows_lookup = 0) {
        return 0;
    } else {
        return MYSQL_RESULT($result_lookup, $lookup_field);
    }
}

//----------------------------------------------------------------------
function createCheckbox($table, $id_field, $value_field, $ids_values, $field_name, $condition) {
    $sql_check = "SELECT * from $table ";
    if ($condition != null && $condition != "")
        $sql_check .= " WHERE $condition ";
    $sql_check .= " ORDER BY $id_field";
    $result_check = MYSQL_QUERY($sql_check);
    $rows_check = MYSQL_NUM_ROWS($result_check);

    $selected_ids = explode(',', $ids_values);
    if ($rows_check) {
        $i = 0;
        while ($i < $rows_check) {
            if ($i % 3 == 0) {
                echo "<br/>";
            }
            $id = MYSQL_RESULT($result_check, $i, $id_field);
            $field = MYSQL_RESULT($result_check, $i, $value_field);
            $checkboxs .= "&nbsp; $field <input name='" . $field_name . "[]' type='checkbox' value='$id' ";
            if (in_array($id, $selected_ids)) {
                $checkboxs .= " checked='checked' />";
            } else {
                $checkboxs .= "/>";
            }
            $i++;
        }
    }
    return $checkboxs;
}

//----------------------------------------------------------------------
function createComboBoxFiltered($table, $id_field, $value_field, $id_value, $field_name, $condition, $required = "") {
    $result = "<select name=\"$field_name\" id=\"$field_name\" class=\"$required\">\n";
    if ($required != 'required') {
        $result .= "<option></option>\n";
    }
    if ($table == 'college') {
        $result .= "<option value='0'>" . University . "</option>\n";
    }
    $sql_combo = "SELECT $id_field , $value_field from $table ";
    if ($condition != null && $condition != "")
        $sql_combo .= " WHERE $condition ";
    $sql_combo .= " ORDER BY $value_field";
    $result_combo = MYSQL_QUERY($sql_combo);
    checkError($result_combo, $sql_combo);
    $rows_combo = MYSQL_NUM_ROWS($result_combo);
    $i = 0;
    while ($i < $rows_combo) {
        $id = MYSQL_RESULT($result_combo, $i, $id_field);
        $filds = explode(",", $value_field);
        $ind = 0;
        $value = "";
        while ($ind < count($filds)) {
            $value.= MYSQL_RESULT($result_combo, $i, $filds[$ind]) . " ";
            $ind++;
        }
        $result .= "<option ";
        if ($id_value != null && $id_value != "" && $id_value == $id)
            $result .= "selected ";
        $result .= "value=\"$id\">" . stripslashes($value) . "</option>\n";
        $i++;
    }
    $result .= "</select>\n";
    return $result;
}

function createComboBoxFilteredNested($table, $id_field, $value_field, $id_value, $field_name, $condition, $required = "") {
    $result = "<select name=\"$field_name\" id=\"$field_name\" class=\"$required\">\n";
    if ($required != 'required') {
        $result .= "<option></option>\n";
    }
    $sql_combo = "SELECT $id_field , $value_field from $table ";
    if ($condition != null && $condition != "")
        $sql_combo .= " WHERE $condition ";
    $sql_combo .= " ORDER BY $value_field";
    $result_combo = MYSQL_QUERY($sql_combo);
    checkError($result_combo, $sql_combo);
    $rows_combo = MYSQL_NUM_ROWS($result_combo);
    $i = 0;
    while ($i < $rows_combo) {
        $id = MYSQL_RESULT($result_combo, $i, $id_field);
        $filds = explode(",", $value_field);
        $ind = 0;
        $value = "";
        while ($ind < count($filds)) {
            $value.= MYSQL_RESULT($result_combo, $i, $filds[$ind]) . " ";
            $ind++;
        }
        $result .= "<option ";
        if ($id_value != null && $id_value != "" && $id_value == $id)
            $result .= "selected ";

        $result .= "value=\"$id\">" . stripslashes($value) . "</option>\n";
        $i++;
    }
    $result .= "</select>\n";
    return $result;
}

//----------------------------------------------------------------------
function createComboBoxFilteredFromArray($array, $field_name) {
    $result = "<select name=\"$field_name\" id=\"$field_name\" >\n";
    for ($i = 0; $i < count($array); $i++) {
        $result .= '<option ';
        if ($i == $_REQUEST[$field_name])
            $result .= ' selected ';
        if ($i == 0)
            $i = '';
        $result .= 'value="' . $i . '">' . $array[$i] . '</option>';
    }
    $result .= "</select>\n";
    return $result;
}

function createComboBoxFromArray($array, $sel_name, $value, $ddAttrbit = '') {
    $result = count($array) . "<select name=\"$sel_name\" id=\"$sel_name\" " . $ddAttrbit . " >\n";
    for ($i = 0; $i < count($array); $i++) {
        $result .= '<option ';
        if ($i == $value)
            $result .= ' selected ';
        $result .= 'value="' . $i . '">' . $array[$i] . '</option>';
    }
    $result .= "</select>\n";
    return $result;
}

function ComboBoxFromArraySameValue($array, $sel_name, $value) {
    $result = "<select name=\"$sel_name\" id=\"$sel_name\" >\n";
    for ($i = 0; $i < count($array); $i++) {
        $result .= '<option ';
        if ($array[$i] == $value)
            $result .= ' selected ';
        $result .= 'value="' . $array[$i] . '">' . $array[$i] . '</option>';
    }
    $result .= "</select>\n";
    return $result;
}

function createComboBox($table, $id_field, $value_field, $id_value, $field_name, $required = "", $actions = "", $cond = "", $isUniversity = 0) {
    $result = '<select name="' . $field_name . '" id="' . $field_name . '" class="' . $required . '" ' . $actions . ' >\n';

    if ($required == '') {
        $result .= "<option></option>\n";
    }
    if ($isUniversity == 1) {
        $result .= "<option value='0'>" . University . "</option>\n";
    }
    $sql_combo = "SELECT $id_field , $value_field from $table $cond ORDER BY $value_field";

    $result_combo = MYSQL_QUERY($sql_combo);
    checkError($result_combo, $sql_combo);
    $rows_combo = MYSQL_NUM_ROWS($result_combo);
    $i = 0;
    while ($i < $rows_combo) {
        $id = MYSQL_RESULT($result_combo, $i, $id_field);
        $filds = explode(",", $value_field);
        $ind = 0;
        $value = "";
        while ($ind < count($filds)) {
            $value.= MYSQL_RESULT($result_combo, $i, $filds[$ind]) . " ";
            $ind++;
        }
        $result .= "<option ";
        if ($id_value != null && $id_value != "" && $id_value == $id)
            $result .= "selected ";
        $result .= "value=\"$id\">" . stripslashes($value) . "</option>\n";
        $i++;
    }
    $result .= "</select>\n";
    return $result;
}

function createComboBoxNew($table, $id_field, $value_field, $id_value, $field_name, $textField, $condition) {
    $result = "<select name=\"$field_name\" onChange=\"if(this.options[this.selectedIndex].value=='other') $textField.style.display='block'; else  $textField.style.display='none'; \" >\n";
    if ($id_value == null || $id_value == "")
        $result .= "<option></option>\n";
    $sql_combo = "SELECT $id_field , $value_field from $table ";
    if ($condition != null && $condition != "")
        $sql_combo .= " WHERE $condition GROUP BY $value_field";
    $sql_combo .= " ORDER BY $value_field";
    $result_combo = MYSQL_QUERY($sql_combo);
    checkError($result_combo, $sql_combo);
    $rows_combo = MYSQL_NUM_ROWS($result_combo);
    $i = 0;
    while ($i < $rows_combo) {
        $id = MYSQL_RESULT($result_combo, $i, $id_field);
        $filds = explode(",", $value_field);
        $ind = 0;
        $value = "";
        while ($ind < count($filds)) {
            $value.= MYSQL_RESULT($result_combo, $i, $filds[$ind]) . " ";
            $ind++;
        }
        $result .= "<option ";
        if ($id_value != null && $id_value != "" && $id_value == $id)
            $result .= "selected ";
        $result .= "value=\"$id\">" . stripslashes($value) . "</option>\n";
        $i++;
    }
    $result.= "<option value=\"other\">--</option>";
    $result.= "</select>\n";
    return $result;
}

function createComboBoxValue($table, $id_field, $value_field, $id_value, $field_name) {
    $result = "<select name=\"$field_name\" >\n";
    if ($id_value == null || $id_value == "")
        $result .= "<option></option>\n";
    $sql_combo = "SELECT $id_field , $value_field from $table ORDER BY $value_field";
    $result_combo = MYSQL_QUERY($sql_combo);
    checkError($result_combo, $sql_combo);
    $rows_combo = MYSQL_NUM_ROWS($result_combo);
    $i = 0;
    while ($i < $rows_combo) {
        $id = MYSQL_RESULT($result_combo, $i, $id_field);
        $value = MYSQL_RESULT($result_combo, $i, $value_field);
        $result .= "<option ";
        if ($id_value != null && $id_value != "" && $id_value == $id)
            $result .= "selected ";
        $result .= "value=\"$value\">" . stripslashes($value) . "</option>\n";
        $i++;
    }
    $result .= "</select>\n";
    return $result;
}

function createComboBoxScript($table, $id_field, $value_field, $id_value, $field_name, $condition, $required = "", $scriptFunc, $first_text = null) {
    $result = "<select name=\"$field_name\" class=\"$required\" onChange=\"$scriptFunc\">\n";
    //if ($id_value == null || $id_value == "") 
    if ($first_text) {
        $result .= "<option value='nothing'>$first_text</option>\n";
    } else {
        $result .= "<option value='nothing'>-------</option>\n";
    }

    $sql_combo = "SELECT $id_field , $value_field from $table";
    if ($condition != null && $condition != "")
        $sql_combo .= " WHERE $condition ";
    $sql_combo .= " ORDER BY $value_field";
    $result_combo = MYSQL_QUERY($sql_combo);
    checkError($result_combo, $sql_combo);
    $rows_combo = MYSQL_NUM_ROWS($result_combo);
    $i = 0;

    while ($i < $rows_combo) {
        $id = MYSQL_RESULT($result_combo, $i, $id_field);
        $value = MYSQL_RESULT($result_combo, $i, $value_field);
        $result .= "<option ";
        if ($id_value != null && $id_value != "" && $id_value == $id)
            $result .= "selected ";
        $result .= "value=\"$id\">" . stripslashes($value) . "</option>\n";
        $i++;
    }
    $result .= "</select>\n";
    return $result;
}

function createComboUlBoxScript($table, $id_field, $value_field, $id_value, $field_name, $condition, $html_id = "", $ajax_loader, $load_pros = null) {


    $result = '<div class="dropdown"  onclick="hide_list(); show_dropdown(' . $html_id . ')" >';
    if ($html_id == 1) {
        $result .='<input class="dd_text dd_text' . $html_id . '"  type="text" value="' . all_categories . '"    readonly=""  />';
    }
    if ($html_id == 2) {
        $result .='<input class="dd_text dd_text' . $html_id . '"  type="text" value="' . all_brands . '"    readonly=""  />';
    }

    if ($id_value) {
        $result .= '<input class="dd_val ' . $field_name . ' field' . $html_id . '" name="' . $field_name . '" type="hidden" value="' . $id_value . '"    readonly=""  />';
    } else {
        $result .= '<input class="dd_val ' . $field_name . ' field' . $html_id . '" name="' . $field_name . '" type="hidden" value="nothing"    readonly=""  />';
    }


    $result .= '<div class="dd_list dd_list' . $html_id . '" onmousemove="show_dropdown(' . $html_id . ')" onmouseout="hide_list()" >
					<div class="ul">';

    if ($id_value == null || $id_value == "") {
        if ($html_id == 1) {
            $result .="<div class='li'><a onclick='setText(\"$html_id\",\"" . all_categories . "\",\"nothing\");'>" . all_categories . "</a></div>";
        }
        if ($html_id == 2) {
            $result .="<div class='li'><a onclick='setText(\"$html_id\",\"" . all_brands . "\",\"nothing\");'>" . all_brands . "</a></div>";
        }
    }

    $sql_combo = "SELECT $id_field , $value_field from $table";
    if ($condition != null && $condition != "")
        $sql_combo .= " WHERE $condition ";
    $sql_combo .= " ORDER BY $value_field";
    $result_combo = MYSQL_QUERY($sql_combo);
    checkError($result_combo, $sql_combo);
    $rows_combo = MYSQL_NUM_ROWS($result_combo);
    $i = 0;

    while ($i < $rows_combo) {
        $id = MYSQL_RESULT($result_combo, $i, $id_field);
        $value = MYSQL_RESULT($result_combo, $i, $value_field);

        //if ($id_value != null && $id_value != "" && $id_value == $id) 
        //$selected_value = $value;

        if ($ajax_loader) {
            $result .="<div class='li'><a onclick='loadModels(\"$id\",\"\",true); setText(\"$html_id\",\"$value\",\"$id\");";
            if ($load_pros) {
                $result.=" loadPros(); ";
            }
            $result.="'>$value</a></div>";
        } else {
            $result .="<div class='li'><a onclick='";
            $result .=" setText(\"$html_id\",\"$value\",\"$id\");";
            if ($load_pros) {
                $result.=" loadPros(); ";
            }
            $result.="'>$value</a></div>";
        }

        $i++;
    }
    //$result .= "</select>\n";
    $result .='</div></div></div>';
    return $result;
}

//-------------------------------------------------------------------------------------------------------
//Gallery Section

function adminGetGPhoto($gid, $w = 100, $h = 100, $al = "") {
    global $HP;
    if ($HP == 1) {
        $Pref = "forms/";
    } else {
        $Pref = "../";
    }
    $res = "&nbsp;";
    if ($gid != 0) {
        $sql2 = "SELECT * FROM galleries_galleries g , galleries_photos p where g.id =$gid and g.main_photo=p.id ";
        $result2 = MYSQL_QUERY($sql2);
        $numberOfRows2 = MYSQL_NUMROWS($result2);
        if ($numberOfRows2 > 0) {
            $photo = MYSQL_RESULT($result2, 0, "p.photo");
            $g_name = MYSQL_RESULT($result2, 0, "g.gallery_name");
            if (file_exists($Pref . 'uploads/' . $photo) && $photo != '') {
                $photo2 = Crop($Pref . "uploads/" . $photo, $w, $h, $Pref . "uploads/cash/c" . $w . "_" . $photo);
                $res = '<img src="' . $photo2 . '" align="' . $al . '" border="0">';
            }
        }
    }
    return $res;
}

function Crop($img, $w, $h, $newfilename, $rewrite = '') {
    if (file_exists($newfilename)) {
        if ($rewrite !== '') {
            return $rewrite;
        } else {
            return $newfilename;
        }
    } else {
        $strY = 0;
        $strX = 0;
        //Check if GD extension is loaded
        if (!extension_loaded('gd') && !extension_loaded('gd2')) {
            trigger_error("GD is not loaded", E_USER_WARNING);
            return false;
        }
        //Get Image size info
        $imgInfo = getimagesize($img);
        $nHeight = $imgInfo[1];
        $nWidth = $imgInfo[0];
        switch ($imgInfo[2]) {
            case 1: $im = imagecreatefromgif($img);
                break;
            case 2: $im = imagecreatefromjpeg($img);
                break;
            case 3: $im = imagecreatefrompng($img);
                break;
            default: trigger_error('Unsupported filetype!', E_USER_WARNING);
                break;
        }
        //If image dimension is smaller, do not resize

        if ($imgInfo[0] <= $w && $imgInfo[1] <= $h) {
            $nHeight = $imgInfo[1];
            $nWidth = $imgInfo[0];
            if ($rewrite !== '') {
                if (copy($img, $newfilename)) {
                    return$rewrite;
                }
            } else {
                return $img;
            }
        } else {
            //yeah, resize it, but keep it proportional
            if ($nWidth / $w < $nHeight / $h) {
                $ww = $w;
                $hh = ($nHeight * $w) / $nWidth;
                $strY = ($hh - $h) / 2;
                $side2 = ($nWidth * $h) / $w;
                $side = $nWidth;
            } else {
                $hh = $h;
                $ww = ($nWidth * $h) / $nHeight;
                $strX = ($ww - $w) / 2;
                $side = ($nHeight * $w) / $h;
                $side2 = $nHeight;
            }
        }
        $newImg = imagecreatetruecolor($w, $h);
        /* Check if this image is PNG or GIF, then set if Transparent */
        if (($imgInfo[2] == 1) OR ($imgInfo[2] == 3)) {
            imagealphablending($newImg, false);
            imagesavealpha($newImg, true);
            $transparent = imagecolorallocatealpha($newImg, 255, 255, 255, 127);
            imagefilledrectangle($newImg, 0, 0, $w, $h, $transparent);
        }
        imagecopyresampled($newImg, $im, 0, 0, $strX, $strY, $w, $h, $side, $side2);
        //Generate the file, and rename it to $newfilename
        switch ($imgInfo[2]) {
            case 1: imagegif($newImg, $newfilename);
                break;
            case 2: imagejpeg($newImg, $newfilename, 100);
                break;
            case 3: imagepng($newImg, $newfilename);
                break;
            default: trigger_error('Failed resize image!', E_USER_WARNING);
                break;
        }
        if ($rewrite !== '') {
            return $rewrite;
        } else {
            return $newfilename;
        }
    }
}

function checkDir($file) {
    $file;
    $d = explode('/', $file);
    $dir = $d[(count($d) - 2)];
    $d1 = explode($dir, $file);
    $F_dir = $d1[0] . $dir;
    if (!file_exists($F_dir)) {
        mkdir($F_dir . "/", 0777);
    }
}

function galleryComboBox($id_value, $field_name, $required = "", $src = "") {
    $result = "<select name=\"$field_name\" id=\"$field_name\" class=\"$required\" onChange=\"if(this.options[this.selectedIndex].value=='new') winopen('../includes/gallery/enterGallery.php?src=$src',850,480) \">\n";
    $result .= "<option></option>\n";
    $sql_combo = "SELECT id , gallery_name_en from galleries_galleries ORDER BY gallery_name_en";
    $result_combo = MYSQL_QUERY($sql_combo);
    checkError($result_combo, $sql_combo);
    $rows_combo = MYSQL_NUM_ROWS($result_combo);
    $i = 0;
    while ($i < $rows_combo) {
        $id = MYSQL_RESULT($result_combo, $i, 'id');
        $filds = explode(",", $value_field);
        $ind = 0;
        $value = "";
        while ($ind < count($filds)) {
            $value.= MYSQL_RESULT($result_combo, $i, 'gallery_name_en') . " ";
            $ind++;
        }
        $result .= "<option ";
        if ($id_value != null && $id_value != "" && $id_value == $id)
            $result .= "selected ";
        $result .= "value=\"$id\">" . stripslashes($value) . "</option>\n";
        $i++;
    }
    $result .="<option id=\"newGal\" value=\"new\">--New Galley--</option>\n";
    $result .= "</select>\n";
    return $result;
}

function lookupGallery($id_value) {
    $sql_lookup = "SELECT thumb from galleries_galleries g, galleries_photos p where g.id = '$id_value' AND p.id = g.main_photo";
    $result_lookup = MYSQL_QUERY($sql_lookup);
    checkError($result_lookup, $sql_lookup);
    $rows_lookup = MYSQL_NUM_ROWS($result_lookup);
    if ($rows_lookup == null || $rows_lookup == 0) {
        return 0;
    } else {
        $value.= MYSQL_RESULT($result_lookup, 0, 'thumb') . " ";
        return "<img src=\"" . _PREF . "uploads/$value\" border=\"0\" align=\"top\" /> ";
    }
}

function lookupGalleryUI($id_value, $uploadPath = "../uploads/") {
    $sql_lookup = "SELECT photo from galleries_galleries g, galleries_photos p where g.id = $id_value AND p.id = g.main_photo";
    $result_lookup = MYSQL_QUERY($sql_lookup);
    checkError($result_lookup, $sql_lookup);
    $rows_lookup = MYSQL_NUM_ROWS($result_lookup);
    if ($rows_lookup == null || $rows_lookup == 0) {
        return 0;
    } else {
        $value = MYSQL_RESULT($result_lookup, 0, 'photo');
        //$newfilename = $uploadPath."cash/TEMP_$value";
        //$url = resizeToFile($uploadPath."$value", 150, 135, $newfilename);
        $url = $uploadPath . $value;
        if ($url)
            return "<img src=\"$url\" width='600px' border=\"0\" align=\"top\"/> ";
        else
            return "<img src=\"" . _PREF . "uploads/$value\" border=\"0\" align=\"top\" width=\"200\" height=\"135\"/> ";
    }
}

//-------------------------------------------------------------------------------------------------------

function checkError($result, $sql) {
    if (!$result) {
        echo "<hr>\n" . $sql . "<br>\n"; // todo: this line should removed before final delivery.
        echo mysql_error() . "<hr>\n";
    }
}

// this method will make an array from $table 
// this array index is $coloumn1 and this array values are $coloumn2
function getColoumnAsArray($table, $coloumn1, $coloumn2) {
    if ($coloumn2) {
        $sql = "SELECT $coloumn1,$coloumn2 FROM $table";
        $result = MYSQL_QUERY($sql);
        $rows_result = MYSQL_NUM_ROWS($result);
        checkError($result, $sql);
        $arra = Array();
        $i = 0;
        while ($i < $rows_result) {
            $j = MYSQL_RESULT($result, $i, $coloumn2);
            $arra[$j] = MYSQL_RESULT($result, $i, $coloumn1);
            $i++;
        }
    } else {
        $sql = "SELECT $coloumn1 FROM $table";
        $result = MYSQL_QUERY($sql);
        $rows_result = MYSQL_NUM_ROWS($result);
        checkError($result, $sql);
        $arra = Array();
        $i = 0;
        while ($i < $rows_result) {

            $arra[$i] = MYSQL_RESULT($result, $i, $coloumn1);
            $i++;
        }
    }

    return $arra;
}

//genrate random string
function randomStringUtil($length = 5) {
    $type = 'num';
    $randstr = '';
    srand((double) microtime() * 1000000);

    $chars = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '0',
        'Q', 'W', 'E', 'R', 'T', 'Y', 'U', 'I', 'O', 'P', 'L', 'K', 'J', 'H', 'G', 'F', 'D', 'S', 'A', 'Z', 'X', 'C', 'V', 'B', 'N', 'M',
        'q', 'w', 'e', 'r', 't', 'y', 'u', 'i', 'o', 'p', 'l', 'k', 'j', 'h', 'g', 'f', 'd', 's', 'a', 'z', 'x', 'c', 'v', 'b', 'n', 'm'
    );
    if ($type == "alpha") {
        array_push($chars, '1');
    }

    for ($rand = 0; $rand < $length; $rand++) {
        $random = rand(0, count($chars) - 1);
        $randstr .= $chars[$random];
    }
    return $randstr;
}

//upload file
function handleupload($fieldName, $folder) {
    $availableEx = array("jpg", "gif", "png", "jpeg", "pdf", "doc", "docx", "xls", "xlsx");
    $realName = str_replace(" ", "_", $_FILES[$fieldName]['name']);
    $fileNameParts = explode(".", "$realName");
    $fileExtension = end($fileNameParts); // part behind last dot
    $ext = $fileExtension . "";
    if (in_array($ext, $availableEx)) {
        while (file_exists($folder . "/" . $realName)) {
            $realName = $fileNameParts[0] . "1." . $ext;
        }
        $uploadfile = $folder . "/" . $realName;
        if (move_uploaded_file($_FILES[$fieldName]['tmp_name'], $uploadfile)) {
            return $realName;
        }
    }
    return NULL;
}

function uploadImg($fieldName, $folder = '') {
    $availableEx = array("jpg", "gif", "png", "jpeg");
    if ($folder == '')
        $folder = '../../uploads';
    $file = $_FILES[$fieldName]['name'];
    if ($file != '') {
        $fileNameParts = explode(".", $file);
        $ext = strtolower(end($fileNameParts));
        if (in_array($ext, $availableEx)) {
            $realName = randomStringUtil(15) . '.' . $ext;
            while (file_exists($folder . "/" . $realName)) {
                $realName = randomStringUtil(15) . '.' . $ext;
            }
            $uploadfile = $folder . "/" . $realName;
            if (move_uploaded_file($_FILES[$fieldName]['tmp_name'], $uploadfile)) {
                return $realName;
            }
        } else {
            return 'x';
        }
    }
}

function uploadimageToGal($fieldName, $gal_name, $src) {
    $folder = "../../uploads";
    $realName = str_replace(" ", "_", $_FILES[$fieldName]['name']);
    while (file_exists($folder . "/" . $realName)) {
        $fileNameParts = explode(".", "$realName");
        $fileExtension = end($fileNameParts);
        $ext = $fileExtension . "";
        $realName = $fileNameParts[0] . "1." . $ext;
    }
    $small = "mcith/mcith_" . randomStringUtil(20) . '.' . $fileExtension;
    $uploadfile = $folder . "/" . $realName;
    if (move_uploaded_file($_FILES[$fieldName]['tmp_name'], $uploadfile)) {
        $newPhotoId = getNewId("galleries_photos", "id");
        $sql = "INSERT INTO `galleries_photos`(`id`,`photo`,`description`,`thumb`)
		VALUES('$newPhotoId','$realName', '', '$small');";
        if (mysql_query($sql)) {
            $newGalId = getNewId("galleries_galleries", "id");
            $sql = "INSERT INTO `galleries_galleries` 
			(`id`,`gallery_name_en`,`gallery_name_ar`,`description_en`,`main_photo`,`photos`,`src` )VALUES
			('$newGalId','$gal_name','','', '$newPhotoId' , '$newPhotoId' ,'$src');";
            if (mysql_query($sql)) {
                return $newGalId;
            } else {
                return 'x';
            }
        } else {
            return 'x';
        }
    }
}

function uploadimageToGallery($fieldName, $folder, $width, $height) {
    $realName = str_replace(" ", "_", $_FILES[$fieldName]['name']);
    while (file_exists($folder . "/" . $realName)) {
        $fileNameParts = explode(".", "$realName");
        $fileExtension = end($fileNameParts); // part behind last dot
        $ext = $fileExtension . "";
        $realName = $fileNameParts[0] . "1." . $ext;
    }
    if ($width && $height) {
        $uploadfile = $folder . "/cash/" . $realName;
        if (move_uploaded_file($_FILES[$fieldName]['tmp_name'], $uploadfile)) {
            $small = resizeToFile($folder . "/cash/" . $realName, $width, $height, $folder . "/" . $realName);
        }
    } else {
        $uploadfile = $folder . "/" . $realName;
        if (move_uploaded_file($_FILES[$fieldName]['tmp_name'], $uploadfile)) {
            $small = resizeToFile($folder . "/cash/" . $realName, $width, $height, $folder . "/" . $realName);
        }
    }
    $newPhotoId = getNewId("galleries_photos", "id");
    $sql = "INSERT INTO `galleries_photos` ( `id` , `photo` , `description` , `thumb` )
						VALUES ('$newPhotoId' , '$realName', '', '$small');";
    if (mysql_query($sql)) {
        $newGalId = getNewId("galleries_galleries", "id");
        $sql = "INSERT INTO `galleries_galleries` ( `id` , `gallery_name` , `description` , `main_photo` , `photos` )
						VALUES ('$newGalId' , '', NULL , '$newPhotoId' , '$newPhotoId');";
        if (mysql_query($sql)) {
            return $newGalId;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

//upload image
function uploadimage($fieldName, $folder, $width, $height) {
    $realName = str_replace(" ", "_", $_FILES[$fieldName]['name']);
    while (file_exists($folder . "/" . $realName)) {
        $fileNameParts = explode(".", "$realName");
        $fileExtension = end($fileNameParts); // part behind last dot
        $ext = $fileExtension . "";
        $realName = $fileNameParts[0] . "1." . $ext;
    }
    if ($width && $height) {
        $uploadfile = $folder . "/cash/" . $realName;
        if (move_uploaded_file($_FILES[$fieldName]['tmp_name'], $uploadfile)) {
            resizeToFile($folder . "/cash/" . $realName, $width, $height, $folder . "/" . $realName);
            unlink($folder . "/cash/" . $realName);
            return $realName;
        }
    } else {
        $uploadfile = $folder . "/" . $realName;
        if (move_uploaded_file($_FILES[$fieldName]['tmp_name'], $uploadfile))
            return $realName;
    }
    return NULL;
}

//resise image
function resizeToFile($img, $w, $h, $newfilename) {
    if (file_exists($newfilename))
        return $newfilename;
    //Check if GD extension is loaded
    if (!extension_loaded('gd') && !extension_loaded('gd2')) {
        trigger_error("GD is not loaded", E_USER_WARNING);
        return false;
    }
    //Get Image size info
    $imgInfo = getimagesize($img);
    switch ($imgInfo[2]) {
        case 1: $im = imagecreatefromgif($img);
            break;
        case 2: $im = imagecreatefromjpeg($img);
            break;
        case 3: $im = imagecreatefrompng($img);
            break;
        default: trigger_error('Unsupported filetype!', E_USER_WARNING);
            break;
    }
    //If image dimension is smaller, do not resize
    if ($imgInfo[0] <= $w && $imgInfo[1] <= $h) {
        $nHeight = $imgInfo[1];
        $nWidth = $imgInfo[0];
        return $img;
    } else {

        //yeah, resize it, but keep it proportional
        $rate = (($w / $imgInfo[0]) < ($h / $imgInfo[1])) ? ($w / $imgInfo[0]) : ($h / $imgInfo[1]);
        $nWidth = $imgInfo[0] * $rate;
        $nHeight = $imgInfo[1] * $rate;
    }
    $nWidth = round($nWidth);
    $nHeight = round($nHeight);

    $newImg = imagecreatetruecolor($nWidth, $nHeight);

    /* Check if this image is PNG or GIF, then set if Transparent */
    if (($imgInfo[2] == 1) OR ($imgInfo[2] == 3)) {
        imagealphablending($newImg, false);
        imagesavealpha($newImg, true);
        $transparent = imagecolorallocatealpha($newImg, 255, 255, 255, 127);
        imagefilledrectangle($newImg, 0, 0, $nWidth, $nHeight, $transparent);
    }
    imagecopyresampled($newImg, $im, 0, 0, 0, 0, $nWidth, $nHeight, $imgInfo[0], $imgInfo[1]);

    //Generate the file, and rename it to $newfilename
    switch ($imgInfo[2]) {
        case 1: imagegif($newImg, $newfilename);
            break;
        case 2: imagejpeg($newImg, $newfilename, 100);
            break;
        case 3: imagepng($newImg, $newfilename);
            break;
        default: trigger_error('Failed resize image!', E_USER_WARNING);
            break;
    }

    return $newfilename;
}

//Show banner
function showBanner($loc, $w, $h) {
    $sql = "SELECT upload,link FROM banners_banners WHERE (location IN ($loc)) AND (start_date<='CURDATE()') AND (end_date<='CURDATE()') ORDER BY RAND() LIMIT 1";
    $result = MYSQL_QUERY($sql);
    $numberOfRows = MYSQL_NUMROWS($result);
    if ($numberOfRows > 0) {
        $i = 0;
        $upload = basename(MYSQL_RESULT($result, $i, "upload"));
        $link = MYSQL_RESULT($result, $i, "link");
        $fileNameParts = explode(".", "$upload");
        $fileExtension = end($fileNameParts); // part behind last dot
        $ext = $fileExtension . "";
        if ($ext == "swf" || $ext == "SWF") {
            echo'<embed src="' . _PREF . 'uploads/' . $upload . '" width="' . $w . '" height="' . $h . '" quality="high"  allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.adobe.com/go/getflashplayer" />';
        } else {
            if ($link)
                echo'<a href="' . $link . '" target="_blank"><img src="' . _PREF . 'uploads/' . $upload . '" width="' . $w . '" height="' . $h . '" border="0"></a>';
            else
                echo'<img src="' . _PREF . 'uploads/' . $upload . '" width="' . $w . '" height="' . $h . '" border="0">';
        }//end else
    }//end nuber of rows
}

function getBannarName($loc) {
    global $BannerLocation;
    for ($b = 0; $b < count($BannerLocation); $b = $b + 2) {
        if ($BannerLocation[$b] == $loc) {
            echo $BannerLocation[$b + 1];
        }
    }
}

function bannerLocComboBox($loc) {
    global $BannerLocation;
    $ret = '<select name="location">';
    for ($b = 0; $b < count($BannerLocation); $b = $b + 2) {
        $ret.='<option value="' . $BannerLocation[$b] . '"';
        if ($BannerLocation[$b] == $loc) {
            $ret.= ' selected ';
        }
        $ret.='>' . $BannerLocation[$b + 1] . '</option>';
    }
    $ret.='</select>';
    echo $ret;
}

function getEval($time, $requirment, $quality, $attitude) {
    return ((50 * $requirment) + (40 * $time) + (5 * $quality) + (5 * $attitude)) / 100;
}

function sendMail($from, $to, $subject, $body) {
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=utf-8\r\n";
    $headers .= "To:" . $to . "\r\n";
    $headers .= "From: " . $from . " \r\n";

    if (@mail($to, $subject, $body, $headers)) {
        return 1;
    } else {
        return 0;
    }
}

function sendMessage($from, $to, $subject, $body, $type, $task_id) {
    $title = addslashes($subject);
    $description = addslashes($body);
    $sql = "INSERT INTO msgs (`title`,`description`,`create_date`,`from`,`to`,`type`,`task_id`) VALUES 
							 ('$title','$description','" . date('Y-m-d h:i:s') . "','$from','$to','$type','$task_id')";
    @mysql_query($sql);


    /* Send Message As Email */
    $sendto = "info@voilaapps.com";
    $res = mysql_query("SELECT email FROM employees WHERE id = '$to'");
    if (mysql_num_rows($res))
        $sendto.= "," . MYSQL_RESULT($res, 0, "email");

    /* To send HTML mail, you can set the Content-type header. */
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=windows-1256\r\n";

    /* additional headers */
    $headers .= "To:" . $sendto . "\r\n";
    $headers .= "From: $email \r\n";

    $bodys = explode("<br>", $body);
    $body = $bodys[0] . "<br> Please login to the task manager to check it";

    mail($sendto, $subject, $body, $headers);
}

// Get the filename with parameters
function getFileName() {
    $file = substr(strrchr($_SERVER['REQUEST_URI'], "/"), 1);
    return $file;
}

function getScriptName() {
    $file = substr(strrchr($_SERVER['SCRIPT_NAME'], "/"), 1);
    return $file;
}

function getFileName_no_uri() {
    $file = substr(strrchr($_SERVER['PHP_SELF'], "/"), 1);
    return $file;
}

function getWidgets() {
    $res = array();
    echo $currFile = getFileName();
    $whereStm = " WHERE ('$currFile' LIKE CONCAT('%',filename) OR '$currFile' LIKE CONCAT('%',filename,'?%') OR '$currFile' LIKE CONCAT('%',filename,'&%') )And (filename<>'') ";
    $sql = "SELECT * FROM wid_curr_widgets $whereStm ";
    $result = MYSQL_QUERY($sql);
    $numberOfRows = MYSQL_NUMROWS($result);

    if ($numberOfRows == 0) {
        if ($currFile == 'index.php' OR $currFile == '') {
            $def_type = 1;
        } else {
            $def_type = 2;
        }
        $whereStm = " WHERE (type='$def_type')";

        $ids = returnImplode_ids('wid_curr_widgets', "  $whereStm  ", 'id');
    }

    if ($numberOfRows > 0) {

        $ids = returnImplode_ids('wid_curr_widgets', "  $whereStm  ", 'id');
    }
    return $ids;
}

function print_internal_Link($link) {
    echo $link;
    $url = "";
    if (strpos($link, "page.php?"))
    //27-12-2011 Ahmad mahmoud
        $url = str_replace("view/page/page.php?", "pages/editPages.php?", $link);
    if ($url)
        echo "&nbsp;&nbsp;<a href=\"../" . $url . "\" target=\"_blank\">[" . "Edit Content" . "]</a>";
}

function print_GridAdminHead($title, $module, $del, $add, $ref, $help = 1, $order = 0, $seo = 0, $viewAll = 0, $lang = '', $widget = '') {
    global $activeModule;
    $out .= "<div class=\"head\">";
    $out .= '<div class="heasdTitle">&raquo; ' . $activeModule['GN'] . ' &raquo; <span>' . $activeModule['MN'] . $title . '</span></div>';
    if ($del)
        $out .= "<a href=\"javascript:actionf('Delete')\" class=\"delete\">
		<img src=\"" . _PREFICO . "Delete.png\" border='0' alt=\"" . _Delete . "\" title=\"" . _Delete . "\"/></a>";

    if ($add)
        $out .= "<img src=\"" . _PREFICO . "new.png\" border=\"0\" hspace=\"5\" onClick=\"actionf('Enter')\" style=\"cursor:pointer\"  
		alt=\"" . _add . "\" title=\"" . _add . "\"/>";

    if ($ref)
        $out .= "<img src=\"" . _PREFICO . "refresh.png\" onClick=\"actionf('Refresh')\" onMouseOver=\"over(this)\" style=\"cursor:pointer\" alt=\"" . _refresh . "\" title=\"" . _refresh . "\"/>";

    if ($order) {
        $out .= "<img src=\"" . _PREFICO . "Drag.png\" alt=\"" . DragToReorder . "\" title=\"" . DragToReorder . "\"/>";
    }
    if ($seo) {
        $out .= '<a href="../common/seo.php?lang=' . $lang . '&filename=' . $seo . '" class="dialog-form" title="' . _SEO . '"><img src="' . _PREFICO . 'SEO.png" alt="' . _SEO . '" title="' . _SEO . '" border=0></a>';
    }
    if ($widget) {
        $out .= '<a href="#" onClick="winopen(\'../widgets/listWidgets.php?filename=' . $seo . '\',600,700)"
		 title="' . _widget . '"><img src="' . _PREFICO . 'widgets.png" alt="' . _widget . '" title="' . _widget . '" border=0></a>';
    }
    if ($viewAll) {
        $out .= '<a href="' . $viewAll . '" target="_blank"><img src="' . _PREFICO . 'View.png" alt="' . _View . '" title="' . _View . '" border=0></a>';
    }
    $out .= getHelp_brief();
    $out .= "</div>";
    echo $out;
}

function printHeader($subTitle = '', $operations = '', $chi = '', $college_id = '') {
    global $activeModule, $CMSLang, $VIEWLang;
    $out.= '<div class="head">';
    if ($subTitle != '')
        $subTitle = ' &raquo; ' . $subTitle;
    if ($chi != '') {
        $chir = " | $chi";
    }
    $out .= '<div class="heasdTitle">&raquo; ' . $activeModule['GN'] . ' &raquo; 
	<span>' . $activeModule['MN'] . $subTitle . '</span>' . $chir . '</div>';

    $par = explode(',', $operations);
    for ($i = 0; $i < count($par); $i++) {
        $addition = '';
        $items = explode(':', $par[$i]);
        if (count($items) > 1)
            $addition = $items[1];
        $out.=getHeaderItime($items[0], $addition, $college_id);
    }
    $out .= getHelp_brief();
    $out .="</div>";
    return $out;
}

function getHeaderItime($item, $addition, $college_id = '') {
    global $VIEWLang;
    switch ($item) {
        case 'ref':
            $link = "javascript:actionf('Refresh')";
            $link_class = '';
            $icon = 'refresh.png';
            $alt = _refresh;
            break;
        case 'del':
            $link = "javascript:actionf('Delete')";
            $link_class = 'delete';
            $icon = 'Delete.png';
            $alt = _Delete;
            break;
        case 'add':
            $link = "javascript:actionf('Enter')";
            if ($addition)
                $link = $addition;
            $link_class = '';
            $icon = 'new.png';
            $alt = _add;
            break;
        case 'ord':
            $icon = 'Drag.png';
            $alt = DragToReorder;
            break;
        case 'seo':
            $link = '../common/seo.php?filename=' . $addition;
            $link_class = 'dialog-form';
            $icon = 'SEO.png';
            $alt = _SEO;
            break;
        case 'view':
            $link = $addition;
            $link_class = '';
            $icon = 'View.png';
            $alt = _View;
            $blank_target = ' target="_blank" ';
            break;
        case 'widget':
            $link = "javascript:winopen('../site_settings/listWidgetsNew.php?filename=" . $addition . "&lang=" . $VIEWLang . "&college_id=" . $college_id . "',700,600,'scrollbars=yes')";
            $link_class = '';
            $icon = 'widgets.png';
            $alt = _widget;
            break;
        case 'gal':
            $link = "javascript:winopen('../includes/gallery/addGallery.php',850,500)";
            $link_class = '';
            $icon = 'new.png';
            $alt = _add;
            break;

        default:$stop = 1;
            break;
    }

    if (!$stop) {
        $out = '';
        if ($link) {
            $out.='<a href="' . $link . '" class="' . $link_class . '" ' . $blank_target . ' >';
        }
        $out.='<img src="' . _PREFICO . $icon . '" alt="' . $alt . '"  title="' . $alt . '" border="0"/>';
        if ($link) {
            $out.='</a>';
        }
        return $out;
    }
}

function getHalpTitle($tip_id, $subTitle = '') {
    global $activeModule, $CMSLang, $VIEWLang;
    $out.= '<div class="head">';
    if ($subTitle != '')
        $subTitle = ' &raquo; ' . $subTitle;

    $out .= '<div class="heasdTitle">&raquo; ' . $activeModule['GN'] . ' &raquo; 
	<span>' . $activeModule['MN'] . $subTitle . '</span></div>';
    $par = explode(',', $operations);
    $out .="</div>";
    return $out;
}

function print_ListAdminHead($title, $module, $addUrl = 0, $del = 0, $help = 1, $order = 0, $seo = 0, $viewAll = 0, $lang = '') {
    global $activeModule;
    $out .= "<div class=\"head\">";
    $out .= '<div class="heasdTitle">&raquo; ' . $activeModule['GN'] . ' &raquo; <span>' . $activeModule['MN'] . $title . '</span></div>';
    if ($del)
        $out .= "<a href=\"javascript:actionf('Delete')\" class=\"delete\"><img src=\"" . _PREFICO . "Delete.png\" alt=\"" . _Delete . "\"  title=\"" . _Delete . "\" border='0'/></a>";
    if ($archive)
        $out .= "<a href=\"javascript:actionf('Archive')\" class=\"delete\"><img src=\"" . _PREFICO . "archive.png\" alt=\"" . _archive . "\"  title=\"" . _archive . "\" border='0'/></a>";
    if ($restore)
        $out .= "<a href=\"javascript:actionf('Restore')\" class=\"delete\"><img src=\"" . _PREFICO . "restore.png\" alt=\"" . _restore . "\"  title=\"" . _restore . "\" border='0'/></a>";

    if ($addUrl)
        $out .= "<a href='" . $addUrl . "'><img src='" . _PREFICO . "new.png' border='0' align='left' hspace='5' alt=\"" . _add . "\"  title=\"" . _add . "\" /></a>";
    if ($order) {
        $out .= "<img src=\"" . _PREFICO . "Drag.png\" alt=\"" . DragToReorder . "\" title=\"" . DragToReorder . "\"/>";
    }
    if ($seo) {
        $out .= '<a href="../common/seo.php?lang=' . $lang . '&filename=' . $seo . '" class="dialog-form" title="' . _SEO . '"><img src="' . _PREFICO . 'SEO.png" alt="' . _SEO . '" title="' . _SEO . '" border=0></a>';
    }
    if ($viewAll) {
        $out .= '<a href="' . $viewAll . '" target="_blank"><img src="' . _PREFICO . 'View.png" alt="' . _View . '" title="' . _View . '" border=0></a>';
    }
    $out .= getHelp_brief();
    $out .="</div>";
    echo $out;
}

function addToCombobox($grp_title, $pk, $title_col, $table, $cond, $url_template, $id_value) {
    $result .= "<optgroup label=\"" . $grp_title . "\">\n";
    if ($cond)
        $cond = "WHERE $cond";
    $sql_p = "SELECT $pk , $title_col from $table $cond  ORDER BY $title_col ";
    $result_p = mysql_query($sql_p);
    $rows_p = mysql_num_rows($result_p);
    $i = 0;
    while ($i < $rows_p) {
        $id = mysql_result($result_p, $i, "$pk");
        $title = mysql_result($result_p, $i, "$title_col");
        $titleR = rewriteFilter(mysql_result($result_p, $i, "$title_col"));
        $result .= "<option ";
        $url = str_replace("#ID#", $id, $url_template);
        $url = str_replace("#TITLE#", $titleR, $url);
        if ($id_value != null && $id_value != "" && $id_value == $url)
            $result .= "selected ";
        $result .= "value=\"$url\">$title</option>\n";
        $i++;
    }
    $result .= "</optgroup>\n";
    return $result;
}

function getLinks($id_value, $name = "item_link", $class = "", $script = "", $linkPref = null,$lang="") {
    global $VIEWLang;
 
    if($lang!=''){$VIEWLang=$lang;}
    $result = "<select name=\"$name\" id=\"$name\" class=\"$class\" $script>\n";
    //if ($id_value == null || $id_value == "") $result .= "<option></option>\n";
    $result .= '<option value="#">--------</option>';
    //$result .= "<option value='#'>$linkPref</option>\n";
    
    $langStmt = " lang='" . $VIEWLang . "' ";
    // Pages
    $result .= addToCombobox("Text Pages", "id", "title", "pages_pages", $langStmt, $linkPref . $VIEWLang . "/Page#ID#/#TITLE#", $id_value);

    // Forms
    $result .= addToCombobox("Forms", "id", "form_name", "qforms_qforms", $langStmt, $linkPref . $VIEWLang . "/Form#ID#/#TITLE#", $id_value);

    // Gallery
    $result .= addToCombobox("Galleries", "id", "name", "galleries_galleries", $langStmt, $linkPref . $VIEWLang . "/Gallery#ID#/#TITLE#", $id_value);

    $result .= addToCombobox("College", "id", "name_" . $VIEWLang, "college", '', $linkPref . $VIEWLang . "/College#ID#", $id_value);

    $result .= addToCombobox("Directorates", "id", "name_" . $VIEWLang, "st_directorates", '', $linkPref . $VIEWLang . "/Directorates#ID#", $id_value);

    $result .= addToCombobox("Publication", "id", "name_" . $VIEWLang, "publication_type", '', $linkPref . $VIEWLang . "/Publication/Type#ID#/", $id_value);
    $result .= addToCombobox("CL", "id", "name_" . $VIEWLang, "lab_type", '', $linkPref . $VIEWLang . "/CL/Type#ID#/#TITLE#", $id_value);

    $result .= addToCombobox("Boards", "id", "name_" . $VIEWLang, "st_league_council", '', $linkPref . $VIEWLang . "/Council#ID#", $id_value);
    // Modules
    $result .='<optgroup label="Modules">';
    $res = mysql_query("select * from menu_modules where active=1 ");
    while ($row = mysql_fetch_array($res)) {
        $sel = '';
        $Mpage = $linkPref . $VIEWLang . '/' . $row['page'];
        if ($id_value == $Mpage)
            $sel = " selected ";
        $result .='<option value="' . $Mpage . '" ' . $sel . ' >' . $row['name'] . '</option>';
    }
    $result .= "</optgroup>\n";


    // Subject Types
    if (chechUserPermissions('listSubjects')) {
        $result .= addToCombobox("Subject Types", "id", "name_" . $VIEWLang, "subjects_types", "", $linkPref . $VIEWLang . "/AllSubjects#ID#/#TITLE#", $id_value);
    }
    if (chechUserPermissions('listProducts')) {
        $result .= addToCombobox("Products Categories", "id", "name_en", "products_category", "", $linkPref . $VIEWLang . "/Products/C#ID#/#TITLE#", $id_value);
    }
    $result .= "</select>\n";


    return $result;
}

function menuHasChildren($mid) {
    $sql = "SELECT menu_id FROM menus WHERE p_id='$mid'";
    $res = mysql_query($sql);
    return mysql_numrows($res) > 0;
}

//***********************************************************//
/* getGPhoto Function 
  1- $gid = gallary id
  2- $w = Photo Width
  3- $h = Photo Hight
  4- $bigSize= (if=1 onclick show photo in oregnal size);
  5- $id = to make deffernt bettwn photos put any  unic value here
  6- $noPhoto= URL for Defult photo
  7- $homePage = if funanction load from hom page enter "1"
  8- $resizeText = any text to add to resize name
 */
function getGPhoto($gid, $w, $h, $bigSize, $id, $noPhoto, $homePage, $resizeText) {
    global $HP;
    if ($HP == 1) {
        $Pref = "";
    } else {
        $Pref = "../";
    }
    $pxx = 0;
    $res = "";
    if ($gid === 0) {
        $pxx = 1;
    } else {
        $ph == 0;
        $sql2 = "SELECT * FROM galleries_galleries g , galleries_photos p where g.id =$gid and g.main_photo=p.id ";
        $result2 = MYSQL_QUERY($sql2);
        $numberOfRows2 = MYSQL_NUMROWS($result2);
        if ($numberOfRows2 <= 0) {
            $pxx = 1;
        } else {

            $photo = MYSQL_RESULT($result2, 0, "p.photo");
            if ($bigSize == 1) {
                $res.='<a  href="' . _PREF . 'uploads/' . $photo . '" id="thumb_' . $id . '" onClick="return hs.expand(this, {slideshowGroup: ' . $id . '})" >';
            }
            $photo2 = resizeToFile($Pref . "uploads/" . $photo, $w, $h, $Pref . "uploads/cash/" . $resizeText . "_" . $photo);
            $res.='<img src="' . $photo2 . '" border="0" >';
            if ($bigSize == 1) {
                $res.='</a>';
            }
        }
    }
    if ($pxx == 1) {
        $res = "&nbsp;";
        if ($noPhoto) {
            $res = '<img src="' . $noPhoto . '" >';
        }
        //$res='<img src='.resizeToFile ("../images/noEvent.jpg",$width,$hight,"../images/".$id."noEvent.jpg").'" width='.$width.'" height='.$hight.'">';
    }
    return $res;
}

//***********************************************************//
//ShowGallery2($gal_id,640,400,90,90,"g1")
/* ShowGallery2
  1- $gid = gallary id
  2-$pw = Gallery width
  3-$ph = Gallery hight
  4-$fw = gallary width
  5-$fh = gallary hight
  6-$Resiz_name = any text to add to resize name
  CSS file: includes/gallery/jquery.galleryview/style.css
 */
function ShowGallery2($gid, $pw, $ph, $fw, $fh, $Resiz_name) {
    global $HP;
    if ($HP == 1) {
        $Pref = "";
    } else {
        $Pref = "../";
    }
    $res = "";
    $d = "";
    $l = "";
    $sql2 = "SELECT * FROM galleries_galleries  where  id=$gid ";
    $result2 = @MYSQL_QUERY($sql2);
    $numberOfRows2 = @ MYSQL_NUMROWS($result2);
    if ($numberOfRows2 > 0) {
        $photos = @MYSQL_RESULT($result2, 0, "photos");
        $sqlG = "SELECT * FROM galleries_photos  where  id in ($photos) ";
        $resultG = @MYSQL_QUERY($sqlG);
        $numberOfRowsG = @MYSQL_NUMROWS($resultG);
        if ($numberOfRowsG > 0) {
            $pxx = 1;
            $g = 0;
            $res.='<script type="text/javascript" src="' . _PREF . 'includes/gallery/jquery.galleryview/galleryview.js"></script>
<script type="text/javascript" src="' . _PREF . 'includes/gallery/jquery.galleryview/easing.js"></script>
<script type="text/javascript" src="' . _PREF . 'includes/gallery/jquery.galleryview/timers.js"></script>';
            $res.="<script>\$(document).ready(function(){\$('#photos').galleryView({
				panel_width:" . $pw . ",panel_height:" . $ph . ",frame_width:" . $fw . ",frame_height:" . $fh . "});});</script>";
            $res.='<table align="center"><tr><td><DIV id=photos class=galleryview align="center">';
            while ($numberOfRowsG > $g) {
                $PHid = @MYSQL_RESULT($resultG, $g, "id");
                $description = @MYSQL_RESULT($resultG, $g, "description");
                $Photo = @MYSQL_RESULT($resultG, $g, "photo");
                $photo2 = _PREF . "uploads/cash/galary1" . $Resiz_name . "_" . $Photo;
                $photo3 = _PREF . "uploads/cash/galary2" . $Resiz_name . "_" . $Photo;
                //if(file_exists(_PREF."uploads/".$Photo)){
                Crop($Pref . "uploads/" . $Photo, $fw, $fh, $Pref . "uploads/cash/galary1" . $Resiz_name . "_" . $Photo);
                Crop($Pref . "uploads/" . $Photo, $pw, $ph, $Pref . "uploads/cash/galary2" . $Resiz_name . "_" . $Photo);
                $d.='<DIV class=panel><IMG src="' . $photo3 . '"><DIV class=panel-overlay>' . $description . '</DIV></DIV>';
                $l.='<li><img src="' . $photo2 . '" border="0" width="' . ($fw - 2) . '" height="' . ($fh - 2) . '" alt="' . $description . '"  title="' . $description . '"/></li>';
                //}
                $g++;
            }
            $res.=$d;
            $res.='<ul class="filmstrip">' . $l . '</ul>
			';
            $res.='</DIV></td></tr></table>';
        }
    }
    return $res . "&nbsp;";
}

function ShowGallery3($gid) {
    global $HP;
    //if($HP==1){$Pref="";}else{$Pref="../../";}
    $Pref = "../../";
    $res = "";
    $sql2 = "SELECT * FROM galleries_galleries  where  id=$gid ";
    $result2 = MYSQL_QUERY($sql2);
    $numberOfRows2 = MYSQL_NUMROWS($result2);
    if ($numberOfRows2 > 0) {
        $photos = MYSQL_RESULT($result2, 0, "photos");
        $sqlG = "SELECT * FROM galleries_photos  where  id in ($photos) ";
        $resultG = MYSQL_QUERY($sqlG);
        $numberOfRowsG = MYSQL_NUMROWS($resultG);
        if ($numberOfRowsG > 0) {
            $pxx = 1;
            $g = 0;
            while ($numberOfRowsG > $g) {
                $PHid = MYSQL_RESULT($resultG, $g, "id");
                $Photo = MYSQL_RESULT($resultG, $g, "photo");
                $photo2 = _PREF . "uploads/cash/gal3_" . $Photo;
                CropSquare($Pref . "uploads/" . $Photo, 100, $Pref . "uploads/cash/gal3_" . $Photo);
                $res.='<a  class="thumb zoombox zgallery1" href="' . _PREF . 'uploads/' . $Photo . '" id="thumb_' . $PHid . '" rel="" >';
                $res.='<img src="' . $photo2 . '" border="0" class=""></a>';
                $g++;
            }
        }
    }

    return $res . "&nbsp;";
}

/* * *********************************************************************** */

// Crop Photo  to be Square
function CropSquare($img, $s, $newfilename) {
    if (file_exists($newfilename))
        return $newfilename;
    checkDir($newfilename);
    $strY = 0;
    $strX = 0;
    //Check if GD extension is loaded
    if (!extension_loaded('gd') && !extension_loaded('gd2')) {
        trigger_error("GD is not loaded", E_USER_WARNING);
        return false;
    }
    //Get Image size info
    $imgInfo = getimagesize($img);
    $nHeight = $imgInfo[1];
    $nWidth = $imgInfo[0];
    switch ($imgInfo[2]) {
        case 1: $im = imagecreatefromgif($img);
            break;
        case 2: $im = imagecreatefromjpeg($img);
            break;
        case 3: $im = imagecreatefrompng($img);
            break;
        default: trigger_error('Unsupported filetype!', E_USER_WARNING);
            break;
    }
    //If image dimension is smaller, do not resize
    if ($imgInfo[0] <= $s && $imgInfo[1] <= $s) {
        $nHeight = $imgInfo[1];
        $nWidth = $imgInfo[0];
        return $img;
    } else {
        //yeah, resize it, but keep it proportional
        if ($nHeight > $nWidth) {
            $ww = $s;
            $hh = ($nHeight * $s) / $nWidth;
            $strY = ($nHeight - $nWidth) / 2;
            $side = $imgInfo[0];
        } else {
            $hh = $s;
            $ww = ($nWidth * $s) / $nHeight;
            $strX = ($nWidth - $nHeight) / 2;
            $side = $imgInfo[1];
        }
    }
    $newImg = imagecreatetruecolor($s, $s);
    /* Check if this image is PNG or GIF, then set if Transparent */
    if (($imgInfo[2] == 1) OR ($imgInfo[2] == 3)) {
        imagealphablending($newImg, false);
        imagesavealpha($newImg, true);
        $transparent = imagecolorallocatealpha($newImg, 255, 255, 255, 127);
        imagefilledrectangle($newImg, 0, 0, $s, $s, $transparent);
    }
    imagecopyresampled($newImg, $im, 0, 0, $strX, $strY, $s, $s, $side, $side);
    //Generate the file, and rename it to $newfilename
    switch ($imgInfo[2]) {
        case 1: imagegif($newImg, $newfilename);
            break;
        case 2: imagejpeg($newImg, $newfilename, 100);
            break;
        case 3: imagepng($newImg, $newfilename);
            break;
        default: trigger_error('Failed resize image!', E_USER_WARNING);
            break;
    }


    return $newfilename;
}

/**
 * Create paging plugin for a page
 * @param int $tp Number of records in this page 
 * @param int $pn Current page nummber
 * @param int $LPP Records per page
 * @param string $link (Optional) Additional parameters to be passed to next page 
 */
function createPagination($tp, $pn, $LPP, $link = "") {
    $PHP_SELF = getPageURL();
    //Remove tp,pn and llp parameters from the URL to avoid repeating parameters
    $pattern = '/tp=(\d+)&/i';
    $PHP_SELF = preg_replace($pattern, "", $PHP_SELF);
    $pattern = '/pn=(\d+)&/i';
    $PHP_SELF = preg_replace($pattern, "", $PHP_SELF);
    $pattern = '/llp=(\d+)&/i';
    $PHP_SELF = preg_replace($pattern, "", $PHP_SELF);

    //Trim & if found to avoid repeating 
    $PHP_SELF = trim($PHP_SELF, "&");
    //If last char is not ? return $PHP_SELF
    //else : If the URI has ? then append & to it 
    //		 else append ?
    if ($PHP_SELF[strlen($PHP_SELF) - 1] != '?') {
        if (strstr($PHP_SELF, '?')) {
            $PHP_SELF .= "&";
        } else {
            $PHP_SELF .= "?";
        }
    }


    $res = "<div align=\"right\" class=\"pagination fr\">";
    $pages = ceil($tp / $LPP);
//    if (!$pn)
//        $pn = 0;
//
//    $start = max(0, ($pn - 2));
//    $end = min($pages - 1, ($start + 5));
//    $start = max(0, ($end - 5));
//
//    if ($pn > 0) {
//        $res .= "<a class='page' href='{$PHP_SELF}llp=$LPP&tp=$tp&pn=" . max(0, ($pn - 1)) . "&$link'>&laquo;</a>&nbsp;";
//        for ($i = $start; $i < $end; $i++)
//            if ($i == $pn)
//                $res .= '[<font color="#EE4717" size="3">' . ($i + 1) . '</font>] ';
//            else
//                $res .= "<a class='page' href='{$PHP_SELF}llp=$LPP&tp=$tp&pn=" . ($i) . "&$link'>" . ($i + 1) . "</a> ";
//        if ($pn + 1 < $pages)
//            $res .= "&nbsp;<a class='page' href='{$PHP_SELF}llp=$LPP&tp=$tp&pn=" . min(($pn + 1), $pages - 1) . "&$link'>&raquo;</a>";
//        $res .= " &nbsp;|&nbsp;";
//    }
    $strtt = ($pn * $LPP);
    if ($strtt == 0) {
        $strtt = 1;
    }
    $endd = ($pn * $LPP) + $LPP;
    if ($endd > $tp) {
        $endd = $tp;
    }
    $res .= "&nbsp;$strtt - $endd From ($tp)";
    $res .= " &nbsp;|&nbsp;";
    $res .= "Go To:&nbsp; <select onchange=\"location='{$PHP_SELF}llp=$LPP&tp=$tp&pn='+(options[selectedIndex].value)+'&$link'\" name='select_pn' id='select_pn' >";
    for ($i = 0; $i < $pages; $i++) {
        $sel = "";
        if ($i == $pn)
            $sel = "selected";
        $res .= "<option $sel value='$i'>" . ($i + 1) . "</option>";
    }
    $res .= "</select>";
    $res .= " &nbsp;|&nbsp; Show: ";
    if ($tp > 10)
        $res .= " <a href='{$PHP_SELF}tp=$tp&llp=10&pn=" . floor($pn * $LPP / 10) . "&$link'>" . (($LPP == 10) ? "<u>10</u>" : "10") . "</a>";
    if ($tp > 20)
        $res .= " <a href='{$PHP_SELF}tp=$tp&llp=20&pn=" . floor($pn * $LPP / 20) . "&$link'>" . (($LPP == 20) ? "<u>20</u>" : "20") . "</a>";
    if ($tp > 50)
        $res .= " <a href='{$PHP_SELF}tp=$tp&llp=50&pn=" . floor($pn * $LPP / 50) . "&$link'>" . (($LPP == 50) ? "<u>50</u>" : "50") . "</a>";
    if ($tp > 100)
        $res .= " <a href='{$PHP_SELF}tp=$tp&llp=100&pn=" . floor($pn * $LPP / 100) . "&$link'>" . (($LPP == 100) ? "<u>100</u>" : "100") . "</a>";

    $res .= " <a href='{$PHP_SELF}tp=$tp&llp=" . ($tp + 1) . "&pn=" . floor($pn * $LPP / ($tp + 1)) . "&$link'>" . (($LPP == $tp + 1) ? "<u>ALL</u>" : "ALL") . "</a>";
    $res.="</div>";

    return $res;
}

function createPagination_ajax($tp, $pn, $LPP, $link = "") {

    $PHP_SELF = getPageURL();
    //Remove tp,pn and llp parameters from the URL to avoid repeating parameters
    $pattern = '/tp=(\d+)&/i';
    $PHP_SELF = preg_replace($pattern, "", $PHP_SELF);
    $pattern = '/pn=(\d+)&/i';
    $PHP_SELF = preg_replace($pattern, "", $PHP_SELF);
    $pattern = '/llp=(\d+)&/i';
    $PHP_SELF = preg_replace($pattern, "", $PHP_SELF);

    //Trim & if found to avoid repeating 
    $PHP_SELF = trim($PHP_SELF, "&");
    //If last char is not ? return $PHP_SELF
    //else : If the URI has ? then append & to it 
    //		 else append ?
    if ($PHP_SELF[strlen($PHP_SELF) - 1] != '?') {
        if (strstr($PHP_SELF, '?')) {
            $PHP_SELF .= "&";
        } else {
            $PHP_SELF .= "?";
        }
    }


    $res = "<div align=\"center\" class=\"pagination\">";
    $pages = ceil($tp / $LPP);
    if (!$pn)
        $pn = 0;

    $start = max(0, ($pn - 2));
    $end = min($pages - 1, ($start + 5));
    $start = max(0, min($start, ($end - 5)));
    //if($pn>0)	{$res .= "<a class='page_num' id='llp=$LPP&tp=$tp&pn=".max(($pn-1),0)."&$link'><img class='left_arrow' src='"._PREF."wedgits/order_products/left_page.jpg'/></a>&nbsp;";}else{$res .= "<img class='left_arrow' src='"._PREF."wedgits/order_products/left_page.jpg'/>&nbsp;";}
    if ($pn > 0) {
        $res .= "<a class='page_num' id='llp=$LPP&tp=$tp&pn=" . max(($pn - 1), 0) . "&$link'> &laquo; </a>&nbsp;";
    } else {
        $res .= "<font color='#e7dbba'> &laquo; </font> &nbsp;";
    }
    for ($i = $start; $i <= $end; $i++)
        if ($i == $pn)
            $res .= '<font class="curr_num" style="">' . ($i + 1) . '</font> ';
        else
            $res .= "<a class='page_num num' id='llp=$LPP&tp=$tp&pn=" . ($i) . "&$link'>" . ($i + 1) . "</a> ";
    if ($pn + 1 < $pages) {
        //$res .= "&nbsp;<a class='page_num' id='llp=$LPP&tp=$tp&pn=".min(($pn+1),$pages-1)."&$link'><img class='right_arrow' src='"._PREF."wedgits/order_products/right_page.jpg'/></a>";
        $res .= "&nbsp;<a class='page_num' id='llp=$LPP&tp=$tp&pn=" . min(($pn + 1), $pages - 1) . "&$link'> &raquo; </a>";
    } else {
        //$res .= "<img class='right_arrow' src='"._PREF."wedgits/order_products/right_page.jpg'/>";
        $res .= " <font color='#e7dbba'> &raquo; </font>";
    }

    return $res;
}

function count_items($table, $cond = "") {
    if ($cond)
        $cond = "WHERE " . $cond;
    $sql = "SELECT count(*)co FROM $table $cond";
    $res = mysql_query($sql);
    $co = mysql_result($res, 0, "co");
    return $co;
}

function getUrlParameters() {
    $params = array();
    $i = 0;
    if (!empty($_POST)) {
        foreach ($_POST as $x => $y) {
            $params[$i][0] = ($x);
            $params[$i][1] = $_POST[$x];
            $i++;
        }
    }
    if (!empty($_GET)) {
        foreach ($_GET as $x => $y) {
            $params[$i][0] = ($x);
            $params[$i][1] = $_GET[$x];
            $i++;
        }
    }
    return $params;
}

/**
 * Create Filtering form and return SQL condition
 * @param filters: (array) filtering input names starting with (# <=> equal , % <=> like)
 * @param fields: (array) db fields 
 * @param op: AND/OR
 * @param with_hidden: 1/0 show url variables in SQL condition.
 * @return SQL condition
 */
function createFilter($filters, $fields, $op = "AND", $extention = "", $with_hidden = 1, $black_list = null) {
    //create filter fields:
    $params = getUrlParameters();
    if (is_array($black_list)) {
        array_push($black_list, 'action');
        array_push($black_list, 'id');
    } else {
        $black_list = array("action", "id");
    }
    foreach ($fields as $f) {
        
    }
    foreach ($fields as $field) {
        $name = $param[0];
        $value = $param[1];
        if ($name == "pn")
            $value = 0;
        if ($name == "")
            continue;
        $field = substr($field, 1);
        if (!in_array($name, $field) && !in_array($name, $black_list)) {
            $hidden_input .= "<input type='hidden1' id='$name' name='$name' value='$value'/>";
        }
    }
    $result = "<form method='get' action='" . $_SERVER['SCRIPT_NAME'] . "'><u>" . filteringBy . ":</u>&nbsp;";
    $i = 0;
    foreach ($filters as $filter) {
        $filter_name = ucfirst(str_replace("_", " ", $filter)) . ":";
        $filter_type = "type='text'";
        if ($fields[$i][0] == '^')
            continue;
        $filde_name = str_replace('^', '', $fields[$i]);
        $filde_name = str_replace('%', '', $fields[$i]);
        $result .= "$filter_name&nbsp;<input $filter_type id='$filter' name='$filde_name' value='" . $_REQUEST[$filde_name] . "'/>&nbsp;&nbsp;";
        $i ++;
    }
    $result .= $extention;
    if ($with_hidden)
        $result .= $hidden_input;
    $result .= "<input type='submit' value='" . _go . "' style='width:35px' class='go' />&nbsp;<a href='" . $_SERVER['PHP_SELF'] . "' >" . clear_fillter . "</a></form>";
    echo "<div class='filtering fl'>" . $result . "</div>";

    //create filter query stmt:
    $num = count($fields);
    $cond = "";
    for ($i = 0; $i < $num; $i++) {
        $filde_name = str_replace('^', '', $fields[$i]);
        $filde_name = str_replace('%', '', $fields[$i]);
        $value = $_REQUEST[$filde_name];
        if ($value == "")
            continue;

        $fieldGrp = trim($fields[$i]);
        $fieldArr = explode('|', $fieldGrp);
        $cond .= "(";
        foreach ($fieldArr as $field) {
            if (strlen($field) <= 1)
                continue;
            $compare_type = $field[0];
            $field = substr($field, 1);
            switch ($compare_type) {
                case "#": case "^":
                    $cond .= $field . "='" . $value . "' ";
                    break;
                case "%":
                    $cond .= $field . " LIKE '%" . $value . "%'";
                    break;
            }
            $cond .= " OR ";
        }
        $cond = trim(trim($cond), "OR");
        $cond .= ") $op ";
    }
    $cond = trim(trim($cond), $op);
    return $cond;
}

function orderingUrlSuffix() {
    $PHP_SELF = getPageURL();
    $PHP_SELF = trim($PHP_SELF, "&");

    if ($PHP_SELF[strlen($PHP_SELF) - 1] != '?')
        $PHP_SELF .= (strstr($PHP_SELF, '?')) ? "&" : "?";

    $pattern = '/so=(\w+)&/i';
    $orderingUrlSuffix = preg_replace($pattern, "", $PHP_SELF);
    $pattern = '/sb=(\w+)&/i';
    $orderingUrlSuffix = preg_replace($pattern, "", $orderingUrlSuffix);
    return $orderingUrlSuffix;
}

function filterURL($url, $filter) {
    $pattern = '/' . $filter . '=(\w+)&/i';
    $url = preg_replace($pattern, "", $url);
    $pattern = '/' . $filter . '=(\w+)/i';
    $url = preg_replace($pattern, "", $url);
    return $url;
}

function deleteRecord($table, $cond, $conf_msg) {
    $sql = "DELETE FROM $table WHERE $cond";
    file_put_contents('del', $sql);
    $result = MYSQL_QUERY($sql);
    if ($result && $conf_msg)
        echo '<script>correctMessage("' . Record_Update . '")</script>';
    return $result;
}

function getCurrentFormName() {
    $file = substr(strrchr($_SERVER['PHP_SELF'], "/"), 1);
    $thisfile = substr($file, 0, strpos($file, '.php'));
    return $thisfile;
}

function getPrevFormName() {
    $file = substr(strrchr($_SERVER['HTTP_REFERER'], "/"), 1);
    $thisfile = substr($file, 0, strpos($file, '.php'));
    return $thisfile;
}

///////////////////////////////////
function getHelp_brief() {
    global $CMSLang;
    $thisfile = getCurrentFormName();
    $sql = "select m.id,h.title,h.brief from login_modules m,help_help h 
	where m.file='$thisfile' and m.m_id!=0 and m.id=h.file_id and h.lang='$CMSLang'";
    $result = mysql_query($sql);
    $numrows = mysql_num_rows($result);
    if ($numrows) {
        $tip_id = mysql_result($result, 0, 'm.id');
        $title = mysql_result($result, 0, 'h.title');
        $brief = strip_tags(mysql_result($result, 0, 'h.brief'));
        $help_content = Help;
        $html_result = '
<a target="_blank"  title="' . $title . ' |' . $brief . '" href="../help/help.php?tip_id=' . $tip_id . '">'
                . '<img src="' . _PREFICO . 'Help.png"   alt="' . Help . '"  title="' . $help_content . '"/></a>
						';
    }
    return $html_result;
}

function print_AdminRecordHead($field, $head, $sb, $so, $static_order = 0) {
    if ($static_order)
        return $head;
    $out .= '<a href="' . orderingUrlSuffix() . 'so=' . $so . '&sb=' . $field . '&"><b>';
    if ($sb == $field)
        $out .= "<u>$head</u>&nbsp;<img src='" . _PREFICO . $so . ".png' alt='D' border=0>";
    else
        $out .= $head;
    $out .= '</b></a>';
    return $out;
}

function print_seo_icon($filename, $lang = '') {
    if ($lang) {
        $lang_param = "lang=" . $lang . "&";
    }
    return '<a href="../common/seo.php?' . $lang_param . 'filename=' . $filename . '" class="dialog-form" title="' . _SEO . '"><img src="' . _PREFICO . 'SEO.png" alt="' . _SEO . '" title="' . _SEO . '" border=0></a>';
}

function print_thumb_icon($photos, $module, $module_id, $crops) {
    if ($photos != '') {
        $count = 0;
        $link = '';
        foreach ($crops as $crop) {
            $count++;
            $type = $crop['type'];
            $width = $crop['width'];
            $height = $crop['height'];
            $link.="&type$count=$type&width$count=$width&height$count=$height";
        }

        $photo = $photos;
        $flink = "count=$count&photo=$photo&module=$module&module_id=$module_id" . $link;
        if ($photo != '') {
            return '<a href="../common/crop.php?' . $flink . '" class="dialog-form" title="' . Thumbnail . '"><img src="' . _PREFICO . 'highlight.png" alt="' . Thumbnail . '" title="' . Thumbnail . '" border=0></a>';
        }
    }
}

function print_seo_icon2($filename, $lang = '') {
    if ($lang) {
        $lang_param = "lang=" . $lang . "&";
    }
    return '<a href="../common/seo.php?' . $lang_param . 'filename=' . $filename . '" class="dialog-form" title="' . _SEO . '"><img src="' . _PREFICO . 'pro_SEO.png" alt="' . _SEO . '" title="' . _SEO . '" border=0></a>';
}

function print_widget_icon($filename, $lang) {
    return '<a href="#" onClick="winopen(\'../site_settings/listWidgets.php?filename=' . $filename . '&lang=' . $lang . '\',700,600)" ><img src="' . _PREFICO . 'widgets.png" alt="' . Widgets . '" title="' . Widgets . '" border="0"></a>';
}

function print_widget_icon3($filename, $lang, $college_id = '') {
    return '<a href="#" onClick="winopen(\'../site_settings/listWidgetsNew.php?filename=' . $filename . '&college_id=' . $college_id . '&lang=' . $lang . '\',700,600)" ><img src="' . _PREFICO . 'widgets.png" alt="' . Widgets . '" title="' . Widgets . '" border="0"></a>';
}

function print_widget_icon2($filename, $lang) {
    return '<a href="#" onClick="winopen(\'../site_settings/listWidgets.php?filename=' . $filename . '&lang=' . $lang . '\',700,600)" ><img src="' . _PREFICO . 'widgets2.png" alt="' . Widgets . '" title="' . Widgets . '" border="0"></a>';
}

function print_gallery_icon($gallery) {
    return '<a href="javascript:winopen(\'../includes/gallery/editGallery.php?gid=' . $gallery . '\',850,480)"><img src="' . _PREFICO . 'gallery.png" alt="' . _editGal . '" title="' . _editGal . '" border=0></a>';
}

function print_edit_icon($url) {
    return '<a href="' . $url . '"><img src="' . _PREFICO . 'Edit.png" alt="' . _Edit . '" title="' . _Edit . '"  border=0></a>';
}

function print_MessageBoard_icon($url) {
    return '<a href="' . $url . '"><label>' . MessageBoard . '</label>&nbsp;<img src="' . _PREFICO . 'message.png" alt="' . _Edit . '" title="' . _Edit . '"  border=0></a>';
}

function print_Major_icon($url) {
    return '<a href="' . $url . '"><label>' . Major . '</label>&nbsp;<img src="' . _PREFICO . 'major.png" alt="' . _Edit . '" title="' . _Edit . '"  border=0></a>';
}

function print_shortCuts_icon($url) {
    return '<a href="' . $url . '"><label>' . ShortCuts . '</label>&nbsp;<img src="' . _PREFICO . 'shortcuts.png" alt="' . _Edit . '" title="' . _Edit . '"  border=0></a>';
}

function print_alumini_icon($url) {
    return '<a href="' . $url . '"><label>' . Alumni . '</label>&nbsp;<img src="' . _PREFICO . 'alumini.png" alt="' . _Edit . '" title="' . _Edit . '"  border=0></a>';
}

function print_honer_icon($url) {
    return '<a href="' . $url . '"><label>' . Honer_List . '</label>&nbsp;<img src="' . _PREFICO . 'alumini.png" alt="' . _Edit . '" title="' . _Edit . '"  border=0></a>';
}

function print_TimeTable_icon($url) {
    return '<a href="' . $url . '"><label>' . Tabs . '</label>&nbsp;<img src="' . _PREFICO . 'timeTable.png" alt="' . _Edit . '" title="' . _Edit . '"  border=0></a>';
}

function print_sp_icon($url) {
    return '<a href="' . $url . '"><label>' . Specialization . '</label>&nbsp;<img src="' . _PREFICO . 'sp.png" alt="' . _Edit . '" title="' . _Edit . '"  border=0></a>';
}

function print_pub_icon($url) {
    return '<a href="' . $url . '"><label>' . Publication . '</label>&nbsp;<img src="' . _PREFICO . 'pub.png" alt="' . _Edit . '" title="' . _Edit . '"  border=0></a>';
}

function print_lab_icon($url) {
    return '<a href="' . $url . '"><label>' . Labs . '</label>&nbsp;<img src="' . _PREFICO . 'lab.png" alt="' . _Edit . '" title="' . _Edit . '"  border=0></a>';
}

function print_alu_icon($url) {
    return '<a href="' . $url . '"><label>' . Alumni . '</label>&nbsp;<img src="' . _PREFICO . 'alu.png" alt="' . _Edit . '" title="' . _Edit . '"  border=0></a>';
}

function print_higllight_icon($url) {
    return '<a href="' . $url . '"><label>' . Higllight . '</label>&nbsp;<img src="' . _PREFICO . 'highlight.png" alt="' . _Edit . '" title="' . _Edit . '"  border=0></a>';
}

function print_link_icon($url) {
    return '<a href="' . $url . '"><img src="' . _PREFICO . 'link.png" alt="' . _link . '" title="' . _link . '"  border=0></a>';
}

function print_copy_icon($url) {
    return '<a href="' . $url . '"><img src="' . _PREFICO . 'copy.png" alt="' . _Copy . '" title="' . _Copy . '"  border=0></a>';
}

function print_view_icon($url) {
    return '<a href="' . $url . '" target="_blank"><img src="' . _PREFICO . 'View.png" alt="' . _View . '" title="' . _View . '" border=0></a>';
}

function print_view_icon2($link, $title) {
    return '
      <a title="' . $title . '" class="popup2" href="' . $link . '">
      <img src="' . _PREF . 'modules/includes/css/images/icons/View.png" width="24" height="25" border="0" />
      </a>';
}

function print_delete_icon($url = "") {
    if ($url != "")
        return '<a href="' . $url . '" class="delete"><img src="' . _PREFICO . 'Delete.png" alt="' . _Delete . '"  title="' . _Delete . '" border=0></a>';
    else
        return '<img src="' . _PREFICO . 'Delete_unactive.png" alt="' . _Delete . '" title="' . _Delete . '" border=0>';
}

function print_archive_icon($url = "") {
    if ($url != "")
        return '<a href="' . $url . '" class="delete"><img src="' . _PREFICO . 'archive.png" alt="' . _archive . '"  title="' . _archive . '" border=0></a>';
    else
        return '<img src="' . _PREFICO . 'Delete_unactive.png" alt="' . _archive . '" title="' . _archive . '" border=0>';
}

function print_restor_icon($url = "") {
    if ($url != "")
        return '<a href="' . $url . '" class="delete"><img src="' . _PREFICO . 'archive.png" alt="' . _restore . '"  title="' . _restore . '" border=0></a>';
    else
        return '<img src="' . _PREFICO . 'restor.png" alt="' . _restore . '" title="' . _restore . '" border=0>';
}

function print_save_icon($action) {
    return '<img src="' . _PREFICO . 'save.png" onClick="actionf(\'' . $action . '\')" onMouseOver="over(this)" alt="' . _save . '" title="Save"  />';
}

function print_subItems_icon($url) {
    return '<a href="' . $url . '"><img src="' . _PREFICO . 'menu.png" alt="' . _ico_subMenu . '"  title="' . _ico_subMenu . '" border=0></a>&nbsp';
}

function print_subItems_icon1($url, $name) {
    return '<a href="' . $url . '"><img src="' . _PREFICO . 'menu.png" alt="' . _ico_subMenu . '"  title="' . $name . '" border=0></a>&nbsp';
}

function print_delete_ckbox($value = "") {
    if ($value != "")
        return '<input name="rid[]" type="checkbox" value="' . $value . '"  ch_del />';
    else
        return '<input  type="checkbox" DISABLED/>';
}

function print_fildes_icon($form_id) {
    return '<a href="listForm_fields.php?form_id=' . $form_id . '" class="icon"><img src="' . _PREFICO . 'fields.png" alt="' . Form_Fields . '" title="' . Form_Fields . '" border=0></a>';
}

function print_exp_excel_icon($form_id) {
    return '<a target="_blank" href="generate_file.php?id=' . $form_id . '" class="icon"><img src="' . _PREFICO . 'export_excel.png" title="' . downEXCEL . '" alt="' . downEXCEL . '" border=0></a>';
}

function create_lang_switcher($addtion = '') {
    if ($addtion)
        $addtion = $addtion . '&';
    $out = '';
    global $langsArrayView, $VIEWLang;
    $lang_type = 'veiw';
    if (count($langsArrayView) > 1) {
        $out.='<div class="lang_switcher fr" >' . switch_Lang . ' 
		<select name="VIEWLang" onchange="window.location=\'?' . $addtion . 'VIEWLang=\'+this.value">';
        foreach ($langsArrayView as $value) {
            $out.='<option value="' . $value[0] . '"';
            if ($VIEWLang == $value[0])
                $out.=' selected ';
            $out.='>' . $value[1] . '</option>';
        }
        $out.='</select></div>';
    }
    return $out;
}

function selectCheckAnswer($q_id, $Can = "") {
    $out = '';
    $arr = explode(",", $Can);
    $sq = "select * from s_answer where question_id=$q_id";
    $res = mysql_query($sq); {

        while ($value = mysql_fetch_array($res)) {

            $out.='<input  type="checkbox" value="' . $value["title_ar"] . '" name="c,' . $value["id"] . '" ';

            if (in_array($value["title_ar"], $arr))
                $out.="checked";
            $out.='>  ' . $value["title_ar"] . '<br>';
        }
    }
    return $out;
}

function selectConflict($an) {
    $out = '';


    $arr = explode(",", $an);
    $sq = "select * from parties_conflict ";
    $res = mysql_query($sq);

    while ($value = mysql_fetch_array($res)) {
        $q_id = $value['id'];

        file_put_contents('d3', $arr);
        $out.='<input  type="checkbox" value="' . $value["title_ar"] . '" name="conflict,' . $value["id"] . '"';

        if (in_array($q_id, $arr))
            $out.="checked";
        $out.=' >  ' . $value["title_ar"] . '<br>';
        $i++;
    }

    return $out;
}

function selectRadioAnswer($q_id, $Ran = "") {
    $out = '';
    $sq = "select * from s_answer where question_id=$q_id";
    file_put_contents('dara', $sq, FILE_APPEND);
    $res = mysql_query($sq); {
        $out.=' 
		<select name="r,' . $q_id . '" >';
        $out.='<option value="--"';

        $out.='>--</option>';
        while ($value = mysql_fetch_array($res)) {

            $out.='<option value="' . $value['title_ar'] . '"';
            if ($Ran == $value['title_ar']) {
                $out.="selected";
            }

            $out.='>' . $value['title_ar'] . '</option>';
        }
        $out.='</select>';
    }
    return $out;
}

function selectLang($lang = '') {
    $out = '';
    global $langsArrayView, $VIEWLang;
    if ($lang == '')
        $lang = $VIEWLang;
    $lang_type = 'veiw';
    if (count($langsArrayView) > 1) {
        $out.=' 
		<select name="VIEWLang" >';
        foreach ($langsArrayView as $value) {
            $out.='<option value="' . $value[0] . '"';
            if ($lang == $value[0])
                $out.=' selected ';
            $out.='>' . $value[1] . '</option>';
        }
        $out.='</select>';
    }
    return $out;
}

function createLangCombo($items, $values, $name, $class, $selected_item) {
    $items = trim($items);
    $itemsArr = explode('|', $items);
    $valuesArr = explode('|', $values);
    $i = 0;
    echo "<select name='" . $name . "' class='" . $class . "'>";
    foreach ($itemsArr as $item) {
        if ($valuesArr[$i] == $selected_item) {
            echo "<option selected value='" . $valuesArr[$i] . "'>$item</option>";
        } else {
            echo "<option value='" . $valuesArr[$i] . "'>$item</option>";
        }
        $i++;
    }
    echo "</select>";
}

//////////////////////////////Ordering function//////////////////////////////////

function getNewItemOrder($table, $ordering_col, $unique_cond = '') {
    if ($unique_cond)
        $unique_cond = "WHERE " . $unique_cond;
    $sql = "SELECT MAX($ordering_col) AS max FROM $table $unique_cond ";
    $result = mysql_query($sql);
    if ($result)
        return $max = MYSQL_RESULT($result, 0, 'max') + 1;
    else
        return 1;
}

function changeOrder($table, $pk, $ordering_col, $new_ord) {
    $action = $_REQUEST['action'];
    if ($action != 'up' && $action != 'down')
        return;
    $id = $_REQUEST['id'];
    $sid = addslashes($_REQUEST['sid']);
    $o = addslashes($_REQUEST['o']);
    $so = addslashes($_REQUEST['so']);
    if ($id != "" && $sid != "" && $o != "" && $so != "") {
        if ($so == $o) {
            $so = $new_ord;
            $o = $new_ord + 1;
        }
        $res = mysql_query("UPDATE $table SET $ordering_col='$so' WHERE $pk='$id'");
        $res1 = mysql_query("UPDATE $table SET $ordering_col='$o'  WHERE $pk='$sid'");
        if ($res && $res1)
            echo '<script>correctMessageWithoutBack("' . Record_Update . '")</script>';
    }
}

function showUpDownIcons($result, $i, $pk, $ordering_col, $url_prefix) {
    $menu_id = MYSQL_RESULT($result, $i, $pk);
    $ord = MYSQL_RESULT($result, $i, $ordering_col);
    $numberOfRows = MYSQL_NUMROWS($result);
    echo '<TD align="center" WIDTH="35">';
    if ($i != '0') {
        $So = MYSQL_RESULT($result, $i - 1, $ordering_col);
        $Sid = MYSQL_RESULT($result, $i - 1, $pk);
        echo '<a href="' . $_SERVER['PHP_SELF'] . '?action=up&id=' . $menu_id . '&sid=' . $Sid . '&o=' . $ord . '&so=' . $So . '&' . $url_prefix . '">';
        echo '<img src="' . _PREFICO . 'up.png" alt="Up" border=0>';
        echo '</a>';
    } else
        echo "&nbsp";
    echo '</TD>';
    echo '<TD align="center" WIDTH="34">';
    if ($i + 1 != $numberOfRows) {
        $So = MYSQL_RESULT($result, $i + 1, $ordering_col);
        $Sid = MYSQL_RESULT($result, $i + 1, $pk);
        echo '<a href="' . $_SERVER['PHP_SELF'] . '?action=down&id=' . $menu_id . '&sid=' . $Sid . '&o=' . $ord . '&so=' . $So . '&' . $url_prefix . '">';
        echo '<img src="' . _PREFICO . 'down.png" alt="Down" border=0>';
        echo '</a>';
    } else
        echo "&nbsp";
    echo '</TD>';
}

function myFormate($number) {
    return number_format(round($number, 2), 2, '.', ',');
}

function getLangsAsArray() {
    $langs = array();
    $res = mysql_query("select `lang` from languages ");
    while ($row = mysql_fetch_array($res)) {
        $langs[] = $row['lang'];
    }

    return $langs;
}

function makeOrderingList($sql, $id, $table, $filed) {
    $res = "<script>
	var order_table=\"" . $table . "\";
	var order_filed =\"" . $filed . "\";
	var order_id =\"" . $id . "\";
	ordIds=new Array();";
    $i = 1;
    while ($row = mysql_fetch_array($sql)) {
        $res.="ordIds[" . $i . "]=" . $row[$id] . ";";
        $i++;
    }
    $res.='
	$(document).ready(function(){
		$("#list tbody tr").hover(function(){
			$(this).css( "cursor", "move" );
		});
		$("#list tbody").sortable({
			connectWith: "tr",
			cursor: "move",
			forcePlaceholderSize: true,
			opacity: 0.4,
			stop: function(event, ui){
				var orderChanges="";
				var sortorder="";
				var itemorder=0;
				$(".sortable tr").each(function(){
					var columnId=$(this).attr("id");
					itemorder++;
					if(columnId!=ordIds[itemorder]){
						orderChanges+=columnId+","+ordIds[itemorder]+"|";
					}
					ordIds[itemorder]=columnId;
				});
				//alert(orderChanges);
				if(orderChanges!=""){
					$("tr").css("cursor","wait");
					$.post("../includes/order.php", {ot:order_table,of:order_filed,oi:order_id, ids:orderChanges} ,function(data){
						$("tr").css("cursor","default");
						$("#info").html(data);
					});
				}
			}
		})
	})';
    $res.="</script>";
    return $res;
}

function getBannerTitle($l) {
    global $locations;
    for ($i = 0; $i < count($locations); $i++) {
        if ($l == $locations[$i][0]) {
            return $locations[$i][1];
        }
    }
}

function getFileEx($file) {
    $ex = explode(".", $file);
    return strtolower(end($ex));
}

function ViewMainPhoto($g_id, $w = 100, $h = 100) {
    $file = '';
    $folder = 'uploads/';
    $reziseFolder = "uploads/mcith/mcith_";
    $images_array = array("jpg", "gif", "png", "jpeg");
    $nophoto = '<div class=" listViewFileDiv noPhoto" style="width:' . $w . 'px; height:' . $h . 'px">&nbsp;</div>';
    $sql = "select p.photo, p.folder from galleries_galleries g , galleries_photos p where g.main_photo=p.id and g.id='$g_id'";
    $res = mysql_query($sql);
    $rows = mysql_num_rows($res);
    if ($rows > 0) {
        $file = mysql_result($res, 0, 'p.photo');
        $subfolder = mysql_result($res, 0, 'p.folder');
        $file_ex = getFileEx($file);
        if (file_exists("../../" . $folder . $subfolder . $file)) {
            if ($file == "") {
                return $nophoto;
            } else {
                if (in_array($file_ex, $images_array)) {
                    $newImage = resizeToFile('../../' . $folder . $subfolder . $file, 100, 100, '../../' . $reziseFolder . $file);
                    if ($newImage != '') {
                        return '
						<div class="listViewFileDiv" style="width:' . $w . 'px; height:' . $h . 'px;background-image:url(' . $newImage . ')" 
						onclick="window.open(\'../../' . $folder . $file . '\',\'\',\'width=800,height=500\')" ></div>';
                    } else {
                        return $nophoto;
                    }
                } else {
                    if (file_exists('../../images/filesTypes/' . $file_ex . '.png')) {
                        return '<a href="../../' . $folder . $file . '" target="_blank"><img src="../../images/filesTypes/' . $file_ex . '.png" width="100" style="margin:5px" border="0"></a>';
                    } else {
                        return '<img src="../../images/filesTypes/x.png" width="100" style="margin:5px" border="0" >';
                    }
                }
            }
        } else {
            return '<img src="../../images/filesTypes/x.png" width="100" style="margin:5px" border="0" >';
        }
    } else {
        return $nophoto;
    }
}

function ViewAdminListFile($file, $reziseFolder = 'uploads/mcith/mcith_', $folder = 'uploads/', $w = 100, $h = 100) {
    $images_array = array("jpg", "gif", "png", "jpeg");
    $file_ex = getFileEx($file);
    $nophoto = '<div class=" listViewFileDiv noPhoto" style="width:' . $w . 'px; height:' . $h . 'px">&nbsp;</div>';
    if (file_exists("../../" . $folder . $file)) {
        if ($file == "") {
            return $nophoto;
        } else {
            if (in_array($file_ex, $images_array)) {
                $newImage = resizeToFile('../../' . $folder . $file, 100, 100, '../../' . $reziseFolder . basename($file));
                if ($newImage != '') {
                    return '
					<div class="listViewFileDiv" style="width:' . $w . 'px; height:' . $h . 'px;background-image:url(' . $newImage . ')" 
					onclick="window.open(\'../../' . $folder . $file . '\',\'\',\'width=800,height=500\')" ></div>';
                } else {
                    return $nophoto;
                }
            } else {
                if (file_exists('../includes/css/images/filesTypes/' . $file_ex . '.png')) {
                    return '<a href="../../' . $folder . $file . '" target="_blank"><img src="../includes/css/images/filesTypes/' . $file_ex . '.png" width="100" style="margin:5px" border="0"></a>';
                } else {
                    return '<img src="../includes/css/images/filesTypes/x.png" width="100" style="margin:5px" border="0" >';
                }
            }
        }
    } else {
        return '<img src="../includes/css/images/filesTypes/x.png" width="100" style="margin:5px" border="0" >';
    }
}

function checkGallogin() {
    if (isset($_SESSION["enterCMS"]) && $_SESSION["enterCMS"] == 'go') {
        return true;
    } else {
        return false;
    }
}

function convTime($v) {
    $str = "H ";
    $v = intval($v);
    //-------Hours--------------
    if ($v > 60 * 60) {
        $str.=intval($v / (60 * 60)) . ":";
        $xhour = intval($v % (60 * 60));
    } else {
        $xhour = $v;
        $str.="0:";
    }
    //-------Mins--------------
    if ($xhour > 60) {
        $str.=intval($xhour / (60)) . ":";
        $xmin = intval($xhour % (60));
    } else {
        $str.="0:";
        $xmin = $xhour;
    }
    //-------Sec--------------
    $str.=$xmin;

    return $str;
}

function getSortCuts($showTitle = 1) {
    global $allow_modules, $CMSLang;
    $allow = explode(',', $allow_modules);
    $user = $_SESSION['USER_ID'];
    $str = '';
    $sql = "select* from shortcuts s ,login_modules m , login_modules g 
	where 
	s.user_id='$user' and 
	s.module=m.id and 
	g.id=m.g_id 
	order by g.ord ASC , m.ord ASC
	";
    $res = mysql_query($sql);
    $rows = mysql_num_rows($res);
    if ($rows > 0) {
        if ($showTitle) {
            $str.='<div class="blocktitle">Shortcuts</div>';
        }
        $i = 0;
        while ($i < $rows) {
            $id = mysql_result($res, $i, 'm.id');
            $title = mysql_result($res, $i, 'm.name_' . $CMSLang);
            $group = mysql_result($res, $i, 'g.name_' . $CMSLang);
            $photo = mysql_result($res, $i, 'g.photo');
            $folder = mysql_result($res, $i, 'm.folder');
            $out_link = mysql_result($res, $i, 'm.out_link');
            $file = mysql_result($res, $i, 'm.file');
            $link = '../' . $folder . '/' . $file . '.php';
            $bg = '';
            if ($photo)
                $bg = ' style="background:url(' . _PREFICO . $photo . '.png) no-repeat center 5px" ';

            $target = '';
            if ($out_link)
                $target = ' target="_blank" ';

            if (in_array($id, $allow) || $user < 2) {
                $str.='
				<a  href="' . $link . '" class="sr_cut_link" ' . $target . '>
				<div ' . $bg . ' class="shortc" title="' . $group . ' : ' . $title . '" >
					<div><span>' . $group . '</span><br>' . $title . '</div>
				</div>
				</a>';
            }

            $i++;
        }
    }
    return $str;
}

//*******************************Top News*************************************************/
$permissins = array('', 'add', 'edit', 'del', 'pub', 'com', 'spi', 'arc');

function get_per($user, $type) {
    global $permissins;
    $per = array(0, 0, 0, 0, 0, 0, 0, 0);
    $sql = "select * from subjects_permissions where user='$user' and type='$type' limit 1";
    $res = mysql_query($sql);
    $rows = mysql_num_rows($res);
    if ($rows > 0) {
        for ($i = 1; $i < count($per); $i++) {
            $per[$i] = mysql_result($res, 0, $permissins[$i]);
        }
    }
    return $per;
}

function get_per_chat($user, $room) {
    $sql = "select * from room_permissions where user='$user' and room='$room' limit 1";
    $res = mysql_query($sql);
    $rows = mysql_num_rows($res);
    if ($rows > 0) {

        $p = mysql_result($res, 0, 'active');
    } else
        $p = 0;
    return $p;
}

function get_offices_per($user, $type) {
    global $permissins;
    $per = array(0, 0, 0, 0, 0, 0, 0, 0);
    $sql = "select * from offices_permissions where user='$user' and type='$type' limit 1";
    $res = mysql_query($sql);
    $rows = mysql_num_rows($res);
    if ($rows > 0) {
        for ($i = 1; $i < count($per); $i++) {
            $per[$i] = mysql_result($res, 0, $permissins[$i]);
        }
    }
    return $per;
}

function get_sections_per($user, $type) {
    global $permissins;
    $per = array(0, 0, 0, 0, 0, 0, 0, 0);
    $sql = "select * from sections_permissions where user='$user' and type='$type' limit 1";
    $res = mysql_query($sql);
    $rows = mysql_num_rows($res);
    if ($rows > 0) {
        for ($i = 1; $i < count($per); $i++) {
            $per[$i] = mysql_result($res, 0, $permissins[$i]);
        }
    }
    return $per;
}

function checkNewsUser() {
    $u = filter($_POST["username"]);
    $p = filter($_POST["password"]);
    $sql = "select * from login_users where user='$u' and pass='$p' and active=1 limit 1";
    $res = mysql_query($sql);
    $rows = mysql_num_rows($res);
    if ($rows > 0) {
        $user_id = mysql_result($res, 0, 'user_id');
        $name = mysql_result($res, 0, 'full_name');
        $_SESSION["enterCMS"] = "topNews";
        $_SESSION["news_user_id"] = $user_id;
        $_SESSION["news_user_name"] = $name;
        $_SESSION["vAdmin"] = "0";
        return 1;
    }
}

function check_news_permissins($user, $p) {
    if ($_SESSION["enterCMS"] == "go" && $_SESSION["USER"] < 2)
        return 1;
    global $permissins;
    $per = $permissins[$p];
    $sql = "select count(*) c from tnews_permissions where `user`='$user' and `$per`=1 ";
    $res = mysql_query($sql);
    return mysql_result($res, 0, 'c');
}

function get_news_cats($user, $p) {
    global $permissins;
    $arr = array();
    $per = $permissins[$p];
    $sql = "select type from subjects_permissions where `user`='$user' and `$per`=1 ";
    $res = mysql_query($sql);
    $rows = mysql_num_rows($res);
    while ($row = mysql_fetch_array($res)) {
        array_push($arr, $row['type']);
    }
    return $arr;
}

function get_news_offices($user, $p) {
    global $permissins;
    $arr = array();
    $per = $permissins[$p];
    $sql = "select type from offices_permissions where `user`='$user' and `$per`=1 ";
    $res = mysql_query($sql);
    $rows = mysql_num_rows($res);
    while ($row = mysql_fetch_array($res)) {
        array_push($arr, $row['type']);
    }
    return $arr;
}

function get_news_sections($user, $p) {
    global $permissins;
    $arr = array();
    $per = $permissins[$p];
    $sql = "select type from sections_permissions where `user`='$user' and `$per`=1 ";
    $res = mysql_query($sql);
    $rows = mysql_num_rows($res);
    while ($row = mysql_fetch_array($res)) {
        array_push($arr, $row['type']);
    }
    return $arr;
}

function convToTimeStamp($t) {
    $t = substr($t, 8, 2) . '-' . substr($t, 5, 2) . '-' . substr($t, 0, 4);
    $timestamp = strtotime(date($t));
    //echo "<br>";
    //echo $timestamp = date('U');
    return $timestamp;
}

function logNews($user, $opr, $news_id, $comment = 0, $title = '') {
    /*
      $opr=
      1-Add news
      2-Edit News
      3-Delete News
      4-Publish News
      5-Cancel Publish News
      6-Publish Comment
      7-Cancel Publish Comment
      8-Delete Comment
      9-Add to Arcive
      10-Restor from Archive
      11-Restor from Recycle bin
      12-Delete from Database
      13-Special News
      14-Cancel Special News
     */
    $date = date('U');
    $sql = "INSERT INTO subjects_log (`date`,`user`,`opr`,`news`,`comment`,`title`)values('$date','$user','$opr','$news_id','$comment','$title')";
    $res = mysql_query($sql);
}

function make_input_date($d, $s = '-') {
    $str = '';
    $str.= date('Y' . $s . 'm' . $s . 'd', $d);
    return $str;
}

function make_database_date($t) {
    $str = '';
    $t = substr($t, 5, 2) . '/' . substr($t, 8, 2) . '/' . substr($t, 0, 4);
    return $t;
}

function make_date($d) {
    $str = '';
    $str.= date('Y-m-d A g:i:s ', $d);
    $from = dateToTimeS(date('U') - $d);
    $str.="(" . $from . ")";
    return $str;
}

function dateToTimeS($d) {
    $c = 0;
    $str = '';
    $tt = $d;
    if ($tt > 60 * 60 * 24 * 365) {
        $str.= "Year aGo";
    } else {
        if ($tt > 60 * 60 * 24) {
            $str = intval($tt / 60 / 60 / 24) . " Days";
            $tt2 = $tt - (intval($tt / 60 / 60 / 24) * (60 * 60 * 24));
            $c++;
        } else {
            $tt2 = $tt;
        }
        if ($tt2 > 60 * 60) {
            if ($c < 2) {
                if ($c > 0) {
                    $str.=' - ';
                }
                $str.= intval($tt2 / 60 / 60) . " Hours";
                $tt3 = $tt2 - (intval($tt2 / 60 / 60) * (60 * 60));
                $c++;
            }
        } else {
            $tt3 = $tt2;
        }
        if ($tt3 > 60) {
            if ($c < 2) {
                if ($c > 0) {
                    $str.=' - ';
                }
                $str.= intval($tt3 / 60) . " Minute";
                $tt4 = $tt3 - (intval($tt3 / 60) * (60));
                $c++;
            }
        } else {
            $tt4 = $tt3;
        }
        if ($tt4 > 0) {
            if ($c < 2) {
                if ($c > 0) {
                    $str.=' - ';
                }
                $str.=intval($tt4) . " Second";
            }
        }
    }

    return $str;
}

function getPubPer($user, $news, $per) {
    $sql = "select count(*) c from tnews_tnews n ,tnews_permissions p where 
	n.id='$news' and
	n.category=p.cat and 
	p.user='$user' and 
	p.$per=1
	";
    $res = mysql_query($sql);
    return mysql_result($res, 0, 'c');
}

function get_comment_cat($com_id) {
    $sql = "select n.category from tnews_comments c , tnews_tnews n where c.id='$com_id' and n.id=c.news_id limit  1";
    $res = mysql_query($sql);
    $rows = mysql_num_rows($res);
    if ($rows > 0) {
        return mysql_result($res, 0, 'n.category');
    }
}

function fileUploader($filedName, $photos = '') {
    $vFiles = '';
    if ($photos) {
        $phs = explode(',', $photos);
        for ($i = 0; $i < count($phs); $i++) {
            $round = rand(1111, 99999);
            $folder = getPhotoFolder($phs[$i]);
            $Bphoto = '../../uploads/' . $folder . '/' . $phs[$i];
            $p1 = explode('.', $phs[$i]);
            $rsphoto = '../../uploads/temp/' . $p1[0] . '_s.' . $p1[1];
            $sphoto = resizeToFile($Bphoto, 80, 80, $rsphoto);
            $vFiles.='
			<div id="id_' . $round . '" style="background-image:url(' . $sphoto . ')" class="upImage" name="' . $phs[$i] . '" >
			<div class="close_butt" onclick="del_photo(\'' . $round . '\',\'' . $phs[$i] . '\')"></div></div>';
        }
    }
    $str = '
	<input name="' . $filedName . '" id="photosArray" style="width:640px;" value="' . $photos . '" type="hidden" />	
	<div class="flexWin">
		<div class="flexWinIn">
			<div style="float:left">
				<div class="l_close" onclick="closeLwin()"></div>
				<div class="l_folders">' . subjectPhotosFolders() . '</div>
			</div>
			<div id="l_files"></div>
		</div>
	</div>
	<div class="uploadify-button3" onclick="openPhotoLibrary()">' . _sel_from_library . '</div>
	<DIV style="width:700px; clear:both" id="vFiles">&nbsp;' . $vFiles . '</DIV>
	<div id="upload_div"></div>				
	<script type="text/javascript" src="../includes/upload_photo/script/uploadPhoto.js" ></script>
	<script>$(document).ready(function(){uploadImage();})</script>';
    return $str;
}

function subjectPhotosFolders() {
    $ret = '';
    $root = '../../uploads/';
    $ret.='<div class="folderCont" onClick="loadThisFolder(\'all\')" ><div class="folderI" id="folall" Folder="all">' . showAllPhotos . '</div></div>';

    if ($Folder = opendir($root)) {
        $i = 0;

        while (false !== ($file = readdir($Folder))) {
            if ($file != "." && $file != "..") {
                if (is_dir($root . $file)) {
                    $ret.='<div class="folderCont" onClick="loadThisFolder(' . $i . ')">';
                    $folderName = checkIsSubjectForder($file);
                    if ($folderName) {
                        $Fclass = "folderI";
                        if ($actPath == $root . $file . '/') {
                            $Fclass = "folderI2";
                        }
                        $ret.='<div class="' . $Fclass . '" id="fol' . $i . '" Folder="' . $file . '">' . $folderName . ' </div>';
                        $i++;
                    }
                    $ret.='</div>';
                }
            }
        }
        closedir($Folder);
    }
    return $ret;
}

function getAllfolder() {
    $root = '../../../uploads/';
    $res = array('');
    if ($Folder = opendir($root)) {

        while (false !== ($file = readdir($Folder))) {
            if ($file != "." && $file != "..") {
                if (is_dir($root . $file)) {
                    $folderName = checkIsSubjectForder($file);
                    if ($folderName) {
                        array_push($res, $file);
                    }
                }
            }
        }
        closedir($Folder);
    }
    return $res;
}

function checkIsSubjectForder($file) {
    if ($file > 0 && $file < 3000) {
        return '20' . substr($file, 0, 2) . '-' . substr($file, 2, 2);
    }
}

function VideoUploader($filedName) {
    $vFiles = '';
    $str = '
	<div id="upload_video" ></div>	
	<script type="text/javascript" src="../includes/upload_photo/script/uploadPhoto.js" ></script>
	<script>$(document).ready(function(){uploadVideo();})</script>';
    return $str;
}

function VideoProUploader($filedName) {
    $vFiles = '';
    $str = '
	<div id="upload_video" ></div>	
	<script type="text/javascript" src="../includes/upload_photo/script/uploadPhoto.js" ></script>
	<script>$(document).ready(function(){uploadProVideo();})</script>';
    return $str;
}

function attachUploader($filedName) {
    $vFiles = '';
    $str = '
	<div id="upload_attach" ></div>	
	<script type="text/javascript" src="../includes/upload_photo/script/uploadPhoto.js" ></script>
	<script>$(document).ready(function(){uploadAttach();})</script>';
    return $str;
}

function attachProUploader($filedName) {
    $vFiles = '';
    $str = '
	<div id="upload_attach" ></div>	
	<script type="text/javascript" src="../includes/upload_photo/script/uploadPhoto.js" ></script>
	<script>$(document).ready(function(){uploadProAttach();})</script>';
    return $str;
}

function getPhotosFromTemp($p, $id = 0) {
    $out = '';
    $f = 0;
    $photos = explode(',', $_REQUEST[$p]);
    //delete files---------------
    if ($id) {
        $lastPhotos = lookupField('tnews_tnews', 'id', 'photos', $id);
        $lastPhotosArray = explode(',', $lastPhotos);
        for ($i = 0; $i < count($lastPhotosArray); $i++) {
            if (!in_array($lastPhotosArray[$i], $photos)) {
                $folder = getPhotoFolder($lastPhotosArray[$i]);
                $files = '../../uploads/' . $folder . '/' . $lastPhotosArray[$i];
                @unlink($files);
            }
        }
    }
    //-------------------
    for ($i = 0; $i < count($photos); $i++) {
        $folder = getPhotoFolder($photos[$i]);
        $tempFile = '../../uploads/temp/' . $photos[$i];
        $path = '../../uploads/' . $folder . '/';
        if (!file_exists($path)) {
            mkdir($path, 0777);
        }
        $newFile = $path . $photos[$i];
        if (!file_exists($newFile)) {
            @copy($tempFile, $newFile);
        }
        if (file_exists($newFile)) {
            if ($f != 0) {
                $out.=',';
            }
            $f = 1;
            $out.=$photos[$i];
        }
    }
    return $out;
}

function getPhotoFolder($photo) {
    $date = substr($photo, 0, 10);
    $forder = @date('ym', $date);
    return $forder;
}

function ViewPhotos($photos, $n = 1, $total = 1, $w = 100, $h = 100) {
    $file = '';
    $allPhotos = '';
    $path = '../../uploads/';
    $reziseFolder = "uploads/cash/thumb_";
    $nophoto = '<div class=" listViewFileDiv noPhoto" style="width:' . $w . 'px; height:' . $h . 'px">&nbsp;</div>';
    if ($photos) {
        $phs = explode(',', $photos);
        $all = count($phs);
        if ($n > $all)
            $n = $all;
        for ($i = 0; $i < $n; $i++) {
            $photo = $path . getPhotoFolder($phs[$i]) . '/' . $phs[$i];
            $photo_ex = getFileEx($phs[$i]);
            file_put_contents('photo.txt', $photo);
            if (file_exists($photo)) {
                $thamp = resizeToFile($photo, $w, $h, '../../' . $reziseFolder . $phs[$i]);
                if ($thamp != '') {
                    if ($total == 1) {
                        $allPhotos.= '<div class="totalPhotos">' . $all . '</div>';
                    }
                    $allPhotos.= '
					<div class="listViewFileDiv" style="float:left;width:' . $w . 'px; height:' . $h . 'px;background-image:url(' . $thamp . ')" 
					onclick="window.open(\'' . $photo . '\',\'\',\'width=800,height=500\')" ></div>';
                } else {
                    $allPhotos.= $nophoto;
                }
            } else {
                return '<img src="../includes/css/images/filesTypes/x.png" width="100" style="margin:5px" border="0" >';
            }
        }
    } else {
        return $nophoto;
    }
    return $allPhotos;
}

function getOrgFiles($files, $withFolder = 1) {
    $str = '';
    if ($files != '' && $files != ',') {
        $sql = "select * from files where id IN($files) order by FIELD (id,$files)";
        $res = mysql_query($sql);
        $rows = mysql_num_rows($res);
        if ($rows > 0) {
            $i = 0;
            while ($i < $rows) {
                if ($i != 0)
                    ($str.=',');
                $file = mysql_result($res, $i, 'file');
                $folder = mysql_result($res, $i, 'folder');
                if ($withFolder)
                    $str.=$folder;
                $str.=$file;
                $i++;
            }
        }
    }
    return $str;
}

function ViewPhotos3($photos, $n = 1, $total = 1, $w = 100, $h = 100) {
    $Ext_arr_images = array("jpg", "jpeg", "gif", "png");
    $photos = explode(',', $photos);
    $photos = $photos[0];
    $photos = getOrgFiles($photos);
    $file = '';
    $allPhotos = '';
    $path = '../../uploads/';
    $reziseFolder = "uploads/cash/thumb_";
    // $nophoto = '<div class=" listViewFileDiv noPhoto" style="width:' . $w . 'px; height:' . $h . 'px">&nbsp;</div>';
    if ($photos) {
        $phs = explode(',', $photos);
        $all = count($phs);
        if ($n > $all)
            $n = $all;
        for ($i = 0; $i < $n; $i++) {
            $y = $phs[$i];
            $photo = $path . $phs[$i];
            $file = $path . $phs[$i];
            $photo_ex = getFileEx($phs[$i]);

            $FN = explode('/', $phs[$i]);
            $fileName = end($FN);
            $xFile = 0;
            if (file_exists($photo)) {
                if (!in_array($photo_ex, $Ext_arr_images)) {
                    $path = '../includes/css/images/filesTypes/';

                    $photo = $path . $photo_ex . '.png';
                    $fileName = $photo_ex . '.png';
                    if (!file_exists($photo)) {
                        $xFile = 1;
                    }
                } else {
                    $path = '../../uploads/';
                }
                if ($xFile == 0) {
                    $thamp = resizeToFile($photo, $w, $h, '../../' . $reziseFolder . str_replace('/', '_', $phs[$i]));
                    if ($thamp != '') {
                        if ($total > 1) {
                            $allPhotos.= $all;
                        }
                        $allPhotos.= $thamp;
                    } else {
                        $allPhotos.= $nophoto;
                    }
                } else {
                    $allPhotos.= $nophoto;
                }
            } else {
                if ($photo != "") {
                    $fileName = str_replace('&feature=youtu.be', '', $fileName);
                    $fileName = str_replace('watch?v=', '', $fileName);
                    return '<img src="http://img.youtube.com/vi/' . $fileName . '/1.jpg"  width="' . $w . '" style="margin:5px" border="0" >';
                } else
                    return '<img src="../includes/css/images/filesTypes/x.png" width="' . $w . '" style="margin:5px" border="0" >';
            }
        }
    } else {
        return $nophoto;
    }
    return $thamp;
}

function Files($files, $first = 1) {
    $query = "select id from files where id in ($files) order by FIELD(id,$files)";
    $result = mysql_query($query);
    $num = mysql_num_rows($result);
    if ($num > 0) {
        if ($first == 1) {
            return mysql_result($result, 0, 'id');
        }
    }
}

function ViewPhotos2($photos, $n = 1, $total = 1, $w = 100, $h = 100) {
    $Ext_arr_images = array("jpg", "jpeg", "gif", "png");
    //$photos = explode(',', $photos);
    $photos = Files($photos);
    $photos = getOrgFiles($photos);
    $file = '';
    $allPhotos = '';
    $path = '../../uploads/';
    $reziseFolder = "uploads/cash/thumb_";
    $nophoto = '<div class=" listViewFileDiv noPhoto" style="width:' . $w . 'px; height:' . $h . 'px">&nbsp;</div>';
    if ($photos) {
        $phs = explode(',', $photos);
        $all = count($phs);
        if ($n > $all)
            $n = $all;
        for ($i = 0; $i < $n; $i++) {
            $y = $phs[$i];
            $photo = $path . $phs[$i];
            $file = $path . $phs[$i];
            $photo_ex = getFileEx($phs[$i]);

            $FN = explode('/', $phs[$i]);
            $fileName = end($FN);
            $xFile = 0;
            if (file_exists($photo)) {
                if (!in_array($photo_ex, $Ext_arr_images)) {
                    $path = '../includes/css/images/filesTypes/';

                    $photo = $path . $photo_ex . '.png';
                    $fileName = $photo_ex . '.png';
                    if (!file_exists($photo)) {
                        $xFile = 1;
                    }
                } else {
                    $path = '../../uploads/';
                }
                if ($xFile == 0) {
                    $thamp = resizeToFile($photo, $w, $h, '../../' . $reziseFolder . str_replace('/', '_', $phs[$i]));
                    if ($thamp != '') {
                        if ($total > 1) {
                            $allPhotos.= '<div class="totalPhotos">' . $all . '</div>';
                        }
                        $allPhotos.= '
						<div class="listViewFileDiv" style="float:left;width:' . $w . 'px; height:' . $h . 'px;background-image:url(' . $thamp . ')" 
						onclick="window.open(\'' . $file . '\',\'\',\'width=800,height=500\')" ></div>';
                    } else {
                        $allPhotos.= $nophoto;
                    }
                } else {
                    $allPhotos.= $nophoto;
                }
            } else {
                if ($photo != "") {
                    $fileName = str_replace('&feature=youtu.be', '', $fileName);
                    $fileName = str_replace('watch?v=', '', $fileName);
                    return '<img src="http://img.youtube.com/vi/' . $fileName . '/1.jpg"  width="' . $w . '" style="margin:5px" border="0" >';
                } else
                    return '<img src="../includes/css/images/filesTypes/x.png" width="' . $w . '" style="margin:5px" border="0" >';
            }
        }
    } else {
        return $nophoto;
    }
    return $allPhotos;
}

function youtube_id($url) {
    $pattern = '%^# Match any youtube URL
        (?:https?://)?  # Optional scheme. Either http or https
        (?:www\.)?      # Optional www subdomain
        (?:             # Group host alternatives
          youtu\.be/    # Either youtu.be,
        | youtube\.com  # or youtube.com
          (?:           # Group path alternatives
            /embed/     # Either /embed/
          | /v/         # or /v/
          | /watch\?v=  # or /watch\?v=
          )             # End path alternatives.
        )               # End host alternatives.
        ([\w-]{10,12})  # Allow 10-12 for 11 char youtube id.
        $%x'
    ;
    $result = preg_match($pattern, $url, $matches);
    if (false !== (bool) $result) {
        return $matches[1];
    }
    return false;
}

///////////////////////////////////////////////////////////////////////////
function setSEO($lang, $file, $title, $des, $keywords = '') {
    $des = strip_tags($des);
    $title = strip_tags($title);
    $sql = "SELECT * FROM seo WHERE filename = '$file' and lang='$lang' ";
    $result = MYSQL_QUERY($sql);
    $numberOfRows = MYSQL_NUMROWS($result);
    if ($numberOfRows > 0) {
        $SQL = "UPDATE seo SET title='$title',keywords='$keywords',description='$des' WHERE filename='$file' && lang='$lang'";
        $result = mysql_query($SQL);
    } else {
        $SQL = "INSERT INTO seo (filename,title,keywords,description,lang)VALUES('$file','$title','$keywords','$des','$lang')";
        $result = mysql_query($SQL);
    }
}

function deleteTempFiles($m = 10) {//$m=minutes
    $tempFolder = '../../uploads/temp/';
    if (file_exists($tempFolder)) {
        $date = date('U') - ($m * 60);
        if ($Folder = opendir($tempFolder)) {
            while (false !== ($file = readdir($Folder))) {
                if ($file != "." && $file != "..") {
                    $filedate = $date - intval(substr($file, 0, 10));
                    if ($filedate > 0) {
                        @unlink($tempFolder . $file);
                    }
                }
            }
            closedir($Folder);
        }
    }
}

function autoArchive() {
    $date = date('U');
    $sql = "select id from subjects_subjects  where end_date < $date ";
    $res = mysql_query($sql);
    $rows = mysql_num_rows($res);
    if ($rows > 0) {
        $i = 0;
        while ($i < $rows) {
            $id = mysql_result($res, $i, 'id');
            moveToArchive($id);
            $i++;
        }
    }
}

function moveToArchive($ids) {
    $sql = "select * from subjects_subjects where id in ($ids)";
    $res = mysql_query($sql);

    $rows = mysql_num_rows($res);
    $arc_date = date('U');
    if ($rows > 0) {
        $i = 0;
        while ($i < $rows) {
            $colms = '';
            $values = '';
            $c = array('id', 'title', 'brief', 'details', 'photos', 'lang', 'tran_lang_id', 'user_editor', 'user_type', 'start_date', 'end_date', 'create_date', 'publish_date', 'user_publisher', 'status', 'type_id', 'office_id', 'section_id', 'views', 'admin_rank', 'view_rank_counter', 'view_rank_value', 'special', 'social', 'videos', 'links', 'attach');
            for ($a = 0; $a < count($c); $a++) {
                if ($a != 0) {
                    $colms.=' , ';
                    $values.=' , ';
                }
                $colms.="`" . $c[$a] . "`";
                $values.="'" . mysql_result($res, $i, $c[$a]) . "'";
            }
            $colms.=" , `archive_date`";
            $values.=" , '" . $arc_date . "'";

            $sql2 = "INSERT INTO subjects_archive ($colms) values($values)";
            file_put_contents('dr', $sql2);
            //echo $values;
            $res2 = mysql_query($sql2);

            $id = mysql_result($res, $i, $c[0]);
            if ($res2) {
                $s = "delete from subjects_subjects where id='$id' ";
                mysql_query($s);
            }
            $i++;
        }
    }
}

function backFromArchive($ids) {
    $sql = "select * from subjects_archive where id in ($ids)";
    $res = mysql_query($sql);
    $rows = mysql_num_rows($res);
    $arc_date = date('U');
    if ($rows > 0) {
        $i = 0;
        while ($i < $rows) {
            $colms = '';
            $values = '';
            $c = array('id', 'title', 'brief', 'details', 'photos', 'lang', 'tran_lang_id', 'user_editor', 'user_type', 'start_date', 'end_date', 'create_date', 'publish_date', 'user_publisher', 'status', 'type_id', 'office_id', 'section_id', 'views', 'admin_rank', 'view_rank_counter', 'view_rank_value', 'special', 'social', 'videos', 'links', 'attach');
            for ($a = 0; $a < count($c); $a++) {
                if ($a != 0) {
                    $colms.=' , ';
                    $values.=' , ';
                }
                $colms.="`" . $c[$a] . "`";
                $values.="'" . mysql_result($res, $i, $c[$a]) . "'";
            }
            $sql2 = "INSERT INTO subjects_subjects ($colms)values($values)";
            file_put_contents('d', $sql2);
            $res2 = mysql_query($sql2);
            $id = mysql_result($res, $i, $c[0]);
            if ($res2) {
                $s = "delete from subjects_archive where id='$id' ";
                mysql_query($s);
            }
            $i++;
        }
    }
}

function createDateFilter() {
    $str = '';
    $str.=From . ': <input name="date_s" class="dateF datefilter" value="' . $_REQUEST['date_s'] . '">';
    $str.=To . ' : <input name="date_e" class="dateF datefilter" value="' . $_REQUEST['date_e'] . '">';
    return $str;
}

function reciveDatePars($colume, $and = '', $dateType = 'n') {//s=timestamp or n=normal date
    if ($_REQUEST['date_s'] != '' || $_REQUEST['date_e'] != '')
        $Q = ' ' . $and . ' ';
    $f = 0;
    //start date
    if (isset($_REQUEST['date_s']) && $_REQUEST['date_s'] != '') {
        if ($dateType == 's') {
            $ds = convToTimeStamp($_REQUEST['date_s']);
        }
        if ($dateType == 'n') {
            $ds = $_REQUEST['date_s'] . ' 00:00:00';
        }
        $Q.=" " . $colume . " > '" . $ds . "' ";
        $f = 1;
    }

    //end date 
    if (isset($_REQUEST['date_e']) && $_REQUEST['date_e'] != '') {
        if ($dateType == 's') {
            $de = convToTimeStamp($_REQUEST['date_e']) + 86400;
        }
        if ($dateType == 'n') {
            $de = $_REQUEST['date_e'] . ' 23:59:59';
        }
        if ($f == 1)
            $Q.=' and ';
        $Q.=" " . $colume . " < '" . $de . "' ";
    }
    return $Q;
}

function reciveDatePars2($colume, $and = '', $dateType = 'n') {//s=timestamp or n=normal date
    if ($_REQUEST['date_s'] != '' || $_REQUEST['date_e'] != '')
        $Q = ' ' . $and . ' ';
    $f = 0;
    //start date
    if (isset($_REQUEST['date_s']) && $_REQUEST['date_s'] != '') {
        if ($dateType == 's') {
            $ds = convToTimeStamp($_REQUEST['date_s']);
        }
        if ($dateType == 'n') {
            $ds = $_REQUEST['date_s'] . ' 00:00:00';
        }
        $Q.=" " . $colume . " >= '" . $ds . "' ";
        $f = 1;
    }

    //end date 
    if (isset($_REQUEST['date_e']) && $_REQUEST['date_e'] != '') {
        if ($dateType == 's') {
            $de = convToTimeStamp($_REQUEST['date_e']) + 86400;
        }
        if ($dateType == 'n') {
            $de = $_REQUEST['date_e'] . ' 23:59:59';
        }
        if ($f == 1)
            $Q.=' and ';
        $Q.=" " . $colume . " =< '" . $de . "' ";
    }
    return $Q;
}

//********************Users*******************************
function getActModule() {
    global $CMSLang;
    $arr = array('G' => 0, 'M' => 0, 'GN' => '', 'MN' => '');
    $page = getPageName();
    $sql = "select t1.g_id,t1.m_id ,t2.name_$CMSLang , t3.name_$CMSLang 
		from login_modules t1 , login_modules t2 ,login_modules t3 where 
		t1.file='$page' and 
		t1.active=1 and 
		t1.m_id!=0 and 
		t1.m_id=t2.id and 
		t2.g_id=t3.id		
		limit 1";
    $res = mysql_query($sql);
    $rows = mysql_num_rows($res);
    if ($rows > 0) {
        $arr['G'] = mysql_result($res, 0, 't1.g_id');
        $arr['M'] = mysql_result($res, 0, 't1.m_id');
        $arr['MN'] = mysql_result($res, 0, 't2.name_' . $CMSLang);
        $arr['GN'] = mysql_result($res, 0, 't3.name_' . $CMSLang);
    }
    return $arr;
}

function getPageName($page = '') {
    if ($page != '') {
        $url = $page;
    } else {
        $url = $_SERVER['REQUEST_URI'];
    }
    $p = explode('/', $url);
    $p2 = explode('.', end($p));
    return $p2[0];
}

function getAllowGroup($user_id) {
    $ids = '';
    $sql = "select m.g_id groups from 
	login_users u , 
	login_groups g ,
	login_groups_permissions p , 
	login_modules m
	where 
	u.user_id='$user_id' and
	u.grp_id=g.grp_id and 
	p.group=g.grp_id and 
	p.module=m.id group by m.g_id";
    $res = mysql_query($sql);
    $rows = mysql_num_rows($res);
    $i = 0;
    if ($rows > 0) {
        while ($i < $rows) {
            $id = mysql_result($res, $i, 'groups');
            if ($i != 0)
                $ids.=',';
            $ids.=$id;
            $i++;
        }
    }
    return $ids;
}

function groupid($uid) {
    $sql = "select grp_id from login_users where user_id=$uid";

    $res = mysql_query($sql);
    while ($r = mysql_fetch_array($res)) {

        $m = $r['grp_id'];
    }
    return $m;
}

function getAllowModules($user_id, $groups) {
    $ids = '';
    $sql = "select m.id modules from 
	login_users u , 
	login_groups g ,
	login_groups_permissions p , 
	login_modules m
	where 
	u.user_id='$user_id' and
	u.grp_id=g.grp_id and 
	p.group=g.grp_id and 
	p.module=m.id and 
	m.g_id in ($groups) 
	
	";
    $res = mysql_query($sql);
    $rows = mysql_num_rows($res);
    $i = 0;
    if ($rows > 0) {
        while ($i < $rows) {
            $id = mysql_result($res, $i, 'modules');
            if ($i != 0)
                $ids.=',';
            $ids.=$id;
            $i++;
        }
    }
    return $ids;
}

/* * ******************************************************* */

function chechUserPermissions($page) {
    $page = getPageName($page);
    $user_id = $_SESSION['USER_ID'];
    if ($_SESSION["SAdmin"] == 1) {//Super admin
        return 1;
    } else if ($user_id == 1) {//Admin
        $sql = "select count(*)c from 
		login_modules g , 
		login_modules m ,
		login_modules f 
		where 
		f.file='$page' and 
		f.m_id=m.id and 
		f.g_id=g.id and 		
		m.active=1 and
		g.active=1 ";
        $res = mysql_query($sql);
        return mysql_result($res, 0, 'c');
    } else {
        $sql = "select count(*)c from 
		login_modules g , 
		login_modules m ,
		login_modules f ,
		login_users u,
		login_groups grp,
		login_groups_permissions p 
		where 
		f.file='$page' and 
		f.m_id=m.id and 
		f.g_id=g.id and 		
		m.active=1 and
		g.active=1 and		
		u.user_id='$user_id' and 
		u.active=1 and
		u.grp_id=grp.grp_id and 
		grp.grp_id=p.group and 
		p.module=m.id";
        $res = mysql_query($sql);
        return mysql_result($res, 0, 'c');
    }
}

function getTableFromId($id) {
    $out = 'x';
    $tables = array('subjects_subjects', 'subjects_archive');
    for ($i = 0; $i < count($tables); $i++) {
        $table = $tables[$i];
        $sql = "select count(*)c from $table where id='$id'";
        $res = mysql_query($sql);
        $rows = mysql_result($res, 0, 'c');
        if ($rows > 0)
            return $table;
    }
    return 'subjects_subjects';
}

function getColorSelector($c) {
    $str = '';
    $colors = array('82c03e', 'f14b1e', '00a8ff', 'e62d2d', '6443a1', 'f1a81e', '47ba6d', 'acacac', '3441e6', 'cc4bd1');
    $str.='<select name="color" class="color_selector" onchange="setColor()">';
    for ($i = 0; $i < count($colors); $i++) {
        $sel = '';
        if ($colors[$i] == $c)
            $sel = ' selected ';
        $str.='<option value="' . $colors[$i] . '" ' . $sel . ' style="background-color:#' . $colors[$i] . ';">#' . $colors[$i] . '</option>';
    }
    $str.='</select><script>setColor()</script>';
    return $str;
}

function checkPagePermision($file) {
    $out = 0;
    $group = $_SESSION['GRP'];
    if ($_SESSION["USER_ID"] == 0) {
        $out = 1;
    } else if ($_SESSION["USER_ID"] == 1) {
        $sql = "select count(*)c from login_modules g ,login_modules m ,login_modules f where	
		g.id=m.g_id and	
		m.id=f.m_id and 
		g.active=1 and
		m.active=1 and
		f.active=1 and
		f.file='$file'";
        $res = mysql_query($sql);
        if (mysql_result($res, 0, 'c')) {
            $out = 1;
        }
    } else {
        $user = $_SESSION['USER_ID'];
        $sql = "select count(*)c from login_modules m ,login_modules f , login_groups_permissions p where
		p.group='$group' and
		p.module=m.id and 
		m.id=f.m_id and 
		m.active=1 and
		f.active=1 and
		f.file='$file'
		";
        $res = mysql_query($sql);
        if (mysql_result($res, 0, 'c')) {
            $out = 1;
        }
    }
    return $out;
}

function newsForPublish() {
    if ($_SESSION["USER_ID"] > 1) {
        $per_cats_pub = get_news_cats($_SESSION['USER_ID'], 4);
        $q = " and category in (" . implode(',', $per_cats_pub) . ") ";
    }
    $sql = "select count(*)c from subjects_subjects where status=0 $q";
    $res = mysql_query($sql);
    return mysql_result($res, 0, 'c');
}

function checkboxChanger($table, $filed, $filed_id, $id, $value) {
    $Xvalue = !$value;
    return '
	<a href="../includes/checkbox.php?t=' . $table . '&fn=' . $filed . '&idn=' . $filed_id . '&idv=' . $id . '&fv=' . $Xvalue . '" 
	class="checkb" ><img src="' . _PREFICO . '_' . $value . '_.png" border="0"  fv="' . $Xvalue . '"/></a>';
}

function checkBoxList($table, $id_field, $value_field, $id_value, $field_name, $condition = '', $chars = 25) {
    $out = '';
    $uneq_string = randomStringUtil(5);
    if ($condition)
        $condition = " where " . $condition;
    $sql = "select $id_field , $value_field from $table $condition order by $value_field ASC";
    $res = mysql_query($sql);
    $rows = mysql_num_rows($res);
    if ($rows > 0) {
        $i = 0;
        while ($i < $rows) {
            $id = mysql_result($res, $i, $id_field);
            $name = mysql_result($res, $i, $value_field);
            $class = 'chebox_0';
            $che = '';
            if (is_array($id_value)) {
                if (in_array($id, $id_value)) {
                    $class = 'chebox_1';
                    $che = ' checked ';
                }
            }
            $out.='<div class="' . $class . '" onclick="chekBoxSel(\'' . $uneq_string . '\',' . $i . ')" id="chDiv' . $uneq_string . $i . '">
			<input type="checkbox" ' . $che . ' name="' . $field_name . '[]" id="ch' . $uneq_string . $i . '" value="' . $id . '" style="display:none" /> 
			<b>' . ucfirst(limit($name, $chars)) . '</b></div>';
            $i++;
        }
    }
    return $out;
}

function printFormIlement($id, $title, $type, $value, $req, $plus, $sub_id = '') {
    $out = '';
    $req_s = '';
    $class = '';
    $c1_width = 100;
    $c2_width = 700;
    if ($req) {
        $req_s = '*';
        $class = "required";
    }
    if ($sub_id != '') {
        $value = getTypeFiledVaslue($sub_id, $id);
    }
    switch ($type) {
        //Text	---------------------------------------------------------------------
        case (0):
            $out = '<tr><td  class="field_title" align="' . _xalign . '">' . $title . ' :</td>
			 <td width="5">' . $req_s . '</td>
			 <td width="' . $c2_width . '">
			 <input type="text" class="' . $class . '" name="f_' . $id . '" id="f_' . $id . '" value="' . $value . '"/></td>
			 </tr>';
            break;
        //Email	---------------------------------------------------------------------
        case (1):
            $out = '<tr><td width="' . $c1_width . '" class="field_title" align="right">' . $title . ' :</td>
			 <td width="5">' . $req_s . '</td>
			 <td width="' . $c2_width . '">
			 <input type="text" class="email ' . $class . '" name="f_' . $id . '" id="f_' . $id . '" value="' . $value . '"/></td>
			 </tr>';
            break;
        //Textarea	---------------------------------------------------------------------
        case (2):
            $out = '<tr><td width="' . $c1_width . '" class="field_title" align="right">' . $title . ' :</td>
			 <td width="5">' . $req_s . '</td>
			 <td width="' . $c2_width . '">
			 <textarea class="tf_texarea ' . $class . '" name="f_' . $id . '" id="f_' . $id . '">' . $value . '</textarea></td>
			 </tr>';
            break;
        //Checkbox	---------------------------------------------------------------------
        case (3):
            if ($sub_id != '') {
                $value = getTypeFiledVaslue($sub_id, $id);
                if ($value != 0)
                    $che = ' checked ';
            }
            if ($value = '')
                $value = 1;
            if ($req == 1)
                $che = ' checked ';
            $out = '<tr><td width="' . $c1_width . '" class="field_title" align="right">' . $title . ' :</td>
			 <td width="5"></td>
			 <td width="' . $c2_width . '">
			 <input type="checkbox" name="f_' . $id . '" id="f_' . $id . '" value="' . $value . '" ' . $che . '/></td>
			 </tr>';
            break;
        //Radio	---------------------------------------------------------------------
        case (4):
            $out = '<tr><td width="' . $c1_width . '" class="field_title" align="right">' . $title . ' :</td>
			 <td width="5"></td><td width="' . $c2_width . '">';
            $pluss = explode(',', $plus);
            for ($i = 0; $i < count($pluss); $i++) {
                $val = $pluss[$i];
                $che = '';
                if ($i == 0 || $val == $value)
                    $che = ' checked ';
                $out.='<div  class="tf_radio"><input type="radio" name="f_' . $id . '" value="' . $val . '" ' . $che . '/> ' . $val . '</div>';
            }

            $out.='</td></tr>';
            break;
        //List	---------------------------------------------------------------------
        case (5):
            $out = '<tr><td width="' . $c1_width . '" class="field_title" align="right">' . $title . ' :</td>
			<td width="5">' . $req_s . '</td>
			<td width="' . $c2_width . '">';
            $pluss = explode(',', $plus);
            $out.='<select name="f_' . $id . '" id="f_' . $id . '">';
            if ($req == 0)
                $out.='<option value="0"></option>';
            for ($i = 0; $i < count($pluss); $i++) {
                $val = $pluss[$i];
                $che = '';
                if ($val == $value)
                    $che = ' selected ';
                $out.='<option value="' . $val . '" ' . $che . '> ' . $val . '</option>';
            }
            $out.='</select>';
            $out.='</td></tr>';
            break;
    }
    return $out;
}

function saveTypeForm($subject_id, $type_id) {
    mysql_query("DELETE  from subjects_extra_data where subject_id='$subject_id'");
    $sql = "select * from subjects_types_fileds where type_id='$type_id'";
    $res = mysql_query($sql);
    $rows = mysql_num_rows($res);
    if ($rows > 0) {
        $i = 0;
        while ($i < $rows) {
            $filed_id = mysql_result($res, $i, 'id');
            $filed_type = mysql_result($res, $i, 'filed_type');
            //if($filed_type!=0){
            if ($filed_type == 3) {// 3=Checkbox
                if (isset($_REQUEST['f_' . $filed_id])) {
                    $val = addslashes($_REQUEST['f_' . $filed_id]);
                    if (!$val) {
                        $val = 1;
                    }
                } else {
                    $val = 0;
                }
            } else {
                $val = $_REQUEST['f_' . $filed_id];
            }
            mysql_query("INSERT INTO subjects_extra_data (`subject_id`,`type_filed_id`,`value`)values
				('$subject_id','$filed_id','$val')");
            //}
            $i++;
        }
    }
}

function getSubjectCats($subject_id) {
    $out = array();
    $res = mysql_query("select category_id from subjects_sub_cat where subject_id='$subject_id'");
    while ($row = mysql_fetch_array($res)) {
        array_push($out, $row['category_id']);
    }
    return $out;
}

function getSubjectLinks($subject_id) {
    $out = array();
    $res = mysql_query("select type_id from subjects_sub_type where subject_id='$subject_id'");
    while ($row = mysql_fetch_array($res)) {
        array_push($out, $row['type_id']);
    }
    return $out;
}

function getTypeFiledVaslue($sub_id, $type_filed_id) {
    $res = mysql_query("select value from subjects_extra_data where subject_id='$sub_id' and type_filed_id='$type_filed_id' limit 1");
    $rows = mysql_num_rows($res);
    if ($rows > 0) {
        return mysql_result($res, 0, 'value');
    }
}

function rewriteFilter($val) {
    $chrs = array('/', '&', '$', '_', ' ');
    for ($i = 0; $i < count($chrs); $i++) {
        $val = str_replace($chrs[$i], '-', $val);
    }
    $chrs2 = array(':', '?', '^', '%', '(', ')', '"', "'");
    for ($i = 0; $i < count($chrs2); $i++) {
        $val = str_replace($chrs2[$i], '', $val);
    }
    return $val;
}

function printAdminRank($id, $rank) {
    $rankV = 5;
    if ($rank == '') {
        $rank = '?';
    } else {
        $rank = $rank . '/' . $rankV;
    }
    $out = '';
    $out.='<div id="win_rank' . $id . '" class="admin_rank_win" >';
    for ($i = 1; $i <= $rankV; $i++) {
        $out.='<div onclick="changeRanl(' . $i . ',' . $id . ',' . $rankV . ')">' . $i . '</div>';
    }

    $out.='</div>';
    $out.='<div class="admin_rank" id="val_rank' . $id . '" onclick="showRankWin(' . $id . ')" title="Set Subject Rank">' . $rank . '</div> &nbsp; ' . Rank;
    return $out;
}

function viewYoutube($video, $w = 160, $h = 120) {
    $n = explode('v=', $video);
    if (count($n) > 1) {
        $v = explode('&', $n[1]);
        $video = 'http://www.youtube.com/embed/' . $v[0];
        return'<iframe width="' . $w . '" height="' . $h . '" src="' . $video . '" frameborder="0" allowfullscreen></iframe>';
    }
}

function getCMSLang() {
    $var = 'CMSLang';
    $var2 = 'VIEWLang';
    $CMSLang = '';
    $VIEWLang = '';
    $langsArray = array();
    $langsArrayView = array();
    global $langsArray, $langsArrayView, $CMSLang, $VIEWLang;

    if (isset($_REQUEST[$var])) {
        $CMSLang = $_REQUEST[$var];
    } else if (isset($_SESSION[$var])) {
        $CMSLang = $_SESSION[$var];
    }

    if (isset($_REQUEST[$var2])) {
        $VIEWLang = $_REQUEST[$var2];
    } else if (isset($_SESSION[$var2])) {
        $VIEWLang = $_SESSION[$var2];
    }

    $sql = "select * from  languages where active =1 ";
    $res = mysql_query($sql);
    $rows = mysql_num_rows($res);
    $validLang = 0;
    $validLangView = 0;
    if ($rows > 0) {
        $i = 0;
        while ($i < $rows) {
            $lang = mysql_result($res, $i, 'lang');
            $lang_name = mysql_result($res, $i, 'lang_name');
            $dl = mysql_result($res, $i, 'default_lang');
            $type = mysql_result($res, $i, 'type');
            if ($type == 'admin') {
                if ($dl == 1) {
                    $default_lang = $lang;
                }
                if ($CMSLang == $lang) {
                    $validLang = 1;
                }
                $langsArray[] = array($lang, $lang_name);
            } else {
                if ($dl == 1) {
                    $default_lang_view = $lang;
                }
                if ($VIEWLang == $lang) {
                    $validLangView = 1;
                }
                $langsArrayView[] = array($lang, $lang_name);
            }
            $i++;
        }
    }
    if ($validLang != 1) {
        $CMSLang = $default_lang;
    }
    if ($validLangView != 1) {
        $VIEWLang = $default_lang_view;
    }
    $_SESSION[$var] = $CMSLang;
    $_SESSION[$var2] = $VIEWLang;
}

function switcher($page, $table, $filed, $files_id, $id, $value) {
    return '
       <a href="../includes/' . $page . '.php?t=' . $table . '&fn=' . $filed . '&idn=' . $files_id . '&idv=' . $id . '&fv=' . !($value) . '"  class="checkb" >
       <img src="' . _PREFICO . '_' . $value . '_.png" border="0"  fv="' . !($value) . '"/>
       </a>';
}

function switcher2($page, $table, $filed, $files_id, $id, $value) {
    return '
       <a href="#" onclick="active(' . $id . ',' . $value . ')"  alt="Active/Inactive" title="Active/Inactive" >
       <img src="' . _PREFICO . 'pro_' . $value . '_.png" border="0"  fv="' . $value . '"/>
       </a>';
}

function switcher5($page, $table, $status_state, $news_id, $user_id, $type_id, $type, $user, $news, $status) {

    return '
       <a href="' . $page . '.php?t=' . $table . '&fn=pub' . '&idn=' . $news_id . '&user=' . $user . '&type=' . $type . '&idv=' . $news . '&fv=' . !($status) . '"  class="checkb" >
       <img src="' . _PREFICO . '_' . $status . '_.png" border="0"  fv="' . !($status) . '"/>
       </a>';
}

function backTo($url) {
    return '<input type="button"  value="' . _GoBack . '" onclick=" document.location=\'' . $url . '\'" />';
}

function Export($url) {
    return '<input style="background-color:#c5161d;" type="button"  value="' . _Export . '" onclick=" document.location=\'' . $url . '\'" />';
}

function submit($value) {
    return '<input name="sub" type="submit"  value="' . $value . '" />';
}

function MakeSelecttFile($file, $filedName, $required = '') {
    return '<input type="Text" name="' . $filedName . '" size="50" value="' . stripslashes($file) . '" class="' . $required . '" style="width:400px"/> 
    <a href="javascript:mcImageManager.open(\'subForm\',\'' . $filedName . '\',\'\',\'\',{relative_urls : true});">[' . selectFile . ']</a>';
}

function subList($val = '', $name = 'type', $t, $pid, $n, $con = '') {
    $subjecttypes = array();

    $sql = "SELECT * from $t $con";
    $res = mysql_query($sql);
    $rows = mysql_num_rows($res);
    if ($rows > 0) {
        $i = 0;
        while ($i < $rows) {
            $subjecttypes[$i]['id'] = mysql_result($res, $i, 'id');
            $subjecttypes[$i][$pid] = mysql_result($res, $i, $pid);
            $subjecttypes[$i][$n] = mysql_result($res, $i, $n);
            $i++;
        }
    }
    $out = '<select name="' . $name . '"><option value="" ></option>';
    foreach ($subjecttypes as $value) {
        if ($value[$pid] == 0) {
            $sel = '';
            if ($val == $value['id'])
                $sel = ' selected ';
            $out.='<option value="' . $value['id'] . '" ' . $sel . ' >' . $value[$n] . '</option>';
            $out.= listSub($value['id'], $subjecttypes, 0, $val, $pid, $n);
        }
    }
    ?></div><?php
    $out.='</select>';
    return $out;
}

function listSub($cat, $subjecttypes, $level, $val, $pid, $n) {
    global $VIEWLang;
    $str = '';
    foreach ($subjecttypes as $value) {
        if ($value[$pid] == $cat) {
            $sel = '';
            if ($val == $value['id'])
                $sel = ' selected ';
            $str.='<option value="' . $value['id'] . '" ' . $sel . ' >' . str_repeat('&nbsp;', (4 * ($level + 1))) . $value[$n] . '</option>';
            $str.=listSub($value['id'], $subjecttypes, $level + 1, $val);
        }
    }
    return $str;
}

function ProCatList($val = '', $name = 'cat_id') {
    $catsData = array();
    $sql = "SELECT * from products_category ";
    $res = mysql_query($sql);
    $rows = mysql_num_rows($res);
    if ($rows > 0) {
        $i = 0;
        while ($i < $rows) {
            $catsData[$i]['id'] = mysql_result($res, $i, 'id');
            $catsData[$i]['p_id'] = mysql_result($res, $i, 'p_id');
            $catsData[$i]['name'] = mysql_result($res, $i, 'name_en');
            $catsData[$i]['form_id'] = mysql_result($res, $i, 'form_id');
            $catsData[$i]['photos'] = mysql_result($res, $i, 'photos');
            $i++;
        }
    }
    $out = '<select name="' . $name . '"><option value="" ></option>';
    foreach ($catsData as $value) {
        if ($value['p_id'] == 0) {
            $sel = '';
            if ($val == $value['id'])
                $sel = ' selected ';
            $out.='<option value="' . $value['id'] . '" ' . $sel . ' >' . $value['name'] . '</option>';
            $out.= listCat222($value['id'], $catsData, 0, $val);
        }
    }
    ?></div><?php
    $out.='</select>';
    return $out;
}

function listCat222($cat, $catsData, $level, $val) {
    global $VIEWLang;
    $str = '';
    foreach ($catsData as $value) {
        if ($value['p_id'] == $cat) {
            $sel = '';
            if ($val == $value['id'])
                $sel = ' selected ';
            $str.='<option value="' . $value['id'] . '" ' . $sel . ' >' . str_repeat('&nbsp;', (4 * ($level + 1))) . $value['name'] . '</option>';
            $str.=listCat222($value['id'], $catsData, $level + 1, $val);
        }
    }
    return $str;
}

function NotLinked($file_id) {
    $res = mysql_query("select count(*)c from login_modules where file!='' and id='$file_id'");
    return mysql_result($res, 0, 'c');
}

function likedHelpModules() {
    global $CMSLang;
    $out = '<select name="helpLink"><option value="0"></option>';

    $sql = "SELECT * FROM login_modules g , login_modules m , login_modules f  where 
	f.m_id!=0 and f.m_id=m.id and f.g_id=g.id order by g.ord ASC , m.ord ASC , f.id ASC ";
    $res = mysql_query($sql);
    $rows = mysql_num_rows($res);
    if ($rows > 0) {
        $i = 0;
        while ($i < $rows) {
            $id = mysql_result($res, $i, "f.id");
            $group = mysql_result($res, $i, "g.name_" . $CMSLang);
            $module = mysql_result($res, $i, "m.name_" . $CMSLang);
            $file = mysql_result($res, $i, "f.file");

            $res2 = mysql_query("select count(*)c from help_help where file_id='$id' and lang='$CMSLang'");
            if (mysql_result($res2, 0, 'c') == 0) {
                $out.='<option value="' . $id . '"><b>' . $group . '</b> &raquo; ' . $module . ' &raquo; ' . $file . '</option>';
            }
            $i++;
        }
    }
    $out.='</select>';
    return $out;
}

function emptyTable($table, $condition = '') {
    if ($condition != '')
        $Q = "where " . $condition;
    $sql = "delete from $table $Q ";
    @mysql_query($sql);
    if (count_items($table) == 0) {
        @mysql_query("ALTER TABLE  `$table` AUTO_INCREMENT =1 ");
    }
}

function recTablesTotal($tables) {
    $total = 0;
    $t = explode(',', $tables);
    for ($i = 0; $i < count($t); $i++) {
        $total+=count_items($t[$i]);
    }
    return $total;
}

function filesTotal($root, $staticFolders = array(), $first = 1) {
    $earray = array();
    $res = array($totalFiles = 0, $totalSize = 0);
    array_push($staticFolders, '.');
    array_push($staticFolders, '..');

    $files = array_diff(scandir($root), $staticFolders);
    foreach ($files as $file) {
        if (is_dir($root . $file)) {
            $funres = filesTotal($root . $file . '/', $earray, 0);
            $res[1]+=$funres[1];
            $res[0]+=$funres[0];
        } else {
            $res[1]+=filesize($root . $file);
            $res[0] ++;
        }
    }
    return $res;
}

function deleteFolder($root, $staticFolders = array(), $first = 1) {
    $earray = array();
    array_push($staticFolders, '.');
    array_push($staticFolders, '..');

    $files = array_diff(scandir($root), $staticFolders);
    foreach ($files as $file) {
        if (is_dir($root . $file)) {
            $funres = deleteFolder($root . $file . '/', $earray, 0);
        } else {
            @unlink($root . $file);
        }
    }
    if ($first == 0)
        (rmdir($root));
}

function fixFileSize($size) {
    $ex = 'Bit';
    if ($size > 1024) {
        $ex = 'KB';
        $size = ($size / 1024);
    }
    if ($size > 1024) {
        $ex = 'MB';
        $size = ($size / 1024);
    }
    return (intval($size * 10) / 10) . ' ' . $ex;
}

function viewFileSize($file) {
    $ex = 'Bit';
    if (file_exists($file)) {
        $size = filesize($file);
        if ($size > 1024) {
            $ex = 'KB';
            $size = ($size / 1024);
        }
        if ($size > 1024) {
            $ex = 'MB';
            $size = ($size / 1024);
        }
    }
    return (intval($size * 10) / 10) . ' ' . $ex;
}

function selectFile($filedName, $files = '', $multiple = 1, $onlyImages = 1) {
    $str = '<link type="text/css" rel="stylesheet" href="../includes/file_selector/css/style.css"/>	
	<script src="../includes/file_selector/js/script.js"></script>	
	<script src="../includes/upload_photo/script/jquery.uploadify.min.js"></script>
	<script>$(document).ready(function(){loadSelFiles(\'' . $filedName . '\',\'' . $files . '\',' . $multiple . ',' . $onlyImages . ');})</script>
	<div id="sf_' . $filedName . '"></div>';
    return $str;
}

/*
  function cleanStringURL($text,$parameters=''){//$parameters='p,e,t' => p=password,e=email,t=text editor
  $pars=explode(',',$parameters);

  if(!in_array('p',$pars)){
  $nonASCII = array('$','!','#','%','^','&','*','=');
  if(!in_array('e',$pars)){
  array_push($nonASCII ,'@');
  }
  }
  $nonASCII_text = str_replace($nonASCII, "",$text);
  if(!in_array('t',$pars)){$new_text = strip_tags($nonASCII_text);}

  $striped_text = addslashes($nonASCII_text);
  $escaped_text = mysql_real_escape_string($striped_text);
  return $escaped_text;
  } */

function buildTree(Array $data, $parent = 0) {

    $tree = array();
    foreach ($data as $d) {
        if ($d['parentId'] == $parent) {
            $children = buildTree($data, $d['id']);
            // set a trivial key
            if (!empty($children)) {
                $d['_children'] = $children;
            }
            $tree[] = $d;
        }
    }
    return $tree;
}

// print_r($tree);

function printTreeWithIdAndPer($per_cats_ids, $id, $tree, $r = 0, $p = null) {
    $lang = "ar";
    foreach ($tree as $i => $t) {
        if ($t['parentId'] == 0) {
            $style = "style='color:red;'";
        } else
            $style = '';
        if ($id == $t['id']) {
            $dash = ($t['parentId'] == 0) ? '' : str_repeat('-', $r) . ' ';
            //$r = 0;
            if (in_array($t['id'], $per_cats_ids))
                printf("\t<option $style value='%d' selected >%s%s</option>\n", $t['id'], $dash, $t['name_' . $lang]);
        }

        else {
            $dash = ($t['parentId'] == 0) ? '' : str_repeat('&nbsp;', $r) . ' ';
            if (in_array($t['id'], $per_cats_ids)) {
                //  file_put_contents('sdfsdf.txt',  json_encode($per_cats_ids));
                // $r = 0;
                printf("\t<option $style value='%d'>%s%s</option>\n", $t['id'], $dash, $t['name_' . $lang]);
            }
        }

        if ($t['parentId'] == $p) {
            // reset $r
            $r = 0;
        }
        if (isset($t['_children'])) {
            printTreeWithIdAndPer($per_cats_ids, $id, $t['_children'], ++$r, $t['parentId']);
        }
    }
}

function printTreeWithId($lng, $id, $tree, $r = 0, $p = null) {
    $lang = $lng;
    foreach ($tree as $i => $t) {
        if ($t['p_id'] == 0) {
            $style = "style='color:red;'";
        } else
            $style = '';
        $dash = ($t['p_id'] == 0) ? '' : str_repeat('&nbsp;&nbsp;&nbsp;', $r) . '&nbsp;&nbsp;';
        if ($id == $t['id']) {

            //$r = 0;
            printf("\t<option $style value='%d' selected >%s%s</option>\n", $t['id'], $dash, $t['name_' . $lang]);
        } else {
            // $r = 0;
            printf("\t<option $style value='%d'>%s%s</option>\n", $t['id'], $dash, $t['name_' . $lang]);
        }

        if ($t['p_id'] == $p) {
            // reset $r
            $r = 0;
        }
        if (isset($t['_children'])) {
            printTreeWithId($lng, $id, $t['_children'], ++$r, $t['p_id']);
        }
    }
}

function printTreeWithIdandPermission($per, $id, $tree, $r = 0, $p = null) {
    $lang = "ar";
    foreach ($tree as $i => $t) {
        if (in_array($t['id'], $per) && $t['parentId'] == 0) {

            if ($id == $t['id']) {

                $dash = ($t['parentId'] == 0) ? '' : str_repeat('&nbsp;', $r) . ' ';
                printf("\t<option value='%d' selected >%s%s</option>\n", $t['id'], $dash, $t['name_' . $lang]);
            } else {
                printf("\t<option value='%d'>%s%s</option>\n", $t['id'], $dash, $t['name_' . $lang]);
            }
            if ($t['parentId'] == $p) {
                // reset $r
                $r = 0;
            }
            if (isset($t['_children'])) {
                printTreeWithIdandPermission($t['_children'], $id, $t['_children'], ++$r, $t['parentId']);
            }
        }
    }
}

function printTree($tree, $r = 0, $p = null) {
    $lang = "ar";
    foreach ($tree as $i => $t) {
        $dash = ($t['parentId'] == 0) ? '' : str_repeat('&nbsp;', $r) . ' ';
        printf("\t<option value='%d'>%s%s</option>\n", $t['id'], $dash, $t['name_' . $lang]);




        if ($t['parentId'] == $p) {
            // reset $r
            $r = 0;
        }
        if (isset($t['_children'])) {
            printTree($id, $t['_children'], ++$r, $t['parentId']);
        }
    }
}

function nlist2($c, $typeTable, $query, $VIEWLang, $action, $id, $parentId, $name_en, $name_ar, $link, $seo, $static, $active, $this_Id, $this_Pid, $this_Link = "", $this_seo = "", $this_Order = "") {
    $str = '<!--[if lt IE 7]> <html lang="en" class="lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>    <html lang="en" class="lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>    <html lang="en" class="lt-ie9"> <![endif]-->
<!--[if IE 9]>    <html lang="en" class="ie9"> <![endif]-->
<!--[if gt IE 9]><!--> <html lang="en"> <!--<![endif]-->
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
<link rel="stylesheet" type="text/css" href="../common/css/style.css" />';
    $str .= "  <form method='post' name='main' action='" . $_SERVER['PHP_SELF'] . "' enctype='multipart/form-data'>

        <input type='hidden' name='action' value=''>
        <input type='hidden' name='" . $parentId . "' size='20' value='" . $parentId . "' />
  <input type='hidden' name='college_id' size='20' value='" . $c . "' />";
    $str.= '<TABLE CELLSPACING="0" CELLPADDING="0" BORDER="0" WIDTH="100%" ID="list" style="direction:ltr;">
            <thead>
                <TR>
      <th width="650">' . print_AdminRecordHeadName("Name", Name, $sb, $so, $static_order) . '</th>';
//  if ($this_seo != "")
//        $str.= '<th width="250">' . print_AdminRecordHead($this_seo, $this_seo, $sb, $so, $static_order) . '</th>';
//   
//    if ($this_Link != "")
//        $str.= '<th width="150">' . print_AdminRecordHead($this_Link, $this_Link, $sb, $so, $static_order) . '</th>';
    if ($_SESSION["SAdmin"] == 1)
        $st = "Static";
    else
        $st = "";

    $str.='   <th width="40">' . $st . '</th>

                    <th width="40">' . print_AdminRecordHead("active", _active, $sb, $so) . '</th>
                    <th width="40">&nbsp;</th>
                    <th width="30"><div class="ch_all"><input type="checkbox"  ch_all /></div></th>
            </TR>
            </thead>';
    if ($action == "Enter") {
        if (1) {

            $str.='    <TR>
                   
<TD><input type="Text" name="name_en" size="20" placeholder="english" value="' . stripslashes($name_en) . '"/ ><input type="Text" name="name_ar" size="20" placeholder="arabic" value="' . stripslashes($name_ar) . '"/>
</td>
    
';
            if ($this_seo != "") {
                $str.=' <td><input type="Text" name="' . $this_seo . '" size="20" placeholder="Seo" "/ ></td>';
            }

            if ($this_Link != "") {
                $str.= "<td>" . getLinks($link, $this_Link) . "</td>";
            }
            $str.='
                    
                    <TD align="center">' . print_save_icon("Insert") . '</TD>
                    <TD align="center">&nbsp;</TD>
</TR>';
        } else {
            $str.='    <TR>
                   
<TD><input type="Text" name="name_" size="20" placeholder="arabic" value="' . stripslashes($name) . '"/></td>
    ';
            if ($this_seo != "") {
                $str.=' <td><input type="Text" name="' . $this_seo . '" size="20" placeholder="' . $this_seo . '" "/ ></td>';
            }

            if ($this_Link != "") {
                $str.="<td>" . getLinks($link, $this_Link) . "</td>";
            }
            $str.='<TD></TD>
                    
                    <TD align="center">' . print_save_icon("Insert") . '</TD>
                    <TD align="center">&nbsp;</TD>
</TR>';
        }
    }
    $str.='   </table> 
         <div class="cf nestable-lists">
        <div class="dd" id="nestable">
            <ol class="dd-list">';

    //file_put_contents('sss.txt',"aa");
    while ($row = mysql_fetch_array($query)) {
        $id = $row[$this_Id];
        $ord = $row[$this_Order];
        $name_en = $row['name_en'];
        $name_ar = $row['name_ar'];
        $parentId = $row[$this_Pid];
        $active = $row["active"];
        $static = $row["static"];
        $c = $row["college_id"];
        $link = $row[$this_Link];
        $seo = $row[$this_seo];
        $str.= '   <li class="dd-item dd3-item" data-id="' . $id . '"  data-order="' . $ord . '">
                           <div class="dd-handle dd3-handle"></div>
                           <div          class="dd3-content">';


        if ($action == "Edit" && $_REQUEST[$this_Id] == $id) {
            $str.='   <div id="' . $id . '">'
                    . ' <input type="Hidden" name="' . $this_Id . '"size="20" value="' . $id . '" />'
                    . '<input type="hidden" name="college_id" size="20" value="' . $c . '" />'
                    . ' <div class="right">  &nbsp;&nbsp;&nbsp;   ' . print_delete_ckbox($id) . '</div>'
                    . ' <div class="right">  ' . print_save_icon("Update") . '</div>'
                    . ' <div class="left"> <input type="Text" name="name_en" size="20" value="' . $name_en . '"/> '
                    . ' <input type="Text" name="name_ar" size="20" value="' . $name_ar . '"/> </div>';
            if ($this_Link != "") {
                $str.=' <div class="left1">' . getLinks($link, $this_Link) . '  </div>';
            }
            if ($this_seo != "") {
                $str.=' <div class="left1"><input type="Text" name="' . $this_seo . '" size="20" value="' . $seo . '"/> </div>';
            }
            $str.='  </div>';
        } else {
            $str.='  <div id="' . $id . '">     <div class="right">';
            if ($static == 0 || $_SESSION["SAdmin"] == 1) {
                $str.="&nbsp;&nbsp;" . print_delete_ckbox($id) . "";
            } else
                $str.="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ";

            $str.=" </div> <div class='right'>&nbsp;&nbsp;" . print_edit_icon($_SERVER['PHP_SELF'] . "?action=Edit&college_id=" . $c . "&" . $this_Id . "=" . $id . "&" . $this_Pid . "=" . $parentId) . "</div>";
            $str.= " <div class='right'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; " . switcher('checkbox', $typeTable, 'active', $this_Id, $id, $active) . " &nbsp;&nbsp;&nbsp;</div>";
            if ($_SESSION["SAdmin"] == 1) {
                $sw = switcher("checkbox", $typeTable, "static", $this_Id, $id, $static);
                $str.= '<div class="right">';
                $str.=$sw;
                $str.= '</div>';
            }

            //if ($this_Link != "") 

            $str.=' <div class="right-link">' . print_widget_icon3('pub_' . $id, $VIEWLang, $c) . ' </div>';


            $str.=' <div class="right"> ' . print_seo_icon('pub_' . $id) . ' &nbsp;&nbsp;&nbsp;</div>';

            $str.= "<div style='float:left;width: 521px;'> " . stripslashes($name_en) . "/" . stripslashes($name_ar) . "</div>";


            $str.= ' </div>';
        }
        $str.= ' </div>';

        $str.= ' <ol class="dd-list">';

        $id = $row[$this_Id];
        $query1 = mysql_query("SELECT * FROM $typeTable WHERE  $this_Pid = '$id' order by $this_Order ");
        while ($row1 = mysql_fetch_array($query1)) {
            $id = $row1[$this_Id];
            $ord = $row1[$this_Order];
            $name_en = $row1['name_en'];
            $name_ar = $row1['name_ar'];
            $parentId = $row1[$this_Pid];
            $active = $row1["active"];
            $static = $row1["static"];
            $c = $row1["college_id"];
            $link = $row1[$this_Link];
            $seo = $row1[$this_seo];

            $str.= '   <li class="dd-item dd3-item" data-id="' . $id . '" data-order="' . $ord . '">
                           <div class="dd-handle dd3-handle"></div>
                           <div          class="dd3-content">';


            if ($action == "Edit" && $_REQUEST[$this_Id] == $id) {
                $str.='   <div id="' . $id . '">'
                        . ' <input type="Hidden" name="' . $this_Id . '"size="20" value="' . $id . '" />'
                        . '<input type="hidden" name="college_id" size="20" value="' . $c . '" />'
                        . ' <div class="right">  &nbsp;&nbsp;&nbsp;   ' . print_delete_ckbox($id) . '</div>'
                        . ' <div class="right">  ' . print_save_icon("Update") . '</div>'
                        . ' <div class="left"> <input type="Text" name="name_en" size="20" value="' . $name_en . '"/> '
                        . ' <input type="Text" name="name_ar" size="20" value="' . $name_ar . '"/> </div>';
                if ($this_Link != "") {
                    $str.=' <div class="left1">' . getLinks($link, $this_Link) . '  </div>';
                }
                if ($this_seo != "") {
                    $str.=' <div class="left1"><input type="Text" name="' . $this_seo . '" size="20" value="' . $seo . '"/> </div>';
                }
                $str.='  </div>';
            } else {
                $str.='  <div id="' . $id . '">     <div class="right">';
                if ($static == 0 || $_SESSION["SAdmin"] == 1) {
                    $str.="&nbsp;&nbsp;" . print_delete_ckbox($id) . "";
                } else
                    $str.="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ";

                $str.=" </div> <div class='right'>&nbsp;&nbsp;" . print_edit_icon($_SERVER['PHP_SELF'] . "?action=Edit&college_id=" . $c . "&" . $this_Id . "=" . $id . "&" . $this_Pid . "=" . $parentId) . "</div>";
                $str.= " <div class='right'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; " . switcher('checkbox', $typeTable, 'active', $this_Id, $id, $active) . " &nbsp;&nbsp;&nbsp;</div>";
                if ($_SESSION["SAdmin"] == 1) {
                    $sw = switcher("checkbox", $typeTable, "static", $this_Id, $id, $static);
                    $str.= '<div class="right">';
                    $str.=$sw;
                    $str.= '</div>';
                }

                $str.=' <div class="right-link">' . print_widget_icon3('pub_' . $id, $VIEWLang, $c) . ' </div>';


                $str.=' <div class="right"> ' . print_seo_icon('pub_' . $id) . ' &nbsp;&nbsp;&nbsp;</div>';

                $str.= "<div style='float:left;width: 521px;'> " . stripslashes($name_en) . "/" . stripslashes($name_ar) . "</div>";


                $str.= ' </div>';
            }
            $str.= ' </div>';

            $str.= ' <ol class="dd-list">';

            $id2 = $row1[$this_Id];
            $query2 = mysql_query("SELECT * FROM $typeTable WHERE  $this_Pid = '$id2' order by $this_Order  ");
            while ($row2 = mysql_fetch_array($query2)) {
                $id = $row2[$this_Id];
                $name_en = $row2['name_en'];
                $name_ar = $row2['name_ar'];
                $ord = $row2[$this_Order];
                $parentId = $row2[$this_Pid];
                $active = $row2["active"];
                $static = $row2["static"];
                $c = $row2["college_id"];
                $link = $row2[$this_Link];
                $seo = $row2[$this_seo];
                $str.= '   <li class="dd-item dd3-item" data-id="' . $id . '">
                           <div class="dd-handle dd3-handle"></div>
                           <div          class="dd3-content">';


                if ($action == "Edit" && $_REQUEST[$this_Id] == $id) {
                    $str.='   <div id="' . $id . '">'
                            . ' <input type="Hidden" name="' . $this_Id . '"size="20" value="' . $id . '" />'
                            . '<input type="hidden" name="college_id" size="20" value="' . $c . '" />'
                            . ' <div class="right">  &nbsp;&nbsp;&nbsp;   ' . print_delete_ckbox($id) . '</div>'
                            . ' <div class="right">  ' . print_save_icon("Update") . '</div>'
                            . ' <div class="left"> <input type="Text" name="name_" size="20" value="' . $name . '"/>  </div>';
                    if ($this_Link != "") {
                        $str.=' <div class="left1">' . getLinks($link, $this_Link) . '  </div>';
                    }
                    if ($this_seo != "") {
                        $str.=' <div class="left1"><input type="Text" name="' . $this_seo . '" size="20" value="' . $seo . '"/> </div>';
                    }
                    $str.='  </div>';
                } else {
                    $str.='  <div id="' . $id . '">     <div class="right">';
                    if ($static == 0 || $_SESSION["SAdmin"] == 1) {
                        $str.="&nbsp;&nbsp;" . print_delete_ckbox($id) . "";
                    } else
                        $str.="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ";

                    $str.=" </div> <div class='right'>&nbsp;&nbsp;" . print_edit_icon($_SERVER['PHP_SELF'] . "?action=Edit&college_id=" . $c . "&" . $this_Id . "=" . $id . "&" . $this_Pid . "=" . $parentId) . "</div>";
                    $str.= " <div class='right'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; " . switcher('checkbox', $typeTable, 'active', $this_Id, $id, $active) . " &nbsp;&nbsp;&nbsp;</div>";
                    if ($_SESSION["SAdmin"] == 1) {
                        $sw = switcher("checkbox", $typeTable, "static", $this_Id, $id, $static);
                        $str.= '<div class="right">';
                        $str.=$sw;
                        $str.= '</div>';
                    }

                    $str.=' <div class="right-link">' . print_widget_icon3('pub_' . $id, $VIEWLang, $c) . ' </div>';


                    $str.=' <div class="right"> ' . print_seo_icon('pub_' . $id) . ' &nbsp;&nbsp;&nbsp;</div>';

                    $str.= "<div style='float:left;width: 521px;'> " . stripslashes($name_en) . "/" . stripslashes($name_ar) . "</div>";


                    $str.= ' </div>';
                }
                $str.= ' </div>';

                $str.= ' <ol class="dd-list">';

                $id3 = $row2[$this_Id];
                $query3 = mysql_query("SELECT * FROM $typeTable WHERE  $this_Pid = '$id3' order by $this_Order ");
                while ($row3 = mysql_fetch_array($query3)) {
                    $id = $row3[$this_Id];
                    $name_en = $row3['name_en'];
                    $name_ar = $row3['name_ar'];
                    $ord = $row3[$this_Order];
                    $parentId = $row3[$this_Pid];
                    $active = $row3["active"];
                    $static = $row3["static"];
                    $link = $row3[$this_Link];
                    $c = $row3["college_id"];
                    $seo = $row3[$this_seo];
                    $str.= '   <li class="dd-item dd3-item" data-id="' . $id . '" data-order="' . $ord . '">
                           <div class="dd-handle dd3-handle"></div>
                           <div          class="dd3-content">';


                    if ($action == "Edit" && $_REQUEST[$this_Id] == $id) {
                        $str.='   <div id="' . $id . '">'
                                . ' <input type="Hidden" name="' . $this_Id . '"size="20" value="' . $id . '" />'
                                . '<input type="hidden" name="college_id" size="20" value="' . $c . '" />'
                                . ' <div class="right">  &nbsp;&nbsp;&nbsp;   ' . print_delete_ckbox($id) . '</div>'
                                . ' <div class="right">  ' . print_save_icon("Update") . '</div>'
                                . ' <div class="left"> <input type="Text" name="name_" size="20" value="' . $name . '"/>  </div>';
                        if ($this_Link != "") {
                            $str.=' <div class="left1">' . getLinks($link, $this_Link) . '  </div>';
                        }
                        $str.='  </div>';
                    } else {
                        $str.='  <div id="' . $id . '">     <div class="right">';
                        if ($static == 0 || $_SESSION["SAdmin"] == 1) {
                            $str.="&nbsp;&nbsp;" . print_delete_ckbox($id) . "";
                        } else
                            $str.="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ";

                        $str.=" </div> <div class='right'>&nbsp;&nbsp;" . print_edit_icon($_SERVER['PHP_SELF'] . "?action=Edit&college_id=" . $c . "&" . $this_Id . "=" . $id . "&" . $this_Pid . "=" . $parentId) . "</div>";
                        $str.= " <div class='right'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; " . switcher('checkbox', $typeTable, 'active', $this_Id, $id, $active) . " &nbsp;&nbsp;&nbsp;</div>";
                        if ($_SESSION["SAdmin"] == 1) {
                            $sw = switcher("checkbox", $typeTable, "static", $this_Id, $id, $static);
                            $str.= '<div class="right">';
                            $str.=$sw;
                            $str.= '</div>';
                        }

                        $str.=' <div class="right-link">' . print_widget_icon3('pub_' . $id, $VIEWLang, $c) . ' </div>';


                        $str.=' <div class="right"> ' . print_seo_icon('pub_' . $id) . ' &nbsp;&nbsp;&nbsp;</div>';

                        $str.= "<div style='float:left;width: 521px;'> " . stripslashes($name_en) . "/" . stripslashes($name_ar) . "</div>";


                        $str.= ' </div>';
                    }
                    $str.= ' </div>';

                    $str.= ' <ol class="dd-list">';
                    $id4 = $row3[$this_Id];
                    $query4 = mysql_query("SELECT * FROM $typeTable WHERE  $this_Pid = '$id4' order by $this_Order ");
                    while ($row4 = mysql_fetch_array($query4)) {
                        $id = $row4[$this_Id];
                        $name_en = $row4['name_en'];
                        $name_ar = $row4['name_ar'];

                        $ord = $row4[$this_Order];
                        $parentId = $row4[$this_Pid];
                        $active = $row4["active"];
                        $static = $row4["static"];
                        $link = $row4[$this_Link];
                        $seo = $row4[$this_seo];
                        $str.= '   <li class="dd-item dd3-item" data-id="' . $id . '" data-order="' . $ord . '">
                           <div class="dd-handle dd3-handle"></div>
                           <div          class="dd3-content">';


                        if ($action == "Edit" && $_REQUEST[$this_Id] == $id) {
                            $str.='   <div id="' . $id . '">'
                                    . ' <input type="Hidden" name="' . $this_Id . '"size="20" value="' . $id . '" />'
                                    . '<input type="hidden" name="college_id" size="20" value="' . $c . '" />'
                                    . ' <div class="right">  &nbsp;&nbsp;&nbsp;   ' . print_delete_ckbox($id) . '</div>'
                                    . ' <div class="right">  ' . print_save_icon("Update") . '</div>'
                                    . ' <div class="left"> <input type="Text" name="name_" size="20" value="' . $name . '"/>  </div>';
                            if ($this_Link != "") {
                                $str.=' <div class="left1">' . getLinks($link, $this_Link) . '  </div>';
                            }
                            if ($this_seo != "") {
                                $str.=' <div class="left1"><input type="Text" name="' . $this_seo . '" size="20" value="' . $seo . '"/> </div>';
                            }
                            $str.='  </div>';
                        } else {
                            $str.='  <div id="' . $id . '">     <div class="right">';
                            if ($static == 0 || $_SESSION["SAdmin"] == 1) {
                                $str.="&nbsp;&nbsp;" . print_delete_ckbox($id) . "";
                            } else
                                $str.="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ";

                            $str.=" </div> <div class='right'>&nbsp;&nbsp;" . print_edit_icon($_SERVER['PHP_SELF'] . "?action=Edit&college_id=" . $c . "&" . $this_Id . "=" . $id . "&" . $this_Pid . "=" . $parentId) . "</div>";
                            $str.= " <div class='right'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; " . switcher('checkbox', $typeTable, 'active', $this_Id, $id, $active) . " &nbsp;&nbsp;&nbsp;</div>";
                            if ($_SESSION["SAdmin"] == 1) {
                                $sw = switcher("checkbox", $typeTable, "static", $this_Id, $id, $static);
                                $str.= '<div class="right">';
                                $str.=$sw;
                                $str.= '</div>';
                            }

                            if ($this_Link != "") {
                                $str.=' <div class="right-link"> ' . stripslashes($link) . '</div>';
                            }
                            if ($this_seo != "") {
                                $str.=' <div class="right"> ' . stripslashes($seo) . ' &nbsp;&nbsp;&nbsp;</div>';
                            }
                            $str.= "<div style='float:left;width: 521px;'> " . stripslashes($name_en) . "/" . stripslashes($name_ar) . "</div>";


                            $str.= ' </div>';
                        }
                        $str.= ' </div>';


                        $str.= ' <ol class="dd-list">';
                        $id5 = $row4[$this_Id];
                        $query5 = mysql_query("SELECT * FROM $typeTable WHERE  $this_Pid = '$id5' order by $this_Order ");
                        while ($row5 = mysql_fetch_array($query5)) {

                            $id = $row5[$this_Id];
                            // $name = $row5[$this_Name];
                            $name_en = $row5['name_en'];
                            $name_ar = $row5['name_ar'];

                            $ord = $row5[$this_Order];
                            $parentId = $row5[$this_Pid];
                            $active = $row5["active"];
                            $static = $row5["static"];
                            $link = $row5[$this_Link];
                            $seo = $row5[$this_seo];
                            $str.= '   <li class="dd-item dd3-item" data-id="' . $id . '" data-order="' . $ord . '">
                           <div class="dd-handle dd3-handle"></div>
                           <div          class="dd3-content">';


                            if ($action == "Edit" && $_REQUEST[$this_Id] == $id) {
                                $str.='   <div id="' . $id . '">'
                                        . ' <input type="Hidden" name="' . $this_Id . '"size="20" value="' . $id . '" />'
                                        . '<input type="hidden" name="college_id" size="20" value="' . $c . '" />'
                                        . ' <div class="right">  &nbsp;&nbsp;&nbsp;   ' . print_delete_ckbox($id) . '</div>'
                                        . ' <div class="right">  ' . print_save_icon("Update") . '</div>'
                                        . ' <div class="left"> <input type="Text" name="name_" size="20" value="' . $name . '"/>  </div>';
                                if ($this_Link != "") {
                                    $str.=' <div class="left1">' . getLinks($link, $this_Link) . '  </div>';
                                }
                                if ($this_seo != "") {
                                    $str.=' <div class="left1"><input type="Text" name="' . $this_seo . '" size="20" value="' . $seo . '"/> </div>';
                                }
                                $str.='  </div>';
                            } else {
                                $str.='  <div id="' . $id . '">     <div class="right">';
                                if ($static == 0 || $_SESSION["SAdmin"] == 1) {
                                    $str.="&nbsp;&nbsp;" . print_delete_ckbox($id) . "";
                                } else
                                    $str.="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ";

                                $str.=" </div> <div class='right'>&nbsp;&nbsp;" . print_edit_icon($_SERVER['PHP_SELF'] . "?action=Edit&college_id=" . $c . "&" . $this_Id . "=" . $id . "&" . $this_Pid . "=" . $parentId) . "</div>";
                                $str.= " <div class='right'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; " . switcher('checkbox', $typeTable, 'active', $this_Id, $id, $active) . " &nbsp;&nbsp;&nbsp;</div>";
                                if ($_SESSION["SAdmin"] == 1) {
                                    $sw = switcher("checkbox", $typeTable, "static", $this_Id, $id, $static);
                                    $str.= '<div class="right">';
                                    $str.=$sw;
                                    $str.= '</div>';
                                }

                                if ($this_Link != "") {
                                    $str.=' <div class="right-link"> ' . stripslashes($link) . '</div>';
                                }
                                if ($this_seo != "") {
                                    $str.=' <div class="right"> ' . stripslashes($seo) . ' &nbsp;&nbsp;&nbsp;</div>';
                                }
                                $str.= "<div style='float:left;width: 521px;'> " . stripslashes($name_en) . "/" . stripslashes($name_ar) . "</div>";


                                $str.= ' </div>';
                            }
                            $str.= ' </div>';


                            $str.= ' <ol class="dd-list">';
                            $id6 = $row5[$this_Id];
                            $query6 = mysql_query("SELECT * FROM $typeTable WHERE  $this_Pid = '$id6' order by $this_Order  ");
                            while ($row6 = mysql_fetch_array($query6)) {
                                $id = $row6[$this_Id];
                                $name_en = $row6['name_en'];
                                $name_ar = $row6['name_ar'];
                                $ord = $row6[$this_Order];
                                $parentId = $row6[$this_Pid];
                                $active = $row6["active"];
                                $static = $row6["static"];
                                $link = $row6[$this_Link];
                                $seo = $row6[$this_seo];
                                $str.= '   <li class="dd-item dd3-item" data-id="' . $id . '" data-order="' . $ord . '">
                           <div class="dd-handle dd3-handle"></div>
                           <div          class="dd3-content">';


                                if ($action == "Edit" && $_REQUEST[$this_Id] == $id) {
                                    $str.='   <div id="' . $id . '">'
                                            . ' <input type="Hidden" name="' . $this_Id . '"size="20" value="' . $id . '" />'
                                            . '<input type="hidden" name="college_id" size="20" value="' . $c . '" />'
                                            . ' <div class="right">  &nbsp;&nbsp;&nbsp;   ' . print_delete_ckbox($id) . '</div>'
                                            . ' <div class="right">  ' . print_save_icon("Update") . '</div>'
                                            . ' <div class="left"> <input type="Text" name="name_" size="20" value="' . $name . '"/>  </div>';
                                    if ($this_Link != "") {
                                        $str.=' <div class="left1">' . getLinks($link, $this_Link) . '  </div>';
                                    }
                                    if ($this_seo != "") {
                                        $str.=' <div class="left1"><input type="Text" name="' . $this_seo . '" size="20" value="' . $seo . '"/> </div>';
                                    }
                                    $str.='  </div>';
                                } else {
                                    $str.='  <div id="' . $id . '">     <div class="right">';
                                    if ($static == 0 || $_SESSION["SAdmin"] == 1) {
                                        $str.="&nbsp;&nbsp;" . print_delete_ckbox($id) . "";
                                    } else
                                        $str.="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ";

                                    $str.=" </div> <div class='right'>&nbsp;&nbsp;" . print_edit_icon($_SERVER['PHP_SELF'] . "?action=Edit&college_id=" . $c . "&" . $this_Id . "=" . $id . "&" . $this_Pid . "=" . $parentId) . "</div>";
                                    $str.= " <div class='right'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; " . switcher('checkbox', $typeTable, 'active', $this_Id, $id, $active) . " &nbsp;&nbsp;&nbsp;</div>";
                                    if ($_SESSION["SAdmin"] == 1) {
                                        $sw = switcher("checkbox", $typeTable, "static", $this_Id, $id, $static);
                                        $str.= '<div class="right">';
                                        $str.=$sw;
                                        $str.= '</div>';
                                    }
                                    if ($this_Link != "") {
                                        $str.=' <div class="right-link"> ' . stripslashes($link) . '</div>';
                                    }
                                    if ($this_seo != "") {
                                        $str.=' <div class="right"> ' . stripslashes($seo) . ' &nbsp;&nbsp;&nbsp;</div>';
                                    }
                                    $str.= "<div style='float:left;width: 521px;'> " . stripslashes($name_en) . "/" . stripslashes($name_ar) . "</div>";


                                    $str.= ' </div>';
                                }
                                $str.= ' </div>';

                                $str.= ' <ol class="dd-list">';
                                $id7 = $row6[$this_Id];
                                $query7 = mysql_query("SELECT * FROM $typeTable WHERE  $this_Pid = '$id7' order by $this_Order  ");
                                while ($row7 = mysql_fetch_array($query7)) {
                                    $id = $row7[$this_Id];
                                    $name_en = $row7['name_en'];
                                    $name_ar = $row7['name_ar'];

                                    $ord = $row7[$this_Order];
                                    $parentId = $row7[$this_Pid];
                                    $active = $row7["active"];
                                    $static = $row7["static"];
                                    $link = $row7[$this_Link];
                                    $seo = $row7[$this_seo];
                                    $str.= '   <li class="dd-item dd3-item" data-id="' . $id . '" data-order="' . $ord . '">
                           <div class="dd-handle dd3-handle"></div>
                           <div          class="dd3-content">';


                                    if ($action == "Edit" && $_REQUEST[$this_Id] == $id) {
                                        $str.='   <div id="' . $id . '">'
                                                . ' <input type="Hidden" name="' . $this_Id . '"size="20" value="' . $id . '" />'
                                                . '<input type="hidden" name="college_id" size="20" value="' . $c . '" />'
                                                . ' <div class="right">  &nbsp;&nbsp;&nbsp;   ' . print_delete_ckbox($id) . '</div>'
                                                . ' <div class="right">  ' . print_save_icon("Update") . '</div>'
                                                . ' <div class="left"> <input type="Text" name="name_" size="20" value="' . $name . '"/>  </div>';
                                        if ($this_Link != "") {
                                            $str.=' <div class="left1">' . getLinks($link, $this_Link) . '  </div>';
                                        }
                                        if ($this_seo != "") {
                                            $str.=' <div class="left1"><input type="Text" name="' . $this_seo . '" size="20" value="' . $seo . '"/> </div>';
                                        }
                                        $str.='  </div>';
                                    } else {
                                        $str.='  <div id="' . $id . '">     <div class="right">';
                                        if ($static == 0 || $_SESSION["SAdmin"] == 1) {
                                            $str.="&nbsp;&nbsp;" . print_delete_ckbox($id) . "";
                                        } else
                                            $str.="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ";

                                        $str.=" </div> <div class='right'>&nbsp;&nbsp;" . print_edit_icon($_SERVER['PHP_SELF'] . "?action=Edit&college_id=" . $c . "&" . $this_Id . "=" . $id . "&" . $this_Pid . "=" . $parentId) . "</div>";
                                        $str.= " <div class='right'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; " . switcher('checkbox', $typeTable, 'active', $this_Id, $id, $active) . " &nbsp;&nbsp;&nbsp;</div>";
                                        if ($_SESSION["SAdmin"] == 1) {
                                            $sw = switcher("checkbox", $typeTable, "static", $this_Id, $id, $static);
                                            $str.= '<div class="right">';
                                            $str.=$sw;
                                            $str.= '</div>';
                                        }

                                        if ($this_Link != "") {
                                            $str.=' <div class="right-link"> ' . stripslashes($link) . '</div>';
                                        }

                                        if ($this_seo != "") {
                                            $str.=' <div class="right"> ' . stripslashes($seo) . ' &nbsp;&nbsp;&nbsp;</div>';
                                        }
                                        $str.= "<div style='float:left;width: 521px;'> " . stripslashes($name_en) . "/" . stripslashes($name_ar) . "</div>";


                                        $str.= ' </div>';
                                    }
                                    $str.= ' </div>';


                                    $str.= ' <ol class="dd-list">';
                                    $id8 = $row7[$this_Id];
                                    $query8 = mysql_query("SELECT * FROM $typeTable WHERE  $this_Pid = '$id8' order by $this_Order  ");
                                    while ($row8 = mysql_fetch_array($query8)) {
                                        $id = $row8[$this_Id];
                                        $ord = $row8[$this_Order];
                                        $name_en = $row8['name_en'];
                                        $name_ar = $row8['name_ar'];

                                        $parentId = $row8[$this_Pid];
                                        $active = $row8["active"];
                                        $static = $row8["static"];
                                        $link = $row8[$this_Link];
                                        $seo = $row8[$this_seo];
                                        $str.= '   <li class="dd-item dd3-item" data-id="' . $id . '" data-order="' . $ord . '">
                           <div class="dd-handle dd3-handle"></div>
                           <div          class="dd3-content">';


                                        if ($action == "Edit" && $_REQUEST[$this_Id] == $id) {
                                            $str.='   <div id="' . $id . '">'
                                                    . ' <input type="Hidden" name="' . $this_Id . '"size="20" value="' . $id . '" />'
                                                    . '<input type="hidden" name="college_id" size="20" value="' . $c . '" />'
                                                    . ' <div class="right">  &nbsp;&nbsp;&nbsp;   ' . print_delete_ckbox($id) . '</div>'
                                                    . ' <div class="right">  ' . print_save_icon("Update") . '</div>'
                                                    . ' <div class="left"> <input type="Text" name="name_" size="20" value="' . $name . '"/>  </div>';
                                            if ($this_Link != "") {
                                                $str.=' <div class="left1">' . getLinks($link, $this_Link) . '  </div>';
                                            }
                                            if ($this_seo != "") {
                                                $str.=' <div class="left1"><input type="Text" name="' . $this_seo . '" size="20" value="' . $seo . '"/> </div>';
                                            }
                                            $str.='  </div>';
                                        } else {
                                            $str.='  <div id="' . $id . '">     <div class="right">';
                                            if ($static == 0 || $_SESSION["SAdmin"] == 1) {
                                                $str.="&nbsp;&nbsp;" . print_delete_ckbox($id) . "";
                                            } else
                                                $str.="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ";

                                            $str.=" </div> <div class='right'>&nbsp;&nbsp;" . print_edit_icon($_SERVER['PHP_SELF'] . "?action=Edit&college_id=" . $c . "&" . $this_Id . "=" . $id . "&" . $this_Pid . "=" . $parentId) . "</div>";
                                            $str.= " <div class='right'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; " . switcher('checkbox', $typeTable, 'active', $this_Id, $id, $active) . " &nbsp;&nbsp;&nbsp;</div>";
                                            if ($_SESSION["SAdmin"] == 1) {
                                                $sw = switcher("checkbox", $typeTable, "static", $this_Id, $id, $static);
                                                $str.= '<div class="right">';
                                                $str.=$sw;
                                                $str.= '</div>';
                                            }

                                            if ($this_Link != "") {
                                                $str.=' <div class="right-link"> ' . stripslashes($link) . '</div>';
                                            }
                                            if ($this_seo != "") {
                                                $str.=' <div class="right"> ' . stripslashes($seo) . ' &nbsp;&nbsp;&nbsp;</div>';
                                            }
                                            $str.= "<div style='float:left;width: 521px;'> " . stripslashes($name_en) . "/" . stripslashes($name_ar) . "</div>";


                                            $str.= ' </div>';
                                        }
                                        $str.= ' </div>';

                                        $str.= ' <ol class="dd-list">';
                                        $id9 = $row8[$this_Id];
                                        $query9 = mysql_query("SELECT * FROM $typeTable WHERE  $this_Pid = '$id9' order by $this_Order  ");
                                        while ($row9 = mysql_fetch_array($query9)) {
                                            $id = $row9[$this_Id];
                                            $ord = $row9[$this_Order];
                                            $name_en = $row9['name_en'];
                                            $name_ar = $row9['name_ar'];

                                            $parentId = $row9[$this_Pid];
                                            $active = $row9["active"];
                                            $static = $row9["static"];
                                            $link = $row9[$this_Link];
                                            $seo = $row9[$this_seo];
                                            $str.= '   <li class="dd-item dd3-item" data-id="' . $id . '" data-order="' . $ord . '">
                           <div class="dd-handle dd3-handle"></div>
                           <div          class="dd3-content">';


                                            if ($action == "Edit" && $_REQUEST[$this_Id] == $id) {
                                                $str.='   <div id="' . $id . '">'
                                                        . ' <input type="Hidden" name="' . $this_Id . '"size="20" value="' . $id . '" />'
                                                        . '<input type="hidden" name="college_id" size="20" value="' . $c . '" />'
                                                        . ' <div class="right">  &nbsp;&nbsp;&nbsp;   ' . print_delete_ckbox($id) . '</div>'
                                                        . ' <div class="right">  ' . print_save_icon("Update") . '</div>'
                                                        . ' <div class="left"> <input type="Text" name="name_" size="20" value="' . $name . '"/>  </div>';
                                                if ($this_Link != "") {
                                                    $str.=' <div class="left1">' . getLinks($link, $this_Link) . '  </div>';
                                                }
                                                if ($this_seo != "") {
                                                    $str.=' <div class="left1"><input type="Text" name="' . $this_seo . '" size="20" value="' . $seo . '"/> </div>';
                                                }
                                                $str.='  </div>';
                                            } else {
                                                $str.='  <div id="' . $id . '">     <div class="right">';
                                                if ($static == 0 || $_SESSION["SAdmin"] == 1) {
                                                    $str.="&nbsp;&nbsp;" . print_delete_ckbox($id) . "";
                                                } else
                                                    $str.="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ";

                                                $str.=" </div> <div class='right'>&nbsp;&nbsp;" . print_edit_icon($_SERVER['PHP_SELF'] . "?action=Edit&college_id=" . $c . "&" . $this_Id . "=" . $id . "&" . $this_Pid . "=" . $parentId) . "</div>";
                                                $str.= " <div class='right'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; " . switcher('checkbox', $typeTable, 'active', $this_Id, $id, $active) . " &nbsp;&nbsp;&nbsp;</div>";
                                                if ($_SESSION["SAdmin"] == 1) {
                                                    $sw = switcher("checkbox", $typeTable, "static", $this_Id, $id, $static);
                                                    $str.= '<div class="right">';
                                                    $str.=$sw;
                                                    $str.= '</div>';
                                                }


                                                if ($this_Link != "") {
                                                    $str.=' <div class="right-link"> ' . stripslashes($link) . '</div>';
                                                }
                                                if ($this_seo != "") {
                                                    $str.=' <div class="right"> ' . stripslashes($seo) . ' &nbsp;&nbsp;&nbsp;</div>';
                                                }

                                                $str.= "<div style='float:left;width: 521px;'> " . stripslashes($name_en) . "/" . stripslashes($name_ar) . "</div>";


                                                $str.= ' </div>';
                                            }


                                            $str.= ' </div>

                                                           </li> 
                                                                                                                                ';
                                        }
                                        $str.='</ol>


                                                                                                                        </li> 
                                                                                                                    ';
                                    }
                                    $str.='</ol>


                                                                                                                        </li> 
                                                                                                                    ';
                                }
                                $str.='</ol>


                                                                                                                        </li> 
                                                                                                                    ';
                            }
                            $str.='</ol>


                                                                                                                        </li> 
                                                                                                                    ';
                        }
                        $str.='</ol>


                                                                                                                        </li> 
                                                                                                                    ';
                    }
                    $str.='</ol>


                                                                                                                        </li> 
                                                                                                                    ';
                }
                $str.='</ol>


                                                                                                                        </li> 
                                                                                                                    ';
            }
            $str.='</ol>


                                                                                                                        </li> 
                                                                                                                    ';
        }
        $str.='</ol>


                                                                                                                        </li> 
                                                                                                                    ';
    }
    $str.='</ol>
            </div>

        </div>
    </form>';


    $str.='<form  id="idForm" method="post" >
        <textarea  cols="20" rows="20" style="display:none;" id="nestable-output" name="output_data"></textarea>
        <input type="hidden" name="table" value="' . $typeTable . '"/>
             <input type="hidden" name="parent"  value="' . $this_Pid . '"/>
              <input type="hidden" name="item_order" value="' . $this_Order . '"/>   
             <input type="hidden" name="table_id" value="' . $this_Id . '"/>
        <input id="ssss" style="display:none;"  type="submit" name="save_data"> 
        <img src="../common/css/loading.gif" id="img" style="display:none"/>
        <p>&nbsp;</p>';
    $str.=' <script src="../common/js/jquery.nestable.js"  charset="UTF-8"></script>'
            . ''
            . '<script>

            $(document).ready(function()
            {
                var updateOutput = function(e)
                {
                    var list = e.length ? e : $(e.target),
                            output = list.data("output");
                    if (window.JSON) {
                        output.val(window.JSON.stringify(list.nestable("serialize")));
                var url = "../common/newList2.php"; // the script where you handle the form input.
                $("#img").show();
                $.ajax({
                    type: "POST",
                    url: url,
                    data: $("#idForm").serialize(),
                    success: function(data)
                    {
                        $("#img").hide();
                    }
                });
                    } else {
                        output.val("JSON browser support required ");
                    }
                    
                };    
                $("#nestable").nestable({
                    group: 1
                })
                        .on("change", updateOutput);

                $("#nestable2").nestable({
                    group: 1
                })
                   .on("change", updateOutput);

                updateOutput($("#nestable").data("output", $("#nestable-output")));
                updateOutput($("#nestable2").data("output", $("#nestable2-output")));

                $("#nestable-$typeTable").on("click", function(e)
                {
                    var target = $(e.target),
                            action = target.data("action");
                    if (action === "expand-all") {
                        $(".dd").nestable("expandAll");
                    }
                    if (action === "collapse-all") {
                        $(".dd").nestable("collapseAll");
                    }

                    if (action === "save-all") {
                        alert("hello");
                    }


                });

                $("#nestable3").nestable();

            });
        </script>
';





    return $str;
}

function nlist($typeTable, $query, $VIEWLang, $action, $id, $parentId, $name, $link, $seo, $static, $active, $this_Id, $this_Name, $this_Pid, $this_Link = "", $this_seo = "", $this_Order = "") {
    $str = '<!--[if lt IE 7]> <html lang="en" class="lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>    <html lang="en" class="lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>    <html lang="en" class="lt-ie9"> <![endif]-->
<!--[if IE 9]>    <html lang="en" class="ie9"> <![endif]-->
<!--[if gt IE 9]><!--> <html lang="en"> <!--<![endif]-->
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
<link rel="stylesheet" type="text/css" href="../common/css/style.css" />';
    $str .= "  <form method='post' name='main' action='" . $_SERVER['PHP_SELF'] . "' enctype='multipart/form-data'>

        <input type='hidden' name='action' value=''>
        <input type='hidden' name='" . $parentId . "' size='20' value='" . $parentId . "' />";

    $str.= '<TABLE CELLSPACING="0" CELLPADDING="0" BORDER="0" WIDTH="100%" ID="list" style="direction:ltr;">
            <thead>
                <TR>
      <th width="650">' . print_AdminRecordHeadName($this_Name, Name, $sb, $so, $static_order) . '</th>';
    if ($this_seo != "")
        $str.= '<th width="250">' . print_AdminRecordHead($this_seo, $this_seo, $sb, $so, $static_order) . '</th>';

    if ($this_Link != "")
        $str.= '<th width="150">' . print_AdminRecordHead($this_Link, $this_Link, $sb, $so, $static_order) . '</th>';
    if ($_SESSION["SAdmin"] == 1)
        $st = "Static";
    else
        $st = "";

    $str.='   <th width="40">' . $st . '</th>

                    <th width="40">' . print_AdminRecordHead("active", _active, $sb, $so) . '</th>
                    <th width="40">&nbsp;</th>
                    <th width="30"><div class="ch_all"><input type="checkbox"  ch_all /></div></th>
            </TR>
            </thead>';
    if ($action == "Enter") {
        if (strpos($this_Name, 'ar') || strpos($this_Name, 'en')) {
            $str.='    <TR>
                   
<TD><input type="Text" name="name_ar" size="20" placeholder="arabic" value="' . stripslashes($name_ar) . '"/>
<input type="Text" name="name_en" size="20" placeholder="english" value="' . stripslashes($name_en) . '"/ ></td>
    
';
            if ($this_seo != "") {
                $str.=' <td><input type="Text" name="' . $this_seo . '" size="20" placeholder="Seo" "/ ></td>';
            }

            if ($this_Link != "") {
                $str.= "<td>" . getLinks($link, $this_Link) . "</td>";
            }
            $str.='
                    
                    <TD align="center">' . print_save_icon("Insert") . '</TD>
                    <TD align="center">&nbsp;</TD>
</TR>';
        } else {
            $str.='    <TR>
                   
<TD><input type="Text" name="name_" size="20" placeholder="arabic" value="' . stripslashes($name) . '"/></td>
    ';
            if ($this_seo != "") {
                $str.=' <td><input type="Text" name="' . $this_seo . '" size="20" placeholder="' . $this_seo . '" "/ ></td>';
            }

            if ($this_Link != "") {
                $str.="<td>" . getLinks($link, $this_Link) . "</td>";
            }
            $str.='<TD></TD>
                    
                    <TD align="center">' . print_save_icon("Insert") . '</TD>
                    <TD align="center">&nbsp;</TD>
</TR>';
        }
    }
    $str.='   </table> 
         <div class="cf nestable-lists">
        <div class="dd" id="nestable">
            <ol class="dd-list">';

    //file_put_contents('sss.txt',"aa");
    while ($row = mysql_fetch_array($query)) {
        $id = $row[$this_Id];
        $ord = $row[$this_Order];
        $name = $row[$this_Name];
        $parentId = $row[$this_Pid];
        $active = $row["active"];
        $static = $row["static"];
        $link = $row[$this_Link];
        $seo = $row[$this_seo];
        $str.= '   <li class="dd-item dd3-item" data-id="' . $id . '"  data-order="' . $ord . '">
                           <div class="dd-handle dd3-handle"></div>
                           <div          class="dd3-content">';


        if ($action == "Edit" && $_REQUEST[$this_Id] == $id) {
            $str.='   <div id="' . $id . '">'
                    . ' <input type="Hidden" name="' . $this_Id . '"size="20" value="' . $id . '" />'
                    . ' <div class="right">  &nbsp;&nbsp;&nbsp;   ' . print_delete_ckbox($id) . '</div>'
                    . ' <div class="right">  ' . print_save_icon("Update") . '</div>'
                    . ' <div class="left"> <input type="Text" name="name_" size="20" value="' . $name . '"/>  </div>';
            if ($this_Link != "") {
                $str.=' <div class="left1">' . getLinks($link, $this_Link) . '  </div>';
            }
            if ($this_seo != "") {
                $str.=' <div class="left1"><input type="Text" name="' . $this_seo . '" size="20" value="' . $seo . '"/> </div>';
            }
            $str.='  </div>';
        } else {
            $str.='  <div id="' . $id . '">     <div class="right">';
            if ($static == 0 || $_SESSION["SAdmin"] == 1) {
                $str.="&nbsp;&nbsp;" . print_delete_ckbox($id) . "";
            } else
                $str.="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ";

            $str.=" </div> <div class='right'>&nbsp;&nbsp;" . print_edit_icon($_SERVER['PHP_SELF'] . "?action=Edit&" . $this_Id . "=" . $id . "&" . $this_Pid . "=" . $parentId) . "</div>";
            $str.= " <div class='right'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; " . switcher('checkbox', $typeTable, 'active', $this_Id, $id, $active) . " &nbsp;&nbsp;&nbsp;</div>";
            if ($_SESSION["SAdmin"] == 1) {
                $sw = switcher("checkbox", $typeTable, "static", $this_Id, $id, $static);
                $str.= '<div class="right">';
                $str.=$sw;
                $str.= '</div>';
            }

            if ($this_Link != "") {

                $str.=' <div class="right-link">' . $link . ' </div>';
            }
            if ($this_seo != "") {
                $str.=' <div class="right"> ' . stripslashes($seo) . ' &nbsp;&nbsp;&nbsp;</div>';
            }
            $str.= "<div style='float:left'> " . stripslashes($name) . "</div>";


            $str.= ' </div>';
        }
        $str.= ' </div>';

        $str.= ' <ol class="dd-list">';

        $id = $row[$this_Id];
        $query1 = mysql_query("SELECT * FROM $typeTable WHERE  $this_Pid = '$id' order by $this_Order ");
        while ($row1 = mysql_fetch_array($query1)) {
            $id = $row1[$this_Id];
            $ord = $row1[$this_Order];
            $name = $row1[$this_Name];
            $parentId = $row1[$this_Pid];
            $active = $row1["active"];
            $static = $row1["static"];
            $link = $row1[$this_Link];
            $seo = $row1[$this_seo];

            $str.= '   <li class="dd-item dd3-item" data-id="' . $id . '" data-order="' . $ord . '">
                           <div class="dd-handle dd3-handle"></div>
                           <div          class="dd3-content">';


            if ($action == "Edit" && $_REQUEST[$this_Id] == $id) {
                $str.='   <div id="' . $id . '">'
                        . ' <input type="Hidden" name="' . $this_Id . '"size="20" value="' . $id . '" />'
                        . ' <div class="right">  &nbsp;&nbsp;&nbsp;   ' . print_delete_ckbox($id) . '</div>'
                        . ' <div class="right">  ' . print_save_icon("Update") . '</div>'
                        . ' <div class="left"> <input type="Text" name="name_" size="20" value="' . $name . '"/>  </div>';
                if ($this_Link != "") {
                    $str.=' <div class="left1">' . getLinks($link, $this_Link) . '  </div>';
                }
                if ($this_seo != "") {
                    $str.=' <div class="left1"><input type="Text" name="' . $this_seo . '" size="20" value="' . $seo . '"/> </div>';
                }
                $str.='  </div>';
            } else {
                $str.='  <div id="' . $id . '">     <div class="right">';
                if ($static == 0 || $_SESSION["SAdmin"] == 1) {
                    $str.="&nbsp;&nbsp;" . print_delete_ckbox($id) . "";
                } else
                    $str.="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ";

                $str.=" </div> <div class='right'>&nbsp;&nbsp;" . print_edit_icon($_SERVER['PHP_SELF'] . "?action=Edit&" . $this_Id . "=" . $id . "&" . $this_Pid . "=" . $parentId) . "</div>";
                $str.= " <div class='right'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; " . switcher('checkbox', $typeTable, 'active', $this_Id, $id, $active) . " &nbsp;&nbsp;&nbsp;</div>";
                if ($_SESSION["SAdmin"] == 1) {
                    $sw = switcher("checkbox", $typeTable, "static", $this_Id, $id, $static);
                    $str.= '<div class="right">';
                    $str.=$sw;
                    $str.= '</div>';
                }

                if ($this_Link != "") {
                    $str.=' <div class="right-link"> ' . stripslashes($link) . '</div>';
                }
                if ($this_seo != "") {
                    $str.=' <div class="right"> ' . stripslashes($seo) . ' &nbsp;&nbsp;&nbsp;</div>';
                }
                $str.= "<div style='float:left'> " . stripslashes($name) . "</div>";


                $str.= ' </div>';
            }
            $str.= ' </div>';

            $str.= ' <ol class="dd-list">';

            $id2 = $row1[$this_Id];
            $query2 = mysql_query("SELECT * FROM $typeTable WHERE  $this_Pid = '$id2' order by $this_Order  ");
            while ($row2 = mysql_fetch_array($query2)) {
                $id = $row2[$this_Id];
                $name = $row2[$this_Name];
                $ord = $row2[$this_Order];
                $parentId = $row2[$this_Pid];
                $active = $row2["active"];
                $static = $row2["static"];
                $link = $row2[$this_Link];
                $seo = $row2[$this_seo];
                $str.= '   <li class="dd-item dd3-item" data-id="' . $id . '">
                           <div class="dd-handle dd3-handle"></div>
                           <div          class="dd3-content">';


                if ($action == "Edit" && $_REQUEST[$this_Id] == $id) {
                    $str.='   <div id="' . $id . '">'
                            . ' <input type="Hidden" name="' . $this_Id . '"size="20" value="' . $id . '" />'
                            . ' <div class="right">  &nbsp;&nbsp;&nbsp;   ' . print_delete_ckbox($id) . '</div>'
                            . ' <div class="right">  ' . print_save_icon("Update") . '</div>'
                            . ' <div class="left"> <input type="Text" name="name_" size="20" value="' . $name . '"/>  </div>';
                    if ($this_Link != "") {
                        $str.=' <div class="left1">' . getLinks($link, $this_Link) . '  </div>';
                    }
                    if ($this_seo != "") {
                        $str.=' <div class="left1"><input type="Text" name="' . $this_seo . '" size="20" value="' . $seo . '"/> </div>';
                    }
                    $str.='  </div>';
                } else {
                    $str.='  <div id="' . $id . '">     <div class="right">';
                    if ($static == 0 || $_SESSION["SAdmin"] == 1) {
                        $str.="&nbsp;&nbsp;" . print_delete_ckbox($id) . "";
                    } else
                        $str.="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ";

                    $str.=" </div> <div class='right'>&nbsp;&nbsp;" . print_edit_icon($_SERVER['PHP_SELF'] . "?action=Edit&" . $this_Id . "=" . $id . "&" . $this_Pid . "=" . $parentId) . "</div>";
                    $str.= " <div class='right'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; " . switcher('checkbox', $typeTable, 'active', $this_Id, $id, $active) . " &nbsp;&nbsp;&nbsp;</div>";
                    if ($_SESSION["SAdmin"] == 1) {
                        $sw = switcher("checkbox", $typeTable, "static", $this_Id, $id, $static);
                        $str.= '<div class="right">';
                        $str.=$sw;
                        $str.= '</div>';
                    }

                    if ($this_Link != "") {
                        $str.=' <div class="right-link"> ' . stripslashes($link) . '</div>';
                    }

                    if ($this_seo != "") {
                        $str.=' <div class="right"> ' . stripslashes($seo) . ' &nbsp;&nbsp;&nbsp;</div>';
                    }
                    $str.= "<div style='float:left'> " . stripslashes($name) . "</div>";


                    $str.= ' </div>';
                }
                $str.= ' </div>';

                $str.= ' <ol class="dd-list">';

                $id3 = $row2[$this_Id];
                $query3 = mysql_query("SELECT * FROM $typeTable WHERE  $this_Pid = '$id3' order by $this_Order ");
                while ($row3 = mysql_fetch_array($query3)) {
                    $id = $row3[$this_Id];
                    $name = $row3[$this_Name];
                    $ord = $row3[$this_Order];
                    $parentId = $row3[$this_Pid];
                    $active = $row3["active"];
                    $static = $row3["static"];
                    $link = $row3[$this_Link];
                    $seo = $row3[$this_seo];
                    $str.= '   <li class="dd-item dd3-item" data-id="' . $id . '" data-order="' . $ord . '">
                           <div class="dd-handle dd3-handle"></div>
                           <div          class="dd3-content">';


                    if ($action == "Edit" && $_REQUEST[$this_Id] == $id) {
                        $str.='   <div id="' . $id . '">'
                                . ' <input type="Hidden" name="' . $this_Id . '"size="20" value="' . $id . '" />'
                                . ' <div class="right">  &nbsp;&nbsp;&nbsp;   ' . print_delete_ckbox($id) . '</div>'
                                . ' <div class="right">  ' . print_save_icon("Update") . '</div>'
                                . ' <div class="left"> <input type="Text" name="name_" size="20" value="' . $name . '"/>  </div>';
                        if ($this_Link != "") {
                            $str.=' <div class="left1">' . getLinks($link, $this_Link) . '  </div>';
                        }
                        $str.='  </div>';
                    } else {
                        $str.='  <div id="' . $id . '">     <div class="right">';
                        if ($static == 0 || $_SESSION["SAdmin"] == 1) {
                            $str.="&nbsp;&nbsp;" . print_delete_ckbox($id) . "";
                        } else
                            $str.="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ";

                        $str.=" </div> <div class='right'>&nbsp;&nbsp;" . print_edit_icon($_SERVER['PHP_SELF'] . "?action=Edit&" . $this_Id . "=" . $id . "&" . $this_Pid . "=" . $parentId) . "</div>";
                        $str.= " <div class='right'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; " . switcher('checkbox', $typeTable, 'active', $this_Id, $id, $active) . " &nbsp;&nbsp;&nbsp;</div>";
                        if ($_SESSION["SAdmin"] == 1) {
                            $sw = switcher("checkbox", $typeTable, "static", $this_Id, $id, $static);
                            $str.= '<div class="right">';
                            $str.=$sw;
                            $str.= '</div>';
                        }

                        if ($this_Link != "") {
                            $str.=' <div class="right-link"> ' . stripslashes($link) . '</div>';
                        }
                        if ($this_seo != "") {
                            $str.=' <div class="right"> ' . stripslashes($seo) . ' &nbsp;&nbsp;&nbsp;</div>';
                        }
                        $str.= "<div style='float:left'> " . stripslashes($name) . "</div>";


                        $str.= ' </div>';
                    }
                    $str.= ' </div>';

                    $str.= ' <ol class="dd-list">';
                    $id4 = $row3[$this_Id];
                    $query4 = mysql_query("SELECT * FROM $typeTable WHERE  $this_Pid = '$id4' order by $this_Order ");
                    while ($row4 = mysql_fetch_array($query4)) {
                        $id = $row4[$this_Id];
                        $name = $row4[$this_Name];
                        $ord = $row4[$this_Order];
                        $parentId = $row4[$this_Pid];
                        $active = $row4["active"];
                        $static = $row4["static"];
                        $link = $row4[$this_Link];
                        $seo = $row4[$this_seo];
                        $str.= '   <li class="dd-item dd3-item" data-id="' . $id . '" data-order="' . $ord . '">
                           <div class="dd-handle dd3-handle"></div>
                           <div          class="dd3-content">';


                        if ($action == "Edit" && $_REQUEST[$this_Id] == $id) {
                            $str.='   <div id="' . $id . '">'
                                    . ' <input type="Hidden" name="' . $this_Id . '"size="20" value="' . $id . '" />'
                                    . ' <div class="right">  &nbsp;&nbsp;&nbsp;   ' . print_delete_ckbox($id) . '</div>'
                                    . ' <div class="right">  ' . print_save_icon("Update") . '</div>'
                                    . ' <div class="left"> <input type="Text" name="name_" size="20" value="' . $name . '"/>  </div>';
                            if ($this_Link != "") {
                                $str.=' <div class="left1">' . getLinks($link, $this_Link) . '  </div>';
                            }
                            if ($this_seo != "") {
                                $str.=' <div class="left1"><input type="Text" name="' . $this_seo . '" size="20" value="' . $seo . '"/> </div>';
                            }
                            $str.='  </div>';
                        } else {
                            $str.='  <div id="' . $id . '">     <div class="right">';
                            if ($static == 0 || $_SESSION["SAdmin"] == 1) {
                                $str.="&nbsp;&nbsp;" . print_delete_ckbox($id) . "";
                            } else
                                $str.="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ";

                            $str.=" </div> <div class='right'>&nbsp;&nbsp;" . print_edit_icon($_SERVER['PHP_SELF'] . "?action=Edit&" . $this_Id . "=" . $id . "&" . $this_Pid . "=" . $parentId) . "</div>";
                            $str.= " <div class='right'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; " . switcher('checkbox', $typeTable, 'active', $this_Id, $id, $active) . " &nbsp;&nbsp;&nbsp;</div>";
                            if ($_SESSION["SAdmin"] == 1) {
                                $sw = switcher("checkbox", $typeTable, "static", $this_Id, $id, $static);
                                $str.= '<div class="right">';
                                $str.=$sw;
                                $str.= '</div>';
                            }

                            if ($this_Link != "") {
                                $str.=' <div class="right-link"> ' . stripslashes($link) . '</div>';
                            }
                            if ($this_seo != "") {
                                $str.=' <div class="right"> ' . stripslashes($seo) . ' &nbsp;&nbsp;&nbsp;</div>';
                            }
                            $str.= "<div style='float:left'> " . stripslashes($name) . "</div>";


                            $str.= ' </div>';
                        }
                        $str.= ' </div>';


                        $str.= ' <ol class="dd-list">';
                        $id5 = $row4[$this_Id];
                        $query5 = mysql_query("SELECT * FROM $typeTable WHERE  $this_Pid = '$id5' order by $this_Order ");
                        while ($row5 = mysql_fetch_array($query5)) {

                            $id = $row5[$this_Id];
                            $name = $row5[$this_Name];
                            $ord = $row5[$this_Order];
                            $parentId = $row5[$this_Pid];
                            $active = $row5["active"];
                            $static = $row5["static"];
                            $link = $row5[$this_Link];
                            $seo = $row5[$this_seo];
                            $str.= '   <li class="dd-item dd3-item" data-id="' . $id . '" data-order="' . $ord . '">
                           <div class="dd-handle dd3-handle"></div>
                           <div          class="dd3-content">';


                            if ($action == "Edit" && $_REQUEST[$this_Id] == $id) {
                                $str.='   <div id="' . $id . '">'
                                        . ' <input type="Hidden" name="' . $this_Id . '"size="20" value="' . $id . '" />'
                                        . ' <div class="right">  &nbsp;&nbsp;&nbsp;   ' . print_delete_ckbox($id) . '</div>'
                                        . ' <div class="right">  ' . print_save_icon("Update") . '</div>'
                                        . ' <div class="left"> <input type="Text" name="name_" size="20" value="' . $name . '"/>  </div>';
                                if ($this_Link != "") {
                                    $str.=' <div class="left1">' . getLinks($link, $this_Link) . '  </div>';
                                }
                                if ($this_seo != "") {
                                    $str.=' <div class="left1"><input type="Text" name="' . $this_seo . '" size="20" value="' . $seo . '"/> </div>';
                                }
                                $str.='  </div>';
                            } else {
                                $str.='  <div id="' . $id . '">     <div class="right">';
                                if ($static == 0 || $_SESSION["SAdmin"] == 1) {
                                    $str.="&nbsp;&nbsp;" . print_delete_ckbox($id) . "";
                                } else
                                    $str.="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ";

                                $str.=" </div> <div class='right'>&nbsp;&nbsp;" . print_edit_icon($_SERVER['PHP_SELF'] . "?action=Edit&" . $this_Id . "=" . $id . "&" . $this_Pid . "=" . $parentId) . "</div>";
                                $str.= " <div class='right'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; " . switcher('checkbox', $typeTable, 'active', $this_Id, $id, $active) . " &nbsp;&nbsp;&nbsp;</div>";
                                if ($_SESSION["SAdmin"] == 1) {
                                    $sw = switcher("checkbox", $typeTable, "static", $this_Id, $id, $static);
                                    $str.= '<div class="right">';
                                    $str.=$sw;
                                    $str.= '</div>';
                                }

                                if ($this_Link != "") {
                                    $str.=' <div class="right-link"> ' . stripslashes($link) . '</div>';
                                }
                                if ($this_seo != "") {
                                    $str.=' <div class="right"> ' . stripslashes($seo) . ' &nbsp;&nbsp;&nbsp;</div>';
                                }
                                $str.= "<div style='float:left'> " . stripslashes($name) . "</div>";


                                $str.= ' </div>';
                            }
                            $str.= ' </div>';


                            $str.= ' <ol class="dd-list">';
                            $id6 = $row5[$this_Id];
                            $query6 = mysql_query("SELECT * FROM $typeTable WHERE  $this_Pid = '$id6' order by $this_Order  ");
                            while ($row6 = mysql_fetch_array($query6)) {
                                $id = $row6[$this_Id];
                                $name = $row6[$this_Name];
                                $ord = $row6[$this_Order];
                                $parentId = $row6[$this_Pid];
                                $active = $row6["active"];
                                $static = $row6["static"];
                                $link = $row6[$this_Link];
                                $seo = $row6[$this_seo];
                                $str.= '   <li class="dd-item dd3-item" data-id="' . $id . '" data-order="' . $ord . '">
                           <div class="dd-handle dd3-handle"></div>
                           <div          class="dd3-content">';


                                if ($action == "Edit" && $_REQUEST[$this_Id] == $id) {
                                    $str.='   <div id="' . $id . '">'
                                            . ' <input type="Hidden" name="' . $this_Id . '"size="20" value="' . $id . '" />'
                                            . ' <div class="right">  &nbsp;&nbsp;&nbsp;   ' . print_delete_ckbox($id) . '</div>'
                                            . ' <div class="right">  ' . print_save_icon("Update") . '</div>'
                                            . ' <div class="left"> <input type="Text" name="name_" size="20" value="' . $name . '"/>  </div>';
                                    if ($this_Link != "") {
                                        $str.=' <div class="left1">' . getLinks($link, $this_Link) . '  </div>';
                                    }
                                    if ($this_seo != "") {
                                        $str.=' <div class="left1"><input type="Text" name="' . $this_seo . '" size="20" value="' . $seo . '"/> </div>';
                                    }
                                    $str.='  </div>';
                                } else {
                                    $str.='  <div id="' . $id . '">     <div class="right">';
                                    if ($static == 0 || $_SESSION["SAdmin"] == 1) {
                                        $str.="&nbsp;&nbsp;" . print_delete_ckbox($id) . "";
                                    } else
                                        $str.="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ";

                                    $str.=" </div> <div class='right'>&nbsp;&nbsp;" . print_edit_icon($_SERVER['PHP_SELF'] . "?action=Edit&" . $this_Id . "=" . $id . "&" . $this_Pid . "=" . $parentId) . "</div>";
                                    $str.= " <div class='right'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; " . switcher('checkbox', $typeTable, 'active', $this_Id, $id, $active) . " &nbsp;&nbsp;&nbsp;</div>";
                                    if ($_SESSION["SAdmin"] == 1) {
                                        $sw = switcher("checkbox", $typeTable, "static", $this_Id, $id, $static);
                                        $str.= '<div class="right">';
                                        $str.=$sw;
                                        $str.= '</div>';
                                    }
                                    if ($this_Link != "") {
                                        $str.=' <div class="right-link"> ' . stripslashes($link) . '</div>';
                                    }
                                    if ($this_seo != "") {
                                        $str.=' <div class="right"> ' . stripslashes($seo) . ' &nbsp;&nbsp;&nbsp;</div>';
                                    }
                                    $str.= "<div style='float:left'> " . stripslashes($name) . "</div>";


                                    $str.= ' </div>';
                                }
                                $str.= ' </div>';

                                $str.= ' <ol class="dd-list">';
                                $id7 = $row6[$this_Id];
                                $query7 = mysql_query("SELECT * FROM $typeTable WHERE  $this_Pid = '$id7' order by $this_Order  ");
                                while ($row7 = mysql_fetch_array($query7)) {
                                    $id = $row7[$this_Id];
                                    $name = $row7[$this_Name];
                                    $ord = $row7[$this_Order];
                                    $parentId = $row7[$this_Pid];
                                    $active = $row7["active"];
                                    $static = $row7["static"];
                                    $link = $row7[$this_Link];
                                    $seo = $row7[$this_seo];
                                    $str.= '   <li class="dd-item dd3-item" data-id="' . $id . '" data-order="' . $ord . '">
                           <div class="dd-handle dd3-handle"></div>
                           <div          class="dd3-content">';


                                    if ($action == "Edit" && $_REQUEST[$this_Id] == $id) {
                                        $str.='   <div id="' . $id . '">'
                                                . ' <input type="Hidden" name="' . $this_Id . '"size="20" value="' . $id . '" />'
                                                . ' <div class="right">  &nbsp;&nbsp;&nbsp;   ' . print_delete_ckbox($id) . '</div>'
                                                . ' <div class="right">  ' . print_save_icon("Update") . '</div>'
                                                . ' <div class="left"> <input type="Text" name="name_" size="20" value="' . $name . '"/>  </div>';
                                        if ($this_Link != "") {
                                            $str.=' <div class="left1">' . getLinks($link, $this_Link) . '  </div>';
                                        }
                                        if ($this_seo != "") {
                                            $str.=' <div class="left1"><input type="Text" name="' . $this_seo . '" size="20" value="' . $seo . '"/> </div>';
                                        }
                                        $str.='  </div>';
                                    } else {
                                        $str.='  <div id="' . $id . '">     <div class="right">';
                                        if ($static == 0 || $_SESSION["SAdmin"] == 1) {
                                            $str.="&nbsp;&nbsp;" . print_delete_ckbox($id) . "";
                                        } else
                                            $str.="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ";

                                        $str.=" </div> <div class='right'>&nbsp;&nbsp;" . print_edit_icon($_SERVER['PHP_SELF'] . "?action=Edit&" . $this_Id . "=" . $id . "&" . $this_Pid . "=" . $parentId) . "</div>";
                                        $str.= " <div class='right'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; " . switcher('checkbox', $typeTable, 'active', $this_Id, $id, $active) . " &nbsp;&nbsp;&nbsp;</div>";
                                        if ($_SESSION["SAdmin"] == 1) {
                                            $sw = switcher("checkbox", $typeTable, "static", $this_Id, $id, $static);
                                            $str.= '<div class="right">';
                                            $str.=$sw;
                                            $str.= '</div>';
                                        }

                                        if ($this_Link != "") {
                                            $str.=' <div class="right-link"> ' . stripslashes($link) . '</div>';
                                        }

                                        if ($this_seo != "") {
                                            $str.=' <div class="right"> ' . stripslashes($seo) . ' &nbsp;&nbsp;&nbsp;</div>';
                                        }
                                        $str.= "<div style='float:left'> " . stripslashes($name) . "</div>";


                                        $str.= ' </div>';
                                    }
                                    $str.= ' </div>';


                                    $str.= ' <ol class="dd-list">';
                                    $id8 = $row7[$this_Id];
                                    $query8 = mysql_query("SELECT * FROM $typeTable WHERE  $this_Pid = '$id8' order by $this_Order  ");
                                    while ($row8 = mysql_fetch_array($query8)) {
                                        $id = $row8[$this_Id];
                                        $ord = $row8[$this_Order];
                                        $name = $row8[$this_Name];
                                        $parentId = $row8[$this_Pid];
                                        $active = $row8["active"];
                                        $static = $row8["static"];
                                        $link = $row8[$this_Link];
                                        $seo = $row8[$this_seo];
                                        $str.= '   <li class="dd-item dd3-item" data-id="' . $id . '" data-order="' . $ord . '">
                           <div class="dd-handle dd3-handle"></div>
                           <div          class="dd3-content">';


                                        if ($action == "Edit" && $_REQUEST[$this_Id] == $id) {
                                            $str.='   <div id="' . $id . '">'
                                                    . ' <input type="Hidden" name="' . $this_Id . '"size="20" value="' . $id . '" />'
                                                    . ' <div class="right">  &nbsp;&nbsp;&nbsp;   ' . print_delete_ckbox($id) . '</div>'
                                                    . ' <div class="right">  ' . print_save_icon("Update") . '</div>'
                                                    . ' <div class="left"> <input type="Text" name="name_" size="20" value="' . $name . '"/>  </div>';
                                            if ($this_Link != "") {
                                                $str.=' <div class="left1">' . getLinks($link, $this_Link) . '  </div>';
                                            }
                                            if ($this_seo != "") {
                                                $str.=' <div class="left1"><input type="Text" name="' . $this_seo . '" size="20" value="' . $seo . '"/> </div>';
                                            }
                                            $str.='  </div>';
                                        } else {
                                            $str.='  <div id="' . $id . '">     <div class="right">';
                                            if ($static == 0 || $_SESSION["SAdmin"] == 1) {
                                                $str.="&nbsp;&nbsp;" . print_delete_ckbox($id) . "";
                                            } else
                                                $str.="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ";

                                            $str.=" </div> <div class='right'>&nbsp;&nbsp;" . print_edit_icon($_SERVER['PHP_SELF'] . "?action=Edit&" . $this_Id . "=" . $id . "&" . $this_Pid . "=" . $parentId) . "</div>";
                                            $str.= " <div class='right'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; " . switcher('checkbox', $typeTable, 'active', $this_Id, $id, $active) . " &nbsp;&nbsp;&nbsp;</div>";
                                            if ($_SESSION["SAdmin"] == 1) {
                                                $sw = switcher("checkbox", $typeTable, "static", $this_Id, $id, $static);
                                                $str.= '<div class="right">';
                                                $str.=$sw;
                                                $str.= '</div>';
                                            }

                                            if ($this_Link != "") {
                                                $str.=' <div class="right-link"> ' . stripslashes($link) . '</div>';
                                            }
                                            if ($this_seo != "") {
                                                $str.=' <div class="right"> ' . stripslashes($seo) . ' &nbsp;&nbsp;&nbsp;</div>';
                                            }
                                            $str.= "<div style='float:left'> " . stripslashes($name) . "</div>";


                                            $str.= ' </div>';
                                        }
                                        $str.= ' </div>';

                                        $str.= ' <ol class="dd-list">';
                                        $id9 = $row8[$this_Id];
                                        $query9 = mysql_query("SELECT * FROM $typeTable WHERE  $this_Pid = '$id9' order by $this_Order  ");
                                        while ($row9 = mysql_fetch_array($query9)) {
                                            $id = $row9[$this_Id];
                                            $ord = $row9[$this_Order];
                                            $name = $row9[$this_Name];
                                            $parentId = $row9[$this_Pid];
                                            $active = $row9["active"];
                                            $static = $row9["static"];
                                            $link = $row9[$this_Link];
                                            $seo = $row9[$this_seo];
                                            $str.= '   <li class="dd-item dd3-item" data-id="' . $id . '" data-order="' . $ord . '">
                           <div class="dd-handle dd3-handle"></div>
                           <div          class="dd3-content">';


                                            if ($action == "Edit" && $_REQUEST[$this_Id] == $id) {
                                                $str.='   <div id="' . $id . '">'
                                                        . ' <input type="Hidden" name="' . $this_Id . '"size="20" value="' . $id . '" />'
                                                        . ' <div class="right">  &nbsp;&nbsp;&nbsp;   ' . print_delete_ckbox($id) . '</div>'
                                                        . ' <div class="right">  ' . print_save_icon("Update") . '</div>'
                                                        . ' <div class="left"> <input type="Text" name="name_" size="20" value="' . $name . '"/>  </div>';
                                                if ($this_Link != "") {
                                                    $str.=' <div class="left1">' . getLinks($link, $this_Link) . '  </div>';
                                                }
                                                if ($this_seo != "") {
                                                    $str.=' <div class="left1"><input type="Text" name="' . $this_seo . '" size="20" value="' . $seo . '"/> </div>';
                                                }
                                                $str.='  </div>';
                                            } else {
                                                $str.='  <div id="' . $id . '">     <div class="right">';
                                                if ($static == 0 || $_SESSION["SAdmin"] == 1) {
                                                    $str.="&nbsp;&nbsp;" . print_delete_ckbox($id) . "";
                                                } else
                                                    $str.="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ";

                                                $str.=" </div> <div class='right'>&nbsp;&nbsp;" . print_edit_icon($_SERVER['PHP_SELF'] . "?action=Edit&college_id=" . $c . "&" . $this_Id . "=" . $id . "&" . $this_Pid . "=" . $parentId) . "</div>";
                                                $str.= " <div class='right'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; " . switcher('checkbox', $typeTable, 'active', $this_Id, $id, $active) . " &nbsp;&nbsp;&nbsp;</div>";
                                                if ($_SESSION["SAdmin"] == 1) {
                                                    $sw = switcher("checkbox", $typeTable, "static", $this_Id, $id, $static);
                                                    $str.= '<div class="right">';
                                                    $str.=$sw;
                                                    $str.= '</div>';
                                                }


                                                if ($this_Link != "") {
                                                    $str.=' <div class="right-link"> ' . stripslashes($link) . '</div>';
                                                }
                                                if ($this_seo != "") {
                                                    $str.=' <div class="right"> ' . stripslashes($seo) . ' &nbsp;&nbsp;&nbsp;</div>';
                                                }

                                                $str.= "<div style='float:left'> " . stripslashes($name) . "</div>";


                                                $str.= ' </div>';
                                            }


                                            $str.= ' </div>

                                                           </li> 
                                                                                                                                ';
                                        }
                                        $str.='</ol>


                                                                                                                        </li> 
                                                                                                                    ';
                                    }
                                    $str.='</ol>


                                                                                                                        </li> 
                                                                                                                    ';
                                }
                                $str.='</ol>


                                                                                                                        </li> 
                                                                                                                    ';
                            }
                            $str.='</ol>


                                                                                                                        </li> 
                                                                                                                    ';
                        }
                        $str.='</ol>


                                                                                                                        </li> 
                                                                                                                    ';
                    }
                    $str.='</ol>


                                                                                                                        </li> 
                                                                                                                    ';
                }
                $str.='</ol>


                                                                                                                        </li> 
                                                                                                                    ';
            }
            $str.='</ol>


                                                                                                                        </li> 
                                                                                                                    ';
        }
        $str.='</ol>


                                                                                                                        </li> 
                                                                                                                    ';
    }
    $str.='</ol>
            </div>

        </div>
    </form>';


    $str.='<form  id="idForm" method="post" >
        <textarea  cols="20" rows="20" style="display:none;" id="nestable-output" name="output_data"></textarea>
        <input type="hidden" name="table" value="' . $typeTable . '"/>
             <input type="hidden" name="parent" value="' . $this_Pid . '"/>
              <input type="hidden" name="item_order" value="' . $this_Order . '"/>   
             <input type="hidden" name="table_id" value="' . $this_Id . '"/>
        <input id="ssss" style="display:none;"  type="submit" name="save_data"> 
        <img src="../common/css/loading.gif" id="img" style="display:none"/>
        <p>&nbsp;</p>';
    $str.=' <script src="../common/js/jquery.nestable.js"  charset="UTF-8"></script>'
            . ''
            . '<script>

            $(document).ready(function()
            {

                var updateOutput = function(e)
                {
                    var list = e.length ? e : $(e.target),
                            output = list.data("output");
                    if (window.JSON) {
                        output.val(window.JSON.stringify(list.nestable("serialize")));
                var url = "../common/newList.php"; // the script where you handle the form input.
                $("#img").show();
                $.ajax({
                    type: "POST",
                    url: url,
                    data: $("#idForm").serialize(),
                    success: function(data)
                    {
                        $("#img").hide();
                    }
                });
                    } else {
                        output.val("JSON browser support required ");
                    }
                    
                };    
                $("#nestable").nestable({
                    group: 1
                })
                        .on("change", updateOutput);

                $("#nestable2").nestable({
                    group: 1
                })
                   .on("change", updateOutput);

                updateOutput($("#nestable").data("output", $("#nestable-output")));
                updateOutput($("#nestable2").data("output", $("#nestable2-output")));

                $("#nestable-$typeTable").on("click", function(e)
                {
                    var target = $(e.target),
                            action = target.data("action");
                    if (action === "expand-all") {
                        $(".dd").nestable("expandAll");
                    }
                    if (action === "collapse-all") {
                        $(".dd").nestable("collapseAll");
                    }

                    if (action === "save-all") {
                        alert("hello");
                    }


                });

                $("#nestable3").nestable();

            });
        </script>
';





    return $str;
}

function nestedSons($str, $row, $ord, $this_Id, $this_Name, $this_Pid, $this_Order, $this_Link, $this_seo) {
    $id = $row[$this_Id];
    $ord = $row[$this_Order];
    $name = $row[$this_Name];
    $parentId = $row[$this_Pid];
    $active = $row["active"];
    $static = $row["static"];
    $link = $row[$this_Link];
    $seo = $row[$this_seo];
    $str.= '   <li class="dd-item dd3-item" data-id="' . $id . '" data-order="' . $ord . '">
                           <div class="dd-handle dd3-handle"></div>
                           <div          class="dd3-content">';


    if ($action == "Edit" && $_REQUEST[$this_Id] == $id) {
        $str.='   <div id="' . $id . '">'
                . ' <input type="Hidden" name="' . $this_Id . '"size="20" value="' . $id . '" />'
                . ' <div class="right">  &nbsp;&nbsp;&nbsp;   ' . print_delete_ckbox($id) . '</div>'
                . ' <div class="right">  ' . print_save_icon("Update") . '</div>'
                . ' <div class="left"> <input type="Text" name="name_" size="20" value="' . $name . '"/>  </div>';
        if ($this_Link != "") {
            $str.=' <div class="left1">' . getLinks($link, $this_Link) . '  </div>';
        }
        if ($this_seo != "") {
            $str.=' <div class="left1"><input type="Text" name="seo" size="20" value="' . $seo . '"/> </div>';
        }
        $str.='  </div>';
    } else {
        $str.='  <div id="' . $id . '">     <div class="right">';
        if ($static == 0 || $_SESSION["SAdmin"] == 1) {
            $str.="" . print_delete_ckbox($id) . "";
        }

        $str.=" </div> <div class='right'>&nbsp;&nbsp;" . print_edit_icon($_SERVER['PHP_SELF'] . "?action=Edit&college_id=" . $c . "&" . $this_Id . "=" . $id . "&" . $this_Pid . "=" . $parentId) . "</div>";
        $str.= " <div class='right'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; " . switcher('checkbox', $typeTable, 'active', $this_Id, $id, $active) . " &nbsp;&nbsp;&nbsp;</div>";
        if ($_SESSION["SAdmin"] == 1) {
            $sw = switcher("checkbox", $typeTable, "static", $this_Id, $id, $static);
            $str.= '<div class="right">';
            $str.=$sw;
            $str.= '</div>';


            if ($this_Link != "") {
                $str.=' <div class="right"> ' . stripslashes($link) . ' &nbsp;&nbsp;&nbsp;</div>';
            }
            if ($this_seo != "") {
                $str.=' <div class="right"> ' . stripslashes($seo) . ' &nbsp;&nbsp;&nbsp;</div>';
            }

            $str.= "<div style='float:left'> " . stripslashes($name) . "</div>";
        }

        $str.= ' </div>';
    }


    return $str;
}

function print_AdminRecordHeadName($field, $head, $sb, $so, $static_order = 0) {
    if ($static_order)
        return $head;
    $out .= '<a href="' . orderingUrlSuffix() . 'so=' . $so . '&sb=' . $field . '&" style="float: left;
margin-left: 74px;"><b>';
    if ($sb == $field)
        $out .= "<u>$head</u>&nbsp;<img src='" . _PREFICO . $so . ".png' alt='D' border=0>";
    else
        $out .= $head;
    $out .= '</b></a>';
    return $out;
}

function get_qtype_per($user, $type) {
    global $permissins;
    $per = array(0, 0, 0, 0, 0);
    $sql = "select * from s_qtype_permissions where user='$user' and type='$type' limit 1";
    $res = mysql_query($sql);
    $rows = mysql_num_rows($res);
    if ($rows > 0) {
        for ($i = 1; $i < count($per); $i++) {
            $per[$i] = mysql_result($res, 0, $permissins[$i]);
        }
    }
    return $per;
}

function get_qtype_cats($user, $p) {
    global $permissins;
    $arr = array();
    $per = $permissins[$p];
    $sql = "select type from s_qtype_permissions where `user`='$user' and `$per`=1 ";
    $res = mysql_query($sql);
    $rows = mysql_num_rows($res);
    while ($row = mysql_fetch_array($res)) {
        array_push($arr, $row['type']);
    }
    return $arr;
}

function get_col_per($user, $type, $permissins1) {

    $per = array('');

    for ($x = 2; $x <= count($permissins1); $x++) {
        array_push($per, 0);
    }
    //$per = array(0, 0, 0, 0, 0, 0, 0, 0);
    $sql = "select * from col_permissions where user='$user' and type='$type' limit 1";

    $res = mysql_query($sql);
    $rows = mysql_num_rows($res);
    if ($rows > 0) {
        for ($i = 1; $i < count($per); $i++) {
            $per[$i] = mysql_result($res, 0, $permissins1[$i]);
        }
    }

    return $per;
}

function multiSelectFromTable($an, $table, $cond = "", $id_html, $lang = 'en') {

    $out.= '<select id="' . $id_html . '" name="' . $id_html . '[]" multiple="multiple" class="required">';
    $arr = explode(",", $an);
    if ($cond == "") {
        $cond = "id<>0";
    }
    $sq = "select * from $table where $cond";

    $res = mysql_query($sq);
    $out.='<option  type="checkbox" value="0" name="' . $table . ',0"';
    if (in_array('0', $arr))
        $out.="selected";
    $out.=' > Univesity</option>';



    while ($value = mysql_fetch_array($res)) {
        $q_id = $value['id'];

        $out.='<option  type="checkbox" value="' . $value["id"] . '" name="' . $table . ',' . $value["id"] . '"';

        if (in_array($q_id, $arr))
            $out.="selected";
        $out.=' >  ' . $value["name_" . $lang] . '</option>';
        $i++;
    }
    $out.="</select>  ";

    return $out;
}

function multiSelectTypeFromTable($filedName, $an, $table) {




    $out.= '<select id="example-enableFiltering" name="' . $filedName . 'example-enableFiltering" multiple="multiple">';

    $arr = explode(",", $an);
    $sq = "select * from $table $cond";
    $res = mysql_query($sq);

    while ($value = mysql_fetch_array($res)) {
        $q_id = $value['id'];

        $out.='<option   id="type-' . $filedName . '" class="' . $filedName . '" type="checkbox" value="' . $value["id"] . '" name="' . $table . ',' . $value["id"] . '"';

        if (in_array($q_id, $arr))
            $out.="selected";
        $out.=' >  ' . $value["name_ar"] . '</option>';
        $i++;
    }
    $out.="</select>  ";

    return $out;
    $out = '';
}

function get_tags_member_name($table, $array, $value, $col, $as_taged = 0) {
    $sql = "SELECT * FROM $table where `$value` in ($array) order by FIELD(id,$array)";
    $res = mysql_query($sql);
    $num = mysql_num_rows($res);
    $return = '';
    if ($num > 0) {
        $i = 0;
        while ($i < $num) {
            $re = mysql_result($res, $i, $col);
            $id = mysql_result($res, $i, 'id');

            if ($as_taged == 0) {
                $return.=$re;
                if ($i !== $num - 1) {
                    $return.=" <br> ";
                }
            } else {
                $return.="'" . $re . "(" . $id . ")'";
                if ($i !== $num - 1) {
                    $return.=",";
                }
            }
            $i++;
        }
        return $return;
    }
}

function get_council_part($council_id, $lang) {
    $sql = "select * from st_council_part where council_id='$council_id' and active='1' order by `ord` asc";
    $result = mysql_query($sql);
    $num = mysql_num_rows($result);
    $return = '';
    $return.="<div id='sortable_rr' style='padding:5px;'>";
    if ($num > 0) {
        $i = 0;

        while ($i < $num) {
            $name = mysql_result($result, $i, 'name_' . $lang);
            $id = mysql_result($result, $i, 'id');
            $tag_member = MYSQL_RESULT($result, $i, "tag_member");
            $ord = mysql_result($result, $i, 'ord');
            $return.="<div id='$id' data-id='$id' class='div_ee' style='padding:5px;width:400px;border:1px solid #666;margin-bottom:5px;cursor: move; opacity: 1;float:left'>";
            $return .="<h5 style='float:left;width:370px'><b>$name</b></h5>"
                    . "<div style='float:right'><img src='../includes/css/images/icons/Edit.png' style='cursor:pointer;' border='0' alt='edit' class='edit_rr' data-id='$id'><br>"
                    . "<img src='../includes/css/images/icons/Delete.png' alt='delete' class='delete_rr' id='delete_rr' data-id='$id' style='cursor:pointer;'  border='0'><br>"
                        . "<img src='../includes/css/images/icons/Drag.png' alt='sort' class='sort_rr' id='delete_rr' data-id='$id' style='cursor:pointer;'  border='0'>"
                    . "</div><br>" . get_tags_member_name('st_members', $tag_member, 'id', 'name_' . $lang);
            $return.="</div>";


            $i++;
        }
    }

    $return.="</div>";
    $return.="<script> 
              var clicked=0;
             var new1=0;
                    $('.edit_rr').click(function(){
                     if(clicked===0 && new1===0){
                     clicked=1;
                    var id=$(this).attr('data-id');
                    $('#sortable_rr #'+id).css('cursor','wait');
$.ajax({url:'get_edit.php',data:{id:id,lang:'$lang'},success:function(data){
    
$('#sortable_rr #'+id).html(data);
    $('#sortable_rr #'+id).css('cursor','default');

}});                    
}
})
                    </script>";
    $return.="<div id='new_rr'>";
    $return .="<span style='width:400px;border:1px solid #666;margin-bottom:5px;float:left;margin-left:5px;text-align:center;cursor:pointer' id='new_part'><img src='../includes/css/images/icons/new.png' alt='Add' border='0'></span>";
    $return.="</div>";
    $return.="   
                
                <script>
                
                 $('#new_part').click(function(){
                 if(new1===0 && clicked===0){
                 new1=1;
$.ajax({
url:'get_new.php',
data:{action:'enter',council_id:'$council_id',lang:'$lang'},
success:function(data){
$('#new_rr').append(data);
}
});  }               
});



$('.sort_rr').click(function(){

$.ajax({
        url:'getSort.php',
        data:{action:'sort',id:$(this).attr('data-id'),council_id:'$council_id',lang:'$lang'},
        success:function(data){
            $('#sorta_rr').html(data);
            }
    });                 
});


$('.delete_rr').click(function(){

$.ajax({
        url:'saveCouncil.php',
        data:{action:'delete',id:$(this).attr('data-id'),council_id:'$council_id',lang:'$lang'},
        success:function(data){
            $('#sorta_rr').html(data);
            }
    });                 
});


                </script>
                ";
    $return.=order_coucile_part(mysql_query($sql), 'id', 'st_council_part', 'ord');


    return $return;
}

function order_coucile_part($sql, $id, $table, $filed) {

    $res = "<script>
	var order_table=\"" . $table . "\";
	var order_filed =\"" . $filed . "\";
	var order_id =\"" . $id . "\";
	ordIds=new Array();";
    $i = 1;

    while ($row = mysql_fetch_array($sql)) {

        $res.="ordIds[" . $i . "]=" . $row[$id] . ";";
        $i++;
    }
    $res.='
       
	$(document).ready(function(){
		
		$("#sortable_rr").sortable({
			connectWith: "div",
			cursor: "move",
			forcePlaceholderSize: true,
			opacity: 0.4,
			stop: function(event, ui){
                

				var orderChanges="";
				var sortorder="";
				var itemorder=0;
				$("#sortable_rr .div_ee").each(function(){
					var columnId=$(this).attr("data-id");
                                        
					itemorder++;
					if(columnId!=ordIds[itemorder]){
						orderChanges+=columnId+","+ordIds[itemorder]+"|";
					}
					ordIds[itemorder]=columnId;
				});
			//alert(orderChanges);
				if(orderChanges!=""){
					$("#sortable_rr").css("cursor","wait");
					$.post("../includes/order.php", {ot:order_table,of:order_filed,oi:order_id, ids:orderChanges} ,function(data){
						$("#sortable_rr").css("cursor","default");
						
					});
				}
			}
		})
	})';
    $res.="</script>";
    return $res;
}

function get_icons($folder, $id_html, $val, $required = 'required') {
    $dir = $folder;


    if (is_dir($dir)) {
        if ($dh = opendir($dir)) {
            $return = "<select name='$id_html' id='$id_html' class='selectpicker'>";
            if ($required != 'required') {
                $return.="<option value=''>--------</option>";
            }
            while (($file = readdir($dh)) !== false) {
                if ($file != '..' && $file != '.') {

                    $class = str_replace('.svg', '', $file);

                    $selected = '';
                    if ($val == "glyph-icon flaticon-$class")
                        $selected = 'selected';

                    $i = "<i class='glyph-icon flaticon-$class'></i>";
                    $return.= '<option value="glyph-icon flaticon-' . $class . '"'
                            . ' data-content="' . $i . '"  ' . $selected . ' >'
                            . ""
                            . "</option>";
                }
            }
            $return.="</select><script>"
                    . ""
                    . "     $('.selectpicker').selectpicker({width:100
    
    });
	 var bool=false;
  $('.bootstrap-select .btn').click(function(){
if(bool===false){
$('.dropdown-menu').css({'height':'200px','display':'block'});bool=true;}
else{
$('.dropdown-menu').css({'height':'200px','display':'none'});bool=false;}
}
);  
	"
                    . "</script>";
            closedir($dh);
        }
        echo $return;
    }
}

function return_news_active($table, $col, $condition) {
    $sql6 = "select * from `$table` where $condition ";
    $res6 = mysql_query($sql6);
    $num6 = mysql_numrows($res6);
    if ($num6 > 0) {
        $return = mysql_result($res6, 0, $col);
        return $return;
    } else {

        return '0';
    }
}

function createComboBox_tow($tables, $id_field, $value_field, $id_value, $field_name, $required = "", $actions = "", $cond = "") {
    $result = '<select name="' . $field_name . '" id="' . $field_name . '" class="' . $required . '" ' . $actions . ' >\n';
    if ($required == '') {
        $result .= "<option></option>\n";
    }
    $arr_tables = explode(',', $tables);
    foreach ($arr_tables as $table) {
        $result.="<optgroup label='$table'>";
        $sql_combo = "SELECT $id_field , $value_field from $table $cond ORDER BY $value_field";
        $def = substr($table, 0, 1);
        $result_combo = MYSQL_QUERY($sql_combo);
        checkError($result_combo, $sql_combo);
        $rows_combo = MYSQL_NUM_ROWS($result_combo);
        $i = 0;
        while ($i < $rows_combo) {
            $id = MYSQL_RESULT($result_combo, $i, $id_field);
            $filds = explode(",", $value_field);
            $ind = 0;
            $value = "";
            while ($ind < count($filds)) {
                $value.= MYSQL_RESULT($result_combo, $i, $filds[$ind]) . " ";
                $ind++;
            }
            $result .= "<option ";
            if ($id_value != null && $id_value != "" && $id_value == $def . $id)
                $result .= "selected ";
            $result .= "value=\"$def$id\">" . stripslashes($value) . "</option>\n";
            $i++;
        }
        $result.="</optgroup>";
    }
    $result .= "</select>\n";
    return $result;
}

function updateAfterDelete($table, $newId) {
    $test = 1;echo $newId;
    $s = "select * from $table ";
    $r = mysql_query($s);
    $numberOfRows = MYSQL_NUMROWS($r);
    $iz = 0;
  $newId = explode(',', $newId);
    while ($iz < $numberOfRows) {
      
        $id = MYSQL_RESULT($r, $iz, "id");
        $arr = MYSQL_RESULT($r, $iz, "collage_id");
        $selected_ids = explode(',', $arr);
        $n = "";
        foreach ($selected_ids as $c) {
            if (!in_array($c, $newId) || $c === '0')
            { $n.=$c . ",";
            
            }else {
                $test = 0;
            }
        }

        if ($test == 0){
            
           $n= str_replace(',,', ',', $n);
            $wq='UPDATE '.$table.' SET `collage_id`="' . $n . '" WHERE id=' . $id;
            mysql_query($wq);
            
        }
        $n = "";
        $test = 1;
        $iz++;
    }
}
