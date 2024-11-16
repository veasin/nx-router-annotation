<?php
namespace nx\tools;

final class routeHttp{

	private string $path ="";
	private string $controllerSort ="";
	public function __construct($path="./src/route.php"){
		$this->path =realpath($path);
		if(!is_readable($this->path)) throw new \Error("Can't Read $this->path");
	}
	public function build():bool{
		//if(file_exists("route.http")) throw new \Error("route.http exits!");
		$routes =$this->parseRoute($this->path);

		$build =self::class;
		$lines =["# build by $build\n\n"];
		foreach($routes as $route){
			$Method =array_shift($route);
			$Uri =array_shift($route);
			$lines[] ="### $Uri";
			foreach($route as $param){
				$cls =$this->controllerSort.$param[0];
				$r =new \ReflectionMethod($cls, $param[1]);
				$lines[] ="# $this->controllerSort$param[0]->$param[1]()";
				$docC =$r->getDocComment();
				if($docC){
					$docCLines =explode("\n", $docC);
					foreach($docCLines as $line){
						if(trim($line) ==='/**') continue;
						if(trim($line) ==='*/') continue;
						$line =preg_replace("/^\s*\*/", "", $line);
						$lines[]="# $line";
					}
					$lines[]="#";
				}
			}
			$lines[] =strtoupper($Method)." {{host}}$Uri";
			$lines[] ="\n\n";
		}
		return file_put_contents("route.http", implode("\n", $lines));
	}
	private function parseRoute($file){
		$_route_file =explode("\n", file_get_contents($file));
		$this->controllerSort =substr($_route_file[2], 7);

		$routes =include $file;
		return array_filter($routes, function($r){
			if($r[0] ==="*" || $r[1] ==="*") return false;
			if($r[1][strlen($r[1])-1] ==='+') return false;
			return $r;
		});
	}
	static function Make($event):void{
		$args=$event->getArguments();
		$path =getcwd()."/src/route.php";
		$_i_path=array_search('--path', $args);
		$_i_path !== false && $path=$args[$_i_path + 1] ?? getcwd()."/src/route.php";

		echo "route file: ", $path, "\n\n";

		$rH=new self($path);
		$ok=$rH->build();
		echo "\n", $ok ?"done." :"fail.", "\n\n";
	}
}