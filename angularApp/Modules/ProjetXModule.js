angular
    .module('ProjetXModule', ['underscore', 'SFRouting', 'ngRoute', 'ui.bootstrap'])
    .config(['$routeProvider', function ($routeProvider) {
        $routeProvider
        // Home
            .when('/', {controller: 'HomeHomeCtrl', templateUrl: '/views/home/home.html'})

            // Security
            .when('/security/register/', {controller: 'SecurityRegisterCtrl', templateUrl: '/views/security/register.html'})
            .when('/security/login/', {controller: 'SecurityLoginCtrl', templateUrl: '/views/security/login.html'})
            .when('/security/logout/', {controller: 'SecurityLogoutCtrl', template: ''})

            // Blog
            .when('/blog/', {controller: 'BlogListCtrl', templateUrl: '/views/blog/list.html'})
            .when('/blog/:article_id/', {controller: 'BlogViewCtrl', templateUrl: '/views/blog/view.html'})
            .when('/blog/:article_id/edit/', {controller: 'BlogEditCtrl', templateUrl: '/views/blog/edit.html'})

            // Fallback
            .otherwise({redirectTo: '/'});
    }]);