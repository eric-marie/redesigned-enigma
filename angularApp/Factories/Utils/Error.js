angular.module('ProjetXModule').factory('UtilsErrorFactory', ['$location', 'ApiSecurityFactory', 'UtilsAuthFactory', function ($location, ApiSecurityFactory, UtilsAuthFactory) {
    return {
        processError: function (response, parentPath) {
            parentPath = typeof parentPath !== 'undefined' ? parentPath : '#!/';

            switch (response.status) {
                // case 401:
                case 403:
                    ApiSecurityFactory.isLoggedIn().then(function (response) {
                        if(!response.data.loggedIn) {
                            UtilsAuthFactory.setUser(null);
                        }
                    });
                    break;
                case 404:
                    $location.path(parentPath);
                    break;
            }

            return response.data.errorMessages;
        }
    }
}]);