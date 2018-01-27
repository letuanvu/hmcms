<?php
/** 
 * Tệp tin model của optimize trong admin
 * Vị trí : admin/optimize/optimize_model.php 
 */
if (!defined('BASEPATH'))
    exit('403');
/** Danh sách hình ảnh **/
function delete_unknown_files($dir, $source_files) {
    $ffs = scandir($dir);
    
    unset($ffs[array_search('.', $ffs, true)]);
    unset($ffs[array_search('..', $ffs, true)]);
    
    if (count($ffs) < 1) {
        return;
    }
    echo '<ol>';
    foreach ($ffs as $ff) {
        echo '<li>';
        if (is_dir($dir . '/' . $ff)) {
            echo '<font color="orange">Folder: ' . $dir . '/' . $ff . '</font>';
            delete_unknown_files($dir . '/' . $ff, $source_files);
        } else {
            if (in_array($dir . '/' . $ff, $source_files)) {
                echo '<font color="green">Saved: ' . $dir . '/' . $ff . '</font>';
            } else {
                unlink($dir . '/' . $ff);
                echo '<font color="red">Remove: ' . $dir . '/' . $ff . '</font>';
            }
        }
        echo '</li>';
    }
    echo '</ol>';
}
/** Tối ưu database */
function optimize_database() {
    $hmdb   = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);
    $result = $hmdb->GetTables();
    foreach ($result as $table) {
        $hmdb->Query("OPTIMIZE TABLE `$table`");
        echo '<p>' . $table . '</p>';
    }
}
/** Tối ưu hình ảnh */
function optimize_images() {
    $hmdb      = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);
    $tableName = DB_PREFIX . "media";
    $hmdb->SelectRows($tableName);
    $files        = array();
    $source_files = array();
    while ($row = $hmdb->Row()) {
        $files[] = $row;
    }
    foreach ($files as $file) {
        $file_id     = $file->id;
        $file_info   = $file->file_info;
        $file_info   = json_decode($file_info, TRUE);
        $file_folder = $file->file_folder;
        $file_name   = $file->file_name;
        if ($file_folder != '/') {
            $file_folder_part = '/' . get_media_group_part($file_folder) . '/';
        } else {
            $file_folder_part = '/';
        }
        if (isset($file_info['crop']) AND is_array($file_info['crop'])) {
            $crop_list = $file_info['crop'];
            foreach ($crop_list as $crop_file) {
                $crop_file_name  = $crop_file['name'];
                $crop_file_local = BASEPATH . HM_CONTENT_DIR . '/uploads' . $file_folder_part . $crop_file_name;
                if (file_exists($crop_file_local)) {
                    unlink($crop_file_local);
                    echo '<p><font color="red">Remove: ' . $crop_file_local . '</font></p>';
                }
            }
        }
        $file_info['crop'] = array();
        $valuesArray       = array(
            'file_info' => MySQL::SQLValue(json_encode($file_info))
        );
        $whereArray        = array(
            'id' => MySQL::SQLValue($file_id)
        );
        $hmdb->UpdateRows($tableName, $valuesArray, $whereArray);
        $source_files[] = BASEPATH . HM_CONTENT_DIR . '/uploads' . $file_folder_part . $file_name;
        $source_files[] = BASEPATH . HM_CONTENT_DIR . '/uploads' . $file_folder_part . '.htaccess';
        if (isset($file_info['thumbnail'])) {
            $source_files[] = BASEPATH . HM_CONTENT_DIR . '/uploads' . $file_folder_part . $file_info['thumbnail'];
        }
        
        
    }
    delete_unknown_files(BASEPATH . HM_CONTENT_DIR . '/uploads', $source_files);
}
?>