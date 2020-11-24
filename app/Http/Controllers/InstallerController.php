<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;

class InstallerController extends Controller
{

    public function getIndex()
    {
        return view('installer.index');
    }

    public function createTable($name, $details, $status)
    {
        if ($status == '1') {
            $pr = '<div class="badge badge-success"><i class="fa fa-check"></i> <span>Checked</span></div>';
            return "<tr><td>$name</td><td>$details</td><td>$pr</td></tr>";
        } else {
            $pr = '<div class="badge badge-danger text-white"><i class="fa fa-exclamation-triangle"></i> <span>Warning</span></div>';
            return "<tr class='bg-warning text-white'><td>$name</td><td>$details</td><td>$pr</td></tr>";
        }

    }

    public function extension_check($name)
    {
        if (!extension_loaded($name)) {
            $response = false;
        } else {
            $response = true;
        }
        return $response;
    }

    public function checkServer()
    {

        $text = '';
        $error = 0;
        $extensions = [
            'openssl', 'pdo', 'mbstring', 'tokenizer', 'JSON', 'cURL', 'XML', 'fileinfo'
        ];

        $phpVersion = version_compare(PHP_VERSION, '7.0.0', '>=');

        if ($phpVersion == true) {
            $error = 0;
            $text .= $this->createTable("PHP", "Required PHP version 7.0 or higher", 1);
        } else {
            $error = 1;
            $text .= $this->createTable("PHP", "Required PHP version 7.0 or higher", 0);
        }
        foreach ($extensions as $key) {
            $extension = $this->extension_check($key);
            if ($extension == true) {
                $error = 0;
                $text .= $this->createTable($key, "Required " . strtoupper($key) . " PHP Extension", 1);
            } else {
                $error = 1;
                $text .= $this->createTable($key, "Required " . strtoupper($key) . " PHP Extension", 0);
            }
        }
        $data['text'] = $text;
        $data['error'] = $error;
        return view('installer.server',$data);
    }

    public function folder_permission($name)
    {
        $path = str_replace('../core/','/',$name);

        $path = base_path().$path;

        $perm = substr(sprintf('%o', fileperms($path)), -4);

        if ($perm >= '0755') {
            $response = true;
        } else {
            $response = false;
        }
        return $response;
    }

    public function checkPermission()
    {
        $text = '';
        $error = 0;
        $folders = [
            '../core/bootstrap/cache', '../core/storage', '../core/storage/app', '../core/storage/framework', '../core/storage/logs'
        ];
        foreach ($folders as $key) {
            $folder_perm = $this->folder_permission($key);
            if ($folder_perm == true) {
                $error = 0;
                $text .= $this->createTable(str_replace("../", "", $key), " Required permission: 0755 ", 1);
            } else {
                $error = 1;
                $text .= $this->createTable(str_replace("../", "", $key), " Required permission: 0755 ", 0);
            }
        }
        $envCheck = is_writable(base_path('.env'));
        if ($envCheck == true) {
            $error = 0;
            $text .= $this->createTable('env', " Required .env to be writable", 1);
        } else {
            $error = 1;
            $text .= $this->createTable('env', " Required .env to be writable", 0);
        }
        $database = file_exists(storage_path('database/database.sql'));
        if ($database == true) {
            $error = 0;
            $text .= $this->createTable('Database', "  Database file is available", 1);
        } else {
            $error = 1;
            $text .= $this->createTable('Database', " Database file not available", 0);
        }
        $data['text'] = $text;
        $data['error'] = $error;
        return view('installer.permission',$data);
    }

    public function checkDatabase()
    {
        $error = 0;
        $data['error'] = $error;
        return view('installer.database',$data);
    }

    public function submitDatabase(Request $request)
    {

        try {
            $data['name'] = $request->name;
            $data['host'] = $request->host;
            $data['user'] = $request->username;
            $data['pass'] = $request->password;

            new \PDO("mysql:host=$request->host;dbname=$request->name", $data['user'], $data['pass']);

            Session::push('database',$data);

            return redirect()->route('check-purchase');

        } catch(\PDOException $ex){
            $data['error'] = 1;
            return view('installer.database',$data);
        }
    }

    public function checkPurchase()
    {
        $data['error'] = 0;

//        Session::flush('database');

//        dd(Session::get('database.0.name'));
        return view('installer.purchase',$data);

    }

    public function submitPurchase(Request $request)
    {

        ini_set('max_execution_time',300);

        $request->validate([
           'username' => 'required',
           'code' => 'required',
        ]);

        $code = $request->code;
        $user = $request->username;
        $base_url = url('/');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://softwarezon.com/purchase-verify?code=$code&name=$user&url=$base_url");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)';
        curl_setopt($ch, CURLOPT_USERAGENT, $agent);
        $rr = curl_exec($ch);
        curl_close($ch);

        $status = json_decode($rr);
        if ($status->error == 1) {
            $data['error'] = 1;
            return view('installer.purchase',$data);
        } else {

            $host = Session::get('database.0.host');
            $name = Session::get('database.0.name');
            $user = Session::get('database.0.user');
            $pass = Session::get('database.0.pass');

            $db = new \PDO("mysql:host=$host;dbname=$name", $user, $pass);
            $path = storage_path('database/database.sql');
            $query = file_get_contents($path);
            $stmt = $db->prepare($query);
            $stmt->execute();

            $key = base64_encode(random_bytes(32));
            $env = 'APP_NAME=Laravel
APP_ENV=live
APP_KEY=base64:'.$key.'
APP_DEBUG=true
APP_LOG_LEVEL=debug
APP_MAINTENANCE=false
APP_INSTALL=true
APP_URL='.$base_url.'

DB_CONNECTION=mysql
DB_HOST='.$host.'
DB_PORT=3306
DB_DATABASE='.$name.'
DB_USERNAME='.$user.'
DB_PASSWORD='.$pass.'

BROADCAST_DRIVER=log
CACHE_DRIVER=file
SESSION_DRIVER=file
SESSION_LIFETIME=120
QUEUE_DRIVER=sync

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_DRIVER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_APP_CLUSTER=mt1

PURCHASE_CODE=' . $status->code . '
BUYER_USERNAME=' . $user . '
';
            $envPath = base_path('.env');
            $file = fopen($envPath, 'w');
            fwrite($file, $env);
            fclose($file);
            File::delete(storage_path('database/database.sql'));
            return redirect()->route('install-complete');

        }

    }

    public function installComplete()
    {
        return view('installer.complete');
    }

}
