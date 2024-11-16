# nx-cache-redis
router annotation for nx

> composer require urn2/nx-router-annotation

### to build "./src/route.php":

composer.json 
```json
{
	"scripts":{
		"routeMake":"nx\\tools\\routerAnnotation::Make",
		"route": "@routeMake --sort \\any\\controllers\\ --file /route.php -o"
	}
}
```
args
* --file default:/route.php => ./src/route.php
* --sort default: none  \\any\\controllers\\some => some
* --origin, -o  support nx\parts\router\uri

> composer run-script route

### php
in controller
```php
#[Any("*", 'cors', 'error')]
#[
	Get("/user/d:uid", "get"),
	Post('/user', 'register'),
	Put("/user/d:uid", "put"),
	Patch("/user/d:uid", "patch"),
	Delete('/user/d:uid', 'delete')
]
#[REST("/console/user", "/d:uid", "list,add,get,update,delete")]
class some extends \nx\helpers\controller\model{
	#[Get("/user")]
	public function check():void{}
	#[Delete("/user/d:uid")]
	public function logout(){}
	#[Method("post", "/user", "add", "register")]
	public function register($next, $params){}
}
```