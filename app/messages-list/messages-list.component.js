// Register `phonelist` component, along with its associated controller and template

angular.module('messagesList').component('messagesList', {
    templateUrl: 'app/messages-list/messages-list.template.html',
    controller: ['$http',
        function MessageListController($http) {
            var self = this;
            self.orderProp = '-age';

            $http.get('http://guestbook/ajax.php?act=getMessages').then(function (response) {
                self.messages = response.data;
            });
        }
    ]
});