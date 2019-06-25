<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Models\Admin;
use Illuminate\Support\Facades\Schema;
use App\GII;
use Illuminate\Support\Facades\DB;
class swoole extends Command
{
    protected $Controller;
    protected $model;
    protected $name;
    protected $table;
    protected $config;
    const DIR_SEP = DIRECTORY_SEPARATOR;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'swoole:start {name}{table} {--mark=!}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->table = $this->argument('table');
        if(!Schema::hasTable($this->table)){
            echo "数据库无".$this->table."表";
            return "";
        }
        $this->name = $this->argument('name');
        $this->readController();
        $this->readModel();
//        $this->readConfig();
        $this->initController();
        $this->initModel();
        $this->writeController();
        $this->writeModel();
        
    }
    public function initController(){
        if(empty($this->config)){
            $this->readConfig();
        }
        foreach($this->config as $key => $value) {
            $this->Controller =  preg_replace("{{{".$key."}}}", $value, $this->Controller);
            # code...
        }
        $this->Controller =  preg_replace("{{{Model}}}", $this->name, $this->Controller);
        $this->Controller =  preg_replace("{{{Controller}}}", $this->name, $this->Controller);
        $this->Controller =  preg_replace("{{{Input}}}",$this->rowToFieldString(), $this->Controller);
        // var_dump($this->Controller);
    }
    public function initModel(){
        if(empty($this->config)){
            $this->readConfig();
        }
        foreach($this->config as $key => $value) {
            $this->model =  preg_replace("{{{".$key."}}}", $value, $this->model);
        }
        $this->model =  preg_replace("{{{Model}}}", $this->name, $this->model);
        $this->model =  preg_replace("{{{Table}}}", $this->table, $this->model);
        
        $this->model =  preg_replace("{{{Fillable}}}",$this->rowToFieldString(), $this->model);
        // var_dump($this->model);
        
    }
    public function getRow() {
        $sql = "SHOW FULL COLUMNS FROM `".config("gii.PREFIX").$this->table."`";
        $result = DB::select($sql);
        return $result;
    }
    public function rowToFieldString(){
        $roe = $this->getRow();
        $string = "[";
        foreach ($roe as $key => $value) {
            $string .= "'".$value->Field."',";
        }
        $string =  substr($string,0,-1);
        $string .= "]";
        return $string;
    }
    //获取控制器
    public function readController(){
        $con =  app_path("GII".self::DIR_SEP."Controller.php");
        $myfile = fopen($con, "r");
        $this->Controller =  fread($myfile,filesize($con));
    }
    //获取配置文件
    public function readConfig(){
        $configPath = app_path("GII".self::DIR_SEP."config.php");
        $configFile = fopen($configPath, 'r');
        while (!feof ($configFile)) 
        {
            $buffer  = fgets($configFile, 4096);
            $data = trim($buffer);
            $data = explode('=',$data);
            $this->config[$data[0]] = $data[1];
            
        }
        fclose ($configFile);
    }
    public function readModel(){
        $con =  app_path("GII".self::DIR_SEP."Model.php");
        $myfile = fopen($con, "r");
        $this->model =  fread($myfile,filesize($con));
    }
    public function writeController(){
        $filePath =  config("gii.CONTROLLERPATH").self::DIR_SEP.$this->name."Controller.php";
        if(file_exists($filePath)){
            echo "控制器".$filePath."已存在";
            return "";
        }
        echo $filePath;
        $file = fopen($filePath, "w");
        fwrite($file, $this->Controller);
        echo "控制器已经写入".$filePath;


    }
    public function writeModel(){
        $filePath =  config("gii.MODELPATH").self::DIR_SEP.$this->name.".php";
        if(file_exists($filePath)){
            echo "模型".$filePath."已存在";
            return "";
        }
        $file = fopen($filePath, "w");
        fwrite($file, $this->model);
        echo "模型写入".$filePath."完成";
    }
}
