angular.module('ProjetXModule').controller('BlogViewCtrl', ['$scope', 'ApiSecurityFactory', 'UtilsAjaxLoadingFactory', function ($scope, ApiSecurityFactory, UtilsAjaxLoadingFactory) {
    ApiSecurityFactory.redirectIfNotLogged(function() {
        $scope.isLoading = UtilsAjaxLoadingFactory.isLoading;

    });
}]);