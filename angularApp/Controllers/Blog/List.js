angular.module('ProjetXModule').controller('BlogListCtrl', ['$scope', 'ApiSecurityFactory', 'ApiArticleFactory', 'UtilsErrorFactory', 'UtilsAjaxLoadingFactory', function ($scope, ApiSecurityFactory, ApiArticleFactory, UtilsErrorFactory, UtilsAjaxLoadingFactory) {
    ApiSecurityFactory.redirectIfNotLogged(function () {
        $scope.isLoading = UtilsAjaxLoadingFactory.isLoading;
        $scope.articlesList = [];

        var getAllGuid = UtilsAjaxLoadingFactory.addLoading();
        ApiArticleFactory.getAll().then(function (response) {
            $scope.articlesList = response.data;
            UtilsAjaxLoadingFactory.removeLoading(getAllGuid);
        }, function (response) {
            UtilsErrorFactory.processError(response);
            UtilsAjaxLoadingFactory.removeLoading(getAllGuid);
        });
    });
}]);