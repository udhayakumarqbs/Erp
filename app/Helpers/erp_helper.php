<?php

use PhpParser\Node\Expr\List_;
use SebastianBergmann\CodeCoverage\Report\Xml\Unit;
use App\Models\Erp\ErpCompanyInformationModel;
use App\Models\Erp\CustomersModel;
use App\Models\Erp\CustomerContactsModel;

use App\Libraries\SendMailer;


function get_tmp_path()
{
    return FCPATH . "temp/";
}

// html purifier function udhaya kumar
function html_purify($content)
{
    $config = HTMLPurifier_Config::createDefault();
    // Customize configuration settings as needed
    // For example:
    // $config->set('HTML.Allowed', 'p,b,a[href]');

    // Create a new HTMLPurifier instance
    $purifier = new HTMLPurifier($config);

    // Purify the HTML
    return $purifier->purify($content);
}

function get_rand_str($max = 5)
{
    return bin2hex(random_bytes($max)) . time();
}

function get_attachment_path($type)
{
    $path = FCPATH . "uploads/";
    switch ($type) {
        case "lead":
            $path .= "lead/";
            break;
        case "customer":
            $path .= "customer/";
            break;
        case "request":
            $path .= "request/";
            break;
        case "raw_material":
            $path .= "rawmaterial/";
            break;
        case "semi_finished":
            $path .= "semifinished/";
            break;
        case "finished_good":
            $path .= "finishedgood/";
            break;
        case "supplier":
            $path .= "supplier/";
            break;
        case "rfq":
            $path .= "rfq/";
            break;
        case "purchase_invoice":
            $path .= "purchaseinvoice/";
            break;
        case "employee":
            $path .= "employee/";
            break;
        case "contractor":
            $path .= "contractor/";
            break;
        case "inventory_service":
            $path .= "service/";
            break;
        case "property":
            $path .= "property/";
            break;
        case "ticket":
            $path .= "ticket/";
            break;
        case "estimate":
            $path .= "estimate/";
            break;
        case "quotation":
            $path .= "quotation/";
            break;
        case "sale_order":
            $path .= "saleorder/";
            break;
        case "sale_invoice":
            $path .= "saleinvoice/";
            break;
        case "credit_note":
            $path .= "creditnote/";
            break;
        case "team":
            $path .= "team/";
            break;
        case "equipment":
            $path .= "equipment/";
            break;
        case "project":
            $path .= "project/";
            break;
        case "expense":
            $path .= "expense/";
            break;
        case "project_testing":
            $path .= "projecttesting/";
            break;
        case "project_contractor":
            $path .= "projectcontractor/";
            break;
        case "database":
            $path .= "backup_db/";
            break;
        case "contract_Attachment":
            $path .= "contractAttachment/";
            break;
        case "Expenses_Attachment":
            $path .= "ExpenseRecipt/";
            break;
        default:
            break;
    }
    return $path;
}
// List a File in a Directory udhayakumar

function List_files($dir)
{
    $ignored = [
        '.',
        '..',
        '.svn',
        '.htaccess',
        'index.html',
    ];
    $Files = [];
    foreach (scandir($dir) as $file) {
        if (in_array($file, $ignored)) {
            continue;
        }
        $Files[$file] = filectime($dir . '/' . $file);
    }
    asort($Files);
    $Files = array_keys($Files);

    return ($Files) ? $Files : [];
}
// file size getter udhayakumar
function byteToSize($path, $filesize = '')
{
    if (!is_numeric($filesize)) {
        $byte = sprintf('%u', filesize($path));
    } else {
        $byte = $filesize;
    }
    if ($byte > 0) {
        $unit = intval(log($byte, 1024));
        $units = [
            'B',
            'KB',
            'MB',
            'GB'
        ];
        if (array_key_exists($unit, $units) === true) {
            return sprintf('%d %s', $byte / pow(1024, $unit), $units[$unit]);
        }
    }

    return $byte;
}

