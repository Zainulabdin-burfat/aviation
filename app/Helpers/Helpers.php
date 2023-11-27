<?php

namespace App\Helpers;

use App\Models\SiteInformation;
use App\Models\Service;
use Buglinjo\LaravelWebp\Facades\Webp;
use Config;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Request;
use PHPMailer\PHPMailer\PHPMailer;

class Helpers
{
    public static function appClasses()
    {

        $data = config('custom.custom');


        // default data array
        $DefaultData = [
            'myLayout' => 'vertical',
            'myTheme' => 'theme-default',
            'myStyle' => 'light',
            'myRTLSupport' => true,
            'myRTLMode' => true,
            'hasCustomizer' => true,
            'showDropdownOnHover' => true,
            'displayCustomizer' => true,
            'contentLayout' => 'compact',
            'headerType' => 'fixed',
            'navbarType' => 'fixed',
            'menuFixed' => true,
            'menuCollapsed' => false,
            'footerFixed' => false,
            'customizerControls' => [
                'rtl',
                'style',
                'headerType',
                'contentLayout',
                'layoutCollapsed',
                'showDropdownOnHover',
                'layoutNavbarOptions',
                'themes',
            ],
            //   'defaultLanguage'=>'en',
        ];

        // if any key missing of array from custom.php file it will be merge and set a default value from dataDefault array and store in data variable
        $data = array_merge($DefaultData, $data);

        // All options available in the template
        $allOptions = [
            'myLayout' => ['vertical', 'horizontal', 'blank', 'front'],
            'menuCollapsed' => [true, false],
            'hasCustomizer' => [true, false],
            'showDropdownOnHover' => [true, false],
            'displayCustomizer' => [true, false],
            'contentLayout' => ['compact', 'wide'],
            'headerType' => ['fixed', 'static'],
            'navbarType' => ['fixed', 'static', 'hidden'],
            'myStyle' => ['light', 'dark', 'system'],
            'myTheme' => ['theme-default', 'theme-bordered', 'theme-semi-dark'],
            'myRTLSupport' => [true, false],
            'myRTLMode' => [true, false],
            'menuFixed' => [true, false],
            'footerFixed' => [true, false],
            'customizerControls' => [],
            // 'defaultLanguage'=>array('en'=>'en','fr'=>'fr','de'=>'de','pt'=>'pt'),
        ];

        //if myLayout value empty or not match with default options in custom.php config file then set a default value
        foreach ($allOptions as $key => $value) {
            if (array_key_exists($key, $DefaultData)) {
                if (gettype($DefaultData[$key]) === gettype($data[$key])) {
                    // data key should be string
                    if (is_string($data[$key])) {
                        // data key should not be empty
                        if (isset($data[$key]) && $data[$key] !== null) {
                            // data key should not be exist inside allOptions array's sub array
                            if (!array_key_exists($data[$key], $value)) {
                                // ensure that passed value should be match with any of allOptions array value
                                $result = array_search($data[$key], $value, 'strict');
                                if (empty($result) && $result !== 0) {
                                    $data[$key] = $DefaultData[$key];
                                }
                            }
                        } else {
                            // if data key not set or
                            $data[$key] = $DefaultData[$key];
                        }
                    }
                } else {
                    $data[$key] = $DefaultData[$key];
                }
            }
        }
        //layout classes
        $layoutClasses = [
            'layout' => $data['myLayout'],
            'theme' => $data['myTheme'],
            'style' => $data['myStyle'],
            'rtlSupport' => $data['myRTLSupport'],
            'rtlMode' => $data['myRTLMode'],
            'textDirection' => $data['myRTLMode'],
            'menuCollapsed' => $data['menuCollapsed'],
            'hasCustomizer' => $data['hasCustomizer'],
            'showDropdownOnHover' => $data['showDropdownOnHover'],
            'displayCustomizer' => $data['displayCustomizer'],
            'contentLayout' => $data['contentLayout'],
            'headerType' => $data['headerType'],
            'navbarType' => $data['navbarType'],
            'menuFixed' => $data['menuFixed'],
            'footerFixed' => $data['footerFixed'],
            'customizerControls' => $data['customizerControls'],
        ];

        // sidebar Collapsed
        if ($layoutClasses['menuCollapsed'] == true) {
            $layoutClasses['menuCollapsed'] = 'layout-menu-collapsed';
        }

        // Header Type
        if ($layoutClasses['headerType'] == 'fixed') {
            $layoutClasses['headerType'] = 'layout-menu-fixed';
        }
        // Navbar Type
        if ($layoutClasses['navbarType'] == 'fixed') {
            $layoutClasses['navbarType'] = 'layout-navbar-fixed';
        } elseif ($layoutClasses['navbarType'] == 'static') {
            $layoutClasses['navbarType'] = '';
        } else {
            $layoutClasses['navbarType'] = 'layout-navbar-hidden';
        }

        // Menu Fixed
        if ($layoutClasses['menuFixed'] == true) {
            $layoutClasses['menuFixed'] = 'layout-menu-fixed';
        }


        // Footer Fixed
        if ($layoutClasses['footerFixed'] == true) {
            $layoutClasses['footerFixed'] = 'layout-footer-fixed';
        }

        // RTL Supported template
        if ($layoutClasses['rtlSupport'] == true) {
            $layoutClasses['rtlSupport'] = '/rtl';
        }

        // RTL Layout/Mode
        if ($layoutClasses['rtlMode'] == true) {
            $layoutClasses['rtlMode'] = 'rtl';
            $layoutClasses['textDirection'] = 'rtl';
        } else {
            $layoutClasses['rtlMode'] = 'ltr';
            $layoutClasses['textDirection'] = 'ltr';
        }

        // Show DropdownOnHover for Horizontal Menu
        if ($layoutClasses['showDropdownOnHover'] == true) {
            $layoutClasses['showDropdownOnHover'] = 'true';
        } else {
            $layoutClasses['showDropdownOnHover'] = 'false';
        }

        // To hide/show display customizer UI, not js
        if ($layoutClasses['displayCustomizer'] == true) {
            $layoutClasses['displayCustomizer'] = 'true';
        } else {
            $layoutClasses['displayCustomizer'] = 'false';
        }

        return $layoutClasses;
    }

