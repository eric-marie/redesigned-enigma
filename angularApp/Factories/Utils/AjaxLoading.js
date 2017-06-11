angular.module('ProjetXModule').factory('UtilsAjaxLoadingFactory', ['$location', function ($location) {
    var ajaxLoading = window.ajaxLoading;

    function guid() {
        function s4() {
            return Math.floor((1 + Math.random()) * 0x10000).toString(16).substring(1);
        }

        return s4() + s4() + '-' + s4() + '-' + s4() + '-' + s4() + '-' + s4() + s4() + s4();
    }

    return {
        addLoading: function () {
            var guidValue = guid();
            ajaxLoading.push(guidValue);

            return guidValue;
        },

        removeLoading: function (existingGuid) {
            var index = ajaxLoading.indexOf(existingGuid);
            if (-1 != index) {
                ajaxLoading.splice(index, 1);
            }

            return -1 != index;
        },

        isLoading: function () {
            console.log(ajaxLoading);
            return ajaxLoading.length > 0;
        }
    };
}]);