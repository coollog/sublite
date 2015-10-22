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
     * Routes a non-full path such that callName is run with the rest of the
     * requested path.
     * For example, if you want to route /jobs/view/somemongoid, you would
     * call routeTree('/jobs/view', '/jobs/view'), and it will pass
     * ['somemongoid'] when calling the function associated with '/jobs/view'.
     * However, calling routeTree('/jobs', '/jobs/view') will overwrite the
     * previous call.
     */
    public static function routeTree($route, $callName);

    /**
     * Perform the actual routing after routes are set up.
     * Tree routes take precedence over exact routes.
     */
    public static function run();
  }

  class Router implements RouterInterface {
    public static function register($callName, $callFunc) {
      $callName = strtolower($callName);

      invariant(is_callable($callFunc));

      self::$callMap[$callName] = $callFunc;
    }

    public static function route($route, $callName) {
      $route = strtolower($route);
      $callName = strtolower($callName);

      invariant(isset(self::$callMap[$callName]),
        "'$callName' is not a registered callName.");

      self::$routeMap[$route] = $callName;
      self::$routeMap["$route.php"] = $callName;
    }

    public static function routeTree($route, $callName) {
      $route = strtolower($route);
      $callName = strtolower($callName);

      invariant(isset(self::$callMap[$callName]),
        "'$callName' is not a registered callName.");

      $routePath = self::routeStringToArray($route);
      $lastIndex = count($routePath) - 1;
      $curNode = &self::$routeTree;
      foreach ($routePath as $index => $branch) {
        // If is last in $routePath, place the $callName.
        if ($index == $lastIndex) {
          $curNode[$branch] = $callName;
          break;
        }

        // If the branch exists:
        //  If the branch is a leaf, replace with node.
        //  If the branch is a node, continue.
        if (!isset($curNode[$branch]) or !is_array($curNode[$branch])) {
          $curNode[$branch] = [];
        }
        $curNode = &$curNode[$branch];
      }
    }

    public static function run() {
      $uri = self::getUri();

      // First check routeTree.
      $routePath = self::routeStringToArray($uri);
      $curNode = self::$routeTree;
      // Traverse self::$routeTree.
      $routeIndex = 0;
      for ($routeIndex = 0; $routeIndex < count($routePath); $routeIndex ++) {
        $branch = $routePath[$routeIndex];
        if (!isset($curNode[$branch])) {
          break;
        }
        $curNode = $curNode[$branch];
      }
      if (isset($curNode) and !is_array($curNode)) {
        $restOfRoute = array_slice($routePath, $routeIndex);

        $callName = $curNode;
        self::call($uri, $callName, $restOfRoute);
        return;
      }

      if (!isset(self::$routeMap[$uri])) {
        // The route isn't registered, so give 404.
        self::routeNotFound();
        return;
      }

      $callName = self::$routeMap[$uri];
      self::call($uri, $callName);
    }

    private static function call($uri,
                                 $callName,
                                 array $restOfRoute = array()) {
      self::setDirpreFromRoute($uri);

      $callFunc = self::$callMap[$callName];

      $callFunc($restOfRoute);
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
      return strtolower($uri);
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
    private static $routeTree = array();
  }
?>