    public static function updatePageConfig($pageConfigs)
    {
        $demo = 'custom';
        if (isset($pageConfigs)) {
            if (count($pageConfigs) > 0) {
                foreach ($pageConfigs as $config => $val) {
                    Config::set('custom.' . $demo . '.' . $config, $val);
                }
            }
        }
    }
    public static function mailConf($subject)
    {
        $siteInfo = SiteInformation::first();

        require base_path("vendor/autoload.php");
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->CharSet = 'utf-8';
        $mail->SMTPAuth = true;
        // $mail->SMTPDebug  = 2;
        $mail->SMTPSecure = env('MAIL_ENCRYPTION');
        $mail->Host = env('MAIL_HOST');  //gmail has host > smtp.gmail.com
        $mail->Port = env('MAIL_PORT'); //gmail has port > 587 . without double quotes
        $mail->Username = env('MAIL_USERNAME'); //your username. actually your email
        $mail->Password = env('MAIL_PASSWORD'); // your password. your mail password
        $mail->setFrom(env('MAIL_FROM_ADDRESS'), $siteInfo->email_recipient);
        // $mail->SetFrom = (env('MAIL_FROM_ADDRESS'));
        $mail->Subject = $subject;
        $mail->IsHTML(true);
        return $mail;
    }

    public static function SendServiceEnquiryMail($enquiry)
    {
        $siteInfo = SiteInformation::first();

        if ($siteInfo->logo)
            $logo = url('storage/site_information/logo/') . '/' .$siteInfo->logo;
        else
            $logo = asset('app/dist/img/default-150x150.png');

        $fb_icon = url('assets/web/images/facebook-white.png');
        $insta_icon = url('assets/web/images/instagram-white.png');

        $subject = ($enquiry->subject != NULL) ? $enquiry->subject : 'Service Enquiry';

        $subject = $siteInfo->brand_name . ' - ' . $subject;
        $mail = self::mailConf($subject);
        if ($enquiry->service_id != NULL) {
            $services = service::where('id',$enquiry->service_id)->get()->first();
            $searchArr = [
                "{name}", "{email}", "{phone}", "{service}", "{message}", "{type}", "{site_name}", "{facebook_url}", "{instagram_url}", "{logo}", "{fb_icon}", "{insta_icon}"
            ];
            $replaceArr = [
                $enquiry->name, $enquiry->email, $enquiry->phone, @$services->title, $enquiry->message, ucwords($enquiry->type), $siteInfo->brand_name, $siteInfo->facebook_url, $siteInfo->instagram_url, $logo, $fb_icon, $insta_icon
            ];
            $body = file_get_contents(resource_path('views/content/mail_template/service_enquiry.blade.php'));
        }
        $body = str_replace($searchArr, $replaceArr, $body);
        $mail->MsgHTML($body);
        $mail->addAddress($siteInfo->email, $siteInfo->email_recepient);
        if ($mail->send()) {
            return true;
        } else {
            return false;
        }
    }

    
    public static function sendServiceEnquiryReply($enquiry)
    {
        $siteInfo = SiteInformation::first();

        if ($siteInfo->logo)
            $logo = Request::root() . '/' . $siteInfo->logo;
        else
            $logo = asset('app/dist/img/default-150x150.png');

        $fb_icon = url('assets/web/images/facebook-white.png');
        $insta_icon = url('assets/web/images/instagram-white.png');

        $subject = ($enquiry->subject != NULL) ? $enquiry->subject : 'Request More Info';
        $subject = $siteInfo->brand_name . ' - Request More Info Reply';
        $mail = self::mailConf($subject);
        $searchArr = ["{name}", "{service}", "{message}", "{reply}", "{site_name}", "{facebook_url}", "{instagram_url}", "{logo}", "{fb_icon}", "{insta_icon}"];
        $replaceArr = [$enquiry->name, ($enquiry->service != 0) ? $enquiry->service->title : '', $enquiry->message, $enquiry->reply, $siteInfo->brand_name, $siteInfo->facebook_url, $siteInfo->instagram_url, $logo, $fb_icon, $insta_icon];
        $body = file_get_contents(resource_path('views/mail_template/service_enquiry_reply.blade.php'));
        $body = str_replace($searchArr, $replaceArr, $body);
        $mail->MsgHTML($body);
        $mail->addAddress($enquiry->email, $enquiry->name);
        $mail->send();
        if ($mail) {
            return true;
        } else {
            return false;
        }
    }