//Force to download udhayakumar
function force_download($filename = '', $data = '', $set_mime = FALSE)
{
    if ($filename === '' or $data === '') {
        return;
    } elseif ($data === NULL) {
        if (!@is_file($filename) or ($filesize = @filesize($filename)) === FALSE) {
            return;
        }

        $filepath = $filename;
        $filename = explode('/', str_replace('/', '/', $filename));
        $filename = end($filename);
    } else {
        $filesize = strlen($data);
    }

    // Set the default MIME type to send
    $mime = 'application/octet-stream';

    $x = explode('.', $filename);
    $extension = end($x);

    if ($set_mime === TRUE) {
        if (count($x) === 1 or $extension === '') {
            /* If we're going to detect the MIME type,
             * we'll need a file extension.
             */
            return;
        }


        // Only change the default MIME if we can find one
        if (isset($mimes[$extension])) {
            $mime = is_array($mimes[$extension]) ? $mimes[$extension][0] : $mimes[$extension];
        }
    }

    /* It was reported that browsers on Android 2.1 (and possibly older as well)
     * need to have the filename extension upper-cased in order to be able to
     * download it.
     *
     * Reference: http://digiblog.de/2011/04/19/android-and-the-download-file-headers/
     */
    if (count($x) !== 1 && isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/Android\s(1|2\.[01])/', $_SERVER['HTTP_USER_AGENT'])) {
        $x[count($x) - 1] = strtoupper($extension);
        $filename = implode('.', $x);
    }

    if ($data === NULL && ($fp = @fopen($filepath, 'rb')) === FALSE) {
        return;
    }

    // Clean output buffer
    if (ob_get_level() !== 0 && @ob_end_clean() === FALSE) {
        @ob_clean();
    }

    // Generate the server headers
    header('Content-Type: ' . $mime);
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Expires: 0');
    header('Content-Transfer-Encoding: binary');
    header('Content-Length: ' . $filesize);
    header('Cache-Control: private, no-transform, no-store, must-revalidate');

    // If we have raw data - just dump it
    if ($data !== NULL) {
        exit($data);
    }

    // Flush 1MB chunks of data
    while (!feof($fp) && ($data = fread($fp, 1048576)) !== FALSE) {
        echo $data;
    }

    fclose($fp);
    exit;
}


function get_attachment_link($type)
{
    $path = base_url() . "uploads/";
    switch ($type) {
        case "lead":
            $path .= "lead/";
            break;
        case "customer":
            $path .= "customer/";
            break;
        case "request":
            $path .= "request/";
            break;
        case "raw_material":
            $path .= "rawmaterial/";
            break;
        case "semi_finished":
            $path .= "semifinished/";
            break;
        case "finished_good":
            $path .= "finishedgood/";
            break;
        case "supplier":
            $path .= "supplier/";
            break;
        case "rfq":
            $path .= "rfq/";
            break;
        case "purchase_invoice":
            $path .= "purchaseinvoice/";
            break;
        case "employee":
            $path .= "employee/";
            break;
        case "contractor":
            $path .= "contractor/";
            break;
        case "inventory_service":
            $path .= "service/";
            break;
        case "property":
            $path .= "property/";
            break;
        case "ticket":
            $path .= "ticket/";
            break;
        case "estimate":
            $path .= "estimate/";
            break;
        case "quotation":
            $path .= "quotation/";
            break;
        case "sale_order":
            $path .= "saleorder/";
            break;
        case "sale_invoice":
            $path .= "saleinvoice/";
            break;
        case "credit_note":
            $path .= "creditnote/";
            break;
        case "team":
            $path .= "team/";
            break;
        case "equipment":
            $path .= "equipment/";
            break;
        case "project":
            $path .= "project/";
            break;
        case "expense":
            $path .= "expense/";
            break;
        case "project_testing":
            $path .= "projecttesting/";
            break;
        case "project_contractor":
            $path .= "projectcontractor/";
            break;
        case "contract_Attachment":
            $path .= "contractAttachment/";
            break;
        case "Expenses_Attachment":
            $path .= "ExpenseRecipt/";
            break;
        default:
            break;
    }
    return $path;
}

