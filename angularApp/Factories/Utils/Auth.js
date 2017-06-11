angular.module('ProjetXModule').factory('UtilsAuthFactory', ['$location', function ($location) {
    var user = window.user;

    return {
        getUser: function() {
            return user;
        },

        setUser: function(newUser) {
            user = newUser;
            if (null == user) {
                $location.path('#!/');
            }
        },

        isLoggedIn: function() {
            return null != user;
        }
    };
}]);