    public static function uploadFile($file, $location, $fileName = null)
    {
        if (!File::exists(public_path($location))) {
            mkdir(public_path($location), 0777, true);
        }
        if ($fileName == null) {
            list($name, $ext) = explode('.', $file->getClientOriginalName());
            $fileName = $name;
        }
        $fileName = str_replace(' ', '-', strtolower($fileName));
        $fileName = preg_replace('/[^A-Za-z0-9\-]/', '-', $fileName) . time() . '.' . $file->getClientOriginalExtension();
        $fileName = str_replace('--', '-', $fileName);
        $target = $location . $fileName;
        if (File::exists(public_path($target))) {
            $increment = 0;
            list($name, $ext) = explode('.', $fileName);
            while (File::exists(public_path($target))) {
                $increment++;
                $fileName = $name . '_' . $increment . '.' . $ext;
                $target = $location . $fileName;
            }
        }
        
        // $file->move(public_path($location), $fileName);
        // dd($target,$fileName);
        return $target;
    }
    public static function uploadWebpImage($file, $location, $fileName)
    {


        $fileName = str_replace(' ', '-', strtolower($fileName));
        $fileName = preg_replace('/[^A-Za-z0-9\-]/', '-', $fileName) . time() . '.webp';
        $fileName = str_replace('--', '-', $fileName);

        if (!File::exists(public_path($location))) {
            File::makeDirectory(public_path($location), 0777, true);
        }
        $target = $location . $fileName;
        if (File::exists(public_path($target))) {
            $increment = 0;
            list($name, $ext) = explode('.', $fileName);
            while (File::exists(public_path($target))) {
                $increment++;
                $fileName = $name . '_' . $increment . '.' . $ext;
                $target = $location . $fileName;
            }
        }
        // $ext=$file->extension();
        // if($ext == 'webp')
        // {

        // }
        // else
        // {
        // Webp::make($file)->save(public_path($target));
      
        // }


        return $target;
    }

    
    /**
     * print an image with webp on pages with picture tag.
     *
     * @param Collection $collection The eloquent collection.
     * @param string $field the name of the field.
     * @param string $webpField the name of the webp field.
     * @param string $attributeField the name of the attribute field.
     * @param string $cssClass the css class of the image.
     *
     * @return string html code for printing image on pages.
     */

     public static function printImage($collection, $field, $webpField, $attributeField, $cssClass = null, $cssStyle = null, $height = null, $width = null)
     {
         $imageData = '<picture>';
 
         if (!empty($collection->$webpField) && File::exists(public_path($collection->$webpField))) {
             $imageData .= '<source srcset="' . asset($collection->$webpField) . '" type="image/webp">';
         }
         if (!empty($collection->$field) && File::exists(public_path($collection->$field))) {
             $imageData .= '<img src="' . asset($collection->$field) . '" ';
         } else {
             $imageData .= '<img src="' . asset('web/images/default-image.jpg') . '" alt="Default Image"';
         }
        
         if ($cssClass) {
             $imageData .= ' class="' . $cssClass . '"';
         }
         if ($cssStyle) {
             $imageData .= ' style="' . $cssStyle . '"';
         }
         if ($width) {
             $imageData .= ' width="' . $width .'"';
             
         }
         if ($height) {
             $imageData .= ' height="' . $height . '"';
         }
          if ($attributeField) {
              if(isset($collection->$attributeField)){
                  $imageData .= $collection->$attributeField;
              }else{
                  $imageData .= $attributeField;
              }
             
         }
         $imageData .= ' ></picture>';
 
         return $imageData;
     }
 
}
