angular.module('ProjetXModule').controller('SecurityRegisterCtrl', ['$scope', '$location', 'ApiSecurityFactory', 'UtilsErrorFactory', 'UtilsAjaxLoadingFactory', function ($scope, $location, ApiSecurityFactory, UtilsErrorFactory, UtilsAjaxLoadingFactory) {
    ApiSecurityFactory.redirectIfLogged(function () {
        $scope.isLoading = UtilsAjaxLoadingFactory.isLoading;
        $scope.errorMessages = [];

        $scope.user = {
            'email': '',
            'username': '',
            'password': '',
            'password2': ''
        };

        $scope.register = function () {
            var registerGuid = UtilsAjaxLoadingFactory.addLoading();
            ApiSecurityFactory.register($scope.user).then(function (response) {
                $location.path('#!/');
                $scope.errorMessages = [];
                UtilsAjaxLoadingFactory.removeLoading(registerGuid);
            }, function (response) {
                $scope.errorMessages = UtilsErrorFactory.processError(response);
                UtilsAjaxLoadingFactory.removeLoading(registerGuid);
            });
        };
    });
}]);