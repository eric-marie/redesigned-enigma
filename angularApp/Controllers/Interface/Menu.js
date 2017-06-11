angular.module('ProjetXModule').controller('InterfaceMenuCtrl', ['$scope', 'UtilsAuthFactory', 'ApiSecurityFactory', 'UtilsErrorFactory', 'UtilsAjaxLoadingFactory', function ($scope, UtilsAuthFactory, ApiSecurityFactory, UtilsErrorFactory, UtilsAjaxLoadingFactory) {
    $scope.isLoading = UtilsAjaxLoadingFactory.isLoading;
    $scope.loggedIn = UtilsAuthFactory.isLoggedIn;

    $scope.logout = function () {
        ApiSecurityFactory.logout().then(function (response) {
            UtilsAuthFactory.setUser(null);
        }, function (response) {
            UtilsErrorFactory.processError(response);
        });
    };
}]);