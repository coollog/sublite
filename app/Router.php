<?php
  interface RouterInterface {
    /**
     * Registers the function $callFunc to the name $callName.
     */
    public static function register($callName, $callFunc);

    /**
     * Routes the $route URI to the function at $callName. $callName must be
     * registered.
     */
    public static function route($route, $callName);

    /**
     * Perform the actual routing after routes are set up.
     */
    public static function run();
  }

  class Router implements RouterInterface {
    public static function register($callName, $callFunc) {
      invariant(is_callable($callFunc));

      self::$callMap[$callName] = $callFunc;
    }

    public static function route($route, $callName) {
      invariant(isset(self::$callMap[$callName]),
        "'$callName' is not a registered callName.");

      self::$routeMap[$route] = $callName;
      self::$routeMap["$route.php"] = $callName;
    }

    public static function run() {
      $uri = self::getUri();

      if (!isset(self::$routeMap[$uri])) {
        // The route isn't registered, so give 404.
        self::routeNotFound();
        return;
      }

      self::setDirpreFromRoute($uri);

      $callName = self::$routeMap[$uri];
      $callFunc = self::$callMap[$callName];

      $callFunc();
    }

    /**
     * 404?
     */
    private static function routeNotFound() {
      echo 'URL invalid.';
    }

    /**
     * Processes the URI into usable routing format.
     */
    private static function getUri() {
      $slicedBase = array_slice(explode('/', $_SERVER['SCRIPT_NAME']), 0, -1);
      $basepath = implode('/', $slicedBase) . '/';
      $uri = substr($_SERVER['REQUEST_URI'], strlen($basepath));

      if (strstr($uri, '?')) $uri = substr($uri, 0, strpos($uri, '?'));

      $uri = '/' . trim($uri, '/');
      return $uri;
    }

    /**
     * Returns the URI parsed into list of routes, each of which is a section
     * split by /. For example, '/foo/bar/cat' would become
     * array('foo', 'bar', 'cat').
     */
    private static function routeStringToArray($uri) {
      $routes = array();

      foreach (explode('/', $uri) as $route) {
        if (trim($route) != '') $routes[] = $route;
      }

      return $routes;
    }

    /**
     * Sets the $GLOBAL[dirpreFromRoute] to be used by paths rendered in HTML so
     * that browsers retrieve files using correct relative path.
     */
    private static function setDirpreFromRoute($uri) {
      $depth = count(self::routeStringToArray($uri));

      $GLOBALS['dirpreFromRoute'] = str_repeat('../', $depth) . 'app/';
    }

    private static $callMap = array();
    private static $routeMap = array();
  }
?>