function log_activity($config = array())
{
    $db = \Config\Database::connect();
    $ci = \Config\Services::session();
    $done_by = $ci->get("erp_username");
    $additional_info = $config['additional_info'] ?? '';
    if (!empty($config)) {
        // var_dump($config);
        // exit();
        $query = "INSERT INTO erp_log(title,log_text,ref_link,additional_info,done_by,created_at) VALUES(?,?,?,?,?,?)";
        $db->query($query, array(
            $config['title'],
            $config['log_text'],
            $config['ref_link'],
            $additional_info,
            $done_by,
            date("Y-m-d H:i:s")
        ));
    }
}



function get_user_name()
{
    $ci = \Config\Services::session();
    $username = $ci->get("erp_username");
    if (!empty($username)) {
        return $username;
    } else {
        return "User";
    }
}

function get_user_id()
{
    $ci = \Config\Services::session();
    $userid = $ci->get("erp_userid");
    return $userid;
}

function get_role_name()
{
    $ci = \Config\Services::session();
    if (is_admin()) {
        return "Admin";
    }
    $rolename = $ci->get("erp_rolename");
    if (!empty($rolename)) {
        return $rolename;
    } else {
        return "Anonymous";
    }
}

function is_admin()
{
    $ci = \Config\Services::session();
    $admin = $ci->get("erp_admin");
    if ($admin == '1') {
        return true;
    } else {
        return false;
    }
}

