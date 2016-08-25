angular.module('messageDetail').component('messageDetail', {
    templateUrl: 'app/message-detail/message-detail.template.html',
    controller: ['$http', '$routeParams',
        function MessageDetailController($http, $routeParams) {
            var self = this;

            

            $http.get('http://guestbook/ajax.php?act=getMessages').then(function (response) {
                var locUrl = window.location.href;
                var messageId = Number(locUrl.replace(/\D+/g, ""));
                messageId -= 1;
                console.log(messageId);
                self.messages = response.data[messageId];
            });
        }
    ]
});