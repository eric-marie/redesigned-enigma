angular.module('ProjetXModule').directive('selfRefresh', ['$location', '$route', function ($location, $route) {
    return {
        restrict: 'A',
        link: function ($scope, $element, $attrs) {
            $element.bind('click', function () {
                if ('#!' + $location.$$url == $attrs.href) {
                    $route.reload();
                } else {
                    $location.path($attrs.href);
                }
            });
        }
    };
}]);