function has_permission($perm)
{
    $ci = \Config\Services::session();
    if ($ci->get("erp_logged")) {
        $admin = $ci->get("erp_admin");
        if ($admin == "1") {
            return true;
        }
        $perms = $ci->get("erp_perms");
        if (in_array($perm, $perms)) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function user_logged()
{
    $db = \Config\Database::connect();
    $ci = \Config\Services::session();
    if ($ci->session->get("erp_logged")) {
        return true;
    } else {
        if (isset($_COOKIE['rhash'])) {
            $rhash = $_COOKIE['rhash'];
            // $result=$db->query("SELECT password,user_id,name,email,is_admin,active,
            // role_name,permissions FROM erp_users LEFT JOIN erp_roles ON 
            // erp_users.role_id=erp_roles.role_id WHERE remember='$rhash' AND 
            // active=1")->result_array();

            $query = $db->table('erp_users');
            $query->select('password, user_id, name, email, is_admin, active, role_name, permissions');
            $query->join('erp_roles', 'erp_users.role_id = erp_roles.role_id', 'LEFT');
            $query->where('remember', $rhash);
            $query->where('active', 1);

            $result = $query->get()->getResultArray();

            if (!empty($result)) {
                $ci->set("erp_username", $result[0]['name']);
                $ci->set("erp_email", $result[0]['email']);
                $ci->set("erp_admin", $result[0]['is_admin']);
                $ci->set("erp_rolename", $result[0]['role_name']);
                $ci->set("erp_userid", $result[0]['user_id']);
                $ci->set("erp_logged", true);
                $perms = array();
                if (!empty($result[0]['permissions'])) {
                    $perms = json_decode($result[0]['permissions'], true);
                }
                $ci->set("erp_perms", $perms);

                $config['title'] = "Login";
                $config['log_text'] = "[ User successfully logged in ]";
                $config['ref_link'] = "";

                log_activity($config);
                return true;
            }
        }
    }
    return false;
}

function is_same_user($id)
{
    $ci = \Config\Services::session();
    $user_id = $ci->session->userdata("erp_userid");
    if ($id == $user_id) {
        return true;
    } else {
        return false;
    }
}


function set_transparency($dest, $src)
{
    $tindex = imagecolortransparent($src);
    $tcolor = array("red" => 255, "green" => 255, "blue" => 255);
    if ($tindex >= 0) {
        $tcolor = imagecolorsforindex($src, $tindex);
    }
    $tindex = imagecolorallocate($dest, $tcolor['red'], $tcolor['green'], $tcolor['blue']);
    imagefill($dest, 0, 0, $tindex);
    imagecolortransparent($dest, $tindex);
}

function resize_image($img_path, $width, $height)
{
    if (!file_exists($img_path)) {
        return false;
    }
    $imginfo = getimagesize($img_path);
    $img_width = $imginfo[0];
    $img_height = $imginfo[1];
    $type = $imginfo[2];
    $src = "";
    $dest = imagecreatetruecolor($width, $height);
    $resized = true;
    switch ($type) {
        case IMAGETYPE_JPEG:
            $src = imagecreatefromjpeg($img_path);
            imagecopyresampled($dest, $src, 0, 0, 0, 0, $width, $height, $img_width, $img_height);
            imagejpeg($dest, $img_path);
            break;
        case IMAGETYPE_PNG:
            $src = imagecreatefrompng($img_path);
            set_transparency($dest, $src);
            imagecopyresampled($dest, $src, 0, 0, 0, 0, $width, $height, $img_width, $img_height);
            imagepng($dest, $img_path);
            break;
        case IMAGETYPE_GIF:
            $src = imagecreatefromgif($img_path);
            imagecopyresampled($dest, $src, 0, 0, 0, 0, $width, $height, $img_width, $img_height);
            imagegif($dest, $img_path);
            break;
        case IMAGETYPE_BMP:
            $src = imagecreatefrombmp($img_path);
            imagecopyresampled($dest, $src, 0, 0, 0, 0, $width, $height, $img_width, $img_height);
            imagebmp($dest, $img_path);
            break;
        case IMAGETYPE_WEBP:
            $src = imagecreatefromwebp($img_path);
            imagecopyresampled($dest, $src, 0, 0, 0, 0, $width, $height, $img_width, $img_height);
            imagewebp($dest, $img_path);
            break;
        case IMAGETYPE_XBM:
            $src = imagecreatefromxbm($img_path);
            imagecopyresampled($dest, $src, 0, 0, 0, 0, $width, $height, $img_width, $img_height);
            imagexbm($dest, $img_path);
            break;
        default:
            $resized = false;
    }
    imagedestroy($dest);
    imagedestroy($src);
    return $resized;
}

function get_next_seq_A($current_col)
{
    $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    if (empty($current_col)) {
        return $chars[0];
    }
    $tmp = $current_col;
    $next_col = array();
    $len = strlen($current_col);
    for ($i = 0; $i < $len; $i++) {
        $next_col[$i] = $current_col[$i];
    }
    $full_z = false;
    for ($i = $len - 1; $i >= 0; $i--) {
        if ($tmp[$i] === 'Z') {
            $next_col[$i] = $chars[0];
            $full_z = true;
        } else {
            $index = strpos($chars, $tmp[$i]);
            $next_col[$i] = $chars[$index + 1];
            $full_z = false;
            break;
        }
    }
    $next_col = implode("", $next_col);
    if ($full_z) {
        $next_col = $chars[0] . $next_col;
    }
    return $next_col;
}

function is_manufacturing_system()
{
    $db = \Config\Database::connect();
    $ci = \Config\Services::session();
    $query = $db->table('erp_settings');
    $query->select('s_value');
    $query->where('s_name', 'system_type');
    $row = $query->get()->getRow();

    $type = isset($row->s_value) ? $row->s_value : null;

    if ($type == "manufacturing") {
        return true;
    }
    return false;
}

function is_construction_system()
{
    $db = \Config\Database::connect();
    $ci = \Config\Services::session();
    $query = $db->table('erp_settings');
    $query->select('s_value');
    $query->where('s_name', 'system_type');
    $row = $query->get()->getRow();

    $type = isset($row->s_value) ? $row->s_value : null;
    if ($type == "construction") {
        return true;
    }
    return false;
}

// Get Username By Id
function get_username_by_id($userid)
{

    $db = \Config\Database::connect();

    $query = $db->table('erp_users');
    $query->select('name');
    $query->select('last_name');
    $query->where('user_id', $userid);
    $result = $query->get()->getResultArray();

    // var_dump($result[0]);
    // echo '<br>';
    return $result[0]['name'] . ' ' . $result[0]['last_name'];
}

// Get Goal Types
function get_goal_types()
{
    $types = [
        [
            'key' => 1,
            'lang_key' => 'Increase Contracts',
            'subtext' => 'Is calculated from the date added to database',
            //'dashboard' => has_permission('contracts', 'view'),
        ],
        [
            'key' => 2,
            'subtext' => '',
            'lang_key' => 'Convert X Leads',
            //'dashboard' => is_staff_member(),
        ],
        [
            'key' => 3,
            'lang_key' => 'Estimates Conversion',
            'subtext' => 'Will be taken only estimates that will be converted to invoices',
            //'dashboard' => has_permission('estimates', 'view'),
        ],
    ];

    return $types;
}

function get_email_settings()
{
    $db = \Config\Database::connect();

    $query = "SELECT s_name,s_value FROM erp_settings";

    $result = $db->query($query)->getResultArray();
    $settting = [];
    foreach ($result as $row) {
        if ($row["s_name"] == "company_logo" || $row["s_name"] == "favicon") {
            continue;
        }
        $settting[$row['s_name']] = $row["s_value"];
    }

    return $settting;
}

//auto password generate

function password_generate($legnth)
{

    $data = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

    for ($i = 0; $i < $legnth; $i++) {
        $n = rand(0, strlen($data));
        $password[$i] = $data[$n];
    }
    return implode($password);

}


/**
 * check the array key and return the value 
 * @auther Ashok kumar
 * @param array $array
 * @return extract array value safely
 */
if (!function_exists('get_array_value')) {

    function get_array_value($array, $key)
    {
        if (is_array($array) && array_key_exists($key, $array)) {
            return $array[$key];
        }
    }
}

/**
 * send mail
 * @auther Ashok kumar
 * @param string $to
 * @param string $subject
 * @param string $message
 * @param array $optoins
 * @return true/false
 */
if (!function_exists('send_app_mail')) {
    function send_app_mail($to, $subject, $message, $optoins = array(), $convert_message_to_html = true)
    {
        $mailer = new SendMailer();

        return $mailer->sendEmail($to, $subject, $message, $optoins, $convert_message_to_html);
    }
}


if (!function_exists('app_lang')) {

    function app_lang($lang = "")
    {
        if (!$lang) {
            return false;
        }

        //first check if the key is exists in custom lang
        $language_result = lang("custom_lang.$lang");
        if ($language_result === "custom_lang.$lang") {
            //this key doesn't exists in custom language, get from default language
            $language_result = lang("default_lang.$lang");
        }

        return $language_result;
    }

}

/**
 * prepare a anchor tag for modal 
 * 
 * @param string $url
 * @param string $title
 * @param array $attributes
 * @return html link of anchor tag
 */
if (!function_exists('modal_anchor')) {

    function modal_anchor($url, $title = '', $attributes = '')
    {
        $attributes["data-act"] = "ajax-modal";
        if (get_array_value($attributes, "data-modal-title")) {
            $attributes["data-title"] = get_array_value($attributes, "data-modal-title");
        } else {
            $attributes["data-title"] = get_array_value($attributes, "title");
        }
        $attributes["data-action-url"] = $url;

        return js_anchor($title, $attributes);
    }

}

/**
 * prepare a anchor tag for any js request
 * 
 * @param string $title
 * @param array $attributes
 * @return html link of anchor tag
 */
if (!function_exists('js_anchor')) {

    function js_anchor($title = '', $attributes = '')
    {
        $title = (string) $title;
        $html_attributes = "";

        if (is_array($attributes)) {
            foreach ($attributes as $key => $value) {
                $html_attributes .= ' ' . $key . '="' . $value . '"';
            }
        }

        return '<a href="#"' . $html_attributes . '>' . $title . '</a>';
    }

}

/**
 * decode html data which submited using a encode method of encodeAjaxPostData() function
 * 
 * @param string $html
 * @return html
 */
if (!function_exists('decode_ajax_post_data')) {

    function decode_ajax_post_data($html)
    {
        $html = str_replace("~", "=", $html);
        $html = str_replace("^", "&", $html);
        return $html;
    }

}


/**
 * add preview on pasted images for rich text editor
 * @param string $text containing text with pasted images
 * @return text with clickable images
 */
if (!function_exists('process_images_from_content')) {

    function process_images_from_content($text = "", $add_preview = true)
    {
        if (!$text) {
            return "";
        }

        preg_match_all('/(<img[^>]+>)/i', $text, $matches);
        $image_tags = get_array_value($matches, 1); //image tags: <img href='' alt=''>

        $images = array();
        if ($image_tags && count($image_tags)) {
            foreach ($image_tags as $key => $image_tag) {

                //get image source url
                preg_match('/src="([^"]*)"/i', $image_tag, $matches);
                $source_url = get_array_value($matches, 1);

                //check if there has already an anchor tag surrounding this img tag
                //we also have to check the pasted-image class because there has static images somewhere like contract editor
                if (strpos($text, '<a href="' . $source_url . '" class="mfp-image"') === false && strpos($image_tag, 'class="pasted-image"') !== false) {
                    //anchor tag not exists and it's a pasted image
                    //get actual file name of image
                    preg_match('/alt="([^"]*)"/i', $image_tag, $matches);
                    $image_file_name = get_array_value($matches, 1);
                    $actual_file_name = remove_file_prefix($image_file_name);

                    //add mfp-image viewer anchor tag
                    $images[] = "<a href='$source_url' class='mfp-image' data-title='" . $actual_file_name . "'>$image_tag</a>";
                } else {
                    //anchor tag exists from before or anchor tag isn't necessary
                    $images[] = $image_tag;
                }
            }
        }

        if ($images) {
            $text = preg_replace_callback('/(<img[^>]+>)/i', function ($image_tags) use (&$images) {
                return array_shift($images);
            }, $text);
        }

        return $text;
    }

}

/**
 * remove file name prefix which was added by move_temp_file() method
 * 
 * @param string $file_name
 * @return filename
 */
if (!function_exists('remove_file_prefix')) {

    function remove_file_prefix($file_name = "")
    {
        return substr($file_name, strpos($file_name, "-") + 1);
    }
}


/**
 * convert a datetime string to date format as defined on settings
 * ex: $date_time = "2015-01-01 23:10:00" will return like this: Today at 23:10 PM
 * @author Ashok kumar | 
 * @param string $date_time .. it will be considered as UTC time.
 * @param string $convert_to_local .. to prevent conversion, pass $convert_to_local=false 
 * @return date
 */
if (!function_exists('format_to_date')) {

    function format_to_date($date_time, $convert_to_local = true)
    {

        if (!$date_time) {
            return "";
        } else {
            //check the date string format is correct
            $date_parts = explode("-", $date_time);
            if (!(get_array_value($date_parts, 0) && get_array_value($date_parts, 1) && get_array_value($date_parts, 2))) {
                return "";
            }
        }


        if ($convert_to_local) {
            $date_time = convert_date_utc_to_local($date_time);
        }

        $target_date = new DateTime($date_time);
        return $target_date->format(get_setting('date_format'));
    }


}

if (!function_exists("get_company_name")) {
    function get_company_name()
    {
        $ErpCompanyInformationModel = new ErpCompanyInformationModel();
        $name = $ErpCompanyInformationModel->company_name();
        if ($name) {
            return $name->company_name;
        } else {
            return "Login";
        }
    }
}
if (!function_exists("get_logo_url")) {
    function get_logo_url()
    {

        $ErpCompanyInformationModel = new ErpCompanyInformationModel();

        $image = $ErpCompanyInformationModel->select("company_logo")->first();
        $url = base_url() . '/uploads/setting/upload_images/' . $image["company_logo"];

        return $url;
    }
}

if (!function_exists("get_client_name")) {
    function get_client_name()
    {
        $CustomerContactsModel = new CustomerContactsModel();

        $id = (int) session('client_cust_id');

        if (!$id) {
            return "Client";
        }

        $client = $CustomerContactsModel->where('cust_id', $id)->where('primary_contact', 1)->get()->getRowArray();

        return $client;

    }
}

if (!function_exists("get_client_profile_url")) {
    function get_client_profile_url()
    {

        $image = get_client_name();
        if ($image) {
            $url = base_url() . '/uploads/customer/' . $image['profile_image'];
            return $url;
        }
    }
}

if( !function_exists("get_client_permission")){
    function get_client_permission(){
        return session('permissions');
    }
}

if(!function_exists("has_client_permission")){
    function has_client_permission($perm){
        $permission = get_client_permission();

        foreach($permission as $val){
            if($perm == $val){
                return true;
            }
        }
        return false;
    }
}