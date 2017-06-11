angular.module('ProjetXModule').controller('SecurityLoginCtrl', ['$scope', '$location', 'UtilsAuthFactory', 'ApiSecurityFactory', 'UtilsErrorFactory', 'UtilsAjaxLoadingFactory', function ($scope, $location, UtilsAuthFactory, ApiSecurityFactory, UtilsErrorFactory, UtilsAjaxLoadingFactory) {
    ApiSecurityFactory.redirectIfLogged(function () {
        $scope.isLoading = UtilsAjaxLoadingFactory.isLoading;
        $scope.errorMessages = [];

        $scope.user = {
            '_username': '',
            '_password': ''
        };


        $scope.login = function () {
            var loginGuid = UtilsAjaxLoadingFactory.addLoading();
            ApiSecurityFactory.loginCheck($scope.user).then(function (response) {
                $location.path('#!/');
                $scope.errorMessages = [];
                UtilsAuthFactory.setUser(response.data);
                UtilsAjaxLoadingFactory.removeLoading(loginGuid);
            }, function (response) {
                $scope.errorMessages = UtilsErrorFactory.processError(response);
                UtilsAjaxLoadingFactory.removeLoading(loginGuid);
            });
        };
    });
}]);