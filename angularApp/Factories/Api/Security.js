angular.module('ProjetXModule').factory('ApiSecurityFactory', ['$http', '$location', 'Routing', 'UtilsAuthFactory', 'UtilsAjaxLoadingFactory', function ($http, $location, Routing, UtilsAuthFactory, UtilsAjaxLoadingFactory) {
    var
        register = function (data) {
            return $http.post(Routing.generate('api_security_register'), data);
        },

        loginCheck = function (data) {
            return $http({
                method: 'post',
                url: Routing.generate('api_security_login_check'),
                transformRequest: function (data) {
                    if (!angular.isObject(data)) {
                        return (data == null) ? '' : data.toString();
                    }
                    var buffer = [];
                    for (var name in data) {
                        if (!data.hasOwnProperty(name)) {
                            continue;
                        }
                        var value = data[name];
                        buffer.push(encodeURIComponent(name) + '=' + encodeURIComponent(( value == null ) ? '' : value));
                    }
                    return buffer.join('&').replace(/%20/g, '+');
                },
                data: data,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            });
        },

        isLoggedIn = function () {
            return $http.get(Routing.generate('api_security_is_logged_in'));
        },

        logout = function () {
            return $http.get(Routing.generate('api_security_logout'));
        },

        redirectIfLogged = function (ifNotLoggedCallback) {
            if (UtilsAuthFactory.isLoggedIn()) $location.path('#!/');
            else ifNotLoggedCallback();
        },

        redirectIfNotLogged = function (ifLoggedCallback) {
            if (!UtilsAuthFactory.isLoggedIn()) $location.path('#!/');
            else ifLoggedCallback();
        };

    return {
        register: register,
        loginCheck: loginCheck,
        logout: logout,
        isLoggedIn: isLoggedIn,
        redirectIfLogged: redirectIfLogged,
        redirectIfNotLogged: redirectIfNotLogged
    }
}]);